<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_groups'] )
	{
		$module['hm_groups']['sm_settings'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_settings_groups';
	
	include('./pagestart.php');
		
	add_lang('groups');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GROUPS;
	$url	= POST_GROUPS;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	$smode		= request('smode', 1);
	$pmode		= request('pmode', 1);
	
	$dir_path	= $root_path . $settings['path_groups'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	$auth_fields	= get_authlist();
	$auth_levels	= array('allowed', 'disallowed');
	$auth_const		= array(AUTH_ALLOWED, AUTH_DISALLOWED);
	
	function select_userid($username)
	{
		global $db;
		
		$sql = "SELECT user_id FROM " . USERS . " WHERE user_name = '$username'";
		if ( !($result = $db->sql_query($sql)) )
		{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp = $db->sql_fetchrow($result);
		$msg = $tmp['user_id'];
		
		return $msg;
	}
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_groups'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_groups.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', '_member', '_order', 'delete', '_overview')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
		case 'update':
			
				$template->assign_block_vars('input', array());
				$template->assign_block_vars('input.' . $mode, array());
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					$data = array(
						'group_name'	=> request('group_name', 2),
						'group_type'	=> '1',
						'group_access'	=> '1',
						'group_desc'	=> '',
						'group_color'	=> 'FFFFFF',
						'group_image'	=> '',
						'group_rank'	=> '0',
						'group_legend'	=> '0',
						'group_order'	=> '',
					);
					
					for ( $i = 0; $i < count($auth_fields); $i++ )
					{
						$data[$auth_fields[$i]] = '0';
					}
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(GROUPS, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
						'group_name'	=> request('group_name', 2),
						'group_type'	=> request('group_type', 0),
						'group_access'	=> request('group_access', 2),
						'group_desc'	=> request('group_desc', 2),
						'group_color'	=> request('group_color', 2),
						'group_image'	=> request('group_image', 2),
						'group_rank'	=> request('rank_id', 0),
						'group_legend'	=> request('group_legend', 0),
						'group_order'	=> request('group_order', 0) ? request('group_order', 0) : request('group_order_new', 0),
					);
					
					for ( $i = 0; $i < count($auth_fields); $i++ )
					{
						$value = request($auth_fields[$i], INT);
						$tmp[$auth_fields[$i]] = $value;
					}
					
					$data['auth_data'] = serialize($tmp);
				#	for ( $i = 0; $i < count($auth_fields); $i++ )
				#	{
				#		$data[$auth_fields[$i]] = request($auth_fields[$i], INT);
				#	}
				}
				
				$user_id = ( $mode == 'create' ) ? request('user_id', 2) : '';
				
				( $data['group_image'] ) ? $template->assign_block_vars('input._image', array()) : false;
				
				
					
				$s_access = "<select class=\"post\" name=\"group_access\" id=\"group_access\">";
				$s_access .= "<optgroup label=\"" . sprintf($lang['sprintf_select_format'], $lang['msg_select_user_level']) . "\">";
				
				foreach ( $lang['group_access_ary'] as $level => $name )
				{
					$selected	= ( $data['group_access'] == $level ) ? 'selected="selected"' : "";
				#	$protect	= ( $data['group_type'] == GROUP_SYSTEM ) ? 'disabled="disabled"' : "";
					$s_access	.= "<option value=\"$level\" $selected>" . sprintf($lang['sprintf_select_format'], $name) . "</option>";
				}
				
				$s_access .= "</optgroup></select>";
				
				$s_type = "<select class=\"post\" name=\"group_type\" id=\"group_type\">";
				$s_type .= "<optgroup label=\"" . sprintf($lang['sprintf_select_format'], $lang['msg_select_level']) . "\">";
				
				foreach ( $lang['group_type_ary'] as $level => $name )
				{
					$selected	= ( $data['group_type'] == $level ) ? 'selected="selected"' : "";
				#	$protect	= ( $data['group_type'] == GROUP_SYSTEM || $level == GROUP_SYSTEM ) ? 'disabled="disabled"' : "";
					$protect	= ( $level == GROUP_SYSTEM ) ? 'disabled="disabled"' : "";
					$s_type		.= "<option value=\"$level\" $selected $protect>" . sprintf($lang['sprintf_select_format'], $name) . "</option>";
				}
				
				$s_type .= "</optgroup></select>";
				
				if ( $data['group_type'] == GROUP_SYSTEM )
				{
					$s_access = $lang['auth_user'];
					$s_type	= $lang['group_system'];
					
					$fields .= "<input type=\"hidden\" name=\"group_type\" value=\"" . GROUP_SYSTEM . "\" />";
					$fields .= "<input type=\"hidden\" name=\"group_access\" value=\"" . USER . "\" />";
				}
				
			#	debug($data['auth_data']);
				
				$gauth = unserialize($data['auth_data']);
				
				debug($gauth);
				
				for ( $j = 0; $j < count($gauth); $j++ )
				{
					if ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $data['group_access'] )
					{
						$selected_yes	= ( $gauth[$auth_fields[$j]] == $auth_const[0] ) ? 'checked="checked"' : '';
						$selected_no	= ( $gauth[$auth_fields[$j]] == $auth_const[1] ) ? 'checked="checked"' : '';
					}
					else if ( $userdata['user_level'] <= $data['group_access'] )
					{
						$selected_yes	= ( $gauth[$auth_fields[$j]] == $auth_const[0] ) ? 'checked="checked" disabled' : 'disabled';
						$selected_no	= ( $gauth[$auth_fields[$j]] == $auth_const[1] ) ? 'checked="checked" disabled' : 'disabled';
					}
					
					$select[$j] = "<label><input type=\"radio\" name=\"" . $auth_fields[$j] . "\" id=\"" . $auth_fields[$j] . "\" value=\"1\" $selected_yes>&nbsp;" . $lang['group_' . $auth_levels[0]] . "</label><span style=\"padding:4px;\"></span>";
					$select[$j] .= "<label><input type=\"radio\" name=\"" . $auth_fields[$j] . "\" id=\"deactivated\" value=\"0\" $selected_no>&nbsp;" . $lang['group_' . $auth_levels[1]] . "</label>";
					
					$template->assign_block_vars('input._auth', array(
						'TITLE'		=> $lang['auths'][$auth_fields[$j]],
						'FIELDS'	=> $auth_fields[$j],
						'SELECT'	=> $select[$j],
					));
					
					$template->assign_block_vars('auth', array('FIELDS' => $auth_fields[$j]));
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"current_color\" value=\"" . $data['group_color'] . "\" />";
				$fields .= "<input type=\"hidden\" name=\"current_image\" value=\"" . $data['group_image'] . "\" />";
				$fields .= "<input type=\"hidden\" name=\"current_access\" value=\"" . $data['group_access'] . "\" />";				
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['titles']),
					'L_INPUT'		=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['group_name']),
					'L_VIEWMEMBER'	=> sprintf($lang['sprintf_overview'], $lang['common_members']),
					
					'L_AUTH'		=> $lang['auth'],
					'L_DATA'		=> $lang['data'],
					'L_IMAGE'		=> $lang['image'],
					
					'L_NAME'		=> $lang['group_name'],
					'L_DESC'		=> $lang['group_desc'],
					'L_MOD'			=> $lang['group_mod'],
					'L_ACCESS'		=> $lang['group_access'],
					'L_TYPE'		=> $lang['group_type'],
					'L_LEGEND'		=> $lang['group_legend'],
					'L_COLOR'		=> $lang['group_color'],
					'L_RANK'		=> $lang['group_rank'],
					'L_ORDER'		=> $lang['common_order'],
					'L_IMAGE_UPLOAD'	=> $lang['common_image_upload'],
					'L_IMAGE_CURRENT'	=> $lang['common_image_current'],
					'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
					
					'NAME'			=> $data['group_name'],
					'DESC'			=> $data['group_desc'],
					'COLOR'			=> $data['group_color'],
					'IMAGE'			=> $dir_path . $data['group_image'],
					
					'MOD'			=> $user_id,
					
					'S_TYPE'		=> $s_type,
					'S_ACCESS'		=> $s_access,
					'S_LEGEND_YES'	=> ( $data['group_legend'] ) ? ' checked="checked"' : '',
					'S_LEGEND_NO'	=> ( !$data['group_legend'] ) ? ' checked="checked"' : '',
					
					'S_RANK'		=> select_box(RANKS, $data['group_rank'], 2),
				#	'S_ORDER'		=> select_order('select', GROUPS, 'group', '', '', $data['group_order']),
					'S_ORDER'		=> simple_order(GROUPS, '', 'select', $data['group_order']),
					
					'S_OVERVIEW'	=> check_sid("$file?mode=_overview"),
					'S_MEMBER'		=> check_sid("$file?mode=_member&amp;$url=$data_id"),
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', TXT) )
				{
			#		if ( $group_img )
			#		{
			#			$sql_pic = image_upload($mode, 'image_group', 'group_image', '', $group_image, '', $dir_path, $group_img['temp'], $group_img['name'], $group_img['size'], $group_img['type'], $error);
			#		}
			#		else
			#		{
			#			$sql_pic = '';
			#		}
					
					$error .= ( !$data['group_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$user_id && $mode == 'create' ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_users'] : '';
					
					if ( !$error )
					{
						$data['group_order'] = ( !$data['group_order'] ) ? maxa(GROUPS, 'group_order', '') : $data['group_order'];
						
						if ( $mode == 'create' )
						{
							$user_id = select_userid(request('user_id', 2));
							
						#	for ( $i = 0; $i < count($auth_fields); $i++ )
						#	{
						#		$data[$auth_fields[$i]] = request($auth_fields[$i], INT);
						#	}
							
							$sql = sql(GROUPS, $mode, $data);
							$gid = $db->sql_nextid();
							$grp = sql(LISTS, $mode, array('user_id' => $user_id, 'type' => TYPE_GROUP, 'type_id' => $gid, 'user_status' => 1));
							
							group_set_auth($user_id, $gid);
							
							$tmp = 'narf';
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
						#	for ( $i = 0; $i < count($auth_fields); $i++ )
						#	{
						#		$data[$auth_fields[$i]] = request($auth_fields[$i], INT);
						#	}
							
						#	if ( request('network_image_delete') )
						#	{
						#		$sql_pic = image_delete($data['group_image'], '', $root_path . $settings['path_groups'] . '/', 'group_image');
						#	}
							
						#	if ( $group_image )
						#	{
						#		$sql_pic = image_upload($mode, 'image_group', 'group_image', '', $data['group_image'], '', $root_path . $settings['path_groups'] . '/', $group_image['temp'], $group_image['name'], $group_image['size'], $group_image['type']);
						#	}
						#	
						#	else
						#	{
						#		$sql_pic = '';
						#	}
						
							$tmp = sql(GROUPS, $mode, $data, 'group_id', $data_id);
							
							if ( $data['group_color'] != request('current_color', 1) )
							{
								$sql = "SELECT user_id FROM " . USERS . " WHERE user_color = '#" . request('current_color', 1) . "'";
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
									
									$sql = "UPDATE " . USERS . " SET user_color = '#" . $data['group_color'] . "' WHERE user_id IN ($user_in)";
									if ( !($result = $db->sql_query($sql)) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
								}
							}
							
							if ( $data['group_access'] != request('current_access', 1) )
							{
								$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id = $data_id";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$users = $db->sql_fetchrowset($result);
								
								for ( $i = 0; $i < count($users); $i++ )
								{
									if ( $data['group_access'] > request('current_access', 1) )
									{
										group_set_auth($users[$i]['user_id'], $data_id);
									}
									else
									{
										group_reset_auth($users[$i]['user_id'], $data_id);
									}
								}
							}
							
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $tmp);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
			
				$template->pparse('body');
				
				break;
			
			case 'member':
			
				$template->assign_block_vars('member', array());
				
				$data = data(GROUPS, $data_id, false, 1, true);
				
				$sql = "SELECT ul.user_status, ul.user_pending, u.user_id, u.user_name, u.user_regdate
							FROM " . USERS . " u, " . LISTS . " ul
						WHERE ul.type_id = $data_id AND type = " . TYPE_GROUP . " AND ul.user_id = u.user_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$members = $db->sql_fetchrowset($result);
				
				$grp_mod = array();
				$grp_mem = array();
				$grp_pen = array();
				
				$ids = '';
				$sql_id = '';
				$s_users = '';
				$s_options = '';
				$s_pending = '';
				
				if ( $members )
				{
					foreach ( $members as $key => $row )
					{
						if ( $row['user_status'] )
						{
							$grp_mod[] = $row;
						}
						else if ( $row['user_pending'] )
						{
							$grp_pen[] = $row;
						}
						else
						{
							$grp_mem[] = $row;
						}
					}
					
					foreach ( $members as $member )
					{
						$ids[] = $member['user_id'];
					}
					
					$sql_id = " AND NOT user_id IN (" . implode(', ', $ids) . ")";
					
					$s_options .= '<select class="postselect" name="smode">';
					$s_options .= '<option value="">' . sprintf($lang['sprintf_select_format'], $lang['common_option_select']) . '</option>';
					$s_options .= '<option value="_change">' . sprintf($lang['sprintf_select_format'], $lang['change']) . '</option>';
					$s_options .= '<option value="_delete">' . sprintf($lang['sprintf_select_format'], $lang['common_delete']) . '</option>';
					$s_options .= '</select>';
				}
				
				if ( $grp_mod )
				{
					for ( $i = 0; $i < count($grp_mod); $i++ )
					{
						$template->assign_block_vars('member._mod_row', array(
							'USER_ID'	=> $grp_mod[$i]['user_id'],
							'USERNAME'	=> $grp_mod[$i]['user_name'],
							'REGISTER'	=> create_date('d.m.Y', $grp_mod[$i]['user_regdate'], $userdata['user_timezone']),
						));
					}
				}
				else
				{
					$template->assign_block_vars('member._mod_no', array());
				}
				
				if ( $grp_mem )
				{
					for ( $i = 0; $i < count($grp_mem); $i++ )
					{
						$template->assign_block_vars('member._mem_row', array(
							'USER_ID'	=> $grp_mem[$i]['user_id'],
							'USERNAME'	=> $grp_mem[$i]['user_name'],
							'REGISTER'	=> create_date('d.m.Y', $grp_mem[$i]['user_regdate'], $userdata['user_timezone']),
						));
					}
				}
				else
				{
					$template->assign_block_vars('member._mem_no', array());
				}
					
				if ( $grp_pen )
				{
					$template->assign_block_vars('member._pending', array());
					
					for ( $i = 0; $i < count($grp_pen); $i++ )
					{
						$template->assign_block_vars('member._pending._pending_row', array(
							'USER_ID'	=> $grp_pen[$i]['user_id'],
							'USERNAME'	=> $grp_pen[$i]['user_name'],
							'REGISTER'	=> create_date('d.m.Y', $grp_pen[$i]['user_regdate'], $userdata['user_timezone']),
						));
					}
					
					$s_pending .= '<select class="postselect" name="pmode">';
					$s_pending .= '<option value="" selected="selected">' . sprintf($lang['sprintf_select_format'], $lang['common_option_select']) . '</option>';
					$s_pending .= '<option value="_agree">' . sprintf($lang['sprintf_select_format'], $lang['request_agree']) . '</option>';
					$s_pending .= '<option value="_deny">' . sprintf($lang['sprintf_select_format'], $lang['request_deny']) . '</option>';
					$s_pending .= '</select>';
				}
				
				$sql = "SELECT user_name, user_id FROM " . USERS . " WHERE user_id <> " . ANONYMOUS . $sql_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$users = $db->sql_fetchrowset($result);
					
				if ( $users )
				{
					$template->assign_block_vars('member._add', array());
						
					$s_users .= '<select class="select" name="members[]" rows="6" multiple="multiple">';
					
					for ( $i = 0; $i < count($users); $i++ )
					{
						$s_users .= '<option value="' . $users[$i]['user_id'] . '">' . sprintf($lang['sprintf_select_format'], $users[$i]['user_name']) . '</option>';
					}
					
					$s_users .= '</select>';
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['titles']),
					'L_INPUT'			=> sprintf($lang['sprintf_update'], $lang['title'], $data['group_name']),
					'L_VIEWMEMBER'		=> sprintf($lang['sprintf_overview'], $lang['common_members']),
					
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['titles']),
					
					'L_MODERATOR'	=> $lang['common_moderators'],
					'L_MODERATOR_NO'=> $lang['common_moderator_empty'],
					'L_MEMBER'		=> $lang['common_members'],
					'L_MEMBER_NO'	=> $lang['common_member_empty'],
					'L_PENDING'		=> $lang['pending_members'],
					
				#	'L_ADD'			=> $lang['add_member'],
				#	'L_ADD_MEMBER'	=> $lang['add_member'],
				#	'L_ADD_MEMBER_EX'	=> $lang['add_member_ex'],
					
					'L_USERNAME'	=> $lang['user_name'],
					'L_REGISTER'	=> $lang['register'],
										
					'S_USERS'		=> $s_users,
					'S_OPTIONS'		=> $s_options,
					'S_PENDING'		=> $s_pending,
					
					'S_UPDATE'		=> check_sid("$file?mode=_update&amp;$url=$data_id"),
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( $smode == '_add' || $smode == '_change' || $smode == 'delete' || $pmode == '_agree' || $pmode == '_deny' )
				{
					$text		= request('textarea' , 2);
					$status		= request('mod') ? 1 : 0 ;
					$members	= ( $pmode == '_agree' || $pmode == '_deny' ) ? request('pending_members', 4) : request('members', 4);
					
					if ( $members )
					{
						$_ary_ids = implode(', ', $members);
						
						$_ary_userid = $members;
					}
					else if ( $text )
					{
						$members = trim($text, ',');
						
						$user_name_ary = array_unique(explode(', ', $members));
						
						$which_ary = 'user_name_ary';
						
						if ( $$which_ary && !is_array($$which_ary) )
						{
							$$which_ary = array($$which_ary);
						}
						
						$sql_in = $$which_ary;
						unset($$which_ary);
						
						$sql_in = implode("', '", $sql_in);
						
						$user_id_ary = $user_name_ary = array();
						
						$sql = "SELECT user_id FROM " . USERS . " WHERE LOWER(user_name) IN ('" . strtolower($sql_in) . "')";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_MESSAGE, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						$row = $db->sql_fetchrowset($result);
						
						if ( $row )
						{
							for ( $i = 0; $i < count($row); $i++ )
							{
								$_ary_userid[] = $row[$i]['user_id'];
							}
							
							$_ary_ids = implode(', ', $_ary_userid);
						}
						else
						{
							$error = $lang['msg_empty_add'];
						}
					}
					else
					{
						$error .= ( $error ? '<br />' : '' ) . $lang['msg_select_users'];
					}
					
					if ( $smode == '_add' && ($text || $members) )
					{
						$sql = "SELECT user_id FROM " . LISTS . " WHERE user_id IN ($_ary_ids) AND type = " . TYPE_GROUP . " AND type_id = $data_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$add_id_ary = array();
						
						while ($row = $db->sql_fetchrow($result))
						{
							$add_id_ary[] = (int) $row['user_id'];
						}
						$db->sql_freeresult($result);
						
						$add_id_ary = array_diff($_ary_userid, $add_id_ary);
						
						if ( !sizeof($add_id_ary) )
						{
							$error = $lang['msg_empty_add'];
						}
						
						$sql_ary = array();
				
						foreach ( $add_id_ary as $user_id )
						{
							$sql_ary[] = array(
								'user_id'		=> $user_id,
								'type'			=> TYPE_GROUP,
								'type_id'		=> $data_id,
								'user_status'	=> $status,
							);
						}
				
						if ( !sizeof($sql_ary) )
						{
							$error = $lang['msg_empty_add'];
						}
					}
					
					if ( !$error )
					{
						if ( $smode == '_add' )
						{
							foreach ( $sql_ary as $id => $_sql_ary )
							{
								$values = array();
								
								foreach ($_sql_ary as $key => $var)
								{
									$values[] = intval($var);
								}
								
								$ary[] = '(' . implode(', ', $values) . ')';
							}
				
							
							$sql = 'INSERT INTO ' . LISTS . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary);
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							for( $i = 0; $i < count($sql_ary); $i++ )
							{
								group_set_auth($sql_ary[$i]['user_id'], $data_id);
							}
							
							$lang_type = 'update_add';
						}
						else if ( $smode == '_change' )
						{
							if ( count($members) > 0 )
							{
								$_ary = '';
								
								$sql = "SELECT user_id FROM " . LISTS . " WHERE type_id = $data_id AND user_status = 1 AND type = " . TYPE_GROUP . " AND user_id IN ($_ary_ids)";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$users = $db->sql_fetchrowset($result);
								
								if ( $users )
								{
									foreach ( $users as $value )
									{
										$_ary[] = $value['user_id'];
									}
								
									if ( count($_ary) > 0 )
									{
										$sql = "UPDATE " . LISTS . " SET user_status = 0 WHERE type_id = $data_id AND type = " . TYPE_GROUP . " AND user_id IN (" . implode(', ', $_ary) . ")";
										if ( !($result = $db->sql_query($sql)) )
										{
											message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
										}
									}
								}
								
								$sql_in = empty($_ary ) ? '' : ' AND NOT user_id IN (' . implode(', ', $_ary) . ')';
								
								$sql = "UPDATE " . LISTS . " SET user_status = 1 WHERE type_id = $data_id AND type = " . TYPE_GROUP . " AND user_id IN (" . implode(', ', $members) . ")" . $sql_in;
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								$lang_type = 'update_change';
							}
						}
						else if ( $smode == 'delete' )
						{
							$sql = "DELETE FROM " . LISTS . " WHERE user_id IN ($_ary_ids) AND type = " . TYPE_GROUP . " AND type_id = $data_id";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							for ( $i = 0; $i < count($members); $i++ )
							{
								group_reset_auth($members[$i], $data_id);
							}
							
							$lang_type = 'update_delete';
						}
						
						switch ( $pmode )
						{
							case 'agree':
							
								$sql = "UPDATE " . LISTS . " SET user_pending = 0 WHERE type = " . TYPE_GROUP . " AND user_id IN ($_ary_ids) AND type_id = $data_id";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								for ( $k = 0; $k < count($members); $k++ )
								{
									group_set_auth($members[$k]['user_id'], $data_id);
								}
								
								$lang_type = 'update_agree';
							
								break;
								
							case 'deny':
								
								$sql = "DELETE FROM " . LISTS . " WHERE user_id IN ($_ary_ids) AND type = " . TYPE_GROUP . " AND type_id = $data_id";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								$lang_type = 'update_deny';
								
								break;
						}
						
						$msg = $lang[$lang_type] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data_id"));
						
						log_add(LOG_ADMIN, $log, ($smode) ? $smode : $pmode, $lang_type);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);

						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
				
				$template->pparse('body');
			
				break;
			
			case 'overview':
			
				$template->set_filenames(array('body' => 'style/acp_groups.tpl'));
				$template->assign_block_vars('overview', array());
				
				$sql = "SELECT * FROM " . GROUPS . " ORDER BY group_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$groups_data = $db->sql_fetchrowset($result);
				
				$sql = "SELECT * FROM " . GROUPS . " ORDER BY group_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$grps = $db->sql_fetchrowset($result);
				
				for ( $i = 0; $i < count($grps); $i++ )
				{
					$template->assign_block_vars('overview._grp_name', array(
						'NAME' => $grps[$i]['group_name'],
					));
				}
				
				$auth = data(AUTHLIST, false, 'authlist_name ASC', 0, false);
				
				for ( $j = 0; $j < count($auth); $j++ )
				{
					$template->assign_block_vars('overview._grp_auth', array(
						'NAME' => $lang[$auth[$j]['authlist_name']],
					));
					
					for ( $k = $start; $k < min(5 + $start, count($grps)); $k++ )
					{
						$name_yes	= $grps[$k]['group_id'] . "[" . $auth[$j]['authlist_name'] . "]";
						$name_no	= $grps[$k]['group_id'] . "[" . $auth[$j]['authlist_name'] . "]";
						
						$auth_yes	= $lang['group_' . $auth_levels[0]];
						$auth_no	= $lang['group_' . $auth_levels[1]];
						
						$mark_yes	= ( $grps[$k][$auth[$j]['authlist_name']] == $auth_const[0] ) ? ' checked="checked"' : '';
						$mark_no	= ( $grps[$k][$auth[$j]['authlist_name']] == $auth_const[1] ) ? ' checked="checked"' : '';
						
						$custom_auth[$k] = '';
						$custom_auth[$k] .= "<label><input type=\"radio\" name=\"$name_yes\" value=\"1\"$mark_yes>&nbsp;$auth_yes</label><span style=\"padding:4px;\"></span>";
						$custom_auth[$k] .= "<label><input type=\"radio\" name=\"$name_no\" value=\"0\"$mark_no>&nbsp;$auth_no</label>";

					#	$custom_auth[$k] = ( $grps[$k][$auth[$j]['authlist_name']] == $auth_const[0] ) ? $lang['group_' . $auth_levels[0]] : $lang['group_' . $auth_levels[1]];
						
						$template->assign_block_vars('overview._grp_auth._auth', array(
							'INFO' => $custom_auth[$k],
						));
					}
				}
				
				$count = count($grps);
				$colspan = count($grps)+1;
				
				$current_page = ( !$count ) ? 1 : ceil($count/5);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				
				$template->assign_vars(array(
					'L_HEAD'				=> $acp_title,
					'L_CREATE'				=> sprintf($lang['sprintf_create'], $lang['title']),
					'L_OVERVIEW'			=> sprintf($lang['sprintf_right_overview'], $lang['titles']),
					'L_OVERVIEW_EXPLAIN'	=> $lang['explain_o'],
					
					'COLSPAN'		=> $colspan,
					
					'PAGE_NUMBER'	=> $count ? sprintf($lang['common_page_of'], ( floor( $start / 5 ) + 1 ), $current_page ) : '',
					'PAGE_PAGING'	=> $count ? generate_pagination("$file?mode=$mode", $count, 5, $start ) : '',
					
					'S_CREATE'	=> check_sid("$file?mode=_create"),
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', TXT) )
				{
					for ( $i = 0; $i < count($grps); $i++ )
					{
						$request = request($grps[$i]['group_id'], 4);
						
						$sql = sql(GROUPS, 'update', $request, 'group_id', $grps[$i]['group_id']);
					}
				}
				
				$template->pparse('body');
			
				break;
			
			case 'order':
			
				update(GROUPS, 'group', $move, $data_id);
				orders(GROUPS, '0');
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;

				break;
								
			case 'delete':
			
				$data = data(GROUPS, $data_id, false, 1, true);
			
				if ( $data_id && $confirm )
				{
					$sql = sql(GROUPS, $mode, $data, 'group_id', $data_id);
					$grp = sql(LISTS, $mode, $data, 'group_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
					
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['group_name']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}
				
				$template->pparse('confirm');
				
				break;
				
			case 'sync':
				
				/*
					07.05 kleiner sync test, habe in der benutzergruppenbenutzerliste
					einen eintrag gesehen wo keine group_id vergeben war! was ja nicht
					sein kann und darf, ergo nur mal zur überprüfung
					
					im moment nur eine spielerrei, soll aber für das komplette sync helfen!
				*/
				$grps = data(GROUPS, false, false, 1, false);
				$grpu = data(LISTS, false, false, 1, false);
				$user = data(USERS, false, false, 1, false);
				
				foreach ( $grps as $key => $row )
				{
					$grps_ary[] = $row['group_id'];
				}
				
				foreach ( $user as $key => $row )
				{
					$user_ary[] = $row['user_id'];
				}
				
				$count_delete = 0;
				
				for ( $i = 0; $i < count($grpu); $i++ )
				{
					if ( !$grpu[$i]['group_id'] )
					{
						$delete[] = $grpu[$i]['group_user_id'];
						$count_delete++;
					}
					else
					{					
						for ( $j = 0; $j < count($user_ary); $j++ )
						{
							if ( $grpu[$i]['user_id'] == $user_ary[$j] )
							{
								$hit_ary[$grpu[$i]['group_id']] = $user_ary[$j];
							}
						}
					}
				}
				
				$diff_ary = array_diff($delete, $hit_ary);
				
				debug($diff_ary);
				/*
					diff_ary nochmal imploden und diese dann löschen dann sind alle einträge die keine gruppe haben weg!
				*/
				
				$index = true;
			
				break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
	
	$fields	= '<input type="hidden" name="mode" value="_create" />';
	$max	= maxi(GROUPS, 'group_order', '');
	
	$sql = "SELECT * FROM " . GROUPS . " ORDER BY group_order";
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
		
		$sql = "SELECT COUNT(user_id) AS total_members, type_id AS group_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id IN (" . implode(', ', array_keys($data)) . ") GROUP BY type_id";
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
			$id		= $row['group_id'];
			$name	= $row['group_name'];
			$type	= $row['group_type'];
			$order	= $row['group_order'];
			
			$template->assign_block_vars('display.row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, $name),
				
				'COUNT'		=> $row['total_members'],
			
				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'MEMBER'	=> href('a_img', $file, array('mode' => '_member', $url => $id), 'icon_member', 'common_member'),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> ( $type != GROUP_SYSTEM ) ? href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
			));
		}
	}
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['titles']),
		'L_OVERVIEW'	=> sprintf($lang['sprintf_right_overview'], $lang['titles']),
		'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['title']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['titles']),
		'L_EXPLAIN'		=> $lang['explain'],
		
		'L_MEMBER'		=> $lang['common_members'],
		'L_COUNT'		=> $lang['count'],
		
		'S_OVERVIEW'	=> check_sid("$file?mode=_overview"),
		'S_CREATE'		=> check_sid("$file?mode=_create"),
		'S_ACTION'		=> check_sid($file),
		'S_FIELDS'		=> $fields,
	));
		
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>