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

	* @autor:	Sebastian Frickel � 2009
	* @code:	Sebastian Frickel � 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_match'] || $userdata['user_level'] == ADMIN )
	{
		$module['match']['match_over'] = $filename;
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
	
	if ( !$userauth['auth_match'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_match.php', true));
	}
	
	$start		= ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$mode		= request_vars('mode', 1);
	$team_id	= request_vars(POST_TEAM_URL);
	$match_id	= request_vars(POST_MATCH_URL);
	
	$show_index = '';
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case 'match_add':
			case 'match_edit':
			
				$template->set_filenames(array('body' => './../admin/style/acp_match.tpl'));
				$template->assign_block_vars('match_edit', array());
				
				if ( $mode == 'match_edit' )
				{
					$match		= get_data('match', $match_id, 0);
					$new_mode	= 'match_update';
					
					$template->assign_block_vars('match_edit.edit_match', array());
					
					if ( $match['match_date'] > time() )
					{
						$template->assign_block_vars('match_edit.reset_match', array());
					}
				}
				else
				{
					$team_id = (isset($HTTP_POST_VARS['team_id'])) ? intval($HTTP_POST_VARS['team_id']) : '';
					
					$match = array (
						'team_id'				=> $team_id,
						'match_type'			=> '',
						'match_league'			=> '',
						'match_league_url'		=> '',
						'match_date'			=> time(),
						'match_categorie'		=> '0',
						'match_public'			=> '1',
						'match_comments'		=> $settings['comments_matches'],
						'match_rival'			=> '',
						'match_rival_tag'		=> '',
						'match_rival_url'		=> '',
						'match_league_match'	=> '',
						'server'				=> '',
						'server_pw'				=> '',
						'server_hltv'			=> '',
						'server_hltv_pw'		=> '',
						'match_create'			=> '',
						'match_update'			=> '',
					);
					$new_mode = 'match_create';
					
					$template->assign_block_vars('match_edit.new_match', array());
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_MATCH_URL . '" value="' . $match_id . '" />';
				
				$template->assign_vars(array(
					'L_MATCH_TITLE'			=> $lang['match_head'],
					'L_MATCH_NEW_EDIT'		=> ( $mode == 'match_add' ) ? $lang['match_new_add'] : $lang['match_edit'],
					'L_MATCH_DETAILS'		=> $lang['match_details'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_MATCH_TEAM'			=> $lang['match_team'],
					'L_MATCH_TYPE'			=> $lang['match_type'],
					'L_MATCH_CATEGORIE'		=> $lang['match_categorie'],
					
					'L_MATCH_LEAGUE'		=> $lang['match_league'],
					'L_MATCH_LEAGUE_URL'	=> $lang['match_league_url'],
					'L_LEAGUE_MATCH'		=> $lang['league_match'],
					'L_MATCH_DATE'			=> $lang['match_date'],
					'L_MATCH_PUBLIC'		=> $lang['match_public'],
					'L_MATCH_COMMENTS'		=> $lang['match_comments'],
					
					'L_MATCH_RIVAL'			=> $lang['match_rival'],
					'L_MATCH_RIVALTAG'		=> $lang['match_rival_tag'],
					'L_MATCH_RIVALURL'		=> $lang['match_rival_url'],
					'L_MATCH_SERVER'		=> $lang['match_server'],
					'L_MATCH_SERVERPW'		=> $lang['match_serverpw'],
					'L_MATCH_HLTV'			=> $lang['match_hltv'],
					'L_MATCH_HLTVPW'		=> $lang['match_hltvpw'],
					
					'L_MATCH_TEXT'			=> $lang['match_text'],
					'L_TRAINING'			=> $lang['training'],
					'L_TRAINING_DATE'		=> $lang['training_date'],
					'L_TRAINING_MAPS'		=> $lang['training_maps'],
					'L_TRAINING_TEXT'		=> $lang['training_text'],
					
					'L_RESET_LIST'			=> $lang['match_interest_reset'],
					
					'L_SUBMIT'				=> $lang['common_submit'],
					'L_RESET'				=> $lang['common_reset'],
					'L_YES'					=> $lang['common_yes'],
					'L_NO'					=> $lang['common_no'],
					
					'LEAGUE_URL'			=> $match['match_league_url'],
					'LEAGUE_MATCH'			=> $match['match_league_match'],
					'MATCH_RIVAL'			=> $match['match_rival'],
					'MATCH_RIVAL_TAG'		=> $match['match_rival_tag'],
					'MATCH_RIVAL_URL'		=> $match['match_rival_url'],
					'SERVER'				=> $match['server'],
					'SERVER_PW'				=> $match['server_pw'],
					'SERVER_HLTV'			=> $match['server_hltv'],
					'SERVER_HLTV_PW'		=> $match['server_hltv_pw'],
					
					'S_TEAMS'				=> select_box('team', 'select', 'team_id', 'team_name', $match['team_id'], 0),
					'S_TYPE'				=> select_lang_box('select_type_box', 'match_type', $match['match_type'], 'select'),
					'S_LEAGUE'				=> select_lang_box('select_league_box', 'match_league', $match['match_league'], 'select'),
					'S_CATEGORIE'			=> select_lang_box('select_categorie_box', 'match_categorie', $match['match_categorie'], 'select'),
					
					'S_DAY'					=> _select_date('day', 'day',		date('d', $match['match_date'])),
					'S_MONTH'				=> _select_date('month', 'month',	date('m', $match['match_date'])),
					'S_YEAR'				=> _select_date('year', 'year',		date('Y', $match['match_date'])),
					'S_HOUR'				=> _select_date('hour', 'hour',		date('H', $match['match_date'])),
					'S_MIN'					=> _select_date('min', 'min',		date('i', $match['match_date'])),
					
					'S_CHECKED_PUB_NO'		=> ( !$match['match_public'] )		? ' checked="checked"' : '',
					'S_CHECKED_PUB_YES'		=> ( $match['match_public'] )		? ' checked="checked"' : '',
					'S_CHECKED_COM_NO'		=> ( !$match['match_comments'] )	? ' checked="checked"' : '',
					'S_CHECKED_COM_YES'		=> ( $match['match_comments'] )		? ' checked="checked"' : '',
					
					'S_TDAY'				=> _select_date('day', 'tday',		date('d', time())),
					'S_TMONTH'				=> _select_date('month', 'tmonth',	date('m', time())),
					'S_TYEAR'				=> _select_date('year', 'tyear',	date('Y', time())),
					'S_THOUR'				=> _select_date('hour', 'thour',	date('H', time())),
					'S_TMIN'				=> _select_date('min', 'tmin',		date('i', time())),
					
					'S_TDURATION'			=> _select_date('duration', 'dmin',	date('i', time())),
					
					
					'S_MATCH_DETAILS'		=> append_sid('admin_match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_id),
					'S_MATCH_ACTION'		=> append_sid('admin_match.php'),
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields
				));
			
				$template->pparse('body');
				
			break;
			
			case 'match_create':
			
				$group_name			= ( isset($HTTP_POST_VARS['group_name']) )			? trim($HTTP_POST_VARS['group_name']) : '';
				$group_mod			= ( isset($HTTP_POST_VARS['group_mod']) )			? intval($HTTP_POST_VARS['group_mod']) : '';
				$group_access		= ( isset($HTTP_POST_VARS['group_access']) )		? intval($HTTP_POST_VARS['group_access']) : '';
				$group_type			= ( isset($HTTP_POST_VARS['group_type']) )			? intval($HTTP_POST_VARS['group_type']) : '';
				$match_league		= ( isset($HTTP_POST_VARS['match_league']) )		? intval($HTTP_POST_VARS['match_league']) : '';
				$match_categorie	= ( isset($HTTP_POST_VARS['match_categorie']))		? intval($HTTP_POST_VARS['match_categorie']) : '';
				$match_type			= ( isset($HTTP_POST_VARS['match_type']) )			? intval($HTTP_POST_VARS['match_type']) : '0';
				$team_id			= ( isset($HTTP_POST_VARS['team_id']) )				? intval($HTTP_POST_VARS['team_id']) : '0';
			
				$error = ''; 
				$error_msg = '';
			
				if ( $team_id == '0' )
				{
					$error = true;
					$error_msg = $lang['msg_select_team'];
				}
				
				if ( $match_type == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_type'];
				}
				
				if ( $match_categorie == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_cat'];
				}
				
				if ( $match_league == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_league'];
				}
				
				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_date'];
				}
				
				if ( $error )
				{
					message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
				}
								
				$match_date = mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				
				$sql = "INSERT INTO " . MATCH . " (team_id, match_type, match_league, match_league_url, match_league_match, match_date, match_categorie, match_public, match_comments, match_rival, match_rival_tag, match_rival_url, server, server_pw, server_hltv, server_hltv_pw, match_create)
							VALUES ('" . $team_id . "',
								'" . $match_type . "',
								'" . $match_league . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['match_league_url']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['match_league_match']) . "',
								$match_date,
								'" . $match_categorie . "',
								'" . intval($HTTP_POST_VARS['match_public']) . "',
								'" . intval($HTTP_POST_VARS['match_comments']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['match_rival']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['match_rival_tag']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['match_rival_url']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['server']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['server_pw']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['server_hltv']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['server_hltv_pw']) . "',
								'" . time() . "')";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$match_id = $db->sql_nextid();
				
				$sql = "INSERT INTO " . MATCH_DETAILS . " (match_id, details_create) VALUES ($match_id, " . time() . ")";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if (!empty($HTTP_POST_VARS['train']))
				{
					$training_start		= mktime($HTTP_POST_VARS['thour'], $HTTP_POST_VARS['tmin'], 00, $HTTP_POST_VARS['tmonth'], $HTTP_POST_VARS['tday'], $HTTP_POST_VARS['tyear']);
					$training_duration	= mktime($HTTP_POST_VARS['thour'], $HTTP_POST_VARS['tmin'] + $HTTP_POST_VARS['dmin'], 00, $HTTP_POST_VARS['tmonth'], $HTTP_POST_VARS['tday'], $HTTP_POST_VARS['tyear']);
					
					$sql = "INSERT INTO " . TRAINING . " (training_vs, team_id, match_id, training_start, training_duration, training_create, training_maps, training_comment)
						VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['match_rival']) . "',
								'" . intval($HTTP_POST_VARS['team_id']) . "',
								$match_id,
								$training_start,
								$training_duration,
								" . time() . ",
								'" . str_replace("\'", "''", $HTTP_POST_VARS['training_maps']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['training_comment']) . "')";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				$monat = $HTTP_POST_VARS['month'];
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('match_list_open_member');
				$oCache -> deleteCache('match_list_open_guest');
				$oCache -> deleteCache('match_list_close_member');
				$oCache -> deleteCache('match_list_close_guest');
				$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
				$oCache -> deleteCache('calendar_' . $monat . '_match_member');
				$oCache -> deleteCache('calendar_' . $monat . '_guest');
				$oCache -> deleteCache('calendar_' . $monat . '_member');
				$oCache -> deleteCache('display_subnavi_matchs_guest');
				$oCache -> deleteCache('display_subnavi_matchs_member');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'acp_match_add');
	
				$message = $lang['create_match'] . '<br><br>' . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

			break;
			
			case 'match_update':
			
				$error = ''; 
				$error_msg = '';
			
				if ( intval($HTTP_POST_VARS['team_id']) == '0' )
				{
					$error = true;
					$error_msg = $lang['msg_select_team'];
				}
				
				if ( intval($HTTP_POST_VARS['match_type']) == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_type'];
				}
				
				if ( intval($HTTP_POST_VARS['match_categorie']) == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_cat'];
				}
				
				if ( intval($HTTP_POST_VARS['match_league']) == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_league'];
				}
				
				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_date'];
				}
				
				if ( $error )
				{
					message_die(GENERAL_ERROR, $error_msg, '');
				}
				
				if ( isset($HTTP_POST_VARS['listdel']) )
				{
					$sql = 'DELETE FROM ' . MATCH_USERS . ' WHERE match_id = ' . $match_id;
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				$match_date = mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				
				$sql = "UPDATE " . MATCH . " SET
							team_id				= '" . intval($HTTP_POST_VARS['team_id']) . "',
							match_type			= '" . intval($HTTP_POST_VARS['match_type']) . "',
							match_league		= '" . intval($HTTP_POST_VARS['match_league']) . "',
							match_league_url	= '" . str_replace("\'", "''", $HTTP_POST_VARS['match_league_url']) . "',
							match_league_match	= '" . str_replace("\'", "''", $HTTP_POST_VARS['match_league_match']) . "',
							match_date			= $match_date,
							match_categorie		= '" . intval($HTTP_POST_VARS['match_categorie']) . "',
							match_public		= '" . intval($HTTP_POST_VARS['match_public']) . "',
							match_comments		= '" . intval($HTTP_POST_VARS['match_comments']) . "',
							match_rival			= '" . str_replace("\'", "''", $HTTP_POST_VARS['match_rival']) . "',
							match_rival_tag		= '" . str_replace("\'", "''", $HTTP_POST_VARS['match_rival_tag']) . "',
							match_rival_url		= '" . str_replace("\'", "''", $HTTP_POST_VARS['match_rival_url']) . "',
							server				= '" . str_replace("\'", "''", $HTTP_POST_VARS['server']) . "',
							server_pw			= '" . str_replace("\'", "''", $HTTP_POST_VARS['server_pw']) . "',
							server_hltv			= '" . str_replace("\'", "''", $HTTP_POST_VARS['server_hltv']) . "',
							server_hltv_pw		= '" . str_replace("\'", "''", $HTTP_POST_VARS['server_hltv_pw']) . "',
							match_update		= '" . time() . "'
						WHERE match_id = $match_id";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update match information', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'acp_match_edit');
				
				$monat = $HTTP_POST_VARS['month'];
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('match_list_open_member');
				$oCache -> deleteCache('match_list_open_guest');
				$oCache -> deleteCache('match_list_close_member');
				$oCache -> deleteCache('match_list_close_guest');
				$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
				$oCache -> deleteCache('calendar_' . $monat . '_match_member');
				$oCache -> deleteCache('calendar_' . $monat . '_guest');
				$oCache -> deleteCache('calendar_' . $monat . '_member');
				$oCache -> deleteCache('display_subnavi_matchs_guest');
				$oCache -> deleteCache('display_subnavi_matchs_member');
				
				$message = $lang['match_update'] . '<br><br>' . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
			break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $match_id && $confirm )
				{
					$sql = 'SELECT * FROM ' . MATCH . " WHERE match_id = $match_id";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Error getting match information', '', __LINE__, __FILE__, $sql);
					}
			
					if (!($match_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['match_not_exist']);
					}
					
					$picture_file	= $match_info['match_picture'];
					$picture_type	= $match_info['match_picture_type'];
					$pictures_file	= $match_info['match_pictures'];
					$pictures_type	= $match_info['match_pictures_type'];
					
					$picture_file = basename($picture_file);
					$pictures_file = basename($pictures_file);
					
					if ( $picture_type == LOGO_UPLOAD && $picture_file != '' )
					{
						if ( @file_exists(@phpbb_realpath($root_path . $settings['path_match_picture'] . '/' . $picture_file)) )
						{
							@unlink($root_path . $settings['path_match_picture'] . '/' . $picture_file);
						}
					}
					
					if ( $pictures_type == LOGO_UPLOAD && $pictures_file != '' )
					{
						if ( @file_exists(@phpbb_realpath($root_path . $settings['match_pictures_path'] . '/' . $pictures_file)) )
						{
							@unlink($root_path . $settings['match_pictures_path'] . '/' . $pictures_file);
						}
					}
				
					$sql = 'DELETE FROM ' . MATCH . " WHERE match_id = $match_id";
					if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
					{
						message_die(GENERAL_ERROR, 'Could not delete match', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . MATCH_COMMENTS . " WHERE match_id = $match_id";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete match', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . MATCH_DETAILS . " WHERE match_id = $match_id";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete match', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . MATCH_LINEUP . " WHERE match_id = $match_id";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete match', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . MATCH_USERS . " WHERE match_id = $match_id";
					if (!($result = $db->sql_query($sql, END_TRANSACTION)))
					{
						message_die(GENERAL_ERROR, 'Could not delete match', '', __LINE__, __FILE__, $sql);
					}

					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, ACP_MATCH_DELETE, $match_info['match_name']);
					
					$monat = date("m", time());
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
					$oCache -> deleteCache('calendar_' . $monat . '_match_member');
					$oCache -> deleteCache('calendar_' . $monat . '_member');
					$oCache -> deleteCache('display_subnavi_matchs_guest');
					$oCache -> deleteCache('display_subnavi_matchs_member');
					
					$message = $lang['match_delete'] . '<br><br>' . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
		
				}
				else if ( $match_id && !$confirm )
				{
					$template->set_filenames(array('body' => './../admin/style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_MATCH_URL . '" value="' . $match_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_match'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid('admin_match.php'),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_match']);
				}
			
				$template->pparse('body');
				
			break;
				
			case 'details':
			
				$template->set_filenames(array('body' => './../admin/style/acp_match.tpl'));
				$template->assign_block_vars('match_details', array());
				
				//	alle Infos
//			$sql = 'SELECT	m.match_type, m.match_league, m.match_categorie, m.match_rival, m.match_rival_tag, m.match_rival_url, m.server, m.server_pw, m.server_hltv, m.server_hltv_pw,
//								md.*,
//								t.team_id, t.team_name,
//								g.game_image
//							FROM ' . MATCH . ' m, ' . MATCH_DETAILS . ' md, ' . TEAMS . ' t, ' . GAMES . " g
//						WHERE m.match_id = md.match_id AND m.team_id = t.team_id AND t.team_game = g.game_id AND m.match_id = $match_id";
						
				$sql = 'SELECT	m.*,
								md.*,
								t.team_id, t.team_name,
								g.game_image,
								tr.training_vs, tr.training_start
							FROM ' . MATCH . ' m
						LEFT JOIN ' . MATCH_DETAILS . ' md ON m.match_id = md.match_id
						LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING . ' tr ON tr.match_id = m.match_id
						WHERE ' . $match_id . ' = m.match_id';
				
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
				}

				if (!($row = $db->sql_fetchrow($result)))
				{
					message_die(GENERAL_MESSAGE, '', '', __LINE__, __FILE__);
				}
				
				$picture_path = $root_path . $settings['path_match_picture'];
				
				$map_pic_a	= $row['details_map_pic_a'];
				$map_pic_b	= $row['details_map_pic_b'];
				$map_pic_c	= $row['details_map_pic_c'];
				$map_pic_d	= $row['details_map_pic_d'];
				$map_pic_e	= $row['details_map_pic_e'];
				$map_pic_f	= $row['details_map_pic_f'];
				$map_pic_g	= $row['details_map_pic_g'];
				$map_pic_h	= $row['details_map_pic_h'];
				
				$pic_a	= $row['pic_a_preview'];
				$pic_b	= $row['pic_b_preview'];
				$pic_c	= $row['pic_c_preview'];
				$pic_d	= $row['pic_d_preview'];
				$pic_e	= $row['pic_e_preview'];
				$pic_f	= $row['pic_f_preview'];
				$pic_g	= $row['pic_g_preview'];
				$pic_h	= $row['pic_h_preview'];
				
				$pic_a	= ( $map_pic_a ) ? '<a href="' . $picture_path . '/' . $map_pic_a . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_a . '" alt="" border="" /></a>' : '';
				$pic_b	= ( $map_pic_b ) ? '<a href="' . $picture_path . '/' . $map_pic_b . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_b . '" alt="" border="" /></a>' : '';
				$pic_c	= ( $map_pic_c ) ? '<a href="' . $picture_path . '/' . $map_pic_c . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_c . '" alt="" border="" /></a>' : '';
				$pic_d	= ( $map_pic_d ) ? '<a href="' . $picture_path . '/' . $map_pic_d . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_d . '" alt="" border="" /></a>' : '';
				$pic_e	= ( $map_pic_e ) ? '<a href="' . $picture_path . '/' . $map_pic_e . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_e . '" alt="" border="" /></a>' : '';
				$pic_f	= ( $map_pic_f ) ? '<a href="' . $picture_path . '/' . $map_pic_f . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_f . '" alt="" border="" /></a>' : '';
				$pic_g	= ( $map_pic_g ) ? '<a href="' . $picture_path . '/' . $map_pic_g . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_g . '" alt="" border="" /></a>' : '';
				$pic_h	= ( $map_pic_h ) ? '<a href="' . $picture_path . '/' . $map_pic_h . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_h . '" alt="" border="" /></a>' : '';
				
				//	Lineup + Ersatz
				$sql = 'SELECT u.user_id, u.username, ml.status
						FROM ' . MATCH_LINEUP . ' ml, ' . USERS . ' u
						WHERE ml.match_id = ' . $match_id . ' AND ml.user_id = u.user_id
						ORDER BY ml.status';
				if (!($result_users = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
				}
				
				$color = '';
				while ( $users = $db->sql_fetchrow($result_users) )
				{
					$class = ($color % 2) ? 'row_class1' : 'row_class2';
					$color++;

					$template->assign_block_vars('match_details.members_row', array(
						'CLASS' 		=> $class,
						'USER_ID'		=> $users['user_id'],
						'USERNAME'		=> $users['username'],
						'STATUS'		=> (!$users['status']) ? $lang['match_player'] : $lang['match_replace']
					));
				}
				
				if (!$db->sql_numrows($result_users))
				{
					$template->assign_block_vars('match_details.no_members_row', array());
					$template->assign_vars(array('NO_MEMBER' => $lang['team_no_member']));
				}
				//	Lineup

				
				//	Add Member aus Liste
				$sql = 'SELECT u.user_id, u.username
							FROM ' . USERS . ' u, ' . TEAMS_USERS . ' tu
							WHERE tu.team_id = ' . $row['team_id'] . ' AND tu.user_id = u.user_id
							ORDER BY u.username';
				if (!($result_addusers = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sqla);
				}

				$s_addusers_select = '<select class="select" name="members[]" rows="5" multiple>';
				while ($addusers = $db->sql_fetchrow($result_addusers))
				{
					$s_addusers_select .= '<option value="' . $addusers['user_id'] . '">' . $addusers['username'] . '&nbsp;</option>';
				}
				$s_addusers_select .= '</select>';
				
				//	Lineup
				
				//	Dropdown
				$s_action_options = '';
				$s_action_options .= '<select class="postselect" name="mode">';
				$s_action_options .= '<option value="option">&raquo; ' . $lang['option_select'] . '</option>';
				$s_action_options .= '<option value="player">&raquo; ' . sprintf($lang['status_set'], $lang['match_player']) . '&nbsp;</option>';
				$s_action_options .= '<option value="replace">&raquo; ' . sprintf($lang['status_set'], $lang['match_replace']) . '&nbsp;</option>';
				$s_action_options .= '<option value="deluser">&raquo; ' . $lang['delete'] . '</option>';
				$s_action_options .= '</select>';
				
				foreach ( $lang['select_categorie_box'] as $key_s => $value_s )
				{
					if ( $key_s == $row['match_categorie'] )
					{
						$match_categorie = $value_s;
					}
				}
				
				foreach ( $lang['select_type_box'] as $key_s => $value_s )
				{
					if ( $key_s == $row['match_type'] )
					{
						$match_type = $value_s;
					}
				}
				
				foreach ( $lang['select_league_box'] as $key_s => $value_s )
				{
					if ( $key_s == $row['match_league'] )
					{
						$match_league = '<a href="' . $value_s['league_link'] . '">' . $value_s['league_name'] . '</a>';
					}
				}
				
				if ( $map_pic_a ) { $template->assign_block_vars('match_details.pictureadel', array()); }
				if ( $map_pic_b ) { $template->assign_block_vars('match_details.picturebdel', array()); }
				if ( $map_pic_c ) { $template->assign_block_vars('match_details.picturecdel', array()); }
				if ( $map_pic_d ) { $template->assign_block_vars('match_details.pictureddel', array()); }
				if ( $map_pic_e ) { $template->assign_block_vars('match_details.pictureedel', array()); }
				if ( $map_pic_f ) { $template->assign_block_vars('match_details.picturefdel', array()); }
				if ( $map_pic_g ) { $template->assign_block_vars('match_details.picturegdel', array()); }
				if ( $map_pic_h ) { $template->assign_block_vars('match_details.picturehdel', array()); }
				
				if ( $row['server_hltv'] ) { $template->assign_block_vars('hltv', array()); }
					
				$s_hidden_fields = '<input type="hidden" name="' . POST_MATCH_URL . '" value="' . $match_id . '" />';
				
				$s_hidden_fielda = '<input type="hidden" name="mode" value="lineup" />';
				$s_hidden_fieldb = '<input type="hidden" name="mode" value="update" />';
				$s_hidden_fieldc = '<input type="hidden" name="mode" value="adduser" />';
				
				$template->assign_vars(array(
					'L_MATCH_TITLE'			=> $lang['match_head'],
					'L_MATCH_DETAILS'		=> $lang['match_details'],
					'L_MATCH_NEW_EDIT'		=> $lang['match_edit'],
					'L_MATCH_EXPLAIN'		=> $lang['match_details_explain'],
					'L_MATCH_INFO'			=> $lang['match_details_info'],
					
					'L_DETAILS_MAPS'		=> $lang['match_details_maps'],
					'L_RIVAL'				=> $lang['match_rival'],
					'L_RIVAL_TAG'			=> $lang['match_rival_tag'],
					'L_SERVER'				=> $lang['match_server'],
					'L_HLTV'				=> $lang['match_hltv'],
					
					'L_MATCH_LINEUP'		=> $lang['match_lineup'],
					'L_MATCH_LINUP_ADD'		=> $lang['match_lineup_add'],
					'L_MATCH_LINUP_ADD_EX'	=> $lang['match_lineup_explain'],
					'L_MATCH_LINEUP_STATUS'	=> $lang['match_lineup_status'],
							
					'L_LINEUP_PLAYER'		=> $lang['match_lineup_player'],
					'L_USERNAME'			=> $lang['username'],
					'L_MARK_ALL'			=> $lang['mark_all'],
					'L_MARK_DEALL'			=> $lang['mark_deall'],
					'L_SUBMIT'				=> $lang['Submit'],
					
					'L_MAP'					=> $lang['match_details_map'],
					'L_UPLOAD_MAP'			=> $lang['match_details_mappic'],
					'L_POINTS'				=> $lang['match_details_points'],
					'L_RIVAL_LINEUP'		=> $lang['match_rival_lineup'],
					'L_MATCH_COMMENT'		=> $lang['match_details_comment'],
					
					'L_MAP_MORE'			=> $lang['match_map_more'],
					'L_MAP_CLOSE'			=> $lang['match_map_close'],
					
					'MATCH_RIVAL'			=> $row['match_rival'],
					'MATCH_RIVAL_URL'		=> $row['match_rival_url'],
					'MATCH_RIVAL_TAG'		=> $row['match_rival_tag'],
					'U_MATCH_RIVAL_URL'		=> $row['match_rival_url'],
					
					'MATCH_CATEGORIE'		=> $match_categorie,
					'MATCH_TYPE'			=> $match_type,
					'MATCH_LEAGUE_INFO'		=> $match_league,
					'SERVER'				=> $row['server'],
					'SERVER_PW'				=> $row['server_pw'],
					'HLTV'					=> $row['server_hltv'],
					'HLTV_PW'				=> $row['server_hltv_pw'],
					
					'MAPC'					=> ($row['details_mapc']) ? '' : 'none',
					'MAPD'					=> ($row['details_mapd']) ? '' : 'none',
					
					'DETAILS_LINEUP_RIVAL'	=> $row['details_lineup_rival'],
					
					'DETAILS_MAPA'			=> $row['details_mapa'],
					'DETAILS_MAPB'			=> $row['details_mapb'],
					'DETAILS_MAPC'			=> $row['details_mapc'],
					'DETAILS_MAPD'			=> $row['details_mapd'],
					'DETAILS_MAPA_CLAN'		=> $row['details_mapa_clan'],
					'DETAILS_MAPB_CLAN'		=> $row['details_mapb_clan'],
					'DETAILS_MAPC_CLAN'		=> $row['details_mapc_clan'],
					'DETAILS_MAPD_CLAN'		=> $row['details_mapd_clan'],
					'DETAILS_MAPA_RIVAL'	=> $row['details_mapa_rival'],
					'DETAILS_MAPB_RIVAL'	=> $row['details_mapb_rival'],
					'DETAILS_MAPC_RIVAL'	=> $row['details_mapc_rival'],
					'DETAILS_MAPD_RIVAL'	=> $row['details_mapd_rival'],
					
					'DETAILS_PIC_A'			=> $pic_a,
					'DETAILS_PIC_B'			=> $pic_b,
					'DETAILS_PIC_C'			=> $pic_c,
					'DETAILS_PIC_D'			=> $pic_d,
					'DETAILS_PIC_E'			=> $pic_e,
					'DETAILS_PIC_F'			=> $pic_f,
					'DETAILS_PIC_G'			=> $pic_g,
					'DETAILS_PIC_H'			=> $pic_h,
					
					'DETAILS_COMMENT'		=> $row['details_comment'],

					'S_ACTION_OPTIONS'		=> $s_action_options,
					
					'S_ADDUSERS'			=> $s_addusers_select,
					
					'L_PLAYER'				=> $lang['match_player'],
					'L_REPLACE'				=> $lang['match_replace'],

					'S_HIDDEN_FIELDA'		=> $s_hidden_fielda,
					'S_HIDDEN_FIELDB'		=> $s_hidden_fieldb,
					'S_HIDDEN_FIELDC'		=> $s_hidden_fieldc,
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_MATCH_EDIT'			=> append_sid('admin_match.php?mode=match_edit&amp;' . POST_MATCH_URL . '=' . $match_id),
					'S_MATCH_ACTION'		=> append_sid('admin_match.php'),
				));
			
				$template->pparse('body');
			
			break;
			
			case 'update':
			
				$sql = 'SELECT * FROM ' . MATCH_DETAILS . " WHERE match_id = $match_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Error getting team information', '', __LINE__, __FILE__, $sql);
				}
		
				if (!($match_info = $db->sql_fetchrow($result)))
				{
					message_die(GENERAL_MESSAGE, 'match_not_exist');
				}
			
				$picturea_upload		= ( $HTTP_POST_FILES['details_map_pic_a']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_a']['tmp_name'] : '';
				$picturea_name			= ( !empty($HTTP_POST_FILES['details_map_pic_a']['name']) ) ? $HTTP_POST_FILES['details_map_pic_a']['name'] : '';
				$picturea_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_a']['type']) ) ? $HTTP_POST_FILES['details_map_pic_a']['type'] : '';
				
				$pictureb_upload		= ( $HTTP_POST_FILES['details_map_pic_b']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_b']['tmp_name'] : '';
				$pictureb_name			= ( !empty($HTTP_POST_FILES['details_map_pic_b']['name']) ) ? $HTTP_POST_FILES['details_map_pic_b']['name'] : '';
				$pictureb_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_b']['type']) ) ? $HTTP_POST_FILES['details_map_pic_b']['type'] : '';
				
				$picturec_upload		= ( $HTTP_POST_FILES['details_map_pic_c']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_c']['tmp_name'] : '';
				$picturec_name			= ( !empty($HTTP_POST_FILES['details_map_pic_c']['name']) ) ? $HTTP_POST_FILES['details_map_pic_c']['name'] : '';
				$picturec_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_c']['type']) ) ? $HTTP_POST_FILES['details_map_pic_c']['type'] : '';
				
				$pictured_upload		= ( $HTTP_POST_FILES['details_map_pic_d']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_d']['tmp_name'] : '';
				$pictured_name			= ( !empty($HTTP_POST_FILES['details_map_pic_d']['name']) ) ? $HTTP_POST_FILES['details_map_pic_d']['name'] : '';
				$pictured_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_d']['type']) ) ? $HTTP_POST_FILES['details_map_pic_d']['type'] : '';
				
				$picturee_upload		= ( $HTTP_POST_FILES['details_map_pic_e']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_e']['tmp_name'] : '';
				$picturee_name			= ( !empty($HTTP_POST_FILES['details_map_pic_e']['name']) ) ? $HTTP_POST_FILES['details_map_pic_e']['name'] : '';
				$picturee_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_e']['type']) ) ? $HTTP_POST_FILES['details_map_pic_e']['type'] : '';
				
				$picturef_upload		= ( $HTTP_POST_FILES['details_map_pic_f']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_f']['tmp_name'] : '';
				$picturef_name			= ( !empty($HTTP_POST_FILES['details_map_pic_f']['name']) ) ? $HTTP_POST_FILES['details_map_pic_f']['name'] : '';
				$picturef_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_f']['type']) ) ? $HTTP_POST_FILES['details_map_pic_f']['type'] : '';
				
				$pictureg_upload		= ( $HTTP_POST_FILES['details_map_pic_g']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_g']['tmp_name'] : '';
				$pictureg_name			= ( !empty($HTTP_POST_FILES['details_map_pic_g']['name']) ) ? $HTTP_POST_FILES['details_map_pic_g']['name'] : '';
				$pictureg_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_g']['type']) ) ? $HTTP_POST_FILES['details_map_pic_g']['type'] : '';
				
				$pictureh_upload		= ( $HTTP_POST_FILES['details_map_pic_h']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_h']['tmp_name'] : '';
				$pictureh_name			= ( !empty($HTTP_POST_FILES['details_map_pic_d']['name']) ) ? $HTTP_POST_FILES['details_map_pic_h']['name'] : '';
				$pictureh_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_h']['type']) ) ? $HTTP_POST_FILES['details_map_pic_h']['type'] : '';
				
				$picturea_sql = '';
				$pictureb_sql = '';
				$picturec_sql = '';
				$pictured_sql = '';
				$picturee_sql = '';
				$picturef_sql = '';
				$pictureg_sql = '';
				$pictureh_sql = '';
				
				if ( isset($HTTP_POST_VARS['pictureadel']) )
				{
					$picturea_sql = picture_delete('a', $match_info['details_map_pic_a'], $match_info['pic_a_preview']);
				}
				else if (!empty($picturea_upload))
				{
					$picturea_sql = picture_upload('a', $match_info['details_map_pic_a'], $match_info['pic_a_preview'], $picturea_upload, $picturea_name, $picturea_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturebdel']) )
				{
					$pictureb_sql = picture_delete('b', $match_info['details_map_pic_b'], $match_info['pic_b_preview']);
				}
				else if (!empty($pictureb_upload))
				{
					$pictureb_sql = picture_upload('b', $match_info['details_map_pic_b'], $match_info['pic_b_preview'], $pictureb_upload, $pictureb_name, $pictureb_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturecdel']) )
				{
					$picturec_sql = picture_delete('c', $match_info['details_map_pic_c'], $match_info['pic_c_preview']);
				}
				else if (!empty($picturec_upload))
				{
					$picturec_sql = picture_upload('c', $match_info['details_map_pic_c'], $match_info['pic_c_preview'], $picturec_upload, $picturec_name, $picturec_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['pictureddel']) )
				{
					$pictured_sql = picture_delete('d', $match_info['details_map_pic_d'], $match_info['pic_d_preview']);
				}
				else if (!empty($pictured_upload))
				{
					$pictured_sql = picture_upload('d', $match_info['details_map_pic_d'], $match_info['pic_d_preview'], $pictured_upload, $pictured_name, $pictured_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['pictureedel']) )
				{
					$picturee_sql = picture_delete('e', $match_info['details_map_pic_e'], $match_info['pic_e_preview']);
				}
				else if (!empty($picturee_upload))
				{
					$picturee_sql = picture_upload('e', $match_info['details_map_pic_e'], $match_info['pic_e_preview'], $picturee_upload, $picturee_name, $picturee_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturefdel']) )
				{
					$picturef_sql = picture_delete('f', $match_info['details_map_pic_f'], $match_info['pic_f_preview']);
				}
				else if (!empty($picturef_upload))
				{
					$picturef_sql = picture_upload('f', $match_info['details_map_pic_f'], $match_info['pic_f_preview'], $picturef_upload, $picturef_name, $picturef_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturegdel']) )
				{
					$pictureg_sql = picture_delete('g', $match_info['details_map_pic_g'], $match_info['pic_g_preview']);
				}
				else if (!empty($pictureg_upload))
				{
					$pictureg_sql = picture_upload('g', $match_info['details_map_pic_g'], $match_info['pic_g_preview'], $pictureg_upload, $pictureg_name, $pictureg_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturehdel']) )
				{
					$pictureh_sql = picture_delete('h', $match_info['details_map_pic_h'], $match_info['pic_h_preview']);
				}
				else if (!empty($pictureh_upload))
				{
					$pictureh_sql = picture_upload('h', $match_info['details_map_pic_h'], $match_info['pic_h_preview'], $pictureh_upload, $pictureh_name, $pictureh_filetype);
				}
				
				$sql = "UPDATE " . MATCH_DETAILS . " SET
							details_lineup_rival	= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_lineup_rival']) . "',
							details_mapa_clan		= '" . intval($HTTP_POST_VARS['details_mapa_clan']) . "',
							details_mapa_rival		= '" . intval($HTTP_POST_VARS['details_mapa_rival']) . "',
							details_mapb_clan		= '" . intval($HTTP_POST_VARS['details_mapb_clan']) . "',
							details_mapb_rival		= '" . intval($HTTP_POST_VARS['details_mapb_rival']) . "',
							details_mapc_clan		= '" . intval($HTTP_POST_VARS['details_mapc_clan']) . "',
							details_mapc_rival		= '" . intval($HTTP_POST_VARS['details_mapc_rival']) . "',
							details_mapd_clan		= '" . intval($HTTP_POST_VARS['details_mapd_clan']) . "',
							details_mapd_rival		= '" . intval($HTTP_POST_VARS['details_mapd_rival']) . "',
							details_mapa			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_mapa']) . "',
							details_mapb			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_mapb']) . "',
							details_mapc			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_mapc']) . "',
							details_mapd			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_mapd']) . "',
							$picturea_sql
							$pictureb_sql
							$picturec_sql
							$pictured_sql
							$picturee_sql
							$picturef_sql
							$pictureg_sql
							$pictureh_sql
							details_comment			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_comment']) . "',
							details_update			= " . time() . "
						WHERE match_id = $match_id";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update team information', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'acp_match_edit');
				
				$message = $lang['team_update']
					. '<br><br>' . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
					. '<br><br>' . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_id) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
				
			break;
			
			case 'player':
			case 'replace':
			
				$member = $_POST['members'];
				$status = ($mode == 'player') ? '0' : '1';
			
				// If we have no users
				if (!sizeof($member))
				{
					message_die(GENERAL_ERROR, $lang['match_no_select']);
				}
				
				$members = $member;
				
				$i	= 0;
				$g	= 0;
				$e	= 1;
				
				while ($i < count($members))
				{
					$member = array_slice($members, $g, $e);
					$member = current($member);
					
					$sql = "UPDATE " . MATCH_LINEUP . " SET status = $status WHERE match_id = $match_id AND user_id = $member";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not add match member', '', __LINE__, __FILE__, $sql);
					}
					
					$i++; $g--;
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'acp_match_lineup_change');
				
				$message = $lang['match_lineup_change']
					. '<br><br>' . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
					. '<br><br>' . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_id) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
				
				break;

			case 'adduser':
			
				$member = $_POST['members'];
				$status = $_POST['status'];
				
				if (!$_POST['members'])
				{
					message_die(GENERAL_ERROR, $lang['match_no_select']);
				}
				
				$user_id_ary = $member;
				$user_id_ary_im = implode(', ', $member);
				
				// Remove users who are already members of this group
				$sql = 'SELECT user_id FROM ' . MATCH_LINEUP . ' WHERE user_id IN ('.$user_id_ary_im.') AND match_id = ' . $match_id;
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
				}
				
				$add_id_ary = $user_id_ary_im = array();
				
				while ($row = $db->sql_fetchrow($result))
				{
					$add_id_ary[] = (int) $row['user_id'];
				}
				$db->sql_freeresult($result);
				
				// Do all the users exist in this group?
				$add_id_ary = array_diff($user_id_ary, $add_id_ary);
				
				// If we have no users
				if (!sizeof($add_id_ary) && !sizeof($user_id_arya))
				{
					message_die(GENERAL_ERROR, $lang['match_lineup_no_users'] . $lang['back']);
				}
				
				if (sizeof($add_id_ary))
				{
					$sql_ary = array();
			
					foreach ($add_id_ary as $user_id)
					{
						$sql_ary[] = array(
							'match_id'		=> (int) $match_id,
							'user_id'		=> (int) $user_id,							
							'status'		=> (int) $status,
						);
					}
			
					if (!sizeof($sql_ary))
					{
						message_die(GENERAL_ERROR, 'Fehler', '', __LINE__, __FILE__);
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
		
					
					$sql = 'INSERT INTO ' . MATCH_LINEUP . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary);
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
					}
				}

				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'acp_match_add_lineup');
			
				$message = $lang['match_lineup_add_yes']
					. '<br><br>' . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
					. '<br><br>' . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_id) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
				
			break;
			
			case 'deluser':
			
				$members = $_POST['members'];
				
				if (!$_POST['members'])
				{
					message_die(GENERAL_ERROR, $lang['match_no_select']);
				}
				
				$sql_in = implode(", ", $members);
				
				$sql = "DELETE FROM " . MATCH_LINEUP . " WHERE user_id IN ($sql_in) AND match_id = $match_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not delete memer', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'acp_match_del_lineup');
			
				$message = $lang['match_lineup_del_yes']
					. '<br><br>' . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
					. '<br><br>' . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_id) . '">', '</a>');
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
	
	$template->set_filenames(array('body' => './../admin/style/acp_match.tpl'));
	$template->assign_block_vars('display', array());
	
	$template->assign_vars(array(
		'L_MATCH_HEAD'		=> $lang['match_head'],
		'L_MATCH_EXPLAIN'	=> $lang['match_explain'],
		'L_MATCH_DETAILS'	=> $lang['match_infos'],
		'L_TRAINING'		=> $lang['training'],
		'L_MATCH_DETAILS'	=> $lang['match_details'],
		'L_MATCH_CREATE'	=> $lang['match_add'],
		'L_UPCOMING'		=> $lang['match_upcoming'],
		'L_EXPIRED'			=> $lang['match_expired'],
		
		'L_SETTINGS'		=> $lang['settings'],
		'L_SETTING'			=> $lang['setting'],
		'L_DELETE'			=> $lang['delete'],
		
		'S_TEAMS'			=> select_box('team', 'selectsmall', 'team_id', 'team_name', 0, 0),
		'S_MATCH_ADD'		=> append_sid('admin_match.php?mode=match_add'),
		'S_MATCH_ACTION'	=> append_sid('admin_match.php'),
	));
	
	$sql = 'SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
				FROM ' . MATCH . ' m
					LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
					LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . TRAINING . ' tr ON m.match_id = tr.match_id
				WHERE match_date > ' . time() . '
			ORDER BY m.match_date DESC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$match_new = $db->sql_fetchrowset($result); 
	$db->sql_freeresult($result);
	
	$sql = 'SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
			FROM ' . MATCH . ' m
				LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
				LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
				LEFT JOIN ' . TRAINING . ' tr ON m.match_id = tr.match_id
			WHERE match_date < ' . time() . '
			ORDER BY m.match_date DESC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$match_old = $db->sql_fetchrowset($result); 
	$db->sql_freeresult($result);
	
	if ( !$match_new )
	{
		$template->assign_block_vars('display.no_entry_new', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($match_new)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
				
			$game_size	= $match_new[$i]['game_size'];
			$game_image	= '<img src="' . $root_path . $settings['path_game'] . '/' . $match_new[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
			
			$match_name	= ($match_new[$i]['match_public']) ? 'vs. ' . $match_new[$i]['match_rival'] : 'vs. <span style="font-style:italic;">' . $match_new[$i]['match_rival'] . '</span>';
			
			$template->assign_block_vars('display.match_row_new', array(
				'CLASS' 		=> $class,
				'MATCH_GAME'	=> $game_image,
				'MATCH_NAME'	=> $match_name,
				'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $match_new[$i]['match_date'], $userdata['user_timezone']),
				'TRAINING'		=> (!$match_new[$i]['training_id']) ? $lang['add_train'] : $lang['edit_train'],
				'U_DETAILS'		=> append_sid('admin_match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_new[$i]['match_id']),
				'U_TRAINING'	=> (!$match_new[$i]['training_id']) ? append_sid('admin_training.php?mode=add&amp;' . POST_TEAM_URL . '=' . $match_new[$i]['team_id'].'&amp;' . POST_MATCH_URL . '=' . $match_new[$i]['match_id'].'&amp;vs=' . $match_new[$i]['match_rival']) : append_sid('admin_training.php?mode=edit&amp;' . POST_TRAINING_URL . '=' . $match_new[$i]['training_id']),
				'U_EDIT'		=> append_sid('admin_match.php?mode=match_edit&amp;' . POST_MATCH_URL . '=' . $match_new[$i]['match_id']),
				'U_DELETE'		=> append_sid('admin_match.php?mode=delete&amp;' . POST_MATCH_URL . '=' . $match_new[$i]['match_id'])
			));
		}
	}
	
	if ( !$match_old )
	{
		$template->assign_block_vars('display.no_entry_old', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($match_old)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
				
			$game_size	= $match_old[$i]['game_size'];
			$game_image	= '<img src="' . $root_path . $settings['path_game'] . '/' . $match_old[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
			
			$match_name	= ($match_old[$i]['match_public']) ? 'vs. ' . $match_old[$i]['match_rival'] : 'vs. <span style="font-style:italic;">' . $match_old[$i]['match_rival'] . '</span>';
			
			$template->assign_block_vars('display.match_row_old', array(
				'CLASS' 		=> $class,
				'MATCH_GAME'	=> $game_image,
				'MATCH_NAME'	=> $match_name,
				'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $match_old[$i]['match_date'], $userdata['user_timezone']),
				'TRAINING'		=> (!$match_old[$i]['training_id']) ? $lang['add_train'] : $lang['edit_train'],
				'U_DETAILS'		=> append_sid('admin_match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_old[$i]['match_id']),
				'U_TRAINING'	=> (!$match_old[$i]['training_id']) ? append_sid('admin_training.php?mode=add&amp;' . POST_TEAM_URL . '=' . $match_old[$i]['team_id'].'&amp;' . POST_MATCH_URL . '=' . $match_old[$i]['match_id']."&amp;vs=" . $match_old[$i]['match_rival']) : append_sid('admin_training.php?mode=edit&amp;' . POST_TRAINING_URL . '=' . $match_old[$i]['training_id']),
				'U_EDIT'		=> append_sid('admin_match.php?mode=match_edit&amp;' . POST_MATCH_URL . '=' . $match_old[$i]['match_id']),
				'U_DELETE'		=> append_sid('admin_match.php?mode=delete&amp;' . POST_MATCH_URL . '=' . $match_old[$i]['match_id'])
			));
		}
	}
	
	$current_page = ( !count($match_old) ) ? 1 : ceil( count($match_old) / $settings['site_entry_per_page'] );

	$template->assign_vars(array(
		'PAGINATION' => ( count($match_old) ) ? generate_pagination('admin_match.php?', count($match_old), $settings['site_entry_per_page'], $start) : '',
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>