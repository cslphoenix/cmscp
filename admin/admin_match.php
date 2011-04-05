<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_match'] )
	{
		$module['_headmenu_05_teams']['_submenu_match'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_match';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('match');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_MATCH;
	$file	= basename(__FILE__);
	
	$url		= POST_MATCH_URL;
	$url_pic	= POST_MATCH_PIC_URL;
	$url_team	= POST_TEAMS_URL;
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_map	= request($url_pic, 0);
	$data_team	= request($url_team, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$order		= request('order', 0);
	$smode		= request('smode', 1);
	$index		= request('index', 1);
	
	$path_dir	= $root_path . $settings['path_matchs'] . '/';
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_match'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header && !$index )		? redirect('admin/' . append_sid($file, true)) : false;
	( $header && $index == 1 )	? redirect('admin/' . append_sid('index.php', true)) : false;
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_match.tpl'));
				$template->assign_block_vars('_input', array());
				$template->assign_block_vars('_input.' . $mode, array());
				
				debug($_POST);
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$data = array(
								'team_id'				=> request('team_id', 0),
								'match_type'			=> 'type_unknown',
								'match_category'		=> '0',
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
								'match_create'			=> time(),
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(MATCH, $data_id, false, 1, 1);
					
					( $data['match_date'] > time() ) ? $template->assign_block_vars('_input._reset', array()) : false;
				}
				else
				{
					$match_date	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
					$train_date	= ( $mode == '_create' ) ? mktime(request('thour', 0), request('tmin', 0), 00, request('tmonth', 0), request('tday', 0), request('tyear', 0)) : '';
					$train_dura	= ( $mode == '_create' ) ? mktime(request('thour', 0), request('tmin', 0) + request('dmin', 0), 00, request('tmonth', 0), request('tday', 0), request('tyear', 0)) : '';
					
					( $mode == '_update' && $match_date > time() ) ? $template->assign_block_vars('_input._reset', array()) : false;
					
					$data = array(
						'team_id'				=> request('team_id', 0),
						'match_type'			=> request('match_type', 2),
						'match_category'		=> request('match_category', 0),
						'match_public'			=> request('match_public', 0),
						'match_comments'		=> request('match_comments', 0),
						'match_date'			=> $match_date,
						'match_league'			=> request('match_league', 2),
						'match_league_url'		=> request('match_league_url', 2),
						'match_league_match'	=> request('teamatch_league_matchm_id', 2),
						'match_rival'			=> request('match_rival', 2),
						'match_rival_tag'		=> request('match_rival_tag', 2),
						'match_rival_url'		=> request('match_rival_url', 2),
						'match_rival_lineup'	=> request('match_rival_lineup', 2),
						'server_ip'				=> request('server_ip', 2),
						'server_pw'				=> request('server_pw', 2),
						'server_hltv'			=> request('server_hltv', 2),
						'server_hltv_pw'		=> request('server_hltv_pw', 2),
						'training'				=> ( $mode == '_create' ) ? request('training', 0) : '',
						'training_date'			=> ( $mode == '_create' ) ? $train_date : '',
						'training_duration'		=> ( $mode == '_create' ) ? $train_dura : '',
						'training_maps'			=> ( $mode == '_create' ) ? request('training_maps', 2) : '',
						'training_text'			=> ( $mode == '_create' ) ? request('training_text', 2) : '',
						'match_create'			=> request('_create', 0),
					);
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			#	$fields .= '<input type="hidden" name="_create" value="' . $data['match_create'] . '" />';
				
				foreach ( $lang['match_type'] as $key => $value )
				{
					$template->assign_block_vars('_input._type', array(
						'NAME'	=> $value,
						'TYPE'	=> $key,
						'MARK'	=> ( $key == $data['match_type'] ) ? 'checked="checked"' : '',
					));
				}
				
				foreach ( $lang['match_category'] as $key => $value )
				{
					$template->assign_block_vars('_input._cat', array(
						'NAME'	=> $value,
						'TYPE'	=> $key,
						'MARK'	=> ( $key == $data['match_category'] ) ? 'checked="checked"' : '',
					));
				}
				
				$template->assign_vars(array(
					'L_TITLE'			=> sprintf($lang['sprintf_head'], $lang['match']),
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['match'], $data['match_rival']),
					'L_DETAIL'			=> $lang['match_details'],
					'L_INFO_STANDARD'	=> $lang['info_standard'],
					'L_INFO_RIVAL'		=> $lang['info_rival'],
					'L_INFO_SERVER'		=> $lang['info_server'],
					'L_INFO_MESSAGE'	=> $lang['info_message'],
					'L_INFO_TRAINING'	=> $lang['info_training'],
					
					'L_TEAM'			=> $lang['info_team'],
					'L_TYPE'			=> $lang['info_type'],
					'L_CATEGORY'		=> $lang['info_category'],
					'L_LEAGUE'			=> $lang['info_league'],
					'L_LEAGUE_URL'		=> $lang['info_league_url'],
					'L_LEAGUE_MATCH'	=> $lang['info_league_match'],
					'L_DATE'			=> $lang['info_date'],
					'L_PUBLIC'			=> $lang['info_public'],
					'L_COMMENTS'		=> sprintf($lang['sprintf_comments'], $lang['match']),
					'L_RIVAL'			=> $lang['info_rival'],
					'L_RIVAL_TAG'		=> $lang['info_rival_tag'],
					'L_RIVAL_URL'		=> $lang['info_rival_url'],
					'L_RIVAL_LINEUP'	=> $lang['info_rival_lineup'],
					'L_SERVER_IP'		=> $lang['info_server'],
					'L_SERVER_PW'		=> $lang['info_server_pw'],
					'L_HLTV'			=> $lang['info_hltv'],
					'L_HLTV_PW'			=> $lang['info_hltv_pw'],
					'L_COMMENT'			=> $lang['match_details_comment'],
					'L_REPORT'			=> $lang['match_text'],
					'L_TRAINING'		=> $lang['training'],
					'L_TRAINING_DATE'	=> $lang['training_date'],
					'L_TRAINING_MAPS'	=> $lang['training_maps'],
					'L_TRAINING_TEXT'	=> $lang['training_text'],
					'L_RESET_LIST'		=> $lang['match_interest_reset'],
					
					'RIVAL'				=> $data['match_rival'],
					'RIVAL_TAG'			=> $data['match_rival_tag'],
					'RIVAL_URL'			=> $data['match_rival_url'],
					'RIVAL_LINEUP'		=> $data['match_rival_lineup'],
					'LEAGUE_URL'		=> $data['match_league_url'],
					'LEAGUE_MATCH'		=> $data['match_league_match'],
					'SERVER_IP'			=> $data['server_ip'],
					'SERVER_PW'			=> $data['server_pw'],
					'SERVER_HLTV'		=> $data['server_hltv'],
					'SERVER_HLTV_PW'	=> $data['server_hltv_pw'],
					'TRAINING_MAPS'		=> ( $mode == '_create' ) ? $data['training_maps'] : '',
					'TRAINING_TEXT'		=> ( $mode == '_create' ) ? $data['training_text'] : '',
					
					'S_TEAMS'			=> select_box('team', 'select', $data['team_id'], 0),
					
					'S_TYPE'			=> select_lang_box('select_type_box', 'match_type', $data['match_type'], 'select'),
									
					'S_LEAGUE'			=> select_lang_box('select_league_box', 'match_league', $data['match_league'], 'select'),
					'S_CATEGORIE'		=> select_lang_box('select_categorie_box', 'match_category', $data['match_category'], 'select'),
					
					'S_DAY'				=> select_date('selectsmall', 'day', 'day',		date('d', $data['match_date']), $data['match_create']),
					'S_MONTH'			=> select_date('selectsmall', 'month', 'month',	date('m', $data['match_date']), $data['match_create']),
					'S_YEAR'			=> select_date('selectsmall', 'year', 'year',	date('Y', $data['match_date']), $data['match_create']),
					'S_HOUR'			=> select_date('selectsmall', 'hour', 'hour',	date('H', $data['match_date']), $data['match_create']),
					'S_MIN'				=> select_date('selectsmall', 'min', 'min',		date('i', $data['match_date']), $data['match_create']),
					
					'S_PUBLIC_YES'		=> ( $data['match_public'] ) ? ' checked="checked"' : '',
					'S_PUBLIC_NO'		=> (!$data['match_public'] ) ? ' checked="checked"' : '',
					'S_COMMENT_YES'		=> ( $data['match_comments'] ) ? ' checked="checked"' : '',
					'S_COMMENT_NO'		=> (!$data['match_comments'] ) ? ' checked="checked"' : '',
					
					'S_TRAINING_YES'	=> ( $mode == '_create' ) ? ( $data['training'] ? ' checked="checked"' : '' ) : '',
					'S_TRAINING_NO'		=> ( $mode == '_create' ) ? (!$data['training'] ? ' checked="checked"' : '' ) : '',
					'S_TRAINING_NONE'	=> ( $mode == '_create' ) ? (!$data['training'] ? 'none' : '' ) : '',
					
					'S_TDAY'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'day', 'tday',		date('d', $data['training_date']), $data['match_create']) : '',
					'S_TMONTH'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'month', 'tmonth',	date('m', $data['training_date']), $data['match_create']) : '',
					'S_TYEAR'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'year', 'tyear',		date('Y', $data['training_date']), $data['match_create']) : '',
					'S_THOUR'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'hour', 'thour',		date('H', $data['training_date']), $data['match_create']) : '',
					'S_TMIN'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'min', 'tmin',		date('i', $data['training_date']), $data['match_create']) : '',
					'S_TDURATION'		=> ( $mode == '_create' ) ? select_date('selectsmall', 'duration', 'dmin',	date('i', $data['training_date']), $data['match_create']) : '',
					
					'S_DETAIL'			=> append_sid("$file?mode=_detail&amp;$url=$data_id"),
					'S_ACTION'			=> append_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$team_id			= request('team_id', 0);
					$match_type			= request('match_type', 0);
					$match_category	= request('match_category', 0);
					$match_public		= request('match_public', 0);
					$match_comments		= request('match_comments', 0);
					$match_league		= request('match_league', 2);
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
					$match_create		= request('_create', 0);
					
					$error .= ( $team_id == '-1' )	? $lang['msg_select_team'] : '';
					$error .= ( !$match_type )		? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
					$error .= ( !$match_category ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_cat'] : '';
					$error .= ( !$match_league )	? ( $error ? '<br />' : '' ) . $lang['msg_select_league'] : '';
					$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
					$error .= (	$mode == '_create' &&  time() >= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0))) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
					$error .= ( !$match_rival )		? ( $error ? '<br />' : '' ) . $lang['msg_select_rival'] : '';
					$error .= ( !$match_rival_tag )	? ( $error ? '<br />' : '' ) . $lang['msg_select_rival_tag'] : '';
					$error .= ( !$server_ip )		? ( $error ? '<br />' : '' ) . $lang['msg_select_server'] : '';
					$error .= ( $mode == '_create' && $training && !checkdate(request('tmonth', 0), request('tday', 0), request('tyear', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
					$error .= ( $mode == '_create' && $training && time() >= mktime(request('hour', 0), request('min', 0), 00, request('tmonth', 0), request('tday', 0), request('tyear', 0))) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$match_date = mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
							
							$sql = "INSERT INTO " . MATCH . " (team_id, match_type, match_league, match_league_url, match_league_match, match_date, match_category, match_public, match_comments, match_rival, match_rival_tag, match_rival_url, server_ip, server_pw, server_hltv, server_hltv_pw, match_create)
										VALUES ('$team_id', '$match_type', '$match_league', '$match_league_url', '$match_league_match', '$match_date', '$match_category', '$match_public', '$match_comments', '$match_rival', '$match_rival_tag', '$match_rival_url', '$server_ip', '$server_pw', '$server_hltv', '$server_hltv_pw', '$match_create')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							if ( request('training', 1) )
							{
								$train_date	= mktime(request('thour'), request('tmin'), 00, request('tmonth'), request('tday'), request('tyear'));
								$train_dura	= mktime(request('thour'), request('tmin') + request('dmin', 0), 00, request('tmonth'), request('tday'), request('tyear'));
								
								$sql = "INSERT INTO " . TRAINING . " (training_vs, team_id, match_id, training_date, training_duration, training_create, training_maps, training_comment)
											VALUES ('$match_rival', '$team_id', '$data_id', '$train_date', '$train_dura', '$match_create', '$training_maps', '$training_comment')";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$message = $lang['create_match'] . sprintf($lang['click_return_match'], '<a href="' . append_sid($file) . '">', '</a>');
						}
						else
						{
							$match_date = mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
							if ( request('listdel', 1) )
							{
								$sql = "DELETE FROM " . MATCH_USERS . " WHERE match_id = $data_id";
								if ( !$db->sql_query($sql) )
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
										match_category		= '$match_category',
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
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_match']
								. sprintf($lang['click_return_match'], '<a href="' . append_sid($file) . '">', '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');;
						}
							
						$monat = request('month', 0);
						#$oCache -> sCachePath = './../cache/';
						#$oCache -> deleteCache('match_list_open_member');
						#$oCache -> deleteCache('match_list_open_guest');
						#$oCache -> deleteCache('match_list_close_member');
						#$oCache -> deleteCache('match_list_close_guest');
						#$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
						#$oCache -> deleteCache('calendar_' . $monat . '_match_member');
						#$oCache -> deleteCache('calendar_' . $monat . '_guest');
						#$oCache -> deleteCache('calendar_' . $monat . '_member');
						#$oCache -> deleteCache('display_subnavi_matchs_guest');
						#$oCache -> deleteCache('display_subnavi_matchs_member');
	
						log_add(LOG_ADMIN, $log, $mode, $match_rival);
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
			
			case '_delete':
			
				$data = data(MATCH, $data_id, '', 1, 1);
				
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
				#	#$oCache -> sCachePath = './../cache/';
				#	#$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
				#	#$oCache -> deleteCache('calendar_' . $monat . '_match_member');
				#	#$oCache -> deleteCache('calendar_' . $monat . '_member');
				#	#$oCache -> deleteCache('display_subnavi_matchs_guest');
				#	#$oCache -> deleteCache('display_subnavi_matchs_member');
					
					$message = $lang['delete_match'] . sprintf($lang['click_return_match'], '<a href="' . append_sid($file) . '">', '</a>');
					log_add(LOG_ADMIN, $log, 'delete_match');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= '<input type="hidden" name="index" value="' . $index . '" />';
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_match'], $data['match_rival']),
						
						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_match']);
				}
			
				$template->pparse('body');
				
				break;
			
			case '_detail':
			
				$template->set_filenames(array('body' => 'style/acp_match.tpl'));
				$template->assign_block_vars('_detail', array());
				
				if ( $order )
				{
					update(MATCH_MAPS, 'map', $move, $data_map);
					orders(MATCH_MAPS);
					
					log_add(LOG_ADMIN, $log, '_order_maps');
				}
				
				$sql = "SELECT	m.*, t.team_id, t.team_name, g.game_image 
							FROM " . MATCH . " m
								LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE m.match_id = $data_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$detail = $db->sql_fetchrow($result);
				
				$sql = "SELECT u.user_id, u.username, ml.status
							FROM " . MATCH_LINEUP . " ml, " . USERS . " u
						WHERE ml.match_id = $data_id AND ml.user_id = u.user_id
						ORDER BY ml.status";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$users = $db->sql_fetchrowset($result);
				
				if ( $users )
				{
					$template->assign_block_vars('_detail._option', array());
					
					for ( $i = 0; $i < count($users); $i++ )
					{
						$template->assign_block_vars('_detail._member_row', array(
							'USER_ID'	=> $users[$i]['user_id'],
							'USERNAME'	=> $users[$i]['username'],
							'STATUS'	=> ( !$users[$i]['status'] ) ? $lang['details_status_player'] : $lang['details_status_replace'],
						));
					}
				}
				else { $template->assign_block_vars('_detail._no_member', array()); }
				
				$sql = "SELECT u.user_id, u.username
							FROM " . USERS . " u, " . TEAMS_USERS . " tu
						WHERE tu.team_id = " . $detail['team_id'] . " AND tu.user_id = u.user_id
						ORDER BY u.username";
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
				$s_options = '<select class="postselect" name="smode">';
			#	$s_options .= '<option value="option">&raquo; ' . $lang['option_select'] . '</option>';
				$s_options .= '<option value="_user_player">&raquo; ' . sprintf($lang['details_status_set'], $lang['details_status_player']) . '&nbsp;</option>';
				$s_options .= '<option value="_user_replace">&raquo; ' . sprintf($lang['details_status_set'], $lang['details_status_replace']) . '&nbsp;</option>';
				$s_options .= '<option value="_user_delete">&raquo; ' . $lang['common_delete'] . '</option>';
				$s_options .= '</select>';
				
				/*
				 *	Map Upload Übersicht
				 */
				$max_order			= get_data_max(MATCH_MAPS, 'map_order', 'match_id = ' . $data_id);
				$match_maps_data	= get_data_array(MATCH_MAPS, 'match_id = ' . $data_id, 'map_order', 'ASC');
				
				if ( $match_maps_data )
				{
					$template->assign_block_vars('_detail.maps', array());
					
					for ( $i = 0; $i < count($match_maps_data); $i++ )
					{
						$map_id = $match_maps_data[$i]['map_id'];
						
						$template->assign_block_vars('_detail.maps.info_row', array(
							'INFO_MAP_ID'		=> $map_id,
																						  
							'INFO_MAP_NAME'		=> $match_maps_data[$i]['map_name'],
							'INFO_MAP_HOME'		=> $match_maps_data[$i]['map_points_home'],
							'INFO_MAP_RIVAL'	=> $match_maps_data[$i]['map_points_rival'],
							'INFO_PIC_URL'		=> '<a href="' . $path_dir . $match_maps_data[$i]['map_picture'] . '" rel="lightbox"><img src="' . $path_dir . $match_maps_data[$i]['map_preview'] . '" alt="" /></a>',
							
							'MOVE_UP'			=> ( $match_maps_data[$i]['map_order'] != '10' )				? '<a href="' . append_sid("$file?mode=_detail&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_pic=$map_id") .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
							'MOVE_DOWN'			=> ( $match_maps_data[$i]['map_order'] != $max_order['max'] )	? '<a href="' . append_sid("$file?mode=_detail&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_pic=$map_id") .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
	
						));
						
						if ( $match_maps_data[$i]['map_picture'] )
						{
							$template->assign_block_vars('_detail.maps.info_row.delete', array());
						}
					}
				}
				
				$fields .= '<input type="hidden" name="mode" value="_detail" />';
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['match']),
					'L_INPUT'			=> sprintf($lang['sprintf_update'], $lang['match'], $detail['match_rival']),
					'L_DETAIL'			=> $lang['match_details'],
					'L_EXPLAIN'			=> $lang['match_details_explain'],
					
					'L_MAPS'			=> $lang['details_maps'],
					'L_MAPS_PIC'		=> $lang['details_maps_pic'],
					'L_MAPS_OVERVIEW'	=> $lang['details_maps_overview'],
					
					'L_LINEUP'			=> $lang['details_lineup'],
					'L_LINEUP_PLAYER'	=> $lang['details_lineup_player'],
					'L_LINEUP_STATUS'	=> $lang['details_lineup_status'],
					
					'L_LINEUP_ADD_EX'	=> $lang['details_lineup_add_explain'],
					'L_LINEUP_PLAYER'	=> $lang['details_status_player'],
					'L_LINEUP_REPLACE'	=> $lang['details_status_replace'],
					'L_LINEUP_USERNAME'	=> $lang['username'],
					'L_DETAIL_MAP'		=> $lang['details_map'],
					'L_DETAIL_MAPPIC'	=> $lang['details_mappic'],
					'L_DETAIL_POINTS'	=> $lang['details_points'],
					
					'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
					
					'L_LINEUP_ADD'		=> $lang['details_lineup_add'],
					'L_LINEUP_PLAYER'	=> $lang['details_status_player'],
					'L_LINEUP_REPLACE'	=> $lang['details_status_replace'],
					'L_ADD'				=> $lang['common_add'],
					
					'S_MAP'				=> select_maps(),
					
					'S_SELECT'			=> $s_select,
					'S_OPTIONS'			=> $s_options,
					'S_INPUT'			=> append_sid("$file?mode=_update&amp;$url=$data_id"),
					
					'S_ACTION'			=> append_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				if ( $smode == '_details_map' || $smode == '_details_mappic' || $smode == '_details_update' )
				{
					$max_row	= get_data_max(MATCH_MAPS, 'map_order', 'match_id = ' . $data_id);
					$next_order = ( !$max_row ) ? 10 : $max_row['max'] + 10;
						
					if ( $smode == '_details_map' )
					{
						$map_name			= request('map_name', 4);
						$map_points_home	= request('map_points_home', 4);
						$map_points_rival	= request('map_points_rival', 4);
						
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
										'map_name'			=> "'" . $map_name[$j] . "'",
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
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								$message = $lang['update_match'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid("$file?mode=_details&amp;$url=$data_id") . '">', '</a>');
								log_add(LOG_ADMIN, $log, 'update_match_map');
							}
							else
							{
								$message = $lang['msg_select_match_map'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid("$file?mode=_details&amp;$url=$data_id") . '">', '</a>');
							}
						}
						else
						{
							$message = $lang['msg_select_match_map'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid("$file?mode=_details&amp;$url=$data_id") . '">', '</a>');
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
							
							$message = $lang['upload_match'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid("$file?mode=_details&amp;$url=$data_id") . '">', '</a>');
							log_add(LOG_ADMIN, $log, 'update_match_upload');
						}
						else
						{
							$message = $lang['msg_select_match_map'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid("$file?mode=_details&amp;$url=$data_id") . '">', '</a>');
							message(GENERAL_ERROR, $message);
						}
					}
				/*
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
				*/
					log_add(LOG_ADMIN, $log, 'update_match');
					message(GENERAL_MESSAGE, $message);
				}
				
				if ( $smode == '_user_add' || $smode == '_user_player' || $smode == '_user_replace' || $smode == '_user_delete' )
				{
					$error		= '';
					$status		= ( $smode == '_user_add' ) ? request('status', 0) : ( $smode == '_user_player' ? '0' : '1' );
					$members	= request('members', 4);
					
					if ( !$members )
					{
						$error = $lang['msg_select_no_members'];
					}
					else
					{
						if ( $smode == '_user_add' )
						{
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
								$error = $lang['match_lineup_no_users'];
							}
							else
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
									$error = $lang['match_lineup_no_users'];
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
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$lang_type = 'create_match_user';
						}
						else if ( $smode == '_user_player' || $smode == '_user_replace' )
						{
							for ( $i = 0; $i < count($members); $i++ )
							{
								$sql = "UPDATE " . MATCH_LINEUP . " SET status = $status WHERE match_id = $data_id AND user_id = " . $members[$i];
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$lang_type = 'update_match_user';
						}
						else if ( $smode == '_user_delete' )
						{
							$sql_in = implode(', ', $members);
				
							$sql = "DELETE FROM " . MATCH_LINEUP . " WHERE user_id IN ($sql_in) AND match_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'Could not delete memer', '', __LINE__, __FILE__, $sql);
							}
							
							$lang_type = 'delete_match_user';
						}
						
						$message = $lang[$lang_type]
							. sprintf($lang['click_return_match'], '<a href="' . append_sid($file) . '">', '</a>')
							. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=_details&amp;$url=$data_id") . '">', '</a>');
						log_add(LOG_ADMIN, $log, $lang_type);
						message(GENERAL_MESSAGE, $message);
					}
					
					if ( $error )
					{
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX_PLAYER', 'reg_header');
					}
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
				
				$message = $lang['upload_match'] . sprintf($lang['click_return_match_details'], '<a href="' . append_sid("$file?mode=_detail&amp;$url=$data_id") . '">', '</a>');
				log_add(LOG_ADMIN, $log, 'update_match_upload');
				message(GENERAL_MESSAGE, $message);
	
				break;
	
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_match.tpl'));
	$template->assign_block_vars('_display', array());
	
	$teams = get_data_array(TEAMS, '', 'team_order', '');
	
	$s_teams = '<select class="selectsmall" name="' . $url_team . '" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
	$s_teams .= '<option value="0">&raquo;&nbsp;' . $lang['msg_select_sort_team'] . '&nbsp;</option>';
	
	foreach ( $teams as $info => $value )
	{
		$selected = ( $value['team_id'] == $data_team ) ? 'selected="selected"' : '';
		$s_teams .= '<option value="' . $value['team_id'] . '" ' . $selected . '>&raquo;&nbsp;' . $value['team_name'] . '&nbsp;</option>';
	}
	$s_teams .= '</select>';
	
	$select_id = ( $data_team >= '1' ) ? "WHERE m.team_id = $data_team" : '';

	$sql = "SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
				FROM " . MATCH . " m
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . TRAINING . " tr ON m.match_id = tr.match_id
					$select_id
			ORDER BY m.match_date DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data_match = $db->sql_fetchrowset($result); 
	
	if ( $data_match )
	{
		$match_new = $match_old = array();
		
		foreach ( $data_match as $match => $row )
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
				$team_id		= $match_new[$i]['team_id'];
				$match_id		= $match_new[$i]['match_id'];
				$match_rival	= $match_new[$i]['match_rival'];
				$public			= ( $match_new[$i]['match_public'] ) ? 'sprintf_match_name' : 'sprintf_match_intern';
				
				$template->assign_block_vars('_display._match_new_row', array(
					'NAME'		=> sprintf($lang[$public], $match_new[$i]['match_rival']),
					'GAME'		=> display_gameicon($match_new[$i]['game_size'], $match_new[$i]['game_image']),
					'DATE'		=> create_date($userdata['user_dateformat'], $match_new[$i]['match_date'], $userdata['user_timezone']),
					
					'L_TRAIN'	=> ( !$match_new[$i]['training_id'] ) ? $lang['match_training_create'] : $lang['match_training_update'],
					'U_TRAIN'	=> ( !$match_new[$i]['training_id'] ) ? append_sid("admin_training.php?mode=_create&amp;$url_team=$team_id&amp;$url=$match_id&amp;vs=$match_rival") : append_sid("admin_training.php?mode=_list&amp;$url_team=$team_id"),
					
					'U_DETAIL'	=> append_sid("$file?mode=_detail&amp;$url=$match_id"),
					'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$match_id"),
					'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$match_id"),
				));
			}
		}
		else { $template->assign_block_vars('_display._no_entry_new', array()); }
		
		if ( $match_old )
		{
			for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($match_old)); $i++ )
			{
				$match_id	= $match_old[$i]['match_id'];
				$public		= ( $match_old[$i]['match_public'] ) ? 'sprintf_match_name' : 'sprintf_match_intern';
				
				
				$template->assign_block_vars('_display._match_old_row', array(
					'NAME'		=> sprintf($lang[$public], $match_old[$i]['match_rival']),
					'GAME'		=> display_gameicon($match_old[$i]['game_size'], $match_old[$i]['game_image']),
					'DATE'		=> create_date($userdata['user_dateformat'], $match_old[$i]['match_date'], $userdata['user_timezone']),
					
					'U_DETAIL'	=> append_sid("$file?mode=_detail&amp;$url=$match_id"),
					'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$match_id"),
					'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$match_id"),
				));
			}
		}
		else { $template->assign_block_vars('_display._no_entry_old', array()); }
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_new', array());
		$template->assign_block_vars('_display._no_entry_old', array());
	}

	$current_page = ( !count($data_match) ) ? 1 : ceil( count($data_match) / $settings['site_entry_per_page'] );
	
	$fields = '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['match']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_creates'], $lang['match']),
		'L_EXPLAIN'		=> $lang['match_explain'],
		
		'L_TRAINING'	=> $lang['training'],
		'L_UPCOMING'	=> $lang['match_upcoming'],
		'L_EXPIRED'		=> $lang['match_expired'],
		'L_DETAILS'		=> $lang['common_details'],
		
		'PAGE_NUMBER'	=> ( count($data_match) ) ? sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ) : '',
		'PAGE_PAGING'	=> ( count($data_match) ) ? generate_pagination('admin_match.php?', count($data_match), $settings['site_entry_per_page'], $start ) : '',
		
		'S_LIST'		=> $s_teams,
		'S_TEAMS'		=> select_box('team', 'selectsmall', 0, 0),
		
		'S_FIELDS'		=> $fields,
		'S_CREATE'		=> append_sid("$file?mode=_create"),
		'S_ACTION'		=> append_sid($file),
	));
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>