<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] )
	{
		$module['_headmenu_01_main']['_submenu_event'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_event';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('event');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_EVENT;
	$url	= POST_EVENT_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);	
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_games'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['event']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_event'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_event.tpl'));
			$template->assign_block_vars('_input', array());
			
			if ( $mode == '_create' && !request('submit', 1) )
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
			else if ( $mode == '_update' && !request('submit', 1) )
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
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			$fields .= "<input type=\"hidden\" name=\"game_order\" value=\"" . $data['event_create'] . "\" />";

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
				
				'S_LEVEL'		=> select_lang('select', 'event_level', 'switch_level', 'userlevel', $data['event_level']),
				'S_DAY'			=> select_date('select', 'day', 'day', date('d', $data['event_date']), $data['event_create']),
				'S_MONTH'		=> select_date('select', 'month', 'month', date('m', $data['event_date']), $data['event_create']),
				'S_YEAR'		=> select_date('select', 'year', 'year', date('Y', $data['event_date']), $data['event_create']),
				'S_HOUR'		=> select_date('select', 'hour', 'hour', date('H', $data['event_date']), $data['event_create']),
				'S_MIN'			=> select_date('select', 'min', 'min', date('i', $data['event_date']), $data['event_create']),
				'S_DURATION'	=> select_date('select', 'duration', 'dmin', ( $data['event_duration'] - $data['event_date'] ) / 60),

				'S_COMMENT_NO'	=> (!$data['event_comments'] ) ? 'checked="checked"' : '',
				'S_COMMENT_YES'	=> ( $data['event_comments'] ) ? 'checked="checked"' : '',

				'S_ACTION'		=> append_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			if ( request('submit', 1) )
			{
			#	$event_title	= request('event_title', 2);
			#	$event_desc		= request('event_desc', 2);
			#	$event_level	= request('event_level', 0);
			#	$event_comments	= request('event_comments', 0);
			#	$event_create	= request('event_create', 0);

			#	$event_date	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
			#	$event_dura	= mktime(request('hour', 0), request('min', 0) + request('dmin', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
			#	$error .= ( !$event_title ) ? ( $error ? '<br />' : '' ) . sprintf($lang['sprintf_msg_select'], sprintf($lang['sprintf_title'], $lang['event'])) : '';
			#	$error .= ( !$event_desc ) ? ( $error ? '<br />' : '' ) . sprintf($lang['sprintf_msg_select'], sprintf($lang['sprintf_desc'], $lang['event'])) : '';
			#	$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
			#	$error .= ( time() >= $event_date ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				
				$data = array(
							'event_title'		=> request('event_title', 2),
							'event_desc'		=> request('event_desc', 2),
							'event_level'		=> request('event_level', 0),
							'event_date'		=> mktime(request('hour', 0), request('min', 0),						00, request('month', 0), request('day', 0), request('year', 0)),
							'event_duration'	=> mktime(request('hour', 0), request('min', 0) + request('dmin', 0),	00, request('month', 0), request('day', 0), request('year', 0)),
							'event_comments'	=> request('event_comments', 0),
							'event_create'		=> request('event_create', 0),
						);
				
				$error .= ( !$data['event_title'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_title'] : '';
				$error .= ( !$data['event_desc'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_desc'] : '';
				$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
				$error .= ( time() >= $data['event_date'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						sql(EVENT, 'insert', $data);
						
						$message = $lang['create'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					}
					else
					{
						sql(EVENT, 'update', $data, 'event_id', $data_id);
						
						$message = $lang['update']
							. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
							. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
					}
					
				#	$monat = request('month', 0);
				#	#$oCache -> sCachePath = './../cache/';
				#	subnavi_calendar_' . $monat . '_member
				#	subnavi_calendar_' . $monat . '_guest
					
					log_add(LOG_ADMIN, $log, $mode, $data['event_title']);
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
			
			$data = data(EVENT, $data_id, false, 1, 1);
			
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
		
				$message = $lang['delete'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
				
				log_add(LOG_ADMIN, $log, $mode, $data['event_title']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['event_title']),
					
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['event'])); }
			
			break;
		
		default:
			
			$template->set_filenames(array('body' => 'style/acp_event.tpl'));
			$template->assign_block_vars('_display', array());
			
			$fields .= '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['event']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['event']),
				'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['event']),
				'L_DATE'	=> $lang['common_date'],
				'L_EXPLAIN'	=> $lang['explain'],
				
				'S_CREATE'	=> append_sid("$file?mode=_create"),
				'S_ACTION'	=> append_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			$tmp = data(EVENT, false, 'event_date', 0, 0);
			
			if ( $tmp )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp)); $i++ )
				{
					$event_id	= $tmp[$i]['event_id'];
					$event_date	= create_date('d.m.Y', $tmp[$i]['event_date'], $userdata['user_timezone']);
					$event_time	= create_date('H:i', $tmp[$i]['event_date'], $userdata['user_timezone']);
					$event_dura	= create_date('H:i', $tmp[$i]['event_duration'], $userdata['user_timezone']);
					
					$template->assign_block_vars('_display._event_row', array(
						'TITLE'		=> $tmp[$i]['event_title'],
						'DATE'		=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
						
						'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$event_id"),
						'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$event_id"),
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