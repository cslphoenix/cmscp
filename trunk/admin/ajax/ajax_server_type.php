<?php

/*
 *	require: acp_match, acp_train
 */

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['mode']) )
{
	$key = intval($_POST['mode']);
	
	$s_select = '';
	
	if ( isset($key) )
	{
		$sql = "SELECT * FROM " . SERVER_TYPE . " WHERE type_sort = $key";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp = $db->sql_fetchrowset($result);
		
		if ( $tmp )
		{
			$s_select .= "<select class=\"select\" name=\"server_game\">";
		
			foreach ( $tmp as $row )
			{
			#	$selected = ( $default == $row['type_game'] ) ? ' selected="selected"' : '';
				$s_select .= "<option value=\"" . $row['type_game'] . "\">" . sprintf($lang['sprintf_select_format'], $row['type_name']) . "</option>";
			}
			$s_select .= "</select>";
			
			
			
		#	for ( $i = 0; $i < count($tmp); $i++ )
		#	{
		#		echo '<li onClick="fill(\'' . $tmp[$i]['server_ip'] . ':' . $tmp[$i]['server_port'] . '\');">' . $tmp[$i]['server_name'] . '</li>';
		#	}
		}
		else
		{
			echo 'ERROR: There was a problem with the query.';
		}
	}
	
	echo $s_select;
}
else
{
	echo 'There should be no direct access to this script!';
}

?>