<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_user'])
	{
		$module['hm_users']['sm_settings'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_settings_user';
	
	include('./pagestart.php');
	
	load_lang('users');
	
	$error	= '';
	$fields	= '';
	
	$log	= SECTION_USER;
	$url	= POST_USER;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	
#	$path_dir	= $root_path . $settings['path_user'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['user']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_user'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_games.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$auth_fields	= get_authlist();
	$auth_levels	= array('disallowed', 'allowed', 'special', 'default');
	$auth_const		= array(AUTH_DISALLOWED, AUTH_ALLOWED, AUTH_SPECIAL, AUTH_DEFAULT);
	
	$password_random = array(
		'0' => random_password(7, 10, false, false),
		'1' => random_password(7, 10, false, true, false),
		'2' => random_password(),
		'3' => random_password(7, 14, true, true, false),
		'4' => random_password(7, 14, true, true),
		'5' => random_password(7, 14, true, true, true, true),
	);
	
	if ( request('user_name', 1) )
	{
		$user_name = request('user_name', 1);
		
		$check = data(USERS, "user_name = '$user_name'", false, 1, 1);
		
		if ( $check )
		{
			$data_id = array_shift($check);
			
			$mode = '_update';
		}
		else
		{
			$data['user_name'] = $user_name;
			
			$mode = '_create';
		}
	}
	
	$temp = ( $mode == '_create' || $mode == '_update' ) ? '_input' : (!$mode ? '_display' : $mode);
	
	if ( $mode != '_create' )
	{
		$s_mode = '<select class="postselect" name="mode" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
		foreach ( $lang['_option'] as $key => $value )
		{
			$selected = ( $mode == $key ) ? ' selected="selected"' : '';
			$s_mode .= '<option value="' . $key . '"' . $selected . '>&raquo;&nbsp;' . $value . '&nbsp;</option>';
		}
		$s_mode .= '</select>';
		$s_mode .= " <input class=\"button2\" type=\"submit\" value=\"Go\" /><input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	}
	else
	{
		$s_mode = '';
	}
	
	function bday($date)
	{
		if ( strstr($date, '-') )
		{
			$elements = explode('-', $date);
			
			$return = sprintf("%s.%s.%s", $elements[2], $elements[1], $elements[0]);
		}
		else
		{
			$elements = explode('.', $date);
			
			$return = sprintf("%s-%s-%s", $elements[2], $elements[1], $elements[0]);
		}
		
		return $return;
	}
	
	$template->set_filenames(array(
		'body'	=> 'style/acp_user.tpl',
		'error'	=> 'style/info_error.tpl',
	));
	
	$template->assign_block_vars($temp, array());
	( $temp != '_display' ) ? $template->assign_block_vars('_head', array()) : '';
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
			
			if ( $mode == '_create' && !request('submit', 1) )
			{
				$data = array(
							'user_name'			=> request('user_name', 1),
							'user_regdate'		=> time(),
							'user_lastvisit'	=> time(),
							'user_email'		=> '',
							'user_founder'		=> '0',
							'user_level'		=> '0',
							'user_active'		=> '0',
							'user_birthday'		=> '',
						);
			}
			else if ( $mode == '_update' && !request('submit', 1) )
			{
				$data = data(USERS, $data_id, false, 1, 1);
				
				if ( $userdata['user_level'] < $data['user_level'] )
				{
					message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
				}
			}
			else
			{
				$data = array(
							'user_name'			=> request('user_name', 2),
							'user_regdate'		=> request('user_regdate', 0),
							'user_lastvisit'	=> request('user_lastvisit', 0),
							'user_email'		=> request('user_email', 2),
							'user_password'		=> request('user_password', 2),
							'user_founder'		=> request('user_founder', 0),
							'user_level'		=> request('user_level', 0),
							'user_active'		=> request('user_active', 0),
							'user_birthday'		=> request('user_birthday', 2),
						);
						
				$pass_gen =	request('password', 0);
				$pass_new = request('password_new', 2);
				$pass_con = request('password_confirm', 2);
				$mail_con = request('user_email_confirm', 2);
				$pass_switch = request('pass_switch', 0);
				
				$error .= check(USERS, array('user_name' => $data['user_name'], 'user_email' => $data['user_email'], 'user_id' => $data_id), $error);
			#	$error .= valid(user_name, user_email);
			
				if ( $mode == '_create' || $mode == '_update' && $mail_con )
				{
					$error .= ( $data['user_email'] != $mail_con ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_email_mismatch'] : '';
				}
				
				if ( $pass_switch )
				{
					if ( $pass_gen || $pass_gen == '0' )
					{
						$pass_rnd = $password_random[$pass_gen];
					}
					else
					{
						$error .= ( $error ? '<br />' : '' ) . $lang['msg_select_pass'];
					}
					
					$password		= $pass_rnd;
					$password_md5	= md5($pass_rnd);
				}
				else
				{
					if ( $mode == '_create' )
					{
						$error .= !$pass_new ? ( $error ? '<br />' : '' ) . $lang['msg_empty_pass'] : '';
						$error .= !$pass_con ? ( $error ? '<br />' : '' ) . $lang['msg_empty_pass_confirm'] : '';
						$error .= ( $pass_new != $pass_con ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_pass_mismatch'] : '';
						
						$password		= $pass_new;
						$password_md5	= md5($pass_new);
					}
					else if ( $mode == '_update' && ( !$pass_new && $data['user_password'] != $pass_new ) )
					{
						$password_md5 = $data['user_password'];
					}
					else
					{
						$error .= ( $pass_new != $pass_con ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_pass_mismatch'] : '';
						
						$password		= $pass_new;
						$password_md5	= md5($pass_new);
					}
				}
				
				if ( !$error )
				{
					$data['user_password'] = $password_md5;
					$data['user_birthday'] = bday($data['user_birthday']);
						
					if ( $mode == '_create' )
					{
						$sql = sql(USERS, $mode, $data);
						$uid = $db->sql_nextid();
						
						$grp = sql(GROUPS, $mode, array('group_name' => $data['user_name'], 'group_type' => '2', 'group_desc' => 'Personal User', 'group_single_user' => '1'));
						$gid = $db->sql_nextid();						
						
						$gus = sql(GROUPS_USERS, $mode, array('user_id' => $uid, 'group_id' => $gid, 'user_pending' => '0'));
						$gus = sql(GROUPS_USERS, $mode, array('user_id' => $uid, 'group_id' => '5', 'user_pending' => '0'));
					#	$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title . $pw);
						$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						
						/*	
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
							'USERNAME' => substr(str_replace("\'", "'", $user_name), 0, 25),
							'PASSWORD' => $new_password,
							'EMAIL_SIG' => str_replace('<br>', "\n", "-- \n" . $config['page_email_sig']),
						));
				
						$emailer->send();
						$emailer->reset();
						*/
					}
					else
					{
						$sql = sql(USERS, $mode, $data, 'user_id', $data_id);
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
					
					log_add(LOG_ADMIN, $log, 'error', $error);
				}
			}
			
			( $mode == '_update' ) ? $template->assign_block_vars($temp . '._update', array()) : '';
	
			
			if ( $userdata['user_founder'] )
			{
				$check_founder_no	= (!$data['user_founder'] ) ? ' checked="checked"' : '';
				$check_founder_yes	= ( $data['user_founder'] ) ? ' checked="checked"' : '';
			}
			else
			{
				$check_founder_no	= (!$data['user_founder'] ) ? ' disabled checked="checked"' : ' disabled';
				$check_founder_yes	= ( $data['user_founder'] ) ? ' disabled checked="checked"' : ' disabled';
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			$fields .= "<input type=\"hidden\" name=\"user_regdate\" value=\"" . $data['user_regdate'] . "\" />";
			$fields .= "<input type=\"hidden\" name=\"user_password\" value=\"" . $data['user_password'] . "\" />";
			$fields .= "<input type=\"hidden\" name=\"user_lastvisit\" value=\"" . $data['user_lastvisit'] . "\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['user'], $data['user_name']),
				'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['user']),
				'L_EMAIL'	=> $lang['email'],
				'L_CONFIRM'	=> $lang['email_confirm'],
				
				'L_REGISTER'	=> $lang['register'],
				'L_LASTLOGIN'	=> $lang['lastlogin'],
				'L_FOUNDER'		=> $lang['founder'],
				'L_ACTIVE'		=> $lang['active'],
				'L_LEVEL'		=> $lang['common_userlevel'],
				'L_BIRTHDAY'	=> $lang['birthday'],
				
				'L_PASSWORD'			=> $lang['password'],
				'L_PASSWORD_CONFIRM'	=> $lang['password_confirm'],
				'L_PASSWORD_INPUT'		=> $lang['password_input'],
				'L_PASSWORD_RANDOM'		=> $lang['password_generate'],
				
				'PASS_1'	=> sprintf($lang['password_random'], $password_random[0]),
				'PASS_2'	=> sprintf($lang['password_random'], $password_random[1]),
				'PASS_3'	=> sprintf($lang['password_random'], $password_random[2]),
				'PASS_4'	=> sprintf($lang['password_random'], $password_random[3]),
				'PASS_5'	=> sprintf($lang['password_random'], $password_random[4]),
				'PASS_6'	=> sprintf($lang['password_random'], $password_random[5]),
				
				'USERNAME'	=> $data['user_name'],
				
				'REGISTER'	=> create_date($userdata['user_dateformat'], $data['user_regdate'], $userdata['user_timezone']),
				'LASTLOGIN'	=> create_date($userdata['user_dateformat'], $data['user_lastvisit'], $userdata['user_timezone']),
				
				'USEREMAIL' => $data['user_email'],
				
				'BIRTHDAY'	=> bday($data['user_birthday']),
				
				'S_INPUT'	=> request('pass_switch', 0) ? 'checked="checked"' : '',
				'S_RANDOM'	=> request('pass_switch', 0) ? '' : 'checked="checked"',
				
				'RANDOM'	=> request('pass_switch', 0) ? '' : 'none',
				'INPUT'		=> request('pass_switch', 0) ? 'none' : '',
				
				'S_FOUNDER_NO'	=> $check_founder_no,
				'S_FOUNDER_YES'	=> $check_founder_yes,
				
				'S_ACTIVE_NO'	=> (!$data['user_active'] ) ? ' checked="checked"' : '',
				'S_ACTIVE_YES'	=> ( $data['user_active'] ) ? ' checked="checked"' : '',
				
				'S_LEVEL'	=> select_level('select', 'user_level', 'user_level', $data['user_level'], 0),
				
				'S_MODE'	=> $s_mode,
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			if ( request('submit', 1) )
			{
				
			}
		
			break;
			
		case '_settings':
		
			$data = data(USERS, $data_id, false, 1, 1);
			
			$viewemail	= $data['user_viewemail'];
			
			$show_sig		= $data['user_show_sig'];
			
			$notify			= $data['user_notify'];
			$notify_pm		= $data['user_notify_pm'];
			$popup_pm		= $data['user_popup_pm'];
			
			$allow_sig			= $data['user_allow_sig'];
			$allow_avatar		= $data['user_allow_avatar'];
			$allow_pm			= $data['user_allow_pm'];
			$allow_viewonline	= $data['user_allow_viewonline'];
			
			$rank_page	= $data['user_rank_page'];
			$rank_forum	= $data['user_rank_forum'];
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf_update'], $lang['user'], $data['user_name']),
				
				'ALLOW_AVATAR_NO'		=> (!$data['user_allow_avatar'] ) ? ' checked="checked"' : '',
				'ALLOW_AVATAR_YES'		=> ( $data['user_allow_avatar'] ) ? ' checked="checked"' : '',
				
				'ALLOW_PM_NO'			=> (!$data['user_allow_pm'] ) ? ' checked="checked"' : '',
				'ALLOW_PM_YES'			=> ( $data['user_allow_pm'] ) ? ' checked="checked"' : '',
				
				'ALLOW_SIG_NO'			=> (!$data['user_allow_sig'] ) ? ' checked="checked"' : '',
				'ALLOW_SIG_YES'			=> ( $data['user_allow_sig'] ) ? ' checked="checked"' : '',
				
				'ALLOW_VIEWONLINE_NO'	=> (!$data['user_allow_viewonline'] ) ? ' checked="checked"' : '',
				'ALLOW_VIEWONLINE_YES'	=> ( $data['user_allow_viewonline'] ) ? ' checked="checked"' : '',
				
				
				
				
				
				
				'VIEW_EMAIL_NO'		=> (!$data['user_view_email'] ) ? ' checked="checked"' : '',
				'VIEW_EMAIL_YES'	=> ( $data['user_view_email'] ) ? ' checked="checked"' : '',
				
				'SHOW_SIG_NO'		=> (!$data['user_show_sig'] ) ? ' checked="checked"' : '',
				'SHOW_SIG_YES'		=> ( $data['user_show_sig'] ) ? ' checked="checked"' : '',
				
				
				
				
				
				'S_LANG'		=> select_language($data['user_lang']),
			 	'S_STYLE'		=> select_style($data['user_style'], 'user_style'),
				'S_TIMEZONE'	=> select_tz($data['user_timezone'], 'page_timezone'),
				
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields
			));
		
			break;
		
		case '_fields':
		
			$data	= data(USERS, $data_id, false, 1, true);
			$data_p	= data(PROFILE_DATA, $data_id, false, 1, true);
		
			$cats	= data(PROFILE_CAT, false, 'cat_order', 1, false);
			
			if ( $cats )
			{
				for ( $i = 0; $i < count($cats); $i++ )
				{
					$cat_id = $cats[$i]['cat_id'];
					$cat_name = $cats[$i]['cat_name'];
					
					$profile = data(PROFILE, 'profile_cat = ' . $cat_id, 'profile_cat, profile_order ASC', 1, false);
				
					$template->assign_block_vars($temp . '._cat_row', array( 
						'CAT_ID'	=> $cat_id,
						'CAT_NAME'	=> $cat_name,
					));
					
					if ( !$profile )
					{
						$template->assign_block_vars($temp . '._cat_row._entry_empty', array());
					}
					else
					{
						for ( $j = 0; $j < count($profile); $j++ )
						{
							$profile_id = $profile[$j]['profile_id'];
							
							if ( $profile[$j]['profile_cat'] == $cat_id )
							{
								$name	= $profile[$j]['profile_name'];
								$field	= $profile[$j]['profile_field'];
								$req	= $profile[$j]['profile_necessary'] ? '*' : '';
								$value	= $data_p[$field];
								$input	= !$profile[$j]['profile_type'] ? "<input type=\"text\" class=\"post\" name=\"$field\" id=\"$field\" value=\"$value\" />" : "<textarea class=\"textarea\" name=\"$field\" id=\"$field\" rows=\"5\" cols=\"50\" />$value</textarea>";
								
								$template->assign_block_vars($temp . '._cat_row._field_row', array(
									'NAME'	=> "<label for=\"$field\">$name</label>: $req",
									'INPUT' => $input,
								));
								
							}
						}
					}
				}
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf_update'], $lang['user'], $data['user_name']),
				'S_MODE'	=> $s_mode,
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields
			));
			
			if ( request('submit', 1) )
			{
				$profile = data(PROFILE, false, 'profile_cat, profile_order ASC', 1, false);
				
				foreach ( $profile as $key => $value )
				{
					$_ary['name'][]		= $value['profile_name'];
					$_ary['field'][]	= $value['profile_field'];
					$_ary['type'][]		= $value['profile_type'];
					$_ary['require'][]	= $value['profile_necessary'];
				}
				
				for ( $i = 0; $i < count($_ary['field']); $i++ )
				{
					$request[$_ary['field'][$i]] = request($_ary['field'][$i], 2);
				}
				
				for ( $i = 0; $i < count($_ary['require']); $i++ )
				{
					if ( $_ary['require'][$i] )
					{
						$error .= !$request[$_ary['field'][$i]] ? ( $error ? '<br />' : '' ) . sprintf($lang['msg_select_profile_field'], $_ary['name'][$i]) : '';
					}
				}
				
				$request['user_id'] = $data_id;
				
				if ( !$error )
				{
					$sql = ( !$data_p ) ? sql(PROFILE_DATA, 'create', $request) : sql(PROFILE_DATA, 'update', $request, 'user_id', $data_id);
					$msg = $lang['update_fields'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					log_add(LOG_ADMIN, $log, 'error', $error);
					
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
				}
			}
			
			break;
		
		case 'delete':
		
			$confirm = isset($HTTP_POST_VARS['confirm']);
			
			if ( $data_id && $confirm )
			{
				$user = get_data('user', $data_id, 0);
			
				if ( $userdata['user_level'] < $data['user_level'] )
				{
					message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
				}			

				log_add(LOG_ADMIN, $log, 'ACP_USER_DELETE', $user_info['user_name']);
				
				#$oCache -> sCachePath = './../cache/';
				#$oCache -> deleteCache('display_subnavi_user');
				
				$message = $lang['delete_user'] . sprintf($lang['click_return_user'], '<a href="' . check_sid($file));
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
	
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_user']);
			}
			
			break;
		
		case '_groups':
			
			$data = data(USERS, $data_id, false, 1, 1);
			
			if ( $userdata['user_level'] < $data['user_level'] )
			{
				message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
			}
			
			$sql = "SELECT * FROM " . GROUPS . " WHERE group_single_user = 0 ORDER BY group_order";
			if ( !($groups = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $group = $db->sql_fetchrow($groups) )
			{
				$_auth = '';
				
				foreach ( $auth_fields as $value )
				{
					if ( $group[$value] )
					{
						$_auth[] = $lang[$value];
					}
				}
				
				$group_id	= $group['group_id'];
				$group_name	= $group['group_name'];
				$group_info	= is_array($_auth) ? sprintf($lang['auth_for'], implode(', ', $_auth)) : '';
				
				$sql = "SELECT user_id, user_pending, group_mod FROM " . GROUPS_USERS . " WHERE user_id = $data_id AND group_id = $group_id";
				if ( !($result_user = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
				}
				
				$member = ( $row = $db->sql_fetchrow($result_user) ) ? true : false;
				$neg_group_id = -1 * $group_id;
				
				if ( $group['group_type'] == GROUP_SYSTEM )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'disabled checked="checked"' : 'disabled';
					$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
					$s_mod_group		= ( $row['group_mod'] ) ? 'disabled checked' : 'disabled';
				}
				else if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? ' checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? '' : 'checked="checked"';
					$s_mod_group		= ( $row['group_mod'] ) ? 'checked="checked"' : '';
				}
				else if ( $userdata['user_id'] == $data['user_id'] )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? '' : 'checked="checked"';
					$s_mod_group		= ( $row['group_mod'] ) ? 'checked="checked"' : '';
				}
				else if ( $userdata['user_level'] > $group['group_access'] )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? ' checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? ' ' : 'checked="checked"';
					$s_mod_group		= ( $row['group_mod'] ) ? 'checked="checked"' : '';
				}
				else
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'disabled checked="checked"' : 'disabled';
					$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
					$s_mod_group		= ( $row['group_mod'] == '1' ) ? 'disabled checked' : 'disabled';
				}
				
				$s_mod_group .= ( !$member ) ? ' disabled' : '';
				
				$template->assign_block_vars('_groups._group_row', array(
					'GROUP_NAME'	=> $group_name,
					'GROUP_INFO'	=> $group_info,
					'GROUP_FIELD'	=> "group_" . $group_id,
					
					'S_MARK_NAME'			=> "marks_group[$group_id]",
					'S_MARK_ID'				=> $group_id,
					'S_NEG_MARK_ID'			=> $neg_group_id,
					'S_ASSIGNED_GROUP'		=> $s_assigned_group,
					'S_UNASSIGNED_GROUP'	=> $s_unassigned_group,
					'S_MOD_GROUP'			=> $s_mod_group,
					'U_USER_PENDING'		=> ( $row['user_pending'] ) ? $lang['Membership_pending'] : '',
					
				));
			}
			$db->sql_freeresult($result);
			
			$sql = "SELECT team_id, team_name FROM " . TEAMS . " ORDER BY team_order";
			if ( !($teams = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $team = $db->sql_fetchrow($teams) )
			{
				$team_id	= $team['team_id'];
				$team_name	= $team['team_name'];

				$sql = "SELECT user_id, team_mod FROM " . TEAMS_USERS . " WHERE user_id = $data_id AND team_id = $team_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
				}
				
				$member = ( $row = $db->sql_fetchrow($result) ) ? TRUE : FALSE;
				$neg_team_id = -1 * $team_id;
				
				$s_assigned_team	= ( $member ) ? 'checked="checked"' : '';
				$s_unassigned_team	= ( $member ) ? '' : 'checked="checked"';
				$s_mod_team	= ( $row['team_mod'] ) ? 'checked="checked"' : '';
				$s_mod_team .= ( !$member ) ? ' disabled' : '';
				
				$template->assign_block_vars('_groups._team_row', array(
					'S_MARK_NAME'		=> "marks_team[$team_id]",
					'S_MARK_ID'			=> $team_id,
					'S_NEG_MARK_ID'		=> $neg_team_id,
					
					'S_ASSIGNED_TEAM'	=> $s_assigned_team,
					'S_UNASSIGNED_TEAM'	=> $s_unassigned_team,
					'S_MOD_TEAM'		=> $s_mod_team,
					
					'U_TEAM_NAME'		=> $team_name
				));
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf_update'], $lang['user'], $data['user_name']),
				
				'L_GROUPS'	=> $lang['usergroups'],
				'L_TEAMS'	=> $lang['teams'],
				
				'L_MAIL'	=> $lang['mail'],
				'L_MOD'		=> $lang['mod'],
				
				'S_MODE'	=> $s_mode,
								
				'S_ACTION'	=> check_sid($file),		
				'S_FIELDS'	=> $fields,
			));
			
			if ( request('submit', 1) )
			{
				$mod_team		= request('mod_team', 4);
				$email_team		= request('email_team', 0);
				$marks_team		= request('marks_team', 4);
				
				$mod_group		= request('mod_group', 4);
				$email_group	= request('email_group', 0);
				$marks_group	= request('marks_group', 4);
				
				$marks_groups = $marks_teams = array();
				
				foreach ( $marks_group as $key => $value )
				{
					$marks_groups[] = $value;
				}
				
				foreach ( $marks_team as $key => $value )
				{
					$marks_teams[] = $value;
				}
				
				// now add the user to any group checked 'YES' if he is not already in that group
				$temp_count = 0;
				
				for ( $i = 0; $i < count($marks_groups); $i++ )
				{
					if ( $marks_groups[$i] > 0 )
					{
						// test to see if there is already an entry for this group and user; if so, skip the insert and go to the next group...
						$sql = "SELECT user_id, user_pending FROM " . GROUPS_USERS . " WHERE user_id = $data_id AND group_id = " . $marks_groups[$i];
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( !($row = $db->sql_fetchrow($result)) )
						{
							// here, we actually put the user into a selected group if there was no entry for the user in this group
							$sql = "INSERT INTO " . GROUPS_USERS . " (group_id, user_id, user_pending) VALUES (" . $marks_groups[$i] . ", $data_id, 0)";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							$temp_count = 1;
						}
						else
						{
							// here, we update the user's entry in the group if his membership was pending and the admin checked YES for the group...
							if ( $row['user_pending'] == 1 )
							{
								$sql = "UPDATE " . GROUPS_USERS . " SET user_pending = 0 WHERE group_id = " . $marks_groups[$i] . " AND user_id = $data_id";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$temp_count = 1;
							}
						}
						group_set_auth($data_id, $marks_groups[$i]);
						
						$sql = "SELECT group_mod FROM " . GROUPS_USERS . " WHERE user_id = $data_id AND group_id = " . $marks_groups[$i];
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( ($row = $db->sql_fetchrow($result)) )
						{
							$mod = ( request('mod_group_' . $marks_groups[$i], 1) == 'on' ) ? 1 : 0;
							
							$sql = "UPDATE " . GROUPS_USERS . " SET group_mod = $mod WHERE user_id = $data_id AND group_id = " . $marks_groups[$i];
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}
				}
				
				// now delete the user from any group that is checked 'NO' if he is currently in that group and is not the moderator (but don't allow deletion of moderator)
				for ( $i = 0; $i < count($marks_groups); $i++ )
				{
					if ( $marks_groups[$i] < 0 )
					{
						// delete user from any group checked 'NO'.
						$group_id = (-1) * $marks_groups[$i];
						
						$sql = "SELECT user_id
									FROM " . GROUPS . " g, " . GROUPS_USERS . " gu
								WHERE gu.user_id = $data_id
									AND g.group_single_user = 0
									AND gu.group_id = $group_id
									AND g.group_id = gu.group_id";
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( $row = $db->sql_fetchrow($result) )
						{
							// here, we actually delete the user from the group if there an entry for the user in the group
							$sql = "DELETE FROM " . GROUPS_USERS . " WHERE user_id = " . $row['user_id'] . " AND group_id = $group_id";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
						group_reset_auth($data_id, $group_id);
					}
				}
				
				$temp_count = 0;
				
				for ( $i = 0; $i < count($marks_teams); $i++ )
				{
					if ( $marks_teams[$i] > 0 )
					{
						$sql = "SELECT user_id FROM " . TEAMS_USERS . " WHERE user_id = $data_id AND team_id = " . $marks_teams[$i];
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( !($row = $db->sql_fetchrow($result)) )
						{
							$sql = "INSERT INTO " . TEAMS_USERS . " (team_id, user_id, team_join) VALUES (" . $marks_teams[$i] . ", $data_id, " . time() . ")";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$temp_count = 1;
						}
						
						$sql = "SELECT team_mod FROM " . TEAMS_USERS . " WHERE user_id = $data_id AND team_id = " . $marks_teams[$i];
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
						}
						
						if ( ($row = $db->sql_fetchrow($result)) )
						{
							$mod = ( request('mod_team_' . $marks_teams[$i], 1) == 'on' ) ? 1 : 0;
							
							$sql = "UPDATE " . TEAMS_USERS . " SET team_mod = $mod WHERE user_id = $data_id AND team_id = " . $marks_teams[$i];
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}
				}
				
				// now delete the user from any group that is checked 'NO' if he is currently in that group and is not the moderator (but don't allow deletion of moderator)
				for ( $i = 0; $i < count($marks_teams); $i++ )
				{
					if ( $marks_teams[$i] < 0 )
					{
						// delete user from any group checked 'NO'.
						$team_id = (-1) * $marks_teams[$i];
						
						$sql = "SELECT user_id
									FROM " . TEAMS . " t, " . TEAMS_USERS . " tu
								WHERE tu.user_id = $data_id
									AND tu.team_id = $team_id
									AND t.team_id = tu.team_id";
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( $row = $db->sql_fetchrow($result) )
						{
							$sql = "DELETE FROM " . TEAMS_USERS . " WHERE user_id = " . $row['user_id'] . " AND team_id = $team_id";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}
				}
				
				// now, find out if user has been added to any groups and the admin wants a notification email sent out
				/*
				if ( $temp_count == 1 && $email_notification == 1 )
				{
					
					$group_name = '';
					$sql = "SELECT g.group_name, u.user_name, u.user_email, u.user_lang
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
					$user_name = $row['user_name'];
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
				
				$msg = $lang['update_groups'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data_id"));				
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $msg);
			}
		
			break;
		
		case '_auth':
		
			$data = data(USERS, $data_id, false, 1, 1);
			
			if ( $userdata['user_level'] < $data['user_level'] )
			{
				message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
			}
			
			/* Gruppen in denen der Benutzer vorhanden ist, mit private Tabelle */
			$sql = "SELECT g.group_id, " . implode(', ', $auth_fields) . " FROM " . GROUPS . " g, " . GROUPS_USERS . " gu WHERE g.group_id = gu.group_id AND gu.user_id = $data_id ORDER BY g.group_id ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$grps_auth = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
					
			foreach ( $grps_auth as $key => $value )
			{
				$auths_data[$value['group_id']] = $value;
			}
			
			$group_ins = array_keys($auths_data);
			
		#	debug($group_ins);
		#	debug($auths_data);
			
			/* Gruppen in denen der Benutzer vorhanden ist, ohne private Tabelle */
			$sql = "SELECT g.group_id, group_name, " . implode(', ', $auth_fields) . " FROM " . GROUPS . " g, " . GROUPS_USERS . " gu WHERE g.group_id = gu.group_id AND g.group_single_user = 0 AND gu.user_id = $data_id ORDER BY g.group_id ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$grps_auth = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
					
			foreach ( $grps_auth as $key => $value )
			{
				$authd_data[$value['group_id']] = $value;
			}
			
			$group_ind = array_keys($authd_data);
			
		#	debug($group_ind);
		#	debug($authd_data);
			
			$sql = "SELECT group_id, " . implode(', ', $auth_fields) . " FROM " . GROUPS . " WHERE group_single_user = 0 AND group_id IN (" . implode(', ', $group_ins) . ") ORDER BY group_id";
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
			
			$sql = 'SELECT group_id, group_name, ' . implode(', ', $auth_fields) . ' FROM ' . GROUPS . ' WHERE group_single_user = 0 AND group_id IN (' . implode(', ', $group_ind) . ') ORDER BY group_id';
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
				foreach ( $group_ids as $group_key => $group_id )
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
			
			$auth_grp = '';
			
			$authd		= array();
			$group_ids	= array_keys($authd_group);
		
			foreach ( $authd_data as $grp_info )
			{
				foreach( $grp_info as $key => $value )
				{
					if ( $value == '1' )
					{
						$auth_grp[$key][] = $grp_info['group_name'];
					}
					
					if ( $value == 0 )
					{
						if ( !array_key_exists($key, $authd) )
						{
							$authd[$key] = $authd_group[$group_id][$key];
						}
						else if ( !$authd[$key] )
						{
							$authd[$key] = $authd_group[$group_id][$key];
						}
					}
					else
					{
						$authd[$key] = $value;
					}
				}
			}
			
			if ( $auth_grp )
			{			
				$_ary_auth = array_keys($auth_grp);
				$_ary_diff = array_diff($auth_fields, $_ary_auth);
			
				foreach ( $auth_fields as $key => $value )
				{
					$ary_diff[$value] = '';
				}
			
				$_ary_new = array_merge($ary_diff, $auth_grp);
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
				
				$info = '';
				$authd_info[$j] = '';
				
				if ( is_array($auth_grp) )
				{
					foreach ( $auth_grp as $key => $ary )
					{
						if ( $key == $auth_fields[$j] )
						{
							$authd_info[$j] .= implode(', ', $ary);
						}
					}
					
					$info = ( $authd_info[$j] ) ? sprintf($lang['auth_from'], $authd_info[$j]) : '';
				}
				
				$custom_auths[$j] = '';
				$custom_auths[$j] .= '<label><input type="radio" onClick="set_color(\'color_' . $auth_fields[$j] . '\', \'#2e8b57\');" name="' . $auth_fields[$j] . '" id="' . $auth_fields[$j] . '" ';
				$custom_auths[$j] .= 'value="2" ' . $selected_special . '>&nbsp;' . $lang['auth_' . $auth_levels[2]] . '</label>&nbsp;&nbsp;';
				$custom_auths[$j] .= '<label title="' . $info . '"><input type="radio" onClick="set_color(\'color_' . $auth_fields[$j] . '\', \'#ff6347\');" name="' . $auth_fields[$j] . '" id="de_' . $auth_fields[$j] . '" ';
				$custom_auths[$j] .= 'value="3" ' . $selected_default . '>&nbsp;' . $lang['auth_' . $auth_levels[3]];
				
				$custom_authd[$j] = ( $authd[$auth_fields[$j]] == $auth_const[0] ) ? $lang['auth_' . $auth_levels[0]] . '</label>' : $lang['auth_' . $auth_levels[1]] . '</label>';
				
				$cell = $lang['auths'][$auth_fields[$j]];
		
				$status = ( !$selected_default ) ? 'style="color: #2e8b57;"' : 'style="color: #ff6347;"';
				
				$template->assign_block_vars('_auth._data', array(
					'TITLE'		=> $cell,
					'NAME'		=> $auth_fields[$j],
					
					'STATUS'	=> $status,

					'S_SELECT'	=> $custom_auths[$j],
					'S_DEFAULT'	=> $custom_authd[$j],
				));
				
				$template->assign_block_vars('_auth._list', array(
					'NAME'	=> $auth_fields[$j],
				));
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf_update'], $lang['user'], $data['user_name']),
				'L_AUTH'	=> $lang['auth'],
				'L_EXPLAIN'	=> $lang['auth_explain'],
				
				'L_SPECIAL'	=> $lang['all_special'],
				'L_DEFAULT'	=> $lang['all_default'],
				
				'S_MODE'	=> $s_mode,
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			if ( request('submit', 1) )
			{
				$sql = "SELECT g.group_id FROM " . GROUPS . " g, " . GROUPS_USERS . " gu WHERE g.group_id = gu.group_id AND gu.user_id = $data_id";
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
				
				$sql = "UPDATE " . GROUPS . " SET $sql_auth WHERE group_id = " . $row['group_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$msg = $lang['update_auth'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data_id"));				
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $msg);
			}
		
			break;
		
		default:
		
			$fields .= '<input type="hidden" name="mode" value="_create" />';
	
			$data = data(USERS, 'user_id != 1', 'user_id DESC', 1, false);
			
			for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data)); $i++ )
			{
				switch ( $data[$i]['user_level'] )
				{
					case GUEST:		$user_level = $lang['auth_guest'];	break;
					case USER:		$user_level = $lang['auth_user'];	break;
					case TRIAL:		$user_level = $lang['auth_trial'];	break;
					case MEMBER:	$user_level = $lang['auth_member'];	break;
					case MOD:		$user_level = $lang['auth_mod'];	break;
					case ADMIN:		$user_level = $lang['auth_admin'];	break;
				}
				
				$user_id = $data[$i]['user_id'];
				
				if ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $data[$i]['user_level'] )
				{
					$link_auth		= '<a href="' . check_sid("$file?mode=_auth&amp;$url=$user_id") . '" ><img src="' . $images['icon_option_auth'] . '" title="" alt="" /></a> ';
					$link_groups	= '<a href="' . check_sid("$file?mode=_groups&amp;$url=$user_id") . '" ><img src="' . $images['icon_option_group'] . '" title="" alt="" /></a> ';
					$link_fields	= '<a href="' . check_sid("$file?mode=_fields&amp;$url=$user_id") . '" ><img src="' . $images['icon_option_field'] . '" title="" alt="" /></a> ';
					
					$link_update	= '<a href="' . check_sid("$file?mode=_update&amp;$url=$user_id") . '" ><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a> ';
					$link_delete	= '<a href="' . check_sid("$file?mode=_delete&amp;$url=$user_id") . '" ><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a> ';
				}
				else
				{
					$link_auth = $link_fields = $link_update = $link_delete = '';
				}
				
				$template->assign_block_vars('_display._user_row', array(
					'USERNAME'	=> $data[$i]['user_name'],
					'REGISTER'	=> create_date($userdata['user_dateformat'], $data[$i]['user_regdate'], $userdata['user_timezone']),
					'LEVEL'		=> $user_level,
					
					'LINKS'		=> $link_auth . $link_groups . $link_fields . $link_update . $link_delete,
				));
			}
			
			$current_page = ( !count($data) ) ? 1 : ceil( count($data) / $settings['site_entry_per_page'] );
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf_new_creates'], $lang['user']),
				'L_NAME'	=> $lang['user'],
				'L_EXPLAIN'	=> $lang['explain'],
				
				'PAGE_NUMBER'	=> ( count($data) ) ? sprintf($lang['common_page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ) : '',
				'PAGE_PAGING'	=> ( count($data) ) ? generate_pagination($file . '?', count($data), $settings['site_entry_per_page'], $start ) : '',

				
				'S_CREATE'	=> check_sid("$file?mode=_create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>