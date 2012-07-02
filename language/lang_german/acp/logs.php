<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'title'			=> 'Protokoll',
	'title_error'	=> 'Fehlerprotokoll',
	
	'explain'		=> 'blub',
	'explain_error'	=> 'blub2',
	
#	'create'	=> 'Neues Spiel hinzugef�gt.',
#	'update'	=> 'Spieldaten erfolgreich ge�ndert.',
	'delete'	=> 'Der/die Eintr�ge wurde gel�scht!',
#	'confirm'	=> 'dass dieses Spiel:',
	
	'common_create'		=> 'Hinzugef�gt',
	'common_error'		=> 'Fehler',
	'common_update'		=> 'Ge�ndert',
	'common_delete'		=> 'Gel�scht',
	'common_default'	=> 'Standard',
	
	'msg_confirm_delete'		=> 'Bist du sicher, dass der/die Logeintr�ge gel�scht werden soll?',
	'msg_confirm_delete_all'	=> 'Bist du sicher, dass Alle Logeintr�ge gel�scht werden soll?',
	
	'msg_must_select'			=> 'Bitte einen Log oder Logeintr�ge ausw�hlen!',
	
#	'delete_log'				=> 'Der oder die Logeintr�ge wurde gel�scht',
#	'delete_log_all'			=> 'Alle Logeintr�ge wurde gel�scht!',
#	'delete_log_error'			=> 'Der oder die Fehlermeldungen wurde gel�scht!',
	
	'section'	=> array(
		SECTION_AUTHLIST	=> 'Berechtigungsfelder',
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
		SECTION_RANK		=> 'R�nge',
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
	),
));

?>