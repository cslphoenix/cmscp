<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

function get_data($mode, $id, $type)
{
	global $db, $lang;
	
	switch ( $mode )
	{
		case AUTHLIST:			$idfield = 'authlist_id';			break;
		case CASH:				$idfield = 'cash_id';				break;
		case CASH_USERS:		$idfield = 'cash_user_id';			break;		
		case EVENT:				$idfield = 'event_id';				break;
		case GALLERY:			$idfield = 'gallery_id';			break;
		case GAMES:				$idfield = 'game_id';				break;
		case GROUPS:			$idfield = 'group_id';				break;
		case MATCH:				$idfield = 'match_id';				break;
		case NAVIGATION;		$idfield = 'navi_id';				break;
		case NETWORK:			$idfield = 'network_id';			break;
		case NEWSCAT:			$idfield = 'newscat_id';			break;
		case NEWSLETTER:		$idfield = 'newsletter_id';			break;
		case PROFILE:			$idfield = 'profile_id';			break;
		case PROFILE_CATEGORY:	$idfield = 'profile_category_id';	break;
		case PROFILE_DATA:		$idfield = 'user_id';				break;
		case RANKS:				$idfield = 'rank_id';				break;			
		case SERVER:			$idfield = 'server_id';				break;
		case THEMES:			$idfield = 'themes_id';				break;
		case TEAMSPEAK:			$idfield = 'teamspeak_id';			break;
		case TRAINING:			$idfield = 'training_id';			break;
		case USERS:				$idfield = 'user_id';				break;
			
		case NEWS:
			$idfield	= 'news_id';
			$connection	= 'news_category';
			$table2		= NEWSCAT;
			$idfield2	= 'newscat_id';
			break;
			
		case TEAMS:
			$idfield	= 'team_id';
			$connection	= 'team_game';
			$table2		= GAMES;
			$idfield2	= 'game_id';
			break;

		default:	message(GENERAL_ERROR, 'Error Data Mode' . $lang['back']);		break;
	}
	
	switch( $type )
	{
		case '0':	$sql = "SELECT * FROM $mode";											break;
		case '1':	$sql = "SELECT * FROM $mode WHERE $idfield = $id";						break;
		case '2':	$sql = "SELECT  t1.*, t2.*
								FROM $mode t1, $table2 t2
								WHERE t1.$idfield = $id
										AND t1.$connection = t2.$idfield2";					break;
		case '3':	$sql = "SELECT  t1.*, t2.*
								FROM $mode t1
									LEFT JOIN $table2 t2 ON t1.$connection = $idfield2
								WHERE $idfield = $id";										break;
		default:	message(GENERAL_ERROR, 'Wrong mode for data', '', __LINE__, __FILE__);	break;
	}
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$return = $db->sql_fetchrow($result);
	
	return $return;
}

function get_data_array($table, $where, $order, $sort)
{
	global $db;
	
	$where_to = ( $where ) ? "WHERE $where" : '';
	$order_to = ( $order ) ? "ORDER BY $order $sort" : '';
	
	$sql = "SELECT * FROM $table $where_to $order_to";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	return $return;
}

function get_data_max($table, $order, $where)
{
	global $db;
	
	$where_to = ( $where ) ? "WHERE $where" : '';
	
	$sql = "SELECT MAX($order) AS max FROM $table $where_to";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return $return;
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

	switch ( $mode )
	{
		case GAMES:			$idfield = 'game_id';		$orderfield = 'game_order';		break;
		case GALLERY:		$idfield = 'gallery_id';	$orderfield = 'gallery_order';	break;
		case NEWSCAT:		$idfield = 'newscat_id';	$orderfield = 'newscat_order';	break;
		case MATCH_MAPS:	$idfield = 'map_id';		$orderfield = 'map_order';		break;
	
		case GROUPS:
			$idfield	= 'group_id';
			$orderfield	= 'group_order';
			$typefield	= 'group_single_user';
		break;
		
		case NETWORK:
			$idfield	= 'network_id';
			$orderfield = 'network_order';
			$typefield	= 'network_type';
			break;
		
		
		
		case 'server':
			$table		= SERVER;
			$idfield	= 'server_id';
			$orderfield = 'server_order';
			$typefield	= 'server_type';
			break;
		
		case 'ranks':
			$table		= RANKS;
			$idfield	= 'rank_id';
			$orderfield = 'rank_order';
			$typefield	= 'rank_type';
			break;
			
		
		
		case 'navi':
			$table		= NAVIGATION;
			$idfield	= 'navi_id';
			$orderfield = 'navi_order';
			$typefield	= 'navi_type';
			break;
			
		case 'category':
			$table = CATEGORIES;
			$idfield = 'cat_id';
			$orderfield = 'cat_order';
			break;

		case 'forum':
			$table = FORUMS;
			$idfield = 'forum_id';
			$orderfield = 'forum_order';
			$typefield = 'cat_id';
			break;
			
		case 'teams':
			$table		= TEAMS;
			$idfield	= 'team_id';
			$orderfield	= 'team_order';
			break;
			
		case 'profile':
			$table		= PROFILE;
			$idfield	= 'profile_id';
			$orderfield	= 'profile_order';
			$typefield	= 'profile_category';
			break;
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

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $mode SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
#		if ( !($result = $db->sql_query($sql)) )
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$i += 10;
	}
}

function size_dir($path)
{
	global $settings, $db, $lang;
	
	$size = 0;

	if ( $dir = @opendir($path) )
	{
		while ( $file = @readdir($dir) )
		{
			if ( $file != '.' && $file != '..' && $file != 'index.htm' )
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
			$size = round($size / 1048576 * 100) / 100 . " MB";
		}
		else if($size >= 1024)
		{
			$size = round($size / 1024 * 100) / 100 . " KB";
		}
		else
		{
			$size = $size . " Bytes";
		}
	}
	else
	{
		$size = $lang['Not_available'];
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
	else if($file >= 1024)
	{
		$size = round($file / 1024 * 100) / 100 . " KB";
	}
	else
	{
		$size = $size . " Bytes";
	}
	
	return $size;
}

function dir_remove($path)
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
			dir_remove($path . $entry . "/");
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


?>