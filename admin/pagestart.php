<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

define('IN_ADMIN', true);

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_ADMIN);
init_userprefs($userdata);

#$oCache -> sCachePath = './../cache/';

$userauth = auth_acp_check($userdata['user_id']);

if ( !$userdata['session_logged_in'] )
{
	$url = str_replace("?", "&", basename($_SERVER['REQUEST_URI']));
	redirect(append_sid("login.php?redirect=admin/$url", true));
}

if ( $_GET['sid'] != $userdata['session_id'] )
{
	redirect('index.php?sid=' . $userdata['session_id']);
}

if ( !$userdata['session_admin'] )
{
	$url = str_replace("?", "&", basename($_SERVER['REQUEST_URI']));
	redirect(append_sid("login.php?redirect=admin/$url&admin=1", true));
}

if ( empty($header) )
{
	include('./page_header_admin.php');
}

?>