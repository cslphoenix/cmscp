<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);

//	session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

//	Set default email variables
$script_name		= preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['script_path']));
$script_name		= ( $script_name != '' ) ? $script_name . '/profile.'.$phpEx : 'profile.'.$phpEx;
$server_name		= trim($config['server_name']);
$server_protocol	= ( $config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port		= ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';
$server_url			= $server_protocol . $server_name . $server_port . $script_name;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
	$mode = htmlspecialchars($mode);

	if ( $mode == 'view' )
	{
		$page_title = $lang['Viewing_profile'];
		
		if ( empty($HTTP_GET_VARS[POST_USERS_URL]) || $HTTP_GET_VARS[POST_USERS_URL] == ANONYMOUS )
		{
			message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
		}
		
		$profiledata = get_userdata($HTTP_GET_VARS[POST_USERS_URL]);
		
		if (!$profiledata)
		{
			message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
		}
		
		$template->set_filenames(array('body' => 'profile_view_body.tpl'));
	}
	else if ( $mode == 'edit' || $mode == 'register' )
	{
		
	}
	else if ( $mode == 'password' )
	{
		
	}
	else if ( $mode == 'activate' )
	{
		
	}
	else if ( $mode == 'email' )
	{
		
	}
	
	include($root_path . 'includes/page_header.php');
	
	$template->pparse('body');
	
	include($root_path . 'includes/page_tail.php');
}

redirect(append_sid("index.php", true));
