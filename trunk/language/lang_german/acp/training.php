<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	'training'	=> 'Training',
	'explain'	=> 'Hier kannst du Trainings der Teams Verwalten.',

	'create'	=> 'Neues Training hinzugefügt.',
	'update'	=> 'Trainingsdaten erfolgreich geändert-',
	'delete'	=> 'Das Training wurde gelöscht!',
	'confirm'	=> 'dass das Training gegen:',
	
	'return'	=> '<br /><br /><strong>%s&laquo; Trainings Administration%s</strong>',

	'upcoming'	=> 'Anstehende Trainings',
	'expired'	=> 'Abgelaufen Trainings',
	
	'vs'		=> 'Training gegen',
	'date'		=> 'Trainingstermin',
	
));

$lang['training_vs']		= 'Training gegen';
$lang['training_date']			= 'Trainingstermin';
$lang['training_maps']			= 'Trainingsmaps';
$lang['training_text']			= 'Trainingsbericht';

?>