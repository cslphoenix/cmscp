<?php

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);
//define('IN_CMS', true);
define('IN_CMS', 1);
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
		$username = isset($HTTP_POST_VARS['username']) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
		$password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';

		$sql = "SELECT user_id, username, user_password, user_active, user_level, user_login_tries, user_last_login_try
			FROM " . USERS_TABLE . "
			WHERE username = '" . str_replace("\\'", "''", $username) . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
		}

		if( $row = $db->sql_fetchrow($result) )
		{
//			if( $row['user_level'] != ADMIN && $config['page_disable'] )
			$disable_mode = explode(',', $config['page_disable_mode']);
			if ($config['page_disable'] && $row['user_level'] != ADMIN && in_array($row['user_level'], $disable_mode))
			{
				redirect(append_sid("index.php", true));
			}
			else
			{
				// If the last login is more than x minutes ago, then reset the login tries/time
				if ($row['user_last_login_try'] && $config['login_reset_time'] && $row['user_last_login_try'] < (time() - ($config['login_reset_time'] * 60)))
				{
					$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);
					$row['user_last_login_try'] = $row['user_login_tries'] = 0;
				}
				
				// Check to see if user is allowed to login again... if his tries are exceeded
				if ($row['user_last_login_try'] && $config['login_reset_time'] && $config['max_login_attempts'] && 
					$row['user_last_login_try'] >= (time() - ($config['login_reset_time'] * 60)) && $row['user_login_tries'] >= $config['max_login_attempts'] && $userdata['user_level'] != ADMIN)
				{
					message_die(GENERAL_MESSAGE, sprintf($lang['Login_attempts_exceeded'], $config['max_login_attempts'], $config['login_reset_time']));
				}

				if( md5($password) == $row['user_password'] && $row['user_active'] )
				{
					$autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;

					$admin = (isset($HTTP_POST_VARS['admin'])) ? 1 : 0;
					$session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin, $admin);

					// Reset login tries
					$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);
					
					if (isset($HTTP_POST_VARS['admin']))
					{
//						$login = ($userdata['user_level'] == ADMIN) ? ACP_LOGIN : MCP_LOGIN;
//						add_log($login, $user_ip, time(), $userdata['user_id'], $userdata['username'], $forum_id, $topic_id, $rule_id, $fight_id, $report_id, $cat_id, $lang['Login_Log_True'], '');
						_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_LOGIN, 'UCP_ACP_LOGIN');
					}

					if( $session_id )
					{
						$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "index.php";
						redirect(append_sid($url, true));
					}
					else
					{
						message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
					}
				}
				// Only store a failed login attempt for an active user - inactive users can't login even with a correct password
				else if ( $row['user_active'] )
				{
					// Save login tries and last login
					if ($row['user_id'] != ANONYMOUS)
					{
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET user_login_tries = user_login_tries + 1, user_last_login_try = ' . time() . '
							WHERE user_id = ' . $row['user_id'];
						$db->sql_query($sql);
					}
					
					if (isset($HTTP_POST_VARS['admin']))
					{
//						$login = ($userdata['user_level'] == ADMIN) ? ACP_LOGIN : MCP_LOGIN;
//						add_log($login, $user_ip, time(), $userdata['user_id'], $userdata['username'], $forum_id, $topic_id, $rule_id, $fight_id, $report_id, $cat_id, $lang['Login_Log_True'], '');
						_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_LOGIN, UCP_ACP_LOGIN_FALSE, '');
					}
				}

				$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : '';
				$redirect = str_replace('?', '&', $redirect);

				if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r") || strstr(urldecode($redirect), ';url'))
				{
					message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
				}
				
				$template->assign_vars(array(
					'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.php?redirect=$redirect\">")
				);

				$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.php?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.php") . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "";
			$redirect = str_replace("?", "&", $redirect);

			if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r") || strstr(urldecode($redirect), ';url'))
			{
				message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
			}
				
			if (isset($HTTP_POST_VARS['admin']))
			{
//				$login = ($userdata['user_level'] == ADMIN) ? ACP_LOGIN : MCP_LOGIN;
//				add_log($login, $user_ip, time(), $userdata['user_id'], $userdata['username'], $forum_id, $topic_id, $rule_id, $fight_id, $report_id, $cat_id, $lang['Login_Log_False'], '');
				_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_LOGIN, UCP_LOGIN_FALSE, '');
			}

			$template->assign_vars(array(
				'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.php?redirect=$redirect\">")
			);

			$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.php?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.php") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	// Admin Session Logout
//	else if( isset($HTTP_GET_VARS['admin_session_logout']) && $userdata['user_level'] == ADMIN )
	else if( isset($HTTP_GET_VARS['admin_session_logout']) )
	{
		// session id check
		if ( $sid == '' || $sid != $userdata['session_id'] )
		{
			message_die(GENERAL_ERROR, 'Invalid_session');
		}
		
		$sql = "UPDATE " . SESSIONS_TABLE . " SET session_admin = 0 WHERE session_id = '" . $userdata['session_id'] . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, 'Couldn\'t update Sessions Table', '', __LINE__, __FILE__, $sql);
		}
		
		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_LOGIN, UCP_ACP_LOGOUT, '');
		
		redirect(append_sid("index.php", true));
	}
	else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) && $userdata['session_logged_in'] )
	{
		// session id check
		if ($sid == '' || $sid != $userdata['session_id'])
		{
			message_die(GENERAL_ERROR, 'Invalid_session');
		}

		if( $userdata['session_logged_in'] )
		{
			session_end($userdata['session_id'], $userdata['user_id']);
		}

		if (!empty($HTTP_POST_VARS['redirect']) || !empty($HTTP_GET_VARS['redirect']))
		{
			$url = (!empty($HTTP_POST_VARS['redirect'])) ? htmlspecialchars($HTTP_POST_VARS['redirect']) : htmlspecialchars($HTTP_GET_VARS['redirect']);
			$url = str_replace('&amp;', '&', $url);
			redirect(append_sid($url, true));
		}
		else
		{
			redirect(append_sid("index.php", true));
		}
	}
	else
	{
		$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "index.php";
		redirect(append_sid($url, true));
	}
}
else
{
	//
	// Do a full login page dohickey if
	// user not already logged in
	//
	if( !$userdata['session_logged_in'] || 
		(isset($HTTP_GET_VARS['admin']) && $userdata['session_logged_in'] && ($userdata['user_level'] == ADMIN || 
				$userdata['auth_teams'] || 
				$userdata['auth_match'] || 
				$userdata['auth_ranks'] || 
				$userdata['auth_games'] ||
					$userdata['auth_contact'] ||
					$userdata['auth_joinus'] ||
					$userdata['auth_fightus'])))
	{
		$page_title = $lang['Login'];
		include($root_path . 'includes/page_header.php');

		$template->set_filenames(array('body' => 'login_body.tpl'));

		$forward_page = '';

		if( isset($HTTP_POST_VARS['redirect']) || isset($HTTP_GET_VARS['redirect']) )
		{
			$forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

			if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
			{
				$forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
				$forward_match = explode('&', $forward_to);

				if(count($forward_match) > 1)
				{
					for($i = 1; $i < count($forward_match); $i++)
					{
						if( !ereg("sid=", $forward_match[$i]) )
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

		$username = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['username'] : '';

		$s_hidden_fields = '<input type="hidden" name="redirect" value="' . $forward_page . '" />';
		$s_hidden_fields .= (isset($HTTP_GET_VARS['admin'])) ? '<input type="hidden" name="admin" value="1" />' : '';

		$template->assign_vars(array(
			'USERNAME' => $username,

			'L_ENTER_PASSWORD' => (isset($HTTP_GET_VARS['admin'])) ? $lang['Admin_reauthenticate'] : $lang['Enter_password'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],

			'U_SEND_PASSWORD' => append_sid("profile.php?mode=sendpassword"),

			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		include($root_path . 'includes/page_tail.php');
	}
	else
	{
		redirect(append_sid("index.php", true));
	}

}

?>