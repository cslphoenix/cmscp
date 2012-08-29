<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Berechtigung',
	'explain'	=> 'Berechtigungen für Benutzer und Benutzergruppen.',
	
	'right_user'	=> 'Benutzer - Berechtigung',
	'right_mod'		=> 'Moderatoren - Berechtigung',
	'right_admin'	=> 'Administrator - Berechtigung',
	
	'forms_noneset' => 'keine Standartwerte zugewiesen',
	
	'forum_access_no'				=> 'Kein Zugang',
	'forum_access_only_read'		=> 'Nur lesender Zugriff',
	'forum_access_default'			=> 'Standard-Zugang',
	'forum_access_default_polls'	=> 'Standard-Zugang + Umfragen',
	'forum_access_full'				=> 'Voller Zugang',

));

$lang = array_merge($lang, array(
	
	'right_select' => array(
		'user'	=> $lang['right_user'],
		'mod'	=> $lang['right_mod'],
		'admin'	=> $lang['right_admin'],
	),

));

?>