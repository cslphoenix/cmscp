<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Servertypen',
	'explain'	=> 'Hier werden die Server verwaltet die f�r die GameQ Class wichtig sind.',

	'create'	=> 'Neues Spiel hinzugef�gt.',
	'update'	=> 'Spieldaten erfolgreich ge�ndert.',
	'delete'	=> 'Das Spiel wurde gel�scht!',
	'confirm'	=> 'dass dieses Spiel:',
	
	'input_data'	=> 'Typedaten',
	
	'type_name'		=> 'Name',
	'type_game'		=> 'Spiel',
	'type_dport'	=> 'Standardport',
	'type_sort'		=> 'Type',
	
	'serv_game'		=> 'Gameserver',
	'serv_voice'	=> 'Voiceserver',
));

$lang = array_merge($lang, array(

	'radio:type' => array(0 => $lang['serv_game'], 1 => $lang['serv_voice']),

));

?>