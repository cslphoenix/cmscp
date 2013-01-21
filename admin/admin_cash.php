<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_cash',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_cash'),
		)
	);
}
else
{
	define('IN_CMS', true);

	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_cash';
	
	include('./pagestart.php');
	
	add_lang('cash');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_CASH;
	$url	= POST_CASH_USER;
	$cat	= POST_CASH;
	$file	= basename(__FILE__);
	$time	= time();
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_cat	= request($cat, INT);
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
	#	'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = (in_array($mode, array('create', 'update', 'delete', 'create_cat', 'update_cat', 'delete_cat', 'bankdata', 'bankdata_delete'))) ? $mode : false;
	
		debug($_POST, 'post');
	
	$test = '101010101010';
	
#	debug(explode('', $test));
	
#	for ($i = 0; $i < strlen($test); $i++)
#    $tests[] = $test{$i};
	
	debug(str_split($test));
	

	
	debug($_POST);
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'user' => array(
					'title1' => 'input_data',
					'user_id'		=> array('validate' => TXT,	'explain' => false,	'type' => 'ajax:25;25', 'required' => 'input_user', 'params' => array('user', 0, 2)),
					'user_amount'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5:5', 'required' => 'input_amount'),
					'user_interval'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:interval'),
					'user_month'	=> array('validate' => ARY,	'type' => 'check:months'),
					'time_create'	=> 'hidden',
					'time_update'	=> 'hidden',
				),
			);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'user_id'		=> request('user_name', TXT),
					'user_amount'	=> '',
					'user_month'	=> date("n", $time),
					'user_interval'	=> 1,
					'time_create'	=> $time,
					'time_update'	=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(CASH_USER, $data_id, false, 2, true);
			}
			else
			{
				$data_sql = build_request(CASH_USER, $vars, $error, $mode);
				
				if ( !$error )
				{
					$data['user_id'] = ( !is_numeric($data['user_id']) ) ? ( $data['user_id'] != '' ) ? search_user('id', $data['user_id']) : '' : $data['user_id'];
					
					if ( $mode == 'create' )
					{
						$sql = sql(CASH_USER, $mode, $data_sql);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(CASH_USER, $mode, $data_sql, 'cash_user_id', $data);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output($data, $vars, 'input', false, CASH_USER);

			$user_name = '';
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
			
			$template->assign_vars(array(
				'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_INPUT'			=> sprintf($lang["sprintf_{$mode}"], $lang['user'], $user_name),

				'S_ACTION'			=> check_sid($file),
				'S_FIELDS'			=> $fields,
			));
			
			break;
		/*		
		case 'delete_user':
		
			$data_sql = data(CASH_USER, $data_user, false, 2, 1);
			
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
			
			$vars = array(
				'cash' => array(
					'title1' => 'input_data',
					'cash_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'cash_amount'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5:5', 'required' => 'input_amount'),
					'cash_type'		=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:type'),
					'cash_interval'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:cinterval'),
				),
			);

			if ( $mode == 'create_cat' && !$submit )
			{
				$data_sql = array(
					'cash_name'		=> request('cash_name', TXT),
					'cash_type'		=> 0,
					'cash_amount'	=> '',
					'cash_interval'	=> 0,
				);
			}
			else if ( $mode == 'update_cat' && !$submit )
			{
				$data_sql = data(CASH, $data_cat, false, 1, true);
			}
			else
			{
				$data_sql = build_request(CASH, $vars, 'cash', $error);
				
				if ( !$error )
				{
					if ( $mode == 'create_cat' )
					{
						$sql = sql(CASH, $mode, $data_sql);
						$msg = $lang['create_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(CASH, $mode, $data_sql, 'cash_id', $data_cat);
						$msg = $lang['update_cat'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output($data, $vars, 'input_cat', false, CASH);
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data_cat\" />";

			$template->assign_vars(array(
				'L_HEAD'			=> $acp_title,
				'L_INPUT'			=> sprintf($lang['sprintf_' . $mode], $lang['cash_reason'], $data['cash_name']),
				
				'S_ACTION'			=> check_sid($file),
				'S_FIELDS'			=> $fields,
			));
			
			break;
		
		case 'delete_cat':
		
			$data_sql = data(CASH, $data, false, 1, true);
			
			if ( $data && $confirm )
			{
				$sql = sql(CASH, $mode, $data_sql, 'cash_id', $data_cat);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				orders(GAMES, '-1');
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
	
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
		*/
		case 'bankdata':
			
			$template->assign_block_vars('bankdata', array());
			
			$data_sql = data(SETTINGS, "WHERE settings_name = 'bank_data'", true, 5, 2);
			
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
					
					if ( !$submit )
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
						
						$error[] = !$data['bank_data'][$keys]['value'] ? ( $error ? '<br />' : '' ) . $lang[$msglng] : '';
					}
				}
			}
			
			if ( $submit )
			{
				if ( !$error )
				{
					$sql = "UPDATE " . SETTINGS . " SET settings_value = '" . serialize($data['bank_data']) . "' WHERE settings_name = 'bank_data'";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', __LINE__, __FILE__, $sql);
					}
					
					$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
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
						'NAME'		=> href('a_txt', $file, array('mode' => 'update_cat', $cat => $id), $name, $name),
						'TYPE'		=> ( $type != '0' ) ? ( ( $type == '1' ) ? img('i_icon', 'icon_sound', '') : img('i_icon', 'icon_other', '') ) : img('i_icon', 'icon_match', ''),
						'AMOUNT'	=> $amount,
						
						'DATE'		=> ( $val != 2 ) ? ( $val != 1 ) ? $lang['interval_month'] : $lang['interval_weeks'] : $lang['interval_weekly'],
						
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update_cat', $cat => $id), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete_cat', $cat => $id), 'icon_cancel', 'common_delete'),
					));
					
				#	$postage_cash += ( $val != 2 ) ? ( $val != 1 ) ? $amount : 2 * str_replace(',', '.', $amount) : 4 * str_replace(',', '.', $amount);
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
						'USER'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $uid), $user[$i]['user_name'], $user[$i]['user_name']),
						
						'MONTH'		=> $user[$i]['user_month'],
						'AMOUNT'	=> $user[$i]['user_amount'],
						'INTERVAL'	=> $user[$i]['user_interval'] ? $lang['interval_only'] : $lang['interval_month'],
						
						'TIME'		=> sprintf('%s: %s', $lang[$lng], create_date($userdata['user_dateformat'], $time, $userdata['user_timezone'])),
						
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $uid), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $uid), 'icon_cancel', 'common_delete'),
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
			#	'POSTAGE_CASH'		=> $postage_cash,
			#	'POSTAGE_CASHUSER'	=> $postage_cashuser,
			#	'POSTAGE_CLASS'		=> $postage_class,
				
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