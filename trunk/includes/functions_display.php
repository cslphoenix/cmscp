<?php

//
//	Cache G�ltigkeit
//
if (defined('CACHE'))
{
	function display_cache($name, $type='')
	{
		global $oCache, $userdata, $lang;
		
		$time = $oCache -> readCacheTime($name);
		
		$type = ($type == '1') ? $lang['cache_valid'] : $lang['cache_duration'] ;
		
		$message = sprintf($type, create_date($userdata['user_dateformat'], $time, $userdata['user_timezone']));
		
		$string = '<img src="images/icon-rss.png" title="' . $message . '" alt="' . $message . '">';
		
		return $string;
	}
}

//
//	Navi Teams
//
function display_teams()
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_teams'] )
	{
		$template->assign_block_vars('teams', array());
	
		$sql = 'SELECT t.team_id, t.team_name, t.team_fight, g.game_size, g.game_image
					FROM ' . TEAMS_TABLE . ' t, ' . GAMES_TABLE . ' g
					WHERE t.team_game = g.game_id AND team_navi = 1
				ORDER BY t.team_order';
		$teams = _cached($sql, 'subnavi_teams');
		
		if ($teams)
		{
			for ($i = 0; $i < count($teams); $i++)
			{
				$template->assign_block_vars('teams.teams_row', array(
					'TEAM_GAME'		=> display_gameicon($teams[$i]['game_size'], $teams[$i]['game_image']),
					'TEAM_NAME'		=> $teams[$i]['team_name'],
					'TO_TEAM'		=> append_sid("teams.php?mode=show&amp;" . POST_TEAMS_URL . "=".$teams[$i]['team_id']),
				));
			}
		}
		
		$template->assign_vars(array(
			'L_TEAMS'		=> $lang['teams'],
			'L_TO_TEAM'		=> $lang['to_team'],
		));
		
	}
	return;
}

//
//	Neuste Mitglieder
//
function display_newusers()
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_newusers'] )
	{
		$template->assign_block_vars('new_users', array());
	}
	
	$sql = 'SELECT user_id, username FROM ' . USERS_TABLE . ' WHERE user_id != -1 AND user_active = 1 ORDER BY user_regdate LIMIT 0, ' . $settings['subnavi_newusers_limit'];
	$users = _cached($sql, 'new_users', 0, 1800);
	
	for ($i = 0; $i < count($users); $i++)
	{
		$template->assign_block_vars('new_users.user_row', array(
			'USERNAME'		=> $users[$i]['username'],
			'U_USERNAME'	=> append_sid("profile.php?mode=views&amp;" . POST_USERS_URL . "=".$users[$i]['user_id']),
		));
	}
	
	$template->assign_vars(array('NEW_USERS_CACHE' => (defined('CACHE')) ? display_cache('new_users', 1) : ''));
	
	return;
}

//
//	Teambilder
//
function display_gameicon($game_size, $game_image)
{
	global $root_path, $settings;
	
	$image	= '<img src="' . $root_path . $settings['game_path'] . '/' . $game_image . '" alt="' . $game_image . '" title="' . $game_image . '" width="' . $game_size . '" height="' . $game_size . '" >';

	return $image;
}

//
//	Minikalender
//
function display_minical()
{
	global $db, $oCache, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_minical'] )
	{
		$template->assign_block_vars('minical', array());
	}

	$tag			= date("d", time());	//	Heutiger Tag: z. B. "1"
	$tag_der_woche	= date("w");			//	Welcher Tag in der Woch: z. B. "0 / Sonntag"
	$tage_im_monat	= date("t");			//	Anzahl der Tage im Monat: z. B. "31"
	$monat			= date("m", time());	//	Heutiger Monat
	$jahr			= date("Y", time());	//	Heutiges Jahr
	$erster			= date("w", mktime(0, 0, 0, $monat, 1, $jahr));		//	Der erste Tag im Monat: z. B. "5 / Freitag"
	$arr_woche_kurz	= array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');	//	Wochentage gek�rzt
	
	$monate = array(
		'01'	=> 'Januar',
		'02'	=> 'Feber',
		'03'	=> 'M&auml;rz',
		'04'	=> 'April',
		'05'	=> 'Mai',
		'06'	=> 'Juni',
		'07'	=> 'Juli',
		'08'	=> 'August',
		'09'	=> 'September',
		'10'	=> 'Oktober',
		'11'	=> 'November',
		'12'	=> 'Dezember'
	);
	
	$month = $monate[$monat];
	
	if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		if (defined('CACHE'))
		{
			$sCacheName = 'kalender_' . $monat . '_member';
	
			if (($monat_data = $oCache -> readCache($sCacheName)) === false)
			{
				for ( $i=1; $i<$tage_im_monat+1; $i++ )
				{
					if ($i < 10)
					{
						$i = '0'.$i;
					}
					
					$sql = 'SELECT username, user_birthday FROM ' . USERS_TABLE . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
					$result = $db->sql_query($sql);
					$day_rows_b = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					$sql = 'SELECT event_start, event_end, event_title FROM ' . EVENTS_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_start), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
					$result = $db->sql_query($sql);
					$day_rows_e = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					$sql = 'SELECT match_rival, match_date FROM ' . MATCH_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
					$result = $db->sql_query($sql);
					$day_rows_w = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					$sql = 'SELECT training_vs, training_start FROM ' . TRAINING_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_start), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
					$result = $db->sql_query($sql);
					$day_rows_t = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					$monat_data_b[$i] = $day_rows_b;
					$monat_data_e[$i] = $day_rows_e;
					$monat_data_w[$i] = $day_rows_w;
					$monat_data_t[$i] = $day_rows_t;
				}
				
				if ( $i == $tage_im_monat+1 )
				{
					$monat_data = array_merge(array($monat_data_b), array($monat_data_e), array($monat_data_w), array($monat_data_t));
					$oCache -> writeCache($sCacheName, $monat_data);
				}
			}
		}
		else
		{
			for ( $i=1; $i<$tage_im_monat+1; $i++ )
			{
				if ($i < 10)
				{
					$i = '0'.$i;
				}
				
				$sql = 'SELECT username, user_birthday FROM ' . USERS_TABLE . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
				$result = $db->sql_query($sql);
				$day_rows_b = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT event_start, event_end, event_title FROM ' . EVENTS_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_start), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
				$result = $db->sql_query($sql);
				$day_rows_e = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT match_rival, match_date FROM ' . MATCH_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
				$result = $db->sql_query($sql);
				$day_rows_w = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT training_vs, training_start FROM ' . TRAINING_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_start), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
				$result = $db->sql_query($sql);
				$day_rows_t = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$monat_data_b[$i] = $day_rows_b;
				$monat_data_e[$i] = $day_rows_e;
				$monat_data_w[$i] = $day_rows_w;
				$monat_data_t[$i] = $day_rows_t;
			}
			
			if ( $i == $tage_im_monat+1 )
			{
				$monat_data = array_merge(array($monat_data_b), array($monat_data_e), array($monat_data_w), array($monat_data_t));
			}
		}
		
		$monat_data_b = array_slice($monat_data, 0, 1);
		$monat_data_e = array_slice($monat_data, 1, 1);
		$monat_data_w = array_slice($monat_data, 2, 1);
		$monat_data_t = array_slice($monat_data, 3, 3);
		
		foreach ($monat_data_b as $monat_birthday) {}
		foreach ($monat_data_e as $monat_events) {}
		foreach ($monat_data_w as $monat_matchs) {}
		foreach ($monat_data_t as $monat_trainings) {}
	}
	else
	{
		if (defined('CACHE'))
		{
			$sCacheName = 'kalender_' . $monat . '_guest';
	
			if (($monat_data = $oCache -> readCache($sCacheName)) === false)
			{
				for ( $i=1; $i<$tage_im_monat+1; $i++ )
				{
					if ($i < 10)
					{
						$i = '0'.$i;
					}
					
					$sql = 'SELECT username, user_birthday FROM ' . USERS_TABLE . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
					$result = $db->sql_query($sql);
					$day_rows_b = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					$sql = 'SELECT event_start, event_end, event_title FROM ' . EVENTS_TABLE . " WHERE event_type = 0 AND DATE_FORMAT(FROM_UNIXTIME(event_start), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
					$result = $db->sql_query($sql);
					$day_rows_e = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					$sql = 'SELECT * FROM ' . MATCH_TABLE . " WHERE match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
					$result = $db->sql_query($sql);
					$day_rows_w = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
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
			for ( $i=1; $i<$tage_im_monat+1; $i++ )
			{
				if ($i < 10)
				{
					$i = '0'.$i;
				}
				
				$sql = 'SELECT username, user_birthday FROM ' . USERS_TABLE . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
				$result = $db->sql_query($sql);
				$day_rows_b = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT event_start, event_end, event_title FROM ' . EVENTS_TABLE . " WHERE event_type = 0 AND DATE_FORMAT(FROM_UNIXTIME(event_start), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
				$result = $db->sql_query($sql);
				$day_rows_e = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT * FROM ' . MATCH_TABLE . " WHERE match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
				$result = $db->sql_query($sql);
				$day_rows_w = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$monat_data_b[$i] = $day_rows_b;
				$monat_data_e[$i] = $day_rows_e;
				$monat_data_w[$i] = $day_rows_w;
			}
			
			if ( $i == $tage_im_monat+1 )
			{
				$monat_data = array_merge(array($monat_data_b), array($monat_data_e), array($monat_data_w));
			}
		}
		
		$monat_data_b = array_slice($monat_data, 0, 1);
		$monat_data_e = array_slice($monat_data, 1, 1);
		$monat_data_w = array_slice($monat_data, 2, 1);
		
		foreach ($monat_data_b as $monat_birthday) {}
		foreach ($monat_data_e as $monat_events) {}
		foreach ($monat_data_w as $monat_matchs) {}
	}
	
	// wochenstart
	// 0=Sonntag; 1=Montag; 2=Dienstag; 3=Mittwoch; 4=Donnerstag; 5=Freitag; 6=Samstag
	$ws = 1;
	// "woche beginnt mit" - array verschiebung
	$edmk = $arr_woche_kurz[$erster];
	$wbmk = $arr_woche_kurz;
	for ( $i=0; $i<$ws; $i++ )
	{
		$wechsel = array_shift($wbmk);
		$wbmk[] = $wechsel;
	}
	$wbmk_wechsel = array_flip($wbmk);
	
	$days = '';
	for ( $i=0; $i<7; $i++ )
	{
		$days .= '<td align="center" width="14%"><b>' . $wbmk[$i] . '</b></td>';
	}
	
	// berechnung der monatstabelle
	// zuerst die unbenutzten tage
	$day = '';
	for ( $i=0; $i<$wbmk_wechsel[$edmk]; $i++)
	{
		$day .= '<td align="center" width="14%">&nbsp;</td>';
	}
	// ab hier benutzte tage
	$wcs = $wbmk_wechsel[$edmk];
	for ( $i=1; $i<$tage_im_monat+1; $i++ )
	{
		if ($i < 10)
		{
			$i = '0'.$i;
		}
		
		if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
		{
			if ($i == $tag || is_array($monat_birthday[$i]) || is_array($monat_events[$i]) || is_array($monat_matchs[$i]) || is_array($monat_trainings[$i]))
			{
				$day_event = '';
				$day_class = '';
				$day_event_num = '0';
				
				if ( $i == $tag )
				{
					$day_event		= '<span><em class="today">' . $lang['cal_today'] . '</em><br />';
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
					$list			= implode('<br />', $list);	
					$day_event		.= (empty($day_event)) ? '<span><em class="birthday">' . $language . '</em><br />' . $list : '<br /><em class="birthday">' . $language . '</em><br />' . $list;
					$day_class		= 'birthday';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_events[$i]) )
				{
					$list = array();
					for ( $k=0; $k < count($monat_events[$i]); $k++ )
					{
						$list[] = $monat_events[$i][$k]['event_title'];
					}
					
					$language		= (count($list) == 1) ? $lang['cal_event'] : $lang['cal_events'];
					$list			= implode('<br />', $list);
					$day_event		.= (empty($day_event)) ? '<span><em class="events">' . $language . '</em><br />' . $list : '<br /><em class="events">' . $language . '</em><br />' . $list;
					$day_class		= 'events';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_matchs[$i]) )
				{
					$list = array();
					for ( $k=0; $k < count($monat_matchs[$i]); $k++ )
					{
						$list[] = $monat_matchs[$i][$k]['match_rival'];
					}
					
					$language		= (count($list) == 1) ? $lang['cal_match'] : $lang['cal_matchs'];
					$list			= implode('<br />', $list);
					$day_event		.= (empty($day_event)) ? '<span><em class="wars">' . $language . '</em><br />' . $list : '<br /><em class="wars">' . $language . '</em><br />' . $list;
					$day_class		= 'wars';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_trainings[$i]) )
				{
					$list = array();
					for ( $k=0; $k < count($monat_trainings[$i]); $k++ )
					{
						$list[] = $monat_trainings[$i][$k]['training_vs'];
					}
					
					$language		= (count($list) == 1) ? $lang['cal_training'] : $lang['cal_trainings'];
					$list			= implode('<br />', $list);
					$day_event		.= (empty($day_event)) ? '<span><em class="trains">' . $language . '</em><br />' . $list : '<br /><em class="trains">' . $language . '</em><br />' . $list;
					$day_class		= 'trains';
					$day_event_num	= $day_event_num + 1;
				}
				
				$class = ( $day_event_num > 1 ) ? 'more' : $day_class;
				$day .= '<td align="center" width="14%" class="' . $class . '"><a class="' . $class . '" href="' . append_sid("calendar.php#" . $i ) . '">' . $i . '' . $day_event . '</span></a></td>';
				
			}
			else
			{
				$day .= '<td align="center" width="14%" class="day">' . $i . '</td>';
			}
		}
		else
		{
			if ($i == $tag || is_array($monat_birthday[$i]) || is_array($monat_events[$i]) || is_array($monat_matchs[$i]))
			{
				$day_event = '';
				$day_class = '';
				$day_event_num = '0';
				
				if ( $i == $tag )
				{
					$day_event		= '<span><em class="today">' . $lang['cal_today'] . '</em><br />';
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
					$list			= implode('<br />', $list);	
					$day_event		.= (empty($day_event)) ? '<span><em class="birthday">' . $language . '</em><br />' . $list : '<br /><em class="birthday">' . $language . '</em><br />' . $list;
					$day_class		= 'birthday';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_events[$i]) )
				{
					$list = array();
					for ( $k=0; $k < count($monat_events[$i]); $k++ )
					{
						$list[] = $monat_events[$i][$k]['event_title'];
					}
					
					$language		= (count($list) == 1) ? $lang['cal_event'] : $lang['cal_events'];
					$list			= implode('<br />', $list);
					$day_event		.= (empty($day_event)) ? '<span><em class="events">' . $language . '</em><br />' . $list : '<br /><em class="events">' . $language . '</em><br />' . $list;
					$day_class		= 'events';
					$day_event_num	= $day_event_num + 1;
				}
				
				if ( is_array($monat_matchs[$i]) )
				{
					$list = array();
					for ( $k=0; $k < count($monat_matchs[$i]); $k++ )
					{
						$list[] = $monat_matchs[$i][$k]['match_rival'];
					}
					
					$language		= (count($list) == 1) ? $lang['cal_match'] : $lang['cal_matchs'];
					$list			= implode('<br />', $list);
					$day_event		.= (empty($day_event)) ? '<span><em class="wars">' . $language . '</em><br />' . $list : '<br /><em class="wars">' . $language . '</em><br />' . $list;
					$day_class		= 'wars';
					$day_event_num	= $day_event_num + 1;
				}
				
				$class = ( $day_event_num > 1 ) ? 'more' : $day_class;
				$day .= '<td align="center" width="14%" class="' . $class . '"><a class="' . $class . '" href="' . append_sid("calendar.php#" . $i ) . '">' . $i . '' . $day_event . '</span></a></td>';
				
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

//
//	Next Wars
//
function display_navimatch()
{
	global $db, $oCache, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_matches'] )
	{
		$template->assign_block_vars('match', array());
	}

	$time = time() - 86400;
	$monat = date("m", time());	//	Heutiger Monat
	
	if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		$cache = 'list_kalender_' . $monat . '_match_member';
		$sql = 'SELECT * FROM ' . MATCH_TABLE . ' WHERE match_date > ' . $time . " AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%m') = '".$monat."' ORDER BY match_date";
		$month_rows_w = _cached($sql, $cache);
	}
	else
	{
		$cache = 'list_kalender_' . $monat . '_match_guest';
		$sql = 'SELECT * FROM ' . MATCH_TABLE . ' WHERE match_date > ' . $time . " AND match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%m') = '".$monat."' ORDER BY match_date";
		$month_rows_w = _cached($sql, $cache);
	}
	
	if ($month_rows_w)
	{
		for($k=0; $k< count($month_rows_w); $k++ )
		{
			$details = (strlen($month_rows_w[$k]['match_rival']) < 15) ? $month_rows_w[$k]['match_rival'] : substr($month_rows_w[$k]['match_rival'], 0, 12) . ' ...';
			$date = create_date('d H:i', $month_rows_w[$k]['match_date'], $userdata['user_timezone']);
			
			$template->assign_block_vars('match.match_row', array(
				'NAME'	=> $details,
				'DATE'	=> $date,
			));
		}
	}
}	

//
//	Next Trainings (nur f�r Trails, Member, Admins Clanstatus!)
//
function display_navitrain()
{
	global $db, $oCache, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_training'] && ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN) )
	{
		$template->assign_block_vars('training', array());
	}

	$time = time() - 86400;
	$monat = date("m", time());	//	Heutiger Monat
	
	$cache = 'list_kalender_' . $monat . '_training';
	$sql = 'SELECT * FROM ' . TRAINING_TABLE . ' WHERE training_start > ' . $time . " AND DATE_FORMAT(FROM_UNIXTIME(training_start), '%m') = '".$monat."' ORDER BY training_start";
	$month_rows_t = _cached($sql, $cache);
	
	if ($month_rows_t)
	{
		for ( $k=0; $k< count($month_rows_t); $k++ )
		{
			$details = (strlen($month_rows_t[$k]['training_vs']) < 15) ? $month_rows_t[$k]['training_vs'] : substr($month_rows_t[$k]['training_vs'], 0, 12) . ' ...';
	
			$date = create_date('d H:i', $month_rows_t[$k]['training_start'], $userdata['user_timezone']);
			
			$template->assign_block_vars('training.training_row', array(
				'NAME'	=> $details,
				'DATE'	=> $date,
				
			));
		}
	}
}

//
//	Navi
//
function display_navi()
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $userdata['session_logged_in'] )
	{
		$template->assign_block_vars('navi_user', array());
		
		$sql = 'SELECT * FROM ' . NAVI_TABLE . ' WHERE navi_show = 1';
		
		$width = 476;
	}
	else
	{
		$sql = 'SELECT * FROM ' . NAVI_TABLE . ' WHERE navi_show = 1 AND navi_intern != 1 AND navi_type != ' . NAVI_USER;
		
		$width = 578;
	}
	$result = $db->sql_query($sql);
	
	$template->assign_block_vars('navi_main', array());
	$template->assign_block_vars('navi_clan', array());
	$template->assign_block_vars('navi_community', array());
	$template->assign_block_vars('navi_misc', array());
	
	while ( $navi = $db->sql_fetchrow($result) )
	{
		switch ($navi['navi_type'])
		{
			case NAVI_MAIN:
				$row_type = 'navi_main.navi_main_row';
			break;
			case NAVI_CLAN:
				$row_type = 'navi_clan.navi_clan_row';
			break;
			case NAVI_COM:
				$row_type = 'navi_community.navi_community_row';
			break;
			case NAVI_MISC:
				$row_type = 'navi_misc.navi_misc_row';
			break;
			case NAVI_USER:
				$row_type = 'navi_user.navi_user_row';
			break;
		}
		
		switch ($navi['navi_target'])
		{
			case 0:
				$navi_target = '_self';
			break;
			case 1:
				$navi_target = '_blank';
			break;
		}
		
		$navi_lang = ($navi['navi_lang']) ? $lang[$navi['navi_name']] : $navi['navi_name'];

		$template->assign_block_vars($row_type, array(
			'NAVI_NAME'		=> $navi_lang,
			'NAVI_URL'		=> $navi['navi_url'],
			'NAVI_TARGET'	=> $navi_target,
		));
	}

	$template->assign_vars(array(
		'WIDTH'		=> $width,
	));
	
}

//
//	Last Matches
//
function display_last_matches()
{
	global $db, $root_path, $oCache, $settings, $template, $userdata, $lang;
	
	if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		$sql = 'SELECT m.*, md.*, t.team_name, g.game_image, g.game_size, tr.training_id
					FROM ' . MATCH_TABLE . ' m
						LEFT JOIN ' . MATCH_DETAILS_TABLE . ' md ON m.match_id = md.match_id
						LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON m.match_id = tr.match_id
					WHERE m.match_date < ' . time() . '
				ORDER BY m.match_date ASC LIMIT 0,' . $settings['last_matches'];
		$match_last = _cached($sql, 'head_match_member');
	}
	else
	{
		$sql = 'SELECT m.*, md.*, t.team_name, g.game_image, g.game_size, tr.training_id
					FROM ' . MATCH_TABLE . ' m
						LEFT JOIN ' . MATCH_DETAILS_TABLE . ' md ON m.match_id = md.match_id
						LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON m.match_id = tr.match_id
					WHERE m.match_date < ' . time() . ' AND m.match_public = 1
				ORDER BY m.match_date ASC LIMIT 0,' . $settings['last_matches'];
		$match_last = _cached($sql, 'head_match_guest');
	}
	
	if (!$match_last)
	{
		$template->assign_block_vars('no_entry_last', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = 0; $i < count($match_last); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$clan	= $match_last[$i]['details_mapa_clan'] + $match_last[$i]['details_mapb_clan'];
			$rival	= $match_last[$i]['details_mapa_rival'] + $match_last[$i]['details_mapb_rival'];
			
			if		($clan > $rival) $class_result = 'win';
			else if	($clan < $rival) $class_result = 'lose';
			else if	($clan = $rival) $class_result = 'draw';
			else	$class_result = '';
			
			$match_rival = substr($match_last[$i]['match_rival'], 0, 15) . ' ...';
			
			$game_size	= $match_last[$i]['game_size'];
			$game_image	= '<img src="' . $root_path . $settings['game_path'] . '/' . $match_last[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
			
			$match_name	= ($match_last[$i]['match_public']) ? 'vs. ' . $match_rival : 'vs. <span style="font-style:italic;">' . $match_rival . '</span>';
			
			$template->assign_block_vars('match_row', array(
				'CLASS' 		=> $class,
				'CLASS_RESULT'	=> $class_result,
				'MATCH_GAME'	=> $game_image,
				'MATCH_NAME'	=> $match_name,
				'MATCH_RESULT'	=> $clan . ':' . $rival,
				'U_DETAILS'		=> append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=".$match_last[$i]['match_id']),
			));
		}
	}
	
	$template->assign_vars(array(
		'L_DETAILS'		=> $lang['match_details'],
		'L_LAST_MATCH'	=> $lang['last_matches'],
	));
	
	return;
}

?>