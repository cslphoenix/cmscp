<?php

define('IN_CMS', true);

$current = 'sm_index';

include('./pagestart.php');

$num	= request('num', 0);
$mode	= request('mode', 1);

$template->set_filenames(array('body' => 'style/acp_index.tpl'));

if ( $mode )
{
	switch ( $mode )
	{
		case '_switch':
		
			$data = data(NEWS, $num, false, 1, true);
			
			$switch = ( $data['news_public'] ) ? 0 : 1;
			
			sql(NEWS, 'update', array('news_public' => $switch), 'news_id', $num);
			
			$oCache->deleteCache('dsp_news');
			
			log_add(LOG_ADMIN, SECTION_NEWS, $mode);
			
			$index = true;
						
			break;
			
		default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
	}
	
	if ( $index != true )
	{
		include('./page_footer_admin.php');
		exit;
	}
}
	
	$url_news	= POST_NEWS;
	$url_event	= POST_EVENT;
	$url_match	= POST_MATCH;
	$url_train	= POST_TRAINING;
	
	$news = data(NEWS, false, 'news_time_public DESC, news_id DESC', 1, false);
	$count = ( count($news) < 5 ) ? count($news) : 5;
	
	if ( !$news )
	{
		$template->assign_block_vars('_entry_empty_news', array());
	}
	else
	{
		for ( $i = 0; $i < $count; $i++ )
		{
			$news_id		= $news[$i]['news_id'];
			$news_typ		= ( $news[$i]['news_intern'] ) ? 'sprintf_intern' : 'sprintf_normal';
			$news_date		= create_date($userdata['user_dateformat'], $news[$i]['news_time_public'], $userdata['user_timezone']);
			$news_public	= ( $news[$i]['news_public'] ) ? '<img src="' . $images['icon_acp_public'] . '" alt="" />' : '<img src="' . $images['icon_acp_privat'] . '" alt="" />';
			
			$link_update = ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? "<a href=\"" . check_sid("admin_news.php?mode=_update&amp;$url_news=$news_id") . "\"><img src=\"" . $images['icon_option_update'] . "\" title=\"" . $lang['common_update'] . "\" alt=\"\" /></a>" : '';
			$link_delete = ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? "<a href=\"" . check_sid("admin_news.php?mode=_delete&amp;$url_news=$news_id") . "\"><img src=\"" . $images['icon_option_delete'] . "\" title=\"" . $lang['common_delete'] . "\" alt=\"\" /></a>" : '';
			$link_public = ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] ) ? "<a href=\"" . check_sid("index.php?mode=_switch&amp;num=$news_id") . "\">$news_public</a>" : "<img src=\"" . $images['icon_acp_denied'] . "\" alt=\"\" />";
	
			$template->assign_block_vars('_news_row', array(
				'TITLE'	=> sprintf($lang[$news_typ], $news[$i]['news_title']),
				'DATE'		=> $news_date,
				'PUBLIC'	=> $link_public,
				'UPDATE'	=> $link_update,
				'DELETE'	=> $link_delete,
			));
		}
	}
	
	$event = data(EVENT, "WHERE event_date > " . time(), 'event_date ASC', 1, false);
	$count = ( count($event) < 5 ) ? count($event) : 5;
	
	if ( !$event )
	{
		$template->assign_block_vars('_entry_empty_event', array());
	}
	else
	{
		for ( $i = 0; $i < $count; $i++ )
		{
			$event_id	= $event[$i]['event_id'];
			$event_date	= create_date($userdata['user_dateformat'], $event[$i]['event_date'], $config['page_timezone']);
			
			if ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] )
			{
				$link_update = '<a href="' . check_sid("admin_event.php?mode=_update&amp;$url_event=$event_id") . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>';
				$link_delete = '<a href="' . check_sid("admin_event.php?mode=_delete&amp;$url_event=$event_id&amp;acp_main=1") . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>';
			}
			else
			{
				$link_update = '';
				$link_delete = '';
			}
			
			switch ( $event[$i]['event_level'] )
			{
				case '0': $level = $images['lvl_guest'];	$level_exp = $lang['auth_guest'];	break;
				case '1': $level = $images['lvl_user'];		$level_exp = $lang['auth_user'];	break;
				case '2': $level = $images['lvl_trial'];	$level_exp = $lang['auth_trial'];	break;
				case '3': $level = $images['lvl_member'];	$level_exp = $lang['auth_member'];	break;
				case '4': $level = $images['lvl_mod'];		$level_exp = $lang['auth_mod'];		break;
				case '5': $level = $images['lvl_admin'];	$level_exp = $lang['auth_admin'];	break;
			}
				
			$template->assign_block_vars('_event_row', array(
				'TITLE'		=> $event[$i]['event_title'],
				'DATE'		=> $event_date,
				'LEVEL'		=> $level,
				'LEVEL_EXP'	=> $level_exp,
				'UPDATE'	=> $link_update,
				'DELETE'	=> $link_delete,
			));
		}
	}

	$sql = "SELECT m.match_id, m.match_rival_name, m.match_public, m.match_date, t.team_name, g.game_image, g.game_size
				FROM " . MATCH . " m
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
			ORDER BY m.match_date DESC LIMIT 0, 5";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data_match = $db->sql_fetchrowset($result);

	if ( !$data_match )
	{
		$template->assign_block_vars('_entry_empty_match', array());
	}
	else
	{
		for ( $i = 0; $i < count($data_match); $i++ )
		{
			$match_id	= $data_match[$i]['match_id'];
			$match_typ	= $data_match[$i]['match_public'] ? 'sprintf_match_name' : 'sprintf_match_intern';
			$match_date	= create_date($userdata['user_dateformat'], $data_match[$i]['match_date'], $userdata['user_timezone']);
			
			if ( $userdata['user_level'] == ADMIN || $userauth['auth_match'] )
			{
				$link_update = '<a href="' . check_sid("admin_match.php?mode=_update&amp;$url_match=$match_id") . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>';
				$link_detail = '<a href="' . check_sid("admin_match.php?mode=_detail&amp;$url_match=$match_id") . '"><img src="' . $images['icon_option_details'] . '" title="' . $lang['common_details'] . '" alt="" /></a>';
				$link_delete = '<a href="' . check_sid("admin_match.php?mode=_delete&amp;$url_match=$match_id&amp;acp_main=1") . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>';
			}
			else
			{
				$link_update = '';
				$link_detail = '';
				$link_delete = '';
			}
				
			$template->assign_block_vars('_match_row', array(
				'GAME'		=> display_gameicon($data_match[$i]['game_size'], $data_match[$i]['game_image']),
				'RIVAL'		=> sprintf($lang[$match_typ], $data_match[$i]['match_rival_name']),
				'DATE'		=> $match_date,
				'UPDATE'	=> $link_update,
				'DETAIL'	=> $link_detail,
				'DELETE'	=> $link_delete,				
			));
		}
	}
	
	$train = data(TRAINING, "WHERE training_date > " . time(), 'training_date DESC', 1, false);
	$count = ( count($train) < 5 ) ? count($train) : 5;

	if ( !$train )
	{
		$template->assign_block_vars('_entry_empty_training', array());
	}
	else
	{
		for ( $i = 0; $i < $count; $i++ )
		{
			$training_id	= $train[$i]['training_id'];
			$training_date	= create_date($userdata['user_dateformat'], $train[$i]['training_date'], $userdata['user_timezone']);
			
			if ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] )
			{
				$link_update = '<a href="' . check_sid('admin_training.php?mode=_update&amp;' . POST_TRAINING . '=' . $training_id) . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>';
				$link_delete = '<a href="' . check_sid('admin_training.php?mode=_delete&amp;' . POST_TRAINING . '=' . $training_id) . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>';
			}
			else
			{
				$link_update = '';
				$link_delete = '';
			}
				
			$template->assign_block_vars('row_training', array(
				'VS'		=> $train[$i]['training_vs'],
				'DATE'		=> $training_date,
				'UPDATE'	=> $link_update,
				'DELETE'	=> $link_delete,
			));
		}
	}
	
	$users = data(USERS, "user_id != 1", 'user_regdate DESC', 1, false);
	$count = ( count($users) < 5 ) ? count($users) : 5;
	
	if ( $users )
	{
		for ( $i = 0; $i < $count; $i++ )
		{
			$user_id		= $users[$i]['user_id'];
			$user_regdate	= create_date($userdata['user_dateformat'], $users[$i]['user_regdate'], $config['page_timezone']);
			
			if ( $userauth['auth_user'] || $userdata['user_level'] == ADMIN )
			{
				$link_update = '<a href="' . check_sid('admin_user.php?mode=_update&amp;' . POST_USER . '=' . $user_id) . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>';
				$link_delete = '<a href="' . check_sid('admin_user.php?mode=_delete&amp;' . POST_USER . '=' . $user_id) . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>';
			}
			else
			{
				$link_update = '';
				$link_delete = '';
			}
			
			switch ( $users[$i]['user_level'] )
			{
				case '0': $level = $images['lvl_guest'];	$level_exp = $lang['auth_guest'];	break;
				case '1': $level = $images['lvl_user'];		$level_exp = $lang['auth_user'];	break;
				case '2': $level = $images['lvl_trial'];	$level_exp = $lang['auth_trial'];	break;
				case '3': $level = $images['lvl_member'];	$level_exp = $lang['auth_member'];	break;
				case '4': $level = $images['lvl_mod'];		$level_exp = $lang['auth_mod'];		break;
				case '5': $level = $images['lvl_admin'];	$level_exp = $lang['auth_admin'];	break;
			}
				
			$template->assign_block_vars('_user_row', array(
				'NAME'		=> $users[$i]['user_name'],
				
				'LEVEL'		=> $level,
				'LEVEL_EXP'	=> $level_exp,
				
				'REGDATE'	=> $user_regdate,
				'UPDATE'	=> $link_update,
				'DELETE'	=> $link_delete,
			));
		}
	}

	$template->assign_vars(array(
	
		'L_WELCOME'	=> $lang['index'],
		'L_EXPLAIN'	=> $lang['explain'],
	
		'L_NEWS'	=> $lang['sm_news'],
		'L_EVENT'	=> $lang['sm_event'],
		'L_MATCH'	=> $lang['sm_match'],
		'L_TRAIN'	=> $lang['sm_training'],
		'L_USERS'	=> $lang['sm_users'],
		
		'I_NEWS'	=> $images['news'],
		'I_EVENT'	=> $images['event'],
		'I_MATCH'	=> $images['match'],
		'I_TRAIN'	=> $images['match'],
		'I_USERS'	=> $images['user'],
		
		'U_NEWS'	=> check_sid('admin_news.php'),
		'U_MATCH'	=> check_sid('admin_match.php'),
		'U_TRAIN'	=> check_sid('admin_training.php'),
		'U_EVENT'	=> check_sid('admin_event.php'),
		'U_USERS'	=> check_sid('admin_user.php'),
		
	));

	/* stats
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
		"DB_SIZE" => $dbsize, 
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