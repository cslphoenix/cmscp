<?php

if (!defined('IN_CMS'))
{
	die("Hacking attempt");
}

define('IN_ADMIN', true);
include($root_path . 'common.php');

//
//	Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ADMIN);
init_userprefs($userdata);

$auth = auth_acp_check($userdata['user_id']);

if (!$userdata['session_logged_in'])
{
	$url = str_replace("?", "&", basename($HTTP_SERVER_VARS['REQUEST_URI']));
	redirect(append_sid("login.php?redirect=admin/$url", true));
}

if ($HTTP_GET_VARS['sid'] != $userdata['session_id'])
{
	redirect("index.php?sid=" . $userdata['session_id']);
}

if (!$userdata['session_admin'])
{
	$url = str_replace("?", "&", basename($HTTP_SERVER_VARS['REQUEST_URI']));
	redirect(append_sid("login.php?redirect=admin/$url&admin=1", true));
}

if (empty($no_page_header))
{
	// Not including the pageheader can be neccesarry if META tags are
	// needed in the calling script.
	include('./page_header_admin.php');
}

?>