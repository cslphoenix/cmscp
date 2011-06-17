<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['hm_dev']['sm_logs']		= $root_file;
		$module['hm_dev']['sm_logs_error']	= $root_file . "?mode=_error";
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_logs';
	
	include('./pagestart.php');

	load_lang('games');
	load_lang('logs');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	
	$log_id		= request(POST_LOGS, 0);
	$mode		= request('mode', 1);
	$confirm	= request('confirm', 1);
	
	$fields	= '';
	$file	= basename(__FILE__);
	$log	= SECTION_LOG;
	
	if ( $userdata['user_level'] != ADMIN )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_logs.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	switch ( $mode )
	{
		case '_error':
			
			$template->assign_block_vars('_error', array());
			
			$logs = data(ERROR, false, 'error_id ASC', 1, false);
		
			if ( !$logs )
			{
				$template->assign_block_vars('_error._entry_empty', array());
			}
			else
			{
				for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($logs)); $i++)
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					$error_id			= $logs[$i]['error_id'];
					$error_user			= $logs[$i]['error_userid'];
					$error_msg_title	= $logs[$i]['error_msg_title'];
					$error_msg_text		= $logs[$i]['error_msg_text'];
					$error_sql_code		= $logs[$i]['error_sql_code'];
					$error_sql_text		= $logs[$i]['error_sql_text'];
					$error_sql_store	= $logs[$i]['error_sql_store'];
					$error_file			= str_replace(array(cms_realpath($root_path), '\\'), array('', '/'), $logs[$i]['error_file']);
					$error_file_line	= $logs[$i]['error_file_line'];
					$error_time			= create_date($config['default_dateformat'], $logs[$i]['error_time'], $config['page_timezone']);
					
					$template->assign_block_vars('_error._error_row', array(
						'CLASS'	=> $class,
						
						'ID'		=> $error_id,
						'USER'		=> $error_user,
						'TIME'		=> $error_time,
						'FILE'		=> $error_file,
						'FILE_LINE'	=> $error_file_line,
						'MSG_TITLE'	=> $error_msg_title,
						'MSG_TEXT'	=> $error_msg_text,
						'SQL_CODE'	=> $error_sql_code,
						'SQL_TEXT'	=> $error_sql_text,
						'SQL_STORE'	=> $error_sql_store,
					));
				}
			}
		
			$current_page = ( !count($logs) ) ? 1 : ceil( count($logs) / $settings['site_entry_per_page'] );
		
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_ERROR'	=> sprintf($lang['sprintf_head'], $lang['title_error']),
				'L_EXPLAIN'	=> $lang['explain_error'],
				
				'L_DELETE'			=> $lang['Delete'],
				'L_MARK_ALL'		=> $lang['mark_all'],
				'L_MARK_DEALL'		=> $lang['mark_deall'],
				'L_GOTO_PAGE'		=> $lang['Goto_page'],
				
				'PAGINATION'		=> generate_pagination('admin_logs.php?', count($logs), $settings['site_entry_per_page'], $start),
				'PAGE_NUMBER'		=> sprintf($lang['common_page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
				
				
				'S_ACTION'		=> check_sid($file),
			));
			
			$template->pparse('body');
		
			break;
			
		case 'deleteerror':

			$confirm	= isset($HTTP_POST_VARS['confirm']);
			$log_id		= ( isset($HTTP_POST_VARS['log_id']) )	? implode(', ', $HTTP_POST_VARS['log_id']) : '';
			$log_ids	= ( isset($HTTP_POST_VARS['log_ids']) )	? $HTTP_POST_VARS['log_ids'] : '';
			
			if ( $confirm && $log_ids )
			{
				$sql = 'DELETE FROM ' . ERROR . " WHERE error_id IN ($log_ids)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				log_add(LOG_ADMIN, $log, 'acp_log_delete_error');
				
				$message = $lang['delete_log_error'] . sprintf($lang['click_return_log_error'], '<a href="' . check_sid('admin_logs.php?mode=error'));
				message(GENERAL_MESSAGE, $message);
	
			}
			else if ( !$confirm && $log_id )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields = '<input type="hidden" name="mode" value="deleteerror" />';
				$fields .= '<input type="hidden" name="log_ids" value="' . $log_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_log_error'],
	
					'L_YES'				=> $lang['common_yes'],
					'L_NO'				=> $lang['common_no'],
	
					'S_ACTION'	=> check_sid('admin_logs.php?mode=error'),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_log']);
			}
		
			$template->pparse('body');
			
			break;
		
		case 'delete_all':
		
			$confirm	= isset($HTTP_POST_VARS['confirm']);

			if ( $confirm )
			{
				$sql = 'TRUNCATE TABLE ' . LOGS;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				log_add(LOG_ADMIN, $log, 'acp_log_delete_all');
				
				$message = $lang['delete_log_all'] . sprintf($lang['click_return_log'], '<a href="' . check_sid($file));
				message(GENERAL_MESSAGE, $message);
	
			}
			else if ( !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields = '<input type="hidden" name="mode" value="delete_all" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_all_log'],
	
					'L_YES'				=> $lang['common_yes'],
					'L_NO'				=> $lang['common_no'],
	
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_log']);
			}
		
			$template->pparse('body');
			
			break;
			
		case 'delete':

			$confirm	= isset($HTTP_POST_VARS['confirm']);
			$log_id		= ( isset($HTTP_POST_VARS['log_id']) )	? implode(', ', $HTTP_POST_VARS['log_id']) : '';
			$log_ids	= ( isset($HTTP_POST_VARS['log_ids']) )	? $HTTP_POST_VARS['log_ids'] : '';
			
			if ( $confirm && $log_ids )
			{
				$sql = 'DELETE FROM ' . LOGS . " WHERE log_id IN ($log_ids)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				log_add(LOG_ADMIN, $log, 'acp_log_delete');
				
				$message = $lang['delete_log'] . sprintf($lang['click_return_log'], '<a href="' . check_sid($file));
				message(GENERAL_MESSAGE, $message);
	
			}
			else if ( !$confirm && $log_id )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields = '<input type="hidden" name="mode" value="delete" />';
				$fields .= '<input type="hidden" name="log_ids" value="' . $log_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_log'],
	
					'L_YES'				=> $lang['common_yes'],
					'L_NO'				=> $lang['common_no'],
	
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_log']);
			}
		
			$template->pparse('body');
			
			break;
			
		default:

			$template->set_filenames(array('body' => 'style/acp_logs.tpl'));
			$template->assign_block_vars('_display', array());
					
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_ERROR'	=> sprintf($lang['sprintf_head'], $lang['title_error']),
				'L_EXPLAIN'	=> $lang['explain'],
		
		#		'L_LOGS_TITLE'		=> $lang['log_head'],
		#		'L_LOGS_EXPLAIN'		=> $lang['log_explain'],
		#		'L_LOGS_ERROR'		=> $lang['log_error'],
				
		#		'L_LOGS_USERNAME'	=> $lang['log_user_name'],
		#		'L_LOGS_IP'			=> $lang['log_ip'],
		#		'L_LOGS_TIME'		=> $lang['log_time'],
		#		'L_LOG_SEKTION'		=> $lang['log_section'],
		#		'L_LOGS_MESSAGE'		=> $lang['log_message'],
		#		'L_LOGS_CHANGE'		=> $lang['log_change'],
				
		#		'L_MARK_ALL'		=> $lang['mark_all'],
		#		'L_MARK_DEALL'		=> $lang['mark_deall'],
		#		'L_DELETE'			=> $lang['common_delete'],
		
				'S_ERROR'		=> check_sid('admin_logs.php?mode=_error'),
				'S_ACTION'		=> check_sid($file),
			));
			
			$sql = 'SELECT l.*, u.user_name
						FROM ' . LOGS . ' l, ' . USERS . ' u
						WHERE l.user_id = u.user_id
					ORDER BY log_id DESC';
			if (!($result = $db->sql_query($sql)))
			{
				message(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
			}
			$log_entry = $db->sql_fetchrowset($result); 
			$db->sql_freeresult($result);
			
			if ( !$log_entry )
			{
				$template->assign_block_vars('_display._entry_empty', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			else
			{
				for ( $i = $start; $i < min(($settings['site_entry_per_page']*2) + $start, count($log_entry)); $i++ )
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					// Log Sektion
				#	switch ( $log_entry[$i]['log_section'] )
				#	{
				#		case 0:
				#			$sektion = 'news';
				#			break;
				#		case 1:
				#			$sektion = 'team';
				#			break;
				#		case 2:
				#			$sektion = 'rank';
				#			break;
				#		case 3:
				#			$sektion = 'user';
				#			break;
				#		default:
				#			$sektion = $log_entry[$i]['log_section'];
				#			break;
				#	}
				
					$msg = $log_entry[$i]['log_message'];
					
					if ( strstr($msg, 'create') )		{ $msg = $lang['common_create']; }
					else if ( strstr($msg, 'update') )	{ $msg = $lang['common_update']; }
					else if ( strstr($msg, 'error') )	{ $msg = $lang['common_error'];		$class = 'row_error'; }
					else if ( strstr($msg, 'delete') )	{ $msg = $lang['common_delete']; }
					else								{ $msg = $lang['common_default']; }
					
					$log_data = unserialize($log_entry[$i]['log_data']);
					
					if ( is_array($log_data) )
					{
						for ( $j = 0; $j < count($log_data); $j++ )
						{
							$field	= $log_data[$j]['field'];
							$data	= isset($log_data[$j]['data']) ? $log_data[$j]['data'] : '';
							$post	= isset($log_data[$j]['post']) ? $log_data[$j]['post'] : '';
							
							$field_lang	= isset($lang[$log_data[$j]['field']]) ? $lang[$log_data[$j]['field']] : $log_data[$j]['field'];
						
							if ( $data && $post )
							{
								$msg_data = sprintf($lang['sprintf_log_change'], $field_lang, $data, $post);
							}
							else if ( !$data && $post )
							{
								$msg_data = sprintf($lang['sprintf_log_create'], $field_lang, $post);
							}
							else if ( $data && !$post )
							{
								$msg_data = sprintf($lang['sprintf_log_delete'], $field_lang, $data);
							}
						}
						
					#	$msg_data .= "<pre>" . print_r($log_data, true) . "</pre>";
					}
					else if ( !$log_data )
					{
						$msg_data = $lang['common_entry_empty'];
					}
					else
					{
						$msg_data = $log_data;
					}
					
			
					$template->assign_block_vars('_display._logs_row', array(
						'CLASS'		=> $class,
						
						'LOG_ID'	=> $log_entry[$i]['log_id'],
						'USERNAME'      => $log_entry[$i]['user_name'],
						'IP'            => decode_ip($log_entry[$i]['user_ip']),
						'DATE'          => create_date($userdata['user_dateformat'], $log_entry[$i]['log_time'], $userdata['user_timezone']),
						'SEKTION'       => isset($lang['section'][$log_entry[$i]['log_section']]) ? $lang['section'][$log_entry[$i]['log_section']] : $log_entry[$i]['log_section'],
						'MESSAGE'       => $msg,
						'DATA'          => $msg_data,
					));
				}
			}
			
			$current_page = ( !count($log_entry) ) ? 1 : ceil( count($log_entry) / $settings['site_entry_per_page'] );
			
			$template->assign_vars(array(
				'L_GOTO_PAGE'   => $lang['Goto_page'],
				'PAGINATION'    => generate_pagination('admin_logs.php?', count($log_entry), $settings['site_entry_per_page'], $start),
				'PAGE_NUMBER'   => sprintf($lang['common_page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
			));
			
			$template->pparse('body');
			
		break;
	}
	
	include('./page_footer_admin.php');
}

?>