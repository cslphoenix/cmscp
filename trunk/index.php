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

		
	
	
	$sql = 'SELECT user_id, username FROM ' . USERS_TABLE . '';
	$users = _cached($sql, 'user_lists');
	
//	_debug_post(_cached($sql, 'users', 'user_list'));
//	_debug_post($users);

/*
	if (defined('CACHE'))
	{
		$oCache = new Cache;
		
		$sCacheName = 'user_list';
		if (($users = $oCache -> readCache($sCacheName)) === false)
		{
			$sql = 'SELECT user_id, username FROM ' . USERS_TABLE . '';
			$result = $db->sql_query($sql);
			$users = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			
			$oCache -> writeCache($sCacheName, $users);
		}
	}
	else
	{
		$sql = 'SELECT user_id, username FROM ' . USERS_TABLE . '';
		$result = $db->sql_query($sql);
		$users = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
	}
*/	
	for ($i = $start; $i < count($users) + $start; $i++)
	{
		$class = ($i % 2) ? 'row1r' : 'row2r';
		
		$template->assign_block_vars('users', array(
			'CLASS' => $class,
			'ID'	=> $users[$i]['user_id'],
			'USER'	=> $users[$i]['username'],
		));
	}


//
//	Generate the page
//
$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>