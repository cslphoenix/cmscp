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

#debug($_POST);

if ( isset($_POST['name']) || isset($_POST['mode']) )
{
	$key = ( isset($_POST['name']) ) ? $_POST['name'] : $_POST['mode'];
	
	if ( $key )
	{
		$sql = "SELECT m.*
					FROM " . MAPS . " m
						LEFT JOIN " . TEAMS . " t ON t.team_id = $key
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				WHERE m.map_tag = g.game_tag";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$cat = $db->sql_fetchrow($result);
		
	#	debug($sql);
	#	debug($cat);
		
		if ( $cat )
		{
			$sql = "SELECT * FROM " . MAPS . " WHERE map_sub = {$cat['map_id']} ORDER BY map_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$maps = $db->sql_fetchrowset($result);
			
		#	debug($maps);
		
			$s_select = '';
			
			if ( $maps )
			{
				$s_select .= '<div><div><select name="training[training_maps][]" id="training_training_maps">';
				$s_select .= '<option selected="selected" value="-1">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . '</option>';
				
				for ( $j = 0; $j < count($maps); $j++ )
				{
					$map_id		= $maps[$j]['map_id'];
					$map_name	= $maps[$j]['map_name'];
		
					$s_select .= '<option value="' . $map_id . '">' . sprintf($lang['sprintf_select_format'], $map_name) . '</option>';
				}
				
				$s_select .= '</select>&nbsp;<input type="button" class="more" value="' . $lang['common_more'] . '" onclick="clone(this)"></div></div>';
			}
			else
			{
				$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
			}
		}
		else
		{
			$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
		}
		
		/*
		if ( $cats )
		{
			$cat_id		= $cats['cat_id'];
			$cat_name	= $cats['cat_name'];
			
			$sql = "SELECT * FROM " . MAPS . " WHERE cat_id = " . $cats['cat_id'] . " ORDER BY map_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$maps = $db->sql_fetchrowset($result);
			
			if ( $maps )
			{
				$s_select .= "<div><div><select class=\"selectsmall\" name=\"training_maps[]\" id=\"training_maps\">";
				$s_select .= "<option selected=\"selected\" >" . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . "</option>";
				
				$s_maps = '';
					
				for ( $j = 0; $j < count($maps); $j++ )
				{
					$map_id		= $maps[$j]['map_id'];
					$map_cat	= $maps[$j]['cat_id'];
					$map_name	= $maps[$j]['map_name'];
		
					$s_maps .= ( $cat_id == $map_cat ) ? "<option value=\"$map_id\">" . sprintf($lang['sprintf_select_format'], $map_name) . "</option>" : '';
				}
				
				$s_select .= ( $s_maps != '' ) ? "<optgroup label=\"$cat_name\">$s_maps</optgroup>" : '';
				$s_select .= "</select>&nbsp;<input type=\"button\" class=\"button2\" value=\"" . $lang['common_more'] . "\" onclick=\"clone(this)\"></div></div>";
			}
			else
			{
				$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
			}
		}
		else
		{
			$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
		}
		*/
	}
	else
	{
		$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_select_team_first']);
	}
	
	echo $s_select;
}
else
{
	echo sprintf($lang['sprintf_select_format'], $lang['msg_select_team_first']);
}

?>