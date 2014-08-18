<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_CALENDAR);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_CALENDAR;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$mode	= request('mode', TXT);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_calendar.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'info_error.tpl',
));

$page_title = $lang['header_calendar'];

main_header();

$sy	= request('year', INT);
$sm	= request('month', INT);

if ( $sm && $sy )
{
	$cur_day	= ( $sm == date('m') ) ? date('d', $time) : '';
	$cur_year	= ( $sy ) ? $sy : date('Y');
	$cur_month	= ( $sm ) ? $sm : date('m');
	$cur_month	= ( $cur_month < 10 ) ? '0' . $cur_month : $cur_month;
	$cur_days	= date('t', mktime(0, 0, 0, $cur_month, 1, $cur_year));
	$cur_first	= date('w', mktime(0, 0, 0, $cur_month, 1, $cur_year));
}
else
{
	$cur_days	= date('t');
	$cur_day	= date('d', $time);
	$cur_year	= date('Y', $time);
	$cur_month	= date('m', $time);
	$cur_first	= date('w', mktime(0, 0, 0, $cur_month, 1, $cur_year));
}

$prev_month = ( $cur_month == 1 ) ? '12' : $cur_month-1;
$prev_days = date('t', mktime(0, 0, 0, $prev_month, 1, $cur_year));

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

$sm = $monate[$cur_month];

if ( defined('CACHE') )
{
	$sCacheName = "data_calendar_$cur_month$cur_year";

	if ( ($monat_data = $oCache -> readCache($sCacheName)) === false )
	{
		$db_birthday = $db_news = $db_event = $db_match = $db_training = array();
		
		$sql = "SELECT user_id, user_name, user_birthday
					FROM " . USERS . "
				WHERE MONTH(user_birthday) = $cur_month";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$tmp = explode('-', $row['user_birthday']);
			
			$db_birthday[$tmp[2]][] = $row;
		}
		$db->sql_freeresult($result);
		
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
			$db_news[date('d', $row['news_date'])][] = $row;
		}
		$db->sql_freeresult($result);
		
		$sql = "SELECT event_id, event_date, event_duration, event_title, event_level
					FROM " . EVENT . "
				WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%m.%Y') = '$cur_month.$cur_year'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$db_event[date('d', $row['event_date'])][] = $row;
		}
		$db->sql_freeresult($result);
		
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
			$db_match[date('d', $row['match_date'])][] = $row;
		}
		$db->sql_freeresult($result);
		
		$sql = "SELECT tr.*, g.game_image, t.team_name
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
			$db_training[date('d', $row['training_date'])][] = $row;
		}
		$db->sql_freeresult($result);
		
		$monat_data = array_merge(array('birthday' => $db_birthday), array('news' => $db_news), array('event' => $db_event), array('match' => $db_match), array('training' => $db_training));
		( $settings['calendar']['time'] != '' ) ? $oCache->writeCache($sCacheName, $monat_data, (int) $settings['calendar']['time']) : $oCache->writeCache($sCacheName, $monat_data);
	}
}
else
{
	$sql = "SELECT user_id, user_name, user_birthday
				FROM " . USERS . "
			WHERE MONTH(user_birthday) = $cur_month";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$tmp = explode('-', $row['user_birthday']);
		
		$db_birthday[$tmp[2]] = $row;
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT n.news_id, n.news_title, n.news_intern, n.news_match, t.team_name, g.game_image
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
		$db_news[date('d', $row['news_date'])][] = $row;
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT event_id, event_date, event_duration, event_title, event_level
				FROM " . EVENT . "
			WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%m.%Y') = '$cur_month.$cur_year'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$db_event[date('d', $row['event_date'])][] = $row;
	}
	$db->sql_freeresult($result);
	
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
		$db_match[date('d', $row['match_date'])][] = $row;
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT tr.*, g.game_image, t.team_name
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
		$db_training[date('d', $row['training_date'])][] = $row;
	}
	$db->sql_freeresult($result);

	$monat_data = array_merge(array('birthday' => $db_birthday), array('news' => $db_news), array('event' => $db_event), array('match' => $db_match), array('training' => $db_training));
}

$viewer = $settings['calendar']['show'];

if ( $viewer )
{
	$template->assign_block_vars('month', array());
	
	$week_start = $settings['calendar']['start'];
	
	$edmk = $arr_woche_kurz[$cur_first];
	$wbmk = $arr_woche_kurz;
	
	for ( $i = 0; $i < $week_start; $i++ )
	{
		$wechsel = array_shift($wbmk);
		$wbmk[] = $wechsel;
	}
	$wbmk_wechsel = array_flip($wbmk);
	
	$days = '<tr>';
	
	for ( $i = 0; $i < 7; $i++ )
	{
		$days .= sprintf('<th>%s</th>', $wbmk[$i]);
	}
	$days .= '</tr>';
	
	$day = '<tr>';
	
	if ( $settings['calendar']['compact'] )
	{
		$prev_day = ($prev_days - $wbmk_wechsel[$edmk]);
		
		for ( $i = $prev_day + 1; $i < $prev_days + 1; $i++ )
		{
			$day .= sprintf('<td><span class="right">%s</span></td>', $i);
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
		
		$cur_news		= isset($monat_data['news'][$i]) ? $monat_data['news'][$i] : false;
		$cur_event		= isset($monat_data['event'][$i]) ? $monat_data['event'][$i] : false;
		$cur_match		= isset($monat_data['match'][$i]) ? $monat_data['match'][$i] : false;
		$cur_birthday	= isset($monat_data['birthday'][$i]) ? $monat_data['birthday'][$i] : false;
		$cur_training	= isset($monat_data['training'][$i]) ? $monat_data['training'][$i] : false;
		
		if ( $i == $cur_day || is_array($cur_news) || is_array($cur_event) || is_array($cur_match) || is_array($cur_birthday) || is_array($cur_training) )
		{
			$style = '';
			$event = '';
			$count = 0;
			
			if ( $i == $cur_day )
			{
				$style = CAL_TODAY;
				$event = "<span class=\"today\" title=\"{$lang['cal_today']}\">{$lang['cal_today']}</span>";
				$count = $count + 1;
			}
			
			if ( is_array($cur_news) && $settings['calendar']['news'] )
			{
				$action = array();
				
				foreach ( $cur_news as $row )
				{
					$id		= $row['news_id'];
					$name	= $row['news_title'];
					$cut	= cal_cut($name, 20);
					
					$action[]	= "<a title=\"$name\" href=\"news.php?mode=view&id=$id\">$cut</a>";
				}
				
				$style = CAL_NEWS;
				$event .= cal_string($event, $style, $lang['cal_news'], $action, false);
				$count = $count + 1;
			}
			
			if ( is_array($cur_event) && $settings['calendar']['event'] )
			{
				$action = array();				
				
				foreach ( $cur_event as $row )
				{
					if ( $userdata['user_level'] >= $row['event_level'] )
					{
						$id		= $row['event_id'];
						$name	= $row['event_title'];
						$cut	= cal_cut($name, 20);
						
						$action[] = "<a title=\"$name\" href=\"event.php?mode=view&id=$id\">$cut</a>";
					}
				}
				
				if ( !empty($action) )
				{
					$style = CAL_EVENT;
					$event .= cal_string($event, $style, ( count($action) == '1' ) ? $lang['cal_event'] : $lang['cal_events'], $action, false);
					$count = $count + 1;
				}
			}
			
			if ( is_array($cur_match) && $settings['calendar']['match'] )
			{
				$action = array();
				
				foreach ( $cur_match as $row )
				{
					$id		= $row['match_id'];
					$name	= $row['match_rival_name'];
					$cut	= cal_cut($name, 20);
					
					$action[] = "<a title=\"$name\" href=\"match.php?mode=view&id=$id\">$cut</a>";
				}
				
				$style = CAL_MATCH;
				$event .= cal_string($event, $style, ( count($action) == '1' ) ? $lang['cal_match'] : $lang['cal_matchs'], $action, false);
				$count = $count + 1;
			}
			
			if ( is_array($cur_birthday) && $settings['calendar']['birthday'] )
			{
				$action = array();
				
				foreach ( $cur_birthday as $row )
				{
					$alter	= 0;
					$gebdt	= explode('-', $row['user_birthday']);
					$gebdt	= $gebdt[0].$gebdt[1].$gebdt[2];
					$now	= date('Ymd', mktime(0, 0, 0, $cur_month, 1, $cur_year));
					
					while ( $gebdt < $now - 10000 )
					{
						$alter++;
						$gebdt = $gebdt + 9999;
					}
					
					$action[] = "<a title=\"". $row['user_name'] . "\" href=\"user.php?mode=u&id=" . $row['user_id'] . "\">" . sprintf($lang['cal_birth'], $row['user_name'], $alter) . "</a>";
				}
				
				$style = CAL_BIRTHDAY;
				$event .= cal_string($event, $style, ( count($action) == '1' ) ? $lang['cal_birthday'] : $lang['cal_birthdays'], $action, false);
				$count = $count + 1;
			}
			
			if ( is_array($cur_training) && $settings['calendar']['training'] && $userdata['user_level'] >= TRIAL )
			{
				$action = array();
				
				foreach ( $cur_training as $row )
				{
					$id		= $row['training_id'];
					$name	= $row['training_vs'];
					$cut	= cal_cut($name, 20);
					
					$action[] = "<a title=\"$name\" href=\"training.php?mode=view&id=$id\">$cut</a>";
				}
				
				$style = CAL_TRAINING;
				$event .= cal_string($action, $style, ( count($action) == '1' ) ? $lang['cal_training'] : $lang['cal_trainings'], $action, false);
				$count = $count + 1;
			}
			
			if ( $count )
			{
				$class = ( $count > 1 ) ? 'more' : $style;
				
				$day .= "<td class=\"$class\" ><a class=\"$class right\" href=\"" . check_sid("calendar.php?mode=view") . "\">$i</a><span class=\"$style\">$event</span></td>";
			}
			else
			{
				$day .= "<td><span class=\"day right\">$i</span></td>";
			}
		}
		else
		{
			$day .= "<td><span class=\"day right\">$i</span></td>";
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
	
	if ( $settings['calendar']['compact'] )
	{
		$next_days = 42 - $cur_days - $wbmk_wechsel[$edmk];
		
	#	debug($next_days, 'next_days');
	
		if ( $next_days < 7 )
		{
			for ( $next_day = 1; $next_day <= $next_days; $next_day++ )
			{
			#	$day .= sprintf('<td><span class="right">%s</span></td>', $i);
				$day .= "<td><span class=\"next right\">$next_day</span></td>";
			}
		}
		else
		{
			$next_day = $next_days - 7;
			
			if ( $next_day >= 7 )
			{
				for ( $i = 1; $i < $next_days+1; $i++ )
				{
					$day .= "<td><span class=\"next right\">$i</span></td>";
				}
			}
			else
			{
				for ( $i = 1; $i < $next_day+1; $i++ )
				{
					$day .= "<td><span class=\"next right\">$i</span></td>";
				}
				
				$day .= '</tr><tr>';
				
				for ( $j = $i; $j < $next_days+1; $j++ )
				{
					$day .= "<td><span class=\"next right\">$j</span></td>";
				}	
			}
		}
		
		$day .= '</tr>';
	}
	else
	{
		for ( $wcs; $wcs < 7; $wcs++ )
        {
                $day .= '<td>&nbsp;</td>';
        }
        
        $day .= '</tr>';
	}
	
	$template->assign_vars(array(
		'DAY' => $days,
		'NUM' => $day,
	));
}
else
{
	$template->assign_block_vars('list', array());
	
	$day = '';
	
	$tbl_start	= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
	$tbl_mid	= '</td></tr><tr><td>';
	$tbl_end	= '</td></tr></table>';
	
	$div_start	= '<div class="cal_list"><ul>';
	$div_middle	= '</ul><ul>';
	$div_end	= '</u></div>';
	
	for ( $i = 1; $i < $cur_days + 1; $i++ )
	{
		$weekday = $arr_woche_kurz[date("w", mktime(0, 0, 0, $cur_month, $i, $cur_year))];
		
		$i = ( $i < 10 ) ? '0' . $i : $i;
		
	#	if ( $i == $cur_day ||  || is_array($bday) || is_array($event) || is_array($match) || is_array($train) )
	#	{
			$act = '';
			
			if ( $i == $cur_day && $cur_year == $sy )
			{
				$day = $i;
				$act = "$div_start<span class=\"today\">{$lang['cal_today']}</span><br/>";
			}
			
			if ( isset($monat_data['bday'][$i]) && $settings['calendar']['bday'] )
			{
				$ary = array();
				$tmp = $monat_data['bday'][$i];
				$cnt = count($tmp);
								
				for ( $k = 0; $k < $cnt; $k++ )
				{
					$alter	= 0;
					$gebdt	= explode("-", $tmp[$k]['user_birthday']);
					$gebdt	= $gebdt[0] . $gebdt[1] . $gebdt[2];
					$date	= mktime(0, 0, 0, $cur_month, $cur_day, $cur_year);
					$now	= date("Ymd", $date);
					
					while ($gebdt < $now - 9999)
					{
						$alter++;
						$gebdt = $gebdt + 10000;
					}
				
					$ary[] = sprintf($lang['cal_birth'], $tmp[$k]['user_name'], $alter);
				}
				
				$day = $i;
				$css = 'birthday';
				$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['cal_birthday'] : $lang['cal_birthdays'], $ary, true);
			}
			else
			{
				$day = $i;
			}
			
			if ( isset($monat_data['news'][$i]) && $settings['calendar']['news'] )
			{
				$ary = array();
				$tmp = $monat_data['news'][$i];
				$cnt = count($tmp);
				
				for ( $k = 0; $k < $cnt; $k++ )
				{
					$id		= $tmp[$k]['news_id'];
					$name	= $tmp[$k]['news_title'];
					$cut	= cal_cut($name, 20);
					
					$ary[]	= "<a title=\"$name\" href=\"news.php?id=$id\">$cut</a>";
				}
				
				$day = $i;
				$css = 'news';
				$act .= cal_string($act, $css, $lang['cal_news'], $ary, true);
			}
			else
			{
				$day = $i;
			}
			
			if ( isset($monat_data['event'][$i]) && $settings['calendar']['event'] )
			{
				$ary = array();
				$tmp = $monat_data['event'][$i];
				$cnt = count($tmp);
				
				for ( $k = 0; $k < $cnt; $k++ )
				{
					if ( $userdata['user_level'] >= $tmp[$k]['event_level'] )
					{
						$id		= $tmp[$k]['event_id'];
						$date	= $tmp[$k]['event_date'];
						$title	= $tmp[$k]['event_title'];
						$dura	= $tmp[$k]['event_duration'];
						
						$time_a	= create_date('H:i', $date, $userdata['user_timezone']);
						$time_b	= create_date('H:i', $dura, $userdata['user_timezone']);
						
						$diff	= ( $date == $dura ) ? "am gesamten Tag" : "von $time_a bis $time_b";
						
						$ary[] = "<a href=\"event.php?id=$id\">$title: $diff</a>";
					}
				}
				
				if ( !empty($ary) )
				{
					$day = $i;
					$css = 'events';
					$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['cal_event'] : $lang['cal_events'], $ary, true);
				}
			}
			else
			{
				$day = $i;
			}
			
			if ( isset($monat_data['match'][$i]) && $settings['calendar']['match'] )
			{
				$ary = array();
				$tmp = $monat_data['match'][$i];
				$cnt = count($tmp);
				
				for ( $k = 0; $k < $cnt; $k++ )
				{
					$id		= $tmp[$k]['match_id'];
					$name	= $tmp[$k]['match_rival_name'];
					$cut	= cal_cut($name, 20);
					
					$match_id	= $tmp[$k]['match_id'];
					$match_vs	= $tmp[$k]['match_rival_name'];
					$match_time	= create_date('H:i', $tmp[$k]['match_date'], $config['default_timezone']);
					$team_name	= $tmp[$k]['team_name'];
					$team_image	= display_gameicon($tmp[$k]['game_image']);
					
				#	$ary[] = $team_image . " <a href=\"match.php?mode=view&id=$match_id\">$match_vs - $match_time</a>";
					$ary[] = "<a title=\"$name\" href=\"match.php?id=$id\">$cut</a>";
				}
				
				$day = $i;
				$css = 'wars';
				$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['cal_match'] : $lang['cal_matchs'], $ary, true);
			}
			else
			{
				$day = $i;
			}
			
			if ( isset($monat_data['train'][$i]) && $userdata['user_level'] >= TRIAL && $settings['calendar']['train'] )
			{
				$ary = array();
				$tmp = $monat_data['train'][$i];
				$cnt = count($tmp);
				
				for ( $k = 0; $k < $cnt; $k++ )
				{
					$training_id	= $tmp[$k]['training_id'];
					$training_vs	= $tmp[$k]['training_vs'];
					$training_time	= create_date('H:i', $tmp[$k]['training_date'], $config['default_timezone']);
					$team_name		= $tmp[$k]['team_name'];
					$team_image		= display_gameicon($tmp[$k]['game_image']);
					
					$ary[] = $team_image . " <a href=\"training.php?id=$training_id\">$training_vs - $training_time</a>";
				}
				
				$day = $i;
				$css = CAL_TRAINING;
				$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['cal_training'] : $lang['cal_trainings'], $ary, true);
			}
			else
			{
				$day = $i;
			}
			
			$act .= ( $act == '' ) ? '' : $div_end;
	#	}
	#	else
	#	{
	#		$day	= $i;
	#		$act	= '&nbsp;';
	#	}
		
		$class = ($i % 2) ? 'row1' : 'row2';
		
		$template->assign_block_vars('list._rows', array(
			'CLASS' 		=> $class,
			'CAL_ID'		=> $i,
			'CAL_DAY'		=> $day,
			'CAL_WEEKDAY'	=> $weekday,
			'CAL_EVENT'		=> $act,
		));
	}
}

$nm = ( ($cur_month+1) == 13 ) ? 1 : $cur_month + 1;
$ny = ( ($cur_month+1) == 13 ) ? $cur_year + 1 : $cur_year;

$pm = ( ($cur_month-1) == 0 ) ? 12 : $cur_month - 1;
$py = ( ($cur_month-1) == 0 ) ? $cur_year - 1 : $cur_year;

$template->assign_vars(array(
	'CAL_MONTH'	=> sprintf($lang['sprintf_empty_line'], $sm, $cur_year),
	
	'NEXT'		=> "?month=$nm&year=$ny",
	'PREV'		=> "?month=$pm&year=$py",
	
	'L_CAL'		=> $lang['head_calendar'],
	'L_LEGEND'	=> $lang['cal_legend'],
	
	'CAL_CACHE' => ( $settings['calendar']['cache'] && defined('CACHE') ) ? display_cache($sCacheName, 1) : '',
));

$template->pparse('body');

main_footer();

?>