<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'TITLE'		=> 'Hauptseite Clanportal',
	'stats'			=> 'Statistiken',
	'viewonline'	=> 'Onlineübersicht',
	'acp_log'		=> 'Administratorprotokoll',
	'fastview'		=> 'Schnellansicht',
	
	'EXPLAIN'	=> 'Willkommen im Adminbereich des Clanportals von CMS-Phoenix.de usw.',
	'explain2'	=> 'Maximal 5 Benutzer die Online sind werdern hier angezeigt.',
	'explain3'	=> 'Diese Liste gibt dir einen Überblick über die letzten fünf von Administratoren durchgeführten Vorgänge',
	'explain4'	=> 'Die Übersichten über News (Nachrichten), Ereignisse (Termine), Begegnungen (Match), Training und Benutzer können direkt bearbeitet werden.',

	'page_start'	=> 'Seite installiert',
	'page_version'	=> 'CMS Version',
	'page_backup'	=> 'letztes Backup',
	'page_gzip'		=> 'GZip Komprimierung',
	'server_info'	=> 'Version PHP / MySQL',
	'page_dbinfo'	=> 'Datenbankinfo',
	
	'path_cache'		=> 'Cache',
	'path_files'		=> 'Files',
	'path_downloads'	=> 'Downloads',
	'path_gallery'		=> 'Galerie',
	'path_matchs'		=> 'Begegnungen',
	'path_users'		=> 'Benutzer',
	'path_groups'		=> 'Gruppen',
	'path_teams'		=> 'Teams',
	'path_network'		=> 'Network',
	
	'dir'	=> 'Verzeichnis',
	'size'	=> 'Größe',
	'info'	=> 'Info',
	'value'	=> 'Werte',
	
	'sprintf_dbsize'	=> 'Name: %s<br />Einträge: %s<br />Tabellen: %s',
	'sprintf_backup'	=> 'Größe: %s',
	'backup_na'			=> 'Nicht Vorhanden!',
	
	'cache_clear'		=> 'leeren',
	
	'version_check'			=> 'Version pr&uuml;fen',
	'version_info_red'		=> '<span style="color:red">%s</span> [ <a href="%s" title="%s">%s</a> ]<br /><span style="color:red">neuere Version: <b>%s</b></span>',
	'version_info_green'	=> '<span style="color:green">%s</span> [ <a href="%s" title="%s">%s</a> ]',
	
	'cache_valid' => 'Gültig bis: %s',
	
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