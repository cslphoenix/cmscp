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
		'ajax'		=> 'style/inc_request.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	function types($css, $default, $type)
	{
		global $db, $lang;
		
		$data = data(SERVER_TYPE, "type_sort = '$type'", false, 1, false);
		
	#	$s_select .= "<select class=\"$css\" name=\"$name\" id=\"$name\" onchange=\"setRequest(this.options[selectedIndex].value);\">";
		
		$return = "<select class=\"$css\" name=\"server_game\" id=\"server_game\">";
		
		foreach ( $data as $row )
		{
			$selected = ( $default == $row['type_game'] ) ? ' selected="selected"' : '';
			$return .= "<option value=\"" . $row['type_game'] . "\" $selected>" . sprintf($lang['sprintf_select_format'], $row['type_name']) . "</option>";
		}
		$return .= "</select>";
		
		return $return;
	}
	
	function print_results($results)
	{
		foreach ( $results as $id => $data )
		{
			printf("<h2>%s</h2>\n", $id);
			print_table($data);
		}
	}
	
	function print_table($data)
	{
		$gqs = array('gq_online', 'gq_address', 'gq_port', 'gq_prot', 'gq_type');
		
		if ( !$data['gq_online'] )
		{
			printf("<p>The server did not respond within the specified time.</p>\n");
			return;
		}
		
		print("<table><thead><tr><td>Variable</td><td>Value</td></tr></thead><tbody>\n");
		
		foreach ( $data as $key => $val )
		{
			if ( is_array($val) ) continue;
			
			$cls = empty($cls) ? ' class="uneven"' : '';
			
			if ( substr($key, 0, 3) == 'gq_' )
			{
				$kcls = (in_array($key, $gqs)) ? 'always' : 'normalise';
				$key = sprintf("<span class=\"key-%s\">%s</span>", $kcls, $key);
			}
			
			printf("<tr%s><td>%s</td><td>%s</td></tr>\n", $cls, $key, $val);
		}
		
		print("</tbody></table>\n");
	}
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
		case 'update':
			
				$template->assign_block_vars('input', array());
				
				$template->assign_vars(array('FILE' => 'ajax_server_type'));
				$template->assign_var_from_handle('AJAX', 'ajax');
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					$data = array(
						'server_name'	=> request('server_name', 2),
						'server_type'	=> '0',
						'server_game'	=> '',
						'server_ip'		=> '',
						'server_port'	=> '',
						'server_live'	=> '',
						'server_pw'		=> '',
						'server_list'	=> '1',
						'server_show'	=> '1',
						'server_own'	=> '1',
						'server_order'	=> '',
					);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(SERVER, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
						'server_name'	=> request('server_name', 2),
						'server_type'	=> request('server_type', 0),
						'server_game'	=> request('server_game', 2),
						'server_ip'		=> request('server_ip', 2),
						'server_port'	=> request('server_port', 2),
						'server_live'	=> request('server_live', 2),
						'server_pw'		=> request('server_pw', 2),
						'server_list'	=> request('server_list', 2),
						'server_show'	=> request('server_show', 2),
						'server_own'	=> request('server_own', 0),
						'server_order'	=> request('server_order', 0) ? request('server_order', 0) : request('server_order_new', 0),
					);
							
					$error .= ( !$data['server_name'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['server_ip'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_ip'] : '';
					$error .= ( !$data['server_port'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_port'] : '';
					
					if ( !$error )
					{
						$data['server_order'] = ( !$data['server_order'] ) ? maxa(SERVER, 'server_order', false) : $data['server_order'];
						
						if ( $mode == 'create' )
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
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['server_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
					
					'L_IP'		=> $lang['server_ip'],
					'L_PORT'	=> $lang['server_port'],
					'L_PW'		=> $lang['server_pw'],
					'L_LIST'	=> $lang['server_list'],
					'L_SHOW'	=> $lang['server_show'],
					'L_OWN'		=> $lang['server_own'],
					'L_LIVE'	=> $lang['server_live'],
					'L_GAME'	=> $lang['server_game'],
					'L_TYPE'	=> $lang['server_type'],
					
					'L_GAMESERVER'	=> $lang['gameserver'],
					'L_VOICESERVER'	=> $lang['voiceserver'],
					
					'NAME'		=> $data['server_name'],
					'IP'		=> $data['server_ip'],
					'PORT'		=> $data['server_port'],
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
					'S_ORDER'	=> simple_order(SERVER, false, 'select', $data['server_order']),
					
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
	
	$fields = '<input type="hidden" name="mode" value="_create" />';
	
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
			
			$template->assign_block_vars('display.server_row', array(
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
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['title']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
		
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>