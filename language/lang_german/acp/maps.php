<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	'TITLE'			=> 'Karte',
	'EXPLAIN'		=> 'Hier kann man die Maps(Karten) bearbeiten, löschen und nach eigener Vorstellungen sortieren.',
	
	'INPUT_DATA'	=> 'Kartendaten',

	'MAP_NAME'		=> 'Name',
	'TYPE'			=> 'Type',
	'MAIN'			=> 'Kategorie',
	'MAP_TAG'		=> 'Tag',
	'MAP_FILE'		=> 'Bilddatei',
	'MAP_INFO'		=> 'Karteninfo',
	'MAP_ORDER'		=> 'Reihenfolge',
	
	'TYPE_0'		=> 'Kategorie',
	'TYPE_1'		=> 'Karte',
	
	'MSG_SELECT_TAG_IMAGE'	=> 'Bitte Tag auswählen',
		
	'ERROR_SELECT_TAG'		=> 'Bitte ein <b>Tag</b> auswählen',
));

$lang = array_merge($lang, array(
	'radio:type' => array(
		0 => $lang['TYPE_0'],
		1 => $lang['TYPE_1'])
));

?>