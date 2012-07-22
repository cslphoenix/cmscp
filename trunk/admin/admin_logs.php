<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['hm_maintenance']['sm_logs']		= $root_file . "?mode=_log";
		$module['hm_maintenance']['sm_logs_error']	= $root_file . "?mode=_error";
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_logs';
	
	include('./pagestart.php');
	
	add_lang(array('games', 'match', 'server', 'logs'));
	
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_LOG;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_logs.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( request('delete', 1) || request('delete_all', 1) )
	{
		if ( request('delete', 1) )
		{
			$mode = 'delete';
		}
		else if ( request('delete_all', 1) )
		{
			$mode = '_delete_all';
		}
	}
	
	$mode = ( in_array($mode, array('_error', 'delete', '_delete_all')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'error':
				
				$template->assign_block_vars('error', array());
				
				$errors	= data(ERROR, false, 'error_id ASC', 1, false);
				$error	= count($errors);
				
				if ( !$errors )
				{
					$template->assign_block_vars('error._entry_empty', array());
				}
				else
				{
					for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $error); $i++ )
					{
						$class = ( $i % 2 ) ? 'row_class1' : 'row_class2';
						
						$error_id			= $errors[$i]['error_id'];
						$error_user			= $errors[$i]['error_userid'];
						$error_msg_title	= $errors[$i]['error_msg_title'];
						$error_msg_text		= $errors[$i]['error_msg_text'];
						$error_sql_code		= $errors[$i]['error_sql_code'];
						$error_sql_text		= $errors[$i]['error_sql_text'];
						$error_sql_store	= $errors[$i]['error_sql_store'];
						$error_file			= str_replace(array(cms_realpath($root_path), '\\'), array('', '/'), $errors[$i]['error_file']);
						$error_file_line	= $errors[$i]['error_file_line'];
						$error_time			= create_date($config['default_dateformat'], $errors[$i]['error_time'], $config['default_timezone']);
						
						$template->assign_block_vars('error._error_row', array(
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
			
				$current_page = ( !$error ) ? 1 : ceil( $error / $settings['per_page_entry']['acp'] );
			
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_ERROR'	=> sprintf($lang['sprintf_head'], $lang['title_error']),
					'L_EXPLAIN'	=> $lang['explain_error'],
					
					'L_DELETE'		=> $lang['Delete'],
					'L_MARK_ALL'	=> $lang['mark_all'],
					'L_MARK_DEALL'	=> $lang['mark_deall'],
					'L_GOTO_PAGE'	=> $lang['Goto_page'],
					
					'PAGE_PAGING'	=> generate_pagination('admin_logs.php?mode=_error', $error, $settings['per_page_entry']['acp'], $start),
					'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ),
					
					
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
				
				$log_id	= request('log_id', 4) ? request('log_id', 4) : '';
				$log_id = is_array($log_id) ? implode(', ', $log_id) : $log_id;
				
				$log_ids = request('log_ids', 2) ? request('log_ids', 2) : '';
				$log_ids = explode(', ', $log_ids);
				
				if ( $log_ids && $confirm )
				{
					$sql = sql(LOGS, $mode, true, 'log_id', $log_ids);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( !$confirm && $log_id )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"log_ids\" value=\"$log_id\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm']),

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
			}

		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
					
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_ERROR'	=> sprintf($lang['sprintf_head'], $lang['title_error']),
		'L_EXPLAIN'	=> $lang['explain'],

#		'L_LOGS_TITLE'		=> $lang['log_head'],
#		'L_LOGS_EXPLAIN'	=> $lang['log_explain'],
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
	
	$sql = 'SELECT l.*, u.user_id, u.user_name, u.user_color FROM ' . LOGS . ' l, ' . USERS . ' u WHERE l.user_id = u.user_id AND log_type = ' . LOG_ADMIN . ' ORDER BY log_id DESC';
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
	}
	$tmp = $db->sql_fetchrowset($result); 
	$db->sql_freeresult($result);
	
	if ( !$tmp )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$cnt = count($tmp);
		
		for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt); $i++ )
		{
			$class = '';
			
			$message = $tmp[$i]['log_message'];
			$section = $tmp[$i]['log_section'];
			$section = ( isset($lang['section'][$section]) ) ? $lang['section'][$section] : $section;
			
			if ( $message )
			{
				$class = ( $message == 'error' || strstr($message, 'auth_fail') ) ? 'row_error' : '';
				$message = isset($lang['common' . $message]) ? $lang['common' . $message] : $message;
			}
			
			/*
			if		( strstr($message, 'create') )		{ $message = $lang['common_create']; }
			else if ( strstr($message, 'update') )		{ $message = $lang['common_update']; }
			else if ( strstr($message, 'delete') )		{ $message = $lang['common_delete']; }
			else if ( strstr($message, 'delete') )		{ $message = $lang['common_delete']; }
			else if ( strstr($message, 'login_acp') )	{ $message = $lang['common_login_acp']; }
			else if ( strstr($message, 'login') )		{ $message = $lang['common_login']; }
			else if ( strstr($message, 'order') )		{ $message = $lang['common_order']; }
			else if ( strstr($message, 'settings') )	{ $message = $lang['common_settings']; }
			else if ( strstr($message, 'error') )		{ $message = $lang['common_error'];		$class = 'row_error'; }
			else										{ $message = $tmp[$i]['log_message']; }
			*/
			
			$log_data = unserialize($tmp[$i]['log_data']);
			$msg_data = '<br />&nbsp;&raquo;';
			
			if ( is_array($log_data) )
			{
				$msg = array();
				$cnt_data = count($log_data);
				
				for ( $j = 0; $j < $cnt_data; $j++ )
				{
					$_meta	= isset($log_data[$j]['meta'])	? $log_data[$j]['meta'] : '';
					$_data	= isset($log_data[$j]['data'])	? $log_data[$j]['data'] : '';
					$_post	= isset($log_data[$j]['post'])	? $log_data[$j]['post'] : '';
					$field	= isset($log_data[$j]['field'])	? $log_data[$j]['field'] : '';
					$_lang	= isset($lang[$field])			? $lang[$field] : $field;
					
					if ( $_data && $_post )
					{
						$msg[] = sprintf($lang['sprintf_log_change'], $_lang, $_data, $_post);
					}
					else if ( !$_data && $_post )
					{
						$msg[] = sprintf($lang['sprintf_log_create'], $_lang, $_post);
					}
					else if ( $_data && !$_post )
					{
						$msg[] = sprintf($lang['sprintf_log_delete'], $_lang, $_data);
					}
				}
				
				$msg_data .= implode('<br />&nbsp;&raquo;', $msg);
			}
			else
			{
				$msg_data = $log_data;
			}
			
			/*
			$moep = '';
			
			if ( $tmp[$i]['log_section'] == SECTION_SERVER )
			{
				$msg = array();
				
				$data = unserialize($tmp[$i]['log_data']);
				
				$msg_data = '<br />&nbsp;&raquo;&nbsp;&nbsp;&raquo;&nbsp;';
				
				for ( $k = 0; $k < count($data); $k++ )
				{
					
					
					$_meta	= isset($log_data[$k]['meta'])	? $log_data[$k]['meta'] : '';
					$_data	= isset($log_data[$k]['data'])	? $log_data[$k]['data'] : '';
					$_post	= isset($log_data[$k]['post'])	? $log_data[$k]['post'] : '';
					$_field	= isset($log_data[$k]['field'])	? $log_data[$k]['field'] : '';
					
				#	$lng	= isset($lang[$_field]) ? $lang[$_field] : $_field;
					$lng	= $_field;
					
					if ( strstr($tmp[$i]['log_message'], 'update') && strstr($_field, '_own') ) { $moep = true; }
					
					if ( isset($_data) && isset($_post) )
					{
						$msg[] = sprintf($lang['sprintf_log_change'], $lng, $_data, $_post);
					}
					else if ( !$_data && $_post )
					{
						$msg[] = sprintf($lang['sprintf_log_create'], $lng, $_post);
					}
					else if ( $_data && !$_post )
					{
						$msg[] = sprintf($lang['sprintf_log_delete'], $lng, $_data);
					}
				}
				
				$msg_data .= implode('<br />&nbsp;&raquo;&nbsp;&nbsp;&raquo;&nbsp;', $msg);
				
				echo 'hmpf';
			}
			*/
			
			$template->assign_block_vars('display.row', array(
				'CLASS'		=> $class,
				
				'LOG_ID'		=> $tmp[$i]['log_id'],
				'USERNAME'      => $tmp[$i]['user_name'],
				'IP'            => decode_ip($tmp[$i]['user_ip']),
				'DATE'          => create_date($userdata['user_dateformat'], $tmp[$i]['log_time'], $userdata['user_timezone']),
				'SEKTION'       => $section,
				'MESSAGE'       => $message,
				'DATA'          => $msg_data,
			));
		}
	}
	
	$current_page = ( !count($tmp) ) ? 1 : ceil( count($tmp) / $settings['per_page_entry']['acp'] );
	
	$template->assign_vars(array(
		'PAGE_PAGING'	=> generate_pagination('admin_logs.php?', count($tmp), $settings['per_page_entry']['acp'], $start),
		'PAGE_NUMBER'   => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ),
	));
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>