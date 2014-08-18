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
	
	$sql = "SELECT * FROM " . FORUM . " ORDER BY main ASC, forum_order ASC";
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
				$cat[$rows['forum_id']] = $rows;
			}
			else if ( $rows['type'] == 1 )
			{
				$forum[$rows['main']][$rows['forum_id']] = $rows;
			}
			else
			{
				$subforum[$rows['main']][$rows['forum_id']] = $rows;
			}
		}
	}
	
	ksort($forum);
	ksort($subforum);
	
	$s_select = '<select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
	
	foreach ( $cat as $ckey => $crow )
	{
		$shut = ( $type == '2' ) ? ' disabled="disabled"': '';
		$s_select .= '<option value="' . ($type ? $crow['forum_id'] : $crow['forum_id']) . '"' . $shut . '>' . $crow['forum_name'] . '</option>';
			
		if ( isset($forum[$ckey]) )
		{
			foreach ( $forum[$ckey] as $fkey => $frow )
			{
				$shut = ( $type == '1' ) ? ' disabled="disabled"': '';
				$s_select .= '<option value="' . $frow['forum_id'] . '"' . $shut . '>&nbsp; &nbsp;' . $frow['forum_name'] . '</option>';
				
				if ( isset($subforum[$fkey]) )
				{
					foreach ( $subforum[$fkey] as $skey => $srow )
					{
						$shut = ' disabled="disabled"';
						$s_select .= '<option value="' . $srow['forum_id'] . '"' . $shut . '>&nbsp; &nbsp;&nbsp; &nbsp;' . $srow['forum_name'] . '</option>';
					}
				}
			}
		}
	}
	
	$s_select .= '</select>';
	
	echo $s_select;
}
else
{
	echo sprintf($lang['stf_select_format'], $lang['msg_empty_forums']);
}

?>