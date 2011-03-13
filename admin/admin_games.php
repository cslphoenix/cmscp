<?php

/*
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
 *	@autor:	Sebastian Frickel © 2009, 2010, 2011
 *	@code:	Sebastian Frickel © 2009, 2010, 2011
 *
 *	Games - Spiele
 *
 */

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_games'] )
	{
		$module['_headmenu_games']['_submenu_games'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$s_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_games';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('games');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request(POST_GAMES_URL, 0);	
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$confirm	= request('confirm', 1);
	
	$error		= '';
	$s_index	= '';
	$s_fields	= '';
	$r_file		= basename(__FILE__);
	$path_dir	= $root_path . $settings['path_games'] . '/';
	
	$p_url	= POST_GAMES_URL;
	$l_sec	= LOG_SEK_GAMES;
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_games'] )
	{
		log_add(LOG_ADMIN, $l_sec, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $s_header ) ? redirect('admin/' . append_sid($r_file, true)) : false;
	
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
					$max	= get_data_max(GAMES, 'game_order', '');
					$data	= array(
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
				
				$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
				$s_fields .= '<input type="hidden" name="' . $p_url . '" value="' . $data_id . '" />';
				$s_fields .= '<input type="hidden" name="game_order" value="' . $data['game_order'] . '" />';
				
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
					'S_IMAGE'	=> select_box_files('game_image', 'post', $path_dir, $data['game_image']),
										
					'S_ACTION'	=> append_sid($r_file),
					'S_FIELDS'	=> $s_fields,
				));
				
				if ( request('submit', 1) )
				{
					$game_name	= request('game_name', 2);
					$game_tag	= request('game_tag', 2);
					$game_image	= request('game_image', 2);
					$game_size	= ( request('game_size', 0) ) ? request('game_size', 0) : 16;
					$game_order	= ( request('game_order_new', 0) ) ? request('game_order_new', 0) : request('game_order', 0);
					
					$error .= ( !$game_name ) ? ( $error ? '<br />' : '' ) . sprintf($lang['sprintf_msg_select'], sprintf($lang['sprintf_name'], $lang['game'])) : '';
					$error .= ( !$game_tag ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_tag'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$sql = "INSERT INTO " . GAMES . " (game_name, game_tag, game_image, game_size, game_order) VALUES ('$game_name', '$game_tag', '$game_image', '$game_size', '$game_order')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$data_id = $db->sql_nextid();
							$message = $lang['create_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid($r_file) . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . GAMES . " SET
										game_name	= '$game_name',
										game_tag	= '$game_tag',
										game_image	= '$game_image',
										game_size	= '$game_size',
										game_order	= '$game_order'
									WHERE game_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_game']
								. sprintf($lang['click_return_game'], '<a href="' . append_sid($r_file) . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid($r_file . '?mode=_update&amp;' . $p_url . '=' . $data_id) . '">', '</a>');
						}
						
						orders(GAMES, -1);
						
						log_add(LOG_ADMIN, $l_sec, $mode, $game_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $l_sec, $mode, $error);
						
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
				
				log_add(LOG_ADMIN, $l_sec, $mode);
				
				$s_index = true;
				
				break;
				
			case '_delete':
			
				$data = data(GAMES, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{
					$sql = "DELETE FROM " . GAMES . " WHERE game_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['delete_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid($r_file) . '">', '</a>');
					
					orders(GAMES, '-1');
					
					log_add(LOG_ADMIN, $l_sec, $mode, $data['game_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
					$s_fields .= '<input type="hidden" name="' . $p_url . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_game'], $data['game_name']),

						'S_ACTION'	=> append_sid($r_file),
						'S_FIELDS'	=> $s_fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['game'])); }
				
				$template->pparse('body');
				
				break;
			
			default:
			
				message(GENERAL_ERROR, $lang['no_mode']);
				
				break;
		}
	
		if ( $s_index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
			
	$template->set_filenames(array('body' => 'style/acp_games.tpl'));
	$template->assign_block_vars('_display', array());
	
	$s_fields .= '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['game']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['game']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['game']),
		
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> append_sid($r_file . '?mode=_create'),
		'S_ACTION'	=> append_sid($r_file),
		'S_FIELDS'	=> $s_fields,
	));
	
	$max	= get_data_max(GAMES, 'game_order', '');
	$games	= get_data_array(GAMES, 'game_id != -1', 'game_order', 'ASC');
	
	if ( $games )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($games)); $i++ )
		{
			$game_id	= $games[$i]['game_id'];
			$game_size	= $games[$i]['game_size'];
			$game_order	= $games[$i]['game_order'];
			$game_image	= ( $games[$i]['game_image'] ) ? $path_dir . $games[$i]['game_image'] : $images['icon_acp_spacer'];
			
			$template->assign_block_vars('_display._game_row', array(
				'NAME'		=> $games[$i]['game_name'],
				'TAG'		=> $games[$i]['game_tag'],
				'IMAGE'		=> '<img src="' . $game_image . '" width="' . $game_size . '" height="' . $game_size . '" alt="" />',
				
				'MOVE_UP'	=> ( $game_order != '10' )			? '<a href="' . append_sid($r_file . '?mode=_order&amp;move=-15&amp;' . $p_url . '=' . $game_id) . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $game_order != $max['max'] )	? '<a href="' . append_sid($r_file . '?mode=_order&amp;move=+15&amp;' . $p_url . '=' . $game_id) . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid($r_file . '?mode=_update&amp;' . $p_url . '=' . $game_id),
				'U_DELETE'	=> append_sid($r_file . '?mode=_delete&amp;' . $p_url . '=' . $game_id),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry', array()); }
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>