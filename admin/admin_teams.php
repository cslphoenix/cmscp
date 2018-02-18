<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_TEAMS',
		'CAT'		=> 'CLAN',
		'MODES'		=> array(
			'MAIN'	=> array(
				'TITLE'	=> 'ACP_TEAMS',
				'AUTH'	=> array('A_TEAM', 'A_TEAM_ASSORT', 'A_TEAM_CREATE', 'A_TEAM_DELETE', 'A_TEAM_MANAGE')
			),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$current = $log = 'ACP_TEAMS';
	
	include('./pagestart.php');
	
	add_lang('teams');
	add_tpls(array('body' => 'acp_teams', 'ajax' => 'inc_request'));
	acl_auth(array('A_TEAM', 'A_TEAM_ASSORT', 'A_TEAM_CREATE', 'A_TEAM_DELETE', 'A_TEAM_MANAGE'));
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$file	= basename(__FILE__) . $iadds;
	
	$games	= $root_path . $settings['path']['games'];
	$flag	= $root_path . $settings['path_team_flag']['path'];
	$logo	= $root_path . $settings['path_team_logo']['path'];
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$smode	= request('smode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$submit = ( isset($_POST['submit']) ) ? true : false;
#	$cancel = ( isset($_POST['cancel']) ) ? redirect('admin/' . check_sid(($file))) : false;
	$cancel = ( isset($_POST['cancel']) ) ? redirect('admin/' . check_sid(($file . ($mode ? "?mode=$mode&id=$data" : '')))) : false;
	
	$mode	= ( in_array($mode, array('create', 'delete', 'member', 'move_up', 'move_down', 'update', 'ranks', 'change', 'wars')) ) ? $mode : false;
	
	$_top	= sprintf($lang['STF_HEADER'], $lang['TITLE']);
	$_tpl	= ($mode === 'delete' || $smode === 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			$template->assign_vars(array('IPATH' => $games));
			
			$vars = array(
				'team' => array(
					'title'			=> 'INPUT_DATA',
					'team_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'team_game'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:images', 'required' => 'select_game', 'params' => array($games, GAMES, true)),
					'team_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40', 'params' => TINY_NORMAL, 'class' => 'tinymce', 'required' => 'input_desc'),
					'team_navi'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
					'team_awards'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
					'team_wars'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
					'team_join'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
					'team_fight'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
					'team_view'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
					'team_show'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:yesno'),
					'team_flag'		=> ( $settings['path_team_flag']['upload'] ) ? array('validate' => TXT,	'explain' => false,	'type' => 'upload:image', 'params' => array($flag, 'team_flag')) : 'hidden',
					'team_logo'		=> ( $settings['path_team_logo']['upload'] ) ? array('validate' => TXT,	'explain' => false,	'type' => 'upload:image', 'params' => array($logo, 'team_logo')) : 'hidden',
					'team_order'	=> 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);

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
				$data_sql = data(TEAMS, $data, false, 3, 'row');
				
				$option[] = href('a_txt', $file, array('mode' => 'member', 'id' => $data), $lang['MEMBER'], $lang['MEMBER']);
				
				$sql_train = data(TRAINING, "WHERE team_id = $data", 'training_date DESC', 1, 'set');
				$sql_match = data(MATCH, "WHERE team_id = $data", 'match_date DESC', 1, 'set');
				
				if ( $sql_train || $sql_match )
				{
					$option[] = href('a_txt', $file, array('mode' => 'wars', 'id' => $data), $lang['WARS'], $lang['WARS']);
				}
			}
			else
			{
				$data_sql = build_request(TEAMS, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'create' )
					{
						$data_sql['team_order']	= _max(TEAMS, 'team_order', false);
						
						$sql = sql(TEAMS, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else
					{
						$sql = sql(TEAMS, $mode, $data_sql, 'team_id', $data);
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
						
						update_main_id($data);
					}
					
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
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['team_name']),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'wars':
			
			$template->assign_block_vars('wars', array());
		
			$data_sql = data(TEAMS, $data, false, 3, 'row');
		
			$sql_training = data(TRAINING, "WHERE team_id = $data", 'training_date DESC', 1, 'set', false, false, false, $settings['ppe_acp']);
			$sql_match = data(MATCH, "WHERE team_id = $data", 'match_date DESC', 1, 'set', false, false, false, $settings['ppe_acp']);
			
			$tupcoming = $texpired = $mupcoming = $mexpired = '';
			
			if ( !$sql_training )
			{
				$template->assign_block_vars('wars.tupcoming_none', array());
				$template->assign_block_vars('wars.texpired_none', array());
			}
			else
			{
				foreach ( $sql_training as $row )
				{
					if ( $row['training_date'] > time() )
					{
						$tupcoming[] = $row;
					}
					else if ( $row['training_date'] < time() )
					{
						$texpired[] = $row;
					}
				}
				
				if ( !$tupcoming )
				{
					$template->assign_block_vars('wars.tupcoming_none', array());
				}
				else
				{
					foreach ( $tupcoming as $row )
					{
						$id = $row['training_id'];
						$vs = $row['training_vs'];
						
						$template->assign_block_vars('wars.tupcoming', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $vs, $vs),
							'DATE'		=> create_date($userdata['user_dateformat'], $row['training_date'], $userdata['user_timezone']),
							
							'MEMBER'	=> href('a_img', $file, array('mode' => 'member', 'id' => $id), 'icon_member', 'common_member'),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
			
				if ( !$texpired )
				{
					$template->assign_block_vars('wars.texpired_none', array());
				}
				else
				{
					foreach ( $texpired as $row )
					{
						$id = $row['training_id'];
						$vs = $row['training_vs'];
						
						$template->assign_block_vars('wars.texpired', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $vs, $vs),
							'DATE'		=> create_date($userdata['user_dateformat'], $row['training_date'], $userdata['user_timezone']),
							
							'MEMBER'	=> href('a_img', $file, array('mode' => 'member', 'id' => $id), 'icon_member', 'common_member'),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
			}
			
			if ( !$sql_match )
			{
				$template->assign_block_vars('wars.mupcoming_none', array());
				$template->assign_block_vars('wars.mexpired_none', array());
			}
			else
			{
				foreach ( $sql_match as $row )
				{
					if ( $row['match_date'] > time() )
					{
						$mupcoming[] = $row;
					}
					else if ( $row['match_date'] < time() )
					{
						$mexpired[] = $row;
					}
				}
				
				if ( !$mupcoming )
				{
					$template->assign_block_vars('wars.mupcoming_none', array());
				}
				else
				{
					foreach ( $mupcoming as $row )
					{
						$id		= $row['match_id'];
						$rival	= $row['match_rival_name'];
						$team	= $row['team_id'];
						$public	= $row['match_public'] ? 'STF_MATCH_NAME' : 'STF_MATCH_INTERN';
						
						$template->assign_block_vars('wars.mupcoming', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), sprintf($lang[$public], $rival), ''),
							'DATE'		=> create_date($userdata['user_dateformat'], $row['match_date'], $userdata['user_timezone']),
							
							'DETAIL'	=> href('a_img', $file, array('mode' => 'detail', 'id' => $id), 'icon_details', 'common_details'),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
				
				if ( !$mexpired )
				{
					$template->assign_block_vars('wars.mexpired_none', array());
				}
				else
				{
					foreach ( $mexpired as $row )
					{
						$id		= $row['match_id'];
						$rival	= $row['match_rival_name'];
						$team	= $row['team_id'];
						$public	= $row['match_public'] ? 'STF_MATCH_NAME' : 'STF_MATCH_INTERN';
						
						$template->assign_block_vars('wars.mexpired', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), sprintf($lang[$public], $rival), ''),
							'DATE'		=> create_date($userdata['user_dateformat'], $row['match_date'], $userdata['user_timezone']),
							
							'DETAIL'	=> href('a_img', $file, array('mode' => 'detail', 'id' => $id), 'icon_details', 'common_details'),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
			}
		
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['INPUT_DATA'], $lang['INPUT_DATA']);
			$option[] = href('a_txt', $file, array('mode' => 'member', 'id' => $data), $lang['MEMBER'], $lang['MEMBER']);
		
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['team_name']),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_MATCH_UPCOMING'		=> $lang['MATCH_UPCOMING'],
				'L_MATCH_EXPIRED'		=> $lang['MATCH_EXPIRED'],
				'L_TRAINING_UPCOMING'	=> $lang['TRAINING_UPCOMING'],
				'L_TRAINING_EXPIRED'	=> $lang['TRAINING_EXPIRED'],
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
		
		case 'delete':
		
			$del = array(
				'field' => 'team_id',
				'table'	=> TEAMS,
				'name'	=> 'team_name'
			);
			
			$sqlout = data($del['table'], $data, false, 1, 'row');

			if ( $data && $accept && $userauth['A_TEAM_MANAGE'] && $sqlout )
			{
				$sql = sql($del['table'], $mode, $sqlout, $del['field'], $data);
				$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
				
				if ( $sqlout['team_logo'] && file_exists($dir_path . $sqlout['team_logo']) )
				{
					unlink($dir_path . $sqlout['team_logo']);
				}
				
				if ( $sqlout['team_flag'] && file_exists($dir_path . $sqlout['team_flag']) )
				{
					unlink($dir_path . $sqlout['team_flag']);
				}

				orders($del['table']);
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept && $userauth['A_TEAM_MANAGE'] )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $sqlout[$del['name']]),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
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
									
									$lang_type = 'UPDATE_CREATE';
									
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
									
									$lang_type = 'UPDATE_CHANGE';
									
									break;
									
								case 'default':
								
									update_main_id($data, $ary_userids);
									
									$lang_type = 'UPDATE_DEFAULT';
								
									break;
								
								case 'ranks':
								
									$sql = "UPDATE " . LISTS . " SET user_rank = $rank_id, time_update = $time WHERE type = " . TYPE_TEAM . " AND type_id = $data AND user_id IN (" . implode(', ', $members) . ")";
									if ( !$db->sql_query($sql) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
			
									$lang_type = 'UPDATE_RANKS';
									
									break;
							}
							
							log_add(LOG_ADMIN, $log, $mode, $lang_type);
							right('ERROR_BOX', langs($lang_type));
						}
						else
						{
							error('ERROR_BOX', $error);
						}
					
					break;
					
				case 'delete':
				
					$data_sql	= data(MATCH, $data, false, 1, 'row');
					$members	= request('members', ARY);
					
					if ( $members && $data && $accept && $userauth['a_team_manage'] )
					{
						$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_TEAM . " AND type_id = $data AND  user_id IN ($members)";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$msg = $lang['UPDATE_DELETE'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
								
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
							'M_TITLE'	=> $lang['COMMON_CONFIRM'],
							'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], sprintf($lang['NOTICE_CONFIRM_TEAM'], $user_names), $data_sql['team_name']),
							
							'S_FIELDS'		=> $fields,
							'S_ACTION'		=> check_sid($file),
						));
					}
					else
					{
						message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
					}
					
					break;
			}
		
			$template->assign_block_vars('member', array());
			
			$template->assign_vars(array('FILE' => 'ajax_team_ranks'));
			$template->assign_var_from_handle('AJAX', 'ajax');
			
			$data_sql = data(TEAMS, $data, false, 1, 'row');
			
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
			
			$cnt = 0;
			$sql_id = $moderator = $member = $s_options = $s_users = '';
			
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
				if ( $row['user_status'] )
				{
					$moderator[] = $row;
				}
				else
				{
					$member[] = $row;
				}
			}
			$db->sql_freeresult($result);
			
			if ( $moderator )
			{
				foreach ( $moderator as $row )
				{
					$template->assign_block_vars('member.moderators', array(
						'ID'	=> $row['user_id'],
						'NAME'	=> $row['user_name'],
						'RANK'	=> $row['rank_name'],
						'MAIN'	=> $teams[$row['team_id']],
						'JOIN'	=> create_date($userdata['user_dateformat'], $row['time_create'], $userdata['user_timezone']),
						'REG'	=> create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),						
					));
				}
			}
			else
			{
				$template->assign_block_vars('member.moderators_none', array());
			}
			
			if ( $member )
			{
				$cnt = count($member);
				
				for ( $i = $start; $i < min($settings['per_page_entry']['acp_userlist'] + $start, $cnt); $i++ )
				{
					$template->assign_block_vars('member.members', array(
						'ID'	=> $member[$i]['user_id'],
						'NAME'	=> $member[$i]['user_name'],
						'RANK'	=> $member[$i]['rank_name'],
						'MAIN'	=> $teams[$member[$i]['team_id']],
						'JOIN'	=> create_date($userdata['user_dateformat'], $member[$i]['time_create'], $userdata['user_timezone']),
						'REG'	=> create_date($userdata['user_dateformat'], $member[$i]['user_regdate'], $userdata['user_timezone']),						
					));
				}
			}
			else
			{
				$template->assign_block_vars('member.members_none', array());
			}
			
			$current_page = !$cnt ? 1 : ceil($cnt/$settings['per_page_entry']['acp_userlist']);
			
			$template->assign_vars(array(
				'PAGE_PAGING'	=> generate_pagination("$file&mode=$mode&id=$data", $cnt, $settings['per_page_entry']['acp_userlist'], $start),
				'PAGE_NUMBER'   => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp_userlist'] ) + 1 ), $current_page ),
			));
			
			$s_options .= build_options(array(
				'smode'		=> 'setRequest(this.options[selectedIndex].value);',
				'option'	=> array(
					'select'	=> 'COMMON_SELECT_OPTION',
					'change'	=> 'NOTICE_SELECT_PERMISSION',
					'ranks'		=> 'NOTICE_SELECT_RANK',
					'default'	=> 'notice_select_default',
					'delete'	=> 'COMMON_DELETE',
				),
			));
			
			$default_rank = data(RANKS, "rank_type = " . RANK_TEAM . " AND rank_standard = 1", '', 1, true);

			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
		
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['INPUT_DATA'], $lang['INPUT_DATA']);
			
			$sql_train = data(TRAINING, "WHERE team_id = $data", 'training_date DESC', 1, 'set');
			$sql_match = data(MATCH, "WHERE team_id = $data", 'match_date DESC', 1, 'set');
			
		#	debug($sql_train, '$sql_train');
		#	debug($sql_match, '$sql_match');
			
			if ( $sql_train || $sql_match )
			{
				$option[] = href('a_txt', $file, array('mode' => 'wars', 'id' => $data), $lang['WARS'], $lang['WARS']);
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_MEMBER'], $lang['TITLE'], $data_sql['team_name']),
				
				'L_OPTION'			=> implode($lang['COMMON_BULL'], $option),
				'L_EXPLAIN'			=> $lang['EXPLAIN_USER'],
				'L_MAIN'			=> $lang['MAIN_GROUP'],
				'L_MEMBER'			=> $lang['MEMBER'],
				'L_MEMBERS'			=> $lang['MEMBERS'],
				'L_MEMBERS_NONE'	=> $lang['MEMBERS_NONE'],
				'L_MODERATORS'		=> $lang['MODERATORS'],
				'L_MODERATORS_NONE'	=> $lang['MODERATORS_NONE'],
				'L_ADD'				=> $lang['COMMON_ADD'],
				'L_ADD_EXPLAIN'		=> $lang['COMMON_ADD_EXPLAIN'],
				'L_MOD'				=> $lang['team_set_moderator'],
				'L_USERNAME'		=> $lang['user_name'],
				'L_REGISTER'		=> $lang['register'],
				'L_JOIN'			=> $lang['joined'],
				'L_RANK'			=> $lang['RANK_TEAM'],
				
				'S_RANKS'			=> select_rank($default_rank['rank_id'], RANK_TEAM),
				'S_OPTIONS'			=> $s_options,
				'S_ACTION'			=> check_sid($file),
				'S_FIELDS'			=> $fields,
			));
			
			break;

		case 'move_up':
		case 'move_down':
	
			if ( $userauth['A_TEAM_ASSORT'] )
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
				$template->assign_block_vars('display.none', array());
			}
			else
			{
				$cnt = count($sqlout);
				
				foreach ( $sqlout as $row )
				{
					$id		= $row['team_id'];
					$name	= $row['team_name'];
					$order	= $row['team_order'];
					
					$template->assign_block_vars('display.row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
						'COUNT'		=> ( isset($row['team_member']) ) ? $row['team_member'] : 0,
						'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
						'MOVE_DOWN'	=> ( $order != $cnt )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
						
						'MEMBER'	=> href('a_img', $file, array('mode' => 'member', 'id' => $id), 'icon_member', 'common_member'),
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
					));
				}
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['TITLE']),
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				'L_NAME'	=> $lang['TITLES'],
				
				'L_COUNT'	=> $lang['COUNT_MEMBER'],
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>