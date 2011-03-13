<?php

/*
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
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 *	Clankasse
 */

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_cash'] )
	{
		$module['_headmenu_main']['_submenu_cash'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$s_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_cash';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('cash');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_CASH_URL, 0);
	$data_user	= request(POST_CASH_USER_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_cash'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_CASH, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $s_header ) ? redirect('admin/' . append_sid('admin_cash.php', true)) : false;
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('_input', array());
			
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
				$data = data(CASH, $data_id, false, 1, 1);
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
			
			$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
			$s_fields .= '<input type="hidden" name="' . POST_CASH_URL . '" value="' . $data_id . '" />';

			$template->assign_vars(array(
				'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['cash']),
				'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['cash'], $data['cash_name']),
				'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['cash']),
				'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['cash']),
				'L_AMOUNT'			=> $lang['cash_amount'],
				'L_INTERVAL'		=> $lang['cash_interval'],
				'L_TYPE_GAME'		=> $lang['type_game'],
				'L_TYPE_VOICE'		=> $lang['type_voice'],
				'L_TYPE_OTHER'		=> $lang['type_other'],
				'L_INTAVAL_MONTH'	=> $lang['cash_interval_month'],
				'L_INTAVAL_WEEKS'	=> $lang['cash_interval_weeks'],
				'L_INTAVAL_WEEKLY'	=> $lang['cash_interval_weekly'],
				
				'NAME'				=> $data['cash_name'],
				'AMOUNT'			=> $data['cash_amount'],
				
				'S_TYPE_GAME'		=> ( $data['cash_type'] == TYPE_GAME ) ? ' checked="checked"' : '',
				'S_TYPE_VOICE'		=> ( $data['cash_type'] == TYPE_VOICE ) ? ' checked="checked"' : '',
				'S_TYPE_OTHER'		=> ( $data['cash_type'] == TYPE_OTHER ) ? ' checked="checked"' : '',
				'S_INT_MONTH'		=> ( $data['cash_interval'] == INTERVAL_MONTH ) ? ' checked="checked"' : '',
				'S_INT_WEEKS'		=> ( $data['cash_interval'] == INTERVAL_WEEKS ) ? ' checked="checked"' : '',
				'S_INT_WEEKLY'		=> ( $data['cash_interval'] == INTERVAL_WEEKLY ) ? ' checked="checked"' : '',

				'S_ACTION'			=> append_sid('admin_cash.php'),
				'S_FIELDS'			=> $s_fields,
			));
			
			if ( request('submit', 2) )
			{
				$cash_name		= request('cash_name', 2);
				$cash_type		= request('cash_type', 0);
				$cash_amount	= request('cash_amount', 2);
				$cash_interval	= request('cash_interval', 0);
				
				$error .= ( !$cash_name )	? ( $error ? '<br />' : '' ) . sprintf($lang['sprintf_msg_select'], sprintf($lang['sprintf_name'], $lang['cash'])) : '';
				$error .= ( !$cash_amount )	? ( $error ? '<br />' : '' ) . $lang['msg_select_amount'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$sql = "INSERT INTO " . CASH . " (cash_name, cash_type, cash_amount, cash_interval)
									VALUES ('$cash_name', '$cash_type', '$cash_amount', '$cash_interval')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create_cash'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
					}
					else
					{
						$sql = "UPDATE " . CASH . " SET
									cash_name		= '$cash_name',
									cash_type		= '$cash_type',
									cash_amount		= '$cash_amount',
									cash_interval	= '$cash_interval'
								WHERE cash_id = $data_id";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['update_cash']
							. sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>')
							. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_cash.php?mode=_update&amp;' . POST_CASH_URL . '=' . $data_id) . '">', '</a>');
					}
					
					log_add(LOG_ADMIN, LOG_SEK_CASH, $mode, $cash_name);
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
		
		case '_delete':
		
			$data = get_data(CASH, $data_id, 0);
		
			if ( $data_id && $confirm )
			{	
				$sql = "DELETE FROM " . CASH . " WHERE cash_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				$message = $lang['delete_cash'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
				
				log_add(LOG_ADMIN, LOG_SEK_CASH, $mode, $data['cash_name']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields .= '<input type="hidden" name="mode" value="_delete" />';
				$s_fields .= '<input type="hidden" name="' . POST_CASH_URL . '" value="' . $data_id . '" />';
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_cash'], $data['cash_name']),
					
					'S_ACTION'	=> append_sid('admin_cash.php'),
					'S_FIELDS'	=> $s_fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['cash']));
			}

			break;
			
		case '_create_user':
		case '_update_user':
		
			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('_input_user', array());
			
			if ( $mode == '_create_user' && !request('submit', 2) )
			{
				$data = array(
					'user_id'		=> request('user_id', 0),
					'user_amount'	=> '0',
					'user_month'	=> date("m", time()),
					'user_interval'	=> '1',
				);
			}
			else if ( $mode == '_update_user' && !request('submit', 2) )
			{
				$data = get_data(CASH_USER, $data_user, 1);
			}
			else
			{
				$data = array(
					'user_id'		=> request('user_id', 0),
					'user_amount'	=> request('user_amount', 2),
					'user_month'	=> request('user_month', 1),
					'user_interval'	=> request('user_interval', 2),
				);
			}
			
			$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
			$s_fields .= '<input type="hidden" name="' . POST_CASH_USER_URL . '" value="' . $data_user . '" />';

			$template->assign_vars(array(
				'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['cash']),
				'L_INPUT'			=> sprintf($lang['sprintf' . str_replace('_user', '', $mode)], $lang['user'], $data['user_id']),
				'L_USER'			=> $lang['user'],
				'L_USER_AMOUNT'		=> $lang['cash_amount'],
				'L_USER_MONTH'		=> $lang['cash_user_month'],
				'L_USER_INTERVAL'	=> $lang['cash_interval'],
				'L_INTAVAL_MONTH'	=> $lang['interval_month'],
				'L_INTAVAL_ONLY'	=> $lang['interval_only'],
				
				'AMOUNT'			=> $data['user_amount'],

				'S_USER'			=> select_box('user', 'select', $data['user_id']),
				'S_MONTH'			=> select_date('select', 'monthm', 'user_month', $data['user_month']),
			
				'S_INTAVAL_MONTH'	=> ( $data['user_interval'] == '0' ) ? ' checked="checked"' : '',
				'S_INTAVAL_ONLY'	=> ( $data['user_interval'] == '1' ) ? ' checked="checked"' : '',

				'S_ACTION'			=> append_sid('admin_cash.php'),
				'S_FIELDS'			=> $s_fields,
			));
			
			if ( request('submit', 2) )
			{
				$user_id		= request('user_id', 0);
				$user_month		= ( request('user_month', 4) ) ? implode(', ', request('user_month', 4)) : '';
				$user_amount	= request('user_amount', 0);
				$user_interval	= request('user_interval', 0);
				
				$error .= ( !$user_id )		? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_user'] : '';
				$error .= ( !$user_amount )	? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_amount'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create_user' )
					{
						$sql = "INSERT INTO " . CASH_USER . " (user_id, user_amount, user_month, user_interval)
									VALUES ('$user_id', '$user_amount', '$user_month', '$user_interval')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create_cashuser'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
					}
					else
					{
						$sql = "UPDATE " . CASH_USER . " SET
									user_id			= $user_id,
									user_amount		= $user_amount,
									user_month		= '$user_month',
									user_interval	= $user_interval
								WHERE cash_user_id = $data_user";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['update_cashuser']
							. sprintf($lang['click_return_cashuser'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>')
							. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_cash.php?mode=_update_user&amp;' . POST_CASH_USER_URL . '=' . $data_user) . '">', '</a>');
					}
					
					log_add(LOG_ADMIN, LOG_SEK_CASH, $mode, $user_id);
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
		
		case '_delete_user':
		
			$data = get_data(CASH_USER, $data_user, 2);
			
			if ( $data_user && $confirm )
			{
				$sql = "DELETE FROM " . CASH_USER . " WHERE cash_user_id = $data_user";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['delete_cash_user'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
				
				log_add(LOG_ADMIN, LOG_SEK_CASH, $mode, $data['username']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_user && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
				
				$s_fields .= '<input type="hidden" name="mode" value="_user_delete" />';
				$s_fields .= '<input type="hidden" name="' . POST_CASH_USER_URL . '" value="' . $data_user . '" />';
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_cash_user'], $data['username']),

					'S_ACTION'	=> append_sid('admin_cash.php'),
					'S_FIELDS'	=> $s_fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['user']));
			}
			
			break;
		
		case '_bankdata':
			
			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('_bankdata', array());
			
			if ( !request('submit', 2) )
			{
				$data = get_data(CASH_BANK, '', 0);
			}
			else
			{
				$data = array(
					'bankdata_name'		=> request('bankdata_name', 2),
					'bankdata_bank'		=> request('bankdata_bank', 2),
					'bankdata_blz'		=> request('bankdata_blz', 2),
					'bankdata_number'	=> request('bankdata_number', 2),
					'bankdata_reason'	=> request('bankdata_reason', 2),
				);
			}
			
			$s_fields .= '<input type="hidden" name="mode" value="_bankdata" />';

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['cash']),
				'L_BANKDATA'	=> sprintf($lang['sprintf_processing'], $lang['bankdata']),
				'L_NAME'		=> $lang['cash_bd_name'],
				'L_BANK'		=> $lang['cash_bd_bank'],
				'L_BLZ'			=> $lang['cash_bd_blz'],
				'L_NUMBER'		=> $lang['cash_bd_number'],
				'L_REASON'		=> $lang['cash_bd_reason'],
				
				'NAME'			=> $data['bankdata_name'],
				'BANK'			=> $data['bankdata_bank'],
				'BLZ'			=> $data['bankdata_blz'],
				'NUMBER'		=> $data['bankdata_number'],
				'REASON'		=> $data['bankdata_reason'],

				'S_ACTION'		=> append_sid('admin_cash.php'),
				'S_FIELDS'		=> $s_fields,
			));
			
			if ( request('submit', 2) )
			{
				$bankdata_name		= request('bankdata_name', 2);
				$bankdata_bank		= request('bankdata_bank', 2);
				$bankdata_blz		= request('bankdata_blz', 2);
				$bankdata_number	= request('bankdata_number', 2);
				$bankdata_reason	= request('bankdata_reason', 2);
			
				$error .= ( !$bankdata_name )	? ( $error ? '<br>' : '' ) . $lang['msg_select_name'] : '';
				$error .= ( !$bankdata_bank )	? ( $error ? '<br>' : '' ) . $lang['msg_select_bank'] : '';
				$error .= ( !$bankdata_blz )	? ( $error ? '<br>' : '' ) . $lang['msg_select_blz'] : '';
				$error .= ( !$bankdata_number )	? ( $error ? '<br>' : '' ) . $lang['msg_select_number'] : '';
				$error .= ( !$bankdata_reason )	? ( $error ? '<br>' : '' ) . $lang['msg_select_reason'] : '';
				
				if ( !$error )
				{
					$data = get_data(CASH_BANK, '', 0);
					
					if ( $data )
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

					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['update_cash_bankdata'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
					
					log_add(LOG_ADMIN, LOG_SEK_CASH, $mode);
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
			
		case '_bankdata_delete':
			
			if ( $confirm )
			{	
				$sql = "TRUNCATE TABLE " . CASH_BANK;
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				$message = $lang['delete_cash_bankdata'] . sprintf($lang['click_return_cash'], '<a href="' . append_sid('admin_cash.php') . '">', '</a>');
				
				log_add(LOG_ADMIN, LOG_SEK_CASH, $mode);
				message(GENERAL_MESSAGE, $message);
			
			}
			else if ( !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields .= '<input type="hidden" name="mode" value="_bankdata_delete" />';
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> $lang['delete_confirm_bankdata'],

					'S_ACTION'	=> append_sid('admin_cash.php'),
					'S_FIELDS'	=> $s_fields,
				));
			}
			
			break;

		default:
			
			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('_display', array());
			
			$postage_cash = '';
			$postage_cashuser = '';
			
			$cash = data(CASH, false, true, 0, false);
			$bank = data(CASH_BANK, false, false, 0, true);
			
			$sql = "SELECT cu.*, u.username, u.user_color
						FROM " . CASH_USER . " cu
							LEFT JOIN " . USERS . " u ON cu.user_id = u.user_id
						WHERE u.user_id <> " . ANONYMOUS . "
					ORDER BY cu.user_id, cu.user_interval";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$user = $db->sql_fetchrowset($result);
			
			if ( $bank )
			{
				$template->assign_block_vars('_display._bank', array());
				
				$template->assign_vars(array(
					'NAME'		=> $bank['bankdata_name'],
					'BANK'		=> $bank['bankdata_bank'],
					'BLZ'		=> $bank['bankdata_blz'],
					'NUMBER'	=> $bank['bankdata_number'],
					'REASON'	=> $bank['bankdata_reason'],
				));
			}

			if ( $cash )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($cash)); $i++ )
				{
					$cash_type	= $cash[$i]['cash_type'];
					
					if ( $cash[$i]['cash_interval'] == '0' )
					{
						$cash_amount	= $cash[$i]['cash_amount'];
						$cash_interval	= $lang['cash_interval_month'];
					}
					else if ( $cash[$i]['cash_interval'] == '1' )
					{
						$cash_amount	= 2 * str_replace(',', '.', $cash[$i]['cash_amount']);
						$cash_interval	= $lang['cash_interval_weeks'];
					}
					else if ( $cash[$i]['cash_interval'] == '2' )
					{
						$cash_amount	= 4 * str_replace(',', '.', $cash[$i]['cash_amount']);
						$cash_interval	= $lang['cash_interval_weekly'];
					}
					
					$template->assign_block_vars('_display._cash_row', array(
						'NAME'		=> $cash[$i]['cash_name'],
						'TYPE'		=> ( $cash_type != '0' ) ? ( $cash_type == '1' ? $images['sound'] : $images['other'] ) : $images['match'],
						'AMOUNT'	=> $cash[$i]['cash_amount'],
						'DATE'		=> $cash_interval,
						
						'U_UPDATE'	=> append_sid('admin_cash.php?mode=_update&amp;' . POST_CASH_URL . '=' . $cash[$i]['cash_id']),
						'U_DELETE'	=> append_sid('admin_cash.php?mode=_delete&amp;' . POST_CASH_URL . '=' . $cash[$i]['cash_id']),
					));
					
					$postage_cash += $cash_amount;
				}
			}
			else
			{
				$template->assign_block_vars('_display._no_entry', array());
			}
			
			if ( $user )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($user)); $i++ )
				{
					$user_id	= $user[$i]['cash_user_id'];
					$month_cur		= date("m", time());
					$month_user		= explode(', ', $user[$i]['user_month']);
					
					if ( $month_cur == $month_user || in_array($month_cur, $month_user) )
					{
						$postage_cashuser += $user[$i]['user_amount'];
					}
					else if ( !$user[$i]['user_interval'] && in_array($month_cur, $month_user) )
					{
						$postage_cashuser += $user[$i]['user_amount'];
					}
					
					$user_interval = ( $user[$i]['user_interval'] ) ? $lang['interval_only'] : $lang['interval_month'];
					
					$template->assign_block_vars('_display._cashuser_row', array(
						'USERNAME'	=> $user[$i]['username'],
						'AMOUNT'	=> $user[$i]['user_amount'],
						'INTERVAL'	=> $user_interval,
						
						'U_UPDATE'	=> append_sid('admin_cash.php?mode=_update_user&amp;' . POST_CASH_USER_URL . '=' . $user[$i]['cash_user_id']),
						'U_DELETE'	=> append_sid('admin_cash.php?mode=_delete_user&amp;' . POST_CASH_USER_URL . '=' . $user[$i]['cash_user_id']),
					));
				}
			}
			else
			{
				$template->assign_block_vars('_display._no_entry_user', array());
			}
			
			$postage		= $postage_cashuser - $postage_cash;
			$postage_class	= ( $postage < 0 ) ? ( $postage > 0 ) ? 'draw' : 'lose' : 'win';
			
			$template->assign_vars(array(
				'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['cash']),
				'L_CREATE'			=> sprintf($lang['sprintf_new_creates'], $lang['cash']),
				'L_CREATE_USER'		=> sprintf($lang['sprintf_new_creates'], $lang['cash_user']),
				'L_NAME'			=> $lang['cash_name'],
				'L_USERNAME'		=> $lang['username'],
				'L_INTERVAL'		=> $lang['cash_interval'],
				'L_POSTAGE'			=> $lang['postage'],
				'L_EXPLAIN'			=> $lang['cash_explain'],
				'L_BD'				=> $lang['cash_bankdata'],
				'L_BD_INFO'			=> $lang['bankdata'],
				'L_BD_NAME'			=> $lang['bankdata_name'],
				'L_BD_BANK'			=> $lang['bankdata_bank'],
				'L_BD_BLZ'			=> $lang['bankdata_blz'],
				'L_BD_NUMBER'		=> $lang['bankdata_number'],
				'L_BD_REASON'		=> $lang['bankdata_reason'],
				'L_BD_DELETE'		=> $lang['bankdata_delete'],
				
				'POSTAGE'			=> $postage,
				'POSTAGE_CASH'		=> $postage_cash,
				'POSTAGE_CASHUSER'	=> $postage_cashuser,
				'POSTAGE_CLASS'		=> $postage_class,
				
				'S_BANKDATA'		=> append_sid('admin_cash.php?mode=_bankdata'),
				'S_CREATE_USER_BOX'	=> select_box('user', 'selectsmall', 'user_id', 'username'),
				'S_CREATE_USER'		=> append_sid('admin_cash.php?mode=_create_user'),
				'S_CREATE'			=> append_sid('admin_cash.php?mode=_create'),
				'S_ACTION'			=> append_sid('admin_cash.php'),
			));
			
			break;
	}

	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>