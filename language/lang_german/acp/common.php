<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'content_encoding'	=> 'iso-8859-1',
	'content_direction'	=> 'ltr',
	'content_footer'	=> '<div class="copyright">powered by <a href="http://www.cms-phoenix.de/" target="_blank">CMS-Phoenix.de</a> &copy; 2009-2012 by Phoenix &bull; Version: %s</div>',

	/*	Hauptinformationen	*/
	'acp_overview'	=> 'Admin-Übersicht',
	'acp_logout'	=> 'Abmelden',
	'acp_session'	=> 'als Administrator Abmelden',

	/* acp menu cat */
	'acp_cat_general'		=> 'Allgemein',
	'acp_cat_clan'			=> 'Clan',
	'acp_cat_usergroups'	=> 'Benutzer & Gruppen',
	'acp_cat_forums'		=> 'Forum',
	'acp_cat_permission'	=> 'Berechtigungen',
	'acp_cat_system'		=> 'System',

	/* acp menu label */
	'acp_label_launch'		=> 'Direktzugriff',
	'acp_label_settings'	=> 'Seiten-Einstellungen',
	'acp_label_news'		=> 'News-Einstellungen',
	'acp_label_other'		=> 'sonstiges',
	
	'acp_label_database'	=> 'Datenbank',
	'acp_label_systeminfo'	=> 'Systeminfos',
	'acp_label_menu'		=> 'Menüverwaltung',
	'acp_label_mapspics'	=> 'Spiele & Karten',
	'acp_label_trainwar'	=> 'Begegnungen & Training',
	'acp_label_user'		=> 'Benutzer',
	'acp_label_forum'		=> 'Forum',
	'acp_label_specific'	=> 'Spezifische Benutzer',
	
	'acp_label_auth'		=> 'Berechitungen',
	'acp_label_group'		=> 'Benutzergruppen',
	'acp_label_server'		=> 'Server Managment',
	'acp_label_team'        => 'Teams verwalten',
	'acp_label_usergroups'	=> 'Benutzergruppen',
	'acp_label_setting'		=> 'Labelverwaltung',
	
	'acp_label_show'		=> 'Berechtigung anzeigen',
	
	/* acp menu */
	'acp_index'			=> 'Übersicht',
	'acp_event'			=> 'Ereignisse',
	'acp_training'		=> 'Training',
	'acp_match'			=> 'Begegnungen',
	'acp_user'			=> 'Benutzer',
	'acp_users'			=> 'Benutzer',
	'acp_gallery'		=> 'Galerie',
	'acp_downloads'		=> 'Downloads',
	'acp_network'		=> 'Netzwerk',
	'acp_rate'			=> 'Bewertung',
	'acp_ranks'			=> 'Ränge',
	'acp_ranks_forum'	=> 'Foren-Ränge',
	'acp_ranks_page'	=> 'Seiten-Ränge',
	'acp_ranks_team'	=> 'Team-Ränge',
	'acp_icons'			=> 'Symbole',
	'acp_navi'			=> 'Navigation',
	'acp_maps'			=> 'Karten',
	'acp_games'			=> 'Spiele',
	'acp_cash'			=> 'Clankasse',
	'acp_cashuser'		=> 'Benutzer',
	'acp_cashtype'		=> 'Art',
	'acp_phpinfo'		=> 'PHP-Infomationen',
	
	'acp_news'			=> 'News',
	'acp_newscat'		=> 'Newskategorie',
	'acp_send'			=> 'Eingesendete News',
	'acp_newsletter'	=> 'Newsletter',
	
	'acp_server'		=> 'Server',
	'acp_gameq'			=> 'Server Typen',
	'acp_profile'		=> 'Profilefelder',
	
	'acp_teams'				=> 'Teams',
#	'acp_teams_match'		=> 'Begegnungen',
#	'acp_teams_training'	=> 'Training',
#	'acp_teams_settings'	=> 'Einstellung',

	'acp_show_admin'		=> 'Administratoren',

	'acp_groups'		=> 'Benutzergruppen',

	'acp_forum'			=> 'Forum',
	
	'acp_database'			=> 'Datenbank',
	'acp_backup'			=> 'Backup',
	'acp_restore'			=> 'Wiederherstellen',
	'acp_optimize'			=> 'Optimieren',
	
	'acp_forums_all'			=> 'Forenrechte',
	'acp_forums_mod'			=> 'Moderatoren',
	'acp_forums_group'			=> 'Gruppen-Forenrechte',
	'acp_forums_user'			=> 'Benutzer-Forenrechte',
	
	'acp_permission_group'		=> 'Gruppenrechte',
	'acp_permission_gallery'	=> 'Galerie',
	'acp_permission_download'	=> 'Download',
	'acp_permission_admin'		=> 'Administratoren',
	'acp_permission_user'		=> 'Benutzerrechte',
	
	'acp_settings'			=> 'Einstellungen',
	'acp_settings_main'		=> 'Seiten-Einstellungen',
	'acp_settings_calendar'	=> 'Kalender-Einstellungen',
	'acp_settings_module'	=> 'Module-Einstellungen',
	'acp_settings_subnavi'	=> 'Module-Anordnung',
	'acp_settings_upload'	=> 'Bild/Uploadverzeichnisse',
	'acp_settings_other'	=> 'Diverse-Einstellungen',
	'acp_settings_match'	=> 'Begegnungs-Einstellung',
	'acp_settings_gallery'	=> 'Galerie-Einstellung',
	'acp_settings_rating'	=> 'Bewertungs-Einstellung',
	'acp_settings_smain'	=> 'Subansichten-Einstellung',
	'acp_settings_ftp'		=> 'FTP Rechte',
	'acp_settings_phpinfo'	=> 'Support Infos',

	'acp_menu_acp'		=> 'Administratoren-Menü',
	'acp_menu_mcp'		=> 'Moderatoren-Menü',
	'acp_menu_ucp'		=> 'Benutzer-Menü',
	'acp_menu_pcp'		=> 'Seiten-Menü',
	
	'acp_admin_label'		=> 'Administrator-Label',
	'acp_mod_label'			=> 'Forum-Label',
	'acp_forum_label'		=> 'Forum-Label',
	'acp_gallery_label'		=> 'Galerie-Label',
	'acp_user_label'		=> 'Benutzer-Label',
	'acp_download_label'	=> 'Download-Label',
	
	/* sprintf: Allgemein */
#	'stf_select_menu'	=> '&raquo;&nbsp;%s',
	'stf_select_menu'	=> '&not;&nbsp;%s',
	'stf_normal'		=> '%s',
	'stf_intern'		=> '<em><b>%s</b></em>',
	'stf_head'			=> '%s Administration',
	'stf_create'		=> '%s hinzufügen',
	'stf_update'		=> '%s bearbeiten: %s',
	'stf_overview'		=> '%s Übersicht: %s',
	'sprintf_list'		=> '%s komplett bearbeiten',
	'stf_member'		=> '%s Mitglieder: %s',
	'stf_detail'		=> '%s Deatils: %s',
	'stf_upload_info'	=> 'Abmessung: %s / Größe: %s KB\'s',	/* upload info */
	'stf_name'			=> '%sname',
	'sprintf_name'		=> '%sname',
	
	'stf_ajax_more'		=> 'weitere %s Einträge vorhanden ...&nbsp;',
	'stf_ajax_users'	=> '%s<br />&nbsp;&not;&nbsp;Benutzerlevel: %s<br />&nbsp;&not;&nbsp;Reg: %s<br />&nbsp;&not;&nbsp;Log: %s',
#	'stf_overview'			=> '%s Übersicht',

	'create'	=> 'Neuen Eintrag hinzugefügt.',
	'update'	=> 'Eintrag erfolgreich geändert.',
	'upload'	=> 'Datei/Bild erfolgreich hochgeladen.',
	'delete'	=> 'Der Eintrag wurde gelöscht!',
	'confirm'	=> 'das dieser Eintrag:',
	'empty'		=> 'Keine Änderungen vorgenommen.',

	'return_update'			=> '<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a><br /><br /><a href="%s">&laquo;&nbsp;zurück</a></strong>',
	'return_update_main'	=> '<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a><br /><br /><a href="%s">&laquo;&nbsp;%s</a><br /><br /><a href="%s">&laquo;&nbsp;zurück</a></strong>',

	'msg_sizedir_empty'		=> 'Leer',
#	'msg_select_match'		=> 'Bitte eine Begegnung auswählen!',
	'notice_auth_fail'			=> 'Keine Berechtiung für dieses Modul: <b>%s</b>',
	'notice_empty_maps'			=> 'Keine Maps vorhanden/eingetragen.',
	'notice_select_file'		=> 'Bitte eine Datei auswählen!',
	'notice_select_cat_image'	=> 'Bitte ein Kategoriebild auswählen!',
	'notice_select_team'		=> 'Bitte ein Team auswählen!',
	'notice_select_match_no'	=> 'Keine Begegnung vorhanden!',
	'notice_select_team_first'	=> 'Bitte ein Team zuerst auswählen!',
	'notice_select_map'			=> 'Bitte eine Karte auswählen!',
	'notice_select_level'		=> 'Bitte ein Benutzerlevel auswählen!',
	'notice_select_game_image'	=> 'Bitte ein Spielbild auswählen!',
	'notice_select_rank'		=> 'Bitte einen Rang auswählen!',
	'notice_select_type'		=> 'Bitte ein Type auswählen!',
	'notice_select_war'			=> 'Bitte ein Wartype auswählen!',
	'notice_select_league'		=> 'Bitte eine Liga auswählen!',
	'notice_select_image'		=> 'Bitte ein Bild auswählen!',
	
	'notice_select_permission'	=> 'Rechte geben/nehmen',
	'notice_confirm_delete'		=> 'Bist du sicher, %s <strong><em>%s</em></strong> gelöscht werden soll?',
	'notice_confirm_team'		=> 'dass dieser Spiele(r): %s vom dem Team:',
	'notice_confirm_group'		=> 'dass dieser Spiele(r): %s von der Gruppe:',
	'notice_select_cat'			=> 'Bitte eine Kategorie auswählen!',
	'notice_select_rank_set'	=> 'Rang \'%s\' setzen',
	
	'notice_select_profile'		=> 'Bitte bei <b>%s</b> einen Wert eintragen, dass ist ein Pflichtfeld!',
	
	'notice_no_changes'		=> 'Keine Änderungen vorgenommen.',

	'error_empty_ucreate'	=> 'Keine <i>Neuen</i> oder <i>eingetragene</i> Benutzer ausgwählt/eingetragen!',
	'error_imagesize'		=> 'Das Bild muss weniger als %d Pixel breit und %d Pixel hoch sein.',
	
	'error_input_title'		=> 'Bitte ein <b>Titel</b> eingeben!',
	'error_input_name'		=> 'Bitte ein <b>Namen</b> eingeben!',
	'error_input_rival'		=> 'Bitte ein <b>Gegner</b> eingeben!',
	'error_input_desc'		=> 'Bitte eine <b>Beschreibung</b> eingeben!',
	'error_input_text'		=> 'Bitte einen <b>Text</b> eingeben!',
	'error_input_clantag'	=> 'Bitte ein <b>Clantag</b> eingeben!',
	'error_input_server'	=> 'Bitte ein <b>Server</b> eingeben!',
	'error_input_user'		=> 'Bitte <b>gültige Benutzernamen</b> eingeben!',
	
	'error_select_league'	=> 'Bitte eine <b>Liga</b> auswählen!',
	'error_select_war'		=> 'Bitte ein <b>Wartype</b> auswählen!',
	'error_select_newscat'	=> 'Bitte eine <b>Newskategorie</b> auswählen!',
	'error_select_type'		=> 'Bitte ein <b>Type</b> auswählen!',
	'error_select_game'		=> 'Bitte ein <b>Spiel</b> auswählen!',
	'error_select_level'	=> 'Bitte ein <b>Benutzerlevel</b> auswählen!',
	'error_select_past'		=> 'Bitte kein <b>verganges</b> Datum auswählen',
	'error_select_team'		=> 'Bitte ein <b>Team</b> auswählen!',
	'error_select_maps'		=> 'Bitte eine <b>Karte(n)</b> Auswählen!',
	'error_select_past'		=> 'Bitte ein <b>Datum/Zeit</b> in der Zukunft auswählen!',
	'error_select_file'		=> 'Bitte eine <b>Datei</b> auswählen!',
	'error_select_user'		=> 'Bitte einen <b>Benutzer</b> auswählen!',
	
	
	
#	'msg_select_set_rank'		=> 'Rang setzen &raquo;',
#	'msg_select_item'			=> 'Bitte etwas auswählen!',
#	'msg_select_rights_group'	=> 'Gruppenrechte geben/nehmen',
#	'msg_select_maps'		=> 'Bitte eine Map Auswählen!',	

#	'com_required'		=> 'Mit * markierte Felder sind erforderlich!',
	'com_required'		=> 'Rot gekenntzeichnete Felder sind erforderlich!',
	'com_empty'			=> 'Keine Einträge vorhanden.',
	'com_remove'		=> 'Entfernen',
	'com_go'			=> 'Los',
	'com_delete'		=> 'Löschen',
	'com_select_option'	=> 'Option wählen',
	
	'com_view'			=> 'Anzeigen',			/* permission, forum, groups */
	'com_noview'		=> 'nicht Anzeigen',	/* permission, forum, groups */
	'com_add'			=> 'Benutzer hinzufügen',
	'com_add_explain'	=> 'Benutzer über das Textfeld hinzufügen, jeder Benutzer eine extra Zeile.',
	
	'mark_no'		=> 'Nein aktivieren',
	'mark_yes'		=> 'Ja aktivieren',
	'mark_all'		=> 'alle markieren',
	'mark_deall'	=> 'alles abwählen',
	'mark_invert'	=> 'Markierung umkehren',

	'size_by'	=> ' Bytes',
	'size_kb'	=> ' kB',
	'size_mb'	=> ' MB',
	'size_gb'	=> ' GB',
	
	'sync'		=> 'Sync',

	'auth_guest'	=> 'Gast',
	'auth_user'		=> 'Benutzer',
	'auth_trial'	=> 'Trial / auf Probe',
	'auth_member'	=> 'Member / Mitglieder',
	'auth_mod'		=> 'Mod / CoLeader',
	'auth_admin'	=> 'Admin / Leader',
	
	'radio:yesno'	=> array(1 => $lang['com_yes'], 0 => $lang['com_no']),
	'radio:acpview'	=> array(1 => 'als Listenansicht', 0 => 'nach Vorgabe der Einstellungen'),
	'radio:match'	=> array(0 => 'als Auswählpunkte', 1 => 'als Dropdownmenü'),
	'radio:caldays'	=> array(0 => 'Sonntag', 1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag', 5 => 'Freitag', 6 => 'Samstag'),
	'radio:calview'	=> array(0 => 'als Listenansicht', 1 => 'als Blockansicht'),
	'radio:news'	=> array(0 => 'als Listenansicht', 1 => 'als Blockansicht'),
	
	'stf_log_create'		=> ' %s hinzugefügt: <b>%s</b>',
	'stf_log_change'		=> ' %s geändert von: <b>%s</b> auf: <b>%s</b>.',
	'stf_log_delete'		=> ' %s gelöscht: %s',

	'com_confirm'			=> 'Bestätigen',
	'com_image_delete'	=> 'Bild löschen',
	'com_never'			=> 'Nie',
	'empty_site' => "<html><head><title></title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"></head><body bgcolor=\"#FFFFFF\" text=\"#000000\"></body></html>",
	
	'sprintf_match_name'		=> 'vs. %s',
	'sprintf_match_intern'		=> '<span style="font-style:italic;">vs. %s</span>',
	
	'msg_select_gametype'		=> 'Bitte ein Spieltype auswählen!',			/* acp_game */
	
	/* added 18.07 */
#	'sm_manage'			=> 'Verwalten',
#	'sm_rights'			=> 'Rechte',
#	
#	'a_txt'		=> '<a href="%s" title="%s">%s</a>',
#	'a_img'		=> '<a href="%s"><img class="icon" src="%s" title="%s" alt="" /></a>',
#	'i_icon'	=> '<img class="icon" src="%s" title="%s" alt="" />',
#	'i_iconn'	=> '<img src="%s" title="%s" alt="" />',
#						/* acp_index, acp_news, index */
#	'sprintf_ajax_fail'			=> 'Kein Suchergebnis!',
#	
#	
#	'sprintf_db_create'			=> '|%s| geändert in: %s',
#	'sprintf_db_delete'			=> '|%s| gelöscht: %s',
#	'sprintf_db_change'			=> '|%s| von: %s auf: %s geändert.',
#	'sprintf_count_maps'		=> '%s %s',			# news
#	'sprintf_empty_space'		=> '%s %s',			# index
#	'sprintf_empty_line'		=> '%s - %s',	# Menü
	/* sprintf: Allgemein */
#	'sprintf_create_cat'	=> '%s hinzufügen',
#	'sprintf_cat_create'	=> '%s hinzufügen',
#	'sprintf_create_user'	=> '%s hinzufügen',
#	'sprintf_update_cat'	=> '%s bearbeiten: %s',
#	'sprintf_cat_update'	=> '%s bearbeiten: %s',
#	'sprintf_update_user'	=> '%s bearbeiten: %s',
#	'sprintf_new_create'	=> 'Neue %s erstellen',
#	'sprintf_new_createn'	=> 'Neuen %s erstellen',
#	'sprintf_new_creates'	=> 'Neues %s erstellen',
#	'sprintf_new_add'		=> 'Neue %s hinzufügen',
#	'sprintf_new_addn'		=> 'Neuen %s hinzufügen',
#	'sprintf_new_adds'		=> 'Neues %s erstellen',
	
#	'sprintf_title'			=> '%stitel',
#	'sprintf_desc'			=> '%sbeschreibung',
	/* sprintf: Spiele */
#	'sprintf_tag'				=> '%s Tag',				# game
#	'sprintf_image'				=> '%s Bild',				# game, maps, network, newscat
#	'sprintf_size'				=> '%sgröße',				# gane
#	'sprintf_event'				=> 'am: %s von %s - %s',	# event
#	'sprintf_type'				=> '%s Type',				# maps, network, navi, profile
#	'sprintf_cat'				=> '%skategorie',			# maps, news
#	'sprintf_text'				=> '%stext',				# news
#	'sprintf_rating'			=> '%sbewertung',			# news
#	'sprintf_auth'				=> '%sberechtigung',
#	'sprintf_comments'			=> '%skommentare erlauben?',
#	'sprintf_create_user'		=> '%s hinzufügen',
#	'sprintf_list'				=> '%sliste',
#	
	
#	'sprintf_message'			=> '%s Nachricht',
#	'sprintf_news_title'		=> '<em><b>%s</b></em>',
#	
#	'sprintf_processing'		=> '%s Bearbeitung',
#	'sprintf_right_overview'	=> '%s Rechteübersicht',
#	'sprintf_update_user'		=> '%s bearbeiten: %s',
#	'sprintf_upload'			=> '%s Upload',
#	'stf_select_format'		=> '&raquo;&nbsp;%s&nbsp;',
#	'sprintf_select_format2'	=> '&raquo;&nbsp;%s&nbsp;::&nbsp;%s',
#	'sprintf_select_order'		=> '&raquo;&nbsp;nach:&nbsp;%s&nbsp;',
#	'sprintf_select_before'		=> '&raquo;&nbsp;vor:&nbsp;%s&nbsp;',
#	
#	
	/* allgemein: Standard */
	
#	'common_order'			=> 'Reihenfolge',
#	'common_date'			=> 'Datum',
#	'common_userlevel'		=> 'Benutzerlevel',
#	'common_duration'		=> 'Dauer',
#	'common_comments'		=> 'Kommentare',

#	'common_more'			=> 'Erweitern',
#	'common_language'		=> 'Sprachdatei',
#	'common_intern'			=> 'Intern',
#	'common_visible'		=> 'Sichtbar',
	
#	'common_out'			=> 'Niemals',
#	'common_cat'			=> 'Kategorie',
#	'common_info_show'		=> 'wird angezeigt',
#	'common_info_show2'		=> 'wird nicht angezeigt',
#	'common_info_lang'		=> 'wird per Sprachdatei angezeigt',
#	'common_info_lang2'		=> 'wird nicht per Sprachdatei angezeigt',
#	'common_info_intern'	=> 'wird nur von eingeloggten Benutzern gesehen',
#	'common_info_intern2'	=> 'wird von allen gesehen',
#	'common_resync'			=> 'Synchronisieren',
#	'common_order_u'		=> 'nach Oben verschieben',
#	'common_order_d'		=> 'nach Unten verschieben',
#	'common_entry_empty'	=> 'Keine Einträge vorhanden.',
#	'common_entry_new'		=> 'Neuer Eintrag',
#	'common_add'			=> 'Hinzufügen',
#	'common_auth'			=> 'Berechtigung',
#	'common_comment'		=> 'Kommentar',
#	'common_comments_pub'	=> 'Kommentare erlauben',
#	'common_input_data'		=> 'Daten eingeben',
#	'common_input_option'	=> 'Option',
#	'common_input_upload'	=> 'Upload',
#	'common_input_standard'	=> 'Standard',
#	'common_default'		=> 'Standarteinstellungen',
#	'common_move'			=> 'Verschieben',
#	
#	'common_delete_all'		=> 'Alles löschen',
#	'common_login'			=> 'Login',
#	'common_login_acp'		=> 'Adminlogin',
#	'common_desc'			=> 'Beschreibung',
#	'common_details'		=> 'Details',
#	'common_image'			=> 'Bild',
#	'common_member'			=> 'Mitglied',
#	'common_members'		=> 'Mitglieder',
#	'common_message'		=> 'Nachricht',
#	'common_moderator'		=> 'Moderator',		
#	'common_moderators'		=> 'Moderatoren',		
#	'common_member_empty'	=> 'Keine Mitglieder eingetragen/vorhanden.',
#	'common_moderator_empty'=> 'Keine Moderator eingetragen/vorhanden.',
#	'common_overview'		=> 'Übersicht',		
#	'common_reset'			=> 'Zurücksetzen',
#	'common_setting'		=> 'Einstellung',
#	'common_settings'		=> 'Einstellungen',		
#	'common_submit'			=> 'Absenden',
#	'common_upload'			=> 'Upload',
#	'common_update'			=> 'Bearbeiten',
#	'common_create'			=> 'Hinzufügen',
#	'common_order'			=> 'Ordnen',
#	'common_sort'			=> 'Sortieren',
#	'common_public'			=> 'Öffentlich',
#	'common_page_of'		=> 'Seite <b>%d</b> von <b>%d</b>',
#	'common_on'				=> 'Aktiv',
#	'common_off'			=> 'Inaktiv',
#	'common_active'			=> 'Aktiviert',
#	'common_deactive'		=> 'Deaktiviert',
#	/* allgemein: Standard */
#	
#	'acp_permission'	=> 'Rechteverwaltung',
#	
#	'msg_select_must'		=> 'Es muss ein %s ausgewählt werden!',
#	'navi_navigation'	=> 'Navigation',
#	'return'			=> '<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a></strong>',
#	'msg_select_module'		=> 'Es wurde keine Gültige Funktion ausgewählt!',
#	'msg_unavailable_size_dir'	=> 'Ordnergröße nicht verfügbar!',
#	'msg_unavailable_size_file'	=> 'Dateigröße nicht verfügbar!',
#	'msg_available_name'	=> 'Name ist schon vergeben, bitte einen anderen eintragen!',
#	'msg_available_title'	=> 'Titel ist schon vergeben, bitte einen anderen eintragen!',
#	'msg_available_tag'		=> 'Tag ist schon vergeben, bitte einen anderen eintragen!',
#	'msg_empty_name'		=> 'Bitte ein Namen eintragen!',
#	'msg_empty_title'		=> 'Bitte ein Titel eintragen!',
#	'msg_empty_desc'		=> 'Bitte eine Beschreibung eintragen!',
#	'msg_empty_text'		=> 'Bitte einen Text eintragen!',
#	
#	'msg_empty_tag'			=> 'Bitte ein Tag eintragen!',
#	'msg_empty_ip'			=> 'Bitte eine IP-Adresse vom Server eintragen!',
#	'msg_empty_port'		=> 'Bitte einen Port vom Server eintragen!',
#	'msg_empty_qport'		=> 'Bitte den QPort vom Server eintragen!',
#	'msg_empty_url'			=> 'Bitte eine URL (Webadresse) eintragen!',
#	'msg_select_map_file'	=> 'Bitte ein Bild auswählen!',		
#	
#	'msg_empty_pass'			=> 'Bitte ein Passwort eintragen!',
#	'msg_empty_pass_confirm'	=> 'Bitte ein Passwort Bestätigen!',
#	'msg_select_pass'			=> 'Bitte ein Passworttype auswählen!',
#	'msg_empty_email'			=> 'Bitte ein eMail eintragen!',
#	'msg_empty_email_confirm'	=> 'Die eMails stimmen nicht überein Bestätigen!',
#	'msg_empty_email_mismatch'	=> 'Die eMails stimmen nicht überein!',
#	'msg_empty_pass_mismatch'	=> 'Die Passwörter stimmen nicht überein!',
#	
#	'msg_empty_map'				=> 'Bitte eine Map eintragen!',
#	'msg_must_select_authlist'	=> 'Es muss ein Wähle ein Authfeld aus',		
#	'msg_must_select_game'		=> 'Wähle ein Spiel aus',		
#	'msg_select_desc'			=> 'Bitte eine Beschreibung eintragen',	
#	'msg_select_dir'			=> 'Bitte ein Verzeichnis auswählen!',	
#	'msg_select_duration'		=> 'Bitte eine Zeitdauer auswählen',		
#	
#	
#	'msg_select_gametype'		=> 'Bitte ein Spieltype auswählen!',			/* game */
#	'msg_select_forms'			=> 'Bitte ein Forum auswählen!',	
#	'msg_select_forum'			=> 'Bitte ein Hauptforum auswählen!',	
#	'msg_select_forum'			=> 'Bitte ein Hauptforum auswählen!',	
#	
#	'msg_select_match_map'		=> 'Bitte Karteninfos eintragen!',		
#	'msg_select_member'			=> 'Bitte 1 oder mehrere Mitglieder auswählen!',	
#	'msg_select_no_users'		=> 'Keine Benutzer ausgewählt.',		
#	'msg_select_nomembers'		=> 'Keine Teammitglieder ausgewählt oder eingetragen.',		
#	'msg_select_option'			=> 'Bitte eine <b>Option</b> auswählen!',	
#	'msg_select_order'			=> 'Bitte Reihenfolge auswählen!',	
#	'msg_select_order_end'		=> 'am Ende sortieren',		
#	'msg_select_pics'			=> 'Bitte ein oder mehrere Bilder auswählen, zum löschen.',	
#	'msg_select_profilefield'	=> 'Bitte Profilefeld eintragen!',		
#	'msg_select_rival'			=> 'Bitte ein Gegnernamen eintragen',	
#	'msg_select_rival_tag'		=> 'Bitte ein Gegnerclantag eintragen',		
#	'msg_select_server'			=> 'Bitte ein Gameserver eintragen',	
#	'msg_select_sort_team'		=> 'Team fürs sortieren wählen!',		
#	'msg_select_type'			=> 'Bitte ein Type auswählen!',	
#	'msg_select_type'			=> 'Bitte ein Type / Art auswählen!',	
#	'msg_select_url'			=> 'Bitte ein Link eintragen!',	
#	'msg_select_user_level'		=> 'Bitte ein Benutzerlevel auswählen!',		
#	'msg_selected_member'		=> 'Bitte Spieler auswählen die noch nicht eingetragen sind.',		
#	'msg_sprintf_noentry'		=> 'Es sind keine %s eingetragen',		
#	'select_msg_cat_image'		=> 'Bitte ein Kategoriebild auswählen!',		
#	'select_msg_forum_icon'		=> 'Bitte ein Forumicon auswählen!',		
#	'select_msg_game_image'		=> 'Bitte ein Spielbild auswählen!',		
#	'msg_page_disable'			=> 'Seite ist im Wartungsmodus!',
#	'select_rank'				=> 'Rang setzen &raquo;',
#	'select_ranks_rights'		=> 'Gruppenrechte geben/nehmen',		
#	'sprintf_msg_select'		=> 'Bitte ein(e) <b>%s</b> eintragen!',		
#	'create_forum'			=> 'Neues Forum hinzugefügt.',
#	'create_newsletter'		=> 'Neue eMailadresse hinzugefügt.',
#	'create_teamspeak'		=> 'Neuen Teamspeak Server hinzugefügt.',
#	'create_user'			=> 'Neuen Benutzer hinzugefügt.',
#	'create_map'			=> 'Karte hinzugefügt',
#	'create_map_cat'		=> 'Kartenkategorie hinzugefügt',
#	'update_forum'			=> 'Forumdaten erfolgreich geändert',
#	'update_newsletter'		=> 'eMailadresse erfolgreich geändert',
#	'update_teamspeak'		=> 'Teamspeakdaten erfolgreich geändert',
#	'update_user'			=> 'Benutzerdaten erfolgreich geändert',
#	'update_map'			=> 'Karteninformation erfolgreich geändert',
#	'update_map_cat'		=> 'Kartenkategorie erfolgreich geändert',
#	'delete_confirm_map'		=> 'dass diese Karte:',
#	'delete_confirm_map_cat'	=> 'dass diese Kartenkategorie:',
#	'delete_newsletter'			=> 'Die eMailadresse wurde gelöscht!',
#	'delete_teamspeak'			=> 'Der Teamspeak wurde gelöscht!',
#	'delete_user'				=> 'Der Benutzer wurde gelöscht!',
#	'delete_map'				=> 'Die Karte wurde gelöscht!',
#	'delete_map_cat'			=> 'Die Kartenkategorie, samt Inahlt wurde gelöscht!',
#	'ON'	=> 'Aktiv',// This is for GZip compression
#	'OFF'	=> 'Inaktiv',
#	'auth_cash'				=> 'Clankasse',
#	'auth_contact'			=> 'Kontakt',
#	'auth_event'			=> 'Event',
#	'auth_fightus'			=> 'Fightus',
#	'auth_forum'			=> 'Forum',
#	'auth_forum_perm'		=> 'Forumberechtigung',
#	'auth_gallery'			=> 'Galerie',
#	'auth_games'			=> 'Spiele',
#	'auth_groups'			=> 'Gruppen',
#	'auth_groups_perm'		=> 'Gruppen Befugnisse',		
#	'auth_joinus'			=> 'Joinus',
#	'auth_match'			=> 'Begegnungen',
#	'auth_navi'				=> 'Navigation',
#	'auth_network'			=> 'Network',
#	'auth_news'				=> 'News',
#	'auth_news_public'		=> 'News veröffentlichen',		
#	'auth_newscat'			=> 'Newskategorien',
#	'auth_newsletter'		=> 'Newsletter',		
#	'auth_ranks'			=> 'Ränge',
#	'auth_server'			=> 'Server',
#	'auth_teams'			=> 'Teams',
#	'auth_teamspeak'		=> 'Teamspeak',
#	'auth_training'			=> 'Training',
#	'auth_user'				=> 'Benutzer',
#	'auth_user_perm'		=> 'Benutzer Befugnisse',
#	'auth_downloads'		=> 'Download',
#	'auth_downloads_cat'	=> 'Downloadkategorien',
#	'auth_maps'				=> 'Karten',
#	'auth_themes'			=> 'Templates/Stlye',
#	'auth_votes'			=> 'Umfragen',
#	'auth_server_type'		=> 'Server Typen',
#	'image_filesize'	=> 'Die Dateigröße muss kleiner als %d KB sein.',
#	'image_imagesize'	=> 'Das Bild muss weniger als %d Pixel breit und %d Pixel hoch sein.',
	
#	'common_image_upload'	=> 'Hochladen',
#	'common_image_current'	=> 'Aktuelles Gruppenbild',
	/* folder */
#	'folder'		=> 'Ordner vorhanden',
#	'folder_add'	=> 'Ordner hinzufügen',
#	'folder_bug'	=> 'Ordnerbug, nicht vorhanden',
#	'folder_edit'	=> 'Ordner hat richtige CHMOD Rechte',
#	'folder_error'	=> 'Ordner hat keine CHMOD Rechte',
	/* image */
#	'image'			=> 'Bild vorhanden',
#	'image_add'		=> 'Bild hinzufügen',
#	'image_delete'	=> 'Bild löschen/gelöscht',
#	'image_edit'	=> 'Bild bearbeiten',
#	'image_link'	=> 'Bild verlinkt',
#	'create'	=> 'hinzugefügt',
#	'update'	=> 'bearbeitet',
#	'common_auth_public'	=> 'Öffentlich',
#	'common_auth_register'	=> 'Registriert',
#	'common_auth_trial'		=> 'Trial',
#	'common_auth_member'	=> 'Mitglieder',
#	'common_auth_mod'		=> 'Moderatoren',
#	'common_auth_admin'		=> 'Administrator',
	'sql_duplicate'	=> '%s: "%s" schon vorhanden!',
#	'create'	=> 'Neuen Eintrag hinzugefügt.',
#	'update'	=> 'Eintrag erfolgreich geändert.',
#	'delete'	=> 'Der Eintrag wurde gelöscht!',
#	'confirm'	=> 'das dieser Eintrag:',
#	'msg_input_size'		=> 'Bitte eine <b>Maximale Größe</b> eingeben!',/* download */
#	'msg_select_userlevel'	=> 'Bitte ein Benutzerlevel auswählen!',
#	'msg_empty_ranks'			=> 'Keine Ränge vorhanden/eingetragen.',
#	'msg_sizedir_empty'			=> 'Leer',
#	'select_rank'	=> 'Rang setzen &raquo;',
#	'rights_groups'	=> 'Gruppenrechte geben/nehmen',
#	'rights_teams'	=> 'Teamrechte geben/nehmen',
	/* error msg */
#	'msg_select_date'		=> 'Bitte ein Gültiges <b>Datum</b> auswählen!',				# match

));

$lang = array_merge($lang, array(
	
	'switch_level'	=> array(
		GUEST => $lang['auth_guest'],
		USER => $lang['auth_user'],
		TRIAL => $lang['auth_trial'],
		MEMBER => $lang['auth_member'],
		MOD => $lang['auth_mod'],
		ADMIN => $lang['auth_admin']
	),  
	
));

?>