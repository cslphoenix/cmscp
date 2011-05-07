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
	
	load_lang('event');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_EVENT;
	$url	= POST_EVENT_URL;
	$file	= basename(__FILE__);
	
	$oCache -> sCachePath = $root_path . 'cache/';
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);	
	$data_level = request('level', 0);
	
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_games'] . '/';
	
	$acp_main	= request('acp_main', 0);
	$acp_title	= sprintf($lang['sprintf_head'], $lang['event']);
	
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
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			$fields .= "<input type=\"hidden\" name=\"event_create\" value=\"" . $data['event_create'] . "\" />";

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
			
			if ( request('submit', 1) )
			{
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
					
					$oCache->deleteCache('cal_member_' . $month);
					$oCache->deleteCache('cal_guests_' . $month);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					log_add(LOG_ADMIN, $log, $mode, $error);

					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
				}
			}
			
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
				
				$oCache->deleteCache('cal_member_' . $month);
				$oCache->deleteCache('cal_guests_' . $month);
				
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
			
			$level = ( $data_level != 0 ) ? ( $data_level > 0 ) ? "event_level = $data_level" : '' : "event_level = 0";
			$event = data(EVENT, $level, 'event_date', 1, false);
			
			$event_new = $event_old = '';
					
			if ( $event )
			{
				foreach ( $event as $key => $row )
				{
					if ( $row['event_date'] > time() )
					{
						$event_new[] = $row;
					}
					else if ( $row['event_date'] < time() )
					{
						$event_old[] = $row;
					}
				}
				
				if ( !$event_new )
				{
					$template->assign_block_vars('_display._entry_empty_new', array());
				}
				else
				{
					for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($event_new)); $i++ )
					{
						$event_id	= $event_new[$i]['event_id'];
						$event_date	= create_date('d.m.Y', $event_new[$i]['event_date'], $userdata['user_timezone']);
						$event_time	= create_date('H:i', $event_new[$i]['event_date'], $userdata['user_timezone']);
						$event_dura	= create_date('H:i', $event_new[$i]['event_duration'], $userdata['user_timezone']);
						
						$template->assign_block_vars('_display._event_new_row', array(
							'TITLE'		=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$event_id") . '" alt="" />' . $event_new[$i]['event_title'] . '</a>',
							'DATE'		=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
							
							'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$event_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
							'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$event_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
						));
					}
				}
				
				if ( !$event_old )
				{
					$template->assign_block_vars('_display._entry_empty_old', array());
				}
				else
				{
					for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($event_old)); $i++ )
					{
						$event_id	= $event_old[$i]['event_id'];
						$event_date	= create_date('d.m.Y', $event_old[$i]['event_date'], $userdata['user_timezone']);
						$event_time	= create_date('H:i', $event_old[$i]['event_date'], $userdata['user_timezone']);
						$event_dura	= create_date('H:i', $event_old[$i]['event_duration'], $userdata['user_timezone']);
						
						$template->assign_block_vars('_display._event_old_row', array(
							'TITLE'		=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$event_id") . '" alt="" />' . $event_old[$i]['event_title'] . '</a>',
							'DATE'		=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
							
							'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$event_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
							'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$event_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
						));
					}
				}
			}
			else
			{
				$template->assign_block_vars('_display._entry_empty_new', array());
				$template->assign_block_vars('_display._entry_empty_old', array());
			}
			
			$current_page = !count($event) ? 1 : ceil( count($event) / $settings['site_entry_per_page'] );
			
			$fields .= '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['event']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['event']),
				
				'L_UPCOMING'	=> $lang['upcoming'],
				'L_EXPIRED'		=> $lang['expired'],
				
				'L_EXPLAIN'	=> $lang['explain'],
				'L_DATE'	=> $lang['common_date'],
				
				'PAGE_NUMBER'	=> count($event) ? sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ) : '',
				'PAGE_PAGING'	=> count($event) ? generate_pagination("$file?", count($event), $settings['site_entry_per_page'], $start ) : '',
				
				'S_LEVEL'	=> select_level('selectsmall', 'user_level', 'level', $data_level),
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