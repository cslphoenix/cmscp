<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'navi_navigation'	=> 'Navigation',
	
	'content_encoding'	=> 'iso-8859-1',
	'content_direction'	=> 'ltr',
<<<<<<< .mine
	
	/* Hauptmenü */
	'hm_contact'	=> 'Kontakt',	
	'hm_database'	=> 'Datenbank',	
	'hm_dev'		=> 'Entwicklung',
	'hm_forum'		=> 'Forum',
	'hm_games'		=> 'Spiele',
	'hm_groups'		=> 'Gruppen',
	'hm_main'		=> 'Allgemein',	
	'hm_news'		=> 'News',
	'hm_server'		=> 'Server',
	'hm_teams'		=> 'Teams',
	'hm_template'	=> 'Themes/Template',	
	'hm_users'		=> 'Benutzer',
=======
	
	/* Hauptmenü */
	'hm_contact'	=> 'Kontakt',	
	'hm_database'	=> 'Datenbank',	
	'hm_dev'		=> 'Entwicklung',
	'hm_forum'		=> 'Forum',
	'hm_games'		=> 'Spiele',
	'hm_groups'		=> 'Gruppen',
	'hm_main'		=> 'Allgemein',	
	'hm_news'		=> 'News',
	'hm_teams'		=> 'Teams',
	'hm_template'	=> 'Themes/Template',	
	'hm_users'		=> 'Benutzer',
>>>>>>> .r85

	/* Hauptmenü */
	
	/* Submenü */
<<<<<<< .mine
	/* Styles */
	'sm_themes'			=> 'Themes',
	'sm_addnew'			=> 'Hinzufügen',
	'sm_create'			=> 'Erstellen',
	'sm_manage'			=> 'Eingestellungen',
	'sm_export'			=> 'Exportieren',
	
	'sm_settings_groups'	=> 'Gruppeneinstellung',
	
	
	'sm_teams_match'	=> 'Begegnungen',
	'sm_teams_training'	=> 'Training',
	'sm_teams_settings'	=> 'Einstellung',
	'sm_news'		=> 'News',
	'sm_news_cat'	=> 'Kategorie',
	'sm_index'		=> 'Übersicht',
	'sm_training'	=> 'Training',
	'sm_authlist'			=> 'Berechtigungsfelder',
	'sm_bugtracker'		=> 'Bugtracker',
	'sm_cash'				=> 'Clankasse',
	'sm_changelog'		=> 'Changelog',
	'sm_downloads'		=> 'Downloads',
	'sm_overview'			=> 'Übersicht',
	'sm_fightus'			=> 'Fight us',
	'sm_joinus'			=> 'Join us',
	'sm_contact'			=> 'Kontakt',
	'sm_database'			=> 'Datenbank',
	'sm_backup'			=> 'Backup',
	'sm_restore'			=> 'Wiederherstellen',
	'sm_optimize'			=> 'Optimieren',
	'sm_event'			=> 'Ereignisse',
	'sm_perm'				=> 'Berechtigung',
	'sm_perm_list'		=> 'Berechtigungsliste',
	'sm_logs'				=> 'Protokoll',
	'sm_logs_error'		=> 'Fehlerprotokoll',		
	'sm_maps'				=> 'Karten',
	'sm_match'			=> 'Begegnungen',
	'sm_navi'				=> 'Navigation',
	'sm_news'				=> 'News',
	'sm_newscat'			=> 'Kategorie',
	'sm_network'			=> 'Netzwerk',
	'sm_newsletter'		=> '!Newsletter',		
	'sm_profile'			=> 'Profilefelder',
	'sm_ranks'			=> 'Ränge',
	'sm_teamspeak'		=> 'Teamspeak',
	'sm_gameserver'		=> 'Gameserver',
	
	
	'sm_settings'		=> 'Einstellungen',
	'sm_settings_teams'	=> 'Teameinstellungen',
	'sm_settings_user'	=> 'Benutzereinstellungen',
	
	'sm_games'			=> 'Spiele',
	'sm_gallery'			=> 'Galerie',
	'sm_group'			=> 'Gruppe',
	'sm_forum'			=> 'Forum',
	'sm_index'			=> 'Übersicht',
	'sm_users'			=> 'Benutzer',
	'sm_group'			=> 'Gruppe',
	'sm_teams'			=> 'Teams',
=======
	/* Styles */
	'sm_themes'			=> 'Themes',
	'sm_addnew'			=> 'Hinzufügen',
	'sm_create'			=> 'Erstellen',
	'sm_manage'			=> 'Eingestellungen',
	'sm_export'			=> 'Exportieren',
	
	'sm_settings_groups'	=> 'Gruppeneinstellung',
	
	'sm_votes'	=> 'Umfragen',
	
	'sm_teams_match'	=> 'Begegnungen',
	'sm_teams_training'	=> 'Training',
	'sm_teams_settings'	=> 'Einstellung',
	'sm_news'		=> 'News',
	'sm_news_cat'	=> 'Kategorie',
	'sm_index'		=> 'Übersicht',
	'sm_training'	=> 'Training',
	'sm_authlist'			=> 'Berechtigungsfelder',
	'sm_bugtracker'		=> 'Bugtracker',
	'sm_cash'				=> 'Clankasse',
	'sm_changelog'		=> 'Changelog',
	'sm_downloads'		=> 'Downloads',
	'sm_overview'			=> 'Übersicht',
	'sm_fightus'			=> 'Fight us',
	'sm_joinus'			=> 'Join us',
	'sm_contact'			=> 'Kontakt',
	'sm_database'			=> 'Datenbank',
	'sm_backup'			=> 'Backup',
	'sm_restore'			=> 'Wiederherstellen',
	'sm_optimize'			=> 'Optimieren',
	'sm_event'			=> 'Ereignisse',
	'sm_perm'				=> 'Berechtigung',
	'sm_perm_list'		=> 'Berechtigungsliste',
	'sm_logs'				=> 'Protokoll',
	'sm_logs_error'		=> 'Fehlerprotokoll',		
	'sm_maps'				=> 'Karten',
	'sm_match'			=> 'Begegnungen',
	'sm_navi'				=> 'Navigation',
	'sm_news'				=> 'News',
	'sm_newscat'			=> 'Kategorie',
	'sm_network'			=> 'Netzwerk',
	'sm_newsletter'		=> '!Newsletter',		
	'sm_profile'			=> 'Profilefelder',
	'sm_ranks'			=> 'Ränge',
	'sm_server'		=> 'Server',
	
	
	'sm_settings'		=> 'Einstellungen',
	'sm_settings_teams'	=> 'Teameinstellungen',
	'sm_settings_user'	=> 'Benutzereinstellungen',
	
	'sm_games'			=> 'Spiele',
	'sm_gallery'			=> 'Galerie',
	'sm_group'			=> 'Gruppe',
	'sm_forum'			=> 'Forum',
	'sm_index'			=> 'Übersicht',
	'sm_users'			=> 'Benutzer',
	'sm_group'			=> 'Gruppe',
	'sm_teams'			=> 'Teams',
>>>>>>> .r85
	/* Submenü */
	
	/* Sektion */
	'section'	=> array(
		SECTION_TEAMSPEAK	=> 'Teamspeak',
		SECTION_DOWNLOADS	=> 'Downloads',
		SECTION_MATCH		=> 'Begegnungen',
	),
	
	/* sprintf: Allgemein */
	'sprintf_head'				=> '%s Administration',
	'sprintf_create'			=> '%s hinzufügen',
	'sprintf_update'			=> '%s bearbeiten: %s',
	
	'sprintf_new_create'		=> 'Neue %s erstellen',
	'sprintf_new_createn'		=> 'Neuen %s erstellen',		
	'sprintf_new_creates'		=> 'Neues %s erstellen',
	
	'sprintf_name'				=> '%sname',
	'sprintf_title'				=> '%stitel',
	'sprintf_desc'				=> '%sbeschreibung',
	/* sprintf: Allgemein */
	
	/* sprintf: Spiele */
	'sprintf_tag'				=> '%s Tag',				# game
	'sprintf_image'				=> '%s Bild',				# game, maps, network, newscat
	'sprintf_size'				=> '%sgröße',				# gane
	'sprintf_event'				=> 'am: %s von %s - %s',	# event
	'sprintf_type'				=> '%s Type',				# maps, network, navi, profile
	'sprintf_cat'				=> '%skategorie',			# maps, news
	'sprintf_text'				=> '%stext',				# news
	'sprintf_rating'			=> '%sbewertung',			# news
	
	'sprintf_auth'				=> '%sberechtigung',
	'sprintf_comments'			=> '%skommentare erlauben?',
	'sprintf_create_user'		=> '%s hinzufügen',		
	'sprintf_intern'			=> '<em><b> %s </b></em>',
	'sprintf_list'				=> '%sliste',
	'sprintf_match_intern'		=> '<span style="font-style:italic;">vs. %s</span>',		
	'sprintf_match_name'		=> 'vs. %s',
	'sprintf_message'			=> '%s Nachricht',
	'sprintf_news_title'		=> '<em><b>%s</b></em>',
	'sprintf_normal'			=> '%s',
	'sprintf_overview'			=> '%s Übersicht',
	'sprintf_processing'		=> '%s Bearbeitung',
	'sprintf_right_overview'	=> '%s Rechteübersicht',		
	'sprintf_update_user'		=> '%s bearbeiten: %s',		
	'sprintf_upload'			=> '%s Upload',
	'sprintf_upload_info'		=> 'Abmessung: %s x %s / Größe: %s KB\'s',
	'sprintf_select_format'		=> '&raquo;&nbsp;%s&nbsp;',
	'sprintf_select_format2'	=> '&raquo;&nbsp;%s&nbsp;::&nbsp;%s',
	'sprintf_select_order'		=> '&raquo;&nbsp;nach:&nbsp;%s&nbsp;',
	'sprintf_select_before'		=> '&raquo;&nbsp;vor:&nbsp;%s&nbsp;',
	'sprintf_imagesize'			=> 'Das Bild muss weniger als %d Pixel breit und %d Pixel hoch sein.',
	'sprintf_ajax_fail'			=> 'Kein Suchergebnis!',
	'sprintf_ajax_users'		=> '%s<br />&nbsp;&not;&nbsp;Benutzerlevel: %s<br />&nbsp;&not;&nbsp;Reg: %s<br />&nbsp;&not;&nbsp;Log: %s',
	
	'sprintf_db_create'			=> '|%s| geändert in: %s',
	'sprintf_db_delete'			=> '|%s| gelöscht: %s',
	'sprintf_db_change'			=> '|%s| von: %s auf: %s geändert.',
	
	'sprintf_log_create'		=> ' %s geändert in: <b>%s</b>',
	'sprintf_log_change'		=> ' %s geändert von: <b>%s</b> auf: <b>%s</b>.<br />',
	'sprintf_log_delete'		=> ' %s gelöscht: %s',
	
	
	/* allgemein: Standard */
	'common_confirm'		=> 'Bestätigen',
	'common_order'			=> 'Reihenfolge',
	'common_date'			=> 'Datum',
	'common_userlevel'		=> 'Benutzerlevel',
	'common_duration'		=> 'Dauer',
	'common_comments'		=> 'Kommentare',
	'common_view'			=> 'Anzeigen',
	'common_image_delete'	=> 'Bild löschen',
	'common_more'			=> 'Erweitern',
	'common_remove'			=> 'Entfernen',
	'common_language'		=> 'Sprachdatei',
	'common_intern'			=> 'Intern',
	'common_visible'		=> 'Sichtbar',
	'common_no'				=> 'Nein',
	'common_yes'			=> 'Ja',
	'common_cat'			=> 'Kategorie',
	'common_required'		=> 'Mit * markierte Felder sind erforderlich!',
	'common_info_show'		=> 'wird angezeigt',
	'common_info_show2'		=> 'wird nicht angezeigt',
	'common_info_lang'		=> 'wird per Sprachdatei angezeigt',
	'common_info_lang2'		=> 'wird nicht per Sprachdatei angezeigt',
	'common_info_intern'	=> 'wird nur von eingeloggten Benutzern gesehen',
	'common_info_intern2'	=> 'wird von allen gesehen',

	'common_entry_empty'	=> 'Keine Einträge vorhanden.',
	'common_entry_new'		=> 'Neuer Eintrag',
	'common_add'			=> 'Hinzufügen',
	'common_auth'			=> 'Berechtigung',
	'common_comment'		=> 'Kommentar',
	'common_comments_pub'	=> 'Kommentare erlauben',
	'common_input_data'		=> 'Daten eingeben',
	'common_input_option'	=> 'Option',
	'common_input_upload'	=> 'Upload',
	'common_input_standard'	=> 'Standard',
	'common_default'		=> 'Standarteinstellungen',
	'common_delete'			=> 'Löschen',
	'common_desc'			=> 'Beschreibung',
	'common_details'		=> 'Details',
	'common_image'			=> 'Bild',
	'common_member'			=> 'Mitglied',
	'common_members'		=> 'Mitglieder',
	'common_message'		=> 'Nachricht',
	'common_moderator'		=> 'Moderator',		
	'common_moderators'		=> 'Moderatoren',		
	'common_member_empty'	=> 'Keine Mitglieder eingetragen/vorhanden.',
	'common_moderator_empty'=> 'Keine Moderator eingetragen/vorhanden.',
	'common_option_select'	=> 'Option wählen',			
	'common_overview'		=> 'Übersicht',		
	'common_reset'			=> 'Zurücksetzen',
	'common_setting'		=> 'Einstellung',
	'common_settings'		=> 'Einstellungen',		
	'common_submit'			=> 'Absenden',
	'common_upload'			=> 'Upload',
	'common_update'			=> 'Bearbeiten',
	'common_sort'			=> 'Sortieren',
	'common_public'			=> 'Öffentlich',
	'common_page_of'		=> 'Seite <b>%d</b> von <b>%d</b>',
	/* allgemein: Standard */
	
	'return'			=> '<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a></strong>',
	'return_update'		=> '<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a><br /><br /><a href="%s">&laquo;&nbsp;zurück</a></strong>',
	
	/* msg: alle */
	'msg_auth_fail'			=> 'Keine Berechtiung für dieses Modul: <b>%s</b>',
	'msg_confirm_delete'	=> 'Bist du sicher, %s <strong><em>%s</em></strong> gelöscht werden soll?',
	'msg_select_must'		=> 'Es muss ein %s ausgewählt werden!',
	'msg_select_module'		=> 'Es wurde keine Gültige Funktion ausgewählt!',
	'msg_unavailable_size_dir'	=> 'Ordnergröße nicht verfügbar!',
	'msg_unavailable_size_file'	=> 'Dateigröße nicht verfügbar!',
	
	'msg_available_name'	=> 'Name ist schon vergeben, bitte einen anderen eintragen!',
	'msg_available_title'	=> 'Titel ist schon vergeben, bitte einen anderen eintragen!',
	'msg_available_tag'		=> 'Tag ist schon vergeben, bitte einen anderen eintragen!',
			
	'msg_empty_name'		=> 'Bitte ein Namen eintragen!',
	'msg_empty_title'		=> 'Bitte ein Titel eintragen!',
	'msg_empty_desc'		=> 'Bitte eine Beschreibung eintragen!',
	'msg_empty_text'		=> 'Bitte einen Text eintragen!',
	
	'msg_select_type'		=> 'Bitte ein Type auswählen!',
	'msg_select_date'		=> 'Bitte ein Gültiges Datum auswählen!',
	'msg_select_past'		=> 'Bitte ein Datum/Zeit in der Zukunft auswählen!',
	/* msg: alle */
	
	/* msg: ajax */
	'msg_empty_maps'		=> 'Keine Maps vorhanden/eingetragen.',
	'msg_empty_ranks'		=> 'Keine Ränge vorhanden/eingetragen.',
	
	/* games */
	'msg_empty_tag'			=> 'Bitte ein Tag eintragen!',
	/* games */
	
	/* server */
	'msg_empty_ip'			=> 'Bitte eine IP-Adresse vom Server eintragen!',
	'msg_empty_port'		=> 'Bitte einen Port vom Server eintragen!',
	'msg_empty_qport'		=> 'Bitte den QPort vom Server eintragen!',
	/* server */
	
	/* network */
	'msg_empty_url'			=> 'Bitte eine URL (Webadresse) eintragen!',
	/* network */
	
	/* news / news_cat */
	'msg_select_cat'		=> 'Bitte eine Kategorie auswählen!',
	'msg_select_cat_image'	=> 'Bitte ein Kategoriebild auswählen!',
	/* news / news_cat */
	
	/* maps / maps_cat */
	'msg_select_map_file'	=> 'Bitte ein Bild auswählen!',		
	/* maps / maps_cat */
	
	/* profile */
	'msg_select_profile_field'	=> 'Bitte bei <b>%s</b> einen Wert eintragen, dass ist ein Pflichtfeld!',
	/* profile */
	
	/* user */
	'msg_empty_pass'			=> 'Bitte ein Passwort eintragen!',
	'msg_empty_pass_confirm'	=> 'Bitte ein Passwort Bestätigen!',
	'msg_select_pass'			=> 'Bitte ein Passworttype auswählen!',
	'msg_empty_email'			=> 'Bitte ein eMail eintragen!',
	'msg_empty_email_confirm'	=> 'Die eMails stimmen nicht überein Bestätigen!',
	'msg_empty_email_mismatch'	=> 'Die eMails stimmen nicht überein!',
	'msg_empty_pass_mismatch'	=> 'Die Passwörter stimmen nicht überein!',
	/* user */
	
	/* groups */
	'msg_empty_add'				=> 'Keine <i>Neuen</i> oder <i>eingetragene</i> Benutzer ausgwählt/eingetragen!',
	'msg_select_level'			=> 'Bitte ein Gruppenlevel auswählen!',
	
	'msg_select_item'			=> 'Bitte etwas auswählen!',
	
	'msg_empty_map'				=> 'Bitte eine Map eintragen!',
	'msg_empty_rival_name'		=> 'Bitte einen Gegnernamen eintragen!',		
	'msg_empty_rival_tag'		=> 'Bitte einen Gegnerclantag eintragen!',		
	'msg_empty_server'			=> 'Bitte einen Gameserver eintragen!',	
	
	
	
	'msg_must_select_authlist'	=> 'Es muss ein Wähle ein Authfeld aus',		
	'msg_must_select_game'		=> 'Wähle ein Spiel aus',		
	'msg_select_amount'			=> 'Bitte einen Betrag eintragen!',	
	'msg_select_cat'			=> 'Bitte ein Match Art auswählen!',	
	'msg_select_cat'			=> 'Bitte ein Match Art auswählen',	
	'msg_select_cat'			=> 'Bitte eine Kategorie auswählen',	
	'msg_select_desc'			=> 'Bitte eine Beschreibung eintragen',	
	'msg_select_dir'			=> 'Bitte ein Verzeichnis auswählen!',	
	'msg_select_duration'		=> 'Bitte eine Zeitdauer auswählen',		
	'msg_select_file'			=> 'Bitte eine Datei auswählen!',	
	'msg_select_forms'			=> 'Bitte ein Forum auswählen!',	
	'msg_select_forum'			=> 'Bitte ein Hauptforum auswählen!',	
	'msg_select_forum'			=> 'Bitte ein Hauptforum auswählen!',	
	'msg_select_game'			=> 'Bitte ein Spiel auswählen!',	
	'msg_select_game_image'		=> 'Bitte ein Spielbild auswählen!',		
	'msg_select_league'			=> 'Bitte eine Liga auswählen!',	
	'msg_select_map'			=> 'Bitte eine Map auswählen!',	
	'msg_select_map'			=> 'Bitte eine Karte auswählen!',	
	'msg_select_maps'			=> 'Bitte eine Map Auswählen!',	
	'msg_select_match'			=> 'Bitte eine Begegnung auswählen',	
	'msg_select_match_map'		=> 'Bitte Karteninfos eintragen!',		
	'msg_select_member'			=> 'Bitte 1 oder mehrere Mitglieder auswählen!',	
	
	
	'msg_select_no_users'		=> 'Keine Benutzer ausgewählt.',		
	'msg_select_nomembers'		=> 'Keine Teammitglieder ausgewählt oder eingetragen.',		
	'msg_select_option'			=> 'Bitte eine <b>Option</b> auswählen!',	
	'msg_select_order'			=> 'Bitte Reihenfolge auswählen!',	
	'msg_select_order_end'		=> 'am Ende sortieren',		
	'msg_select_pics'			=> 'Bitte ein oder mehrere Bilder auswählen, zum löschen.',	
	'msg_select_profilefield'	=> 'Bitte Profilefeld eintragen!',		
	'msg_select_rank_set'		=> 'Status %s setzen',		
	'msg_select_ranks'			=> 'Bitte einen Rang auswählen!',	
	'msg_select_rival'			=> 'Bitte ein Gegnernamen eintragen',	
	'msg_select_rival_tag'		=> 'Bitte ein Gegnerclantag eintragen',		
	'msg_select_server'			=> 'Bitte ein Gameserver eintragen',	
	'msg_select_sort_team'		=> 'Team fürs sortieren wählen!',		
	'msg_select_team'			=> 'Bitte ein Team auswählen!',	
	'msg_select_team'			=> 'Bitte ein Team auswählen!',	
	'msg_select_team_first'		=> 'Bitte ein Team zuerst auswählen!',		
	'msg_select_type'			=> 'Bitte ein Type auswählen!',	
	'msg_select_type'			=> 'Bitte ein Type / Art auswählen!',	
	'msg_select_url'			=> 'Bitte ein Link eintragen!',	
	'msg_select_user'			=> 'Bitte einen Benutzer auswählen!',	
	'msg_select_user_level'		=> 'Bitte ein Benutzerlevel auswählen!',		
	'msg_select_war'			=> 'Bitte ein Wartype auswählen!',	
	'msg_selected_member'		=> 'Bitte Spieler auswählen die noch nicht eingetragen sind.',		
	'msg_sprintf_noentry'		=> 'Es sind keine %s eingetragen',		
	'select_msg_cat_image'		=> 'Bitte ein Kategoriebild auswählen!',		
	'select_msg_forum_icon'		=> 'Bitte ein Forumicon auswählen!',		
	'select_msg_game_image'		=> 'Bitte ein Spielbild auswählen!',		
	
	'select_rank'				=> 'Rang setzen &raquo;',
	'select_ranks_rights'		=> 'Gruppenrechte geben/nehmen',		
	'sprintf_msg_select'		=> 'Bitte ein(e) <b>%s</b> eintragen!',		
	


	
	
	
	
	'create_forum'			=> 'Neues Forum hinzugefügt.',
	'create_newsletter'		=> 'Neue eMailadresse hinzugefügt.',
	'create_teamspeak'		=> 'Neuen Teamspeak Server hinzugefügt.',
	'create_user'			=> 'Neuen Benutzer hinzugefügt.',
	'create_map'			=> 'Karte hinzugefügt',
	'create_map_cat'		=> 'Kartenkategorie hinzugefügt',

	'update_forum'			=> 'Forumdaten erfolgreich geändert',
	'update_newsletter'		=> 'eMailadresse erfolgreich geändert',
	'update_teamspeak'		=> 'Teamspeakdaten erfolgreich geändert',
	'update_user'			=> 'Benutzerdaten erfolgreich geändert',
	'update_map'			=> 'Karteninformation erfolgreich geändert',
	'update_map_cat'		=> 'Kartenkategorie erfolgreich geändert',

	'delete_confirm_map'		=> 'dass diese Karte:',
	'delete_confirm_map_cat'	=> 'dass diese Kartenkategorie:',
	'delete_log'				=> 'Der oder die Logeinträge wurde gelöscht',
	'delete_log_all'			=> 'Alle Logeinträge wurde gelöscht!',
	'delete_log_error'			=> 'Der oder die Fehlermeldungen wurde gelöscht!',
	'delete_newsletter'			=> 'Die eMailadresse wurde gelöscht!',
	'delete_teamspeak'			=> 'Der Teamspeak wurde gelöscht!',
	'delete_user'				=> 'Der Benutzer wurde gelöscht!',
	'delete_map'				=> 'Die Karte wurde gelöscht!',
	'delete_map_cat'			=> 'Die Kartenkategorie, samt Inahlt wurde gelöscht!',

	

	/*	Hauptinformationen	*/
	
	
	'index_session'		=> 'als Administrator Abmelden',
	'index_logout'		=> 'Abmelden',
	'header_acp'	=> 'Adminhauptseite',

	'mark_all'			=> 'alle markieren',
	'mark_deall'		=> 'keine Auswahl',
	'mark_yes'			=> 'Ja aktivieren',
	'mark_no'			=> 'Nein aktivieren',
	
	/* index */
	'index'		=> 'Hauptseite Clanportal',
	'explain'	=> 'Willkommen im Adminbereich des Clanportals von CMS-Phoenix.de usw.',
	/* index */
	

	'ON'	=> 'Aktiv',// This is for GZip compression
	'OFF'	=> 'Inaktiv',

/*	Berechtigung	*/
	'auth_guest'	=> 'Gast',
	'auth_user'		=> 'Benutzer',
	'auth_trial'	=> 'Trial',
	'auth_member'	=> 'Member',
	'auth_mod'		=> 'Moderator',
	'auth_admin'	=> 'Administrator',

	'switch_level' => array(
						GUEST	=> 'Gast',
						USER	=> 'Benutzer',
						TRIAL	=> 'Trial',
						MEMBER	=> 'Member',
						MOD		=> 'Moderator',
						ADMIN	=> 'Administrator',
					),

	'auth_cash'			=> 'Clankasse',
	'auth_contact'		=> 'Kontakt',
	'auth_event'		=> 'Event',
	'auth_fightus'		=> 'Fightus',
	'auth_forum'		=> 'Forum',
	'auth_forum_perm'	=> 'Forumberechtigung',
	'auth_gallery'		=> 'Galerie',
	'auth_games'		=> 'Spiele',
	'auth_groups'		=> 'Gruppen',
	'auth_groups_perm'	=> 'Gruppen Befugnisse',		
	'auth_joinus'		=> 'Joinus',
	'auth_match'		=> 'Begegnungen',
	'auth_navi'			=> 'Navigation',
	'auth_network'		=> 'Network',
	'auth_news'			=> 'News',
	'auth_news_public'	=> 'News veröffentlichen',		
	'auth_newscat'		=> 'Newskategorien',
	'auth_newsletter'	=> 'Newsletter',		
	'auth_ranks'		=> 'Ränge',
	'auth_server'		=> 'Server',
	'auth_teams'		=> 'Teams',
	'auth_teamspeak'	=> 'Teamspeak',
	'auth_training'		=> 'Training',
	'auth_user'			=> 'Benutzer',
	'auth_user_perm'	=> 'Benutzer Befugnisse',
	'auth_download'		=> 'Download',
	'auth_download_cat'	=> 'Downloadkategorien',
	'auth_maps'			=> 'Karten',

	'auths' => array(
				'auth_cash'				=> 'Clankasse',
				'auth_contact'			=> 'Kontakt',
				'auth_event'			=> 'Event',
				'auth_fightus'			=> 'Fightus',
				'auth_forum'			=> 'Forum',
				'auth_forum_perm'		=> 'Forumberechtigung',
				'auth_gallery'			=> 'Galerie',
				'auth_games'			=> 'Spiele',
				'auth_groups'			=> 'Gruppen',
				'auth_groups_perm'		=> 'Gruppen Befugnisse',
				'auth_joinus'			=> 'Joinus',	
				'auth_match'			=> 'Begegnungen',	
				'auth_navi'				=> 'Navigation',
				'auth_network'			=> 'Network',	
				'auth_news'				=> 'News',
				'auth_news_public'		=> 'News veröffentlichen',
				'auth_newscat'			=> 'Newskategorien',	
				'auth_newsletter'		=> 'Newsletter',
				'auth_ranks'			=> 'Ränge',	
				'auth_server'			=> 'Server',	
				'auth_teams'			=> 'Teams',	
				'auth_teamspeak'		=> 'Teamspeak',
				'auth_training'			=> 'Training',	
				'auth_user'				=> 'Benutzer',
				'auth_user_perm'		=> 'Benutzer Befugnisse',
				'auth_download'			=> 'Download',
				'auth_download_cat'		=> 'Downloadkategorien',
				'auth_maps'				=> 'Karten',
				'auth_styles'			=> 'Templates/Stlye',
			),

	'image_filesize'	=> 'Die Dateigröße muss kleiner als %d KB sein.',
	'image_imagesize'	=> 'Das Bild muss weniger als %d Pixel breit und %d Pixel hoch sein.',

	'empty_site' => "<html><head><title></title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"></head><body bgcolor=\"#FFFFFF\" text=\"#000000\"></body></html>",
	
	'size_by' => ' Bytes',
	'size_kb' => ' KB',
	'size_mb' => ' MB',
	'size_gb' => ' GB',
	
	
));

?>