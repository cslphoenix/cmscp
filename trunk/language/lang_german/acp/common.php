<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'content_encoding'	=> 'iso-8859-1',
	'content_direction'	=> 'ltr',

	/* Hauptmen� */
	'_headmenu_01_main'			=> 'Allgemein',
	'_headmenu_02_forum'		=> 'Forum',
	'_headmenu_03_news'			=> 'News',
	'_headmenu_04_users'		=> 'Benutzer',
	'_headmenu_05_teams'		=> 'Teams',
	'_headmenu_06_groups'		=> 'Gruppen',
	'_headmenu_07_development'	=> 'Entwicklung',		
	'_headmenu_08_games'		=> 'Spiele',
	'_headmenu_09_server'		=> 'Server',
	'_headmenu_10_contact'		=> 'Kontakt',
	'_headmenu_11_database'		=> 'Datenbank',
	'_headmenu_12_template'		=> 'Themes/Template',
	/* Hauptmen� */
	
	/* Submen� */
	'_submenu_authlist'			=> 'Berechtigungsfelder',
	'_submenu_bugtracker'		=> 'Bugtracker',
	'_submenu_cash'				=> 'Clankasse',
	'_submenu_changelog'		=> 'Changelog',
	'_submenu_downloads'		=> 'Downloads',
	'_submenu_overview'			=> '�bersicht',
	'_submenu_fightus'			=> 'Fight us',
	'_submenu_joinus'			=> 'Join us',
	'_submenu_contact'			=> 'Kontakt',
	'_submenu_database'			=> 'Datenbank',
	'_submenu_backup'			=> 'Backup',
	'_submenu_restore'			=> 'Wiederherstellen',
	'_submenu_optimize'			=> 'Optimieren',
	'_submenu_event'			=> 'Ereignisse',
	'_submenu_perm'				=> 'Berechtigung',
	'_submenu_perm_list'		=> 'Berechtigungsliste',
	'_submenu_logs'				=> 'Protokoll',
	'_submenu_logs_error'		=> 'Fehlerprotokoll',		
	'_submenu_maps'				=> 'Karten',
	'_submenu_match'			=> 'Begegnungen',
	'_submenu_navi'				=> 'Navigation',
	'_submenu_news'				=> 'News',
	'_submenu_newscat'			=> 'Kategorie',
	'_submenu_network'			=> 'Netzwerk',
	'_submenu_newsletter'		=> '!Newsletter',		
	'_submenu_profile'			=> 'Profilefelder',
	'_submenu_ranks'			=> 'R�nge',
	'_submenu_teamspeak'		=> 'Teamspeak',
	'_submenu_gameserver'		=> 'Gameserver',
	'_submenu_add_new'			=> 'add new',
	'_submenu_create'			=> 'create',
	'_submenu_manage'			=> 'manage',
	'_submenu_export'			=> 'export',
	'_submenu_settings'			=> 'Einstellungen',
	'_submenu_games'			=> 'Spiele',
	'_submenu_gallery'			=> 'Galerie',
	'_submenu_training'			=> 'Training',
	'_submenu_group'			=> 'Gruppe',
	'_submenu_forum'			=> 'Forum',
	'_submenu_index'			=> '�bersicht',
	'_submenu_users'			=> 'Benutzer',
	'_submenu_group'			=> 'Gruppe',
	'_submenu_teams'			=> 'Teams',
	/* Submen� */
	
	/* sprintf: Allgemein */
	'sprintf_head'				=> '%s Administration',
	'sprintf_create'			=> '%s hinzuf�gen',
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
	'sprintf_size'				=> '%sgr��e',				# gane
	'sprintf_event'				=> 'am: %s von %s - %s',	# event
	'sprintf_type'				=> '%s Type',				# maps, network, navi, profile
	'sprintf_cat'				=> '%skategorie',			# maps, news
	'sprintf_text'				=> '%stext',				# news
	'sprintf_rating'			=> '%sbewertung',			# news
	
	'sprintf_auth'				=> '%sberechtigung',
	'sprintf_comments'			=> '%skommentare erlauben?',
	'sprintf_create_user'		=> '%s hinzuf�gen',		
	'sprintf_intern'			=> '<em><b> %s </b></em>',
	'sprintf_list'				=> '%sliste',
	'sprintf_match_intern'		=> '<span style="font-style:italic;">vs. %s</span>',		
	'sprintf_match_name'		=> 'vs. %s',
	'sprintf_message'			=> '%s Nachricht',
	'sprintf_news_title'		=> '<em><b>%s</b></em>',
	'sprintf_normal'			=> '%s',
	'sprintf_overview'			=> '%s �bersicht',
	'sprintf_processing'		=> '%s Bearbeitung',
	'sprintf_right_overview'	=> '%s Rechte�bersicht',		
	'sprintf_update_user'		=> '%s bearbeiten: %s',		
	'sprintf_upload'			=> '%s Upload',
	'sprintf_upload_info'		=> 'Abmessung: %s x %s / Gr��e: %s KB\'s',
	'sprintf_select_format'		=> '&raquo;&nbsp;%s&nbsp;',
	'sprintf_select_format2'	=> '&raquo;&nbsp;%s&nbsp;::&nbsp;%s',
	'sprintf_select_order'		=> '&raquo;&nbsp;nach:&nbsp;%s&nbsp;',
	'sprintf_select_before'		=> '&raquo;&nbsp;vor:&nbsp;%s&nbsp;',
	'sprintf_imagesize'			=> 'Das Bild muss weniger als %d Pixel breit und %d Pixel hoch sein.',
	'sprintf_ajax_fail'			=> 'Kein Suchergebnis!',
	'sprintf_ajax_users'		=> '%s<br />&nbsp;&not;&nbsp;Benutzerlevel: %s<br />&nbsp;&not;&nbsp;Reg: %s<br />&nbsp;&not;&nbsp;Log: %s',
	'sprintf_db_create'			=> '|%s| ge�ndert in: %s',
	'sprintf_db_delete'			=> '|%s| gel�scht: %s',
	'sprintf_db_change'			=> '|%s| von: %s auf: %s ge�ndert.',
	
	/* allgemein: Standard */
	'common_confirm'		=> 'Best�tigen',
	'common_order'			=> 'Reihenfolge',
	'common_date'			=> 'Datum',
	'common_userlevel'		=> 'Benutzerlevel',
	'common_duration'		=> 'Dauer',
	'common_comments'		=> 'Kommentare',
	'common_view'			=> 'Anzeigen',
	'common_image_delete'	=> 'Bild l�schen',
	'common_more'			=> 'Erweitern',
	'common_remove'			=> 'Entfernen',
	'common_language'		=> 'Sprachdatei',
	'common_intern'			=> 'Intern',
	'common_visible'		=> 'Sichtbar',
	'common_no'				=> 'Nein',
	'common_yes'			=> 'Ja',
	'common_cat'			=> 'Kategorie',
	'common_required'		=> 'Mit * markierte Felder sind erforderlich!',

	'common_entry_empty'	=> 'Keine Eintr�ge vorhanden.',
	'common_entry_new'		=> 'Neuer Eintrag',
		
	'common_add'			=> 'Hinzuf�gen',
	'common_auth'			=> 'Berechtigung',
	
	'common_comment'		=> 'Kommentar',
	'common_comments_pub'	=> 'Kommentare erlauben',
	'common_input_data'		=> 'Daten eingeben',
	'common_input_option'	=> 'Option',
	'common_input_upload'	=> 'Upload',
	'common_input_standard'	=> 'Standard',
	'common_default'		=> 'Standarteinstellungen',
	'common_delete'			=> 'L�schen',
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
	
	'common_option_select'	=> 'Option w�hlen',			
	'common_overview'		=> '�bersicht',		
	
	'common_reset'			=> 'Zur�cksetzen',
	'common_setting'		=> 'Einstellung',
	'common_settings'		=> 'Einstellungen',		
	'common_submit'			=> 'Absenden',
	'common_update'			=> 'Bearbeiten',
	'common_upload'			=> 'Upload',
	
	
	
	'common_sort'			=> 'Sortieren',
	'common_public'			=> '�ffentlich',
	/* allgemein: Standard */
	
	'return'			=> '<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a></strong>',
	'return_update'		=> '<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a><br /><br /><a href="%s">&laquo;&nbsp;zur�ck</a></strong>',
	
	/* msg: alle */
	'msg_auth_fail'			=> 'Keine Berechtiung f�r dieses Modul: <b>%s</b>',
	'msg_confirm_delete'	=> 'Bist du sicher, %s <strong><em>%s</em></strong> gel�scht werden soll?',
	'msg_select_must'		=> 'Es muss ein %s ausgew�hlt werden!',
	'msg_select_module'		=> 'Es wurde keine G�ltige Funktion ausgew�hlt!',
	
	'msg_available_name'	=> 'Name ist schon vergeben, bitte einen anderen eintragen!',
	'msg_available_title'	=> 'Titel ist schon vergeben, bitte einen anderen eintragen!',
	'msg_available_tag'		=> 'Tag ist schon vergeben, bitte einen anderen eintragen!',
			
	'msg_empty_name'		=> 'Bitte ein Namen eintragen!',
	'msg_empty_title'		=> 'Bitte ein Titel eintragen!',
	'msg_empty_desc'		=> 'Bitte eine Beschreibung eintragen!',
	'msg_empty_text'		=> 'Bitte einen Text eintragen!',
	
	'msg_select_type'		=> 'Bitte ein Type ausw�hlen!',
	'msg_select_date'		=> 'Bitte ein G�ltiges Datum ausw�hlen!',
	'msg_select_past'		=> 'Bitte ein Datum/Zeit in der Zukunft ausw�hlen!',
	/* msg: alle */
	
	/* msg: ajax */
	'msg_empty_maps'		=> 'Keine Maps vorhanden/eingetragen.',
	'msg_empty_ranks'		=> 'Keine R�nge vorhanden/eingetragen.',
	
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
	'msg_select_cat'		=> 'Bitte eine Kategorie ausw�hlen!',
	'msg_select_cat_image'	=> 'Bitte ein Kategoriebild ausw�hlen!',
	/* news / news_cat */
	
	/* maps / maps_cat */
	'msg_select_map_file'	=> 'Bitte ein Bild ausw�hlen!',		
	/* maps / maps_cat */
	
	/* profile */
	'msg_select_profile_field'	=> 'Bitte bei <b>%s</b> einen Wert eintragen, dass ist ein Pflichtfeld!',
	/* profile */
	
	/* user */
	'msg_empty_pass'			=> 'Bitte ein Passwort eintragen!',
	'msg_empty_pass_confirm'	=> 'Bitte ein Passwort Best�tigen!',
	'msg_select_pass'			=> 'Bitte ein Passworttype ausw�hlen!',
	'msg_empty_email'			=> 'Bitte ein eMail eintragen!',
	'msg_empty_email_confirm'	=> 'Die eMails stimmen nicht �berein Best�tigen!',
	'msg_empty_email_mismatch'	=> 'Die eMails stimmen nicht �berein!',
	'msg_empty_pass_mismatch'	=> 'Die Passw�rter stimmen nicht �berein!',
	/* user */
	
	/* groups */
	'msg_empty_add'				=> 'Keine <i>Neuen</i> oder <i>eingetragene</i> Benutzer ausgw�hlt/eingetragen!',
	'msg_select_level'			=> 'Bitte ein Gruppenlevel ausw�hlen!',
	
	'msg_select_item'			=> 'Bitte etwas ausw�hlen!',
	
	'msg_empty_map'				=> 'Bitte eine Map eintragen!',
	'msg_empty_rival_name'		=> 'Bitte einen Gegnernamen eintragen!',		
	'msg_empty_rival_tag'		=> 'Bitte einen Gegnerclantag eintragen!',		
	'msg_empty_server'			=> 'Bitte einen Gameserver eintragen!',	
	
	
	
	'msg_must_select_authlist'	=> 'Es muss ein W�hle ein Authfeld aus',		
	'msg_must_select_game'		=> 'W�hle ein Spiel aus',		
	'msg_select_amount'			=> 'Bitte einen Betrag eintragen!',	
	'msg_select_cat'			=> 'Bitte ein Match Art ausw�hlen!',	
	'msg_select_cat'			=> 'Bitte ein Match Art ausw�hlen',	
	'msg_select_cat'			=> 'Bitte eine Kategorie ausw�hlen',	
	'msg_select_desc'			=> 'Bitte eine Beschreibung eintragen',	
	'msg_select_dir'			=> 'Bitte ein Verzeichnis ausw�hlen!',	
	'msg_select_duration'		=> 'Bitte eine Zeitdauer ausw�hlen',		
	'msg_select_file'			=> 'Bitte eine Datei ausw�hlen!',	
	'msg_select_forms'			=> 'Bitte ein Forum ausw�hlen!',	
	'msg_select_forum'			=> 'Bitte ein Hauptforum ausw�hlen!',	
	'msg_select_forum'			=> 'Bitte ein Hauptforum ausw�hlen!',	
	'msg_select_game'			=> 'Bitte ein Spiel ausw�hlen!',	
	'msg_select_game_image'		=> 'Bitte ein Spielbild ausw�hlen!',		
	'msg_select_league'			=> 'Bitte eine Liga ausw�hlen!',	
	'msg_select_map'			=> 'Bitte eine Map ausw�hlen!',	
	'msg_select_map'			=> 'Bitte eine Karte ausw�hlen!',	
	'msg_select_maps'			=> 'Bitte eine Map Ausw�hlen!',	
	'msg_select_match'			=> 'Bitte eine Begegnung ausw�hlen',	
	'msg_select_match_map'		=> 'Bitte Karteninfos eintragen!',		
	'msg_select_member'			=> 'Bitte 1 oder mehrere Mitglieder ausw�hlen!',	
	
	
	'msg_select_no_users'		=> 'Keine Benutzer ausgew�hlt.',		
	'msg_select_nomembers'		=> 'Keine Teammitglieder ausgew�hlt oder eingetragen.',		
	'msg_select_option'			=> 'Bitte eine <b>Option</b> ausw�hlen!',	
	'msg_select_order'			=> 'Bitte Reihenfolge ausw�hlen!',	
	'msg_select_order_end'		=> 'am Ende sortieren',		
	'msg_select_pics'			=> 'Bitte ein oder mehrere Bilder ausw�hlen, zum l�schen.',	
	'msg_select_profilefield'	=> 'Bitte Profilefeld eintragen!',		
	'msg_select_rank_set'		=> 'Status %s setzen',		
	'msg_select_ranks'			=> 'Bitte einen Rang ausw�hlen!',	
	'msg_select_rival'			=> 'Bitte ein Gegnernamen eintragen',	
	'msg_select_rival_tag'		=> 'Bitte ein Gegnerclantag eintragen',		
	'msg_select_server'			=> 'Bitte ein Gameserver eintragen',	
	'msg_select_sort_team'		=> 'Team f�rs sortieren w�hlen!',		
	'msg_select_team'			=> 'Bitte ein Team ausw�hlen!',	
	'msg_select_team'			=> 'Bitte ein Team ausw�hlen!',	
	'msg_select_team_first'		=> 'Bitte ein Team zuerst ausw�hlen!',		
	'msg_select_type'			=> 'Bitte ein Type ausw�hlen!',	
	'msg_select_type'			=> 'Bitte ein Type / Art ausw�hlen!',	
	'msg_select_url'			=> 'Bitte ein Link eintragen!',	
	'msg_select_user'			=> 'Bitte einen Benutzer ausw�hlen!',	
	'msg_select_user_level'		=> 'Bitte ein Benutzerlevel ausw�hlen!',		
	'msg_select_war'			=> 'Bitte ein Wartype ausw�hlen!',	
	'msg_selected_member'		=> 'Bitte Spieler ausw�hlen die noch nicht eingetragen sind.',		
	'msg_sprintf_noentry'		=> 'Es sind keine %s eingetragen',		
	'select_msg_cat_image'		=> 'Bitte ein Kategoriebild ausw�hlen!',		
	'select_msg_forum_icon'		=> 'Bitte ein Forumicon ausw�hlen!',		
	'select_msg_game_image'		=> 'Bitte ein Spielbild ausw�hlen!',		
	
	'select_rank'				=> 'Rang setzen &raquo;',
	'select_ranks_rights'		=> 'Gruppenrechte geben/nehmen',		
	'sprintf_msg_select'		=> 'Bitte ein(e) <b>%s</b> eintragen!',		
	


	
	
	
	
	'create_forum'			=> 'Neues Forum hinzugef�gt.',
	'create_newsletter'		=> 'Neue eMailadresse hinzugef�gt.',
	'create_teamspeak'		=> 'Neuen Teamspeak Server hinzugef�gt.',
	'create_user'			=> 'Neuen Benutzer hinzugef�gt.',
	'create_map'			=> 'Karte hinzugef�gt',
	'create_map_cat'		=> 'Kartenkategorie hinzugef�gt',

	'update_forum'			=> 'Forumdaten erfolgreich ge�ndert',
	'update_newsletter'		=> 'eMailadresse erfolgreich ge�ndert',
	'update_teamspeak'		=> 'Teamspeakdaten erfolgreich ge�ndert',
	'update_user'			=> 'Benutzerdaten erfolgreich ge�ndert',
	'update_map'			=> 'Karteninformation erfolgreich ge�ndert',
	'update_map_cat'		=> 'Kartenkategorie erfolgreich ge�ndert',

	'delete_confirm_map'		=> 'dass diese Karte:',
	'delete_confirm_map_cat'	=> 'dass diese Kartenkategorie:',
	'delete_log'				=> 'Der oder die Logeintr�ge wurde gel�scht',
	'delete_log_all'			=> 'Alle Logeintr�ge wurde gel�scht!',
	'delete_log_error'			=> 'Der oder die Fehlermeldungen wurde gel�scht!',
	'delete_newsletter'			=> 'Die eMailadresse wurde gel�scht!',
	'delete_teamspeak'			=> 'Der Teamspeak wurde gel�scht!',
	'delete_user'				=> 'Der Benutzer wurde gel�scht!',
	'delete_map'				=> 'Die Karte wurde gel�scht!',
	'delete_map_cat'			=> 'Die Kartenkategorie, samt Inahlt wurde gel�scht!',

	

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
						GUEST	=> 	'Gast',
						USER	=> 	'Benutzer',
						TRIAL	=> 	'Trial',
						MEMBER	=> 	'Member',
						MOD		=> 	'Moderator',
						ADMIN	=> 	'Administrator',
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
	'auth_news_public'	=> 'News ver�ffentlichen',		
	'auth_newscat'		=> 'Newskategorien',
	'auth_newsletter'	=> 'Newsletter',		
	'auth_ranks'		=> 'R�nge',
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
				'auth_cash'				=> 	'Clankasse',
				'auth_contact'			=> 	'Kontakt',
				'auth_event'			=> 	'Event',
				'auth_fightus'			=> 	'Fightus',
				'auth_forum'			=> 	'Forum',
				'auth_forum_perm'		=> 	'Forumberechtigung',
				'auth_gallery'			=> 	'Galerie',
				'auth_games'			=> 	'Spiele',
				'auth_groups'			=> 	'Gruppen',
				'auth_groups_perm'		=> 	'Gruppen Befugnisse',
				'auth_joinus'			=> 	'Joinus',	
				'auth_match'			=> 	'Begegnungen',	
				'auth_navi'				=> 	'Navigation',
				'auth_network'			=> 	'Network',	
				'auth_news'				=> 	'News',
				'auth_news_public'		=> 	'News ver�ffentlichen',
				'auth_newscat'			=> 	'Newskategorien',	
				'auth_newsletter'		=> 	'Newsletter',
				'auth_ranks'			=> 	'R�nge',	
				'auth_server'			=> 	'Server',	
				'auth_teams'			=> 	'Teams',	
				'auth_teamspeak'		=> 	'Teamspeak',
				'auth_training'			=> 	'Training',	
				'auth_user'				=> 	'Benutzer',
				'auth_user_perm'		=> 	'Benutzer Befugnisse',
				'auth_download'			=> 	'Download',
				'auth_download_cat'		=> 	'Downloadkategorien',
				'auth_maps'				=> 	'Karten',
			),

	'image_filesize'	=> 'Die Dateigr��e muss kleiner als %d KB sein.',
	'image_imagesize'	=> 'Das Bild muss weniger als %d Pixel breit und %d Pixel hoch sein.',

	'empty_site' => "<html><head><title></title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"></head><body bgcolor=\"#FFFFFF\" text=\"#000000\"></body></html>",
	
	
	
));

?>