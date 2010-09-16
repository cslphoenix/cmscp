<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_teamspeak'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_server']['_submenu_teamspeak'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_authlist';
	
	include('./pagestart.php');
	include($root_path . 'includes/class_cyts.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/teamspeak.php');
	
	$data_id	= request(POST_TEAMSPEAK_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$show_index	= '';
	
	if ( $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $no_header ) ? redirect('admin/' . append_sid('admin_teamspeak.php', true)) : false;
	
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
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_teamspeak.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !(request('submit', 2)) )
				{
					$data = array (
						'teamspeak_name'		=> request('authlist_name', 2),
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
				}
				else if ( $mode == '_update' && !(request('submit', 2)) )
				{
					$data = get_data(TEAMSPEAK, $data_id, 0);
				}
				else
				{
					$data = array (
						'teamspeak_name'		=> request('teamspeak_name', 2),
						'teamspeak_ip'			=> request('teamspeak_ip', 2),
						'teamspeak_port'		=> request('teamspeak_port', 2),
						'teamspeak_qport'		=> request('teamspeak_qport', 2),
						'teamspeak_pass'		=> request('teamspeak_pass', 2),
						'teamspeak_join_name'	=> request('teamspeak_join_name', 2),
						'teamspeak_cstats'		=> request('teamspeak_cstats', 2),
						'teamspeak_ustats'		=> request('teamspeak_ustats', 2),
						'teamspeak_sstats'		=> request('teamspeak_sstats', 2),
						'teamspeak_infos'		=> request('teamspeak_infos', 2),
						'teamspeak_plist'		=> request('teamspeak_plist', 2),
						'teamspeak_mouseover'	=> request('teamspeak_mouseover', 2),
						'teamspeak_show'		=> request('teamspeak_show', 2),
					);
				}
				/*
				if ( ( $ts_host || $ts_db || $ts_user || $ts_pass || $ts_prefix ) && $userdata['user_level'] == ADMIN )
				{
					$template->assign_block_vars('_input.user', array());
				}
				*/
				$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
				$s_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_TEAMSPEAK_URL . '" value="' . $data_id . '" />';

				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['teamspeak']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['teamspeak'], $data['teamspeak_name']),
				
				#	'L_USER'		=> $lang['teamspeak_user'],
					
					
					'L_NAME'		=> $lang['teamspeak_name'],
					'L_IP'			=> $lang['teamspeak_ip'],
					'L_PORT'		=> $lang['teamspeak_port'],
					'L_QPORT'		=> $lang['teamspeak_qport'],
					'L_PASS'		=> $lang['teamspeak_pass'],
					
					'L_CSTATS'		=> $lang['teamspeak_cstats'],
					'L_USTATS'		=> $lang['teamspeak_ustats'],
					'L_SSTATS'		=> $lang['teamspeak_sstats'],
					'L_MOUSEO'		=> $lang['teamspeak_mouseo'],
					'L_VIEWER'		=> $lang['teamspeak_viewer'],
					'L_JOIN'		=> $lang['teamspeak_join'],
					
					'L_SHOW'		=> $lang['teamspeak_show'],
					'L_NOSHOW'		=> $lang['teamspeak_noshow'],
					
					'NAME'			=> $data['teamspeak_name'],
					'IP'			=> $data['teamspeak_ip'],
					'PORT'			=> $data['teamspeak_port'],
					'QPORT'			=> $data['teamspeak_qport'],
					'PASS'			=> $data['teamspeak_pass'],
					'JOIN'			=> $data['teamspeak_join_name'],
					
					'S_CSTATS_YES'	=> ( $data['teamspeak_cstats'] ) ? ' checked="checked"' : '',
					'S_CSTATS_NO'	=> ( !$data['teamspeak_cstats'] ) ? ' checked="checked"' : '',
					'S_USTATS_YES'	=> ( $data['teamspeak_ustats'] ) ? ' checked="checked"' : '',
					'S_USTATS_NO'	=> ( !$data['teamspeak_ustats'] ) ? ' checked="checked"' : '',
					'S_SSTATS_YES'	=> ( $data['teamspeak_sstats'] ) ? ' checked="checked"' : '',
					'S_SSTATS_NO'	=> ( !$data['teamspeak_sstats'] ) ? ' checked="checked"' : '',
					'S_MOUSEO_YES'	=> ( $data['teamspeak_mouseover'] ) ? ' checked="checked"' : '',
					'S_MOUSEO_NO'	=> ( !$data['teamspeak_mouseover'] ) ? ' checked="checked"' : '',
					'S_VIEWER_YES'	=> ( $data['teamspeak_show'] ) ? ' checked="checked"' : '',
					'S_VIEWER_NO'	=> ( !$data['teamspeak_show'] ) ? ' checked="checked"' : '',

					'S_FIELDS'	=> $s_fields,
					'S_MEMBER'	=> append_sid('admin_teamspeak.php?mode=member'),
					'S_ACTION'	=> append_sid('admin_teamspeak.php'),
				));
				
				if ( request('submit', 2) )
				{
					$teamspeak_ip			= request('teamspeak_ip', 2);
					$teamspeak_port			= request('teamspeak_port', 2);
					$teamspeak_qport		= request('teamspeak_qport', 2);
					$teamspeak_pass			= request('teamspeak_pass', 2);
					$teamspeak_name			= request('teamspeak_name', 2);
					$teamspeak_join_name	= request('teamspeak_join_name', 2);
					$teamspeak_cstats		= request('teamspeak_cstats', 2);
					$teamspeak_ustats		= request('teamspeak_ustats', 2);
					$teamspeak_sstats		= request('teamspeak_sstats', 2);
					$teamspeak_plist		= request('teamspeak_plist', 2);
					$teamspeak_mouseover	= request('teamspeak_mouseover', 2);
					$teamspeak_show			= request('teamspeak_show', 2);
					
					$error = ( !$teamspeak_name ) ? $lang['empty_name'] : '';
					
					if ( $error )
					{
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}					
					else
					{
						if ( $teamspeak_show )
						{
							$sql = "SELECT * FROM " . TEAMSPEAK . " WHERE teamspeak_show = 1";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							$ts_sql = $db->sql_fetchrow($result);
							
							if ( $ts_sql )
							{
								$sql = 'UPDATE ' . TEAMSPEAK . ' SET teamspeak_show = 0 WHERE teamspeak_id = ' . $ts_sql['teamspeak_id'];
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
						}
						
						if ( $mode == '_create' )
						{
							$sql = "INSERT INTO " . TEAMSPEAK . " (teamspeak_ip, teamspeak_port, teamspeak_qport, teamspeak_pass, teamspeak_name, teamspeak_join_name, teamspeak_cstats, teamspeak_ustats, teamspeak_sstats, teamspeak_plist, teamspeak_mouseover, teamspeak_show)
								VALUES ('$teamspeak_ip', '$teamspeak_port', '$teamspeak_qport', '$teamspeak_pass', '$teamspeak_name', '$teamspeak_join_name', $teamspeak_cstats, $teamspeak_ustats, $teamspeak_sstats, $teamspeak_plist, $teamspeak_mouseover, $teamspeak_cstats, $teamspeak_show)";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_teamspeak'] . sprintf($lang['click_return_teamspeak'], '<a href="' . append_sid('admin_teamspeak.php') . '">', '</a>');
							log_add(LOG_ADMIN, LOG_SEK_TEAMSPEAK, 'create_teamspeak');
						}
						else
						{
							$sql = "UPDATE " . TEAMSPEAK . " SET
										teamspeak_ip			= '$teamspeak_ip',
										teamspeak_port			= '$teamspeak_port',
										teamspeak_qport			= '$teamspeak_qport',
										teamspeak_pass			= '$teamspeak_pass',
										teamspeak_name			= '$teamspeak_name',
										teamspeak_join_name		= '$teamspeak_join_name',
										teamspeak_cstats		= $teamspeak_cstats,
										teamspeak_ustats		= $teamspeak_ustats,
										teamspeak_sstats		= $teamspeak_sstats,
										teamspeak_plist			= $teamspeak_plist,
										teamspeak_mouseover		= $teamspeak_mouseover,
										teamspeak_show			= $teamspeak_show
									WHERE teamspeak_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_teamspeak']
								. sprintf($lang['click_return_teamspeak'], '<a href="' . append_sid('admin_teamspeak.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_teamspeak.php?mode=_update&amp;' . POST_TRAINING_URL . '=' . $data_id) . '">', '</a>');
							log_add(LOG_ADMIN, LOG_SEK_GAME, 'update_teamspeak');
						}
						
						$oCache -> sCachePath = './../cache/';
						$oCache -> deleteCache('teamspeak_data');
						
						message(GENERAL_MESSAGE, $message);
					}
				}
			
				$template->pparse('body');
				
				break;
			
			case '_member':
				
				$template->set_filenames(array('body' => 'style/acp_teamspeak.tpl'));
				$template->assign_block_vars('teamspeak_member', array());
				
				if ( $ts_host || $ts_db || $ts_user || $ts_pass || $ts_prefix )
				{
					$db->sql_close();
					
					$db->sql_db($ts_host, $ts_user, $ts_pass, $ts_db, true);
					
					$sql = 'SELECT * FROM ' . $ts_prefix . 'clients WHERE i_client_server_id = ' . $ts_server;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$teamspeak_user = $db->sql_fetchrowset($result);
					
					debug($teamspeak_user);
					
					$db->sql_close();
					
					include($root_path . 'includes/config.php');
					include($root_path . 'includes/db.php');
				}
				
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($teamspeak_user)); $i++)
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					if ( $teamspeak_user[$i]['b_client_privilege_serveradmin'] == '-1' )
					{
						$edit	= ( $userdata['user_level'] == ADMIN ) ? '<a href="' . append_sid('admin_teamspeak.php?mode=useredit&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak_user[$i]['i_client_id']) . '">' . $lang['edit'] . '</a>' : $lang['edit'];
						$delete	= ( $userdata['user_level'] == ADMIN ) ? '<a href="' . append_sid('admin_teamspeak.php?mode=userdelete&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak_user[$i]['i_client_id']) . '">' . $lang['common_delete'] . '</a>' : $lang['common_delete'];
					}
					else
					{
						$edit	= ( $userauth['auth_user'] || $userdata['user_level'] == ADMIN ) ? '<a href="' . append_sid('admin_teamspeak.php?mode=useredit&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak_user[$i]['i_client_id']) . '">' . $lang['edit'] . '</a>' : $lang['edit'];
						$delete	= ( $userauth['auth_user'] || $userdata['user_level'] == ADMIN ) ? '<a href="' . append_sid('admin_teamspeak.php?mode=userdelete&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak_user[$i]['i_client_id']) . '">' . $lang['common_delete'] . '</a>' : $lang['common_delete'];
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
					'L_TITLE'		=> $lang['teamspeak_head'],
					'L_INPUT'	=> $lang['teamspeak_edit'],
					'L_USER'		=> $lang['teamspeak_user'],
					'L_GOTO_PAGE'			=> $lang['Goto_page'],
					
					'PAGINATION'			=> generate_pagination('admin_teamspeak.php?', count($teamspeak_user), $settings['site_entry_per_page'], $start),
					'PAGE_NUMBER'			=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
					
					'S_TEAMSPEAK_EDIT'			=> append_sid('admin_teamspeak.php?mode=edit&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak_id),
					'S_TEAMSPEAK_ACTION'		=> append_sid('admin_teamspeak.php'),
				));
				
				$template->pparse('body');
				
				break;
			
			case '_delete':
			
				$data = get_data(TEAMSPEAK, $data_id, 1);
				
				if ( $data_id && $confirm )
				{
					$sql = "DELETE FROM " . TEAMSPEAK . " WHERE teamspeak_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					log_add(LOG_ADMIN, LOG_SEK_TEAMSPEAK, 'acp_teamspeak_delete', $teamspeak['teamspeak_name']);
					
					$message = $lang['delete_teamspeak'] . sprintf($lang['click_return_teamspeak'], '<a href="' . append_sid('admin_teamspeak.php') . '">', '</a>');
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_TEAMSPEAK_URL . '" value="' . $data_id . '" />';
					
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_teamspeak'], $data['teamspeak_name']),
						
						'S_FIELDS'		=> $s_fields,
						'S_ACTION'		=> append_sid('admin_teamspeak.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_teamspeak']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
			
				message(GENERAL_ERROR, $lang['msg_no_module_select']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_teamspeak.tpl'));
	$template->assign_block_vars('_display', array());
	
	if ( ( $ts_host || $ts_db || $ts_user || $ts_pass || $ts_prefix ) && $userdata['user_level'] == ADMIN )
	{
		$template->assign_block_vars('_display._management', array());
	}
	
	$sql = "SELECT * FROM " . TEAMSPEAK . " WHERE teamspeak_show = 1";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$teamspeak = $db->sql_fetchrow($result);
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
					
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['teamspeak']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_creates'], $lang['teamspeak']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['teamspeak']),
		'L_EXPLAIN'		=> $lang['teamspeak_explain'],
		
		'S_FIELDS'		=> $s_fields,
		'S_MEMBER'		=> append_sid('admin_teamspeak.php?mode=_member&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak['teamspeak_id']),
		'S_CREATE'		=> append_sid('admin_teamspeak.php?mode=_create'),
		'S_ACTION'		=> append_sid('admin_teamspeak.php'),
	));
	
	$data = get_data_array(TEAMSPEAK, '', 'teamspeak_id', 'ASC');
			
	for ( $i = 0; $i < count($data); $i++ )
	{
		$data_id = $data[$i]['teamspeak_id'];
		
		$template->assign_block_vars('_display._servers_row', array(
			'NAME'		=> $data[$i]['teamspeak_name'],
			
			'U_UPDATE'	=> append_sid('admin_teamspeak.php?mode=_update&amp;' . POST_TEAMSPEAK_URL . '=' . $data_id),
			'U_DELETE'	=> append_sid('admin_teamspeak.php?mode=_delete&amp;' . POST_TEAMSPEAK_URL . '=' . $data_id),
		));
	}
	
	/*
	
	$sql = 'SELECT * FROM ' . TEAMSPEAK;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$color = '0';
	
	while ( $teamspeak_data = $db->sql_fetchrow($result) )
	{
		$class = ( $color % 2 ) ? 'row_class1' : 'row_class2';
		$color++;
		
		$template->assign_block_vars('display.ts_data', array(
			'CLASS'		=> $class,
			'TS_NAME'	=> $teamspeak_data['teamspeak_name'],
			'TS_IP'		=> $teamspeak_data['teamspeak_ip'],
			'TS_PORT'	=> $teamspeak_data['teamspeak_port'],
			'TS_VIEW'	=> ( $teamspeak_data['teamspeak_show'] ) ? $lang['Enabled'] : $lang['Disabled'],
			
			'U_DELETE'	=> append_sid('admin_teamspeak.php?mode=delete&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak_data['teamspeak_id']),
			'U_EDIT'	=> append_sid('admin_teamspeak.php?mode=edit&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak_data['teamspeak_id']),
		));
	}
	
	if ( !$db->sql_numrows($result) )
	{
		$template->assign_block_vars('_display._no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$s_fields = '<input type="hidden" name="mode" value="edit" />';
	$s_fields .= '<input type="hidden" name="' . POST_TEAMSPEAK_URL . '" value="' . $teamspeak['teamspeak_id'] . '" />';
	
	$template->assign_vars(array(
		'L_TITLE'		=> $lang['teamspeak_head'],
		'L_EXPLAIN'	=> $lang['teamspeak_explain'],
		'L_ADD'		=> $lang['teamspeak_add'],
		'L_EDIT'		=> $lang['teamspeak_edit'],
		'L_INPUT'	=> ( !$teamspeak ) ? $lang['teamspeak_add'] : $lang['teamspeak_edit'],
		'L_CURRENT'	=> $lang['teamspeak_current'],
		'L_SERVER'	=> $lang['teamspeak_server'],
		
		'L_USER'		=> $lang['teamspeak_user'],
		
		'L_EDIT'				=> $lang['common_update'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['common_delete'],
		
		'S_FIELDS'		=> $s_fields,
		'S_TEAMSPEAK_EDIT'		=> append_sid('admin_teamspeak.php?mode=edit&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak['teamspeak_id']),
		'S_TEAMSPEAK_MEMBER'	=> append_sid('admin_teamspeak.php?mode=member&amp;' . POST_TEAMSPEAK_URL . '=' . $teamspeak['teamspeak_id']),
		'S_TEAMSPEAK_ACTION'	=> append_sid('admin_teamspeak.php'),
	));
	*/
	if ( $teamspeak )
	{
		$template->assign_block_vars('_display._server', array());
		
		$cyts = new cyts;
		$cyts->connect($teamspeak['teamspeak_ip'], $teamspeak['teamspeak_qport'], $teamspeak['teamspeak_port']);
		$info = $cyts->info_serverInfo();
		
		if ( !$info )
		{
			$template->assign_block_vars('_display._server._off', array());
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
				'S_TEAMSPEAK_ACTION'		=> append_sid('admin_teamspeak.php'),
				
				
			));
			
			debug($info);
			debug($info['server_allow_codec_celp51']);
			
			$template->assign_block_vars('_display._server._on', array(
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
		$template->assign_block_vars('_display._nothing', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>