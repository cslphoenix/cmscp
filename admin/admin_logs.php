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
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel � 2009, 2010
 *	@code:	Sebastian Frickel � 2009, 2010
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_development']['_submenu_logs']		= $filename;
		$module['_headmenu_development']['_submenu_logs_error']	= $filename . "?mode=error";
	//	$module['logs']['logs_admin']	= $filename . "?mode=admin";
	//	$module['logs']['logs_member']	= $filename . "?mode=member";
	//	$module['logs']['logs_user']	= $filename . "?mode=user";
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_logs';
	
	include('./pagestart.php');

	load_lang('logs');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$log_id		= request(POST_LOG_URL, 0);
	$mode		= request('mode', 1);
	$confirm	= request('confirm', 1);
	$s_fields	= '';
	
	if ( $userdata['user_level'] != ADMIN )
	{
		log_add(LOG_ADMIN, LOG_SEK_LOG, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $no_header ) ? redirect('admin/' . append_sid('admin_logs.php', true)) : false;
	
	switch ( $mode )
	{
		case 'error':
			
			$template->set_filenames(array('body' => 'style/acp_logs.tpl'));
			$template->assign_block_vars('error', array());
		
			$sql = 'SELECT * FROM ' . ERROR . ' ORDER BY error_id DESC';
			if (!($result = $db->sql_query($sql)))
			{
				message(GENERAL_ERROR, 'Could not obtain list', '', __LINE__, __FILE__, $sql);
			}
			$log_entry = $db->sql_fetchrowset($result); 
			$db->sql_freeresult($result);
			
			if ( !$log_entry )
			{
				$template->assign_block_vars('error.no_entry', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			else
			{
				for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($log_entry)); $i++)
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					$error_id			= $log_entry[$i]['error_id'];
					$error_userid		= $log_entry[$i]['error_userid'];
					$error_msg_title	= $log_entry[$i]['error_msg_title'];
					$error_msg_text		= $log_entry[$i]['error_msg_text'];
					$error_sql_code		= $log_entry[$i]['error_sql_code'];
					$error_sql_text		= $log_entry[$i]['error_sql_text'];
					$error_sql_store	= $log_entry[$i]['error_sql_store'];
					$error_file			= str_replace(array(cms_realpath($root_path), '\\'), array('', '/'), $log_entry[$i]['error_file']);
					$error_file_line	= $log_entry[$i]['error_file_line'];
					$error_time			= create_date($config['default_dateformat'], $log_entry[$i]['error_time'], $config['page_timezone']);
					
					$template->assign_block_vars('error.error_row', array(
						'CLASS'		=> $class,
						
						'TIME' => $error_time,
						'ERROR_ID' => $error_id,
						'ERROR_FILE' => $error_file,
						'ERROR_FILE_LINE' => $error_file_line,
						'ERROR_USERID' => $error_userid,
						'ERROR_MSG_TITLE' => $error_msg_title,
						'ERROR_MSG_TEXT' => $error_msg_text,
						'ERROR_SQL_CODE' => $error_sql_code,
						'ERROR_SQL_TEXT' => $error_sql_text,
						'ERROR_SQL_STORE' => $error_sql_store,
					));
				}
			}
		
			$current_page = ( !count($log_entry) ) ? 1 : ceil( count($log_entry) / $settings['site_entry_per_page'] );
		
			$template->assign_vars(array(
				'L_LOG_TITLE'		=> $lang['log_head'],
				'L_LOG_EXPLAIN'		=> $lang['log_explain'],
				'L_LOG_ERROR'		=> $lang['log_error'],
				
				'L_DELETE'			=> $lang['Delete'],
				'L_MARK_ALL'		=> $lang['mark_all'],
				'L_MARK_DEALL'		=> $lang['mark_deall'],
				'L_GOTO_PAGE'		=> $lang['Goto_page'],
				
				'PAGINATION'		=> generate_pagination('admin_logs.php?', count($log_entry), $settings['site_entry_per_page'], $start),
				'PAGE_NUMBER'		=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
				
				'S_LOG_ERROR'		=> append_sid('admin_logs.php?mode=error'),
				'S_LOG_ACTION'		=> append_sid('admin_logs.php'),
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
				
				log_add(LOG_ADMIN, LOG_SEK_LOG, 'acp_log_delete_error');
				
				$message = $lang['delete_log_error'] . sprintf($lang['click_return_log_error'], '<a href="' . append_sid('admin_logs.php?mode=error') . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
	
			}
			else if ( !$confirm && $log_id )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="deleteerror" />';
				$s_fields .= '<input type="hidden" name="log_ids" value="' . $log_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_log_error'],
	
					'L_YES'				=> $lang['common_yes'],
					'L_NO'				=> $lang['common_no'],
	
					'S_ACTION'	=> append_sid('admin_logs.php?mode=error'),
					'S_FIELDS'	=> $s_fields,
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
				
				log_add(LOG_ADMIN, LOG_SEK_LOG, 'acp_log_delete_all');
				
				$message = $lang['delete_log_all'] . sprintf($lang['click_return_log'], '<a href="' . append_sid('admin_logs.php') . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
	
			}
			else if ( !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="delete_all" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_all_log'],
	
					'L_YES'				=> $lang['common_yes'],
					'L_NO'				=> $lang['common_no'],
	
					'S_ACTION'	=> append_sid('admin_logs.php'),
					'S_FIELDS'	=> $s_fields,
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
				
				log_add(LOG_ADMIN, LOG_SEK_LOG, 'acp_log_delete');
				
				$message = $lang['delete_log'] . sprintf($lang['click_return_log'], '<a href="' . append_sid('admin_logs.php') . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
	
			}
			else if ( !$confirm && $log_id )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="delete" />';
				$s_fields .= '<input type="hidden" name="log_ids" value="' . $log_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_log'],
	
					'L_YES'				=> $lang['common_yes'],
					'L_NO'				=> $lang['common_no'],
	
					'S_ACTION'	=> append_sid('admin_logs.php'),
					'S_FIELDS'	=> $s_fields,
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
				'L_LOG_TITLE'		=> $lang['log_head'],
				'L_LOG_EXPLAIN'		=> $lang['log_explain'],
				'L_LOG_ERROR'		=> $lang['log_error'],
				
				'L_LOG_USERNAME'	=> $lang['log_username'],
				'L_LOG_IP'			=> $lang['log_ip'],
				'L_LOG_TIME'		=> $lang['log_time'],
				'L_LOG_SEKTION'		=> $lang['log_sektion'],
				'L_LOG_MESSAGE'		=> $lang['log_message'],
				'L_LOG_CHANGE'		=> $lang['log_change'],
				
				'L_MARK_ALL'		=> $lang['mark_all'],
				'L_MARK_DEALL'		=> $lang['mark_deall'],
				'L_DELETE'			=> $lang['common_delete'],
		
				'S_LOG_ERROR'		=> append_sid('admin_logs.php?mode=error'),
				'S_LOG_ACTION'		=> append_sid('admin_logs.php'),
			));
			
			$sql = 'SELECT l.*, u.username
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
				$template->assign_block_vars('_display._no_entry', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			else
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($log_entry)); $i++ )
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					// Log Sektion
					switch ( $log_entry[$i]['log_sektion'] )
					{
						case 0:
							$sektion = 'news';
							break;
						case 1:
							$sektion = 'team';
							break;
						case 2:
							$sektion = 'rank';
							break;
						case 3:
							$sektion = 'user';
							break;
						default:
							$sektion = $log_entry[$i]['log_sektion'];
							break;
					}
			
					$template->assign_block_vars('display.logs_row', array(
						'CLASS'		=> $class,
						
						'LOG_ID'	=> $log_entry[$i]['log_id'],
						'USERNAME'	=> $log_entry[$i]['username'],
						'IP'		=> decode_ip($log_entry[$i]['user_ip']),
						'DATE'		=> create_date($userdata['user_dateformat'], $log_entry[$i]['log_time'], $userdata['user_timezone']),
						'SEKTION'	=> $sektion,
						'MESSAGE'	=> $log_entry[$i]['log_message'],
						'DATA'		=> $log_entry[$i]['log_data']
					));
				}
			}
		
			
			
			$current_page = ( !count($log_entry) ) ? 1 : ceil( count($log_entry) / $settings['site_entry_per_page'] );
		
			$template->assign_vars(array(
				'L_GOTO_PAGE'	=> $lang['Goto_page'],
				'PAGINATION'	=> generate_pagination('admin_logs.php?', count($log_entry), $settings['site_entry_per_page'], $start),
				'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
			));
			
			$template->pparse('body');
			
			break;
	}
	include('./page_footer_admin.php');
}
?>