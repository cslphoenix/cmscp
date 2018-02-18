<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_CASH',
		'CAT'		=> 'TEAMS',
		'MODES'		=> array(
			'BANK'	=> array('TITLE' => 'ACP_CASHBANK'),
			'USER'	=> array('TITLE' => 'ACP_CASHUSER'),
			'TYPE'	=> array('TITLE' => 'ACP_CASHTYPE'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$current = $log = 'ACP_CASH';
	
	include('./pagestart.php');
	
	add_lang('cash');
	add_tpls('acp_cash');
	acl_auth(array('A_CASH', 'A_CASH_BANK', 'A_CASH_CREATE', 'A_CASH_DELETE', 'A_CASH_TYPE'));
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$sub	= request('sub', TYP);
	$subs	= request('subs', TYP);
	$mode	= request('mode', TYP);
	$sort	= request('sort', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$cancel = ( isset($_POST['cancel']) ) ? redirect('admin/' . check_sid($file)) : false;
#	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	
	$mode = in_array($mode, array('create', 'update', 'delete', 'paid', 'bankdata', 'bankdata_delete')) ? $mode : false;
	
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';

	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				$action => array(
					'title'	=> 'INPUT_DATA',
					'cash_name'		=>						array('validate' => TXT, 'explain' => false, 'type' => ($action == 'user' ? 'ajax:25;25' : 'text:25;25'), 'required' => ($action == 'user' ? 'input_user' : 'input_name'), 'params' => ($action == 'user' ? array('user:0:5') : '')),
					'cash_type'		=> ($action == 'type' ? array('validate' => INT, 'explain' => false, 'type' => 'radio:ctype', 'params' => array(false, true, false)) : 'hidden'),
					'cash_amount'	=>						array('validate' => INT, 'explain' => false, 'type' => 'text:5;5', 'required' => 'input_amount'),
					'cash_interval'	=>						array('validate' => INT, 'explain' => false, 'type' => "radio:$action", 'params' => array(false, true, false)),
					'cash_month'    => ($action == 'user' ? array('validate' => ARY, 'explain' => false, 'type' => 'check:months', 'required' => 'select_month') : 'hidden'),
					'cash_paid'		=> ($action == 'user' ? array('validate' => ARY, 'explain' => false, 'type' => 'check:months') : 'hidden'),
					'type'			=> 'hidden',
					'time_create'	=> 'hidden',
					'time_update'	=> 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'cash_name'		=> ($action == 'type') ? request('cash_type', TXT) : request('user_name', TXT),
					'cash_type'		=> 0,
					'cash_amount'	=> '',
					'cash_interval'	=> 0,
					'cash_month'	=> ($action == 'user') ? serialize(array(date("n", $time) => date("n", $time))) : '',
					'cash_paid'		=> 'a:0:{}',
					'type'			=> ($action == 'user') ? 1 : 0,
					'time_create'	=> $time,
					'time_update'	=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(CASH, $data, false, 1, 'row');
			}
			else
			{
				$data_sql = build_request(CASH, $vars, $error, $mode);
				
				if ( !$error )
				{
					$data_sql['cash_type']	= ($data_sql['type']) ? search_user('id', $data_sql['cash_name']) : $data_sql['cash_type'];
					$data_sql['cash_month']	= ($data_sql['type']) ? $data_sql['cash_month'] : '';
					
					if ( $mode == 'create' && acl_auth('create') )
					{
						$sql = sql(CASH, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else
					{
						$sql = sql(CASH, $mode, $data_sql, 'cash_id', $data);
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output(CASH, $vars, $data_sql);
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
				'action'=> $action,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_' . strtoupper($mode)], $lang[strtoupper($action)], $data_sql['cash_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'paid':
		
			$template->assign_block_vars($mode, array());
			
			$sql = "SELECT c.*, u.user_name, u.user_color
						FROM " . CASH . " c
							LEFT JOIN " . USERS . " u ON c.cash_type = u.user_id
						WHERE u.user_id <> " . ANONYMOUS . " AND c.type = 1
					ORDER BY c.cash_type, c.cash_interval";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$sqlout = $db->sql_fetchrowset($result);
			
			debug($sqlout, '$sqlout');
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
				'action'=> $action,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_' . strtoupper($mode)], $lang[strtoupper($action)], $data_sql['cash_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
			
		case 'delete':
		
			$data_sql = data(CASH, $data, false, 1, 'row');
			
			if ( $data && $accept && $userauth['a_cashuser_delete'] )
			{
				$sql = sql(CASH, $mode, $data_sql, 'cash_id', $data);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);

				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept && $userauth['a_cashuser_delete'] )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['cash_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}
			
			break;
		
		case 'bankdata':
		
			$template->assign_block_vars('bankdata', array());
			
			$bank_data = data(SETTINGS, "WHERE settings_name = 'bank_data'", true, 5, 2);
			
		#	debug($bank_data, '$bank_data');
			
			foreach ( $bank_data as $key => $value )
			{
				$lng = $key;
				
				$template->assign_block_vars("$mode.row", array(
					'LNG' => $lng,
					'KEY' => $key,
				));
				
				foreach ( $value as $keys => $rows )
				{
					$keys = $keys;
					$vali = $rows['validate'];
					$lngs = isset($lang[$keys]) ? $lang[$keys] : $keys;
					
					$template->assign_block_vars("$mode.row.option", array(
						'KEYS' => $keys,
						'LNGS' => langs($lngs),
					));
					
					$bank_data['bank_data'][$keys]['value'] = $_POST['bank_data'][$keys];
						
					$template->assign_block_vars("$mode.row.option.input", array(
						'TYPE'	=> $rows['type'],
						'VALUE' => $submit ? $_POST['bank_data'][$keys] : $rows['value'],
					));
					
					$msglng = str_replace('bank', 'msg_empty', $keys);
					
					$error[] .= !$bank_data['bank_data'][$keys]['value'] ? ( $error ? '<br />' : '' ) . $lang[$msglng] : '';
				}
			}
			
			if ( $submit )
			{
				if ( !$error )
				{
					$sql = "UPDATE " . SETTINGS . " SET settings_value = '" . serialize($bank_data['bank_data']) . "' WHERE settings_name = 'bank_data'";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', __LINE__, __FILE__, $sql);
					}
					
					$msg = $lang['UPDATE'] . sprintf($lang['RETURN_UPDATE'], check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}

			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_BANKDATA'	=> $lang['BANK'],
				
				'S_ACTION'		=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'		=> $fields,
			));
		
			break;
			
		case 'bankdata_delete':
			
			if ( $accept )
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
			
				$message = $lang['delete_bank'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $message);
			
			}
			else if ( !$accept )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> $lang['confirm_bank'],
						
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			
			break;
		
		default:
		
			$template->assign_block_vars($action, array());
			
			$fields = build_fields(array('mode' => 'create'));
			
			$postage_cash = $postage_cashuser = 0;
			$current_page = $cnt_user = '';
		
			switch ( $action )
			{
				case 'bank':
				
					$bank = data(SETTINGS, "WHERE settings_name = 'bank_data'", true, 5, 2);
					
				#	debug($bank, 'bank');
					
					$template->assign_vars(array(
						'HOLDER'	=> $bank['bank_data']['bank_holder']['value'] ? $bank['bank_data']['bank_holder']['value'] : ' - ',
						'NAME'		=> $bank['bank_data']['bank_name']['value'] ? $bank['bank_data']['bank_name']['value'] : ' - ',
						'BLZ'		=> $bank['bank_data']['bank_blz']['value'] ? $bank['bank_data']['bank_blz']['value'] : ' - ',
						'NUMBER'	=> $bank['bank_data']['bank_number']['value'] ? $bank['bank_data']['bank_number']['value'] : ' - ',
						'REASON'	=> $bank['bank_data']['bank_reason']['value'] ? $bank['bank_data']['bank_reason']['value'] : ' - ',
					));
				
					break;
					
				case 'user':
				
					$option[] = href('a_txt', $file, array('action' => $action, 'mode' => 'paid'), $lang['CASH_PAID'], $lang['CASH_PAID']);
					
					$sql = "SELECT c.*, u.user_name, u.user_color
								FROM " . CASH . " c
									LEFT JOIN " . USERS . " u ON c.cash_type = u.user_id
								WHERE u.user_id <> " . ANONYMOUS . " AND c.type = 1
							ORDER BY c.cash_type, c.cash_interval";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$sqlout = $db->sql_fetchrowset($result);
					
					if ( !$sqlout )
					{
						$template->assign_block_vars("$action.empty", array());
					}
					else
					{
						$cnt_user	= count($sqlout);
						$month_cur	= date("m", $time);
						
						for ( $i = $start; $i < min($settings['ppe_acp'] + $start, $cnt_user); $i++ )
						{
							$id		= $sqlout[$i]['cash_id'];							
							$uid	= $sqlout[$i]['cash_type'];
							
							$cash_month = unserialize($sqlout[$i]['cash_month']);
							$month_user	= implode(', ', $cash_month);
							
							if ( $month_user <= $month_cur )
							{
								$postage_cashuser += $sqlout[$i]['cash_amount'];
							}
							else
							{
								if ( is_array($cash_month) )
								{
									if ( in_array($month_cur, $cash_month) )
									{
										$postage_cashuser += $sqlout[$i]['cash_amount'];
									}
								}
							}
							
							$time = ( $sqlout[$i]['time_update'] ) ? $sqlout[$i]['time_update'] : $sqlout[$i]['time_create'];
												
							$template->assign_block_vars("$action.row", array(
								'USER'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $sqlout[$i]['cash_name'], $sqlout[$i]['cash_name']),
								
								'MONTH'		=> $month_user,
								'AMOUNT'	=> $sqlout[$i]['cash_amount'],
								'INTERVAL'	=> $sqlout[$i]['cash_interval'] ? $lang['INTERVAL_ONLY'] : $lang['INTERVAL_MONTH'],
								
								'TIME'		=> sprintf('%s: %s', langs(($sqlout[$i]['time_update']) ? 'update' : 'create'), create_date($userdata['user_dateformat'], $time, $userdata['user_timezone'])),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							));
						}
						
						$current_page = ( !$cnt_user ) ? 1 : ceil($cnt_user/$settings['ppe_acp']);
					}
				
					break;
					
				case 'type':
				
					$sqlout = data(CASH, 'WHERE type = 0', false, 1, 'set');
					
					if ( !$sqlout )
					{
						$template->assign_block_vars("$action.empty", array());
					}
					else
					{
						foreach ( $sqlout as $row )
						{
							$id		= $row['cash_id'];
							$name	= $row['cash_name'];
							$typ	= $row['cash_type'];
							$amount	= $row['cash_amount'];
							$val	= $row['cash_interval'];
							
							$template->assign_block_vars("$action.row", array(
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
								'AMOUNT'	=> $amount,
								
								'TYPE'		=> ($typ != 0) ? (($typ == 1) ? img('i_icon', 'icon_sound', '') : img('i_icon', 'icon_other', '')) : img('i_icon', 'icon_match', ''),
								'DATE'		=> ($val != 2) ? (($val != 1) ? $lang['INTERVAL_MONTH'] : $lang['INTERVAL_WEEKS']) : $lang['INTERVAL_WEEKLY'],
																
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							));
							
							$postage_cash += ($val != 2) ? (($val != 1) ? $amount : 2 * str_replace(',', '.', $amount)) : 4 * str_replace(',', '.', $amount);
						}
					}
				
					break;
			}
			
			$template->assign_vars(array(
				'L_HEADER'		=> sprintf($lang['STF_HEADER'], $lang[strtoupper($action)]),
				'L_CREATE'		=> sprintf($lang['STF_CREATE'], $lang[strtoupper($action)]),
				'L_EXPLAIN'		=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),

				'L_REASON'		=> $lang['CASH_REASON'],
				'L_INTERVAL'	=> $lang['CASH_INTERVAL'],
			#	'L_POSTAGE'		=> $lang['cash_postage'],
				'L_BANK'		=> $lang['BANK_DATA'],
				'L_HOLDER'		=> $lang['BANK_HOLDER'],
				'L_NAME'		=> $lang['BANK_NAME'],
				'L_BLZ'			=> $lang['BANK_BLZ'],
				'L_NUMBER'		=> $lang['BANK_NUMBER'],
				'L_REASON'		=> $lang['BANK_REASON'],
				'L_DELETE'		=> $lang['BANK_DELETE'],
			
				'POSTAGE'		=> sprintf('%s %s', $postage_cashuser, $config['default_currency']),
				'POSTAGE_CASH'	=> sprintf('%s %s', $postage_cash, $config['default_currency']),

                'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor($start/$settings['ppe_acp']) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file&", $cnt_user, $settings['ppe_acp'], $start),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>