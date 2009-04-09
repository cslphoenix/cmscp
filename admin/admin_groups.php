<?php

/***

	
	admin_groups.php
	
	Erstellt von Phoenix
	
	
***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($auth['auth_groups'] || $userdata['user_level'] == ADMIN)
	{
		$module['main']['groups_over'] = $filename;
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
	
	if (!$auth['auth_groups'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_groups.php", true));
	}
	
	if ( isset($HTTP_POST_VARS[POST_GROUPS_URL]) || isset($HTTP_GET_VARS[POST_GROUPS_URL]) )
	{
		$group_id = ( isset($HTTP_POST_VARS[POST_GROUPS_URL]) ) ? intval($HTTP_POST_VARS[POST_GROUPS_URL]) : intval($HTTP_GET_VARS[POST_GROUPS_URL]);
	}
	else
	{
		$group_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		$mode = '';
	}
	
	$group_auth_fields = array(
		'auth_contact',
		'auth_fightus',
		'auth_forum',
		'auth_forum_auth',
		'auth_games',
		'auth_groups',
		'auth_joinus',
		'auth_match',
		'auth_navi',
		'auth_news',
		'auth_news_public',
		'auth_newscat',
		'auth_ranks',
		'auth_server',
		'auth_teams',
		'auth_teamspeak',
		'auth_training',
	);
	
	$group_auth_levels	= array('DISALLOWED', 'ALLOWED');
	$group_auth_const	= array(AUTH_DISALLOWED, AUTH_ALLOWED);
	
	$field_names = array(
		'auth_contact'		=> $lang['auth_contact'],
		'auth_fightus'		=> $lang['auth_fightus'],
		'auth_forum'		=> $lang['auth_forum'],
		'auth_forum_auth'	=> $lang['auth_forum_auth'],
		'auth_games'		=> $lang['auth_games'],
		'auth_groups'		=> $lang['auth_groups'],
		'auth_joinus'		=> $lang['auth_joinus'],
		'auth_match'		=> $lang['auth_match'],
		'auth_navi'			=> $lang['auth_navi'],
		'auth_news'			=> $lang['auth_news'],
		'auth_news_public'	=> $lang['auth_news_public'],
		'auth_newscat'		=> $lang['auth_newscat'],
		'auth_ranks'		=> $lang['auth_ranks'],
		'auth_server'		=> $lang['auth_server'],
		'auth_teams'		=> $lang['auth_teams'],
		'auth_teamspeak'	=> $lang['auth_teamspeak'],
		'auth_training'		=> $lang['auth_training'],
	);
	
	$show_index = '';
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
				
				if ( $mode == 'edit' )
				{
					$group		= get_data('groups', $group_id, 1);
					$new_mode	= 'editgroup';
				}
				else if ( $mode == 'add' )
				{
					$group = array (
						'group_name'		=> trim($HTTP_POST_VARS['group_name']),
						'group_type'		=> '1',
						'group_access'		=> '1',
						'group_description'	=> '',
						'group_color'		=> '',
						'group_avatar'		=> '',
						'group_avatar_type'	=> '',
						'group_rank'		=> '',
						'group_legend'		=> '',
						
						'auth_contact'		=> '0',
						'auth_fightus'		=> '0',
						'auth_forum'		=> '0',
						'auth_forum_auth'	=> '0',
						'auth_games'		=> '0',
						'auth_groups'		=> '0',
						'auth_joinus'		=> '0',
						'auth_match'		=> '0',
						'auth_navi'			=> '0',
						'auth_news'			=> '0',
						'auth_news_public'	=> '0',
						'auth_newscat'		=> '0',
						'auth_ranks'		=> '0',
						'auth_server'		=> '0',
						'auth_teams'		=> '0',
						'auth_teamspeak'	=> '0',
						'auth_training'		=> '0',
					);

					$new_mode = 'addgroup';
				}
				
				$template->set_filenames(array('body' => './../admin/style/acp_groups.tpl'));
				$template->assign_block_vars('groups_edit', array());
				
				$s_group_access = '<select name="group_access" class="post">';
				foreach ($lang['group_access_opt'] as $const => $name)
				{
					$selected = ( $const == $group['group_access'] ) ? ' selected="selected"' : '';
					$s_group_access .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
				}
				$s_group_access .= '</select>';
				
				$s_group_type = '<select name="group_type" class="post">';
				foreach ($lang['group_type_opt'] as $const => $name)
				{
					$selected = ( $const == $group['group_type'] ) ? ' selected="selected"' : '';
					$s_group_type .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
				}
				$s_group_type .= '</select>';
				
				for($j = 0; $j < count($group_auth_fields); $j++)
				{
					$custom_auth[$j] = '<select class="post" name="' . $group_auth_fields[$j] . '">';
			
					for($k = 0; $k < count($group_auth_levels); $k++)
					{
						$selected = ( $group[$group_auth_fields[$j]] == $group_auth_const[$k] ) ? ' selected="selected"' : '';
						$custom_auth[$j] .= '<option value="' . $group_auth_const[$k] . '"' . $selected . '>' . $lang['Forum_' . $group_auth_levels[$k]] . '</option>';
					}
					
					$custom_auth[$j] .= '</select>';
					
					$selected_yes = ( $group[$group_auth_fields[$j]] == $group_auth_const[1] ) ? ' checked="checked"' : '';
					$selected_no = ( $group[$group_auth_fields[$j]] == $group_auth_const[0] ) ? ' checked="checked"' : '';
					$custom_auth2[$j] = '<input type="radio" name="' . $group_auth_fields[$j] . '"';
					$custom_auth2[$j] .= 'value="1" ' . $selected_yes . '> ' . $lang['Forum_' . $group_auth_levels[1]];
					$custom_auth2[$j] .= '&nbsp;&nbsp;<input type="radio" name="' . $group_auth_fields[$j] . '"';
					$custom_auth2[$j] .= 'value="0" ' . $selected_no . '> ' . $lang['Forum_' . $group_auth_levels[0]];
				
					$cell_title = $field_names[$group_auth_fields[$j]];
			
					$template->assign_block_vars('groups_edit.group_auth_data', array(
						'CELL_TITLE'			=> $cell_title,
						'S_AUTH_LEVELS_SELECT'	=> $custom_auth[$j],
						'S_AUTH_LEVELS_SELECT2'	=> $custom_auth2[$j],
					));
				}
	
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';

				$template->assign_vars(array(
					'L_GROUP_HEAD'			=> $lang['group_head'],
					'L_GROUP_NEW_EDIT'		=> ($mode == 'add') ? $lang['group_add'] : $lang['group_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_GROUP_NAME'			=> $lang['group_name'],
					'L_GROUP_ACCESS'		=> $lang['group_access'],
					'L_GROUP_TYPE'			=> $lang['group_type'],
							
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'GROUP_NAME'			=> $group['group_name'],
					'GROUP_DESCRIPTION'		=> $group['group_description'],
					'GROUP_COLOR'			=> $group['group_color'],
					'GROUP_LEGEND'			=> $group['group_legend'],
					
					'S_GROUP_ACCESS'		=> $s_group_access,
					'S_GROUP_TYPE'			=> $s_group_type,
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_GROUP_ACTION'		=> append_sid("admin_groups.php")
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addgroup':
			
				$sql = '';
				
				$group_name		= (isset($HTTP_POST_VARS['group_name']))		? trim($HTTP_POST_VARS['group_name']) : '';
				
				if ( $group_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['wrong_back'], '');
				}
				
				$sql_field = '';
				$sql_value = '';
				
				for($i = 0; $i < count($group_auth_fields); $i++)
				{
					$value = $HTTP_POST_VARS[$group_auth_fields[$i]];
					$field = $group_auth_fields[$i]; 
					
					$sql_field .= ( ( $sql_field != '' ) ? ', ' : '' ) . $field;
					$sql_value .= ( ( $sql_value != '' ) ? ', ' : '' ) . $value;
				}
				
//				_debug_post($sql_field);
//				_debug_poste($sql_value);
	
				$sql = 'SELECT MAX(group_order) AS max_order FROM ' . GROUPS_TABLE;
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				$sql = 'INSERT INTO ' . GROUPS_TABLE . " (group_name, $sql_field, group_order)
					VALUES ('" . str_replace("\'", "''", $group_name) . "', $sql_value, $next_order)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
				}
				
				$group_id = $db->sql_nextid();
				
				$sql = 'INSERT INTO ' . GROUPS_AUTH_TABLE . " (group_id, $sql_field)
					VALUES ($group_id, $sql_value)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'acp_group_add', $group_name);
	
				$message = $lang['create_group'] . '<br /><br />' . sprintf($lang['click_return_group'], '<a href="' . append_sid("admin_groups.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editgroup':

				$group_name		= (isset($HTTP_POST_VARS['group_name']))	? trim($HTTP_POST_VARS['group_name']) : '';
				
				
				if ( $group_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['wrong_back'], '');
				}
				
				$sql = 'SELECT user_id
							FROM ' . USERS_AUTH_TABLE . '
							WHERE group_id = ' . $group_id;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
				}
				$users = $db->sql_fetchrowset($result);
				
				$user_in = '';
				for ( $i = 0; $i < count($users); $i++ )
				{
					$user_in .= ( ( $user_in != '' ) ? ', ' : '' ) . $users[$i]['user_id'];
				}
				
				$sql_in = '';
				for ( $i = 0; $i < count($group_auth_fields); $i++ )
				{
					$value = intval($HTTP_POST_VARS[$group_auth_fields[$i]]);
	
					$sql_in .= ( ( $sql_in != '' ) ? ', ' : '' ) . $group_auth_fields[$i] . ' = ' . $value;
				}
				
				$sql = "UPDATE " . GROUPS_TABLE . " SET
							group_name		= '" . str_replace("\'", "''", $group_name) . "'
						WHERE group_id = " . intval($HTTP_POST_VARS[POST_GROUPS_URL]);
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
				}
				
				$sql = "UPDATE " . GROUPS_AUTH_TABLE . " SET $sql_in WHERE group_id = $group_id";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
				}
			
				$sql = "UPDATE " . USERS_AUTH_TABLE . " SET $sql_in WHERE group_id = $group_id AND user_id IN ($user_in)";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'acp_group_edit', $group_name);
				
				$message = $lang['update_group'] . '<br /><br />' . sprintf($lang['click_return_group'], '<a href="' . append_sid("admin_groups.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $group_id && $confirm )
				{	
					$group = get_data('groups', $group_id, 0);
				
					$sql = 'DELETE FROM ' . GROUPS_TABLE . " WHERE group_id = $group_id";
					$result = $db->sql_query($sql);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'acp_group_delete', $group['group_name']);
					
					$message = $lang['delete_group'] . '<br /><br />' . sprintf($lang['click_return_group'], '<a href="' . append_sid("admin_groups.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $group_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_group'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_groups.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['must_select_groups']);
				}
				
				$template->pparse("body");
				
				break;
			
			case 'order':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . GROUPS_TABLE . " SET group_order = group_order + $move WHERE group_id = $group_id";
				$result = $db->sql_query($sql);
		
				renumber_order('groups', -1);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'ACP_GAME_ORDER');
				
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
	
	$template->set_filenames(array('body' => './../admin/style/acp_groups.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_GROUP_TITLE'			=> $lang['group_head'],
		'L_GROUP_EXPLAIN'		=> $lang['group_explain'],
		
		'L_GROUP_ADD'			=> $lang['group_add'],
		
		'L_EDIT'				=> $lang['edit'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'L_MOVE_UP'				=> $lang['Move_up'], 
		'L_MOVE_DOWN'			=> $lang['Move_down'], 
		
		'S_GROUP_ACTION'		=> append_sid("admin_groups.php")
	));
	
	$sql = 'SELECT MAX(group_order) AS max FROM ' . GROUPS_TABLE . ' WHERE group_single_user = 0';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
	}
	$max_order = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT * FROM ' . GROUPS_TABLE . ' WHERE group_single_user = 0 ORDER BY group_order';
	$result = $db->sql_query($sql);
	
	$color = '';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$class = ($color % 2) ? 'row_class1' : 'row_class2';
		$color++;
		
		$group_id	= $row['group_id'];
		
		$icon_up	= ( $row['group_order'] != '10' ) ? '<img src="' . $images['icon_acp_arrow_u'] . '" alt="" />' : '';
		$icon_down	= ( $row['group_order'] != $max_order['max'] ) ? '<img src="' . $images['icon_acp_arrow_d'] . '" alt="" />' : '';
		
		$template->assign_block_vars('display.group_row', array(
			'CLASS' 		=> $class,
			'NAME'			=> $row['group_name'],
			
			'ICON_UP'		=> $icon_up,
			'ICON_DOWN'		=> $icon_down,
			
			'U_DELETE'		=> append_sid("admin_groups.php?mode=delete&amp;" . POST_GROUPS_URL . "=" . $group_id),
			'U_EDIT'		=> append_sid("admin_groups.php?mode=edit&amp;" . POST_GROUPS_URL . "=" . $group_id),
			'U_MOVE_UP'		=> append_sid("admin_groups.php?mode=order&amp;move=-15&amp;" . POST_GROUPS_URL . "=" . $group_id),
			'U_MOVE_DOWN'	=> append_sid("admin_groups.php?mode=order&amp;move=15&amp;" . POST_GROUPS_URL . "=" . $group_id),
		));
	}

	if (!$db->sql_numrows($result))
	{
		$template->assign_block_vars('display.no_groups', array());
		$template->assign_vars(array('NO_GAMES' => $lang['group_empty']));
	}
	
	$template->pparse("body");
			
	include('./page_footer_admin.php');
}
?>
