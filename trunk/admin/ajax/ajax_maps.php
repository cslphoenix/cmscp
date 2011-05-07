<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['mode']) )
{
	$mode = (string) $_POST['mode'];
	
	$sql = "SELECT cat_tag FROM " . MAPS_CAT . " WHERE cat_id = $mode";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cat = $db->sql_fetchrow($result);
	
	$path_files = scandir($root_path . $settings['path_maps'] . '/' . $cat['cat_tag']);
	
	$s_select = '';
	$s_select .= '<tr><td class="row1"><label for="map_file">' . sprintf($lang['sprintf_image'], $lang['maps']) . ': *</label></td><td class="row2">';		
		
	$endung = array('png', 'jpg', 'jpeg', 'gif');
	
	if ( $path_files )
	{
		$clear_files = '';
		
		foreach ( $path_files as $files )
		{
			if ( $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' && $files != 'spacer.gif' )
			{
				if ( in_array(substr($files, -3), $endung) )
				{
					$clear_files[] = $files;
				}
			}
		}
		
		if ( $clear_files )
		{
			$s_select .= '<select class="select" name="map_file" id="map_file" onchange="update_ajax_' . $cat['cat_tag'] . '(this.options[selectedIndex].value);">';
			$s_select .= '<option value="./../admin/style/images/spacer.gif">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_map_file']) . '</option>';
			
			foreach ( $clear_files as $files )
			{
				$filter = str_replace(substr($files, strrpos($files, '.')), "", $files);
		
		#		$marked = ( $files == $default ) ? ' selected="selected"' : '';
				$s_select .= '<option value="' . $files . '" >' . sprintf($lang['sprintf_select_format'], $filter) . '</option>';
			}
			
			$s_select .= '</select><br /><img src="" id="image2" alt="" /></td></tr>';	
		}
		else
		{
			$s_select .= $lang['no_entry'];
		}
	}
	else
	{
		$s_select .= $lang['no_entry'];
	}
		
	$sql = "SELECT cat_id AS cat_type, cat_name FROM " . MAPS_CAT . " WHERE cat_id = $mode ORDER BY cat_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cats = $db->sql_fetchrowset($result);
	
	$sql = "SELECT map_name, map_order, cat_id AS map_type FROM " . MAPS . " WHERE cat_id = $mode ORDER BY cat_id, map_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$entries = $db->sql_fetchrowset($result);
	
	$s_select .= "<tr><td class=\"row1\"><label for=\"map_order\">" . $lang['common_order'] . ":</label></td><td class=\"row2\">";
	
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
				$name = $entries[$j]['map_name'];
				$type = $entries[$j]['map_type'];
				$order = $entries[$j]['map_order'];
				
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
		$s_select .= $lang['no_entry'];
	}
	$s_select .= "</td></tr>";
	
	echo $s_select;
	
	$db->sql_close();
}
else
{
	echo '';
}

exit;

?>