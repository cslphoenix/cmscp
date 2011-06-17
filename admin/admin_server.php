<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_server'] )
	{
		$module['hm_server']['sm_gameserver'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_gameserver';
	
	include('./pagestart.php');
	
	load_lang('server');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_SERVER;
	$url	= POST_SERVER;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['server']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_server'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_server.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	function types($css, $default, $type)
	{
		global $db, $lang;
		
		$data = data(SERVER_TYPE, "type_sort = $type", false, 1, false);
		
		$return = "<select class=\"$css\" name=\"server_game\">";
		
		foreach ( $data as $row )
		{
			$selected = ( $default == $row['type_game'] ) ? ' selected="selected"' : '';
			$return .= "<option value=\"" . $row['type_game'] . "\" $selected>" . sprintf($lang['sprintf_select_format'], $row['type_name']) . "</option>";
		}
		$return .= "</select>";
		
		return $return;	
	}
	
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$data = array(
								'server_name'	=> request('server_name', 2),
								'server_name'	=> '',
								'server_type'	=> '0',
								'server_game'	=> '',
								'server_ip'		=> '',
								'server_port'	=> '',
								'server_qport'	=> '',
								'server_pw'		=> '',
								'server_live'	=> '1',
								'server_list'	=> '1',
								'server_show'	=> '1',
								'server_own'	=> '1',
								'server_order'	=> '',
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(SERVER, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
								'server_name'	=> request('server_name', 2),
								'server_type'	=> request('server_type', 0),
								'server_game'	=> request('server_game', 2),
								'server_name'	=> request('server_name', 2),
								'server_ip'		=> request('server_ip', 2),
								'server_port'	=> request('server_port', 2),
								'server_qport'	=> request('server_qport', 2),
								'server_pw'		=> request('server_pw', 2),
								'server_live'	=> request('server_live', 2),
								'server_list'	=> request('server_list', 2),
								'server_show'	=> request('server_show', 2),
								'server_own'	=> request('server_own', 0),
								'server_order'	=> request('server_order', 0) ? request('server_order', 0) : request('server_order_new', 0),
							);
							
					$error .= ( !$data['server_name'] )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['server_ip'] )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_ip'] : '';
					$error .= ( !$data['server_port'] )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_port'] : '';
					$error .= ( !$data['server_qport'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_qport'] : '';
					
					if ( !$error )
					{
						$data['server_order'] = ( !$data['server_order'] ) ? maxa(SERVER, 'server_order', false) : $data['server_order'];
						
						if ( $mode == '_create' )
						{
							$sql = sql(SERVER, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(SERVER, $mode, $data, 'server_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(SERVER);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['server']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['server'], $data['server_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['server']),
					
					'L_IP'		=> $lang['server_ip'],
					'L_PORT'	=> $lang['server_port'],
					'L_QPORT'	=> $lang['server_qport'],
					'L_PW'		=> $lang['server_pw'],
					'L_LIST'	=> $lang['list'],
					'L_SHOW'	=> $lang['show'],
					'L_OWN'		=> $lang['own'],
					
					'L_GAMESERVER'	=> $lang['gameserver'],
					'L_VOICESERVER'	=> $lang['voiceserver'],
					
					'NAME'		=> $data['server_name'],
					'IP'		=> $data['server_ip'],
					'PORT'		=> $data['server_port'],
					'QPORT'		=> $data['server_qport'],
					'PW'		=> $data['server_pw'],
					
					'S_LIVE_NO'		=> (!$data['server_live'] ) ? 'checked="checked"' : '',
					'S_LIVE_YES'	=> ( $data['server_live'] ) ? 'checked="checked"' : '',
					'S_LIST_NO'		=> (!$data['server_list'] ) ? 'checked="checked"' : '',
					'S_LIST_YES'	=> ( $data['server_list'] ) ? 'checked="checked"' : '',
					'S_SHOW_NO'		=> (!$data['server_show'] ) ? 'checked="checked"' : '',
					'S_SHOW_YES'	=> ( $data['server_show'] ) ? 'checked="checked"' : '',
					'S_OWN_NO'		=> (!$data['server_own'] ) ? 'checked="checked"' : '',
					'S_OWN_YES'		=> ( $data['server_own'] ) ? 'checked="checked"' : '',
					
					'S_GAMESERVER'	=> (!$data['server_type'] ) ? 'checked="checked"' : '',
					'S_VOICESERVER'	=> ( $data['server_type'] ) ? 'checked="checked"' : '',

					'S_GAME'	=> types('select', $data['server_game'], $data['server_type']),
					'S_ORDER'	=> simple_order(SERVER, $data['server_type'], 'select', $data['server_order']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			
				$template->pparse('body');
				
				break;
				
			case '_order':
				
				update(SERVER, 'server', $move, $data_id);
				orders(SERVER);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete':
			
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
					message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['server']));
				}
				
				$template->pparse('confirm');
				
				break;
			
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['server']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['server']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['server']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$ary = array();
	$max = maxi(SERVER, 'server_order', '');
	$server = data(SERVER, false, 'server_order ASC', 1, false);
	
	if ( !$server )
	{
		$template->assign_block_vars('_display._entry_empty', array());
	}
	else
	{
		include($root_path . 'includes/server/gameq/GameQ.php');
		
		foreach ( $server as $keys => $row )
		{
			if ( $row['server_live'] == '1' )
			{
				$ary[$row['server_id']] = array($row['server_game'], $row['server_ip'], $row['server_port']);
			}
		}
		
		$gq = new GameQ();
		$gq->addServers($ary);
		$gq->setOption('timeout', 200);
		$gq->setFilter('normalise');
		$gq->setFilter('sortplayers', 'gq_ping');
		$serv = $gq->requestData();
		
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($server)); $i++ )
		{
			$server_id		= $server[$i]['server_id'];
			$server_name	= $server[$i]['server_name'];
			$server_order	= $server[$i]['server_order'];
			
			$cur_users		= ( isset($serv[$server[$i]['server_id']]['gq_numplayers']) ) ? ( ( $serv[$server[$i]['server_id']]['gq_numplayers'] >= 1 ) ? $serv[$server[$i]['server_id']]['gq_numplayers'] : '0' ) : '0';
			$max_users		= ( isset($serv[$server[$i]['server_id']]['gq_maxplayers']) ) ? $serv[$server[$i]['server_id']]['gq_maxplayers'] : '0';
			
			$template->assign_block_vars('_display._server_row', array(
				'NAME'		=> $server_name,
				
				'MOVE_UP'	=> ( $server_order != '10' ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=-15&amp;$url=$server_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $server_order != $max ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=+15&amp;$url=$server_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'STATUS'	=> ( isset($serv[$server[$i]['server_id']]['gq_online']) ) ? 'Online' : 'Offline',
				'USERS'		=> ( isset($serv[$server[$i]['server_id']]['gq_online']) ) ? sprintf($lang['cur_max'], $cur_users, $max_users) : '',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$server_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$server_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
			
		#	$servers['server_' . $i] = array($server[$i]['server_game'], $server[$i]['server_ip'], $server[$i]['server_port']);
			
		}
		
	}
		
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>