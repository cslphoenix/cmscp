<?php
$heute = date("d-m-Y"); // Format: z. B. 01-09-2002
//$heute_a = date("j.n.Y"); // anderes Format: z. B. 1.9.2002
$heute_d = date("j");
$today = date("d", time()); // Heutiger Tag: z. B. "1"
$tage_im_monat = date("t"); // Anzahl der Tage im Monat: z. B. "31"
$tag_der_woche = date("w"); // Welcher Tag in der Woch: z. B. "0 / Sonntag"
$monat = date("n", time());
$jahr = date("Y", time());
$erster=date("w", mktime(0,0,0,$monat,1,$jahr)); // Der erste Tag im Monat: z. B. "5 / Freitag"
$arr_woche_kurz = array("So","Mo","Di","Mi","Do","Fr","Sa");

$monate = array(
	'1'		=> 'Januar',
	'2'		=> 'Februar',
	'3'		=> 'März',
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

$month = $monate[$monat];

echo $month;
// wochenstart
// 0=Sonntag; 1=Montag; 2=Dienstag; 3=Mittwoch; 4=Donnerstag; 5=Freitag; 6=Samstag
$ws = 1; 
// "woche beginnt mit" - array verschiebung
$edmk = $arr_woche_kurz[$erster];
$wbmk = $arr_woche_kurz;
for ($i=0;$i<$ws;$i++){
$wechsel = array_shift($wbmk);
$wbmk[] = $wechsel;
}
$wbmk_wechsel = array_flip($wbmk);
?> 
<table border="0" cellspacing="1" width="150" height="150">
<tr>
<?php for($i=0;$i<7;$i++){echo "<td>" . $wbmk[$i]."</td>";} ?>
</tr>
<tr> 
<?php
// berechnung der monatstabelle
// zuerst die unbenutzten tage

for($i=0;$i<$wbmk_wechsel[$edmk];$i++){echo "<td></td>";} 

// ab hier benutzte tage
$wcs = $wbmk_wechsel[$edmk];
for ($i=1;$i<$tage_im_monat+1;$i++)
{
if ($i == $today) { echo "<td style=\"color:#F00\"><b>$i</b></td>";}
else
echo "<td>" . $i."</td>";	
if ($wcs < 7){	$wcs++;}
if ($wcs == 7){echo "</tr><tr>";$wcs = 0;}

}

for ($wcs; $wcs<7;$wcs++)
{

echo "<td></td>";
}
?>