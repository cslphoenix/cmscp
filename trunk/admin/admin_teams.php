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
	
	if ( $userauth['auth_teams'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_teams']['_submenu_settings'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	$current	= '_submenu_teams';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/teams.php');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_TEAMS_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);	
	$path_dir	= $root_path . $settings['path_teams'] . '/';
	$show_index	= '';
	
	if ( !$userauth['auth_teams'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}

	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_teams.php', true));
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_teams.tpl'));
				$template->assign_block_vars('team_edit', array());
				
				if ( $mode == '_create' && !request('submit', 2) )
				{
					$data = array(
						'team_name'			=> request('team_name', 2),
						'team_desc'			=> '',
						'team_game'			=> '-1',
						'team_navi'			=> '0',
						'team_awards'		=> '0',
						'team_wars'			=> '0',
						'team_join'			=> '1',
						'team_fight'		=> '1',
						'team_view'			=> '1',
						'team_show'			=> '1',
					);
				}
				else if ( $mode == '_update' && !request('submit', 2) )
				{
					$data = get_data(TEAMS, $data_id, 1);
					
					$template->assign_block_vars('team_edit.member', array());
				}
				else
				{
					$data = array(
						'team_name'			=> request('team_name', 2),
						'team_desc'			=> request('team_desc', 3),
						'team_game'			=> request('team_game', 0),
						'team_navi'			=> request('team_navi', 0),
						'team_awards'		=> request('team_awards', 0),
						'team_wars'			=> request('team_wars', 0),
						'team_join'			=> request('team_join', 0),
						'team_fight'		=> request('team_fight', 0),
						'team_view'			=> request('team_view', 0),
						'team_show'			=> request('team_show', 0),
					);
				}
				
				$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
				$s_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $data_id . '" />';
				
				
				
				$game_empty	= $images['icon_acp_spacer'];
				$game_size	= $data['game_size'];
				$game_image = ( $mode == '_create' ) ? $images['icon_acp_spacer'] : ($data['game_id'] == '-1') ? $images['icon_acp_spacer'] : $game_path . $data['game_image'];
				
			#	$logo_up_explain	= $settings['team_logo_max_height'] . ' x ' . $settings['team_logo_max_width'] . ' / ' . round($settings['team_logo_filesize']/1024) . ' KBs';
			#	$logos_up_explain	= $settings['team_logos_max_height'] . ' x ' . $settings['team_logos_max_width'] . ' / ' . round($settings['team_logos_filesize']/1024) . ' KBs';
				
			#	if ( $settings['team_logo_upload'] && $settings['team_logos_upload'] )
			#	{
			#		$template->assign_block_vars('team_edit.logo_upload', array() );
			#	}
				
			#	if ( $settings['team_logo_upload'] && file_exists(@cms_realpath($path_team_logo)) )
			#	{
			#		$template->assign_block_vars('team_edit.logo_upload.team_logo_upload', array() );
			#	}
				
			#	if ( $settings['team_logos_upload'] && file_exists(@cms_realpath($team_logos_path)) )
			#	{
			#		$template->assign_block_vars('team_edit.logo_upload.team_logos_upload', array() );
			#	}
				


				$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $team_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['team']),
					'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['team'], $data['team_name']),
					'L_MEMBER'		=> $lang['team_view_member'],
					
					'L_NAME'			=> $lang['team_name'],
					'L_DESC'			=> $lang['team_desc'],
					'L_GAME'			=> $lang['team_game'],
					'L_NAVI'			=> $lang['team_navi'],
					'L_AWARDS'			=> $lang['team_sawards'],
					'L_FIGHTS'			=> $lang['team_sfight'],
					'L_JOIN'			=> $lang['team_join'],
					'L_FIGHT'			=> $lang['team_fight'],
					'L_VIEW'			=> $lang['team_view'],
					'L_SHOW'			=> $lang['team_show'],
					
				#	'L_LOGO_UP'		=> $lang['team_logo_upload'],
				#	'L_LOGO_LINK'		=> $lang['team_logo_link'],
				#	'L_UPLOAD_LOGO'			=> $lang['logo_upload'],
				#	'L_LOGOS_UP'		=> $lang['team_logos_upload'],
				#	'L_LOGOS_LINK'		=> $lang['team_logos_link'],
				#	'L_UPLOAD_LOGOS'		=> $lang['logos_upload'],
					
					'L_INFOS'			=> $lang['team_infos'],
					'L_LOGO_SETTINGS'	=> $lang['team_logo_setting'],
					'L_MENU_SETTINGS'	=> $lang['team_menu_setting'],
					
					'TEAM_NAME'			=> $data['team_name'],
					'TEAM_DESC'			=> $data['team_desc'],
					
					'CHECKED_NAVI_NO'		=> (!$data['team_navi']) ? ' checked="checked"' : '',
					'CHECKED_NAVI_YES'		=> ($data['team_navi']) ? ' checked="checked"' : '',
					'CHECKED_SAWARDS_NO'	=> (!$data['team_awards']) ? ' checked="checked"' : '',
					'CHECKED_SAWARDS_YES'	=> ($data['team_awards']) ? ' checked="checked"' : '',
					'CHECKED_SWARS_NO'		=> (!$data['team_wars']) ? ' checked="checked"' : '',
					'CHECKED_SWARS_YES'		=> ($data['team_wars']) ? ' checked="checked"' : '',
					'CHECKED_JOIN_NO'		=> (!$data['team_join']) ? ' checked="checked"' : '',
					'CHECKED_JOIN_YES'		=> ($data['team_join']) ? ' checked="checked"' : '',
					'CHECKED_FIGHT_NO'		=> (!$data['team_fight']) ? ' checked="checked"' : '',
					'CHECKED_FIGHT_YES'		=> ($data['team_fight']) ? ' checked="checked"' : '',
					'CHECKED_VIEW_NO'		=> (!$data['team_view']) ? ' checked="checked"' : '',
					'CHECKED_VIEW_YES'		=> ($data['team_view']) ? ' checked="checked"' : '',
					'CHECKED_SHOW_NO'		=> (!$data['team_show']) ? ' checked="checked"' : '',
					'CHECKED_SHOW_YES'		=> ($data['team_show']) ? ' checked="checked"' : '',
					
					'GAME_PATH'				=> $root_path . $settings['path_games'],
					'GAME_IMAGE'			=> $game_image,
					'GAME_SIZE'				=> $game_size,
					
			#		'TEAM_LOGO'				=> $logo_img,
			#		'TEAM_LOGOS'			=> $logos_img,
					
			#		'L_LOGO_UP_EXPLAIN'		=> $logo_up_explain,
			#		'L_LOGOS_UP_EXPLAIN'	=> $logos_up_explain,
					'S_GAME'			=> select_box('game', 'select', $data['team_game']),
			#		'S_GAME'			=> _select_game($data['team_game']),
					
					
					
					'S_FIELDS'		=> $s_fields,
					'S_MEMBER'			=> append_sid('admin_teams.php?mode=_member&amp;' . POST_TEAMS_URL . '=' . $team_id),
					'S_ACTION'			=> append_sid('admin_teams.php'),
				));
				
				if ( request('submit', 2) )
				{
					$team_name		= request('team_name', 2);
					$team_desc		= request('team_desc', 3);
					$game_image		= request('game_image', 2);
					$team_navi		= request('team_navi', 0);
					$team_join		= request('team_join', 0);
					$team_fight		= request('team_fight', 0);
					$team_wars		= request('team_wars', 0);
					$team_awards	= request('team_awards', 0);
					$team_show		= request('team_show', 0);
					$team_view		= request('team_view', 0);
					
					$error = '';
					$error .= ( !$game_name ) ? $lang['msg_select_name'] : '';
					
					$error = '';
					$error .= ( !$team_name )				? $lang['msg_select_name'] : '';
					$error .= ( $news_category == '-1' )	? ( $error ? '<br>' : '' ) . $lang['msg_select_newscat'] : '';
					$error .= ( !$news_text )				? ( $error ? '<br>' : '' ) . $lang['msg_select_text'] : '';
					
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
							$max_row	= get_data_max(GAMES, 'game_order', '');
							$next_order	= $max_row['max'] + 10;
							
							$sql = "INSERT INTO " . GAMES . " (game_name, game_image, game_size, game_order) VALUES ('$game_name', '$game_image', '$game_size', '$next_order')";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>');
							log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'create_game');
						}
						else
						{
							$sql = "UPDATE " . GAMES . " SET game_name = '$game_name', game_image = '$game_image', game_size = '$game_size' WHERE game_id = $data_id";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_game']
								. sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_games.php?mode=_update&amp;' . POST_GAMES_URL . '=' . $data_id) . '">', '</a>');
							log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'update_game');
						}
						message(GENERAL_MESSAGE, $message);
					}
				}
			
				$template->pparse('body');
				
			break;
			
			case '_create_save':
			
				$team_name		= request('team_name', 2);
				$team_desc		= request('team_desc', 'textfeld_clean');
				$game_image		= request('game_image', 2);
				$team_navi		= request('team_navi', 0);
				$team_join		= request('team_join', 0);
				$team_fight		= request('team_fight', 0);
				$team_wars		= request('team_wars', 0);
				$team_awards	= request('team_awards', 0);
				$team_show		= request('team_show', 0);
				$team_view		= request('team_view', 0);
				
				if ( !$team_name )
				{
					message(GENERAL_ERROR, $lang['msg_select_name'] . $lang['back']);
				}
				
//				$team_logo_upload		= ( $HTTP_POST_FILES['team_logo']['tmp_name'] != "none") ? $HTTP_POST_FILES['team_logo']['tmp_name'] : '';
//				$team_logo_name			= ( !empty($HTTP_POST_FILES['team_logo']['name']) ) ? $HTTP_POST_FILES['team_logo']['name'] : '';
//				$team_logo_size			= ( !empty($HTTP_POST_FILES['team_logo']['size']) ) ? $HTTP_POST_FILES['team_logo']['size'] : 0;
//				$team_logo_filetype		= ( !empty($HTTP_POST_FILES['team_logo']['type']) ) ? $HTTP_POST_FILES['team_logo']['type'] : '';
//				
//				$team_logos_upload		= ( $HTTP_POST_FILES['team_logos']['tmp_name'] != "none") ? $HTTP_POST_FILES['team_logos']['tmp_name'] : '';
//				$team_logos_name		= ( !empty($HTTP_POST_FILES['team_logos']['name']) ) ? $HTTP_POST_FILES['team_logos']['name'] : '';
//				$team_logos_size		= ( !empty($HTTP_POST_FILES['team_logos']['size']) ) ? $HTTP_POST_FILES['team_logos']['size'] : 0;
//				$team_logos_filetype	= ( !empty($HTTP_POST_FILES['team_logos']['type']) ) ? $HTTP_POST_FILES['team_logos']['type'] : '';
//				
//				$logo_sql = '';
//				$logos_sql = '';
//				
//				if (!empty($team_logo_upload) && $settings['team_logo_upload'])
//				{
//					$logo_sql = team_logo_upload($mode, 'n', $team['team_logo'], $team['team_logo_type'], $team_logo_upload, $team_logo_name, $team_logo_size, $team_logo_filetype);
//				}
//				
//				if ( $logo_sql == '' )
//				{
//					$logo_sql = "'', " . LOGO_NONE;
//				}
//
//				if (!empty($team_logos_upload) && $settings['team_logos_upload'])
//				{
//					$logos_sql = team_logo_upload($mode, 's', $team['team_logos'], $team['team_logos_type'], $team_logos_upload, $team_logos_name, $team_logos_size, $team_logos_filetype);
//				}
//				
//				if ( $logos_sql == '' )
//				{
//					$logos_sql = "'', " . LOGO_NONE;
//				}
				
				$max_row	= get_data_max(TEAMS, 'team_order', '');
				$max_order	= $max_row['max'];
				$next_order	= $max_order + 10;
				
				if ( $game_image )
				{
					$sql = 'SELECT * FROM ' . GAMES . " WHERE game_image = '" . $game_image . "'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$game_info = $db->sql_fetchrow($result);
					
					$team_game = ( $game_info['game_id'] ) ? $game_info['game_id'] : '-1';
				}
				else
				{
					$team_game = '-1';
				}
				
				$sql = "INSERT INTO " . TEAMS . " (team_name, team_desc, team_game, team_navi, team_join, team_fight, team_wars, team_awards, team_show, team_view, team_create, team_order)
					VALUES (
								'" . str_replace("\'", "''", $team_name) . "',
								'" . str_replace("\'", "''", $team_desc) . "',
								'$team_game',
								'$team_navi',
								'$team_join',
								'$team_fight',
								'$team_wars',
								'$team_awards',
								'$team_show',
								'$team_view',
								'" . time() . "',
								'$next_order')";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
			#	$oCache -> sCachePath = './../cache/';
			#	$oCache -> deleteCache('list_teams');
			#	$oCache -> deleteCache('subnavi_list_teams');
	
				$message = $lang['create_team'] . sprintf($lang['click_return_team'], '<a href="' . append_sid('admin_teams.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'create_team');
				message(GENERAL_MESSAGE, $message);

			break;
			
			case '_update_save':
			
				$team_name		= request('team_name', 2);
				$team_desc		= request('team_desc', 'textfeld_clean');
				$game_image		= request('game_image', 2);
				$team_navi		= request('team_navi', 0);
				$team_join		= request('team_join', 0);
				$team_fight		= request('team_fight', 0);
				$team_wars		= request('team_wars', 0);
				$team_awards	= request('team_awards', 0);
				$team_show		= request('team_show', 0);
				$team_view		= request('team_view', 0);
				
				if ( !$team_name )
				{
					message(GENERAL_ERROR, $lang['msg_select_name'] . $lang['back']);
				}
				
				if ( $game_image )
				{
					$sql = 'SELECT * FROM ' . GAMES . " WHERE game_image = '" . $game_image . "'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$game_info = $db->sql_fetchrow($result);
					
					$team_game = ( $game_info['game_id'] ) ? $game_info['game_id'] : '-1';
				}
				else
				{
					$team_game = '-1';
				}
				
#				$team_logo_upload		= ( $HTTP_POST_FILES['team_logo']['tmp_name'] != "none") ? $HTTP_POST_FILES['team_logo']['tmp_name'] : '';
#				$team_logo_name			= ( !empty($HTTP_POST_FILES['team_logo']['name']) ) ? $HTTP_POST_FILES['team_logo']['name'] : '';
#				$team_logo_size			= ( !empty($HTTP_POST_FILES['team_logo']['size']) ) ? $HTTP_POST_FILES['team_logo']['size'] : 0;
#				$team_logo_filetype		= ( !empty($HTTP_POST_FILES['team_logo']['type']) ) ? $HTTP_POST_FILES['team_logo']['type'] : '';
				
#				$team_logos_upload		= ( $HTTP_POST_FILES['team_logos']['tmp_name'] != "none") ? $HTTP_POST_FILES['team_logos']['tmp_name'] : '';
#				$team_logos_name		= ( !empty($HTTP_POST_FILES['team_logos']['name']) ) ? $HTTP_POST_FILES['team_logos']['name'] : '';
#				$team_logos_size		= ( !empty($HTTP_POST_FILES['team_logos']['size']) ) ? $HTTP_POST_FILES['team_logos']['size'] : 0;
#				$team_logos_filetype	= ( !empty($HTTP_POST_FILES['team_logos']['type']) ) ? $HTTP_POST_FILES['team_logos']['type'] : '';

#				$logo_sql = '';
#				$logos_sql = '';
				
#				if ( isset($HTTP_POST_VARS['logodel']) && $mode == 'editteam' )
#				{
#					$logo_sql = team_logo_delete('n', $team_info['team_logo_type'], $team_info['team_logo']);
#				}
#				else if (!empty($team_logo_upload) && $settings['team_logo_upload'])
#				{
#					$logo_sql = team_logo_upload($mode, 'n', $team_info['team_logo'], $team_info['team_logo_type'], $team_logo_upload, $team_logo_name, $team_logo_size, $team_logo_filetype);
#				}
#				
#				if ( $logo_sql == '' )
#				{
#					$logo_sql = '';
#				}
#
#				if ( isset($HTTP_POST_VARS['logosdel']) && $mode == 'editteam' )
#				{
#					$logos_sql = team_logo_delete('s', $team_info['team_logos_type'], $team_info['team_logos']);
#				}
#				else if (!empty($team_logos_upload) && $settings['team_logos_upload'])
#				{
#					$logos_sql = team_logo_upload($mode, 's', $team_info['team_logos'], $team_info['team_logos_type'], $team_logos_upload, $team_logos_name, $team_logos_size, $team_logos_filetype);
#				}
#				
#				if ( $logos_sql == '' )
#				{
#					$logos_sql = '';
#				}
				
				$sql = "UPDATE " . TEAMS . " SET
							team_name	= '" . str_replace("\'", "''", $team_name) . "',
							team_desc	= '" . str_replace("\'", "''", $team_desc) . "',
							team_game	= '$team_game',
							team_navi	= '$team_navi',
							team_join	= '$team_join',
							team_fight	= '$team_fight',
							team_wars	= '$team_wars',
							team_awards	= '$team_awards',
							team_show	= '$team_show',
							team_view	= '$team_view',
							team_update	= " . time() . "
						WHERE team_id = $team_id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
			#	$oCache -> sCachePath = './../cache/';
			#	$oCache -> deleteCache('list_teams');
			#	$oCache -> deleteCache('subnavi_list_teams');
				
				$message = $lang['team_update'] . sprintf($lang['click_return_team'], '<a href="' . append_sid('admin_teams.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_edit');
				message(GENERAL_MESSAGE, $message);
	
				break;
			
			case '_order':
			
				update(TEAMS, 'team', $move, $team_id);
				orders('teams');
				
			#	$oCache -> sCachePath = './../cache/';
			#	$oCache -> deleteCache('list_teams');
			#	$oCache -> deleteCache('subnavi_list_teams');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'ACP_TEAM_ORDER');
				
				$show_index = TRUE;
	
				break;
	
			case '_member':
			
				$template->set_filenames(array('body' => 'style/acp_teams.tpl'));
				$template->assign_block_vars('team_member', array());
				
				$sql = 'SELECT tu.rank_id, tu.team_join, tu.team_mod, u.user_id, u.username, u.user_regdate, r.rank_title
							FROM ' . USERS . ' u, ' . TEAMS_USERS . ' tu
								LEFT JOIN ' . RANKS . ' r ON r.rank_id = tu.rank_id
							WHERE tu.team_id = ' . $team_id . ' AND tu.user_id = u.user_id
						ORDER BY r.rank_order';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$team_members = $db->sql_fetchrowset($result);
				
				$team_mods = $team_nomods = array();
				
				if ( $team_members )
				{
					foreach ( $team_members as $member => $row )
					{
						if ( $row['team_mod'] )
						{
							$team_mods[] = $row;
						}
						else
						{
							$team_nomods[] = $row;
						}
					}
				}
				
				if ( $team_mods )
				{
					for ( $i = 0; $i < count($team_mods); $i++ )
					{
						$template->assign_block_vars('team_member.mods_row', array(
							'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
							'USER_ID'		=> $team_mods[$i]['user_id'],
							'USERNAME'		=> $team_mods[$i]['username'],
							'REGISTER'		=> create_date($userdata['user_dateformat'], $team_mods[$i]['user_regdate'], $userdata['user_timezone']),
							'JOINED'		=> create_date($userdata['user_dateformat'], $team_mods[$i]['team_join'], $userdata['user_timezone']),
							'RANK'			=> $team_mods[$i]['rank_title']
						));
					}
				}
				else
				{
					$template->assign_block_vars('team_member.switch_no_moderators', array());
					$template->assign_vars(array('L_NO_MODERATORS' => $lang['no_moderators']));
				}
			
				if ( $team_nomods )
				{
					for ( $i = 0; $i < count($team_nomods); $i++ )
					{
						$template->assign_block_vars('team_member.nomods_row', array(
							'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
							'USER_ID'		=> $team_nomods[$i]['user_id'],
							'USERNAME'		=> $team_nomods[$i]['username'],
							'REGISTER'		=> create_date($userdata['user_dateformat'], $team_nomods[$i]['user_regdate'], $userdata['user_timezone']),
							'JOINED'		=> create_date($userdata['user_dateformat'], $team_nomods[$i]['team_join'], $userdata['user_timezone']),
							'RANK'			=> $team_nomods[$i]['rank_title']
						));
					}
				}
				else
				{
					$template->assign_block_vars('team_member.switch_no_members', array());
					$template->assign_vars(array('L_NO_MEMBERS' => $lang['no_members']));
				}

				$sql_id = '';
				
				if ( $team_members )
				{
					foreach ( $team_members as $member )
					{
						$ids[] = $member['user_id'];
					}
					
					$sql_id .= " AND NOT user_id IN (" . implode(', ', $ids) . ")";
				}
				
				$sql = 'SELECT username, user_id FROM ' . USERS . ' WHERE user_id <> ' . ANONYMOUS . $sql_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$missing_users = $db->sql_fetchrowset($result);
				
				$s_select_users = '';
				
				if ( $missing_users )
				{
					$template->assign_block_vars('team_member.user_add', array());
					
					$s_select_users .= '<select class="select" name="members_select[]" rows="5" multiple>';
					
					foreach ( $missing_users as $info => $value )
					{
						$s_select_users .= '<option value="' . $value['user_id'] . '">' . $value['username'] . '&nbsp;</option>';
					}
					$s_select_users .= '</select>';
				}				
				
				$sql = 'SELECT rank_id, rank_title FROM ' . RANKS . ' WHERE rank_type = ' . RANK_TEAM . ' ORDER BY rank_order ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$rank = $db->sql_fetchrowset($result);
				
				$s_select_options = '';				
				$s_select_options .= '<select class="postselect" name="mode">';
				$s_select_options .= '<option value="option">&raquo;&nbsp;' . $lang['option_select'] . '</option>';
				$s_select_options .= '<option value="_level">&raquo;&nbsp;' . $lang['msg_select_rank_rights'] . '</option>';
				
				foreach ( $rank as $info => $value )
				{
					$s_select_options .= '<option onClick="this.form.rank_id.value=' . $value['rank_id'] . '" value="change">&raquo;&nbsp;' . sprintf($lang['msg_select_rank_set'], $value['rank_title']) . '&nbsp;</option>';
				}
				
				$s_select_options .= '<option value="deluser">&raquo;&nbsp;' . $lang['common_delete'] . '</option>';
				$s_select_options .= '</select>';
				
				$sql = 'SELECT rank_id FROM ' . RANKS . ' WHERE rank_type = ' . RANK_TEAM . ' AND rank_standard = 1';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$rank_default = $db->sql_fetchrow($result);
				
				$s_fields = '<input type="hidden" name="rank_id" value="" /><input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $team_id . '" />';
				$s_fields2 = '<input type="hidden" name="mode" value="_add_user" /><input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $team_id . '" />';

				$template->assign_vars(array(
					'L_TITLE'			=> $lang['team_head'],
					'L_NEW_EDIT'		=> $lang['team_edit'],
					'L_MEMBER'			=> $lang['team_view_member'],
					'L_ADD_MEMBER'		=> $lang['team_add_member'],
					
					'L_ADD_MEMBER_EX'	=> $lang['team_add_member_ex'],
					'L_ADD'			=> $lang['team_add_member'],
					
					'L_NAME'			=> $lang['team_name'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					
					'L_USERNAME'			=> $lang['username'],
					'L_REGISTER'			=> $lang['register'],
					'L_JOIN'				=> $lang['joined'],
					'L_RANK'				=> $lang['rank'],
					
					'L_MEMBER'				=> $lang['members'],
					'L_MODERATOR'			=> $lang['moderators'],
					
					'L_MARK_ALL'			=> $lang['mark_all'],
					'L_MARK_DEALL'			=> $lang['mark_deall'],
					
					'S_RANK_SELECT'			=> select_box('ranks', 'selectsmall', $rank_default['rank_id'], RANK_TEAM),
					
					'S_SELECT_USERS'		=> $s_select_users,
					'S_SELECT_OPTIONS'		=> $s_select_options,
					
					'S_EDIT'			=> append_sid('admin_teams.php?mode=_edit&amp;' . POST_TEAMS_URL . '=' . $team_id),
					'S_ACTION'			=> append_sid('admin_teams.php'),
					'S_FIELDS'		=> $s_fields,
					'S_HIDDEN_FIELDS2'		=> $s_fields2)
				);
			
				$template->pparse('body');
			
			break;
			
			case 'change':
			
				$member		= ( isset($HTTP_POST_VARS['members']) ) ? $HTTP_POST_VARS['members'] : '';
				$rank_id	= ( isset($HTTP_POST_VARS['rank_id']) ) ? $HTTP_POST_VARS['rank_id'] : '';
			
				if ( !sizeof($member) )
				{
					message(GENERAL_ERROR, $lang['team_no_select']);
				}
				
				$members = $member;
				
				foreach ($members as $user_id)
				{
					$sql = "UPDATE " . TEAMS_USERS . " SET rank_id = $rank_id WHERE team_id = $team_id AND user_id = $user_id";
					$result = $db->sql_query($sql);
				}
				
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_change_rank');
				
				$message = $lang['team_change_member']
					. sprintf($lang['click_return_team'], '<a href="' . append_sid('admin_teams.php') . '">', '</a>')
					. sprintf($lang['click_return_team_member'], '<a href="' . append_sid('admin_teams.php?mode=member&' . POST_TEAMS_URL . '=' .$team_id) . '">', '</a>');				
				message(GENERAL_MESSAGE, $message);
				
				break;
				
			case '_level':
			
				$members_mark	= request('members', 'only');
				$members_select	= array();
				
				for ( $i = 0; $i < count($members_mark); $i++ )
				{
					$members_select[] = $members_mark[$i];
				}
				
				if ( count($members_select) > 0 )
				{
					$user_ids = implode(', ', $members_select);
					
					$sql = 'SELECT user_id
								FROM ' . TEAMS_USERS . '
								WHERE team_id = ' . $team_id . '
									AND team_mod = 1
									AND user_id IN (' . $user_ids . ')';
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$team_mods = array();
					while ( $row = $db->sql_fetchrow($result) )
					{
						$team_mods[] = $row['user_id'];
					}
					$db->sql_freeresult($result);

					if ( count($team_mods) > 0)
					{
						$sql = 'UPDATE ' . TEAMS_USERS . '
									SET team_mod = 0
									WHERE team_id = ' . intval($team_id) . '
										AND user_id IN (' . implode(', ', $team_mods) . ')';
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					$sql_in = ( empty($team_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $team_mods) . ')');
					
					$sql = 'UPDATE ' . TEAMS_USERS . '
								SET team_mod = 1
								WHERE team_id = ' . intval($team_id) . '
									AND user_id IN (' . implode(', ', $members_select) . ')' . $sql_in;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['team_change_member']
						. sprintf($lang['click_return_team'], '<a href="' . append_sid('admin_teams.php') . '">', '</a>')
						. sprintf($lang['click_return_team_member'], '<a href="' . append_sid('admin_teams.php?mode=_member&' . POST_TEAMS_URL . '=' .$team_id) . '">', '</a>');	
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_change_level');
					message(GENERAL_MESSAGE, $message);

				}
				
				break;

			case '_add_user':
			
				$members	= request('members', 'only');
				$members_s	= request('members_select', 'only');
				$rank_id	= request('rank_id', 0);
				$mod		= request('mod', 0);
				
				if ( !$members && !$members_s )
				{
					message(GENERAL_ERROR, $lang['team_select_no_users'] . $lang['back']);
				}
				else
				{
					if ( $members )
					{
						$members = trim($members, ', ');
						$members = trim($members, ',');
						
						$username_ary = array_unique(explode(', ', $members));
						
						$which_ary = 'username_ary';
						
						if ($$which_ary && !is_array($$which_ary))
						{
							$$which_ary = array($$which_ary);
						}
						
						$sql_in = $$which_ary;
						unset($$which_ary);
						
						$sql_in = implode("', '", $sql_in);
						
						$user_id_ary = $username_ary = array();
						
						$sql = 'SELECT *
									FROM ' . USERS . '
									WHERE username IN ("' . $sql_in . '")';
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_MESSAGE, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if (!($row = $db->sql_fetchrow($result)))
						{
							$db->sql_freeresult($result);
							message(GENERAL_ERROR, $lang['team_select_no_new'] . $lang['back']);
						}
						
						do
						{
							$username_ary[$row['user_id']] = $row['username'];
							$user_id_ary[] = $row['user_id'];
						}
						while ($row = $db->sql_fetchrow($result));
						$db->sql_freeresult($result);
					}
					
					$user_id_ary = ( $members ) ? $user_id_ary : $members_s;
						
					$user_id_ary_im = implode('", "', $user_id_ary);
					
					$sql = 'SELECT user_id
								FROM ' . TEAMS_USERS . '
								WHERE user_id IN ("' . $user_id_ary_im . '")
									AND team_id = ' . $team_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$add_id_ary = $user_id_ary_im = array();
					
					while ($row = $db->sql_fetchrow($result))
					{
						$add_id_ary[] = (int) $row['user_id'];
					}
					$db->sql_freeresult($result);
						
					$add_id_ary = array_diff($user_id_ary, $add_id_ary);
					
					if (!sizeof($add_id_ary) && !sizeof($user_id_arya))
					{
						message(GENERAL_ERROR, $lang['team_select_no_new'] . $lang['back']);
					}
					
					if (sizeof($add_id_ary))
					{
						$sql_ary = array();
				
						foreach ($add_id_ary as $user_id)
						{
							$sql_ary[] = array(
								'user_id'		=> (int) $user_id,
								'team_id'		=> (int) $team_id,
								'rank_id'		=> (int) $rank_id,
								'team_join'		=> (int) time(),
								'team_mod'		=> $mod,
							);
						}
				
						if (!sizeof($sql_ary))
						{
							message(GENERAL_ERROR, 'Fehler', '', __LINE__, __FILE__, $sql);
						}
						
						$ary = array();
						foreach ($sql_ary as $id => $_sql_ary)
						{
							$values = array();
							foreach ($_sql_ary as $key => $var)
							{
								$values[] = intval($var);
							}
							$ary[] = '(' . implode(', ', $values) . ')';
						}
			
						
						$sql = 'INSERT INTO ' . TEAMS_USERS . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary);
						$result = $db->sql_query($sql);
					}

					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_add_member');
			
					$message = $lang['team_add_member']
						. sprintf($lang['click_return_team'], '<a href="' . append_sid('admin_teams.php') . '">', '</a>')
						. sprintf($lang['click_return_team_member'], '<a href="' . append_sid('admin_teams.php?mode=member&' . POST_TEAMS_URL . '=' .$team_id) . '">', '</a>');
					message(GENERAL_MESSAGE, $message);
				}
				
			break;
			
			case 'deluser':
			
				$members = $_POST['members'];
				
				
				if (!$_POST['members'])
				{
					message(GENERAL_ERROR, $lang['team_no_select']);
				}
				
				$sql_in = implode(", ", $members);
				
				$sql = "DELETE FROM " . TEAMS_USERS . " WHERE user_id IN ($sql_in) AND team_id = $team_id";
				$result = $db->sql_query($sql);
				
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_delete_member');
			
				$message = $lang['team_del_member']
					. sprintf($lang['click_return_team'], '<a href="' . append_sid('admin_teams.php') . '">', '</a>')
					. sprintf($lang['click_return_team_member'], '<a href="' . append_sid('admin_teams.php?mode=member&' . POST_TEAMS_URL . '=' .$team_id) . '">', '</a>');				
				message(GENERAL_MESSAGE, $message);
			
			break;
			
			case 'team_delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $team_id && $confirm )
				{
					$sql = 'SELECT * FROM ' . TEAMS . " WHERE team_id = $team_id";
					if (!($result = $db->sql_query($sql)))
					{
						message(GENERAL_ERROR, 'Error getting team information', '', __LINE__, __FILE__, $sql);
					}
			
					if (!($team_info = $db->sql_fetchrow($result)))
					{
						message(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
					
					$logo_file	= $team_info['team_logo'];
					$logo_type	= $team_info['team_logo_type'];
					$logos_file	= $team_info['team_logos'];
					$logos_type	= $team_info['team_logos_type'];
					
					$logo_file = basename($logo_file);
					$logos_file = basename($logos_file);
					
					if ( $logo_type == LOGO_UPLOAD && $logo_file != '' )
					{
						if ( @file_exists(@cms_realpath($root_path . $settings['path_team_logo'] . '/' . $logo_file)) )
						{
							@unlink($root_path . $settings['path_team_logo'] . '/' . $logo_file);
						}
					}
					
					if ( $logos_type == LOGO_UPLOAD && $logos_file != '' )
					{
						if ( @file_exists(@cms_realpath($root_path . $settings['path_team_logos'] . '/' . $logos_file)) )
						{
							@unlink($root_path . $settings['path_team_logos'] . '/' . $logos_file);
						}
					}
				
					$sql = 'DELETE FROM ' . TEAMS . " WHERE team_id = $team_id";
					$result = $db->sql_query($sql, BEGIN_TRANSACTION);
					
					$sql = 'DELETE FROM ' . TEAMS_USERS . " WHERE team_id = $team_id";
					$result = $db->sql_query($sql, END_TRANSACTION);

					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'ACP_TEAM_DELETE', $team_info['team_name']);
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('list_teams');
					$oCache -> deleteCache('subnavi_list_teams');
					
					$message = $lang['team_delete'] . sprintf($lang['click_return_team'], '<a href="' . append_sid('admin_teams.php') . '">', '</a>');
					message(GENERAL_MESSAGE, $message);
		
				}
				else if ( $team_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $team_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_team'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_ACTION'	=> append_sid('admin_teams.php'),
						'S_FIELDS'	=> $s_fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_team']);
				}
			
				$template->pparse('body');
				
			break;
			
			default:
				
				message(GENERAL_ERROR, $lang['no_mode']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_teams.tpl'));
	$template->assign_block_vars('display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';

	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['team']),
		'L_CREATE'		=> sprintf($lang['sprintf_creates'], $lang['team']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['team']),
		
		'L_EXPLAIN'		=> $lang['team_explain'],
		
		'L_MEMBERCOUNT'	=> $lang['team_membercount'],
		'L_MEMBER'				=> $lang['common_member'],
		
		'S_FIELDS'		=> $s_fields,
		'S_CREATE'		=> append_sid('admin_teams.php?mode=_create'),
		'S_ACTION'		=> append_sid('admin_teams.php'),
	));
	
	$max_order	= get_data_max(TEAMS, 'team_order', '');
	
	//	Daten aus DB
	$sql = 'SELECT t.*, g.* FROM ' . TEAMS . ' t, ' . GAMES . ' g WHERE t.team_game = g.game_id ORDER BY t.team_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $db->sql_numrows($result) )
	{
		$type = '';
		$group_data = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$group_data[$row['team_id']] = $row;
			$group_data[$row['team_id']]['total_members'] = 0;
		}
		$db->sql_freeresult($result);
		
		$sql = 'SELECT COUNT(tu.user_id) AS total_members, tu.team_id FROM ' . TEAMS_USERS . ' tu WHERE tu.team_id IN (' . implode(', ', array_keys($group_data)) . ') GROUP BY tu.team_id';
		$result = $db->sql_query($sql);
		
		while ($row = $db->sql_fetchrow($result))
		{
			$group_data[$row['team_id']]['total_members'] = $row['total_members'];
		}
		$db->sql_freeresult($result);
		
		foreach ($group_data as $team_id => $row)
		{
			$team_id = $row['team_id'];
			$game_size = (!$row['game_size']) ? '16' : $row['game_size'];
			
			$icon_up	= ( $row['team_order'] != '10' ) ? '<img src="' . $images['icon_acp_arrow_u'] . '" alt="" />' : '';
			$icon_down	= ( $row['team_order'] != $max_order['max'] ) ? '<img src="' . $images['icon_acp_arrow_d'] . '" alt="" />' : '';
			
			$template->assign_block_vars('display.teams_row', array(
				'NAME'			=> $row['team_name'],
				'GAME'			=> ($row['game_image'] != '-1') ? '<img src="' . $root_path . $settings['path_games'] . '/' . $row['game_image'] . '"  width="' . $game_size . '" height="' . $game_size . '" alt="">' : ' - ',
				'MEMBER_COUNT'	=> $row['total_members'],
				
				'MOVE_UP'			=> ( $row['team_order'] != '10' )				? '<a href="' . append_sid('admin_teams.php?mode=_order&amp;move=-15&amp;' . POST_TEAMS_URL . '=' . $team_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'			=> ( $row['team_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_teams.php?mode=_order&amp;move=15&amp;' . POST_TEAMS_URL . '=' . $team_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_MEMBER'			=> append_sid('admin_teams.php?mode=_member&amp;' . POST_TEAMS_URL . '=' . $team_id),
				'U_UPDATE'			=> append_sid('admin_teams.php?mode=_update&amp;' . POST_TEAMS_URL . '=' . $team_id),
				'U_DELETE'			=> append_sid('admin_teams.php?mode=_delete&amp;' . POST_TEAMS_URL . '=' . $team_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_teams', array());
		$template->assign_vars(array('NO_TEAMS' => $lang['team_empty']));
	}

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>