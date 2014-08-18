<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_event',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_event'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_event';
	
	include('./pagestart.php');
	
	add_lang('event');
	acl_auth('a_event');
	
	$error	= '';
	$index	= '';
	$fields = '';

	$log	= SECTION_EVENT;
	$time	= time();
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	
	$acp_main	= request('acp_main', INT);
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);

	( $cancel && !$acp_main )	? redirect('admin/' . check_sid($file, true)) : false;
	( $cancel && $acp_main )	? redirect('admin/' . check_sid('index.php', true)) : false;

	$template->set_filenames(array(
		'body'		=> 'style/acp_event.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
#	debug($_POST, '_POST');

	$base	= $settings['switch']['event'];
	$mode = (in_array($mode, array('create', 'update', 'delete'))) ? $mode : false;

	switch ( $mode )
	{
		case 'create':
		case 'update':

			$template->assign_block_vars('input', array());
			
			$vars = array(
				'event' => array(
					'title' => 'data_input',
					'event_title'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',		'required' => 'input_title'),
					'event_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:50',	'required' => 'input_desc', 'params' => TINY_NORMAL, 'class' => 'tinymce'),
					'event_level'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:userlevel', 'required' => 'select_level', 'params' => 'user_level'),
					'event_date'		=> array('validate' => ($base ? INT : TXT), 'explain' => false, 'type' => ($base ? 'drop:datetime' : 'text:25;25'), 'params' => ($base ? (($mode == 'create') ? $time : '-1') : 'format')),
					'event_duration'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:duration',	'params' => 'event_date'),
					'event_comments'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'time_create'		=> 'hidden',
					'time_update'		=> 'hidden',
				),
			);
				
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'event_title'		=> request('event_title', TXT),
					'event_desc'		=> '',
					'event_level'		=> '-1',
					'event_date'		=> $time,
					'event_duration'	=> 0,
					'event_comments'	=> $settings['comments']['event'],
					'time_create'		=> $time,
					'time_update'		=> $time,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(EVENT, $data, false, 1, true);
			}
			else
			{
				$data_sql = build_request(EVENT, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'create' )
					{
						$sql = sql(EVENT, $mode, $data_sql);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(EVENT, $mode, $data_sql, 'event_id', $data);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
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
			
			build_output(EVENT, $vars, $data_sql);
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], lang($data_sql['event_title'])),
				'L_EXPLAIN'	=> $lang['com_required'],

				'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'delete':
			
			$data_sql = data(EVENT, $data, false, 1, true);
			
			if ( $data && $confirm )
			{
				$file = ( $acp_main ) ? check_sid('index.php') : check_sid($file);
				$name = ( $acp_main ) ? $lang['acp_overview'] : $acp_title;
				
				$sql = sql(EVENT, $mode, $data_sql, 'event_id', $data);
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
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				$fields .= "<input type=\"hidden\" name=\"acp_main\" value=\"$acp_main\" />";
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['com_confirm'],
					'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['event_title']),
					
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
				foreach ( $data as $row )
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
							'TITLE'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, $title),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
							
							'DATE'		=> sprintf($lang['sprintf_event'], $date, $time, $dura),
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

					for ( $i = $start; $i < min($settings['ppe_acp'] + $start, $cnt_old); $i++ )
					{
						$id		= $old[$i]['event_id'];
						$title	= $old[$i]['event_title'];
						$date	= create_date('d.m.Y', $old[$i]['event_date'], $userdata['user_timezone']);
						$time	= create_date('H:i', $old[$i]['event_date'], $userdata['user_timezone']);
						$dura	= create_date('H:i', $old[$i]['event_duration'], $userdata['user_timezone']);

						$template->assign_block_vars('display.old', array(
							'TITLE'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, $title),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
							
							'DATE'		=> sprintf($lang['sprintf_event'], $date, $time, $dura),
						));
					}
				}
			}

			$current_page = (!$cnt_old) ? 1 : ceil($cnt_old/$settings['ppe_acp']);

			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
				'L_CREATE'	=> sprintf($lang['stf_create'], $lang['title']),
				'L_EXPLAIN'	=> $lang['explain'],

				'L_DATE'		=> $lang['event_date'],
				'L_UPCOMING'	=> $lang['event_upcoming'],
				'L_EXPIRED'		=> $lang['event_expired'],

				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor($start/$settings['ppe_acp']) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file&", $cnt_old, $settings['ppe_acp'], $start),

				'S_LEVEL'	=> select_level($lvl, 'level', 'user_level'),
				'S_CREATE'	=> check_sid("$file?mode=create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
	}

	$template->pparse('body');

	acp_footer();
}

?>