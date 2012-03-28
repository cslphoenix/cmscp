<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'page_start'	=> 'Seite installiert',
	'page_version'	=> 'CMS Version',
	'page_backup'	=> 'letztes Backup',
	'page_gzip'		=> 'GZip Komprimierung',
	'server_info'	=> 'Version PHP / MySQL',
	'page_dbinfo'	=> 'Datenbankinfo',
	
	'path_cache'		=> 'Cache',
	'path_files'		=> 'Files',
	'path_downloads'	=> 'Downloads',
	'path_gallery'		=> 'Galerie',
	'path_matchs'		=> 'Begegnungen',
	'path_users'		=> 'Benutzer',
	
	'dir'	=> 'Verzeichnis',
	'size'	=> 'Größe',
	'info'	=> 'Info',
	'value'	=> 'Werte',
	
	'sprintf_dbsize'	=> 'Einträge: %s<br />Tabellen: %s',
	'sprintf_backup'	=> 'Größe: %s',
	'backup_na'			=> 'Nicht Vorhanden!',
	
	'cache_clear'		=> 'leeren',
	
	'version_check' => 'Version prüfen',
	'version_info_red'		=> '<span style="color:red">%s</span> [ <a href="%s" title="%s">%s</a> ]<br /><span style="color:red">neuere Version: <b>%s</b></span>',
	'version_info_green'	=> '<span style="color:green">%s</span> [ <a href="%s" title="%s">%s</a> ]',
	
	'cache_valid' => 'Gültig bis: %s',
));

?>