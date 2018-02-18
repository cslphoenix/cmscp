<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_TRAINING',
		'CAT'		=> 'CLAN',
		'MODES'		=> array(
			'MAIN'	=> array(
				'TITLE' => 'ACP_TRAINING',
				'AUTH'	=> array('A_TRAINING', 'A_TRAINING_CREATE', 'A_TRAINING_MANAGE', 'A_TRAINING_DELETE')
			),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$current = $log = 'ACP_TRAINING';
	
	include('./pagestart.php');
	
	add_lang('training');
	add_tpls('acp_training');
	acl_auth(array('A_TRAINING', 'A_TRAINING_CREATE', 'A_TRAINING_MANAGE', 'A_TRAINING_DELETE'));
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$file	= basename(__FILE__) . $iadds;
	
	$base	= $settings['switch']['training'];
	$comt	= $settings['comments']['training'];
	
	$data	= request('id', INT);
	$m_id	= request('m_id', INT);
	$t_id	= request('t_id', INT);
	$index	= request('index', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);	
	$sub	= request('sub', TYP);
	$subs	= request('subs', TYP);
	$mode	= request('mode', TYP);
	$smode	= request('smode', TYP);
	$sort	= request('sort', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$cancel = ( isset($_POST['cancel']) ) ? redirect('admin/' . check_sid($file)) : false;
#	( $cancel && !$index )	? redirect('admin/' . check_sid((basename(__FILE__) . $iadds . ($mode ? "?mode=$mode&id=$data" : '')))) : false;
#	( $cancel && $index )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$mode	= ( in_array($mode, array('create', 'delete', 'member', 'update')) ) ? $mode : false;
	
	$_top	= sprintf($lang['STF_HEADER'], $lang['TITLE']);
	$_tpl	= ($mode === 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());

			$vars = array(
				'training' => array(
					'title'	=> 'INPUT_DATA',
					'training_vs'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_rival'),
					'team_id'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:team', 'required' => 'select_team', 'params' => array('request', 'training_maps')),
					'match_id'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:match'),
					'training_maps'		=> array('validate' => ARY,	'explain' => false,	'type' => 'drop:maps', 'required' => 'select_maps'),
					'training_date'		=> array('validate' => ($base ? INT : TXT), 'type' => ($base ? 'drop:datetime' : 'text:25;25'), 'params' => ($base ? (($mode == 'create') ? $time : '-1') : 'format')),
					'training_duration'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:duration', 'params' => 'training_date'),
					'training_text'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40', 'params' => TINY_NORMAL, 'class' => 'tinymce'),
					'training_comments'	=> ( $comt ) ? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
					'time_create'		=> 'hidden',
					'time_update'		=> 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'training_vs'		=> ( request('training_vs', TXT) ) ? request('training_vs', TXT) : request('vs', TXT),
					'team_id'			=> $data,
					'match_id'			=> $m_id,
					'training_maps'		=> 'a:0:{}',
					'training_date'		=> $time,
					'training_duration'	=> '',
					'training_text'		=> '',
					'training_comments'	=> $settings['comments']['training'],
					'time_create'		=> $time,
					'time_update'		=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(TRAINING, $data, false, 1, 'row');
				$option[] = href('a_txt', $file, array('mode' => 'member', 'id' => $data), $lang['SUBSCRIBER'], $lang['SUBSCRIBER']);
			}
			else
			{
				$data_sql = build_request(TRAINING, $vars, $error, $mode);
				
				if ( !$error )
				{
				#	$data_sql['training_maps'] = is_array($data_sql['training_maps']) ? serialize($data_sql['training_maps']) : array();
					
					if ( $mode == 'create' && $userauth['A_TRAINING_CREATE'] )
					{
						$sql = sql(TRAINING, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else if ( $userauth['A_TRAINING'] )
					{
						$sql = sql(TRAINING, $mode, $data_sql, 'training_id', $data);
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					}
					
				#	$oCache -> deleteCache('cal_sn_' . request('month', 0) . '_member');
				#	$oCache -> deleteCache('subnavi_training_' . request('month', 0));
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output(TRAINING, $vars, $data_sql);

            $fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['training_vs']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'member':
		
			switch ( $smode )
			{
				case 'create':
				case 'status_y':
				case 'status_n':
				case 'status_r':
		
						$status		= ( $smode == 'create' ) ? request('user_status', INT) : ( ( $smode != 'status_r' ) ? (($smode == 'status_y') ? STATUS_YES : STATUS_NO) : STATUS_REPLACE );
						$members	= request('members', ARY);
					
						if ( !$members )
						{
							$error[] = $lang['msg_select_member'];
						}
						
						if ( !$error )
						{
							switch ( $smode )
							{
								case 'create':
							
									$ary_in_db = array();
									$ary_users = $members;
									$ary_users_list = implode(', ', $members);
									
									$sql = "SELECT user_id FROM " . LISTS . " WHERE type = " . TYPE_TRAINING . " AND type_id = $data AND user_id IN ($ary_users_list)";
									if ( !($result = $db->sql_query($sql)) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
									
									while ( $row = $db->sql_fetchrow($result) )
									{
										$ary_in_db[] = (int) $row['user_id'];
									}
									$db->sql_freeresult($result);
									
									$user_in_db = array_diff($ary_users, $ary_in_db);
									
									if ( !count($user_in_db) )
									{
										$error = ( $error ? '<br />' : '' ) . $lang['msg_selected_member'];
									}
									else
									{
										$sql_ary = array();
									
										foreach ( $user_in_db as $user_id )
										{
											$sql_ary[] = array(
												'type'			=> (int) TYPE_TRAINING,
												'type_id'		=> (int) $data,
												'user_id'		=> (int) $user_id,							
												'user_status'	=> (int) $status,
												'time_create'	=> (int) $time,
											);
										}
									
										if ( !count($sql_ary) )
										{
											$error = ( $error ? '<br />' : '' ) . $lang['msg_selected_member'];
										}
										
										$ary = array();
										foreach ( $sql_ary as $id => $_sql_ary )
										{
											$values = array();
											foreach ( $_sql_ary as $key => $var )
											{
												$values[] = (int) $var;
											}
											$ary[] = "(" . implode(', ', $values) . ")";
										}
										
										$sql = "INSERT INTO " . LISTS . " (" . implode(', ', array_keys($sql_ary[0])) . ") VALUES " . implode(', ', $ary);
										if ( !$db->sql_query($sql) )
										{
											message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
										}
									}
									
									$lang_type = 'user_create';
									
									break;
									
								case 'status_y':
								case 'status_n':
								case 'status_r':
								
									$sql = "UPDATE " . LISTS . " SET user_status = $status, time_update = '$time' WHERE type = " . TYPE_TRAINING . " AND type_id = $data AND user_id IN (" . implode(', ', $members) . ")";
									if ( !$db->sql_query($sql) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
									$lang_type = 'update_ranks';
									
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
				
					$data_sql	= data(TEAMS, $data, false, 1, 'row');
					$members	= request('members', ARY);
					
					if ( $members && $data && $accept && $userauth['a_team_manage'] )
					{
						$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_TRAINING . " AND type_id = $data AND  user_id IN ($members)";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$msg = $lang['update_delete'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
								
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
							$ary_user_name[] = (string) $row['user_name'];
						}
						$db->sql_freeresult($result);
						
						$user_names = implode(', ', $ary_user_name);
		
						$template->assign_vars(array(
							'M_TITLE'	=> $lang['COMMON_CONFIRM'],
							'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], sprintf($lang['notice_confirm_training'], $user_names), $data_sql['training_vs']),
							
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
			
			$data_sql = data(TRAINING, $data, false, 1, 'row');
			
			$sql = "SELECT u.user_id, u.user_name, u.user_color, l.user_status, l.time_create, l.time_update
						FROM " . USERS . " u, " . LISTS . " l
					WHERE l.type_id = $data AND l.type = " . TYPE_TRAINING . " AND l.user_id = u.user_id
						ORDER BY l.user_status ASC, u.user_name";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$member = $user_id = array();
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$member[]	= $row;
				$user_id[]	= $row['user_id'];
			}
			
			$s_team_member = select_team_member($data_sql['team_id'], $user_id, $member, 'selectsmall');
			
			if ( $member )
			{
				$cnt = count($member);
				
				for ( $i = $start; $i < min($settings['per_page_entry']['acp_userlist'] + $start, $cnt); $i++ )
				{
					$template->assign_block_vars('member.row', array(
						'ID'		=> $member[$i]['user_id'],
						'NAME'		=> $member[$i]['user_name'],
						'STATUS'	=> ( $member[$i]['user_status'] != STATUS_YES ? ($member[$i]['user_status'] != STATUS_NO ? $lang['status_replace'] : $lang['status_no']) : $lang['status_yes']),
						'CREATE'	=> create_date($userdata['user_dateformat'], $member[$i]['time_create'], $userdata['user_timezone']),
						'UPDATE'	=> $member[$i]['time_update'] ? create_date($userdata['user_dateformat'], $member[$i]['time_update'], $userdata['user_timezone']) : $lang['no_time'],
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
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
		
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['input_data'], $lang['input_data']);
			
			$s_options = build_options(array(
				'smode'		=> false,
				'option'	=> array(
					'select'	=> 'COMMON_SELECT_OPTION',
					'status_y'	=> 'notice_select_sy',
					'status_n'	=> 'notice_select_sn',
					'status_r'	=> 'notice_select_sr',
					'delete'	=> 'COMMON_DELETE',
				),
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_MEMBER'], $lang['TITLE'], $data_sql['training_vs']),
				'L_EXPLAIN'	=> $lang['explain_u'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'L_NO_PLAYER'	=>	$lang['no_player'],
				
				'L_PLAYER'		=> $lang['subscriber'],
			
				'L_SY'			=> $lang['status_yes'],
				'L_SN'			=> $lang['status_no'],
				'L_SR'			=> $lang['status_replace'],
				
				'L_CREATE'		=> $lang['CREATE'],
				'L_UPDATE'		=> $lang['UPDATE'],
				
				'L_PLAYER_ADD'			=> $lang['subscriber_add'],
				'L_PLAYER_ADD_EXPLAIN'	=> $lang['subscriber_add_explain'],
				'L_PLAYER_STATUS'		=> $lang['subscriber_set_status'],
				
				'S_OPTIONS'	=> $s_options,
				
				'S_ADDED'	=> $s_team_member,
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
			
		case 'delete':
		
			$data_sql = data(TRAINING, $data, false, 1, 'row');
		
			if ( $data && $accept && acl_auth('a_training_delete') )
			{
				$file = ( $index ) ? check_sid('index.php') : check_sid($file);
				$name = ( $index ) ? $lang['acp_overview'] : $_top;
				
				$sql = sql(TRAINING, $mode, $data_sql, 'training_id', $data);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $name);
				
			#	sql(COMMENTS, $mode, $data_sql, 'training_id', $data);
			#	sql(COMMENTS_READ, $mode, $data_sql, 'training_id', $data);
			#	sql(TRAINING_USERS, $mode, false, 'training_id', $data);
					
			#	$oCache -> deleteCache('subnavi_training_*');
				
				log_add(LOG_ADMIN, $log, $mode, $dsql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept && $userauth['a_training_delete'] )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
					'index'	=> $index,
				));
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['training_vs']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}
			
			break;
						
		default:
		
			$template->assign_block_vars('display', array());
			
			$upcoming = $cnt_upcoming = $expired = $cnt_expired = '';
			$cnt = 0;
			$current_page = 1;
			
			$select_id = ( $t_id > 0 ) ? "AND tr.team_id = $t_id" : '';
			
			$sql = "SELECT tr.*, g.game_image
						FROM " . TRAINING . " tr, " . TEAMS . " t, " . GAMES . " g
							WHERE tr.team_id = t.team_id AND t.team_game = g.game_id $select_id
						ORDER BY training_date DESC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$sqlout = $db->sql_fetchrowset($result);
			
			if ( !$sqlout )
			{
				$template->assign_block_vars('display.upcoming_none', array());
				$template->assign_block_vars('display.expired_none', array());
			}
			else
			{
				foreach ( $sqlout as $data => $row )
				{
					if ( $row['training_date'] > $time )
					{
						$upcoming[] = $row;
					}
					else if ( $row['training_date'] < $time )
					{
						$expired[] = $row;
					}
				}
				
				if ( !$upcoming )
				{
					$template->assign_block_vars('display.upcoming_none', array());
				}
				else
				{
					foreach ( $upcoming as $row )
					{
						$id = $row['training_id'];
						$vs = $row['training_vs'];
						
						$template->assign_block_vars('display.upcoming', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $vs, $vs),
							'GAME'		=> display_gameicon($row['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $row['training_date'], $userdata['user_timezone']),
							
							'MEMBER'	=> href('a_img', $file, array('mode' => 'member', 'id' => $id), 'icon_member', 'common_member'),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
				
				if ( !$expired )
				{
					$template->assign_block_vars('display.expired_none', array());
				}
				else
				{
					$cnt = count($expired);
					
					for ($i = $start; $i < min($settings['ppe_acp'] + $start, $cnt); $i++)
					{
						$id = $expired[$i]['training_id'];
						$vs = $expired[$i]['training_vs'];
						
						$template->assign_block_vars('display.expired', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $vs, $vs),
							'GAME'		=> display_gameicon($expired[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $expired[$i]['training_date'], $userdata['user_timezone']),
							
							'MEMBER'	=> href('a_img', $file, array('mode' => 'member', 'id' => $id), 'icon_member', 'common_member'),							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
				
				$current_page = (!$cnt) ? 1 : ceil($cnt/$settings['ppe_acp']);
			}
			
			$fields .= build_fields(array('mode' => 'create'));
			
			$sqlteams = "SELECT * FROM " . TEAMS . " ORDER BY team_order";
			if ( !($result = $db->sql_query($sqlteams)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
		#	$sort[] = href((($t_id != 0) ? 'AHREF_TXT' : 'AHREF_TXT_B'), $file, array('t_id' => 0), $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$sort[] = href((($t_id != $row['team_id']) ? 'AHREF_TXT' : 'AHREF_TXT_B'), $file, array('t_id' => $row['team_id']), $row['team_name'], $row['team_name']);
			}
					
			$sort = implode(', ', $sort);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['TITLE']),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_SORT'	=> sprintf($lang['STF_COMMON_SORT'], $sort),
				
				'L_EXPLAIN'		=> $lang['EXPLAIN'],
				'L_UPCOMING'	=> $lang['UPCOMING'],
				'L_EXPIRED'		=> $lang['EXPIRED'],
				
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['ppe_acp'] ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination($file . (empty($t_id) ? '' : "&t_id=$t_id"), $cnt, $settings['ppe_acp'], $start ),
				
				'S_SORT'	=> s_team($t_id, '', 't_id', 'submit', 'selectsmall'),
				'S_TEAM'	=> s_team($t_id, '', 'id', false, 'selectsmall'),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
		break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>