<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'acp_cat_addition'	=> 'Zusatz',
	
	'acp_change'		=> 'Änderungsprotokoll',

	'TITLE'		=> 'Änderungsprotokoll',
	'EXPLAIN'	=> 'Hier kannst du Änderungsprotokoll anlegen.',
	
	'change_num'	=> 'Name',
	'change_date'	=> 'Datum',
	'main'			=> 'Version',
	
	'radio:type' => array(0 => 'Eintrag', 1 => 'Änderungen'),
	
	'radio:typ'	=> array(
		0 => 'Hinzugefügt :: Added',
		1 => 'Geändert :: Changed',
		2 => 'Entfernt :: Removed',
		3 => 'Bug-Fixes :: Fixed',
		4 => 'Sicherheit :: Security',
	),
));

?>