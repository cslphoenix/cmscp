<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'forum'		=> 'Forum',
	'forum_c'	=> 'Kategorie',
	'explain'	=> 'Hier kann man die Kategorien, Foren und Subforen alle samt Verwalten.',

	'create'	=> 'Neues Forum hinzugefügt.',
	'update'	=> 'Forumdaten erfolgreich geändert.',
	'delete'	=> 'Das Forum wurde gelöscht!',
	'confirm'	=> 'dass dieses Forum:',

	'create_c'	=> 'Neue Kategorie hinzugefügt.',
	'update_c'	=> 'Kategoriedaten erfolgreich geändert.',
	'delete_c'	=> 'Die Kategorie wurde gelöscht!',
	'confirm_c'	=> 'dass diese Kategorie:',
	
	'icon'		=> 'Forumicon',
	'sub'		=> 'Subforum',
	'main'		=> 'Hauptforum',
	'copy'		=> 'Rechtekopieren',
	
	'locked'	=> 'Gesperrt',
	'unlocked'	=> 'Offen',
	
	'legend'	=> 'Auflisten',
	'legend_ex'	=> 'Wurde \'Ja\' aktiviert, werden die Unterforen in der Legende angezeigt.',
	
	'auth_all'			=> 'Öffentlich',
	'auth_register'		=> 'Registriert',
	'auth_trial'		=> 'Trial',
	'auth_member'		=> 'Mitglieder',
	'auth_moderator'	=> 'Moderatoren',
	'auth_private'		=> 'Privat',
	'auth_admin'		=> 'Administrator',
	
	'forms_public'		=> 'Öffentlich',
	'forms_register'	=> 'Registriert',
	'forms_trial'		=> 'Trial',
	'forms_member'		=> 'Mitglieder',
	'forms_moderator'	=> 'Moderatoren',
	'forms_privat'		=> 'Privat',
	'forms_admin'		=> 'Administrator',
	'forms_special'		=> 'Individuell',
	'forms_hidden'		=> '%s <span style="font-size:9px">[ Versteckt ]</span>',
	
	'forms_view'			=> 'Ansicht',
	'forms_read'			=> 'Lesen',
	'forms_post'			=> 'Posten',
	'forms_reply'			=> 'Antworten',
	'forms_edit'			=> 'Editieren',
	'forms_delete'			=> 'Löschen',
	'forms_sticky'			=> 'Wichtig',
	'forms_announce'		=> 'Ankündigung',
	'forms_globalannounce'	=> 'Globaleankündigung',
	'forms_poll'			=> 'Umfrage',
	'forms_pollcreate'		=> 'Umfrage erstellen',
	
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
	
	'auth_simple'	=> 'Normal',
	'auth_extended'	=> 'Individuell',
	
#	$lang['Forum_ALL'] = 'Alle';
#	$lang['Forum_REG'] = 'Reg';
#	$lang['Forum_PRIVATE'] = 'Privat';
#	$lang['Forum_MOD'] = 'Mods';
#	$lang['Forum_ADMIN'] = 'Admin';

));

?>