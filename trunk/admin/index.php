<?php

define('IN_CMS', true);

$root_path	= './../';
$current	= '_submenu_index';

include('./pagestart.php');
include($root_path . 'includes/acp/acp_functions.php');

$template->set_filenames(array('body' => 'style/acp_index.tpl'));

	$data_match	= get_data_index(MATCH, 'match_id, match_rival, match_public, match_date', '', 'match_date DESC');
	$data_train	= get_data_index(TRAINING, 'training_id, training_vs, training_date', '', 'training_date DESC');
	$data_event	= get_data_index(EVENT, 'event_id, event_title, event_date', '', 'event_date DESC');
	$data_users	= get_data_index(USERS, 'user_id, username, user_regdate, user_level, user_lastvisit', '', 'user_regdate DESC');
	
	if ( $data_match )
	{
		for ( $i = 0; $i < count($data_match); $i++ )
		{
			$match_id	= $data_match[$i]['match_id'];
			$match_typ	= ( $data_match[$i]['match_public'] ) ? 'sprintf_match_name' : 'sprintf_match_intern';
			$match_date	= create_date($userdata['user_dateformat'], $data_match[$i]['match_date'], $userdata['user_timezone']);
			
			if ( $userauth['auth_match'] || $userdata['user_level'] == ADMIN )
			{
				$link_update = '<a href="' . append_sid('admin_match.php?mode=_update&amp;' . POST_MATCH_URL . '=' . $match_id) . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>';
				$link_delete = '<a href="' . append_sid('admin_match.php?mode=_delete&amp;' . POST_MATCH_URL . '=' . $match_id) . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>';
				$link_details = '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_id) . '"><img src="' . $images['icon_option_details'] . '" title="' . $lang['common_details'] . '" alt="" /></a>';
			}
			else
			{
				$link_update = '';
				$link_delete = '';
				$link_details = '';
			}
				
			$template->assign_block_vars('row_match', array(
				'MATCH_RIVAL'	=> sprintf($lang[$match_typ], $data_match[$i]['match_rival']),
				'MATCH_DATE'	=> $match_date,
				'MATCH_UPDATE'	=> $link_update,
				'MATCH_DELETE'	=> $link_delete,
				'MATCH_DETAILS'	=> $link_details,
			));
		}
	}
	else
	{
		$template->assign_block_vars('no_entry_match', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_train )
	{
		for ( $i = 0; $i < count($data_train); $i++ )
		{
			$training_id	= $data_train[$i]['training_id'];
			$training_date	= create_date($userdata['user_dateformat'], $data_train[$i]['training_date'], $userdata['user_timezone']);
			
			if ( $userauth['auth_training'] || $userdata['user_level'] == ADMIN )
			{
				$link_update = '<a href="' . append_sid('admin_training.php?mode=_update&amp;' . POST_TRAINING_URL . '=' . $training_id) . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>';
				$link_delete = '<a href="' . append_sid('admin_training.php?mode=_delete&amp;' . POST_TRAINING_URL . '=' . $training_id) . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>';
			}
			else
			{
				$link_update = '';
				$link_delete = '';
			}
				
			$template->assign_block_vars('row_training', array(
				'TRAINING_VS'		=> $data_train[$i]['training_vs'],
				'TRAINING_DATE'		=> $training_date,
				'TRAINING_UPDATE'	=> $link_update,
				'TRAINING_DELETE'	=> $link_delete,
			));
		}
	}
	else
	{
		$template->assign_block_vars('no_entry_training', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_event )
	{
		for ( $i = 0; $i < count($data_event); $i++ )
		{
			$event_id	= $data_event[$i]['event_id'];
			$event_date	= create_date($userdata['user_dateformat'], $data_event[$i]['event_date'], $config['page_timezone']);
			
			if ( $userauth['auth_event'] || $userdata['user_level'] == ADMIN )
			{
				$link_update = '<a href="' . append_sid('admin_event.php?mode=_update&amp;' . POST_EVENT_URL . '=' . $event_id) . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>';
				$link_delete = '<a href="' . append_sid('admin_event.php?mode=_delete&amp;' . POST_EVENT_URL . '=' . $event_id) . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>';
			}
			else
			{
				$link_update = '';
				$link_delete = '';
			}
				
			$template->assign_block_vars('row_event', array(
				'EVENT_TITLE'	=> $data_event[$i]['event_title'],
				'EVENT_DATE'		=> $event_date,
				'EVENT_UPDATE'	=> $link_update,
				'EVENT_DELETE'	=> $link_delete,
			));
		}
	}
	else
	{
		$template->assign_block_vars('no_entry_event', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_users )
	{
		for ( $i = 0; $i < count($data_users); $i++ )
		{
			$user_id		= $data_users[$i]['user_id'];
			$user_regdate	= create_date($userdata['user_dateformat'], $data_users[$i]['user_regdate'], $config['page_timezone']);
			
			if ( $userauth['auth_user'] || $userdata['user_level'] == ADMIN )
			{
				$link_update = '<a href="' . append_sid('admin_user.php?mode=_update&amp;' . POST_USERS_URL . '=' . $user_id) . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>';
				$link_delete = '<a href="' . append_sid('admin_user.php?mode=_delete&amp;' . POST_USERS_URL . '=' . $user_id) . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>';
			}
			else
			{
				$link_update = '';
				$link_delete = '';
			}
				
			$template->assign_block_vars('row_user', array(
				'USER_NAME'		=> $data_users[$i]['username'],
				'USER_REGDATE'	=> $user_regdate,
				'USER_UPDATE'	=> $link_update,
				'USER_DELETE'	=> $link_delete,
			));
		}
	}
	else
	{
		$template->assign_block_vars('no_entry_user', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}


$template->assign_vars(array(
							 
	'L_MATCH'				=> $lang['match'],
	'L_EVENT'				=> $lang['event'],
	'L_USERS'				=> $lang['users'],
	
	'I_MATCH'				=> $images['match'],
	'I_EVENT'				=> $images['event'],
	'I_USERS'				=> $images['user'],
	
	'U_MATCH'				=> append_sid('admin_match.php'),
	'U_EVENT'				=> append_sid('admin_event.php'),
	'U_USERS'				=> append_sid('admin_user.php'),
	
	
							 
#	'L_CAL'					=> $lang['head_calendar'],
#	'L_USER'				=> $lang['head_user'],
	
#	'L_AUTH'				=> $lang['common_auth'],
#	'L_DELETE'				=> $lang['common_delete'],
#	'L_UPDATE'				=> $lang['common_update'],
	
#	'ICON_CAL'				=> $images['calendar'],
#	'ICON_CAL_CREATE'		=> $images['calendar_add'],
#	'ICON_CAL_UPDATE'		=> $images['calendar_edit'],
#	'ICON_CAL_DELETE'		=> $images['calendar_delete'],
	
#	'ICON_USER'				=> $images['user'],
#	'ICON_USER_GO'			=> $images['user_go'],
#	'ICON_USER_CREATE'		=> $images['user_add'],
#	'ICON_USER_UPDATE'		=> $images['user_edit'],
#	'ICON_USER_DELETE'		=> $images['user_delete'],
								 
	
		
		'L_WELCOME'			=> $lang['welcome_cms'],
		'L_ADMIN_INTRO'		=> $lang['welcome_cms_explain'],
		
		
		"L_FORUM_STATS" => $lang['Forum_stats'],
		"L_WHO_IS_ONLINE" => $lang['Who_is_Online'],
		"L_USERNAME" => $lang['Username'],
		"L_LOCATION" => $lang['Location'],
		"L_LAST_UPDATE" => $lang['Last_updated'],
		"L_IP_ADDRESS" => $lang['IP_Address'],
		"L_STATISTIC" => $lang['Statistic'],
		"L_VALUE" => $lang['Value'],
		"L_NUMBER_POSTS" => $lang['Number_posts'],
		"L_POSTS_PER_DAY" => $lang['Posts_per_day'],
		"L_NUMBER_TOPICS" => $lang['Number_topics'],
		"L_TOPICS_PER_DAY" => $lang['Topics_per_day'],
		"L_NUMBER_USERS" => $lang['Number_users'],
		"L_USERS_PER_DAY" => $lang['Users_per_day'],
		"L_BOARD_STARTED" => $lang['Board_started'],
		"L_AVATAR_DIR_SIZE" => $lang['Avatar_dir_size'],
		"L_DB_SIZE" => $lang['Database_size'], 
		"L_FORUM_LOCATION" => $lang['Forum_Location'],
		"L_STARTED" => $lang['Login'],
		"L_GZIP_COMPRESSION" => $lang['Gzip_compression'])
	);

	//
	// Get forum statistics
	//

	$start_date = create_date($config['default_dateformat'], $config['page_startdate'], $config['page_timezone']);

	$boarddays = ( time() - $config['page_startdate'] ) / 86400;

	$avatar_dir_size = 0;

	if ($avatar_dir = @opendir($root_path . $config['avatar_path']))
	{
		while( $file = @readdir($avatar_dir) )
		{
			if( $file != "." && $file != ".." )
			{
				$avatar_dir_size += @filesize($root_path . $config['avatar_path'] . "/" . $file);
			}
		}
		@closedir($avatar_dir);

		//
		// This bit of code translates the avatar directory size into human readable format
		// Borrowed the code from the PHP.net annoted manual, origanally written by:
		// Jesse (jesse@jess.on.ca)
		//
		if($avatar_dir_size >= 1048576)
		{
			$avatar_dir_size = round($avatar_dir_size / 1048576 * 100) / 100 . " MB";
		}
		else if($avatar_dir_size >= 1024)
		{
			$avatar_dir_size = round($avatar_dir_size / 1024 * 100) / 100 . " KB";
		}
		else
		{
			$avatar_dir_size = $avatar_dir_size . " Bytes";
		}
	}
	else
	{
		// Couldn't open Avatar dir.
		$avatar_dir_size = $lang['Not_available'];
	}

	//
	// DB size ... MySQL only
	//
	// This code is heavily influenced by a similar routine
	// in phpMyAdmin 2.2.0
	//
	if( preg_match("/^mysql/", SQL_LAYER) )
	{
		$sql = "SELECT VERSION() AS mysql_version";
		if($result = $db->sql_query($sql))
		{
			$row = $db->sql_fetchrow($result);
			$version = $row['mysql_version'];

			if( preg_match("/^(3\.23|4\.|5\.)/", $version) )
			{
				$db_name = ( preg_match("/^(3\.23\.[6-9])|(3\.23\.[1-9][1-9])|(4\.)|(5\.)/", $version) ) ? "$db_name" : $db_name;

				$sql = "SHOW TABLE STATUS 
					FROM " . $db_name;
				if($result = $db->sql_query($sql))
				{
					$tabledata_ary = $db->sql_fetchrowset($result);
					
					$dbsize = 0;
					for($i = 0; $i < count($tabledata_ary); $i++)
					{
						if( $tabledata_ary[$i]['Engine'] != "MRG_MyISAM" )
						{
							if( $db_prefix != "" )
							{
								if( strstr($tabledata_ary[$i]['Name'], $db_prefix) )
								{
									$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
								}
							}
							else
							{
								$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
							}
						}
					}
				} // Else we couldn't get the table status.
			}
			else
			{
				$dbsize = $lang['Not_available'];
			}
		}
		else
		{
			$dbsize = $lang['Not_available'];
		}
	}
	else if( preg_match("/^mssql/", SQL_LAYER) )
	{
		$sql = "SELECT ((SUM(size) * 8.0) * 1024.0) as dbsize 
			FROM sysfiles"; 
		if( $result = $db->sql_query($sql) )
		{
			$dbsize = ( $row = $db->sql_fetchrow($result) ) ? intval($row['dbsize']) : $lang['Not_available'];
		}
		else
		{
			$dbsize = $lang['Not_available'];
		}
	}
	else
	{
		$dbsize = $lang['Not_available'];
	}

	if ( is_integer($dbsize) )
	{
		if( $dbsize >= 1048576 )
		{
			$dbsize = sprintf("%.2f MB", ( $dbsize / 1048576 ));
		}
		else if( $dbsize >= 1024 )
		{
			$dbsize = sprintf("%.2f KB", ( $dbsize / 1024 ));
		}
		else
		{
			$dbsize = sprintf("%.2f Bytes", $dbsize);
		}
	}
	
	$bytes = array_sum(array_map('filesize', glob('*')));
	
	$template->assign_vars(array(
		"AVATAR_DIR_SIZE" => $avatar_dir_size,
//		"DB_SIZE" => $dbsize, 
		"GZIP_COMPRESSION" => ( $config['gzip_compress'] ) ? $lang['ON'] : $lang['OFF'])
	);
	//
	// End forum statistics
	//

	/*
	// Check for new version
	$current_version = explode('.', '2' . $config['version']);
	$minor_revision = (int) $current_version[2];

	$errno = 0;
	$errstr = $version_info = '';

	if ($fsock = @fsockopen('www.phpbb.com', 80, $errno, $errstr, 10))
	{
		@fputs($fsock, "GET /updatecheck/20x.txt HTTP/1.1\r\n");
		@fputs($fsock, "HOST: www.phpbb.com\r\n");
		@fputs($fsock, "Connection: close\r\n\r\n");

		$get_info = false;
		while (!@feof($fsock))
		{
			if ($get_info)
			{
				$version_info .= @fread($fsock, 1024);
			}
			else
			{
				if (@fgets($fsock, 1024) == "\r\n")
				{
					$get_info = true;
				}
			}
		}
		@fclose($fsock);

		$version_info = explode("\n", $version_info);
		$latest_head_revision = (int) $version_info[0];
		$latest_minor_revision = (int) $version_info[2];
		$latest_version = (int) $version_info[0] . '.' . (int) $version_info[1] . '.' . (int) $version_info[2];

		if ($latest_head_revision == 2 && $minor_revision == $latest_minor_revision)
		{
			$version_info = '<p style="color:green">' . $lang['Version_up_to_date'] . '</p>';
		}
		else
		{
			$version_info = '<p style="color:red">' . $lang['Version_not_up_to_date'];
			$version_info .= '<br>' . sprintf($lang['Latest_version_info'], $latest_version) . ' ' . sprintf($lang['Current_version_info'], '2' . $config['version']) . '</p>';
		}
	}
	else
	{
		if ($errstr)
		{
			$version_info = '<p style="color:red">' . sprintf($lang['Connect_socket_error'], $errstr) . '</p>';
		}
		else
		{
			$version_info = '<p>' . $lang['Socket_functions_disabled'] . '</p>';
		}
	}
	
	$version_info .= '<p>' . $lang['Mailing_list_subscribe_reminder'] . '</p>';
	

	$template->assign_vars(array(
		'VERSION_INFO'	=> $version_info,
		'L_VERSION_INFORMATION'	=> $lang['Version_information'])
	);
	*/
	
$template->pparse('body');

include('./page_footer_admin.php');

?>