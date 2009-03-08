<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//
//	Start session management
//
$userdata = session_pagestart($user_ip, PAGE_CALENDAR);
init_userprefs($userdata);

$page_title = $lang['Index'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'calendar_body.tpl'));

	$tag			= date("d", time()); // Heutiger Tag: z. B. "1"
	$tag_der_woche	= date("w"); // Welcher Tag in der Woch: z. B. "0 / Sonntag"
	$tage_im_monat	= date("t"); // Anzahl der Tage im Monat: z. B. "31"
	$monat			= date("m", time());
	$jahr			= date("Y", time());
	$erster			= date("w", mktime(0, 0, 0, $monat, 1, $jahr)); // Der erste Tag im Monat: z. B. "5 / Freitag"
	$arr_woche_kurz	= array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
	
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
	
	
	for ( $i=0; $i<7; $i++ )
	{
		$cal_days = $wbmk[$i];
		
		$template->assign_block_vars('days', array(
			'CAL_DAYS'	=> $cal_days,
		));
	}
	
	$cal_day = '';
	$cal_days = '';
	for ( $i=1; $i<$tage_im_monat+1; $i++ )
	{
		if ($i < 10)
		{
			$i = '0'.$i;
		}
		
		if ( $i == $tag )
		{
			$cal_day = '<b>' . $i . '</b>';
		}
		else
		{
			$cal_day = '' . $i . '';
		}
		
		
		$template->assign_block_vars('days', array(
			'CAL_DAY'	=> $cal_day,
		));
															
		
		
		
		/*
		if ($i == $tag)
		{
			$cal_day .= '<td align="center" style="color:#F00">' . $i . '</td>';
		}
		else
		{
			$cal_day .= '<td align="center">' . $i . '</td>';
		}
		*/
		
	}
	
	$template->assign_vars(array(
		'CAL_MONTH'	=> $month,
//		'CAL_DAYS'	=> $cal_days,
//		'CAL_DAY'	=> $cal_day,
	));


$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>