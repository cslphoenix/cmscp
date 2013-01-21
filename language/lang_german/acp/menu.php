<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(	
	
	'title'			=> 'Men�',
	
	'explain_acp'	=> 'Admin Men� verwalten',
	'explain_mcp'	=> 'Moderatoren Men� verwalten',
	'explain_ucp'	=> 'Benutzer Men� verwalten',
	'explain_pcp'	=> 'Seiten Men� verwalten',
	
	'input_data'	=> 'Daten',
	
	'menu_name'		=> 'Name',
	'type'			=> 'Type',
	'main'			=> '�bergeordnete Kategorie/Label',
	'menu_lang'		=> 'Sprachdatei',
	'menu_show'		=> 'Anzeigen',
	'menu_intern'	=> 'Intern',
	'menu_target'	=> 'Ziel',
	'menu_file'		=> 'Men�-Datei/Adresse',
	'menu_opts'		=> 'Men�-Option',
	
	'type_0'		=> 'Kategorie',
	'type_1'		=> 'Men�label',
	'type_2'		=> 'Men�punkt',
	
	'acp'			=> 'Administrator',
	'mcp'			=> 'Moderator',
	'ucp'			=> 'Benutzer',
	'pcp'			=> 'Seiten',
	
	
	'target'		=> 'Ziel',
	'target_new'	=> 'Neue Seite',
	'target_self'	=> 'Selbe Seite',
	
));

$lang = array_merge($lang, array(	
	
	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Men�label', 2 => 'Men�punkt'),
	'radio:navi'	=> array(3 => 'Kategorie', 4 => 'Men�punkt'),
	'radio:target'	=> array(1 => $lang['target_new'], 0 => $lang['target_self']),
	
));

?>