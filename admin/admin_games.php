<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_games'] )
	{
		$module['_headmenu_main']['_submenu_games'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_games';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('game');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_GAMES_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_games'] . '/';
	$show_index	= '';
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_games'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_GAME, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['sprintf_auth_fail'], $lang[$current]));
	}
	
	if ( $no_header )
	{
		redirect('admin/' . append_sid('admin_games.php', true));
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_games.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 2) )
				{
					$data = array(
						'game_name'		=> request('game_name', 2),
						'game_image'	=> '',
						'game_size'		=> '16',
					);
				}
				else if ( $mode == '_update' && !request('submit', 2) )
				{
					$data = get_data(GAMES, $data_id, 1);
				}
				else
				{
					$data = array(
						'game_name'		=> request('game_name', 2),
						'game_image'	=> request('game_image', 2),
						'game_size'		=> request('game_size', 0),
					);
				}
				
				$s_fields	= '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_GAMES_URL . '" value="' . $data_id . '" />';
				$s_sprintf	= ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
		
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['game']),
					'L_INPUT'	=> sprintf($lang[$s_sprintf], $lang['game'], $data['game_name']),
					
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['game']),
					'L_IMAGE'	=> sprintf($lang['sprintf_image'], $lang['game']),
					'L_SIZE'	=> $lang['game_size'],
					
					'TITLE'			=> $data['game_name'],
					'SIZE'			=> $data['game_size'],
					'IMAGE'			=> ( $data['game_image'] ) ? $path_dir . $data['game_image'] : $images['icon_acp_spacer'],
					'IMAGE_LIST'	=> select_box_files('game_image', 'post', $path_dir, $data['game_image']),
					'IMAGE_PATH'	=> $path_dir,
					'IMAGE_DEFAULT'	=> $images['icon_acp_spacer'],
					
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_games.php'),
				));
				
				if ( request('submit', 2) )
				{
					$game_name	= request('game_name', 2);
					$game_image	= request('game_image', 2);
					$game_size	= ( request('game_size', 0) ) ? request('game_size', 0) : '16';
					
					$error = ( !$game_name ) ? $lang['msg_select_name'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$max_row	= get_data_max(GAMES, 'game_order', '');
							$next_order	= $max_row['max'] + 10;
							
							$sql = "INSERT INTO " . GAMES . " (game_name, game_image, game_size, game_order) VALUES ('$game_name', '$game_image', '$game_size', '$next_order')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . GAMES . " SET game_name = '$game_name', game_image = '$game_image', game_size = '$game_size' WHERE game_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_game']
								. sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_games.php?mode=_update&amp;' . POST_GAMES_URL . '=' . $data_id) . '">', '</a>');
						}
						
						log_add(LOG_ADMIN, LOG_SEK_GAME, $mode, $game_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');
				
				break;
			
			case '_order':
				
				update(GAMES, 'game', $move, $data_id);
				orders(GAMES, -1);
				
				log_add(LOG_ADMIN, LOG_SEK_GAME, $mode);
				
				$show_index = true;
				
				break;
				
			case '_delete':
			
				$data = get_data(GAMES, $data_id, 1);
			
				if ( $data_id && $confirm )
				{	
					$sql = "DELETE FROM " . GAMES . " WHERE game_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['delete_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>');
					
					log_add(LOG_ADMIN, LOG_SEK_GAME, $mode, $data['game_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_GAMES_URL . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_game'], $data['game_name']),

						'S_FIELDS'	=> $s_fields,
						'S_ACTION'	=> append_sid('admin_games.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_game']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
				
				message(GENERAL_ERROR, $lang['no_mode']);
				
				break;
		}
	
		if ( $show_index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
			
	$template->set_filenames(array('body' => 'style/acp_games.tpl'));
	$template->assign_block_vars('_display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['game']),
		'L_CREATE'	=> sprintf($lang['sprintf_creates'], $lang['game']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['game']),
		
		'L_EXPLAIN'	=> $lang['game_explain'],
		
		'S_FIELDS'	=> $s_fields,
		'S_CREATE'	=> append_sid('admin_games.php?mode=_create'),
		'S_ACTION'	=> append_sid('admin_games.php'),
	));
	
	$max	= get_data_max(GAMES, 'game_order', '');
	$data	= get_data_array(GAMES, 'game_id != -1', 'game_order', 'ASC');
	
	if ( $data )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data)); $i++ )
		{
			$game_id	= $data[$i]['game_id'];
			$game_size	= $data[$i]['game_size'];
			$game_image	= ( $data[$i]['game_image'] ) ? $path_dir . $data[$i]['game_image'] : $images['icon_acp_spacer'];
			
			$template->assign_block_vars('_display._game_row', array(
				'NAME'	=> $data[$i]['game_name'],
				'IMAGE'	=> '<img src="' . $game_image . '" width="' . $game_size . '" height="' . $game_size . '" alt="" />',
				
				'MOVE_UP'	=> ( $data[$i]['game_order'] != '10' )			? '<a href="' . append_sid('admin_games.php?mode=_order&amp;move=-15&amp;' . POST_GAMES_URL . '=' . $game_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data[$i]['game_order'] != $max['max'] )	? '<a href="' . append_sid('admin_games.php?mode=_order&amp;move=15&amp;' . POST_GAMES_URL . '=' . $game_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid('admin_games.php?mode=_update&amp;' . POST_GAMES_URL . '=' . $game_id),
				'U_DELETE'	=> append_sid('admin_games.php?mode=_delete&amp;' . POST_GAMES_URL . '=' . $game_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>