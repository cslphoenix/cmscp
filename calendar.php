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

main_header($page_title);

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
	$cur_year 	= date('Y', $time);
	$cur_month	= date('m', $time);
	$cur_first	= date('w', mktime(0, 0, 0, $cur_month, 1, $cur_year));
}

$y_current = date('Y');
$m_current = date('m');

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
		
		$sql = "SELECT user_id, user_name, user_birthday FROM " . USERS . "	WHERE MONTH(user_birthday) = $cur_month";
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
		
		$sql = "SELECT event_id, event_date, event_duration, event_title, event_group
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
	$sql = "SELECT user_id, user_name, user_birthday FROM " . USERS . " WHERE MONTH(user_birthday) = $cur_month";
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
	
	$sql = "SELECT event_id, event_date, event_duration, event_title, event_group
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

	$monat_data = array_merge(
		array('birthday' => $db_birthday),
		array('news' => $db_news),
		array('event' => $db_event),
		array('match' => $db_match),
		array('training' => $db_training)
	);
}

switch ( $mode )
{
	case 'view':
	
		debug('echo');
	
		break;
		
	default:
	
		if ( $settings['calendar']['show'] )
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
				
			#	if ( $i == $cur_day || is_array($cur_news) || is_array($cur_event) || is_array($cur_match) || is_array($cur_birthday) || is_array($cur_training) )
			#	{
					$style = $event = '';
					$count = 0;
					
					if ( $i == $cur_day )
					{
						$style = CAL_TODAY;
						$event = sprintf('<span class="today" title="%s">%s</span>', $lang['CAL_TODAY'], $lang['CAL_TODAY']);
						$count = $count + 1;
					}
					
					if ( $settings['calendar']['holidays'] )
					{
						$temp_hd = holidays($cur_year, $cur_month, $i);
						
						if ( $temp_hd )
						{
							$style = CAL_HOLIDAYS;
							$event .= sprintf($lang['stf_cal_today'], $temp_hd[1]);
							$count = $count + 1;
						}
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
							
							$action[] = "<a title=\"". $row['user_name'] . "\" href=\"user.php?mode=u&id=" . $row['user_id'] . "\">" . sprintf($lang['CAL_AGE'], $row['user_name'], $alter) . "</a>";
						}
						
						$style = CAL_BIRTHDAY;
						$event .= cal_string($event, $style, ( count($action) == '1' ) ? $lang['CAL_BIRTHDAY'] : $lang['CAL_BIRTHDAYS'], $action, false);
						$count = $count + 1;
					}
					
					if ( is_array($cur_news) && $settings['calendar']['news'] )
					{
						$action = array();
						
						foreach ( $cur_news as $row )
						{
							$action[] = sprintf('<a href="news.php?mode=view&id=%s" title="%s">%s</a>', $row['news_id'], $row['news_title'], cut_string($row['news_title'], $settings['calendar']['length']));
						}
						
						$style = CAL_NEWS;
						$event .= cal_string($event, $style, $lang['CAL_NEWS'], $action, false);
						$count = $count + 1;
					}
					
					if ( is_array($cur_event) && $settings['calendar']['event'] )
					{
						$action = array();				
						
						foreach ( $cur_event as $row )
						{
							$user_in_groups = $in_group = '';
							
							if ( is_array(unserialize($row['event_group'])) )
							{
								$sql = "SELECT type_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND user_pending != 1 AND user_id = " . $userdata['user_id'];
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$user_in_groups = $db->sql_fetchrowset($result);
							}
							
							if ( $user_in_groups )
							{
								$event_group = unserialize($row['event_group']);
								
								foreach ( $user_in_groups as $groups )
								{
									foreach ( $groups as $group_id )
									{
										if ( in_array($group_id, $event_group) )
										{
											$in_group = true;
										}
									}
								}
							}
							
							if ( $in_group )
							{
								$action[] = sprintf('<a href="event.php?mode=view&id=%s" title="%s">%s</a>', $row['event_id'], $row['event_title'], cut_string($row['event_title'], $settings['calendar']['length']));
							}
						}
						
						if ( !empty($action) )
						{
							$style = CAL_EVENT;
							$event .= cal_string($event, $style, ( count($action) == '1' ) ? $lang['CAL_EVENT'] : $lang['CAL_EVENTS'], $action, false);
							$count = $count + 1;
						}
					}
					
					if ( is_array($cur_training) && $settings['calendar']['training'] )
					{
						$action = array();
						
						foreach ( $cur_training as $row )
						{
							$action[] = sprintf('<a href="training.php?mode=view&id=%s" title="%s">%s</a>', $row['training_id'], $row['training_vs'], cut_string($row['training_vs'], $settings['calendar']['length']));
						}
						
						$style = CAL_TRAINING;
						$event .= cal_string($event, $style, ( count($action) == '1' ) ? $lang['CAL_TRAINING'] : $lang['CAL_TRAININGS'], $action, false);
						$count = $count + 1;
					}
					
					if ( is_array($cur_match) && $settings['calendar']['match'] )
					{
						$action = array();
						
						foreach ( $cur_match as $row )
						{
							$action[] = sprintf('<a href="match.php?mode=view&id=%s" title="%s">%s</a>', $row['match_id'], $row['match_rival_name'], cut_string($row['match_rival_name'], $settings['calendar']['length']));
						}
						
						$style = CAL_MATCH;
						$event .= cal_string($event, $style, ( count($action) == '1' ) ? $lang['CAL_MATCH'] : $lang['CAL_MATCHS'], $action, false);
						$count = $count + 1;
					}
					
					if ( $count )
					{
						$class = ( $count > 1 ) ? 'more' : $style;
						
					#	$day .= "<td class=\"$class\"><a class=\"$class right\" href=\"" . check_sid("calendar.php?mode=view") . "\">$i</a><span class=\"$style\">$event</span></td>";
						$day .= sprintf('<td class="%s"><a class="%s right" href="%s">%s</a><span class="%s">%s</span></td>', $class, $class, check_sid("calendar.php?mode=view"), $i, $style, $event);
					}
					else
					{
						$day .= sprintf('<td><span class="day right">%s</span></td>', $i);
					}
			#	}
			#	else
			#	{
			#		$day .= sprintf('<td><span class="day right">%s</span></td>', $i);
			#	}
				
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
				
				if ( $next_days < 7 )
				{
					for ( $next_day = 1; $next_day <= $next_days; $next_day++ )
					{
						$day .= sprintf('<td><span class="next right">%s</span></td>', $next_day);
					}
				}
				else
				{
					$next_day = $next_days - 7;
					
					if ( $next_day >= 7 )
					{
						for ( $i = 1; $i < $next_days+1; $i++ )
						{
						#	$day .= "<td><span class=\"next right\">$i</span></td>";
							$day .= sprintf('<td><span class="next right">%s</span></td>', $i);
						}
					}
					else
					{
						for ( $i = 1; $i < $next_day+1; $i++ )
						{
						#	$day .= "<td><span class=\"next right\">$i</span></td>";
							$day .= sprintf('<td><span class="next right">%s</span></td>', $i);
						}
						
					#	$day .= '</tr><tr>';
						
					#	for ( $j = $i; $j < $next_days+1; $j++ )
					#	{
					#		$day .= "<td><span class=\"next right\">$j</span></td>";
					#		$day .= sprintf('<td><span class="next right">%s</span></td>', $j);
					#	}	
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
			$temp_hd = '';
			
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
				
				$act = '';
				
				if ( $i == $cur_day && $m_current == $cur_month && $y_current == $cur_year )
				{
					$act = $div_start . sprintf('<span class="%s">%s</span><br/>', CAL_TODAY, $lang['CAL_TODAY']);
				}
				
				if ( $settings['calendar']['holidays'] )
				{
					$temp_hd = holidays($cur_year, $cur_month, $i);
	
					if ( $temp_hd )
					{
						$act = $div_start . sprintf('<span class="%s">%s</span><br/>', $temp_hd[0], $temp_hd[1]);
					}
				}
				if ( isset($monat_data['news'][$i]) && $settings['calendar']['news'] )
				{
					$ary = array();
					$tmp = $monat_data['news'][$i];
					$cnt = count($tmp);
					
					for ( $k = 0; $k < $cnt; $k++ )
					{
						$ary[]	= sprintf('<a title="%s" href="news.php?id=%s">%s</a>', $tmp[$k]['news_title'], $tmp[$k]['news_id'], $tmp[$k]['news_title']);
					}
					
					$day = $i;
					$css = CAL_NEWS;
					$act .= cal_string($act, $css, $lang['CAL_NEWS'], $ary, true);
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
					$user_in_groups = $in_group = '';
					
					$sql = "SELECT type_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND user_pending != 1 AND user_id = " . $userdata['user_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$user_in_groups = $db->sql_fetchrowset($result);
					
					for ( $k = 0; $k < $cnt; $k++ )
					{
						if ( $user_in_groups )
						{
							$egroup		= unserialize($tmp[$k]['event_group']);
							$in_group	= _in_ary($user_in_groups, $egroup);
						}
						
						if ( $in_group )
						{
							$date	= $tmp[$k]['event_date'];
							$dura	= $tmp[$k]['event_duration'];
							
							$time_a	= create_date('H:i', $date, $userdata['user_timezone']);
							$time_b	= create_date('H:i', $dura, $userdata['user_timezone']);
							
							$diff	= ( $date == $dura ) ? $lang['CAL_DAY_WHOLE'] : sprintf($lang['CAL_DAY_FROMTO'], $time_a, $time_b);
							$ary[]	= sprintf('<a href="event.php?id=%s">%s %s</a>', $tmp[$k]['event_id'], $tmp[$k]['event_title'], $diff);
						}
					}
					
					if ( !empty($ary) )
					{
						$day = $i;
						$css = CAL_EVENT;
						$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['CAL_EVENT'] : $lang['CAL_EVENTS'], $ary, true);
					}
				}
				else
				{
					$day = $i;
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
					
						$ary[] = sprintf($lang['CAL_AGE'], $tmp[$k]['user_name'], $alter);
					}
					
					$day = $i;
					$css = CAL_BIRTHDAY;
					$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['CAL_BIRTHDAY'] : $lang['CAL_BIRTHDAYS'], $ary, true);
				}
				else
				{
					$day = $i;
				}

				if ( isset($monat_data['training'][$i]) && $settings['calendar']['training'] )
				{
					$sql = "SELECT type_id FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND user_id = " . $userdata['user_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$user_in_teams = $db->sql_fetchrowset($result);
					
					if ( $user_in_teams )
					{
						$ary = array();
						$tmp = $monat_data['training'][$i];
						$cnt = count($tmp);
						
						$user_in_teams = $in_team = '';
							
						for ( $k = 0; $k < $cnt; $k++ )
						{
							$training_time	= create_date('H:i', $tmp[$k]['training_date'], $config['default_timezone']);
							$team_name		= $tmp[$k]['team_name'];
							$team_image		= display_gameicon($tmp[$k]['game_image']);
							
							$ary[]	= sprintf('%s <a href="training.php?id=%s">%s %s</a>', $team_image, $tmp[$k]['training_id'], $tmp[$k]['training_vs'], $training_time);
						}
					}
					$day = $i;
					$css = CAL_TRAINING;
					$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['CAL_TRAINING'] : $lang['CAL_TRAININGS'], $ary, true);
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
						
						$match_id	= $tmp[$k]['match_id'];
						$match_vs	= $tmp[$k]['match_rival_name'];
						$match_time	= create_date('H:i', $tmp[$k]['match_date'], $config['default_timezone']);
						$team_name	= $tmp[$k]['team_name'];
						$team_image	= display_gameicon($tmp[$k]['game_image']);
						
						$ary[] = sprintf('%s <a title="%s" href="match.php?id=%s">%s - %s</a>', $team_image, $match_vs, $tmp[$k]['match_id'], $match_vs, $match_time);
					}
					
					$day = $i;
					$css = CAL_MATCH;
					$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['CAL_MATCH'] : $lang['CAL_MATCHS'], $ary, true);
				}
				else
				{
					$day = $i;
				}

				$act .= ( $act == '' ) ? '' : $div_end;
				
				$class = ($i % 2) ? 'row1' : 'row2';
				
				$template->assign_block_vars('list.rows', array(
					'CLASS' 		=> $class,
					'CAL_ID'		=> $i,
					'CAL_DAY'		=> $day,
					'CAL_WEEKDAY'	=> $weekday,
					'CAL_EVENT'		=> $act,
				));
			}
		}
	
		break;
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