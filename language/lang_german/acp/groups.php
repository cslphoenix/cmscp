<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'TITLE'		=> 'Benutzergruppe',
	'TITLES'	=> 'Benutzergruppen',
	
	'EXPLAIN'		=> 'Hier kannst du die Gruppen Verwalten.',
	'EXPLAIN_USER'	=> 'Benutzer verwalten',
	'EXPLAIN_PERM'	=> 'Hier können die Recht für die einzelnen Gruppen verwalten und bearbeiten.',
	
	'COUNT_MEMBER'	=> 'Mitgliederanzahl',

	'INPUT_DATA'	=> 'Gruppendaten',	
	
	'GROUP_NAME'	=> 'Name',
	'GROUP_DESC'	=> 'Beschreibung',
	'GROUP_TYPE'	=> 'Anfragentyp',
	'GROUP_LEGEND'	=> 'Legende',
	'GROUP_COLOR'	=> 'Farbe',
	'GROUP_RANK'	=> 'Rang',
	'GROUP_IMAGE'	=> 'Bild',
	
	'GROUP_OPEN'	=> 'Offen',
	'GROUP_REQUEST'	=> 'Anfragen',
	'GROUP_CLOSED'	=> 'Geschlossen',
	'GROUP_HIDDEN'	=> 'Versteckt',
	'GROUP_SYSTEM'	=> 'System',

	'REQUEST_AGREE'	=> 'Antrag zustimmen',
	'REQUEST_DENY'	=> 'Antrag verweigern',

	'MEMBER'			=> 'Gruppenmitglieder',
	'MEMBERS'			=> 'Mitglieder',
	'MEMBERS_NONE'		=> 'Keine Mitglieder eingetragen',
	'MODERATORS'		=> 'Moderatoren',
	'MODERATORS_NONE'	=> 'Keine Moderatoren eingetragen',
	'PENDING'			=> 'wartende Mitglieder',
	
	'MOD'			=> 'Moderatorenstatus',	
	'MAIN'			=> 'Hauptgruppe setzen',
	'MAIN_GROUP'	=> 'Hauptgruppe',
	'PERMISSION'	=> 'Gruppenberechtigung',
	
	'UPDATE_CHANGE'		=> 'Rechte wurden erfolgreich geändert.',
	'UPDATE_DEFAULT'	=> 'Hauptgruppe erfolgreich geändert.',
	'UPDATE_CREATE'		=> 'Benutzer wurden erfolgreich hinzugefügt.',
	'UPDATE_DELETE'		=> 'Benutzer wurden erfolgreich gelöscht.',
	
	'NOTICE_SELECT_DEFAULT'		=> 'zur Hauptgruppe machen',
	'NOTICE_CONFIRM_GROUP'		=> 'dass dieser Benutzer: %s von der Gruppe:',
));

$lang = array_merge($lang, array(
	'radio:type'	=> array(
		GROUP_OPEN		=> $lang['GROUP_OPEN'],
		GROUP_REQUEST	=> $lang['GROUP_REQUEST'],
		GROUP_CLOSED	=> $lang['GROUP_CLOSED'],
		GROUP_HIDDEN	=> $lang['GROUP_HIDDEN'],
		GROUP_SYSTEM	=> $lang['GROUP_SYSTEM'],
	),
	'radio:legend'	=> array(
		1 => $lang['COMMON_VIEW'],
		0 => $lang['COMMON_NONE_VIEW']
	),
));

?>