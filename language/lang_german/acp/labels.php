<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'NO_SELECT'					=> 'Keine Auswahl',
	'NO_SELECT_DESC'			=> 'test bla blub',
	
	'LABEL_ADMIN_NONE'			=> 'Keine Administrator-Rechte',
	'LABEL_ADMIN_NONE_DESC'		=> 'Hat keine Rechte für den Administrator-Bereich.',
	'LABEL_ADMIN_FULL'			=> 'Volle Administrator-Rechte',
	'LABEL_ADMIN_FULL_DESC'		=> 'Kann alle Einstellungen für Teams, Gruppen, Benutzer und der Seite ändern.',
	
	'LABEL_DOWNLOAD_NONE'		=> 'Keine Download-Rechte',
	'LABEL_DOWNLOAD_NONE_DESC'	=> 'Keine Download-Rechte',
	'LABEL_DOWNLOAD_FULL'		=> 'Volle Download-Rechte',
	'LABEL_DOWNLOAD_FULL_DESC'	=> 'Volle Download-Rechte',
	
	'LABEL_FORUM_NONE'			=> 'Keine Forum-Rechte',
	'LABEL_FORUM_NONE_DESC'		=> 'Keine Forum-Rechte',
	'LABEL_FORUM_FULL'			=> 'Volle Forum-Rechte',
	'LABEL_FORUM_FULL_DESC'		=> 'Volle Forum-Rechte',
	
	'LABEL_GALLERY_NONE'		=> 'Keine Galerie-Rechte',
	'LABEL_GALLERY_NONE_DESC'	=> 'Keine Galerie-Rechte',
	'LABEL_GALLERY_FULL'		=> 'Volle Galerie-Rechte',
	'LABEL_GALLERY_FULL_DESC'	=> 'Volle Galerie-Rechte',
	
	'TYPE_ADMIN'	=> 'Administrator-Label',
	'TYPE_FORUM'	=> 'Forum-Label',
	'TYPE_MOD'		=> 'Moderatoren-Label',
	'TYPE_GALLERY'	=> 'Galerie-Label',
	'TYPE_DL'		=> 'Download-Label',
	'TYPE_USER'		=> 'Benutzer-Label',
#	'type_new'		=> 'Recht',

));

?>