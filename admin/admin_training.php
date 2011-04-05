<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);

	if ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] )
	{
		$module['_headmenu_05_teams']['_submenu_training'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_training';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('training');
	
	$error	= '';
	$index	= '';
	$log	= LOG_SEK_TRAINING;
	$url	= POST_TRAINING_URL;
	$team	= POST_TEAMS_URL;
	$match	= POST_MATCH_URL;
	$file	= basename(__FILE__);
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	
	$sort		= ( request('sort', 1) ) ? request('sort', 1) : '';
	$data_id	= request($url, 0);
	$team_id	= request($team, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$fields	= '';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['training']);
		
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_training'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}

	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_training.tpl'));
			$template->assign_block_vars('_input', array());
			
			if ( $mode == '_create' && !request('submit', 1) )
			{
				$data = array(
					'training_vs'		=> ( request('training_vs', 2) ) ? request('training_vs', 2) : request('vs', 2),
					'team_id'			=> ( request('team_id', 0) ) ? request('team_id', 0) : $team_id,
					'match_id'			=> request($match),
					'training_maps'		=> '',
					'training_text'		=> '',
					'training_date'		=> time(),
					'training_duration'	=> '',
					'training_create'	=> time(),
				);
			}
			else if ( $mode == '_update' && !request('submit', 1) )
			{
				$data = data(TRAINING, $data_id, false, 1, 1);
			}
			else
			{
				$training_date	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$training_dura	= mktime(request('hour', 0), request('min', 0) + request('dmin', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				$data = array(
					'training_vs'		=> request('training_vs', 2),
					'team_id'			=> request('team_id', 0),
					'match_id'			=> request('match_id', 0),
					'training_maps'		=> request('training_maps', 4),
					'training_text'		=> request('training_text', 2),
					'training_date'		=> $training_date,
					'training_duration'	=> ( $training_dura - $training_date ) / 60,
					'training_create'	=> request('training_create', 0),
				);
			}
		
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			$fields .= '<input type="hidden" name="training_create" value="' . $data['training_create'] . '" />';
			
			/*
			if ( $data['team_id'] )
			{
				$sql_select = 'SELECT g.game_tag FROM ' . GAMES . ' g, ' . TEAMS . ' t WHERE g.game_id = t.team_game AND t.team_id = ' . $data['team_id'];
				if ( !($result = $db->sql_query($sql_select)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql_select);
				}
				$select = $db->sql_fetchrow($result);
				
				$s_select = $select['game_tag'];
			}
			else
			{
				$s_select = '*';
			}
			*/
			
			$sql = 'SELECT team_id, team_name FROM ' . TEAMS . ' ORDER BY team_order';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$teams = $db->sql_fetchrowset($result);
			
			debug($data);
			
			$select = '';
				
			if ( $teams )
			{	
				$select .= '<select class="select" name="team_id" id="inputString" onkeyup="lookup(this.value);" onchange="lookup(this.value);">';	
				$select .= '<option value="-1">Auswahl</option>';
				
				foreach ( $teams as $info => $value )
				{
					$selected = ( $data['team_id'] == $value['team_id'] ) ? 'selected="selected"' : '';
					$select .= "<option value=\"" . $value['team_id'] . "\" $selected>&raquo;&nbsp;" . $value['team_name'] . "&nbsp;</option>";
				}
				$select .= '</select>';
			}
			
			$template->assign_vars(array(
				'L_HEAD'		=> $acp_title,
				'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['training'], $data['training_vs']),
				'L_VS'			=> $lang['training_vs'],
				'L_TEAM'		=> $lang['training_team'],
				'L_MATCH'		=> $lang['training_match'],
				'L_DATE'		=> $lang['training_date'],
				'L_DURATION'	=> $lang['training_duration'],
				'L_MAPS'		=> $lang['training_maps'],
				'L_TEXT'		=> $lang['training_text'],

				'VS'			=> $data['training_vs'],
				'MAPS'			=> $data['training_maps'],
				'TEXT'			=> $data['training_text'],
				
			#	'S_MAPS'		=> select_maps($s_select),
			
				'S_TEAMS'		=> $select,
				
			#	'S_TEAMS'		=> select_box('team',	'select', $data['team_id']),
				'S_MATCH'		=> select_box('match',	'select', $data['match_id']),
				'S_DAY'			=> select_date('selectsmall', 'day',		'day',		date('d', $data['training_date']), $data['training_create']),
				'S_MONTH'		=> select_date('selectsmall', 'month',		'month',	date('m', $data['training_date']), $data['training_create']),
				'S_YEAR'		=> select_date('selectsmall', 'year',		'year',		date('Y', $data['training_date']), $data['training_create']),
				'S_HOUR'		=> select_date('selectsmall', 'hour',		'hour',		date('H', $data['training_date']), $data['training_create']),
				'S_MIN'			=> select_date('selectsmall', 'min',		'min',		date('i', $data['training_date']), $data['training_create']),
				'S_DURATION'	=> select_date('selectsmall', 'duration',	'dmin',		( $data['training_duration'] - $data['training_date'] ) / 60),
				'S_FIELDS'		=> $fields,
				'S_ACTION'		=> append_sid($file),
			));
			
			if ( request('submit', 1) )
			{
				$team_id			= request('team_id', 0);
				$match_id			= request('match_id', 0);
				$training_vs		= request('training_vs', 2);
				$training_create	= request('training_create', 0);
				$training_maps		= request('training_maps', 4);
				$training_text		= request('training_text', 2);
				$training_date		= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$training_dura		= mktime(request('hour', 0), request('min', 0) + request('dmin', 0),	00, request('month', 0), request('day', 0), request('year', 0));
				
				$error .= ( !$training_vs )			? ( $error ? '<br />' : '' ) . $lang['msg_select_rival'] : '';
				$error .= ( $team_id == '-1' )	? ( $error ? '<br />' : '' ) . $lang['msg_select_team'] : '';
				$error .= ( !$training_maps )			? ( $error ? '<br />' : '' ) . $lang['msg_select_map'] : '';
				$error .= ( $training_dura == '00' )	? ( $error ? '<br />' : '' ) . $lang['msg_select_duration'] : '';
				$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
				$error .= ( time() >= $training_date )	? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$sql = "INSERT INTO " . TRAINING . " (training_vs, team_id, match_id, training_date, training_duration, training_create, training_maps, training_text)
									VALUES ('$training_vs', '$team_id', '$match_id', '$training_date', '$training_dura', '$training_create', '$training_maps', '$training_text')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					}
					else
					{
						$sql = "UPDATE " . TRAINING . " SET
									training_vs			= '$training_vs',
									team_id				= '$team_id',
									match_id			= '$match_id',
									training_date		= '$training_date',
									training_duration	= '$training_dura',
									training_maps		= '$training_maps',
									training_text		= '$training_text',
									training_update		= '" . time() . "'
								WHERE training_id = $data_id";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['update']
							. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
							. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
					}
					
				#	#$oCache -> sCachePath = './../cache/';
				#	#$oCache -> deleteCache('subnavi_calendar_' . request('month', 0) . '_member');
				#	#$oCache -> deleteCache('subnavi_training_' . request('month', 0));
					
					log_add(LOG_ADMIN, $log, $mode, $training_vs);
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					log_add(LOG_ADMIN, $log, $mode, $error);
					
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
			
		case '_delete':
		
			$data = data(TRAINING, $data_id, false, 1, 1);
		
			if ( $data_id && $confirm )
			{
				$sql = "DELETE FROM " . TRAINING . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_COMMENTS . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_COMMENTS_READ . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_USERS . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql, END_TRANSACTION) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
			#	#$oCache -> sCachePath = './../cache/';
			#	#$oCache -> deleteCache('subnavi_training_*');
				
				$message = $lang['delete'] . sprintf($lang['click_return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
				
				log_add(LOG_ADMIN, $log, $mode, $data['training_vs']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['training_vs']),
					
					
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['training'])); }
			
			break;
						
		default:
		
			$template->set_filenames(array('body' => 'style/acp_training.tpl'));
			$template->assign_block_vars('_display', array());
			
			$teams = data(TEAMS, '', true, 0, 0);
			
			$s_action = '<select class="selectsmall" name="' . $team . '" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
			$s_action .= '<option value="0">&raquo;&nbsp;' . $lang['msg_select_team'] . '&nbsp;</option>';
			
			foreach ( $teams as $info => $value )
			{
				$selected = ( $value['team_id'] == $team_id ) ? 'selected="selected"' : '';
				$s_action .= '<option value="' . $value['team_id'] . '" ' . $selected . '>' . sprintf($lang['sprintf_select_format'], $value['team_name']) . '</option>';
			}
			$s_action .= '</select>';
			
			$fields = '<input type="hidden" name="mode" value="_create" />';
			
			$template->assign_vars(array(
				'L_HEAD'	=> $acp_title,
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['training']),
				'L_EXPLAIN'	=> $lang['explain'],
				
				'L_UPCOMING'	=> $lang['upcoming'],
				'L_EXPIRED'		=> $lang['expired'],
				
				'S_LIST'	=> $s_action,
				'S_TEAMS'	=> select_box('team', 'selectsmall', 0),
				
				'S_CREATE'	=> append_sid("$file?mode=_create"),
				'S_ACTION'	=> append_sid($file),
				'S_FIELDS'	=> $fields,
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
			$training = $db->sql_fetchrowset($result);
			
			if ( $training )
			{
				$training_new = $training_old = array();
					
				foreach ( $training as $training => $row )
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
					for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_new)); $i++ )
					{
						$training_id = $training_new[$i]['training_id'];
						
						$template->assign_block_vars('_display._training_new_row', array(
							'NAME'		=> $training_new[$i]['training_vs'],
							'IMAGE'		=> display_gameicon($training_new[$i]['game_size'], $training_new[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $training_new[$i]['training_date'], $userdata['user_timezone']),
							
							'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$training_id"),
							'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$training_id"),
						));
					}
				}
				else { $template->assign_block_vars('_display._no_entry_new', array()); }
				
				if ( $training_old )
				{
					for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_old)); $i++ )
					{
						$training_id = $training_old[$i]['training_id'];
						
						$template->assign_block_vars('_display._training_old_row', array(
							'NAME'	=> $training_old[$i]['training_vs'],
							'IMAGE'	=> display_gameicon($training_old[$i]['game_size'], $training_old[$i]['game_image']),
							'DATE'	=> create_date($userdata['user_dateformat'], $training_old[$i]['training_date'], $userdata['user_timezone']),
							
							'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$training_id"),
							'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$training_id"),
						));
					}
				}
				else { $template->assign_block_vars('_display._no_entry_old', array()); }
			}
			else
			{
				$template->assign_block_vars('_display._no_entry_new', array());
				$template->assign_block_vars('_display._no_entry_old', array());
			}
			
			$current_page = ( !count($training) ) ? 1 : ceil( count($training) / $settings['site_entry_per_page'] );
		
			$template->assign_vars(array(
				'PAGE_NUMBER'	=> ( count($training) ) ? sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ) : '',
				'PAGINATION'	=> ( count($training) ) ? generate_pagination('admin_match.php?', count($training), $settings['site_entry_per_page'], $start) : '',
			));
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>