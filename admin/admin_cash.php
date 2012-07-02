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
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_cash';
	
	include('./pagestart.php');
	
	add_lang('cash');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_CASH;
	$url	= POST_CASH;
	$urlu	= POST_CASH_USER;
	$file	= basename(__FILE__);
	$time	= time();
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_user	= request($urlu, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_cash'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_cash.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'delete', 'create_cat', 'update_cat', 'delete_cat', 'bankdata', 'bankdata_delete')) ) ? $mode : '';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'user' => array(
					'title'			=> 'input_data',
					'user_id'		=> array('validate' => 'int',	'type' => 'drop:user',		'explain' => true),
					'user_amount'	=> array('validate' => 'int',	'type' => 'text:5:5',		'explain' => true),
					'user_month'	=> array('validate' => 'text',	'type' => 'check:months',	'explain' => true),
					'user_interval'	=> array('validate' => 'int',	'type' => 'radio:interval',	'explain' => true),
					'time_create'	=> 'hidden',
					'time_update'	=> 'hidden',
				),
			);
			
			if ( $mode == 'create' && !request('submit', TXT) )
			{
				$data = array(
					'user_id'		=> request('user_id', INT),
					'user_amount'	=> 0,
					'user_month'	=> date("m", $time),
					'user_interval'	=> 1,
					'time_create'	=> $time,
					'time_update'	=> $time,
				);
			}
			else if ( $mode == 'update' && !request('submit', TXT) )
			{
				$data = data(CASH_USER, $data_user, false, 2, true);
			}
			else
			{
				$temp = data(CASH_USER, $data_id, false, 1, true);
				$temp = array_keys($temp);
				unset($temp[0]);
				
				$data = build_request($temp, $vars, 'user', $error);
				/*
				$data = array(
					'user_id'		=> request('user_id', INT),
					'user_amount'	=> request('user_amount', CLN),
					'user_month'	=> request('user_month', 4) ? implode(', ', request('user_month', 4)) : '',
					'user_interval'	=> request('user_interval', INT),
					'time_create'	=> request('time_create', INT),
					'time_update'	=> request('time_update', INT),
				);
				
			#	$user_id		= request('user_id', INT);
			#	$user_amount	= request('user_amount', INT);
			#	$user_interval	= request('user_interval', INT);
			#	$user_month		= ( request('user_month', 4) ) ? implode(', ', request('user_month', 4)) : '';
				
				$error .= ( $data['user_id'] <= 0 ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_users'] : '';
				$error .= ( !$data['user_amount'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_amount'] : '';
				*/
				if ( !$error )
				{
					if ( $mode == 'create' )
					{
				#		$sql = sql(CASH_USER, $mode, $data);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
				#		$sql = sql(CASH_USER, $mode, $data, 'cash_user_id', $data_user);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$urlu=$data_user"));
					}
					
				#	log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
					
					log_add(LOG_ADMIN, $log, 'error', $error);
				}
			}
			
			build_output($data, $vars, 'input', false, CASH_USER);
			
		#	$data['user_id'] = ( !is_numeric($data['user_id']) ) ? ( $data['user_id'] != '' ) ? search_user('id', $data['user_id']) : '' : $data['user_id'];
			
		#	$monate = array(
		#		'1'		=> $lang['datetime']['month_01'],
		#		'2'		=> $lang['datetime']['month_02'],
		#		'3'		=> $lang['datetime']['month_03'],
		#		'4'		=> $lang['datetime']['month_04'],
		#		'5'		=> $lang['datetime']['month_05'],
		#		'6'		=> $lang['datetime']['month_06'],
		#		'7'		=> $lang['datetime']['month_07'],
		#		'8'		=> $lang['datetime']['month_08'],
		#		'9'		=> $lang['datetime']['month_09'],
		#		'10'	=> $lang['datetime']['month_10'],
		#		'11'	=> $lang['datetime']['month_11'],
		#		'12'	=> $lang['datetime']['month_12'],
		#	);
		#	
		#	$switch = '';
		#	$check = '';
		#	
		#	for ( $i = 1; $i < 13; $i++ )
		#	{
		#		$i = ( $i < 10 ) ? '0' . $i : $i;
		#		
		#		$active = 'disabled="disabled"';
		#		$check = '';
		#		
		#		if ( in_array($i, explode(', ', $data['user_month'])) )
		#		{
		#			$active = '';
		#			$check = 'checked="checked"';
		#		}
				
			#	$switch[$i] = "<label><input type=\"radio\" name=\"$i\" id=\"$i\" value=\"1\" $active>&nbsp;" . $lang['cash_received'] . "</label><span style=\"padding:4px;\"></span>";
			#	$switch[$i] .= "<label><input type=\"radio\" name=\"$i\" id=\"$i\" value=\"0\" $active $checked>&nbsp;" . $lang['cast_notreceived'] . "</label>";
				
			#	$template->assign_block_vars("input.month", array(
			#		'CHECK'	=> $check,
			#		'SWITCH'=> $switch[$i],
			#		'NUM'	=> $i,
			#		'MONTH'	=> $monate[$i],
			#	));
				
		#	}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$urlu\" value=\"$data_user\" />";
			
			$fields .= "<input type=\"hidden\" name=\"time_create\" value=\"{$data['time_create']}\" />";
			$fields .= "<input type=\"hidden\" name=\"time_update\" value=\"{$data['time_update']}\" />";
			
			$template->assign_vars(array(
				'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_INPUT'			=> sprintf($lang["sprintf_{$mode}"], $lang['user'], $data['user_name']),
			#	'L_USER'			=> $lang['user'],
			#	'L_USER_AMOUNT'		=> $lang['amount'],
			#	'L_USER_MONTH'		=> $lang['month'],
			#	'L_USER_INTERVAL'	=> $lang['interval'],
			#	'L_INTAVAL_MONTH'	=> $lang['interval_month'],
			#	'L_INTAVAL_ONLY'	=> $lang['interval_only'],
				
			#	'AMOUNT'			=> $data['user_amount'],

			#	'S_USER'			=> select_box(USERS, $data['user_id'], TRIAL),
			#	'S_MONTH'			=> select_date('select', 'monthm', 'user_month', $data['user_month']),
			
			#	'S_INTAVAL_MONTH'	=> ( $data['user_interval'] == '0' ) ? ' checked="checked"' : '',
			#	'S_INTAVAL_ONLY'	=> ( $data['user_interval'] == '1' ) ? ' checked="checked"' : '',

				'S_ACTION'			=> check_sid($file),
				'S_FIELDS'			=> $fields,
			));
			
			break;
					
		case 'delete_user':
		
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
			else { message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['user'])); }
			
			break;
			
		case 'create_cat':
		case 'update_cat':
		
			$template->assign_block_vars('input_cat', array());
			
			if ( $mode == 'create' && !request('submit', TXT) )
			{
				$data = array(
					'cash_name'		=> request('cash_name', CLN),
					'cash_type'		=> '0',
					'cash_amount'	=> '0',
					'cash_interval'	=> '0',
				);
			}
			else if ( $mode == 'update' && !request('submit', TXT) )
			{
				$data = data(CASH, $data_id, false, 1, 1);
			}
			else
			{
				$data = array(
					'cash_name'		=> request('cash_name', CLN),
					'cash_type'		=> request('cash_type', INT),
					'cash_amount'	=> request('cash_amount', INT),
					'cash_interval'	=> request('cash_interval', INT),
				);
				
				$error .= ( !$data['cash_name'] )	? ( $error ? '<br />' : '' ) . sprintf($lang['sprintf_msg_select'], sprintf($lang['sprintf_name'], $lang['cash'])) : '';
				$error .= ( !$data['cash_amount'] )	? ( $error ? '<br />' : '' ) . $lang['msg_select_amount'] : '';
				
				if ( !$error )
				{
					if ( $mode == 'create' )
					{
						$sql = sql(CASH, $mode, $data);
						$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(CASH, $mode, $data, 'cash_id', $data_id);
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
					
					log_add(LOG_ADMIN, $log, 'error', $error);
				}
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

			$template->assign_vars(array(
				'L_HEAD'			=> $acp_title,
				'L_INPUT'			=> sprintf($lang["sprintf_$mode"], $lang['cash_reason'], $data['cash_name']),
				
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
			
			break;
		
		case 'delete_cat':
		
			$data = data(CASH, $data_id, false, 1, true);
			
			if ( $data_id && $confirm )
			{
				$sql = sql(CASH, $mode, $data, 'cash_id', $data_id);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				orders(GAMES, '-1');
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['cash_name']),

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
		
		case 'bankdata':
			
			$template->assign_block_vars('bankdata', array());
			
			$data = data(SETTINGS, "WHERE settings_name = 'bank_data'", true, 5, 2);
			
			foreach ( $data['bank_data'] as $key => $array )
			{
				$sort[$key] = $array['order'];
			}
			array_multisort($sort, SORT_ASC, SORT_NUMERIC, $data['bank_data']);
			
			foreach ( $data as $key => $value )
			{
				$lng = $key;
				$lng = isset($lang[$lng]) ? $lang[$lng] : $lng;
				
				$template->assign_block_vars("$mode._row", array(
					'LNG' => $lng,
					'KEY' => $key,
				));
				
				foreach ( $value as $keys => $rows )
				{
					$keys = $keys;
					$reqs = $rows['req'];
					$lngs = isset($lang[$keys]) ? $lang[$keys] : $keys;
					
					$template->assign_block_vars("$mode.row._option", array(
						'KEYS' => $keys,
						'LNGS' => $lngs,
					));
					
					if ( !request('submit', TXT) )
					{
						$template->assign_block_vars("$mode.row._option._input", array(
							'TYPE'	=> $rows['type'],
							'VALUE' => $rows['value'],
						));
					}
					else
					{
						$template->assign_block_vars("$mode.row._option._input", array(
							'TYPE'	=> $rows['type'],
							'VALUE' => request($keys, $reqs),
						));
						
						$data['bank_data'][$keys]['value'] = request($keys, $reqs);
						
						$msglng = str_replace('bank', 'msg_empty', $keys);
						
						$error .= !$data['bank_data'][$keys]['value'] ? ( $error ? '<br />' : '' ) . $lang[$msglng] : '';
					}
				}
			}
			
			if ( request('submit', TXT) )
			{
				if ( !$error )
				{
					$sql = "UPDATE " . SETTINGS . " SET settings_value = '" . serialize($data['bank_data']) . "' WHERE settings_name = 'bank_data'";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', __LINE__, __FILE__, $sql);
					}
					
					$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
					
					log_add(LOG_ADMIN, $log, 'error', $error);
				}
			}

			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_BANKDATA'	=> $lang['cash_bank'],
				
				'S_ACTION'		=> check_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			break;
			
		case 'bankdata_delete':
			
			if ( $confirm )
			{
				$cash_empty = array(
					'bank_holder'	=> array('type' => 'input', 'req' => 2, 'value' => '', 'order' => 10),
					'bank_name'		=> array('type' => 'input', 'req' => 2, 'value' => '', 'order' => 20),
					'bank_blz'		=> array('type' => 'input', 'req' => 0, 'value' => '', 'order' => 30),
					'bank_number'	=> array('type' => 'input', 'req' => 0, 'value' => '', 'order' => 40),
					'bank_reason'	=> array('type' => 'input', 'req' => 2, 'value' => '', 'order' => 50),
				);
				
				$sql = "UPDATE " . SETTINGS . " SET settings_value = '" . serialize($cash_empty) . "' WHERE settings_name = 'bank_data'";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', __LINE__, __FILE__, $sql);
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
					'M_TEXT'	=> $lang['confirm_bank'],

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			
			break;
		
		default:
			
			$template->assign_block_vars('display', array());
			
			$postage_cash = $postage_cashuser = 0;
			
			$cash = data(CASH, false, true, 0, false);
			$bank = data(SETTINGS, "WHERE settings_name = 'bank_data'", true, 5, 2);
			
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
			
			if ( !$cash )
			{
				$template->assign_block_vars('display.empty', array());
			}
			else
			{
				$cnt_cash = count($cash);
				
				for ( $i = 0; $i < $cnt_cash; $i++ )
				{
					$id		= $cash[$i]['cash_id'];
					$name	= $cash[$i]['cash_name'];
					$type	= $cash[$i]['cash_type'];
					$amount	= $cash[$i]['cash_amount'];
					$val	= $cash[$i]['cash_interval'];
					
					$template->assign_block_vars('display.cash_row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
						'TYPE'		=> ( $type != '0' ) ? ( ( $type == '1' ) ? img('i_icon', 'icon_sound', '') : img('i_icon', 'icon_other', '') ) : img('i_icon', 'icon_match', ''),
						'AMOUNT'	=> $amount,
						
						'DATE'		=> ( $val != 2 ) ? ( $val != 1 ) ? $lang['interval_month'] : $lang['interval_weeks'] : $lang['interval_weekly'],
						
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
					));
					
					$postage_cash += ( $val != 2 ) ? ( $val != 1 ) ? $amount : 2 * str_replace(',', '.', $amount) : 4 * str_replace(',', '.', $amount);
				}
			}
			
			if ( !$user )
			{
				$template->assign_block_vars('display.empty_user', array());
			}
			else
			{
				$cnt_user = count($user);
				
				for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt_user); $i++ )
				{
					$uid	= $user[$i]['cash_user_id'];
					$month_cur	= date("m", $time);
					$month_user	= explode(', ', $user[$i]['user_month']);
					
					if ( $month_cur == $month_user || in_array($month_cur, $month_user) )
					{
						$postage_cashuser += $user[$i]['user_amount'];
					}
					else if ( !$user[$i]['user_interval'] && in_array($month_cur, $month_user) )
					{
						$postage_cashuser += $user[$i]['user_amount'];
					}
					
					$lng	= ( $user[$i]['time_update'] ) ? 'update' : 'create';
					$time	= ( $user[$i]['time_update'] ) ? $user[$i]['time_update'] : $user[$i]['time_create'];
										
					$template->assign_block_vars('display.user_row', array(
						'USER'		=> href('a_txt', $file, array('mode' => '_update_user', $urlu => $uid), $user[$i]['user_name'], $user[$i]['user_name']),
						
						'MONTH'		=> $user[$i]['user_month'],
						'AMOUNT'	=> $user[$i]['user_amount'],
						'INTERVAL'	=> $user[$i]['user_interval'] ? $lang['interval_only'] : $lang['interval_month'],
						
						'TIME'		=> sprintf('%s: %s', $lang[$lng], create_date($userdata['user_dateformat'], $time, $userdata['user_timezone'])),
						
						'UPDATE'	=> href('a_img', $file, array('mode' => '_update_user', $urlu => $uid), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => '_delete_user', $urlu => $uid), 'icon_cancel', 'common_delete'),
					));
				}
				
				$current_page = $cnt_user ? ceil($cnt_user/$settings['per_page_entry']['acp']) : 1;
			}
			
			if ( $bank['bank_data']['bank_holder']['value'] || $bank['bank_data']['bank_name']['value'] || $bank['bank_data']['bank_blz']['value'] || $bank['bank_data']['bank_number']['value'] || $bank['bank_data']['bank_reason']['value'] )
			{
				$template->assign_block_vars('display.bank', array(
					'HOLDER'	=> $bank['bank_data']['bank_holder']['value'],
					'NAME'		=> $bank['bank_data']['bank_name']['value'],
					'BLZ'		=> $bank['bank_data']['bank_blz']['value'],
					'NUMBER'	=> $bank['bank_data']['bank_number']['value'],
					'REASON'	=> $bank['bank_data']['bank_reason']['value'],
				));
			}
			
			$postage		= $postage_cashuser - $postage_cash;
			$postage_class	= ( $postage < 0 ) ? ( $postage > 0 ) ? 'draw' : 'lose' : 'win';
			
			$template->assign_vars(array(
				'L_HEAD'		=> $acp_title,
				'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['cash_reason']),
				'L_CREATE_USER'	=> sprintf($lang['sprintf_create'], $lang['cash_user']),
				'L_CREATE_BANK'	=> $lang['cash_bank'],
				'L_EXPLAIN'		=> $lang['explain'],
				
				'L_BANK'		=> $lang['bank_data'],
				'L_REASON'		=> $lang['cash_reason'],
			#	'L_NAME'		=> $lang['cash_name'],
				'L_USERNAME'	=> $lang['user_name'],
				'L_INTERVAL'	=> $lang['interval'],
				'L_POSTAGE'		=> $lang['postage'],
			#	'L_BD'			=> $lang['cash_bankdata'],
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
				
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], (floor($start/$settings['per_page_entry']['acp'])+1), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file?", $cnt_user, $settings['per_page_entry']['acp'], $start),

				
			#	'S_CREATE_USER_BOX'	=> select_box('user', 'selectsmall', 'user_id', 'user_name'),
				'S_BANKDATA'		=> check_sid("$file?mode=bankdata"),				
				'S_CREATE_USER'		=> check_sid("$file?mode=create"),
				'S_CREATE'			=> check_sid("$file?mode=create_cat"),
				'S_ACTION'			=> check_sid($file),
			));
			
			break;
	}

	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>