<?php
/***
*
*	© by Dooki: dooki@php-gfx.net
*
*	change by Phoenix
*
***/

class tss2info
{
	////// TeamSpeak Einstellungen ///////////////////////////
	var $sitetitle       = "TS"; // SeitenTitle und Scriptversion
//	var $serverAddress   = '88.198.52.237'; // Hier die TeamSpeak IP Adresse eintragen !!wichtig!! (Beispiel: 192.168.7.1)
//	var $serverQueryPort = "26384"; // TeamSpeak QueryPort.. Schau in die server.ini von TeamSpeak (Standard 51234)
//	var $serverUDPPort   = "8767"; // UDP Port für Teamspeak der auch hinter der IP Adresse genutzt wird (Standard 8767)
	var $serverPasswort  = ""; // Serverpasswort das bei Serversettings eingestellt wird (wenn kein Passwort erteilt, dann leer lassen)
//	var $serverAddress   = "tempelball.de"; // Hier die TeamSpeak IP Adresse eintragen !!wichtig!! (Beispiel: 192.168.7.1)
//	var $serverQueryPort = "51234"; // TeamSpeak QueryPort.. Schau in die server.ini von TeamSpeak (Standard 51234)
//	var $serverUDPPort   = "6002"; // UDP Port für Teamspeak der auch hinter der IP Adresse genutzt wird (Standard 8767)
//	var $serverPasswort  = ""; // Serverpasswort das bei Serversettings eingestellt wird (wenn kein Passwort erteilt, dann leer lassen)
	// (Passwort wird meistens bei Clanservern gebraucht)
	//
	//
	//////////////////////////////////////////////////////////
	////// Erweiterte Einstellungen //////////////////////////
	//
//	var $tabellenbreite    = "150"; // Mindestbreite der Teamspeaktabelle (die einbindung mit einem IFRAME sollte 20px mehr betragen)
	var $alternativer_nick = "TS-Viewer-Guest"; // Alternativer Gastname
	
	var $TS_userstatus_ausgabe     = 1;   // Soll der Status des Players angezeigt werden? (U,R,SA etc.)
	var $TS_leerchannel_anzeigen   = 1;   // Sollen die leeren Channel angezeigt werden?
//	var $TS_title_anzeigen         = 0;   // Soll der Title über den Channels sichtbar sein?
	var $TS_channelflags_ausgabe   = 1;   // Sollen die Channelrechte angezeigt werden? (R,M,S,P etc.)
	
	var $TS_refresh                = 1;   // Refreshen generell erlauben (inkl. Refreshlink)
	var $TS_autorefresh            = 1;   // Autorefresh erlauben oder nicht
	var $TS_autorefresh_zeit       = 10;  // Zeit in Sekunden angeben (Funktioniert nur, wenn autorefresh aktiviert wurde)
	
	var $TS_overlib_mouseover      = 0;   // Soll der Mouseover Effekt vorhanden sein?
	
//	var $TS_channel_anzeigen       = 1;   // Sollen die Channel angezeigt werden? (0 = nur Playerausgabe)

	var $TS_hide_channels          = array(); // Welche Channels sollen versteckt werden?
	//
	// Beispiel: array(CHANNELID,CHANNELID,CHANNELID,CHANNELID)
	//
	
	var $errno = '';
	var $errstr = '';
	
	/*******************************************************/
	/* Ab hier darf >>> KEIN <<< Text mehr geändert werden */
	/*******************************************************/
	
	//internal
	var $socket;
	
	// external
	var $serverStatus = "offline";
	var $playerList = array();
	var $channelList = array();

	// opens a connection to the teamspeak server
	function getSocket($host, $port, $errno, $errstr, $timeout)
	{
		$attempts = 1;
		
		while ($attempts <= 1)
		{
			$attempts++;
			
			$socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
			
			$this->errno = $errno;
			$this->errstr = $errstr;
			
			if ($socket and fread($socket, 4) == "[TS]")
			{
				fgets($socket, 128);
				
				return $socket;
			}
		}// end while
		
		return false;
	}// end function getSocket(...)
	
	// sends a query to the teamspeak server
	function sendQuery($socket, $query)
	{
		fputs($socket, $query."\n");
	}// end function sendQuery(...)
	
	// answer OK?
	function getOK($socket)
	{
		$result = fread($socket, 2);
		
		fgets($socket, 128);
		
		return ($result == "OK");
	}// end function getOK(...)
	
	// closes the connection to the teamspeak server
	function closeSocket($socket)
	{
		fputs($socket, "quit");
		fclose($socket);
	}// end function closeSocket(...)
	
	// retrieves the next argument in a tabulator-separated string (PHP scanf function bug workaround)
	function getNext($evalString)
	{
		$pos = strpos($evalString, "\t");
		if (is_integer($pos))
		{
			return substr($evalString, 0, $pos);
		}
		else
		{
			return $evalString;
		}// end if
	}// end function getNext($evalString);
	
	// removes the first argument in a tabulator-separated string (PHP scanf function bug workaround)
	function chopNext($evalString)
	{
		$pos = strpos($evalString, "\t");
		
		if (is_integer($pos))
		{
			return substr($evalString, $pos + 1);
		}
		else
		{
			return "";
		}// end if
	}// end function chopNext($evalString)
	
	// strips the quotes around a string
	function stripQuotes($evalString)
	{
		if (strpos($evalString, '"') == 0)
		{
			$evalString = substr($evalString, 1, strlen($evalString) - 1);
		}
		
		if (strrpos($evalString, '"') == strlen($evalString) - 1)
		{
			$evalString = substr($evalString, 0, strlen($evalString) - 1);
		}
		
		return htmlentities($evalString);
	}// end function stripQuotes($evalString)
	
	// returns the codec name
	function getVerboseCodec($codec)
	{
		if($codec == 0)
		{
			$codec = "CELP 5.1 Kbit";
		}
		else if ($codec == 1)
		{
			$codec = "CELP 6.3 Kbit";
		}
		else if ($codec == 2)
		{
			$codec = "GSM 14.8 Kbit";
		}
		else if ($codec == 3)
		{
			$codec = "GSM 16.4 Kbit";
		}
		else if ($codec == 4)
		{
			$codec = "CELP Windows 5.2 Kbit";
		}
		else if ($codec == 5)
		{
			$codec = "Speex 3.4 Kbit";
		}
		else if ($codec == 6)
		{
			$codec = "Speex 5.2 Kbit";
		}
		else if ($codec == 7)
		{
			$codec = "Speex 7.2 Kbit";
		}
		else if ($codec == 8)
		{
			$codec = "Speex 9.3 Kbit";
		}
		else if ($codec == 9)
		{
			$codec = "Speex 12.3 Kbit";
		}
		else if ($codec == 10)
		{
			$codec = "Speex 16.3 Kbit";
		}
		else if ($codec == 11)
		{
			$codec = "Speex 19.5 Kbit";
		}
		else if ($codec == 12)
		{
			$codec = "Speex 25.9 Kbit";
		}
		else
		{
			$codec = "unknown (" . $codec.")";
		}// end if
		
		return $codec;
	}// end function getVerboseCodec($codec);
	
	function getInfo()
	{
		// ---=== main program ===---
		
		$errno = '';
		$errstr = '';
		// establish connection to teamspeak server
		$this->socket = $this->getSocket($this->serverAddress, $this->serverQueryPort, $errno, $errstr, 0.3);
		
		if ($this->socket == false)
		{
			return;
		
		}
		else
		{
			$this->serverStatus = "online";
			
			// select the one and only running server on port 8767
			$this->sendQuery($this->socket, "sel " . $this->serverUDPPort);
			
			// retrieve answer "OK"
			if (!$this->getOK($this->socket))
			{
				return;
			}// end if

			// retrieve player list
			$this->sendQuery($this->socket,"pl");
			
			// read player info
			$this->playerList = array();
			do {
				$playerinfo = fscanf($this->socket, "%s %d %d %d %d %d %d %d %d %d %d %d %d %s %[^\t]");
				list($playerid, $channelid, $receivedpackets, $receivedbytes, $sentpackets, $sentbytes, $paketlost, $pingtime, $totaltime, $idletime, $privileg, $userstatus, $attribute, $s, $playername) = $playerinfo;
				
				if ($playerid != "OK")
				{
					$this->playerList[$playerid] = array(
						"playerid"			=> $playerid,
						"channelid"			=> $channelid,
						"receivedpackets"	=> $receivedpackets,
						"receivedbytes"		=> $receivedbytes,
						"sentpackets"		=> $sentpackets,
						"sentbytes"			=> $sentbytes,
						"paketlost"			=> $paketlost / 100,
						"pingtime"			=> $pingtime,
						"totaltime"			=> $totaltime,
						"idletime"			=> $idletime,
						"privileg"			=> $privileg,
						"userstatus"		=> $userstatus,
						"attribute"			=> $attribute,
						"s"					=> $this->stripQuotes($s),
						"playername"		=> $this->stripQuotes($playername)
					);
				}// end if
			}
			while ($playerid != "OK");
			// retrieve channel list
			
			$this->sendQuery($this->socket,"cl");
			
			// read channel info
			$this->channelList = array();
			do {
				$channelinfo = "";
				do {
					$input = fread($this->socket, 1);
					
					if ($input != "\n" && $input != "\r")
					{
						$channelinfo .= $input;
					}
				}
				while ($input != "\n");
				
				$channelid			= $this->getNext($channelinfo); $channelinfo = $this->chopNext($channelinfo);
				$channelcodec		= $this->getNext($channelinfo); $channelinfo = $this->chopNext($channelinfo);
				$channelparent		= $this->getNext($channelinfo); $channelinfo = $this->chopNext($channelinfo);
				$channelorder		= $this->getNext($channelinfo); $channelinfo = $this->chopNext($channelinfo);
				$channelmaxplayers	= $this->getNext($channelinfo); $channelinfo = $this->chopNext($channelinfo);
				$channelname		= $this->getNext($channelinfo); $channelinfo = $this->chopNext($channelinfo);
				$channelflags		= $this->getNext($channelinfo); $channelinfo = $this->chopNext($channelinfo);
				$channelpasswort	= $this->getNext($channelinfo); $channelinfo = $this->chopNext($channelinfo);
				$channeltopic		= $this->getNext($channelinfo);
				
				if ($channelid != "OK")
				{
					// determine number of players in channel
					$playercount = 0;
					
					foreach ($this->playerList as $playerInfo)
					{
						if ($playerInfo['channelid'] == $channelid)
						{
							$playercount++;
						}
					}// end foreach
					
					$this->channelList[$channelid] = array(
						"channelid"				=> $channelid,
						"channelcodec"			=> $this->getVerboseCodec($channelcodec),
						"channelparent"			=> $channelparent,
						"channelorder"			=> $channelorder,
						"channelmaxplayers"		=> $channelmaxplayers,
						"channelname"			=> $this->stripQuotes($channelname),
						"channelflags"			=> $channelflags,
						"channelpasswort"		=> $channelpasswort,
						"channeltopic"			=> $this->stripQuotes($channeltopic),
						"channelcurrentplayers"	=> $playercount
					);
				}// end if
			}
			while ($channelid != "OK");
			// retrieve channel list
			
			// close connection to teamspeak server
			$this->closeSocket($this->socket);
		}// end getInfo()
	}// class tss2info
}

$tss2info = new tss2info;

?>
