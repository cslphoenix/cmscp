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
	$c_user	= ( isset($_POST['c_user']) ) ? true : false;
	
	$current = 'acp_groups';
	
	include('./pagestart.php');
	
	add_lang('groups');
	acl_auth(array('a_group', 'a_group_create', 'a_group_delete', 'a_group_manage'));
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GROUPS;
	$time	= time();
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$smode	= request('smode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$mode .= $smode;
	
	$dir_path	= $root_path . $settings['path_group']['path'];
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file)) : false;
	( $c_user )	? redirect('admin/' . check_sid("$file&id=$data")) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_groups.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	debug($_POST, '_POST');
#	debug($_FILES, '_FILES');
	
#	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down', 'ucreate', 'uchange', 'udelete'))) ? $mode : false;
	
	if ( $mode )
	{
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

				if ( $mode == 'create' && !$submit )
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
					
					$template->assign_vars(array('L_OPTION' => href('a_txt', $file, array('id' => $data), $lang['members'], $lang['members'])));
				}
				else
				{
					$data_sql = build_request(GROUPS, $vars, $error, $mode);
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$data_sql['group_order'] = maxa(GROUPS, 'group_order', '');
							
							$sql = sql(GROUPS, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
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
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['group_name']),
					'L_EXPLAIN'	=> $lang['com_required'],
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
				
			case 'move_up':
			case 'move_down':
			
				move(GROUPS, $mode, $order);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
			
			case 'delete':
			
				$data_sql = data(GROUPS, $data, false, 1, true);
			
				if ( $data && $confirm )
				{
					$sql = sql(GROUPS, $mode, $data_sql, 'group_id', $data);
					$grp = sql(LISTS, $mode, $data_sql, 'group_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
					
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['group_name']),
						
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
				
			case 'ucreate':
			case 'uchange':
			case 'uaccept':
			case 'udeny':
			case 'udefault':
				
				$status		= request('status', TYP) ? 1 : 0 ;
				$default	= request('default', TYP) ? 1 : 0 ;
				$textarea	= request('textarea' , ARY);
				$members	= ( $mode == 'uaccept' || $mode == 'udeny' ) ? request('pending', ARY) : request('members', ARY);
				
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
				
			#	debug($ary_ids, 'ary_ids 2');
			#	debug($_ary_userid, '_ary_userid');
				
				if ( $mode == 'ucreate' && $textarea && !$error )
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
					switch ( $mode )
					{
						case 'ucreate':
							
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
						
						case 'uchange':
							
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
							
						case 'udefault':
						
							update_main_id($data, $ary_userids, true);
							
							$lang_type = 'update_default';
						
							break;
						
						case 'uaccept':
						
							$sql = "UPDATE " . LISTS . " SET user_pending = 0, time_update = '$time' WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN ($ary_ids)";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$lang_type = 'update_agree';
						
							break;
							
						case 'udeny':
							
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
				
				$index = true;
			
				break;
				
			case 'udelete':
			
				$data_sql	= data(GROUPS, $data, false, 1, true);
				$members	= request('members', ARY);
							
				if ( $members && $accept )
				{
					$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN ($members)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$members = explode(', ', $members);
					
					log_add(LOG_ADMIN, $log, $mode, 'update_delete');
					right('ERROR_BOX', lang('update_delete'));
					
					#$oCache -> deleteCache('list_teams');
					#$oCache -> deleteCache('subnavi_list_teams');
										
					$index = true;
				}
				else if ( $members && !$accept )
				{
					$template->set_filenames(array('body' => 'style/info_confirm_user.tpl'));

					$sql_in = implode(', ', $members);
					
					$fields .= '<input type="hidden" name="mode" value="udelete" />';
					$fields .= '<input type="hidden" name="id" value="' . $data . '" />';
					$fields .= '<input type="hidden" name="members" value="' . $sql_in . '" />';
					
					$sql = "SELECT user_name, user_id FROM " . USERS . " WHERE user_id IN ($sql_in)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$ary_userid = $ary_user_name = array();
							
					while ($row = $db->sql_fetchrow($result))
					{
						$ary_userid[]		= (int)		$row['user_id'];
						$ary_user_name[]	= (string)	$row['user_name'];
					}
					$db->sql_freeresult($result);
					
					$user_names = implode(', ', $ary_user_name);

					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['com_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['notice_confirm_delete'], sprintf($lang['notice_confirm_group'], $user_names), $data_sql['group_name']),
					
						'S_FIELDS'		=> $fields,
						'S_ACTION'		=> check_sid($file),
					));
				}
				
				$template->pparse('body');
			
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
				
				$index = true;
			
				break;
		}
	
		if ( $index != true )
		{
			acp_footer();
			exit;
		}
	}
	
	if ( !$data )
	{
		$template->assign_block_vars('display', array());
		
		$fields .= '<input type="hidden" name="mode" value="create" />';
		
		$sql = "SELECT * FROM " . GROUPS . " ORDER BY group_order";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		if ( $db->sql_numrows($result) )
		{
			$type = '';
			$data_sql = array();
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$data_sql[$row['group_id']] = $row;
				$data_sql[$row['group_id']]['total_members'] = 0;
			}
			$db->sql_freeresult($result);
			
			$sql = "SELECT COUNT(user_id) AS total_members, type_id AS group_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id IN (" . implode(', ', array_keys($data_sql)) . ") GROUP BY type_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$data_sql[$row['group_id']]['total_members'] = $row['total_members'];
			}
			$db->sql_freeresult($result);
			
			$max = count($data_sql);
			
			foreach ( $data_sql as $key => $row )
			{
				$id		= $row['group_id'];
				$name	= $row['group_name'];
				$type	= $row['group_type'];
				$order	= $row['group_order'];
				
				$template->assign_block_vars('display.row', array(
					'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
					
					'COUNT'		=> $row['total_members'],
				
					'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
						
					'MEMBER'	=> href('a_img', $file, array('id' => $id), 'icon_member', 'common_member'),
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'	=> ( $type != GROUP_SYSTEM ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete'),
				));
			}
		}
		
		$template->assign_vars(array(
			'L_HEAD'		=> sprintf($lang['stf_head'], $lang['titles']),
			'L_CREATE'		=> sprintf($lang['stf_create'], $lang['title']),
			'L_NAME'		=> $lang['titles'],
			'L_EXPLAIN'		=> $lang['explain'],
			
			'L_MEMBER'		=> $lang['common_members'],
			'L_COUNT'		=> $lang['users_count'],
			
			'S_ACTION'		=> check_sid($file),
			'S_FIELDS'		=> $fields,
		));
	}
	else
	{
		$template->assign_block_vars('member', array());
				
		$data_sql = data(GROUPS, $data, false, 1, true);
		
		$sql = "SELECT u.group_id, ul.user_status, ul.user_pending, ul.time_create, u.user_id, u.user_name, u.user_regdate
					FROM " . USERS . " u, " . LISTS . " ul
				WHERE ul.type_id = $data AND type = " . TYPE_GROUP . " AND ul.user_id = u.user_id";
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
			$template->assign_block_vars('member.options', array());
			
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
				
				$ids[] = $row['user_id'];
			}
			
			$sql_id = " AND NOT user_id IN (" . implode(', ', $ids) . ")";
			
			$s_options .= '<select name="mode">';
			$s_options .= '<option value="">' . sprintf($lang['stf_select_format'], $lang['com_select_option']) . "</option>\n";
			$s_options .= '<option value="uchange">' . sprintf($lang['stf_select_format'], $lang['notice_select_permission']) . "</option>\n";
			$s_options .= '<option value="udefault">' . sprintf($lang['stf_select_format'], $lang['notice_select_default']) . "</option>\n";
			$s_options .= '<option value="udelete">' . sprintf($lang['stf_select_format'], $lang['com_delete']) . "</option>\n";
			$s_options .= '</select>';
		}
		
		if ( !$grp_mod )
		{
			$template->assign_block_vars('member.no_moderators', array());
		}
		else
		{
			foreach ( $grp_mod as $row  )
			{
				$template->assign_block_vars('member.moderators', array(
					'ID'	=> $row['user_id'],
					'NAME'	=> $row['user_name'],
					'MAIN'	=> ($row['group_id'] == $data) ? $lang['com_yes'] : $lang['com_no'],
					'JOIN'	=> create_date($userdata['user_dateformat'], $row['time_create'], $userdata['user_timezone']),
					'REG'	=> create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),
				));
			}
		}
		
		if ( !$grp_mem )
		{
			$template->assign_block_vars('member.no_members', array());
		}
		else
		{
			foreach ( $grp_mem as $row  )
			{
				$template->assign_block_vars('member.members', array(
					'ID'	=> $row['user_id'],
					'NAME'	=> $row['user_name'],
					'MAIN'	=> ($row['group_id'] == $data) ? $lang['com_yes'] : $lang['com_no'],
					'JOIN'	=> create_date($userdata['user_dateformat'], $row['time_create'], $userdata['user_timezone']),
					'REG'	=> create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),
				));
			}
		}
			
		if ( $grp_pen )
		{
			$template->assign_block_vars('member.pending', array());
			
			foreach ( $grp_pen as $row  )
			{
				$template->assign_block_vars('member.pending.row_pending', array(
					'ID'	=> $row['user_id'],
					'NAME'	=> $row['user_name'],
					'JOIN'	=> create_date($userdata['user_dateformat'], $row['time_create'], $userdata['user_timezone']),
					'REG'	=> create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),
				));
			}
			
			$s_pending .= '<select name="smode">';
			$s_pending .= '<option value="">' . sprintf($lang['stf_select_format'], $lang['com_select_option']) . "</option>\n";
			$s_pending .= '<option value="uaccept">' . sprintf($lang['stf_select_format'], $lang['request_agree']) . "</option>\n";
			$s_pending .= '<option value="udeny">' . sprintf($lang['stf_select_format'], $lang['request_deny']) . "</option>\n";
			$s_pending .= '</select>';
		}
		
		$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
		$template->assign_vars(array(
			'L_HEAD'		=> sprintf($lang['stf_member'], $lang['titles'], $data_sql['group_name']),
			'L_EXPLAIN'		=> $lang['explain_user'],
			'L_OPTION'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['data_input'], $lang['data_input']),
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
			
			'S_ACTION'		=> check_sid("$file&id=$data"),
			'S_FIELDS'		=> $fields,
		));
	}
		
	$template->pparse('body');
			
	acp_footer();
}

?>