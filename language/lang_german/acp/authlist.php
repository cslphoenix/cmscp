<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'authlist'	=> 'Berechtigungsfelder',
	'explain'	=> 'Hier können Sie die Berechtigungsfelder der Seite verwalten, erleichtert das hinzufügen von Modifikationen die Extra Rechte haben sollen. Bitte nur was verstellen wenn man ein Backup der Datenbank gemacht hat!<br><b>Wichtig:</b> neue Felder werden direkt in der DB eingetragen und das \'auth_\' wird automatisch davor eingetragen!',
	
	'create'	=> 'Neues Berechtigungsfeld hinzugefügt.',
	'update'	=> 'Berechtigungsfelddaten erfolgreich geändert.',
	'delete'	=> 'Das Berechtigungsfeld wurde gelöscht!',
	'confirm'	=> 'dass dieses Berechtigunsfeld:',
	
	'field'		=> 'Feld',

));

?>