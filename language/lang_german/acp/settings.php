<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	'title'		=> 'Einstellungen der Seite',
	'explain'	=> 'Hier werden alle wichtigen Einstellungen f�r die Seite gemacht.',
	
	'update'	=> 'Seiteneinstellung erfolgreich ge�ndert.',
	
	/* dropdown */
	'site_default'			=> 'Seiteneinstellungen',
	'site_default_explain'	=> 'alle wichtigen Einstellungen die die Seite betreffen.',
	'site_calendar'			=> 'Kalender',
	'site_calendar_explain'	=> 'Kalender blubs',
	'site_module'			=> 'Moduleeinstellungen',
	'site_module_explain'	=> 'Hier werden alle Einstellung f�r die Hauptseite eingestellt.',
	'site_subnavi'			=> 'Moduleanordnung',
	'site_subnavi_explain'	=> 'Hier werden die Module angeordnet.',
	'site_upload'			=> 'Bild/Uploadverzeichnisse',
	'site_upload_explain'	=> 'Gr�ne oder Rote Symbole kennzeichnen, ob ein Verzeichnis Vorhanden ist oder ob Schreibrecht vorliegen.',
	'site_other'			=> 'andere Einstellungen',
	'site_other_explain'	=> 'andere Einstellungen blub :D',
	'site_match'			=> 'Begegnungs Einstellung',
	'site_match_explain'	=> 'Typeinstellungen',
	'site_gallery'			=> 'Galerie Voreinstellungen',
	'site_gallery_explain'	=> 'Standarteinstellungen f�r das erstellen neuer Galerien, es kann aber jede Galerie eingenst�ndig verwaltet werden von den Rechten. Die Rechte sind nur mit den vorhergesehen Gruppen verbunden, Leader Rechte beziehen sich nur auf die Benutzer die in der Gruppe Leader sind. F�r weitere Infos einfach auf der Entwicklerhauptseite informieren.',
	'site_ftp'				=> 'FTP Rechte',
	'site_ftp_explain'		=> 'Hier kannst Du Notfalls FTP Rechte eingestellt werden, FTP-Benutzername und FTP-Passwort sind notwendig!',
	'site_phpinfo'			=> 'Support Infos',
	'site_phpinfo_explain'	=> 'Infos �ber PHP und SQL.',
	
	/* site settings */
	'server_name'			=> 'Domainname',
	'server_name_explain'	=> 'Der Name der Domain, auf dem die Seite l�uft',
	'server_port'			=> 'Server Port',
	'server_port_explain'	=> 'Der Port, unter dem dein Server l�uft, normalerweise 80. �ndere dies nur, wenn es ein anderer Port ist',
	'page_path'				=> 'Scriptpfad',
	'page_path_explain'		=> 'Der Pfad zum CMS, relativ zum Domainnamen',
	'page_name'				=> 'Name der Seite',
	'page_name_explain'		=> 'Wird auf jeder Seite angezeigt.',
	'page_desc'				=> 'Beschreibung der Seite',
	'disable_page'			=> 'Seite deaktivieren',
	'disable_page_explain'	=> 'Hiermit sperrst du die Seite f�r alle Benutzer. Administratoren k�nnen auf den Administrations-Bereich zugreifen, wenn die Seite gesperrt ist.',
	'disable_page_reason'	=> 'Deaktivierungsgrund',
	'disable_page_mode'		=> 'f�r Benutzerlevel',
	
	/* site upload */
	'path'				=> 'Verzeichnispfad',
	'path_cache'		=> 'Cache',
	'path_files'		=> 'Files',
	'path_games'		=> 'Spiele',
	'path_maps'			=> 'Karten',
	'path_newscat'		=> 'Newskategorie',
	'path_ranks'		=> 'R�nge',
	'path_downloads'	=> 'Downloads',
	'path_gallery'		=> 'Galerie',
	'path_groups'		=> 'Gruppen',
	'path_matchs'		=> 'Begegnungen',
	'path_network'		=> 'Network',
	'path_team_banner'	=> 'Team Banner',
	'path_team_logo'	=> 'Team Logo',
	'path_users'		=> 'Benutzer',
	
	'explain_cache'			=> 'Verzeichnispfad f�r das speichern von Cache-Dateien. (z. B. cache/)',
	'explain_files'			=> 'Ordner in dem die DB-Files gespeichert werden. (z. B. files/)',
	'explain_games'			=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Spieleicons liegen (z. B. images/games)',
	'explain_maps'			=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Spieleicons liegen (z. B. images/games)',
	'explain_newscat'		=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)',
	'explain_ranks'			=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Rangbilder liegen (z. B. images/ranks)',
	'explain_downloads'		=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Rangbilder liegen (z. B. images/ranks)',
	'explain_gallery'		=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Spieleicons liegen (z. B. images/games)',
	'explain_groups'		=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Rangbilder liegen (z. B. images/ranks)',
	'explain_matchs'		=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)',
	'explain_network'		=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)',
	'explain_team_banner'	=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)',
	'explain_team_logo'		=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)',
	'explain_users'			=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)',
	
	'upload_team_banner'	=> 'Team Banner Upload',
	'upload_team_logo'		=> 'Team Logo Upload',
	
	'explain_upload_team_banner'	=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)',
	'explain_upload_team_logo'		=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)',
	
	'filesize'				=> '<span title="in MegaByte">Dateigr��e</span>',
	'dimension'				=> '<span title="Breite x H�he">Bildgr��e</span>',
	'preview'				=> '<span title="Breite x H�he">Vorschaugr��e</span>',

	'explain_filesize_groups'		=> 'Bildgr��e',
	'explain_filesize_matchs'		=> 'Bildgr��e',
	'explain_filesize_network'		=> 'Bildgr��e',
	'explain_filesize_team_banner'	=> 'Bildgr��e',
	'explain_filesize_team_logo'	=> 'Bildgr��e',
	'explain_filesize_users'		=> 'Bildgr��e',
	
	'dimension_groups'		=> 'Max Breite / H�he',
	'dimension_matchs'		=> 'Max Breite / H�he',
	'dimension_network'		=> 'Max Breite / H�he',
	'dimension_team_logo'	=> 'Max Breite / H�he',
	'dimension_team_banner'	=> 'Max Breite / H�he',
	'dimension_users'		=> 'Max Breite / H�he',
	
	'explain_dimension_groups'		=> 'Gruppenbildgr��e',
	'explain_dimension_matchs'		=> 'Gruppenbildgr��e',
	'explain_dimension_network'		=> 'Gruppenbildgr��e',
	'explain_dimension_team_logo'	=> 'Gruppenbildgr��e',
	'explain_dimension_team_banner'	=> 'Gruppenbildgr��e',
	'explain_dimension_users'		=> 'Gruppenbildgr��e',
	
	'preview_matchs'			=> 'Vorschau Breite / H�he',
	'explain_preview_matchs'	=> 'Vorschaugr��e',
	
	'subnavi_news'			=> 'Letzte Nachrichten anzeigen',
	'subnavi_match'			=> 'Letzte Begegnungen anzeigen',
	'subnavi_topics'		=> 'Letzte Forumthemen anzeigen',
	'subnavi_downloads'		=> 'Letzte Downloads anzeigen',
	'subnavi_newusers'		=> 'neuesten Mitglieder anzeigen',
	'subnavi_teams'			=> 'Teams anzeigen',
	'subnavi_links'			=> 'Links anzeigen',
	'subnavi_partner'		=> 'Partner anzeigen',
	'subnavi_sponsor'		=> 'Sponsor anzeigen',
	'subnavi_statscounter'	=> 'Statscounter anzeigen',
	'subnavi_statsonline'	=> 'Statsonline anzeigen',
	'subnavi_minical'		=> 'Kalender anzeigen',
	'subnavi_server'		=> 'Server Viewer anzeigen',
	'subnavi_next_match'	=> 'next War anzeigen',
	'subnavi_next_training'	=> 'next Train anzeigen',
	
	'explain_news'			=> 'Letzte Nachrichten anzeigen',
	'explain_match'			=> 'Letzte Begegnungen anzeigen',
	'explain_topics'		=> 'Letzte Forumthemen anzeigen',
	'explain_downloads'		=> 'Letzte Downloads anzeigen',
	'explain_newusers'		=> 'neuesten Mitglieder anzeigen',
	'explain_teams'			=> 'Teams anzeigen',
	'explain_links'			=> 'Links anzeigen',
	'explain_partner'		=> 'Partner anzeigen',
	'explain_sponsor'		=> 'Sponsor anzeigen',
	'explain_statscounter'	=> 'Statscounter anzeigen',
	'explain_statsonline'	=> 'Statsonline anzeigen',
	'explain_minical'		=> 'Kalender anzeigen',
	'explain_server'		=> 'Server Viewer anzeigen',
	'explain_next_match'	=> 'next War anzeigen',
	'explain_next_training'	=> 'next Train anzeigen',
	
	'show_cache'			=> 'Cache anzeigen',
	'explain_show_cache'	=> 'Zeigt die Dauer bis eine neue Cache datei erstellt wird.',
	
	'limit_news'		=> 'Anzahl der Nachrichten',
	'limit_match'		=> 'Anzahl der Begegnungen',
	'limit_topics'		=> 'Anzahl der Forenthemen',
	'limit_downloads'	=> 'Anzahl der Downloads',
	'limit_newusers'	=> 'Anzahl der Benutzer',
	'limit_teams'		=> 'Anzahl der Teams',
	
	'explain_limit_news'		=> 'Die Zahl angeben, wieviele News untereinander angezeigt werden sollen.',
	'explain_limit_match'		=> 'Die Zahl angeben, wieviele Begegnungen untereinander angezeigt werden sollen.',
	'explain_limit_topics'		=> 'Die Zahl angeben, wieviele News untereinander angezeigt werden sollen.',
	'explain_limit_downloads'	=> 'Die Zahl angeben, wieviele Begegnungen untereinander angezeigt werden sollen.',
	'explain_limit_newusers'	=> 'Die Zahl angeben, wieviele News untereinander angezeigt werden sollen.',
	'explain_limit_teams'		=> 'Die Zahl angeben, wieviele Begegnungen untereinander angezeigt werden sollen.',
	
	'length_news'		=> 'Anzahl der Zeichen vom Nachrichtentitel',
	'length_match'		=> 'Anzahl der Zeichen vom Name der Gegner',
	'length_topics'		=> 'L�nge der Forentitel',
	'length_downloads'	=> 'L�nge der Downloadnamen',
	'length_newusers'	=> 'L�nge der Forentitel',
	'length_teams'		=> 'L�nge der Downloadnamen',
	
	'explain_length_news'		=> 'Anzahl der Zeichen vom Nachrichtentitel',
	'explain_length_match'		=> 'Anzahl der Zeichen vom Name der Gegner',
	'explain_length_topics'		=> 'L�nge der Forentitel',
	'explain_length_downloads'	=> 'L�nge der Downloadnamen',
	'explain_length_newusers'	=> 'L�nge der Forentitel',
	'explain_length_teams'		=> 'L�nge der Downloadnamen',
	
	'cache_news'		=> 'L�nge der Downloadnamen',
	'cache_newusers'	=> 'L�nge der Downloadnamen',
	
	'explain_cache_news'		=> 'L�nge der Downloadnamen',
	'explain_cache_newusers'	=> 'L�nge der Downloadnamen',
	
	'writable_yes'	=> 'beschreibbar',
	'writable_no'	=> 'nicht beschreibbar',	

	/*	PHP/MySQL Support Infos	*/
	'support_common'	=> 'Allgemein',
	'support_version'	=> 'Versionen',
	'support_server'	=> 'Servereinstellungen',
	'version'			=> 'CMS Version:',
	'domain'			=> 'Domain:',
	'browser'			=> 'System/Browser:',
	'server_os'			=> 'Server OS:',
	'server_apache'		=> 'Apache Version:',
	'server_php'		=> 'PHP-Version:',
	'server_sql'		=> 'MySQL-Version:',
	
	'info_option_a'		=> 'fopen():',
	'info_option_b'		=> 'fsockopen():',
	'info_option_c'		=> 'register_globals:',
	'info_option_d'		=> 'safe_mode:',
	'info_option_e'		=> 'GD Support:',
	'info_option_f'		=> 'GD Version:',
	'info_option_g'		=> 'magic_quotes_gpc:',
	'info_option_h'		=> 'file_uploads:',
	'info_option_i'		=> 'upload_max_filesize:',
	'info_option_j'		=> 'HTTP_ACCEPT_ENCODING:',
	
	'save_as'			=> 'Speichern als Textdatei',
	
	'calendar'			=> 'Kalender',
	'subnavi_calendar'	=> 'Minikalender',
	
	'show'		=> 'Anzeigen',
	'time'		=> 'Cachedauer',
	'cache'		=> 'Cache Anzeigen',
	'start'		=> 'Wochenstart',
	'bday'		=> 'Geburtstag',
	'news'		=> 'News',
	'event'		=> 'Ereignis',
	'match'		=> 'Begegnung',
	'train'		=> 'Training',
	'viewer'	=> 'Ansicht',
	
#	'news_view'		=> array(0 => 'als Listenansicht', 1 => 'als Blockansicht'),
#	'cal_view'		=> array(0 => 'als Listenansicht', 1 => 'als Blockansicht'),
#	'cal_days'		=> array(0 => 'Sonntag', 1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag', 5 => 'Freitag', 6 => 'Samstag'),
#	'opt_gallery'	=> array(0 => 'als Listenansicht', 1 => 'nach Vorgabe der Einstellungen'),
#	'radio_match'	=> array(0 => 'als Ausw�hlpunkte', 1 => 'als Dropdownmen�'),

	'maintenance'				=> 'Wartungsarbeiten',
	'page_counter'				=> 'Besucherz�hler',
	'page_counter_explain'		=> 'Besucherz�hler blub',
	'page_counter_start'		=> 'Besucherz�hler Start',
	'page_counter_start_explain'=> 'Besucherz�hler Start blub',
	'page_gzip'					=> 'GZip Komprimierung',
	'page_gzip_explain'			=> 'GZip Komprimierung aus lassen ;)',
	'clan'						=> 'Clandaten',
	'clan_name'					=> 'Clanname',
	'clan_name_explain'			=> 'Clanname blub',
	'clan_tag'					=> 'Clantag',
	'clan_tag_explain'			=> 'Clantag blub',
	'clan_tag_show'				=> 'Clantag Anzeigen',
	'clan_tag_show_explain'		=> 'Clantag Anzeigen blub',
	'standards'					=> 'Standardeinstellungen',
	'dateformat'				=> 'Datumsformat',
	'dateformat_explain'		=> 'Die Syntax entspricht der date()-Funktion von PHP.',
	'timezone'					=> 'Zeitzone f�r G�ste',
	'timezone_explain'			=> 'Zeitzone, die f�r Benutzer verwendet wird, die nicht angemeldet sind (G�ste, Bots). Angemeldete Benutzer legen ihre Zeitzone w�hrend der Registrierung fest und k�nnen sie im pers�nlichen Bereich �ndern.',
	'lang'						=> 'Standard-Sprache',
	'lang_explain'				=> 'Standard-Sprache blub',
	'style'						=> 'Standard-Style',
	'style_explain'				=> 'Standard-Style blub',
	'style_override'			=> 'Benutzer-Style �berschreiben',
	'style_override_explain'	=> 'Verwendet den Standard-Style statt der individuell von den Benutzern gew�hlten Styles.',
	
	/* per_page_entry */
	'per_page_entry'	=> 'Eintr�ge pro Seite',
	'acp'				=> 'Adminbereich',
	'mcp'				=> 'Moderatorenbereich',
	'ucp'				=> 'Benutzerbereich',
	'site'				=> 'Allgemein',
	'index'				=> 'Adminindex',
	'comments'			=> 'Kommentare',
	
	'auth_gallery_guest'	=> 'Gast',
	'auth_gallery_user'		=> 'Benutzer',
	'auth_gallery_trial'	=> 'Trialmember',
	'auth_gallery_member'	=> 'Member',
	'auth_gallery_coleader'	=> 'Squadleader',
	'auth_gallery_leader'	=> 'Leader',
	'auth_gallery_uploader'	=> 'Uploader',
	
));

$lang = array_merge($lang, array(
	'settings_option'	=> array(
		'default'	=> $lang['site_default'],
		'calendar'	=> $lang['site_calendar'],
		'module'	=> $lang['site_module'],
		'subnavi'	=> $lang['site_subnavi'],
		'upload'	=> $lang['site_upload'],
		'other'		=> $lang['site_other'],
		'match'		=> $lang['site_match'],
		'gallery'	=> $lang['site_gallery'],
		'ftp'		=> $lang['site_ftp'],
		'phpinfo'	=> $lang['site_phpinfo'],
	),
));

?>