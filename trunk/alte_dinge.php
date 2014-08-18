<?php

function _cached($sql, $sCacheName, $rows = '', $time = '')
{
	/*
		@param string $sql		enthÃ¯Â¿Â½lt die SQL Abfrage
		@param string $name		enthÃ¯Â¿Â½lt den Namen der Cachdatei
		@param int $row			sql_fetchrow/sql_fetchrowset
		@param int $time		Lebensdauer der Cachdatei
		@return string
	*/
	
	global $db, $oCache;
	
	if ( defined('CACHE') )
	{
		if ( ( $data = $oCache->readCache($sCacheName) ) === false )
		{
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$data = ( $rows == '1' ) ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
			$time = ( $time != '' ) ? $oCache->writeCache($sCacheName, $data, (int) $time) : $oCache->writeCache($sCacheName, $data);
			
			$db->sql_freeresult($result);
		}
	}
	else
	{
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$data = $rows ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
	}
	
	return $data;
}

function cached($type, $data, $name, $ary = '', $time = '')
{
	/*
		@param string $type		enthÃ¯Â¿Â½lt die Type (SQL, Daten)
		@param string $data		enthÃ¯Â¿Â½lt die SQL Abfrage oder Daten die gespeichert werden sollen
		@param string $name		enthÃ¯Â¿Â½lt den Namen der Cachdatei
		@param int $ary			sql_fetchrow/sql_fetchrowset
		@param int $time		Lebensdauer der Cachdatei
	
		@return SQL Daten oder Daten die gespeichert worden
	*/
	
	global $db, $oCache;
	
	if ( defined('CACHE') )
	{
		switch ( $type )
		{
			case 'sql':
			
				if ( ( $tmp = $oCache->readCache($name) ) === false )
				{
					if ( !($result = $db->sql_query($data)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $data);
					}
					
					$tmp = ( $ary == '1' ) ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
					
					( $time != '' ) ? $oCache->writeCache($name, $tmp, (int) $time) : $oCache->writeCache($name, $tmp);
					
					$db->sql_freeresult($result);
				}
				
				break;
		
			case 'ary':
			
				if ( ( $tmp = $oCache->readCache($name) ) === false )
				{
					$tmp = $data;
						
					( $time != '' ) ? $oCache->writeCache($name, $tmp, (int) $time) : $oCache->writeCache($name, $tmp);
				}
				
				break;
		}
	}
	else
	{
		switch ( $type )
		{
			case 'sql':
			
				if ( !($result = $db->sql_query($data)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$tmp = ( $ary == '1' ) ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
			
				break;
		
			case 'ary':
			
				$tmp = $data;

				break;
		}
	}
	
	return $tmp;
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
function data($s_table, $s_where, $s_order, $s_sql, $s_fetch, $s_cache = false)
{
	global $db, $lang;
	
	switch ( $s_table )
	{
		case CASH_USER:		$field_id = 'cash_user_id';	$field_link = 'user_id';	$table_link = USERS;	$field_id2 = 'user_id';	break;
		case NEWS:			$field_id = 'news_id';		$field_link = 'news_cat';	$table_link = NEWS_CAT;	$field_id2 = 'cat_id';	break;
		case TEAMS:			$field_id = 'team_id';		$field_link = 'team_game';	$table_link = GAMES;	$field_id2 = 'game_id';	break;

		/* only match for index? */
		case MATCH:			( $s_sql == 4 ) ? $tmp_ary = array('s_table1' => MATCH, 'field_id1' => 'match_id', 'field_link1' => 'team_id', 's_table2' => TEAMS, 'field_id2' => 'team_id', 'field_link2' => 'team_game', 's_table3' => GAMES, 'field_id3' => 'game_id') : $field_id = 'match_id'; $field_link = 'team_id'; break;
		case MATCH_TYPE:	$ary = array('s_table1' => MATCH_TYPE, 'name' => 'type_name', 'value' => 'type_value'); break;
		case SETTINGS:		$ary = array('s_table1' => SETTINGS, 'name' => 'settings_name', 'value' => 'settings_value'); break;

	#	default: message(GENERAL_ERROR, 'Error Data Mode: ' . $s_table);	break;
	}
	
	if ( !isset($field_id) && $s_table )
	{
		$sql = 'SHOW FIELDS FROM ' . $s_table;
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

#	$ary = array('type', 'cat', 'sub', '-1', 'user_name', 'user_id', 'level', 'live');
#	if ( strstr($s_where, in_array($s_where, $ary)) )
	/* strstr($s_where, 'WHERE') ist vielleicht nützlicher */
	if ( is_array($s_where) )
	{
		$where = "WHERE {$field_id[0]} = {$s_where[0]} AND {$field_id[1]} = {$s_where[1]}";
	}
	else if (
		strstr($s_where, 'menu_class') ||
		strstr($s_where, '-1') ||
		strstr($s_where, 'cat') ||
		strstr($s_where, 'sub') ||
		strstr($s_where, 'live') ||
		strstr($s_where, 'type') ||
		strstr($s_where, 'level') ||
		strstr($s_where, 'in_send') ||
		strstr($s_where, 'user_id') ||
		strstr($s_where, 'user_name') ||
		strstr($s_where, 'match_id')
		)
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
		case 3: $sql = "SELECT t1.*, t2.* FROM $s_table t1 LEFT JOIN $table_link t2 ON t1.$field_link = t2.$field_id2 WHERE t1.$field_id = $s_where"; break;
		/* match only ? */
		case 4: $sql = "SELECT m.*, t.*, g.* FROM {$tmp_ary['s_table1']} m LEFT JOIN {$tmp_ary['s_table2']} t ON m.{$tmp_ary['field_link1']} = t.{$tmp_ary['field_id2']} LEFT JOIN {$tmp_ary['s_table3']} g ON t.{$tmp_ary['field_link2']} = g.{$tmp_ary['field_id3']} $where $order"; break;
		case 5: $sql = "SELECT * FROM {$ary['s_table1']} $where"; break;
		default: message(GENERAL_ERROR, 'Wrong mode for data', '', __LINE__, __FILE__); break;
	}
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $s_fetch == '1' )
	{
		$return = $db->sql_fetchrow($result);
	}
	else if ( $s_fetch == '2' )
	{
		while ( $row = $db->sql_fetchrow($result) )
		{
			$return[$row[$ary['name']]] = unserialize($row[$ary['value']]);
		}
	}
	else
	{
		$return = $db->sql_fetchrowset($result);
	}
		
	return $return;
}

?>