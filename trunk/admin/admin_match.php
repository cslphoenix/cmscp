<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_match',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_match'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
		
	$current = 'acp_match';
	
	include('./pagestart.php');
	
	add_lang('match');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$time	= time();
	$log	= SECTION_MATCH;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$sub	= request('sub', TYP);
	$subs	= request('subs', TYP);
	$mode	= request('mode', TYP);
	$smode	= request('smode', TXT);
	$sort	= request('sort', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$p_id	= request('p_id', INT);
	$t_id	= request('t_id', INT);
	
	$acp_main	= request('acp_main', INT);

	$dir_path	= $root_path . $settings['path_matchs']['path'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['match']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['a_match'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel && !$acp_main )	? redirect('admin/' . check_sid($file, true)) : false;
	( $cancel && $acp_main )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_match.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	debug($_POST, '_POST');
	
	$mode = (in_array($mode, array('create', 'update', 'detail', 'delete', 'sync'))) ? $mode : false;
	
	$mswitch = $settings['switch']['match'];
	$tswitch = $settings['switch']['training'];
	$comment = $settings['comments']['match'];
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				$template->assign_block_vars("input.$mode", array());
				
				$vars = array(
					'match' => array(
						'title1' => 'input_standard',
						'team_id'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:team',			'required' => 'select_team', 'params' => array('request', 'training_maps')),
						'match_type'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:match_type',	'required' => 'select_type'),
						'match_war'			=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:match_war',		'required' => 'select_war'),
						'match_league'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:match_league',	'required' => 'select_league'),
						'match_league_match'=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25'),
						'match_date'		=> array('validate' => INT,	'explain' => false,	'type' => 'drop:datetime',	'params' => ( $mode == 'create' ) ? $time : '-1'),
						'match_date'		=> array('validate' => ($mswitch ? INT : TXT), 'type' => ($mswitch ? 'drop:datetime' : 'text:25;25'), 'params' => ($mswitch ? (($mode == 'create') ? $time : '-1') : 'format')),
						'match_public'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						'match_comments'	=> ( $comment ) ? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
						
						'title2' => 'input_rival',
						'match_rival_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'ajax:25;25',	'required' => 'input_rival', 'params' => 'rival'),
						'match_rival_tag'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50', 'required' => 'input_clantag'),
						'match_rival_url'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
						'match_rival_logo'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
						'match_rival_lineup'=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
						
						'title3' => 'input_server',
						'match_server_ip'	=> array('validate' => TXT,	'explain' => false,	'type' => 'ajax:25;25', 'required' => 'input_server', 'params' => 'server'),
						'match_server_pw'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
						'match_hltv_ip'		=> array('validate' => TXT,	'explain' => false,	'type' => 'ajax:25;50', 'params' => 'server'),
						'match_hltv_pw'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
						
						'title4' => 'input_message',
						'match_report'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:30'),
						'match_comment'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:30'),
				
						'match_path'		=> 'hidden',
						'time_create'		=> 'hidden',
						'time_update'		=> 'hidden',
						
						'title5'			=> ( $mode == 'create' ) ? 'input_training' : 'hidden',
						'training_on'		=> ( $mode == 'create' ) ? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno',	'params' => array('type', false)) : 'hidden',
						'training_date'		=> ( $mode == 'create' ) ? array('validate' => ($tswitch ? INT : TXT), 'explain' => false,	'type' => ($tswitch ? 'drop:datetime' : 'text:25;25'), 'params' => ($tswitch ? $time : 'format'), 'prefix' => ($tswitch ? 't' : ''), 'divbox' => true, 'required' => array('select_date', 'training_on', 1)) : 'hidden',
						'training_duration'	=> ( $mode == 'create' ) ? array('validate' => INT,	'explain' => false,	'type' => 'drop:duration',	'divbox' => true, 'params' => 'training_date') : 'hidden',
						'training_maps'		=> ( $mode == 'create' ) ? array('validate' => ARY,	'explain' => false,	'type' => 'drop:maps',		'divbox' => true) : 'hidden',
						'training_text'		=> ( $mode == 'create' ) ? array('validate' => TXT,	'explain' => false,	'type' => 'textarea:30',	'divbox' => true) : 'hidden',
						
					#	'training_date'		=> ( $mode == 'create' ) ? array('validate' => INT, 'type' => 'drop:datetime',	'trid' => 'training_date', 'params' => ( $mode == 'create' ) ? $time : '-1', 'prefix' => 't') : 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$data_sql = array(
						'team_id'				=> request('team_id', 0),
						'match_type'			=> '',
						'match_war'				=> '',
						'match_league'			=> '',
						'match_league_match'	=> '',
						'match_public'			=> 0,
						'match_comments'		=> $settings['comments']['match'],
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
						'match_path'			=> '',
						'match_date'			=> $time,
						
						'time_create'			=> $time,
						'time_update'			=> 0,
						
						'training_on'			=> 0,
						'training_date'			=> $time,
						'training_duration'		=> 0,
						'training_maps'			=> 'a:0:{}',
						'training_text'			=> '',
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(MATCH, $data, false, 1, true);
					
					if ( $data['match_date'] > time() )
					{
						$template->assign_block_vars('input.reset', array());
					}
					
					$template->assign_vars(array('L_OPTION' => href('a_txt', $file, array('id' => $data), $lang['head_details'], $lang['head_details'])));
				}
				else
				{
					$data_sql = build_request(MATCH, $vars, $error, $mode, false, array('training_on', 'training_date', 'training_duration', 'training_maps', 'training_text'));
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$data['match_path'] = create_folder($dir_path, sprintf('%d%d%d_', request('day', INT), request('month', INT), request('year', INT)), true);
							
							foreach ( $data as $key => $value )
							{
								if ( in_array($key, array('training_date', 'training_duration', 'training_maps', 'training_text')) )
								{
									$train[$key] = $value;
								}
								else if ( $key == 'training_on' )
								{
									$settrain = true;
								}
								else
								{
									$match[$key] = $value;
								}
							}
							
							$sql = sql(MATCH, $mode, $match);
							
							$train['team_id']			= $match['team_id'];
							$train['match_id']			= $db->sql_nextid();								
							$train['training_vs']		= $match['match_rival_name'];
							$train['training_create']	= $match['match_create'];
							
							sql(TRAINING, $mode, $train);
							/*
							if ( $s_train )
							{
								$d_train['team_id']			= $data['team_id'];
								$d_train['match_id']		= $db->sql_nextid();								
								$d_train['training_vs']		= $data['match_rival_name'];
								$d_train['training_create']	= $data['match_create'];
								
								$db_train = sql(TRAINING, $mode, $d_train);
							}
							*/
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(MATCH, $mode, $data_sql, 'match_id', $data);
							
						#	if ( isset($reset) ) { sql(MATCH_USERS, 'delete', false, 'match_id', $data); }
							
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
			#	debug($data_sql, 'data_sql');
				
				build_output(MATCH, $vars, $data_sql);
				
			#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_' . $mode], $lang['title'], $data_sql['match_rival_name']),
					'L_EXPLAIN'	=> $lang['common_required'],
					
				#	'L_TITLE'	=> sprintf($lang['sprintf_head'], $lang['title']),
				#	'L_INPUT'	=> sprintf($lang['sprintf_' . $mode], $lang['title'], $data_sql['match_rival_name']),
				#	'L_DETAIL'	=> $lang['head_details'],
				
					'S_DETAIL'	=> check_sid("$file&mode=detail&amp;id=$data"),
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
			
			case 'detail':
			
				$template->assign_block_vars('detail', array());
				
			#	debug($_POST);
				
				if ( $smode == 'map_create' || $smode == 'map_update' )
				{
				#	debug($_POST);
					
					if ( $smode == 'map_create' )
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
										$pic_ary[$map_name[$i]] = upload_image('', 'image_match', '', '1', '', '', $dir_path . $detail['match_path'], $map_file['temp'][$i], $map_file['name'][$i], $map_file['size'][$i], $map_file['type'][$i], $error);
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
										'map_picture'		=> isset($pic_ary[$map_name[$i]]['map_picture']) ? $pic_ary[$map_name[$i]]['map_picture'] : '',
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
									$_sql[] = sql(MATCH_MAPS, 'create', $map_ary[$i]);
								}
							}
							else
							{
								$error[] = ( $error ? '<br />' : '' ) . $lang['msg_select_map'];
							}
							
							if ( !$error )
							{
								$message = $lang['create_map'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
										
								log_add(LOG_ADMIN, $log, $smode, $_sql);
								message(GENERAL_MESSAGE, $message);
							}
							else
							{
								$template->assign_vars(array('ERROR_MESSAGE' => $error));
								$template->assign_var_from_handle('ERROR_BOX_UPLOAD', 'error');
								
								log_add(LOG_ADMIN, $log, 'error', $error);
							}
						}
					}
					else
					{
						$map	= request('map_id', 'array');
						$pic	= request('map_pic', 'array');
						$delete	= request('map_delete', 4);
						$round	= request('map_round', 4);
						
						$name	= request('map_name', 4);
						$home	= request('map_points_home', 4);
						$rival	= request('map_points_rival', 4);
						$files	= request_file('ufile');
						
						$picture	= request('pic_picture', 4);
						$preview	= request('pic_preview', 4);
						
						$ary_del = '';
						
						/*
						 *	Maps filtern um gelöscht Maps vorab zulöschen,
						 *	um unnötiges Speichern zuverhindern!
						 */					
						for ( $i = 0; $i < count($map); $i++ )
						{
							if ( isset($delete[$map[$i]]) )
							{
								$ary_del[] = $map[$i];
							}
							else
							{
								$ary_map[] = $map[$i];
								$ary_pic[$map[$i]] = isset($pic[$map[$i]]) ? 1 : 0;
								$ary_name[$map[$i]] = $name[$map[$i]];
								$ary_home[$map[$i]] = $home[$map[$i]];
								$ary_rival[$map[$i]] = $rival[$map[$i]];
								$ary_round[$map[$i]] = $round[$map[$i]];
								$ary_file[$map[$i]] = array($files['temp'][$map[$i]], $files['name'][$map[$i]], $files['size'][$map[$i]], $files['type'][$map[$i]]);
							}
						}
						
						if ( $ary_del )
						{
							$sql = sql(MATCH_MAPS, 'delete', false, 'map_id', $ary_del);
						}
						
						if ( $ary_map )
						{
							for ( $i = 0; $i < count($ary_map); $i++ )
							{
								if ( $ary_file[$ary_map[$i]][0] )
								{
									$pic_ary[$ary_map[$i]] = upload_image('', 'image_match', '', '1', $picture[$ary_map[$i]], $preview[$ary_map[$i]], $dir_path . $detail['match_path'], $ary_file[$ary_map[$i]][0], $ary_file[$ary_map[$i]][1], $ary_file[$ary_map[$i]][2], $ary_file[$ary_map[$i]][3], $error);
								}
								else
								{
									if ( $ary_pic[$ary_map[$i]] )
									{
										image_delete($picture[$ary_map[$i]], $preview[$ary_map[$i]], $dir_path . $detail['match_path']);
										
										$picture[$ary_map[$i]] = '';
										$preview[$ary_map[$i]] = '';
									}
								}
							}
						}
						
						$_sql = '';
						
						if ( !$error )
						{
							for ( $i = 0; $i < count($ary_map); $i++ )
							{
								$ary = array(
									'map_name'			=> $ary_name[$ary_map[$i]],
									'map_round'			=> $ary_round[$ary_map[$i]],
									'map_points_home'	=> $ary_home[$ary_map[$i]],
									'map_points_rival'	=> $ary_rival[$ary_map[$i]],
									'map_picture'		=> isset($pic_ary[$ary_map[$i]]['map_picture']) ? $pic_ary[$ary_map[$i]]['map_picture'] : $picture[$ary_map[$i]],
									'map_preview'		=> isset($pic_ary[$ary_map[$i]]['pic_preview']) ? $pic_ary[$ary_map[$i]]['pic_preview'] : $preview[$ary_map[$i]],
								);
								
								$sql = sql(MATCH_MAPS, $smode, $ary, 'map_id', $ary_map[$i]);
								
								if ( is_array($sql) )
								{
									foreach ( $sql as $keys => $values )
									{
										if ( $values )
										{
											$_sql[] = $values;
										}
									}
								}
							}
							
							$msg = $lang['update_map'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
							
							log_add(LOG_ADMIN, $log, $smode, $_sql);
							message(GENERAL_MESSAGE, $msg);
						}
						else
						{
							$template->assign_vars(array('ERROR_MESSAGE' => $error));
							$template->assign_var_from_handle('ERROR_BOX_MAPS', 'error');
							
							log_add(LOG_ADMIN, $log, 'error', $error);
						}
					}
				}
				
				if ( $smode == 'user_add' || $smode == 'user_player' || $smode == 'user_replace' || $smode == 'user_delete' || $smode == 'option' )
				{
					debug($_POST, 'post2');
					
					$status		= ( $smode == 'user_add' ) ? request('status', 0) : ( $smode == 'user_player' ? '0' : '1' );
					$members	= request('members', 4);
					
					$error[] = ( !$members ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_member'] : '';
					$error[] = ( $smode == 'option' ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_option'] : '';
					
					if ( !$error )
					{
						if ( $smode == 'user_add' )
						{
							$ary_in_db = array();
							$ary_users = $members;
							$ary_users_list = implode(', ', $members);
							
							$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_MLINE . " AND type_id = $data_id AND user_id IN ($ary_users_list)";
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
										'type'			=> (int) TYPE_MLINE,
										'type_id'		=> (int) $data_id,
										'user_id'		=> (int) $user_id,							
										'user_status'	=> (int) $status,
										'time_create'	=> (int) $time,
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
								
								$sql = "INSERT INTO " . LISTS . " (" . implode(', ', array_keys($sql_ary[0])) . ") VALUES " . implode(', ', $ary);
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$lang_type = 'create_user';
						}
						else if ( $smode == 'user_player' || $smode == 'user_replace' )
						{
							for ( $i = 0; $i < count($members); $i++ )
							{
								$sql = "UPDATE " . LISTS . " SET user_status = $status, time_update = $time WHERE type = " . TYPE_MLINE . " AND type_id = $data_id AND user_id = " . $members[$i];
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$lang_type = 'update_user';
						}
						else if ( $smode == 'user_delete' )
						{
							$sql_in = implode(', ', $members);
				
							$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_MLINE . " AND type_id = $data_id AND user_id IN ($sql_in)";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$lang_type = 'delete_user';
						}
						
						if ( $error )
						{
							$template->assign_vars(array('ERROR_MESSAGE' => $error));
							$template->assign_var_from_handle('ERROR_BOX_PLAYER', 'error');
							
							log_add(LOG_ADMIN, $log, $smode, $error);
						}
						else
						{
							$msg = $lang[$lang_type] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
							
							log_add(LOG_ADMIN, $log, $smode, $lang_type);
							message(GENERAL_MESSAGE, $msg);
						}
					}
					else
					{
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX_PLAYER', 'error');
						
						log_add(LOG_ADMIN, $log, $smode, $error);
					}
				}
				
			#	$template->pparse('body');
				
				break;
				
			case 'sync':
			
				$template->assign_block_vars('sync', array());

				$sql = "SELECT m.match_id, m.match_rival_name, m.match_date, m.match_path, t.team_name, g.game_image
							FROM " . MATCH . " m
								LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
						ORDER BY m.match_date DESC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$matchs = $db->sql_fetchrowset($result);
				
				$cnt_match = count($matchs);
				
				for ( $i = 0; $i < $cnt_match; $i++ )
				{
					$sql = "SELECT mm.*, m.map_name AS match_map_name, m.map_file
								FROM " . MATCH_MAPS . " mm, " . MAPS . " m
							WHERE mm.map_id = m.map_id AND mm.match_id = " . $matchs[$i]['match_id'] . "
							ORDER BY map_id ASC, match_id ASC";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$match_maps = $db->sql_fetchrowset($result);
					$count_maps = count($match_maps);
					
					$_files = $_picture = $_preview = $tmp_files = '';
					
					$dir_check = ( is_dir($dir_path . $matchs[$i]['match_path']) ) ? img('i_icon', 'folder', 'folder') : img('i_icon', 'folder_bug', 'folder_bug');
					$dir_write = ( is_writable($dir_path . $matchs[$i]['match_path']) ) ? img('i_icon', 'folder_edit', 'folder_edit') : img('i_icon', 'folder_error', 'folder_error');
					
					if ( is_dir($dir_path . $matchs[$i]['match_path']) )
					{
						$path_files = scandir($dir_path . $matchs[$i]['match_path']);
						
						foreach ( $path_files as $files )
						{
							if ( $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' && $files != 'spacer.gif' && $files != 'Thumbs.db' && in_array(substr($files, -3), array('png', 'jpg', 'jpeg', 'gif', 'bmp')) )
							{
								$_picture[] = $files;
							}
						#	if ( !strstr($files, 'preview') && $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' && $files != 'spacer.gif' && $files != 'Thumbs.db' )
						#	{
						#		$_picture[] = $files;
						#	}
						#	else if ( strstr($files, 'preview') && $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' && $files != 'spacer.gif' && $files != 'Thumbs.db' )
						#	{
						#		$_preview[] = $files;
						#	}
						}
					}
					
					$template->assign_block_vars('sync.row', array(
						'GAME'	=> $matchs[$i]['game_image'] ? display_gameicon($matchs[$i]['game_size'], $matchs[$i]['game_image']) : $images['icon_spacer'],
						'TEAM'	=> $matchs[$i]['team_name'],
						'RIVAL'	=> $matchs[$i]['match_rival_name'],
						'CHECK'	=> $dir_check,
						'WRITE'	=> $dir_write,
					));
					
					if ( is_dir($dir_path . $matchs[$i]['match_path']) && $_picture )
					{
						$tmp_files[$matchs[$i]['match_id']] = match_check_image($dir_path . $matchs[$i]['match_path'], $count_maps, $_picture, $match_maps);
					}
					
					if ( $match_maps )
					{
						$points_home = $points_rival = 0;
						
						for ( $j = 0; $j < $count_maps; $j++ )
						{
							$pic_confirm = $pre_confirm = $lang['common_no'];
							
							if ( $matchs[$i]['match_id'] == $match_maps[$j]['match_id'] )
							{
								if ( isset($tmp_files[$match_maps[$j]['match_id']]) )
								{
									$pic_confirm = isset($tmp_files[$matchs[$i]['match_id']]['in']['picture']) ? ( in_array($match_maps[$j]['map_picture'], $tmp_files[$matchs[$i]['match_id']]['in']['picture']) ) ? $lang['common_yes'] : $lang['common_no'] : $lang['common_no'];
									$pre_confirm = isset($tmp_files[$matchs[$i]['match_id']]['in']['preview']) ? ( in_array($match_maps[$j]['map_preview'], $tmp_files[$matchs[$i]['match_id']]['in']['preview']) ) ? $lang['common_yes'] : $lang['common_no'] : $lang['common_no'];
								}
								
								$points_home += $match_maps[$j]['map_points_home'];
								$points_rival += $match_maps[$j]['map_points_rival'];
								
								$template->assign_block_vars('sync.row.maps', array(
									'NAME'		=> $match_maps[$j]['match_map_name'],
									'PICTURE'	=> $pic_confirm,
									'PREVIEW'	=> $pre_confirm,
									'HOME'		=> $match_maps[$j]['map_points_home'],
									'RIVAL'		=> $match_maps[$j]['map_points_rival'],
								));
							}
						}
						
						if ( isset($tmp_files[$matchs[$i]['match_id']]['out']) )
						{
							$tmp_count = count($tmp_files[$matchs[$i]['match_id']]['out']);
							
							for ( $j = 0; $j < count($tmp_files[$matchs[$i]['match_id']]['out']); $j++ )
							{
								$template->assign_block_vars('sync.row.maps.result_row', array(
									'NAME'	=> $tmp_files[$matchs[$i]['match_id']]['out'][$j],
								));
							}
						}
						else
						{
							$tmp_count = 0;
						}
						
						$template->assign_block_vars('sync.row.maps.result', array(
							'COUNT'	=> $tmp_count,
							'HOME'	=> $points_home,
							'RIVAL'	=> $points_rival,
						));
					}
				}

				$template->pparse('body');
			
				break;
				
			case 'delete':
			
				$data_sql = data(MATCH, $data, false, 1, true);
				
				if ( $data && $confirm )
				{
					$file = ( $acp_main ) ? check_sid('index.php') : check_sid($file);
					$name = ( $acp_main ) ? $lang['header_acp'] : $acp_title;
					
					$sql = sql(MATCH, $mode, $data_sql, 'match_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $name);
					
					sql(MATCH_MAPS, $mode, $data_sql, 'match_id', $data);
					sql(MATCH_USERS, $mode, $data_sql, 'match_id', $data);
					sql(MATCH_LINEUP, $mode, $data_sql, 'match_id', $data);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
					$fields .= "<input type=\"hidden\" name=\"acp_main\" value=\"$acp_main\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['match_rival_name']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}
			
				$template->pparse('confirm');
				
				break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	if ( !$data )
	{
		$template->assign_block_vars('display', array());
		
		$select_id = ( $t_id > 0 ) ? "WHERE m.team_id = $t_id" : '';

		$sql = "SELECT m.*, t.team_name, g.game_image, tr.training_id
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
		$tmp = $db->sql_fetchrowset($result);
		
		$_new = $_old = '';
		
		if ( !$tmp )
		{
			$template->assign_block_vars('display.new_empty', array());
			$template->assign_block_vars('display.old_empty', array());
		}
		else
		{
			foreach ( $tmp as $match => $row )
			{
				if ( $row['match_date'] > time() )
				{
					$_new[] = $row;
				}
				else if ( $row['match_date'] < time() )
				{
					$_old[] = $row;
				}
			}
			
			if ( !$_new )
			{
				$template->assign_block_vars('display.new_empty', array());
			}
			else
			{
				for ( $i = $start; $i < count($_new); $i++ )
				{
					$id		= $_new[$i]['match_id'];
					$rival	= $_new[$i]['match_rival_name'];
					$team	= $_new[$i]['team_id'];
					$public	= $_new[$i]['match_public'] ? 'sprintf_match_name' : 'sprintf_match_intern';
					
					$template->assign_block_vars('display.new_row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), sprintf($lang[$public], $rival), ''),
						'GAME'		=> display_gameicon($_new[$i]['game_image']),
						'DATE'		=> create_date($userdata['user_dateformat'], $_new[$i]['match_date'], $userdata['user_timezone']),
						'TRAINING'	=> ( !$_new[$i]['training_id'] ) ? href('a_img', 'admin_training.php', array('mode' => 'create', 'team' => $team, 'id' => $id, 'vs' => $rival), 'icon_match_add', 'sm_training') : href('a_img', 'admin_training.php', array('mode' => 'list', 'team' => $team), 'icon_match', 'sm_training'),
						
						'DETAIL'	=> href('a_img', $file, array('mode' => 'detail', 'id' => $id), 'icon_details', 'common_details'),
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
					));
				}
			}
			
			if ( !$_old )
			{
				$template->assign_block_vars('display.old_empty', array());
			}
			else
			{
				for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, count($_old)); $i++ )
				{
					$id		= $_old[$i]['match_id'];
					$rival	= $_old[$i]['match_rival_name'];
					$team	= $_old[$i]['team_id'];
					$public	= $_old[$i]['match_public'] ? 'sprintf_match_name' : 'sprintf_match_intern';
					
					$template->assign_block_vars('display.old_row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), sprintf($lang[$public], $rival), ''),
						
						'GAME'		=> display_gameicon($_old[$i]['game_image']),
						'DATE'		=> create_date($userdata['user_dateformat'], $_old[$i]['match_date'], $userdata['user_timezone']),
						
						'DETAIL'	=> href('a_img', $file, array('mode' => 'detail', 'id' => $id), 'icon_details', 'common_details'),
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
					));
				}
			}
		}
		
		$current_page = !count($_old) ? 1 : ceil( count($_old) / $settings['per_page_entry']['acp'] );
		
		$fields = '<input type="hidden" name="mode" value="create" />';
		
		$template->assign_vars(array(
			'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['match']),
			'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['match']),
			'L_EXPLAIN'		=> $lang['explain'],
			
			'L_TRAINING'	=> $lang['training'],
			'L_UPCOMING'	=> $lang['upcoming'],
			'L_EXPIRED'		=> $lang['expired'],
			'L_DETAILS'		=> $lang['common_details'],
			
			'L_SYNC'		=> $lang['sync'],
			'U_SYNC'		=> check_sid("$file?mode=sync"),
			
			'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ),
			'PAGE_PAGING'	=> generate_pagination('admin_match.php?', count($_old), $settings['per_page_entry']['acp'], $start ),
			#select_team($tmp_data, $tmp_meta, $tmp_name, 'request')
			'S_SORT'		=> select_team($t_id, '', 'team', 'submit', 'selectsmall'),
			'S_TEAM'		=> select_team($t_id, '', 'team_id', false, 'selectsmall'),
			
			'S_CREATE'		=> check_sid("$file&mode=create"),
			'S_ACTION'		=> check_sid($file),
			'S_FIELDS'		=> $fields,
		));
	}
	else
	{
		$template->assign_block_vars('detail', array());
		
		$s_options = $s_team_users = '';
				
				if ( $order )
				{
					update(MATCH_MAPS, 'map', $move, $data_map);
					orders(MATCH_MAPS);
					
					log_add(LOG_ADMIN, $log, 'order_maps');
				}
				
				$sql = "SELECT	m.*, t.team_id, t.team_name, g.game_image, g.game_tag
							FROM " . MATCH . " m
								LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
							WHERE m.match_id = $data";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$detail = $db->sql_fetchrow($result);
				
				$sql = "SELECT u.user_id, u.user_name
							FROM " . USERS . " u, " . LISTS . " tu
						WHERE tu.type_id = " . $detail['team_id'] . " AND tu.user_id = u.user_id
						ORDER BY u.user_name";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$team_users = $db->sql_fetchrowset($result);
				
				$sql = "SELECT u.user_id, u.user_name, ml.user_status
							FROM " . LISTS . " ml, " . USERS . " u
						WHERE ml.type_id = $data AND ml.user_id = u.user_id
						ORDER BY ml.user_status";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$list_users = $db->sql_fetchrowset($result);
				
				if ( $team_users || $list_users )
				{
					if ( $team_users )
					{
						$template->assign_block_vars('detail.team_users', array());

						$s_team_users = "<select class=\"select\" name=\"members[]\" id=\"table\" size=\"6\" multiple=\"multiple\">";
						
						for ( $i = 0; $i < count($team_users); $i++ )
						{
							$s_team_users .= "<option value=\"" . $team_users[$i]['user_id'] . "\">" . sprintf($lang['sprintf_select_format'], $team_users[$i]['user_name']) . "</option>";
						}
						
						$s_team_users .= "</select>";
					}
					
					if ( $list_users )
					{
						$template->assign_block_vars('detail.list_users', array());
						
						for ( $i = 0; $i < count($list_users); $i++ )
						{
							$template->assign_block_vars('detail.list_users.member_row', array(
								'USER_ID'	=> $list_users[$i]['user_id'],
								'USERNAME'	=> $list_users[$i]['user_name'],
								'STATUS'	=> ( !$list_users[$i]['user_status'] ) ? $lang['status_player'] : $lang['status_replace'],
							));
						}
						
						$s_options .= "<select class=\"postselect\" name=\"smode\">";
						$s_options .= "<option value=\"option\">" . sprintf($lang['sprintf_select_format'], $lang['common_select_option']) . "</option>";
						$s_options .= "<option value=\"user_player\">" . sprintf($lang['sprintf_select_format'], sprintf($lang['status_set'], $lang['status_player'])) . "</option>";
						$s_options .= "<option value=\"user_replace\">" . sprintf($lang['sprintf_select_format'], sprintf($lang['status_set'], $lang['status_replace'])) . "</option>";
						$s_options .= "<option value=\"user_delete\">" . sprintf($lang['sprintf_select_format'], $lang['common_delete']) . "</option>";
						$s_options .= "</select>";
					}
					else
					{
						$template->assign_block_vars('detail.no_list_users', array());
					}
				}
				else
				{
					$template->assign_block_vars('detail.entry_empty', array());
					
					$s_team_users = $lang['no_users'];
				}
				
				$max = maxi(MATCH_MAPS, 'map_order', "match_id = $data");
				$match_maps = data(MATCH_MAPS, "match_id = $data", 'map_order', 1, false);
				
				if ( $match_maps )
				{
					$template->assign_block_vars('detail.maps', array());
					
					for ( $i = 0; $i < count($match_maps); $i++ )
					{
						$map_id = $match_maps[$i]['map_id'];
						
						$order = $match_maps[$i]['map_order'];
						
						$fields .= "<input type=\"hidden\" name=\"pic_picture[$map_id]\" value=\"" . $match_maps[$i]['map_picture'] . "\" />";
						$fields .= "<input type=\"hidden\" name=\"pic_preview[$map_id]\" value=\"" . $match_maps[$i]['map_preview'] . "\" />";
						
						$template->assign_block_vars('detail.maps.map_row', array(
							'MAP_ID'	=> $map_id,
							'MAP_HOME'	=> $match_maps[$i]['map_points_home'],
							'MAP_RIVAL'	=> $match_maps[$i]['map_points_rival'],
							
							'PIC_URL'	=> ( $match_maps[$i]['map_picture'] ) ? '<a href="' . $dir_path . $detail['match_path'] . '/' . $match_maps[$i]['map_picture'] . '" rel="lightbox"><img src="' . $dir_path . $detail['match_path'] . '/' . $match_maps[$i]['map_preview'] . '" alt="" /></a>' : '',
							
						#	'MOVE_UP'	=> ( $match_maps[$i]['map_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=detail&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_pic=$map_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
						#	'MOVE_DOWN'	=> ( $match_maps[$i]['map_order'] != $max ) ? '<a href="' . check_sid("$file?mode=detail&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_pic=$map_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',
							
							'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => 'detail', 'move' => '-15', 'id' => $data, 'p' => $map_id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => 'detail', 'move' => '+15', 'id' => $data, 'p' => $map_id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

							
						#	'S_MAP'		=> select_map($detail['team_id'], $match_maps[$i]['map_id'], $match_maps[$i]['map_name']),
							'S_ROUND'	=> match_round('selectsmall', $map_id, $match_maps[$i]['map_round']),
						));
						
						( $match_maps[$i]['map_picture'] ) ? $template->assign_block_vars('detail.maps.map_row.delete', array()) : '';
					}
				}
				
				$cats = data(MAPS, " map_tag = '" . $detail['game_tag'] . "'", 'map_order ASC', 1, true);
				$maps = $cats ? data(MAPS, " map_id = " . $cats['map_id'], 'map_order ASC', 1, false) : '';
				
				$s_maps = '';
				
				if ( $maps )
				{
					$s_maps .= "<select class=\"selectsmall\" name=\"map_name[]\" id=\"map_name\">";
					$s_maps .= "<option selected=\"selected\" value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . "</option>";
					
					$cat_id		= $cats['map_id'];
					$cat_name	= $cats['map_name'];
					
					$s_map = '';
					
					for ( $j = 0; $j < count($maps); $j++ )
					{
						$map_id		= $maps[$j]['map_id'];
					#	$map_cat	= $maps[$j]['cat_id'];
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
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$create = "<input type=\"hidden\" name=\"smode\" value=\"map_create\" />";
				$submit = "<input type=\"hidden\" name=\"smode\" value=\"map_update\" />";
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['match']),
					'L_INPUT'			=> sprintf($lang['sprintf_update'], $lang['match'], $detail['match_rival_name']),
					'L_DETAIL'			=> $lang['head_details'],
					
				#	'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['match']),
				#	'L_INPUT'			=> sprintf($lang['sprintf_update'], $lang['match'], $detail['match_rival_name']),
				#	'L_DETAIL'			=> $lang['match_details'],
				#	'L_EXPLAIN'			=> $lang['match_details_explain'],
			
					'L_LINEUP'			=> $lang['lineup'],
					'L_LINEUP_ADD'		=> $lang['lineup_add'],
					'L_LINEUP_ADD_EXP'	=> $lang['lineup_add_exp'],
					'L_LINEUP_PLAYER'	=> $lang['status_player'],
					'L_LINEUP_REPLACE'	=> $lang['status_replace'],
					'L_LINEUP_STATUS'	=> $lang['lineup_status'],
					
					'L_NO_MEMBER'	=> $lang['no_users'],
					'L_NO_STORE'	=> $lang['no_users_store'],
					
					'L_MAPS_OVERVIEW'	=> $lang['detail_maps_overview'],
					'L_MAPS_PIC'		=> $lang['detail_maps_pic'],
					
					'L_DETAIL_MAP'		=> $lang['detail_map'],
					'L_DETAIL_POINTS'	=> $lang['detail_points'],
					'L_DETAIL_MAPPIC'	=> $lang['detail_mappic'],
					
					
					
				#	'L_MAPS'			=> $lang['details_maps'],
				#	
				#	'L_MAPS_OVERVIEW'	=> $lang['details_maps_overview'],
				#	
				#	
				#	
				#	'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
				#	'L_LINEUP_PLAYER'	=> $lang['status_player'],
				#	'L_LINEUP_REPLACE'	=> $lang['status_replace'],
				#	'L_LINEUP_PLAYER'	=> $lang['lineup_player'],
					
					'L_ADD'		=> $lang['common_add'],
				#	'S_MAP'		=> $s_maps,
				#	'S_USERS'	=> $s_team_users,
				#	'S_OPTIONS'	=> $s_options,
					'S_INPUT'	=> check_sid("$file?mode=update&amp;$url=$data"),
					
					'S_ACTION'	=> check_sid($file),
					'S_CREATE'	=> $create,
					'S_UPDATE'	=> $submit,
					'S_FIELDS'	=> $fields,
				));
		
	}
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>