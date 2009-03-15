<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//
//	Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

//
//	Start output of page
//
define('SHOW_ONLINE', true);
$page_title = $lang['Index'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array(
	'body' => 'index_body.tpl')
);

$template->assign_vars(array(
	
	'L_MARK_FORUMS_READ' => $lang['Mark_all_forums'], 

	'U_MARK_READ' => append_sid("index.php?mark=forums"))
);

if ( $mode == 'cache')
{
	_cache_clear();
	
	message_die(GENERAL_MESSAGE, 'Cache geleert!');
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>