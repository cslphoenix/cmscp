<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel � 2009
 *	- @code:	Sebastian Frickel � 2009
 *
 *	Teams
 *
 */

if ( !defined('IN_CMS') )
{
	exit;
}

$lang['match']					= 'Begegnung';
$lang['match_details']			= 'Begegnung Details';
$lang['match_explain']			= 'Hier k�nnen Wars verwaltet werden.';
$lang['match_details_explain']	= 'Hier k�nnen die Details f�r die Begegnung eingetragen und ver�ndert werden.';

$lang['match_upcoming']			= 'Anstehende Begegnung';
$lang['match_expired']			= 'Abgelaufen Begegnung';
$lang['match_maps']				= 'Matchbilder Upload';
$lang['match_infos']			= 'Matchinfos';

$lang['info_standard']			= 'Standard Infos';
$lang['info_rival']				= 'Gegner Infos';
$lang['info_server']			= 'Server Infos';
$lang['info_message']			= 'Bericht / Kommentar';
$lang['info_training']			= 'Training Infos';
$lang['info_team']				= 'Team';
$lang['info_type']				= 'XonX';
$lang['info_categorie']			= 'Match Art';
$lang['info_league']			= 'Liga';
$lang['info_league_url']		= 'Liga Website';
$lang['info_league_match']		= 'Liga Matchlink';
$lang['info_date']				= 'Match Datum';
$lang['info_public']			= 'Match �ffentlich?';
$lang['info_rival']				= 'Gegner Name';
$lang['info_rival_tag']			= 'Gegner Clantag';
$lang['info_rival_url']			= 'Gegner Homepage';
$lang['info_rival_lineup']		= 'Gegner Lineup';
$lang['info_server']			= 'Server';
$lang['info_server_pw']			= 'Server PW';
$lang['info_hltv']				= 'HLTV-Server';
$lang['info_hltv_pw']			= 'HLTV-Server PW';

$lang['training_date']			= 'Trainingstermin';
$lang['training_maps']			= 'Trainingsmaps';
$lang['training_text']			= 'Trainingsbericht';


$lang['match_training_create']	= 'hinzuf�gen';
$lang['match_training_update']	= 'Trainingsliste';



#	Match	Text

$lang['match_text']				= 'Match Bericht';
$lang['match_interest_reset']	= 'Teilnahme zur�cksetzen?';



$lang['match_details_comment']	= 'Match Kommentar';

$lang['match_lineup_add_yes']	= 'Spieler hinzugef�gt';
$lang['match_lineup_del_yes']	= 'Spieler gel�scht';




$lang['match_lineup_no_users']	= 'Bitte Spieler ausw�hlen die noch nicht eingetragen sind.';
$lang['match_lineup_change']	= 'Spielerliste ver�ndert';

$lang['select_team']		= 'Team ausw�hlen';





$lang['select_categorie']	= 'Matchtyp ausw�hlen';
$lang['select_categorie1']	= 'Fun War';
$lang['select_categorie2']	= 'Clan War';
$lang['select_categorie3']	= 'Liga War';
$lang['select_categorie4']	= 'Train War';

$lang['select_type']		= 'XonX ausw�hlen';
$lang['select_type1']		= 'Unbekannt';
$lang['select_type2']		= '2on2';
$lang['select_type3']		= '3on3';
$lang['select_type4']		= '4on4';
$lang['select_type5']		= '5on5';
$lang['select_type6']		= '6on6';

$lang['select_league']		= 'Liga ausw�hlen';
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


#	Matchdetails - �berschriften

$lang['match_details_maps']		= 'Karten Details';
$lang['match_details_upload']	= 'Karten Upload';



#	Matchdetails

$lang['details_maps']				= 'Maps hinzuf�gen';
$lang['details_maps_pic']			= 'Maps mit Bildern hinzuf�gen';
$lang['details_maps_overview']		= 'Maps �bersicht';

$lang['details_lineup']				= 'Clan Lineup';
$lang['details_lineup_player']		= 'Spieler f�r das Lineup';
$lang['details_lineup_status']		= 'Spielerstatus';
$lang['details_lineup_add']			= 'Spieler hinzuf�gen';
$lang['details_lineup_add_explain']	= 'Spieler einfach mit gedr�ckter STRG Taste ausw�hlen und Absenden.';

$lang['details_status_set']			= 'Status %s setzen';
$lang['details_status_player']		= 'Spieler';
$lang['details_status_replace']		= 'Ersatz';

$lang['details_map']				= 'Karte';
$lang['details_mappic']				= 'Bild';
$lang['details_points']				= 'Punkte';

?>