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
	$current	= '_submenu_match';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/match.php');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_MATCH_URL, 0);
	$map_id		= request(POST_MATCH_PIC_URL, 0);
	$team_id	= request(POST_TEAMS_URL, 0);
	$confirm	= request('confirm', 1);
	$order		= request('order', 0);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_matchs'] . '/';
	$show_index	= '';
	
	if ( !$userauth['auth_match'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_match.php', true));
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_match.tpl'));
				$template->assign_block_vars('match_edit', array());
				
				debug($_POST);
				
				if ( $mode == '_create' && !request('submit', 2) )
				{
					$data = array(
						'team_id'				=> request('team_id', 0),
						'match_type'			=> '',
						'match_categorie'		=> '0',
						'match_date'			=> time(),
						'match_public'			=> '1',
						'match_comments'		=> $settings['comments_matches'],
						'match_league'			=> '',
						'match_league_url'		=> '',
						'match_league_match'	=> '',
						'match_rival'			=> '',
						'match_rival_tag'		=> '',
						'match_rival_url'		=> '',
						'match_rival_lineup'	=> '',
						'server_ip'				=> '',
						'server_pw'				=> '',
						'server_hltv'			=> '',
						'server_hltv_pw'		=> '',
						'training'				=> '0',
						'training_date'			=> time(),
						'training_duration'		=> '',
						'training_maps'			=> '',
						'training_text'			=> '',
					);
					
					$template->assign_block_vars('match_edit.new_match', array());
				}
				else if ( $mode == '_update' && !request('submit', 2) )
				{
					$data = get_data(MATCH, $data_id, 1);
					
					$template->assign_block_vars('match_edit.edit_match', array());
				
					if ( $data['match_date'] > time() )
					{
						$template->assign_block_vars('match_edit.reset_match', array());
					}
				}
				else
				{
					$match_date			= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
					$training_date		= ( $mode == '_create' ) ? mktime(request('thour', 0), request('tmin', 0), 00, request('tmonth', 0), request('tday', 0), request('tyear', 0)) : '';
					$training_duration	= ( $mode == '_create' ) ? mktime(request('thour', 0), request('tmin', 0) + request('dmin', 0), 00, request('tmonth', 0), request('tday', 0), request('tyear', 0)) : '';
					
					if ( $mode == '_create' )
					{
						$template->assign_block_vars('match_edit.new_match', array());
					}
					else if ( $mode == '_update' )
					{
						$template->assign_block_vars('match_edit.edit_match', array());
				
						if ( $match_date > time() )
						{
							$template->assign_block_vars('match_edit.reset_match', array());
						}
					}
					
					$data = array(
						'team_id'				=> request('team_id', 0),
						'match_type'			=> request('match_type', 0),
						'match_categorie'		=> request('match_categorie', 0),
						'match_public'			=> request('match_public', 0),
						'match_comments'		=> request('match_comments', 0),
						'match_date'			=> $match_date,
						'match_league'			=> request('match_league', 0),
						'match_league_url'		=> request('match_league_url', 0),
						'match_league_match'	=> request('teamatch_league_matchm_id', 0),
						'match_rival'			=> request('match_rival', 2),
						'match_rival_tag'		=> request('match_rival_tag', 2),
						'match_rival_url'		=> request('match_rival_url', 2),
						'match_rival_lineup'	=> request('match_rival_lineup', 2),
						'server_ip'				=> request('server_ip', 2),
						'server_pw'				=> request('server_pw', 2),
						'server_hltv'			=> request('server_hltv', 2),
						'server_hltv_pw'		=> request('server_hltv_pw', 2),
						'training'				=> ( $mode == '_create' ) ? request('training', 0) : '',
						'training_date'			=> ( $mode == '_create' ) ? $training_date : '',
						'training_duration'		=> ( $mode == '_create' ) ? $training_duration : '',
						'training_maps'			=> ( $mode == '_create' ) ? request('training_maps', 2) : '',
						'training_text'			=> ( $mode == '_create' ) ? request('training_text', 2) : '',
					);
				}
			
				$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
				$s_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_MATCH_URL . '" value="' . $data_id . '" />';
				
				$template->assign_vars(array(
					'L_TITLE'			=> sprintf($lang['sprintf_head'], $lang['match']),
					'L_NEW_EDIT'		=> sprintf($lang[$ssprintf], $lang['match'], $data['match_rival']),
					'L_DETAILS'			=> $lang['match_details'],
					
					'L_INFO_A'			=> $lang['match_info_a'],
					'L_INFO_B'			=> $lang['match_info_b'],
					'L_INFO_C'			=> $lang['match_info_c'],
					'L_INFO_D'			=> $lang['match_info_d'],
					'L_INFO_E'			=> $lang['match_info_e'],
					
					'L_TEAM'			=> $lang['match_team'],
					'L_TYPE'			=> $lang['match_type'],
					'L_CATEGORIE'		=> $lang['match_categorie'],
					'L_LEAGUE'			=> $lang['match_league'],
					'L_LEAGUE_URL'		=> $lang['match_league_url'],
					'L_LEAGUE_MATCH'	=> $lang['match_league_match'],
					'L_DATE'			=> $lang['match_date'],
					'L_PUBLIC'			=> $lang['match_public'],
					'L_COMMENTS'		=> $lang['match_comments'],
					
					'L_RIVAL'			=> $lang['match_rival'],
					'L_RIVAL_TAG'		=> $lang['match_rival_tag'],
					'L_RIVAL_URL'		=> $lang['match_rival_url'],
					'L_RIVAL_LINEUP'	=> $lang['match_rival_lineup'],
					
					'L_SERVER_IP'		=> $lang['match_server'],
					'L_SERVER_PW'		=> $lang['match_serverpw'],
					'L_HLTV'			=> $lang['match_hltv'],
					'L_HLTV_PW'			=> $lang['match_hltvpw'],
					
					'L_COMMENT'			=> $lang['match_details_comment'],
					'L_REPORT'			=> $lang['match_text'],
					
					'L_TRAINING'		=> $lang['training'],
					'L_TRAINING_DATE'	=> $lang['training_date'],
					'L_TRAINING_MAPS'	=> $lang['training_maps'],
					'L_TRAINING_TEXT'	=> $lang['training_text'],
					
					'L_RESET_LIST'		=> $lang['match_interest_reset'],
					
					'LEAGUE_URL'		=> $data['match_league_url'],
					'LEAGUE_MATCH'		=> $data['match_league_match'],
					
					'RIVAL'				=> $data['match_rival'],
					'RIVAL_TAG'			=> $data['match_rival_tag'],
					'RIVAL_URL'			=> $data['match_rival_url'],
					'RIVAL_LINEUP'		=> $data['match_rival_lineup'],
					
					'SERVER_IP'			=> $data['server_ip'],
					'SERVER_PW'			=> $data['server_pw'],
					'SERVER_HLTV'		=> $data['server_hltv'],
					'SERVER_HLTV_PW'	=> $data['server_hltv_pw'],
					
					'TRAINING_MAPS'		=> $data['training_maps'],
					'TRAINING_TEXT'		=> $data['training_text'],
					
					'S_TEAMS'			=> select_box('team', 'select', $data['team_id'], 0),
					'S_TYPE'			=> select_lang_box('select_type_box', 'match_type', $data['match_type'], 'select'),
					'S_LEAGUE'			=> select_lang_box('select_league_box', 'match_league', $data['match_league'], 'select'),
					'S_CATEGORIE'		=> select_lang_box('select_categorie_box', 'match_categorie', $data['match_categorie'], 'select'),
					
					'S_DAY'				=> select_date('day', 'day',		date('d', $data['match_date'])),
					'S_MONTH'			=> select_date('month', 'month',	date('m', $data['match_date'])),
					'S_YEAR'			=> select_date('year', 'year',		date('Y', $data['match_date'])),
					'S_HOUR'			=> select_date('hour', 'hour',		date('H', $data['match_date'])),
					'S_MIN'				=> select_date('min', 'min',		date('i', $data['match_date'])),
					
					'S_PUBLIC_YES'		=> ( $data['match_public'] ) ? ' checked="checked"' : '',
					'S_PUBLIC_NO'		=> ( !$data['match_public'] ) ? ' checked="checked"' : '',
					'S_COMMENT_YES'		=> ( $data['match_comments'] ) ? ' checked="checked"' : '',
					'S_COMMENT_NO'		=> ( !$data['match_comments'] ) ? ' checked="checked"' : '',
					
					'S_TRAINING_YES'	=> ( $mode == '_create' ) ? ( $data['training'] ? ' checked="checked"' : '' ) : '',
					'S_TRAINING_NO'		=> ( $mode == '_create' ) ? ( !$data['training'] ? ' checked="checked"' : '' ) : '',
					'S_TRAINING_NONE'	=> ( $mode == '_create' ) ? ( !$data['training'] ? 'none' : ''  ) : '',
					
					'S_TDAY'			=> ( $mode == '_create' ) ? select_date('day', 'tday',		date('d', $data['training_date'])) : '',
					'S_TMONTH'			=> ( $mode == '_create' ) ? select_date('month', 'tmonth',	date('m', $data['training_date'])) : '',
					'S_TYEAR'			=> ( $mode == '_create' ) ? select_date('year', 'tyear',	date('Y', $data['training_date'])) : '',
					'S_THOUR'			=> ( $mode == '_create' ) ? select_date('hour', 'thour',	date('H', $data['training_date'])) : '',
					'S_TMIN'			=> ( $mode == '_create' ) ? select_date('min', 'tmin',		date('i', $data['training_date'])) : '',
					'S_TDURATION'		=> ( $mode == '_create' ) ? select_date('duration', 'dmin',	date('i', $data['training_date'])) : '',
					
					'S_FIELDS'			=> $s_fields,
					'S_DETAILS'			=> append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id),
					'S_ACTION'			=> append_sid('admin_match.php'),
				));
				
				if ( request('submit', 2) )
				{
					$team_id			= request('team_id', 0);
					$match_type			= request('match_type', 0);
					$match_categorie	= request('match_categorie', 0);
					$match_public		= request('match_public', 0);
					$match_comments		= request('match_comments', 0);
					$match_league		= request('match_league', 0);
					$match_league_url	= request('match_league_url', 2);
					$match_league_match	= request('match_league_match', 2);
					$match_rival		= request('match_rival', 2);
					$match_rival_tag	= request('match_rival_tag', 2);
					$match_rival_url	= request('match_rival_url', 2);
					$match_rival_lineup	= request('match_rival_lineup', 2);
					$server_ip			= request('server_ip', 2);
					$server_pw			= request('server_pw', 2);
					$server_hltv		= request('server_hltv', 2);
					$server_hltv_pw		= request('server_hltv_pw', 2);
					$training			= request('training', 0);
					$training_maps		= request('training_maps', 2);
					$training_comment	= request('training_comment', 2);
					
					$error = '';
					$error .= ( $team_id == '-1' )	? $lang['msg_select_team'] : '';
					$error .= ( !$match_type )		? ( $error ? '<br>' : '' ) . $lang['msg_select_type'] : '';
					$error .= ( !$match_categorie ) ? ( $error ? '<br>' : '' ) . $lang['msg_select_cat'] : '';
					$error .= ( !$match_league )	? ( $error ? '<br>' : '' ) . $lang['msg_select_league'] : '';
					$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br>' : '' ) . $lang['msg_select_date'] : '';
					$error .= ( !$match_rival )		? ( $error ? '<br>' : '' ) . $lang['msg_select_rival'] : '';
					$error .= ( !$match_rival_tag )	? ( $error ? '<br>' : '' ) . $lang['msg_select_rival_tag'] : '';
					$error .= ( !$server_ip )		? ( $error ? '<br>' : '' ) . $lang['msg_select_server'] : '';
					$error .= ( $mode == '_create' && $training && !checkdate(request('tmonth', 0), request('tday', 0), request('tyear', 0)) ) ? ( $error ? '<br>' : '' ) . $lang['msg_select_date'] : '';
						
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
							$match_date = mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
							
							$sql = "INSERT INTO " . MATCH . " (team_id, match_type, match_league, match_league_url, match_league_match, match_date, match_categorie, match_public, match_comments, match_rival, match_rival_tag, match_rival_url, server_ip, server_pw, server_hltv, server_hltv_pw, match_create)
										VALUES ('$team_id', '$match_type', '$match_league', '$match_league_url', '$match_league_match', '$match_date', '$match_categorie', '$match_public', '$match_comments', '$match_rival', '$match_rival_tag', '$match_rival_url', '$server_ip', '$server_pw', '$server_hltv', '$server_hltv_pw', '" . time() . "')";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							if ( request('training') )
							{
								$training_date		= mktime(request('thour'), request('tmin'), 00, request('tmonth'), request('tday'), request('tyear'));
								$training_duration	= mktime(request('thour'), request('tmin') + request('dmin', 0), 00, request('tmonth'), request('tday'), request('tyear'));
								
								$sql = "INSERT INTO " . TRAINING . " (training_vs, team_id, match_id, training_date, training_duration, training_create, training_maps, training_comment)
											VALUES ('$match_rival', '$team_id', '$data_id', '$training_date', '$training_duration', " . time() . ", '$training_maps', '$training_comment')";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$message = $lang['create_match'] . sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>');
							log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'create_match');
						}
						else
						{
							$match_date = mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
							if ( request('listdel') )
							{
								$sql = "DELETE FROM " . MATCH_USERS . " WHERE match_id = $data_id";
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
									WHERE match_id = $data_id";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'Could not update match information', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_match']
								. sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_match.php?mode=_update&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');;
							log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'update_match');
						}
							
	//					$monat = request('month', 0);
	//					$oCache -> sCachePath = './../cache/';
	//					$oCache -> deleteCache('match_list_open_member');
	//					$oCache -> deleteCache('match_list_open_guest');
	//					$oCache -> deleteCache('match_list_close_member');
	//					$oCache -> deleteCache('match_list_close_guest');
	//					$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
	//					$oCache -> deleteCache('calendar_' . $monat . '_match_member');
	//					$oCache -> deleteCache('calendar_' . $monat . '_guest');
	//					$oCache -> deleteCache('calendar_' . $monat . '_member');
	//					$oCache -> deleteCache('display_subnavi_matchs_guest');
	//					$oCache -> deleteCache('display_subnavi_matchs_member');
	
						message(GENERAL_MESSAGE, $message);
					}
				}
				
				$template->pparse('body');
				
				break;
			
			case '_create_save':
			
				$team_id			= request('team_id', 0);
				$match_type			= request('match_type', 0);
				$match_categorie	= request('match_categorie', 0);
				$match_league		= request('match_league', 0);
				$match_league_url	= request('match_league_url', 2);
				$match_league_match	= request('match_league_match', 2);
				$match_public		= request('match_public', 0);
				$match_comments		= request('match_comments', 0);
				$match_rival		= request('match_rival', 2);
				$match_rival_tag	= request('match_rival_tag', 2);
				$match_rival_url	= request('match_rival_url', 2);
				$match_rival_lineup	= request('match_rival_lineup', 2);
				$server_ip			= request('server_ip', 2);
				$server_pw			= request('server_pw', 2);
				$server_hltv		= request('server_hltv', 2);
				$server_hltv_pw		= request('server_hltv_pw', 2);
				$training_maps		= request('training_maps', 2);
				$training_comment	= request('training_comment', 2);
				
				$error_msg = '';
				$error_msg .= ( !$team_id ) ? $lang['msg_select_team'] : '';
				$error_msg .= ( !$match_type ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_type'] : '';
				$error_msg .= ( !$match_categorie ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_cat'] : '';
				$error_msg .= ( !$match_league ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_league'] : '';
				$error_msg .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_date'] : '';
				$error_msg .= ( request('train') && !checkdate(request('tmonth', 0), request('tday', 0), request('tyear', 0)) ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_date'] : '';
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}
				
				$match_date = mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				$sql = "INSERT INTO " . MATCH . " (team_id, match_type, match_league, match_league_url, match_league_match, match_date, match_categorie, match_public, match_comments, match_rival, match_rival_tag, match_rival_url, server_ip, server_pw, server_hltv, server_hltv_pw, match_create)
							VALUES ('$team_id', '$match_type', '$match_league', '$match_league_url', '$match_league_match', '$match_date', '$match_categorie', '$match_public', '$match_comments', '$match_rival', '$match_rival_tag', '$match_rival_url', '$server_ip', '$server_pw', '$server_hltv', '$server_hltv_pw', '" . time() . "')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( request('train') )
				{
					$training_date		= mktime(request('thour', 0),	request('tmin', 0),								00, request('tmonth', 0),	request('tday', 0),	request('tyear', 0));
					$training_duration	= mktime(request('thour', 0),	request('tmin', 0) + request('dmin', 0),	00, request('tmonth', 0),	request('tday', 0),	request('tyear', 0));
					
					if ( $error_msg )
					{
						message(GENERAL_ERROR, $error_msg . $lang['back']);
					}
					
					$sql = "INSERT INTO " . TRAINING . " (training_vs, team_id, match_id, training_date, training_duration, training_create, training_maps, training_comment)
								VALUES ('$match_rival', '$team_id', '$data_id', '$training_date', '$training_duration', " . time() . ", '$training_maps', '$training_comment')";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
	//			$monat = request('month', 0);
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
			
				$team_id			= request('team_id', 0);
				$match_type			= request('match_type', 0);
				$match_categorie	= request('match_categorie', 0);
				$match_league		= request('match_league', 0);
				$match_league_url	= request('match_league_url', 2);
				$match_league_match	= request('match_league_match', 2);
				$match_public		= request('match_public', 0);
				$match_comments		= request('match_comments', 0);
				$match_rival		= request('match_rival', 2);
				$match_rival_tag	= request('match_rival_tag', 2);
				$match_rival_url	= request('match_rival_url', 2);
				$match_rival_lineup	= request('match_rival_lineup', 2);
				$server_ip			= request('server_ip', 2);
				$server_pw			= request('server_pw', 2);
				$server_hltv		= request('server_hltv', 2);
				$server_hltv_pw		= request('server_hltv_pw', 2);
				
				$error_msg = '';
				$error_msg .= ( !$team_id ) ? $lang['msg_select_team'] : '';
				$error_msg .= ( !$match_type ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_type'] : '';
				$error_msg .= ( !$match_categorie ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_cat'] : '';
				$error_msg .= ( !$match_league ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_league'] : '';
				$error_msg .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_date'] : '';
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}
				
				$match_date = mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				if ( isset($HTTP_POST_VARS['listdel']) )
				{
					$sql = 'DELETE FROM ' . MATCH_USERS . ' WHERE match_id = ' . $data_id;
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
						WHERE match_id = $data_id";
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
				
				if ( $order )
				{
					update(MATCH_MAPS, 'map', $move, $map_id);
					orders(MATCH_MAPS);
					
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'acp_mappic_order');
				}
				
				/*
				 *	Match details und Match training rausgenommen!
				 */
				$sql = "SELECT	m.*, t.team_id, t.team_name, g.game_image 
							FROM " . MATCH . " m
								LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE m.match_id = $data_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$data_details = $db->sql_fetchrow($result);
				
				/*
				 *	Lineup + Erstaz
				 */
				$sql = "SELECT u.user_id, u.username, ml.status FROM " . MATCH_LINEUP . " ml, " . USERS . " u WHERE ml.match_id = " . $data_id . " AND ml.user_id = u.user_id ORDER BY ml.status";
				if (!($result_users = $db->sql_query($sql)))
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$users = $db->sql_fetchrowset($result_users);
				
				if ( $users )
				{
					$template->assign_block_vars('match_details.option', array());
					
					for ( $i = 0; $i < count($users); $i++ )
					{
						$template->assign_block_vars('match_details.row_members', array(
							'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
							'USER_ID'		=> $users[$i]['user_id'],
							'USERNAME'		=> $users[$i]['username'],
							'STATUS'		=> ( !$users[$i]['status'] ) ? $lang['details_status_player'] : $lang['details_status_replace'],
						));
					}
				}
				else
				{
					$template->assign_block_vars('match_details.no_row_members', array());
					$template->assign_vars(array('NO_MEMBER' => $lang['team_no_member']));
				}
				
				/*
				 *	Lineup + Erstaz
				 */
				$sql = 'SELECT u.user_id, u.username FROM ' . USERS . ' u, ' . TEAMS_USERS . ' tu WHERE tu.team_id = ' . $data_details['team_id'] . ' AND tu.user_id = u.user_id ORDER BY u.username';
				if (!($result_addusers = $db->sql_query($sql)))
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
	
				$s_select = '<select class="select" name="members[]" rows="5" multiple>';
				while ( $addusers = $db->sql_fetchrow($result_addusers ))
				{
					$s_select .= '<option value="' . $addusers['user_id'] . '">' . $addusers['username'] . '&nbsp;</option>';
				}
				$s_select .= '</select>';
				
				//	Dropdown
				$s_options = '<select class="postselect" name="mode">';
				$s_options .= '<option value="option">&raquo; ' . $lang['option_select'] . '</option>';
				$s_options .= '<option value="_details_user_player">&raquo; ' . sprintf($lang['details_status_set'], $lang['details_status_player']) . '&nbsp;</option>';
				$s_options .= '<option value="_details_user_replace">&raquo; ' . sprintf($lang['details_status_set'], $lang['details_status_replace']) . '&nbsp;</option>';
				$s_options .= '<option value="_details_user_delete">&raquo; ' . $lang['common_delete'] . '</option>';
				$s_options .= '</select>';
				
				/*
				 *	Map Upload Übersicht
				 */
				$max_order = get_data_max(MATCH_MAPS, 'map_order', 'match_id = ' . $data_id);
				$match_maps_data = get_data_array(MATCH_MAPS, 'match_id = ' . $data_id, 'map_order', 'ASC');
				
				if ( $match_maps_data )
				{
					$template->assign_block_vars('match_details.maps', array());
					
					for ( $i = 0; $i < count($match_maps_data); $i++ )
					{
						$map_id = $match_maps_data[$i]['map_id'];
						
						$template->assign_block_vars('match_details.maps.info_row', array(
							'INFO_MAP_ID'		=> $map_id,
																						  
							'INFO_MAP_NAME'		=> $match_maps_data[$i]['map_name'],
							'INFO_MAP_HOME'		=> $match_maps_data[$i]['map_points_home'],
							'INFO_MAP_RIVAL'	=> $match_maps_data[$i]['map_points_rival'],
							'INFO_PIC_URL'		=> '<a href="' . $path_dir . $match_maps_data[$i]['map_picture'] . '" rel="lightbox"><img src="' . $path_dir . $match_maps_data[$i]['map_preview'] . '" alt="" /></a>',
							
							'MOVE_UP'			=> ( $match_maps_data[$i]['map_order'] != '10' )				? '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id . '&amp;order=1&amp;move=-15&amp;' . POST_MATCH_PIC_URL . '=' . $map_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
							'MOVE_DOWN'			=> ( $match_maps_data[$i]['map_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id . '&amp;order=1&amp;move=15&amp;' . POST_MATCH_PIC_URL . '=' . $map_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
	
						));
						
						if ( $match_maps_data[$i]['map_picture'] )
						{
							$template->assign_block_vars('match_details.maps.info_row.delete', array());
						}
					}
				}
				
				$s_fields = '<input type="hidden" name="mode" value="_details" /><input type="hidden" name="' . POST_MATCH_URL . '" value="' . $data_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['match']),
					'L_EDIT'			=> sprintf($lang['sprintf_edit'], $lang['match'], $data_details['match_rival']),
					'L_DETAILS'			=> $lang['match_details'],
					'L_EXPLAIN'			=> $lang['match_details_explain'],
					
					'L_MAPS'			=> $lang['details_maps'],
					'L_MAPS_PIC'		=> $lang['details_maps_pic'],
					'L_MAPS_OVERVIEW'	=> $lang['details_maps_overview'],
					
					'L_LINEUP'			=> $lang['details_lineup'],
					'L_LINEUP_PLAYER'	=> $lang['details_lineup_player'],
					'L_LINEUP_STATUS'	=> $lang['details_lineup_status'],
					'L_LINEUP_ADD'		=> $lang['details_lineup_add'],
					'L_LINEUP_ADD_EX'	=> $lang['details_lineup_add_explain'],
					'L_LINEUP_PLAYER'	=> $lang['details_status_player'],
					'L_LINEUP_REPLACE'	=> $lang['details_status_replace'],
					'L_LINEUP_USERNAME'	=> $lang['username'],
					'L_DETAIL_MAP'		=> $lang['details_map'],
					'L_DETAIL_MAPPIC'	=> $lang['details_mappic'],
					'L_DETAIL_POINTS'	=> $lang['details_points'],
					
					'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
					
					'S_FIELDS'			=> $s_fields,
					'S_SELECT'			=> $s_select,
					'S_OPTIONS'			=> $s_options,
					'S_EDIT'			=> append_sid('admin_match.php?mode=_update&amp;' . POST_MATCH_URL . '=' . $data_id),
					'S_ACTION'			=> append_sid('admin_match.php'),
				));
				
				if ( request('_details_map') || request('_details_mappic') || request('_details_update') )
				{
					$max_row	= get_data_max(MATCH_MAPS, 'map_order', 'match_id = ' . $data_id);
					$next_order = ( !$max_row ) ? 10 : $max_row['max'] + 10;
						
					if ( request('_details_map') )
					{
						$map_name			= request('map_name', 2);
						$map_points_home	= request('map_points_home');
						$map_points_rival	= request('map_points_rival');
						
						if ( $map_name )
						{						
							for ( $i = 0; $i < count($map_name); $i++ )
							{
								if ( $map_name[$i] == '' )
								{
									unset($map_name[$i], $map_points_home[$i], $map_points_rival[$i]);
								}
							}
						
							if ( $map_name )
							{
								array_multisort($map_name, $map_points_home, $map_points_rival);
								
								for ( $j = 0; $j < count($map_name); $j++ )
								{
									$pic_ary[] = array(
										'match_id'			=> $data_id,
										'map_name'			=> "'" . trim(htmlentities(str_replace("'", "\'", strip_tags($map_name[$j])), ENT_COMPAT)) . "'",
										'map_points_home'	=> intval($map_points_home[$j]),
										'map_points_rival'	=> intval($map_points_rival[$j]),
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
									$db_ary[] = "(" . implode(', ', $values) . ")";
								}
								
								$sql = "INSERT INTO " . MATCH_MAPS . " (" . implode(', ', array_keys($pic_ary[0])) . ") VALUES " . implode(', ', $db_ary);
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								$message = $lang['update_match'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');
								log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'update_match_map');
							}
							else
							{
								$message = $lang['msg_select_match_map'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');
							}
						}
						else
						{
							$message = $lang['msg_select_match_map'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');
							message(GENERAL_ERROR, $message);
						}
					}
					else if ( request('_details_mappic') )
					{
						$pic_info = request_files('ufile');
						$pic_count = count($pic_info['temp']);
						
						if ( $pic_info['temp'] )
						{
							debug($pic_info['temp']);
							
							for ( $i = 0; $i < $pic_count; $i++ )
							{
								$sql_pic[] = image_upload('', 'image_match', '', '1', '', '', $path_dir, $pic_info['temp'][$i], $pic_info['name'][$i], $pic_info['size'][$i], $pic_info['type'][$i]);
							}
							
							$db_ary = array();
							$pic_ary = array();
							
							for ( $i = 0; $i < $pic_count; $i++ )
							{
								$pic_ary[] = array(
									'match_id'			=> $data_id,
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
							
							$sql = "INSERT INTO " . MATCH_MAPS . " (" . implode(', ', array_keys($pic_ary[0])) . ") VALUES " . implode(', ', $db_ary);
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['upload_match'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');
							log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'update_match_upload');
						}
						else
						{
							$message = $lang['msg_select_match_map'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');
							message(GENERAL_ERROR, $message);
						}
					}
					else if ( request('_details_update') )
					{
						if ( request('delete') )
						{
							$delete = request('delete');
					
							$sql_in = implode(', ', $delete);
							
							$sql = "SELECT map_id, map_picture, map_preview FROM " . MATCH_MAPS . " WHERE map_id IN ($sql_in) AND match_id = $data_id";
							if (!($result_users = $db->sql_query($sql)))
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							$delete_data = $db->sql_fetchrowset($result_users);
							
							for ( $i = 0; $i < count($delete_data); $i++ )
							{
								if ( $delete_data[$i]['map_picture'] )
								{
									image_delete($delete_data[$i]['map_picture'], $delete_data[$i]['map_picture'], $path_dir, 'map_picture, map_preview');
								}
							}
							
							$sql = "DELETE FROM " . MATCH_MAPS . " WHERE map_id IN ($sql_in) AND match_id = $data_id";
							if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							orders(MATCH_MAPS);
						}
						
						if ( request('mappic_delete') )
						{
							$mappic = request('mappic_delete');
							
							$sql_in = implode(', ', $mappic);
							
							$sql = "SELECT map_id, map_picture, map_preview FROM " . MATCH_MAPS . " WHERE map_id IN ($sql_in) AND match_id = $data_id";
							if (!($result_users = $db->sql_query($sql)))
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							$delete_data = $db->sql_fetchrowset($result_users);
							
							for ( $i = 0; $i < count($delete_data); $i++ )
							{
								if ( $delete_data[$i]['map_picture'] )
								{
									$sql_qry = image_delete($delete_data[$i]['map_picture'], $delete_data[$i]['map_picture'], $path_dir, 'map_picture, map_preview');
								
									$sql = "UPDATE " . MATCH_MAPS . " SET $sql_qry WHERE map_id = " . $delete_data[$i]['map_id'] . " AND match_id = $data_id";
									if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
								}
							}
							
						}
						
						if ( request('_details_update') )
						{
							$pic_info = request_files('ufile');
							$pic_count = count($pic_info['temp']);
						
							for ( $i = 0; $i < $pic_count; $i++ )
							{
								$sql_pic[] = image_upload('', 'image_match', '', '1', '', '', $path_dir, $pic_info['temp'][$i], $pic_info['name'][$i], $pic_info['size'][$i], $pic_info['type'][$i]);
							}
							
							$db_ary = array();
							$pic_ary = array();
							
							for ( $i = 0; $i < $pic_count; $i++ )
							{
								$pic_ary[] = array(
									'match_id'			=> $data_id,
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
							
							$sql = "INSERT INTO " . MATCH_MAPS . " (" . implode(', ', array_keys($pic_ary[0])) . ") VALUES " . implode(', ', $db_ary);
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'update_match');
					message(GENERAL_MESSAGE, $message);
				}
				
				$template->pparse('body');
				
				break;
				
			case '_details_upload':
			
				$pic_info = request_files('ufile');
				$pic_count = count($pic_info['temp']);
			
				for ( $i = 0; $i < $pic_count; $i++ )
				{
					$sql_pic[] = image_upload('', 'image_match', '', '1', '', '', $path_dir, $pic_info['temp'][$i], $pic_info['name'][$i], $pic_info['size'][$i], $pic_info['type'][$i]);
				}
				
				$pic_ary = array();
				$db_ary = array();
				
				$max_row	= get_data_max(MATCH_MAPS, 'map_order', 'match_id = ' . $data_id);
				$next_order = ( !$max_row ) ? 10 : $max_row['max'] + 10;
				
				for ( $i = 0; $i < $pic_count; $i++ )
				{
					$pic_ary[] = array(
						'match_id'			=> $data_id,
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
				
				$message = $lang['upload_match'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'update_match_upload');
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
				
				$sql = "SELECT user_id FROM " . MATCH_LINEUP . " WHERE user_id IN ($ary_users_list) AND match_id = $data_id";
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
							'match_id'	=> (int) $data_id,
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
					. sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');
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
					$sql = "UPDATE " . MATCH_LINEUP . " SET status = $status WHERE match_id = $data_id AND user_id = " . $member[$i];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
			
				$message = $lang['update_match_user']
					. sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
					. sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');
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
				
				$sql = "DELETE FROM " . MATCH_LINEUP . " WHERE user_id IN ($sql_in) AND match_id = $data_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Could not delete memer', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['delete_match_user']
					. sprintf($lang['click_return_match'], '<a href="' . append_sid('admin_match.php') . '">', '</a>')
					. sprintf($lang['click_return_match_details'], '<a href="' . append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $data_id) . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'delete_match_user');
				message(GENERAL_MESSAGE, $message);
			
				break;
				
		#	case '_order':
		#	
		#		update(MATCH_MAPS, 'map', $move, $map_id);
		#		orders(MATCH_MAPS);
		#		
		#		log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'acp_mappic_order');
		#		
		#		$show_index = true;
		#		
		#		break;
			
			case '_delete':
			
				$data = get_data(MATCH, $data_id, 1);
				
				if ( $data_id && $confirm )
				{
	//				$sql = 'SELECT * FROM ' . MATCH . " WHERE match_id = $data_id";
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
				
					$sql = 'DELETE FROM ' . MATCH . " WHERE match_id = $data_id";
					if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . MATCH_COMMENTS . " WHERE match_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . MATCH_DETAILS . " WHERE match_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . MATCH_LINEUP . " WHERE match_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . MATCH_USERS . " WHERE match_id = $data_id";
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
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_MATCH_URL . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_match'], $data['match_rival']),
						
						'S_FIELDS'		=> $s_fields,
						'S_ACTION'		=> append_sid('admin_match.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_match']);
				}
			
				$template->pparse('body');
				
				break;
			
			default:
				
				message(GENERAL_ERROR, $lang['no_mode']);
				
				break;
		}
	
		if ( $show_index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_match.tpl'));
	$template->assign_block_vars('display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['match']),
		'L_CREATE'		=> sprintf($lang['sprintf_creates'], $lang['match']),
		'L_EXPLAIN'		=> $lang['match_explain'],
		
		'L_UPCOMING'	=> $lang['match_upcoming'],
		'L_EXPIRED'		=> $lang['match_expired'],
		'L_TRAINING'	=> $lang['training'],
		'L_DETAILS'		=> $lang['common_details'],
		
		'S_TEAMS'		=> select_box('team', 'selectsmall', 0, 0),
		
		'S_FIELDS'		=> $s_fields,
		'S_CREATE'		=> append_sid('admin_match.php?mode=_create'),
		'S_ACTION'		=> append_sid('admin_match.php'),
	));

	$sql = "SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
				FROM " . MATCH . " m
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . TRAINING . " tr ON m.match_id = tr.match_id
			ORDER BY m.match_date DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$match_data = $db->sql_fetchrowset($result); 
	
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
				$type = ( $match_new[$i]['match_public'] ) ? 'sprintf_match_name' : 'sprintf_match_intern';
				$team_id = $match_new[$i]['team_id'];
				$match_id = $match_new[$i]['match_id'];
				
				$template->assign_block_vars('display.row_match_new', array(
					'GAME'			=> display_gameicon($match_new[$i]['game_size'], $match_new[$i]['game_image']),
					'NAME'			=> sprintf($lang[$type], $match_new[$i]['match_rival']),
					'DATE'			=> create_date($userdata['user_dateformat'], $match_new[$i]['match_date'], $userdata['user_timezone']),
					
					'L_TRAINING'	=> ( !$match_new[$i]['training_id'] ) ? $lang['match_training_create'] : $lang['match_training_update'],
					'U_TRAINING'	=> ( !$match_new[$i]['training_id'] ) ? append_sid('admin_training.php?mode=_create&amp;' . POST_TEAMS_URL . '=' . $team_id .'&amp;' . POST_MATCH_URL . '=' . $match_id . '&amp;vs=' . $match_new[$i]['match_rival']) : append_sid('admin_training.php?mode=_list&amp;' . POST_TEAMS_URL . '=' . $team_id),
					
					'U_DETAILS'		=> append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_id),
					'U_UPDATE'		=> append_sid('admin_match.php?mode=_update&amp;' . POST_MATCH_URL . '=' . $match_id),
					'U_DELETE'		=> append_sid('admin_match.php?mode=_delete&amp;' . POST_MATCH_URL . '=' . $match_id),
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
				$type = ( $match_old[$i]['match_public'] ) ? 'sprintf_match_name' : 'sprintf_match_intern';
				$match_id = $match_old[$i]['match_id'];
				
				$template->assign_block_vars('display.row_match_old', array(
					'GAME'			=> display_gameicon($match_old[$i]['game_size'], $match_old[$i]['game_image']),
					'NAME'			=> sprintf($lang[$type], $match_old[$i]['match_rival']),
					'DATE'			=> create_date($userdata['user_dateformat'], $match_old[$i]['match_date'], $userdata['user_timezone']),
					
					'U_DETAILS'		=> append_sid('admin_match.php?mode=_details&amp;' . POST_MATCH_URL . '=' . $match_id),
					'U_UPDATE'		=> append_sid('admin_match.php?mode=_update&amp;' . POST_MATCH_URL . '=' . $match_id),
					'U_DELETE'		=> append_sid('admin_match.php?mode=_delete&amp;' . POST_MATCH_URL . '=' . $match_id),
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
			
	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>