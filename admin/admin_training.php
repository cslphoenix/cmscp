<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_training',
		'cat'		=> 'clan',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_training'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_training';
	
	include('./pagestart.php');
	
	add_lang('training');
	acl_auth(array('a_training', 'a_training_create', 'a_training_manage', 'a_training_delete'));
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$log	= SECTION_TRAINING;
	$file	= basename(__FILE__) . $iadds;
	
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

	$acp_title	= sprintf($lang['stf_header'], $lang['title']);
	
	( $cancel && !$index )	? redirect('admin/' . check_sid((basename(__FILE__) . $iadds . ($mode ? "?mode=$mode&id=$data" : '')))) : false;
	( $cancel && $index )	? redirect('admin/' . check_sid('admin_index.php', true)) : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
		
	$base = $settings['switch']['training'];
	$comt = $settings['comments']['training'];
	
	$mode = (in_array($mode, array('create', 'delete', 'member', 'update'))) ? $mode : false;
	$_tpl = ($mode === 'delete' || $smode === 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());

			$vars = array(
				'training' => array(
					'title' => 'input_data',
					'training_vs'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',		'required' => 'input_rival'),
					'team_id'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:team',		'required' => 'select_team', 'params' => array('request', 'training_maps')),
					'match_id'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:match'),
					'training_maps'		=> array('validate' => ARY,	'explain' => false,	'type' => 'drop:maps',		'required' => 'select_maps'),
					'training_date'		=> array('validate' => ($base ? INT : TXT), 'type' => ($base ? 'drop:datetime' : 'text:25;25'), 'params' => ($base ? (($mode == 'create') ? $time : '-1') : 'format')),
					'training_duration'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:duration',	'params' => 'training_date'),
					'training_text'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40',	'params' => TINY_NORMAL, 'class' => 'tinymce'),
					'training_comments'	=> ( $comt ) ? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
					'time_create'		=> 'hidden',
					'time_update'		=> 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'training_vs'		=> ( request('training_vs', TXT) ) ? request('training_vs', TXT) : request('vs', TXT),
					'team_id'			=> $t_id,
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
				$data_sql = data(TRAINING, $data, false, 1, true);
				$option[] = href('a_txt', $file, array('mode' => 'member', 'id' => $data), $lang['subscriber'], $lang['subscriber']);
			}
			else
			{
				$data_sql = build_request(TRAINING, $vars, $error, $mode);
				
				if ( !$error )
				{
				#	$data_sql['training_maps'] = is_array($data_sql['training_maps']) ? serialize($data_sql['training_maps']) : array();
					
					if ( $mode == 'create' && $userauth['a_training_create'] )
					{
						$sql = sql(TRAINING, $mode, $data_sql);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else if ( $userauth['a_training'] )
					{
						$sql = sql(TRAINING, $mode, $data_sql, 'training_id', $data);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
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
				'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['training_vs']),
				'L_EXPLAIN'	=> $lang['com_required'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),
				
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
							right('ERROR_BOX', lang($lang_type));
						}
						else
						{
							error('ERROR_BOX', $error);
						}
					
					break;
					
				case 'delete':
				
					$data_sql	= data(TEAMS, $data, false, 1, true);
					$members	= request('members', ARY);
					
					if ( $members && $data && $accept && $userauth['a_team_manage'] )
					{
						$sql = "DELETE FROM " . LISTS . " WHERE type = " . TYPE_TRAINING . " AND type_id = $data AND  user_id IN ($members)";
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
							$ary_user_name[] = (string) $row['user_name'];
						}
						$db->sql_freeresult($result);
						
						$user_names = implode(', ', $ary_user_name);
		
						$template->assign_vars(array(
							'M_TITLE'	=> $lang['com_confirm'],
							'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], sprintf($lang['notice_confirm_training'], $user_names), $data_sql['training_vs']),
							
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
			
			$data_sql = data(TRAINING, $data, false, 1, true);
			
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
		
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
			$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['input_data'], $lang['input_data']);
			
			$s_options = build_options(array(
				'smode'		=> false,
				'option'	=> array(
					'select'	=> 'com_select_option',
					'status_y'	=> 'notice_select_sy',
					'status_n'	=> 'notice_select_sn',
					'status_r'	=> 'notice_select_sr',
					'delete'	=> 'com_delete',
				),
			));
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_member'], $lang['title'], $data_sql['training_vs']),
				'L_EXPLAIN'	=> $lang['explain_u'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),
				
				'L_NO_PLAYER'	=>	$lang['no_player'],
				
				'L_PLAYER'		=> $lang['subscriber'],
			
				'L_SY'			=> $lang['status_yes'],
				'L_SN'			=> $lang['status_no'],
				'L_SR'			=> $lang['status_replace'],
				
				'L_CREATE'		=> $lang['create'],
				'L_UPDATE'		=> $lang['update'],
				
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
		
			$data_sql = data(TRAINING, $data, false, 1, true);
		
			if ( $data && $accept && acl_auth('a_training_delete') )
			{
				$file = ( $index ) ? check_sid('admin_index.php') : check_sid($file);
				$name = ( $index ) ? $lang['acp_overview'] : $acp_title;
				
				$sql = sql(TRAINING, $mode, $data_sql, 'training_id', $data);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $name);
				
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
					'M_TITLE'	=> $lang['com_confirm'],
					'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['training_vs']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}
			
			break;
						
		default:
		
			$template->assign_block_vars('display', array());
			
			$new = $cnt_new = $old = $cnt_old = '';
			$cnt = 0;
			$current_page = 1;
			
			$select_id = ( $t_id > 0 ) ? "AND tr.team_id = $t_id" : '';
			
			$sql = "SELECT tr.*, g.game_image
						FROM " . TRAINING . " tr, " . TEAMS . " t, " . GAMES . " g
							WHERE tr.team_id = t.team_id AND t.team_game = g.game_id $select_id
						ORDER BY training_date";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$training = $db->sql_fetchrowset($result);
			
			if ( !$training )
			{
				$template->assign_block_vars('display.new_empty', array());
				$template->assign_block_vars('display.old_empty', array());
			}
			else
			{
				foreach ( $training as $data => $row )
				{
					if ( $row['training_date'] > $time )
					{
						$new[] = $row;
					}
					else if ( $row['training_date'] < $time )
					{
						$old[] = $row;
					}
				}
				
				if ( !$new )
				{
					$template->assign_block_vars('display.new_empty', array());
				}
				else
				{
					foreach ( $new as $row )
					{
						$id = $row['training_id'];
						$vs = $row['training_vs'];
						
						$template->assign_block_vars('display.new_row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $vs, $vs),
							'GAME'		=> display_gameicon($row['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $row['training_date'], $userdata['user_timezone']),
							
							'MEMBER'	=> href('a_img', $file, array('mode' => 'member', 'id' => $id), 'icon_member', 'common_member'),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
						));
					}
				}
				
				if ( !$old )
				{
					$template->assign_block_vars('display.old_empty', array());
				}
				else
				{
					$cnt = count($old);
					
					for ($i = $start; $i < min($settings['ppe_acp'] + $start, $cnt); $i++)
					{
						$id = $old[$i]['training_id'];
						$vs = $old[$i]['training_vs'];
						
						$template->assign_block_vars('display.old_row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $vs, $vs),
							'GAME'		=> display_gameicon($old[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $old[$i]['training_date'], $userdata['user_timezone']),
							
							'MEMBER'	=> href('a_img', $file, array('mode' => 'member', 'id' => $id), 'icon_member', 'common_member'),							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
						));
					}
				}
				
				$current_page = (!$cnt) ? 1 : ceil($cnt/$settings['ppe_acp']);
			}
			
			$fields .= build_fields(array('mode' => 'create'));
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
				'L_CREATE'		=> sprintf($lang['stf_create'], $lang['title']),
				
				'L_EXPLAIN'		=> $lang['explain'],
				'L_UPCOMING'	=> $lang['upcoming'],
				'L_EXPIRED'		=> $lang['expired'],
				
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['ppe_acp'] ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination($file, $cnt, $settings['ppe_acp'], $start ),
				
				'S_SORT'	=> select_team($t_id, '', 't_id', 'submit', 'selectsmall'),
				'S_TEAM'	=> select_team($t_id, '', 't_id', false, 'selectsmall'),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>