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
	$result = $db->sql_query($sql);
	
    // ist der Tag nocht nicht vorhanden,  
    // wird ein neuer Tagescounter erstellt 
    if (!$db->sql_numrows($result))
	{
		$sql = 'INSERT INTO ' . COUNTER_COUNTER_TABLE . ' SET counter_date = CURDATE()'; 
		$result = $db->sql_query($sql);
    }
	
	// Alte (mehr als 1 Tag) IPs in 'Online' löschen 
	// damit die Datenbank nicht überfüllt wird
	$sql = 'DELETE FROM ' . COUNTER_ONLINE_TABLE . ' WHERE DATE_SUB(NOW(), INTERVAL 1 DAY) > online_date'; 
	$result = $db->sql_query($sql);
	
	// Überprüfe, ob die IP bereits gespeichert ist
	$sql = 'SELECT online_ip FROM ' . COUNTER_ONLINE_TABLE . ' WHERE online_ip = "' . $userdata['session_ip'] . '"';
	$result = $db->sql_query($sql);
		
	// Falls nicht, wird sie gespeichert 
    if (!$db->sql_numrows($result))
	{
		$sql = 'INSERT INTO ' . COUNTER_ONLINE_TABLE . ' (online_ip, online_date) VALUES ("' . $userdata['session_ip'] . '", NOW())'; 
		$result = $db->sql_query($sql);
		
		// ... und die Anzahl wird um 1 erhöht 
		$sql = 'UPDATE ' . COUNTER_COUNTER_TABLE . ' SET counter_entry = counter_entry+1 WHERE counter_date = CURDATE()'; 
		$result = $db->sql_query($sql);
    } 
    // Falls ja, wird ihr Datum aktualisiert 
    else
	{
		$sql = 'UPDATE ' . COUNTER_ONLINE_TABLE . ' SET online_date = NOW() WHERE online_ip = "' . $userdata['session_ip'] . '"';
		$result = $db->sql_query($sql);
	}
	
	// User die 'heute' auf der Seite waren 
    $sql = 'SELECT counter_entry FROM ' . COUNTER_COUNTER_TABLE . ' WHERE counter_date = CURDATE()'; 
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$stats_day = (int) $row['counter_entry'];
	$db->sql_freeresult($result);
	
	// User die 'gestern' auf der Seite waren 
	$sql = 'SELECT counter_entry AS sum FROM ' . COUNTER_COUNTER_TABLE . ' WHERE counter_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)'; 
	$row = _cached($sql, 'counter_stats_yesterday', 1, 3600);
	$stats_yesterday = (int) $row['sum'];
	
	// user-monat: 
	$sql = 'SELECT SUM(counter_entry) AS sum FROM ' . COUNTER_COUNTER_TABLE . " WHERE counter_entry != 0 AND DATE_FORMAT(counter_date, '%m') = DATE_FORMAT(NOW(), '%m')"; 
	$row = _cached($sql, 'counter_stats_month', 1, 3600);
	$stats_month = (int) $row['sum'];
	
	// User die insgesamt die Seite besucht haben. 
    // Dazu wird die Gruppenfunktion SUM() 
    // verwendet, die alle Werte der Spalte 'Anzahl' summiert 
    $sql = 'SELECT SUM(counter_entry) AS sum FROM ' . COUNTER_COUNTER_TABLE;
	$row = _cached($sql, 'counter_stats_total', 1, 3600);
	$stats_year = (int) $row['sum'];
	
	$l_counter_head = sprintf($lang['counter_today'], $stats_day);
	$l_counter_head .= sprintf($lang['counter_yesterday'], $stats_yesterday);
	$l_counter_head .= sprintf($lang['counter_month'], $stats_month);
	$l_counter_head .= sprintf($lang['counter_year'], $stats_year);
	$l_counter_head .= sprintf($lang['counter_total'], $stats_year+$config['counter_start']);
	
	if ( $settings['subnavi_statscounter'] )
	{
		$template->assign_block_vars('statscounter', array());
	
		$template->assign_vars(array(
			'STATS_COUNTER_TODAY' 		=> sprintf($lang['counter_today'], $stats_day),
			'STATS_COUNTER_YESTERDAY' 	=> sprintf($lang['counter_yesterday'], $stats_yesterday),
			'STATS_COUNTER_MONTH' 		=> sprintf($lang['counter_month'], $stats_month),
			'STATS_COUNTER_YEAR' 		=> sprintf($lang['counter_year'], $stats_year),
			'STATS_COUNTER_TOTAL' 		=> sprintf($lang['counter_total'], $stats_year+$config['counter_start']),
			'STATS_COUNTER_CACHE'		=> (defined('CACHE')) ? display_cache('counter_stats_total', 1) : '',
		));
	}
}

$sql = 'SELECT group_id, group_name, group_color, group_order FROM ' . GROUPS_TABLE . ' WHERE group_legend = 1 ORDER BY group_order';
$group_data = _cached($sql, 'list_overall_group');


$group_list = array();
for ( $i=0; $i < count($group_data); $i++ )
{
	$group_id		= $group_data[$i]['group_id'];
	$group_name		= $group_data[$i]['group_name'];
	$group_style	= $group_data[$i]['group_color'];
	
	$group_list[] = '<a href="' . append_sid("groups.php?mode=view&amp;" . POST_GROUPS_URL . "=" . $group_id) . '" style="color:#' . $group_style . '"><b>' . $group_name . '</b></a>';

}

$group_list = implode(', ', $group_list);
			
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
$sql = "SELECT u.username, u.user_id, u.user_allow_viewonline, u.user_level, u.user_color, s.session_logged_in, s.session_ip
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
			
			$row['username'] = '<b>' . $row['username'] . '</b>';
			$style_color = 'style="color:#' . $row['user_color'] . '"';

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

	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '$total_online_users' WHERE config_name = 'record_online_users'";
	$db->sql_query($sql);

	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . $config['record_online_date'] . "' WHERE config_name = 'record_online_date'";
	$db->sql_query($sql);
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

if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
{
	if ( $settings['subnavi_training'] )	{ display_navitrain(); }
}

if ( $settings['subnavi_minical'] )		{ display_minical(); }
if ( $settings['subnavi_newusers'] )	{ display_newusers(); }
if ( $settings['subnavi_teams'] )		{ display_teams(); }
if ( $settings['subnavi_matches'] )		{ display_navimatch(); }

if ( $settings['subnavi_statsonline'] )
{
	$template->assign_block_vars('statsonline', array());
	
	$l_t_user_s_n = ( $total_online_users != 0 )	? ( $total_online_users == 1 )		? $l_t_user_s = $lang['n_online_user_total'] : $l_t_user_s = $lang['n_online_users_total'] : $lang['n_online_users_zero_total'];
	$l_r_user_s_n = ( $logged_visible_online != 0 )	? ( $logged_visible_online == 1 )	? $l_r_user_s = $lang['n_reg_user_total'] : $l_r_user_s = $lang['n_reg_users_total'] : $l_r_user_s = $lang['n_reg_users_zero_total'];
	$l_h_user_s_n = ( $logged_hidden_online != 0 )	? ( $logged_hidden_online == 1 )	? $l_h_user_s = $lang['n_hidden_user_total'] : $l_h_user_s = $lang['n_hidden_users_total'] : $l_h_user_s = $lang['n_hidden_users_zero_total'];	
	$l_g_user_s_n = ( $guests_online != 0 )			? ( $guests_online == 1 )			? $l_g_user_s = $lang['n_guest_user_total'] : $l_g_user_s = $lang['n_guest_users_total'] : $l_g_user_s = $lang['n_guest_users_zero_total'];
	
	$l_online_users_total	= sprintf($l_t_user_s_n, $total_online_users);
	$l_online_users_visible	= sprintf($l_r_user_s_n, $logged_visible_online);
	$l_online_users_hidden	= sprintf($l_h_user_s_n, $logged_hidden_online);
	$l_online_users_guests	= sprintf($l_g_user_s_n, $guests_online);
	
	$template->assign_vars(array(
		'STATS_ONLINE_TOTAL' 	=> $l_online_users_total,
		'STATS_ONLINE_VISIBLE' 	=> $l_online_users_visible,
		'STATS_ONLINE_HIDDEN' 	=> $l_online_users_hidden,
		'STATS_ONLINE_GUESTS' 	=> $l_online_users_guests,
	));
}

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
	
	'TOTAL_COUNTER_HEAD'		=> $l_counter_head,
	'TOTAL_USERS_ONLINE_HEAD'	=> $l_online_users_head,
	
	'GROUPS_LEGENED'	=> 'Gruppen: ' . $group_list,
	
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
	'U_INDEX' => append_sid('forum.php'),
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

//
// Show board disabled note
//
if (defined('PAGE_DISABLE'))
{
	$disable_message = (!empty($config['page_disable_msg'])) ? htmlspecialchars($config['page_disable_msg']) : $lang['Board_disable'];
	$template->assign_block_vars('page_disable', array('MSG' => str_replace("\n", '<br />', $disable_message)));
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