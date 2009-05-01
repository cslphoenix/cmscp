<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_cash'] || $userdata['user_level'] == ADMIN )
	{
		$module['main']['cash'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	include($root_path . 'includes/functions_admin.php');
	include($root_path . 'includes/functions_selects.php');
	
	if ( !$userauth['auth_cash'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid("admin_cash.php", true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_CASH_URL]) || isset($HTTP_GET_VARS[POST_CASH_URL]) )
	{
		$cash_id = ( isset($HTTP_POST_VARS[POST_CASH_URL]) ) ? intval($HTTP_POST_VARS[POST_CASH_URL]) : intval($HTTP_GET_VARS[POST_CASH_URL]);
	}
	else
	{
		$cash_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		if ( isset($HTTP_POST_VARS['cash_add']) || isset($HTTP_GET_VARS['cash_add']) )
		{
			$mode = 'cash_add';
		}
		else
		{
			$mode = '';
		}
	}
	
	$show_index = '';
	
	if ( !empty($mode) )
	{
		switch($mode)
		{
			case 'cash_add':
			case 'cash_edit':
			
				$template->set_filenames(array('body' => './../admin/style/acp_cash.tpl'));
				$template->assign_block_vars('cash_edit', array());
				
				if ( $mode == 'cash_edit' )
				{
					$cash		= get_data('cash', $cash_id, 0);
					$new_mode	= 'cash_update';
				}
				else
				{
					$cash = array (
						'cash_name'		=> trim($HTTP_POST_VARS['cash_name']),
						'cash_type'		=> '0',
						'cash_amount'	=> '',
					);

					$new_mode = 'cash_create';
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_CASH_URL . '" value="' . $cash_id . '" />';

				$template->assign_vars(array(
					'L_CASH_HEAD'		=> $lang['cash_head'],
					'L_CASH_NEW_EDIT'	=> ( $mode == 'cash_add' ) ? $lang['cash_add'] : $lang['cash_edit'],
					'L_REQUIRED'		=> $lang['required'],
				
					'L_CASH_NAME'	=> $lang['cash_name'],
					'L_CASH_TYPE'	=> $lang['cash_type'],
					'L_CASH_AMOUNT'	=> $lang['cash_amount'],
					
					'L_CASH_TYPE_A'	=> $lang['cash_type_a'],
					'L_CASH_TYPE_B'	=> $lang['cash_type_b'],
					'L_CASH_TYPE_C'	=> $lang['cash_type_c'],
					
					'L_SUBMIT'	=> $lang['common_submit'],
					'L_RESET'	=> $lang['common_reset'],
					'L_YES'		=> $lang['common_yes'],
					'L_NO'		=> $lang['common_no'],
					
					'CASH_NAME'		=> $cash['cash_name'],
					'CASH_AMOUNT'	=> $cash['cash_amount'],
					
					'S_CHECKED_TYPE_A'	=> ( $cash['cash_type'] == '0' ) ? ' checked="checked"' : '',
					'S_CHECKED_TYPE_B'	=> ( $cash['cash_type'] == '1' ) ? ' checked="checked"' : '',
					'S_CHECKED_TYPE_C'	=> ( $cash['cash_type'] == '2' ) ? ' checked="checked"' : '',
					
					'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
					'S_CASH_ACTION'		=> append_sid("admin_cash.php"),
				));
			
				$template->pparse('body');
				
			break;
			
			case 'cash_create':
			
				$cash_name		= ( isset($HTTP_POST_VARS['cash_name']) )	? trim($HTTP_POST_VARS['cash_name']) : '';
				$cash_amount	= ( isset($HTTP_POST_VARS['cash_amount']) )	? trim($HTTP_POST_VARS['cash_amount']) : '';
				$cash_type		= ( isset($HTTP_POST_VARS['cash_type']) )	? intval($HTTP_POST_VARS['cash_type']) : '';
				
				$error = ''; 
				$error_msg = '';
				
				if ( !$cash_name )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_name'];
				}
				
				if ( !$cash_amount )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_amount'];
				}
				
		//		if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
		//		{
		//			$error = true;
		//			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_date'];
		//		}
				
				if ($error)
				{
					message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
				}
				
		//		$cash_date = mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
		//		$cash_duration	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'] + $HTTP_POST_VARS['dmin'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);

				$cash_date = '0';
				$cash_duration	= '0';

				$sql = 'INSERT INTO ' . CASH . " (cash_name, cash_type, cash_amount, cash_date, cash_duration)
					VALUES ('" . str_replace("\'", "''", $cash_name) . "', $cash_type, '" . str_replace("\'", "''", $cash_amount) . "', $cash_date, $cash_duration)";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_add', $cash_name);
	
				$message = $lang['create_cash'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid("admin_cash.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'cash_update':
			
				$cash_name		= ( isset($HTTP_POST_VARS['cash_name']) )	? trim($HTTP_POST_VARS['cash_name']) : '';
				$cash_amount	= ( isset($HTTP_POST_VARS['cash_amount']) )	? trim($HTTP_POST_VARS['cash_amount']) : '';
				$cash_type		= ( isset($HTTP_POST_VARS['cash_type']) )	? intval($HTTP_POST_VARS['cash_type']) : '';
				
				$error = ''; 
				$error_msg = '';
				
				if ( !$cash_title )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_title'];
				}
				
				if ( !$cash_description )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_description'];
				}
				
				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_date'];
				}
				
				if ($error)
				{
					message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
				}
				
		//		$cash_date = mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
		//		$cash_duration	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'] + $HTTP_POST_VARS['dmin'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);

				$cash_date = '0';
				$cash_duration	= '0';
				
				$sql = "UPDATE " . CASH . " SET
							cash_name		= '" . str_replace("\'", "''", $cash_name) . "',
							cash_type		= $cash_type,
							cash_amount		= '" . str_replace("\'", "''", $cash_amount) . "',
							cash_date		= $cash_date,
							cash_duration	= $cash_duration,
						WHERE cash_id = " . $cash_id;
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_edit');
				
				$message = $lang['update_cash'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid("admin_cash.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
				
			case 'cashuser_add':
			case 'cashuser_edit':
			
			CREATE TABLE IF NOT EXISTS `cms_cash_users` (
  `cash_user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_amount` varchar(10) COLLATE utf8_bin NOT NULL,
  `user_duration` int(11) unsigned NOT NULL,
  `cash_type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cash_user_id`)
)
				$template->set_filenames(array('body' => './../admin/style/acp_cash.tpl'));
				$template->assign_block_vars('cashuser_edit', array());
				
				if ( $mode == 'cashuser_edit' )
				{
					$cashuser	= get_data('cashuser', $user_id, 0);
					$new_mode	= 'cashuser_update';
				}
				else
				{
					$cash = array (
						'user_id'		=> '',
						'user_amount'	=> '',
						'user_duration'	=> '',
						'cash_type'		=> '',
					);

					$new_mode = 'cashuser_create';
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_CASHUSER_URL . '" value="' . $cash_id . '" />';

				$template->assign_vars(array(
					'L_CASH_HEAD'		=> $lang['cash_head'],
					'L_CASH_NEW_EDIT'	=> ( $mode == 'cash_add' ) ? $lang['cash_add'] : $lang['cash_edit'],
					'L_REQUIRED'		=> $lang['required'],
				
					'L_CASH_NAME'	=> $lang['cash_name'],
					'L_CASH_TYPE'	=> $lang['cash_type'],
					'L_CASH_AMOUNT'	=> $lang['cash_amount'],
					
					'L_CASH_TYPE_A'	=> $lang['cash_type_a'],
					'L_CASH_TYPE_B'	=> $lang['cash_type_b'],
					'L_CASH_TYPE_C'	=> $lang['cash_type_c'],
					
					'L_SUBMIT'	=> $lang['common_submit'],
					'L_RESET'	=> $lang['common_reset'],
					'L_YES'		=> $lang['common_yes'],
					'L_NO'		=> $lang['common_no'],
					
					'CASH_NAME'		=> $cash['cash_name'],
					'CASH_AMOUNT'	=> $cash['cash_amount'],
					
					'S_CHECKED_TYPE_A'	=> ( $cash['cash_type'] == '0' ) ? ' checked="checked"' : '',
					'S_CHECKED_TYPE_B'	=> ( $cash['cash_type'] == '1' ) ? ' checked="checked"' : '',
					'S_CHECKED_TYPE_C'	=> ( $cash['cash_type'] == '2' ) ? ' checked="checked"' : '',
					
					'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
					'S_CASH_ACTION'		=> append_sid("admin_cash.php"),
				));
			
				$template->pparse('body');
				
				break;
				
			case 'cash_delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $cash_id && $confirm )
				{	
					$cash = get_data('cash', $cash_id, 0);
				
					$sql = 'DELETE FROM ' . CASH . ' WHERE cash_id = ' . $cash_id;
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_delete', $cash['cash_name']);
					
					$message = $lang['delete_cash'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid("admin_cash.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $cash_id && !$confirm )
				{
					$template->set_filenames(array('body' => './../admin/style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="cash_delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_CASH_URL . '" value="' . $cash_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_cash'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_cash.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_cash']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
				
				message_die(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_cash.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_CASH_HEAD'		=> $lang['cash_head'],
		'L_CASH_EXPLAIN'	=> $lang['cash_explain'],
		'L_CASH_NAME'		=> $lang['cash_name'],
		
		'L_CASH_ADD'		=> $lang['cash_add'],

		'L_EDIT'			=> $lang['edit'],
		'L_SETTINGS'		=> $lang['settings'],
		'L_DELETE'			=> $lang['delete'],
		
		'S_CASH_ACTION'		=> append_sid("admin_cash.php"),
	));
	
	$sql = 'SELECT * FROM ' . CASH;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cash_data = $db->sql_fetchrowset($result);
	
	if ( !$cash_data )
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		$total_amount = '';
		
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($cash_data)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$cash_id	= $cash_data[$i]['cash_id'];
			$cash_date	= create_date($userdata['user_dateformat'], $cash_data[$i]['cash_date'], $userdata['user_timezone']);
			
			if ( $config['time_today'] < $cash_data[$i]['cash_date'])
			{ 
				$cash_date = sprintf($lang['today_at'], create_date($config['default_timeformat'], $cash_data[$i]['cash_date'], $userdata['user_timezone'])); 
			}
			else if ( $config['time_yesterday'] < $cash_data[$i]['cash_date'])
			{ 
				$cash_date = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $cash_data[$i]['cash_date'], $userdata['user_timezone'])); 
			}
				
			$template->assign_block_vars('display.cash_row', array(
				'CLASS' 		=> $class,
				
				'CASH_NAME'		=> $cash_data[$i]['cash_name'],
				'CASH_AMOUNT'	=> $cash_data[$i]['cash_amount'],
				'CASH_DATE'		=> $cash_date,
				
				'U_EDIT'		=> append_sid("admin_cash.php?mode=cash_edit&amp;" . POST_CASH_URL . "=" . $cash_id),
				'U_DELETE'		=> append_sid("admin_cash.php?mode=cash_delete&amp;" . POST_CASH_URL . "=" . $cash_id)
			));
			
			$total_amount += $cash_data[$i]['cash_amount'];
		}
	}
	
	$sql = 'SELECT cu.*, u.username, u.user_color
				FROM ' . CASH_USERS . ' cu
					LEFT JOIN ' . USERS . ' u ON cu.user_id = u.user_id
				WHERE u.user_id <> ' . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cashusers_data = $db->sql_fetchrowset($result);
	
	if ( !$cashusers_data )
	{
		$template->assign_block_vars('display.no_entry_users', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		$total_amount = '';
		
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($cashusers_data)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$cash_id	= $cashusers_data[$i]['cash_id'];
			$cash_date	= create_date($userdata['user_dateformat'], $cashusers_data[$i]['cash_date'], $userdata['user_timezone']);
			
			if ( $config['time_today'] < $cashusers_data[$i]['cash_date'])
			{ 
				$cash_date = sprintf($lang['today_at'], create_date($config['default_timeformat'], $cashusers_data[$i]['cash_date'], $userdata['user_timezone'])); 
			}
			else if ( $config['time_yesterday'] < $cashusers_data[$i]['cash_date'])
			{ 
				$cash_date = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $cashusers_data[$i]['cash_date'], $userdata['user_timezone'])); 
			}
				
			$template->assign_block_vars('display.cashusers_row', array(
				'CLASS' 		=> $class,
				
				'CASH_NAME'		=> $cashusers_data[$i]['cash_name'],
				'CASH_AMOUNT'	=> $cashusers_data[$i]['cash_amount'],
				'CASH_DATE'		=> $cash_date,
				
				'U_EDIT'		=> append_sid("admin_cash.php?mode=cash_edit&amp;" . POST_CASH_URL . "=" . $cash_id),
				'U_DELETE'		=> append_sid("admin_cash.php?mode=cash_delete&amp;" . POST_CASH_URL . "=" . $cash_id)
			));
			
			$total_amount += $cashusers_data[$i]['cash_amount'];
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>