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
 *	- @autor:	Sebastian Frickel � 2009
 *	- @code:	Sebastian Frickel � 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_games'] || $userdata['user_level'] == ADMIN )
	{
		$module['main']['games_over'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', 1);
	
	$root_path	= './../';
	$cancel		= ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/functions.php');
	
	if ( !$userauth['auth_games'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_games.php', true));
	}
	
	$mode		= request('mode', true);
	$game_id	= request(POST_GAMES_URL);
	$show_index = '';
		
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case 'game_add':
			case 'game_edit':
				
				$template->set_filenames(array('body' => 'style/acp_games.tpl'));
				$template->assign_block_vars('games_edit', array());
				
				if ( $mode == 'game_edit' )
				{
					$game		= get_data('games', $game_id, 0);
					$new_mode	= 'game_update';
				}
				else
				{
					$game = array (
						'game_name'		=> request('game_name', true),
						'game_image'	=> '',
						'game_size'		=> '16',
						'game_order'	=> ''
					);
	
					$new_mode = 'game_create';
				}
				
				$folder	= $root_path . $settings['path_game'];
				$files	= scandir($folder);
				
				$game_list = '';
				$game_list .= '<select name="game_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
				$game_list .= '<option value="">----------</option>';
				
				foreach ($files as $file)
				{
					if ( $file != '.' && $file != '..' && $file != 'index.htm' && $file != '.svn' )
					{
						$selected = ( $file == $game['game_image'] ) ? ' selected="selected"' : '';
						$game_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$game_list .= '</select>';
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_GAMES_URL . '" value="' . $game_id . '" />';
	
				$template->assign_vars(array(
					'L_GAME_HEAD'			=> $lang['game_head'],
					'L_GAME_NEW_EDIT'		=> ( $mode == 'game_add' ) ? $lang['game_add'] : $lang['game_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_GAME_NAME'			=> $lang['game_title'],
					'L_GAME_IMAGE'			=> $lang['game_image'],
					'L_GAME_SIZE'			=> $lang['game_size'],
					
					'L_RESET'				=> $lang['common_reset'],
					'L_SUBMIT'				=> $lang['common_submit'],
					
					'GAME_TITLE'			=> $game['game_name'],
					'GAME_IMAGE'			=> ( $mode == 'game_add' ) ? $root_path . $images['icon_acp_spacer'] : $root_path . $settings['path_game'] . '/' . $game['game_image'],
					'GAME_SIZE'				=> $game['game_size'],
					'GAMES_PATH'			=> $root_path . $settings['path_game'],
					
					'S_FILENAME_LIST'		=> $game_list,
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_GAME_ACTION'			=> append_sid('admin_games.php'),
				));
			
				$template->pparse('body');
				
			break;
			
			case 'game_create':
				
				$game_name	= request('game_name', true);
				$game_image	= request('game_image', true);
				$game_size	= ( isset($HTTP_POST_VARS['game_size']) ) ? intval($HTTP_POST_VARS['game_size']) : '16';
				
				if ( $game_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				else
				{	
					$sql = 'SELECT MAX(game_order) AS max_order FROM ' . GAMES;
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
		
					$max_order	= $row['max_order'];
					$next_order	= $max_order + 10;
					
					$sql = 'INSERT INTO ' . GAMES . " (game_name, game_image, game_size, game_order) VALUES ('" . str_replace("\'", "''", $game_name) . "', '" . str_replace("\'", "''", $game_image) . "', $game_size, $next_order)";
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'acp_game_add', $game_name);
		
					$message = $lang['create_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}
	
				break;
			
			case 'game_update':
			
				$game_name	= request('game_name', true);
				$game_image	= request('game_image', true);
				$game_size	= ( isset($HTTP_POST_VARS['game_size']) ) ? intval($HTTP_POST_VARS['game_size']) : '16';
				
				if ( $game_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				else
				{				
					$sql = "UPDATE " . GAMES . " SET
								game_name		= '" . str_replace("\'", "''", $game_name) . "',
								game_image		= '" . str_replace("\'", "''", $game_image) . "',
								game_size		= $game_size							
							WHERE game_id = " . $game_id;
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'acp_game_edit', $game_name);
					
					$message = $lang['update_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}
	
				break;
			
			case 'game_order':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . GAMES . " SET game_order = game_order + $move WHERE game_id = $game_id";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
		
				renumber_order('games', -1);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'acp_game_order');
				
				$show_index = TRUE;
				
				break;
				
			case 'game_delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $game_id && $confirm )
				{	
					$game = get_data('games', $game_id, 0);
				
					$sql = 'DELETE FROM ' . GAMES . ' WHERE game_id = ' . $game_id;
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					debug(serialize($game));
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'acp_game_delete', serialize($game));
					
					$message = $lang['delete_game'] . sprintf($lang['click_return_game'], '<a href="' . append_sid('admin_games.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}
				else if ( $game_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="game_delete" /><input type="hidden" name="' . POST_GAMES_URL . '" value="' . $game_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_game'],
		
						'L_NO'				=> $lang['common_no'],
						'L_YES'				=> $lang['common_yes'],						
		
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
						'S_CONFIRM_ACTION'	=> append_sid('admin_games.php'),
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_games']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
				
				message_die(GENERAL_ERROR, $lang['no_mode']);
				
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
			
	$template->assign_vars(array(
		'L_GAME_TITLE'		=> $lang['game_head'],
		'L_GAME_EXPLAIN'	=> $lang['game_explain'],
		'L_GAME_NAME'		=> $lang['game_name'],
		
		'L_GAME_ADD'		=> $lang['game_add'],
		
		'L_EDIT'			=> $lang['common_edit'],
		'L_DELETE'			=> $lang['common_delete'],
		'L_SETTINGS'		=> $lang['settings'],		
		
		'L_MOVE_UP'			=> $lang['move_up'], 
		'L_MOVE_DOWN'		=> $lang['move_down'], 
		
		'S_GAME_ACTION'		=> append_sid('admin_games.php'),
	));
	
	$sql = 'SELECT MAX(game_order) AS max FROM ' . GAMES;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$max_order = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT * FROM ' . GAMES . ' WHERE game_id != -1 ORDER BY game_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$color = '';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$class = ( $color % 2 ) ? 'row_class1' : 'row_class2';
		$color++;
		
		$game_id	= $row['game_id'];
		$game_size	= (!$row['game_size']) ? '16' : $row['game_size'];
		
		$template->assign_block_vars('display.game_row', array(
			'CLASS' 		=> $class,
			'NAME'			=> $row['game_name'],
			'I_IMAGE'		=> '<img src="' . $root_path . $settings['path_game'] . '/' . $row['game_image'] . '" width="' . $game_size . '" height="' . $game_size . '" alt="">',
			
			'MOVE_UP'		=> ( $row['game_order'] != '10' )				? '<a href="' . append_sid('admin_games.php?mode=game_order&amp;move=-15&amp;' . POST_GAMES_URL . '=' . $game_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
			'MOVE_DOWN'		=> ( $row['game_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_games.php?mode=game_order&amp;move=15&amp;' . POST_GAMES_URL . '=' . $game_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
			
			'U_DELETE'		=> append_sid('admin_games.php?mode=game_delete&amp;' . POST_GAMES_URL . '=' . $game_id),
			'U_EDIT'		=> append_sid('admin_games.php?mode=game_edit&amp;' . POST_GAMES_URL . '=' . $game_id),
		));
	}

	if ( !$db->sql_numrows($result) )
	{
		$template->assign_block_vars('display.no_games', array());
		$template->assign_vars(array('NO_GAMES' => $lang['game_empty']));
	}
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>