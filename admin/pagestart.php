<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

define('IN_ADMIN', true);

$root_path = './../';

include($root_path . 'common.php');
include($root_path . 'includes/acp/acp_build.php');
include($root_path . 'includes/acp/acp_functions.php');
include($root_path . 'includes/acp/acp_selects.php');
include($root_path . 'includes/acp/acp_upload.php');

$userdata = session_pagestart($user_ip, PAGE_ADMIN);
init_userprefs($userdata);

$userauth = auth_acp_check($userdata['user_id']);

if ( !$userdata['session_logged_in'] )
{
	$url = str_replace("?", "&", basename($_SERVER['REQUEST_URI']));
	redirect(check_sid("login.php?redirect=admin/$url", true));
}

if ( $_GET['sid'] != $userdata['session_id'] )
{
	redirect('index.php?sid=' . $userdata['session_id']);
}

if ( !$userdata['session_admin'] )
{
	$url = str_replace("?", "&", basename($_SERVER['REQUEST_URI']));
	redirect(check_sid("login.php?redirect=admin/$url&i=1&admin=1", true));
}

if ( empty($cancel) )
{
	include('./page_header_admin.php');
}

?>