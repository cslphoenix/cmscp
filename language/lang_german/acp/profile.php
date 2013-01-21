<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Profilefelder',
	'explain'	=> 'Hier kann man ja halt irgendwas machen xD',
	
	'profile_name'			=> 'Name',
	'type'					=> 'Type',
	'main'					=> 'Kategorie',
	'profile_field'			=> 'Feld',
	'profile_typ'			=> 'Art',
	'profile_lang'			=> 'Sprachdatei',
	'profile_show_user'		=> 'für Benutzer sichtbar',
	'profile_show_member'	=> 'für Member sichtbar',
	'profile_show_register'	=> 'für Registierung sichtbar',
	'profile_required'		=> 'Pflichtfeld',
	
	'profile_field_explain' => 'Der Präfix \'field_\' wird automatisch hinzugefügt',
	'profile_typ_explain'	=> 'Die Art des Feldes bestimmt, wie viele Zeichen in des Feld geschrieben werden können!<br /><b>Textzeilen</b> fassen 255 Zeichen<br /><b>Textfeld</b> umfasst 65000 Zeichen<br /><b>Ja/Nein</b> Feld fast 1 Zahl!',
	
#	'necessary'	=> 'Pflichtfeld',


	'show_guest'	=> 'für Gäste',
	'show_member'	=> 'für Mitglieder',
	'show_register'	=> 'beim registieren',
	
	
	
	'profile_text'	=> 'Textzeile',
	'profile_area'	=> 'Textfeld',
	'profile_type'	=> 'Ja/Nein Option',
	
	'field'		=> 'Profilefeld',
	'cat'		=> 'Profilekategrie',
	
	'type_0'	=> 'Kategrie',
	'type_1'	=> 'Profilefeld',
	
));

$lang = array_merge($lang, array(
	
	'radio:type' => array(
		0 => $lang['cat'],
		1 => $lang['field']
	),
	
	'radio:typ' => array(
		PROFILE_TEXT => $lang['profile_text'],
		PROFILE_AREA => $lang['profile_area'],
		PROFILE_TYPE => $lang['profile_type'],
	),
	
));

?>