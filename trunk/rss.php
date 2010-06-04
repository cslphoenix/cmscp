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

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_RSS);
init_userprefs($userdata);

// Build URL components
$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['page_path']));
$viewpost = ( $script_name != '' ) ? $script_name . '/viewtopic.' . $phpEx : 'viewtopic.'. $phpEx;
$index = ( $script_name != '' ) ? $script_name . '/index.' . $phpEx : 'index.'. $phpEx;
$server_name = trim($config['server_name']);
$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';
// Assemble URL components
$index_url = $server_protocol . $server_name . $server_port . $script_name . '/';
$viewpost_url = $server_protocol . $server_name . $server_port . $viewpost;
// Reformat site name and description
$site_name = strip_tags($config['page_name']);
$page_desc = strip_tags($config['page_desc']);
//
// END Create main board information
//
//
// MOD - TODAY AT - BEGIN
// PARSE DATEFORMAT TO GET TIME FORMAT 
//
$time_reg = '([gh][[:punct:][:space:]]{1,2}[i][[:punct:][:space:]]{0,2}[a]?[[:punct:][:space:]]{0,2}[S]?)';
//eregi($time_reg, $config['default_dateformat'], $regs);
//$config['default_timeformat'] = $regs[1];
unset($time_reg);
unset($regs);

//
// GET THE TIME TODAY AND YESTERDAY
//
$today_ary = explode('|', create_date('m|d|Y', time(), $config['page_timezone']));
$config['time_today'] = gmmktime(0 - $config['page_timezone'] - $config['page_timezone'],0,0,$today_ary[0],$today_ary[1],$today_ary[2]);
$config['time_yesterday'] = $config['time_today'] - 86400;
unset($today_ary);

$template->set_filenames(array('body' => 'body_rss.xml'));

//
// BEGIN Assign static variables to template
//
// Variable reassignment for Topic Replies
$l_topic_replies = $lang['Topic'] . ' ' . $lang['Replies'];
$template->assign_vars(array(
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'BOARD_URL' => $index_url,
	'BOARD_TITLE' => $site_name,
	'BOARD_DESCRIPTION' => $page_desc,
	'BOARD_MANAGING_EDITOR' => $config['page_email'],
	'BOARD_WEBMASTER' => $config['page_email'],
	'BUILD_DATE' => gmdate('D, d M Y H:i:s', time()) . ' GMT', 
	'L_AUTHOR' => $lang['Author'],
	'L_POSTED' => $lang['Posted'],
	'L_TOPIC_REPLIES' => $l_topic_replies,
	'L_POST' => $lang['Post'])
);
//
// END Assign static variabless to template
//

$sql = 'SELECT n.*, nc.newscat_title, nc.newscat_image, u.username, u.user_color, m.*, md.*, t.team_name, g.game_image, g.game_size
			FROM ' . NEWS . ' n
				LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
				LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
				LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
				LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
				LEFT JOIN ' . MATCH_DETAILS . ' md ON m.match_id = md.match_id
				LEFT JOIN ' . NEWSCAT . ' nc ON n.news_category = nc.newscat_id
			WHERE n.news_time_public < ' . time() . ' AND n.news_intern = 0 AND news_public = 1
		ORDER BY n.news_time_public DESC, n.news_id DESC';
if ( !($result = $db->sql_query($sql)) )
{
	message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
}
$news_data = $db->sql_fetchrowset($result);

if ( !$news_data )
{
	message(GENERAL_MESSAGE, $lang['No_match']);
}
else
{
	for ( $i = 0; $i < count($news_data); $i++ )
	{
		$news_date = create_date($userdata['user_dateformat'], $news_data[$i]['news_time_public'], $userdata['user_timezone']); 
			
		if ( $config['time_today'] < $news_data[$i]['news_time_public'])
		{ 
			$news_date = sprintf($lang['today_at'], create_date($config['default_timeformat'], $news_data[$i]['news_time_public'], $userdata['user_timezone'])); 
		}
		else if ( $config['time_yesterday'] < $news_data[$i]['news_time_public'])
		{ 
			$news_date = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $news_data[$i]['news_time_public'], $userdata['user_timezone'])); 
		}
		
		$template->assign_block_vars('post_item', array(
			'NEWS_TITLE'		=> $news_data[$i]['news_title'],
			'NEWS_DATE'			=> $news_date,
			'NEWS_AUTHOR'		=> $news_data[$i]['username'],
		));
	}
}


$template->pparse('body');
header("Content-type: text/xml");

?>