<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_teams'] )
	{
		$module['_headmenu_05_teams']['_submenu_settings'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$header_sub	= ( isset($_POST['cancel_sub']) ) ? true : false;
	$current	= '_submenu_teams';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('teams');
	
	$error	= '';
	$index	= '';
	$log	= LOG_SEK_TEAM;
	$url	= POST_TEAMS_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$smode		= request('smode', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_teams'] . '/';
	$path_games	= $root_path . $settings['path_games'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['team']);
	$fields	= '';
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_teams'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header )		? redirect('admin/' . append_sid($file, true)) : false;
	( $header_sub )	? redirect('admin/' . append_sid("$file?mode=_member&$url=$data_id")) : false;
	
	( $smode == '_user_delete' ) ? $mode = '_user_delete' : false;
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_teams.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$max	= get_data_max(TEAMS, 'team_order', '');
					$data	= array(
								'team_name'		=> request('team_name', 2),
								'team_desc'		=> '',
								'team_game'		=> '-1',
								'team_logo'		=> '',
								'team_flag'		=> '',
								'team_navi'		=> '0',
								'team_awards'	=> '0',
								'team_wars'		=> '0',
								'team_join'		=> '1',
								'team_fight'	=> '1',
								'team_view'		=> '1',
								'team_show'		=> '1',
								'team_order'	=> $max['max'] + 10,
								
								'game_size'		=> '16',
								'game_image'	=> $images['icon_acp_spacer'],
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(TEAMS, $data_id, false, 3, 1);
					
					$data['game_image'] = ( $data['team_game'] == '-1' ) ? $images['icon_acp_spacer'] : $path_games . $data['game_image'];
					
					$template->assign_block_vars('_input._member', array());
				}
				else
				{
					$data = array(
								'team_name'		=> request('team_name', 2),
								'team_desc'		=> request('team_desc', 3),
								'team_game'		=> request('game_image', 2),
								'team_navi'		=> request('team_navi', 0),
								'team_awards'	=> request('team_awards', 0),
								'team_wars'		=> request('team_wars', 0),
								'team_join'		=> request('team_join', 0),
								'team_fight'	=> request('team_fight', 0),
								'team_view'		=> request('team_view', 0),
								'team_show'		=> request('team_show', 0),
								'team_order'	=> request('team_order', 0),
								'team_logo'		=> request('image_logo', 2),
								'team_flag'		=> request('image_flag', 2),
								
								'game_image'	=> request('game_image', 2),
								'game_size'		=> '16',
								'team_logo'		=> request('image_logo', 2),
								'team_flag'		=> request('image_flag', 2),
								'temp_logo'		=> request_file('temp_logo'),
								'temp_flag'		=> request_file('temp_flag'),
							);
					
					$data['team_game']	= img_num(GAMES, 'game', request('game_image', 2));
					$data['game_image']	= ( $data['team_game'] == '-1' ) ? $images['icon_acp_spacer'] : $path_games . img_name(GAMES, 'game', $data['team_game']);
				}
				
				if ( $settings['team_logo_upload'] || $settings['team_flag_upload'] )
				{
					$template->assign_block_vars('_input._upload', array() );
					
					if ( $settings['team_logo_upload'] && file_exists(@cms_realpath($path_team_logo)) )
					{
						$template->assign_block_vars('_input._upload._logo', array() );
						
						( $data['team_logo'] ) ? $template->assign_block_vars('_input._upload._logo._img', array()) : '';
					}
					
					if ( $settings['team_flag_upload'] && file_exists(@cms_realpath($team_flag_path)) )
					{
						$template->assign_block_vars('_input._upload._flag', array());
						
						( $data['team_flag'] ) ? $template->assign_block_vars('_input._upload._flag._img', array()) : '';
					}
					
					$logo_up_info = sprintf($lang['sprintf_upload_info'], $settings['team_logo_max_height'], $settings['team_logo_max_width'], round($settings['team_logo_filesize']/1024) );
					$flag_up_info = sprintf($lang['sprintf_upload_info'], $settings['team_flag_max_height'], $settings['team_flag_max_width'], round($settings['team_flag_filesize']/1024) );
				}
			
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= '<input type="hidden" name="team_order" value="' . $data['team_order'] . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'			=> $acp_title,
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['team'], $data['team_name']),
					'L_MEMBER'			=> $lang['members'],
					
					'L_INFOS'			=> $lang['team_infos'],
					'L_LOGO_SETTINGS'	=> $lang['team_logo_setting'],
					'L_MENU_SETTINGS'	=> $lang['team_menu_setting'],
					
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['team']),
					'L_DESC'			=> sprintf($lang['sprintf_desc'], $lang['team']),
					'L_GAME'			=> $lang['team_game'],
					'L_NAVI'			=> $lang['team_navi'],
					'L_AWARDS'			=> $lang['team_sawards'],
					'L_FIGHTS'			=> $lang['team_sfight'],
					'L_JOIN'			=> $lang['team_join'],
					'L_FIGHT'			=> $lang['team_fight'],
					'L_VIEW'			=> $lang['team_view'],
					'L_SHOW'			=> $lang['team_show'],
					'L_LOGO_UP'			=> $lang['team_logo_upload'],
					'L_LOGO_UP_INFO'	=> $logo_up_info,
					'L_LOGO_CURRENT'	=> $lang['team_logo_current'],
					'L_FLAG_UP'			=> $lang['team_flag_upload'],
					'L_FLAG_UP_INFO'	=> $flag_up_info,
					'L_FLAG_CURRENT'	=> $lang['team_flag_current'],
					
					'NAME'				=> $data['team_name'],
					'DESC'				=> $data['team_desc'],
					
					'GAME_PATH'			=> $path_games,
					'GAME_IMAGE'		=> $data['game_image'],
					'GAME_SIZE'			=> $data['game_size'],
					
					'S_NAVI_NO'			=> ( !$data['team_navi'] )	? ' checked="checked"' : '',
					'S_NAVI_YES'		=> ( $data['team_navi'] )	? ' checked="checked"' : '',
					'S_SAWARDS_NO'		=> ( !$data['team_awards'] )? ' checked="checked"' : '',
					'S_SAWARDS_YES'		=> ( $data['team_awards'] )	? ' checked="checked"' : '',
					'S_SWARS_NO'		=> ( !$data['team_wars'] )	? ' checked="checked"' : '',
					'S_SWARS_YES'		=> ( $data['team_wars'] )	? ' checked="checked"' : '',
					'S_JOIN_NO'			=> ( !$data['team_join'] )	? ' checked="checked"' : '',
					'S_JOIN_YES'		=> ( $data['team_join'] )	? ' checked="checked"' : '',
					'S_FIGHT_NO'		=> ( !$data['team_fight'] )	? ' checked="checked"' : '',
					'S_FIGHT_YES'		=> ( $data['team_fight'] )	? ' checked="checked"' : '',
					'S_VIEW_NO'			=> ( !$data['team_view'] )	? ' checked="checked"' : '',
					'S_VIEW_YES'		=> ( $data['team_view'] )	? ' checked="checked"' : '',
					'S_SHOW_NO'			=> ( !$data['team_show'] )	? ' checked="checked"' : '',
					'S_SHOW_YES'		=> ( $data['team_show'] )	? ' checked="checked"' : '',
					
					'LOGO'				=> $path_dir . $data['team_logo'],
					'IMG_LOGO'			=> $data['team_logo'],
					'FLAG'				=> $path_dir . $data['team_flag'],
					'IMG_FLAG'			=> $data['team_flag'],
					
					'S_GAME'			=> select_box('game', 'select', $data['team_game']),
					'S_MEMBER'			=> append_sid("$file?mode=_member&amp;$url=$data_id"),
					
					'S_ACTION'			=> append_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$sql_logo		= '';
					$sql_flag		= '';
					
					$team_name		= request('team_name', 2);
					$team_desc		= request('team_desc', 3);
					$team_game		= request('game_image', 2);
					$team_navi		= request('team_navi', 0);
					$team_join		= request('team_join', 0);
					$team_fight		= request('team_fight', 0);
					$team_wars		= request('team_wars', 0);
					$team_awards	= request('team_awards', 0);
					$team_show		= request('team_show', 0);
					$team_view		= request('team_view', 0);
					$game_image		= request('game_image', 2);
					$team_logo		= request('image_logo');
					$team_flag		= request('image_flag');
					$team_order		= ( request('team_order_new', 0) ) ? request('team_order_new', 0) : request('team_order', 0);
					
					$temp_logo		= request_file('temp_logo');
					$temp_flag		= request_file('temp_flag');
					
					$data['team_game']	= img_num(GAMES, 'game', request('game_image', 2));
					$data['game_image']	= ( $data['team_game'] == '-1' ) ? $images['icon_acp_spacer'] : $path_games . img_name(GAMES, 'game', $data['team_game']);
					
					if ( $team_game )
					{
						$sql = "SELECT game_id, game_image FROM " . GAMES . " WHERE game_image = '$team_game'";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}						
						$game_info = $db->sql_fetchrow($result);
						
						$game_id	= ( $game_info['game_id'] ) ? $game_info['game_id'] : '-1';
						$game_img	= ( $game_info['game_id'] ) ? $game_info['game_image'] : $images['icon_acp_spacer'];
					}
					else
					{
						$game_id	= '-1';
						$game_img	= $images['icon_acp_spacer'];
					}
					
					if ( $temp_logo['temp'] && $settings['team_logo_upload'] )
					{
						$sql_logo = image_upload($mode, 'image_team_logo', 'team_logo', '', $data['team_logo'], '', $path_dir, $temp_logo['temp'], $temp_logo['name'], $temp_logo['size'], $temp_logo['type'], $error);

					}
					
					if ( $temp_flag['temp'] && $settings['team_flag_upload'] )
					{
						$sql_flag = image_upload($mode, 'image_team_flag', 'team_flag', '', $data['team_flag'], '', $path_dir, $temp_flag['temp'], $temp_flag['name'], $temp_flag['size'], $temp_flag['type'], $error);
					}
					
					$error .= ( !$team_name )			? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
					$error .= ( !$team_desc )			? ( $error ? '<br />' : '' ) . $lang['msg_select_text'] : '';
					$error .= ( $team_game == '-1' )	? ( $error ? '<br />' : '' ) . $lang['msg_select_game'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$max	= get_data_max(TEAMS, 'team_order', '');
							$next	= $max['max'] + 10;
							
							$sql = "INSERT INTO " . TEAMS . " (team_name, team_desc, team_game, team_navi, team_join, team_fight, team_wars, team_awards, team_show, team_view, team_logo, team_flag, team_create, team_order) VALUES ('$team_name', '$team_desc', '$game_id', '$team_navi', '$team_join', '$team_fight', '$team_wars', '$team_awards', '$team_show', '$team_view', '$sql_logo', '$sql_flag', '" . time() . "', '$next')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
				
							$message = $lang['create_team'] . sprintf($lang['click_return_team'], '<a href="' . append_sid($file) . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . TEAMS . " SET
										team_name	= '$team_name',
										team_desc	= '$team_desc',
										team_game	= '$game_id',
										team_navi	= '$team_navi',
										team_join	= '$team_join',
										team_fight	= '$team_fight',
										team_wars	= '$team_wars',
										team_awards	= '$team_awards',
										team_show	= '$team_show',
										team_view	= '$team_view',
										$sql_logo
										$sql_flag
										team_update	= " . time() . ",
										team_order	= '$team_order'
									WHERE team_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_team']
								. sprintf($lang['click_return_team'], '<a href="' . append_sid($file) . '">', '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
						}
						
						orders(TEAMS);
						
					#	#$oCache -> sCachePath = './../cache/';
					#	#$oCache -> deleteCache('list_teams');
					#	#$oCache -> deleteCache('subnavi_list_teams');
					
						log_add(LOG_ADMIN, $log, $mode, $team_name);
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
			
				update(TEAMS, 'team', $move, $data_id);
				orders(TEAMS);
				
				log_add(LOG_ADMIN, $log, 'ACP_TEAM_ORDER');
				
			#	#$oCache -> sCachePath = './../cache/';
			#	#$oCache -> deleteCache('list_teams');
			#	#$oCache -> deleteCache('subnavi_list_teams');
				
				$index = true;
	
				break;
	
			case '_member':
			
				$template->set_filenames(array('body' => 'style/acp_teams.tpl'));
				$template->assign_block_vars('_member', array());
				
				$data = get_data(TEAMS, $data_id, 1);
				
				$sql = "SELECT tu.rank_id, tu.team_join, tu.team_mod, u.user_id, u.username, u.user_regdate, r.rank_title
							FROM " . USERS . " u, " . TEAMS_USERS . " tu
								LEFT JOIN " . RANKS . " r ON r.rank_id = tu.rank_id
							WHERE tu.team_id = $data_id AND tu.user_id = u.user_id
						ORDER BY r.rank_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$members = $db->sql_fetchrowset($result);
				
				$team_mods = $team_members = array();
				
				if ( $members )
				{
					foreach ( $members as $member => $row )
					{
						if ( $row['team_mod'] )
						{
							$team_mods[] = $row;
						}
						else
						{
							$team_members[] = $row;
						}
					}
				}
				
				if ( $team_mods )
				{
					for ( $i = 0; $i < count($team_mods); $i++ )
					{
						$template->assign_block_vars('_member._mod_row', array(
							'ID'	=> $team_mods[$i]['user_id'],
							'NAME'	=> $team_mods[$i]['username'],
							'RANK'	=> $team_mods[$i]['rank_title'],
							'REG'	=> create_date($userdata['user_dateformat'], $team_mods[$i]['user_regdate'], $userdata['user_timezone']),
							'JOIN'	=> create_date($userdata['user_dateformat'], $team_mods[$i]['team_join'], $userdata['user_timezone']),
						));
					}
				}
				else { $template->assign_block_vars('_member._no_moderators', array()); }
			
				if ( $team_members )
				{
					for ( $i = 0; $i < count($team_members); $i++ )
					{
						$template->assign_block_vars('_member._member_row', array(
							'ID'	=> $team_members[$i]['user_id'],
							'NAME'	=> $team_members[$i]['username'],
							'RANK'	=> $team_members[$i]['rank_title'],
							'REG'	=> create_date($userdata['user_dateformat'], $team_members[$i]['user_regdate'], $userdata['user_timezone']),
							'JOIN'	=> create_date($userdata['user_dateformat'], $team_members[$i]['team_join'], $userdata['user_timezone']),
						));
					}
				}
				else { $template->assign_block_vars('_member._no_members', array()); }
				
				$sql_id = '';
				
				if ( $members )
				{
					foreach ( $members as $member )
					{
						$ids[] = $member['user_id'];
					}
					
					$sql_id .= " AND NOT user_id IN (" . implode(', ', $ids) . ")";
				}
				
				$sql = "SELECT username, user_id FROM " . USERS . " WHERE user_id <> " . ANONYMOUS . $sql_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$missing_users = $db->sql_fetchrowset($result);
				
				$s_users = '';
				
				if ( $missing_users )
				{
					$template->assign_block_vars('_member._user_add', array());
					
					$s_users .= "<select class=\"select\" name=\"members_select[]\" rows=\"5\" multiple>";
					
					foreach ( $missing_users as $info => $value )
					{
						$s_users .= "<option value=\"" . $value['user_id'] . "\">" . sprintf($lang['sprintf_select_format'], $value['username']) . "</option>";
					}
					
					$s_users .= "</select>";
				}				
				
			#	$sql = "SELECT rank_id, rank_title FROM " . RANKS . " WHERE rank_type = " . RANK_TEAM . " ORDER BY rank_order ASC";
			#	if ( !($result = $db->sql_query($sql)) )
			#	{
			#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			#	}
			#	$rank = $db->sql_fetchrowset($result);
			
				$rank = get_data_array(RANKS, 'rank_type = ' . RANK_TEAM, 'rank_special DESC, rank_order', 'ASC');
				
				$s_options = '<select class="selectsmall" name="smode" id="smode">';
				$s_options .= '<option value="">&raquo;&nbsp;' . $lang['common_option_select'] . '</option>';
				$s_options .= '<option value="_user_level">&raquo;&nbsp;' . $lang['select_ranks_rights'] . '</option>';
				$s_options .= '<option value="_user_setrank">&raquo;&nbsp;' . $lang['select_rank'] . '</option>';
				/* Disable by IE, Chromo, Safari but only function by FF!
				foreach ( $rank as $info => $value )
				{
					$s_options .= '<option value="_user_setrank" onclick="document.list.rank_id.value=' . $value['rank_id'] . '">&raquo;&nbsp;' . sprintf($lang['msg_select_rank_set'], $value['rank_title']) . '&nbsp;</option>';
					$s_options .= '<option value="_user_setrank" onClick="fill(\'' . $value['rank_id'] . '\');">&raquo;&nbsp;' . sprintf($lang['msg_select_rank_set'], $value['rank_title']) . '&nbsp;</option>';
					$s_options .= '<option value="_user_setrank" onClick="this.form.rank_id.value=' . $value['rank_id'] . '");">&raquo;&nbsp;' . sprintf($lang['msg_select_rank_set'], $value['rank_title']) . '&nbsp;</option>';
					$s_options .= '<option value="_user_setrank" title="' . $value['rank_id'] . '">&raquo;&nbsp;' . sprintf($lang['msg_select_rank_set'], $value['rank_title']) . '&nbsp;</option>';
				}
				*/
				
				$s_options .= '<option value="_user_delete">&raquo;&nbsp;' . $lang['common_delete'] . '</option>';
				$s_options .= '</select>';
				
				$s_ranks = '<select class="selectsmall" name="rank_id" id="rank">';
				foreach ( $rank as $info => $value )
				{
					$s_ranks .= '<option value="' . $value['rank_id'] . '">&raquo;&nbsp;' . sprintf($lang['msg_select_rank_set'], $value['rank_title']) . '&nbsp;</option>';
				}
				$s_ranks .= '</select>';
				
				$sql = "SELECT rank_id FROM " . RANKS . " WHERE rank_type = " . RANK_TEAM . " AND rank_standard = 1";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$rank_default = $db->sql_fetchrow($result);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> $acp_title,
					'L_INPUT'	=> sprintf($lang['sprintf_update'], $lang['team'], $data['team_name']),
					'L_MEMBER'	=> $lang['members'],
					'L_EXPLAIN'	=> $lang['team_member_explain'],
					
					'L_ADD'			=> $lang['team_member_add'],
					'L_ADD_EXPLAIN'	=> $lang['team_member_add_explain'],
					
					'L_MODERATOR'	=> ( count($team_mods) > '1' ) ? $lang['team_moderators'] : $lang['team_moderator'],
					'L_MEMBERS'		=> ( count($team_members) > '1' ) ? $lang['team_members'] : $lang['team_member'],
					
					'L_ADD_RANK'	=> $lang['team_set_rank'],
					'L_ADD_MOD'		=> $lang['team_set_moderator'],
				
					'L_USERNAME'			=> $lang['username'],
					'L_REGISTER'			=> $lang['register'],
					'L_JOIN'				=> $lang['joined'],
					'L_RANK'				=> $lang['rank_team'],
					
				#	'L_NO_MEMBERS' => $lang['no_members'],
				#	'L_NO_MODERATORS' => $lang['no_moderators'],
					
					'S_RANK_SELECT'			=> select_box('ranks', 'selectsmall', $rank_default['rank_id'], RANK_TEAM),
					'S_USERS'	=> $s_users,
					'S_OPTIONS'	=> $s_options,
					'S_RANKS'	=> $s_ranks,
					'S_INPUT'	=> append_sid("$file?mode=_update&amp;$url=$data_id"),
					
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( $smode == '_user_create' || $smode == '_user_setrank' || $smode == '_user_level' )
				{
					$error		= '';
					$members	= request('members', 4);
					$members_s	= request('members_select', 4);
					$rank_id	= request('rank_id', 0);
					$moderator	= request('moderator', 0);
					
					debug($_POST);
					
					if ( !$members && !$members_s )
					{
						$error = $lang['msg_select_no_members'];
					}
					else
					{
						if ( $smode == '_user_create' )
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
								
							$user_id_ary_im = implode(', ', $user_id_ary);
							
							$sql = "SELECT user_id
										FROM " . TEAMS_USERS . "
										WHERE user_id IN ($user_id_ary_im)
											AND team_id = $data_id";
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
										'team_id'		=> (int) $data_id,
										'rank_id'		=> (int) $rank_id,
										'team_join'		=> (int) time(),
										'team_mod'		=> (int) $moderator,
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
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$lang_type = 'create_team_user';
						}
						else if ( $smode == '_user_setrank' )
						{
							foreach ($members as $user_id)
							{
								$sql = "UPDATE " . TEAMS_USERS . " SET rank_id = $rank_id WHERE team_id = $data_id AND user_id = $user_id";
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$lang_type = 'update_team_rank';
						}
						else if ( $smode == '_user_level' )
						{
							$members_select	= array();
							
							for ( $i = 0; $i < count($members); $i++ )
							{
								$members_select[] = $members[$i];
							}
							
							if ( count($members_select) > 0 )
							{
								$user_ids = implode(', ', $members_select);
								
								$sql = "SELECT user_id
											FROM " . TEAMS_USERS . "
											WHERE team_id = $data_id
												AND team_mod = 1
												AND user_id IN ($user_ids)";
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
									$sql = "UPDATE " . TEAMS_USERS . "
												SET team_mod = 0
												WHERE team_id = $data_id
													AND user_id IN (" . implode(', ', $team_mods) . ")";
									if ( !($result = $db->sql_query($sql)) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
								}
								
								$sql_in = ( empty($team_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $team_mods) . ')');
								
								$sql = "UPDATE " . TEAMS_USERS . "
											SET team_mod = 1
											WHERE team_id = $data_id
												AND user_id IN (" . implode(', ', $members_select) . ")" . $sql_in;
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
						
							$lang_type = 'update_team_level';
						}
						
						$message = $lang[$lang_type]
							. sprintf($lang['click_return_team'], '<a href="' . append_sid($file) . '">', '</a>')
							. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=_member&$url=$data_id") . '">', '</a>');	
						log_add(LOG_ADMIN, LOG_SEK_MATCH, $lang_type);
						message(GENERAL_MESSAGE, $message);
					}
					
					if ( $error )
					{
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');
			
				break;

			case '_user_delete':

				$data		= get_data(TEAMS, $data_id, 1);
				$members	= request('members', 4);
				
				if ( $members && $confirm )
				{
					$sql = "DELETE FROM " . TEAMS_USERS . " WHERE user_id IN ($members) AND team_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					log_add(LOG_ADMIN, $log, 'ACP_TEAM_USER_DELETE', $data['team_name']);
					
					#$oCache -> sCachePath = './../cache/';
					#$oCache -> deleteCache('list_teams');
					#$oCache -> deleteCache('subnavi_list_teams');
					
					$message = $lang['delete_team_user'] . sprintf($lang['click_return_team'], '<a href="' . append_sid($file) . '">', '</a>');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $members && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm_sub.tpl'));

					$sql_in = implode(', ', $members);
					
					$fields = '<input type="hidden" name="mode" value="_user_delete" /><input type="hidden" name="' . $url . '" value="' . $data_id . '" /><input type="hidden" name="members" value="' . $sql_in . '" />';
					
					$sql = "SELECT username, user_id FROM " . USERS . " WHERE user_id IN ($sql_in)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$ary_userid = $ary_username = array();
							
					while ($row = $db->sql_fetchrow($result))
					{
						$ary_userid[]	= (int)		$row['user_id'];
						$ary_username[]	= (string)	$row['username'];
					}
					$db->sql_freeresult($result);
					
					$usernames = implode(', ', $ary_username);

					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], sprintf($lang['delete_confirm_team_user'], $usernames), $data['team_name']),
					
						'S_FIELDS'		=> $fields,
						'S_ACTION'		=> append_sid($file),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_team']);
				}
				
				$template->pparse('body');
			
				break;
			
			case '_delete':
			
				$data = get_data(TEAMS, $data_id, 1);
			
				if ( $data_id && $confirm )
				{
				/*
					$sql = "SELECT * FROM " . TEAMS . " WHERE team_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$logo_file	= $data['team_logo'];
				#	$logo_type	= $data['team_logo_type'];
					$flag_file	= $data['team_flag'];
				#	$flag_type	= $data['team_logos_type'];
					
					$logo_file = basename($logo_file);
					$flag_file = basename($flag_file);
					
					if ( $logo_type == LOGO_UPLOAD && $logo_file != '' )
					{
						if ( @file_exists(@cms_realpath($root_path . $settings['path_team_logo'] . '/' . $logo_file)) )
						{
							@unlink($root_path . $settings['path_team_logo'] . '/' . $logo_file);
						}
					}
					
					if ( $flag_type == LOGO_UPLOAD && $logos_file != '' )
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
				*/
					log_add(LOG_ADMIN, $log, 'ACP_TEAM_DELETE', $team_info['team_name']);
					
					#$oCache -> sCachePath = './../cache/';
					#$oCache -> deleteCache('list_teams');
					#$oCache -> deleteCache('subnavi_list_teams');
					
					$message = $lang['team_delete'] . sprintf($lang['click_return_team'], '<a href="' . append_sid($file) . '">', '</a>');
					message(GENERAL_MESSAGE, $message);
		
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
					$fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . $url . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_team'], $data['team_name']),
						
						'S_FIELDS'		=> $fields,
						'S_ACTION'		=> append_sid($file),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_team']);
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
	
	$template->set_filenames(array('body' => 'style/acp_teams.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';

	$template->assign_vars(array(
		'L_HEAD'	=> $acp_title,
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['team']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['team']),
		
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_COUNT'	=> $lang['count'],
		'L_MEMBER'	=> $lang['common_member'],
		
		'S_CREATE'	=> append_sid("$file?mode=_create"),
		'S_ACTION'	=> append_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max = get_data_max(TEAMS, 'team_order', '');
	
	$sql = "SELECT t.*, g.*
				FROM " . TEAMS . " t, " . GAMES . " g
					WHERE t.team_game = g.game_id
				ORDER BY t.team_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $db->sql_numrows($result) )
	{
		$type = '';
		$data = array();
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$data[$row['team_id']] = $row;
			$data[$row['team_id']]['total_members'] = 0;
		}
		$db->sql_freeresult($result);
		
		$sql = "SELECT COUNT(tu.user_id) AS total_members, tu.team_id
					FROM " . TEAMS_USERS . " tu
						WHERE tu.team_id IN (" . implode(', ', array_keys($data)) . ")
					GROUP BY tu.team_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ($row = $db->sql_fetchrow($result))
		{
			$data[$row['team_id']]['total_members'] = $row['total_members'];
		}
		$db->sql_freeresult($result);
		
		foreach ( $data as $team_id => $row )
		{
			$team_id	= $row['team_id'];
			$game_size	= ( !$row['game_size'] ) ? '16' : $row['game_size'];

			$template->assign_block_vars('_display._team_row', array(
				'NAME'	=> $row['team_name'],
				'GAME'	=> ($row['game_image'] != '-1') ? '<img src="' . $root_path . $settings['path_games'] . '/' . $row['game_image'] . '"  width="' . $game_size . '" height="' . $game_size . '" alt="" />' : ' - ',
				'COUNT'	=> $row['total_members'],
				
				'MOVE_UP'	=> ( $row['team_order'] != '10' )			? '<a href="' . append_sid("$file?mode=_order&amp;move=-15&amp;$url=$team_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $row['team_order'] != $max['max'] )	? '<a href="' . append_sid("$file?mode=_order&amp;move=+15&amp;$url=$team_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_MEMBER'	=> append_sid("$file?mode=_member&amp;$url=$team_id"),
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$team_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$team_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_team', array()); }

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>