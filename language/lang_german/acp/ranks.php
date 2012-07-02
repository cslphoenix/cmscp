<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Rang',
	'explain'	=> 'Verwalten von Rängen.',

	'create'	=> 'Neuer Rang hinzugefügt.',
	'update'	=> 'Rangdaten erfolgreich geändert.',
	'delete'	=> 'Der Rang wurde gelöscht!',
	'confirm'	=> 'dass dieser Rang:',
	
	'rank_page'		=> 'Seitenrang',
	'rank_forum'	=> 'Forumrang',
	'rank_team'		=> 'Teamrang',
	'rank_special'	=> 'Spezial Rang',
	'rank_min'		=> 'Beiträge',
	'rank_image'	=> 'Rangbild',
	'rank_type'		=> 'Rangtype',
	'rank_standard'	=> 'Standardrang',
	
	'radio:ranks'	=> array(RANK_FORUM => 'Forumrang', RANK_PAGE => 'Seitenrang', RANK_TEAM => 'Teamrang'),

));

?>