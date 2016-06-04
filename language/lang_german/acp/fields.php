<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'	=> 'Profilefelder',
	'profile_c'	=> 'Profilekategorie',
	'explain'	=> 'Hier kann man ja halt irgendwas machen xD',

	'field'		=> 'Profilefeld',
	'cat'		=> 'Profilekategrie',
	'cats'		=> 'Profilekategrien',
	
	'show_guest'	=> 'für Gäste',
	'show_member'	=> 'für Mitglieder',
	'show_register'	=> 'beim registieren',
	
	'necessary'	=> 'Pflichtfeld',
	
	'type_text'		=> 'Textzeile',
	'type_area'		=> 'Textfeld',
	'type_radio'	=> 'Ja/Nein Option',
	
	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Feld'),
	'radio:typ'		=> array(FIELD_TEXT => 'Textzeile', FIELD_AREA => 'Textfeld', FIELD_TYPE => 'Ja/Nein Option'),
));
	
?>