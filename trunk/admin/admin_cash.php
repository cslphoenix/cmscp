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
		redirect('admin/' . append_sid('admin_cash.php', true));
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
	
	if ( isset($HTTP_POST_VARS[POST_CASH_USER_URL]) || isset($HTTP_GET_VARS[POST_CASH_USER_URL]) )
	{
		$cash_user_id = ( isset($HTTP_POST_VARS[POST_CASH_USER_URL]) ) ? intval($HTTP_POST_VARS[POST_CASH_USER_URL]) : intval($HTTP_GET_VARS[POST_CASH_USER_URL]);
	}
	else
	{
		$cash_user_id = 0;
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
		else if ( isset($HTTP_POST_VARS['cash_user']) || isset($HTTP_GET_VARS['cash_user']) )
		{
			$mode = 'cash_user_add';
		}
		else if ( isset($HTTP_POST_VARS['bankdata_clear']) || isset($HTTP_GET_VARS['bankdata_clear']) )
		{
			$mode = 'bankdata_clear';
		}
		else
		{
			$mode = '';
		}
	}
	
	switch ($mode)
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
					'cash_interval'	=> '0',
				);

				$new_mode = 'cash_create';
			}
			
			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="' . POST_CASH_URL . '" value="' . $cash_id . '" />';

			$template->assign_vars(array(
				'L_CASH_HEAD'		=> $lang['cash_head'],
				'L_CASH_NEW_EDIT'	=> ( $mode == 'cash_add' ) ? $lang['cash_add'] : $lang['cash_edit'],
				'L_REQUIRED'		=> $lang['required'],
			
				'L_CASH_NAME'		=> $lang['cash_name'],
				'L_CASH_TYPE'		=> $lang['cash_type'],
				'L_CASH_AMOUNT'		=> $lang['cash_amount'],
				'L_CASH_INTERVAL'	=> $lang['cash_interval'],
				
				'L_CASH_TYPE_A'	=> $lang['cash_type_a'],
				'L_CASH_TYPE_B'	=> $lang['cash_type_b'],
				'L_CASH_TYPE_C'	=> $lang['cash_type_c'],
				
				'L_INTAVAL_0'	=> $lang['cash_interval_month'],
				'L_INTAVAL_1'	=> $lang['cash_interval_2weeks'],
				'L_INTAVAL_2'	=> $lang['cash_interval_week'],
				
				'L_SUBMIT'	=> $lang['common_submit'],
				'L_RESET'	=> $lang['common_reset'],
				'L_YES'		=> $lang['common_yes'],
				'L_NO'		=> $lang['common_no'],
				
				'CASH_NAME'		=> $cash['cash_name'],
				'CASH_AMOUNT'	=> $cash['cash_amount'],
				
				'S_CHECKED_TYPE_A'	=> ( $cash['cash_type'] == '0' ) ? ' checked="checked"' : '',
				'S_CHECKED_TYPE_B'	=> ( $cash['cash_type'] == '1' ) ? ' checked="checked"' : '',
				'S_CHECKED_TYPE_C'	=> ( $cash['cash_type'] == '2' ) ? ' checked="checked"' : '',
				
				'S_CHECKED_INTAVAL_0'	=> ( $cash['cash_interval'] == '0' ) ? ' checked="checked"' : '',
				'S_CHECKED_INTAVAL_1'	=> ( $cash['cash_interval'] == '1' ) ? ' checked="checked"' : '',
				'S_CHECKED_INTAVAL_2'	=> ( $cash['cash_interval'] == '2' ) ? ' checked="checked"' : '',
				
				'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
				'S_CASH_ACTION'		=> append_sid('admin_cash.php'),
			));
		
			$template->pparse('body');
			
			break;
		
		case 'cash_create':
		
			$cash_name		= ( isset($HTTP_POST_VARS['cash_name']) )		? trim($HTTP_POST_VARS['cash_name']) : '';
			$cash_amount	= ( isset($HTTP_POST_VARS['cash_amount']) )		? trim($HTTP_POST_VARS['cash_amount']) : '';
			$cash_type		= ( isset($HTTP_POST_VARS['cash_type']) )		? intval($HTTP_POST_VARS['cash_type']) : '';
			$cash_interval	= ( isset($HTTP_POST_VARS['cash_interval']) )	? intval($HTTP_POST_VARS['cash_interval']) : '';
			
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
			
			if ( $error )
			{
				message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
			}

			$sql = 'INSERT INTO ' . CASH . " (cash_name, cash_type, cash_amount, cash_interval)
				VALUES ('" . str_replace("\'", "''", $cash_name) . "', $cash_type, '" . str_replace("\'", "''", $cash_amount) . "', $cash_interval)";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_add', $cash_name);

			$message = $lang['create_cash'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);

			break;
		
		case 'cash_update':
		
			$cash_name		= ( isset($HTTP_POST_VARS['cash_name']) )		? trim($HTTP_POST_VARS['cash_name']) : '';
			$cash_amount	= ( isset($HTTP_POST_VARS['cash_amount']) )		? trim($HTTP_POST_VARS['cash_amount']) : '';
			$cash_type		= ( isset($HTTP_POST_VARS['cash_type']) )		? intval($HTTP_POST_VARS['cash_type']) : '';
			$cash_interval	= ( isset($HTTP_POST_VARS['cash_interval']) )	? intval($HTTP_POST_VARS['cash_interval']) : '';
			
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
			
			if ( $error )
			{
				message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
			}
			
			$sql = "UPDATE " . CASH . " SET
						cash_name		= '" . str_replace("\'", "''", $cash_name) . "',
						cash_type		= $cash_type,
						cash_amount		= '" . str_replace("\'", "''", $cash_amount) . "',
						cash_interval	= $cash_interval
					WHERE cash_id = " . $cash_id;
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_edit');
			
			$message = $lang['update_cash'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'cash_user_add':
		case 'cash_user_edit':

			$template->set_filenames(array('body' => './../admin/style/acp_cash.tpl'));
			$template->assign_block_vars('cash_user_edit', array());
			
			if ( $mode == 'cash_user_edit' )
			{
				$cash_user	= get_data('cash_user', $cash_user_id, 0);
				$new_mode	= 'cash_user_update';
			}
			else
			{
				$cash_user = array (
					'user_id'		=> intval($HTTP_POST_VARS['user_id']),
					'user_amount'	=> '',
					'user_month'	=> date("m", time()),
					'user_interval'	=> '1',
				);

				$new_mode = 'cash_user_create';
			}
			
			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="' . POST_CASH_USER_URL . '" value="' . $cash_user_id . '" />';

			$template->assign_vars(array(
				'L_CASH_HEAD'			=> $lang['cash_head'],
				'L_CASH_USER_NEW_EDIT'	=> ( $mode == 'cash_user_add' ) ? $lang['cash_user_add'] : $lang['cash_user_edit'],
				'L_REQUIRED'			=> $lang['required'],
			
				'L_CASH_USER'			=> $lang['cash_username'],
				'L_CASH_USER_AMOUNT'	=> $lang['cash_amount'],
				'L_CASH_USER_MONTH'		=> $lang['cash_user_month'],
				'L_CASH_USER_INTERVAL'	=> $lang['cash_interval'],
				
				'L_INTAVAL_M'	=> $lang['cash_user_interval_m'],
				'L_INTAVAL_O'	=> $lang['cash_user_interval_o'],
				
				'L_SUBMIT'	=> $lang['common_submit'],
				'L_RESET'	=> $lang['common_reset'],
				'L_YES'		=> $lang['common_yes'],
				'L_NO'		=> $lang['common_no'],
				
				
				'CASH_AMOUNT'	=> $cash_user['user_amount'],
				
				'S_MONTH'		=> _select_date('monthm', 'user_month', $cash_user['user_month']),
				
				'S_CHECKED_INTAVAL_M'	=> ( $cash_user['user_interval'] == '0' ) ? ' checked="checked"' : '',
				'S_CHECKED_INTAVAL_O'	=> ( $cash_user['user_interval'] == '1' ) ? ' checked="checked"' : '',
				
				'S_CASH_USER'		=> select_box('user', 'select', 'user_id', 'username', $cash_user['user_id']),
				'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
				'S_CASH_ACTION'		=> append_sid('admin_cash.php'),
			));
		
			$template->pparse('body');
			
			break;
			
		case 'cash_user_create':
		
			$user_id		= ( isset($HTTP_POST_VARS['user_id']) )			? trim($HTTP_POST_VARS['user_id']) : '';
			$user_amount	= ( isset($HTTP_POST_VARS['user_amount']) )		? trim($HTTP_POST_VARS['user_amount']) : '';
			$user_month		= ( isset($HTTP_POST_VARS['user_month']) )		? implode(', ', $HTTP_POST_VARS['user_month']) : '';
			$user_interval	= ( isset($HTTP_POST_VARS['user_interval']) )	? intval($HTTP_POST_VARS['user_interval']) : '0';
			
			$error = ''; 
			$error_msg = '';
			
			if ( !$user_amount )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_amount'];
			}
			
			if ( $error )
			{
				message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
			}
			

			$sql = 'INSERT INTO ' . CASH_USERS . " (user_id, user_amount, user_month, user_interval)
				VALUES ($user_id, '" . str_replace("\'", "''", $user_amount) . "', '$user_month', $user_interval)";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_user_add');

			$message = $lang['create_cash_user'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);

			break;
		
		case 'cash_user_update':
		
			$user_id		= ( isset($HTTP_POST_VARS['user_id']) )			? trim($HTTP_POST_VARS['user_id']) : '';
			$user_amount	= ( isset($HTTP_POST_VARS['user_amount']) )		? trim($HTTP_POST_VARS['user_amount']) : '';
			$user_month		= ( isset($HTTP_POST_VARS['user_month']) )		? implode(', ', $HTTP_POST_VARS['user_month']) : '';
			$user_interval	= ( isset($HTTP_POST_VARS['user_interval']) )	? intval($HTTP_POST_VARS['user_interval']) : '0';
			
			$error = ''; 
			$error_msg = '';
			
			if ( !$user_amount )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_amount'];
			}
			
			if ( $error )
			{
				message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
			}
			
			$sql = "UPDATE " . CASH_USERS . " SET
						user_id			= $user_id,
						user_amount		= '" . str_replace("\'", "''", $user_amount) . "',
						user_month		= '$user_month',
						user_interval	= $user_interval
					WHERE cash_user_id = " . $cash_user_id;
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_user_edit');
			
			$message = $lang['update_cash_user'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);

			break;
		
		case 'cash_user_delete':
		
			$confirm = isset($HTTP_POST_VARS['confirm']);
			
			if ( $cash_user_id && $confirm )
			{	
				$cash_user = get_data('cash_user', $cash_user_id, 0);
			
				$sql = 'DELETE FROM ' . CASH_USERS . ' WHERE cash_user_id = ' . $cash_user_id;
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_user_delete');
				
				$message = $lang['delete_cash_user'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			
			}
			else if ( $cash_user_id && !$confirm )
			{
				$template->set_filenames(array('body' => './../admin/style/info_confirm.tpl'));
	
				$hidden_fields = '<input type="hidden" name="mode" value="cash_user_delete" />';
				$hidden_fields .= '<input type="hidden" name="' . POST_CASH_USER_URL . '" value="' . $cash_user_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_cash_user'],
	
					'L_YES'				=> $lang['common_yes'],
					'L_NO'				=> $lang['common_no'],
	
					'S_CONFIRM_ACTION'	=> append_sid('admin_cash.php'),
					'S_HIDDEN_FIELDS'	=> $hidden_fields,
				));
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['msg_must_select_cash']);
			}
			
			$template->pparse('body');
			
			break;
			
		case 'bankdata_edit':

			$template->set_filenames(array('body' => './../admin/style/acp_cash.tpl'));
			$template->assign_block_vars('cash_bankdata', array());
			
			$sql = 'SELECT * FROM ' . CASH_BANK;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cash_bankdata = $db->sql_fetchrow($result);
			
			$s_hidden_fields = '<input type="hidden" name="mode" value="bankdata_update" />';
			$s_hidden_fields .= '<input type="hidden" name="' . POST_CASH_USER_URL . '" value="' . $cash_user_id . '" />';

			$template->assign_vars(array(
				'L_CASH_HEAD'			=> $lang['cash_head'],
				'L_CASH_BD'				=> $lang['cash_bankdata'],
				'L_REQUIRED'			=> $lang['required'],
			
				'L_BD_NAME'			=> $lang['cash_bd_name'],
				'L_BD_BANK'			=> $lang['cash_bd_bank'],
				'L_BD_BLZ'			=> $lang['cash_bd_blz'],
				'L_BD_NUMBER'		=> $lang['cash_bd_number'],
				'L_BD_REASON'		=> $lang['cash_bd_reason'],
				
				'BD_NAME'			=> $cash_bankdata['bankdata_name'],
				'BD_BANK'			=> $cash_bankdata['bankdata_bank'],
				'BD_BLZ'			=> $cash_bankdata['bankdata_blz'],
				'BD_NUMBER'			=> $cash_bankdata['bankdata_number'],
				'BD_REASON'			=> $cash_bankdata['bankdata_reason'],
				
				
				'L_SUBMIT'	=> $lang['common_submit'],
				'L_RESET'	=> $lang['common_reset'],

				'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
				'S_CASH_ACTION'		=> append_sid('admin_cash.php'),
			));
		
			$template->pparse('body');
			
			break;
			
		case 'bankdata_update':
		
			$bankdata_name		= ( isset($HTTP_POST_VARS['bd_name']) )	? trim($HTTP_POST_VARS['bd_name']) : '';
			$bankdata_bank		= ( isset($HTTP_POST_VARS['bd_bank']) )	? trim($HTTP_POST_VARS['bd_bank']) : '';
			$bankdata_blz		= ( isset($HTTP_POST_VARS['bd_blz']) )	? trim($HTTP_POST_VARS['bd_blz']) : '';
			$bankdata_number	= ( isset($HTTP_POST_VARS['bd_number']) )	? trim($HTTP_POST_VARS['bd_number']) : '';
			$bankdata_reason	= ( isset($HTTP_POST_VARS['bd_reason']) )	? trim($HTTP_POST_VARS['bd_reason']) : '';
			
			$error = ''; 
			$error_msg = '';
			
			if ( !$bankdata_name )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_bankdata_name'];
			}
			
			if ( !$bankdata_bank )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_bankdata_bank'];
			}
			
			if ( !$bankdata_blz )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_bankdata_blz'];
			}
			
			if ( !$bankdata_number )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_bankdata_number'];
			}
			
			if ( !$bankdata_reason )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_bankdata_reason'];
			}
			
			if ( $error )
			{
				message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
			}
			
			$sql = 'SELECT * FROM ' . CASH_BANK;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			if ( $db->sql_numrows($result) )
			{			
				$sql = "UPDATE " . CASH_BANK . " SET
							bankdata_name	= '" . str_replace("\'", "''", $bankdata_name) . "',
							bankdata_bank	= '" . str_replace("\'", "''", $bankdata_bank) . "',
							bankdata_blz	= '" . str_replace("\'", "''", $bankdata_blz) . "',
							bankdata_number	= '" . str_replace("\'", "''", $bankdata_number) . "',
							bankdata_reason = '" . str_replace("\'", "''", $bankdata_reason) . "'";
			}
			else
			{			
				$sql = 'INSERT INTO ' . CASH_BANK . " (bankdata_name, bankdata_bank, bankdata_blz, bankdata_number, bankdata_reason)
					VALUES ('" . str_replace("\'", "''", $bankdata_name) . "', '" . str_replace("\'", "''", $bankdata_bank) . "', '" . str_replace("\'", "''", $bankdata_blz) . "', '" . str_replace("\'", "''", $bankdata_number) . "', '" . str_replace("\'", "''", $bankdata_reason) . "')";
			}
			
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_bankdata');
			
			$message = $lang['update_cash_bank'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'bankdata_clear':
		
			$confirm = isset($HTTP_POST_VARS['confirm']);
			
			if ( $confirm )
			{	
				$sql = 'TRUNCATE TABLE ' . CASH_BANK;
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'acp_cash_bank_delete');
				
				$message = $lang['delete_cash_bank'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			
			}
			else if ( !$confirm )
			{
				$template->set_filenames(array('body' => './../admin/style/info_confirm.tpl'));
	
				$hidden_fields = '<input type="hidden" name="mode" value="bankdata_clear" />';
				$hidden_fields .= '<input type="hidden" name="' . POST_CASH_URL . '" value="' . $cash_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_cash_bank'],
	
					'L_YES'				=> $lang['common_yes'],
					'L_NO'				=> $lang['common_no'],
	
					'S_CONFIRM_ACTION'	=> append_sid('admin_cash.php'),
					'S_HIDDEN_FIELDS'	=> $hidden_fields,
				));
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['msg_must_select_cash']);
			}
			
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
				
				$message = $lang['delete_cash'] . '<br><br>' . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
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
	
					'S_CONFIRM_ACTION'	=> append_sid('admin_cash.php'),
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
			
			$template->set_filenames(array('body' => './../admin/style/acp_cash.tpl'));
			$template->assign_block_vars('display', array());
					
			$sql = 'SELECT * FROM ' . CASH;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cash_data = $db->sql_fetchrowset($result);
			
			$sql = 'SELECT * FROM ' . CASH_BANK;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			if ( $db->sql_numrows($result) )
			{
				$cash_bankdata = $db->sql_fetchrow($result);
				
				$template->assign_block_vars('display.show_bd', array());
				$template->assign_vars(array(
					'BD_NAME'	=> $cash_bankdata['bankdata_name'],
					'BD_BANK'	=> $cash_bankdata['bankdata_bank'],
					'BD_BLZ'	=> $cash_bankdata['bankdata_blz'],
					'BD_NUMBER'	=> $cash_bankdata['bankdata_number'],
					'BD_REASON'	=> $cash_bankdata['bankdata_reason'],
				));
			}
			
			$total_amount = '';
			
			if ( !$cash_data )
			{
				$template->assign_block_vars('display.no_entry', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			else
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($cash_data)); $i++ )
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					$cash_id	= $cash_data[$i]['cash_id'];
					
					switch ( $cash_data[$i]['cash_interval'] )
					{
						case '0':
							$cash_interval	= $lang['cash_interval_month'];
							$cash_amount	= $cash_data[$i]['cash_amount'];
							break;
						case '1':
							$cash_interval	= $lang['cash_interval_2weeks'];
							$cash_amount	= 2 * str_replace(',', '.', $cash_data[$i]['cash_amount']);
							break;
						case '2':
							$cash_interval	= $lang['cash_interval_week'];
							$cash_amount	= 4 * str_replace(',', '.', $cash_data[$i]['cash_amount']);
							break;
					}
					
					$template->assign_block_vars('display.cash_row', array(
						'CLASS' 		=> $class,
						
						'CASH_NAME'		=> $cash_data[$i]['cash_name'],
						'CASH_AMOUNT'	=> $cash_data[$i]['cash_amount'],
						'CASH_DATE'		=> $cash_interval,
						
						'U_EDIT'		=> append_sid('admin_cash.php?mode=cash_edit&amp;' . POST_CASH_URL . '=' . $cash_id),
						'U_DELETE'		=> append_sid('admin_cash.php?mode=cash_delete&amp;' . POST_CASH_URL . '=' . $cash_id)
					));
					
					$total_amount += $cash_amount;
				}
			}
			
			$sql = 'SELECT cu.*, u.username, u.user_color
						FROM ' . CASH_USERS . ' cu
							LEFT JOIN ' . USERS . ' u ON cu.user_id = u.user_id
						WHERE u.user_id <> ' . ANONYMOUS . '
					ORDER BY cu.user_id, cu.user_interval';
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cash_users_data = $db->sql_fetchrowset($result);
			
			if ( !$cash_users_data )
			{
				$template->assign_block_vars('display.no_entry_users', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			else
			{
				$total_users_amount = '';
				
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($cash_users_data)); $i++ )
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					$cash_user_id = $cash_users_data[$i]['cash_user_id'];
					
					$month_cur	= date("m", time());
					$month_user	= explode(', ', $cash_users_data[$i]['user_month']);
					
					if ( $month_cur == $month_user || in_array($month_cur, $month_user) )
					{
						$total_users_amount += $cash_users_data[$i]['user_amount'];
					}
					else if ( !$cash_users_data[$i]['user_interval'] && in_array($month_cur, $month_user) )
					{
						$total_users_amount += $cash_users_data[$i]['user_amount'];
					}
					
					$user_interval	= ( $cash_users_data[$i]['user_interval'] ) ? $lang['cash_user_interval_o'] : $lang['cash_user_interval_m'];
					
					
					$template->assign_block_vars('display.cash_users_row', array(
						'CLASS' 				=> $class,
						
						'CASH_USERNAME'			=> $cash_users_data[$i]['username'],
						'CASH_USER_AMOUNT'		=> $cash_users_data[$i]['user_amount'],
						'CASH_USER_INTERVAL'	=> $user_interval,
						
						'U_EDIT'		=> append_sid('admin_cash.php?mode=cash_user_edit&amp;' . POST_CASH_USER_URL . '=' . $cash_user_id),
						'U_DELETE'		=> append_sid('admin_cash.php?mode=cash_user_delete&amp;' . POST_CASH_USER_URL . '=' . $cash_user_id)
					));
				}
			}
			
			$template->assign_vars(array(
				'L_CASH_HEAD'		=> $lang['cash_head'],
				'L_CASH_EXPLAIN'	=> $lang['cash_explain'],
				'L_CASH_NAME'		=> $lang['cash_name'],
				'L_CASH_BD'			=> $lang['cash_bankdata'],
				'L_CASH_BANK_CLEAR'	=> $lang['cash_bank_clear'],
				'L_CASH_USERNAME'	=> $lang['username'],
				
				'L_BD_NAME'			=> $lang['cash_bd_name'],
				'L_BD_BANK'			=> $lang['cash_bd_bank'],
				'L_BD_BLZ'			=> $lang['cash_bd_blz'],
				'L_BD_NUMBER'		=> $lang['cash_bd_number'],
				'L_BD_REASON'		=> $lang['cash_bd_reason'],
				
				'L_CASH_ADD'		=> $lang['cash_add'],
				'L_CASH_USER_ADD'	=> $lang['cash_user_add'],
				
				'L_CASH_INTERVAL'	=> $lang['cash_interval'],
				'L_EDIT'			=> $lang['edit'],
				'L_SETTINGS'		=> $lang['settings'],
				'L_DELETE'			=> $lang['delete'],
				
				'TOTAL_CASH'		=> $total_amount,
				'USER_CASH'			=> $total_users_amount,
				
				'S_CASH_USER_ADD'	=> select_box('user', 'selectsmall', 'user_id', 'username'),
				
				'S_CASH_BD'			=> append_sid('admin_cash.php?mode=bankdata_edit'),
				'S_CASH_ACTION'		=> append_sid('admin_cash.php'),
			));
			
			$template->pparse('body');
			
			break;
	}
	include('./page_footer_admin.php');
}
?>