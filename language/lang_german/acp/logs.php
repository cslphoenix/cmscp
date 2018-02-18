<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'TITLE'		=> 'Protokoll',
	'TITLE_E'	=> 'Fehlerprotokoll',
	
	'EXPLAIN'	=> 'Alle Protokolle über Aktivitäten.',
	'EXPLAIN_E'	=> 'Alle Fehlerprotokolle über Aktivitäten.',
	
#	'create'	=> 'Neues Spiel hinzugefügt.',
#	'update'	=> 'Spieldaten erfolgreich geändert.',
#	'delete'	=> 'Der/die Einträge wurde gelöscht!',
#	'confirm'	=> 'dass dieses Spiel:',

	'LOG_IP'		=> 'IP-Adresse',
	'LOG_TIME'		=> 'Datum und Zeit',
	'LOG_SECTION'	=> 'Bereich und Aktion',
	
#	'common_create'		=> 'hinzugefügt',
#	'common_error'		=> 'fehler',
#	'COMMON_UPDATE'		=> 'geändert',
#	'COMMON_DELETE'		=> 'gelöscht',
#	'common_default'	=> 'Standard',
	
#	'NOTICE_CONFIRM_DELETE'		=> 'Bist du sicher, dass der/die Logeinträge gelöscht werden soll?',
#	'msg_confirm_delete_all'	=> 'Bist du sicher, dass Alle Logeinträge gelöscht werden soll?',
	
#	'msg_must_select'			=> 'Bitte einen Log oder Logeinträge auswählen!',
	
#	'delete_log'				=> 'Der oder die Logeinträge wurde gelöscht',
#	'delete_log_all'			=> 'Alle Logeinträge wurde gelöscht!',
#	'delete_log_error'			=> 'Der oder die Fehlermeldungen wurde gelöscht!',
	
	'section'	=> array(
		SECTION_COMMENT		=> 'Kommentare',
		SECTION_EVENT		=> 'Ergeinisse',
		SECTION_FORUM		=> 'Forum',
		SECTION_GAMES		=> 'Spiele',
		SECTION_GROUPS		=> 'Gruppen',
		SECTION_LOG			=> 'Logs',
		SECTION_LOGIN		=> 'Login',
		SECTION_MATCH		=> 'Begegnungen',
		SECTION_NAVI		=> 'Navigation',
		SECTION_NETWORK		=> 'Network',
		SECTION_NEWS		=> 'News',
		SECTION_NEWS_CAT	=> 'Newskategorie',
		SECTION_NEWSLETTER	=> 'Newsletter',
		SECTION_PROFILE		=> 'Profilefelder',
		SECTION_RANK		=> 'Ränge',
		SECTION_TEAM		=> 'Teams',
		SECTION_TEAMSPEAK	=> 'Teamspeak',
		SECTION_TRAINING	=> 'Training',
		SECTION_USER		=> 'Benutzer',
		SECTION_CASH		=> 'Clankasse',
		SECTION_GALLERY		=> 'Galerie',
		SECTION_MAPS		=> 'Karten',
		SECTION_SERVER		=> 'Server',
		SECTION_DOWNLOADS	=> 'Downloads',
		SECTION_CALENDAR	=> 'Kalender',
		SECTION_VOTES		=> 'Umfragen',
		SECTION_SERVER_TYPE	=> 'Servertypen',
		SECTION_SETTINGS	=> 'Einstellungen',
		SECTION_MENU		=> 'Menü',
	),
));

?>