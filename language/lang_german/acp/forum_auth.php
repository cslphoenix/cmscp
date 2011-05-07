<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
#	'game'		=> 'Spiel',
#	'explain'	=> 'Hier kannst du die Spiele der Seite Verwalten. Du kannst bestehende Spiele löschen, editieren oder neue anlegen.',

#	'create'	=> 'Neues Spiel hinzugefügt.',
	'update'	=> 'Forumberechtigung erfolgreich geändert.',
#	'delete'	=> 'Das Spiel wurde gelöscht!',
#	'confirm'	=> 'dass dieses Spiel:',

	'legende'			=> 'Bildlegende',
	
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
	
	'no_entry'			=> 'Keine Foren vorhanden.',

));

$lang['Look_up_Forum'] = 'Forum auswählen';
$lang['Simple_mode'] = 'Einfache Methode';
$lang['Advanced_mode'] = 'Fortgeschrittene Methode';
$lang['Auth_Control_Forum'] = 'Forenzugangskontrolle';
$lang['Forum_auth_explain'] = 'Du kannst hier die Zugangsebenen für jedes Forum bestimmen. Es gibt eine einfache und eine fortgeschrittene Methode, dies zu tun. Bei der fortgeschrittenen Methode hast du eine bessere Kontrolle über jedes Forum. Bedenke, dass das Ändern der Zugangsebene beeinflusst, welche Benutzer welche Aktionen im Forum durchführen können.';

/*
$lang['Forum_auth_list_explain_ALL'] = 'All users';
$lang['Forum_auth_list_explain_REG'] = 'All registered users';
$lang['Forum_auth_list_explain_PRIVATE'] = 'Only users granted special permission';
$lang['Forum_auth_list_explain_MOD'] = 'Only moderators of this forum';
$lang['Forum_auth_list_explain_ADMIN'] = 'Only administrators';

$lang['Forum_auth_list_explain_auth_view'] = '%s can view this forum';
$lang['Forum_auth_list_explain_auth_read'] = '%s can read posts in this forum';
$lang['Forum_auth_list_explain_auth_post'] = '%s can post in this forum';
$lang['Forum_auth_list_explain_auth_reply'] = '%s can reply to posts this forum';
$lang['Forum_auth_list_explain_auth_edit'] = '%s can edit posts in this forum';
$lang['Forum_auth_list_explain_auth_delete'] = '%s can delete posts in this forum';
$lang['Forum_auth_list_explain_auth_sticky'] = '%s can post sticky topics in this forum';
$lang['Forum_auth_list_explain_auth_announce'] = '%s can post announcements in this forum';
$lang['Forum_auth_list_explain_auth_vote'] = '%s can vote in polls in this forum';
$lang['Forum_auth_list_explain_auth_pollcreate'] = '%s can create polls in this forum';
*/

$lang = array_merge($lang, array(
	'auth_forum_explain_all'		=> 'Alle Gäste',
	'auth_forum_explain_register'	=> 'Alle registrierten Benutzer',
	'auth_forum_explain_private'	=> 'Nur Benutzer mit speziellen Erlaubnis',
	'auth_forum_explain_moderator'	=> 'Nur Moderatoren dieses Forums',
	'auth_forum_explain_admin'		=> 'Nur Administratoren',
	'auth_forum_explain_trial'		=> 'Nur Trials',
	'auth_forum_explain_member'		=> 'Nur Mitglieder',
	
	'auth_forum_explain_auth_view'				=> '%s können das Forum sehen.',
	'auth_forum_explain_auth_read'				=> '%s können Beiträge lesen.',
	'auth_forum_explain_auth_post'				=> '%s können Beiträge schreibe.',
	'auth_forum_explain_auth_reply'				=> '%s können Antworten auf Beiträge.',
	'auth_forum_explain_auth_edit'				=> '%s können Beiträge bearbeiten.',
	'auth_forum_explain_auth_delete'			=> '%s können Beiträge löschen.',
	'auth_forum_explain_auth_sticky'			=> '%s können Wichtig Themen schreiben.',
	'auth_forum_explain_auth_announce'			=> '%s können Ankündigungen schreiben.',
	'auth_forum_explain_auth_globalannounce'	=> '%s können Globale Themen schreiben.',
	'auth_forum_explain_auth_poll'				=> '%s können an Umfragen teilnehmen.',
	'auth_forum_explain_auth_pollcreate'		=> '%s können Umfragen starten/erstellen.',
));

$lang['Permissions_List'] = 'Permissions List';// Added by Permissions List MOD
$lang['Forum_auth_list_explain'] = 'This provides a summary of the authorisation levels of each forum. You can edit these permissions, using either a simple or advanced method by clicking on the forum name. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.';// Added by Permissions List MOD
$lang['Cat_auth_list_explain'] = 'This provides a summary of the authorisation levels of each forum within this category. You can edit the permissions of individual forums, using either a simple or advanced method by clicking on the forum name. Alternatively, you can set the permissions for all the forums in this category by using the drop-down menus at the bottom of the page. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.';// Added by Permissions List MOD
$lang['Forum_name'] = 'Forumsname';



?>