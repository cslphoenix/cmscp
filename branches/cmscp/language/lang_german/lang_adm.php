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

$lang['network']			= 'Network';
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


$lang['teams_over']			= 'Übersicht';
$lang['training']			= 'Training';
$lang['users']				= 'Benutzer';

$lang['gallery']			= 'Galerie';
$lang['profile']			= 'Profilfelder';



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
$lang['auth_gallery']		= 'Galerie';
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

$lang['gallery_auth_view']		= 'Betrachten';
$lang['gallery_auth_edit']		= 'Bearbeiten';
$lang['gallery_auth_delete']	= 'Löschen';
$lang['gallery_auth_rate']		= 'Bewertung';
$lang['gallery_auth_upload']	= 'Hochladen';

$lang['gallery_max_width']		= 'maximale Breite';
$lang['gallery_max_height']		= 'maximale Höhe';
$lang['gallery_max_filesize']	= 'maximale Größe';

$lang['auth_gallery'] = array(
	'auth_view'		=> $lang['gallery_auth_view'],
	'auth_edit'		=> $lang['gallery_auth_edit'],
	'auth_delete'	=> $lang['gallery_auth_delete'],
	'auth_rate'		=> $lang['gallery_auth_rate'],
	'auth_upload'	=> $lang['gallery_auth_upload'],
);




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
//	Network (Links/Partner/Sponsor)
//


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
//	Profilfelder
//


//
//	Ränge (Ranks)
//


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

/*
 *	Rang auswahl
 */
$lang['msg_select_rank']		= 'Bitte eine Option wählen!';
$lang['msg_select_rank_rights']	= 'Gruppenrechte geben/nehmen';
$lang['msg_select_rank_set']	= 'Status %s setzen';

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


$lang['pending_members']	= 'wartende Mitglieder';



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
$lang['msg_select_match']		= 'Bitte eine Begegnung auswählen';
$lang['msg_select_game']		= 'Bitte ein Spiel auswählen!';
$lang['msg_select_ranks']		= 'Bitte einen Rang auswählen!';

$lang['msg_select_title']		= 'Bitte Title eintragen';
$lang['msg_select_name']		= 'Bitte Name eintragen';
$lang['msg_select_description']	= 'Bitte Beschreibung eintragen';
$lang['msg_select_url']			= 'Bitte Link eintragen';
$lang['msg_select_amount']		= 'Bitte einen Betrag eintragen';
$lang['msg_select_user']		= 'Bitte einen Benutzer auswählen';
$lang['msg_select_pics']		= 'Bitte ein oder mehrere Bilder auswählen, zum löschen.';
$lang['msg_select_category']		= 'Bitte eine Kategorie auswählen';
$lang['msg_select_news_category']	= 'Bitte eine Newskategorie auswählen';
$lang['msg_select_text']		= 'Bitte einen Text eingeben';

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

$lang['team_select_no_users']	= 'Keine Member ausgewählt!';
$lang['team_select_no_new']		= 'Keinen Neuen Member';

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