<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//
//	Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

$page_title = $lang['Index'];
include($root_path . 'includes/page_header.php');
/*
$sql = "SELECT * FROM ". MATCH_TABLE." WHERE match_id = 4";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
}

if ( !($row = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'nope');
}
_debug_post($row);


$monat			= date("m", time());
$jahr			= date("Y", time());
	
$year = date('Y', $row['match_date']);
$month = date('m', $row['match_date']);
$day = date('d', $row['match_date']);

echo $year.$month.$day;

//$sql = "SELECT * FROM ". MATCH_TABLE." WHERE YEAR(".$year.") = 2009 AND MONTH(".$month.") = 3 AND DAYOFMONTH(".$day.") = 28";

$sql = "SELECT * FROM ". MATCH_TABLE." WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '".$day.".".$month.".".$year."'";

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
}

$day_rows = array();
while ($row = $db->sql_fetchrow($result))
{
	$day_rows[] = $row;
}
$db->sql_freeresult($result);

_debug_post($day_rows);

<a class="f_popup" href="#">{open_fightrow.FIGHT_NAME}</a>
		<span>
Fightname: <b>{open_fightrow.FIGHT_NAME}</b> <br />
Slots: {open_fightrow.SLOTS} <br />
Map 2: {open_fightrow.TYPE} <br />
Map 1: {open_fightrow.MAP_A} <br />
Map 2: {open_fightrow.MAP_B} <br />
Server: {open_fightrow.SERVER}
</span>
*/
/*
$monat	= date("m", time());
$sql = 'SELECT * FROM ' . MATCH_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%m') = '".$monat."'";
$day_rows_w[] = _cached($sql, $monat.'_w_kalender');

$sql = 'SELECT * FROM ' . TRAINING_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_start), '%m') = '".$monat."'";
$day_rows_t[] = _cached($sql, $monat.'_t_kalender');
_debug_post($day_rows_w);
_debug_post($day_rows_t);

*/
$tag			= date("j", time());
$monat	= date("m", time());
$jahr		= date("Y", time());

$day_rows = array();
for ( $i=1; $i<31; $i++ )
{
	if ($i < 10)
	{
		$i = '0'.$i;
	}
	
	$sql = 'SELECT * FROM ' . MATCH_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
	$day_rows_w[$i] = _cached($sql, $monat.'-'. $i . '_w_kalender');
	
	$sql = 'SELECT * FROM ' . TRAINING_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_start), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
	$day_rows_t[$i] = _cached($sql, $monat.'-'. $i . '_t_kalender');
	
	
	if ($i == $tag )
	{
		echo '<br /><b>' . $i . '</b>';
	}
	else if ( is_array($day_rows_w[$i]) )
	{
		echo '<br />' . $i . ' <a class="f_popup" href="#">Match<span>test infos '.count($day_rows_w[$i]).' </span></a>';
	}
	else if ( is_array($day_rows_t[$i]) )
	{
//		_debug_post($day_rows_t[$i]);
		
		
		for ( $k=0; $k< count($day_rows_t[$i]); $k++ )
		{
			$test[] = $day_rows_t[$i][$k]['training_vs'];
//			_debug_post($day_rows[$i][$k]);
		}
		
		$test = implode('<br />', $test);
		
		echo '<br />' . $i . ' <a class="f_popup" href="#">Train<span>test infos <br />'.$test.' </span></a>';
	}

/*	
	if ($i == $tag || is_array($day_rows_w[$i]))
	{
		if ($i == $tag && is_array($day_rows_w[$i]) )
		{
			echo '<br /><b>' . $i . ' Match </b>';
		}
		else if ($i == $tag )
		{
			echo '<br /><b>' . $i . '</b>';
		}
		else if ( is_array($day_rows_w[$i]) )
		{
			
			echo '<br />' . $i . ' <a class="f_popup" href="#">Match<span>test infos '.count($day_rows_w[$i]).' </span></a>';
//			_debug_post($day_rows[$i]);
		}
	}
*/	
	else
	{
		echo '<br />' . $i . '';
	}
}

echo '<br />';


//$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>