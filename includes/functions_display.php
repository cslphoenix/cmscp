<?php

#	Cache Gültigkeit
if ( defined('CACHE') )
{
	function display_cache($name, $type = '')
	{
		global $oCache, $userdata, $lang;
		
		$time	= $oCache -> readCacheTime($name);
		$type	= ( $type == '1' ) ? $lang['cache_valid'] : $lang['cache_duration'] ;
		$msg	= sprintf($type, create_date($userdata['user_dateformat'], $time, $userdata['user_timezone']));
		$str	= "<img src=\"images/icon-rss.png\" title=\"$msg\" alt=\"$msg\">";
		
		return $str;
	}
}


#	Teambilder
function display_gameicon($game_size, $game_image)
{
	global $root_path, $settings;
	
	$image	= '<img src="' . $root_path . $settings['path_games'] . '/' . $game_image . '" alt="' . $game_image . '" title="' . $game_image . '" width="' . $game_size . '" height="' . $game_size . '" >';

	return $image;
}


#	Navigation
function display_navi()
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	$sql = ( $userdata['session_logged_in'] ) ? 'SELECT * FROM ' . NAVIGATION . ' WHERE navi_show = 1' : 'SELECT * FROM ' . NAVIGATION . ' WHERE navi_show = 1 AND navi_intern != 1 AND navi_type != ' . NAVI_USER;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$template->assign_block_vars('_navi_main', array());
	$template->assign_block_vars('_navi_clan', array());
	$template->assign_block_vars('_navi_comm', array());
	$template->assign_block_vars('_navi_misc', array());
	$template->assign_block_vars('_navi_user', array());
	
	while ( $navi = $db->sql_fetchrow($result) )
	{
		switch ( $navi['navi_type'] )
		{
			case NAVI_MAIN:	$row_type = '_navi_main._navi_main_row';	break;
			case NAVI_CLAN:	$row_type = '_navi_clan._navi_clan_row';	break;
			case NAVI_COM:	$row_type = '_navi_comm._navi_comm_row';	break;
			case NAVI_MISC:	$row_type = '_navi_misc._navi_misc_row';	break;
			case NAVI_USER:	$row_type = '_navi_user._navi_user_row';	break;
		}
		
		$navi_lang = ( $navi['navi_lang'] ) ? $lang[$navi['navi_name']] : $navi['navi_name'];
		
		$template->assign_block_vars($row_type, array(
			'NAVI_NAME'		=> $navi_lang,
			'NAVI_URL'		=> $navi['navi_url'],
			'NAVI_TARGET'	=> ( $navi['navi_target'] == '0' ) ? '_self' : '_blank',
		));
	}
}


#	News
function display_navi_news()
{
	global $config, $db, $lang, $root_path, $settings, $template, $userdata;
	
	$sql = 'SELECT n.*, t.team_name, g.game_image, g.game_size
				FROM ' . NEWS . ' n
					LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
					LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
					LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
				WHERE n.news_time_public < ' . time() . '
			ORDER BY n.news_time_public DESC, n.news_id DESC LIMIT 0,' . $settings['subnavi_news_limit'];
	$tmp = _cached($sql, 'display_navi_news');
	
	if ( $tmp )
	{
		$news_member = $news_guest = array();
	
		foreach ( $tmp as $news => $row )
		{
			if ( $userdata['user_level'] >= TRIAL )
			{
				$news_member[] = $row;
			}
			else if ( $row['news_intern'] == '0' )
			{
				$news_guest[] = $row;
			}
		}
		
		$tmp = ( $userdata['user_level'] >= TRIAL ) ? $news_member : $news_guest;
		
		if ( $tmp )
		{
			for ( $i = 0; $i < count($tmp); $i++ )
			{
				$class = ($i % 2) ? 'row1r' : 'row2r';
							
				if ( $config['time_today'] < $tmp[$i]['news_time_public'] )
				{ 
					$news_date = sprintf($lang['today_at'], create_date($config['default_timeformat'], $tmp[$i]['news_time_public'], $userdata['user_timezone'])); 
				}
				else if ( $config['time_yesterday'] < $tmp[$i]['news_time_public'] )
				{ 
					$news_date = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $tmp[$i]['news_time_public'], $userdata['user_timezone'])); 
				}
				
				$template->assign_block_vars('_news_subnavi_row', array(
					'CLASS' 		=> $class,
					'NEWS_TITLE'	=> cut_string($tmp[$i]['news_title'], $settings['subnavi_news_length']),
					'NEWS_GAME'		=> ( $tmp[$i]['match_id'] ) ? display_gameicon($tmp[$i]['game_size'], $tmp[$i]['game_image']) : '',
					'U_DETAILS'		=> append_sid('news.php?mode=view&amp;' . POST_NEWS_URL . '=' . $tmp[$i]['news_id']),
				));
			}
		}
	}
	else { $template->assign_block_vars('_news_subnavi_empty', array()); }
	
	return;
}


#	Match
function display_navi_match()
{
	global $config, $db, $lang, $root_path, $settings, $template, $userdata;
	
	$sql = "SELECT
				m.match_id, m.match_date, m.match_rival, m.match_public,
				md.details_mapa_clan, md.details_mapb_clan, md.details_mapc_clan, md.details_mapd_clan, md.details_mapa_rival, md.details_mapb_rival, md.details_mapc_rival, md.details_mapd_rival,
				t.team_name,
				g.game_image, g.game_size
				FROM " . MATCH . " m
					LEFT JOIN " . MATCH_DETAILS . " md ON m.match_id = md.match_id
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE m.match_date < " . time() . "
			ORDER BY m.match_date ASC LIMIT 0," . $settings['subnavi_match_limit'];
	$tmp = _cached($sql, 'display_navi_match');
	
	if ( $tmp )
	{
		$navi_match_last = array();
			
		foreach ( $tmp as $navi_match => $row )
		{
			if ( $userdata['user_level'] >= TRIAL )
			{
				if ( $row['match_date'] < time() )
				{
					$navi_match_last[] = $row;
				}
			}
			else if ( $row['match_public'] == '1' )
			{
				if ( $row['match_date'] < time() )
				{
					$navi_match_last[] = $row;
				}
			}
		}
		
		if ( $navi_match_last )
		{
			for ( $i = 0; $i < count($navi_match_last); $i++ )
			{
				$class = ($i % 2) ? 'row1r' : 'row2r';
				
				$result_clan	= $navi_match_last[$i]['details_mapa_clan'] + $navi_match_last[$i]['details_mapb_clan'] + $navi_match_last[$i]['details_mapc_clan'] + $navi_match_last[$i]['details_mapd_clan'];
				$result_rival	= $navi_match_last[$i]['details_mapa_rival'] + $navi_match_last[$i]['details_mapb_rival'] + $navi_match_last[$i]['details_mapc_rival'] + $navi_match_last[$i]['details_mapd_rival'];
				
				if ( $result_clan > $result_rival )
				{
					$class_result = 'win';
				}
				else if	( $result_clan < $result_rival )
				{
					$class_result = 'lose';
				}
				else if	( $result_clan = $result_rival )
				{
					$class_result = 'draw';
				}
				else
				{
					$class_result = '';
				}
				
				$match_rival	= cut_string($navi_match_last[$i]['match_rival'], $settings['subnavi_match_length']);
				$match_name		= ( $navi_match_last[$i]['match_public'] ) ? sprintf($lang['sprintf_subnavi_match'], $match_rival) : sprintf($lang['sprintf_subnavi_match_i'], $match_rival);
				
				$template->assign_block_vars('_match_subnavi_row', array(
					'CLASS' 		=> $class,
					'CLASS_RESULT'	=> $class_result,
					'MATCH_GAME'	=> display_gameicon($navi_match_last[$i]['game_size'], $navi_match_last[$i]['game_image']),
					'MATCH_NAME'	=> $match_name,
					'MATCH_RESULT'	=> sprintf($lang['sprintf_subnavi_match_result'], $result_clan, $result_rival),
					'U_DETAILS'		=> append_sid('match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $navi_match_last[$i]['match_id']),
				));
			}
		}
	}
	else { $template->assign_block_vars('_match_subnavi_empty', array()); }
	
	return;
}


#	Neuste Mitglieder
function display_navi_newusers()
{
	global $db, $lang, $root_path, $settings, $template, $userdata;
	
	if ( $settings['subnavi_newusers'] )
	{
		$template->assign_block_vars('_subnavi_newusers', array());
		
		$sql = "SELECT user_id, username, user_color
					FROM " . USERS . "
					WHERE user_id != -1 AND user_active = 1
				ORDER BY user_regdate
					LIMIT 0, " . $settings['subnavi_newusers_limit'];
#		if ( !($result = $db->sql_query($sql)) )
#		{
#			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#		}
#		$users = $db->sql_fetchrowset($result);
		$users = _cached($sql, 'display_subnavi_newusers', 0, $settings['subnavi_newusers_cache']);
		
		for ( $i = 0; $i < count($users); $i++ )
		{
			$template->assign_block_vars('_subnavi_newusers._user_row', array(
				'L_USERNAME'	=> '<b>' . cut_string($users[$i]['username'], $settings['subnavi_newusers_length']) . '</b>',
				'U_USERNAME'	=> append_sid('profile.php?mode=view&amp;' . POST_USER_URL . '=' . $users[$i]['user_id']),
				'C_USERNAME'	=> 'style="color:' . $users[$i]['user_color'] . '"',
			));
		}
		
		$template->assign_vars(array(
			'NEW_USERS_CACHE'	=> ( defined('CACHE') ) ? display_cache('display_subnavi_newusers', 1) : '',									 
			'L_NEW_USERS'		=> sprintf($lang['newest_users'], $settings['subnavi_newusers_limit']),
		));
		
		return;
	}
}


#	Teans
function display_navi_teams()
{
	global $db, $lang, $root_path, $settings, $template, $userdata;
	
	if ( $settings['subnavi_teams'] )
	{
		$template->assign_block_vars('_subnavi_teams', array());
		
		$sql = "SELECT t.team_id, t.team_name, t.team_fight, g.game_size, g.game_image
					FROM " . TEAMS . " t, " . GAMES . " g
					WHERE t.team_game = g.game_id AND t.team_navi = 1
				ORDER BY t.team_order
					LIMIT 0, " . $settings['subnavi_teams_limit'];
#		if ( !($result = $db->sql_query($sql)) )
#		{
#			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#		}
#		$teams = $db->sql_fetchrowset($result);
		$teams = _cached($sql, 'display_subnavi_teams');
		
		if ( $teams )
		{
			for ( $i = 0; $i < count($teams); $i++ )
			{
				$template->assign_block_vars('_subnavi_teams._teams_row', array(
					'I_TEAM'	=> display_gameicon($teams[$i]['game_size'], $teams[$i]['game_image']),
					'L_TEAM'	=> cut_string($teams[$i]['team_name'], $settings['subnavi_teams_length']),
					'U_TEAM'	=> append_sid("teams.php?" . POST_TEAMS_URL . "=" . $teams[$i]['team_id']),
				));
			}
		}
		else { $template->assign_block_vars('_teams_subnavi_empty', array()); }
	}
	
	return;
}


#	Network: Link/Partner/Sponsor
function display_navi_network($type)
{
	global $db, $lang, $root_path, $settings, $template, $userdata;
	
	$sql = "SELECT network_name, network_url, network_type, network_image FROM " . NETWORK . " WHERE network_view = 1 ORDER BY network_order";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$network_data = $db->sql_fetchrowset($result);
	$network_data = _cached($sql, 'display_subnavi_network');
	
	if ( $settings['subnavi_' . $type] )
	{
		$template->assign_block_vars('_subnavi_' . $type, array());
		
		$typ = ( $type != 'links' ) ? ( $type == 'partner' ) ? NETWORK_PARTNER : NETWORK_SPONSOR : NETWORK_LINK;
		
		foreach ( $network_data as $network )
		{
			if ( $network['network_type'] == $typ )
			{
				$template->assign_block_vars('_subnavi_' . $type . '._' . $type . '_row', array(
					'L_URL'	=> $network['network_name'],
					'U_URL'	=> $network['network_url'],
				));
			}
		}
	}
	
	return;
}


#	Minikalender
function display_navi_minical()
{
	global $db, $lang, $config, $oCache, $root_path, $settings, $template, $userdata;
	
	if ( $settings['subnavi_minical'] )
	{
		$template->assign_block_vars('_subnavi_minical', array());
	}
	
	$tag			= date("d", time());	//	Heutiger Tag: z. B. "1"
	$tag_der_woche	= date("w");			//	Welcher Tag in der Woch: z. B. "0 / Sonntag"
	$tage_im_monat	= date("t");			//	Anzahl der Tage im Monat: z. B. "31"
	$monat			= date("m", time());	//	Heutiger Monat
	$jahr			= date("Y", time());	//	Heutiges Jahr
	$erster			= date("w", mktime(0, 0, 0, $monat, 1, $jahr));		//	Der erste Tag im Monat: z. B. "5 / Freitag"
	$arr_woche_kurz	= array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');	//	Wochentage gekürzt
	
	$monate = array(
		'01'	=> $lang['datetime']['month_01'],
		'02'	=> $lang['datetime']['month_02'],
		'03'	=> $lang['datetime']['month_03'],
		'04'	=> $lang['datetime']['month_04'],
		'05'	=> $lang['datetime']['month_05'],
		'06'	=> $lang['datetime']['month_06'],
		'07'	=> $lang['datetime']['month_07'],
		'08'	=> $lang['datetime']['month_08'],
		'09'	=> $lang['datetime']['month_09'],
		'10'	=> $lang['datetime']['month_10'],
		'11'	=> $lang['datetime']['month_11'],
		'12'	=> $lang['datetime']['month_12'],
	);
	
	$month = $monate[$monat];
	
	if ( $userdata['user_level'] >= TRIAL )
	{
		if ( defined('CACHE') )
		{
			$sCacheName = 'subnavi_calendar_' . $monat . '_member';
	
			if ( ( $monat_data = $oCache -> readCache($sCacheName)) === false )
			{
				for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
				{
					$i = ( $i < 10 ) ? '0' . $i : $i;
					
					$sql = "SELECT username, user_birthday, user_color FROM " . USERS . " WHERE MONTH(user_birthday) = $monat AND DAYOFMONTH(user_birthday) = $i";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$day_rows_b = $db->sql_fetchrowset($result);
					
					$sql = 'SELECT event_date, event_duration, event_title, event_level FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$day_rows_e = $db->sql_fetchrowset($result);
					
				#	$sql = 'SELECT match_rival, match_date FROM ' . MATCH . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
					$sql = "SELECT m.match_id, m.match_rival, m.match_date, g.game_image, g.game_size, t.team_name
								FROM " . MATCH . " m
									LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
									LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$day_rows_w = $db->sql_fetchrowset($result);
					
					$sql = 'SELECT training_vs, training_date FROM ' . TRAINING . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$day_rows_t = $db->sql_fetchrowset($result);
					
					$monat_data_b[$i] = $day_rows_b;
					$monat_data_e[$i] = $day_rows_e;
					$monat_data_w[$i] = $day_rows_w;
					$monat_data_t[$i] = $day_rows_t;
				}
				
				if ( $i == $tage_im_monat + 1 )
				{
					$monat_data = array_merge(array($monat_data_b), array($monat_data_e), array($monat_data_w), array($monat_data_t));
					$oCache -> writeCache($sCacheName, $monat_data);
				}
			}
		}
		else
		{
			for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
			{
				$i = ( $i < 10 ) ? '0' . $i : $i;
				
				$sql = 'SELECT username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_b = $db->sql_fetchrowset($result);
				
				$sql = 'SELECT event_date, event_duration, event_title, event_level FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_e = $db->sql_fetchrowset($result);
				
			#	$sql = 'SELECT match_rival, match_date FROM ' . MATCH . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				$sql = "SELECT m.match_id, m.match_rival, m.match_date, g.game_image, g.game_size, t.team_name
								FROM " . MATCH . " m
									LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
									LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_w = $db->sql_fetchrowset($result);
				
				$sql = 'SELECT training_vs, training_date FROM ' . TRAINING . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_t = $db->sql_fetchrowset($result);
				
				$monat_data_b[$i] = $day_rows_b;
				$monat_data_e[$i] = $day_rows_e;
				$monat_data_w[$i] = $day_rows_w;
				$monat_data_t[$i] = $day_rows_t;
			}
			
			if ( $i == $tage_im_monat + 1 )
			{
				$monat_data = array_merge(array($monat_data_b), array($monat_data_e), array($monat_data_w), array($monat_data_t));
			}
		}
		
		$monat_data_b = array_slice($monat_data, 0, 1);
		$monat_data_e = array_slice($monat_data, 1, 1);
		$monat_data_w = array_slice($monat_data, 2, 1);
		$monat_data_t = array_slice($monat_data, 3, 3);
		
		foreach ( $monat_data_b as $monat_birthday ) {}
		foreach ( $monat_data_e as $monat_events ) {}
		foreach ( $monat_data_w as $monat_matchs ) {}
		foreach ( $monat_data_t as $monat_trainings ) {}
	}
	else
	{
		if ( defined('CACHE') )
		{
			$sCacheName = 'subnavi_calendar_' . $monat . '_guest';
	
			if (( $monat_data = $oCache -> readCache($sCacheName)) === false )
			{
				for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
				{
					$i = ( $i < 10 ) ? '0' . $i : $i;
					
					$sql = 'SELECT username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$day_rows_b = $db->sql_fetchrowset($result);
					
					$sql = 'SELECT event_date, event_duration, event_title, event_level FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$day_rows_e = $db->sql_fetchrowset($result);
					
				#	$sql = 'SELECT * FROM ' . MATCH . " WHERE match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
					$sql = "SELECT m.match_id, m.match_rival, m.match_date, g.game_image, g.game_size, t.team_name
								FROM " . MATCH . " m
									LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
									LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$day_rows_w = $db->sql_fetchrowset($result);
					
					$monat_data_b[$i] = $day_rows_b;
					$monat_data_e[$i] = $day_rows_e;
					$monat_data_w[$i] = $day_rows_w;
				}
				
				if ( $i == $tage_im_monat+1 )
				{
					$monat_data = array_merge(array($monat_data_b), array($monat_data_e), array($monat_data_w));
					$oCache -> writeCache($sCacheName, $monat_data);
				}
			}
		}
		else
		{
			for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
			{
				$i = ( $i < 10 ) ? '0' . $i : $i;
				
				$sql = 'SELECT username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_b = $db->sql_fetchrowset($result);
				
				$sql = 'SELECT event_date, event_duration, event_title, event_level FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_e = $db->sql_fetchrowset($result);
				
			#	$sql = 'SELECT * FROM ' . MATCH . " WHERE match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				$sql = "SELECT m.match_id, m.match_rival, m.match_date, g.game_image, g.game_size, t.team_name
								FROM " . MATCH . " m
									LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
									LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_w = $db->sql_fetchrowset($result);
				
				$monat_data_b[$i] = $day_rows_b;
				$monat_data_e[$i] = $day_rows_e;
				$monat_data_w[$i] = $day_rows_w;
			}
			
			if ( $i == $tage_im_monat + 1 )
			{
				$monat_data = array_merge(array($monat_data_b), array($monat_data_e), array($monat_data_w));
			}
		}
		
		$monat_data_b = array_slice($monat_data, 0, 1);
		$monat_data_e = array_slice($monat_data, 1, 1);
		$monat_data_w = array_slice($monat_data, 2, 1);
		
		foreach ( $monat_data_b as $monat_birthday ) {}
		foreach ( $monat_data_e as $monat_events ) {}
		foreach ( $monat_data_w as $monat_matchs ) {}
	}
	
	// wochenstart
	// 0=Sonntag; 1=Montag; 2=Dienstag; 3=Mittwoch; 4=Donnerstag; 5=Freitag; 6=Samstag
	$ws = 1;
	// "woche beginnt mit" - array verschiebung
	$edmk = $arr_woche_kurz[$erster];
	$wbmk = $arr_woche_kurz;
	for ( $i = 0; $i < $ws; $i++ )
	{
		$wechsel = array_shift($wbmk);
		$wbmk[] = $wechsel;
	}
	$wbmk_wechsel = array_flip($wbmk);
	
	$days = '';
	for ( $i = 0; $i < 7; $i++ )
	{
		$days .= '<td align="center" width="14%"><b>' . $wbmk[$i] . '</b></td>';
	}
	
	// berechnung der monatstabelle
	// zuerst die unbenutzten tage
	$day = '';
	for ( $i = 0; $i < $wbmk_wechsel[$edmk]; $i++ )
	{
		$day .= '<td align="center" width="14%">&nbsp;</td>';
	}
	// ab hier benutzte tage
	$wcs = $wbmk_wechsel[$edmk];
	
	for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
	{
		if ( $i < 10 ) { $i = '0' . $i; }

		if ( $userdata['user_level'] >= TRIAL )
		{
			if ( $i == $tag || is_array($monat_birthday[$i]) || is_array($monat_events[$i]) || is_array($monat_matchs[$i]) || is_array($monat_trainings[$i]) )
			{
				$day_class = '';
				$day_event = '';
				$day_event_num = '0';
				
				if ( $i == $tag )
				{
					$day_class		= 'today';
					$day_event		= '<span><em class="today">' . $lang['cal_today'] . '</em> ';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_birthday[$i]) )
				{
					$list = array();
					
					for ( $k = 0; $k < count($monat_birthday[$i]); $k++ )
					{
						$alter	= 0;
						$gebdt	= explode('-', $monat_birthday[$i][$k]['user_birthday']);
						$gebdt	= $gebdt[0].$gebdt[1].$gebdt[2];
						$now	= date('Ymd', time());
						
						while ($gebdt < $now - 9999)
						{
							$alter++;
							$gebdt = $gebdt + 10000;
						}
		
						$list[] = sprintf($lang['cal_birth'], $monat_birthday[$i][$k]['username'], $alter);
					}
					
					$language		= (count($list) == 1) ? $lang['cal_birthday'] : $lang['cal_birthdays'];
					$list			= implode('<br>', $list);	
					$day_event		.= (empty($day_event)) ? "<span><em class=\"birthday\">$language:</em>$list" : "<br /><em class=\"birthday\">$language</em><br>$list";
					$day_class		= 'birthday';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_events[$i]) )
				{
					$list = array();
					
					for ( $k = 0; $k < count($monat_events[$i]); $k++ )
					{
						if ( $userdata['user_level'] >= $monat_events[$i][$k]['event_level'] )
						{
							$date	= $monat_events[$i][$k]['event_date'];
							$title	= $monat_events[$i][$k]['event_title'];
							$dura	= $monat_events[$i][$k]['event_duration'];
							
							$time_a	= create_date('H:i', $date, $userdata['user_timezone']);
							$time_b	= create_date('H:i', $dura, $userdata['user_timezone']);
							
							$diff	= ( $date == $dura ) ? "am gesamten Tag" : "von $time_a bis $time_b";
							
							$list[] = "$title: $diff";
						}
					}
					
					if ( !empty($list) )
					{
						$language		= ( count($list) == '1' ) ? $lang['cal_event'] : $lang['cal_events'];
						$list			= implode('<br />', $list);	
						$day_event		.= ( empty($day_event) ) ? "<span><em class=\"events\">$language:</em><br />$list" : "<br /><em class=\"events\">$language</em><br />$list";
						$day_class		= 'events';
						$day_event_num	= $day_event_num + 1;
					}
				}
				
				if ( is_array($monat_matchs[$i]) )
				{
					$list = array();
					for ( $k = 0; $k < count($monat_matchs[$i]); $k++ )
					{
					#	$match_id	= $monat_matchs[$i][$k]['match_id'];
						$match_vs	= $monat_matchs[$i][$k]['match_rival'];
						$match_time	= create_date('H:i', $monat_matchs[$i][$k]['match_date'], $config['page_timezone']);
						$team_name	= $monat_matchs[$i][$k]['team_name'];
						$game_size	= $monat_matchs[$i][$k]['game_size'];
						$game_image	= $monat_matchs[$i][$k]['game_image'];
						$team_image	= "<img src=\"$root_path" . $settings['path_games'] . "/$game_image\" alt=\"$team_name\" title=\"$team_name\" width=\"$game_size\" align=\"middle\">";
						
						$list[] = "$team_image $match_vs - $match_time";
					#	$list[] = $monat_matchs[$i][$k]['match_rival'];
					}
					
					$language		= ( count($list) == '1' ) ? $lang['cal_match'] : $lang['cal_matchs'];
					$list			= implode('<br />', $list);
					$day_event		.= ( empty($day_event) ) ? "<span><em class=\"wars\">$language:</em><br />$list" : "<br /><em class=\"wars\">$language</em><br />$list";
					$day_class		= 'wars';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_trainings[$i]) )
				{
					$list = array();
					for ( $k = 0; $k < count($monat_trainings[$i]); $k++ )
					{
						$list[] = $monat_trainings[$i][$k]['training_vs'];
					}
					
					$language		= ( count($list) == '1' ) ? $lang['cal_training'] : $lang['cal_trainings'];
					$list			= implode('<br />', $list);
					$day_event		.= ( empty($day_event) ) ? "<span><em class=\"trains\">$language:</em><br />$list" : "<br /><em class=\"trains\">$language</em><br />$list";
					$day_class		= 'trains';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( $day_event_num )
				{
					$class = ( $day_event_num > 1 ) ? 'more' : $day_class;
					$day .= "<td align=\"center\" width=\"14%\" class=\"$class\"><a class=\"$class\" href=\"" . append_sid("calendar.php#$i") . "\">$i $day_event</span></a></td>";
				}
				else
				{
					$day .= "<td align=\"center\" width=\"14%\" class=\"day\">$i</td>";
				}
			}
			else
			{
				$day .= "<td align=\"center\" width=\"14%\" class=\"day\">$i</td>";
			}
		}
		else
		{
			if ( $i == $tag || is_array($monat_birthday[$i]) || is_array($monat_events[$i]) || is_array($monat_matchs[$i]) )
			{
				$day_event = '';
				$day_class = '';
				$day_event_num = '0';
				
				if ( $i == $tag )
				{
					$day_event		= '<span><em class="today">' . $lang['cal_today'] . '</em> ';
					$day_class		= 'today';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_birthday[$i]) )
				{
					$list = array();
					for ( $k=0; $k < count($monat_birthday[$i]); $k++ )
					{
						$alter	= 0;
						$gebdt	= explode("-", $monat_birthday[$i][$k]['user_birthday']);
						$gebdt	= $gebdt[0].$gebdt[1].$gebdt[2];
						$now	= date("Ymd", time());
						
						while ($gebdt < $now - 9999)
						{
							$alter++;
							$gebdt = $gebdt + 10000;
						}
		
						$list[] = sprintf($lang['cal_birth'], $monat_birthday[$i][$k]['username'], $alter);
					}
					
					$language		= (count($list) == 1) ? $lang['cal_birthday'] : $lang['cal_birthdays'];
					$list			= implode('<br>', $list);	
					$day_event		.= (empty($day_event)) ? '<span><em class="birthday">' . $language . ':</em> ' . $list : '<br><em class="birthday">' . $language . '</em><br>' . $list;
					$day_class		= 'birthday';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_events[$i]) )
				{
					$list = array();
					
					for ( $k = 0; $k < count($monat_events[$i]); $k++ )
					{
						if ( $userdata['user_level'] >= $monat_events[$i][$k]['event_level'] )
						{
							$date	= $monat_events[$i][$k]['event_date'];
							$title	= $monat_events[$i][$k]['event_title'];
							$dura	= $monat_events[$i][$k]['event_duration'];
							
							$time_a	= create_date('H:i', $date, $userdata['user_timezone']);
							$time_b	= create_date('H:i', $dura, $userdata['user_timezone']);
							
							$diff	= ( $date == $dura ) ? "am gesamten Tag" : "von $time_a bis $time_b";
							
							$list[] = "$title: $diff";
						}
					}
					
					if ( !empty($list) )
					{
						$language		= ( count($list) == '1' ) ? $lang['cal_event'] : $lang['cal_events'];
						$list			= implode('<br />', $list);	
						$day_event		.= ( empty($day_event) ) ? "<span><em class=\"events\">$language:</em><br />$list" : "<br /><em class=\"events\">$language</em><br />$list";
						$day_class		= 'events';
						$day_event_num	= $day_event_num + 1;
					}
				}
				
				if ( is_array($monat_matchs[$i]) )
				{
					$list = array();
					
					for ( $k=0; $k < count($monat_matchs[$i]); $k++ )
					{
						$list[] = $monat_matchs[$i][$k]['match_rival'];
					}
					
					$language		= (count($list) == 1) ? $lang['cal_match'] : $lang['cal_matchs'];
					$list			= implode('<br>', $list);
					$day_event		.= (empty($day_event)) ? '<span><em class="wars">' . $language . ':</em> ' . $list : '<br><em class="wars">' . $language . '</em><br>' . $list;
					$day_class		= 'wars';
					$day_event_num	= $day_event_num + 1;
				}
				
				$class = ( $day_event_num > 1 ) ? 'more' : $day_class;
				$day .= '<td align="center" width="14%" class="' . $class . '"><a class="' . $class . '" href="' . append_sid('calendar.php#' . $i ) . '">' . $i . '' . $day_event . '</span></a></td>';
				
			}
			else
			{
				$day .= '<td align="center" width="14%" class="day">' . $i . '</td>';
			}
		}
		
		if ( $wcs < 7 )
		{
			$wcs++;
		}
		
		if ( $wcs == 7 )
		{
			$day .= '</tr><tr>';
			$wcs = 0;
		}
	}
	
	for ( $wcs; $wcs<7; $wcs++ )
	{
		$day .= '<td align="center">&nbsp;</td>';
	}
	
	$template->assign_vars(array(
		'MONTH'		=> $month,
		'DAYS'		=> $days,
		'DAY'		=> $day,
		'CAL_CACHE'	=> (defined('CACHE')) ? display_cache($sCacheName, 1) : '',
	));
}


#	next Wars
function display_next_match()
{
	global $db, $oCache, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_match_next'] )
	{
		$template->assign_block_vars('_next_match', array());
	}

	$time	= time() - 86400;
	$monat	= date("m", time());
	
	if ( $userdata['user_level'] >= TRIAL )
	{
		$cache = 'subnavi_match_' . $monat . '_member';
		
		$sql = "SELECT m.match_id, m.match_rival, m.match_date, g.game_image, g.game_size, t.team_name
						FROM " . MATCH . " m
							LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
							LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					WHERE match_date > $time AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%m') = '$monat' ORDER BY match_date";
		$tmp = _cached($sql, $cache);
	}
	else
	{
		$cache = 'subnavi_match_' . $monat . '_guest';
		
		$sql = "SELECT m.match_id, m.match_rival, m.match_date, g.game_image, g.game_size, t.team_name
						FROM " . MATCH . " m
							LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
							LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					WHERE match_date > $time AND match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%m') = '$monat' ORDER BY match_date";
		$tmp = _cached($sql, $cache);
	}
	
	if ( $tmp )
	{
		for ( $k = 0; $k < count($tmp); $k++ )
		{
			$team_name	= $tmp[$k]['team_name'];
			$game_size	= $tmp[$k]['game_size'];
			$game_image	= $tmp[$k]['game_image'];
			$info_date	= create_date('d H:i', $tmp[$k]['match_date'], $userdata['user_timezone']);
			$info_image	= "<img src=\"$root_path" . $settings['path_games'] . "/$game_image\" alt=\"$team_name\" title=\"$team_name\" width=\"$game_size\" align=\"middle\">";
			$info_name	= ( strlen($tmp[$k]['match_rival']) < 10 ) ? $tmp[$k]['match_rival'] : substr($tmp[$k]['match_rival'], 0, 9) . ' ...';
			
			
			$template->assign_block_vars('_next_match._match_row', array(
				'NAME'	=> $info_name,
				'DATE'	=> $info_date,
				'IMAGE'	=> $info_image,
				'URL'	=> append_sid('match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $tmp[$k]['match_id']),
			));
		}
	}
}


#	next trainings
function display_next_training()
{
	global $db, $oCache, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_training'] && ( $userdata['user_level'] >= TRIAL ) )
	{
		$template->assign_block_vars('_next_training', array());
	}

	$time = time() - 86400;
	$monat = date("m", time());	//	Heutiger Monat
	
	$cache = 'subnavi_training_' . $monat;
	
	$sql = "SELECT * FROM " . TRAINING . " WHERE training_date > $time AND DATE_FORMAT(FROM_UNIXTIME(training_date), '%m') = '$monat' ORDER BY training_date";
	$tmp = _cached($sql, $cache);
	
	if ( $tmp )
	{
		for ( $k = 0; $k < count($tmp); $k++ )
		{
			$details = (strlen($tmp[$k]['training_vs']) < 15) ? $tmp[$k]['training_vs'] : substr($tmp[$k]['training_vs'], 0, 12) . ' ...';
	
			$date = create_date('d H:i', $tmp[$k]['training_date'], $userdata['user_timezone']);
			
			$template->assign_block_vars('_next_training._training_row', array(
				'L_NAME'	=> $details,
				'U_NAME'	=> append_sid('training.php?mode=trainingdetails&amp;' . POST_TRAINING_URL . '=' . $tmp[$k]['training_id']),
				'DATE'		=> $date,
				
			));
		}
	}
}

?>