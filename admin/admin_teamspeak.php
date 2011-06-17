<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_teamspeak'] )
	{
		$module['hm_server']['sm_teamspeak'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_teamspeak';
	
	include('./pagestart.php');
	include($root_path . 'includes/class_cyts.php');
	
	load_lang('teamspeak');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_TEAMSPEAK;
	$url	= POST_TEAMSPEAK;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_teamspeak'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_teamspeak.tpl',
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
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$data = array(
								'teamspeak_name'		=> request('authlist_name', 2),
								'teamspeak_ip'			=> '',
								'teamspeak_port'		=> '',
								'teamspeak_qport'		=> '',
								'teamspeak_pass'		=> '',
								'teamspeak_type'		=> '0',
								'teamspeak_viewer'		=> '',
							#	'teamspeak_join_name'	=> '',
							#	'teamspeak_cstats'		=> '1',
							#	'teamspeak_ustats'		=> '1',
							#	'teamspeak_sstats'		=> '1',
							#	'teamspeak_infos'		=> '1',
							#	'teamspeak_plist'		=> '1',
							#	'teamspeak_mouseover'	=> '0',
								'teamspeak_show'		=> '0',
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(TEAMSPEAK, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
								'teamspeak_name'		=> request('teamspeak_name', 2),
								'teamspeak_ip'			=> request('teamspeak_ip', 2),
								'teamspeak_port'		=> request('teamspeak_port', 2),
								'teamspeak_qport'		=> request('teamspeak_qport', 2),
								'teamspeak_pass'		=> request('teamspeak_pass', 2),
								'teamspeak_type'		=> request('teamspeak_type', 0),
								'teamspeak_viewer'		=> request('teamspeak_viewer', 4),
							#	'teamspeak_join_name'	=> request('teamspeak_join_name', 2),
							#	'teamspeak_cstats'		=> request('teamspeak_cstats', 2),
							#	'teamspeak_ustats'		=> request('teamspeak_ustats', 2),
							#	'teamspeak_sstats'		=> request('teamspeak_sstats', 2),
							#	'teamspeak_infos'		=> request('teamspeak_infos', 2),
							#	'teamspeak_plist'		=> request('teamspeak_plist', 2),
							#	'teamspeak_mouseover'	=> request('teamspeak_mouseover', 2),
								'teamspeak_show'		=> request('teamspeak_show', 0),
							);
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['title'], $data['teamspeak_name']),
					
					'L_NAME'			=> $lang['name'],
					'L_IP'				=> $lang['ip'],
					'L_PORT'			=> $lang['port'],
					'L_PORT_EXPLAIN'	=> $lang['port_explain'],
					'L_QPORT'			=> $lang['qport'],
					'L_QPORT_EXPLAIN'	=> $lang['qport_explain'],
					'L_PASS'			=> $lang['pass'],
					'L_TYPE'			=> $lang['type'],
					'L_VIEWER'			=> $lang['viewer'],
					
					'L_TS2'			=> $lang['ts2'],
					'L_TS3'			=> $lang['ts3'],
					
				#	'L_CSTATS'		=> $lang['teamspeak_cstats'],
				#	'L_USTATS'		=> $lang['teamspeak_ustats'],
				#	'L_SSTATS'		=> $lang['teamspeak_sstats'],
				#	'L_MOUSEO'		=> $lang['teamspeak_mouseo'],
				#	'L_VIEWER'		=> $lang['teamspeak_viewer'],
				#	'L_JOIN'		=> $lang['teamspeak_join'],
					
					'NAME'			=> $data['teamspeak_name'],
					'IP'			=> $data['teamspeak_ip'],
					'PORT'			=> $data['teamspeak_port'],
					'QPORT'			=> $data['teamspeak_qport'],
					'PASS'			=> $data['teamspeak_pass'],
					
					'S_TYPE_TS2'	=> (!$data['teamspeak_type'] ) ? 'checked="checked"' : '',
					'S_TYPE_TS3'	=> ( $data['teamspeak_type'] ) ? 'checked="checked"' : '',
					
					'S_SHOW_NO'		=> (!$data['teamspeak_show'] ) ? 'checked="checked"' : '',
					'S_SHOW_YES'	=> ( $data['teamspeak_show'] ) ? 'checked="checked"' : '',
				
				#	'JOIN'			=> $data['teamspeak_join_name'],
					
				#	'S_CSTATS_YES'	=> ( $data['teamspeak_cstats'] ) ? ' checked="checked"' : '',
				#	'S_CSTATS_NO'	=> ( !$data['teamspeak_cstats'] ) ? ' checked="checked"' : '',
				#	'S_USTATS_YES'	=> ( $data['teamspeak_ustats'] ) ? ' checked="checked"' : '',
				#	'S_USTATS_NO'	=> ( !$data['teamspeak_ustats'] ) ? ' checked="checked"' : '',
				#	'S_SSTATS_YES'	=> ( $data['teamspeak_sstats'] ) ? ' checked="checked"' : '',
				#	'S_SSTATS_NO'	=> ( !$data['teamspeak_sstats'] ) ? ' checked="checked"' : '',
				#	'S_MOUSEO_YES'	=> ( $data['teamspeak_mouseover'] ) ? ' checked="checked"' : '',
				#	'S_MOUSEO_NO'	=> ( !$data['teamspeak_mouseover'] ) ? ' checked="checked"' : '',
				#	'S_VIEWER_YES'	=> ( $data['teamspeak_show'] ) ? ' checked="checked"' : '',
				#	'S_VIEWER_NO'	=> ( !$data['teamspeak_show'] ) ? ' checked="checked"' : '',

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$error .= !$data['teamspeak_name']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= !$data['teamspeak_ip']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_ip'] : '';
					$error .= !$data['teamspeak_port']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_port'] : '';
					$error .= !$data['teamspeak_qport'] ? ( $error ? '<br />' : '' ) . $lang['msg_empty_qport'] : '';
					$error .= !isset($data['teamspeak_type']) ? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
					
					if ( !$error )
					{
						if ( $data['teamspeak_show'] )
						{
							$show = data(TEAMSPEAK, "WHERE teamspeak_show = 1", false, 1, true);
							
							if ( $show )
							{
								sql(TEAMSPEAK, 'update', array('teamspeak_show' => 0), 'teamspeak_id', $show['teamspeak_id']);
							}
						}
						
						if ( $mode == '_create' )
						{
							$sql = sql(TEAMSPEAK, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(TEAMSPEAK, $mode, $data, 'teamspeak_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						$oCache -> deleteCache('teamspeak');
						
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
			
				$template->pparse('body');
				
				break;
			
			case '_delete':
			
				$data = get_data(TEAMSPEAK, $data_id, 1);
				
				if ( $data_id && $confirm )
				{
					$sql = "DELETE FROM " . TEAMSPEAK . " WHERE teamspeak_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					log_add(LOG_ADMIN, SECTION_TEAMSPEAK, 'acp_teamspeak_delete', $teamspeak['teamspeak_name']);
					
					$message = $lang['delete_teamspeak'] . sprintf($lang['click_return_teamspeak'], '<a href="' . check_sid('admin_teamspeak.php'));
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_TEAMSPEAK . '" value="' . $data_id . '" />';
					
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['delete_confirm_teamspeak'], $data['teamspeak_name']),
						
						'S_FIELDS'		=> $fields,
						'S_ACTION'		=> check_sid('admin_teamspeak.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_teamspeak']);
				}
				
				$template->pparse('body');
				
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
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['title']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid($file . "?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$teamspeaks = data(TEAMSPEAK, false, false, 1, false);
	
	if ( !$teamspeaks )
	{
		$template->assign_block_vars('_display._entry_empty', array());
	}
	else
	{
		for ( $i = 0; $i < count($teamspeaks); $i++ )
		{
			$teamspeak_id = $teamspeaks[$i]['teamspeak_id'];
			
			$template->assign_block_vars('_display._teamspeak_row', array(
				'NAME'		=> $teamspeaks[$i]['teamspeak_name'],
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$teamspeak_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$teamspeak_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	
	$show = data(TEAMSPEAK, "WHERE teamspeak_show = 1", false, 1, true);
	
	if ( $show )
	{
		$template->assign_block_vars('_display._server', array());
		
		if ( $show['teamspeak_type'] != TS3 )
		{
			$cyts = new cyts;
			$cyts->connect($show['teamspeak_ip'], $show['teamspeak_qport'], $show['teamspeak_port']);
			$info = $cyts->info_serverInfo();
			
			if ( !$info )
			{
				$template->assign_block_vars('_display._server._off', array());
			}
			else
			{
				$template->assign_vars(array(
					'L_SERVER_NAME'				=> $lang['server_name'],
					'L_SERVER_PLATFORM'			=> $lang['server_platform'],
					'L_SERVER_WELCOME_MSG'		=> $lang['server_welcomemessage'],			
					'L_SERVER_WEB_LINK'			=> $lang['server_webpost_linkurl'],
					'L_SERVER_WEB_POST'			=> $lang['server_webpost_posturl'],
					'L_SERVER_PASSWORD'			=> $lang['server_password'],
					'L_SERVER_TYPE'				=> $lang['server_clan_server'],
					'L_SERVER_USER_MAX'			=> $lang['server_maxusers'],
					'L_SERVER_USER_CURRENT'		=> $lang['server_currentusers'],
					'L_SERVER_CODEC_1'			=> $lang['server_allow_codec_celp51'],
					'L_SERVER_CODEC_2'			=> $lang['server_allow_codec_celp63'],
					'L_SERVER_CODEC_3'			=> $lang['server_allow_codec_gsm148'],
					'L_SERVER_CODEC_4'			=> $lang['server_allow_codec_gsm164'],
					'L_SERVER_CODEC_5'			=> $lang['server_allow_codec_windowscelp52'],
					'L_SERVER_CODEC_6'			=> $lang['server_allow_codec_speex2150'],
					'L_SERVER_CODEC_7'			=> $lang['server_allow_codec_speex3950'],
					'L_SERVER_CODEC_8'			=> $lang['server_allow_codec_speex5950'],
					'L_SERVER_CODEC_9'			=> $lang['server_allow_codec_speex8000'],
					'L_SERVER_CODEC_10'			=> $lang['server_allow_codec_speex11000'],
					'L_SERVER_CODEC_11'			=> $lang['server_allow_codec_speex15000'],
					'L_SERVER_CODEC_12'			=> $lang['server_allow_codec_speex18200'],
					'L_SERVER_CODEC_13'			=> $lang['server_allow_codec_speex24600'],
					'L_SERVER_SEND_PACKET'		=> $lang['server_packetssend'],
					'L_SERVER_SEND_BYTE'		=> $lang['server_bytessend'],
					'L_SERVER_RECEIVED_PACKET'	=> $lang['server_packetsreceived'],
					'L_SERVER_RECEIVED_BYTE'	=> $lang['server_bytesreceived'],
					'L_SERVER_UPTIME'			=> $lang['server_uptime'],
					'L_SERVER_NUM_CHANNELS'		=> $lang['server_currentchannels'],
					'S_TEAMSPEAK_ACTION'		=> check_sid('admin_teamspeak.php'),
				));
				
				$uptime	= $info['server_uptime'];
				
				$d = floor($uptime / 86400);
				$h = $uptime - ($d * 86400);
				$h = floor($h / 3600);
				$m = $uptime - (($d * 86400) + ($h * 3600));
				$m = floor($m / 60);
				$s = $uptime - (($d * 86400) + ($h * 3600) + ($m * 60));
				
				$h = ( $h < 10 ) ? 0 . $h : $h;
				$m = ( $m < 10 ) ? 0 . $m : $m;
				$s = ( $s < 10 ) ? 0 . $s : $s;
				
				$uptime = sprintf($lang['uptime'], $d, $h, $m, $s);
				
				$template->assign_block_vars('_display._server._on', array(
					'SERVER_NAME'				=> $info['server_name'],
					'SERVER_PLATFORM'			=> $info['server_platform'],
					'SERVER_WELCOME_MSG'		=> $info['server_welcomemessage'],			
					'SERVER_WEB_LINK'			=> $info['server_webpost_linkurl'],
					'SERVER_WEB_POST'			=> $info['server_webpost_posturl'],
					'SERVER_PASSWORD'			=> $info['server_password'],
					'SERVER_TYPE'				=> ( $info['server_clan_server'] == '1' ) ? 'Clan' : 'Public',
					'SERVER_USER_MAX'			=> $info['server_maxusers'],
					'SERVER_USER_CURRENT'		=> $info['server_currentusers'],
					'SERVER_CODEC_1'			=> ( $info['server_allow_codec_celp51'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_2'			=> ( $info['server_allow_codec_celp63'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_3'			=> ( $info['server_allow_codec_gsm148'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_4'			=> ( $info['server_allow_codec_gsm164'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_5'			=> ( $info['server_allow_codec_windowscelp52'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_6'			=> ( $info['server_allow_codec_speex2150'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_7'			=> ( $info['server_allow_codec_speex3950'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_8'			=> ( $info['server_allow_codec_speex5950'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_9'			=> ( $info['server_allow_codec_speex8000'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_10'			=> ( $info['server_allow_codec_speex11000'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_11'			=> ( $info['server_allow_codec_speex15000'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_12'			=> ( $info['server_allow_codec_speex18200'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_CODEC_13'			=> ( $info['server_allow_codec_speex24600'] == '-1' ) ? $lang['codec_on'] : $lang['codec_off'],
					'SERVER_SEND_PACKET'		=> $info['server_packetssend'],
					'SERVER_SEND_BYTE'			=> $info['server_bytessend'],
					'SERVER_RECEIVED_PACKET'	=> $info['server_packetsreceived'],
					'SERVER_RECEIVED_BYTE'		=> $info['server_bytesreceived'],
					'SERVER_UPTIME'				=> $uptime,
					'SERVER_NUM_CHANNELS'		=> $info['server_currentchannels'],
				));
			}		
			$cyts->disconnect();
		}
		else
		{
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>