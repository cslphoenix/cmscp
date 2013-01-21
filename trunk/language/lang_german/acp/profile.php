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
	'profile_show_user'		=> 'f�r Benutzer sichtbar',
	'profile_show_member'	=> 'f�r Member sichtbar',
	'profile_show_register'	=> 'f�r Registierung sichtbar',
	'profile_required'		=> 'Pflichtfeld',
	
	'profile_field_explain' => 'Der Pr�fix \'field_\' wird automatisch hinzugef�gt',
	'profile_typ_explain'	=> 'Die Art des Feldes bestimmt, wie viele Zeichen in des Feld geschrieben werden k�nnen!<br /><b>Textzeilen</b> fassen 255 Zeichen<br /><b>Textfeld</b> umfasst 65000 Zeichen<br /><b>Ja/Nein</b> Feld fast 1 Zahl!',
	
#	'necessary'	=> 'Pflichtfeld',


	'show_guest'	=> 'f�r G�ste',
	'show_member'	=> 'f�r Mitglieder',
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