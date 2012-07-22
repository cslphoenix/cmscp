<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

define('HEADER_INC', true);

/* gzip_compression */

$do_gzip_compress = FALSE;

if ( $config['page_gzip'] )
{
	$phpver = phpversion();

	$useragent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');

	if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if ( extension_loaded('zlib') )
		{
			ob_start('ob_gzhandler');
		}
	}
	else if ( $phpver > '4.0' )
	{
		if ( strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') )
		{
			if ( extension_loaded('zlib') )
			{
				$do_gzip_compress = TRUE;
				ob_start();
				ob_implicit_flush(0);

				header('Content-Encoding: gzip');
			}
		}
	}
}

$template->set_filenames(array(
	'header'	=> $root_path . 'admin/style/page_header.tpl',
	'nav'		=> $root_path . 'admin/style/page_navigate.tpl',
	'footer'	=> $root_path . 'admin/style/page_footer.tpl'
));

$l_timezone = explode('.', $config['default_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $config['default_timezone'])] : $lang[number_format($config['default_timezone'])];

$current_page = isset($current) ? isset($lang[$current]) ? $lang[$current] : $current : 'info';

$template->assign_vars(array(
	'L_HEADER'	=> $current_page,
	'L_TIME'	=> sprintf($lang['current_time'], create_date($userdata['user_dateformat'], time(), $userdata['user_timezone'])),
	'L_ACP'		=> $lang['header_acp'],
	'L_SITE'	=> $config['page_name'],
	'L_USER'	=> $userdata['user_name'],
	'L_LOGOUT'	=> $lang['index_logout'],
	'L_SESSION'	=> $lang['index_session'],

	'L_NAVIGATION'		=> $lang['navi_navigation'],

	'L_INPUT_DATA'		=> $lang['common_input_data'],
	'L_INPUT_OPTION'	=> $lang['common_input_option'],
	'L_INPUT_UPLOAD'	=> $lang['common_input_upload'],
	'L_INPUT_STANDARD'	=> $lang['common_input_standard'],

	'L_EMPTY'		=> $lang['common_empty'],
	'L_ORDER'		=> $lang['common_order'],
	'L_MORE'		=> $lang['common_more'],
	'L_REMOVE'		=> $lang['common_remove'],
	'L_REQUIRED'	=> $lang['common_required'],
	'L_SORT'		=> $lang['common_sort'],
	'L_GO'			=> $lang['common_go'],
	'L_NO'			=> $lang['common_no'],
	'L_YES'			=> $lang['common_yes'],
	'L_RESET'		=> $lang['common_reset'],
	'L_SUBMIT'		=> $lang['common_submit'],
	'L_UPDATE'		=> $lang['common_update'],
	'L_DELETE'		=> $lang['common_delete'],
	'L_DELETE_ALL'	=> $lang['common_delete_all'],
	'L_SETTINGS'	=> $lang['common_settings'],

	'L_MARK_NO'		=> $lang['mark_no'],
	'L_MARK_YES'	=> $lang['mark_yes'],
	'L_MARK_ALL'	=> $lang['mark_all'],
	'L_MARK_DEALL'	=> $lang['mark_deall'],
	'L_MARK_INVERT'	=> $lang['mark_invert'],
	'L_SHOW'		=> $lang['show'],
	'L_NOSHOW'		=> $lang['noshow'],
	'L_UPLOAD'		=> $lang['common_upload'],
	'L_USERNAME'	=> $lang['user_name'],
	'L_GOTO_PAGE'	=> $lang['Goto_page'],

	'CONTENT_FOOTER'	=> sprintf($lang['content_footer'], $config['page_version']),

	'U_ACP'		=> check_sid('index.php?pane=right'),
	'U_SITE'	=> check_sid('../index.php'),
	'U_LOGOUT'	=> check_sid('./../login.php?logout=true'),
	'U_SESSION'	=> check_sid('./../login.php?logout=true&admin_session_logout=true'),

	'S_CONTENT_ENCODING'	=> $lang['content_encoding'],
	'S_CONTENT_DIRECTION'	=> $lang['content_direction'],
));

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
if (!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
{
	header('Cache-Control: no-cache, pre-check=0, post-check=0');
}
else
{
	header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header('Expires: 0');
header('Pragma: no-cache');

$setmodules = 1;

$dir = scandir('./');

foreach ( $dir as $files )
{
	if ( preg_match("/^admin_.*?\.php$/", $files) )
	{
		include("./$files");
	}
}

unset($setmodules);

$tmp = data(MENU2, false, 'menu_order ASC', 1, false);

$db_cat = $db_sub = $cat_info = $sub_info = array();

if ( $tmp )
{
	foreach ( $tmp as $row )
	{
		if ( !$row['menu_sub'] )
		{
			$sql_cat[$row['menu_name']] = $row['menu_name'];
			$cat_ino[$row['menu_name']] = $row['menu_id'];
		}
		else
		{
			$sql_sub[$row['menu_sub']][$row['menu_name']] = $row['menu_name'];
			$sub_ino[$row['menu_sub']][$row['menu_name']] = $row['menu_hash'];
		}
	}
}
else
{
	$sql_cat = $sql_sub = $cat_ino = array();
}

foreach ( $module as $key => $row )
{
	$mod_cat[$key] = $key;
	
	foreach ( $row as $rkey => $rrow )
	{
		$mod_sub[$key][$rkey] = $rrow;
		$mod_ino[$key][$rkey] = substr(md5($rrow), 0, 10);
	}
}

if ( $userdata['user_level'] == ADMIN )
{
	if ( $new_ary = array_diff($mod_cat, $sql_cat) )
	{
		foreach ( $new_ary as $rows )
		{
			$ary_new[$rows] = $module[$rows];
		}
		
		if ( $ary_new )
		{
			$tmp_cat = array_merge($cat_ino, $ary_new);
		}
	}
	else
	{
		$tmp_cat = $mod_cat;
	}
}
else
{
	$tmp_cat = $mod_cat;
}

foreach ( $tmp_cat as $catkey => $catrow )
{
	$cat_name = ( isset($lang[$catkey]) ) ? $lang[$catkey] : preg_replace("/_/", " ", $catkey);
	$tmp_user = unserialize($userdata['user_acp_menu']);
	
	$template->assign_block_vars('row', array(
		'L_NAME' => $cat_name,
		
		'NAME' => $catkey,
		'SHOW' => isset($tmp_user[$catkey]) ? $tmp_user[$catkey] ? '' : 'none' : '',
	));
	
	$ssql	= !is_array($catrow) ? isset($sql_sub[$catrow]) ? $sql_sub[$catrow] : '' : $catrow;
	$smod	= isset($mod_sub[$catkey]) ? $mod_sub[$catkey] : '';
	
	if ( !is_array($catrow) && isset($sql_sub[$catrow]) )
	{
		foreach ( $sql_sub[$catrow] as $value )
		{
			$new_sub_ary[$catrow][$value] = $mod_sub[$catkey][$value];
		}
		
		if ( $sub_ary[$catrow] = array_diff($mod_sub[$catkey], $new_sub_ary[$catrow]) )
		{
			if ( $sub_ary[$catrow] )
			{
				$tmp_sub = array_merge($new_sub_ary[$catrow], $sub_ary[$catrow]);
			}
		}
		else
		{
			$tmp_sub = $mod_sub[$catkey];
		}
	}
	else
	{
		$tmp_sub = $mod_sub[$catkey];
	}
	
	
#	debug($mod_ino);
	
	foreach ( $tmp_sub as $subkey => $subrow )
	{
#		debug($sub_ino[$subkey]);
#		debug($mod_ino[$subkey]);
	
		$mod = sprintf($lang['sprintf_select_menu'], ( !empty($lang[$subkey]) ) ? $lang[$subkey] : preg_replace("/_/", " ", $subkey));
		
		 $active = '';
        $active_module_mode = '';
        $active_module = basename($_SERVER['PHP_SELF']);
		
	#	debug($active_module, false, '1');
	#	debug(basename(__FILE__), false, '2');
		
        $active_module = '';
        $size_get = sizeof($_GET);

	   if ( $size_get == 1 )
		{
			$active_module = basename($_SERVER['PHP_SELF']);

		}
		elseif ( $size_get == 2 )
		{
            if (isset($_GET['mode']) )
            {
				$active_module = basename($_SERVER['PHP_SELF']) . "?mode=" . $_GET['mode'];
				
				if ($subrow == $active_module && stristr($subrow, 'mode') == TRUE)
				{
					$active_module = $active_module;
				}
				elseif ($subrow == $active_module && stristr($subrow, 'mode') == FALSE)
				{
                 	$active_module = basename($_SERVER['PHP_SELF']);
    			}
    			elseif ($subrow == basename($_SERVER['PHP_SELF']) && stristr($subrow, 'mode') == FALSE)
				{
                 	 $active_module = basename($_SERVER['PHP_SELF']);
    			}
		     }
		}
		elseif ( $size_get >= 3 )
		{
			$active_module = basename($_SERVER['PHP_SELF']);
		}

		if ($active_module == $subrow)
		{
         $active = ' class="active"';
		}
		
      
		$template->assign_block_vars('row.sub', array(
			'CLASS'  => $active,
			
			'L_MODULE'	=> $mod,
			'U_MODULE'	=> check_sid($subrow),
			
		));
	}

}

/*
$sql = "SELECT cat_name FROM " . MENU_CAT . " ORDER BY cat_order ASC";
if ( !($result = $db->sql_query($sql)) )
{
	message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) )
{
	foreach ( $module as $key => $value )
	{
		if ( $key == $row['cat_name'] )
		{
			$_module[$key] = $value;
			unset($module[$key]);
		}
	}
}

if ( is_array($module) )
{
	foreach ( $module as $key => $value )
	{
		$_module[$key] = $value;
	}
}
else
{
	$_module = $module;
}

$files = '';

while ( list($cat, $action_array) = each($_module) )
{
	$sql = "SELECT f.file_name FROM " . MENU . " f LEFT JOIN " . MENU_CAT . " c ON f.cat_id = c.cat_id WHERE c.cat_name = '$cat' ORDER BY  file_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$files = $db->sql_fetchrowset($result);
	
	if ( $files )
	{
		$_v = '';
		
		foreach ( $files as $fkey => $fvalue )
		{
			foreach ( $fvalue as $vkey => $vvalue )
			{
				$_v[] = $vvalue;
			}
		}
	}
	
	$_action_array = '';
		
	if ( isset($_v) )
	{
		foreach ( $_v as $_vk => $_vv )
		{
			foreach ( $action_array as $key => $value )
			{
				if ( $key == $_vv )
				{
					$_action_array[$key] = $value;
					unset($action_array[$key]);
				}
			}
		}
		
		if ( is_array($action_array) )
		{
			foreach ( $action_array as $key => $value )
			{
				$_action_array[$key] = $value;
			}
		}
		else
		{
			$_action_array = $action_array;
		}
	}
	else
	{	
		if ( is_array($action_array) )
		{
			foreach ( $action_array as $key => $value )
			{
				$_action_array[$key] = $value;
			}
		}
		else
		{
			$_action_array = $action_array;
		}
	}
	
	$cat_name = ( !empty($lang[$cat]) ) ? $lang[$cat] : preg_replace("/_/", " ", $cat);
	
	$tmp_user = isset($userdata['user_acp_menu']) ? unserialize($userdata['user_acp_menu']) : '';

	$template->assign_block_vars('cat_row', array(
		'L_NAME' => $cat_name,
		
		'NAME' => $cat,
		'SHOW' => isset($tmp_user[$cat]) ? $tmp_user[$cat] ? '' : 'none' : '',
	));
	
	while ( list($action, $file) = each($_action_array) )
	{
		$mod = sprintf($lang['sprintf_select_menu'], ( !empty($lang[$action]) ) ? $lang[$action] : preg_replace("/_/", " ", $action));
        
        $active = '';
        $active_module_mode = '';
        $active_module = basename($_SERVER['PHP_SELF']);
		
	#	debug($active_module, false, '1');
	#	debug(basename(__FILE__), false, '2');
		
        $active_module = '';
        $size_get = sizeof($_GET);

	   if ( $size_get == 1 )
		{
			$active_module = basename($_SERVER['PHP_SELF']);

		}
		elseif ( $size_get == 2 )
		{
            if (isset($_GET['mode']) )
            {
				$active_module = basename($_SERVER['PHP_SELF']) . "?mode=" . $_GET['mode'];
				
				if ($file == $active_module && stristr($file, 'mode') == TRUE)
				{
					$active_module = $active_module;
				}
				elseif ($file == $active_module && stristr($file, 'mode') == FALSE)
				{
                 	$active_module = basename($_SERVER['PHP_SELF']);
    			}
    			elseif ($file == basename($_SERVER['PHP_SELF']) && stristr($file, 'mode') == FALSE)
				{
                 	 $active_module = basename($_SERVER['PHP_SELF']);
    			}
		     }
		}
		elseif ( $size_get >= 3 )
		{
			$active_module = basename($_SERVER['PHP_SELF']);
		}

		if ($active_module == $file)
		{
         $active = ' class="active"';
		}
		
		$template->assign_block_vars('catrow.mod_row', array(
			'CLASS'  => $active,
			
			'L_MODULE'	=> $mod,
			'U_MODULE'	=> check_sid($file),
			
		));
	}
}
*/
$oCache->sCachePath = './../cache/';

$template->pparse('header');
$template->pparse('nav');

?>