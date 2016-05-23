<?php

if ( !defined('IN_CMS') )
{
	exit;
}


$lang = array_merge($lang, array(
	
	'title'		=> 'Benutzer',
	'explain'	=> 'Benutzer',

	'update_auth'	=> 'Benutzerberechtigungen erfolgreich geändert.',
	'update_groups'	=> 'Benutzergruppen & Teams erfolgreich geändert.',
	'update_fields'	=> 'Profilefelder erfolgreich geändert.',
	
	'option' => array(
		'update'	=> 'Übersicht',
		'settings'	=> '!Einstellungen',
		'pics'		=> '!Foto & Avatar',
		'permission'=> 'Benutzerberechtigungen',
		'groups'	=> 'Benutzergruppen & Teams',		
		'fields'	=> 'Profilfelder',
	),
	
	'teams'			=> 'Teams',
	'usergroups'	=> 'Benutzergruppen',
	
	'auth_default'		=> 'Vorgabe',
	'auth_special'		=> 'Spezial',
	'auth_allowed'		=> 'Ja',
	'auth_disallowed'	=> 'Nein',
	
	'mail'	=> 'eMail verschicken?',
	'mod'	=> 'Rechte',

	'all_default' => 'Vorgabe aktivieren',
	'all_special' => 'Spezial aktivieren',
	
	'auth'			=> 'Benutzerberechtigungen',
	'auth_explain'	=> 'Hier kann jeder Benutzer individuell eingestellt werden, "<em>Spezial</em>" heisst, er hat immer diese Rechte, egal wie die Gruppe oder Gruppen eingestellt sind von den Rechten.',
	
	'password_random'	=> 'Beispiel: %s',
	'password_input'	=> 'Passwort eintragen',
	'password_generate'	=> 'Passwort generieren',
	
	'register'			=> 'Angemeldet seit',
	'lastlogin'			=> 'Letzter Login',
	
	'email'				=> 'Mail',
	'email_confirm'		=> 'Mail bestätigen',
	'password'			=> 'Passwort',
	'password_confirm'	=> 'Passwort wiederholen',
	
	'auth_for'		=> 'Rechte für: %s',
	'auth_from'		=> 'Rechte von: %s',
	
	'active'		=> 'Aktiviert',
	'birthday'		=> 'Geburtstag',
	
	'radio:password'	=> array(0 => 'Generieren', 1 => 'Eintragen'),
	
	'auth_forum' => array(
		'auth_view'				=> 'Ansicht',
		'auth_read'				=> 'Lesen',
		'auth_post'				=> 'Posten',
		'auth_reply'			=> 'Antworten',
		'auth_edit'				=> 'Editieren',
		'auth_delete'			=> 'Löschen',
		'auth_sticky'			=> 'Wichtig',
		'auth_announce'			=> 'Ankündigung',
		'auth_globalannounce'	=> 'Globaleankündigung',
		'auth_poll'				=> 'Umfrage',
		'auth_pollcreate'		=> 'Umfrage erstellen',
	),
	
#	'tabs'	=> array(0 => 'Forum', 1 => 'Beitrag', 2 => 'Umfrage'),
	
	'tabs'	=> array(
		'a_' => array(
			0 => 'Clan',
			1 => 'Benutzer & Gruppen',
			2 => 'Download & Galerie',
			3 => 'Forum & News',
			4 => 'Rechte',
			5 => 'Seite',
			6 => 'System'
		),
		'f_' => array(
			0 => 'Forum',
			1 => 'Beitrag',
			2 => 'Umfrage',
		),
		'u_' => array(
			0 => 'Profile',
			1 => 'Diverses',
			2 => 'Private Nachrichten',
		),
		'm_' => array(
			0 => 'Beiträge',
			1 => 'Themen',
		),
		'g_' => array(
			0 => 'Galerie',
			1 => 'Bild',
		),
		'd_' => array(
			0 => 'Ordner',
			1 => 'Datei',
		),
	),
	
	'label'		=> 'Label',
	
	'a_right'	=> 'Administrator-Berechtigungen',
	'f_right'	=> 'Forum-Berechtigungen',
	'm_right'	=> 'Moderatoren-Berechtigungen',
	'u_right'	=> 'Benutzer-Berechtigungen',
	'd_right'	=> 'Download-Berechtigungen',
	'g_right'	=> 'Galerie-Berechtigungen',
	
	'a_permission'			=> 'Administrator-Berechtigung',
	'd_permission'			=> 'Download-Berechtigung',
	'g_permission'			=> 'Galerie-Berechtigung',
	
	'extended_permission'	=> 'erweitere Berechtigung',
	
	'tabs:admin' => array(
			0 => 'Clan',
			1 => 'Benutzer & Gruppen',
			2 => 'Download & Galerie',
			3 => 'Forum & News',
			4 => 'Rechte',
			5 => 'Seite',
			6 => 'System'
	),
	
	'tabs:forum' => array(
			0 => 'Forum',
			1 => 'Beitrag',
			2 => 'Umfrage',
	),
	
	'tabs:gallery' => array(
			0 => 'Galerie',
			1 => 'Bild',
	),
	
	
	
));

$lang['Membership_pending'] = '<span title="Ja für Annehmen, Nein für Ablehnen"><u>Freigabe</u></span>';

/*
$lang['user_pass_random']	= 'Beispiel: %s';

//
//	Benutzer
//
$lang['user']					= 'Benutzer';
$lang['user_explain']			= 'Hier kannst du die Benutzer verwalten und organisieren.';

$lang['user_default']			= '&Uuml;bersicht';
$lang['user_regedit']			= 'Erforderliche Daten';
$lang['user_regedit_explain']	= 'alle wichtigen Einstellungen die die Seite betreffen.';
$lang['user_groups']			= 'Uploadverzeichnisse';
$lang['user_groups_explain']	= 'Grüne oder Rote Symbole kennzeichnen, ob ein Verzeichnis Vorhanden ist oder ob Schreibrecht vorliegen.';

$lang['user_groups']			= 'Benutzergruppen & Teams';
$lang['user_auth']				= 'Benutzerberechtigungen';
$lang['user_auth_explain']		= 'Hier kann jeder Benutzer individuell eingestellt werden, "<em>Spezial</em>" heisst, er hat immer diese Rechte, egal wie die Gruppe oder Gruppen eingestellt sind von den Rechten.';

$lang['user_usergroups']		= 'Benutzergruppen';
$lang['user_teams']				= 'Teams';


$lang['user_register']			= 'Angemeldet seit';
$lang['user_lastlogin']			= 'Letzter Login';
$lang['user_founder']			= 'Gründer';
$lang['user_email']				= 'E-Mail-Adresse';
$lang['user_email_confirm']		= 'E-Mail-Adresse wiederholen';
$lang['user_password']			= 'Passwort';
$lang['user_password_confirm']	= 'Passwort wiederholen';


$lang['user_change_auths']		= 'Seitenberechtigung';
$lang['user_change_groups']		= 'Benutzergruppen und Teams aktualisiert.';
$lang['user_mailnotification']	= 'eMail Benachrichtigung verschicken?';

$lang['user_register']				= 'Erforderliche Daten';
$lang['user_fields']				= 'Profilfelder';
$lang['user_settings']				= 'Einstellungen';
$lang['user_images']				= 'Bilder';

$lang['user_groupmod']			= 'Moderatorstatus';
$lang['user_look_up']			= 'Benutzer auswählen';

$lang['password_register']		= 'Passwort eintragen';
$lang['password_generate']		= 'Passwort generieren';

$lang['user_option'] = array(
#	'update'		=> $lang['user_default'],
	'update'		=> $lang['user_regedit'],
	'user_groups'	=> $lang['user_groups'],
	'user_auth'		=> $lang['user_auth'],
);

$lang['group_default']		= 'Vorgabe';
$lang['group_special']		= 'Spezial';
$lang['group_allowed']		= 'Ja';
$lang['group_disallowed']	= 'Nein';

$lang['all_default']		= 'Vorgabe aktivieren';
$lang['all_special']		= 'Spezial aktivieren';
*/
?>