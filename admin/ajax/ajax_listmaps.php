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
	$type = ( $_POST['name'] ) ? " WHERE cat_id = " . intval($_POST['name']) : false;
	
	$sql = "SELECT * FROM " . MAPS_CAT . "$type ORDER BY cat_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cats = $db->sql_fetchrowset($result);
	
	$sql = "SELECT * FROM " . MAPS . "$type ORDER BY map_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$maps = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	if ( $maps )
	{
		$s_select .= "<div><div><select class=\"selectsmall\" name=\"training_maps[]\" id=\"training_maps\">";
		$s_select .= "<option selected=\"selected\" value=\"-1\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . "</option>";
		
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id		= $cats[$i]['cat_id'];
			$cat_name	= $cats[$i]['cat_name'];
			
			$s_maps = '';
			
			for ( $j = 0; $j < count($maps); $j++ )
			{
				$map_id		= $maps[$j]['map_id'];
				$map_cat	= $maps[$j]['cat_id'];
				$map_name	= $maps[$j]['map_name'];

				if ( $cat_id == $map_cat )
				{
					$s_maps .= "<option value=\"$map_id\">" . sprintf($lang['sprintf_select_format'], $map_name) . "</option>";
				}
			}
			
			$s_select .= ( $s_maps != '' ) ? "<optgroup label=\"$cat_name\">$s_maps</optgroup>" : '';
		}
	
		$s_select .= "</select>&nbsp;<input type=\"button\" class=\"button2\" value=\"" . $lang['common_more'] . "\" onclick=\"clone(this)\"></div></div>";
	}
	else
	{
		$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
	}
	
	echo $s_select;
}
else
{
	echo sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
}

?>