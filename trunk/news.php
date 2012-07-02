<?php

define('IN_CMS', true);

$root_path = './';

include("{$root_path}common.php");

$userdata = session_pagestart($user_ip, PAGE_NEWS);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_NEWS;
$url	= POST_NEWS;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, INT);
$mode	= request('mode', TXT);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_news.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'info_error.tpl',
));

/**
 * Truncates a string to a certain length.
 * 
 * @param string $text
 * @param int $limit
 * @param string $ending
 * @return string
 */
function truncate($text, $limit = 25, $ending = '...')
{
	if ( strlen($text) > $limit)
	{
		$text = strip_tags($text);
		$text = substr($text, 0, $limit);
		$text = substr($text, 0, -(strlen(strrchr($text, ' '))));
		$text = $text . $ending;
	}
	
	return $text;
}

function truncate2($text, $limit = 25, $ending = '...')
{
	/*
	$text = explode(" ",$long_text);// each individual word will be in the array
$number_of_words = 30;// however many words you want to display
for($i=0;$i<$number_of_words;++$i){
echo $text[$i];
} 

	if (strlen($text) > $limit) {
		$text = strip_tags($text);
		$text = substr($text, 0, $limit);
		$text = substr($text, 0, -(strlen(strrchr($text, ' '))));
		$text = $text . $ending;
	}
	*/
	
	$_ary = '';
	$_txt = explode(" ", $text);
	
	if ( count($_txt) > $limit+10 )
	{	
		for ( $i = 0; $i < $limit; $i++ )
		{
			$_ary[] = $_txt[$i];
		}
		
		$return = implode(" ", $_ary);
		$return .= $ending;
	}
	else
	{
		$return = $text;
	}
		
	return $return;
}

$sql = "SELECT n.*, nc.cat_name, nc.cat_image, u.user_name, u.user_color, m.*, t.team_name, t.team_logo, g.game_image
			FROM " . NEWS . " n
				LEFT JOIN " . USERS . " u ON n.user_id = u.user_id
				LEFT JOIN " . MATCH . " m ON n.news_match = m.match_id
				LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
				LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				LEFT JOIN " . NEWS_CAT . " nc ON n.news_cat = nc.cat_id
			WHERE n.news_time_public < " . time() . " AND news_public = 1
		ORDER BY n.news_time_public DESC, n.news_id DESC";
#$tmp = _cached($sql, 'data_news');
$tmp = _cached($sql, 'sql_news');

if ( $data && $tmp )
{
	$template->assign_block_vars('view', array());
	
	foreach ( $tmp as $key => $row )
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
	
	/*
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] )
	{
		$template->assign_block_vars('view._update', array(
			'UPDATE' => "<a href=\"" . check_sid("admin/admin_news.php?mode=_update&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . sprintf($lang['update_sprintf'], $lang['header_news']) . "</a>",
		));
	}
	*/
	
	if ( $settings['comments']['news'] && $view['news_comments'] )
	{
		$template->assign_block_vars('view._comment', array());
		
		$sql = "SELECT c.*, u.user_id, u.user_name, u.user_color, u.user_email
					FROM " . COMMENT . " c
						LEFT JOIN " . USERS . " u ON c.poster_id = u.user_id
					WHERE c.type_id = $data AND c.type = " . READ_NEWS . "
				ORDER BY c.time_create DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$comments = $db->sql_fetchrowset($result);
		
		if ( !$comments )
		{
			$template->assign_block_vars('view._comment._empty', array());
			
			$last_entry = array('poster_ip' => '', 'time_create' => '');
		}
		else
		{
			$cnt = count($comments);
			
			$sql = "SELECT read_time FROM " . COMMENT_READ . " WHERE user_id = $user AND type_id = $data AND type = " . READ_NEWS;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$unread = $db->sql_fetchrow($result);
			
			$unreads = ( !$unread ) ? true : false;

			for ( $i = $start; $i < min($settings['ppec'] + $start, $cnt); $i++ )
			{
				$icon = ( $userdata['session_logged_in'] ) ? ( $unreads || ( $unread['read_time'] < $comments[$i]['time_create'] ) ) ? $images['icon_minipost_new'] : $images['icon_minipost'] : $images['icon_minipost'];
				$name = $comments[$i]['poster_nick'] ? $comments[$i]['poster_nick'] : '<font color="' . $comments[$i]['user_color'] . '">' . $comments[$i]['user_name'] . '</font>';
				$link = $comments[$i]['poster_nick'] ? $userdata['session_logged_in'] ? 'mailto:' . $comments[$i]['poster_email'] : $comments[$i]['poster_nick'] : 'profile.php?mode=view&amp;' . POST_USER . '=' . $comments[$i]['poster_id'];
				
				$s_option = '';
				
				$template->assign_block_vars('view._comment._row', array(
					'CLASS'	=> ( $i % 2 ) ? $theme['td_class1'] : $theme['td_class2'],
					'ICON'	=> $icon,

					'DATE'		=> create_shortdate($userdata['user_dateformat'], $comments[$i]['time_create'], $userdata['user_timezone']),
					'POSTER'	=> "<a href=\"$link\">$name</a>",
					'MESSAGE'	=> $comments[$i]['poster_text'],

					'OPTIONS'	=> $s_option,
				));
			}
		
			$current_page = $cnt ? ceil($cnt/$settings['ppec']) : 1;
			
			$template->assign_vars(array(
				'PAGE_PAGING' => generate_pagination("$file?$url=$data", $cnt, $settings['ppec'], $start),
				'PAGE_NUMBER' => sprintf($lang['common_page_of'], (floor($start/$settings['ppec'])+1), $current_page),
			));
			
			/* Letzter Kommentareintrag */
			sort($comments);
			$last_entry = array_pop($comments);
		}
		
		/* Kommentare für Gäste */
		if ( $settings['comments']['news_guest'] && !$userdata['session_logged_in'] )
		{
			$template->assign_block_vars('view._comment._guest', array());
		}
		
		if ( request('submit', TXT) && ( $last_entry['poster_ip'] != $userdata['session_ip'] || ($last_entry['time_create'] + $settings['spam_comments']['news']) < $time ) )
		{
			if ( !$userdata['session_logged_in'] )
			{
				$sql = "SELECT captcha FROM " . CAPTCHA . " WHERE session_id = '" . $userdata['session_id'] . "' AND captcha_id = '" . md5($user_ip) . "'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cp = $db->sql_fetchrow($result);
				$captcha = $cp['captcha'];
			
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
				$template->assign_vars(array('META' => "<meta http-equiv=\"refresh\" content=\"3;url=$file?$url=$data\">"));
				
				$msg = $lang['add_comment'] . sprintf($lang['click_return_news'],  '<a href="' . check_sid("$file?$url=$data") . '">', '</a>');
				
				sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_NEWS, $data, $user));
				msg_add(NEWS, $data, $user, $poster_msg, $poster_nick, $poster_mail, $poster_hp);
				message(GENERAL_MESSAGE, $msg);
			}
			else
			{
				$template->assign_vars(array('ERROR_MESSAGE' => $error));
				$template->assign_var_from_handle('ERROR_BOX', 'error');
			}
		}
		
		$template->assign_vars(array(
			'L_COMMENT'		=> $lang['common_comment'],
			'L_SUBMIT'		=> $lang['common_submit'],
			'L_RESET'		=> $lang['common_reset'],
		));
		
		$template->assign_var_from_handle('COMMENTS', 'comments');
	}
	
	if ( $userdata['session_logged_in'] )
	{
		$sql = "SELECT read_time FROM " . COMMENT_READ . " WHERE user_id = $user AND type_id = $data AND type = " . READ_NEWS;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$unread = $db->sql_fetchrow($result);
		
		( $unread ) ? sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_NEWS, $data, $user)) : sql(COMMENT_READ, 'create', array('user_id' => $user, 'type_id' => $data, 'type' => READ_NEWS, 'read_time' => $time));
	}
	
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
    $template->assign_block_vars('archiv', array());
		
	foreach ( $tmp as $key => $row )
	{
		if ( $userdata['user_level'] >= TRIAL && $row['news_public'] == 1 )
		{
			$ids[] = $row['news_id'];
			$ary[] = $row;
		}
		else if ( $row['news_intern'] == 0 && $row['news_public'] == 1 )
		{
			$ids[] = $row['news_id'];
			$ary[] = $row;
		}
	}
	
	$page_title = $lang['header_news_archiv'];
	
	if ( !$ary )
	{
		$template->assign_block_vars('archiv.empty', array());
	}
	else
	{
		$cnt = count($ary);
		
		$sql = "SELECT news_id, count_comment AS count FROM " . NEWS . " WHERE news_id IN (" . implode(', ', $ids) . ")";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$cnt_comment[$row['news_id']] = $row['count'];
		}
		
		for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, $cnt); $i++ )
		{
			$news_id	= $ary[$i]['news_id'];
			$comment	= $cnt_comment[$news_id];
			
			$template->assign_block_vars('archiv.row', array(
				'CLASS'		=> ( $i % 2 ) ? $theme['td_class1'] : $theme['td_class2'],
				
				'TITLE'		=> href('a_txt', $file, array('mode' => 'view', $url => $news_id), $ary[$i]['news_title'], $ary[$i]['news_title']),
				'AUTHOR'	=> href('a_user', 'profile.php', array('mode' => 'view', POST_USER => $ary[$i]['user_id']), $ary[$i]['user_color'], $ary[$i]['user_name']),
				'CAT'		=> $ary[$i]['cat_name'],
				'COMMENTS'	=> ( $comment != 0 ) ? (( $comment == 1 ) ? $lang['sprintf_comment'] : sprintf($lang['sprintf_comments'], $comment)) : $lang['sprintf_comment_no'],
				'DATE'		=> create_shortdate($userdata['user_dateformat'], $ary[$i]['news_time_public'], $userdata['user_timezone']),
				
			#	'TITLE'		=> '<a href="' . check_sid("$file?mode=view&amp;$url=$news_id") . '">' . $ary[$i]['news_title'] . '</a>',	
			#	'AUTHOR'	=> '<a href="' . check_sid('profile.php?mode=view&amp;' . POST_USER . '=' . $ary[$i]['user_id']) . '" style="color:' . $ary[$i]['user_color'] . '"><b>' . $ary[$i]['user_name'] . '</b></a>',
			));
		}
	}
	
	$template->assign_vars(array(
		'L_MAIN'	=> $page_title,
		
		'OVERVIEW'	=> href('a_txt', $file, false, $ary[$i]['header_overview'], $ary[$i]['header_overview']),
		
		'S_FIELDS'	=> $fields,
		'S_ACTION'	=> check_sid($file),
	));
}
else
{
	$template->assign_block_vars('list', array());
	
	foreach ( $tmp as $row )
	{
		if ( $userdata['user_level'] >= TRIAL && $row['news_public'] == 1 )
		{
			$ids[] = $row['news_id'];
			$ary[] = $row;
		}
		else if ( $row['news_intern'] == 0 && $row['news_public'] == 1 )
		{
			$ids[] = $row['news_id'];
			$ary[] = $row;
		}
	}
	
	$page_title = $lang['news'];
	
	main_header($page_title);
	
	if ( !$ary )
	{
		$template->assign_block_vars('list.empty', array());
	}
	else
	{
		$sql = "SELECT news_id, count_comment AS count FROM " . NEWS . " WHERE news_id IN (" . implode(', ', $ids) . ")";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$cnt_comment[$row['news_id']] = $row['count'];
		}
		
		$cnt_ary = count($ary);
		
		for ( $i = $start; $i < min($settings['news']['limit'] + $start, $cnt_ary); $i++ )
		{
			$news_id	= $ary[$i]['news_id'];
			$comments	= $cnt_comment[$news_id];
			
			$txt_tmp = html_entity_decode($ary[$i]['news_text'], ENT_QUOTES);
			
			$template->assign_block_vars('list.row', array(
				'TITLE'		=> $ary[$i]['news_title'],
				'TEXT'		=> truncate2($txt_tmp, 100, '...'),
				'DATE'		=> create_shortdate($userdata['user_dateformat'], $ary[$i]['news_time_public'], $userdata['user_timezone']),
				'COMMENTS'	=> ( $comments != 0 ) ? ( $comments == 1 ) ? $lang['sprintf_comment'] : sprintf($lang['sprintf_comments'], $comments) : $lang['sprintf_comment_no'],
				'AUTHOR'	=> '<a href="' . check_sid('profile.php?mode=view&amp;' . POST_USER . '=' . $ary[$i]['user_id']) . '" style="color:' . $ary[$i]['user_color'] . '"><b>' . $ary[$i]['user_name'] . '</b></a>',
				
				'NC_TITLE'	=> ( $ary[$i]['cat_name'] ) ? $ary[$i]['cat_name'] : '',
				'NC_IMAGE'	=> ( $ary[$i]['cat_image'] ) ? $root_path . $settings['path_newscat'] . '/' . $ary[$i]['cat_image'] : '',
				
				'U_NEWS'	=> check_sid("$file?$url=$news_id"),
				
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
				
				$template->assign_block_vars('list.row.urls', array(
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
				
				$template->assign_block_vars('list.row.match', array(
					'GAME'			=> display_gameicon($ary[$i]['game_image']),
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
		
		if ( $settings['news']['browse'] )
		{
			$current_page = ( !count($ary) ) ? 1 : ceil( count($ary) / $settings['news']['limit'] );
				
			$template->assign_vars(array(
				'PAGE_PAGING' => generate_pagination("$file?", count($ary), $settings['news']['limit'], $start),
				'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['news']['limit'] ) + 1 ), $current_page ), 
			));
		}
		
		$template->assign_vars(array(
			'L_MAIN' => $page_title,
		));
	}
}

main_header($page_title);

$template->pparse('body');

main_footer();

?>