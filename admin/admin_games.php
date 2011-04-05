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
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
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
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_games.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$max = get_data_max(GAMES, 'game_order', '');
					$data = array(
								'game_name'		=> request('game_name', 2),
								'game_tag'		=> '',
								'game_image'	=> '',
								'game_size'		=> '16',
								'game_order'	=> $max['max'] + 10,
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(GAMES, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
								'game_name'		=> request('game_name', 2),
								'game_tag'		=> request('game_tag', 2),
								'game_image'	=> request('game_image', 2),
								'game_size'		=> request('game_size', 0),
								'game_order'	=> ( request('game_order_new', 0) ) ? request('game_order_new', 0) : request('game_order', 0),
							);
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"game_order\" value=" . $data['game_order'] . " />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['game']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['game'], $data['game_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['game']),
					'L_TAG'		=> sprintf($lang['sprintf_tag'], $lang['game']),
					'L_IMAGE'	=> sprintf($lang['sprintf_image'], $lang['game']),
					'L_SIZE'	=> sprintf($lang['sprintf_size'], $lang['common_image']),
					'L_ORDER'	=> $lang['common_order'],
					
					'NAME'		=> $data['game_name'],
					'TAG'		=> $data['game_tag'],
					'SIZE'		=> $data['game_size'],
					'PATH'		=> $path_dir,
					'IMAGE'		=> ( $data['game_image'] ) ? $path_dir . $data['game_image'] : $images['icon_acp_spacer'],
					
					'S_ORDER'	=> select_order('select', GAMES, 'game', $data['game_order']),
					'S_IMAGE'	=> select_box_files('post', 'game_image', $path_dir, $data['game_image']),
										
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$data = array(
								'game_name'		=> request('game_name', 2),
								'game_tag'		=> request('game_tag', 2),
								'game_image'	=> request('game_image', 2),
								'game_size'		=> request('game_size', 0),
								'game_order'	=> request('game_order', 0) ? request('game_order', 0) : request('game_order_new', 0),
							);
							
					$error .= ( !$data['game_name'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['game_tag'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_tag'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							sql(GAMES, 'insert', $data);
							
							$message = $lang['create'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							sql(GAMES, 'update', $data, 'game_id', $data_id);
							
							$message = $lang['update']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
						}
						
						orders(GAMES, -1);
						
						log_add(LOG_ADMIN, $log, $mode, $data['game_name']);
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
				
				update(GAMES, 'game', $move, $data_id);
				orders(GAMES, '-1');
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete':
			
				$data = data(GAMES, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{
				#	sql(GAMES, 'delete', false, 'game_id', $data_id);
					$sql = "DELETE FROM " . GAMES . " WHERE game_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['delete'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					
					orders(GAMES, '-1');
					
					log_add(LOG_ADMIN, $log, $mode, $data['game_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['game_name']),

						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['game'])); }
				
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
			
	$template->set_filenames(array('body' => 'style/acp_games.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['game']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['game']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['game']),
		
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> append_sid("$file?mode=_create"),
		'S_ACTION'	=> append_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max = get_data_max(GAMES, 'game_order', '');
#	$tmp = get_data_array(GAMES, 'game_id != -1', 'game_order', 'ASC');
	$tmp = data(GAMES, '-1', 'game_order ASC', 1, false);
	
	if ( $tmp )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp)); $i++ )
		{
			$game_id	= $tmp[$i]['game_id'];
			$game_size	= $tmp[$i]['game_size'];
			$game_order	= $tmp[$i]['game_order'];
			$game_image	= ( $tmp[$i]['game_image'] ) ? $path_dir . $tmp[$i]['game_image'] : $images['icon_acp_spacer'];
			
			$template->assign_block_vars('_display._game_row', array(
				'NAME'		=> $tmp[$i]['game_name'],
				'TAG'		=> $tmp[$i]['game_tag'],
				'IMAGE'		=> '<img src="' . $game_image . '" width="' . $game_size . '" height="' . $game_size . '" alt="" />',
				
				'MOVE_UP'	=> ( $game_order != '10' )			? '<a href="' . append_sid("$file?mode=_order&amp;move=-15&amp;$url=$game_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $game_order != $max['max'] )	? '<a href="' . append_sid("$file?mode=_order&amp;move=+15&amp;$url=$game_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$game_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$game_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry', array()); }
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>