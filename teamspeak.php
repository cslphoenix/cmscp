<?php

define('IN_CMS', true); 

$root_path = './'; // <-- 
include($root_path . 'common.php'); 

$userdata = session_pagestart($user_ip, PAGE_TEAMSPEAK); 
init_userprefs($userdata); 

$page_title = $lang['teamspeak'];
include($root_path . 'includes/page_header.php');

include($root_path . 'includes/teamspeak/ts_config.php');


$tss2info->getInfo();

include($root_path . 'includes/teamspeak/ts_functions.php');

$tsv_array_1 = array(" ","-","(",")","[","]","{","}","&"); // Das wird gesucht..
$tsv_array_2 = array("_","_","","","","","","",""); // ..und ersetzt mit diesem
$tsv_count = count($tsv_array_1);

for ($x=0;$x<$tsv_count;$x++)
{
	$tsv_username = trim(str_replace($tsv_array_1[$x],$tsv_array_2[$x],$tsv_username));
}
$tsv_username = trim($tsv_username);

$counter = 0;
$channelcounter = count($tss2info->channelList) - 1;

//
//	Channel Sortierung
//
unset($s1);
unset($s2);
unset($v);
$s1 = array();
$s2 = array();

foreach ($tss2info->channelList as $v)
{
	$s1[] = $v['channelorder'];    // Sortierung nach Order
}
foreach ($tss2info->channelList as $v)
{
	$s2[] = $v['channelname'];    // Wenn Order gleich Sortierung nach Name
}

array_multisort($s1, SORT_ASC, $s2, SORT_ASC, $tss2info->channelList); // ASC = auf-, DESC = absteigend

//
//	Subchannel nach Player durchsuchen
//
$tss2info_channellist = $tss2info->channelList;

for ($i=0;$i<count($tss2info_channellist);$i++)
{
	if (intval($tss2info_channellist[$i]['channelparent']) > 0 AND intval($tss2info_channellist[$i]['channelcurrentplayers']) > 0)
	{
		$subchannels[$tss2info_channellist[$i]['channelparent']] = 1;
	}
}

//
//	ChannelList
//
foreach($tss2info->channelList as $channelInfo)
{
	if ($channelInfo['channelid'] != "id" AND !in_array($channelInfo['channelid'],$tss2info->TS_hide_channels))
	{
		if ($channelInfo['channelparent'] < "0")
		{
			//
			//	Channelanzeigen
			//
			if ($tss2info->TS_channel_anzeigen == 1 AND ($tss2info->TS_leerchannel_anzeigen == 1 OR ($tss2info->TS_leerchannel_anzeigen == 0 AND (trim($channelInfo['channelcurrentplayers']) > 0 OR (intval($subchannels[$channelInfo['channelid']]) == 1)))))
			{
				//
				//	Mouseover
				//
				$channel_mouseover1 = "Join als: ".$tsv_username." | Channelname: ".$channelInfo['channelname']." | Topic: ".$channelInfo['channeltopic']." | Maximale User: ".$channelInfo['channelmaxplayers']." | Derzeitige User: ".$channelInfo['channelcurrentplayers']." | Codec: ".$channelInfo['channelcodec']."";
				$channel_mouseover2 = "Kein Joinen möglich | Channelname: ".$channelInfo['channelname']." | Topic: ".$channelInfo['channeltopic']." | Maximale User: ".$channelInfo['channelmaxplayers']." | Derzeitige User: ".$channelInfo['channelcurrentplayers']." | Codec: ".$channelInfo['channelcodec']."";
				$channel_mouseover3 = "<b>Join als:</b> ".$tsv_username."<br><br><b>Channelname:</b><br>".$channelInfo['channelname']."<br><br><b>Topic:</b><br>".$channelInfo['channeltopic']."<br><br><b>Maximale User:</b> ".$channelInfo['channelmaxplayers']."<br><b>Derzeitige User:</b> ".$channelInfo['channelcurrentplayers']."<br><br><b>Codec:</b><br>".$channelInfo['channelcodec']."";
				$channel_mouseover4 = "<b>Kein Joinen möglich</b><br><br><b>Channelname:</b><br>".$channelInfo['channelname']."<br><br><b>Topic:</b><br>".$channelInfo['channeltopic']."<br><br><b>Maximale User:</b> ".$channelInfo['channelmaxplayers']."<br><b>Derzeitige User:</b> ".$channelInfo['channelcurrentplayers']."<br><br><b>Codec:</b><br>".$channelInfo['channelcodec']."";
				
				//---> Passwortabfrage <---\\ Anfang
				if ($channelInfo['channelpasswort'] == "0")
				{
					if ($tss2info->TS_overlib_mouseover == 1)
					{
						$channel_mouseover3 = "onmouseover=\"return overlib('".str_replace("'","\'",$channel_mouseover3)."', WIDTH, 200);\"  onmouseout=\"return nd();\"";
					}
					else
					{
						$channel_mouseover3 = "title=\"".$channel_mouseover1."\"";
					}
					
					$channellink = "<a class=\"channellink\" href=\"teamspeak://".$tss2info->serverAddress.":".$tss2info->serverUDPPort."/?channel=".rawurlencode($channelInfo['channelname'])."?password=".$tss2info->serverPasswort."?nickname=".rawurlencode($tsv_username)."\" ".$channel_mouseover3.">".$channelInfo['channelname']."</a>";
				}
				else
				{
					if ($tss2info->TS_overlib_mouseover == 1)
					{
						$channel_mouseover4 = "style=\"cursor: help;\" onmouseover=\"return overlib('".str_replace("'","\'",$channel_mouseover4)."', WIDTH, 200);\"  onmouseout=\"return nd();\"";
					}
					else
					{
						$channel_mouseover4 = "title=\"".$channel_mouseover2."\"";
					}
					$channellink = "<span ".$channel_mouseover4.">".$channelInfo['channelname']."</span>";
				}
				//---> Passwortabfrage <---\\ Ende
  
        //---> Channelflags <---\\ Anfang
        if($tss2info->TS_channelflags_ausgabe == 1) $channellink .= ' ('.TS_channelflags($channelInfo['channelflags']).')';
        //---> Channelflags <---\\ Ende

        //---> Channel <---\\ Anfang
        $ts_viewer_ausgabe .= '
        <tr>
          <td valign="top">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td class="channel" width="25" valign="top" nowrap><img width="5" height="13" src="images/teamspeak/blank.gif" border="0" alt=""><img src="images/teamspeak/channel.gif" width="20" height="13" border="0" alt=""></td>
                <td class="channel" width="100%" valign="top" nowrap>&nbsp;'.$channellink.'</td>';

        //---> Debug Modus <---\\ Anfang
        if($tss2info->TS_debug_modus == 1) {
          $ts_viewer_ausgabe .= "\n                <td class=\"player\" width=\"1500\" valign=\"top\" nowrap>&nbsp;&nbsp;<b>channelid:</b> ".$channelInfo['channelid']."&nbsp;&nbsp;<b>channelcodec:</b> ".$channelInfo['channelcodec']."&nbsp;&nbsp;<b>channelparent:</b> ".$channelInfo['channelparent']."&nbsp;&nbsp;<b>channelorder:</b> ".$channelInfo['channelorder']."&nbsp;&nbsp;<b>channelmaxplayers:</b> ".$channelInfo['channelmaxplayers']."&nbsp;&nbsp;<b>channelname:</b> ".$channelInfo['channelname']."&nbsp;&nbsp;<b>channelflags:</b> ".$channelInfo['channelflags']."&nbsp;&nbsp;<b>channelpasswort:</b> ".$channelInfo['channelpasswort']."&nbsp;&nbsp;<b>channeltopic:</b> ".$channelInfo['channeltopic']."&nbsp;&nbsp;<b>channelcurrentplayers:</b> ".$channelInfo['channelcurrentplayers']."</td>";
        }
        //---> Debug Modus <---\\ Ende

        $ts_viewer_ausgabe .= '
              </tr>
            </table>
          </td>
        </tr>';
        //---> Channel <---\\ Ende
      }
      //---> Channelanzeigen <---\\ Ende

      $counter_player = 0; // Playercounter beginnen

      //---> Player Sortierung <---\\ Anfang
      unset($s1);
      unset($s2);
      unset($v);
      $s1 = array();
      $s2 = array();
      foreach($tss2info->playerList as $v) $s1[] = $v['userstatus'];    // Sortierung nach Order
      foreach($tss2info->playerList as $v) $s2[] = $v['playername'];    // Wenn Order gleich Sortierung nach Name
      array_multisort($s1, SORT_DESC, $s2, SORT_ASC, $tss2info->playerList); // ASC = auf-, DESC = absteigend
      //---> Player Sortierung <---\\ Ende

      //---> PlayerList <---\\ Anfang
      foreach($tss2info->playerList as $playerInfo) {
        if($playerInfo['channelid'] == $channelInfo['channelid']) {
          $playercounter1 = $counter_player+1;
          $playercounter2 = $channelInfo['channelcurrentplayers'];
          $player_mouse_over1 = "".$playerInfo['playername']." | Online seit: ".TS_totaltime($playerInfo['totaltime'])." | Idle seit: ".TS_idletime($playerInfo['idletime'])." | Ping: ".$playerInfo['pingtime']." ms";
          $player_mouse_over2 = "<b>".$playerInfo['playername']."</b><br><br><b>Online seit:</b><br>".TS_totaltime($playerInfo['totaltime'])."<br><br><b>Idle seit:</b><br>".TS_idletime($playerInfo['idletime'])."<br><br><b>Ping:</b> ".$playerInfo['pingtime']." ms";
          if($tss2info->TS_overlib_mouseover == 1) $player_mouse_over = "style=\"cursor: help;\" onmouseover=\"return overlib('".str_replace("'","\'",$player_mouse_over2)."', WIDTH, 150);\" onmouseout=\"return nd();\"";
          else $player_mouse_over = "title=\"".$player_mouse_over1."\"";

          //---> Player <---\\ Anfang
          $ts_viewer_ausgabe .= '
        <tr>
          <td>
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>';
              unset($userstatus);
              if($tss2info->TS_userstatus_ausgabe == 1) $userstatus = ' ('.TS_userstatus($playerInfo['userstatus']).TS_privileg($playerInfo['privileg'],$playerInfo['attribute']).')';
              if($tss2info->TS_channel_anzeigen == 1) {
              $ts_viewer_ausgabe .= '
                <td width="40" nowrap><img width="5" height="16" src="images/teamspeak/blank.gif" border="0" alt=""><img src="images/teamspeak/blank.gif" width="15" height="16" border="0" alt=""><img src="images/teamspeak/'.TS_attribute($playerInfo['attribute']).'" width="20" height="16" border="0" alt=""></td>';
              } else {
              $player_without_channel[] = $playerInfo;
              $ts_viewer_ausgabe .= '
                <td width="20" nowrap><img src="images/teamspeak/'.TS_attribute($playerInfo['attribute']).'" width="20" height="16" border="0" alt=""></td>';
              }
              $ts_viewer_ausgabe .= '
                <td class="player" width="100%">&nbsp;<span '.$player_mouse_over.'>'.$playerInfo['playername'].$userstatus.'</span></td>
              </tr>
            </table>
          </td>
        </tr>';
          //---> Player <---\\ Ende

          $counter_player++; // Playercounter hochzählen

        }
      }
      //---> PlayerList <---\\ Ende

      //---> Subchannel Sortierung <---\\ Anfang
      unset($s1);
      unset($s2);
      $s1 = array();
      $s2 = array();
      foreach($tss2info->channelList as $v) $s1[] = $v['channelorder'];    // Sortierung nach Order
      foreach($tss2info->channelList as $v) $s2[] = $v['channelname'];    // Wenn Order gleich Sortierung nach Name
      array_multisort($s1, SORT_ASC, $s2, SORT_ASC, $tss2info->channelList); // ASC = auf-, DESC = absteigend
      unset($v);
      //---> Subchannel Sortierung <---\\ Ende

      //---> SubchannelList <---\\ Anfang
      foreach($tss2info->channelList as $subchannelInfo) {
        if($subchannelInfo['channelparent'] == $channelInfo['channelid'] AND !in_array($subchannelInfo['channelid'],$tss2info->TS_hide_channels) AND ($tss2info->TS_leerchannel_anzeigen == 1 OR ($tss2info->TS_leerchannel_anzeigen == 0 AND trim($subchannelInfo['channelcurrentplayers']) > 0))) {
          if($tss2info->TS_channel_anzeigen == 1) {
            $subchannel_mouseover1 = "Join als: ".$tsv_username." | Channelname: ".$subchannelInfo['channelname']." | Subchannel von: ".$channelInfo['channelname']." | Topic: ".$subchannelInfo['channeltopic']." | Maximale User: ".$subchannelInfo['channelmaxplayers']." | Derzeitige User: ".$subchannelInfo['channelcurrentplayers']." | Codec: ".$subchannelInfo['channelcodec']."";
            $subchannel_mouseover2 = "Kein Joinen möglich | Channelname: ".$subchannelInfo['channelname']." | Subchannel von: ".$channelInfo['channelname']." | Topic: ".$subchannelInfo['channeltopic']." | Maximale User: ".$subchannelInfo['channelmaxplayers']." | Derzeitige User: ".$subchannelInfo['channelcurrentplayers']." | Codec: ".$subchannelInfo['channelcodec']."";
            $subchannel_mouseover3 = "<b>Join als:</b> ".$tsv_username."<br><br><b>Channelname:</b><br>".$subchannelInfo['channelname']."<br><b>Subchannel von:</b><br>".$channelInfo['channelname']."<br><br><b>Topic:</b><br>".$subchannelInfo['channeltopic']."<br><br><b>Maximale User:</b> ".$subchannelInfo['channelmaxplayers']."<br><b>Derzeitige User:</b> ".$subchannelInfo['channelcurrentplayers']."<br><br><b>Codec:</b><br>".$subchannelInfo['channelcodec']."";
            $subchannel_mouseover4 = "<b>Kein Joinen möglich</b><br><br><b>Channelname:</b><br>".$subchannelInfo['channelname']."<br><b>Subchannel von:</b><br>".$channelInfo['channelname']."<br><br><b>Topic:</b><br>".$subchannelInfo['channeltopic']."<br><br><b>Maximale User:</b> ".$subchannelInfo['channelmaxplayers']."<br><b>Derzeitige User:</b> ".$subchannelInfo['channelcurrentplayers']."<br><br><b>Codec:</b><br>".$subchannelInfo['channelcodec']."";
            if($channelInfo['channelpasswort'] == "0") {
              if($tss2info->TS_overlib_mouseover == 1) $subchannel_mouseover3 = "onmouseover=\"return overlib('".str_replace("'","\'",$subchannel_mouseover3)."', WIDTH, 200);\"  onmouseout=\"return nd();\"";
              else $subchannel_mouseover3 = "title=\"".$channel_mouseover1."\"";
              $subchannellink = "<a class=\"channellink\" href=\"teamspeak://".$tss2info->serverAddress.":".$tss2info->serverUDPPort."/?channel=".rawurlencode($subchannelInfo['channelname'])."?password=".$tss2info->serverPasswort."?nickname=".rawurlencode($tsv_username)."\" ".$subchannel_mouseover3.">".$subchannelInfo['channelname']."</a>";
            } else {
              if($tss2info->TS_overlib_mouseover == 1) $subchannel_mouseover4 = "style=\"cursor: help;\" onmouseover=\"return overlib('".str_replace("'","\'",$subchannel_mouseover4)."', WIDTH, 200);\"  onmouseout=\"return nd();\"";
              else $subchannel_mouseover4 = "title=\"".$channel_mouseover2."\"";
              $subchannellink = "<span ".$subchannel_mouseover4.">".$subchannelInfo['channelname']."</span>";
            }
            //---> Channel <---\\ Anfang
            $ts_viewer_ausgabe .= '
        <tr>
          <td valign="top">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td class="channel" width="40" valign="top" nowrap><img width="5" height="13" src="images/teamspeak/blank.gif" border="0" alt=""><img width="15" height="13" src="images/teamspeak/blank.gif" border="0" alt=""><img src="images/teamspeak/channel.gif" width="20" height="13" border="0" alt=""></td>
                <td class="channel" width="100%" valign="top" nowrap>&nbsp;'.$subchannellink.'</td>';
            //---> Debug Modus <---\\ Anfang
            if($tss2info->TS_debug_modus == 1) {
              $ts_viewer_ausgabe .= "\n                <td class=\"player\" width=\"1500\" valign=\"top\" nowrap>&nbsp;&nbsp;<b>channelid:</b> ".$subchannelInfo['channelid']."&nbsp;&nbsp;<b>channelcodec:</b> ".$subchannelInfo['channelcodec']."&nbsp;&nbsp;<b>channelparent:</b> ".$subchannelInfo['channelparent']."&nbsp;&nbsp;<b>channelorder:</b> ".$subchannelInfo['channelorder']."&nbsp;&nbsp;<b>channelmaxplayers:</b> ".$subchannelInfo['channelmaxplayers']."&nbsp;&nbsp;<b>channelname:</b> ".$subchannelInfo['channelname']."&nbsp;&nbsp;<b>channelflags:</b> ".$subchannelInfo['channelflags']."&nbsp;&nbsp;<b>channelpasswort:</b> ".$subchannelInfo['channelpasswort']."&nbsp;&nbsp;<b>channeltopic:</b> ".$subchannelInfo['channeltopic']."&nbsp;&nbsp;<b>channelcurrentplayers:</b> ".$subchannelInfo['channelcurrentplayers']."</td>";
            }
            //---> Debug Modus <---\\ Ende
            $ts_viewer_ausgabe .= '
              </tr>
            </table>
          </td>
        </tr>';
            //---> Channel <---\\ Ende
          }
          $counter_player = 0;

          //---> Sortierung <---\\ Anfang
          unset($s1);
          unset($s2);
          unset($v);
          $s1 = array();
          $s2 = array();
          foreach($tss2info->playerList as $v) $s1[] = $v['userstatus'];    // Sortierung nach Order
          foreach($tss2info->playerList as $v) $s2[] = $v['playername'];    // Wenn Order gleich Sortierung nach Name
          array_multisort($s1, SORT_DESC, $s2, SORT_ASC, $tss2info->playerList); // ASC = auf-, DESC = absteigend
          //---> Sortierung <---\\ Ende

          //---> SubPlayerList <---\\ Anfang
          foreach($tss2info->playerList as $playerInfo) {
            if($playerInfo['channelid'] == $subchannelInfo['channelid'] && $subchannelInfo['channelparent'] == $channelInfo['channelid']) {
              $playercounter1 = $counter_player+1;
              $playercounter2 = $subchannelInfo['channelcurrentplayers'];
              $player_mouse_over1 = "".$playerInfo['playername']." | Online seit: ".TS_totaltime($playerInfo['totaltime'])." | Idle seit: ".TS_idletime($playerInfo['idletime'])." | Ping: ".$playerInfo['pingtime']." ms";
              $player_mouse_over2 = "<b>".$playerInfo['playername']."</b><br><br><b>Online seit:</b><br>".TS_totaltime($playerInfo['totaltime'])."<br><br><b>Idle seit:</b><br>".TS_idletime($playerInfo['idletime'])."<br><br><b>Ping:</b> ".$playerInfo['pingtime']." ms";
              if($tss2info->TS_overlib_mouseover == 1) $player_mouse_over = "style=\"cursor: help;\" onmouseover=\"return overlib('".str_replace("'","\'",$player_mouse_over2)."', WIDTH, 150);\" onmouseout=\"return nd();\"";
              else $player_mouse_over = "title=\"".$player_mouse_over1."\"";
              //---> SubPlayer <---\\ Anfang
              $ts_viewer_ausgabe .= '
        <tr>
          <td>
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>';
              unset($subuserstatus);
              if($tss2info->TS_userstatus_ausgabe == 1) $subuserstatus = ' ('.TS_userstatus($playerInfo['userstatus']).TS_privileg($playerInfo['privileg'],$playerInfo['attribute']).')';
              if($tss2info->TS_channel_anzeigen == 1) {
              $ts_viewer_ausgabe .= '
                <td class="player" width="55" nowrap><img width="5" height="16" src="images/teamspeak/blank.gif" border="0" alt=""><img src="images/teamspeak/blank.gif" width="15" height="16" border="0" alt=""><img src="images/teamspeak/blank.gif" width="15" height="16" border="0" alt=""><img src="images/teamspeak/'.TS_attribute($playerInfo['attribute']).'" width="20" height="16" border="0" alt=""></td>';
              } else {
              $player_without_channel[] = $playerInfo;
              $ts_viewer_ausgabe .= '
                <td class="player" width="20" nowrap><img src="images/teamspeak/'.TS_attribute($playerInfo['attribute']).'" width="20" height="16" border="0" alt=""></td>';
              }
              $ts_viewer_ausgabe .= '
                <td class="player" width="100%">&nbsp;<span '.$player_mouse_over.'>'.$playerInfo['playername'].$subuserstatus.'</span></td>
              </tr>
            </table>
          </td>
        </tr>';
              //---> SubPlayer <---\\ Ende
              $counter_player++; // Playercounter hochzählen
            }
          }
          //---> SubPlayerList <---\\ Ende

        }
      }
      //---> SubchannelList <---\\ Ende

      $counter++; // Channelcounter hochzählen

    }
  }
  $counter++; // Channelcounter hochzählen
}
//---> ChannelList <---\\ Ende

//---> OFFLINE <---\\ Anfang
if($counter == 0) {
  $ts_viewer_ausgabe .= '
        <tr>
          <td>
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td class="offline" width="110" align="center"><font class="heads"><b>Offline</b></font></td>
              </tr>
            </table>
          </td>
        </tr>';
}
//---> OFFLINE <---\\ Ende

if(is_array($player_without_channel)) {
  unset($ts_viewer_ausgabe);

  //---> Sortierung <---\\ Anfang
  unset($s1);
  unset($s2);
  unset($v);
  $s1 = array();
  $s2 = array();
  foreach($player_without_channel as $v) $s1[] = $v['userstatus'];    // Sortierung nach Order
  foreach($player_without_channel as $v) $s2[] = $v['playername'];    // Wenn Order gleich Sortierung nach Name
  array_multisort($s1, SORT_DESC, $s2, SORT_ASC, $player_without_channel); // ASC = auf-, DESC = absteigend
  //---> Sortierung <---\\ Ende

  //---> PlayerList <---\\ Anfang
  foreach($player_without_channel as $playerInfo) {
    $player_mouse_over1 = "".$playerInfo['playername']." | Online seit: ".TS_totaltime($playerInfo['totaltime'])." | Idle seit: ".TS_idletime($playerInfo['idletime'])." | Ping: ".$playerInfo['pingtime']." ms";
    $player_mouse_over2 = "<b>".$playerInfo['playername']."</b><br><br><b>Online seit:</b><br>".TS_totaltime($playerInfo['totaltime'])."<br><br><b>Idle seit:</b><br>".TS_idletime($playerInfo['idletime'])."<br><br><b>Ping:</b> ".$playerInfo['pingtime']." ms";
    if($tss2info->TS_overlib_mouseover == 1) $player_mouse_over = "style=\"cursor: help;\" onmouseover=\"return overlib('".str_replace("'","\'",$player_mouse_over2)."', WIDTH, 150);\" onmouseout=\"return nd();\"";
    else $player_mouse_over = "title=\"".$player_mouse_over1."\"";
    unset($userstatus);
    if($tss2info->TS_userstatus_ausgabe == 1) $userstatus = ' ('.TS_userstatus($playerInfo['userstatus']).TS_privileg($playerInfo['privileg'],$playerInfo['attribute']).')';
    $ts_viewer_ausgabe .= '
        <tr>
          <td>
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td class="player" width="20" nowrap><img src="images/teamspeak/'.TS_attribute($playerInfo['attribute']).'" width="20" height="16" border="0" alt=""></td>
                <td class="player" width="100%">&nbsp;<span '.$player_mouse_over.'>'.$playerInfo['playername'].$userstatus.'</span></td>
              </tr>
            </table>
          </td>
        </tr>';
  }
  //---> PlayerList <---\\ Anfang
}

//---> Start <---\\ Anfang
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>'.$tss2info->sitetitle.'</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta http-equiv="content-language" content="de">';
if($tss2info->TS_blendtrans == "1") {
  echo '
<meta http-equiv="Page-Exit" content="blendTrans(Duration=0.5)">
<meta http-equiv="Page-Enter" content="blendTrans(Duration=0.5)">';
}
if($tss2info->TS_refresh == 1 AND $tss2info->TS_autorefresh == 1 AND strtolower($_GET['refresh']) == "auto") {
  echo '
<meta http-equiv="refresh" content="'.$tss2info->TS_autorefresh_zeit.'; URL=TS_Viewer.php?refresh=auto">';
}
echo '
<link rel="stylesheet" type="text/css" href="stylesheet.php">';
if($tss2info->TS_overlib_mouseover == 1) {
  echo '
<!-- overLIB (c) Erik Bosrup -->
<script type="text/javascript" src="overlib.js"></script>
<!-- overLIB (c) Erik Bosrup -->';
}
echo '
</head>
<body>
'.$phpkiterror.'';
if($tss2info->TS_overlib_mouseover == 1) {
  echo '
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>';
}
echo '
<table border="0" width="100%" cellpadding="1" cellspacing="4">
  <tr>
    <td class="odd" align="left" valign="top" width="'.$tss2info->tabellenbreite.'" nowrap>
      <table border="0" width="100%" cellpadding="0" cellspacing="2">';
if($tss2info->TS_refresh == 1) {
  echo '
        <tr>
          <td>
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>';
  if((strtolower($_GET['refresh']) != "auto") OR ($tss2info->TS_autorefresh != 1 AND strtolower($_GET['refresh']) == "auto")) {
    echo '
                <td class="refresh" width="50%" nowrap><a class="refresh" href="TS_Viewer.php?refresh=">refresh</a></td>';
  }
  if($tss2info->TS_autorefresh == 1) {
    if(strtolower($_GET['refresh']) == "auto") {
      echo '
                <td class="refresh" align="right" nowrap><a class="refresh" href="TS_Viewer.php?refresh=">autorefresh deaktivieren</a></td>';
    } else {
      echo '
                <td class="refresh" align="right" width="50%" nowrap><a class="refresh" href="TS_Viewer.php?refresh=auto">autorefresh</a></td>';
    }
  }
  echo '
              </tr>
            </table>
          </td>
        </tr>';
}
if($tss2info->TS_title_anzeigen == 1) {
  echo '
        <tr>
          <td>
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td class="teamspeak" width="33" nowrap><img src="images/teamspeak/teamspeak.gif" width="33"height="18" border="0" alt=""></td><td class="teamspeak" width="100%">'.$tss2info->sitetitle.'</td>
              </tr>
            </table>
          </td>
        </tr>';
}
echo ''.$ts_viewer_ausgabe.'
      </table>
    </td>
  </tr>
  <tr>
    <td class="created"><br>TS-V '.$tss2info->TS_Version.' by <a class="created" href="http://www.php-gfx.net" target="_blank">php-gfx.net</a></td>
  </tr>
</table>
</body>
</html>';
//---> Stop <---\\ Ende
		
include($root_path . 'includes/page_tail.php');

?>