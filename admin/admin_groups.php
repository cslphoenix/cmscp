<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_groups',
		'cat'		=> 'usergroups',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_groups'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_groups';
	
	include('./pagestart.php');
	
	add_lang(array('permission', 'groups'));
	acl_auth(array('a_group', 'a_group_create', 'a_group_delete', 'a_group_manage'));
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$time	= time();
	$log	= SECTION_GROUPS;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$ptype	= request('ptype', TYP);
	$smode	= request('smode', TYP);    
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_group']['path'];
	$acp_title	= sprintf($lang['stf_header'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid(($file . ($mode ? "?mode=$mode&id=$data" : '')))) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_groups.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = (in_array($mode, array('create', 'update', 'move_up', 'move_down', 'member', 'permission', 'sync', 'delete'))) ? $mode : false;
	$_tpl = ($mode == 'delete' || $smode == 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'group' => array(
					'title' => 'data_input',
					'group_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'check' => true, 'required' => 'input_name'),
					'group_desc'	=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:50'),
					'group_access'	=> array('validate' => ARY,	'explain' => false,	'type' => 'radio:access', 'params' => array(false, true, false)),
					'group_type'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array(false, true, false)),
					'group_legend'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:legend', 'params' => array(false, false, false)),
					'group_color'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:7;7', 'class' => 'minicolors'),
					'group_rank'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:rank', 'params' => RANK_PAGE),
					'group_image'	=> array('validate' => TXT,	'explain' => false,	'type' => 'upload:image', 'params' => array($dir_path, 'group')),
					'group_order'	=> 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);

			if ( $mode == 'create' && !$submit && $userauth['a_group_create'] )
			{
				$data_sql = array(
					'group_name'	=> request('group_name', TXT),
					'group_type'	=> '1',
					'group_access'	=> '1',
					'group_desc'	=> '',
					'group_color'	=> 'FFFFFF',
					'group_image'	=> '',
					'group_rank'	=> '0',
					'group_legend'	=> '0',
					'group_order'	=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(GROUPS, $data, false, 1, true);
				
				$option[] = href('a_txt', $file, array('mode' => 'member', 'id' => $data), $lang['members'], $lang['members']);
				$option[] = href('a_txt', $file, array('mode' => 'permission', 'id' => $data), $lang['permission'], $lang['permission']);
			}
			else
			{
				$data_sql = build_request(GROUPS, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'create' && $userauth['a_group_create'] )
					{
						$data_sql['group_order'] = maxa(GROUPS, 'group_order', '');
						
						$sql = sql(GROUPS, $mode, $data_sql);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else if ( $userauth['a_group'] )
					{
						$sql = sql(GROUPS, $mode, $data_sql, 'group_id', $data);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						
						update_main_id($data, false, true, true);
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output(GROUPS, $vars, $data_sql);

			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['group_name']),
				'L_EXPLAIN'	=> $lang['com_required'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
		
		case 'permission':
		
			$template->assign_block_vars('permission', array());
			
			$data_sql = data(GROUPS, $data, false, 1, true);
			$options		= array('a_', 'd_', 'g_');
			$ptype			= (isset($ptype) && $ptype != '' ? $ptype : $options[0]);
			$_action		= 'permission';
			$acl_label		= acl_label($ptype);
			$acl_auth_group = acl_auth_group($ptype);
			$acl_field_id	= acl_field($ptype, '');
			$acl_field_name = acl_field($ptype, 'name');
			$acl_label_ids	= array_keys($acl_label);
			$acl_label_data	= acl_label_data($acl_label_ids);
			
			$u_a = access(ACL_GROUPS, array('group_id', $data_sql['group_id']), 0, $acl_label_data, $acl_field_name);
			
			$forums[0] = lang($ptype . $_action);

			$s_options = '';
			
			if ( is_array($options) && count($options) > 1 )
			{
				/* änderung auf oki button drücken zum wechsel */
				$s_options .= '<select name="ptype" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
				
				foreach ( $options as $opts )
				{
					$selected = ( $opts == $ptype ) ? ' selected="selected"' : '';
					$s_options .= '<option value="' . $opts . '"' . $selected . '>' . lang("{$opts}right") . '</option>';
				}
				
				$s_options .= '</select>';
			}
			
			$forums[0] = lang($ptype . $_action);
			
			$template->assign_block_vars('permission.row', array('NAME' => $data_sql['group_name']));
				
			$row_switch = sprintf('0sg%s', $data);
			
			$template->assign_block_vars('permission.row.parent', array(
				'NAME'		=> $forums[0],
				'TOGGLE'	=> $row_switch,
				'LABEL'		=> '<label for="' . $row_switch . '">' . $lang['label'] . '</label>',
				'AUTHS'		=> 'auths0' . $data,
			));
			
			foreach ( $acl_auth_group as $cat => $rows )
			{
				$template->assign_block_vars('permission.row.parent.cats', array(
					'CAT'	=> $cat,
					'NAME'	=> $lang['tabs'][$ptype][$cat],
					'OPTIONS' => "options0{$data}{$cat}",
				));
				
				foreach ( $rows as $row )
				{
					$row_format = sprintf('%s%s[%s][%s]', $ptype, 0, $data, $row);
					
					$template->assign_block_vars('permission.row.parent.cats.auths', array(
						'OPT_NAME'	=> lang($row),
						'CSS_YES'	=> ( @$u_a[0][$data][$row] == '1' ) ? 'bggreen' : '',
						'CSS_NO'	=> ( @$u_a[0][$data][$row] != '1' ) ? 'bgred' : '',
					));
				}
			}
			
			$fields .= '<input type="hidden" name="mode" value="' .  ($mode ? $mode : $ptype) . '" />';
			$fields .= '<input type="hidden" name="id" value="' . $data . '">';
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
			$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['data_input'], $lang['data_input']);
			$option[] = href('a_txt', $file, array('mode' => 'member', 'id' => $data), $lang['members'], $lang['members']);
	
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['stf_member'], $lang['titles'], $data_sql['group_name']),
				'L_EXPLAIN'		=> $lang['explain_perm'],
			#	'L_OPTION'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['data_input'], $lang['data_input']),
				'L_OPTION'	=> implode($lang['com_bull'], $option),
			#	'L_HEADER'	=> sprintf($lang['stf_header'], $lang['user']),
			#	'L_CREATE'	=> sprintf($lang['stf_create'], $lang['user']),
			#	'L_INPUT'	=> sprintf($lang['stf_create'], $lang['user']),
			#	'L_NAME'	=> $lang['user'],
			#	'L_EXPLAIN'	=> $lang['explain'],
				'L_VIEW_AUTH'	=> $lang['common_auth'],
				
				'S_OPTIONS' => $s_options,
				'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'	=> $fields,
			));

			break;
		
		case 'delete':
		
			$data_sql = data(GROUPS, $data, false, 1, true);
			
			if ( $data && $accept && $userauth['a_group_delete'] )		
			{
				$sql = sql(GROUPS, $mode, $data_sql, 'group_id', $data);
				$grp = sql(LISTS, $mode, $data_sql, 'group_id', $data);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept && $userauth['a_group_delete'] )
			{
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['com_confirm'],
					'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['group_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}

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
			
		#	debug($diff_ary);
			/*
				diff_ary nochmal imploden und diese dann löschen dann sind alle einträge die keine gruppe haben weg!
			*/
			
			break;
			
		case 'member':
		
			switch ( $smode )
			{
				case 'create':
				case 'change':
				case 'accept':
				case 'deny':
				case 'default':
					
					$status		= request('status', TYP) ? 1 : 0 ;
					$default	= request('default', TYP) ? 1 : 0 ;
					$textarea	= request('textarea' , ARY);
					$members	= ( $smode == 'allow' || $smode == 'deny' ) ? request('pending', ARY) : request('members', ARY);
					
					if ( $members )
					{
						$ary_ids = implode(', ', $members);
						$ary_userids = $members;
					}
					else if ( $textarea )
					{
						$result = get_user_name_id($ary_ids, $textarea);
						
						if ( !sizeof($ary_ids) || $result !== false )
						{
							$error[] = $lang['error_input_user'];
						}
					}
					else
					{
						$error[] = $lang['error_select_user'];
					}
					
					if ( $smode == 'create' && $textarea && !$error )
					{
						$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN (" . implode(', ', $ary_ids) . ")";
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
						
						$add_id_ary = array_diff($ary_ids, $add_id_ary);
						
						if ( !sizeof($add_id_ary) )
						{
							$error[] = $lang['error_empty_ucreate'];
						}
						
						$sql_ary = array();
				
						foreach ( $add_id_ary as $user_id )
						{
							$sql_ary[] = array(
								'type'			=> (int) TYPE_GROUP,
								'type_id'		=> (int) $data,
								'user_id'		=> (int) $user_id,
								'user_status'	=> (int) $status,
								'time_create'	=> (int) $time,
							);
						}
					}
					
					if ( !$error )
					{
						switch ( $smode )
						{
							case 'create':
								
								foreach ( $sql_ary as $id => $_sql_ary )
								{
									$values = array();
									
									foreach ($_sql_ary as $key => $var)
									{
										$values[] = (int) $var;
									}
									
									$ary[] = '(' . implode(', ', $values) . ')';
								}
								
								$sql = 'INSERT INTO ' . LISTS . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary);
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								update_main_id($data, $ary_ids, true);
								
								$lang_type = 'update_create';
								
								break;
							
							case 'change':
								
								$ary = '';
									
								$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_status = 1 AND user_id IN ($ary_ids)";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								if ( $db->sql_numrows($result) )
								{
									while ( $row = $db->sql_fetchrow($result) )
									{
										$ary[] = $row['user_id'];
									}
								
									$sql = "UPDATE " . LISTS . " SET user_status = 0, time_update = '$time' WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN (" . implode(', ', $ary) . ")";
									if ( !$db->sql_query($sql) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
								}
								
								$sql_in = empty($ary) ? '' : ' AND NOT user_id IN (' . implode(', ', $ary) . ')';
								
								$sql = "UPDATE " . LISTS . " SET user_status = 1, time_update = '$time' WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN (" . implode(', ', $members) . ")" . $sql_in;
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								$lang_type = 'update_change';
								
								break;
								
							case 'default':
							
								update_main_id($data, $ary_userids, true);
								
								$lang_type = 'update_default';
							
								break;
							
							case 'allow':
							
								$sql = "UPDATE " . LISTS . " SET user_pending = 0, time_update = '$time' WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN ($ary_ids)";
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								$lang_type = 'update_agree';
							
								break;
								
							case 'deny':
								
								$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN ($ary_ids)";
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								$lang_type = 'update_deny';
								
								break;
						}
						
						log_add(LOG_ADMIN, $log, $mode, $lang_type);
						right('ERROR_BOX', lang($lang_type));
					}
					else
					{
						error('ERROR_BOX', $error);
					}
					
					break;
				
				case 'delete':
				
					$data_sql	= data(GROUPS, $data, false, 1, true);
					$members	= request('members', ARY);
					
					if ( $members && $data && $accept && $userauth['a_group_manage'] )
					{
						$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id = $data AND  user_id IN ($members)";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$msg = $lang['update_delete'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
								
						log_add(LOG_ADMIN, $log, $mode, 'update_delete');
						message(GENERAL_MESSAGE, $msg);
						
					#	$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN ($members)";
					#	if ( !($result = $db->sql_query($sql)) )
					#	{
					#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					#	}
						
					#	log_add(LOG_ADMIN, $log, $mode, 'update_delete');
					#	right('ERROR_BOX', 'update_delete');
					}
					else if ( $members && $data && !$accept && $userauth['a_group_manage'] )
					{
						$sql_in = implode(', ', $members);
						
						$fields .= build_fields(array(
							'mode'		=> $mode,
							'smode'		=> $smode,
							'id'		=> $data,
							'members'	=> $sql_in,
						));
						
						$sql = "SELECT user_name, user_id FROM " . USERS . " WHERE user_id IN ($sql_in)";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						$ary_user_name = array();
								
						while ($row = $db->sql_fetchrow($result))
						{
							$ary_user_name[] = (string)	$row['user_name'];
						}
						$db->sql_freeresult($result);
						
						$user_names = implode(', ', $ary_user_name);
						
						$template->assign_vars(array(
							'M_TITLE'	=> $lang['com_confirm'],
							'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], sprintf($lang['notice_confirm_group'], $user_names), $data_sql['group_name']),
		
							'S_ACTION'	=> check_sid($file),
							'S_FIELDS'	=> $fields,
						));
					}
					else
					{
						message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
					}
	
					break;
			}
			
			$template->assign_block_vars('member', array());
			
			$data_sql = data(GROUPS, $data, false, 1, true);
			
			$sql = "SELECT u.group_id, ul.user_status, ul.user_pending, ul.time_create, u.user_id, u.user_name, u.user_regdate
						FROM " . USERS . " u, " . LISTS . " ul
					WHERE ul.type_id = $data AND type = " . TYPE_GROUP . " AND ul.user_id = u.user_id
						ORDER BY ul.user_status DESC, u.user_name ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		#	$members = $db->sql_fetchrowset($result);
		
			$member = $pending = '';
			$s_pending = $s_member = array();
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				if ( $row['user_pending'] )
				{
					$pending[] = $row;
				}
				else
				{
					$member[] = $row;
				}
			}
			$db->sql_freeresult($result);
			
			if ( $member )
			{
				$cnt = count($member);
				
				for ( $i = $start; $i < min($settings['per_page_entry']['acp_userlist'] + $start, $cnt); $i++ )
				{
					$template->assign_block_vars('member.row', array(
						'ID'	=> $member[$i]['user_id'],
						'NAME'	=> $member[$i]['user_name'],
						'MOD'	=> ($member[$i]['user_status'] ? '*' : ''),
						'MAIN'	=> ($member[$i]['group_id'] == $data) ? $lang['com_yes'] : $lang['com_no'],
						'JOIN'	=> create_date($userdata['user_dateformat'], $member[$i]['time_create'], $userdata['user_timezone']),
						'REG'	=> create_date($userdata['user_dateformat'], $member[$i]['user_regdate'], $userdata['user_timezone']),
					));
				}
					
				$s_options = build_options(array(
					'smode'		=> false,
					'option'	=> array(
						'select'	=> 'com_select_option',
						'change'	=> 'notice_select_permission',
						'default'	=> 'notice_select_default',
						'delete'	=> 'com_delete',
					),
				));
				
				$current_page = !$cnt ? 1 : ceil($cnt/$settings['per_page_entry']['acp_userlist']);
			
				$template->assign_vars(array(
					'PAGE_PAGING'	=> generate_pagination("$file&mode=$mode&id=$data", $cnt, $settings['per_page_entry']['acp_userlist'], $start),
					'PAGE_NUMBER'   => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp_userlist'] ) + 1 ), $current_page ),
					
					'S_OPTIONS'	=> $s_options,
				));
			}
			else
			{
               $template->assign_block_vars('member.no_row', array());
			}
			
			if ( $pending )
			{
				$template->assign_block_vars('member.pending', array());
				
				foreach ( $pending as $row  )
				{
					$template->assign_block_vars('member.pending.row', array(
						'ID'	=> $row['user_id'],
						'NAME'	=> $row['user_name'],
						'JOIN'	=> create_date($userdata['user_dateformat'], $row['time_create'], $userdata['user_timezone']),
						'REG'	=> create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),
					));
				}
				
				$s_pending = build_options(array(
					'smode'		=> false,
					'option'	=> array(
						'select'	=> 'com_select_option',
						'allow'		=> 'request_agree',
						'udeny'		=> 'request_deny',
					),
				));
			}
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
			$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['data_input'], $lang['data_input']);
			$option[] = href('a_txt', $file, array('mode' => 'permission', 'id' => $data), $lang['permission'], $lang['permission']);
				
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['stf_member'], $lang['titles'], $data_sql['group_name']),
				'L_EXPLAIN'		=> $lang['explain_user'],
				'L_OPTION'	=> implode($lang['com_bull'], $option),
				'L_MAIN'		=> $lang['type_main'],
				'L_MODERATOR'	=> $lang['common_moderators'],
				'L_MODERATOR_NO'=> $lang['common_moderator_empty'],
				'L_MEMBER'		=> $lang['common_members'],
				'L_MEMBER_NO'	=> $lang['common_member_empty'],
				'L_PENDING'		=> $lang['pending'],
				'L_ADD'			=> $lang['com_add'],
				'L_ADD_EXPLAIN'	=> $lang['com_add_explain'],
				'L_USERNAME'	=> $lang['user_name'],
				'L_JOIN'		=> $lang['joined'],
				'L_REGISTER'	=> $lang['register'],
				
				'L_USERS'		=> $lang['members'],
				
				'L_MOD'			=> $lang['mod'],
				'L_MAIN'		=> $lang['main'],
				
				'S_OPTIONS'		=> $s_options,
				'S_PENDING'		=> $s_pending,
				
				'S_ACTION'		=> check_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			break;
			
		case 'move_up':
		case 'move_down':
		
			if ( $userauth['a_group_assort'] )
			{
				move(TEAMS, $mode, $order);
				log_add(LOG_ADMIN, $log, $mode);
			}
		
		default:
		
			$template->assign_block_vars('display', array());

			$fields = build_fields(array('mode' => 'create'));
			$sqlout = data(GROUPS, false, 'group_order ASC', 1, 3);

			$sql = "SELECT COUNT(user_id) AS total_members, type_id AS group_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id IN (" . implode(', ', array_keys($sqlout)) . ") GROUP BY type_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}

			while ( $row = $db->sql_fetchrow($result) )
			{
				$sqlout[$row['group_id']]['group_member'] = $row['total_members'];
			}
			$db->sql_freeresult($result);
			
			if ( !$sqlout )
			{
				$template->assign_block_vars('display.empty', array());
			}
			else
			{
				$max = count($sqlout);
	
				foreach ( $sqlout as $row )
				{
					$id		= $row['group_id'];
					$name	= $row['group_name'];
					$type	= $row['group_type'];
					$order	= $row['group_order'];
					
					$template->assign_block_vars('display.row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
						'COUNT'		=> ( isset($row['group_member']) ) ? $row['group_member'] : 0,
					
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
							
						'MEMBER'	=> href('a_img', $file, array('mode' => 'member', 'id' => $id), 'icon_member', 'common_member'),
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
						'DELETE'	=> ( $type != GROUP_SYSTEM ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete'),
					));
				}
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['stf_header'], $lang['titles']),
				'L_CREATE'	=> sprintf($lang['stf_create'], $lang['title']),
				
				'L_NAME'	=> $lang['titles'],
				'L_EXPLAIN'	=> $lang['explain'],
				
				'L_COUNT'	=> $lang['users_count'],
				'L_MEMBER'	=> $lang['common_members'],
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>