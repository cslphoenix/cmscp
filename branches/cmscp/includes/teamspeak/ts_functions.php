<?php
/***
*
*
*
*	© by Dooki: dooki@php-gfx.net
*
*	edit by Phoenix
*
*
***/

//
//	channelflags
//
function TS_channelflags($channelflags)
{
	$TS_channelflags = '';
	
	if (preg_match("/^(1|3|5|7|9|11|13)$/",$channelflags))
	{
		$TS_channelflags = "U"; // Unregistriert
	}
	
	if (preg_match("/^(0|2|4|6|8|10|12|14|16|18|24|26)$/",$channelflags))
	{
		$TS_channelflags .= "R"; // Registriert
	}
	
	if (preg_match("/^(2|3|6|7|10|11|14|15|18|26)$/",$channelflags))
	{
		$TS_channelflags .= "M"; // Moderiert
	}
	
	if (preg_match("/^(4|6|7|12|13|14)$/",$channelflags))
	{
		$TS_channelflags .= "P"; // Passwort
	}
	
	if (preg_match("/^(8|9|10|11|12|13|14|15|24|26)$/",$channelflags))
	{
		$TS_channelflags .= "S"; // Subchannels
	}
	
	if (preg_match("/^(16|18|24|26)$/",$channelflags))
	{
		$TS_channelflags .= "D"; // Default
	}
	
	return $TS_channelflags;
}

//
//	attribute
//
function TS_attribute($attribute)
{
	if(preg_match("/^(0|4)$/",$attribute)) $TS_attribute = "player.gif"; // Player (grün)
	if(preg_match("/^(1|5)$/",$attribute)) $TS_attribute = "commander.gif"; // ChannelComander (rot)
	if(preg_match("/^(16|17|20|21)$/",$attribute)) $TS_attribute = "micro.gif"; // Micro aus
	if(preg_match("/^(32|33|36|37|48|49|52|53)$/",$attribute)) $TS_attribute = "speakers.gif"; // Headset aus
	if(preg_match("/^(8|9|12|13|24|25|28|29|40|41|42|44|45|56|57|60|61)$/",$attribute)) $TS_attribute = "away.gif"; // Abwesend
	if(preg_match("/^(6|14|22|38|46|54|62)$/",$attribute)) $TS_attribute = "request.gif"; // Request Voice
	if($attribute >= "64") $TS_attribute = "record.gif"; // Aufnehmen
	
	return $TS_attribute;
}

//
//	userstatus
//
function TS_userstatus($userstatus)
{
	$TS_userstatus = '';
	if(preg_match("/^0$/",$userstatus)) $TS_userstatus = "U"; //
	if(preg_match("/^4$/",$userstatus)) $TS_userstatus .= "R"; //
	if(preg_match("/^5$/",$userstatus)) $TS_userstatus .= "R SA"; //
	
	return $TS_userstatus;
}

//
//	userstatus
//
function TS_privileg($privileg, $attribute)
{
	$TS_privileg = '';
	if(preg_match("/^(1|3|5|7|9|11|13|15|17|19|21|23|25|27|29|31)$/",$privileg)) $TS_privileg = " CA"; // Channeladmin
	if(preg_match("/^(8|9|10|11|12|13|14|15|24|25|26|27|28|29|30|31)$/",$privileg)) $TS_privileg .= " AO"; // AutoOperator
	if(preg_match("/^(16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31)$/",$privileg)) $TS_privileg .= " AV"; // AutoVoice
	if(preg_match("/^(2|3|6|7|10|11|14|15|18|19|22|23|26|27|30|31)$/",$privileg)) $TS_privileg .= " O"; // Operator
	if(preg_match("/^(4|5|6|7|12|13|14|15|20|21|22|23|28|29|30|31)$/",$privileg)) $TS_privileg .= " V"; // Voice
	if(preg_match("/^(6|14|22|38|46|54|62)$/",$attribute)) $TS_privileg = " WV"; // RequestVoice
	if($attribute >= "64") $TS_privileg .= " Rec"; // Record
	
	return $TS_privileg;
}

//
//	totaltime
//
function TS_totaltime($totaltime)
{
	if($totaltime < 60 )
	{
		$playertotaltime = strftime("%S Sekunden", $totaltime);
	}
	else
	{
		if ($totaltime >= 3600 )
		{
			$playertotaltime = strftime("%H:%M:%S Stunden", $totaltime - 3600);
		}
		else
		{
			$playertotaltime = strftime("%M:%S Minuten", $totaltime);
		}
	}
	return $playertotaltime;
}

//
//	idltime
//
function TS_idletime($idletime)
{
	if ($idletime < 60 )
	{
		$playeridletime = strftime("%S Sekunden", $idletime);
	}
	else
	{
		if ($idletime >= 3600 )
		{
			$playeridletime = strftime("%H:%M:%S Stunden", $idletime - 3600);
		}
		else
		{
			$playeridletime = strftime("%M:%S Minuten", $idletime);
		}
	}
	return $playeridletime;
}
?>