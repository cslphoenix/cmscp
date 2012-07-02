<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['user_name']) && isset($_POST['type']) && isset($_POST['type_id']) )
{
	$user_name = str_replace("'", "\'", $_POST['user_name']);
	$user_name = str_replace('*', '%', strtolower($user_name));
	
	$type = str_replace("'", "\'", $_POST['type']);
	$type_id = str_replace("'", "\'", $_POST['type_id']);
	
	switch ( $type )
	{
		case 'g':
			$tbl_sql	= GROUPS;
			$tbl_usr	= GROUPS_USERS;
			$tbl_id		= 'group_id';
			
			break;
		
		case 't':
			$tbl_sql	= TEAMS;
			$tbl_usr	= TEAMS_USERS;
			$tbl_id		= 'team_id';
			
			break;
	}
	
	$sql = "SELECT u.user_id FROM $tbl_usr tu
				LEFT JOIN " . USERS . " u ON u.user_id = tu.user_id
			 WHERE $tbl_id = $type_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$in_team[] = $row['user_id'];
	}
	
	$sql = "SELECT user_id, user_name, user_level, user_regdate, user_lastvisit, user_dateformat
				FROM " . USERS . "
			WHERE LOWER(user_name) LIKE '%$user_name%' AND user_id != 1 AND NOT user_id IN (" . implode(', ', $in_team) . ")";
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
		echo sprintf($lang['sprintf_select_format'], $lang['no_entry']);
	}
}
else
{
	echo sprintf($lang['sprintf_select_format'], $lang['no_entry']);
}

?>