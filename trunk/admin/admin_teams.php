<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_teams',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_teams'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$c_user	= ( isset($_POST['c_user']) ) ? true : false;
	
	$current = 'acp_teams';
	
	include('./pagestart.php');
	
	add_lang('teams');
	acl_auth(array('a_team', 'a_team_create', 'a_team_delete', 'a_team_manage'));

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_TEAM;
	$time	= time();
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_flag	= $root_path . $settings['path_team_flag']['path'];
	$dir_logo	= $root_path . $settings['path_team_logo']['path'];
	$dir_games	= $root_path . $settings['path_games'];
	
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	( $cancel )	? redirect('admin/' . check_sid($file, true)) : false;
	( $c_user )	? redirect('admin/' . check_sid("$file?mode=member&id=$data")) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_teams.tpl',
		'ajax'		=> 'style/inc_request.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	debug($_POST, '_POST');
#	debug($_FILES, '_FILES');
	
#	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down', 'ucreate', 'uranks', 'uchange', 'udelete'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':	acl_auth('a_team_create');
			case 'update':	acl_auth('a_team');
			
				$template->assign_block_vars('input', array());
				$template->assign_vars(array('IPATH' => $dir_games));
				
				$vars = array(
					'team' => array(
						'title1' => 'input_data',
						'team_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'team_game'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:images', 'required' => 'select_game', 'params' => array($dir_games, GAMES, true)),
						'team_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40', 'params' => TINY_NORMAL, 'class' => 'tinymce', 'required' => 'input_desc'),
						'team_navi'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
						'team_awards'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
						'team_wars'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
						'team_join'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
						'team_fight'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
						'team_view'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
						'team_show'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
						'team_flag'		=> ( $settings['path_team_flag']['upload'] ) ? array('validate' => TXT,	'explain' => false,	'type' => 'upload:image', 'params' => array($dir_flag, 'team_flag')) : 'hidden',
						'team_logo'		=> ( $settings['path_team_logo']['upload'] ) ? array('validate' => TXT,	'explain' => false,	'type' => 'upload:image', 'params' => array($dir_logo, 'team_logo')) : 'hidden',
						'team_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$data_sql = array(
						'team_name'		=> request('team_name', TXT),
						'team_desc'		=> '',
						'team_game'		=> '',
						'team_flag'		=> '',
						'team_logo'		=> '',
						'team_navi'		=> '0',
						'team_awards'	=> '0',
						'team_wars'		=> '0',
						'team_join'		=> '1',
						'team_fight'	=> '1',
						'team_view'		=> '1',
						'team_show'		=> '1',
						'team_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(TEAMS, $data, false, 3, true);
					
					$template->assign_vars(array('L_OPTION' => href('a_txt', $file, array('id' => $data), $lang['members'], $lang['members'])));
				}
				else
				{
					$data_sql = build_request(TEAMS, $vars, $error, $mode);
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$data_sql['team_order']	= maxa(TEAMS, 'team_order', false);
							
							$sql = sql(TEAMS, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(TEAMS, $mode, $data_sql, 'team_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
							
							update_main_id($data);
						}
						
						orders(TEAMS);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(TEAMS, $vars, $data_sql);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['team_name']),
					'L_EXPLAIN'	=> $lang['com_required'],
					
					'S_ACTION'	=> check_sid("$file&amp;mode=$mode&amp;id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;

			case 'move_up':
			case 'move_down':
			
				move(TEAMS, $mode, $order);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
			
			case 'delete':
				
				$data_sql = data(TEAMS, $data, false, 1, 1);
				
				if ( $data && $confirm )
				{
					$db_data = sql(TEAMS, $mode, $data_sql, 'team_id', $data);
					
					$db_user = sql(TEAMS_USERS, $mode, $data_sql, 'team_id', $data);
					
					if ( $data['team_logo'] && file_exists($dir_path . $data['team_logo']) )
					{
						unlink($dir_path . $data['team_logo']);
					}
					
					if ( $data['team_flag'] && file_exists($dir_path . $data['team_flag']) )
					{
						unlink($dir_path . $data['team_flag']);
					}
					
					$message = $lang['delete']
						. sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(TEAMS);
					
					log_add(LOG_ADMIN, $log, $mode, $db_data);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
					
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
	
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['team_name']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}
			
				$template->pparse('body');
				
				break;
				
			case 'ucreate':
			case 'uchange':
			case 'uranks':
			case 'udefault':
	
					$status		= request('status', TYP) ? 1 : 0 ;
					$default	= request('default', TYP) ? 1 : 0 ;
					$textarea	= request('textarea' , ARY);
					$rank_id	= request('rank_id' , INT);
					$members	= request('members', ARY);
					
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
					
					if ( $mode == 'ucreate' && $textarea && !$error )
					{
						$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_id IN (" . implode(', ', $ary_ids) . ")";
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
								'type'			=> (int) TYPE_TEAM,
								'type_id'		=> (int) $data,
								'user_id'		=> (int) $user_id,
								'user_rank'		=> (int) $rank_id,
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
								
								update_main_id($data, $ary_ids);
								
								$lang_type = 'update_create';
								
								break;
							
							case 'uchange':
								
								$ary = '';
									
								$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_status = 1 AND user_id IN ($ary_ids)";
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
								
									$sql = "UPDATE " . LISTS . " SET user_status = 0, time_update = '$time' WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_id IN (" . implode(', ', $ary) . ")";
									if ( !$db->sql_query($sql) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
								}
								
								$sql_in = empty($ary) ? '' : ' AND NOT user_id IN (' . implode(', ', $ary) . ')';
								
								$sql = "UPDATE " . LISTS . " SET user_status = 1, time_update = '$time' WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_id IN (" . implode(', ', $members) . ")" . $sql_in;
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								$lang_type = 'update_change';
								
								break;
								
							case 'udefault':
							
								update_main_id($data, $ary_userids);
								
								$lang_type = 'update_default';
							
								break;
							
							case 'uranks':
							
								$sql = "UPDATE " . LISTS . " SET user_rank = $rank_id, time_update = $time WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_id IN (" . implode(', ', $members) . ")";
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
		
								$lang_type = 'update_ranks';
								
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
			
				$data_sql	= data(TEAMS, $data, false, 1, true);
				$members	= request('members', ARY);
							
				if ( $members && $accept )
				{
					$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id = $data AND  user_id IN ($members)";
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
		
		$sql = "SELECT t.*, g.* FROM " . TEAMS . " t, " . GAMES . " g WHERE t.team_game = g.game_id ORDER BY t.team_order";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		if ( !$db->sql_numrows($result) )
		{
			$template->assign_block_vars('display.empty', array());
		}
		else
		{
			$type = '';
			$data_sql = array();
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$data_sql[$row['team_id']] = $row;
				$data_sql[$row['team_id']]['total_members'] = 0;
			}
			$db->sql_freeresult($result);
			
			$sql = "SELECT COUNT(user_id) AS total_members, type_id AS team_id FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id IN (" . implode(', ', array_keys($data_sql)) . ") GROUP BY type_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$data_sql[$row['team_id']]['total_members'] = $row['total_members'];
			}
			$db->sql_freeresult($result);
			
			$max = count($data_sql);
			
			foreach ( $data_sql as $team_id => $row )
			{
				$id		= $row['team_id'];
				$name	= $row['team_name'];
				$order	= $row['team_order'];
				
				$template->assign_block_vars('display.row', array(
					'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
					'COUNT'		=> $row['total_members'],
					'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
					
					'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
					
					'MEMBER'	=> href('a_img', $file, array('id' => $id), 'icon_member', 'common_member'),
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
				));
			}
		}
		
		$template->assign_vars(array(
			'L_HEAD'	=> sprintf($lang['stf_head'], $lang['team']),
			'L_CREATE'	=> sprintf($lang['stf_create'], $lang['team']),
			'L_NAME'	=> $lang['teams'],
			
			'L_EXPLAIN'	=> $lang['explain'],
			
			'L_COUNT'	=> $lang['count'],
			'L_MEMBER'	=> $lang['common_members'],
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	else
	{
		$template->assign_block_vars('member', array());
				
		$template->assign_vars(array('FILE' => 'ajax_team_ranks'));
		$template->assign_var_from_handle('AJAX', 'ajax');
		
		$data_sql = data(TEAMS, $data, false, 1, true);
		
		$sql_id = $team_mods = $team_members = $s_options = $s_users = '';
		
		$sql = "SELECT u.team_id, ul.user_rank, ul.time_create, ul.user_status, u.user_id, u.user_name, u.user_regdate, r.rank_name
					FROM " . USERS . " u, " . LISTS . " ul
						LEFT JOIN " . RANKS . " r ON r.rank_id = ul.user_rank
					WHERE type = " . TYPE_TEAM . " AND ul.type_id = $data AND ul.user_id = u.user_id
				ORDER BY r.rank_order ASC, ul.time_create ASC, u.user_name ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		if ( !$members = $db->sql_fetchrowset($result) )
		{
			$template->assign_block_vars('member.no_moderators', array());
			$template->assign_block_vars('member.no_members', array());
		}
		else				
		{
		#	debug($members);
		#	foreach ( $members as $member )
		#	{
		#		$ids[] = $member['user_id'];
		#	}
		
			$team_moderators = $team_members = array();
			
			foreach ( $members as $row )
			{
				if ( $row['user_status'] )
				{
					$team_moderators[] = $row;
				}
				else
				{
					$team_members[] = $row;
				}
			}
			
			if ( !$team_moderators )
			{
				$template->assign_block_vars('member.no_moderators', array());
			}
			else
			{
				foreach ( $team_moderators as $row )
				{
					$template->assign_block_vars('member.moderators', array(
						'ID'	=> $row['user_id'],
						'NAME'	=> $row['user_name'],
						'RANK'	=> $row['rank_name'],
						'MAIN'	=> ($row['team_id'] == $data) ? $lang['com_yes'] : $lang['com_no'],
						'JOIN'	=> create_date($userdata['user_dateformat'], $row['time_create'], $userdata['user_timezone']),
						'REG'	=> create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),						
					));
				}
			}
			
			if ( !$team_members )
			{
				$template->assign_block_vars('member.no_members', array());
			}
			else
			{
				foreach ( $team_members as $row )
				{
					$template->assign_block_vars('member.members', array(
						'ID'	=> $row['user_id'],
						'NAME'	=> $row['user_name'],
						'RANK'	=> $row['rank_name'],
						'MAIN'	=> ($row['team_id'] == $data) ? $lang['com_yes'] : $lang['com_no'],
						'REG'	=> create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),
						'JOIN'	=> create_date($userdata['user_dateformat'], $row['time_create'], $userdata['user_timezone']),
					));
				}
			}
		}
		
		$s_options .= '<select name="mode" onchange="setRequest(this.options[selectedIndex].value);">';
		$s_options .= '<option value="">' . sprintf($lang['stf_select_format'], $lang['com_select_option']) . "</option>\n";
		$s_options .= '<option value="uchange">' . sprintf($lang['stf_select_format'], $lang['notice_select_permission']) . "</option>\n";
		$s_options .= '<option value="uranks">' . sprintf($lang['stf_select_format'], $lang['notice_select_rank']) . "</option>\n";
		$s_options .= '<option value="udefault">' . sprintf($lang['stf_select_format'], $lang['notice_select_default']) . "</option>\n";
		$s_options .= '<option value="udelete">' . sprintf($lang['stf_select_format'], $lang['com_delete']) . "</option>\n";
		$s_options .= '</select>';
		
		$default_rank = data(RANKS, "rank_type = " . RANK_TEAM . " AND rank_standard = 1", '', 1, true);
		
	#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
	#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
		$template->assign_vars(array(
			'L_HEAD'	=> sprintf($lang['stf_member'], $lang['title'], $data_sql['team_name']),
			'L_EXPLAIN'	=> $lang['explain_user'],
			
			'L_OPTION'	=> href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['input_data'], $lang['input_data']),
		
	#		'L_HEAD'	=> sprintf($lang['stf_head'], $lang['team']),
	#		'L_INPUT'	=> sprintf($lang['stf_update'], $lang['team'], $data['team_name']),
			'L_MEMBER'	=> $lang['members'],
		#	'L_EXPLAIN'	=> $lang['team_member_explain'],
			
		#	'L_ADD'			=> $lang['team_member_add'],
		#	'L_ADD_EXPLAIN'	=> $lang['team_member_add_explain'],
			
			'L_MODERATORS'	=> ( count($team_mods) > 1 ) ? $lang['moderators'] : $lang['moderator'],
			'L_MEMBERS'		=> ( count($team_members) > 1 ) ? $lang['members'] : $lang['member'],
			
		#	'L_ADD_RANK'	=> $lang['team_set_rank'],
		#	'L_ADD_MOD'		=> $lang['team_set_moderator'],
		
			'L_USERNAME'			=> $lang['user_name'],
			'L_REGISTER'			=> $lang['register'],
			'L_JOIN'				=> $lang['joined'],
			'L_RANK'				=> $lang['rank_team'],
			
			'L_NO_MEMBERS'		=> $lang['no_members'],
			'L_NO_MODERATORS'	=> $lang['no_moderators'],
			
			'S_RANK_SELECT'			=> select_rank($default_rank['rank_id'], RANK_TEAM),
			'S_OPTIONS'	=> $s_options,
			
			'S_ACTION'	=> check_sid("$file&id=$data"),
		#	'S_FIELDS'	=> $fields,
		));
	}
	
	$template->pparse('body');
			
	acp_footer();
}

?>