<?php

function update_main_id($id, $user = false, $group = false, $color_update = false)
{
	global $db, $lang;
	
	$data = ( $group ) ? data(GROUPS, $id, false, 1, true) : false;
	$info = ( $group ) ? 'group_id' : 'team_id';
	
	if ( !$user )
	{
		$sql = "SELECT l.user_id
				FROM " . LISTS . " l
					LEFT JOIN " . USERS . " u ON l.user_id = u.user_id
				WHERE type = " . TYPE_GROUP . " AND type_id = $id AND user_pending = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$user[] = $row['user_id'];
		}
	}
	
	$sql = "SELECT user_id, $info, user_level FROM " . USERS . " WHERE user_id IN (" . implode(', ', $user) . ")";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$users = array();
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		if ( $color_update )
		{
			$users[] = $row;
		}
		else if ( $row[$info] != $id )
		{
			$users[] = $row;
		}
	}
	
	if ( $users )
	{
		foreach ( $users as $user => $row )
		{
			$sql = "UPDATE " . USERS . " SET $info = $id";
			
			if ( $data )
			{
				$sql .= ", user_color = '{$data['group_color']}'";
				
				if ( $data['group_access'] == ADMIN && ( $row['user_level'] < ADMIN || $row['user_level'] < MOD || $row['user_level'] < MEMBER || $row['user_level'] < TRIAL ) )
				{
					$sql .= ", user_level = " . ADMIN;
				}
				else if ( $data['group_access'] == MOD && ( $row['user_level'] < MOD || $row['user_level'] < MEMBER || $row['user_level'] < TRIAL ) )
				{
					$sql .= ", user_level = " . MOD;
				}
				else if ( $data['group_access'] == MEMBER && ( $row['user_level'] < MEMBER || $row['user_level'] < TRIAL ) )
				{
					$sql .= ", user_level = " . MEMBER;
				}
				else if ( $data['group_access'] == TRIAL && $row['user_level'] < TRIAL )
				{
					$sql .= ", user_level = " . TRIAL;
				}
			}
			
			$sql .= " WHERE user_id = {$row['user_id']}";
			if ( !$result = $db->sql_query($sql) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		}
	}
}

function sql($table, $type, $submit, $id_field = '', $id = '')
{
	global $db, $lang;
	
	/*
		@params:	$table		=> SQL Table
		@params:	$type		=> insert, update, delete
		@params:	$submit		=> Data
		@params:	$id_field	=> ID Field
		@params:	$id			=> ID
	*/
	
	switch ( $type )
	{
		case strstr($type, 'change'):
		
			break;
	}
	
	if ( strstr($type, 'create') )
	{
		foreach ( $submit as $key => $var )
		{
			$keys[] = $key;
			$vars[] = $var;
		}
		
		$name = 0;
		
		foreach ( $keys as $int => $key )
		{
			if ( strpos($key, '_name') !== false || strpos($key, '_title') !== false )
			{
				$name = $int;
			}
		}
		
		$key = $keys;
		$var = $vars;
		
		$keys = implode(', ', $keys);
		$vars = implode('\', \'', $vars);
		
		$sql = "INSERT INTO $table ($keys) VALUES ('$vars')";
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$return = array(array('field' => $key[$name], 'post' => $var[$name]));
	}
	else if ( strstr($type, 'update') )
	{
		$change = '';
		
		if ( defined('IN_ADMIN') )
		{
			$data = array_merge($submit, array($id_field => $id));
			$data_db = data($table, $id, false, 1, true);
			
			if ( $data_db )
			{
				$new = array_diff_assoc($data_db, $data);
				
				if ( $new )
				{
					$change = '';
					
					foreach ( $new as $key_new => $value_new )
					{
						foreach ( $data as $key_data => $value_data )
						{
							if ( $key_new == $key_data )
							{
								$change[] = array(
									'field'	=> $key_new,
									'data'	=> $value_new,
									'post'	=> $value_data,
									'meta'	=> $id
								);
							}
						}
					}
					
					$change = $change;
				}
				else
				{
					$change = 'notice_no_changes';
				}
			}
			else
			{
				$change = '!data_db';
			}
		}
		
		foreach ( $submit as $key => $var )
		{
			$input[] = "$key = '$var'";
		}
		
		$input = implode(', ', $input);
		
		if ( is_array($id_field) && is_array($id) )
		{
			foreach ( $id_field as $key => $row )
			{
				$ary_new[] = "$row = " . $id[$key];
			}
			
			$sql_where = implode(' AND ', $ary_new);
		}
		else
		{
			$sql_where = "$id_field = $id";
		}
		
		$sql = "UPDATE $table SET $input WHERE $sql_where";
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$return = $change;
	}
	else if ( strstr($type, 'modify') )
	{
		foreach ( $submit as $row_id => $row_vars )
		{
			foreach ( $row_vars as $_name => $_value )
			{
				$_update[$row_id][] = "$_name = '$_value'";
			}
			
			$input[$row_id] = implode(', ', $_update[$row_id]);

			$sql = "UPDATE $table SET $input[$row_id] WHERE $id_field = $row_id";
			if ( !$db->sql_query($sql) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		}
	}
	else if ( strstr($type, 'alter') )
	{
		$part = $submit['part'];
		$type = $submit['type'];
		
		$sql = "ALTER TABLE $table $part $type";
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$return = '';
	}
	else if ( strstr($type, 'delete') )
	{
		unset($submit[$id_field]);
		
		if ( !empty($submit) )
		{
			foreach ( $submit as $key => $var )
			{
				$vars[] = $var;
			}
		}
		else
		{
			$var[] = '';
		}
		
		$sql_where = ( is_array($id) ) ? "$id_field IN (" . implode(', ', $id) . ")" : "$id_field = $id";
		
		$sql = "DELETE FROM $table WHERE $sql_where";
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$return = $var[0];
	}
	
	return $return;
}

/*
 *	Soll die Abfragen der Datenbank einfacher machen!
 *
 *	@param: string	$s_table	example: GAMES
 *	@param: string	$s_where	example: game_id != -1
 *	@param: string	$s_order	example: game_order ASC
 *	@param:	int		$s_sql		example: 1
 *	@param: both	$s_fetch	example: true or false 
 *
 */
function data($s_table, $s_where, $s_order, $s_sql, $s_fetch, $s_cache = '', $s_time = '', $s_separate = '')
{
	global $db, $lang, $oCache;
	
	switch ( $s_table )
	{
		case NEWS:			$field_id = 'news_id';	$field_link = 'news_cat';	$table_link = NEWS_CAT;	$field_id2 = 'cat_id';	break;
		case TEAMS:			$field_id = 'team_id';	$field_link = 'team_game';	$table_link = GAMES;	$field_id2 = 'game_id';	break;
		
		/* only match for index? */
		case MATCH:			( $s_sql == 4 ) ? $tmp_ary = array('s_table1' => MATCH, 'field_id1' => 'match_id', 'field_link1' => 'team_id', 's_table2' => TEAMS, 'field_id2' => 'team_id', 'field_link2' => 'team_game', 's_table3' => GAMES, 'field_id3' => 'game_id') : $field_id = 'match_id'; $field_link = 'team_id'; break;
		case MATCH_TYPE:	$ary = array('s_table1' => MATCH_TYPE, 'name' => 'type_name', 'value' => 'type_value'); break;
		case SETTINGS:		$ary = array('s_table1' => SETTINGS, 'name' => 'settings_name', 'value' => 'settings_value'); break;

	#	default: message(GENERAL_ERROR, 'Error Data Mode: ' . $s_table);	break;
	}
	
	if ( !isset($field_id) && $s_table )
	{
		$sql = 'SHOW FIELDS FROM ' . $s_table;
		
		if ( defined('CACHE') )
		{
			$sCacheName = $s_table . '_show';
			
			if ( ( $field_id = $oCache->readCache($sCacheName) ) === false )
			{
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$temp[] = $row['Field'];
				}
				
				$field_id = array_shift($temp);
				
				$oCache->writeCache($sCacheName, $field_id);
			}
		}
		else
		{
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$temp[] = $row['Field'];
			}
			
			$field_id = array_shift($temp);
		}
	}
	
	if ( strstr($s_where, 'user_id') )
	{
		$where = "WHERE $s_where";
	}
	else if ( strstr($s_where, 'WHERE') )
	{
		$where = $s_where;
	}
	else if ( $s_where )
	{
		$where = "WHERE $field_id = $s_where";
	}
	else
	{
		$where = '';
	}
	
#	$where = ( preg_match('/rank_type/i', $s_where) ) ? "WHERE $s_where" : false;
#	$where = ( $s_where ) ? "WHERE $field_id = $s_where" : '';
#	$where = ( $s_where == '-1' ) ? $field_where : false;

	$order = ( $s_order ) ? "ORDER BY $s_order" : '';
	
	switch ( $s_sql )
	{
		case 0: $sql = "SELECT * FROM $s_table $order"; break;
		case 1: $sql = "SELECT * FROM $s_table $where $order"; break;
		case 2: $sql = "SELECT t1.*, t2.* FROM $s_table t1, $table_link t2 WHERE t1.$field_id = $s_where AND t1.$field_link = t2.$field_id2"; break;
		case 3: $sql = "SELECT t1.*, t2.* FROM $s_table t1 LEFT JOIN $table_link t2 ON t1.$field_link = t2.$field_id2 $order";
				$sql .= ($s_where) ? " WHERE t1.$field_id = $s_where" : ""; break;
		/* match only ? */
		case 4: $sql = "SELECT m.*, t.*, g.* FROM {$tmp_ary['s_table1']} m LEFT JOIN {$tmp_ary['s_table2']} t ON m.{$tmp_ary['field_link1']} = t.{$tmp_ary['field_id2']} LEFT JOIN {$tmp_ary['s_table3']} g ON t.{$tmp_ary['field_link2']} = g.{$tmp_ary['field_id3']} $where $order"; break;
		case 5: $sql = "SELECT * FROM {$ary['s_table1']} $where"; break;
		default: message(GENERAL_ERROR, 'Wrong mode for data', '', __LINE__, __FILE__); break;
	}
	
	$sCacheName = strtolower($s_table);
	
	if ( defined('CACHE') && $s_cache )
	{
		if ( ( $data = $oCache->readCache($sCacheName) ) === false )
		{
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			switch ( $s_fetch )
			{
				case 0:	$data = $db->sql_fetchrowset($result);	break;
				case 1:	$data = $db->sql_fetchrow($result);		break;
				case 2:
					
					while ( $row = $db->sql_fetchrow($result) )
					{
						$data[$row[$ary['name']]] = unserialize($row[$ary['value']]);
					}
					
					break;
				
				case 3:
				
					while ( $row = $db->sql_fetchrow($result) )
					{
						$data[$row[$field_id]] = ($s_separate) ? $row[$s_separate] : $row;
					}
					
					break;
				
				case 4:	/*	acp_change	*/
				
					while ( $row = $db->sql_fetchrow($result) )
					{
						if ( !$row['main'] )
						{
							$data['main'][] = $row;
						}
						else
						{
							$data['data_id'][$row['main']][] = $row;
						}
					}
					
					break;

                case 5: /*  acp_network */

					while ( $row = $db->sql_fetchrow($result) )
					{
						$data[$row[$s_separate]][] = $row;
					}

					break;
			}
			
			( $s_time ) ? $oCache->writeCache($sCacheName, $data, (int) $s_time) : $oCache->writeCache($sCacheName, $data);
		}
		
		$return = $data;
	}
	else
	{
		$data = false;
		
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		switch ( $s_fetch )
		{
			case 0:	$data = $db->sql_fetchrowset($result);	break;				
			case 1:	$data = $db->sql_fetchrow($result);		break;
			case 2:
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$data[$row[$ary['name']]] = unserialize($row[$ary['value']]);
				}
				
				break;
			
			case 3: /*  acp_groups  */
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$data[$row[$field_id]] = ($s_separate) ? $row[$s_separate] : $row;
				}
				
				break;
				
			case 4:	/*	acp_change	*/
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					if ( !$row['main'] )
					{
						$data['main'][] = $row;
					}
					else
					{
						$data['data_id'][$row['main']][] = $row;
					}
				}
				
				break;
				
			case 5: /*  acp_network */
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$data[$row[$s_separate]][] = $row;
				}
				
				break;
		}
		
		$return = $data;
	}
		
	return $return;
}

?>