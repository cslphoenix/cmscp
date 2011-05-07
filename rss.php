<?php

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

$template->set_filenames(array('body' => 'body_rss.tpl'));

//
// BEGIN Assign static variables to template
//
// Variable reassignment for Topic Replies
$l_topic_replies = $lang['Topic'] . ' ' . $lang['Replies'];
$template->assign_vars(array(
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'PAGE_URL' => $index_url,
	'PAGE_TITLE' => $site_name,
	'PAGE_DESCRIPTION' => $page_desc,
	'PAGE_MANAGING_EDITOR' => $config['page_email'],
	'PAGE_WEBMASTER' => $config['page_email'],
	'BUILD_DATE' => gmdate('D, d M Y H:i:s', time()) . ' GMT', 
	'L_AUTHOR' => $lang['Author'],
	'L_POSTED' => $lang['Posted'],
	'L_TOPIC_REPLIES' => $l_topic_replies,
	'L_POST' => $lang['Post'])
);
//
// END Assign static variabless to template
//

$sql = 'SELECT n.*, nc.cat_name, nc.cat_image, u.user_name, u.user_color, m.*, t.team_name, g.game_image, g.game_size
			FROM ' . NEWS . ' n
				LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
				LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
				LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
				LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
				LEFT JOIN ' . NEWSCAT . ' nc ON n.news_cat = nc.cat_id
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
		$news_date = create_date($config['default_dateformat'], $news_data[$i]['news_time_public'], $config['page_timezone']); 
		
		$template->assign_block_vars('post_item', array(
			'NEWS_TITLE'		=> $news_data[$i]['news_title'],
			'NEWS_DATE'			=> $news_date,
			'NEWS_AUTHOR'		=> $news_data[$i]['user_name'],
		));
	}
}

header("Content-type: text/xml");

$template->pparse('body');

?>