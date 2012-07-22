<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_server'] )
	{
		$module['hm_clan']['sm_gameserver'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_server';
	
	include('./pagestart.php');
	
	add_lang('server');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_SERVER;
	$url	= POST_SERVER;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_server'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_server.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'order', 'delete')) ) ? $mode : '';
	
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
						'server_name'	=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name'),
						'server_game'	=> array('validate' => TXT,	'type' => 'drop:server',	'explain' => true, 'required' => 'input_game'),
						'server_ip'		=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_ip'),
						'server_port'	=> array('validate' => TXT,	'type' => 'text:10:10',		'explain' => true, 'required' => 'input_port'),
						'server_pw'		=> array('validate' => TXT,	'type' => 'text:10:10',		'explain' => true),
						'server_live'	=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'server_list'	=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'server_show'	=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'server_own'	=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'server_order'	=> array('validate' => INT,	'type' => 'drop:order',		'explain' => true),
						'server_type'	=> array('type' => 'hidden'),
					)
				);
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					$data = array(
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
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(SERVER, $data_id, false, 1, true);
				}
				else
				{
					$data = build_request(SERVER, $vars, 'server', $error);
					
					if ( !$error )
					{
						$data['server_order'] = $data['server_order'] ? $data['server_order'] : maxa(SERVER, 'server_order', false);
						
						if ( $mode == 'create' )
						{
							$sql = sql(SERVER, $mode, $data);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(SERVER, $mode, $data, 'server_id', $data_id);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(SERVER);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output($data, $vars, 'input', false, SERVER);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['server_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			
				$template->pparse('body');
				
				break;
				
			case 'order':
				
				update(SERVER, 'server', $move, $data_id);
				orders(SERVER);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case 'delete':
			
				$data = data(SERVER, $data_id, false, 1, true);
			
				if ( $data_id && $confirm )
				{
					$sql = sql(SERVER, $mode, $data, 'server_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(SERVER);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
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
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
	
	$fields = '<input type="hidden" name="mode" value="create" />';
	
	$max	= maxi(SERVER, 'server_order', '');
	$serv	= data(SERVER, false, 'server_order ASC', 1, false);
	$live	= data(SERVER, 'server_live = 1', 'server_order ASC', 1, false);
	
	if ( $live )
	{
		include($root_path . 'includes/class_gameq.php');
		
		$ary = '';
		
		foreach ( $live as $keys => $row )
		{
			if ( $row['server_live'] == '1' && $row['server_game'] )
			{
				$ary[] = array('id' => $row['server_name'], 'type' => $row['server_game'], 'host' => $row['server_ip'] . ':' . $row['server_port']);
			}
		}
		
		$gq = new GameQ();
		$gq->addServers($ary);
		$gq->setOption('timeout', 4); // Seconds
		$gq->setFilter('normalise');
		$gq_data = $gq->requestData();
		$gq_serv = cached_file($gq_data, 'data_servers', 120);
	}
	
	if ( !$serv )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$cnt = count($serv);
		
		for ( $i = $start; $i < $cnt; $i++ )
		{
			$id		= $serv[$i]['server_id'];
			$name	= $serv[$i]['server_name'];
			$game	= $serv[$i]['server_game'];
			$type	= $serv[$i]['server_type'];
			$live	= $serv[$i]['server_live'];
			$order	= $serv[$i]['server_order'];
			
			$cur_u	= ( isset($gq_serv[$name]['gq_numplayers']) ) ? ( ( $gq_serv[$name]['gq_numplayers'] >= 1 ) ? $gq_serv[$name]['gq_numplayers'] : '0' ) : '0';
			$max_u	= ( isset($gq_serv[$name]['gq_maxplayers']) ) ? $gq_serv[$name]['gq_maxplayers'] : '0';
			
		#	debug($gq_serv[$server_name]['gq_online']);
			
			$template->assign_block_vars('display.row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
				'TYPE'		=> $type ? img('i_icon', 'icon_sound', '') : img('i_icon', 'icon_match', ''),
				'USERS'		=> ( isset($gq_serv[$name]['gq_online']) ) ? sprintf($lang['cur_max'], $cur_u, $max_u) : '',
				'STATUS'	=> $live ? ( isset($gq_serv[$name]['gq_online']) ) ? $lang['online'] : $lang['offline'] : '',
				
				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
			
		#	$servers['server_' . $i] = array($serv[$i]['server_game'], $serv[$i]['server_ip'], $serv[$i]['server_port']);
			
		}
		
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['title']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file?mode=create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
		
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>