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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_cash'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_main']['_submenu_cash'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	$current	= '_submenu_cash';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/cash.php');
	
	$start			= ( request('start') ) ? request('start') : 0;
	$start			= ( $start < 0 ) ? 0 : $start;
	$cash_id		= request(POST_CASH_URL, 0);
	$cashuser_id	= request(POST_CASHUSER_URL, 0);
	$confirm		= request('confirm');
	$mode			= request('mode');

	if ( !$userauth['auth_cash'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_cash.php', true));
	}
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('cash_edit', array());
			
			if ( $mode == '_create' && !request('submit', 2) )
			{
				$data = array(
					'cash_name'		=> request('cash_name', 2),
					'cash_type'		=> '0',
					'cash_amount'	=> '0',
					'cash_interval'	=> '0',
				);
			}
			else if ( $mode == '_update' && !request('submit', 2) )
			{
				$data = get_data(CASH, $data_id, 1);
			}
			else
			{
				$data = array(
					'cash_name'		=> request('cash_name', 2),
					'cash_type'		=> request('cash_type', 0),
					'cash_amount'	=> request('cash_amount', 0),
					'cash_interval'	=> request('cash_interval', 0),
				);
			}
			
			$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
			$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_CASH_URL . '" value="' . $cash_id . '" />';

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['cash']),
				'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['cash']),
			
				'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['cash']),
				'L_TYPE'		=> sprintf($lang['sprintf_type'], $lang['cash']),
				'L_AMOUNT'		=> $lang['cash_amount'],
				'L_INTERVAL'	=> $lang['cash_interval'],
				
				'L_TYPE_A'		=> $lang['cash_type_a'],
				'L_TYPE_B'		=> $lang['cash_type_b'],
				'L_TYPE_C'		=> $lang['cash_type_c'],
				'L_INTAVAL_0'	=> $lang['cash_interval_month'],
				'L_INTAVAL_1'	=> $lang['cash_interval_2weeks'],
				'L_INTAVAL_2'	=> $lang['cash_interval_week'],
				
				'L_NO'			=> $lang['common_no'],
				'L_YES'			=> $lang['common_yes'],
				'L_RESET'		=> $lang['common_reset'],
				'L_SUBMIT'		=> $lang['common_submit'],
				
				'CASH_NAME'		=> $cash['cash_name'],
				'CASH_AMOUNT'	=> $cash['cash_amount'],
				
				'S_TYPE_A'		=> ( $cash['cash_type'] == '0' ) ? ' checked="checked"' : '',
				'S_TYPE_B'		=> ( $cash['cash_type'] == '1' ) ? ' checked="checked"' : '',
				'S_TYPE_C'		=> ( $cash['cash_type'] == '2' ) ? ' checked="checked"' : '',
				'S_INT_1'		=> ( $cash['cash_interval'] == '0' ) ? ' checked="checked"' : '',
				'S_INT_1'		=> ( $cash['cash_interval'] == '1' ) ? ' checked="checked"' : '',
				'S_INT_2'		=> ( $cash['cash_interval'] == '2' ) ? ' checked="checked"' : '',
				
				'S_FIELDS'		=> $s_fields,
				'S_ACTION'		=> append_sid('admin_cash.php'),
			));
			
			if ( request('submit', 2) )
			{
				$cash_name		= request('cash_name', 2);
				$cash_type		= request('cash_type', 0);
				$cash_amount	= request('cash_amount', 2);
				$cash_interval	= request('cash_interval', 0);
				
				$error = '';
				$error .= ( !$cash_name ) ? $lang['msg_select_name'] : '';
				$error .= ( !$cash_amount ) ? ( $error ? '<br>' : '' ) . $lang['msg_select_amount'] : '';
				
				if ( $error )
				{
					$template->set_filenames(array('reg_header' => 'style/error_body.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
				else
				{
					if ( $mode == '_create' )
					{
						$sql = "INSERT INTO " . CASH . " (cash_name, cash_type, cash_amount, cash_interval) VALUES ('$cash_name', '$cash_type', '$cash_amount', '$cash_interval')";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create_cash'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
						log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'create_cash');
					}
					else
					{
						$sql = "UPDATE " . CASH . " SET
									cash_name		= '$cash_name',
									cash_type		= '$cash_type',
									cash_amount		= '$cash_amount',
									cash_interval	= '$cash_interval'
								WHERE cash_id = $cash_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['update_cash']
							. sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>')
							. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_cash.php?mode=_update&amp;' . POST_CASH_URL . '=' . $data_id) . '">', '</a>');
						log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'update_cash');
					}
					message(GENERAL_MESSAGE, $message);
				}
			}
			
			break;
		
		case '_cashuser_update':

			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('cashuser_edit', array());
			
			if ( $mode == '_cashuser_update' )
			{
				$cash_user	= get_data('cashuser', $cashuser_id, 0);
				$new_mode	= '_cashuser_update_save';
			}
			else
			{
				$cash_user = array(
					'user_id'		=> request('user_id', 0),
					'user_amount'	=> '0',
					'user_month'	=> date("m", time()),
					'user_interval'	=> '1',
				);
				$new_mode = '_cashuser_create_save';
			}
			
			$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_CASHUSER_URL . '" value="' . $cashuser_id . '" />';

			$template->assign_vars(array(
				'L_HEAD'			=> $lang['cash_head'],
				'L_CASHUSER_NEW_EDIT'	=> ( $mode == '_user_add' ) ? $lang['cash_user_add'] : $lang['cash_user_edit'],
			
				'L_CASHUSER'			=> $lang['cash_username'],
				'L_CASHUSER_AMOUNT'	=> $lang['cash_amount'],
				'L_CASHUSER_MONTH'		=> $lang['cash_user_month'],
				'L_CASHUSER_INTERVAL'	=> $lang['cash_interval'],
				'L_INTAVAL_M'			=> $lang['cash_user_interval_m'],
				'L_INTAVAL_O'			=> $lang['cash_user_interval_o'],
				
				'L_NO'					=> $lang['common_no'],
				'L_YES'					=> $lang['common_yes'],
				'L_RESET'				=> $lang['common_reset'],
				'L_SUBMIT'				=> $lang['common_submit'],
				
				'CASH_AMOUNT'			=> $cash_user['user_amount'],
				'S_MONTH'				=> select_date('monthm', 'user_month', $cash_user['user_month']),
				'S_INTAVAL_M'	=> ( $cash_user['user_interval'] == '0' ) ? ' checked="checked"' : '',
				'S_INTAVAL_O'	=> ( $cash_user['user_interval'] == '1' ) ? ' checked="checked"' : '',
				
				'S_CASHUSER'			=> select_box('user', 'select', 'user_id', 'username', $cash_user['user_id']),
				'S_FIELDS'		=> $s_fields,
				'S_ACTION'			=> append_sid('admin_cash.php'),
			));
			
			break;
			
		case '_cashuser_create_save':
		
			$user_id		= request('user_id', 0);
			$user_amount	= request('user_amount', 0);
			$user_month		= ( request('user_month', 'only') ) ? implode(', ', request('user_month', 'only')) : '';
			$user_interval	= request('user_interval');
			
			$error_msg = '';
			$error_msg .= ( !$user_id ) ? $lang['msg_select_user'] : '';
			$error_msg .= ( !$user_id ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_amount'] : '';
			
			if ( $error_msg )
			{
				message(GENERAL_ERROR, $error_msg . $lang['back']);
			}
			
			$sql = 'INSERT INTO ' . CASH_USERS . " (user_id, user_amount, user_month, user_interval) VALUES ($user_id, '" . str_replace("\'", "''", $user_amount) . "', '$user_month', $user_interval)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$message = $lang['create_cash_user'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'create_cash_user');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_cashuser_update_save':
		
			$user_id		= request('user_id', 0);
			$user_amount	= request('user_amount', 0);
			$user_month		= ( request('user_month', 'only') ) ? implode(', ', request('user_month', 'only')) : '';
			$user_interval	= request('user_interval');
			
			if ( !$user_amount )
			{
				message(GENERAL_ERROR, $lang['msg_select_amount'] . $lang['back']);
			}
			
			$sql = "UPDATE " . CASH_USERS . " SET
						user_id			= $user_id,
						user_amount		= $user_amount,
						user_month		= '$user_month',
						user_interval	= $user_interval
					WHERE cash_user_id = " . $cashuser_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$message = $lang['update_cash_user'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'update_cash_user');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_bankdata':

			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('cash_bankdata', array());
			
			$cash_bank = get_data('cash_bank', '', 4);
			
			$s_fields = '<input type="hidden" name="mode" value="_bankdata_save" />';

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['cash']),
				'L_BANKDATA'	=> sprintf($lang['sprintf_processing'], $lang['bankdata']),
			
				'L_NAME'		=> $lang['cash_bd_name'],
				'L_BANK'		=> $lang['cash_bd_bank'],
				'L_BLZ'			=> $lang['cash_bd_blz'],
				'L_NUMBER'		=> $lang['cash_bd_number'],
				'L_REASON'		=> $lang['cash_bd_reason'],
				
				'NAME'			=> $cash_bank['bankdata_name'],
				'BANK'			=> $cash_bank['bankdata_bank'],
				'BLZ'			=> $cash_bank['bankdata_blz'],
				'NUMBER'		=> $cash_bank['bankdata_number'],
				'REASON'		=> $cash_bank['bankdata_reason'],
				
				'S_FIELDS'		=> $s_fields,
				'S_ACTION'		=> append_sid('admin_cash.php'),
			));
		
			break;
			
		case '_bankdata_save':
			
			$bankdata_name		= request('bankdata_name', 2);
			$bankdata_bank		= request('bankdata_bank', 2);
			$bankdata_blz		= request('bankdata_blz', 2);
			$bankdata_number	= request('bankdata_number', 2);
			$bankdata_reason	= request('bankdata_reason', 2);
			
			$error_msg = '';
			$error_msg .= ( !$bankdata_name ) ? $lang['msg_select_name'] : '';
			$error_msg .= ( !$bankdata_bank ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_bank'] : '';
			$error_msg .= ( !$bankdata_blz ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_blz'] : '';
			$error_msg .= ( !$bankdata_number ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_number'] : '';
			$error_msg .= ( !$bankdata_reason ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_reason'] : '';
			
			if ( $error_msg )
			{
				message(GENERAL_ERROR, $error_msg . $lang['back']);
			}
			
			$cash_bank = get_data('cash_bank', '', 4);
			
			if ( $cash_bank )
			{			
				$sql = "UPDATE " . CASH_BANK . " SET
							bankdata_name	= '$bankdata_name',
							bankdata_bank	= '$bankdata_bank',
							bankdata_blz	= '$bankdata_blz',
							bankdata_number	= '$bankdata_number',
							bankdata_reason = '$bankdata_reason'";
			}
			else
			{			
				$sql = "INSERT INTO " . CASH_BANK . " (bankdata_name, bankdata_bank, bankdata_blz, bankdata_number, bankdata_reason)
							VALUES ('$bankdata_name', '$bankdata_bank', '$bankdata_blz', '$bankdata_number', '$bankdata_reason')";
			}
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$message = $lang['update_cash_bankdata'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'update_cash_bankdata');
			message(GENERAL_MESSAGE, $message);
			
			break;
			
		case '_bankdata_delete':
		
			if ( $confirm )
			{	
				$sql = "TRUNCATE TABLE " . CASH_BANK;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				$message = $lang['delete_cash_bankdata'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'delete_cash_bankdata');
				message(GENERAL_MESSAGE, $message);
			
			}
			else if ( !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_bankdata_delete" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'	=> $lang['common_confirm'],
					'MESSAGE_TEXT'	=> $lang['confirm_delete_bankdata'],
					
					'S_FIELDS'		=> $s_fields,
					'S_ACTION'		=> append_sid('admin_cash.php'),					
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_cash']);
			}
			
			break;
			
		case '_user_delete':
		
			if ( $cashuser_id && $confirm )
			{	
			#	$cash_user = get_data('cash_user', $cashuser_id, 0);
			
				$sql = 'DELETE FROM ' . CASH_USERS . ' WHERE cash_user_id = ' . $cashuser_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['delete_cash_user'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'delete_cash_user');
				message(GENERAL_MESSAGE, $message);
			
			}
			else if ( $cashuser_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_user_delete" /><input type="hidden" name="' . POST_CASHUSER_URL . '" value="' . $cashuser_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_cash_user'],
					
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_cash.php'),
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_cash']);
			}
			
			break;
		
		case '_delete':
		
			if ( $cash_id && $confirm )
			{	
			#	$cash = get_data('cash', $cash_id, 0);
			
				$sql = 'DELETE FROM ' . CASH . ' WHERE cash_id = ' . $cash_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				$message = $lang['delete_cash'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CASH, 'delete_cash');
				message(GENERAL_MESSAGE, $message);
			
			}
			else if ( $cash_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_CASH_URL . '" value="' . $cash_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_cash'],
					
					'S_FIELDS'	=> $s_fields,	
					'S_ACTION'	=> append_sid('admin_cash.php'),					
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_cash']);
			}
			
			break;
		
		default:
			
			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('display', array());
			
			$cash_bank = get_data(CASH_BANK, '', 0);
			
			if ( $cash_bank )
			{
				$template->assign_block_vars('display.show_bank', array());
				
				$template->assign_vars(array(
					'NAME'		=> $cash_bank['bankdata_name'],
					'BANK'		=> $cash_bank['bankdata_bank'],
					'BLZ'		=> $cash_bank['bankdata_blz'],
					'NUMBER'	=> $cash_bank['bankdata_number'],
					'REASON'	=> $cash_bank['bankdata_reason'],
				));
			}
			
			$cash_data = get_data_array(CASH, '', 'cash_id', 'ASC');
			
			$total_amount = '';
			$total_amount_users = '';
			
			if ( $cash_data )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($cash_data)); $i++ )
				{
					$class		= ( $i % 2 ) ? 'row_class1' : 'row_class2';
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
						
						'U_EDIT'		=> append_sid('admin_cash.php?mode=_edit&amp;' . POST_CASH_URL . '=' . $cash_id),
						'U_DELETE'		=> append_sid('admin_cash.php?mode=_delete&amp;' . POST_CASH_URL . '=' . $cash_id)
					));
					
					$total_amount += $cash_amount;
				}
			}
			else
			{
				$template->assign_block_vars('display.no_entry', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			
			$sql = 'SELECT cu.*, u.username, u.user_color
						FROM ' . CASH_USERS . ' cu
							LEFT JOIN ' . USERS . ' u ON cu.user_id = u.user_id
						WHERE u.user_id <> ' . ANONYMOUS . '
					ORDER BY cu.user_id, cu.user_interval';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cash_users_data = $db->sql_fetchrowset($result);
			
			if ( $cash_users_data )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($cash_users_data)); $i++ )
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					$cashuser_id	= $cash_users_data[$i]['cash_user_id'];
					$month_cur		= date("m", time());
					$month_user		= explode(', ', $cash_users_data[$i]['user_month']);
					
					if ( $month_cur == $month_user || in_array($month_cur, $month_user) )
					{
						$total_amount_users += $cash_users_data[$i]['user_amount'];
					}
					else if ( !$cash_users_data[$i]['user_interval'] && in_array($month_cur, $month_user) )
					{
						$total_amount_users += $cash_users_data[$i]['user_amount'];
					}
					
					$user_interval = ( $cash_users_data[$i]['user_interval'] ) ? $lang['cash_user_interval_o'] : $lang['cash_user_interval_m'];
					
					$template->assign_block_vars('display.cash_users_row', array(
						'CLASS' 				=> $class,
						
						'CASHUSER_USERNAME'			=> $cash_users_data[$i]['username'],
						'CASHUSER_AMOUNT'		=> $cash_users_data[$i]['user_amount'],
						'CASHUSER_INTERVAL'	=> $user_interval,
						
						'U_EDIT'				=> append_sid('admin_cash.php?mode=_user_edit&amp;' . POST_CASHUSER_URL . '=' . $cashuser_id),
						'U_DELETE'				=> append_sid('admin_cash.php?mode=_user_delete&amp;' . POST_CASHUSER_URL . '=' . $cashuser_id)
					));
				}
			}
			else
			{
				$template->assign_block_vars('display.no_entry_users', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['cash']),
				'L_CREATE'	=> sprintf($lang['sprintf_creates'], $lang['cash']),
				'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['cash']),
				'L_EXPLAIN'	=> $lang['cash_explain'],
				
				'L_BD_INFO'			=> $lang['bankdata'],
				'L_BD_NAME'			=> $lang['bankdata_name'],
				'L_BD_BANK'			=> $lang['bankdata_bank'],
				'L_BD_BLZ'			=> $lang['bankdata_blz'],
				'L_BD_NUMBER'		=> $lang['bankdata_number'],
				'L_BD_REASON'		=> $lang['bankdata_reason'],
				'L_BD_DELETE'		=> $lang['bankdata_delete'],
				
				'L_NAME'		=> $lang['cash_name'],
				'L_BD'			=> $lang['cash_bankdata'],
				
				'L_CASHUSER_USERNAME'	=> $lang['username'],
				
				
				
				'L_ADD'		=> $lang['cash_add'],
				'L_CASHUSER_ADD'	=> $lang['cash_user_add'],
				
				'L_INTERVAL'	=> $lang['cash_interval'],
				'L_EDIT'			=> $lang['common_update'],
				'L_SETTINGS'		=> $lang['common_settings'],
				'L_DELETE'			=> $lang['common_delete'],
				
				'TOTAL_CASH'		=> $total_amount,
				'USER_CASH'			=> $total_amount_users,
				
				'S_CREATE'			=> append_sid('admin_games.php?mode=_create'),
				'S_USER_ADD'	=> select_box('user', 'selectsmall', 'user_id', 'username'),
				'S_BANKDATA'		=> append_sid('admin_cash.php?mode=_bankdata'),
				'S_ACTION'			=> append_sid('admin_cash.php'),
			));
			
			break;
	}

	$template->pparse('body');
	
	include('./page_footer_admin.php');
}
?>
<!-- 655 -->