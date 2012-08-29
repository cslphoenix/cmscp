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
	
	'create'	=> 'Neuen Men�punkt hinzugef�gt.',
	'update'	=> 'Men�punktdaten erfolgreich ge�ndert.',
	'delete'	=> 'Der Men�punkt wurde gel�scht!',
	'confirm'	=> 'das dieser Men�punkt:',
	
	'input_data'	=> 'Men�punktdaten',
	
	'menu_name'		=> 'Name',
	'menu_lang'		=> 'Sprache',
	
	'type_0'	=> 'Kategorie',
	'type_1'	=> 'Men�label',
	'type_2'	=> 'Men�punkt',
	
	'acp'		=> 'Admin',
	'mcp'		=> 'Moderatoren',
	'ucp'		=> 'Benutzer',
	
	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Men�label', 2 => 'Men�punkt'),
	
));

?>