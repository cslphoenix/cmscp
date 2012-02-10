<?php

function match_round($css, $round, $default)
{
	global $lang;
	
	$select = "<select class=\"$css\" name=\"map_round[$round]\">";
				
	for ( $i = 1; $i < 11; $i++ )
	{
	#	$i = ( $i < 10 ) ? '0' . $i : $i;
		
		$mark	= ( $i == $default ) ? 'selected="selected"' : '';
		$select .= "<option value=\"$i\" $mark>" . sprintf($lang['sprintf_select_format'], sprintf($lang['sprintf_round'], $i)) . "</option>";
	}
	
	$select .= "</select>";
	
	return $select;
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
function data($s_table, $s_where, $s_order, $s_sql, $s_fetch)
{
	global $db, $lang;
	
	switch ( $s_table )
	{
		case AUTHLIST:		$field_id = 'authlist_id';	break;
		case BANLIST:		$field_id = 'ban_id';		break;
		case CASH:			$field_id = 'cash_id';		break;
		case CASH_BANK:		$field_id = '';				break;
		case CONTACT:		$field_id = 'contact_id';	break;
		case DISALLOW:		$field_id = 'disallow_id';	break;
		case DOWNLOAD:		$field_id = 'file_id';		break;
		case DOWNLOAD_CAT:	$field_id = 'cat_id';		break;
		case EVENT:			$field_id = 'event_id';		break;
		case ERROR:			$field_id = 'error_id';		break;
		case FORUM:			$field_id = 'forum_id';		break;		
		case FORUM_CAT:		$field_id = 'cat_id';		break;
		case GALLERY:		$field_id = 'gallery_id';	break;			
		case GALLERY_PIC:	$field_id = 'gallery_id';	break;
		case GAMES:			$field_id = 'game_id';		break;
		case GROUPS:		$field_id = 'group_id';		break;
		case GROUPS_USERS:	$field_id = 'group_user_id';break;
		case LOGS:			$field_id = 'log_id';		break;
		case ERROR:			$field_id = 'error_id';		break;
		case MAPS_CAT:		$field_id = 'cat_id';		break;
		case MATCH:			$field_id = 'match_id';		break;
		case MATCH_MAPS:	$field_id = 'match_id';		break;
		case NAVI:			$field_id = 'navi_id';		break;
		case NETWORK:		$field_id = 'network_id';	break;
		case NEWSCAT:		$field_id = 'cat_id';		break;
		case NEWSLETTER:	$field_id = 'newsletter_id';break;
		case PROFILE:		$field_id = 'profile_id';	break;
		case PROFILE_CAT:	$field_id = 'cat_id';		break;
		case PROFILE_DATA:	$field_id = 'user_id';		break;
		case RANKS:			$field_id = 'rank_id';		break;
	#	case RATE:			$field_id = 'rate_id';		break;	
		case SERVER:		$field_id = 'server_id';	break;
		case SERVER_TYPE:	$field_id = 'type_id';		break;
		case TEAMSPEAK:		$field_id = 'teamspeak_id';	break;	
		case THEMES:		$field_id = 'themes_id';	break;
		case TRAINING:		$field_id = 'training_id';	break;
		case USERS:			$field_id = 'user_id';		break;
		
		case CASH_USER:		$field_id = 'cash_user_id';	$field_link = 'user_id';	$table_link = USERS;	$field_id2 = 'user_id';	break;
		case MAPS:			$field_id = 'map_id';		$field_link = 'cat_id';		$table_link = MAPS_CAT;	$field_id2 = 'cat_id';	break;
		case NEWS:			$field_id = 'news_id';		$field_link = 'news_cat';	$table_link = NEWSCAT;	$field_id2 = 'cat_id';	break;
		case TEAMS:			$field_id = 'team_id';		$field_link = 'team_game';	$table_link = GAMES;	$field_id2 = 'game_id';	break;
			
	#	case BT:			$field_id = 'bg_id';		break;
		
		default:	message(GENERAL_ERROR, 'Error Data Mode');	break;
	}
	
	/* strstr($s_where, 'WHERE') ist vielleicht nützlicher */
	if ( strstr($s_where, 'type') || strstr($s_where, 'cat') || strstr($s_where, 'sub') || strstr($s_where, '-1') || strstr($s_where, 'user_name') || strstr($s_where, 'user_id') || strstr($s_where, 'level') )
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
		case 0:	$sql = "SELECT * FROM $s_table $order";
			break;
		case 1:	$sql = "SELECT * FROM $s_table $where $order";
			break;
		case 2:	$sql = "SELECT  t1.*, t2.*
							FROM $s_table t1, $table_link t2
						WHERE t1.$field_id = $s_where AND t1.$field_link = t2.$field_id2";
			break;
		case 3:	$sql = "SELECT  t1.*, t2.*
							FROM $s_table t1
								LEFT JOIN $table_link t2 ON t1.$field_link = t2.$field_id2
						WHERE t1.$field_id = $s_where";
			break;
			
		default: message(GENERAL_ERROR, 'Wrong mode for data', '', __LINE__, __FILE__);
			break;
	}
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$return = ( $s_fetch ) ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
	
	return $return;
}

/*
 *	Gibt eine Liste wieder, wonach sortiert werden kann
 *
 *	@param: string	$mode		example: games
 *	@param: string	$option		example: game_id != -1
 *	@param: string	$css		example: select
 *	@param:	int		$default	example: 10
 *
 */
function simple_order($mode, $option, $css, $default)
{
	/*
		require:	acp_game,
					acp_group,
					acp_map,
					acp_navi,
					acp_network,
					acp_newscat,
					acp_profile,
					acp_rank
	*/
	global $db, $lang;
	
	$cats = '';
	
	$filter = array(DOWNLOAD_CAT, MAPS_CAT, PROFILE_CAT);
	
	if ( in_array($mode, $filter) )
	{
		$sql = "SELECT * FROM $mode ORDER BY cat_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$field = 'cat';
	}
	
	switch ( $mode )
	{
		/*
		case DOWNLOAD_CAT:
		
			$field = 'cat';
		
			$sql = "SELECT * FROM " . DOWNLOAD_CAT . " ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
		*/
		
		case SERVER:
		
			$field = 'server';
		
			$sql = "SELECT * FROM " . SERVER . " WHERE server_type = $option ORDER BY server_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case GAMES:
		
			$field = 'game';
		
			$sql = "SELECT * FROM " . GAMES . " WHERE $option ORDER BY game_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case GROUPS:
		
			$field = 'group';
		
			$sql = "SELECT * FROM " . GROUPS . " WHERE $option ORDER BY group_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case MAPS:
		
			$field = 'map';
		
			$sql = "SELECT cat_id AS cat_type, cat_name FROM " . MAPS_CAT . " WHERE cat_id = $option ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cats = $db->sql_fetchrowset($result);
			
			$sql = "SELECT map_name, map_order, cat_id AS map_type FROM " . MAPS . " WHERE cat_id = $option ORDER BY cat_id, map_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
		/*	
		case MAPS_CAT:
		
			$field = 'cat';
		
			$sql = "SELECT * FROM " . MAPS_CAT . " ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
		*/	
		case NAVI:
		
			$field = 'navi';
		
			$cats = array(
				'0' => array('cat_type' => NAVI_MAIN,	'cat_name' => $lang['main']),
				'1' => array('cat_type' => NAVI_CLAN,	'cat_name' => $lang['clan']),
				'2' => array('cat_type' => NAVI_COM,	'cat_name' => $lang['com']),
				'3' => array('cat_type' => NAVI_MISC,	'cat_name' => $lang['misc']),
				'4' => array('cat_type' => NAVI_USER,	'cat_name' => $lang['user']),
			);
			
			$sql = "SELECT * FROM " . NAVI . " WHERE navi_type = $option ORDER BY navi_type, navi_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case NETWORK:
		
			$field = 'network';
			
			$cats = array(
				'0' => array('cat_type' => NETWORK_LINK,	'cat_name' => $lang['link']),
				'1' => array('cat_type' => NETWORK_PARTNER,	'cat_name' => $lang['partner']),
				'2' => array('cat_type' => NETWORK_SPONSOR,	'cat_name' => $lang['sponsor']),
			);
			
			$sql = "SELECT * FROM " . NETWORK . " WHERE network_type = $option ORDER BY network_type, network_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case NEWSCAT:
		
			$field = 'cat';
		
			$sql = "SELECT * FROM " . NEWSCAT . " ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case PROFILE:
		
			$field = 'profile';
		
			$sql = "SELECT cat_id AS cat_type, cat_name FROM " . PROFILE_CAT . " WHERE cat_id = $option ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cats = $db->sql_fetchrowset($result);
			
			$sql = "SELECT profile_name, profile_order, profile_cat AS profile_type FROM " . PROFILE . " WHERE profile_cat = $option ORDER BY profile_cat, profile_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
		/*	
		case PROFILE_CAT:
		
			$field = 'cat';
			
			$sql = "SELECT * FROM " . PROFILE_CAT . " ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
		*/
		case RANKS:
		
			$field = 'rank';	
				
			$cats = array(
				'0' => array('cat_type' => RANK_PAGE,	'cat_name' => $lang['page']),
				'1' => array('cat_type' => RANK_FORUM,	'cat_name' => $lang['forum']),
				'2' => array('cat_type' => RANK_TEAM,	'cat_name' => $lang['team']),
			);
			
			$sql = "SELECT * FROM " . RANKS . " WHERE rank_type = $option ORDER BY rank_type, rank_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
	}	
	$entries = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	if ( $entries )	
	{
		$s_select .= "<select class=\"$css\" name=\"" . $field . "_order_new\" id=\"" . $field . "_order\">";
		$s_select .= "<option selected=\"selected\" value=\"$default\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
		
		if ( $cats )
		{
			for ( $i = 0; $i < count($cats); $i++ )
			{
				$entry = '';
				
				$cat_name = $cats[$i]['cat_name'];
				$cat_type = $cats[$i]['cat_type'];
				
				for ( $j = 0; $j < count($entries); $j++ )
				{
					$name = $entries[$j][$field . '_name'];
					$type = $entries[$j][$field . '_type'];
					$order = $entries[$j][$field . '_order'];
					
					if ( $cat_type == $type )
					{
						$disabled = ( $entries[$j][$field . '_order'] == $default ) ? ' disabled="disabled"' : '';
						
						$entry .= ( $order == 10 ) ? "<option value=\"5\"$disabled>" . sprintf($lang['sprintf_select_before'], $name) . "</option>" : '';
						$entry .= "<option value=\"" . ( $order + 5 ) . "\"$disabled>" . sprintf($lang['sprintf_select_order'], $name) . "</option>";
					}
				}
				
				$s_select .= ( $entry != '' ) ? "<optgroup label=\"$cat_name\">$entry</optgroup>" : '';
			}
		}
		else
		{
			for ( $j = 0; $j < count($entries); $j++ )
			{
				$name = $entries[$j][$field . '_name'];
				$order = $entries[$j][$field . '_order'];
				
				$disabled = ( $entries[$j][$field . '_order'] == $default ) ? ' disabled="disabled"' : '';
					
				$s_select .= ( $order == 10 ) ? "<option value=\"5\"$disabled>" . sprintf($lang['sprintf_select_before'], $name) . "</option>" : '';
				$s_select .= "<option value=\"" . ( $order + 5 ) . "\"$disabled>" . sprintf($lang['sprintf_select_order'], $name) . "</option>";
			}
		}
		
		$s_select .= "</select>";
	}
	else
	{
		$s_select = $lang['no_entry'];
	}
	
	return $s_select;
}

/*
 *	Prüft Daten ob Leer und ob sie schon in der DB vorhanden sind!
 *
 *	@param: string	$tbl		example: games
 *	@param: string	$ary		example: Daten zum Überprüfen, mit ID!
 *	@param: string	$err		example: Fehler
 *
 */
function check($tbl, $ary, $err)
{
	global $db, $lang;

	$return = '';
	
	$_id = array_pop($ary);
	
	foreach ( $ary as $key => $val )
	{
		$tmp = explode("_", $key); 
		$end = array_pop($tmp);
		
		if ( $val )
		{
			$sql = "SELECT * FROM $tbl WHERE LOWER($key) LIKE '%" . strtolower($val) . "%'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$entry = $db->sql_fetchrowset($result);
			
			if ( $entry )
			{
				for ( $i = 0; $i < count($entry); $i++ )
				{
					if ( array_shift($entry[$i]) != $_id )
					{
						$return .= ( $return || $err ? '<br />' : '' ) . $lang['msg_available_' . $end];
					}
				}
			}
		}
	#	else if ( !$val )
	#	{
	#		$return .= ( $return || $err ? '<br />' : '' ) . $lang['msg_empty_' . $end];
	#	}
		else
		{
			$return .= isset($val) ? ( $return || $err ? '<br />' : '' ) . $lang['msg_empty_' . $end] : '';
		}
	}

	return $return;
}

function load_lang($file)
{
	global $root_path, $userdata, $lang;
	
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/' . $file . '.php');
}

function maxi($table, $order, $where)
{
	global $db;
	
	$where_to = ( $where ) ? "WHERE $where" : "";
	
	$sql = "SELECT MAX($order) AS $order FROM $table $where_to";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return $return[$order];
}

function maxa($table, $order, $where)
{
	global $db;
	
	$where_to = ( $where ) ? "WHERE $where" : false;
	
	$sql = "SELECT MAX($order) AS $order FROM $table $where_to";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrow($result);
	
	return $return[$order] + 10;
}

function update($table, $index, $move, $index_id)
{
	global $db;
	
	$sql = "UPDATE $table SET " . $index . "_order = " . $index . "_order + $move WHERE " . $index . "_id = $index_id";
	if ( !$db->sql_query($sql) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	return;
}

function orders($mode, $type = '')
{
	global $db;
	
	if ( in_array($mode, array(DOWNLOAD_CAT, NEWSCAT, MAPS_CAT, FORUM_CAT, PROFILE_CAT)) )
	{
		$idfield = 'cat_id';
		$orderfield	= 'cat_order';
	}

	switch ( $mode )
	{
		case GAMES:			$idfield = 'game_id';		$orderfield = 'game_order';		break;
		case SERVER:		$idfield = 'server_id';		$orderfield	= 'server_order';	break;
		case TEAMS:			$idfield = 'team_id';		$orderfield = 'team_order';		break;
		case MATCH_MAPS:	$idfield = 'map_id';		$orderfield = 'map_order';		break;
		case GALLERY:		$idfield = 'gallery_id';	$orderfield = 'gallery_order';	break;
		
		case FORUM:			$idfield = 'forum_id';		$orderfield = 'forum_order';	$typefield = 'cat_id';				break;
		case MAPS:			$idfield = 'map_id';		$orderfield	= 'map_order';		$typefield = 'cat_id';				break;
		case GALLERY_PIC:	$idfield = 'pic_id';		$orderfield = 'pic_order';		$typefield = 'gallery_id';			break;
		case RANKS:			$idfield = 'rank_id';		$orderfield = 'rank_order';		$typefield = 'rank_type';			break;
		case PROFILE;		$idfield = 'profile_id';	$orderfield = 'profile_order';	$typefield = 'profile_cat';			break;
		case NAVI:			$idfield = 'navi_id';		$orderfield = 'navi_order';		$typefield = 'navi_type';			break;
		case GROUPS:		$idfield = 'group_id';		$orderfield	= 'group_order';	$typefield = 'group_single_user';	break;
		case NETWORK:		$idfield = 'network_id';	$orderfield = 'network_order';	$typefield = 'network_type';		break;
		case SERVER:		$idfield = 'server_id';		$orderfield = 'server_order';	$typefield = 'server_type';			break;
		case NAVI:			$idfield = 'navi_id';		$orderfield = 'navi_order';		$typefield = 'navi_type';			break;
		case FORUM:			$idfield = 'forum_id';		$orderfield = 'forum_order';	$typefield = 'cat_id';				break;
	}
	
	$sql = "SELECT $idfield, $orderfield FROM $mode";
	
	if ( $type == '-1' )
	{
		$sql .= " WHERE $idfield != $type";
	}
	else if ( $type != '' )
	{
		$sql .= " WHERE $typefield = $type";
		$sql .= ( $mode == 'ranks' && $type == RANK_FORUM ) ?  ' AND rank_special = 1' : '';
	}
	
	$sql .= " ORDER BY $orderfield ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$i = 10;

	while ( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $mode SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$i += 10;
	}
}

function orders_new($mode, $type, $id)
{
	global $db;

	switch ( $mode )
	{
		case FORUM:
			$idfield	= 'forum_id';
			$orderfield	= 'forum_order';
			$typefield	= 'cat_id';
			break;
	}

	$sql = "SELECT $idfield, $orderfield FROM $mode";
	
	switch ( $type )
	{
		case 'cat':
			$sql .= " WHERE ";
			break;
			
		case 'forum':
			$sql .= " WHERE cat_id = $id";
			break;
			
		case 'sub':
			$sql .= " WHERE forum_sub = $id";
			break;
	}
	
	/*
	if ( $type == '-1' )
	{
		$sql .= " WHERE $idfield != $type";
	}
	else if ( $type != '' )
	{
		$sql .= " WHERE $typefield = $type";
		$sql .= ( $mode == 'ranks' && $type == RANK_FORUM ) ?  ' AND rank_special = 1' : '';
	}
	*/
	$sql .= " ORDER BY $orderfield ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$i = 10;

	while ( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $mode SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$i += 10;
	}
}

function size_dir($path)
{
	global $lang;
	
	$size = 0;

	if ( $dir = @opendir($path) )
	{
		while ( $file = @readdir($dir) )
		{
			if ( $file != '.' && $file != '..' && $file != 'index.htm' && !strstr($file, '_preview') )
			{
				$size += @filesize($path . "/" . $file);
			}
		}
		@closedir($dir);

		//
		// This bit of code translates the avatar directory size into human readable format
		// Borrowed the code from the PHP.net annoted manual, origanally written by:
		// Jesse (jesse@jess.on.ca)
		//
		if ( $size >= 1048576 )
		{
			$size = round($size/1048576) . " MB";
		}
		else if( $size >= 1024 )
		{
			$size = round($size/1024) . " KB";
		}
		else
		{
			$size = $size . " Bytes";
		}
	}
	else
	{
		$size = $lang['msg_unavailable_size_dir'];
	}
	
	return $size;
}

function size_file($file)
{
	$size = 0;
	
	if ( $file >= 1048576 )
	{
		$size = round($file / 1048576 * 100) / 100 . " MB";
	}
	else if( $file >= 1024 )
	{
		$size = round($file / 1024 * 100) / 100 . " KB";
	}
	else
	{
		$size = $size . " Bytes";
	}
	
	return $size;
}

function _size($size, $round)
{
	global $lang;
	
	$return = 0;
	
	if ( $size >= 1073741824 )
	{
		$return = round($size/1073741824, $round) . $lang['size_gb'];
	}
	else if ( $size >= 1048576 )
	{
		$return = round($size/1048576, $round) . $lang['size_mb'];
	}
	else if ( $size >= 1024 )
	{
		$return = round($size/1024, $round) . $lang['size_kb'];
	}
	else
	{
		$return = round($size, $round) . $lang['size_by'];
	}
	
	return $return;
}

/*
 *	Ordner erstellen. match, gallery
 *
 *	@param: string	$path		example: ./../upload/matchs/
 *	@param: string	$name		example: 01022001_
 *	@param: both	$cryp		example: true or false
 *
 */
function create_folder($path, $name, $cryp)
{
	global $lang;
	
	$folder_name = ( $cryp ) ? uniqid($name) : $name;
	$folder_path = $path . $folder_name;		
	
	mkdir("$folder_path", 0755);
	
	$file	= 'index.htm';
	$code	= $lang['empty_site'];
	$create	= fopen("$folder_path/$file", "w");
	
	fwrite($create, $code);
	fclose($create);
	
	return $folder_name;
}

function delete_folder($path)
{
	$dir = opendir($path);
	
	while ( $entry = readdir($dir) )
	{
		if ( $entry == '..' || $entry == '.' )
		{
			continue;
		}
		
		if ( is_dir($path . $entry) )
		{
			delete_folder($path . $entry . "/");
		}
		else
		{
			unlink($path . $entry);
		}
	}
	closedir($dir);
	rmdir($path);
}

function set_http(&$website)
{
	if ( !preg_match('#^http[s]?:\/\/#i', $website) )
	{
		$website = 'http://' . $website;
	}
	
	return $website;
}

function set_chmod($host, $port, $user, $pass, $path, $file, $perms)
{
	global $root_path, $db, $config, $settings, $lang;
	
	$conn = ftp_connect($host, $port, 3);
	
	if (!$conn) die('Verbindung zu ftp.example.com konnte nicht aufgebaut werden');
	
	// Login mit Benutzername und Passwort
	if (!ftp_login($conn, $user, $pass)) die('Fehler beim Login zu ftp.example.com');
	
	// Kommando "SITE CHMOD 0600 /home/user/privatefile" an den Server senden */
	if (ftp_site($conn, 'CHMOD 0' . $perms . ' ' . $path))
	{
		echo "Kommando erfolgreich ausgeführt.\n";
	}
	else
	{
		die('Kommando fehlgeschlagen.');
//		message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
	}
}

/*
 *	ID/Namen abfrage für DropDown Menüs
 *
 *	@param: string	$type	example: GAMES
 *	@param: string	$mode	example: 'name' / id
 *	@param: string	$select	example: 1 / 'cs.png'
 */
function search_image($type, $mode, $select)
{
	global $db;
	
	switch ( $mode )
	{
		case 'name':
		
			switch ( $type )
			{
				case GAMES:
					
					$sql = "SELECT game_image FROM $type WHERE game_id = $select";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
					
				case NEWSCAT:
				
					$sql = "SELECT cat_image FROM $type WHERE cat_id = $select";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
			}
			
			break;
			
		case 'id':
		
			switch ( $type )
			{
				case GAMES:
					
					$sql = "SELECT game_id FROM $type WHERE game_image = '$select'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
					
				case NEWSCAT:
				
					$sql = "SELECT cat_id FROM $type WHERE cat_image = '$select'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
			}
			
			break;
	}
	$tmp = $db->sql_fetchrow($result);
	$key = array_keys($tmp);
	$msg = $tmp[$key[0]];

	return $msg;
}

function get_data_index($table, $field, $where, $order)
{
	global $db;
	
	$field_to = ( $field ) ? "WHERE $field" : '*';
	$where_to = ( $where ) ? "WHERE $where" : '';
	$order_to = ( $order ) ? "ORDER BY $order" : '';
	
	$sql = "SELECT $field FROM $table $where_to $order_to LIMIT 0, 5";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	return $return;
}

function select_map($team, $num, $default = '')
{
	global $db, $lang;
	
	$sql = "SELECT mc.*
				FROM " . MAPS_CAT . " mc
					LEFT JOIN " . TEAMS . " t ON t.team_id = $team
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
			WHERE mc.cat_tag = g.game_tag";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cats = $db->sql_fetchrow($result);
	
	$s_select = '';
		
	if ( $cats )
	{
		$cat_id		= $cats['cat_id'];
		$cat_name	= $cats['cat_name'];
		
		$sql = "SELECT * FROM " . MAPS . " WHERE cat_id = " . $cats['cat_id'] . " ORDER BY map_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$maps = $db->sql_fetchrowset($result);
		
		if ( $maps )
		{
			$s_select .= "<select class=\"select\" name=\"map_name[$num]\" id=\"map_name\">";
			$s_select .= "<optgroup label=\"" . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . "\" >";
			
			$s_maps = '';
				
			for ( $j = 0; $j < count($maps); $j++ )
			{
				$map_id		= $maps[$j]['map_id'];
				$map_cat	= $maps[$j]['cat_id'];
				$map_name	= $maps[$j]['map_name'];
				
				$selected	= ( $map_id == $default ) ? 'selected="selected"' : "";
	
				$s_maps .= ( $cat_id == $map_cat ) ? "<option value=\"$map_id\"$selected>" . sprintf($lang['sprintf_select_format'], $map_name) . "</option>" : '';
			}
			
			$s_select .= ( $s_maps != '' ) ? "<optgroup label=\"$cat_name\">$s_maps</optgroup>" : '';
			$s_select .= "</optgroup></select>";
		}
		else
		{
			$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
		}
	}
	else
	{
		$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
	}
	
	return $s_select;
}

?>