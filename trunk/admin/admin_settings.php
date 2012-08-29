<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_settings',
		'modes'		=> array(
			'default'	=> array('title' => 'acp_settings'),
			'calendar'	=> array('title' => 'acp_calendar'),
			'module'	=> array('title' => 'acp_module'),
			'subnavi'	=> array('title' => 'acp_subnavi'),
			'upload'	=> array('title' => 'acp_upload'),
			'other'		=> array('title' => 'acp_other'),
			'match'		=> array('title' => 'acp_match'),
			'gallery'	=> array('title' => 'acp_gallery'),
			'rating'	=> array('title' => 'acp_rating'),
			'ftp'		=> array('title' => 'acp_ftp'),
			'phpinfo'	=> array('title' => 'acp_phpinfo'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = true;
	$update = ( isset($_POST['submit']) ) ? true : false;
	
	$current	= 'acp_settings';
	
	include('./pagestart.php');
	
	add_lang('settings');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_SETTINGS;
	
#	$mode	= request('mode', TYP);
	$mode	= request('action', TYP);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);

	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	$mode = ( in_array($mode, array('default', 'calendar', 'module', 'subnavi', 'upload', 'other', 'match', 'gallery', 'rating', 'ftp', 'phpinfo')) ) ? $mode : 'default';
	
	$s_mode = '<select name="action" onkeyup="if (this.options[this.selectedIndex].value != \'\') this.form.submit();" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
	
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
		
	#	'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
#	debug($_SERVER);
#	debug($_POST);
	
	switch ( $mode )
	{
		case 'default':
			
			include('./page_header_admin.php');
			
			$display_vars = array(
				'config'	=> array(
					'title1'	=> 'default',
					'server_name'	=> array('type' => 'text:25;25',	'validate' => TXT,	'explain' => true),
					'server_port'	=> array('type' => 'text:4;4',		'validate' => INT,		'explain' => true),
					'page_path'		=> array('type' => 'text:25;25',	'validate' => TXT,	'explain' => true),
					'page_name'		=> array('type' => 'text:25;25',	'validate' => TXT,	'explain' => true),
					'page_desc'		=> array('type' => 'textarea:35:255',	'validate' => TXT),
					'page_gzip'		=> array('type' => 'radio:yesno',	'validate' => INT,		'explain' => true),
				
					'title2'	=> 'clan',
					'clan_name'		=> array('type' => 'text:25;25',	'validate' => TXT,	'explain' => true),
					'clan_tag'		=> array('type' => 'text:25;25',	'validate' => TXT,	'explain' => true),
					'clan_tag_show'	=> array('type' => 'radio:yesno',	'validate' => INT,		'explain' => true),
					
					'title3'	=> 'standards',
					'default_dateformat'	=> array('type' => 'text:25;25',	'validate' => TXT,	'explain' => true),
					'default_timezone'		=> array('type' => 'drop:tz',		'validate' => TXT,	'explain' => true),	// select_tz($new['default_timezone'], 'default_timezone')
					'default_lang'			=> array('type' => 'drop:lang',		'validate' => TXT,	'explain' => true),	// select_lang($new['default_lang'], 'default_lang', "language")
					'default_style'			=> array('type' => 'drop:style',	'validate' => TXT,	'explain' => true),	// select_style($new['default_style'], 'default_style', "../templates")
					'override_user_style'	=> array('type' => 'radio:yesno',	'validate' => INT,		'explain' => true),
					
					'title4'	=> 'maintenance',
					'page_disable'		=> array('type' => 'radio:yesno',	'validate' => TXT,	'explain' => true),
					'page_disable_msg'	=> array('type' => 'textarea:35:255',	'validate' => TXT,	'explain' => true),
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
			
			build_output($config, $display_vars, $config, 'default');
			
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
		case 'rating':
		case 'gallery':
		
			include('./page_header_admin.php');
			
			switch ( $mode )
			{
				case 'calendar':	
					
					$display_vars = array(
						// Kalendereinstellungen
						'calendar' => array(
							'tab1'	=> 'calendar',
							'show'	=> array('type' => 'radio:calview',	'validate' => INT, 'params' => array(false, true)),
							'time'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'start'	=> array('type' => 'radio:caldays',	'validate' => INT, 'params' => array(false, true)),
							'bday'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'news'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'event'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'match'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'train'	=> array('type' => 'radio:yesno',	'validate' => INT),
						),
						// Minikalendereinstellungen
						'module_calendar' => array(
							'tab2'	=> 'module_calendar',
							'show'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'start'	=> array('type' => 'radio:caldays',	'validate' => INT, 'params' => array(false, true)),
							'bday'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'news'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'event'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'match'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'train'	=> array('type' => 'radio:yesno',	'validate' => INT),
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
							'filesize'		=> array('validate' => INT, 'type' => 'text:4;5'),
							'dimension'		=> array('validate' => INT, 'type' => 'double:4;5'),
							'format'		=> array('validate' => INT, 'type' => 'double:4;5'),
							'preview'		=> array('validate' => INT, 'type' => 'double:4;5'),
							'preview_list'	=> array('validate' => INT, 'type' => 'radio:gallery'),
						)
					);
					
					break;	
				
				case 'module':
					
					$display_vars = array(
						// News -> Top
						'module_news' => array(
							'tab1'	=> 'sn_news',
							'show'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'length'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'		=> array('type' => 'text:4;5',		'validate' => INT),
						),
						// Match -> Top
						'module_match' => array(
							'tab2'	=> 'sn_match',
							'show'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'length'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'		=> array('type' => 'text:4;5',		'validate' => INT),
						),
						// Topics -> Top
						'module_topics' => array(
							'tab3'	=> 'sn_topics',
							'show'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'length'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'		=> array('type' => 'text:4;5',		'validate' => INT),
						),
						// Download -> Top
						'module_downloads' => array(
							'tab4'	=> 'sn_downloads',
							'show'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'length'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'		=> array('type' => 'text:4;5',		'validate' => INT),
						),
						// Neue Benutzer -> Left
						'module_newusers' => array(
							'tab5'	=> 'sn_newusers',
							'show'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'length'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'		=> array('type' => 'text:4;5',		'validate' => INT),
						),
						// Teams -> Left
						'module_teams' => array(
							'tab6'	=> 'sn_teams',
							'show'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'length'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'		=> array('type' => 'text:4;5',		'validate' => INT),
						),
						// Links/Partner/Sponsor -> Left
						'module_network' => array(
							'tab7'	=> 'sn_network',
							'show_links'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'show_partner'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'show_sponsor'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'cache'			=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'			=> array('type' => 'text:4;5',		'validate' => INT),
						),
						// Stats Header: Counter/Online Navi: Counter/Online -> Left
						'module_stats' => array(
							'tab8'	=> 'sn_stats',
							'counter'				=> array('type' => 'radio:yesno',	'validate' => INT),
							'counter_start'			=> array('type' => 'text:4;5',		'validate' => INT),
							'show_header_counter'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'show_header_online'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'show_navi_counter'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'show_navi_online'		=> array('type' => 'radio:yesno',	'validate' => INT),
						),
						// Minikalendereinstellungen -> Right
						'module_calendar' => array(
							'tab9'	=> 'sn_mini2',
							'show'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'start'	=> array('type' => 'radio:caldays',	'validate' => INT),
							'bday'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'news'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'event'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'match'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'train'	=> array('type' => 'radio:yesno',	'validate' => INT),
						),
						// Serverviewer -> Right
						'module_server' => array(
							'tab10'	=> 'sn_server',
							'show'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'length'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'		=> array('type' => 'text:4;5',		'validate' => INT),
						),
						// nächste Wars -> Right
						'module_next_match' => array(
							'tab11'	=> 'sn_next_match',
							'show'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'length'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'		=> array('type' => 'text:4;5',		'validate' => INT),
						),
						// nächste Trainings -> Right
						'module_next_training' => array(
							'tab12'	=> 'sn_next_train',
							'show'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'length'	=> array('type' => 'text:4;5',		'validate' => INT),
							'cache'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'time'		=> array('type' => 'text:4;5',		'validate' => INT),
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
						'path' => array(
							'tab'	=> 'path',
							'games'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15'),
							'icons'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15'),
							'maps'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15'),
							'newscat'	=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15'),
							'ranks'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15'),
							'downloads'	=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15'),
							'gallery'	=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15'),
						),
						
						'path_group' => array(
							'tab'	=> 'path_group',
							'path'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15',),
							'filesize'	=> array('validate' => INT, 'explain' => false, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'explain' => false, 'type' => 'double:4;5'),
						),
						
						'path_matchs' => array(
							'tab'	=> 'path_matchs',
							'path'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15',),
							'filesize'	=> array('validate' => INT, 'explain' => false, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'explain' => false, 'type' => 'double:4;5'),
							'preview'	=> array('validate' => INT, 'explain' => false, 'type' => 'double:4;5'),
						),
						
						'path_network' => array(
							'tab'	=> 'path_network',
							'path'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15',),
							'filesize'	=> array('validate' => INT, 'explain' => false, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'explain' => false, 'type' => 'double:4;5'),
						),
						
						'path_users' => array(
							'tab'	=> 'path_users',
							'path'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15',),
							'filesize'	=> array('validate' => INT, 'explain' => false, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'explain' => false, 'type' => 'double:4;5'),
						),
						
						'path_team_flag' => array(
							'tab'	=> 'path_teamflag',
							'path'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15',),
							'filesize'	=> array('validate' => INT, 'explain' => false, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'explain' => false, 'type' => 'double:4;5'),
							'upload'	=> array('type' => 'radio:yesno',	'validate' => INT),
						),
						
						'path_team_logo' => array(
							'tab'	=> 'path_team_logo',
							'path'		=> array('validate' => TXT, 'explain' => false, 'type' => 'text:14;15',),
							'filesize'	=> array('validate' => INT, 'explain' => false, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'explain' => false, 'type' => 'double:4;5'),
							'upload'	=> array('type' => 'radio:yesno',	'validate' => INT),
						),
					);
					
				#	debug($display_vars, 'vars');
					
					/*
					$display_vars = array(
						'path_games' => array(
							'tab'	=> 'path_games',
							'path'	=> array('type' => 'text:14;15', 'validate' => TXT)
						),
						'path_maps' => array(
							'tab'	=> 'path_maps',
							'path'	=> array('type' => 'text:14;15', 'validate' => TXT)
						),
						'path_newscat' => array(
							'tab'	=> 'path_newscat',
							'path'	=> array('type' => 'text:14;15', 'validate' => TXT)
						),
						'path_ranks' => array(
							'tab'	=> 'path_ranks',
							'path'	=> array('type' => 'text:14;15', 'validate' => TXT)
						),
						'path_downloads' => array(
							'tab'	=> 'path_downloads',
							'path'	=> array('type' => 'text:14;15', 'validate' => TXT)),
						'path_gallery' => array(
							'tab'	=> 'path_gallery',
							'path'	=> array('type' => 'text:14;15', 'validate' => TXT)),
						'path_groups' => array(
							'tab'		=> 'path_groups',
							'path'		=> array('type' => 'text:14;15',	'validate' => TXT),
							'filesize'	=> array('type' => 'text:14;15',	'validate' => INT),
							'dimension'	=> array('type' => 'double:4;5',	'validate' => INT),
						),
						'path_matchs' => array(
							'tab'	=> 'path_matchs',
							'path'		=> array('type' => 'text:14;15',	'validate' => TXT),
							'filesize'	=> array('type' => 'text:14;15',	'validate' => INT),
							'dimension'	=> array('type' => 'double:4;5',	'validate' => INT),
							'preview'	=> array('type' => 'double:4;5',	'validate' => INT),
						),
						'path_network'	=> array(
							'tab'	=> 'path_network',
							'path'		=> array('type' => 'text:14;15',	'validate' => TXT),
							'filesize'	=> array('type' => 'text:14;15',	'validate' => INT),
							'dimension'	=> array('type' => 'double:4;5',	'validate' => INT),
						),
						'path_users' => array(
							'tab'	=> 'path_users',
							'path'		=> array('type' => 'text:14;15',	'validate' => TXT),
							'filesize'	=> array('type' => 'text:14;15',	'validate' => INT),
							'dimension'	=> array('type' => 'double:4;5',	'validate' => INT),
						),
						'path_team_flag' => array(
							'tab'	=> 'path_team_flag',
							'path'		=> array('type' => 'text:14;15',	'validate' => TXT),
							'filesize'	=> array('type' => 'text:14;15',	'validate' => INT),
							'dimension'	=> array('type' => 'double:4;5',	'validate' => INT),
							'upload'	=> array('type' => 'radio:yesno',	'validate' => INT),
						),
						'path_team_logo' => array(
							'tab'	=> 'path_team_logo',
							'path'		=> array('type' => 'text:14;15',	'validate' => TXT),
							'filesize'	=> array('type' => 'text:14;15',	'validate' => INT),
							'dimension'	=> array('type' => 'double:4;5',	'validate' => INT),
							'upload'	=> array('type' => 'radio:yesno',	'validate' => INT),
						),
					);
					*/
					break;
														
				case 'other':
					
					$display_vars = array(
						'per_page_entry' => array(
							'tab'	=> 'path_games',
							'site'		=> array('type' => 'text:4;5',	'validate' => INT),
							'comments'	=> array('type' => 'text:4;5',	'validate' => INT),
							'ucp'		=> array('type' => 'text:4;5',	'validate' => INT),
							'mcp'		=> array('type' => 'text:4;5',	'validate' => INT),
							'acp'		=> array('type' => 'text:4;5',	'validate' => INT),
							'index'		=> array('type' => 'text:4;5',	'validate' => INT),
						),
						'comments' => array(
							'tab'	=> 'path_games',
							'news'			=> array('type' => 'radio:yesno',	'validate' => INT),
							'news_guest'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'event'			=> array('type' => 'radio:yesno',	'validate' => INT),
							'event_guest'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'match'			=> array('type' => 'radio:yesno',	'validate' => INT),
							'match_guest'	=> array('type' => 'radio:yesno',	'validate' => INT),
							'train'			=> array('type' => 'radio:yesno',	'validate' => INT),
						),
						'lobby' => array(
							'tab'	=> 'path_games',
							'date'			=> array('type' => 'text:15;15',	'validate' => TXT),
							'news_limit'	=> array('type' => 'text:4;5',		'validate' => INT),
							'event_limit'	=> array('type' => 'text:4;5',		'validate' => INT),
							'match_limit'	=> array('type' => 'text:4;5',		'validate' => INT),
							'train_limit'	=> array('type' => 'text:4;5',		'validate' => INT),
							'news_future'	=> array('type' => 'text:4;5',		'validate' => INT),
							'event_future'	=> array('type' => 'text:4;5',		'validate' => INT),
							'match_future'	=> array('type' => 'text:4;5',		'validate' => INT),
							'train_future'	=> array('type' => 'text:4;5',		'validate' => INT),
						),
						'spam_comments' => array(
							'tab'	=> 'path_games',
							'news'	=> array('type' => 'text:4;5',	'validate' => INT),
							'event'	=> array('type' => 'text:4;5',	'validate' => INT),
							'match'	=> array('type' => 'text:4;5',	'validate' => INT),
							'train'	=> array('type' => 'text:4;5',	'validate' => INT),
						),
						'news' => array(
							'tab'	=> 'path_games',
							'browse'	=> array('type' => 'radio:news',	'validate' => INT),
							'limit'		=> array('type' => 'text:4;5',		'validate' => INT),
							'words'		=> array('type' => 'text:4;5',		'validate' => INT),
						),
						'userlist' => array(
							'tab'	=> 'path_games',
							'groups'	=> array('type' => 'radio:yesno',	'validate' => TXT),
							'teams'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'mail'		=> array('type' => 'radio:yesno',	'validate' => INT),
							'private'	=> array('type' => 'radio:yesno',	'validate' => INT),
						),
					);
					
					break;
					
				case 'match':
				
					$display_vars = array(
						'match_type' => array(
							'tab1' => 'match_type',
							'show'			=> array('validate' => INT,	'type' => 'radio:match'),
							'type_unknown'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_two'		=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_three'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_four'		=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_five'		=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_six'		=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							
						),
						'match_war' => array(
							'tab2' => 'match_war',
							'show'			=> array('validate' => INT,	'type' => 'radio:match'),
							'war_fun'		=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'war_training'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'war_clan'		=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'war_league'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
						),
						
						'match_league' => array(
							'tab3' => 'match_league',
							'show'			=> array('validate' => INT,	'type' => 'radio:match'),
							'league_nope'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_esl'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_sk'		=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_liga'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_lgz'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_te'		=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_xgc'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_ncsl'	=> array('validate' => TXT,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
						),
						
					);
					
					break;
			}
			
		#	debug($_POST, 'post');
			
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
						if ( isset($_POST[$settings_name]) )
						{
							$request = build_request($settings_name, $display_vars, true, $error);
						#	debug($request, 'debug data');
							$new[$settings_name] = serialize($request);
							
						#	debug($new[$settings_name]);
						}
						
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
		
				$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file"));
				
				message(GENERAL_MESSAGE, $msg);
			}
			
		#	debug($setting);
			
			build_output(false, $display_vars, $setting, $mode, true);
		#	build_output(MENU2, $vars, $data_sql);
			
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