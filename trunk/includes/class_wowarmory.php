<?php

class armory
{
	const BROWSER="Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3";
	
	public $query;
	public $server;
	public $guild;
	public $guildie;
	public $page;
	
	public function __construct($query, $server, $guild, $guildie, $page)
	{
		$this->query = $query;
		$this->server = $server;
		$this->guild = $guild;
		$this->guildie = $guildie;
		$this->page = $page;
	}
	
	public function pull_xml()
	{
		// change the first part of the $url to the armory link that you need
		if ( $this->query === 'roster' )
		{
		#	$url = 'http://eu.wowarmory.com/guild-info.xml?r=' . urlencode($this->server) . '&n=' . urlencode($this->guild) . '&p=' . $this->page;
		}
		else if ( $this->query === 'character' )
		{
			$url = 'http://eu.wowarmory.com/character-sheet.xml?r=' . urlencode($this->server) . '&n=' . $this->guildie;
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_USERAGENT,  self::BROWSER);
		$url_string = curl_exec($ch);
		curl_close($ch);
		
		return simplexml_load_string($url_string);
	} // end of pull_xml()
} // end class 
/*
And then to use it:

Syntax:
$armory = new armory( [character or roster] , realm , [guild name or NULL] , character name , [page number (guilds only) or NULL];

Example:
$armory = new armory(character, hellscream, NULL, mortisimus, NULL);
$xml = $armory->pull_xml(); 

$armory = new armory(character, frostwolf, NULL, Longor , NULL);

$xml = $armory->pull_xml(); 

$armory = new armory(character, hellscream, NULL, mortisimus, NULL);
$xml = $armory->pull_xml();
echo $xml->characterInfo->character['name'];

include($root_path . 'includes/class_wowarmory.php');

$armory = new armory(character, frostwolf, NULL, Longor , NULL);

$xml = $armory->pull_xml();
echo $xml->characterInfo->character['name'];
*/

?>