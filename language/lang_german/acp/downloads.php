<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'title'		=> 'Download',
	'explain'	=> 'Downloads halt',

	'data_input'	=> 'Download-Daten',
	
	'dl_name'		=> 'Name',
	'type'			=> 'Type',
	'main'			=> 'Kategorie',
	'dl_desc'		=> 'Beschreibung',
	'dl_icon'		=> 'Icon',
	'dl_types'		=> 'Art',
	'dl_file'		=> 'Datei',
	'dl_size'		=> 'Größe',
	'dl_maxsize'	=> 'Maximale Größe',
	'dl_rate'		=> 'Bewertung',
	'dl_comment'	=> 'Kommentare',
	
	'dl_size_explain'	=> 'Maximale Größe',
	
	
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
	/* image http://www.iconfinder.com/search/?q=file+extension# */
	'mime_application'	=> 'Anwendung',
	'mime_image'		=> 'Bilder',
	'mime_text'			=> 'Text',
	'mime_video'		=> 'Audio / Video',
	
	'mime_application_type'	=> array(
		'ai, eps, ps'	=> 'application/postscript',		
		'cab'			=> 'application/vnd.ms-cab-compressed',
		'doc'			=> 'application/msword',
		'exe, msi'		=> 'application/x-msdownload',	
		'js'			=> 'application/javascript',
	#	'json'			=> 'application/json',
	#	'ods'			=> 'application/vnd.oasis.opendocument.spreadsheet',
	#	'odt'			=> 'application/vnd.oasis.opendocument.text',
		'pdf'			=> 'application/pdf',
		'ppt'			=> 'application/vnd.ms-powerpoint',
		'rar'			=> 'application/x-rar-compressed',
		'rtf'			=> 'application/rtf',
		'swf'			=> 'application/x-shockwave-flash',
		'xls'			=> 'application/vnd.ms-excel',
		'xml'			=> 'application/xml',
		'zip'			=> 'application/zip',
		'torrent'		=> 'application/x-bittorrent',
	),
	
	'application/postscript' => 'ai, eps, ps',
	'application/vnd.ms-cab-compressed' => 'cab',
	'application/msword' => 'doc',
	'application/x-msdownload' => 'exe, msi',
	'application/javascript' => 'js',
	'application/pdf' => 'pdf',
	'application/vnd.ms-powerpoint' => 'ppt',
	'application/x-rar-compressed' => 'rar',
	'application/rtf' => 'rtf',
	'application/x-shockwave-flash' => 'swf',
	'application/vnd.ms-excel' => 'xls',
	'application/xml' => 'xml',
	'application/zip' => 'zip',
	'application/x-bittorrent' => 'torrent',
  
	'mime_image_type' => array(
		'bmp'				=> 'image/bmp',
		'gif'				=> 'image/gif',
		'png'				=> 'image/png',
		'psd'				=> 'image/vnd.adobe.photoshop',
	#	'ico'				=> 'image/vnd.microsoft.icon',
		'jpeg, jpg'			=> 'image/jpeg', # jpg,
		'tiff, tif'			=> 'image/tiff',
	#	'svg, svgz'			=> 'image/svg+xml',
	),
	
	'image/bmp' => 'bmp',
	'image/gif' => 'gif',
	'image/png' => 'png',
	'image/vnd.adobe.photoshop' => 'psd',
	'image/jpeg' => 'jpeg, jpg',
	'image/tiff' => 'tiff, tif',
	
	'mime_text_type' => array(
		'css'				=> 'text/css',
		'htm, html, php'	=> 'text/html',
		'txt'				=> 'text/plain',
	),
	
	'text/css' => 'css',
	'text/html' => 'htm, html, php',
	'text/plain' => 'txt',
	
	'mime_video_type' => array(
		'mp3'		=> 'audio/mpeg',
		'flv'		=> 'video/x-flv',
		'qt, mov'	=> 'video/quicktime',
	),
	
	'audio/mpeg' => 'mp3',
	'video/x-flv' => 'flv',
	'video/quicktime' => 'qt, mov',
	
	'a:1:{i:0;s:0:"";}' => 'Alle Daten',
	
	'up_filesize'	=> 'Die Dateigröße muss kleiner als %d KB sein.',
	'up_filetype'	=> 'Die Dateitype entspricht nicht den vorgaben: %s.',

	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Datei'),
	
	'type_0' => 'Kategorie',
	'type_1' => 'Datei',
));

?>