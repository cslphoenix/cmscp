<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_teams',
		'cat'       => 'clan',
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
	
	$current = 'acp_teams';
	
	include('./pagestart.php');
	
	add_lang('teams');
	acl_auth(array('a_team', 'a_team_assort', 'a_team_create', 'a_team_delete', 'a_team_manage'));

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$time	= time();
	$log	= SECTION_TEAM;
    $file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$smode	= request('smode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_flag	= $root_path . $settings['path_team_flag']['path'];
	$dir_logo	= $root_path . $settings['path_team_logo']['path'];
	$dir_games	= $root_path . $settings['path_games'];
	
	$acp_title	= sprintf($lang['stf_header'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid(($file . ($mode ? "?mode=$mode&id=$data" : '')))) : false;
	
	$template->set_filenames(array(
		'body'	=> "style/$current.tpl",
		'ajax'	=> 'style/inc_request.tpl',
	));

	$mode = (in_array($mode, array('create', 'delete', 'member', 'move_up', 'move_down', 'update', 'ranks', 'change'))) ? $mode : false;
	$_tpl = ($mode === 'delete' || $smode === 'delete') ? 'confirm' : 'body';
	
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
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);

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
				
				$option[] = href('a_txt', $file, array('mode' => 'member', 'id' => $data), $lang['member'], $lang['member']);
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

			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['team_name']),
				'L_EXPLAIN'	=> $lang['com_required'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'delete':
			
			$data_sql = data(TEAMS, $data, false, 1, 1);
			
			if ( $data && $accept && $userauth['a_team_delete'] )
			{
				$sql = sql(TEAMS, $mode, $data_sql, 'team_id', $data);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				if ( $sql['team_logo'] && file_exists($dir_path . $sql['team_logo']) )
				{
					unlink($dir_path . $sql['team_logo']);
				}
				
				if ( $sql['team_flag'] && file_exists($dir_path . $sql['team_flag']) )
				{
					unlink($dir_path . $sql['team_flag']);
				}

				orders(TEAMS);

				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
				
				log_add(LOG_ADMIN, $log, $mode, $db_data);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data && !$accept && $userauth['a_team_delete'] )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['com_confirm'],
					'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['team_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}

			break;
		
		case 'member':
		
			switch ( $smode )
			{
				case 'create':
				case 'change':
				case 'ranks':
				case 'default':
		
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
						
						if ( $smode == 'create' && $textarea && !$error )
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
									
									update_main_id($data, $ary_ids);
									
									$lang_type = 'update_create';
									
									break;
								
								case 'change':
									
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
									
								case 'default':
								
									update_main_id($data, $ary_userids);
									
									$lang_type = 'update_default';
								
									break;
								
								case 'ranks':
								
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
					
					break;
					
				case 'delete':
				
					$data_sql	= data(MATCH, $data, false, 1, true);
					$members	= request('members', ARY);
					
					if ( $members && $data && $accept && $userauth['a_team_manage'] )
					{
						$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id = $data AND  user_id IN ($members)";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$msg = $lang['update_delete'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
								
						log_add(LOG_ADMIN, $log, $mode, 'update_delete');
						message(GENERAL_MESSAGE, $msg);
					}
					else if ( $members && $data && !$accept && $userauth['a_team_manage'] )
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
							'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], sprintf($lang['notice_confirm_team'], $user_names), $data_sql['team_name']),
							
							'S_FIELDS'		=> $fields,
							'S_ACTION'		=> check_sid($file),
						));
					}
					else
					{
						message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
					}
					
					break;
			}
		
			$template->assign_block_vars('member', array());
			
			$template->assign_vars(array('FILE' => 'ajax_team_ranks'));
			$template->assign_var_from_handle('AJAX', 'ajax');
			
			$data_sql = data(TEAMS, $data, false, 1, true);
			
			$sql = "SELECT team_id, team_name FROM " . TEAMS . " ORDER BY team_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$teams[$row['team_id']] = $row['team_name'];
			}
			$db->sql_freeresult($result);
			
			$sql_id = $team_mod = $team_mem = $s_options = $s_users = '';
			
			$sql = "SELECT u.team_id, ul.user_rank, ul.time_create, ul.user_status, u.user_id, u.user_name, u.user_regdate, r.rank_name
						FROM " . USERS . " u, " . LISTS . " ul
							LEFT JOIN " . RANKS . " r ON r.rank_id = ul.user_rank
						WHERE type = " . TYPE_TEAM . " AND ul.type_id = $data AND ul.user_id = u.user_id
					ORDER BY ul.user_status DESC, r.rank_order ASC, u.user_name ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$member[] = $row;
			}
			$db->sql_freeresult($result);
			
			if ( $member )
			{
				$cnt = count($member);
				
				for ( $i = $start; $i < min($settings['per_page_entry']['acp_userlist'] + $start, $cnt); $i++ )
				{
					$template->assign_block_vars('member.row', array(
						'ID'	=> $member[$i]['user_id'],
						'MOD'	=> ($member[$i]['user_status'] ? '*' : ''),
						'NAME'	=> $member[$i]['user_name'],
						'RANK'	=> $member[$i]['rank_name'],
						'MAIN'	=> $teams[$member[$i]['team_id']],
						'JOIN'	=> create_date($userdata['user_dateformat'], $member[$i]['time_create'], $userdata['user_timezone']),
						'REG'	=> create_date($userdata['user_dateformat'], $member[$i]['user_regdate'], $userdata['user_timezone']),						
					));
				}
				
				$current_page = !$cnt ? 1 : ceil($cnt/$settings['per_page_entry']['acp_userlist']);
			
				$template->assign_vars(array(
					'PAGE_PAGING'	=> generate_pagination("$file&mode=$mode&id=$data", $cnt, $settings['per_page_entry']['acp_userlist'], $start),
					'PAGE_NUMBER'   => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp_userlist'] ) + 1 ), $current_page ),
				));
			}
			else
			{
				$template->assign_block_vars('member.no_row', array());
			}
			
			$s_options .= build_options(array(
				'smode'		=> 'setRequest(this.options[selectedIndex].value);',
				'option'	=> array(
					'select'	=> 'com_select_option',
					'change'	=> 'notice_select_permission',
					'ranks'		=> 'notice_select_rank',
					'default'	=> 'notice_select_default',
					'delete'	=> 'com_delete',
				),
			));
			
			$default_rank = data(RANKS, "rank_type = " . RANK_TEAM . " AND rank_standard = 1", '', 1, true);

			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
		
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
			$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['input_data'], $lang['input_data']);
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_member'], $lang['title'], $data_sql['team_name']),
				'L_EXPLAIN'	=> $lang['explain_user'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),
				
				'L_MAIN'	=> $lang['type_main'],
				
				'L_MEMBERS_ADD'			=> $lang['team_member_add'],
				'L_MEMBERS_ADD_EXPLAIN'	=> $lang['team_member_add_explain'],
				
				'L_MEMBER_ADD_MOD'	=> $lang['team_set_moderator'],
				'L_MEMBER_ADD_RANK'	=> $lang['team_set_rank'],
				
				'L_MEMBER'		=> $lang['member'],
				
				'L_USERNAME'			=> $lang['user_name'],
				'L_REGISTER'			=> $lang['register'],
				'L_JOIN'				=> $lang['joined'],
				'L_RANK'				=> $lang['rank_team'],
				
				'S_RANK_SELECT'			=> select_rank($default_rank['rank_id'], RANK_TEAM),
				'S_OPTIONS'	=> $s_options,
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;

		case 'move_up':
		case 'move_down':
	
			if ( $userauth['a_team_assort'] )
			{
				move(TEAMS, $mode, $order);
				log_add(LOG_ADMIN, $log, $mode);
			}
		
		default:
		
			$template->assign_block_vars('display', array());
			
			$fields = build_fields(array('mode' => 'create'));
			$sqlout = data(TEAMS, false, 'team_order ASC', 3, 3);
			
			$sql = "SELECT COUNT(user_id) AS total_members, type_id AS team_id FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id IN (" . implode(', ', array_keys($sqlout)) . ") GROUP BY type_id ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}

			while ( $row = $db->sql_fetchrow($result) )
			{
				$sqlout[$row['team_id']]['team_member'] = $row['total_members'];
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
					$id		= $row['team_id'];
					$name	= $row['team_name'];
					$order	= $row['team_order'];
					
					$template->assign_block_vars('display.row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
						'COUNT'		=> ( isset($row['team_member']) ) ? $row['team_member'] : 0,
						'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
						
						'MEMBER'	=> href('a_img', $file, array('mode' => 'member', 'id' => $id), 'icon_member', 'common_member'),
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
					));
				}
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['stf_header'], $lang['team']),
				'L_CREATE'	=> sprintf($lang['stf_create'], $lang['team']),
				
				'L_NAME'	=> $lang['titles'],
				'L_EXPLAIN'	=> $lang['explain'],
				
				'L_COUNT'	=> $lang['count'],
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