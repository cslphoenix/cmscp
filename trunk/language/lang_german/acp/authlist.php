<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 *	Berechtigungsfelder
 *
 */

if ( !defined('IN_CMS') )
{
	exit;
}

$lang['authlist']			= 'Berechtigungsfelder';
$lang['authlist_field']		= 'Feld';
$lang['authlist_explain']	= 'Hier k&ouml;nnen Sie die Berechtigungsfelder der Seite verwalten, erleichtert das hinzuf&uuml;gen von Modifikationen die Extra Rechte haben sollen. Bitte nur was verstellen wenn man ein Backup der Datenbank gemacht hat!<br><b>Wichtig:</b> neue Felder werden direkt in der DB eingetragen und das \'auth_\' wird automatisch davor eingetragen!';

?>