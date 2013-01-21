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
	'main'			=> 'Übergeordnete Kategorie/Label',
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
	
));

$lang = array_merge($lang, array(	
	
	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Menülabel', 2 => 'Menüpunkt'),
	'radio:navi'	=> array(3 => 'Kategorie', 4 => 'Menüpunkt'),
	'radio:target'	=> array(1 => $lang['target_new'], 0 => $lang['target_self']),
	
));

?>