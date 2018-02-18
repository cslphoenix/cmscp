<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_USER',
		'CAT'		=> 'USERGROUPS',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'ACP_USER'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_USER';
	
	include('./pagestart.php');
	
#	debug($userauth, 'userauth');

	add_lang(array('users', 'permission'));
	acl_auth(array('a_user', 'a_user_create', 'a_user_delete', 'a_user_fields', 'a_user_settings', 'a_team_manage', 'a_group_manage', 'a_uauth'));

	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$log	= SECTION_USER;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$index	= request('index', INT);
	$sec	= request('sec', TYP);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$sort	= request('sort', TYP);
	$type	= request('type', TYP);
	$ptype	= request('ptype', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_users']['path'];
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);
	
	( $cancel && !$index )	? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	( $cancel && $index )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_user.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));

	$password_random = array(
		'0' => random_password(7, 10, false, false),
		'1' => random_password(7, 10, false, true, false),
		'2' => random_password(),
		'3' => random_password(7, 14, true, true, false),
		'4' => random_password(7, 14, true, true),
		'5' => random_password(7, 14, true, true, true, true),
	);
	
	if ( request('user_name', TXT) )
	{
		$user_name = request('user_name', TXT);
		
		$check = data(USERS, "WHERE user_name = '$user_name'", false, 1, 1);
		
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
	
/*	
	if ( $mode != 'create' )
	{
		$s_mode = '<select class="postselect" name="mode" onchange="if (this.options[this.selectedIndex].value != \'\');">';
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
*/	
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
	
	function option($delimiter, $array, $num)
	{
		$max = count($array);
		
		$return = '';
		
		for ( $i = 0; $i < $max; $i++ )
		{
			$return .= (( $i == 0 || $i == $num+1 ) ? '' : $delimiter) . $array[$i];
			
			if ( $i == $num )
			{
				$return .= '<br />';
			}		
		}	
		
		return $return;
	}
	
	$template->set_filenames(array(
		'body'	=> 'style/acp_user.tpl',
		'error'	=> 'style/info_error.tpl',
	));
	
	$template->assign_block_vars($temp, array());
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';
	
	$_switch = array(
		array('mode' => 'update', 'lang' => $lang['userinfo']),
		array('mode' => 'settings', 'lang' => $lang['settings']),
		array('mode' => 'pics', 'lang' => $lang['pics']),
		array('mode' => 'permission', 'lang' => $lang['permission']),
		array('mode' => 'groups', 'lang' => $lang['tgroups']),
		array('mode' => 'fields', 'lang' => $lang['fields']),
		array('mode' => 'log', 'lang' => $lang['log']),
	);
	
#	debug($_switch);
	
#	$sort[] = href(($mode == 'update') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'update', 'id' => $data), $lang['userinfo'], $lang['userinfo']);
#	$sort[] = href(($mode == 'settings') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'settings', 'id' => $data), $lang['settings'], $lang['settings']);
#	$sort[] = href(($mode == 'pics') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'pics', 'id' => $data), $lang['pics'], $lang['pics']);
#	$sort[] = href(($mode == 'permission') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'permission', 'id' => $data), $lang['permission'], $lang['permission']);
#	$sort[] = href(($mode == 'groups') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'groups', 'id' => $data), $lang['tgroups'], $lang['tgroups']);
#	$sort[] = href(($mode == 'fields') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'fields', 'id' => $data), $lang['fields'], $lang['fields']);
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$vars = array(
				'user' => array(
					'title'		=> 'INPUT_DATA',
					'user_name'			=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name', 'check' => true),
					'user_email'		=> array('validate' => MAL,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_mail', 'check' => true),
					'email_confirm'		=> array('validate' => MAL,	'explain' => false,	'type' => 'text:25;25'),
					'user_regdate'		=> ($mode != 'create') ? array('validate' => INT,	'explain' => false,	'type' => 'show:time') : 'hidden',
					'user_lastvisit'	=> ($mode != 'create') ? array('validate' => INT,	'explain' => false,	'type' => 'show:time') : 'hidden',
					'whois'				=> ($mode != 'create') ? array('validate' => INT,	'explain' => false,	'type' => 'show:ip') : 'hidden',
					'user_level'		=> 'hidden',
					'user_founder'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'user_active'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'user_birthday'		=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25'),
					
					'password_type'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:password', 'params' => array('type', false, false)),
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
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
				$data_sql = data(USERS, $data, false, 1, 'row');
				$data_sql['password_type'] = 1;
			}
			else
			{
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
					#	$msg = $lang['CREATE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top . $pw);
						$msg = $lang['CREATE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
						
						
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
						$msg = $lang['UPDATE'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
			/*
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
			*/
			
			$sort = '';
			
			foreach ( $_switch as $swi )
			{
				$sort[] = href((($mode == $swi['mode']) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('id' => $data, 'mode' => $swi['mode']), langs($swi['lang']), langs($swi['lang']));
			}
			
		#	$sort[] = href(($mode == 'update') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'update', 'id' => $data), $lang['userinfo'], $lang['userinfo']);
		#	$sort[] = href(($mode == 'settings') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'settings', 'id' => $data), $lang['settings'], $lang['settings']);
		#	$sort[] = href(($mode == 'pics') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'pics', 'id' => $data), $lang['pics'], $lang['pics']);
		#	$sort[] = href(($mode == 'permission') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'permission', 'id' => $data), $lang['permission'], $lang['permission']);
		#	$sort[] = href(($mode == 'groups') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'groups', 'id' => $data), $lang['tgroups'], $lang['tgroups']);
		#	$sort[] = href(($mode == 'fields') ? 'AHREF_TXT_B' : 'AHREF_TXT', $file, array('mode' => 'fields', 'id' => $data), $lang['fields'], $lang['fields']);
					
			$sort = implode(', ', $sort);
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			build_output(USERS, $vars, $data_sql);

			$template->assign_vars(array(
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['user_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
			#	'L_OPTION'	=> option($lang['COMMON_BULL'], $option, 3),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_SWITCH'	=> sprintf($lang['STF_COMMON_SWITCH'], $sort),
				
				'L_PASSWORD'			=> $lang['PASSWORD'],
				'L_PASSWORD_CONFIRM'	=> $lang['PASSWORD_CONFIRM'],
				
				'PASS_1'	=> sprintf($lang['PASSWORD_RANDOM'], $password_random[0]),
				'PASS_2'	=> sprintf($lang['PASSWORD_RANDOM'], $password_random[1]),
				'PASS_3'	=> sprintf($lang['PASSWORD_RANDOM'], $password_random[2]),
				'PASS_4'	=> sprintf($lang['PASSWORD_RANDOM'], $password_random[3]),
				'PASS_5'	=> sprintf($lang['PASSWORD_RANDOM'], $password_random[4]),
				'PASS_6'	=> sprintf($lang['PASSWORD_RANDOM'], $password_random[5]),
				
				'NONE_INPUT'	=> ($data_sql['password_type']) ? '' : 'none',
				'NONE_PASSWORD'	=> (!$data_sql['password_type']) ? '' : 'none',

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
			
		case 'settings':
		
			$data_sql = data(USERS, $data, false, 1, 'row');
			
			$display_vars = array(
				'setting'	=> array(
					'title1' => 'settings',
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
					'user_allow_viewonline'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Darf sich verstecken oder nicht
					'user_allow_sig'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Signatur erlauben oder sperren
					'user_allow_avatar'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Avatar erlauben oder sperren
					'user_allow_pm'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),	// Private Nachrichten erlauben oder sperren
					
				),
			);
			
			build_output(GAMES, $display_vars, $data_sql, $mode);
			
			$rank_page	= $data['user_rank_page'];
			$rank_forum	= $data['user_rank_forum'];

			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);			
			
			$sort = '';
			
			foreach ( $_switch as $swi )
			{
				$sort[] = href((($mode == $swi['mode']) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('id' => $data, 'mode' => $swi['mode']), langs($swi['lang']), langs($swi['lang']));
			}
					
			$sort = implode(', ', $sort);
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['STF_UPDATE'], $lang['user'], $data['user_name']),
				
			#	'L_OPTION'	=> option($lang['COMMON_BULL'], $option, 3),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_SWITCH'	=> sprintf($lang['STF_COMMON_SWITCH'], $sort),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields
			));
		
			break;
			
		case 'pics':
		
			$data_sql = data(USERS, $data, false, 1, 'row');
			
			$display_vars = array(
				'setting'	=> array(
					'title1' => 'settings',
					
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
			
			build_output(GAMES, $display_vars, $data_sql, $mode);
			
			$rank_page	= $data['user_rank_page'];
			$rank_forum	= $data['user_rank_forum'];

            $fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));

			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			$sort = '';
			
			foreach ( $_switch as $swi )
			{
				$sort[] = href((($mode == $swi['mode']) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('id' => $data, 'mode' => $swi['mode']), langs($swi['lang']), langs($swi['lang']));
			}
					
			$sort = implode(', ', $sort);
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['STF_UPDATE'], $lang['user'], $data['user_name']),
				
			#	'S_MODE'	=> $s_mode,
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_SWITCH'	=> sprintf($lang['STF_COMMON_SWITCH'], $sort),
			#	'L_OPTION'	=> option($lang['COMMON_BULL'], $option, 3),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields
			));
		
			break;
			
		case 'permission':
		
			$data_sql		= data(USERS, $data, false, 1, 'row');
			$options		= array('A_', 'D_', 'G_');
			$ptype			= ($ptype ? $ptype : $options[0]);
			$forum_ids		= 0;
			$_action		= 'PERSMISSION';
			$ug_ids			= array($data_sql['user_id']);
			$acl_label		= acl_label($ptype);
			$acl_auth_group = acl_auth_group($ptype);
			$acl_field_id	= acl_field($ptype, '');
			$acl_field_name = acl_field($ptype, 'name');
			$acl_label_ids	= array_keys($acl_label);
			$acl_label_data	= acl_label_data($acl_label_ids);
			
			/* Gruppen des Benutzers abfragen */
			$sql = "SELECT type_id as group_id, user_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND user_pending = 0 AND user_id = " . $data_sql['user_id'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$group_ids[] = $row['group_id'];
				$user_ids[$row['group_id']] = $row['user_id'];
			}
			$db->sql_freeresult($result);
			
			$group_ids = array_unique($group_ids);
			
			/* Gruppenname abfragen */
			$sql = "SELECT group_id, group_name, group_color FROM " . GROUPS . " WHERE group_id IN (" . (is_array($group_ids) ? implode(', ', $group_ids) : $group_ids) . ") ORDER BY group_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$groups[$row['group_id']] = $row['group_name'];
				
				$row['group_color'] = ($row['group_color'] == '#FFFFFF') ? '#000000' : $row['group_color'];
				
				$groups_color[$row['group_id']] = $row['group_color'];
			}
			$db->sql_freeresult($result);
			
			/* Rechte abfragen für Gruppen und Benutzerrechte */
			$group_access = access(ACL_GROUPS, array('group_id', $group_ids), 0, $acl_label_data, $acl_field_name);
			$users_access = access(ACL_USERS, array('user_id', $data_sql['user_id']), 0, $acl_label_data, $acl_field_name);
			
			$grp_info = array();
			
			if ( $users_access )
			{
				foreach ( $users_access as $forum => $users )
				{
					foreach ( $users as $u_id => $u_data )
					{
						foreach ( $u_data as $r_field => $r_value )
						{
							if ( isset($urs_access[$forum][$r_field]) )
							{
								if ( $urs_access[$forum][$r_field] == 1 )
								{
									if ( $r_value == 1 )
									{
										$urs_access[$forum][$r_field] = '1';
									}
									else if ( $r_value == -1 )
									{
										$urs_access[$forum][$r_field] = '-1';
									}
									else
									{
										$urs_access[$forum][$r_field] = '1';
									}
								}
								
								if ( $urs_access[$forum][$r_field] == -1 )
								{
									if ( $r_value == 1 )
									{
										$urs_access[$forum][$r_field] = '-1';
									}
									else if ( $r_value == -1 )
									{
										$urs_access[$forum][$r_field] = '-1';
									}
									else
									{
										$urs_access[$forum][$r_field] = '-1';
									}
								}
								
								if ( $urs_access[$forum][$r_field] == 0 )
								{
									if ( $r_value == 1 )
									{
										$urs_access[$forum][$r_field] = '1';
									}
									else if ( $r_value == -1 )
									{
										$urs_access[$forum][$r_field] = '-1';
									}
									else
									{
										$urs_access[$forum][$r_field] = '0';
									}
								}
							}
							else
							{
								$urs_access[$forum][$r_field] = $r_value;
							}
							
							if ( $data_sql['user_founder'] )
							{
								$urs_access[$forum][$r_field] = '1';
								$grp_info[$forum][$data_sql['user_id']][$r_field][$lang['PERMISSION_FOUNDER']] = $lang['PERM_1'];
							}
							
							if ( $r_value != 0 )
							{
								$grp_info[$forum][$data_sql['user_id']][$r_field][$lang['PERMISSION_USER']] = $lang['PERM_' . $r_value];
							}
						}
					}
				}
			}
			
			if ( $group_access )
			{
				foreach ( $group_access as $forum => $group )
				{
					foreach ( $group as $g_id => $g_data )
					{
						foreach ( $g_data as $r_field => $r_value )
						{
							if ( isset($grp_access[$forum][$r_field]) )
							{
								if ( $grp_access[$forum][$r_field] == 1 )
								{
									if ( $r_value == 1 )
									{
										$grp_access[$forum][$r_field] = '1';
									}
									else if ( $r_value == -1 )
									{
										$grp_access[$forum][$r_field] = '-1';
									}
									else
									{
										$grp_access[$forum][$r_field] = '1';
									}
								}
								
								if ( $grp_access[$forum][$r_field] == -1 )
								{
									if ( $r_value == 1 )
									{
										$grp_access[$forum][$r_field] = '-1';
									}
									else if ( $r_value == -1 )
									{
										$grp_access[$forum][$r_field] = '-1';
									}
									else
									{
										$grp_access[$forum][$r_field] = '-1';
									}
								}
								
								if ( $grp_access[$forum][$r_field] == 0 )
								{
									if ( $r_value == 1 )
									{
										$grp_access[$forum][$r_field] = '1';
									}
									else if ( $r_value == -1 )
									{
										$grp_access[$forum][$r_field] = '-1';
									}
									else
									{
										$grp_access[$forum][$r_field] = '0';
									}
								}
							}
							else
							{
								$grp_access[$forum][$r_field] = $r_value;
							}
							
							if ( $data_sql['user_founder'] )
							{
								$grp_access[$forum][$r_field] = '1';
								$grp_info[$forum][$data_sql['user_id']][$r_field][$lang['PERMISSION_FOUNDER']] = $lang['PERM_1'];
							}
							
							if ( $r_value != 0 )
							{
								$grp_info[$forum][$data_sql['user_id']][$r_field][sprintf('%s - %s', $lang['PERMISSION_GROUP'], $groups[$g_id])] = $lang['PERM_' . $r_value];
							}
						}
					}
				}
			}
			
			$u_a = array();
			
			if ( !is_array($forum_ids) )
			{
				foreach ( $acl_field_name as $f_name )
				{
					if ( isset($urs_access[$forum_ids][$f_name])  )
					{
						if ( $urs_access[$forum_ids][$f_name] == '1' )
						{
							if ( isset($u_a[$forum_ids][$data_sql['user_id']][$f_name]) )
							{
								if ( $u_a[$forum_ids][$data_sql['user_id']][$f_name] == '-1' )
								{
									$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '-1';
								}
								else if ( $u_a[$forum_ids][$data_sql['user_id']][$f_name] == '0' )
								{
									$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '1';
								}
							}
							else
							{
								$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '1';
							}
						}
						else if ( $urs_access[$forum_ids][$f_name] == '0' )
						{
							if ( isset($u_a[$forum_ids][$data_sql['user_id']][$f_name]) )
							{
								if ( $u_a[$forum_ids][$data_sql['user_id']][$f_name] == '-1' )
								{
									$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '-1';
								}
								else if ( $u_a[$forum_ids][$data_sql['user_id']][$f_name] == '1' )
								{
									$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '1';
								}
							}
							else
							{
								$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '0';
							}
						}
						else
						{
							$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '-1';
						}
					}
					
					if ( isset($grp_access[$forum_ids][$f_name])  )
					{
						if ( $grp_access[$forum_ids][$f_name] == '1' )
						{
							if ( isset($u_a[$forum_ids][$data_sql['user_id']][$f_name]) )
							{
								if ( $u_a[$forum_ids][$data_sql['user_id']][$f_name] == '-1' )
								{
									$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '-1';
								}
								else if ( $u_a[$forum_ids][$data_sql['user_id']][$f_name] == '0' )
								{
									$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '1';
								}
							}
							else
							{
								$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '1';
							}
						}
						else if ( $grp_access[$forum_ids][$f_name] == '0' )
						{
							if ( isset($u_a[$forum_ids][$data_sql['user_id']][$f_name]) )
							{
								if ( $u_a[$forum_ids][$data_sql['user_id']][$f_name] == '-1' )
								{
									$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '-1';
								}
								else if ( $u_a[$forum_ids][$data_sql['user_id']][$f_name] == '1' )
								{
									$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '1';
								}
							}
							else
							{
								$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '0';
							}
						}
						else
						{
							$u_a[$forum_ids][$data_sql['user_id']][$f_name] = '-1';
						}
					}
				}
			}
			
			$template->assign_block_vars('view', array());
			
			$forums[0] = langs($ptype . strtoupper($_action));
			
			$s_options = '';
				
			if ( is_array($options) && count($options) > 1 )
			{
				/* änderung auf oki button drücken zum wechsel */
				$s_options .= '<select name="ptype" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
				
				foreach ( $options as $opts )
				{
					$selected = ( $opts == $ptype ) ? ' selected="selected"' : '';
					$s_options .= '<option value="' . $opts . '"' . $selected . '>' . langs($opts . strtoupper('right')) . '</option>';
				}
				
				$s_options .= '</select>';
			}
			
			/* SPRACHFILES ANPASSEN UND ERSTELLEN !!! */
			$add_lang = array('label_id' => 0, 'label_name' => 'no_select', 'label_desc' => 'no_select_reset', 'label_type' => $type);
			array_unshift($acl_label, $add_lang);
			
			$sql = "SELECT user_id as ugid, user_name as name FROM " . USERS . " WHERE user_id IN ($ug_ids[0]) ORDER BY user_name ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$ugs = array();
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$ugs[$row['ugid']] = $row['name'];
			}
			
			$main	= ( sizeof($forums) > 1 && sizeof($ugs) < 2 ) ? $ugs : $forums;
			$parent	= ( sizeof($forums) > 1 && sizeof($ugs) < 2 ) ? $forums : $ugs;
			
			foreach ( $main as $m_id => $m_data )
			{
				$template->assign_block_vars('view.row', array('NAME' => $m_data));
				
				foreach ( $parent as $p_id => $p_data )
				{
					$row_switch = sprintf('s%sg%s', $m_id, $p_id);
					
					$template->assign_block_vars('view.row.parent', array(
						'NAME'		=> $p_data,
						'TOGGLE'	=> $row_switch,
						'LABEL'		=> '<label for="' . $row_switch . '">' . $lang['label'] . '</label>',
						'AUTHS'		=> 'auths' . $m_id . $p_id,
					));
					
					foreach ( $acl_auth_group as $cat => $rows )
					{
						$template->assign_block_vars('view.row.parent.cats', array(
							'CAT'	=> $cat,
							'NAME'	=> $lang['TABS'][$ptype][$cat],
							'OPTIONS' => "options{$m_id}{$p_id}{$cat}",
						));
						
						foreach ( $rows as $row )
						{
							$row_format = sprintf('%s%s[%s][%s]', $ptype, $m_id, $p_id, $row);
							
							if ( isset($grp_info[$m_id][$p_id][$row]) && is_array($grp_info[$m_id][$p_id][$row]) )
							{
								foreach ( $grp_info[$m_id][$p_id][$row] as $g_id => $g_value )
								{
									$grp_info_grp[$m_id][$p_id][$row][$g_id] = $g_id . ': ' .$g_value;
								}
								
								$grp_info_grp[$m_id][$p_id][$row] = implode('<br />', $grp_info_grp[$m_id][$p_id][$row]);
							}
							
							$template->assign_block_vars('view.row.parent.cats.auths', array(
								'OPT_NAME'	=> langs($row),
								'OPT_INFO'	=> isset($grp_info_grp[$m_id][$p_id][$row]) ? img('i_icon', 'icon_details', $grp_info_grp[$m_id][$p_id][$row]) : '',
							#	'OPT_INFO'	=> isset($grp_info_grp[$m_id][$p_id][$row]) ? img('i_icon', 'icon_details', $grp_info_grp[$m_id][$p_id][$row]) : '',
								'CSS_YES'	=> ( @$u_a[$m_id][$p_id][$row] == '1' ) ? 'bggreen' : '',
								'CSS_NO'	=> ( @$u_a[$m_id][$p_id][$row] != '1' ) ? 'bgred' : '',
							));
						}
					}
				}
			}
			
			$fields .= '<input type="hidden" name="mode" value="' .  ($mode ? $mode : $type) . '" />';
			$fields .= '<input type="hidden" name="id" value="' . $data . '">';
			
			foreach ( $groups as $grp_id => $grp_name )
			{
				$legend[$grp_id] = href('a_style', 'admin_groups.php' . $iadds, array('mode' => 'permission', 'id' => $grp_id), $groups_color[$grp_id], $grp_name);
			}
			
			$legend = implode(', ', $legend);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
		#	$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
		#	debug($option, '$option');
			
			$sort = '';
			
			foreach ( $_switch as $swi )
			{
				$sort[] = href((($mode == $swi['mode']) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('id' => $data, 'mode' => $swi['mode']), langs($swi['lang']), langs($swi['lang']));
			}
					
			$sort = implode(', ', $sort);
			
		#	debug($legend, '$legend');
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['user']),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['STF_CREATE'], $lang['user']),
				'L_NAME'	=> $lang['user'],
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				'L_VIEW_AUTH'	=> $lang['COMMON_AUTH'],
				
				'GROUPS'	=> $legend,
				
			#	'S_MODE'	=> $s_mode,
			#	'L_OPTION'	=> option($lang['COMMON_BULL'], $option, 3),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_SWITCH'	=> sprintf($lang['STF_COMMON_SWITCH'], $sort),
				
				'S_OPTIONS' => $s_options,
			#	'S_ACTION'	=> check_sid($file),
				'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'groups':
			
			$data_sql = data(USERS, $data, false, 1, 'row');
			
			$sql = "SELECT group_id, group_type, group_name, group_access, group_color FROM " . GROUPS . " ORDER BY group_order";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$groups = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			
			$sql = "SELECT t.team_id, t.team_name, g.game_image FROM " . TEAMS . " t, " . GAMES . " g WHERE t.team_game = g.game_id ORDER BY t.team_order";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$teams = $db->sql_fetchrowset($result);
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
			
			if ( $groups )
			{
				$template->assign_block_vars($temp . '.group', array());
				
				$cnt = count($groups);
				
				foreach ( $groups as $row )
				{
					$group_id	= $row['group_id'];
					$group_name	= $row['group_name'];
									
					$member = (isset($in_groups[TYPE_GROUP][$group_id])) ? true : false;
					$neg_group_id = -1 * $group_id;
					
					$ustatus	= ($member) ? $in_groups[TYPE_GROUP][$group_id]['user_status'] : false;
					$upending	= ($member) ? $in_groups[TYPE_GROUP][$group_id]['user_pending'] : false;
					
					if ( ($row['group_type'] == GROUP_SYSTEM) || @!$userauth['A_GROUP_MANAGE'] )
					{
						$s_assigned_group	= ( $member && !$upending ) ? 'disabled checked="checked"' : 'disabled';
						$s_unassigned_group	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
						$s_mod_group		= ( $ustatus ) ? 'disabled checked' : 'disabled';
					}
					else
					{
						$s_assigned_group	= ( $member && !$upending ) ? ' checked="checked"' : '';
						$s_unassigned_group	= ( $member ) ? '' : ' checked="checked"';
						$s_mod_group		= ( $ustatus ) ? ' checked' : '';
					}
					
					$s_mod_group .= ( !$member ) ? ' disabled' : '';
					
					$template->assign_block_vars($temp . '.group.row', array(
						'NAME'	=> $group_name,
						'FIELD'	=> "group_" . $group_id,
						
						'S_MARK_NAME'			=> "marks_group[$group_id]",
						'S_MARK_ID'				=> $group_id,
						'S_NEG_MARK_ID'			=> $neg_group_id,
						'S_ASSIGNED_GROUP'		=> $s_assigned_group,
						'S_UNASSIGNED_GROUP'	=> $s_unassigned_group,
						'S_MOD_GROUP'			=> $s_mod_group,
						
						'U_USER_PENDING'		=> ( $upending ) ? $lang['Membership_pending'] : '',
					));
				}
			}
			
			if ( $teams )
			{
				$template->assign_block_vars($temp . '.team', array());
				
				$cnt = count($teams);
							
				foreach ( $teams as $row )
				{
					$team_id	= $row['team_id'];
					$team_name	= $row['team_name'];
					
					$member = (isset($in_groups[TYPE_TEAM][$team_id])) ? true : false;
					$neg_team_id = -1 * $team_id;
					
					$ustatus = ($member) ? $in_groups[TYPE_TEAM][$team_id]['user_status'] : false;
					
					if ( @!$userauth['A_TEAM_MANAGE'] )
					{
						$s_assigned_team	= ( $member ) ? 'disabled checked="checked"' : 'disabled';
						$s_unassigned_team	= ( $member ) ? 'disabled' : 'disabled checked="checked"';
						$s_mod_team	= ( $ustatus ) ? 'disabled checked="checked"' : '';
						$s_mod_team .= ( !$member ) ? 'disabled' : '';
					}
					else
					{
						$s_assigned_team	= ( $member ) ? 'checked="checked"' : '';
						$s_unassigned_team	= ( $member ) ? '' : 'checked="checked"';
						$s_mod_team	= ( $ustatus ) ? 'checked="checked"' : '';
						$s_mod_team .= ( !$member ) ? 'disabled' : '';
					}
					
					$template->assign_block_vars($temp . '.team.row', array(
						'NAME'	=> $team_name,
						'FIELD'	=> "team_" . $team_id,
						
						'S_MARK_NAME'		=> "marks_team[$team_id]",
						'S_MARK_ID'			=> $team_id,
						'S_NEG_MARK_ID'		=> $neg_team_id,
						'S_ASSIGNED_TEAM'	=> $s_assigned_team,
						'S_UNASSIGNED_TEAM'	=> $s_unassigned_team,
						'S_MOD_TEAM'		=> $s_mod_team,
					));
				}
			}
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			$sort = '';
			
			foreach ( $_switch as $swi )
			{
				$sort[] = href((($mode == $swi['mode']) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('id' => $data, 'mode' => $swi['mode']), langs($swi['lang']), langs($swi['lang']));
			}
					
			$sort = implode(', ', $sort);
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_UPDATE'], $lang['user'], $data_sql['user_name']),
				
				'L_GROUPS'	=> $lang['usergroups'],
				'L_TEAMS'	=> $lang['teams'],
				
				'L_MAIL'	=> $lang['mail'],
				'L_MOD'		=> $lang['mod'],
				
			#	'L_OPTION'	=> option($lang['COMMON_BULL'], $option, 3),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_SWITCH'	=> sprintf($lang['STF_COMMON_SWITCH'], $sort),
				
				'S_FLOAT_BEGIN'	=> (@$userauth['A_GROUP_MANAGE'] && @$userauth['A_TEAM_MANAGE']) ? 'left' : '',
				'S_FLOAT_END'	=> (@$userauth['A_GROUP_MANAGE'] && @$userauth['A_TEAM_MANAGE']) ? 'right' : '',
				'S_WIDTH'		=> (@$userauth['A_GROUP_MANAGE'] && @$userauth['A_TEAM_MANAGE']) ? '49' : '',
								
				'S_ACTION'	=> check_sid($file),		
				'S_FIELDS'	=> $fields,
			));
		
			if ( $submit )
			{
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
							$sql = "INSERT INTO " . LISTS . " (type, type_id, user_id, time_create, user_pending) VALUES (" . TYPE_GROUP . ", {$marks_groups[$i]}, $data, {$time}, 0)";
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
						
						$sql = "SELECT user_status FROM " . LISTS . " WHERE user_id = $data AND type = " . TYPE_GROUP . " AND type_id = {$marks_groups[$i]}";
						if ( !($result = $db->sql_query($sql) ) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( ($row = $db->sql_fetchrow($result)) )
						{
							$mod = ( request('mod_group_' . $marks_groups[$i], TXT) == 'on' ) ? 1 : 0;
							
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
							$mod = ( request('mod_team_' . $marks_teams[$i], TXT) == 'on' ) ? 1 : 0;
							
							$sql = "UPDATE " . LISTS . " SET user_status = $mod WHERE user_id = $data AND type = " . TYPE_TEAM . " AND type_id = " . $marks_teams[$i];
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
				
				$msg = sprintf($lang['RETURN_UPDATE'], langs('update_groups'), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));				
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $msg);
			}
		
			break;
		
		case 'fields':
			
			$data_sql	= data(USERS, $data, false, 1, 'row');
			$profile	= data(PROFILE, false, 'main ASC, profile_order ASC', 1, 'set');
			$info		= data(PROFILE_DATA, $data, false, 1, 'row');
			
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
						$template->assign_block_vars("$mode.tab", array('L_LANG' => $value));
						
						foreach ( $entry[$key] as $field => $row )
						{
							$name	= $row['profile_name'];
							$field	= $row['profile_field'];
							$req	= $row['profile_required'] ? 'r' : '';
							$valuea	= request($field, 'text') ? request($field, 'text') : $info[$field];
							
							$request['user_id'] = $data;
							$request[$field] = $valuea;
						
							if ( $row['profile_required'] )
							{
								$error[] = !$valuea ? ( $error ? '<br />' : '' ) . sprintf($lang['notice_select_profile'], $name) : '';
							}
							
							if ( $row['profile_typ'] == 0 )
							{
								$input = '<input type="text" name="' . $field . '" id="' . $field . '" value="' . $valuea . '" />';
							}
							else if ( $row['profile_typ'] == 1 )
							{
								$input = '<textarea name="' . $field . '" id="' . $field . '" cols="30\" />' . $valuea . '</textarea>';
							}
							else if ( $row['profile_typ'] == 2 )
							{
								$checked_yes = ($valuea) ? 'checked="checked"' : '';
								$checked_no = (!$valuea) ? 'checked="checked"' : '';
								$input = '<label><input type="radio" name="' . $field . '" value="1" ' . $checked_yes . ' />&nbsp;' . $lang['COMMON_YES'] . '</label><span style="padding:4px;"></span><label><input type="radio" name="' . $field . '" value="0" ' . $checked_no . ' />&nbsp;' . $lang['COMMON_NO'] . '</label>';
							}
							
							$template->assign_block_vars("$mode.tab.option", array(
								'REQ'	=> $req,
								'L_NAME'	=> '<label for="' . $field . '">' . $name . '</label>',
								'OPTION' => $input,
							));
						}
					}
				}
			}

			if ( $submit )
			{
				if ( !$error )
				{
					$sql = ( !$info ) ? sql(PROFILE_DATA, 'create', $request) : sql(PROFILE_DATA, 'update', $request, 'user_id', $data);
					$msg = $lang['update_fields'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					
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
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			$sort = '';
			
			foreach ( $_switch as $swi )
			{
				$sort[] = href((($mode == $swi['mode']) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('id' => $data, 'mode' => $swi['mode']), langs($swi['lang']), langs($swi['lang']));
			}
					
			$sort = implode(', ', $sort);
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['STF_UPDATE'], $lang['user'], $data['user_name']),
				
			#	'L_OPTION'	=> option($lang['COMMON_BULL'], $option, 3),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_SWITCH'	=> sprintf($lang['STF_COMMON_SWITCH'], $sort),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields
			));
			
			break;
			
		case 'log':
		
			$user	= data(USERS, $data, false, 1, 'row');
		#	
			$sql_where = '';
			
			if (!empty($sort) && $sort != 'sort_all' )
			{
				switch ( $sort )
				{
					case 'sort_day':	$_where = $time-86400;		break;
					case 'sort_week':	$_where = $time-604800;		break;
					case 'sort_weeks':	$_where = $time-1209600;	break;
					case 'sort_month':	$_where = $time-2629743;	break;
					case 'sort_year':	$_where = $time-31556926;	break;
				}		
				
				$sql_where = " AND log_time BETWEEN $_where AND $time";
			}
			
			$sql_sec = $sec ? " AND log_section = $sec" : '';
		
			debug($sql_where);
			
			$sql = "SELECT * FROM " . LOGS . " WHERE user_id = $data $sql_where $sql_sec ORDER BY log_id DESC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}

			while ( $row = $db->sql_fetchrow($result) )
			{
				$sqlout[] = $row;
				$logsec[] = $row['log_section'];
			}
			$db->sql_freeresult($result);
			
		#	$sqlout	= data(LOGS, 'user_id = ' . $data . $sql_where, 'log_id DESC', 1, 'set');
			
		#	debug($sqlout, '$sqlout');
			
			$logsec = array_unique($logsec);
			
		#	debug($logsec, '$logsec');
		
			$switch = $sortby = $secby = '';
			
			foreach ( $logsec as $logsection )
			{
				
				$secby[] = href((($sec == strtolower($logsection)) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('id' => $data, 'mode' => $mode, 'sort' => $sort, 'sec' => strtolower($logsection)), langs($logsection), langs($logsection));
				
		#		debug($logsection, '$logsection');
			}
			
		#	debug($secby, '$secby');
			
		#	$sql = 'SELECT * FROM ' . LOGS . '
		#				WHERE l.user_id = u.user_id AND log_type = ' . $log_type . ' ORDER BY log_id DESC';
		#	if (!($result = $db->sql_query($sql)))
		#	{
		#		message(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
		#	}
		#	$tmp = $db->sql_fetchrowset($result);
		#	$db->sql_freeresult($result);
			
		#	debug($user, 'user');
		#	debug($sqlout, 'sqlout');
		
			if ( $sqlout )
			{
				$max = count($sqlout);
				
			#	debug($max, 'max');
				
				for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $max); $i++ )
				{
					$message = $sqlout[$i]['log_message'];
					$section = $sqlout[$i]['log_section'];
					$section = isset($lang['section'][$section]) ? $lang['section'][$section] : $section;
				#	debug($message, 'message', true);
					if ( $message )
					{
						$class = ( $message == 'error' || strstr($message, 'auth_fail') ) ? 'row_error' : '';
						$message = ($message == 'update') ? langs('COMMON_LOG_' . $message) : langs('COMMON_' . $message);
					}
					
				#	debug($message, 'message', true);
					
					switch ( $sqlout[$i]['log_section'] )
					{
						case SECTION_NEWS:	$log_title = data(NEWS, 'in_send = 0', 'news_date DESC, news_id DESC', 1, 3); break;
					}
					
					$log_data = unserialize($sqlout[$i]['log_data']);
					$msg_data = '&raquo;';
					
					if ( is_array($log_data) )
					{
						$msg = array();
						
						foreach ( $log_data as $_row )
						{
							$_meta	= isset($_row['meta'])	? $_row['meta'] : false;
							$_data	= isset($_row['data'])	? $_row['data'] : false;
							$_post	= isset($_row['post'])	? $_row['post'] : false;
							$field	= isset($_row['field'])	? $_row['field'] : false;
							$_lang	= langs($field);
							
							if ( strpos($sqlout[$i]['log_message'], 'create') !== false )
							{
								$msg[] = sprintf($lang['STF_LOG_CREATE'], langs($field), $_row['post']);
							}
							else if ( strpos($sqlout[$i]['log_message'], 'update') !== false )
							{
								if ( $field == 'main' )
								{
									switch ( $sqlout[$i]['log_section'] )
									{
										case SECTION_MENU: 
										
											$menu = data(MENU, false, false, 1, 'set');
											
											foreach ( $menu as $_menu )
											{
												$_new[$_menu['menu_id']] = $_menu['menu_name'];
											}
											
											break;
									}
								}
								
								switch ( $sqlout[$i]['log_section'] )
								{
									case SECTION_NEWS:	$_meta = sprintf('&nbsp;%s<br />&raquo;', $log_title[$_row['meta']]['news_title']); break;
								}
								
								switch ( $field )
								{
									case 'time_update':

										$_lang = langs($field);
										$_data = (isset($_new[$_row['data']]) ? create_shortdate($userdata['user_dateformat'], $_new[$_row['data']], $userdata['user_timezone']) : create_shortdate($userdata['user_dateformat'], $_row['data'], $userdata['user_timezone']));
										$_post = (isset($_new[$_row['post']]) ? create_shortdate($userdata['user_dateformat'], $_new[$_row['post']], $userdata['user_timezone']) : create_shortdate($userdata['user_dateformat'], $_row['post'], $userdata['user_timezone']));
										
										break;
									
									default:
									
										$_lang = langs($field);
										$_data = (isset($_new[$_row['data']]) ? $_new[$_row['data']] : $_row['data']);
										$_post = (isset($_new[$_row['post']]) ? $_new[$_row['post']] : $_row['post']);
										
										break;
								}
								
								$msg[] = sprintf($lang['STF_LOG_CHANGE'], $_lang, $_data, $_post);
								
								unset($_new);
							}
							else if ( strpos($sqlout[$i]['log_message'], 'delete') !== false )
							{
								$msg[] = sprintf($lang['STF_LOG_DELETE'], langs($field), $_row['data']);
							}
							else
							{
								$msg[] = $_row;
							}
						}
						
						if ( isset($_meta) )
						{
							$msg_data .= $_meta;
						}
						
						$msg_data .= implode('<br />&raquo;&nbsp;', $msg);
					}
					else
					{
						$msg_data = '&nbsp;' . langs($log_data);
					}
					
					$template->assign_block_vars($mode . '.row', array(
						'DATE'		=> create_date($userdata['user_dateformat'], $sqlout[$i]['log_time'], $userdata['user_timezone']),
						'IP'		=> decode_ip($sqlout[$i]['user_ip']),
						'SEKTION'	=> $section,
						'MESSAGE'	=> $message,
						'DATA'		=> $msg_data,
					));
				}
				
				$current_page = ( !count($sqlout) ) ? 1 : ceil( count($sqlout) / $settings['per_page_entry']['acp'] );
					
				$template->assign_vars(array(
					'PAGE_PAGING'	=> generate_pagination($file, count($sqlout), $settings['per_page_entry']['acp'], $start) . 'as',
					'PAGE_NUMBER'   => sprintf($lang['COMMON_PAGE_OF'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page) . 'asas',
				));
			}
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			foreach ( $_switch as $swi )
			{
				$switch[] = href((($mode == $swi['mode']) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('id' => $data, 'mode' => $swi['mode']), langs($swi['lang']), langs($swi['lang']));
			}
			
			debug($switch, '$switch');
			
			$sort = empty($sort) ? 'sort_all' : $sort;
			
			foreach ( $lang['SORT_BY_TIME'] as $key => $sort_by_time )
			{
				$sortby[] = href((($sort == strtolower($key)) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('id' => $data, 'mode' => $mode, 'sort' => strtolower($key)), langs($sort_by_time), langs($sort_by_time));
			}
					
			$switch = implode(', ', $switch);
			$sortby = implode(', ', $sortby);
			$secby	= implode(', ', $secby);
			
			$template->assign_vars(array(
				'L_EXPLAIN'		=> $lang['EXPLAIN'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_SWITCH'	=> sprintf($lang['STF_COMMON_SWITCH'], $switch),
				'L_SORT'	=> sprintf($lang['STF_COMMON_SORT'], $sortby),
				'L_SECTION'	=> sprintf($lang['STF_COMMON_SORT'], $secby),
				
				'L_GOTO_PAGE'	=> $lang['Goto_page'],
						
				'PAGE_PAGING'	=> generate_pagination($file . "&mode=$mode&id=$data" . (empty($sort) && $sort == 'sort_all' ? '' : "&sort=$sort"), count($sqlout), $settings['per_page_entry']['acp'], $start),
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ),
						
			));
		
			break;
		
		case 'delete':
		
			$data_sql = data(USERS, $data, false, 1, 'row');
			
			if ( $data && $accept && $userauth['A_USER_DELETE'] )
			{
				$file = ( $index ) ? check_sid('index.php') : check_sid($file);
				$name = ( $index ) ? $lang['acp_overview'] : $_top;
				
				$sql = sql(USERS, $mode, $data_sql, 'user_id', $data);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $name);
				
			#	$oCache -> sCachePath = './../cache/';
			#	$oCache -> deleteCache('display_subnavi_user');
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept && $userauth['A_USER_DELETE'] )
			{
				$fields .= build_fields(array(
					'mode'		=> $mode,
					'id'		=> $data,
					'index'	=> $index,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['user_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}
			
			break;
			
		default:
			
			$fields = build_fields(array('mode' => 'create'));
			$sqlout = data(USERS, 'user_id != 1', 'user_id DESC', 1, 'set');
			
			$max = count($sqlout);

			for ( $i = $start; $i < min($settings['ppe_acp'] + $start, $max); $i++ )
			{
				$id		= $sqlout[$i]['user_id'];
				$name	= $sqlout[$i]['user_name'];
				
				$template->assign_block_vars('display.row', array(
					'USERNAME'	=> ( @$userauth['A_USER'] /*&&		($userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'])*/ ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name) : $name,
					'AUTH'		=> ( @$userauth['A_USER'] /*&&		($userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'])*/ ) ? href('a_img', $file, array('mode' => 'permission', 'id' => $id), 'icon_auth', '') : img('i_icon', 'icon_auth2', ''),
					'FIELD'		=> ( @$userauth['A_USER_FIELDS'] /*&&	($userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'])*/ ) ? href('a_img', $file, array('mode' => 'fields', 'id' => $id), 'icon_field', '') : img('i_icon', 'icon_field2', ''),
					'GROUP'		=> ( @$userauth['A_GROUP_MANAGE'] || @$userauth['A_TEAM_MANAGE'] /*&&		($userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'])*/ ) ? href('a_img', $file, array('mode' => 'groups', 'id' => $id), 'icon_group', '') : img('i_icon', 'icon_group2', ''),
					'UPDATE'	=> ( @$userauth['A_USER'] /*&&		($userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'])*/ ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE'),
					'DELETE'	=> ( @$userauth['A_USER_DELETE'] /*&&	($userdata['user_level'] == ADMIN || $userdata['user_level'] > $tmp[$i]['user_level'])*/ ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE'),
					
					'REGISTER'	=> create_date($userdata['user_dateformat'], $sqlout[$i]['user_regdate'], $userdata['user_timezone']),
				));
			}
			
			$current_page = ( !$max ) ? 1 : ceil($max/$settings['ppe_acp']);
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['user']),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['user']),
				'L_INPUT'	=> sprintf($lang['STF_CREATE'], $lang['user']),
				'L_NAME'	=> $lang['user'],
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				
				'PAGE_NUMBER'	=> ( $max ) ? sprintf($lang['common_page_of'], ( floor( $start / $settings['ppe_acp'] ) + 1 ), $current_page ) : '',
				'PAGE_PAGING'	=> ( $max ) ? generate_pagination($file . '?', $max, $settings['ppe_acp'], $start ) : '',
				
				'S_CREATE'	=> check_sid("$file?mode=create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>