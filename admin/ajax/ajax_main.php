<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

debug($_POST);

if ( isset($_POST['type']) )
{
	$type = $_POST['type'];
	$meta = $_POST['meta'];
	$name = $_POST['name'];
	
	switch ( $meta )
	{
		case 'menu':	$tbl = MENU2; $field_id = $meta . '_id'; $field_name = $meta . '_name'; $field_order = $meta . '_order'; break;
		case 'forum':	$tbl = FORMS; $field_id = $meta . '_id'; $field_name = $meta . '_name'; $field_order = $meta . '_order'; break;
	}
	
	$sql = 'SELECT * FROM ' . $tbl . ' ORDER BY main ASC, ' . $field_order . ' ASC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $db->sql_numrows($result) )
	{
		$tmp = $db->sql_fetchrowset($result);
		
		foreach ( $tmp as $rows )
		{
			if ( !$rows['type'] )
			{
				$cat[$rows[$field_id]] = $rows;
			}
			else if ( $rows['type'] == 1 )
			{
				$lab[$rows['main']][$rows[$field_id]] = $rows;
			}
			else
			{
				$sub[$rows['main']][$rows[$field_id]] = $rows;
			}
		}
	}
	
	ksort($cat);
	ksort($lab);
	
#	debug($name);
	
	$opt = '<div id="close"><select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
	
	foreach ( $cat as $ckey => $crow )
	{
		$lng = isset($lang[$crow[$field_name]]) ? $lang[$crow[$field_name]] : $crow[$field_name];
		
		$shut = ( $type == 2 ) ? ' disabled="disabled"': '';
		
		$opt .= '<option value="' . $crow[$field_id] . '"' . $shut . '>' . $lng . '</option>';
			
		if ( isset($lab[$ckey]) )
		{
			foreach ( $lab[$ckey] as $lkey => $lrow )
			{
				$lng = isset($lang[$lrow[$field_name]]) ? $lang[$lrow[$field_name]] : $lrow[$field_name];
				
				$shut = ( $type == 1 ) ? ' disabled="disabled"': '';
				
				$opt .= '<option value="' . $lrow[$field_id] . '"' . $shut . '>&nbsp; &nbsp;' . $lng . '</option>';
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