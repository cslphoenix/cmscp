<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//
//	Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

$page_title = $lang['Index'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'counter_body.tpl'));


	//	heutiges Datum auswählen
	$day	= date("d", time());	// Heutiger Tag: z. B. "1"
	$month	= date("n", time());	// Heutiger Monat
	$year	= date("Y", time());	// Heutiges Jahr
//	$days	= date("t");			// Anzahl der Tage im Monat: z. B. "31"
	//	Fehlerarray erzeugen
	$errors	= array();
	
	if (isset($HTTP_POST_VARS['submit']))
	{
		// Prüfen, ob Jahr, Monat und Tag ausgewählt wurden 
		if (!isset($HTTP_POST_VARS['year']) || $HTTP_POST_VARS['year'] == '0')
		{
			$errors[] = "Sie haben kein Jahr ausgew&auml;hlt.";
		}
		else if (!isset($HTTP_POST_VARS['month']) || $HTTP_POST_VARS['month'] == '0')
		{
			$errors[] = "Sie haben keinen Monat ausgew&auml;hlt.";
		}
		else if (!isset($HTTP_POST_VARS['day']) || $HTTP_POST_VARS['day'] == '0')
		{
			$errors[] = "Sie haben keinen Tag ausgew&auml;hlt."; 
		}
		else
		{
			// Prüfen, ob ds Datum gültig ist
			if (!checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']))
			{
				$errors[] = "Das Datum ist ung&uuml;ltig."; 
			}
			else
			{
				// Prüfen, ob zu dem ausgewählten Datum ein Counter existiert 
				$sql = 'SELECT COUNT(*) FROM ' . COUNTER_COUNTER . ' 
						WHERE
							YEAR(counter_date)			= ' . ($HTTP_POST_VARS['year']) . ' AND 
							MONTH(counter_date)			= ' . ($HTTP_POST_VARS['month']) . ' AND 
							DAYOFMONTH(counter_date)	= ' . ($HTTP_POST_VARS['day']) . ' 
						'; 
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
				}
				
				if(!mysql_result($result, 0))
				{
					$errors[] = "Zu dem gew&auml;hlten Datum existieren keine Daten.";
				}
			}
		}
		
		if ($errors)
		{
			foreach($errors as $error)
			echo $error;
		} 
		else
		{
			$year	= ($HTTP_POST_VARS['year']); 
			$month	= ($HTTP_POST_VARS['month']); 
			$day	= ($HTTP_POST_VARS['day']);
		}
	}
	
	$Monatsnamen = array(
		'1'		=> 'Januar',
		'2'		=> 'Feber',
		'3'		=> 'M&auml;rz',
		'4'		=> 'April',
		'5'		=> 'Mai',
		'6'		=> 'Juni',
		'7'		=> 'Juli',
		'8'		=> 'August',
		'9'		=> 'September',
		'10'	=> 'Oktober',
		'11'	=> 'November',
		'12'	=> 'Dezember'
	); 

	$select_day = '<select class="postselect" name="day">';
	$select_day .= '<option disabled value="0">' . $lang['day'] . '</option>'; 
	for ($i=1; $i<=31; $i++)
	{
		if ($i < 10)
		{
			$i = '0'.$i;
		}
		$selected = ( $i == $day ) ? ' selected="selected" ' : '';
		$select_day .= '<option value="' . $i . '"' . $selected . '>' . $i . '&nbsp;</option>';
	}
	$select_day .= '</select>';
	
	$select_month = '<select class="postselect" name="month">';
	$select_month .= '<option disabled value="0">' . $lang['month'] . '</option>'; 
	for ( $i=1; $i<=12; $i++ )
	{
		$selected = ( $i == $month ) ? ' selected="selected" ' : '';
		$select_month .= '<option value="' . $i . '"' . $selected . '>' . $Monatsnamen[$i] . '&nbsp;</option>';
	}
	$select_month .= '</select>';
	
	$select_year = '<select class="postselect" name="year">';
	$select_year .= '<option disabled value="0">' . $lang['year'] . '</option>'; 
	for ( $i=2008; $i<$year+3; $i++ )
	{
		$selected = ( $i == $year ) ? ' selected="selected" ' : '';
		$select_year .= '<option value="' . $i . '"' . $selected . '>' . $i . '&nbsp;</option>';
	}
	$select_year .= '</select>';
	
	$sql = 'SELECT counter_entry
				FROM ' . COUNTER_COUNTER . '
				WHERE
					YEAR(counter_date) = "'.$year.'" AND
					MONTH(counter_date) = "'.$month.'" AND
					DAYOFMONTH(counter_date) = "'.$day.'"';
	$row_day = _cached($sql, 'counter_day_' . $day . $month . $year, 1);
	$anzahl_tag = (int) $row_day['counter_entry'];
	
    $sql = 'SELECT SUM(counter_entry) AS sum
				FROM ' . COUNTER_COUNTER . '
				WHERE 
					YEAR(counter_date) = "'.$year.'" AND
					MONTH(counter_date) = "'.$month.'"'; 
	$row_month = _cached($sql, 'counter_month_' . $day . $month . $year, 1);
	$anzahl_monat = (int) $row_month['sum'];

    $sql = 'SELECT SUM(counter_entry) AS sum
				FROM ' . COUNTER_COUNTER . '
				WHERE YEAR(counter_date) = "'.$year.'"';
	$row_year = _cached($sql, 'counter_year_' . $day . $month . $year, 1);
	$anzahl_jahr = (int) $row_year['sum'];
	
	$diagramme = array();
    $diagramme['Tag'] = array();
    $diagramme['Tag']['Name'] = $Monatsnamen[$month]." " . $year;
	$diagramme['Tag']['Hoehe'] = 20;
    $diagramme['Tag']['Breite'] = 200;
    $diagramme['Tag']['Balken'] = 200;
    $diagramme['Tag']['Stellen'] = array();
    $diagramme['Tag']['Werte'] = array();

    // $stellen bezeichnet die X-Werte des Diagramms
    // als Standard wird 31 festgelegt
    $stellen = 31;
    // Wurde als Monat April (4), Juni (6), 
    // September (9) oder November (11)gewählt,
    // wird die Anzahl der Stellen auf 30 begrenzt
    if ( in_array($month, array(4,6,9,11)) )
	{
        $stellen = 30;
	}
    // Wurde der Februar (2) gewählt, wird geprüft,
    // ob es sich beim gewählten Jahr um ein Schaltjahr handelt 
    else if ( $month == 2 )
	{
		if ( $year%4==0 )
		{
			$stellen = 29;
		}
		else
		{
			$stellen = 28;
		}
    }
	// Für jeden Tag wird der entsprechende Counter ausgelesen
	for ( $i=1; $i<=$stellen; $i++ )
	{
		$sql = "SELECT counter_entry
					FROM " . COUNTER_COUNTER . "
					WHERE
						YEAR(counter_date) = " . $year." AND
						MONTH(counter_date) = " . $month." AND
						DAYOFMONTH(counter_date) = " . $i."";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
		}
        $row = $db->sql_fetchrow($result);
		
		
        // Sollte kein Counter für einen Tag gefunden werden,
        // ist $row['counter_entry'] NULL und wird durch den Cast-Operator
        // (int) zu 0, so dass nur INT's vorliegen.
        $diagramme['Tag']['Werte'][] = (int) $row['counter_entry'];
        $diagramme['Tag']['Stellen'][] = $i;
	}
	
	$diagramme['Monat'] = array();
    $diagramme['Monat']['Name'] = $year;
    $diagramme['Monat']['Hoehe'] = 20;
    $diagramme['Monat']['Breite'] = 200;
    $diagramme['Monat']['Balken'] = 500;
    $diagramme['Monat']['Stellen'] = array();
    $diagramme['Monat']['Werte'] = array();

	for ($i=1; $i<=12; $i++)
	{
		$sql = "SELECT SUM(counter_entry) AS sum
					FROM " . COUNTER_COUNTER . "
					WHERE
						YEAR(counter_date) = '" . $year."' AND
						MONTH(counter_date) = '" . $i."'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$diagramme['Monat']['Werte'][] = (int) $row['sum'];
		$diagramme['Monat']['Stellen'][] = $i;
    }
	
	$diagramme['Jahr'] = array(); 
    $diagramme['Jahr']['Name'] = "Übersicht"; 
    $diagramme['Jahr']['Hoehe'] = 20; 
    $diagramme['Jahr']['Breite'] = 200; 
    $diagramme['Jahr']['Balken'] = 500; 
    $diagramme['Jahr']['Stellen'] = array(); 
    $diagramme['Jahr']['Werte'] = array(); 

    $diagramme['Jahr']['Name'] = ""; 
    
	for ( $i=2009; $i<=2009; $i++ )
	{
		$sql = 'SELECT
					SUM(counter_entry) AS sum
				FROM 
                     ' . COUNTER_COUNTER . '
                WHERE 
                     YEAR(counter_date) = "'.$i.'"';
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
        $diagramme['Jahr']['Werte'][] = (int) $row['sum'];
        $diagramme['Jahr']['Stellen'][] = $i; 
    }
	
    // Jedes Diagramm (Tag/Monat/Jahr) erzeugen
    foreach ($diagramme as $diagramm => $daten)
	{
		// Maximalwert aus den Daten ermitteln, der Maximalwert
		// bekommt später die zuvor angegebene maximale Balkenhöhe in px
		$maximalwert = (max($daten['Werte']) != 0) ? max($daten['Werte']) : 1;
		
		// Verhältnis aller Daten zum Maximalwert berechnen
		// Jeder Wert erhält dann als Höhe einen Bruchteil der
		// maximalen Balkenhöhe
		$verhaeltnis = array();
		foreach ($daten['Werte'] as $key => $wert)
		{
			$verhaeltnis[$key] = $wert/$maximalwert;
		}
		
		// Tabelle erzeugen
		// Diagrammname ausgeben
		$template->assign_block_vars('table_row', array(
			'COL'		=> count($daten['Werte']),
			'VALUE'		=> $daten['Name'],
		));
       
		// Werte - also Balken - ausgeben
		foreach($verhaeltnis as $key => $wert)
		{
			$template->assign_block_vars('table_row.balken_row', array(
				'WERT1'		=> floor( $daten['Breite'] / count($daten['Werte']) ),
				'WERT2'		=> floor( $daten['Breite'] * $wert),
				'WERT3'		=> '100%',
				'WERT4'		=> $daten['Werte'][$key],
				
			));
			// Breite einer Zelle berechnen,
			// Ein Balken ist halb so breit wie eine Zelle,
			// deshalb das '/2'
		}
		
        // Stellen - also Balkenzuordnung - ausgeben
		foreach($daten['Stellen'] as $stelle)
		{
			$template->assign_block_vars('table_row.stellen_row', array(
				'STELLE'		=> $stelle,
			));
		}
		
} 

	$template->assign_vars(array(
		'L_DAY'				=> $lang['day'],
		'L_MONTH'			=> $lang['month'],
		'L_YEAR'			=> $lang['year'],
		
		'L_STATS_FROM'		=> sprintf($lang['counter_stats_from'], $day, $month, $year),
		
		'SELECT_DAY'		=> $select_day,
		'SELECT_MONTH'		=> $select_month,
		'SELECT_YEAR'		=> $select_year,
		
		'COUNT_DAY'			=> $anzahl_tag,
		'COUNT_MONTH'		=> $anzahl_monat,
		'COUNT_YEAR'		=> $anzahl_jahr,
		
		'S_COUNTER_ACTION'	=> append_sid("counter.php"),
	));

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>