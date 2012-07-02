<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Server',
	'explain'	=> 'Verwaltung der Gameserver.',

	'create'	=> 'Neuen Server hinzugefügt.',
	'update'	=> 'Serverdaten erfolgreich geändert.',
	'delete'	=> 'Der Server wurde gelöscht!',
	'confirm'	=> 'dass dieser Server:',
	
	'server_ip'		=> 'IP',
	'server_pw'		=> 'Passwort',
	'server_port'	=> 'Port',
	'server_qport'	=> 'QPort',
	'server_type'	=> 'Servertyp',
	'server_game'	=> 'Server Spiel/Voice',
	'server_live'	=> 'Live',
	'server_list'	=> 'Auflisten',
	'server_show'	=> 'Anzeigen',
	'server_own'	=> 'eigener Server',
	
	'cur_max'	=> '%s / %s',
	
	'gameserver'	=> 'Gameserver',
	'voiceserver'	=> 'Voiceserver',
	
	'online'	=> 'Online',
	'offline'	=> 'Offline',

));

?>