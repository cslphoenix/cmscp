<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_teams'] || $userdata['user_level'] == ADMIN)
	{
		$module['teams']['teams_over'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	include($root_path . 'includes/functions_admin.php');
	include($root_path . 'includes/functions_selects.php');
	
	if (!$userauth['auth_teams'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}

	if ( $cancel )
	{
		redirect('admin/' . append_sid("admin_teams.php", true));
	}
	
	if ( isset($HTTP_POST_VARS[POST_TEAMS_URL]) || isset($HTTP_GET_VARS[POST_TEAMS_URL]) )
	{
		$team_id = ( isset($HTTP_POST_VARS[POST_TEAMS_URL]) ) ? intval($HTTP_POST_VARS[POST_TEAMS_URL]) : intval($HTTP_GET_VARS[POST_TEAMS_URL]);
	}
	else
	{
		$team_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		if (isset($HTTP_POST_VARS['add']))
		{
			$mode = 'add';
		}
		else
		{
			$mode = '';
		}
	}
	
	$show_index = '';
		
	if ( !empty($mode) )
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
			
				$template->set_filenames(array('body' => './../admin/style/acp_teams.tpl'));
				$template->assign_block_vars('team_edit', array());
				
				if ( $mode == 'edit' )
				{
					$sql = 'SELECT t.*, g.* FROM ' . TEAMS . ' t, ' . GAMES . ' g WHERE t.team_game = g.game_id AND t.team_id = ' . $team_id;
					$result = $db->sql_query($sql);
			
					if (!($team = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
			
					$new_mode = 'editteam';
					$template->assign_block_vars('team_edit.member', array());
				}
				else if ( $mode == 'add' )
				{
					$team = array (
						'team_name'			=> trim($HTTP_POST_VARS['team_name']),
						'team_description'	=> '',
						'team_game'			=> '',
						'team_navi'			=> '0',
						'team_show_awards'	=> '0',
						'team_show_wars'	=> '0',
						'team_join'			=> '1',
						'team_fight'		=> '1',
						'team_view'			=> '1',
						'team_show'			=> '1',
						'game_size'			=> '',
					);
					
					$new_mode = 'addteam';
				}
				
				$path_team_logo		= $root_path . $settings['path_team_logo'];
				$path_team_logos	= $root_path . $settings['path_team_logos'];
				$game_path			= $root_path . $settings['path_game'] . '/';
				
				$team_logo			= ( $mode == 'add' ) ? '' : $team['team_logo'];
				$team_logo_type		= ( $mode == 'add' ) ? '' : $team['team_logo_type'];
				$team_logos			= ( $mode == 'add' ) ? '' : $team['team_logos'];
				$team_logos_type	= ( $mode == 'add' ) ? '' : $team['team_logos_type'];
				
				$logo_img	= '';
				$logo_img	= ( $settings['team_logo_upload'] && $team_logo ) ? '<img src="' . $path_team_logo . '/' . $team_logo . '" alt="" />' : '';
				$logos_img	= '';
				$logos_img	= ( $settings['team_logos_upload'] && $team_logos ) ? '<img src="' . $team_logos_path . '/' . $team_logos . '" alt="" />' : '';
				
				$game_empty	= $root_path . 'images/spacer.gif';
				$game_size	= ($team['game_size']) ? $team['game_size'] : '16';
				$game_image = ($mode == 'add') ? $game_empty : ($team['game_id'] == '-1') ? $game_empty : $game_path . $team['game_image'];

				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $team_id . '" />';
				
				$logo_up_explain	= $settings['team_logo_max_height'] . ' x ' . $settings['team_logo_max_width'] . ' / ' . round($settings['team_logo_filesize']/1024) . ' KBs';
				$logos_up_explain	= $settings['team_logos_max_height'] . ' x ' . $settings['team_logos_max_width'] . ' / ' . round($settings['team_logos_filesize']/1024) . ' KBs';
				
				if ( $settings['team_logo_upload'] && $settings['team_logos_upload'] )
				{
					$template->assign_block_vars('team_edit.logo_upload', array() );
				}
				
				if ( $settings['team_logo_upload'] && file_exists(@phpbb_realpath($path_team_logo)) )
				{
					$template->assign_block_vars('team_edit.logo_upload.team_logo_upload', array() );
				}
				
				if ( $settings['team_logos_upload'] && file_exists(@phpbb_realpath($team_logos_path)) )
				{
					$template->assign_block_vars('team_edit.logo_upload.team_logos_upload', array() );
				}
				
				
				
				$template->assign_vars(array(
					'L_TEAM_TITLE'			=> $lang['team_head'],
					'L_TEAM_NEW_EDIT'		=> ($mode == 'add') ? $lang['team_new_add'] : $lang['team_edit'],
					'L_TEAM_MEMBER'			=> $lang['team_view_member'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_TEAM_NAME'			=> $lang['team_name'],
					'L_TEAM_DESCRIPTION'	=> $lang['team_description'],
					'L_TEAM_GAME'			=> $lang['team_game'],
					
					'L_TEAM_LOGO_UP'		=> $lang['team_logo_upload'],
					'L_TEAM_LOGO_LINK'		=> $lang['team_logo_link'],
					'L_UPLOAD_LOGO'			=> $lang['logo_upload'],
					'L_TEAM_LOGOS_UP'		=> $lang['team_logos_upload'],
					'L_TEAM_LOGOS_LINK'		=> $lang['team_logos_link'],
					'L_UPLOAD_LOGOS'		=> $lang['logos_upload'],
					
					'L_TEAM_NAVI'			=> $lang['team_navi'],
					'L_TEAM_SAWARDS'		=> $lang['team_sawards'],
					'L_TEAM_SFIGHT'			=> $lang['team_sfight'],
					'L_TEAM_JOIN'			=> $lang['team_join'],
					'L_TEAM_FIGHT'			=> $lang['team_fight'],
					'L_TEAM_VIEW'			=> $lang['team_view'],
					'L_TEAM_SHOW'			=> $lang['team_show'],
					
					'L_TEAM_INFOS'			=> $lang['team_infos'],
					'L_LOGO_SETTINGS'		=> $lang['team_logo_setting'],
					'L_MENU_SETTINGS'		=> $lang['team_menu_setting'],
					
					'L_SUBMIT'				=> $lang['common_submit'],
					'L_RESET'				=> $lang['common_reset'],
					'L_YES'					=> $lang['common_yes'],
					'L_NO'					=> $lang['common_no'],
					
					'TEAM_NAME'				=> $team['team_name'],
					'TEAM_DESCRIPTION'		=> $team['team_description'],
					
					'CHECKED_NAVI_NO'		=> (!$team['team_navi']) ? ' checked="checked"' : '',
					'CHECKED_NAVI_YES'		=> ( $team['team_navi']) ? ' checked="checked"' : '',
					'CHECKED_SAWARDS_NO'	=> (!$team['team_show_awards']) ? ' checked="checked"' : '',
					'CHECKED_SAWARDS_YES'	=> ( $team['team_show_awards']) ? ' checked="checked"' : '',
					'CHECKED_SWARS_NO'		=> (!$team['team_show_wars']) ? ' checked="checked"' : '',
					'CHECKED_SWARS_YES'		=> ( $team['team_show_wars']) ? ' checked="checked"' : '',
					'CHECKED_JOIN_NO'		=> (!$team['team_join']) ? ' checked="checked"' : '',
					'CHECKED_JOIN_YES'		=> ( $team['team_join']) ? ' checked="checked"' : '',
					'CHECKED_FIGHT_NO'		=> (!$team['team_fight']) ? ' checked="checked"' : '',
					'CHECKED_FIGHT_YES'		=> ( $team['team_fight']) ? ' checked="checked"' : '',
					'CHECKED_VIEW_NO'		=> (!$team['team_view']) ? ' checked="checked"' : '',
					'CHECKED_VIEW_YES'		=> ( $team['team_view']) ? ' checked="checked"' : '',
					'CHECKED_SHOW_NO'		=> (!$team['team_show']) ? ' checked="checked"' : '',
					'CHECKED_SHOW_YES'		=> ( $team['team_show']) ? ' checked="checked"' : '',
					
					'GAME_PATH'				=> $root_path . $settings['path_game'],
					'GAME_IMAGE'			=> $game_image,
					'GAME_SIZE'				=> $game_size,
					
					'TEAM_LOGO'				=> $logo_img,
					'TEAM_LOGOS'			=> $logos_img,
					
					'L_LOGO_UP_EXPLAIN'		=> $logo_up_explain,
					'L_LOGOS_UP_EXPLAIN'	=> $logos_up_explain,
					
					'S_TEAM_GAME'			=> _select_game($team['team_game']),
					
					'S_TEAM_MEMBER'			=> append_sid("admin_teams.php?mode=member&amp;" . POST_TEAMS_URL . "=" . $team_id),
					'S_TEAM_ACTION'			=> append_sid("admin_teams.php"),
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addteam':

				if ( trim($HTTP_POST_VARS['team_name']) == '' )
				{
					message_die(GENERAL_ERROR, $lang['create_no_name']);
				}
				
				$team_logo_upload		= ( $HTTP_POST_FILES['team_logo']['tmp_name'] != "none") ? $HTTP_POST_FILES['team_logo']['tmp_name'] : '';
				$team_logo_name			= ( !empty($HTTP_POST_FILES['team_logo']['name']) ) ? $HTTP_POST_FILES['team_logo']['name'] : '';
				$team_logo_size			= ( !empty($HTTP_POST_FILES['team_logo']['size']) ) ? $HTTP_POST_FILES['team_logo']['size'] : 0;
				$team_logo_filetype		= ( !empty($HTTP_POST_FILES['team_logo']['type']) ) ? $HTTP_POST_FILES['team_logo']['type'] : '';
				
				$team_logos_upload		= ( $HTTP_POST_FILES['team_logos']['tmp_name'] != "none") ? $HTTP_POST_FILES['team_logos']['tmp_name'] : '';
				$team_logos_name		= ( !empty($HTTP_POST_FILES['team_logos']['name']) ) ? $HTTP_POST_FILES['team_logos']['name'] : '';
				$team_logos_size		= ( !empty($HTTP_POST_FILES['team_logos']['size']) ) ? $HTTP_POST_FILES['team_logos']['size'] : 0;
				$team_logos_filetype	= ( !empty($HTTP_POST_FILES['team_logos']['type']) ) ? $HTTP_POST_FILES['team_logos']['type'] : '';
				
				$logo_sql = '';
				$logos_sql = '';
				
				if (!empty($team_logo_upload) && $settings['team_logo_upload'])
				{
					$logo_sql = team_logo_upload($mode, 'n', $team['team_logo'], $team['team_logo_type'], $team_logo_upload, $team_logo_name, $team_logo_size, $team_logo_filetype);
				}
				
				if ( $logo_sql == '' )
				{
					$logo_sql = "'', " . LOGO_NONE;
				}

				if (!empty($team_logos_upload) && $settings['team_logos_upload'])
				{
					$logos_sql = team_logo_upload($mode, 's', $team['team_logos'], $team['team_logos_type'], $team_logos_upload, $team_logos_name, $team_logos_size, $team_logos_filetype);
				}
				
				if ( $logos_sql == '' )
				{
					$logos_sql = "'', " . LOGO_NONE;
				}
				
				$sql = 'SELECT MAX(team_order) AS max_order FROM ' . TEAMS;
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not get order number from forums table', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				if (!empty($HTTP_POST_VARS['game_image']))
				{
					$sql = 'SELECT * FROM ' . GAMES . " WHERE game_image = '" . str_replace("\'", "''", $HTTP_POST_VARS['game_image']) . "'";
					$result = $db->sql_query($sql);
					
					if (!($game_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
					
					$team_game = ($game_info['game_id']) ? $game_info['game_id'] : '-1';
				}
				else
				{
					$team_game = '-1';
				}
				
				$sql = "INSERT INTO " . TEAMS . " (team_name, team_description, team_game, team_logo, team_logo_type, team_logos , team_logos_type, team_navi, team_join, team_fight, team_show_wars, team_show_awards, team_show, team_view, team_create, team_update, team_order)
					VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['team_name']) . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['team_description']) . "', $team_game, $logo_sql, $logos_sql, '" . intval($HTTP_POST_VARS['team_navi']) . "', '" . intval($HTTP_POST_VARS['team_join']) . "', '" . intval($HTTP_POST_VARS['team_fight']) . "', '" . intval($HTTP_POST_VARS['team_show_wars']) . "', '" . intval($HTTP_POST_VARS['team_show_awards']) . "', '" . intval($HTTP_POST_VARS['team_show']) . "', '" . intval($HTTP_POST_VARS['team_view']) . "', '" . time() . "', '0', $next_order)";
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_add');
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('display_subnavi_teams');
	
				$message = $lang['team_create'] . '<br><br>' . sprintf($lang['click_return_team'], '<a href="' . append_sid("admin_teams.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

			break;
			
			case 'editteam':
			
				$sql = 'SELECT * FROM ' . TEAMS . " WHERE team_id = $team_id";
				$result = $db->sql_query($sql);
		
				if (!($team_info = $db->sql_fetchrow($result)))
				{
					message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
				}
			
				$team_data = $team_info;
				$team_ordert = $_POST;
				
				$new = array_diff($team_ordert, $team_data);

				unset($new[mode]);
				unset($new[send]);
				
				$log_data = '';
				foreach ($new as $index => $wert)
				{
					$log_data .= $index . ': ' . $wert . ', ';
				}
				$log_data = trim($log_data, ', ');
				
				if (!empty($HTTP_POST_VARS['game_image']))
				{
					$sql = 'SELECT * FROM ' . GAMES . " WHERE game_image = '" . str_replace("\'", "''", $HTTP_POST_VARS['game_image']) . "'";
					$result = $db->sql_query($sql);
					
					if (!($game_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
					
					$team_game = ($game_info['game_id']) ? $game_info['game_id'] : '-1';
				}
				else
				{
					$team_game = '-1';
				}
				
				$team_logo_upload		= ( $HTTP_POST_FILES['team_logo']['tmp_name'] != "none") ? $HTTP_POST_FILES['team_logo']['tmp_name'] : '';
				$team_logo_name			= ( !empty($HTTP_POST_FILES['team_logo']['name']) ) ? $HTTP_POST_FILES['team_logo']['name'] : '';
				$team_logo_size			= ( !empty($HTTP_POST_FILES['team_logo']['size']) ) ? $HTTP_POST_FILES['team_logo']['size'] : 0;
				$team_logo_filetype		= ( !empty($HTTP_POST_FILES['team_logo']['type']) ) ? $HTTP_POST_FILES['team_logo']['type'] : '';
				
				$team_logos_upload		= ( $HTTP_POST_FILES['team_logos']['tmp_name'] != "none") ? $HTTP_POST_FILES['team_logos']['tmp_name'] : '';
				$team_logos_name		= ( !empty($HTTP_POST_FILES['team_logos']['name']) ) ? $HTTP_POST_FILES['team_logos']['name'] : '';
				$team_logos_size		= ( !empty($HTTP_POST_FILES['team_logos']['size']) ) ? $HTTP_POST_FILES['team_logos']['size'] : 0;
				$team_logos_filetype	= ( !empty($HTTP_POST_FILES['team_logos']['type']) ) ? $HTTP_POST_FILES['team_logos']['type'] : '';

				$logo_sql = '';
				$logos_sql = '';
				
				if ( isset($HTTP_POST_VARS['logodel']) && $mode == 'editteam' )
				{
					$logo_sql = team_logo_delete('n', $team_info['team_logo_type'], $team_info['team_logo']);
				}
				else if (!empty($team_logo_upload) && $settings['team_logo_upload'])
				{
					$logo_sql = team_logo_upload($mode, 'n', $team_info['team_logo'], $team_info['team_logo_type'], $team_logo_upload, $team_logo_name, $team_logo_size, $team_logo_filetype);
				}
				
				if ( $logo_sql == '' )
				{
					$logo_sql = '';
				}

				if ( isset($HTTP_POST_VARS['logosdel']) && $mode == 'editteam' )
				{
					$logos_sql = team_logo_delete('s', $team_info['team_logos_type'], $team_info['team_logos']);
				}
				else if (!empty($team_logos_upload) && $settings['team_logos_upload'])
				{
					$logos_sql = team_logo_upload($mode, 's', $team_info['team_logos'], $team_info['team_logos_type'], $team_logos_upload, $team_logos_name, $team_logos_size, $team_logos_filetype);
				}
				
				if ( $logos_sql == '' )
				{
					$logos_sql = '';
				}
				
				$sql = "UPDATE " . TEAMS . " SET
							team_name = '" . str_replace("\'", "''", $HTTP_POST_VARS['team_name']) . "',
							team_description = '" . str_replace("\'", "''", $HTTP_POST_VARS['team_description']) . "',
							team_game = $team_game,
							$logo_sql
							$logos_sql
							team_navi = '" . intval($HTTP_POST_VARS['team_navi']) . "',
							team_join = '" . intval($HTTP_POST_VARS['team_join']) . "',
							team_fight = '" . intval($HTTP_POST_VARS['team_fight']) . "',
							team_show_wars = '" . intval($HTTP_POST_VARS['team_show_wars']) . "',
							team_show_awards = '" . intval($HTTP_POST_VARS['team_show_awards']) . "',
							team_show = '" . intval($HTTP_POST_VARS['team_show']) . "',
							team_view = '" . intval($HTTP_POST_VARS['team_view']) . "',
							team_update = " . time() . "
						WHERE team_id = $team_id";
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_edit');
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('display_subnavi_teams');
				
				$message = $lang['team_update'] . '<br><br>' . sprintf($lang['click_return_team'], '<a href="' . append_sid("admin_teams.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
			break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $team_id && $confirm )
				{
					$sql = 'SELECT * FROM ' . TEAMS . " WHERE team_id = $team_id";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Error getting team information', '', __LINE__, __FILE__, $sql);
					}
			
					if (!($team_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
					
					$logo_file	= $team_info['team_logo'];
					$logo_type	= $team_info['team_logo_type'];
					$logos_file	= $team_info['team_logos'];
					$logos_type	= $team_info['team_logos_type'];
					
					$logo_file = basename($logo_file);
					$logos_file = basename($logos_file);
					
					if ( $logo_type == LOGO_UPLOAD && $logo_file != '' )
					{
						if ( @file_exists(@phpbb_realpath($root_path . $settings['path_team_logo'] . '/' . $logo_file)) )
						{
							@unlink($root_path . $settings['path_team_logo'] . '/' . $logo_file);
						}
					}
					
					if ( $logos_type == LOGO_UPLOAD && $logos_file != '' )
					{
						if ( @file_exists(@phpbb_realpath($root_path . $settings['path_team_logos'] . '/' . $logos_file)) )
						{
							@unlink($root_path . $settings['path_team_logos'] . '/' . $logos_file);
						}
					}
				
					$sql = 'DELETE FROM ' . TEAMS . " WHERE team_id = $team_id";
					$result = $db->sql_query($sql, BEGIN_TRANSACTION);
					
					$sql = 'DELETE FROM ' . TEAMS_USERS . " WHERE team_id = $team_id";
					$result = $db->sql_query($sql, END_TRANSACTION);

					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'ACP_TEAM_DELETE', $team_info['team_name']);
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('display_subnavi_teams');
					
					$message = $lang['team_delete'] . '<br><br>' . sprintf($lang['click_return_team'], '<a href="' . append_sid("admin_teams.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
		
				}
				else if ( $team_id && !$confirm )
				{
					$template->set_filenames(array('body' => './../admin/style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $team_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_team'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_teams.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_team']);
				}
			
				$template->pparse('body');
				
			break;
			
			case 'order':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . TEAMS . " SET team_order = team_order + $move WHERE team_id = $team_id";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not change team order', '', __LINE__, __FILE__, $sql);
				}
				
				renumber_order('teams');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'ACP_TEAM_ORDER');
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('display_subnavi_teams');
				
				$show_index = TRUE;
	
			break;
	
			case 'member':
			
				$template->set_filenames(array('body' => './../admin/style/acp_teams.tpl'));
				$template->assign_block_vars('team_member', array());
				
				$sql = 'SELECT tu.rank_id, tu.team_join, tu.team_mod, u.user_id, u.username, u.user_regdate, r.rank_title
							FROM ' . USERS . ' u, ' . TEAMS_USERS . ' tu
								LEFT JOIN ' . RANKS . ' r ON r.rank_id = tu.rank_id
							WHERE tu.team_id = ' . $team_id . ' AND tu.user_id = u.user_id
						ORDER BY r.rank_order';
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
						$class = ($i % 2) ? 'row_class1' : 'row_class2';
						
						$date_register	= create_date('d.m.Y', $team_mods[$i]['user_regdate'], $userdata['user_timezone']);
						$date_joined	= create_date($userdata['user_dateformat'], $team_mods[$i]['team_join'], $userdata['user_timezone']);
						
						$template->assign_block_vars('team_member.mods_row', array(
							'CLASS' 		=> $class,
							'USER_ID'		=> $team_mods[$i]['user_id'],
							'USERNAME'		=> $team_mods[$i]['username'],
							'REGISTER'		=> $date_register,
							'JOINED'		=> $date_joined,
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
						$class = ($i % 2) ? 'row_class1' : 'row_class2';
						
						$date_register	= create_date('d.m.Y', $team_nomods[$i]['user_regdate'], $userdata['user_timezone']);
						$date_joined	= create_date($userdata['user_dateformat'], $team_nomods[$i]['team_join'], $userdata['user_timezone']);
						
						$template->assign_block_vars('team_member.nomods_row', array(
							'CLASS' 		=> $class,
							'USER_ID'		=> $team_nomods[$i]['user_id'],
							'USERNAME'		=> $team_nomods[$i]['username'],
							'REGISTER'		=> $date_register,
							'JOINED'		=> $date_joined,
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
					foreach ($team_members as $member )
					{
						$ids[] = $member['user_id'];
					}
					
					$sql_id .= " AND NOT user_id IN (" . implode(', ', $ids) . ")";
				}
				
				$sql = 'SELECT username, user_id
							FROM ' . USERS . '
							WHERE user_id <> ' . ANONYMOUS . $sql_id;
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
				}
				
				$s_addusers_select = '<select class="select" name="members_select[]" rows="5" multiple>';
				while ($addusers = $db->sql_fetchrow($result))
				{
					$s_addusers_select .= '<option value="' . $addusers['user_id'] . '">' . $addusers['username'] . '&nbsp;</option>';
				}
				$s_addusers_select .= '</select>';
				
				$sql = 'SELECT rank_id, rank_title FROM ' . RANKS . ' WHERE rank_type = ' . RANK_TEAM . ' ORDER BY rank_order';
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
				}
				
				$s_action_options = '<select class="postselect" name="mode">';
				$s_action_options .= '<option value="option">&raquo; ' . $lang['option_select'] . '</option>';
				$s_action_options .= '<option value="change_level">&raquo; Gruppenrechte geben/nehmen</option>';
				
				while ($rank = $db->sql_fetchrow($result))
				{
					$s_action_options .= '<option onClick="this.form.rank_id.value=' . $rank['rank_id'] . '" value="change">&raquo; ' . sprintf($lang['status_set'], $rank['rank_title']) . '&nbsp;</option>';
				}
				
				$s_action_options .= '<option value="deluser">&raquo; ' . $lang['delete'] . '</option>';
				$s_action_options .= '</select>';
				
				$s_hidden_fields = '<input type="hidden" name="rank_id" value="" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $team_id . '" />';
				
				$s_hidden_fields2 = '<input type="hidden" name="mode" value="adduser" />';
				$s_hidden_fields2 .= '<input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $team_id . '" />';

				$template->assign_vars(array(
					'L_TEAM_TITLE'			=> $lang['team_head'],
					'L_TEAM_NEW_EDIT'		=> $lang['team_edit'],
					'L_TEAM_MEMBER'			=> $lang['team_view_member'],
					'L_TEAM_ADD_MEMBER'		=> $lang['team_add_member'],
					
					'L_TEAM_ADD_MEMBER_EX'	=> $lang['team_add_member_ex'],
					'L_TEAM_ADD'			=> $lang['team_add_member'],
					
					'L_TEAM_NAME'			=> $lang['team_name'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					
					'L_USERNAME'			=> $lang['username'],
					'L_REGISTER'			=> $lang['register'],
					'L_JOIN'				=> $lang['joined'],
					'L_RANK'				=> $lang['rank'],
					
					'L_MEMBER'				=> $lang['members'],
					'L_MODERATOR'			=> $lang['moderators'],
					
					'L_MARK_ALL'			=> $lang['mark_all'],
					'L_MARK_DEALL'			=> $lang['mark_deall'],
					
					'S_RANK_SELECT'			=> _select_rank(0, RANK_TEAM),
					
					'S_ACTION_ADDUSERS'		=> $s_addusers_select,
					'S_ACTION_OPTIONS'		=> $s_action_options,
					
					'S_TEAM_EDIT'			=> append_sid("admin_teams.php?mode=edit&amp;" . POST_TEAMS_URL . "=" . $team_id),
					'S_TEAM_ACTION'			=> append_sid("admin_teams.php"),
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_HIDDEN_FIELDS2'		=> $s_hidden_fields2)
				);
			
				$template->pparse('body');
			
			break;
			
			case 'change':
			
				$member		= ( isset($HTTP_POST_VARS['members']) ) ? $HTTP_POST_VARS['members'] : '';
				$rank_id	= ( isset($HTTP_POST_VARS['rank_id']) ) ? $HTTP_POST_VARS['rank_id'] : '';
			
				if ( !sizeof($member) )
				{
					message_die(GENERAL_ERROR, $lang['team_no_select']);
				}
				
				$members = $member;
				
				foreach ($members as $user_id)
				{
					$sql = "UPDATE " . TEAMS_USERS . " SET rank_id = $rank_id WHERE team_id = $team_id AND user_id = $user_id";
					$result = $db->sql_query($sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_change_rank');
				
				$message = $lang['team_change_member']
					. '<br><br>' . sprintf($lang['click_return_team'], '<a href="' . append_sid("admin_teams.php") . '">', '</a>')
					. '<br><br>' . sprintf($lang['click_return_team_member'], '<a href="' . append_sid("admin_teams.php?mode=member&t=$team_id") . '">', '</a>');				
				message_die(GENERAL_MESSAGE, $message);
				
				break;
				
			case 'change_level':
			
				$members_select = array();
				$members_mark = count($HTTP_POST_VARS['members']);
				
				for ( $i = 0; $i < $members_mark; $i++ )
				{
					if ( intval($HTTP_POST_VARS['members'][$i]) )
					{
						$members_select[] = intval($HTTP_POST_VARS['members'][$i]);
					}
				}
				
				if ( count($members_select) > 0 )
				{
					$user_ids = implode(', ', $members_select);
					
					$sql = 'SELECT user_id
								FROM ' . TEAMS_USERS . '
								WHERE team_id = ' . $team_id . '
									AND team_mod = 1
									AND user_id IN (' . $user_ids . ')';
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					$sql_in = ( empty($team_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $team_mods) . ')');
					
					$sql = 'UPDATE ' . TEAMS_USERS . '
								SET team_mod = 1
								WHERE team_id = ' . intval($team_id) . '
									AND user_id IN (' . implode(', ', $members_select) . ')' . $sql_in;
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_change_level');
					
					$message = $lang['team_change_member']
						. '<br><br>' . sprintf($lang['click_return_team'], '<a href="' . append_sid("admin_teams.php") . '">', '</a>')
						. '<br><br>' . sprintf($lang['click_return_team_member'], '<a href="' . append_sid("admin_teams.php?mode=member&t=$team_id") . '">', '</a>');				
					message_die(GENERAL_MESSAGE, $message);

				}
				
				break;

			case 'adduser':
			
				$members	= ( isset($HTTP_POST_VARS['members']) ) ? $HTTP_POST_VARS['members'] : '';
				$members_s	= ( isset($HTTP_POST_VARS['members_select']) ) ? $HTTP_POST_VARS['members_select'] : '';
				$rank_id	= ( isset($HTTP_POST_VARS['rank_id']) ) ? $HTTP_POST_VARS['rank_id'] : '';
				$mod		= ( isset($HTTP_POST_VARS['mod']) ) ? 1 : 0 ;
				
				if ( !$members && !$members_s )
				{
					message_die(GENERAL_ERROR, $lang['team_no_select']);
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
							message_die(GENERAL_MESSAGE, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if (!($row = $db->sql_fetchrow($result)))
						{
							$db->sql_freeresult($result);
							message_die(GENERAL_MESSAGE, $lang['team_no_new'], '');
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
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
						message_die(GENERAL_MESSAGE, $lang['team_no_new']);
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
							message_die(GENERAL_ERROR, 'Fehler', '', __LINE__, __FILE__, $sql);
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

					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_add_member');
			
					$message = $lang['team_add_member']
						. '<br><br>' . sprintf($lang['click_return_team'], '<a href="' . append_sid("admin_teams.php") . '">', '</a>')
						. '<br><br>' . sprintf($lang['click_return_team_member'], '<a href="' . append_sid("admin_teams.php?mode=member&t=$team_id") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}
				
			break;
			
			case 'deluser':
			
				$members = $_POST['members'];
				
				
				if (!$_POST['members'])
				{
					message_die(GENERAL_ERROR, $lang['team_no_select']);
				}
				
				$sql_in = implode(", ", $members);
				
				$sql = "DELETE FROM " . TEAMS_USERS . " WHERE user_id IN ($sql_in) AND team_id = $team_id";
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, 'acp_team_delete_member');
			
				$message = $lang['team_del_member']
					. '<br><br>' . sprintf($lang['click_return_team'], '<a href="' . append_sid("admin_teams.php") . '">', '</a>')
					. '<br><br>' . sprintf($lang['click_return_team_member'], '<a href="' . append_sid("admin_teams.php?mode=member&t=$team_id") . '">', '</a>');				
				message_die(GENERAL_MESSAGE, $message);
			
			break;
			
			default:
				message_die(GENERAL_ERROR, $lang['no_mode']);
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	//	Template
	$template->set_filenames(array('body' => './../admin/style/acp_teams.tpl'));
	$template->assign_block_vars('display', array());

	$template->assign_vars(array(
		'L_TEAM_TITLE'			=> $lang['team_head'],
		'L_TEAM_EXPLAIN'		=> $lang['team_explain'],
		
		'L_TEAM_CREATE'			=> $lang['team_add'],
		'L_TEAM_NAME'			=> $lang['team_name'],
		'L_TEAM_GAME'			=> $lang['team_game'],
		'L_TEAM_MEMBERCOUNT'	=> $lang['team_membercount'],
		'L_TEAM_SETTINGS'		=> $lang['settings'],
		'L_TEAM_SETTING'		=> $lang['setting'],
		'L_MEMBER'				=> $lang['member'],
		
		'L_DELETE'				=> $lang['delete'],
		
		'S_TEAM_ACTION'		=> append_sid("admin_teams.php")
	));
	
	$sql = 'SELECT MAX(team_order) AS max FROM ' . TEAMS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$max_order = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	//	Daten aus DB
	$sql = 'SELECT t.*, g.* FROM ' . TEAMS . ' t, ' . GAMES . ' g WHERE t.team_game = g.game_id ORDER BY t.team_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
				'TEAM_NAME'			=> $row['team_name'],
				'TEAM_GAME'			=> ($row['game_image'] != '-1') ? '<img src="' . $root_path . $settings['path_game'] . '/' . $row['game_image'] . '"  width="' . $game_size . '" height="' . $game_size . '" alt="">' : ' - ',
				'TEAM_MEMBER_COUNT'	=> $row['total_members'],
				
				'MOVE_UP'			=> ( $row['team_order'] != '10' )				? '<a href="' . append_sid("admin_teams.php?mode=order&amp;move=-15&amp;" . POST_TEAMS_URL . "=" . $team_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'			=> ( $row['team_order'] != $max_order['max'] )	? '<a href="' . append_sid("admin_teams.php?mode=order&amp;move=15&amp;" . POST_TEAMS_URL . "=" . $team_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_MEMBER'			=> append_sid("admin_teams.php?mode=member&amp;" . POST_TEAMS_URL . "=" . $team_id),
				'U_EDIT'			=> append_sid("admin_teams.php?mode=edit&amp;" . POST_TEAMS_URL . "=" . $team_id),
				'U_DELETE'			=> append_sid("admin_teams.php?mode=delete&amp;" . POST_TEAMS_URL . "=" . $team_id),
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