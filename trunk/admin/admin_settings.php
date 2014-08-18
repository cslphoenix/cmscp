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
			'smain'		=> array('title' => 'acp_smain'),
			'ftp'		=> array('title' => 'acp_ftp'),
			'phpinfo'	=> array('title' => 'acp_phpinfo'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current	= 'acp_settings';
	
	include('./pagestart.php');
	
	add_lang('settings');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_SETTINGS;
	
#	$mode	= request('mode', TYP);
	$mode	= request('action', TYP);
	
	debug($_POST, '_POST');
	
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);

	$mode = (in_array($mode, array('default', 'calendar', 'module', 'subnavi', 'upload', 'other', 'match', 'gallery', 'smain', 'rating', 'ftp', 'phpinfo')) ) ? $mode : 'default';
	
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
			
		#	include('./page_header_admin.php');
			
			$vars = array(
				'config'	=> array(
					'tab1' => 'default',
					'server_name'			=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25'),
					'server_port'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;4'),
					'page_path'				=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25'),
					'page_name'				=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25'),
					'page_desc'				=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:25;255'),
					'page_gzip'				=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
				
					'tab2' => 'clan',
					'clan_name'				=> array('validate' => TXT, 'type' => 'text:25;25'),
					'clan_tag'				=> array('validate' => TXT, 'type' => 'text:25;25'),
					'clan_tag_show'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					
					'tab3' => 'standards',
					'default_dateformat'	=> array('validate' => TXT, 'type' => 'text:25;25'),
					'default_timezone'		=> array('validate' => TXT, 'type' => 'drop:tz'),	// select_tz($new['default_timezone'], 'default_timezone')
					'default_lang'			=> array('validate' => TXT, 'type' => 'drop:lang'),	// select_lang($new['default_lang'], 'default_lang', "language")
					'default_style'			=> array('validate' => TXT, 'type' => 'drop:style'),	// select_style($new['default_style'], 'default_style', "../templates")
					'override_user_style'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					
					'tab4' => 'maintenance',
					'page_disable'			=> array('validate' => TXT, 'type' => 'radio:yesno'),
					'page_disable_msg'		=> array('validate' => TXT, 'type' => 'textarea:25;255'),
					'page_disable_mode'		=> array('validate' => ARY,	'type' => 'drop:disable'), // page_mode_select($config['page_disable_mode'])
				),
			);
			
			if ( $submit )
			{
				$request = build_request(CONFIG, $vars, $error, $mode, 'config');
			#	$request = build_request(MENU, $vars, $error, $mode);
				
				$sql = 'SELECT * FROM ' . CONFIG;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$config_name	= $row['config_name'];
					$config_value	= $row['config_value'];
					
				#	$old[$config_name] = isset($_POST['submit']) ? str_replace("'", "\'", $config_value) : $config_value;
				#	$new[$config_name] = isset($_POST[$config_name] ) ? $_POST[$config_name] : $old[$config_name];
					
					if ( request('submit', TXT))
					{
						if ( isset($request[$config_name]) )
						{
							$new[$config_name] = ( isset($request[$config_name]) ) ? $request[$config_name] : $request[$config_name];
						}
						
						if ( isset($new[$config_name]) )
						{
							$request[$config_name] = ( is_array($request[$config_name]) ) ? serialize($request[$config_name]) : $request[$config_name];
						
							$con_name = $request[$config_name];
							$con_value = $request[$config_name];
							
							
							$sql = "UPDATE " . CONFIG . " SET config_value = '{$request[$config_name]}' WHERE config_name = '$config_name'";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error: ' . $config_name, '', __LINE__, __FILE__, $sql);
							}
						}
					}
					
					/*
				#	debug($request, 'request');
					
				#	$data[$row['config_name']] = build_request(CONFIG, $vars, $error, $mode, $row['config_name']);
					
				#	debug($data_sql);
				#	$data_sql = build_request(MENU, $vars, $error, $mode);
					/*
					$config_name	= $con['config_name'];
					$config_value	= $con['config_value'];
					
					$old[$config_name] = isset($_POST['submit'])				? str_replace("'", "\'", $config_value) : $config_value;
					$new[$config_name] = isset($_POST['config'][$config_name] )	? $_POST['config'][$config_name] : $old[$config_name];

					if ( request('submit', TXT) )
					{
						if ( isset($_POST['config'][$config_name]) )
						{
					#		$request = build_request($config_name, $vars, 'config', $error);
							
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
						debug($sql);
					#	if ( !$db->sql_query($sql) )
					#	{
					#		message(GENERAL_ERROR, 'SQL Error: ' . $config_name, '', __LINE__, __FILE__, $sql);
					#	}
					}
				*/
				}
			}
			
		#	if ( request('submit', TXT) )
		#	{
		#		$oCache->deleteCache('cfg_config');
		#		
		#		$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode"));
		#		
		#		message(GENERAL_MESSAGE, $msg);
		#	}
			
			build_output($config, $vars, $config, $mode);
			
			$template->assign_vars(array(
				'S_MODE'	=> $s_mode,
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			$template->pparse('body');
			
			break;
		
		case 'calendar':
		case 'gallery':
		case 'match':
		case 'module':
		case 'other':
		case 'rating':
		case 'subnavi':
		case 'upload':
		case 'smain':

		#	include('./page_header_admin.php');
			
			switch ( $mode )
			{
				case 'calendar':	
					
					$vars = array(
						// Kalendereinstellungen
						'calendar' => array(
							'tab1'	=> 'calendar',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:calview', 'params' => array('type', true, 'compact')),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'start'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:caldays', 'params' => array(false, true, false)),
							'compact'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
							'birthday'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'news'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'event'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'match'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'training'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						),
						// Minikalendereinstellungen
						'module_calendar' => array(
							'tab2'	=> 'module_calendar',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'start'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:caldays', 'params' => array(false, true, false)),
							'compact'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'birthday'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'news'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'event'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'match'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'training'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						),
					);
					
					break;
				
				case 'gallery':
					
					$vars = array(
						'gallery' => array(
							'tab1'	=> 'gallery',
						#	'auth_view'		=> array('validate' => ARY,	'type' => 'drop:auth_view',	),
						#	'auth_edit'		=> array('validate' => ARY,	'type' => 'drop:auth_edit'),
						#	'auth_delete'	=> array('validate' => ARY,	'type' => 'drop:auth_delete'),
						#	'auth_rate'		=> array('validate' => ARY,	'type' => 'drop:auth_rate'),
						#	'auth_upload'	=> array('validate' => ARY,	'type' => 'drop:auth_upload'),
						#	'auth'			=> array('validate' => ARY, 'type' => 'drop:auth'),
							'filesize'		=> array('validate' => INT, 'type' => 'text:4;5'),
							'dimension'		=> array('validate' => INT, 'type' => 'double:4;5'),
							'format'		=> array('validate' => INT, 'type' => 'double:4;5'),
							'preview'		=> array('validate' => INT, 'type' => 'double:4;5'),
							'acpview'		=> array('validate' => INT, 'type' => 'radio:acpview'),
						)
					);
					
					break;
					
				case 'match':
				
					$vars = array(
						'match_type' => array(
							'tab1' => 'match_type',
							'show'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:match'),
							'type_unknown'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_two'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_three'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_four'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_five'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'type_six'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							
						),
						'match_war' => array(
							'tab2' => 'match_war',
							'show'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:match'),
							'war_fun'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'war_training'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'war_clan'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
							'war_league'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('drop', 'radio')),
						),
						
						'match_league' => array(
							'tab3' => 'match_league',
							'show'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:match'),
							'league_nope'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_esl'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_sk'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_liga'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_lgz'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_te'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_xgc'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
							'league_ncsl'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25','opt' => array('url', 'drop', 'radio')),
						),
						
					);
					
					break;	
				
				case 'module':
					
					$vars = array(
						// News -> Top
						'module_news' => array(
							'tab1' => 'sn_news',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'length'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						// Match -> Top
						'module_match' => array(
							'tab2' => 'sn_match',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'length'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						// Topics -> Top
						'module_topics' => array(
							'tab3'	=> 'sn_topics',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'length'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						// Download -> Top
						'module_downloads' => array(
							'tab4' => 'sn_downloads',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'length'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						// Neue Benutzer -> Left
						'module_newusers' => array(
							'tab5' => 'sn_newusers',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'length'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						// Teams -> Left
						'module_teams' => array(
							'tab6' => 'sn_teams',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'length'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						// Links/Partner/Sponsor -> Left
						'module_network' => array(
							'tab7' => 'sn_network',
							'show_links'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'show_partner'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'show_sponsor'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'cache'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						// Stats Header: Counter/Online Navi: Counter/Online -> Left
						'module_stats' => array(
							'tab8' => 'sn_stats',
							'counter'				=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'counter_start'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'show_header_counter'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'show_header_online'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'show_navi_counter'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'show_navi_online'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						),
						// Minikalendereinstellungen -> Right
						'module_calendar' => array(
							'tab9' => 'sn_mini2',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'start'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:caldays', 'params' => array(false, true, false)),
							'bday'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'news'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'event'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'match'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'train'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						),
						// Serverviewer -> Right
						'module_server' => array(
							'tab10'	=> 'sn_server',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'length'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						// nächste Wars -> Right
						'module_next_match' => array(
							'tab11'	=> 'sn_next_match',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'length'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'period'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:period', 'params' => array(false, true, false)),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						// nächste Trainings -> Right
						'module_next_training' => array(
							'tab12'	=> 'sn_next_train',
							'show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'length'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'period'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:period', 'params' => array(false, true, false)),
							'cache'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'time'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
					);
					
					break;
					
				case 'other':
					
					$vars = array(
						'per_page_entry' => array(
							'tab' => 'per_page_entry',
							'site'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'comments'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'ucp'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'mcp'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'acp'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'index'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						'comments' => array(
							'tab' => 'comments',
							'news'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'news_guest'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'event'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'event_guest'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'match'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'match_guest'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'training'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						),
						'lobby' => array(
							'tab' => 'lobby',
							'date'				=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;15'),
							'news_limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'event_limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'match_limit'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'training_limit'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'news_future'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'event_future'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'match_future'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'training_future'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						'spam_comments' => array(
							'tab' => 'spam_comments',
							'news'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'event'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'match'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'training'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						'news' => array(
							'tab' => 'news',
							'browse'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:news'),
							'limit'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
							'words'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;5'),
						),
						'userlist' => array(
							'tab' => 'userlist',
							'groups'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
							'teams'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'mail'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
							'private'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						),
						'switch' => array(
							'tab' => 'switch',
							'event'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:switch'),
							'training'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:switch'),
							'match'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:switch'),
							'news'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:switch'),
						),
					);
					
					break;
					
				case 'rating':
					
					$vars = array(
						'rating_news' => array(
							'tab' => 'rating_news',
							'status'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_s'),
							'images'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_i'),
							'guests'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_g'),
							'number'	=> array('validate' => INT,	'explain' => true,	'type' => 'text:4;5'),
							'maximal'	=> array('validate' => INT,	'explain' => true,	'type' => 'text:4;5'),
							'fullstep'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_f'),
						),
						'rating_gallery' => array(
							'tab' => 'rating_gallery',
							'status'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_s'),
							'images'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_i'),
							'guests'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_g'),
							'number'	=> array('validate' => INT,	'explain' => true,	'type' => 'text:4;5'),
							'maximal'	=> array('validate' => INT,	'explain' => true,	'type' => 'text:4;5'),
							'fullstep'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_f'),
						),
						'rating_downloads' => array(
							'tab' => 'rating_downloads',
							'status'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_s'),
							'images'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_i'),
							'guests'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_g'),
							'number'	=> array('validate' => INT,	'explain' => true,	'type' => 'text:4;5'),
							'maximal'	=> array('validate' => INT,	'explain' => true,	'type' => 'text:4;5'),
							'fullstep'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:rate_f'),
						),
					);
					
					break;
					
				case 'subnavi':
					
					$vars = array(
						'navi_settings' => array(
							'tab1' => 'navi_top',
							'navi_top'		=> array('validate' => ARY,	'explain' => false,	'type' => 'drop:navi_top'),
							'tab2' => 'navi_left',
							'navi_left'		=> array('validate' => ARY,	'explain' => false,	'type' => 'drop:navi_left'),
							'tab3' => 'navi_right',
							'navi_right'	=> array('validate' => ARY,	'explain' => false,	'type' => 'drop:navi_right'),
							'tab4' => 'navi_modus',
							'navi_logout'	=> array('validate' => ARY,	'explain' => false,	'type' => 'select:navi_user'),
							'navi_modus'	=> array('validate' => ARY,	'explain' => false,	'type' => 'select:navi_menu'),
						),
					);
					
					break;
				
				case 'upload':
				
					$vars = array(
						'path' => array(
							'tab' => 'path',
							'games'		=> array('validate' => TXT, 'type' => 'text:25;25'),
							'icons'		=> array('validate' => TXT, 'type' => 'text:25;25'),
							'maps'		=> array('validate' => TXT, 'type' => 'text:25;25'),
							'newscat'	=> array('validate' => TXT, 'type' => 'text:25;25'),
							'ranks'		=> array('validate' => TXT, 'type' => 'text:25;25'),
							'downloads'	=> array('validate' => TXT, 'type' => 'text:25;25'),
							'gallery'	=> array('validate' => TXT, 'type' => 'text:25;25'),
						),
						'path_group' => array(
							'tab' => 'path_group',
							'path'		=> array('validate' => TXT, 'type' => 'text:25;25',),
							'filesize'	=> array('validate' => INT, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'type' => 'double:4;5'),
						),
						'path_matchs' => array(
							'tab' => 'path_matchs',
							'path'		=> array('validate' => TXT, 'type' => 'text:25;25',),
							'filesize'	=> array('validate' => INT, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'type' => 'double:4;5'),
							'preview'	=> array('validate' => INT, 'type' => 'double:4;5'),
						),
						'path_network' => array(
							'tab' => 'path_network',
							'path'		=> array('validate' => TXT, 'type' => 'text:25;25',),
							'filesize'	=> array('validate' => INT, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'type' => 'double:4;5'),
						),
						'path_users' => array(
							'tab' => 'path_users',
							'path'		=> array('validate' => TXT, 'type' => 'text:25;25',),
							'filesize'	=> array('validate' => INT, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'type' => 'double:4;5'),
						),
						'path_team_flag' => array(
							'tab' => 'path_teamflag',
							'path'		=> array('validate' => TXT, 'type' => 'text:25;25'),
							'upload'	=> array('validate' => INT, 'type' => 'radio:yesno'),
							'filesize'	=> array('validate' => INT, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'type' => 'double:4;5'),
						),
						'path_team_logo' => array(
							'tab' => 'path_team_logo',
							'path'		=> array('validate' => TXT, 'type' => 'text:25;25'),
							'upload'	=> array('validate' => INT, 'type' => 'radio:yesno'),
							'filesize'	=> array('validate' => INT, 'type' => 'text:4;5'),
							'dimension'	=> array('validate' => INT, 'type' => 'double:4;5'),
						),
					);
					
					break;
					
				case 'smain':
				
					$vars = array(
						'smain' => array(
							'tab1'	=> 'dl',
							'dl_switch'			=> array('validate' => INT, 'type' => 'radio:smain'),
							'dl_entrys'			=> array('validate' => INT, 'type' => 'radio:yesno'),
							'tab2'	=> 'profile',
							'profile_switch'	=> array('validate' => INT, 'type' => 'radio:smain'),
							'profile_entrys'	=> array('validate' => INT, 'type' => 'radio:yesno'),
							'tab3'	=> 'gallery',
							'gallery_switch'	=> array('validate' => INT, 'type' => 'radio:smain'),
							'gallery_entrys'	=> array('validate' => INT, 'type' => 'radio:yesno'),
							'tab4'	=> 'forum',
							'forum_switch'		=> array('validate' => INT, 'type' => 'radio:smain'),
							'forum_entrys'		=> array('validate' => INT, 'type' => 'radio:yesno'),
						#	'forum_subs'		=> array('validate' => INT, 'type' => 'radio:yesno'),
							'tab5'	=> 'menu',
							'menu_switch'		=> array('validate' => INT, 'type' => 'radio:smain'),
							'menu_entrys'		=> array('validate' => INT, 'type' => 'radio:yesno'),
						#	'menu_subs'			=> array('validate' => INT, 'type' => 'radio:yesno'),
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
						if ( isset($_POST[$settings_name]) )
						{
							$request = build_request($settings_name, $vars, $error, $mode, $settings_name);
						#	debug($request, 'debug data');
							$new[$settings_name] = serialize($request);
							
						#	debug($new[$settings_name]);
						}
						
						$sql = "UPDATE " . SETTINGS . " SET settings_value = '{$new[$settings_name]}' WHERE settings_name = '$settings_name'";
					#	debug($sql);
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
			
			build_output(false, $vars, $setting, $mode, true);
		#	build_output(false, $vars, $config, $mode, true);
			
			$template->assign_vars(array(
				'S_MODE'	=> $s_mode,
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			$template->pparse('body');
		
			break;
			
		case 'ftp':
		
			$template->pparse('body');
		
			break;
			
		case 'phpinfo':
		
			if ( !(request('submit', TXT)) )
			{
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

acp_footer();

?>