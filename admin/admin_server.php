<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_server',
		'modes'		=> array(
			'server'	=> array('title' => 'acp_server', 'auth' => 'auth_server'),
			'types'		=> array('title' => 'acp_types', 'auth' => 'auth_server'),
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

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_SERVER;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$acp_title	= sprintf($lang['sprintf_head'], (($action == 'server') ? $lang['title'] : $lang['type_title']));
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_server'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_server.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = (in_array($mode, array('create', 'update', 'type_create', 'type_update', 'move_down', 'move_up', 'delete'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'server' => array(
						'title'	=> 'data_input',
						'server_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'server_game'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:server', 'required' => 'input_game', 'params' => true),
						'server_ip'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_ip'),
						'server_port'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:10;10', 'required' => 'input_port'),
						'server_pw'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:10;10'),
						'server_live'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						'server_list'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						'server_show'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						'server_own'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						'server_type'	=> 'hidden',
						'server_order'	=> 'hidden',
					)
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$data_sql = array(
						'server_name'	=> request('server_name', TXT),
						'server_type'	=> 0,
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
						if ( $mode == 'create' )
						{
							$data_sql['server_order'] = maxa(SERVER, 'server_order', false);
							
							$sql = sql(SERVER, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
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
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_' . $mode], $lang['title'], $data_sql['server_name']),
					'L_EXPLAIN'	=> $lang['common_required'],
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
			
				$template->pparse('body');
				
				break;
			
			case 'type_create':
			case 'type_update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'type' => array(
						'title1' => 'input_data',
						'type_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'type_game'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_game'),
						'type_dport'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:10;10', 'required' => 'input_dport'),
						'type_sort'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type'),
					)
				);
				
				if ( $mode == 'type_create' && !$submit )
				{
					$data_sql = array(
						'type_name'		=> request('type_name', TXT),
						'type_game'		=> '',
						'type_dport'	=> '',
						'type_sort'		=> '',
					);
				}
				else if ( $mode == 'type_update' && !$submit )
				{
					$data_sql = data(SERVER_TYPE, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(SERVER_TYPE, $vars, $error, substr($mode, 5));
					
					if ( !$error )
					{
						if ( $mode == 'type_create' )
						{
							$sql = sql(SERVER_TYPE, $mode, $data_sql);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(SERVER_TYPE, $mode, $data_sql, 'type_id', $data);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						log_add(LOG_ADMIN, $log, substr($mode, 5), $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(SERVER_TYPE, $vars, $data_sql);
				
			#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_' . substr($mode, 5)], $lang['type_title'], $data_sql['type_name']),
					'L_EXPLAIN'	=> $lang['common_required'],
				
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
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
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['server_name']),

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
			
			case 'type_delete':
			
				$data_sql = data(SERVER_TYPE, $data, false, 1, true);
			
				if ( $data && $confirm )
				{
					$sql = sql(SERVER_TYPE, $mode, $data_sql, 'type_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(SERVER_TYPE, '-1');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['type_name']),

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
				
			case 'move_up':
			case 'move_down':
			
				move(SERVER, $mode, $order);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	switch ( $action )
	{
		case 'server':
		
			$template->assign_block_vars('display', array());
	
			$fields = '<input type="hidden" name="mode" value="create" />';
			
			$server = data(SERVER, false, 'server_order ASC', 1, false);
			
			if ( $server )
			{
				$live = array();
				
				foreach ( $server as $row )
				{
					if ( $row['server_live'] )
					{
						$live[] = array('type' => $row['server_game'], 'host' => $row['server_ip'] . ':' . $row['server_port'], 'id' => $row['server_id']);
					}
				}
				
				if ( $live )
				{
					$gq = new GameQ(); // or $gq = GameQ::factory();
					$gq->setOption('timeout', 1); // Seconds
				#	$gq->setOption('debug', TRUE);
					$gq->setFilter('normalise');
					$gq->addServers($live);
					$gq_server = $gq->requestData();
				}
			}
			
			if ( !$server )
			{
				$template->assign_block_vars('display.empty', array());
			}
			else
			{
				$max = count($server);
				
				foreach ( $server as $row )
				{
					$id		= $row['server_id'];
					$name	= $row['server_name'];
					$game	= $row['server_game'];
					$type	= $row['server_type'];
					$live	= $row['server_live'];
					$order	= $row['server_order'];
					
					$cur_u	= (isset($gq_server[$id]['gq_numplayers'])) ? (($gq_server[$id]['gq_numplayers'] >= 1) ? $gq_server[$id]['gq_numplayers'] : 0) : 0;
					$max_u	= (isset($gq_server[$id]['gq_maxplayers'])) ? $gq_server[$id]['gq_maxplayers'] : '0';
					
				#	debug($gq_serv[$server_name]['gq_online']);
					
					$template->assign_block_vars('display.row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, ''),
						'TYPE'		=> $type ? img('i_icon', 'icon_sound', '') : img('i_icon', 'icon_match', ''),
						'USERS'		=> ( isset($gq_server[$id]['gq_online']) ) ? sprintf($lang['cur_max'], $cur_u, $max_u) : '',
						'STATUS'	=> $live ? ( isset($gq_server[$id]['gq_online']) ) ? $lang['online'] : $lang['offline'] : '',
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down', 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
						
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
					));
				#	$servers['server_' . $i] = array($row['server_game'], $row['server_ip'], $row['server_port']);
				}
			}
		
			break;
			
		case 'types':
		
			$template->assign_block_vars('type_display', array());
	
			$fields		= '<input type="hidden" name="mode" value="type_create" />';
			
			$type_game	= data(SERVER_TYPE, 'type_sort = 0', 'type_id ASC', 1, false);
			$type_voice	= data(SERVER_TYPE, 'type_sort = 1', 'type_id ASC', 1, false);
					
			if ( !$type_game )
			{
				$template->assign_block_vars('type_display.game_empty', array());
			}
			else
			{
				foreach ( $type_game as $row )
				{
					$id		= $row['type_id'];
					$name	= $row['type_name'];
					
					$template->assign_block_vars('type_display.game_row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'type_update', 'id' => $id), $name, $name),
					
						'GAME'		=> $row['type_game'],
						'DPORT'		=> $row['type_dport'],
						
						'UPDATE'	=> href('a_img', $file, array('mode' => 'type_update', 'id' => $id), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'type_delete', 'id' => $id), 'icon_cancel', 'common_delete'),
					));
				}
			}
			
			if ( !$type_voice )
			{
				$template->assign_block_vars('type_display.voice_empty', array());
			}
			else
			{
				foreach ( $type_voice as $row )
				{
					$id		= $row['type_id'];
					$name	= $row['type_name'];
					
					$template->assign_block_vars('type_display.voice_row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'type_update', 'id' => $id), $name, $name),
					
						'GAME'		=> $row['type_game'],
						'DPORT'		=> $row['type_dport'],
						
						'UPDATE'	=> href('a_img', $file, array('mode' => 'type_update', 'id' => $id), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'type_delete', 'id' => $id), 'icon_cancel', 'common_delete'),
					));
				}
			}
			
			$template->assign_vars(array(
				
			));
		
			break;
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], (($action == 'server') ? $lang['title'] : $lang['type_title'])),
		'L_CREATE'	=> sprintf($lang['sprintf_create'], (($action == 'server') ? $lang['title'] : $lang['type_title'])),
		
		'L_EXPLAIN'	=> (($action == 'server') ? $lang['explain'] : $lang['type_explain']),
		'L_GAME'	=> $lang['typ_game'],
		'L_VOICE'	=> $lang['typ_voice'],

		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
		
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>