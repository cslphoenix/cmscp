<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Network',
	'explain'	=> 'Hier kannst Du Links, Partner und Sponsoren verwalten.',
	
	'data_input'	=> 'Daten',
	
	'network_name'	=> 'Name',
	'network_url'	=> 'Link',
	'network_image'	=> 'Bild',
	'network_type'	=> 'Typ',
	'network_view'	=> 'Anzeigen',

	'network_link'		=> 'Link',
	'network_partner'	=> 'Partner',
	'network_sponsor'	=> 'Sponsor',

));

$lang = array_merge($lang, array(
	
	'radio:network'	=> array(NETWORK_LINK => $lang['network_link'], NETWORK_PARTNER => $lang['network_partner'], NETWORK_SPONSOR => $lang['network_sponsor']),
	
));

?>