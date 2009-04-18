<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($userauth['auth_groups'] || $userdata['user_level'] == ADMIN)
	{
		$module['groups']['set'] = $filename;
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
	include($root_path . 'includes/functions_selects.php');
	
	if (!$userauth['auth_groups'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_groups.php", true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	
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
	
	$group_auth_fields	= get_authlist();
	
	
	$group_auth_levels	= array('DISALLOWED', 'ALLOWED');
	$group_auth_const	= array(AUTH_DISALLOWED, AUTH_ALLOWED);
	
	$show_index = '';
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
				
				if ( $mode == 'edit' )
				{
					$group		= get_data('groups', $group_id, 0);
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
						'group_rank'		=> '0',
						'group_legend'		=> '0',
						
						'auth_contact'		=> '0',
						'auth_fightus'		=> '0',
						'auth_forum'		=> '0',
						'auth_forum_auth'	=> '0',
						'auth_games'		=> '0',
						'auth_groups'		=> '0',
						'auth_groups_perm'	=> '0',
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
						'auth_user'			=> '0',
						'auth_user_perm'	=> '0',
					);

					$new_mode = 'addgroup';
				}
				
				$template->set_filenames(array('body' => './../admin/style/acp_groups.tpl'));
				$template->assign_block_vars('groups_edit', array());
				
				$s_group_access = '<select name="group_access" class="post">';
				foreach ($lang['group_access_opt'] as $const => $name)
				{
					$selected = ( $const == $group['group_access'] ) ? ' selected="selected"' : '';
					$protect = ( GROUP_SYSTEM == $group['group_type'] ) ? ' disabled' : '';
					$s_group_access .= '<option value="' . $const . '"' . $selected . $protect . '>' . $name . '</option>';
				}
				$s_group_access .= '</select>';
				
				$s_group_type = '<select name="group_type" class="post">';
				foreach ($lang['group_type_opt'] as $const => $name)
				{
					$selected	= ( $group['group_type'] == $const ) ? ' selected="selected"' : '';
					$protect	= ( $group['group_type'] == GROUP_SYSTEM ) ? ' disabled' : '';
					$protect_go	= ( $const == GROUP_SYSTEM ) ? ' disabled' : '';
				//	$s_group_type .= '<option value="' . $const . '"' . $selected . $protect . '>' . $name . '</option>';
					$s_group_type .= '<option value="' . $const . '"' . $selected . $protect . $protect_go . '>' . $name . '</option>';
				}
				$s_group_type .= '</select>';
				
				for($j = 0; $j < count($group_auth_fields); $j++)
				{
					if ( $userdata['user_level'] > $group['group_access'] || $userdata['user_level'] == ADMIN )
					{
						$selected_yes = ( $group[$group_auth_fields[$j]] == $group_auth_const[1] ) ? ' checked="checked"' : '';
						$selected_no = ( $group[$group_auth_fields[$j]] == $group_auth_const[0] ) ? ' checked="checked"' : '';
					}
					else if ( $userdata['user_level'] <= $group['group_access'] )
					{
						$selected_yes = ( $group[$group_auth_fields[$j]] == $group_auth_const[1] ) ? ' disabled checked="checked"' : ' disabled';
						$selected_no = ( $group[$group_auth_fields[$j]] == $group_auth_const[0] ) ? ' disabled checked="checked"' : ' disabled';
					}
					
					$custom_auth[$j] = '<input type="radio" name="' . $group_auth_fields[$j] . '"';
					$custom_auth[$j] .= 'value="1" ' . $selected_yes . '> ' . $lang['Forum_' . $group_auth_levels[1]];
					$custom_auth[$j] .= '&nbsp;&nbsp;<input type="radio" name="' . $group_auth_fields[$j] . '"';
					$custom_auth[$j] .= 'value="0" ' . $selected_no . '> ' . $lang['Forum_' . $group_auth_levels[0]];
				
					$cell_title = $lang['auths'][$group_auth_fields[$j]];
			
					$template->assign_block_vars('groups_edit.group_auth_data', array(
						'CELL_TITLE'			=> $cell_title,
						'S_AUTH_LEVELS_SELECT'	=> $custom_auth[$j],
					));
				}
	
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';

				$template->assign_vars(array(
					'L_GROUP_HEAD'			=> $lang['group_head'],
					'L_GROUP_NEW_EDIT'		=> ($mode == 'add') ? $lang['group_add'] : $lang['group_edit'],
					'L_REQUIRED'			=> $lang['required'],
					'L_GROUP_MEMBER'		=> $lang['group_view_member'],
					'L_OVERVIEW'			=> $lang['group_overview'],
					
					'L_GROUP_NAME'			=> $lang['group_name'],
					'L_GROUP_ACCESS'		=> $lang['group_access'],
					'L_GROUP_TYPE'			=> $lang['group_type'],
					'L_GROUP_DESCRIPTION'	=> $lang['group_description'],
					'L_GROUP_LEGEND'		=> $lang['group_legend'],
					'L_GROUP_COLOR'			=> $lang['group_color'],
					'L_GROUP_RANK'			=> $lang['group_rank'],
					
					'L_GROUP_AUTH'			=> $lang['group_auth'],
					'L_GROUP_DATA'			=> $lang['group_data'],
					'L_GROUP_LOGO'			=> $lang['group_logo'],
					
							
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					'L_SHOW'				=> $lang['show'],
					'L_NOSHOW'				=> $lang['noshow'],
					
					'GROUP_NAME'			=> $group['group_name'],
					'GROUP_DESCRIPTION'		=> $group['group_description'],
					'GROUP_COLOR'			=> $group['group_color'],
					
					
					'S_CHECKED_LEGEND_YES'	=> ( $group['group_legend']) ? ' checked="checked"' : '',
					'S_CHECKED_LEGEND_NO'	=> ( !$group['group_legend']) ? ' checked="checked"' : '',
					
					'S_GROUP_ACCESS'		=> $s_group_access,
					'S_GROUP_TYPE'			=> $s_group_type,
					'S_GROUP_RANK'			=> _select_rank($group['group_rank'], 2),
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_OVERVIEW'			=> append_sid("admin_groups.php?mode=list"),
					'S_MEMBER_ACTION'		=> append_sid("admin_groups.php?mode=member&amp;" . POST_GROUPS_URL . "=" . $group_id),
					'S_GROUP_ACTION'		=> append_sid("admin_groups.php"),
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addgroup':
			
				$group_name			= ( isset($HTTP_POST_VARS['group_name']) )			? trim($HTTP_POST_VARS['group_name']) : '';
				$group_access		= ( isset($HTTP_POST_VARS['group_access']) )		? intval($HTTP_POST_VARS['group_access']) : '';
				$group_type			= ( isset($HTTP_POST_VARS['group_type']) )			? intval($HTTP_POST_VARS['group_type']) : '';
				$group_description	= ( isset($HTTP_POST_VARS['group_description']) )	? trim($HTTP_POST_VARS['group_description']) : '';
				$group_color		= ( isset($HTTP_POST_VARS['group_color']))			? trim($HTTP_POST_VARS['group_color']) : '';
				$group_legend		= ( isset($HTTP_POST_VARS['group_legend']) )		? intval($HTTP_POST_VARS['group_legend']) : '0';
				$group_rank			= ( isset($HTTP_POST_VARS['rank_id']) )				? intval($HTTP_POST_VARS['rank_id']) : '0';
				
				if ( $group_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back'], '');
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
				
				$sql = 'SELECT MAX(group_order) AS max_order FROM ' . GROUPS . ' WHERE group_single_user = 0';
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				$sql = 'INSERT INTO ' . GROUPS . " (group_name, $sql_field, group_color, group_legend, group_rank, group_order)
					VALUES ('" . str_replace("\'", "''", $group_name) . "', $sql_value, '$group_color', $group_legend, $group_rank, $next_order)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'acp_group_add', $group_name);
	
				$message = $lang['create_group'] . '<br /><br />' . sprintf($lang['click_return_group'], '<a href="' . append_sid("admin_groups.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editgroup':
			
				$group_name			= ( isset($HTTP_POST_VARS['group_name']) )			? trim($HTTP_POST_VARS['group_name']) : '';
				$group_access		= ( isset($HTTP_POST_VARS['group_access']) )		? intval($HTTP_POST_VARS['group_access']) : '1';
				$group_type			= ( isset($HTTP_POST_VARS['group_type']) )			? intval($HTTP_POST_VARS['group_type']) : '4';
				$group_description	= ( isset($HTTP_POST_VARS['group_description']) )	? trim($HTTP_POST_VARS['group_description']) : '';
				$group_color		= ( isset($HTTP_POST_VARS['group_color']))			? trim($HTTP_POST_VARS['group_color']) : '';
				$group_legend		= ( isset($HTTP_POST_VARS['group_legend']) )		? intval($HTTP_POST_VARS['group_legend']) : '0';
				$group_rank			= ( isset($HTTP_POST_VARS['rank_id']) )				? intval($HTTP_POST_VARS['rank_id']) : '0';
				
				if ( $group_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back'], '');
				}
				
				$group = get_data('groups', $group_id, 0);
				
				$sql_auth = '';
				for ( $i = 0; $i < count($group_auth_fields); $i++ )
				{
					$value = intval($HTTP_POST_VARS[$group_auth_fields[$i]]);
	
					$sql_auth .= ( ( $sql_auth != '' ) ? ', ' : '' ) . $group_auth_fields[$i] . ' = ' . $value;
				}
				
				$sql = "UPDATE " . GROUPS . " SET
							group_name			= '" . str_replace("\'", "''", $group_name) . "',
							group_access		= $group_access,
							group_type			= $group_type,
							group_description	= '" . str_replace("\'", "''", $group_description) . "',
							group_color			= '" . str_replace("\'", "''", $group_color) . "',
							group_legend		= $group_legend,
							group_rank			= $group_rank,
							$sql_auth
						WHERE group_id = " . intval($HTTP_POST_VARS[POST_GROUPS_URL]);
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( $group['group_color'] != $group_color )
				{
					$sql = 'SELECT user_id
								FROM ' . USERS . '
							WHERE user_color = "' . $group['group_color'] . '"';;
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$users = $db->sql_fetchrowset($result);
					
					$user_in = '';
					for ( $i = 0; $i < count($users); $i++ )
					{
						$user_in .= ( ( $user_in != '' ) ? ', ' : '' ) . $users[$i]['user_id'];
					}
					
					$sql = "UPDATE " . USERS . " SET user_color = '$group_color' WHERE user_id IN ($user_in)";
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				if ( $group['group_access'] != $group_access )
				{
					
					$sql = 'SELECT user_level
								FROM ' . USERS . '
							WHERE user_level = "' . $group['group_access'] . '"';;
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$users = $db->sql_fetchrowset($result);
					
					$user_in = '';
					for ( $i = 0; $i < count($users); $i++ )
					{
						group_set_auth($users[$i]['user_id'], $group_id);
					}
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'acp_group_edit', $group_name);
				
				$message = $lang['update_group'] . '<br /><br />' . sprintf($lang['click_return_group'], '<a href="' . append_sid("admin_groups.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
				
				case 'member':
			
					$template->set_filenames(array('body' => './../admin/style/acp_groups.tpl'));
					$template->assign_block_vars('group_member', array());
				
					$sql = 'SELECT gu.group_mod, gu.user_pending, u.user_id, u.username, u.user_regdate
								FROM ' . USERS . ' u, ' . GROUPS_USERS . ' gu
								WHERE gu.group_id = ' . $group_id . ' AND gu.user_id = u.user_id';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
					}
					$group_members = $db->sql_fetchrowset($result);
					
					$group_mods = $group_nomods = $group_pending = array();
					
					if ( $group_members )
					{
						foreach ( $group_members as $member => $row )
						{
							if ( $row['group_mod'] )
							{
								$group_mods[] = $row;
							}
							else if ( $row['user_pending'] )
							{
								$group_pending[] = $row;
							}
							else
							{
								$group_nomods[] = $row;
							}
						}
					}
					
					if ( $group_mods )
					{
						for ( $i = 0; $i < count($group_mods); $i++ )
						{
							$class = ($i % 2) ? 'row_class1' : 'row_class2';

							$template->assign_block_vars('group_member.mods_row', array(
								'CLASS' 		=> $class,
								'USER_ID'		=> $group_mods[$i]['user_id'],
								'USERNAME'		=> $group_mods[$i]['username'],
								'REGISTER'		=> create_date('d.m.Y', $group_mods[$i]['user_regdate'], $userdata['user_timezone']),
							));
						}
					}
					else
					{
						$template->assign_block_vars('group_member.switch_no_moderators', array());
						$template->assign_vars(array('L_NO_MODERATORS' => $lang['no_moderators']));
					}
				
					if ( $group_nomods )
					{
						for ( $i = 0; $i < count($group_nomods); $i++ )
						{
							$class = ($i % 2) ? 'row_class1' : 'row_class2';

							$template->assign_block_vars('group_member.nomods_row', array(
								'CLASS' 		=> $class,
								'USER_ID'		=> $group_nomods[$i]['user_id'],
								'USERNAME'		=> $group_nomods[$i]['username'],
								'REGISTER'		=> create_date('d.m.Y', $group_nomods[$i]['user_regdate'], $userdata['user_timezone']),
							));
						}
					}
					else
					{
						$template->assign_block_vars('group_member.switch_no_members', array());
						$template->assign_vars(array('L_NO_MEMBERS' => $lang['no_members']));
					}
					
					if ( $group_pending )
					{
						$template->assign_block_vars('group_member.pending', array());
						
						for ( $i = 0; $i < count($group_pending); $i++ )
						{
							$class = ($i % 2) ? 'row_class1' : 'row_class2';
							
							$template->assign_block_vars('group_member.pending.pending_row', array(
								'CLASS' 		=> $class,
								'USER_ID'		=> $group_pending[$i]['user_id'],
								'USERNAME'		=> $group_pending[$i]['username'],
								'REGISTER'		=> create_date('d.m.Y', $group_pending[$i]['user_regdate'], $userdata['user_timezone']),
							));
						}
					}
					else
					{
						$template->assign_block_vars('group_member.switch_pending', array());
						$template->assign_vars(array('L_NO_MEMBERS' => $lang['no_members']));
					}
	
					$sql_id = '';
					
					if ( $group_members )
					{
						foreach ($group_members as $member )
						{
							$ids[] = $member['user_id'];
						}
						
						$sql_id .= " AND NOT user_id IN (" . implode(', ', $ids) . ")";
					}
					
					$sql = 'SELECT username, user_id
								FROM ' . USERS . '
								WHERE user_id <> ' . ANONYMOUS . $sql_id;
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
					}
					
					$s_addusers_select = '<select class="select" name="members_select[]" rows="5" multiple>';
					while ($addusers = $db->sql_fetchrow($result))
					{
						$s_addusers_select .= '<option value="' . $addusers['user_id'] . '">' . $addusers['username'] . '&nbsp;</option>';
					}
					$s_addusers_select .= '</select>';
					
					$s_action_options = '<select class="postselect" name="mode">';
					$s_action_options .= '<option value="option">&raquo; ' . $lang['option_select'] . '</option>';
					$s_action_options .= '<option value="approve">&raquo; Antrag zustimmen</option>';
					$s_action_options .= '<option value="remove">&raquo; Antrag verweigern</option>';
					$s_action_options .= '<option value="change_level">&raquo; Gruppenrechte geben/nehmen</option>';
					
					$s_action_options .= '<option value="deluser">&raquo; ' . $lang['delete'] . '</option>';
					$s_action_options .= '</select>';
					
					$s_hidden_fields = '<input type="hidden" name="rank_id" value="" />';
					$s_hidden_fields .= '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
					
					$s_hidden_fields2 = '<input type="hidden" name="mode" value="adduser" />';
					$s_hidden_fields2 .= '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
	
					$template->assign_vars(array(
						'L_GROUP_TITLE'			=> $lang['group_head'],
						'L_GROUP_NEW_EDIT'		=> $lang['group_edit'],
						'L_GROUP_MEMBER'		=> $lang['group_view_member'],
						'L_GROUP_ADD_MEMBER'	=> $lang['group_add_member'],
						'L_OVERVIEW'			=> $lang['group_overview'],
						
						'L_GROUP_ADD'			=> $lang['group_add_member'],
						'L_GROUP_ADD_MEMBER_EX'	=> $lang['group_add_member_ex'],
						
						
						
						'L_GROUP_NAME'			=> $lang['group_name'],
						
						'L_SUBMIT'				=> $lang['Submit'],
						
						'L_USERNAME'			=> $lang['username'],
						'L_REGISTER'			=> $lang['register'],
						'L_JOIN'				=> $lang['joined'],
						'L_RANK'				=> $lang['rank'],
						
						
						'L_MODERATOR'			=> $lang['moderators'],
						'L_MEMBER'				=> $lang['members'],
						'L_PENDING_MEMBER'		=> $lang['pending_members'],
						
						'L_MARK_ALL'			=> $lang['mark_all'],
						'L_MARK_DEALL'			=> $lang['mark_deall'],
						
						'S_ACTION_ADDUSERS'		=> $s_addusers_select,
						'S_ACTION_OPTIONS'		=> $s_action_options,
						
						'S_OVERVIEW'			=> append_sid("admin_groups.php?mode=list"),
						'S_GROUP_EDIT'			=> append_sid("admin_groups.php?mode=edit&amp;" . POST_GROUPS_URL . "=" . $group_id),
						'S_GROUP_ACTION'		=> append_sid("admin_groups.php"),
						'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
						'S_HIDDEN_FIELDS2'		=> $s_hidden_fields2
					));
				
					$template->pparse('body');
				
				break;
			
				case 'approve':
				case 'deny':
				case 'remove':
					
					$group_info = get_data('groups', $group_id, 0);
					
					$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($config['script_path']));
					$script_name = ( $script_name != '' ) ? $script_name . '/groups.php' : 'groups.php';
					$server_name = trim($config['server_name']);
					$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
					$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';
					$server_url = $server_protocol . $server_name . $server_port . $script_name;
					
					$members = ( $mode == 'approve' || $mode == 'deny' ) ? $HTTP_POST_VARS['pending_members'] : $HTTP_POST_VARS['members'];

					$sql_in = '';
					for($i = 0; $i < count($members); $i++)
					{
						$sql_in .= ( ( $sql_in != '' ) ? ', ' : '' ) . intval($members[$i]);
					}

					if ( $mode == 'approve' )
					{
						$sql = 'UPDATE ' . GROUPS_USERS . '
									SET user_pending = 0
									WHERE user_id IN (' . $sql_in . ')
										AND group_id = ' . $group_id;
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						for( $k = 0; $k < count($members); $k++)
						{
							group_set_auth($members[$k]['user_id'], $group_id);
						}
						
						$sql_select = 'SELECT user_email FROM ' . USERS . ' WHERE user_id IN (' . $sql_in . ')';
					}
					else if ( $mode == 'deny' || $mode == 'remove' )
					{
						$sql = 'DELETE FROM ' . GROUPS_USERS . ' WHERE user_id IN (' . $sql_in . ') AND group_id = ' . $group_id;
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						for( $i = 0; $i < count($members); $i++ )
						{
							group_reset_auth($members[$i]['user_id'], $group_id);
						}
					}

					if ( $mode == 'approve' )
					{
						if ( !($result = $db->sql_query($sql_select)) )
						{
							message_die(GENERAL_ERROR, 'Could not get user email information', '', __LINE__, __FILE__, $sql);
						}

						$bcc_list = array();
						while ($row = $db->sql_fetchrow($result))
						{
							$bcc_list[] = $row['user_email'];
						}

						$group_name = $group_info['group_name'];

						include($root_path . 'includes/emailer.php');
						$emailer = new emailer($config['smtp_delivery']);

						$emailer->from($config['page_email']);
						$emailer->replyto($config['page_email']);

						for ($i = 0; $i < count($bcc_list); $i++)
						{
							$emailer->bcc($bcc_list[$i]);
						}

						$emailer->use_template('group_approved');
						$emailer->set_subject($lang['Group_approved']);

						$emailer->assign_vars(array(
							'SITENAME' => $config['sitename'], 
							'GROUP_NAME' => $group_name,
							'EMAIL_SIG' => (!empty($config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $config['board_email_sig']) : '', 

							'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . "=$group_id")
						);
						$emailer->send();
						$emailer->reset();
					}
					$show_index = TRUE;
				
				break;
				
			case 'change_level':
				
				$members_select = array();
				$members_mark = count($HTTP_POST_VARS['members']);
				
				for ( $i = 0; $i < $members_mark; $i++ )
				{
					if ( intval($HTTP_POST_VARS['members'][$i]) )
					{
						$members_select[] = intval($HTTP_POST_VARS['members'][$i]);
					}
				}
				
				if ( count($members_select) > 0 )
				{
					$user_ids = implode(', ', $members_select);
					
					$sql = 'SELECT user_id
								FROM ' . GROUPS_USERS . '
								WHERE group_id = ' . $group_id . '
									AND group_mod = 1
									AND user_id IN (' . $user_ids . ')';
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$group_mods = array();
					while ( $row = $db->sql_fetchrow($result) )
					{
						$group_mods[] = $row['user_id'];
					}
					$db->sql_freeresult($result);

					if ( count($group_mods) > 0)
					{
						$sql = 'UPDATE ' . GROUPS_USERS . '
									SET group_mod = 0
									WHERE group_id = ' . intval($group_id) . '
										AND user_id IN (' . implode(', ', $group_mods) . ')';
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					$sql_in = ( empty($group_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $group_mods) . ')');
					
					$sql = 'UPDATE ' . GROUPS_USERS . '
								SET group_mod = 1
								WHERE group_id = ' . intval($group_id) . '
									AND user_id IN (' . implode(', ', $members_select) . ')' . $sql_in;
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					$message = $lang['group_set_mod']
						. '<br /><br />' . sprintf($lang['click_return_group'], '<a href="' . append_sid("admin_groups.php") . '">', '</a>')
						. '<br /><br />' . sprintf($lang['click_return_group_member'], '<a href="' . append_sid("admin_groups.php?mode=member&" . POST_GROUPS_URL . "=$group_id") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);

				}
			
				break;
				
			case 'list':
			
				$template->set_filenames(array('body' => './../admin/style/acp_groups.tpl'));
				$template->assign_block_vars('groups_list', array());
			
				$sql = 'SELECT * FROM ' . GROUPS . ' WHERE group_single_user = 0 AND group_type != ' . GROUP_SYSTEM . ' ORDER BY group_order';
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$groups_data = $db->sql_fetchrowset($result); 
				
				for ( $i = $start; $i < min(5 + $start, count($groups_data)); $i++)
				{
					$template->assign_block_vars('groups_list.groups_data', array(
						'GROUP_NAME'	=> $groups_data[$i]['group_name'],
					));

					for ($j = 0; $j < count($group_auth_fields); $j++ )
					{
						$selected_yes = ( $groups_data[$i][$group_auth_fields[$j]] == $group_auth_const[1] ) ? ' checked="checked"' : '';
						$selected_no = ( $groups_data[$i][$group_auth_fields[$j]] == $group_auth_const[0] ) ? ' checked="checked"' : '';
						
						$custom_auth[$j] = '<input type="radio" name="' . $group_auth_fields[$j] . $groups_data[$i]['group_id']  . '"';
						$custom_auth[$j] .= ' value="1" ' . $selected_yes . '> ' . $lang['Forum_' . $group_auth_levels[1]];
						$custom_auth[$j] .= '&nbsp;&nbsp;<input type="radio" name="' . $group_auth_fields[$j] . $groups_data[$i]['group_id']  . '"';
						$custom_auth[$j] .= ' value="0" ' . $selected_no . '> ' . $lang['Forum_' . $group_auth_levels[0]];
					
						$cell_title = '<td class="row1">' . $lang['auths'][$group_auth_fields[$j]] . '</td>';
				
						$template->assign_block_vars('groups_list.groups_data.groups_auth', array(
							'CELL_TITLE'			=> ( $i == '0' || $i == '5' || $i == '10' || $i == '15' ) ? $cell_title : '',
							'S_AUTH_LEVELS_SELECT'	=> $custom_auth[$j],
						));
					}
				}
				
				$current_page = ( !count($groups_data) ) ? 1 : ceil( count($groups_data) / 5 );
				
				$template->assign_vars(array(
					'L_GROUP_TITLE'			=> $lang['group_head'],
					'L_GROUP_EXPLAIN'		=> $lang['group_explain'],
					'L_OVERVIEW'			=> $lang['group_overview'],
					'L_OVERVIEW_EXPLAIN'	=> $lang['group_overview_explain'],
					'L_GOTO_PAGE'			=> $lang['Goto_page'],
					
					'PAGINATION'			=> generate_pagination("admin_groups.php?mode=list", count($groups_data), 5, $start),
					'PAGE_NUMBER'			=> sprintf($lang['Page_of'], ( floor( $start / 5 ) + 1 ), $current_page ), 
		

					'S_OVERVIEW'			=> append_sid("admin_groups.php?mode=list"),
					'S_GROUP_ACTION'		=> append_sid("admin_groups.php"),
				));
				
				$template->pparse('body');
			
				break;
				
			case 'adduser':
			
				$members	= ( isset($HTTP_POST_VARS['members']) ) ? $HTTP_POST_VARS['members'] : '';
				$members_s	= ( isset($HTTP_POST_VARS['members_select']) ) ? $HTTP_POST_VARS['members_select'] : '';
				$mod		= ( isset($HTTP_POST_VARS['mod']) ) ? 1 : 0 ;
				
				if ( !$members && !$members_s )
				{
					message_die(GENERAL_ERROR, $lang['team_no_select']);
				}
				else
				{
					if ( $members )
					{
						$members = trim($members, ', ');
						$members = trim($members, ',');
						
						$username_ary = array_unique(explode(', ', $members));
						
						$which_ary = 'username_ary';
						
						if ($$which_ary && !is_array($$which_ary))
						{
							$$which_ary = array($$which_ary);
						}
						
						$sql_in = $$which_ary;
						unset($$which_ary);
						
						$sql_in = implode("', '", $sql_in);
						
						$user_id_ary = $username_ary = array();
						
						$sql = 'SELECT *
									FROM ' . USERS . '
									WHERE username IN ("' . $sql_in . '")';
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_MESSAGE, 'Error getting group information', '', __LINE__, __FILE__, $sql);
						}
						
						if (!($row = $db->sql_fetchrow($result)))
						{
							$db->sql_freeresult($result);
							message_die(GENERAL_MESSAGE, $lang['team_no_new'], '');
						}
						
						do
						{
							$username_ary[$row['user_id']] = $row['username'];
							$user_id_ary[] = $row['user_id'];
						}
						while ($row = $db->sql_fetchrow($result));
						$db->sql_freeresult($result);
					}
					
					$user_id_ary = ( $members ) ? $user_id_ary : $members_s;
						
					$user_id_ary_im = implode('", "', $user_id_ary);
					
					$sql = 'SELECT user_id
								FROM ' . GROUPS_USERS . '
								WHERE user_id IN ("' . $user_id_ary_im . '")
									AND group_id = ' . $group_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
					}
					
					$add_id_ary = $user_id_ary_im = array();
					
					while ($row = $db->sql_fetchrow($result))
					{
						$add_id_ary[] = (int) $row['user_id'];
					}
					$db->sql_freeresult($result);
						
					$add_id_ary = array_diff($user_id_ary, $add_id_ary);
					
					if (!sizeof($add_id_ary) && !sizeof($user_id_arya))
					{
						message_die(GENERAL_MESSAGE, $lang['team_no_new']);
					}
					
					if (sizeof($add_id_ary))
					{
						$sql_ary = array();
				
						foreach ($add_id_ary as $user_id)
						{
							$sql_ary[] = array(
								'user_id'		=> (int) $user_id,
								'group_id'		=> (int) $group_id,
								'group_mod'		=> $mod,
							);
						}
				
						if (!sizeof($sql_ary))
						{
							message_die(GENERAL_ERROR, 'Fehler', '', __LINE__, __FILE__, $sql);
						}
						
						$ary = array();
						foreach ($sql_ary as $id => $_sql_ary)
						{
							$values = array();
							foreach ($_sql_ary as $key => $var)
							{
								$values[] = intval($var);
							}
							$ary[] = '(' . implode(', ', $values) . ')';
						}
			
						
						$sql = 'INSERT INTO ' . GROUPS_USERS . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary);
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}

					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'acp_group_add_member');
			
					$message = $lang['msg_group_add_member']
						. '<br /><br />' . sprintf($lang['click_return_group'], '<a href="' . append_sid("admin_groups.php") . '">', '</a>')
						. '<br /><br />' . sprintf($lang['click_return_group_member'], '<a href="' . append_sid("admin_groups.php?mode=member&" . POST_GROUPS_URL . "=$group_id") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}
				
			break;
			
			case 'deluser':
			
				$members = $_POST['members'];
				
				if (!$_POST['members'])
				{
					message_die(GENERAL_ERROR, $lang['team_no_select']);
				}
				
				$sql_in = implode(", ", $members);
				
				$sql = "DELETE FROM " . GROUPS_USERS . " WHERE user_id IN ($sql_in) AND group_id = $group_id";
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'acp_group_delete_member');
			
				$message = $lang['msg_group_del_member']
					. '<br /><br />' . sprintf($lang['click_return_group'], '<a href="' . append_sid("admin_groups.php") . '">', '</a>')
					. '<br /><br />' . sprintf($lang['click_return_group_member'], '<a href="' . append_sid("admin_groups.php?mode=member&" . POST_GROUPS_URL . "=$group_id") . '">', '</a>');				
				message_die(GENERAL_MESSAGE, $message);
			
			break;
			
			case 'order':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . GROUPS . " SET group_order = group_order + $move WHERE group_id = $group_id";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
		
				renumber_order('groups', '0');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'acp_group_order');
				
				$show_index = TRUE;
	
				break;
				
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $group_id && $confirm )
				{	
					$group = get_data('groups', $group_id, 0);
				
					$sql = 'DELETE FROM ' . GROUPS . " WHERE group_id = $group_id";
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'acp_group_delete', $group['group_name']);
					
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
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_groups']);
				}
				
				$template->pparse('body');
				
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
		'L_OVERVIEW'			=> $lang['group_overview'],
		'L_OVERVIEW_EXPLAIN'	=> $lang['group_overview_explain'],
		
		'L_GROUP_ADD'			=> $lang['group_add'],
		'L_GROUP_NAME'			=> $lang['group_name'],
		'L_GROUP_MEMBERCOUNT'	=> $lang['group_membercount'],
		
		'L_EDIT'				=> $lang['edit'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_MEMBER'				=> $lang['member'],
		
		'S_OVERVIEW'			=> append_sid("admin_groups.php?mode=list"),
		'S_GROUP_ACTION'		=> append_sid("admin_groups.php"),
	));
	
	$sql = 'SELECT MAX(group_order) AS max FROM ' . GROUPS . ' WHERE group_single_user = 0';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
	}
	$max_order = $db->sql_fetchrow($result);
	
	$sql = 'SELECT * FROM ' . GROUPS . ' WHERE group_single_user = 0 ORDER BY group_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $db->sql_numrows($result) )
	{
		$type = '';
		$group_data = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$group_data[$row['group_id']] = $row;
			$group_data[$row['group_id']]['total_members'] = 0;
		}
		$db->sql_freeresult($result);
		
		$sql = 'SELECT COUNT(gu.user_id) AS total_members, gu.group_id
					FROM ' . GROUPS_USERS . ' gu
					WHERE gu.group_id IN (' . implode(', ', array_keys($group_data)) . ')
				GROUP BY gu.group_id';
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
		}
		
		while ($row = $db->sql_fetchrow($result))
		{
			$group_data[$row['group_id']]['total_members'] = $row['total_members'];
		}
		$db->sql_freeresult($result);
		
		foreach ($group_data as $group_id => $row)
		{
			$group_id = $row['group_id'];
			
			$template->assign_block_vars('display.group_row', array(
			//	'NAME'			=> ( $row['group_color'] ) ? '<div style="color:' . $row['group_color'] . '">' . $row['group_name'] . '</div>' : $row['group_name'],
				'NAME'			=> $row['group_name'],
				'MEMBER_COUNT'	=> $row['total_members'],
			
				'MOVE_UP'			=> ( $row['group_order'] != '10' )				? '<a href="' . append_sid("admin_groups.php?mode=order&amp;move=-15&amp;" . POST_GROUPS_URL . "=" . $group_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'			=> ( $row['group_order'] != $max_order['max'] )	? '<a href="' . append_sid("admin_groups.php?mode=order&amp;move=15&amp;" . POST_GROUPS_URL . "=" . $group_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'L_DELETE'		=> ( $row['group_type'] != GROUP_SYSTEM ) ? $lang['delete'] : '',
				
				'U_MEMBER'		=> append_sid("admin_groups.php?mode=member&amp;" . POST_GROUPS_URL . "=" . $group_id),
				'U_DELETE'		=> ( $row['group_type'] != GROUP_SYSTEM ) ? append_sid("admin_groups.php?mode=delete&amp;" . POST_GROUPS_URL . "=" . $group_id) : '',
				'U_EDIT'		=> append_sid("admin_groups.php?mode=edit&amp;" . POST_GROUPS_URL . "=" . $group_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_groups', array());
		$template->assign_vars(array('NO_GAMES' => $lang['group_empty']));
	}
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>