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
	if ($userauth['auth_training'] || $userdata['user_level'] == ADMIN)
	{
		$module['teams']['training'] = $filename;
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
	include($root_path . 'includes/functions_admin.php');
	include($root_path . 'includes/functions_selects.php');
	
	if (!$userauth['auth_training'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}

	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_training.php", true));
	}
	
	//	ID Abfrage
	if ( isset($HTTP_POST_VARS[POST_TRAINING_URL]) || isset($HTTP_GET_VARS[POST_TRAINING_URL]) )
	{
		$training_id = ( isset($HTTP_POST_VARS[POST_TRAINING_URL]) ) ? intval($HTTP_POST_VARS[POST_TRAINING_URL]) : intval($HTTP_GET_VARS[POST_TRAINING_URL]);
	}
	else
	{
		$training_id = 0;
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	
	//	mode Abfrage
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
	
	$show_index = '';
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
			
				if ( $mode == 'edit' )
				{
					//	Infos der Spiele
					$sql = 'SELECT * FROM ' . TRAINING . " WHERE training_id = $training_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Error getting training information', '', __LINE__, __FILE__, $sql);
					}
			
					if ( !($training = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['training_not_exist']);
					}
			
					$new_mode = 'edittraining';
				}
				else if ( $mode == 'add' )
				{
					$training_vs	= (isset($HTTP_POST_VARS['training_vs'])) ? trim($HTTP_POST_VARS['training_vs']) : trim($HTTP_GET_VARS['vs']);
					$team_id		= (isset($HTTP_POST_VARS['team_id'])) ? intval($HTTP_POST_VARS['team_id']) : '';
					$match_id		= (isset($HTTP_POST_VARS[POST_MATCH_URL])) ? intval($HTTP_POST_VARS[POST_MATCH_URL]) : '';
					//	Start Werte setzen
					$training = array (
						'training_vs'		=> $training_vs,
						'team_id'			=> $team_id,
						'match_id'			=> $match_id,
						'training_start'	=> time(),
						'training_maps'		=> '',
						'training_text'		=> '',
						'training_duration'	=> '',
					);

					$new_mode = 'addtraining';
				}
				
				//	Template definieren
				$template->set_filenames(array('body' => './../admin/style/acp_training.tpl'));
				$template->assign_block_vars('training_edit', array());
				
				//	Unsichtbare Felder für andere Infos
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $training_id . '" />';
				
				//	Variablen zur Ausgabe
				$template->assign_vars(array(
					'L_TRAINING_HEAD'		=> $lang['training_head'],
					'L_TRAINING_NEW_EDIT'	=> ($mode == 'add') ? $lang['training_add'] : $lang['training_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					
					'L_TRAINING_VS'			=> $lang['training_vs'],
					'L_TRAINING_TEAM'		=> $lang['training_team'],
					'L_TRAINING_MATCH'		=> $lang['training_match'],
					'L_TRAINING_DATE'		=> $lang['training_date'],
					'L_TRAINING_DURATION'	=> $lang['training_duration'],
					'L_TRAINING_MAPS'		=> $lang['training_maps'],
					'L_TRAINING_TEXT'		=> $lang['training_text'],
	
					
					'TRAINING_VS'			=> $training['training_vs'],
					'TRAINING_MAPS'			=> $training['training_maps'],
					'TRAINING_TEXT'			=> $training['training_text'],
					
					'S_DAY'					=> _select_date('day',		'day',		date('d', $training['training_start'])),
					'S_MONTH'				=> _select_date('month',	'month',	date('m', $training['training_start'])),
					'S_YEAR'				=> _select_date('year',		'year',		date('Y', $training['training_start'])),
					'S_HOUR'				=> _select_date('hour',		'hour',		date('H', $training['training_start'])),
					'S_MIN'					=> _select_date('min',		'min',		date('i', $training['training_start'])),
					
					'S_DURATION'			=> _select_date('duration', 'dmin',	($training['training_duration'] - $training['training_start']) / 60),
					
					'S_TEAMS'				=> _select_team($training['team_id'], 0, 'post'),
					'S_MATCH'				=> _select_match($training['match_id'], '', 'post'),
				
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_TEAM_ACTION'			=> append_sid("admin_training.php")
				));
			
				// Template ausgabe
				$template->pparse('body');
				
			break;
			
			case 'addtraining':
			
				$error = ''; 
				$error_msg = '';
			
				if ( intval($HTTP_POST_VARS['team_id']) == '0' )
				{
					$error = true;
					$error_msg = $lang['msg_select_team'];
				}
				
				if ( intval($HTTP_POST_VARS['dmin']) == '00' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['msg_select_duration'];
				}
				
//				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) || time() > mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['msg_select_date'];
				}
				
				if ( $HTTP_POST_VARS['training_maps'] == '' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['msg_select_map'];
				}
				
				$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['back'];
				
				if ($error)
				{
					message_die(GENERAL_ERROR, $error_msg, '');
				}
	
				$training_start		= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				$training_duration	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'] + $HTTP_POST_VARS['dmin'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				
				$sql = "INSERT INTO " . TRAINING . " (training_vs, team_id, match_id, training_start, training_duration, training_create, training_maps, training_text)
					VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['training_vs']) . "',
							'" . intval($HTTP_POST_VARS['team_id']) . "',
							'" . intval($HTTP_POST_VARS['match_id']) . "',
							$training_start,
							$training_duration,
							" . time() . ",
							'" . str_replace("\'", "''", $HTTP_POST_VARS['training_maps']) . "',
							'" . str_replace("\'", "''", $HTTP_POST_VARS['training_text']) . "')";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not insert row in match table', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TRAINING, 'acp_training_add');
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('subnavi_trains');
	
				$message = $lang['training_create'] . '<br /><br />' . sprintf($lang['click_return_training'], '<a href="' . append_sid("admin_training.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'edittraining':
			
				$error = ''; 
				$error_msg = '';
			
				if ( intval($HTTP_POST_VARS['team_id']) == '0' )
				{
					$error = true;
					$error_msg = $lang['select_fail_team'];
				}
				
				if ( intval($HTTP_POST_VARS['dmin']) == '00' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['select_fail_duration'];
				}
				
//				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) || time() > mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['select_fail_date'];
				}
				
				if ( $HTTP_POST_VARS['training_maps'] == '' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['select_fail_map'];
				}
				
				$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['back'];
				
				if ($error)
				{
					message_die(GENERAL_ERROR, $error_msg, '');
				}
			
				$training_start		= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				$training_duration	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'] + $HTTP_POST_VARS['dmin'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);

				$sql = "UPDATE " . TRAINING . " SET
							training_vs			= '" . str_replace("\'", "''", $HTTP_POST_VARS['training_vs']) . "',
							team_id				= '" . intval($HTTP_POST_VARS['team_id']) . "',
							match_id			= '" . intval($HTTP_POST_VARS['match_id']) . "',
							training_start		= $training_start,
							training_duration	= $training_duration,
							training_update		= " . time() . ",
							training_maps		= '" . str_replace("\'", "''", $HTTP_POST_VARS['training_maps']) . "',
							training_text		= '" . str_replace("\'", "''", $HTTP_POST_VARS['training_text']) . "'
						WHERE training_id = " . intval($HTTP_POST_VARS[POST_TRAINING_URL]);
				$result = $db->sql_query($sql);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TRAINING, 'acp_team_edit');
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('subnavi_trains');
				
				$message = $lang['training_update'] . '<br /><br />' . sprintf($lang['click_return_training'], '<a href="' . append_sid("admin_training.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $training_id && $confirm )
				{	
					$sql = 'SELECT * FROM ' . TRAINING . " WHERE training_id = $training_id";
					$result = $db->sql_query($sql);
			
					if ( !($team_info = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['training_not_exist']);
					}
				
					$sql = 'DELETE FROM ' . TRAINING . " WHERE training_id = $training_id";
					$result = $db->sql_query($sql, BEGIN_TRANSACTION);
					
					$sql = 'DELETE FROM ' . TRAINING_COM . " WHERE training_id = $training_id";
					$result = $db->sql_query($sql);
					
					$sql = 'DELETE FROM ' . TRAINING_USERS . " WHERE training_id = $training_id";
					$result = $db->sql_query($sql, END_TRANSACTION);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TRAINING, ACP_TRAINING_DELETE, $team_info['training_title']);
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('subnavi_trains');
					
					$message = $lang['team_delete'] . '<br /><br />' . sprintf($lang['click_return_training'], '<a href="' . append_sid("admin_training.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $training_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $training_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_training'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_training.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_training']);
				}
				
				break;
							
			default:
				message_die(GENERAL_ERROR, $lang['no_select_module'], '');
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_training.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_TRAINING_TITLE'		=> $lang['training_head'],
		'L_TRAINING_EXPLAIN'	=> $lang['training_explain'],
		'L_TRAINING'			=> $lang['training'],
		'L_UPCOMING'			=> $lang['training_upcoming'],
		'L_EXPIRED'				=> $lang['training_expired'],
		'L_TRAINING_ADD'		=> $lang['training_add'],
		'L_SETTING'				=> $lang['setting'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		'S_TEAMS'				=> _select_team('', 0, 'postselect'),
		'S_TEAM_ACTION'			=> append_sid("admin_training.php")
	));
	
	$sql = 'SELECT tr.*, g.game_image, g.game_size
			FROM ' . TRAINING . ' tr, ' . TEAMS . ' t, ' . GAMES . ' g
			WHERE tr.team_id = t.team_id AND t.team_game = g.game_id AND tr.training_start > ' . time() . '
			ORDER BY training_start';
	$result = $db->sql_query($sql);
	
	$training_entry_n = $db->sql_fetchrowset($result);
	
	if (!$training_entry_n)
	{
		$template->assign_block_vars('display.no_entry_new', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_entry_n)); $i++)
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$template->assign_block_vars('display.training_row_n', array(
				'CLASS'		=> $class,
				'NAME'		=> $training_entry_n[$i]['training_vs'],
				
				'I_IMAGE'		=> display_gameicon($training_entry_n[$i]['game_size'], $training_entry_n[$i]['game_image']),
				'TRAINING_DATE'	=> create_date($userdata['user_dateformat'], $training_entry_n[$i]['training_start'], $userdata['user_timezone']),
				
				'U_EDIT'		=> append_sid("admin_training.php?mode=edit&amp;" . POST_TRAINING_URL . "=" . $training_entry_n[$i]['training_id']),
				'U_DELETE'		=> append_sid("admin_training.php?mode=delete&amp;" . POST_TRAINING_URL . "=" . $training_entry_n[$i]['training_id'])
			));
		}
	}
	
	$sql = 'SELECT tr.*, g.game_image, g.game_size
			FROM ' . TRAINING . ' tr, ' . TEAMS . ' t, ' . GAMES . ' g
			WHERE tr.team_id = t.team_id AND t.team_game = g.game_id AND tr.training_start < ' . time() . '
			ORDER BY training_start';
	$result = $db->sql_query($sql);
	
	$training_entry_o = $db->sql_fetchrowset($result);
	
	if ( !$training_entry_o )
	{
		$template->assign_block_vars('display.no_entry_old', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_entry_o)); $i++)
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$template->assign_block_vars('display.training_row_o', array(
				'CLASS'		=> $class,
				'NAME'		=> $training_entry_o[$i]['training_vs'],
				
				'I_IMAGE'		=> display_gameicon($training_entry_o[$i]['game_size'], $training_entry_o[$i]['game_image']),
				'TRAINING_DATE'	=> create_date($userdata['user_dateformat'], $training_entry_o[$i]['training_start'], $userdata['user_timezone']),
				
				'U_EDIT'		=> append_sid("admin_training.php?mode=edit&amp;" . POST_TRAINING_URL . "=" . $training_entry_o[$i]['training_id']),
				'U_DELETE'		=> append_sid("admin_training.php?mode=delete&amp;" . POST_TRAINING_URL . "=" . $training_entry_o[$i]['training_id'])
				
			));
		}
	}
	
	
	$current_page = ( !count($training_entry_o) ) ? 1 : ceil( count($training_entry_o) / $settings['site_entry_per_page'] );

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("admin_training.php?", count($training_entry_o), $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>