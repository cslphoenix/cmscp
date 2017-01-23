<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'match_type' => array(
		'type_unknown'	=> 'unbekannt',
		'type_two'		=> '2on2',
		'type_three'	=> '3on3',
		'type_four'		=> '4on4',
		'type_five'		=> '5on5',
		'type_six'		=> '6on6',
	),

	'match_war' => array(
		'war_fun'		=> 'Fun War',
		'war_training'	=> 'Training War',
		'war_clan'		=> 'Clan War',
		'war_league'	=> 'Liga War',
	),

	'match_league' => array(
		'league_nope'	=> array('name' => 'keine Liga', 'url' => ''),
		'league_esl'	=> array('name' => 'ESL', 'url' => 'http://www.esl.eu/'),
		'league_sk'		=> array('name' => 'Stammkneipe', 'url' => 'http://www.stammkneipe.eu/'),
		'league_liga'	=> array('name' => '0815 Liga', 'url' => 'http://www.0815liga.eu/'),
		'league_lgz'	=> array('name' => 'Leaguez', 'url' => 'http://www.lgz.eu/'),
		'league_te'		=> array('name' => 'TE', 'url' => 'http://www.tactical-esports.de/'),
		'league_xgc'	=> array('name' => 'XGC', 'url' => 'http://www.xgc-online.de/'),
		'league_ncsl'	=> array('name' => 'NCSL', 'url' => 'http://www.ncsl.de/'),
	),
	
	
	
));

$lang['hlsw']		= 'HLSW';
$lang['hltv']		= 'HLTV';

?>