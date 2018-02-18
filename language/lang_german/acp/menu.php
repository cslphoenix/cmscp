<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(	
	'TITLE'		=> 'Menü',
	
	'INPUT_DATA'	=> 'Daten',
	'MENU_NAME'		=> 'Name',
	'TYPE'			=> 'Type',
	'MAIN'			=> 'Kategorie/Label',
	'MENU_SHOW'		=> 'Anzeigen',
	'MENU_INTERN'	=> 'Intern',
	'MENU_TARGET'	=> 'Ziel',
	'MENU_FILE'		=> 'Menü-Datei/Adresse',
	'MENU_OPTS'		=> 'Menü-Option',
	
	'TYPE_0'		=> 'Kategorie',
	'TYPE_1'		=> 'Menülabel',
	'TYPE_2'		=> 'Menüpunkt',
	
	'NAVI_MAIN'		=> 'Main Navigation',
	'NAVI_CLAN'		=> 'Clan Navigation',
	'NAVI_COM'		=> 'Community Navigation',
	'NAVI_MISC'		=> 'Misc Navigation',
	'NAVI_USER'		=> 'Benutzer Navigation',
	
	'ACP'			=> 'Administrator',
	'MCP'			=> 'Moderator',
	'UCP'			=> 'Benutzer',
	'PCP'			=> 'Seiten',
	
	'EXPLAIN_ACP'	=> 'Admin Menü verwalten',
	'EXPLAIN_MCP'	=> 'Moderatoren Menü verwalten',
	'EXPLAIN_UCP'	=> 'Benutzer Menü verwalten',
	'EXPLAIN_PCP'	=> 'Seiten Menü verwalten',
	
	'SETTINGS'		=> 'Einstellungen',
	'ADDITION'		=> 'Zusatz',
	'TEAMS'			=> 'Teams',
	'SYSTEM'		=> 'System',
	'SITE'			=> 'Seite',
	'CLAN'			=> 'Clan',
	'USERGROUPS'	=> 'Benutzer & Gruppen',
	'PERMISSION'	=> 'Berechtigung',
	
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