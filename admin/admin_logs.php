<?php

/***

	
	admin_games.php
	
	Erstellt von Phoenix
	
	
***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($userdata['user_level'] == ADMIN)
	{
		$module['logs']['logs_over']	= $filename;
		$module['logs']['logs_db']		= $filename . "?mode=db";
	//	$module['logs']['logs_admin']	= $filename . "?mode=admin";
	//	$module['logs']['logs_member']	= $filename . "?mode=member";
	//	$module['logs']['logs_user']	= $filename . "?mode=user";
	}

	return;
}
else
{
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	
	if ($userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}

	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_teams.php", true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_LOG_URL]) || isset($HTTP_GET_VARS[POST_LOG_URL]) )
	{
		$team_id = ( isset($HTTP_POST_VARS[POST_LOG_URL]) ) ? intval($HTTP_POST_VARS[POST_LOG_URL]) : intval($HTTP_GET_VARS[POST_LOG_URL]);
	}
	else
	{
		$team_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		$mode = '';
	}
	
	$show_index = '';
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $team_id && $confirm )
				{
					$sql = 'SELECT * FROM ' . TEAMS_TABLE . " WHERE team_id = $team_id";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Error getting team information', '', __LINE__, __FILE__, $sql);
					}
			
					if (!($team_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
				
					$sql = 'DELETE FROM ' . TEAMS_TABLE . " WHERE team_id = $team_id";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete team', '', __LINE__, __FILE__, $sql);
					}
					
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAM, ACP_TEAM_DELETE, $team_info['team_name']);
					
					$message = $lang['team_delete'] . '<br /><br />' . sprintf($lang['click_admin_index'], '<a href="' . append_sid("index.php?pane=right") . '">', '</a>')
						. "<br /><br />" . sprintf($lang['click_return_team'], '<a href="' . append_sid("admin_teams.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
		
				}
				else if ( $team_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_LOG_URL . '" value="' . $team_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_team'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_teams.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['Must_select_team']);
				}
			
				$template->pparse("body");
				
			break;
			
			case 'db':
				
				$template->set_filenames(array('body' => './../admin/style/logs_db_body.tpl'));
			
				$sql = 'SELECT * FROM ' . ERROR_TABLE . ' ORDER BY error_time DESC';
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not obtain list', '', __LINE__, __FILE__, $sql);
				}
				
				$log_entry = $db->sql_fetchrowset($result); 
				$log_count = count($log_entry);
				$db->sql_freeresult($result);
				
				for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, $log_count); $i++)
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					$error_id			= $log_entry[$i]['error_id'];
					$error_userid		= $log_entry[$i]['error_userid'];
					$error_msg_title	= $log_entry[$i]['error_msg_title'];
					$error_msg_text		= $log_entry[$i]['error_msg_text'];
					$error_sql_code		= $log_entry[$i]['error_sql_code'];
					$error_sql_text		= $log_entry[$i]['error_sql_text'];
					$error_sql_store	= $log_entry[$i]['error_sql_store'];
					$error_file			= str_replace(array(phpbb_realpath($root_path), '\\'), array('', '/'), $log_entry[$i]['error_file']);
					$error_file_line	= $log_entry[$i]['error_file_line'];
					$error_time			= create_date($config['default_dateformat'], $log_entry[$i]['error_time'], $config['board_timezone']);
					
					$template->assign_block_vars('logs_row', array(
						'CLASS'		=> $class,
						'L_DELETE' => $lang['Delete'],
						'TIME' => $error_time,
						'ERROR_ID' => $error_id,
						'ERROR_FILE' => $error_file,
						'ERROR_FILE_LINE' => $error_file_line,
						'ERROR_USERID' => $error_userid,
						'ERROR_MSG_TITLE' => $error_msg_title,
						'ERROR_MSG_TEXT' => $error_msg_text,
						'ERROR_SQL_CODE' => $error_sql_code,
						'ERROR_SQL_TEXT' => $error_sql_text,
						'ERROR_SQL_STORE' => $error_sql_store,
						'DELETE' => append_sid("admin_error_log.$phpEx?mode=delete&id=$error_id")
					));
				}
			
				if ( !$log_count )
				{
					$template->assign_block_vars('no_entry', array());
					$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
				}
				
				$current_page = ( !$log_count ) ? 1 : ceil( $log_count / $settings['site_entry_per_page'] );
			
				$template->assign_vars(array(
					'PAGINATION' => generate_pagination("admin_logs.php?", $log_count, $settings['site_entry_per_page'], $start),
					'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 
			
					'L_GOTO_PAGE' => $lang['Goto_page'])
				);
				
				$template->pparse("body");
				
			
			break;
			
			default:
				message_die(GENERAL_ERROR, $lang['no_mode']);
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/logs_body.tpl'));
			
	$template->assign_vars(array(
		'L_TEAM_TITLE'			=> $lang['team_head'],
		'L_TEAM_EXPLAIN'		=> $lang['team_explain'],
		
		'L_TEAM_CREATE'			=> $lang['team_add'],
		'L_TEAM_NAME'			=> $lang['team_name'],
		'L_TEAM_GAME'			=> $lang['team_game'],
		'L_TEAM_MEMBERCOUNT'	=> $lang['team_membercount'],
		'L_TEAM_SETTINGS'		=> $lang['settings'],
		'L_TEAM_SETTING'		=> $lang['setting'],
		'L_TEAM_MEMBER'			=> $lang['team_member'],
		
		'L_DELETE'				=> $lang['delete'],
		
		'L_MOVE_UP'				=> $lang['Move_up'], 
		'L_MOVE_DOWN'			=> $lang['Move_down'], 
		
		'ICON_MOVE_UP'			=> '<img src="./../admin/style/images/icon_arrow_up.png" alt="Up" title="" width="12" height="12" />',
		'ICON_MOVE_DOWN'		=> '<img src="./../admin/style/images/icon_arrow_down.png" alt="Down" title="" width="12" height="12" />',
		
		'S_TEAM_ACTION'		=> append_sid("admin_teams.php")
	));
	
	$sql = 'SELECT l.*, u.username FROM ' . LOG_TABLE . ' l, ' . USERS_TABLE . ' u WHERE l.user_id = u.user_id ORDER BY log_id DESC';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
	}
	
	$log_entry = $db->sql_fetchrowset($result); 
	$log_count = count($log_entry);
	$db->sql_freeresult($result);
	
	for($i = $start; $i < min($settings['site_entry_per_page'] + $start, $log_count); $i++)
	{
		$class = ($i % 2) ? 'row_class1' : 'row_class2';
		
		// Log Sektion
		switch ($log_entry[$i]['log_sektion'])
		{
			case 0:
				$sektion = 'news';
				break;
			case 1:
				$sektion = 'team';
				break;
			case 2:
				$sektion = 'rank';
				break;
			case 3:
				$sektion = 'user';
				break;
			default:
				$sektion = $log_entry[$i]['log_sektion'];
				break;
				
		}

		$template->assign_block_vars('logs_row', array(
			'CLASS'		=> $class,
			'USERNAME'	=> $log_entry[$i]['username'],
			'IP'		=> decode_ip($log_entry[$i]['user_ip']),
			'DATE'		=> create_date($userdata['user_dateformat'], $log_entry[$i]['log_time'], $userdata['user_timezone']),
			'SEKTION'	=> $sektion,
			'MESSAGE'	=> $log_entry[$i]['log_message'],
			'DATA'		=> $log_entry[$i]['log_data']
		));
	}

	if ( !$log_count )
	{
		//
		// No group members
		//
		$template->assign_block_vars('no_teams', array());
		$template->assign_vars(array('NO_TEAMS' => $lang['team_empty']));
	}
	
	$current_page = ( !$log_count ) ? 1 : ceil( $log_count / $settings['site_entry_per_page'] );

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("admin_logs.php?", $log_count, $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);
/*		
	while ( $row = $db->sql_fetchrow($result) )
	{
		$class = ($color % 2) ? 'row_class1' : 'row_class2';
		$color++;
		
		$template->assign_block_vars('logs_row', array(
			'CLASS' 		=> $class,
			'ID'			=> $row['team_id'],
			'NAME'			=> $row['team_name'],
			
			'U_MEMBER'		=> append_sid("admin_teams.php?mode=member&amp;" . POST_LOG_URL . "=".$row['team_id']),
			'U_DELETE'		=> append_sid("admin_teams.php?mode=delete&amp;" . POST_LOG_URL . "=".$row['team_id']),
			'U_EDIT'		=> append_sid("admin_teams.php?mode=edit&amp;" . POST_LOG_URL . "=".$row['team_id']),
			'U_MOVE_UP'		=> append_sid("admin_teams.php?mode=team_order&amp;move=-15&amp;" . POST_LOG_URL . "=".$row['team_id']),
			'U_MOVE_DOWN'	=> append_sid("admin_teams.php?mode=team_order&amp;move=15&amp;" . POST_LOG_URL . "=".$row['team_id'])
		));
	}

	if ($db->sql_numrows($result) == 0)
	{
		$template->assign_block_vars('no_teams', array());
		$template->assign_vars(array('NO_TEAMS' => $lang['team_empty']));
	}

	if ($db->sql_numrows($result))
	{
		$lookup = $cached_group_data = array();
		while ($row = $db->sql_fetchrow($result))
		{
			// used to determine what type a group is
			$lookup[$row['team_id']] = $type;
	
			// used for easy access to the data within a group
			$cached_group_data[$type][$row['team_id']] = $row;
			$cached_group_data[$type][$row['team_id']]['total_members'] = 0;
		}
		$db->sql_freeresult($result);
		
		$sql = 'SELECT COUNT(tu.user_id) AS total_members, tu.team_id FROM ' . TEAMS_USERS_TABLE . ' tu WHERE tu.team_id IN (' . implode(', ', array_keys($lookup)) . ') GROUP BY tu.team_id';
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain rank list', '', __LINE__, __FILE__, $sql);
		}
		
		while ($row = $db->sql_fetchrow($result))
		{
			$type = $lookup[$row['team_id']];
			$cached_group_data[$type][$row['team_id']]['total_members'] = $row['total_members'];
		}
		$db->sql_freeresult($result);

		foreach ($cached_group_data as $type => $row_ary)
		{
			foreach ($row_ary as $team_id => $row)
			{
				$template->assign_block_vars('teams_row', array(
					'TEAM_NAME'			=> $row['team_name'],
					'TEAM_ICON'			=> $row['team_name'],
					'TEAM_MEMBER_COUNT'	=> $row['total_members'],
					
					'U_MEMBER'			=> append_sid("admin_teams.php?mode=member&amp;" . POST_LOG_URL . "=".$row['team_id']),
					'U_EDIT'			=> append_sid("admin_teams.php?mode=edit&amp;" . POST_LOG_URL . "=".$row['team_id']),
					'U_MOVE_UP'			=> append_sid("admin_teams.php?mode=order&amp;move=-15&amp;" . POST_LOG_URL . "=".$row['team_id']),
					'U_MOVE_DOWN'		=> append_sid("admin_teams.php?mode=order&amp;move=15&amp;" . POST_LOG_URL . "=".$row['team_id']),
					'U_DELETE'			=> append_sid("admin_teams.php?mode=delete&amp;" . POST_LOG_URL . "=".$row['team_id'])
				));
			}
		}
	}
	else
	{
		$template->assign_block_vars('no_teams', array());
		$template->assign_vars(array('NO_TEAMS' => $lang['team_empty']));
	}
	*/
	$template->pparse("body");
			
	include('./page_footer_admin.php');
}
?>