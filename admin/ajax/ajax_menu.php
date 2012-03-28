<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['mode']) )
{
	$_menu = $_POST['mode'];
	$umenu = $userdata['user_acp_menu'] ? unserialize($userdata['user_acp_menu']) : false;
	
	if ( $umenu )
	{
		foreach ( $umenu as $keys => $rows )
		{
			$key[]		= $keys;
			$row[$keys] = $rows;
		}
		
		/*
		$value = '';
		
		if ( in_array($_menu, $key) )
		{
			$value = ( $row[$_menu] == '1' ) ? '0':'1';
			$tmpsvars = serialize(array_merge($row, array($_menu => $value)));
		}
		else
		{
			$tmpsvars = serialize(array_merge($row, array($_menu => 1)));
		}
		*/
		
		$tmpsvars = serialize(array_merge($row, array($_menu => ( in_array($_menu, $key) ) ? ( $row[$_menu] == 1 ) ? 0 : 1 : 0)));
		
		$sql = "UPDATE " . USERS . " SET user_acp_menu = '$tmpsvars' WHERE user_id = " . $userdata['user_id'];
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		$sql = "UPDATE " . USERS . " SET user_acp_menu = '" . serialize(array($_menu => 1)) . "' WHERE user_id = " . $userdata['user_id'];
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
}
else
{
	echo 'There should be no direct access to this script!';
}

?>