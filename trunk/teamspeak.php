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

define('IN_CMS', true); 

$root_path = './';
include($root_path . 'common.php'); 

$userdata = session_pagestart($user_ip, PAGE_TEAMSPEAK); 
init_userprefs($userdata); 

$page_title = $lang['teamspeak'];


$template->set_filenames(array('body' => 'body_teamspeak.tpl'));

$sql = 'SELECT * FROM ' . TEAMSPEAK . ' WHERE teamspeak_show = 1';
$teamspeak = _cached($sql, 'teamspeak_data', 1);

include($root_path . 'includes/teamspeak/ts_config.php');
include($root_path . 'includes/teamspeak/ts_functions.php');
include($root_path . 'includes/class_cyts.php');

$tss2info->serverAddress=$teamspeak['teamspeak_ip'];
$tss2info->serverUDPPort=$teamspeak['teamspeak_port'];
$tss2info->serverQueryPort=$teamspeak['teamspeak_qport'];

$tss2info->getInfo();

$cyts = new cyts;
$cyts->connect($teamspeak['teamspeak_ip'], $teamspeak['teamspeak_qport'], $teamspeak['teamspeak_port']);
$info = $cyts->info_serverInfo();

if ( $info )
{
	$template->assign_block_vars('show_sstats', array());
	
	$template->assign_vars(array(
		'L_SERVER_NAME'				=> $lang['teamspeak_server_name'],
		'L_SERVER_PLATFORM'			=> $lang['teamspeak_server_platform'],
		'L_SERVER_TYPE'				=> $lang['teamspeak_server_clan_server'],
		'L_SERVER_USER_MAX'			=> $lang['teamspeak_server_maxusers'],
		'L_SERVER_USER_CURRENT'		=> $lang['teamspeak_server_currentusers'],
		'L_SERVER_UPTIME'			=> $lang['teamspeak_server_uptime'],
		'L_SERVER_NUM_CHANNELS'		=> $lang['teamspeak_server_currentchannels'],
	
		'SERVER_NAME'				=> $info['server_name'],
		'SERVER_PLATFORM'			=> $info['server_platform'],
		'SERVER_TYPE'				=> ( $info['server_clan_server'] == '1' ) ? 'Clan' : 'Public',
		'SERVER_USER_MAX'			=> $info['server_maxusers'],
		'SERVER_USER_CURRENT'		=> $info['server_currentusers'],
		'SERVER_UPTIME'				=> $info['server_uptime'],
		'SERVER_NUM_CHANNELS'		=> $info['server_currentchannels'],
	));
}		
$cyts->disconnect();

//
//	Userliste 
//
$template->assign_block_vars('show_userist', array());

//	löschen der Variablen
unset($s1); unset($s2); unset($v);

//	Arrays erstellen
$s1 = array();
$s2 = array();

foreach ( $tss2info->playerList as $v ) { $s1[] = $v['channelid']; }
foreach ( $tss2info->playerList as $v ) { $s2[] = $v['userstatus']; }

array_multisort($s1, SORT_ASC, $s2, SORT_DESC, $tss2info->playerList);

$channels = $tss2info->channelList;

function _getname($id)
{
	global $channels;
	
	$channelname = '';
	
	foreach ( $channels as $channel )
	{
		if ( $id == $channel['channelid'] )
		{
			$channelname = $channel['channelname'];
		}
	}
	
	return $channelname;
}

$tss2info_playerlist = $tss2info->playerList;

unset($tss2info_playerlist['p_id']);

$color = '';
foreach ( $tss2info_playerlist as $player )
{
	$class = ($color % 2) ? 'row1r' : 'row2r';
	$color++;
	
	$template->assign_block_vars('show_userist.userlist', array(
		'CLASS' 	=> $class,
		'USERNAME'	=> $player['playername'],
		'CHANNEL'	=> _getname($player['channelid']),
	));
	
}
	
//	Arrays zum Suchen und Ersetzen von Sonderzeichen
$tsv_array_1 = array(" ","-","(",")","[","]","{","}","&");
$tsv_array_2 = array("_","_","","","","","","","");

$tsv_username = ( $userdata['user_id'] == ANONYMOUS ) ? $teamspeak['teamspeak_join_name'] : $userdata['username'];

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
unset($s1); unset($s2); unset($v);

$s1 = array();
$s2 = array();

foreach ($tss2info->channelList as $v) { $s1[] = $v['channelorder']; }	// Sortierung nach Order 
foreach ($tss2info->channelList as $v) { $s2[] = $v['channelname']; }	// Wenn Order gleich Sortierung nach Name
array_multisort($s1, SORT_ASC, $s2, SORT_ASC, $tss2info->channelList);	// ASC = auf-, DESC = absteigend

//
//	Subchannel nach Player durchsuchen
//
$tss2info_channellist = $tss2info->channelList;

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
	if ( $channelInfo['channelid'] != 'id' )
	{
		if ( $channelInfo['channelparent'] < 0 )
		{
			//
			//	Channelanzeigen
			//
			if ( ( $tss2info->TS_leerchannel_anzeigen == 1 || ( $tss2info->TS_leerchannel_anzeigen == 0 && ( trim($channelInfo['channelcurrentplayers']) > 0 || ( intval($subchannels[$channelInfo['channelid']]) == 1) ))))
			{
				//
				//	Mouseover
				//
				$channel_mouseover1 = sprintf($lang['ts_info_1'], $tsv_username, $channelInfo['channelname'], $channelInfo['channeltopic'], $channelInfo['channelmaxplayers'], $channelInfo['channelcurrentplayers'], $channelInfo['channelcodec']);
				$channel_mouseover2 = sprintf($lang['ts_info_2'], $channelInfo['channelname'], $channelInfo['channeltopic'], $channelInfo['channelmaxplayers'], $channelInfo['channelcurrentplayers'], $channelInfo['channelcodec']);
				
				//
				//	Passwortabfrage
				//
				if ( !$channelInfo['channelpasswort'] )
				{
					$channel_mouseover = "title=\"" . $channel_mouseover1 . "\"";
					
					$channelicon = 'channel.gif';
					$channellink = '<a class="channellink" href="teamspeak://' . $teamspeak['teamspeak_ip'] . ':' . $teamspeak['teamspeak_port'] . '/?channel=' . rawurlencode($channelInfo['channelname']) . '?password=' . $teamspeak['teamspeak_pass'] . '?nickname=' . rawurlencode($tsv_username) . ' "'.$channel_mouseover.'">' . $channelInfo['channelname'] . '</a>';
				}
				else
				{
					$channel_mouseover = "title=\"" . $channel_mouseover2 . "\"";
					
					$channelicon = 'channel_close.gif';
					$channellink = "<span " . $channel_mouseover2 . ">" . $channelInfo['channelname'] . "</span>";
				}
				
				//
				//	Channelflags
				//
				$channellink .= ( $teamspeak['teamspeak_cstats'] ) ? ' (' . TS_channelflags($channelInfo['channelflags']) . ')' : '';
				
				//
				//	Channel
				//
				$template->assign_block_vars('channel', array(
					'CHANNEL'		=> $channellink,
					'CHANNEL_ICON'	=> $channelicon,
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
					
					$player_mouse_over = sprintf($lang['ts_info_u'], $playerInfo['playername'], TS_totaltime($playerInfo['totaltime']), TS_idletime($playerInfo['idletime']), $playerInfo['pingtime']);
					$player_mouse_over = 'title="' . $player_mouse_over . '"';
					
					//
					//	Player
					//
					$template->assign_block_vars('channel.user', array(
						'USERNAME'		=> $playerInfo['playername'],
						'USERSTATUS'	=> ( $teamspeak['teamspeak_ustats'] ) ? ' ('.TS_userstatus($playerInfo['userstatus']).TS_privileg($playerInfo['privileg'],$playerInfo['attribute']).')' : '',
						'USERPIC'		=> TS_attribute($playerInfo['attribute']),
						'USERHOVER'		=> $player_mouse_over,											   
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
				if ( $subchannelInfo['channelparent'] == $channelInfo['channelid'] )
				{
					$subchannel_mouseover1 = sprintf($lang['ts_info_1'], $tsv_username, $subchannelInfo['channelname'], $subchannelInfo['channeltopic'], $subchannelInfo['channelmaxplayers'], $subchannelInfo['channelcurrentplayers'], $subchannelInfo['channelcodec']);
					$subchannel_mouseover2 = sprintf($lang['ts_info_2'], $subchannelInfo['channelname'], $subchannelInfo['channeltopic'], $subchannelInfo['channelmaxplayers'], $subchannelInfo['channelcurrentplayers'], $subchannelInfo['channelcodec']);
					
					if ( !$subchannelInfo['channelpasswort'] )
					{
						$subchannel_mouseover = "title=\"" . $subchannel_mouseover1 . "\"";
				
						$subchannelicon = 'channel.gif';
						$subchannellink = '<a class="channellink" href="teamspeak://' . $teamspeak['teamspeak_ip'] . ':' . $teamspeak['teamspeak_port'] . '/?channel=' . rawurlencode($subchannelInfo['channelname']) . '?password=' . $teamspeak['teamspeak_pass'] . '?nickname=' . rawurlencode($tsv_username) . ' "'.$subchannel_mouseover.'">' . $subchannelInfo['channelname'] . '</a>';
					}
					else
					{
						$subchannel_mouseover = "title=\"" . $subchannel_mouseover2 . "\"";
					
						$subchannelicon = 'channel_close.gif';
						$subchannellink = "<span " . $subchannel_mouseover2 . ">" . $subchannelInfo['channelname'] . "</span>";
					}

					$template->assign_block_vars('channel.subchannel', array(
						'SUBCHANNEL'		=> $subchannellink,
						'SUBCHANNEL_ICON'	=> $subchannelicon,
					));
					
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
					foreach( $tss2info->playerList as $playerInfo )
					{
						if ( $playerInfo['channelid'] == $subchannelInfo['channelid'] && $subchannelInfo['channelparent'] == $channelInfo['channelid'] )
						{
							unset($subuserstatus);
							
							$playercounter1 = $counter_player + 1;
							$playercounter2 = $subchannelInfo['channelcurrentplayers'];
							
							$player_mouse_over = sprintf($lang['ts_info_u'], $playerInfo['playername'], TS_totaltime($playerInfo['totaltime']), TS_idletime($playerInfo['idletime']), $playerInfo['pingtime']);

							$player_mouse_over = 'title="' . $player_mouse_over . '"';
							
							$template->assign_block_vars('channel.subchannel.subuser', array(
								'USERNAME'		=> $playerInfo['playername'],
								'USERSTATUS'	=> ( $teamspeak['teamspeak_ustats'] ) ? ' ('.TS_userstatus($playerInfo['userstatus']).TS_privileg($playerInfo['privileg'],$playerInfo['attribute']).')' : '',
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

$template->assign_vars(array(
	'L_TS_VIEWER'			=> $lang['teamspeak_viewer'],
	'L_TS_PLIST'			=> $lang['teamspeak_plist'],
	
	'S_TEAMSPEAK_ACTION'	=> append_sid("teamspeak.php"),
));

include($root_path . 'includes/page_header.php');
		
$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>