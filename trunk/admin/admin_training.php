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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);

	if ( $userauth['auth_training'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_teams']['_submenu_training'] = $filename;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/training.php');
	
	$start			= ( request('start') ) ? request('start') : 0;
	$start			= ( $start < 0 ) ? 0 : $start;
	$training_id	= request(POST_TRAINING_URL);
	$confirm		= request('confirm');
	$mode			= request('mode');
	
	if ( !$userauth['auth_training'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}

	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_training.php', true));
	}
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_training.tpl'));
			$template->assign_block_vars('training_edit', array());
		
			if ( $mode == '_create' )
			{
				$training = array (
					'training_vs'		=> ( request('training_vs') ) ? request('training_vs') : request('vs'),
					'team_id'			=> ( request('tean_id') ) ? request('tean_id') : request(POST_TEAMS_URL),
					'match_id'			=> request(POST_MATCH_URL),
					'training_date'		=> time(),
					'training_maps'		=> '',
					'training_text'		=> '',
					'training_duration'	=> '',
				);
				$new_mode = '_create_save';
			}
			else
			{
				$training = get_data(TRAINING, $training_id, 1);
				$new_mode = '_update_save';
			}
			
			$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
			$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $training_id . '" />';
			
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['training']),
				'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['training']),
				
				'L_VS'			=> $lang['training_vs'],
				'L_TEAM'		=> $lang['training_team'],
				'L_MATCH'		=> $lang['training_match'],
				'L_DATE'		=> $lang['training_date'],
				'L_DURATION'	=> $lang['training_duration'],
				'L_MAPS'		=> $lang['training_maps'],
				'L_TEXT'		=> $lang['training_text'],

				'VS'			=> $training['training_vs'],
				'MAPS'			=> $training['training_maps'],
				'TEXT'			=> $training['training_text'],
				
				'S_DAY'			=> select_date('day',		'day',		date('d', $training['training_date'])),
				'S_MONTH'		=> select_date('month',		'month',	date('m', $training['training_date'])),
				'S_YEAR'		=> select_date('year',		'year',		date('Y', $training['training_date'])),
				'S_HOUR'		=> select_date('hour',		'hour',		date('H', $training['training_date'])),
				'S_MIN'			=> select_date('min',		'min',		date('i', $training['training_date'])),
				'S_DURATION'	=> select_date('duration',	'dmin',		( $training['training_duration'] - $training['training_date'] ) / 60),
				
				'S_TEAMS'		=> select_box('team', 'select', $training['team_id']),
				'S_MATCH'		=> select_box('match', 'select', $training['match_id']),
			
				'S_FIELDS'		=> $s_fields,
				'S_ACTION'		=> append_sid('admin_training.php'),
			));
		
			break;
		
		case '_create_save':
		
			$training_vs		= request('training_vs', 'text');
			$team_id			= request('team_id', 'num');
			$match_id			= request('match_id', 'num');
			$training_maps		= request('training_maps', 'text');
			$training_text		= request('training_text', 'text');
			$training_date		= mktime(request('hour'), request('min'), 00, request('month'), request('day'), request('year'));
			$training_duration	= mktime(request('hour'), request('min') + request('dmin'),	00, request('month'), request('day'), request('year'));

			$error_msg = '';
			$error_msg .= ( !$team_id ) ? $lang['msg_select_team'] : '';
			$error_msg .= ( request('dmin') == '00' ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_duration'] : '';
			$error_msg .= ( !checkdate(request('month'), request('day'), request('day', 'year')) ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_date'] : '';
			$error_msg .= ( !$training_maps ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_map'] : '';
		
			if ( $error_msg )
			{
				message(GENERAL_ERROR, $error_msg . $lang['back']);
			}
			
			$sql = "INSERT INTO " . TRAINING . " (training_vs, team_id, match_id, training_date, training_duration, training_create, training_maps, training_text)
						VALUES ('$training_vs', '$team_id', '$match_id', '$training_date', '$training_duration', '" . time() . "', '$training_maps', '$training_text')";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$oCache -> sCachePath = './../cache/';
			$oCache -> deleteCache('subnavi_calendar_' . request('month') . '_member');
			$oCache -> deleteCache('subnavi_training_' . request('month'));

			$message = $lang['create_training'] . sprintf($lang['click_return_training'], '<a href="' . append_sid('admin_training.php') . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TRAINING, 'create_training');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_update_save':
		
			$training_vs		= request('training_vs', 'text');
			$team_id			= request('team_id', 'num');
			$match_id			= request('match_id', 'num');
			$training_maps		= request('training_maps', 'text');
			$training_text		= request('training_text', 'text');
			$training_date		= mktime(request('hour'), request('min'), 00, request('month'), request('day'), request('year'));
			$training_duration	= mktime(request('hour'), request('min') + request('dmin'),	00, request('month'), request('day'), request('year'));
			
			$error_msg = '';
			$error_msg .= ( !$team_id ) ? $lang['msg_select_team'] : '';
			$error_msg .= ( request('dmin') == '00' ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_duration'] : '';
			$error_msg .= ( !checkdate(request('month', 'text'), request('day', 'text'), request('day', 'year')) ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_date'] : '';
			$error_msg .= ( !$training_maps ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_map'] : '';
		
			if ( $error_msg )
			{
				message(GENERAL_ERROR, $error_msg . $lang['back']);
			}

			$sql = "UPDATE " . TRAINING . " SET
						training_vs			= '$training_vs',
						team_id				= '$team_id',
						match_id			= '$match_id',
						training_date		= '$training_date',
						training_duration	= '$training_duration',
						training_maps		= '$training_maps',
						training_text		= '$training_text',
						training_update		= '" . time() . "'
					WHERE training_id = $training_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$oCache -> sCachePath = './../cache/';
			$oCache -> deleteCache('subnavi_calendar_' . request('month', 'text') . '_member');
			$oCache -> deleteCache('subnavi_training_' . request('month', 'text'));
			
			$message = $lang['update_training']
				. sprintf($lang['click_return_training'], '<a href="' . append_sid('admin_training.php') . '">', '</a>')
				. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_training.php?mode=_update&amp;' . POST_TRAINING_URL . '=' . $training_id) . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TRAINING, 'update_training');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_delete':
		
			#	$training = get_data(TRAINING, $training_id, 1);
			
			if ( $training_id && $confirm )
			{	
				$sql = "DELETE FROM " . TRAINING . " WHERE training_id = $training_id";
				if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_COMMENTS . " WHERE training_id = $training_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_COMMENTS_READ . " WHERE training_id = $training_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_USERS . " WHERE training_id = $training_id";
				if ( !($result = $db->sql_query($sql, END_TRANSACTION)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
#				$oCache -> sCachePath = './../cache/';
#				$oCache -> deleteCache('subnavi_training_*');
				
				$message = $lang['delete_training'] . sprintf($lang['click_return_training'], '<a href="' . append_sid('admin_training.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TRAINING, 'delete_training');
				message(GENERAL_MESSAGE, $message);
			
			}
			else if ( $training_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $training_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'	=> $lang['common_confirm'],
					'MESSAGE_TEXT'	=> $lang['confirm_delete_training'],
					
					'S_FIELDS'		=> $s_fields,
					'S_ACTION'		=> append_sid('admin_training.php'),
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_training']);
			}
			
			break;
						
		default:
			
			$template->set_filenames(array('body' => 'style/acp_training.tpl'));
			$template->assign_block_vars('display', array());

			$s_fields = '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['training']),
				'L_CREATE'		=> sprintf($lang['sprintf_creates'], $lang['training']),
				'L_EXPLAIN'		=> $lang['training_explain'],
				
				'L_TRAINING'	=> $lang['training'],
				'L_UPCOMING'	=> $lang['training_upcoming'],
				'L_EXPIRED'		=> $lang['training_expired'],
										 
				'S_TEAMS'		=> select_box('team', 'selectsmall', 0),
				'S_FIELDS'		=> $s_fields,
				'S_CREATE'		=> append_sid('admin_training.php?mode=_create'),
				'S_ACTION'		=> append_sid('admin_training.php'),
			));
			
			$sql = "SELECT tr.*, g.game_image, g.game_size
						FROM " . TRAINING . " tr, " . TEAMS . " t, " . GAMES . " g
						WHERE tr.team_id = t.team_id AND t.team_game = g.game_id
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
						
						$template->assign_block_vars('display.training_row_n', array(
							'CLASS'		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
							
							'NAME'		=> $training_new[$i]['training_vs'],
							'IMAGE'		=> display_gameicon($training_new[$i]['game_size'], $training_new[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $training_new[$i]['training_date'], $userdata['user_timezone']),
							
							'U_UPDATE'	=> append_sid('admin_training.php?mode=_update&amp;' . POST_TRAINING_URL . '=' . $training_new[$i]['training_id']),
							'U_DELETE'	=> append_sid('admin_training.php?mode=_delete&amp;' . POST_TRAINING_URL . '=' . $training_new[$i]['training_id']),
						));
					}
				}
				else
				{
					$template->assign_block_vars('display.no_entry_new', array());
					$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
				}
				
				if ( $training_old )
				{
					for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_old)); $i++)
					{
						$training_id = $training_old[$i]['training_id'];
						
						$template->assign_block_vars('display.training_row_o', array(
							'CLASS'		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
							
							'NAME'		=> $training_old[$i]['training_vs'],
							'IMAGE'		=> display_gameicon($training_old[$i]['game_size'], $training_old[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $training_old[$i]['training_date'], $userdata['user_timezone']),
							
							'U_UPDATE'	=> append_sid('admin_training.php?mode=_update&amp;' . POST_TRAINING_URL . '=' . $training_old[$i]['training_id']),
							'U_DELETE'	=> append_sid('admin_training.php?mode=_delete&amp;' . POST_TRAINING_URL . '=' . $training_old[$i]['training_id']),
						));
					}
				}
				else
				{
					$template->assign_block_vars('display.no_entry_old', array());
					$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
				}
			}
			else
			{
				$training_new = $training_old = '';
				$template->assign_block_vars('display.no_entry_new', array());
				$template->assign_block_vars('display.no_entry_old', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			
			$current_page = ( !count($training_data) ) ? 1 : ceil( count($training_data) / $settings['site_entry_per_page'] );
		
			$template->assign_vars(array(
				'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 
				'PAGINATION'	=> ( count($training_data) ) ? generate_pagination('admin_match.php?', count($training_data), $settings['site_entry_per_page'], $start) : '',		
			));
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>