<?php

/***
*
*
*	© by Richard Perry from www.greycube.com
*
*	modified by CodeKing for DZCP 10-29-2006 (mm-dd-yyyy)
*
*	Fixed by Corefreak Clan-sfh.de supportet by xOR
*
*	edit by Phoenix for CMS-CP 2009
*
*
*/

function get($request, $ip, $port, $game)
{
	$protocol = array(
		'halflife'		=> 'one',
		'halflifeold'	=> 'one',
		'quake3'		=> 'two',
		'callofduty'	=> 'two',
		'jediknight2'	=> 'two',
		'wolfenstein'	=> 'two',
		'startrekef'	=> 'two',
		'sof2'			=> 'two',
		'mohq3'			=> 'two',
		'mohgs'			=> 'three',
		'swat4'			=> 'three',
		'ut'			=> 'three',
		'tops'			=> 'three',
		'ut2003'		=> 'three',
		'ut2004'		=> 'three',
		'bf1942'		=> 'three',
		'battlefront'	=> 'three',
		'bfvietnam'		=> 'three',
		'aarmy'			=> 'three',
		'halo'			=> 'three',
		'cnc'			=> 'eleven',
		'vietcong'		=> 'three',
		'ravenshield'	=> 'four',
		'halflife2'		=> 'five',
		'bf2'			=> 'six',
		'bf2142'		=> 'six',
		'quakew'		=> 'seven',
		'farcry'		=> 'eight',
		'neverwinter'	=> 'nine',
		'fear'			=> 'nine',
		'vietcong2'		=> 'nine',
		'doom3'			=> 'ten',
		'quake4'		=> 'ten'
	);
	
	if ($request == 'protocol')
	{
		return $protocol;
	}
	
	if ($request == 'launch')
	{
		if ($ip != '' && $port != '' && $game != '')
		{
			if ($game == 'swat4' || $game == 'ut' || $game == 'tops' || $game == 'ut2003' || $game == 'ut2004' || $game == 'aarmy')
			{
				$port = $port + 1;
			}
			
			if ($game == 'vietcong')
			{
				$port = $port + 10000;
			}
			
			if ($game == 'ravenshield')
			{
				$port = $port + 1000;
			}
			
			if ($game == 'bf1942' || $game == 'farcry')
			{
				$port = $port + 123;
			}
			
			if ($game == 'bfvietnam' && $port == '15567')
			{
				$port = '23000';
			}
			
			$launch = "hlsw://" . $ip.":" . $port;
		}
		return $launch;
	}
}

function random()
{
	$str = '';
	
	for ($i=0; $i<4; $i++)
	{
		$str .= chr(mt_rand(0,255));
	}
	return $str;
}

function getSplitnums($data)
{
	preg_match("/splitnum(...)/is",$data,$regs);
	
	$splitnumString = $regs[1];
	
	$splitNum[0] = ord(substr($splitnumString,0,1));
	$splitNum[1] = ord(substr($splitnumString,1,1));
	$splitNum[2] = ord(substr($splitnumString,2,1));
	
	if ($splitNum[1] == 128)
	{
		$packetInfo = "1 of 1";
	}
	
	if ($splitNum[1] == 0)
	{
		$packetInfo = "1 of more";
	}
	
	if ($splitNum[1] == 129)
	{
		$packetInfo = "2 of 2";
	}
  	
	if ($splitNum[1] == 1)
	{
		$packetInfo = "2 of 3";
	}
  	
	if ($splitNum[1] == 130)
	{
		$packetInfo = "3 of 3";
	}
	
	return $splitNum;
}

function server_query($ip, $port, $qport, $game, $request)
{
	$protocol = get('protocol','','','');
	
	if ($protocol[$game] == 'one')
	{
		$data = query_one($ip, $port, $game, $request);
	}
	
	if ($protocol[$game] == 'two')
	{
		$data = query_two($ip, $port, $game, $request);
	}
	
	if ($protocol[$game] == 'three')
	{
		if (empty($qport))
		{
			$queryport = $port;
			
			if ($game == "ut" || $game == "aarmy" || $game == "swat4" || $game == "tops")
			{
				$queryport = $port + 1;
			}
			
			if ($game == "ut2003" || $game == "ut2004")
			{
				$queryport = $port + 10;
			}
			
			if ($game == "vietcong")
			{
				$queryport = "15425";
			}
			
			if ($game == "mohgs")
			{
				$queryport = "12300";
			}
			
			if ($game == "bf1942" && $port == "14567")
			{
				$queryport = "23000";
			}
			
			if ($game == "bfvietnam" && $port == "15567")
			{
				$queryport = "23000";
			}
		}
		else
		{
			$queryport = $qport;
		}
		
		$data = query_three($ip, $port, $queryport, $game, $request);
	}
	
	if ($protocol[$game] == 'four')
	{
		if (empty($qport))
		{
			$queryport = $port + 1000;
		}
		else
		{
			$queryport = $qport;
		}
		
		$data = query_four($ip, $port, $queryport, $game, $request);
	}
	
	if ($protocol[$game] == 'five')
	{
		$data = query_five($ip, $port, $game, $request);
	}
	
	if ($protocol[$game] == 'six')
	{
		if (empty($qport))
		{
			$queryport = 29900;
		}
		else
		{
			$queryport = $qport;
		}
		$data = query_six($ip, $port, $queryport, $game, $request);
	}
	
	if ($protocol[$game] == "seven")
	{
		$data = query_seven($ip, $port, $game, $request);
	}
	
	if ($protocol[$game] == "eight")
	{
		if (!empty($qport))
		{
			$queryport = $qport;
		}
		else
		{
			$queryport = $port;
			
			if ($game == "farcry")
			{
				$queryport = $port + 123;
			}
		}
		$data = query_eight($ip, $port, $queryport, $game, $request);
	}
	
	if ($protocol[$game] == "nine")
	{
		if (!empty($qport))
		{
			$queryport = $qport;
		}
		else
		{
			$queryport = $port;
			
			if ($game == "vietcong2" && $queryport <= 10000)
			{
				$queryport = 19967;
			}
		}
		$data = query_nine($ip, $port, $queryport, $game, $request);
	}
	
	if ($protocol[$game] == "ten")
	{
		if (empty($qport))
		{
			$queryport = $port;
		}
		else
		{
			$queryport = $qport;
		}
		$data = query_ten($ip, $port, $queryport, $game, $request);
	}
	
	if ($protocol[$game] == "eleven")
	{
		$data = query_eleven($ip, $port, $qport, $request);
	}
	
	if ($request == "info")
	{
		$data['status']   = TRUE;
		
		$data['gamemod']  = str_replace(" ", "", $data['gamemod']);
		$data['gamemod']  = trim(strtolower($data['gamemod']));
		$data['mapname']  = trim($data['mapname']);
/*		
		if (!trim($data['ip']))							{ $data['ip'] = $ip; }		
		if (!trim($data['port']))						{ $data['port'] = $port; }
		if (!trim($data['gametype']))					{ $data['gametype'] = $game; }
		if (!trim($data['gamemod']))					{ $data['gamemod']  = $game; }
		
		if (trim($data['hostname']) == "")				{ $data['status']   = FALSE; }
		if (trim($data['password']) == "")				{ $data['password'] = FALSE; }
		if (strtolower($data['password']) == "false")	{ $data['password'] = FALSE; }
		if (strtolower($data['password']) == "true")	{ $data['password'] = TRUE;  }
*/
	}
	
	return $data;
}

function query_one($ip, $port, $game, $request)
{
	$getchallengenumber = "\xFF\xFF\xFF\xFF\x55\xFF\xFF\xFF\xFF";
	
	$fp = @fsockopen("udp://$ip", $port, $errno, $errstr, 1);
	
	if (!$fp)
	{
		return FALSE;
	}
	
	stream_set_timeout($fp, 1, INT); stream_set_blocking($fp, true);
	
	if ($request == "players")
	{
		fwrite($fp, $getchallengenumber);
		
		$tmp = fread($fp, 4096);
		
		if (!$tmp) { fclose($fp); return FALSE; }
		
		$challengenumber = substr($tmp, 5, 4);
	}
	
	if ($request == "info")     { $challenge = "\xFF\xFF\xFF\xFFTSource Engine Query\x00"; }
	if ($request == "players")  { $challenge = "\xFF\xFF\xFF\xFF\x55" . $challengenumber;       }
	
    fwrite($fp, $challenge);

    $buffer = fread($fp, 4096);

    fclose($fp);

    $buffer = trim(substr($buffer, 4));

    if (!trim($buffer)) { return FALSE; }


    if ($request == "info")
    {
      $tmp = substr($buffer, 2);
      $tmp = explode("\x00", $tmp);

      $place = strlen($tmp[0].$tmp[1].$tmp[2].$tmp[3]) + 8;

      $data['gamemod']       = $tmp[2];
      $data['hostname']      = $tmp[0];
      $data['mapname']       = $tmp[1];
      $data['players']       = ord($buffer[$place]);
      $data['maxplayers']    = ord($buffer[$place + 1]);
      $data['password']      = ord($buffer[$place + 5]);

      $data['datatype']      = $buffer[0];
      $data['version']       = ord($buffer[1]);
      $data['description']   = $tmp[3];
      $data['botplayers']    = ord($buffer[$place + 2]);
      $data['server_type']   = $buffer[$place + 3];
      $data['server_os']     = $buffer[$place + 4];
      $data['server_bots']   = ord($buffer[$place + 2]);
      $data['server_secure'] = ord($buffer[$place + 6]);

      return $data;
    }

    if ($request == "players")
    {
      unset($playernumber);

      $position = 2;

      do
      {
        $playernumber++;

        $player[$playernumber]['id'] = ord($buffer[$position]);
        $position ++;

        while($buffer[$position] != "\x00" && $position < 5000)
        {
          $player[$playernumber]['name'] .= $buffer[$position];
          $position ++;
        }

        $player[$playernumber]['score'] = (ord($buffer[$position + 1]))
                                      + (ord($buffer[$position + 2]) * 256)
                                      + (ord($buffer[$position + 3]) * 65536)
                                      + (ord($buffer[$position + 4]) * 16777216);

        if ($player[$playernumber]['score'] > 2147483648) { $player[$playernumber]['score'] -= 4294967296; }

        $tmp = substr($buffer, $position + 5, 4);
        if (strlen($tmp) < 4) { return FALSE; }
        $tmp = unpack("f", $tmp);

        $timestamp = mktime(0, 0, $tmp[1]);
        if (!$tmp[1]) { $timestamp = mktime(0, 0, $tmp[""]); }

        $player[$playernumber]['time'] = date("H:i:s", $timestamp);

        $position += 9;
      }
      while ($position < strlen($buffer));

      return $player;
    }
  }

  function query_two($ip, $port, $game, $request)
  {

    if ($game == "mohq3")
    {
      $challenge = "\xFF\xFF\xFF\xFF\x02getstatus\x00";
    }
    else
    {
      $challenge = "\xFF\xFF\xFF\xFFgetstatus\x00";
    }

    $fp = @fsockopen("udp://$ip", $port, $errno, $errstr, 1);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, INT); stream_set_blocking($fp, true);

    fwrite($fp, $challenge);

    $tmp = fread($fp, 4096);

    fclose($fp);

    $tmp = trim($tmp);

    if (!$tmp) { return FALSE; }

     $rawdata = explode("\n", $tmp);

    $rawsetting = explode("\\", $rawdata[1]);

    for($i= 1; $i<count($rawsetting); $i++)
    {
      $rawsetting[$i] = strtolower($rawsetting[$i]);
      $rawsetting[$i] = preg_replace("/\^./", "", $rawsetting[$i]);
      $rawsetting[$i+1] = preg_replace("/\^./", "", $rawsetting[$i+1]);
      $settings[$rawsetting[$i]] = $rawsetting[$i+1];
      $i++;
    }

    unset($data);

    $data['gamemod']    = $settings['gamename'];
    $data['hostname']   = $settings['sv_hostname'];
    $data['mapname']    = strtolower($settings['mapname']);
    $data['players']    = count($rawdata) - 2;
    $data['maxplayers'] = $settings['sv_maxclients'];
    $data['password']   = $settings['g_needpass'];

    if (isset($settings['pswrd'])) { $data['password'] = $settings['pswrd']; }

    if ($request == "info") { return $data; }

    for($i=2; $i<count($rawdata); $i++)
    {
      if ($game == "sof2")
      {
        $tmp = explode(" ", $rawdata[$i], 4);
        $player[$i-1]['score']  = $tmp[0];
        $player[$i-1]['ping']   = $tmp[1];
        $player[$i-1]['deaths'] = $tmp[2];
        $player[$i-1]['name']   = substr(preg_replace("/\^./", "", $tmp[3]) , 1, -1);
      }
      else if ($game == "mohq3")
      {
        $tmp = explode(" ", $rawdata[$i], 2);
        $player[$i-1]['ping']   = $tmp[0];
        $player[$i-1]['name']   = substr(preg_replace("/\^./", "", $tmp[1]) , 1, -1);
      }
      else
      {
        $tmp = explode(" ", $rawdata[$i], 3);
        $player[$i-1]['score'] = $tmp[0];
        $player[$i-1]['ping']  = $tmp[1];
        $player[$i-1]['name']  = substr(preg_replace("/\^./", "", $tmp[2]) , 1, -1);
      }
    }

    if ($request == "players") { return $player; }

  }

  function query_three($ip, $port, $queryport, $game, $request)
  {
    if ($request == "info")    { $challenge = "\\basic\\\\info\\\\rules\\"; }
    if ($request == "players") { $challenge = "\\players\\"; }

    $fp = fsockopen("udp://$ip", $queryport, $errno, $errstr, 1);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, INT); stream_set_blocking($fp, true);

    fwrite($fp, $challenge);

    $buffer = fread($fp, 4096);

    if (!$buffer) { fclose($fp); return FALSE; }

    if (!strstr($buffer, "\\final\\"))
    {
      $buffer .= fread($fp, 4096);
    }

    if ($request == "players" && !strstr($buffer, "\\player_0\\") && strstr($buffer, "\\final\\") )
    {
      $buffer = fread($fp, 4096) . $buffer;
    }

    fclose($fp);

    $buffer = trim($buffer);

    if ($request == "info")
    {
      $buffer = explode("\\player_0", $buffer);
      $buffer = $buffer[0];

      $buffer = explode("\\leader_0", $buffer);
      $buffer = $buffer[0];

      $rawsetting = explode("\\", $buffer);

      for($i=1; $i<count($rawsetting); $i++)
      {
        $rawsetting[$i] = strtolower("$rawsetting[$i]");

        if ($rawsetting[$i] != "final" && $rawsetting[$i] != "queryid")
        {
          $settings[$rawsetting[$i]] = $rawsetting[$i+1];
        }

        $i++;
      }

      unset($data);

      $data['gamemod'] = $settings['gamename'];

      if (!$data['gamemod'] || $game == "bf1942")
      {
        $data['gamemod'] = $settings['gameid'];
      }

      $data['hostname'] = $settings['sv_hostname'];

      if (!$data['hostname']) { $data['hostname'] = $settings['hostname']; }

      $data['mapname']    = str_replace("_"," ",$settings['mapname']);
      $data['players']    = $settings['numplayers'];
      $data['maxplayers'] = $settings['maxplayers'];
      $data['password']   = $settings['password'];

      return $data;
    }

    if ($request == "players")
    {
      $rawsetting = explode("\\", $buffer);

      for($i=1; $i<count($rawsetting); $i++)
      {
        if (!strstr($rawsetting[$i], "_")) { $i++; continue; }

        $rawsetting[$i] = strtolower("$rawsetting[$i]");

        $buffer = explode("_", $rawsetting[$i], 2);

        if ($buffer[0] == "player")     { $buffer[0] = "name";  }
        if ($buffer[0] == "playername") { $buffer[0] = "name";  }
        if ($buffer[0] == "frags")      { $buffer[0] = "score"; }
        if ($buffer[0] == "ngsecret")   { $buffer[0] = "stats"; }

        if ($buffer[0] == "ping" && !$rawsetting[$i+1]) { $buffer[0] = "null"; }

        if (is_numeric($buffer[1]))
        {
          $player[$buffer[1]+1][$buffer[0]] = $rawsetting[$i+1];
        }

        $i++;
      }

      return $player;
    }
  }

  function query_four($ip, $port, $queryport, $game, $request)
  {


    $challenge = "REPORT";

    $fp = @fsockopen("udp://$ip", $queryport, $errno, $errstr, 1);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, INT); stream_set_blocking($fp, true);

    fwrite($fp, $challenge);

    $tmp = fread($fp, 4096);

    fclose($fp);

    if (!$tmp) { return FALSE; }

    $tmp = trim($tmp);


    $ravenshield_codes = array(	"P1" => "queryport",
					"E1" => "mapname",
					"I1" => "hostname",
					"F1" => "maptype",
					"A1" => "maxplayers",
					"G1" => "password",
					"H1" => "dedicated",
					"L1" => "playername",
					"M1" => "playertime",
					"N1" => "playerping",
					"O1" => "playerscore",
					"B1" => "players",
					"Q1" => "rounds",
					"R1" => "roundtime",
					"S1" => "bombtimer",
					"T1" => "bomb",
					"W1" => "allowteammatenames",
					"X1" => "iserver",
					"Y1" => "friendlyfire",
					"Z1" => "autobalance",
					"A2" => "tkpenalty",
					"D2" => "version",
					"B2" => "allowradar",
					"E2" => "lid",
					"F2" => "gid",
					"G2" => "hostport",
					"H2" => "terroristcount",
					"I2" => "aibackup",
					"J2" => "rotatemaponsuccess",
					"K2" => "forcefirstpersonweapons",
					"L2" => "gamename",
					"L3" => "punkbuster",
					"K1" => "mapcycle",
					"J1" => "mapcycletypes");


    $rawdata = explode("\xB6", $tmp);

    for($i=0; $i<count($rawdata); $i++)
    {
      $setting_code = substr($rawdata[$i], 0, 2);
      $setting_code = $ravenshield_codes[$setting_code];
      $rawdata[$i] = substr($rawdata[$i], 3);
      $settings[$setting_code] = trim($rawdata[$i]);
    }

    if ($request == "info")
    {
      unset($data);

      $data['gamemod']    = $settings['gamename'];
      $data['hostname']   = $settings['hostname'];
      $data['mapname']    = $settings['mapname'];
      $data['players']    = $settings['players'];
      $data['maxplayers'] = $settings['maxplayers'];
      $data['password']   = $settings['password'];

      return $data;
    }


    if ($request == "players")
    {
      $playername  = explode("/", $settings['playername']);
      $playertime  = explode("/", $settings['playertime']);
      $playerping  = explode("/", $settings['playerping']);
      $playerscore = explode("/", $settings['playerscore']);

      for($i=1; $i<count($playername); $i++)
      {
        $player[$i]['name']   = $playername[$i];
        $player[$i]['time']   = $playertime[$i];
        $player[$i]['ping']   = $playerping[$i];
        $player[$i]['score']  = $playerscore[$i];
      }

      return $player;
    }

  }

  function query_five($ip, $port, $game, $request)
  {
    $getchallengenumber = "\xFF\xFF\xFF\xFF\x57";

    $fp = @fsockopen("udp://$ip", $port, $errno, $errstr, 1);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, INT); stream_set_blocking($fp, true);

    if ($request == "players")
    {
      fwrite($fp, $getchallengenumber);

      $tmp = fread($fp, 4096);

      if (!$tmp) { fclose($fp); return FALSE; }

      $challengenumber = substr($tmp, 5, 4);
    }

    if ($request == "info")     { $challenge = "\xFF\xFF\xFF\xFFTSource Engine Query\x00"; }
    if ($request == "players")  { $challenge = "\xFF\xFF\xFF\xFF\x55" . $challengenumber;       }

    fwrite($fp, $challenge);

    $buffer = fread($fp, 4096);

    fclose($fp);

    $buffer = trim(substr($buffer, 4));

    if (!trim($buffer)) { return FALSE; }


    if ($request == "info")
    {
      $tmp = substr($buffer, 2);
      $tmp = explode("\x00", $tmp);

      $place = strlen($tmp[0].$tmp[1].$tmp[2].$tmp[3]) + 8;

      $data['gamemod']       = $tmp[2];
      $data['hostname']      = $tmp[0];
      $data['mapname']       = $tmp[1];
      $data['players']       = ord($buffer[$place]);
      $data['maxplayers']    = ord($buffer[$place + 1]);
      $data['password']      = ord($buffer[$place + 5]);

      $data['datatype']      = $buffer[0];
      $data['version']       = ord($buffer[1]);
      $data['description']   = $tmp[3];
      $data['botplayers']    = ord($buffer[$place + 2]);
      $data['server_type']   = $buffer[$place + 3];
      $data['server_os']     = $buffer[$place + 4];
      $data['server_bots']   = ord($buffer[$place + 2]);
      $data['server_secure'] = ord($buffer[$place + 6]);

      return $data;
    }

    if ($request == "players")
    {
      unset($playernumber);

      $position = 2;

      do
      {
        $playernumber++;

        $player[$playernumber]['id'] = ord($buffer[$position]);
        $position ++;

        while($buffer[$position] != "\x00" && $position < 5000)
        {
          $player[$playernumber]['name'] .= $buffer[$position];
          $position ++;
        }

        $player[$playernumber]['score'] = (ord($buffer[$position + 1]))
                                      + (ord($buffer[$position + 2]) * 256)
                                      + (ord($buffer[$position + 3]) * 65536)
                                      + (ord($buffer[$position + 4]) * 16777216);

        if ($player[$playernumber]['score'] > 2147483648) { $player[$playernumber]['score'] -= 4294967296; }

        $tmp = substr($buffer, $position + 5, 4);
        if (strlen($tmp) < 4) { return FALSE; }
        $tmp = unpack("f", $tmp);

        $timestamp = mktime(0, 0, $tmp[1]);
        if (!$tmp[1]) { $timestamp = mktime(0, 0, $tmp[""]); }

        $player[$playernumber]['time'] = date("H:i:s", $timestamp);

        $position += 9;
      }
      while ($position < strlen($buffer));

      return $player;
    }
  }

	function query_six($ip, $port, $queryport, $game, $request)
	{
		$fp = @fsockopen("udp://$ip", $queryport, $errno, $errstr, 1);
		
		if (!$fp)
		{
			return FALSE; 
		}
		
		stream_set_timeout($fp, 1, INT);
		stream_set_blocking($fp, true);
		
		$challenge_code = '';
		
		if ($game == "bf2142")
		{
			$challenge_code = "\xFE\xFD\x09\x21\x21\x21\x21\xFF\xFF\xFF\x01";
			
			fwrite($fp, $challenge_code);
			
			$challenge_packet = fread($fp, 1400);
			
			if (!trim($challenge_packet))
			{
				fclose($fp);
				return FALSE;
			}
			
			$challenge_code = substr($challenge_packet, 5, -1);
			$challenge_code = chr($challenge_code >> 24).chr($challenge_code >> 16).chr($challenge_code >> 8).chr($challenge_code >> 0);
		}
		
		$challenge = '';
		$challenge  = "\xFE\xFD\x00\x21\x21\x21\x21" . $challenge_code."\xFF\xFF\xFF\x01";
		
		fwrite($fp, $challenge);
		
		$packet_check = "/(hostname\\x00|player_\\x00|score_\\x00|ping_\\x00|deaths_\\x00|pid_\\x00|skill_\\x00|team_\\x00|team_t\\x00|score_t\\x00)/U";
		
		if ($game == "graw")
		{
			$packet_check_expected = 3;
		}
		else
		{
			$packet_check_expected = 10;
		}
		
		$packet1 = fread($fp, 1400);
		
		if (!trim($packet1))
		{
			fclose($fp);
			return FALSE;
		}
		
		$packet2 = '';
		$packet3 = '';
		
		preg_match_all($packet_check, $packet1, $matches);
		
		if (count(array_unique($matches[1])) < $packet_check_expected)
		{
			$packet2 = fread($fp, 1400);
			
			if (!trim($packet2))
			{
				fclose($fp);
				return FALSE;
			}
			
			preg_match_all($packet_check, $packet1.$packet2, $matches);
			
			if (count(array_unique($matches[1])) < $packet_check_expected)
			{
				$packet3 = fread($fp, 1400);
				
				if (!trim($packet3))
				{
					fclose($fp);
					return FALSE;
				}
				
				preg_match_all($packet_check, $packet1.$packet2.$packet3, $matches);
				
				if (count(array_unique($matches[1])) < $packet_check_expected)
				{
					fclose($fp);
					return FALSE;
				}
			}
		}
		fclose($fp); // CLOSE CONNECTION;
		
		if ( strstr($packet3, "hostname\x00") ) { $tmp = $packet3; $packet3 = $packet1; $packet1 = $tmp; }
		if ( strstr($packet2, "hostname\x00") ) { $tmp = $packet2; $packet2 = $packet1; $packet1 = $tmp; }
		if ( strstr($packet2, "score_t") )      { $tmp = $packet3; $packet3 = $packet2; $packet2 = $tmp; }

    if ($packet2 && substr($packet1, -1) != "\x00\x00")
    {
      $tmp = explode("\x00", $packet1);
      array_pop($tmp);
      array_pop($tmp);
      $tmp = implode("\x00", $tmp);
      $tmp .= "\x00\x00";
      $packet1 = $tmp;
    }

    if ($packet3 && substr($packet2, -2) != "\x00\x00")
    {
      $tmp = explode("\x00", $packet2);
      array_pop($tmp);
      array_pop($tmp);
      $tmp = implode("\x00", $tmp);
      $tmp .= "\x00\x00";
      $packet2 = $tmp;
    }

    $buffer = $packet1.$packet2.$packet3;
    $buffer = preg_replace("/\\x00\\x00....splitnum/U", "", $buffer);

    $server = substr($buffer, 16);
    $server = explode("\x01", $server, 2);
    $server = explode("\x00", $server[0]);

    for($i=0; $i<count($server); $i=$i+2)
    {
      if (!trim($server[$i])) { continue; }

      $server[$i] = strtolower("$server[$i]");
      $settings[$server[$i]] = $server[$i+1];
    }

    if ($request == "settings") { return $setting; }


    if ($request == "info")
    {
      unset($data);
      
      $gamemod = ($settings['gamename']=='stella'||$settings['gamename']=='stellad')
                 ?'bf2142':$settings['gamename'];
      $data['gamemod']    = $gamemod;
      $data['hostname']   = $settings['hostname'];
      $data['mapname']    = $settings['mapname'];
      $data['players']    = $settings['numplayers'];
      $data['maxplayers'] = $settings['maxplayers'];
      $data['password']   = $settings['password'];

      return $data;
    }

    if ($request == "players")
    {
      $buffer = explode("\x01", $buffer, 2);
      $buffer = $buffer[1];

      $name     = preg_match_all("/player_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $name     = explode("\x00", $match[1][0]."\x00" . $match[1][1]);

      if (!$name[0]) { return FALSE; }

      $score    = preg_match_all(" /score_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $score    = explode("\x00", $match[1][0]."\x00" . $match[1][1]);
      $ping     = preg_match_all("  /ping_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $ping     = explode("\x00", $match[1][0]."\x00" . $match[1][1]);
      $deaths   = preg_match_all("/deaths_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $deaths   = explode("\x00", $match[1][0]."\x00" . $match[1][1]);
      $pid      = preg_match_all("   /pid_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $pid      = explode("\x00", $match[1][0]."\x00" . $match[1][1]);
      $skill    = preg_match_all(" /skill_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $skill    = explode("\x00", $match[1][0]."\x00" . $match[1][1]);
      $team     = preg_match_all("  /team_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $team     = explode("\x00", $match[1][0]."\x00" . $match[1][1]);
      $teamname = preg_match_all(" /team_t\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $teamname = explode("\x00", $match[1][0]."\x00" . $match[1][1]);

      for($i=0; $i<count($name); $i++)
      {
        if (!$name[$i]) { continue; }

        $player[$i+1]['name']   = $name[$i];
        $player[$i+1]['score']  = $score[$i];
        $player[$i+1]['ping']   = $ping[$i];
        $player[$i+1]['deaths'] = $deaths[$i];
        $player[$i+1]['pid']    = $pid[$i];
        $player[$i+1]['skill']  = $skill[$i];
        $player[$i+1]['team']   = $teamname[$team[$i]-1];
      }
      return $player;
    }
  }

  function query_seven($ip, $port, $game, $request)
  {
    $challenge = "\xFF\xFF\xFF\xFFstatus\x00";

    $fp = @fsockopen("udp://$ip", $port, $errno, $errstr, 1);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, INT); stream_set_blocking($fp, true);

    fwrite($fp, $challenge);

    $buffer = fread($fp, 4096);

    fclose($fp);

    if (!$buffer) { return FALSE; }

    $buffer = substr($buffer, 6);
    $buffer = explode("\n", $buffer);

    $tmp = explode("\\", $buffer[0]);

    for($i=0; $i<=count($tmp); $i=$i+2)
    {
      if (!trim($tmp[$i])) { continue; }

      $tmp[$i] = strtolower("$tmp[$i]");
      $settings[$tmp[$i]] = $tmp[$i+1];
    }

    for($i=1; $i<=count($buffer); $i++)
    {
      if (!trim($buffer[$i])) { continue; }

      preg_match("/(.*) (.*) (.*) (.*) \"(.*)\" \"(.*)\" (.*) (.*)/U", $buffer[$i], $match);

      $player[$i]['pid']         = $match[1];
      $player[$i]['score']       = $match[2];
      $player[$i]['time']        = $match[3];
      $player[$i]['ping']        = $match[4];
      $player[$i]['name']        = $match[5];
      $player[$i]['skin']        = $match[6];
      $player[$i]['skin_top']    = $match[7];
      $player[$i]['skin_bottom'] = $match[8];

      $name_length = strlen($player[$i]['name']);

      for($char_pos=0; $char_pos<$name_length; $char_pos++)
      {
        $char = ord($player[$i]['name'][$char_pos]);

        if ($char > 141)
        {
          $player[$i]['name'][$char_pos] = chr($char-128);
        }

        $char = ord($player[$i]['name'][$char_pos]);

        if ($char < 32)
        {
          $player[$i]['name'][$char_pos] = chr($char+30);
        }
      }
    }

    if ($request == "players") { return $player; }

    $data['gamemod']    = $settings['*gamedir'];
    $data['hostname']   = $settings['hostname'];
    $data['mapname']    = $settings['map'];
    $data['players']    = count($player);
    $data['maxplayers'] = $settings['maxclients'];
    $data['password']   = ($settings['needpass'] > 0 && $settings['needpass'] < 4 ? TRUE:FALSE);

    return $data;
  }

  function query_eight($ip, $port, $queryport, $game, $request)
  {
    $challenge = "s";

    $fp = @fsockopen("udp://$ip", $queryport, $errno, $errstr, 1);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, INT); stream_set_blocking($fp, true);

    fwrite($fp, $challenge);

    $buffer = fread($fp, 4096);

    fclose($fp);

    if (!$buffer) { return FALSE; }

    $buffer = substr($buffer, 4);

    $buffer_part = explode("\x01", $buffer, 2);

    $buffer = $buffer_part[0];

    $position = 0;

    do
    {
      $rawsetting[] = substr($buffer, $position+1, ord($buffer[$position])-1);

      $position = $position + ord($buffer[$position]);
    }
    while ($position < strlen($buffer));

    $settings['game']       = $rawsetting[0];
    $settings['port']       = $rawsetting[1];
    $settings['hostname']   = preg_replace("/\\$\d/", "", $rawsetting[2]);
    $settings['mode']       = $rawsetting[3];
    $settings['mapname']    = $rawsetting[4];
    $settings['version']    = $rawsetting[5];
    $settings['password']   = $rawsetting[6];
    $settings['players']    = $rawsetting[7];
    $settings['maxplayers'] = $rawsetting[8];

    for($i=9; $i<=count($rawsetting); $i=$i+2)
    {
      if (!trim($rawsetting[$i])) { continue; }

      $rawsetting[$i] = strtolower("$rawsetting[$i]");
      $settings[$rawsetting[$i]] = $rawsetting[$i+1];
    }

    $data['gamemod']    = $settings['gr_ssmod'];
    $data['hostname']   = $settings['hostname'];
    $data['mapname']    = $settings['mapname'];
    $data['players']    = $settings['players'];
    $data['maxplayers'] = $settings['maxplayers'];
    $data['password']   = $settings['password'];

    if ($request == "info") { return $data; }

    $buffer = $buffer_part[1];

    if (!$buffer[0]) { return FALSE; exit; }

    $player_id  = 0;
    $position   = 0;

    do
    {
      unset($field_list);

      if (ord($buffer[$position]) & 1)  { $field_list[] = "name";         }
      if (ord($buffer[$position]) & 2)  { $field_list[] = "team";         }
      if (ord($buffer[$position]) & 4)  { $field_list[] = "skin_NOTUSED"; }
      if (ord($buffer[$position]) & 8)  { $field_list[] = "score";        }
      if (ord($buffer[$position]) & 16) { $field_list[] = "ping";         }
      if (ord($buffer[$position]) & 32) { $field_list[] = "time";         }

      $player_id++;
      $position++;

      foreach ($field_list as $field)
      {
        $increment = ord($buffer[$position]);

        $player[$player_id][$field] = substr($buffer, $position+1, $increment-1);

        if ($field == "name")
        {
          $player[$player_id] = preg_replace("/\\$\d/", "", $player[$player_id]);
        }

        $position += $increment;
      }
    }
    while ($position < strlen($buffer));

    return $player;
  }

  function query_nine($ip, $port, $queryport, $game, $request)
  {
    if ($request == "info")     { $challenge = "\xFE\xFD\x00\xAA\xAA\xAA\xAA\xFF\x00\x00"; }
    if ($request == "players")  { $challenge = "\xFE\xFD\x00\xAA\xAA\xAA\xAA\x00\xFF\x00"; }

    $fp = @fsockopen("udp://$ip", $queryport, $errno, $errstr, 1);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, INT); stream_set_blocking($fp, true);

    fwrite($fp, $challenge);

    $buffer = fread($fp, 4096);

    fclose($fp);

    if (!$buffer) { return FALSE; }

    $buffer = trim(substr($buffer, 5));

    if ($request == "info")
    {
      $rawsetting = explode("\x00",$buffer);

      for($i=0; $i<count($rawsetting); $i=$i+2)
      {
        if (!trim($rawsetting[$i])) { continue; }

        $rawsetting[$i] = strtolower("$rawsetting[$i]");
        $settings[$rawsetting[$i]] = $rawsetting[$i+1];
      }
    }

    if ($request == "info")
    {
      $data['hostname']   = $settings['hostname'];
      $data['mapname']    = $settings['mapname'];
      $data['players']    = $settings['numplayers'];
      $data['maxplayers'] = $settings['maxplayers'];
      $data['password']   = $settings['password'];

      return $data;
    }

    if ($request == "players")
    {
      if ($game == "neverwinter" || $game == "vietcong2")
      {
        $player[1]['name'] = "This Game Does Not Provide Player Information"; return $player;
      }

      $tmp = explode("\x00\x00",$buffer);

      $rawsetting = explode("\x00",$tmp[1]);

      $player_number = 0;

      for($i=0; $i<count($rawsetting); $i=$i+3)
      {
        $player_number++;
        $player[$player_number]['name']  = $rawsetting[$i];
        $player[$player_number]['score'] = $rawsetting[$i+1];
        $player[$player_number]['ping']  = $rawsetting[$i+2];
      }

      return $player;
    }
  }

  function query_ten($ip, $port, $queryport, $game, $request)
  {
    $challenge = "\xFF\xFFgetInfo";

    $fp = @fsockopen("udp://$ip", $port, $errno, $errstr, 1);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, INT); stream_set_blocking($fp, true);

    fwrite($fp, $challenge);

    $buffer = fread($fp, 4096);

    fclose($fp);

    if (!$buffer) { return FALSE; }

    $buffer = substr($buffer, 23);

    $buffer = explode("\x00\x00\x00", $buffer);

    $rawsetting = explode("\x00", $buffer[0]);

    for($i=0; $i<count($rawsetting); $i=$i+2)
    {
      $rawsetting[$i] = strtolower($rawsetting[$i]);
      $rawsetting[$i] = preg_replace("/\^./", "", $rawsetting[$i]);
      $rawsetting[$i+1] = preg_replace("/\^./", "", $rawsetting[$i+1]);
      $settings[$rawsetting[$i]] = $rawsetting[$i+1];
    }

    if ($game == "doom3")
    {
      preg_match_all("/(.)(..)(..)(..)(.*)\\x00/U", $buffer[1], $matches); // DOOM3
    }
    else
    {
      preg_match_all("/(.)(..)(..)(..)(.*)\\x00(.*)\\x00/U", $buffer[1], $matches); // QUAKE4
    }

    for($i=0; $i<count($matches[5]); $i++)
    {
      $player[$i+1]['id']          = ord($matches[1][$i]);
      list(,$player[$i+1]['ping']) = unpack("s", $matches[2][$i]);
      list(,$player[$i+1]['rate']) = unpack("s", $matches[3][$i]);
      $player[$i+1]['name']        = preg_replace("/\^./", "", $matches[5][$i]);
      $player[$i+1]['tag']         = preg_replace("/\^./", "", $matches[6][$i]); // QUAKE4

      if ($player[$i+1]['tag']) { $player[$i+1]['name'] = $player[$i+1]['tag']." " . $player[$i+1]['name']; }
    }

    if($request == "players") { return $player;  }

    $data['gamemod']    = $settings['gamename'];
    $data['hostname']   = $settings['si_name'];
    $data['mapname']    = $settings['si_map'];
    $data['players']    = count($player);
    $data['maxplayers'] = $settings['si_maxplayers'];
    $data['password']   = $settings['si_usepass'];

    return $data;
  }

  function query_eleven($ip, $port, $queryport, $request)
  {
    $i = 0;
    $list = array();
    $connect = fsockopen("udp://" . $ip, $queryport, $errno, $errstr, 30);
    if($connect)
    {
      socket_set_timeout ($connect, 1, 000000);
      $send = "\\info\\";
      fputs($connect, $send);
      fwrite ($connect, $send);
      $output = fread ($connect, 1);

      if(!empty($output)) {
       do {
         $status_pre = socket_get_status ($connect);
         $out = fread ($connect, 1);

    	 if ($out=="\\") { if(empty ($b)) $b=1;

    	  $list[$b++]=$output;
    	  $output="";
         } else {
          $output = $output . $out;
         }
         $status_post = socket_get_status ($connect);
       } while ($status_pre['unread_bytes'] != $status_post['unread_bytes']);
      };
      fclose($connect);
      $data = ($output);
      $temp = array();
      $players = array();
      $stat = array();
      $stat2 = array();
      $temp = preg_split('/\n/', $data);
      for($i=0;$i<count($temp);$i++) {
        if ($i>0) {
         $players[$i-1]=$temp[$i];
        }
      }
  
      for($i=0;$i<count($list);$i++)
      {
        if($i>0)
        {
          if($list[$i] == "\gamename")  $gamename = $list[$i+1];
          if($list[$i] == "gametype")   $g_gametype = $list[$i+1];
          if($list[$i] == "mapname")    $mapname = $list[$i+1];
          if($list[$i] == "password")   $pswrd = $list[$i+1];
          if($list[$i] == "maxplayers") $sv_maxclients = $list[$i+1];
          if($list[$i] == "hostname")   $servername = $list[$i+1];
          if($list[$i] == "numplayers") $numplayers = $list[$i+1];
          if($list[$i] == "timeleft")   $timeleft = $list[$i+1];
  
       	  if(empty($p)) $p=0;
  
      	  if($list[$i] == "player_$p") 
          {	
            $p++;
      		  $player_name[$p]  = $list[$i+1];
      		  $player_score[$p] = $list[$i-1];
      		  $player_ping[$p]  = $list[$i-3] ;
      	  }
        }
      }
  
      if($request == "info")
      {
        $data['gametype']   = "cnc";
        $data['gamemod']    = $gamename;
        $data['hostname']   = $servername;
        $data['mapname']    = $mapname;
        $data['players']    = $numplayers;
        $data['maxplayers'] = $sv_maxclients;
        $data['password']   = $pswrd;
  
        return $data;
      }
      
      if($request == "players")
      {
        for($i=0;$i<count($player_name);$i++)
        {
        	$player[$i+1]['name']  = 	$player_name[$i+1];
        	$player[$i+1]['score'] = 	$player_score[$i+1];
          $player[$i+1]['ping']  =	$player_ping[$i+1];
        }
  
        return $player;
      }
    }
  }
?>