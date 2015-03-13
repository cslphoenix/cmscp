<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_CASH);
init_userprefs($userdata);

$start	= request('start', INT);

$data	= request('id', INT);
$mode	= request('mode', TXT);

if ( $mode == '' )
{
	if ( !$userdata['session_logged_in'] )
	{
		redirect(check_sid("login.php?redirect=cash.php"));
	}
	
	$page_title = $lang['head_cash'];
	main_header();
	
	$template->set_filenames(array('body' => 'body_cash.tpl'));
	$template->assign_block_vars('display', array());
	
	//
	//	Bankdaten
	//
	
	debug($settings['bank_data']);
	
	$template->assign_vars(array(
#		'L_BD_NAME'		=> $lang['cash_bd_name'],
#		'L_BD_BANK'		=> $lang['cash_bd_bank'],
#		'L_BD_BLZ'		=> $lang['cash_bd_blz'],
#		'L_BD_NUMBER'	=> $lang['cash_bd_number'],
#		'L_BD_REASON'	=> $lang['cash_bd_reason'],
		
		'BD_NAME'		=> unserialize($settings['bank_data']['bank_holder']),
		'BD_BANK'		=> unserialize($settings['bank_data']['bank_name']),
		'BD_BLZ'		=> unserialize($settings['bank_data']['bank_blz']),
		'BD_NUMBER'		=> unserialize($settings['bank_data']['bank_number']),
		'BD_REASON'		=> unserialize($settings['bank_data']['bank_reason']),
	));
	
	//
	//	Gesammtübersicht aller Kosten
	//	
	$sql = 'SELECT * FROM ' . CASH;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cash_data = $db->sql_fetchrowset($result);
	
	if ( !$cash_data )
	{
		$template->assign_block_vars('display.entry_empty', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		$total_amount = '';
		
		for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, count($cash_data)); $i++ )
		{
			$class = ($i % 2) ? 'row1' : 'row2';
			
			$cash_id	= $cash_data[$i]['cash_id'];
			
			switch ( $cash_data[$i]['cash_interval'] )
			{
				case '0':
			#		$cash_interval	= $lang['cash_interval_month'];
					$cash_amount	= $cash_data[$i]['cash_amount'];
					break;
				case '1':
			#		$cash_interval	= $lang['cash_interval_weeks'];
					$cash_amount	= 2 * str_replace(',', '.', $cash_data[$i]['cash_amount']);
					break;
				case '2':
			#		$cash_interval	= $lang['cash_interval_weekly'];
					$cash_amount	= 4 * str_replace(',', '.', $cash_data[$i]['cash_amount']);
					break;
			}
			
			$template->assign_block_vars('display.cash_row', array(
				'CLASS' 		=> $class,
				'CASH_NAME'		=> $cash_data[$i]['cash_name'],
				'CASH_AMOUNT'	=> $cash_data[$i]['cash_amount'],
		#		'CASH_DATE'		=> $cash_interval,
			));
			
			$total_amount += $cash_amount;
		}
	}
	
	//
	//	Benutzereinträge
	//
	$sql = "SELECT cu.*, u.user_name, u.user_color
				FROM " . CASH_USER . " cu
					LEFT JOIN " . USERS . " u ON cu.user_id = u.user_id
				WHERE u.user_id <> " . ANONYMOUS . "
			ORDER BY cu.user_id, cu.user_interval";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cash_user_data = $db->sql_fetchrowset($result);
	
	if ( !$cash_user_data )
	{
		$template->assign_block_vars('display.no_entry_users', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		$total_users_amount = $cash_count = '';
		
		for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, count($cash_user_data)); $i++ )
		{
			$class = ($i % 2) ? 'row1' : 'row2';
			
			$user_id			= $cash_user_data[$i]['user_id'];
			$user_amount		= $cash_user_data[$i]['user_amount'];
			$user_name			= $cash_user_data[$i]['user_name'];
			$user_interval		= ( $cash_user_data[$i]['user_interval'] ) ? $lang['cash_interval_o'] : $lang['cash_interval_m'];
			$user_month			= '';
			$user_month_info	= '';
			
			$month_cur	= date("m", time());
			$month_user	= explode(', ', $cash_user_data[$i]['user_month']);
			
			foreach ( $month_user as $key => $value )
			{
				$user_month[] = $lang['month'][$value];
			}
			
			$user_month = implode(', ', $user_month);
			$user_month_info = ( $cash_user_data[$i]['user_interval'] ) ? sprintf($lang['interval_o'], $user_month) : $user_month_info = sprintf($lang['interval_m'], $user_month);

			if ( $month_cur == $month_user || in_array($month_cur, $month_user) )
			{
				$total_users_amount += $cash_user_data[$i]['user_amount'];
				$cash_count++;
			}
			else if ( !$cash_user_data[$i]['user_interval'] && in_array($month_cur, $month_user) )
			{
				$total_users_amount += $cash_user_data[$i]['user_amount'];
				$cash_count++;
			}
			
			if ( $userdata['user_id'] == $user_id )
			{
				$user_amount	= '<b>' . $user_amount . '</b>';
				$user_name		= '<b>' . $user_name . '</b>';
			}
			
			if ( $userdata['user_level'] > MOD )
			{
				$template->assign_block_vars('display.cash_user_row', array(
					'CLASS' 				=> $class,
					'CASHUSERNAME'			=> $user_name,
					'CASHUSER_AMOUNT'		=> $user_amount,
					'CASHUSER_INTERVAL'	=> $user_interval,
					'CASHUSER_MONTH'		=> $user_month_info,
				));
			}
		}
	}
	
	if ( $userdata['user_level'] <= MOD )
	{
		foreach ( $cash_user_data as $entry => $data )
		{
			if ( $userdata['user_id'] == $data['user_id'] )
			{
				$user_month			= '';
				$user_month_info	= '';
				
				$month_cur	= date("m", time());
				$month_user	= explode(', ', $data['user_month']);
				
				foreach ( $month_user as $key => $value )
				{
					$user_month[] = $lang['month'][$value];
				}
				
				$user_month = implode(', ', $user_month);
				$user_month_info = ( $data['user_interval'] ) ? sprintf($lang['interval_o'], $user_month) : $user_month_info = sprintf($lang['interval_m'], $user_month);
	
				$template->assign_block_vars('display.cash_user', array(
					'CLASS' 				=> $class,
					'CASHUSER_NAME'		=> $data['user_name'],
					'CASHUSER_AMOUNT'		=> $data['user_amount'],
					'CASHUSER_INTERVAL'	=> ( $data['user_interval'] ) ? $lang['cash_interval_o'] : $lang['cash_interval_m'],
					'CASHUSER_MONTH'		=> $user_month_info,
				));
			}
		}
	}
	
	$total_cash = ($total_amount - $total_users_amount) * -1;
	$cash_class = ( $total_cash < 0 ) ? ( $total_cash > 0 ) ? 'draw' : 'lose' : 'win';
	
	$template->assign_vars(array(
		'L_HEAD_CASH'		=> $lang['head_cash'],
		
		'L_CASH_AMOUNT'		=> $lang['cash_amount'],
		'L_CASH_NAME'		=> $lang['cash_name'],
		'L_CASH_INTERVAL'	=> $lang['cash_interval'],
		'L_CASHUSER_USERNAME'	=> $lang['user_name'],
		
		'CASH_T_AMOUNT'		=> $total_amount,
		'CASH_U_AMOUNT'		=> $total_users_amount,
		'CASH_TOTAL'		=> $total_cash,
		'CASH_CLASS'		=> $cash_class,
		'CASH_COUNT'		=> $cash_count,
		
		'S_CASH_ACTION'		=> check_sid('cash.php'),
	));
	
}
else
{
	redirect(check_sid('cash.php', true));
}

if ( $userdata['user_level'] <= TRIAL )
{
	message(GENERAL_ERROR, $lang['access_denied']);
}

$template->pparse('body');

main_footer();

?>