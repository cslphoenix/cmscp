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
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_server_type'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_server_type.tpl',
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
				
				$vars = array(
					'type' => array(
						'title1' => 'input_data',
						'type_name'		=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name'),
						'type_game'		=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_game'),
						'type_dport'	=> array('validate' => INT,	'type' => 'text:10:10',		'explain' => true, 'required' => 'input_dport'),
						'type_sort'		=> array('validate' => INT,	'type' => 'radio:types', 	'explain' => true),
					),
				);
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					$data = array(
								'type_name'		=> request('type_name', TXT),
								'type_game'		=> '',
								'type_dport'	=> '',
								'type_sort'		=> '',
							);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(SERVER_TYPE, $data_id, false, 1, true);
				}
				else
				{
					$temp = data(SERVER_TYPE, $data_id, false, 1, true);
					$temp = array_keys($temp);
					unset($temp[0]);
					
					$data = build_request($temp, $vars, 'type', $error);
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$sql = sql(SERVER_TYPE, $mode, $data);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(SERVER_TYPE, $mode, $data, 'type_id', $data_id);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
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
				
				build_output($data, $vars, 'input', false, SERVER_TYPE);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['type_name']),
				
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
			
			case 'delete':
			
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
			
	$template->assign_block_vars('display', array());
	
	$fields		= '<input type="hidden" name="mode" value="create" />';
	
	$type_game	= data(SERVER_TYPE, 'type_sort = 0', 'type_id ASC', 1, false);
	$type_voice	= data(SERVER_TYPE, 'type_sort = 1', 'type_id ASC', 1, false);
			
	if ( !$type_game )
	{
		$template->assign_block_vars('display.game_empty', array());
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
			
			$template->assign_block_vars('display.game_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
			
				'GAME'		=> $game,
				'DPORT'		=> $dport,
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$type_voice )
	{
		$template->assign_block_vars('display.voice_empty', array());
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
			
			$template->assign_block_vars('display.voice_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
			
				'GAME'		=> $game,
				'DPORT'		=> $dport,
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		'L_GAME'	=> $lang['serv_game'],
		'L_VOICE'	=> $lang['serv_voice'],
		
		'S_CREATE'	=> check_sid("$file?mode=create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>