<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ( $userauth['auth_user'] || $userdata['user_level'] == ADMIN )
	{
		$module['users']['set'] = $filename;
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
//	include($root_path . 'includes/functions_selects.php');
	
	if ( !$userauth['auth_user'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_user.php", true));
	}
	
	if ( isset($HTTP_POST_VARS[POST_USERS_URL]) || isset($HTTP_GET_VARS[POST_USERS_URL]) )
	{
		$user_id = ( isset($HTTP_POST_VARS[POST_USERS_URL]) ) ? intval($HTTP_POST_VARS[POST_USERS_URL]) : intval($HTTP_GET_VARS[POST_USERS_URL]);
	}
	else
	{
		$user_id = 0;
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
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
	
	$group_auth_fields	= get_authlist();
	$group_auth_levels	= array('DISALLOWED', 'ALLOWED', 'SPECIAL', 'DEFAULT');
	$group_auth_const	= array(AUTH_DISALLOWED, AUTH_ALLOWED, AUTH_SPECIAL, AUTH_DEFAULT);
	
	$show_index = '';
		
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
			
				$template->set_filenames(array('body' => './../admin/style/acp_user.tpl'));
				$template->assign_block_vars('user_edit', array());
				
				if ( $mode == 'edit' )
				{
					$user		= get_data('user', $user_id, 0);
					$user_data	= get_data('profile_data', $user_id, 0);
					$new_mode	= 'edituser';
					
					_debug_post($user_data);
					
					$template->assign_block_vars('user_edit.edituser', array());
					
					if ( $userdata['user_level'] < $user['user_level'] )
					{
						message_die(GENERAL_ERROR, $lang['auth_fail']);
					}
				}
				else if ( $mode == 'add' )
				{
					$user = array (
						'username'				=> trim($HTTP_POST_VARS['username']),
						'user_regdate'			=> time(),
						'user_email'			=> '',
						'user_email_confirm'	=> '',
						'new_password'			=> '',
						'password_confirm'		=> '',
						'user_founder'			=> '0',
						'user_lastvisit'		=> '',
					);
					
					$new_mode = 'adduser';
					
					$template->assign_block_vars('adduser', array());
				}
				
				if ( $userdata['user_founder'] )
				{
					$check_founder_no	= ( !$user['user_founder'] ) ? ' checked="checked"' : '';
					$check_founder_yes	= ( $user['user_founder'] ) ? ' checked="checked"' : '';
				}
				else
				{
					$check_founder_no	= ( !$user['user_founder'] ) ? ' disabled checked="checked"' : ' disabled';
					$check_founder_yes	= ( $user['user_founder'] ) ? ' disabled checked="checked"' : ' disabled';
				}
				
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
				
					if ( $total_profile = $db->sql_numrows($result) )
					{
						$profile_rows = $db->sql_fetchrowset($result);
					}
					
					for ( $i = 0; $i < $total_categories; $i++ )
					{
						$cat_id = $category_rows[$i]['profile_category_id'];
				
						$template->assign_block_vars('user_edit.catrow', array( 
							'CATEGORY_ID'			=> $cat_id,
							'CATEGORY_NAME'			=> $category_rows[$i]['category_name'],
						));
						
						for($j = 0; $j < $total_profile; $j++)
						{
							$profile_id = $profile_rows[$j]['profile_id'];
							
							if ( $profile_rows[$j]['profile_category'] == $cat_id )
							{
								$value = $user_data[$profile_rows[$j]['profile_field']];
								$template->assign_block_vars('user_edit.catrow.profilerow',	array(
									'NAME'	=> $profile_rows[$j]['profile_name'],
									'FIELD' => '<input class="post" type="text" name="'.$profile_rows[$j]['profile_field'].'" value="'.$value.'">',
								));
							}
						}
					}
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />';
				
				$template->assign_vars(array(
					'L_USER_HEAD'			=> $lang['user_head'],
					'L_USER_NEW_EDIT'		=> ($mode == 'add') ? $lang['user_new_add'] : $lang['user_edit'],
					'L_USER_GROUP'			=> $lang['user_group'],
					'L_USER_AUTHS'			=> $lang['user_auths'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_USERNAME'			=> $lang['username'],
					'L_REGISTER'			=> $lang['user_register'],
					'L_EMAIL'				=> $lang['user_email'],
					'L_EMAIL_CONFIRM'		=> $lang['user_email_confirm'],
					
					'L_PASSWORD'			=> $lang['user_password'],
					'L_PASSWORD_CONFIRM'	=> $lang['user_password_confirm'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'PASS_1'				=> sprintf($lang['user_pass_random'], random_password(7, 10, false, false)),
					'PASS_2'				=> sprintf($lang['user_pass_random'], random_password(7, 10, false, true, false)),
					'PASS_3'				=> sprintf($lang['user_pass_random'], random_password()),
					'PASS_4'				=> sprintf($lang['user_pass_random'], random_password(7, 14, true, true, false)),
					'PASS_5'				=> sprintf($lang['user_pass_random'], random_password(7, 14, true, true)),
					'PASS_6'				=> sprintf($lang['user_pass_random'], random_password(7, 14, true, true, true, true)),
					
					'USERNAME'				=> $user['username'],
					
					'JOINED'				=> create_date($userdata['user_dateformat'], $user['user_regdate'], $userdata['user_timezone']),
					'LAST_VISIT'			=> create_date($userdata['user_dateformat'], $user['user_lastvisit'], $userdata['user_timezone']),
					
					'USER_EMAIL'			=> $user['user_email'],
					
					'S_CHECKED_FOUNDER_NO'	=> $check_founder_no,
					'S_CHECKED_FOUNDER_YES'	=> $check_founder_yes,
					
					'S_USER_EDIT'			=> append_sid("admin_user.php?mode=edit&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_GROUP'			=> append_sid("admin_user.php?mode=groups&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_AUTHS'			=> append_sid("admin_user.php?mode=auths&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_ACTION'			=> append_sid("admin_user.php"),
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields
				));
			
				$template->pparse('body');
				
			break;
			
			case 'adduser':
				
				_debug_post($_POST);
				
				include($root_path . 'includes/functions_validate.php');
			
				$username				= ( isset($HTTP_POST_VARS['username']) )			? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
				$user_email				= ( isset($HTTP_POST_VARS['user_email']) )			? trim(htmlspecialchars(strtolower($HTTP_POST_VARS['user_email']))) : '';
				$email_confirm			= ( isset($HTTP_POST_VARS['email_confirm']) )		? trim(htmlspecialchars(strtolower($HTTP_POST_VARS['email_confirm']))) : '';
				$new_password			= ( isset($HTTP_POST_VARS['new_password']) )		? trim(htmlspecialchars($HTTP_POST_VARS['new_password'])) : '';
				$password_confirm		= ( isset($HTTP_POST_VARS['password_confirm']) )	? trim(htmlspecialchars($HTTP_POST_VARS['password_confirm'])) : '';
				
				$error = ''; 
				$error_msg = '';
				
				$username_sql = '';
				if ( !$username )
				{
					$error = TRUE;
				}
				else
				{
					$result = validate_username($username);
					if ( $result['error'] )
					{
						$error = TRUE;
						$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $result['error_msg'];
					}
		
					if (!$error)
					{
						$username_sql = str_replace("\'", "''", $username);
					}
				}
				
				if (!empty($HTTP_POST_VARS['language']))
				{
					if (preg_match('/^[a-z_]+$/i', $HTTP_POST_VARS['language']))
					{
						$user_lang = htmlspecialchars($HTTP_POST_VARS['language']);
					}
					else
					{
						$error = true;
						$error_msg = $lang['Fields_empty'];
					}
				}
				else
				{
					$user_lang = $config['default_lang'];
				}
				
				if ( $user_email == $email_confirm )
				{
					$result = validate_email($user_email);
					if ( $result['error'] )
					{
						
			
						$error = TRUE;
						$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $result['error_msg'];
					}
				}
				else
				{
					$error = TRUE;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . 'ungleiche mail addy';
				}
				
				if ( $HTTP_POST_VARS['pass'] == '1' )
				{
					if ( ( empty($new_password) && !empty($password_confirm) ) || ( !empty($new_password) && empty($password_confirm) ) || ( $new_password != $password_confirm ) )
					{
						$error = TRUE;
						$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['Password_mismatch'];
					}
					
					$new_password = md5($new_password);
				}
				else
				{
					if ( $HTTP_POST_VARS['random'] == '0' )
					{
						$password = random_password(7, 10, false, false);
					}
					else if ( $HTTP_POST_VARS['random'] == '1' )
					{
						$password = random_password(7, 10, false, true, false);
					}
					else if ( $HTTP_POST_VARS['random'] == '2' )
					{
						$password = random_password();
					}
					else if ( $HTTP_POST_VARS['random'] == '3' )
					{
						$password = random_password(7, 14, true, true, false);
					}
					else if ( $HTTP_POST_VARS['random'] == '4' )
					{
						$password = random_password(7, 14, true, true);
					}
					else if ( $HTTP_POST_VARS['random'] == '5' )
					{
						$password = random_password(7, 14, true, true, true, true);
						
					}
					$new_password = md5($password);
				}
				
				
				if ( !$error )
				{
					$sql = 'SELECT MAX(user_id) AS total
								FROM ' . USERS;
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
					}
					
					if ( !($row = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
					}
					
					$user_id = $row['total'] + 1;
					
					$sql = "INSERT INTO " . USERS . " (user_id, username, user_password, user_email, user_regdate) VALUES ($user_id, '$username_sql', '$new_password', '$user_email', " . time() . ")";
					if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
					{
						message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
					}
			
					$sql = "INSERT INTO " . GROUPS . " (group_name, group_type, group_description, group_single_user)
						VALUES ('$username_sql', 2, 'Personal User', 1)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
					}
			
					$group_id = $db->sql_nextid();
			
					$sql = "INSERT INTO " . GROUPS_USERS . " (user_id, group_id, user_pending)
						VALUES ($user_id, $group_id, 0)";
					if( !($result = $db->sql_query($sql, END_TRANSACTION)) )
					{
						message_die(GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['Account_added'];
					$email_template = 'user_welcome';

					include($root_path . 'includes/emailer.php');
					$emailer = new emailer($config['smtp_delivery']);
		
					$emailer->from($config['page_email']);
					$emailer->replyto($config['page_email']);
		
					$emailer->use_template($email_template, stripslashes($user_lang));
					$emailer->email_address($user_email);
					$emailer->set_subject(sprintf($lang['Welcome_subject'], $config['sitename']));

					$emailer->assign_vars(array(
						'SITENAME' => $config['sitename'],
						'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $config['sitename']),
						'USERNAME' => substr(str_replace("\'", "'", $username), 0, 25),
						'PASSWORD' => $new_password,
						'EMAIL_SIG' => str_replace('<br>', "\n", "-- \n" . $config['page_email_sig']),
					));
			
					$emailer->send();
					$emailer->reset();
					
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_USER, 'acp_user_add');
					
				//	$oCache -> sCachePath = './../cache/';
				//	$oCache -> deleteCache('display_subnavi_user');
		
					$message = $lang['create_user'] . '<br><br>' . sprintf($lang['click_return_user'], '<a href="' . append_sid("admin_user.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}
				else
				{
					message_die(GENERAL_ERROR, $error_msg, '');
				}

			break;
			
			case 'edituser':
				
				_debug_poste($_POST);
			
				$sql = 'SELECT * FROM ' . USERS . " WHERE user_id = $user_id";
				$result = $db->sql_query($sql);
		
				if (!($user_info = $db->sql_fetchrow($result)))
				{
					message_die(GENERAL_MESSAGE, $lang['user_not_exist']);
				}
			
				$user_data = $user_info;
				$user_ordert = $_POST;
				
				$new = array_diff($user_ordert, $user_data);

				unset($new[mode]);
				unset($new[send]);
				
				$log_data = '';
				foreach ($new as $index => $wert)
				{
					$log_data .= $index . ': ' . $wert . ', ';
				}
				$log_data = trim($log_data, ', ');
				
//				_debug_post($_POST);
				
				if (!empty($HTTP_POST_VARS['game_image']))
				{
					$sql = 'SELECT * FROM ' . GAMES . " WHERE game_image = '" . str_replace("\'", "''", $HTTP_POST_VARS['game_image']) . "'";
					$result = $db->sql_query($sql);
					
					if (!($game_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['user_not_exist']);
					}
					
					$user_game = ($game_info['game_id']) ? $game_info['game_id'] : '-1';
				}
				else
				{
					$user_game = '-1';
				}
				
				$user_logo_upload		= ( $HTTP_POST_FILES['user_logo']['tmp_name'] != "none") ? $HTTP_POST_FILES['user_logo']['tmp_name'] : '';
				$user_logo_name			= ( !empty($HTTP_POST_FILES['user_logo']['name']) ) ? $HTTP_POST_FILES['user_logo']['name'] : '';
				$user_logo_size			= ( !empty($HTTP_POST_FILES['user_logo']['size']) ) ? $HTTP_POST_FILES['user_logo']['size'] : 0;
				$user_logo_filetype		= ( !empty($HTTP_POST_FILES['user_logo']['type']) ) ? $HTTP_POST_FILES['user_logo']['type'] : '';
				
				$user_logos_upload		= ( $HTTP_POST_FILES['user_logos']['tmp_name'] != "none") ? $HTTP_POST_FILES['user_logos']['tmp_name'] : '';
				$user_logos_name		= ( !empty($HTTP_POST_FILES['user_logos']['name']) ) ? $HTTP_POST_FILES['user_logos']['name'] : '';
				$user_logos_size		= ( !empty($HTTP_POST_FILES['user_logos']['size']) ) ? $HTTP_POST_FILES['user_logos']['size'] : 0;
				$user_logos_filetype	= ( !empty($HTTP_POST_FILES['user_logos']['type']) ) ? $HTTP_POST_FILES['user_logos']['type'] : '';

				$logo_sql = '';
				$logos_sql = '';
				
				if ( isset($HTTP_POST_VARS['logodel']) && $mode == 'edituser' )
				{
					$logo_sql = user_logo_delete('n', $user_info['user_logo_type'], $user_info['user_logo']);
				}
				else if (!empty($user_logo_upload) && $settings['user_logo_upload'])
				{
					$logo_sql = user_logo_upload($mode, 'n', $user_info['user_logo'], $user_info['user_logo_type'], $user_logo_upload, $user_logo_name, $user_logo_size, $user_logo_filetype);
				}
				
				if ( $logo_sql == '' )
				{
					$logo_sql = '';
				}

				if ( isset($HTTP_POST_VARS['logosdel']) && $mode == 'edituser' )
				{
					$logos_sql = user_logo_delete('s', $user_info['user_logos_type'], $user_info['user_logos']);
				}
				else if (!empty($user_logos_upload) && $settings['user_logos_upload'])
				{
					$logos_sql = user_logo_upload($mode, 's', $user_info['user_logos'], $user_info['user_logos_type'], $user_logos_upload, $user_logos_name, $user_logos_size, $user_logos_filetype);
				}
				
				if ( $logos_sql == '' )
				{
					$logos_sql = '';
				}
				
				$sql = "UPDATE " . USERS . " SET
							user_name = '" . str_replace("\'", "''", $HTTP_POST_VARS['user_name']) . "',
							user_description = '" . str_replace("\'", "''", $HTTP_POST_VARS['user_description']) . "',
							user_game = $user_game,
							$logo_sql
							$logos_sql
							user_navi = '" . intval($HTTP_POST_VARS['user_navi']) . "',
							user_join = '" . intval($HTTP_POST_VARS['user_join']) . "',
							user_fight = '" . intval($HTTP_POST_VARS['user_fight']) . "',
							user_show_wars = '" . intval($HTTP_POST_VARS['user_show_wars']) . "',
							user_show_awards = '" . intval($HTTP_POST_VARS['user_show_awards']) . "',
							user_show = '" . intval($HTTP_POST_VARS['user_show']) . "',
							user_view = '" . intval($HTTP_POST_VARS['user_view']) . "',
							user_update = " . time() . "
						WHERE user_id = $user_id";
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_USER, 'acp_user_edit');
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('display_subnavi_user');
				
				$message = $lang['user_update'] . '<br><br>' . sprintf($lang['click_return_user'], '<a href="' . append_sid("admin_user.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
			break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $user_id && $confirm )
				{
					$user = get_data('user', $user_id, 0);
				
					if ( $userdata['user_level'] < $user['user_level'] )
					{
						message_die(GENERAL_ERROR, $lang['auth_fail']);
					}			

					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_USER, 'ACP_USER_DELETE', $user_info['user_name']);
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('display_subnavi_user');
					
					$message = $lang['delete_user'] . '<br><br>' . sprintf($lang['click_return_user'], '<a href="' . append_sid("admin_user.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
		
				}
				else if ( $user_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_user'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_user.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_user']);
				}
			
				$template->pparse('body');
				
			break;
			
			case 'groups':
			
				$template->set_filenames(array('body' => './../admin/style/acp_user.tpl'));
				$template->assign_block_vars('user_groups', array());
				
				$user = get_data('user', $user_id, 0);
				
				if ( $userdata['user_level'] < $user['user_level'] )
				{
					message_die(GENERAL_ERROR, $lang['auth_fail']);
				}
				
				$sql_groups = 'SELECT group_id, group_name, group_type, group_access
							FROM ' . GROUPS . '
							WHERE group_single_user = 0
						ORDER BY group_order';
				if ( !($result_groups = $db->sql_query($sql_groups)) )
				{
					message_die(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql_groups);
				}
				
				while ( $row_groups = $db->sql_fetchrow($result_groups) )
				{
					
					$group_id	= $row_groups['group_id'];
					$group_name	= $row_groups['group_name'];

					$sql = 'SELECT user_id, user_pending, group_mod
								FROM ' . GROUPS_USERS . '
								WHERE user_id = ' . $user_id . '
									AND group_id = ' . $group_id;
					if ( !($result_user = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
					}
					
					$member = ( $row = $db->sql_fetchrow($result_user) ) ? TRUE : FALSE;
					$neg_group_id = -1 * $group_id;
					
					if ( $row_groups['group_type'] == GROUP_SYSTEM )
					{
						$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'disabled checked="checked"' : 'disabled';
						$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
						$s_mod_group		= ( $row['group_mod'] == '1' ) ? 'disabled checked' : 'disabled';
					}
					else if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
					{
						$s_assigned_group	= ( $member && !$row['user_pending'] ) ? ' checked="checked"' : '';
						$s_unassigned_group	= ( $member ) ? '' : ' checked="checked"';
						$s_mod_group		= ( $row['group_mod'] == '1' ) ? ' checked ' : '';
					}
					else if ( $userdata['user_id'] == $user['user_id'] )
					{
						$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'checked="checked"' : '';
						$s_unassigned_group	= ( $member ) ? '' : 'checked="checked"';
						$s_mod_group		= ( $row['group_mod'] == '1' ) ? ' checked' : '';
					}
					else if ( $userdata['user_level'] > $row_groups['group_access'] )
					{
						$s_assigned_group	= ( $member && !$row['user_pending'] ) ? ' checked="checked"' : ' ';
						$s_unassigned_group	= ( $member ) ? ' ' : ' checked="checked"';
						$s_mod_group		= ( $row['group_mod'] == '1' ) ? ' checked ' : '';
					}
					else
					{
						$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'disabled checked="checked"' : 'disabled';
						$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
						$s_mod_group		= ( $row['group_mod'] == '1' ) ? 'disabled checked' : 'disabled';
					}
					
					$template->assign_block_vars('user_groups.groups_row', array(
						'S_MARK_NAME'			=> "groups_mark[$group_id]",
						'S_MARK_ID'				=> $group_id,
						'S_NEG_MARK_ID'			=> $neg_group_id,
						'S_ASSIGNED_GROUP'		=> $s_assigned_group,
						'S_UNASSIGNED_GROUP'	=> $s_unassigned_group,
						'S_MOD_GROUP'			=> $s_mod_group,
						'U_USER_PENDING'		=> ( $row['user_pending'] ) ? $lang['Membership_pending'] : '',
						'U_GROUP_NAME'			=> $group_name
					));
				}
				
				
				$sql_teams = 'SELECT team_id, team_name
							FROM ' . TEAMS . '
						ORDER BY team_order';
				if ( !($result_teams = $db->sql_query($sql_teams)) )
				{
					message_die(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql_teams);
				}
				
				while ( $row_teams = $db->sql_fetchrow($result_teams) )
				{
					$team_id	= $row_teams['team_id'];
					$team_name	= $row_teams['team_name'];

					$sql = 'SELECT user_id, team_mod
								FROM ' . TEAMS_USERS . '
								WHERE user_id = ' . $user_id . '
									AND team_id = ' . $team_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
					}
					
					$member = ( $row = $db->sql_fetchrow($result) ) ? TRUE : FALSE;
					$neg_team_id = -1 * $team_id;
					
					$template->assign_block_vars('user_groups.teams_row', array(
						'S_MARK_NAME'		=> "teams_mark[$team_id]",
						'S_MARK_ID'			=> $team_id,
						'S_NEG_MARK_ID'		=> $neg_team_id,
						
						'S_MOD_TEAM'		=> ( $row['team_mod'] ) ? 'checked="checked"' : '',
						'S_ASSIGNED_TEAM'	=> ( $member ) ? 'checked="checked"' : '',
						'S_UNASSIGNED_TEAM' => ( !$member ) ? 'checked="checked"' : '',
						'U_TEAM_NAME'		=> $team_name
					));
				}
				$db->sql_freeresult($result);
		
				$s_hidden_fields = '<input type="hidden" name="mode" value="editgroups" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />';

				$template->assign_vars(array(
					'L_USER_HEAD'			=> $lang['user_head'],
					'L_USER_NEW_EDIT'		=> $lang['user_edit'],
					'L_USER_GROUP'			=> $lang['user_group'],
					'L_USER_AUTHS'			=> $lang['user_auths'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'L_USER_GROUPS'			=> $lang['user_groups'],
					'L_USER_TEAMS'			=> $lang['user_teams'],
					'L_EMAIL_NOTIFICATION'	=> $lang['user_mailnotification'],
					'L_MODERATOR'			=> $lang['user_groupmod'],

					'S_USER_EDIT'			=> append_sid("admin_user.php?mode=edit&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_GROUP'			=> append_sid("admin_user.php?mode=groups&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_AUTHS'			=> append_sid("admin_user.php?mode=auths&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_ACTION'			=> append_sid("admin_user.php"),
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
				));
			
				$template->pparse('body');
			
			break;
			
			case 'editgroups':
			
				$groups_mark_list	= $HTTP_POST_VARS['groups_mark'];
				$email_notification	= intval($HTTP_POST_VARS['email_notification']);
				
				$groups_mark_lists = array();
				foreach ( $groups_mark_list as $key => $value )
				{
					$groups_mark_lists[] = $value;
				}
				
				// now add the user to any group checked 'YES' if he is not already in that group
				$temp_count = 0;
				
				for ($i = 0; $i < count($groups_mark_lists); $i++)
				{
					if ( $groups_mark_lists[$i] > 0 )
					{
						// test to see if there is already an entry for this group and user; if so, skip the insert and go to the next group...
						$sql = 'SELECT user_id, user_pending
									FROM ' . GROUPS_USERS . '
									WHERE user_id = ' . $user_id . '
										AND group_id = ' . $groups_mark_lists[$i];
						if ( !($result = $db->sql_query($sql) ) )
						{
							message_die(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
						}
						
						if ( !($row = $db->sql_fetchrow($result)) )
						{
							// here, we actually put the user into a selected group if there was no entry for the user in this group
							$sql = 'INSERT INTO ' . GROUPS_USERS . " (group_id, user_id, user_pending) VALUES ($groups_mark_lists[$i], $user_id, 0)";
							if ( !$db->sql_query($sql) )
							{
								message_die(GENERAL_ERROR, 'Could not add user to checked groups', '', __LINE__, __FILE__, $sql);
							}
							$temp_count = 1;
						}
						else
						{
							// here, we update the user's entry in the group if his membership was pending and the admin checked YES for the group...
							if( $row['user_pending'] == 1 )
							{
								$sql = 'UPDATE ' . GROUPS_USERS . '
											SET
												user_pending = 0
											WHERE group_id = ' . $groups_mark_lists[$i] . '
												AND user_id = ' . $user_id;
								if ( !$db->sql_query($sql) )
								{
									message_die(GENERAL_ERROR, 'Could not add user to checked groups', '', __LINE__, __FILE__, $sql);
								}
								$temp_count = 1;
							}
						}
						group_set_auth($user_id, $groups_mark_lists[$i]);
					}
				}
				
				// now delete the user from any group that is checked 'NO' if he is currently in that group and is not the moderator (but don't allow deletion of moderator)
				for ($i = 0; $i < count($groups_mark_lists); $i++)
				{
					if ( $groups_mark_lists[$i] < 0 )
					{
						// delete user from any group checked 'NO'.
						$group_id = (-1) * $groups_mark_lists[$i];
						
						$sql = 'SELECT user_id
									FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
									WHERE gu.user_id = ' . $user_id . '
										AND g.group_single_user = 0
										AND gu.group_id = ' . $group_id . '
										AND g.group_id = gu.group_id';
						if ( !($result = $db->sql_query($sql) ) )
						{
							message_die(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
						}
						
						if ( $row = $db->sql_fetchrow($result) )
						{
							// here, we actually delete the user from the group if there an entry for the user in the group
							$sql = 'DELETE FROM ' . GROUPS_USERS . ' WHERE user_id = ' . $row['user_id'] . ' AND group_id = ' . $group_id;
							if ( !$db->sql_query($sql) )
							{
								message_die(GENERAL_ERROR, 'Could not add delete user from group marked \'NO\'', '', __LINE__, __FILE__, $sql);
							}
						}
						group_reset_auth($user_id, $group_id);
					}
				}
				
				$teams_mark_list	= $HTTP_POST_VARS['teams_mark'];
				
				$teams_mark_lists = array();
				foreach ( $teams_mark_list as $key => $value )
				{
					$teams_mark_lists[] = $value;
				}
				
				$temp_count = 0;
				
				for ($i = 0; $i < count($teams_mark_lists); $i++)
				{
					if ( $teams_mark_lists[$i] > 0 )
					{
						$sql = 'SELECT user_id
									FROM ' . TEAMS_USERS . '
									WHERE user_id = ' . $user_id . '
										AND team_id = ' . $teams_mark_lists[$i];
						if ( !($result = $db->sql_query($sql) ) )
						{
							message_die(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
						}
						
						if ( !($row = $db->sql_fetchrow($result)) )
						{
							$sql = 'INSERT INTO ' . TEAMS_USERS . " (team_id, user_id, team_join) VALUES ($teams_mark_lists[$i], $user_id, " . time() . ")";
							if ( !$db->sql_query($sql) )
							{
								message_die(GENERAL_ERROR, 'Could not add user to checked teams', '', __LINE__, __FILE__, $sql);
							}
							$temp_count = 1;
						}
					}
				}
				
				// now delete the user from any group that is checked 'NO' if he is currently in that group and is not the moderator (but don't allow deletion of moderator)
				for ($i = 0; $i < count($teams_mark_lists); $i++)
				{
					if ( $teams_mark_lists[$i] < 0 )
					{
						// delete user from any group checked 'NO'.
						$team_id = (-1) * $teams_mark_lists[$i];
						
						$sql = 'SELECT user_id
									FROM ' . TEAMS . ' t, ' . TEAMS_USERS . ' tu
									WHERE tu.user_id = ' . $user_id . '
										AND tu.team_id = ' . $team_id . '
										AND t.team_id = tu.team_id';
						if ( !($result = $db->sql_query($sql) ) )
						{
							message_die(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
						}
						
						if ( $row = $db->sql_fetchrow($result) )
						{
							$sql = 'DELETE FROM ' . TEAMS_USERS . ' WHERE user_id = ' . $row['user_id'] . ' AND team_id = ' . $team_id;
							if ( !$db->sql_query($sql) )
							{
								message_die(GENERAL_ERROR, 'Could not add delete user from group marked \'NO\'', '', __LINE__, __FILE__, $sql);
							}
						}
					}
				}
				
				// now, find out if user has been added to any groups and the admin wants a notification email sent out
				/*
				if ( $temp_count == 1 && $email_notification == 1 )
				{
					
					$group_name = '';
					$sql = "SELECT g.group_name, u.username, u.user_email, u.user_lang
					FROM " . GROUPS . " g, " . USERS . " u, " . USER_GROUP . " ug
					WHERE u.user_id = $user_id
					AND ug.user_id = u.user_id
					AND g.group_id = ug.group_id
					AND ug.user_pending = 0";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not get group information', '', __LINE__, __FILE__, $group_sql);
					}
					
					$row = $db->sql_fetchrow($result);
					$username = $row['username'];
					$user_email = $row['user_email'];
					$user_lang = $row['user_lang'];
					$group_name = $row['group_name'];
					while ( $row = $db->sql_fetchrow($result) )
					{
						$group_name = $group_name . "\n" . $row['group_name'];
					}
					include($phpbb_root_path . 'includes/emailer.'.$phpEx);
					$emailer = new emailer($board_config['smtp_delivery']);
					
					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);
					$emailer->use_template('current_groups', $user_lang);
					$emailer->email_address($user_email);
					$emailer->set_subject($lang['Group_added']);
					
					$emailer->assign_vars(array(
						'SITENAME' => $board_config['sitename'],
						'GROUP_NAMES' => $group_name,
						'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $board_config['board_email_sig']) : ''
					));
					
					$emailer->send();
					$emailer->reset();
				}
				*/
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_USER, 'acp_user_groups');
			
				$message = $lang['user_change_groups']
					. '<br><br>' . sprintf($lang['click_return_user'], '<a href="' . append_sid("admin_user.php") . '">', '</a>')
					. '<br><br>' . sprintf($lang['click_return_user_groups'], '<a href="' . append_sid("admin_user.php?mode=groups&" . POST_USERS_URL . "=$user_id") . '">', '</a>');				
				message_die(GENERAL_MESSAGE, $message);
				
			break;
			
			case 'auths':
			
				$template->set_filenames(array('body' => './../admin/style/acp_user.tpl'));
				$template->assign_block_vars('user_auths', array());
				
				$user = get_data('user', $user_id, 0);
				
				if ( $userdata['user_level'] < $user['user_level'] )
				{
					message_die(GENERAL_ERROR, $lang['auth_fail']);
				}
				
				$sql = 'SELECT g.group_id, ' . implode(', ', $group_auth_fields) . '
							FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
							WHERE g.group_id = gu.group_id
								AND gu.user_id = ' . $user_id . ' ORDER BY group_id';
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
				}
				
				$auths_data = array();
				while ($row_auths = $db->sql_fetchrow($result))
				{
					$group_ins[] = $row_auths['group_id'];
					$auths_data[$row_auths['group_id']] = $row_auths;
					
					unset($auths_data[$row_auths['group_id']]['group_id']);
				}
				$db->sql_freeresult($result);
				
				$sql = 'SELECT g.group_id, ' . implode(', ', $group_auth_fields) . '
							FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
							WHERE g.group_id = gu.group_id
								AND group_single_user = 0
								AND gu.user_id = ' . $user_id . ' ORDER BY group_id';
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
				}
				
				$authd_data = array();
				while ($row_authd = $db->sql_fetchrow($result))
				{
					$group_ind[] = $row_authd['group_id'];
					$authd_data[$row_authd['group_id']] = $row_authd;
					
					unset($authd_data[$row_authd['group_id']]['group_id']);
				}
				$db->sql_freeresult($result);
				
				$sql = 'SELECT group_id, ' . implode(', ', $group_auth_fields) . '
							FROM ' . GROUPS . '
							WHERE group_single_user = 0
								AND group_id IN (' . implode(', ', $group_ins) . ')
						ORDER BY group_id';
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql2);
				}
				
				$auths_group = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$auths_group[$row['group_id']] = $row;
					
					unset($auths_group[$row['group_id']]['group_id']);
				}
				$db->sql_freeresult($result);
				
				$sql = 'SELECT group_id, ' . implode(', ', $group_auth_fields) . '
							FROM ' . GROUPS . '
							WHERE group_single_user = 0
								AND group_id IN (' . implode(', ', $group_ind) . ')
						ORDER BY group_id';
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql2);
				}
				
				$authd_group = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$authd_group[$row['group_id']] = $row;
					
					unset($authd_group[$row['group_id']]['group_id']);
				}
				$db->sql_freeresult($result);
				
				$auths		= array();
				$group_ids	= array_keys($auths_group);
				foreach ( $auths_data as $key => $value )
				{
					foreach ($group_ids as $group_key => $group_id)
					{
						foreach( $value as $v_key => $v_value )
						{
							if ( $v_value == 0 )
							{
								if ( !array_key_exists($v_key, $auths) )
								{
									$auths[$v_key] = $auths_group[$group_id][$v_key];
								}
								else if ( !$auths[$v_key] )
								{
									$auths[$v_key] = $auths_group[$group_id][$v_key];
								}
							}
							else
							{
								$auths[$v_key] = $v_value;
							}
						}
					}
				}
				
				$authd		= array();
				$group_ids	= array_keys($authd_group);
				foreach ( $authd_data as $key => $value )
				{
					foreach ($group_ids as $group_key => $group_id)
					{
						foreach( $value as $v_key => $v_value )
						{
							if ( $v_value == 0 || $v_value == 2 )
							{
								if ( !array_key_exists($v_key, $authd) )
								{
									$authd[$v_key] = $authd_group[$group_id][$v_key];
								}
								else if ( !$authd[$v_key] )
								{
									$authd[$v_key] = $authd_group[$group_id][$v_key];
								}
							}
							else
							{
								$authd[$v_key] = $v_value;
							}
						}
					}
				}
								
				for($j = 0; $j < count($group_auth_fields); $j++)
				{
					if ( $userdata['user_level'] == ADMIN )
					{
						$selected_special	= ( $auths[$group_auth_fields[$j]] == $group_auth_const[2] ) ? ' checked="checked"' : '';
						$selected_default	= ( $auths[$group_auth_fields[$j]] != $group_auth_const[2] ) ? ' checked="checked"' : '';
					}
					else
					{
						$selected_special	= ( $auths[$group_auth_fields[$j]] == $group_auth_const[2] ) ? ' disabled checked="checked"' : ' disabled';
						$selected_default	= ( $auths[$group_auth_fields[$j]] != $group_auth_const[2] ) ? ' disabled checked="checked"' : ' disabled';
					}
					
					$custom_auths[$j] = '<input type="radio" onClick="set_color(\'auth_farbe_' . $j . '\', \'#9FC\');" id="sett_ja_' . $j . '" name="' . $group_auth_fields[$j] . '"';
					$custom_auths[$j] .= 'value="2" ' . $selected_special . '> ' . $lang['Group_' . $group_auth_levels[2]];
					$custom_auths[$j] .= '&nbsp;&nbsp;<input onClick="set_color(\'auth_farbe_' . $j . '\', \'#F99\');" id="sett_ja_' . $j . '" type="radio" name="' . $group_auth_fields[$j] . '"';
					$custom_auths[$j] .= 'value="3" ' . $selected_default . '> ' . $lang['Group_' . $group_auth_levels[3]];
					
					$custom_authd[$j] = ( $authd[$group_auth_fields[$j]] == $group_auth_const[0] ) ? $lang['Group_' . $group_auth_levels[0]] : $lang['Group_' . $group_auth_levels[1]];
				
					$cell_title = $lang['auths'][$group_auth_fields[$j]];
			
					$status = ( !$selected_default ) ? 'style="background-color: #9FC;"' : 'style="background-color: #F99;"';
					
					$template->assign_block_vars('user_auths.user_auth_data', array(
						'TEMP_ID'				=> $j,
						'STATUS' 				=> $status,
						'CELL_TITLE'			=> $cell_title,
						'S_AUTH_LEVELS_SELECT'	=> $custom_auths[$j],
						'S_AUTH_LEVELS_DEFAULT'	=> $custom_authd[$j],
					));
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="editauths" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />';
				
				$template->assign_vars(array(
					'L_USER_HEAD'			=> $lang['user_head'],
					'L_USER_NEW_EDIT'		=> $lang['user_edit'],
					'L_USER_GROUP'			=> $lang['user_group'],
					'L_USER_AUTHS'			=> $lang['user_auths'],
					'L_USER_AUTHS_EXPLAIN'	=> $lang['user_auths_explain'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'L_USER_GROUPS'			=> $lang['user_groups'],
					'L_USER_TEAMS'			=> $lang['user_teams'],

					'S_USER_EDIT'			=> append_sid("admin_user.php?mode=edit&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_GROUP'			=> append_sid("admin_user.php?mode=groups&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_AUTHS'			=> append_sid("admin_user.php?mode=auths&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_ACTION'			=> append_sid("admin_user.php"),
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
				));
			
				$template->pparse('body');
			
			break;
			
			case 'editauths':
			
				$sql = 'SELECT g.group_id
							FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
							WHERE g.group_id = gu.group_id
								AND gu.user_id = ' . $user_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);

				$sql_auth = '';
				for ( $i = 0; $i < count($group_auth_fields); $i++ )
				{
					$value = intval($HTTP_POST_VARS[$group_auth_fields[$i]]);
					$value = ( $value == '3' ) ? '0' : '2';
	
					$sql_auth .= ( ( $sql_auth != '' ) ? ', ' : '' ) . $group_auth_fields[$i] . ' = ' . $value;
				}
				
				$sql = "UPDATE " . GROUPS . " SET
							
							$sql_auth
						WHERE group_id = " . $row['group_id'];
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_USER, 'acp_auths_edit');
				
				$message = $lang['user_change_auths']
					. '<br><br>' . sprintf($lang['click_return_user'], '<a href="' . append_sid("admin_user.php") . '">', '</a>')
					. '<br><br>' . sprintf($lang['click_return_user_auths'], '<a href="' . append_sid("admin_user.php?mode=auths&" . POST_USERS_URL . "=$user_id") . '">', '</a>');				
				message_die(GENERAL_MESSAGE, $message);
			
			break;
			
			case 'settings':
			
				$template->set_filenames(array('body' => './../admin/style/acp_user.tpl'));
				$template->assign_block_vars('user_auths', array());
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="editsettings" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />';

				$template->assign_vars(array(
					'L_USER_HEAD'			=> $lang['user_head'],
					'L_USER_NEW_EDIT'		=> $lang['user_edit'],
					'L_USER_GROUP'			=> $lang['user_group'],
					'L_USER_AUTHS'			=> $lang['user_auths'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'L_USER_GROUPS'			=> $lang['user_groups'],
					'L_USER_TEAMS'			=> $lang['user_teams'],

					'S_USER_EDIT'			=> append_sid("admin_user.php?mode=edit&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_GROUP'			=> append_sid("admin_user.php?mode=groups&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_AUTHS'			=> append_sid("admin_user.php?mode=auths&amp;" . POST_USERS_URL . "=" . $user_id),
					'S_USER_ACTION'			=> append_sid("admin_user.php"),
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
				));
			
				$template->pparse('body');
			
			break;
			
			case 'editsettings':
			
				_debug_poste($_POST);
			
			break;
			
			default:
				message_die(GENERAL_ERROR, $lang['no_mode']);
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_user.tpl'));
	$template->assign_block_vars('display', array());
	
	$template->assign_vars(array(
		'L_USER_TITLE'		=> $lang['user_head'],
		'L_USER_EXPLAIN'	=> $lang['user_explain'],
		
		'L_USER'			=> $lang['users'],
		'L_USER_ADD'		=> $lang['user_add'],
		
		'L_SETTINGS'		=> $lang['settings'],
		
		

		'S_USER_ACTION' => append_sid('admin_user.php'),
	));
	
	$sql = 'SELECT *
				FROM ' . USERS . '
				WHERE user_id <> ' . ANONYMOUS . '
			ORDER BY user_id DESC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
	}
	
	$user_list = $db->sql_fetchrowset($result);
	
	for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($user_list)); $i++)
	{
		$class = ($i % 2) ? 'row_class1' : 'row_class2';
		
		switch ($user_list[$i]['user_level'])
		{
			case GUEST:
				$user_level = $lang['auth_guest'];
				break;
			case USER:
				$user_level = $lang['auth_user'];
				break;
			case TRIAL:
				$user_level = $lang['auth_trial'];
				break;
			case MEMBER:
				$user_level = $lang['auth_member'];
				break;
			case MOD:
				$user_level = $lang['auth_mod'];
				break;
			case ADMIN:
				$user_level = $lang['auth_admin'];
				break;
		}
		
		if ( $userdata['user_level'] > $user_list[$i]['user_level'] )
		{
			$link_edit		= '<a href="' . append_sid("admin_user.php?mode=edit&amp;" . POST_USERS_URL . "=" . $user_list[$i]['user_id']) . '" >' . $lang['edit'] . '</a>';
			$link_delete	= '<a href="' . append_sid("admin_user.php?mode=delete&amp;" . POST_USERS_URL . "=" . $user_list[$i]['user_id']) . '" >' . $lang['delete'] . '</a>';
		}
		else if ( $userdata['user_level'] == ADMIN )
		{
			$link_edit		= '<a href="' . append_sid("admin_user.php?mode=edit&amp;" . POST_USERS_URL . "=" . $user_list[$i]['user_id']) . '" >' . $lang['edit'] . '</a>';
			$link_delete	= '<a href="' . append_sid("admin_user.php?mode=delete&amp;" . POST_USERS_URL . "=" . $user_list[$i]['user_id']) . '" >' . $lang['delete'] . '</a>';
		}
		else
		{
			$link_edit		= $lang['edit'];
			$link_delete	= $lang['delete'];
		}
		
		$template->assign_block_vars('display.user_list', array(
			'CLASS'			=> $class,
			'USER_LEVEL'	=> $user_level,
			'USERNAME'		=> $user_list[$i]['username'],
			'JOINED'		=> create_date($userdata['user_dateformat'], $user_list[$i]['user_regdate'], $userdata['user_timezone']),
			
			'EDIT'			=> $link_edit,
			'DELETE'		=> $link_delete,
		));
	}
	
	$current_page = ( !count($user_list) ) ? 1 : ceil( count($user_list) / $settings['site_entry_per_page'] );

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("admin_user.php?", count($user_list), $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>