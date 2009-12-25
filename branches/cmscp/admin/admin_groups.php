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
	
	if ( $userauth['auth_groups'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_groups']['_submenu_settings'] = $filename;
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
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	$start		= ( request('start') ) ? request('start') : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$group_id	= request(POST_GROUPS_URL);
	$move		= request('move', 'num');
	$confirm	= request('confirm');
	$mode		= request('mode');
	$show_index	= '';
	
	$group_auth_fields	= get_authlist();
	$group_auth_levels	= array('allowed', 'disallowed');
	$group_auth_const	= array(AUTH_ALLOWED, AUTH_DISALLOWED);
	
	if ( !$userauth['auth_groups'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_groups.php', true));
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_groups.tpl'));
				$template->assign_block_vars('groups_edit', array());
				
				if ( $mode == '_update' )
				{
					$group		= get_data(GROUPS, $group_id, 1);
					$new_mode	= '_update_save';

					$template->assign_block_vars('groups_edit.edit_group', array());
				}
				else
				{
					$group = array (
						'group_name'		=> request('group_name', 'text'),
						'group_type'		=> '1',
						'group_access'		=> '1',
						'group_desc'		=> '',
						'group_color'		=> '',
						'group_image'		=> '',
						'group_rank'		=> '0',
						'group_legend'		=> '0',
						'group_mod'			=> '0',
					);
					$new_mode = '_create_save';
					
					for ( $i = 0; $i < count($group_auth_fields); $i++ )
					{
						$group[$group_auth_fields[$i]] = '0';
					}
					
					$template->assign_block_vars('groups_edit.add_group', array());
				}
				
				$s_group_access = '<select name="group_access" class="post">';

				foreach ($lang['group_access_opt'] as $const => $name)
				{
					$selected = ( $const == $group['group_access'] ) ? ' selected="selected"' : '';
					$protect = ( GROUP_SYSTEM == $group['group_type'] ) ? ' disabled' : '';
					$s_group_access .= '<option value="' . $const . '"' . $selected . $protect . '>' . $name . '</option>';
				}
				$s_group_access .= '</select>';
				
				$s_group_type = '<select name="group_type" class="post">';

				foreach ( $lang['group_type_opt'] as $const => $name )
				{
					$selected	= ( $group['group_type'] == $const ) ? ' selected="selected"' : '';
					$protect	= ( $group['group_type'] == GROUP_SYSTEM ) ? ' disabled' : '';
					$protect_go	= ( $const == GROUP_SYSTEM ) ? ' disabled' : '';
					$s_group_type .= '<option value="' . $const . '"' . $selected . $protect . $protect_go . '>' . $name . '</option>';
				}
				$s_group_type .= '</select>';
				
				for ( $j = 0; $j < count($group_auth_fields); $j++ )
				{
					if ( $userdata['user_level'] > $group['group_access'] || $userdata['user_level'] == ADMIN )
					{
						$selected_yes	= ( $group[$group_auth_fields[$j]] == $group_auth_const[0] ) ? 'checked="checked"' : '';
						$selected_no	= ( $group[$group_auth_fields[$j]] == $group_auth_const[1] ) ? 'checked="checked"' : '';
					}
					else if ( $userdata['user_level'] <= $group['group_access'] )
					{
						$selected_yes	= ( $group[$group_auth_fields[$j]] == $group_auth_const[0] ) ? 'checked="checked" disabled' : 'disabled';
						$selected_no	= ( $group[$group_auth_fields[$j]] == $group_auth_const[1] ) ? 'checked="checked" disabled' : 'disabled';
					}
					
					$custom_auth[$j] = '';
					$custom_auth[$j] .= '<label><input type="radio" name="' . $group_auth_fields[$j] . '" id="' . $group_auth_fields[$j] . '" value="1" ' . $selected_yes . '>&nbsp;' . $lang['group_' . $group_auth_levels[0]] . '</label>&nbsp;';
					$custom_auth[$j] .= '&nbsp;<label><input type="radio" name="' . $group_auth_fields[$j] . '" value="0" ' . $selected_no . '>&nbsp;' . $lang['group_' . $group_auth_levels[1]] . '</label>';

					$cell_title = $lang['auths'][$group_auth_fields[$j]];
					
					$template->assign_block_vars('groups_edit.group_auth_data', array(
						'CELL_TITLE'			=> $cell_title,
						'S_AUTH_NAME'			=> $group_auth_fields[$j],
						'S_AUTH_LEVELS_SELECT'	=> $custom_auth[$j],
					));
				}
	
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';

				$template->assign_vars(array(
					'L_GROUP_HEAD'			=> sprintf($lang['sprintf_head'], $lang['groups']),
					'L_GROUP_NEW_EDIT'		=> ( $mode == '_create' ) ? sprintf($lang['sprintf_add'], $lang['group']) : sprintf($lang['sprintf_edit'], $lang['group']),
					'L_GROUP_OVERVIEW'		=> sprintf($lang['sprintf_right_overview'], $lang['groups']),
					'L_GROUP_MEMBER'		=> $lang['group_view_member'],
					
					'L_GROUP_NAME'			=> sprintf($lang['sprintf_name'], $lang['groups']),
					'L_GROUP_DESC'			=> sprintf($lang['sprintf_desc'], $lang['groups']),
					
					'L_GROUP_MOD'			=> $lang['group_mod'],
					'L_GROUP_ACCESS'		=> $lang['group_access'],
					'L_GROUP_TYPE'			=> $lang['group_type'],
					'L_GROUP_LEGEND'		=> $lang['group_legend'],
					'L_GROUP_COLOR'			=> $lang['group_color'],
					'L_GROUP_RANK'			=> $lang['group_rank'],
					
					'L_GROUP_AUTH'			=> $lang['group_auth'],
					'L_GROUP_DATA'			=> $lang['group_data'],
					'L_GROUP_IMAGE'			=> $lang['group_image'],
					
					'L_GROUP_IMAGE_UPLOAD'	=> $lang['group_image_upload'],
					'L_GROUP_IMAGE_CURRENT'	=> $lang['group_image_current'],
					'L_IMAGE_DELETE'		=> $lang['common_image_delete'],
					
					'L_NO'					=> $lang['common_no'],
					'L_YES'					=> $lang['common_yes'],
					'L_RESET'				=> $lang['common_reset'],
					'L_SUBMIT'				=> $lang['common_submit'],
					
					
					'L_SHOW'				=> $lang['show'],
					'L_NOSHOW'				=> $lang['noshow'],
					
					'GROUP_NAME'			=> $group['group_name'],
					'GROUP_DESC'			=> $group['group_desc'],
					'GROUP_COLOR'			=> $group['group_color'],
					'GROUP_IMAGE'			=> $root_path . $settings['path_groups'] . '/' . $group['group_image'],
					
					
					'S_LEGEND_YES'	=> ( $group['group_legend'] ) ? ' checked="checked"' : '',
					'S_LEGEND_NO'	=> ( !$group['group_legend'] ) ? ' checked="checked"' : '',
					
					'S_GROUP_TYPE'			=> $s_group_type,
					'S_GROUP_ACCESS'		=> $s_group_access,
					
					'S_GROUP_RANK'			=> select_box('ranks', 'select', $group['group_rank'], 2),
					'S_GROUP_MOD'			=> ( $mode == '_create' ) ? select_box('user', 'select', $group['group_mod']) : '',
					
					'S_FIELDS'		=> $s_hidden_fields,
					'S_GROUP_OVERVIEW'		=> append_sid('admin_groups.php?mode=_overview'),
					'S_MEMBER_ACTION'		=> append_sid('admin_groups.php?mode=_member&amp;' . POST_GROUPS_URL . '=' . $group_id),
					'S_GROUP_ACTION'		=> append_sid('admin_groups.php'),
				));
			
				$template->pparse('body');
				
				break;
			
			case '_create_save':
			
				$group_name		= request('group_name', 'text');
				$group_mod		= request('user_id', 'num');
				$group_access	= request('group_access', 'num');
				$group_type		= request('group_type', 'num');
				$group_desc		= request('group_desc', 'text');
				$group_color	= request('group_color', 'text');
				$group_legend	= request('group_legend', 'num');
				$group_rank		= request('rank_id', 'num');
				$group_image	= request_files('group_image');
				$sql_field		= '';
				$sql_value		= '';
				
				$error_msg = '';
				$error_msg .= ( !$group_name ) ? $lang['msg_select_name'] : '';
				$error_msg .= ( !$group_mod ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_must_select_user'] : '';
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}

				for ( $i = 0; $i < count($group_auth_fields); $i++ )
				{
					$value = request($group_auth_fields[$i]);
					$field = $group_auth_fields[$i];
					
					$sql_field .= ( ( $sql_field != '' ) ? ', ' : '' ) . $field;
					$sql_value .= ( ( $sql_value != '' ) ? ', ' : '' ) . $value;
				}
				
				if ( $group_image['temp'] )
				{
					$sql_pic = image_upload('_create', 'image_group', 'group_image', '', $group['group_image'], '', $root_path . $settings['path_groups'] . '/', $group_image['temp'], $group_image['name'], $group_image['size'], $group_image['type']);
				}
				else
				{
					$sql_pic = '';
				}
				
				$max_order	= get_data_max(GROUPS, 'group_order', 'group_single_user = 0');
				$next_order	= $max_order['max'] + 10;
				
				$sql = "INSERT INTO " . GROUPS . " (group_name, $sql_field, group_color, group_legend, group_desc, group_rank, group_order)
							VALUES ('$group_name', $sql_value, '$group_color', '$group_legend', '$group_desc', '$group_rank', '$next_order')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				$group_id = $db->sql_nextid();
				
				$sql = "INSERT INTO " . GROUPS_USERS . " (user_id, group_id, group_mod) VALUES ($group_mod, $group_id, 1)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				group_set_auth($group_mod, $group_id);
				
				$message = $lang['create_group']
					. sprintf($lang['click_return_group_data'], '<a href="' . append_sid('admin_groups.php?mode=_update&' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>')
					. sprintf($lang['click_return_group'], '<a href="' . append_sid('admin_groups.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'create_group');
				message(GENERAL_MESSAGE, $message);

				break;

			case '_update_save':
			
				$group_name		= request('group_name', 'text');
				$group_access	= request('group_access', 'num');
				$group_type		= request('group_type', 'num');
				$group_desc		= request('group_desc', 'text');
				$group_color	= request('group_color', 'text');
				$group_legend	= request('group_legend', 'num');
				$group_rank		= request('rank_id', 'num');
				$group_image	= request_files('group_image');
				$sql_auth		= '';
			
				$group = get_data('groups', $group_id, 0);

				if ( !$group_name )
				{
					message(GENERAL_ERROR, $lang['msg_select_name'] . $lang['back']);
				}

				
				for ( $i = 0; $i < count($group_auth_fields); $i++ )
				{
					$value = request($group_auth_fields[$i]);
	
					$sql_auth .= ( ( $sql_auth != '' ) ? ', ' : '' ) . $group_auth_fields[$i] . ' = ' . $value;
				}
				
				if ( $group_image['temp'] )
				{
					$sql_pic = image_upload('_update', 'image_group', 'group_image', '', $group['group_image'], '', $root_path . $settings['path_groups'] . '/', $group_image['temp'], $group_image['name'], $group_image['size'], $group_image['type']);
				}
				else if ( request('network_image_delete') )
				{
					$sql_pic = image_delete($group['group_image'], '', $root_path . $settings['path_groups'] . '/', 'group_image');
				}
				else
				{
					$sql_pic = '';
				}
				
				$sql = "UPDATE " . GROUPS . " SET
							group_name		= '$group_name',
							group_access	= '$group_access',
							group_type		= '$group_type',
							group_desc		= '$group_desc',
							group_color		= '$group_color',
							group_legend	= '$group_legend',
							group_rank		= '$group_rank',
							$sql_pic
							$sql_auth
						WHERE group_id = $group_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( $group['group_color'] != $group_color )
				{
					$sql = "SELECT user_id FROM " . USERS . " WHERE user_color = '" . $group['group_color'] . "'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$users = $db->sql_fetchrowset($result);
					
					if ( $users )
					{
						$user_in = '';
						for ( $i = 0; $i < count($users); $i++ )
						{
							$user_in .= ( ( $user_in != '' ) ? ', ' : '' ) . $users[$i]['user_id'];
						}
						
						$sql = "UPDATE " . USERS . " SET user_color = '$group_color' WHERE user_id IN ($user_in)";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
				}
				
				if ( $group['group_access'] != $group_access )
				{
					$sql = "SELECT user_id FROM " . USERS . " WHERE user_level = " . $group['group_access'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$users = $db->sql_fetchrowset($result);
					
					for ( $i = 0; $i < count($users); $i++ )
					{
						group_set_auth($users[$i]['user_id'], $group_id);
					}
				}
				
				$message = $lang['create_group']
					. sprintf($lang['click_return_group_data'], '<a href="' . append_sid('admin_groups.php?mode=_update&' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>')
					. sprintf($lang['click_return_group'], '<a href="' . append_sid('admin_groups.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'update_group');
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case '_member':
			
				$template->set_filenames(array('body' => 'style/acp_groups.tpl'));
				$template->assign_block_vars('group_member', array());
			
				$sql = 'SELECT gu.group_mod, gu.user_pending, u.user_id, u.username, u.user_regdate
							FROM ' . USERS . ' u, ' . GROUPS_USERS . ' gu
							WHERE gu.group_id = ' . $group_id . ' AND gu.user_id = u.user_id';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$group_members = $db->sql_fetchrowset($result);
				
				$group_mods		= array();
				$group_nomods	= array();
				$group_pending	= array();
				
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
						$template->assign_block_vars('group_member.mods_row', array(
							'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
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
						$template->assign_block_vars('group_member.nomods_row', array(
							'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
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
						$template->assign_block_vars('group_member.pending.pending_row', array(
							'CLASS' 		=> ($i % 2) ? 'row_class1' : 'row_class2',
							'USER_ID'		=> $group_pending[$i]['user_id'],
							'USERNAME'		=> $group_pending[$i]['username'],
							'REGISTER'		=> create_date('d.m.Y', $group_pending[$i]['user_regdate'], $userdata['user_timezone']),
						));
					}
				}

				$sql_id = '';
				
				if ( $group_members )
				{
					foreach ( $group_members as $member )
					{
						$ids[] = $member['user_id'];
					}
					
					$sql_id .= " AND NOT user_id IN (" . implode(', ', $ids) . ")";
				}
					
				$sql = "SELECT username, user_id FROM " . USERS . " WHERE user_id <> " . ANONYMOUS . $sql_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$s_addusers_select = '<select class="select" name="members_select[]" rows="5" multiple>';
				while ($addusers = $db->sql_fetchrow($result))
				{
					$s_addusers_select .= '<option value="' . $addusers['user_id'] . '">' . $addusers['username'] . '&nbsp;</option>';
				}
				$s_addusers_select .= '</select>';
				
				$s_action_options = '<select class="postselect" name="mode">';
				$s_action_options .= '<option value="option">&raquo; ' . $lang['option_select'] . '</option>';
				$s_action_options .= '<option value="_user_approve">&raquo; Antrag zustimmen</option>';
				$s_action_options .= '<option value="_user_remove">&raquo; Antrag verweigern</option>';
				$s_action_options .= '<option value="_user_change">&raquo; Gruppenrechte geben/nehmen</option>';
				
				$s_action_options .= '<option value="deluser">&raquo; ' . $lang['common_delete'] . '</option>';
				$s_action_options .= '</select>';
				
				$s_hidden_fields = '<input type="hidden" name="rank_id" value="" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
				
				$s_hidden_fields2 = '<input type="hidden" name="mode" value="adduser" />';
				$s_hidden_fields2 .= '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
	
				$template->assign_vars(array(
					'L_GROUP_HEAD'			=> sprintf($lang['sprintf_head'], $lang['groups']),
					'L_GROUP_EDIT'			=> sprintf($lang['sprintf_edit'], $lang['group']),
					'L_GROUP_MEMBER'		=> sprintf($lang['sprintf_overview'], $lang['common_members']),
					'L_GROUP_OVERVIEW'		=> sprintf($lang['sprintf_right_overview'], $lang['groups']),
					'L_GROUP_NAME'			=> sprintf($lang['sprintf_name'], $lang['groups']),
					'L_REQUIRED'			=> $lang['required'],
					
					'L_MODERATOR'			=> $lang['common_moderators'],
					'L_MEMBER'				=> $lang['common_members'],
					'L_PENDING_MEMBER'		=> $lang['pending_members'],
					
					
					'L_GROUP_ADD_MEMBER'	=> $lang['group_add_member'],
					
					'L_GROUP_ADD'			=> $lang['group_add_member'],
					'L_GROUP_ADD_MEMBER_EX'	=> $lang['group_add_member_ex'],
					
					
					
					'L_SUBMIT'				=> $lang['Submit'],
					
					'L_USERNAME'			=> $lang['username'],
					'L_REGISTER'			=> $lang['register'],
					'L_JOIN'				=> $lang['joined'],
					'L_RANK'				=> $lang['rank'],
					
					'L_MARK_ALL'			=> $lang['mark_all'],
					'L_MARK_DEALL'			=> $lang['mark_deall'],
					
					'S_ACTION_ADDUSERS'		=> $s_addusers_select,
					'S_ACTION_OPTIONS'		=> $s_action_options,
					
					'S_GROUP_OVERVIEW'		=> append_sid('admin_groups.php?mode=_overview'),
					'S_GROUP_EDIT'			=> append_sid('admin_groups.php?mode=_update&amp;' . POST_GROUPS_URL . '=' . $group_id),
					'S_GROUP_ACTION'		=> append_sid('admin_groups.php'),
					'S_FIELDS'		=> $s_hidden_fields,
					'S_HIDDEN_FIELDS2'		=> $s_hidden_fields2
				));
				
				$template->pparse('body');
			
				break;
			
			case '_user_approve':
			case '_user_deny':
			case '_user_remove':
				
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
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
						message(GENERAL_ERROR, 'Could not get user email information', '', __LINE__, __FILE__, $sql);
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
						'EMAIL_SIG' => (!empty($config['board_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $config['board_email_sig']) : '', 

						'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . "=$group_id")
					);
					$emailer->send();
					$emailer->reset();
				}
				$show_index = TRUE;
			
				break;
				
			case '_user_change':
				
				$members		= request('members');
				$members_select	= array();

				for ( $i = 0; $i < count($members); $i++ )
				{
					if ( $members[$i] )
					{
						$members_select[] = (int) $members[$i];
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
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					$sql_in = ( empty($group_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $group_mods) . ')');
					
					$sql = 'UPDATE ' . GROUPS_USERS . '
								SET group_mod = 1
								WHERE group_id = ' . intval($group_id) . '
									AND user_id IN (' . implode(', ', $members_select) . ')' . $sql_in;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					$message = $lang['group_set_mod']
						. sprintf($lang['click_return_group'], '<a href="' . append_sid('admin_groups.php') . '">', '</a>')
						. sprintf($lang['click_return_group_member'], '<a href="' . append_sid('admin_groups.php?mode=_member&' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>');
					message(GENERAL_MESSAGE, $message);
				}
			
				break;
				
			case 'adduser':
			
				$members	= ( isset($HTTP_POST_VARS['members']) ) ? $HTTP_POST_VARS['members'] : '';
				$members_s	= ( isset($HTTP_POST_VARS['members_select']) ) ? $HTTP_POST_VARS['members_select'] : '';
				$mod		= ( isset($HTTP_POST_VARS['mod']) ) ? 1 : 0 ;
				
				if ( !$members && !$members_s )
				{
					message(GENERAL_ERROR, $lang['team_no_select']);
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
							message(GENERAL_MESSAGE, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if (!($row = $db->sql_fetchrow($result)))
						{
							$db->sql_freeresult($result);
							message(GENERAL_MESSAGE, $lang['team_no_new'], '');
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
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
						message(GENERAL_MESSAGE, $lang['team_no_new']);
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
							message(GENERAL_ERROR, 'Fehler', '', __LINE__, __FILE__, $sql);
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
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						for( $i = 0; $i < count($add_id_ary); $i++ )
						{
							group_set_auth($add_id_ary[$i]['user_id'], $group_id);
						}
					}

					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'acp_group_add_member');
			
					$message = $lang['msg_group_add_member']
						. sprintf($lang['click_return_group'], '<a href="' . append_sid('admin_groups.php') . '">', '</a>')
						. sprintf($lang['click_return_group_member'], '<a href="' . append_sid('admin_groups.php?mode=member&' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>');
					message(GENERAL_MESSAGE, $message);
				}
				
				break;
			
			case 'deluser':
			
				$members = $_POST['members'];
				
				if (!$_POST['members'])
				{
					message(GENERAL_ERROR, $lang['team_no_select']);
				}
				
				$sql_in = implode(", ", $members);
				
				$sql = "DELETE FROM " . GROUPS_USERS . " WHERE user_id IN ($sql_in) AND group_id = $group_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['msg_group_del_member']
					. sprintf($lang['click_return_group'], '<a href="' . append_sid('admin_groups.php') . '">', '</a>')
					. sprintf($lang['click_return_group_member'], '<a href="' . append_sid('admin_groups.php?mode=member&' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'msg_group_del_member');
				message(GENERAL_MESSAGE, $message);
			
				break;
			
			case '_overview':
			
				$template->set_filenames(array('body' => 'style/acp_groups.tpl'));
				$template->assign_block_vars('groups_list', array());
				
				$sql = "SELECT * FROM " . GROUPS . " WHERE group_single_user = 0 ORDER BY group_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$groups_data = $db->sql_fetchrowset($result);
				
				for ( $i = $start; $i < min(5 + $start, count($groups_data)); $i++)
				{
					$template->assign_block_vars('groups_list.groups_data', array('GROUP_NAME'	=> $groups_data[$i]['group_name']));

					for ( $j = 0; $j < count($group_auth_fields); $j++ )
					{
						$selected_yes	= ( $groups_data[$i][$group_auth_fields[$j]] == $group_auth_const[0] ) ? ' checked="checked"' : '';
						$selected_no	= ( $groups_data[$i][$group_auth_fields[$j]] == $group_auth_const[1] ) ? ' checked="checked"' : '';
						
						$custom_auth[$j] = '';
						$custom_auth[$j] .= '<label><input type="radio" name="' . $group_auth_fields[$j] . $groups_data[$i]['group_id']  . '" value="1" ' . $selected_yes . '> ' . $lang['group_' . $group_auth_levels[0]] . '</label>&nbsp;';
						$custom_auth[$j] .= '&nbsp;<label><input type="radio" name="' . $group_auth_fields[$j] . $groups_data[$i]['group_id']  . '" value="0" ' . $selected_no . '> ' . $lang['group_' . $group_auth_levels[1]] . '</label>';
					
						$cell_title = '<td class="row1"><label>' . $lang['auths'][$group_auth_fields[$j]] . '</label></td>';
				
						$template->assign_block_vars('groups_list.groups_data.groups_auth', array(
							'CELL_TITLE'			=> ( $i == '0' || $i == '5' || $i == '10' || $i == '15' ) ? $cell_title : '',
							'S_AUTH_LEVELS_SELECT'	=> $custom_auth[$j],
						));
					}
				}
				
				$current_page = ( !count($groups_data) ) ? 1 : ceil( count($groups_data) / 5 );
				
				$template->assign_vars(array(
					'L_GROUP_HEAD'				=> sprintf($lang['sprintf_head'], $lang['groups']),
					'L_GROUP_OVERVIEW'			=> sprintf($lang['sprintf_right_overview'], $lang['groups']),
					'L_GROUP_OVERVIEW_EXPLAIN'	=> $lang['group_overview_explain'],
					
					'L_GOTO_PAGE'				=> $lang['Goto_page'],
					
					'PAGINATION'				=> generate_pagination('admin_groups.php?mode=_list', count($groups_data), 5, $start),
					'PAGE_NUMBER'				=> sprintf($lang['Page_of'], ( floor( $start / 5 ) + 1 ), $current_page ), 
		
					'S_GROUP_ACTION'			=> append_sid('admin_groups.php'),
				));
				
				$template->pparse('body');
			
				break;
			
			case '_order':
			
				update(GROUPS, 'group', $move, $group_id);
				orders('groups', '0');
				
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'acp_group_order');
				
				$show_index = TRUE;

				break;
				
			case '_delete':
			
				if ( $group_id && $confirm )
				{	
				#	$group = get_data('groups', $group_id, 0);
				
					$sql = "DELETE FROM " . GROUPS . " WHERE group_id = $group_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = "DELETE FROM " . GROUPS_USERS . " WHERE group_id = $group_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete_group'] . sprintf($lang['click_return_group'], '<a href="' . append_sid('admin_groups.php') . '">', '</a>');
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GROUPS, 'delete_group');
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( $group_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_group'],
						'L_NO'				=> $lang['common_no'],
						'L_YES'				=> $lang['common_yes'],
						'S_FIELDS'	=> $s_fields,
						'S_ACTION'	=> append_sid('admin_groups.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_groups']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
				
				message(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_groups.tpl'));
	$template->assign_block_vars('display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_GROUP_HEAD'			=> sprintf($lang['sprintf_head'], $lang['groups']),
		'L_GROUP_OVERVIEW'		=> sprintf($lang['sprintf_right_overview'], $lang['groups']),
		'L_GROUP_CREATE'		=> sprintf($lang['sprintf_create'], $lang['group']),
		'L_GROUP_NAME'			=> sprintf($lang['sprintf_name'], $lang['groups']),
		'L_GROUP_EXPLAIN'		=> $lang['group_explain'],
		
		'L_GROUP_MEMBERCOUNT'	=> $lang['group_membercount'],
		
		'L_UPDATE'				=> $lang['common_update'],
		'L_DELETE'				=> $lang['common_delete'],
		'L_MEMBER'				=> $lang['common_members'],
		'L_SETTINGS'			=> $lang['common_settings'],
		
		'S_FIELDS'		=> $s_fields,
		'S_GROUP_OVERVIEW'		=> append_sid('admin_groups.php?mode=_overview'),
		'S_GROUP_CREATE'		=> append_sid('admin_groups.php?mode=_create'),
		'S_GROUP_ACTION'		=> append_sid('admin_groups.php'),
	));
	
	$max_order = get_data_max(GROUPS, 'group_order', 'group_single_user = 0');
	
	$sql = "SELECT * FROM " . GROUPS . " WHERE group_single_user = 0 ORDER BY group_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
			
				'MOVE_UP'			=> ( $row['group_order'] != '10' )				? '<a href="' . append_sid('admin_groups.php?mode=_order&amp;move=-15&amp;' . POST_GROUPS_URL . '=' . $group_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'			=> ( $row['group_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_groups.php?mode=_order&amp;move=15&amp;' . POST_GROUPS_URL . '=' . $group_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'L_DELETE'		=> ( $row['group_type'] != GROUP_SYSTEM ) ? $lang['common_delete'] : '',
				
				'U_MEMBER'		=> append_sid('admin_groups.php?mode=_member&amp;' . POST_GROUPS_URL . '=' . $group_id),
				'U_UPDATE'		=> append_sid('admin_groups.php?mode=_update&amp;' . POST_GROUPS_URL . '=' . $group_id),
				'U_DELETE'		=> ( $row['group_type'] != GROUP_SYSTEM ) ? append_sid('admin_groups.php?mode=_delete&amp;' . POST_GROUPS_URL . '=' . $group_id) : '',
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