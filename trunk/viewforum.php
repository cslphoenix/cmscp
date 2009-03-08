<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

if ( isset($HTTP_GET_VARS[POST_FORUM_URL]) || isset($HTTP_POST_VARS[POST_FORUM_URL]) )
{
	$forum_id = ( isset($HTTP_GET_VARS[POST_FORUM_URL]) ) ? intval($HTTP_GET_VARS[POST_FORUM_URL]) : intval($HTTP_POST_VARS[POST_FORUM_URL]);
}
else if ( isset($HTTP_GET_VARS['forum']))
{
	$forum_id = intval($HTTP_GET_VARS['forum']);
}
else
{
	$forum_id = '';
}

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( !empty($forum_id) )
{
	$sql = "SELECT *
		FROM " . FORUM_FORUMS_TABLE . "
		WHERE forum_id = $forum_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
	}
}
else
{
	message_die(GENERAL_MESSAGE, 'Forum_not_exist');
}

if ( !($forum_row = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Forum_not_exist');
}

$userdata = session_pagestart($user_ip, $forum_id);
init_userprefs($userdata);

$page_title = $lang['View_forum'] . ' - ' . $forum_row['forum_name'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array(
	'body' => 'viewforum_body.tpl')
);


$template->pparse("body");
	
include($root_path . 'includes/page_tail.php');

?>