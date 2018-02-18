<?php

/*
 *	require: acp_match, acp_train
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
	$type = $_POST['type'];
	$meta = $_POST['meta'];
	$name = $_POST['name'];
	
	if ( $type )
	{
		$sql = "SELECT m.*
					FROM " . MAPS . " m
						LEFT JOIN " . TEAMS . " t ON t.team_id = $type
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE m.map_tag = g.game_tag";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo 'SQL Error in Line: ' . __LINE__;
			exit;
		}
		
		$cat = $db->sql_fetchrow($result);
		
		if ( $cat )
		{
			$sql = "SELECT * FROM " . MAPS . " WHERE main = {$cat['map_id']} ORDER BY map_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				echo 'SQL Error in Line: ' . __LINE__;
				exit;
			}
			$maps = $db->sql_fetchrowset($result);
			
			$s_select = '';
			
			if ( $maps )
			{
				$s_select .= '<div><div><select name="' . sprintf('%s[%s][]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
				$s_select .= '<option selected="selected" value="-1">' . sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_SELECT_MAP']) . '</option>';
				
				for ( $j = 0; $j < count($maps); $j++ )
				{
					$map_id		= $maps[$j]['map_id'];
					$map_name	= $maps[$j]['map_name'];
		
					$s_select .= '<option value="' . $map_id . '">' . sprintf($lang['STF_SELECT_FORMAT'], $map_name) . '</option>';
				}
				
				$s_select .= '</select>&nbsp;<input type="button" class="more" value="' . $lang['common_more'] . '" onclick="clone(this)"></div></div>';
			}
			else
			{
				$s_select = sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_MAPS_NONE']);
			}
		}
		else
		{
			$s_select = sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_MAPS_NONE']);
		}
	}
	else
	{
		$s_select = sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_SELECT_TEAM_FIRST']);
	}
	
	echo $s_select;
}
else
{
	echo sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_SELECT_TEAM_FIRST']);
}

?>