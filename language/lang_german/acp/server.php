<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'SERVER_TITLE'		=> 'Server',
	'SERVER_EXPLAIN'	=> 'Verwaltung der Gameserver.',
	'GAMEQ_TITLE'		=> 'Servertypen',
	'GAMEQ_EXPLAIN'		=> 'Hier werden die Server verwaltet die für die GameQ Class wichtig sind.',
	
	'INPUT_DATA_SERVER'	=> 'Daten eingeben',
	'INPUT_DATA_GAMEQ'	=> 'Servertyp Daten',
	
	'SERVER_NAME'	=> 'Server Name',
	'SERVER_TYPE'	=> 'Server Typ',
	'SERVER_GAME'	=> 'Server Game',
	'SERVER_IP'		=> 'IP',
	'SERVER_PORT'	=> 'Port',
	'SERVER_PW'		=> 'Passwort',
#	'server_qport'	=> 'QPort',
	'SERVER_LIVE'	=> 'Live',
	'SERVER_LIST'	=> 'Auflisten',
	'SERVER_SHOW'	=> 'Anzeigen',
	'SERVER_OWN'	=> 'eigener Server',
	'SERVER_ORDER'	=> 'Reihenfolge',
	
	'CURRENT_MAX'	=> '%s / %s',
	
#	'gameserver'	=> 'Gameserver',
#	'voiceserver'	=> 'Voiceserver',
	
	'ONLINE'	=> 'Online',
	'OFFLINE'	=> 'Offline',
	
	'GAMEQ_NAME'	=> 'Name',
	'GAMEQ_GAME'	=> 'GameQ Spiel',
	'GAMEQ_DPORT'	=> 'Standardport',
	'GAMEQ_TYPE'	=> 'Type',
	'GAMEQ_VIEWER'	=> 'Viewer',
	
	'TYP_GAME'		=> 'Gameserver',
	'TYP_VOICE'		=> 'Voiceserver',
));

$lang = array_merge($lang, array(

	'radio:type' => array(
		0 => $lang['TYP_GAME'],
		1 => $lang['TYP_VOICE'],
	),
	
));

?>