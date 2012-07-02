<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'match'		=> 'Begegnung',
	'explain'	=> 'Begegnung Details',
	
	'upcoming'	=> 'Anstehende Begegnung',
	'expired'	=> 'Abgelaufen Begegnung',

	'create'		=> 'Neues Begegnung hinzugefügt.',
	'update'		=> 'Begegnungsdaten erfolgreich geändert.',
	'delete'		=> 'Die Begegnung wurde gelöscht!',
	'confirm'		=> 'dass diese Begegnung:',
	'create_map'	=> 'Neue Map hinzugefügt.',
	'update_map'	=> 'Mapinfos erfolgreich geändert.',
	'delete_map'	=> 'Die Map wurde aus der Begegnung gelöscht!',	
	'create_user'	=> 'Neuen Benutzer zum Lineup hinzugefügt.',
	'update_user'	=> 'Lineup erfolgreich geändert.',
	'delete_user'	=> 'Der Benutzer wurden aus dem Lineup gelöscht!',
	
	'head_details'	=> 'Matchdeatials',
	'head_standard'	=> 'Standard Infos',
	'head_rival'	=> 'Gegner Infos',
	'head_server'	=> 'Server Infos',
	'head_message'	=> 'Kommentar / Report',
	'head_training'	=> 'Training',
	
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
	'team'				=> 'Team',
	'type'				=> 'Typ',
	'war'				=> 'Begegnungstyp',
	'league'			=> 'Liga',
	'match_league_url'	=> 'Ligawebsite',
	'league_match'		=> 'Match-ID',
	'rival_name'		=> 'Gegner Name',
	'rival_tag'			=> 'Gegner Clantag',
	'rival_url'			=> 'Gegner Homepage',
	'rival_logo'		=> 'Gegner Logo',
	'rival_lineup'		=> 'Gegner Lineup',
	'rival_lineup_exp'	=> 'Gegner ohne Clantag mit Komma getrennt aufschreiben.',
	'server_ip'			=> 'Server IP',
	'server_pw'			=> 'Server Passwort',
	'hltv_ip'			=> 'HLTV-Server IP',
	'hltv_pw'			=> 'HLTV-Server Passwort',
	
	'comment'		=> 'Kommentar',
	'comment_exp'	=> 'Öffentlicher Kommentar oder Zusatzinfos zum Match.',
	'report'		=> 'Report',
	'report_exp'	=> 'Text der nur für Interne Zwecke verwendet werden sollte.',
	
	'training'		=> 'Training',
	'train_date'	=> 'Trainingstermin',
	'train_maps'	=> 'Trainingsmaps',
	'train_text'	=> 'Trainingsbericht',
	
	'reset_list'	=> 'Teilnahmen reset?',
	
	'training_create'	=> 'hinzufügen',
	'training_update'	=> 'Trainingsliste',
	
	
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
	
	
	/* error msg */
	'msg_select_team'			=> 'Bitte ein Team auswählen!',
	'msg_select_type'			=> 'Bitte ein Type auswählen!',
	'msg_select_war'			=> 'Bitte ein Wartype auswählen!',
	'msg_select_league'			=> 'Bitte eine Liga auswählen!',
	
	'msg_select_team_first'		=> 'Bitte ein Team zuerst auswählen!',
	
	'msg_empty_rival_name'		=> 'Bitte einen Gegnernamen eintragen!',
	'msg_empty_rival_tag'		=> 'Bitte einen Gegnerclantag eintragen!',
	'msg_empty_server'			=> 'Bitte einen Gameserver eintragen!',
	
	/* details maps */
	'detail_maps'			=> 'Maps hinzufügen',
	'detail_maps_pic'		=> 'Maps mit Bildern hinzufügen',
	'detail_maps_overview'	=> 'Maps Übersicht',
	
	'detail_map'			=> 'Map',
	'detail_points'			=> 'Punkte',
	'detail_mappic'			=> 'Bild',

	
));

$lang = array_merge($lang, array(
	'match_type' => array(
		'type_unknown'	=> $lang['type_unknown'],
		'type_two'		=> $lang['type_two'],
		'type_three'	=> $lang['type_three'],
		'type_four'		=> $lang['type_four'],
		'type_five'		=> $lang['type_five'],
		'type_six'		=> $lang['type_six'],
	),
));

$lang = array_merge($lang, array(
	'match_war' => array(
		'war_fun'		=> 'Fun War',
		'war_training'	=> 'Training War',
		'war_clan'		=> 'Clan War',
		'war_league'	=> 'Liga War',
	),
));

$lang = array_merge($lang, array(
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