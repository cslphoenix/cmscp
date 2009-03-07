<?php

if ( !defined('IN_CMS') )
{
	die("Hacking attempt");
}

define('HEADER_INC', TRUE);

//
// gzip_compression
//
$do_gzip_compress = FALSE;
if ( $config['gzip_compress'] )
{
	$phpver = phpversion();

	$useragent = (isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) ? $HTTP_SERVER_VARS['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');

	if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if ( extension_loaded('zlib') )
		{
			ob_start('ob_gzhandler');
		}
	}
	else if ( $phpver > '4.0' )
	{
		if ( strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') )
		{
			if ( extension_loaded('zlib') )
			{
				$do_gzip_compress = TRUE;
				ob_start();
				ob_implicit_flush(0);

				header('Content-Encoding: gzip');
			}
		}
	}
}

//
// Parse and show the overall header.
//
$template->set_filenames(array(
	'overall_header' => ( empty($gen_simple_header) ) ? 'overall_header.tpl' : 'simple_header.tpl')
);

//
// Generate logged in/logged out status
//
if ( $userdata['session_logged_in'] )
{
	$u_login_logout = 'login.php?logout=true&amp;sid=' . $userdata['session_id'];
	$l_login_logout = $lang['Logout'] . ' [ ' . $userdata['username'] . ' ]';
}
else
{
	$smart_redirect = strrchr($_SERVER['PHP_SELF'], '/');
	$smart_redirect = substr($smart_redirect, 1, strlen($smart_redirect));

	if( ($smart_redirect == ('profile.php')) or ($smart_redirect == ('login.php')) )
	{
		$smart_redirect = '';
	}

	if( isset($HTTP_GET_VARS) and !empty($smart_redirect) )
	{		
		$smart_get_keys = array_keys($HTTP_GET_VARS);

		for ($i = 0; $i < count($HTTP_GET_VARS); $i++)
		{
			if ($smart_get_keys[$i] != 'sid')
			{
				$smart_redirect .= '&amp;' . $smart_get_keys[$i] . '=' . $HTTP_GET_VARS[$smart_get_keys[$i]];
			}
		}
	}

	$u_login_logout = 'login.php';
	$u_login_logout .= (!empty($smart_redirect)) ? '?redirect=' . $smart_redirect : '';
	$l_login_logout = $lang['Login'];
}

$s_last_visit = ( $userdata['session_logged_in'] ) ? create_date($config['default_dateformat'], $userdata['user_lastvisit'], $config['board_timezone']) : '';

//
//	Counter
//	von http://www.mywebsolution.de/
//
if ($settings['counter'] == '1')
{
	// Prüfen, ob bereits ein Counter für den  
    // heutigen Tag erstellt wurde 
    $sql = 'SELECT counter_id FROM ' . COUNTER_COUNTER_TABLE . ' WHERE counter_date = CURDATE()'; 
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
	}
    // ist der Tag nocht nicht vorhanden,  
    // wird ein neuer Tagescounter erstellt 
    if (!$db->sql_numrows($result))
	{
		$sql = 'INSERT INTO ' . COUNTER_COUNTER_TABLE . ' SET counter_date = CURDATE()'; 
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
		}
    }
	
	// Alte (mehr als 1 Tag) IPs in 'Online' löschen 
	// damit die Datenbank nicht überfüllt wird
	$sql = 'DELETE FROM ' . COUNTER_ONLINE_TABLE . ' WHERE DATE_SUB(NOW(), INTERVAL 1 DAY) > online_date'; 
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
	}
	
	// Überprüfe, ob die IP bereits gespeichert ist
	$sql = 'SELECT online_ip FROM ' . COUNTER_ONLINE_TABLE . ' WHERE online_ip = "' . $userdata['session_ip'] . '"';
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
	}
		
	// Falls nicht, wird sie gespeichert 
    if (!$db->sql_numrows($result))
	{
		$sql = 'INSERT INTO ' . COUNTER_ONLINE_TABLE . ' (online_ip, online_date) VALUES ("' . $userdata['session_ip'] . '", NOW())'; 
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
		}
		
		// ... und die Anzahl wird um 1 erhöht 
        $sql = 'UPDATE ' . COUNTER_COUNTER_TABLE . ' SET counter_entry = counter_entry+1 WHERE counter_date = CURDATE()'; 
        if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
		}
    } 
    // Falls ja, wird ihr Datum aktualisiert 
    else
	{
		$sql = 'UPDATE ' . COUNTER_ONLINE_TABLE . ' SET online_date = NOW() WHERE online_ip = "' . $userdata['session_ip'] . '"';
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
		}
	}
	
	// User die 'heute' auf der Seite waren 
    $sql = 'SELECT counter_entry FROM ' . COUNTER_COUNTER_TABLE . ' WHERE counter_date = CURDATE()'; 
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	print 'heute: '. $row['counter_entry'] .'<br />';
	$db->sql_freeresult($result);
	
	// User die 'gestern' auf der Seite waren 
	$sql = 'SELECT counter_entry FROM ' . COUNTER_COUNTER_TABLE . ' WHERE counter_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)'; 
    $result = $db->sql_query($sql); 
    $row = $db->sql_fetchrow($result); 
    echo 'gestern: '. $row['counter_entry'] .'<br />';
	$db->sql_freeresult($result);
	
	// User die insgesamt die Seite besucht haben. 
    // Dazu wird die Gruppenfunktion SUM() 
    // verwendet, die alle Werte der Spalte 'Anzahl' summiert 
    $sql = 'SELECT SUM(counter_entry) FROM ' . COUNTER_COUNTER_TABLE;
	$result = $db->sql_query($sql);
	echo 'insgesamt: '. mysql_result($result, 0);
	$db->sql_freeresult($result);
}
//
// Get basic (usernames + totals) online
// situation
//
$logged_visible_online = 0;
$logged_hidden_online = 0;
$guests_online = 0;
$online_userlist = '';
$l_online_users = '';
$l_online_users_head = '';

	$user_forum_sql = ( !empty($forum_id) ) ? "AND s.session_page = " . intval($forum_id) : '';
	$sql = "SELECT u.username, u.user_id, u.user_allow_viewonline, u.user_level, s.session_logged_in, s.session_ip
		FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s
		WHERE u.user_id = s.session_user_id
			AND s.session_time >= ".( time() - 300 ) . "
			$user_forum_sql
		ORDER BY u.username ASC, s.session_ip ASC";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user/online information', '', __LINE__, __FILE__, $sql);
	}

	$userlist_ary = array();
	$userlist_visible = array();

	$prev_user_id = 0;
	$prev_user_ip = $prev_session_ip = '';

	while( $row = $db->sql_fetchrow($result) )
	{
		// User is logged in and therefor not a guest
		if ( $row['session_logged_in'] )
		{
			// Skip multiple sessions for one user
			if ( $row['user_id'] != $prev_user_id )
			{
				$style_color = '';
				if ( $row['user_level'] == ADMIN )
				{
					$row['username'] = '<b>' . $row['username'] . '</b>';
					$style_color = 'style="color:#' . $theme['fontcolor3'] . '"';
				}
				else if ( $row['user_level'] == MOD )
				{
					$row['username'] = '<b>' . $row['username'] . '</b>';
					$style_color = 'style="color:#' . $theme['fontcolor2'] . '"';
				}

				if ( $row['user_allow_viewonline'] )
				{
					$user_online_link = '<a href="' . append_sid("profile.php?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '"' . $style_color .'>' . $row['username'] . '</a>';
					$logged_visible_online++;
				}
				else
				{
					$user_online_link = '<a href="' . append_sid("profile.php?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '"' . $style_color .'><i>' . $row['username'] . '</i></a>';
					$logged_hidden_online++;
				}

				if ( $row['user_allow_viewonline'] || $userdata['user_level'] == ADMIN )
				{
					$online_userlist .= ( $online_userlist != '' ) ? ', ' . $user_online_link : $user_online_link;
				}
			}

			$prev_user_id = $row['user_id'];
		}
		else
		{
			// Skip multiple sessions for one user
			if ( $row['session_ip'] != $prev_session_ip )
			{
				$guests_online++;
			}
		}

		$prev_session_ip = $row['session_ip'];
	}
	$db->sql_freeresult($result);

	if ( empty($online_userlist) )
	{
		$online_userlist = $lang['None'];
	}
	$online_userlist = ( ( isset($forum_id) ) ? $lang['Browsing_forum'] : $lang['Registered_users'] ) . ' ' . $online_userlist;

	$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

	if ( $total_online_users > $config['record_online_users'])
	{
		$config['record_online_users'] = $total_online_users;
		$config['record_online_date'] = time();

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$total_online_users'
			WHERE config_name = 'record_online_users'";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update online user record (nr of users)', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . $config['record_online_date'] . "'
			WHERE config_name = 'record_online_date'";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update online user record (date)', '', __LINE__, __FILE__, $sql);
		}
	}
	
	$l_t_user_s = ( $total_online_users != 0 )		? ( $total_online_users == 1 )		? $l_t_user_s = $lang['online_user_total'] : $l_t_user_s = $lang['online_users_total'] : $lang['online_users_zero_total'];
	$l_r_user_s = ( $logged_visible_online != 0 )	? ( $logged_visible_online == 1 )	? $l_r_user_s = $lang['reg_user_total'] : $l_r_user_s = $lang['reg_users_total'] : $l_r_user_s = $lang['reg_users_zero_total'];
	$l_h_user_s = ( $logged_hidden_online != 0 )	? ( $logged_hidden_online == 1 )	? $l_h_user_s = $lang['hidden_user_total'] : $l_h_user_s = $lang['hidden_users_total'] : $l_h_user_s = $lang['hidden_users_zero_total'];	
	$l_g_user_s = ( $guests_online != 0 )			? ( $guests_online == 1 )			? $l_g_user_s = $lang['guest_user_total'] : $l_g_user_s = $lang['guest_users_total'] : $l_g_user_s = $lang['guest_users_zero_total'];
	
	$l_t_user_s_h = ( $total_online_users != 0 )	? ( $total_online_users == 1 )		? $l_t_user_s = $lang['online_user_total_h'] : $l_t_user_s = $lang['online_users_total_h'] : $lang['online_users_zero_total_h'];
	$l_r_user_s_h = ( $logged_visible_online != 0 )	? ( $logged_visible_online == 1 )	? $l_r_user_s = $lang['reg_user_total_h'] : $l_r_user_s = $lang['reg_users_total_h'] : $l_r_user_s = $lang['reg_users_zero_total_h'];
	$l_h_user_s_h = ( $logged_hidden_online != 0 )	? ( $logged_hidden_online == 1 )	? $l_h_user_s = $lang['hidden_user_total_h'] : $l_h_user_s = $lang['hidden_users_total_h'] : $l_h_user_s = $lang['hidden_users_zero_total_h'];	
	$l_g_user_s_h = ( $guests_online != 0 )			? ( $guests_online == 1 )			? $l_g_user_s = $lang['guest_user_total_h'] : $l_g_user_s = $lang['guest_users_total_h'] : $l_g_user_s = $lang['guest_users_zero_total_h'];
	
	$l_online_users = sprintf($l_t_user_s, $total_online_users);
	$l_online_users .= sprintf($l_r_user_s, $logged_visible_online);
	$l_online_users .= sprintf($l_h_user_s, $logged_hidden_online);
	$l_online_users .= sprintf($l_g_user_s, $guests_online);
	
	$l_online_users_head = sprintf($l_t_user_s_h, $total_online_users);
	$l_online_users_head .= sprintf($l_r_user_s_h, $logged_visible_online);
	$l_online_users_head .= sprintf($l_h_user_s_h, $logged_hidden_online);
	$l_online_users_head .= sprintf($l_g_user_s_h, $guests_online);

/*
//
// Obtain number of new private messages
// if user is logged in
//
if ( ($userdata['session_logged_in']) && (empty($gen_simple_header)) )
{
	if ( $userdata['user_new_privmsg'] )
	{
		$l_message_new = ( $userdata['user_new_privmsg'] == 1 ) ? $lang['New_pm'] : $lang['New_pms'];
		$l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']);

		if ( $userdata['user_last_privmsg'] > $userdata['user_lastvisit'] )
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_last_privmsg = " . $userdata['user_lastvisit'] . "
				WHERE user_id = " . $userdata['user_id'];
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update private message new/read time for user', '', __LINE__, __FILE__, $sql);
			}

			$s_privmsg_new = 1;
			$icon_pm = $images['pm_new_msg'];
		}
		else
		{
			$s_privmsg_new = 0;
			$icon_pm = $images['pm_new_msg'];
		}
	}
	else
	{
		$l_privmsgs_text = $lang['No_new_pm'];

		$s_privmsg_new = 0;
		$icon_pm = $images['pm_no_new_msg'];
	}

	if ( $userdata['user_unread_privmsg'] )
	{
		$l_message_unread = ( $userdata['user_unread_privmsg'] == 1 ) ? $lang['Unread_pm'] : $lang['Unread_pms'];
		$l_privmsgs_text_unread = sprintf($l_message_unread, $userdata['user_unread_privmsg']);
	}
	else
	{
		$l_privmsgs_text_unread = $lang['No_unread_pm'];
	}
}
else
{
	$icon_pm = $images['pm_no_new_msg'];
	$l_privmsgs_text = $lang['Login_check_pm'];
	$l_privmsgs_text_unread = '';
	$s_privmsg_new = 0;
}
*/

//
// Generate HTML required for Mozilla Navigation bar
//
if (!isset($nav_links))
{
	$nav_links = array();
}

$nav_links_html = '';
$nav_link_proto = '<link rel="%s" href="%s" title="%s" />' . "\n";
while( list($nav_item, $nav_array) = @each($nav_links) )
{
	if ( !empty($nav_array['url']) )
	{
		$nav_links_html .= sprintf($nav_link_proto, $nav_item, append_sid($nav_array['url']), $nav_array['title']);
	}
	else
	{
		// We have a nested array, used for items like <link rel='chapter'> that can occur more than once.
		while( list(,$nested_array) = each($nav_array) )
		{
			$nav_links_html .= sprintf($nav_link_proto, $nav_item, $nested_array['url'], $nested_array['title']);
		}
	}
}

display_last_matches();
display_navi();

if ( $settings['subnavi_minical'] )		{ display_minical(); }
if ( $settings['subnavi_newusers'] )	{ display_newusers(); }
if ( $settings['subnavi_teams'] )		{ display_teams(); }

//
// Standardseitentitel
//
if (!isset($page_title))
{
   $page_title = $lang['Information'];
}

// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$l_timezone = explode('.', $config['board_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $config['board_timezone'])] : $lang[number_format($config['board_timezone'])];

//
// The following assigns all _common_ variables that may be used at any point
// in a template.
//
$template->assign_vars(array(
							 
	'NEW_USERS'		=> sprintf($lang['newest_users'], $settings['subnavi_newusers_limit']),
	'SITENAME' => $config['sitename'],
	
	'PAGE_TITLE' => $page_title,
	
	'LAST_VISIT_DATE' => sprintf($lang['You_last_visit'], $s_last_visit),
	'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),
	'TOTAL_USERS_ONLINE' => $l_online_users,
	
	'TOTAL_USERS_ONLINE_HEAD' => $l_online_users_head,
	
	'LOGGED_IN_USER_LIST' => $online_userlist,
	'RECORD_USERS' => sprintf($lang['Record_online_users'], $config['record_online_users'], create_date($config['default_dateformat'], $config['record_online_date'], $config['board_timezone'])),

	'L_USERNAME' => $lang['Username'],
	'L_PASSWORD' => $lang['Password'],
	'L_LOGIN_LOGOUT' => $l_login_logout,
	'L_LOGIN' => $lang['Login'],
	'L_LOG_ME_IN' => $lang['Log_me_in'],
	'L_AUTO_LOGIN' => $lang['Log_me_in'],
	'L_INDEX' => sprintf($lang['Forum_Index'], $config['sitename']),
	'L_REGISTER' => $lang['Register'],
	'L_PROFILE' => $lang['Profile'],
	'L_SEARCH' => $lang['Search'],
	'L_PRIVATEMSGS' => $lang['Private_Messages'],
	'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
	'L_MEMBERLIST' => $lang['Memberlist'],
	'L_FAQ' => $lang['FAQ'],
	'L_USERGROUPS' => $lang['Usergroups'],
	'L_SEARCH_NEW' => $lang['Search_new'],
	'L_SEARCH_UNANSWERED' => $lang['Search_unanswered'],
	'L_SEARCH_SELF' => $lang['Search_your_posts'],
	'L_WHOSONLINE_ADMIN' => sprintf($lang['Admin_online_color'], '<span style="color:#' . $theme['fontcolor3'] . '">', '</span>'),
	'L_WHOSONLINE_MOD' => sprintf($lang['Mod_online_color'], '<span style="color:#' . $theme['fontcolor2'] . '">', '</span>'),

	'U_SEARCH_UNANSWERED' => append_sid('search.php?search_id=unanswered'),
	'U_SEARCH_SELF' => append_sid('search.php?search_id=egosearch'),
	'U_SEARCH_NEW' => append_sid('search.php?search_id=newposts'),
	'U_INDEX' => append_sid('index.php'),
	'U_REGISTER' => append_sid('profile.php?mode=register'),
	'U_PROFILE' => append_sid('profile.php?mode=editprofile'),
	'U_PRIVATEMSGS' => append_sid('privmsg.php?folder=inbox'),
	'U_PRIVATEMSGS_POPUP' => append_sid('privmsg.php?mode=newpm'),
	'U_SEARCH' => append_sid('search.php'),
	'U_MEMBERLIST' => append_sid('memberlist.php'),
	'U_MODCP' => append_sid('modcp.php'),
	'U_FAQ' => append_sid('faq.php'),
	'U_VIEWONLINE' => append_sid('viewonline.php'),
	'U_LOGIN_LOGOUT' => append_sid($u_login_logout),
	'U_GROUP_CP' => append_sid('groupcp.php'),

	'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'S_CONTENT_DIR_LEFT' => $lang['LEFT'],
	'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],
	'S_TIMEZONE' => sprintf($lang['All_times'], $l_timezone),
	'S_LOGIN_ACTION' => append_sid('login.php'),

	'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
	'T_BODY_BACKGROUND' => $theme['body_background'],
	'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
	'T_BODY_TEXT' => '#'.$theme['body_text'],
	'T_BODY_LINK' => '#'.$theme['body_link'],
	'T_BODY_VLINK' => '#'.$theme['body_vlink'],
	'T_BODY_ALINK' => '#'.$theme['body_alink'],
	'T_BODY_HLINK' => '#'.$theme['body_hlink'],
	'T_TR_COLOR1' => '#'.$theme['tr_color1'],
	'T_TR_COLOR2' => '#'.$theme['tr_color2'],
	'T_TR_COLOR3' => '#'.$theme['tr_color3'],
	'T_TR_CLASS1' => $theme['tr_class1'],
	'T_TR_CLASS2' => $theme['tr_class2'],
	'T_TR_CLASS3' => $theme['tr_class3'],
	'T_TH_COLOR1' => '#'.$theme['th_color1'],
	'T_TH_COLOR2' => '#'.$theme['th_color2'],
	'T_TH_COLOR3' => '#'.$theme['th_color3'],
	'T_TH_CLASS1' => $theme['th_class1'],
	'T_TH_CLASS2' => $theme['th_class2'],
	'T_TH_CLASS3' => $theme['th_class3'],
	'T_TD_COLOR1' => '#'.$theme['td_color1'],
	'T_TD_COLOR2' => '#'.$theme['td_color2'],
	'T_TD_COLOR3' => '#'.$theme['td_color3'],
	'T_TD_CLASS1' => $theme['td_class1'],
	'T_TD_CLASS2' => $theme['td_class2'],
	'T_TD_CLASS3' => $theme['td_class3'],
	'T_FONTFACE1' => $theme['fontface1'],
	'T_FONTFACE2' => $theme['fontface2'],
	'T_FONTFACE3' => $theme['fontface3'],
	'T_FONTSIZE1' => $theme['fontsize1'],
	'T_FONTSIZE2' => $theme['fontsize2'],
	'T_FONTSIZE3' => $theme['fontsize3'],
	'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
	'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
	'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
	'T_SPAN_CLASS1' => $theme['span_class1'],
	'T_SPAN_CLASS2' => $theme['span_class2'],
	'T_SPAN_CLASS3' => $theme['span_class3'],

	'NAV_LINKS' => $nav_links_html)
);

//
// Login box?
//
if ( !$userdata['session_logged_in'] )
{
	$template->assign_block_vars('switch_user_logged_out', array());
	//
	// Allow autologin?
	//
	if (!isset($config['allow_autologin']) || $config['allow_autologin'] )
	{
		$template->assign_block_vars('switch_allow_autologin', array());
		$template->assign_block_vars('switch_user_logged_out.switch_allow_autologin', array());
	}
}
else
{
	$template->assign_block_vars('switch_user_logged_in', array());

	if ( !empty($userdata['user_popup_pm']) )
	{
		$template->assign_block_vars('switch_enable_pm_popup', array());
	}
}

// Add no-cache control for cookies if they are set
//$c_no_cache = (isset($HTTP_COOKIE_VARS[$config['cookie_name'] . '_sid']) || isset($HTTP_COOKIE_VARS[$config['cookie_name'] . '_data'])) ? 'no-cache="set-cookie", ' : '';

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header('Cache-Control: no-cache, pre-check=0, post-check=0');
}
else
{
	header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header('Expires:0');
header('Pragma:no-cache');

$template->pparse('overall_header');

?>