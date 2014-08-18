<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Galerie',
	'explain'	=> 'Hier werden alle Galerien verwaltet. Die Ordnergröße und Anzahl wird immer nur auf die Eigentlichen Bilder bezogen nicht auf die Vorschaubilder, diese werden nicht mit einberechnet!',
	
	'data_input'	=> 'Galerie-Daten',
	
	'gallery_name'		=> 'Name',
	'type'				=> 'Typ',
	'main'				=> 'Kategorie',
	'copy'				=> 'Rechte kopieren',
	'gallery_desc'		=> 'Beschreibung',
	'gallery_comments'	=> 'Kommentare',
	'gallery_rate'		=> 'Bewertung',
	'gallery_filesize'	=> 'Größe',
	'gallery_dimension'	=> 'Abmessung',
	'gallery_format'	=> 'Darstellung',
	'gallery_thumbnail'	=> 'Vorschau',
	
	'sprintf_size-pic'	=> '%s / %s Bilder',
	
	'input_data'	=> 'Galeriedaten',
	
	'rating'	=> 'Bewertung',
	'comment'	=> 'Kommentare',
	
#	'update_d'	=> 'Standarteinstellungen erfolgreich geändert.',
#	
#	'update_u'	=> 'Bild/Bilder erfolgreich hochgeladen.',
	
	
#	$lang['create_gallery']				= 'Neue Galerie hinzugefügt.';
#	$lang['update_gallery']				= 'Galerie erfolgreich geändert';
#	$lang['update_gallery_default']		= 'Galerie Standardeinstellungen geändert!';		
#	$lang['update_gallery_pic']			= 'Galeriebilder erfolgreich geändert';
#	$lang['update_gallery_upload']		= 'Bild oder Bilder hochgeladen.';		
#	$lang['delete_confirm_gallery']		= 'dass diese Galerie:';
#	$lang['delete_gallery']				= 'Die Galerie wurde gelöscht!';
#	$lang['delete_gallery_pic']			= 'Das Bilder oder die Bilder wurden gelöscht!';		

#	'auth_gallery_guest'	=> 'Gast',
#	'auth_gallery_user'		=> 'Benutzer',
#	'auth_gallery_trial'	=> 'Trialmember',
#	'auth_gallery_member'	=> 'Member',
#	'auth_gallery_coleader'	=> 'Squadleader',
#	'auth_gallery_leader'	=> 'Leader',
#	'auth_gallery_uploader'	=> 'Uploader',
	
#	'auth_gallery_view'		=> 'Betrachten',
#	'auth_gallery_edit'		=> 'Bearbeiten',
#	'auth_gallery_delete'	=> 'Löschen',
#	'auth_gallery_rate'		=> 'Bewertung',
#	'auth_gallery_upload'	=> 'Upload',

#	'auth_gallery' => array(
#		'auth_view'		=> 'Bild betrachten',
#		'auth_edit'		=> 'Bild bearbeiten',
#		'auth_delete'	=> 'Bild löschen',
#		'auth_rate'		=> 'Bild bewerten',
#		'auth_upload'	=> 'Bild uploaden',
#	),
	
#	'per_rows'			=> 'Bilder pro Zeile',
#	'per_cols'			=> 'Bilder pro Seite',
#	'max_width'			=> 'Maximale Breite',
#	'max_height'		=> 'Maximale Höhe',
#	'max_filesize'		=> 'Maximale Größe',
#	'preview_list'		=> 'Adminvorschau',
#	'preview_widht'		=> 'Vorschaubreite',
#	'preview_height'	=> 'Vorschauhöhe',
#
#	'list'		=> 'als Liste',
#	'preview'	=> 'nach Vorgabe der Einstellungen',
	
#	'pic'			=> 'Bild',
#	'pic_widht'		=> 'Breite',
#	'pic_height'	=> 'Höhe',
#	'pic_size'		=> 'Größe',
	
	
	'radio:type'	=> array(0 => 'Ordner', 1 => 'Bild'),


));

/*
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



$lang['auth_gallery_guest']		= 'Gast';
$lang['auth_gallery_user']		= 'Benutzer';
$lang['auth_gallery_trial']		= 'Trialmember';
$lang['auth_gallery_member']	= 'Member';
$lang['auth_gallery_coleader']	= 'Squadleader';
$lang['auth_gallery_leader']	= 'Leader';
$lang['auth_gallery_upload']	= 'Uploader';


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
*/
?>