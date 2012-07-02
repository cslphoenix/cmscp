<?php

define('IN_CMS', true);

$current = 'sm_index';

include('./pagestart.php');

add_lang('index');

$num	= request('num', INT);
$mode	= request('mode', TXT);
$index	= '';

$file	= basename(__FILE__);

$template->set_filenames(array(
	'body'	=> 'style/acp_index.tpl',
	'error'	=> 'style/info_error.tpl',
));

$mode = ( in_array($mode, array('switch')) ) ? $mode : '';

switch ( $mode )
{
	case 'switch':
	
		$data = data(NEWS, $num, false, 1, true);
		
		$switch = ( $data['news_public'] ) ? 0 : 1;
		
		sql(NEWS, 'update', array('news_public' => $switch), 'news_id', $num);
		
		$oCache->deleteCache('dsp_news');
		
		log_add(LOG_ADMIN, SECTION_NEWS, $mode);
	
	default:
	
		if ( request('vchk', 1) )
		{
			$oCache->deleteCache('version_cms');
		}
		
		if ( request('delc', 1) )
		{
			$oCache->truncateCache();
		}
	
		$url_news	= POST_NEWS;
		$url_event	= POST_EVENT;
		$url_match	= POST_MATCH;
		$url_train	= POST_TRAINING;
		$url_user	= POST_USER;
		
		$news	= data(NEWS, false, 'news_time_public DESC, news_id DESC', 1, false);
		$event	= data(EVENT, "WHERE event_date > " . time(), 'event_date ASC', 1, false);
		$match	= data(MATCH, false, 'match_date DESC', 4, false);
		$train	= data(TRAINING, "WHERE training_date > " . time(), 'training_date DESC', 0, false);
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
				$typ	= $news[$i]['news_intern'] ? 'sprintf_intern' : 'sprintf_normal';
				$title	= sprintf($lang[$typ], $news[$i]['news_title']);
				$public	= $news[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
				
				$template->assign_block_vars('news_row', array(
					'TITLE'		=> ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', 'admin_news.php', array('mode' => 'update', $url_news => $id), $title, $title) : $title : $title,
					
					'DATE'		=> create_date($userdata['user_dateformat'], $news[$i]['news_time_public'], $userdata['user_timezone']),
					
					'PUBLIC'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] )	? href('a_txt', 'index.php', array('mode' => 'switch', 'num' => $id), $public, '') : img('i_icon', 'icon_news_denied', ''),
					'UPDATE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', 'admin_news.php', array('mode' => 'update', $url_news => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
					'DELETE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', 'admin_news.php', array('mode' => 'delete', $url_news => $id, 'acp_main' => 1), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
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
					'TITLE'		=> ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] ) ? href('a_txt', 'admin_event.php', array('mode' => 'update', $url_event => $id), $title, $title) : $title,
					
					'LEVEL'		=> $level,
					'DATE'		=> create_date($userdata['user_dateformat'], $event[$i]['event_date'], $config['default_timezone']),
					
					'UPDATE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] ) ? href('a_img', 'admin_event.php', array('mode' => 'update', $url_event => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
					'DELETE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] ) ? href('a_img', 'admin_event.php', array('mode' => 'delete', $url_event => $id, 'acp_main' => 1), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
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
					'RIVAL'		=> ( $userdata['user_level'] == ADMIN || $userauth['auth_match'] ) ? href('a_txt', 'admin_match.php', array('mode' => 'update', $url_match => $id), $rival, $rival) : $rival,
					
					'GAME'		=> display_gameicon($match[$i]['game_image']),
					'DATE'		=> create_date($userdata['user_dateformat'], $match[$i]['match_date'], $userdata['user_timezone']),
					
					'DETAIL'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_match'] ) ? href('a_img', 'admin_match.php', array('mode' => 'detail', $url_match => $id), 'icon_details', 'common_details') : img('i_icon', 'icon_details2', 'common_details'),
					'UPDATE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_match'] ) ? href('a_img', 'admin_match.php', array('mode' => 'update', $url_match => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
					'DELETE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_match'] ) ? href('a_img', 'admin_match.php', array('mode' => 'delete', $url_match => $id, 'acp_main' => 1), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
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
					'VS'		=> ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] ) ? href('a_txt', 'admin_training.php', array('mode' => 'update', $url_train => $id), $vs, $vs) : $vs,
					
					'DATE'		=> create_date($userdata['user_dateformat'], $train[$i]['training_date'], $userdata['user_timezone']),
					
					'UPDATE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] ) ? href('a_img', 'admin_training.php', array('mode' => 'update', $url_train => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
					'DELETE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] ) ? href('a_img', 'admin_training.php', array('mode' => 'delete', $url_train => $id, 'acp_main' => 1), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
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
					'NAME'		=> ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] ) ? href('a_txt', 'admin_user.php', array('mode' => 'update', $url_user => $id), $name, $name) : $name,
					
					'LEVEL'		=> $level,
					'REGDATE'	=> create_date($userdata['user_dateformat'], $users[$i]['user_regdate'], $config['default_timezone']),
					
					'AUTH'		=> ( $userdata['user_level'] == ADMIN || $userauth['auth_user'] ) ? href('a_img', 'admin_user.php', array('mode' => 'auth', $url_user => $id), 'icon_user_auth', '') : img('i_icon', 'icon_user_auth2', ''),
					'UPDATE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_user'] ) ? href('a_img', 'admin_user.php', array('mode' => 'update', $url_user => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
					'DELETE'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_user'] ) ? href('a_img', 'admin_user.php', array('mode' => 'delete', $url_user => $id, 'acp_main' => 1), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
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
		$lastest_version = implode('.', $lastest_checked);
		
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
	
		$template->assign_vars(array(
			'L_WELCOME'	=> $lang['index'],
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
			'L_SIZE_MATCH'		=> $lang['path_matchs'],
			'L_SIZE_USER'		=> $lang['path_users'],
			
			'L_NEWS'		=> $lang['sm_news'],
			'L_EVENT'		=> $lang['sm_event'],
			'L_MATCH'		=> $lang['sm_match'],
			'L_TRAIN'		=> $lang['sm_training'],
			'L_USERS'		=> $lang['sm_users'],
			
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
			'SIZE_MATCH'		=> size_dir2($settings['path_matchs']['path']),
			'SIZE_USER'			=> size_dir2($settings['path_users']['path']),
			
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
	
		$template->pparse('body');
	
	break;
}
	
if ( $index != true )
{
	include('./page_footer_admin.php');
	exit;
}

include('./page_footer_admin.php');

?>