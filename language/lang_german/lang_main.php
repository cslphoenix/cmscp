<?php

include('lang_head.php');
include('lang_control.php');
include('lang_teamspeak.php');
include('lang_server.php');

$lang = array_merge($lang, array(

	'update_training_status_add'	=> 'Training-Status hinzugefügt',
	'update_training_status_edit'	=> 'Training-Status geändert',


	'NOTICE_SELECT_PERMISSION'	=> 'Rechte geben/nehmen',
	

	'header_empty_news'			=> 'keine News vorhanden',
	'header_empty_match'		=> 'keine Begnungen vorhanden',
	'header_empty_topics'		=> 'keine Themen vorhanden',
	'header_empty_downloads'	=> 'keine Downloads vorhanden',
	
	'MARK_ALL'			=> 'Alles markieren',
	'MARK_DEALL'		=> 'Alles abwählen',
	

	'cur_max'	=> '%s / %s',
	'ip_port'	=> '%s:%s',

	'common_entry_empty'	=> 'Keine Eintr&auml;ge vorhanden.',

	'NAVI_MAIN'	=> 'Main',
	'NAVI_CLAN'	=> 'Clan',
	'NAVI_COM'	=> 'Community',
	'NAVI_MISC'	=> 'Misc',
	'NAVI_USER'	=> 'Benutzer',
	
	'UCP_LOBBY'	=> 'Lobby',
	
	'link'		=> 'Link',
	'partner'	=> 'Partner',
	'sponsor'	=> 'Sponsor',
	
	'page'	=> 'Seite',
	'forum'	=> 'Forum',
	'team'	=> 'Team',
	
	'maps'	=> 'Karten',
	
	'match_update'	=> 'bearbeiten',
	'match_detail'	=> 'Details bearbeiten',
	
	'lineup_player'		=> 'Spieler: %s',
	'lineup_players'	=> 'Spieler: %s<br />Ersatz: %s',
	
	'msg_change_status_none'	=> 'Status nicht verändert.',
	'msg_change_status_create'	=> 'Status hinzugefügt.',
	'msg_change_status_update'	=> 'Status geändert.',
	
	'msg_empty_nick'	=> 'Bitte ein Nickname eintragen!',
	'msg_empty_mail'	=> 'Bitte ein Mail eintragen!',
	'msg_empty_text'	=> 'Bitte ein Nachricht eintragen!',
	'msg_empty_captcha'	=> 'Bitte ein Captcha richtig eintragen!',
	
	'post_normal'	=> 'Normal',
	'post_sticky'	=> 'Wichtig',
	'post_announce'	=> 'Ankündigung',
	'post_global'	=> 'Globaleankündigung',
	
	'msg_empty_subject'	=> 'Bitte Thementitel eintragen!',
	'msg_empty_message'	=> 'Bitte Themennachricht eintragen!',
	
	'newtopic'	=> 'Thema erstellt',

    'sort_all'			=> 'alle',
    'sort_others'		=> 'andere',
	'sort_by_letter'	=> 'Nur Benutzernamen beginnend mit',
	
	'sprintf_empty_space'		=> '%s %s',
	'sprintf_empty_line'		=> '%s - %s',
	'sprintf_empty_points'		=> ' :: %s',
	
	'a_txt'			=> '<a href="%s" title="%s">%s</a>',
	'a_txt_b'		=> '<a href="%s" title="%s"><b>%s</b></a>',
	
	'AHREF_TXT'		=> '<a href="%s" title="%s">%s</a>',
	'AHREF_TXT_B'	=> '<a href="%s" title="%s"><b>%s</b></a>',
	
	'a_new'			=> '<a href="%s" target="_blank">%s</a>',
	'a_css'			=> '<a href="%s" class="%s">%s</a>',
	'a_style'		=> '<a href="%s" style="color:%s">%s</a>',
	'a_user'		=> '<a href="%s" style="color:%s">%s</a>',
	'a_txt_ly'		=> '<a href="%s" title="%s">%s</a> :: %s',
	'a_img'			=> '<a href="%s"><img class="icon" src="%s" title="%s" alt="" /></a>',
	
	'ahref_style'	=> '<a href="%s" style="color:%s">%s</a>',
	
	'i_icon'		=> '<img class="icon" src="%s" title="%s" alt="" />',
	'i_iconn'		=> '<img src="%s" title="%s" alt="" />',
	
	'upcoming'	=> 'Aktuelle',
	'expired'	=> 'Abgelaufen',
	
	
	'unread_txt'	=> 'als gelesen markieren',
	'unread_all'	=> 'Alles als gelesen markiert!',
	'unread_news'	=> 'Alle News als gelesen markiert!',
	'unread_event'	=> 'Alle Ereignisse als gelesen markiert!',
	'unread_match'	=> 'Alle Begegnungen als gelesen markiert!',
	'unread_train'	=> 'Alle Trainings als gelesen markiert!',
	
	'main_ucp'		=> 'Benutzer-Center',
	
	
	'CAL_TODAY'		=> 'Heute',
	'CAL_NEWS'		=> 'News',
	
	'CAL_NYEAR'			=> 'Neujahr',
	'CAL_KINGS'			=> 'Heilige Drei Könige',
	'CAL_FRIDAY'		=> 'Karfreitag',
	'CAL_EASTERO'		=> 'Ostersonntag',
	'CAL_EASTERM'		=> 'Ostermontag',
	'CAL_MAY'			=> 'Erster Mai',
	'CAL_ASCENSION'		=> 'Christi Himmelfahrt',
	'CAL_WHITS'			=> 'Pfingstsonntag',
	'CAL_WHITM'			=> 'Pfingstmontag',
	'CAL_CORPUS'		=> 'Fronleichnam',
	'CAL_ASS_UMPTION'	=> 'Mariä Himmelfahrt',
	'CAL_DOGU'			=> 'Tag der deutschen Einheit',
	'CAL_RDAY'			=> 'Reformationstag',
	'CAL_ASDAY'			=> 'Allerheiligen',
	'CAL_DAYOPAR'		=> 'Buß- und Bettag',
	'CAL_XMAS'			=> 'Weihnachten',
	'CAL_XMAS1'			=> '1. Weihnachtsfeiertag',
	'CAL_XMAS2'			=> '2. Weihnachtsfeiertag',
	'CAL_NEWYEAR'		=> 'Silvester',
	
	
	'CAL_EVENT'			=> 'Ereignis',
	'CAL_EVENTS'		=> 'Ereignisse',
	
	'CAL_DAY_WHOLE'		=> 'am gesamten Tag',
	'CAL_DAY_FROMTO'	=> 'von %s bis %s',
	
	'SPRINTF_WHOLE'		=> 'am gesamten Tag: %s',	# event
	'SPRINTF_EVENT'		=> 'am: %s von %s - %s',	# event
	
	'CAL_BIRTHDAY'	=> 'Geburtstag',
	'CAL_BIRTHDAYS'	=> 'Geburtstage',
	'CAL_AGE'		=> '%s wird %s Jahre alt',
	
	'CAL_TRAINING'	=> 'Training',
	'CAL_TRAININGS'	=> 'Trainings',
	'CAL_MATCH'		=> 'War',
	'CAL_MATCHS'	=> 'Wars',
	
	'stf_cal_today'	=> '<b>%s</b><br/>',
	
	
	'common_entry_new'		=> 'Neuer Eintrag',
	'common_add'			=> 'Hinzufügen',
	'COMMON_AUTH'			=> 'Berechtigung',
	'common_comment'		=> 'Kommentar',
	'common_comments_pub'	=> 'Kommentare erlauben',
	'common_input_data'		=> 'Daten eingeben',
	'common_input_option'	=> 'Option',
	'common_input_upload'	=> 'Upload von Dateien oder Bildern',
	'common_input_standard'	=> 'Standard',
	'common_default'		=> 'Standarteinstellungen',
	'COMMON_DELETE'			=> 'Lüschen',
	'common_delete_all'		=> 'Alles löschen',
	'common_login'			=> 'Login',
	'common_login_acp'		=> 'Adminlogin',
	'common_desc'			=> 'Beschreibung',
	'common_details'		=> 'Details',
	'common_image'			=> 'Bild',
	'common_member'			=> 'Mitglied',
	'common_members'		=> 'Mitglieder',
	'common_message'		=> 'Nachricht',
	'common_moderator'		=> 'Moderator',		
	'COMMON_MODERATORS'		=> 'Moderatoren',		
	'common_member_empty'	=> 'Keine Mitglieder eingetragen/vorhanden.',
	'common_moderator_empty'=> 'Keine Moderator eingetragen/vorhanden.',
	'COMMON_SELECT_OPTION'		=> 'Option wählen',			
	'COMMON_OVERVIEW'		=> '&Uuml;bersicht as',
	'common_reset'			=> 'Zurücksetzen',
	'common_setting'		=> 'Einstellung',
	'common_settings'		=> 'Einstellungen',		
	'common_submit'			=> 'Absenden',
	'common_upload'			=> 'Upload',
	'COMMON_UPDATE'			=> 'Bearbeiten',
	'common_create'			=> 'Hinzufügen',
	'common_order'			=> 'Ordnen',
	'common_sort'			=> 'Sortieren',
	'common_public'			=> '�ffentlich',
	'common_page_of'		=> 'Seite <b>%d</b> von <b>%d</b>',
	'common_on'				=> 'Aktiv',
	'common_off'			=> 'Inaktiv',
	
	'COMMON_LOG_UPDATE'		=> 'bearbeitet',
	

	'COMMON_YES'	=> 'Ja',
	'COMMON_NO'		=> 'Nein',
	'COMMON_NEVER'	=> 'Nie',
	
	'COMMON_ALL'	=> 'Alle',
	
	'SORT_BY_TIME'	=> array(
		'SORT_ALL'		=> 'Alle Einträge',
		'SORT_DAY'		=> '1 Tag',
		'SORT_WEEK'		=> 'Woche',
		'SORT_WEEKS'	=> '2 Wochen',
		'SORT_MONTH'	=> 'Monat',
		'SORT_YEAR'		=> 'Jahr',
	),
	
	/* 01.08 */
	'common_rating'		=> 'Abstimmungen',

	
	'add_comment'			=> 'Kommentar hinzugefügt.',
	'click_return_match'	=> '%sHier klicken%s, um zum Match zurückzukehren.',
	'click_return_training'	=> '%sHier klicken%s, um zum Training zurückzukehren.',
	'click_return_news'		=> '%sHier klicken%s, um zu den News zurückzukehren.',
	
	'RETURN'			=> '%s<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a></strong>',
	
));

$lang = array_merge($lang, array(
	'language' => array(
		'german' => 'Deutsch',
	),
));

/* server error */
$lang = array_merge($lang, array(
	'unknown_error'	=> 'Unknown Error',
	'code_404'	=> '404 - Not Found',
	'desc_404'	=> 'The page you are trying to reach does not exist on this server.<br>You can browse the forum by following one of the links above.<br><br>We apologise for the inconvenience.',
	'code_401'	=> '401 - Incorrect Password',
	'desc_401'	=> 'The user_name or password you supplied is not correct.<br>You can browse the forum by following one of the links above.',
	'code_403'	=> '403 - Access Denied',
	'desc_403'	=> 'You don\'t have the proper rights to view this page.<br>You can browse the forum by following one of the links above.',
	'code_500'	=> '500 - Internal Server Error',
	'desc_500'	=> 'An Internal Server Error occurred.<br>If reloading the page doens\'t help, please contact the system administrator.',
));

/* calendar */
$lang = array_merge($lang, array(
	'cal_weekdays' => array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'),
));

$lang['msg_match_fail'] = 'Match nicht vorhanden oder Match nicht freigegeben!';
$lang['at_today'] = "Heute %s"; // %s is the time
$lang['at_yesterday'] = "Gestern %s"; // %s is the time
$lang['at_tomorrow'] = "Morgen %s"; // %s is the time

$lang['news'] = 'News';
$lang['lobby_news'] = 'News';

$lang['comment']	= '%s neuer Kommentar';
$lang['comments']	= '%s neue Kommentare';

$lang['sprintf_comment']	= '1 Kommentar';
$lang['sprintf_comments']	= '%s Kommentare';
$lang['sprintf_comment_no']	= 'keine Kommentare';

/* event */
$lang = array_merge($lang, array(

	'STF_SELECT_FORMAT'		=> '&raquo;&nbsp;%s&nbsp;',
	'STF_SELECT_FORMAT2'	=> '&raquo;&nbsp;%s&nbsp;&nbsp;\'%s\'', /* acp_selects, s_gameq() */
	

	'EVENT_UPCOMING'	=> 'Ereignis: Aktuelle',
	'EVENT_EXPIRED'		=> 'Ereignis: Abgelaufen',

	'sprintf_event'	=> 'am: %s von %s - %s',
	
	'join_not'	=> 'keine Teilnahme',
	'join_yes'	=> 'Teilnahme',
	'join_none'	=> 'Unbekannt',
));

$lang = array_merge($lang, array(
	'header_sprintf'	=> '%s: %s',
	'update_sprintf'	=> '%s bearbeiten',
	
	'header_overview'		=> 'Übersicht',
	'header_news'			=> 'News',
	'header_news_archiv'	=> 'News: Archiv',
	'header_event'			=> 'Ereignis',
	'header_match'			=> 'Begegnung',
	'header_calendar'		=> 'Kalender',
	'header_group'			=> 'Benutzergruppe',
	
));

/* subtitle */
$lang = array_merge($lang, array(

	'navi_main'			=> 'Main',
	'navi_clan'			=> 'Clan',
	'navi_com'			=> 'Community',
	'navi_misc'			=> 'Misc',
	'navi_user'			=> 'Benutzer',

	'sn_news'			=> 'Letzte Nachrichten',
	'sn_match'			=> 'Letzte Begegnungen',

	'sn_topics'			=> 'Letzte Forumthemen',
	'sn_downloads'		=> 'Letzte Downloads',
	'sn_newest_users'	=> '%s neuesten Mitglieder',
	'sn_teams'			=> 'Teams',
	'sn_link'			=> 'Links',
	'sn_partner'		=> 'Partner',
	'sn_sponsor'		=> 'Sponsor',
	'sn_statsonline'	=> 'Stats Online',
	'sn_statscounter'	=> 'Stats Counter',
	'sn_minical'		=> 'Kalender',
	'sn_server'			=> 'Server',
	'sn_nextmatch'		=> 'next War',
	'sn_nexttraining'	=> 'next Train',
	
#	/* subtitle server */
#	'server_status'		=> 'Status:',
#	'server_map'		=> 'Karte:',
#	'server_online'		=> '<font color="#green">Online</font>',
#	'server_offline'	=> '<font color="#red">Offline</font>',
#	'server_player'		=> 'Spieler:',
#	'server_user'		=> 'Benutzer:',
));

$lang = array_merge($lang, array(
	'map_bf3' => array(
		'MP_001'	=> 'Grand Bazaar',
		'MP_003'	=> 'Tehran Highway',
		'MP_007'	=> 'Caspian Border',
		'MP_011'	=> 'Seine Crossing',
		'MP_012'	=> 'Operation Firestorm',
		'MP_013'	=> 'Damavand Peak',
		'MP_017'	=> 'Noshahar Canals',
		'MP_018'	=> 'Kharg Island',
		'MP_Subway'	=> 'Operation Metro',
		'XP1_001'	=> 'Strike at Karkand',
		'XP1_002'	=> 'Gulf of Oman',
		'XP1_003'	=> 'Sharqi Peninsula',
		'XP1_004'	=> 'Wake Island',
	),
	
	'map_cs' => array(
		'as_highrise'		=> 'High Rise',
		'as_oilrig'			=> 'Oilrig',
		'as_tundra'			=> 'Tundra',
		'cs_747'			=> '474 Hijack',
		'cs_assault'		=> 'Assault',
		'cs_arabstreets'	=> 'Arab Streets',
		'cs_backalley'		=> 'Alleyway',
		'cs_estate'			=> 'Zaphod\'s Estate',
		'cs_havana'			=> 'Havana',
		'cs_italy'			=> 'Italy',
		'cs_militia'		=> 'Militia',
		'cs_office'			=> 'Office Complex',
		'cs_siege'			=> 'Canyon Siege',
		'cs_thunder'		=> 'Thunder',
		'de_aztec'			=> 'Aztec',
		'de_chateau'		=> 'Chateau',
		'de_cbble'			=> 'Cobble',
		'de_dust'			=> 'Dust',
		'de_dust2'			=> 'Dust II',
		'de_foption'		=> 'Final Option',
		'de_inferno'		=> 'Inferno',
		'de_nuke'			=> 'Nuke',
		'de_piranesi'		=> 'Piranesi',
		'de_prodigy'		=> 'Prodigy',
		'de_rotterdam'		=> 'Rotterdam Docks',
		'de_storm'			=> 'Storm',
		'de_train'			=> 'Trainyard',
		'de_vegas'			=> 'Vegas',
		'de_vertigo'		=> 'Vertigo',
		'fy_pool_day'		=> 'Pool Day',
	),
	
));

$lang = array_merge($lang, array(
	'CURRENT_TIME' => 'Aktuelles Datum und Uhrzeit: %s',
));


$lang['msg_select_order']		= 'Bitte Reihenfolge ausw&auml;hlen';

$lang['sprintf_select_order']	= '&raquo;&nbsp;nach:&nbsp;%s&nbsp;';
$lang['sprintf_select_before']	= '&raquo;&nbsp;vor:&nbsp;%s&nbsp;';

$lang['common_more']	= 'Erweitern';
$lang['cal_legend']		= '<b>Legende:</b> <span class="today">Heute</span>, <span class="news">News</span>, <span class="event">Ereignisse</span>, <span class="match">Wars</span>, <span class="birthday">Geburtstage</span>, <span class="training">Trainings</span>, <span class="more">mehrere Ereignisse an einem Tag</span>';

$lang['user']			= 'Benutzer';

$lang['Today_at'] = "Today at %s"; // %s is the time
$lang['Yesterday_at'] = "Yesterday at %s"; // %s is the time

#$lang['STF_MATCH_NAME']		= 'vs. %s';
#$lang['STF_MATCH_INTERN']	= 'vs. <span style="font-style:italic;">%s</span>';



$lang['sprintf_subnavi_match']			= 'vs. %s';
$lang['sprintf_subnavi_match_i']		= '<span style="font-style:italic;">vs. %s</span>';
$lang['sprintf_subnavi_match_result']	= '%s:%s';

/*30072012*/
#$lang['team_view']					= 'Team �bersicht';




$lang['msg_select_sort_team']		= 'Team f�rs sortieren w�hlen';


$lang['common_comment']				= 'Kommentar';
$lang['common_comments']			= 'Kommentare';
$lang['common_comment_add']			= 'Kommentar hinzufügen';
$lang['common_comment_edit']		= 'Kommentar bearbeiten';
$lang['common_comment_delete']		= 'Kommentar löschen';
$lang['common_unread']				= 'Ungelesen';
$lang['common_num_comment']			= '%s neuer Kommentar';
$lang['common_num_comments']		= '%s neue Kommentare';

$lang['error_number']				= 'Diese ID ist nicht vergeben!';


$lang['click_return_index'] = 'Klick %shier%s um zum Index zurückzukehren';

$lang['page_newsletter']	= 'Newsletter';

$lang['news_info_url']		= 'Link: ';
$lang['news_info_urls']		= 'Links: ';


$lang['group_set_mod']		= 'Gruppenrechte geändert.';
$lang['group_is_moderator'] = 'Du bist der Moderator dieser Gruppe.';



$lang['Forum_DISALLOWED']	= 'Nein';
$lang['Forum_ALLOWED']		= 'Ja';

$lang['auth_contact']		= 'Kontakt';
$lang['auth_fightus']		= 'Fightus';
$lang['auth_forum']			= 'Forum';
$lang['auth_forum_auth']	= 'Forumberechtigung';
$lang['auth_games']			= 'Spiele';
$lang['auth_groups']		= 'Gruppen';
$lang['auth_joinus']		= 'Joinus';
$lang['auth_match']			= 'Match/Wars';
$lang['auth_navi']			= 'Navigation';
$lang['auth_news']			= 'News';
$lang['auth_news_public']	= 'News ver�ffentlichen';
$lang['auth_newscat']		= 'News Kategorien';
#$lang['auth_ranks']			= 'R�nge';
$lang['auth_server']		= 'Server';
$lang['auth_teams']			= 'Teams';
$lang['auth_teamspeak']		= 'Teamspeak';
$lang['auth_training']		= 'Training';
$lang['auth_user']			= 'Benutzer';

$lang['news_head_info']	= 'News: %s';
$lang['forum_index'] = 'Forum:';

$lang['today_at'] = "Heute um %s"; // %s is the time
$lang['yesterday_at'] = "Gestern um %s"; // %s is the time
$lang['tomorrow_at'] = "Morgen um %s"; // %s is the time



$lang['page_disable'] = 'Seite deaktiviert.';
$lang['Board_disable_explain'] = 'This will deny access to the board for several user groups you can define below.';
$lang['Board_disable_mode'] = 'Disable board for...';
$lang['Board_disable_mode_explain'] = 'Here you can choose who isn\'t allowed to access the board when it\'s disabled. With Ctrl key pressed you can select more than one user group.';

$lang['Board_disable_msg'] = 'Board disabled message';
$lang['Board_disable_msg_explain'] = 'This message will be shown when the board is disabled (empty message = phpBB default message).';

$lang['page_disable_mode_opt'] = array(
										GUEST => 'G�ste',
										USER => 'Benutzer',
										TRIAL => 'Trial Member',
										MEMBER => 'Member',
										MOD => 'CoLeader',
									);




$lang['cache_duration']			= 'Cache-Datei verf�llt am: %s';
$lang['cache_valid']			= 'G�ltig bis: %s';

//
//	Counter
//
$lang['counter_stats_from']		= 'Besucherstatistik vom %s.%s.%s';
$lang['counter_today']			= 'Heute: %s';
$lang['counter_yesterday']		= ' Gestern: %s';
$lang['counter_month']			= ' Monat: %s';
$lang['counter_year']			= ' Jahr: %s';
$lang['counter_total']			= ' Gesamt: %s';


//
//	Header
//


//
//	Forum
//
$lang['forum']				= 'Forum';
$lang['subforums']			= 'Subforen';

//
//	Matches
//
//	Teams

$lang['replace']			= ' Ersatz: ';
$lang['player']				= 'Spieler: ';

$lang['subnavi_last_matches']		= 'Last Wars';
$lang['teams']				= 'Teams';
$lang['all_matches']		= 'alle Wars';
$lang['match_fightus']		= 'FightUs?';
$lang['match_joinus']		= 'JoinUs?';
$lang['to_team']			= 'zum Team';
$lang['match_details']		= 'Details';
$lang['match']				= 'Begegnungen';
$lang['match_info']			= 'Matchinformationen';
$lang['match_upcoming']		= 'Anstehende Wars';
$lang['match_expired']		= 'Abgelaufen Wars';

$lang['training']			= 'Trainings';
$lang['training_upcoming']	= 'Anstehende Trainings';
$lang['training_expired']	= 'Abgelaufen Trainings';
$lang['training_vs']		= 'Training gegen';


$lang['edit_match']			= 'War bearbeiten';
$lang['edit_match_details']	= 'Wardetails bearbeiten';
$lang['edit_training']			= 'Training bearbeiten';

$lang['set_status']			= 'Status �ndern';
$lang['status_none']		= 'Unbekannt';
$lang['status_yes']			= 'kann spielen';
$lang['status_no']			= 'kann nicht';
$lang['status_replace']		= 'Ersatzspieler';
$lang['change_on']			= '<i>geändert:</i> ';



$lang['select_type']		= 'XonX auswählen';
$lang['select_league']		= 'Liga auswählen';
$lang['select_categorie']	= 'Matchtyp auswählen';

$lang['select_type_box']['0']	= 'XonX auswählen';
$lang['select_type_box']['1']	= 'Unbekannt';
$lang['select_type_box']['2']	= '2on2';
$lang['select_type_box']['3']	= '3on3';
$lang['select_type_box']['4']	= '4on4';
$lang['select_type_box']['5']	= '5on5';
$lang['select_type_box']['6']	= '6on6';

$lang['select_categorie_box']['0']	= 'Matchtyp auswählen';
$lang['select_categorie_box']['1']	= 'Fun War';
$lang['select_categorie_box']['2']	= 'Clan War';
$lang['select_categorie_box']['3']	= 'Liga War';
$lang['select_categorie_box']['4']	= 'Train War';

$lang['select_league_box']['0']['league_id']	= '0';
$lang['select_league_box']['0']['league_name']	= 'Liga auswählen';
$lang['select_league_box']['0']['league_link']	= '';
$lang['select_league_box']['1']['league_id']	= '1';
$lang['select_league_box']['1']['league_name']	= 'ESL';
$lang['select_league_box']['1']['league_link']	= 'http://www.esl.eu/';
$lang['select_league_box']['2']['league_id']	= '2';
$lang['select_league_box']['2']['league_name']	= 'Stammkneipe';
$lang['select_league_box']['2']['league_link']	= 'http://www.stammkneipe.de/';
$lang['select_league_box']['3']['league_id']	= '3';
$lang['select_league_box']['3']['league_name']	= '0815 Liga';
$lang['select_league_box']['3']['league_link']	= 'http://www.0815liga.de/';
$lang['select_league_box']['4']['league_id']	= '4';
$lang['select_league_box']['4']['league_name']	= 'Leaguez';
$lang['select_league_box']['4']['league_link']	= 'http://www.lgz.de/';
$lang['select_league_box']['5']['league_id']	= '5';
$lang['select_league_box']['5']['league_name']	= 'TE';
$lang['select_league_box']['5']['league_link']	= 'http://www.tactical-esports.de/';
$lang['select_league_box']['6']['league_id']	= '6';
$lang['select_league_box']['6']['league_name']	= 'XGC';
$lang['select_league_box']['6']['league_link']	= 'http://www.xgc-online.de/';
$lang['select_league_box']['7']['league_id']	= '7';
$lang['select_league_box']['7']['league_name']	= 'NCSL';
$lang['select_league_box']['7']['league_link']	= 'http://www.ncsl.de/';
$lang['select_league_box']['8']['league_id']	= '8';
$lang['select_league_box']['8']['league_name']	= 'andere';
$lang['select_league_box']['8']['league_link']	= '';

//
//	Allgemein
//
$lang['no_entry']			= 'Keine Einträge vorhanden.';
$lang['new_entry'] = 'Neuer Eintrag';
$lang['user_name']			= 'Benutzername';
$lang['joined']				= 'beigetreten';

$lang['register']			= 'registiert';
$lang['status']				= 'Status';

$lang['items_required']			= 'Mit * markierte Felder sind erforderlich';

$lang['day']				= 'Tag';
$lang['month']				= 'Monat';
$lang['year']				= 'Jahr';

//$lang['back']			= '<br><a style="color:#fff; font-weight:bold; font-size:blod;" href="javascript:history.back(-1)">&laquo; Zurück</a>';
$lang['back']			= '%s&laquo; Zurück%s';

//
//	Log
//
$lang['ucp_acp_login']	= 'Erfolgreich im Adminbereich eingeloggt.';
$lang['acp_team_order']	= 'Teams sortiert.';

//
//	Navi
//
$lang['navi_training']		= 'Training';
$lang['navi_match']			= 'Wars';
$lang['navi_index']			= 'Startseite';




$lang['click_return_newsletter']	= '%sHier klicken%s, um zu der Newsletter Seite zurückzukehren.';

$lang['connect']	= 'spielen';


//
// The format of this file is ---> $lang['message'] = 'text';
//
// You should also try to set a locale and a character encoding (plus direction). The encoding and direction
// will be sent to the template. The locale may or may not work, it's dependent on OS support and the syntax
// varies ... give it your best guess!
//

$lang['ENCODING'] = 'iso-8859-1';
$lang['DIRECTION'] = 'ltr';
$lang['LEFT'] = 'left';
$lang['RIGHT'] = 'right';
$lang['DATE_FORMAT'] = 'd.m.Y';// This should be changed to the default date format for your language, php date() format

// This is optional, if you would like a _SHORT_ message output
// along with our copyright message indicating you are the translator
// please add it here.
$lang['TRANSLATION_INFO'] = 'Deutsche �bersetzung von <a href="http://www.phpbb.de/" target="_blank" class="copyright">phpBB.de</a>';

//
// Common, these terms are used
// extensively on several pages
//
$lang['Forum'] = 'Forum';
$lang['Category'] = 'Kategorie';
$lang['Topic'] = 'Thema';
$lang['Topics'] = 'Themen';
$lang['Replies'] = 'Antworten';
$lang['Views'] = 'Aufrufe';
$lang['Post'] = 'Beitrag';
$lang['Posts'] = 'Beiträge';
$lang['Posted'] = 'Verfasst am';
$lang['Username'] = 'Benutzername';
$lang['Password'] = 'Passwort';
$lang['Email'] = 'E-Mail';
$lang['Poster'] = 'Poster';
$lang['Author'] = 'Autor';
$lang['Time'] = 'Zeit';
$lang['Hours'] = 'Stunden';
$lang['Message'] = 'Nachricht';

$lang['1_Day'] = '1 Tag';
$lang['7_Days'] = '7 Tage';
$lang['2_Weeks'] = '2 Wochen';
$lang['1_Month'] = '1 Monat';
$lang['3_Months'] = '3 Monate';
$lang['6_Months'] = '6 Monate';
$lang['1_Year'] = '1 Jahr';

$lang['Go'] = 'Los';
$lang['Jump_to'] = 'Gehe zu';
$lang['Submit'] = 'Absenden';
$lang['Reset'] = 'Zurücksetzen';
$lang['Cancel'] = 'Abbrechen';
$lang['Preview'] = 'Vorschau';
$lang['Confirm'] = 'Bestätigen';
$lang['Spellcheck'] = 'Rechtschreibprüfung';
$lang['Yes'] = 'Ja';
$lang['No'] = 'Nein';
$lang['Enabled'] = 'Aktiviert';
$lang['Disabled'] = 'Deaktiviert';
$lang['Error'] = 'Fehler';

$lang['yes']	= 'Ja';
$lang['no']		= 'Nein';
$lang['replace']	= 'Ersatz';
$lang['show']	= 'Anzeigen';
$lang['noshow']	= 'nicht Anzeigen';


$lang['Next'] = 'Weiter';
$lang['Previous'] = 'Zurück';
$lang['Goto_page'] = 'Gehe zu Seite';
$lang['Joined'] = 'Anmeldedatum';
$lang['IP_Address'] = 'IP-Adresse';

$lang['Select_forum'] = 'Forum auswählen';
$lang['View_latest_post'] = 'Letzten Beitrag anzeigen';
$lang['View_newest_post'] = 'Neuesten Beitrag anzeigen';
$lang['common_page_of'] = 'Seite <b>%d</b> von <b>%d</b>';// Replaces with: Page 1 of 2 for example

$lang['ICQ'] = 'ICQ-Nummer';
$lang['AIM'] = 'AIM-Name';
$lang['MSNM'] = 'MSN Messenger';
$lang['YIM'] = 'Yahoo Messenger';

$lang['Forum_Index'] = '%s Foren-�bersicht';// eg. page_name Forum Index, %s can be removed if you prefer

$lang['Post_new_topic'] = 'Neues Thema er�ffnen';
$lang['Reply_to_topic'] = 'Neue Antwort erstellen';
$lang['Reply_with_quote'] = 'Antworten mit Zitat';

$lang['Click_return_topic'] = '%sHier klicken%s, um zum Thema zurückzukehren';// %s's here are for uris, do not remove!
$lang['Click_return_login'] = '%sHier klicken%s, um es noch einmal zu versuchen';
$lang['Click_return_forum'] = '%sHier klicken%s, um zum Forum zurückzukehren';
$lang['Click_view_message'] = '%sHier klicken%s, um deine Nachricht anzuzeigen';
$lang['Click_return_modcp'] = '%sHier klicken%s, um zur Moderatorenkontrolle zurückzukehren';
$lang['Click_return_group'] = '%sHier klicken%s, um zur Gruppeninfo zurückzukehren';

$lang['Admin_panel'] = 'Administrations';

$lang['Board_disable'] = 'Sorry, aber dieses Board ist im Moment nicht verfügbar. Probier es bitte sp�ter wieder.';


//
// Global Header strings
//
$lang['Registered_users'] = 'Registrierte Benutzer:';
$lang['Browsing_forum'] = 'Benutzer in diesem Forum:';
$lang['online_users_zero_total'] = 'Insgesamt sind <b>0</b> Benutzer online: ';
$lang['online_users_total'] = 'Insgesamt sind <b>%d</b> Benutzer online: ';
$lang['online_user_total'] = 'Insgesamt ist <b>ein</b> Benutzer online: ';
$lang['reg_users_zero_total'] = 'Kein registrierter, ';
$lang['reg_users_total'] = '%d registrierte, ';
$lang['reg_user_total'] = 'Ein registrierter, ';
$lang['hidden_users_zero_total'] = 'kein versteckter und ';
$lang['hidden_users_total'] = '%d versteckte und ';
$lang['hidden_user_total'] = 'ein versteckter und ';
$lang['guest_users_zero_total'] = 'kein Gast.';
$lang['guest_users_total'] = '%d G�ste.';
$lang['guest_user_total'] = 'ein Gast.';
$lang['Record_online_users'] = 'Der Rekord liegt bei <b>%s</b> Benutzern am %s.';// first %s = number of users, second %s is the date.

$lang['online_users_zero_total_h'] = '<b>0</b> Benutzer: ';
$lang['online_users_total_h'] = '<b>%d</b> Benutzer: ';
$lang['online_user_total_h'] = '<b>1</b> Benutzer: ';
$lang['reg_users_zero_total_h'] = '0 registrierter, ';
$lang['reg_users_total_h'] = '%d registrierte, ';
$lang['reg_user_total_h'] = '1 registrierter, ';
$lang['hidden_users_zero_total_h'] = '0 versteckte und ';
$lang['hidden_users_total_h'] = '%d versteckte und ';
$lang['hidden_user_total_h'] = '1 versteckter und ';
$lang['guest_users_zero_total_h'] = '0 G�ste';
$lang['guest_users_total_h'] = '%d G�ste';
$lang['guest_user_total_h'] = '1 Gast';

$lang['n_online_users_zero_total'] = '<b>0</b> Benutzer';
$lang['n_online_users_total'] = '<b>%d</b> Benutzer';
$lang['n_online_user_total'] = '<b>1</b> Benutzer';
$lang['n_reg_users_zero_total'] = '0 registrierter';
$lang['n_reg_users_total'] = '%d registrierte';
$lang['n_reg_user_total'] = '1 registrierter';
$lang['n_hidden_users_zero_total'] = '0 versteckte';
$lang['n_hidden_users_total'] = '%d versteckte';
$lang['n_hidden_user_total'] = '1 versteckter';
$lang['n_guest_users_zero_total'] = '0 G�ste';
$lang['n_guest_users_total'] = '%d G�ste';
$lang['n_guest_user_total'] = '1 Gast';

$lang['Admin_online_color'] = '%sAdministrator%s';
$lang['Mod_online_color'] = '%sModerator%s';

$lang['You_last_visit'] = 'Dein letzter Besuch war am: %s';// %s replaced by date/time
$lang['Current_time'] = 'Aktuelles Datum und Uhrzeit: %s';// %s replaced by time

$lang['Search_new'] = 'Beiträge seit dem letzten Besuch anzeigen';
$lang['Search_your_posts'] = 'Eigene Beiträge anzeigen';
$lang['Search_unanswered'] = 'Unbeantwortete Beiträge anzeigen';

$lang['Register'] = 'Registrieren';
$lang['Profile'] = 'Profil';
$lang['Edit_profile'] = 'Profil bearbeiten';
$lang['Search'] = 'Suchen';
$lang['Memberlist'] = 'Mitgliederliste';
$lang['FAQ'] = 'FAQ';
$lang['BBCode_guide'] = 'BBCode-Hilfe';
$lang['Usergroups'] = 'Benutzergruppen';
$lang['Last_Post'] = 'Letzter&nbsp;Beitrag';
$lang['Moderator'] = '<b>Moderator</b>';
$lang['Moderators'] = '<b>Moderatoren</b>';


//
// Stats block text
//
$lang['Posted_articles_zero_total'] = 'Unsere Benutzer haben <b>noch keine</b> Beiträge geschrieben.';// Number of posts
$lang['Posted_articles_total'] = 'Unsere Benutzer haben insgesamt <b>%d</b> Beiträge geschrieben.';// Number of posts
$lang['Posted_article_total'] = 'Unsere Benutzer haben <b>einen</b> Beitrag geschrieben.';// Number of posts
$lang['Registered_users_zero_total'] = 'Wir haben <b>keine</b> registrierten Benutzer.';// # registered users
$lang['Registered_users_total'] = 'Wir haben <b>%d</b> registrierte Benutzer.';// # registered users
$lang['Registered_user_total'] = 'Wir haben <b>einen</b> registrierten Benutzer.';// # registered users
$lang['Newest_user'] = 'Der neueste Benutzer ist <b>%s%s%s</b>.';// a href, user_name, /a

$lang['No_new_posts_last_visit'] = 'Keine neuen Beiträge seit deinem letzten Besuch';
$lang['No_new_posts'] = 'Keine neuen Beiträge';
$lang['New_posts'] = 'Neue Beiträge';
$lang['New_post'] = 'Neuer Beitrag';
$lang['No_new_posts_hot'] = 'Keine neuen Beiträge [ Top-Thema ]';
$lang['New_posts_hot'] = 'Neue Beiträge [ Top-Thema ]';
$lang['No_new_posts_locked'] = 'Keine neuen Beiträge [ Gesperrt ]';
$lang['New_posts_locked'] = 'Neue Beiträge [ Gesperrt ]';
$lang['Forum_is_locked'] = 'Forum ist gesperrt';


//
// Login
//
$lang['Enter_password'] = 'Gib bitte deinen Benutzernamen und dein Passwort ein, um dich einzuloggen!';
$lang['Login'] = 'Login';
$lang['Logout'] = 'Logout';

$lang['Forgotten_password'] = 'Ich habe mein Passwort vergessen!';

$lang['Log_me_in'] = 'Bei jedem Besuch automatisch einloggen';

$lang['Error_login'] = 'Du hast einen falschen oder inaktiven Benutzernamen oder ein falsches Passwort eingegeben.';


//
// Index page
//
$lang['Index'] = 'Index';
$lang['No_Posts'] = 'Keine Beiträge';
$lang['No_forums'] = 'Dieses Board hat keine Foren.';

$lang['Private_Message'] = 'Private Nachricht';
$lang['Private_Messages'] = 'Private Nachrichten';
$lang['Who_is_Online'] = 'Wer ist online?';

$lang['Mark_all_forums'] = 'Alle Foren als gelesen markieren';
$lang['Forums_marked_read'] = 'Alle Foren wurden als gelesen markiert.';


//
// Viewforum
//
$lang['View_forum'] = 'Forum anzeigen';

$lang['Forum_not_exist'] = 'Das ausgewählte Forum existiert nicht.';
$lang['Reached_on_error'] = 'Fehler auf dieser Seite!';

$lang['Display_topics'] = 'Siehe Beiträge der letzten';
$lang['All_Topics'] = 'Alle Themen anzeigen';

$lang['Topic_Announcement'] = '<b>Ank�ndigungen:</b>';
$lang['Topic_Sticky'] = '<b>Wichtig:</b>';
$lang['Topic_Moved'] = '<b>Verschoben:</b>';
$lang['Topic_Poll'] = '<b>[Umfrage]</b>';

$lang['Mark_all_topics'] = 'Alle Themen als gelesen markieren';
$lang['Topics_marked_read'] = 'Alle Themen wurden als gelesen markiert.';

$lang['Rules_post_can'] = 'Du <b>kannst</b> Beiträge in dieses Forum schreiben.';
$lang['Rules_post_cannot'] = 'Du <b>kannst keine</b> Beiträge in dieses Forum schreiben.';
$lang['Rules_reply_can'] = 'Du <b>kannst</b> auf Beiträge in diesem Forum antworten.';
$lang['Rules_reply_cannot'] = 'Du <b>kannst</b> auf Beiträge in diesem Forum <b>nicht</b> antworten.';
$lang['Rules_edit_can'] = 'Du <b>kannst</b> deine Beiträge in diesem Forum bearbeiten.';
$lang['Rules_edit_cannot'] = 'Du <b>kannst</b> deine Beiträge in diesem Forum <b>nicht</b> bearbeiten.';
$lang['Rules_delete_can'] = 'Du <b>kannst</b> deine Beiträge in diesem Forum löschen.';
$lang['Rules_delete_cannot'] = 'Du <b>kannst</b> deine Beiträge in diesem Forum <b>nicht</b> löschen.';
$lang['Rules_vote_can'] = 'Du <b>kannst</b> an Umfragen in diesem Forum teilnehmen.';
$lang['Rules_vote_cannot'] = 'Du <b>kannst</b> an Umfragen in diesem Forum <b>nicht</b> teilnehmen.';
$lang['Rules_moderate'] = 'Du <b>kannst</b> %sdieses Forum moderieren%s.';// %s replaced by a href links, do not remove!

$lang['Rules_post_can'] = 'Du <b>kannst</b> Beiträge schreiben.';
$lang['Rules_post_cannot'] = 'Du <b>kannst keine</b> Beiträge schreiben.';
$lang['Rules_reply_can'] = 'Du <b>kannst</b> auf Beiträge antworten.';
$lang['Rules_reply_cannot'] = 'Du <b>kannst</b> auf Beiträge <b>nicht</b> antworten.';
$lang['Rules_edit_can'] = 'Du <b>kannst</b> deine Beiträge bearbeiten.';
$lang['Rules_edit_cannot'] = 'Du <b>kannst</b> deine Beiträge <b>nicht</b> bearbeiten.';
$lang['Rules_delete_can'] = 'Du <b>kannst</b> deine Beiträge löschen.';
$lang['Rules_delete_cannot'] = 'Du <b>kannst</b> deine Beiträge <b>nicht</b> löschen.';
$lang['Rules_vote_can'] = 'Du <b>kannst</b> an Umfragen teilnehmen.';
$lang['Rules_vote_cannot'] = 'Du <b>kannst</b> an Umfragen <b>nicht</b> teilnehmen.';
$lang['Rules_moderate'] = 'Du <b>kannst</b> %sdieses Forum moderieren%s.';// %s replaced by a href links, do not remove!

$lang['No_topics_post_one'] = 'In diesem Forum sind keine Beiträge vorhanden.<br>Klicke auf <b>Neues Thema</b>, um den ersten Beitrag zu erstellen.';


//
// Viewtopic
//
$lang['View_topic'] = 'Thema anzeigen';

$lang['Guest'] = 'Gast';
$lang['Post_subject'] = 'Titel';
$lang['View_next_topic'] = 'N�chstes Thema anzeigen';
$lang['View_previous_topic'] = 'Vorheriges Thema anzeigen';
$lang['Submit_vote'] = 'Stimme absenden';
$lang['View_results'] = 'Ergebnis anzeigen';

$lang['No_newer_topics'] = 'Es gibt keine neueren Themen in diesem Forum.';
$lang['No_older_topics'] = 'Es gibt keine �lteren Themen in diesem Forum.';
$lang['Topic_post_not_exist'] = 'Das gew�hlte Thema oder der Beitrag existiert nicht.';
$lang['No_posts_topic'] = 'Es existieren keine Beiträge zu diesem Thema.';

$lang['Display_posts'] = 'Beiträge der letzten Zeit anzeigen';
$lang['All_Posts'] = 'Alle Beiträge';
$lang['Newest_First'] = 'Die neusten zuerst';
$lang['Oldest_First'] = 'Die �ltesten zuerst';

$lang['Back_to_top'] = 'Nach oben';

$lang['Read_profile'] = 'Benutzer-Profile anzeigen';
$lang['Visit_website'] = 'Website dieses Benutzers besuchen';
$lang['ICQ_status'] = 'ICQ-Status';
$lang['Edit_delete_post'] = 'Beitrag bearbeiten oder löschen';
$lang['View_IP'] = 'IP-Adresse zeigen';
$lang['Delete_post'] = 'Beitrag löschen';

$lang['wrote'] = 'hat Folgendes geschrieben';// proceeds the user_name and is followed by the quoted text
$lang['Quote'] = 'Zitat';// comes before bbcode quote output.
$lang['Code'] = 'Code';// comes before bbcode code output.

$lang['Edited_time_total'] = 'Zuletzt bearbeitet von %s am %s, insgesamt einmal bearbeitet';// Last edited by me on 12 Oct 2001, edited 1 time in total
$lang['Edited_times_total'] = 'Zuletzt bearbeitet von %s am %s, insgesamt %d-mal bearbeitet';// Last edited by me on 12 Oct 2001, edited 2 times in total

$lang['Lock_topic'] = 'Thema sperren';
$lang['Unlock_topic'] = 'Thema entsperren';
$lang['Move_topic'] = 'Thema verschieben';
$lang['Delete_topic'] = 'Thema löschen';
$lang['Split_topic'] = 'Thema teilen';

$lang['Stop_watching_topic'] = 'Bei Antworten zu diesem Thema nicht mehr benachrichtigen';
$lang['Start_watching_topic'] = 'Bei Antworten zu diesem Thema benachrichtigen';
$lang['No_longer_watching'] = 'Das Thema wird nicht mehr von dir beobachtet.';
$lang['You_are_watching'] = 'Du beobachtest nun das Thema.';

$lang['Total_votes'] = 'Stimmen insgesamt';

//
// Posting/Replying (Not private messaging!)
//
$lang['Message_body'] = 'Nachrichtentext';
$lang['Topic_review'] = 'Thema-�berblick';

$lang['No_post_mode'] = 'Kein Eintrags-Modus ausgewählt';// If posting.php is called without a mode (newtopic/reply/delete/etc, shouldn't be shown normaly)

$lang['Post_a_new_topic'] = 'Neues Thema schreiben';
$lang['Post_a_reply'] = 'Antwort schreiben';
$lang['Post_topic_as'] = 'Thema schreiben als';
$lang['Edit_Post'] = 'Beitrag editieren';
$lang['Options'] = 'Optionen';

$lang['Post_Announcement'] = 'Ank�ndigung';
$lang['Post_Sticky'] = 'Wichtig';
$lang['Post_Normal'] = 'Normal';

$lang['Confirm_delete'] = 'Sicher, dass dieser Beitrag gelöscht werden soll?';
$lang['Confirm_delete_poll'] = 'Sicher, dass diese Umfrage gelöscht werden soll?';

$lang['Flood_Error'] = 'Du kannst einen Beitrag nicht so schnell nach deinem letzten absenden, bitte warte einen Augenblick.';
$lang['Empty_subject'] = 'Bei einem neuen Thema musst du einen Titel angeben.';
$lang['Empty_message'] = 'Du musst zu deinem Beitrag einen Text eingeben.';
$lang['Forum_locked'] = 'Dieses Forum ist gesperrt, du kannst keine Beiträge editieren, schreiben oder beantworten.';
$lang['Topic_locked'] = 'Dieses Thema ist gesperrt, du kannst keine Beiträge editieren oder beantworten.';
$lang['No_post_id'] = 'Du musst einen Beitrag zum Editieren auswählen.';
$lang['No_topic_id'] = 'Du musst ein Thema f�r deine Antwort auswählen.';
$lang['No_valid_mode'] = 'Du kannst nur Beiträge schreiben, bearbeiten, beantworten und zitieren. Versuch es noch einmal.';
$lang['No_such_post'] = 'Es existiert kein solcher Beitrag. Versuch es noch einmal.';
$lang['Edit_own_posts'] = 'Du kannst nur deine eigenen Beiträge bearbeiten.';
$lang['Delete_own_posts'] = 'Du kannst nur deine eigenen Beiträge löschen.';
$lang['Cannot_delete_replied'] = 'Du kannst keine Beiträge löschen, die schon beantwortet wurden.';
$lang['Cannot_delete_poll'] = 'Du kannst keine aktiven Umfrage löschen.';
$lang['Empty_poll_title'] = 'Du musst einen Titel f�r die Umfrage eingeben.';
$lang['To_few_poll_options'] = 'Du musst mindestens zwei Antworten f�r die Umfrage angeben.';
$lang['To_many_poll_options'] = 'Du hast zu viele Antworten f�r die Umfrage angegeben';
$lang['Post_has_no_poll'] = 'Dieser Beitrag hat keine Umfrage.';
$lang['Already_voted'] = 'Du hast an dieser Umfrage schon teilgenommen.';
$lang['No_vote_option'] = 'Du musst eine Auswahl treffen, um abzustimmen.';

$lang['Add_poll'] = 'Umfrage hinzufügen';
$lang['Add_poll_explain'] = 'Wenn du keine Umfrage zum Thema hinzufügen willst, lass die Felder leer.';
$lang['Poll_question'] = 'Frage';
$lang['Poll_option'] = 'Antwort';
$lang['Add_option'] = 'Antwort hinzufügen';
$lang['Update'] = 'Aktualisieren';
$lang['Delete'] = 'Löschen';
$lang['Poll_for'] = 'Dauer der Umfrage:';
$lang['Days'] = 'Tage';// This is used for the Run poll for ... Days + in admin_forums for pruning
$lang['Poll_for_explain'] = '[ Gib 0 ein oder lass dieses Feld leer, um die Umfrage auf unbeschr�nkte Zeit durchzuf�hren ]';
$lang['Delete_poll'] = 'Umfrage löschen';

$lang['Disable_HTML_post'] = 'HTML in diesem Beitrag deaktivieren';
$lang['Disable_BBCode_post'] = 'BBCode in diesem Beitrag deaktivieren';
$lang['Disable_Smilies_post'] = 'Smilies in diesem Beitrag deaktivieren';

$lang['HTML_is_ON'] = 'HTML ist <u>an</u>';
$lang['HTML_is_OFF'] = 'HTML ist <u>aus</u>';
$lang['BBCode_is_ON'] = '%sBBCode%s ist <u>an</u>';// %s are replaced with URI pointing to FAQ
$lang['BBCode_is_OFF'] = '%sBBCode%s ist <u>aus</u>';
$lang['Smilies_are_ON'] = 'Smilies sind <u>an</u>';
$lang['Smilies_are_OFF'] = 'Smilies sind <u>aus</u>';

$lang['Attach_signature'] = 'Signatur anh�ngen (Signatur kann im Profil geändert werden)';
$lang['Notify'] = 'Benachrichtigt mich, wenn eine Antwort geschrieben wurde';

$lang['Stored'] = 'Deine Nachricht wurde erfolgreich eingetragen.';
$lang['Deleted'] = 'Deine Nachricht wurde erfolgreich gelöscht.';
$lang['Poll_delete'] = 'Deine Umfrage wurde erfolgreich gelöscht.';
$lang['Vote_cast'] = 'Deine Stimme wurde gez�hlt.';

$lang['Topic_reply_notification'] = 'Benachrichtigen bei Antworten';

$lang['bbcode_b_help'] = 'Text in fett: [b]Text[/b] (alt+b)';
$lang['bbcode_i_help'] = 'Text in kursiv: [i]Text[/i] (alt+i)';
$lang['bbcode_u_help'] = 'Unterstrichener Text: [u]Text[/u] (alt+u)';
$lang['bbcode_q_help'] = 'Zitat: [quote]Text[/quote] (alt+q)';
$lang['bbcode_c_help'] = 'Code anzeigen: [code]Code[/code] (alt+c)';
$lang['bbcode_l_help'] = 'Liste: [list]Text[/list] (alt+l)';
$lang['bbcode_o_help'] = 'Geordnete Liste: [list=]Text[/list] (alt+o)';
$lang['bbcode_p_help'] = 'Bild einf�gen: [img]http://URL_des_Bildes[/img] (alt+p)';
$lang['bbcode_w_help'] = 'URL einf�gen: [url]http://URL[/url] oder [url=http://url]URL Text[/url] (alt+w)';
$lang['bbcode_a_help'] = 'Alle offenen BBCodes schlie�en';
$lang['bbcode_s_help'] = 'Schriftfarbe: [color=red]Text[/color] Tipp: Du kannst ebenfalls color=#FF0000 benutzen';
$lang['bbcode_f_help'] = 'Schriftgröße: [size=x-small]Kleiner Text[/size]';

$lang['Emoticons'] = 'Smilies';
$lang['More_emoticons'] = 'Weitere Smilies ansehen';

$lang['Font_color'] = 'Schriftfarbe';
$lang['color_default'] = 'Standard';
$lang['color_dark_red'] = 'Dunkelrot';
$lang['color_red'] = 'Rot';
$lang['color_orange'] = 'Orange';
$lang['color_brown'] = 'Braun';
$lang['color_yellow'] = 'Gelb';
$lang['color_green'] = 'Gr�n';
$lang['color_olive'] = 'Oliv';
$lang['color_cyan'] = 'Cyan';
$lang['color_blue'] = 'Blau';
$lang['color_dark_blue'] = 'Dunkelblau';
$lang['color_indigo'] = 'Indigo';
$lang['color_violet'] = 'Violett';
$lang['color_white'] = 'Wei�';
$lang['color_black'] = 'Schwarz';

$lang['Font_size'] = 'Schriftgröße';
$lang['font_tiny'] = 'Winzig';
$lang['font_small'] = 'Klein';
$lang['font_normal'] = 'Normal';
$lang['font_large'] = 'Gro�';
$lang['font_huge'] = 'Riesig';

$lang['Close_Tags'] = 'Tags schlie�en';
$lang['Styles_tip'] = 'Tipp: Styles k�nnen schnell zum markierten Text hinzugefügt werden.';


//
// Private Messaging
//
$lang['Private_Messaging'] = 'Private Nachrichten';

$lang['Login_check_pm'] = 'Einloggen, um private Nachrichten zu lesen';
$lang['New_pms'] = 'Du hast %d neue Nachrichten';// You have 2 new messages
$lang['New_pm'] = 'Du hast 1 neue Nachricht';// You have 1 new message
$lang['No_new_pm'] = 'Du hast keine neuen Nachrichten';
$lang['Unread_pms'] = 'Du hast %d ungelesene Nachrichten';
$lang['Unread_pm'] = 'Du hast 1 ungelesene Nachricht';
$lang['No_unread_pm'] = 'Du hast keine ungelesenen Nachrichten';
$lang['You_new_pm'] = 'Eine neue private Nachricht befindet sich in deinem Posteingang';
$lang['You_new_pms'] = 'Es befinden sich neue private Nachrichten in deinem Posteingang';
$lang['You_no_new_pm'] = 'Es sind keine neuen privaten Nachrichten vorhanden';

$lang['Unread_message'] = 'Ungelesene Nachricht';
$lang['Read_message'] = 'Gelesene Nachricht';

$lang['Read_pm'] = 'Nachricht lesen';
$lang['Post_new_pm'] = 'Nachricht schreiben';
$lang['Post_reply_pm'] = 'Auf Nachricht antworten';
$lang['Post_quote_pm'] = 'Nachricht zitieren';
$lang['Edit_pm'] = 'Nachricht bearbeiten';

$lang['Inbox'] = 'Posteingang';
$lang['Outbox'] = 'Postausgang';
$lang['Savebox'] = 'Archiv';
$lang['Sentbox'] = 'Gesendete Nachrichten';
$lang['Flag'] = 'Flag';
$lang['Subject'] = 'Titel';
$lang['From'] = 'Von';
$lang['To'] = 'An';
$lang['Date'] = 'Datum';
$lang['Mark'] = 'Markiert';
$lang['Sent'] = 'Gesendet';
$lang['Saved'] = 'Gespeichert';
$lang['Delete_marked'] = 'Markierte löschen';
$lang['Delete_all'] = 'Alle löschen';
$lang['Save_marked'] = 'Markierte speichern';
$lang['Save_message'] = 'Nachricht speichern';
$lang['Delete_message'] = 'Nachricht löschen';

$lang['Display_messages'] = 'Nachrichten anzeigen der letzten';// Followed by number of days/weeks/months
$lang['All_Messages'] = 'Alle Nachrichten';

$lang['No_messages_folder'] = 'Es sind keine weiteren Nachrichten in diesem Ordner.';

$lang['PM_disabled'] = 'Private Nachrichten wurden in diesem Board deaktiviert.';
$lang['Cannot_send_privmsg'] = 'Der Administrator hat private Nachrichten f�r dich gesperrt.';
$lang['No_to_user'] = 'Du musst einen Benutzernamen angeben, um diese Nachricht zu senden.';
$lang['No_such_user'] = 'Es existiert kein Benutzer mit diesem Namen.';

$lang['Disable_HTML_pm'] = 'HTML in dieser Nachricht deaktivieren';
$lang['Disable_BBCode_pm'] = 'BBCode in dieser Nachricht deaktivieren';
$lang['Disable_Smilies_pm'] = 'Smilies in dieser Nachricht deaktivieren';

$lang['Message_sent'] = 'Deine Nachricht wurde gesendet.';

$lang['Click_return_inbox'] = 'Klick %shier%s um zum Posteingang zurückzukehren';
$lang['Click_return_index'] = 'Klick %shier%s um zum Index zurückzukehren';

$lang['Send_a_new_message'] = 'Neue Nachricht senden';
$lang['Send_a_reply'] = 'Auf private Nachricht antworten';
$lang['Edit_message'] = 'Private Nachricht bearbeiten';

$lang['Notification_subject'] = 'Eine neue private Nachricht ist eingetroffen!';

$lang['Find_user_name'] = 'Benutzernamen finden';
$lang['Find'] = 'Finden';
$lang['No_match'] = 'Keine Ergebnisse gefunden.';

$lang['No_post_id'] = 'Es wurde keine Beitrags-ID angegeben.';
$lang['No_such_folder'] = 'Es existiert kein solcher Ordner.';
$lang['No_folder'] = 'Kein Ordner ausgewählt';

$lang['MARK_ALL'] = 'Alle markieren';
$lang['Unmark_all'] = 'Markierungen aufheben';

$lang['Confirm_delete_pm'] = 'Diese Nachricht wirklich löschen?';
$lang['Confirm_delete_pms'] = 'Diese Nachrichten wirklich löschen?';

$lang['Inbox_size'] = 'Dein Posteingang ist zu %d%% voll';// eg. Your Inbox is 50% full
$lang['Sentbox_size'] = 'Deine gesendeten Nachrichten sind zu %d%% voll';
$lang['Savebox_size'] = 'Dein Archiv ist zu %d%% voll';

$lang['Click_view_privmsg'] = 'Klick %shier%s, um deinen Posteingang aufzurufen';


//
// Profiles/Registration
//
$lang['Viewing_user_profile'] = 'Profil anzeigen: %s';// %s is user_name
$lang['About_user'] = 'Alles �ber %s';

$lang['Preferences'] = 'Einstellungen';
$lang['Items_required'] = 'Mit * markierte Felder sind erforderlich';
$lang['Registration_info'] = 'Registrierungs-Informationen';
$lang['Profile_info'] = 'Profil-Informationen';
$lang['Profile_info_warn'] = 'Diese Informationen sind �ffentlich abrufbar!';
$lang['Avatar_panel'] = 'Avatar-Steuerung';
$lang['Avatar_gallery'] = 'Avatar-Galerie';

$lang['Website'] = 'Website';
$lang['Location'] = 'Wohnort';
$lang['Contact'] = 'Kontakt';
$lang['Email_address'] = 'E-Mail-Adresse';
$lang['Send_private_message'] = 'Private Nachricht senden';
$lang['Hidden_email'] = '[ Versteckt ]';
$lang['Interests'] = 'Interessen';
$lang['Occupation'] = 'Beruf';
$lang['Poster_rank'] = 'Rang';

$lang['Total_posts'] = 'Beiträge insgesamt';
$lang['User_post_pct_stats'] = '%.2f%% aller Beiträge';// 1.25% of total
$lang['User_post_day_stats'] = '%.2f Beiträge pro Tag';// 1.5 posts per day
$lang['Search_user_posts'] = 'Alle Beiträge von %s anzeigen';// Find all posts by user_name

$lang['No_user_id_specified'] = 'Dieser Benutzer existiert nicht.';
$lang['Wrong_Profile'] = 'Du kannst nur dein eigenes Profil bearbeiten.';

$lang['Only_one_avatar'] = 'Es kann nur ein Avatar ausgewählt werden';
$lang['File_no_data'] = 'Die angegebene Datei enth�lt keine Daten';
$lang['No_connection'] = 'Es konnte keine Verbindung zur angegebenen Datei hergestellt werden';
$lang['Incomplete'] = 'Die angegebene URL ist unvollst�ndig';
$lang['Wrong_remote_avatar_format'] = 'Das Format des Avatars ist nicht g�ltig';
$lang['No_send_account_inactive'] = 'Sorry, aber ein neues Passwort kann im Moment nicht gesendet werden, da dein Account derzeit noch inaktiv ist. Bitte kontaktiere den Administrator f�r weitere Informationen.';

$lang['Always_smile'] = 'Smilies immer aktivieren';
$lang['Always_html'] = 'HTML immer aktivieren';
$lang['Always_bbcode'] = 'BBCode immer aktivieren';
$lang['Always_add_sig'] = 'Signatur immer anh�ngen';
$lang['Always_notify'] = 'Bei Antworten immer benachrichtigen';
$lang['Always_notify_explain'] = 'Sendet dir eine E-Mail, wenn jemand auf einen deiner Beiträge antwortet. Kann f�r jeden Beitrag geändert werden.';

$lang['Board_style'] = 'Board-Style';
$lang['Board_lang'] = 'Board-Sprache';
$lang['No_themes'] = 'Keine Themes in der Datenbank';
$lang['Timezone'] = 'Zeitzone';
$lang['Date_format'] = 'Datums-Format';
$lang['Date_format_explain'] = 'Die Syntax ist identisch mit der PHP-Funktion <a href=\'http://www.php.net/date\' target=\'_other\'>date()</a>';
$lang['Signature'] = 'Signatur';
$lang['Signature_explain'] = 'Dies ist ein Text, der an jeden Beitrag von dir angeh�ngt werden kann. Es besteht ein Limit von %d Buchstaben.';
$lang['Public_view_email'] = 'Zeige meine E-Mail-Adresse immer an';

$lang['Current_password'] = 'Altes Passwort';
$lang['New_password'] = 'Neues Passwort';
$lang['Confirm_password'] = 'Passwort bestätigen';
$lang['Confirm_password_explain'] = 'Du musst dein Passwort angeben, wenn du dein Passwort oder deine Mailadresse �ndern m�chtest.';
$lang['password_if_changed'] = 'Du musst nur dann ein neues Passwort angeben, wenn du es �ndern willst';
$lang['password_confirm_if_changed'] = 'Du musst dein neues Passwort bestätigen, wenn du es �ndern willst';

$lang['Avatar'] = 'Avatar';
$lang['Avatar_explain'] = 'Zeigt eine kleine Grafik neben jedem deiner Beiträge an. Es kann immer nur ein Avatar angezeigt werden, seine Breite darf nicht größer als %d Pixel sein, die H�he nicht größer als %d Pixel, und die Dateigröße darf maximal %d KB betragen.';
$lang['Upload_Avatar_file'] = 'Avatar von deinem Computer hochladen';
$lang['Upload_Avatar'] = 'Avatar von URL hochladen';
$lang['Upload_Avatar_URL_explain'] = 'Gib die URL des gew�nschten Avatars an, dieser wird dann kopiert';
$lang['Pick_local_Avatar'] = 'Avatar aus der Galerie auswählen';
$lang['Link_remote_Avatar'] = 'Zu einem externen Avatar verlinken';
$lang['Link_remote_Avatar_explain'] = 'Gib die URL des Avatars ein, der verlinkt werden soll';
$lang['Avatar'] = 'URL des Avatars';
$lang['Select_from_gallery'] = 'Avatar aus der Galerie auswählen';
$lang['View_avatar_gallery'] = 'Galerie anzeigen';

$lang['Select_avatar'] = 'Avatar auswählen';
$lang['Return_profile'] = 'Avatar abbrechen';
$lang['Select_category'] = 'Kategorie auswählen';

$lang['Delete_Image'] = 'Bild löschen';
$lang['Current_Image'] = 'Aktuelles Bild';

$lang['Notify_on_privmsg'] = 'Bei neuen Privaten Nachrichten benachrichtigen';
$lang['Popup_on_privmsg'] = 'PopUp-Fenster bei neuen Privaten Nachrichten';
$lang['Popup_on_privmsg_explain'] = 'Einige Templates �ffnen neue Fenster, um dich �ber neue private Nachrichten zu benachrichtigen.';
$lang['Hide_user'] = 'Online-Status verstecken';

$lang['Profile_updated'] = 'Dein Profil wurde aktualisiert.';
$lang['Profile_updated_inactive'] = 'Dein Profil wurde aktualisiert. Du hast jedoch wesentliche Details geändert, weswegen dein Account jetzt inaktiv ist. Du wurdest per Mail dar�ber informiert, wie du deinen Account reaktivieren kannst. Falls eine Aktivierung durch den Administrator erforderlich ist, warte bitte, bis die Reaktivierung erfolgt ist.';

$lang['Password_mismatch'] = 'Du musst zweimal das gleiche Passwort eingeben.';
$lang['Current_password_mismatch'] = 'Das aktuelle Passwort stimmt nicht mit dem in der Datenbank �berein.';
$lang['Password_long'] = 'Dein Passwort kann nicht l�nger als 32 Zeichen sein.';
$lang['Username_taken'] = 'Der gew�nschte Benutzername ist leider bereits belegt.';
$lang['Username_invalid'] = 'Der gew�nschte Benutzername enth�lt ein ung�ltiges Sonderzeichen (z. B. \').';
$lang['Username_disallowed'] = 'Der gew�nschte Benutzername wurde vom Administrator gesperrt.';
$lang['Email_taken'] = 'Die angegebene Mailadresse wird bereits von einem anderen Benutzer verwendet.';
$lang['Email_banned'] = 'Die angegebene Mailadresse wurde vom Administrator gesperrt.';
$lang['Email_invalid'] = 'Die angegebene Mailadresse ist ung�ltig.';
$lang['Signature_too_long'] = 'Deine Signatur ist zu lang.';
$lang['Fields_empty'] = 'Du musst alle ben�tigten Felder ausf�llen.';
$lang['Avatar_filetype'] = 'Der Avatar muss im GIF-, JPG- oder PNG-Format sein.';
$lang['Avatar_filesize'] = 'Die Dateigröße muss kleiner als %d KB sein.';// followed by xx kB, xx being the size
$lang['Avatar_imagesize'] = 'Der Avatar muss weniger als %d Pixel breit und %d Pixel hoch sein.';

$lang['Welcome_subject'] = 'Willkommen auf %s';
$lang['New_account_subject'] = 'Neuer Benutzeraccount';
$lang['Account_activated_subject'] = 'Account aktiviert';

$lang['Account_added'] = 'Danke f�r die Registrierung, dein Account wurde erstellt. Du kannst dich jetzt mit deinem Benutzernamen und deinem Passwort einloggen.';
$lang['Account_inactive'] = 'Dein Account wurde erstellt. Dieses Forum ben�tigt aber eine Aktivierung, daher wurde ein Activation-Key an deine E-Mail-Adresse gesendet. Bitte �berpr�fe deine Mailbox f�r weitere Informationen.';
$lang['Account_inactive_admin'] = 'Dein Account wurde erstellt. Dieser muss noch durch den Administrator freigeschaltet werden. Du wirst benachrichtigt, wenn dies erfolgt ist.';
$lang['Account_active'] = 'Dein Account wurde aktiviert. Danke f�r die Registrierung.';
$lang['Account_active_admin'] = 'Dein Account wurde jetzt aktiviert.';
$lang['Reactivate'] = 'Account wieder aktivieren!';
$lang['Already_activated'] = 'Dein Account ist bereits aktiv';
$lang['COPPA'] = 'Dein Account wurde erstellt, muss aber zuerst �berpr�ft werden. Mehr Details dazu wurden dir per E-Mail gesendet.';

$lang['Registration'] = 'Einverst�ndniserkl�rung';
$lang['Reg_agreement'] = 'Die Administratoren und Moderatoren dieses Forums bem�hen sich, Beiträge mit fragw�rdigem Inhalt so schnell wie m�glich zu bearbeiten oder ganz zu löschen; aber es ist nicht m�glich, jede einzelne Nachricht zu �berprüfen. Du best�tigst mit Absenden dieser Einverst�ndniserkl�rung, dass du akzeptierst, dass jeder Beitrag in diesem Forum die Meinung seines Urhebers wiedergibt und dass die Administratoren, Moderatoren und Betreiber dieses Forums nur f�r ihre eigenen Beiträge verantwortlich sind.<br><br>Du verpflichtest dich, keine beleidigenden, obsz�nen, vulg�ren, verleumderischen, gewaltverherrlichenden oder aus anderen Gr�nden strafbare Inhalte in diesem Forum zu ver�ffentlichen. Verst��e gegen diese Regel f�hren zu sofortiger und permanenter Sperrung. Die Betreiber behalten sich vor, Verbindungsdaten u. �. an die strafverfolgenden Beh�rden weiterzugeben. Du r�umst den Betreibern, Administratoren und Moderatoren dieses Forums das Recht ein, Beiträge nach eigenem Ermessen zu entfernen, zu bearbeiten, zu verschieben oder zu sperren. Du stimmst zu, dass die im Rahmen der Registrierung erhobenen Daten in einer Datenbank gespeichert werden.<br><br>Dieses System verwendet Cookies, um Informationen auf deinem Computer zu speichern. Diese Cookies enthalten keine der oben angegebenen Informationen, sondern dienen ausschlie�lich dem Bedienungskomfort. Deine Mail-Adresse wird nur zur Best�tigung der Registrierung und ggf. zum Versand eines neuen Passwortes verwendet.<br><br>Durch das Abschlie�en der Registrierung stimmst du diesen Nutzungsbedingungen zu.';

$lang['Agree_under_13'] = 'Ich bin mit den Konditionen dieses Forums einverstanden und <b>unter</b> 12 Jahre alt.';
$lang['Agree_over_13'] = 'Ich bin mit den Konditionen dieses Forums einverstanden und <b>�ber</b> oder <b>exakt</b> 12 Jahre alt.';
$lang['Agree_not'] = 'Ich bin mit den Konditionen nicht einverstanden.';

$lang['Wrong_activation'] = 'Der Aktivierungsschl�ssel aus dem Link stimmt nicht mit dem in der Datenbank gespeicherten �berein. Bitte �berpr�fe die URL, und versuche es erneut.';
$lang['Send_password'] = 'Schickt mir ein neues Passwort.';
$lang['Password_updated'] = 'Ein neues Passwort wurde erstellt, es wurde eine E-Mail mit weiteren Anweisungen verschickt.';
$lang['No_email_match'] = 'Die angegebene E-Mail-Adresse stimmt nicht mit der f�r den Benutzernamen gespeicherten �berein.';
$lang['New_password_activation'] = 'Aktivierung des neuen Passwortes';
$lang['Password_activated'] = 'Dein Account wurde wieder aktiviert. Um dich einzuloggen, benutze das Passwort, welches du per E-Mail erhalten hast.';

$lang['Send_email_msg'] = 'E-Mail senden';
$lang['No_user_specified'] = 'Es wurde kein Benutzer ausgewählt';
$lang['User_prevent_email'] = 'Dieser Benutzer hat den E-Mail-Empfang deaktiviert. Bitte versuche es mit einer privaten Nachricht.';
$lang['User_not_exist'] = 'Dieser Benutzer existiert nicht.';
$lang['CC_email'] = 'Eine Kopie dieser E-Mail an dich senden';
$lang['Email_message_desc'] = 'Diese Nachricht wird als Text versendet, verwende bitte deshalb kein HTML oder BBCode. Als Antwort-Adresse der E-Mail wird deine Adresse angegeben.';
$lang['Flood_email_limit'] = 'Im Moment kannst du keine weiteren E-Mails versenden. Versuch es sp�ter noch einmal.';
$lang['Recipient'] = 'Empf�nger';
$lang['Email_sent'] = 'E-Mail wurde gesendet';
$lang['Send_email'] = 'E-Mail senden';
$lang['Empty_subject_email'] = 'Du musst einen Titel f�r diese E-Mail angeben.';
$lang['Empty_message_email'] = 'Du musst einen Text zur E-Mail angeben.';


//
// Visual confirmation system strings
//
$lang['Confirm_code_wrong'] = 'Der eingegebene Best�tigungs-Code war nicht richtig';
$lang['Too_many_registers'] = 'Du hast die zul�ssige Zahl von Registrierungs-Versuchen f�r diese Sitzung �berschritten. Bitte versuche es sp�ter erneut.';
$lang['Confirm_code_impaired'] = 'Wenn du eine Sehschw�che hast oder aus einem anderen Grund den Code nicht lesen kannst, kontaktiere bitte den %sAdministrator%s f�r Hilfe.';
$lang['Confirm_code'] = 'Best�tigungs-Code';
$lang['Confirm_code_explain'] = 'Gebe den Code exakt so ein, wie du ihn siehst. Der Code unterscheidet zwischen Gro�- und Kleinschreibung, die Null hat im Inneren einen schr�gen Strich.';



//
// Memberslist
//
$lang['Select_sort_method'] = 'Sortierungs-Methode auswählen';
$lang['Sort'] = 'Sortieren';
$lang['Sort_Top_Ten'] = 'Top-Ten-Autoren';
$lang['Sort_Joined'] = 'Anmeldedatum';
$lang['Sort_Username'] = 'Benutzername';
$lang['Sort_Location'] = 'Ort';
$lang['Sort_Posts'] = 'Beiträge total';
$lang['Sort_Email'] = 'E-Mail';
$lang['Sort_Website'] = 'Website';
$lang['Sort_Ascending'] = 'Aufsteigend';
$lang['Sort_Descending'] = 'Absteigend';
$lang['Order'] = 'Ordnung';


//
//	Gruppen neu
//
$lang['group_request']		= 'Eine Anfrage zum Beitritt in diese Gruppe wurde erstellt.';
$lang['group_request_msg']	= 'Hallo %s!<br><br>Ein Benutzer m�chte einer Gruppe auf %s beitreten, bei der du der Moderator bist.<br>Um dieser Bitte zu ent- oder widersprechen, klicke auf den folgenden Link:<br> %shier klicken%s<br><br>%s';

//
// Group control panel
//
$lang['Group_Control_Panel'] = 'Gruppen-Kontrolle';
$lang['Group_member_details'] = 'Details zur Gruppen-Mitgliedschaft';
$lang['Group_member_join'] = 'Gruppe beitreten';

$lang['Group_Information'] = 'Information';
$lang['Group_name'] = 'Name';
#$lang['group_desc'] = 'Beschreibung';
$lang['Group_membership'] = 'Gruppen-Mitgliedschaft';
$lang['Group_Members'] = 'Gruppen-Mitglieder';
$lang['Group_Moderator'] = 'Gruppen-Moderatoren';
$lang['Pending_members'] = 'Wartende Mitglieder';

$lang['Group_type'] = 'Gruppentyp';
$lang['Group_open'] = 'Offene Gruppe';
$lang['Group_quest'] = 'Anfrage Gruppe';
$lang['Group_closed'] = 'Geschlossene Gruppe';
$lang['Group_hidden'] = 'Versteckte Gruppe';
$lang['Group_system'] = 'System Gruppe';

$lang['Current_memberships'] = 'Aktuelle Mitgliedschaften';
$lang['Non_member_groups'] = 'Gruppen ohne deine Mitgliedschaft';
$lang['Memberships_pending'] = 'Warten auf Mitgliedschaft';

$lang['No_groups_exist'] = 'Es existieren keine Gruppen';
$lang['Group_not_exist'] = 'Diese Gruppe existiert nicht';

$lang['group_no_members'] = 'Diese Gruppe hat keine Mitglieder.';
$lang['group_no_moderators'] = 'Diese Gruppe hat keine Moderatoren.';

$lang['group_msg_open'] = 'Du wurdest erfolgreich bei dieser Gruppe angemeldet.';
$lang['group_msg_request'] = 'Du wurdest erfolgreich bei dieser Gruppe angemeldet.<br>Du wirst benachrichtigt, wenn der Gruppenmoderator deine Mitgliedschaft akzeptiert hat.';

$lang['Join_group'] = 'Gruppe beitreten';
$lang['No_group_members'] = 'Diese Gruppe hat keine Mitglieder.';
$lang['Group_hidden_members'] = 'Diese Gruppe ist versteckt, du kannst keine Mitgliedschaften anzeigen.';
$lang['No_pending_group_members'] = 'Diese Gruppe hat keine wartenden Mitglieder.';
$lang['Group_joined'] = 'Du wurdest erfolgreich bei dieser Gruppe angemeldet.<br>Du wirst benachrichtigt, wenn der Gruppenmoderator deine Mitgliedschaft akzeptiert hat.';
$lang['Group_request'] = 'Eine Anfrage zum Beitritt in diese Gruppe wurde erstellt.';
$lang['Group_approved'] = 'Deine Anfrage wurde akzeptiert.';
$lang['Group_added'] = 'Du bist dieser Gruppe beigetreten.';
$lang['Already_member_group'] = 'Du bist bereits Mitglied dieser Gruppe.';
$lang['User_is_member_group'] = 'Dieser Benutzer ist bereits ein Mitglied dieser Gruppe.';
$lang['Group_type_updated'] = 'Gruppentyp wurde erfolgreich aktualisiert.';

$lang['Could_not_add_user'] = 'Der ausgewählte Benutzer existiert nicht.';
$lang['Could_not_anon_user'] = 'Ein anonymer Benutzer kann kein Gruppenmitglied werden.';

$lang['Confirm_unsub'] = 'Bist du sicher, dass du die Mitgliedschaft in dieser Gruppe beenden m�chtest?';
$lang['Confirm_unsub_pending'] = 'Deine Anmeldung bei der Gruppe wurde noch nicht best�tigt, m�chtest du wirklich austreten?';

$lang['Unsub_success'] = 'Du wurdest aus dieser Gruppe abgemeldet.';

$lang['Approve_selected'] = 'Ausgew�hlte akzeptieren';
$lang['Deny_selected'] = 'Ausgew�hlte löschen';
$lang['Not_logged_in'] = 'Du musst eingeloggt sein, um einer Gruppe beizutreten.';
$lang['Remove_selected'] = 'Ausgew�hlte entfernen';
$lang['Add_member'] = 'Mitglied hinzufügen';
$lang['Not_group_moderator'] = 'Du bist nicht der Moderator dieser Gruppe. Daher kannst du diese Aktion nicht durchf�hren.';

$lang['Login_to_join'] = 'Einloggen, um Gruppe zu verwalten';
$lang['This_open_group'] = 'Dies ist eine offene Gruppe. Du wirst automatisch eingetragen.';
$lang['This_request_group'] = 'Dies ist eine Anfrage Gruppe. Du kannst eine Mitgliedschaft beantragen.';
$lang['This_closed_group'] = 'Dies ist eine geschlossene Gruppe, keine weiteren Mitglieder werden akzeptiert.';
$lang['This_hidden_group'] = 'Dies ist eine versteckte Gruppe, automatische Anmeldungen werden nicht akzeptiert.';
$lang['This_system_group'] = 'Dies ist eine System Gruppe, automatische An/Abmeldungen werden nicht akzeptiert.';
$lang['Member_this_group'] = 'Du bist ein Mitglied dieser Gruppe.';
$lang['Pending_this_group'] = 'Du wartest auf eine Mitgliedschaft in dieser Gruppe.';
$lang['Are_group_moderator'] = 'Du bist der Moderator dieser Gruppe.';
$lang['None'] = 'Keine';

$lang['Subscribe'] = 'Anmelden';
$lang['Unsubscribe'] = 'Abmelden';
$lang['View_Information'] = 'Information anzeigen';


//
// Search
//
$lang['Search_query'] = 'Suchabfrage';
$lang['Search_options'] = 'Suchoptionen';

$lang['Search_keywords'] = 'Nach Begriffen suchen';
$lang['Search_keywords_explain'] = 'Du kannst <u>AND</u> benutzen, um W�rter zu definieren, die vorkommen m�ssen; <u>OR</u> kannst du benutzen f�r W�rter, die im Resultat sein k�nnen und <u>NOT</u> f�r W�rter, die im Ergebnis nicht vorkommen sollen. Das *-Zeichen kannst du als Platzhalter benutzen.';
$lang['Search_author'] = 'Nach Autor suchen';
$lang['Search_author_explain'] = 'Benutze das *-Zeichen als Platzhalter';

$lang['Search_for_any'] = 'Nach irgendeinem Wort suchen';
$lang['Search_for_all'] = 'Nach allen W�rtern suchen';
$lang['Search_title_msg'] = 'Titel und Text durchsuchen';
$lang['Search_msg_only'] = 'Nur Nachrichtentext durchsuchen';

$lang['Return_first'] = 'Die ersten ';// followed by xxx characters in a select box
$lang['characters_posts'] = 'Zeichen des Beitrags anzeigen';

$lang['Search_previous'] = 'Durchsuchen';// followed by days, weeks, months, year, all in a select box

$lang['Sort_by'] = 'Sortieren nach';
$lang['Sort_Time'] = 'Zeit';
$lang['Sort_Post_Subject'] = 'Titel des Beitrags';
$lang['Sort_Topic_Title'] = 'Titel des Themas';
$lang['Sort_Author'] = 'Autor';
$lang['Sort_Forum'] = 'Forum';

$lang['Display_results'] = 'Ergebnis anzeigen als';
$lang['All_available'] = 'Alle';
$lang['No_searchable_forums'] = 'Du hast nicht die Berechtigung, dieses Forum zu durchsuchen.';

$lang['No_search_match'] = 'Keine Beiträge entsprechen deinen Kriterien.';
$lang['Found_search_match'] = 'Die Suche hat %d Ergebnis ergeben.';// eg. Search found 1 match
$lang['Found_search_matches'] = 'Die Suche hat %d Ergebnisse ergeben.';// eg. Search found 24 matches
$lang['Search_Flood_Error'] = 'Du kannst keine weitere Suche so schnell nach deiner letzten durchf�hren. Bitte versuche es in K�rze erneut.';

$lang['Close_window'] = 'Fenster schlie�en';


//
// Auth related entries
//
// Note the %s will be replaced with one of the following 'user' arrays
$lang['Sorry_auth_announce'] = 'Ank�ndigungen k�nnen in diesem Forum nur von %s erstellt werden.';
$lang['Sorry_auth_sticky'] = 'Wichtige Nachrichten k�nnen in diesem Forum nur von %s erstellt werden.';
$lang['Sorry_auth_read'] = 'Nur %s haben die Berechtigung, in diesem Forum Beiträge zu lesen.';
$lang['Sorry_auth_post'] = 'Nur %s haben die Berechtigung, in diesem Forum Beiträge zu erstellen.';
$lang['Sorry_auth_reply'] = 'Nur %s haben die Berechtigung, in diesem Forum auf Beiträge zu antworten.';
$lang['Sorry_auth_edit'] = 'Nur %s haben die Berechtigung, in diesem Forum Beiträge zu bearbeiten.';
$lang['Sorry_auth_delete'] = 'Nur %s haben die Berechtigung, in diesem Forum Beiträge zu löschen.';
$lang['Sorry_auth_vote'] = 'In diesem Forum k�nnen sich nur %s an Abstimmungen beteiligen.';

// These replace the %s in the above strings
$lang['auth_anonymous_users']		= '<b>anonyme Benutzer</b>';
$lang['auth_registered_users']		= '<b>registrierte Benutzer</b>';
$lang['auth_trial_users']			= '<b>Trail Member</b>';
$lang['auth_member_users']			= '<b>Member</b>';
$lang['auth_users_granted_access']	= '<b>Benutzer mit speziellen Rechten</b>';
$lang['auth_moderators']			= '<b>Moderatoren</b>';
$lang['auth_administrators']		= '<b>Administratoren</b>';

$lang['Not_Moderator'] = 'Du bist nicht Moderator dieses Forums.';
$lang['Not_Authorised'] = 'Nicht berechtigt';

$lang['You_been_banned'] = 'Du wurdest von diesem Forum verbannt.<br>Kontaktiere den Administrator, um mehr Informationen zu erhalten.';


//
// Viewonline
//
$lang['Reg_users_zero_online'] = 'Es sind kein registrierter und ';// There are 5 Registered and
$lang['Reg_users_online'] = 'Es sind %d registrierte und ';
$lang['Reg_user_online'] = 'Es ist ein registrierter und ';// There are 5 Registered and
$lang['Hidden_users_zero_online'] = 'kein versteckter Benutzer online.';// 6 Hidden users online
$lang['Hidden_users_online'] = '%d versteckte Benutzer online.';// 6 Hidden users online
$lang['Hidden_user_online'] = 'ein versteckter Benutzer online.';// 6 Hidden users online
$lang['Guest_users_online'] = 'Es sind %d G�ste online.';
$lang['Guest_users_zero_online'] = 'Es sind keine G�ste online.';// There are 10 Guest users online
$lang['Guest_user_online'] = 'Es ist ein Gast online.';
$lang['No_users_browsing'] = 'Im Moment sind keine Benutzer im Forum.';

$lang['Online_explain'] = 'Diese Daten zeigen an, wer in den letzten 5 Minuten online war.';

$lang['Forum_Location'] = 'Welche Seite';
$lang['Last_updated'] = 'Zuletzt aktualisiert';

$lang['Forum_index'] = 'Forum-Index';
$lang['Logging_on'] = 'Einloggen';
$lang['Posting_message'] = 'Nachricht schreiben';
$lang['Searching_forums'] = 'Foren durchsuchen';
$lang['Viewing_profile'] = 'Profil anzeigen';
$lang['Viewing_online'] = 'Anzeigen, wer online ist';
$lang['Viewing_member_list'] = 'Mitgliederliste anzeigen';
$lang['Viewing_priv_msgs'] = 'Private Nachrichten anzeigen';
$lang['Viewing_FAQ'] = 'FAQ anzeigen';


//
// Moderator Control Panel
//
$lang['Mod_CP'] = 'Moderator Control Panel';
$lang['Mod_CP_explain'] = 'Mit dem unteren Menü kannst du mehrere Moderatoren-Operationen gleichzeitig ausf�hren. Du kannst Beiträge �ffnen, schlie�en, löschen oder verschieben.';

$lang['Select'] = 'Auswählen';
$lang['Delete'] = 'Löschen';
$lang['Move'] = 'Verschieben';
$lang['Lock'] = 'Sperren';
$lang['Unlock'] = 'Entsperren';

$lang['Topics_Removed'] = 'Die gew�hlten Themen wurden erfolgreich gelöscht.';
$lang['Topics_Locked'] = 'Die gew�hlten Themen wurden erfolgreich gesperrt.';
$lang['Topics_Moved'] = 'Die gew�hlten Themen wurden verschoben.';
$lang['Topics_Unlocked'] = 'Die gew�hlten Themen wurden entsperrt.';
$lang['No_Topics_Moved'] = 'Es wurden keine Themen verschoben.';

$lang['Confirm_delete_topic'] = 'Bist du sicher, dass die gew�hlten Themen entfernt werden sollen?';
$lang['Confirm_lock_topic'] = 'Bist du sicher, dass die gew�hlten Themen gesperrt werden sollen?';
$lang['Confirm_unlock_topic'] = 'Bist du sicher, dass die gew�hlten Themen entsperrt werden sollen?';
$lang['Confirm_move_topic'] = 'Bist du sicher, dass die gew�hlten Themen verschoben werden sollen?';

$lang['Move_to_forum'] = 'Verschieben nach';
$lang['Leave_shadow_topic'] = 'Shadow Topic im alten Forum lassen';

$lang['Split_Topic'] = 'Split Topic Control Panel';
$lang['Split_Topic_explain'] = 'Mit den Eingabefeldern unten kannst du ein Thema in zwei teilen, indem du entweder die Beiträge manuell ausw�hlst oder ab einem gew�hlten Beitrag teilst';
$lang['Split_title'] = 'Titel des neuen Themas';
$lang['Split_forum'] = 'Forum des neuen Themas';
$lang['Split_posts'] = 'Gew�hlte Beiträge teilen';
$lang['Split_after'] = 'Ab gew�hltem Beitrag teilen';
$lang['Topic_split'] = 'Das gew�hlte Thema wurde erfolgreich geteilt';

$lang['Too_many_error'] = 'Du hast zu viele Beiträge ausgewählt. Du kannst nur einen Beitrag auswählen, ab dem geteilt werden soll!';

$lang['None_selected'] = 'Du hast keine Themen ausgewählt, auf denen diese Aktion ausgef�hrt werden soll. Bitte w�hle mindestens eines aus.';
$lang['New_forum'] = 'Neues Forum';

$lang['This_posts_IP'] = 'IP-Adresse f�r diesen Beitrag';
$lang['Other_IP_this_user'] = 'Andere IP-Adressen, von denen dieser Benutzer geschrieben hat';
$lang['Users_this_IP'] = 'Beiträge von dieser IP-Adresse';
$lang['IP_info'] = 'IP-Information';
$lang['Lookup_IP'] = 'IP nachschlagen';


//
// Timezones ... for display on each page
//
$lang['All_times'] = 'Alle Zeiten sind %s';// eg. All times are GMT - 12 Hours (times from next block)

$lang['-12'] = 'GMT - 12 Stunden';
$lang['-11'] = 'GMT - 11 Stunden';
$lang['-10'] = 'GMT - 10 Stunden';
$lang['-9'] = 'GMT - 9 Stunden';
$lang['-8'] = 'GMT - 8 Stunden';
$lang['-7'] = 'GMT - 7 Stunden';
$lang['-6'] = 'GMT - 6 Stunden';
$lang['-5'] = 'GMT - 5 Stunden';
$lang['-4'] = 'GMT - 4 Stunden';
$lang['-3.5'] = 'GMT - 3.5 Stunden';
$lang['-3'] = 'GMT - 3 Stunden';
$lang['-2'] = 'GMT - 2 Stunden';
$lang['-1'] = 'GMT - 1 Stunden';
$lang['0'] = 'GMT';
$lang['1'] = 'GMT + 1 Stunde';
$lang['2'] = 'GMT + 2 Stunden';
$lang['3'] = 'GMT + 3 Stunden';
$lang['3.5'] = 'GMT + 3.5 Stunden';
$lang['4'] = 'GMT + 4 Stunden';
$lang['4.5'] = 'GMT + 4.5 Stunden';
$lang['5'] = 'GMT + 5 Stunden';
$lang['5.5'] = 'GMT + 5.5 Stunden';
$lang['6'] = 'GMT + 6 Stunden';
$lang['6.5'] = 'GMT + 6.5 Stunden';
$lang['7'] = 'GMT + 7 Stunden';
$lang['8'] = 'GMT + 8 Stunden';
$lang['9'] = 'GMT + 9 Stunden';
$lang['9.5'] = 'GMT + 9.5 Stunden';
$lang['10'] = 'GMT + 10 Stunden';
$lang['11'] = 'GMT + 11 Stunden';
$lang['12'] = 'GMT + 12 Stunden';
$lang['13'] = 'GMT + 13 Stunden';

/* These are displayed in the timezone select box */
/* by phpBB2 */
/* mody by script */
$lang = array_merge($lang, array(
	'tz' => array(
		'-12' => 'GMT - 12 Stunden',
		'-11' => 'GMT - 11 Stunden',
		'-10' => 'GMT - 10 Stunden',
		'-9' => 'GMT - 9 Stunden',
		'-8' => 'GMT - 8 Stunden',
		'-7' => 'GMT - 7 Stunden',
		'-6' => 'GMT - 6 Stunden',
		'-5' => 'GMT - 5 Stunden',
		'-4' => 'GMT - 4 Stunden',
		'-3.5' => 'GMT - 3.5 Stunden',
		'-3' => 'GMT - 3 Stunden',
		'-2' => 'GMT - 2 Stunden',
		'-1' => 'GMT - 1 Stunden',
		'0' => 'GMT',
		'1' => 'GMT + 1 Stunde',
		'2' => 'GMT + 2 Stunden',
		'3' => 'GMT + 3 Stunden',
		'3.5' => 'GMT + 3.5 Stunden',
		'4' => 'GMT + 4 Stunden',
		'4.5' => 'GMT + 4.5 Stunden',
		'5' => 'GMT + 5 Stunden',
		'5.5' => 'GMT + 5.5 Stunden',
		'6' => 'GMT + 6 Stunden',
		'6.5' => 'GMT + 6.5 Stunden',
		'7' => 'GMT + 7 Stunden',
		'8' => 'GMT + 8 Stunden',
		'9' => 'GMT + 9 Stunden',
		'9.5' => 'GMT + 9.5 Stunden',
		'10' => 'GMT + 10 Stunden',
		'11' => 'GMT + 11 Stunden',
		'12' => 'GMT + 12 Stunden',
		'13' => 'GMT + 13 Stunden',
	),
));

$lang = array_merge($lang, array(
	'state' => array(
		'all'	=> 'alle Bundesländer',
		'bw'	=> 'Baden-Württemberg',
		'by'	=> 'Bayern',
		'be'	=> 'Berlin',
		'bb'	=> 'Brandenburg',
		'hb'	=> 'Bremen',
		'hh'	=> 'Hamburg',
		'he'	=> 'Hessen',
		'mv'	=> 'Mecklenburg-Vorpommern',
		'ni'	=> 'Niedersachsen',
		'nw'	=> 'Nordrhein-Westfalen',
		'rp'	=> 'Rheinland-Pfalz',
		'sl'	=> 'Saarland',
		'sn'	=> 'Sachsen',
		'st'	=> 'Sachsen-Anhalt',
		'sh'	=> 'Schleswig-Holstein',
		'th'	=> 'Thüringen',
	),
));

$lang['datetime']['Sunday'] = 'Sonntag';
$lang['datetime']['Monday'] = 'Montag';
$lang['datetime']['Tuesday'] = 'Dienstag';
$lang['datetime']['Wednesday'] = 'Mittwoch';
$lang['datetime']['Thursday'] = 'Donnerstag';
$lang['datetime']['Friday'] = 'Freitag';
$lang['datetime']['Saturday'] = 'Samstag';
$lang['datetime']['Sun'] = 'So';
$lang['datetime']['Mon'] = 'Mo';
$lang['datetime']['Tue'] = 'Di';
$lang['datetime']['Wed'] = 'Mi';
$lang['datetime']['Thu'] = 'Do';
$lang['datetime']['Fri'] = 'Fr';
$lang['datetime']['Sat'] = 'Sa';
$lang['datetime']['January'] = 'Januar';
$lang['datetime']['February'] = 'Februar';
$lang['datetime']['March'] = 'M�rz';
$lang['datetime']['April'] = 'April';
$lang['datetime']['May'] = 'Mai';
$lang['datetime']['June'] = 'Juni';
$lang['datetime']['July'] = 'Juli';
$lang['datetime']['August'] = 'August';
$lang['datetime']['September'] = 'September';
$lang['datetime']['October'] = 'Oktober';
$lang['datetime']['November'] = 'November';
$lang['datetime']['December'] = 'Dezember';
$lang['datetime']['month_01'] = 'Januar';
$lang['datetime']['month_02'] = 'Februar';
$lang['datetime']['month_03'] = 'März';
$lang['datetime']['month_04'] = 'April';
$lang['datetime']['month_05'] = 'Mai';
$lang['datetime']['month_06'] = 'Juni';
$lang['datetime']['month_07'] = 'Juli';
$lang['datetime']['month_08'] = 'August';
$lang['datetime']['month_09'] = 'September';
$lang['datetime']['month_10'] = 'Oktober';
$lang['datetime']['month_11'] = 'November';
$lang['datetime']['month_12'] = 'Dezember';
$lang['datetime']['Jan'] = 'Jan';
$lang['datetime']['Feb'] = 'Feb';
$lang['datetime']['Mar'] = 'M�rz';
$lang['datetime']['Apr'] = 'Apr';
$lang['datetime']['May'] = 'Mai';
$lang['datetime']['Jun'] = 'Jun';
$lang['datetime']['Jul'] = 'Jul';
$lang['datetime']['Aug'] = 'Aug';
$lang['datetime']['Sep'] = 'Sep';
$lang['datetime']['Oct'] = 'Okt';
$lang['datetime']['Nov'] = 'Nov';
$lang['datetime']['Dec'] = 'Dez';

//
// Errors (not related to a
// specific failure on a page)
//
$lang['Information'] = 'Information';
$lang['Critical_Information'] = 'Kritische Information';

$lang['General_Error'] = 'Allgemeiner Fehler';
$lang['Critical_Error'] = 'Kritischer Fehler';
$lang['An_error_occured'] = 'Ein Fehler ist aufgetreten.';
$lang['A_critical_error'] = 'Ein kritischer Fehler ist aufgetreten.';

//$lang['Admin_reauthenticate'] = 'F�r den Zugriff auf den Administrations-Bereich musst du deinen Benutzernamen und dein Passwort erneut eingeben.';
$lang['Admin_reauthenticate'] = 'Benutzernamen und dein Passwort erneut eingeben!';
$lang['Login_attempts_exceeded'] = 'Die maximale Anzahl von %s zul�ssigen Login-Versuchen wurde �berschritten. Du kannst dich in den n�chsten %s Minuten nicht einloggen.';
$lang['Please_remove_install_contrib'] = 'Bitte stelle sicher, dass du die Verzeichnisse install/ gelöscht hast.';

$lang['Session_invalid'] = 'Ung�ltige Sitzung. Bitte sende das Formular erneut ab.';

$lang['Stat_surround'] = '[ %s ]';
$lang['Stat_sep'] = ' - ';
$lang['Stat_page_duration'] = 'Time: %.4fs';
$lang['Stat_local_duration'] = 'local trace: %.4fs';
$lang['Stat_part_php'] = 'PHP: %.2d%%';
$lang['Stat_part_sql'] = 'SQL: %.2d%%';
$lang['Stat_queries'] = 'Queries: %2d (%.4fs)';
$lang['Stat_gzip_enable'] = 'GZIP on';
$lang['Stat_debug_enable'] = 'Debug on';
$lang['Stat_request'] = 'Request';
$lang['Stat_line'] = 'Line:&nbsp;%d';
$lang['Stat_cache'] = 'cache:&nbsp;%.4fs';
$lang['Stat_dur'] = 'dur.:&nbsp;%.4fs';
$lang['Stat_table'] = 'Table';
$lang['Stat_type'] = 'Type';
$lang['Stat_possible_keys'] = 'Possible keys';
$lang['Stat_key'] = 'Used key';
$lang['Stat_key_len'] = 'Key length';
$lang['Stat_ref'] = 'Ref.';
$lang['Stat_rows'] = 'Rows';
$lang['Stat_Extra'] = 'Comment';
$lang['Stat_Comment'] = 'Comment';
$lang['Stat_id'] = 'Id';
$lang['Stat_select_type'] = 'Select type';

// debug
$lang['dbg_line'] = 'Line: %s';
$lang['dbg_file'] = 'File: %s';
$lang['dbg_empty'] = 'Empty';



$lang['required']		= "Mit * markierte Felder sind erforderlich!";


$lang['access_denied']	= 'Keine Berechtigung für diese Seite.';



//
//	Header Title
//
$lang['head_cash']		= 'Clankasse';

$lang['ary_month'] = array(
	'01'	=> 'Januar',
	'02'	=> 'Februar',
	'03'	=> 'M�rz',
	'04'	=> 'April',
	'05'	=> 'Mai',
	'06'	=> 'Juni',
	'07'	=> 'Juli',
	'08'	=> 'August',
	'09'	=> 'September',
	'10'	=> 'Oktober',
	'11'	=> 'November',
	'12'	=> 'Dezember'
);



$lang['main_change'] = array(
	'cl_added'		=> 'Hinzugefügt',
	'cl_change'		=> 'Geändert',
	'cl_fixed'		=> 'Beseitigt',
	'cl_improved'	=> 'Verbessert',
	'cl_removed'	=> 'Entfernt',	
);

#
#	Gallerie / Gallery
#
$lang['gallery']			= 'Galerie';
$lang['gallery_overview']	= 'Galerie�bersicht von: %s';

$lang['sprintf_pic']		= '%s Bilder';


$lang['gallery_rule_can_edit']		= 'Du <b>kannst</b> Bilder bearbeiten.';
$lang['gallery_rule_cannot_edit']	= 'Du <b>kannst keine</b> Bilder bearbeiten.';
$lang['gallery_rule_can_delete']	= 'Du <b>kannst</b> Bilder löschen.';
$lang['gallery_rule_cannot_delete']	= 'Du <b>kannst keine</b> Bilder löschen.';
$lang['gallery_rule_can_rate']		= 'Du <b>kannst</b> Bilder bewerten.';
$lang['gallery_rule_cannot_rate']	= 'Du <b>kannst keine</b> Bilder bewerten.';
$lang['gallery_rule_can_upload']	= 'Du <b>kannst</b> Bilder hochladen.';
$lang['gallery_rule_cannot_upload']	= 'Du <b>kannst keine</b> Bilder hochladen.';

//
// Version Check
//
$lang['Version_up_to_date'] = 'Die Seite ist auf dem neuesten Stand.';
$lang['Version_not_up_to_date'] = 'Seite <b>nicht</b> auf dem neuesten Stand. Es sind Updates verfügbar.';
$lang['Latest_version_info'] = 'Die neueste verfügbare Version ist <b>%s</b>.';
$lang['Current_version_info'] = 'Du verwendest <b>%s</b>.';
$lang['Connect_socket_error'] = 'Die Verbindung zum phpBB-Server konnte nicht aufgebaut werden. Es trat folgender Fehler auf:<br>%s';
$lang['Socket_functions_disabled'] = 'Die Socket-Funktionen konnten nicht benutzt werden.';
$lang['Mailing_list_subscribe_reminder'] = 'Um immer die neuesten Informationen zu Updates von phpBB zu erhalten, solltest du dich <a href="http://www.phpbb.com/support/" target="_new">beim phpBB Newsletter anmelden</a>.';
$lang['Version_information'] = 'Versions-Information';



?>