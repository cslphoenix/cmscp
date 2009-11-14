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

function deltree($dir) {

  $fh = opendir($dir);
  while($entry = readdir($fh)) {
    if($entry == ".." || $entry == ".")
      continue;
    if(is_dir($dir . $entry))
      deltree($dir . $entry . "/");
    else
      unlink($dir . $entry);
  }
  closedir($fh);
  rmdir($dir);

}


function set_http(&$website)
{
	if (!preg_match('#^http[s]?:\/\/#i', $website))
	{
		$website = 'http://' . $website;
	}
}
			
function get_data($mode, $id, $type)
{
	global $db;

	switch ( $mode )
	{
		case 'authlist':
			$table		= AUTHLIST;
			$idfield	= 'auth_id';
		break;
		
		case 'gallery':
			$table		= GALLERY;
			$idfield	= 'gallery_id';
		break;
		
		case 'cash':
			$table		= CASH;
			$idfield	= 'cash_id';
		break;
		
		case 'cash_user':
			$table		= CASH_USERS;
			$idfield	= 'cash_user_id';
		break;
		
		case 'event':
			$table		= EVENT;
			$idfield	= 'event_id';
		break;
		
		case 'network':
			$table		= NETWORK;
			$idfield	= 'network_id';
		break;
		
		case 'teams':
			$table		= TEAMS;
			$idfield	= 'team_id';
		break;
		
		case 'server':
			$table		= SERVER;
			$idfield	= 'server_id';
		break;
		
		case 'groups':
			$table		= GROUPS;
			$idfield	= 'group_id';
		break;
		
		case 'games':
			$table		= GAMES;
			$idfield	= 'game_id';
			
		break;
		
		case 'news':
			$table		= NEWS;
			$idfield	= 'news_id';
		break;
		
		case 'newscat':
			$table		= NEWS_CATEGORY;
			$idfield	= 'news_category_id';
		break;
		
		case 'newsletter':
			$table		= NEWSLETTER;
			$idfield	= 'newsletter_id';
		break;
		
		case 'server':
			$table		= SERVER;
			$idfield	= 'server_id';
			break;
		
		case 'ranks':
			$table		= RANKS;
			$idfield	= 'rank_id';
			break;
		
		case 'navi':
			$table		= NAVIGATION;
			$idfield	= 'navi_id';
			break;
			
		case 'user':
			$table		= USERS;
			$idfield	= 'user_id';
			break;
			
		case 'teamspeak':
			$table		= TEAMSPEAK;
			$idfield	= 'teamspeak_id';
			break;
			
		case 'match':
			$table		= MATCH;
			$idfield	= 'match_id';
			break;
		
		case 'news_newscat':
			$table		= NEWS;
			$idfield	= 'news_id';
			$connection	= 'news_category';
			$table2		= NEWS_CATEGORY;
			$idfield2	= 'news_category_id';
		break;
		
		case 'profile':
			$table		= PROFILE;
			$idfield	= 'profile_id';
			break;
			
		case 'profile_category':
			$table		= PROFILE_CATEGORY;
			$idfield	= 'profile_category_id';
			break;
			
		case 'profile_data':
			$table		= PROFILE_DATA;
			$idfield	= 'user_id';
			break;
			
		case 'training':
			$table		= TRAINING;
			$idfield	= 'training_id';
			break;

		default:
			message_die(GENERAL_ERROR, 'Error Data Mode', '', __LINE__, __FILE__);
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
		
		case '3':
			$sql = "SELECT * FROM $table ORDER BY $idfield DESC";
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

function get_data_array($table, $string)
{
	global $db;
	
	$sql = "SELECT * FROM $table ORDER BY $string DESC";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrowset($result);
	
	return $return;
}
	
function renumber_order($mode, $type = '')
{
	global $db;

	switch ( $mode )
	{
		case 'teams':
			$table		= TEAMS;
			$idfield	= 'team_id';
			$orderfield	= 'team_order';
		break;
		
		case 'server':
			$table		= SERVER;
			$idfield	= 'server_id';
			$orderfield	= 'server_order';
		break;
		
		case 'games':
			$table		= GAMES;
			$idfield	= 'game_id';
			$orderfield	= 'game_order';
		break;
		
		case 'groups':
			$table		= GROUPS;
			$idfield	= 'group_id';
			$orderfield	= 'group_order';
			$typefield	= 'group_single_user';
		break;
		
		case 'newscat':
			$table		= NEWS_CATEGORY;
			$idfield	= 'news_category_id';
			$orderfield	= 'news_category_order';
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
			
		case 'network':
			$table		= NETWORK;
			$idfield	= 'network_id';
			$orderfield = 'network_order';
			$typefield	= 'network_type';
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
	}

	$sql = "SELECT * FROM $table";
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
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$i = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $table SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$i += 10;
	}
}

function size_dir($path)
{
	global $sttings, $db, $lang;
	
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
		if($size >= 1048576)
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
		// Couldn't open Avatar dir.
		$size = $lang['Not_available'];
	}
	
	return $size;
}

?>