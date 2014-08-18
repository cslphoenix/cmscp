<?php

define('IN_CMS', true);

$root_path	= './../../';

@include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['queryString']) )
{
	$queryString = $_POST['queryString'];
	
	if ( strlen($queryString) > 0 )
	{
		$sql = "SELECT server_name, server_ip, server_port FROM " . SERVER . " WHERE server_ip LIKE '$queryString%' OR server_name LIKE '$queryString%'";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
			exit;
		}
		$tmp = $db->sql_fetchrowset($result);

		if ( $tmp )
		{
			for ( $i = 0; $i < count($tmp); $i++ )
			{
				echo '<li onClick="fill(\'' . $tmp[$i]['server_ip'] . ':' . $tmp[$i]['server_port'] . '\');">' . $tmp[$i]['server_name'] . '</li>';
			}
		}
		else
		{
			echo 'ERROR: There was a problem with the query.';
		}
	}
}
else
{
	echo 'There should be no direct access to this script!';
}

?>