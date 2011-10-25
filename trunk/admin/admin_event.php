<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] )
	{
		$module['hm_main']['sm_event'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_event';
	
	include('./pagestart.php');
	
	load_lang('event');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_EVENT;
	$url	= POST_EVENT;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);	
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$acp_main	= request('acp_main', 0);
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_event'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header && !$acp_main )	? redirect('admin/' . check_sid($file, true)) : false;
	( $header && $acp_main )	? redirect('admin/' . check_sid('index.php', true)) : false;
		
	$template->set_filenames(array(
		'body'		=> 'style/acp_event.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
		'tiny_mce'	=> 'style/tinymce_normal.tpl',
	));
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->assign_block_vars('_input', array());
			$template->assign_var_from_handle('TINYMCE', 'tiny_mce');
				
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
						
				$error .= ( !$data['event_title'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_title'] : '';
				$error .= ( !$data['event_desc'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_desc'] : '';
				$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
				$error .= ( time() >= $data['event_date'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$sql = sql(EVENT, $mode, $data);
						$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(EVENT, $mode, $data, 'event_id', $data_id);
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
					}
					
					$month = date('m', $data['event_date']);
					
					$oCache->deleteCache('data_event');
					$oCache->deleteCache('data_calendar_' . $month);
					$oCache->deleteCache('dsp_sn_minical');
					$oCache->deleteCache('ly_event');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
					
					log_add(LOG_ADMIN, $log, 'error', $error);
				}
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			$fields .= "<input type=\"hidden\" name=\"event_create\" value=\"" . $data['event_create'] . "\" />";

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['title'], $data['event_title']),
				'L_TITLE'		=> $lang['event_title'],
				'L_DESC'		=> $lang['event_desc'],
				'L_DATE'		=> $lang['event_date'],
				'L_LEVEL'		=> $lang['event_userlevel'],
				'L_DURATION'	=> $lang['event_duration'],
				'L_COMMENTS'	=> $lang['event_comments'],
				
				'TITLE'			=> $data['event_title'],
				'DESC'			=> $data['event_desc'],
				
				'S_LEVEL'		=> select_lang('select', 'event_level', 'switch_level', 'user_level', $data['event_level'], $data['event_create']),
				
				'S_DAY'			=> select_date('select', 'day', 'day', date('d', $data['event_date']), $data['event_create'], $data['event_create']),
				'S_MONTH'		=> select_date('select', 'month', 'month', date('m', $data['event_date']), $data['event_create'], $data['event_create']),
				'S_YEAR'		=> select_date('select', 'year', 'year', date('Y', $data['event_date']), $data['event_create'], $data['event_create']),
				'S_HOUR'		=> select_date('select', 'hour', 'hour', date('H', $data['event_date']), $data['event_create'], $data['event_create']),
				'S_MIN'			=> select_date('select', 'min', 'min', date('i', $data['event_date']), $data['event_create'], $data['event_create']),
				'S_DURATION'	=> select_date('select', 'duration', 'dmin', ( $data['event_duration'] - $data['event_date'] ) / 60),

				'S_COMMENT_NO'	=> (!$data['event_comments'] ) ? 'checked="checked"' : '',
				'S_COMMENT_YES'	=> ( $data['event_comments'] ) ? 'checked="checked"' : '',

				'S_ACTION'		=> check_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			break;
		
		case '_delete':
			
			$data = data(EVENT, $data_id, false, 1, 1);
			
			if ( $data_id && $confirm )
			{
				$file = ( $acp_main ) ? check_sid('index.php') : check_sid($file);
				$name = ( $acp_main ) ? $lang['header_acp'] : $acp_title;
				
				$sql = sql(EVENT, $mode, $data, 'event_id', $data_id);
				$msg = $lang['delete'] . sprintf($lang['return'], $file, $name);
				
				$month = date('m', $data['event_date']);
					
				$oCache->deleteCache('data_event');
				$oCache->deleteCache('data_calendar_' . $month);
				$oCache->deleteCache('dsp_sn_minical');
				$oCache->deleteCache('ly_event');
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"acp_main\" value=\"$acp_main\" />";
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['event_title']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['event']));
			}
			
			$template->pparse('confirm');
			
			break;
		
		default:
		
			$template->assign_block_vars('_display', array());
			
			$lvl	= isset($_POST['level']) ? $_POST['level'] : -1;
			$level	= ( $lvl >= 0 ) ? "event_level = $lvl" : '';
			$event	= data(EVENT, $level, 'event_date', 1, false);
			
			$new = $old = '';
					
			if ( !$event )
			{
				$template->assign_block_vars('_display._entry_empty_new', array());
				$template->assign_block_vars('_display._entry_empty_old', array());
			}
			else
			{
				foreach ( $event as $key => $row )
				{
					if ( $row['event_date'] > time() )
					{
						$new[] = $row;
					}
					else if ( $row['event_date'] < time() )
					{
						$old[] = $row;
					}
				}
				
				$cnt_new = count($new);
				$cnt_old = count($old);
				
				if ( !$new )
				{
					$template->assign_block_vars('_display._entry_empty_new', array());
				}
				else
				{
					for ( $i = 0; $i < $cnt_new; $i++ )
					{
						$event_id	= $new[$i]['event_id'];
						$event_date	= create_date('d.m.Y', $new[$i]['event_date'], $userdata['user_timezone']);
						$event_time	= create_date('H:i', $new[$i]['event_date'], $userdata['user_timezone']);
						$event_dura	= create_date('H:i', $new[$i]['event_duration'], $userdata['user_timezone']);
						
						$template->assign_block_vars('_display._new_row', array(
							'TITLE'		=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$event_id") . '" alt="" />' . $new[$i]['event_title'] . '</a>',
							'DATE'		=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
							
							'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$event_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
							'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$event_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
						));
					}
				}
				
				if ( !$old )
				{
					$template->assign_block_vars('_display._entry_empty_old', array());
				}
				else
				{
					for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, $cnt_old); $i++ )
					{
						$event_id	= $old[$i]['event_id'];
						$event_date	= create_date('d.m.Y', $old[$i]['event_date'], $userdata['user_timezone']);
						$event_time	= create_date('H:i', $old[$i]['event_date'], $userdata['user_timezone']);
						$event_dura	= create_date('H:i', $old[$i]['event_duration'], $userdata['user_timezone']);
						
						$template->assign_block_vars('_display._old_row', array(
							'TITLE'		=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$event_id") . '" alt="" />' . $old[$i]['event_title'] . '</a>',
							'DATE'		=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
							
							'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$event_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
							'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$event_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
						));
					}
				}
			}
						
			$current_page = ( !$cnt_old ) ? 1 : ceil( $cnt_old / $settings['site_entry_per_page'] );
			
			$fields .= '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['title']),
				'L_EXPLAIN'	=> $lang['explain'],
				
				'L_DATE'		=> $lang['event_date'],
				'L_UPCOMING'	=> $lang['event_upcoming'],
				'L_EXPIRED'		=> $lang['event_expired'],
				
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file?", $cnt_old, $settings['site_entry_per_page'], $start ),
				
				'S_LEVEL'	=> select_level('selectsmall', 'user_level', 'level', $lvl),
				'S_CREATE'	=> check_sid("$file?mode=_create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>