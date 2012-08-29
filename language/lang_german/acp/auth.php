<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'title'		=> 'Forum',
	'cat'		=> 'Kategorie',
	
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
	
	'tabs'	=> array(0 => 'Forum', 1 => 'Beitrag', 2 => 'Umfrage'),
	
	'auth_simple'	=> 'Normal',
	'auth_extended'	=> 'Individuell',
	
#	$lang['Forum_ALL'] = 'Alle';
#	$lang['Forum_REG'] = 'Reg';
#	$lang['Forum_PRIVATE'] = 'Privat';
#	$lang['Forum_MOD'] = 'Mods';
#	$lang['Forum_ADMIN'] = 'Admin';

	'closed' => 'Gesperrt',
	'opened' => 'Geöffnet',
	
	/* added 19.07 */
	
	'f_view'	=> 'Kann das Forum sehen',
	'f_read'	=> 'Kann das Forum lesen',
	'f_notice'	=> 'Kann Ankündigungen schreiben',
	'f_sticky'	=> 'Kann Wichtige Themen schreiben',
	'f_icons'	=> 'Kann Themenicons verwenden',
	'f_reply'	=> 'Kann antworten auf Themen',
	'f_post'	=> 'Kann Themen eröffnen',
	'm_ownedit'	=> 'Kann eigene Beiträge bearbeiten',
	'm_owndelete'	=> 'Kann eigene Beiträge löschen',
	'm_ownclose'	=> 'Kann eigene Themen schließen',
	'm_report'	=> 'Kann Thema melden',
	'p_view'	=> 'Kann Umfrage sehen',
	'p_create'	=> 'Kann Umfrage erstellen',
	'p_vote'	=> 'Kann an Umfrage teilnehmen',
	'p_change'	=> 'Kann seine Auswahl ändern',
	'p_close'	=> 'Kann Umfrage schließen',
	
	'forms_noneset' => 'keine Standartwerte zugewiesen',
	
	'forum_access_no'				=> 'Kein Zugang',
	'forum_access_only_read'		=> 'Nur lesender Zugriff',
	'forum_access_default'			=> 'Standard-Zugang',
	'forum_access_default_polls'	=> 'Standard-Zugang + Umfragen',
	'forum_access_full'				=> 'Voller Zugang',
	
	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Forum', 2 => 'Subforum'),

));

 

 

$lang = array_merge($lang, array(
	'radio:status'	=> array(0 => $lang['opened'], 1 => $lang['closed']),
	'radio:legend'	=> array(1 => $lang['common_view'], 0 => $lang['common_noview']),
	
));

$field_names = array(
	'auth_view'				=> $lang['forms_view'],
	'auth_read'				=> $lang['forms_read'],
	'auth_post'				=> $lang['forms_post'],
	'auth_reply'			=> $lang['forms_reply'],
	'auth_edit'				=> $lang['forms_edit'],
	'auth_delete'			=> $lang['forms_delete'],
	'auth_sticky'			=> $lang['forms_sticky'],
	'auth_announce'			=> $lang['forms_announce'],
	'auth_globalannounce'	=> $lang['forms_globalannounce'],
	'auth_poll'				=> $lang['forms_poll'],
	'auth_pollcreate'		=> $lang['forms_pollcreate'],
);

?>