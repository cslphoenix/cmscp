<?php

/***

	
	admin_ranks.php
	
	Erstellt von Phoenix
	
	
***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($auth['auth_ranks'] || $userdata['user_level'] == ADMIN)
	{
		$module['main']['ranks_over'] = $filename;
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
	
	if (!$auth['auth_games'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_match.php", true));
	}
	
	if ( isset($HTTP_POST_VARS[POST_RANKS_URL]) || isset($HTTP_GET_VARS[POST_RANKS_URL]) )
	{
		$rank_id = ( isset($HTTP_POST_VARS[POST_RANKS_URL]) ) ? intval($HTTP_POST_VARS[POST_RANKS_URL]) : intval($HTTP_GET_VARS[POST_RANKS_URL]);
	}
	else
	{
		$rank_id = 0;
	}
	
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
					$rank		= get_data('ranks', $rank_id, 0);
					$new_mode	= 'editrank';
				}
				else if ( $mode == 'add' )
				{
					//	Start Werte setzen
					$rank = array (
						'rank_title'	=> trim($HTTP_POST_VARS['rank_title']),
						'rank_type'		=> '1',
						'rank_min'		=> '0',
						'rank_special'	=> '1',
						'rank_image'	=> '',
						'rank_order'	=> ''
					);

					$new_mode = 'addrank';
				}
				
				$template->set_filenames(array('body' => './../admin/style/acp_ranks.tpl'));
				$template->assign_block_vars('ranks_edit', array());
				
				$folder = $root_path . $settings['path_ranks'];
				$files = scandir($folder);
				
				$filename_list = '';
				$filename_list .= '<select name="rank_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
				$filename_list .= '<option value="">----------</option>';
				
				foreach ($files as $file)
				{
					if ($file != '.' && $file != '..' && $file != 'index.htm')
					{
						$selected = ( $file == $rank['rank_image'] ) ? ' selected="selected"' : '';
						$filename_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$filename_list .= '</select>';
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_RANKS_URL . '" value="' . $rank_id . '" />';

				$template->assign_vars(array(
					'L_RANK_HEAD'			=> $lang['rank_head'],
					'L_RANK_NEW_EDIT'		=> ($mode == 'add') ? $lang['rank_add'] : $lang['rank_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_RANK_NAME'			=> $lang['rank_title'],
					'L_RANK_IMAGE'			=> $lang['rank_image'],
					'L_RANK_TYPE'			=> $lang['rank_type'],
					'L_RANK_SPECIAL'		=> $lang['rank_special'],
					'L_RANK_MIN'			=> $lang['rank_min'],
					'L_TYPE_PAGE'			=> $lang['rank_page'],
					'L_TYPE_FORUM'			=> $lang['rank_forum'],
					'L_TYPE_TEAM'			=> $lang['rank_team'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'RANK_TITLE'			=> $rank['rank_title'],
					'RANK_IMAGE'			=> ($mode == 'add') ? $root_path . 'images/spacer.gif' : $root_path . $settings['path_ranks'] . '/' . $rank['rank_image'],
					'RANK_MIN'				=> $rank['rank_min'],
					'RANKS_PATH'			=> $root_path . $settings['path_ranks'],
					'CHECKED_TYPE_PAGE'		=> ($rank['rank_type'] == '1') ? ' checked="checked"' : '',
					'CHECKED_TYPE_FORUM'	=> ($rank['rank_type'] == '2') ? ' checked="checked"' : '',
					'CHECKED_TYPE_TEAM'		=> ($rank['rank_type'] == '3') ? ' checked="checked"' : '',
					'CHECKED_SPECIAL_YES'	=> ( $rank['rank_special']) ? ' checked="checked"' : '',
					'CHECKED_SPECIAL_NO'	=> (!$rank['rank_special']) ? ' checked="checked"' : '',
					
					'S_FILENAME_LIST'		=> $filename_list,
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_RANKS_ACTION'		=> append_sid("admin_ranks.php")
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addrank':
				
				$rank_title		= (isset($HTTP_POST_VARS['rank_title']))	? trim($HTTP_POST_VARS['rank_title']) : '';
				$rank_image		= (isset($HTTP_POST_VARS['rank_image']))	? trim($HTTP_POST_VARS['rank_image']) : '';
				$rank_min		= (isset($HTTP_POST_VARS['rank_min']))		? intval($HTTP_POST_VARS['rank_min']) : -1;
				$rank_special	= ($HTTP_POST_VARS['rank_special'] == 1)	? 1 : 0;
				
				if ( $rank_title == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_title'] . $lang['wrong_back'], '');
				}
	
				$sql = 'SELECT MAX(rank_order) AS max_order
							FROM ' . RANKS_TABLE . '
							WHERE rank_type = ' . intval($HTTP_POST_VARS['rank_type']);
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				$sql = 'INSERT INTO ' . RANKS_TABLE . " (rank_title, rank_type, rank_min, rank_special, rank_image, rank_order)
					VALUES ('" . str_replace("\'", "''", $rank_title) . "', '" . intval($HTTP_POST_VARS['rank_type']) . "', $rank_min, $rank_special, '" . str_replace("\'", "''", $rank_image) . "', $next_order)";
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'acp_rank_add', $rank_title);
	
				$message = $lang['create_rank'] . '<br /><br />' . sprintf($lang['click_return_rank'], '<a href="' . append_sid("admin_ranks.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editrank':
			
				$rank_title		= (isset($HTTP_POST_VARS['rank_title']))	? trim($HTTP_POST_VARS['rank_title']) : '';
				$rank_image		= (isset($HTTP_POST_VARS['rank_image']))	? trim($HTTP_POST_VARS['rank_image']) : '';
				$rank_min		= (isset($HTTP_POST_VARS['rank_min']))		? intval($HTTP_POST_VARS['rank_min']) : -1;
				$rank_special	= ($HTTP_POST_VARS['rank_special'] == 1)	? 1 : 0;
				
				if ( $rank_title == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_title'] . $lang['wrong_back'], '');
				}
					
				$sql = "UPDATE " . RANKS_TABLE . " SET
							rank_title		= '" . str_replace("\'", "''", $rank_title) . "',
							rank_type		= '" . intval($HTTP_POST_VARS['rank_type']) . "',
							rank_min		= $rank_min,
							rank_special	= $rank_special,
							rank_image		= '" . str_replace("\'", "''", $rank_image) . "'
						WHERE rank_id = " . intval($HTTP_POST_VARS[POST_RANKS_URL]);
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'acp_rank_edit');
				
				$message = $lang['update_rank'] . '<br /><br />' . sprintf($lang['click_return_rank'], '<a href="' . append_sid("admin_ranks.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $rank_id && $confirm )
				{	
					$rank = get_data('rank', $rank_id, 0);
				
					$sql = 'DELETE FROM ' . RANKS_TABLE . " WHERE rank_id = $rank_id";
					$result = $db->sql_query($sql);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'acp_rank_delete', $rank['rank_title']);
					
					$message = $lang['delete_rank'] . '<br /><br />' . sprintf($lang['click_return_rank'], '<a href="' . append_sid("admin_ranks.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $rank_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_RANKS_URL . '" value="' . $rank_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_rank'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_ranks.php"),
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
				
				$sql = 'UPDATE ' . RANKS_TABLE . " SET rank_order = rank_order + $move WHERE rank_id = $rank_id";
				$result = $db->sql_query($sql);
		
				renumber_order('ranks', RANK_PAGE);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'acp_rank_order', RANK_PAGE);
				
				$show_index = TRUE;
	
				break;
			
			case 'order_forum':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . RANKS_TABLE . " SET rank_order = rank_order + $move WHERE rank_id = $rank_id";
				$result = $db->sql_query($sql);
		
				renumber_order('ranks', RANK_FORUM);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'acp_rank_order', RANK_FORUM);
				
				$show_index = TRUE;
	
				break;
			
			case 'order_team':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . RANKS_TABLE . " SET rank_order = rank_order + $move WHERE rank_id = $rank_id";
				$result = $db->sql_query($sql);
		
				renumber_order('ranks', RANK_TEAM);
					
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'acp_rank_order', RANK_TEAM);
				
				$show_index = TRUE;
	
				break;
	
			default:
				message_die(GENERAL_ERROR, $lang['no_select_module'], '');
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_ranks.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_RANK_TITLE'			=> $lang['rank_head'],
		'L_RANK_EXPLAIN'		=> $lang['rank_explain'],
		
		'L_RANK_PAGE'			=> $lang['rank_page'],
		'L_RANK_FORUM'			=> $lang['rank_forum'],
		'L_RANK_TEAM'			=> $lang['rank_team'],
		'L_RANK_SPECIAL'		=> $lang['rank_special'],
		'L_RANK_MIN'			=> $lang['rank_min'],
		'L_RANK_ADD'			=> $lang['rank_add'],
		
		'L_EDIT'				=> $lang['edit'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'L_MOVE_UP'				=> $lang['Move_up'], 
		'L_MOVE_DOWN'			=> $lang['Move_down'], 
		
		'S_RANKS_ACTION'		=> append_sid("admin_ranks.php")
	));
	
	$sql = 'SELECT MAX(rank_order) AS max FROM ' . RANKS_TABLE . ' WHERE rank_type = ' . RANK_PAGE;
	$result = $db->sql_query($sql);
	$max_page = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT MAX(rank_order) AS max FROM ' . RANKS_TABLE . ' WHERE rank_type = ' . RANK_FORUM;
	$result = $db->sql_query($sql);
	$max_forum = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT MAX(rank_order) AS max FROM ' . RANKS_TABLE . ' WHERE rank_type = ' . RANK_TEAM;
	$result = $db->sql_query($sql);
	$max_team = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT * FROM ' . RANKS_TABLE . ' ORDER BY rank_type ASC, rank_special DESC, rank_order ASC';
	$result = $db->sql_query($sql);
	
	$color = '';
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$class = ($color % 2) ? 'row_class1' : 'row_class2';
		$color++;
		
		$rank_id = $row['rank_id'];
		
		if ($row['rank_type'] == RANK_PAGE)
		{
			$icon_up	= ( $row['rank_order'] != '10' ) ? '<img src="' . $images['icon_acp_arrow_u'] . '" alt="" />' : '';
			$icon_down	= ( $row['rank_order'] != $max_page['max'] ) ? '<img src="' . $images['icon_acp_arrow_d'] . '" alt="" />' : '';
			
			$template->assign_block_vars('display.page_row', array(
				'CLASS' 		=> $class,
				'RANK_TITLE'	=> $row['rank_title'],
				
				'ICON_UP'		=> $icon_up,
				'ICON_DOWN'		=> $icon_down,

				'U_MEMBER'		=> append_sid("admin_ranks.php?mode=member&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_DELETE'		=> append_sid("admin_ranks.php?mode=delete&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_EDIT'		=> append_sid("admin_ranks.php?mode=edit&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_MOVE_UP'		=> append_sid("admin_ranks.php?mode=order_page&amp;move=-15&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_MOVE_DOWN'	=> append_sid("admin_ranks.php?mode=order_page&amp;move=15&amp;" . POST_RANKS_URL . "=" . $rank_id),
			));
		}
		else if ($row['rank_type'] == RANK_FORUM)
		{
			$icon_up	= ( $row['rank_order'] != '10' ) ? '<img src="' . $images['icon_acp_arrow_u'] . '" alt="" />' : '';
			$icon_down	= ( $row['rank_order'] != $max_forum['max'] ) ? '<img src="' . $images['icon_acp_arrow_d'] . '" alt="" />' : '';
			
			$template->assign_block_vars('display.forum_row', array(
				'CLASS' 		=> $class,
				'RANK_TITLE'	=> $row['rank_title'],
				'RANK_MIN'		=> ($row['rank_special'] == '0') ? $row['rank_min'] : ' - ',
				'RANK_SPECIAL'	=> ($row['rank_special'] == '1') ? $lang['Yes'] : $lang['No'],
				
				'ICON_UP'		=> $icon_up,
				'ICON_DOWN'		=> $icon_down,
				
				'U_MEMBER'		=> append_sid("admin_ranks.php?mode=member&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_DELETE'		=> append_sid("admin_ranks.php?mode=delete&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_EDIT'		=> append_sid("admin_ranks.php?mode=edit&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_MOVE_UP'		=> append_sid("admin_ranks.php?mode=order_forum&amp;move=-15&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_MOVE_DOWN'	=> append_sid("admin_ranks.php?mode=order_forum&amp;move=15&amp;" . POST_RANKS_URL . "=" . $rank_id),
			));
		}
		else if ($row['rank_type'] == RANK_TEAM)
		{
			$icon_up	= ( $row['rank_order'] != '10' ) ? '<img src="' . $images['icon_acp_arrow_u'] . '" alt="" />' : '';
			$icon_down	= ( $row['rank_order'] != $max_team['max'] ) ? '<img src="' . $images['icon_acp_arrow_d'] . '" alt="" />' : '';
			
			$template->assign_block_vars('display.team_row', array(
				'CLASS' 		=> $class,
				'RANK_TITLE'	=> $row['rank_title'],
				
				'ICON_UP'		=> $icon_up,
				'ICON_DOWN'		=> $icon_down,

				'U_MEMBER'		=> append_sid("admin_ranks.php?mode=member&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_DELETE'		=> append_sid("admin_ranks.php?mode=delete&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_EDIT'		=> append_sid("admin_ranks.php?mode=edit&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_MOVE_UP'		=> append_sid("admin_ranks.php?mode=order_team&amp;move=-15&amp;" . POST_RANKS_URL . "=" . $rank_id),
				'U_MOVE_DOWN'	=> append_sid("admin_ranks.php?mode=order_team&amp;move=15&amp;" . POST_RANKS_URL . "=" . $rank_id),
			));
		}
		
	}
	
	if ($db->sql_numrows($result) == 0)
	{
		$template->assign_block_vars('no_ranks', array());
		$template->assign_vars(array('NO_RANKS' => $lang['rank_empty']));
	}
	
	$template->pparse("body");
			
	include('./page_footer_admin.php');
}
?>