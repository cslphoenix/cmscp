<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'TITLE'		=> 'Rang',
	'EXPLAIN'	=> 'Verwalten von Rängen.',
	
	'INPUT_DATA'	=> 'Rangdaten',

	'RANK_NAME'		=> 'Rangname',
	'RANK_IMAGE'	=> 'Rangbild',
	'RANK_TYPE'		=> 'Rangtype',
	
	'RANK_MIN'		=> 'Beiträge',
	'RANK_SPECIAL'	=> 'Spezial Rang',	
	'RANK_STANDARD'	=> 'Standardrang',

	'RANKS'		=> 'Ränge',
	
	'OVERVIEW'	=> 'Übersicht Ränge',	
	'FORUM'		=> 'Foren-Ränge',
	'PAGE'		=> 'Seiten-Ränge',
	'TEAM'		=> 'Team-Ränge',
));

$lang = array_merge($lang, array(
	'radio:ranks' => array(
		RANK_FORUM	=> $lang['FORUM'],
		RANK_PAGE	=> $lang['PAGE'],
		RANK_TEAM	=> $lang['TEAM'],
	),
	'radio:special' => array(
		4 => $lang['COMMON_YES'],
		5 => $lang['COMMON_NO']
	),
));

?>