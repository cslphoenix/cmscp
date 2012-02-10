<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_CALENDAR);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', 0) ) ? request('start', 0) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_CALENDAR;
$url	= POST_CALENDAR;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, 0);	
$mode	= request('mode', 1);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_calendar.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'info_error.tpl',
));

$page_title = $lang['header_calendar'];

main_header();

$year	= request('year', 1);
$month	= request('month', 1);

if ( $month && $year )
{
	$tag	= ( $month == date('m') ) ? date("d", $time) : '';
	$jahr	= ( $year ) ? $year : date('Y');
	$monat	= ( $month ) ? $month : date('m');
	$monat	= ( $monat < 10 ) ? '0' . $monat : $monat;
	$tage	= date("t", mktime(0, 0, 0, $monat, 1, $jahr));
	$erster	= date("w", mktime(0, 0, 0, $monat, 1, $jahr));
}
else
{
	$tage	= date("t");
	$tag	= date("d", $time);
	$jahr	= date("Y", $time);
	$monat	= date("m", $time);
	$erster	= date("w", mktime(0, 0, 0, $monat, 1, $jahr));
}

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

if ( defined('CACHE') )
{
	$sCacheName = 'data_calendar_' . $monat;

	if ( ($monat_data = $oCache -> readCache($sCacheName)) === false )
	{
		for ( $i = 1; $i < $tage + 1; $i++ )
		{
			$i = ( $i < 10 ) ? '0' . $i : $i;
			
			$sql = "SELECT user_id, user_name, user_birthday
						FROM " . USERS . "
					WHERE MONTH(user_birthday) = $monat AND DAYOFMONTH(user_birthday) = $i";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_b = $db->sql_fetchrowset($result);
			
			$sql = "SELECT event_id, event_date, event_duration, event_title, event_level
						FROM " . EVENT . "
					WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_e = $db->sql_fetchrowset($result);
			
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
			
			$sql = "SELECT tr.*, g.game_image, g.game_size, t.team_name
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
			$monat_data = array_merge(array('bday' => $monat_data_b), array('event' => $monat_data_e), array('match' => $monat_data_w), array('train' => $monat_data_t));
			$oCache -> writeCache($sCacheName, $monat_data);
		}
	}
}
else
{
	for ( $i = 1; $i < $tage + 1; $i++ )
	{
		$i = ( $i < 10 ) ? '0' . $i : $i;
		
		$sql = "SELECT user_id, user_name, user_birthday
						FROM " . USERS . "
					WHERE MONTH(user_birthday) = $monat AND DAYOFMONTH(user_birthday) = $i";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$day_rows_b = $db->sql_fetchrowset($result);
		
		$sql = "SELECT event_id, event_date, event_duration, event_title, event_level
						FROM " . EVENT . "
					WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '$i.$monat.$jahr'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$day_rows_e = $db->sql_fetchrowset($result);
		
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
		
		$sql = "SELECT tr.*, g.game_image, g.game_size, t.team_name
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
		$monat_data = array_merge(array('bday' => $monat_data_b), array('event' => $monat_data_e), array('match' => $monat_data_w), array('train' => $monat_data_t));
	}
}

$day = '';

$tbl_start	= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
$tbl_mid	= '</td></tr><tr><td>';
$tbl_end	= '</td></tr></table>';

for ( $i = 1; $i < $tage + 1; $i++ )
{
	$weekday = $arr_woche_kurz[date("w", mktime(0, 0, 0, $monat, $i, $jahr))];
	
	$i = ( $i < 10 ) ? '0' . $i : $i;
	
	if ( $i == $tag || is_array($monat_data['bday'][$i]) || is_array($monat_data['event'][$i]) || is_array($monat_data['match'][$i]) || is_array($monat_data['train'][$i]) )
	{
		$event = '';
		
		if ( $i == $tag )
		{
			$day = "<span class=\"today\">$i</span>";
		}
		
		if ( is_array($monat_data['bday'][$i]) )
		{
			$list = array();
			
			for ( $j = 0; $j < count($monat_data['bday'][$i]); $j++ )
			{
				$alter	= 0;
				$gebdt	= explode("-", $monat_data['bday'][$i][$j]['user_birthday']);
				$gebdt	= $gebdt[0] . $gebdt[1] . $gebdt[2];
				$date	= mktime(0, 0, 0, (int)$monat, (int)$tag, (int)$jahr+1);
				$now	= date("Ymd", $date);
				
				while ($gebdt < $now - 9999)
				{
					$alter++;
					$gebdt = $gebdt + 10000;
				}
			
				$list[] = sprintf($lang['cal_birth'], $monat_data['bday'][$i][$j]['user_name'], $alter);
			}
			
			$language	= ( count($list) == 1 ) ? $lang['cal_birthday'] : $lang['cal_birthdays'];
			$list		= implode('<br />', $list);	
			$day		= $i;
			$event		.= ( !$event ) ? "$tbl_start <em class=\"birthday\">$language</em>$tbl_mid $list" : "$tbl_mid <em class=\"birthday\">$language</em><br /> $list";
		}
		
		if ( is_array($monat_data['event'][$i]) )
		{
			$list = array();
			
			for ( $j = 0; $j < count($monat_data['event'][$i]); $j++ )
			{
				if ( $userdata['user_level'] >= $monat_data['event'][$i][$j]['event_level'] )
				{
					$id		= $monat_data['event'][$i][$j]['event_id'];
					$date	= $monat_data['event'][$i][$j]['event_date'];
					$title	= $monat_data['event'][$i][$j]['event_title'];
					$dura	= $monat_data['event'][$i][$j]['event_duration'];
					
					$time_a	= create_date('H:i', $date, $userdata['user_timezone']);
					$time_b	= create_date('H:i', $dura, $userdata['user_timezone']);
					
					$diff	= ( $date == $dura ) ? "am gesamten Tag" : "von $time_a bis $time_b";
					
					$list[] = "<a href=\"event.php?mode=view&" . POST_EVENT . "=$id\">$title: $diff</a>";
				}
			}
			
			if ( !empty($list) )
			{
				$language	= ( count($list) == 1 ) ? $lang['cal_event'] : $lang['cal_events'];
				$list		= implode('<br />', $list);
				$day		= $i;
				$event		.= ( !$event ) ? "$tbl_start <em class=\"events\">$language</em>$tbl_mid  $list" : "$tbl_mid <em class=\"events\">$language</em><br /> $list";
			}
		}
		
		if ( is_array($monat_data['match'][$i]) )
		{
			$list = array();
			for ( $k = 0; $k < count($monat_data['match'][$i]); $k++ )
			{
				$match_id	= $monat_data['match'][$i][$k]['match_id'];
				$match_vs	= $monat_data['match'][$i][$k]['match_rival_name'];
				$match_time	= create_date('H:i', $monat_data['match'][$i][$k]['match_date'], $config['page_timezone']);
				$team_name	= $monat_data['match'][$i][$k]['team_name'];
				$team_image	= display_gameicon($monat_data['match'][$i][$k]['game_size'], $monat_data['match'][$i][$k]['game_image']);
				
				$list[] = $team_image . " <a href=\"match.php?mode=view&" . POST_MATCH . "=$match_id\">$match_vs - $match_time</a>";
			}
			
			$language	= ( count($list) == 1 ) ? $lang['cal_match'] : $lang['cal_matchs'];
			$list		= implode('<br />', $list);
			$day		= $i;
			$event		.= ( !$event ) ? "$tbl_start <em class=\"wars\">$language</em>$tbl_mid $list" : "$tbl_mid <em class=\"wars\">$language</em><br /> $list";
		}
		
		if ( is_array($monat_data['train'][$i]) && $userdata['user_level'] >= TRIAL )
		{
			$list = array();
			
			for ( $k = 0; $k < count($monat_data['train'][$i]); $k++ )
			{
				$training_id	= $monat_data['train'][$i][$k]['training_id'];
				$training_vs	= $monat_data['train'][$i][$k]['training_vs'];
				$training_time	= create_date('H:i', $monat_data['train'][$i][$k]['training_date'], $config['page_timezone']);
				$team_name		= $monat_data['train'][$i][$k]['team_name'];
				$team_image		= display_gameicon($monat_data['train'][$i][$k]['game_size'], $monat_data['train'][$i][$k]['game_image']);
				
				$list[] = $team_image . " <a href=\"training.php?mode=view&" . POST_TRAINING . "=$training_id\">$training_vs - $training_time</a>";
			}
			
			$language	= ( count($list) == 1 ) ? $lang['cal_training'] : $lang['cal_trainings'];
			$list		= implode('<br />', $list);
			$day		= $i;
			$event		.= ( !$event ) ? "$tbl_start <em class=\"trains\">$language</em>$list</p>" : "$tbl_mid <em class=\"trains\">$language</em><br /> $list";
		}
		
		$event .= !$event ? '' : $tbl_end;
	}
	else
	{
		$day	= $i;
		$event	= '&nbsp;';
	}
	
	$class = ($i % 2) ? 'row1r' : 'row2r';
	
	$template->assign_block_vars('days', array(
		'CLASS' 		=> $class,
		'CAL_ID'		=> $i,
		'CAL_DAY'		=> $day,
		'CAL_WEEKDAY'	=> $weekday,
		'CAL_EVENT'		=> $event,
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

main_footer();

?>