<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_navi'] )
	{
		$module['_headmenu_01_main']['_submenu_navi'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_navi';

	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('navi');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_NAVI;
	$url	= POST_NAVIGATION_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_type	= request('type');
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_games'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['navi']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_navi'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_navigation.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					list($type) = ( isset($_POST['navi_type']) ) ? each($_POST['navi_type']) : '';
					$name		= ( isset($_POST['navi_name']) ) ? str_replace("\'", "'", $_POST['navi_name'][$type]) : '';
					
					$data = array(
								'navi_name'		=> $name,
								'navi_type'		=> $type,
								'navi_url'		=> '',
								'navi_lang'		=> '0',
								'navi_show'		=> '1',
								'navi_target'	=> '0',
								'navi_intern'	=> '0',
								'navi_order'	=> '',
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(NAVIGATION, $data_id, false, 1, 1);
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
								'navi_order'	=> ( request('navi_order_new', 0) ) ? request('navi_order_new', 0) : request('navi_order', 0),
							);
				}
				
				$navi_url = str_replace('./', '', $data['navi_url']);

				$folder = $root_path;
				$files = scandir($folder);
				
				$s_list = '<select class="selectsmall" name="navi_url" id="navi_url" onchange="select.value = this.value;">';
				$s_list .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_file']) . "</option>";
				
				foreach ( $files as $sfile )
				{
					if ( strstr($sfile, '.php') )
					{
						$selected = ( $sfile == $navi_url ) ? 'selected="selected"' : '';
						$s_list .= "<option value=\"$sfile\" $selected>" . sprintf($lang['sprintf_select_format'], $sfile) . "</option>";
					}
				}
				$s_list .= '</select>';
				
				$head = array(
							'0' => array('navi_type' => NAVI_MAIN, 'navi_name' => $lang['main']),
							'1' => array('navi_type' => NAVI_CLAN, 'navi_name' => $lang['clan']),
							'2' => array('navi_type' => NAVI_COM, 'navi_name' => $lang['com']),
							'3' => array('navi_type' => NAVI_MISC, 'navi_name' => $lang['misc']),
							'4' => array('navi_type' => NAVI_USER, 'navi_name' => $lang['user']),
						);
				
				$type = ( $data['navi_type'] ) ? " WHERE navi_type = " . $data['navi_type'] : false;
				
				$sql = "SELECT * FROM " . NAVIGATION . "$type ORDER BY navi_type, navi_order ASC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$entrys = $db->sql_fetchrowset($result);
				
				$s_order = "<select class=\"select\" name=\"navi_order_new\" id=\"navi_order\">";
				$s_order .= "<option selected=\"selected\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
				
				for ( $i = 0; $i < count($head); $i++ )
				{
					$entry = '';

					for ( $j = 0; $j < count($entrys); $j++ )
					{
						if ( $head[$i]['navi_type'] == $entrys[$j]['navi_type'] )
						{
							$navi_lang = ( $entrys[$j]['navi_lang'] ) ? $lang[$entrys[$j]['navi_name']] : $entrys[$j]['navi_name'];
							
							$entry .= ( $entrys[$j]['navi_order'] == 10 ) ? "<option value=\"5\">" . sprintf($lang['sprintf_select_before'], $navi_lang) . "</option>" : '';
							$entry .= "<option value=\"" . ( $entrys[$j]['navi_order'] + 5 ) . "\">" . sprintf($lang['sprintf_select_order'], $navi_lang) . "</option>";
						}
					}
					
					if ( $entry != '' )
					{
						$s_order .= '<optgroup label="' . $head[$i]['navi_name'] . '">';
						$s_order .= $entry;
						$s_order .= '</optgroup>';
					}
				}
				
				$s_order .= '</select>';
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"navi_order\" value=" . $data['navi_order'] . " />";

				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['navi']),
					'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['field'], $data['navi_name']),
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['field']),
					'L_SET'			=> $lang['navi_s'],
					'L_URL'			=> $lang['url'],
					'L_TYPE'		=> sprintf($lang['sprintf_type'], $lang['field']),
					'L_LANGUAGE'	=> $lang['common_language'],
					'L_SHOW'		=> $lang['common_visible'],
					'L_INTERN'		=> $lang['common_intern'],
					
					'L_TARGET'		=> $lang['target'],
					'L_TARGET_NEW'	=> $lang['target_new'],
					'L_TARGET_SELF'	=> $lang['target_self'],
					'L_TYPE_MAIN'	=> $lang['main'],
					'L_TYPE_CLAN'	=> $lang['clan'],
					'L_TYPE_COM'	=> $lang['com'],
					'L_TYPE_MISC'	=> $lang['misc'],
					'L_TYPE_USER'	=> $lang['user'],
					
					'NAME'			=> $data['navi_name'],
					'URL'			=> $navi_url,
					
					'S_LIST'		=> $s_list,
					'S_LANG_YES'	=> ( $data['navi_lang'] ) ? 'checked="checked"' : '',
					'S_LANG_NO'		=> (!$data['navi_lang'] ) ? 'checked="checked"' : '',
					'S_SHOW_YES'	=> ( $data['navi_show'] ) ? 'checked="checked"' : '',
					'S_SHOW_NO'		=> (!$data['navi_show'] ) ? 'checked="checked"' : '',
					'S_INTERN_YES'	=> ( $data['navi_intern'] ) ? 'checked="checked"' : '',
					'S_INTERN_NO'	=> (!$data['navi_intern'] ) ? 'checked="checked"' : '',
					'S_TARGET_NEW'	=> ( $data['navi_target'] ) ? 'checked="checked"' : '',
					'S_TARGET_SELF'	=> (!$data['navi_target'] ) ? 'checked="checked"' : '',
					'S_TYPE_MAIN'	=> ( $data['navi_type'] == NAVI_MAIN ) ? 'checked="checked"' : '',
					'S_TYPE_CLAN'	=> ( $data['navi_type'] == NAVI_CLAN ) ? 'checked="checked"' : '',
					'S_TYPE_COM'	=> ( $data['navi_type'] == NAVI_COM ) ? 'checked="checked"' : '',
					'S_TYPE_MISC'	=> ( $data['navi_type'] == NAVI_MISC ) ? 'checked="checked"' : '',
					'S_TYPE_USER'	=> ( $data['navi_type'] == NAVI_USER ) ? 'checked="checked"' : '',
					
					'S_ORDER'		=> $s_order,
					
					'S_SET'			=> append_sid("$file?mode=_settings"),
					'S_ACTION'		=> append_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', 1) )
				{
				#	$navi_name		= request('navi_name', 2);
				#	$navi_type		= request('navi_type', 0);
				#	$navi_url		= request('navi_url', 2);
				#	$navi_lang		= request('navi_lang', 0);
				#	$navi_show		= request('navi_show', 0);
				#	$navi_target	= request('navi_target', 0);
				#	$navi_intern	= request('navi_intern', 0);
					
				#	$error .= ( !$navi_name ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
				#	$error .= ( !$navi_type ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
				
					$data = array(
								'navi_name'		=> request('navi_name', 2),
								'navi_type'		=> request('navi_type', 0),
								'navi_url'		=> ( request('navi_url', 2) ) ? ( request('navi_target', 0) ) ? set_http(request('navi_url', 2)) : './' . request('navi_url', 2) : '',
								'navi_lang'		=> request('navi_lang', 0),
								'navi_show'		=> request('navi_show', 0),
								'navi_target'	=> request('navi_target', 0),
								'navi_intern'	=> request('navi_intern', 0),
								'navi_order'	=> ( request('navi_order_new', 0) ) ? request('navi_order_new', 0) : request('navi_order', 0),
							);
					
					$error .= ( !$data['navi_name'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['navi_url'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_url'] : '';
					$error .= ( !$data['navi_type'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_type'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							foreach ( $data as $key => $var )
							{
								$keys[] = $key;
								$vars[] = $var;
							}
							
							$sql = 'INSERT INTO ' . NAVIGATION . ' (' . implode(', ', $keys) . ') VALUES (\'' . implode('\', \'', $vars) . '\')';
						#	$max	= get_data_max(NAVIGATION, 'navi_order', '');
						#	$next	= $max['max'] + 10;
							
						#	$navi_url = ( !$navi_target ) ? './' . $navi_url : set_http($navi_url);
							
						#	$sql = "INSERT INTO " . NAVIGATION . " (navi_name, navi_type, navi_url, navi_lang, navi_show, navi_target, navi_intern, navi_order)
						#				VALUES ('$navi_name', '$navi_type', '$navi_url', '$navi_lang', '$navi_show', '$navi_url', '$navi_intern', '$next')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							foreach ( $data as $key => $var )
							{
								$input[] = "$key = '$var'";
							}
							
							$sql = "UPDATE " . NAVIGATION . " SET " . implode(', ', $input) . " WHERE navi_id = $data_id";
						#	$navi_url = ( !$navi_target ) ? './' . $navi_url : set_http($navi_url);
						#	
						#	$sql = "UPDATE " . NAVIGATION . " SET
						#				navi_name		= '$navi_name',
						#				navi_type		= '$navi_type',
						#				navi_url		= '$navi_url',
						#				navi_lang		= '$navi_lang',
						#				navi_show		= '$navi_show',
						#				navi_target		= '$navi_target',
						#				navi_intern		= '$navi_intern'
						#			WHERE navi_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&$url=$data_id") . '">', '</a>');
						}
						
						orders(NAVIGATION, $data['navi_type']);
						
						log_add(LOG_ADMIN, $log, $mode, $data['navi_name']);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
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
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
	
				break;
				
			case '_delete':
			
				$data = data(NAVIGATION, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{
					$sql = "DELETE FROM " . NAVIGATION . " WHERE navi_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					
					log_add(LOG_ADMIN, $log, $mode, $data['navi_name']);
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['navi_name']),
						
						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['field'])); }
				
				$template->pparse('body');
				
				break;

			case '_settings':
			
				$template->set_filenames(array('body' => 'style/acp_navigation.tpl'));
				$template->assign_block_vars('_settings', array());
				
				$fields .= '<input type="hidden" name="mode" value="_settings" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['navi']),
					'L_SET'			=> $lang['settings'],
					
					'L_NEWS'		=> $lang['subnavi_news'],
					'L_MATCH'		=> $lang['subnavi_match'],
					'L_NEWUSERS'	=> sprintf($lang['newest_users'], $settings['subnavi_newusers_limit']),
					'L_TOPICS'		=> $lang['subnavi_topics'],
					'L_DOWNLOADS'	=> $lang['subnavi_downloads'],
					'L_TEAMS'		=> $lang['teams'],
					
					'L_NEWS_LIMIT'				=> $lang['news_limit'],
					'L_NEWS_LIMIT_EXPLAIN'		=> $lang['news_limit_explain'],
					'L_NEWS_LENGTH'				=> $lang['news_length'],
					'L_NEWS_LENGTH_EXPLAIN'		=> $lang['news_length_explain'],
					'L_MATCH_LIMIT'				=> $lang['match_limit'],
					'L_MATCH_LIMIT_EXPLAIN'		=> $lang['match_limit_explain'],
					'L_MATCH_LENGTH'			=> $lang['match_length'],
					'L_MATCH_LENGTH_EXPLAIN'	=> $lang['match_length_explain'],
					
					'L_NEWUSERS_SHOW'			=> $lang['newusers_show'],
					'L_NEWUSERS_SHOW_EXPLAIN'	=> $lang['newusers_show_explain'],
					'L_NEWUSERS_CACHE'			=> $lang['newusers_cache'],
					'L_NEWUSERS_CACHE_EXPLAIN'	=> $lang['newusers_cache_explain'],
					'L_NEWUSERS_LIMIT'			=> $lang['newusers_limit'],
					'L_NEWUSERS_LIMIT_EXPLAIN'	=> $lang['newusers_limit_explain'],
					'L_NEWUSERS_LENGTH'			=> $lang['newusers_length'],
					'L_NEWUSERS_LENGTH_EXPLAIN'	=> $lang['newusers_length_explain'],
					
					'L_TEAMS_SHOW'				=> $lang['teams_show'],
					'L_TEAMS_SHOW_EXPLAIN'		=> $lang['teams_show_explain'],
					'L_TEAMS_LIMIT'				=> $lang['teams_limit'],
					'L_TEAMS_LIMIT_EXPLAIN'		=> $lang['teams_limit_explain'],
					'L_TEAMS_LENGTH'			=> $lang['teams_length'],
					'L_TEAMS_LENGTH_EXPLAIN'	=> $lang['teams_length_explain'],

					'NEWS_LENGTH'		=> $settings['subnavi_news_length'],
					'NEWS_LIMIT'		=> $settings['subnavi_news_limit'],
					'MATCH_LENGTH'		=> $settings['subnavi_match_length'],
					'MATCH_LIMIT'		=> $settings['subnavi_match_limit'],
					'NEWUSERS_CACHE'	=> $settings['subnavi_newusers_cache'],					
					'NEWUSERS_LENGTH'	=> $settings['subnavi_newusers_length'],
					'NEWUSERS_LIMIT'	=> $settings['subnavi_newusers_limit'],
					'TEAMS_LIMIT'		=> $settings['subnavi_teams_limit'],
					'TEAMS_LENGTH'		=> $settings['subnavi_teams_length'],
					
					'NEWUSERS_NO'		=> (!$settings['subnavi_newusers'] ) ? 'checked="checked"' : '',
					'NEWUSERS_YES'		=> ( $settings['subnavi_newusers'] ) ? 'checked="checked"' : '',
					'TEAMS_NO'			=> (!$settings['subnavi_teams'] ) ? 'checked="checked"' : '',
					'TEAMS_YES'			=> ( $settings['subnavi_teams'] ) ? 'checked="checked"' : '',
					
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', 1) )
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
					
					#$oCache -> sCachePath = './../cache/';
					#$oCache -> deleteCache('settings');
									
					$message = $lang['update_navigation_set'] . sprintf($lang['click_return_navigation_set'], '<a href="' . append_sid("$file?mode=_settings") . '">', '</a>');
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $message);
				}
			
				$template->pparse('body');
				
				break;
				
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_navigation.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['navi']),
		'L_SET'		=> $lang['settings'],
		'L_CREATE'	=> sprintf($lang['sprintf_new_create'], $lang['field']),
		'L_EXPLAIN'	=> $lang['explain'],
		'L_MAIN'	=> $lang['main'],
		'L_CLAN'	=> $lang['clan'],
		'L_COM'		=> $lang['com'],
		'L_MISC'	=> $lang['misc'],
		'L_USER'	=> $lang['user'],
		
		'S_SET'		=> append_sid("$file?mode=_settings"),
		'S_CREATE'	=> append_sid("$file?mode=_create"),
		'S_ACTION'	=> append_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max_main	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_MAIN);
	$max_clan	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_CLAN);
	$max_com	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_COM);
	$max_misc	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_MISC);
	$max_user	= get_data_max(NAVIGATION, 'navi_order', 'navi_type = ' . NAVI_USER);
	
	$tmp_main	= data(NAVIGATION, 'navi_type = ' . NAVI_MAIN, 'navi_order ASC', 1, false);
	$tmp_clan	= data(NAVIGATION, 'navi_type = ' . NAVI_CLAN, 'navi_order ASC', 1, false);
	$tmp_com	= data(NAVIGATION, 'navi_type = ' . NAVI_COM, 'navi_order ASC', 1, false);
	$tmp_misc	= data(NAVIGATION, 'navi_type = ' . NAVI_MISC, 'navi_order ASC', 1, false);
	$tmp_user	= data(NAVIGATION, 'navi_type = ' . NAVI_USER, 'navi_order ASC', 1, false);
	
#	$tmp_main	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_MAIN, 'navi_order', 'ASC');
#	$tmp_clan	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_CLAN, 'navi_order', 'ASC');
#	$tmp_com	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_COM, 'navi_order', 'ASC');
#	$tmp_misc	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_MISC, 'navi_order', 'ASC');
#	$tmp_user	= get_data_array(NAVIGATION, 'navi_type = ' . NAVI_USER, 'navi_order', 'ASC');
	
	if ( $tmp_main )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_main)); $i++ )
		{
			$navi_id	= $tmp_main[$i]['navi_id'];
			$navi_order	= $tmp_main[$i]['navi_order'];
			$navi_lang	= ( $tmp_main[$i]['navi_lang'] ) ? $lang[$tmp_main[$i]['navi_name']] : $tmp_main[$i]['navi_name'];
				
			$template->assign_block_vars('_display._main_row', array(
				'NAME'		=> ( $tmp_main[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $tmp_main[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $tmp_main[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $navi_order != '10' )				? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NAVI_MAIN . "&amp;move=-15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $navi_order != $max_main['max'] )	? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NAVI_MAIN . "&amp;move=+15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$navi_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$navi_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry_main', array()); }
	
	if ( $tmp_clan )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_clan)); $i++ )
		{
			$navi_id	= $tmp_clan[$i]['navi_id'];
			$navi_order	= $tmp_clan[$i]['navi_order'];
			$navi_lang	= ( $tmp_clan[$i]['navi_lang'] ) ? $lang[$tmp_clan[$i]['navi_name']] : $tmp_clan[$i]['navi_name'];
				
			$template->assign_block_vars('_display._clan_row', array(
				'NAME'		=> ( $tmp_clan[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $tmp_clan[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $tmp_clan[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $navi_order != '10' )				? '<a href="' . append_sid("$file?mode=_order&amp;type=' . NAVI_CLAN . '&amp;move=-15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $navi_order != $max_clan['max'] )	? '<a href="' . append_sid("$file?mode=_order&amp;type=' . NAVI_CLAN . '&amp;move=+15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$navi_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$navi_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry_clan', array()); }
	
	if ( $tmp_com )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_com)); $i++ )
		{
			$navi_id	= $tmp_com[$i]['navi_id'];
			$navi_order	= $tmp_com[$i]['navi_order'];
			$navi_lang	= ( $tmp_com[$i]['navi_lang'] ) ? $lang[$tmp_com[$i]['navi_name']] : $tmp_com[$i]['navi_name'];
				
			$template->assign_block_vars('_display._com_row', array(
				'NAME'		=> ( $tmp_com[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $tmp_com[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $tmp_com[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $navi_order != '10' )				? '<a href="' . append_sid("$file?mode=_order&amp;type=' . NAVI_COM . '&amp;move=-15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $navi_order != $max_com['max'] )	? '<a href="' . append_sid("$file?mode=_order&amp;type=' . NAVI_COM . '&amp;move=+15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$navi_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$navi_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry_com', array()); }
	
	if ( $tmp_misc )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_misc)); $i++ )
		{
			$navi_id	= $tmp_misc[$i]['navi_id'];
			$navi_order	= $tmp_misc[$i]['navi_order'];
			$navi_lang	= ( $tmp_misc[$i]['navi_lang'] ) ? $lang[$tmp_misc[$i]['navi_name']] : $tmp_misc[$i]['navi_name'];
				
			$template->assign_block_vars('_display._misc_row', array(
				'NAME'		=> ( $tmp_misc[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $tmp_misc[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $tmp_misc[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $navi_order != '10' )				? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NAVI_MISC . "&amp;move=-15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $navi_order != $max_misc['max'] )	? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NAVI_MISC . "&amp;move=+15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$navi_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$navi_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry_misc', array()); }
	
	if ( $tmp_user )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_user)); $i++ )
		{
			$navi_id	= $tmp_user[$i]['navi_id'];
            $navi_order	= $tmp_user[$i]['navi_order'];
			$navi_lang	= ( $tmp_user[$i]['navi_lang'] ) ? $lang[$tmp_user[$i]['navi_name']] : $tmp_user[$i]['navi_name'];
				
			$template->assign_block_vars('_display._user_row', array(
				'NAME'		=> ( $tmp_user[$i]['navi_intern']) ? sprintf($lang['sprintf_intern'], $navi_lang) : $navi_lang,
				'LANG'		=> ( $tmp_user[$i]['navi_lang'] ) ? '<img src="' . $images['icon_option_lang'] . '" alt="" />' : '<img src="' . $images['icon_option_lang2'] . '" alt="" />',
				'SHOW'		=> ( $tmp_user[$i]['navi_show'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $navi_order != '10' )				? '<a href="' . append_sid("$file?mode=_order&amp;type=' . NAVI_USER . '&amp;move=-15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $navi_order != $max_user['max'] )	? '<a href="' . append_sid("$file?mode=_order&amp;type=' . NAVI_USER . '&amp;move=+15&amp;$url=$navi_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$navi_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$navi_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry_user', array()); }

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>