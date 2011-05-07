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
	
	$path_dir	= $root_path . $settings['path_matchs'] . '/';
	
	$acp_main	= request('acp_main', 0);
	$acp_title	= sprintf($lang['sprintf_head'], $lang['match']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_match'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header && !$acp_main )	? redirect('admin/' . check_sid($file, true)) : false;
	( $header && $acp_main )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_match.tpl',
		'ajax'		=> 'style/inc_request.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->assign_block_vars('_input', array());
				$template->assign_block_vars('_input.' . $mode, array());
				
				$template->assign_vars(array('FILE' => 'ajax_listmaps'));
				$template->assign_var_from_handle('AJAX', 'ajax');
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$data = array(
								'team_id'				=> request('team_id', 0),
								'match_type'			=> 'type_unknown',
								'match_war'				=> 'war_fun',
								'match_league'			=> 'league_nope',
								'match_league_url'		=> '',
								'match_league_match'	=> '',
								'match_public'			=> '1',
								'match_comments'		=> $settings['comments_matches'],
								'match_rival_name'		=> '',
								'match_rival_tag'		=> '',
								'match_rival_url'		=> '',
								'match_rival_logo'		=> '',
								'match_rival_lineup'	=> '',
								'match_server_ip'		=> '',
								'match_server_pw'		=> '',
								'match_hltv_ip'			=> '',
								'match_hltv_pw'			=> '',
								'match_report'			=> '',
								'match_comment'			=> '',
								'match_date'			=> time(),
								'match_create'			=> time(),
							);
					
					$s_train = '0';
					$d_train = array(
								'training_date'			=> time(),
								'training_duration'		=> '',
								'training_maps'			=> '',
								'training_text'			=> '',
							);	
						
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(MATCH, $data_id, false, 1, 1);
					
					( $data['match_date'] > time() ) ? $template->assign_block_vars('_input._reset', array()) : false;
				}
				else
				{
					$data = array(
								'match_rival_name'		=> request('match_rival_name', 2),
								'match_rival'			=> strtolower(request('match_rival_name', 2)),
								'match_rival_tag'		=> request('match_rival_tag', 2),
								'match_rival_url'		=> request('match_rival_url', 5),
								'match_rival_logo'		=> request('match_rival_logo', 6),
								'match_rival_lineup'	=> request('match_rival_lineup', 2),
								'team_id'				=> request('team_id', 0),
								'match_type'			=> request('match_type', 1),
								'match_war'				=> request('match_war', 1),
								'match_league'			=> request('match_league', 1),
								'match_league_url'		=> request('match_league_url', 2),
								'match_league_match'	=> request('match_league_match', 2),
								'match_public'			=> request('match_public', 0),
								'match_comments'		=> request('match_comments', 0),
								'match_server_ip'		=> request('match_server_ip', 2),
								'match_server_pw'		=> request('match_server_pw', 2),
								'match_hltv_ip'			=> request('match_hltv_ip', 2),
								'match_hltv_pw'			=> request('match_hltv_pw', 2),
								'match_report'			=> request('match_report', 2),
								'match_comment'			=> request('match_comment', 2),
								'match_create'			=> request('match_create', 0),
								'match_date'			=> mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0)),
							);
							
					( $mode == '_update' && $data['match_date'] > time() ) ? $template->assign_block_vars('_input._reset', array()) : false;
					
					if ( $mode == '_create' )
					{
						$s_train = request('training', 0);
						$d_train = array(
									'training_date'			=> mktime(request('thour', 0), request('tmin', 0), 00, request('tmonth', 0), request('tday', 0), request('tyear', 0)),
									'training_duration'		=> mktime(request('thour', 0), request('tmin', 0) + request('dmin', 0), 00, request('tmonth', 0), request('tday', 0), request('tyear', 0)),
									'training_maps'			=> request('training_maps', 4),
									'training_text'			=> request('training_text', 2),
								);
								
						debug($d_train);
					}
				}
				
				foreach ( $lang['match_type'] as $key => $value )
				{
					$template->assign_block_vars('_input._type', array(
						'NAME'	=> $value,
						'TYPE'	=> $key,
						'MARK'	=> ( $key == $data['match_type'] ) ? 'checked="checked"' : '',
					));
				}
				
				foreach ( $lang['match_war'] as $key => $value )
				{
					$template->assign_block_vars('_input._war', array(
						'NAME'	=> $value,
						'TYPE'	=> $key,
						'MARK'	=> ( $key == $data['match_war'] ) ? 'checked="checked"' : '',
					));
				}
				
				foreach ( $lang['match_league'] as $key => $value )
				{
					$template->assign_block_vars('_input._league', array(
						'NAME'	=> $value['name'],
						'TYPE'	=> $key,
						'CLICK'	=> "set_site('match_league_url', '" . $value['url'] . "')",
						'MARK'	=> ( $key == $data['match_league'] ) ? 'checked="checked"' : '',
					));
				}
				
				if ( $data['team_id'] )
				{
					$sql = "SELECT mc.*
								FROM cms_maps_cat mc
									LEFT JOIN cms_teams t ON t.team_id = " . $data['team_id'] . "
									LEFT JOIN cms_game g ON t.team_game = g.game_id
							WHERE mc.cat_tag = g.game_tag";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$cats = $db->sql_fetchrow($result);
					$maps = $cats ? data(MAPS, " cat_id = " . $cats['cat_id'], 'map_order ASC', 1, false) : '';
					
					$s_maps = '';
					
					if ( $maps )
					{
						$s_maps .= "<div><div><select class=\"selectsmall\" name=\"training_maps[]\" id=\"training_maps\">";
						$s_maps .= "<option selected=\"selected\" value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . "</option>";
						
						$cat_id		= $cats['cat_id'];
						$cat_name	= $cats['cat_name'];
						
						$s_map = '';
						
						for ( $j = 0; $j < count($maps); $j++ )
						{
							$map_id		= $maps[$j]['map_id'];
							$map_cat	= $maps[$j]['cat_id'];
							$map_name	= $maps[$j]['map_name'];
	
							$s_map .= ( $cat_id == $map_cat ) ? "<option value=\"$map_id\">" . sprintf($lang['sprintf_select_format'], $map_name) . "</option>" : '';
						}
						
						$s_maps .= ( $s_map != '' ) ? "<optgroup label=\"$cat_name\">$s_map</optgroup>" : '';
						$s_maps .= "</select>&nbsp;<input type=\"button\" class=\"button2\" value=\"" . $lang['common_more'] . "\" onclick=\"clone(this)\"></div></div>";
					}
					else
					{
						$s_maps = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
					}
				}
				else
				{
					$s_maps = sprintf($lang['sprintf_select_format'], $lang['msg_select_team_first']);
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"match_create\" value=\"" . $data['match_create'] . "\" />";
				
				$template->assign_vars(array(
					'L_TITLE'		=> sprintf($lang['sprintf_head'], $lang['match']),
					'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['match'], $data['match_rival_name']),
					'L_DETAIL'		=> $lang['head_details'],
					'L_STANDARD'	=> $lang['head_standard'],
					'L_RIVAL'		=> $lang['head_rival'],
					'L_SERVER'		=> $lang['head_server'],
					'L_MESSAGE'		=> $lang['head_message'],
					'L_TRAINING'	=> $lang['head_training'],
					
					'L_TEAM'			=> $lang['team'],
					'L_TYPE'			=> $lang['type'],
					'L_WAR'				=> $lang['war'],
					'L_LEAGUE'			=> $lang['league'],
					'L_LEAGUE_URL'		=> $lang['league_url'],
					'L_LEAGUE_MATCH'	=> $lang['league_match'],
					'L_RIVAL_NAME'		=> $lang['rival_name'],
					'L_RIVAL_TAG'		=> $lang['rival_tag'],
					'L_RIVAL_URL'		=> $lang['rival_url'],
					'L_RIVAL_LOGO'		=> $lang['rival_logo'],
					'L_RIVAL_LINEUP'	=> $lang['rival_lineup'],
					'L_RIVAL_LINEUP_EXP'=> $lang['rival_lineup_exp'],
					'L_SERVER_IP'		=> $lang['server_ip'],
					'L_SERVER_PW'		=> $lang['server_pw'],
					'L_HLTV_IP'			=> $lang['hltv_ip'],
					'L_HLTV_PW'			=> $lang['hltv_pw'],
					
					'L_DATE'			=> $lang['common_date'],
					'L_PUBLIC'			=> $lang['common_public'],
					'L_COMMENTS'		=> $lang['common_comments_pub'],
					'L_COMMENT'			=> $lang['comment'],
					'L_COMMENT_EXP'		=> $lang['comment_exp'],
					'L_REPORT'			=> $lang['report'],
					'L_REPORT_EXP'		=> $lang['report_exp'],
					'L_RESET_LIST'		=> $lang['reset_list'],
					
					'L_TRAINING'		=> $lang['training'],
					'L_TRAINING_DATE'	=> $lang['train_date'],
					'L_TRAINING_MAPS'	=> $lang['train_maps'],
					'L_TRAINING_TEXT'	=> $lang['train_text'],
				
					'LEAGUE_URL'		=> $data['match_league_url'],
					'LEAGUE_MATCH'		=> $data['match_league_match'],
					
					'RIVAL_NAME'		=> $data['match_rival_name'],
					'RIVAL_TAG'			=> $data['match_rival_tag'],
					'RIVAL_URL'			=> $data['match_rival_url'],
					'RIVAL_LOGO'		=> $data['match_rival_logo'],
					'RIVAL_LINEUP'		=> $data['match_rival_lineup'],
					
					'SERVER_IP'			=> $data['match_server_ip'],
					'SERVER_PW'			=> $data['match_server_pw'],
					'HLTV_IP'			=> $data['match_hltv_ip'],
					'HLTV_PW'			=> $data['match_hltv_pw'],
					
					'MATCH_REPORT'		=> $data['match_report'],
					'MATCH_COMMENT'		=> $data['match_comment'],
					
					'TRAINING_MAPS'		=> ( $mode == '_create' ) ? $d_train['training_maps'] : '',
					'TRAINING_TEXT'		=> ( $mode == '_create' ) ? $d_train['training_text'] : '',
					
					'S_TEAM'			=> select_team('select', 'team', 'request', 'team_id', $data['team_id']),
					'S_MAPS'			=> $s_maps,
					
					'S_DAY'				=> select_date('selectsmall', 'day', 'day',		date('d', $data['match_date']), $data['match_create']),
					'S_MONTH'			=> select_date('selectsmall', 'month', 'month',	date('m', $data['match_date']), $data['match_create']),
					'S_YEAR'			=> select_date('selectsmall', 'year', 'year',	date('Y', $data['match_date']), $data['match_create']),
					'S_HOUR'			=> select_date('selectsmall', 'hour', 'hour',	date('H', $data['match_date']), $data['match_create']),
					'S_MIN'				=> select_date('selectsmall', 'min', 'min',		date('i', $data['match_date']), $data['match_create']),
					
					'S_PUBLIC_YES'		=> ( $data['match_public'] ) ? 'checked="checked"' : '',
					'S_PUBLIC_NO'		=> (!$data['match_public'] ) ? 'checked="checked"' : '',
					'S_COMMENT_YES'		=> ( $data['match_comments'] ) ? 'checked="checked"' : '',
					'S_COMMENT_NO'		=> (!$data['match_comments'] ) ? 'checked="checked"' : '',
					
					'S_TRAINING_YES'	=> ( $mode == '_create' ) ? ( $s_train ? 'checked="checked"' : '' ) : '',
					'S_TRAINING_NO'		=> ( $mode == '_create' ) ? (!$s_train ? 'checked="checked"' : '' ) : '',
					'S_TRAINING_NONE'	=> ( $mode == '_create' ) ? ( $s_train ? '' : 'none' ) : '',
					
					'S_TDAY'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'day', 'tday',		date('d', $d_train['training_date']), $data['match_create']) : '',
					'S_TMONTH'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'month', 'tmonth',	date('m', $d_train['training_date']), $data['match_create']) : '',
					'S_TYEAR'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'year', 'tyear',		date('Y', $d_train['training_date']), $data['match_create']) : '',
					'S_THOUR'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'hour', 'thour',		date('H', $d_train['training_date']), $data['match_create']) : '',
					'S_TMIN'			=> ( $mode == '_create' ) ? select_date('selectsmall', 'min', 'tmin',		date('i', $d_train['training_date']), $data['match_create']) : '',
					'S_TDURATION'		=> ( $mode == '_create' ) ? select_date('selectsmall', 'duration', 'dmin',	date('i', $d_train['training_date']), $data['match_create']) : '',
					
					'S_DETAIL'			=> check_sid("$file?mode=_detail&amp;$url=$data_id"),
					'S_ACTION'			=> check_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$min	= request('min', 0);
					$hour	= request('hour', 0);
					$day	= request('day', 0);
					$month	= request('month', 1);
					$year	= request('year', 0);
					$tday	= request('tday', 0);
					$tmonth	= request('tmonth', 0);
					$tyear	= request('tyear', 0);
					$reset	= request('listdel', 0);
					
					$error .= !$data['team_id']				? ( $error ? '<br />' : '' ) . $lang['msg_select_team'] : '';
					$error .= !$data['match_type']			? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
					$error .= !$data['match_war']			? ( $error ? '<br />' : '' ) . $lang['msg_select_war'] : '';
					$error .= !$data['match_league']		? ( $error ? '<br />' : '' ) . $lang['msg_select_league'] : '';
					$error .= !$data['match_rival_name']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_rival_name'] : '';
					$error .= !$data['match_rival_tag']		? ( $error ? '<br />' : '' ) . $lang['msg_empty_rival_tag'] : '';
					$error .= !$data['match_server_ip']		? ( $error ? '<br />' : '' ) . $lang['msg_empty_server'] : '';
					
					$error .= !checkdate($month, $day, $year) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
					$error .= (	$mode == '_create' && time() >= $data['match_date'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
					
					if ( $mode == '_create' && $s_train == 1 )
					{
						$error .= ( !checkdate($tmonth, $tday, $tyear) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
						$error .= ( time() >= mktime($hour, $min, 00, $tmonth, $tday, $tyear)) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
					}
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$data['match_path'] = create_folder($path_dir, $day . $month . $year . '_', true);
							
							$db_data = sql(MATCH, $mode, $data);
							
							if ( $s_train )
							{
								$d_train['team_id']			= $data['team_id'];
								$d_train['match_id']		= $db->sql_nextid();								
								$d_train['training_vs']		= $data['match_rival_name'];
								$d_train['training_create']	= $data['match_create'];
								
								$db_train = sql(TRAINING, $mode, $d_train);
							}
							
							$message = $lang['create']
								. sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$db_data = sql(MATCH, $mode, $data, 'match_id', $data_id);
							
							if ( $reset )
							{
								sql(MATCH_USERS, 'delete', false, 'match_id', $data_id);
							}
							
							$message = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));;
						}
							
					#	$monat = request('month', 0);
					#	$oCache -> sCachePath = './../cache/';
					#	$oCache -> deleteCache('match_list_open_member');
					#	$oCache -> deleteCache('match_list_open_guest');
					#	$oCache -> deleteCache('match_list_close_member');
					#	$oCache -> deleteCache('match_list_close_guest');
					#	$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
					#	$oCache -> deleteCache('calendar_' . $monat . '_match_member');
					#	$oCache -> deleteCache('calendar_' . $monat . '_guest');
					#	$oCache -> deleteCache('calendar_' . $monat . '_member');
					#	$oCache -> deleteCache('display_subnavi_matchs_guest');
					#	$oCache -> deleteCache('display_subnavi_matchs_member');
	
						log_add(LOG_ADMIN, $log, $mode, $db_data);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
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
					
				#	monat = date("m", time());
				#	$oCache -> sCachePath = './../cache/';
				#	$oCache -> deleteCache('calendar_' . $monat . '_match_guest');
				#	$oCache -> deleteCache('calendar_' . $monat . '_match_member');
				#	$oCache -> deleteCache('calendar_' . $monat . '_member');
				#	$oCache -> deleteCache('display_subnavi_matchs_guest');
				#	$oCache -> deleteCache('display_subnavi_matchs_member');
					
					$message = $lang['delete_match'] . sprintf($lang['click_return_match'], '<a href="' . check_sid($file));
					log_add(LOG_ADMIN, $log, 'delete_match');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
					$fields .= "<input type=\"hidden\" name=\"acp_main\" value=\"$acp_main\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['match_rival_name']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['match'])); }
			
				$template->pparse('confirm');
				
				break;
			
			case '_detail':
			
				$template->assign_block_vars('_detail', array());
				
				$s_options = $s_team_users = '';
				
				if ( $order )
				{
					update(MATCH_MAPS, 'map', $move, $data_map);
					orders(MATCH_MAPS);
					
					log_add(LOG_ADMIN, $log, '_order_maps');
				}
				
				$sql = "SELECT	m.*, t.team_id, t.team_name, g.game_image, g.game_tag
							FROM " . MATCH . " m
								LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE m.match_id = $data_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$detail = $db->sql_fetchrow($result);
				
				$sql = "SELECT u.user_id, u.user_name
							FROM " . USERS . " u, " . TEAMS_USERS . " tu
						WHERE tu.team_id = " . $detail['team_id'] . " AND tu.user_id = u.user_id
						ORDER BY u.user_name";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$team_users = $db->sql_fetchrowset($result);
				
				$sql = "SELECT u.user_id, u.user_name, ml.status
							FROM " . MATCH_LINEUP . " ml, " . USERS . " u
						WHERE ml.match_id = $data_id AND ml.user_id = u.user_id
						ORDER BY ml.status";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$list_users = $db->sql_fetchrowset($result);
				
				if ( $team_users || $list_users )
				{
					if ( $team_users )
					{
						$template->assign_block_vars('_detail._team_users', array());
						
						$s_team_users = "<select class=\"select\" name=\"members[]\" size=\"6\" multiple=\"multiple\">";
						
						for ( $i = 0; $i < count($team_users); $i++ )
						{
							$s_team_users .= "<option value=\"" . $team_users[$i]['user_id'] . "\">" . sprintf($lang['sprintf_select_format'], $team_users[$i]['user_name']) . "</option>";
						}
						
						$s_team_users .= "</select>";
					}
					
					if ( $list_users )
					{
						$template->assign_block_vars('_detail._list_users', array());
						
						for ( $i = 0; $i < count($list_users); $i++ )
						{
							$template->assign_block_vars('_detail._list_users._member_row', array(
								'USER_ID'	=> $list_users[$i]['user_id'],
								'USERNAME'	=> $list_users[$i]['user_name'],
								'STATUS'	=> ( !$list_users[$i]['status'] ) ? $lang['status_player'] : $lang['status_replace'],
							));
						}
						
						$s_options .= "<select class=\"postselect\" name=\"smode\">";
						$s_options .= "<option value=\"option\">" . sprintf($lang['sprintf_select_format'], $lang['common_option_select']) . "</option>";
						$s_options .= "<option value=\"_user_player\">" . sprintf($lang['sprintf_select_format'], sprintf($lang['status_set'], $lang['status_player'])) . "</option>";
						$s_options .= "<option value=\"_user_replace\">" . sprintf($lang['sprintf_select_format'], sprintf($lang['status_set'], $lang['status_replace'])) . "</option>";
						$s_options .= "<option value=\"_user_delete\">" . sprintf($lang['sprintf_select_format'], $lang['common_delete']) . "</option>";
						$s_options .= "</select>";
					}
					else
					{
						$template->assign_block_vars('_detail._no_list_users', array());
					}
				}
				else
				{
					$template->assign_block_vars('_detail._entry_empty', array());
					
					$s_team_users = $lang['no_users'];
				}
				
				$max = maxi(MATCH_MAPS, 'map_order', 'match_id = ' . $data_id);
				$match_maps = data(MATCH_MAPS, $data_id, 'map_order', 1, false);
				
				if ( $match_maps )
				{
					$template->assign_block_vars('_detail._maps', array());
					
					for ( $i = 0; $i < count($match_maps); $i++ )
					{
						$map_id = $match_maps[$i]['map_id'];
						
						$template->assign_block_vars('_detail._maps._map_row', array(
							'MAP_ID'	=> $map_id,
							'MAP_HOME'	=> $match_maps[$i]['map_points_home'],
							'MAP_RIVAL'	=> $match_maps[$i]['map_points_rival'],
							
							'PIC_URL'	=> '<a href="' . $path_dir . $detail['match_path'] . '/' . $match_maps[$i]['map_picture'] . '" rel="lightbox"><img src="' . $path_dir . $detail['match_path'] . '/' . $match_maps[$i]['map_preview'] . '" alt="" /></a>',
							
							'MOVE_UP'	=> ( $match_maps[$i]['map_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_detail&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_pic=$map_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
							'MOVE_DOWN'	=> ( $match_maps[$i]['map_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_detail&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_pic=$map_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
							
							'S_MAP'		=> select_map($detail['team_id'], $match_maps[$i]['map_name']),
						));
						
						( $match_maps[$i]['map_picture'] ) ? $template->assign_block_vars('_detail._maps._map_row._delete', array()) : '';
					}
				}
				
				$cats = data(MAPS_CAT, " cat_tag = '" . $detail['game_tag'] . "'", 'cat_order ASC', 1, true);
				$maps = $cats ? data(MAPS, " cat_id = " . $cats['cat_id'], 'map_order ASC', 1, false) : '';
				
				$s_maps = '';
				
				if ( $maps )
				{
					$s_maps .= "<select class=\"selectsmall\" name=\"map_name[]\" id=\"map_name\">";
					$s_maps .= "<option selected=\"selected\" value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . "</option>";
					
					$cat_id		= $cats['cat_id'];
					$cat_name	= $cats['cat_name'];
					
					$s_map = '';
					
					for ( $j = 0; $j < count($maps); $j++ )
					{
						$map_id		= $maps[$j]['map_id'];
						$map_cat	= $maps[$j]['cat_id'];
						$map_name	= $maps[$j]['map_name'];

						$s_map .= ( $cat_id == $map_cat ) ? "<option value=\"$map_id\">" . sprintf($lang['sprintf_select_format'], $map_name) . "</option>" : '';
					}
					
					$s_maps .= ( $s_map != '' ) ? "<optgroup label=\"$cat_name\">$s_map</optgroup>" : '';
					$s_maps .= "</select>";
				}
				else
				{
					$s_maps = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
			#		'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['match']),
			#		'L_INPUT'			=> sprintf($lang['sprintf_update'], $lang['match'], $detail['match_rival_name']),
			#		'L_DETAIL'			=> $lang['match_details'],
			#		'L_EXPLAIN'			=> $lang['match_details_explain'],
					
			
					'L_LINEUP'			=> $lang['lineup'],
					'L_LINEUP_ADD'		=> $lang['lineup_add'],
					'L_LINEUP_ADD_EXP'	=> $lang['lineup_add_exp'],
					'L_LINEUP_PLAYER'	=> $lang['status_player'],
					'L_LINEUP_REPLACE'	=> $lang['status_replace'],
					'L_LINEUP_STATUS'	=> $lang['lineup_status'],
					
					'L_NO_MEMBER'	=> $lang['no_users'],
					'L_NO_STORE'	=> $lang['no_users_store'],
					
			#		'L_MAPS'			=> $lang['details_maps'],
			#		'L_MAPS_PIC'		=> $lang['details_maps_pic'],
			#		'L_MAPS_OVERVIEW'	=> $lang['details_maps_overview'],
		
				
					
					
					
					
			#		'L_DETAIL_MAP'		=> $lang['details_map'],
			#		'L_DETAIL_MAPPIC'	=> $lang['details_mappic'],
			#		'L_DETAIL_POINTS'	=> $lang['details_points'],
					
			#		'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
					
				#	
				#	'L_LINEUP_PLAYER'	=> $lang['status_player'],
				#	'L_LINEUP_REPLACE'	=> $lang['status_replace'],
				#	
					
				#	'L_LINEUP_PLAYER'	=> $lang['lineup_player'],
				#	
				
					'L_ADD'				=> $lang['common_add'],
					
					'S_MAP'				=> $s_maps,
					
					'S_USERS'		=> $s_team_users,
					
					
					'S_OPTIONS'			=> $s_options,
					'S_INPUT'			=> check_sid("$file?mode=_update&amp;$url=$data_id"),
					
					'S_ACTION'			=> check_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				if ( $smode == '_details_map' || $smode == '_detail_mappic' || $smode == '_details_update' )
				{
				#	$max_row	= get_data_max(MATCH_MAPS, 'map_order', 'match_id = ' . $data_id);
				#	$next_order = ( !$max_row ) ? 10 : $max_row['max'] + 10;
						
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
								
								$message = $lang['update_match'] . sprintf($lang['click_return_match_details'], '<a href="' . check_sid("$file?mode=_details&amp;$url=$data_id"));
								log_add(LOG_ADMIN, $log, 'update_match_map');
							}
							else
							{
								$message = $lang['msg_select_match_map'] . sprintf($lang['click_return_match_details'], '<a href="' . check_sid("$file?mode=_details&amp;$url=$data_id"));
							}
						}
						else
						{
							$message = $lang['msg_select_match_map'] . sprintf($lang['click_return_match_details'], '<a href="' . check_sid("$file?mode=_details&amp;$url=$data_id"));
							message(GENERAL_ERROR, $message);
						}
					}
					else if ( $smode == '_detail_mappic' )
					{
						$map_name	= request('map_name', 4);
						$map_home	= request('map_points_home', 4);
						$map_rival	= request('map_points_rival', 4);
						$map_file	= request_files('ufile');
						$next_order = maxa(MATCH_MAPS, 'map_order', "match_id = $data_id");
						
						if ( $map_name )
						{
							for ( $i = 0; $i < count($map_name); $i++ )
							{
								if ( $map_name[$i] )
								{
									if ( $map_file['temp'][$i] )
									{
										$pic_ary[$map_name[$i]] = image_upload('', 'image_match', '', '1', '', '', $path_dir . $detail['match_path'], $map_file['temp'][$i], $map_file['name'][$i], $map_file['size'][$i], $map_file['type'][$i], $error);
									}
								}
							}
							
							$map_ary = '';
							
							for ( $i = 0; $i < count($map_name); $i++ )
							{
								if ( $map_name[$i] )
								{
									$map_ary[] = array(
										'match_id'			=> $data_id,
										'map_name'			=> $map_name[$i],
										'map_points_home'	=> $map_home[$i],
										'map_points_rival'	=> $map_rival[$i],
										'map_picture'		=> isset($pic_ary[$map_name[$i]]['pic_filename']) ? $pic_ary[$map_name[$i]]['pic_filename'] : '',
										'map_preview'		=> isset($pic_ary[$map_name[$i]]['pic_preview']) ? $pic_ary[$map_name[$i]]['pic_preview'] : '',
										'upload_user'		=> $userdata['user_id'],
										'upload_time'		=> time(),
										'map_order'			=> $next_order,
									);
									$next_order += 10;
								}
							}
							
							if ( $map_ary )
							{
								for ( $i = 0; $i < count($map_ary); $i++ )
								{
									sql(MATCH_MAPS, 'create', $map_ary[$i]);
								}
							}
							else
							{
								$error .= ( $error ? '<br />' : '' ) . $lang['msg_select_map'];
							}
							
							if ( !$error )
							{
								$message = $lang['create_map'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
										
								log_add(LOG_ADMIN, $log, $smode);
								message(GENERAL_MESSAGE, $message);
							}
							else
							{
								log_add(LOG_ADMIN, $log, $mode, $error);
						
								$template->assign_vars(array('ERROR_MESSAGE' => $error));
								$template->assign_var_from_handle('ERROR_BOX_UPLOAD', 'error');
							}
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
				#	log_add(LOG_ADMIN, $log, 'update_match');
				#	message(GENERAL_MESSAGE, $message);
				}
				
				if ( $smode == '_user_add' || $smode == '_user_player' || $smode == '_user_replace' || $smode == '_user_delete' || $smode == 'option' )
				{
					$status		= ( $smode == '_user_add' ) ? request('status', 0) : ( $smode == '_user_player' ? '0' : '1' );
					$members	= request('members', 4);
					
					$error .= ( !$members ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_member'] : '';
					$error .= ( $smode == 'option' ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_option'] : '';
					
					if ( !$error )
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
								$error = ( $error ? '<br />' : '' ) . $lang['msg_selected_member'];
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
									$error = ( $error ? '<br />' : '' ) . $lang['msg_selected_member'];
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
							
							$lang_type = 'create_user';
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
							
							$lang_type = 'update_user';
						}
						else if ( $smode == '_user_delete' )
						{
							$sql_in = implode(', ', $members);
				
							$sql = "DELETE FROM " . MATCH_LINEUP . " WHERE user_id IN ($sql_in) AND match_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$lang_type = 'delete_user';
						}
						
						if ( $error )
						{
							log_add(LOG_ADMIN, $log, $smode, $error);
							
							$template->assign_vars(array('ERROR_MESSAGE' => $error));
							$template->assign_var_from_handle('ERROR_BOX_PLAYER', 'error');
						}
						else
						{
							$message = $lang[$lang_type]
								. sprintf($lang['return'], check_sid($file), $acp_title)
								. sprintf($lang['return_update'], '<a href="' . check_sid("$file?mode=$mode&amp;$url=$data_id"));
						
							log_add(LOG_ADMIN, $log, $smode, $lang_type);
							message(GENERAL_MESSAGE, $message);
						}
					}
					else
					{
						log_add(LOG_ADMIN, $log, $smode, $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX_PLAYER', 'error');
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
				
				$message = $lang['upload_match'] . sprintf($lang['click_return_match_details'], '<a href="' . check_sid("$file?mode=_detail&amp;$url=$data_id"));
				log_add(LOG_ADMIN, $log, 'update_match_upload');
				message(GENERAL_MESSAGE, $message);
	
				break;
	
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	} // if end
	
	$template->assign_block_vars('_display', array());
	
	$select_id = ( $data_team > 0 ) ? "WHERE m.team_id = $data_team" : '';

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
	$data = $db->sql_fetchrowset($result);
	
	$data_new = $data_old = '';
	
	if ( $data )
	{
		foreach ( $data as $match => $row )
		{
			if ( $row['match_date'] > time() )
			{
				$data_new[] = $row;
			}
			else if ( $row['match_date'] < time() )
			{
				$data_old[] = $row;
			}
		}
		
		if ( !$data_new )
		{
			$template->assign_block_vars('_display._entry_empty_new', array());
		}
		else
		{
			for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_new)); $i++ )
			{
				$team_id		= $data_new[$i]['team_id'];
				$match_id		= $data_new[$i]['match_id'];
				$match_rival	= $data_new[$i]['match_rival_name'];
				$public			= $data_new[$i]['match_public'] ? 'sprintf_match_name' : 'sprintf_match_intern';
				
				$template->assign_block_vars('_display._match_new_row', array(
					'NAME'		=> sprintf($lang[$public], $match_rival),
					'GAME'		=> display_gameicon($data_new[$i]['game_size'], $data_new[$i]['game_image']),
					'DATE'		=> create_date($userdata['user_dateformat'], $data_new[$i]['match_date'], $userdata['user_timezone']),
					
					'L_TRAIN'	=> !$data_new[$i]['training_id'] ? $lang['training_create'] : $lang['training_update'],
					'U_TRAIN'	=> !$data_new[$i]['training_id'] ? check_sid("admin_training.php?mode=_create&amp;$url_team=$team_id&amp;$url=$match_id&amp;vs=$match_rival") : check_sid("admin_training.php?mode=_list&amp;$url_team=$team_id"),
					
					'U_DETAIL'	=> check_sid("$file?mode=_detail&amp;$url=$match_id"),
					'U_UPDATE'	=> check_sid("$file?mode=_update&amp;$url=$match_id"),
					'U_DELETE'	=> check_sid("$file?mode=_delete&amp;$url=$match_id"),
				));
			}
		}
		
		
		if ( !$data_old )
		{
			$template->assign_block_vars('_display._entry_empty_old', array());
		}
		else
		{
			for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_old)); $i++ )
			{
				$match_id	= $data_old[$i]['match_id'];
				$public		= $data_old[$i]['match_public'] ? 'sprintf_match_name' : 'sprintf_match_intern';
				
				$template->assign_block_vars('_display._match_old_row', array(
					'NAME'		=> sprintf($lang[$public], $data_old[$i]['match_rival_name']),
					'GAME'		=> display_gameicon($data_old[$i]['game_size'], $data_old[$i]['game_image']),
					'DATE'		=> create_date($userdata['user_dateformat'], $data_old[$i]['match_date'], $userdata['user_timezone']),
					
					'U_DETAIL'	=> check_sid("$file?mode=_detail&amp;$url=$match_id"),
					'U_UPDATE'	=> check_sid("$file?mode=_update&amp;$url=$match_id"),
					'U_DELETE'	=> check_sid("$file?mode=_delete&amp;$url=$match_id"),
				));
			}
		}
	}
	else
	{
		$template->assign_block_vars('_display._entry_empty_new', array());
		$template->assign_block_vars('_display._entry_empty_old', array());
	}
	
	$current_page = !count($data) ? 1 : ceil( count($data) / $settings['site_entry_per_page'] );
	
	$fields = '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['match']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_creates'], $lang['match']),
		'L_EXPLAIN'		=> $lang['explain'],
		
		'L_TRAINING'	=> $lang['training'],
		'L_UPCOMING'	=> $lang['upcoming'],
		'L_EXPIRED'		=> $lang['expired'],
		'L_DETAILS'		=> $lang['common_details'],
		
		'PAGE_NUMBER'	=> count($data) ? sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ) : '',
		'PAGE_PAGING'	=> count($data) ? generate_pagination('admin_match.php?', count($data), $settings['site_entry_per_page'], $start ) : '',
		
		'S_SORT'		=> select_team('selectsmall', 'sort_team', 'submit', $url_team, $data_team),
		'S_TEAM'		=> select_team('selectsmall', 'team', false, 'team_id', false),
		
		'S_CREATE'		=> check_sid("$file?mode=_create"),
		'S_ACTION'		=> check_sid($file),
		'S_FIELDS'		=> $fields,
	));
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>