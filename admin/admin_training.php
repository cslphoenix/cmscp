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

	if ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] )
	{
		$module['_headmenu_teams']['_submenu_training'] = $filename;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_training';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('training');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$sort		= ( request('sort', 1) ) ? request('sort', 1) : '';
	$data_id	= request(POST_TRAINING_URL, 0);
	$team_id	= request(POST_TEAMS_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_training'] )
	{
		message(GENERAL_ERROR, sprintf($lang['sprintf_auth_fail'], $lang[$current]));
	}

	if ( $no_header )
	{
		redirect('admin/' . append_sid('admin_training.php', true));
	}
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_training.tpl'));
			$template->assign_block_vars('_input', array());
			
			if ( $mode == '_create' && !request('submit', 2) )
			{
				$data = array(
					'training_vs'		=> ( request('training_vs', 2) ) ? request('training_vs', 2) : request('vs', 2),
					'team_id'			=> ( request('team_id', 0) ) ? request('team_id', 0) : $team_id,
					'match_id'			=> request(POST_MATCH_URL),
					'training_date'		=> time(),
					'training_maps'		=> '',
					'training_text'		=> '',
					'training_duration'	=> '',
				);
			}
			else if ( $mode == '_update' && !request('submit', 2) )
			{
				$data = get_data(TRAINING, $data_id, 1);
			}
			else
			{
				$training_date		= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$training_duration	= mktime(request('hour', 0), request('min', 0) + request('dmin', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				$data = array(
					'training_vs'		=> request('training_vs', 2),
					'team_id'			=> request('team_id', 0),
					'match_id'			=> request('match_id', 0),
					'training_date'		=> $training_date,
					'training_maps'		=> request('training_maps', 2),
					'training_text'		=> request('training_text', 2),
					'training_duration'	=> ( $training_duration - $training_date ) / 60,
				);
			}
		
			$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
			$s_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $data_id . '" />';
			
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['training']),
				'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['training']),
				'L_INFOS'		=> $lang['common_data_input'],
				
				'L_VS'			=> $lang['training_vs'],
				'L_TEAM'		=> $lang['training_team'],
				'L_MATCH'		=> $lang['training_match'],
				'L_DATE'		=> $lang['training_date'],
				'L_DURATION'	=> $lang['training_duration'],
				'L_MAPS'		=> $lang['training_maps'],
				'L_TEXT'		=> $lang['training_text'],

				'VS'	=> $data['training_vs'],
				'MAPS'	=> $data['training_maps'],
				'TEXT'	=> $data['training_text'],
				
				'S_DAY'			=> select_date('selectsmall', 'day',		'day',		date('d', $data['training_date'])),
				'S_MONTH'		=> select_date('selectsmall', 'month',		'month',	date('m', $data['training_date'])),
				'S_YEAR'		=> select_date('selectsmall', 'year',		'year',		date('Y', $data['training_date'])),
				'S_HOUR'		=> select_date('selectsmall', 'hour',		'hour',		date('H', $data['training_date'])),
				'S_MIN'			=> select_date('selectsmall', 'min',		'min',		date('i', $data['training_date'])),
				'S_DURATION'	=> select_date('selectsmall', 'duration',	'dmin',		( $data['training_duration'] - $data['training_date'] ) / 60),
				
				'S_TEAMS'	=> select_box('team',	'select', $data['team_id']),
				'S_MATCH'	=> select_box('match',	'select', $data['match_id']),
			
				'S_FIELDS'	=> $s_fields,
				'S_ACTION'	=> append_sid('admin_training.php'),
			));
			
			if ( request('submit', 2) )
			{
				$training_vs		= request('training_vs', 2);
				$team_id			= request('team_id', 0);
				$match_id			= request('match_id', 0);
				$training_maps		= request('training_maps', 2);
				$training_text		= request('training_text', 2);
				$training_date		= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$training_duration	= mktime(request('hour', 0), request('min', 0) + request('dmin', 0),	00, request('month', 0), request('day', 0), request('year', 0));
				
				$error = '';
				$error .= ( !$training_vs ) ? $lang['msg_select_rival'] : '';
				$error .= ( $team_id == -1 ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_team'] : '';
				$error .= ( !$training_maps ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_map'] : '';
				$error .= ( $training_duration == '00' ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_duration'] : '';
				$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
				$error .= ( time() >= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0))) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$max_row	= get_data_max(GAMES, 'game_order', '');
						$next_order	= $max_row['max'] + 10;
						
						$sql = "INSERT INTO " . TRAINING . " (training_vs, team_id, match_id, training_date, training_duration, training_create, training_maps, training_text) VALUES ('$training_vs', '$team_id', '$match_id', '$training_date', '$training_duration', '" . time() . "', '$training_maps', '$training_text')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create_training'] . sprintf($lang['click_return_training'], '<a href="' . append_sid('admin_training.php') . '">', '</a>');
						log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'create_training');
					}
					else
					{
						$sql = "UPDATE " . TRAINING . " SET
									training_vs			= '$training_vs',
									team_id				= '$team_id',
									match_id			= '$match_id',
									training_date		= '$training_date',
									training_duration	= '$training_duration',
									training_maps		= '$training_maps',
									training_text		= '$training_text',
									training_update		= '" . time() . "'
								WHERE training_id = $data_id";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['update_training']
							. sprintf($lang['click_return_training'], '<a href="' . append_sid('admin_training.php') . '">', '</a>')
							. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_training.php?mode=_update&amp;' . POST_TRAINING_URL . '=' . $data_id) . '">', '</a>');
						log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GAME, 'update_training');
					}
					
				#	$oCache -> sCachePath = './../cache/';
				#	$oCache -> deleteCache('subnavi_calendar_' . request('month', 0) . '_member');
				#	$oCache -> deleteCache('subnavi_training_' . request('month', 0));
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					$template->set_filenames(array('reg_header' => 'style/error_body.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
		
			break;
			
		case '_delete':
		
			$data = get_data(TRAINING, $data_id, 1);
			
			if ( $data_id && $confirm )
			{	
				$sql = "DELETE FROM " . TRAINING . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_COMMENTS . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_COMMENTS_READ . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_USERS . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql, END_TRANSACTION) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
			#	$oCache -> sCachePath = './../cache/';
			#	$oCache -> deleteCache('subnavi_training_*');
				
				$message = $lang['delete_training'] . sprintf($lang['click_return_training'], '<a href="' . append_sid('admin_training.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TRAINING, 'delete_training');
				message(GENERAL_MESSAGE, $message);
			
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $data_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'	=> $lang['common_confirm'],
					'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_training'], $data['training_vs']),
					
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_training.php'),
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_training']);
			}
			
			break;
						
		default:
			
			$template->set_filenames(array('body' => 'style/acp_training.tpl'));
			$template->assign_block_vars('_display', array());
			
			$data = get_data_array(TEAMS, '', 'team_order', 'ASC');
			
			$s_action = '<select class="selectsmall" name="' . POST_TEAMS_URL . '" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
			$s_action .= '<option value="0">&raquo;&nbsp;' . $lang['msg_select_team'] . '&nbsp;</option>';
			
			foreach ( $data as $info => $value )
			{
				$selected = ( $value['team_id'] == $team_id ) ? 'selected="selected"' : '';
				$s_action .= '<option value="' . $value['team_id'] . '" ' . $selected . '>&raquo;&nbsp;' . $value['team_name'] . '&nbsp;</option>';
			}
			
			$s_action .= '</select>';
			
			$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['training']),
				'L_CREATE'	=> sprintf($lang['sprintf_creates'], $lang['training']),
				'L_EXPLAIN'	=> $lang['training_explain'],
				
				'L_TRAINING'	=> $lang['training'],
				'L_UPCOMING'	=> $lang['training_upcoming'],
				'L_EXPIRED'		=> $lang['training_expired'],
				
				'L_NOENTRY'	=> $lang['no_entry'],
										 
				'S_LIST'	=> $s_action,
				'S_FIELDS'	=> $s_fields,
				'S_TEAMS'	=> select_box('team', 'selectsmall', 0),
				'S_CREATE'	=> append_sid('admin_training.php?mode=_create'),
				'S_ACTION'	=> append_sid('admin_training.php'),
			));
			
			$select_id = ( $team_id >= '1' ) ? "AND tr.team_id = $team_id" : '';
			
			$sql = "SELECT tr.*, g.game_image, g.game_size
						FROM " . TRAINING . " tr, " . TEAMS . " t, " . GAMES . " g
						WHERE tr.team_id = t.team_id AND t.team_game = g.game_id $select_id
					ORDER BY training_date";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$training_data = $db->sql_fetchrowset($result);
			
			if ( $training_data )
			{
				$training_new = $training_old = array();
					
				foreach ( $training_data as $training => $row )
				{
					if ( $row['training_date'] > time() )
					{
						$training_new[] = $row;
					}
					else if ( $row['training_date'] < time() )
					{
						$training_old[] = $row;
					}
				}
				
				if ( $training_new )
				{
					for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_new)); $i++)
					{
						$training_id = $training_new[$i]['training_id'];
						
						$template->assign_block_vars('_display._training_row_new', array(
							'NAME'		=> $training_new[$i]['training_vs'],
							'IMAGE'		=> display_gameicon($training_new[$i]['game_size'], $training_new[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $training_new[$i]['training_date'], $userdata['user_timezone']),
							
							'U_UPDATE'	=> append_sid('admin_training.php?mode=_update&amp;' . POST_TRAINING_URL . '=' . $training_id),
							'U_DELETE'	=> append_sid('admin_training.php?mode=_delete&amp;' . POST_TRAINING_URL . '=' . $training_id),
						));
					}
				}
				else
				{
					$template->assign_block_vars('_display._no_entry_new', array());
				}
				
				if ( $training_old )
				{
					for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_old)); $i++)
					{
						$training_id = $training_old[$i]['training_id'];
						
						$template->assign_block_vars('_display._training_row_old', array(
							'NAME'		=> $training_old[$i]['training_vs'],
							'IMAGE'		=> display_gameicon($training_old[$i]['game_size'], $training_old[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $training_old[$i]['training_date'], $userdata['user_timezone']),
							
							'U_UPDATE'	=> append_sid('admin_training.php?mode=_update&amp;' . POST_TRAINING_URL . '=' . $training_id),
							'U_DELETE'	=> append_sid('admin_training.php?mode=_delete&amp;' . POST_TRAINING_URL . '=' . $training_id),
						));
					}
				}
				else
				{
					$template->assign_block_vars('_display._no_entry_old', array());
				}
			}
			else
			{
				$training_new = $training_old = '';
				$template->assign_block_vars('_display._no_entry_new', array());
				$template->assign_block_vars('_display._no_entry_old', array());
			}
			
			$current_page = ( !count($training_data) ) ? 1 : ceil( count($training_data) / $settings['site_entry_per_page'] );
		
			$template->assign_vars(array(
				'PAGE_NUMBER'	=> ( count($training_data) ) ? sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ) : '',
				'PAGINATION'	=> ( count($training_data) ) ? generate_pagination('admin_match.php?', count($training_data), $settings['site_entry_per_page'], $start) : '',
			));
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>