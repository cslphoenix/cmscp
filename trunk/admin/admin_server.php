<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_server'] )
	{
		$module['_headmenu_09_server']['_submenu_gameserver'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_gameserver';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');

#	include($root_path . 'includes/server_query.php');
#	include($root_path . 'includes/teamspeak_query.php');
	
	load_lang('server');
	
	$error	= '';
	$index	= '';
	$log	= LOG_SEK_SERVER;
	$url	= POST_SERVER_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$acp_title	= sprintf($lang['sprintf_head'], $lang['server']);
	$fields	= '';
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_server'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	function _select_game($default)
	{
		global $lang;
		
		$type = array (
			'0'				=> 'auswahl',
			'aarmy'			=> 'Americas Army',
			'bf2'			=> 'Battlefield 2 (PoE, PR, ....)',
			'bf1942'		=> 'Battlefield 1942',
			'bf2142'		=> 'Battlefield 2142',
			'bfvietnam'		=> 'Battlefield Vietnam',
			'callofduty'	=> 'Call of Duty 1 &amp; 2',
			'cnc'			=> 'Command and Conquer',
			'halflife'		=> 'Dark Messiah of Might & Magic',
			'farcry'		=> 'Far Cry',
			'fear'			=> 'F.E.A.R',
			'halo'			=> 'Halo',
			'halflife'		=> 'Halflife (CS, DoD, ...)',
			'halflife2'		=> 'Halflife 2 (CS:S, DoD:S, ...)',
			'jediknight2'	=> 'Jediknight 2',
			'mohq3'			=> 'Medal of Honor - Method 1',
			'mohgs'			=> 'Medal of Honor - Method 2',
			'neverwinter'	=> 'Neverwinter Nights',
			'aarmy'			=> 'Operation Flashpoint',
			'quake4'		=> 'Prey',
			'quake3'		=> 'Quake 3',
			'quake4'		=> 'Quake 4',
			'quakew'		=> 'Quake World',
			'ravenshield'	=> 'Ravenshield',
			'sof2'			=> 'Soldier of Fortune 2',
			'startrekef'	=> 'StarTrek Elite-Force',
			'battlefront'	=> 'StarWars Battlefront 2',
			'ut2004'		=> 'Red Orchestra',
			'swat4'			=> 'SWAT 4',
			'ut'			=> 'Unreal Tournament',
			'ut2003'		=> 'Unreal Tournament 2003',
			'ut2004'		=> 'Unreal Tournament 2004',
			'vietcong'		=> 'Vietcong',
			'vietcong2'		=> 'Vietcong 2',
			'wolfenstein'	=> 'Wolfenstein (RTCW &amp; Enemy Territory)',
		);
		
		$select_game = '<select name="server_type" class="post">';
		foreach ($type as $valve => $typ)
		{
			$selected = ( $valve == $default ) ? ' selected="selected"' : '';
			$select_game .= '<option value="' . $valve . '" ' . $selected . '>&raquo; ' . $typ . '&nbsp;</option>';
		}
		$select_game .= '</select>';
		
		return $select_game;	
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_server.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$max	= get_data_max(SERVER, 'server_order', '');
					$data	= array(
								'server_name'	=> request('server_name', 2),
								'server_game'	=> '',
								'server_ip'		=> '',
								'server_port'	=> '',
								'server_qport'	=> '',
								'server_pw'		=> '',
								'server_live'	=> '',
								'server_list'	=> '1',
								'server_show'	=> '1',
								'server_own'	=> '1',
								'server_order'	=> $max['max'] + 10,
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(SERVER, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
								'server_name'	=> request('server_name', 2),
								'server_game'	=> request('server_game', 2),
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
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= '<input type="hidden" name="server_order" value="' . $data['server_order'] . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'	=> $acp_title,
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['server'], $data['server_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['server']),
					'L_IP'		=> $lang['ip'],
					'L_PORT'	=> $lang['port'],
					'L_QPORT'	=> $lang['qport'],
					'L_PW'		=> $lang['pw'],
					'L_LIST'	=> $lang['list'],
					'L_SHOW'	=> $lang['show'],
					'L_OWN'		=> $lang['own'],
					'L_ORDER'	=> $lang['common_order'],
					
					'NAME'	=> $data['server_name'],
					'IP'	=> $data['server_ip'],
					'PORT'	=> $data['server_port'],
					'QPORT'	=> $data['server_qport'],
					'PW'	=> $data['server_pw'],
					
					'S_LIVE_NO'		=> (!$data['server_live']) ? 'checked="checked"' : '',
					'S_LIVE_YES'	=> ( $data['server_live']) ? 'checked="checked"' : '',
					'S_LIST_NO'		=> (!$data['server_list']) ? 'checked="checked"' : '',
					'S_LIST_YES'	=> ( $data['server_list']) ? 'checked="checked"' : '',
					'S_SHOW_NO'		=> (!$data['server_show']) ? 'checked="checked"' : '',
					'S_SHOW_YES'	=> ( $data['server_show']) ? 'checked="checked"' : '',
					'S_OWN_NO'		=> (!$data['server_own']) ? 'checked="checked"' : '',
					'S_OWN_YES'		=> ( $data['server_own']) ? 'checked="checked"' : '',

					'S_LIVE'	=> _select_game($data['server_game']),
					'S_ORDER'	=> select_order('select', SERVER, 'server', $data['server_order']),
					
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
			
				if ( request('submit', 1) )
				{
					$data = array(
								'server_name'	=> request('server_name', 2),
								'server_game'	=> request('server_game', 2),
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
					
					$data['server_order'] = ( !$data['server_order'] ) ? maxa(SERVER, 'server_order', false) : $data['server_order'];
					
					$error .= ( !$data['server_name'] )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['server_ip'] )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_ip'] : '';
					$error .= ( !$data['server_port'] )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_port'] : '';
					$error .= ( !$data['server_qport'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_qport'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$db_data = sql(SERVER, $mode, $data);
							
							$message = $lang['create']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							$db_data = sql(SERVER, $mode, $data, 'server_id', $data_id);
							
							$message = $lang['update']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
						}
						
						orders(SERVER);
						
						log_add(LOG_ADMIN, $log, $mode, $db_data);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');
				
				break;
				
			case '_order':
				
				update(SERVER, 'server', $move, $data_id);
				orders(SERVER);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete':
			
				$data = data(SERVER, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{
					$db_data = sql(SERVER, $mode, $data, 'server_id', $data_id);
					
					$message = $lang['delete']
						. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					
					orders(SERVER);
					
					log_add(LOG_ADMIN, $log, $mode, $db_data);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['server_name']),

						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['server'])); }
				
				$template->pparse('body');
				
				break;
			
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_server.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['server']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['server']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['server']),
		
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> append_sid("$file?mode=_create"),
		'S_ACTION'	=> append_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max = maxi(SERVER, 'server_order', '');
	$tmp = data(SERVER, false, 'server_order ASC', 1, false);
	
	if ( $tmp )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp)); $i++ )
		{
			$server_id		= $tmp[$i]['server_id'];
			$server_order	= $tmp[$i]['server_order'];
			
			$template->assign_block_vars('_display._server_row', array(
				'NAME'		=> $tmp[$i]['server_name'],
				
				'MOVE_UP'	=> ( $server_order != '10' ) ? '<a href="' . append_sid("$file?mode=_order&amp;move=-15&amp;$url=$server_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $server_order != $max ) ? '<a href="' . append_sid("$file?mode=_order&amp;move=+15&amp;$url=$server_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$server_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$server_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry', array()); }
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>