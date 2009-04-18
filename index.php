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
$page_title = $lang['Index'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array(
	'body' => 'index_body.tpl')
);

$group_auth_fields = get_authlist();

$group_auth_levels	= array('DISALLOWED', 'ALLOWED');
$group_auth_const	= array(AUTH_DISALLOWED, AUTH_ALLOWED);

$template->assign_vars(array(
	
	'L_MARK_FORUMS_READ' => $lang['Mark_all_forums'], 

	'U_MARK_READ' => append_sid("index.php?mark=forums"))
);

if ( $mode == 'cache')
{
	_cache_clear();
	
	message_die(GENERAL_MESSAGE, 'Cache geleert!');
}


//
//	Gruppen in der der Benutzer vertreten ist
//
$sql = 'SELECT g.group_id, ' . implode(', ', $group_auth_fields) . '
			FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
			WHERE g.group_id = gu.group_id
				AND gu.user_id = ' . $userdata['user_id'] . ' ORDER BY group_id';
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
}

$usergroup_index_data = $group_in = array();
while ( $row_user = $db->sql_fetchrow($result) )
{
	$group_in[] = $row_user['group_id'];
	$usergroup_index_data[$row_user['group_id']] = $row_user;
	
	unset($usergroup_index_data[$row_user['group_id']]['group_id']);
}
$db->sql_freeresult($result);


$sql = 'SELECT group_id, ' . implode(', ', $group_auth_fields) . '
			FROM ' . GROUPS . '
			WHERE group_single_user = 0
				AND group_id IN (' . implode(', ', $group_in) . ')
		ORDER BY group_id';
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql2);
}

$group_index_data = array();
while ( $row_group = $db->sql_fetchrow($result) )
{
	$group_index_data[$row_group['group_id']] = $row_group;
	
	unset($group_index_data[$row_group['group_id']]['group_id']);
}
$db->sql_freeresult($result);


$authi		= array();
$group_ids	= array_keys($group_index_data);
foreach ( $usergroup_index_data as $key => $value )
{
	foreach ($group_ids as $group_key => $group_id)
	{
		foreach( $value as $v_key => $v_value )
		{
			if ( $v_value == '0' )
			{
				if ( !array_key_exists($v_key, $authi) )
				{
					$authi[$v_key] = $group_index_data[$group_id][$v_key];
				}
				else if ( !$authi[$v_key] )
				{
					$authi[$v_key] = $group_index_data[$group_id][$v_key];
				}
			}
			else
			{
				$authi[$v_key] = $v_value;
			}
		}
	}
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>