<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Server',
	'explain'	=> 'Verwaltung der Gameserver.',
	
	'gameq_title'	=> 'Servertypen',
	'gameq_explain'	=> 'Hier werden die Server verwaltet die für die GameQ Class wichtig sind.',
	
	'server_name'	=> 'Server Name',
	'server_type'	=> 'Server Typ',
	'server_game'	=> 'Server Game',
	'server_ip'		=> 'IP',
	'server_port'	=> 'Port',
	'server_pw'		=> 'Passwort',
#	'server_qport'	=> 'QPort',
	'server_live'	=> 'Live',
	'server_list'	=> 'Auflisten',
	'server_show'	=> 'Anzeigen',
	'server_own'	=> 'eigener Server',
	'server_order'	=> 'Reihenfolge',
	
	'cur_max'	=> '%s / %s',
	
#	'gameserver'	=> 'Gameserver',
#	'voiceserver'	=> 'Voiceserver',
	
	'online'	=> 'Online',
	'offline'	=> 'Offline',
	
	'gameq_name'	=> 'Name',
	'gameq_game'	=> 'GameQ Spiel',
	'gameq_dport'	=> 'Standardport',
	'gameq_type'	=> 'Type',
	'gameq_viewer'	=> 'Viewer',
	
	'typ_game'		=> 'Gameserver',
	'typ_voice'		=> 'Voiceserver',
	
	'all_update'	=> 'alle bearbeiten',

));

$lang = array_merge($lang, array(

	'radio:type' => array(
		0 => $lang['typ_game'],
		1 => $lang['typ_voice'],
	),
	
));

?>