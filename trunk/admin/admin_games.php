<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_games'] )
	{
		$module['_headmenu_08_games']['_submenu_games'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_games';
	
	include('./pagestart.php');
	
	load_lang('games');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_GAMES;
	$url	= POST_GAMES_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_games'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['game']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_games'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_games.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
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
				
				$template->assign_vars(array('PATH' => $path_dir));
				$template->assign_var_from_handle('UIMG', 'uimg');
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$data = array(
								'game_name'		=> request('game_name', 1),
								'game_tag'		=> '',
								'game_image'	=> '',
								'game_size'		=> '16',
								'game_order'	=> '',
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(GAMES, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
								'game_name'		=> request('game_name', 2),
								'game_tag'		=> strtolower(request('game_tag', 2)),
								'game_image'	=> request('game_image', 2),
								'game_size'		=> request('game_size', 0),
								'game_order'	=> request('game_order', 0) ? request('game_order', 0) : request('game_order_new', 0),
							);
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['game']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['game'], $data['game_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['game']),
					'L_TAG'		=> sprintf($lang['sprintf_tag'], $lang['game']),
					'L_IMAGE'	=> sprintf($lang['sprintf_image'], $lang['game']),
					'L_SIZE'	=> sprintf($lang['sprintf_size'], $lang['common_image']),
					
					'NAME'		=> $data['game_name'],
					'TAG'		=> $data['game_tag'],
					'SIZE'		=> $data['game_size'],
					'IMAGE'		=> $data['game_image'] ? $path_dir . $data['game_image'] : $images['icon_acp_spacer'],
					
					'S_ORDER'	=> simple_order(GAMES, 'game_id != -1', 'select', $data['game_order']),
					'S_IMAGE'	=> select_box_files('post', 'game_image', $path_dir, $data['game_image']),
										
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					debug($_POST);
					
					$_ary = array(
								'game_name' => $data['game_name'],
								'game_id' => $data_id,
							);
							
					$error .= check(GAMES, $_ary, $error);
					$error .= empty($data['game_tag']) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_tag'] : '';
					
					if ( !$error )
					{
						$data['game_order'] = ( !$data['game_order'] ) ? maxa(GAMES, 'game_order', 'game_id != -1') : $data['game_order'];
												
						if ( $mode == '_create' )
						{
							$sql = sql(GAMES, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(GAMES, $mode, $data, 'game_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(GAMES, -1);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
				
				$template->pparse('body');
				
				break;
			
			case '_order':
				
				update(GAMES, 'game', $move, $data_id);
				orders(GAMES, '-1');
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete':
			
				$data = data(GAMES, $data_id, false, 1, true);
			
				if ( $data_id && $confirm )
				{
					$sql = sql(GAMES, $mode, $data, 'game_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(GAMES, '-1');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['game_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['game'])); }
				
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
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['game']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['game']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['game']),
		
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max = maxi(GAMES, 'game_order', '');
	$games = data(GAMES, 'game_id != -1', 'game_order ASC', 1, false);
	
	if ( !$games )
	{
		$template->assign_block_vars('_display._entry_empty', array());
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($games)); $i++ )
		{
			$game_id	= $games[$i]['game_id'];
			$game_size	= $games[$i]['game_size'];
			$game_order	= $games[$i]['game_order'];
			$game_image	= $games[$i]['game_image'] ? $path_dir . $games[$i]['game_image'] : $images['icon_acp_spacer'];
			
			$template->assign_block_vars('_display._game_row', array(
				'NAME'		=> $games[$i]['game_name'],
				'TAG'		=> $games[$i]['game_tag'],
				'IMAGE'		=> "<img src=\"$game_image\" width=\"$game_size\" height=\"$game_size\" alt=\"\" />",
				
				'MOVE_UP'	=> ( $game_order != '10' ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=-15&amp;$url=$game_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $game_order != $max ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=+15&amp;$url=$game_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$game_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$game_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>