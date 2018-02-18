<?php

/*
 *	require: acp_server.tpl
 */

header('content-type: text/html; charset=UTF-8');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['type']) )
{
	$type = (int) $_POST['type'];
	$game = (string) $_POST['game'];
	
	$s_select = '';
	
	if ( isset($type) )
	{
		$sql = "SELECT * FROM " . GAMEQ . " WHERE gameq_type = $type";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
			exit;
		}
		$tmp = $db->sql_fetchrowset($result);
		
		if ( $tmp )
		{
			$s_select .= '<select name="server_game">';
			$s_select .= '<option value="">' . sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_SELECT_GAMETYPE']) . '</option>';
		
			foreach ( $tmp as $row )
			{
				$selected = ( $game == $row['gameq_game'] ) ? ' selected="selected"' : '';
				$s_select .= '<option title="' . sprintf('%s :: %s', $row['gameq_game'], $row['gameq_dport']) . '" value="' . $row['gameq_game'] . '"' . $selected . '>' . sprintf($lang['STF_SELECT_FORMAT2'], $row['gameq_name'], $row['gameq_game']) . '</option>';
			}
			$s_select .= '</select>';
			
			
			
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