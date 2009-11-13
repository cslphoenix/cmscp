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
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */


//
// Admin Header
//
$lang['index_head']			= 'Adminbereich von: %s';
$lang['session']			= 'Verlassen';
$lang['logout']				= 'Abmelden';
$lang['index']				= 'Übersicht';
$lang['page']				= 'WebSeite';

//
//	Index
//
$lang['welcome_cms']			= 'Willkommen im Adminbereichs des Phoenix-Clanportals';
$lang['welcome_cms_explain']	= 'Text bla blub bla ;).';


//
// Menue
//
$lang['contact']			= 'Kontakt';
$lang['contact_over']		= 'Kontakt - Übersicht';	
$lang['event']				= 'Event';
$lang['fighus']				= 'FightUs';
$lang['forums']				= 'Forum';
$lang['games_over']			= 'Spiele';
$lang['gameserver']			= 'Gameserver';
$lang['groups']				= 'Gruppen';
$lang['joinus']				= 'JoinUs';
$lang['log']				= 'Übersicht';
$lang['log_error']			= 'Fehlermeldungen';
$lang['logs']				= 'Protokolle';
$lang['logs_admin']			= 'Protokoll - Admin';
$lang['logs_member']		= 'Protokoll - Member';	
$lang['logs_user']			= 'Protokoll - User';
$lang['main']				= 'Allgemein';
$lang['match']				= 'Begegnungen';
$lang['match_over']			= 'Übersicht';
$lang['navi']				= 'Navigation';
$lang['network']			= 'Network';
$lang['news']				= 'News';
$lang['news']				= 'News';
$lang['newscat']			= 'Newskategorie';
$lang['newsletter']			= 'Newsletter';
$lang['permissions']		= 'Befugnisse';	
$lang['permissions_list']	= 'Befugnisse Übersicht';		
$lang['ranks']				= 'Ränge';
$lang['ranks_over']			= 'Ränge';
$lang['server']				= 'Server';
$lang['set']				= 'Einstellungen';
$lang['set_ftp']			= 'FTP-Rechte';
$lang['teams']				= 'Teams';
$lang['teams']				= 'Teams';
$lang['teams_over']			= 'Übersicht';
$lang['training']			= 'Training';
$lang['users']				= 'Benutzer';

$lang['gallery']			= 'Galerie';



//
//	Berechtigung
//
$lang['auth_guest']			= 'Gast';
$lang['auth_user']			= 'Benutzer';
$lang['auth_trial']			= 'Trial';
$lang['auth_member']		= 'Member';
$lang['auth_mod']			= 'Moderator';
$lang['auth_admin']			= 'Administrator';

$lang['auth_cash']			= 'Clankasse';
$lang['auth_contact']		= 'Kontakt';	
$lang['auth_event']			= 'Event';
$lang['auth_fightus']		= 'Fightus';	
$lang['auth_forum']			= 'Forum';
$lang['auth_forum_auth']	= 'Forumberechtigung';
$lang['auth_gallery']		= 'Gallery';
$lang['auth_games']			= 'Spiele';
$lang['auth_groups']		= 'Gruppen';	
$lang['auth_groups_perm']	= 'Gruppen Befugnisse';		
$lang['auth_joinus']		= 'Joinus';	
$lang['auth_match']			= 'Match/Wars';
$lang['auth_navi']			= 'Navigation';
$lang['auth_network']		= 'Network';	
$lang['auth_news']			= 'News';
$lang['auth_news_public']	= 'News veröffentlichen';		
$lang['auth_newscat']		= 'News Kategorien';	
$lang['auth_newsletter']	= 'Newsletter';		
$lang['auth_ranks']			= 'Ränge';
$lang['auth_server']		= 'Server';	
$lang['auth_teams']			= 'Teams';
$lang['auth_teamspeak']		= 'Teamspeak';	
$lang['auth_training']		= 'Training';	
$lang['auth_user']			= 'Benutzer';
$lang['auth_user_perm']		= 'Benutzer Befugnisse';	

$lang['auths'] = array(
	'auth_cash'			=> $lang['auth_cash'],
	'auth_contact'		=> $lang['auth_contact'],
	'auth_event'		=> $lang['auth_event'],	
	'auth_fightus'		=> $lang['auth_fightus'],	
	'auth_forum'		=> $lang['auth_forum'],	
	'auth_forum_auth'	=> $lang['auth_forum_auth'],
	'auth_gallery'		=> $lang['auth_gallery'],
	'auth_games'		=> $lang['auth_games'],	
	'auth_groups'		=> $lang['auth_groups'],	
	'auth_groups_perm'	=> $lang['auth_groups_perm'],
	'auth_joinus'		=> $lang['auth_joinus'],	
	'auth_match'		=> $lang['auth_match'],	
	'auth_navi'			=> $lang['auth_navi'],
	'auth_network'		=> $lang['auth_network'],	
	'auth_news'			=> $lang['auth_news'],
	'auth_news_public'	=> $lang['auth_news_public'],
	'auth_newscat'		=> $lang['auth_newscat'],	
	'auth_newsletter'	=> $lang['auth_newsletter'],
	'auth_ranks'		=> $lang['auth_ranks'],	
	'auth_server'		=> $lang['auth_server'],	
	'auth_teams'		=> $lang['auth_teams'],	
	'auth_teamspeak'	=> $lang['auth_teamspeak'],
	'auth_training'		=> $lang['auth_training'],	
	'auth_user'			=> $lang['auth_user'],
	'auth_user_perm'	=> $lang['auth_user_perm'],
);

$lang['auth_gallery_view']		= 'Betrachten';
$lang['auth_gallery_edit']		= 'Bearbeiten';
$lang['auth_gallery_delete']	= 'Löschen';
$lang['auth_gallery_rate']		= 'Bewertung';
$lang['auth_gallery_upload']	= 'Hochladen';

$lang['auth_gallery'] = array(
	'auth_view'		=> $lang['auth_gallery_view'],
	'auth_edit'		=> $lang['auth_gallery_edit'],
	'auth_delete'	=> $lang['auth_gallery_delete'],
	'auth_rate'		=> $lang['auth_gallery_rate'],
	'auth_upload'	=> $lang['auth_gallery_upload'],
);


//
//	Berechtigungsfelder
//
$lang['authlist']			= 'Auth Felder';
$lang['authlist_head']		= 'Berechtigungsfelder Administration';
$lang['authlist_explain']	= 'Hier können Sie die Berechtigungsfelder der Seite verwalten, erleichtert das hinzufügen von Modifikationen die Extra Rechte haben sollen. Bitte nur was verstellen wenn man ein Backup der Datenbank gemacht hat!<br><b>Wichtig:</b> neue Felder werden direkt in der DB eingetragen und das \'auth_\' wird automatisch davor eingetragen!';

$lang['authlist_add']		= 'Neues Feld erstellen';
$lang['authlist_new_add']	= 'Feld hinzufügen';
$lang['authlist_edit']		= 'Feld bearbeiten';

$lang['authlist_name']		= 'Feld Name';


//
//	Kontakt
//
$lang['contact_overview']			= 'Kontakt Übersicht';
$lang['contact_head_normal']		= 'Kontakt-Einträge';
$lang['contact_head_fightus']		= 'FightUs-Einträge';
$lang['contact_head_joinus']		= 'JoinUs-Einträge';
$lang['contact_normal']				= 'Kontakt';
$lang['contact_fightus']			= 'FightUs';
$lang['contact_joinus']				= 'JoinUs';
$lang['contact_details']			= 'Details';

$lang['contact_type_open']			= 'Offen';
$lang['contact_type_edit']			= 'Bearbeitung';
$lang['contact_type_close']			= 'Geschlossen';


//
//	Downloads
//


//
//	Forumberechtigung
//


//
//	Befugnisse
//
$lang['Public']				= 'Öffentlich';
$lang['Private']			= 'Privat';
$lang['Trial']				= 'Trial';
$lang['Member']				= 'Member';
$lang['Registered']			= 'Registriert';
$lang['Administrators']		= 'Administratoren';
$lang['Hidden']				= 'Versteckt';

$lang['View']				= 'Ansicht';
$lang['Read']				= 'Lesen';
$lang['Post']				= 'Posten';
$lang['Reply']				= 'Antworten';
$lang['Edit']				= 'Editieren';
$lang['Delete']				= 'Löschen';
$lang['Sticky']				= 'Wichtig';
$lang['Announce']			= 'Ankündigung';
$lang['Poll']				= 'Umfrage';
$lang['Pollcreate']			 = 'Umfrage erstellen';

$lang['Forum_ALL']			= 'Alle';
$lang['Forum_REG']			= 'Reg';
$lang['Forum_TRI']			= 'Trial';
$lang['Forum_MEM']			= 'Member';
$lang['Forum_MOD']			= 'Mods';
$lang['Forum_ACL']			= 'Privat';
$lang['Forum_ADM']			= 'Admin';



//
//	Forumberechtigung Liste
//


//
//	Galerie
//
$lang['gallery_head']		= 'Galerie Administration';
$lang['gallery_explain']	= 'Hier kannst Du Galerien verwalten.';
$lang['gallery_name']		= 'Galeriename';

$lang['gallery_add']		= 'Neue Galerie erstellen';
$lang['gallery_new_add']	= 'Galerie hinzufügen';
$lang['gallery_edit']		= 'Galerie bearbeiten';

$lang['gallery_auth']		= 'Galerieberechtigung';
$lang['gallery_desc']		= 'Galeriebeschreibung';
$lang['gallery_comment']	= 'Bildkommentare';
$lang['gallery_rate']		= 'Bildbewertungen';
$lang['gallery_upload']		= 'Upload';

$lang['gallery_link']		= 'Link';
$lang['gallery_partner']	= 'Partner';
$lang['gallery_sponsor']	= 'Sponsor';

$lang['gallery_sprintf_size-pic']	= '%s / %s Bilder';

//
//	Forum
//


//
//	Network (Links/Partner/Sponsor)
//
$lang['network_head']		= 'Network Administration';
$lang['network_explain']	= 'Hier kannst Du Links, Partner und Sponsoren verwalten.';
$lang['network_name']		= 'Networkname';

$lang['network_add']		= 'Neuen %s erstellen';
$lang['network_new_add']	= '%s hinzufügen';
$lang['network_edit']		= '%s bearbeiten';

$lang['network_type']		= 'Network Art';
$lang['network_url']		= 'Network Link';
$lang['network_image']		= 'Network Bild';

$lang['network_link']		= 'Links';
$lang['network_partner']	= 'Partner';
$lang['network_sponsor']	= 'Sponsoren';


//
//	Ereignis (Event)
//
$lang['event_head']			= 'Event Administration';
$lang['event_explain']		= 'Beschreibung';
$lang['event_title']		= 'Eventtitel';

$lang['event_add']			= 'Neues Event erstellen';
$lang['event_new_add']		= 'Event hinzufügen';
$lang['event_edit']			= 'Event bearbeiten';

$lang['event_description']	= 'Eventbeschreibung';
$lang['event_date']			= 'Datum';
$lang['event_level']		= 'Benutzerlevel';
$lang['event_duration']		= 'Dauer';



//
//	Spiele (Games)
//
$lang['game_head']		= 'Spiel Administration';
$lang['game_explain']	= 'Hier kannst du die Spiele der Seite Verwalten. Du kannst bestehende Spiele löschen, editieren oder neue anlegen.';
$lang['game_name']		= 'Spielnamen';

$lang['game_add']			= 'Neues Spiel erstellen';
$lang['game_new_add']		= 'Spiel hinzufügen';
$lang['game_edit']			= 'Spiel bearbeiten';
$lang['game_empty']			= 'Es sind keine Spiele vorhanden.';
$lang['game_title']			= 'Spielname';

$lang['game_size']			= 'Bildgröße';
$lang['game_image']			= 'Spielicon';

$lang['game_update'] = 'Update des Spiels erfolgt.';
$lang['game_create'] = 'Neues Spiel hinzuggefügt.';
$lang['game_update'] = 'Update des Spiels erfolgt.';
$lang['game_empty'] = 'Es sind keine Spiele vorhanden.';

//
//	Gruppen (Groups)
//
$lang['group_head']				= 'Gruppen Administration';
$lang['group_explain']			= 'Hier kannst du die Gruppen Verwalten';
$lang['group_add']				= 'Neue Gruppe erstellen';
$lang['group_new_add']			= 'Gruppe hinzufügen';
$lang['group_edit']				= 'Gruppe bearbeiten';
$lang['group_overview']			= 'Gruppen Rechteübersicht';
$lang['group_overview_explain']	= 'Hier sind alle Gruppen aufgelistet die es gibt, leider aus Platzgründen vorerst immer nur 5!';

$lang['group_add_member']		= 'Mitglieder hinzufügen';
$lang['group_edit_member']		= 'Mitglieder bearbeiten';
$lang['group_view_member']		= 'Mitglieder Übersicht';
$lang['group_view_member']		= 'Mitglieder Übersicht';
$lang['group_add_member_ex']	= 'Hier kannst du, Mitglieder hinzufügen. Entweder Benutzer per Login mit Komma getrennt eintragen, <b>oder</b> über das Dropdown-Menü auswahlen!';

$lang['group_name']			= 'Gruppenname';
$lang['group_mod']			= 'Moderator';
$lang['group_membercount']	= 'Mitgliederanzahl';
$lang['group_access']		= 'Gruppenrechte';
$lang['group_type']			= 'Gruppentyp';
$lang['group_description']	= 'Beschreibung';
$lang['group_color']		= 'Gruppenfarbe';
$lang['group_legend']		= 'Legende';
$lang['group_rank']			= 'Gruppenrang';

$lang['group_auth']			= 'Gruppenrechte';
$lang['group_data']			= 'Gruppendaten';
$lang['group_logo']			= 'Gruppenlogo';

$lang['group_open']		= 'Gruppe: ohne Anfrage';
$lang['group_quest']	= 'Gruppe: mit Anfrage';
$lang['group_closed']	= 'Gruppe: geschlossen';
$lang['group_hidden']	= 'Gruppe: versteckt';
$lang['group_system']	= 'Gruppe: System';

$lang['group_type_opt']		= array(GROUP_OPEN => $lang['group_open'], GROUP_REQUEST => $lang['group_quest'], GROUP_CLOSED => $lang['group_closed'], GROUP_HIDDEN => $lang['group_hidden'], GROUP_SYSTEM => $lang['group_system']);
$lang['group_access_opt']	= array(ADMIN => $lang['auth_admin'], MOD => $lang['auth_mod'], MEMBER => $lang['auth_member'], TRIAL => $lang['auth_trial'], USER => $lang['auth_user']);

$lang['switch_level']		= array(ADMIN => $lang['auth_admin'], MOD => $lang['auth_mod'], MEMBER => $lang['auth_member'], TRIAL => $lang['auth_trial'], USER => $lang['auth_user'], GUEST => $lang['auth_guest']);

$lang['select_group_mod']	= '&raquo; Gruppenmoderator auswählen';



$lang['Group_DEFAULT']		= 'Vorgabe';
$lang['Group_DISALLOWED']	= 'Nein';
$lang['Group_ALLOWED']		= 'Ja';
$lang['Group_SPECIAL']		= 'Spezial';

$lang['Gallery_USER']		= 'Benutzer';
$lang['Gallery_TRIAL']		= 'Trialmember';
$lang['Gallery_MEMBER']		= 'Member';
$lang['Gallery_COLEADER']	= 'Squadleader';
$lang['Gallery_LEADER']		= 'Leader';
$lang['Gallery_UPLOAD']		= 'Uploader';


//
//	Log
//
$lang['log_head']			= 'Log Administration';
$lang['log_explain']		= 'Hier kannst du Logs löschen!';
$lang['log_error']			= 'Fehlermeldungen';

$lang['log_username']		= 'Benutzername';
$lang['log_ip']				= 'IP-Adresse';
$lang['log_time']			= 'Dateum/Zeit';
$lang['log_sektion']		= 'Logbereich';
$lang['log_message']		= 'Lognachricht';
$lang['log_change']			= 'Änderungen';


//
//	Match (Wars)
//
$lang['match_head']				= 'Match Administration';
$lang['match_explain']			= 'Hier können Wars verwaltet werden.';
$lang['match_details_explain']	= 'Hier können die Details für den War eingetragen und verändert werden.';
$lang['match_infos']			= 'Matchinfos';

$lang['match_add']			= 'Neues Match erstellen';
$lang['match_new_add']		= 'Match hinzufügen';
$lang['match_edit']			= 'Match bearbeiten';
$lang['match_update']		= 'Match geändert.';
$lang['match_upcoming']		= 'Anstehende Matches';
$lang['match_expired']		= 'Abgelaufen Matches';

$lang['match_team']			= 'Wechles Team';
$lang['match_type']			= 'XonX';
$lang['match_categorie']	= 'Match Art';
$lang['match_league']		= 'Liga';
$lang['match_league_url']	= 'Liga Link';
$lang['league_match']		= 'Ligamatchlink';
$lang['match_date']			= 'Match Datum';
$lang['match_public']		= 'Match Öffentlich?';
$lang['match_comments']		= 'Kommentare erlauben?';
$lang['match_rival']		= 'Gegner Name';
$lang['match_rival_tag']	= 'Gegner Clantag';
$lang['match_rival_url']	= 'Gegner Homepage';
$lang['match_server']		= 'Server';
$lang['match_serverpw']		= 'Server PW';
$lang['match_hltv']			= 'HLTV-Server';
$lang['match_hltvpw']		= 'HLTV-Server PW';
$lang['match_text']			= 'Match Bericht';
$lang['match_interest_reset'] = 'Teilnahme zurücksetzen?';

$lang['match_map_more']			= 'weitere Map';
$lang['match_map_close']		= 'schließen';

$lang['match_details_maps']		= 'Map Details';
$lang['match_rival_lineup']		= 'Gegner Lineup';
$lang['match_details_map']		= 'Map';
$lang['match_details_mappic']	= 'Mapbild hochladen';
$lang['match_details_points']	= 'Punkte';
$lang['match_details_info']		= 'Wardetails in der Übersicht';
$lang['match_details_comment']	= 'Match Kommentar';
$lang['match_lineup']			= 'Clan Lineup';
$lang['match_lineup_add_yes']	= 'Spieler hinzugefügt';
$lang['match_lineup_del_yes']	= 'Spieler gelöscht';
$lang['match_lineup_add']		= 'Spieler hinzufügen';
$lang['match_lineup_explain']	= 'Spieler einfach mit gedrückter STRG Taste auswählen und Absenden.';
$lang['match_lineup_status']	= 'Spielerstatus';

$lang['match_lineup_no_users']	= 'Bitte Spieler auswählen die noch nicht eingetragen sind.';
$lang['match_lineup_change']	= 'Spielerliste verändert';

$lang['match_lineup_player']	= 'Spieler für das Lineup';


$lang['select_team']		= 'Team auswählen';

$lang['add_train']			= 'hinzufügen';
$lang['edit_train']			= 'ändern';
$lang['match_player']		= 'Spieler';
$lang['match_replace']		= 'Ersatz';


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

$lang['match_details']		= 'Details';


//
//	Navigation
//
$lang['navi_head']		= 'Navigation Administration';
$lang['navi_set']		= 'Subnavieinstellungen';
$lang['navi_explain']	= 'Hier kannst du die Navigation Verwalten';

$lang['navi_add']		= 'Neuen Link erstellen';
$lang['navi_new_add']	= 'Link hinzufügen';
$lang['navi_edit']		= 'Link bearbeiten';

$lang['navi_name']		= 'Navi Name';
$lang['navi_url']		= 'Navi Url';
$lang['navi_type']		= 'Navi Typ';
$lang['navi_language']	= 'Sprachdatei';
$lang['navi_show']		= 'Sichtbar';
$lang['navi_intern']	= 'Intern';
$lang['navi_target']	= 'Ziel';

$lang['navi_main']		= 'Main Navi';
$lang['navi_clan']		= 'Clan Navi';
$lang['navi_com']		= 'Community Navi';
$lang['navi_misc']		= 'Misc Navi';
$lang['navi_user']		= 'Benutzer Navi';

$lang['navi_new']		= 'Neue Seite';
$lang['navi_self']		= 'Selbe Seite';

$lang['subnavi_news_length']	= 'Anzahl der Zeichen vom Nachrichtentitel';	
$lang['subnavi_news_limit']		= 'Anzahl an Nachrichtentiteln';
$lang['subnavi_match_length']	= 'Anzahl der Zeichen vom Name der Gegner';	
$lang['subnavi_match_limit']	= 'Anzahl der Begegnungen';	

$lang['subnavi_user_cache']		= 'Cachedauer in Sekunden';
$lang['subnavi_user_length']	= 'Anzahl der Zeichen vom Benutzername';	
$lang['subnavi_user_limit']		= 'Anzahl an Benutzern';
$lang['subnavi_user_show']		= 'Letzten Neuen Benutzer anzeigen';

$lang['subnavi_teams_show']		= 'Teams anzeigen';
$lang['subnavi_teams_length']	= 'Teamnamenlänge';


//
//	News
//
$lang['news_head']			= 'News Administration';
$lang['news_explain']		= 'Hier kannst du die News Verwalten';
$lang['news_add']			= 'Neue News erstellen';
$lang['news_new_add']		= 'News hinzufügen';
$lang['news_edit']			= 'News bearbeiten';
$lang['news_name']			= 'News Titel';

$lang['news_title']			= 'Newstitel';
$lang['news_category']		= 'Newskategorie';
$lang['news_match']			= 'Match einbinden';
$lang['news_text']			= 'Newstext';
$lang['news_link']			= 'Link URL / Name';
$lang['news_link_explain']	= 'Link beschreibung';
$lang['news_public']		= 'Öffentlich';
$lang['news_public_time']	= 'Zeit & Datum zum veröffentlichen';
$lang['news_intern']		= 'Internnews';
$lang['news_comments']		= 'Kommentare erlauben';
$lang['news_rating']		= 'Newsbewertung';
$lang['news_main']			= 'Main Navi';
$lang['news_clan']			= 'Clan Navi';
$lang['news_com']			= 'Community Navi';
$lang['news_misc']			= 'Misc Navi';
$lang['news_user']			= 'Benutzer Navi';
$lang['news_new']			= 'Neue Seite';
$lang['news_self']			= 'Selbe Seite';

//
//	Newskategorie
//
$lang['newscat_head']		= 'Newskategorie Administration';
$lang['newscat_explain']	= 'Hier kannst du die Newskategorien Verwalten';
$lang['newscat_add']		= 'Neue Newskategorie erstellen';
$lang['newscat_new_add']	= 'Newskategorie hinzufügen';
$lang['newscat_edit']		= 'Newskategorie bearbeiten';
$lang['newscat_name']		= 'Newskategorie Name';
$lang['newscat_title']		= 'Name';
$lang['newscat_image']		= 'Bild';

$lang['newscat_select']		= 'Newskategorie auswählen&nbsp;';


//
//	Newsletter
//
$lang['newsletter_head']		= 'Newsletter Administration';
$lang['newsletter_explain']		= 'Text';

$lang['newsletter_add']			= 'Adresse Eintragen';
$lang['newsletter_edit']		= 'Adresse bearbeiten';
$lang['newsletter_email']		= 'eMail Adressen';
$lang['newsletter_mail']		= 'eMail Adresse';

$lang['newsletter_status_0']	= 'Eingetragen, nicht bestätigt';
$lang['newsletter_status_1']	= 'Bestätigt';
$lang['newsletter_status_2']	= 'Ausgetragen, nicht bestätigt';

$lang['newsletter_type']			= 'Status Mail?';
$lang['newsletter_type_explain']	= 'Der Benutzer bekommt eine Mail zugesendet mit einem Bestätigunslink oder mit der Bestätigung, dass seine Adresse eingetragen wurde.';

$lang['newsletter_semd_new']	= 'Neue eMail zusenden zum Bestätigen';
$lang['newsletter_send_new2']	= 'Erneutes Bestätigen erzwingen';

$lang['newsletter_type_new']	= 'Bestätigungsmail zusenden?';
$lang['newsletter_type_active']	= 'Als Aktiviert eintragen?';

//
//	Profilfelder
//


//
//	Ränge (Ranks)
//
$lang['rank_head']		= 'Rang Administration';
$lang['rank_explain']	= 'Hier kannst du die Ränge der Seite Verwalten. Du kannst bestehende Ränge löschen, editieren oder neue anlegen.';

$lang['rank_add']			= 'Neuen Rang erstellen';
$lang['rank_new_add']		= 'Rang hinzufügen';
$lang['rank_edit']			= 'Rang bearbeiten';
$lang['rank_empty']			= 'Es sind keine Ränge vorhanden.';
$lang['rank_title']			= 'Rangtitle';
$lang['rank_page']			= 'Seitenrang';
$lang['rank_forum']			= 'Forumrang';
$lang['rank_team']			= 'Teamrang';
$lang['rank_special']		= 'Spezial Rang';
$lang['rank_min']			= 'Beiträge';
$lang['rank_image']			= 'Rangbild';
$lang['rank_type']			= 'Rangtype';
$lang['rank_standard']		= 'Standardrang';


//
//	Gameserver
//
$lang['gs_head']		= 'Server Administration';
$lang['gs_explain']		= 'Hier kannst du die Gameserver der Seite überwachen. Du kannst bestehende Server löschen, editieren oder neue anlegen.';

$lang['gs_add']			= 'Neuen Server eintragen';
$lang['gs_new_add']		= 'Server eintragen';
$lang['gs_edit']		= 'Server bearbeiten';

$lang['gs_name']		= 'Gameservername';

$lang['select_live']	= 'Live Status auswählen';

//
//	Einstellungen (Settings)
//


//
//	Teams
//
$lang['team_head']			= 'Team Administration';
$lang['team_explain']		= 'Hier kannst du die Teams der Seite überwachen. Du kannst bestehende Gruppen löschen, editieren oder neue anlegen.';
$lang['team_add_member_ex']	= 'Hier kannst du, Member hinzufügen.<br>Entweder Benutzer per Login mit Komma getrennt eintragen, <b>oder</b> über das Dropdown-Menü auswahlen!';

$lang['team_add']			= 'Neues Team erstellen';
$lang['team_new_add']		= 'Team hinzufügen';
$lang['team_edit']			= 'Team bearbeiten';
$lang['team_add_member']	= 'Member hinzufügen';
$lang['team_edit_member']	= 'Member bearbeiten';
$lang['team_view_member']	= 'Member Übersicht';

$lang['team_name']			= 'Teamname';
$lang['team_game']			= 'Team Game';
$lang['team_description']	= 'Teambeschreibung';
$lang['team_logo_upload']	= 'Logo Upload';
$lang['team_logo_link']		= 'Logo Link';
$lang['logo_upload']		= 'Upload Logo';
$lang['team_logos_upload']	= 'Logo (klein) Upload';
$lang['team_logos_link']	= 'Logo (klein) Link';
$lang['logos_upload']		= 'Upload Logo (klein)';
$lang['team_navi']			= 'Navi anzeige?';
$lang['team_sawards']		= 'Awards zeigen?';
$lang['team_sfight']		= 'Wars anzeigen?';
$lang['team_join']			= 'Auflistung bei JoinUs';
$lang['team_fight']			= 'Auflistung bei FightUs';
$lang['team_view']			= 'Auflistung bei Teams';
$lang['team_show']			= 'Aufgeklappt?';

$lang['team_infos']			= 'Team Details';
$lang['team_logo_setting']	= 'Logo Upload';
$lang['team_menu_setting']	= 'Menü Einstellungen';

//
//	Training
//
$lang['training_head']			= 'Training Administration';
$lang['training_explain']		= 'Hier kannst du Trainings der Teams Verwalten';

$lang['training_add']			= 'Neues Training erstellen';
$lang['training_new_add']		= 'Training hinzufügen';
$lang['training_edit']			= 'Training bearbeiten';
$lang['training_empty']			= 'Es sind keine Trainings eingetragen.';
$lang['training_upcoming']		= 'Anstehende Trainings';
$lang['training_expired']		= 'Abgelaufen Trainings';
$lang['training_vs']			= 'Trainingsname';
$lang['training_team']			= 'Team';
$lang['training_match']			= 'War';
$lang['training_date']			= 'Trainingstermin';
$lang['training_duration']		= 'Trainingsdauer (min)';
$lang['training_maps']			= 'Trainingsmaps';
$lang['training_text']			= 'Trainingsbericht';

$lang['training_create']		= 'Neues Training hinzuggefügt.';
$lang['training_update']		= 'Training geändert.';

//
//	Benutzer
//
$lang['user_head']			= 'Benutzer Administration';
$lang['user_explain']		= 'Hier kannst du die Daten und Optionen eines Nutzers ändern. Um die Befugnisse eines Benutzers zu ändern, benutze bitte die Benutzer- und Gruppenkontrolle.';
$lang['user_add']			= 'Neue Benutzer erstellen';
$lang['user_new_add']		= 'Benutzer hinzufügen';
$lang['user_edit']			= 'Benutzer bearbeiten';

$lang['user_register']			= 'Angemeldet seit';
$lang['user_lastlogin']			= 'Letzter Besuch';
$lang['user_founder']			= 'Gründer';
$lang['user_email']				= 'eMail-Adresse';
$lang['user_email_confirm']		= 'eMail-Adresse bestätigen';
$lang['user_password']			= 'Passwort';
$lang['user_password_confirm']	= 'Passwort bestätigen';
$lang['user_group']				= 'Benutzergruppen & Teams';
$lang['user_groups']			= 'Benutzergruppen';
$lang['user_teams']				= 'Teams';
$lang['user_auths']				= 'Seitenberechtigung';
$lang['user_auths_explain']		= 'Hier kann jeder Benutzer individuell eingestellt werden, Spezial heißt, er hat immer diese Rechte, egal wie die Gruppe oder Gruppen eingestellt sind von den Rechten!';
$lang['user_change_auths']		= 'Seitenberechtigung';
$lang['user_change_groups']		= 'Benutzergruppen und Teams aktualisiert.';
$lang['user_mailnotification']	= 'eMail Benachrichtigung verschicken?';

$lang['user_register']				= 'Erforderliche Daten';
$lang['user_fields']				= 'Profilfelder';
$lang['user_settings']				= 'Einstellungen';
$lang['user_images']				= 'Bilder';

$lang['user_groupmod']			= 'Moderatorstatus';
$lang['user_look_up']			= 'Benutzer auswählen';


//
//	Profilefelder
//
$lang['profile_head']				= 'Profilefelder Administration';
$lang['profile_explain']			= 'Text';

$lang['profile_add']			= 'Neues Profilefeld erstellen';
$lang['profile_new_add']		= 'Profilefeld hinzufügen';
$lang['profile_edit']			= 'Profilefeld bearbeiten';
$lang['profile_update']			= 'Profilefeld geändert.';
$lang['profile_edit_categpry']	= 'Kategorie bearbeiten';
$lang['profile_cat_edit']		= 'Profilekategorie Bearbeiten';


$lang['profile_name']			= 'Feldbeschreibung';
$lang['profile_field']			= 'Feldname';
$lang['profile_categpry']		= 'Kategorie';
$lang['profile_type']			= 'Feldtype';
$lang['profile_sguest']			= 'Anzeigen für Gäste';
$lang['profile_smember']		= 'Anzeigen nur für Member';
$lang['profile_sreg']			= 'Anzeigen beim registieren';
$lang['profile_language']		= 'Sprachdatei';

$lang['profile_text']			= 'Textzeile';
$lang['profile_area']			= 'Textfeld';

//
//	Teamspeak
//
$lang['teamspeak_head']			= 'Teamspeak Administration';
$lang['teamspeak_explain']		= 'Hier kannst du dein Teamspeak Verwalten';
$lang['teamspeak_add']			= 'Teamspeak-Server eintragen';
$lang['teamspeak_new_add']		= 'Teamspeak-Server eintragen';
$lang['teamspeak_edit']			= 'Teamspeak-Server bearbeiten';
$lang['teamspeak_user']			= 'Benutzer bearbeiten';
$lang['teamspeak_current']		= 'Aktuelle Teamspeakserver';
$lang['teamspeak_server']		= 'Teamspeakserver';

$lang['teamspeak_name']			= 'Server Name';
$lang['teamspeak_ip']			= 'Server IP';
$lang['teamspeak_port']			= 'Server Port';
$lang['teamspeak_qport']		= 'Server QueryPort';
$lang['teamspeak_pass']			= 'Server Passwort';
$lang['teamspeak_join']			= 'Nickname für Gäste';
$lang['teamspeak_cstats']		= 'Channel Rechte';
$lang['teamspeak_ustats']		= 'Benutzer Rechte';
$lang['teamspeak_sstats']		= 'Server Statistik';
$lang['teamspeak_mouseo']		= 'Mouseover Effekt?';
$lang['teamspeak_viewer']		= 'Anzeigen im Viewer';
$lang['teamspeak_show']			= 'Anzeigen';
$lang['teamspeak_noshow']		= 'Nicht Anzeigen';

$lang['teamspeak_codec_on']			= 'Aktiviert';
$lang['teamspeak_codec_off']		= 'Deaktiviert';
$lang['teamspeak_server_name']						= 'Server Name';
$lang['teamspeak_server_platform']					= 'Server Plattform';
$lang['teamspeak_server_welcomemessage']			= 'Willkommensnachricht';	
$lang['teamspeak_server_webpost_linkurl']			= '';
$lang['teamspeak_server_webpost_posturl']			= '';
$lang['teamspeak_server_password']					= 'Server Passwort';
$lang['teamspeak_server_clan_server']				= 'Server Type';
$lang['teamspeak_server_maxusers']					= 'Maximale Benutzer';
$lang['teamspeak_server_currentusers']				= 'momentane Benutzer';
$lang['teamspeak_server_allow_codec_celp51']		= 'Codec: Celp 5.1 Kbit';
$lang['teamspeak_server_allow_codec_celp63']		= 'Codec: Celp 6.3 Kbit';
$lang['teamspeak_server_allow_codec_gsm148']		= 'Codec: GSM 14.8 Kbit';
$lang['teamspeak_server_allow_codec_gsm164']		= 'Codec: GSM 16.4 Kbit';
$lang['teamspeak_server_allow_codec_windowscelp52']	= 'Codec: CELP Windows 5.2 Kbit';
$lang['teamspeak_server_allow_codec_speex2150']		= 'Codec: Speex 3.4 Kbit';
$lang['teamspeak_server_allow_codec_speex3950']		= 'Codec: Speex 5.2 Kbit';
$lang['teamspeak_server_allow_codec_speex5950']		= 'Codec: Speex 7.2 Kbit';
$lang['teamspeak_server_allow_codec_speex8000']		= 'Codec: Speex 9.3 Kbit';
$lang['teamspeak_server_allow_codec_speex11000']	= 'Codec: Speex 12.3 Kbit';
$lang['teamspeak_server_allow_codec_speex15000']	= 'Codec: Speex 16.3 Kbit';
$lang['teamspeak_server_allow_codec_speex18200']	= 'Codec: Speex 19.5 Kbit';
$lang['teamspeak_server_allow_codec_speex24600']	= 'Codec: Speex 25.9 Kbit';
$lang['teamspeak_server_packetssend']				= 'Gesendete Packete';
$lang['teamspeak_server_bytessend']					= 'Gesendete Bytes';
$lang['teamspeak_server_packetsreceived']			= 'Empfangene Packete';
$lang['teamspeak_server_bytesreceived']				= 'Empfangene Bytes';
$lang['teamspeak_server_uptime']					= 'Server Laufzeit';
$lang['teamspeak_server_currentchannels']			= 'momentane Channelanzahl';

//
//	Allgemeines
//

$lang['required']		= 'Mit * markierte Felder sind erforderlich';
$lang['common_delete']			= 'Löschen';
$lang['option_select']	= 'Option wählen';

$lang['setting']		= 'Einstellung';
$lang['settings']		= 'Einstellungen';
$lang['username']		= 'Benutzername';
$lang['register']		= 'Anmeldedatum';
$lang['joined']			= 'Beigetreten';
$lang['rank']			= 'Rang im Team';
$lang['common_confirm']	= 'Bestätigen';


$lang['mark_all']		= 'Alle markieren';
$lang['mark_deall']		= 'Keine Auswahl';

$lang['no_mode']		= 'Bitte eine gültige Aktion wählen.<br><br><a style="color:#fff; font-weight:bold; font-size:blod;" href="javascript:history.back(1)">Zur&uuml;ck</a>';

$lang['no_members'] = 'Diese Gruppe hat keine Mitglieder.';
$lang['no_moderators'] = 'Diese Gruppe hat keine Moderatoren.';

$lang['moderators']			= 'Moderatoren';
$lang['members']			= 'Mitglieder';
$lang['pending_members']	= 'wartende Mitglieder';

/*
 *	Erstellen / Erneuern / Löschen / Bestätigen zum Löschen / Klicks
 */	
$lang['create_authlist']			= 'Neues Berechtigungsfeld hinzuggefügt.';
$lang['create_event']				= 'Neues Event hinzuggefügt.';
$lang['create_forum']				= 'Neues Forum hinzugefügt.';
$lang['create_game']				= 'Neues Spiel hinzuggefügt.';
$lang['create_group']				= 'Neue Gruppe hinzuggefügt.';
$lang['create_match']				= 'Neues Match hinzuggefügt.';
$lang['create_navigation']			= 'Neuen Link hinzuggefügt.';
$lang['create_network']				= 'Neuen %s hinzuggefügt.';
$lang['create_news']				= 'Neue News hinzuggefügt.';
$lang['create_newscat']				= 'Neue Newskategorie hinzuggefügt.';
$lang['create_newsletter']			= 'Neue eMailadresse hinzuggefügt.';
$lang['create_profile']				= 'Neues Profilefeld hinzuggefügt.';
$lang['create_profile_cat']			= 'Neues Profilekategorie hinzuggefügt.';
$lang['create_rank']				= 'Neuer Rang hinzuggefügt.';
$lang['create_team']				= 'Neues Team hinzuggefügt.';
$lang['create_teamspeak']			= 'Neuen Teamspeak Server hinzuggefügt.';
$lang['create_training']			= 'Neues Training hinzuggefügt.';
$lang['create_user']				= 'Neuen Benutzer hinzuggefügt.';
$lang['create_cash']				= 'Neuen Betrag hinzuggefügt.';
$lang['create_cash_user']			= 'Neuen Benutzer zu Liste hinzuggefügt.';
$lang['create_gallery']				= 'Neue Galerie hinzuggefügt.';

$lang['update_authlist']			= 'Berechtigungsfelddaten erfolgreich geändert';
$lang['update_event']				= 'Eventdaten erfolgreich geändert';
$lang['update_forum']				= 'Forumdaten erfolgreich geändert';
$lang['update_game']				= 'Spieldaten erfolgreich geändert';
$lang['update_group']				= 'Gruppendaten erfolgreich geändert';
$lang['update_match']				= 'Matchdaten erfolgreich geändert';
$lang['update_navigation']			= 'Link erfolgreich geändert';
$lang['update_navigation_set']		= 'Subnavigation erfolgreich geändert';
$lang['update_network']				= '%sdaten erfolgreich geändert';
$lang['update_news']				= 'Newsdaten erfolgreich geändert';
$lang['update_newscat']				= 'Newskategoriedaten erfolgreich geändert';
$lang['update_newsletter']			= 'eMailadresse erfolgreich geändert';
$lang['update_profile']				= 'Profilefeld erfolgreich geändert';
$lang['update_profile_cat']			= 'Profilekategorie erfolgreich geändert';
$lang['update_rank']				= 'Rangdaten erfolgreich geändert';
$lang['update_team']				= 'Teamdaten erfolgreich geändert';
$lang['update_teamspeak']			= 'Teamspeakdaten erfolgreich geändert';
$lang['update_training']			= 'Trainingsdaten erfolgreich geändert';
$lang['update_user']				= 'Benutzerdaten erfolgreich geändert';
$lang['update_cash']				= 'Daten erfolgreich geändert';
$lang['update_cash_bank']			= 'Bankdaten erfolgreich geändert';
$lang['update_cash_user']			= 'Benutzerdaten erfolgreich geändert';
$lang['update_gallery']				= 'Galerie erfolgreich geändert';

$lang['delete_authlist']			= 'Das Berechtigungsfeld wurde gelöscht';
$lang['delete_event']				= 'Das Event wurde gelöscht';
$lang['delete_game']				= 'Das Spiel wurde gelöscht';
$lang['delete_group']				= 'Die Gruppe wurde gelöscht';
$lang['delete_log']					= 'Der oder die Logeinträge wurde gelöscht';
$lang['delete_log_all']				= 'Alle Logeinträge wurde gelöscht';
$lang['delete_log_error']			= 'Der oder die Fehlermeldungen wurde gelöscht';
$lang['delete_navigation']			= 'Der Link wurde gelöscht';
$lang['delete_network']				= 'Den %s wurde gelöscht';
$lang['delete_news']				= 'Die News wurde gelöscht';
$lang['delete_newscat']				= 'Der Newskategorie wurde gelöscht';
$lang['delete_newsletter']			= 'Die eMailadresse wurde gelöscht';
$lang['delete_profile']				= 'Das Profilefeld wurde gelöscht';
$lang['delete_profile_cat']			= 'Die Profilekategorie wurde gelöscht';
$lang['delete_rank']				= 'Der Rang wurde gelöscht';
$lang['delete_team']				= 'Das Team wurde gelöscht';
$lang['delete_teamspeak']			= 'Der Teamspeak wurde gelöscht';
$lang['delete_training']			= 'Der Rang wurde gelöscht';
$lang['delete_user']				= 'Der Benutzer wurde gelöscht';
$lang['delete_match']				= 'Die Begegnung wurde gelöscht';
$lang['delete_cash']				= 'Der Eintrag wurde gelöscht';
$lang['delete_cash_bank']			= 'Bankdaten wurden gelöscht';
$lang['delete_cash_user']			= 'Der Benutzereintrag wurde gelöscht';
$lang['delete_gallery']				= 'Die Galerie wurde gelöscht';

$lang['confirm_delete_authlist']		= 'Bist du sicher, das dieses Berechtigunsfeld gelöscht werden soll?';
$lang['confirm_delete_event']			= 'Bist du sicher, dass dieses Event gelöscht werden soll?';
$lang['confirm_delete_game']			= 'Bist du sicher, dass dieses Spiel gelöscht werden soll?';
$lang['confirm_delete_group']			= 'Bist du sicher, das die Gruppe gelöscht werden soll?';
$lang['confirm_delete_log']				= 'Bist du sicher, das dieser oder diese Logeinträge gelöscht werden soll?';
$lang['confirm_delete_all_log']			= 'Bist du sicher, das alle Logeinträge gelöscht werden soll?';
$lang['confirm_delete_log_error']		= 'Bist du sicher, das dieser oder diese Fehlermeldungen gelöscht werden soll?';
$lang['confirm_delete_navigation']		= 'Bist du sicher, das der Link gelöscht werden soll?';
$lang['confirm_delete_network']			= 'Bist du sicher, das der %s gelöscht werden soll?';
$lang['confirm_delete_news']			= 'Bist du sicher, das die News gelöscht werden soll?';
$lang['confirm_delete_newscat']			= 'Bist du sicher, das die Newskategorie gelöscht werden soll?';
$lang['confirm_delete_newsletter']		= 'Bist du sicher, das die eMailadresse gelöscht werden soll?';
$lang['confirm_delete_newsletter_all']	= 'Bist du sicher, das alle eMailadresse gelöscht werden sollen?';	
$lang['confirm_delete_profile']			= 'Bist du sicher, dass dieses Profilefeld gelöscht werden soll?';
$lang['confirm_delete_profile_cat']		= 'Bist du sicher, dass diese Profilekategorie gelöscht werden soll?';
$lang['confirm_delete_rank']			= 'Bist du sicher, dass dieser Rang gelöscht werden soll?';
$lang['confirm_delete_team']			= 'Bist du sicher, dass dieses Team gelöscht werden soll?';
$lang['confirm_delete_teamspeak']		= 'Bist du sicher, dass dieser Teamspeak gelöscht werden soll?';
$lang['confirm_delete_training']		= 'Bist du sicher, dass dieses Training gelöscht werden soll?';
$lang['confirm_delete_match']			= 'Bist du sicher, dass dieses Begegnung gelöscht werden soll?';
$lang['confirm_delete_user']			= 'Bist du sicher, das der Benutzer gelöscht werden soll?';
$lang['confirm_delete_cash']			= 'Bist du sicher, dass dieser Beitrag gelöscht werden soll?';
$lang['confirm_delete_cash_bank']		= 'Bist du sicher, dass die Bankdaten gelöscht werden soll?';
$lang['confirm_delete_cash_user']		= 'Bist du sicher, dass dieser Benutzereintrag gelöscht werden soll?';
$lang['confirm_delete_gallery']			= 'Bist du sicher, dass diese Galerie gelöscht werden soll?';

$lang['click_admin_index']				= '<br><br>Klicke %shier%s, um zum Adminstart zurückzukehren';
$lang['click_return_authlist']			= '<br><br>Klicke %shier%s, um zur Berechtigungsfelder Administration zurückzukehren';
$lang['click_return_details']			= '<br><br>Klicke %shier%s, um zur Detail Administration zurückzukehren';
$lang['click_return_event']				= '<br><br>Klicke %shier%s, um zur Event Administration zurückzukehren';
$lang['click_return_forum']				= '<br><br>Klicke %shier%s, um zur Forum Administration zurückzukehren';
$lang['click_return_game']				= '<br><br>Klicke %shier%s, um zur Spiel Administration zurückzukehren';
$lang['click_return_group']				= '<br><br>Klicke %shier%s, um zur Gruppen Administration zurückzukehren';
$lang['click_return_group_member']		= '<br><br>Klicke %shier%s, um zur Gruppenmitglieder Administration zurückzukehren';
$lang['click_return_log']				= '<br><br>Klicke %shier%s, um zur Log Administration zurückzukehren';
$lang['click_return_log_error']			= '<br><br>Klicke %shier%s, um zur Log (Fehlermeldungen) Administration zurückzukehren';
$lang['click_return_match']				= '<br><br>Klicke %shier%s, um zur Match Administration zurückzukehren';
$lang['click_return_match_details']		= '<br><br>Klicke %shier%s, um zur Matchdeatils Administration zurückzukehren';
$lang['click_return_navigation']		= '<br><br>Klicke %shier%s, um zur Navigations Administration zurückzukehren';
$lang['click_return_navigation_set']	= '<br><br>Klicke %shier%s, um zur Subnavigations Administration zurückzukehren';
$lang['click_return_network']			= '<br><br>Klicke %shier%s, um zur Network Administration zurückzukehren';
$lang['click_return_news']				= '<br><br>Klicke %shier%s, um zur News Administration zurückzukehren';
$lang['click_return_newscat']			= '<br><br>Klicke %shier%s, um zur Newskategorie Administration zurückzukehren';
$lang['click_return_newsletter']		= '<br><br>Klicke %shier%s, um zur Newsletter Administration zurückzukehren';
$lang['click_return_profile']			= '<br><br>Klicke %shier%s, um zur Profilefelder Administration zurückzukehren';
$lang['click_return_rank']				= '<br><br>Klicke %shier%s, um zur Rang Administration zurückzukehren';
$lang['click_return_set']				= '<br><br>Klicke %shier%s, um zur Einstelluns Administration zurückzukehren';
$lang['click_return_team']				= '<br><br>Klicke %shier%s, um zur Team Administration zurückzukehren';
$lang['click_return_team_member']		= '<br><br>Klicke %shier%s, um zur Teammember Administration zurückzukehren';
$lang['click_return_teamspeak']			= '<br><br>Klicke %shier%s, um zur Teamspeak Administration zurückzukehren';
$lang['click_return_training']			= '<br><br>Klicke %shier%s, um zur Trainings Administration zurückzukehren';
$lang['click_return_user']				= '<br><br>Klicke %shier%s, um zur Benutzer Administration zurückzukehren';
$lang['click_return_user_auths']		= '<br><br>Klicke %shier%s, um zur Benutzer (Seitenberechtigung) Administration zurückzukehren';
$lang['click_return_user_groups']		= '<br><br>Klicke %shier%s, um zur Benutzer (Benutzergruppen & Teams) Administration zurückzukehren';
$lang['click_return_cash']				= '<br><br>Klicke %shier%s, um zur Clankassen Administration zurückzukehren';
$lang['click_return_gallery']			= '<br><br>Klicke %shier%s, um zur Galerie Administration zurückzukehren';

$lang['order_game']					= 'Spiele neusortiert.';

$lang['msg_group_add_member']		= 'Benutzer zur Gruppe hinzugefügt';
$lang['msg_group_del_member']		= 'Benutzer gelöscht von der Gruppe';

$lang['msg_team_add_member']		= 'Member zum Team hinzugefügt';
$lang['msg_team_del_member']		= 'Member gelöscht von dem Team';

//	Match	MSG
$lang['msg_select_team']		= 'Bitte ein Team auswählen';
$lang['msg_select_duration']	= 'Bitte eine Zeitdauer auswählen';
$lang['msg_select_type']		= 'Bitte ein Type / Art auswählen';
$lang['msg_select_cat']			= 'Bitte ein Match Art auswählen';
$lang['msg_select_league']		= 'Bitte eine Liga auswählen';
$lang['msg_select_date']		= 'Bitte ein Gültiges Datum auswählen';
$lang['msg_select_map']			= 'Bitte eine Map eintragen';

$lang['msg_select_title']		= 'Bitte Title eintragen';
$lang['msg_select_name']		= 'Bitte Name eintragen';
$lang['msg_select_description']	= 'Bitte Beschreibung eintragen';
$lang['msg_select_url']			= 'Bitte Link eintragen';
$lang['msg_select_amount']		= 'Bitte einen Betrag eintragen';
$lang['msg_select_user']		= 'Bitte einen Benutzer auswählen';

$lang['msg_select_bankdata_name']		= 'Bitte Inhaber des Kontos eintragen';
$lang['msg_select_bankdata_bank']		= 'Bitte Bankname eintragen';
$lang['msg_select_bankdata_blz']		= 'Bitte Bankleitzahl eintragen';
$lang['msg_select_bankdata_number']		= 'Bitte Kontonummer eintragen';
$lang['msg_select_bankdata_reason']		= 'Bitte Verwendungszweck eintragen';


$lang['game_select']	= 'Spiel auswählen';
$lang['select_match']	= 'Match auswählen';

$lang['back']			= '<br><br><a style="color:#fff; font-weight:bold; font-size:blod;" href="javascript:history.back(1)">&laquo; Zur&uuml;ck</a>';




$lang['Username_taken'] = 'Der gewünschte Benutzername ist leider bereits belegt.';
$lang['Username_invalid'] = 'Der gewünschte Benutzername enthält ein ungültiges Sonderzeichen (z. B. \').';
$lang['Password_mismatch'] = 'Du musst zweimal das gleiche Passwort eingeben.';

$lang['user_pass_random']	= 'Beispiel: %s';
$lang['Assigned_groups'] = 'Assigned Groups';
$lang['Membership_pending'] = '(membership pending: click \'YES\' to approve or \'NO\' to deny)';
$lang['Email_notification'] = 'Email notification if user is added to any groups';

$lang['email_enabled'] = "E-Mail-Funktion";
$lang['email_enabled_explain'] = "Hier kann die E-Mail-Funktion des Boards komplett ausgeschaltet werden";

$lang['auth_forum_explain_ALL'] = 'All users';
$lang['auth_forum_explain_REG'] = 'All registered users';
$lang['auth_forum_explain_PRIVATE'] = 'Only users granted special permission';
$lang['auth_forum_explain_MOD'] = 'Only moderators of this forum';
$lang['auth_forum_explain_ADMIN'] = 'Only administrators';

$lang['auth_forum_explain_auth_view'] = '%s can view this forum';
$lang['auth_forum_explain_auth_read'] = '%s can read posts in this forum';
$lang['auth_forum_explain_auth_post'] = '%s can post in this forum';
$lang['auth_forum_explain_auth_reply'] = '%s can reply to posts this forum';
$lang['auth_forum_explain_auth_edit'] = '%s can edit posts in this forum';
$lang['auth_forum_explain_auth_delete'] = '%s can delete posts in this forum';
$lang['auth_forum_explain_auth_sticky'] = '%s can post sticky topics in this forum';
$lang['auth_forum_explain_auth_announce'] = '%s can post announcements in this forum';
$lang['auth_forum_explain_auth_poll'] = '%s can vote in polls in this forum';
$lang['auth_forum_explain_auth_pollcreate'] = '%s can create polls in this forum';

$lang['Permissions_List'] = 'Permissions List'; // Added by Permissions List MOD
$lang['Forum_auth_list_explain'] = 'This provides a summary of the authorisation levels of each forum. You can edit these permissions, using either a simple or advanced method by clicking on the forum name. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.'; // Added by Permissions List MOD
$lang['Cat_auth_list_explain'] = 'This provides a summary of the authorisation levels of each forum within this category. You can edit the permissions of individual forums, using either a simple or advanced method by clicking on the forum name. Alternatively, you can set the permissions for all the forums in this category by using the drop-down menus at the bottom of the page. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.'; // Added by Permissions List MOD

//
//	Auswahl
//

$lang['msg_must_select_authlist']		= 'Wähle ein Authfeld aus';
$lang['msg_must_select_bugtracker']		= 'Wähle ein Bugeintrag aus';
$lang['msg_must_select_games']			= 'Wähle ein Spiel aus';
$lang['msg_must_select_log']			= 'Wähle ein Logeintrag aus';
$lang['msg_must_select_match']			= 'Wähle ein Match/War aus';
$lang['msg_must_select_navigation']		= 'Wähle ein Link aus';
$lang['msg_must_select_profile']		= 'Wähle ein Profilefeld aus';
$lang['msg_must_select_rank']			= 'Wähle ein Rang aus';
$lang['msg_must_select_server']			= 'Wähle ein Server aus';
$lang['msg_must_select_team']			= 'Wähle ein Team aus';
$lang['msg_must_select_teamspeak']		= 'Wähle ein Teamspeak aus';
$lang['msg_must_select_training']		= 'Wähle ein Training aus';
$lang['msg_must_select_user']			= 'Wähle ein Benutzer aus';

$lang['msg_select_standard']			= 'Rang kann nicht gelöscht werden, da es sich um einen Standardrang handelt!';



//
//	Errors
//
$lang['DB_errors']					= 'DB-Fehler';
$lang['No_errors_found']			= 'Keine Fehler gefunden bzw. es sind bisher keine Einträge in der Datenbank hinterlegt!';
$lang['Click_return_error_log']		= '<br><br>Klicke %shier%s, um zur Übersicht zurückzukehren';
$lang['Could_not_delete_id']		= 'Eintrag konnte nicht gelöscht werden!';
$lang['Could_not_delete_all_id']	= 'Einträge konnte nicht gelöscht werden!';
$lang['Id_deleted']					= 'Eintrag wurde gelöscht!';
$lang['Id_all_deleted']				= 'Alle Einträge wurden gelöscht!';

//
//	Teams
//
$lang['team_no_member']		= 'Keine Mitglieder vorhanden.';
$lang['team_empty'] = 'Es sind keine Teams vorhanden.';
$lang['team_membercount'] = 'Memberanzahl';
$lang['member'] = 'Mitglieder';
$lang['msg_team_add_member'] = 'Member hinzugefügt';
$lang['team_del_member'] = 'Member gelöscht';
$lang['team_change_member'] = 'Rang geändert';
$lang['status_set'] = '<strong>Status %s setzen</strong>';
$lang['team_create'] = 'Neues Team hinzuggefügt.';
$lang['team_update'] = 'Update des Teams erfolgt.';

//
//	Einstellungen (Config/Settings)
//
$lang['set_head']				= 'Einstellungen der Seite';
$lang['set_explain']			= 'Hier werden alle wichtigen Einstellungen für die Seite gemacht.';
$lang['set_ftp']				= 'FTP Einstellungen';
$lang['set_ftp_explain']		= 'Hier kannst du Notfalls FTP Rechte verändert werdem!';


//	Haupteinstellungen
$lang['settings_general']			= 'Seite';
$lang['settings_general_explain']	= 'alle wichtigen Einstellungen die die Seite betreffen.';
$lang['settings_upload']			= 'Upload Allgemein';
$lang['settings_upload_explain']	= 'Rüne und Rote Punkte symbolisieren, ob ein Verzeichnis Vorhanden ist und ob Schreibrecht vorliegen.';
$lang['settings_team_logo']			= 'Team Logo Upload';
$lang['settings_team_logo_explain']	= 'Hier können spezielle Parameter für den Upload von Teamlogos bestimmt werden.';

//	Allgeminer Block
$lang['server_name']				= 'Domainname';
$lang['server_name_explain']		= 'Der Name der Domain, auf dem die Seite läuft';
$lang['server_port']				= 'Server Port';
$lang['server_port_explain']		= 'Der Port, unter dem dein Server läuft, normalerweise 80. Ändere dies nur, wenn es ein anderer Port ist';
$lang['script_pfad']				= 'Scriptpfad';
$lang['script_pfad_explain']		= 'Der Pfad zum CMS, relativ zum Domainnamen';
$lang['site_name']					= 'Name der Seite';
$lang['site_name_explain']			= 'Wird auf jeder Seite angezeigt.';
$lang['site_description']			= 'Beschreibung der Seite';
$lang['disable_page']				= 'Seite deaktivieren';
$lang['disable_page_explain']		= 'Hiermit sperrst du die Seite für alle Benutzer. Administratoren können auf den Administrations-Bereich zugreifen, wenn die Seite gesperrt ist.';
$lang['disable_page_reason']		= 'Deaktivierungsgrund';
$lang['disable_page_mode']			= 'Deaktivert für Level';

//	Upload Block
$lang['games_storage']				= 'Spiele Speicherpfad';
$lang['games_storage_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Spieleicons liegen (z. B. images/games)';
$lang['ranks_storage']				= 'Rang Speicherpfad';
$lang['ranks_storage_explain']		= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Rangbilder liegen (z. B. images/ranks)';
$lang['team_logo_storage']			= 'Team Logo Speicherpfad';
$lang['team_logo_storage_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Teamlogos liegen (z. B. images/teams)';
$lang['team_logos_storage']			= 'Team Logo (klein) Speicherpfad';
$lang['team_logos_storage_explain']	= 'Der Pfad in deinem CMS-Verzeichnis, in dem die Teamlogos (klein) liegen (z. B. images/teams/small)';

$lang['team_logo_upload']			= 'Team Logo Upload';
$lang['team_logo_upload_explain']	= 'Team Logo';
$lang['team_logo_file']				= 'Team Logo Datei';
$lang['team_logo_file_explain']		= 'Team Logo Dateibeschreibung';
$lang['team_logo_size']				= 'Team Logo Größe';
$lang['team_logo_size_explain']		= 'Team Logo Größe Beschreibung';

$lang['team_logos_upload']			= 'Team Logo Upload';
$lang['team_logos_upload_explain']	= 'Team Logo';
$lang['team_logos_file']				= 'Team Logo Datei';
$lang['team_logos_file_explain']		= 'Team Logo Dateibeschreibung';
$lang['team_logos_size']				= 'Team Logo Größe';
$lang['team_logos_size_explain']		= 'Team Logo Größe Beschreibung';


//
//	Meldungen
//
$lang['empty_name']			= 'Bitte einen Namen eingeben!';
$lang['empty_title']		= 'Bitte einen Titel eingeben!';
$lang['empty_mail']			= 'Bitte eine gültige eMail Adresse eingeben!';
$lang['no_select_module']	= 'Kein Modul ausgewählt!';
$lang['auth_fail']			= 'Keine Berechtiung für dieses Modul';
$lang['wrong_filetype']		= 'Falscher Dateityp!';
$lang['no_entry']			= 'Keine Daten Vorhanden';
$lang['id_nonexistent']		= 'ID nicht vorhanden!';

//	Teams

$lang['team_not_exist']			= 'Das Team ist nicht vorhanden!';
$lang['create_no_name']			= 'Namen für das Team eingeben!';
$lang['team_no_new'] = 'Keinen Neuen Member';
$lang['team_no_select'] = 'Keine Member ausgewählt!';

//	Logo
$lang['logo_filetype'] = 'Das Logo muss im GIF-, JPG- oder PNG-Format sein.';
$lang['logo_filesize'] = 'Die Dateigröße muss kleiner als %d KB sein.';
$lang['logo_imagesize'] = 'Das Logo muss weniger als %d Pixel breit und %d Pixel hoch sein.';
$lang['logos_filetype'] = 'Das kleine Logo muss im GIF-, JPG- oder PNG-Format sein.';
$lang['logos_filesize'] = 'Die Dateigröße muss kleiner als %d KB sein.';
$lang['logos_imagesize'] = 'Das kleine Logo muss weniger als %d Pixel breit und %d Pixel hoch sein.';

//	Ranks
$lang['must_select_rank']		= 'Wähle einen Rang aus';
$lang['rank_not_exist']			= 'Der Rang ist nicht vorhanden!';

$lang['game_not_exist']			= 'Das Spiel ist nicht vorhanden!';

$lang['description']			= 'Beschreibung';
$lang['type']					= 'Typ / Art';
$lang['message']				= 'Nachricht';


$lang['alldelete']				= 'Alle Einträge löschen';

$lang['create_forum']		= 'Neues Forum erstellen';
$lang['create_profile']		= 'Neues Profilefeld erstellen';
$lang['create_category']	= 'Neue Kategorie erstellen';


$lang['common_submit']		= 'Absenden';
$lang['common_reset']		= 'Zurücksetzen';
$lang['common_yes']			= 'Ja';
$lang['common_no']			= 'Nein';

$lang['common_comments']	= 'Kommentare erlauben?';
$lang['common_view']		= 'Anzeigen';


$lang['common_edit']			= 'Bearbeiten';
$lang['common_delete']			= 'Löschen';
$lang['common_upload']			= 'Upload';

//
//	Clankasse (Cash)
//
$lang['cash']			= 'Clankasse';
$lang['cash_head']		= 'Clankasse Administration';
$lang['cash_explain']	= 'Clankasse halt';

$lang['cash_add']		= 'Neuen Eintrag erstellen';
$lang['cash_new_add']	= 'Eintrag hinzufügen';
$lang['cash_edit']		= 'Eintrag bearbeiten';

$lang['cash_name']		= 'Beitragsname';
$lang['cash_type']		= 'Beitragstype';
$lang['cash_amount']	= 'Betrag';

$lang['cash_type_a']	= 'Gameserver';
$lang['cash_type_b']	= 'Voiceserver';
$lang['cash_type_c']	= 'sonstiges';

$lang['cash_user_add']		= 'Neuen Benutzereintrag erstellen';
$lang['cash_user_new_add']	= 'Benutzer hinzufügen';
$lang['cash_user_edit']		= 'Benutzer bearbeiten';

$lang['cash_username']			= 'Benutzer';
$lang['cash_user_month']		= 'Monat';
$lang['cash_user_interval_m']	= 'Monatlich';
$lang['cash_user_interval_o']	= 'Einmalig';

$lang['cash_interval']			= 'Zahlungsintervall';
$lang['cash_interval_month']	= 'Monatlich';
$lang['cash_interval_2weeks']	= 'alle 2 Wochen';
$lang['cash_interval_week']		= 'Wöchentlich';

$lang['cash_bankdata']		= 'Bankdaten';
$lang['cash_bd_bank']		= 'Kreditinstitut';
$lang['cash_bd_number']		= 'Kontonummer';
$lang['cash_bd_blz']		= 'Bankleitzahl';
$lang['cash_bd_name']		= 'Inhaber';
$lang['cash_bd_reason']		= 'Verwendungszweck';

$lang['cash_bank_clear']	= 'Bankdaten löschen';

$lang['month'] = array(
	'01'	=> 'Januar',
	'02'	=> 'Februar',
	'03'	=> 'M&auml;rz',
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

$lang['empty_site'] = "<html>
<head>
	<title></title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
</head>
<body bgcolor=\"#FFFFFF\" text=\"#000000\"></body>
</html>";

?>