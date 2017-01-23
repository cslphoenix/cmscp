<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_label',
		'cat'		=> 'system',
		'modes'		=> array(
			'admin'		=> array('title' => 'acp_label_admin'),
			'forum'		=> array('title' => 'acp_label_forum'),
			'mod'		=> array('title' => 'acp_label_mod'),
			'gallery'	=> array('title' => 'acp_label_gallery'),
			'dl'		=> array('title' => 'acp_label_dl'),
			'user'		=> array('title' => 'acp_label_user'),
			'new'		=> array('title' => 'acp_label_new'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_label';
	
	include('./pagestart.php');
	
	add_lang(array('label', 'labels'));
	acl_auth(array('a_label', 'a_label_assort', 'a_label_create', 'a_label_delete'));
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_LABEL;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$copy	= request('copy', INT);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$acp_title	= sprintf($lang['stf_header'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_label.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'delete'))) ? $mode : false;
	$_tpl = ($mode == 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'label' => array(
					'title1' => 'input_data',
					'label_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'label_desc'	=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:25', 'required' => 'input_desc'),
					'label_type'	=> 'hidden',
					'label_order'	=> 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'label_name'	=> request('label_name', TXT),
					'label_desc'	=> '',
					'label_type'	=> $action[0] . '_',
					'label_order'	=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(ACL_LABEL, $data, false, 1, true);
			}
			else
			{
				$data_sql = build_request(ACL_LABEL, $vars, $error, $mode);

				if ( !$error )
				{
					if ( $mode == 'create' )
					{
						$data_sql['label_order'] = maxa(ACL_LABEL, 'label_order', false);
						
						$sql = sql(ACL_LABEL, $mode, $data_sql);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						$lid = $db->sql_nextid();
						
						if ( isset($_POST['set']) )
						{
							$sql = 'SELECT * FROM ' . ACL_OPTION . ' WHERE auth_option LIKE "' . $data_sql['label_type'] . '%"';
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							while ( $row = $db->sql_fetchrow($result) )
							{
								$acl_field_name[$row['auth_option']] = $row['auth_option_id'];
							}
							$db->sql_freeresult($result);
							
							foreach ( $_POST['set'] as $name => $value )
							{
								$n_option[] = array(
									'label_id'			=> $lid,
									'auth_option_id'	=> $acl_field_name[$name],
									'auth_value'		=> $value,
								);
							}
							
							foreach ( $n_option as $option )
							{
								$values = array();
								
								foreach ( $option as $key => $row )
								{
									$values[] = (int) $row;
								}
								
								$_option[] = '(' . implode(', ', $values) . ')';
							}
							
							$sql = 'INSERT INTO ' . ACL_LABEL_DATA . ' (' . implode(', ', array_keys($n_option[0])) . ') VALUES ' . implode(', ', $_option);
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}
					else
					{
						$sql = sql(ACL_LABEL, $mode, $data_sql, 'label_id', $data);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						
						if ( isset($_POST['set']) )
						{
							$sql = 'SELECT o.auth_option_id as auth_id, o.auth_option as auth_name
										FROM ' . ACL_OPTION . ' o
											LEFT JOIN ' . ACL_LABEL_DATA . ' d ON o.auth_option_id = d.auth_option_id
										WHERE o.auth_option LIKE "' . $data_sql['label_type'] . '%"
											AND d.label_id = ' . $data;
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							while ( $row = $db->sql_fetchrow($result) )
							{
								$f_data[$row['auth_name']] = $row['auth_id'];
							}
							
							foreach ( $_POST['set'] as $set_name => $set_value )
							{
								/* könnte man noch gruppieren ... */
								$sql = "UPDATE " . ACL_LABEL_DATA . " SET auth_value = $set_value WHERE label_id = $data AND auth_option_id = $f_data[$set_name]";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
						}
					}

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output(ACL_LABEL, $vars, $data_sql);
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));

			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], lang($data_sql['label_name'])),
				'L_EXPLAIN'	=> $lang['com_required'],
				
				'L_ACL_USERS'	=> $lang['acl_users'],
				'L_ACL_GROUPS'	=> $lang['acl_groups'],
				'L_PERMISSION'	=> $lang['common_auth'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
		
		case 'delete':

			$data_sql = data(ACL_LABEL, $data, false, 1, true);
			
			if ( $data && $accept && $userauth['a_label_delete'] )
			{
				$sql = sql(ACL_LABEL, $mode, $data_sql, 'label_id', $data);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

				orders(ACL_LABEL);

				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept && $userauth['a_label_delete'] )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['com_confirm'],
					'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['label_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}

			break;
				
		case 'move_up':
		case 'move_down':
		
			move(ACL_LABEL, $mode, $order, false, false, false, false, $type);
			log_add(LOG_ADMIN, $log, $mode);
			
		default:
		
			$template->assign_block_vars('display', array());
			
			$fields = build_fields(array('mode' => 'create'));
			$sqlout = data(ACL_LABEL, "WHERE label_type = '$action[0]_'", 'label_order ASC', 1, false);
			
			if ( !$sqlout )
			{
				$template->assign_block_vars('display.empty', array());
				$s_copy = '';
			}
			else
			{
				$cnt = count($sqlout);
				
				$s_copy = '<select name="copy">';
				$s_copy .= '<option value="0">' . lang('select_copy') . '</option>';
				
				foreach ( $sqlout as $row )
				{
					$id		= $row['label_id'];
					$order	= $row['label_order'];
					$name	= lang($row['label_name']);
					$tact	= $action[0] . '_';
					
				#	foreach ( $options as $opts )
				#	{
					#	$selected = ( $opts == $type ) ? ' selected="selected"' : '';
					$s_copy .= '<option value="' . $id . '">' . lang($name) . '</option>';
				#	}
		
					$template->assign_block_vars('display.row', array( 
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
						'DESC'		=> lang($row['label_desc']),
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'type' => $tact, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $order != $cnt )	? href('a_img', $file, array('mode' => 'move_down', 'type' => $tact, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
					));
				}
				
				$s_copy .= '</select>&nbsp;';
				
			#	$add_lang = array('label_id' => 0, 'label_name' => 'no_select', 'label_desc' => 'no_select_reset', 'label_type' => $type);
			#	array_unshift($acl_label, $add_lang);
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
				'L_EXPLAIN'	=> $lang['explain'],
				
				'L_NAME'	=> $lang['label'],
				'L_CREATE'	=> sprintf($lang['stf_create'], $lang["type_$action"]),
				
				'S_COPY'	=> $s_copy,
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));		
		
		break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>