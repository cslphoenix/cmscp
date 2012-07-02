<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'profile'	=> 'Profilefelder',
	'profile_c'	=> 'Profilekategorie',
	'explain'	=> 'Hier kann man ja halt irgendwas machen xD',

	'create'	=> 'Neues Profilefeld hinzugefügt.',
	'update'	=> 'Profilefeld erfolgreich geändert.',
	'delete'	=> 'Das Profilefeld wurde gelöscht!',
	'confirm'	=> 'dass dieses Profilfeld:',
	
	'create_cat'	=> 'Neues Profilekategorie hinzugefügt.',
	'update_cat'	=> 'Profilekategorie erfolgreich geändert.',
	'delete_cat'	=> 'Die Profilekategorie wurde gelöscht!',
	'confirm_sub'	=> 'dass dieses Profilekategorie:',
	
	'field'		=> 'Profilefeld',
	'cat'		=> 'Profilekategrie',
	'cats'		=> 'Profilekategrien',
	
	'show_guest'	=> 'für Gäste',
	'show_member'	=> 'für Mitglieder',
	'show_register'	=> 'beim registieren',
	
	'necessary'	=> 'Pflichtfeld',
	
	'type_text'		=> 'Textzeile',
	'type_area'		=> 'Textfeld',
	'type_radio'	=> 'Ja/Nein Option',
	
));
	

$lang = array_merge($lang, array(	
	'radio:type'	=> array(0 => $lang['type_text'], 1 => $lang['type_area'], 2 => $lang['type_radio']),
));

?>