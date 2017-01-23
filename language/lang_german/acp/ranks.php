<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Rang',
	'explain'	=> 'Verwalten von Rängen.',

	'rank_name'		=> 'Rangname',
	'rank_image'	=> 'Rangbild',
	'rank_type'		=> 'Rangtype',
	
	'rank_min'		=> 'Beiträge',
	'rank_special'	=> 'Spezial Rang',	
	'rank_standard'	=> 'Standardrang',

#	'type_1'	=> 'Seitenrang',
#	'type_2'	=> 'Forumrang',
#	'type_3'	=> 'Teamrang',
	
#	'rank_1'	=> 'Rang auf der Seite',
#	'rank_2'	=> 'Rang im Forum',
#	'rank_3'	=> 'Rang im Team',

	'ranks'		=> 'Ränge',
	
	'overview'	=> 'Übersicht Ränge',	
	'forum'		=> 'Foren-Ränge',
	'page'		=> 'Seiten-Ränge',
	'team'		=> 'Team-Ränge',
	
	

));

$lang = array_merge($lang, array(

	'radio:ranks' => array(
		RANK_FORUM	=> $lang['forum'],
		RANK_PAGE	=> $lang['page'],
		RANK_TEAM	=> $lang['team'],
	),
	
	'radio:yesno2' => array(
		4 => $lang['com_yes'],
		5 => $lang['com_no']
	),
	
));

?>