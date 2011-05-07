<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['user_name']) )
{
	$user_name = str_replace("'", "\'", $_POST['user_name']);
	
	$sql = "SELECT user_id, user_name, user_level, user_regdate, user_lastvisit, user_dateformat FROM " . USERS . " WHERE LOWER(user_name) LIKE '%" . strtolower($user_name) . "%' LIMIT 5";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$user = $db->sql_fetchrowset($result);
	
	if ( $user )
	{
		for ( $i = 0; $i < count($user); $i++ )
		{
			$reg = date($user[$i]['user_dateformat'], $user[$i]['user_regdate']);
			$log = date($user[$i]['user_dateformat'], $user[$i]['user_lastvisit']);
			
			echo '<li onclick="fill(\'' . $user[$i]['user_name'] . '\');">' . sprintf($lang['sprintf_ajax_users'], $user[$i]['user_name'], $user[$i]['user_level'], $reg, $log) . '</li>';
		}
	}
	else
	{
		echo '<li onclick="fill(\'' . $user_name . '\');">' . $lang['new_entry'] . '</li>';
	}
}
else
{
	echo sprintf($lang['sprintf_select_format'], $lang['no_entry']);
}

?>