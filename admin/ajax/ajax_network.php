<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['name']) )
{
	$type = ( $_POST['name'] ) ? " WHERE network_type = " . $_POST['name'] : false;

	$cats = array(
				'0' => array('cat_type' => NETWORK_LINK, 'cat_name' => $lang['link']),
				'1' => array('cat_type' => NETWORK_PARTNER, 'cat_name' => $lang['partner']),
				'2' => array('cat_type' => NETWORK_SPONSOR, 'cat_name' => $lang['sponsor']),
			);
	
	$sql = "SELECT * FROM " . NETWORK . "$type ORDER BY network_type, network_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
		exit;
	}
	$entries = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	if ( $entries )	
	{
		$s_select .= "<select class=\"select\" name=\"network_order_new\" id=\"network_order\">";
		$s_select .= "<option selected=\"selected\">" . sprintf($lang['stf_select_format'], $lang['msg_select_order']) . "</option>";
	
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$entry = '';
			$cat_name = $cats[$i]['cat_name'];
			$cat_type = $cats[$i]['cat_type'];
			
			for ( $j = 0; $j < count($entries); $j++ )
			{
				$network_name = $entries[$j]['network_name'];
				$network_type = $entries[$j]['network_type'];
				$network_order = $entries[$j]['network_order'];
				
				if ( $cat_type == $network_type )
				{
					$entry .= ( $network_order == 10 ) ? "<option value=\"5\">" . sprintf($lang['sprintf_select_before'], $network_name) . "</option>" : '';
					$entry .= "<option value=\"" . ( $network_order + 5 ) . "\">" . sprintf($lang['sprintf_select_order'], $network_name) . "</option>";
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
	
	echo $s_select;
}
else
{
	echo sprintf($lang['stf_select_format'], $lang['notice_empty_maps']);
}

?>