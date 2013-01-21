<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_teams',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_teams', 'auth' => 'auth_teams'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$tmpsub	= ( isset($_POST['cancel_sub']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_teams';
	
	include('./pagestart.php');
	
	add_lang('teams');

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
	
	
	$dir_flag	= $root_path . $settings['path_team_flag']['path'];
	$dir_logo	= $root_path . $settings['path_team_logo']['path'];
	$dir_games	= $root_path . $settings['path_games'];
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_teams'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel )	? redirect('admin/' . check_sid($file, true)) : false;
	( $tmpsub )	? redirect('admin/' . check_sid("$file?mode=member&id=$data")) : false;
	
#	( $smode == 'user_delete' ) ? $mode = 'user_delete' : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_teams.tpl',
		'ajax'		=> 'style/inc_request.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
#	debug($_GET, '_GET');
	debug($_POST, '_POST');
	
#	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down', 'user_create', 'user_ranks', 'user_level', 'user_delete'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				$template->assign_vars(array('IPATH' => $dir_games));
				
				$vars = array(
					'team' => array(
						'title1' => 'input_data',
						'team_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'team_game'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:images', 'required' => 'select_game', 'params' => array($dir_games, GAMES, true)),
						'team_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40', 'params' => TINY_NORMAL, 'required' => 'input_desc'),
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
					'L_HEAD'	=> sprintf($lang['sprintf_' . $mode], $lang['title'], $data_sql['team_name']),
					'L_EXPLAIN'	=> $lang['common_required'],
					
					'S_ACTION'			=> check_sid("$file&amp;mode=$mode&amp;id=$data"),
					'S_FIELDS'			=> $fields,
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
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['team_name']),
						
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
	
			case 'user_create':
			case 'user_ranks':
			case 'user_level':
					
					if ( $mode == 'user_create' )
					{
						$members	= request('members', TXT);
						$members_s	= request('members_select', ARY);
						$rank_id	= request('rank_id', INT);
						$moderator	= request('moderator', INT);
							
						if ( $members )
						{
							$members = trim($members, ', ');
							$members = trim($members, ',');
							
							$user_name_ary = array_unique(explode(', ', $members));
							
							$which_ary = 'user_name_ary';
							
							if ( $$which_ary && !is_array($$which_ary))
							{
								$$which_ary = array($$which_ary);
							}
							
							$sql_in = $$which_ary;
							unset($$which_ary);
							
							$sql_in = implode("', '", $sql_in);
							
							$user_id_ary = $user_name_ary = array();
							
							$sql = 'SELECT * FROM ' . USERS . ' WHERE user_name IN ("' . $sql_in . '")';
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_MESSAGE, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							if ( !($row = $db->sql_fetchrow($result)) )
							{
								$db->sql_freeresult($result);
								
								$error[] = $lang['msg_select_nomembers'];
							}
							
							do
							{
								$user_name_ary[$row['user_id']] = $row['user_name'];
								$user_id_ary[] = $row['user_id'];
							}
							while ($row = $db->sql_fetchrow($result));
							$db->sql_freeresult($result);
						}
						
						$user_id_ary = ( $members ) ? $user_id_ary : $members_s;
							
						$user_id_ary_im = implode(', ', $user_id_ary);
						
						$sql = "SELECT user_id FROM " . LISTS . " WHERE user_id IN ($user_id_ary_im) AND type = " . TYPE_TEAM . " AND type_id = $data";
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
						
						if ( !$add_id_ary )
						{
							$error[] = $lang['msg_select_nomembers'];
						}
						
						if (sizeof($add_id_ary))
						{
							$sql_ary = array();
					
							foreach ($add_id_ary as $user_id)
							{
								$sql_ary[] = array(
									'user_id'		=> (int) $user_id,
									'type'			=> (int) TYPE_TEAM,
									'type_id'		=> (int) $data,
									'user_rank'		=> (int) $rank_id,
									'user_status'	=> (int) $moderator,
									'time_create'	=> (int) $time,
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
				
							
							$sql = 'INSERT INTO ' . LISTS . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary);
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
						
						$lang_type = 'create_team_user';
					}
					else
					{
						$member = request('member',	ARY);
						$rankid = request('rank_id', INT);
						
						if ( $member && $mode == 'user_level')
						{
							$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_status = 1 AND user_id IN (" . implode(', ', $member) . ")";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$team_mods = array();
							while ( $row = $db->sql_fetchrow($result) )
							{
								$team_mods[] = $row['user_id'];
							}
							$db->sql_freeresult($result);
		
							if ( count($team_mods) > 0 )
							{
								$sql = "UPDATE " . LISTS . " SET user_status = 0, time_update = $time WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_id IN (" . implode(', ', $team_mods) . ")";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$sql_in = ( empty($team_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $team_mods) . ')');
							
							$sql = "UPDATE " . LISTS . " SET user_status = 1, time_update = $time WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_id IN (" . implode(', ', $member) . ")" . $sql_in;
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						
							$lang_type = ( count($member) > 1 ) ? 'update_rights' : 'update_right';
						}
						else if ( $member && $mode == 'user_ranks' )
						{
							$sql = "UPDATE " . LISTS . " SET user_rank = $rankid, time_update = $time WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_id IN (" . implode(', ', $member) . ")";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
	
							$lang_type = ( count($member) > 1 ) ? 'update_ranks' : 'update_rank';
						}
						else
						{
							$error[] = 'empty user';
						}
					}
					
				#	if ( !$error )
				#	{
				#		$msg = $lang[$lang_type] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&amp;id=$data"));
				#		
				#		log_add(LOG_ADMIN, $log, $smode, $lang_type);
				#		message(GENERAL_MESSAGE, $msg);
				#	}
				#	else
				#	{
				#		error('ERROR_BOX', $error);
				#	}
				
					$index = true;
				
				break;

			case 'user_delete':

				$data		= get_data(TEAMS, $data, 1);
				$members	= request('members', 4);
				
				if ( $members && $confirm )
				{
					$sql = "DELETE FROM " . LISTS . " WHERE user_id IN ($members) AND type = " . TYPE_TEAM . " AND type_id = $data";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					log_add(LOG_ADMIN, $log, 'ACP_TEAM_USER_DELETE', $data['team_name']);
					
					#$oCache -> sCachePath = './../cache/';
					#$oCache -> deleteCache('list_teams');
					#$oCache -> deleteCache('subnavi_list_teams');
					
					$message = $lang['delete_team_user'] . sprintf($lang['click_return_team'], '<a href="' . check_sid($file));
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $members && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirmsub.tpl'));

					$sql_in = implode(', ', $members);
					
					$fields = '<input type="hidden" name="mode" value="_user_delete" /><input type="hidden" name="' . 'id' . '" value="' . $data . '" /><input type="hidden" name="members" value="' . $sql_in . '" />';
					
					$sql = "SELECT user_name, user_id FROM " . USERS . " WHERE user_id IN ($sql_in)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$ary_userid = $ary_user_name = array();
							
					while ($row = $db->sql_fetchrow($result))
					{
						$ary_userid[]	= (int)		$row['user_id'];
						$ary_user_name[]	= (string)	$row['user_name'];
					}
					$db->sql_freeresult($result);
					
					$user_names = implode(', ', $ary_user_name);

					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['msg_confirm_delete'], sprintf($lang['delete_confirm_team_user'], $user_names), $data['team_name']),
					
						'S_FIELDS'		=> $fields,
						'S_ACTION'		=> check_sid($file),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_team']);
				}
				
				$template->pparse('body');
			
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
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
				));
			}
		}
		
		$template->assign_vars(array(
			'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['team']),
			'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['team']),
			'L_NAME'	=> $lang['teams'],
			
			'L_EXPLAIN'	=> $lang['explain'],
			
			'L_COUNT'	=> $lang['count'],
			'L_MEMBER'	=> $lang['common_member'],
			
		#	'S_CREATE'	=> check_sid("$file?mode=create"),
		#	'S_ACTION'	=> check_sid($file),
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
		
		$sql = "SELECT ul.user_rank, ul.time_create, ul.user_status, u.user_id, u.user_name, u.user_regdate, r.rank_name
					FROM " . USERS . " u, " . LISTS . " ul
						LEFT JOIN " . RANKS . " r ON r.rank_id = ul.user_rank
					WHERE type = " . TYPE_TEAM . " AND ul.type_id = $data AND ul.user_id = u.user_id
				ORDER BY r.rank_order";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$members = $db->sql_fetchrowset($result);
		
		if ( !$members )
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
				
				$users[] = $row['user_id'];
			}
			
			$sql_id .= " AND NOT user_id IN (" . implode(', ', $users) . ")";
			
			if ( !$team_moderators )
			{
				$template->assign_block_vars('member.no_moderators', array());
			}
			else
			{
				foreach ( $team_moderators as $row )
			#	for ( $i = 0; $i < count($team_moderators); $i++ )
				{
					$template->assign_block_vars('member.moderators', array(
						'ID'	=> $row['user_id'],
						'NAME'	=> $row['user_name'],
						'RANK'	=> $row['rank_name'],
						'REG'	=> create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),
						'JOIN'	=> create_date($userdata['user_dateformat'], $row['time_create'], $userdata['user_timezone']),
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
			#	for ( $i = 0; $i < count($team_members); $i++ )
				{
					$template->assign_block_vars('member.members', array(
						'ID'	=> $row['user_id'],
						'NAME'	=> $row['user_name'],
						'RANK'	=> $row['rank_name'],
						'REG'	=> create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),
						'JOIN'	=> create_date($userdata['user_dateformat'], $row['time_create'], $userdata['user_timezone']),
					));
				}
			}
		}
		
		$s_options .= '<select name="mode" onchange="setRequest(this.options[selectedIndex].value);">';
		$s_options .= '<option>' . sprintf($lang['sprintf_select_format'], $lang['common_select_option']) . '</option>';
		$s_options .= '<option value="user_level">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_rights_team']) . '</option>';
		$s_options .= '<option value="user_ranks">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_set_rank']) . '</option>';
		$s_options .= '<option value="user_delete">' . sprintf($lang['sprintf_select_format'], $lang['common_delete']) . '</option>';
		$s_options .= '</select>';
		
		$sql = "SELECT user_name, user_id FROM " . USERS . " WHERE user_id <> " . ANONYMOUS . $sql_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$missing_users = $db->sql_fetchrowset($result);
		
		if ( $missing_users )
		{
			$template->assign_block_vars('member.add', array());
			
			$s_users .= "<select class=\"select\" name=\"members_select[]\" rows=\"5\" multiple>";
			
			foreach ( $missing_users as $info => $value )
			{
				$s_users .= "<option value=\"" . $value['user_id'] . "\">" . sprintf($lang['sprintf_select_format'], $value['user_name']) . "</option>";
			}
			
			$s_users .= "</select>";
		}				
		
		$default_rank = data(RANKS, "rank_type = " . RANK_TEAM . " AND rank_standard = 1", '', 1, true);
		
	#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
	#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
		$template->assign_vars(array(
			'L_HEAD'	=> sprintf($lang['sprintf_member'], $lang['title'], $data_sql['team_name']),
			'L_EXPLAIN'	=> $lang['common_required'],
			
			'L_OPTION'	=> href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['input_data'], $lang['input_data']),
		
	#		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['team']),
	#		'L_INPUT'	=> sprintf($lang['sprintf_update'], $lang['team'], $data['team_name']),
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
			
			'S_RANK_SELECT'			=> select_box(RANKS, $default_rank['rank_id'], RANK_TEAM),
			'S_USERS'	=> $s_users,
			'S_OPTIONS'	=> $s_options,
			
			'S_ACTION'	=> check_sid("$file&id=$data"),
		#	'S_FIELDS'	=> $fields,
		));
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>