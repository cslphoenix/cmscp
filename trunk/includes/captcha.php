<?php

define('IN_CMS', true);
#session_start();

#header ("Content-type: image/png");

#$_SESSION['captcha'] = '';

$width 			= 166;
$height			= 50;		// Hoehe
$line			= 0;		// Anzahl der Linien im Bild
$ellipse		= 0;		// Anzahl der Kreise
$pixel 			= 0;		// Anzahl der Punkte im Captcha
$rectangle		= 0;
$length			= 5;		// Captcha Laenge
$min_size		= 10;		// Mindestschriftgroesse
$max_size		= 14;		// Maximale Schriftgroesse
$min_angle	 	= 0;		// Neigung der Schrift in Grad
$max_angle 		= 0;		// Maximale Neigung der Schrift in Grad. 45 empfohlen.
$dist_head  	= 5;		// Mindestabstand des Zeichens nach oben
$dist_bottom	= 5;		// Mindestabstand des Zeichens nach unten

//	Auswahl an Zeichen im Captcha
//$possible = "ABCDEFGHJKLMNPQRSTUVWXZabcdefghijklmnopqrstuvwxz23456789";
$possible = "123456789";

//	Bilder erstellen
$img = imagecreate($width, $height); 	// Bitte nicht aendern!
imagecolorallocatealpha($img, 255, 255, 255, 0); // 255 255 255 127

//	Reloadbild
$reload = imagecreatefrompng('../images/reload.png');
$reloadW = imagesx($reload);
$reloadH = imagesy($reload);
$s2 = getimagesize('../images/reload.png');
$x = $width - $s2[0];
$y = $height - $s2[1];
imagecopy($img, $reload, $x, $y, 0, 0, $s2[0], $s2[1]);


$color 		= array();
$color[] 	= imagecolorallocate($img, 128, 0, 0); 		// rot
$color[]	= imagecolorallocate($img, 0, 128, 0); 		// gruen
$color[]	= imagecolorallocate($img, 0, 0, 128); 		// blau
$color[]	= imagecolorallocate($img, 0, 0, 0);		// schwarz

$ttf 		= array();
$ttf[]		= 'x-files.ttf';

function new_string($length, $possible)
{
	$string = '';
	
	while (strlen($string) < $length)
	{
		$string .= substr($possible, (mt_rand()%(strlen($possible))), 1);
	}
	return $string;
}

while ($rectangle > 0)
{
	imagerectangle($img, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $color[mt_rand(0, count($color)-1)]);
	$rectangle--;
}

while ($line > 0)
{
	imageline($img, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $color[mt_rand(0, count($color)-1)]);
	$line--;
}

while ($ellipse > 0)
{
	imageellipse($img, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $color[mt_rand(0, count($color)-1)]);
	$ellipse--;
}

while ($pixel > 0) 
{
	imagesetpixel($img, mt_rand(0, $width), mt_rand(0, $height), $color[mt_rand(0, count($color)-1)]);
	$pixel--;
}

$cp = '';
for ($i = 0; $i < $length; $i++)
{
	$angle = mt_rand($min_angle, $max_angle);
	$size  = mt_rand($min_size, $max_size);

	$xi = ($angle > '28') ? '42' : '35';
	$x = ($i == '0') ? '10' : $xi * $i;
	
	$c = $height - ($dist_bottom + $dist_head + $size);
	$string = new_string(1, $possible);
	$y = $dist_head + $size + mt_rand(0 , $c);
	
	imagettftext($img, $size, $angle, $x, $y, $color[mt_rand(0, count($color)-1)], $ttf[mt_rand(0, count($ttf)-1)], $string);
	
	$cp .= $string;
}

#$_SESSION['captcha'] = $cp;

$root_path = './../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

$confirm_id = md5($user_ip);

$sql = "SELECT * FROM " . CAPTCHA . " WHERE session_id = '" . $userdata['session_id'] . "' AND captcha_id = '" . md5($user_ip) . "'";
if ( !($result = $db->sql_query($sql)) )
{
	message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);

if ( $row )
{
	$sql = "UPDATE " . CAPTCHA . " SET captcha = '$cp' WHERE captcha_id = '$confirm_id' AND session_id = '" . $userdata['session_id'] . "'";
	if ( !$db->sql_query($sql) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
}
else
{
	$sql = "INSERT INTO " . CAPTCHA . " (captcha_id, session_id, captcha) VALUES ('$confirm_id', '" . $userdata['session_id'] . "', '$cp')";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
}

$db->sql_close();

header ("Content-type: image/png");

imagepng($img);
imagedestroy($img);

?>