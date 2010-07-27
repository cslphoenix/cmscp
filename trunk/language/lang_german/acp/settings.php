<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 *	Teams
 *
 */

if ( !defined('IN_CMS') )
{
	exit;
}

$lang['set_head']				= 'Einstellungen der Seite';
$lang['set_explain']			= 'Hier werden alle wichtigen Einstellungen für die Seite gemacht.';

$lang['site_default']			= 'Seiteneinstellungen';
$lang['site_default_explain']	= 'alle wichtigen Einstellungen die die Seite betreffen.';
$lang['site_upload']			= 'Uploadverzeichnisse';
$lang['site_upload_explain']	= 'Grüne oder Rote Symbole kennzeichnen, ob ein Verzeichnis Vorhanden ist oder ob Schreibrecht vorliegen.';
$lang['site_session']			= 'Sitzungseinstellungen';
$lang['site_session_explain']	= 'alle wichtigen Einstellungen die die Seite betreffen.';

$lang['set_ftp']				= 'FTP Einstellungen';
$lang['set_ftp_explain']		= 'Hier kannst du Notfalls FTP Rechte verändert werdem!';


$lang['server_name']			= 'Domainname';
$lang['server_name_explain']	= 'Der Name der Domain, auf dem die Seite läuft';
$lang['server_port']			= 'Server Port';
$lang['server_port_explain']	= 'Der Port, unter dem dein Server läuft, normalerweise 80. Ändere dies nur, wenn es ein anderer Port ist';
$lang['page_path']				= 'Scriptpfad';
$lang['page_path_explain']		= 'Der Pfad zum CMS, relativ zum Domainnamen';
$lang['page_name']				= 'Name der Seite';
$lang['page_name_explain']		= 'Wird auf jeder Seite angezeigt.';
$lang['page_desc']				= 'Beschreibung der Seite';
$lang['disable_page']			= 'Seite deaktivieren';
$lang['disable_page_explain']	= 'Hiermit sperrst du die Seite für alle Benutzer. Administratoren können auf den Administrations-Bereich zugreifen, wenn die Seite gesperrt ist.';
$lang['disable_page_reason']	= 'Deaktivierungsgrund';
$lang['disable_page_mode']		= 'für Benutzerlevel';

$lang['settings_option'] = array(
	'_default'	=> $lang['site_default'],
	'_upload'	=> $lang['site_upload'],
	'_session'	=> $lang['site_session'],
);

$lang['path_games']				= 'Spiele';
$lang['path_games_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Spieleicons liegen (z. B. images/games)';
$lang['path_ranks']				= 'Ränge';
$lang['path_ranks_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Rangbilder liegen (z. B. images/ranks)';
$lang['path_newscat']			= 'Newskategorie';
$lang['path_newscat_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Newskategoriebileder liegen (z. B. images/newscat)';

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
$lang['settings_team_logo_explain']	= 'Hier können spezielle Parameter für den Upload von Teamlogos bestimmt werden.';


?>