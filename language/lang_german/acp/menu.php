<?php

if ( !defined('IN_CMS') )
{
	exit;
}

/* file */
$lang = array_merge($lang, array(	
	'title'		=> 'Menü',
	'explain'	=> 'menü krams bla blubb admin',
	
	'create'	=> 'Neuen Menüpunkt hinzugefügt.',
	'update'	=> 'Menüpunktdaten erfolgreich geändert.',
	'delete'	=> 'Der Menüpunkt wurde gelöscht!',
	'confirm'	=> 'das dieser Menüpunkt:',
	
	'data'		=> 'Menüpunktdaten',
	
	'menu_name'	=> 'Name',
	'menu_lang'	=> 'Sprache',
));

/* cat */
$lang = array_merge($lang, array(
	'title_cat'		=> 'Kategorie',
	'explain_cat'	=> 'blub bla acp admin menü',
	'create_cat'	=> 'Neue Kategorie hinzugefügt.',
	'update_cat'	=> 'Kategoriedaten erfolgreich geändert.',
	'delete_cat'	=> 'Die Kategorie wurde gelöscht!',
	'confirm_cat'	=> 'das diese Kategorie:',
	'data_cat'		=> 'Kategoriedaten',
	
	'cat_name'	=> 'Kategorie',
));

?>