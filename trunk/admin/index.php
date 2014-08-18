<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_index',
		'modes'		=> array(
			'main'		=> array('title' => 'acp_index'),
		),
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel	= ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_index';
	
	include('./pagestart.php');
	
	add_lang('index');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GALLERY;
	$time	= time();
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$num	= request('num', INT);
	$mode	= request('mode', TYP);
	$sync	= request('sync', TYP);

	$template->set_filenames(array(
		'body'	=> 'style/acp_index.tpl',
		'error'	=> 'style/info_error.tpl',
	));
	
#	debug($_POST, '_POST');

	$mode = (in_array($mode, array('switch', 'sync'))) ? $mode : false;
	
	switch ( $mode )
	{
		case 'switch':
		
			$data_sql = data(NEWS, $num, false, 1, true);
			
			$switch = ( $data_sql['news_public'] ) ? 0 : 1;
			
			sql(NEWS, 'update', array('news_public' => $switch), 'news_id', $num);
			
			$oCache->deleteCache('dsp_news');
			
			log_add(LOG_ADMIN, SECTION_NEWS, $mode);
		
		default:
		
			if ( request('vchk', TYP) )
			{
				$oCache->deleteCache('version_cms');
			}
			
			if ( request('delc', TYP) )
			{
				$oCache->truncateCache();
			}
			
			if ( $sync )
			{
				switch ( $sync )
				{
					case 'group':	$typ = array(GROUPS, 'group_id', 'group_image'); break;
					case 'network':	$typ = array(NETWORK, 'network_id', 'network_image'); break;
				}
				
				$tmp_file = array_diff(scandir($root_path . $settings['path_' . $sync]['path'], 1), array('.', '..', 'index.htm', '.htaccess'));
				
				$sql = 'SELECT * FROM ' . $typ[0];
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$tmp_data = '';
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					if ( $row[$typ[2]] )
					{
						$tmp_data[$row[$typ[1]]] = $row[$typ[2]];
					}
				}
				
				if ( $tmp_data )
				{
					foreach ( $tmp_data as $key => $row )
					{
						if ( in_array($row, $tmp_file) )
						{
							$check[$key] = $row;
						}
						else
						{
							$uncheck[] = $key;
						}
					}
					
					if ( isset($uncheck) && is_array($uncheck) )
					{					
						foreach ( $uncheck as $key )
						{
							sql($typ[0], 'update', array($typ[2] => ''), $typ[1], $key);
						}
					}
					
					if ( isset($check) && is_array($check) && $tmp_file )
					{
						$tmp_diff = array_diff($tmp_file, $check);
														
						foreach ( $tmp_diff as $row )
						{
							@unlink($root_path . $settings['path_' . $sync]['path'] . '/' . $row);
						}
					}
				}
			}
		
			$news	= data(NEWS, false, 'news_date DESC, news_id DESC', 1, false);
			$event	= data(EVENT, "WHERE event_date > $time", 'event_date ASC', 1, false);
			$match	= data(MATCH, "WHERE match_date > $time", 'match_date DESC', 0, false);
			
			debug($match, true);
			
			$train	= data(TRAINING, "WHERE training_date > $time", 'training_date DESC', 0, false);
			$users	= data(USERS, "user_id != 1", 'user_regdate DESC', 1, false);
			
			if ( !$news )
			{
				$template->assign_block_vars('news_empty', array());
			}
			else
			{
				$cnt = count($news);
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id		= $news[$i]['news_id'];
					$typ	= $news[$i]['news_intern'] ? 'stf_intern' : 'stf_normal';
					$title	= sprintf($lang[$typ], $news[$i]['news_title']);
					$public	= $news[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
					
					$template->assign_block_vars('news_row', array(
						'TITLE'		=> ( $userdata['user_level'] == ADMIN || @$userauth['a_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', 'admin_news.php' . $adds, array('mode' => 'update', 'id' => $id), $title, $title) : $title : $title,
						
						'DATE'		=> create_date($userdata['user_dateformat'], $news[$i]['news_date'], $userdata['user_timezone']),
						
						'PUBLIC'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_news_public'] )	? href('a_txt', 'admin_index.php' . $adds, array('mode' => 'switch', 'num' => $id), $public, '') : img('i_icon', 'icon_news_denied', ''),
						'UPDATE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_news'] )			? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', 'admin_news.php' . $adds, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
						'DELETE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_news_delete'] )	? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', 'admin_news.php' . $adds, array('mode' => 'delete', 'id' => $id, 'acp_main' => 1), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete'),
					));
				}
			}
			
			if ( !$event )
			{
				$template->assign_block_vars('event_empty', array());
			}
			else
			{
				$cnt = count($event);
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id		= $event[$i]['event_id'];
					$title	= $event[$i]['event_title'];
					
					switch ( $event[$i]['event_level'] )
					{
						case GUEST:	$level = img('i_icon', 'icon_level_guest', 'auth_guest');	break;
						case USER:	$level = img('i_icon', 'icon_level_user', 'auth_user');		break;
						case TRIAL:	$level = img('i_icon', 'icon_level_trial', 'auth_trial');	break;
						case MEMBER:$level = img('i_icon', 'icon_level_member', 'auth_member');	break;
						case MOD:	$level = img('i_icon', 'icon_level_mod', 'auth_mod');		break;
						case ADMIN:	$level = img('i_icon', 'icon_level_admin', 'auth_admin');	break;
					}
					
					$template->assign_block_vars('event_row', array(
						'TITLE'		=> ( $userdata['user_level'] == ADMIN || @$userauth['a_event'] ) ? href('a_txt', 'admin_event.php' . $adds, array('mode' => 'update', 'id' => $id), $title, $title) : $title,
						
						'LEVEL'		=> $level,
						'DATE'		=> create_date($userdata['user_dateformat'], $event[$i]['event_date'], $config['default_timezone']),
						
						'UPDATE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_event'] ) ? href('a_img', 'admin_event.php' . $adds, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
						'DELETE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_event'] ) ? href('a_img', 'admin_event.php' . $adds, array('mode' => 'delete', 'id' => $id, 'acp_main' => 1), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete'),
					));
				}
			}
			
			if ( !$match )
			{
				$template->assign_block_vars('match_empty', array());
			}
			else
			{
				$cnt = count($match);
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id		= $match[$i]['match_id'];
					$typ	= $match[$i]['match_public'] ? 'sprintf_match_name' : 'sprintf_match_intern';
					$name	= $match[$i]['match_rival_name'];
					$rival	= sprintf($lang[$typ], $name);
					
					$template->assign_block_vars('match_row', array(
						'RIVAL'		=> ( $userdata['user_level'] == ADMIN || @$userauth['a_match'] ) ? href('a_txt', 'admin_match.php' . $adds, array('mode' => 'update', 'id' => $id), $rival, $rival) : $rival,
						
						'GAME'		=> display_gameicon($match[$i]['game_image']),
						'DATE'		=> create_date($userdata['user_dateformat'], $match[$i]['match_date'], $userdata['user_timezone']),
						
						'DETAIL'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_match_manage'] ) ? href('a_img', 'admin_match.php' . $adds, array('id' => $id), 'icon_details', 'common_details') : img('i_icon', 'icon_details2', 'common_details'),
						'UPDATE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_match'] ) ? href('a_img', 'admin_match.php' . $adds, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
						'DELETE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_match_delete'] ) ? href('a_img', 'admin_match.php' . $adds, array('mode' => 'delete', 'id' => $id, 'acp_main' => 1), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete'),
					));
				}
			}
				
			if ( !$train )
			{
				$template->assign_block_vars('training_empty', array());
			}
			else
			{
				$cnt = count($train);
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id	= $train[$i]['training_id'];
					$vs = $train[$i]['training_vs'];
					
					$template->assign_block_vars('training_row', array(
						'VS'		=> ( $userdata['user_level'] == ADMIN || @$userauth['a_training'] ) ? href('a_txt', 'admin_training.php' . $adds, array('mode' => 'update', 'id' => $id), $vs, $vs) : $vs,
						
						'DATE'		=> create_date($userdata['user_dateformat'], $train[$i]['training_date'], $userdata['user_timezone']),
						
						'UPDATE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_training'] ) ? href('a_img', 'admin_training.php' . $adds, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
						'DELETE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_training_delete'] ) ? href('a_img', 'admin_training.php' . $adds, array('mode' => 'delete', 'id' => $id, 'acp_main' => 1), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete'),
					));
				}
			}
				
			if ( $users )
			{
				$cnt = count($users);
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
			
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id		= $users[$i]['user_id'];
					$name	= $users[$i]['user_name'];
					
					switch ( $users[$i]['user_level'] )
					{
						case GUEST:	$level = img('i_icon', 'icon_level_guest', 'auth_guest');	break;
						case USER:	$level = img('i_icon', 'icon_level_user', 'auth_user');		break;
						case TRIAL:	$level = img('i_icon', 'icon_level_trial', 'auth_trial');	break;
						case MEMBER:$level = img('i_icon', 'icon_level_member', 'auth_member');	break;
						case MOD:	$level = img('i_icon', 'icon_level_mod', 'auth_mod');		break;
						case ADMIN:	$level = img('i_icon', 'icon_level_admin', 'auth_admin');	break;
					}
					
					$template->assign_block_vars('user_row', array(
						'NAME'		=> ( $userdata['user_level'] == ADMIN || @$userauth['a_user'] )			? href('a_txt', "admin_user.php{$adds}", array('mode' => 'update', 'id' => $id), $name, $name) : $name,
						
						'LEVEL'		=> $level,
						'REGDATE'	=> create_date($userdata['user_dateformat'], $users[$i]['user_regdate'], $config['default_timezone']),
						
						'AUTH'		=> ( $userdata['user_level'] == ADMIN || @$userauth['a_auth_user'] )	? href('a_img', "admin_user.php{$adds}", array('mode' => 'auth', 'id' => $id), 'icon_user_auth', '') : img('i_icon', 'icon_user_auth2', ''),
						'UPDATE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_user'] )			? href('a_img', "admin_user.php{$adds}", array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
						'DELETE'	=> ( $userdata['user_level'] == ADMIN || @$userauth['a_user_delete'] )	? href('a_img', "admin_user.php{$adds}", array('mode' => 'delete', 'id' => $id, 'acp_main' => 1), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete'),
					));
				}
			}
			
			if ( $config['page_disable'] == 1 )
			{
				$template->assign_vars(array('ERROR_MESSAGE' => $lang['msg_page_disable']));
				$template->assign_var_from_handle('ERROR_BOX', 'error');
			}
			
			$errstr = '';
			$errno = 0;
					
			$lastest_checked = get_version('cms-phoenix.de', '/updatecheck', 'version.txt', $errstr, $errno);
			$lastest_version = @implode('.', $lastest_checked);
			
			$cache_time = $oCache->readCacheTime('version_cms');
			$cache_time = sprintf($lang['cache_valid'], create_date($userdata['user_dateformat'], $cache_time, $userdata['user_timezone']));
			
			/*		
			if ( version_compare($lastest_version, $config['page_version'], '<=') )
			{
				$version_info = sprintf($lang['version_info_green'], $config['page_version'], check_sid("$file?vchk=1"), $cache_time, $lang['version_check']);
			}
			else
			{
				$version_info = sprintf($lang['version_info_red'], $config['page_version'], check_sid("$file?vchk=1"), $cache_time, $lang['version_check'], $lastest_version);
			}
			*/
			
			$version_info = ( version_compare($lastest_version, $config['page_version'], '<=') ) ? 'green' : 'red';
			$version_info = sprintf($lang["version_info_$version_info"], $config['page_version'], check_sid("$file?vchk=1"), $cache_time, $lang['version_check'], $lastest_version);
			
			if ( preg_match("/^mysql/", SQL_LAYER) )
			{
				$sql = "SELECT VERSION() AS mysql_version";
				
				if ( $result = $db->sql_query($sql) )
				{
					$row = $db->sql_fetchrow($result);
					$version = $row['mysql_version'];
					
					if ( preg_match("/^(3\.23|4\.|5\.)/", $version) )
					{
						$db_name = ( preg_match("/^(3\.23\.[6-9])|(3\.23\.[1-9][1-9])|(4\.)|(5\.)/", $version) ) ? "$db_name" : $db_name;
						
						$sql = "SHOW TABLE STATUS FROM " . $db_name;
						
						if ( $result = $db->sql_query($sql) )
						{
							$tabledata_ary = $db->sql_fetchrowset($result);
							
							$dbsize = 0;
							$dbrows = 0;
							$tables = 0;
							
							for ( $i = 0; $i < count($tabledata_ary); $i++ )
							{
								if ( $tabledata_ary[$i]['Engine'] != "MRG_MyISAM" )
								{
									if ( $db_prefix != "" )
									{
										if ( strstr($tabledata_ary[$i]['Name'], $db_prefix) )
										{
											$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
										}
									}
									else
									{
										$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
									}
									
									$dbrows += $tabledata_ary[$i]['Rows'];
								}
								
								$tables++;
							}
						}
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
			else
			{
				$dbsize = $lang['Not_available'];
			}
			
			$backup_file = $backup_time = $backup_size = '';
			
			if ( is_dir("{$root_path}files/") )
			{
				$files = array_diff(scandir("{$root_path}files/", 1), array('.', '..', '.htaccess'));
				
				if ( is_array($files) )
				{
					foreach ( $files as $_file )
					{
						if ( strpos($_file, 'full_') !== false && strpos($_file, '.sql.gz') !== false )
						{
							$backup_file = preg_replace('/^full_|.sql.gz$/', '', $_file);
							$backup_time = create_date($userdata['user_dateformat'], $backup_file, $userdata['user_timezone']);
							$backup_size = size_round(@filesize("{$root_path}files/$_file"), 2);
							
							break;
						}
					}
				}
			}
			
		#	$cache_dir_tmp = array_diff(scandir($root_path . $settings['path_cache']), array('.', '..', '.htaccess'));
		#	$cache_dir_tmp = implode(', ', $cache_dir_tmp);
		
			/* added 11.07.2012 */
			/*
			$year = '2009';
			
			for ( $i = 1; $i < 13; $i++ ) 
			{
				$i = ( $i < 10 ) ? "0$i" : $i;
				
				$dates = date("t", mktime(0, 0, 0, $i, 1, $year));
				
				for ( $j = 1; $j < $dates+1; $j++ )
				{
					$j = ( $j < 10 ) ? "0$j" : $j;
					
					$sql = "SELECT counter_date, counter_entry FROM " . COUNTER_COUNTER . " WHERE MONTH(counter_date) = $i AND DAYOFMONTH(counter_date) = $j AND YEAR(counter_date) = $year";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					
					$c09["{$year}-{$i}-{$j}"] = isset($row['counter_entry']) ? $row['counter_entry'] : '0';
				}
			}
			
			$year = '2010';
			
			for ( $i = 1; $i < 13; $i++ ) 
			{
				$i = ( $i < 10 ) ? "0$i" : $i;
				
				$dates = date("t", mktime(0, 0, 0, $i, 1, $year));
				
				for ( $j = 1; $j < $dates+1; $j++ )
				{
					$j = ( $j < 10 ) ? "0$j" : $j;
					
					$sql = "SELECT counter_date, counter_entry FROM " . COUNTER_COUNTER . " WHERE MONTH(counter_date) = $i AND DAYOFMONTH(counter_date) = $j AND YEAR(counter_date) = $year";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					
					$c10["{$year}-{$i}-{$j}"] = isset($row['counter_entry']) ? $row['counter_entry'] : '0';
				}
			}
			
			$year = '2011';
			
			for ( $i = 1; $i < 13; $i++ ) 
			{
				$i = ( $i < 10 ) ? "0$i" : $i;
				
				$dates = date("t", mktime(0, 0, 0, $i, 1, $year));
				
				for ( $j = 1; $j < $dates+1; $j++ )
				{
					$j = ( $j < 10 ) ? "0$j" : $j;
					
					$sql = "SELECT counter_date, counter_entry FROM " . COUNTER_COUNTER . " WHERE MONTH(counter_date) = $i AND DAYOFMONTH(counter_date) = $j AND YEAR(counter_date) = $year";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					
					$c11["{$year}-{$i}-{$j}"] = isset($row['counter_entry']) ? $row['counter_entry'] : '0';
				}
			}
			
			$year = '2012';
			
			for ( $i = 1; $i < 13; $i++ ) 
			{
				$i = ( $i < 10 ) ? "0$i" : $i;
				
				$dates = date("t", mktime(0, 0, 0, $i, 1, $year));
				
				for ( $j = 1; $j < $dates+1; $j++ )
				{
					$j = ( $j < 10 ) ? "0$j" : $j;
					
					$sql = "SELECT counter_date, counter_entry FROM " . COUNTER_COUNTER . " WHERE MONTH(counter_date) = $i AND DAYOFMONTH(counter_date) = $j AND YEAR(counter_date) = $year";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					
					$c12["{$year}-{$i}-{$j}"] = isset($row['counter_entry']) ? $row['counter_entry'] : '0';
				}
			}
			
			$year = '2013';
			
			for ( $i = 1; $i < 13; $i++ ) 
			{
				$i = ( $i < 10 ) ? "0$i" : $i;
				
				$dates = date("t", mktime(0, 0, 0, $i, 1, $year));
				
				for ( $j = 1; $j < $dates+1; $j++ )
				{
					$j = ( $j < 10 ) ? "0$j" : $j;
					
					$sql = "SELECT counter_date, counter_entry FROM " . COUNTER_COUNTER . " WHERE MONTH(counter_date) = $i AND DAYOFMONTH(counter_date) = $j AND YEAR(counter_date) = $year";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					
					$c13["{$year}-{$i}-{$j}"] = isset($row['counter_entry']) ? $row['counter_entry'] : '0';
				}
			}
			*/
			/* debug(count($c09)); // days */
			/* debug(count($c10)); // days */
			/* debug(count($c11)); // days */
			/* debug(count($c12)); // days */
			/* debug(count($c13)); // days */
			
			if ( $settings['path_team_flag']['path'] == $settings['path_team_logo']['path'] )
			{
				$size_teams = size_dir2($settings['path_team_flag']['path']);
			}
			else
			{
				$size_teams = (size_dir2($settings['path_team_flag']['path']) + size_dir2($settings['path_team_logo']['path']));
			}
			
			
			$template->assign_vars(array(
				'L_WELCOME'	=> $lang['title'],
				'L_EXPLAIN'	=> $lang['explain'],
				
				'L_DIR'		=> $lang['dir'],
				'L_SIZE'	=> $lang['size'],
				'L_INFO'	=> $lang['info'],
				'L_VALUE'	=> $lang['value'],
				
				'L_PAGE_STARTED'	=> $lang['page_start'],
				'L_PAGE_VERSION'	=> $lang['page_version'],
				'L_PAGE_BACKUP'		=> $lang['page_backup'],
				'L_PAGE_GZIP'		=> $lang['page_gzip'],
				'L_SERVER'			=> $lang['server_info'],
				'L_DB_INFO'			=> $lang['page_dbinfo'],
				
				'L_SIZE_CACHE'		=> $lang['path_cache'],
				'L_SIZE_FILES'		=> $lang['path_files'],
				'L_SIZE_DOWNLOADS'	=> $lang['path_downloads'],
				'L_SIZE_GALLERY'	=> $lang['path_gallery'],
				'L_SIZE_MATCHS'		=> $lang['path_matchs'],
				'L_SIZE_USERS'		=> $lang['path_users'],
				'L_SIZE_GROUPS'		=> $lang['path_groups'],
				'L_SIZE_TEAMS'		=> $lang['path_teams'],
				'L_SIZE_NETWORK'	=> $lang['path_network'],
				
				'L_NEWS'		=> $lang['acp_news'],
				'L_EVENT'		=> $lang['acp_event'],
				'L_MATCH'		=> $lang['acp_match'],
				'L_TRAIN'		=> $lang['acp_training'],
				'L_USERS'		=> $lang['acp_users'],
				
				'PAGE_STARTED'		=> create_date($config['default_dateformat'], $config['page_startdate'], $config['default_timezone']),
				'PAGE_VERSION'		=> $version_info,
				'PAGE_BACKUP'		=> ( $backup_file != '' ) ? $backup_time : $lang['backup_na'],
				'PAGE_BACKUP_INFO'	=> ( $backup_file != '' ) ? sprintf($lang['sprintf_backup'], $backup_size) : '',
				'PAGE_GZIP'			=> $config['page_gzip'] ? $lang['common_on'] : $lang['common_off'],
				'SERVER'			=> sprintf('%s / %s [ %s ]', phpversion(), mysql_get_server_info(), '<a href="' . check_sid('admin_phpinfo.php') . '">phpInfo</a>'),
				'DB_INFO'			=> size_round($dbsize, 2),
				'DB_INFO_INFO'		=> sprintf($lang['sprintf_dbsize'], $db_name, $dbrows, $tables),
				
				'SIZE_CACHE'		=> sprintf('%s [ %s ]', size_dir2("cache/"), href('a_txt', $file, array('delc' => 1), $lang['cache_clear'])),
				'SIZE_FILES'		=> size_dir2("files/"),
				'SIZE_DOWNLOADS'	=> size_dir2($settings['path_downloads']),
				'SIZE_GALLERY'		=> size_dir2($settings['path_gallery']),
				'SIZE_MATCHS'		=> size_dir2($settings['path_matchs']['path']),
				'SIZE_USERS'		=> size_dir2($settings['path_users']['path']),
				'SIZE_GROUPS'		=> sprintf('%s [ %s ]', size_dir2($settings['path_group']['path']), href('a_txt', $file, array('sync' => 'group'), 'sync')),
			#	'SIZE_TEAMS'		=> sprintf('%s [ %s ]', $size_teams, href('a_txt', $file, array('sync' => 'team'), 'sync')),
				'SIZE_TEAMS'		=> $size_teams,
				'SIZE_NETWORK'		=> sprintf('%s [ %s ]', size_dir2($settings['path_network']['path']), href('a_txt', $file, array('sync' => 'network'), 'sync')),
				
				'I_NEWS'		=> $images['icon_news'],
				'I_EVENT'		=> $images['icon_event'],
				'I_MATCH'		=> $images['icon_match'],
				'I_TRAIN'		=> $images['icon_match'],
				'I_USERS'		=> $images['icon_user'],
				
				'U_NEWS'		=> check_sid('admin_news.php'),
				'U_NEWS_ADD'	=> check_sid('admin_news.php?mode=_create'),
				'U_MATCH'		=> check_sid('admin_match.php'),
				'U_TRAIN'		=> check_sid('admin_training.php'),
				'U_EVENT'		=> check_sid('admin_event.php'),
				'U_USERS'		=> check_sid('admin_user.php'),
			));
		
			
		
		break;
	}
}

$template->pparse('body');

acp_footer();

?>