<?php

function auth_acp_check($user_id)
{
	global $db, $oCache;
	
	$gaccess = array();
	$gauth_access = array();
	$uauth_access = array();
	$options = array();
	$tmp_auth = array();
	$userauth = array();
	$auth_group = array();
	
	if ( defined('IN_ADMIN') )
	{
		$oCache->sCachePath = './../cache/';
	}
	else
	{
		$oCache->sCachePath = './cache/';
	}
	
	/* Gruppen des Benutzers abfragen */
	$sql = 'SELECT type_id AS group_id FROM ' . LISTS . ' WHERE type = ' . TYPE_GROUP . ' AND user_pending = 0 AND user_id = ' . $user_id;
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
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$gauth_access[] = $row;
		}
		$db->sql_freeresult($result);
	}
	
	/* Prüfen ob Benutzer extra Rechte hat */
	$sql = 'SELECT * FROM ' . ACL_USERS . ' WHERE user_id = ' . $user_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
		
	while ( $row = $db->sql_fetchrow($result) )
	{
		$uauth_access[] = $row;
	}
	$db->sql_freeresult($result);
	
	/* Abfragen der Label für Adminrechte, keine Auswirkung wieviele man erstellt */
	$acl_label = data(ACL_LABEL, "WHERE label_type = 'a_'", false, 1, 3, true);
	
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
	
	$acl_fields = data(ACL_OPTION, "WHERE auth_option LIKE 'a_%'", false, 1, 3, true, false, 'auth_option');
	
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
	
	return $userauth;
}

?>