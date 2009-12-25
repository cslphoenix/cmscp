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
		$module['_headmenu_main']['_submenu_event'] = $filename;
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
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/event.php');
	
	$start		= ( request('start') ) ? request('start') : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$event_id	= request(POST_EVENT_URL);
	$confirm	= request('confirm');
	$mode		= request('mode');
	
	if ( !$userauth['auth_event'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_event.php', true));
	}
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_event.tpl'));
			$template->assign_block_vars('event_edit', array());
			
			if ( $mode == '_create' )
			{
				$event = array (
					'event_title'		=> request('event_title', 'text'),
					'event_desc'		=> '',
					'event_level'		=> '0',
					'event_date'		=> time(),
					'event_duration'	=> '0',
					'event_comments'	=> '1',
				);
				$new_mode = '_create_save';
			}
			else
			{
				$event		= get_data(EVENT, $event_id, 1);
				$new_mode	= '_update_save';
			}
			
			$s_select_level = '<select class="select" name="event_level">';
			foreach ( $lang['switch_level'] as $const => $name )
			{
				$selected = ( $const == $event['event_level'] ) ? ' selected="selected"' : '';
				
				$s_select_level .= '<option value="' . $const . '"' . $selected . '>' . $name . '&nbsp;</option>';
			}
			$s_select_level .= '</select>';
			
			$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
			$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_EVENT_URL . '" value="' . $event_id . '" />';
			
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['event']),
				'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['event']),				
				'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['event']),
				'L_DESC'		=> sprintf($lang['sprintf_desc'], $lang['event']),
				
				'L_DATE'		=> $lang['common_date'],
				'L_LEVEL'		=> $lang['common_userlevel'],
				'L_DURATION'	=> $lang['common_duration'],
				'L_COMMENTS'	=> $lang['common_comments'],
				
				'TITLE'			=> $event['event_title'],
				'DESC'			=> $event['event_desc'],
				
				'S_LEVEL'		=> $s_select_level,
				'S_DAY'			=> select_date('day',		'day',		date('d', $event['event_date'])),
				'S_MONTH'		=> select_date('month',		'month',	date('m', $event['event_date'])),
				'S_YEAR'		=> select_date('year',		'year',		date('Y', $event['event_date'])),
				'S_HOUR'		=> select_date('hour',		'hour',		date('H', $event['event_date'])),
				'S_MIN'			=> select_date('min',		'min',		date('i', $event['event_date'])),
				'S_DURATION'	=> select_date('duration',	'dmin',		($event['event_duration'] - $event['event_date']) / 60),
				'S_COMMENT_YES'	=> ( $event['event_comments'] )		? ' checked="checked"' : '',
				'S_COMMENT_NO'	=> ( !$event['event_comments'] )	? ' checked="checked"' : '',
				
				'S_FIELDS'		=> $s_fields,
				'S_ACTION'		=> append_sid('admin_event.php'),
			));
			
			break;
		
		case '_create_save':
		
			$event_title	= request('event_title', 'text');
			$event_desc		= request('event_desc', 'text');
			$event_level	= request('event_level', 'num');
			$event_comments	= request('event_comments', 'num');
			
			$error_msg = '';
			$error_msg .= ( !$event_title ) ? $lang['msg_select_title'] : '';
			$error_msg .= ( !$event_desc ) ? '<br>' . $lang['msg_select_description'] : '';
			$error_msg .= ( !checkdate(request('month'), request('day'), request('year')) ) ? '<br>' . $lang['msg_select_date'] : '';
		
			if ( $error_msg )
			{
				message(GENERAL_ERROR, $error_msg . $lang['back']);
			}
							
			$event_date		= mktime(request('hour'),	request('min'),						00, request('month'),	request('day'),	request('year'));
			$event_duration	= mktime(request('hour'),	request('min') + request('dmin'),	00, request('month'),	request('day'),	request('year'));
			
			$sql = "INSERT INTO " . EVENT . " (event_title, event_desc, event_level, event_date, event_duration, event_comments, event_create)
						VALUES ('$event_title', '$event_desc', '$event_level', '$event_date', '$event_duration', '$event_comments', '" . time() . "')";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
#			$monat = request('month', 'num');
#			$oCache -> sCachePath = './../cache/';
#			subnavi_calendar_' . $monat . '_member
#			subnavi_calendar_' . $monat . '_guest
			
			$message = $lang['create_event'] . sprintf($lang['click_return_event'], '<a href="' . append_sid('admin_event.php') . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_EVENT, 'create_event');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_update_save':
		
			$event_title	= request('event_title', 'text');
			$event_desc		= request('event_desc', 'text');
			$event_level	= request('event_level', 'num');
			$event_comments	= request('event_comments', 'num');
			
			$error_msg = '';
			$error_msg .= ( !$event_title ) ? $lang['msg_select_title'] : '';
			$error_msg .= ( !$event_desc ) ? '<br>' . $lang['msg_select_description'] : '';
			$error_msg .= ( !checkdate(request('month'), request('day'), request('year')) ) ? '<br>' . $lang['msg_select_date'] : '';
		
			if ( $error_msg )
			{
				message(GENERAL_ERROR, $error_msg . $lang['back']);
			}
			
			$event_date		= mktime(request('hour'),	request('min'),						00, request('month'),	request('day'),	request('year'));
			$event_duration	= mktime(request('hour'),	request('min') + request('dmin'),	00, request('month'),	request('day'),	request('year'));

			$sql = "UPDATE " . EVENT . " SET
						event_title		= '$event_title',
						event_desc		= '$event_desc',
						event_level		= '$event_level',
						event_date		= '$event_date',
						event_duration	= '$event_duration',
						event_comments	= '$event_comments',
						event_update	= '" . time() . "'
					WHERE event_id = $event_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
#			$monat = request('month', 'num');
#			$oCache -> sCachePath = './../cache/';
#			subnavi_calendar_' . $monat . '_member
#			subnavi_calendar_' . $monat . '_guest
			
			$message = $lang['update_event']
				. sprintf($lang['click_return_event'], '<a href="' . append_sid('admin_event.php') . '">', '</a>')
				. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_event.php?mode=_update&amp;' . POST_EVENT_URL . '=' . $event_id) . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_EVENT, 'update_event');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_delete':
			
#			$event = get_data(EVENT, $event_id, 1);
			
			if ( $event_id && $confirm )
			{	
				$sql = "DELETE FROM " . EVENT . " WHERE event_id = $event_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				$message = $lang['delete_event'] . sprintf($lang['click_return_event'], '<a href="' . append_sid('admin_event.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_EVENT, 'delete_event');
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $event_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_EVENT_URL . '" value="' . $event_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'	=> $lang['common_confirm'],
					'MESSAGE_TEXT'	=> $lang['confirm_delete_event'],
					
					'S_FIELDS'		=> $s_fields,
					'S_ACTION'		=> append_sid('admin_event.php'),
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_event']);
			}
			
			break;
		
		default:
			
			$template->set_filenames(array('body' => 'style/acp_event.tpl'));
			$template->assign_block_vars('display', array());
			
			$s_fields = '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['event']),
				'L_EXPLAIN'		=> $lang['event_explain'],
				
				'L_CREATE'		=> sprintf($lang['sprintf_creates'], $lang['event']),
				'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['event']),
				'L_DATE'		=> $lang['common_date'],
				
				'S_FIELDS'		=> $s_fields,
				'S_CREATE'		=> append_sid('admin_event.php?mode=_create'),
				'S_ACTION'		=> append_sid('admin_event.php'),
			));
			
			$event_data = get_data_array(EVENT, '', 'event_date', 'DESC');
			
			if ( $event_data )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($event_data)); $i++ )
				{
					$event_id = $event_data[$i]['event_id'];
					$event_date = create_date('d.m.Y', $event_data[$i]['event_date'], $userdata['user_timezone']);
					$event_time = create_date('H:i', $event_data[$i]['event_date'], $userdata['user_timezone']);
					$event_dura = create_date('H:i', $event_data[$i]['event_duration'], $userdata['user_timezone']);
					
					$template->assign_block_vars('display.row_event', array(
						'CLASS' 	=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
						
						'TITLE'		=> $event_data[$i]['event_title'],
						'DATE'		=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
						
						'U_UPDATE'	=> append_sid('admin_event.php?mode=_update&amp;' . POST_EVENT_URL . '=' . $event_id),
						'U_DELETE'	=> append_sid('admin_event.php?mode=_delete&amp;' . POST_EVENT_URL . '=' . $event_id),
					));
				}
			}
			else
			{
				$template->assign_block_vars('display.no_entry', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>