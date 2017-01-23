<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_cash',
		'cat'		=> 'teams',
		'modes'		=> array(
			'bank'	=> array('title' => 'acp_cashbank'),
			'user'	=> array('title' => 'acp_cashuser'),
			'type'	=> array('title' => 'acp_cashtype'),
		)
	);
}
else
{
	define('IN_CMS', true);

	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_cash';
	
	include('./pagestart.php');
	
	add_lang('cash');
	acl_auth(array('a_cashuser', 'a_cashuser_bank', 'a_cashuser_create', 'a_cashuser_delete'));
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$time	= time();
	$log	= SECTION_CASH;
	$file	= basename(__FILE__) . $iadds;	
	
	$data	= request('id', INT);
	$sub	= request('sub', TYP);
	$subs	= request('subs', TYP);
	$mode	= request('mode', TYP);
	$sort	= request('sort', TYP);
	$type	= request('type', TYP);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$acp_title	= sprintf($lang['stf_header'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_cash.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = (in_array($mode, array('create', 'update', 'delete', 'bankdata', 'bankdata_delete'))) ? $mode : false;
	$_tpl = ($mode == 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				$action => array(
					'title' => 'data_input',
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
				$data_sql = data(CASH2, $data, false, 1, true);
			}
			else
			{
				$data_sql = build_request(CASH2, $vars, $error, $mode);
				
				if ( !$error )
				{
					$data_sql['cash_type']	= ($data_sql['type']) ? search_user('id', $data_sql['cash_name']) : $data_sql['cash_type'];
					$data_sql['cash_month']	= ($data_sql['type']) ? $data_sql['cash_month'] : '';
					
					if ( $mode == 'create' && acl_auth('create') )
					{
						$sql = sql(CASH2, $mode, $data_sql);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(CASH2, $mode, $data_sql, 'cash_id', $data);
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
			
			build_output(CASH2, $vars, $data_sql);
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
				'action'=> $action,
			));
			
			$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);

			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang[$action], $data_sql['cash_name']),
				'L_EXPLAIN'	=> $lang['com_required'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'delete':
		
			$data_sql = data(CASH2, $data, false, 1, true);
			
			if ( $data && $accept && $userauth['a_cashuser_delete'] )
			{
				$sql = sql(CASH2, $mode, $data_sql, 'cash_id', $data);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

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
					'M_TITLE'	=> $lang['com_confirm'],
					'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['cash_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}
			
			break;
		
		case 'bankdata':
		
			$template->assign_block_vars('bankdata', array());
			
			$bank_data = data(SETTINGS, "WHERE settings_name = 'bank_data'", true, 5, 2);
			
			foreach ( $bank_data as $key => $value )
			{
				$lng = $key;
				$lng = isset($lang[$lng]) ? $lang[$lng] : $lng;
				
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
						'LNGS' => $lngs,
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
					
					$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}

			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
				'L_BANKDATA'	=> $lang['bank'],
				
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
			
				$message = $lang['delete_bank'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $message);
			
			}
			else if ( !$accept )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['com_confirm'],
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
					
					$template->assign_vars(array(
						'HOLDER'	=> $bank['bank_data']['bank_holder']['value'] ? $bank['bank_data']['bank_holder']['value'] : ' - ',
						'NAME'		=> $bank['bank_data']['bank_name']['value'] ? $bank['bank_data']['bank_name']['value'] : ' - ',
						'BLZ'		=> $bank['bank_data']['bank_blz']['value'] ? $bank['bank_data']['bank_blz']['value'] : ' - ',
						'NUMBER'	=> $bank['bank_data']['bank_number']['value'] ? $bank['bank_data']['bank_number']['value'] : ' - ',
						'REASON'	=> $bank['bank_data']['bank_reason']['value'] ? $bank['bank_data']['bank_reason']['value'] : ' - ',
					));
				
					break;
					
				case 'user':

					$sql = "SELECT c.*, u.user_name, u.user_color
								FROM " . CASH2 . " c
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
								'INTERVAL'	=> $sqlout[$i]['cash_interval'] ? $lang['interval_only'] : $lang['interval_month'],
								
								'TIME'		=> sprintf('%s: %s', lang(($sqlout[$i]['time_update']) ? 'update' : 'create'), create_date($userdata['user_dateformat'], $time, $userdata['user_timezone'])),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
							));
						}
						
						$current_page = ( !$cnt_user ) ? 1 : ceil($cnt_user/$settings['ppe_acp']);
					}
				
					break;
					
				case 'type':
				
					$sqlout = data(CASH2, 'WHERE type = 0', false, 1, false);
					
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
								'DATE'		=> ($val != 2) ? (($val != 1) ? $lang['interval_month'] : $lang['interval_weeks']) : $lang['interval_weekly'],
																
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
							));
							
							$postage_cash += ($val != 2) ? (($val != 1) ? $amount : 2 * str_replace(',', '.', $amount)) : 4 * str_replace(',', '.', $amount);
						}
					}
				
					break;
			}
			
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['stf_header'], $lang[$action]),
				'L_CREATE'		=> sprintf($lang['stf_create'], $lang[$action]),
				'L_EXPLAIN'		=> $lang['com_required'],

				'L_REASON'		=> $lang['cash_reason'],
				'L_INTERVAL'	=> $lang['cash_interval'],
				'L_POSTAGE'		=> $lang['cash_postage'],
				
				'L_BANK'		=> $lang['bank_data'],

				'L_HOLDER'		=> $lang['bank_holder'],
				'L_NAME'		=> $lang['bank_name'],
				'L_BLZ'			=> $lang['bank_blz'],
				'L_NUMBER'		=> $lang['bank_number'],
				'L_REASON'		=> $lang['bank_reason'],
				'L_DELETE'		=> $lang['bank_delete'],
			
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