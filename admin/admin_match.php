<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_MATCH',
		'CAT'		=> 'CLAN',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'ACP_MATCH'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$current = $log = 'ACP_MATCH';
	
	include('./pagestart.php');
	
	add_lang(array('training', 'match'));
	add_tpls('acp_match');
	acl_auth(array('ACP_MATCH', 'ACP_MATCH_CREATE', 'ACP_MATCH_DELETE', 'ACP_MATCH_MANAGE', 'ACP_MATCH_UPLOAD'));
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$file	= basename(__FILE__) . $iadds;
	$path	= $root_path . $settings['path']['games'];
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$sub	= request('sub', TYP);
	$subs	= request('subs', TYP);
	$mode	= request('mode', TYP);
	$sort	= request('sort', TYP);
	$type	= request('type', TYP);
	$smode	= request('smode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$p_id	= request('p_id', INT);
	$t_id	= request('t_id', INT);
	
	$index	= request('index', INT);
	
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$cancel = ( isset($_POST['cancel']) ) ? redirect('admin/' . check_sid($file)) : false;
#	$cancel = ( isset($_POST['cancel']) ) ? true : false;

	( $cancel && !$index )	? redirect('admin/' . check_sid(($file . ($mode ? "?mode=$mode&id=$data" : '')))) : false;
	( $cancel && $index )	? redirect('admin/' . check_sid('index.php', true)) : false;

	$_top	= sprintf($lang['STF_HEADER'], $lang['match']);
	$_tpl	= ($mode === 'delete') ? 'confirm' : 'body';
	$_path	= $root_path . $settings['path_matchs']['path'];
	
	$mswitch = $settings['switch']['match'];
	$tswitch = $settings['switch']['training'];
	$comment = $settings['comments']['match'];

    $mode = (in_array($mode, array('create', 'update', 'detail', 'delete', 'move_up', 'move_down', 'member', 'rival', 'change', 'sync'))) ? $mode : false;
    $_tpl = ($mode === 'delete' || $smode === 'delete') ? 'confirm' : 'body';
	
	if ( $mode == 'sync' || $mode == 'rival' || $t_id != 0 )
	{
		$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
	}
	
	$option[] = href('a_txt', $file, array('mode' => 'sync', 'id' => $data), $lang['COMMON_RESYNC'], $lang['COMMON_RESYNC']);
	$option[] = href('a_txt', $file, array('mode' => 'rival', 'id' => $data), $lang['RIVAL'], $lang['RIVAL']);

	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			$template->assign_block_vars("input.$mode", array());
			
			$vars = array(
				'match' => array(
					'title1'	=> 'INPUT_STANDARD',
					'team_id'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:team',			'required' => 'select_team', 'params' => array('request', 'training_maps')),
					'match_type'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:match_type',	'required' => 'select_type'),
					'match_war'			=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:match_war',		'required' => 'select_war'),
					'match_league'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:match_league',	'required' => 'select_league'),
					'match_league_match'=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25'),
				#	'match_date'		=> array('validate' => INT,	'explain' => false,	'type' => 'drop:datetime',	'params' => ( $mode == 'create' ) ? $time : '-1'),
					'match_date'		=> array('validate' => ($mswitch ? INT : TXT), 'type' => ($mswitch ? 'drop:datetime' : 'text:25;25'), 'params' => ($mswitch ? (($mode == 'create') ? $time : '-1') : 'format')),
				#	'match_public'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'match_comments'	=> ( $comment ) ? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
					
					'title2'	=> 'INPUT_RIVAL',
					'match_rival_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'ajax:25;25',	'required' => 'input_rival', 'params' => 'rival'),
					'match_rival_tag'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50', 'required' => 'input_clantag'),
					'match_rival_url'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
					'match_rival_logo'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
					'match_rival_lineup'=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
					
					'title3'	=> 'INPUT_SERVER',
					'match_server_ip'	=> array('validate' => TXT,	'explain' => false,	'type' => 'ajax:25;25', 'required' => 'input_server', 'params' => 'server'),
					'match_server_pw'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
					'match_hltv_ip'		=> array('validate' => TXT,	'explain' => false,	'type' => 'ajax:25;50', 'params' => 'server'),
					'match_hltv_pw'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
					
					'title4'	=> 'INPUT_MESSAGE',
					'match_report'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:30'),
					'match_comment'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:30'),
			
					'match_path'		=> 'hidden',
					'time_create'		=> 'hidden',
					'time_update'		=> 'hidden',
					
					'title5'	=> ( $mode == 'create' ) ? 'INPUT_TRAINING' : 'hidden',
					'training_on'		=> ( $mode == 'create' ) ? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno',	'params' => array('type', false)) : 'hidden',
					'training_date'		=> ( $mode == 'create' ) ? array('validate' => ($tswitch ? INT : TXT), 'explain' => false,	'type' => ($tswitch ? 'drop:datetime' : 'text:25;25'), 'params' => ($tswitch ? $time : 'format'), 'prefix' => ($tswitch ? 't' : ''), 'divbox' => true, 'required' => array('select_date', 'training_on', 1)) : 'hidden',
					'training_duration'	=> ( $mode == 'create' ) ? array('validate' => INT,	'explain' => false,	'type' => 'drop:duration',	'divbox' => true, 'params' => 'training_date') : 'hidden',
					'training_maps'		=> ( $mode == 'create' ) ? array('validate' => ARY,	'explain' => false,	'type' => 'drop:maps',		'divbox' => true) : 'hidden',
					'training_text'		=> ( $mode == 'create' ) ? array('validate' => TXT,	'explain' => false,	'type' => 'textarea:30',	'divbox' => true) : 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'team_id'				=> $t_id,
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
				$data_sql = data(MATCH, $data, false, 1, 'row');
				
				if ( $data['match_date'] > time() )
				{
					$template->assign_block_vars('input.reset', array());
				}
				
				$option[] = href('a_txt', $file, array('mode' => 'detail', 'id' => $data), $lang['INPUT_DETAILS'], $lang['INPUT_DETAILS']);
			}
			else
			{
				$data_sql = build_request(MATCH, $vars, $error, $mode, false, array('training_on', 'training_date', 'training_duration', 'training_maps', 'training_text'));
				
				if ( !$error )
				{
					if ( $mode == 'create' )
					{
						$date_day	= request('day', INT) ? request('day', INT) : date('d', $data_sql['match_date']);
						$date_month	= request('month', INT) ? request('month', INT) : date('m', $data_sql['match_date']);
						$date_year	= request('year', INT) ? request('year', INT) : date('y', $data_sql['match_date']);
						
						$data_sql['match_path'] = create_folder($_path, sprintf('%d%d%d_', $date_day, $date_month, $date_year), true);
						
						foreach ( $data_sql as $key => $value )
						{
							if ( in_array($key, array('training_date', 'training_duration', 'training_maps', 'training_text')) )
							{
								$train[$key] = $value;
							}
							else if ( $key == 'training_on' )
							{
								$settrain = ( $value ) ? true : false;
							}
							else
							{
								$match[$key] = $value;
							}
						}
						
						$sql = sql(MATCH, $mode, $match);
						
						$train['team_id']		= $match['team_id'];
						$train['match_id']		= $db->sql_nextid();								
						$train['training_vs']	= $match['match_rival_name'];
						$train['training_date']	= $match['match_date'];
						
						if ( $settrain )
						{
							sql(TRAINING, $mode, $train);
						}
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
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $_top);
					}
					else
					{
						$sql = sql(MATCH, $mode, $data_sql, 'match_id', $data);
						
					#	if ( isset($reset) ) { sql(MATCH_USERS, 'delete', false, 'match_id', $data); }
						
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output(MATCH, $vars, $data_sql);
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['match_rival_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
			
		case 'detail':
		
			$template->assign_block_vars('detail', array());
			
			$s_options = $s_team_users = $current_page = $cnt = '';
			
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
			
			/**	Users **/
			switch ( $smode )
			{
				case 'create':
				case 'player':
				case 'replace':
				
					$status		= ( $smode == 'create' ) ? request('status', INT) : ( $smode == 'player' ? '0' : '1' );
					$members	= request('members', ARY);
					
					if ( !$members )
					{
						$error[] = $lang['msg_select_member'];
					}
					
					if ( !$error )
					{
						switch ( $smode )
						{
							case 'create':
							
								$ary_in_db = array();
								$ary_users = $members;
								$ary_users_list = implode(', ', $members);
								
								$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_MLINE . " AND type_id = $data AND user_id IN ($ary_users_list)";
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
											'type_id'		=> (int) $data,
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
								
								$lang_type = 'user_create';
								
								break;
								
							case 'player':
							case 'replace':
							
								for ( $i = 0; $i < count($members); $i++ )
								{
									$sql = "UPDATE " . LISTS . " SET user_status = $status, time_update = $time WHERE type = " . TYPE_MLINE . " AND type_id = $data AND user_id = " . $members[$i];
									if ( !$db->sql_query($sql) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
								}
								
								$lang_type = 'user_update';
								
								break;
							}
							
						log_add(LOG_ADMIN, $log, $mode, $lang_type);
						right('ERROR_BOX', langs($lang_type));
					}
					else
					{
						error('ERROR_BOX', $error);
					}
					
					break;						
				
				case 'delete':
				
					$data_sql	= data(MATCH, $data, false, 1, 'row');
					$members	= request('members', ARY);
					
					if ( $members && $data && $accept && $userauth['a_match_manage'] )
					{
						$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_MLINE . " AND type_id = $data AND  user_id IN ($members)";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$msg = $lang['update_delete'] . sprintf($lang['return_update'], check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
								
						log_add(LOG_ADMIN, $log, $mode, 'update_delete');
						message(GENERAL_MESSAGE, $msg);
					}
					else if ( $members && $data && !$accept && $userauth['a_match_manage'] )
					{
						$sql_in = implode(', ', $members);

						$fields .= build_fields(array(
							'mode'		=> $mode,
							'smode'		=> $smode,
							'id'		=> $data,
							'members'	=> $sql_in,
						));
						
						$sql = "SELECT user_name, user_id FROM " . USERS . " WHERE user_id IN ($sql_in)";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						$ary_user_name = array();
								
						while ($row = $db->sql_fetchrow($result))
						{
							$ary_user_name[] = (string)	$row['user_name'];
						}
						$db->sql_freeresult($result);
						
						$user_names = implode(', ', $ary_user_name);
						
						$template->assign_vars(array(
							'M_TITLE'	=> $lang['COMMON_CONFIRM'],
							'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], sprintf($lang['notice_confirm_match'], $user_names), $data_sql['match_rival_name']),
		
							'S_ACTION'	=> check_sid($file),
							'S_FIELDS'	=> $fields,
						));
					}
					else
					{
						message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
					}
	
					break;
					
				case 'upload':
				
					$map_name	= request('map_name', 4);
					$map_home	= request('map_points_home', 4);
					$map_rival	= request('map_points_rival', 4);
					$map_file	= request_files('ufile');
					$next_order = _max(MATCH_MAPS, 'map_order', "match_id = $data");
					
					if ( $map_name )
					{
						for ( $i = 0; $i < count($map_name); $i++ )
						{
							if ( $map_name[$i] )
							{
								if ( $map_file['temp'][$i] )
								{
									$pic_ary[$map_name[$i]] = upload_image('', 'image_match', '', 'preview', '', '', $_path . $detail['match_path'], array('temp' => $map_file['temp'][$i], 'name' => $map_file['name'][$i], 'size' => $map_file['size'][$i], 'type' => $map_file['type'][$i]), $error);
								}
							}
						}
						
						$map_ary = '';
						
						for ( $i = 0; $i < count($map_name); $i++ )
						{
							if ( $map_name[$i] )
							{
								$map_ary[] = array(
									'match_id'			=> $data,
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
							$error[] = ( $error ? '<br />' : '' ) . $lang['NOTICE_SELECT_MAP'];
						}
						
						if ( !$error )
						{
							$lang_type = 'create_map';
						
							log_add(LOG_ADMIN, $log, $smode, $_sql);
							right('ERROR_BOX', langs($lang_type));
						#	$message = $lang['create_map'] . sprintf($lang['return_update'], check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
									
						#	log_add(LOG_ADMIN, $log, $smode, $_sql);
						#	message(GENERAL_MESSAGE, $message);
						}
						else
						{
							error('ERROR_BOX_UPLOAD', $error);
							
							log_add(LOG_ADMIN, $log, 'error', $error);
						}
					}
				
					break;
					
				case 'update':
				
					$map	= request('map_id', ARY);
					$pic	= request('map_pic', ARY);
					$delete	= request('map_delete', ARY);
					$round	= request('map_round', ARY);
					
					$name	= request('map_name', ARY);
					$home	= request('map_points_home', ARY);
					$rival	= request('map_points_rival', ARY);
					$files	= request_file('ufile');
					
					$picture	= request('pic_picture', ARY);
					$preview	= request('pic_preview', ARY);
					
					$ary_del = '';
					
				#	debug($name, 'name');
					
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
								$pic_ary[$ary_map[$i]] = upload_image('', 'image_match', '', '1', $picture[$ary_map[$i]], $preview[$ary_map[$i]], $_path . $detail['match_path'], array($ary_file[$ary_map[$i]][0], $ary_file[$ary_map[$i]][1], $ary_file[$ary_map[$i]][2], $ary_file[$ary_map[$i]][3]), $error);
							}
							else
							{
								if ( $ary_pic[$ary_map[$i]] )
								{
									image_delete($picture[$ary_map[$i]], $preview[$ary_map[$i]], $_path . $detail['match_path']);
									
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
						
						$lang_type = 'update_map';
						
						log_add(LOG_ADMIN, $log, $smode, $_sql);
						right('ERROR_BOX', langs($lang_type));
					}
					else
					{
						error('ERROR_BOX_MAPS', $error);
						log_add(LOG_ADMIN, $log, 'error', $error);
					}
				
					break;
			}
			
			$sql = "SELECT u.user_id, u.user_name, ml.user_status, ml.time_create
						FROM " . LISTS . " ml, " . USERS . " u
					WHERE ml.type_id = $data AND ml.type = " . TYPE_MLINE . " AND ml.user_id = u.user_id
					ORDER BY ml.user_status";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$member = $user_id = array();
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$member[]	= $row;
				$user_id[]	= $row['user_id'];
			}
			
			$ignore_user = ( isset($user_id) ) ? ( count($user_id) ) ? 'AND u.user_id NOT IN (' . implode(', ', $user_id) . ')' : '' : '';
			
			$sql = "SELECT u.user_id, u.user_name
						FROM " . USERS . " u, " . LISTS . " l
					WHERE l.type_id = " . $detail['team_id'] . " AND l.type = " . TYPE_TEAM . " AND l.user_id = u.user_id $ignore_user
					ORDER BY u.user_name";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$team_users = $db->sql_fetchrowset($result);
			
			if ( $member || $team_users )
			{
				if ( $member )
				{
					$template->assign_block_vars('detail.member', array());

					$cnt = count($member);
					
					for ( $i = $start; $i < min($settings['per_page_entry']['acp_userlist'] + $start, $cnt); $i++ )
					{
						$template->assign_block_vars('detail.member.row', array(
							'USER_ID'	=> $member[$i]['user_id'],
							'USERNAME'	=> $member[$i]['user_name'],
							'CREATE'	=> create_date($userdata['user_dateformat'], $member[$i]['time_create'], $userdata['user_timezone']),
							'STATUS'	=> ( !$member[$i]['user_status'] ) ? $lang['status_player'] : $lang['status_replace'],
						));
					}
					
					$s_options .= build_options(array(
						'smode'		=> false,
						'option'	=> array(
							'option'	=> 'COMMON_SELECT_OPTION',
							'status_set'	=> array(
								'player'	=> 'status_player',
								'replace'	=> 'status_replace',
							),
							'delete'	=> 'COMMON_DELETE',
						),
					));
					
					$current_page = !$cnt ? 1 : ceil($cnt/$settings['per_page_entry']['acp_userlist']);
				}
				else
				{
					$template->assign_block_vars('detail.no_row', array());
				}
				
				if ( $team_users )
				{
					$template->assign_block_vars('detail.member_create', array());
	
					$s_team_users = "<select class=\"select\" name=\"members[]\" id=\"table\" size=\"6\" multiple=\"multiple\">";
					
					foreach ( $team_users as $row )
					{
						$s_team_users .= "<option value=\"" . $row['user_id'] . "\">" . sprintf($lang['STF_SELECT_FORMAT'], $row['user_name']) . "</option>";
					}
					
					$s_team_users .= "</select>";
				}
				
				$template->assign_vars(array(
					'MEMBER_PP'	=> generate_pagination("$file&mode=$mode&id=$data", $cnt, $settings['per_page_entry']['acp_userlist'], $start),
					'MEMBER_PN'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp_userlist'] ) + 1 ), $current_page ),

					'S_OPTIONS'	=> $s_options,
					'S_USERS'	=> $s_team_users,
				));
			}
			else
			{
				$template->assign_block_vars('detail.entry_empty', array());
			}
			
			$max_maps = _max(MATCH_MAPS, 'map_order', "match_id = $data", false);
			$sql_maps = data(MATCH_MAPS, "WHERE match_id = $data", 'map_order', 1, 'set');
			
			debug($sql_maps, '$sql_maps');
			
			if ( $sql_maps )
			{
				$template->assign_block_vars('detail.map', array());
				
				$cnt = count($sql_maps);
				
				for ( $j = $start; $j < min($settings['per_page_entry']['acp_mapslist'] + $start, $cnt); $j++ )
				{
					$map_id = $sql_maps[$j]['map_id'];
					$order = $sql_maps[$j]['map_order'];
					
					$fields .= "<input type=\"hidden\" name=\"pic_picture[$map_id]\" value=\"" . $sql_maps[$j]['map_picture'] . "\" />";
					$fields .= "<input type=\"hidden\" name=\"pic_preview[$map_id]\" value=\"" . $sql_maps[$j]['map_preview'] . "\" />";
					
					$template->assign_block_vars('detail.map.row', array(
						'MAP_ID'	=> $map_id,
						'MAP_HOME'	=> $sql_maps[$j]['map_points_home'],
						'MAP_RIVAL'	=> $sql_maps[$j]['map_points_rival'],
						
						'PIC_URL'	=> ( $sql_maps[$j]['map_picture'] ) ? '<a href="' . $_path . $detail['match_path'] . '/' . $sql_maps[$j]['map_picture'] . '" rel="lightbox"><img src="' . $_path . $detail['match_path'] . '/' . $sql_maps[$j]['map_preview'] . '" alt="" /></a>' : '',
						
					#	'MOVE_UP'	=> ( $sql_maps[$j]['map_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=detail&amp;$url=$data&amp;order=1&amp;move=-15&amp;$url_pic=$map_id") . '"><img src="' . $jmages['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $jmages['icon_arrow_u2'] . '" alt="" />',
					#	'MOVE_DOWN'	=> ( $sql_maps[$j]['map_order'] != $max ) ? '<a href="' . check_sid("$file?mode=detail&amp;$url=$data&amp;order=1&amp;move=+15&amp;$url_pic=$map_id") . '"><img src="' . $jmages['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $jmages['icon_arrow_d2'] . '" alt="" />',
						
						'MOVE_UP'	=> ( $order != '1' ) ? href('a_img', $file, array('mode' => 'detail', 'move' => '-15', 'id' => $data, 'p' => $map_id), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
						'MOVE_DOWN'	=> ( $order != $max_maps ) ? href('a_img', $file, array('mode' => 'detail', 'move' => '+15', 'id' => $data, 'p' => $map_id), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
	
						
						'S_MAP'		=> select_map($detail['team_id'], $sql_maps[$j]['map_id'], $sql_maps[$j]['map_name']),
						'S_ROUND'	=> match_round('selectsmall', $map_id, $sql_maps[$j]['map_round']),
					));
					
					( $sql_maps[$j]['map_picture'] ) ? $template->assign_block_vars('detail.map.row.delete', array()) : '';
				}
				
				$current_page = !$cnt ? 1 : ceil($cnt/$settings['per_page_entry']['acp_mapslist']);
				
				$template->assign_vars(array(
					'MAP_PP'	=> generate_pagination("$file&mode=$mode&id=$data", $cnt, $settings['per_page_entry']['acp_mapslist'], $start),
					'MAP_PN'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp_userlist'] ) + 1 ), $current_page ),
				));
			}
			
			$cats = data(MAPS, "WHERE map_tag = '" . $detail['game_tag'] . "'", 'map_order ASC', 1, 'row');
			
			debug($cats, 'cats');
			
			
			$maps = $cats ? data(MAPS, "WHERE main = " . $cats['map_id'], 'map_order ASC', 1, 'set') : '';
			
		#	debug($cats, 'cats');
			debug($maps, 'maps');
		
			$s_maps = '';
			
			if ( $maps )
			{
				$s_maps .= "<select class=\"selectsmall\" name=\"map_name[]\" id=\"map_name\">";
				$s_maps .= "<option selected=\"selected\" value=\"0\">" . sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_SELECT_MAP']) . "</option>";
				
				$cat_id		= $cats['map_id'];
				$cat_name	= $cats['map_name'];
				
				$s_map = '';
				
				foreach ( $maps as $row )
				{
					$map_id		= $row['map_id'];
					$map_cat	= $row['main'];
					$map_name	= $row['map_name'];
					
					$s_map .= ( $cat_id == $map_cat ) ? "<option value=\"$map_id\">" . sprintf($lang['STF_SELECT_FORMAT'], $map_name) . "</option>" : '';
				}
				
				$s_maps .= ( $s_map != '' ) ? "<optgroup label=\"$cat_name\">$s_map</optgroup>" : '';
				$s_maps .= "</select>";
			}
			else
			{
				$s_maps = sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_MAPS_NONE']);
			}
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$upload = build_fields(array('smode' => 'upload'));
			$update = build_fields(array('smode' => 'update'));
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['input_data'], $lang['input_data']);
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['stf_detail'], $lang['TITLE'], $detail['match_rival_name']),
				
			#	'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['match']),
			#	'L_INPUT'			=> sprintf($lang['STF_UPDATE'], $lang['match'], $detail['match_rival_name']),
				
			#	'L_OPTION'			=> href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['input_data'], $lang['input_data']),
				
				'L_DETAIL'			=> $lang['input_details'],
				
			#	'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['match']),
			#	'L_INPUT'			=> sprintf($lang['STF_UPDATE'], $lang['match'], $detail['match_rival_name']),
			#	'L_DETAIL'			=> $lang['match_details'],
			#	'L_EXPLAIN'			=> $lang['match_details_explain'],
			
				'L_CREATE'			=> $lang['joined'],
		
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
			#	'L_IMAGE_DELETE'	=> $lang['COMMON_IMAGE_DELETE'],
			#	'L_LINEUP_PLAYER'	=> $lang['status_player'],
			#	'L_LINEUP_REPLACE'	=> $lang['status_replace'],
			#	'L_LINEUP_PLAYER'	=> $lang['lineup_player'],
				
				'L_ADD'		=> $lang['common_add'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
			
				'S_MAP'		=> $s_maps,
			#	'S_USERS'	=> $s_team_users,
				
				
			
			#	'S_INPUT'	=> check_sid("$file?mode=update&amp;$url=$data"),
				
			#	'S_ACTION'	=> check_sid("$file&id=$data"),
			
				
				
				'S_UPLOAD'	=> $upload,
				'S_UPDATE'	=> $update,
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			

			break;
			
		case 'rival':
		
			$template->assign_block_vars('rival', array());
		
			$sql = "SELECT DISTINCT match_rival_name, match_rival_tag, match_rival_url, match_rival_logo FROM " . MATCH;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$tmp_rival = $db->sql_fetchrowset($result);
			
			$cnt = count($tmp_rival);
			
			for ( $i = $start; $i < min($settings['ppe_acp'] + $start, $cnt); $i++ )
			{
				$template->assign_block_vars('rival.row', array(
					'NAME'		=> $tmp_rival[$i]['match_rival_name'],
					'TAG'		=> $tmp_rival[$i]['match_rival_tag'],
					'URL'		=> $tmp_rival[$i]['match_rival_url'],
					'LOGO'		=> $tmp_rival[$i]['match_rival_logo'],
					'UPDATE'	=> href('a_img', $file, array('mode' => 'change', 'id' => $i), 'icon_update', 'COMMON_UPDATE'),
				));
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['stf_rival'], $lang['TITLE'], $lang['rival_overview']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;	
		
		case 'change':
		
			$template->assign_block_vars('change', array());
			
			$vars = array(
				'match' => array(
					'title1'			=> 'input_rival',
					'match_rival_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'ajax:25;25',	'required' => 'input_rival', 'params' => 'rival'),
					'match_rival_tag'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50', 'required' => 'input_clantag'),
					'match_rival_url'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
					'match_rival_logo'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50'),
				),
			);
			
			if ( !$submit )
			{
				$sql = "SELECT DISTINCT match_rival_name, match_rival_tag, match_rival_url, match_rival_logo FROM " . MATCH . " ";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$tmp_rival = $db->sql_fetchrowset($result);
			
				$sql = "SELECT match_id, match_rival_name, match_rival_tag, match_rival_url, match_rival_logo FROM " . MATCH . " WHERE match_rival_name LIKE '" . $tmp_rival[$data]['match_rival_name'] . "'";
				$sql .= $tmp_rival[$data]['match_rival_tag'] ? " AND match_rival_tag LIKE '" . $tmp_rival[$data]['match_rival_tag'] . "'" : ' AND match_rival_tag = ""';
				$sql .= $tmp_rival[$data]['match_rival_url'] ? " AND match_rival_url LIKE '" . $tmp_rival[$data]['match_rival_url'] . "'" : ' AND match_rival_url = ""';
				$sql .= $tmp_rival[$data]['match_rival_logo'] ? " AND match_rival_logo LIKE '" . $tmp_rival[$data]['match_rival_logo'] . "'" : ' AND match_rival_logo = ""';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$data_sql_ids[] = $row['match_id'];
					$data_sql = $row;
				}
				$db->sql_freeresult($result);
			#	unset($data_sql['match_id']);
				$data_sql['match_id'] = implode(', ', $data_sql_ids);
			
				
				
				debug($data_sql, 'data_sql1');
			}
			else
			{
				$data_sql = build_request(MATCH, $vars, $error, $mode);
				debug($data_sql, 'data_sql2');
				if ( !$error )
				{
				#	if ( $mode == 'create' && acl_auth('a_game_create') )
				#	{
				#		$data_sql['game_order'] = _max(GAMES, 'game_order', false);
				#		
				#		$sql = sql(GAMES, $mode, $data_sql);
				#		$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $_top);
				#	}
				#	else if ( acl_auth('a_game') )
				#	{
						$sql = sql(MATCH, $mode, $data_sql, 'match_id', $data_sql_ids);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
				#	}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output(MATCH, $vars, $data_sql, 'change');
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_' . strtoupper($mode)], $lang['TITLE']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'	=> $fields,
			));
			
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
			
			debug($matchs, 'matchs');
			
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
				$sql_maps = $db->sql_fetchrowset($result);
				$count_maps = count($sql_maps);
				
				$_files = $_picture = $_preview = $tmp_files = '';
				
				$dir_check = ( is_dir($_path . $matchs[$i]['match_path']) ) ? img('i_icon', 'folder', 'folder') : img('i_icon', 'folder_bug', 'folder_bug');
				$dir_write = ( is_writable($_path . $matchs[$i]['match_path']) ) ? img('i_icon', 'folder_edit', 'folder_edit') : img('i_icon', 'folder_error', 'folder_error');
				
				if ( is_dir($_path . $matchs[$i]['match_path']) )
				{
					$path_files = scandir($_path . $matchs[$i]['match_path']);
					
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
				
				if ( is_dir($_path . $matchs[$i]['match_path']) && $_picture )
				{
					$tmp_files[$matchs[$i]['match_id']] = match_check_image($_path . $matchs[$i]['match_path'], $count_maps, $_picture, $sql_maps);
				}
				
				if ( $sql_maps )
				{
					$points_home = $points_rival = 0;
					
					for ( $j = 0; $j < $count_maps; $j++ )
					{
						$pic_confirm = $pre_confirm = $lang['COMMON_NO'];
						
						if ( $matchs[$i]['match_id'] == $sql_maps[$j]['match_id'] )
						{
							if ( isset($tmp_files[$sql_maps[$j]['match_id']]) )
							{
								$pic_confirm = isset($tmp_files[$matchs[$i]['match_id']]['in']['picture']) ? ( in_array($sql_maps[$j]['map_picture'], $tmp_files[$matchs[$i]['match_id']]['in']['picture']) ) ? $lang['COMMON_YES'] : $lang['COMMON_NO'] : $lang['COMMON_NO'];
								$pre_confirm = isset($tmp_files[$matchs[$i]['match_id']]['in']['preview']) ? ( in_array($sql_maps[$j]['map_preview'], $tmp_files[$matchs[$i]['match_id']]['in']['preview']) ) ? $lang['COMMON_YES'] : $lang['COMMON_NO'] : $lang['COMMON_NO'];
							}
							
							$points_home += $sql_maps[$j]['map_points_home'];
							$points_rival += $sql_maps[$j]['map_points_rival'];
							
							$template->assign_block_vars('sync.row.maps', array(
								'NAME'		=> $sql_maps[$j]['match_map_name'],
								'PICTURE'	=> $pic_confirm,
								'PREVIEW'	=> $pre_confirm,
								'HOME'		=> $sql_maps[$j]['map_points_home'],
								'RIVAL'		=> $sql_maps[$j]['map_points_rival'],
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

			break;
			
		case 'delete':
		
			$data_sql = data(MATCH, $data, false, 1, 'row');
			
			if ( $data && $accept && $userauth['a_match_delete'] )
			{
				$file = ( $index ) ? check_sid('index.php') : check_sid($file);
				$name = ( $index ) ? $lang['acp_overview'] : $_top;
				
				$sql = sql(MATCH, $mode, $data_sql, 'match_id', $data);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $name);
				
			#	sql(MATCH_MAPS, $mode, $data_sql, 'match_id', $data);
			#	sql(MATCH_USERS, $mode, $data_sql, 'match_id', $data);
			#	sql(MATCH_LINEUP, $mode, $data_sql, 'match_id', $data);
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept && $userauth['a_match_delete'] )
			{
				$fields .= build_fields(array(
					'mode'		=> $mode,
					'id'		=> $data,
					'index'	=> $index,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['match_rival_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}
		
			break;
			
		default:
		
			$template->assign_block_vars('display', array());
			
			$upcoming = $cnt_upcoming = $expired = $cnt_expired = '';
			$cnt = 0;
			$current_page = 1;
			
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
			$sqlout = $db->sql_fetchrowset($result);
			
			if ( !$sqlout )
			{
				$template->assign_block_vars('display.upcoming_none', array());
				$template->assign_block_vars('display.expired_none', array());
			}
			else
			{
				foreach ( $sqlout as $match => $row )
				{
					if ( $row['match_date'] > time() )
					{
						$upcoming[] = $row;
					}
					else if ( $row['match_date'] < time() )
					{
						$expired[] = $row;
					}
				}
				
				if ( !$upcoming )
				{
					$template->assign_block_vars('display.upcoming_none', array());
				}
				else
				{
					foreach ( $upcoming as $row )
					{
						$id		= $row['match_id'];
						$rival	= $row['match_rival_name'];
						$team	= $row['team_id'];
						$public	= $row['match_public'] ? 'STF_MATCH_NAME' : 'STF_MATCH_INTERN';
						
						$template->assign_block_vars('display.upcoming', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), sprintf($lang[$public], $rival), ''),
							'GAME'		=> display_gameicon($row['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $row['match_date'], $userdata['user_timezone']),
							'TRAINING'	=> ( !$row['training_id'] ) ? href('a_img', 'admin_training.php?i=2', array('mode' => 'create', 'team' => $team, 'id' => $id, 'vs' => $rival), 'icon_match_add', 'sm_training') : href('a_img', 'admin_training.php', array('mode' => 'list', 'team' => $team), 'icon_match', 'icon_match'),
							
							'DETAIL'	=> href('a_img', $file, array('mode' => 'detail', 'id' => $id), 'icon_details', 'common_details'),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
				
				if ( !$expired )
				{
					$template->assign_block_vars('display.expired_none', array());
				}
				else
				{
					$cnt = count($expired);
					
					for ( $i = $start; $i < min($settings['ppe_acp'] + $start, $cnt); $i++ )
					{
						$id		= $expired[$i]['match_id'];
						$rival	= $expired[$i]['match_rival_name'];
						$team	= $expired[$i]['team_id'];
						$public	= $expired[$i]['match_public'] ? 'STF_MATCH_NAME' : 'STF_MATCH_INTERN';
						
						$template->assign_block_vars('display.expired', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), sprintf($lang[$public], $rival), ''),
							
							'GAME'		=> display_gameicon($expired[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $expired[$i]['match_date'], $userdata['user_timezone']),
							
							'DETAIL'	=> href('a_img', $file, array('mode' => 'detail', 'id' => $id), 'icon_details', 'common_details'),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
			}
			
			$current_page = ( !$cnt ) ? 1 : ceil($cnt/$settings['ppe_acp']);
			
			$fields .= build_fields(array('mode' => 'create'));
			
			$sqlteams = "SELECT * FROM " . TEAMS . " ORDER BY team_order";
			if ( !($result = $db->sql_query($sqlteams)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
		#	$sort[] = href((($t_id != 0) ? 'AHREF_TXT' : 'AHREF_TXT_B'), $file, array('t_id' => 0), $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$sort[] = href((($t_id != $row['team_id']) ? 'AHREF_TXT' : 'AHREF_TXT_B'), $file, array('t_id' => $row['team_id']), $row['team_name'], $row['team_name']);
			}
					
			$sort = implode(', ', $sort);
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['match']),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['match']),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_SORT'	=> sprintf($lang['STF_COMMON_SORT'], $sort),
				
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				
				'L_UPCOMING'	=> $lang['UPCOMING'],
				'L_EXPIRED'		=> $lang['EXPIRED'],
			#	'L_DETAILS'		=> $lang['common_details'],
				
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['ppe_acp'] ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination($file . (empty($t_id) ? '' : "&t_id=$t_id"), $cnt, $settings['ppe_acp'], $start ),
				
				#s_team($tmp_data, $tmp_meta, $tmp_name, 'request')
			#	'S_SORT'		=> s_team($t_id, '', 't_id', 'submit', 'selectsmall'),
				'S_TEAM'		=> s_team($t_id, '', 't_id', false, 'selectsmall'),
				
				'S_ACTION'		=> check_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>