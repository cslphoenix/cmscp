<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_NEWS);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', 0) ) ? request('start', 0) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_NEWS;
$url	= POST_NEWS;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, 0);
$mode	= request('mode', 1);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_news.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'info_error.tpl',
));

if ( $data || ( $mode == 'view' && $data ) )
{
	$template->assign_block_vars('_view', array());
	
	$sql = "SELECT n.*, nc.cat_name, nc.cat_image, u.user_name, u.user_color, m.*, t.team_name, t.team_logo, g.game_image, g.game_size
				FROM " . NEWS . " n
					LEFT JOIN " . USERS . " u ON n.user_id = u.user_id
					LEFT JOIN " . MATCH . " m ON n.match_id = m.match_id
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . NEWSCAT . " nc ON n.news_cat = nc.cat_id
				WHERE n.news_time_public < " . time() . " AND news_public = 1
			ORDER BY n.news_time_public DESC, n.news_id DESC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$news = $db->sql_fetchrow($result);
	$news = _cached($sql, 'data_news');
	
	foreach ( $news as $key => $row )
	{
		if ( $row['news_public'] == 1 && $row['news_id'] == $data )
		{
			$view = $row;
		}
	}
	
	if ( !$view )
	{
		message(GENERAL_ERROR, $lang['msg_news_fail']);
	}
	
	$page_title = sprintf($lang['header_sprintf'], $lang['header_news'], $view['news_title']);
	
	main_header();

	if ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] )
	{
		$template->assign_block_vars('_view._update', array(
			'UPDATE' => "<a href=\"" . check_sid("admin/admin_news.php?mode=_update&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . sprintf($lang['update_sprintf'], $lang['header_news']) . "</a>",
		));
	}
	
	if ( $settings['comments_news'] && $view['news_comments'] )
	{
		$comments = '';
		
		$template->assign_block_vars('_view._comment', array());
		
		$sql = "SELECT c.*, u.user_id, u.user_name, u.user_color, u.user_email
					FROM " . COMMENT . " c
						LEFT JOIN " . USERS . " u ON c.poster_id = u.user_id
					WHERE c.type = " . READ_NEWS . " AND c.type_id = $data
				ORDER BY c.time_create DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp_comments = $db->sql_fetchrowset($result);
	#	$tmp_comments = _cached($sql, 'data_news_comments');
	
		$comments = $tmp_comments;
	
	#	foreach ( $tmp_comments as $key => $row )
	#	{
	#		if ( $row['type_id'] == $data )
	#		{
	#			$comments[] = $row;
	#		}
	#	}
		
		if ( !$comments )
		{
			$template->assign_block_vars('_view._comment._entry_empty', array());
			
			$last_entry = array('poster_ip' => '', 'time_create' => '');
		}
		else
		{
			$count = count($comments);
			
			$sql = "SELECT read_time FROM " . COMMENT_READ . " WHERE user_id = $user AND type_id = $data AND type = " . READ_NEWS;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$unread = $db->sql_fetchrow($result);
			
			$unreads = ( !$unread ) ? true : false;

			for  ( $i = $start; $i < min($settings['site_comment_per_page'] + $start, $count); $i++ )
			{
				debug($comments[$i]['time_create']);
				
				$css	= ( $i % 2 ) ? 'row1' : 'row2';
				$icon	= ( $userdata['session_logged_in'] ) ? ( $unreads || ( $unread['read_time'] < $comments[$i]['time_create'] ) ) ? $images['icon_minipost_new'] : $images['icon_minipost'] : $images['icon_minipost'];
			
				$poster_name = $comments[$i]['poster_nick'] ? $comments[$i]['poster_nick'] : '<font color="' . $comments[$i]['user_color'] . '">' . $comments[$i]['user_name'] . '</font>';
				$poster_link = $comments[$i]['poster_nick'] ? $userdata['session_logged_in'] ? 'mailto:' . $comments[$i]['poster_email'] : $comments[$i]['poster_nick'] : 'profile.php?mode=view&amp;' . POST_USER . '=' . $comments[$i]['poster_id'];
				
				$s_option = '';
				
				$template->assign_block_vars('_view._comment._row', array(
					'CSS'	=> $css,
					'ICON'	=> $icon,

					'DATE'		=> create_shortdate($userdata['user_dateformat'], $comments[$i]['time_create'], $userdata['user_timezone']),
					'POSTER'	=> "<a href=\"$poster_link\">$poster_name</a>",
					'MESSAGE'	=> $comments[$i]['poster_text'],

					'OPTIONS'	=> $s_option,
				));
				
			#	( $unread['read_time'] < $comments[$i]['time_create'] ) ? sql(COMMENT_READ, 'create', array('type' => READ_NEWS, 'type_id' => $data, 'user_id' => $user, 'read_time' => $comments[$i]['time_create'])) : sql(COMMENT_READ, 'update', array('read_time' => $comments[$i]['time_create']), array('type', 'type_id', 'user_id'), array(READ_NEWS, $data, $user));
			}
		
			$current_page = ( !count($comments) ) ? 1 : ceil( count($comments) / $settings['site_comment_per_page'] );
			
			$template->assign_vars(array(
				'L_GOTO_PAGE'	=> $lang['Goto_page'],
				'PAGINATION'	=> generate_pagination("$file?$url=$data", count($comments), $settings['site_comment_per_page'], $start),
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['site_comment_per_page'] ) + 1 ), $current_page ),
			));
			
			/* Letzter Kommentareintrag */
			sort($comments);
			$last_entry = array_pop($comments);
		}
		
		/* Kommentare für Gäste */
		if ( $settings['comments_news_guest'] && !$userdata['session_logged_in'] )
		{
			$template->assign_block_vars('_view._comment._guest', array());
		}
		
		if ( request('submit', 1) && ( $last_entry['poster_ip'] != $userdata['session_ip'] || ($last_entry['time_create'] + $settings['spam_comment_match']) < $time ) )
		{
			debug($_POST);
			
			include($root_path . 'includes/functions_post.php');
				
			if ( !$userdata['session_logged_in'] )
			{
				$sql = "SELECT captcha FROM " . CAPTCHA . " WHERE session_id = '" . $userdata['session_id'] . "' AND captcha_id = '" . md5($user_ip) . "'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cp = $db->sql_fetchrow($result);
				$captcha = $cp['captcha'];
			
			#	$captcha = $_SESSION['captcha'];
				
				$poster_nick	= request('poster_nick', 2) ? request('poster_nick', 2) : '';
				$poster_mail	= request('poster_mail', 2) ? request('poster_mail', 2) : '';
				$poster_hp		= request('poster_hp', 2) ? request('poster_hp', 2) : '';
				$poster_captcha	= request('captcha', 3) ? request('captcha', 3) : '';
				
				$error .= !$poster_nick ? ( $error ? '<br />' : '' ) . $lang['msg_empty_nick'] : '';
				$error .= !$poster_mail ? ( $error ? '<br />' : '' ) . $lang['msg_empty_mail'] : '';
				$error .= ( $poster_captcha != $captcha  ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_captcha'] : '';
			}
			else
			{
				$poster_nick = $poster_mail = $poster_hp = $poster_captcha = '';
			}
			
			$poster_msg = request('poster_msg', 3) ? request('poster_msg', 3) : '';
			
			$error .= !$poster_msg ? ( $error ? '<br />' : '' ) . $lang['msg_empty_text'] : '';
			
			$template->assign_vars(array(
				'POSTER_NICK'	=> $poster_nick,
				'POSTER_MAIL'	=> $poster_mail,
				'POSTER_HP'		=> $poster_hp,
				'POSTER_MSG'	=> $poster_msg,
			));
			
			if ( !$error )
			{
				$oCache->deleteCache('data_comments_news');
				
			#	$sql = sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_NEWS, $data, $user));
				$msg = $lang['add_comment'] . sprintf($lang['click_return_match'],  '<a href="' . check_sid("$file?$url=$data") . '">', '</a>');
				
				msg_add('news', $data, $user, $poster_msg, $poster_nick, $poster_mail, $poster_hp);
				message(GENERAL_MESSAGE, $msg);
			}
			else
			{
				$template->assign_vars(array('ERROR_MESSAGE' => $error));
				$template->assign_var_from_handle('ERROR_BOX', 'error');
			}
		}
		
		$template->assign_var_from_handle('COMMENTS', 'comments');
	}
	
	if ( $userdata['session_logged_in'] )
	{
	#	$sql = "SELECT read_time FROM " . COMMENT_READ . " WHERE user_id = $user AND type_id = $data AND type = " . READ_NEWS;
	#	if ( !($result = $db->sql_query($sql)) )
	#	{
	#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	#	}
	#	$unread = $db->sql_fetchrow($result);
	
		debug($unread);
		
		( !$unread ) ? sql(COMMENT_READ, 'create', array('user_id' => $user, 'type_id' => $data, 'type' => READ_NEWS, 'read_time' => $time)) : sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_NEWS, $data, $user));
	}
		
#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"view\" />";
#	$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data\" />";
	
	$template->assign_vars(array(
		'L_MAIN'		=> $page_title,
	#	'L_USERNAME'		=> $lang['user_name'],
	#	'L_STATUS'			=> $lang['status'],
	#	'L_STATUS_YES'		=> $lang['status_yes'],
	#	'L_STATUS_NO'		=> $lang['status_no'],
	#	'L_STATUS_REPLACE'	=> $lang['status_replace'],
	#	'L_SET_STATUS'		=> $lang['set_status'],
	#	'CLAN'	=> $clan,
	#	'RIVAL'	=> $rival,
	#	'RIVAL_NAME'		=> $detail['match_rival_name'],
	#	'RIVAL_TAG'			=> $detail['match_rival_tag'],
	#	'U_MATCH_RIVAL'		=> $detail['match_rival_url'],
	#	'MATCH_RIVAL'		=> $detail['match_rival_url'],
	#	'MATCH_CATEGORIE'		=> $match_cat,
	#	'MATCH_TYPE'			=> $match_type,
	#	'MATCH_LEAGUE_INFO'		=> $match_league,
	#	'SERVER'				=> ($detail['server']) ? '<a href="hlsw://' . $detail['server'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
	#	'SERVER_PW'				=> ( $userdata['user_level'] >= TRIAL ) ? $detail['server_pw'] : '',
	#	'HLTV'					=> ($detail['server']) ? '<a href="hlsw://' . $detail['server_hltv'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
	#	'HLTV_PW'				=> ( $userdata['user_level'] >= TRIAL ) ? $detail['server_hltv_pw'] : '',
	#	'DETAILS_COMMENT'		=> $detail['details_comment'],
	#	'MATCH_MAIN'			=> '<a href="' . check_sid('match.php') . '">Übersicht</a>',
	
		'TITLE'	=> $page_title,
		'TEXT'	=> html_entity_decode($view['news_text'], ENT_QUOTES),
	
		'OVERVIEW'		=> '<a href="' . check_sid($file) . '">' . $lang['header_overview'] . '</a>',
		
		'S_FIELDS'		=> $fields,
		'S_ACTION'		=> check_sid("$file?$url=$data"),
	));
}
else if ( $mode == 'archiv' )
{
    $template->assign_block_vars('_archiv', array());

	$sql = "SELECT n.*, nc.cat_name, nc.cat_image, u.user_name, u.user_color, m.*, t.team_name, t.team_logo, g.game_image, g.game_size
				FROM " . NEWS . " n
					LEFT JOIN " . USERS . " u ON n.user_id = u.user_id
					LEFT JOIN " . MATCH . " m ON n.match_id = m.match_id
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . NEWSCAT . " nc ON n.news_cat = nc.cat_id
				WHERE n.news_time_public < $time AND news_public = 1
			ORDER BY n.news_time_public DESC, n.news_id DESC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$news = $db->sql_fetchrowset($result);
	$news = _cached($sql, 'data_news');

	$page_title = $lang['header_news_archiv'];
	
	main_header();
	
	foreach ( $news as $key => $row )
	{
		if ( $userdata['user_level'] >= TRIAL && $row['news_public'] == 1 )
		{
			$ary[] = $row;
		}
		else if ( $row['news_intern'] == 0 && $row['news_public'] == 1 )
		{
			$ary[] = $row;
		}
	}
	
	$count = count($ary);
	
	if ( !$ary )
	{
		$template->assign_block_vars('_narf._entry_empty', array());
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, $count); $i++ )
		{
			$news_id	= $ary[$i]['news_id'];
			$comments	= $ary[$i]['news_comment'];
			
			$template->assign_block_vars('_archiv._row', array(
				'TITLE'		=> '<a href="' . check_sid("$file?mode=view&amp;$url=$news_id") . '">' . $ary[$i]['news_title'] . '</a>',
				'NC_CAT'	=> $ary[$i]['cat_name'],
				'DATE'		=> create_shortdate($userdata['user_dateformat'], $ary[$i]['news_time_public'], $userdata['user_timezone']),
				'COMMENT'	=> ( $comments != 0 ) ? (( $comments == 1 ) ? $lang['sprintf_comment'] : sprintf($lang['sprintf_comment'], $comments)) : $lang['sprintf_comment_no'],
				'AUTHOR'	=> '<a href="' . check_sid('profile.php?mode=view&amp;' . POST_USER . '=' . $ary[$i]['user_id']) . '" style="color:' . $ary[$i]['user_color'] . '"><b>' . $ary[$i]['user_name'] . '</b></a>',
			));
		}
	}
	
	$template->assign_vars(array(
		'L_MAIN'		=> $page_title,
	
		'OVERVIEW'		=> '<a href="' . check_sid($file) . '">' . $lang['header_overview'] . '</a>',
		
		'S_FIELDS'		=> $fields,
		'S_ACTION'		=> check_sid($file),
	));
}
else
{
	$template->assign_block_vars('_list', array());
	
	$sql = "SELECT n.*, nc.cat_name, nc.cat_image, u.user_name, u.user_color, m.*, t.team_name, t.team_logo, g.game_image, g.game_size
				FROM " . NEWS . " n
					LEFT JOIN " . USERS . " u ON n.user_id = u.user_id
					LEFT JOIN " . MATCH . " m ON n.match_id = m.match_id
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . NEWSCAT . " nc ON n.news_cat = nc.cat_id
				WHERE n.news_time_public < " . time() . " AND news_public = 1
			ORDER BY n.news_time_public DESC, n.news_id DESC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$news = $db->sql_fetchrowset($result);
	$news = _cached($sql, 'data_news');

	$page_title = $lang['news'];
	
	main_header();
	
	foreach ( $news as $key => $row )
	{
		if ( $userdata['user_level'] >= TRIAL && $row['news_public'] == 1 )
		{
			$ary[] = $row;
		}
		else if ( $row['news_intern'] == 0 && $row['news_public'] == 1 )
		{
			$ary[] = $row;
		}
	}
	
	if ( !$ary )
	{
		$template->assign_block_vars('_list._entry_empty', array());
	}
	else
	{
		for ( $i = $start; $i < min($settings['news_limit'] + $start, count($ary)); $i++ )
		{
			$news_id	= $ary[$i]['news_id'];
			$comments	= $ary[$i]['news_comment'];
			
			$template->assign_block_vars('_list._row', array(
				'TITLE'		=> $ary[$i]['news_title'],
				'TEXT'		=> html_entity_decode($ary[$i]['news_text'], ENT_QUOTES),
				'DATE'		=> create_shortdate($userdata['user_dateformat'], $ary[$i]['news_time_public'], $userdata['user_timezone']),
				'COMMENTS'	=> ( $comments != 0 ) ? (( $comments == 1 ) ? $lang['sprintf_comment'] : sprintf($lang['sprintf_comment'], $comments)) : $lang['sprintf_comment_no'],
				'AUTHOR'	=> '<a href="' . check_sid('profile.php?mode=view&amp;' . POST_USER . '=' . $ary[$i]['user_id']) . '" style="color:' . $ary[$i]['user_color'] . '"><b>' . $ary[$i]['user_name'] . '</b></a>',
				
				'NC_TITLE'	=> ( $ary[$i]['cat_name'] ) ? $ary[$i]['cat_name'] : '',
				'NC_IMAGE'	=> ( $ary[$i]['cat_image'] ) ? $root_path . $settings['path_newscat'] . '/' . $ary[$i]['cat_image'] : '',
				
				'U_NEWS'	=> check_sid("$file?mode=view&amp;$url=$news_id"),
				
			));
			
			if ( unserialize($ary[$i]['news_url']) )
			{
				$_ary	= '';
				$urls	= unserialize($ary[$i]['news_url']);
				$links	= unserialize($ary[$i]['news_link']);
				
				foreach ( $urls as $key => $value )
				{
					$_ary[] .= '<a href="' . $value . '" target="_new">' . $links[$key] . '</a>';
				}
				
				$urls = implode(', ', $_ary);
				
				$template->assign_block_vars('_list._row._urls', array(
					'LINK'	=> ( count($_ary) > 1 ) ? $lang['news_info_urls'] : $lang['news_info_url'],
					'URLS'	=> $urls,
				));
			}

			if ( isset($ary[$i]['match_id']) )
			{
				if ( file_exists($root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_match.php') )
				{
					include($root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_match.php');
				}
				
				$result_clan = $result_rival = 0;
				
				$sql = "SELECT mm.map_points_home, mm.map_points_rival, m.map_name
							FROM " . MATCH_MAPS . " mm
								LEFT JOIN " . MAPS . " m ON m.map_id = mm.map_name
							WHERE match_id = " . $ary[$i]['match_id'] . " ORDER BY mm.map_order ASC";
			#	if ( !($result = $db->sql_query($sql)) )
			#	{
			#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			#	}
			#	$maps[$i] = $db->sql_fetchrowset($result);
				$maps[$i] = _cached($sql, 'dsp_news_maps_' . $ary[$i]['match_id']);
				
				if ( isset($maps[$i]) )
				{
					for ( $j = 0; $j < count($maps[$i]); $j++ )
					{
						$result_maps[]	= $maps[$i][$j]['map_name'];
						$result_clan	+= $maps[$i][$j]['map_points_home'];
						$result_rival	+= $maps[$i][$j]['map_points_rival'];
					}
				}
				
				$css = isset($result_clan) && isset($result_rival) ? ( $result_clan != $result_rival ) ? ( $result_clan > $result_rival ) ? WIN : LOSE : DRAW : '';
				$map = isset($result_maps) ? implode(', ', $result_maps) : '';
				
				$template->assign_block_vars('_list._row._match', array(
					'GAME'			=> display_gameicon($ary[$i]['game_size'], $ary[$i]['game_image']),
					'TEAM'			=> $ary[$i]['team_name'],
					'TEAM_LOGO'		=> $ary[$i]['team_logo'] ? '<img src="' . $root_path . $settings['path_teams'] . '/' . $ary[$i]['team_logo'] . '" alt="" class="icon" >' : '',
					'RIVAL'			=> $ary[$i]['match_rival_url'] ? '<a href="' . $ary[$i]['match_rival_url'] . '">' . $ary[$i]['match_rival_name'] . '</a>' : $ary[$i]['match_rival_name'],
					'RIVAL_LOGO'	=> $ary[$i]['match_rival_logo'] ? '<img src="' . $ary[$i]['match_rival_logo'] . '" alt="" class="icon" >' : '',
					
					'WAR'			=> $lang['match_war'][$ary[$i]['match_war']],
					'TYPE'			=> $lang['match_type'][$ary[$i]['match_type']],
					'LEAGUE'		=> ( $ary[$i]['match_league'] != 'league_nope' ) ? '<a href="' . check_sid($lang['match_league'][$ary[$i]['match_league']]['url']) . '">' . $lang['match_league'][$ary[$i]['match_league']]['name'] . '</a>' : '',
					
					'MAPS'			=> $map,
					
					'CSS'			=> $css,
					'RESULT'		=> sprintf($lang['sprintf_subnavi_match_result'], $result_clan, $result_rival)
				));
			}
		}
		
		if ( $settings['news_browse'] )
		{
			$current_page = ( !count($ary) ) ? 1 : ceil( count($ary) / $settings['news_limit'] );
				
			$template->assign_vars(array(
				'PAGE_PAGING' => generate_pagination('news.php', count($ary), $settings['news_limit'], $start),
				'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['news_limit'] ) + 1 ), $current_page ), 
			));
		}
		
		$template->assign_vars(array(
			'L_MAIN' => $page_title,
		));
	}
}

$template->pparse('body');

main_footer();

?>