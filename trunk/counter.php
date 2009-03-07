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

	// heutiges Datum auswählen
	
	$datum = getdate();
	
	$jahr = $datum['year'];
	$monat = $datum['mon'];
	$tag = $datum['mday'];
	
	// Fehlerarray erzeugen
	$errors = array();
	// Prüfen, ob Jahr, Monat und Tag ausgewählt wurden 
	if		(!isset($_POST['Jahr']) OR $_POST['Jahr'] == '0')	$errors[] = "Sie haben kein Jahr ausgew&auml;hlt."; 
	else if	(!isset($_POST['Monat']) OR $_POST['Monat'] == '0')	$errors[] = "Sie haben keinen Monat ausgew&auml;hlt."; 
	else if	(!isset($_POST['Tag']) OR $_POST['Tag'] == '0')		$errors[] = "Sie haben keinen Tag ausgew&auml;hlt."; 
	else
	{
		// Prüfen, ob ds Datum gültig ist
		if (!checkdate($_POST['Monat'], $_POST['Tag'], $_POST['Jahr']))
		{
			$errors[] = "Das Datum (".$_POST['Tag']." ".$Monatsnamen[$_POST['Monat']].". ".$_POST['Jahr'].") ist ung&uuml;ltig."; 
		}
		else
		{
			// Prüfen, ob zu dem ausgewählten Datum ein Counter existiert 
			$sql = 'SELECT COUNT(*) FROM ' . COUNTER_COUNTER_TABLE . ' 
					WHERE
						YEAR(counter_date) = '.mysql_real_escape_string($_POST['Jahr']).' AND 
						MONTH(counter_date) = '.mysql_real_escape_string($_POST['Monat']).' AND 
						DAYOFMONTH(counter_date) = '.mysql_real_escape_string($_POST['Tag']).' 
					'; 
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
			}
			if(!mysql_result($result, 0))	$errors[] = "Zu dem gew&auml;hlten Datum (".$_POST['Tag'].". ".$Monatsnamen[$_POST['Monat']]." ".$_POST['Jahr'].") existieren keine Daten."; 
		} 
	} 
	
	// Fehler ausgeben
	if (count($errors))
	{
		echo "<p style=\"font-weight:bold;\">\n"; 
		foreach($errors as $error) 
		echo $error."\n<br>\n"; 
		echo "Standardm&auml;ßig wird das heutige Datum (".$tag.". ".$Monatsnamen[$monat]." ".$jahr.") ausgew&auml;hlt.\n"; 
		echo "</p>\n"; 
	} 
	// Ansonsten Daten aus dem Formular übernehmen 
	else
	{
		$jahr = (int)mysql_real_escape_string($_POST['Jahr']); 
		$monat = (int)mysql_real_escape_string($_POST['Monat']); 
		$tag = (int)mysql_real_escape_string($_POST['Tag']);
	}

	$Monatsnamen = array(
		1 => 'Januar', 
		2 => 'Februar', 
		3 => 'März', 
		4 => 'April', 
		5 => 'Mai', 
		6 => 'Juni', 
		7 => 'Juli', 
		8 => 'August', 
		9 => 'September', 
		10 => 'Oktober', 
		11 => 'November', 
		12 => 'Dezember' 
	); 

	echo "<form name=\"User\" ". 
         " action=\"".$_SERVER['PHP_SELF']."\" ". 
         " method=\"post\" ". 
         " accept-charset=\"ISO-8859-1\">\n"; 
    echo "<div style=\"padding:0px 0px 0px 20px;\">\n"; 
    echo "<select name=\"Tag\">\n"; 
    echo "<option value=\"0\">Tag</option>\n"; 
    for($i=1;$i<=31;$i++){ 
        if($i==$tag) 
            echo "<option value=\"".$i."\" selected>".$i.".</option>\n"; 
        else 
            echo "<option value=\"".$i."\">".$i.".</option>\n"; 
    } 
    echo "</select>\n"; 
    echo "<select name=\"Monat\">\n"; 
    echo "<option value=\"0\">Monat</option>\n"; 
    for($i=1;$i<=12;$i++){ 
        if($i==$monat) 
            echo "<option value=\"".$i."\" selected>".$Monatsnamen[$i]."</option>\n"; 
        else 
            echo "<option value=\"".$i."\">".$Monatsnamen[$i]."</option>\n"; 
    } 
    echo "</select>\n"; 
    echo "<select name=\"Jahr\">\n"; 
    echo "<option value=\"0\">Jahr</option>\n"; 
    for($i=2009;$i>=2009;$i--){ 
        if($i==$jahr) 
             echo "<option value=\"".$i."\" selected>".$i."</option>\n"; 
        else 
            echo "<option value=\"".$i."\">".$i."</option>\n"; 
    } 
    echo "</select>\n"; 
    echo "<br>\n"; 
    echo "<input type=\"submit\" name=\"submit\" value=\"Daten anzeigen\">\n"; 
    echo "</div></form>\n";
	
	$sql = 'SELECT counter_entry
				FROM ' . COUNTER_COUNTER_TABLE . '
				WHERE
					YEAR(counter_date) = "'.$jahr.'" AND
					MONTH(counter_date) = "'.$monat.'" AND
					DAYOFMONTH(counter_date) = "'.$tag.'"'; 
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
	}
    $row = $db->sql_fetchrow($result);
    $anzahl_tag = $row['counter_entry']; 

    $sql = 'SELECT SUM(counter_entry)
				FROM ' . COUNTER_COUNTER_TABLE . '
				WHERE 
					YEAR(counter_date) = "'.$jahr.'" AND
					MONTH(counter_date) = "'.$monat.'"'; 
    if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
	}
    $anzahl_monat = mysql_result($result, 0); 

    $sql = 'SELECT SUM(counter_entry)
				FROM ' . COUNTER_COUNTER_TABLE . '
				WHERE YEAR(counter_date) = "'.$jahr.'"';
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
	}
    $anzahl_jahr = mysql_result($result, 0); 
	
	echo "    <table  id=\"tabelle\" cellpadding=\"0\" cellspacing=\"0\">
        <tr class=\"odd\"><td colspan=\"4\"><p>Besucherstatistik vom ".$tag.". ".$Monatsnamen[$monat]." 

".$jahr.":</p></td></tr>
        <tr><td  width=\"200\"><p>Tag (".$tag."):</p></td><td width=\"70\"></td><td><p>".$row['counter_entry']."</p></td><td 

width=\"450\"></td></tr>
        <tr><td width=\"200\"><p>Monat (".$Monatsnamen[$monat]."):</p></td><td 

width=\"70\"></td><td><p>".$anzahl_monat."</p></td><td width=\"450\"></td></tr>
        <tr><td width=\"200\"><p>Jahr (".$jahr."):</p></td><td width=\"70\"></td><td><p>".$anzahl_jahr."</p></td><td 

width=\"450\"></td></tr>
        </table>";
        
	$diagramme = array();
    $diagramme['Tag'] = array();
    $diagramme['Tag']['Name'] = "";
	$diagramme['Tag']['Hoehe'] = 20;
    $diagramme['Tag']['Breite'] = 200;
    $diagramme['Tag']['Balken'] = 200;
    $diagramme['Tag']['Stellen'] = array();
    $diagramme['Tag']['Werte'] = array();

    // Name z.B. Januar 2008
    $diagramme['Tag']['Name'] = $Monatsnamen[$monat]." ".$jahr;
    // $stellen bezeichnet die X-Werte des Diagramms
    // als Standard wird 31 festgelegt
    $stellen = 31;
    // Wurde als Monat April (4), Juni (6), 
    // September (9) oder November (11)gewählt,
    // wird die Anzahl der Stellen auf 30 begrenzt
    if (in_array($monat, array(4,6,9,11)))
	{
        $stellen = 30;
	}
    // Wurde der Februar (2) gewählt, wird geprüft,
    // ob es sich beim gewählten Jahr um ein Schaltjahr handelt 
    else if($monat == 2)
	{
		if ( $jahr%4==0 )
		{
			$stellen = 29;
		}
		else
		{
			$stellen = 28;
		}
    }
	// Für jeden Tag wird der entsprechende Counter ausgelesen
	for ($i=1; $i<=$stellen; $i++)
	{
		$sql = "SELECT counter_entry
					FROM " . COUNTER_COUNTER_TABLE . "
					WHERE
						YEAR(counter_date) = ".$jahr." AND
						MONTH(counter_date) = ".$monat." AND
						DAYOFMONTH(counter_date) = ".$i."";
        if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, '', '', __LINE__, __FILE__, $sql);
		}
        $row = mysql_fetch_assoc($result);
        // Sollte kein Counter für einen Tag gefunden werden,
        // ist $row['counter_entry'] NULL und wird durch den Cast-Operator
        // (int) zu 0, so dass nur INT's vorliegen.
        $diagramme['Tag']['Werte'][] = (int)$row['counter_entry'];
        $diagramme['Tag']['Stellen'][] = $i;
	}
	
	
    
	
	
	$diagramme['Monat'] = array();
    $diagramme['Monat']['Name'] = "";
    $diagramme['Monat']['Hoehe'] = 20;
    $diagramme['Monat']['Breite'] = 200;
    $diagramme['Monat']['Balken'] = 500;
    $diagramme['Monat']['Stellen'] = array();
    $diagramme['Monat']['Werte'] = array();

    $diagramme['Monat']['Name'] = $jahr;
	
	for ($i=1; $i<=12; $i++)
	{
		$sql = "SELECT SUM(counter_entry)
					FROM " . COUNTER_COUNTER_TABLE . "
					WHERE
						YEAR(counter_date) = '".$jahr."' AND
						MONTH(counter_date) = '".$i."'";
        $result = mysql_query($sql);
        $diagramme['Monat']['Werte'][] = (int)mysql_result($result, 0);
        $diagramme['Monat']['Stellen'][] = $i;
    }
	
	$diagramme['Jahr'] = array(); 
    $diagramme['Jahr']['Name'] = ""; 
    $diagramme['Jahr']['Hoehe'] = 20; 
    $diagramme['Jahr']['Breite'] = 200; 
    $diagramme['Jahr']['Balken'] = 500; 
    $diagramme['Jahr']['Stellen'] = array(); 
    $diagramme['Jahr']['Werte'] = array(); 

    $diagramme['Jahr']['Name'] = ""; 
    for($i=2008; $i<=2009; $i++){ 
        $sql = "SELECT 
                     SUM(counter_entry) 
                FROM 
                     " . COUNTER_COUNTER_TABLE . "
                WHERE 
                     YEAR(counter_date) = '".$i."' 
               "; 
        $result = mysql_query($sql); 
        $diagramme['Jahr']['Werte'][] = (int)mysql_result($result, 0); 
        $diagramme['Jahr']['Stellen'][] = $i; 
    }
	
//	_debug_post($diagramme);

    // Jedes Diagramm (Tag/Monat/Jahr) erzeugen
    foreach($diagramme as $diagramm => $daten)
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
        echo '
<table cellpadding="1" style="width:'.$daten['Breite'].'px; height:500px; text-align:center; background-color:#aaa; border:solid 1px black; font-size:10px; margin:0px auto;">';
		// Diagrammname ausgeben
        echo '
<tr>
	<td colspan="'.count($daten['Werte']).'" style="height:20px;">'.$daten['Name'].'</td>
</tr>
<tr>';
		
		// Werte - also Balken - ausgeben
		foreach($verhaeltnis as $key => $wert)
		{
			// Breite einer Zelle berechnen,
			// Ein Balken ist halb so breit wie eine Zelle,
			// deshalb das '/2'
echo	'
<td style="vertical-align:bottom; height:10px; width:' . floor( $daten['Breite'] / count($daten['Werte']) ).'px;">
	<div style="margin:auto; background-color:red; height:' . floor($daten['Breite']*$wert).'px; width:'.floor(($daten['Breite']/2)/count($daten['Werte'])).'px" title="'.$daten['Werte'][$key].'"></div>
</td>';
		}
        echo " </tr>";

        echo " <tr>";
        // Stellen - also Balkenzuordnung - ausgeben
        foreach($daten['Stellen'] as $stelle){
             echo "  <td style=\"vertical-align:middle; border:solid 1px black; border-width:1px 1px 0px 1px; height:80px;\">";
			 echo $stelle;
             echo "  </td>\n";
        }

        echo " </tr>\n";
        echo "</table>\n";
    
} 

//$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>