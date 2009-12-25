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
	
	if ( $userauth['auth_match'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_teams']['_submenu_match'] = $filename;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	$start		= ( request('start') ) ? request('start', 'num') : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$team_id	= request(POST_TEAMS_URL);
	$match_id	= request(POST_MATCH_URL);
	$confirm	= request('confirm');
	$mode		= request('mode');
	$show_index = '';
	
	if ( !$userauth['auth_match'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_match.php', true));
	}
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_match.tpl'));
			$template->assign_block_vars('match_edit', array());
			
			if ( $mode == '_update' )
			{
				$match		= get_data('match', $match_id, 0);
				$new_mode	= '_update_save';
				
				$template->assign_block_vars('match_edit.edit_match', array());
				
				if ( $match['match_date'] > time() )
				{
					$template->assign_block_vars('match_edit.reset_match', array());
				}
			}
			else
			{
				$match = array (
					'team_id'				=> request('team_id', 'num'),
					'match_type'			=> '',
					'match_league'			=> '',
					'match_league_url'		=> '',
					'match_league_match'	=> '',
					'match_date'			=> time(),
					'match_categorie'		=> '0',
					'match_public'			=> '1',
					'match_comments'		=> $settings['comments_matches'],
					'match_rival'			=> '',
					'match_rival_tag'		=> '',
					'match_rival_url'		=> '',
					'match_rival_lineup'	=> '',
					'server_ip'				=> '',
					'server_pw'				=> '',
					'server_hltv'			=> '',
					'server_hltv_pw'		=> '',
					'match_create'			=> '',
					'match_update'			=> '',
				);
				$new_mode = '_create_save';
				
				$template->assign_block_vars('match_edit.new_match', array());
			}
			
			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_MATCH_URL . '" value="' . $match_id . '" />';
			
			$template->assign_vars(array(
				'L_MATCH_TITLE'			=> sprintf($lang['sprintf_head'], $lang['match']),
				'L_MATCH_NEW_EDIT'		=> ( $mode == '_create' ) ? sprintf($lang['sprintf_add'], $lang['match']) : sprintf($lang['sprintf_edit'], $lang['match']),
				'L_MATCH_DETAILS'		=> $lang['match_details'],
				'L_REQUIRED'			=> $lang['required'],
				
				'L_MATCH_INFO_A'		=> $lang['match_info_a'],
				'L_MATCH_INFO_B'		=> $lang['match_info_b'],
				'L_MATCH_INFO_C'		=> $lang['match_info_c'],
				'L_MATCH_INFO_D'		=> $lang['match_info_d'],
				'L_MATCH_INFO_E'		=> $lang['match_info_e'],
				
				'L_MATCH_TEAM'			=> $lang['match_team'],
				'L_MATCH_TYPE'			=> $lang['match_type'],
				'L_MATCH_CATEGORIE'		=> $lang['match_categorie'],
				'L_MATCH_LEAGUE'		=> $lang['match_league'],
				'L_MATCH_LEAGUE_URL'	=> $lang['match_league_url'],
				'L_LEAGUE_MATCH'		=> $lang['match_league_match'],
				'L_MATCH_DATE'			=> $lang['match_date'],
				'L_MATCH_PUBLIC'		=> $lang['match_public'],
				'L_MATCH_COMMENTS'		=> $lang['match_comments'],
				
				'L_MATCH_RIVAL'			=> $lang['match_rival'],
				'L_MATCH_RIVAL_TAG'		=> $lang['match_rival_tag'],
				'L_MATCH_RIVAL_URL'		=> $lang['match_rival_url'],
				'L_MATCH_RIVAL_LINEUP'	=> $lang['match_rival_lineup'],
				
				'L_MATCH_SERVER_IP'		=> $lang['match_server'],
				'L_MATCH_SERVER_PW'		=> $lang['match_serverpw'],
				'L_MATCH_HLTV'			=> $lang['match_hltv'],
				'L_MATCH_HLTV_PW'		=> $lang['match_hltvpw'],
				
				'L_MATCH_COMMENT'		=> $lang['match_details_comment'],
				'L_MATCH_REPORT'		=> $lang['match_text'],
				
				'L_TRAINING'			=> $lang['training'],
				'L_TRAINING_DATE'		=> $lang['training_date'],
				'L_TRAINING_MAPS'		=> $lang['training_maps'],
				'L_TRAINING_TEXT'		=> $lang['training_text'],
				
				'L_RESET_LIST'			=> $lang['match_interest_reset'],
				
				'L_NO'					=> $lang['common_no'],
				'L_YES'					=> $lang['common_yes'],
				'L_RESET'				=> $lang['common_reset'],
				'L_SUBMIT'				=> $lang['common_submit'],
				
				'MATCH_LEAGUE_URL'		=> $match['match_league_url'],
				'MATCH_LEAGUE_MATCH'	=> $match['match_league_match'],
				
				'MATCH_RIVAL'			=> $match['match_rival'],
				'MATCH_RIVAL_TAG'		=> $match['match_rival_tag'],
				'MATCH_RIVAL_URL'		=> $match['match_rival_url'],
				'MATCH_RIVAL_LINEUP'	=> $match['match_rival_lineup'],
				
				
				'SERVER_IP'				=> $match['server_ip'],
				'SERVER_PW'				=> $match['server_pw'],
				'SERVER_HLTV'			=> $match['server_hltv'],
				'SERVER_HLTV_PW'		=> $match['server_hltv_pw'],
				
				'S_TEAMS'				=> select_box('team', 'select', $match['team_id'], 0),
				'S_TYPE'				=> select_lang_box('select_type_box', 'match_type', $match['match_type'], 'select'),
				'S_LEAGUE'				=> select_lang_box('select_league_box', 'match_league', $match['match_league'], 'select'),
				'S_CATEGORIE'			=> select_lang_box('select_categorie_box', 'match_categorie', $match['match_categorie'], 'select'),
				
				'S_DAY'					=> select_date('day', 'day',		date('d', $match['match_date'])),
				'S_MONTH'				=> select_date('month', 'month',	date('m', $match['match_date'])),
				'S_YEAR'				=> select_date('year', 'year',		date('Y', $match['match_date'])),
				'S_HOUR'				=> select_date('hour', 'hour',		date('H', $match['match_date'])),
				'S_MIN'					=> select_date('min', 'min',		date('i', $match['match_date'])),
				
				'S_PUBLIC_NO'	=> ( !$match['match_public'] )		? ' checked="checked"' : '',
				'S_PUBLIC_YES'	=> ( $match['match_public'] )		? ' checked="checked"' : '',
				'S_COMMENT_NO'	=> ( !$match['match_comments'] )	? ' checked="checked"' : '',
				'S_COMMENT_YES'	=> ( $match['match_comments'] )		? ' checked="checked"' : '',
				
				'S_TDAY'				=> select_date('day', 'tday',		date('d', time())),
				'S_TMONTH'				=> select_date('month', 'tmonth',	date('m', time())),
				'S_TYEAR'				=> select_date('year', 'tyear',		date('Y', time())),
				'S_THOUR'				=> select_date('hour', 'thour',		date('H', time())),
				'S_TMIN'				=> select_date('min', 'tmin',		date('i', time())),
				'S_TDURATION'			=> select_date('duration', 'dmin',	date('i', time())),
				
				'S_FIELDS'		=> $s_hidden_fields,
				'S_MATCH_DETAILS'		=> append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_id),
				'S_MATCH_ACTION'		=> append_sid('admin_match.php'),
			));
			
			break;
		
		case '_create_save':
		
			$team_id			= request('team_id', 'num');
			$match_type			= request('match_type', 'num');
			$match_categorie	= request('match_categorie', 'num');
			$match_league		= request('match_league', 'num');
			$match_league_url	= request('match_league_url', 'text');
			$match_league_match	= request('match_league_match', 'text');
			$match_public		= request('match_public', 'num');
			$match_comments		= request('match_comments', 'num');
			$match_rival		= request('match_rival', 'text');
			$match_rival_tag	= request('match_rival_tag', 'text');
			$match_rival_url	= request('match_rival_url', 'text');
			$match_rival_lineup	= request('match_rival_lineup', 'text');
			$server_ip			= request('server_ip', 'text');
			$server_pw			= request('server_pw', 'text');
			$server_hltv		= request('server_hltv', 'text');
			$server_hltv_pw		= request('server_hltv_pw', 'text');
			$training_maps		= request('training_maps', 'text');
			$training_comment	= request('training_comment', 'text');
			
			$error_msg = '';
			$error_msg .= ( !$team_id ) ? $lang['msg_select_team'] : '';
			$error_msg .= ( !$match_type ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_type'] : '';
			$error_msg .= ( !$match_categorie ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_cat'] : '';
			$error_msg .= ( !$match_league ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_league'] : '';
			$error_msg .= ( !checkdate(request('month', 'num'), request('day', 'num'), request('year', 'num')) ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_date'] : '';
			$error_msg .= ( request('train') && !checkdate(request('tmonth', 'num'), request('tday', 'num'), request('tyear', 'num')) ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_date'] : '';
			
			if ( $error_msg )
			{
				message(GENERAL_ERROR, $error_msg . $lang['back']);
			}
			
			$match_date = mktime(request('hour', 'num'), request('min', 'num'), 00, request('month', 'num'), request('day', 'num'), request('year', 'num'));
			
			$sql = "INSERT INTO " . MATCH . " (team_id, match_type, match_league, match_league_url, match_league_match, match_date, match_categorie, match_public, match_comments, match_rival, match_rival_tag, match_rival_url, server_ip, server_pw, server_hltv, server_hltv_pw, match_create)
						VALUES ('$team_id', '$match_type', '$match_league', '$match_league_url', '$match_league_match', '$match_date', '$match_categorie', '$match_public', '$match_comments', '$match_rival', '$match_rival_tag', '$match_rival_url', '$server_ip', '$server_pw', '$server_hltv', '$server_hltv_pw', '" . time() . "')";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			if ( request('train') )
			{
				$training_date		= mktime(request('thour', 'num'),	request('tmin', 'num'),								00, request('tmonth', 'num'),	request('tday', 'num'),	request('tyear', 'num'));
				$training_duration	= mktime(request('thour', 'num'),	request('tmin', 'num') + request('dmin', 'num'),	00, request('tmonth', 'num'),	request('tday', 'num'),	request('tyear', 'num'));
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}
				
				$sql = "INSERT INTO " . TRAINING . " (training_vs, team_id, match_id, training_date, training_duration, training_create, training_maps, training_comment)
							VALUES ('$match_rival', '$team_id', '$match_id', '$training_date', '$training_duration', " . time() . ", '$training_maps', '$training_comment')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
			
//			$monat = request('month', 'num');
//			$oCache -> sCachePath = './../cache/';
//			$oCache -> deleteCache('match_list_open_member');
//			$oCache -> deleteCache('match_list_open_guest');
//			$oCache -> deleteCache('match_list_close_member');
//			$oCache -> deleteCache('match_list_close_guest');
//			$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
//			$oCache -> deleteCache('calendar_' . $monat . '_match_member');
//			$oCache -> deleteCache('calendar_' . $monat . '_guest');
//			$oCache -> deleteCache('calendar_' . $monat . '_member');
//			$oCache -> deleteCache('display_subnavi_matchs_guest');
//			$oCache -> deleteCache('display_subnavi_matchs_member');
		
			$message = $lang['create_match'] . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'create_match');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_update_save':
		
			$team_id			= request('team_id', 'num');
			$match_type			= request('match_type', 'num');
			$match_categorie	= request('match_categorie', 'num');
			$match_league		= request('match_league', 'num');
			$match_league_url	= request('match_league_url', 'text');
			$match_league_match	= request('match_league_match', 'text');
			$match_public		= request('match_public', 'num');
			$match_comments		= request('match_comments', 'num');
			$match_rival		= request('match_rival', 'text');
			$match_rival_tag	= request('match_rival_tag', 'text');
			$match_rival_url	= request('match_rival_url', 'text');
			$match_rival_lineup	= request('match_rival_lineup', 'text');
			$server_ip			= request('server_ip', 'text');
			$server_pw			= request('server_pw', 'text');
			$server_hltv		= request('server_hltv', 'text');
			$server_hltv_pw		= request('server_hltv_pw', 'text');
			
			$error_msg = '';
			$error_msg .= ( !$team_id ) ? $lang['msg_select_team'] : '';
			$error_msg .= ( !$match_type ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_type'] : '';
			$error_msg .= ( !$match_categorie ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_cat'] : '';
			$error_msg .= ( !$match_league ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_league'] : '';
			$error_msg .= ( !checkdate(request('month', 'num'), request('day', 'num'), request('year', 'num')) ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_date'] : '';
			
			if ( $error_msg )
			{
				message(GENERAL_ERROR, $error_msg . $lang['back']);
			}
			
			$match_date = mktime(request('hour', 'num'), request('min', 'num'), 00, request('month', 'num'), request('day', 'num'), request('year', 'num'));
			
			if ( isset($HTTP_POST_VARS['listdel']) )
			{
				$sql = 'DELETE FROM ' . MATCH_USERS . ' WHERE match_id = ' . $match_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
			
			$sql = "UPDATE " . MATCH . " SET
						team_id				= '$team_id',
						match_type			= '$match_type',
						match_league		= '$match_league',
						match_league_url	= '$match_league_url',
						match_league_match	= '$match_league_match',
						match_date			= '$match_date',
						match_categorie		= '$match_categorie',
						match_public		= '$match_public',
						match_comments		= '$match_comments',
						match_rival			= '$match_rival',
						match_rival_tag		= '$match_rival_tag',
						match_rival_url		= '$match_rival_url',
						match_rival_lineup	= '$match_rival_lineup',
						server_ip			= '$server_ip',
						server_pw			= '$server_pw',
						server_hltv			= '$server_hltv',
						server_hltv_pw		= '$server_hltv_pw',
						match_update		= '" . time() . "'
					WHERE match_id = $match_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'Could not update match information', '', __LINE__, __FILE__, $sql);
			}
			
			/*
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
			*/
			
			$message = $lang['match_update'] . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'match_update');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_details':
		
			$template->set_filenames(array('body' => 'style/acp_match.tpl'));
			$template->assign_block_vars('match_details', array());
			
			$sql = 'SELECT	m.*,
							md.*,
							t.team_id, t.team_name,
							g.game_image,
							tr.training_vs, tr.training_date
						FROM ' . MATCH . ' m
					LEFT JOIN ' . MATCH_DETAILS . ' md ON m.match_id = md.match_id
					LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
					LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . TRAINING . ' tr ON tr.match_id = m.match_id
					WHERE ' . $match_id . ' = m.match_id';
			
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
			}
			$details_data = $db->sql_fetchrow($result);
			
			//	Lineup + Ersatz
			$sql = 'SELECT u.user_id, u.username, ml.status
					FROM ' . MATCH_LINEUP . ' ml, ' . USERS . ' u
					WHERE ml.match_id = ' . $match_id . ' AND ml.user_id = u.user_id
					ORDER BY ml.status';
			if (!($result_users = $db->sql_query($sql)))
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$color = '';
			while ( $users = $db->sql_fetchrow($result_users) )
			{
				$class = ( $color % 2 ) ? 'row_class1' : 'row_class2';
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
						WHERE tu.team_id = ' . $details_data['team_id'] . ' AND tu.user_id = u.user_id
						ORDER BY u.username';
			if (!($result_addusers = $db->sql_query($sql)))
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}

			$s_addusers_select = '<select class="select" name="members[]" rows="5" multiple>';
			while ( $addusers = $db->sql_fetchrow($result_addusers ))
			{
				$s_addusers_select .= '<option value="' . $addusers['user_id'] . '">' . $addusers['username'] . '&nbsp;</option>';
			}
			$s_addusers_select .= '</select>';
			
			//	Lineup
			
			//	Dropdown
			$s_action_options = '<select class="postselect" name="mode">';
			$s_action_options .= '<option value="option">&raquo; ' . $lang['option_select'] . '</option>';
			$s_action_options .= '<option value="_details_user_player">&raquo; ' . sprintf($lang['status_set'], $lang['match_player']) . '&nbsp;</option>';
			$s_action_options .= '<option value="_details_user_replace">&raquo; ' . sprintf($lang['status_set'], $lang['match_replace']) . '&nbsp;</option>';
			$s_action_options .= '<option value="_details_user_delete">&raquo; ' . $lang['common_delete'] . '</option>';
			$s_action_options .= '</select>';
			
			/*
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
			*/
			
			
			/*
			 *	Map Upload Übersicht
			 */
			$picture_path = $root_path . $settings['path_match_picture'];
			$match_maps_data = get_data_array(MATCH_MAPS, 'match_id = ' . $match_id, 'map_order', 'ASC');
			
			if ( $match_maps_data )
			{
				$template->assign_block_vars('match_details.maps', array());
				
				for ( $i = 0; $i < count($match_maps_data); $i++ )
				{
					$template->assign_block_vars('match_details.maps.info_row', array(
						'INFO_MAP_ID'		=> $match_maps_data[$i]['map_id'],
																					  
						'INFO_MAP_NAME'		=> $match_maps_data[$i]['map_name'],
						'INFO_MAP_HOME'		=> $match_maps_data[$i]['map_points_home'],
						'INFO_MAP_RIVAL'	=> $match_maps_data[$i]['map_points_rival'],
						
						'INFO_PIC_URL'		=> '<a href="' . $picture_path . '/' . $match_maps_data[$i]['map_picture'] . '" rel="lightbox"><img src="' . $picture_path . '/' . $match_maps_data[$i]['map_preview'] . '" alt="" /></a>',
					));
				}
			}
				
			$s_hidden_fields = '<input type="hidden" name="' . POST_MATCH_URL . '" value="' . $match_id . '" />';
			
			$template->assign_vars(array(
				'L_MATCH_HEAD'			=> sprintf($lang['sprintf_head'], $lang['match']),
				'L_MATCH_EDIT'			=> sprintf($lang['sprintf_edit'], $lang['match']),
				'L_MATCH_DETAILS'		=> $lang['match_details'],
				
				'L_DETAILS_UPLOAD'		=> $lang['match_details_upload'],
				'L_DETAILS_MAPS'		=> $lang['match_details_maps'],
				'L_MAP'					=> $lang['match_details_map'],
				'L_POINTS'				=> $lang['match_details_points'],
				'L_UPLOAD_MAP'			=> $lang['match_details_mappic'],
				
				'L_LINEUP_PLAYER'               => $lang['match_lineup_player'],

				'L_MATCH_EXPLAIN'		=> $lang['match_details_explain'],
				
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
				
				'DETAILS_COMMENT'		=> $row['details_comment'],

				'S_ACTION_OPTIONS'		=> $s_action_options,
				
				'S_ADDUSERS'			=> $s_addusers_select,
				
				'L_PLAYER'				=> $lang['match_player'],
				'L_REPLACE'				=> $lang['match_replace'],

				'L_UPLOAD'				=> $lang['common_upload'],
				'L_MORE'				=> $lang['common_more'],
				'L_REMOVE'				=> $lang['common_remove'],
				
				'S_FIELDS'		=> $s_hidden_fields,
				'S_MATCH_MAPS'			=> append_sid('admin_match.php?mode=_maps&amp;' . POST_MATCH_URL . '=' . $match_id),
				'S_MATCH_EDIT'			=> append_sid('admin_match.php?mode=_update&amp;' . POST_MATCH_URL . '=' . $match_id),
				'S_MATCH_ACTION'		=> append_sid('admin_match.php'),
			));
		
			break;
			
		case '_details_upload':
		
			$pic_info = request_files('ufile');
			$pic_count = count($pic_info['temp']);
		
			for ( $i = 0; $i < $pic_count; $i++ )
			{
				$sql_pic[] = image_upload('', 'image_match', '', '1', '', '', $root_path . $settings['path_match_picture'] . '/', $pic_info['temp'][$i], $pic_info['name'][$i], $pic_info['size'][$i], $pic_info['type'][$i]);
			}
			
			$pic_ary = array();
			$db_ary = array();
			
			$max_row	= get_data_max(MATCH_MAPS, 'map_order', 'match_id = ' . $match_id);
			$next_order = ( !$max_row ) ? 10 : $max_row['max'] + 10;
			
			for ( $i = 0; $i < $pic_count; $i++ )
			{
				$pic_ary[] = array(
					'match_id'			=> $match_id,
					'map_name'			=> "'" . $_POST['map_name'][$i] . "'",
					'map_points_home'	=> "'" . $_POST['map_points_home'][$i] . "'",
					'map_points_rival'	=> "'" . $_POST['map_points_rival'][$i] . "'",
					'map_picture'		=> "'" . $sql_pic[$i]['pic_filename'] . "'",
					'map_preview'		=> "'" . $sql_pic[$i]['pic_preview'] . "'",
					'upload_user'		=> $userdata['user_id'],
					'upload_time'		=> time(),
					'map_order'			=> $next_order,
				);
				$next_order += 10;
			}
			
			foreach ( $pic_ary as $id => $_pic_ary )
			{
				$values = array();
				foreach ( $_pic_ary as $key => $var )
				{
					$values[] = $var;
				}
				$db_ary[] = '(' . implode(', ', $values) . ')';
			}
			
			$sql = 'INSERT INTO ' . MATCH_MAPS . ' (' . implode(', ', array_keys($pic_ary[0])) . ') VALUES ' . implode(', ', $db_ary);
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$message = $lang['upload_match'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_id) . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'update_match_upload');
			message(GENERAL_MESSAGE, $message);

			break;
			
		case 'update':
		
			$sql = 'SELECT * FROM ' . MATCH_DETAILS . " WHERE match_id = $match_id";
			if (!($result = $db->sql_query($sql)))
			{
				message(GENERAL_ERROR, 'Error getting team information', '', __LINE__, __FILE__, $sql);
			}
	
			if (!($match_info = $db->sql_fetchrow($result)))
			{
				message(GENERAL_MESSAGE, 'match_not_exist');
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
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'Could not update team information', '', __LINE__, __FILE__, $sql);
			}
			
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'acp_match_edit');
			
			$message = $lang['team_update']
				. sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
				. sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_id) . '">', '</a>');
			message(GENERAL_MESSAGE, $message);
			
			break;
			
		case '_details_user_add':
		
			$members = request('members');
			$status = request('status');
		
			if ( !$members )
			{
				message(GENERAL_ERROR, $lang['msg_select_no_members'] . $lang['back']);
			}
			
			$ary_in_db = array();
			$ary_users = $members;
			$ary_users_list = implode(', ', $members);
			
			$sql = "SELECT user_id FROM " . MATCH_LINEUP . " WHERE user_id IN ($ary_users_list) AND match_id = $match_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$ary_in_db[] = (int) $row['user_id'];
			}
			$db->sql_freeresult($result);
			
			$user_in_db = array_diff($ary_users, $ary_in_db);
			
			if ( !count($user_in_db) )
			{
				message(GENERAL_ERROR, $lang['match_lineup_no_users'] . $lang['back']);
			}
			
			if ( count($user_in_db) )
			{
				$sql_ary = array();
		
				foreach ( $user_in_db as $user_id )
				{
					$sql_ary[] = array(
						'match_id'	=> (int) $match_id,
						'user_id'	=> (int) $user_id,							
						'status'	=> (int) $status,
					);
				}
		
				if ( !count($sql_ary) )
				{
					message(GENERAL_ERROR, $lang['match_lineup_no_users'] . $lang['back']);
				}
				
				$ary = array();
				foreach ( $sql_ary as $id => $_sql_ary )
				{
					$values = array();
					foreach ( $_sql_ary as $key => $var )
					{
						$values[] = (int) $var;
					}
					$ary[] = "(" . implode(', ', $values) . ")";
				}
				
				$sql = "INSERT INTO " . MATCH_LINEUP . " (" . implode(', ', array_keys($sql_ary[0])) . ") VALUES " . implode(', ', $ary);
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}

			$message = $lang['create_match_user']
				. sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
				. sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_id) . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'create_match_user');
			message(GENERAL_MESSAGE, $message);
				
			break;
		
		case '_details_user_player':
		case '_details_user_replace':
		
			$member = request('members');
			$status = ( $mode == '_details_user_player' ) ? '0' : '1';
			
			if ( !$member )
			{
				message(GENERAL_ERROR, $lang['msg_select_no_members'] . $lang['back']);
			}
			
			for ( $i = 0; $i < count($member); $i++ )
			{
				$sql = "UPDATE " . MATCH_LINEUP . " SET status = $status WHERE match_id = $match_id AND user_id = " . $member[$i];
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
		
			$message = $lang['update_match_user']
				. sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
				. sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_id) . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'update_match_user');
			message(GENERAL_MESSAGE, $message);
			
			break;

		case '_details_user_delete':
		
			$members = request('members');
			
			if ( !$members )
			{
				message(GENERAL_ERROR, $lang['msg_select_no_members']);
			}
			
			$sql_in = implode(', ', $members);
			
			$sql = "DELETE FROM " . MATCH_LINEUP . " WHERE user_id IN ($sql_in) AND match_id = $match_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'Could not delete memer', '', __LINE__, __FILE__, $sql);
			}
			
			$message = $lang['delete_match_user']
				. sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
				. sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_id) . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'delete_match_user');
			message(GENERAL_MESSAGE, $message);
		
			break;
		
		case '_delete':
			
			if ( $match_id && $confirm )
			{
				$match = get_data('match', $match_id, 0);
				
//				$sql = 'SELECT * FROM ' . MATCH . " WHERE match_id = $match_id";
//				if (!($result = $db->sql_query($sql)))
//				{
//					message(GENERAL_ERROR, 'Error getting match information', '', __LINE__, __FILE__, $sql);
//				}
//		
//				if (!($match_info = $db->sql_fetchrow($result)))
//				{
//					message(GENERAL_MESSAGE, $lang['match_not_exist']);
//				}
//				
//				$picture_file	= $match_info['match_picture'];
//				$picture_type	= $match_info['match_picture_type'];
//				$pictures_file	= $match_info['match_pictures'];
//				$pictures_type	= $match_info['match_pictures_type'];
//				
//				$picture_file = basename($picture_file);
//				$pictures_file = basename($pictures_file);
//				
//				if ( $picture_type == LOGO_UPLOAD && $picture_file != '' )
//				{
//					if ( @file_exists(@cms_realpath($root_path . $settings['path_match_picture'] . '/' . $picture_file)) )
//					{
//						@unlink($root_path . $settings['path_match_picture'] . '/' . $picture_file);
//					}
//				}
//				
//				if ( $pictures_type == LOGO_UPLOAD && $pictures_file != '' )
//				{
//					if ( @file_exists(@cms_realpath($root_path . $settings['match_pictures_path'] . '/' . $pictures_file)) )
//					{
//						@unlink($root_path . $settings['match_pictures_path'] . '/' . $pictures_file);
//					}
//				}
			
				$sql = 'DELETE FROM ' . MATCH . " WHERE match_id = $match_id";
				if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'DELETE FROM ' . MATCH_COMMENTS . " WHERE match_id = $match_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'DELETE FROM ' . MATCH_DETAILS . " WHERE match_id = $match_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'DELETE FROM ' . MATCH_LINEUP . " WHERE match_id = $match_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'DELETE FROM ' . MATCH_USERS . " WHERE match_id = $match_id";
				if (!($result = $db->sql_query($sql, END_TRANSACTION)))
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
			#	$monat = date("m", time());
			#	$oCache -> sCachePath = './../cache/';
			#	$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
			#	$oCache -> deleteCache('calendar_' . $monat . '_match_member');
			#	$oCache -> deleteCache('calendar_' . $monat . '_member');
			#	$oCache -> deleteCache('display_subnavi_matchs_guest');
			#	$oCache -> deleteCache('display_subnavi_matchs_member');
				
				$message = $lang['delete_match'] . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'delete_match');
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $match_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_MATCH_URL . '" value="' . $match_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_match'],
					'L_NO'				=> $lang['common_no'],
					'L_YES'				=> $lang['common_yes'],
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_match.php'),
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_match']);
			}
		
			break;
		
		default:
	
			$template->set_filenames(array('body' => 'style/acp_match.tpl'));
			$template->assign_block_vars('display', array());
			
			$s_hidden_fields = '<input type="hidden" name="mode" value="_create" />';
			
			$template->assign_vars(array(
				'L_MATCH_HEAD'		=> sprintf($lang['sprintf_head'], $lang['match']),
				'L_MATCH_CREATE'	=> sprintf($lang['sprintf_creates'], $lang['match']),
				'L_MATCH_EXPLAIN'	=> $lang['match_explain'],
				
				'L_UPCOMING'		=> $lang['match_upcoming'],
				'L_EXPIRED'			=> $lang['match_expired'],
				'L_TRAINING'		=> $lang['training'],
				'L_DETAILS'			=> $lang['common_details'],
				
				'L_UPDATE'			=> $lang['common_update'],
				'L_DELETE'			=> $lang['common_delete'],				
				'L_SETTINGS'		=> $lang['common_settings'],
				
				'S_TEAMS'			=> select_box('team', 'selectsmall', 0, 0),
				
				'S_FIELDS'	=> $s_hidden_fields,
				'S_MATCH_CREATE'	=> append_sid('admin_match.php?mode=_create'),
				'S_MATCH_ACTION'	=> append_sid('admin_match.php'),
			));
	
			$sql = 'SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
						FROM ' . MATCH . ' m
							LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
							LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
							LEFT JOIN ' . TRAINING . ' tr ON m.match_id = tr.match_id
					ORDER BY m.match_date DESC';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$match_data = $db->sql_fetchrowset($result); 
			$db->sql_freeresult($result);
			
			if ( $match_data )
			{
				$match_new = $match_old = array();
					
				foreach ( $match_data as $match => $row )
				{
					if ( $row['match_date'] > time() )
					{
						$match_new[] = $row;
					}
					else if ( $row['match_date'] < time() )
					{
						$match_old[] = $row;
					}
				}
				
				if ( $match_new )
				{
					for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($match_new)); $i++ )
					{
						$match_typ = ( $match_new[$i]['match_public'] ) ? 'sprintf_match_name' : 'sprintf_match_intern';
						
						$template->assign_block_vars('display.match_row_new', array(
							'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
							'MATCH_GAME'	=> display_gameicon($match_new[$i]['game_size'], $match_new[$i]['game_image']),
							'MATCH_NAME'	=> sprintf($lang[$match_typ], $match_new[$i]['match_rival']),
							'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $match_new[$i]['match_date'], $userdata['user_timezone']),
							'TRAINING'		=> ( !$match_new[$i]['training_id'] ) ? $lang['add_train'] : $lang['edit_train'],
							'U_TRAINING'	=> ( !$match_new[$i]['training_id'] ) ? append_sid('admin_training.php?mode=_create&amp;' . POST_TEAMS_URL . '=' . $match_new[$i]['team_id'].'&amp;' . POST_MATCH_URL . '=' . $match_new[$i]['match_id'].'&amp;vs=' . $match_new[$i]['match_rival']) : append_sid('admin_training.php?mode=edit&amp;' . POST_TRAINING_URL . '=' . $match_new[$i]['training_id']),
							'U_DETAILS'		=> append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_new[$i]['match_id']),
							'U_UPDATE'		=> append_sid('admin_match.php?mode=_update&amp;' . POST_MATCH_URL . '=' . $match_new[$i]['match_id']),
							'U_DELETE'		=> append_sid('admin_match.php?mode=_delete&amp;' . POST_MATCH_URL . '=' . $match_new[$i]['match_id'])
						));
					}
				}
				else
				{
					$template->assign_block_vars('display.no_entry_new', array());
					$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
				}
				
				if ( $match_old )
				{
					for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($match_old)); $i++ )
					{
						$match_typ = ( $match_old[$i]['match_public'] ) ? 'sprintf_match_name' : 'sprintf_match_intern';
						
						$template->assign_block_vars('display.match_row_old', array(
							'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
							'MATCH_GAME'	=> display_gameicon($match_old[$i]['game_size'], $match_old[$i]['game_image']),
							'MATCH_NAME'	=> sprintf($lang[$match_typ], $match_old[$i]['match_rival']),
							'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $match_old[$i]['match_date'], $userdata['user_timezone']),
							'TRAINING'		=> ( !$match_old[$i]['training_id'] ) ? $lang['add_train'] : $lang['edit_train'],
							'U_TRAINING'	=> ( !$match_old[$i]['training_id'] ) ? append_sid('admin_training.php?mode=_create&amp;' . POST_TEAMS_URL . '=' . $match_old[$i]['team_id'].'&amp;' . POST_MATCH_URL . '=' . $match_old[$i]['match_id']."&amp;vs=" . $match_old[$i]['match_rival']) : append_sid('admin_training.php?mode=edit&amp;' . POST_TRAINING_URL . '=' . $match_old[$i]['training_id']),
							'U_DETAILS'		=> append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_old[$i]['match_id']),
							'U_UPDATE'		=> append_sid('admin_match.php?mode=_update&amp;' . POST_MATCH_URL . '=' . $match_old[$i]['match_id']),
							'U_DELETE'		=> append_sid('admin_match.php?mode=_delete&amp;' . POST_MATCH_URL . '=' . $match_old[$i]['match_id']),
						));
					}
				}
				else
				{
					$template->assign_block_vars('display.no_entry_old', array());
					$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
				}
			}
			else
			{
				$match_new = $match_old = '';
				$template->assign_block_vars('display.no_entry_new', array());
				$template->assign_block_vars('display.no_entry_old', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}

			$current_page = ( !count($match_data) ) ? 1 : ceil( count($match_data) / $settings['site_entry_per_page'] );
		
			$template->assign_vars(array(
				'L_GOTO_PAGE'	=> $lang['Goto_page'],
				'PAGINATION'	=> generate_pagination('admin_match.php?', count($match_data), $settings['site_entry_per_page'], $start),
				'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
			));
			
			break;
	}
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}
?>