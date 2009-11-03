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
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	include($root_path . 'includes/functions_admin.php');
	
	if ( !$userauth['auth_games'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_profile.php', true));
	}
	
	if ( isset($HTTP_POST_VARS[POST_PROFILE_URL]) || isset($HTTP_GET_VARS[POST_PROFILE_URL]) )
	{
		$profile_id = ( isset($HTTP_POST_VARS[POST_PROFILE_URL]) ) ? intval($HTTP_POST_VARS[POST_PROFILE_URL]) : intval($HTTP_GET_VARS[POST_PROFILE_URL]);
	}
	else
	{
		$profile_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS[POST_CATEGORY_URL]) || isset($HTTP_GET_VARS[POST_CATEGORY_URL]) )
	{
		$cat_id = ( isset($HTTP_POST_VARS[POST_CATEGORY_URL]) ) ? intval($HTTP_POST_VARS[POST_CATEGORY_URL]) : intval($HTTP_GET_VARS[POST_CATEGORY_URL]);
	}
	else
	{
		$cat_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
	}
	else
	{
		if (isset($HTTP_POST_VARS['add']))
		{
			$mode = 'add';
		}
		else
		{
			$mode = '';
		}
	}
	
	if( isset($HTTP_POST_VARS['add_profile']) || isset($HTTP_POST_VARS['addcategory']) )
	{
		$mode = ( isset($HTTP_POST_VARS['add_profile']) ) ? 'add_profile' : 'addcat';
	
		if ( $mode == 'add_profile' )
		{
			list($cat_id) = each($HTTP_POST_VARS['add_profile']);
			$cat_id = intval($cat_id);

			$profile_name = stripslashes($HTTP_POST_VARS['profile_name'][$cat_id]);
		}
	}
	
	function _select_category($select)
	{
		global $db;
		
		$sql = 'SELECT * FROM ' . PROFILE_CATEGORY . ' ORDER BY category_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$category = '<select name="profile_category" class="post">';
		while ( $row = $db->sql_fetchrow($result) )
		{
			$selected = ( $row['profile_category_id'] == $select ) ? ' selected="selected"' : '';
			$category .= '<option value="' . $row['profile_category_id'] . '"' . $selected . '>' . $row['category_name'] . '</option>';
		}
		$category .= '</select>';
		
		return $category;	
	}
	
	$show_index = '';
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case 'add_profile':
			case 'edit_profile':
				
				if ( $mode == 'edit_profile' )
				{
					$profile	= get_data('profile', $profile_id, 0);
					$new_mode	= 'updateprofile';
					
					$profile_name	= $profile['profile_name'];
					$cat_id			= $profile['profile_category'];
				}
				else if ( $mode == 'add_profile' )
				{
					$profile = array (
						'profile_category'		=> _select_category($cat_id),
						'profile_field'			=> '',
						'profile_type'			=> '0',
						'profile_show_guest'	=> '0',
						'profile_show_member'	=> '0',
						'profile_show_register'	=> '0',
					);
					
					$new_mode = 'createprofile';
				}
				$template->set_filenames(array('body' => 'style/acp_profile.tpl'));
				$template->assign_block_vars('profile_edit', array());

				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_CATEGORY_URL . '" value="' . $profile_id . '" />';

				$template->assign_vars(array(
					'L_PROFILE_HEAD'		=> $lang['profile_head'],
					'L_PROFILE_NEW_EDIT'	=> ($mode == 'add_profile') ? $lang['profile_add'] : $lang['profile_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_PROFILE_NAME'		=> $lang['profile_name'],
					'L_PROFILE_FIELD'		=> $lang['profile_field'],
					'L_PROFILE_CATEGORY'	=> $lang['profile_categpry'],
					'L_PROFILE_TYPE'		=> $lang['profile_type'],
					'L_PROFILE_LANGUAGE'	=> $lang['profile_language'],
					'L_PROFILE_SGUEST'		=> $lang['profile_sguest'],
					'L_PROFILE_SMEMBER'		=> $lang['profile_smember'],
					'L_PROFILE_SREG'		=> $lang['profile_sreg'],

					'L_TYPE_TEXT'			=> $lang['profile_text'],
					'L_TYPE_AREA'			=> $lang['profile_area'],
					
					'L_SUBMIT'				=> $lang['common_submit'],
					'L_RESET'				=> $lang['common_reset'],
					'L_YES'					=> $lang['common_yes'],
					'L_NO'					=> $lang['common_no'],
					
					'PROFILE_NAME'			=> $profile_name,
					'PROFILE_FIELD'			=> str_replace('profile_', '', $profile['profile_field']),
					
					'S_PROFILE_CATEGORY'	=> _select_category($cat_id),
					
					'S_CHECKED_LANG_YES'	=> ( $profile['profile_language'] ) ? ' checked="checked"' : '',
					'S_CHECKED_LANG_NO'		=> ( !$profile['profile_language'] ) ? ' checked="checked"' : '',
					'S_CHECKED_TYPE_TEXT'	=> ( $profile['profile_type'] == '0' ) ? ' checked="checked"' : '',
					'S_CHECKED_TYPE_AREA'	=> ( $profile['profile_type'] == '1' ) ? ' checked="checked"' : '',
					'S_CHECKED_SGUEST_YES'	=> ( $profile['profile_show_guest'] ) ? ' checked="checked"' : '',
					'S_CHECKED_SGUEST_NO'	=> ( !$profile['profile_show_guest'] ) ? ' checked="checked"' : '',
					'S_CHECKED_SMEMBER_YES'	=> ( $profile['profile_show_member'] ) ? ' checked="checked"' : '',
					'S_CHECKED_SMEMBER_NO'	=> ( !$profile['profile_show_member'] ) ? ' checked="checked"' : '',
					'S_CHECKED_SREG_YES'	=> ( $profile['profile_show_register'] ) ? ' checked="checked"' : '',
					'S_CHECKED_SREG_NO'		=> ( !$profile['profile_show_register'] ) ? ' checked="checked"' : '',

					
					
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_NAVI_ACTION'			=> append_sid('admin_profile.php'),
				));
			
				$template->pparse('body');
				
			break;
			
			case 'createprofile':
			
				$profile_name		= ( isset($HTTP_POST_VARS['profile_name']) )		? str_replace("\'", "''", $HTTP_POST_VARS['profile_name']) : '';
				$profile_field		= ( isset($HTTP_POST_VARS['profile_field']) )		? str_replace("\'", "''", $HTTP_POST_VARS['profile_field']) : '';
				$profile_category	= ( isset($HTTP_POST_VARS['profile_category']) )	? intval($HTTP_POST_VARS['profile_category']) : 0;
				$profile_language	= ( isset($HTTP_POST_VARS['profile_language']) )	? intval($HTTP_POST_VARS['profile_language']) : 0;
				$profile_type		= ( isset($HTTP_POST_VARS['profile_type']) )		? intval($HTTP_POST_VARS['profile_type']) : 0;
				$profile_sguest		= ( isset($HTTP_POST_VARS['profile_sguest']) )		? intval($HTTP_POST_VARS['profile_sguest']) : 0;
				$profile_smember	= ( isset($HTTP_POST_VARS['profile_smember']) )		? intval($HTTP_POST_VARS['profile_smember']) : 0;
				$profile_sregister	= ( isset($HTTP_POST_VARS['profile_sreg']) )		? intval($HTTP_POST_VARS['profile_sreg']) : 0;
				
				if ( $profile_name == '' || $profile_field == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
	
				$sql = 'SELECT MAX(profile_order) AS max_order FROM ' . PROFILE . ' WHERE profile_category = ' . $profile_category;
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);

				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				$sql = 'INSERT INTO ' . PROFILE . " (profile_name, profile_field, profile_category, profile_type, profile_language, profile_show_guest, profile_show_member, profile_show_register, profile_order)
					VALUES ('" . str_replace("\'", "''", $profile_name) . "', 'profile_" . str_replace("\'", "''", $profile_field) . "', '" . $profile_category . "', '" . $profile_type . "', '" . $profile_language . "', '" . $profile_sguest . "', '" . $profile_smember . "', '" . $profile_sregister . "', $next_order)";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( !$profile_type )
				{
					$sql = 'ALTER TABLE ' . PROFILE_DATA . ' ADD `profile_' . $profile_field . '` VARCHAR( 255 ) NOT NULL';
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					$sql = 'ALTER TABLE ' . PROFILE_DATA . ' ADD `profile_' . $profile_field . '` TEXT NOT NULL';
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_PROFILE, 'acp_profile_add', $profile_name);
	
				$message = $lang['create_profile'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'updateprofile':
			
				$row		= get_data('profile', $profile_id, 0);
				$p_cat		= $row['profile_category'];
				$p_type		= $row['profile_type'];
				$p_field	= $row['profile_field'];
			
				$profile_name		= ( isset($HTTP_POST_VARS['profile_name']) )		? str_replace("\'", "''", $HTTP_POST_VARS['profile_name']) : '';
				$profile_field		= ( isset($HTTP_POST_VARS['profile_field']) )		? str_replace("\'", "''", $HTTP_POST_VARS['profile_field']) : '';
				$profile_category	= ( isset($HTTP_POST_VARS['profile_category']) )	? intval($HTTP_POST_VARS['profile_category']) : 0;
				$profile_type		= ( isset($HTTP_POST_VARS['profile_type']) )		? intval($HTTP_POST_VARS['profile_type']) : 0;
				$profile_sguest		= ( isset($HTTP_POST_VARS['profile_sguest']) )		? intval($HTTP_POST_VARS['profile_sguest']) : 0;
				$profile_smember	= ( isset($HTTP_POST_VARS['profile_smember']) )		? intval($HTTP_POST_VARS['profile_smember']) : 0;
				
				if ( $profile_name == '' || $profile_field == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				
				$sql_order = '';
				if ( $p_cat != $profile_category )
				{
					$sql = 'SELECT MAX(profile_order) AS max_order FROM ' . PROFILE . ' WHERE profile_category = ' . $profile_category;
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
	
					$max_order = $row['max_order'];
					$next_order = $max_order + 10;
					
					$sql_order .= ', profile_order = ' . $next_order;
				}
				
				if ( $p_type != $profile_type )
				{
					if ( $profile_type )
					{
						$sql = 'ALTER TABLE ' . PROFILE_DATA . ' CHANGE `' . $p_field . '` `profile_' . str_replace("\'", "''", $profile_field) . '` TEXT NOT NULL';
						if ( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					else
					{
						$sql = 'ALTER TABLE ' . PROFILE_DATA . ' CHANGE `' . $p_field . '` `profile_' . str_replace("\'", "''", $profile_field) . '` VARCHAR( 255 ) NOT NULL';
						if ( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
				}
				
				$sql = "UPDATE " . PROFILE . " SET
							profile_name		= '" . str_replace("\'", "''", $profile_name) . "',
							profile_field		= 'profile_" . str_replace("\'", "''", $profile_field) . "',
							profile_category	= $profile_category,
							profile_type		= $profile_type,
							profile_show_guest	= $profile_sguest,
							profile_show_member	= $profile_smember
							$sql_order
						WHERE profile_id = " . $profile_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_PROFILE, 'acp_profile_edit');
				
				$message = $lang['update_profile'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $profile_id && $confirm )
				{	
					$profile = get_data('profile', $profile_id, 0);

					$sql = 'DELETE FROM ' . NAVIGATION . " WHERE profile_id = $profile_id";
					$result = $db->sql_query($sql);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_PROFILE, 'acp_profile_delete', $profile['profile_name']);
					
					$message = $lang['delete_profile'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $profile_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_CATEGORY_URL . '" value="' . $profile_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_profile'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid('admin_profile.php'),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_profile']);
				}
				
				$template->pparse('body');
				
				break;
			
			case 'order_main':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NAVIGATION . " SET profile_order = profile_order + $move WHERE profile_id = $profile_id";
				$result = $db->sql_query($sql);
		
				renumber_order('profile', NAVI_MAIN);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_PROFILE, 'acp_profile_order', NAVI_MAIN);
				
				$show_index = TRUE;
	
				break;
				
			case 'addcat':
				
				if ( trim($HTTP_POST_VARS['categoryname']) == '' )
				{
					message_die(GENERAL_ERROR, "Can't create a category without a name");
				}
	
				$sql = 'SELECT MAX(category_order) AS max_order
							FROM ' . PROFILE_CATEGORY;
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
	
				$sql = 'INSERT INTO ' . PROFILE_CATEGORY . " (category_name, category_order)
						VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['categoryname']) . "', $next_order)";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
	
				$message = $lang['create_profile_cat'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
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
	
					'S_HIDDEN_FIELDS' => $s_hidden_fields, 
					
					'S_SUBMIT_VALUE' => $lang['profile_cat_edit'], 
					'S_PROFILE_ACTION' => append_sid('admin_profile.php'))
				);
				
				$template->pparse('body');
	
				break;
	
			case 'update_cat':
				
				if ( trim($HTTP_POST_VARS['categoryname']) == '' )
				{
					message_die(GENERAL_ERROR, "Can't create a category without a name");
				}
				
				$sql = "UPDATE " . PROFILE_CATEGORY . "
							SET
								category_name = '" . str_replace("\'", "''", $HTTP_POST_VARS['category_name']) . "'
							WHERE profile_category_id = " . intval($HTTP_POST_VARS[POST_CATEGORY_URL]);
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
	
				$message = $lang['update_profile_cat'] . sprintf($lang['click_return_profile'], '<a href="' . append_sid('admin_profile.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
				
			default:
			
				message_die(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
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
		'L_PROFILE_HEAD'	=> $lang['profile_head'],
		'L_PROFILE_EXPLAIN'	=> $lang['profile_explain'],
		
		
		
		
		'L_CREATE_PROFILE'	=> $lang['create_profile'], 
		'L_CREATE_CATEGORY'	=> $lang['create_category'], 
		
		'L_EDIT'			=> $lang['edit'],
		'L_SETTINGS'		=> $lang['settings'],
		'L_DELETE'			=> $lang['delete'],
		
		'L_MOVE_UP'			=> $lang['move_up'], 
		'L_MOVE_DOWN'		=> $lang['move_down'], 
		
		'S_TEAM_ACTION'		=> append_sid('admin_profile.php'),
	));
	
	$sql = 'SELECT MAX(category_order) AS max FROM ' . PROFILE_CATEGORY;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$max_order = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT * FROM ' . PROFILE_CATEGORY . ' ORDER BY category_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $total_categories = $db->sql_numrows($result) )
	{
		$category_rows = $db->sql_fetchrowset($result);
		
		$sql = 'SELECT *
					FROM ' . PROFILE . '
					ORDER BY profile_category, profile_order';
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_profile = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$icon_up	= ( $category_rows[$i]['category_order'] != '10' ) ? $lang['move_up'] : '';
			$icon_down	= ( $category_rows[$i]['category_order'] != $max_order['max'] ) ? $lang['move_down'] : '';
	
			$template->assign_block_vars('display.catrow', array( 
				'S_ADD_PROFILE_SUBMIT'	=> "add_profile[$cat_id]",
				'S_ADD_PROFILE_NAME'		=> "profile_name[$cat_id]",
				
				'CATEGORY_ID'			=> $cat_id,
				'CATEGORY_NAME'			=> $category_rows[$i]['category_name'],
				
				'L_MOVE_UP'				=> $icon_up,
				'L_MOVE_DOWN'			=> $icon_down,
				
				'U_CAT_EDIT'			=> append_sid('admin_profile.php?mode=editcat&amp;" . POST_CATEGORY_URL . "=$cat_id'),
//				'U_CAT_DELETE'			=> append_sid('admin_profile.php?mode=deletecat&amp;" . POST_CATEGORY_URL . "=$cat_id'),
				'U_CAT_MOVE_UP'			=> append_sid('admin_profile.php?mode=cat_order&amp;move=-15&amp;" . POST_CATEGORY_URL . "=$cat_id'),
				'U_CAT_MOVE_DOWN'		=> append_sid('admin_profile.php?mode=cat_order&amp;move=15&amp;" . POST_CATEGORY_URL . "=$cat_id'),
				'U_VIEWCAT'				=> append_sid($root_path."profile.php?" . POST_CATEGORY_URL . "=$cat_id"))
			);
	
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
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>