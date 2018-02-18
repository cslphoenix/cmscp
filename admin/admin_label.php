<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_LABEL',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'ADMIN'		=> array('TITLE' => 'ACP_LABEL_ADMIN', 'AUTH' => 'A_LABEL'),
			'FORUM'		=> array('TITLE' => 'ACP_LABEL_FORUM', 'AUTH' => 'A_LABEL'),
			'MOD'		=> array('TITLE' => 'ACP_LABEL_MOD', 'AUTH' => 'A_LABEL'),
			'GALLERY'	=> array('TITLE' => 'ACP_LABEL_GALLERY', 'AUTH' => 'A_LABEL'),
			'DL'		=> array('TITLE' => 'ACP_LABEL_DL', 'AUTH' => 'A_LABEL'),
			'USER'		=> array('TITLE' => 'ACP_LABEL_USER', 'AUTH' => 'A_LABEL'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_LABEL';
	
	include('./pagestart.php');
	
	add_lang(array('label', 'labels'));
	acl_auth('A_LABEL');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_LABEL;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$copy	= request('copy', INT);
	$main	= request('main', TYP);	
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
		
	( $cancel )	? redirect('admin/' . $file) : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
	
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);
	$mode = (in_array($mode, array('create', 'delete', 'move_up', 'move_down', 'update'))) ? $mode : false;
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'label' => array(
					'title'			=> 'INPUT_DATA',
					'label_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'label_desc'	=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:25', 'required' => 'input_desc'),
					'label_type'	=> 'hidden',
					'label_order'	=> 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'label_name'	=> request('label_name', TXT),
					'label_desc'	=> '',
					'label_type'	=> strtoupper($action[0]) . '_',
					'label_order'	=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(ACL_LABEL, $data, false, 1, 'row');
			}
			else
			{
				$data_sql = build_request(ACL_LABEL, $vars, $error, $mode);

				if ( !$error )
				{
					if ( $mode == 'create' )
					{
						$data_sql['label_order'] = _max(ACL_LABEL, 'label_order', false);
						
						$sql = sql(ACL_LABEL, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
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
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
						
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
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['label_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_ACL_USERS'	=> $lang['ACL_USERS'],
				'L_ACL_GROUPS'	=> $lang['ACL_GROUPS'],
				'L_PERMISSION'	=> $lang['COMMON_AUTH'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
		
		case 'delete':

			$data_sql = data(ACL_LABEL, $data, false, 1, 'row');
			
			if ( $data && $accept && $userauth['a_label_delete'] )
			{
				$sql = sql(ACL_LABEL, $mode, $data_sql, 'label_id', $data);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);

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
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['label_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}

			break;
				
		case 'move_up':
		case 'move_down':
		
			move(ACL_LABEL, $mode, $order, false, false, false, false, $type);
			log_add(LOG_ADMIN, $log, $mode);
			
		default:
		
			$template->assign_block_vars('display', array());
			
			$ltypes = strtoupper($action[0]);
						
			$fields = build_fields(array('mode' => 'create'));
			$sqlout = data(ACL_LABEL, 'WHERE label_type = "' . $ltypes . '_"', 'label_order ASC', 1, 0);
			
			if ( !$sqlout )
			{
				$template->assign_block_vars('display.none', array());
				$s_copy = '';
			}
			else
			{
				$cnt = count($sqlout);
				
				$s_copy = '<select name="copy">';
				$s_copy .= '<option value="0">' . $lang['SELECT_COPY'] . '</option>';
				
				foreach ( $sqlout as $row )
				{
					$id		= $row['label_id'];
					$order	= $row['label_order'];
					$name	= langs($row['label_name']);
					$tact	= strtoupper($action[0]) . '_';
					
					$s_copy .= '<option value="' . $id . '">' . langs($name) . '</option>';
					
					$template->assign_block_vars('display.row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
						'DESC'		=> langs($row['label_desc']),
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'type' => $tact, 'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
						'MOVE_DOWN'	=> ( $order != $cnt )	? href('a_img', $file, array('mode' => 'move_down', 'type' => $tact, 'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
			
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
					));
				}
				
				$s_copy .= '</select>&nbsp;';
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				
				'L_NAME'	=> $lang['LABEL'],
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang["TYPE_" . strtoupper($action)]),
				
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