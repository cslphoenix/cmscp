<?php

/***

	
	admin_navigation.php
	
	Erstellt von Phoenix
	
	
***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($userdata['auth_navi'] || $userdata['user_level'] == ADMIN)
	{
		$module['main']['navi_over'] = $filename;
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
	if ( isset($HTTP_POST_VARS[POST_NAVIGATION_URL]) || isset($HTTP_GET_VARS[POST_NAVIGATION_URL]) )
	{
		$navi_id = ( isset($HTTP_POST_VARS[POST_NAVIGATION_URL]) ) ? intval($HTTP_POST_VARS[POST_NAVIGATION_URL]) : intval($HTTP_GET_VARS[POST_NAVIGATION_URL]);
	}
	else
	{
		$navi_id = 0;
	}
	
	//	mode Abfrage
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		if (isset($HTTP_POST_VARS['add']))
		{
			$mode = 'add';
		}
		else
		{
			$mode = '';
		}
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
					$sql = 'SELECT * FROM ' . NAVIGATION_TABLE . ' WHERE navi_id = ' . $navi_id;
					$result = $db->sql_query($sql);
			
					if ( !($rank = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['navi_not_exist']);
					}
			
					$new_mode = 'editrank';
				}
				else if ( $mode == 'add' )
				{
					//	Start Werte setzen
					$rank = array (
						'navi_name'	=> trim($HTTP_POST_VARS['navi_name']),
						'navi_type'		=> '1',
						'navi_min'		=> '0',
						'navi_special'	=> '1',
						'navi_image'	=> '',
						'navi_order'	=> ''
					);

					$new_mode = 'addrank';
				}
				
				//	Template definieren
				$template->set_filenames(array('body' => './../admin/style/navi_edit_body.tpl'));
				
				//	Rangbilder auslesen, ganz einfach, mit Dropdown erstelluung
				$folder = $root_path . $settings['navi_path'];
				$files = scandir($folder);
				
				$filename_list = '';
				$filename_list .= '<select name="navi_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
				$filename_list .= '<option value="">----------</option>';
				
				foreach ($files as $file)
				{
					if ($file != '.' && $file != '..' && $file != 'index.htm')
					{
						$selected = ( $file == $rank['navi_image'] ) ? ' selected="selected"' : '';
						$filename_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$filename_list .= '</select>';
				
				//	Unsichtbare Felder für andere Infos
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_NAVIGATION_URL . '" value="' . $navi_id . '" />';

				//	Variablen zur Ausgabe
				$template->assign_vars(array(
					'L_NAVI_HEAD'			=> $lang['navi_head'],
					'L_NAVI_NEW_EDIT'		=> ($mode == 'add') ? $lang['navi_add'] : $lang['navi_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_NAVI_NAME'			=> $lang['navi_name'],
					'L_NAVI_IMAGE'			=> $lang['navi_image'],
					'L_NAVI_TYPE'			=> $lang['navi_type'],
					'L_NAVI_SPECIAL'		=> $lang['navi_special'],
					'L_NAVI_MIN'			=> $lang['navi_min'],
					
					'L_TYPE_PAGE'			=> $lang['navi_page'],
					'L_TYPE_FORUM'			=> $lang['navi_forum'],
					'L_TYPE_TEAM'			=> $lang['navi_team'],
					
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					
					
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'NAVI_TITLE'			=> $rank['navi_name'],
					'NAVI_IMAGE'			=> ($mode == 'add') ? $root_path . 'images/spacer.gif' : $root_path . $settings['navi_path'] . '/' . $rank['navi_image'],
					'NAVI_MIN'				=> $rank['navi_min'],
					
					'NAVIGATION_PATH'			=> $root_path . $settings['navi_path'],
					
					'CHECKED_TYPE_PAGE'		=> ($rank['navi_type'] == '1') ? ' checked="checked"' : '',
					'CHECKED_TYPE_FORUM'	=> ($rank['navi_type'] == '2') ? ' checked="checked"' : '',
					'CHECKED_TYPE_TEAM'		=> ($rank['navi_type'] == '3') ? ' checked="checked"' : '',
					
					'CHECKED_SPECIAL_YES'	=> ( $rank['navi_special']) ? ' checked="checked"' : '',
					'CHECKED_SPECIAL_NO'	=> (!$rank['navi_special']) ? ' checked="checked"' : '',
					
					
				
					'S_FILENAME_LIST'		=> $filename_list,
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_TEAM_ACTION'			=> append_sid("admin_navigation.php")
				));
			
				// Template ausgabe
				$template->pparse('body');
				
			break;
			
			case 'addrank':
				
				$navi_name		= (isset($HTTP_POST_VARS['navi_name']))	? trim($HTTP_POST_VARS['navi_name']) : '';
				$navi_image		= (isset($HTTP_POST_VARS['navi_image']))	? trim($HTTP_POST_VARS['navi_image']) : '';
				$navi_min		= (isset($HTTP_POST_VARS['navi_min']))		? intval($HTTP_POST_VARS['navi_min']) : -1;
				$navi_special	= ($HTTP_POST_VARS['navi_special'] == 1)	? 1 : 0;
				
				if( $navi_name == '' )
				{
					message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
				}
	
				$sql = 'SELECT MAX(navi_order) AS max_order FROM ' . NAVIGATION_TABLE . ' WHERE navi_type = ' . intval($HTTP_POST_VARS['navi_type']);
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				// There is no problem having duplicate forum names so we won't check for it.
				$sql = 'INSERT INTO ' . NAVIGATION_TABLE . " (navi_name, navi_type, navi_min, navi_special, navi_image, navi_order)
					VALUES ('" . str_replace("\'", "''", $navi_name) . "', '" . intval($HTTP_POST_VARS['navi_type']) . "', $navi_min, $navi_special, '" . str_replace("\'", "''", $navi_image) . "', $next_order)";
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'ACP_NAVI_ADD', $navi_name);
	
				$message = $lang['team_create'] . '<br /><br />' . sprintf($lang['click_return_team'], "<a href=\"" . append_sid("admin_navigation.php") . '">', '</a>') . '<br /><br />' . sprintf($lang['click_admin_index'], "<a href=\"" . append_sid("index.php?pane=right") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editrank':
			
				$navi_name		= (isset($HTTP_POST_VARS['navi_name']))	? trim($HTTP_POST_VARS['navi_name']) : '';
				$navi_image		= (isset($HTTP_POST_VARS['navi_image']))	? trim($HTTP_POST_VARS['navi_image']) : '';
				$navi_min		= (isset($HTTP_POST_VARS['navi_min']))		? intval($HTTP_POST_VARS['navi_min']) : -1;
				$navi_special	= ($HTTP_POST_VARS['navi_special'] == 1)	? 1 : 0;
				
				if( $navi_name == '' )
				{
					message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
				}

				$sql = "UPDATE " . NAVIGATION_TABLE . " SET
							navi_name		= '" . str_replace("\'", "''", $navi_name) . "',
							navi_type		= '" . intval($HTTP_POST_VARS['navi_type']) . "',
							navi_min		= $navi_min,
							navi_special	= $navi_special,
							navi_image		= '" . str_replace("\'", "''", $navi_image) . "'
						WHERE navi_id = " . intval($HTTP_POST_VARS[POST_NAVIGATION_URL]);
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'ACP_TEAM_EDIT', $log_data);
				
				$message = $lang['team_update'] . '<br /><br />' . sprintf($lang['click_admin_index'], "<a href=\"" . append_sid("index.php?pane=right") . '">', '</a>')
					. '<br /><br />' . sprintf($lang['click_return_rank'], "<a href=\"" . append_sid("admin_navigation.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $navi_id && $confirm )
				{	
					$sql = 'SELECT * FROM ' . NAVIGATION_TABLE . " WHERE navi_id = $navi_id";
					$result = $db->sql_query($sql);
			
					if ( !($team_info = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['navi_not_exist']);
					}
				
					$sql = 'DELETE FROM ' . NAVIGATION_TABLE . " WHERE navi_id = $navi_id";
					$result = $db->sql_query($sql);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'ACP_NAVI_DELETE', $team_info['navi_name']);
					
					$message = $lang['team_delete'] . '<br /><br />' . sprintf($lang['click_admin_index'], "<a href=\"" . append_sid("index.php?pane=right") . '">', '</a>')
						. '<br /><br />' . sprintf($lang['click_return_rank'], "<a href=\"" . append_sid("admin_navigation.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $navi_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_NAVIGATION_URL . '" value="' . $navi_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_rank'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_navigation.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['must_select_rank']);
				}
				
				$template->pparse("body");
				
				break;
			
			case 'order_page':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NAVIGATION_TABLE . " SET navi_order = navi_order + $move WHERE navi_id = $navi_id";
				$result = $db->sql_query($sql);
		
				renumber_order('navi', NAVI_PAGE);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'ACP_NAVI_ORDER', 'page');
				
				$show_index = TRUE;
	
				break;
			
			case 'order_forum':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NAVIGATION_TABLE . " SET navi_order = navi_order + $move WHERE navi_id = $navi_id";
				$result = $db->sql_query($sql);
		
				renumber_order('navi', NAVI_FORUM);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'ACP_NAVI_ORDER', 'forum');
				
				$show_index = TRUE;
	
				break;
			
			case 'order_team':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NAVIGATION_TABLE . " SET navi_order = navi_order + $move WHERE navi_id = $navi_id";
				$result = $db->sql_query($sql);
		
				renumber_order('navi', NAVI_TEAM);
					
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'ACP_NAVI_ORDER', 'team');
				
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
	
	$template->set_filenames(array('body' => './../admin/style/navigation_body.tpl'));
			
	$template->assign_vars(array(
//		'L_NAVI_TITLE'			=> $lang['navi_head'],
//		'L_NAVI_EXPLAIN'		=> $lang['navi_explain'],
//		
//		'L_NAVI_PAGE'			=> $lang['navi_page'],
//		'L_NAVI_FORUM'			=> $lang['navi_forum'],
//		'L_NAVI_TEAM'			=> $lang['navi_team'],
//		'L_NAVI_SPECIAL'		=> $lang['navi_special'],
//		'L_NAVI_MIN'			=> $lang['navi_min'],
//		
//		'L_NAVI_ADD'			=> $lang['navi_add'],
		
		'L_SETTING'				=> $lang['setting'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'L_MOVE_UP'				=> $lang['Move_up'], 
		'L_MOVE_DOWN'			=> $lang['Move_down'], 
		
		'ICON_MOVE_UP'			=> '<img src="./../admin/style/images/icon_arrow_up.png" alt="Up" title="" width="12" height="12" />',
		'ICON_MOVE_DOWN'		=> '<img src="./../admin/style/images/icon_arrow_down.png" alt="Down" title="" width="12" height="12" />',
		
		'S_TEAM_ACTION'		=> append_sid("admin_navigation.php")
	));
	
	$sql = 'SELECT * FROM ' . NAVIGATION_TABLE . ' ORDER BY navi_type ASC, navi_order ASC';
	$result = $db->sql_query($sql);
	
	$color = '';
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$class = ($color % 2) ? 'row_class1' : 'row_class2';
		$color++;
		
		if ($row['navi_type'] == NAVI_MAIN)
		{
			$template->assign_block_vars('main_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> $row['navi_name'],

				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_UP'		=> append_sid("admin_navigation.php?mode=order_page&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_DOWN'	=> append_sid("admin_navigation.php?mode=order_page&amp;move=15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id'])
			));
		}
		else if ($row['navi_type'] == NAVI_CLAN)
		{
			$template->assign_block_vars('clan_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> $row['navi_name'],

				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_UP'		=> append_sid("admin_navigation.php?mode=order_forum&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_DOWN'	=> append_sid("admin_navigation.php?mode=order_forum&amp;move=15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id'])
			));
		}
		else if ($row['navi_type'] == NAVI_COM)
		{
			$template->assign_block_vars('com_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> $row['navi_name'],

				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_UP'		=> append_sid("admin_navigation.php?mode=order_team&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_DOWN'	=> append_sid("admin_navigation.php?mode=order_team&amp;move=15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id'])
			));
		}
		else if ($row['navi_type'] == NAVI_MISC)
		{
			$template->assign_block_vars('misc_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> $row['navi_name'],

				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_UP'		=> append_sid("admin_navigation.php?mode=order_team&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_DOWN'	=> append_sid("admin_navigation.php?mode=order_team&amp;move=15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id'])
			));
		}
		else if ($row['navi_type'] == NAVI_USER)
		{
			$template->assign_block_vars('user_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> $row['navi_name'],

				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_UP'		=> append_sid("admin_navigation.php?mode=order_team&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id']),
				'U_MOVE_DOWN'	=> append_sid("admin_navigation.php?mode=order_team&amp;move=15&amp;" . POST_NAVIGATION_URL . "=".$row['navi_id'])
			));
		}
		
	}
	
	if (!$db->sql_numrows($result))
	{
		$template->assign_block_vars('no_navi', array());
		$template->assign_vars(array('NO_NAVIS' => $lang['navi_empty']));
	}
	
	$template->pparse("body");
			
	include('./page_footer_admin.php');
}
?>
