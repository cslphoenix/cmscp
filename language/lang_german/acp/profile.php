<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	'TITLE'		=> 'Profilefelder',
	'EXPLAIN'	=> 'Hier kann man ja halt irgendwas machen xD',
	
	'INPUT_DATA'	=> 'Felderdaten',
	
	'PROFILE_NAME'			=> 'Name',
	'TYPE'					=> 'Type',
	'MAIN'					=> 'Kategorie',
	'PROFILE_FIELD'			=> 'Feld',
	'PROFILE_TYP'			=> 'Art',
	'profile_lang'			=> 'Sprachdatei',
	'PROFILE_SHOW_USER'		=> 'für Benutzer sichtbar',
	'PROFILE_SHOW_MEMBER'	=> 'für Member sichtbar',
	'PROFILE_SHOW_REGISTER'	=> 'für Registierung sichtbar',
	'PROFILE_REQUIRED'		=> 'Pflichtfeld',
	
	'PROFILE_FIELD_EXLPAIN' => 'Der Präfix \'field_\' wird automatisch hinzugefügt',
	'PROFILE_TYP_EXPLAIN'	=> 'Die Art des Feldes bestimmt, wie viele Zeichen in des Feld geschrieben werden können!<br /><b>Textzeilen</b> fassen 255 Zeichen<br /><b>Textfeld</b> umfasst 65000 Zeichen<br /><b>Ja/Nein</b> Feld fast 1 Zahl!',

	'PROFILE_TEXT'	=> 'Textzeile',
	'PROFILE_AREA'	=> 'Textfeld',
	'PROFILE_TYPE'	=> 'Ja/Nein Option',
		
	'TYPE_0'	=> 'Kategrie',
	'TYPE_1'	=> 'Profilefeld',	
));

$lang = array_merge($lang, array(
	'radio:type' => array(
		0 => $lang['TYPE_0'],
		1 => $lang['TYPE_1']
	),
	'radio:typ' => array(
		PROFILE_TEXT => $lang['PROFILE_TEXT'],
		PROFILE_AREA => $lang['PROFILE_AREA'],
		PROFILE_TYPE => $lang['PROFILE_TYPE'],
	),
));

?>