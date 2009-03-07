<?php

/***

	
	admin_games.php
	
	Erstellt von Phoenix
	
	
***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($userdata['auth_games'] || $userdata['user_level'] == ADMIN)
	{
		$module['main']['games_over'] = $filename;
	}
	return;
}
else
{
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	include($root_path . 'includes/functions_admin.php');
	
	if (!$userdata['auth_games'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_match.php", true));
	}
	
	//	ID Abfrage
	if ( isset($HTTP_POST_VARS[POST_GAMES_URL]) || isset($HTTP_GET_VARS[POST_GAMES_URL]) )
	{
		$game_id = ( isset($HTTP_POST_VARS[POST_GAMES_URL]) ) ? intval($HTTP_POST_VARS[POST_GAMES_URL]) : intval($HTTP_GET_VARS[POST_GAMES_URL]);
	}
	else
	{
		$game_id = 0;
	}
	
	//	mode Abfrage
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		$mode = '';
	}
	
	$show_index = '';
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
				
				if ( $mode == 'edit' )
				{
					//	Infos der Spiele
					$sql = 'SELECT * FROM ' . GAMES_TABLE . ' WHERE game_id = ' . $game_id;
					$result = $db->sql_query($sql);
			
					if ( !($game = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['game_not_exist']);
					}
			
					$new_mode = 'editgame';
				}
				else if ( $mode == 'add' )
				{
					//	Start Werte setzen
					$game = array (
						'game_name'	=> trim($HTTP_POST_VARS['game_name']),
						'game_image'	=> '',
						'game_size'		=> '16',
						'game_order'	=> ''
					);

					$new_mode = 'addgame';
				}
				
				//	Template definieren
				$template->set_filenames(array('body' => './../admin/style/games_edit_body.tpl'));
				
				//	Gamebilder auslesen, ganz einfach, mit Dropdown erstelluung
				
				$folder = $root_path . $settings['game_path'];
				$files = scandir($folder);
				
				$game_list = '';
				$game_list .= '<select name="game_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
				$game_list .= '<option value="">----------</option>';
				
				foreach ($files as $file)
				{
					if ($file != '.' && $file != '..' && $file != 'index.htm')
					{
						$selected = ( $file == $game['game_image'] ) ? ' selected="selected"' : '';
						$game_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$game_list .= "</select>";
				
				//	Unsichtbare Felder für andere Infos
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_GAMES_URL . '" value="' . $game_id . '" />';

				//	Variablen zur Ausgabe
				$template->assign_vars(array(
					'L_GAME_HEAD'			=> $lang['game_head'],
					'L_GAME_NEW_EDIT'		=> ($mode == 'add') ? $lang['game_add'] : $lang['game_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_GAME_NAME'			=> $lang['game_title'],
					'L_GAME_IMAGE'			=> $lang['game_image'],
					'L_GAME_SIZE'			=> $lang['game_size'],
									
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					
					'GAME_TITLE'			=> $game['game_name'],
					'GAME_IMAGE'			=> ($mode == 'add') ? $root_path . 'images/spacer.gif' : $root_path . $settings['game_path'] . '/' . $game['game_image'],
					'GAME_SIZE'				=> $game['game_size'],
					
					'GAMES_PATH'			=> $root_path . $settings['game_path'],
					
					'S_FILENAME_LIST'		=> $game_list,
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_TEAM_ACTION'			=> append_sid("admin_games.php")
				));
			
				// Template ausgabe
				$template->pparse('body');
				
			break;
			
			case 'addgame':
				
				$game_name		= (isset($HTTP_POST_VARS['game_name']))		? trim($HTTP_POST_VARS['game_name']) : '';
				$game_image		= (isset($HTTP_POST_VARS['game_image']))	? trim($HTTP_POST_VARS['game_image']) : '';
				$game_size		= (isset($HTTP_POST_VARS['game_size']))		? intval($HTTP_POST_VARS['game_size']) : '16';
				
				if( $game_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['game_not_exist'], '', __LINE__, __FILE__);
				}
	
				$sql = 'SELECT MAX(game_order) AS max_order FROM ' . GAMES_TABLE;
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				// There is no problem having duplicate forum names so we won't check for it.
				$sql = 'INSERT INTO ' . GAMES_TABLE . " (game_name, game_image, game_size, game_order)
					VALUES ('" . str_replace("\'", "''", $game_name) . "', '" . str_replace("\'", "''", $game_image) . "', $game_size, $next_order)";
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, ACP_GAME_ADD, $game_title);
	
				$message = $lang['game_create'] . '<br /><br />' . sprintf($lang['click_return_game'], "<a href=\"" . append_sid("admin_games.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editgame':
			
				$game_name		= (isset($HTTP_POST_VARS['game_name']))	? trim($HTTP_POST_VARS['game_name']) : '';
				$game_image		= (isset($HTTP_POST_VARS['game_image']))	? trim($HTTP_POST_VARS['game_image']) : '';
				$game_size		= (isset($HTTP_POST_VARS['game_size']))		? intval($HTTP_POST_VARS['game_size']) : '16';
				
				if( $game_name == '' )
				{
					message_die(GENERAL_MESSAGE, $lang['game_not_exist'], '', __LINE__, __FILE__);
				}
				
//				$log_data = ($team_info['team_name'] != $HTTP_POST_VARS['team_name']) ? $team_info['team_name'] . '>' . str_replace("\'", "''", $HTTP_POST_VARS['team_name'])  : '';
				$sql = "UPDATE " . GAMES_TABLE . " SET
							game_name		= '" . str_replace("\'", "''", $game_name) . "',
							game_image		= '" . str_replace("\'", "''", $game_image) . "',
							game_size		= $game_size							
						WHERE game_id = " . intval($HTTP_POST_VARS[POST_GAMES_URL]);
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, ACP_TEAM_EDIT, $log_data);
				
				$message = $lang['game_update'] . '<br /><br />' . sprintf($lang['click_return_game'], "<a href=\"" . append_sid("admin_games.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $game_id && $confirm )
				{	
					$sql = 'SELECT * FROM ' . GAMES_TABLE . " WHERE game_id = $game_id";
					$result = $db->sql_query($sql);
			
					if ( !($team_info = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['game_not_exist']);
					}
				
					$sql = 'DELETE FROM ' . GAMES_TABLE . " WHERE game_id = $game_id";
					$result = $db->sql_query($sql);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, ACP_GAME_DELETE, $team_info['game_title']);
					
					$message = $lang['team_delete'] . '<br /><br />' . sprintf($lang['click_return_game'], "<a href=\"" . append_sid("admin_games.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $game_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_GAMES_URL . '" value="' . $game_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_game'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_games.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['must_select_games']);
				}
				
				$template->pparse("body");
				
				break;
			
			case 'order':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . GAMES_TABLE . " SET game_order = game_order + $move WHERE game_id = $game_id";
				$result = $db->sql_query($sql);
		
				renumber_order('games', -1);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'ACP_GAME_ORDER');
				
				$show_index = TRUE;
	
				break;
				
			default:
				message_die(GENERAL_ERROR, 'kein modul');
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/games_body.tpl'));
			
	$template->assign_vars(array(
		'L_GAME_TITLE'			=> $lang['game_head'],
		'L_GAME_EXPLAIN'		=> $lang['game_explain'],
		
		'L_GAME_ADD'			=> $lang['game_add'],
		
		'L_SETTING'				=> $lang['setting'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'L_MOVE_UP'				=> $lang['Move_up'], 
		'L_MOVE_DOWN'			=> $lang['Move_down'], 
		
		'ICON_MOVE_UP'			=> '<img src="./../admin/style/images/icon_arrow_up.png" alt="Up" title="" width="12" height="12" />',
		'ICON_MOVE_DOWN'		=> '<img src="./../admin/style/images/icon_arrow_down.png" alt="Down" title="" width="12" height="12" />',
		
		'S_TEAM_ACTION'		=> append_sid("admin_games.php")
	));
	
	$sql = 'SELECT * FROM ' . GAMES_TABLE . ' WHERE game_id != -1 ORDER BY game_order';
	$result = $db->sql_query($sql);
	
	$color = '';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$class = ($color % 2) ? 'row_class1' : 'row_class2';
		$color++;
		
		$game_size = (!$row['game_size']) ? '16' : $row['game_size'];
		
		$template->assign_block_vars('game_row', array(
			'CLASS' 		=> $class,
			'NAME'			=> $row['game_name'],
			'I_IMAGE'		=> '<img src="' . $root_path . $settings['game_path'] . '/' . $row['game_image'] . '" width="' . $game_size . '" height="' . $game_size . '" alt="">',
			
			'U_DELETE'		=> append_sid("admin_games.php?mode=delete&amp;" . POST_GAMES_URL . "=".$row['game_id']),
			'U_EDIT'		=> append_sid("admin_games.php?mode=edit&amp;" . POST_GAMES_URL . "=".$row['game_id']),
			'U_MOVE_UP'		=> append_sid("admin_games.php?mode=order&amp;move=-15&amp;" . POST_GAMES_URL . "=".$row['game_id']),
			'U_MOVE_DOWN'	=> append_sid("admin_games.php?mode=order&amp;move=15&amp;" . POST_GAMES_URL . "=".$row['game_id'])
		));
	}

	if (!$db->sql_numrows($result))
	{
		$template->assign_block_vars('no_games', array());
		$template->assign_vars(array('NO_GAMES' => $lang['game_empty']));
	}
	
	$template->pparse("body");
			
	include('./page_footer_admin.php');
}
?>
