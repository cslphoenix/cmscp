<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_groups'] )
	{
		$module['_headmenu_06_groups']['_submenu_settings'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_group';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('groups');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_GROUPS;
	$url	= POST_GROUPS_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_groups'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['groups']);

	$auth_fields	= get_authlist();
	$auth_levels	= array('allowed', 'disallowed');
	$auth_const		= array(AUTH_ALLOWED, AUTH_DISALLOWED);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_groups'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	debug($_POST);
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_groups.tpl'));
				$template->assign_block_vars('_input', array());
				$template->assign_block_vars('_input.' . $mode, array());
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$max	= get_data_max(GROUPS, 'group_order', 'group_single_user = 0');
					$data	= array(
								'group_name'	=> request('group_name', 2),
								'group_type'	=> '1',
								'group_access'	=> '1',
								'group_desc'	=> '',
								'group_color'	=> '',
								'group_image'	=> '',
								'group_rank'	=> '0',
								'group_legend'	=> '0',
								'group_mod'		=> '-1',
								'group_order'	=> $max['max'] + 10,
							);
					
					for ( $i = 0; $i < count($auth_fields); $i++ )
					{
						$data[$auth_fields[$i]] = '0';
					}
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(GROUPS, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
							'group_name'	=> request('group_name', 2),
							'group_type'	=> request('group_type', 0),
							'group_access'	=> request('group_access', 0),
							'group_desc'	=> request('group_desc', 2),
							'group_color'	=> request('group_color', 2),
							'group_rank'	=> request('rank_id', 0),
							'group_legend'	=> request('group_legend', 0),
							'group_image'	=> request('group_image', 2),
							'group_img'		=> request_file('group_img'),
							'group_mod'		=> ( $mode == '_create' ) ? request('user_id', 0) : '',
							'group_order'	=> request('group_order', 0),
						);
					
					for ( $i = 0; $i < count($auth_fields); $i++ )
					{
						$data[$auth_fields[$i]] = request($auth_fields[$i]);
					}
				}
										
				$data['group_image'] ? $template->assign_block_vars('_input._image', array()) : false;
				
				$s_access = "<select class=\"post\" name=\"group_access\" id=\"group_access\">";
				foreach ( $lang['group_option_access'] as $option => $name )
				{
					$selected	= ( $data['group_access'] == $option ) ? "selected=\"selected\"" : "";
					$protect	= ( $data['group_type'] == GROUP_SYSTEM ) ? "disabled" : "";
					$s_access	.= "<option value=\"$option\" $selected $protect>" . sprintf($lang['sprintf_select_format'], $name) . "</option>";
				}
				$s_access .= "</select>";
				
				$s_type = "<select class=\"post\" name=\"group_type\" id=\"group_type\">";
				foreach ( $lang['group_option_type'] as $option => $name )
				{
					$selected	= ( $data['group_type'] == $option ) ? "selected=\"selected\"" : "";
					$protect	= ( $data['group_type'] == GROUP_SYSTEM || $option == GROUP_SYSTEM ) ? "disabled" : "";
					$s_type		.= "<option value=\"$option\" $selected $protect>" . sprintf($lang['sprintf_select_format'], $name) . "</option>";
				}
				$s_type .= "</select>";
				
				for ( $j = 0; $j < count($auth_fields); $j++ )
				{
					if ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $data['group_access'] )
					{
						$selected_yes	= ( $data[$auth_fields[$j]] == $auth_const[0] ) ? 'checked="checked"' : '';
						$selected_no	= ( $data[$auth_fields[$j]] == $auth_const[1] ) ? 'checked="checked"' : '';
					}
					else if ( $userdata['user_level'] <= $data['group_access'] )
					{
						$selected_yes	= ( $data[$auth_fields[$j]] == $auth_const[0] ) ? 'checked="checked" disabled' : 'disabled';
						$selected_no	= ( $data[$auth_fields[$j]] == $auth_const[1] ) ? 'checked="checked" disabled' : 'disabled';
					}
					
					$select[$j] = "<label><input type=\"radio\" name=\"" . $auth_fields[$j] . "\" id=\"" . $auth_fields[$j] . "\" value=\"1\" $selected_yes>&nbsp;" . $lang['group_' . $auth_levels[0]] . "</label><span style=\"padding:4px;\"></span>";
					$select[$j] .= "<label><input type=\"radio\" name=\"" . $auth_fields[$j] . "\" id=\"deactivated\" value=\"0\" $selected_no>&nbsp;" . $lang['group_' . $auth_levels[1]] . "</label>";
					
					$template->assign_block_vars('_input._auth', array(
						'TITLE'		=> $lang['auths'][$auth_fields[$j]],
						'FIELDS'	=> $auth_fields[$j],
						'SELECT'	=> $select[$j],
					));
					
					$template->assign_block_vars('_auth', array('FIELDS' => $auth_fields[$j]));
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= '<input type="hidden" name="group_image" value="' . $data['group_image'] . '" />';
				$fields .= '<input type="hidden" name="group_order" value="' . $data['group_order'] . '" />';
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> $acp_title,
					'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['group'], $data['group_name']),
					'L_MEMBER'		=> $lang['member'],
					'L_OVERVIEW'	=> sprintf($lang['sprintf_right_overview'], $lang['groups']),
					
					'L_AUTH'		=> $lang['auth'],
					'L_DATA'		=> $lang['data'],
					'L_IMAGE'		=> $lang['image'],
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['groups']),
					'L_DESC'		=> sprintf($lang['sprintf_desc'], $lang['groups']),
					'L_MOD'			=> $lang['mod'],
					'L_ACCESS'		=> $lang['access'],
					'L_TYPE'		=> $lang['type'],
					'L_LEGEND'		=> $lang['legend'],
					'L_COLOR'		=> $lang['color'],
					'L_RANK'		=> $lang['rank'],
					'L_ORDER'		=> $lang['common_order'],
					'L_IMAGE_UPLOAD'	=> $lang['upload'],
					'L_IMAGE_CURRENT'	=> $lang['current'],
					'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
					
					'NAME'			=> $data['group_name'],
					'DESC'			=> $data['group_desc'],
					'COLOR'			=> $data['group_color'],
					'IMAGE'			=> $path_dir . $data['group_image'],
					
					'S_TYPE'		=> $s_type,
					'S_ACCESS'		=> $s_access,
					'S_LEGEND_YES'	=> ( $data['group_legend'] ) ? ' checked="checked"' : '',
					'S_LEGEND_NO'	=> ( !$data['group_legend'] ) ? ' checked="checked"' : '',
					
					'S_RANK'		=> select_box('ranks', 'select', $data['group_rank'], 2),
					'S_MOD'			=> ( $mode == '_create' ) ? select_box('user', 'select', $data['group_mod']) : '',
					'S_ORDER'		=> select_order('select', GROUPS, 'group', '', '', $data['group_order']),
					
					'S_OVERVIEW'	=> append_sid("$file?mode=_overview"),
					'S_MEMBER'		=> append_sid("$file?mode=_member&amp;$url=$data_id"),
					'S_ACTION'		=> append_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$group_name		= request('group_name', 2);
					$group_mod		= request('user_id', 0);
					$group_access	= request('group_access', 0);
					$group_type		= request('group_type', 0);
					$group_desc		= request('group_desc', 2);
					$group_color	= request('group_color', 2);
					$group_legend	= request('group_legend', 0);
					$group_rank		= request('rank_id', 0);
					$group_order	= request('group_order', 0);
					$group_image	= request('group_image', 2);
					$group_img		= request_file('group_img');
					$sql_field		= '';
					$sql_value		= '';
					
					if ( $group_img )
					{
						$sql_pic = image_upload($mode, 'image_group', 'group_image', '', $group_image, '', $path_dir, $group_img['temp'], $group_img['name'], $group_img['size'], $group_img['type'], $error);
					}
					else
					{
						$sql_pic = '';
					}
					
					$error .= ( !$group_name ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
					$error .= ( $mode == '_create' && $group_mod == '-1' ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_user'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							for ( $i = 0; $i < count($auth_fields); $i++ )
							{
								$value = request($auth_fields[$i]);
								$field = $auth_fields[$i];
								
								$sql_field .= ( ( $sql_field != '' ) ? ', ' : '' ) . $field;
								$sql_value .= ( ( $sql_value != '' ) ? ', ' : '' ) . $value;
							}
							
							$sql = "INSERT INTO " . GROUPS . " (group_name, $sql_field, group_color, group_legend, group_desc, group_rank, group_image, group_order)
										VALUES ('$group_name', $sql_value, '$group_color', '$group_legend', '$group_desc', '$group_rank', '$sql_pic', '$group_order')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
			
							$group_id = $db->sql_nextid();
							
							$sql = "INSERT INTO " . GROUPS_USERS . " (user_id, group_id, group_mod)
										VALUES ('$group_mod', '$group_id', 1)";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							group_set_auth($group_mod, $group_id);
							debug(group_set_auth($group_mod, $group_id));
							
							$message = $lang['create'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							$data = get_data(GROUPS, $group_id, 1);
							
							for ( $i = 0; $i < count($auth_fields); $i++ )
							{
								$value = request($auth_fields[$i]);
				
								$sql_auth .= ( ( $sql_auth != '' ) ? ', ' : '' ) . $auth_fields[$i] . ' = ' . $value;
							}
							
							if ( request('network_image_delete') )
							{
								$sql_pic = image_delete($data['group_image'], '', $root_path . $settings['path_groups'] . '/', 'group_image');
							}
							
						#	if ( $group_image )
						#	{
						#		$sql_pic = image_upload($mode, 'image_group', 'group_image', '', $data['group_image'], '', $root_path . $settings['path_groups'] . '/', $group_image['temp'], $group_image['name'], $group_image['size'], $group_image['type']);
						#	}
						#	
						#	else
						#	{
						#		$sql_pic = '';
						#	}
							
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
									WHERE group_id = $data_id";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							if ( $data['group_color'] != $group_color )
							{
								$sql = "SELECT user_id FROM " . USERS . " WHERE user_color = '" . $data['group_color'] . "'";
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
							
							if ( $data['group_access'] != $group_access )
							{
								$sql = "SELECT user_id FROM " . USERS . " WHERE user_level = " . $data['group_access'];
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$users = $db->sql_fetchrowset($result);
								
								for ( $i = 0; $i < count($users); $i++ )
								{
									group_set_auth($users[$i]['user_id'], $data_id);
								}
							}
							
							$message = $lang['create_group']
								. sprintf($lang['click_return_groups'], '<a href="' . append_sid($file) . '">', '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
						}
						
						log_add(LOG_ADMIN, $log, $mode, $group_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
			
				$template->pparse('body');
				
				break;
				
			case '_overview':
			
				$template->set_filenames(array('body' => 'style/acp_groups.tpl'));
				$template->assign_block_vars('_overview', array());
				
				$sql = "SELECT * FROM " . GROUPS . " WHERE group_single_user = 0 ORDER BY group_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$groups_data = $db->sql_fetchrowset($result);
				
				for ( $i = $start; $i < min(5 + $start, count($groups_data)); $i++)
				{
					$template->assign_block_vars('_overview.groups_data', array(
						'NAME' => $groups_data[$i]['group_name']
					));

					for ( $j = 0; $j < count($auth_fields); $j++ )
					{
#						$selected_yes	= ( $groups_data[$i][$auth_fields[$j]] == $auth_const[0] ) ? ' checked="checked"' : '';
#						$selected_no	= ( $groups_data[$i][$auth_fields[$j]] == $auth_const[1] ) ? ' checked="checked"' : '';
#						
#						$custom_auth[$j] = '';
#						$custom_auth[$j] .= '<label><input type="radio" name="' . $auth_fields[$j] . $groups_data[$i]['group_id']  . '" value="1" ' . $selected_yes . '> ' . $lang['group_' . $auth_levels[0]] . '</label>&nbsp;';
#						$custom_auth[$j] .= '&nbsp;<label><input type="radio" name="' . $auth_fields[$j] . $groups_data[$i]['group_id']  . '" value="0" ' . $selected_no . '> ' . $lang['group_' . $auth_levels[1]] . '</label>';
					
						$custom_auth[$j] = ( $groups_data[$i][$auth_fields[$j]] == $auth_const[0] ) ? $lang['group_' . $auth_levels[0]] : $lang['group_' . $auth_levels[1]];
						
						$cell_title = '<td class="row1"><label>' . $lang['auths'][$auth_fields[$j]] . '</label></td>';
				
						$template->assign_block_vars('_overview.groups_data.groups_auth', array(
							'TITLE'		=> ( $i == '0' || $i == '5' || $i == '10' || $i == '15' ) ? $cell_title : '',
							'SELECT'	=> $custom_auth[$j],
						));
					}
				}
				
				$current_page = ( !count($groups_data) ) ? 1 : ceil( count($groups_data) / 5 );
				
				$template->assign_vars(array(
					'L_HEAD'				=> $acp_title,
					'L_OVERVIEW'			=> sprintf($lang['sprintf_right_overview'], $lang['groups']),
					'L_OVERVIEW_EXPLAIN'	=> $lang['overview_explain'],
					
					'L_GOTO_PAGE'				=> $lang['Goto_page'],
					
					'PAGINATION'				=> generate_pagination("$file?mode=_list", count($groups_data), 5, $start),
					'PAGE_NUMBER'				=> sprintf($lang['Page_of'], ( floor( $start / 5 ) + 1 ), $current_page ), 
		
					'S_ACTION'			=> append_sid($file),
				));
				
				$template->pparse('body');
			
				break;
			
			case '_order':
			
				update(GROUPS, 'group', $move, $data_id);
				orders(GROUPS, '0');
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;

				break;
				
			case '_delete':
			
				if ( $data_id && $confirm )
				{
					$data = get_data(GROUPS, $data_id, 1);
					
					$sql = "DELETE FROM " . GROUPS . " WHERE group_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = "DELETE FROM " . GROUPS_USERS . " WHERE group_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete_group'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					log_add(LOG_ADMIN, $log, 'delete_group');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . $url . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_group'], $data['group_name']),
						
						'S_FIELDS'		=> $fields,
						'S_ACTION'		=> append_sid($file),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_groups']);
				}
				
				$template->pparse('body');
				
				break;
			
			case '_member':
			
				$template->set_filenames(array('body' => 'style/acp_groups.tpl'));
				$template->assign_block_vars('_member', array());
				
				/*	SQL:	Gruppen Informationen	*/
				$data = data(GROUPS, $data_id, false, 1, 1);
				
				debug($data);
				
				/*	SQL:	Mitglieder der Gruppe	*/
				$sql = 'SELECT gu.group_mod, gu.user_pending, u.user_id, u.username, u.user_regdate FROM ' . USERS . ' u, ' . GROUPS_USERS . ' gu WHERE gu.group_id = ' . $data_id . ' AND gu.user_id = u.user_id';
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
						$template->assign_block_vars('_member._mods_row', array(
							'USER_ID'	=> $group_mods[$i]['user_id'],
							'USERNAME'	=> $group_mods[$i]['username'],
							'REGISTER'	=> create_date('d.m.Y', $group_mods[$i]['user_regdate'], $userdata['user_timezone']),
						));
					}
				}
				else { $template->assign_block_vars('_member._no_moderators', array()); }
			
				if ( $group_nomods )
				{
					for ( $i = 0; $i < count($group_nomods); $i++ )
					{
						$template->assign_block_vars('_member._nomods_row', array(
							'USER_ID'	=> $group_nomods[$i]['user_id'],
							'USERNAME'	=> $group_nomods[$i]['username'],
							'REGISTER'	=> create_date('d.m.Y', $group_nomods[$i]['user_regdate'], $userdata['user_timezone']),
						));
					}
				}
				else { $template->assign_block_vars('_member._no_members', array()); }
					
				if ( $group_pending )
				{
					$template->assign_block_vars('_member._pending', array());
					
					for ( $i = 0; $i < count($group_pending); $i++ )
					{
						$template->assign_block_vars('_member._pending._pending_row', array(
							'USER_ID'	=> $group_pending[$i]['user_id'],
							'USERNAME'	=> $group_pending[$i]['username'],
							'REGISTER'	=> create_date('d.m.Y', $group_pending[$i]['user_regdate'], $userdata['user_timezone']),
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
				$s_action_options .= '<option value="option">&raquo; ' . $lang['common_option_select'] . '</option>';
				$s_action_options .= '<option value="_user_approve">&raquo; Antrag zustimmen</option>';
				$s_action_options .= '<option value="_user_remove">&raquo; Antrag verweigern</option>';
				$s_action_options .= '<option value="_user_change">&raquo; Gruppenrechte geben/nehmen</option>';
				
				$s_action_options .= '<option value="deluser">&raquo; ' . $lang['common_delete'] . '</option>';
				$s_action_options .= '</select>';
				
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	
				$template->assign_vars(array(
					'L_HEAD'			=> $acp_title,
					'L_INPUT'			=> sprintf($lang['sprintf_update'], $lang['group'], $data['group_name']),
					'L_MEMBER'			=> sprintf($lang['sprintf_overview'], $lang['common_members']),
					'L_OVERVIEW'		=> sprintf($lang['sprintf_right_overview'], $lang['groups']),
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['groups']),
					'L_REQUIRED'			=> $lang['required'],
					
					'L_MODERATOR'			=> $lang['common_moderators'],
					'L_NO_MODERATORS'		=> $lang['no_moderators'],
					'L_MEMBER'				=> $lang['common_members'],
					'L_NO_MEMBERS'			=> $lang['no_members'],
					'L_PENDING_MEMBER'		=> $lang['pending_members'],
					
					
					'L_ADD_MEMBER'	=> $lang['add_member'],
					
					'L_ADD'			=> $lang['add_member'],
					'L_ADD_MEMBER_EX'	=> $lang['add_member_ex'],
					
					
					
					'L_SUBMIT'				=> $lang['Submit'],
					
					'L_USERNAME'			=> $lang['username'],
					'L_REGISTER'			=> $lang['register'],
					'L_JOIN'				=> $lang['joined'],
					'L_RANK'				=> $lang['rank'],
					
					'L_MARK_ALL'			=> $lang['mark_all'],
					'L_MARK_DEALL'			=> $lang['mark_deall'],
					
					'S_ACTION_ADDUSERS'		=> $s_addusers_select,
					'S_ACTION_OPTIONS'		=> $s_action_options,
					
					'S_OVERVIEW'		=> append_sid("$file?mode=_overview"),
					'S_EDIT'			=> append_sid("$file?mode=_update&amp;$url=$data_id"),
					'S_ACTION'		=> append_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				$template->pparse('body');
			
				break;
			
			case '_user_approve':
			case '_user_deny':
			case '_user_remove':
				
			#	$group_info = get_data(GROUPS, $data_id, 1);
				$group_info = data(GROUPS, $data_id, false, 1, 1);
				
			#	$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($config['page_path']));
			#	$script_name = ( $script_name != '' ) ? $script_name . '/groups.php' : 'groups.php';
			#	$server_name = trim($config['server_name']);
			#	$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
			#	$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';
			#	$server_url = $server_protocol . $server_name . $server_port . $script_name;
				
				$members = ( $mode == '_user_approve' || $mode == '_user_deny' || $mode == '_user_remove' ) ? request('pending_members') : request('members');

				$sql_in = '';
				
				for ($i = 0; $i < count($members); $i++ )
				{
					$sql_in .= ( ( $sql_in != '' ) ? ', ' : '' ) . intval($members[$i]);
				}

				if ( $mode == '_user_approve' )
				{
					$sql = "UPDATE " . GROUPS_USERS . " SET user_pending = 0 WHERE user_id IN ($sql_in) AND group_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					for ( $k = 0; $k < count($members); $k++ )
					{
						group_set_auth($members[$k]['user_id'], $data_id);
					}
				}
				else if ( $mode == '_user_deny' || $mode == '_user_remove' )
				{
					$sql = "DELETE FROM " . GROUPS_USERS . " WHERE user_id IN ($sql_in) AND group_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					if ( $mode == '_user_remove' )
					{
						for ( $i = 0; $i < count($members); $i++ )
						{
							group_reset_auth($members[$i]['user_id'], $data_id);
						}
					}
				}

#				Mailfunktionen werden später noch eingeführt!
#				if ( $mode == 'approve' )
#				{
#					$sql_select = 'SELECT user_email FROM ' . USERS . ' WHERE user_id IN (' . $sql_in . ')';
#					if ( !($result = $db->sql_query($sql_select)) )
#					{
#						message(GENERAL_ERROR, 'Could not get user email information', '', __LINE__, __FILE__, $sql);
#					}
#
#					$bcc_list = array();
#					while ($row = $db->sql_fetchrow($result))
#					{
#						$bcc_list[] = $row['user_email'];
#					}
#
#					$group_name = $group_info['group_name'];
#
#					include($root_path . 'includes/emailer.php');
#					$emailer = new emailer($config['smtp_delivery']);
#
#					$emailer->from($config['page_email']);
#					$emailer->replyto($config['page_email']);
#
#					for ($i = 0; $i < count($bcc_list); $i++)
#					{
#						$emailer->bcc($bcc_list[$i]);
#					}
#
#					$emailer->use_template('group_approved');
#					$emailer->set_subject($lang['Group_approved']);
#
#					$emailer->assign_vars(array(
#						'SITENAME' => $config['page_name'], 
#						'NAME' => $group_name,
#						'EMAIL_SIG' => (!empty($config['board_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $config['board_email_sig']) : '', 
#
#						'U_GROUPCP' => $server_url . '?' . $url . "=$data_id")
#					);
#					$emailer->send();
#					$emailer->reset();
#				}
				
				$index = true;
			
				break;
				
			case '_user_change':
			
				debug($_POST);
				
				$members		= request('members', 4);
				$members_select	= array();

				for ( $i = 0; $i < count($members); $i++ )
				{
					if ( $members[$i] )
					{
						$members_select[] = (int) $members[$i];
					}
				}
				
				debug($members_select);
				
				if ( count($members_select) > 0 )
				{
					$user_ids = implode(', ', $members_select);
					
					$sql = 'SELECT user_id
								FROM ' . GROUPS_USERS . '
								WHERE group_id = ' . $data_id . '
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
									WHERE group_id = ' . intval($data_id) . '
										AND user_id IN (' . implode(', ', $group_mods) . ')';
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					$sql_in = ( empty($group_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $group_mods) . ')');
					
					$sql = 'UPDATE ' . GROUPS_USERS . '
								SET group_mod = 1
								WHERE group_id = ' . intval($data_id) . '
									AND user_id IN (' . implode(', ', $members_select) . ')' . $sql_in;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					$message = $lang['group_set_mod']
						. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
						. sprintf($lang['click_return_group_member'], '<a href="' . append_sid("$file?mode=_member&$url=$data_id") . '">', '</a>');
						
					message(GENERAL_MESSAGE, $message);
				}
			
				break;
				
			case '_user_add':
			
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
									AND group_id = ' . $data_id;
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
								'group_id'		=> (int) $data_id,
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
							group_set_auth($add_id_ary[$i]['user_id'], $data_id);
						}
					}

					log_add(LOG_ADMIN, $log, 'acp_group_add_member');
			
					$message = $lang['msg_group_add_member']
						. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
						. sprintf($lang['click_return_group_member'], '<a href="' . append_sid("$file?mode=member&$url=$data_id") . '">', '</a>');
					message(GENERAL_MESSAGE, $message);
				}
				
				break;
			
#			case 'deluser':
#			
#				$members = $_POST['members'];
#				
#				if (!$_POST['members'])
#				{
#					message(GENERAL_ERROR, $lang['team_no_select']);
#				}
#				
#				$sql_in = implode(", ", $members);
#				
#				$sql = "DELETE FROM " . GROUPS_USERS . " WHERE user_id IN ($sql_in) AND group_id = $data_id";
#				if ( !($result = $db->sql_query($sql)) )
#				{
#					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#				}
#				
#				$message = $lang['msg_group_del_member']
#					. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
#					. sprintf($lang['click_return_group_member'], '<a href="' . append_sid("$file?mode=member&$url=$data_id") . '">', '</a>');
#				log_add(LOG_ADMIN, $log, 'msg_group_del_member');
#				message(GENERAL_MESSAGE, $message);
#			
#				break;
			
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_groups.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'		=> $acp_title,
		'L_OVERVIEW'	=> sprintf($lang['sprintf_right_overview'], $lang['groups']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_create'], $lang['group']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['groups']),
		'L_EXPLAIN'		=> $lang['explain'],
		
		'L_MEMBER'		=> $lang['common_members'],
		'L_COUNT'		=> $lang['count'],
		
		'S_OVERVIEW'	=> append_sid("$file?mode=_overview"),
		'S_CREATE'		=> append_sid("$file?mode=_create"),
		'S_ACTION'		=> append_sid($file),
		'S_FIELDS'		=> $fields,
	));
	
	$max = get_data_max(GROUPS, 'group_order', 'group_single_user = 0');
	
	$sql = "SELECT * FROM " . GROUPS . " WHERE group_single_user = 0 ORDER BY group_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $db->sql_numrows($result) )
	{
		$type = '';
		$data = array();
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$data[$row['group_id']] = $row;
			$data[$row['group_id']]['total_members'] = 0;
		}
		$db->sql_freeresult($result);
		
		$sql = "SELECT COUNT(gu.user_id) AS total_members, gu.group_id
					FROM " . GROUPS_USERS . " gu
					WHERE gu.group_id IN (" . implode(', ', array_keys($data)) . ")
				GROUP BY gu.group_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$data[$row['group_id']]['total_members'] = $row['total_members'];
		}
		$db->sql_freeresult($result);
		
		foreach ( $data as $key => $row )
		{
			$group_id = $row['group_id'];
			
			$template->assign_block_vars('_display._row_groups', array(
				'NAME'		=> $row['group_name'],
				'COUNT'		=> $row['total_members'],
			
				'MOVE_UP'	=> ( $row['group_order'] != '10' ) ? '<a href="' . append_sid("$file?mode=_order&amp;move=-15&amp;$url=$group_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $row['group_order'] != $max['max'] ) ? '<a href="' . append_sid("$file?mode=_order&amp;move=+15&amp;$url=$group_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'DELETE'	=> ( $row['group_type'] != GROUP_SYSTEM ) ? '<a href="' . append_sid("$file?mode=_delete&amp;$url=$group_id") . '"><img src="' . $images['option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_spacer'] . '" width="16" alt="" />',
							
				'U_MEMBER'	=> append_sid("$file?mode=_member&amp;$url=$group_id"),
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$group_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_groups', array()); }
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>