<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	'TITLE'				=> 'Network',
	'EXPLAIN'			=> 'Hier kannst Du Links, Partner und Sponsoren verwalten.',
	
	'INPUT_DATA'		=> 'Daten',
	
	'NETWORK_NAME'		=> 'Name',
	'NETWORK_URL'		=> 'Link',
	'NETWORK_IMAGE'		=> 'Bild',
	'NETWORK_TYPE'		=> 'Typ',
	'NETWORK_VIEW'		=> 'Anzeigen',

	'NETWORK_LINK'		=> 'Link',
	'NETWORK_PARTNER'	=> 'Partner',
	'NETWORK_SPONSOR'	=> 'Sponsor',
));

$lang = array_merge($lang, array(
	'radio:network'	=> array(
		NETWORK_LINK	=> $lang['NETWORK_LINK'],
		NETWORK_PARTNER	=> $lang['NETWORK_PARTNER'],
		NETWORK_SPONSOR	=> $lang['NETWORK_SPONSOR']
	),
));

?>