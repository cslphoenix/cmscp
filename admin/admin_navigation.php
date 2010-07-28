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
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_navi'] )
	{
		$module['_headmenu_main']['_submenu_navi'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_navi';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('navi');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_NAVIGATION_URL, 0);
	$data_type	= request('type');
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$show_index	= '';
		
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_navi'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_NAVI, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['sprintf_auth_fail'], $lang[$current]));
	}
	
	if ( $no_header )
	{
		redirect('admin/' . append_sid('admin_navigation.php', true));
	}

	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_navigation.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 2) )
				{
					$data = array(
						'navi_name'		=> request('navi_name', 2),
						'navi_type'		=> '1',
						'navi_url'		=> '',
						'navi_lang'		=> '1',
						'navi_show'		=> '1',
						'navi_target'	=> '0',
						'navi_intern'	=> '0',
					);
				}
				else if ( $mode == '_update' && !request('submit', 2) )
				{
					$data = get_data(NAVIGATION, $data_id, 1);
				}
				else
				{
					$data = array(
						'navi_name'		=> request('navi_name', 2),
						'navi_type'		=> request('navi_type', 0),
						'navi_url'		=> request('navi_url', 2),
						'navi_lang'		=> request('navi_lang', 0),
						'navi_show'		=> request('navi_show', 0),
						'navi_target'	=> request('navi_target', 0),
						'navi_intern'	=> request('navi_intern', 0),
					);
				}
				
				$navi_url = str_replace('./', '', $data['navi_url']);

				$folder = $root_path;
				$files = scandir($folder);
				
				$s_list = '';
				$s_list .= '<select class="selectsmall" name="navi_url" id="navi_url" onchange="select.value = this.value;">';
				$s_list .= '<option value="">----------</option>';
				
				foreach ( $files as $file )
				{
					if ( strstr($file, '.php') )
					{
						$selected = ( $file == $navi_url ) ? ' selected="selected"' : '';
						$s_list .= "<option value=\"$file\" $selected>&raquo;&nbsp;$file&nbsp;</option>";
					}
				}
				$s_list .= '</select>';
				
				$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
				$s_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_NAVIGATION_URL . '" value="' . $data_id . '" />';

				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['navi']),
					'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['navi_field'], $data['navi_name']),
					'L_INFOS'		=> $lang['common_data_input'],
					
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['navi_field']),
					'L_SET'		=> $lang['navi_settings'],
					'L_URL'		=> $lang['navi_url'],
					'L_TYPE'	=> sprintf($lang['sprintf_type'], $lang['navi_field']),
					
					'L_LANGUAGE'	=> $lang['common_language'],
					'L_SHOW'		=> $lang['common_visible'],
					'L_INTERN'		=> $lang['common_intern'],
					
					'L_TARGET'		=> $lang['navi_target'],
					'L_TARGET_NEW'	=> $lang['navi_target_new'],
					'L_TARGET_SELF'	=> $lang['navi_target_self'],
					
					'L_TYPE_MAIN'	=> $lang['navi_main'],
					'L_TYPE_CLAN'	=> $lang['navi_clan'],
					'L_TYPE_COM'	=> $lang['navi_com'],
					'L_TYPE_MISC'	=> $lang['navi_misc'],
					'L_TYPE_USER'	=> $lang['navi_user'],
					
					'NAME'	=> $data['navi_name'],
					'URL'	=> $navi_url,
					
					'S_LANG_YES'	=> ( $data['navi_lang'] ) ? ' checked="checked"' : '',
					'S_LANG_NO'		=> ( !$data['navi_lang'] ) ? ' checked="checked"' : '',
					'S_SHOW_YES'	=> ( $data['navi_show'] ) ? ' checked="checked"' : '',
					'S_SHOW_NO'		=> ( !$data['navi_show'] ) ? ' checked="checked"' : '',
					'S_INTERN_YES'	=> ( $data['navi_intern'] ) ? ' checked="checked"' : '',
					'S_INTERN_NO'	=> ( !$data['navi_intern'] ) ? ' checked="checked"' : '',
					'S_TARGET_NEW'	=> ( $data['navi_target'] ) ? ' checked="checked"' : '',
					'S_TARGET_SELF'	=> ( !$data['navi_target'] ) ? ' checked="checked"' : '',
					'S_TYPE_MAIN'	=> ( $data['navi_type'] == NAVI_MAIN ) ? ' checked="checked"' : '',
					'S_TYPE_CLAN'	=> ( $data['navi_type'] == NAVI_CLAN ) ? ' checked="checked"' : '',
					'S_TYPE_COM'	=> ( $data['navi_type'] == NAVI_COM ) ? ' checked="checked"' : '',
					'S_TYPE_MISC'	=> ( $data['navi_type'] == NAVI_MISC ) ? ' checked="checked"' : '',
					'S_TYPE_USER'	=> ( $data['navi_type'] == NAVI_USER ) ? ' checked="checked"' : '',
					
					'S_LIST'	=> $s_list,
					'S_FIELDS'	=> $s_fields,
					'S_SET'		=> append_sid('admin_navigation.php?mode=_settings'),
					'S_ACTION'	=> append_sid('admin_navigation.php'),
				));
				
				if ( request('submit', 2) )
				{
					$navi_name		= request('navi_name', 2);
					$navi_type		= request('navi_type', 0);
					$navi_url		= request('navi_url', 2);
					$navi_lang		= request('navi_lang', 0);
					$navi_show		= request('navi_show', 0);
					$navi_target	= request('navi_target', 0);
					$navi_intern	= request('navi_intern', 0);
					
					$error = ( !$navi_name ) ? $lang['msg_select_name'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$max	= get_data_max(NAVIGATION, 'navi_order', '');
							$next	= $max['max'] + 10;
							
							$navi_url = ( !$navi_target ) ? './' . $navi_url : set_http($navi_url);
							
							$sql = "INSERT INTO " . NAVIGATION . " (navi_name, navi_type, navi_url, navi_lang, navi_show, navi_target, navi_intern, navi_order)
										VALUES ('$navi_name', '$navi_type', '$navi_url', '$navi_lang', '$navi_show', '$navi_url', '$navi_intern', '$next')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_navigation'] . sprintf($lang['click_return_navigation'], '<a href="' . append_sid('admin_navigation.php') . '">', '</a>');
						}
						else
						{
							$navi_url = ( !$navi_target ) ? './' . $navi_url : set_http($navi_url);
							
							$sql = "UPDATE " . NAVIGATION . " SET
										navi_name		= '$navi_name',
										navi_type		= '$navi_type',
										navi_url		= '$navi_url',
										navi_lang		= '$navi_lang',
										navi_show		= '$navi_show',
										navi_target		= '$navi_target',
										navi_intern		= '$navi_intern'
									WHERE navi_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_navigation']
								. sprintf($lang['click_return_navigation'], '<a href="' . append_sid('admin_navigation.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_navigation.php?mode=_update&' . POST_NAVIGATION_URL . '=' . $data_id) . '">', '</a>');
						}
						
						log_add(LOG_ADMIN, LOG_SEK_NAVI, $mode, $navi_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
			
				$template->pparse('body');
				
				break;
			
			case '_order':
			
				update(NAVIGATION, 'navi', $move, $data_id);
				orders(NAVIGATION, $data_type);
				
				log_add(LOG_ADMIN, LOG_SEK_NAVI, $mode);
				
				$show_index = TRUE;
	
				break;

			case '_settings':
			
				$template->set_filenames(array('body' => 'style/acp_navigation.tpl'));
				$template->assign_block_vars('_settings', array());
				
				$s_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['navi']),
					'L_SET'			=> $lang['navi_settings'],
					'L_REQUIRED'	=> $lang['required'],
					
					'L_NEWS'		=> $lang['last_news'],
					'L_MATCH'		=> $lang['last_match'],
					'L_NEWUSERS'	=> $lang['last_users'],
					'L_TOPICS'		=> $lang['last_topics'],
					'L_DOWNLOADS'	=> $lang['last_downloads'],
					'L_TEAMS'		=> $lang['subnavi_teams'],
					
					'L_NEWS_LIMIT'				=> $lang['subnavi_news_limit'],
					'L_NEWS_LIMIT_EXPLAIN'		=> $lang['subnavi_news_limit_explain'],
					'L_NEWS_LENGTH'				=> $lang['subnavi_news_length'],
					'L_NEWS_LENGTH_EXPLAIN'		=> $lang['subnavi_news_length_explain'],
					'L_MATCH_LIMIT'				=> $lang['subnavi_match_limit'],
					'L_MATCH_LIMIT_EXPLAIN'		=> $lang['subnavi_match_limit_explain'],
					'L_MATCH_LENGTH'			=> $lang['subnavi_match_length'],
					'L_MATCH_LENGTH_EXPLAIN'	=> $lang['subnavi_match_length_explain'],
					
					'L_NEWUSERS_SHOW'			=> $lang['subnavi_newusers_show'],
					'L_NEWUSERS_SHOW_EXPLAIN'	=> $lang['subnavi_newusers_show_explain'],
					'L_NEWUSERS_CACHE'			=> $lang['subnavi_newusers_cache'],
					'L_NEWUSERS_CACHE_EXPLAIN'	=> $lang['subnavi_newusers_cache_explain'],
					'L_NEWUSERS_LIMIT'			=> $lang['subnavi_newusers_limit'],
					'L_NEWUSERS_LIMIT_EXPLAIN'	=> $lang['subnavi_newusers_limit_explain'],
					'L_NEWUSERS_LENGTH'			=> $lang['subnavi_newusers_length'],
					'L_NEWUSERS_LENGTH_EXPLAIN'	=> $lang['subnavi_newusers_length_explain'],
					
					'L_TEAMS_SHOW'				=> $lang['subnavi_teams_show'],
					'L_TEAMS_SHOW_EXPLAIN'		=> $lang['subnavi_teams_show_explain'],
					'L_TEAMS_LIMIT'				=> $lang['subnavi_teams_limit'],
					'L_TEAMS_LIMIT_EXPLAIN'		=> $lang['subnavi_teams_limit_explain'],
					'L_TEAMS_LENGTH'			=> $lang['subnavi_teams_length'],
					'L_TEAMS_LENGTH_EXPLAIN'	=> $lang['subnavi_teams_length_explain'],

					'NEWS_LENGTH'		=> $settings['subnavi_news_length'],
					'NEWS_LIMIT'		=> $settings['subnavi_news_limit'],
					'MATCH_LENGTH'		=> $settings['subnavi_match_length'],
					'MATCH_LIMIT'		=> $settings['subnavi_match_limit'],
					'NEWUSERS_CACHE'	=> $settings['subnavi_newusers_cache'],					
					'NEWUSERS_LENGTH'	=> $settings['subnavi_newusers_length'],
					'NEWUSERS_LIMIT'	=> $settings['subnavi_newusers_limit'],
					'TEAMS_LIMIT'		=> $settings['subnavi_teams_limit'],
					'TEAMS_LENGTH'		=> $settings['subnavi_teams_length'],
					
					'NEWUSERS_ON'		=> ( $settings['subnavi_newusers'] ) ? ' checked="checked"' : '',
					'NEWUSERS_OFF'		=> ( !$settings['subnavi_newusers'] ) ? ' checked="checked"' : '',
					'TEAMS_ON'			=> ( $settings['subnavi_teams'] ) ? ' checked="checked"' : '',
					'TEAMS_OFF'			=> ( !$settings['subnavi_teams'] ) ? ' checked="checked"' : '',
					
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_navigation.php'),
				));
				
				if ( request('submit', 2) )
				{
					$sql = "SELECT * FROM " . SETTINGS;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					else
					{
						while ( $row_settings = $db->sql_fetchrow($result) )
						{
							$setting_name	= $row_settings['settings_name'];
							$setting_value	= $row_settings['settings_value'];
							
							$old[$setting_name] = ( isset($_POST['submit']) )		? str_replace("'", "\'", $setting_value) : $setting_value;
							$new[$setting_name] = ( isset($_POST[$setting_name]) )	? $_POST[$setting_name] : $old[$setting_name];
							
							$sql = "UPDATE " . SETTINGS . " SET settings_value = '" . str_replace("\'", "''", $new[$setting_name]) . "' WHERE settings_name = '$setting_name'";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}					
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('settings');
									
					$message = $lang['update_navigation_set'] . sprintf($lang['click_return_navigation_set'], '<a href="' . append_sid('admin_navigation.php?mode=_settings') . '">', '</a>');
					
					log_add(LOG_ADMIN, LOG_SEK_NAVI, $mode);
					message(GENERAL_MESSAGE, $message);
				}
			
				$template->pparse('body');
				
				break;
				
			case '_delete':
			
				$data = get_data(NAVIGATION, $data_id, 1);
			
				if ( $data_id && $confirm )
				{	
					$sql = "DELETE FROM " . NAVIGATION . " WHERE navi_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete_navigation'] . sprintf($lang['click_return_navigation'], '<a href="' . append_sid('admin_navigation.php') . '">', '</a>');
					
					log_add(LOG_ADMIN, LOG_SEK_NAVI, $mode, $data['navi_name']);
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_NAVIGATION_URL . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_navigation'], $data['navi_name']),
						
						'S_FIELDS'	=> $s_fields,
						'S_ACTION'	=> append_sid('admin_navigation.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_navigation']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
			
				message(GENERAL_ERROR, $lang['msg_no_module_select']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_navigation.tpl'));
	$template->assign_block_vars('_display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['navi']),
		'L_HEAD_SET'	=> $lang['navi_settings'],
		'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['navi_field']),
		'L_EXPLAIN'		=> $lang['navi_explain'],
		
		'L_MAIN'	=> $lang['navi_main'],
		'L_CLAN'	=> $lang['navi_clan'],
		'L_COM'		=> $lang['navi_com'],
		'L_MISC'	=> $lang['navi_misc'],
		'L_USER'	=> $lang['navi_user'],
		
		'NO_ENTRY'	=> $lang['no_entry'],
		
		'S_SET'		=> append_sid('admin_navigation.php?mode=_settings'),
		'S_FIELDS'	=> $s_fields,
		'S_CREATE'	=> append_sid('admin_navigation.php?mode=_create'),
		'S_ACTION'	=> append_sid('admin_navigation.php'),
	));
	
	$max_main	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_MAIN);
	$max_clan	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_CLAN);
	$max_com	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_COM);
	$max_misc	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_MISC);
	$max_user	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_USER);
	
	$data_main	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_MAIN, 'navi_order', 'ASC');
	$data_clan	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_CLAN, 'navi_order', 'ASC');
	$data_com	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_COM, 'navi_order', 'ASC');
	$data_misc	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_MISC, 'navi_order', 'ASC');
	$data_user	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_USER, 'navi_order', 'ASC');
	
	if ( $data_main )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_main)); $i++ )
		{
			$navi_id	= $data_main[$i]['navi_id'];
			$navi_lang	= ( $data_main[$i]['navi_lang'] ) ? $lang[$data_main[$i]['navi_name']] : $data_main[$i]['navi_name'];
				
			$template->assign_block_vars('_display._main_row', array(
				'TITLE'		=> ( $data_main[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_main[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $data_main[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $data_main[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_MAIN . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data_main[$i]['navi_order'] != $max_main['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_MAIN . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_main', array());
	}
	
	if ( $data_clan )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_clan)); $i++ )
		{
			$navi_id	= $data_clan[$i]['navi_id'];
			$navi_lang	= ( $data_clan[$i]['navi_lang'] ) ? $lang[$data_clan[$i]['navi_name']] : $data_clan[$i]['navi_name'];
				
			$template->assign_block_vars('_display._clan_row', array(
				'TITLE'		=> ( $data_clan[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_clan[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $data_clan[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $data_clan[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_CLAN . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data_clan[$i]['navi_order'] != $max_clan['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_CLAN . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_clan', array());
	}
	
	if ( $data_com )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_com)); $i++ )
		{
			$navi_id	= $data_com[$i]['navi_id'];
			$navi_lang	= ( $data_com[$i]['navi_lang'] ) ? $lang[$data_com[$i]['navi_name']] : $data_com[$i]['navi_name'];
				
			$template->assign_block_vars('_display._com_row', array(
				'TITLE'		=> ( $data_com[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_com[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $data_com[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $data_com[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_COM . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data_com[$i]['navi_order'] != $max_com['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_COM . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_com', array());
	}
	
	if ( $data_misc )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_misc)); $i++ )
		{
			$navi_id	= $data_misc[$i]['navi_id'];
			$navi_lang	= ( $data_misc[$i]['navi_lang'] ) ? $lang[$data_misc[$i]['navi_name']] : $data_misc[$i]['navi_name'];
				
			$template->assign_block_vars('_display._misc_row', array(
				'TITLE'		=> ( $data_misc[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_misc[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $data_misc[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $data_misc[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_MISC . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data_misc[$i]['navi_order'] != $max_misc['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_MISC . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_misc', array());
	}
	
	if ( $data_user )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_user)); $i++ )
		{
			$navi_id	= $data_user[$i]['navi_id'];
			$navi_lang	= ( $data_user[$i]['navi_lang'] ) ? $lang[$data_user[$i]['navi_name']] : $data_user[$i]['navi_name'];
				
			$template->assign_block_vars('_display._user_row', array(
				'TITLE'		=> ( $data_user[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_user[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $data_user[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $data_user[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_USER . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data_user[$i]['navi_order'] != $max_user['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_USER . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_user', array());
	}

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>