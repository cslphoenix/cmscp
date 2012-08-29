<?php

/*
 *	require: 
 */

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['match_server_ip']) || isset($_POST['match_hltv_ip']) )
{
	$fill = ( isset($_POST['match_server_ip']) ) ? 'set_match_server_ip' : 'set_match_hltv_ip';
	$post = ( isset($_POST['match_server_ip']) ) ? $_POST['match_server_ip'] : $_POST['match_hltv_ip'];
	
	$post = str_replace("'", "\'", $post);
	$post = str_replace('*', '%', strtolower($post));
	
	if ( strlen($post) > 0 )
	{
		$sql = "SELECT server_name, server_ip, server_port FROM " . SERVER . " WHERE server_type = '0' AND (server_ip LIKE '%$post%' OR server_name LIKE '%$post%')";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp_s = $db->sql_fetchrowset($result);
		
		$sql = "SELECT DISTINCT match_server_ip FROM " . MATCH . " WHERE match_server_ip LIKE '%$post%'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp_m = $db->sql_fetchrowset($result);
		
		if ( $tmp_s || $tmp_m )
		{
			if ( $tmp_s )
			{
				$cnt_s = count($tmp_s);
				$num_s = $cnt_s - 5;
				
				for ( $i = 0; $i < $cnt_s; $i++ )
				{
					echo '<li onClick="' . $fill . '(\'' . $tmp_s[$i]['server_ip'] . ':' . $tmp_s[$i]['server_port'] . '\');">' . $tmp_s[$i]['server_name'] . '</li>';
					
					if ( $i == 4 && !$cnt_s )
					{
						echo '<li class="more">&nbsp;&raquo;&nbsp;' . sprintf($lang['sprintf_ajax_more'], $num_s) . '</li>';
						break;
					}
				}
			}
			
			if ( $tmp_m )
			{
				$cnt_m = count($tmp_m);
				$num_m = $cnt_m - 5;
				
				for ( $i = 0; $i < $cnt_m; $i++ )
				{
					echo '<li onClick="' . $fill . '(\'' . $tmp_m[$i]['match_server_ip'] . '\');">' . $tmp_m[$i]['match_server_ip'] . '</li>';
					
					if ( $i == 4 )
					{
						echo '<li class="more">&nbsp;&raquo;&nbsp;' . sprintf($lang['sprintf_ajax_more'], $num_m) . '</li>';
						break;
					}
				}
			}
		}
		else
		{
			echo "<li onClick=\"$fill('$post');\">" . $lang['new_entry'] . "</li>";
		}
	}
}
else
{
	echo 'There should be no direct access to this script!';
}

?>