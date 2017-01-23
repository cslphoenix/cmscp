<?php

/* function list

	display_navi			// Navigation
	display_news			// Nachrichten
	display_match			// Begegnungen
	display_forms			// Top Forenthemen
	display_downloads		// Top Downloads
	display_newusers		// Neueste Benutzer
	display_teams			// Teams
	display_network			// Netzwerk ( Links, Partner, Sponsoren )
	display_minical			// Minikalender
	display_server			// Gameserver Viewer
	display_next_match		// naeste Begegnungen
	display_next_training	// naechste Trainings
	display_cache			// Cachespeicherdauer
	display_gameicon		// Spieliconanzeige

*/

function display_navi($l_login_logout, $u_login_logout)
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	$userauth = auth_acp_check($userdata['user_id']);
	unset($tmp);
	$sql = "SELECT * FROM " . MENU . " WHERE action = 'pcp' AND menu_show = 1 ORDER BY menu_order ASC";
	$tmp = _cached($sql, 'dsp_menu');

	$logout = @unserialize($settings['navi_settings']['navi_logout']);
	$logout = isset($logout) ? $logout[0] : false;
	
	$separate = @unserialize($settings['navi_settings']['navi_modus']);
	$separate = isset($separate) ? $separate : false;
	
#	$admin_link = (	$userdata['user_level'] == ADMIN || $auth ) ? '<a href="admin/admin_index.php?sid=' . $userdata['session_id'] . '">' . $lang['Admin_panel'] . '</a>' : '';
#	'U_LOGIN_LOGOUT' => check_sid($u_login_logout),	
#	'L_LOGIN_LOGOUT' => $l_login_logout,
	
	foreach ( $tmp as $row )
	{
	#	if ( $userdata['user_level'] >= TRIAL && ( $row['navi_intern'] == 0 || $row['navi_intern'] == 1 ) )
		if ( $row['type'] == 3 )
		{
			$ary_cat[] = $row;
		}
			
		if ( $row['type'] == 4 && $userdata['user_level'] >= TRIAL )
		{
			$ary_entry[$row['main']][] = $row;
		}
		else if ( $row['type'] == 4 && $row['menu_intern'] != 1 )
		{
			$ary_entry[$row['main']][] = $row;
		}
	}
	
	if ( $logout )
	{
		if ( $userdata['user_level'] == ADMIN || $userauth || $userdata['user_founder'] )
		{
			$ary_entry[$logout][] = array('menu_name' => $lang['Admin_panel'], 'menu_lang' => 0, 'menu_target' => 0, 'menu_file' => 'admin/admin_index.php?sid=' . $userdata['session_id']);
		}
		$ary_entry[$logout][] = array('menu_name' => $l_login_logout, 'menu_lang' => 0, 'menu_target' => 0, 'menu_file' => $u_login_logout);
		
	}
	
	$_cat_s = array();
	
	foreach ( $ary_cat as $row )
	{
		if ( $separate && !in_array($row['menu_id'], $separate) )
		{
			$_cat[] = $row;
		}
		else if ( $separate && in_array($row['menu_id'], $separate) )
		{
			$_cat_s[] = $row;
		}
		else
		{
			$_cat[] = $row;
		}
	}
	
	if ( $_cat )
	{
		$template->assign_block_vars('navi', array());
		
		foreach ( $_cat as $row )
		{
			$template->assign_block_vars('navi.row_navi', array(
				'NAME'	=> ($row['menu_lang']) ? lang($row['menu_name']) : $row['menu_name'],
			));
			
			if ( isset($ary_entry[$row['menu_id']]) )
			{
				foreach ( $ary_entry[$row['menu_id']] as $erow )
				{
					$template->assign_block_vars('navi.row_navi.row_entry', array(
						'NAME'		=> ($erow['menu_lang']) ? lang($erow['menu_name']) : $erow['menu_name'],
						'TARGET'	=> ($erow['menu_target']) ? '_blank' : '_self',
						'URL'		=> $erow['menu_file'],
					));
				}
			}
			
		}
	}
	
	if ( $_cat_s )
	{
		$template->assign_block_vars('separate', array());
		
		foreach ( $_cat_s as $row )
		{
			$template->assign_block_vars('separate.row_separate', array(
				'NAME'	=> ($row['menu_lang']) ? lang($row['menu_name']) : $row['menu_name'],
			));
			
			if ( isset($ary_entry[$row['menu_id']]) )
			{
				foreach ( $ary_entry[$row['menu_id']] as $erow )
				{
					$template->assign_block_vars('separate.row_separate.row_entry', array(
						'NAME'		=> ($erow['menu_lang']) ? lang($erow['menu_name']) : $erow['menu_name'],
						'TARGET'	=> ($erow['menu_target']) ? '_blank' : '_self',
						'URL'		=> $erow['menu_file'],
					));
				}
			}
			
		}
	}
	
	/*
	if ( is_array($separate) )
	{
		foreach ()

	if ( $separate )
	{
		$url = $ary[$i]['navi_url'];
		$tar = $ary[$i]['navi_target'] ? '_blank' : '_self';
		$name = $ary[$i]['navi_lang'] ? isset($lang[$ary[$i]['navi_name']]) ? $lang[$ary[$i]['navi_name']] : $ary[$i]['navi_name'] : $ary[$i]['navi_name'];
		
		$template->assign_block_vars($row_type, array('URL' => "<a href=\"$url\" target=\"$tar\" title=\"$name\">$name</a>"));
			
		
		$template->assign_block_vars('separate', array());
	}
	else
	{
		$template->assign_block_vars('navi', array());
	}
	*/

/*
	$sql = "SELECT * FROM " . NAVI . " WHERE navi_show = 1 ORDER BY navi_order ASC";
	$tmp = _cached($sql, 'dsp_navi', 0);

	foreach ( $tmp as $key => $row )
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
	
	$template->assign_block_vars('navi_main', array());
	$template->assign_block_vars('navi_clan', array());
	$template->assign_block_vars('navi_comm', array());
	$template->assign_block_vars('navi_misc', array());
	$template->assign_block_vars('navi_user', array());
	
	$cnt = count($ary);
	
	for ( $i = 0; $i < $cnt; $i++ )
	{
		switch ( $ary[$i]['navi_type'] )
		{
			case NAVI_MAIN:	$row_type = 'navi_main.row';	break;
			case NAVI_CLAN:	$row_type = 'navi_clan.navi_clan_row';	break;
			case NAVI_COM:	$row_type = 'navi_comm.navi_comm_row';	break;
			case NAVI_MISC:	$row_type = 'navi_misc.navi_misc_row';	break;
			case NAVI_USER:	$row_type = 'navi_user.navi_user_row';	break;
		}
		
		$url = $ary[$i]['navi_url'];
		$tar = $ary[$i]['navi_target'] ? '_blank' : '_self';
		$name = $ary[$i]['navi_lang'] ? isset($lang[$ary[$i]['navi_name']]) ? $lang[$ary[$i]['navi_name']] : $ary[$i]['navi_name'] : $ary[$i]['navi_name'];
		
		$template->assign_block_vars($row_type, array('URL' => "<a href=\"$url\" target=\"$tar\" title=\"$name\">$name</a>"));
	}
*/	
	$template->set_filenames(array('navi' => 'navi_navigation.tpl'));
	$template->assign_var_from_handle('NAVIGATION', 'navi');
}

function display_news()
{
	global $db, $lang, $settings, $template, $userdata;
	
	$sql = "SELECT n.news_id, n.news_title, n.news_intern, n.news_match, t.team_name, g.game_image
				FROM " . NEWS . " n
					LEFT JOIN " . MATCH . " m ON n.news_match = m.match_id
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE n.news_date < " . time() . " AND news_public = 1
			ORDER BY n.news_date DESC, n.news_id DESC LIMIT 0," . $settings['module_news']['limit'];
	$tmp = _cached($sql, 'dsp_news', 0, $settings['module_news']['time']);
	
	if ( !$tmp )
	{
		$template->assign_block_vars('sn_news_empty', array('L_EMPTY' => $lang['header_empty_topics']));
	}
	else
	{
		foreach ( $tmp as $news => $row )
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
		
		$cnt = count($ary);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$game	= $ary[$i]['news_match'] ? display_gameicon($ary[$i]['game_image']) . '&nbsp;' : '';
			$url	= check_sid('news.php?id=' . $ary[$i]['news_id']);
			$name	= cut_string($ary[$i]['news_title'], $settings['module_news']['length']);
			$title	= $ary[$i]['news_title'];

			$template->assign_block_vars('sn_news_row', array(
				'CLASS' 	=> ( $i % 2 ) ? 'row1' : 'row2',
				'GAME'		=> $game,
				'URL'		=> href('a_txt', 'news.php', array('id' => $ary[$i]['news_id']), $title, $name),
			));
		}
	}
	
	$template->set_filenames(array('news' => 'navi_news.tpl'));
	$template->assign_vars(array('CACHE' => ( defined('CACHE') && $settings['module_news']['cache'] ) ? display_cache('dsp_news', 1) : ''));
	$template->assign_var_from_handle('NEWS', 'news');
}

function display_match()
{
	global $db, $lang, $settings, $template, $userdata;
	
	$sql = "SELECT m.match_id, m.match_date, m.match_rival_name, m.match_public, t.team_name, g.game_image
				FROM " . MATCH . " m
					LEFT JOIN " . TEAMS . " t ON t.team_id = m.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE m.match_date < " . time() . "
			ORDER BY m.match_date ASC LIMIT 0," . $settings['module_match']['limit'];
	$tmp = _cached($sql, 'dsp_match', 0, $settings['module_match']['time']);
	
	if ( !$tmp )
	{
		$template->assign_block_vars('sn_match_empty', array('L_EMPTY' => $lang['header_empty_match']));
	}
	else
	{
		$match_last = $_ary_ids = array();
		
		foreach ( $tmp as $row )
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
		
		if ( $match_ids )
		{
			$sql = "SELECT match_id, map_name, map_points_home, map_points_rival FROM " . MATCH_MAPS . " WHERE match_id IN ($match_ids) ORDER BY match_id, map_order ASC";
			$maps = _cached($sql, 'dsp_match_maps');
		}
		else
		{
			$maps = '';
		}
		
	#	debug($settings);
		
		if ( $match_last )
		{
			$cnt = count($match_last);
			$cnt_maps = count($maps);
			
			$result = '';
			
			for ( $i = 0; $i < $cnt; $i++ )
			{
				$match_id = $match_last[$i]['match_id'];
				
				$class = ($i % 2) ? 'row1' : 'row2';
				
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
				$name	= $match_last[$i]['match_public'] ? sprintf($lang['sprintf_subnavi_match'], cut_string($match_last[$i]['match_rival_name'], $settings['module_match']['length'])) : sprintf($lang['sprintf_subnavi_match_i'], cut_string($match_last[$i]['match_rival_name'], $settings['module_match']['length']));
				
				$result[]	.= ( $result_clan[$i] != $result_rival[$i] ) ? ( $result_clan[$i] > $result_rival[$i] ) ? 1 : -1 : 0;
				
				$template->assign_block_vars('sn_match_row', array(
					'CSS'		=> $css,
					'CLASS' 	=> $class,
					'GAME'		=> display_gameicon($match_last[$i]['game_image']),
					'NAME'		=> $name,
					'RESULT'	=> sprintf($lang['sprintf_subnavi_match_result'], $result_clan[$i], $result_rival[$i]),
					'DETAILS'	=> check_sid('match.php?id=' . $match_last[$i]['match_id']),
				));
			}
		}
		
		$template->assign_vars(array(
			'CACHE'		=> ( defined('CACHE') && $settings['module_match']['cache'] ) ? display_cache('dsp_match', 1) : '',
			'RESULT'	=> ( isset($result) ) ? implode(', ', $result) : 0,
		));
	}
	
	$template->set_filenames(array('match' => 'navi_match.tpl'));
	$template->assign_var_from_handle('MATCH', 'match');
}

function display_topics()
{
	global $db, $lang, $settings, $template, $userdata;
	
	$sql = "SELECT * FROM " . TOPICS . " LIMIT 0," . $settings['module_topics']['limit'];
	$data = _cached($sql, 'dsp_topics');
	
	if ( !$data )
	{
		$template->assign_block_vars('sn_topics_empty', array('L_EMPTY' => $lang['header_empty_news']));
	}
	else
	{
		$cnt = count($data);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
		#	$clicks	= '';
		#	$url	= check_sid('news.php?' . POST_NEWS . '=' . $ary[$i]['news_id']);
		#	$name	= cut_string($ary[$i]['news_title'], $settings['module_downloads_length']);
		#	$title	= $ary[$i]['news_title'];

			$template->assign_block_vars('sn_topics_row', array(
				'CLASS' 	=> ( $i % 2 ) ? 'row1' : 'row2',
		#		'CLICKS'	=> $clicks,
		#		'URL'		=> "<a href=\"id\" title=\"$title\">$name</a>",
			));
		}
	}
	
	$template->set_filenames(array('topics' => 'navi_topics.tpl'));
	$template->assign_vars(array('CACHE' => ( defined('CACHE') && $settings['module_topics']['cache'] ) ? display_cache('dsp_topics', 1) : ''));
	$template->assign_var_from_handle('TOPICS', 'topics');
}

function display_downloads()
{
	global $db, $lang, $settings, $template, $userdata;
	
	$sql = "SELECT * FROM " . DOWNLOAD . " WHERE type != 0 LIMIT 0," . $settings['module_downloads']['limit'];
	$data = _cached($sql, 'dsp_downloads');
	
	if ( !$data )
	{
		$template->assign_block_vars('sn_downloads_empty', array('L_EMPTY' => $lang['header_empty_downloads']));
	}
	else
	{
		$cnt = count($data);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
		#	$clicks	= '';
		#	$url	= check_sid('news.php?' . POST_NEWS . '=' . $ary[$i]['news_id']);
		#	$name	= cut_string($ary[$i]['news_title'], $settings['module_downloads_length']);
		#	$title	= $ary[$i]['news_title'];

			$template->assign_block_vars('sn_downloads_row', array(
				'CLASS' 	=> ( $i % 2 ) ? 'row1r' : 'row2r',
		#		'CLICKS'	=> $clicks,
		#		'URL'		=> "<a href=\"id\" title=\"$title\">$name</a>",
			));
		}
	}
	
	$template->set_filenames(array('downloads' => 'navi_downloads.tpl'));
	$template->assign_vars(array('CACHE' => ( defined('CACHE') && $settings['module_downloads']['cache'] ) ? display_cache('dsp_downloads', 1) : ''));
	$template->assign_var_from_handle('DOWNLOADS', 'downloads');
}

function display_newusers()
{
	global $db, $lang, $settings, $template;
	
	$sql = "SELECT user_id, user_name, user_color FROM " . USERS . " WHERE user_id != -1 AND user_active = 1 ORDER BY user_regdate LIMIT 0, " . $settings['module_newusers']['limit'];
	$tmp = _cached($sql, 'dsp_sn_newusers', 0, $settings['module_newusers']['time']);
	$cnt = count($tmp);
	
	for ( $i = 0; $i < $cnt; $i++ )
	{
		$id		= $tmp[$i]['user_id'];
		$url	= check_sid('profile.php?id=' . $id);
		$name	= cut_string($tmp[$i]['user_name'], $settings['module_newusers']['length']);
		$color	= $tmp[$i]['user_color'] ? $tmp[$i]['user_color'] : '';
		
		$template->assign_block_vars('sn_newuers', array('URL' => href('a_style', 'profile.php', array('mode' => 'view', 'id' => $id), $color, $name)));
	}
	
	$template->set_filenames(array('newuers' => 'navi_newusers.tpl'));
	$template->assign_vars(array('CACHE_NEWUSERS' => ( defined('CACHE') && $settings['module_newusers']['cache'] ) ? display_cache('dsp_sn_newusers', 1) : ''));
	$template->assign_var_from_handle('NEWUSERS', 'newuers');
}

function display_teams()
{
	global $db, $lang, $settings, $template;
	
	$sql = "SELECT t.team_id, t.team_name, t.team_fight, g.game_image
				FROM " . TEAMS . " t, " . GAMES . " g
			WHERE t.team_game = g.game_id
				AND t.team_navi = 1
					ORDER BY t.team_order
				LIMIT 0, " . $settings['module_teams']['limit'];
	$data = _cached($sql, 'dsp_sn_teams');
	
	if ( $data )
	{
		$cnt = count($data);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$id		= $data[$i]['team_id'];
			$url	= check_sid("teams.php?mode=t&amp;id=$id");
			$name	= cut_string($data[$i]['team_name'], $settings['module_teams']['length']);
			
			$template->assign_block_vars('sn_teams', array(
				'URL'	=> "<a href=\"$url\">$name</a>",
				'GAME'	=> display_gameicon($data[$i]['game_image']),
			));
		}
		
		$template->set_filenames(array('teams' => 'navi_teams.tpl'));
		$template->assign_vars(array('CACHE' => ( defined('CACHE') && $settings['module_teams']['cache'] ) ? display_cache('dsp_sn_teams', 1) : ''));
		$template->assign_var_from_handle('TEAMS', 'teams');
	}
}

function display_network($type)
{
	global $db, $lang, $settings, $template, $userdata;
	
	$template->set_filenames(array('network' => 'navi_network.tpl'));
	
	$sql = "SELECT * FROM " . NETWORK . " WHERE network_view = 1 ORDER BY network_order";
	$tmp = _cached($sql, "dsp_sn_network");
	
	if ( $settings['module_network']["show_$type"] )
	{
		$template->assign_block_vars("sn_$type", array());
		
		$typ = ( $type != 'links' ) ? ( $type == 'partner' ) ? NETWORK_PARTNER : NETWORK_SPONSOR : NETWORK_LINK;
		
		foreach ( $tmp as $info )
		{
			if ( $info['network_type'] == $typ )
			{
				$name = $info['network_name'];
				$img = $info['network_image'] ? '<img src="' . $settings['path_network']['path'] . $info['network_image'] . '" alt="" border="0" />' : '';
				$url = $info['network_url'];
				$info = $img ? $img : $name;
				
				$template->assign_block_vars("sn_{$type}.row", array('LINK' => "<a href=\"id\" title=\"$name\" target=\"_new\">$info</a>"));
			}
		}
	}
	
	$template->assign_var_from_handle('NETWORK', 'network');
}

#function display_calendar()
function display_minical()
{
	global $db, $lang, $config, $oCache, $root_path, $settings, $template, $userdata;
	
	$cur_day		= date("d", time());		//	Heutiger Tag: z. B. "1"
	$cur_days		= date("t");				//	Anzahl der Tage im Monat: z. B. "31"
	$cur_month		= date("m", time());		//	Heutiger Monat
	$cur_year		= date("Y", time());		//	Heutiges Jahr
	$cur_first		= date("w", mktime(0, 0, 0, $cur_month, 1, $cur_year));		//	Der erste Tag im Monat: z. B. "5 / Freitag"
	$cur_weekdays	= $lang['cal_weekdays'];	//	Wochentage gekürzt
	
	$prev_month		= ( $cur_month == 1 ) ? '12' : $cur_month-1;
	$prev_days		= date('t', mktime(0, 0, 0, $prev_month, 1, $cur_year));
	
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
	
	$months		= $monate[$cur_month];
	$ctime		= $settings['module_calendar']['time'];
	$snews		= $settings['module_calendar']['news'];
	$sevent		= $settings['module_calendar']['event'];
	$smatch		= $settings['module_calendar']['match'];
	$sbirthday	= $settings['module_calendar']['birthday'];
	$straining	= $settings['module_calendar']['training'];
	
	if ( defined('CACHE') )
	{
		$sCacheName = 'dsp_sn_minical';
		
		if ( ( $month_data = $oCache->readCache($sCacheName) ) === false )
		{
			$news = $event = $match = $birthday = $training = array();
			
			/* news */
			if ( $snews )
			{
				$sql = "SELECT n.news_id, n.news_title, n.news_intern, n.news_match, n.news_date, t.team_name, g.game_image
							FROM " . NEWS . " n
								LEFT JOIN " . MATCH . " m ON n.news_match = m.match_id
								LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE n.news_date < " . time() . " AND news_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(news_date), '%m.%Y') = '$cur_month.$cur_year'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$news[date('d', $row['news_date'])][] = $row;
				}
				$db->sql_freeresult($result);
			}
			
			/*event */
			if ( $sevent )
			{
				$sql = "SELECT event_id, event_date, event_duration, event_title, event_level FROM " . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%m.%Y') = '$cur_month.$cur_year'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$event[date('d', $row['event_date'])][] = $row;
				}
				$db->sql_freeresult($result);
			}
			
			/* match */
			if ( $smatch )
			{
				$sql = "SELECT m.match_id, m.match_rival_name, m.match_date, g.game_image, t.team_name
							FROM " . MATCH . " m
								LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%m.%Y') = '$cur_month.$cur_year'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$match[date('d', $row['match_date'])][] = $row;
				}
				$db->sql_freeresult($result);
			}
			
			/* birthday */
			if ( $sbirthday )
			{
				$sql = "SELECT user_id, user_name, user_birthday FROM " . USERS . " WHERE MONTH(user_birthday) = $cur_month";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$tmp = explode('-', $row['user_birthday']);
					
					$birthday[$tmp[2]][] = $row;
				}
				$db->sql_freeresult($result);
			}
			
			/* training */
			if ( $straining )
			{
				$sql = "SELECT tr.training_vs, tr.training_date, g.game_image, t.team_name
							FROM " . TRAINING . " tr
								LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id	
							WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%m.%Y') = '$cur_month.$cur_year'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$training[date('d', $row['training_date'])][] = $row;
				}
				$db->sql_freeresult($result);
			}
			
			$month_data = array_merge(
				array('news'		=> $news),
				array('event'		=> $event),
				array('match'		=> $match),
				array('birthday'	=> $birthday),
				array('training'	=> $training)
			);
			
			( $ctime != '' ) ? $oCache->writeCache($sCacheName, $month_data, (int) $ctime) : $oCache->writeCache($sCacheName, $month_data);
		}
	}
	else
	{
		$news = $event = $match = $birthday = $training = array();
		
		/* news */
		if ( $snews )
		{
			$sql = "SELECT n.news_id, n.news_title, n.news_intern, n.news_match, n.news_date, t.team_name, g.game_image
						FROM " . NEWS . " n
							LEFT JOIN " . MATCH . " m ON n.news_match = m.match_id
							LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
							LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					WHERE n.news_date < " . time() . " AND news_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(news_date), '%m.%Y') = '$cur_month.$cur_year'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$news[date('d', $row['news_date'])][] = $row;
			}
			$db->sql_freeresult($result);
		}
		
		/*event */
		if ( $sevent )
		{
			$sql = "SELECT event_id, event_date, event_duration, event_title, event_level FROM " . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%m.%Y') = '$cur_month.$cur_year'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$event[date('d', $row['event_date'])][] = $row;
			}
			$db->sql_freeresult($result);
		}
		
		/* match */
		if ( $smatch )
		{
			$sql = "SELECT m.match_id, m.match_rival_name, m.match_public, m.match_date, g.game_image, t.team_name
						FROM " . MATCH . " m
							LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
							LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%m.%Y') = '$cur_month.$cur_year'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$match[date('d', $row['match_date'])][] = $row;
			}
			$db->sql_freeresult($result);
		}
			
		/* birthday */
		if ( $sbirthday )
		{
			$sql = "SELECT user_id, user_name, user_birthday FROM " . USERS . " WHERE MONTH(user_birthday) = $cur_month";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$tmp = explode('-', $row['user_birthday']);
				
				$birthday[$tmp[2]][] = $row;
			}
			$db->sql_freeresult($result);
		}
		
		/* training */
		if ( $straining )
		{
			$sql = "SELECT tr.training_vs, tr.training_date, g.game_image, t.team_name
						FROM " . TRAINING . " tr
							LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
							LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id	
					WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%m.%Y') = '$cur_month.$cur_year'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$training[date('d', $row['training_date'])][] = $row;
			}
			$db->sql_freeresult($result);
		}
		/*
		for ( $i = 1; $i < $cur_days + 1; $i++ )
		{
			$i = ( $i < 10 ) ? "0$i" : $i;
			
			if ( $sbirthday )
			{
				$sql = "SELECT user_name, user_birthday, user_color
							FROM " . USERS . "
						WHERE MONTH(user_birthday) = $cur_month	AND DAYOFMONTH(user_birthday) = $i";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cur_monthonth_data_b[$i] = $db->sql_fetchrowset($result);
			}
			
			if ( $snews )
			{
				$sql = "SELECT n.news_id, n.news_title, n.news_intern, n.news_match, t.team_name, g.game_image
							FROM " . NEWS . " n
								LEFT JOIN " . MATCH . " m ON n.news_match = m.match_id
								LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
						WHERE n.news_date < " . time() . " AND news_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(news_date), '%d.%m.%Y') = '$i.$cur_month.$cur_year'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cur_monthonth_data_n[$i] = $db->sql_fetchrowset($result);
			}
			
			if ( $sevent )
			{
				$sql = "SELECT event_date, event_duration, event_title, event_level
							FROM " . EVENT . "
						WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$cur_month.$cur_year'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cur_monthonth_data_e[$i] = $db->sql_fetchrowset($result);
			}
			
			if ( $smatch )
			{
				$sql = "SELECT m.match_id, m.match_rival_name, m.match_public, m.match_date, g.game_image, t.team_name
							FROM " . MATCH . " m
								LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
						WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '$i.$cur_month.$cur_year'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cur_monthonth_data_m[$i] = $db->sql_fetchrowset($result);
			}
			
			if ( $straining )
			{
				$sql = "SELECT training_vs, training_date
							FROM " . TRAINING . "
						WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%d.%m.%Y') = '$i.$cur_month.$cur_year'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$month_data_t[$i] = $db->sql_fetchrowset($result);
			}
		}
		*/
		if ( $i == $cur_days + 1 )
		{
			$month_data = array_merge(
				array('news'		=> $news),
				array('event'		=> $event),
				array('match'		=> $match),
				array('birthday'	=> $birthday),
				array('training'	=> $training)
			);
			
			/*
			$month_data = array_merge(
				array('news'		=> $month_data_n),
				array('event'		=> $month_data_e),
				array('match'		=> $month_data_m),
				array('birthday'	=> $month_data_b),
				array('training'	=> $month_data_t)
			);
			*/
		}
	}
	
	$week_start = $settings['module_calendar']['start'];
	
	$edmk = $cur_weekdays[$cur_first];
	$wbmk = $cur_weekdays;
	for ( $i = 0; $i < $week_start; $i++ )
	{
		$wechsel = array_shift($wbmk);
		$wbmk[] = $wechsel;
	}
	$wbmk_wechsel = array_flip($wbmk);
	
	$days = '';
	for ( $i = 0; $i < 7; $i++ )
	{
		$days .= '<th>' . $wbmk[$i] . '</th>';
	}
	
	$day = '';
	
	if ( $settings['module_calendar']['compact'] )
	{
		$prev_day = ($prev_days - $wbmk_wechsel[$edmk]);
		
		for ( $i = $prev_day + 1; $i < $prev_days + 1; $i++ )
		{
			$day .= "<td><span>$i</span></td>";
		}
	}
	else
	{
		for ( $i = 0; $i < $wbmk_wechsel[$edmk]; $i++ )
		{
			$day .= '<td>&nbsp;</td>';
		}
	}

	$wcs = $wbmk_wechsel[$edmk];
	
	for ( $i = 1; $i < $cur_days + 1; $i++ )
	{
		$i = ( $i < 10 ) ? "0$i" : $i;
		
		$cur_news		= isset($month_data['news'][$i])		? $month_data['news'][$i] : false;
		$cur_event		= isset($month_data['event'][$i])		? $month_data['event'][$i] : false;
		$cur_match		= isset($month_data['match'][$i])		? $month_data['match'][$i] : false;
		$cur_birthday	= isset($month_data['birthday'][$i])	? $month_data['birthday'][$i] : false;
		$cur_training	= isset($month_data['training'][$i])	? $month_data['training'][$i] : false;
		
		if ( $i == $cur_day || is_array($cur_news) || is_array($cur_event) || is_array($cur_match) || is_array($cur_birthday) || is_array($cur_training) )
		{
			$count = 0;
			$style = '';
			$event = '';
			
			if ( $i == $cur_day )
			{
				$style = CAL_TODAY;
				$event .= sprintf($lang['stf_cal_today'], $lang['cal_today']);
				$count = $count + 1;
			}
			
			if ( is_array($cur_news) )
			{
				$action = array();
				
				foreach ( $cur_news as $row )
				{
					$action[] = $row['news_title'];
				}
				
				$style = CAL_NEWS;
				$event .= cal_string($event, false, $lang['cal_news'], $action, false);
				$count = $count + 1;
			}
			
			if ( is_array($cur_event) )
			{
				$action = array();
				
				foreach ( $cur_event as $row )
				{
					if ( $userdata['user_level'] >= $row['event_level'] )
					{
						$date	= $row['event_date'];
						$title	= $row['event_title'];
						$dura	= $row['event_duration'];
						
						$time_a	= create_date('H:i', $date, $userdata['user_timezone']);
						$time_b	= create_date('H:i', $dura, $userdata['user_timezone']);
						
						$diff	= ( $date == $dura ) ? "am gesamten Tag" : "von $time_a bis $time_b";
						
						$action[] = "$title: $diff";
					}
				}
				
				if ( !empty($action) )
				{
					$style = CAL_EVENT;
					$event .= cal_string($action, false, ( count($action) == '1' ) ? $lang['cal_event'] : $lang['cal_events'], $action, false);
					$count = $count + 1;
				}
			}
			
			if ( is_array($cur_match) )
			{
				$action = array();
				
				foreach ( $cur_match as $row )
				{
					$name = $row['match_rival_name'];
					$time = create_date('H:i', $row['match_date'], $config['default_timezone']);
					
					$action[] = sprintf($lang['sprintf_empty_line'], $name, $time);
				}
				
				$style = CAL_MATCH;
				$event .= cal_string($event, false, ( count($action) == '1' ) ? $lang['cal_match'] : $lang['cal_matchs'], $action, false);
				$count = $count + 1;
			}
			
			if ( is_array($cur_birthday) )
			{
				$action = array();
				
				foreach ( $cur_birthday as $row )
				{
					$alter	= 0;
					$gebdt	= explode("-", $row['user_birthday']);
					$gebdt	= $gebdt[0] . $gebdt[1] . $gebdt[2];
					$date	= mktime(0, 0, 0, $cur_month, 1, $cur_year);
					$now	= date("Ymd", $date);
					
					while ( $gebdt < $now - 9999 )
					{
						$alter++;
						$gebdt = $gebdt + 10000;
					}
	
					$action[] = sprintf($lang['cal_birth'], $row['user_name'], $alter);
				}
				
				$style  = CAL_BIRTHDAY;
				$event .= cal_string($event, false, ( count($action) == '1' ) ? $lang['cal_birthday'] : $lang['cal_birthdays'], $action, false);
				$count = $count + 1;
			}
			
			if ( is_array($cur_training) && $userdata['user_level'] >= TRIAL )
			{
					$action = array();
					
					foreach ( $cur_training as $row )
					{
						$name = $row['training_vs'];
						$time = create_date('H:i', $row['training_date'], $config['default_timezone']);
						
						$ary[] = sprintf($lang['sprintf_empty_line'], $name, $time);
					}
					
					$style = CAL_TRAINING;
					$event .= cal_string($event, false, ( count($event) == '1' ) ? $lang['cal_training'] : $lang['cal_trainings'], $action, false);
					$count = $count + 1;
			}
			
			if ( $count )
			{
				$class = ( $count > 1 ) ? 'more' : $style;

				$day .= "<td><a class=\"$class\" href=\"" . check_sid("calendar.php?mode=view") . "\" title=\"$event\">$i</a></td>";
			}
			else
			{
				$day .= "<td class=\"day\">$i</td>";
			}
		}
		else
		{
			$day .= "<td class=\"day\">$i</td>";
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
	
	if ( $settings['module_calendar']['compact'] )
	{
		$next_days = 42 - $cur_days - $wbmk_wechsel[$edmk];
		
		if ( $next_days > 7 )
		{
			$nday = $next_days - 7;
			
			if ( $nday < 0 )
			{
				for ( $next_day = 1; $next_day <= 7; $next_day++ )
				{
					$day .= "<td><span class=\"next\">$next_day</span>as3</td>";
				}
			}
			else
			{
				for ( $fordays = 1; $fordays < $nday+1; $fordays++ )
				{
					$day .= "<td><span class=\"next\">$fordays</span></td>";
				}
				
				$day .= '</tr><tr>';
				
				for ( $nextweek = $fordays; $nextweek < $next_days+1; $nextweek++ )
				{
					$day .= "<td><span class=\"next\">$nextweek</span></td>";
				}
				
			}
		}
		else
		{
			for ( $forward_day = 1; $forward_day < $next_days+1; $forward_day++ )
			{
				$day .= "<td><span class=\"next\">$forward_day</span></td>";
			}
		}
	}
	else
	{
		for ( $wcs; $wcs < 7; $wcs++ )
		{
			$day .= '<td>&nbsp;</td>';
		}
	}
	
	$template->set_filenames(array('minical' => 'navi_minical.tpl'));
	$template->assign_vars(array(
		'MINI_MONTH'	=> $months,
		'MINI_DAYS'		=> $days,
		'MINI_DAY'		=> $day,
		'MINI_CACHE'	=> ( $settings['module_calendar']['cache'] && defined('CACHE') ) ? display_cache($sCacheName, 1) : '',
	));
	$template->assign_var_from_handle('MINICAL', 'minical');
}

function display_server()
{
	global $db, $lang, $template;
	
	$template->set_filenames(array('server' => 'navi_server.tpl'));
	
	$sql	= "SELECT * FROM " . SERVER . " WHERE server_live = 1 ORDER BY server_order ASC";
	$data	= _cached($sql, 'dsp_server');
	
	if ( $data )
	{
		include_once('class_gameq.php');
		
		$ary = '';
		
		foreach ( $data as $keys => $row )
		{
			if ( $row['server_live'] == '1' )
			{
				$ary[] = array('id' => $row['server_name'], 'type' => $row['server_game'], 'host' => $row['server_ip'] . ':' . $row['server_port']);
			}
		}
		
		$gq = new GameQ();
		$gq->addServers($ary);
		$gq->setOption('timeout', 1); // Seconds
		$gq->setFilter('normalise');
		$gq_serv = $gq->requestData();
		$gq_data = cached_file($gq_serv, 'data_servers', 120);
	#	$gq_data = $gq->requestData();
	}
	
	if ( $data && $gq_data )
	{
		$cnt = count($data);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$id		= $data[$i]['server_id'];
			$name	= $data[$i]['server_name'];
			$game	= $data[$i]['server_game'];	
			$type	= $data[$i]['server_type'];
			
			$status	= isset($gq_data[$name]['gq_online']) ? $gq_data[$name]['gq_online'] ? $lang['server_online'] : $lang['server_offline'] : '';
			$player = isset($gq_data[$name]['gq_online']) ? sprintf($lang['cur_max'], $gq_data[$name]['gq_numplayers'], $gq_data[$name]['gq_maxplayers']) : '';
			
			$template->assign_block_vars('row', array(
				'NAME'		=> ( isset($gq_data[$name]['gq_hostname']) ) ? cut_string($gq_data[$name]['gq_hostname'], 20) : cut_string($data[$i]['server_name'], 20),
				'STATUS'	=> $status,
				'PLAYER'	=> $player,
				'IPPORT'	=> sprintf($lang['ip_port'], $gq_data[$name]['gq_address'], $gq_data[$name]['gq_port']),
				
				'L_PLAYER'	=> $type ? $lang['server_user'] : $lang['server_player'],
			));
			 
			if ( isset($gq_data[$name]['gq_mapname']) )
			{
				$game = in_array($game, array('cs16', 'cscz', 'css')) ? 'cs' : $game;
				
				$template->assign_block_vars('row._map', array(
					'MAP' => isset($lang['map_' . $game][$gq_data[$name]['gq_mapname']]) ? $lang['map_' . $game][$gq_data[$name]['gq_mapname']] : cut_string($gq_data[$name]['gq_mapname'], 15),
				));
			}
		}
		
		$template->assign_vars(array(
			'L_TITLE'	=> $lang['sn_server'],
			'L_STATUS'	=> $lang['server_status'],
			'L_MAP'		=> $lang['server_map'],
			
			'CACHE_SERVER' => defined('CACHE') ? display_cache('data_servers', 1) : '',
		));
	
		$template->assign_var_from_handle('SERVER', 'server');
	}
}

function display_next_match()
{
	global $db, $oCache, $settings, $template, $userdata, $lang;
	
	$ary	= '';
	$time	= time() - 86400;
	$month	= date("m", time());
	
#	debug($settings['module_next_match']['period']);
		
	$sql	= "SELECT m.match_id, m.match_rival_name, m.match_date, m.match_public, t.team_name, g.game_image
					FROM " . MATCH . " m
						LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE match_date > $time AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%m') = '$month' ORDER BY match_date";
	$data	= _cached($sql, 'dsp_sn_next_match');
	
	if ( $data )
	{
		foreach ( $data as $key => $row )
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
	
	if ( $ary )
	{
		$cnt = count($ary);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$id		= $ary[$i]['match_id'];
			$name	= $ary[$i]['team_name'];
			$image	= $ary[$i]['game_image'];
			
			$name = ( strlen($ary[$i]['match_rival_name']) < 10 ) ? $ary[$i]['match_rival_name'] : substr($ary[$i]['match_rival_name'], 0, 9) . ' ...';
			
			$template->assign_block_vars('sn_next_match_row', array(
				'GAME'	=>  display_gameicon($ary[$i]['game_image']),
				'NAME'	=> "<a href=\"" . check_sid("match.php?id=" . $id) . "\">$name</a>",
				'DATE'	=> create_date('d H:i', $ary[$i]['match_date'], $userdata['user_timezone']),
			));
		}
		
	}
	
	$template->set_filenames(array('next_match' => 'navi_next_match.tpl'));
	$template->assign_vars(array('CACHE' => ( defined('CACHE') && $settings['module_next_match']['cache'] ) ? display_cache('dsp_sn_next_match', 1) : ''));
	$template->assign_var_from_handle('NEXT_MATCH', 'next_match');
}

function display_next_training()
{
	global $db, $oCache, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['module_next_training'] && $userdata['user_level'] >= TRIAL )
	{
		$time	= time() - 86400;
		$month	= date("m", time());
		
		$sql = "SELECT tr.*, t.team_name, g.game_image
					FROM " . TRAINING . "  tr
						LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE training_date > $time AND DATE_FORMAT(FROM_UNIXTIME(training_date), '%m') = '$month' ORDER BY training_date";
	#	if ( !($result = $db->sql_query($sql)) )
	#	{
	#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	#	}
	#	$train = $db->sql_fetchrowset($result);
		$data = _cached($sql, 'dsp_sn_next_training');
		
	#	debug($data);
		
		if ( $data )
		{
			$cnt = count($data);
			
			for ( $i = 0; $i < $cnt; $i++ )
			{
				$id		= $data[$i]['training_id'];
				$name	= $data[$i]['team_name'];
				$image	= $data[$i]['game_image'];
				
				$name = ( strlen($data[$i]['training_vs']) < 10 ) ? $data[$i]['training_vs'] : substr($data[$i]['training_vs'], 0, 9) . ' ...';
				
				$template->assign_block_vars('sn_next_training_row', array(
					'GAME'	=>  display_gameicon($data[$i]['game_image']),
					'NAME'	=> "<a href=\"" . check_sid("training.php?id=" . $id) . "\">$name</a>",
					'DATE'	=> create_date('d H:i', $data[$i]['training_date'], $userdata['user_timezone']),
				));
				
		#		$team_name	= $train[$i]['team_name'];
		#		$game_size	= $train[$i]['game_size'];
		#		$game_image	= $train[$i]['game_image'];
		#		
		#		$game = "<img src=\"$root_path" . $settings['path_games'] . "/$game_image\" alt=\"$team_name\" title=\"$team_name\" width=\"$game_size\" >";
		#		$name = ( strlen($train[$i]['training_vs']) < 10 ) ? $train[$i]['training_vs'] : substr($train[$i]['training_vs'], 0, 9) . '...';
		
		#		$template->assign_block_vars('sn_next_training_row', array(
		#			'GAME'	=> $game,
		#			'NAME'	=> "<a href=\"" . check_sid('training.php?id=' . $train[$i]['training_id']) . "\">$name</a>",
		#			'DATE'	=> create_date('d H:i', $train[$i]['training_date'], $userdata['user_timezone']),
		#		));
			}
		}
		
		$template->set_filenames(array('next_training' => 'navi_next_training.tpl'));
		$template->assign_vars(array('CACHE' => ( defined('CACHE') && $settings['module_next_training']['cache'] ) ? display_cache('dsp_sn_next_training', 1) : ''));
		$template->assign_var_from_handle('NEXT_TRAINING', 'next_training');
	}
}

function display_cache($name, $type = '')
{
	global $oCache, $userdata, $lang;
	
	if ( defined('CACHE') )
	{
		$time	= $oCache->readCacheTime($name);
		$type	= ( $type == '1' ) ? $lang['cache_valid'] : $lang['cache_duration'] ;
		$msg	= sprintf($type, create_date($userdata['user_dateformat'], $time, $userdata['user_timezone']));
		$str	= "<img class=\"icon\" src=\"images/icon-rss.png\" title=\"$msg\" alt=\"$msg\">";
		
		return $str;
	}
}

function display_gameicon($image)
{
	global $root_path, $settings;
	
	$return	= '<img class="icon" src="' . $root_path . $settings['path_games'] . '/' . $image . '" alt="' . $image . '" title="' . $image . '" width="16" height="16">';

	return $return;
}

?>