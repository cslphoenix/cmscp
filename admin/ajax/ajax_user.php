<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);
define('IN_AJAX', true);

$root_path = './../../';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);
#debug($_POST);
if ( isset($_POST['cash_name']) || isset($_POST['user_name']) || isset($_POST['user_id']) )
{
	$fill_type	= isset($_POST['user_name']) ? 'fill' : (isset($_POST['cash_name']) ? 'set_cash_name' : 'set_user_id');
	$user_name	= isset($_POST['user_name']) ? str_replace("'", "\'", $_POST['user_name']) : ((isset($_POST['cash_name']) ? str_replace("'", "\'", $_POST['cash_name']) : str_replace("'", "\'", $_POST['user_id'])));
	$user_name	= str_replace('*', '%', strtolower($user_name));
	$user_new	= isset($_POST['user_new']) ? str_replace("'", "\'", $_POST['user_new']) : '';
	$user_level	= isset($_POST['user_level']) ? "AND user_level >= " . str_replace("'", "\'", $_POST['user_level']) : false;
	
	$sql = "SELECT user_id, user_name, user_level, user_regdate, user_lastvisit, user_dateformat FROM " . USERS . " WHERE LOWER(user_name) LIKE '%$user_name%' AND user_id != 1 $user_level";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
		exit;
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
			
			echo "<li onclick=\"$fill_type('$name');\">" . sprintf($lang['STF_AJAX_USERS'], $name, $reg, $log) . "</li>";
			
			if ( $i == 4 )
			{
				echo '&nbsp;&raquo;&nbsp;' . sprintf($lang['stf_ajax_more'], $num) . '';
				break;
			}
		}
	}
	else
	{
		if ( $user_new )
		{
			echo '<li onclick="$fill_type(\'' . $user_name . '\');">' . $lang['new_entry'] . '</li>';
		}
		else
		{
			echo sprintf($lang['STF_SELECT_FORMAT'], $lang['no_entry']);
		}
	}
}
else
{
	echo sprintf($lang['STF_SELECT_FORMAT'], $lang['no_entry']);
}

?>