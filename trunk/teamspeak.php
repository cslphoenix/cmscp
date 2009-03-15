<?php

define('IN_CMS', true); 

$root_path = './'; // <-- 
include($root_path . 'common.php'); 

$userdata = session_pagestart($user_ip, PAGE_TEAMSPEAK); 
init_userprefs($userdata); 

$page_title = $lang['teamspeak'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'teamspeak_body.tpl'));

$sql = 'SELECT * FROM ' . SERVER_TABLE . ' WHERE server_id = 4';
if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not insert row in match table', '', __LINE__, __FILE__, $sql);
				}
				if (!($server = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['match_not_exist']);
					}
					
$serverAddress = $server['server_ip'];

include($root_path . 'includes/teamspeak/ts_config.php');
include($root_path . 'includes/teamspeak/ts_functions.php');
$tss2info->serverAddress=$server['server_ip'];
$tss2info->serverUDPPort=$server['server_port'];
$tss2info->serverQueryPort=$server['server_qport'];
$tss2info->getInfo();



$tsv_array_1 = array(" ","-","(",")","[","]","{","}","&"); // Das wird gesucht..
$tsv_array_2 = array("_","_","","","","","","",""); // ..und ersetzt mit diesem

$tsv_username = ( $userdata['user_id'] == '-1' ) ? $tss2info->alternativer_nick : $userdata['username'];

for ( $x=0; $x < count($tsv_array_1); $x++ )
{
	$tsv_username = trim(str_replace($tsv_array_1[$x], $tsv_array_2[$x], $tsv_username));
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
foreach ($tss2info->channelList as $v) { $s1[] = $v['channelorder']; }	// Sortierung nach Order 
foreach ($tss2info->channelList as $v) { $s2[] = $v['channelname']; }	// Wenn Order gleich Sortierung nach Name
array_multisort($s1, SORT_ASC, $s2, SORT_ASC, $tss2info->channelList);	// ASC = auf-, DESC = absteigend

//
//	Subchannel nach Player durchsuchen
//
$tss2info_channellist = $tss2info->channelList;

//$tss2info_serverinfo = $tss2info->playerList;

//_debug_post($tss2info_serverinfo);

//
//	Fehlerunterdrückung
//	Listet zum Schluß noch einen Versteckten Channel
//	dieser wird mit unset aus dem Array genommen
//
unset($tss2info_channellist['id']);

for ( $i=0; $i < count($tss2info_channellist); $i++ )
{
	if ( intval($tss2info_channellist[$i]['channelparent']) > 0 && intval($tss2info_channellist[$i]['channelcurrentplayers']) > 0)
	{
		$subchannels[$tss2info_channellist[$i]['channelparent']] = 1;
	}
}

//
//	ChannelList
//
foreach ($tss2info->channelList as $channelInfo)
{
	if ( $channelInfo['channelid'] != "id" && !in_array($channelInfo['channelid'], $tss2info->TS_hide_channels) )
	{
		if ( $channelInfo['channelparent'] < 0 )
		{
			//
			//	Channelanzeigen
			//
			if ( $tss2info->TS_channel_anzeigen == 1 && ( $tss2info->TS_leerchannel_anzeigen == 1 || ( $tss2info->TS_leerchannel_anzeigen == 0 && ( trim($channelInfo['channelcurrentplayers']) > 0 || ( intval($subchannels[$channelInfo['channelid']]) == 1) ))))
			{
				//
				//	Mouseover
				//
				$channel_mouseover1 = "Join als: ".$tsv_username." | Channelname: ".$channelInfo['channelname']." | Topic: ".$channelInfo['channeltopic']." | Maximale User: ".$channelInfo['channelmaxplayers']." | Derzeitige User: ".$channelInfo['channelcurrentplayers']." | Codec: ".$channelInfo['channelcodec']."";
				$channel_mouseover2 = "Kein Joinen möglich | Channelname: ".$channelInfo['channelname']." | Topic: ".$channelInfo['channeltopic']." | Maximale User: ".$channelInfo['channelmaxplayers']." | Derzeitige User: ".$channelInfo['channelcurrentplayers']." | Codec: ".$channelInfo['channelcodec']."";
				$channel_mouseover3 = "<b>Join als:</b> ".$tsv_username."<br><br><b>Channelname:</b><br>".$channelInfo['channelname']."<br><br><b>Topic:</b><br>".$channelInfo['channeltopic']."<br><br><b>Maximale User:</b> ".$channelInfo['channelmaxplayers']."<br><b>Derzeitige User:</b> ".$channelInfo['channelcurrentplayers']."<br><br><b>Codec:</b><br>".$channelInfo['channelcodec']."";
				$channel_mouseover4 = "<b>Kein Joinen möglich</b><br><br><b>Channelname:</b><br>".$channelInfo['channelname']."<br><br><b>Topic:</b><br>".$channelInfo['channeltopic']."<br><br><b>Maximale User:</b> ".$channelInfo['channelmaxplayers']."<br><b>Derzeitige User:</b> ".$channelInfo['channelcurrentplayers']."<br><br><b>Codec:</b><br>".$channelInfo['channelcodec']."";
				
				//
				//	Passwortabfrage
				//
				if ( $channelInfo['channelpasswort'] == "0" )
				{
					if ( $tss2info->TS_overlib_mouseover == 1 )
					{
						$channel_mouseover3 = 'onmouseover="return overlib(' . str_replace("'","\'",$channel_mouseover3) . ', WIDTH, 200);" onmouseout="return nd();"';
					}
					else
					{
						$channel_mouseover3 = 'title="' . $channel_mouseover1 . '"';
					}
					
					$channellink = '<a class="channellink" href="teamspeak://' . $serverAddress . ':' . $tss2info->serverUDPPort . '/?channel=' . rawurlencode($channelInfo['channelname']) . '?password=' . $tss2info->serverPasswort . '?nickname=' . rawurlencode($tsv_username)."\" ".$channel_mouseover3.">".$channelInfo['channelname']."</a>";
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
				
				//
				//	Channelflags
				//
				if ( $tss2info->TS_channelflags_ausgabe == 1 )
				{
					$channellink .= ' (' . TS_channelflags($channelInfo['channelflags']) . ')';
				}
				
				//
				//	Channel
				//
				$template->assign_block_vars('channel', array(
					'channel' => $channellink,
				));
				
			}
			
			$counter_player = 0;	// Playercounter beginnen
			
			//
			//	Player Sortierung
			//
			unset($s1);
			unset($s2);
			unset($v);
			$s1 = array();
			$s2 = array();
			foreach($tss2info->playerList as $v) $s1[] = $v['userstatus'];			// Sortierung nach Order
			foreach($tss2info->playerList as $v) $s2[] = $v['playername'];			// Wenn Order gleich Sortierung nach Name
			array_multisort($s1, SORT_DESC, $s2, SORT_DESC, $tss2info->playerList);	// ASC = auf-, DESC = absteigend
			
			//
			//	PlayerList
			//
			foreach($tss2info->playerList as $playerInfo)
			{
				if ( $playerInfo['channelid'] == $channelInfo['channelid'] )
				{
					unset($userstatus);
					
					$playercounter1 = $counter_player + 1;
					$playercounter2 = $channelInfo['channelcurrentplayers'];
					
					$player_mouse_over1 = $playerInfo['playername'] . ' | Online seit: ' . TS_totaltime($playerInfo['totaltime']) . ' | Idle seit: ' . TS_idletime($playerInfo['idletime']) . ' | Ping: ' . $playerInfo['pingtime'] . ' ms';
					$player_mouse_over2 = '<b>' . $playerInfo['playername'] . '</b><br /><br /><b>Online seit:</b><br />' . TS_totaltime($playerInfo['totaltime']) . '<br /><br /><b>Idle seit:</b><br />' . TS_idletime($playerInfo['idletime']) . '<br /><br /><b>Ping:</b><br /> '. $playerInfo['pingtime'] . ' ms';
					
					if ( $tss2info->TS_overlib_mouseover == 1)
					{
						$player_mouse_over = 'style="cursor: help;" onmouseover="return overlib(' . str_replace("'","\'",$player_mouse_over2) . ', WIDTH, 150);" onmouseout="return nd();"';
					}
					else
					{
						$player_mouse_over = 'title="' . $player_mouse_over1 . '"';
					}
					
					//
					//	Player
					//
					$template->assign_block_vars('channel.user', array(
						'USERNAME'		=> $playerInfo['playername'],
						'USERSTATUS'	=> ($tss2info->TS_userstatus_ausgabe == 1) ? ' ('.TS_userstatus($playerInfo['userstatus']).TS_privileg($playerInfo['privileg'],$playerInfo['attribute']).')' : '',
						'USERPIC'		=> TS_attribute($playerInfo['attribute']),
						'USERHOVER'		=> 'test' . $player_mouse_over,											   
						
					));

					$counter_player++;	// Playercounter hochzählen
				}
			}
			
			//
			//	Subchannel Sortierung
			//
			unset($s1);
			unset($s2);
			$s1 = array();
			$s2 = array();
			foreach($tss2info->channelList as $v) $s1[] = $v['channelorder'];		// Sortierung nach Order
			foreach($tss2info->channelList as $v) $s2[] = $v['channelname'];		// Wenn Order gleich Sortierung nach Name
			array_multisort($s1, SORT_ASC, $s2, SORT_ASC, $tss2info->channelList);	// ASC = auf-, DESC = absteigend
			unset($v);
			
			//
			//	SubchannelList
			//
			foreach ( $tss2info->channelList as $subchannelInfo )
			{
				if ($subchannelInfo['channelparent'] == $channelInfo['channelid'] && !in_array($subchannelInfo['channelid'],$tss2info->TS_hide_channels) AND ($tss2info->TS_leerchannel_anzeigen == 1 OR ($tss2info->TS_leerchannel_anzeigen == 0 AND trim($subchannelInfo['channelcurrentplayers']) > 0)))
				{
					if ($tss2info->TS_channel_anzeigen == 1)
					{
						$subchannel_mouseover1 = "Join als: ".$tsv_username." | Channelname: ".$subchannelInfo['channelname']." | Subchannel von: ".$channelInfo['channelname']." | Topic: ".$subchannelInfo['channeltopic']." | Maximale User: ".$subchannelInfo['channelmaxplayers']." | Derzeitige User: ".$subchannelInfo['channelcurrentplayers']." | Codec: ".$subchannelInfo['channelcodec']."";
						$subchannel_mouseover2 = "Kein Joinen möglich | Channelname: ".$subchannelInfo['channelname']." | Subchannel von: ".$channelInfo['channelname']." | Topic: ".$subchannelInfo['channeltopic']." | Maximale User: ".$subchannelInfo['channelmaxplayers']." | Derzeitige User: ".$subchannelInfo['channelcurrentplayers']." | Codec: ".$subchannelInfo['channelcodec']."";
						$subchannel_mouseover3 = "<b>Join als:</b> ".$tsv_username."<br><br><b>Channelname:</b><br>".$subchannelInfo['channelname']."<br><b>Subchannel von:</b><br>".$channelInfo['channelname']."<br><br><b>Topic:</b><br>".$subchannelInfo['channeltopic']."<br><br><b>Maximale User:</b> ".$subchannelInfo['channelmaxplayers']."<br><b>Derzeitige User:</b> ".$subchannelInfo['channelcurrentplayers']."<br><br><b>Codec:</b><br>".$subchannelInfo['channelcodec']."";
						$subchannel_mouseover4 = "<b>Kein Joinen möglich</b><br><br><b>Channelname:</b><br>".$subchannelInfo['channelname']."<br><b>Subchannel von:</b><br>".$channelInfo['channelname']."<br><br><b>Topic:</b><br>".$subchannelInfo['channeltopic']."<br><br><b>Maximale User:</b> ".$subchannelInfo['channelmaxplayers']."<br><b>Derzeitige User:</b> ".$subchannelInfo['channelcurrentplayers']."<br><br><b>Codec:</b><br>".$subchannelInfo['channelcodec']."";
						
						if ($channelInfo['channelpasswort'] == "0")
						{
							if($tss2info->TS_overlib_mouseover == 1) $subchannel_mouseover3 = "onmouseover=\"return overlib('".str_replace("'","\'",$subchannel_mouseover3)."', WIDTH, 200);\"  onmouseout=\"return nd();\"";
							else $subchannel_mouseover3 = "title=\"".$channel_mouseover1."\"";
							
							$subchannellink = "<a class=\"channellink\" href=\"teamspeak://".$serverAddress.":".$tss2info->serverUDPPort."/?channel=".rawurlencode($subchannelInfo['channelname'])."?password=".$tss2info->serverPasswort."?nickname=".rawurlencode($tsv_username)."\" ".$subchannel_mouseover3.">".$subchannelInfo['channelname']."</a>";
						}
						else
						{
							if($tss2info->TS_overlib_mouseover == 1) $subchannel_mouseover4 = "style=\"cursor: help;\" onmouseover=\"return overlib('".str_replace("'","\'",$subchannel_mouseover4)."', WIDTH, 200);\"  onmouseout=\"return nd();\"";
							else $subchannel_mouseover4 = "title=\"".$channel_mouseover2."\"";
							$subchannellink = "<span ".$subchannel_mouseover4.">".$subchannelInfo['channelname']."</span>";
						}

						$template->assign_block_vars('channel.subchannel', array(
							"subchannel" => $subchannellink,
						));
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
					array_multisort($s1, SORT_DESC, $s2, SORT_DESC, $tss2info->playerList); // ASC = auf-, DESC = absteigend
					//---> Sortierung <---\\ Ende
					
					//---> SubPlayerList <---\\ Anfang
					foreach($tss2info->playerList as $playerInfo)
					{
						if ($playerInfo['channelid'] == $subchannelInfo['channelid'] && $subchannelInfo['channelparent'] == $channelInfo['channelid'])
						{
							unset($subuserstatus);
							
							$playercounter1 = $counter_player + 1;
							$playercounter2 = $subchannelInfo['channelcurrentplayers'];
							
							$player_mouse_over1 = $playerInfo['playername'] . ' | Online seit: ' . TS_totaltime($playerInfo['totaltime']) . ' | Idle seit: ' . TS_idletime($playerInfo['idletime']) . ' | Ping: ' . $playerInfo['pingtime'] . ' ms';
							$player_mouse_over2 = '<b>' . $playerInfo['playername'] . '</b><br /><br /><b>Online seit:</b><br />' . TS_totaltime($playerInfo['totaltime']) . '<br /><br /><b>Idle seit:</b><br />' . TS_idletime($playerInfo['idletime']) . '<br /><br /><b>Ping:</b><br /> '. $playerInfo['pingtime'] . ' ms';
							
							if ( $tss2info->TS_overlib_mouseover == 1)
							{
								$player_mouse_over = 'style="cursor: help;" onmouseover="return overlib(' . str_replace("'","\'",$player_mouse_over2) . ', WIDTH, 150);" onmouseout="return nd();"';
							}
							else
							{
								$player_mouse_over = 'title="' . $player_mouse_over1 . '"';
							}
							
							$template->assign_block_vars('channel.subchannel.subuser', array(
								'USERNAME'		=> $playerInfo['playername'],
								'USERSTATUS'	=> ($tss2info->TS_userstatus_ausgabe == 1) ? ' ('.TS_userstatus($playerInfo['userstatus']).TS_privileg($playerInfo['privileg'],$playerInfo['attribute']).')' : '',
								'USERPIC'		=> TS_attribute($playerInfo['attribute']),
								'USERHOVER'		=> $player_mouse_over,
							));
							
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
if ($counter == 0)
{
	$template->assign_block_vars('offline', array());
}
//---> OFFLINE <---\\ Ende

/*
if ( is_array($player_without_channel) )
{
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
*/
		
$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>