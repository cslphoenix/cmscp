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
	
	'gameserver'	=> 'Gameserver',
	'voiceserver'	=> 'Voiceserver',
	
	'online'	=> 'Online',
	'offline'	=> 'Offline',

));

?>