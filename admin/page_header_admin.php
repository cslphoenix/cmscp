<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

define('HEADER_INC', true);

//
// gzip_compression
//
$do_gzip_compress = FALSE;
if ( $config['gzip_compress'] )
{
	$phpver = phpversion();

	$useragent = (isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) ? $HTTP_SERVER_VARS['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');

	if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if ( extension_loaded('zlib') )
		{
			ob_start('ob_gzhandler');
		}
	}
	else if ( $phpver > '4.0' )
	{
		if ( strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') )
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


$template->set_filenames(array('header' => './../admin/style/page_header.tpl'));

// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$l_timezone = explode('.', $config['page_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $config['page_timezone'])] : $lang[number_format($config['page_timezone'])];

//
// The following assigns all _common_ variables that may be used at any point
// in a template. Note that all URL's should be wrapped in append_sid, as
// should all S_x_ACTIONS for forms.
//

$current_page = ( isset($current) ) ? $lang[$current] : 'info';

$template->assign_vars(array(
							 
	'L_HEADER'			=> sprintf($lang['index_header'], $config['page_name'], $current_page),
							 
	'L_REQUIRED'		=> $lang['required'],
	
	'L_NO'				=> $lang['common_no'],
	'L_YES'				=> $lang['common_yes'],
	'L_RESET'			=> $lang['common_reset'],
	'L_SUBMIT'			=> $lang['common_submit'],
	
	'L_UPDATE'			=> $lang['common_update'],
	'L_DELETE'			=> $lang['common_delete'],
	'L_SETTINGS'		=> $lang['common_settings'],
	
	'L_MARK_ALL'		=> $lang['mark_all'],
	'L_MARK_DEALL'		=> $lang['mark_deall'],
	
	'L_MARK_YES'		=> $lang['mark_yes'],
	'L_MARK_NO'			=> $lang['mark_no'],
	
	'L_SHOW'			=> $lang['show'],
	'L_NOSHOW'			=> $lang['noshow'],
	'L_MORE'			=> $lang['common_more'],
	'L_REMOVE'			=> $lang['common_remove'],
	'L_UPLOAD'			=> $lang['common_upload'],
	
	
	'I_UPDATE'			=> '<img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" >',
	'I_DELETE'			=> '<img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" >',
	'I_MEMBER'			=> '<img src="' . $images['icon_option_member'] . '" title="' . $lang['common_members'] . '" alt="" >',
	'I_DETAILS'			=> '<img src="' . $images['icon_option_details'] . '" title="' . $lang['common_details'] . '" alt="" >',
	
	'L_GOTO_PAGE'	=> $lang['Goto_page'],
	
	
	'SITENAME' => $config['page_name'],
	
	

	
	'L_SESSION'		=> $lang['session'],
	'L_LOGOUT'		=> $lang['logout'],
	
	'L_HEAD_USER'	=> ' [ ' . $userdata['username'] . ' ] ',
	
	

	'U_INDEX' => append_sid('../index.php'),
	"U_LOGOUT" => append_sid('./../login.php?logout=true'),
	"U_ADMIN_LOGOUT" => append_sid('./../login.php?logout=true&admin_session_logout=true'),
	
	"U_PAGE_INDEX" => append_sid('../index.php'),
		"U_ADMIN_INDEX" => append_sid('index.php?pane=right'),

		"L_ADMIN_INDEX" => $lang['index'], 
		"L_PAGE_INDEX" => $lang['page'],
		
	
	

	'S_TIMEZONE' => sprintf($lang['All_times'], $l_timezone),
	'S_LOGIN_ACTION' => append_sid('../login.php'),
	'S_JUMPBOX_ACTION' => append_sid('../viewforum.php'),
	'S_CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['page_timezone'])), 
	'S_CONTENT_DIRECTION' => $lang['DIRECTION'], 
	'S_CONTENT_ENCODING' => $lang['ENCODING'], 
	'S_CONTENT_DIR_LEFT' => $lang['LEFT'], 
	'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'], 

	'T_SPAN_CLASS3' => $theme['span_class3'])
);

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: 0');
header ('Pragma: no-cache');

$template->pparse('header');

//
// Generate navigation bar (moved from index.php)
//
$dir = @opendir(".");

$setmodules = 1;
while( $file = @readdir($dir) )
{
	if( preg_match("/^admin_.*?\.php$/", $file) )
	{
		include('./' . $file);
	}
}

@closedir($dir);

unset($setmodules);

$template->set_filenames(array(
	"nav" => "./../admin/style/index_navigate.tpl")
);

$template->assign_vars(array(
	"U_FORUM_INDEX" => append_sid('../index.php'),
	"U_ADMIN_INDEX" => append_sid('index.php'),

	"L_FORUM_INDEX" => $lang['Main_index'],
	"L_ADMIN_INDEX" => $lang['Admin_Index'], 
	
	"L_PREVIEW_FORUM" => $lang['Preview_forum'])
);

ksort($module);

while( list($cat, $action_array) = each($module) )
{
	$cat = ( !empty($lang[$cat]) ) ? $lang[$cat] : preg_replace("/_/", " ", $cat);

	$template->assign_block_vars("catrownav", array(
		"ADMIN_CATEGORY" => $cat)
	);

	ksort($action_array);

	$row_count = 0;
	while( list($action, $file)	= each($action_array) )
	{
		$row_color = ( !($row_count%2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($row_count%2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$action = ( !empty($lang[$action]) ) ? $lang[$action] : preg_replace("/_/", " ", $action);

		$template->assign_block_vars("catrownav.modulerow", array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class, 

			"ADMIN_MODULE" => $action,
			"U_ADMIN_MODULE" => append_sid($file))
		);
		$row_count++;
	}
}

$template->pparse("nav");

?>
