<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'TITLE'			=> 'Begegnung',
	'EXPLAIN'		=> 'Begegnung Details',
	
	'INPUT_DATA'	=> 'Begegnungsdaten',
	
	'UPCOMING'	=> 'Anstehende Begegnung',
	'EXPIRED'	=> 'Abgelaufen Begegnung',

	'INPUT_STANDARD'	=> 'Standard',
	'INPUT_RIVAL'		=> 'Gegner',
	'INPUT_SERVER'		=> 'Server',
	'INPUT_MESSAGE'		=> 'Kommentar & Report',
	'INPUT_TRAINING'	=> 'Training',
	'INPUT_DETAILS'		=> 'Details',

#	'type_unknown'	=> 'unbekannt',
#	'type_two'		=> '2on2',
#	'type_three'	=> '3on3',
#	'type_four'		=> '4on4',
#	'type_five'		=> '5on5',
#	'type_six'		=> '6on6',
	
#	'msg_select_mtype'		=> 'Bitte Typ auswählen',
#	'msg_select_mwar'		=> 'Bitte Wartype auswählen',
#	'msg_select_mleague'	=> 'Bitte Liga auswählen',
	
#	'msg_select_member'		=> 'Bitte 1 oder mehrere Mitglieder auswählen!',
#	'msg_select_option'		=> 'Bitte eine <b>Option</b> auswählen!',

	'TEAM_ID'				=> 'Team',
	'MATCH_TYPE'			=> 'Typ',
	'MATCH_WAR'				=> 'Begegnungstyp',
	'MATCH_LEAGUE'			=> 'Liga',
	'MATCH_LEAGUE_MATCH'	=> 'Match-ID',
	'MATCH_DATE'			=> 'Datum',
	'MATCH_PUBLIC'			=> 'Öffentlich',
	'MATCH_COMMENTS'		=> 'Kommentare',
	'MATCH_RIVAL_NAME'		=> 'Gegner Name',
	'MATCH_RIVAL_TAG'		=> 'Gegner Clantag',
	'MATCH_RIVAL_URL'		=> 'Gegner Homepage',
	'MATCH_RIVAL_LOGO'		=> 'Gegner Logo',
	'MATCH_RIVAL_LINEUP'	=> 'Gegner Lineup',
	'MATCH_SERVER_IP'		=> 'Server IP',
	'MATCH_SERVER_PW'		=> 'Server Passwort',
	'MATCH_HLTV_IP'			=> 'HLTV-Server IP',
	'MATCH_HLTV_PW'			=> 'HLTV-Server Passwort',
	
	'MATCH_COMMENT'		=> 'Kommentar',
	'MATCH_REPORT'		=> 'Report',
	
	'TRAINING'			=> 'Training',
	'TRAINING_ON'		=> 'Training planen',
	
	'notice_select_type'		=> 'Bitte ein Type auswählen!',
	'notice_select_war'			=> 'Bitte ein Wartype auswählen!',
	'notice_select_league'		=> 'Bitte eine Liga auswählen!',

#	'training_date'		=> 'Datum',
#	'training_duration'	=> 'Dauer',
#	'training_maps'		=> 'Karte(n)',
#	'training_text'		=> 'Zusatz',
	
#	'reset_list'	=> 'Teilnahmen reset?',
	
#	'training_create'	=> 'hinzufügen',
#	'training_update'	=> 'Trainingsliste',
	
#	'match_comment_exp'			=> 'Öffentlicher Kommentar oder Zusatzinfos zum Match.',
#	'match_report_exp'			=> 'Text der nur für Interne Zwecke verwendet werden sollte.',
#	'match_rival_lineup_exp'	=> 'Gegner ohne Clantag mit Komma getrennt aufschreiben.',

	/* Detail */
	/* Lineup */
#	'lineup'			=> 'Clan Lineup',
#	'lineup_rival'		=> 'Gegner Lineup',
#	'lineup_status'		=> 'Spielerstatus',
#	'lineup_add'		=> 'Spieler hinzufügen',
#	'lineup_add_exp'	=> 'Spieler einfach mit gedrückter STRG Taste auswählen und Absenden.',
	
#	'status_set'		=> 'Status %s setzen',
#	'status_player'		=> 'Spieler',
#	'status_replace'	=> 'Ersatz',
	
#	'sprintf_round'		=> 'Runde: %s',
	
	
	/* details maps */
#	'detail_maps'			=> 'Maps hinzufügen',
#	'detail_maps_pic'		=> 'Maps mit Bildern hinzufügen',
#	'detail_maps_overview'	=> 'Maps Übersicht',
	
#	'detail_map'			=> 'Map',
#	'detail_points'			=> 'Punkte',
#	'detail_mappic'			=> 'Bild',
	
#	'match_type'	=> 'match_type',
#	'match_war'		=> 'match_war',
#	'match_league'	=> 'match_league',

#	'delete_user'	=> 'Benutzer gelöscht',
#	'user_create'	=> 'Benutzer hinzugefügt',
#	'user_update'	=> 'Benutzerstatus geändert',
#	'user_delete'	=> 'Benutzer gelöscht',
	
#	'rival_overview'	=> 'Gegner Übersicht',
	
#	'update_change'	=> 'Rechte wurden erfolgreich geändert.',
#	'update_delete'	=> 'Benutzer wurden erfolgreich gelöscht.',
#	'update_create'	=> 'Benutzer wurden erfolgreich hinzugefügt.',

	'UPCOMING'	=> 'Anstehende Begegnung',
	'EXPIRED'	=> 'Abgelaufen Begegnung',
	
	'RIVAL'		=> 'Gegner',
	
));

$lang = array_merge($lang, array(
	
#	'ary_match_type' => array(
#		'type_unknown'	=> $lang['type_unknown'],
#		'type_two'		=> $lang['type_two'],
#		'type_three'	=> $lang['type_three'],
#		'type_four'		=> $lang['type_four'],
#		'type_five'		=> $lang['type_five'],
#		'type_six'		=> $lang['type_six'],
#	),
	
#	'ary_match_war' => array(
#		'war_fun'		=> 'Fun War',
#		'war_training'	=> 'Training War',
#		'war_clan'		=> 'Clan War',
#		'war_league'	=> 'Liga War',
#	),
	
#	'ary_match_league' => array(
#		'league_nope'	=> array('name' => 'keine Liga', 'url' => 'leer'),
#		'league_esl'	=> array('name' => 'ESL', 'url' => 'http://www.esl.eu/'),
#		'league_sk'		=> array('name' => 'Stammkneipe', 'url' => 'http://www.stammkneipe.eu/'),
#		'league_liga'	=> array('name' => '0815 Liga', 'url' => 'http://www.0815liga.eu/'),
#		'league_lgz'	=> array('name' => 'Leaguez', 'url' => 'http://www.lgz.eu/'),
#		'league_te'		=> array('name' => 'TE', 'url' => 'http://www.tactical-esports.de/'),
#		'league_xgc'	=> array('name' => 'XGC', 'url' => 'http://www.xgc-online.de/'),
#		'league_ncsl'	=> array('name' => 'NCSL', 'url' => 'http://www.ncsl.de/'),
#	),
));



#$lang['no_users'] = 'Keine Mitglieder im Team!';
#$lang['no_users_store'] = 'Keine Mitglieder gespeichert.';



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