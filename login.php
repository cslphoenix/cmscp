<?php

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);
//define('IN_CMS', true);
define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

if( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($HTTP_POST_VARS['logout']) || isset($HTTP_GET_VARS['logout']) )
{
	if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) ) && (!$userdata['session_logged_in'] || isset($HTTP_POST_VARS['admin'])) )
	{
		$user_name = isset($HTTP_POST_VARS['user_name']) ? phpbb_clean_user_name($HTTP_POST_VARS['user_name']) : '';
		$password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';

		$sql = "SELECT user_id, user_name, user_password, user_active, user_level, user_login_tries, user_last_login_try
			FROM " . USERS . "
			WHERE user_name = '" . str_replace("\\'", "''", $user_name) . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
//			if( $row['user_level'] != ADMIN && $config['page_disable'] )
			$disable_mode = explode(',', $config['page_disable_mode']);
			if ($config['page_disable'] && $row['user_level'] != ADMIN && in_array($row['user_level'], $disable_mode))
			{
				redirect(check_sid('news.php', true));
			}
			else
			{
				// If the last login is more than x minutes ago, then reset the login tries/time
				if ($row['user_last_login_try'] && $config['login_reset_time'] && $row['user_last_login_try'] < (time() - ($config['login_reset_time'] * 60)))
				{
					$db->sql_query('UPDATE ' . USERS . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);
					$row['user_last_login_try'] = $row['user_login_tries'] = 0;
				}
				
				// Check to see if user is allowed to login again... if his tries are exceeded
				if ($row['user_last_login_try'] && $config['login_reset_time'] && $config['max_login_attempts'] && 
					$row['user_last_login_try'] >= (time() - ($config['login_reset_time'] * 60)) && $row['user_login_tries'] >= $config['max_login_attempts'] && $userdata['user_level'] != ADMIN )
				{
					message(GENERAL_MESSAGE, sprintf($lang['Login_attempts_exceeded'], $config['max_login_attempts'], $config['login_reset_time']));
				}
				
				if ( md5($password) == $row['user_password'] && $row['user_active'] )
				{
					if ( isset($HTTP_POST_VARS['admin']) && $userdata['user_name'] != $user_name )
					{
						$message = $lang['Error_login'] . '<br><br>' . sprintf($lang['Click_return_login'], "<a href=\"login.php?redirect=$redirect\">", '</a>') . '<br><br>' .  sprintf($lang['Click_return_index'], '<a href="' . check_sid('news.php') . '">', '</a>');
						message(GENERAL_MESSAGE, $message);
					}
					$autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;

					$admin = (isset($HTTP_POST_VARS['admin'])) ? 1 : 0;
					$session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin, $admin);

					// Reset login tries
					$db->sql_query('UPDATE ' . USERS . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);
					
					if ( isset($HTTP_POST_VARS['admin']) )
					{
//						$login = ($userdata['user_level'] == ADMIN ) ? ACP_LOGIN : MCP_LOGIN;
//						log_add($login, $user_ip, time(), $userdata['user_id'], $userdata['user_name'], $forum_id, $topic_id, $rule_id, $fight_id, $report_id, $cat_id, $lang['Login_Log_True'], '');
					#	log_add(LOG_USERS, SECTION_LOGIN, 'ucp_acp_login');
						log_add(LOG_ADMIN, SECTION_LOGIN, 'login_acp', 'login true');
					}
					else
					{
						log_add(LOG_USERS, SECTION_LOGIN, 'login', 'login true');
					}

					if( $session_id )
					{
						$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "ucp.php";
						redirect(check_sid($url, true));
					}
					else
					{
						message(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
					}
				}
				// Only store a failed login attempt for an active user - inactive users can't login even with a correct password
				else if ( $row['user_active'] )
				{
					// Save login tries and last login
					if ($row['user_id'] != ANONYMOUS)
					{
						$sql = 'UPDATE ' . USERS . '
							SET user_login_tries = user_login_tries + 1, user_last_login_try = ' . time() . '
							WHERE user_id = ' . $row['user_id'];
						$db->sql_query($sql);
					}
					
					if (isset($HTTP_POST_VARS['admin']))
					{
						$login = ( $userdata['user_level'] == ADMIN ) ? ACP_LOGIN : MCP_LOGIN;
					#	log_add($login, $user_ip, time(), $userdata['user_id'], $userdata['user_name'], $forum_id, $topic_id, $rule_id, $fight_id, $report_id, $cat_id, $lang['Login_Log_True'], '');
					#	log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], SECTION_LOGIN, UCP_ACP_LOGIN_FALSE, '');
					#	log_add(LOG_ADMIN, $log, $mode);
					#	log_add($type, $log, $message, $data = '');
						log_add(LOG_ADMIN, SECTION_LOGIN, 'ucp_acp_login2');
					}
				}

				$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : '';
				$redirect = str_replace('?', '&', $redirect);

				if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r") || strstr(urldecode($redirect), ';url'))
				{
					message(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
				}
				
				$template->assign_vars(array('META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.php?redirect=$redirect\">"));

				$message = $lang['Error_login'] . '<br><br>' . sprintf($lang['Click_return_login'], "<a href=\"login.php?redirect=$redirect\">", '</a>') . '<br><br>' .  sprintf($lang['Click_return_index'], '<a href="' . check_sid('news.php') . '">', '</a>');

				message(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "";
			$redirect = str_replace("?", "&", $redirect);

			if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r") || strstr(urldecode($redirect), ';url'))
			{
				message(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
			}
				
			if (isset($HTTP_POST_VARS['admin']))
			{
//				$login = ($userdata['user_level'] == ADMIN ) ? ACP_LOGIN : MCP_LOGIN;
//				log_add($login, $user_ip, time(), $userdata['user_id'], $userdata['user_name'], $forum_id, $topic_id, $rule_id, $fight_id, $report_id, $cat_id, $lang['Login_Log_False'], '');
			#	log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], SECTION_LOGIN, 'ucp_login_false');
			}

			$template->assign_vars(array('META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.php?redirect=$redirect\">"));

			$message = $lang['Error_login'] . '<br><br>' . sprintf($lang['Click_return_login'], "<a href=\"login.php?redirect=$redirect\">", '</a>') . '<br><br>' .  sprintf($lang['Click_return_index'], '<a href="' . check_sid('news.php') . '">', '</a>');

			message(GENERAL_MESSAGE, $message);
		}
	}
	// Admin Session Logout
//	else if( isset($HTTP_GET_VARS['admin_session_logout']) && $userdata['user_level'] == ADMIN )
	else if( isset($HTTP_GET_VARS['admin_session_logout']) )
	{
		// session id check
		if ( $sid == '' || $sid != $userdata['session_id'] )
		{
			message(GENERAL_ERROR, 'Invalid_session');
		}
		
		$sql = "UPDATE " . SESSIONS . " SET session_admin = 0 WHERE session_id = '" . $userdata['session_id'] . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message(CRITICAL_ERROR, 'Couldn\'t update Sessions Table', '', __LINE__, __FILE__, $sql);
		}
		
	#	log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], SECTION_LOGIN, 'ucp_acp_logout');
		
		redirect(check_sid('news.php', true));
	}
	else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) && $userdata['session_logged_in'] )
	{
		// session id check
		if ($sid == '' || $sid != $userdata['session_id'])
		{
			message(GENERAL_ERROR, 'Invalid_session');
		}

		if( $userdata['session_logged_in'] )
		{
			session_end($userdata['session_id'], $userdata['user_id']);
		}

		if (!empty($HTTP_POST_VARS['redirect']) || !empty($HTTP_GET_VARS['redirect']))
		{
			$url = (!empty($HTTP_POST_VARS['redirect'])) ? htmlspecialchars($HTTP_POST_VARS['redirect']) : htmlspecialchars($HTTP_GET_VARS['redirect']);
			$url = str_replace('&amp;', '&', $url);
			redirect(check_sid($url, true));
		}
		else
		{
			redirect(check_sid('news.php', true));
		}
	}
	else
	{
		$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "news.php";
		redirect(check_sid($url, true));
	}
}
else
{
	//
	// Do a full login page dohickey if
	// user not already logged in
	//

	$userauth = auth_acp_check($userdata['user_id']);
	$auth = array();
	foreach ($userauth as $key => $value)
	{
		if ($value != '0')
		{
			$auth[$key] = $value;
		}
	}
	
	if ( !$userdata['session_logged_in'] || ( isset($HTTP_GET_VARS['admin']) && $userdata['session_logged_in'] && ( $userdata['user_level'] == ADMIN || $auth ) ) )
	{
		$page_title = $lang['Login'];
		main_header();

		$template->set_filenames(array('body' => 'login_body.tpl'));

		$forward_page = '';

		if ( isset($HTTP_POST_VARS['redirect']) || isset($HTTP_GET_VARS['redirect']) )
		{
			$forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

			if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
			{
				$forward_to		= ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
				$forward_match	= explode('&', $forward_to);

				if ( count($forward_match) > 1 )
				{
					for ($i = 1; $i < count($forward_match); $i++)
					{
					//	if ( !ereg("sid=", $forward_match[$i]) )
						if ( !preg_match("/sid=/", $forward_match[$i]) )
						{
							if( $forward_page != '' )
							{
								$forward_page .= '&';
							}
							$forward_page .= $forward_match[$i];
						}
					}
					$forward_page = $forward_match[0] . '?' . $forward_page;
				}
				else
				{
					$forward_page = $forward_match[0];
				}
			}
		}

		$user_name = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['user_name'] : '';

		$s_hidden_fields = '<input type="hidden" name="redirect" value="' . $forward_page . '" />';
		$s_hidden_fields .= (isset($HTTP_GET_VARS['admin'])) ? '<input type="hidden" name="admin" value="1" />' : '';

		$template->assign_vars(array(
			'USERNAME' => $user_name,

			'L_ENTER_PASSWORD' => (isset($HTTP_GET_VARS['admin'])) ? $lang['Admin_reauthenticate'] : $lang['Enter_password'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],

			'U_SEND_PASSWORD' => check_sid('profile.php?mode=sendpassword'),

			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		main_footer();
	}
	else
	{
		redirect(check_sid('news.php', true));
	}

}

?>