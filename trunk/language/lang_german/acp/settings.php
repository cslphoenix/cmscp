<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang['set_head']				= 'Einstellungen der Seite';
$lang['set_explain']			= 'Hier werden alle wichtigen Einstellungen f�r die Seite gemacht.';

$lang['site_default']			= 'Seiteneinstellungen';
$lang['site_default_explain']	= 'alle wichtigen Einstellungen die die Seite betreffen.';
$lang['site_upload']			= 'Uploadverzeichnisse';
$lang['site_upload_explain']	= 'Gr�ne oder Rote Symbole kennzeichnen, ob ein Verzeichnis Vorhanden ist oder ob Schreibrecht vorliegen.';
$lang['site_session']			= 'Sitzungseinstellungen';
$lang['site_session_explain']	= 'alle wichtigen Einstellungen die die Seite betreffen.';

$lang['set_ftp']				= 'FTP Einstellungen';
$lang['set_ftp_explain']		= 'Hier kannst du Notfalls FTP Rechte ver�ndert werdem!';


$lang['server_name']			= 'Domainname';
$lang['server_name_explain']	= 'Der Name der Domain, auf dem die Seite l�uft';
$lang['server_port']			= 'Server Port';
$lang['server_port_explain']	= 'Der Port, unter dem dein Server l�uft, normalerweise 80. �ndere dies nur, wenn es ein anderer Port ist';
$lang['page_path']				= 'Scriptpfad';
$lang['page_path_explain']		= 'Der Pfad zum CMS, relativ zum Domainnamen';
$lang['page_name']				= 'Name der Seite';
$lang['page_name_explain']		= 'Wird auf jeder Seite angezeigt.';
$lang['page_desc']				= 'Beschreibung der Seite';
$lang['disable_page']			= 'Seite deaktivieren';
$lang['disable_page_explain']	= 'Hiermit sperrst du die Seite f�r alle Benutzer. Administratoren k�nnen auf den Administrations-Bereich zugreifen, wenn die Seite gesperrt ist.';
$lang['disable_page_reason']	= 'Deaktivierungsgrund';
$lang['disable_page_mode']		= 'f�r Benutzerlevel';



$lang['path_games']				= 'Spiele';
$lang['path_games_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Spieleicons liegen (z. B. images/games)';
$lang['path_ranks']				= 'R�nge';
$lang['path_ranks_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Rangbilder liegen (z. B. images/ranks)';
$lang['path_newscat']			= 'Newskategorie';
$lang['path_cat_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)';

$lang['path_gallery']			= 'Galerie';
$lang['path_gallery_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Spieleicons liegen (z. B. images/games)';
$lang['path_groups']			= 'Gruppen';
$lang['path_groups_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Rangbilder liegen (z. B. images/ranks)';
$lang['path_matchs']			= 'Begegnungen';
$lang['path_matchs_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)';
$lang['path_teams']				= 'Teams';
$lang['path_teams_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)';
$lang['path_users']				= 'Benutzer';
$lang['path_users_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)';

$lang['settings_team_logo']			= 'Team Logo Upload';
$lang['settings_team_logo_explain']	= 'Hier k�nnen spezielle Parameter f�r den Upload von Teamlogos bestimmt werden.';

$lang['site_display']			= 'Anzeigeneinstellungen';
$lang['site_display_explain']	= 'Hier werden alle Einstellung f�r die Hauptseite eingestellt.';

#news_limit
#news_length
#match_limit
#match_length

$lang['news_limit']			= 'Anzahl der News';
$lang['news_length']		= 'L�nge der Newstitel';
$lang['match_limit']		= 'Anzahl der Matchs';
$lang['match_length']		= 'L�nge der Matchnamen';
$lang['forum_limit']		= 'Anzahl der Forenbeitr�ge';
$lang['forum_length']		= 'L�nge der Forentitel';
$lang['download_limit']		= 'Anzahl der Downloads';
$lang['download_length']	= 'L�nge der Downloadnamen';
$lang['newusers']			= 'Neuste Benutzer Anzeigen';
$lang['newusers_cache']		= 'Cache l�nge';
$lang['newusers_limit']		= 'Anzahl der Benutzer';
$lang['newusers_length']	= 'Benutzernamen l�nge';
$lang['teams']				= 'Teams anzeigen';
$lang['teams_limit']		= 'Anzahl der Teams';
$lang['links']				= 'Links anzeigen';
$lang['partner']			= 'Partner anzeigen';
$lang['sponsor']			= 'Sponsoren anzeigen';
$lang['minical']			= 'Minikalender anzeigen';
$lang['match_next']			= 'Next Match anzeigen';
$lang['training']			= 'Training anzeigen';

$lang['settings_option'] = array(
	'_default'	=> $lang['site_default'],
	'_upload'	=> $lang['site_upload'],
	'_session'	=> $lang['site_session'],
	'_display'	=> $lang['site_display'],
);
?>