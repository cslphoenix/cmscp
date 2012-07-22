<?php

if ( !defined('IN_CMS') )
{
	exit;
}


$lang = array_merge($lang, array(
	
	'game'		=> 'Spiel',
	'explain'	=> 'Hier kannst du die Benutzer verwalten und organisieren.',

	'create'	=> 'Neuen Benutzer hinzugef�gt.',
	'update'	=> 'Benutzerdaten erfolgreich ge�ndert.',
	'delete'	=> 'Der Benutzer wurde gel�scht!',
	'confirm'	=> 'dass dieser Benutzer:',
	
	'update_auth'	=> 'Benutzerberechtigungen erfolgreich ge�ndert.',
	'update_groups'	=> 'Benutzergruppen & Teams erfolgreich ge�ndert.',
	'update_fields'	=> 'Profilefelder erfolgreich ge�ndert.',
	
	'option' => array(
		'update'	=> 'Erforderliche Daten',
		'settings'	=> '!Einstellungen',
		'pics'		=> '!Foto & Avatar',
		'auth'		=> 'Benutzerberechtigungen',
		'groups'	=> 'Benutzergruppen & Teams',		
		'fields'	=> 'Profilfelder',
		'overview'	=> '!�bersicht',
	),
	
	'teams'			=> 'Teams',
	'usergroups'	=> 'Benutzergruppen',
	
	'auth_default'		=> 'Vorgabe',
	'auth_special'		=> 'Spezial',
	'auth_allowed'		=> 'Ja',
	'auth_disallowed'	=> 'Nein',
	
	'mail'	=> 'eMail verschicken?',
	'mod'	=> 'Moderatorstatus',

	'all_default' => 'Vorgabe aktivieren',
	'all_special' => 'Spezial aktivieren',
	
	'auth'			=> 'Benutzerberechtigungen',
	'auth_explain'	=> 'Hier kann jeder Benutzer individuell eingestellt werden, "<em>Spezial</em>" heisst, er hat immer diese Rechte, egal wie die Gruppe oder Gruppen eingestellt sind von den Rechten.',
	
	'password_random'	=> 'Beispiel: %s',
	'password_input'	=> 'Passwort eintragen',
	'password_generate'	=> 'Passwort generieren',
	
	'register'			=> 'Angemeldet seit',
	'lastlogin'			=> 'Letzter Login',
	'founder'			=> 'Gr�nder',
	'email'				=> 'Mail',
	'email_confirm'		=> 'Mail best�tigen',
	'password'			=> 'Passwort',
	'password_confirm'	=> 'Passwort wiederholen',
	
	'auth_for'		=> 'Rechte f�r: %s',
	'auth_from'		=> 'Rechte von: %s',
	
	'active'		=> 'Aktiviert',
	'birthday'		=> 'Geburtstag',
	
));

$lang['Membership_pending'] = '<span title="Ja f�r Annehmen, Nein f�r Ablehnen">warten auf Freigabe<sup>I</sup></span>';

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
$lang['user_groups_explain']	= 'Gr�ne oder Rote Symbole kennzeichnen, ob ein Verzeichnis Vorhanden ist oder ob Schreibrecht vorliegen.';

$lang['user_groups']			= 'Benutzergruppen & Teams';
$lang['user_auth']				= 'Benutzerberechtigungen';
$lang['user_auth_explain']		= 'Hier kann jeder Benutzer individuell eingestellt werden, "<em>Spezial</em>" heisst, er hat immer diese Rechte, egal wie die Gruppe oder Gruppen eingestellt sind von den Rechten.';

$lang['user_usergroups']		= 'Benutzergruppen';
$lang['user_teams']				= 'Teams';


$lang['user_register']			= 'Angemeldet seit';
$lang['user_lastlogin']			= 'Letzter Login';
$lang['user_founder']			= 'Gr�nder';
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
$lang['user_look_up']			= 'Benutzer ausw�hlen';

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