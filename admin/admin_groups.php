<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_groups',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_groups', 'auth' => 'auth_groups'),
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
		
	add_lang('groups');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GROUPS;
	$time	= time();
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	$smode		= request('smode', TXT);
	$pmode		= request('pmode', TXT);
	
	$dir_path	= $root_path . $settings['path_group']['path'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
#	$auth_fields	= get_authlist();
#	$auth_levels	= array('allowed', 'disallowed');
#	$auth_const		= array(AUTH_ALLOWED, AUTH_DISALLOWED);

#	debug($auth_fields);
/*	
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
*/	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_groups'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_groups.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down'))) ? $mode : false;
	
	debug($_POST, '_POST');
	debug($_FILES, '_FILES');
	
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
					#	'user_id'		=> ( $mode == 'create' ) ? array('validate' => TXT,	'explain' => false,	'type' => 'ajax:25;25', 'required' => 'input_user', 'params' => 'user:0:2') : 'hidden',
					#	'group_access'	=> array('validate' => ARY,	'explain' => false,	'type' => 'drop:gaccess'),
						'group_desc'	=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:50'),
						'group_type'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array(false, true, false)),
						'group_legend'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:legend', 'params' => array(false, false, false)),
						'group_color'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:7;7', 'class' => 'color-picker'),
						'group_rank'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:ranks', 'params' => RANK_PAGE),
						'group_image'	=> array('validate' => TXT,	'explain' => false,	'type' => 'upload:image', 'params' => array($dir_path, 'group')),
						'group_order'	=> 'hidden',
					),
				);

				if ( $mode == 'create' && !$submit )
				{
					$data_sql = array(
						'group_name'	=> request('group_name', TXT),
						'group_type'	=> '1',
					#	'group_access'	=> '1',
						'group_desc'	=> '',
						'group_color'	=> 'FFFFFF',
						'group_image'	=> '',
						'group_rank'	=> '0',
						'group_legend'	=> '0',
						'group_order'	=> 0,
					#	'user_id'		=> '',
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
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
			#	debug($data_sql);
				
				build_output(GROUPS, $vars, $data_sql);
				
			#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
			#	$fields .= "<input type=\"hidden\" name=\"current_color\" value=\"" . $data_sql['group_color'] . "\" />";
			#	$fields .= "<input type=\"hidden\" name=\"current_image\" value=\"" . $data_sql['group_image'] . "\" />";
			#	$fields .= "<input type=\"hidden\" name=\"current_access\" value=\"" . $data['group_access'] . "\" />";				
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_' . $mode], $lang['title'], $data_sql['group_name']),
					'L_EXPLAIN'	=> $lang['common_required'],
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
			/*	
				if ( $submit )
				{
			#		if ( $group_img )
			#		{
			#			$sql_pic = upload_image($mode, 'image_group', 'group_image', '', $group_image, '', $dir_path, $group_img['temp'], $group_img['name'], $group_img['size'], $group_img['type'], $error);
			#		}
			#		else
			#		{
			#			$sql_pic = '';
			#		}
					
					$error[] = ( !$data['group_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error[] = ( !$user_id && $mode == 'create' ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_users'] : '';
					
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
							
							$sql = sql(GROUPS, $mode, $data_sql);
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
						#		$sql_pic = image_delete($data['group_image'], '', $root_path . $settings['path_group'] . '/', 'group_image');
						#	}
							
						#	if ( $group_image )
						#	{
						#		$sql_pic = upload_image($mode, 'image_group', 'group_image', '', $data['group_image'], '', $root_path . $settings['path_group'] . '/', $group_image['temp'], $group_image['name'], $group_image['size'], $group_image['type']);
						#	}
						#	
						#	else
						#	{
						#		$sql_pic = '';
						#	}
						
							$tmp = sql(GROUPS, $mode, $data_sql, 'group_id', $data);
							
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
							
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $tmp);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
			*/
				$template->pparse('body');
				
				break;
			
			case 'user_create':
			case 'user_ranks':
			case 'user_level':
				
				if ( $smode == 'add' || $smode == 'change' || $smode == 'delete' || $pmode == 'agree' || $pmode == 'deny' )
				{
					$text		= request('textarea' , ARY);
					$status		= request('mod', TYP) ? 1 : 0 ;
					$members	= ( $pmode == 'agree' || $pmode == 'deny' ) ? request('pending_members', ARY) : request('members', ARY);
					
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
						$error[] = ( $error ? '<br />' : '' ) . $lang['msg_select_users'];
					}
					
					if ( $smode == 'add' && ($text || $members) )
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
						if ( $smode == 'add' )
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
						else if ( $smode == 'change' )
						{
							if ( count($members) > 0 )
							{
								$_ary = '';
								
								$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_status = 1 AND user_id IN ($_ary_ids)";
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
										$sql = "UPDATE " . LISTS . " SET user_status = 0 WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN (" . implode(', ', $_ary) . ")";
										if ( !($result = $db->sql_query($sql)) )
										{
											message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
										}
									}
								}
								
								$sql_in = empty($_ary ) ? '' : ' AND NOT user_id IN (' . implode(', ', $_ary) . ')';
								
								$sql = "UPDATE " . LISTS . " SET user_status = 1, time_update = '$time' WHERE type = " . TYPE_GROUP . " AND type_id = $data AND user_id IN (" . implode(', ', $members) . ")" . $sql_in;
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
						
						$msg = $lang[$lang_type] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						
						log_add(LOG_ADMIN, $log, ($smode) ? $smode : $pmode, $lang_type);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
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
				
			#	debug($diff_ary);
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
	
	if ( !$data )
	{
		$template->assign_block_vars('display', array());
		
		$fields	= '<input type="hidden" name="mode" value="create" />';
		
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
					'DELETE'	=> ( $type != GROUP_SYSTEM ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
				));
			}
		}
		
		$template->assign_vars(array(
			'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['titles']),
			'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['title']),
			'L_NAME'		=> $lang['titles'],
			'L_EXPLAIN'		=> $lang['explain'],
			
			'L_MEMBER'		=> $lang['common_members'],
			'L_COUNT'		=> $lang['count'],
			
			'S_CREATE'		=> check_sid("$file&mode=create"),
			'S_ACTION'		=> check_sid($file),
			'S_FIELDS'		=> $fields,
		));
	}
	else
	{
		$template->assign_block_vars('member', array());
				
		$data_sql = data(GROUPS, $data, false, 1, true);
		
		$sql = "SELECT ul.user_status, ul.user_pending, u.user_id, u.user_name, u.user_regdate
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
			
			$s_options .= '<select name="smode">';
			$s_options .= '<option value="">' . sprintf($lang['sprintf_select_format'], $lang['common_select_option']) . '</option>';
			$s_options .= '<option value="change">' . sprintf($lang['sprintf_select_format'], $lang['change']) . '</option>';
			$s_options .= '<option value="delete">' . sprintf($lang['sprintf_select_format'], $lang['common_delete']) . '</option>';
			$s_options .= '</select>';
		}
		
		if ( !$grp_mod )
		{
			$template->assign_block_vars('member.mod_no', array());
		}
		else
		{
			foreach ( $grp_mod as $row  )
			{
				$template->assign_block_vars('member.mod', array(
					'USER_ID'	=> $row['user_id'],
					'USERNAME'	=> $row['user_name'],
					'REGISTER'	=> create_date('d.m.Y', $row['user_regdate'], $userdata['user_timezone']),
				));
			}
		}
		
		if ( !$grp_mem )
		{
			$template->assign_block_vars('member.mem_no', array());
		}
		else
		{
			foreach ( $grp_mem as $row  )
			{
				$template->assign_block_vars('member.mem', array(
					'USER_ID'	=> $row['user_id'],
					'USERNAME'	=> $row['user_name'],
					'REGISTER'	=> create_date('d.m.Y', $row['user_regdate'], $userdata['user_timezone']),
				));
			}
		}
			
		if ( $grp_pen )
		{
			$template->assign_block_vars('member.pending', array());
			
			foreach ( $grp_pen as $row  )
			{
				$template->assign_block_vars('member.pending.pen', array(
					'USER_ID'	=> $row['user_id'],
					'USERNAME'	=> $row['user_name'],
					'REGISTER'	=> create_date('d.m.Y', $row['user_regdate'], $userdata['user_timezone']),
				));
			}
			
			$s_pending .= '<select name="pmode">';
			$s_pending .= '<option value="">' . sprintf($lang['sprintf_select_format'], $lang['common_select_option']) . '</option>';
			$s_pending .= '<option value="agree">' . sprintf($lang['sprintf_select_format'], $lang['request_agree']) . '</option>';
			$s_pending .= '<option value="deny">' . sprintf($lang['sprintf_select_format'], $lang['request_deny']) . '</option>';
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
			$template->assign_block_vars('member.add', array());
				
			$s_users .= '<select name="members[]" rows="10" multiple="multiple">';
			
			foreach ( $users as $row  )
			{
				$s_users .= '<option value="' . $row['user_id'] . '">' . sprintf($lang['sprintf_select_format'], $row['user_name']) . '</option>';
			}
			
			$s_users .= '</select>';
		}
		
		$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
		$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
		$template->assign_vars(array(
			'L_HEAD'	=> sprintf($lang['sprintf_member'], $lang['title'], $data_sql['group_name']),
			'L_EXPLAIN'	=> $lang['common_required'],
			
			'L_OPTION'	=> href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['input_data'], $lang['input_data']),
			
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
			
		#	'S_UPDATE'		=> check_sid("$file?mode=_update&amp;$url=$data_id"),
			'S_ACTION'		=> check_sid("$file&mode=$mode&id=$data"),
			'S_FIELDS'		=> $fields,
		));
	}
		
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>