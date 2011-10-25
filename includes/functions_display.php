<?php

/* Cache Gültigkeit */
function display_cache($name, $type = '')
{
	global $oCache, $userdata, $lang;
	
	if ( defined('CACHE') )
	{
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
	
	$image	= '<img src="' . $root_path . $settings['path_games'] . '/' . $game_image . '" alt="' . $game_image . '" title="' . $game_image . '" width="' . $game_size . '" height="' . $game_size . '" class="icon" >';

	return $image;
}


/* navigation */
function display_navi()
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	$sql = "SELECT * FROM " . NAVI . " WHERE navi_show = 1 ORDER BY navi_order ASC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$navi = $db->sql_fetchrowset($result);
	$navi = _cached($sql, 'dsp_navi');

	foreach ( $navi as $key => $row )
	{
		if ( $userdata['user_level'] >= TRIAL && ( $row['navi_intern'] == 0 || $row['navi_intern'] == 1 ) )
		{
			$ary[] = $row;
		}
		else if ( $row['navi_intern'] != 1 && $row['navi_type'] != NAVI_USER )
		{
			$ary[] = $row;
		}
	}
	
	$template->assign_block_vars('_navi_main', array());
	$template->assign_block_vars('_navi_clan', array());
	$template->assign_block_vars('_navi_comm', array());
	$template->assign_block_vars('_navi_misc', array());
	$template->assign_block_vars('_navi_user', array());
	
<<<<<<< .mine
	for ( $i = 0; $i < count($ary); $i++ )
=======
	$cnt = count($ary);
	
	for ( $i = 0; $i < $cnt; $i++ )
>>>>>>> .r85
	{
		switch ( $ary[$i]['navi_type'] )
		{
			case NAVI_MAIN:	$row_type = '_navi_main._navi_main_row';	break;
			case NAVI_CLAN:	$row_type = '_navi_clan._navi_clan_row';	break;
			case NAVI_COM:	$row_type = '_navi_comm._navi_comm_row';	break;
			case NAVI_MISC:	$row_type = '_navi_misc._navi_misc_row';	break;
			case NAVI_USER:	$row_type = '_navi_user._navi_user_row';	break;
		}
		
<<<<<<< .mine
		$navi_lang = ( $ary[$i]['navi_lang'] ) ? $lang[$ary[$i]['navi_name']] : $ary[$i]['navi_name'];
		
=======
>>>>>>> .r85
		$template->assign_block_vars($row_type, array(
<<<<<<< .mine
			'NAVI_NAME'		=> $navi_lang,
			'NAVI_URL'		=> $ary[$i]['navi_url'],
			'NAVI_TARGET'	=> ( $ary[$i]['navi_target'] == '0' ) ? '_self' : '_blank',
=======
			'NAVI_URL'		=> $ary[$i]['navi_url'],
			'NAVI_NAME'		=> ( $ary[$i]['navi_lang'] ) ? $lang[$ary[$i]['navi_name']] : $ary[$i]['navi_name'],
			'NAVI_TARGET'	=> ( $ary[$i]['navi_target'] == '0' ) ? '_self' : '_blank',			
>>>>>>> .r85
		));
	}
}


/* news */
function display_navi_news()
{
	global $config, $db, $lang, $root_path, $settings, $template, $userdata;
	
	$sql = "SELECT n.news_id, n.news_title, n.news_intern, n.match_id, t.team_name, g.game_image, g.game_size
				FROM " . NEWS . " n
					LEFT JOIN " . MATCH . " m ON n.match_id = m.match_id
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE n.news_time_public < " . time() . " AND news_public = 1
			ORDER BY n.news_time_public DESC, n.news_id DESC LIMIT 0," . $settings['subnavi_news_limit'];
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$news = $db->sql_fetchrowset($result);
	$news = _cached($sql, 'dsp_news');
	
	if ( !$news )
	{
		$template->assign_block_vars('_news_sn_empty', array());
	}
	else
	{
		foreach ( $news as $news => $row )
		{
			if ( $userdata['user_level'] >= TRIAL )
			{
				$ary[] = $row;
			}
			else if ( $row['news_intern'] == '0' )
			{
				$ary[] = $row;
			}
		}
		
<<<<<<< .mine
		$i = 0; 
		
		while ( $i < count($ary) )
=======
		$cnt = count($ary);
	
		for ( $i = 0; $i < $cnt; $i++ )
>>>>>>> .r85
		{
<<<<<<< .mine
			$class	= ( $i % 2 ) ? 'row1r' : 'row2r';
			
			$template->assign_block_vars('_sn_news_row', array(
				'CLASS' 	=> $class,
				'TITLE'		=> cut_string($ary[$i]['news_title'], $settings['subnavi_news_length']),
				'GAME'		=> ( $ary[$i]['match_id'] ) ? display_gameicon($ary[$i]['game_size'], $ary[$i]['game_image']) : '',
				'DETAILS'	=> check_sid('news.php?mode=view&amp;' . POST_NEWS . '=' . $ary[$i]['news_id']),
			));
			
			$i++;
=======
			$template->assign_block_vars('_sn_news_row', array(
				'CLASS' 	=> ( $i % 2 ) ? 'row1r' : 'row2r',
				'TITLE'		=> cut_string($ary[$i]['news_title'], $settings['subnavi_news_length']),
				'GAME'		=> ( $ary[$i]['match_id'] ) ? display_gameicon($ary[$i]['game_size'], $ary[$i]['game_image']) : '',
				'DETAILS'	=> check_sid('news.php?' . POST_NEWS . '=' . $ary[$i]['news_id']),
			));
>>>>>>> .r85
		}
	}
	
	return;
}

/* match */
function display_navi_match()
{
	global $config, $db, $lang, $root_path, $settings, $template, $userdata;
	
	$sql = "SELECT m.match_id, m.match_date, m.match_rival_name, m.match_public, t.team_name, g.game_image, g.game_size
				FROM " . MATCH . " m
					LEFT JOIN " . TEAMS . " t ON t.team_id = m.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE m.match_date < " . time() . "
			ORDER BY m.match_date ASC LIMIT 0," . $settings['subnavi_match_limit'];
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$match = $db->sql_fetchrowset($result);
	$match = _cached($sql, 'dsp_match');
	
	if ( !$match )
	{
		$template->assign_block_vars('_sn_match_empty', array());
	}
	else
	{
		foreach ( $match as $key => $row )
		{
			if ( $userdata['user_level'] >= TRIAL )
			{
				if ( $row['match_date'] < time() )
				{
					$match_last[] = $row;
					$_ary_ids[] = $row['match_id'];
				}
			}
			else if ( $row['match_public'] == '1' )
			{
				if ( $row['match_date'] < time() )
				{
					$match_last[] = $row;
					$_ary_ids[] = $row['match_id'];
				}
			}
		}
		
		$match_ids = implode(', ', $_ary_ids);
		
		$sql = "SELECT match_id, map_name, map_points_home, map_points_rival FROM " . MATCH_MAPS . " WHERE match_id IN ($match_ids) ORDER BY match_id, map_order ASC";
	#	if ( !($result = $db->sql_query($sql)) )
	#	{
	#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	#	}
	#	$maps = $db->sql_fetchrowset($result);
		$maps = _cached($sql, 'dsp_match_maps');
		
		if ( $match_last )
		{
			$cnt = count($match_last);
			$cnt_maps = count($maps);
			
			for ( $i = 0; $i < $cnt; $i++ )
			{
				$match_id = $match_last[$i]['match_id'];
				
				$class = ($i % 2) ? 'row1r' : 'row2r';
				
				$result_clan[$i] = 0;
				$result_rival[$i] = 0;
				
				for ( $j = 0; $j < $cnt_maps; $j++ )
				{
					if ( $maps[$j]['match_id'] == $match_id )
					{
						$result_clan[$i]	+= $maps[$j]['map_points_home'];
						$result_rival[$i]	+= $maps[$j]['map_points_rival'];
					}
				}
			
				$css	= ( $result_clan[$i] != $result_rival[$i] ) ? ( $result_clan[$i] > $result_rival[$i] ) ? WIN : LOSE : DRAW;
				$name	= $match_last[$i]['match_public'] ? sprintf($lang['sprintf_subnavi_match'], cut_string($match_last[$i]['match_rival_name'], $settings['subnavi_match_length'])) : sprintf($lang['sprintf_subnavi_match_i'], cut_string($match_last[$i]['match_rival_name'], $settings['subnavi_match_length']));
				
<<<<<<< .mine
				$template->assign_block_vars('_sn_match_row', array(
					'CSS'		=> $css,
					'CLASS' 	=> $class,
					'GAME'		=> display_gameicon($match_last[$i]['game_size'], $match_last[$i]['game_image']),
					'NAME'		=> $name,
					'RESULT'	=> sprintf($lang['sprintf_subnavi_match_result'], $result_clan[$i], $result_rival[$i]),
					'DETAILS'	=> check_sid('match.php?mode=detail&amp;' . POST_MATCH . '=' . $match_last[$i]['match_id']),
=======
				$template->assign_block_vars('_sn_match_row', array(
					'CSS'		=> $css,
					'CLASS' 	=> $class,
					'GAME'		=> display_gameicon($match_last[$i]['game_size'], $match_last[$i]['game_image']),
					'NAME'		=> $name,
					'RESULT'	=> sprintf($lang['sprintf_subnavi_match_result'], $result_clan[$i], $result_rival[$i]),
					'DETAILS'	=> check_sid('match.php?' . POST_MATCH . '=' . $match_last[$i]['match_id']),
>>>>>>> .r85
				));
			}
		}
	}
	
	return;
}

/* downloads */
/* forum */

/* neuste Mitglieder */
function display_navi_newusers()
{
	global $db, $lang, $root_path, $settings, $template, $userdata;
	
	if ( $settings['subnavi_newusers'] )
	{
		$template->assign_block_vars('_sn_newusers', array());
		
		$sql = "SELECT user_id, user_name, user_color
					FROM " . USERS . "
					WHERE user_id != -1 AND user_active = 1
				ORDER BY user_regdate
					LIMIT 0, " . $settings['subnavi_newusers_limit'];
#		if ( !($result = $db->sql_query($sql)) )
#		{
#			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#		}
#		$users = $db->sql_fetchrowset($result);
		$users = _cached($sql, 'dsp_sn_newusers', 0, $settings['subnavi_newusers_cache']);
		
		for ( $i = 0; $i < count($users); $i++ )
		{
			$template->assign_block_vars('_sn_newusers._user_row', array(
				'L_USERNAME'	=> '<b>' . cut_string($users[$i]['user_name'], $settings['subnavi_newusers_length']) . '</b>',
<<<<<<< .mine
				'U_USERNAME'	=> check_sid('profile.php?mode=view&amp;' . POST_USER . '=' . $users[$i]['user_id']),
=======
				'U_USERNAME'	=> check_sid('profile.php?' . POST_USER . '=' . $users[$i]['user_id']),
>>>>>>> .r85
				'C_USERNAME'	=> 'style="color:' . $users[$i]['user_color'] . '"',
			));
		}
		
		$template->assign_vars(array(
			'NEW_USERS_CACHE'	=> ( defined('CACHE') ) ? display_cache('display_sn_newusers', 1) : '',									 
			'L_NEW_USERS'		=> sprintf($lang['newest_users'], $settings['subnavi_newusers_limit']),
		));
		
		return;
	}
}

/* teams */
function display_navi_teams()
{
	global $db, $lang, $root_path, $settings, $template, $userdata;
	
	if ( $settings['subnavi_teams'] )
	{
		$template->assign_block_vars('_sn_teams', array());
		
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
		$teams = _cached($sql, 'dsp_sn_teams');
		
		if ( !$teams )
		{
			$template->assign_block_vars('_sn_teams_empty', array());
		}
		else		
		{
			for ( $i = 0; $i < count($teams); $i++ )
			{
				$template->assign_block_vars('_sn_teams._team_row', array(
					'L_TEAM'	=> cut_string($teams[$i]['team_name'], $settings['subnavi_teams_length']),
					'I_GAME'	=> display_gameicon($teams[$i]['game_size'], $teams[$i]['game_image']),
					'U_TEAM'	=> check_sid("teams.php?" . POST_TEAMS . "=" . $teams[$i]['team_id']),
				));
			}
		}
	}
	
	return;
}

/* network: link/partner/sponsor */
function display_navi_network($type)
{
	global $db, $lang, $root_path, $settings, $template, $userdata;
	
	$sql = "SELECT * FROM " . NETWORK . " WHERE network_view = 1 ORDER BY network_order";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$network_data = $db->sql_fetchrowset($result);
	$network_data = _cached($sql, 'dsp_sn_network');
	
	if ( $settings['subnavi_' . $type] )
	{
		$template->assign_block_vars('_sn_' . $type, array());
		
		$typ = ( $type != 'links' ) ? ( $type == 'partner' ) ? NETWORK_PARTNER : NETWORK_SPONSOR : NETWORK_LINK;
		
		foreach ( $network_data as $network )
		{
			if ( $network['network_type'] == $typ )
			{
				$template->assign_block_vars('_sn_' . $type . '._' . $type . '_row', array(
					'L_URL'	=> $network['network_name'],
					'U_URL'	=> $network['network_url'],
				));
			}
		}
	}
	
	return;
}

/* minikalender */
function display_navi_minical()
{
	global $db, $lang, $config, $oCache, $root_path, $settings, $template, $userdata;
	
	if ( $settings['subnavi_minical'] )
	{
		$template->assign_block_vars('_sn_minical', array());
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
	
	if ( defined('CACHE') )
	{
		$sCacheName = 'dsp_sn_minical';

		if ( ( $monat_data = $oCache -> readCache($sCacheName)) === false )
		{
			for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
			{
				$i = ( $i < 10 ) ? '0' . $i : $i;
				
				$sql = "SELECT user_name, user_birthday, user_color FROM " . USERS . " WHERE MONTH(user_birthday) = $monat AND DAYOFMONTH(user_birthday) = $i";
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
				
			#	$sql = 'SELECT match_rival_name, match_date FROM ' . MATCH . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				$sql = "SELECT m.match_id, m.match_rival_name, m.match_date, g.game_image, g.game_size, t.team_name
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
				$monat_data = array_merge(array('bday' => $monat_data_b), array('event' => $monat_data_e), array('match' => $monat_data_w), array('train' => $monat_data_t));
				$oCache -> writeCache($sCacheName, $monat_data);
			}
		}
	}
	else
	{
		for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
		{
			$i = ( $i < 10 ) ? '0' . $i : $i;
			
			$sql = 'SELECT user_name, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
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
			
		#	$sql = 'SELECT match_rival_name, match_date FROM ' . MATCH . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
			$sql = "SELECT m.match_id, m.match_rival_name, m.match_date, g.game_image, g.game_size, t.team_name
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
			$monat_data = array_merge(array('bday' => $monat_data_b), array('event' => $monat_data_e), array('match' => $monat_data_w), array('train' => $monat_data_t));
		}
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
		$i = ( $i < 10 ) ? '0' . $i : $i;
#		if ( $i < 10 ) { $i = '0' . $i; }
		
		if ( $i == $tag || is_array($monat_data['bday'][$i]) || is_array($monat_data['event'][$i]) || is_array($monat_data['match'][$i]) || is_array($monat_data['train'][$i]) )
		{
			$day_class = '';
			$day_event = '';
			$day_event_num = '0';
			
			if ( $i == $tag )
			{
				$day_class		= 'today';
				$day_event		= '<span><em class="today">' . $lang['cal_today'] . '</em>';
				$day_event_num	= $day_event_num + 1;
			}
			
			if ( is_array($monat_data['bday'][$i]) )
			{
				$list = array();
				
				for ( $k = 0; $k < count($monat_data['bday'][$i]); $k++ )
				{
					$alter	= 0;
					$gebdt	= explode('-', $monat_data['bday'][$i][$k]['user_birthday']);
					$gebdt	= $gebdt[0].$gebdt[1].$gebdt[2];
					$now	= date('Ymd', time());
					
					while ( $gebdt < $now - 9999 )
					{
						$alter++;
						$gebdt = $gebdt + 10000;
					}
	
					$list[] = sprintf($lang['cal_birth'], $monat_data['bday'][$i][$k]['user_name'], $alter);
				}
				
				$language		= (count($list) == 1) ? $lang['cal_birthday'] : $lang['cal_birthdays'];
				$list			= implode('<br>', $list);	
				$day_event		.= (empty($day_event)) ? "<span><em class=\"birthday\">$language:</em><br />$list" : "<br /><em class=\"birthday\">$language</em><br />$list";
				$day_class		= 'birthday';
				$day_event_num	= $day_event_num + 1;
			}
			
			if ( is_array($monat_data['event'][$i]) )
			{
				$list = array();
				
				for ( $k = 0; $k < count($monat_data['event'][$i]); $k++ )
				{
					if ( $userdata['user_level'] >= $monat_data['event'][$i][$k]['event_level'] )
					{
						$date	= $monat_data['event'][$i][$k]['event_date'];
						$title	= $monat_data['event'][$i][$k]['event_title'];
						$dura	= $monat_data['event'][$i][$k]['event_duration'];
						
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
					$day_class		= 'events';
					$day_event_num	= $day_event_num + 1;
					
					$day_event	.= ( empty($day_event) ) ? "<span><em class=\"events\">$language:</em><br />$list" : "<br /><em class=\"events\">$language</em><br />$list";
				}
			}
			
			if ( is_array($monat_data['match'][$i]) )
			{
				$list = array();
				for ( $k = 0; $k < count($monat_data['match'][$i]); $k++ )
				{
				#	$match_id	= $monat_data['match'][$i][$k]['match_id'];
					$match_vs	= $monat_data['match'][$i][$k]['match_rival_name'];
					$match_time	= create_date('H:i', $monat_data['match'][$i][$k]['match_date'], $config['page_timezone']);
					$team_name	= $monat_data['match'][$i][$k]['team_name'];
				#	$game_size	= $monat_data['match'][$i][$k]['game_size'];
				#	$game_image	= $monat_data['match'][$i][$k]['game_image'];
				#	$team_image	= "<img src=\"$root_path" . $settings['path_games'] . "/$game_image\" alt=\"$team_name\" title=\"$team_name\" width=\"$game_size\" align=\"middle\">";
					
				#	$list[] = "$team_image $match_vs - $match_time";
					$list[] = "$match_vs - $match_time";
				#	$list[] = $monat_data['match'][$i][$k]['match_rival_name'];
				}
				
				$language		= ( count($list) == '1' ) ? $lang['cal_match'] : $lang['cal_matchs'];
				$list			= implode('<br />', $list);
				$day_event		.= ( empty($day_event) ) ? "<span><em class=\"wars\">$language:</em><br />$list" : "<br /><em class=\"wars\">$language</em><br />$list";
				$day_class		= 'wars';
				$day_event_num	= $day_event_num + 1;
			}
			
			if ( is_array($monat_data['train'][$i]) && $userdata['user_level'] >= TRIAL )
			{
				$list = array();
				for ( $k = 0; $k < count($monat_data['train'][$i]); $k++ )
				{
					$list[] = $monat_data['train'][$i][$k]['training_vs'];
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
				
				$day .= "<td align=\"center\" width=\"14%\" class=\"$class\"><a class=\"$class\" href=\"" . check_sid("calendar.php") . "\">$i</span>$day_event</a></td>";
				
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
	
	for ( $wcs; $wcs < 7; $wcs++ )
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

/* next Match */
function display_next_match()
{
	global $db, $oCache, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_match_next'] )
	{
		$template->assign_block_vars('_sn_next_match', array());
	}

<<<<<<< .mine
	$match	= '';
=======
	$ary	= '';
>>>>>>> .r85
	$time	= time() - 86400;
	$month	= date("m", time());
	$cache	= 'dsp_sn_next_match';
		
	$sql = "SELECT m.match_id, m.match_rival_name, m.match_date, m.match_public, t.team_name, g.game_image, g.game_size
					FROM " . MATCH . " m
						LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE match_date > $time AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%m') = '$month' ORDER BY match_date";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$match = $db->sql_fetchrowset($result);
	$match = _cached($sql, $cache);
	
	if ( $match )
	{
		foreach ( $match as $key => $row )
		{
			if ( $userdata['user_level'] >= TRIAL )
			{
				$ary[] = $row;
			}
			else if ( $row['match_public'] == '1' )
			{
				$ary[] = $row;
			}
		}
	}
	
<<<<<<< .mine
	if ( isset($ary) )
=======
	if ( $ary )
>>>>>>> .r85
	{
<<<<<<< .mine
		for ( $i = 0; $i < count($ary); $i++ )
=======
		$cnt = count($ary);
		
		for ( $i = 0; $i < $cnt; $i++ )
>>>>>>> .r85
		{
			$team_name	= $ary[$i]['team_name'];
			$game_size	= $ary[$i]['game_size'];
			$game_image	= $ary[$i]['game_image'];
			
			$name = ( strlen($ary[$i]['match_rival_name']) < 10 ) ? $ary[$i]['match_rival_name'] : substr($ary[$i]['match_rival_name'], 0, 9) . ' ...';
			
<<<<<<< .mine
			$template->assign_block_vars('_sn_next_match._match_row', array(
				'GAME'	=>  display_gameicon($ary[$i]['game_size'], $ary[$i]['game_image']),
				'NAME'	=> "<a href=\"" . check_sid("match.php?mode=detail&amp;" . POST_MATCH . "=" . $ary[$i]['match_id']) . "\">$name</a>",
				'DATE'	=> create_date('d H:i', $ary[$i]['match_date'], $userdata['user_timezone']),
=======
			$template->assign_block_vars('_sn_next_match._match_row', array(
				'GAME'	=>  display_gameicon($ary[$i]['game_size'], $ary[$i]['game_image']),
				'NAME'	=> "<a href=\"" . check_sid("match.php?" . POST_MATCH . "=" . $ary[$i]['match_id']) . "\">$name</a>",
				'DATE'	=> create_date('d H:i', $ary[$i]['match_date'], $userdata['user_timezone']),
>>>>>>> .r85
			));
		}
	}
}

<<<<<<< .mine

/* next Training */
=======
/* next Training */
>>>>>>> .r85
function display_next_training()
{
	global $db, $oCache, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_training'] && $userdata['user_level'] >= TRIAL )
	{
		$template->assign_block_vars('_sn_next_training', array());

<<<<<<< .mine
		$time	= time() - 86400;
		$month	= date("m", time());
		$cache	= 'dsp_sn_next_training';
		
		$sql = "SELECT tr.*, t.team_name, g.game_image, g.game_size
					FROM " . TRAINING . "  tr
						LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE training_date > $time AND DATE_FORMAT(FROM_UNIXTIME(training_date), '%m') = '$month' ORDER BY training_date";
	#	if ( !($result = $db->sql_query($sql)) )
	#	{
	#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	#	}
	#	$train = $db->sql_fetchrowset($result);
		$train = _cached($sql, $cache);
		
		$i = 0;
		
		while ( $i < count($train) )
=======
		$time	= time() - 86400;
		$month	= date("m", time());
		$cache	= 'dsp_sn_next_training';
		
		$sql = "SELECT tr.*, t.team_name, g.game_image, g.game_size
					FROM " . TRAINING . "  tr
						LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE training_date > $time AND DATE_FORMAT(FROM_UNIXTIME(training_date), '%m') = '$month' ORDER BY training_date";
	#	if ( !($result = $db->sql_query($sql)) )
	#	{
	#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	#	}
	#	$train = $db->sql_fetchrowset($result);
		$train = _cached($sql, $cache);
		
		if ( $train )
>>>>>>> .r85
		{
<<<<<<< .mine
			$team_name	= $train[$i]['team_name'];
			$game_size	= $train[$i]['game_size'];
			$game_image	= $train[$i]['game_image'];
=======
			$cnt = count($train);
>>>>>>> .r85
			
<<<<<<< .mine
			$game = "<img src=\"$root_path" . $settings['path_games'] . "/$game_image\" alt=\"$team_name\" title=\"$team_name\" width=\"$game_size\" >";
			$name = ( strlen($train[$i]['training_vs']) < 10 ) ? $train[$i]['training_vs'] : substr($train[$i]['training_vs'], 0, 9) . '...';
	
			$template->assign_block_vars('_sn_next_training._training_row', array(
				'GAME'	=> $game,
				'NAME'	=> "<a href=\"" . check_sid('training.php?mode=detail&amp;' . POST_TRAINING . '=' . $train[$i]['training_id']) . "\">$name</a>",
				'DATE'	=> create_date('d H:i', $train[$i]['training_date'], $userdata['user_timezone']),
=======
			for ( $i = 0; $i < $cnt; $i++ )
			{
				$team_name	= $train[$i]['team_name'];
				$game_size	= $train[$i]['game_size'];
				$game_image	= $train[$i]['game_image'];
				
>>>>>>> .r85
				$game = "<img src=\"$root_path" . $settings['path_games'] . "/$game_image\" alt=\"$team_name\" title=\"$team_name\" width=\"$game_size\" >";
				$name = ( strlen($train[$i]['training_vs']) < 10 ) ? $train[$i]['training_vs'] : substr($train[$i]['training_vs'], 0, 9) . '...';
		
				$template->assign_block_vars('_sn_next_training._training_row', array(
					'GAME'	=> $game,
					'NAME'	=> "<a href=\"" . check_sid('training.php?' . POST_TRAINING . '=' . $train[$i]['training_id']) . "\">$name</a>",
					'DATE'	=> create_date('d H:i', $train[$i]['training_date'], $userdata['user_timezone']),
				));
			}
		}
	}
}

?>