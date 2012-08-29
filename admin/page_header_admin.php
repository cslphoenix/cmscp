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
#	'nav'		=> $root_path . 'admin/style/page_navigate.tpl',
	'footer'	=> $root_path . 'admin/style/page_footer.tpl'
));

$l_timezone = explode('.', $config['default_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $config['default_timezone'])] : $lang[number_format($config['default_timezone'])];

$current_page = isset($current) ? isset($lang[$current]) ? $lang[$current] : $current : 'info';

#debug($_POST, 'hpost');
#debug($_GET, 'hget');
#debug(request('i', INT), 'i');

$typ = request('i', INT) ? request('i', INT) : 1;
$act = request('action', TXT);

$action = $act ? "&action=$act" : '';

$adds = "?i=$typ$action";
$file = basename($_SERVER['PHP_SELF']) . $adds;

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

	'U_ACP'		=> check_sid('admin_index.php?i=1'),
	'U_SITE'	=> check_sid('../index.php'),
	'U_LOGOUT'	=> check_sid('./../login.php?logout=true'),
	'U_SESSION'	=> check_sid('./../login.php?logout=true&admin_session_logout=true'),

	'S_CONTENT_ENCODING'	=> $lang['content_encoding'],
	'S_CONTENT_DIRECTION'	=> $lang['content_direction'],
	
	'S_ACTION' => check_sid($file),
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

$tmp = data(MENU2, "action = 'acp'", 'menu_order ASC', 1, false);

foreach ( $tmp as $row )
{
	if ( !$row['type'] )
	{
		$sql_cat[$row['menu_id']] = $row['menu_name'];
	}
	else if ( $row['type'] == 1 )
	{
		$sql_lab[$row['main']][$row['menu_id']] = $row;
	}
	else
	{
		$sql_sub[$row['main']][] = $row;
	}
}

foreach ( $sql_cat as $catkey => $catrow )
{
	if ( isset($sql_lab[$catkey]) )
	{
		$fmenu = current($sql_lab[$catkey]);
		$menu_file = isset($sql_sub[$fmenu['menu_id']][0]['menu_file']) ? $sql_sub[$fmenu['menu_id']][0]['menu_file'] : '';
		$menu_opts = isset($sql_sub[$fmenu['menu_id']][0]['menu_opts']) ? $sql_sub[$fmenu['menu_id']][0]['menu_opts'] != 'main' ? '&amp;action=' . $sql_sub[$fmenu['menu_id']][0]['menu_opts'] : '' : '';
	}
	
	$template->assign_block_vars('icat', array(
		'NAME'		=> isset($lang[$catrow]) ? $lang[$catrow] : str_replace("_", " ", $catrow),
		'ACTIVE'	=> ( $typ == $catkey ) ? ' id="active"' : '',
		'CURRENT'	=> ( $typ == $catkey ) ? ' id="current"' : '',
		'URL'		=> check_sid($menu_file . '?i=' . $catkey . $menu_opts),
	));
	
	if ( $catkey == $typ )
	{
		@reset($sql_lab);
		
		if ( isset($sql_lab[$catkey]) )
		{
			foreach ( $sql_lab[$catkey] as $labkey => $labrow )
			{
				$template->assign_block_vars('ilab', array('NAME' => sprintf($lang['sprintf_select_menu'], (isset($lang[$labrow['menu_name']]) ? $lang[$labrow['menu_name']] : str_replace("_", " ", $labrow['menu_name'])))));
				
				if ( isset($sql_sub[$labkey]) )
				{
					foreach ( $sql_sub[$labkey] as $subrow )
					{
						$active = '';

						$menu_file = $subrow['menu_file'];
						$menu_opts = ( $subrow['menu_opts'] != 'main' ) ? '&amp;action=' . $subrow['menu_opts'] : '';
						
						$template->assign_block_vars('ilab.isub', array(
							'L_MODULE'	=> sprintf($lang['sprintf_select_menu'], (isset($lang[$subrow['menu_name']]) ? $lang[$subrow['menu_name']] : str_replace("_", " ", $subrow['menu_name']))),
							'U_MODULE'	=> check_sid($menu_file . '?i=' . $catkey . $menu_opts),
							
							'CLASS'		=> $active,
						));
					}
				}
			}
		}
		else if ( isset($sql_sub[$catkey]) )
		{
			foreach ( $sql_sub[$catkey] as $subrow )
			{
				$menu_file = $subrow['menu_file'];
				$menu_opts = $subrow['menu_opts'];
				
				$template->assign_block_vars('isub', array(
					'L_MODULE'	=> sprintf($lang['sprintf_select_menu'], (isset($lang[$subrow['menu_name']]) ? $lang[$subrow['menu_name']] : str_replace("_", " ", $subrow['menu_name']))),
					'U_MODULE'	=> check_sid($menu_file . '?i=' . $catkey . '&amp;' . $menu_opts),
				));
			}
		}
	}
}

$oCache->sCachePath = './../cache/';

$template->pparse('header');

?>