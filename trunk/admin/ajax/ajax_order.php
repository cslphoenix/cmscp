<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

debug($_POST);

if ( isset($_POST['mode']) || isset($_POST['option']) )
{
	$mode	= (string) $_POST['mode'];
	$option	= (string) $_POST['option'];
	
	switch ( $mode )
	{
		case 'map':
			
			$sql = "SELECT cat_id AS cat_type, cat_name FROM " . MAPS_CAT . " WHERE cat_id = $option ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cats = $db->sql_fetchrowset($result);
			
			$sql = "SELECT map_name, map_order, cat_id AS map_type FROM " . MAPS . " WHERE cat_id = $option ORDER BY cat_id, map_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		
			break;
			
		case 'network':
			
			$cats = array(
				'0' => array('cat_type' => NETWORK_LINK, 'cat_name' => $lang['link']),
				'1' => array('cat_type' => NETWORK_PARTNER, 'cat_name' => $lang['partner']),
				'2' => array('cat_type' => NETWORK_SPONSOR, 'cat_name' => $lang['sponsor']),
			);
			
			$sql = "SELECT * FROM " . NETWORK . " WHERE network_type = $option ORDER BY network_type, network_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case 'navi':
		
			$cats = array(
				'0' => array('cat_type' => NAVI_MAIN, 'cat_name' => $lang['main']),
				'1' => array('cat_type' => NAVI_CLAN, 'cat_name' => $lang['clan']),
				'2' => array('cat_type' => NAVI_COM, 'cat_name' => $lang['com']),
				'3' => array('cat_type' => NAVI_MISC, 'cat_name' => $lang['misc']),
				'4' => array('cat_type' => NAVI_USER, 'cat_name' => $lang['user']),
			);
			
			$sql = "SELECT * FROM " . NAVI . " WHERE navi_type = $option ORDER BY navi_type, navi_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case 'rank':
		
			$cats = array(
				'0' => array('cat_type' => RANK_PAGE, 'cat_name' => $lang['page']),
				'1' => array('cat_type' => RANK_FORUM, 'cat_name' => $lang['forum']),
				'2' => array('cat_type' => RANK_TEAM, 'cat_name' => $lang['team']),
			);
			
			$sql = "SELECT * FROM " . RANKS . " WHERE rank_type = $option ORDER BY rank_type, rank_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case 'profile':
		
			$sql = "SELECT cat_id AS cat_type, cat_name FROM " . PROFILE_CAT . " WHERE cat_id = $option ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cats = $db->sql_fetchrowset($result);
			
			$sql = "SELECT profile_name, profile_order, profile_cat AS profile_type FROM " . PROFILE . " WHERE profile_cat = $option ORDER BY profile_cat, profile_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
	}	
	$entries = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	$s_select .= ( $mode == 'map' ) ? "<tr><td class=\"row1\"><label for=\"map_order\">" . $lang['common_order'] . ":</label></td><td class=\"row2\">" : '';
	
	if ( $entries )	
	{
		$s_select .= "<select class=\"select\" name=\"$mode order_new\" id=\"$mode order\">";
		$s_select .= "<option selected=\"selected\" value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
	
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$entry = '';
			
			$cat_name = $cats[$i]['cat_name'];
			$cat_type = $cats[$i]['cat_type'];
			
			for ( $j = 0; $j < count($entries); $j++ )
			{
				$name = $entries[$j][$mode . '_name'];
				$type = $entries[$j][$mode . '_type'];
				$order = $entries[$j][$mode . '_order'];
				
				if ( $cat_type == $type )
				{
					$entry .= ( $order == 10 ) ? "<option value=\"5\">" . sprintf($lang['sprintf_select_before'], $name) . "</option>" : '';
					$entry .= "<option value=\"" . ( $order + 5 ) . "\">" . sprintf($lang['sprintf_select_order'], $name) . "</option>";
				}
			}
			
			$s_select .= ( $entry != '' ) ? "<optgroup label=\"$cat_name\">$entry</optgroup>" : '';
		}
		
		$s_select .= "</select>";
	}
	else
	{
		$s_select = $lang['no_entry'];
	}
	
	$s_select .= ( $mode == 'map' ) ? "</td></tr>" : '';
	
	echo $s_select;
}
else
{
	echo '';
}

?>