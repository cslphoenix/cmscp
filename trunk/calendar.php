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
$url	= POST_CALENDAR;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, INT);	
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

$sy	= request('year', 1);
$sm	= request('month', 1);

if ( $sm && $sy )
{
	$tag	= ( $sm == date('m') ) ? date("d", $time) : '';
	$jahr	= ( $sy ) ? $sy : date('Y');
	$monat	= ( $sm ) ? $sm : date('m');
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

$sm = $monate[$monat];

if ( defined('CACHE') )
{
	$sCacheName = "data_calendar_$monat$jahr";

	if ( ($monat_data = $oCache -> readCache($sCacheName)) === false )
	{
		$bday = $news = $event = $match = $train = array();
		
		$sql = "SELECT user_id, user_name, user_birthday FROM " . USERS . " WHERE MONTH(user_birthday) = $monat";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$tmp = explode('-', $row['user_birthday']);
			
			$bday[$tmp[2]][] = $row;
		}
		$db->sql_freeresult($result);
		
		$sql = "SELECT n.news_id, n.news_title, n.news_intern, n.news_match, n.news_date, t.team_name, g.game_image
					FROM " . NEWS . " n
						LEFT JOIN " . MATCH . " m ON n.news_match = m.match_id
						LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE n.news_date < " . time() . " AND news_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(news_date), '%m.%Y') = '$monat.$jahr'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$news[date('d', $row['news_date'])][] = $row;
		}
		$db->sql_freeresult($result);
		
		$sql = "SELECT event_id, event_date, event_duration, event_title, event_level FROM " . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%m.%Y') = '$monat.$jahr'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$event[date('d', $row['event_date'])][] = $row;
		}
		$db->sql_freeresult($result);
		
		$sql = "SELECT m.match_id, m.match_rival_name, m.match_date, g.game_image, t.team_name
					FROM " . MATCH . " m
						LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%m.%Y') = '$monat.$jahr'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$match[date('d', $row['match_date'])][] = $row;
		}
		$db->sql_freeresult($result);
		
		$sql = "SELECT tr.*, g.game_image, t.team_name
					FROM " . TRAINING . " tr
						LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id	
				WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%m.%Y') = '$monat.$jahr'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$train[date('d', $row['training_date'])][] = $row;
		}
		$db->sql_freeresult($result);
		
		$monat_data = array_merge(array('bday' => $bday), array('news' => $news), array('event' => $event), array('match' => $match), array('train' => $train));
		( $settings['calendar']['time'] != '' ) ? $oCache->writeCache($sCacheName, $monat_data, (int) $settings['calendar']['time']) : $oCache->writeCache($sCacheName, $monat_data);
	}
}
else
{
	$sql = "SELECT user_id, user_name, user_birthday FROM " . USERS . " WHERE MONTH(user_birthday) = $monat";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$tmp = explode('-', $row['user_birthday']);
		
		$bday[$tmp[2]] = $row;
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT n.news_id, n.news_title, n.news_intern, n.news_match, t.team_name, g.game_image
				FROM " . NEWS . " n
					LEFT JOIN " . MATCH . " m ON n.news_match = m.match_id
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE n.news_date < " . time() . " AND news_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(news_date), '%m.%Y') = '$monat.$sy'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$news[date('d', $row['news_date'])][] = $row;
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT event_id, event_date, event_duration, event_title, event_level FROM " . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%m.%Y') = '$monat.$sy'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$event[date('d', $row['event_date'])][] = $row;
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT m.match_id, m.match_rival_name, m.match_date, g.game_image, t.team_name
				FROM " . MATCH . " m
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%m.%Y') = '$monat.$sy'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$match[date('d', $row['match_date'])][] = $row;
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT tr.*, g.game_image, t.team_name
				FROM " . TRAINING . " tr
					LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id	
				WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%m.%Y') = '$monat.$sy'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$train[date('d', $row['training_date'])][] = $row;
	}
	$db->sql_freeresult($result);

	$monat_data = array_merge(array('bday' => $bday), array('news' => $news), array('event' => $event), array('match' => $match), array('train' => $train));
}

$viewer = $settings['calendar']['show'];

if ( $viewer )
{
	$template->assign_block_vars('month', array());
	
	$ws = $settings['calendar']['start'];
	
	$edmk = $arr_woche_kurz[$erster];
	$wbmk = $arr_woche_kurz;
	for ( $i = 0; $i < $ws; $i++ )
	{
		$wechsel = array_shift($wbmk);
		$wbmk[] = $wechsel;
	}
	$wbmk_wechsel = array_flip($wbmk);
	
	$days = '<tr>';
	for ( $i = 0; $i < 7; $i++ )
	{
		$days .= '<th>' . $wbmk[$i] . '</th>';
	}
	$days .= '</tr>';
	
	$day = '<tr>';
	for ( $i = 0; $i < $wbmk_wechsel[$edmk]; $i++ )
	{
		$day .= '<td>&nbsp;</td>';
	}
	
	$wcs = $wbmk_wechsel[$edmk];
	
	debug($wcs);
	
#	debug($monat_data);
	
	for ( $i = 1; $i < $tage + 1; $i++ )
	{
		$i = ( $i < 10 ) ? "0$i" : $i;
		
		$bday	= isset($monat_data['bday'][$i]) ? $monat_data['bday'][$i] : '';
		$news	= isset($monat_data['news'][$i]) ? $monat_data['news'][$i] : '';
		$event	= isset($monat_data['event'][$i]) ? $monat_data['event'][$i] : '';
		$match	= isset($monat_data['match'][$i]) ? $monat_data['match'][$i] : '';
		$train	= isset($monat_data['train'][$i]) ? $monat_data['train'][$i] : '';
		
		if ( $i == $tag || is_array($bday) || is_array($news) || is_array($event) || is_array($match) || is_array($train) )
		{
			$css = '';
			$act = '';
			$num = '0';
			
			if ( $i == $tag )
			{
				$num = $num + 1;
				$css = 'today';
				$act = "<span class=\"today\" title=\"{$lang['cal_today']}\">{$lang['cal_today']}</span>";
			}
			
			if ( is_array($bday) && $settings['calendar']['bday'] )
			{
				$ary = array();
				$cnt = count($bday);
				
				for ( $k = 0; $k < $cnt; $k++ )
				{
					$alter	= 0;
					$gebdt	= explode('-', $bday[$k]['user_birthday']);
					$gebdt	= $gebdt[0].$gebdt[1].$gebdt[2];
					$now	= date('Ymd', time());
					
					while ( $gebdt < $now - 9999 )
					{
						$alter++;
						$gebdt = $gebdt + 10000;
					}
	
					$ary[] = sprintf($lang['cal_birth'], $bday[$k]['user_name'], $alter);
				}
				
				$num = $num + 1;
				$css  = 'birthday';
				$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['cal_birthday'] : $lang['cal_birthdays'], $ary, false);
			}
			
			if ( is_array($news) && $settings['calendar']['news'] )
			{
				$ary = array();
				$cnt = count($news);
				
				for ( $k = 0; $k < $cnt; $k++ )
				{
					$id		= $news[$k]['news_id'];
					$name	= $news[$k]['news_title'];
					$cut	= cal_cut($name, 20);
					
					$ary[]	= "<a title=\"$name\" href=\"news.php?mode=view&" . POST_NEWS . "=$id\">$cut</a>";
				}
				
				$num = $num + 1;
				$css = 'news';
				$act .= cal_string($act, $css, $lang['cal_news'], $ary, false);
			}
					
			if ( is_array($event) && $settings['calendar']['event'] )
			{
				$ary = array();
				$cnt = count($event);
				
				for ( $k = 0; $k < $cnt; $k++ )
				{
					if ( $userdata['user_level'] >= $event[$k]['event_level'] )
					{
						$id		= $event[$k]['event_id'];
						$name	= $event[$k]['event_title'];
						$cut	= cal_cut($name, 20);
						
						$ary[] = "<a title=\"$name\" href=\"event.php?mode=view&" . POST_EVENT . "=$id\">$cut</a>";
					}
				}
				
				if ( !empty($ary) )
				{
					$num = $num + 1;
					$css = 'events';
					$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['cal_event'] : $lang['cal_events'], $ary, false);
				}
			}
			
			if ( is_array($match) && $settings['calendar']['match'] )
			{
				$ary = array();
				$cnt = count($match);
				
				for ( $k = 0; $k < $cnt; $k++ )
				{
					$id		= $match[$k]['match_id'];
					$name	= $match[$k]['match_rival_name'];
					$cut	= cal_cut($name, 20);
					
					$ary[] = "<a title=\"$name\" href=\"match.php?mode=view&" . POST_MATCH . "=$id\">$cut</a>";
				}
				
				$num = $num + 1;
				$css = 'wars';
				$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['cal_match'] : $lang['cal_matchs'], $ary, false);
			}
			
			if ( is_array($train) && $userdata['user_level'] >= TRIAL && $settings['calendar']['train'] )
			{
				$ary = array();
				$cnt = count($train);
				
				for ( $k = 0; $k < $cnt; $k++ )
				{
					$id		= $train[$k]['training_id'];
					$name	= $train[$k]['training_vs'];
					$cut	= cal_cut($name, 20);
					
					$ary[] = "<a title=\"$name\" href=\"training.php?mode=view&" . POST_TRAINING . "=$id\">$cut</a>";
				}
				
				$css = 'trains';
				$act .= cal_string($act, $css, ( count($ary) == '1' ) ? $lang['cal_training'] : $lang['cal_trainings'], $ary, false);
				$num = $num + 1;
			}
			
			if ( $num )
			{
				$class = ( $num > 1 ) ? 'more' : $css;
				
				$day .= "<td class=\"$class\" ><a class=\"$class right\" href=\"" . check_sid("calendar.php?mode=view") . "\">$i</a><span class=\"$css\">$act</span></td>";
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
	
	for ( $wcs; $wcs < 7; $wcs++ )
	{
		$day .= '<td>&nbsp;</td>';
	}
	
	$day .= '</tr>';
	
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
	
	for ( $i = 1; $i < $tage + 1; $i++ )
	{
		$weekday = $arr_woche_kurz[date("w", mktime(0, 0, 0, $monat, $i, $jahr))];
		
		$i = ( $i < 10 ) ? '0' . $i : $i;
		
	#	if ( $i == $tag ||  || is_array($bday) || is_array($event) || is_array($match) || is_array($train) )
	#	{
			$act = '';
			
			if ( $i == $tag && $jahr == $sy )
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
					$date	= mktime(0, 0, 0, $monat, $tag, $jahr);
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
					
					$ary[]	= "<a title=\"$name\" href=\"news.php?" . POST_NEWS . "=$id\">$cut</a>";
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
						
						$ary[] = "<a href=\"event.php?" . POST_EVENT . "=$id\">$title: $diff</a>";
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
					
				#	$ary[] = $team_image . " <a href=\"match.php?mode=view&" . POST_MATCH . "=$match_id\">$match_vs - $match_time</a>";
					$ary[] = "<a title=\"$name\" href=\"match.php?" . POST_MATCH . "=$id\">$cut</a>";
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
					
					$ary[] = $team_image . " <a href=\"training.php?" . POST_TRAINING . "=$training_id\">$training_vs - $training_time</a>";
				}
				
				$day = $i;
				$css = 'trains';
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

$nm = ( ($monat+1) == 13 ) ? 1 : $monat + 1;
$ny = ( ($monat+1) == 13 ) ? $jahr + 1 : $jahr;

$pm = ( ($monat-1) == 0 ) ? 12 : $monat - 1;
$py = ( ($monat-1) == 0 ) ? $jahr - 1 : $jahr;

$template->assign_vars(array(
	'CAL_MONTH'	=> sprintf($lang['sprintf_empty_line'], $sm, $jahr),
	
	'NEXT'		=> "?month=$nm&year=$ny",
	'PREV'		=> "?month=$pm&year=$py",
	
	'L_CAL'		=> $lang['head_calendar'],
	'L_LEGEND'	=> $lang['cal_legend'],
	
	'CAL_CACHE' => ( $settings['calendar']['cache'] && defined('CACHE') ) ? display_cache($sCacheName, 1) : '',
));

$template->pparse('body');

main_footer();

?>