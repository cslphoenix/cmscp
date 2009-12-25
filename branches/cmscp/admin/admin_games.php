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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_games'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_main']['_submenu_games'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/game.php');
	
	$start		= ( request('start') ) ? request('start') : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$game_id	= request(POST_GAMES_URL);
	$confirm	= request('confirm');
	$mode		= request('mode');
	$move		= request('move');	
	$path_game	= $root_path . $settings['path_game'] . '/';
	$show_index	= '';
	
	if ( !$userauth['auth_games'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
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
				$template->assign_block_vars('games_edit', array());
				
				if ( $mode == '_create' )
				{
					$game = array (
						'game_name'		=> request('game_name', 'text'),
						'game_image'	=> '',
						'game_size'		=> '16',
						'game_order'	=> ''
					);
					$new_mode = '_create_save';
				}
				else
				{
					$game = get_data(GAMES, $game_id, 1);
					$new_mode = '_update_save';
				}
				
				$files	= scandir($path_game);
				
				$game_list = '';
				$game_list .= '<select name="game_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
				$game_list .= '<option value="">----------</option>';
				
				foreach ( $files as $file )
				{
					if ( $file != '.' && $file != '..' && $file != 'index.htm' && $file != '.svn' )
					{
						$selected = ( $file == $game['game_image'] ) ? ' selected="selected"' : '';
						$game_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$game_list .= '</select>';
				
				$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
				$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_GAMES_URL . '" value="' . $game_id . '" />';
		
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['game']),
					'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['game']),
					'L_NAME'		=> sprintf($lang['sprintf_title'], $lang['game']),
					'L_IMAGE'		=> sprintf($lang['sprintf_image'], $lang['game']),
					'L_SIZE'		=> $lang['game_size'],
					
					'TITLE'			=> $game['game_name'],
					'SIZE'			=> $game['game_size'],
					'IMAGE_LIST'	=> $game_list,
					'IMAGE'			=> ( $mode == '_update' ) ? ( $game['game_image'] ) ? $path_game . $game['game_image'] : $images['icon_acp_spacer'] : $images['icon_acp_spacer'],
					'IMAGE_PATH'	=> $path_game,
					'IMAGE_DEFAULT'	=> $images['icon_acp_spacer'],
					
					'S_FIELDS'		=> $s_fields,
					'S_ACTION'		=> append_sid('admin_games.php'),
				));
				
				$template->pparse('body');
			
				break;
			
			case '_create_save':
			
				$game_name	= request('game_name', 'text');
				$game_image	= request('game_image', 'text');
				$game_size	= ( request('game_size') ) ? request('game_size', 'num') : '16';
				
				if ( !$game_name )
				{
					message(GENERAL_ERROR, $lang['msg_select_name'] . $lang['back']);
				}
				
				$max_row	= get_data_max(GAMES, 'game_order', '');
				$next_order	= $max_order['max'] + 10;
				
				$sql = "INSERT INTO " . GAMES . " (game_name, game_image, game_size, game_order)
							VALUES ('$game_name', '$game_image', '$game_size', '$next_order')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['create_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'create_game');
				message(GENERAL_MESSAGE, $message);
	
				break;
			
			case '_update_save':
			
				$game_name	= request('game_name', 'text');
				$game_image	= request('game_image', 'text');
				$game_size	= ( request('game_size') ) ? request('game_size', 'num') : '16';
				
				if ( !$game_name )
				{
					message(GENERAL_ERROR, $lang['msg_select_name'] . $lang['back']);
				}
				
				$sql = "UPDATE " . GAMES . " SET
							game_name		= '$game_name',
							game_image		= '$game_image',
							game_size		= '$game_size'
						WHERE game_id = $game_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['update_game']
					. sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>')
					. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_games.php?mode=_update&amp;' . POST_GAMES_URL . '=' . $game_id) . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'update_game');
				message(GENERAL_MESSAGE, $message);
					
				break;
			
			case '_order':
				
				update(GAMES, 'game', $move, $game_id);
				orders('games', -1);
				
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'acp_game_order');
				
				$show_index = TRUE;
				
				break;
				
			case '_delete':
			
			#	$game = get_data(GAMES, $game_id, 1);
			
				if ( $game_id && $confirm )
				{	
					$sql = "DELETE FROM " . GAMES . " WHERE game_id = $game_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['delete_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>');
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'delete_game');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $game_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_GAMES_URL . '" value="' . $game_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> $lang['confirm_delete_game'],

						'S_FIELDS'		=> $s_fields,
						'S_ACTION'		=> append_sid('admin_games.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_games']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
				
				message(GENERAL_ERROR, $lang['no_mode']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
			
	$template->set_filenames(array('body' => 'style/acp_games.tpl'));
	$template->assign_block_vars('display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['game']),
		'L_EXPLAIN'	=> $lang['game_explain'],
		
		'L_CREATE'	=> sprintf($lang['sprintf_creates'], $lang['game']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['game']),
		
		'S_FIELDS'	=> $s_fields,
		'S_CREATE'	=> append_sid('admin_games.php?mode=_create'),
		'S_ACTION'	=> append_sid('admin_games.php'),
	));
	
	$max_order	= get_data_max(GAMES, 'game_order', '');
	$games_data	= get_data_array(GAMES, 'game_id != -1', 'game_order', 'ASC');
	
	if ( $games_data )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($games_data)); $i++ )
		{
			$game_id	= $games_data[$i]['game_id'];
			$game_size	= $games_data[$i]['game_size'];
			
			$template->assign_block_vars('display.game_row', array(
				'CLASS'		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				
				'NAME'		=> $games_data[$i]['game_name'],
				'IMAGE'		=> '<img src="' . $path_game . $games_data[$i]['game_image'] . '" width="' . $game_size . '" height="' . $game_size . '" alt="">',
				
				'MOVE_UP'	=> ( $games_data[$i]['game_order'] != '10' )				? '<a href="' . append_sid('admin_games.php?mode=_order&amp;move=-15&amp;' . POST_GAMES_URL . '=' . $game_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'	=> ( $games_data[$i]['game_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_games.php?mode=_order&amp;move=15&amp;' . POST_GAMES_URL . '=' . $game_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_UPDATE'	=> append_sid('admin_games.php?mode=_update&amp;' . POST_GAMES_URL . '=' . $game_id),
				'U_DELETE'	=> append_sid('admin_games.php?mode=_delete&amp;' . POST_GAMES_URL . '=' . $game_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>