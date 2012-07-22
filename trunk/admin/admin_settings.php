<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['hm_system']['sm_settings'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= true;
	$current	= 'sm_settings';
	
	include('./pagestart.php');
	
	add_lang('settings');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_SETTINGS;
	$file	= basename(__FILE__);
	
	$mode	= request('mode', TXT) ? request('mode', TXT) : 'default';
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);

	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	$mode = ( in_array($mode, array('default', 'calendar', 'module', 'subnavi', 'upload', 'other', 'match', 'gallery', 'ftp', 'phpinfo')) ) ? $mode : 'default';
	
	$s_mode = '<select class="postselect" name="mode" onkeyup="if (this.options[this.selectedIndex].value != \'\') this.form.submit();" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
	
	$key = $value = '';
	
	foreach ( $lang['settings_option'] as $key => $value )
	{
		$selected = ( $mode == $key ) ? ' selected="selected"' : '';
		$s_mode .= "<option value=\"$key\" $selected>&raquo;&nbsp;$value&nbsp;</option>";
	}
	
	$s_mode .= '</select>';
	
	$template->set_filenames(array('body' => 'style/acp_settings.tpl'));
	
	$template->assign_block_vars($mode, array(
		$template->assign_vars(array(
			'L_HEADING'			=> $lang["site_{$mode}"],
			'L_HEADING_EXPLAIN'	=> $lang["site_{$mode}_explain"],
		)),
	));
	
	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
	
	$template->assign_vars(array(
		'L_HEAD'	=> $lang['title'],
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_PATH_PAGE'		=> select_path(),
		'S_PATH_PERMS'		=> select_perms(),
	#	'SORT'		=> $sort,
		
		'S_MODE'	=> $s_mode,
		
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
#	debug($_POST);
	
	switch ( $mode )
	{
		case 'default':
			
			include('./page_header_admin.php');
			
			$display_vars = array(
				'config'	=> array(
					'title1'	=> 'default',
					'server_name'	=> array('type' => 'text:25:25',	'validate' => 'text',	'explain' => true),
					'server_port'	=> array('type' => 'text:4:4',		'validate' => 'int',		'explain' => true),
					'page_path'		=> array('type' => 'text:25:25',	'validate' => 'text',	'explain' => true),
					'page_name'		=> array('type' => 'text:25:25',	'validate' => 'text',	'explain' => true),
					'page_desc'		=> array('type' => 'textarea:35:255',	'validate' => 'text'),
					'page_gzip'		=> array('type' => 'radio:yesno',	'validate' => 'int',		'explain' => true),
				
					'title2'	=> 'clan',
					'clan_name'		=> array('type' => 'text:25:25',	'validate' => 'text',	'explain' => true),
					'clan_tag'		=> array('type' => 'text:25:25',	'validate' => 'text',	'explain' => true),
					'clan_tag_show'	=> array('type' => 'radio:yesno',	'validate' => 'int',		'explain' => true),
					
					'title3'	=> 'standards',
					'default_dateformat'	=> array('type' => 'text:25:25',	'validate' => 'text',	'explain' => true),
					'default_timezone'		=> array('type' => 'drop:tz',		'validate' => 'text',	'explain' => true),	// select_tz($new['default_timezone'], 'default_timezone')
					'default_lang'			=> array('type' => 'drop:lang',		'validate' => 'text',	'explain' => true),	// select_lang($new['default_lang'], 'default_lang', "language")
					'default_style'			=> array('type' => 'drop:style',	'validate' => 'text',	'explain' => true),	// select_style($new['default_style'], 'default_style', "../templates")
					'override_user_style'	=> array('type' => 'radio:yesno',	'validate' => 'int',		'explain' => true),
					
					'title4'	=> 'maintenance',
					'page_disable'		=> array('type' => 'radio:yesno',	'validate' => 'text',	'explain' => true),
					'page_disable_msg'	=> array('type' => 'textarea:35:255',	'validate' => 'text',	'explain' => true),
					'page_disable_mode'	=> array('type' => 'drop:disable',	'validate' => 'array',	'explain' => true),		// page_mode_select($config['page_disable_mode'])
				),
			);
			
			$sql = 'SELECT * FROM ' . CONFIG;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			else
			{
				while ( $con = $db->sql_fetchrow($result) )
				{
					$config_name	= $con['config_name'];
					$config_value	= $con['config_value'];
					
					$old[$config_name] = isset($_POST['submit'])				? str_replace("'", "\'", $config_value) : $config_value;
					$new[$config_name] = isset($_POST['config'][$config_name] )	? $_POST['config'][$config_name] : $old[$config_name];

					if ( request('submit', TXT) )
					{
						if ( isset($_POST['config'][$config_name]) )
						{
							$request = build_request($config_name, $display_vars, 'config', $error);
							
							$new[$config_name] = $request;
							
							if ( $config_name == 'page_disable_mode' && is_array($new['page_disable_mode']) )
							{
								$new[$config_name] = implode(',', $new[$config_name]);
							}
							
							if ( $config_name == 'cookie_name' )
							{
								$new['cookie_name'] = str_replace('.', '_', $new['cookie_name']);
							}
					
							if ( $config_name == 'server_name' )
							{
								$new['server_name'] = str_replace('http://', '', $new['server_name']);
							}
						}
						
						$sql = "UPDATE " . CONFIG . " SET config_value = '" . str_replace("\'", "''", $new[$config_name]) . "' WHERE config_name = '$config_name'";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error: ' . $config_name, '', __LINE__, __FILE__, $sql);
						}
					}
				}
			}
			
			if ( request('submit', TXT) )
			{
				$oCache->deleteCache('cfg_config');
				
				$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode"));
				
				message(GENERAL_MESSAGE, $msg);
			}
			
			build_output($config, $display_vars, $mode);
			
			$template->assign_vars(array(
				'S_MODE'	=> $s_mode,
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			$template->pparse('body');
			
			break;
		
		case 'calendar':
		case 'module':
		case 'subnavi':
		case 'upload':
		case 'other':
		case 'match':
		case 'gallery':
		
			include('./page_header_admin.php');
			
			switch ( $mode )
			{
				case 'calendar':	
					
					$display_vars = array(
						// Kalendereinstellungen
						'calendar' => array(
							'tab1'	=> 'calendar',
							'show'	=> array('type' => 'radio:calview',	'validate' => 'int'),
							'time'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'start'	=> array('type' => 'radio:caldays',	'validate' => 'int'),
							'bday'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'news'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'event'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'match'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'train'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
						),
						// Minikalendereinstellungen
						'module_calendar' => array(
							'tab2'	=> 'module_calendar',
							'show'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'start'	=> array('type' => 'radio:caldays',	'validate' => 'int'),
							'bday'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'news'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'event'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'match'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'train'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
						),
					);
					
					break;
				
				case 'gallery':
					
					$display_vars = array(
						'gallery' => array(
							'tab1'	=> 'gallery',
						#	'auth_view'		=> array('validate' => ARY,	'type' => 'drop:auth_view',	),
						#	'auth_edit'		=> array('validate' => ARY,	'type' => 'drop:auth_edit'),
						#	'auth_delete'	=> array('validate' => ARY,	'type' => 'drop:auth_delete'),
						#	'auth_rate'		=> array('validate' => ARY,	'type' => 'drop:auth_rate'),
						#	'auth_upload'	=> array('validate' => ARY,	'type' => 'drop:auth_upload'),
							'auth'			=> array('validate' => ARY, 'type' => 'drop:auth'),
							'filesize'		=> array('validate' => INT, 'type' => 'text:4:5'),
							'dimension'		=> array('validate' => INT, 'type' => 'double:4:5'),
							'format'		=> array('validate' => INT, 'type' => 'double:4:5'),
							'preview'		=> array('validate' => INT, 'type' => 'double:4:5'),
							'preview_list'	=> array('validate' => INT, 'type' => 'radio:gallery'),
						)
					);
					
					break;	
				
				case 'module':
					
					$display_vars = array(
						// News -> Top
						'module_news' => array(
							'tab1'	=> 'sn_news',
							'show'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'length'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						// Match -> Top
						'module_match' => array(
							'tab2'	=> 'sn_match',
							'show'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'length'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						// Topics -> Top
						'module_topics' => array(
							'tab3'	=> 'sn_topics',
							'show'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'length'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						// Download -> Top
						'module_downloads' => array(
							'tab4'	=> 'sn_downloads',
							'show'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'length'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						// Neue Benutzer -> Left
						'module_newusers' => array(
							'tab5'	=> 'sn_newusers',
							'show'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'length'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						// Teams -> Left
						'module_teams' => array(
							'tab6'	=> 'sn_teams',
							'show'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'length'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						// Links/Partner/Sponsor -> Left
						'module_network' => array(
							'tab7'	=> 'sn_network',
							'show_links'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'show_partner'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'show_sponsor'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'cache'			=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'			=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						// Stats Header: Counter/Online Navi: Counter/Online -> Left
						'module_stats' => array(
							'tab8'	=> 'sn_stats',
							'counter'				=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'counter_start'			=> array('type' => 'text:4:5',		'validate' => 'int'),
							'show_header_counter'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'show_header_online'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'show_navi_counter'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'show_navi_online'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
						),
						// Minikalendereinstellungen -> Right
						'module_calendar' => array(
							'tab9'	=> 'sn_mini2',
							'show'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'start'	=> array('type' => 'radio:caldays',	'validate' => 'int'),
							'bday'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'news'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'event'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'match'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'train'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
						),
						// Serverviewer -> Right
						'module_server' => array(
							'tab10'	=> 'sn_server',
							'show'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'length'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						// nächste Wars -> Right
						'module_next_match' => array(
							'tab11'	=> 'sn_next_match',
							'show'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'length'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						// nächste Trainings -> Right
						'module_next_training' => array(
							'tab12'	=> 'sn_next_train',
							'show'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'length'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'time'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
					);
					
					break;
					
				case 'subnavi':
					
					$display_vars = array(
						'navi_settings' => array(
							'tab1' => 'navi_top',
							'navi_top' => array('type' => 'drop:navi_top',		'validate' => 'array'),
							'tab2' => 'navi_left',
							'navi_left' => array('type' => 'drop:navi_left',	'validate' => 'array'),
							'tab3' => 'navi_right',
							'navi_right' => array('type' => 'drop:navi_right',	'validate' => 'array'),
						),
					);
					
					break;
				
				case 'upload':
					
					$display_vars = array(
						'path_games' => array(
							'tab'	=> 'path_games',
							'path'	=> array('type' => 'text:14:15', 'validate' => 'text')
						),
						'path_maps' => array(
							'tab'	=> 'path_maps',
							'path'	=> array('type' => 'text:14:15', 'validate' => 'text')
						),
						'path_newscat' => array(
							'tab'	=> 'path_newscat',
							'path'	=> array('type' => 'text:14:15', 'validate' => 'text')
						),
						'path_ranks' => array(
							'tab'	=> 'path_ranks',
							'path'	=> array('type' => 'text:14:15', 'validate' => 'text')
						),
						'path_downloads' => array(
							'tab'	=> 'path_downloads',
							'path'	=> array('type' => 'text:14:15', 'validate' => 'text')),
						'path_gallery' => array(
							'tab'	=> 'path_gallery',
							'path'	=> array('type' => 'text:14:15', 'validate' => 'text')),
						'path_groups' => array(
							'tab'		=> 'path_groups',
							'path'		=> array('type' => 'text:14:15',	'validate' => 'text'),
							'filesize'	=> array('type' => 'text:14:15',	'validate' => 'int'),
							'dimension'	=> array('type' => 'double:4:5',	'validate' => 'int'),
						),
						'path_matchs' => array(
							'tab'	=> 'path_matchs',
							'path'		=> array('type' => 'text:14:15',	'validate' => 'text'),
							'filesize'	=> array('type' => 'text:14:15',	'validate' => 'int'),
							'dimension'	=> array('type' => 'double:4:5',	'validate' => 'int'),
							'preview'	=> array('type' => 'double:4:5',	'validate' => 'int'),
						),
						'path_network'	=> array(
							'tab'	=> 'path_network',
							'path'		=> array('type' => 'text:14:15',	'validate' => 'text'),
							'filesize'	=> array('type' => 'text:14:15',	'validate' => 'int'),
							'dimension'	=> array('type' => 'double:4:5',	'validate' => 'int'),
						),
						'path_users' => array(
							'tab'	=> 'path_users',
							'path'		=> array('type' => 'text:14:15',	'validate' => 'text'),
							'filesize'	=> array('type' => 'text:14:15',	'validate' => 'int'),
							'dimension'	=> array('type' => 'double:4:5',	'validate' => 'int'),
						),
						'path_team_flag' => array(
							'tab'	=> 'path_team_flag',
							'path'		=> array('type' => 'text:14:15',	'validate' => 'text'),
							'filesize'	=> array('type' => 'text:14:15',	'validate' => 'int'),
							'dimension'	=> array('type' => 'double:4:5',	'validate' => 'int'),
							'upload'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
						),
						'path_team_logo' => array(
							'tab'	=> 'path_team_logo',
							'path'		=> array('type' => 'text:14:15',	'validate' => 'text'),
							'filesize'	=> array('type' => 'text:14:15',	'validate' => 'int'),
							'dimension'	=> array('type' => 'double:4:5',	'validate' => 'int'),
							'upload'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
						),
					);
					
					break;
														
				case 'other':
					
					$display_vars = array(
						'per_page_entry' => array(
							'tab'	=> 'path_games',
							'site'		=> array('type' => 'text:4:5',	'validate' => 'int'),
							'comments'	=> array('type' => 'text:4:5',	'validate' => 'int'),
							'ucp'		=> array('type' => 'text:4:5',	'validate' => 'int'),
							'mcp'		=> array('type' => 'text:4:5',	'validate' => 'int'),
							'acp'		=> array('type' => 'text:4:5',	'validate' => 'int'),
							'index'		=> array('type' => 'text:4:5',	'validate' => 'int'),
						),
						'comments' => array(
							'tab'	=> 'path_games',
							'news'			=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'news_guest'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'event'			=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'event_guest'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'match'			=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'match_guest'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'train'			=> array('type' => 'radio:yesno',	'validate' => 'int'),
						),
						'lobby' => array(
							'tab'	=> 'path_games',
							'date'			=> array('type' => 'text:15:15',	'validate' => 'text'),
							'news_limit'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'event_limit'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'match_limit'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'train_limit'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'news_future'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'event_future'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'match_future'	=> array('type' => 'text:4:5',		'validate' => 'int'),
							'train_future'	=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						'spam_comments' => array(
							'tab'	=> 'path_games',
							'news'	=> array('type' => 'text:4:5',	'validate' => 'int'),
							'event'	=> array('type' => 'text:4:5',	'validate' => 'int'),
							'match'	=> array('type' => 'text:4:5',	'validate' => 'int'),
							'train'	=> array('type' => 'text:4:5',	'validate' => 'int'),
						),
						'news' => array(
							'tab'	=> 'path_games',
							'browse'	=> array('type' => 'radio:news',	'validate' => 'int'),
							'limit'		=> array('type' => 'text:4:5',		'validate' => 'int'),
							'words'		=> array('type' => 'text:4:5',		'validate' => 'int'),
						),
						'userlist' => array(
							'tab'	=> 'path_games',
							'groups'	=> array('type' => 'radio:yesno',	'validate' => 'text'),
							'teams'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'mail'		=> array('type' => 'radio:yesno',	'validate' => 'int'),
							'private'	=> array('type' => 'radio:yesno',	'validate' => 'int'),
						),
					);
					
					break;
					
				case 'match':
				
					$display_vars = array(
						'match_type2' => array(
							'title1' => 'match_type',
							'show'			=> array('validate' => 'int',	'type' => 'radio:match'),
							'type_unknown'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
							'type_two'		=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
							'type_three'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
							'type_four'		=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
							'type_five'		=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
							'type_six'		=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
							
						),
						'match_war2' => array(
							'title2' => 'match_war',
							'show'			=> array('validate' => 'int',	'type' => 'radio:match'),
							'war_fun'		=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
							'war_training'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
							'war_clan'		=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
							'war_league'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('drop', 'radio')),
						),
						'match_league2' => array(
							'title3' => 'match_league',
							'show'			=> array('validate' => 'int',	'type' => 'radio:match'),
							'league_nope'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('url', 'drop', 'radio')),
							'league_esl'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('url', 'drop', 'radio')),
							'league_sk'		=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('url', 'drop', 'radio')),
							'league_liga'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('url', 'drop', 'radio')),
							'league_lgz'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('url', 'drop', 'radio')),
							'league_te'		=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('url', 'drop', 'radio')),
							'league_xgc'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('url', 'drop', 'radio')),
							'league_ncsl'	=> array('validate' => 'text',	'type' => 'text:15:25','opt' => array('url', 'drop', 'radio')),
						),
						
					);
					
					break;
			}
			
			$sql = 'SELECT * FROM ' . SETTINGS;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			else
			{
				while ( $set = $db->sql_fetchrow($result) )
				{
					$settings_name	= $set['settings_name'];
					$settings_value	= $set['settings_value'];
					
					$old[$settings_name] = isset($_POST['submit']) ? str_replace("'", "\'", $settings_value) : $settings_value;
					$new[$settings_name] = isset($_POST[$settings_name] ) ? $_POST[$settings_name] : $old[$settings_name];
					
					if ( request('submit', TXT))
					{
						if ( isset($_POST[$settings_name]) && !in_array($settings_name, array('match_war', 'match_type', 'match_league')) )
						{
							$request = build_request($settings_name, $display_vars, true, $error);
					#		debug($new[$settings_name]);
							$new[$settings_name] = serialize($request);
						}
						
						/*
						if ( isset($_POST[$settings_name]) && in_array($settings_name, array('match_war', 'match_type', 'match_league')) )
					#	if ( isset($_POST[$settings_name]) && in_array($settings_name, array('match_war')) )
						{
							
							$request = request($settings_name, 4);
							$tmp_old = unserialize($old[$settings_name]);
							
							$i = 1;
							$ary_new = '';
							
							debug($request);
							
							foreach ( $request as $key => $row )
							{
								$nkey = "{$settings_name}_key";
								$sdef = "{$settings_name}_default";
								
								$keys = isset($_POST[$nkey][$key]) ? $_POST[$nkey][$key] : $_POST[$nkey]['new_key'];
								
								if ( $keys != '' )
								{
									$ary_new[$keys]['type']		= isset($tmp_old[$key]['type']) ? $tmp_old[$key]['type'] : 'input';
									$ary_new[$keys]['order']	= isset($tmp_old[$key]['order']) ? $tmp_old[$key]['order'] : $i*10;
									$ary_new[$keys]['value']	= isset($_POST[$key]) ? $_POST[$key] : $row;
									$ary_new[$keys]['default']	= isset($_POST[$sdef]) ? ( $keys == $_POST[$sdef] ) ? 1 : 0 : 0;
									
									if ( $settings_name == 'match_league' && isset($tmp_old[$key]['url']) )
									{
										$nurl = "{$settings_name}_url";
										
										$keyu = isset($_POST[$nurl][$key]) ? $_POST[$nurl][$key] : $_POST[$nurl]['new_url'];
										
										$ary_new[$keys]['url']['type']	= 'input';
										$ary_new[$keys]['url']['value']	= $keyu;
									}
								}
								
								$i++;
							}
							
							$new[$settings_name] = serialize($ary_new);
						}
					*/
					/*	
						if ( isset($_POST[$settings_name]) && in_array($settings_name, array('cal2', 'cal_mini2', 'gallery2')) )
					#	if ( isset($_POST[$settings_name]) && in_array($settings_name, array('match_war')) )
						{
					#		debug($_POST[$settings_name], false, 'post');
							$request = request($settings_name, 4);
							$tmp_old = unserialize($old[$settings_name]);
							$ary = '';
							
							foreach ( $request as $key => $row )
							{
								$ary[$key] = ( !is_array($row) ) ? ( strpos($key, 'filesize') !== false ) ? ($row*1048576) : $row : "{$row[0]}:{$row[1]}";
							}
							
							$new[$settings_name] = serialize($ary);
						}
					*/	
						$sql = "UPDATE " . SETTINGS . " SET settings_value = '{$new[$settings_name]}' WHERE settings_name = '$settings_name'";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error: ' . $settings_name, '', __LINE__, __FILE__, $sql);
						}
					}
					else
					{
						$setting[$set['settings_name']] = $set['settings_value'];
					}
				}
			}
			
			if ( request('submit', TXT) )
			{
				$oCache->deleteCache('cfg_setting');
		
				$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode"));
				
				message(GENERAL_MESSAGE, $msg);
			}
			
			build_output($setting, $display_vars, $mode, true);
			
			$template->assign_vars(array(
				'S_MODE'	=> $s_mode,
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			$template->pparse('body');
		
			break;
			
		case 'ftp':
		
			include('./page_header_admin.php');
			
			$template->pparse('body');
		
			break;
			
		case 'phpinfo':
		
			if ( !(request('submit', TXT)) )
			{
				include('./page_header_admin.php');
				
				$phpinfo = parsePHPInfo();
				
				$core = ( $_SERVER['HTTP_HOST'] == 'localhost' ) ? 'Core' : 'PHP Core';
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				
				$template->assign_vars(array(
					'L_SUPPORT_COMMON'	=> $lang['support_common'],
					'L_SUPPORT_VERSION'	=> $lang['support_version'],
					'L_SUPPORT_SERVER'	=> $lang['support_server'],
					'L_VERSION'			=> $lang['version'],
					'L_DOMAIN'			=> $lang['domain'],
					'L_BROWSER'			=> $lang['browser'],
					'L_SERVER_OS'		=> $lang['server_os'],
					'L_SERVER_APACHE'	=> $lang['server_apache'],
					'L_SERVER_PHP'		=> $lang['server_php'],
					'L_SERVER_SQL'		=> $lang['server_sql'],
					'L_OPTION_A'		=> $lang['info_option_a'],
					'L_OPTION_B'		=> $lang['info_option_b'],
					'L_OPTION_C'		=> $lang['info_option_c'],
					'L_OPTION_D'		=> $lang['info_option_d'],
					'L_OPTION_E'		=> $lang['info_option_e'],
					'L_OPTION_F'		=> $lang['info_option_f'],
					'L_OPTION_G'		=> $lang['info_option_g'],
					'L_OPTION_H'		=> $lang['info_option_h'],
					'L_OPTION_I'		=> $lang['info_option_i'],
					'L_OPTION_J'		=> $lang['info_option_j'],
					
					'VERSION'			=> $config['page_version'],
					'DOMAIN'			=> $_SERVER['HTTP_HOST'].str_replace('/admin','/',dirname($_SERVER['PHP_SELF'])),
					'BROWSER'			=> $_SERVER['HTTP_USER_AGENT'],
					'SERVER_OS'			=> @php_uname(),
					'SERVER_APACHE'		=> $phpinfo['apache2handler']['Apache Version'],
					'SERVER_PHP'		=> phpversion(),
					'SERVER_SQL'		=> mysql_get_server_info(),
					'OPTION_A'			=> function_exists('fopen') == true ? 'On' : 'Off',
					'OPTION_B'			=> function_exists('fsockopen') == true? 'On' : 'Off',
					'OPTION_C'			=> $phpinfo[$core]['register_globals']['0'],
					'OPTION_D'			=> $phpinfo[$core]['safe_mode']['0'],
					'OPTION_E'			=> $phpinfo['gd']['GD Support'],
					'OPTION_F'			=> $phpinfo['gd']['GD Version'],
					'OPTION_G'			=> $phpinfo[$core]['magic_quotes_gpc']['0'],
					'OPTION_H'			=> $phpinfo[$core]['file_uploads']['0'],
					'OPTION_I'			=> $phpinfo[$core]['upload_max_filesize']['0'],
					'OPTION_J'			=> $_SERVER['HTTP_ACCEPT_ENCODING'],
					
					'L_SUBMIT'			=> $lang['save_as'],
					
					'S_MODE'	=> $s_mode,
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse("body");
				
				break;
			}
			
			$phpinfo = parsePHPInfo();
			
			$core = ( $_SERVER['HTTP_HOST'] == 'localhost' ) ? 'Core' : 'PHP Core';
			
			$func_fopen = ( function_exists('fopen') == 1 ) ? 'On' : 'Off';
			$func_fsock = ( function_exists('fsockopen') == 1 ) ? 'On' : 'Off';
			
			header("Pragma: no-cache");
			header("Content-Type: text/x-delimtext; name=\"phpinfo.txt\"");
			header("Content-disposition: attachment; filename=phpinfo.txt");
			
			echo "/**********************************************";
			echo "\n * " . $lang['support_common'];
			echo "\n **********************************************";
			echo "\n * " . $lang['version'] . " " . $config['page_version'];
			echo "\n * " . $lang['domain'] . " " . $_SERVER['HTTP_HOST'].str_replace('/admin','/',dirname($_SERVER['PHP_SELF']));
			echo "\n * " . $lang['browser'] . " " . $_SERVER['HTTP_USER_AGENT'];
			echo "\n **********************************************";
			echo "\n * " . $lang['support_version'];
			echo "\n **********************************************";
			echo "\n * " . $lang['server_os'] . " " . @php_uname();
			echo "\n * " . $lang['server_apache'] . " " . $phpinfo['apache2handler']['Apache Version'];
			echo "\n * " . $lang['server_php'] . " " . phpversion();
			echo "\n * " . $lang['server_sql'] . " " . mysql_get_server_info();
			echo "\n **********************************************";
			echo "\n * " . $lang['support_server'];
			echo "\n **********************************************";
			echo "\n * " . $lang['info_option_a'] . " " . $func_fopen;
			echo "\n * " . $lang['info_option_b'] . " " . $func_fsock;
			echo "\n * " . $lang['info_option_c'] . " " . $phpinfo[$core]['register_globals']['0'];
			echo "\n * " . $lang['info_option_d'] . " " . $phpinfo[$core]['safe_mode']['0'];
			echo "\n * " . $lang['info_option_e'] . " " . $phpinfo['gd']['GD Support'];
			echo "\n * " . $lang['info_option_f'] . " " . $phpinfo['gd']['GD Version'];
			echo "\n * " . $lang['info_option_g'] . " " . $phpinfo[$core]['magic_quotes_gpc']['0'];
			echo "\n * " . $lang['info_option_h'] . " " . $phpinfo[$core]['file_uploads']['0'];
			echo "\n * " . $lang['info_option_i'] . " " . $phpinfo[$core]['upload_max_filesize']['0'];
			echo "\n * " . $lang['info_option_j'] . " " . $_SERVER['HTTP_ACCEPT_ENCODING'];
			echo "\n **********************************************/";
			exit;
		
		break;
	}
}

include('./page_footer_admin.php');

?>