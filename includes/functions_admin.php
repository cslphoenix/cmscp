<?php

function get_data($mode, $id, $type)
{
	global $db;

	switch($mode)
	{
		case 'teams':
			$table		= TEAMS_TABLE;
			$idfield	= 'team_id';
		break;
		
		case 'server':
			$table		= SERVER_TABLE;
			$idfield	= 'server_id';
		break;
		
		case 'groups':
			$table		= GROUPS_TABLE;
			$idfield	= 'group_id';
			$connection	= 'group_id';
			$table2		= GROUPS_AUTH_TABLE;
			$idfield2	= 'group_id';
		break;
		
		case 'games':
			$table		= GAMES_TABLE;
			$idfield	= 'game_id';
			
		break;
		
		case 'news':
			$table		= NEWS_TABLE;
			$idfield	= 'news_id';
		break;
		
		case 'newscat':
			$table		= NEWS_CATEGORY_TABLE;
			$idfield	= 'news_category_id';
		break;
		
		case 'server':
			$table		= SERVER_TABLE;
			$idfield	= 'server_id';
			break;
		
		case 'ranks':
			$table		= RANKS_TABLE;
			$idfield	= 'rank_id';
			break;
		
		case 'navi':
			$table		= NAVIGATION_TABLE;
			$idfield	= 'navi_id';
			break;
			
		case 'user':
			$table		= USERS_TABLE;
			$idfield	= 'user_id';
			break;
			
		case 'news_newscat':
			$table		= NEWS_TABLE;
			$idfield	= 'news_id';
			$connection	= 'news_category';
			$table2		= NEWS_CATEGORY_TABLE;
			$idfield2	= 'news_category_id';
		break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for data", "", __LINE__, __FILE__);
			break;
	}
	
	switch($type)
	{
		case '0':
			$sql = "SELECT * FROM $table WHERE $idfield = $id"; 
		break;
		
		case '1':
			$sql = "SELECT  t1.*, t2.*
						FROM $table t1, $table2 t2
						WHERE t1.$idfield = $id
							AND t1.$connection = t2.$idfield2";
		break;
		
		case '2':
			$sql = "SELECT  t1.*, t2.*
						FROM $table t1
							LEFT JOIN $table2 t2 ON t1.$connection = $idfield2
						WHERE $idfield = $id";
		break;
		
		default:
			message_die(GENERAL_ERROR, "Wrong mode for data", "", __LINE__, __FILE__);
			break;
	}
	
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$return = $db->sql_fetchrow($result);
	
	return $return;
}
	
function renumber_order($mode, $type = '')
{
	global $db;

	switch($mode)
	{
		case 'teams':
			$table		= TEAMS_TABLE;
			$idfield	= 'team_id';
			$orderfield	= 'team_order';
		break;
		
		case 'server':
			$table		= SERVER_TABLE;
			$idfield	= 'server_id';
			$orderfield	= 'server_order';
		break;
		
		case 'games':
			$table		= GAMES_TABLE;
			$idfield	= 'game_id';
			$orderfield	= 'game_order';
		break;
		
		case 'newscat':
			$table		= NEWS_CATEGORY_TABLE;
			$idfield	= 'news_category_id';
			$orderfield	= 'news_category_order';
		break;
		
		case 'server':
			$table		= SERVER_TABLE;
			$idfield	= 'server_id';
			$orderfield = 'server_order';
			$typefield	= 'server_type';
			break;
		
		case 'ranks':
			$table		= RANKS_TABLE;
			$idfield	= 'rank_id';
			$orderfield = 'rank_order';
			$typefield	= 'rank_type';
			break;
		
		case 'navi':
			$table		= NAVIGATION_TABLE;
			$idfield	= 'navi_id';
			$orderfield = 'navi_order';
			$typefield	= 'navi_type';
			break;
			
		case 'category':
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			$orderfield = 'cat_order';
			break;

		case 'forum':
			$table = FORUMS_TABLE;
			$idfield = 'forum_id';
			$orderfield = 'forum_order';
			$typefield = 'cat_id';
			break;
	}

	$sql = "SELECT * FROM $table";
	if ( $type == '-1' )
	{
		$sql .= " WHERE $idfield != $type";
	}
	else if ( $type != '' )
	{
		$sql .= " WHERE $typefield = $type";
	}
	$sql .= " ORDER BY $orderfield ASC";
	$result = $db->sql_query($sql);

	$i = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $table SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
		$db->sql_query($sql);
		
		$i += 10;
	}
}

?>