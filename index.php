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

$group_auth_fields = array(
	'auth_contact',
	'auth_fightus',
	'auth_forum',
	'auth_forum_auth',
	'auth_games',
	'auth_groups',
	'auth_joinus',
	'auth_match',
	'auth_navi',
	'auth_news',
	'auth_news_public',
	'auth_newscat',
	'auth_ranks',
	'auth_server',
	'auth_teams',
	'auth_teamspeak',
	'auth_training',
	'auth_user',
);

$group_auth_levels	= array('DISALLOWED', 'ALLOWED');
$group_auth_const	= array(AUTH_DISALLOWED, AUTH_ALLOWED);

$field_names = array(
	'auth_contact'		=> $lang['auth_contact'],
	'auth_fightus'		=> $lang['auth_fightus'],
	'auth_forum'		=> $lang['auth_forum'],
	'auth_forum_auth'	=> $lang['auth_forum_auth'],
	'auth_games'		=> $lang['auth_games'],
	'auth_groups'		=> $lang['auth_groups'],
	'auth_joinus'		=> $lang['auth_joinus'],
	'auth_match'		=> $lang['auth_match'],
	'auth_navi'			=> $lang['auth_navi'],
	'auth_news'			=> $lang['auth_news'],
	'auth_news_public'	=> $lang['auth_news_public'],
	'auth_newscat'		=> $lang['auth_newscat'],
	'auth_ranks'		=> $lang['auth_ranks'],
	'auth_server'		=> $lang['auth_server'],
	'auth_teams'		=> $lang['auth_teams'],
	'auth_teamspeak'	=> $lang['auth_teamspeak'],
	'auth_training'		=> $lang['auth_training'],
	'auth_user'			=> $lang['auth_user'],
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


//$auth_data = $db->sql_fetchrowset($result);
/*
function _check_auth($group_id, $auth_name)
{
	
	global $db;
	
	$sql = 'SELECT ' . $auth_name . '
			FROM ' . GROUPS_AUTH_TABLE . '
				WHERE group_id = ' . $group_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	
	
	return $row[$auth_name];
	
	return
}
*/



$sql = 'SELECT g.group_id, ' . implode(', ', $group_auth_fields) . '
			FROM ' . GROUPS_TEST_TABLE . ' g, ' . GROUPS_USER_TABLE . ' gu
			WHERE g.group_id = gu.group_id
				AND gu.user_id = ' . $userdata['user_id'] . ' ORDER BY group_id';
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
}

$authi_data = array();
while ($row = $db->sql_fetchrow($result))
{
	$authi_data[$row['group_id']] = $row;
	
	unset($authi_data[$row['group_id']]['group_id']);
}
$db->sql_freeresult($result);

$sql = 'SELECT group_id, ' . implode(', ', $group_auth_fields) . ' FROM ' . GROUPS_TEST_TABLE . ' WHERE group_single_user = 0 ORDER BY group_id';
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql2);
}

$authi_group = array();
while ($row = $db->sql_fetchrow($result))
{
	$authi_group[$row['group_id']] = $row;
	
	unset($authi_group[$row['group_id']]['group_id']);
}
$db->sql_freeresult($result);


function _check_authi($auth_group_array, $group_id, $auth_name)
{
	$auth_group_array = array();
	return $auth_group_array[$group_id][$auth_name];
}


$authi		= array();
$group_ids	= array_keys($authi_group);
foreach ( $authi_data as $key => $value )
{
	foreach ($group_ids as $group_key => $group_id)
	{
		foreach( $value as $v_key => $v_value )
		{
			if ( $v_value == '0' )
			{
				if ( !array_key_exists($v_key, $authi) )
				{
					$authi[$v_key] = $authi_group[$group_id][$v_key];
				//	$authi[$v_key] = _check_authi($authi_group, $group_id, $v_key);
				}
				else if ( !$authi[$v_key] )
				{
					$authi[$v_key] = $authi_group[$group_id][$v_key];
				//	$authi[$v_key] = _check_authi($authi_group, $group_id, $v_key);
				}
			}
			else
			{
				$authi[$v_key] = $v_value;
			}
		}
	}
}

/*

function _check_auth($group_array,$group_id, $auth_name)
{
	return $group_array[$group_id][$auth_name];
}
$sql = 'SELECT ga.group_id, ga.auth_contact, ga.auth_fightus, ga.auth_forum, ga.auth_forum_auth, ga.auth_games, ga.auth_groups, ga.auth_joinus, ga.auth_match, ga.auth_navi, ga.auth_news, ga.auth_news_public, ga.auth_newscat, ga.auth_ranks, ga.auth_server, ga.auth_teams, ga.auth_teamspeak, ga.auth_training
			FROM ' . USERS_TABLE . ' u, ' . USERS_AUTH_TABLE . ' ga
			WHERE u.user_id = ga.user_id
				AND u.user_id = ' . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
}

$auth_data=array();

while ($row = $db->sql_fetchrow($result))
{
	$auth_data[$row['group_id']] = $row;
	unset($auth_data[$row['group_id']]['group_id']);
}
$db->sql_freeresult($result);

_debug_post($auth_data);

$sql2 = 'SELECT * FROM ' . GROUPS_AUTH_TABLE;
if ( !($result2 = $db->sql_query($sql2)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql2);
}


while ($row2 = $db->sql_fetchrow($result2))
{
	$auth_gruppen[$row2['group_id']] = $row2;
	unset($auth_gruppen[$row2['group_id']]['group_id']);
}
$db->sql_freeresult($result2);

_debug_post($auth_gruppen);


$group_id=array_keys($auth_data);

foreach($auth_data as $key => $value )
{
	
	unset($value['group_id']);
	$auth=array();
	foreach($group_id as $group_key => $group_value){
		foreach( $value as $v_key => $v_value )
		{
			if ( $v_value == NULL && ($v_key != 'group_id' ||$v_key != 'user_id' ))
			{
				//wenn auth schon einen wert ander stelle von $v_key hat
				//dann wird überprüft ob an der stelle es gleich 0 steht wenn ja kann man 
				//in der neuen group_id den wert auslesen und wenn dieser auch 0 ist is es auch egal 
				//if(!(isset($auth[$v_key]))){
				//	$auth[$v_key] = _check_auth($auth_gruppen, $group_id, $v_key);
				//}
				
				if(!(array_key_exists($v_key,$auth))){
					$auth[$v_key] = _check_auth($auth_gruppen, $group_value, $v_key);
				}
				else if($auth[$v_key] == 0){
					$auth[$v_key] = _check_auth($auth_gruppen, $group_value, $v_key);
				}
				
				
				//Wenns leer ist an der stelle dann wird das array befüllt
				
	
			}
			else
			{
				$auth[$v_key] = $v_value;
			}
		}
	}
}
//_debug_post(_check_auth($gruppen_id, $v_key))
_debug_post($auth);
*/


/*
$test = array();
foreach ( $auth_data as $auth )
{
	foreach ( $auth as $key => $value )
	{
		$test[$key] += $value;
		_debug_post($test);
	}
}
*/
/*
$user_auth = array();

foreach ( $auth_data as $auth )
{
	foreach ( $auth as $key => $value )
	{
		$user_auth[$key][] = $value;
//		$user_auth[$key] .= $value;
	}
}

_debug_post($user_auth);
*/
/*
mit user_id
foreach ( $user_auth as $uauth => $uvalue )
{
	if ( $uauth != 'user_id' )
	{
		$count = ( count($uvalue) >= 1 ) ? '1' : '0';
		$user[$uauth] = $count;
	}
	else
	{
		$user[$uauth] = array_pop($uvalue);
	}
}
*/



/*
foreach ( $user_auth as $auth_name )
{
	
	foreach ( $auth_name as $name => $werte )
	{
		
//		$count[$name] += $wert;
	}
//	$count = ( count($uvalue) >= 1 ) ? '1' : '0';
//	_debug_post($count);
//	$user[$uauth] = $count;
}
*/
/*
$sql = 'SELECT * FROM ' . GROUPS_TEST_TABLE . ' WHERE group_single_user = 0';
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql2);
}
$test_data = $db->sql_fetchrowset($result);

for ( $i = 0; $i < count($test_data); $i++ )
{
	unset($test_data[$i]['group_type']);
 	unset($test_data[$i]['group_access']);
	unset($test_data[$i]['group_name']);
	unset($test_data[$i]['group_description']);
	unset($test_data[$i]['group_color']);
	unset($test_data[$i]['group_avatar']);
	unset($test_data[$i]['group_avatar_type']);
	unset($test_data[$i]['group_rank']);
	unset($test_data[$i]['group_legend']);
	unset($test_data[$i]['group_single_user']);
	unset($test_data[$i]['group_order']);
	
	$auth_groups[$test_data[$i]['group_id']] = $test_data[$i];
	
	unset($auth_groups[$test_data[$i]['group_id']]['group_id']);
}

_debug_post($auth_groups);
*/
/*
for ($j = 0; $j < count($group_auth_fields); $j++)
{
	$custom_auth[$j] = '<select class="post" name="' . $group_auth_fields[$j] . '">';

	for($k = 0; $k < count($group_auth_levels); $k++)
	{
		$selected = ( $user[$group_auth_fields[$j]] == $group_auth_const[$k] ) ? ' selected="selected"' : '';
		$custom_auth[$j] .= '<option value="' . $group_auth_const[$k] . '"' . $selected . '>' . $lang['Forum_' . $group_auth_levels[$k]] . '</option>';
	}
	$custom_auth[$j] .= '</select>';
	
	$cell_title = $field_names[$group_auth_fields[$j]];

	$template->assign_block_vars('group_auth_data', array(
		'CELL_TITLE'			=> $cell_title,
		'S_AUTH_LEVELS_SELECT'	=> $custom_auth[$j],
	));
}
*/
$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>