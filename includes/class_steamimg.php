<?php

	

	$xml = simplexml_load_file('http://steamcommunity.com/id/cslphoenix?xml=1');
	
	$Schriftarten = array("x-files.ttf");
    $Bilddatei = imagecreatefrompng("../images/steam.png");
    $TextFarbe1 = imagecolorallocate( $Bilddatei, 0, 125, INT);
    $TextFarbe2 = imagecolorallocate( $Bilddatei, 130, 70, 90 );
    $TextFarbe3 = imagecolorallocate( $Bilddatei, 180, 90, 190 );

    $user = utf8_decode($xml->steamID);
    $status = utf8_decode($xml->onlineState);
    
    imagettftext($Bilddatei, 10, 15, 3, 24, $TextFarbe1, $Schriftarten[0], "STEAM");
    imagettftext($Bilddatei, 10, 0, 77, 24, $TextFarbe2, $Schriftarten[0], "User:");
    imagettftext($Bilddatei, 10, 0, 144, 24, $TextFarbe2, $Schriftarten[0], $user);
    imagettftext($Bilddatei, 10, 0, 77, 55, $TextFarbe2, $Schriftarten[0], "Status:");
    imagettftext($Bilddatei, 10, 0, 144, 55, $TextFarbe2, $Schriftarten[0], $status);
    
    $img = '../images/reload.png';
 #   imagecopyresampled($img, $Bilddatei, 12, 0, 3, 55);
	
	header('Content-type: image/png');
    
    imagepng($Bilddatei);

    imagedestroy($Bilddatei);
	
?>
<!--<a href="http://steam064.appspot.com"><img border="0" src="http://steam064.appspot.com/img/STEAM_0:1:1369754"/></a>-->
<?php
function createcard($gp1, $gp2, $gp3, $gp4) {
      header('Content-type: image/png');
      $file = 'images/rohling.png';
      $size = getimagesize($file);
      $width = $size[0];
      $height = $size[1];
      $old_picture = imagecreatefrompng($file);
      $new_picture = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_picture, $old_picture, 0, 0, 0, 0, $width, $height, $width, $height);
      $transition = 100;
      // Einfügen der Gameboxes
      $gameboxdest_x = 8;
      $gp = array();
      $gp[0] = 'gameboxes/platzhalterflag.jpg';
      $gp[] = 'gameboxes/'.$gp1;
      $gp[] = 'gameboxes/'.$gp2;
      $gp[] = 'gameboxes/'.$gp3;
      $gp[] = 'gameboxes/'.$gp4;
      for($i = 1; $i<=4; $i++) {
           $gamebox = imagecreatefrompng($gp[$i]);
          $gamebox_width = imagesx($gamebox);
          $gamebox_height = imagesy($gamebox);
         if ($i == 1) {
              $gameboxdest_x = $gameboxdest_x;
         } else {
             $gameboxdest_x = $gameboxdest_x + 89;
         }
          $gameboxdest_y = 10;
         imagecopymerge($new_picture, $gamebox, $gameboxdest_x, $gameboxdest_y, 0, 0, $gamebox_width, $gamebox_height, $transition);
      }
      /* Einfügen der Flagge
      $flag = imagecreatefrompng($flag);
      $flag_width = imagesx($flag);
      $flag_height = imagesy($flag);
      $flag_x = $flag_x + 89;
      $flag_y = 10;
      imagecopymerge($new_picture, $flag, $flag_x, $flag_y, 0, 0, $flag_width, $flag_height, $transition);*/
      imagejpeg($new_picture);
}?>