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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_navi'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_main']['_submenu_navi'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	$current	= '_submenu_navi';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/navi.php');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_NAVIGATION_URL, 0);
	$data_type	= request('type');
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$show_index	= '';
		
	if ( !$userauth['auth_games'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
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
				$template->assign_block_vars('navigation_edit', array());
				
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
				
				$filename_list = '';
				$filename_list .= '<select name="navi_url" id="navi_url" class="post" onchange="select.value = this.value;">';
				$filename_list .= '<option value="">----------</option>';
				
				foreach ( $files as $file )
				{
					if ( strstr($file, '.php') )
					{
						$selected = ( $file == $navi_url ) ? ' selected="selected"' : '';
						$filename_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$filename_list .= '</select>';
				
				$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
				$s_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_NAVIGATION_URL . '" value="' . $data_id . '" />';

				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['navi']),
					'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['navi_field'], $data['navi_name']),
					'L_SET'			=> $lang['navi_settings'],
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['navi_field']),
					'L_URL'			=> $lang['navi_url'],
					'L_TYPE'		=> sprintf($lang['sprintf_type'], $lang['navi_field']),
					
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
					
					'NAME'			=> $data['navi_name'],
					'URL'			=> $navi_url,
					
					'S_LANG_YES'	=> ( $data['navi_lang'] ) ? ' checked="checked"' : '',
					'S_LANG_NO'		=> ( !$data['navi_lang'] ) ? ' checked="checked"' : '',
					'S_SHOW_YES'	=> ( $data['navi_show'] ) ? ' checked="checked"' : '',
					'S_SHOW_NO'		=> ( !$data['navi_show'] ) ? ' checked="checked"' : '',
					'S_INTERN_YES'	=> ( $data['navi_intern'] ) ? ' checked="checked"' : '',
					'S_INTERN_NO'	=> ( !$data['navi_intern'] ) ? ' checked="checked"' : '',
					'S_TARGET_NEW'	=> ( $data['navi_target'] ) ? ' checked="checked"' : '',
					'S_TARGET_SELF'	=> ( !$data['navi_target'] ) ? ' checked="checked"' : '',
					'S_TYPE_MAIN'	=> ( $data['navi_type'] == '1' ) ? ' checked="checked"' : '',
					'S_TYPE_CLAN'	=> ( $data['navi_type'] == '2' ) ? ' checked="checked"' : '',
					'S_TYPE_COM'	=> ( $data['navi_type'] == '3' ) ? ' checked="checked"' : '',
					'S_TYPE_MISC'	=> ( $data['navi_type'] == '4' ) ? ' checked="checked"' : '',
					'S_TYPE_USER'	=> ( $data['navi_type'] == '5' ) ? ' checked="checked"' : '',
					
					'S_FILENAME_LIST'	=> $filename_list,
					
					'S_FIELDS'		=> $s_fields,
					'S_SET'			=> append_sid('admin_navigation.php?mode=_settings'),
					'S_ACTION'		=> append_sid('admin_navigation.php'),
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
					
					$error = '';
					$error .= ( !$navi_name ) ? $lang['msg_select_name'] : '';
					
					if ( $error )
					{
						$template->set_filenames(array('reg_header' => 'style/error_body.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}					
					else
					{
						if ( $mode == '_create' )
						{
							$max_row	= get_data_max(NAVIGATION, 'navi_order', '');
							$next_order	= $max_row['max'] + 10;
							
							$navi_url = ( !$navi_target ) ? './' . $navi_url : set_http($navi_url);
							
							$sql = "INSERT INTO " . NAVIGATION . " (navi_name, navi_type, navi_url, navi_lang, navi_show, navi_target, navi_intern, navi_order)
										VALUES ('$navi_name', '$navi_type', '$navi_url', '$navi_lang', '$navi_show', '$navi_url', '$navi_intern', '$next_order')";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_navigation'] . sprintf($lang['click_return_navigation'], '<a href="' . append_sid('admin_navigation.php') . '">', '</a>');
							log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'create_navigation');
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
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_navigation']
								. sprintf($lang['click_return_navigation'], '<a href="' . append_sid('admin_navigation.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_navigation.php?mode=_update&' . POST_NAVIGATION_URL . '=' . $data_id) . '">', '</a>');
							log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'update_navigation');
						}
						message(GENERAL_MESSAGE, $message);
					}
				}
			
				$template->pparse('body');
				
				break;
			
			case '_order':
			
				update(NAVIGATION, 'navi', $move, $data_id);
				orders(NAVIGATION, $data_type);
				
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_order');
				
				$show_index = TRUE;
	
				break;

			case '_settings':
			
				$template->set_filenames(array('body' => 'style/acp_navigation.tpl'));
				$template->assign_block_vars('navigation_set', array());
				
				$s_fields = '<input type="hidden" name="mode" value="_settings_save" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['navi']),
					'L_SET'			=> $lang['navi_settings'],
					'L_REQUIRED'	=> $lang['required'],
					
					'L_NEWS'		=> $lang['subnavi_news'],
					'L_NEWUSERS'	=> $lang['subnavi_user'],
					'L_MATCH'		=> $lang['subnavi_match'],
					'L_TOPICS'		=> $lang['subnavi_topics'],
					'L_DOWNLOADS'	=> $lang['subnavi_downloads'],
					
					'L_NEWS_LIMIT'			=> $lang['subnavi_news_limit'],
					'L_NEWS_LIMIT_EXPLAIN'	=> $lang['subnavi_news_limit_explain'],
					'L_NEWS_LENGTH'			=> $lang['subnavi_news_length'],
					'L_NEWS_LENGTH_EXPLAIN'	=> $lang['subnavi_news_length_explain'],
					
					'L_MATCH_LIMIT'				=> $lang['subnavi_match_limit'],
					'L_MATCH_LIMIT_EXPLAIN'		=> $lang['subnavi_match_limit_explain'],
					'L_MATCH_LENGTH'			=> $lang['subnavi_match_length'],
					'L_MATCH_LENGTH_EXPLAIN'	=> $lang['subnavi_match_length_explain'],
					
					'L_NEWUSERS_CACHE'	=> $lang['subnavi_user_cache'],
					'L_NEWUSERS_SHOW'	=> $lang['subnavi_user_show'],
					'L_NEWUSERS_LIMIT'	=> $lang['subnavi_user_limit'],
					'L_NEWUSERS_LENGTH'	=> $lang['subnavi_user_length'],
					
					'L_NEWUSERS_SHOW_EXPLAIN'	=> $lang['subnavi_newusers_show_explain'],
					
					'L_TEAMS'			=> $lang['subnavi_teams'],
					'L_TEAMS_SHOW'		=> $lang['subnavi_teams_show'],
					'L_TEAMS_LENGTH'	=> $lang['subnavi_teams_length'],

					'NEWS_LENGTH'	=> $settings['subnavi_news_length'],
					'NEWS_LIMIT'	=> $settings['subnavi_news_limit'],
					'MATCH_LENGTH'	=> $settings['subnavi_match_length'],
					'MATCH_LIMIT'	=> $settings['subnavi_match_limit'],
					
					'USER_CACHE'	=> $settings['subnavi_newusers_cache'],					
					'USER_LENGTH'	=> $settings['subnavi_newusers_length'],
					'USER_LIMIT'	=> $settings['subnavi_newusers_limit'],
					
					'TEAMS_LENGTH'	=> $settings['subnavi_teams_length'],
					
					'USER_ON'		=> ( $settings['subnavi_newusers'] ) ? ' checked="checked"' : '',
					'USER_OFF'		=> ( !$settings['subnavi_newusers'] ) ? ' checked="checked"' : '',
					
					'TEAMS_ON'		=> ( $settings['subnavi_teams'] ) ? ' checked="checked"' : '',
					'TEAMS_OFF'		=> ( !$settings['subnavi_teams'] ) ? ' checked="checked"' : '',
					
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_navigation.php'),
				));
			
				$template->pparse('body');
				
				break;
				
			case '_settings_save':
			
				$sql = "SELECT * FROM " . SETTINGS;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				else
				{
					while( $row_settings = $db->sql_fetchrow($result) )
					{
						$settings_name = $row_settings['settings_name'];
						$settings_value = $row_settings['settings_value'];
						
						$default_settings[$settings_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $settings_value) : $settings_value;
						
						$new_settings[$settings_name] = ( isset($HTTP_POST_VARS[$settings_name]) ) ? $HTTP_POST_VARS[$settings_name] : $default_settings[$settings_name];
			
						$sql = 'UPDATE ' . SETTINGS . " SET settings_value = '" . str_replace("\'", "''", $new_settings[$settings_name]) . "' WHERE settings_name = '$settings_name'";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('settings');
								
				$message = $lang['update_navigation_set'] . sprintf($lang['click_return_navigation_set'], '<a href="' . append_sid('admin_navigation.php?mode=navigation_set') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'update_navigation_set');
				message(GENERAL_MESSAGE, $message);
	
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
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'delete_navigation');
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_NAVIGATION_URL . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_navigation'], $data['navi_name']),
						
						'S_FIELDS'		=> $s_fields,
						'S_ACTION'		=> append_sid('admin_navigation.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_navigation']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
			
				message(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_navigation.tpl'));
	$template->assign_block_vars('display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['navi']),
		'L_HEAD_SET'	=> $lang['navi_settings'],
		'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['navi_field']),
		'L_EXPLAIN'		=> $lang['navi_explain'],
		
		'L_MAIN'		=> $lang['navi_main'],
		'L_CLAN'		=> $lang['navi_clan'],
		'L_COM'			=> $lang['navi_com'],
		'L_MISC'		=> $lang['navi_misc'],
		'L_USER'		=> $lang['navi_user'],
		
		'L_SHOW'		=> $lang['common_visible'],
		'L_LANGUAGE'	=> $lang['common_language'],		
		
		'S_FIELDS'		=> $s_fields,
		'S_CREATE'		=> append_sid('admin_navigation.php?mode=_create'),
		'S_SET'			=> append_sid('admin_navigation.php?mode=_settings'),
		'S_ACTION'		=> append_sid('admin_navigation.php'),
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
				
			$template->assign_block_vars('display.main_row', array(
				'TITLE'		=> ( $data_main[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_main[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="">' : '<img src="' . $images['icon_option_lang2'] . '" alt="">',
				'SHOW'		=> ( $data_main[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="">' : '<img src="' . $images['icon_option_show2'] . '" alt="">',
				
				'MOVE_UP'	=> ( $data_main[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_MAIN . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'	=> ( $data_main[$i]['navi_order'] != $max_main['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_MAIN . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_main', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_clan )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_clan)); $i++ )
		{
			$navi_id	= $data_clan[$i]['navi_id'];
			$navi_lang	= ( $data_clan[$i]['navi_lang'] ) ? $lang[$data_clan[$i]['navi_name']] : $data_clan[$i]['navi_name'];
				
			$template->assign_block_vars('display.clan_row', array(
				'TITLE'		=> ( $data_clan[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_clan[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="">' : '<img src="' . $images['icon_option_lang2'] . '" alt="">',
				'SHOW'		=> ( $data_clan[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="">' : '<img src="' . $images['icon_option_show2'] . '" alt="">',
				
				'MOVE_UP'	=> ( $data_clan[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_CLAN . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'	=> ( $data_clan[$i]['navi_order'] != $max_clan['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_CLAN . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_clan', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_com )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_com)); $i++ )
		{
			$navi_id	= $data_com[$i]['navi_id'];
			$navi_lang	= ( $data_com[$i]['navi_lang'] ) ? $lang[$data_com[$i]['navi_name']] : $data_com[$i]['navi_name'];
				
			$template->assign_block_vars('display.com_row', array(
				'TITLE'		=> ( $data_com[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_com[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="">' : '<img src="' . $images['icon_option_lang2'] . '" alt="">',
				'SHOW'		=> ( $data_com[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="">' : '<img src="' . $images['icon_option_show2'] . '" alt="">',
				
				'MOVE_UP'	=> ( $data_com[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_COM . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'	=> ( $data_com[$i]['navi_order'] != $max_com['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_COM . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_com', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_misc )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_misc)); $i++ )
		{
			$navi_id	= $data_misc[$i]['navi_id'];
			$navi_lang	= ( $data_misc[$i]['navi_lang'] ) ? $lang[$data_misc[$i]['navi_name']] : $data_misc[$i]['navi_name'];
				
			$template->assign_block_vars('display.misc_row', array(
				'TITLE'		=> ( $data_misc[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_misc[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="">' : '<img src="' . $images['icon_option_lang2'] . '" alt="">',
				'SHOW'		=> ( $data_misc[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="">' : '<img src="' . $images['icon_option_show2'] . '" alt="">',
				
				'MOVE_UP'	=> ( $data_misc[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_MISC . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'	=> ( $data_misc[$i]['navi_order'] != $max_misc['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_MISC . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_misc', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_user )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_user)); $i++ )
		{
			$navi_id	= $data_user[$i]['navi_id'];
			$navi_lang	= ( $data_user[$i]['navi_lang'] ) ? $lang[$data_user[$i]['navi_name']] : $data_user[$i]['navi_name'];
				
			$template->assign_block_vars('display.user_row', array(
				'TITLE'		=> ( $data_user[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $data_user[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="">' : '<img src="' . $images['icon_option_lang2'] . '" alt="">',
				'SHOW'		=> ( $data_user[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="">' : '<img src="' . $images['icon_option_show2'] . '" alt="">',
				
				'MOVE_UP'	=> ( $data_user[$i]['navi_order'] != '10' )				? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_USER . '&amp;move=-15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'	=> ( $data_user[$i]['navi_order'] != $max_user['max'] )	? '<a href="' . append_sid('admin_navigation.php?mode=_order&amp;type=' . NAVI_USER . '&amp;move=15&amp;' . POST_NAVIGATION_URL . '=' . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

				'U_UPDATE'	=> append_sid('admin_navigation.php?mode=_update&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
				'U_DELETE'	=> append_sid('admin_navigation.php?mode=_delete&amp;' . POST_NAVIGATION_URL . '=' . $navi_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_user', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>