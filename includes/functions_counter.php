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

function _counter_update()
{
	global $db, $settings, $template, $userdata, $lang;
	
	// Prüfen, ob bereits ein Counter für den  
    // heutigen Tag erstellt wurde 
    $sql = 'SELECT counter_id FROM ' . COUNTER_COUNTER . ' WHERE counter_date = CURDATE()'; 
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
    // ist der Tag nocht nicht vorhanden,  
    // wird ein neuer Tagescounter erstellt 
    if (!$db->sql_numrows($result))
	{
		$sql = 'INSERT INTO ' . COUNTER_COUNTER . ' SET counter_date = CURDATE()'; 
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
    }
	
	// Alte (mehr als 1 Tag) IPs in 'Online' löschen 
	// damit die Datenbank nicht überfüllt wird
	$sql = 'DELETE FROM ' . COUNTER_ONLINE . ' WHERE DATE_SUB(NOW(), INTERVAL 1 DAY) > online_start'; 
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	// Überprüfe, ob die IP bereits gespeichert ist
	$sql = 'SELECT online_ip FROM ' . COUNTER_ONLINE . ' WHERE online_ip = "' . $userdata['session_ip'] . '"';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
		
	// Falls nicht, wird sie gespeichert 
    if (!$db->sql_numrows($result))
	{
		$sql = 'INSERT INTO ' . COUNTER_ONLINE . ' (online_ip, online_date, online_start) VALUES ("' . $userdata['session_ip'] . '", NOW(), NOW())'; 
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		// ... und die Anzahl wird um 1 erhöht 
		$sql = 'UPDATE ' . COUNTER_COUNTER . ' SET counter_entry = counter_entry + 1 WHERE counter_date = CURDATE()'; 
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
    } 
    // Falls ja, wird ihr Datum aktualisiert 
    else
	{
		$sql = 'UPDATE ' . COUNTER_ONLINE . ' SET online_date = NOW() WHERE online_ip = "' . $userdata['session_ip'] . '"';
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
}

function _counter_result()
{
	global $db, $config, $settings, $template, $userdata, $lang;
	
	// User die 'heute' auf der Seite waren 
    $sql = 'SELECT counter_entry FROM ' . COUNTER_COUNTER . ' WHERE counter_date = CURDATE()';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
//	$row = _cached($sql, 'counter_stats_today', 1, 1800);
	$stats_day = (int) $row['counter_entry'];
	$db->sql_freeresult($result);
	
	// User die 'gestern' auf der Seite waren 
	$sql = 'SELECT counter_entry AS sum FROM ' . COUNTER_COUNTER . ' WHERE counter_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)'; 
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
//	$row = _cached($sql, 'counter_stats_yesterday', 1, 1800);
	$stats_yesterday = (int) $row['sum'];
	$db->sql_freeresult($result);
	
	// user-monat: 
	$sql = 'SELECT SUM(counter_entry) AS sum FROM ' . COUNTER_COUNTER . " WHERE counter_entry != 0 AND DATE_FORMAT(counter_date, '%m') = DATE_FORMAT(NOW(), '%m')"; 
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
//	$row = _cached($sql, 'counter_stats_month', 1, 1800);
	$stats_month = (int) $row['sum'];
	$db->sql_freeresult($result);

	// User die insgesamt die Seite besucht haben. 
    // Dazu wird die Gruppenfunktion SUM() 
    // verwendet, die alle Werte der Spalte 'Anzahl' summiert 
    $sql = 'SELECT SUM(counter_entry) AS sum FROM ' . COUNTER_COUNTER;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
//	$row = _cached($sql, 'counter_stats_total', 1, 1800);
	$stats_year = (int) $row['sum'];
	$db->sql_freeresult($result);
	
	$l_counter_head = sprintf($lang['counter_today'], $stats_day);
	$l_counter_head .= sprintf($lang['counter_yesterday'], $stats_yesterday);
	$l_counter_head .= sprintf($lang['counter_month'], $stats_month);
	$l_counter_head .= sprintf($lang['counter_year'], $stats_year);
	$l_counter_head .= sprintf($lang['counter_total'], $stats_year+$config['counter_start']);
	
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
			'STATS_COUNTER_TOTAL' 		=> sprintf($lang['counter_total'], $stats_year+$config['counter_start']),
//			'STATS_COUNTER_CACHE'		=> ( defined('CACHE') ) ? display_cache('counter_stats_total', 1) : '',
		));
	}
}

?>