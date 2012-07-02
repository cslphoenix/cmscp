<?php

/*
 *	require: acp_navi, acp_network, acp_profile, acp_ranks
 */

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);
define('HEADER_INC', true);

$root_path = './../../';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['new_mode']) )
{
	global $db_prefix;
	
	$s_select	= '';
	$new_mode	= $_POST['new_mode'];
	$new_opt	= $_POST['new_opt'];
	$cur_mode	= $_POST['cur_mode'];
	$cur_opt	= $_POST['cur_opt'];
	
	$db_field = trim(str_replace($db_prefix, '', $new_mode), 's');
	$suborcat = ( in_array($db_field, array('field')) ) ? 'sub' : 'type';
	
	$sql = "SELECT * FROM $new_mode WHERE {$db_field}_{$suborcat} = $new_opt ORDER BY {$db_field}_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$entries = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	if ( !$entries )
	{
		$s_select = $lang['common_entry_empty'];
	}
	else
	{
		$s_select .= "<select class=\"select\" name=\"{$new_mode}_order_new\" id=\"{$new_mode}_order\">";
		$s_select .= "<option selected=\"selected\" value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
	
		for ( $j = 0; $j < count($entries); $j++ )
		{
			$name	= $entries[$j]["{$db_field}_name"];
			$order	= $entries[$j]["{$db_field}_order"];
			
				$disabled = ( $order == $cur_opt && $new_opt == $cur_mode ) ? 'disabled="disabled"' : '';
				
				$s_select .= ( $order == 10 ) ? "<option value=\"5\" $disabled>" . sprintf($lang['sprintf_select_before'], $name) . "</option>" : '';
				$s_select .= "<option value=\"" . ( $order + 5 ) . "\" $disabled>" . sprintf($lang['sprintf_select_order'], $name) . "</option>";
		}
		
		$s_select .= "</select>";
	}
	
	/*
	switch ( $new_mode )
	{
		case 'navi':
		
			$cats = array(
				'0' => array('cat_type' => NAVI_MAIN,	'cat_name' => $lang['main']),
				'1' => array('cat_type' => NAVI_CLAN,	'cat_name' => $lang['clan']),
				'2' => array('cat_type' => NAVI_COM,	'cat_name' => $lang['com']),
				'3' => array('cat_type' => NAVI_MISC,	'cat_name' => $lang['misc']),
				'4' => array('cat_type' => NAVI_USER,	'cat_name' => $lang['user']),
			);
			
			$sql = "SELECT * FROM " . NAVI . " WHERE navi_type = $new_opt ORDER BY navi_type, navi_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case 'network':
			
			$cats = array(
				'0' => array('cat_type' => NETWORK_LINK,	'cat_name' => $lang['link']),
				'1' => array('cat_type' => NETWORK_PARTNER,	'cat_name' => $lang['partner']),
				'2' => array('cat_type' => NETWORK_SPONSOR,	'cat_name' => $lang['sponsor']),
			);
			
			$sql = "SELECT * FROM " . NETWORK . " WHERE network_type = $new_opt ORDER BY network_type, network_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
		
		case 'rank':
		
			$cats = array(
				'0' => array('cat_type' => RANK_PAGE,	'cat_name' => $lang['page']),
				'1' => array('cat_type' => RANK_FORUM,	'cat_name' => $lang['forum']),
				'2' => array('cat_type' => RANK_TEAM,	'cat_name' => $lang['team']),
			);
			
			$sql = "SELECT * FROM " . RANKS . " WHERE rank_type = $new_opt ORDER BY rank_type, rank_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case 'profile':
		
			$sql = "SELECT cat_id AS cat_type, cat_name FROM " . PROFILE_CAT . " WHERE cat_id = $new_opt ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cats = $db->sql_fetchrowset($result);
			
			$sql = "SELECT profile_name, profile_order, profile_cat AS profile_type FROM " . PROFILE . " WHERE profile_cat = $new_opt ORDER BY profile_cat, profile_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case 'field':

			$sql = "SELECT field_id AS cat_type, field_name AS cat_name FROM " . FIELDS . " WHERE field_id = $new_opt ORDER BY field_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cats = $db->sql_fetchrowset($result);
			
			$sql = "SELECT field_name, field_order, field_sub AS field_type FROM " . FIELDS . " WHERE field_sub = $new_opt ORDER BY field_sub, field_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
	}	
	$entries = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	if ( !$entries )
	{
		$s_select = $lang['common_entry_empty'];
	}
	else
	{
		$s_select .= "<select class=\"select\" name=\"$new_mode order_new\" id=\"$new_mode order\">";
		$s_select .= "<option selected=\"selected\" value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
	
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$entry = '';
			
			$cat_name = $cats[$i]['cat_name'];
			$cat_type = $cats[$i]['cat_type'];
			
			for ( $j = 0; $j < count($entries); $j++ )
			{
				$name = $entries[$j][$new_mode . '_name'];
				$type = $entries[$j][$new_mode . '_type'];
				$order = $entries[$j][$new_mode . '_order'];
				
				if ( $cat_type == $type )
				{
					$disabled = ( $order == $cur_opt && $cat_type == $cur_mode ) ? ' disabled="disabled"' : '';
					
					$entry .= ( $order == 10 ) ? "<option value=\"5\"$disabled>" . sprintf($lang['sprintf_select_before'], $name) . "</option>" : '';
					$entry .= "<option value=\"" . ( $order + 5 ) . "\"$disabled>" . sprintf($lang['sprintf_select_order'], $name) . "</option>";
				}
			}
			
			$s_select .= ( $entry != '' ) ? "<optgroup label=\"$cat_name\">$entry</optgroup>" : '';
		}
		
		$s_select .= "</select>";
	}
	*/
	
	echo $s_select;
}
else
{
	echo $lang['common_entry_empty'];
}

?>