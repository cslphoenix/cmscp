<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_user'])
	{
		$module['_headmenu_04_users']['_submenu_settings'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$root_file	= basename(__FILE__);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_settings';
	
	require('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('users');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_USER_URL, 0);
	$mode		= request('mode', 1);
	$confirm	= request('confirm', 1);
	
	$error		= '';
	$fields	= '';
	
	$url	= POST_USER_URL;
	$log	= LOG_SEK_USER;

	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_user'])
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($root_file, true)) : false;

#	debug($_GET);
#	debug($_POST);
	
	$auth_fields	= get_authlist();
	$auth_levels	= array('disallowed', 'allowed', 'special', 'default');
	$auth_const		= array(AUTH_DISALLOWED, AUTH_ALLOWED, AUTH_SPECIAL, AUTH_DEFAULT);
	
	if ( $mode != '_create' )
	{
		$s_mode = '<select class="postselect" name="mode" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
		foreach ( $lang['user_option'] as $key => $value )
		{
			$selected = ( $mode == $key ) ? ' selected="selected"' : '';
			$s_mode .= '<option value="' . $key . '"' . $selected . '>&raquo;&nbsp;' . $value . '&nbsp;</option>';
		}
		$s_mode .= '</select>';
		$s_mode .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	}
	
	$temp = ( $mode == '_create' || $mode == '_update' ) ? 'user_regedit' : ( !$mode ? 'display' : $mode);
	
#	debug($mode);
#	debug($temp);
	
	$template->set_filenames(array('body' => 'style/acp_user.tpl'));
	$template->assign_block_vars($temp, array());
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			if ( $mode == '_create' && !request('submit', 1) )
			{
				$data = array(
					'username'				=> request('username', 2),
					'user_regdate'			=> time(),
					'user_email'			=> '',
					'user_email_confirm'	=> '',
					'new_password'			=> '',
					'password_confirm'		=> '',
					'user_founder'			=> '0',
				);
				
				$template->assign_block_vars('adduser', array());
			}
			else if ( $mode == '_update' && !request('submit', 1) )
			{
				$data = get_data(USERS, $data_id, 1);
				
				$template->assign_block_vars($temp . '.edituser', array());
				
				if ( $userdata['user_level'] < $data['user_level'] )
				{
					message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
				}
			}
			else
			{
				$data = array(
					'username'				=> request('username', 2),
					'user_regdate'			=> time(),
					'user_email'			=> request('user_email', 2),
					'user_email_confirm'	=> request('user_email_confirm', 2),
					'new_password'			=> request('new_password', 2),
					'password_confirm'		=> request('password_confirm', 2),
					'user_founder'			=> request('user_founder', 0),
				);
			}
			
			if ( $userdata['user_founder'] )
			{
				$check_founder_no	= ( !$data['user_founder'] ) ? ' checked="checked"' : '';
				$check_founder_yes	= ( $data['user_founder'] ) ? ' checked="checked"' : '';
			}
			else
			{
				$check_founder_no	= ( !$data['user_founder'] ) ? ' disabled checked="checked"' : ' disabled';
				$check_founder_yes	= ( $data['user_founder'] ) ? ' disabled checked="checked"' : ' disabled';
			}
			
			$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
			$fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . $url . '" value="' . $data_id . '" />';
			
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['user'], $data['username']),
				'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['user']),
				
				'L_REGISTER'		=> $lang['user_register'],
				'L_FIELDS'			=> $lang['user_fields'],
				'L_SETTINGS'		=> $lang['user_settings'],
				'L_IMAGES'			=> $lang['user_images'],
				
				
				'L_USERNAME'			=> $lang['username'],
				'L_REGISTER'			=> $lang['user_register'],
				'L_LASTLOGIN'			=> $lang['user_lastlogin'],
				'L_FOUNDER'				=> $lang['user_founder'],
				'L_EMAIL'				=> $lang['user_email'],
				'L_EMAIL_CONFIRM'		=> $lang['user_email_confirm'],
				
				'L_PASSWORD'			=> $lang['user_password'],
				'L_PASSWORD_CONFIRM'	=> $lang['user_password_confirm'],
				
				'L_PASSWORD_REGISTER'	=> $lang['password_register'],
				'L_PASSWORD_GENERATE'	=> $lang['password_generate'],
				
				'PASS_1'				=> sprintf($lang['user_pass_random'], random_password(7, 10, false, false)),
				'PASS_2'				=> sprintf($lang['user_pass_random'], random_password(7, 10, false, true, false)),
				'PASS_3'				=> sprintf($lang['user_pass_random'], random_password()),
				'PASS_4'				=> sprintf($lang['user_pass_random'], random_password(7, 14, true, true, false)),
				'PASS_5'				=> sprintf($lang['user_pass_random'], random_password(7, 14, true, true)),
				'PASS_6'				=> sprintf($lang['user_pass_random'], random_password(7, 14, true, true, true, true)),
				
				'USERNAME'				=> $data['username'],
				
				'REGISTER'		=> create_date($userdata['user_dateformat'], $data['user_regdate'], $userdata['user_timezone']),
				'LASTLOGIN'		=> ( $mode == '_update' ) ? create_date($userdata['user_dateformat'], $data['user_lastvisit'], $userdata['user_timezone']) : '',
				
				'USER_EMAIL'			=> $data['user_email'],
				
				'S_FOUNDER_NO'	=> $check_founder_no,
				'S_FOUNDER_YES'	=> $check_founder_yes,
				
				
				'S_MODE'		=> $s_mode,
				
			#	'S_REGISTER'		=> append_sid("$file?mode=register&amp;$url=$data_id"),
			#	'S_FIELDS'			=> append_sid("$file?mode=fields&amp;$url=$data_id"),
			#	'S_SETTINGS'		=> append_sid("$file?mode=settings&amp;$url=$data_id"),
			#	'S_IMAGES'			=> append_sid("$file?mode=images&amp;$url=$data_id"),
				
			#	'S_EDIT'		=> append_sid("$file?mode=edit&amp;$url=$data_id"),
			#	'S_GROUP'		=> append_sid("$file?mode=groups&amp;$url=$data_id"),
			#	'S_AUTHS'		=> append_sid("$file?mode=auths&amp;$url=$data_id"),
				'S_ACTION'		=> append_sid($root_file),
				'S_FIELDS'		=> $fields,
			));
		
		break;
		
		case 'adduser':
			
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
					message(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
				}
				
				if ( !($row = $db->sql_fetchrow($result)) )
				{
					message(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
				}
				
				$data_id = $row['total'] + 1;
				
				$sql = "INSERT INTO " . USERS . " (user_id, username, user_password, user_email, user_regdate) VALUES ($data_id, '$username_sql', '$new_password', '$user_email', " . time() . ")";
				if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
				{
					message(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
				}
		
				$sql = "INSERT INTO " . GROUPS . " (group_name, group_type, group_desc, group_single_user)
					VALUES ('$username_sql', 2, 'Personal User', 1)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
				}
		
				$group_id = $db->sql_nextid();
		
				$sql = "INSERT INTO " . GROUPS_USERS . " (user_id, group_id, user_pending)
					VALUES ($data_id, $group_id, 0)";
				if( !($result = $db->sql_query($sql, END_TRANSACTION)) )
				{
					message(GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['Account_added'];
				$email_template = 'user_welcome';

				include($root_path . 'includes/emailer.php');
				$emailer = new emailer($config['smtp_delivery']);
	
				$emailer->from($config['page_email']);
				$emailer->replyto($config['page_email']);
	
				$emailer->use_template($email_template, stripslashes($user_lang));
				$emailer->email_address($user_email);
				$emailer->set_subject(sprintf($lang['Welcome_subject'], $config['page_name']));

				$emailer->assign_vars(array(
					'SITENAME' => $config['page_name'],
					'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $config['page_name']),
					'USERNAME' => substr(str_replace("\'", "'", $username), 0, 25),
					'PASSWORD' => $new_password,
					'EMAIL_SIG' => str_replace('<br>', "\n", "-- \n" . $config['page_email_sig']),
				));
		
				$emailer->send();
				$emailer->reset();
				
				log_add(LOG_ADMIN, $log, 'acp_user_add');
				
			//	#$oCache -> sCachePath = './../cache/';
			//	#$oCache -> deleteCache('display_subnavi_user');
	
				$message = $lang['create_user'] . sprintf($lang['click_return_user'], '<a href="' . append_sid($root_file) . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
			}
			else
			{
				message(GENERAL_ERROR, $error_msg, '');
			}

		break;
		
		case 'edituser':
			
			$sql = 'SELECT * FROM ' . USERS . " WHERE user_id = $data_id";
			$result = $db->sql_query($sql);
	
			if (!($user_info = $db->sql_fetchrow($result)))
			{
				message(GENERAL_MESSAGE, $lang['user_not_exist']);
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
			
//				debug($_POST);
			
			if (!empty($HTTP_POST_VARS['game_image']))
			{
				$sql = 'SELECT * FROM ' . GAMES . " WHERE game_image = '" . str_replace("\'", "''", $HTTP_POST_VARS['game_image']) . "'";
				$result = $db->sql_query($sql);
				
				if (!($game_info = $db->sql_fetchrow($result)))
				{
					message(GENERAL_MESSAGE, $lang['user_not_exist']);
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
					WHERE user_id = $data_id";
			$result = $db->sql_query($sql);
			
			log_add(LOG_ADMIN, $log, 'acp_user_regedit');
			
			#$oCache -> sCachePath = './../cache/';
			#$oCache -> deleteCache('display_subnavi_user');
			
			$message = $lang['user_update'] . sprintf($lang['click_return_user'], '<a href="' . append_sid($root_file) . '">', '</a>');
			message(GENERAL_MESSAGE, $message);

		break;
		
		case 'fields':
		
			$user		= get_data('user', $data_id, 0);
			$user_data	= get_data('profile_data', $data_id, 0);
		
			$template->set_filenames(array('body' => 'style/acp_user.tpl'));
			$template->assign_block_vars('user_fields', array());
			
			$sql = 'SELECT * FROM ' . PROFILE_CAT . ' ORDER BY cat_order';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			if ( $total_categories = $db->sql_numrows($result) )
			{
				$category_rows = $db->sql_fetchrowset($result);
				
				$sql = 'SELECT *
							FROM ' . PROFILE . '
							ORDER BY profile_cat, profile_order';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				if ( $total_profile = $db->sql_numrows($result) )
				{
					$profile_rows = $db->sql_fetchrowset($result);
				}
				
				for ( $i = 0; $i < $total_categories; $i++ )
				{
					$cat_id = $category_rows[$i]['cat_id'];
			
					$template->assign_block_vars('user_fields.catrow', array( 
						'CATEGORY_ID'			=> $cat_id,
						'CATEGORY_NAME'			=> $category_rows[$i]['category_name'],
					));
					
					for($j = 0; $j < $total_profile; $j++)
					{
						$profile_id = $profile_rows[$j]['profile_id'];
						
						if ( $profile_rows[$j]['profile_cat'] == $cat_id )
						{
							$value = $user_data[$profile_rows[$j]['profile_field']];
							
							if ( $profile_rows[$j]['profile_type'] )
							{
								$field = '<textarea class="textarea" name="' . $profile_rows[$j]['profile_field'] . '" rows="5" cols="50" >' . $value . '</textarea>';
								
							}
							else
							{
								$field = '<input type="text" class="post" name="'.$profile_rows[$j]['profile_field'].'" value="'.$value.'">';
							}
							
							$template->assign_block_vars('user_fields.catrow.profilerow',	array(
								'NAME'	=> $profile_rows[$j]['profile_name'],
								'FIELD' => $field,
							));
						}
					}
				}
			}
			
			$fields = '<input type="hidden" name="mode" value="update_fields" />';
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			
			$template->assign_vars(array(
				'L_HEAD'			=> $lang['user_head'],
				'L_INPUT'		=> ($mode == 'add') ? $lang['user_new_add'] : $lang['user_regedit'],
				'L_GROUP'			=> $lang['user_group'],
				'L_AUTHS'			=> $lang['user_auths'],
				'L_REQUIRED'			=> $lang['required'],
				
				'L_REGISTER'		=> $lang['user_register'],
				'L_FIELDS'			=> $lang['user_fields'],
				'L_SETTINGS'		=> $lang['user_settings'],
				'L_IMAGES'			=> $lang['user_images'],
				
				'L_SUBMIT'				=> $lang['common_submit'],
				'L_RESET'				=> $lang['common_reset'],
				'L_YES'					=> $lang['common_yes'],
				'L_NO'					=> $lang['common_no'],
				
				'S_FIELDS'			=> append_sid("$file?mode=fields&amp;$url=$data_id"),
				'S_SETTINGS'		=> append_sid("$file?mode=settings&amp;$url=$data_id"),
				'S_IMAGES'			=> append_sid("$file?mode=images&amp;$url=$data_id"),
				
				
				'S_EDIT'			=> append_sid("$file?mode=edit&amp;$url=$data_id"),
				'S_GROUP'			=> append_sid("$file?mode=groups&amp;$url=$data_id"),
				'S_AUTHS'			=> append_sid("$file?mode=auths&amp;$url=$data_id"),
				'S_ACTION'			=> append_sid($root_file),
				'S_FIELDS'		=> $fields
			));
		
			$template->pparse('body');
		
		break;
		
		case 'update_fields':
		
			debug($_POST);
		
		break;
		
		case 'settings':
		
			$user = get_data('user', $data_id, 0);
			
			$template->set_filenames(array('body' => 'style/acp_user.tpl'));
			$template->assign_block_vars('user_settings', array());
			
			$user_timezone			= $data['user_timezone'];
			$user_style				= $data['user_style'];
			$user_lang				= $data['user_lang'];
			$user_dateformat		= $data['user_dateformat'];
			$user_viewemail			= $data['user_viewemail'];
			$user_birthday			= $data['user_birthday'];
			$user_sig				= $data['user_sig'];
			
			$user_notify			= $data['user_notify'];
			$user_notify_pm			= $data['user_notify_pm'];
			$user_popup_pm			= $data['user_popup_pm'];
			
			
			
			
			
			
			
			$user_allow_avatar		= $data['user_allow_avatar'];
			$user_allow_pm			= $data['user_allow_pm'];
			$user_allow_viewonline	= $data['user_allow_viewonline'];
			
			$user_rank				= $data['user_rank'];
			
			$fields = '<input type="hidden" name="mode" value="update_settings" />';
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			
			$template->assign_vars(array(
				'L_HEAD'			=> $lang['user_head'],
				'L_INPUT'		=> ($mode == 'add') ? $lang['user_new_add'] : $lang['user_regedit'],
				'L_GROUP'			=> $lang['user_group'],
				'L_AUTHS'			=> $lang['user_auths'],
				'L_REQUIRED'			=> $lang['required'],
				
				'L_REGISTER'		=> $lang['user_register'],
				'L_FIELDS'			=> $lang['user_fields'],
				'L_SETTINGS'		=> $lang['user_settings'],
				'L_IMAGES'			=> $lang['user_images'],
				
				'L_SUBMIT'				=> $lang['common_submit'],
				'L_RESET'				=> $lang['common_reset'],
				'L_YES'					=> $lang['common_yes'],
				'L_NO'					=> $lang['common_no'],
				
				'S_FIELDS'			=> append_sid("$file?mode=fields&amp;$url=$data_id"),
				'S_SETTINGS'		=> append_sid("$file?mode=settings&amp;$url=$data_id"),
				'S_IMAGES'			=> append_sid("$file?mode=images&amp;$url=$data_id"),
				
				
				'S_EDIT'			=> append_sid("$file?mode=edit&amp;$url=$data_id"),
				'S_GROUP'			=> append_sid("$file?mode=groups&amp;$url=$data_id"),
				'S_AUTHS'			=> append_sid("$file?mode=auths&amp;$url=$data_id"),
				'S_ACTION'			=> append_sid($root_file),
				'S_FIELDS'		=> $fields
			));
		
			$template->pparse('body');
		
		break;
		
		case 'update_settings':
		
			debug($_POST);
		
		break;
		
		case 'delete':
		
			$confirm = isset($HTTP_POST_VARS['confirm']);
			
			if ( $data_id && $confirm )
			{
				$user = get_data('user', $data_id, 0);
			
				if ( $userdata['user_level'] < $data['user_level'] )
				{
					message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
				}			

				log_add(LOG_ADMIN, $log, 'ACP_USER_DELETE', $user_info['user_name']);
				
				#$oCache -> sCachePath = './../cache/';
				#$oCache -> deleteCache('display_subnavi_user');
				
				$message = $lang['delete_user'] . sprintf($lang['click_return_user'], '<a href="' . append_sid($root_file) . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
	
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . $url . '" value="' . $data_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_user'],
	
					'L_YES'				=> $lang['common_yes'],
					'L_NO'				=> $lang['common_no'],
	
					'S_ACTION'	=> append_sid($root_file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_user']);
			}
		
			$template->pparse('body');
			
		break;
		
		case 'user_groups':
			
			$data = get_data(USERS, $data_id, 1);
			
			if ( $userdata['user_level'] < $data['user_level'] )
			{
				message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
			}
			
			$sql = "SELECT group_id, group_name, group_type, group_access
						FROM " . GROUPS . "
							WHERE group_single_user = 0
							ORDER BY group_order";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row_group = $db->sql_fetchrow($result) )
			{
				$group_id	= $row_group['group_id'];
				$group_name	= $row_group['group_name'];

				$sql = "SELECT user_id, user_pending, group_mod
							FROM " . GROUPS_USERS . "
								WHERE user_id = $data_id AND group_id = $group_id";
				if ( !($result_user = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
				}
				
				$member = ( $row = $db->sql_fetchrow($result_user) ) ? true : false;
				$neg_group_id = -1 * $group_id;
				
				if ( $row_group['group_type'] == GROUP_SYSTEM )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'disabled checked="checked"' : 'disabled';
					$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
					$s_mod_group		= ( $row['group_mod'] ) ? 'disabled checked' : 'disabled';
				}
				else if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? ' checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? '' : ' checked="checked"';
					$s_mod_group		= ( $row['group_mod'] ) ? ' checked ' : '';
				}
				else if ( $userdata['user_id'] == $data['user_id'] )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? '' : 'checked="checked"';
					$s_mod_group		= ( $row['group_mod'] ) ? ' checked' : '';
				}
				else if ( $userdata['user_level'] > $row_group['group_access'] )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? ' checked="checked"' : ' ';
					$s_unassigned_group	= ( $member ) ? ' ' : ' checked="checked"';
					$s_mod_group		= ( $row['group_mod'] ) ? ' checked ' : '';
				}
				else
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'disabled checked="checked"' : 'disabled';
					$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
					$s_mod_group		= ( $row['group_mod'] == '1' ) ? 'disabled checked' : 'disabled';
				}
				
				$s_mod_group .= ( !$member ) ? ' disabled' : '';
				
				$template->assign_block_vars($temp . '.row_group', array(
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
				message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql_teams);
			}
			
			while ( $row_teams = $db->sql_fetchrow($result_teams) )
			{
				$team_id	= $row_teams['team_id'];
				$team_name	= $row_teams['team_name'];

				$sql = 'SELECT user_id, team_mod
							FROM ' . TEAMS_USERS . '
							WHERE user_id = ' . $data_id . '
								AND team_id = ' . $team_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
				}
				
				$member = ( $row = $db->sql_fetchrow($result) ) ? TRUE : FALSE;
				$neg_team_id = -1 * $team_id;
				
				$template->assign_block_vars('user_groups.row_team', array(
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
	
			$fields = '<input type="hidden" name="mode" value="editgroups" /><input type="hidden" name="' . $url . '" value="' . $data_id . '" />';

			$template->assign_vars(array(
				'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_EDIT'			=> sprintf($lang['sprintf_edit'], $lang['user'], $data['username']),
				
				'L_GROUPS'			=> $lang['user_usergroups'],
				'L_TEAMS'			=> $lang['user_teams'],
				'L_EMAIL_NOTIFICATION'	=> $lang['user_mailnotification'],
				'L_MODERATOR'			=> $lang['user_groupmod'],
				
				

				
				'S_GROUP'			=> append_sid("$file?mode=groups&amp;$url=$data_id"),
				'S_AUTHS'			=> append_sid("$file?mode=auths&amp;$url=$data_id"),
				
				'S_MODE'			=> $s_mode,
				'S_EDIT'			=> append_sid("$file?mode=_update&amp;$url=$data_id"),
				'S_ACTION'			=> append_sid($root_file),
				'S_FIELDS'		=> $fields,
			));
		
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
								WHERE user_id = ' . $data_id . '
									AND group_id = ' . $groups_mark_lists[$i];
					if ( !($result = $db->sql_query($sql) ) )
					{
						message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
					}
					
					if ( !($row = $db->sql_fetchrow($result)) )
					{
						// here, we actually put the user into a selected group if there was no entry for the user in this group
						$sql = 'INSERT INTO ' . GROUPS_USERS . " (group_id, user_id, user_pending) VALUES ($groups_mark_lists[$i], $data_id, 0)";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'Could not add user to checked groups', '', __LINE__, __FILE__, $sql);
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
											AND user_id = ' . $data_id;
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'Could not add user to checked groups', '', __LINE__, __FILE__, $sql);
							}
							$temp_count = 1;
						}
					}
					group_set_auth($data_id, $groups_mark_lists[$i]);
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
								WHERE gu.user_id = ' . $data_id . '
									AND g.group_single_user = 0
									AND gu.group_id = ' . $group_id . '
									AND g.group_id = gu.group_id';
					if ( !($result = $db->sql_query($sql) ) )
					{
						message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
					}
					
					if ( $row = $db->sql_fetchrow($result) )
					{
						// here, we actually delete the user from the group if there an entry for the user in the group
						$sql = 'DELETE FROM ' . GROUPS_USERS . ' WHERE user_id = ' . $row['user_id'] . ' AND group_id = ' . $group_id;
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'Could not add delete user from group marked \'NO\'', '', __LINE__, __FILE__, $sql);
						}
					}
					group_reset_auth($data_id, $group_id);
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
								WHERE user_id = ' . $data_id . '
									AND team_id = ' . $teams_mark_lists[$i];
					if ( !($result = $db->sql_query($sql) ) )
					{
						message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
					}
					
					if ( !($row = $db->sql_fetchrow($result)) )
					{
						$sql = 'INSERT INTO ' . TEAMS_USERS . " (team_id, user_id, team_join) VALUES ($teams_mark_lists[$i], $data_id, " . time() . ")";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'Could not add user to checked teams', '', __LINE__, __FILE__, $sql);
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
								WHERE tu.user_id = ' . $data_id . '
									AND tu.team_id = ' . $team_id . '
									AND t.team_id = tu.team_id';
					if ( !($result = $db->sql_query($sql) ) )
					{
						message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
					}
					
					if ( $row = $db->sql_fetchrow($result) )
					{
						$sql = 'DELETE FROM ' . TEAMS_USERS . ' WHERE user_id = ' . $row['user_id'] . ' AND team_id = ' . $team_id;
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'Could not add delete user from group marked \'NO\'', '', __LINE__, __FILE__, $sql);
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
				WHERE u.user_id = $data_id
				AND ug.user_id = u.user_id
				AND g.group_id = ug.group_id
				AND ug.user_pending = 0";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Could not get group information', '', __LINE__, __FILE__, $group_sql);
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
				include($root_path . 'includes/emailer.'.$phpEx);
				$emailer = new emailer($board_config['smtp_delivery']);
				
				$emailer->from($board_config['board_email']);
				$emailer->replyto($board_config['board_email']);
				$emailer->use_template('current_groups', $user_lang);
				$emailer->email_address($user_email);
				$emailer->set_subject($lang['Group_added']);
				
				$emailer->assign_vars(array(
					'SITENAME' => $board_config['page_name'],
					'GROUP_NAMES' => $group_name,
					'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $board_config['board_email_sig']) : ''
				));
				
				$emailer->send();
				$emailer->reset();
			}
			*/
			
			log_add(LOG_ADMIN, $log, 'acpuser_groups');
		
			$message = $lang['user_change_groups']
				. sprintf($lang['click_return_user'], '<a href="' . append_sid($root_file) . '">', '</a>')
				. sprintf($lang['click_return_user_groups'], '<a href="' . append_sid("$file?mode=user_groups&$url=$data_id") . '">', '</a>');				
			message(GENERAL_MESSAGE, $message);
			
		break;
		
		case 'user_auth':
		
			$data = get_data(USERS, $data_id, 1);
			
			if ( $userdata['user_level'] < $data['user_level'] )
			{
				message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
			}
			
			$sql = 'SELECT g.group_id, ' . implode(', ', $auth_fields) . '
						FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
						WHERE g.group_id = gu.group_id
							AND gu.user_id = ' . $data_id . ' ORDER BY group_id';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$auths_data = array();
			while ($row_auths = $db->sql_fetchrow($result))
			{
				$group_ins[] = $row_auths['group_id'];
				$auths_data[$row_auths['group_id']] = $row_auths;
				
				unset($auths_data[$row_auths['group_id']]['group_id']);
			}
			$db->sql_freeresult($result);
			
			$sql = 'SELECT g.group_id, ' . implode(', ', $auth_fields) . '
						FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
						WHERE g.group_id = gu.group_id
							AND group_single_user = 0
							AND gu.user_id = ' . $data_id . ' ORDER BY group_id';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$authd_data = array();
			while ($row_authd = $db->sql_fetchrow($result))
			{
				$group_ind[] = $row_authd['group_id'];
				$authd_data[$row_authd['group_id']] = $row_authd;
				
				unset($authd_data[$row_authd['group_id']]['group_id']);
			}
			$db->sql_freeresult($result);
			
			$sql = 'SELECT group_id, ' . implode(', ', $auth_fields) . '
						FROM ' . GROUPS . '
						WHERE group_single_user = 0
							AND group_id IN (' . implode(', ', $group_ins) . ')
					ORDER BY group_id';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql2);
			}
			
			$auths_group = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$auths_group[$row['group_id']] = $row;
				
				unset($auths_group[$row['group_id']]['group_id']);
			}
			$db->sql_freeresult($result);
			
			$sql = 'SELECT group_id, ' . implode(', ', $auth_fields) . '
						FROM ' . GROUPS . '
						WHERE group_single_user = 0
							AND group_id IN (' . implode(', ', $group_ind) . ')
					ORDER BY group_id';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql2);
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
							
			for ( $j = 0; $j < count($auth_fields); $j++ )
			{
				if ( $userdata['user_level'] == ADMIN )
				{
					$selected_special	= ( $auths[$auth_fields[$j]] == $auth_const[2] ) ? " checked=\"checked\"" : "";
					$selected_default	= ( $auths[$auth_fields[$j]] != $auth_const[2] ) ? " checked=\"checked\"" : "";
				}
				else
				{
					$selected_special	= ( $auths[$auth_fields[$j]] == $auth_const[2] ) ? " checked=\"checked\" disabled" : " disabled";
					$selected_default	= ( $auths[$auth_fields[$j]] != $auth_const[2] ) ? " checked=\"checked\" disabled" : " disabled";
				}
				
				$custom_auths[$j] = '';
				$custom_auths[$j] .= '<label><input type="radio" onClick="set_color(\'color_' . $auth_fields[$j] . '\', \'#2e8b57\');" name="' . $auth_fields[$j] . '" id="' . $auth_fields[$j] . '" ';
				$custom_auths[$j] .= 'value="2" ' . $selected_special . '>&nbsp;' . $lang['group_' . $auth_levels[2]] . '</label>&nbsp;&nbsp;';
				$custom_auths[$j] .= '<label><input type="radio" onClick="set_color(\'color_' . $auth_fields[$j] . '\', \'#ff6347\');" name="' . $auth_fields[$j] . '" id="de_' . $auth_fields[$j] . '" ';
				$custom_auths[$j] .= 'value="3" ' . $selected_default . '>&nbsp;' . $lang['group_' . $auth_levels[3]];
				
				$custom_authd[$j] = ( $authd[$auth_fields[$j]] == $auth_const[0] ) ? $lang['group_' . $auth_levels[0]] . '</label>' : $lang['group_' . $auth_levels[1]] . '</label>';
			
				$cell = $lang['auths'][$auth_fields[$j]];
		
				$status = ( !$selected_default ) ? 'style="color: #2e8b57;"' : 'style="color: #ff6347;"';
				
				$template->assign_block_vars('user_auth.data', array(
					'TITLE'			=> $cell,
					'NAME'			=> $auth_fields[$j],
					
					'STATUS' 				=> $status,

					'S_SELECT'	=> $custom_auths[$j],
					'S_DEFAULT'	=> $custom_authd[$j],
				));
				
				$template->assign_block_vars('user_auth.list', array(
					'NAME'		=> $auth_fields[$j],
				));
			}
			
			$fields = '<input type="hidden" name="mode" value="editauths" /><input type="hidden" name="' . $url . '" value="' . $data_id . '" />';
			
			$template->assign_vars(array(
				'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_EDIT'			=> sprintf($lang['sprintf_edit'], $lang['user'], $data['username']),
				'L_AUTH'			=> $lang['user_auth'],
				'L_AUTH_EXPLAIN'	=> $lang['user_auth_explain'],

				'L_GROUPS'			=> $lang['user_groups'],
				'L_TEAMS'			=> $lang['user_teams'],
				
				'L_SPECIAL'		=> $lang['all_special'],
				'L_DEFAULT'		=> $lang['all_default'],

				'S_MODE'		=> $s_mode,
				'S_EDIT'		=> append_sid("$file?mode=_update&amp;$url=$data_id"),
				'S_ACTION'		=> append_sid($root_file),
				'S_FIELDS'		=> $fields,
			));
		
		break;
		
		case 'editauths':
		
			$sql = 'SELECT g.group_id
						FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
						WHERE g.group_id = gu.group_id
							AND gu.user_id = ' . $data_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$sql_auth = '';
			for ( $i = 0; $i < count($auth_fields); $i++ )
			{
				$value = intval($HTTP_POST_VARS[$auth_fields[$i]]);
				$value = ( $value == '3' ) ? '0' : '2';

				$sql_auth .= ( ( $sql_auth != '' ) ? ', ' : '' ) . $auth_fields[$i] . ' = ' . $value;
			}
			
			$sql = "UPDATE " . GROUPS . " SET
						
						$sql_auth
					WHERE group_id = " . $row['group_id'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			log_add(LOG_ADMIN, $log, 'acp_auths_edit');
			
			$message = $lang['user_change_auths']
				. sprintf($lang['click_return_user'], '<a href="' . append_sid($root_file) . '">', '</a>')
				. sprintf($lang['click_return_user_auths'], '<a href="' . append_sid("$file?mode=user_auth&$url=$data_id") . '">', '</a>');				
			message(GENERAL_MESSAGE, $message);
		
		break;
		
		case 'settings':
		
			$template->set_filenames(array('body' => 'style/acp_user.tpl'));
			$template->assign_block_vars('user_auths', array());
			
			$fields = '<input type="hidden" name="mode" value="editsettings" />';
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

			$template->assign_vars(array(
				'L_HEAD'			=> $lang['user_head'],
				'L_INPUT'		=> $lang['user_regedit'],
				'L_GROUP'			=> $lang['user_group'],
				'L_AUTHS'			=> $lang['user_auths'],
				
				'L_SUBMIT'				=> $lang['common_submit'],
				'L_RESET'				=> $lang['common_reset'],
				'L_YES'					=> $lang['common_yes'],
				'L_NO'					=> $lang['common_no'],
				
				'L_GROUPS'			=> $lang['user_groups'],
				'L_TEAMS'			=> $lang['user_teams'],

				'S_EDIT'			=> append_sid("$file?mode=edit&amp;$url=$data_id"),
				'S_GROUP'			=> append_sid("$file?mode=groups&amp;$url=$data_id"),
				'S_AUTHS'			=> append_sid("$file?mode=auths&amp;$url=$data_id"),
				'S_ACTION'			=> append_sid($root_file),
				'S_FIELDS'		=> $fields,
			));
		
			$template->pparse('body');
		
			break;
		
		case 'editsettings':
		
			debuge($_POST);
		
			break;
			
		default:
		
			$template->set_filenames(array('body' => 'style/acp_user.tpl'));
			$template->assign_block_vars('_display', array());
			
			$fields = '<input type="hidden" name="mode" value="_create" />';
	
			$data_user	= get_data_array(USERS, ' user_id <> ' . ANONYMOUS, 'user_id', 'DESC');
			
			for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_user)); $i++ )
			{
				switch ( $data_user[$i]['user_level'] )
				{
					case GUEST:		$user_level = $lang['auth_guest'];	break;
					case USER:		$user_level = $lang['auth_user'];	break;
					case TRIAL:		$user_level = $lang['auth_trial'];	break;
					case MEMBER:	$user_level = $lang['auth_member'];	break;
					case MOD:		$user_level = $lang['auth_mod'];	break;
					case ADMIN:		$user_level = $lang['auth_admin'];	break;
				}
				
				$user_id = $data_user[$i]['user_id'];
				
				if ( $userdata['user_level'] > $data_user[$i]['user_level'] )
				{
					$link_edit		= '<a href="' . append_sid("$file?mode=_update&amp;$url=$user_id") . '" ><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" ></a>';
					$link_delete	= '<a href="' . append_sid("$file?mode_=delete&amp;$url=$user_id") . '" ><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" ></a>';
				}
				else if ( $userdata['user_level'] == ADMIN )
				{
					$link_edit		= '<a href="' . append_sid("$file?mode=_update&amp;$url=$user_id") . '" ><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" ></a>';
					$link_delete	= '<a href="' . append_sid("$file?mode=_delete&amp;$url=$user_id") . '" ><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" ></a>';
				}
				else
				{
					$link_edit		= $lang['common_update'];
					$link_delete	= $lang['common_delete'];
				}
				
				$template->assign_block_vars('_display._user_row', array(
					'USER_LEVEL'	=> $user_level,
					'USERNAME'		=> $data_user[$i]['username'],
					'JOINED'		=> create_date($userdata['user_dateformat'], $data_user[$i]['user_regdate'], $userdata['user_timezone']),
					
					'EDIT'			=> $link_edit,
					'DELETE'		=> $link_delete,
				));
			}
			
			$current_page = ( !count($data_user) ) ? 1 : ceil( count($data_user) / $settings['site_entry_per_page'] );
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['user']),
				'L_NAME'	=> $lang['user'],
				'L_EXPLAIN'	=> $lang['user_explain'],
				
				'PAGE_NUMBER'	=> ( count($data_user) ) ? sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ) : '',
				'PAGE_PAGING'	=> ( count($data_user) ) ? generate_pagination($root_file . '?', count($data_user), $settings['site_entry_per_page'], $start ) : '',

				'S_FIELDS'	=> $fields,
				'S_CREATE'	=> append_sid("$file?mode=_create"),
				'S_ACTION'	=> append_sid($root_file),
			));
		
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>