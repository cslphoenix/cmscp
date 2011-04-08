<?php

#	beim Event fehlt die Lang

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_CALENDAR);
init_userprefs($userdata);

$page_title = $lang['head_calendar'];

include($root_path . 'includes/page_header.php');

$year	= request('year', 1);
$month	= request('month', 1);

if ( $month && $year )
{
	$tag	= '';
	$jahr	= ( $year ) ? $year : date('Y');
	$monat	= ( $month ) ? $month : date('m');
	$monat	= ( $monat < 10 ) ? '0' . $monat : $monat;
	$tage	= date("t", mktime(0, 0, 0, $monat, 1, $jahr));
	$erster	= date("w", mktime(0, 0, 0, $monat, 1, $jahr));
}
else
{
	$tage	= date("t");
	$tag	= date("d", time());
	$jahr	= date("Y", time());
	$monat	= date("m", time());
	$erster	= date("w", mktime(0, 0, 0, $monat, 1, $jahr));
}

$template->set_filenames(array('body' => 'body_calendar.tpl'));

$tag_der_woche	= date("w"); // Welcher Tag in der Woch: z. B. "0 / Sonntag"
$arr_woche_kurz	= array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
	
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

function leereentfernen($array)
{
	$rueckgabe = array();
	
	for ( $i = 0; $i <= count($array) - 1; $i++ )
	{
		if ( $array[$i] != "" )
		{
			array_push($rueckgabe, $array[$i]);
		}
	}
	
	return $rueckgabe;
}

if ( $userdata['user_level'] >= TRIAL )
{
	if ( defined('CACHE') )
	{
		$sCacheName = 'calendar_' . $monat . '_member';

		if ( ($monat_data = $oCache -> readCache($sCacheName)) === false )
		{
			for ( $i = 1; $i < $tage + 1; $i++ )
			{
				if ( $i < 10 ) { $i = '0' . $i; }
				
				$sql = 'SELECT user_id, username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_b = $db->sql_fetchrowset($result);
				
				$sql = 'SELECT event_id, event_date, event_duration, event_title, event_level FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_e = $db->sql_fetchrowset($result);
				
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
				
				$sql = "SELECT training_id, training_vs, training_date, g.game_image, g.game_size, t.team_name
						FROM " . TRAINING . " tr
							LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
							LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id	
					WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
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
			
			if ( $i == $tage+1 )
			{
				$monat_data = array_merge(array($monat_data_b), array($monat_data_e), array($monat_data_w), array($monat_data_t));
				$oCache -> writeCache($sCacheName, $monat_data);
			}
		}
	}
	else
	{
		for ( $i = 1; $i < $tage + 1; $i++ )
		{
			$i = ( $i < 10 ) ? '0' . $i : $i;
			
			$sql = 'SELECT user_id, username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_b = $db->sql_fetchrowset($result);
			
			$sql = 'SELECT event_id, event_date, event_duration, event_title, event_level FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_e = $db->sql_fetchrowset($result);
			
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
			
			$sql = "SELECT training_id, training_vs, training_date, g.game_image, g.game_size, t.team_name
						FROM " . TRAINING . " tr
							LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
							LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id	
					WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
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
		
		if ( $i == $tage + 1 )
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
		$sCacheName = 'calendar_' . $monat . '_guest';

		if ( ($monat_data = $oCache -> readCache($sCacheName)) === false )
		{
			for ( $i = 1; $i < $tage + 1; $i++ )
			{
				$i = ( $i < 10 ) ? '0' . $i : $i;
				
				$sql = 'SELECT user_id, username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_b = $db->sql_fetchrowset($result);
				
				$sql = 'SELECT event_id, event_date, event_duration, event_title, event_level FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_e = $db->sql_fetchrowset($result);
				
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
			
			if ( $i == $tage + 1 )
			{
				$monat_data = array_merge(array($monat_data_b), array($monat_data_e), array($monat_data_w));
				$oCache -> writeCache($sCacheName, $monat_data);
			}
		}
	}
	else
	{
		for ( $i=1; $i<$tage+1; $i++ )
		{
			if ($i < 10)
			{
				$i = '0'.$i;
			}
			
			$sql = 'SELECT user_id, username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_b = $db->sql_fetchrowset($result);
			
			$sql = 'SELECT event_id, event_date, event_duration, event_title, event_level FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_e = $db->sql_fetchrowset($result);
			
			
			
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
		
		if ( $i == $tage+1 )
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

$cal_day = '';
$cal_days = '';

for ( $i = 1; $i < $tage + 1; $i++ )
{
	$cal_weekday = $arr_woche_kurz[date("w", mktime(0, 0, 0, $monat, $i, $jahr))];
	
	$i = ( $i < 10 ) ? '0' . $i : $i;
	
	if ( $userdata['user_level'] >= TRIAL )
	{
		if ( $i == $tag || is_array($monat_birthday[$i]) || is_array($monat_events[$i]) || is_array($monat_matchs[$i]) || is_array($monat_trainings[$i]) )
		{
			$cal_event = '';
			
			if ( $i == $tag )
			{
				$cal_day	= '<span class="today">' . $i . '</span>';
				$cal_event	= '';
			}
			
			if ( is_array($monat_birthday[$i]) )
			{
				$list = array();
				for ( $k = 0; $k < count($monat_birthday[$i]); $k++ )
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
				$cal_day		= $i;
				$cal_event		.= (empty($cal_event)) ? '<span><em class="birthday">' . $language . ':</em> ' . $list : '<br><em class="birthday">' . $language . '</em><br>' . $list;
			}
			
			if ( is_array($monat_events[$i]) )
			{
				$list = array();
				
				for ( $k = 0; $k < count($monat_events[$i]); $k++ )
				{
					$data = array();
					
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
				
				$language		= ( count($list) == 1 ) ? $lang['cal_event'] : $lang['cal_events'];
				$list			= implode(', ', $list);
				$cal_day		= $i;
				$cal_event		.= (empty($cal_event)) ? '<span><em class="events">' . $language . ':</em> ' . $list : '<br><em class="events">' . $language . '</em><br>' . $list;
			}
			
		#	if ( is_array($monat_events[$i]) )
		#	{
		#		$list = array();
		#		for ( $k = 0; $k < count($monat_events[$i]); $k++ )
		#		{
		#			$list[] = $monat_events[$i][$k]['event_title'];
		#		}
		#		
		#		$language		= (count($list) == 1) ? $lang['cal_event'] : $lang['cal_events'];
		#		$list			= implode('<br>', $list);
		#		$cal_day		= $i;
		#		$cal_event		.= (empty($cal_event)) ? '<span><em class="events">' . $language . ':</em> ' . $list : '<br><em class="events">' . $language . '</em><br>' . $list;
		#	}
			
			if ( is_array($monat_matchs[$i]) )
			{
				$list = array();
				for ( $k = 0; $k < count($monat_matchs[$i]); $k++ )
				{
					$match_id	= $monat_matchs[$i][$k]['match_id'];
					$match_vs	= $monat_matchs[$i][$k]['match_rival'];
					$match_time	= create_date('H:i', $monat_matchs[$i][$k]['match_date'], $config['page_timezone']);
					$team_name	= $monat_matchs[$i][$k]['team_name'];
					$game_size	= $monat_matchs[$i][$k]['game_size'];
					$game_image	= $monat_matchs[$i][$k]['game_image'];
					$team_image	= "<img src=\"$root_path" . $settings['path_games'] . "/$game_image\" alt=\"$team_name\" title=\"$team_name\" width=\"$game_size\" align=\"middle\">";
					
					$list[] = $team_image . " <a href=\"match.php?mode=details&" . POST_MATCH_URL . "=$match_id\">$match_vs - $match_time</a>";
				}
				
				$language	= ( count($list) == 1 ) ? $lang['cal_match'] : $lang['cal_matchs'];
				$list		= implode(', ', $list);
				$cal_day	= $i;
				$cal_event	.= ( empty($cal_event) ) ? "<p><span><em class=\"wars\">$language:</em>$list</p>" : "<br /><p><em class=\"wars\">$language</em><br />$list</p>";
			}
			
			if ( is_array($monat_trainings[$i]) )
			{
				$list = array();
				for ( $k = 0; $k < count($monat_trainings[$i]); $k++ )
				{
					$training_id	= $monat_trainings[$i][$k]['training_id'];
					$training_vs	= $monat_trainings[$i][$k]['training_vs'];
					$training_time	= create_date('H:i', $monat_trainings[$i][$k]['training_date'], $config['page_timezone']);
					$team_name		= $monat_trainings[$i][$k]['team_name'];
					$game_size		= $monat_trainings[$i][$k]['game_size'];
					$game_image		= $monat_trainings[$i][$k]['game_image'];
					$team_image		= "<img src=\"$root_path" . $settings['path_games'] . "/$game_image\" alt=\"$team_name\" title=\"$team_name\" width=\"$game_size\" align=\"middle\">";
					
					$list[] = $team_image . " <a href=\"training.php?mode=trainingdetails&" . POST_TRAINING_URL . "=$training_id\">$training_vs - $training_time</a>";
				}
				
				$language	= ( count($list) == 1 ) ? $lang['cal_training'] : $lang['cal_trainings'];
				$list		= implode(', ', $list);
				$cal_day	= $i;
				$cal_event	.= (empty($cal_event)) ? "<p><span><em class=\"trains\">$language:</em>$list</p>" : "<br /><p><em class=\"trains\">$language</em><br />$list</p>";
			}
		}
		else
		{
			$cal_day	= $i;
			$cal_event	= '&nbsp;';
		}
	}
	else
	{
		if ( $i == $tag || is_array($monat_birthday[$i]) || is_array($monat_events[$i]) || is_array($monat_matchs[$i]) )
		{
			$cal_event = '';
			
			if ( $i == $tag )
			{
				$cal_day	= '<span class="today">' . $i . '</span>';
				$cal_event	= '';
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
				$cal_day		= $i;
				$cal_event		.= (empty($cal_event)) ? '<span><em class="birthday">' . $language . ':</em> ' . $list : '<br><em class="birthday">' . $language . '</em><br>' . $list;
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
					$language	= ( count($list) == 1 ) ? $lang['cal_event'] : $lang['cal_events'];
					$list		= implode(', ', $list);
					$cal_day	= $i;
					$cal_event	.= (empty($cal_event)) ? '<span><em class="events">' . $language . ':</em> ' . $list : '<br><em class="events">' . $language . '</em><br>' . $list;
				}
			}
			
			if ( is_array($monat_matchs[$i]) )
			{
				$list = array();
				
				for ( $k = 0; $k < count($monat_matchs[$i]); $k++ )
				{
					$match_id	= $monat_matchs[$i][$k]['match_id'];
					$match_vs	= $monat_matchs[$i][$k]['match_rival'];
					$match_time	= create_date('H:i', $monat_matchs[$i][$k]['match_date'], $config['page_timezone']);
					$team_name	= $monat_matchs[$i][$k]['team_name'];
					$game_size	= $monat_matchs[$i][$k]['game_size'];
					$game_image	= $monat_matchs[$i][$k]['game_image'];
					$team_image	= "<img src=\"$root_path" . $settings['path_games'] . "/$game_image\" alt=\"$team_name\" title=\"$team_name\" width=\"$game_size\" align=\"middle\">";
					
					$list[] = $team_image . " <a href=\"match.php?mode=details&" . POST_MATCH_URL . "=$match_id\">$match_vs - $match_time</a>";
				}
				
				$language	= ( count($list) == 1 ) ? $lang['cal_match'] : $lang['cal_matchs'];
				$list		= implode(', ', $list);
				$cal_day	= $i;
				$cal_event	.= ( empty($cal_event) ) ? "<p><span><em class=\"wars\">$language:</em>$list</p>" : "<br /><p><em class=\"wars\">$language</em><br />$list</p>";
			}
		}
		else
		{
			$cal_day	= $i;
			$cal_event	= '&nbsp;';
		}
	}
	
	$class = ($i % 2) ? 'row1r' : 'row2r';
	
	$template->assign_block_vars('days', array(
		'CLASS' 		=> $class,
		'CAL_ID'		=> $i,
		'CAL_DAY'		=> $cal_day,
		'CAL_WEEKDAY'	=> $cal_weekday,
		'CAL_EVENT'		=> $cal_event,
	));
}

$nm = ( ($monat+1) == 13 ) ? 1 : $monat + 1;
$ny = ( ($monat+1) == 13 ) ? $jahr + 1 : $jahr;

$pm = ( ($monat-1) == 0 ) ? 12 : $monat - 1;
$py = ( ($monat-1) == 0 ) ? $jahr - 1 : $jahr;

$template->assign_vars(array(
	'CAL_MONTH'	=> $month,
	
	'NEXT'		=> "?month=$nm&year=$ny",
	'PREV'		=> "?month=$pm&year=$py",
	
	'L_CAL'		=> $lang['head_calendar'],
	'L_LEGEND'	=> $lang['cal_legend'],
));

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>