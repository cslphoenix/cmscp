<?php

function counter_update()
{
	global $db, $lang, $settings, $template, $userdata;
	
	// Prüfen, ob bereits ein Counter für den  
    // heutigen Tag erstellt wurde 
    $sql = 'SELECT counter_id FROM ' . COUNTER_COUNTER . ' WHERE counter_date = CURDATE()';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
    // ist der Tag nocht nicht vorhanden,  
    // wird ein neuer Tagescounter erstellt 
    if ( !$db->sql_numrows($result) )
	{
		$sql = 'INSERT INTO ' . COUNTER_COUNTER . ' SET counter_date = CURDATE()';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
    }
	
	// Alte (mehr als 1 Tag) IPs in 'Online' löschen 
	// damit die Datenbank nicht überfüllt wird
	$sql = 'DELETE FROM ' . COUNTER_ONLINE . ' WHERE DATE_SUB(NOW(), INTERVAL 1 DAY) > online_start';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	// Überprüfe, ob die IP bereits gespeichert ist
	$sql = 'SELECT online_ip FROM ' . COUNTER_ONLINE . ' WHERE online_ip = "' . $userdata['session_ip'] . '"';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
		
	// Falls nicht, wird sie gespeichert 
    if ( !$db->sql_numrows($result) )
	{
		$sql = 'INSERT INTO ' . COUNTER_ONLINE . ' (online_ip, online_date, online_start) VALUES ("' . $userdata['session_ip'] . '", NOW(), NOW())';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		// ... und die Anzahl wird um 1 erhöht 
		$sql = 'UPDATE ' . COUNTER_COUNTER . ' SET counter_entry = counter_entry + 1 WHERE counter_date = CURDATE()';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
    } 
    // Falls ja, wird ihr Datum aktualisiert 
    else
	{
		$sql = 'UPDATE ' . COUNTER_ONLINE . ' SET online_date = NOW() WHERE online_ip = "' . $userdata['session_ip'] . '"';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
}

function counter_result()
{
	global $db, $lang, $settings, $template, $userdata, $oCache;
	
	if ( defined('CACHE') )
	{
		$sCacheName = 'data_counter';

		if ( ( $counter_data = $oCache -> readCache($sCacheName)) === false )
		{
			$sql = "SELECT counter_entry AS count_day FROM " . COUNTER_COUNTER . " WHERE counter_date = CURDATE()";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$count_day = $db->sql_fetchrow($result);
		
			$sql = "SELECT COUNT(counter_entry) AS count_yesterday FROM " . COUNTER_COUNTER . " WHERE counter_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$count_yesterday = $db->sql_fetchrow($result);
			
			$sql = "SELECT SUM(counter_entry) AS count_month FROM " . COUNTER_COUNTER . " WHERE counter_entry != 0 AND DATE_FORMAT(counter_date, '%m') = DATE_FORMAT(NOW(), '%m')";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$count_month = $db->sql_fetchrow($result);
			
			$sql = "SELECT SUM(counter_entry) AS count_year FROM " . COUNTER_COUNTER . " WHERE counter_entry != 0 AND DATE_FORMAT(counter_date, '%y') = DATE_FORMAT(NOW(), '%y')";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$count_year = $db->sql_fetchrow($result);
			
			$sql = "SELECT SUM(counter_entry) AS count_total FROM " . COUNTER_COUNTER;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$count_total = $db->sql_fetchrow($result);
			
			$counter_data = array_merge($count_day, $count_yesterday, $count_month, $count_year, $count_total);
			
			$oCache -> writeCache($sCacheName, $counter_data);
		}
	}
	else
	{
		$sql = "SELECT counter_entry AS count_day FROM " . COUNTER_COUNTER . " WHERE counter_date = CURDATE()";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$count_day = $db->sql_fetchrow($result);
	
		$sql = "SELECT SELECT COUNT(counter_entry) AS count_yesterday FROM " . COUNTER_COUNTER . " WHERE counter_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$count_yesterday = $db->sql_fetchrow($result);
		
		$sql = "SELECT SUM(counter_entry) AS count_month FROM " . COUNTER_COUNTER . " WHERE counter_entry != 0 AND DATE_FORMAT(counter_date, '%m') = DATE_FORMAT(NOW(), '%m')";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$count_month = $db->sql_fetchrow($result);
		
		$sql = "SELECT SUM(counter_entry) AS count_year FROM " . COUNTER_COUNTER . " WHERE counter_entry != 0 AND DATE_FORMAT(counter_date, '%y') = DATE_FORMAT(NOW(), '%y')";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$count_year = $db->sql_fetchrow($result);
		
		$sql = "SELECT SUM(counter_entry) AS count_total FROM " . COUNTER_COUNTER;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$count_total = $db->sql_fetchrow($result);
		
		$counter_data = array_merge($count_day, $count_yesterday, $count_month, $count_year, $count_total);
	}
	
	$l_counter_head = sprintf($lang['counter_today'], $counter_data['count_day']);
	$l_counter_head .= sprintf($lang['counter_yesterday'], $counter_data['count_yesterday']);
	$l_counter_head .= sprintf($lang['counter_month'], $counter_data['count_month']);
	$l_counter_head .= sprintf($lang['counter_year'], $counter_data['count_year']);
	$l_counter_head .= sprintf($lang['counter_total'], $counter_data['count_total']);
	
	$template->assign_vars(array(
		'TOTAL_COUNTER_HEAD'		=> $l_counter_head,
	));
	
	if ( $settings['subnavi_statscounter'] )
	{
		$template->assign_block_vars('statscounter', array());
	
		$template->assign_vars(array(
			'STATS_COUNTER_TODAY' 		=> sprintf($lang['counter_today'], $stats_day),
			'STATS_COUNTER_YESTERDAY' 	=> sprintf($lang['counter_yesterday'], $stats_yesterday),
			'STATS_COUNTER_MONTH' 		=> sprintf($lang['counter_month'], $stats_month),
			'STATS_COUNTER_YEAR' 		=> sprintf($lang['counter_year'], $stats_year),
			'STATS_COUNTER_TOTAL' 		=> sprintf($lang['counter_total'], $stats_total),
//			'STATS_COUNTER_CACHE'		=> ( defined('CACHE') ) ? display_cache('counter_stats_total', 1) : '',
		));
	}
}

?>