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
	
	if ( $userauth['auth_event'] || $userdata['user_level'] == ADMIN )
	{
		$module['main']['event'] = $filename;
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
	
	if ( !$userauth['auth_event'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_event.php', true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	$mode		= request('mode', true);
	$event_id	= request(POST_EVENT_URL);
	
	$show_index = '';
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case 'event_add':
			case 'event_edit':
			
				$template->set_filenames(array('body' => 'style/acp_event.tpl'));
				$template->assign_block_vars('event_edit', array());
				
				if ( $mode == 'event_edit' )
				{
					$event		= get_data('event', $event_id, 0);
					$new_mode	= 'event_update';
				}
				else
				{
					$event = array (
						'event_title'		=> trim($HTTP_POST_VARS['event_title']),
						'event_description'	=> '',
						'event_level'		=> '0',
						'event_date'		=> time(),
						'event_duration'	=> '0',
						'event_comments'	=> '0',
					);

					$new_mode = 'event_create';
				}
				
				$s_select_level = '<select class="select" name="event_level">';
				foreach ( $lang['switch_level'] as $const => $name )
				{
					$selected = ( $const == $event['event_level'] ) ? ' selected="selected"' : '';
					
					$s_select_level .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
				}
				$s_select_level .= '</select>';
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_EVENT_URL . '" value="' . $event_id . '" />';

				$template->assign_vars(array(
					'L_EVENT_HEAD'			=> $lang['event_head'],
					'L_EVENT_NEW_EDIT'		=> ( $mode == 'event_add' ) ? $lang['event_add'] : $lang['event_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_EVENT_TITLE'			=> $lang['event_title'],
					'L_EVENT_DESCRIPTION'	=> $lang['event_description'],
					'L_EVENT_LEVEL'			=> $lang['event_level'],
					'L_EVENT_DATE'			=> $lang['event_date'],
					'L_EVENT_DURATION'		=> $lang['event_duration'],
					'L_EVENT_COMMENTS'		=> $lang['common_comments'],
					
					'L_SUBMIT'	=> $lang['common_submit'],
					'L_RESET'	=> $lang['common_reset'],
					'L_YES'		=> $lang['common_yes'],
					'L_NO'		=> $lang['common_no'],
					
					'EVENT_TITLE'			=> $event['event_title'],
					'EVENT_DESCRIPTION'		=> $event['event_description'],
					
					'S_DAY'					=> select_date('day', 'day',		date('d', $event['event_date'])),
					'S_MONTH'				=> select_date('month', 'month',	date('m', $event['event_date'])),
					'S_YEAR'				=> select_date('year', 'year',		date('Y', $event['event_date'])),
					'S_HOUR'				=> select_date('hour', 'hour',		date('H', $event['event_date'])),
					'S_MIN'					=> select_date('min', 'min',		date('i', $event['event_date'])),
					'S_DURATION'			=> select_date('duration', 'dmin',	($event['event_duration'] - $event['event_date']) / 60),
					
					'S_CHECKED_COMMENT_YES'	=> ( $event['event_comments'] )		? ' checked="checked"' : '',
					'S_CHECKED_COMMENT_NO'	=> ( !$event['event_comments'] )	? ' checked="checked"' : '',
					
					'S_EVENT_LEVEL'			=> $s_select_level,
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_EVENT_ACTION'		=> append_sid('admin_event.php'),
				));
			
				$template->pparse('body');
				
			break;
			
			case 'event_create':
				
				$event_title		= ( isset($HTTP_POST_VARS['event_title']) )			? trim($HTTP_POST_VARS['event_title']) : '';
				$event_description	= ( isset($HTTP_POST_VARS['event_description']) )	? trim($HTTP_POST_VARS['event_description']) : '';
				$event_level		= ( isset($HTTP_POST_VARS['event_level']) )			? intval($HTTP_POST_VARS['event_level']) : '';
				$event_comments		= ( isset($HTTP_POST_VARS['event_comments']) )		? intval($HTTP_POST_VARS['event_comments']) : '';
				
				$error = ''; 
				$error_msg = '';
			
				if ( !$event_title )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_title'];
				}
				
				if ( !$event_description )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_description'];
				}
				
				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_date'];
				}
				
				if ( $error )
				{
					message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
				}
								
				$event_date = mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				$event_duration	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'] + $HTTP_POST_VARS['dmin'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				
				$sql = 'INSERT INTO ' . EVENT . " (event_title, event_description, event_level, event_date, event_duration, event_comments)
					VALUES ('" . str_replace("\'", "''", $event_title) . "', '" . $event_description . "', $event_level, $event_date, $event_duration, $event_comments)";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$monat = $HTTP_POST_VARS['month'];
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('calendar_mini_' . $monat . '_member');
				$oCache -> deleteCache('calendar_mini_' . $monat . '_guest');
				$oCache -> deleteCache('subnavi_match_' . $monat . '_member');
				$oCache -> deleteCache('subnavi_match_' . $monat . '_guest');
				$oCache -> deleteCache('subnavi_training_' . $monat);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_EVENT, 'acp_event_add', $event_title);
	
				$message = $lang['create_event'] . sprintf($lang['click_return_event'], '<a href="' . append_sid('admin_event.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'event_update':
			
				$event_title		= ( isset($HTTP_POST_VARS['event_title']) )			? trim($HTTP_POST_VARS['event_title']) : '';
				$event_description	= ( isset($HTTP_POST_VARS['event_description']) )	? trim($HTTP_POST_VARS['event_description']) : '';
				$event_level		= ( isset($HTTP_POST_VARS['event_level']) )			? intval($HTTP_POST_VARS['event_level']) : '';
				$event_comments		= ( isset($HTTP_POST_VARS['event_comments']) )		? intval($HTTP_POST_VARS['event_comments']) : '';
				
				$error = ''; 
				$error_msg = '';
			
				if ( !$event_title )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_title'];
				}
				
				if ( !$event_description )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_description'];
				}
				
				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_date'];
				}
				
				if ( $error )
				{
					message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
				}
								
				$event_date = mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				$event_duration	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'] + $HTTP_POST_VARS['dmin'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);

				$sql = "UPDATE " . EVENT . " SET
							event_title			= '" . str_replace("\'", "''", $event_title) . "',
							event_description	= '" . $event_description . "',
							event_level			= $event_level,
							event_date			= $event_date,
							event_duration		= $event_duration,
							event_comments		= $event_comments
						WHERE event_id = " . $event_id;
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$monat = $HTTP_POST_VARS['month'];
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('calendar_mini_' . $monat . '_member');
				$oCache -> deleteCache('calendar_mini_' . $monat . '_guest');
				$oCache -> deleteCache('subnavi_match_' . $monat . '_member');
				$oCache -> deleteCache('subnavi_match_' . $monat . '_guest');
				$oCache -> deleteCache('subnavi_training_' . $monat);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_EVENT, 'acp_event_edit');
				
				$message = $lang['update_event'] . sprintf($lang['click_return_event'], '<a href="' . append_sid('admin_event.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'event_delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $event_id && $confirm )
				{	
					$event = get_data('event', $event_id, 0);
				
					$sql = 'DELETE FROM ' . EVENT . ' WHERE event_id = ' . $event_id;
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_EVENT, 'acp_event_delete', $event['event_title']);
					
					$message = $lang['delete_event'] . sprintf($lang['click_return_event'], '<a href="' . append_sid('admin_event.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}
				else if ( $event_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="event_delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_EVENT_URL . '" value="' . $event_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_event'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid('admin_event.php'),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_event']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
			
				message_die(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_event.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_EVENT_HEAD'			=> $lang['event_head'],
		'L_EVENT_EXPLAIN'		=> $lang['event_explain'],
		'L_EVENT_TITLE'			=> $lang['event_title'],
		'L_EVENT_DATE'			=> $lang['event_date'],
		
		'L_EVENT_ADD'			=> $lang['event_add'],
		
		'L_EDIT'				=> $lang['edit'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'S_EVENT_ACTION'		=> append_sid('admin_event.php'),
	));
	
	$sql = 'SELECT * FROM ' . EVENT . ' ORDER BY event_date';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$event_data = $db->sql_fetchrowset($result);
	
	if ( !$event_data )
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($event_data)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$event_id	= $event_data[$i]['event_id'];
			$event_date	= create_date($userdata['user_dateformat'], $event_data[$i]['event_date'], $userdata['user_timezone']);
			
			if ( $config['time_today'] < $event_data[$i]['event_date'])
			{ 
				$event_date = sprintf($lang['today_at'], create_date($config['default_timeformat'], $event_data[$i]['event_date'], $userdata['user_timezone'])); 
			}
			else if ( $config['time_yesterday'] < $event_data[$i]['event_date'])
			{ 
				$event_date = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $event_data[$i]['event_date'], $userdata['user_timezone'])); 
			}
				
			$template->assign_block_vars('display.event_row', array(
				'CLASS' 		=> $class,
				
				'EVENT_TITLE'	=> $event_data[$i]['event_title'],
				'EVENT_DATE'	=> $event_date,
				
				'U_EDIT'		=> append_sid('admin_event.php?mode=event_edit&amp;' . POST_EVENT_URL . '=' . $event_id),
				'U_DELETE'		=> append_sid('admin_event.php?mode=event_delete&amp;' . POST_EVENT_URL . '=' . $event_id)
			));
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>