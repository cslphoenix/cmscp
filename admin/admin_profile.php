<?php

if ( isset($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_profile',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_profile'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_profile';
	
	include('./pagestart.php');

	add_lang('profile');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_PROFILE;

	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);

	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_profile.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$base = ($settings['smain']['profile_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'move_up', 'move_down', 'delete'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'profile' => array(
						'title1' => 'input_data',
						'profile_name'			=> array('validate' => TYP,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name', 'check' => true),
						'type'					=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('combi', false, 'main')),
						'main'					=> array('validate' => INT,	'explain' => false,	'type' => $base,		'divbox' => true, 'params' => array(false, true, false)),
						'profile_field'			=> array('validate' => TXT,	'explain' => true,	'type' => 'text:25;25',	'divbox' => true, 'required' => array('input_fields', 'type', '1'), 'check' => true, 'prefix' => 'field_'),
						'profile_typ'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:typ',	'divbox' => true, 'params' => array(false, true, false)),
						'profile_lang'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'profile_show_user'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'profile_show_member'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'profile_show_register'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'profile_required'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'profile_order'			=> 'hidden',
					)
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$name = ( isset($_POST['profile_name']) ) ? request('profile_name', TXT) : request('profile_field', TXT);
					$type = ( isset($_POST['profile_name']) ) ? 0 : 1;
					
					$data_sql = array(
						'profile_name'			=> $name,
						'main'					=> $main,
						'type'					=> $type,
						'profile_field'			=> 'field_' . str_replace(' ', '_', strtolower($name)),
						'profile_lang'			=> 0,
						'profile_typ'			=> 0,
						'profile_show_user'		=> 0,
						'profile_show_member'	=> 0,
						'profile_show_register'	=> 0,
						'profile_required'		=> 0,
						'profile_order'			=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(PROFILE, $data, false, 1, true);
					
					debug($data_sql, 'edit');
				}
				else
				{
					$data_sql = build_request(PROFILE, $vars, $error, $mode);
					
					debug($data_sql, 'after');
					
					if ( !$error )
					{
						$current_typ	= request('current_typ', INT);
						$current_field	= request('current_field', TXT);
						
						debug($current_typ);
						
						if ( !$data_sql['type'] )
						{
							$data_sql['main'] = 0;
							$data_sql['profile_field'] = '';
						}
						
						switch ( $data_sql['profile_typ'] )
						{
							case PROFILE_TEXT: $typ = 'VARCHAR(255) NOT NULL'; break;
							case PROFILE_AREA: $typ = 'TEXT NOT NULL'; break;
							case PROFILE_TYPE: $typ = 'TINYINT(1) UNSIGNED NOT NULL'; break;
						}
						
						if ( $mode == 'create' )
						{
							$data_sql['profile_order'] = maxa(PROFILE, 'profile_order', 'main = ' . $data_sql['main']);
							
							if ( $data_sql['type'] )
							{
								sql(PROFILE_DATA, 'alter', array('part' => 'ADD `' . $data_sql['profile_field'] . '`', 'type' => $typ));
							}
							
							$sql = sql(PROFILE, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							if ( $data_sql['type'] == $current_typ && $current_field != $data_sql['profile_field'] )
							{
								sql(PROFILE_DATA, 'alter', array('part' => 'CHANGE `' . $current_field . '` `' . $data_sql['profile_field'] . '`', 'type' => $typ));
							}
							
							$sql = sql(PROFILE, $mode, $data_sql, 'profile_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
							
							orders(PROFILE, $data_sql['type']);
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
					
				}
				
				build_output(PROFILE, $vars, $data_sql);
				
				$fields .= '<input type="hidden" name="current_typ" value="' . $data_sql['type'] . '" />';
				$fields .= '<input type="hidden" name="current_field" value="' . $data_sql['profile_field'] . '" />';

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['profile_name']),

					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
				
			case 'move_up':
			case 'move_down':
			
				move(PROFILE, $mode, $order, $main, $type, $usub);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
			
		#	case 'order_field':
		#		
		#		update(PROFILE, 'profile', $move, $data_id);
		#		orders(PROFILE, $data_type);
		#		
		#		log_add(LOG_ADMIN, $log, $mode);
		#		
		#		$index = true;
		#		
		#		break;
			
//			case 'delete_field':
//			
//				if ( $profile_id && $confirm )
//				{	
//					$profile = get_data('profile', $profile_id, INT);
//
//					$sql = 'DELETE FROM ' . NAVI . " WHERE profile_id = $profile_id";
//					if ( !($result = $db->sql_query($sql)) )
//					{
//						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
//					}
//				
//					log_add(LOG_ADMIN, $log, 'acp_profile_delete', $profile['profile_name']);
//					
//					$message = $lang['delete_profile'] . sprintf($lang['return'], check_sid($file), $acp_title);
//					message(GENERAL_MESSAGE, $message);
//				
//				}
//				else if ( $profile_id && !$confirm )
//				{
//					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
//		
//					$fields = '<input type="hidden" name="mode" value="delete" />';
//					$fields .= '<input type="hidden" name="' . $cat . '" value="' . $profile_id . '" />';
//		
//					$template->assign_vars(array(
//						'MESSAGE_TITLE'		=> $lang['com_confirm'],
//						'MESSAGE_TEXT'		=> $lang['confirm_delete_profile'],
//		
//						'L_YES'				=> $lang['com_yes'],
//						'L_NO'				=> $lang['com_no'],
//		
//						'S_ACTION'	=> check_sid($file),
//						'S_FIELDS'	=> $fields,
//					));
//				}
//				else
//				{
//					message(GENERAL_MESSAGE, $lang['msg_must_select_profile']);
//				}
//				
//				$template->pparse('body');
//				
//				break;
			
			
		}
	
		if ( $index != true )
		{
			acp_footer();
			exit;
		}
	}
	
	if ( $main )
	{
		$template->assign_block_vars('list', array());
		
		$tmp = data(PROFILE, false, 'main ASC, profile_order ASC', 1, false);
		
		$cat = $field = array();
		
		if ( $tmp )
		{
			foreach ( $tmp as $row )
			{
				if ( $row['profile_id'] == $main )
				{
					$cat = $row;
				}
				else if ( $row['main'] == $main )
				{
					$field[] = $row;
				}
			}
			
			$cid = $cat['profile_id'];
		
			$template->assign_vars(array(
				'CAT'		=> href('a_txt', $file, array($file), $lang['acp_overview'], $lang['acp_overview']),
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $cid), $cat['profile_name'], $cat['profile_name']),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'com_delete'),
				
				'S_NAME'	=> "profile_fields[$cid]",
				'S_SUBMIT'	=> "submit_field[$cid]",
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
		}
		
		if ( $field )
		{
			$max = count($field);
			
			foreach ( $field as $row )
			{
				$fid	= $row['profile_id'];
				$order	= $row['profile_order'];
				$name	= isset($lang[$row['profile_name']]) ? $lang[$row['profile_name']] : $row['profile_name'];
				
				$template->assign_block_vars('list.row', array(
					'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $fid), $name, $name),
					
					'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
					
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $fid), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $fid), 'icon_cancel', 'com_delete'),
				));
			}
		}
		else
		{
			$template->assign_block_vars('list.empty', array());
		}
		
		$fields .= '<input type="hidden" name="mode" value="create" />';
		$fields .= '<input type="hidden" name="main" value="' . $main . '" />';
	}
	else
	{
		$template->assign_block_vars('display', array());
		
		$tmp = data(PROFILE, 'WHERE type = 0', 'profile_order ASC', 1, false);
		
		if ( $tmp )
		{
			$cnt = count($tmp);
		
			foreach ( $tmp as $row )
			{
				$id		= $row['profile_id'];
				$name	= ( isset($lang[$row['profile_name']]) ) ? $lang[$row['profile_name']] : $row['profile_name'];
				$order	= $row['profile_order'];
				
				$template->assign_block_vars('display.row', array( 
					'NAME'		=> href('a_txt', $file, array('main' => $id), $name, $name),
					
					'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $order != $cnt )	? href('a_img', $file, array('mode' => 'move_down', 'main' => 0, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
					));
			}
		}
		
		$fields	.= '<input type="hidden" name="mode" value="create" />';
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		'L_NAME'	=> $lang['type_0'],
		
		'L_CREATE_CAT'		=> sprintf($lang['stf_create'], $lang['type_0']),
		'L_CREATE_FIELD'	=> sprintf($lang['stf_create'], $lang['type_1']),
		
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');

	acp_footer();
}

?>