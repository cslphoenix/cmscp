<?php

function auth_acp_check($user_id)
{
	global $db, $oCache, $userdata;
	
	$gaccess = $gauth_access = $uauth_access = $options = $tmp_auth = $userauth = $auth_group = array();
	
	if ( defined('IN_ADMIN') )
	{
		$oCache->sCachePath = './../cache/';
	}
	else
	{
		$oCache->sCachePath = './cache/';
	}
	
	$acl_fields = data(ACL_OPTION, "WHERE auth_option LIKE 'A_%'", false, 1, 3, true, false, 'auth_option');
	
	if ( $userdata['user_founder'] )
	{
		foreach ( $acl_fields as $row )
		{
			$userauth[$row] = '1';
		}
	}
	else
	{
		/* Gruppen des Benutzers abfragen */
		$sql = 'SELECT type_id AS group_id
					FROM ' . LISTS . '
						WHERE type = ' . TYPE_GROUP . '
							AND user_pending = 0
							AND user_id = ' . $user_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$auth_group[] = $row['group_id'];
		}
		$db->sql_freeresult($result);
		
		/* Prüfen ob Gruppen bestehen, falls ja, Prüfen auf Rechte der Gruppen */
		if ( $auth_group )
		{
			$sql = 'SELECT * FROM ' . ACL_GROUPS . ' WHERE group_id IN (' . implode(', ', $auth_group) . ')';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$gauth_access = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
		}
		
		/* Prüfen ob Benutzer extra Rechte hat */
		$sql = 'SELECT * FROM ' . ACL_USERS . ' WHERE user_id = ' . $user_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$uauth_access = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		/* Abfragen der Label für Adminrechte, keine Auswirkung wieviele man erstellt */
		$acl_label = data(ACL_LABEL, "WHERE label_type = 'A_'", false, 1, 3, true);
		
		$sql = 'SELECT * FROM ' . ACL_LABEL_DATA . ' d
					LEFT JOIN ' . ACL_OPTION . ' o ON o.auth_option_id = d.auth_option_id
						WHERE d.label_id IN (' . implode(', ', array_keys($acl_label)) . ')';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$options[$row['label_id']][$row['auth_option_id']] = $row['auth_value'];
		}
		$db->sql_freeresult($result);
		
		if ( $gauth_access )
		{
			foreach ( $gauth_access as $keys => $rows )
			{
				if ( $rows['label_id'] != 0 )
				{
					if ( isset($options[$rows['label_id']]) )
					{
						foreach ( $options[$rows['label_id']] as $key => $row )
						{
							$gaccess[$keys][$acl_fields[$key]] = $row;
						}
					}
				}
				
				if ( $rows['auth_option_id'] != 0 )
				{
					$gaccess[$keys][$acl_fields[$rows['auth_option_id']]] = $rows['auth_value'];
				}
			}
			
			if ( $gaccess )
			{
				foreach ( $gaccess as $rows )
				{
					foreach ( $rows as $key => $row )
					{
						if ( $row == '1' )
						{
							if ( isset($tmp_auth[$key]) )
							{
								if ( $tmp_auth[$key] == '-1' )
								{
									$tmp_auth[$key] = '-1';
								}
								else if ( $tmp_auth[$key] == '0' )
								{
									$tmp_auth[$key] = '1';
								}
							}
							else
							{
								$tmp_auth[$key] = '1';
							}
						}
						else if ( $row == '0' )
						{
							if ( isset($tmp_auth[$key]) )
							{
								if ( $tmp_auth[$key] == '-1' )
								{
									$tmp_auth[$key] = '-1';
								}
								else if ( $tmp_auth[$key] == '1' )
								{
									$tmp_auth[$key] = '1';
								}
							}
							else
							{
								$tmp_auth[$key] = '0';
							}
						}
						else
						{
							$tmp_auth[$key] = '-1';
						}
					}
				}
			}
		}
		
		if ( $uauth_access )
		{
			$uaccess = array();
			
			foreach ( $uauth_access as $keys => $rows )
			{
				if ( $rows['label_id'] != 0 )
				{
					if ( isset($options[$rows['label_id']]) )
					{
						foreach ( $options[$rows['label_id']] as $key => $row )
						{
							$uaccess[$keys][$acl_fields[$key]] = $row;
						}
					}
				}
				
				if ( $rows['auth_option_id'] != 0 )
				{
					$uaccess[$keys][$acl_fields[$rows['auth_option_id']]] = $rows['auth_value'];
				}
			}
			
			foreach ( $uaccess as $rows )
			{
				foreach ( $rows as $key => $row )
				{
					if ( $row == '1' )
					{
						if ( isset($tmp_auth[$key]) )
						{
							if ( $tmp_auth[$key] == '-1' )
							{
								$tmp_auth[$key] = '1';
							}
							else if ( $tmp_auth[$key] == '0' )
							{
								$tmp_auth[$key] = '1';
							}
						}
						else
						{
							$tmp_auth[$key] = '1';
						}
					}
					else if ( $row == '0' )
					{
						if ( isset($tmp_auth[$key]) )
						{
							if ( $tmp_auth[$key] == '-1' )
							{
								$tmp_auth[$key] = '-1';
							}
							else if ( $tmp_auth[$key] == '1' )
							{
								$tmp_auth[$key] = '1';
							}
						}
						else
						{
							$tmp_auth[$key] = '0';
						}
					}
					else
					{
						$tmp_auth[$key] = '-1';
					}
				}
			}
		}
		
	#	debug($tmp_auth, 'tmp_auth');
		
		if ( $tmp_auth )
		{
			foreach ( $tmp_auth as $key => $row )
			{
				if ( $row )
				{
					$userauth[$key] = $row;
				}
			}
		}
	}
	
	return $userauth;
}

function acl_auth($acl_check, $founder = false)
{
	global $db, $current, $log, $userdata, $lang, $mode;
	
	$gaccess = $gauth_access = $uauth_access = $options = $tmp_auth = $userauth = $auth_group = array();

	
	if ( $founder === true && $userdata['user_founder'] != 1 )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['notice_auth_fail1'], langs($acl_check[0])));
	}
	
	$label_type = ( is_array($acl_check) ) ? substr($acl_check[0], 0, 2) : substr($acl_check, 0, 2);
	
	/* Gruppen des Benutzers abfragen */
	$sql = 'SELECT type_id AS group_id
				FROM ' . LISTS . '
					WHERE type = ' . TYPE_GROUP . '
						AND user_pending = 0
						AND user_id = ' . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$auth_group[] = $row['group_id'];
	}
	$db->sql_freeresult($result);
	
	/* Prüfen ob Gruppen bestehen, falls ja, Prüfen auf Rechte der Gruppen */
	if ( $auth_group )
	{
		$sql = "SELECT * FROM " . ACL_GROUPS . " WHERE group_id IN (" . implode(', ', $auth_group) . ")";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$gauth_access = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
	}
	
	/* Prüfen ob Benutzer extra Rechte hat */
	$sql = 'SELECT * FROM ' . ACL_USERS . ' WHERE user_id = ' . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$uauth_access = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	/* Abfragen der Label für Adminrechte, keine Auswirkung wieviele man erstellt */
	$acl_label = data(ACL_LABEL, "WHERE label_type = '$label_type'", false, 1, 3, true);

	$sql = "SELECT * FROM " . ACL_LABEL_DATA . " d
				LEFT JOIN " . ACL_OPTION . " o ON o.auth_option_id = d.auth_option_id
					WHERE d.label_id IN (" . implode(', ', array_keys($acl_label)) . ")";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$options[$row['label_id']][$row['auth_option_id']] = $row['auth_value'];
	}
	
	$acl_fields = data(ACL_OPTION, "WHERE auth_option LIKE '$label_type%'", false, 1, 3, true, false, 'auth_option');

	if ( $gauth_access )
	{
		foreach ( $gauth_access as $keys => $rows )
		{
			if ( $rows['label_id'] != 0 )
			{
				if ( isset($options[$rows['label_id']]) )
				{
					foreach ( $options[$rows['label_id']] as $key => $row )
					{
						$gaccess[$keys][$acl_fields[$key]] = $row;
					}
				}
				$tlabel = $rows['label_id'];
			}
			
			if ( $rows['auth_option_id'] != 0 )
			{
				$gaccess[$keys][$acl_fields[$rows['auth_option_id']]] = $rows['auth_value'];
			}
		}
		
		if ( $gaccess )
		{
			foreach ( $gaccess as $rows )
			{
				foreach ( $rows as $key => $row )
				{
					if ( $row == '1' )
					{
						if ( isset($tmp_auth[$key]) )
						{
							if ( $tmp_auth[$key] == '-1' )
							{
								$tmp_auth[$key] = '-1';
							}
							else if ( $tmp_auth[$key] == '0' )
							{
								$tmp_auth[$key] = '1';
							}
						}
						else
						{
							$tmp_auth[$key] = '1';
						}
					}
					else if ( $row == '0' )
					{
						if ( isset($tmp_auth[$key]) )
						{
							if ( $tmp_auth[$key] == '-1' )
							{
								$tmp_auth[$key] = '-1';
							}
							else if ( $tmp_auth[$key] == '1' )
							{
								$tmp_auth[$key] = '1';
							}
						}
						else
						{
							$tmp_auth[$key] = '0';
						}
					}
					else
					{
						$tmp_auth[$key] = '-1';
					}
				}
			}
		}
	}

	if ( $uauth_access )
	{
		$uaccess = array();
		
		foreach ( $uauth_access as $keys => $rows )
		{
			if ( $rows['label_id'] != 0 )
			{
				if ( isset($options[$rows['label_id']]) )
				{
					foreach ( $options[$rows['label_id']] as $key => $row )
					{
						$uaccess[$keys][$acl_fields[$key]] = $row;
					}
				}
				$tlabel = $rows['label_id'];
			}
			
			if ( $rows['auth_option_id'] != 0 )
			{
				$uaccess[$keys][$acl_fields[$rows['auth_option_id']]] = $rows['auth_value'];
			}
		}
		
		foreach ( $uaccess as $rows )
		{
			foreach ( $rows as $key => $row )
			{
				if ( $row == '1' )
				{
					if ( isset($tmp_auth[$key]) )
					{
						if ( $tmp_auth[$key] == '-1' )
						{
							$tmp_auth[$key] = '1';
						}
						else if ( $tmp_auth[$key] == '0' )
						{
							$tmp_auth[$key] = '1';
						}
					}
					else
					{
						$tmp_auth[$key] = '1';
					}
				}
				else if ( $row == '0' )
				{
					if ( isset($tmp_auth[$key]) )
					{
						if ( $tmp_auth[$key] == '-1' )
						{
							$tmp_auth[$key] = '-1';
						}
						else if ( $tmp_auth[$key] == '1' )
						{
							$tmp_auth[$key] = '1';
						}
					}
					else
					{
						$tmp_auth[$key] = '0';
					}
				}
				else
				{
					$tmp_auth[$key] = '-1';
				}
			}
		}
	}
	
	if ( $tmp_auth )
	{
		foreach ( $tmp_auth as $key => $row )
		{
			if ( $row )
			{
				$userauth[$key] = $row;
			}
		}
	}

	if ( is_array($acl_check) )
	{
		$check = '';
		
		foreach ( $acl_check as $acl )
		{
			if ( in_array($acl, array_keys($userauth)) )
			{
				$check[$acl] = true;
			}
		#	else
		#	{
		#		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		#		message(GENERAL_ERROR, sprintf($lang['notice_auth_fail'], $lang[$current]));
		#	}
		}

		if ( $userdata['user_founder'] != 1 )
		{
			if ( $check )
			{
				foreach ( $check as $_check )
				{
					if ( $_check )
					{
						return true;
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'auth_fail', $current);
						message(GENERAL_ERROR, sprintf($lang['notice_auth_fail2'], $lang[$_check]));
					}
				}
			}
			else
			{
				log_add(LOG_ADMIN, $log, 'auth_fail', $current);
				message(GENERAL_ERROR, sprintf($lang['notice_auth_fail'], $lang[$current]));
			}
		}
		else
		{
			return true;
		}
	}
	else
	{
		if ( $userdata['user_founder'] != 1 )
		{
			if ( in_array($acl_check, array_keys($userauth)) )
			{
				return true;
			}
			else
			{
				debug($acl_check);
				log_add(LOG_ADMIN, $log, 'auth_fail', $current);
				message(GENERAL_ERROR, sprintf($lang['notice_auth_fail4'], langs($acl_check)));
			}
		}
		else
		{
			return true;
		}
	}
}

function auth($type, $forum_id, $userdata)
{
	global $db, $lang;
	
	debug($type, 'type');
	debug($forum_id, 'forum_id');
	debug($userdata, 'userdata');
	
	/*
	switch( $type )
	{
		case AUTH_ALL:
			$a_sql = 'a.auth_view, a.auth_read, a.auth_post, a.auth_reply, a.auth_edit, a.auth_delete, a.auth_sticky, a.auth_announce, a.auth_vote, a.auth_pollcreate';
			$auth_fields = array('auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_vote', 'auth_pollcreate');
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
		case AUTH_VOTE:
			$a_sql = 'a.auth_vote';
			$auth_fields = array('auth_vote');
			break;
		case AUTH_ATTACH:
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
			FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug 
			WHERE ug.user_id = ".$userdata['user_id']. " 
				AND ug.user_pending = 0 
				AND a.group_id = ug.group_id
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
					$auth_user[$key . '_type'] = $lang['Auth_Anonymous_Users'];
					break;

				case AUTH_REG:
					$auth_user[$key] = ( $userdata['session_logged_in'] ) ? TRUE : 0;
					$auth_user[$key . '_type'] = $lang['Auth_Registered_Users'];
					break;

				case AUTH_ACL:
					$auth_user[$key] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_ACL, $key, $u_access, $is_admin) : 0;
					$auth_user[$key . '_type'] = $lang['Auth_Users_granted_access'];
					break;

				case AUTH_MOD:
					$auth_user[$key] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_MOD, 'auth_mod', $u_access, $is_admin) : 0;
					$auth_user[$key . '_type'] = $lang['Auth_Moderators'];
					break;

				case AUTH_ADMIN:
					$auth_user[$key] = $is_admin;
					$auth_user[$key . '_type'] = $lang['Auth_Administrators'];
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
						$auth_user[$f_forum_id][$key . '_type'] = $lang['Auth_Anonymous_Users'];
						break;

					case AUTH_REG:
						$auth_user[$f_forum_id][$key] = ( $userdata['session_logged_in'] ) ? TRUE : 0;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['Auth_Registered_Users'];
						break;

					case AUTH_ACL:
						$auth_user[$f_forum_id][$key] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_ACL, $key, $u_access[$f_forum_id], $is_admin) : 0;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['Auth_Users_granted_access'];
						break;

					case AUTH_MOD:
						$auth_user[$f_forum_id][$key] = ( $userdata['session_logged_in'] ) ? auth_check_user(AUTH_MOD, 'auth_mod', $u_access[$f_forum_id], $is_admin) : 0;
						$auth_user[$f_forum_id][$key . '_type'] = $lang['Auth_Moderators'];
						break;

					case AUTH_ADMIN:
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
	*/

	return $auth_user;
}

function auth_check($auth)
{
	global $userdata, $userauth, $log, $current, $lang;
	
#	debug($userauth, 'userauth');
	
	$_userauth = array();
	
	foreach ( $userauth as $key => $value )
	{
#		debug($key, 'key');
#		debug($value, 'value');
		
		if ( $value == '1' )
		{
			$_userauth[$key] = $key;
		}
	}
	
#	debug($_userauth, '_userauth');
	
	if ( !in_array($auth, array_keys($_userauth)) )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['notice_auth_fail'], langs($auth)));
	}
}

?>