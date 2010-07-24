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
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] )
	{
		$module['_headmenu_main']['_submenu_event'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_event';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('event');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_EVENT_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_event'] )
	{
		message(GENERAL_ERROR, sprintf($lang['sprintf_auth_fail'], $lang[$current]));
	}
	
	if ( $no_header )
	{
		redirect('admin/' . append_sid('admin_event.php', true));
	}
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_event.tpl'));
			$template->assign_block_vars('_input', array());
			
			if ( $mode == '_create' && !request('submit', 2) )
			{
				$data = array(
					'event_title'		=> request('event_title', 2),
					'event_desc'		=> '',
					'event_level'		=> GUEST,
					'event_date'		=> time(),
					'event_duration'	=> '0',
					'event_comments'	=> '1',
				);
			}
			else if ( $mode == '_update' && !request('submit', 2) )
			{
				$data = get_data(EVENT, $data_id, 1);
			}
			else
			{
				$event_date		= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$event_duration	= mktime(request('hour', 0), request('min', 0) + request('dmin', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				$data = array(
					'event_title'		=> request('event_title', 2),
					'event_desc'		=> request('event_desc', 2),
					'event_level'		=> request('event_level', 0),
					'event_date'		=> $event_date,
					'event_duration'	=> $event_duration,
					'event_comments'	=> request('event_comments', 0),
				);
			}
			
			$s_select_level = '<select class="select" name="event_level" id="event_level">';
			foreach ( $lang['switch_level'] as $const => $name )
			{
				$selected = ( $data['event_level'] == $const ) ? ' selected="selected"' : '';				
				$s_select_level .= '<option value="' . $const . '"' . $selected . '>&raquo;&nbsp;' . $name . '&nbsp;</option>';
			}
			$s_select_level .= '</select>';
			
			$s_fields	= '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_EVENT_URL . '" value="' . $data_id . '" />';
			$s_sprintf	= ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['event']),
				'L_INPUT'	=> sprintf($lang[$s_sprintf], $lang['event'], $data['event_title']),	
				
				'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['event']),
				'L_DESC'		=> sprintf($lang['sprintf_desc'], $lang['event']),
				'L_DATE'		=> $lang['common_date'],
				'L_LEVEL'		=> $lang['common_userlevel'],
				'L_DURATION'	=> $lang['common_duration'],
				'L_COMMENTS'	=> $lang['common_comments'],
				
				'TITLE'	=> $data['event_title'],
				'DESC'	=> $data['event_desc'],
				
				'S_LEVEL'		=> $s_select_level,
				'S_DAY'			=> select_date('selectsmall', 'day', 'day', date('d', $data['event_date'])),
				'S_MONTH'		=> select_date('selectsmall', 'month', 'month', date('m', $data['event_date'])),
				'S_YEAR'		=> select_date('selectsmall', 'year', 'year', date('Y', $data['event_date'])),
				'S_HOUR'		=> select_date('selectsmall', 'hour', 'hour', date('H', $data['event_date'])),
				'S_MIN'			=> select_date('selectsmall', 'min', 'min', date('i', $data['event_date'])),
				'S_DURATION'	=> select_date('selectsmall', 'duration', 'dmin', ( $data['event_duration'] - $data['event_date'] ) / 60),
				
				'S_COMMENT_YES'	=> ( $data['event_comments'] )	? ' checked="checked"' : '',
				'S_COMMENT_NO'	=> ( !$data['event_comments'] )	? ' checked="checked"' : '',
				
				'S_FIELDS'	=> $s_fields,
				'S_ACTION'	=> append_sid('admin_event.php'),
			));
			
			if ( request('submit', 2) )
			{
				$event_title	= request('event_title', 2);
				$event_desc		= request('event_desc', 2);
				$event_level	= request('event_level', 0);
				$event_comments	= request('event_comments', 0);
				
				$error = ( !$event_title ) ? $lang['msg_select_title'] : '';
				$error .= ( !$event_desc ) ? ( $error ? '<br>' : '' ) . $lang['msg_select_desc'] : '';
				$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br>' : '' ) . $lang['msg_select_date'] : '';
				
				$event_date		= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$event_duration	= mktime(request('hour', 0), request('min', 0) + request('dmin', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$sql = "INSERT INTO " . EVENT . " (event_title, event_desc, event_level, event_date, event_duration, event_comments, event_create)
									VALUES ('$event_title', '$event_desc', '$event_level', '$event_date', '$event_duration', '$event_comments', '" . time() . "')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create_event'] . sprintf($lang['click_return_event'], '<a href="' . append_sid('admin_event.php') . '">', '</a>');
						log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_EVENT, 'create_event');
					}
					else
					{
						$sql = "UPDATE " . EVENT . " SET
									event_title		= '$event_title',
									event_desc		= '$event_desc',
									event_level		= '$event_level',
									event_date		= '$event_date',
									event_duration	= '$event_duration',
									event_comments	= '$event_comments',
									event_update	= '" . time() . "'
								WHERE event_id = $data_id";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['update_event']
							. sprintf($lang['click_return_event'], '<a href="' . append_sid('admin_event.php') . '">', '</a>')
							. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_event.php?mode=_update&amp;' . POST_EVENT_URL . '=' . $data_id) . '">', '</a>');
						log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_EVENT, 'update_event');
					}
					
				#	$monat = request('month', 0);
				#	$oCache -> sCachePath = './../cache/';
				#	subnavi_calendar_' . $monat . '_member
				#	subnavi_calendar_' . $monat . '_guest
		
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
		
		case '_delete':
			
			$data = get_data(EVENT, $data_id, 1);
			
			if ( $data_id && $confirm )
			{	
				$sql = "DELETE FROM " . EVENT . " WHERE event_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
			#	$monat = request('month', 0);
			#	$oCache -> sCachePath = './../cache/';
			#	subnavi_calendar_' . $monat . '_member
			#	subnavi_calendar_' . $monat . '_guest
		
				$message = $lang['delete_event'] . sprintf($lang['click_return_event'], '<a href="' . append_sid('admin_event.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_EVENT, 'delete_event');
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_EVENT_URL . '" value="' . $data_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'	=> $lang['common_confirm'],
					'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_event'], $data['event_title']),
					
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_event.php'),
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_event']);
			}
			
			break;
		
		default:
			
			$template->set_filenames(array('body' => 'style/acp_event.tpl'));
			$template->assign_block_vars('_display', array());
			
			$s_fields = '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['event']),
				'L_CREATE'	=> sprintf($lang['sprintf_creates'], $lang['event']),
				'L_EXPLAIN'	=> $lang['event_explain'],
				
				'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['event']),
				'L_DATE'	=> $lang['common_date'],
				
				'S_FIELDS'	=> $s_fields,
				'S_CREATE'	=> append_sid('admin_event.php?mode=_create'),
				'S_ACTION'	=> append_sid('admin_event.php'),
			));
			
			$data = get_data_array(EVENT, '', 'event_date', 'DESC');
			
			if ( $data )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data)); $i++ )
				{
					$event_id	= $data[$i]['event_id'];
					$event_date	= create_date('d.m.Y', $data[$i]['event_date'], $userdata['user_timezone']);
					$event_time	= create_date('H:i', $data[$i]['event_date'], $userdata['user_timezone']);
					$event_dura	= create_date('H:i', $data[$i]['event_duration'], $userdata['user_timezone']);
					
					$template->assign_block_vars('_display._event_row', array(
						'TITLE'	=> $data[$i]['event_title'],
						'DATE'	=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
						
						'U_UPDATE'	=> append_sid('admin_event.php?mode=_update&amp;' . POST_EVENT_URL . '=' . $event_id),
						'U_DELETE'	=> append_sid('admin_event.php?mode=_delete&amp;' . POST_EVENT_URL . '=' . $event_id),
					));
				}
			}
			else { $template->assign_block_vars('_display._no_entry', array()); }
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>