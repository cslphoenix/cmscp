<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Karte',
	'explain'	=> 'Hier kann man die Maps(Karten) bearbeiten, löschen und nach eigener Vorstellungen sortieren.',

	'map_name'	=> 'Name',
	'type'		=> 'Type',
	'main'		=> 'Kategorie',
	'map_tag'	=> 'Tag',
	'map_file'	=> 'Bilddatei',
	'map_info'	=> 'Karteninfo',
	'map_order'	=> 'Reihenfolge',
	
	'type_0'	=> 'Kategorie',
	'type_1'	=> 'Karte',
	
	'msg_select_tag_image'	=> 'Bitte test blubb',
	
));

$lang = array_merge($lang, array('radio:type' => array(0 => $lang['type_0'], 1 => $lang['type_1'])));

?>