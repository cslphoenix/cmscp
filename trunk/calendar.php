<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 */

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_CALENDAR);
init_userprefs($userdata);

$page_title = $lang['head_calendar'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'body_calendar.tpl'));

$tag			= date("d", time()); // Heutiger Tag: z. B. "1"
$tag_der_woche	= date("w"); // Welcher Tag in der Woch: z. B. "0 / Sonntag"
$tage_im_monat	= date("t"); // Anzahl der Tage im Monat: z. B. "31"
$monat			= date("m", time());
$jahr			= date("Y", time());
$erster			= date("w", mktime(0, 0, 0, $monat, 1, $jahr)); // Der erste Tag im Monat: z. B. "5 / Freitag"
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

if ( $userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN )
{
	if ( defined('CACHE') )
	{
		$sCacheName = 'calendar_' . $monat . '_member';

		if (($monat_data = $oCache -> readCache($sCacheName)) === false)
		{
			for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
			{
				if ( $i < 10 ) { $i = '0' . $i; }
				
				$sql = 'SELECT username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_b = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT event_date, event_duration, event_title FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_e = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT match_rival, match_date FROM ' . MATCH . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$day_rows_w = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT training_vs, training_date FROM ' . TRAINING . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
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
		for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
		{
			if ( $i < 10 ) { $i = '0' . $i; }
			
			$sql = 'SELECT username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_b = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			
			$sql = 'SELECT event_date, event_duration, event_title FROM ' . EVENT . " WHERE DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_e = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			
			$sql = 'SELECT match_rival, match_date FROM ' . MATCH . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_w = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			
			$sql = 'SELECT training_vs, training_date FROM ' . TRAINING . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$day_rows_t = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			
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
	
	foreach ($monat_data_b as $monat_birthday) {}
	foreach ($monat_data_e as $monat_events) {}
	foreach ($monat_data_w as $monat_matchs) {}
	foreach ($monat_data_t as $monat_trainings) {}
}
else
{
	if (defined('CACHE'))
	{
		$sCacheName = 'calendar_' . $monat . '_guest';

		if (($monat_data = $oCache -> readCache($sCacheName)) === false)
		{
			for ( $i=1; $i<$tage_im_monat+1; $i++ )
			{
				if ($i < 10)
				{
					$i = '0'.$i;
				}
				
				$sql = 'SELECT username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
				$result = $db->sql_query($sql);
				$day_rows_b = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT event_date, event_duration, event_title FROM ' . EVENT . " WHERE event_level = 0 AND DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
				$result = $db->sql_query($sql);
				$day_rows_e = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = 'SELECT * FROM ' . MATCH . " WHERE match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
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
			
			$sql = 'SELECT username, user_birthday FROM ' . USERS . " WHERE MONTH(user_birthday) = " . $monat . " AND DAYOFMONTH(user_birthday) = " . $i;
			$result = $db->sql_query($sql);
			$day_rows_b = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			
			$sql = 'SELECT event_date, event_duration, event_title FROM ' . EVENT . " WHERE event_level = 0 AND DATE_FORMAT(FROM_UNIXTIME(event_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
			$result = $db->sql_query($sql);
			$day_rows_e = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			
			$sql = 'SELECT * FROM ' . MATCH . " WHERE match_public = 1 AND DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '" . $i."." . $monat."." . $jahr."'";
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

$cal_day = '';
$cal_days = '';

for ( $i = 1; $i < $tage_im_monat + 1; $i++ )
{
	$cal_weekday = $arr_woche_kurz[date("w", mktime(0, 0, 0, $monat, $i, $jahr))];
	
	if ( $i < 10 ) { $i = '0' . $i; }
	
	if ($userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN )
	{
		if ($i == $tag || is_array($monat_birthday[$i]) || is_array($monat_events[$i]) || is_array($monat_matchs[$i]) || is_array($monat_trainings[$i]))
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
				for ( $k=0; $k < count($monat_events[$i]); $k++ )
				{
					$list[] = $monat_events[$i][$k]['event_title'];
				}
				
				$language		= (count($list) == 1) ? $lang['cal_event'] : $lang['cal_events'];
				$list			= implode('<br>', $list);
				$cal_day		= $i;
				$cal_event		.= (empty($cal_event)) ? '<span><em class="events">' . $language . ':</em> ' . $list : '<br><em class="events">' . $language . '</em><br>' . $list;
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
				$cal_day		= $i;
				$cal_event		.= (empty($cal_event)) ? '<span><em class="wars">' . $language . ':</em> ' . $list : '<br><em class="wars">' . $language . '</em><br>' . $list;
			}
			
			if ( is_array($monat_trainings[$i]) )
			{
				$list = array();
				for ( $k=0; $k < count($monat_trainings[$i]); $k++ )
				{
					$list[] = $monat_trainings[$i][$k]['training_vs'];
				}
				
				$language		= (count($list) == 1) ? $lang['cal_training'] : $lang['cal_trainings'];
				$list			= implode('<br>', $list);
				$cal_day		= $i;
				$cal_event		.= (empty($cal_event)) ? '<span><em class="trains">' . $language . ':</em> ' . $list : '<br><em class="trains">' . $language . '</em><br>' . $list;
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
		if ($i == $tag || is_array($monat_birthday[$i]) || is_array($monat_events[$i]) || is_array($monat_matchs[$i]) )
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
				for ( $k=0; $k < count($monat_events[$i]); $k++ )
				{
					$list[] = $monat_events[$i][$k]['event_title'];
				}
				
				$language		= (count($list) == 1) ? $lang['cal_event'] : $lang['cal_events'];
				$list			= implode('<br>', $list);
				$cal_day		= $i;
				$cal_event		.= (empty($cal_event)) ? '<span><em class="events">' . $language . ':</em> ' . $list : '<br><em class="events">' . $language . '</em><br>' . $list;
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
				$cal_day		= $i;
				$cal_event		.= (empty($cal_event)) ? '<span><em class="wars">' . $language . ':</em> ' . $list : '<br><em class="wars">' . $language . '</em><br>' . $list;
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

$template->assign_vars(array(
	'CAL_MONTH'	=> $month,
	
	'L_CAL'		=> $lang['head_calendar'],
));


$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>