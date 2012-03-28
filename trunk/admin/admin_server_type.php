<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);

	if ( $userdata['user_level'] == ADMIN || $userauth['auth_server_type'] )
	{
		$module['hm_server']['sm_server_type'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);

	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_server_type';

	include('./pagestart.php');

	add_lang('types');

	$error	= '';
	$index	= '';
	$fields	= '';

	$log	= SECTION_SERVER_TYPE;
	$url	= POST_SERVER_TYPE;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_server_type'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . str_replace('sm', '', $current));
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_server_type.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('_create', '_update', '_delete')) ) ? $mode : '';
	
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
								'type_name'		=> request('type_name', 1),
								'type_game'		=> '',
								'type_dport'	=> '',
								'type_sort'		=> '',
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(SERVER_TYPE, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
								'type_name'		=> request('type_name', 2),
								'type_game'		=> request('type_game', 2),
								'type_dport'	=> request('type_dport', 0),
								'type_sort'		=> request('type_sort', 0),
							);
							
					$error .= check(SERVER_TYPE, array('type_name' => $data['type_name'], 'type_id' => $data_id), $error);
					$error .= !$data['game_tag'] ? ( $error ? '<br />' : '' ) . $lang['msg_empty_tag'] : '';
					$error .= !$data['game_tag'] ? ( $error ? '<br />' : '' ) . $lang['msg_empty_tag'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$sql = sql(SERVER_TYPE, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(SERVER_TYPE, $mode, $data, 'type_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
						
						log_add(LOG_ADMIN, $log, 'error', $error);
					}
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['title'], $data['type_name']),
					'L_DATA'	=> $lang['data'],
					
					'L_NAME'	=> $lang['type_name'],
					'L_GAME'	=> $lang['type_game'],
					'L_DPORT'	=> $lang['type_dport'],
					'L_SORT'	=> $lang['type_sort'],
					
					'L_GAMESERVER'	=> $lang['serv_game'],
					'L_VOICESERVER'	=> $lang['serv_voice'],
					
					'NAME'		=> $data['type_name'],
					'GAME'		=> $data['type_game'],
					'DPORT'		=> $data['type_dport'],
					
					'S_GAME'	=> (!$data['type_sort'] ) ? 'checked="checked"' : '',
					'S_VOICE'	=> ( $data['type_sort'] ) ? 'checked="checked"' : '',
					
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
			
			case '_delete':
			
				$data = data(SERVER_TYPE, $data_id, false, 1, true);
			
				if ( $data_id && $confirm )
				{
					$sql = sql(SERVER_TYPE, $mode, $data, 'type_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(SERVER_TYPE, '-1');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
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
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
			
	$template->assign_block_vars('_display', array());
	
	$fields		= '<input type="hidden" name="mode" value="_create" />';
	$type_game	= data(SERVER_TYPE, 'type_sort = 0', 'type_id ASC', 1, false);
	$type_voice	= data(SERVER_TYPE, 'type_sort = 1', 'type_id ASC', 1, false);
			
	if ( !$type_game )
	{
		$template->assign_block_vars('_display._game_empty', array());
	}
	else
	{
		$count = count($type_game);
		
		for ( $i = $start; $i < $count; $i++ )
		{
			$id		= $type_game[$i]['type_id'];
			$name	= $type_game[$i]['type_name'];
			$game	= $type_game[$i]['type_game'];
			$dport	= $type_game[$i]['type_dport'];
			
			$template->assign_block_vars('_display._game_row', array(
				'NAME'		=> href('a_txt', $file, array('?mode' => '_update', $url => $id), $name, ''),
			
				'GAME'		=> $game,
				'DPORT'		=> $dport,
				
				'UPDATE'	=> href('a_img', $file, array('?mode' => '_update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('?mode' => '_delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$type_voice )
	{
		$template->assign_block_vars('_display._voice_empty', array());
	}
	else
	{
		$count = count($type_voice);
		
		for ( $i = $start; $i < $count; $i++ )
		{
			$id		= $type_voice[$i]['type_id'];
			$name	= $type_voice[$i]['type_name'];
			$game	= $type_voice[$i]['type_game'];
			$dport	= $type_voice[$i]['type_dport'];
			
			$template->assign_block_vars('_display._voice_row', array(
				'NAME'		=> href('a_txt', $file, array('?mode' => '_update', $url => $id), $name, ''),
			
				'GAME'		=> $game,
				'DPORT'		=> $dport,
				
				'UPDATE'	=> href('a_img', $file, array('?mode' => '_update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('?mode' => '_delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		'L_GAME'	=> $lang['serv_game'],
		'L_VOICE'	=> $lang['serv_voice'],
		
		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>