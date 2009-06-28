<?php

define('IN_CMS', true); 

$root_path = './';
include($root_path . 'common.php'); 

$userdata = session_pagestart($user_ip, PAGE_TEAMSPEAK); 
init_userprefs($userdata); 

$page_title = $lang['teamspeak'];
include($root_path . 'includes/page_header.php');
include($root_path . 'includes/teamspeak_query.php');

$template->set_filenames(array('body' => 'teamspeak_dzcp_body.tpl'));

	/*
	$qry = db("SELECT ts_port,ts_sport,ts_ip FROM " . $db['settings']."");
	$s = _fetch($qry);
	*/
	$uip 	= "88.198.52.237";
    $tPort 	= "26384";
    $port 	= "8767";
/*	
	$uip 	= "tempelball.de";
    $tPort 	= "51234";
    $port 	= "6002";
*/
  	$fp = fsockopen($uip, $tPort, $errno, $errstr, 3);
	
	if (!$fp)
	{
		echo 'error';
	}
	else
	{
		$out = "";
		$fp = fsockopen($uip, $tPort, $errno, $errstr, 3);
		
		if( $fp )
		{
			fputs($fp, "sel " . $port."\n");
			fputs($fp, "si\n");
			fputs($fp, "quit\n");

			while(!feof($fp))
			{
				$out .= fgets($fp, 1024);
			}
			$out = str_replace("[TS]", "", $out);
			$out = str_replace("OK", "", $out);
			$out = trim($out);
			
			$name=substr($out,indexof($out,"server_name='),strlen($out));
			$name=substr($name,0,indexOf($name,"server_platform=")-strlen("server_platform="));
			$os=substr($out,indexOf($out,"server_platform='),strlen($out));
			$os=substr($os,0,indexOf($os,"server_welcomemessage=")-strlen("server_welcomemessage="));
			$uptime=substr($out,indexOf($out,"server_uptime='),strlen($out));
			$uptime=substr($uptime,0,indexOf($uptime,"server_currrentusers=")-strlen("server_currrentusers="));
			$cAmount=substr($out,indexOf($out,"server_currentchannels='),strlen($out));
			$cAmount=substr($cAmount,0,indexOf($cAmount,"server_bwinlastsec=")-strlen("server_bwinlastsec="));
			$user=substr($out,indexOf($out,"server_currentusers='),strlen($out));
			$user=substr($user,0,indexOf($user,"server_currentchannels=")-strlen("server_currentchannels="));
			$max=substr($out,indexOf($out,"server_maxusers='),strlen($out));
			$max=substr($max,0,indexOf($max,"server_allow_codec_celp51=")-strlen("server_allow_codec_celp51="));
			
			fclose($fp);
		}
		
		$uArray = array();
		$innerArray = array();
		$out = "";
		$j = 0;
		$k = 0;
		
		$fp = fsockopen($uip, $tPort, $errno, $errstr, 3);

		if($fp)
		{
			fputs($fp, "pl " . $port."\n");
			fputs($fp, "quit\n");
			
			while(!feof($fp))
			{
				$out .= fgets($fp, 1024);
			}
			
			$out = str_replace("[TS]", "", $out);
			$out = str_replace("loginname", "loginname\t", $out);
			$data	= explode("\t", $out);
			
			for($i=0;$i<count($data);$i++)
			{
				$innerArray[$j] = $data[$i];
				if ($j>=15)
				{
					$uArray[$k]=$innerArray;
					$j = 0;
					$k = $k+1;
				}
				else
				{
					$j++;
				}
			}
			fclose($fp);
		}
		$debug = false;
		
		for ($i=1;$i<count($uArray);$i++)
		{
			$innerArray=$uArray[$i];
			$p = setUserStatus($innerArray[12])."&nbsp;<span class=\"fontBold\">".removeChar($innerArray[14])."</span>&nbsp;(".setPPriv($innerArray[11])."".setCPriv($innerArray[10]).")";
			
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
//			_debug($innerArray);
			
//			$userstats .= show($dir."/userstats", array(
			$template->assign_block_vars('userstats', array(
				"class" => $class,
				"player" => $p,
				"channel" => getChannelName($innerArray[1],$uip,$port,$tPort),
				"misc1" => $innerArray[6],
				"misc2" => $innerArray[7],
				"misc3" => time_convert($innerArray[8]),
				"misc4" => time_convert($innerArray[9])
			));
		}
		
		$uArr = getTSChannelUsers($uip,$port,$tPort);
		$pcArr = Array();
		$ccArr = Array();
		$thisArr = Array();
		$listArr = Array();
		$usedArr = Array();
		$cArr	= getChannels($uip,$port,$tPort);
		$z = 0;
		$x = 0;
		
		for ($i=0;$i<count($cArr);$i++)
		{
			$innerArr=$cArr[$i];
			$listArr[$i]=$innerArr[3];
		}
		
		sort($listArr);
		for ($i=0;$i<count($listArr);$i++)
		{
			for ($j=0;$j<count($cArr);$j++)
			{
				$innArr=$cArr[$j];
				
				if ($innArr[3]==$listArr[$i] && usedID($usedArr,$innArr[0]))
				{
					if($innArr[2]==-1)
					{
						$thisArr[0] = $innArr[0];
						$thisArr[1] = $innArr[5];
						$thisArr[2] = $innArr[2];
						$pcArr[$z] = $thisArr;
						$usedArr[count($usedArr)] = $innArr[0];
						$z++;
					}
					else
					{
						$thisArr[0] = $innArr[0];
						$thisArr[1] = $innArr[5];
						$thisArr[2] = $innArr[2];
						$ccArr[$x] = $thisArr;
						$usedArr[count($usedArr)] = $innArr[0];
						$x++;
					}
				}
			}
		}
		
		for ($i=0;$i<count($pcArr);$i++)
		{
			$innerArr=$pcArr[$i];
			$subchan = "";
			$users = "";
			
			for($k=1;$k<count($uArr);$k++)
			{
				$innerUArray=$uArr[$k];
				
				if($innerArr[0]==$innerUArray[1])
				{
					$users .= "<img src=\"images/teamspeak/trenner.gif\" alt=\"\" class=\"tsicon\" />".setUserStatus($innerUArray[12])."<span class=\"fontBold\">".removeChar($innerUArray[14])."</span>&nbsp;(".setPPriv($innerUArray[11])."".setCPriv($innerUArray[10]).")<br>";
				}
			}
			
			$channels = "<img src=\"images/teamspeak/channel.gif\" alt=\"\" class=\"tsicon\" />&nbsp;<a style=\"font-weight:bold\" href=\"teamspeak.php?cID=".trim($innerArr[0])."&amp;type=1\">".removeChar($innerArr[1])."&nbsp;</a><br> " . $users."";
			
//			$chan .= show($dir."/channel", array(
			$template->assign_block_vars('channel', array(
				"channel" => $channels,
			));
			
			for($j=0;$j<count($ccArr);$j++)
			{
				$innerCCArray=$ccArr[$j];
				if ($innerArr[0]==$innerCCArray[2])
				{
					for ($p=1;$p<count($uArr);$p++)
					{
						$subusers = "";
						for($p=1;$p<count($uArr);$p++)
						{
							$innerUArray=$uArr[$p];
							if($innerCCArray[0]==$innerUArray[1])
							{
								$subusers .= "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"images/teamspeak/trenner.gif\" alt=\"\" class=\"tsicon\" />".setUserStatus($innerUArray[12])."&nbsp;<span class=\"fontBold\">".removeChar($innerUArray[14])."</span>&nbsp;(".setPPriv($innerUArray[11])."".setCPriv($innerUArray[10]).")<br>";
							}
						}
					}
					$subchannels = "<img src=\"images/teamspeak/trenner.gif\" alt=\"\"  /><img src=\"images/teamspeak/channel.gif\" alt=\"\" class=\"tsicon\" /><a style=\"font-weight:normal\" href=\"teamspeak.php?cID=" . $innerCCArray[0]."&amp;type=1\">&nbsp;".removeChar($innerCCArray[1])."&nbsp;</a><br> " . $subusers."";
					
//					$subchan .= show($dir."/", array(
					$template->assign_block_vars('channel.subchannel', array(
						"subchannels" => $subchannels
					));
				}
			}
			
			
			
			
		}
		
		if(isset($_GET['cID']))
		{
			$cID 	= $_GET['cID'];
			$type	= $_GET['type'];
		}
		else
		{
			$cID 	= 0;
			$type	= 0;
		}
		
		if($type==0)     $info = defaultInfo($uip,$tPort,$port);
		elseif($type==1) $info = channelInfo($uip,$tPort,$port,$cID);
		
		$template->assign_vars(array(
			'L_TEAMSPEAK'		=> $lang['teamspeak'],
			"name" => $name,
			"os" => $os,
			"uptime" => time_convert($uptime),
			"user" => $user,
			"t_name" => $lang['_ts_name'],
			"t_os" => $lang['_ts_os'],
//			"uchannels" => $chan,
			"info" => $info,
			"t_uptime" => $lang['_ts_uptime'],
			"t_channels" => $lang['_ts_channels'],
			"t_user" => $lang['_ts_user'],
			"head" => $lang['_ts_head'],
			"users_head" => $lang['_ts_users_head'],
			"player" => $lang['_ts_player'],
			"channel" => $lang['_ts_channel'],
			"channel_head" => $lang['_ts_channel_head'],
			"max" => $max,
			"channels" => $cAmount,
			"logintime" => $lang['_ts_logintime'],
			"idletime" => $lang['_ts_idletime'],
//			"channelstats" => $channelstats,
//			"userstats" => $userstats
		));
	}
	
$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>