<?php
/***************************************************************************
 *                                 auth.php
 *                            -------------------                         
 *   begin                : Saturday, Feb 13, 2001 
 *   copyright            : (C) 2001 The phpBB Group        
 *   email                : support@phpbb.com                           
 *                                                          
 *   $Id: auth.php 5604 2006-03-06 17:28:51Z grahamje $                                                           
 *                                                            
 * 
 ***************************************************************************/ 

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/*
	$type's accepted (pre-pend with AUTH_):
	VIEW, READ, POST, REPLY, EDIT, DELETE, STICKY, ANNOUNCE, VOTE, POLLCREATE

	Possible options ($type/forum_id combinations):

	* If you include a type and forum_id then a specific lookup will be done and
	the single result returned

	* If you set type to AUTH_ALL and specify a forum_id an array of all auth types
	will be returned

	* If you provide a forum_id a specific lookup on that forum will be done

	* If you set forum_id to AUTH_LIST_ALL and specify a type an array listing the
	results for all forums will be returned

	* If you set forum_id to AUTH_LIST_ALL and type to AUTH_ALL a multidimensional
	array containing the auth permissions for all types and all forums for that
	user is returned

	All results are returned as associative arrays, even when a single auth type is
	specified.

	If available you can send an array (either one or two dimensional) containing the
	forum auth levels, this will prevent the auth function having to do its own
	lookup
*/

function auth($type, $forum_id, $userdata, $f_access = '')
{
	global $db, $lang;

	switch( $type )
	{
		case AUTH_ALL:
			$a_sql = 'a.auth_view, a.auth_read, a.auth_post, a.auth_reply, a.auth_edit, a.auth_delete, a.auth_sticky, a.auth_announce, a.auth_poll, a.auth_pollcreate';
			$auth_fields = array('auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_poll', 'auth_pollcreate');
			break;

		case AUTH_VIEW:
			$a_sql = 'a.auth_view';
			$auth_fields = array('auth_view');
			break;

		case AUTH_READ:
			$a_sql = 'a.auth_read';
			$auth_fields = array('auth_read');
			break;
		case AUTH_POST:
			$a_sql = 'a.auth_post';
			$auth_fields = array('auth_post');
			break;
		case AUTH_REPLY:
			$a_sql = 'a.auth_reply';
			$auth_fields = array('auth_reply');
			break;
		case AUTH_EDIT:
			$a_sql = 'a.auth_edit';
			$auth_fields = array('auth_edit');
			break;
		case AUTH_DELETE:
			$a_sql = 'a.auth_delete';
			$auth_fields = array('auth_delete');
			break;

		case AUTH_ANNOUNCE:
			$a_sql = 'a.auth_announce';
			$auth_fields = array('auth_announce');
			break;
		case AUTH_STICKY:
			$a_sql = 'a.auth_sticky';
			$auth_fields = array('auth_sticky');
			break;

		case AUTH_POLLCREATE:
			$a_sql = 'a.auth_pollcreate';
			$auth_fields = array('auth_pollcreate');
			break;
		case AUTH_POLL:
			$a_sql = 'a.auth_poll';
			$auth_fields = array('auth_poll');
			break;

		default:
			break;
	}

	//
	// If f_access has been passed, or auth is needed to return an array of forums
	// then we need to pull the auth information on the given forum (or all forums)
	//
	if ( empty($f_access) )
	{
		$forum_match_sql = ( $forum_id != AUTH_LIST_ALL ) ? "WHERE a.forum_id = $forum_id" : '';

		$sql = "SELECT a.forum_id, $a_sql
			FROM " . FORUMS_TABLE . " a
			$forum_match_sql";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Failed obtaining forum access control lists', '', __LINE__, __FILE__, $sql);
		}

		$sql_fetchrow = ( $forum_id != AUTH_LIST_ALL ) ? 'sql_fetchrow' : 'sql_fetchrowset';

		if ( !($f_access = $db->$sql_fetchrow($result)) )
		{
			$db->sql_freeresult($result);
			return array();
		}
		$db->sql_freeresult($result);
	}

	//
	// If the user isn't logged on then all we need do is check if the forum
	// has the type set to ALL, if yes they are good to go, if not then they
	// are denied access
	//
	$u_access = array();
	if ( $userdata['session_logged_in'] )
	{
		$forum_match_sql = ( $forum_id != AUTH_LIST_ALL ) ? "AND a.forum_id = $forum_id" : '';

		$sql = "SELECT a.forum_id, $a_sql, a.auth_mod 
			FROM " . AUTH_ACCESS_TABLE . " a, " . GROUPS_USER_TABLE . " gu 
			WHERE gu.user_id = " . $userdata['user_id'] . " 
				AND gu.user_pending = 0 
				AND a.group_id = gu.group_id
				$forum_match_sql";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Failed obtaining forum access control lists', '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			do
			{
				if ( $forum_id != AUTH_LIST_ALL)
				{
					$u_access[] = $row;
				}
				else
				{
					$u_access[$row['forum_id']][] = $row;
				}
			}
			while( $row = $db->sql_fetchrow($result) );
		}
		$db->sql_freeresult($result);
	}

	$is_admin = ( $userdata['user_level'] == ADMIN && $userdata['session_logged_in'] ) ? TRUE : 0;

	$auth_user = array();
	for($i = 0; $i < count($auth_fields); $i++)
	{
		$key = $auth_fields[$i];

		//
		// If the user is logged on and the forum type is either ALL or REG then the user has access
		//
		// If the type if ACL, MOD or ADMIN then we need to see if the user has specific permissions
		// to do whatever it is they want to do ... to do this we pull relevant information for the
		// user (and any groups they belong to)
		//
		// Now we compare the users access level against the forums. We assume here that a moderator
		// and admin automatically have access to an ACL forum, similarly we assume admins meet an
		// auth requirement of MOD
		//
		if ( $forum_id != AUTH_LIST_ALL )
		{
			$value = $f_access[$key];

			switch( $value )
			{
				case AUTH_ALL:
					$auth_user[$key] = TRUE;
					$auth_user[$key . '_type'] = $lang['auth_anonymous_users'];
					break;

				case AUTH_REG:
					$auth_user[$key] = ( $userdata['session_logged_in'] ) ? TRUE : 0;
					$auth_user[$key . '_type'] = $lang['auth_registered_users'];
					break;
					
				case AUTH_TRI:
					$auth_user[$key] = ( $userdata['session_logged_in'] ) ? TRUE : 0;
					$auth_user[$key . '_type'] = $lang['auth_trial_users'];
					break;
					
				case AUTH_MEM:
					$auth_user[$key] = ( $userdata['session_logged_in'] ) ? TRUE : 0;
					$auth_user[$key . '_type'] = $lang['auth_member_users'];
					break;

				case AUTH_MOD:
					$auth_user[$key] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_MOD, 'auth_mod', $u_access, $is_admin) : 0;
					$auth_user[$key . '_type'] = $lang['auth_moderators'];
					break;
					
				case AUTH_ACL:
					$auth_user[$key] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_ACL, $key, $u_access, $is_admin) : 0;
					$auth_user[$key . '_type'] = $lang['auth_users_granted_access'];
					break;

				case AUTH_ADM:
					$auth_user[$key] = $is_admin;
					$auth_user[$key . '_type'] = $lang['auth_administrators'];
					break;

				default:
					$auth_user[$key] = 0;
					break;
			}
		}
		else
		{
			for($k = 0; $k < count($f_access); $k++)
			{
				$value = $f_access[$k][$key];
				$f_forum_id = $f_access[$k]['forum_id'];
				$u_access[$f_forum_id] = isset($u_access[$f_forum_id]) ? $u_access[$f_forum_id] : array();

				switch( $value )
				{
					case AUTH_ALL:
						$auth_user[$f_forum_id][$key] = TRUE;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['auth_anonymous_users'];
						break;

					case AUTH_REG:
						$auth_user[$f_forum_id][$key] = ( $userdata['session_logged_in'] ) ? TRUE : 0;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['auth_registered_users'];
						break;
						
					case AUTH_TRI:
						$auth_user[$f_forum_id][$key] = ( $userdata['session_logged_in'] ) ? TRUE : 0;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['auth_trial_users'];
						break;
						
					case AUTH_MEM:
						$auth_user[$f_forum_id][$key] = ( $userdata['session_logged_in'] ) ? TRUE : 0;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['auth_member_users'];
						break;

					case AUTH_MOD:
						$auth_user[$f_forum_id][$key] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_MOD, 'auth_mod', $u_access[$f_forum_id], $is_admin) : 0;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['auth_moderators'];
						break;
						
					case AUTH_ACL:
						$auth_user[$f_forum_id][$key] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_ACL, $key, $u_access[$f_forum_id], $is_admin) : 0;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['auth_administrators'];
						break;

					case AUTH_ADM:
						$auth_user[$f_forum_id][$key] = $is_admin;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['Auth_Administrators'];
						break;

					default:
						$auth_user[$f_forum_id][$key] = 0;
						break;
				}
			}
		}
	}

	//
	// Is user a moderator?
	//
	if ( $forum_id != AUTH_LIST_ALL )
	{
		$auth_user['auth_mod'] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_MOD, 'auth_mod', $u_access, $is_admin) : 0;
	}
	else
	{
		for($k = 0; $k < count($f_access); $k++)
		{
			$f_forum_id = $f_access[$k]['forum_id'];
			$u_access[$f_forum_id] = isset($u_access[$f_forum_id]) ? $u_access[$f_forum_id] : array();

			$auth_user[$f_forum_id]['auth_mod'] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_MOD, 'auth_mod', $u_access[$f_forum_id], $is_admin) : 0;
		}
	}

	return $auth_user;
}

function auth_check_user($type, $key, $u_access, $is_admin)
{
	$auth_user = 0;

	if ( count($u_access) )
	{
		for($j = 0; $j < count($u_access); $j++)
		{
			$result = 0;
			switch($type)
			{
				case AUTH_ACL:
					$result = $u_access[$j][$key];

				case AUTH_MOD:
					$result = $result || $u_access[$j]['auth_mod'];

				case AUTH_ADM:
					$result = $result || $is_admin;
					break;
			}

			$auth_user = $auth_user || $result;
		}
	}
	else
	{
		$auth_user = $is_admin;
	}

	return $auth_user;
}

function auth_acp_check($user_id)
{
	global $db;
	
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
	
	$sql = 'SELECT g.group_id, ' . implode(', ', $group_auth_fields) . '
				FROM ' . GROUPS_TEST_TABLE . ' g, ' . GROUPS_USER_TABLE . ' gu
				WHERE g.group_id = gu.group_id
					AND gu.user_pending = 0
					AND gu.user_id = ' . $user_id . ' ORDER BY group_id';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
	}
	
	$auth_data = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$auth_data[$row['group_id']] = $row;
		
		unset($auth_data[$row['group_id']]['group_id']);
	}
	$db->sql_freeresult($result);
	
	$sql = 'SELECT group_id, ' . implode(', ', $group_auth_fields) . ' FROM ' . GROUPS_TEST_TABLE . ' WHERE group_single_user = 0 ORDER BY group_id';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql2);
	}
	
	$auth_group = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$auth_group[$row['group_id']] = $row;
		
		unset($auth_group[$row['group_id']]['group_id']);
	}
	$db->sql_freeresult($result);
	
	$auth		= array();
	$group_ids	= array_keys($auth_group);
	foreach ( $auth_data as $key => $value )
	{
		foreach ($group_ids as $group_key => $group_id)
		{
			foreach( $value as $v_key => $v_value )
			{
				if ( $v_value == '0' )
				{
					if ( !array_key_exists($v_key, $auth) )
					{
						$auth[$v_key] = $auth_group[$group_id][$v_key];
					}
					else if ( !$auth[$v_key] )
					{
						$auth[$v_key] = $auth_group[$group_id][$v_key];
					}
				}
				else if ( $v_value == '2' )
				{
					$auth[$v_key] = $v_value;
				}
				else
				{
					$auth[$v_key] = $v_value;
				}
			}
		}
	}
	
	return $auth;
}

?>