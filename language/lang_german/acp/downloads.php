<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'title'		=> 'Download',
	'file'		=> 'Datei',
	'cat'		=> 'Kategorie',
	'explain'	=> 'Downloads',

	'create'	=> 'Neuen Download hinzugefügt.',
	'update'	=> 'Downloaddaten erfolgreich geändert.',
	'delete'	=> 'Der Download wurde gelöscht!',
	'confirm'	=> 'dass dieser Datei:',
	
	'data'		=> 'Downloaddaten',
	
	'download_name'		=> 'Name',

	
	'create_cat'	=> 'Neue Downloadkategorie hinzugefügt.',
	'update_cat'	=> 'Downloadkategorie erfolgreich geändert.',
	'delete_cat'	=> 'Die Downloadkategorie wurde gelöscht!',
	'confirm_cat'	=> 'dass diese Downloadkategorie:',
	
	'data_cat'		=> 'Kategoriedaten',
	
	
	'name_cat'			=> 'Name',
	'desc_cat'			=> 'Beschreibung',
	'icon_cat'			=> 'Icon',
	'path_cat'			=> 'Pfad',
	'types_cat'			=> 'Type',
	'auth_cat'			=> 'Freigabe für',
	'auth_set_cat'		=> 'Freigabe einstellen',
	'rate_cat'			=> 'Bewertung',
	'rate_set_cat'		=> 'Bewertung einstellen',
	'comment_cat'		=> 'Kommentare',
	'comment_set_cat'	=> 'Kommentare einstellen',
	
	/* http://www.php.net/manual/de/function.mime-content-type.php#87856 */
	'meta_application'	=> 'Anwendung',
	'meta_image'		=> 'Bilder',
	'meta_text'			=> 'Text',
	'meta_video'		=> 'Audio / Video',
	
	'type_application'	=> array(
		'ai, eps, ps'	=> 'application/postscript',		
		'cab'			=> 'application/vnd.ms-cab-compressed',
		'doc'			=> 'application/msword',
		'exe, msi'		=> 'application/x-msdownload',	
		'js'			=> 'application/javascript',
		'json'			=> 'application/json',
		'ods'			=> 'application/vnd.oasis.opendocument.spreadsheet',
		'odt'			=> 'application/vnd.oasis.opendocument.text',
		'pdf'			=> 'application/pdf',
		'ppt'			=> 'application/vnd.ms-powerpoint',
		'rar'			=> 'application/x-rar-compressed',
		'rtf'			=> 'application/rtf',
		'swf'			=> 'application/x-shockwave-flash',
		'xls'			=> 'application/vnd.ms-excel',
		'xml'			=> 'application/xml',
		'zip'			=> 'application/zip',
		'torren'		=> 'application/x-bittorrent',
	),
	
	'type_image' => array(
		'bmp'				=> 'image/bmp',
		'gif'				=> 'image/gif',
		'png'				=> 'image/png',
		'psd'				=> 'image/vnd.adobe.photoshop',
		'ico'				=> 'image/vnd.microsoft.icon',
		'jpe, jpeg, jpg'	=> 'image/jpeg',			
		'tiff, tif'			=> 'image/tiff',	
		'svg, svgz'			=> 'image/svg+xml',	
	),
	
	'type_text' => array(
		'css'				=> 'text/css',
		'htm, html, php'	=> 'text/html',			
		'txt'				=> 'text/plain',
	),
	
	'type_video' => array(
		'mp3'		=> 'audio/mpeg',
		'flv'		=> 'video/x-flv',
		'qt, mov'	=> 'video/quicktime',
	),

	'up_filesize'	=> 'Die Dateigröße muss kleiner als %d KB sein.',
	'up_filetype'	=> 'Die Dateitype entspricht nicht den vorgaben: %s.',
	
	'msg_empty_name'	=> 'Bitte ein Namen eintragen!',
	'msg_select_file'	=> 'Bitte eine Datei auswählen!',
	

	
	
));

?>