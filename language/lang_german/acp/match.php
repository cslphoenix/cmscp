<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Begegnung',
	'explain'	=> 'Begegnung Details',
	
	'upcoming'	=> 'Anstehende Begegnung',
	'expired'	=> 'Abgelaufen Begegnung',

	'input_standard'	=> 'Standard',
	'input_rival'		=> 'Gegner',
	'input_server'		=> 'Server',
	'input_message'		=> 'Kommentar & Report',
	'input_training'	=> 'Training',
	
	'head_details'		=> 'Matchdeatials',
	
	'type_unknown'	=> 'unbekannt',
	'type_two'		=> '2on2',
	'type_three'	=> '3on3',
	'type_four'		=> '4on4',
	'type_five'		=> '5on5',
	'type_six'		=> '6on6',
	
	'msg_select_mtype'		=> 'Bitte Typ auswählen',
	'msg_select_mwar'		=> 'Bitte Wartype auswählen',
	'msg_select_mleague'	=> 'Bitte Liga auswählen',
	
	
/*
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
		'league_nope'	=> array('name' => 'keine Liga', 'url' => 'leer'),
		'league_esl'	=> array('name' => 'ESL', 'url' => 'http://www.esl.eu/'),
		'league_sk'		=> array('name' => 'Stammkneipe', 'url' => 'http://www.stammkneipe.eu/'),
		'league_liga'	=> array('name' => '0815 Liga', 'url' => 'http://www.0815liga.eu/'),
		'league_lgz'	=> array('name' => 'Leaguez', 'url' => 'http://www.lgz.eu/'),
		'league_te'		=> array('name' => 'TE', 'url' => 'http://www.tactical-esports.de/'),
		'league_xgc'	=> array('name' => 'XGC', 'url' => 'http://www.xgc-online.de/'),
		'league_ncsl'	=> array('name' => 'NCSL', 'url' => 'http://www.ncsl.de/'),
	),
*/
	'team_id'				=> 'Team',
	'match_type'			=> 'Typ',
	'match_war'				=> 'Begegnungstyp',
	'match_league'			=> 'Liga',
	'match_league_match'	=> 'Match-ID',
	'match_date'			=> 'Datum',
	'match_public'			=> 'Öffentlich',
	'match_comments'		=> 'Kommentare',
	'match_rival_name'		=> 'Gegner Name',
	'match_rival_tag'		=> 'Gegner Clantag',
	'match_rival_url'		=> 'Gegner Homepage',
	'match_rival_logo'		=> 'Gegner Logo',
	'match_rival_lineup'	=> 'Gegner Lineup',
	'match_server_ip'		=> 'Server IP',
	'match_server_pw'		=> 'Server Passwort',
	'match_hltv_ip'			=> 'HLTV-Server IP',
	'match_hltv_pw'			=> 'HLTV-Server Passwort',
	
	'match_comment'		=> 'Kommentar',
	'match_report'		=> 'Report',
	
	'training'			=> 'Training',
	'training_on'		=> 'Training planen',
	'training_date'		=> 'Datum',
	'training_duration'	=> 'Dauer',
	'training_maps'		=> 'Karte(n)',
	'training_text'		=> 'Zusatz',
	
	'reset_list'	=> 'Teilnahmen reset?',
	
	'training_create'	=> 'hinzufügen',
	'training_update'	=> 'Trainingsliste',
	
	'match_comment_exp'			=> 'Öffentlicher Kommentar oder Zusatzinfos zum Match.',
	'match_report_exp'			=> 'Text der nur für Interne Zwecke verwendet werden sollte.',
	'match_rival_lineup_exp'	=> 'Gegner ohne Clantag mit Komma getrennt aufschreiben.',

	
	
	/* Detail */
	/* Lineup */
	'lineup'			=> 'Clan Lineup',
	'lineup_rival'		=> 'Gegner Lineup',
	'lineup_status'		=> 'Spielerstatus',
	'lineup_add'		=> 'Spieler hinzufügen',
	'lineup_add_exp'	=> 'Spieler einfach mit gedrückter STRG Taste auswählen und Absenden.',
	
	'status_set'		=> 'Status %s setzen',
	'status_player'		=> 'Spieler',
	'status_replace'	=> 'Ersatz',
	
	'sprintf_round'		=> 'Runde: %s',
	
	
	/* details maps */
	'detail_maps'			=> 'Maps hinzufügen',
	'detail_maps_pic'		=> 'Maps mit Bildern hinzufügen',
	'detail_maps_overview'	=> 'Maps Übersicht',
	
	'detail_map'			=> 'Map',
	'detail_points'			=> 'Punkte',
	'detail_mappic'			=> 'Bild',
	
#	'match_type'	=> 'match_type',
#	'match_war'		=> 'match_war',
#	'match_league'	=> 'match_league',

	
));

$lang = array_merge($lang, array(
	
	'ary_match_type' => array(
		'type_unknown'	=> $lang['type_unknown'],
		'type_two'		=> $lang['type_two'],
		'type_three'	=> $lang['type_three'],
		'type_four'		=> $lang['type_four'],
		'type_five'		=> $lang['type_five'],
		'type_six'		=> $lang['type_six'],
	),
	
	'ary_match_war' => array(
		'war_fun'		=> 'Fun War',
		'war_training'	=> 'Training War',
		'war_clan'		=> 'Clan War',
		'war_league'	=> 'Liga War',
	),
	
	'ary_match_league' => array(
		'league_nope'	=> array('name' => 'keine Liga', 'url' => 'leer'),
		'league_esl'	=> array('name' => 'ESL', 'url' => 'http://www.esl.eu/'),
		'league_sk'		=> array('name' => 'Stammkneipe', 'url' => 'http://www.stammkneipe.eu/'),
		'league_liga'	=> array('name' => '0815 Liga', 'url' => 'http://www.0815liga.eu/'),
		'league_lgz'	=> array('name' => 'Leaguez', 'url' => 'http://www.lgz.eu/'),
		'league_te'		=> array('name' => 'TE', 'url' => 'http://www.tactical-esports.de/'),
		'league_xgc'	=> array('name' => 'XGC', 'url' => 'http://www.xgc-online.de/'),
		'league_ncsl'	=> array('name' => 'NCSL', 'url' => 'http://www.ncsl.de/'),
	),
));



$lang['no_users'] = 'Keine Mitglieder im Team!';
$lang['no_users_store'] = 'Keine Mitglieder gespeichert.';



/*
$lang['match']					= 'Begegnung';
$lang['match_details']			= 'Begegnung Details';
$lang['match_explain']			= 'Hier können Wars verwaltet werden.';
$lang['match_details_explain']	= 'Hier können die Details für die Begegnung eingetragen und verändert werden.';

$lang['match_upcoming']			= 'Anstehende Begegnung';
$lang['match_expired']			= 'Abgelaufen Begegnung';
$lang['match_maps']				= 'Matchbilder Upload';
$lang['match_infos']			= 'Matchinfos';

$lang['info_standard']			= 'Standard Infos';
$lang['info_rival']				= 'Gegner Infos';
$lang['info_server']			= 'Server Infos';
$lang['info_message']			= 'Bericht / Kommentar';
$lang['info_training']			= 'Training Infos';

$lang['info_team'] = 'Team';
$lang['info_type'] = 'XonX';
$lang['info_categorie'] = 'Match Art';
$lang['info_league'] = 'Liga';
$lang['info_league_url'] = 'Liga Website';
$lang['info_league_match'] = 'Liga Matchlink';
$lang['info_date'] = 'Match Datum';
$lang['info_public'] = 'Match Öffentlich?';
$lang['info_rival'] = 'Gegner Name';
$lang['info_rival_tag'] = 'Gegner Clantag';
$lang['info_rival_url'] = 'Gegner Homepage';
$lang['info_rival_lineup'] = 'Gegner Lineup';
$lang['info_server'] = 'Server';
$lang['info_server_pw'] = 'Server PW';
$lang['info_hltv'] = 'HLTV-Server';
$lang['info_hltv_pw'] = 'HLTV-Server PW';

$lang['training_date']			= 'Trainingstermin';
$lang['training_maps']			= 'Trainingsmaps';
$lang['training_text']			= 'Trainingsbericht';


$lang['match_training_create']	= 'hinzufügen';
$lang['match_training_update']	= 'Trainingsliste';



#	Match	Text

$lang['match_text']				= 'Match Bericht';




$lang['match_details_comment']	= 'Match Kommentar';

$lang['match_lineup_add_yes']	= 'Spieler hinzugefügt';
$lang['match_lineup_del_yes']	= 'Spieler gelöscht';




$lang['match_lineup_no_users']	= 'Bitte Spieler auswählen die noch nicht eingetragen sind.';
$lang['match_lineup_change']	= 'Spielerliste verändert';

$lang['select_team']		= 'Team auswählen';





$lang['select_categorie']	= 'Matchtyp auswählen';
$lang['select_categorie1']	= 'Fun War';
$lang['select_categorie2']	= 'Clan War';
$lang['select_categorie3']	= 'Liga War';
$lang['select_categorie4']	= 'Train War';

$lang['select_type']		= 'XonX auswählen';
$lang['select_type1']		= 'Unbekannt';
$lang['select_type2']		= '2on2';
$lang['select_type3']		= '3on3';
$lang['select_type4']		= '4on4';
$lang['select_type5']		= '5on5';
$lang['select_type6']		= '6on6';



$lang['select_league']		= 'Liga auswählen';
$lang['select_league1']		= 'ESL';
$lang['select_league2']		= 'Stammkneipe';
$lang['select_league3']		= '0815 Liga';
$lang['select_league4']		= 'Leaguez';
$lang['select_league5']		= 'TE';
$lang['select_league6']		= 'XGC';
$lang['select_league7']		= 'NCSL';
$lang['select_league8']		= 'andere / keine';
$lang['select_league1i']	= 'http://www.esl.eu/';
$lang['select_league2i']	= 'http://www.stammkneipe.de/';
$lang['select_league3i']	= 'http://www.0815liga.de/';
$lang['select_league4i']	= 'http://www.lgz.de/';
$lang['select_league5i']	= 'http://www.tactical-esports.de/';
$lang['select_league6i']	= 'http://www.xgc-online.de/';
$lang['select_league7i']	= 'http://www.ncsl.de/';


#	Matchdetails - Überschriften

$lang['match_details_maps']		= 'Karten Details';
$lang['match_details_upload']	= 'Karten Upload';



#	Matchdetails

$lang['details_maps']				= 'Maps hinzufügen';
$lang['details_maps_pic']			= 'Maps mit Bildern hinzufügen';
$lang['details_maps_overview']		= 'Maps Übersicht';




$lang['details_status_set']			= 'Status %s setzen';
$lang['details_status_player']		= 'Spieler';
$lang['details_status_replace']		= 'Ersatz';

$lang['details_map']				= 'Karte';
$lang['details_mappic']				= 'Bild';
$lang['details_points']				= 'Punkte';
*/
?>