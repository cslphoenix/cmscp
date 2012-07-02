<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);
define('IN_AJAX', true);

$root_path = './../../';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['user_name']) )
{
	$user_name	= str_replace("'", "\'", $_POST['user_name']);
	$user_name	= str_replace('*', '%', strtolower($user_name));
	$user_new	= isset($_POST['user_new']) ? str_replace("'", "\'", $_POST['user_new']) : '';
	$user_level	= isset($_POST['user_level']) ? "AND user_level >= " . str_replace("'", "\'", $_POST['user_level']) : false;
	
	$sql = "SELECT user_id, user_name, user_level, user_regdate, user_lastvisit, user_dateformat FROM " . USERS . " WHERE LOWER(user_name) LIKE '%$user_name%' AND user_id != 1 $user_level";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$tmp = $db->sql_fetchrowset($result);
	
	if ( $tmp )
	{
		$cnt = count($tmp);
		$num = $cnt - 5;
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$name	= $tmp[$i]['user_name'];
			$reg	= date($tmp[$i]['user_dateformat'], $tmp[$i]['user_regdate']);
			$log	= date($tmp[$i]['user_dateformat'], $tmp[$i]['user_lastvisit']);
			
			switch ( $tmp[$i]['user_level'] )
			{
				case '0': $lvl = $lang['auth_guest'];	break;
				case '1': $lvl = $lang['auth_user'];	break;
				case '2': $lvl = $lang['auth_trial'];	break;
				case '3': $lvl = $lang['auth_member'];	break;
				case '4': $lvl = $lang['auth_mod'];		break;
				case '5': $lvl = $lang['auth_admin'];	break;
				
				default: $lvl = $tmp[$i]['user_level'];	break;
			}				
			
			echo '<li onclick="fill(\'' . $name . '\');">' . sprintf($lang['sprintf_ajax_users'], $name, $lvl, $reg, $log) . '</li>';
			
			if ( $i == 4 )
			{
				echo '&nbsp;&raquo;&nbsp;' . sprintf($lang['sprintf_ajax_more'], $num) . '';
				break;
			}
		}
	}
	else
	{
		if ( $user_new )
		{
			echo '<li onclick="fill(\'' . $user_name . '\');">' . $lang['new_entry'] . '</li>';
		}
		else
		{
			echo sprintf($lang['sprintf_select_format'], $lang['no_entry']);
		}
	}
}
else
{
	echo sprintf($lang['sprintf_select_format'], $lang['no_entry']);
}

?>