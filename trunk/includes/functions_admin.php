<?php

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