<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['server']) || isset($_POST['hltv']) )
{
	$fill = ( isset($_POST['server']) ) ? 'set_server' : 'set_hltv';
	$post = ( isset($_POST['server']) ) ? $_POST['server'] : $_POST['hltv'];
	
	if ( strlen($post) > 0 )
	{
		$sql = "SELECT server_name, server_ip, server_port FROM " . SERVER . " WHERE server_ip LIKE '%$post%' OR server_name LIKE '%$post%'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp = $db->sql_fetchrowset($result);

		if ( $tmp )
		{
			for ( $i = 0; $i < count($tmp); $i++ )
			{
				echo '<li onClick="' . $fill . '(\'' . $tmp[$i]['server_ip'] . ':' . $tmp[$i]['server_port'] . '\');">' . $tmp[$i]['server_name'] . '</li>';
			}
		}
		else
		{
			echo "<li  onClick=\"$fill('$post');\">" . $lang['new_entry'] . "</li>";
		}
	}
}
else
{
	echo 'There should be no direct access to this script!';
}

?>