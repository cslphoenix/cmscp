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
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel � 2009, 2010
 *	@code:	Sebastian Frickel � 2009, 2010
 *
 */

//
// Admin Header
//
$lang['index_head']			= 'Adminbereich von: %s';
$lang['session']			= 'Verlassen';
$lang['logout']				= 'Abmelden';
$lang['index']				= '�bersicht';
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
$lang['contact_over']		= 'Kontakt - �bersicht';
$lang['event']				= 'Event';
$lang['fighus']				= 'FightUs';
$lang['forums']				= 'Forum';
$lang['games_over']			= 'Spiele';
$lang['gameserver']			= 'Gameserver';
$lang['groups']				= 'Gruppen';
$lang['joinus']				= 'JoinUs';
$lang['log']				= '�bersicht';
$lang['log_error']			= 'Fehlermeldungen';
$lang['logs']				= 'Protokolle';
$lang['logs_admin']			= 'Protokoll - Admin';
$lang['logs_member']		= 'Protokoll - Member';
$lang['logs_user']			= 'Protokoll - User';
$lang['main']				= 'Allgemein';
$lang['match']				= 'Begegnungen';
$lang['match_over']			= '�bersicht';

$lang['network']			= 'Network';
$lang['news']				= 'News';
$lang['newscat']			= 'Newskategorie';
$lang['newsletter']			= 'Newsletter';
$lang['permissions']		= 'Befugnisse';
$lang['permissions_list']	= 'Befugnisse �bersicht';		
$lang['ranks']				= 'R�nge';
$lang['ranks_over']			= 'R�nge';
$lang['server']				= 'Server';
$lang['set']				= 'Einstellungen';
$lang['set_ftp']			= 'FTP-Rechte';


$lang['teams_over']			= '�bersicht';
$lang['training']			= 'Training';
$lang['users']				= 'Benutzer';

$lang['gallery']			= 'Galerie';
$lang['profile']			= 'Profilfelder';



//
//	Berechtigung
//
$lang['auth_gallery_view']		= 'Betrachten';
$lang['auth_gallery_edit']		= 'Bearbeiten';
$lang['auth_gallery_delete']	= 'L�schen';
$lang['auth_gallery_rate']		= 'Bewertung';
$lang['auth_gallery_upload']	= 'Hochladen';

$lang['gallery_max_width']		= 'maximale Breite';
$lang['gallery_max_height']		= 'maximale H�he';
$lang['gallery_max_filesize']	= 'maximale Gr��e';

$lang['auth_gallery'] = array(
	'auth_view'		=> $lang['auth_gallery_view'],
	'auth_edit'		=> $lang['auth_gallery_edit'],
	'auth_delete'	=> $lang['auth_gallery_delete'],
	'auth_rate'		=> $lang['auth_gallery_rate'],
	'auth_upload'	=> $lang['auth_gallery_upload'],
);




//
//	Kontakt
//
$lang['contact_overview']			= 'Kontakt �bersicht';
$lang['contact_head_normal']		= 'Kontakt-Eintr�ge';
$lang['contact_head_fightus']		= 'FightUs-Eintr�ge';
$lang['contact_head_joinus']		= 'JoinUs-Eintr�ge';
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
$lang['Public']				= '�ffentlich';
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
$lang['Delete']				= 'L�schen';
$lang['Sticky']				= 'Wichtig';
$lang['Announce']			= 'Ank�ndigung';
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
$lang['log_explain']		= 'Hier kannst du Logs l�schen!';
$lang['log_error']			= 'Fehlermeldungen';

$lang['log_username']		= 'Benutzername';
$lang['log_ip']				= 'IP-Adresse';
$lang['log_time']			= 'Dateum/Zeit';
$lang['log_sektion']		= 'Logbereich';
$lang['log_message']		= 'Lognachricht';
$lang['log_change']			= '�nderungen';


//
//	Profilfelder
//


//
//	R�nge (Ranks)
//


//
//	Gameserver
//
$lang['gs_head']		= 'Server Administration';
$lang['gs_explain']		= 'Hier kannst du die Gameserver der Seite �berwachen. Du kannst bestehende Server l�schen, editieren oder neue anlegen.';

$lang['gs_add']			= 'Neuen Server eintragen';
$lang['gs_new_add']		= 'Server eintragen';
$lang['gs_edit']		= 'Server bearbeiten';

$lang['gs_name']		= 'Gameservername';

$lang['select_live']	= 'Live Status ausw�hlen';

//
//	Einstellungen (Settings)
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
$lang['common_delete']			= 'L�schen';


$lang['setting']		= 'Einstellung';
$lang['settings']		= 'Einstellungen';
$lang['username']		= 'Benutzername';
$lang['register']		= 'Anmeldedatum';
$lang['joined']			= 'Beigetreten';
$lang['rank']			= 'Rang im Team';
$lang['common_confirm']	= 'Best�tigen';


$lang['mark_all']		= 'alle markieren';
$lang['mark_deall']		= 'keine Auswahl';
$lang['mark_yes']		= 'Ja aktivieren';
$lang['mark_no']		= 'Nein aktivieren';

$lang['no_mode']		= 'Bitte eine g�ltige Aktion w�hlen.<br><br><a style="color:#fff; font-weight:bold; font-size:blod;" href="javascript:history.back(1)">Zur�ck</a>';

$lang['no_members'] = 'Diese Gruppe hat keine Mitglieder.';
$lang['no_moderators'] = 'Diese Gruppe hat keine Moderatoren.';


$lang['pending_members']	= 'wartende Mitglieder';



$lang['order_game']					= 'Spiele neusortiert.';

$lang['msg_group_add_member']		= 'Benutzer zur Gruppe hinzugef�gt';
$lang['msg_group_del_member']		= 'Benutzer gel�scht von der Gruppe';

$lang['msg_team_add_member']		= 'Member zum Team hinzugef�gt';
$lang['msg_team_del_member']		= 'Member gel�scht von dem Team';

//	Match	MSG

$lang['back']			= '<br><br><a style="color:#fff; font-weight:bold; font-size:blod;" href="javascript:history.back()">&laquo; Zur�ck</a>';




$lang['Username_taken'] = 'Der gew�nschte Benutzername ist leider bereits belegt.';
$lang['Username_invalid'] = 'Der gew�nschte Benutzername enth�lt ein ung�ltiges Sonderzeichen (z. B. \').';
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

$lang['Permissions_List'] = 'Permissions List';// Added by Permissions List MOD
$lang['Forum_auth_list_explain'] = 'This provides a summary of the authorisation levels of each forum. You can edit these permissions, using either a simple or advanced method by clicking on the forum name. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.';// Added by Permissions List MOD
$lang['Cat_auth_list_explain'] = 'This provides a summary of the authorisation levels of each forum within this category. You can edit the permissions of individual forums, using either a simple or advanced method by clicking on the forum name. Alternatively, you can set the permissions for all the forums in this category by using the drop-down menus at the bottom of the page. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.';// Added by Permissions List MOD

//
//	Auswahl
//


$lang['msg_must_select_bugtracker']		= 'W�hle ein Bugeintrag aus';

$lang['msg_must_select_log']			= 'W�hle ein Logeintrag aus';
$lang['msg_must_select_match']			= 'W�hle ein Match/War aus';
$lang['msg_must_select_navigation']		= 'W�hle ein Link aus';
$lang['msg_must_select_profile']		= 'W�hle ein Profilefeld aus';
$lang['msg_must_select_rank']			= 'W�hle ein Rang aus';
$lang['msg_must_select_server']			= 'W�hle ein Server aus';
$lang['msg_must_select_team']			= 'W�hle ein Team aus';
$lang['msg_must_select_teamspeak']		= 'W�hle ein Teamspeak aus';
$lang['msg_must_select_training']		= 'W�hle ein Training aus';
$lang['msg_must_select_user']			= 'W�hle ein Benutzer aus';

$lang['msg_select_standard']			= 'Rang kann nicht gel�scht werden, da es sich um einen Standardrang handelt!';



//
//	Errors
//
$lang['DB_errors']					= 'DB-Fehler';
$lang['No_errors_found']			= 'Keine Fehler gefunden bzw. es sind bisher keine Eintr�ge in der Datenbank hinterlegt!';
$lang['Click_return_error_log']		= '<br><br>Klicke %shier%s, um zur �bersicht zur�ckzukehren';
$lang['Could_not_delete_id']		= 'Eintrag konnte nicht gel�scht werden!';
$lang['Could_not_delete_all_id']	= 'Eintr�ge konnte nicht gel�scht werden!';
$lang['Id_deleted']					= 'Eintrag wurde gel�scht!';
$lang['Id_all_deleted']				= 'Alle Eintr�ge wurden gel�scht!';

//
//	Teams
//
$lang['team_no_member']		= 'Keine Mitglieder vorhanden.';
$lang['team_empty'] = 'Es sind keine Teams vorhanden.';


$lang['msg_team_add_member'] = 'Member hinzugef�gt';
$lang['team_del_member'] = 'Member gel�scht';
$lang['team_change_member'] = 'Rang ge�ndert';

$lang['team_create'] = 'Neues Team hinzuggef�gt.';
$lang['team_update'] = 'Update des Teams erfolgt.';

//
//	Einstellungen (Config/Settings)
//



//	Upload Block

$lang['team_logo_upload']			= 'Team Logo Upload';
$lang['team_logo_upload_explain']	= 'Team Logo';
$lang['team_logo_file']				= 'Team Logo Datei';
$lang['team_logo_file_explain']		= 'Team Logo Dateibeschreibung';
$lang['team_logo_size']				= 'Team Logo Gr��e';
$lang['team_logo_size_explain']		= 'Team Logo Gr��e Beschreibung';

$lang['team_logos_upload']			= 'Team Logo Upload';
$lang['team_logos_upload_explain']	= 'Team Logo';
$lang['team_logos_file']				= 'Team Logo Datei';
$lang['team_logos_file_explain']		= 'Team Logo Dateibeschreibung';
$lang['team_logos_size']				= 'Team Logo Gr��e';
$lang['team_logos_size_explain']		= 'Team Logo Gr��e Beschreibung';


//
//	Meldungen
//
$lang['empty_name']			= 'Bitte einen Namen eingeben!';
$lang['empty_title']		= 'Bitte einen Titel eingeben!';
$lang['empty_mail']			= 'Bitte eine g�ltige eMail Adresse eingeben!';


$lang['wrong_filetype']		= 'Falscher Dateityp!';
$lang['no_entry']			= 'Keine Daten Vorhanden';
$lang['id_nonexistent']		= 'ID nicht vorhanden!';

//	Teams

$lang['team_not_exist']			= 'Das Team ist nicht vorhanden!';
$lang['create_no_name']			= 'Namen f�r das Team eingeben!';
$lang['team_no_new'] = 'Keinen Neuen Member';
$lang['team_no_select'] = 'Keine Member ausgew�hlt!';

$lang['team_select_no_users']	= 'Keine Member ausgew�hlt!';
$lang['team_select_no_new']		= 'Keinen Neuen Member';

//	Logo
$lang['logo_filetype'] = 'Das Logo muss im GIF-, JPG- oder PNG-Format sein.';
$lang['logo_filesize'] = 'Die Dateigr��e muss kleiner als %d KB sein.';
$lang['logo_imagesize'] = 'Das Logo muss weniger als %d Pixel breit und %d Pixel hoch sein.';
$lang['logos_filetype'] = 'Das kleine Logo muss im GIF-, JPG- oder PNG-Format sein.';
$lang['logos_filesize'] = 'Die Dateigr��e muss kleiner als %d KB sein.';
$lang['logos_imagesize'] = 'Das kleine Logo muss weniger als %d Pixel breit und %d Pixel hoch sein.';

//	Ranks
$lang['must_select_rank']		= 'W�hle einen Rang aus';
$lang['rank_not_exist']			= 'Der Rang ist nicht vorhanden!';

$lang['game_not_exist']			= 'Das Spiel ist nicht vorhanden!';

$lang['description']			= 'Beschreibung';
$lang['type']					= 'Typ / Art';
$lang['message']				= 'Nachricht';


$lang['alldelete']				= 'Alle Eintr�ge l�schen';

$lang['create_forum']		= 'Neues Forum erstellen';
$lang['create_profile']		= 'Neues Profilefeld erstellen';
$lang['create_category']	= 'Neue Kategorie erstellen';

$lang['month'] = array(
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

?>