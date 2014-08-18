<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['cat']) )
{
	$cat = $_POST['cat'];
	
	$sql = 'SELECT * FROM ' . MAPS . ' WHERE map_id = ' . $cat . ' ORDER BY main ASC, map_order ASC';
	if ( !($result = $db->sql_query($sql)) )
	{
		echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
		exit;
	}
	$tmp_db = $db->sql_fetchrow($result);
	
	$files	= scandir($root_path . $settings['path_maps'] . $tmp_db['map_tag']);
	$format	= array('png', 'jpg', 'jpeg', 'gif');
	
	$tmeta	= 'map';
	$tname	= 'map_file';
	
	if ( $files )
	{
		$tmp_files = '';
		
		foreach ( $files as $row )
		{
			if ( $row != '.' && $row != '..' && $row != 'index.htm' && $row != '.svn' && $row != 'spacer.gif' )
			{
				if ( in_array(substr($row, -3), $format) )
				{
					$tmp_files[] = $row;
				}
			}
		}
		
		if ( $tmp_files )
		{
			$img_path = './../' . $settings['path_maps'] . $tmp_db['map_tag'] . '/';
										
			$current_image = '';
			
			$opt = '<div id="close"><select name="' . sprintf('%s[%s]', $tmeta, $tname) . '" id="' . sprintf('%s_%s', $tmeta, $tname) . '" onkeyup="update_ajax_' . $tmp_db['map_tag'] . '(this.options[selectedIndex].value);" onchange="update_ajax_' . $tmp_db['map_tag'] . '(this.options[selectedIndex].value);">';
			
			foreach ( $tmp_files as $row )
			{
				$current_image = $img_path . $row;
				
				$mask = str_replace(substr($row, strrpos($row, '.')), "", $row);
	
				$opt .= '<option value="' . $row . '">' . sprintf($lang['stf_select_format'], $mask) . '</option>';
			}
			
			$opt .= '</select><br /><img src="' . $current_image . '" id="image2" alt="" /></div><div id="ajax_content"></div>';
		}
		else
		{
			$opt  = '<div id="close">select cat oder keine bilder vorhanden</div><div id="ajax_content"></div>';
		}
	}
	else
	{
		$opt = '<div id="close">select cat oder keine bilder vorhanden</div><div id="ajax_content"></div>';
	}
	
	echo $opt;
}
else
{
	echo 'There should be no direct access to this script!';
}

?>