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
 *	Galerie
 *
 */

if ( !defined('IN_CMS') )
{
	exit;
}

$lang['gallery']				= 'Galerie';
$lang['gallery_explain']		= 'Hier kannst Du Galerien verwalten.';

$lang['default_explain']		= 'Standarteinstellungen für Galerien, es kann aber jede Galerie selbst anders eingestellt werden.';

$lang['per_rows']				= 'Bilder pro Zeile';
$lang['per_cols']				= 'Bilder pro Seite';
$lang['max_width']				= 'Maximale Breite';
$lang['max_height']				= 'Maximale Höhe';
$lang['max_filesize']			= 'Maximale Größe';
$lang['preview_list']			= 'Adminvorschau';
$lang['preview_widht']			= 'Vorschaubreite';
$lang['preview_height']			= 'Vorschauhöhe';

$lang['list']		= 'als Liste';
$lang['preview']	= 'nach Vorgabe der Einstellungen';

$lang['msg_filetype']			= 'Das Bild muss im GIF-, JPG- oder PNG-Format sein.';
$lang['msg_filesize']			= 'Die Dateigröße muss kleiner als %d KB sein.';
$lang['msg_imagesize']			= 'Das Bild muss weniger als %d Pixel breit und %d Pixel hoch sein.';

$lang['sprintf_size-pic']		= '%s / %s Bilder';

$lang['auth_gallery_guest']		= 'Gast';
$lang['auth_gallery_user']		= 'Benutzer';
$lang['auth_gallery_trial']		= 'Trialmember';
$lang['auth_gallery_member']	= 'Member';
$lang['auth_gallery_coleader']	= 'Squadleader';
$lang['auth_gallery_leader']	= 'Leader';
$lang['auth_gallery_upload']	= 'Uploader';

$lang['pic_widht']				= 'Breite';
$lang['pic_height']				= 'Höhe';
$lang['pic_size']				= 'Größe';

$lang['auth_gallery_view']		= 'Betrachten';
$lang['auth_gallery_edit']		= 'Bearbeiten';
$lang['auth_gallery_delete']	= 'Löschen';
$lang['auth_gallery_rate']		= 'Bewertung';
$lang['auth_gallery_upload']	= 'Hochladen';

$lang['gallery_max_width']		= 'maximale Breite';
$lang['gallery_max_height']		= 'maximale Höhe';
$lang['gallery_max_filesize']	= 'maximale Größe';


$lang['auth_gallery'] = array(
	'auth_view'		=> $lang['auth_gallery_view'],
	'auth_edit'		=> $lang['auth_gallery_edit'],
	'auth_delete'	=> $lang['auth_gallery_delete'],
	'auth_rate'		=> $lang['auth_gallery_rate'],
	'auth_upload'	=> $lang['auth_gallery_upload'],
);

?>