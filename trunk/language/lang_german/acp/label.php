<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Labeltypen',
	'explain'	=> 'explain',

	'input_data'	=> 'Labeldaten',
	
	'label_name'	=> 'Name',
	'label_desc'	=> 'Beschreibung',
	'label_type'	=> 'Type',
	
	'label_admin_none'	=> 'Keine Adminrechte',
	'label_admin_full'	=> 'Volle Adminrechte',

#	'radio:type' => array('a_' => 'Administratorenlabel', 'm_' => 'Moderatorenlabel', 'u_' => 'Benutzerlabel', 'f_' => 'Forumlabel'),

	'tabs:admin'	=> array(0 => 'Kontakt', 1 => 'Forum', 2 => 'News', 3 => 'Seite', 4 => 'Server', 5 => 'Team', 6 => 'Benutzer & Gruppen', 7 => 'Sonstiges'),
	'tabs:forum'	=> array(0 => 'Forum', 1 => 'Beitrag', 2 => 'Umfrage'),
));

?>