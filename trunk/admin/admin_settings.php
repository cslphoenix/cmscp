<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_01_main']['_submenu_settings'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);

	$root_path	= './../';
	$current	= '_submenu_settings';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('settings');
	
	$sort = ( request('sort', 1) ) ? request('sort', 1) : '_default';

	if ( $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	function _select_path($default = '')
	{
		global $root_path, $settings;
		
		$paths = array (
			$settings['path_games'],
			$settings['path_ranks'],
			$settings['path_team_logo'],
			$settings['path_team_logos'],
			$settings['path_match_picture'],
		);
		
		$select_path = '';
		$select_path .= '<select name="path" class="post">';
		foreach ($paths as $path)
		{
			$status = is_writable($root_path . $path) ? 'ja ' : 'nein ';
			$selected = ( $path == $default ) ? ' selected="selected"' : '';
			$select_path .= '<option value="' . $path . '" ' . $selected . '>&raquo; ' . $status . $path . '&nbsp;</option>';
		}
		$select_path .= '</select>';
		
		return $select_path;	
	}
	
	$s_sort = '<select class="postselect" name="sort" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
	
	foreach ( $lang['settings_option'] as $key => $value )
	{
		$selected = ( $sort == $key ) ? ' selected="selected"' : '';
		$s_sort .= '<option value="' . $key . '"' . $selected . '>&raquo;&nbsp;' . $value . '&nbsp;</option>';
	}
	
	$s_sort .= '</select>';
	
	$template->set_filenames(array('body' => 'style/acp_settings.tpl'));
	$template->assign_block_vars($sort, array());
	
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
			
			$old[$config_name] = ( isset($_POST['submit']) )		? str_replace("'", "\'", $config_value) : $config_value;
			$new[$config_name] = ( isset($_POST[$config_name]) )	? $_POST[$config_name] : $old[$config_name];

			if ( $config_name == 'cookie_name' )
			{
				$new['cookie_name'] = str_replace('.', '_', $new['cookie_name']);
			}
	
			if ( $config_name == 'server_name' )
			{
				$new['server_name'] = str_replace('http://', '', $new['server_name']);
			}
	
#			if ($config_name == 'avatar_path')
#			{
#				$new['avatar_path'] = trim($new['avatar_path']);
#				if (strstr($new['avatar_path'], "\0") || !is_dir($phpbb_root_path . $new['avatar_path']) || !is_writable($phpbb_root_path . $new['avatar_path']))
#				{
#					$new['avatar_path'] = $old['avatar_path'];
#				}
#			}

			if ( isset($_POST['submit']) )
			{
				if ( $config_name == 'page_disable_mode' && is_array($new['page_disable_mode']) )
				{
					$new[$config_name] = implode(',', $new[$config_name]);
				}
				
				$sql = "UPDATE " . CONFIG . " SET config_value = '" . str_replace("\'", "''", $new[$config_name]) . "' WHERE config_name = '$config_name'";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error: ' . $config_name, '', __LINE__, __FILE__, $sql);
				}
			}
		}
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
			
			$old[$settings_name] = ( isset($_POST['submit']) )		? str_replace("'", "\'", $settings_value) : $settings_value;
			$new[$settings_name] = ( isset($_POST[$settings_name]) )	? $_POST[$settings_name] : $old[$settings_name];

			if ( isset($_POST['submit']) )
			{
				$sql = "UPDATE " . SETTINGS . " SET settings_value = '" . str_replace("\'", "''", $new[$settings_name]) . "' WHERE settings_name = '$settings_name'";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error: ' . $settings_name, '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}
	
	if ( isset($_POST['submit']) )
	{
		#$oCache -> sCachePath = './../cache/';
		#$oCache -> deleteCache('config');
		#$oCache -> deleteCache('settings');

		$message = $lang['Config_updated'] . sprintf($lang['click_return_set'], '<a href="' . append_sid('admin_settings.php') . '">', '</a>');
		message(GENERAL_MESSAGE, $message);
	}
	
//	$style_select = style_select($new['default_style'], 'default_style', "../templates");
//	$lang_select = language_select($new['default_lang'], 'default_lang', "language");
//	$timezone_select = tz_select($new['page_timezone'], 'page_timezone');
	
	
/*	
	$html_tags = $new['allow_html_tags'];
	
	$override_user_style_yes = ( $new['override_user_style'] ) ? 'checked="checked"' : "";
	$override_user_style_no = ( !$new['override_user_style'] ) ? 'checked="checked"' : "";
	
	$html_yes = ( $new['allow_html'] ) ? 'checked="checked"' : "";
	$html_no = ( !$new['allow_html'] ) ? 'checked="checked"' : "";
	
	$bbcode_yes = ( $new['allow_bbcode'] ) ? 'checked="checked"' : "";
	$bbcode_no = ( !$new['allow_bbcode'] ) ? 'checked="checked"' : "";
	
	$activation_none = ( $new['require_activation'] == USER_ACTIVATION_NONE ) ? 'checked="checked"' : "";
	$activation_user = ( $new['require_activation'] == USER_ACTIVATION_SELF ) ? 'checked="checked"' : "";
	$activation_admin = ( $new['require_activation'] == USER_ACTIVATION_ADMIN ) ? 'checked="checked"' : "";
	
	$confirm_yes = ($new['enable_confirm']) ? 'checked="checked"' : '';
	$confirm_no = (!$new['enable_confirm']) ? 'checked="checked"' : '';
	
	$allow_autologin_yes = ($new['allow_autologin']) ? 'checked="checked"' : '';
	$allow_autologin_no = (!$new['allow_autologin']) ? 'checked="checked"' : '';
	
	$board_email_form_yes = ( $new['board_email_form'] ) ? 'checked="checked"' : "";
	$board_email_form_no = ( !$new['board_email_form'] ) ? 'checked="checked"' : "";
	
	$gzip_yes = ( $new['gzip_compress'] ) ? 'checked="checked"' : "";
	$gzip_no = ( !$new['gzip_compress'] ) ? 'checked="checked"' : "";
	
	$privmsg_on = ( !$new['privmsg_disable'] ) ? 'checked="checked"' : "";
	$privmsg_off = ( $new['privmsg_disable'] ) ? 'checked="checked"' : "";
	
	$prune_yes = ( $new['prune_enable'] ) ? 'checked="checked"' : "";
	$prune_no = ( !$new['prune_enable'] ) ? 'checked="checked"' : "";
	
	$smile_yes = ( $new['allow_smilies'] ) ? 'checked="checked"' : "";
	$smile_no = ( !$new['allow_smilies'] ) ? 'checked="checked"' : "";
	
	$sig_yes = ( $new['allow_sig'] ) ? 'checked="checked"' : "";
	$sig_no = ( !$new['allow_sig'] ) ? 'checked="checked"' : "";
	
	$namechange_yes = ( $new['allow_namechange'] ) ? 'checked="checked"' : "";
	$namechange_no = ( !$new['allow_namechange'] ) ? 'checked="checked"' : "";
	
	$avatars_local_yes = ( $new['allow_avatar_local'] ) ? 'checked="checked"' : "";
	$avatars_local_no = ( !$new['allow_avatar_local'] ) ? 'checked="checked"' : "";
	$avatars_remote_yes = ( $new['allow_avatar_remote'] ) ? 'checked="checked"' : "";
	$avatars_remote_no = ( !$new['allow_avatar_remote'] ) ? 'checked="checked"' : "";
	$avatars_upload_yes = ( $new['allow_avatar_upload'] ) ? 'checked="checked"' : "";
	$avatars_upload_no = ( !$new['allow_avatar_upload'] ) ? 'checked="checked"' : "";
	
	$smtp_yes = ( $new['smtp_delivery'] ) ? 'checked="checked"' : "";
	$smtp_no = ( !$new['smtp_delivery'] ) ? 'checked="checked"' : "";
*/	
	//
	// Escape any quotes in the site description for proper display in the text
	// box on the admin page 
	//
	$config['page_desc'] = str_replace('"', '&quot;', $config['page_desc']);
	$config['page_name'] = str_replace('"', '&quot;', strip_tags($config['page_name']));
	
	$template->assign_vars(array(
								 
		'L_HEAD'				=> $lang['set_head'],
		'L_EXPLAIN'				=> $lang['set_explain'],
								 
		'L_DEFAULT'					=> $lang['site_default'],
		'L_DEFAULT_EXPLAIN'			=> $lang['site_default_explain'],
		'L_UPLOAD'					=> $lang['site_upload'],
		'L_UPLOAD_EXPLAIN'			=> $lang['site_upload_explain'],
		'L_SESSION'					=> $lang['site_session'],
		'L_SESSION_EXPLAIN'			=> $lang['site_session_explain'],
		
		'L_SERVER_NAME'				=> $lang['server_name'],
		'L_SERVER_NAME_EXPLAIN'		=> $lang['server_name_explain'],
		'L_SERVER_PORT'				=> $lang['server_port'],
		'L_SERVER_PORT_EXPLAIN'		=> $lang['server_port_explain'],
		'L_PAGE_PATH'				=> $lang['page_path'],
		'L_PAGE_PATH_EXPLAIN'		=> $lang['page_path_explain'],
		'L_PAGE_NAME'				=> $lang['page_name'],
		'L_PAGE_NAME_EXPLAIN'		=> $lang['page_name_explain'],
		'L_PAGE_DESC'				=> $lang['page_desc'],
		'L_DISABLE_PAGE'			=> $lang['disable_page'],
		'L_DISABLE_PAGE_MODE'		=> $lang['disable_page_mode'],
		'L_DISABLE_PAGE_REASON'		=> $lang['disable_page_reason'],
		'L_DISABLE_PAGE_EXPLAIN'	=> $lang['disable_page_explain'],
		
		'L_PATH_GAMES'				=> $lang['path_games'],
		'L_PATH_GAMES_EXPLAIN'		=> $lang['path_games_explain'],
		'L_PATH_RANKS'				=> $lang['path_ranks'],
		'L_PATH_RANKS_EXPLAIN'		=> $lang['path_ranks_explain'],
		'L_PATH_NEWSCAT'			=> $lang['path_newscat'],
		'L_PATH_NEWSCAT_EXPLAIN'	=> $lang['path_cat_explain'],
		'L_PATH_GALLERY'			=> $lang['path_gallery'],
		'L_PATH_GALLERY_EXPLAIN'	=> $lang['path_gallery_explain'],
		'L_PATH_GROUPS'				=> $lang['path_groups'],
		'L_PATH_GROUPS_EXPLAIN'		=> $lang['path_groups_explain'],
		'L_PATH_MATCHS'				=> $lang['path_matchs'],
		'L_PATH_MATCHS_EXPLAIN'		=> $lang['path_matchs_explain'],
		'L_PATH_TEAMS'				=> $lang['path_teams'],
		'L_PATH_TEAMS_EXPLAIN'		=> $lang['path_teams_explain'],
		'L_PATH_USERS'				=> $lang['path_users'],
		'L_PATH_USERS_EXPLAIN'		=> $lang['path_users_explain'],
		
		'SERVER_NAME'				=> $config['server_name'], 
		'SERVER_PORT'				=> $config['server_port'], 
		'PAGE_PATH'					=> $config['page_path'], 
		'PAGE_NAME'					=> $config['page_name'],
		'PAGE_DESC'					=> $config['page_desc'], 
		'DISABLE_REASON'			=> $config['page_disable_msg'], 
		'PAGE_DISABLE_MODE'			=> page_mode_select($config['page_disable_mode']),
		'S_DISABLE_PAGE_YES'		=> ( $config['page_disable'] ) ? 'checked="checked"' : '',
		'S_DISABLE_PAGE_NO'			=> ( !$config['page_disable'] ) ? 'checked="checked"' : '',
		'EMAIL_ON'					=> ( $config['email_enabled'] ) ? 'checked="checked"' : '',
		'EMAIL_OFF'					=> ( !$config['email_enabled'] ) ? 'checked="checked"' : '',
		
		'PATH_GAMES'				=> $settings['path_games'],
		'PATH_GAMES_CHECKED'		=> ( is_writable($root_path . $settings['path_games']) ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',	
		'PATH_RANKS'				=> $settings['path_ranks'],
		'PATH_RANKS_CHECKED'		=> ( is_writable($root_path . $settings['path_ranks']) ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',	
		'PATH_NEWSCAT'				=> $settings['path_newscat'],
		'PATH_NEWSCAT_CHECKED'		=> ( is_writable($root_path . $settings['path_newscat']) ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
		'PATH_GALLERY'				=> $settings['path_gallery'],
		'PATH_GALLERY_CHECKED'		=> ( is_writable($root_path . $settings['path_gallery']) ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
		'PATH_GROUPS'				=> $settings['path_groups'],
		'PATH_GROUPS_CHECKED'		=> ( is_writable($root_path . $settings['path_groups']) ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
		'PATH_MATCHS'				=> $settings['path_matchs'],
		'PATH_MATCHS_CHECKED'		=> ( is_writable($root_path . $settings['path_matchs']) ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
		'PATH_TEAMS'				=> $settings['path_teams'],
		'PATH_TEAMS_CHECKED'		=> ( is_writable($root_path . $settings['path_teams']) ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
		'PATH_USERS'				=> $settings['path_users'],
		'PATH_USERS_CHECKED'		=> ( is_writable($root_path . $settings['path_users']) ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
		

		'L_DISPLAY'				=> $lang['site_display'],
		'L_DISPLAY_EXPLAIN'		=> $lang['site_display_explain'],
		
		'L_NEWS_LIMIT'			=> $lang['news_limit'],
		'L_NEWS_LENGTH'			=> $lang['news_length'],
		
		'L_MATCH_LIMIT'			=> $lang['match_limit'],
		'L_MATCH_LENGTH'		=> $lang['match_length'],
		'L_FORUM_LIMIT'			=> $lang['forum_limit'],
		'L_FORUM_LENGTH'		=> $lang['forum_length'],
		'L_DOWNLOAD_LIMIT'		=> $lang['download_limit'],
		'L_DOWNLOAD_LENGTH'		=> $lang['download_length'],
		'L_NEWUSERS'			=> $lang['newusers'],
		'L_NEWUSERS_CACHE'		=> $lang['newusers_cache'],
		'L_NEWUSERS_LIMIT'		=> $lang['newusers_limit'],
		'L_NEWUSERS_LENGTH'		=> $lang['newusers_length'],
		'L_TEAMS'				=> $lang['teams'],
		'L_TEAMS_LIMIT'			=> $lang['teams_limit'],
		'L_LINKS'				=> $lang['links'],
		'L_PARTNER'				=> $lang['partner'],
		'L_SPONSOR'				=> $lang['sponsor'],
		'L_MINICAL'				=> $lang['minical'],
		'L_MATCH_NEXT'			=> $lang['match_next'],
		'L_MATCH_NEXT'			=> $lang['training'],
		
		'NEWS_LIMIT'			=> $settings['subnavi_news_limit'],
		'NEWS_LENGTH'			=> $settings['subnavi_news_length'],
		'MATCH_LIMIT'			=> $settings['subnavi_match_limit'],
		'MATCH_LENGTH'			=> $settings['subnavi_match_length'],
		'FORUM_LIMIT'			=> $settings['subnavi_forum_limit'],
		'FORUM_LENGTH'			=> $settings['subnavi_forum_length'],
		'DOWNLOAD_LIMIT'		=> $settings['subnavi_download_limit'],
		'DOWNLOAD_LENGTH'		=> $settings['subnavi_download_length'],
		
		'S_NEWUSERS_NO'			=> (!$settings['subnavi_newusers'] ) ? 'checked="checked"' : '',
		'S_NEWUSERS_YES'		=> ( $settings['subnavi_newusers'] ) ? 'checked="checked"' : '',
		'NEWUSERS_CACHE'		=> $settings['subnavi_newusers_cache'],
		'NEWUSERS_LIMIT'		=> $settings['subnavi_newusers_limit'],
		'NEWUSERS_LENGTH'		=> $settings['subnavi_newusers_length'],
		
		'S_TEAMS_NO'			=> (!$settings['subnavi_teams'] ) ? 'checked="checked"' : '',
		'S_TEAMS_YES'			=> ( $settings['subnavi_teams'] ) ? 'checked="checked"' : '',
		'TEAMS_LIMIT'			=> $settings['subnavi_teams_limit'],
		
		'S_LINKS_NO'			=> (!$settings['subnavi_links'] ) ? 'checked="checked"' : '',
		'S_LINKS_YES'			=> ( $settings['subnavi_links'] ) ? 'checked="checked"' : '',
		'S_PARTNER_NO'			=> (!$settings['subnavi_partner'] ) ? 'checked="checked"' : '',
		'S_PARTNER_YES'			=> ( $settings['subnavi_partner'] ) ? 'checked="checked"' : '',
		'S_SPONSOR_NO'			=> (!$settings['subnavi_sponsor'] ) ? 'checked="checked"' : '',
		'S_SPONSOR_YES'			=> ( $settings['subnavi_sponsor'] ) ? 'checked="checked"' : '',
		'S_MINICAL_NO'			=> (!$settings['subnavi_minical'] ) ? 'checked="checked"' : '',
		'S_MINICAL_YES'			=> ( $settings['subnavi_minical'] ) ? 'checked="checked"' : '',
		'S_MATCH_NO'			=> (!$settings['subnavi_match_next'] ) ? 'checked="checked"' : '',
		'S_MATCH_YES'			=> ( $settings['subnavi_match_next'] ) ? 'checked="checked"' : '',
		'S_TRAINING_NO'			=> (!$settings['subnavi_training'] ) ? 'checked="checked"' : '',
		'S_TRAINING_YES'		=> ( $settings['subnavi_training'] ) ? 'checked="checked"' : '',
		
#		'L_EMAIL_ON-OFF'			=> $lang['email_enabled'],
#		'L_EMAIL_ON-OFF_EXPLAIN'	=> $lang['email_enabled_explain'],
		
		
//		'L_TEAM_LOGO_UPLOAD'				=> $lang['team_logo_upload'],
//		'L_TEAM_LOGO_UPLOAD_EXPLAIN'		=> $lang['team_logo_upload_explain'],
//		'L_TEAM_LOGO_MAX_FILESIZE'			=> $lang['team_logo_file'],
//		'L_TEAM_LOGO_MAX_FILESIZE_EXPLAIN'	=> $lang['team_logo_file_explain'],
//		'L_TEAM_LOGO_MAX_SIZE'				=> $lang['team_logo_size'],
//		'L_TEAM_LOGO_MAX_SIZE_EXPLAIN'		=> $lang['team_logo_size_explain'],
//		'L_TEAM_LOGOS_UPLOAD'				=> $lang['team_logos_upload'],
//		'L_TEAM_LOGOS_UPLOAD_EXPLAIN'		=> $lang['team_logos_upload_explain'],
//		'L_TEAM_LOGOS_MAX_FILESIZE'			=> $lang['team_logos_file'],
//		'L_TEAM_LOGOS_MAX_FILESIZE_EXPLAIN'	=> $lang['team_logos_file_explain'],
//		'L_TEAM_LOGOS_MAX_SIZE'				=> $lang['team_logos_size'],
//		'L_TEAM_LOGOS_MAX_SIZE_EXPLAIN'		=> $lang['team_logos_size_explain'],
//		"TEAM_LOGO_FILESIZE"		=> $settings['team_logo_filesize'],
//		"TEAM_LOGO_MAX_HEIGHT"		=> $settings['team_logo_max_height'],
//		"TEAM_LOGO_MAX_WIDTH"		=> $settings['team_logo_max_width'],
//		
//		"S_LOGO_UPLOAD_YES"	=> ( $settings['team_logo_upload'] ) ? 'checked="checked"' : '',
//		"S_LOGO_UPLOAD_NO"		=> (!$settings['team_logo_upload'] ) ? 'checked="checked"' : '',
//		
//		"TEAM_LOGOS_FILESIZE"		=> $settings['team_logos_filesize'],
//		"TEAM_LOGOS_MAX_HEIGHT"		=> $settings['team_logos_max_height'],
//		"TEAM_LOGOS_MAX_WIDTH"		=> $settings['team_logos_max_width'],	
//		
//		"S_LOGOS_UPLOAD_YES"	=> ( $settings['team_logos_upload'] ) ? 'checked="checked"' : '',
//		"S_LOGOS_UPLOAD_NO"	=> (!$settings['team_logos_upload'] ) ? 'checked="checked"' : '',
//		
//		'L_AUTOLOGIN_TIME_EXPLAIN'	=> $lang['Autologin_time_explain'],
//		'L_COOKIE_SETTINGS'			=> $lang['Cookie_settings'], 
//		'L_COOKIE_SETTINGS_EXPLAIN'	=> $lang['Cookie_settings_explain'], 
//		'L_COOKIE_DOMAIN'			=> $lang['Cookie_domain'],
//		'L_COOKIE_NAME'				=> $lang['Cookie_name'], 
//		'L_COOKIE_PATH'				=> $lang['Cookie_path'], 
//		'L_COOKIE_SECURE'			=> $lang['Cookie_secure'], 
//		'L_COOKIE_SECURE_EXPLAIN'	=> $lang['Cookie_secure_explain'], 
//		'L_SESSION_LENGTH'			=> $lang['Session_length'], 
//		
//		'COOKIE_DOMAIN'				=> $config['cookie_domain'], 
//		'COOKIE_NAME'				=> $config['cookie_name'], 
//		'COOKIE_PATH'				=> $config['cookie_path'], 
//		'SESSION_LENGTH'			=> $config['session_length'], 
//		'S_COOKIE_SECURE_ENABLED'	=> ( $config['cookie_secure'] ) ? 'checked="checked"' : '', 
//		'S_COOKIE_SECURE_DISABLED'	=> (!$config['cookie_secure'] ) ? 'checked="checked"' : '', 
		
		
	
		
		/*
		"L_CONFIGURATION_TITLE" => $lang['General_Config'],
		"L_CONFIGURATION_EXPLAIN" => $lang['Config_explain'],
		"L_GENERAL_SETTINGS" => $lang['General_settings'],
		"L_SERVER_NAME" => $lang['Server_name'], 
		"L_SERVER_NAME_EXPLAIN" => $lang['Server_name_explain'], 
		"L_SERVER_PORT" => $lang['Server_port'], 
		"L_SERVER_PORT_EXPLAIN" => $lang['Server_port_explain'], 
		"L_SCRIPT_PATH" => $lang['Script_path'], 
		"L_SCRIPT_PATH_EXPLAIN" => $lang['Script_path_explain'], 
		"L_SITE_NAME" => $lang['Site_name'],
		"L_SITE_DESCRIPTION" => $lang['Site_desc'],
		"L_DISABLE_BOARD" => $lang['Board_disable'], 
		"L_DISABLE_BOARD_EXPLAIN" => $lang['Board_disable_explain'], 
		"L_ACCT_ACTIVATION" => $lang['Acct_activation'], 
		"L_NONE" => $lang['Acc_None'], 
		"L_USER" => $lang['Acc_User'], 
		"L_ADMIN" => $lang['Acc_Admin'], 
		"L_VISUAL_CONFIRM" => $lang['Visual_confirm'], 
		"L_VISUAL_CONFIRM_EXPLAIN" => $lang['Visual_confirm_explain'], 
		"L_ALLOW_AUTOLOGIN" => $lang['Allow_autologin'],
		"L_ALLOW_AUTOLOGIN_EXPLAIN" => $lang['Allow_autologin_explain'],
		"L_AUTOLOGIN_TIME" => $lang['Autologin_time'],
		"L_AUTOLOGIN_TIME_EXPLAIN" => $lang['Autologin_time_explain'],
		"L_COOKIE_SETTINGS" => $lang['Cookie_settings'], 
		"L_COOKIE_SETTINGS_EXPLAIN" => $lang['Cookie_settings_explain'], 
		"L_COOKIE_DOMAIN" => $lang['Cookie_domain'],
		"L_COOKIE_NAME" => $lang['Cookie_name'], 
		"L_COOKIE_PATH" => $lang['Cookie_path'], 
		"L_COOKIE_SECURE" => $lang['Cookie_secure'], 
		"L_COOKIE_SECURE_EXPLAIN" => $lang['Cookie_secure_explain'], 
		"L_SESSION_LENGTH" => $lang['Session_length'], 
		"L_PRIVATE_MESSAGING" => $lang['Private_Messaging'], 
		"L_INBOX_LIMIT" => $lang['Inbox_limits'], 
		"L_SENTBOX_LIMIT" => $lang['Sentbox_limits'], 
		"L_SAVEBOX_LIMIT" => $lang['Savebox_limits'], 
		"L_DISABLE_PRIVATE_MESSAGING" => $lang['Disable_privmsg'], 
		"L_ENABLED" => $lang['Enabled'], 
		"L_DISABLED" => $lang['Disabled'], 
		"L_ABILITIES_SETTINGS" => $lang['Abilities_settings'],
		"L_MAX_POLL_OPTIONS" => $lang['Max_poll_options'],
		"L_FLOOD_INTERVAL" => $lang['Flood_Interval'],
		"L_FLOOD_INTERVAL_EXPLAIN" => $lang['Flood_Interval_explain'], 
		"L_SEARCH_FLOOD_INTERVAL" => $lang['Search_Flood_Interval'],
		"L_SEARCH_FLOOD_INTERVAL_EXPLAIN" => $lang['Search_Flood_Interval_explain'], 
	
		'L_MAX_LOGIN_ATTEMPTS'			=> $lang['Max_login_attempts'],
		'L_MAX_LOGIN_ATTEMPTS_EXPLAIN'	=> $lang['Max_login_attempts_explain'],
		'L_LOGIN_RESET_TIME'			=> $lang['Login_reset_time'],
		'L_LOGIN_RESET_TIME_EXPLAIN'	=> $lang['Login_reset_time_explain'],
		'MAX_LOGIN_ATTEMPTS'			=> $new['max_login_attempts'],
		'LOGIN_RESET_TIME'				=> $new['login_reset_time'],
	
		"L_BOARD_EMAIL_FORM" => $lang['Board_email_form'], 
		"L_BOARD_EMAIL_FORM_EXPLAIN" => $lang['Board_email_form_explain'], 
		"L_TOPICS_PER_PAGE" => $lang['Topics_per_page'],
		"L_POSTS_PER_PAGE" => $lang['Posts_per_page'],
		"L_HOT_THRESHOLD" => $lang['Hot_threshold'],
		"L_DEFAULT_STYLE" => $lang['Default_style'],
		"L_OVERRIDE_STYLE" => $lang['Override_style'],
		"L_OVERRIDE_STYLE_EXPLAIN" => $lang['Override_style_explain'],
		"L_DEFAULT_LANGUAGE" => $lang['Default_language'],
		"L_DATE_FORMAT" => $lang['Date_format'],
		"L_SYSTEM_TIMEZONE" => $lang['System_timezone'],
		"L_ENABLE_GZIP" => $lang['Enable_gzip'],
		"L_ENABLE_PRUNE" => $lang['Enable_prune'],
		"L_ALLOW_HTML" => $lang['Allow_HTML'],
		"L_ALLOW_BBCODE" => $lang['Allow_BBCode'],
		"L_ALLOWED_TAGS" => $lang['Allowed_tags'],
		"L_ALLOWED_TAGS_EXPLAIN" => $lang['Allowed_tags_explain'],
		"L_ALLOW_SMILIES" => $lang['Allow_smilies'],
		"L_SMILIES_PATH" => $lang['Smilies_path'],
		"L_SMILIES_PATH_EXPLAIN" => $lang['Smilies_path_explain'],
		"L_ALLOW_SIG" => $lang['Allow_sig'],
		"L_MAX_SIG_LENGTH" => $lang['Max_sig_length'],
		"L_MAX_SIG_LENGTH_EXPLAIN" => $lang['Max_sig_length_explain'],
		"L_ALLOW_NAME_CHANGE" => $lang['Allow_name_change'],
		"L_AVATAR_SETTINGS" => $lang['Avatar_settings'],
		"L_ALLOW_LOCAL" => $lang['Allow_local'],
		"L_ALLOW_REMOTE" => $lang['Allow_remote'],
		"L_ALLOW_REMOTE_EXPLAIN" => $lang['Allow_remote_explain'],
		"L_ALLOW_UPLOAD" => $lang['Allow_upload'],
		"L_MAX_FILESIZE" => $lang['Max_filesize'],
		"L_MAX_FILESIZE_EXPLAIN" => $lang['Max_filesize_explain'],
		"L_MAX_AVATAR_SIZE" => $lang['Max_avatar_size'],
		"L_MAX_AVATAR_SIZE_EXPLAIN" => $lang['Max_avatar_size_explain'],
		"L_AVATAR_STORAGE_PATH" => $lang['Avatar_storage_path'],
		"L_AVATAR_STORAGE_PATH_EXPLAIN" => $lang['Avatar_storage_path_explain'],
		"L_AVATAR_GALLERY_PATH" => $lang['Avatar_gallery_path'],
		"L_AVATAR_GALLERY_PATH_EXPLAIN" => $lang['Avatar_gallery_path_explain'],
		
		
		"L_EMAIL_SETTINGS" => $lang['Email_settings'],
		"L_ADMIN_EMAIL" => $lang['Admin_email'],
		"L_EMAIL_SIG" => $lang['Email_sig'],
		"L_EMAIL_SIG_EXPLAIN" => $lang['Email_sig_explain'],
		"L_USE_SMTP" => $lang['Use_SMTP'],
		"L_USE_SMTP_EXPLAIN" => $lang['Use_SMTP_explain'],
		"L_SMTP_SERVER" => $lang['SMTP_server'], 
		"L_SMTP_USERNAME" => $lang['SMTP_username'], 
		"L_SMTP_USERNAME_EXPLAIN" => $lang['SMTP_username_explain'], 
		"L_SMTP_PASSWORD" => $lang['SMTP_password'], 
		"L_SMTP_PASSWORD_EXPLAIN" => $lang['SMTP_password_explain'], 
		
		
		
		"ACTIVATION_NONE" => USER_ACTIVATION_NONE, 
		"ACTIVATION_NONE_CHECKED" => $activation_none,
		"ACTIVATION_USER" => USER_ACTIVATION_SELF, 
		"ACTIVATION_USER_CHECKED" => $activation_user,
		"ACTIVATION_ADMIN" => USER_ACTIVATION_ADMIN, 
		"ACTIVATION_ADMIN_CHECKED" => $activation_admin, 
		"CONFIRM_ENABLE" => $confirm_yes,
		"CONFIRM_DISABLE" => $confirm_no,
		'ALLOW_AUTOLOGIN_YES' => $allow_autologin_yes,
		'ALLOW_AUTOLOGIN_NO' => $allow_autologin_no,
		'AUTOLOGIN_TIME' => (int) $new['max_autologin_time'],
		"BOARD_EMAIL_FORM_ENABLE" => $board_email_form_yes, 
		"BOARD_EMAIL_FORM_DISABLE" => $board_email_form_no, 
		"MAX_POLL_OPTIONS" => $new['max_poll_options'], 
		"FLOOD_INTERVAL" => $new['flood_interval'],
		"SEARCH_FLOOD_INTERVAL" => $new['search_flood_interval'],
		"TOPICS_PER_PAGE" => $new['topics_per_page'],
		"POSTS_PER_PAGE" => $new['posts_per_page'],
		"HOT_TOPIC" => $new['hot_threshold'],
		"STYLE_SELECT" => $style_select,
		"OVERRIDE_STYLE_YES" => $override_user_style_yes,
		"OVERRIDE_STYLE_NO" => $override_user_style_no,
		"LANG_SELECT" => $lang_select,
		"L_DATE_FORMAT_EXPLAIN" => $lang['Date_format_explain'],
		"DEFAULT_DATEFORMAT" => $new['default_dateformat'],
		"TIMEZONE_SELECT" => $timezone_select,
		"S_PRIVMSG_ENABLED" => $privmsg_on, 
		"S_PRIVMSG_DISABLED" => $privmsg_off, 
		"INBOX_LIMIT" => $new['max_inbox_privmsgs'], 
		"SENTBOX_LIMIT" => $new['max_sentbox_privmsgs'],
		"SAVEBOX_LIMIT" => $new['max_savebox_privmsgs'],
		"COOKIE_DOMAIN" => $new['cookie_domain'], 
		"COOKIE_NAME" => $new['cookie_name'], 
		"COOKIE_PATH" => $new['cookie_path'], 
		"SESSION_LENGTH" => $new['session_length'], 
		"S_COOKIE_SECURE_ENABLED" => $cookie_secure_yes, 
		"S_COOKIE_SECURE_DISABLED" => $cookie_secure_no, 
		"GZIP_YES" => $gzip_yes,
		"GZIP_NO" => $gzip_no,
		"PRUNE_YES" => $prune_yes,
		"PRUNE_NO" => $prune_no, 
		"HTML_TAGS" => $html_tags, 
		"HTML_YES" => $html_yes,
		"HTML_NO" => $html_no,
		"BBCODE_YES" => $bbcode_yes,
		"BBCODE_NO" => $bbcode_no,
		"SMILE_YES" => $smile_yes,
		"SMILE_NO" => $smile_no,
		"SIG_YES" => $sig_yes,
		"SIG_NO" => $sig_no,
		"SIG_SIZE" => $new['max_sig_chars'], 
		"NAMECHANGE_YES" => $namechange_yes,
		"NAMECHANGE_NO" => $namechange_no,
		"AVATARS_LOCAL_YES" => $avatars_local_yes,
		"AVATARS_LOCAL_NO" => $avatars_local_no,
		"AVATARS_REMOTE_YES" => $avatars_remote_yes,
		"AVATARS_REMOTE_NO" => $avatars_remote_no,
		"AVATARS_UPLOAD_YES" => $avatars_upload_yes,
		"AVATARS_UPLOAD_NO" => $avatars_upload_no,
		"AVATAR_FILESIZE" => $new['avatar_filesize'],
		"AVATAR_MAX_HEIGHT" => $new['avatar_max_height'],
		"AVATAR_MAX_WIDTH" => $new['avatar_max_width'],
		"AVATAR_PATH" => $new['avatar_path'], 
		"AVATAR_GALLERY_PATH" => $new['avatar_gallery_path'], 
		"SMILIES_PATH" => $new['smilies_path'], 
		"INBOX_PRIVMSGS" => $new['max_inbox_privmsgs'], 
		"SENTBOX_PRIVMSGS" => $new['max_sentbox_privmsgs'], 
		"SAVEBOX_PRIVMSGS" => $new['max_savebox_privmsgs'], 
		"EMAIL_FROM" => $new['board_email'],
		"EMAIL_SIG" => $new['board_email_sig'],
		"SMTP_YES" => $smtp_yes,
		"SMTP_NO" => $smtp_no,
		"SMTP_HOST" => $new['smtp_host'],
		"SMTP_USERNAME" => $new['smtp_username'],
		"SMTP_PASSWORD" => $new['smtp_password'],
		
		"L_ENABLED" => $lang['Enabled'], 
		"L_DISABLED" => $lang['Disabled'], 
		*/

		'S_SORT'	=> $s_sort,
		'S_ACTION'	=> append_sid('admin_settings.php'),
	));
	
	$template->pparse('body');

	include('./page_footer_admin.php');

}

?>