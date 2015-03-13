<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['type']) )
{
	$type = $_POST['type'];
	$meta = $_POST['meta'];
	$name = $_POST['name'];
	
	$sql = "SELECT * FROM " . MENU . " ORDER BY main ASC, menu_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
		exit;
	}
	$tmp_db = $db->sql_fetchrowset($result);
	
	if ( isset($tmp_db) )
	{
		foreach ( $tmp_db as $rows )
		{
			if ( !$rows['type'] )
			{
				$cat[$rows['menu_id']] = $rows;
			}
			else if ( $rows['type'] == 1 )
			{
				$lab[$rows['main']][$rows['menu_id']] = $rows;
			}
			else
			{
				$sub[$rows['main']][$rows['menu_id']] = $rows;
			}
		}
	}
	
	ksort($cat);
	ksort($lab);
	
	debug($name, 'name test');
	
	$opt = '<div id="close"><select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
	
	foreach ( $cat as $ckey => $crow )
	{
		$shut = ( $type == 2 ) ? ' disabled="disabled"': '';
		
		$opt .= '<option value="' . $crow['menu_id'] . '"' . $shut . '>' . $crow['menu_name'] . ' :: ' . $crow['menu_id'] . '</option>';
			
		if ( isset($lab[$ckey]) )
		{
			foreach ( $lab[$ckey] as $lkey => $lrow )
			{
				$shut = ( $type == 1 ) ? ' disabled="disabled"': '';
				
				$opt .= '<option value="' . $lrow['menu_id'] . '"' . $shut . '>&nbsp; &nbsp;' . $lrow['menu_name'] . ' :: ' . $lrow['menu_id'] . '</option>';
			}
		}
	}
	
	$opt .= '</select></div><div id="ajax_content"></div>';
	
	echo $opt;
}
else
{
	echo 'There should be no direct access to this script!';
}

?>