<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'authlist'	=> 'Berechtigungsfelder',
	'explain'	=> 'Hier k�nnen Sie die Berechtigungsfelder der Seite verwalten, erleichtert das hinzuf�gen von Modifikationen die Extra Rechte haben sollen. Bitte nur was verstellen wenn man ein Backup der Datenbank gemacht hat!<br><b>Wichtig:</b> neue Felder werden direkt in der DB eingetragen und das \'auth_\' wird automatisch davor eingetragen!',
	
	'create'	=> 'Neues Berechtigungsfeld hinzugef�gt.',
	'update'	=> 'Berechtigungsfelddaten erfolgreich ge�ndert.',
	'delete'	=> 'Das Berechtigungsfeld wurde gel�scht!',
	'confirm'	=> 'dass dieses Berechtigunsfeld:',
	
	'field'		=> 'Feld',

));

?>