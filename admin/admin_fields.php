<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['hm_usergroups']['sm_fields'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_profile';
	
	include('./pagestart.php');

	add_lang('fields');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_FIELDS;
	$url	= POST_FIELDS;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_sub	= request('sub', INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$dir_path	= $root_path . $settings['path_games'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['profile']);
	
	if ( $userdata['user_level'] != ADMIN )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_fields.tpl',
	#	'ajax'		=> 'style/ajax_order.tpl',
	#	'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));

	if ( isset($_POST['add_field']) || isset($_POST['add_cat']) )
	{
		if ( isset($_POST['add_field']) )
		{
			$mode = 'create';
			$cat_id = key($_POST['add_field']);
			$name = request(array('sub_field', $cat_id), TXT);
		}
		else
		{
			$mode = 'create_cat';
			$name = request('field_name', TXT);
		}
	}
#	debug($_POST);
	$mode = ( in_array($mode, array('create', 'update', 'order', 'delete', 'create_cat', 'update_cat', 'order_cat', 'delete_cat')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
			
			#	$template->assign_vars(array('FILE' => 'ajax_order'));
			#	$template->assign_var_from_handle('AJAX', 'ajax');
				
				$vars = array(
					'field' => array(
						'title1' => 'input_data',
						'field_name'			=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name', 'check' => true),
						'field_field'			=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_field', 'check' => true, 'prefix' => 'field_'),
						'field_type'			=> array('validate' => INT,	'type' => 'radio:type',		'explain' => true),
						'field_sub'				=> array('validate' => INT,	'type' => 'radio:sub', 		'explain' => true, 'params' => true, 'ajax' => 'ajax_order:ajax_order'),
						'field_lang'			=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'field_show_user'		=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'field_show_member'		=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'field_show_register'	=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'field_required'		=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'field_order'			=> array('validate' => INT,	'type' => 'drop:order',		'explain' => true),
					),
				);
				
				if ( $mode == 'create' && !(request('submit', TXT)) )
				{
					$data = array(
						'field_name'			=> $name,
						'field_field'			=> str_replace(' ', '_', strtolower($name)),
						'field_type'			=> 0,
						'field_sub'				=> $cat_id,
						'field_lang'			=> 0,
						'field_show_user'		=> 0,
						'field_show_member'		=> 0,
						'field_show_register'	=> 0,
						'field_required'		=> 0,
						'field_order'			=> 0,
					);
				}
				else if ( $mode == 'update' && !(request('submit', TXT)) )
				{
					$data = data(FIELDS, $data_id, false, 1, true);
				}
				else
				{
					$data = build_request(FIELDS, $vars, 'field', $error);
					
					if ( !$error )
					{
						$data['field_order'] = $data['field_order'] ? $data['field_order'] : maxa(FIELDS, 'field_order', "field_sub = " . $data['field_sub']);
					
						if ( $mode == 'create' )
						{
							$sql = sql(FIELDS, $mode, $data);
					#		$typ = ( $data['field_type'] != 0 ) ? ( $data['field_type'] == 1 ) ? 'TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL' : 'TINYINT( 1 ) UNSIGNED NOT NULL': 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL';
							$typ = ( $data['field_type'] != 0 ) ? ( $data['field_type'] == 1 ) ? 'TEXT NOT NULL' : 'TINYINT( 1 ) UNSIGNED NOT NULL': 'VARCHAR( 255 ) NOT NULL';
							$add = sql(FIELDS_DATA, 'alter', array('part' => "ADD `{$data['field_field']}`", 'type' => $typ));
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(FIELDS, $mode, $data, 'field_id', $data_id);
							$typ = ( $data['field_type'] != 0 ) ? ( $data['field_type'] == 1 ) ? 'TEXT NOT NULL' : 'TINYINT( 1 ) UNSIGNED NOT NULL': 'VARCHAR( 255 ) NOT NULL';
							$add = sql(FIELDS_DATA, 'alter', array('part' => "CHANGE `" . request('current_field', TXT) . "` `{$data['field_field']}`", 'type' => $typ));
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
					
						orders(FIELDS, $data['field_sub']);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
					
				}
				
				build_output($data, $vars, 'input', false, FIELDS);
			
			
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"current_field\" value=\"" . $data['field_field'] . "\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['field'], $data['field_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
				
			case 'create_cat':
			case 'update_cat':
			
				$template->assign_block_vars('input_cat', array());
			#	$template->assign_vars(array('FILE' => 'ajax_order'));
			#	$template->assign_var_from_handle('AJAX', 'ajax');
				
				
				$vars = array(
					'field' => array(
						'title' => 'data_input',
						'field_name'	=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => false,	'required' => 'input_name', 'check' => true),
						'field_order'	=> array('validate' => INT,	'type' => 'drop:order',		'explain' => false),
						'field_sub'		=> array('type' => 'hidden'),
					),
				);
				
				if ( $mode == 'create_cat' && !(request('submit', TXT)) )
				{
					$data = array(
						'field_name'	=> $name,
						'field_order'	=> '0',
						'field_sub'		=> '0',
					);
				}
				else if ( $mode == 'update_cat' && !(request('submit', TXT)) )
				{
					$data = data(FIELDS, $data_id, false, 1, true);
				}
				else
				{
					$data = build_request(FIELDS, $vars, 'field', $error);

					if ( !$error )
					{
						$data['field_order'] = $data['field_order'] ? $data['field_order'] : maxa(FIELDS, 'field_order', "field_sub = {$data['field_sub']}");
												
						if ( $mode == 'create_cat' )
						{
							$sql = sql(FIELDS, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(FIELDS, $mode, $data, 'field_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(FIELDS, $data['field_sub']);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output($data, $vars, 'input_cat', false, FIELDS);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['cat'], $data['field_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
			
			case 'order':
				
				update(FIELDS, 'field', $move, $data_id);
				orders(FIELDS, $data_sub);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
			case 'delete':

				$data = data(FIELDS, $data_id, false, 1, true);

				if ( $data_id && $confirm )
				{
					if ( $data['field_sub'] == 0 )
					{
						$subs = data(FIELDS, "field_sub = $data_id", 'field_order ASC', 1, false);
						
						foreach ($subs as $row)
						{
							$del_id[] = $row['field_id'];
							
							sql(FIELDS_DATA, 'alter', array('part' => "DROP ", 'type' => $row['field_field']));
						}
						
						sql(FIELDS, $mode, $data, 'field_id', $del_id);
					}
					else
					{
						sql(FIELDS, $mode, $data, 'field_id', $data_id);
						sql(FIELDS_DATA, 'alter', array('part' => "DROP ", 'type' => $data['field_field']));
					}
						
					$sql = sql(FIELDS, $mode, $data, 'field_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					orders(FIELDS, $data['field_sub']);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
					
					$lang_confirm = ( $data['field_sub'] != '0' ) ? $lang['confirm'] : $lang['confirm_sub'];

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang_confirm, $data['field_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}

				$template->pparse('confirm');

				break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_CREATE_CAT'		=> sprintf($lang['sprintf_create'], $lang['cat']),
		'L_CREATE_FIELD'	=> sprintf($lang['sprintf_new_creates'], $lang['field']),
		
		'S_ACTION'	=> check_sid($file),
	));
	
	$tmp = data(FIELDS, false, 'field_sub ASC, field_order ASC', 1, false);
	
	if ( $tmp )
	{
		foreach ( $tmp as $row )
		{
			if ( !$row['field_sub'] )
			{
				$db_cat[$row['field_name']]	= sprintf('%s:%s', $row['field_id'], $row['field_order']);
			}
			else
			{
				$db_sub[$row['field_sub']][$row['field_name']] = $row;
			}
		}
		
		if ( isset($db_sub) )
		{
			foreach ( $db_sub as $cat => $row )
			{
				foreach ( $row as $name => $details )
				{
					$max_sub[$cat] = $details['field_order'];
				}
			}
		}
	}
	else
	{
		$db_cat = $max_cat = $db_sub = $max_sub = array();
	}
	
	if ( $db_cat )
	{
		list($cat, $max) = explode(':', end($db_cat));
		
		foreach ( $db_cat as $name => $key )
		{
			$cname = isset($lang[$name]) ? $lang[$name] : $name;
			
			list($cid, $corder) = explode(':', $key);
			
			$template->assign_block_vars('display.cat', array( 
				'NAME'		=> href('a_txt', $file, array('mode' => 'update_cat', $url => $cid), $cname, $cname),
				
				'MOVE_UP'	=> ( $corder != '10' ) ? href('a_img', $file, array('mode' => 'order', 'sub' => 0, 'move' => '-15', $url => $cid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $corder != $max ) ? href('a_img', $file, array('mode' => 'order', 'sub' => 0, 'move' => '+15', $url => $cid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

				'UPDATE'	=> href('a_img', $file, array('mode' => 'update_cat', $url => $cid), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete_cat', $url => $cid), 'icon_cancel', 'common_delete'),
				
				'S_NAME'	=> "sub_field[$cid]",
				'S_SUBMIT'	=> "add_field[$cid]",
			));
			
			if ( isset($db_sub[$cid]) )
			{
				
				foreach ( $db_sub[$cid] as $subname => $subrow )
				{
					$fid	= $subrow['field_id'];
					$fname	= isset($lang[$subname]) ? $lang[$subname] : $subname;
					$ftype	= $subrow['field_type'];
					$forder	= $subrow['field_order'];
					
					$template->assign_block_vars('display.cat.field', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $fid), $fname, $fname),
						'TYPE'		=> ( $ftype != 0 ) ? ( $ftype == 1 ) ? 'textarea' : 'option' : 'textzeile',

						'MOVE_UP'	=> ( $forder != '10' )				? href('a_img', $file, array('mode' => 'order', 'sub' => $cid, 'move' => '-15', $url => $fid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $forder != $max_sub[$cid] )	? href('a_img', $file, array('mode' => 'order', 'sub' => $cid, 'move' => '+15', $url => $fid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $fid), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $fid), 'icon_cancel', 'common_delete'),
					));
				}
			}
			else
			{
				$template->assign_block_vars('display.cat.empty', array());
			}
		}
	}
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>