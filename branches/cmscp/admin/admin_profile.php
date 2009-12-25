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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['main']['profile'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	$start			= ( request('start') ) ? request('start', 'num') : 0;
	$start			= ( $start < 0 ) ? 0 : $start;
	$profile_id		= request(POST_PROFILE_URL);
	$cat_id			= request(POST_CATEGORY_URL);
	$cat_type		= request('cat_type');
	$confirm		= request('confirm');
	$mode			= request('mode');
	$move			= request('move');
	$show_index		= '';
	
	if ( !$userauth['auth_games'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_profile.php', true));
	}
	
	if ( request('add_profile') || request('addcategory') )
	{
		$mode = ( request('add_profile') ) ? '_create_field' : '_create_cat';
	
		if ( $mode == '_create_field' )
		{
			list($cat_id) = each(request('add_profile'));
			$cat_id = intval($cat_id);
			
			$profile_name = trim(htmlentities(str_replace("\'", "'", $_POST['profile_name'][$cat_id]), ENT_COMPAT));
		}
	}
	
	function _select_category($select)
	{
		global $db;
		
		$sql = 'SELECT * FROM ' . PROFILE_CATEGORY . ' ORDER BY category_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$category = '<select name="profile_category" id="profile_category" class="post">';
		while ( $row = $db->sql_fetchrow($result) )
		{
			$selected = ( $row['profile_category_id'] == $select ) ? ' selected="selected"' : '';
			$category .= '<option value="' . $row['profile_category_id'] . '"' . $selected . '>' . $row['category_name'] . '&nbsp;</option>';
		}
		$category .= '</select>';
		
		return $category;	
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create_field':
			case '_update_field':
			
				$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
				$template->assign_block_vars('profile_edit', array());
				
				if ( $mode == '_update_field' )
				{
					$profile	= get_data('profile', $profile_id, 0);
					$new_mode	= '_update_field_save';
					
					$profile_name	= $profile['profile_name'];
					$cat_id			= $profile['profile_category'];
				}
				else
				{
					$profile = array (
						'profile_category'		=> _select_category($cat_id),
						'profile_field'			=> '',
						'profile_type'			=> '0',
						'profile_language'		=> '0',
						'profile_show_guest'	=> '0',
						'profile_show_member'	=> '0',
						'profile_show_register'	=> '0',
						'profile_required'		=> '0',
					);
					$new_mode = '_create_field_save';
				}

				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_CATEGORY_URL . '" value="' . $profile_id . '" />';

				$template->assign_vars(array(
					'L_PROFILE_HEAD'		=> sprintf($lang['sprintf_head'], $lang['profile']),
					'L_PROFILE_NEW_EDIT'	=> ( $mode == '_create' ) ? sprintf($lang['sprintf_add'], $lang['profile_field']) : sprintf($lang['sprintf_edit'], $lang['profile_field']),
					
					'L_PROFILE_NAME'		=> sprintf($lang['sprintf_name'], $lang['profile_field']),
					'L_PROFILE_FIELD'		=> $lang['profile_field'],
					'L_PROFILE_CATEGORY'	=> $lang['common_category'],
					'L_PROFILE_LANGUAGE'	=> $lang['common_language'],
					'L_PROFILE_TYPE'		=> sprintf($lang['sprintf_type'], $lang['profile_field']),
					'L_PROFILE_SHOW'		=> $lang['common_view'],
					'L_PROFILE_SGUEST'		=> $lang['profile_show_guest'],
					'L_PROFILE_SMEMBER'		=> $lang['profile_show_member'],
					'L_PROFILE_SREG'		=> $lang['profile_show_reg'],
					'L_PROFILE_REQUIRED'	=> $lang['profile_required'],

					'L_TYPE_TEXT'			=> $lang['profile_type_text'],
					'L_TYPE_AREA'			=> $lang['profile_type_area'],
					
					'L_NO'					=> $lang['common_no'],
					'L_YES'					=> $lang['common_yes'],
					'L_RESET'				=> $lang['common_reset'],
					'L_SUBMIT'				=> $lang['common_submit'],
					
					'PROFILE_NAME'			=> $profile_name,
					'PROFILE_FIELD'			=> str_replace('profile_', '', $profile['profile_field']),
					
					'S_PROFILE_CATEGORY'	=> _select_category($cat_id),
					
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

					'S_FIELDS'		=> $s_hidden_fields,
					'S_PROFILE_ACTION'		=> append_sid('admin_profile.php'),
				));
			
				$template->pparse('body');
				
				break;
			
			case '_create_field_save':
			
				debuge($_POST);
				
				$profile_name		= request('profile_name', 'text');
				$profile_field		= request('profile_field', 'num');
				$profile_category	= request('profile_category', 'num');
				$profile_language	= request('profile_language', 'num');
				$profile_type		= request('profile_type', 'num');
				$profile_sguest		= request('profile_sguest', 'num');
				$profile_smember	= request('profile_smember', 'num');
				$profile_sregister	= request('profile_sregister', 'num');
				$profile_required	= request('profile_required', 'num');
				
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
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_PROFILE, 'create_profile');
				message(GENERAL_MESSAGE, $message);

				break;
			
			case '_update_field_save':
			
				debuge($_POST);
			
				$profile	= get_data('profile', $profile_id, 0);
				$field		= $profile['profile_field'];
				$type		= $profile['profile_type'];
				$category	= $profile['profile_category'];
				
				$profile_name		= request('profile_name', 'text');
				$profile_field		= request('profile_field', 'num');
				$profile_category	= request('profile_category', 'num');
				$profile_language	= request('profile_language', 'num');
				$profile_type		= request('profile_type', 'num');
				$profile_sguest		= request('profile_sguest', 'num');
				$profile_smember	= request('profile_smember', 'num');
				$profile_sregister	= request('profile_sregister', 'num');
				$profile_required	= request('profile_required', 'num');
				
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
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_PROFILE, 'update_profile');
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
//					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_PROFILE, 'acp_profile_delete', $profile['profile_name']);
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
			
//			case '_order_cat':
//				
//				update(PROFILE_CATEGORY, 'game', $move, $game_id);
//				orders('games', -1);
//				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'acp_game_order');
//				$show_index = TRUE;
//				
//				break;
//				
			case '_order_field':
				
				update(PROFILE, 'profile', $move, $profile_id);
				orders('profile', $cat_type);
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_PROFILE, 'acp_profile_order');
				$show_index = TRUE;
				
				break;
				
			case 'addcat':
				
				if ( trim($HTTP_POST_VARS['categoryname']) == '' )
				{
					message(GENERAL_ERROR, "Can't create a category without a name");
				}
	
				$sql = 'SELECT MAX(category_order) AS max_order
							FROM ' . PROFILE_CATEGORY;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
	
				$sql = 'INSERT INTO ' . PROFILE_CATEGORY . " (category_name, category_order)
						VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['categoryname']) . "', $next_order)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
	
				$message = $lang['create_profile_cat'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case 'editcat':

				$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
				$template->assign_block_vars('category_edit', array());
				
				$cat_id = intval($HTTP_GET_VARS[POST_CATEGORY_URL]);
				$row = get_data('profile_category', $cat_id, 0);
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="update_cat" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_CATEGORY_URL . '" value="' . $cat_id . '" />';
	
				$template->assign_vars(array(
					'L_PROFILE_HEAD'			=> $lang['profile_head'],
					'L_PROFILE_EDIT_CATEGORY'	=> $lang['profile_edit_categpry'],
											 
					'L_CATEGORY'		=> $lang['Category'], 
					
	
					'CAT_TITLE'			=> $row['category_name'],
	
					'S_FIELDS' => $s_hidden_fields, 
					
					'S_SUBMIT_VALUE' => $lang['profile_cat_edit'], 
					'S_PROFILE_ACTION' => append_sid('admin_profile.php'))
				);
				
				$template->pparse('body');
	
				break;
	
			case 'update_cat':
				
				if ( trim($HTTP_POST_VARS['categoryname']) == '' )
				{
					message(GENERAL_ERROR, "Can't create a category without a name");
				}
				
				$sql = "UPDATE " . PROFILE_CATEGORY . "
							SET
								category_name = '" . str_replace("\'", "''", $HTTP_POST_VARS['category_name']) . "'
							WHERE profile_category_id = " . intval($HTTP_POST_VARS[POST_CATEGORY_URL]);
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
	
				$message = $lang['update_profile_cat'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			default:
			
				message(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_PROFILE_HEAD'		=> sprintf($lang['sprintf_head'], $lang['profile']),
		'L_PROFILE_CREATE'		=> sprintf($lang['sprintf_creates'], $lang['profile_field']),
		'L_PROFILE_NAME'		=> sprintf($lang['sprintf_name'], $lang['profile']),
		'L_PROFILE_EXPLAIN'		=> $lang['profile_explain'],
		
		'L_CREATE_CATEGORY'	=> $lang['create_category'], 
		
		'L_UPDATE'			=> $lang['common_update'],
		'L_DELETE'			=> $lang['common_delete'],
		
		'L_MOVE_UP'			=> $lang['move_up'], 
		'L_MOVE_DOWN'		=> $lang['move_down'], 
		
		'S_ACTION'		=> append_sid('admin_profile.php'),
	));
	
	$max_order = get_data_max(PROFILE_CATEGORY, 'category_order', '');
	$cats_data = get_data_array(PROFILE_CATEGORY, '', 'category_order', 'ASC');
	
	if ( $cats_data )
	{
		$profile_data = get_data_array(PROFILE, '', 'profile_category, profile_order', '');
		
		for ( $i = 0; $i < count($cats_data); $i++ )
		{
			$cat_id = $cats_data[$i]['profile_category_id'];
			
			$sql = "SELECT MAX(profile_order) AS max$cat_id FROM " . PROFILE . " WHERE profile_category = $cat_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_profile = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$template->assign_block_vars('display.catrow', array( 
				'CATEGORY_ID'			=> $cat_id,
				'CATEGORY_NAME'			=> $cats_data[$i]['category_name'],
				
				'CATEGORY_MOVE_UP'		=> ( $cats_data[$i]['category_order'] != '10' )					? '<a href="' . append_sid('admin_profile.php?mode=_order&amp;move=-15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'CATEGORY_MOVE_DOWN'	=> ( $cats_data[$i]['category_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_profile.php?mode=_order&amp;move=15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_CAT_UPDATE'			=> append_sid('admin_profile.php?mode=_update_cat&amp;' . POST_CATEGORY_URL . '=' .$cat_id),
				'U_CAT_DELETE'			=> append_sid('admin_profile.php?mode=_delete_cat&amp;' . POST_CATEGORY_URL . '=' .$cat_id),
				
				'S_ADD_PROFILE_SUBMIT'	=> "add_profile[$cat_id]",
				'S_ADD_PROFILE_NAME'		=> "profile_name[$cat_id]",

			));
	
			for($j = 0; $j < count($profile_data); $j++)
			{
				$profile_id = $profile_data[$j]['profile_id'];

				if ( $profile_data[$j]['profile_category'] == $cat_id )
				{
					$template->assign_block_vars('display.catrow.profilerow',	array(
						'CLASS'				=> ( $j % 2 ) ? 'row_class1' : 'row_class2',
						
						'PROFILE_NAME'		=> $profile_data[$j]['profile_name'],
						'PROFILE_FIELD'		=> $profile_data[$j]['profile_field'],
						
						'PROFILE_MOVE_UP'	=> ( $profile_data[$j]['profile_order'] != '10' )							? '<a href="' . append_sid('admin_profile.php?mode=_order_field&amp;cat_type=' . $cat_id . '&amp;move=-15&amp;' . POST_PROFILE_URL . '=' . $profile_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
						'PROFILE_MOVE_DOWN'	=> ( $profile_data[$j]['profile_order'] != $max_profile['max'.$cat_id] )	? '<a href="' . append_sid('admin_profile.php?mode=_order_field&amp;cat_type=' . $cat_id . '&amp;move=15&amp;' . POST_PROFILE_URL . '=' . $profile_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

						'U_PROFILE_PERMIS' => append_sid('admin_profileauth.php?' . POST_PROFILE_URL . '=' .$profile_id . '&amp;adv=1'),
						'U_PROFILE_UPDATE' => append_sid('admin_profile.php?mode=_update_field&amp;' . POST_PROFILE_URL . '=' . $profile_id),
						'U_PROFILE_DELETE' => append_sid('admin_profile.php?mode=_delete_field&amp;' . POST_PROFILE_URL . '=' . $profile_id),
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
			$cat_id = $category_rows[$i]['profile_category_id'];
			
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
		
				'CATEGORY_MOVE_UP'		=> ( $category_rows[$i]['category_order'] != '10' )				? '<a href="' . append_sid('admin_profile.php?mode=_order&amp;move=-15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'CATEGORY_MOVE_DOWN'	=> ( $category_rows[$i]['category_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_profile.php?mode=_order&amp;move=15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
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