<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_teamspeak'] )
	{
		$module['hm_clan']['sm_teamspeak'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_teamspeak';
	
	include('./pagestart.php');
	
	add_lang('teamspeak');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_TEAMSPEAK;
	$url	= POST_TEAMSPEAK;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_teamspeak'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_teamspeak.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'delete')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
		case 'update':
				
				$template->assign_block_vars('input', array());
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					$data = array(
						'teamspeak_name'		=> request('teamspeak_name', 2),
						'teamspeak_type'		=> TS_3,
						'teamspeak_viewer'		=> TS_TSSTATUS,
						'teamspeak_ip'			=> '',
						'teamspeak_port'		=> '',
						'teamspeak_qport'		=> '10011',
						'teamspeak_pass'		=> '',
						'teamspeak_option'		=> '',
						'teamspeak_join_name'	=> '',
						'teamspeak_show'		=> '0',
					);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(TEAMSPEAK, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
						'teamspeak_name'		=> request('teamspeak_name', 2),
						'teamspeak_type'		=> request('teamspeak_type', 0),
						'teamspeak_viewer'		=> request('teamspeak_viewer', 0),
						'teamspeak_ip'			=> request('teamspeak_ip', 2),
						'teamspeak_port'		=> request('teamspeak_port', 0),
						'teamspeak_qport'		=> request('teamspeak_qport', 0),
						'teamspeak_pass'		=> request('teamspeak_pass', 2),
						'teamspeak_option'		=> request('teamspeak_option', 2),
						'teamspeak_join_name'	=> request('teamspeak_join_name', 2),
						'teamspeak_show'		=> request('teamspeak_show', 0),
					);
							
					$error[] = !$data['teamspeak_name']			? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error[] = !$data['teamspeak_ip']			? ( $error ? '<br />' : '' ) . $lang['msg_empty_ip'] : '';
					$error[] = !$data['teamspeak_port']			? ( $error ? '<br />' : '' ) . $lang['msg_empty_port'] : '';
					$error[] = !$data['teamspeak_qport']			? ( $error ? '<br />' : '' ) . $lang['msg_empty_qport'] : '';
					$error[] = !isset($data['teamspeak_type'])	? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
					
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
						
						if ( $mode == 'create' )
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
						error('ERROR_BOX', $error);
					}
				
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['teamspeak_name']),
					
					'L_NAME'			=> $lang['name'],
					'L_IP'				=> $lang['ip'],
					'L_PORT'			=> $lang['port'],
					'L_PORT_EXPLAIN'	=> $lang['port_explain'],
					'L_QPORT'			=> $lang['qport'],
					'L_QPORT_EXPLAIN'	=> $lang['qport_explain'],
					'L_PASS'			=> $lang['pass'],
					'L_TYPE'			=> $lang['type'],
					'L_VIEWERS'			=> $lang['viewer'],
					
					'L_TS2'			=> $lang['ts2'],
					'L_TS3'			=> $lang['ts3'],
					
					'L_CYTS'				=> $lang['viewer_cyts'],
					'L_CYTS_EXPLAIN'		=> $lang['viewer_cyts_explain'],
					'L_VIEWER'			=> $lang['viewer_viewer'],
					'L_VIEWER_EXPLAIN'	=> $lang['viewer_viewer_explain'],
					'L_GAMEQ'				=> $lang['viewer_gameq'],
					'L_GAMEQ_EXPLAIN'		=> $lang['viewer_gameq_explain'],
					'L_TSSTATUS'			=> $lang['viewer_tsstatus'],
					'L_TSSTATUS_EXPLAIN'	=> $lang['viewer_tsstatus_explain'],
					
					
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
					'OPTION'		=> $data['teamspeak_option'],
					
					'S_TYPE_TS2'	=> (!$data['teamspeak_type'] ) ? 'checked="checked"' : '',
					'S_TYPE_TS3'	=> ( $data['teamspeak_type'] ) ? 'checked="checked"' : '',
					
					'S_SHOW_NO'		=> (!$data['teamspeak_show'] ) ? 'checked="checked"' : '',
					'S_SHOW_YES'	=> ( $data['teamspeak_show'] ) ? 'checked="checked"' : '',
					
					'S_VIEWER_CYTS'		=> ( $data['teamspeak_viewer'] == TS_CYTS )		? 'checked="checked"' : '',
					'S_VIEWER_VIEWER'	=> ( $data['teamspeak_viewer'] == TS_VIEWER )	? 'checked="checked"' : '',
					'S_VIEWER_GAMEQ'	=> ( $data['teamspeak_viewer'] == TS_GAMEQ )	? 'checked="checked"' : '',
					'S_VIEWER_TSSTATUS'	=> ( $data['teamspeak_viewer'] == TS_TSSTATUS )	? 'checked="checked"' : '',
					
				
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
				
				/*
				if ( request('submit', TXT) )
				{
					$error[] = !$data['teamspeak_name']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error[] = !$data['teamspeak_ip']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_ip'] : '';
					$error[] = !$data['teamspeak_port']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_port'] : '';
					$error[] = !$data['teamspeak_qport'] ? ( $error ? '<br />' : '' ) . $lang['msg_empty_qport'] : '';
					$error[] = !isset($data['teamspeak_type']) ? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
					
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
						
						if ( $mode == 'create' )
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
						error('ERROR_BOX', $error);
					}
				}
				*/			
				$template->pparse('body');
				
				break;
			
			case 'delete':
				
				$data = data(TEAMSPEAK, $data_id, false, 1, true);
			
				if ( $data_id && $confirm )
				{
					$sql = sql(TEAMSPEAK, $mode, $data, 'teamspeak_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['teamspeak_name']),

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
	
	$fields	= '<input type="hidden" name="mode" value="create" />';
	$tmp	= data(TEAMSPEAK, false, false, 1, false);
					
	if ( !$tmp )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$cnt = count($tmp);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$id		= $tmp[$i]['teamspeak_id'];
			$name	= $tmp[$i]['teamspeak_name'];
			$show	= $tmp[$i]['teamspeak_show'];
			
			$template->assign_block_vars('display.row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
				
				'SHOW'		=> $show ? $lang['teamspeak_viewer'] : '',
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['title']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid($file . "?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>