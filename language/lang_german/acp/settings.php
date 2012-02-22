<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Einstellungen der Seite',
	'explain'	=> 'Hier werden alle wichtigen Einstellungen f�r die Seite gemacht.',
	
	'update'	=> 'Seiteneinstellung erfolgreich ge�ndert.',
	
	'site_default'			=> 'Seiteneinstellungen',
	'site_default_explain'	=> 'alle wichtigen Einstellungen die die Seite betreffen.',
	'site_upload'			=> 'Uploadverzeichnisse',
	'site_upload_explain'	=> 'Gr�ne oder Rote Symbole kennzeichnen, ob ein Verzeichnis Vorhanden ist oder ob Schreibrecht vorliegen.',
	'site_session'			=> 'Sitzungseinstellungen',
	'site_session_explain'	=> 'alle wichtigen Einstellungen die die Seite betreffen.',
	'site_display'			=> 'Anzeigeneinstellungen',
	'site_display_explain'	=> 'Hier werden alle Einstellung f�r die Hauptseite eingestellt.',
	
	'server_name'	=> 'Domainname',
	'server_name_explain'	=> 'Der Name der Domain, auf dem die Seite l�uft',
	'server_port'	=> 'Server Port',
	'server_port_explain'	=> 'Der Port, unter dem dein Server l�uft, normalerweise 80. �ndere dies nur, wenn es ein anderer Port ist',
	'page_path'	=> 'Scriptpfad',
	'page_path_explain'	=> 'Der Pfad zum CMS, relativ zum Domainnamen',
	'page_name'	=> 'Name der Seite',
	'page_name_explain'	=> 'Wird auf jeder Seite angezeigt.',
	'page_desc'	=> 'Beschreibung der Seite',
	'disable_page'	=> 'Seite deaktivieren',
	'disable_page_explain'	=> 'Hiermit sperrst du die Seite f�r alle Benutzer. Administratoren k�nnen auf den Administrations-Bereich zugreifen, wenn die Seite gesperrt ist.',
	'disable_page_reason'	=> 'Deaktivierungsgrund',
	'disable_page_mode'	=> 'f�r Benutzerlevel',
	
	'path_games'	=> 'Spiele',
	'path_games_explain'	=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Spieleicons liegen (z. B. images/games)',
	'path_ranks'	=> 'R�nge',
	'path_ranks_explain'	=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Rangbilder liegen (z. B. images/ranks)',
	'path_newscat'	=> 'Newskategorie',
	'path_cat_explain'	=> 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)',
	

));

$lang = array_merge($lang, array(

	'settings_option'	=> array(
		'_default'	=> $lang['site_default'],
		'_upload'	=> $lang['site_upload'],
		'_session'	=> $lang['site_session'],
		'_display'	=> $lang['site_display'],
	),

));

#$lang['set_head']				= 'Einstellungen der Seite';
#$lang['set_explain']			= 'Hier werden alle wichtigen Einstellungen f�r die Seite gemacht.';

$lang['set_ftp']				= 'FTP Einstellungen';
$lang['set_ftp_explain']		= 'Hier kannst du Notfalls FTP Rechte ver�ndert werdem!';

$lang['path_gallery']			= 'Galerie';
$lang['path_gallery_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Spieleicons liegen (z. B. images/games)';
$lang['path_matchs']			= 'Begegnungen';
$lang['path_matchs_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)';



$lang['path_groups']			= 'Gruppen';
$lang['path_groups_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Rangbilder liegen (z. B. images/ranks)';
$lang['path_teams']				= 'Teams';
$lang['path_teams_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)';
$lang['path_users']				= 'Benutzer';
$lang['path_users_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)';



$lang['settings_team_logo']			= 'Team Logo Upload';
$lang['settings_team_logo_explain']	= 'Hier k�nnen spezielle Parameter f�r den Upload von Teamlogos bestimmt werden.';


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


?>