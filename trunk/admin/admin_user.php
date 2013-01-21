<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_user',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_user'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_user';
	
	include('./pagestart.php');
	
	add_lang('users');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_USER;
	$time	= time();
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_users']['path'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['user']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_user'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_user.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
#	$auth_levels	= array('disallowed', 'allowed', 'special', 'default');
#	$auth_const		= array(AUTH_DISALLOWED, AUTH_ALLOWED, AUTH_SPECIAL, AUTH_DEFAULT);
	
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
			$data = array_shift($check);
			
			$mode = 'update';
		}
		else
		{
			$data['user_name'] = $user_name;
			
			$mode = 'create';
		}
	}
	
	$temp = ( $mode == 'create' || $mode == 'update' ) ? 'input' : (!$mode ? 'display' : $mode);
	
	if ( $mode != 'create' )
	{
		$s_mode = '<select class="postselect" name="mode" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
		foreach ( $lang['option'] as $key => $value )
		{
			$selected = ( $mode == $key ) ? ' selected="selected"' : '';
			$s_mode .= '<option value="' . $key . '"' . $selected . '>&raquo;&nbsp;' . $value . '&nbsp;</option>';
		}
		$s_mode .= '</select>';
		$s_mode .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
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
	( $temp != 'display' ) ? $template->assign_block_vars('head', array()) : '';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$vars = array(
				'user' => array(
					'title1' => 'input_data',
					'user_name'			=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name', 'check' => true),
					'user_email'		=> array('validate' => MAL,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_mail', 'check' => true),
					'email_confirm'		=> array('validate' => MAL,	'explain' => false,	'type' => 'text:25;25'),
					'user_regdate'		=> ($mode != 'create') ? array('validate' => INT,	'explain' => false,	'type' => 'show:time') : 'hidden',
					'user_lastvisit'	=> ($mode != 'create') ? array('validate' => INT,	'explain' => false,	'type' => 'show:time') : 'hidden',
					
					'user_level'		=> array('validate' => INT,	'explain' => false,	'type' => 'drop:userlevel'),
					'user_founder'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'user_active'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'user_birthday'		=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25'),
					
					'password_type'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:password', 'params' => array('type', false, false)),
				),
			);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'user_name'			=> request('user_name', TXT),
					'user_regdate'		=> $time,
					'user_lastvisit'	=> $time,
					'user_email'		=> '',
					'user_founder'		=> 0,
					'user_level'		=> 0,
					'user_active'		=> 0,
					'user_birthday'		=> '',
					'password_type'		=> 1,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(USERS, $data, false, 1, true);
				
				$data_sql['password_type'] = 1;
				
			#	if ( $userdata['user_level'] < $data['user_level'] )
			#	{
			#		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
			#	}
			}
			else
			{
			#	$data_sql = build_request(USERS, $vars, $error, $mode);
				$data_sql = build_request(USERS, $vars, $error, $mode, false, 'password_type');
				/*
				$data_sql = array(
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
						
				$pass_gen =	request('password', INT);
				$pass_new = request('password_new', 2);
				$pass_con = request('password_confirm', 2);
				$mail_con = request('user_email_confirm', 2);
				$pass_switch = request('pass_switch', INT);
				
				$error[] = check(USERS, array('user_name' => $data['user_name'], 'user_email' => $data['user_email'], 'user_id' => $data), $error);
			#	$error[] = valid(user_name, user_email);
			
				if ( $mode == 'create' || $mode == 'update' && $mail_con )
				{
					$error[] = ( $data['user_email'] != $mail_con ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_email_mismatch'] : '';
				}
				
				if ( $pass_switch )
				{
					if ( $pass_gen || $pass_gen == '0' )
					{
						$pass_rnd = $password_random[$pass_gen];
					}
					else
					{
						$error[] = ( $error ? '<br />' : '' ) . $lang['msg_select_pass'];
					}
					
					$password		= $pass_rnd;
					$password_md5	= md5($pass_rnd);
				}
				else
				{
					if ( $mode == 'create' )
					{
						$error[] = !$pass_new ? ( $error ? '<br />' : '' ) . $lang['msg_empty_pass'] : '';
						$error[] = !$pass_con ? ( $error ? '<br />' : '' ) . $lang['msg_empty_pass_confirm'] : '';
						$error[] = ( $pass_new != $pass_con ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_pass_mismatch'] : '';
						
						$password		= $pass_new;
						$password_md5	= md5($pass_new);
					}
					else if ( $mode == 'update' && ( !$pass_new && $data['user_password'] != $pass_new ) )
					{
						$password_md5 = $data['user_password'];
					}
					else
					{
						$error[] = ( $pass_new != $pass_con ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_pass_mismatch'] : '';
						
						$password		= $pass_new;
						$password_md5	= md5($pass_new);
					}
				}
				*/
				/*
				if ( !$error )
				{
					$data['user_password'] = $password_md5;
					$data['user_birthday'] = bday($data['user_birthday']);
						
					if ( $mode == 'create' )
					{
						$sql = sql(USERS, $mode, $data_sql);
						$uid = $db->sql_nextid();
						
						$grp = sql(GROUPS, $mode, array('group_name' => $data['user_name'], 'group_type' => '2', 'group_desc' => 'Personal User', 'group_single_user' => '1'));
						$gid = $db->sql_nextid();						
						
						$gus = sql(GROUPS_USERS, $mode, array('user_id' => $uid, 'group_id' => $gid, 'user_pending' => '0'));
						$gus = sql(GROUPS_USERS, $mode, array('user_id' => $uid, 'group_id' => '5', 'user_pending' => '0'));
					#	$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title . $pw);
						$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						
						
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
						
					}
					else
					{
						$sql = sql(USERS, $mode, $data_sql, 'user_id', $data);
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
				*/
			}
			
			( $mode == 'update' ) ? $template->assign_block_vars($temp . '.update', array()) : '';
			
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
			$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
			$fields .= "<input type=\"hidden\" name=\"user_regdate\" value=\"" . $data['user_regdate'] . "\" />";
			$fields .= "<input type=\"hidden\" name=\"user_password\" value=\"" . $data['user_password'] . "\" />";
			$fields .= "<input type=\"hidden\" name=\"user_lastvisit\" value=\"" . $data['user_lastvisit'] . "\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf_' . $mode], $lang['user'], $data['user_name']),
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
				
				'S_LEVEL'	=> select_level($data['user_level'], 'user_level', 0),
				
				'S_MODE'	=> $s_mode,
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
				
				'NONE_INPUT'	=> ($data_sql['password_type']) ? '' : 'none',
				'NONE_PASSWORD'	=> (!$data_sql['password_type']) ? '' : 'none',
			));
			
			
			build_output(USERS, $vars, $data_sql);

			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_' . $mode], $lang['title'], lang($data_sql['user_name'])),
				'L_EXPLAIN'	=> $lang['common_required'],

				'S_MODE'	=> $s_mode,
				'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'	=> $fields,
			));

			break;
			
		case 'settings':
		
			$data_sql = data(USERS, $data, false, 1, true);
			
			$display_vars = array(
				'setting'	=> array(
					'title1' => 'sub',
					
					'user_allow_viewonline'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Darf sich verstecken oder nicht					
					'user_viewemail'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Zeige meine E-Mail-Adresse immer an
					'user_attachsig'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Signatur immer anhängen
					
					'user_notify'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Bei Antworten immer benachrichtigen
					'user_notify_pm'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Bei neuen Privaten Nachrichten benachrichtigen
					'user_popup_pm'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// PopUp-Fenster bei neuen Privaten Nachrichten
					'user_send_type'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	//					
					
					'user_lang'				=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:lang'),
					'user_style'			=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:style'),
					'user_timezone'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:tz'),
					'user_dateformat'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25'),
					
					'tab2' => 'admin',
					'user_allow_sig'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Signatur erlauben oder sperren
					'user_allow_avatar'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Avatar erlauben oder sperren
					'user_allow_pm'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Private Nachrichten erlauben oder sperren
					
				),
			);
			
			build_output($data, $display_vars, 'settings');
			
			$rank_page	= $data['user_rank_page'];
			$rank_forum	= $data['user_rank_forum'];
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf_update'], $lang['user'], $data['user_name']),
				
				'S_MODE'	=> $s_mode,
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields
			));
		
			break;
		
		case 'fields':
			
			$data_sql	= data(USERS, $data, false, 1, true);
			$profile	= data(PROFILE, false, 'main ASC, profile_order ASC', 1, false);
			$info		= data(PROFILE_DATA, $data, false, 1, true);
			
			if ( $profile )
			{
				foreach ( $profile as $row )
				{
					if ( !$row['main'] )
					{
						$cat[$row['profile_id']] = $row['profile_name'];
					}
					else
					{
						$entry[$row['main']][$row['profile_id']] = $row;
					}
				}
				
				foreach ( $cat as $key => $value )
				{
					if ( isset($entry[$key]) )
					{
						$template->assign_block_vars("$temp.cat", array('CAT_NAME' => $value));
						
						foreach ( $entry[$key] as $field => $row )
						{
							$name	= $row['profile_name'];
							$field	= $row['profile_field'];
							$req	= $row['profile_required'] ? 'r' : '';
							$value	= request($field, 'text') ? request($field, 'text') : $info[$field];
							
							$request['user_id'] = $data;
							$request[$field] = $value;
						
							if ( $row['profile_required'] )
							{
								$error[] = !$value ? ( $error ? '<br />' : '' ) . sprintf($lang['msg_select_profile_field'], $name) : '';
							}
							
							if ( $row['profile_typ'] == 0 )
							{
								$input = '<input type="text" name="' . $field . '" id="' . $field . '" value="' . $value . '" />';
							}
							else if ( $row['profile_typ'] == 1 )
							{
								$input = '<textarea name="' . $field . '" id="' . $field . '" cols="30\" />' . $value . '</textarea>';
							}
							else if ( $row['profile_typ'] == 2 )
							{
								$checked_yes = ($value) ? 'checked="checked"' : '';
								$checked_no = (!$value) ? 'checked="checked"' : '';
								$input = '<label><input type="radio" name="' . $field . '" value="1" ' . $checked_yes . ' />&nbsp;' . $lang['common_yes'] . '</label><span style="padding:4px;"></span><label><input type="radio" name="' . $field . '" value="0" ' . $checked_no . ' />&nbsp;' . $lang['common_no'] . '</label>';
							}
							
							$template->assign_block_vars("$temp.cat.field", array(
								'REQ'	=> $req,
								'NAME'	=> '<label for="' . $field . '">' . $name . '</label>',
								'INPUT' => $input,
							));
						}
					}
				}
			}
			
		#	debug($tmp);
		#	debug($cat);
		#	debug($entry);
			
			if ( $submit )
			{
				if ( !$error )
				{
					$sql = ( !$info ) ? sql(FIELDS_DATA, 'create', $request) : sql(FIELDS_DATA, 'update', $request, 'user_id', $data);
					$msg = $lang['update_fields'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf_update'], $lang['user'], $data['user_name']),
				
				'S_MODE'	=> $s_mode,
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields
			));
			
			break;
		
		case 'delete':
		
			$confirm = isset($HTTP_POST_VARS['confirm']);
			
			if ( $data && $confirm )
			{
				$user = get_data('user', $data, INT);
			
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
			else if ( $data && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="id" value="' . $data . '" />';
	
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
		
		case 'groups':
			
			$data_sql = data(USERS, $data, false, 1, true);
			
			if ( $userdata['user_level'] < $data['user_level'] )
			{
				message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
			}
			
			$sql = "SELECT group_id, group_type, group_name, group_color FROM " . GROUPS . " ORDER BY group_order";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$groups[$row['group_id']] = $row;
			}
			$db->sql_freeresult($result);
			
			$sql = "SELECT t.team_id, t.team_name, g.game_image FROM " . TEAMS . " t, " . GAMES . " g WHERE t.team_game = g.game_id ORDER BY t.team_order";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$teams[$row['team_id']] = $row;
			}
			$db->sql_freeresult($result);
			
			$sql = "SELECT * FROM " . LISTS . " WHERE type IN (" . TYPE_GROUP . ", " . TYPE_TEAM .") AND user_id = " . $data;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				if ( $row['type'] == TYPE_GROUP )
				{
					$in_groups[TYPE_GROUP][$row['type_id']] = $row;
				}
				else
				{
					$in_groups[TYPE_TEAM][$row['type_id']] = $row;
				}
			}
			$db->sql_freeresult($result);
			
			foreach ( $groups as $key => $row )
			{
				$group_id	= $row['group_id'];
				$group_name	= $row['group_name'];
				$group_info	= '';
				
				$member = (isset($in_groups[TYPE_GROUP][$key])) ? true : false;
				$neg_group_id = -1 * $group_id;
				
				$ustatus	= ($member) ? $in_groups[TYPE_GROUP][$key]['user_status'] : false;
				$upending	= ($member) ? $in_groups[TYPE_GROUP][$key]['user_pending'] : false;
				
				if ( $row['group_type'] == GROUP_SYSTEM )
				{
					$s_assigned_group	= ( $member && !$upending ) ? 'disabled checked="checked"' : 'disabled';
					$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
					$s_mod_group		= ( $ustatus ) ? 'disabled checked' : 'disabled';
				}
				else if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
				{
					$s_assigned_group	= ( $member && !$upending ) ? ' checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? '' : 'checked="checked"';
					$s_mod_group		= ( $ustatus ) ? 'checked="checked"' : '';
				}
				else if ( $userdata['user_id'] == $data['user_id'] )
				{
					$s_assigned_group	= ( $member && !$upending ) ? 'checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? '' : 'checked="checked"';
					$s_mod_group		= ( $ustatus ) ? 'checked="checked"' : '';
				}
				else if ( $userdata['user_level'] > $row['group_access'] )
				{
					$s_assigned_group	= ( $member && !$upending ) ? ' checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? ' ' : 'checked="checked"';
					$s_mod_group		= ( $ustatus ) ? 'checked="checked"' : '';
				}
				else
				{
					$s_assigned_group	= ( $member && !$upending ) ? 'disabled checked="checked"' : 'disabled';
					$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
					$s_mod_group		= ( $ustatus ) ? 'disabled checked' : 'disabled';
				}
				
				$s_mod_group .= ( !$member ) ? ' disabled' : '';
				
				$template->assign_block_vars('groups.row_group', array(
					'GROUP_NAME'	=> $group_name,
					'GROUP_INFO'	=> $group_info,
					'GROUP_FIELD'	=> "group_" . $group_id,
					
					'S_MARK_NAME'			=> "marks_group[$group_id]",
					'S_MARK_ID'				=> $group_id,
					'S_NEG_MARK_ID'			=> $neg_group_id,
					'S_ASSIGNED_GROUP'		=> $s_assigned_group,
					'S_UNASSIGNED_GROUP'	=> $s_unassigned_group,
					'S_MOD_GROUP'			=> $s_mod_group,
					'U_USER_PENDING'		=> ( $upending ) ? $lang['Membership_pending'] : '',
					
				));
			}
			
			foreach ( $teams as $key => $row )
			{
				$team_id	= $row['team_id'];
				$team_name	= $row['team_name'];
				
				$member = (isset($in_groups[TYPE_TEAM][$key])) ? true : false;
				$neg_team_id = -1 * $team_id;
				
				$ustatus = ($member) ? $in_groups[TYPE_TEAM][$key]['user_status'] : false;
				
				$s_assigned_team	= ( $member ) ? 'checked="checked"' : '';
				$s_unassigned_team	= ( $member ) ? '' : 'checked="checked"';
				$s_mod_team	= ( $ustatus ) ? 'checked="checked"' : '';
				$s_mod_team .= ( !$member ) ? ' disabled' : '';
				
				$template->assign_block_vars('groups.row_team', array(
					'S_MARK_NAME'		=> "marks_team[$team_id]",
					'S_MARK_ID'			=> $team_id,
					'S_NEG_MARK_ID'		=> $neg_team_id,
					
					'S_ASSIGNED_TEAM'	=> $s_assigned_team,
					'S_UNASSIGNED_TEAM'	=> $s_unassigned_team,
					'S_MOD_TEAM'		=> $s_mod_team,
					
					'U_TEAM_NAME'		=> $team_name
				));
			}
			
		#	debug($groups);
		#	debug($teams);
		#	debug($in_groups);
			/*
			$sql = "SELECT * FROM " . GROUPS . " ORDER BY group_order";
			if ( !($groups = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $group = $db->sql_fetchrow($groups) )
			{
				$_auth = '';
				
				$auth = unserialize($group['group_auth']);
				
				foreach ( $auth as $key => $value )
				{
					if ( $value )
					{
						$_auth[] = $lang[$key];
					}
				}
				
				$group_id	= $group['group_id'];
				$group_name	= $group['group_name'];
				$group_info	= is_array($_auth) ? sprintf($lang['auth_for'], implode(', ', $_auth)) : '';
				
				$sql = "SELECT user_id, user_pending, user_status FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id = $group_id AND user_id = $data";
				if ( !($result_user = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$member = ( $row = $db->sql_fetchrow($result_user) ) ? true : false;
				$neg_group_id = -1 * $group_id;
				
				if ( $group['group_type'] == GROUP_SYSTEM )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'disabled checked="checked"' : 'disabled';
					$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
					$s_mod_group		= ( $row['user_status'] ) ? 'disabled checked' : 'disabled';
				}
				else if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? ' checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? '' : 'checked="checked"';
					$s_mod_group		= ( $row['user_status'] ) ? 'checked="checked"' : '';
				}
				else if ( $userdata['user_id'] == $data['user_id'] )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? '' : 'checked="checked"';
					$s_mod_group		= ( $row['user_status'] ) ? 'checked="checked"' : '';
				}
				else if ( $userdata['user_level'] > $group['group_access'] )
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? ' checked="checked"' : '';
					$s_unassigned_group	= ( $member ) ? ' ' : 'checked="checked"';
					$s_mod_group		= ( $row['user_status'] ) ? 'checked="checked"' : '';
				}
				else
				{
					$s_assigned_group	= ( $member && !$row['user_pending'] ) ? 'disabled checked="checked"' : 'disabled';
					$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
					$s_mod_group		= ( $row['user_status'] == '1' ) ? 'disabled checked' : 'disabled';
				}
				
				$s_mod_group .= ( !$member ) ? ' disabled' : '';
				
				$template->assign_block_vars('groups.group_row', array(
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
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $team = $db->sql_fetchrow($teams) )
			{
				$team_id	= $team['team_id'];
				$team_name	= $team['team_name'];

				$sql = "SELECT user_id, user_status FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id = $team_id AND user_id = $data";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$member = ( $row = $db->sql_fetchrow($result) ) ? TRUE : FALSE;
				$neg_team_id = -1 * $team_id;
				
				$s_assigned_team	= ( $member ) ? 'checked="checked"' : '';
				$s_unassigned_team	= ( $member ) ? '' : 'checked="checked"';
				$s_mod_team	= ( $row['user_status'] ) ? 'checked="checked"' : '';
				$s_mod_team .= ( !$member ) ? ' disabled' : '';
				
				$template->assign_block_vars('groups.team_row', array(
					'S_MARK_NAME'		=> "marks_team[$team_id]",
					'S_MARK_ID'			=> $team_id,
					'S_NEG_MARK_ID'		=> $neg_team_id,
					
					'S_ASSIGNED_TEAM'	=> $s_assigned_team,
					'S_UNASSIGNED_TEAM'	=> $s_unassigned_team,
					'S_MOD_TEAM'		=> $s_mod_team,
					
					'U_TEAM_NAME'		=> $team_name
				));
			}
			*/	
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";

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
		
			if ( $submit )
			{
				debug($_POST);
				
				$mod_team		= request('mod_team', 4);
				$email_team		= request('email_team', INT);
				$marks_team		= request('marks_team', 4);
				
				$mod_group		= request('mod_group', 4);
				$email_group	= request('email_group', INT);
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
						$sql = "SELECT user_id, user_pending FROM " . LISTS . " WHERE user_id = $data AND type = " . TYPE_GROUP . " AND type_id = " . $marks_groups[$i];
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( !($row = $db->sql_fetchrow($result)) )
						{
							// here, we actually put the user into a selected group if there was no entry for the user in this group
							$sql = "INSERT INTO " . LISTS . " (type, type_id, user_id, user_pending) VALUES (" . TYPE_GROUP . ", {$marks_groups[$i]}, $data, 0)";
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
								$sql = "UPDATE " . LISTS . " SET user_pending = 0 WHERE type = " . TYPE_GROUP . " AND type_id = {$marks_groups[$i]} AND user_id = $data";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$temp_count = 1;
							}
						}
						group_set_auth($data, $marks_groups[$i]);
						
						$sql = "SELECT user_status FROM " . LISTS . " WHERE user_id = $data AND type = " . TYPE_GROUP . " AND type_id = {$marks_groups[$i]}";
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( ($row = $db->sql_fetchrow($result)) )
						{
							$mod = ( request('mod_group_' . $marks_groups[$i], 1) == 'on' ) ? 1 : 0;
							
							$sql = "UPDATE " . LISTS . " SET user_status = $mod WHERE user_id = $data AND type = " . TYPE_GROUP . " AND type_id = {$marks_groups[$i]}";
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
									FROM " . GROUPS . " g, " . LISTS . " gu
								WHERE gu.user_id = $data
									AND gu.type_id = $group_id
									AND gu.type_id = g.group_id";
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( $row = $db->sql_fetchrow($result) )
						{
							// here, we actually delete the user from the group if there an entry for the user in the group
							$sql = "DELETE FROM " . LISTS . " WHERE user_id = " . $row['user_id'] . " AND type = " . TYPE_GROUP . " AND type_id = $group_id";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
						group_reset_auth($data, $group_id);
					}
				}
				
				$temp_count = 0;
				
				for ( $i = 0; $i < count($marks_teams); $i++ )
				{
					if ( $marks_teams[$i] > 0 )
					{
						$sql = "SELECT user_id FROM " . LISTS . " WHERE user_id = $data AND type = " . TYPE_TEAM . " AND type_id = " . $marks_teams[$i];
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( !($row = $db->sql_fetchrow($result)) )
						{
							$sql = "INSERT INTO " . LISTS . " (type, type_id, user_id, time_create) VALUES (" . TYPE_TEAM . ", {$marks_teams[$i]}, $data, $time)";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							debug($sql);
							$temp_count = 1;
						}
						
						$sql = "SELECT user_status FROM " . LISTS . " WHERE user_id = $data AND type = " . TYPE_TEAM . " AND type_id = " . $marks_teams[$i];
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
						}
						
						if ( ($row = $db->sql_fetchrow($result)) )
						{
							$mod = ( request('mod_team_' . $marks_teams[$i], 1) == 'on' ) ? 1 : 0;
							
							$sql = "UPDATE " . LISTS . " SET user_status = $mod WHERE user_id = $data AND type = " . TYPE_TEAM . " AND type_id = " . $marks_teams[$i];
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							debug($sql);
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
									FROM " . TEAMS . " t, " . LISTS . " tu
								WHERE tu.user_id = $data
									AND tu.type_id = $team_id
									AND tu.type_id = t.team_id";
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( $row = $db->sql_fetchrow($result) )
						{
							$sql = "DELETE FROM " . LISTS . " WHERE user_id = " . $row['user_id'] . " AND type = " . TYPE_TEAM . " AND type_id = $team_id";
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
					WHERE u.user_id = $data
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
				
				$msg = $lang['update_groups'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data"));				
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $msg);
			}
		
			break;
		
		case 'auth':
		
		debug($_POST);
		
			$data_sql = data(USERS, $data, false, 1, true);
			
			if ( $userdata['user_level'] < $data['user_level'] )
			{
				message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
			}
			
			/* Sonderrechte */
			$user_auth = unserialize($data['user_gauth']);
			
			/* Gruppenrechte */
			$sql = "SELECT g.group_id, g.group_name, g.group_auth
						FROM " . GROUPS . " g, " . LISTS . " ul
						WHERE g.group_id = ul.type_id
							AND ul.type = " . TYPE_GROUP . "
							AND ul.user_pending = 0
							AND ul.user_id = $data
					ORDER BY g.group_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$auth_group = array();
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$name_group[$row['group_id']] = $row['group_name'];
				$auth_group[$row['group_id']] = unserialize($row['group_auth']);
			}
			$db->sql_freeresult($result);
			
			$auths = $user_auth;
			
			$authg = $authn = $auth = array();
			
			foreach ( $auth_group as $key => $row )
			{
				foreach ( $row as $gkey => $grow )
				{
					if ( $grow )
					{
						$authg[$gkey] = $grow;
						$authn[$gkey][] = $name_group[$key];
					}
				}
			}
			
			foreach ( $auth_fields as $row )
			{
				$auth[$row] = 0;
			}
			
			$authd = array_merge($auth, $authg);
			
			
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
				
				if ( is_array($authn) )
				{
					foreach ( $authn as $key => $ary )
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
				
				$template->assign_block_vars('auth.data', array(
					'TITLE'		=> $cell,
					'NAME'		=> $auth_fields[$j],
					
					'STATUS'	=> $status,

					'S_SELECT'	=> $custom_auths[$j],
					'S_DEFAULT'	=> $custom_authd[$j],
				));
				
				$template->assign_block_vars('auth.list', array(
					'NAME'	=> $auth_fields[$j],
				));
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
			
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
			
			if ( $submit )
			{
				$tmp = '';
				
				for ( $i = 0; $i < count($fields); $i++ )
				{
					$value = request($fields[$i], INT);
					$value = ( $value == '3' ) ? '0' : '2';
					
					$tmp[$fields[$i]] = $value;
				}
				
				$tmp_auth = serialize($tmp);
				
				$sql = "UPDATE " . USERS . " SET user_gauth = '$tmp_auth' WHERE user_id = $data";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$msg = $lang['update_auth'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data"));				
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $msg);
			}
		
			break;
		
		default:
		
			$fields .= '<input type="hidden" name="mode" value="create" />';
	
			$tmp = data(USERS, 'user_id != 1', 'user_id DESC', 1, false);
			
			$cnt = count($tmp);
			
			for ( $i = $start; $i < min($settings['ppe_acp'] + $start, $cnt); $i++ )
			{
				$id		= $tmp[$i]['user_id'];
				$name	= $tmp[$i]['user_name'];
				$level	= $tmp[$i]['user_level'];
				
				switch ( $level )
				{
					case GUEST:		$user_level = $lang['auth_guest'];	break;
					case USER:		$user_level = $lang['auth_user'];	break;
					case TRIAL:		$user_level = $lang['auth_trial'];	break;
					case MEMBER:	$user_level = $lang['auth_member'];	break;
					case MOD:		$user_level = $lang['auth_mod'];	break;
					case ADMIN:		$user_level = $lang['auth_admin'];	break;
				}
				
				$template->assign_block_vars('display.row', array(
					'USERNAME'	=> ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name) : img('a_txt', $name, $name),
					'AUTH'		=> ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'] ) ? href('a_img', $file, array('mode' => 'auth', 'id' => $id), 'icon_auth', '') : img('i_icon', 'icon_auth2', ''),
					'FIELD'		=> ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'] ) ? href('a_img', $file, array('mode' => 'fields', 'id' => $id), 'icon_field', '') : img('i_icon', 'icon_field2', ''),
					'GROUP'		=> ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'] ) ? href('a_img', $file, array('mode' => 'groups', 'id' => $id), 'icon_group', '') : img('i_icon', 'icon_group2', ''),
					'UPDATE'	=> ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
					'DELETE'	=> ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
					
					'REGISTER'	=> create_date($userdata['user_dateformat'], $tmp[$i]['user_regdate'], $userdata['user_timezone']),
					'LEVEL'		=> $user_level,
				));
			}
			
			$current_page = ( !count($data) ) ? 1 : ceil($cnt/$settings['ppe_acp']);
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['user']),
				'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['sprintf_create'], $lang['user']),
				'L_NAME'	=> $lang['user'],
				'L_EXPLAIN'	=> $lang['explain'],
				
				'PAGE_NUMBER'	=> ( count($data) ) ? sprintf($lang['common_page_of'], ( floor( $start / $settings['ppe_acp'] ) + 1 ), $current_page ) : '',
				'PAGE_PAGING'	=> ( count($data) ) ? generate_pagination($file . '?', count($data), $settings['ppe_acp'], $start ) : '',
				
				'S_CREATE'	=> check_sid("$file?mode=create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>