<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_server',
		'cat'       => 'clan',
		'modes'		=> array(
			'server'	=> array('title' => 'acp_server'),
			'gameq'		=> array('title' => 'acp_gameq'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_server';
	
	include('./pagestart.php');
	include($root_path . 'includes/class_gameq.php');
	
	add_lang('server');
	acl_auth(array('a_server', 'a_server_create', 'a_server_delete', 'a_servertype'));

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_SERVER;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept = request('accept', TYP);
	$action	= request('action', TYP);
	
	$acp_title	= sprintf($lang['stf_header'], (($action == 'server') ? $lang['title'] : $lang['gameq_title']));
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
	
	$mode = (in_array($mode, array('create', 'update', 'server_list', 'gameq_create', 'gameq_update', 'gameq_list', 'move_down', 'move_up', 'delete'))) ? $mode : false;
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';

	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'server' => array(
					'title'	=> 'data_input',
					'server_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'server_type'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('gameq', false, 'server_game')),
					'server_game'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:gameq', 'params' => 'server_type', 'required' => 'input_game'),
					'server_ip'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_ip'),
					'server_port'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:10;10', 'required' => 'input_port'),
					'server_pw'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:10;10'),
					'server_live'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'server_list'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'server_show'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'server_own'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'server_order'	=> 'hidden',
				)
			);
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
						
			if ( $mode == 'create' && !$submit && $userauth['a_server_create'] )
			{
				$name	= ( isset($_POST['game_name']) ) ? request('game_name', TXT) : request('voice_name', TXT);
				$typ	= ( isset($_POST['game_name']) ) ? 0 : 1;
				
				$data_sql = array(
					'server_name'	=> $name,
					'server_type'	=> $typ,
					'server_game'	=> '',
					'server_ip'		=> '',
					'server_port'	=> '',
					'server_live'	=> '',
					'server_pw'		=> '',
					'server_list'	=> 1,
					'server_show'	=> 1,
					'server_own'	=> 1,
					'server_order'	=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(SERVER, $data, false, 1, true);
			}
			else
			{
				$data_sql = build_request(SERVER, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'create' && $userauth['a_server_create'] )
					{
						$data_sql['server_order'] = maxa(SERVER, 'server_order', false);
						
						$sql = sql(SERVER, $mode, $data_sql);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else if ( $userauth['a_server'] )
					{
						$sql = sql(SERVER, $mode, $data_sql, 'server_id', $data);
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
			
			build_output(SERVER, $vars, $data_sql);
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['server_name']),
				'L_EXPLAIN'	=> $lang['com_required'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'server_list':
		
			$template->assign_block_vars($mode, array());
			
			$vars = array(
				'server_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:20;25', 'required' => 'input_name'),
				'server_ip'		=> array('validate' => INT,	'explain' => false,	'type' => 'text:15;25', 'required' => 'input_ip'),
				'server_port'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:6;10',	'required' => 'input_port'),
				'server_pw'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:6;10'),
				'server_live'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:server_live'),
				'server_list'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:server_list'),
				'server_show'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:server_show'),
				'server_own'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:server_own'),
			);
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
			
			if ( !$submit )
			{
				$data_sql = data(SERVER, "WHERE server_type = $type", 'server_order ASC', 1, false);				
			}
			else
			{
				$data_sql = build_request_list(SERVER, $vars, $error, 'server_id');
				
				if ( !$error )
				{
					if ( $data_sql )
					{
						foreach ( $data_sql as $key => $row )
						{
							foreach ( $row as $name => $info )
							{
								$ary[$key][] = "$name = '$info'";
							}
							$implode = implode(', ', $ary[$key]);
						
							$sql = "UPDATE " . SERVER . " SET $implode WHERE server_id = $key";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
						
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&type=$type"));
					}
					else
					{
						$msg = $lang['empty'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&type=$type"));
					}
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output_list(SERVER, $vars, $data_sql, $mode, 'title');
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'type'	=> $type,
			));
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_' . substr($mode, strpos($mode, '_')+1)], $lang['title']),
				'L_EXPLAIN'	=> $lang['com_required'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),
				'L_LANG'	=> $lang['data_input'],
			
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'delete':
		
			$data_sql = data(SERVER, $data, false, 1, true);
		
			if ( $data && $confirm )
			{
				$sql = sql(SERVER, $mode, $data_sql, 'server_id', $data);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				orders(SERVER);
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['com_confirm'],
					'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['server_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}
			
			break;
			
		case 'gameq_create':
		case 'gameq_update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'gameq' => array(
					'title1' => 'input_data',
					'gameq_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'gameq_game'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_game'),
					'gameq_dport'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:10;10', 'required' => 'input_dport'),
					'gameq_type'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type'),
					'gameq_viewer'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
				)
			);
			
			if ( $mode == 'gameq_create' && !$submit )
		#	if ( $mode == 'gameq_create' && !$submit && $userauth['a_servertype'] )
			{
				$data_sql = array(
					'gameq_name'	=> request('gameq_name', TXT),
					'gameq_game'	=> '',
					'gameq_dport'	=> '',
					'gameq_type'	=> '',
					'gameq_viewer'	=> '',
				);
			}
			else if ( $mode == 'gameq_update' && !$submit )
			{
				$data_sql = data(GAMEQ, $data, false, 1, true);
			}
			else
			{
				$data_sql = build_request(GAMEQ, $vars, $error, substr($mode, strpos($mode, '_')+1));
				
				if ( !$error )
				{
					if ( $mode == 'gameq_create' )
				#	if ( $mode == 'gameq_create' && $userauth['a_servertype'] )
					{
						$sql = sql(GAMEQ, $mode, $data_sql);
						$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(GAMEQ, $mode, $data_sql, 'gameq_id', $data);
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
					}
					
					log_add(LOG_ADMIN, $log, substr($mode, strpos($mode, '_')+1), $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output(GAMEQ, $vars, $data_sql);
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_' . substr($mode, strpos($mode, '_')+1)], $lang['gameq_title'], $data_sql['gameq_name']),
				'L_EXPLAIN'	=> $lang['com_required'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),
			
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'gameq_list':
		
			$template->assign_block_vars($mode, array());
			
			$vars = array(
				'gameq_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
				'gameq_game'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_game'),
				'gameq_dport'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:10;10', 'required' => 'input_dport'),
				'gameq_viewer'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:gameq_type'),
			);
			
			if ( !$submit )
			{
				$data_sql = data(GAMEQ, "WHERE gameq_type = $type", 'gameq_id ASC', 1, false);
			}
			else
			{
				$data_sql = build_request_list(GAMEQ, $vars, $error, 'gameq_id');
			#	$data_sql = build_request_list(GAMEQ, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $data_sql )
					{
						foreach ( $data_sql as $key => $row )
						{
							foreach ( $row as $name => $info )
							{
								$ary[$key][] = "$name = '$info'";
							}
							$implode = implode(', ', $ary[$key]);
						
							$sql = "UPDATE " . GAMEQ . " SET $implode WHERE gameq_id = $key";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
						
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&type=$type"));
					}
					else
					{
						$msg = $lang['empty'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&type=$type"));
					}
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output_list(GAMEQ, $vars, $data_sql, $mode, 'gameq_title');
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'type'	=> $type,
			));
		
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_' . substr($mode, strpos($mode, '_')+1)], $lang['gameq_title']),
				'L_EXPLAIN'	=> $lang['com_required'],
			
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'gameq_delete':
		
			$data_sql = data(SERVER_TYPE, $data, false, 1, true);
		
			if ( $data && $confirm )
			{
				$sql = sql(SERVER_TYPE, $mode, $data_sql, 'gameq_id', $data);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				orders(SERVER_TYPE, '-1');
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['com_confirm'],
					'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['gameq_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}
			
			break;
			
		case 'move_up':
		case 'move_down':
		
			move(SERVER, $mode, $order);
			log_add(LOG_ADMIN, $log, $mode);
		
		default:
		
			switch ( $action )
			{
				case 'server':
				
					$template->assign_block_vars('display', array());
					
					$fields = build_fields(array('mode' => 'create'));
					
					$game	= data(SERVER, 'WHERE server_type = 0', 'server_order ASC', 1, false);
					$voice	= data(SERVER, 'WHERE server_type = 1', 'server_order ASC', 1, false);
					$live	= data(SERVER, 'WHERE server_live = 1', 'server_order ASC', 1, false);
					
					if ( $live )
					{
						$online = array();
						
						foreach ( $live as $row )
						{
							$online[] = array('type' => $row['server_game'], 'host' => $row['server_ip'] . ':' . $row['server_port'], 'id' => $row['server_id']);
						}
						
						if ( $online )
						{
							$gq_server = cached_gameq($online, 'data_servers', 240);
						}
					}
					
					if ( !$game )
					{
						$template->assign_block_vars('display.game_empty', array());
					}
					else
					{
						$max = count($game);
						
						foreach ( $game as $row )
						{
							$id		= $row['server_id'];
							$name	= $row['server_name'];
							$game	= $row['server_game'];
							$type	= $row['server_type'];
							$live	= $row['server_live'];
							$order	= $row['server_order'];
							
							$u_cur	= ( isset($gq_server[$id]['gq_numplayers']) ) ? ((count($gq_server[$id]['players']) >= 1) ? $gq_server[$id]['gq_numplayers'] : 0) : 0;
							$u_max	= ( isset($gq_server[$id]['gq_maxplayers']) ) ? $gq_server[$id]['gq_maxplayers'] : 0;
							
							$template->assign_block_vars('display.game_row', array(
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, ''),
								'TYPE'		=> $type ? img('i_icon', 'icon_sound', '') : img('i_icon', 'icon_match', ''),
								'USERS'		=> ( isset($gq_server[$id]['gq_online']) && $gq_server[$id]['gq_online'] ) ? sprintf($lang['cur_max'], $u_cur, $u_max) : '',
								'STATUS'	=> ( isset($gq_server[$id]['gq_online']) && $gq_server[$id]['gq_online'] ) ? $lang['online'] : $lang['offline'],
								
								'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down', 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
							));
						}
					}
					
					if ( !$voice )
					{
						$template->assign_block_vars('display.voice_empty', array());
					}
					else
					{
						$max = count($voice);
						
						foreach ( $voice as $row )
						{
							$id		= $row['server_id'];
							$name	= $row['server_name'];
							$game	= $row['server_game'];
							$type	= $row['server_type'];
							$live	= $row['server_live'];
							$order	= $row['server_order'];
							
							$u_cur	= ( isset($gq_server[$id]['gq_numplayers']) ) ? ((count($gq_server[$id]['players']) >= 1) ? $gq_server[$id]['gq_numplayers'] : 0) : 0;
							$u_max	= ( isset($gq_server[$id]['gq_maxplayers']) ) ? $gq_server[$id]['gq_maxplayers'] : 0;
							
							$template->assign_block_vars('display.voice_row', array(
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, ''),
								'TYPE'		=> $type ? img('i_icon', 'icon_sound', '') : img('i_icon', 'icon_match', ''),
								'USERS'		=> ( isset($gq_server[$id]['gq_online']) && $gq_server[$id]['gq_online'] ) ? sprintf($lang['cur_max'], $u_cur, $u_max) : '',
								'STATUS'	=> ( isset($gq_server[$id]['gq_online']) && $gq_server[$id]['gq_online'] ) ? $lang['online'] : $lang['offline'],
								
								'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down', 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
							));
						}
					}
				
					break;
					
				case 'gameq':
				
					$template->assign_block_vars('gameq', array());
			
					$fields			= '<input type="hidden" name="mode" value="gameq_create" />';
					
					$gameq_game		= data(GAMEQ, 'WHERE gameq_type = 0', 'gameq_id ASC', 1, false);
					$gameq_voice	= data(GAMEQ, 'WHERE gameq_type = 1', 'gameq_id ASC', 1, false);
							
					if ( !$gameq_game )
					{
						$template->assign_block_vars('gameq.game_empty', array());
					}
					else
					{
						foreach ( $gameq_game as $row )
						{
							$id		= $row['gameq_id'];
							$name	= $row['gameq_name'];
							
							$template->assign_block_vars('gameq.game_row', array(
								'NAME'		=> href('a_txt', $file, array('mode' => 'gameq_update', 'id' => $id), $name, $name),
							
								'GAME'		=> $row['gameq_game'],
								'DPORT'		=> $row['gameq_dport'],
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'gameq_update', 'id' => $id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'gameq_delete', 'id' => $id), 'icon_cancel', 'com_delete'),
							));
						}
					}
					
					if ( !$gameq_voice )
					{
						$template->assign_block_vars('gameq.voice_empty', array());
					}
					else
					{
						foreach ( $gameq_voice as $row )
						{
							$id		= $row['gameq_id'];
							$name	= $row['gameq_name'];
							
							$template->assign_block_vars('gameq.voice_row', array(
								'NAME'		=> href('a_txt', $file, array('mode' => 'gameq_update', 'id' => $id), $name, $name),
							
								'GAME'		=> $row['gameq_game'],
								'DPORT'		=> $row['gameq_dport'],
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'gameq_update', 'id' => $id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'gameq_delete', 'id' => $id), 'icon_cancel', 'com_delete'),
							));
						}
					}
					
					$template->assign_vars(array(
						
					));
				
					break;
			}
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_header'], (($action == 'server') ? $lang['title'] : $lang['gameq_title'])),
				'L_CREATE'	=> sprintf($lang['stf_create'], (($action == 'server') ? $lang['title'] : $lang['gameq_title'])),
				
				'L_EXPLAIN'	=> (($action == 'server') ? $lang['explain'] : $lang['gameq_explain']),
				'L_GAME'	=> $lang['typ_game'],
				'L_VOICE'	=> $lang['typ_voice'],
				
				'LIST_GAME'		=> href('a_txt', $file, array('mode' => (($action == 'server') ? 'server_list' : 'gameq_list'), 'type' => 0), $lang['all_update'], $lang['all_update']),
				'LIST_VOICE'	=> href('a_txt', $file, array('mode' => (($action == 'server') ? 'server_list' : 'gameq_list'), 'type' => 1), $lang['all_update'], $lang['all_update']),
		
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>