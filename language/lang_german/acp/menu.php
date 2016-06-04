<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(	
	
	'title'			=> 'Menü',
	
	'explain_acp'	=> 'Admin Menü verwalten',
	'explain_mcp'	=> 'Moderatoren Menü verwalten',
	'explain_ucp'	=> 'Benutzer Menü verwalten',
	'explain_pcp'	=> 'Seiten Menü verwalten',
	
	'input_data'	=> 'Daten',
	
	'menu_name'		=> 'Name',
	'type'			=> 'Type',
	'main'			=> 'Kategorie/Label',
	'menu_lang'		=> 'Sprachdatei',
	'menu_show'		=> 'Anzeigen',
	'menu_intern'	=> 'Intern',
	'menu_target'	=> 'Ziel',
	'menu_file'		=> 'Menü-Datei/Adresse',
	'menu_opts'		=> 'Menü-Option',
	
	'type_0'		=> 'Kategorie',
	'type_1'		=> 'Menülabel',
	'type_2'		=> 'Menüpunkt',
	
	'acp'			=> 'Administrator',
	'mcp'			=> 'Moderator',
	'ucp'			=> 'Benutzer',
	'pcp'			=> 'Seiten',
	
	
	'target'		=> 'Ziel',
	'target_new'	=> 'Neue Seite',
	'target_self'	=> 'Selbe Seite',
	
	'teams'			=> 'Teams',
	'usergroups'	=> 'Benutzergruppen',
	'none'			=> 'Keine Kategorie',
	'permission'	=> 'Berechtigungnen',
	'dir'			=> 'Hauptverzeichnis',
	'settings'		=> 'Einstellungen',
	
	'confirm_0'				=> 'das diese Kategorie:',
	'confirm_1'				=> 'das diese Menü-Label:',
	'confirm_2'				=> 'das diese Menü-Punkt:',
	
	'notice_confirm0_delete'	=> 'Bist du sicher, %s <strong><em>%s</em></strong> und alle Menü-Label und alle Menü-Punkte gelöscht werden soll?',
	'notice_confirm1_delete'	=> 'Bist du sicher, %s <strong><em>%s</em></strong> und alle Menü-Punkte gelöscht werden soll?',
	'notice_confirm2_delete'	=> 'Bist du sicher, %s <strong><em>%s</em></strong> gelöscht werden soll?',
	
));

$lang = array_merge($lang, array(	
	
	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Menülabel', 2 => 'Menüpunkt'),
	'radio:navi'	=> array(3 => 'Kategorie', 4 => 'Menüpunkt'),
	'radio:target'	=> array(1 => $lang['target_new'], 0 => $lang['target_self']),
	
));

?>