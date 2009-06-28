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
	
	if ( $userauth['auth_server'] || $userdata['user_level'] == ADMIN )
	{
		$module['server']['gameserver'] = $filename;
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
	include($root_path . 'includes/server_query.php');
	include($root_path . 'includes/teamspeak_query.php');
	include($root_path . 'includes/functions_admin.php');
	
	if (!$userauth['auth_games'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_match.php', true));
	}
	
	if ( isset($HTTP_POST_VARS[POST_GAMESERVER_URL]) || isset($HTTP_GET_VARS[POST_GAMESERVER_URL]) )
	{
		$server_id = ( isset($HTTP_POST_VARS[POST_GAMESERVER_URL]) ) ? intval($HTTP_POST_VARS[POST_GAMESERVER_URL]) : intval($HTTP_GET_VARS[POST_GAMESERVER_URL]);
	}
	else
	{
		$server_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		if (isset($HTTP_POST_VARS['add']))
		{
			$mode = 'add';
		}
		else
		{
			$mode = '';
		}
	}
	
	function _select_game($default)
	{
		global $lang;
		
		$type = array (
			'0'				=> $lang['select_live'],
			'aarmy'			=> 'Americas Army',
			'bf2'			=> 'Battlefield 2 (PoE, PR, ....)',
			'bf1942'		=> 'Battlefield 1942',
			'bf2142'		=> 'Battlefield 2142',
			'bfvietnam'		=> 'Battlefield Vietnam',
			'callofduty'	=> 'Call of Duty 1 &amp; 2',
			'cnc'			=> 'Command and Conquer',
			'halflife'		=> 'Dark Messiah of Might & Magic',
			'farcry'		=> 'Far Cry',
			'fear'			=> 'F.E.A.R',
			'halo'			=> 'Halo',
			'halflife'		=> 'Halflife (CS, DoD, ...)',
			'halflife2'		=> 'Halflife 2 (CS:S, DoD:S, ...)',
			'jediknight2'	=> 'Jediknight 2',
			'mohq3'			=> 'Medal of Honor - Method 1',
			'mohgs'			=> 'Medal of Honor - Method 2',
			'neverwinter'	=> 'Neverwinter Nights',
			'aarmy'			=> 'Operation Flashpoint',
			'quake4'		=> 'Prey',
			'quake3'		=> 'Quake 3',
			'quake4'		=> 'Quake 4',
			'quakew'		=> 'Quake World',
			'ravenshield'	=> 'Ravenshield',
			'sof2'			=> 'Soldier of Fortune 2',
			'startrekef'	=> 'StarTrek Elite-Force',
			'battlefront'	=> 'StarWars Battlefront 2',
			'ut2004'		=> 'Red Orchestra',
			'swat4'			=> 'SWAT 4',
			'ut'			=> 'Unreal Tournament',
			'ut2003'		=> 'Unreal Tournament 2003',
			'ut2004'		=> 'Unreal Tournament 2004',
			'vietcong'		=> 'Vietcong',
			'vietcong2'		=> 'Vietcong 2',
			'wolfenstein'	=> 'Wolfenstein (RTCW &amp; Enemy Territory)',
		);
		
		$select_game = '';
		$select_game .= '<select name="match_type" class="post">';
		foreach ($type as $valve => $typ)
		{
			$selected = ( $valve == $default ) ? ' selected="selected"' : '';
			$select_game .= '<option value="' . $valve . '" ' . $selected . '>&raquo; ' . $typ . '&nbsp;</option>';
		}
		$select_game .= '</select>';
		
		return $select_game;	
	}
	
	$show_index = '';
	
	if ( !empty($mode) )
	{
		switch ($mode)
		{
			case 'add':
			case 'edit':
				
				if ( $mode == 'edit' )
				{
					$sql = 'SELECT * FROM ' . SERVER . ' WHERE server_id = ' . $server_id;
					$result = $db->sql_query($sql);
			
					if ( !($server = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['server_not_exist']);
					}
			
					$new_mode = 'editserver';
				}
				else if ( $mode == 'add' )
				{
					//	Start Werte setzen
					$server = array (
						'server_name'		=> '',
						'server_type'		=> '1',
						'server_game'		=> '',
						'server_ip'			=> '',
						'server_port'		=> '',
						'server_qport'		=> '',
						'server_live'		=> '1',
						'server_pw'			=> '',
						'server_list'		=> '1',
						'server_show'		=> '1',
						'server_own'		=> '1',
					);

					$new_mode = 'addserver';
				}
				
				$template->set_filenames(array('body' => './../admin/style/acp_server.tpl'));
				$template->assign_block_vars('server_edit', array());
				
				//	Unsichtbare Felder für andere Infos
				$s_hidden_fields = '';
				$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_GAMESERVER_URL . '" value="' . $server_id . '" />';

				//	Variablen zur Ausgabe
				$template->assign_vars(array(
					'L_SERVER_HEAD'			=> $lang['server_head'],
					'L_SERVER_NEW_EDIT'		=> ($mode == 'add') ? $lang['server_add'] : $lang['server_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_SERVER_NAME'			=> $lang['server_name'],
					
					'L_SERVER_GAME'			=> $lang['server_game'],
					'L_SERVER_VOICE'		=> $lang['server_voice'],
					
					
					
					'L_SUBMIT'				=> $lang['common_submit'],
					'L_RESET'				=> $lang['common_reset'],
					'L_YES'					=> $lang['common_yes'],
					'L_NO'					=> $lang['common_no'],
					
					'SERVER_NAME'			=> $server['server_name'],
					'SERVER_IP'				=> $server['server_ip'],
					'SERVER_PORT'			=> $server['server_port'],
					'SERVER_QPORT'			=> $server['server_qport'],
					'SERVER_PW'				=> $server['server_pw'],
					
					'S_CHECKED_TYPE_GAME'	=> ($server['server_type'] == '1') ? ' checked="checked"' : '',
					'S_CHECKED_TYPE_VOICE'	=> ($server['server_type'] == '2') ? ' checked="checked"' : '',					
					'S_CHECKED_LIVE_YES'	=> ( $server['server_live']) ? ' checked="checked"' : '',
					'S_CHECKED_LIVE_NO'		=> (!$server['server_live']) ? ' checked="checked"' : '',
					'S_CHECKED_LIST_YES'	=> ( $server['server_list']) ? ' checked="checked"' : '',
					'S_CHECKED_LIST_NO'		=> (!$server['server_list']) ? ' checked="checked"' : '',
					'S_CHECKED_SHOW_YES'	=> ( $server['server_show']) ? ' checked="checked"' : '',
					'S_CHECKED_SHOW_NO'		=> (!$server['server_show']) ? ' checked="checked"' : '',
					'S_CHECKED_OWN_YES'		=> ( $server['server_own']) ? ' checked="checked"' : '',
					'S_CHECKED_OWN_NO'		=> (!$server['server_own']) ? ' checked="checked"' : '',

					'S_LIVE'				=> _select_game($server['server_game']),
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_TEAM_ACTION'			=> append_sid('admin_server.php'),
				));
			
				// Template ausgabe
				$template->pparse('body');
				
			break;
			
			case 'addserver':
				
				if( $server_title == '' )
				{
					message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
				}
	
				$sql = 'SELECT MAX(server_order) AS max_order FROM ' . SERVER . ' WHERE server_type = ' . intval($HTTP_POST_VARS['server_type']);
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				// There is no problem having duplicate forum names so we won't check for it.
				$sql = 'INSERT INTO ' . SERVER . " (server_title, server_type, server_min, server_special, server_image, server_order)
					VALUES ('" . str_replace("\'", "''", $server_title) . "', '" . intval($HTTP_POST_VARS['server_type']) . "', $server_min, $server_special, '" . str_replace("\'", "''", $server_image) . "', $next_order)";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not insert row in team table', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_SERVER, ACP_SERVER_ADD, $server_title);
	
				$message = $lang['team_create'] . '<br><br>' . sprintf($lang['click_return_team'], '<a href="' . append_sid('admin_server.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editserver':
			
				$server_title	= (isset($HTTP_POST_VARS['server_title']))	? trim($HTTP_POST_VARS['server_title']) : '';
				$server_image	= (isset($HTTP_POST_VARS['server_image']))	? trim($HTTP_POST_VARS['server_image']) : '';
				$server_min		= (isset($HTTP_POST_VARS['server_min']))		? intval($HTTP_POST_VARS['server_min']) : -1;
				$server_special	= ($HTTP_POST_VARS['server_special'] == 1)	? 1 : 0;
				
				if( $server_title == '' )
				{
					message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
				}

				$sql = "UPDATE " . SERVER . " SET
							server_title		= '" . str_replace("\'", "''", $server_title) . "',
							server_type		= '" . intval($HTTP_POST_VARS['server_type']) . "',
							server_min		= $server_min,
							server_special	= $server_special,
							server_image		= '" . str_replace("\'", "''", $server_image) . "'
						WHERE server_id = " . intval($HTTP_POST_VARS[POST_GAMESERVER_URL]);
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update team information', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_SERVER, ACP_TEAM_EDIT, $log_data);
				
				$message = $lang['team_update'] . '<br><br>' . sprintf($lang['click_return_server'], '<a href="' . append_sid('admin_server.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $server_id && $confirm )
				{	
					$sql = 'SELECT * FROM ' . SERVER . " WHERE server_id = $server_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Error getting team information', '', __LINE__, __FILE__, $sql);
					}
			
					if ( !($team_info = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['server_not_exist']);
					}
				
					$sql = 'DELETE FROM ' . SERVER . " WHERE server_id = $server_id";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete team', '', __LINE__, __FILE__, $sql);
					}
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_SERVER, ACP_SERVER_DELETE, $team_info['server_title']);
					
					$message = $lang['team_delete'] . '<br><br>' . sprintf($lang['click_return_server'], '<a href="' . append_sid('admin_server.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $server_id && !$confirm )
				{
					$template->set_filenames(array('body' => './../admin/style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_GAMESERVER_URL . '" value="' . $server_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_server'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid('admin_server.php'),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_server']);
				}
				
				$template->pparse('body');
				
				break;
			
			case 'order':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . SERVER . " SET server_order = server_order + $move WHERE server_id = $server_id";
				$result = $db->sql_query($sql);
		
				renumber_server('server');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_SERVER, 'ACP_SERVER_ORDER');
				
				$show_index = TRUE;
	
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
	
	$template->set_filenames(array('body' => './../admin/style/acp_server.tpl'));
	$template->assign_block_vars('display', array());
	
	$template->assign_vars(array(
		'L_SERVER_HEAD'		=> $lang['gs_head'],
		'L_SERVER_EXPLAIN'	=> $lang['gs_explain'],
		'L_SERVER_EMAIL'	=> $lang['gs_name'],
		'L_SERVER_ADD'		=> $lang['gs_add'],
		
		'L_SETTINGS'		=> $lang['settings'],
		'L_EDIT'			=> $lang['edit'],
		'L_DELETE'			=> $lang['delete'],
		
		'L_MOVE_UP'			=> $lang['Move_up'], 
		'L_MOVE_DOWN'		=> $lang['Move_down'], 
		
		'S_SERVER_ACTION'		=> append_sid('admin_server.php'),
	));
	
	
	$sql = 'SELECT * FROM ' . SERVER . ' ORDER BY server_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$color = '';
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$class = ($color % 2) ? 'row_class1' : 'row_class2';
		$color++;
		
//		$server = server_query($row['server_ip'], $row['server_port'], $row['server_qport'], $row['server_game'], 'info');
		
//	_debug($server);
		
		$template->assign_block_vars('display.server_row', array(
			'CLASS' 		=> $class,
			'SERVER_NAME'	=> $row['server_name'],

			'U_DELETE'		=> append_sid('admin_server.php?mode=delete&amp;' . POST_GAMESERVER_URL . '=' . $row['server_id']),
			'U_EDIT'		=> append_sid('admin_server.php?mode=edit&amp;' . POST_GAMESERVER_URL . '=' . $row['server_id']),
			'U_MOVE_UP'		=> append_sid('admin_server.php?mode=order_page&amp;move=-15&amp;' . POST_GAMESERVER_URL . '=' . $row['server_id']),
			'U_MOVE_DOWN'	=> append_sid('admin_server.php?mode=order_page&amp;move=15&amp;' . POST_GAMESERVER_URL . '=' . $row['server_id']),
		));
	}
	
	if ( !$db->sql_numrows($result) )
	{
		$template->assign_block_vars('no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	$db->sql_freeresult($result);
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>