<?php

/*
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
 *	@autor:	Sebastian Frickel © 2009, 2010, 2011
 *	@code:	Sebastian Frickel © 2009, 2010, 2011
 *
 *	Ereignisse
 *
 */

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] )
	{
		$module['_headmenu_main']['_submenu_event'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$s_header	= ( isset($_POST['cancel']) ) ? true : false;
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
	$root_file	= basename(__FILE__);
	
	$error		= '';
	$s_fields	= '';
	
	$p_url		= POST_EVENT_URL;
	$l_sec	= LOG_SEK_EVENT;
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_event'] )
	{
		log_add(LOG_ADMIN, $l_sec, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $s_header ) ? redirect('admin/' . append_sid($root_file, true)) : false;
	
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
					'event_level'		=> '-1',
					'event_date'		=> time(),
					'event_duration'	=> '0',
					'event_comments'	=> '1',
					'event_create'		=> time(),
				);
			}
			else if ( $mode == '_update' && !request('submit', 2) )
			{
				$data = data(EVENT, $data_id, false, 1, 1);
			}
			else
			{
				$data = array(
					'event_title'		=> request('event_title', 2),
					'event_desc'		=> request('event_desc', 2),
					'event_level'		=> request('event_level', 0),
					'event_date'		=> mktime(request('hour', 0), request('min', 0),						00, request('month', 0), request('day', 0), request('year', 0)),
					'event_duration'	=> mktime(request('hour', 0), request('min', 0) + request('dmin', 0),	00, request('month', 0), request('day', 0), request('year', 0)),
					'event_comments'	=> request('event_comments', 0),
					'event_create'		=> request('event_create', 0),
				);
			}
			
			$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
			$s_fields .= '<input type="hidden" name="event_create" value="' . $data['event_create'] . '" />';
			$s_fields .= '<input type="hidden" name="' . $p_url . '" value="' . $data_id . '" />';
			
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['event']),
				'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['event'], $data['event_title']),	
				'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['event']),
				'L_DESC'		=> sprintf($lang['sprintf_desc'], $lang['event']),
				'L_DATE'		=> $lang['common_date'],
				'L_LEVEL'		=> $lang['common_userlevel'],
				'L_DURATION'	=> $lang['common_duration'],
				'L_COMMENTS'	=> $lang['common_comments'],
				
				'TITLE'			=> $data['event_title'],
				'DESC'			=> $data['event_desc'],
				
				'S_LEVEL'		=> select_lang('selectsmall', 'event_level', 'switch_level', 'userlevel', $data['event_level']),
				'S_DAY'			=> select_date('selectsmall', 'day', 'day', date('d', $data['event_date']), $data['event_create']),
				'S_MONTH'		=> select_date('selectsmall', 'month', 'month', date('m', $data['event_date']), $data['event_create']),
				'S_YEAR'		=> select_date('selectsmall', 'year', 'year', date('Y', $data['event_date']), $data['event_create']),
				'S_HOUR'		=> select_date('selectsmall', 'hour', 'hour', date('H', $data['event_date']), $data['event_create']),
				'S_MIN'			=> select_date('selectsmall', 'min', 'min', date('i', $data['event_date']), $data['event_create']),
				'S_DURATION'	=> select_date('selectsmall', 'duration', 'dmin', ( $data['event_duration'] - $data['event_date'] ) / 60),

				'S_COMMENT_NO'	=> (!$data['event_comments'] ) ? ' checked="checked"' : '',
				'S_COMMENT_YES'	=> ( $data['event_comments'] ) ? ' checked="checked"' : '',

				'S_ACTION'		=> append_sid($root_file),
				'S_FIELDS'		=> $s_fields,
			));
			
			if ( request('submit', 2) )
			{
				$event_title	= request('event_title', 2);
				$event_desc		= request('event_desc', 2);
				$event_level	= request('event_level', 0);
				$event_comments	= request('event_comments', 0);
				$event_create	= request('event_create', 0);

				$event_date	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$event_dura	= mktime(request('hour', 0), request('min', 0) + request('dmin', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				$error .= ( !$event_title ) ? ( $error ? '<br />' : '' ) . sprintf($lang['sprintf_msg_select'], sprintf($lang['sprintf_title'], $lang['event'])) : '';
				$error .= ( !$event_desc ) ? ( $error ? '<br />' : '' ) . sprintf($lang['sprintf_msg_select'], sprintf($lang['sprintf_desc'], $lang['event'])) : '';
				$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
				$error .= ( time() >= $event_date ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$sql = "INSERT INTO " . EVENT . " (event_title, event_desc, event_level, event_date, event_duration, event_comments, event_create)
									VALUES ('$event_title', '$event_desc', '$event_level', '$event_date', '$event_dura', '$event_comments', '$event_create')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create_event'] . sprintf($lang['click_return_event'], '<a href="' . append_sid($root_file) . '">', '</a>');
					}
					else
					{
						$sql = "UPDATE " . EVENT . " SET
									event_title		= '$event_title',
									event_desc		= '$event_desc',
									event_level		= '$event_level',
									event_date		= '$event_date',
									event_duration	= '$event_dura',
									event_comments	= '$event_comments',
									event_update	= '" . time() . "'
								WHERE event_id = $data_id";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['update_event']
							. sprintf($lang['click_return_event'], '<a href="' . append_sid($root_file) . '">', '</a>')
							. sprintf($lang['click_return_update'], '<a href="' . append_sid($root_file . '?mode=_update&amp;' . $p_url . '=' . $data_id) . '">', '</a>');
					}
					
				#	$monat = request('month', 0);
				#	$oCache -> sCachePath = './../cache/';
				#	subnavi_calendar_' . $monat . '_member
				#	subnavi_calendar_' . $monat . '_guest
					
					log_add(LOG_ADMIN, $l_sec, $mode, $event_title);
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					log_add(LOG_ADMIN, $l_sec, $mode, $error);
					
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
		
		case '_delete':
			
			if ( $data_id && $confirm )
			{
				$data = data(EVENT, $data_id, false, 1, 1);
				
				$sql = "DELETE FROM " . EVENT . " WHERE event_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
			#	$monat = request('month', 0);
			#	$oCache -> sCachePath = './../cache/';
			#	subnavi_calendar_' . $monat . '_member
			#	subnavi_calendar_' . $monat . '_guest
		
				$message = $lang['delete_event'] . sprintf($lang['click_return_event'], '<a href="' . append_sid($root_file) . '">', '</a>');
				
				log_add(LOG_ADMIN, $l_sec, $mode, $data['event_title']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
				
				$s_fields .= '<input type="hidden" name="mode" value="_delete" />';
				$s_fields .= '<input type="hidden" name="' . $p_url . '" value="' . $data_id . '" />';
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_event'], $data['event_title']),
					
					'S_ACTION'	=> append_sid($root_file),
					'S_FIELDS'	=> $s_fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['event']));
			}
			
			break;
		
		default:
			
			$template->set_filenames(array('body' => 'style/acp_event.tpl'));
			$template->assign_block_vars('_display', array());
			
			$s_fields .= '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['event']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['event']),
				'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['event']),
				'L_DATE'	=> $lang['common_date'],
				'L_EXPLAIN'	=> $lang['event_explain'],
				
				'S_CREATE'	=> append_sid($root_file . '?mode=_create'),
				'S_ACTION'	=> append_sid($root_file),
				'S_FIELDS'	=> $s_fields,
			));
			
			$event = data(EVENT, '', 'event_date', 0, 0);
			
			if ( $event )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($event)); $i++ )
				{
					$event_id	= $event[$i]['event_id'];
					$event_date	= create_date('d.m.Y', $event[$i]['event_date'], $userdata['user_timezone']);
					$event_time	= create_date('H:i', $event[$i]['event_date'], $userdata['user_timezone']);
					$event_dura	= create_date('H:i', $event[$i]['event_duration'], $userdata['user_timezone']);
					
					$template->assign_block_vars('_display._event_row', array(
						'TITLE'		=> $event[$i]['event_title'],
						'DATE'		=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
						
						'U_UPDATE'	=> append_sid($root_file . '?mode=_update&amp;' . $p_url . '=' . $event_id),
						'U_DELETE'	=> append_sid($root_file . '?mode=_delete&amp;' . $p_url . '=' . $event_id),
					));
				}
			}
			else
			{
				$template->assign_block_vars('_display._no_entry', array());
			}
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>