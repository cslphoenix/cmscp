<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_cash'] )
	{
		$module['hm_main']['sm_cash'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
<<<<<<< .mine
	$current	= 'sm_cash';
=======
	$current	= 'sm_authlist';
>>>>>>> .r85
	
	include('./pagestart.php');
	
	load_lang('cash');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_CASH;
	$url	= POST_CASH;
	$url_u	= POST_CASH_USER;
	$file	= basename(__FILE__);
	
	$oCache -> sCachePath = $root_path . 'cache/';
	
	$data_id	= request($url, 0);
	$data_user	= request($url_u, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_cash.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	switch ( $mode )
	{
		case '_bankdata':
			
			$template->assign_block_vars('_bankdata', array());
			
			if ( !request('submit', 1) )
			{
				$data = data(CASH_BANK, '', false, 0, 1);
			}
			else
			{
				$data = array(
					'bank_holder'	=> request('bank_holder', 2),
					'bank_name'		=> request('bank_name', 2),
					'bank_blz'		=> request('bank_blz', 2),
					'bank_number'	=> request('bank_number', 2),
					'bank_reason'	=> request('bank_reason', 2),
				);
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['cash']),
				'L_BANKDATA'	=> $lang['cash_bank'],
				
				'L_HOLDER'		=> $lang['bank_holder'],
				'L_NAME'		=> $lang['bank_name'],
				'L_BLZ'			=> $lang['bank_blz'],
				'L_NUMBER'		=> $lang['bank_number'],
				'L_REASON'		=> $lang['bank_reason'],
				
				'HOLDER'		=> $data['bank_holder'],
				'NAME'			=> $data['bank_name'],
				'BLZ'			=> $data['bank_blz'],
				'NUMBER'		=> $data['bank_number'],
				'REASON'		=> $data['bank_reason'],

				'S_ACTION'		=> check_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			if ( request('submit', 1) )
			{
				$bank_holder	= request('bank_holder', 2);
				$bank_name		= request('bank_name', 2);
				$bank_blz		= request('bank_blz', 2);
				$bank_number	= request('bank_number', 2);
				$bank_reason	= request('bank_reason', 2);
				
				$error .= ( !$bank_holder )	? ( $error ? '<br />' : '' ) . $lang['msg_select_holder'] : '';
				$error .= ( !$bank_name )	? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
				$error .= ( !$bank_blz )	? ( $error ? '<br />' : '' ) . $lang['msg_select_blz'] : '';
				$error .= ( !$bank_number )	? ( $error ? '<br />' : '' ) . $lang['msg_select_number'] : '';
				$error .= ( !$bank_reason )	? ( $error ? '<br />' : '' ) . $lang['msg_select_reason'] : '';
				
				if ( !$error )
				{
					$data = data(CASH_BANK, '', false, 0, 1);
					
					if ( $data )
					{			
						$sql = "UPDATE " . CASH_BANK . " SET
									bank_holder	= '$bank_holder',
									bank_name	= '$bank_name',
									bank_blz	= '$bank_blz',
									bank_number	= '$bank_number',
									bank_reason = '$bank_reason'";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['update_bank'];
					}
					else
					{			
						$sql = "INSERT INTO " . CASH_BANK . " (bank_holder, bank_name, bank_blz, bank_number, bank_reason)
									VALUES ('$bank_holder', '$bank_name', '$bank_blz', '$bank_number', '$bank_reason')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create_bank'];
					}
					
					$message .= sprintf($lang['return'], check_sid($file), $acp_title);
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					log_add(LOG_ADMIN, $log, 'error', $error);
					
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
			
		case '_bank_delete':
			
			if ( $confirm )
			{	
				$sql = "TRUNCATE TABLE " . CASH_BANK;
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				$message = $lang['delete_bank'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $message);
			
			}
			else if ( !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> $lang['confirm'],

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			
			break;
			
		case '_create':
		case '_update':
		
			$template->assign_block_vars('_input_user', array());
			
			if ( $mode == '_create' && !request('submit', 1) )
			{
				$data = array(
							'user_id'		=> request('user_id', 0),
							'user_amount'	=> '0',
							'user_month'	=> date("m", time()),
							'user_interval'	=> '1',
						);
			}
			else if ( $mode == '_update' && !request('submit', 1) )
			{
				$data = data(CASH_USER, $data_user, false, 1, 1);
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
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url_u\" value=\"$data_user\" />";

			$template->assign_vars(array(
				'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['cash']),
				'L_INPUT'			=> sprintf($lang['sprintf' . str_replace('_user', '', $mode)], $lang['user'], $data['user_id']),
				'L_USER'			=> $lang['user'],
				'L_USER_AMOUNT'		=> $lang['amount'],
				'L_USER_MONTH'		=> $lang['month'],
				'L_USER_INTERVAL'	=> $lang['interval'],
				'L_INTAVAL_MONTH'	=> $lang['interval_month'],
				'L_INTAVAL_ONLY'	=> $lang['interval_only'],
				
				'AMOUNT'			=> $data['user_amount'],

				'S_USER'			=> select_box('user', 'select', $data['user_id']),
				'S_MONTH'			=> select_date('select', 'monthm', 'user_month', $data['user_month']),
			
				'S_INTAVAL_MONTH'	=> ( $data['user_interval'] == '0' ) ? ' checked="checked"' : '',
				'S_INTAVAL_ONLY'	=> ( $data['user_interval'] == '1' ) ? ' checked="checked"' : '',

				'S_ACTION'			=> check_sid($file),
				'S_FIELDS'			=> $fields,
			));
			
			if ( request('submit', 1) )
			{
				$user_id		= request('user_id', 0);
				$user_amount	= request('user_amount', 0);
				$user_interval	= request('user_interval', 0);
				$user_month		= ( request('user_month', 4) ) ? implode(', ', request('user_month', 4)) : '';
				
				$error .= ( $user_id <= 0 ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_user'] : '';
				$error .= ( !$user_amount ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_amount'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$sql = "INSERT INTO " . CASH_USER . " (user_id, user_amount, user_month, user_interval)
									VALUES ('$user_id', '$user_amount', '$user_month', '$user_interval')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$msg = $lang['create_user'] . sprintf($lang['return'], check_sid($file), $acp_title);
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
						
						$msg = $lang['update_user'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url_u=$data_user"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $user_id);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					log_add(LOG_ADMIN, $log, 'error', $error);
					
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
		
		case '_delete_user':
		
			$data = data(CASH_USER, $data_user, false, 2, 1);
			
			if ( $data_user && $confirm )
			{
				$sql = "DELETE FROM " . CASH_USER . " WHERE cash_user_id = $data_user";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['delete_user'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				log_add(LOG_ADMIN, $log, $mode, $data['user_name']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_user && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= '<input type="hidden" name="' . $url_u . '" value="' . $data_user . '" />';
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm_user'], $data['user_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else { message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['user'])); }
			
			break;
			
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('_input', array());
			
			if ( $mode == '_create' && !request('submit', 1) )
			{
				$data = array(
							'cash_name'		=> request('cash_name', 2),
							'cash_type'		=> '0',
							'cash_amount'	=> '0',
							'cash_interval'	=> '0',
						);
			}
			else if ( $mode == '_update' && !request('submit', 1) )
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
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

			$template->assign_vars(array(
				'L_HEAD'			=> $acp_title,
				'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['cash_reason'], $data['cash_name']),
				'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['cash_reason']),
				'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['cash_reason']),
				'L_AMOUNT'			=> $lang['amount'],
				'L_INTERVAL'		=> $lang['interval'],
				'L_TYPE_GAME'		=> $lang['type_game'],
				'L_TYPE_VOICE'		=> $lang['type_voice'],
				'L_TYPE_OTHER'		=> $lang['type_other'],
				'L_INTAVAL_MONTH'	=> $lang['interval_month'],
				'L_INTAVAL_WEEKS'	=> $lang['interval_weeks'],
				'L_INTAVAL_WEEKLY'	=> $lang['interval_weekly'],
				
				'NAME'				=> $data['cash_name'],
				'AMOUNT'			=> $data['cash_amount'],
				
				'S_TYPE_GAME'		=> ( $data['cash_type'] == TYPE_GAME ) ? ' checked="checked"' : '',
				'S_TYPE_VOICE'		=> ( $data['cash_type'] == TYPE_VOICE ) ? ' checked="checked"' : '',
				'S_TYPE_OTHER'		=> ( $data['cash_type'] == TYPE_OTHER ) ? ' checked="checked"' : '',
				'S_INT_MONTH'		=> ( $data['cash_interval'] == INTERVAL_MONTH ) ? ' checked="checked"' : '',
				'S_INT_WEEKS'		=> ( $data['cash_interval'] == INTERVAL_WEEKS ) ? ' checked="checked"' : '',
				'S_INT_WEEKLY'		=> ( $data['cash_interval'] == INTERVAL_WEEKLY ) ? ' checked="checked"' : '',

				'S_ACTION'			=> check_sid($file),
				'S_FIELDS'			=> $fields,
			));
			
			if ( request('submit', 1) )
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
						$sql = "INSERT INTO " . CASH . " (cash_name, cash_type, cash_amount, cash_interval) VALUES ('$cash_name', '$cash_type', '$cash_amount', '$cash_interval')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
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
							. sprintf($lang['return'], check_sid($file), $acp_title)
							. sprintf($lang['return_update'], '<a href="' . check_sid("$file?mode=$mode&amp;$url=$data_id"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $cash_name);
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					log_add(LOG_ADMIN, $log, 'error', $error);
					
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
		
		case '_delete':
		
			$data = data(CASH, $data_id, false, 1, 1);
		
			if ( $data_id && $confirm )
			{	
				$sql = "DELETE FROM " . CASH . " WHERE cash_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				$message = $lang['delete_cash'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				log_add(LOG_ADMIN, $log, $mode, $data['cash_name']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['delete_confirm_cash'], $data['cash_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else { message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['cash'])); }

			break;
		
		default:
			
			$template->set_filenames(array('body' => 'style/acp_cash.tpl'));
			$template->assign_block_vars('_display', array());
			
			$postage_cash = '';
			$postage_cashuser = '';
			
			$cash = data(CASH, false, true, 0, false);
			$bank = data(CASH_BANK, false, false, 0, true);
			
			$sql = "SELECT cu.*, u.user_name, u.user_color
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
					'HOLDER'	=> $bank['bank_holder'],
					'NAME'		=> $bank['bank_name'],
					'BLZ'		=> $bank['bank_blz'],
					'NUMBER'	=> $bank['bank_number'],
					'REASON'	=> $bank['bank_reason'],
				));
			}

			if ( $cash )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($cash)); $i++ )
				{
					$cash_id	= $cash[$i]['cash_id'];
					$cash_type	= $cash[$i]['cash_type'];
					
					if ( $cash[$i]['cash_interval'] == '0' )
					{
						$cash_amount	= $cash[$i]['cash_amount'];
						$cash_interval	= $lang['interval_month'];
					}
					else if ( $cash[$i]['cash_interval'] == '1' )
					{
						$cash_amount	= 2 * str_replace(',', '.', $cash[$i]['cash_amount']);
						$cash_interval	= $lang['interval_weeks'];
					}
					else if ( $cash[$i]['cash_interval'] == '2' )
					{
						$cash_amount	= 4 * str_replace(',', '.', $cash[$i]['cash_amount']);
						$cash_interval	= $lang['interval_weekly'];
					}
					
					$template->assign_block_vars('_display._cash_row', array(
						'NAME'		=> $cash[$i]['cash_name'],
						'TYPE'		=> ($cash_type != '0') ? ( ($cash_type == '1') ? $images['sound'] : $images['other'] ) : $images['match'],
						'AMOUNT'	=> $cash[$i]['cash_amount'],
						'DATE'		=> $cash_interval,
						
						'U_UPDATE'	=> check_sid("$file?mode=_update&amp;$url=$cash_id"),
						'U_DELETE'	=> check_sid("$file?mode=_delete&amp;$url=$cash_id"),
					));
					
					$postage_cash += $cash_amount;
				}
			}
			else
			{
				$template->assign_block_vars('_display._entry_empty', array());
			}
			
			if ( $user )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($user)); $i++ )
				{
					$user_id	= $user[$i]['cash_user_id'];
					$month_cur	= date("m", time());
					$month_user	= explode(', ', $user[$i]['user_month']);
					
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
						'USERNAME'	=> $user[$i]['user_name'],
						'MONTH'		=> $user[$i]['user_month'],
						'AMOUNT'	=> $user[$i]['user_amount'],
						'INTERVAL'	=> $user_interval,
						
						'U_UPDATE'	=> check_sid("$file?mode=_update&amp;$url_u=$user_id"),
						'U_DELETE'	=> check_sid("$file?mode=_delete_user&amp;$url_u=$user_id"),
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
				'L_HEAD'		=> $acp_title,
				'L_CREATE'		=> sprintf($lang['sprintf_new_create'], $lang['cash_reason']),
				'L_CREATE_USER'	=> sprintf($lang['sprintf_new_creates'], $lang['cash_user']),
				'L_CREATE_BANK'	=> $lang['cash_bank'],
				'L_EXPLAIN'		=> $lang['explain'],
				'L_CASH'		=> $lang['cash'],
				'L_REASON'		=> $lang['cash_reason'],
				'L_NAME'		=> $lang['cash_name'],
				'L_USERNAME'	=> $lang['user_name'],
				'L_INTERVAL'	=> $lang['interval'],
				'L_POSTAGE'		=> $lang['postage'],
				'L_BD'			=> $lang['cash_bankdata'],
				'L_HOLDER'		=> $lang['bank_holder'],
				'L_NAME'		=> $lang['bank_name'],
				'L_BLZ'			=> $lang['bank_blz'],
				'L_NUMBER'		=> $lang['bank_number'],
				'L_REASON'		=> $lang['bank_reason'],
				'L_DELETE'		=> $lang['bank_delete'],
				
				'POSTAGE'			=> $postage,
				'POSTAGE_CASH'		=> $postage_cash,
				'POSTAGE_CASHUSER'	=> $postage_cashuser,
				'POSTAGE_CLASS'		=> $postage_class,
				
				'S_CREATE_USER_BOX'	=> select_box('user', 'selectsmall', 'user_id', 'user_name'),
				'S_BANKDATA'		=> check_sid("$file?mode=_bankdata"),				
				'S_CREATE_USER'		=> check_sid("$file?mode=_create"),
				'S_CREATE'			=> check_sid("$file?mode=_create"),
				'S_ACTION'			=> check_sid($file),
			));
			
			break;
	}

	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>