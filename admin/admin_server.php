<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_SERVER',
		'CAT'		=> 'CLAN',
		'MODES'		=> array(
			'SERVER'	=> array('TITLE' => 'ACP_SERVER'),
			'GAMEQ'		=> array('TITLE' => 'ACP_GAMEQ'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$current = $log = 'ACP_SERVER';
	
	include('./pagestart.php');
	
	add_lang('server');
	add_tpls('acp_server');
	acl_auth(array('A_SERVER', 'A_SERVER_ASSORT', 'A_SERVER_CREATE', 'A_SERVER_DELETE', 'A_SERVER_TYPE'));
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$file	= basename(__FILE__) . $iadds;
	$path	= $root_path . $settings['path']['games'];
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept = request('accept', TYP);
	$action	= request('action', TYP);
	
	switch ( $action )
	{
		case 'server':	$table = SERVER;	$langs = 'SERVER';	$fields = 'server';	$orders = true;
		case 'gameq':	$table = GAMEQ;		$langs = 'GAMEQ';	$fields = 'gameq';	$orders = false;
	}
	
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$cancel = ( isset($_POST['cancel']) ) ? redirect('admin/' . check_sid($file)) : false;
	
	$mode	= ( in_array($mode, array('create', 'update', 'server_list', 'gameq_create', 'gameq_update', 'gameq_list', 'move_down', 'move_up', 'delete')) ) ? $mode : false;
	
	$_top	= sprintf($lang['STF_HEADER'], $lang["{$langs}_TITLE"]);
	$_tpl	= ($mode === 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'server' => array(
					'title'	=> 'INPUT_DATA',
					'server_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'server_type'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('gameq', false, 'server_game')),
					'server_game'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:gameq', 'params' => 'server_type', 'required' => 'select_game'),
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
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
						
			if ( $mode == 'create' && !$submit && $userauth['A_SERVER_CREATE'] )
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
				$data_sql = data(SERVER, $data, false, 1, 'row');
			}
			else
			{
				$data_sql = build_request(SERVER, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'create' && $userauth['A_SERVER_CREATE'] )
					{
						$data_sql['server_order'] = _max(SERVER, 'server_order', false);
						
						$sql = sql(SERVER, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else if ( $userauth['A_SERVER'] )
					{
						$sql = sql(SERVER, $mode, $data_sql, 'server_id', $data);
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['server_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'server_list':
		
			$template->assign_block_vars($mode, array());
			
			$vars = array(
				'server_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:20;25', 'required' => 'input_name'),
				'server_ip'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:15;25', 'required' => 'input_ip'),
				'server_port'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:6;10',	'required' => 'input_port'),
				'server_pw'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:6;10'),
				'server_live'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:server_live'),
				'server_list'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:server_list'),
				'server_show'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:server_show'),
				'server_own'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:server_own'),
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			if ( !$submit && $userauth['A_SERVER'] )
			{
				$data_sql = data(SERVER, "WHERE server_type = $type", 'server_order ASC', 1, 'set');				
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
						
						$msg = $lang['UPDATE'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&type=$type"));
					}
					else
					{
						$msg = $lang['empty'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&type=$type"));
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
				'L_HEADER'	=> msg_head(exp_mode($mode), $lang['SERVER_TITLE'], ''),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'gameq_create':
		case 'gameq_update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'gameq' => array(
					'title' => 'INPUT_DATA2',
					'gameq_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'gameq_game'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_game'),
					'gameq_dport'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:10;10', 'required' => 'input_dport'),
					'gameq_type'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type'),
					'gameq_viewer'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
				)
			);
			
			if ( $mode == 'gameq_create' && !$submit && $userauth['A_SERVER_TYPE'] )
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
				$data_sql = data(GAMEQ, $data, false, 1, 'row');
			}
			else
			{
				$data_sql = build_request(GAMEQ, $vars, $error, substr($mode, strpos($mode, '_')+1));
				
				if ( !$error )
				{
					if ( $mode == 'gameq_create' && $userauth['A_SERVER_TYPE'] )
					{
						$sql = sql(GAMEQ, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs(exp_mode($mode)), check_sid($file), $_top);
					}
					else if ( $userauth['A_SERVER_TYPE'] )
					{
						$sql = sql(GAMEQ, $mode, $data_sql, 'gameq_id', $data);
						$msg = sprintf($lang['RETURN_UPDATE'], langs(exp_mode($mode)), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head(exp_mode($mode), $lang['GAMEQ_TITLE'], $data_sql['gameq_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
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
				$data_sql = data(GAMEQ, "WHERE gameq_type = $type", 'gameq_id ASC', 1, 'set');
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
						
						$msg = $lang['UPDATE'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&type=$type"));
					}
					else
					{
						$msg = $lang['empty'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&type=$type"));
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
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
		
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head(exp_mode($mode), $lang['GAMEQ_TITLE'], ''),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'delete':
		
			switch ( $action )
			{
				case 'server': $_del = array('server_id', SERVER, 'server_name'); $orders = true;	break;
				case 'gameq': $_del = array('gameq_id', GAMEQ, 'gameq_name'); $orders = false;	break;
			}
		
			$sqlout = data($_del[1], $data, false, 1, 'row');

			if ( $data && $accept )
			{
				$sql = sql($_del[1], $mode, $sqlout, $_del[0], $data);
				$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);

				if ( $orders )
				{
					orders($_del[1]);
				}

				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $sqlout[$_del[2]]),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}

			break;
			
		case 'move_up':
		case 'move_down':
		
			move(SERVER, $mode, $order, false, 'server_type', $type);
			log_add(LOG_ADMIN, $log, $mode);
		
		default:
		
			switch ( $action )
			{
				case 'server':
				
					$template->assign_block_vars('display', array());
					
					$fields = build_fields(array('mode' => 'create'));
					
					$sqlout_g	= data(SERVER, 'WHERE server_type = 0', 'server_order ASC', 1, 'set');
					$sqlout_v	= data(SERVER, 'WHERE server_type = 1', 'server_order ASC', 1, 'set');
					$sqlout_l	= data(SERVER, 'WHERE server_live = 1', 'server_order ASC', 1, 'set');
					
				#	if ( $sqlout_l )
				#	{
				#		$online = array();
				#		
				#		foreach ( $sqlout_l as $row )
				#		{
				#			$online[] = array('type' => $row['server_game'], 'host' => $row['server_ip'] . ':' . $row['server_port'], 'id' => $row['server_id']);
				#		}
				#		
				#		if ( $online )
				#		{
				#			$gq_server = cached_gameq($online, 'data_servers', 240);
				#		}
				#	}
					
					if ( !$sqlout_g )
					{
						$template->assign_block_vars('display.game_empty', array());
					}
					else
					{
						$max = count($sqlout_g);
						
						foreach ( $sqlout_g as $row )
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
								'USERS'		=> ( isset($gq_server[$id]['gq_online']) && $gq_server[$id]['gq_online'] ) ? sprintf($lang['CURRENT_MAX'], $u_cur, $u_max) : '',
								'STATUS'	=> ( isset($gq_server[$id]['gq_online']) && $gq_server[$id]['gq_online'] ) ? $lang['ONLINE'] : $lang['OFFLINE'],
								
								'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order, 'type' => 0), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down', 'order' => $order, 'type' => 0), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							));
						}
					}
					
					if ( !$sqlout_v )
					{
						$template->assign_block_vars('display.voice_empty', array());
					}
					else
					{
						$max = count($sqlout_v);
						
						foreach ( $sqlout_v as $row )
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
								'USERS'		=> ( isset($gq_server[$id]['gq_online']) && $gq_server[$id]['gq_online'] ) ? sprintf($lang['CURRENT_MAX'], $u_cur, $u_max) : '',
								'STATUS'	=> ( isset($gq_server[$id]['gq_online']) && $gq_server[$id]['gq_online'] ) ? $lang['ONLINE'] : $lang['OFFLINE'],
								
								'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order, 'type' => 1), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down', 'order' => $order, 'type' => 1), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							));
						}
					}
				
					break;
					
				case 'gameq':
				
					$template->assign_block_vars('gameq', array());
					
					$fields			= build_fields(array('mode' => 'gameq_create'));
			
					$gameq_game		= data(GAMEQ, 'WHERE gameq_type = 0', 'gameq_id ASC', 1, 'set');
					$gameq_voice	= data(GAMEQ, 'WHERE gameq_type = 1', 'gameq_id ASC', 1, 'set');
							
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
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'gameq_update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
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
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'gameq_update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							));
						}
					}
					
					break;
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang["{$langs}_TITLE"]),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang["{$langs}_TITLE"]),
				
				'L_EXPLAIN'	=> $lang["{$langs}_TITLE"],
				'L_GAME'	=> $lang['TYP_GAME'],
				'L_VOICE'	=> $lang['TYP_VOICE'],
				
				'LIST_GAME'		=> href('a_txt', $file, array('mode' => (($action == 'server') ? 'server_list' : 'gameq_list'), 'type' => 0), $lang['COMMON_ALL_UPDATE'], $lang['COMMON_ALL_UPDATE']),
				'LIST_VOICE'	=> href('a_txt', $file, array('mode' => (($action == 'server') ? 'server_list' : 'gameq_list'), 'type' => 1), $lang['COMMON_ALL_UPDATE'], $lang['COMMON_ALL_UPDATE']),
		
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>