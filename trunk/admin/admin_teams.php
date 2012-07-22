<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_teams'] )
	{
		$module['hm_usergroups']['sm_settings'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$header_sub	= ( isset($_POST['cancel_sub']) ) ? true : false;
	$current	= 'sm_settings_teams';
	
	include('./pagestart.php');
	
	add_lang('teams');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_TEAM;
	$url	= POST_TEAMS;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	$smode		= request('smode', TXT);	
	
	$dir_flag	= $root_path . $settings['path_team_flag']['path'];
	$dir_logo	= $root_path . $settings['path_team_logo']['path'];
	$dir_games	= $root_path . $settings['path_games'];
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_teams'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header )		? redirect('admin/' . check_sid($file, true)) : false;
	( $header_sub )	? redirect('admin/' . check_sid("$file?mode=_member&$url=$data_id")) : false;
	
	( $smode == 'user_delete' ) ? $mode = 'user_delete' : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_teams.tpl',
		'ajax'		=> 'style/inc_request.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
		'tiny'		=> 'style/tinymce_normal.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'order', 'delete', 'member')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$template->assign_vars(array('PATH' => $dir_games));
				$template->assign_var_from_handle('UIMG', 'uimg');
				
				$vars = array(
					'team' => array(
						'title1' => 'input_data',
						'team_name'		=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name'),
						'team_game'		=> array('validate' => TXT,	'type' => 'drop:game',		'explain' => true, 'required' => 'input_game', 'params' => $dir_games),
						'team_desc'		=> array('validate' => TXT,	'type' => 'textarea:40',	'explain' => true, 'required' => 'input_desc'),
						'team_navi'		=> array('validate' => TXT,	'type' => 'radio:yesno',	'explain' => true),
						'team_awards'	=> array('validate' => TXT,	'type' => 'radio:yesno',	'explain' => true),
						'team_wars'		=> array('validate' => TXT,	'type' => 'radio:yesno',	'explain' => true),
						'team_join'		=> array('validate' => TXT,	'type' => 'radio:yesno',	'explain' => true),
						'team_fight'	=> array('validate' => TXT,	'type' => 'radio:yesno',	'explain' => true),
						'team_view'		=> array('validate' => TXT,	'type' => 'radio:yesno',	'explain' => true),
						'team_show'		=> array('validate' => TXT,	'type' => 'radio:yesno',	'explain' => true),
						'team_flag'		=> ( $settings['path_team_flag']['upload'] ) ? array('validate' => TXT, 'type' => 'upload:team_flag', 'explain' => true, 'params' => $dir_flag) : array('type' => 'hidden'),
						'team_logo'		=> ( $settings['path_team_logo']['upload'] ) ? array('validate' => TXT, 'type' => 'upload:team_logo', 'explain' => true, 'params' => $dir_logo) : array('type' => 'hidden'),
						'team_order'	=> array('validate' => INT,	'type' => 'drop:order',		'explain' => true),
					),
				);
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					$data = array(
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
						'team_order'	=> '',
					);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(TEAMS, $data_id, false, 3, true);
					
					$data['team_game'] = search_image(GAMES, 'name', $data['team_game']);
					
					$template->assign_block_vars('input.member', array());
				}
				else
				{
					$data = build_request(TEAMS, $vars, 'team', $error);
					
					if ( !$error )
					{
						$data['team_game']	= search_image(GAMES, 'id', $data['team_game']);
						$data['team_order']	= $data['team_order'] ? $data['team_order'] : maxa(TEAMS, 'team_order', false);
						
						if ( $mode == 'create' )
						{
							$sql = sql(TEAMS, $mode, $data);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(TEAMS, $mode, $data, 'team_id', $data_id);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
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
				
				build_output($data, $vars, 'input', false, TEAMS);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['team']),
					'L_INPUT'			=> sprintf($lang["sprintf_$mode"], $lang['team'], $data['team_name']),
					'L_MEMBER'			=> $lang['members'],
					
					'S_MEMBER'			=> check_sid("$file?mode=_member&amp;$url=$data_id"),
					'S_ACTION'			=> check_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				$template->pparse('body');
				
				break;

			case 'order':
				
				update(TEAMS, 'team', $move, $data_id);
				orders(TEAMS);
			
				log_add(LOG_ADMIN, $log, $mode);
								
			#	$oCache -> sCachePath = './../cache/';
			#	$oCache -> deleteCache('list_teams');
			#	$oCache -> deleteCache('subnavi_list_teams');
				
				$index = true;
	
				break;
				
			case 'delete':
				
				$data = data(TEAMS, $data_id, false, 1, 1);
				
				if ( $data_id && $confirm )
				{
					$db_data = sql(TEAMS, $mode, $data, 'team_id', $data_id);
					
					$db_user = sql(TEAMS_USERS, $mode, $data, 'team_id', $data_id);
					
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
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
					
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	
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
	
			case 'member':
			
				$template->assign_block_vars('member', array());
				
				$template->assign_vars(array('FILE' => 'ajax_team_ranks'));
				$template->assign_var_from_handle('AJAX', 'ajax');
				
				$data = data(TEAMS, $data_id, false, 1, true);
				
				$sql_id = $team_mods = $team_members = $s_options = $s_users = '';
				
				$sql = "SELECT ul.user_rank, ul.time_create, ul.user_status, u.user_id, u.user_name, u.user_regdate, r.rank_name
							FROM " . USERS . " u, " . LISTS . " ul
								LEFT JOIN " . RANKS . " r ON r.rank_id = ul.user_rank
							WHERE type = " . TYPE_TEAM . " AND ul.type_id = $data_id AND ul.user_id = u.user_id
						ORDER BY r.rank_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$members = $db->sql_fetchrowset($result);
				
				if ( !$members )
				{
					$template->assign_block_vars('member._no_moderators', array());
					$template->assign_block_vars('member._no_members', array());
				}
				else				
				{
					foreach ( $members as $member )
					{
						$ids[] = $member['user_id'];
					}
					
					$sql_id .= " AND NOT user_id IN (" . implode(', ', $ids) . ")";
					
					foreach ( $members as $member => $row )
					{
						if ( $row['user_status'] )
						{
							$team_mods[] = $row;
						}
						else
						{
							$team_members[] = $row;
						}
					}
					
					if ( !$team_mods )
					{
						$template->assign_block_vars('member._no_moderators', array());
					}
					else
					{
						for ( $i = 0; $i < count($team_mods); $i++ )
						{
							$template->assign_block_vars('member._mod_row', array(
								'ID'	=> $team_mods[$i]['user_id'],
								'NAME'	=> $team_mods[$i]['user_name'],
								'RANK'	=> $team_mods[$i]['rank_name'],
								'REG'	=> create_date($userdata['user_dateformat'], $team_mods[$i]['user_regdate'], $userdata['user_timezone']),
								'JOIN'	=> create_date($userdata['user_dateformat'], $team_mods[$i]['time_create'], $userdata['user_timezone']),
							));
						}
					}
					
					if ( !$team_members )
					{
						$template->assign_block_vars('member._no_members', array());
					}
					else
					{
						for ( $i = 0; $i < count($team_members); $i++ )
						{
							$template->assign_block_vars('member._member_row', array(
								'ID'	=> $team_members[$i]['user_id'],
								'NAME'	=> $team_members[$i]['user_name'],
								'RANK'	=> $team_members[$i]['rank_name'],
								'REG'	=> create_date($userdata['user_dateformat'], $team_members[$i]['user_regdate'], $userdata['user_timezone']),
								'JOIN'	=> create_date($userdata['user_dateformat'], $team_members[$i]['time_create'], $userdata['user_timezone']),
							));
						}
					}
				}
				
				$s_options .= "<select class=\"selectsmall\" name=\"smode\" id=\"smode\" onchange=\"setRequest(this.options[selectedIndex].value);\">";
				$s_options .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['common_option_select']) . "</option>";
				$s_options .= "<option value=\"_user_level\">" . sprintf($lang['sprintf_select_format'], $lang['select_ranks_rights']) . "</option>";
				$s_options .= "<option value=\"_user_setrank\">" . sprintf($lang['sprintf_select_format'], $lang['select_rank']) . "</option>";
				$s_options .= "<option value=\"_user_delete\">" . sprintf($lang['sprintf_select_format'], $lang['common_delete']) . "</option>";
				$s_options .= "</select>";
				
				$sql = "SELECT user_name, user_id FROM " . USERS . " WHERE user_id <> " . ANONYMOUS . $sql_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$missing_users = $db->sql_fetchrowset($result);
				
				if ( $missing_users )
				{
					$template->assign_block_vars('member._user_add', array());
					
					$s_users .= "<select class=\"select\" name=\"members_select[]\" rows=\"5\" multiple>";
					
					foreach ( $missing_users as $info => $value )
					{
						$s_users .= "<option value=\"" . $value['user_id'] . "\">" . sprintf($lang['sprintf_select_format'], $value['user_name']) . "</option>";
					}
					
					$s_users .= "</select>";
				}				
				
				$default_rank = data(RANKS, "rank_type = " . RANK_TEAM . " AND rank_standard = 1", '', 1, true);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['team']),
					'L_INPUT'	=> sprintf($lang['sprintf_update'], $lang['team'], $data['team_name']),
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
				#	'S_RANKS'	=> $s_ranks,
					'S_INPUT'	=> check_sid("$file?mode=_update&amp;$url=$data_id"),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( $smode == '_user_create' || $smode == '_user_setrank' || $smode == '_user_level' )
				{
					if ( $smode == '_user_create' )
					{
						$members	= request('members', 2);
						$members_s	= request('members_select', 4);
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
						
						$sql = "SELECT user_id FROM " . LISTS . " WHERE user_id IN ($user_id_ary_im) AND type = " . TYPE_TEAM . " AND type_id = $data_id";
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
									'type'			=> TYPE_TEAM,
									'type_id'		=> (int) $data_id,
									'user_rank'		=> (int) $rank_id,
									'user_status'	=> (int) $moderator,
									'time_create'	=> (int) time(),
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
						
						if ( $smode == '_user_level' )
						{
							$members_select	= array();
							
							for ( $i = 0; $i < count($member); $i++ )
							{
								$members_select[] = $member[$i];
							}
							
							if ( count($members_select) > 0 )
							{
								$user_ids = implode(', ', $members_select);
								
								$sql = "SELECT user_id FROM " . LISTS . " WHERE type_id = $data_id AND user_status = 1 AND type = " . TYPE_TEAM . " AND user_id IN ($user_ids)";
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
			
								if ( count($team_mods) > 0)
								{
									$sql = "UPDATE " . LISTS . " SET user_status = 0 WHERE type_id = $data_id AND type = " . TYPE_TEAM . " AND user_id IN (" . implode(', ', $team_mods) . ")";
									if ( !($result = $db->sql_query($sql)) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
								}
								
								$sql_in = ( empty($team_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $team_mods) . ')');
								
								$sql = "UPDATE " . LISTS . " SET user_status = 1 WHERE type_id = $data_id AND type = " . TYPE_TEAM . " AND user_id IN (" . implode(', ', $members_select) . ")" . $sql_in;
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
						
							$lang_type = ( $i > 1 ) ? 'update_rights' : 'update_right';
						}
						else if ( $smode == '_user_setrank' )
						{
							for ( $i = 0; $i < count($member); $i++ )
							{
								$sql = "UPDATE " . LISTS . " SET user_rank = $rankid WHERE type = " . TYPE_TEAM . " AND type_id = $data_id AND user_id = " . $member[$i];
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
							
							$lang_type = ( $i > 1 ) ? 'update_ranks' : 'update_rank';
						}
					}
					
					if ( !$error )
					{
						$message = $lang[$lang_type]
							. sprintf($lang['return'], check_sid($file), $acp_title)
							. sprintf($lang['return_update'], '<a href="' . check_sid("$file?mode=$mode&$url=$data_id"));	
						
						log_add(LOG_ADMIN, $log, $smode, $lang_type);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);

						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');
			
				break;

			case 'user_delete':

				$data		= get_data(TEAMS, $data_id, 1);
				$members	= request('members', 4);
				
				if ( $members && $confirm )
				{
					$sql = "DELETE FROM " . LISTS . " WHERE user_id IN ($members) AND type = " . TYPE_TEAM . " AND type_id = $data_id";
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
					
					$fields = '<input type="hidden" name="mode" value="_user_delete" /><input type="hidden" name="' . $url . '" value="' . $data_id . '" /><input type="hidden" name="members" value="' . $sql_in . '" />';
					
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
	
	$template->assign_block_vars('display', array());
	
	$fields .= '<input type="hidden" name="mode" value="create" />';
	
	$max = maxi(TEAMS, 'team_order', '');
	
	$sql = "SELECT t.*, g.* FROM " . TEAMS . " t, " . GAMES . " g WHERE t.team_game = g.game_id ORDER BY t.team_order";
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
			$data[$row['team_id']] = $row;
			$data[$row['team_id']]['total_members'] = 0;
		}
		$db->sql_freeresult($result);
		
		$sql = "SELECT COUNT(user_id) AS total_members, type_id AS team_id FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id IN (" . implode(', ', array_keys($data)) . ") GROUP BY type_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$data[$row['team_id']]['total_members'] = $row['total_members'];
		}
		$db->sql_freeresult($result);
		
		foreach ( $data as $team_id => $row )
		{
			$id		= $row['team_id'];
			$name	= $row['team_name'];
			$order	= $row['team_order'];
			
			$template->assign_block_vars('display.row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, $name),
				'COUNT'		=> $row['total_members'],
				'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
				
				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => 'order', 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => 'order', 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

				'MEMBER'	=> href('a_img', $file, array('mode' => 'member', $url => $id), 'icon_member', 'common_member'),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.empty', array());
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['team']),
		'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['team']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['team']),
		
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_COUNT'	=> $lang['count'],
		'L_MEMBER'	=> $lang['common_member'],
		
		'S_CREATE'	=> check_sid("$file?mode=create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>