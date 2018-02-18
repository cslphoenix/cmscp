<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_EVENT',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'MAIN'	=> array(
				'TITLE'	=> 'ACP_EVENT',
				'AUTH'	=> 'A_GAME'),
		)
	);
}
else
{
	define('IN_CMS', true);

	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;

	$current = 'ACP_EVENT';

	include('./pagestart.php');

	add_lang('event');
	acl_auth('A_EVENT');

	$error	= '';
	$index	= '';
	$fields = '';

	$time	= time();
	$log	= SECTION_EVENT;
	$file	= basename(__FILE__) . $iadds;
	$base	= $settings['switch']['event'];
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$index	= request('index', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);

	( $cancel && !$index )	? redirect('admin/' . $file) : false;
	( $cancel && $index )	? redirect('admin/' . check_sid('index.php', true)) : false;

	$template->set_filenames(array('body' => "style/$current.tpl"));
	
	$_tpl	= ($mode === 'delete') ? 'confirm' : 'body';
    $_top	= sprintf($lang['STF_HEADER'], $lang['TITLE']);
	$mode	= (in_array($mode, array('create', 'delete', 'update'))) ? $mode : false;

	switch ( $mode )
	{
		case 'create':
		case 'update':

			$template->assign_block_vars('input', array());

			$vars = array(
				'event' => array(
					'title'				=> 'INPUT_DATA',
					'event_title'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',		'required' => 'input_title'),
					'event_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:50',	'required' => 'input_desc', 'params' => TINY_NORMAL, 'class' => 'tinymce'),
					'event_group'		=> array('validate' => ARY,	'explain' => false,	'type' => 'drop:groups',	'required' => 'select_group', 'params' => ''),
					'event_date'		=> array('validate' => ($base ? INT : TXT), 'explain' => false, 'type' => ($base ? 'drop:datetime' : 'text:25;25'), 'params' => ($base ? (($mode == 'create') ? $time : '-1') : 'format')),
					'event_duration'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:duration',	'params' => 'event_date'),
					'event_comments'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'time_create'		=> 'hidden',
					'time_update'		=> 'hidden',
				),
			);

			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);

			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'event_title'		=> request('event_title', TXT),
					'event_desc'		=> '',
					'event_group'		=> 'a:0:{}',
					'event_date'		=> $time,
					'event_duration'	=> 0,
					'event_comments'	=> $settings['comments']['event'],
					'time_create'		=> $time,
					'time_update'		=> $time,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(EVENT, $data, false, 1, 'row');
			}
			else
			{
				$data_sql = build_request(EVENT, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'create' )
					{
						$sql = sql(EVENT, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else
					{
						$sql = sql(EVENT, $mode, $data_sql, 'event_id', $data);
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['event_title']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'delete':
		
			$_del = array(
				'field' => 'event_id',
				'table'	=> EVENT,
				'name'	=> 'event_title'
			);
		
			$sqlout = data($_del['table'], $data, false, 1, 'row');

			if ( $data && $accept )
			{
				$_file = ( $index ) ? check_sid('index.php') : check_sid($file);
				$_name = ( $index ) ? $lang['ACP_OVERVIEW'] : $_top;
				
				$sql = sql($_del['table'], $mode, $sqlout, $_del['field'], $data);
				$msg = sprintf($lang['RETURN'], langs($mode), $_file, $_file);
				
			#	$month = date('m', $data['event_date']);
			#	$oCache->deleteCache('data_event');
			#	$oCache->deleteCache('data_calendar_' . $month);
			#	$oCache->deleteCache('dsp_sn_minical');
			#	$oCache->deleteCache('ly_event');

				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
					'index'	=> $index,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $sqlout[$_del['name']]),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}

			break;
		
		default:
		
			$template->assign_block_vars('display', array());
			
			$fields = build_fields(array('mode' => 'create'));
			$sqlout	= data(EVENT, false, 'event_date DESC', 1, 'set');
			$groups = data(GROUPS, false, 'group_order ASC', 1, 3);
			
			$new = $old = $cnt_new = $cnt_old = '';
			
			if ( !$sqlout )
			{
				$template->assign_block_vars('display.new_empty', array());
				$template->assign_block_vars('display.old_empty', array());
			}
			else
			{
				foreach ( $sqlout as $row )
				{
					if ( $row['event_date'] > $time )
					{
						$new[] = $row;
					}
					else if ( $row['event_date'] < $time )
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

					foreach ( $new as $row )
					{
						$in_group = '';
						
						$id		= $row['event_id'];
						$title	= $row['event_title'];
						$date	= create_date('d.m.Y', $row['event_date'], $userdata['user_timezone']);
						$time	= create_date('H:i', $row['event_date'], $userdata['user_timezone']);
						$dura	= create_date('H:i', $row['event_duration'], $userdata['user_timezone']);
						
						$event_group = unserialize($row['event_group']);
						
						foreach ( $groups as $group_id => $group_value )
						{
							if ( in_array($group_id, $event_group) )
							{
								$in_group[] = href('a_txt', check_sid("admin_groups.php"), array('mode' => 'member', 'id' => $group_id), $group_value['group_name'], $group_value['group_name']);
							}
						}

						$template->assign_block_vars('display.new', array(
							'TITLE'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, $title),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							
							'DATE'		=> ( $time == $dura ) ? sprintf($lang['SPRINTF_WHOLE'], $date) : sprintf($lang['SPRINTF_EVENT'], $date, $time, $dura),
							'GROUPS'	=> implode(', ', $in_group),
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
						$in_group = '';
					
						$id		= $old[$i]['event_id'];
						$title	= $old[$i]['event_title'];
						$date	= create_date('d.m.Y', $old[$i]['event_date'], $userdata['user_timezone']);
						$time	= create_date('H:i', $old[$i]['event_date'], $userdata['user_timezone']);
						$dura	= create_date('H:i', $old[$i]['event_duration'], $userdata['user_timezone']);
						
						$event_group = unserialize($old[$i]['event_group']);
						
						foreach ( $groups as $group_id => $group_value )
						{
							if ( in_array($group_id, $event_group) )
							{
								$in_group[] = href('a_txt', check_sid("admin_groups.php"), array('mode' => 'member', 'id' => $group_id), $group_value['group_name'], $group_value['group_name']);
							}
						}

						$template->assign_block_vars('display.old', array(
							'TITLE'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, $title),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							
						#	'DATE'		=> sprintf($lang['sprintf_event'], $date, $time, $dura),
							'DATE'		=> ( $time == $dura ) ? sprintf($lang['SPRINTF_WHOLE'], $date) : sprintf($lang['SPRINTF_EVENT'], $date, $time, $dura),
							'GROUPS'	=> implode(', ', $in_group),
						));
					}
				}
			}

			$current_page = (!$cnt_old) ? 1 : ceil($cnt_old/$settings['ppe_acp']);

			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['TITLE']),
				'L_EXPLAIN'	=> $lang['EXPLAIN'],

				'L_DATE'		=> $lang['EVENT_DATE'],
				'L_UPCOMING'	=> $lang['EVENT_UPCOMING'],
				'L_EXPIRED'		=> $lang['EVENT_EXPIRED'],

				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor($start/$settings['ppe_acp']) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file&", $cnt_old, $settings['ppe_acp'], $start),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>