<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] )
	{
		$module['hm_clan']['sm_event'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_event';
	
	include('./pagestart.php');
	
	add_lang('event');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_EVENT;
	$url	= POST_EVENT;
	$file	= basename(__FILE__);
	$time	= time();
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);	
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	$acp_main	= request('acp_main', INT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_event'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header && !$acp_main )	? redirect('admin/' . check_sid($file, true)) : false;
	( $header && $acp_main )	? redirect('admin/' . check_sid('index.php', true)) : false;
		
	$template->set_filenames(array(
		'body'		=> 'style/acp_event.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'delete')) ) ? $mode : '';
	
	debug($_POST);
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());

			$vars = array(
				'event' => array(
					'title' => 'data_input',
					'event_title'		=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_title'),
					'event_desc'		=> array('validate' => TXT,	'type' => 'textarea:50',	'explain' => true, 'required' => 'input_desc', 'params' => TINY_NORMAL),
					'event_level'		=> array('validate' => TXT,	'type' => 'drop:userlevel',	'explain' => true, 'required' => 'select_level', 'params' => 'user_level'),
					'event_date'		=> array('validate' => INT,	'type' => 'drop:datetime',	'explain' => true, 'params' => ( $mode == 'create' ) ? $time : '-1'),
					'event_duration'	=> array('validate' => INT,	'type' => 'drop:duration',	'explain' => true, 'params' => 'event_date'),
					'event_comments'	=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
					'event_create'		=> array('type' => 'hidden'),
					'event_update'		=> array('type' => 'hidden'),
					'count_comment'		=> array('type' => 'hidden'),
				),
			);
				
			if ( $mode == 'create' && !request('submit', TXT) )
			{
				$data = array(
					'event_title'		=> request('event_title', 2),
					'event_desc'		=> '',
					'event_level'		=> '-1',
					'event_date'		=> $time,
					'event_duration'	=> 0,
					'event_comments'	=> 1,
					'event_create'		=> $time,
					'event_update'		=> 0,
					'count_comment'		=> 0,
				);
			}
			else if ( $mode == 'update' && !request('submit', TXT) )
			{
				$data = data(EVENT, $data_id, false, 1, true);
			}
			else
			{
				$data = build_request(EVENT, $vars, 'event', $error);
				
				debug($data);
				
			#	$error[] = ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
			#	$error[] = ( time() >= $data['event_date'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				
				if ( !$error )
				{
					if ( $mode == 'create' )
					{
						$sql = sql(EVENT, $mode, $data);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(EVENT, $mode, $data, 'event_id', $data_id);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
					}
					
				#	$month = date('m', $data['event_date']);
					
				#	$oCache->deleteCache('data_event');
				#	$oCache->deleteCache('data_calendar_' . $month);
				#	$oCache->deleteCache('dsp_sn_minical');
				#	$oCache->deleteCache('ly_event');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output($data, $vars, 'input', false, EVENT);
			
		#	$event_create = ( $mode == 'update' ) ? $time : $data['event_create'];
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		#	$fields .= "<input type=\"hidden\" name=\"event[event_date]\" value=\"" . $data['event_date'] . "\" />";
		#	$fields .= "<input type=\"hidden\" name=\"event[event_create]\" value=\"" . $data['event_create'] . "\" />";
		#	$fields .= "<input type=\"hidden\" name=\"event[event_update]\" value=\"$event_create\" />";

			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['event_title']),
			
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'delete':
			
			$data = data(EVENT, $data_id, false, 1, true);
			
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
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}
			
			$template->pparse('confirm');
			
			break;
		
		default:
		
			$template->assign_block_vars('display', array());
			
			$fields	= '<input type="hidden" name="mode" value="create" />';
			$lvl	= isset($_POST['level']) ? $_POST['level'] : -1;
			$level	= ( $lvl >= 0 ) ? "event_level = $lvl" : '';
			$data	= data(EVENT, $level, 'event_date', 1, false);
			
			$new = $old = $cnt_new = $cnt_old = '';
			
			if ( !$data )
			{
				$template->assign_block_vars('display.new_empty', array());
				$template->assign_block_vars('display.old_empty', array());
			}
			else
			{
				foreach ( $data as $key => $row )
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
				
				if ( !$new )
				{
					$template->assign_block_vars('display.new_empty', array());
				}
				else
				{
					$cnt_new = count($new);
					
					for ( $i = 0; $i < $cnt_new; $i++ )
					{
						$id		= $new[$i]['event_id'];
						$title	= $new[$i]['event_title'];
						$date	= create_date('d.m.Y', $new[$i]['event_date'], $userdata['user_timezone']);
						$time	= create_date('H:i', $new[$i]['event_date'], $userdata['user_timezone']);
						$dura	= create_date('H:i', $new[$i]['event_duration'], $userdata['user_timezone']);
						
						$template->assign_block_vars('display.new', array(
							'TITLE'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $title, $title),
							
							'DATE'		=> sprintf($lang['sprintf_event'], $date, $time, $dura),
							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
						));
					}
				}
				
				if ( !$old )
				{
					$template->assign_block_vars('display.old_empty', array());
				}
				else
				{
					$cnt_old = count($old);
					
					for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt_old); $i++ )
					{
						$id		= $old[$i]['event_id'];
						$title	= $old[$i]['event_title'];
						$date	= create_date('d.m.Y', $old[$i]['event_date'], $userdata['user_timezone']);
						$time	= create_date('H:i', $old[$i]['event_date'], $userdata['user_timezone']);
						$dura	= create_date('H:i', $old[$i]['event_duration'], $userdata['user_timezone']);
						
						$template->assign_block_vars('display.old', array(
							'TITLE'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $title, $title),
							
							'DATE'		=> sprintf($lang['sprintf_event'], $date, $time, $dura),
							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
						));
					}
				}
			}
						
			$current_page = ( !$cnt_old ) ? 1 : ceil( $cnt_old / $settings['per_page_entry']['acp'] );
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['title']),
				'L_EXPLAIN'	=> $lang['explain'],
				
				'L_DATE'		=> $lang['event_date'],
				'L_UPCOMING'	=> $lang['event_upcoming'],
				'L_EXPIRED'		=> $lang['event_expired'],
				
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file?", $cnt_old, $settings['per_page_entry']['acp'], $start ),
				
				'S_LEVEL'	=> select_level($lvl, 'level', 'user_level'),
				'S_CREATE'	=> check_sid("$file?mode=create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>