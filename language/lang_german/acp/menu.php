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
	
	'create'	=> 'Neuen Menüpunkt hinzugefügt.',
	'update'	=> 'Menüpunktdaten erfolgreich geändert.',
	'delete'	=> 'Der Menüpunkt wurde gelöscht!',
	'confirm'	=> 'das dieser Menüpunkt:',
	
	'input_data'	=> 'Menüpunktdaten',
	
	'menu_name'		=> 'Name',
	'menu_lang'		=> 'Sprache',
	
	'type_0'	=> 'Kategorie',
	'type_1'	=> 'Menülabel',
	'type_2'	=> 'Menüpunkt',
	
	'acp'		=> 'Admin',
	'mcp'		=> 'Moderatoren',
	'ucp'		=> 'Benutzer',
	
	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Menülabel', 2 => 'Menüpunkt'),
	
));

?>