<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_01_main']['_submenu_profile'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_profile';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('profile');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_PROFILE;
	$url	= POST_PROFILE_URL;
	$url_c	= POST_CATEGORY_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_cat	= request($url_c, 0);
	$data_type	= request('cat_type', 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_games'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['profile']);
	
	if ( $userdata['user_level'] != ADMIN )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	if ( request('add_field', 1) || request('add_cat', 1) )
	{
		$mode = ( request('add_field', 1) ) ? '_create' : '_create_cat';
	
		if ( $mode == '_create' )
		{
			list($cat_id) = each($_POST['add_field']);
			$cat_id = intval($cat_id);
			
			$field_name = trim(htmlentities(str_replace("\'", "'", $_POST['field_name'][$cat_id]), ENT_COMPAT));
		}
	}
	
#	function _select_category($select)
	function select_cat($select)
	{
		global $db;
		
		$sql = "SELECT * FROM " . PROFILE_CAT . " ORDER BY cat_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$category = '<select name="profile_cat" id="profile_cat" class="post">';
		while ( $row = $db->sql_fetchrow($result) )
		{
			$selected = ( $row['cat_id'] == $select ) ? ' selected="selected"' : '';
			$category .= '<option value="' . $row['cat_id'] . '"' . $selected . '>' . $row['cat_name'] . '&nbsp;</option>';
		}
		$category .= '</select>';
		
		return $category;	
	}
	
	debug($_POST);
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !(request('submit', 1)) )
				{
					$data = array(
								'profile_name'			=> $field_name,
								'profile_cat'			=> $cat_id,
								'profile_field'			=> '',
								'profile_type'			=> '0',
								'profile_language'		=> '0',
								'profile_show_guest'	=> '0',
								'profile_show_member'	=> '0',
								'profile_show_register'	=> '0',
								'profile_necessary'		=> '0',
								'profile_order'			=> '',
							);
				}
				else if ( $mode == '_update' && !(request('submit', 1)) )
				{
					$data = data(PROFILE, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
								'profile_name'			=> request('profile_name', 2),
								'profile_cat'			=> request('profile_cat', 0),
								'profile_field'			=> request('profile_field', 2),
								'profile_type'			=> request('profile_type', 0),
								'profile_language'		=> request('profile_language', 0),
								'profile_show_guest'	=> request('profile_show_guest', 0),
								'profile_show_member'	=> request('profile_show_member', 0),
								'profile_show_register'	=> request('profile_show_register', 0),
								'profile_necessary'		=> request('profile_necessary', 0),
							);
				}
			
				$data_cat = data(PROFILE_CAT, false, 'cat_order ASC', 1, false);
				
				for ( $j = 0; $j < count($data_cat); $j++ )
				{
					$template->assign_block_vars('_input._cat', array(
						'CAT_ID'	=> $data_cat[$j]['cat_id'],
						'CAT_NAME'	=> $data_cat[$j]['cat_name'],
						
						'S_MARK'	=> ( $data['profile_cat'] == $data_cat[$j]['cat_id'] ) ? 'checked="checked"' : '',
					));
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= '<input type="hidden" name="profile_order" value="' . $data['profile_order'] . '" />';
				
			#	$fields	= '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . $url_c . '" value="' . $data_id . '" />';
			#	$s_sprintf	= ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['field'], $data['profile_name']),
				
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['field']),
					'L_FIELD'		=> $lang['field'],
					'L_CAT'			=> $lang['common_category'],
					'L_LANGUAGE'	=> $lang['common_language'],
					'L_TYPE'		=> sprintf($lang['sprintf_type'], $lang['field']),
					'L_SHOW'		=> $lang['common_view'],
					'L_SGUEST'		=> $lang['show_guest'],
					'L_SMEMBER'		=> $lang['show_member'],
					'L_SREG'		=> $lang['show_reg'],

					'L_TYPE_TEXT'	=> $lang['type_text'],
					'L_TYPE_AREA'	=> $lang['type_area'],
					
					'NAME'			=> $data['profile_name'],
					'FIELD'			=> str_replace('profile_', '', $data['profile_field']),
					
					'S_CAT'	=> select_cat($cat_id),
					
					'S_TYPE_AREA'	=> (!$data['profile_type'] ) ? ' checked="checked"' : '',
					'S_TYPE_TEXT'	=> ( $data['profile_type'] ) ? ' checked="checked"' : '',
					'S_LANG_NO'		=> (!$data['profile_language'] ) ? ' checked="checked"' : '',
					'S_LANG_YES'	=> ( $data['profile_language'] ) ? ' checked="checked"' : '',
					'S_SGUEST_NO'	=> (!$data['profile_show_guest'] ) ? ' checked="checked"' : '',
					'S_SGUEST_YES'	=> ( $data['profile_show_guest'] ) ? ' checked="checked"' : '',
					'S_SMEMBER_NO'	=> (!$data['profile_show_member'] ) ? ' checked="checked"' : '',
					'S_SMEMBER_YES'	=> ( $data['profile_show_member'] ) ? ' checked="checked"' : '',
					'S_SREG_NO'		=> (!$data['profile_show_register'] ) ? ' checked="checked"' : '',
					'S_SREG_YES'	=> ( $data['profile_show_register'] ) ? ' checked="checked"' : '',
					'S_REQ_NO'		=> (!$data['profile_necessary'] ) ? ' checked="checked"' : '',
					'S_REQ_YES'		=> ( $data['profile_necessary'] ) ? ' checked="checked"' : '',
					
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$profile_name			= request('profile_name', 2);
					$profile_cat		= request('profile_cat', 0);
					$profile_field			= request('profile_field', 2);
					$profile_type			= request('profile_type', 0);
					$profile_language		= request('profile_language', 0);
					$profile_show_guest		= request('profile_show_guest', 0);
					$profile_show_member	= request('profile_show_member', 0);
					$profile_show_register	= request('profile_show_register', 0);
					$profile_necessary		= request('profile_necessary', 0);
						
					$error .= ( !$profile_name ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
					$error .= ( !$profile_field ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_profilefield'] : '';
					
					str_replace('profile_', '', $profile_field);
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$max	= get_data_max(PROFILE, 'profile_order', 'profile_cat = ' . $profile_cat);
							$next	= $max['max'] + 10;
							
							$sql = "INSERT INTO " . PROFILE . " (profile_name, profile_field, profile_cat, profile_type, profile_language, profile_show_guest, profile_show_member, profile_show_register, profile_necessary, profile_order)
										VALUES ('$profile_name', 'profile_" . $profile_field . "', '$profile_cat', '$profile_type', '$profile_language', '$profile_sguest', '$profile_smember', '$profile_sregister', '$profile_necessary', '$next')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							$sql = "ALTER TABLE " . PROFILE_DATA . " ADD '$profile_field' 'profile_" . $profile_field . "'" . ( $profile_type ) ? "VARCHAR( 255 ) NOT NULL" : "TEXT NOT NULL" ;
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
						}
						
						#$oCache -> sCachePath = './../cache/';
						#$oCache -> deleteCache('authlist');
						
						log_add(LOG_ADMIN, $log, $mode, $profile_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');
				
				break;
			
			case '_create_field_save':
			
				debuge($_POST);
				
				$profile_name		= request('profile_name', 2);
				$profile_field		= request('profile_field', 0);
				$profile_cat	= request('profile_cat', 0);
				$profile_language	= request('profile_language', 0);
				$profile_type		= request('profile_type', 0);
				$profile_sguest		= request('profile_sguest', 0);
				$profile_smember	= request('profile_smember', 0);
				$profile_sregister	= request('profile_sregister', 0);
				$profile_necessary	= request('profile_necessary', 0);
				
				if ( $profile_name == '' || $profile_field == '' )
				{
					message(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				
				$max_row	= get_data_max(PROFILE, 'profile_order', 'profile_cat = ' . $profile_cat);
				$max_order	= $max_row['max'];
				$next_order	= $max_order + 10;
	
				$sql = "INSERT INTO " . PROFILE . " (profile_name, profile_field, profile_cat, profile_type, profile_language, profile_show_guest, profile_show_member, profile_show_register, profile_necessary, profile_order)
							VALUES ('$profile_name', 'profile_" . $profile_field . "', '$profile_cat', '$profile_type', '$profile_language', '$profile_sguest', '$profile_smember', '$profile_sregister', '$profile_necessary', '$next_order')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$typ = ( $profile_type ) ? 'VARCHAR( 255 ) NOT NULL' : 'TEXT NOT NULL';
				
				$sql = "ALTER TABLE " . PROFILE_DATA . " ADD '" . $field . "' 'profile_" . $profile_field . "' $typ";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['create_profile'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
				log_add(LOG_ADMIN, $log, 'create_profile');
				message(GENERAL_MESSAGE, $message);

				break;
			
			case '_update_field_save':
			
				debuge($_POST);
			
				$profile	= get_data('profile', $profile_id, 0);
				$field		= $profile['profile_field'];
				$type		= $profile['profile_type'];
				$category	= $profile['profile_cat'];
				
				$profile_name		= request('profile_name', 2);
				$profile_field		= request('profile_field', 0);
				$profile_cat	= request('profile_cat', 0);
				$profile_language	= request('profile_language', 0);
				$profile_type		= request('profile_type', 0);
				$profile_sguest		= request('profile_sguest', 0);
				$profile_smember	= request('profile_smember', 0);
				$profile_sregister	= request('profile_sregister', 0);
				$profile_necessary	= request('profile_necessary', 0);
				
				if ( $profile_name == '' || $profile_field == '' )
				{
					message(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				
				if ( $category != $profile_cat )
				{
					$max_row	= get_data_max(PROFILE, 'profile_order', 'profile_cat = ' . $profile_cat);
					$max_order	= $max_row['max'];
					$next_order	= $max_order + 10;
					$sql_order	= ', profile_order = ' . $next_order;
					
					orders('profile', $cat_type);
				}
				else
				{
					$sql_order = '';
				}
				
				if ( $type != $profile_type )
				{
					$typ = ( $profile_type ) ? 'VARCHAR( 255 ) NOT NULL' : 'TEXT NOT NULL';
					$sql = "ALTER TABLE " . PROFILE_DATA . " CHANGE '" . $field . "' 'profile_" . $profile_field . "' $typ";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				$sql = "UPDATE " . PROFILE . " SET
							profile_name		= '$profile_name',
							profile_field		= 'profile_" . $profile_field . "',
							profile_cat	= '$profile_cat',
							profile_type		= '$profile_type',
							profile_show_guest	= '$profile_sguest',
							profile_show_member	= '$profile_smember',
							profile_necessary	= '$profile_necessary'
							$sql_order
						WHERE profile_id = $profile_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['update_profile'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
				log_add(LOG_ADMIN, $log, 'update_profile');
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case '_order_field':
				
				update(PROFILE, 'profile', $move, $data_id);
				orders(PROFILE, $data_type);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
//			case '_delete_field':
//			
//				if ( $profile_id && $confirm )
//				{	
//					$profile = get_data('profile', $profile_id, 0);
//
//					$sql = 'DELETE FROM ' . NAVIGATION . " WHERE profile_id = $profile_id";
//					if ( !($result = $db->sql_query($sql)) )
//					{
//						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
//					}
//				
//					log_add(LOG_ADMIN, $log, 'acp_profile_delete', $profile['profile_name']);
//					
//					$message = $lang['delete_profile'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
//					message(GENERAL_MESSAGE, $message);
//				
//				}
//				else if ( $profile_id && !$confirm )
//				{
//					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
//		
//					$fields = '<input type="hidden" name="mode" value="delete" />';
//					$fields .= '<input type="hidden" name="' . $url_c . '" value="' . $profile_id . '" />';
//		
//					$template->assign_vars(array(
//						'MESSAGE_TITLE'		=> $lang['common_confirm'],
//						'MESSAGE_TEXT'		=> $lang['confirm_delete_profile'],
//		
//						'L_YES'				=> $lang['common_yes'],
//						'L_NO'				=> $lang['common_no'],
//		
//						'S_ACTION'	=> append_sid($file),
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
			
			case '_create_cat':
			case '_update_cat':
			
				$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
				$template->assign_block_vars('_input_cat', array());
				
				if ( $mode == '_create_cat' && !(request('submit', 1)) )
				{
					$max = get_data_max(PROFILE_CAT, 'cat_order', '');
					$data = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_order'	=> $max['max'] + 10,
							);
				}
				else if ( $mode == '_update_cat' && !(request('submit', 1)) )
				{
					$data = data(PROFILE_CAT, $data_cat, false, 1, 1);
				}
				else
				{
					$data = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_order'	=> ( request('cat_order_new', 0) ) ? request('cat_order_new', 0) : request('cat_order', 0),
							);
				}
				
				$sql = "SELECT * FROM " . PROFILE_CAT . " ORDER BY cat_order ASC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$entrys = $db->sql_fetchrowset($result);
				
				$entry = '';
				
				$s_order = "<select class=\"select\" name=\"cat_order_new\" id=\"cat_order\">";
				$s_order .= "<option selected=\"selected\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
				
				for ( $i = 0; $i < count($entrys); $i++ )
				{
					$mark = ( $entrys[$i]['cat_order'] == $data['cat_order'] ) ? 'disabled' : '';
					$s_order .= ( $entrys[$i]['cat_order'] == 10 ) ? "<option value=\"5\" $mark>" . sprintf($lang['sprintf_select_before'], $entrys[$i]['cat_name']) . "</option>" : '';
					$s_order .= "<option value=\"" . ( $entrys[$i]['cat_order'] + 5 ) . "\" $mark>" . sprintf($lang['sprintf_select_order'], $entrys[$i]['cat_name']) . "</option>";
				}
				
				$s_order .= '</select>';
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$data_cat\" />";
				$fields .= "<input type=\"hidden\" name=\"cat_order\" value=" . $data['cat_order'] . " />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_cat', '', $mode)], $lang['profile_c'], $data['cat_name']),
					'L_NAME'	=> sprintf($lang['sprintf_category'], $lang['field']),
					
					'NAME'		=> $data['cat_name'],
					
					'S_ORDER'	=> $s_order,
					
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', 1) )
				{
				#	$cat_name = request('cat_name', 2);
					
				#	$error = ( !$cat_name ) ? $lang['msg_select_name'] : '';
				
					$data = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_order'	=> ( request('cat_order_new', 0) ) ? request('cat_order_new', 0) : request('cat_order', 0),
							);
							
					$error .= ( !$data['cat_name'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create_cat' )
						{
							foreach ( $data as $key => $var )
							{
								$keys[] = $key;
								$vars[] = $var;
							}
						
						#	$max	= get_data_max(PROFILE_CAT, 'cat_order', '');
						#	$next	= $max_row['max'] + 10;
						#	
						#	$sql = "INSERT INTO " . PROFILE_CAT . " (cat_name, cat_order) VALUES ('$cat_name', '$next')";
							$sql = 'INSERT INTO ' . PROFILE_CAT . ' (' . implode(', ', $keys) . ') VALUES (\'' . implode('\', \'', $vars) . '\')';
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_c'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							foreach ( $data as $key => $var )
							{
								$input[] = "$key = '$var'";
							}
							
							$sql = "UPDATE " . PROFILE_CAT . " SET " . implode(', ', $input) . " WHERE cat_id = $data_cat";
						#	$sql = "UPDATE " . PROFILE_CAT . " SET cat_name = '$cat_name' WHERE cat_id = $data_cat";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_c']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url_c=$data_cat") . '">', '</a>');
						}
						
						orders(PROFILE_CAT);
						
						log_add(LOG_ADMIN, $log, $mode, $data['cat_name']);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');
			
				break;
			
			case '_order_cat':
				
				update(PROFILE_CAT, 'cat', $move, $data_cat);
				orders(PROFILE_CAT);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete_cat':
			
				$data = data(PROFILE_CAT, $data_cat, false, 1, 1);
			
				if ( $data_cat && $confirm )
				{
				#	sql(PROFILE_CAT, 'delete', false, 'cat_id', $data_id);
					$sql = "DELETE FROM " . PROFILE_CAT . " WHERE cat_id = $data_cat";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['delete_c'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					
					#$oCache -> sCachePath = './../cache/';
					#$oCache -> deleteCache('_display_navi_news');
					
					orders(PROFILE_CAT);
					
					log_add(LOG_ADMIN, $log, $mode, $data['cat_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_cat && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$data_cat\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm_c'], $data['cat_name']),

						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['profile_cat'])); }
				
				$template->pparse('body');
				
				break;
				
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
	$template->assign_block_vars('_display', array());
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_CREATE_CAT'		=> sprintf($lang['sprintf_new_create'], $lang['cat']),
		'L_CREATE_FIELD'	=> sprintf($lang['sprintf_new_creates'], $lang['field']),
		
		'S_ACTION'	=> append_sid($file),
	));
	
	$max	= get_data_max(PROFILE_CAT, 'cat_order', '');
#	$cats	= get_data_array(PROFILE_CAT, '', 'cat_order', 'ASC');
	$cats	= data(PROFILE_CAT, false, 'cat_order ASC', 1, false);
	
	if ( $cats )
	{
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id = $cats[$i]['cat_id'];
			
		#	$profile	= get_data_array(PROFILE, 'profile_cat = ' . $cat_id, 'profile_cat, profile_order', '');
			$profile	= data(PROFILE, 'profile_cat = ' . $cat_id, 'profile_cat, profile_order ASC', 1, false);
			
			$sql = "SELECT MAX(profile_order) AS max$cat_id FROM " . PROFILE . " WHERE profile_cat = $cat_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_profile = $db->sql_fetchrow($result);
			
			$template->assign_block_vars('_display._cat_row', array( 
				'ID'		=> $cats[$i]['cat_id'],
				'NAME'		=> $cats[$i]['cat_name'],
				
				'MOVE_UP'	=> ( $cats[$i]['cat_order'] != '10' )			? '<a id="right" href="' . append_sid("$file?mode=_order_cat&amp;move=-15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" /></a>',
				'MOVE_DOWN'	=> ( $cats[$i]['cat_order'] != $max['max'] )	? '<a id="right" href="' . append_sid("$file?mode=_order_cat&amp;move=+15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" /></a>',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update_cat&amp;$url_c=$cat_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete_cat&amp;$url_c=$cat_id"),
				
				'S_NAME'	=> "field_name[$cat_id]",
				'S_SUBMIT'	=> "add_field[$cat_id]",
			));
			
			if ( $profile )
			{
				for ( $j = 0; $j < count($profile); $j++ )
				{
					$profile_id = $profile[$j]['profile_id'];
					$profile_cat = $profile[$j]['profile_cat'];
	
					if ( $profile_cat == $cat_id )
					{
						$template->assign_block_vars('_display._cat_row._profile_row', array(
							'NAME'	=> $profile[$j]['profile_name'],
							'FIELD'	=> $profile[$j]['profile_field'],
							
							'MOVE_UP'	=> ( $profile[$j]['profile_order'] != '10' )						? '<a href="' . append_sid("$file?mode=_order_field&amp;cat_type=$cat_id&amp;move=-15&amp;$url=$profile_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
							'MOVE_DOWN'	=> ( $profile[$j]['profile_order'] != $max_profile['max' . $cat_id] )	? '<a href="' . append_sid("$file?mode=_order_field&amp;cat_type=$cat_id&amp;move=+15&amp;$url=$profile_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
							
							'U_UPDATE' => append_sid("$file?mode=_update&amp;$url=$profile_id"),
							'U_DELETE' => append_sid("$file?mode=_delete&amp;$url=$profile_id"),
						));
					}
				}
			}
			else { $template->assign_block_vars('_display._cat_row._no_entry', array()); }
		}
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>