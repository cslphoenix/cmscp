<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_teamspeak'] || $userdata['user_level'] == ADMIN)
	{
		$module['server']['teamspeak'] = $filename;
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
	include($root_path . 'includes/class_cyts.php');
	include($root_path . 'includes/functions_admin.php');
	
	if (!$userauth['auth_games'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid("admin_teamspeak.php", true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_TEAMSPEAK_URL]) || isset($HTTP_GET_VARS[POST_TEAMSPEAK_URL]) )
	{
		$teamspeak_id = ( isset($HTTP_POST_VARS[POST_TEAMSPEAK_URL]) ) ? intval($HTTP_POST_VARS[POST_TEAMSPEAK_URL]) : intval($HTTP_GET_VARS[POST_TEAMSPEAK_URL]);
	}
	else
	{
		$teamspeak_id = 0;
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
	
	function ts_time($time)
	{
		$da = substr($time, 0, -15);
		$mo = substr($time, 2, -13);
		$ye = substr($time, 4, -9);
		$ho = substr($time, 8, -7);
		$mi = substr($time, 10, -5);
		$se = substr($time, 12, -3);
		
		$date = mktime($ho, $mi, $se, $mo, $da, $ye);
		
		return $date;
	}
	
	$show_index = '';
	
	if ( !empty($mode) )
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
				
				if ( $mode == 'edit' )
				{
					$teamspeak	= get_data('teamspeak', $teamspeak_id, 0);
					$new_mode	= 'editteamspeak';
				}
				else if ( $mode == 'add' )
				{
					$teamspeak = array (
						'teamspeak_name'		=> '',
						'teamspeak_ip'			=> '',
						'teamspeak_port'		=> '',
						'teamspeak_qport'		=> '',
						'teamspeak_pass'		=> '',
						'teamspeak_join_name'	=> '',
						'teamspeak_cstats'		=> '1',
						'teamspeak_ustats'		=> '1',
						'teamspeak_sstats'		=> '1',
						'teamspeak_infos'		=> '1',
						'teamspeak_plist'		=> '1',
						'teamspeak_mouseover'	=> '0',
						'teamspeak_show'		=> '0',
					);

					$new_mode = 'addteamspeak';
				}
				
				$template->set_filenames(array('body' => './../admin/style/acp_teamspeak.tpl'));
				$template->assign_block_vars('teamspeak_edit', array());
				
				if ( ( $ts_host || $ts_db || $ts_user || $ts_pass || $ts_prefix ) && $userdata['user_level'] == ADMIN )
				{
					$template->assign_block_vars('teamspeak_edit.user', array());
				}
				
				$s_hidden_fields = '';
				$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_TEAMSPEAK_URL . '" value="' . $teamspeak_id . '" />';

				$template->assign_vars(array(
					'L_TEAMSPEAK_HEAD'		=> $lang['teamspeak_head'],
					'L_TEAMSPEAK_NEW_EDIT'	=> ($mode == 'add') ? $lang['teamspeak_add'] : $lang['teamspeak_edit'],
					'L_TEAMSPEAK_USER'		=> $lang['teamspeak_user'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_TEAMSPEAK_NAME'		=> $lang['teamspeak_name'],
					'L_TEAMSPEAK_IP'		=> $lang['teamspeak_ip'],
					'L_TEAMSPEAK_PORT'		=> $lang['teamspeak_port'],
					'L_TEAMSPEAK_QPORT'		=> $lang['teamspeak_qport'],
					'L_TEAMSPEAK_PASS'		=> $lang['teamspeak_pass'],
					
					'L_TEAMSPEAK_CSTATS'	=> $lang['teamspeak_cstats'],
					'L_TEAMSPEAK_USTATS'	=> $lang['teamspeak_ustats'],
					'L_TEAMSPEAK_SSTATS'	=> $lang['teamspeak_sstats'],
					'L_TEAMSPEAK_MOUSEO'	=> $lang['teamspeak_mouseo'],
					'L_TEAMSPEAK_VIEWER'	=> $lang['teamspeak_viewer'],
					'L_TEAMSPEAK_JOIN'		=> $lang['teamspeak_join'],
					
					'L_TEAMSPEAK_SHOW'		=> $lang['teamspeak_show'],
					'L_TEAMSPEAK_NOSHOW'	=> $lang['teamspeak_noshow'],
					
					'L_SUBMIT'				=> $lang['common_submit'],
					'L_RESET'				=> $lang['common_reset'],
					'L_YES'					=> $lang['common_yes'],
					'L_NO'					=> $lang['common_no'],
					
					'TEAMSPEAK_NAME'		=> $teamspeak['teamspeak_name'],
					'TEAMSPEAK_IP'			=> $teamspeak['teamspeak_ip'],
					'TEAMSPEAK_PORT'		=> $teamspeak['teamspeak_port'],
					'TEAMSPEAK_QPORT'		=> $teamspeak['teamspeak_qport'],
					'TEAMSPEAK_PASS'		=> $teamspeak['teamspeak_pass'],
					'TEAMSPEAK_JOIN'		=> $teamspeak['teamspeak_join_name'],
					
					'S_CHECKED_CSTATS_YES'	=> ( $teamspeak['teamspeak_cstats'] ) ? ' checked="checked"' : '',
					'S_CHECKED_CSTATS_NO'	=> ( !$teamspeak['teamspeak_cstats'] ) ? ' checked="checked"' : '',
					'S_CHECKED_USTATS_YES'	=> ( $teamspeak['teamspeak_ustats'] ) ? ' checked="checked"' : '',
					'S_CHECKED_USTATS_NO'	=> ( !$teamspeak['teamspeak_ustats'] ) ? ' checked="checked"' : '',
					'S_CHECKED_SSTATS_YES'	=> ( $teamspeak['teamspeak_sstats'] ) ? ' checked="checked"' : '',
					'S_CHECKED_SSTATS_NO'	=> ( !$teamspeak['teamspeak_sstats'] ) ? ' checked="checked"' : '',
					'S_CHECKED_MOUSEO_YES'	=> ( $teamspeak['teamspeak_mouseover'] ) ? ' checked="checked"' : '',
					'S_CHECKED_MOUSEO_NO'	=> ( !$teamspeak['teamspeak_mouseover'] ) ? ' checked="checked"' : '',
					'S_CHECKED_VIEWER_YES'	=> ( $teamspeak['teamspeak_show'] ) ? ' checked="checked"' : '',
					'S_CHECKED_VIEWER_NO'	=> ( !$teamspeak['teamspeak_show'] ) ? ' checked="checked"' : '',

					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_TEAMSPEAK_MEMBER'	=> append_sid("admin_teamspeak.php?mode=member"),
					'S_TEAMSPEAK_ACTION'	=> append_sid("admin_teamspeak.php"),
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addteamspeak':

				$teamspeak_ip			= ( isset($HTTP_POST_VARS['teamspeak_ip']) )			? trim($HTTP_POST_VARS['teamspeak_ip']) : '';
				$teamspeak_port			= ( isset($HTTP_POST_VARS['teamspeak_port']) )			? trim($HTTP_POST_VARS['teamspeak_port']) : '';
				$teamspeak_qport		= ( isset($HTTP_POST_VARS['teamspeak_qport']) )			? trim($HTTP_POST_VARS['teamspeak_qport']) : '';
				$teamspeak_pass			= ( isset($HTTP_POST_VARS['teamspeak_pass']) )			? trim($HTTP_POST_VARS['teamspeak_pass']) : '';
				$teamspeak_name			= ( isset($HTTP_POST_VARS['teamspeak_name']))			? trim($HTTP_POST_VARS['teamspeak_name']) : '';
				$teamspeak_join_name	= ( isset($HTTP_POST_VARS['teamspeak_join_name']) )		? trim($HTTP_POST_VARS['teamspeak_join_name']) : '0';
				$teamspeak_cstats		= ( isset($HTTP_POST_VARS['teamspeak_cstats']) )		? intval($HTTP_POST_VARS['teamspeak_cstats']) : '0';
				$teamspeak_ustats		= ( isset($HTTP_POST_VARS['teamspeak_ustats']) )		? intval($HTTP_POST_VARS['teamspeak_ustats']) : '0';
				$teamspeak_sstats		= ( isset($HTTP_POST_VARS['teamspeak_sstats']) )		? intval($HTTP_POST_VARS['teamspeak_sstats']) : '0';
				$teamspeak_plist		= ( isset($HTTP_POST_VARS['teamspeak_plist']) )			? intval($HTTP_POST_VARS['teamspeak_plist']) : '0';
				$teamspeak_mouseover	= ( isset($HTTP_POST_VARS['teamspeak_mouseover']) )		? intval($HTTP_POST_VARS['teamspeak_mouseover']) : '0';
				$teamspeak_show			= ( isset($HTTP_POST_VARS['teamspeak_show']) )			? intval($HTTP_POST_VARS['teamspeak_show']) : '0';
				
				if ( $teamspeak_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				
				if ( $teamspeak_show )
				{
					$sql = 'SELECT * FROM ' . TEAMSPEAK . ' WHERE teamspeak_show = 1';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$teamspeak = $db->sql_fetchrow($result);
					
					if ( $teamspeak )
					{
						$sql = 'UPDATE ' . TEAMSPEAK . ' SET teamspeak_show = 0 WHERE teamspeak_id = ' . $teamspeak['teamspeak_id'];
						if (!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
				}
	
				$sql = 'INSERT INTO ' . TEAMSPEAK . " (teamspeak_ip, teamspeak_port, teamspeak_qport, teamspeak_pass, teamspeak_name, teamspeak_join_name, teamspeak_cstats, teamspeak_ustats, teamspeak_sstats, teamspeak_plist, teamspeak_mouseover, teamspeak_show)
					VALUES (	'" . str_replace("\'", "''", $teamspeak_ip) . "',
								'" . str_replace("\'", "''", $teamspeak_port) . "',
								'" . str_replace("\'", "''", $teamspeak_qport) . "',
								'" . str_replace("\'", "''", $teamspeak_pass) . "',
								'" . str_replace("\'", "''", $teamspeak_name) . "',
								'" . str_replace("\'", "''", $teamspeak_join_name) . "',
								$teamspeak_cstats,
								$teamspeak_ustats,
								$teamspeak_sstats,
								$teamspeak_plist,
								$teamspeak_mouseover,
								$teamspeak_cstats,
								$teamspeak_show
							)";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('teamspeak_data');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAMSPEAK, 'acp_teamspeak_add');
	
				$message = $lang['create_teamspeak'] . '<br><br>' . sprintf($lang['click_return_teamspeak'], '<a href="' . append_sid("admin_teamspeak.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editteamspeak':
			
				$teamspeak_ip			= ( isset($HTTP_POST_VARS['teamspeak_ip']) )			? trim($HTTP_POST_VARS['teamspeak_ip']) : '';
				$teamspeak_port			= ( isset($HTTP_POST_VARS['teamspeak_port']) )			? trim($HTTP_POST_VARS['teamspeak_port']) : '';
				$teamspeak_qport		= ( isset($HTTP_POST_VARS['teamspeak_qport']) )			? trim($HTTP_POST_VARS['teamspeak_qport']) : '';
				$teamspeak_pass			= ( isset($HTTP_POST_VARS['teamspeak_pass']) )			? trim($HTTP_POST_VARS['teamspeak_pass']) : '';
				$teamspeak_name			= ( isset($HTTP_POST_VARS['teamspeak_name']))			? trim($HTTP_POST_VARS['teamspeak_name']) : '';
				$teamspeak_join_name	= ( isset($HTTP_POST_VARS['teamspeak_join_name']) )		? trim($HTTP_POST_VARS['teamspeak_join_name']) : '0';
				$teamspeak_cstats		= ( isset($HTTP_POST_VARS['teamspeak_cstats']) )		? intval($HTTP_POST_VARS['teamspeak_cstats']) : '0';
				$teamspeak_ustats		= ( isset($HTTP_POST_VARS['teamspeak_ustats']) )		? intval($HTTP_POST_VARS['teamspeak_ustats']) : '0';
				$teamspeak_sstats		= ( isset($HTTP_POST_VARS['teamspeak_sstats']) )		? intval($HTTP_POST_VARS['teamspeak_sstats']) : '0';
				$teamspeak_plist		= ( isset($HTTP_POST_VARS['teamspeak_plist']) )			? intval($HTTP_POST_VARS['teamspeak_plist']) : '0';
				$teamspeak_mouseover	= ( isset($HTTP_POST_VARS['teamspeak_mouseover']) )		? intval($HTTP_POST_VARS['teamspeak_mouseover']) : '0';
				$teamspeak_show			= ( isset($HTTP_POST_VARS['teamspeak_show']) )			? intval($HTTP_POST_VARS['teamspeak_show']) : '0';
				
				if ( $teamspeak_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				
				if ( $teamspeak_show )
				{
					$sql = 'SELECT * FROM ' . TEAMSPEAK . ' WHERE teamspeak_show = 1';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$teamspeak = $db->sql_fetchrow($result);
					
					if ( $teamspeak )
					{
						$sql = 'UPDATE ' . TEAMSPEAK . ' SET teamspeak_show = 0 WHERE teamspeak_id = ' . $teamspeak['teamspeak_id'];
						if (!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
				}
	
				$sql = 'UPDATE ' . TEAMSPEAK . "
							SET
								teamspeak_ip			= '" . str_replace("\'", "''", $teamspeak_ip) . "',
								teamspeak_port			= '" . str_replace("\'", "''", $teamspeak_port) . "',
								teamspeak_qport			= '" . str_replace("\'", "''", $teamspeak_qport) . "',
								teamspeak_pass			= '" . str_replace("\'", "''", $teamspeak_pass) . "',
								teamspeak_name			= '" . str_replace("\'", "''", $teamspeak_name) . "',
								teamspeak_join_name		= '" . str_replace("\'", "''", $teamspeak_join_name) . "',
								teamspeak_cstats		= $teamspeak_cstats,
								teamspeak_ustats		= $teamspeak_ustats,
								teamspeak_sstats		= $teamspeak_sstats,
								teamspeak_plist			= $teamspeak_plist,
								teamspeak_mouseover		= $teamspeak_mouseover,
								teamspeak_show			= $teamspeak_show
							WHERE teamspeak_id = " . $teamspeak_id;
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('teamspeak_data');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAMSPEAK, 'acp_teamspeak_edit');
				
				$message = $lang['update_teamspeak'] . '<br><br>' . sprintf($lang['click_return_teamspeak'], '<a href="' . append_sid("admin_teamspeak.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
				
			case 'member':
				
				$template->set_filenames(array('body' => './../admin/style/acp_teamspeak.tpl'));
				$template->assign_block_vars('teamspeak_member', array());
				
				if ( $ts_host || $ts_db || $ts_user || $ts_pass || $ts_prefix )
				{
					$db->sql_close();
					
					$db->sql_db($ts_host, $ts_user, $ts_pass, $ts_db, true);
					
					$sql = 'SELECT * FROM ' . $ts_prefix . 'clients WHERE i_client_server_id = ' . $ts_server;
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$teamspeak_user = $db->sql_fetchrowset($result);
					
					$db->sql_close();
					
					include($root_path . 'includes/config.php');
					include($root_path . 'includes/db.php');
				}
				
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($teamspeak_user)); $i++)
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					if ( $teamspeak_user[$i]['b_client_privilege_serveradmin'] == '-1' )
					{
						$edit	= ( $userdata['user_level'] == ADMIN ) ? '<a href="' . append_sid("admin_teamspeak.php?mode=useredit&amp;" . POST_TEAMSPEAK_URL . "=" . $teamspeak_user[$i]['i_client_id']) . '">' . $lang['edit'] . '</a>' : $lang['edit'];
						$delete	= ( $userdata['user_level'] == ADMIN ) ? '<a href="' . append_sid("admin_teamspeak.php?mode=userdelete&amp;" . POST_TEAMSPEAK_URL . "=" . $teamspeak_user[$i]['i_client_id']) . '">' . $lang['delete'] . '</a>' : $lang['delete'];
					}
					else
					{
						$edit	= ( $userauth['auth_user'] || $userdata['user_level'] == ADMIN ) ? '<a href="' . append_sid("admin_teamspeak.php?mode=useredit&amp;" . POST_TEAMSPEAK_URL . "=" . $teamspeak_user[$i]['i_client_id']) . '">' . $lang['edit'] . '</a>' : $lang['edit'];
						$delete	= ( $userauth['auth_user'] || $userdata['user_level'] == ADMIN ) ? '<a href="' . append_sid("admin_teamspeak.php?mode=userdelete&amp;" . POST_TEAMSPEAK_URL . "=" . $teamspeak_user[$i]['i_client_id']) . '">' . $lang['delete'] . '</a>' : $lang['delete'];
					}
					
					$template->assign_block_vars('teamspeak_member.member_row', array(
						'CLASS' 		=> $class,
						'USER_ID'		=> $teamspeak_user[$i]['i_client_id'],
						'USERNAME'		=> $teamspeak_user[$i]['s_client_name'],
						'REGISTER'		=> create_date($userdata['user_dateformat'], ts_time($teamspeak_user[$i]['dt_client_created']), $userdata['user_timezone']),
						'LASTVIEW'		=> ( $teamspeak_user[$i]['dt_client_lastonline'] ) ? create_date($userdata['user_dateformat'], ts_time($teamspeak_user[$i]['dt_client_lastonline']), $userdata['user_timezone']) : '',
						
						'USER_LEVEL'	=> ( $teamspeak_user[$i]['b_client_privilege_serveradmin'] == '-1' ) ? $lang['auth_admin'] : $lang['auth_user'],
						
						'EDIT'			=> $edit,
						'DELETE'		=> $delete,
					));
				}
				
				$current_page = ( !count($teamspeak_user) ) ? 1 : ceil( count($teamspeak_user) / $settings['site_entry_per_page'] );

				$template->assign_vars(array(
					'L_TEAMSPEAK_TITLE'		=> $lang['teamspeak_head'],
					'L_TEAMSPEAK_NEW_EDIT'	=> $lang['teamspeak_edit'],
					'L_TEAMSPEAK_USER'		=> $lang['teamspeak_user'],
					'L_GOTO_PAGE'			=> $lang['Goto_page'],
					
					'PAGINATION'			=> generate_pagination("admin_teamspeak.php?", count($teamspeak_user), $settings['site_entry_per_page'], $start),
					'PAGE_NUMBER'			=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
					
					'S_TEAMSPEAK_EDIT'			=> append_sid("admin_teamspeak.php?mode=edit&amp;" . POST_TEAMSPEAK_URL . "=" . $teamspeak_id),
					'S_TEAMSPEAK_ACTION'		=> append_sid("admin_teamspeak.php"),
				));
				
				$template->pparse('body');
				
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $teamspeak_id && $confirm )
				{
					$teamspeak = get_data('teamspeak', $teamspeak_id, 0);
				
					$sql = 'DELETE FROM ' . TEAMSPEAK . ' WHERE teamspeak_id = ' .$teamspeak_id;
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TEAMSPEAK, 'acp_teamspeak_delete', $teamspeak['teamspeak_name']);
					
					$message = $lang['delete_teamspeak'] . '<br><br>' . sprintf($lang['click_return_teamspeak'], '<a href="' . append_sid("admin_teamspeak.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $teamspeak_id && !$confirm )
				{
					$template->set_filenames(array('body' => './../admin/style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_TEAMSPEAK_URL . '" value="' . $teamspeak_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_teamspeak'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_teamspeak.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_teamspeak']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
			
				message_die(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_teamspeak.tpl'));
	$template->assign_block_vars('display', array());
	
	if ( ( $ts_host || $ts_db || $ts_user || $ts_pass || $ts_prefix ) && $userdata['user_level'] == ADMIN )
	{
		$template->assign_block_vars('display.user', array());
	}
	
	$sql = 'SELECT * FROM ' . TEAMSPEAK . ' WHERE teamspeak_show = 1';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$teamspeak = $db->sql_fetchrow($result);
	
	$sql = 'SELECT * FROM ' . TEAMSPEAK;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$color = '0';
	
	while ( $teamspeak_data = $db->sql_fetchrow($result) )
	{
		$class = ($color % 2) ? 'row_class1' : 'row_class2';
		$color++;
		
		$template->assign_block_vars('display.ts_data', array(
			'CLASS'		=> $class,
			'TS_NAME'	=> $teamspeak_data['teamspeak_name'],
			'TS_IP'		=> $teamspeak_data['teamspeak_ip'],
			'TS_PORT'	=> $teamspeak_data['teamspeak_port'],
			'TS_VIEW'	=> ( $teamspeak_data['teamspeak_show'] ) ? $lang['Enabled'] : $lang['Disabled'],
			
			'U_DELETE'	=> append_sid("admin_teamspeak.php?mode=delete&amp;" . POST_TEAMSPEAK_URL . "=" . $teamspeak_data['teamspeak_id']),
			'U_EDIT'	=> append_sid("admin_teamspeak.php?mode=edit&amp;" . POST_TEAMSPEAK_URL . "=" . $teamspeak_data['teamspeak_id']),
		));
	}
	
	if ( !$db->sql_numrows($result) )
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$s_hidden_fields = '<input type="hidden" name="mode" value="edit" />';
	$s_hidden_fields .= '<input type="hidden" name="' . POST_TEAMSPEAK_URL . '" value="' . $teamspeak['teamspeak_id'] . '" />';
	
	$template->assign_vars(array(
		'L_TEAMSPEAK_TITLE'		=> $lang['teamspeak_head'],
		'L_TEAMSPEAK_EXPLAIN'	=> $lang['teamspeak_explain'],
		'L_TEAMSPEAK_ADD'		=> $lang['teamspeak_add'],
		'L_TEAMSPEAK_EDIT'		=> $lang['teamspeak_edit'],
		'L_TEAMSPEAK_NEW_EDIT'	=> ( !$teamspeak ) ? $lang['teamspeak_add'] : $lang['teamspeak_edit'],
		'L_TEAMSPEAK_CURRENT'	=> $lang['teamspeak_current'],
		'L_TEAMSPEAK_SERVER'	=> $lang['teamspeak_server'],
		
		'L_TEAMSPEAK_USER'		=> $lang['teamspeak_user'],
		
		'L_EDIT'				=> $lang['edit'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
		'S_TEAMSPEAK_EDIT'		=> append_sid("admin_teamspeak.php?mode=edit&amp;" . POST_TEAMSPEAK_URL . "=" . $teamspeak['teamspeak_id']),
		'S_TEAMSPEAK_MEMBER'	=> append_sid("admin_teamspeak.php?mode=member&amp;" . POST_TEAMSPEAK_URL . "=" . $teamspeak['teamspeak_id']),
		'S_TEAMSPEAK_ACTION'	=> append_sid("admin_teamspeak.php")
	));
	
	if ( $teamspeak )
	{
		$template->assign_block_vars('display.server', array());
		
		$cyts = new cyts;
		$cyts->connect($teamspeak['teamspeak_ip'], $teamspeak['teamspeak_qport'], $teamspeak['teamspeak_port']);
		$info = $cyts->info_serverInfo();
		
		if ( !$info )
		{
			$template->assign_block_vars('display.server.off', array());
		}
		else
		{
			$template->assign_vars(array(
				'L_SERVER_NAME'				=> $lang['teamspeak_server_name'],
				'L_SERVER_PLATFORM'			=> $lang['teamspeak_server_platform'],
				'L_SERVER_WELCOME_MSG'		=> $lang['teamspeak_server_welcomemessage'],			
				'L_SERVER_WEB_LINK'			=> $lang['teamspeak_server_webpost_linkurl'],
				'L_SERVER_WEB_POST'			=> $lang['teamspeak_server_webpost_posturl'],
				'L_SERVER_PASSWORD'			=> $lang['teamspeak_server_password'],
				'L_SERVER_TYPE'				=> $lang['teamspeak_server_clan_server'],
				'L_SERVER_USER_MAX'			=> $lang['teamspeak_server_maxusers'],
				'L_SERVER_USER_CURRENT'		=> $lang['teamspeak_server_currentusers'],
				'L_SERVER_CODEC_1'			=> $lang['teamspeak_server_allow_codec_celp51'],
				'L_SERVER_CODEC_2'			=> $lang['teamspeak_server_allow_codec_celp63'],
				'L_SERVER_CODEC_3'			=> $lang['teamspeak_server_allow_codec_gsm148'],
				'L_SERVER_CODEC_4'			=> $lang['teamspeak_server_allow_codec_gsm164'],
				'L_SERVER_CODEC_5'			=> $lang['teamspeak_server_allow_codec_windowscelp52'],
				'L_SERVER_CODEC_6'			=> $lang['teamspeak_server_allow_codec_speex2150'],
				'L_SERVER_CODEC_7'			=> $lang['teamspeak_server_allow_codec_speex3950'],
				'L_SERVER_CODEC_8'			=> $lang['teamspeak_server_allow_codec_speex5950'],
				'L_SERVER_CODEC_9'			=> $lang['teamspeak_server_allow_codec_speex8000'],
				'L_SERVER_CODEC_10'			=> $lang['teamspeak_server_allow_codec_speex11000'],
				'L_SERVER_CODEC_11'			=> $lang['teamspeak_server_allow_codec_speex15000'],
				'L_SERVER_CODEC_12'			=> $lang['teamspeak_server_allow_codec_speex18200'],
				'L_SERVER_CODEC_13'			=> $lang['teamspeak_server_allow_codec_speex24600'],
				'L_SERVER_SEND_PACKET'		=> $lang['teamspeak_server_packetssend'],
				'L_SERVER_SEND_BYTE'		=> $lang['teamspeak_server_bytessend'],
				'L_SERVER_RECEIVED_PACKET'	=> $lang['teamspeak_server_packetsreceived'],
				'L_SERVER_RECEIVED_BYTE'	=> $lang['teamspeak_server_bytesreceived'],
				'L_SERVER_UPTIME'			=> $lang['teamspeak_server_uptime'],
				'L_SERVER_NUM_CHANNELS'		=> $lang['teamspeak_server_currentchannels'],
				'S_TEAMSPEAK_ACTION'		=> append_sid("admin_teamspeak.php")
			));
			
			$template->assign_block_vars('display.server.on', array(
				'SERVER_NAME'				=> $info['server_name'],
				'SERVER_PLATFORM'			=> $info['server_platform'],
				'SERVER_WELCOME_MSG'		=> $info['server_welcomemessage'],			
				'SERVER_WEB_LINK'			=> $info['server_webpost_linkurl'],
				'SERVER_WEB_POST'			=> $info['server_webpost_posturl'],
				'SERVER_PASSWORD'			=> $info['server_password'],
				'SERVER_TYPE'				=> ( $info['server_clan_server'] == '1' ) ? 'Clan' : 'Public',
				'SERVER_USER_MAX'			=> $info['server_maxusers'],
				'SERVER_USER_CURRENT'		=> $info['server_currentusers'],
				'SERVER_CODEC_1'			=> ( $info['server_allow_codec_celp51'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_2'			=> ( $info['server_allow_codec_celp63'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_3'			=> ( $info['server_allow_codec_gsm148'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_4'			=> ( $info['server_allow_codec_gsm164'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_5'			=> ( $info['server_allow_codec_windowscelp52'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_6'			=> ( $info['server_allow_codec_speex2150'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_7'			=> ( $info['server_allow_codec_speex3950'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_8'			=> ( $info['server_allow_codec_speex5950'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_9'			=> ( $info['server_allow_codec_speex8000'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_10'			=> ( $info['server_allow_codec_speex11000'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_11'			=> ( $info['server_allow_codec_speex15000'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_12'			=> ( $info['server_allow_codec_speex18200'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_CODEC_13'			=> ( $info['server_allow_codec_speex24600'] == '-1' ) ? $lang['teamspeak_codec_on'] : $lang['teamspeak_codec_off'],
				'SERVER_SEND_PACKET'		=> $info['server_packetssend'],
				'SERVER_SEND_BYTE'			=> $info['server_bytessend'],
				'SERVER_RECEIVED_PACKET'	=> $info['server_packetsreceived'],
				'SERVER_RECEIVED_BYTE'		=> $info['server_bytesreceived'],
				'SERVER_UPTIME'				=> $info['server_uptime'],
				'SERVER_NUM_CHANNELS'		=> $info['server_currentchannels'],
			));
		}		
		$cyts->disconnect();
	}
	else
	{
		$template->assign_block_vars('display.nothing', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>