<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_INDEX',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'ACP_INDEX'),
		),
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel	= ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_INDEX';
	
	include('./pagestart.php');
	
	add_lang('index');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$time	= time();
	$log	= SECTION_INDEX;
	
	$start	= request('start', INT);
	$num	= request('num', INT);
	$sync	= request('sync', TYP);
	$mode	= request('mode', TYP);
	$mode = (in_array($mode, array('switch', 'sync', 'repair'))) ? $mode : false;
	$repair	= request('repair', TYP);

	$template->set_filenames(array(
		'body'	=> 'style/acp_index.tpl',
		'error'	=> 'style/info_error.tpl',
	));
	
	switch ( $mode )
	{
		case 'switch':
		
			$data_sql = data(NEWS, $num, false, 1, 'row');
			
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
			
			if ( $repair )
			{
				switch ( $repair )
				{
					case 'session':
					
						$sql = array();
						$sql[] = 'TRUNCATE ' . SESSIONS;
						$sql[] = 'REPAIR TABLE ' . SESSIONS;
						$sql[] = 'OPTIMIZE TABLE ' . SESSIONS;
						
						for ( $i = 0; $i < count($sql); $i++ )
						{
							if( !$result = $db->sql_query($sql[$i]) )
							{
								$error = $db->sql_error();
						
								echo '<li>' . $sql[$i] . '<br /> +++ <font color="#FF0000"><b>Error:</b></font> ' . $error['message'] . '</li><br />';
							}
							else
							{
								echo '<li>' . $sql[$i] . '<br /> +++ <font color="#00AA00"><b>Successfull</b></font></li><br />';
							}
						}
						
						break;
				}
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
		
			$sql_news	= data(NEWS, false, 'news_date DESC, news_id DESC', 1, 'set');
			$sql_event	= data(EVENT, "WHERE event_date > $time", 'event_date ASC', 1, 'set');
			$sql_match	= data(MATCH, "WHERE match_date > $time", 'match_date DESC', 4, 'set');
			$sql_train	= data(TRAINING, "WHERE training_date > $time", 'training_date DESC', 1, 'set');
			$sql_users	= data(USERS, "user_id != 1", 'user_regdate DESC', 1, 'set');
			
			if ( !$sql_news )
			{
				$template->assign_block_vars('news_empty', array());
			}
			else
			{
				$cnt = count($sql_news);
				
				$current = $cnt;
				$history = $cnt;
				$timeline = $time-604800;
				
				foreach ( $sql_news as $row )
				{
					if ( $timeline > $row['time_create'] )
					{
						$history -= 1;
					}
				}
				
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id		= $sql_news[$i]['news_id'];
					$typ	= $sql_news[$i]['news_intern'] ? 'STF_INTERN' : 'STF_NORMAL';
					$title	= sprintf($lang[$typ], $sql_news[$i]['news_title']);
					$public	= $sql_news[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
					
					$template->assign_block_vars('news_row', array(
						'TITLE'		=> ( $userauth['A_NEWS'] ) ? ( $userdata['user_level'] == ADMIN || $sql_news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', 'admin_news.php' . $iadds, array('mode' => 'update', 'id' => $id), $title, $title) : $title : $title,
						
						'DATE'		=> create_date($userdata['user_dateformat'], $sql_news[$i]['news_date'], $userdata['user_timezone']),
						
						'PUBLIC'	=> ( $userauth['A_NEWS_PUBLIC'] )	? href('a_txt', 'admin_index.php' . $iadds, array('mode' => 'switch', 'num' => $id), $public, '') : img('i_icon', 'icon_news_denied', ''),
						'UPDATE'	=> ( $userauth['A_NEWS'] )			? ( $userdata['user_level'] == ADMIN || $sql_news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', 'admin_news.php' . $iadds, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE'),
						'DELETE'	=> ( $userauth['A_NEWS_DELETE'] )	? ( $userdata['user_level'] == ADMIN || $sql_news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', 'admin_news.php' . $iadds, array('mode' => 'delete', 'id' => $id, 'index' => 1), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE'),
					));
				}
			}
			
			if ( !$sql_event )
			{
				$template->assign_block_vars('event_empty', array());
			}
			else
			{
				$cnt = count($sql_event);
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id		= $sql_event[$i]['event_id'];
					$title	= $sql_event[$i]['event_title'];
					
					$template->assign_block_vars('event_row', array(
						'TITLE'		=> ( $userauth['A_EVENT'] ) ? href('a_txt', 'admin_event.php' . $iadds, array('mode' => 'update', 'id' => $id), $title, $title) : $title,
						
						'DATE'		=> create_date($userdata['user_dateformat'], $sql_event[$i]['event_date'], $config['default_timezone']),
						
						'UPDATE'	=> ( $userauth['A_EVENT'] ) ? href('a_img', 'admin_event.php' . $iadds, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE'),
						'DELETE'	=> ( $userauth['A_EVENT'] ) ? href('a_img', 'admin_event.php' . $iadds, array('mode' => 'delete', 'id' => $id, 'index' => 1), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE'),
					));
				}
			}
			
			if ( !$sql_match )
			{
				$template->assign_block_vars('match_empty', array());
			}
			else
			{
				$cnt = count($sql_match);
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id		= $sql_match[$i]['match_id'];
					$typ	= $sql_match[$i]['match_public'] ? 'STF_MATCH_NAME' : 'STF_MATCH_INTERN';
					$name	= $sql_match[$i]['match_rival_name'];
					$rival	= sprintf($lang[$typ], $name);
					
					$template->assign_block_vars('match_row', array(
						'RIVAL'		=> ( $userauth['A_MATCH'] ) ? href('a_txt', 'admin_match.php' . $iadds, array('mode' => 'update', 'id' => $id), $rival, $rival) : $rival,
						
						'GAME'		=> display_gameicon($sql_match[$i]['game_image']),
						'DATE'		=> create_date($userdata['user_dateformat'], $sql_match[$i]['match_date'], $userdata['user_timezone']),
						
						'DETAIL'	=> ( $userauth['A_MATCH_MANAGE'] ) ? href('a_img', 'admin_match.php' . $iadds, array('id' => $id, 'mode' => 'detail'), 'icon_details', 'common_details') : img('i_icon', 'icon_details2', 'common_details'),
						'UPDATE'	=> ( $userauth['A_MATCH'] ) ? href('a_img', 'admin_match.php' . $iadds, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE'),
						'DELETE'	=> ( $userauth['A_MATCH_DELETE'] ) ? href('a_img', 'admin_match.php' . $iadds, array('mode' => 'delete', 'id' => $id, 'index' => 1), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE'),
					));
				}
			}
				
			if ( !$sql_train )
			{
				$template->assign_block_vars('training_empty', array());
			}
			else
			{
				$cnt = count($sql_train);
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id	= $sql_train[$i]['training_id'];
					$vs = $sql_train[$i]['training_vs'];
					
					$template->assign_block_vars('training_row', array(
						'VS'		=> ( $userauth['A_TRAINING'] ) ? href('a_txt', 'admin_training.php' . $iadds, array('mode' => 'update', 'id' => $id), $vs, $vs) : $vs,
						
						'DATE'		=> create_date($userdata['user_dateformat'], $sql_train[$i]['training_date'], $userdata['user_timezone']),
						
						'UPDATE'	=> ( $userauth['A_TRAINING'] ) ? href('a_img', 'admin_training.php' . $iadds, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE'),
						'DELETE'	=> ( $userauth['A_TRAINING_DELETE'] ) ? href('a_img', 'admin_training.php' . $iadds, array('mode' => 'delete', 'id' => $id, 'index' => 1), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE'),
					));
				}
			}
				
			if ( $sql_users )
			{
				$cnt = count($sql_users);
				$cnt = ( $cnt < $settings['per_page_entry']['index'] ) ? $cnt : $settings['per_page_entry']['index'];
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id		= $sql_users[$i]['user_id'];
					$name	= $sql_users[$i]['user_name'];
					
					$template->assign_block_vars('user_row', array(
						'NAME'		=> ( $userauth['A_USER'] )			? href('a_txt', "admin_user.php{$iadds}", array('mode' => 'update', 'id' => $id), $name, $name) : $name,
						
						'REGDATE'	=> create_date($userdata['user_dateformat'], $sql_users[$i]['user_regdate'], $config['default_timezone']),
						
						'AUTH'		=> ( $userauth['A_AUTH_USER'] == 1 )	? href('a_img', "admin_user.php?i=4", array('mode' => 'permission', 'id' => $id), 'icon_user_auth', '') : img('i_icon', 'icon_user_auth2', ''),
						'UPDATE'	=> ( $userauth['A_USER'] == 1 )			? href('a_img', "admin_user.php?i=4", array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE'),
						'DELETE'	=> ( $userauth['A_USER_DELETE'] == 1 )	? href('a_img', "admin_user.php?i=4", array('mode' => 'delete', 'id' => $id, 'index' => 1), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE'),
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
			
			$version_info = ( version_compare($lastest_version, $config['page_version'], '>=') ) ? 'green' : 'red';
			$version_info = sprintf($lang["version_info_$version_info"], $config['page_version'], check_sid($file . "vchk=1", true), $cache_time, $lang['version_check'], $lastest_version);
			
			if ( preg_match("/^mysql/", SQL_LAYER) )
			{
				$sql = "SELECT VERSION() AS mysql_version";
				
				if ( $result = $db->sql_query($sql) )
				{
					$row = $db->sql_fetchrow($result);
					$version = $row['mysql_version'];
					
					if ( preg_match("/^(3\.23|4\.|5\.|10\.)/", $version) )
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
		
			if ( $settings['path_team_flag']['path'] == $settings['path_team_logo']['path'] )
			{
				$size_teams = size_dir2($settings['path_team_flag']['path']);
			}
			else
			{
				$size_teams = (size_dir2($settings['path_team_flag']['path']) + size_dir2($settings['path_team_logo']['path']));
			}
			
			
			/*
			 *	Viewonline
			 */
			$sql = "SELECT u.user_id, u.user_name, u.user_session_time, u.user_session_page, u.user_allow_viewonline, s.session_logged_in, s.session_ip, s.session_start 
				FROM " . USERS . " u, " . SESSIONS . " s
				WHERE s.session_logged_in = " . TRUE . " 
					AND u.user_id = s.session_user_id 
					AND u.user_id <> " . ANONYMOUS . " 
					AND s.session_time >= " . ( time() - 300 ) . " 
				ORDER BY u.user_session_time DESC";
			if(!$result = $db->sql_query($sql))
			{
				message(GENERAL_ERROR, "Couldn't obtain regd user/online information.", "", __LINE__, __FILE__, $sql);
			}
			$onlinerow_reg = $db->sql_fetchrowset($result);
			
			$sql = "SELECT session_page, session_logged_in, session_time, session_ip, session_start   
				FROM " . SESSIONS . "
				WHERE session_logged_in = 0
					AND session_time >= " . ( time() - 300 ) . "
				ORDER BY session_time DESC";
			if(!$result = $db->sql_query($sql))
			{
				message(GENERAL_ERROR, "Couldn't obtain guest user/online information.", "", __LINE__, __FILE__, $sql);
			}
			$onlinerow_guest = $db->sql_fetchrowset($result);
			
			$reg_userid_ary = array();

			if ( count($onlinerow_reg) )
			{
				$registered_users = 0;
		
				for($i = 0; $i < count($onlinerow_reg); $i++)
				{
					if( !in_array($onlinerow_reg[$i]['user_id'], $reg_userid_ary) )
					{
						$reg_userid_ary[] = $onlinerow_reg[$i]['user_id'];
		
						$username = $onlinerow_reg[$i]['user_name'];
		
						if ( $onlinerow_reg[$i]['user_allow_viewonline'] )
						{
							$registered_users++;
							$hidden = FALSE;
						}
						else
						{
							$hidden_users++;
							$hidden = TRUE;
						}
		
						if( $onlinerow_reg[$i]['user_session_page'] < 1 )
						{
							switch($onlinerow_reg[$i]['user_session_page'])
							{
								case PAGE_ADMIN:		$location = 'acp_index';					break;
								case PAGE_INDEX:		$location = $lang['Forum_index'];			break;
								case PAGE_POSTING:		$location = $lang['Posting_message'];		break;
								case PAGE_LOGIN:		$location = $lang['Logging_on'];			break;
								case PAGE_SEARCH:		$location = $lang['Searching_forums'];		break;
								case PAGE_PROFILE:		$location = $lang['Viewing_profile'];		break;
								case PAGE_VIEWONLINE:	$location = $lang['Viewing_online'];		break;
								case PAGE_VIEWMEMBERS:	$location = $lang['Viewing_member_list'];	break;
								default:				$location = $lang['Forum_index'];
							}
						}
						else
						{
							$location = $forum_data[$onlinerow_reg[$i]['user_session_page']];
						}
		
						$row_color = ( $registered_users % 2 ) ? $theme['td_color1'] : $theme['td_color2'];
						$row_class = ( $registered_users % 2 ) ? $theme['td_class1'] : $theme['td_class2'];
		
						$reg_ip = decode_ip($onlinerow_reg[$i]['session_ip']);
		
						$template->assign_block_vars('online_users', array(
							"USERNAME" => $username, 
							"STARTED" => create_date($config['default_dateformat'], $onlinerow_reg[$i]['session_start'], $config['default_timezone']), 
							"LASTUPDATE" => create_date($config['default_dateformat'], $onlinerow_reg[$i]['user_session_time'], $config['default_timezone']),
							
							"FORUM_LOCATION" => langs($location),							
							"IP_ADDRESS" => $reg_ip, 
		
							"U_WHOIS_IP" => "http://network-tools.com/default.asp?host=$reg_ip",
						));
					}
				}
			}
			
			if ( count($onlinerow_guest) )
			{
				$guest_users = 0;
		
				for($i = 0; $i < count($onlinerow_guest); $i++)
				{
					$guest_userip_ary[] = $onlinerow_guest[$i]['session_ip'];
					$guest_users++;
		
					if( $onlinerow_guest[$i]['session_page'] < 1 )
					{
						switch( $onlinerow_guest[$i]['session_page'] )
						{
							case PAGE_ADMIN:		$location = 'acp_index';					break;
							case PAGE_INDEX:		$location = $lang['Forum_index'];			break;
							case PAGE_POSTING:		$location = $lang['Posting_message'];		break;
							case PAGE_LOGIN:		$location = $lang['Logging_on'];			break;
							case PAGE_SEARCH:		$location = $lang['Searching_forums'];		break;
							case PAGE_PROFILE:		$location = $lang['Viewing_profile'];		break;
							case PAGE_VIEWONLINE:	$location = $lang['Viewing_online'];		break;
							case PAGE_VIEWMEMBERS:	$location = $lang['Viewing_member_list'];	break;
							default:				$location = $lang['Forum_index'];
						}
					}
					else
					{
						$location = $forum_data[$onlinerow_guest[$i]['session_page']];
					}
		
					$guest_ip = decode_ip($onlinerow_guest[$i]['session_ip']);
					
					$template->assign_block_vars('online_guests', array(
						"USERNAME" => $username, 
						"STARTED" => create_date($config['default_dateformat'], $onlinerow_reg[$i]['session_start'], $config['default_timezone']), 
						"LASTUPDATE" => create_date($config['default_dateformat'], $onlinerow_reg[$i]['user_session_time'], $config['default_timezone']),
						
						"FORUM_LOCATION" => langs($location),							
						"IP_ADDRESS" => $reg_ip, 
	
						"U_WHOIS_IP" => "http://network-tools.com/default.asp?host=$reg_ip",
					));
				}
			}
			
			/*
			 *	user auths
			 */
			$implode_userauth = $iu = '';
			
			if ( $userauth && is_array($userauth) )
			{
				foreach ( $userauth as $auth => $row )
				{
					if ( $row == 1 )
					{
						$iu[] = langs($auth);
					}
				}
				
				$implode_userauth = is_array($iu) ? implode(', ', $iu) : '';
			}
						
			/* 
			 * acp protcoll
			 */
			$sql = 'SELECT l.*, u.user_id, u.user_name, u.user_color FROM ' . LOGS . ' l, ' . USERS . ' u WHERE l.user_id = u.user_id AND log_type = ' . LOG_ADMIN . ' AND NOT l.log_message = "login" AND NOT l.log_message = "login_acp" ORDER BY log_id DESC LIMIT 0, 5';
			if (!($result = $db->sql_query($sql)))
			{
				message(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
			}
			$sqlout = $db->sql_fetchrowset($result);
			
			if ( $sqlout )
			{
				$max = count($sqlout);
				
				foreach ( $sqlout as $row )
				{
					$message = $row['log_message'];
					$section = $row['log_section'];
					$section = isset($lang['section'][$section]) ? $lang['section'][$section] : $section;
					
					if ( $message )
					{
						$class = ( $message == 'error' || strstr($message, 'auth_fail') ) ? 'row_error' : '';
						$message = langs('common_' . $message);
					}
					
					$log_data = unserialize($row['log_data']);
					$msg_data = '&raquo;';
					
					if ( is_array($log_data) )
					{
						$msg = array();
						
						foreach ( $log_data as $_row )
						{
							$_meta	= isset($_row['meta'])	? $_row['meta'] : false;
							$_data	= isset($_row['data'])	? $_row['data'] : false;
							$_post	= isset($_row['post'])	? $_row['post'] : false;
							$field	= isset($_row['field'])	? $_row['field'] : false;
							$_lang	= langs($field);
							
							if ( strpos($row['log_message'], 'create') !== false )
							{
								$msg[] = sprintf($lang['STF_LOG_CREATE'], langs($field), $_row['post']);
							}
							else if ( strpos($row['log_message'], 'update') !== false )
							{
								if ( $field == 'main' )
								{
									switch ( $row['log_section'] )
									{
										case SECTION_MENU: 
										
											$menu = data(MENU, false, false, 1, 'set');
											
											foreach ( $menu as $_menu )
											{
												$_new[$_menu['menu_id']] = $_menu['menu_name'];
											}
											
											break;
									}
								}
								$msg[] = sprintf($lang['STF_LOG_CHANGE'], langs($field), (isset($_new[$_row['data']]) ? $_new[$_row['data']] : $_row['data']), (isset($_new[$_row['post']]) ? $_new[$_row['post']] : $_row['post']));
								
								unset($_new);
							}
							else if ( strpos($row['log_message'], 'delete') !== false )
							{
								$msg[] = sprintf($lang['STF_LOG_DELETE'], langs($field), $_row['data']);
							}
							else
							{
								$msg[] = sprintf($lang['STF_LOG_ERROR'], langs($field), $_row);
							}
						}
						
						$msg_data .= implode('<br />&raquo;', $msg);
					}
					else
					{
						$msg_data = '&nbsp;' . langs($log_data);
					}
					
					$template->assign_block_vars('logrow', array(
						'USERNAME'		=> href('ahref_style', 'admin_user.php?', array('i' => '4', 'mode' => 'update', 'id' => $row['user_id']), $row['user_color'], $row['user_name'], $row['user_name']),
						'IP'            => decode_ip($row['user_ip']),
						'DATE'          => create_date($userdata['user_dateformat'], $row['log_time'], $userdata['user_timezone']),
						'SEKTION'       => $section,
						'MESSAGE'       => $message,
						'DATA'          => $msg_data,
					));
				}
			}
			
			$PhpInfo = parsePHPInfo();
			
			$template->assign_vars(array(
				'L_WELCOME'		=> $lang['TITLE'],
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				'L_EXPLAIN2'	=> $lang['explain2'],
				'L_EXPLAIN3'	=> $lang['explain3'],
				'L_EXPLAIN4'	=> $lang['explain4'],
				
				'L_STATS'		=> $lang['stats'],
				'L_VIEWONLINE'	=> $lang['viewonline'],
				'L_ACP_LOG'		=> $lang['acp_log'],
				'L_FASTVIEW'	=> $lang['fastview'],
				
				'L_STARTED'			=> $lang['Login'],
				'L_LAST_UPDATE'		=> $lang['Last_updated'],
				'L_FORUM_LOCATION'	=> $lang['Forum_Location'],
				'L_IP_ADDRESS'		=> $lang['IP_Address'],
				
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
				
				'L_NEWS'		=> $lang['ACP_NEWS'],
				'L_EVENT'		=> $lang['ACP_EVENT'],
				'L_MATCH'		=> $lang['ACP_MATCH'],
				'L_TRAIN'		=> $lang['ACP_TRAINING'],
				'L_USERS'		=> $lang['ACP_USER'],
				
				'SESSION'			=> href('a_txt', $file, array('repair' => 'session'), 'session'),
				
				'PAGE_STARTED'		=> create_date($config['default_dateformat'], $config['page_startdate'], $config['default_timezone']),
				'PAGE_VERSION'		=> $version_info,
				'PAGE_BACKUP'		=> ( $backup_file != '' ) ? $backup_time : $lang['backup_na'],
				'PAGE_BACKUP_INFO'	=> ( $backup_file != '' ) ? sprintf($lang['sprintf_backup'], $backup_size) : '',
				'PAGE_GZIP'			=> $config['page_gzip'] ? $lang['common_on'] : $lang['common_off'],
				'SERVER'			=> sprintf('%s / %s [ %s ]', phpversion(), substr(mysql_get_server_info(), 0, strpos(mysql_get_server_info(), '-')), '<a href="' . check_sid('admin_phpinfo.php') . '">phpInfo</a>'),
				'DB_INFO'			=> size_round($dbsize, 2),
				'DB_INFO_INFO'		=> sprintf($lang['sprintf_dbsize'], $db_name, $dbrows, $tables),
				
				'SIZE_CACHE'		=> sprintf('%s [ %s ]', size_dir2("cache/"), href('a_txt', $file, array('delc' => 1), $lang['cache_clear'])),
				'SIZE_FILES'		=> size_dir2("files/"),
				'SIZE_DOWNLOADS'	=> size_dir2($settings['path']['downloads']),
				'SIZE_GALLERY'		=> size_dir2($settings['path']['gallery']),
				'SIZE_MATCHS'		=> size_dir2($settings['path_matchs']['path']),
				'SIZE_USERS'		=> size_dir2($settings['path_users']['path']),
				'SIZE_GROUPS'		=> sprintf('[ %s ] %s', href('a_txt', $file, array('sync' => 'group'), $lang['COMMON_RESYNC']), size_dir2($settings['path_group']['path'])),
			#	'SIZE_TEAMS'		=> sprintf('[ %s ] %s', href('a_txt', $file, array('sync' => 'team'), $lang['COMMON_RESYNC']), $size_teams),
				'SIZE_TEAMS'		=> $size_teams,
				'SIZE_NETWORK'		=> sprintf('[ %s ] %s', href('a_txt', $file, array('sync' => 'network'), $lang['COMMON_RESYNC']), size_dir2($settings['path_network']['path'])),
				
				'FILE_UPLOAD'		=> $PhpInfo['Core']['file_uploads'][0],
				'FILE_UPLOAD_MAX'	=> $PhpInfo['Core']['upload_max_filesize'][0],
				'HTTP_ACCEPT_ENC'	=> $_SERVER["HTTP_ACCEPT_ENCODING"],
				
				'USERAUTH'		=> $implode_userauth,
				
				'I_NEWS'		=> $images['icon_news'],
				'I_EVENT'		=> $images['icon_event'],
				'I_MATCH'		=> $images['icon_match'],
				'I_TRAIN'		=> $images['icon_match'],
				'I_USERS'		=> $images['icon_user'],
				
				'U_NEWS'		=> check_sid('admin_news.php?i=1&action=news'),
				'U_MATCH'		=> check_sid('admin_match.php?i=2'),
				'U_TRAIN'		=> check_sid('admin_training.php?i=2'),
				'U_EVENT'		=> check_sid('admin_event.php?i=1'),
				'U_USERS'		=> check_sid('admin_user.php?i=4'),
			));

		break;
	}
}

$template->pparse('body');
acp_footer();

?>