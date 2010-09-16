<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_main']['_submenu_profile'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_profile';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');

	load_lang('profile');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_PROFILE_URL, 0);
	$data_cat	= request(POST_CATEGORY_URL, 0);
	$data_type	= request('cat_type', 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$show_index	= '';
	
	if ( $userdata['user_level'] != ADMIN )
	{
		log_add(LOG_ADMIN, LOG_SEK_PROFILE, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $no_header ) ? redirect('admin/' . append_sid('admin_profile.php', true)) : false;
	
	if ( request('add_profile', 2) || request('addcategory', 2) )
	{
		$mode = ( request('add_profile', 2) ) ? '_create' : '_create_cat';
	
		if ( $mode == '_create' )
		{
			list($cat_id) = each(request('add_profile'));
			$cat_id = intval($cat_id);
			
			$profile_name = trim(htmlentities(str_replace("\'", "'", $_POST['profile_name'][$cat_id]), ENT_COMPAT));
		}
	}
	
#	function _select_category($select)
	function select_cat($select)
	{
		global $db;
		
		$sql = "SELECT * FROM " . PROFILE_CATEGORY . " ORDER BY category_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$category = '<select name="profile_category" id="profile_category" class="post">';
		while ( $row = $db->sql_fetchrow($result) )
		{
			$selected = ( $row['category_id'] == $select ) ? ' selected="selected"' : '';
			$category .= '<option value="' . $row['category_id'] . '"' . $selected . '>' . $row['category_name'] . '&nbsp;</option>';
		}
		$category .= '</select>';
		
		return $category;	
	}
	
	
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !(request('submit', 2)) )
				{
					$data = array(
						'profile_name'			=> request('profile_name', 2),
						'profile_category'		=> select_cat($cat_id),
						'profile_field'			=> '',
						'profile_type'			=> '0',
						'profile_language'		=> '0',
						'profile_show_guest'	=> '0',
						'profile_show_member'	=> '0',
						'profile_show_register'	=> '0',
						'profile_required'		=> '0',
					);
				}
				else if ( $mode == '_update' && !(request('submit', 2)) )
				{
					$data = get_data(PROFILE, $data_id, 1);
				}
				else
				{
					$data = array(
						'profile_name'			=> request('profile_name', 2),
						'profile_category'		=> request('profile_category', 0),
						'profile_field'			=> request('profile_field', 2),
						'profile_type'			=> request('profile_type', 0),
						'profile_language'		=> request('profile_language', 0),
						'profile_show_guest'	=> request('profile_show_guest', 0),
						'profile_show_member'	=> request('profile_show_member', 0),
						'profile_show_register'	=> request('profile_show_register', 0),
						'profile_required'		=> request('profile_required', 0),
					);
				}
				
				$s_fields	= '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_CATEGORY_URL . '" value="' . $data_id . '" />';
				$s_sprintf	= ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['profile_field'], $data['profile_name']),
				
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['profile_field']),
					'L_FIELD'		=> $lang['profile_field'],
					'L_CATEGORY'	=> $lang['common_category'],
					'L_LANGUAGE'	=> $lang['common_language'],
					'L_TYPE'		=> sprintf($lang['sprintf_type'], $lang['profile_field']),
					'L_SHOW'		=> $lang['common_view'],
					'L_SGUEST'		=> $lang['profile_show_guest'],
					'L_SMEMBER'		=> $lang['profile_show_member'],
					'L_SREG'		=> $lang['profile_show_reg'],

					'L_TYPE_TEXT'	=> $lang['profile_type_text'],
					'L_TYPE_AREA'	=> $lang['profile_type_area'],
					
					'NAME'	=> $profile_name,
					'FIELD'	=> str_replace('profile_', '', $profile['profile_field']),
					
					'S_CATEGORY'	=> select_cat($cat_id),
					
					'S_TYPE_TEXT'	=> ( $profile['profile_type'] ) ? ' checked="checked"' : '',
					'S_TYPE_AREA'	=> ( !$profile['profile_type'] ) ? ' checked="checked"' : '',
					'S_LANG_YES'	=> ( $profile['profile_language'] ) ? ' checked="checked"' : '',
					'S_LANG_NO'		=> ( !$profile['profile_language'] ) ? ' checked="checked"' : '',
					'S_SGUEST_YES'	=> ( $profile['profile_show_guest'] ) ? ' checked="checked"' : '',
					'S_SGUEST_NO'	=> ( !$profile['profile_show_guest'] ) ? ' checked="checked"' : '',
					'S_SMEMBER_YES'	=> ( $profile['profile_show_member'] ) ? ' checked="checked"' : '',
					'S_SMEMBER_NO'	=> ( !$profile['profile_show_member'] ) ? ' checked="checked"' : '',
					'S_SREG_YES'	=> ( $profile['profile_show_register'] ) ? ' checked="checked"' : '',
					'S_SREG_NO'		=> ( !$profile['profile_show_register'] ) ? ' checked="checked"' : '',
					'S_REQ_YES'		=> ( $profile['profile_required'] ) ? ' checked="checked"' : '',
					'S_REQ_NO'		=> ( !$profile['profile_required'] ) ? ' checked="checked"' : '',

					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_profile.php'),
				));
				
				if ( request('submit', 2) )
				{
					$profile_name			= request('profile_name', 2);
					$profile_category		= request('profile_category', 0);
					$profile_field			= request('profile_field', 2);
					$profile_type			= request('profile_type', 0);
					$profile_language		= request('profile_language', 0);
					$profile_show_guest		= request('profile_show_guest', 0);
					$profile_show_member	= request('profile_show_member', 0);
					$profile_show_register	= request('profile_show_register', 0);
					$profile_required		= request('profile_required', 0);
						
					$error =	( !$profile_name ) ? $lang['msg_select_name'] : '';
					$error .= 	( !$profile_field ) ? ( $error ? '<br>' : '' ) . $lang['msg_select_profilefield'] : '';
					
					str_replace('profile_', '', $profile_field);
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$max	= get_data_max(PROFILE, 'profile_order', 'profile_category = ' . $profile_category);
							$next	= $max['max'] + 10;
							
							$sql = "INSERT INTO " . PROFILE . " (profile_name, profile_field, profile_category, profile_type, profile_language, profile_show_guest, profile_show_member, profile_show_register, profile_required, profile_order)
										VALUES ('$profile_name', 'profile_" . $profile_field . "', '$profile_category', '$profile_type', '$profile_language', '$profile_sguest', '$profile_smember', '$profile_sregister', '$profile_required', '$next')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
						#	$typ = ( $profile_type ) ? 'VARCHAR( 255 ) NOT NULL' : 'TEXT NOT NULL';
							
							$sql = "ALTER TABLE " . PROFILE_DATA . " ADD '$profile_field' 'profile_" . $profile_field . "'" . ( $profile_type ) ? "VARCHAR( 255 ) NOT NULL" : "TEXT NOT NULL" ;
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . AUTHLIST . " SET authlist_name = 'auth_" . $authlist_name . "' WHERE authlist_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$sql = "ALTER TABLE " . GROUPS . " CHANGE '" . $data['authlist_name'] . "' 'auth_" . $authlist_name . "' TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_authlist']
								. sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_games.php?mode=_update&amp;' . POST_AUTHLIST_URL . '=' . $data_id) . '">', '</a>');
						}
						
						$oCache -> sCachePath = './../cache/';
						$oCache -> deleteCache('authlist');
						
						log_add(LOG_ADMIN, LOG_SEK_AUTHLIST, $mode . '_authlist');
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
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
				$profile_category	= request('profile_category', 0);
				$profile_language	= request('profile_language', 0);
				$profile_type		= request('profile_type', 0);
				$profile_sguest		= request('profile_sguest', 0);
				$profile_smember	= request('profile_smember', 0);
				$profile_sregister	= request('profile_sregister', 0);
				$profile_required	= request('profile_required', 0);
				
				if ( $profile_name == '' || $profile_field == '' )
				{
					message(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				
				$max_row	= get_data_max(PROFILE, 'profile_order', 'profile_category = ' . $profile_category);
				$max_order	= $max_row['max'];
				$next_order	= $max_order + 10;
	
				$sql = "INSERT INTO " . PROFILE . " (profile_name, profile_field, profile_category, profile_type, profile_language, profile_show_guest, profile_show_member, profile_show_register, profile_required, profile_order)
							VALUES ('$profile_name', 'profile_" . $profile_field . "', '$profile_category', '$profile_type', '$profile_language', '$profile_sguest', '$profile_smember', '$profile_sregister', '$profile_required', '$next_order')";
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
				
				$message = $lang['create_profile'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
				log_add(LOG_ADMIN, LOG_SEK_PROFILE, 'create_profile');
				message(GENERAL_MESSAGE, $message);

				break;
			
			case '_update_field_save':
			
				debuge($_POST);
			
				$profile	= get_data('profile', $profile_id, 0);
				$field		= $profile['profile_field'];
				$type		= $profile['profile_type'];
				$category	= $profile['profile_category'];
				
				$profile_name		= request('profile_name', 2);
				$profile_field		= request('profile_field', 0);
				$profile_category	= request('profile_category', 0);
				$profile_language	= request('profile_language', 0);
				$profile_type		= request('profile_type', 0);
				$profile_sguest		= request('profile_sguest', 0);
				$profile_smember	= request('profile_smember', 0);
				$profile_sregister	= request('profile_sregister', 0);
				$profile_required	= request('profile_required', 0);
				
				if ( $profile_name == '' || $profile_field == '' )
				{
					message(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				
				if ( $category != $profile_category )
				{
					$max_row	= get_data_max(PROFILE, 'profile_order', 'profile_category = ' . $profile_category);
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
							profile_category	= '$profile_category',
							profile_type		= '$profile_type',
							profile_show_guest	= '$profile_sguest',
							profile_show_member	= '$profile_smember',
							profile_required	= '$profile_required'
							$sql_order
						WHERE profile_id = $profile_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['update_profile'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
				log_add(LOG_ADMIN, LOG_SEK_PROFILE, 'update_profile');
				message(GENERAL_MESSAGE, $message);
	
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
//					log_add(LOG_ADMIN, LOG_SEK_PROFILE, 'acp_profile_delete', $profile['profile_name']);
//					
//					$message = $lang['delete_profile'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
//					message(GENERAL_MESSAGE, $message);
//				
//				}
//				else if ( $profile_id && !$confirm )
//				{
//					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
//		
//					$s_fields = '<input type="hidden" name="mode" value="delete" />';
//					$s_fields .= '<input type="hidden" name="' . POST_CATEGORY_URL . '" value="' . $profile_id . '" />';
//		
//					$template->assign_vars(array(
//						'MESSAGE_TITLE'		=> $lang['common_confirm'],
//						'MESSAGE_TEXT'		=> $lang['confirm_delete_profile'],
//		
//						'L_YES'				=> $lang['common_yes'],
//						'L_NO'				=> $lang['common_no'],
//		
//						'S_ACTION'	=> append_sid('admin_profile.php'),
//						'S_FIELDS'	=> $s_fields,
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

			
			
			case '_order_field':
				
				update(PROFILE, 'profile', $move, $data_id);
				orders(PROFILE, $data_type);
				
				log_add(LOG_ADMIN, LOG_SEK_PROFILE, $mode);
				
				$show_index = TRUE;
				
				break;
				
			case '_create_cat':
			case '_update_cat':
			
				$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
				$template->assign_block_vars('_input_cat', array());
				
				if ( $mode == '_create_cat' && !(request('submit', 2)) )
				{
					$data = array('category_name' => request('category_name', 2));
				}
				else if ( $mode == '_update_cat' && !(request('submit', 2)) )
				{
					$data = get_data(PROFILE_CATEGORY, $data_cat, 1);
				}
				else
				{
					$data = array('category_name' => request('category_name', 2));
				}
				
				$s_fields	= '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_CATEGORY_URL . '" value="' . $data_cat . '" />';
				$s_sprintf	= ( $mode == '_create_cat' ) ? 'sprintf_add' : 'sprintf_edit';
	
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile_cat']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['profile_cat'], $data['category_name']),
											 
					'L_NAME'	=> sprintf($lang['sprintf_category'], $lang['profile_field']),
					
					'NAME'	=> $data['category_name'],
					
					'S_FIELDS'	=> $s_fields, 
					'S_ACTION'	=> append_sid('admin_profile.php'),
				));
				
				if ( request('submit', 2) )
				{
					$category_name = request('category_name', 2);
					
					$error = ( !$category_name ) ? $lang['msg_select_name'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create_cat' )
						{
							$max	= get_data_max(PROFILE_CATEGORY, 'category_order', '');
							$next	= $max_row['max'] + 10;
							
							$sql = "INSERT INTO " . PROFILE_CATEGORY . " (category_name, category_order) VALUES ('$category_name', '$next')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_profile_cat'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . PROFILE_CATEGORY . " SET category_name = '$category_name' WHERE category_id = $data_cat";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_profile_cat']
								. sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_profile.php?mode=_update_cat&amp;' . POST_CATEGORY_URL . '=' . $data_cat) . '">', '</a>');
						}
						
						log_add(LOG_ADMIN, LOG_SEK_PROFILE, $mode . '_profile');
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');
			
				break;
			
			case '_order_cat':
				
				update(PROFILE_CATEGORY, 'category', $move, $data_cat);
				orders(PROFILE_CATEGORY);
				
				log_add(LOG_ADMIN, LOG_SEK_PROFILE, $mode);
				
				$show_index = TRUE;
				
				break;
				
			default:
			
				message(GENERAL_ERROR, $lang['msg_no_module_select']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
	$template->assign_block_vars('_display', array());
			
	$template->assign_vars(array(
	#	'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['profile']),
	#	'L_CREATE'		=> sprintf($lang['sprintf_new_creates'], $lang['profile_field']),
	#	'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['profile']),
	#	'L_EXPLAIN'		=> $lang['profile_explain'],
	#	
		'L_CAT_CREATE'	=> sprintf($lang['sprintf_new_create'], $lang['profile_cat']), 
		
		'S_ACTION'	=> append_sid('admin_profile.php'),
	));
	
	$max	= get_data_max(PROFILE_CATEGORY, 'category_order', '');
	$cats	= get_data_array(PROFILE_CATEGORY, '', 'category_order', 'ASC');
	
	if ( $cats )
	{
		$profile = get_data_array(PROFILE, '', 'profile_category, profile_order', '');
		
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id = $cats[$i]['category_id'];
			
			$sql = "SELECT MAX(profile_order) AS max$cat_id FROM " . PROFILE . " WHERE profile_category = $cat_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_profile = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			$template->assign_block_vars('_display._cat_row', array( 
				'CAT_ID'	=> $cat_id,
				'CAT_NAME'	=> $cats[$i]['category_name'],
				
				'CAT_MOVE_UP'	=> ( $cats[$i]['category_order'] != '10' )			? '<a id="right" href="' . append_sid('admin_profile.php?mode=_order_cat&amp;move=-15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img width="75%" src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<a id="right" href="' . append_sid('admin_profile.php') .'"><img width="75%" src="' . $images['icon_acp_arrow_u2'] . '" alt="" /></a>',
				'CAT_MOVE_DOWN'	=> ( $cats[$i]['category_order'] != $max['max'] )	? '<a id="right" href="' . append_sid('admin_profile.php?mode=_order_cat&amp;move=15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img width="75%" src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<a id="right" href="' . append_sid('admin_profile.php') .'"><img width="75%" src="' . $images['icon_acp_arrow_d2'] . '" alt="" /></a>',
				
				'U_CAT_UPDATE'	=> append_sid('admin_profile.php?mode=_update_cat&amp;' . POST_CATEGORY_URL . '=' .$cat_id),
				'U_CAT_DELETE'	=> append_sid('admin_profile.php?mode=_delete_cat&amp;' . POST_CATEGORY_URL . '=' .$cat_id),
				
				'S_ADD_PROFILE_NAME'	=> 'profile_name[' . $cat_id . ']',
				'S_ADD_PROFILE_SUBMIT'	=> 'add_profile[' . $cat_id . ']',

			));
	
			for ( $j = 0; $j < count($profile); $j++ )
			{
				$profile_id = $profile[$j]['profile_id'];

				if ( $profile[$j]['profile_category'] == $cat_id )
				{
					$template->assign_block_vars('_display._cat_row._profile_row', array(
						'NAME'	=> $profile[$j]['profile_name'],
						'FIELD'	=> $profile[$j]['profile_field'],
						
						'MOVE_UP'	=> ( $profile[$j]['profile_order'] != '10' )						? '<a href="' . append_sid('admin_profile.php?mode=_order_field&amp;cat_type=' . $cat_id . '&amp;move=-15&amp;' . POST_PROFILE_URL . '=' . $profile_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
						'MOVE_DOWN'	=> ( $profile[$j]['profile_order'] != $max_profile['max'.$cat_id] )	? '<a href="' . append_sid('admin_profile.php?mode=_order_field&amp;cat_type=' . $cat_id . '&amp;move=15&amp;' . POST_PROFILE_URL . '=' . $profile_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

						'U_PERMISSION' => append_sid('admin_profileauth.php?' . POST_PROFILE_URL . '=' . $profile_id . '&amp;adv=1'),
						
						'U_UPDATE' => append_sid('admin_profile.php?mode=_update&amp;' . POST_PROFILE_URL . '=' . $profile_id),
						'U_DELETE' => append_sid('admin_profile.php?mode=_delete&amp;' . POST_PROFILE_URL . '=' . $profile_id),
					));
				}
			}
		}

	}

/*
	$sql = 'SELECT MAX(category_order) AS max FROM ' . PROFILE_CATEGORY;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$max_order = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT * FROM ' . PROFILE_CATEGORY . ' ORDER BY category_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $total_categories = $db->sql_numrows($result) )
	{
		$category_rows = $db->sql_fetchrowset($result);
		
		$sql = 'SELECT *
					FROM ' . PROFILE . '
					ORDER BY profile_category, profile_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	
		if( $total_profile = $db->sql_numrows($result) )
		{
			$profile_rows = $db->sql_fetchrowset($result);
		}
		
		//
		// Okay, let's build the index
		//
		for ( $i = 0; $i < $total_categories; $i++ )
		{
			$cat_id = $category_rows[$i]['category_id'];
			
			$sql = "SELECT MAX(profile_order) AS max$cat_id FROM " . PROFILE . " WHERE profile_category = $cat_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_profile = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$icon_up	= ( $category_rows[$i]['category_order'] != '10' ) ? $lang['move_up'] : '';
			$icon_down	= ( $category_rows[$i]['category_order'] != $max_order['max'] ) ? $lang['move_down'] : '';
	
			$template->assign_block_vars('display.catrow', array( 
				
				
				'CATEGORY_ID'			=> $cat_id,
				'CATEGORY_NAME'			=> $category_rows[$i]['category_name'],
				
				'L_MOVE_UP'				=> $icon_up,
				'L_MOVE_DOWN'			=> $icon_down,
				
				
				
				'U_CAT_EDIT'			=> append_sid('admin_profile.php?mode=editcat&amp;" . POST_CATEGORY_URL . "=$cat_id'),
//				'U_CAT_DELETE'			=> append_sid('admin_profile.php?mode=deletecat&amp;" . POST_CATEGORY_URL . "=$cat_id'),
				'U_CAT_MOVE_UP'			=> append_sid('admin_profile.php?mode=cat_order&amp;move=-15&amp;" . POST_CATEGORY_URL . "=$cat_id'),
				'U_CAT_MOVE_DOWN'		=> append_sid('admin_profile.php?mode=cat_order&amp;move=15&amp;" . POST_CATEGORY_URL . "=$cat_id'),
				'U_VIEWCAT'				=> append_sid($root_path."profile.php?" . POST_CATEGORY_URL . "=$cat_id"),
		
				'CATEGORY_MOVE_UP'		=> ( $category_rows[$i]['category_order'] != '10' )				? '<a href="' . append_sid('admin_profile.php?mode=_order&amp;move=-15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'CATEGORY_MOVE_DOWN'	=> ( $category_rows[$i]['category_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_profile.php?mode=_order&amp;move=15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'S_ADD_PROFILE_SUBMIT'	=> "add_profile[$cat_id]",
				'S_ADD_PROFILE_NAME'		=> "profile_name[$cat_id]",

			));
	
			for($j = 0; $j < $total_profile; $j++)
			{
				$class = ($j % 2) ? 'row_class1' : 'row_class2';
				$profile_id = $profile_rows[$j]['profile_id'];

				if ( $profile_rows[$j]['profile_category'] == $cat_id )
				{
					$icon_up	= ( $profile_rows[$j]['profile_order'] != '10' ) ? $lang['move_up'] : '';
					$icon_down	= ( $profile_rows[$j]['profile_order'] != $max_profile['max'.$cat_id] ) ? $lang['move_down'] : '';
					
			
					$template->assign_block_vars('display.catrow.profilerow',	array(
						'CLASS'			=> $class,
						'PROFILE_NAME'	=> $profile_rows[$j]['profile_name'],
						'PROFILE_FIELD'	=> $profile_rows[$j]['profile_field'],
						'ROW_COLOR' => $row_color,
						
						'L_MOVE_UP'		=> $icon_up,
						'L_MOVE_DOWN'		=> $icon_down,
						
						'U_PROFILE_PERMISSIONS' => append_sid('admin_profileauth.php?" . POST_PROFILE_URL . "=$profile_id&amp;adv=1'),
	
						'U_VIEWFORUM' => append_sid($root_path."viewprofile.php?" . POST_PROFILE_URL . "=$profile_id'),
						'U_PROFILE_EDIT' => append_sid('admin_profile.php?mode=edit_profile&amp;" . POST_PROFILE_URL . "=$profile_id'),
						'U_PROFILE_DELETE' => append_sid('admin_profile.php?mode=deleteprofile&amp;" . POST_PROFILE_URL . "=$profile_id'),
						'U_PROFILE_MOVE_UP' => append_sid('admin_profile.php?mode=profile_order&amp;move=-15&amp;" . POST_PROFILE_URL . "=$profile_id'),
						'U_PROFILE_MOVE_DOWN' => append_sid('admin_profile.php?mode=profile_order&amp;move=15&amp;" . POST_PROFILE_URL . "=$profile_id'),
						'U_PROFILE_RESYNC' => append_sid('admin_profile.php?mode=profile_sync&amp;" . POST_PROFILE_URL . "=$profile_id"))
					);
				}
			}
		}
	}
*/	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>