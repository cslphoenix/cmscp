<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

#	'content_encoding'	=> 'iso-8859-1',
	'content_encoding'	=> 'utf-8',
	'content_direction'	=> 'ltr',
	'CONTENT_FOOTER'	=> '<div class="copyright">powered by <a href="http://www.cms-phoenix.de/" target="_blank">CMS-Phoenix.de</a> &copy; 2009-2012 by Phoenix &bull; Version: %s</div>',

	/*	Hauptinformationen	*/
	'ACP_OVERVIEW'	=> 'Admin-Übersicht',
	'ACP_LOGOUT'	=> 'Abmelden',
	'ACP_SESSION'	=> 'als Administrator Abmelden',

	/* acp menu cat */
	'ACP_CAT_GENERAL'		=> 'Allgemein',
	'ACP_CAT_CLAN'			=> 'Clan',
	'ACP_CAT_USERGROUPS'	=> 'Benutzer & Gruppen',
	'ACP_CAT_FORUMS'		=> 'Forum',
	'ACP_CAT_PERMISSION'	=> 'Berechtigungen',
	'ACP_CAT_SYSTEM'		=> 'System',
	'ACP_CAT_ADDITION'		=> 'Zusatz',

	/* acp menu label */
	'ACP_LABEL_LAUNCH'		=> 'Direktzugriff',
	'ACP_LABEL_SETTINGS'	=> 'Seiten-Einstellungen',
	'ACP_LABEL_NEWS'		=> 'News-Einstellungen',
	'ACP_LABEL_OTHER'		=> 'sonstiges',
	
	'ACP_LABEL_MAPSPICS'	=> 'Spiele & Karten',
	'ACP_LABEL_TRAINWAR'	=> 'Begegnungen & Training',
	'ACP_LABEL_SERVER'		=> 'Server Managment',
	'ACP_LABEL_TEAM'        => 'Teams verwalten',
	'ACP_LABEL_CASH'		=> 'Clankasse',
	
	'ACP_LABEL_FORUM'		=> 'Forum',
	
	'ACP_LABEL_USER'		=> 'Benutzer',
	'ACP_LABEL_USERGROUPS'	=> 'Benutzergruppen',
	
	'ACP_LABEL_SPECIFIC'	=> 'Spezifische Benutzer',
	'ACP_LABEL_SHOW'		=> 'Berechtigung anzeigen',
	'ACP_LABEL_SETTING'		=> 'Labelverwaltung',
	
	'ACP_LABEL_MENU'		=> 'Menüverwaltung',
	'ACP_LABEL_DATABASE'	=> 'Datenbank',
	'ACP_LABEL_DIFFERENT'	=> 'Diverses',
	'ACP_LABEL_SYSTEMINFO'	=> 'Systeminfos',
	'ACP_LABEL_LOGS'		=> 'Protokolle',
	
	/*
	 *	acp
	 */
	'ACP_GAMES'		=> 'Spiele',
	'ACP_MAPS'		=> 'Karten',
	'ACP_TRAINING'	=> 'Training',
	'ACP_INDEX'		=> 'Übersicht',
	'ACP_GROUPS'	=> 'Benutzergruppen',
	'ACP_DOWNLOADS'	=> 'Downloads',
	'ACP_PROFILE'	=> 'Profilefelder',
	'ACP_FORUM'		=> 'Forum',
	'ACP_MATCH'		=> 'Begegnungen',
	'ACP_NAVI'		=> 'Navigation',
	'ACP_GALLERY'	=> 'Galerie',
	'ACP_CASH'		=> 'Clankasse',
	'ACP_TEAMS'		=> 'Teams',
	'ACP_EVENT'		=> 'Ereignisse',
	
	'ACP_RANKS'			=> 'Ränge',
	'ACP_RANKS_FORUM'	=> 'Foren-Ränge',
	'ACP_RANKS_PAGE'	=> 'Seiten-Ränge',
	'ACP_RANKS_TEAM'	=> 'Team-Ränge',
	
	'ACP_ATTACHMENT'		=> 'Anhänge',
	'ACP_ATTACHMENT_GROUPS'	=> 'Gruppierungen',
	
	'ACP_MENU'		=> 'Menü-Verwaltung',
	'ACP_MENU_ACP'	=> 'Administratoren-Menü',
	'ACP_MENU_MCP'	=> 'Moderatoren-Menü',
	'ACP_MENU_UCP'	=> 'Benutzer-Menü',
	'ACP_MENU_PCP'	=> 'Seiten-Menü',
	
	'ACP_PERMISSION'			=> 'Rechteverwaltung',
	'ACP_PERMISSION_ADMIN'		=> 'Administratoren',
	'ACP_PERMISSION_DOWNLOAD'	=> 'Download',
	'ACP_PERMISSION_GALLERY'	=> 'Galerie',
	'ACP_PERMISSION_GROUP'		=> 'Gruppenrechte',
	'ACP_PERMISSION_USER'		=> 'Benutzerrechte',

	'ACP_DATABASE'	=> 'Datenbank',
	'ACP_BACKUP'	=> 'Backup',
	'ACP_RESTORE'	=> 'Wiederherstellen',
	'ACP_OPTIMIZE'	=> 'Optimieren',
	
	'ACP_LABEL'			=> 'Labeltypen',
	'ACP_LABELS_ADMIN'	=> 'Label für Administratoren',
	'ACP_LABELS_FORUM'	=> 'Label für Foren',
	'ACP_LABELS_MOD'		=> 'Label für Moderatoren',
	'ACP_LABELS_GALLERY'	=> 'Label für Galerie',
	'ACP_LABELS_DL'		=> 'Label für Downloads',
	'ACP_LABELS_USER'	=> 'Label für Benutzer',
	
	'ACP_NEWS'			=> 'News',
	'ACP_NEWSCAT'		=> 'Newskategorie',
	'ACP_NEWSLETTER'	=> 'Newsletter',
	'ACP_SEND'			=> 'Eingesendete News',
	
	'ACP_NETWORK'		=> 'Netzwerk',
	'ACP_RATE'			=> 'Bewertung',
	'ACP_SERVER'		=> 'Server',
	'ACP_GAMEQ'			=> 'Server Typen',
	'ACP_USER'			=> 'Benutzer',
	
	'ACP_LOG_ERROR'		=> 'Fehlerprotokoll',
	'ACP_LOG_ACP'		=> 'Administratoren-Protokoll',
	'ACP_LOG_MCP'		=> 'Moderatoren-Protokoll',
	'ACP_LOG_UCP'		=> 'Benutzer-Protokoll',
	
	'ACP_ICONS'			=> 'Symbole',
	'ACP_SMILEYS'		=> 'Smilies',
	'ACP_PHPINFO'		=> 'PHP-Infomationen',
	
	'ACP_CASHBANK'		=> 'Bankdaten',
	'ACP_CASHUSER'		=> 'Benutzer',
	'ACP_CASHTYPE'		=> 'Art',
	
	'USER_ALLOW_VIEWONLINE'	=> 'Kann die Wer ist Online-Übersicht einsehen',
	'USER_ALLOW_SIG'		=> 'Signaturen erlauben',
	'USER_ALLOW_AVATAR'		=> 'Avatar erlauben',
	'USER_ALLOW_PM'			=> 'Persönliche Nachrichten erlauben',
	
#	'acp_label_user'		=> 'Benutzer',
#	'acp_label_auth'		=> 'Berechitungen',
#	'acp_label_group'		=> 'Benutzergruppen',
#	'acp_teams_match'		=> 'Begegnungen',
#	'acp_teams_training'	=> 'Training',
#	'acp_teams_settings'	=> 'Einstellung',
#	'acp_show_admin'		=> 'Administratoren',
#	'acp_show_forum'		=> 'Forum',
#	'acp_forums_all'		=> 'Forenrechte',
#	'acp_forums_mod'		=> 'Moderatoren',
#	'acp_forums_group'		=> 'Gruppen-Forenrechte',
#	'acp_forums_user'		=> 'Benutzer-Forenrechte',
#	'acp_settings'			=> 'Einstellungen',
#	'acp_settings_main'		=> 'Seiten-Einstellungen',
#	'acp_settings_calendar'	=> 'Kalender-Einstellungen',
#	'acp_settings_module'	=> 'Module-Einstellungen',
#	'acp_settings_subnavi'	=> 'Module-Anordnung',
#	'acp_settings_upload'	=> 'Bild/Uploadverzeichnisse',
#	'acp_settings_other'	=> 'Diverse-Einstellungen',
#	'acp_settings_match'	=> 'Begegnungs-Einstellung',
#	'acp_settings_gallery'	=> 'Galerie-Einstellung',
#	'acp_settings_rating'	=> 'Bewertungs-Einstellung',
#	'acp_settings_smain'	=> 'Subansichten-Einstellung',
#	'acp_settings_ftp'		=> 'FTP Rechte',
#	'acp_settings_phpinfo'	=> 'Support Infos',
#	'acp_admin_label'		=> 'Administrator-Label',
#	'acp_mod_label'			=> 'Forum-Label',
#	'acp_forum_label'		=> 'Forum-Label',
#	'acp_gallery_label'		=> 'Galerie-Label',
#	'acp_user_label'		=> 'Benutzer-Label',
#	'acp_download_label'	=> 'Download-Label',
#	'acp_changelog'		=> 'Änderungsprotokoll',
#	'acp_bugtracker'	=> 'Bugtracker',
	
	/* sprintf: Allgemein */
	'STF_SELECT_MENU'	=> '&not;&nbsp;%s',
	'STF_SELECT_MENU2'	=> '&raquo;&nbsp;%s',
	
	'STF_NORMAL'		=> '%s',
	'STF_INTERN'		=> '<em><b>%s</b></em>',
	'STF_HEADER'		=> '%s Administration',
	'STF_CREATE'		=> '%s hinzufügen',
	'STF_UPDATE'		=> '%s bearbeiten: %s',
	'STF_WARS'			=> '%s Begegnungen und/oder Trainings anzeigen',
	'STF_LIST'			=> '%s komplett bearbeiten',
	'STF_MEMBER'		=> '%s Mitglieder: %s',
	
	'STF_UPLOAD'		=> '%sbilder hochladen', /*	maps: ULPOAD	*/
	
	'STF_MATCH_NAME'	=> 'vs. %s',
	'STF_MATCH_INTERN'	=> '<span style="font-style:italic;">vs. %s</span>',
	
	'STF_UPLOAD_INFO'	=> 'Abmessung: <b>%s</b> / Größe: <b>%s %s</b>',	/* upload info */
	'STF_DOWNLOAD_INFO'	=> 'Formate: <b>%s</b> / Größe: <b>%s %s</b>',	/* upload info */
	
	'DL_ALL_FILES'		=> 'Alle Arten zum Download',
	
	'STF_AJAX_USERS'	=> '%s<br />&nbsp;&not;&nbsp;Reg: %s<br />&nbsp;&not;&nbsp;Log: %s',
	
#	'stf_overview'		=> '%s Übersicht: %s',
#	'stf_detail'		=> '%s Deatils: %s',
#	'stf_rival'			=> '%s ',
#	'stf_change'		=> '%s\'s Gegnerinfos ändern',
#	'stf_name'			=> '%sname',
#	'sprintf_name'		=> '%sname',
#	'stf_ajax_more'		=> 'weitere %s Einträge vorhanden ...&nbsp;',
#	'stf_overview'			=> '%s �bersicht',

	'CREATE'	=> 'Neuen Eintrag hinzugefügt.',
	'UPDATE'	=> 'Eintrag erfolgreich geändert.',
	'UPLOAD'	=> 'Datei/Bild erfolgreich hochgeladen.',
	'DELETE'	=> 'Der Eintrag wurde gelöscht!',
	
	'CONFIRM'	=> 'das dieser Eintrag:',	
	'EMPTY'		=> 'Keine Änderungen vorgenommen.',
	
#	'RETURN'				=> '%s<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a></strong>',
	'RETURN_UPDATE'			=> '%s<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a><br /><br /><a href="%s">&laquo;&nbsp;zurück</a></strong>',
	'RETURN_UPDATE_MAIN'	=> '%s<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a><br /><br /><a href="%s">&laquo;&nbsp;%s</a><br /><br /><a href="%s">&laquo;&nbsp;zurück</a></strong>',
	'RETURN_OVERVIEW'		=> '<a href="%s">Zur Übersicht zurückzukehren</a>',

	'MSG_SIZEDIR_EMPTY'			=> 'Leer',
	'MSG_UNAVAILABLE_SIZE_DIR'	=> 'Ordnergröße nicht verfügbar!',
	
	'NOTICE_CONFIRM_DELETE'		=> 'Bist du sicher, %s <strong><em>%s</em></strong> gelöscht werden soll?',

	'NOTICE_SELECT_PERMISSION'	=> 'Rechte geben/nehmen',
	'NOTICE_SELECT_IMAGE'		=> 'Bitte ein Bild auswählen!',
	'NOTICE_SELECT_GAMETYPE'	=> 'Bitte ein Spieltype auswählen!',			/* acp_game */
	'NOTICE_SELECT_TEAM'		=> 'Bitte ein Team auswählen!',
	'NOTICE_SELECT_MATCH'		=> 'Keine Begegnung vorhanden!',
	'NOTICE_SELECT_CAT_IMAGE'	=> 'Bitte ein Kategoriebild auswählen!',
	'NOTICE_SELECT_CAT-IMAGE'	=> 'Bitte eine <b>Kategorie</b> oder ein <b>Bild</b> auswählen',
	
	'NOTICE_SELECT_TEAM_FIRST'	=> 'Bitte ein Team zuerst auswählen!',
	'NOTICE_SELECT_MAP'			=> 'Bitte eine Karte auswählen!',
	'NOTICE_SELECT_GAME_IMAGE'	=> 'Bitte ein Spielbild auswählen!',
	'NOTICE_SELECT_RANK'		=> 'Bitte einen Rang auswählen!',
	
	'NOTICE_SELECT_MACTH'		=> 'Bitte eine Begegnung auswählen!',
	
	'NOTICE_MAPS_NONE'			=> 'Keine Maps vorhanden / eingetragen.',
	'NOTICE_IMAGE_NONE'			=> 'Keine Bilder vorhanden / eingetragen.',
		
#	'notice_auth_fail'			=> 'Keine Berechtiung für dieses Modul: <b>%s</b>',
	'NOTICE_SELECT_FILE'		=> 'Bitte eine Datei auswählen!',
	'NOTICE_SELECT_FORUM'		=> 'Bitte ein Forum auswählen!',
	
#	'notice_select_level'		=> 'Bitte ein Benutzerlevel auswählen!',
#	'notice_select_order'		=> 'Bitte eine Postion auswählen!',
#	'notice_confirm_match'		=> 'dass dieser Spiele(r): %s von dem Match:',
#	'notice_confirm_training'	=> 'dass dieser Spiele(r): %s von dem Training:',
#	'notice_select_cat'			=> 'Bitte eine Kategorie auswählen!',
#	
#	'notice_select_sy'		=> 'Status: \'Spielen\'',
#	'notice_select_sn'		=> 'Status: \'Verhindert\'',
#	'notice_select_sr'		=> 'Status: \'Ersatz\'',
#	'notice_select_profile'		=> 'Bitte bei <b>%s</b> einen Wert eintragen, dass ist ein Pflichtfeld!',
#	'notice_no_changes'		=> 'Keine �nderungen vorgenommen.',

	'STF_SELECT_RANK_SET'	=> 'Rang \'%s\' setzen',

	
	
	'ERROR_INPUT_NAME'		=> 'Bitte ein <b>Namen</b> eingeben!',
	'ERROR_INPUT_TITLE'		=> 'Bitte ein <b>Titel</b> eingeben!',
	'ERROR_INPUT_USER'		=> 'Bitte <b>gültige Benutzernamen</b> eingeben!',
	'ERROR_INPUT_AMOUNT'	=> 'Bitte einen <b>Betrag</b> eingeben!',
	'ERROR_INPUT_DESC'		=> 'Bitte eine <b>Beschreibung</b> eingeben!',
	'ERROR_INPUT_TEXT'		=> 'Bitte einen <b>Text</b> eingeben!',
	'ERROR_INPUT_URL'		=> 'Bitte eine <b>URL</b> eingeben!',
	'ERROR_INPUT_RIVAL'		=> 'Bitte ein <b>Gegner</b> eingeben!',
	'ERROR_INPUT_CLANTAG'	=> 'Bitte ein <b>Clantag</b> eingeben!',
	'ERROR_INPUT_SERVER'	=> 'Bitte ein <b>Server</b> eingeben!',
	'ERROR_INPUT_IP'		=> 'Bitte ein <b>IP-Adresse</b> eingeben!',
	'ERROR_INPUT_PORT'		=> 'Bitte ein <b>Server Port</b> eingeben!',
	'ERROR_INPUT_GAME'		=> 'Bitte ein <b>GameQ Spiel</b> eingeben!',
	'ERROR_INPUT_DPORT'		=> 'Bitte ein <b>Standardport</b> eingeben!',
	'ERROR_INPUT_MAXSIZE'	=> 'Bitte eine <b>Maximale Größe</b> eingeben!',
	
	'ERROR_MAIN'			=> 'Bitte eine <b>Kategorie</b> auswählen!',
		
	'ERROR_SELECT_MONTH'	=> 'Bitte einen <b>Monat</b> auswählen!',
	'ERROR_SELECT_GAME'		=> 'Bitte ein <b>Spiel</b> auswählen!',
	'ERROR_SELECT_GROUP'	=> 'Bitte eine <b>Gruppe</b> auswählen!',
	'ERROR_SELECT_NEWSCAT'	=> 'Bitte eine <b>Newskategorie</b> auswählen!',
	'ERROR_SELECT_FILE'		=> 'Bitte eine <b>Datei</b> auswählen!',
	'ERROR_SELECT_TEAM'		=> 'Bitte ein <b>Team</b> auswählen!',
	'ERROR_SELECT_PAST'		=> 'Bitte ein <b>Datum/Zeit</b> in der Zukunft auswählen!',
	
	'ERROR_SELECT_MAPS'		=> 'Bitte eine <b>Karte(n)</b> Auswählen!',

#	'error_select_past'		=> 'Bitte kein <b>verganges</b> Datum auswählen',
#	'error_imagesize'		=> 'Das Bild muss weniger als %d Pixel breit und %d Pixel hoch sein.',
#	'error_empty_ucreate'	=> 'Keine <i>Neuen</i> oder <i>eingetragene</i> Benutzer ausgw�hlt/eingetragen!',
#	'error_select_league'	=> 'Bitte eine <b>Liga</b> auswählen!',
#	'error_select_war'		=> 'Bitte ein <b>Wartype</b> auswählen!',
#	'error_select_type'		=> 'Bitte ein <b>Type</b> auswählen!',
#	'error_select_level'	=> 'Bitte ein <b>Benutzerlevel</b> auswählen!',
#	
#	'error_select_user'		=> 'Bitte einen <b>Benutzer</b> auswählen!',

#	'msg_select_set_rank'		=> 'Rang setzen &raquo;',
#	'msg_select_item'			=> 'Bitte etwas auswählen!',
#	'msg_select_rights_group'	=> 'Gruppenrechte geben/nehmen',
#	'msg_select_maps'		=> 'Bitte eine Map Auswählen!',	
#	'com_required'		=> 'Mit * markierte Felder sind erforderlich!',

	'COMMON_ALL_UPDATE'		=> 'Alle bearbeiten', /* games, server */

	'COMMON_LOGIN_ACP'		=> 'Adminlogin',
	'COMMON_OVERVIEW'		=> 'Übersicht',
	'COMMON_CREATE'			=> 'Hinzufügen',
	'COMMON_UPDATE'			=> 'Bearbeiten',
	'COMMON_ERROR'			=> 'Fehlermeldung',
	'COMMON_DELETE'			=> 'Löschen',
	'COMMON_CONFIRM'		=> 'Bestätigen',
	'COMMON_REQUIRED'		=> 'Rot gekenntzeichnete Felder sind erforderlich!',
	'COMMON_BULL'			=> ' &bull; ',
	'COMMON_SELECT_OPTION'	=> 'Option wählen',
	'COMMON_VIEW'			=> 'Anzeigen',			/* permission, forum, groups */
	'COMMON_NONE_VIEW'		=> 'nicht Anzeigen',	/* permission, forum, groups */
	'COMMON_EMPTY'			=> 'Keine Einträge vorhanden.',
	'COMMON_REMOVE'			=> 'Entfernen',
	'COMMON_GO'				=> 'Los',
	'COMMON_ICON_NONE'		=> 'Keine Icons vorhanden oder angelegt.',
	'COMMON_IMAGE_DELETE'	=> 'Bild löschen',
	'COMMON_RESYNC'			=> 'Synchronisieren',
	'COMMON_PAGE_OF'		=> 'Seite <b>%d</b> von <b>%d</b>',
#	'com_postion'			=> ' - nichts ändern - ',

	
	
	'COMMON_ADD'			=> 'Benutzer hinzufügen',
	'COMMON_ADD_EXPLAIN'	=> 'Benutzer über das Textfeld hinzufügen, jeder Benutzer eine extra Zeile.',
	
	'MARK_ALL'		=> 'Alles markieren',
	'MARK_DEALL'	=> 'Alles abwählen',
	'MARK_NO'		=> 'Nein aktivieren',
	'MARK_YES'		=> 'Ja aktivieren',
	'MARK_INVERT'	=> 'Markierung umkehren',

	'SIZE_BY'	=> ' Bytes',
	'SIZE_KB'	=> ' kB',
	'SIZE_MB'	=> ' MB',
	'SIZE_GB'	=> ' GB',

	'PERM_1' => 'erlaubt',
	'PERM_-1' => 'nicht erlaubt',
	
	'STF_LOG_CREATE'	=> ' %s hinzugefügt: <b>%s</b>',
	'STF_LOG_CHANGE'	=> ' %s geändert von: <b>%s</b> auf: <b>%s</b>.',
	'STF_LOG_DELETE'	=> ' %s gelöscht: %s',
#	'STF_LOG_ERROR'		=> ' %s Fehlermeldung: %s',
	
	'STF_COMMON_SORT'	=> '<b>Sortieren:</b> %s',
	'STF_COMMON_SWITCH'	=> '<b>Umschalten:</b> %s',

	'IMAGES'		=> 'Bilder',
	'ARCHIVES'		=> 'Archiv-Dateien',
	'PLAIN_TEXT'	=> 'Text-Dateien',
	'DOCUMENTS'		=> 'Dokumente',
	
	'TIME_UPDATE'	=> 'Aktualisiert',

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
#	'sprintf_message'			=> '%s Nachricht',
#	'sprintf_news_title'		=> '<em><b>%s</b></em>',
#	'sprintf_processing'		=> '%s Bearbeitung',
#	'sprintf_right_overview'	=> '%s Rechte�bersicht',
#	'sprintf_update_user'		=> '%s bearbeiten: %s',
#	'sprintf_upload'			=> '%s Upload',
#	'stf_select_format'		=> '&raquo;&nbsp;%s&nbsp;',
#	'sprintf_select_format2'	=> '&raquo;&nbsp;%s&nbsp;::&nbsp;%s',
#	'sprintf_select_order'		=> '&raquo;&nbsp;nach:&nbsp;%s&nbsp;',
#	'sprintf_select_before'		=> '&raquo;&nbsp;vor:&nbsp;%s&nbsp;',
#	
#	
	/* allgemein: Standard */
	'COMMON_ORDER_U'		=> 'nach Oben verschieben',
	'COMMON_ORDER_D'		=> 'nach Unten verschieben',
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
#	
#	
#	'common_entry_new'		=> 'Neuer Eintrag',
#	'common_add'			=> 'Hinzufügen',
#	'COMMON_AUTH'			=> 'Berechtigung',
#	'common_comment'		=> 'Kommentar',
#	'common_comments_pub'	=> 'Kommentare erlauben',
#	'common_input_data'		=> 'Daten eingeben',
#	'common_input_option'	=> 'Option',
#	'common_input_upload'	=> 'Upload',
#	'common_input_standard'	=> 'Standard',
#	'common_default'		=> 'Standarteinstellungen',
#	'common_move'			=> 'Verschieben',
#	'common_delete_all'		=> 'Alles löschen',
#	'common_login'			=> 'Login',
#	'common_desc'			=> 'Beschreibung',
#	'common_details'		=> 'Details',
#	'common_image'			=> 'Bild',
#	'common_member'			=> 'Mitglied',
#	'common_members'		=> 'Mitglieder',
#	'common_message'		=> 'Nachricht',
#	'common_moderator'		=> 'Moderator',		
#	'COMMON_MODERATORS'		=> 'Moderatoren',		
#	'common_member_empty'	=> 'Keine Mitglieder eingetragen/vorhanden.',
#	'common_moderator_empty'=> 'Keine Moderator eingetragen/vorhanden.',
#	'common_reset'			=> 'Zurücksetzen',
#	'common_setting'		=> 'Einstellung',
#	'common_settings'		=> 'Einstellungen',		
#	'common_submit'			=> 'Absenden',
#	'common_upload'			=> 'Upload',
#	'common_order'			=> 'Ordnen',
#	'common_sort'			=> 'Sortieren',
#	'common_public'			=> '�ffentlich',
#	'common_page_of'		=> 'Seite <b>%d</b> von <b>%d</b>',
#	'common_on'				=> 'Aktiv',
#	'common_off'			=> 'Inaktiv',
#	'common_active'			=> 'Aktiviert',
#	'common_deactive'		=> 'Deaktiviert',
	
	/* allgemein: Standard */
	'MSG_SELECT_MUST'		=> 'Es muss ein %s ausgewählt werden!',
	
#	
#	'MSG_SELECT_DATE'		=> 'Bitte ein Gültiges <b>Datum</b> auswählen!',
	
#	'navi_navigation'	=> 'Navigation',
#	'return'			=> '<br /><br /><strong><a href="%s">&laquo;&nbsp;%s</a></strong>',
#	'msg_select_module'		=> 'Es wurde keine G�ltige Funktion ausgewählt!',
#	
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
#	'msg_empty_pass_confirm'	=> 'Bitte ein Passwort Best�tigen!',
#	'msg_select_pass'			=> 'Bitte ein Passworttype auswählen!',
#	'msg_empty_email'			=> 'Bitte ein eMail eintragen!',
#	'msg_empty_email_confirm'	=> 'Die eMails stimmen nicht �berein Best�tigen!',
#	'msg_empty_email_mismatch'	=> 'Die eMails stimmen nicht �berein!',
#	'msg_empty_pass_mismatch'	=> 'Die Passw�rter stimmen nicht �berein!',
#	
#	'msg_empty_map'				=> 'Bitte eine Map eintragen!',
#	'msg_must_select_authlist'	=> 'Es muss ein W�hle ein Authfeld aus',		
#	'msg_must_select_game'		=> 'W�hle ein Spiel aus',		
#	'msg_select_desc'			=> 'Bitte eine Beschreibung eintragen',	
#	'msg_select_dir'			=> 'Bitte ein Verzeichnis auswählen!',	
#	'msg_select_duration'		=> 'Bitte eine Zeitdauer auswählen',		
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
#	'msg_select_sort_team'		=> 'Team f�rs sortieren w�hlen!',		
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
#	'create_map_cat'		=> 'Kartenkategorie hinzugefügt',
#	'update_forum'			=> 'Forumdaten erfolgreich geändert',
#	'update_newsletter'		=> 'eMailadresse erfolgreich geändert',
#	'update_teamspeak'		=> 'Teamspeakdaten erfolgreich geändert',
#	'update_user'			=> 'Benutzerdaten erfolgreich geändert',
#	'create_map'			=> 'Karte hinzugefügt',
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

	/* Upload */
	'IMAGE_FILESIZE'	=> 'Die Dateigröße muss kleiner als %d KB sein.',
	
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
#	
#	'common_auth_public'	=> '�ffentlich',
#	'common_auth_register'	=> 'Registriert',
#	'common_auth_trial'		=> 'Trial',
#	'common_auth_member'	=> 'Mitglieder',
#	'common_auth_mod'		=> 'Moderatoren',
#	'common_auth_admin'		=> 'Administrator',
	
#	'update'	=> 'Eintrag erfolgreich geändert.',
#	'delete'	=> 'Der Eintrag wurde gelöscht!',
#	'confirm'	=> 'das dieser Eintrag:',
#	'msg_input_size'		=> 'Bitte eine <b>Maximale Gr��e</b> eingeben!',/* download */
#	'msg_select_userlevel'	=> 'Bitte ein Benutzerlevel auswählen!',
#	'msg_empty_ranks'			=> 'Keine Ränge vorhanden/eingetragen.',
#	'select_rank'	=> 'Rang setzen &raquo;',
#	'rights_groups'	=> 'Gruppenrechte geben/nehmen',
#	'rights_teams'	=> 'Teamrechte geben/nehmen',
	/* error msg */
#	'msg_select_date'		=> 'Bitte ein G�ltiges <b>Datum</b> auswählen!',				# match


	/*
	 *	error msg, acp_build, build_request()
	 */
	'SQL_DUPLICATE'		=> '%s: "%s" schon vorhanden!',

	'A_SHOW'	=> 'Administratoren-Berechtigung Anzeigen',
	'F_SHOW'	=> 'Forum-Berechtigung Anzeigen',
	'M_SHOW'	=> 'Moderatoren-Berechtigung Anzeigen',
	'U_SHOW'	=> 'Benutzer-Berechtigung Anzeigen',
	'D_SHOW'	=> 'Download-Berechtigung Anzeigen',
	'G_SHOW'	=> 'Galerie-Berechtigung Anzeigen',
	
	'A_RIGHT'	=> 'Administratoren-Berechtigungen',
	'F_RIGHT'	=> 'Forum-Berechtigungen',
	'M_RIGHT'	=> 'Moderatoren-Berechtigungen',
	'U_RIGHT'	=> 'Benutzer-Berechtigungen',
	'D_RIGHT'	=> 'Download-Berechtigungen',
	'G_RIGHT'	=> 'Galerie-Berechtigungen',
	
	'A_PERMISSION'	=> 'Administratoren Berechtigung',
	'F_PERMISSION'	=> 'Forum Berechtigung',
	'M_PERMISSION'	=> 'Moderatoren Berechtigung',
	'U_PERMISSION'	=> 'Benutzer Berechtigung',
	'D_PERMISSION'	=> 'Download Berechtigung',
	'G_PERMISSION'	=> 'Galerie Berechtigung',
	
	'A_CASH'			=> 'Kann Clankasse verwalten.',
	'A_CASH_CREATE'		=> 'Kann Kassenbeiträge verwalten.',
	'A_CASH_BANK'		=> 'Kann Clanbank verwalten.',
	'A_CASH_DELETE'		=> 'Kann Clankasse Einträge löschen.',
	'A_CASH_TYPE'		=> 'Kann Teams verwalten',
	'A_FIGHTUS'			=> 'Kann Fight Us Formulare verwalten.',
	'A_JOINUS'			=> 'Kann Join Us Formulare verwalten.',
	'A_MATCH'			=> 'Kann Begegnungen verwalten.',
	'A_MATCH_CREATE'	=> 'Kann Begegnungen erstellen.',
	'A_MATCH_DELETE'	=> 'Kann Begegnungen löschen.',
	'A_MATCH_MANAGE'	=> 'Kann Begegnungendetails verwalten.',
	'A_MATCH_UPLOAD'	=> 'Kann Begegnungensbilder verwalten.',
	'A_SERVER'			=> 'Kann Server verwalten.',
	'A_SERVER_CREATE'	=> 'Kann Server hinzufügen.',
	'A_SERVER_ASSORT'	=> 'Kann Server sortieren.',
	'A_SERVER_DELETE'	=> 'Kann Server löschen.',
	'A_SERVER_TYPE'		=> 'Kann Servertypen verwalten.',
	'A_TEAM'			=> 'Kann Teams verwalten.',
	'A_TEAM_CREATE'		=> 'Kann Teams hinzufügen.',
	'A_TEAM_ASSORT'		=> 'Kann Teams sortieren.',
	'A_TEAM_DELETE'		=> 'Kann Teams löschen.',
	'A_TEAM_MANAGE'		=> 'Kann Teamsmitglieder verwalten.',
	'A_TEAM_RANKS'		=> 'Kann Teamsränge verwalten.',
	'A_TRAINING'		=> 'Kann Trainings verwalten.',
	'A_TRAINING_CREATE'	=> 'Kann Trianings hinzufügen.',
	'A_TRAINING_DELETE'	=> 'Kann Trainings löschen.',		
	'A_TRAINING_MANAGE'	=> 'Kann Trainingsspieler verwalten',
	
	'A_SETTINGS'			=> 'Einstellungen',
	'A_SETTINGS_CALENDAR'	=> 'Einstellung Kalender',
	'A_SETTINGS_MODULE'		=> 'Einstellung Module',
	'A_SETTINGS_NAVIGATION'	=> 'Einstellung Navigation',
	'A_SETTINGS_RATING'		=> 'Einstellung Bewertung',
	'A_SETTINGS_SMAIN'		=> 'Einstellung Kategorie',
	'A_SETTINGS_UPLOAD'		=> 'Einstellung Upload',
#	'A_SETTINGS_DOWNLOAD'	=> 'Einstellung Download',
	'A_SETTINGS_DOWNLOAD'	=> 'Kann Downloadeinstellungen verwalten',
	'A_SETTINGS_FTP'		=> 'Einstellung FTP',
	'A_SETTINGS_GALLERY'	=> 'Einstellung Galerie',
	'A_SETTINGS_MATCH'		=> 'Einstellung Begegnungen',
	'A_SETTINGS_SUPPORT'	=> 'Support',
	'A_SETTINGS_USER'		=> 'Einstellung Benutzer',
	
	'PERMISSION_FOUNDER'	=> 'Gründerrechte',
	'PERMISSION_USER'		=> 'Benutzerrechte',
	'PERMISSION_GROUP'		=> 'Gruppe',
	
#	'application/postscript' => 'ai, eps, ps',
#	'application/vnd.ms-cab-compressed' => 'cab',
#	'application/msword' => 'doc',
#	'application/x-msdownload' => 'exe, msi',
#	'application/javascript' => 'js',
#	'application/pdf' => 'pdf',
#	'application/vnd.ms-powerpoint' => 'ppt',
#	'application/x-rar-compressed' => 'rar',
#	'application/rtf' => 'rtf',
#	'application/x-shockwave-flash' => 'swf',
#	'application/vnd.ms-excel' => 'xls',
#	'application/xml' => 'xml',
#	'application/zip' => 'zip',
#	'application/x-bittorrent' => 'torrent',
	
#	'image/bmp' => 'bmp',
#	'image/gif' => 'gif',
#	'image/png' => 'png',
#	'image/vnd.adobe.photoshop' => 'psd',
#	'image/jpeg' => 'jpeg, jpg',
#	'image/tiff' => 'tiff, tif',
	
#	'text/css' => 'css',
#	'text/html' => 'htm, html, php',
#	'text/plain' => 'txt',
	
#	'audio/mpeg' => 'mp3',
#	'video/x-flv' => 'flv',
#	'video/quicktime' => 'qt, mov',
	
#	'a:1:{i:0;s:0:"";}' => 'Alle Daten',
	
	/*
	 *	Berechtigung
	 */
	'TABS'	=> array(
		'A_' => array(
			0 => 'Clan',
			1 => 'Benutzer & Gruppen',
			2 => 'Download & Galerie',
			3 => 'Forum & News',
			4 => 'Rechte',
			5 => 'Seite',
			6 => 'System'
		),
		'F_' => array(
			0 => 'Forum',
			1 => 'Beitrag',
			2 => 'Umfrage',
		),
		'U_' => array(
			0 => 'Profile',
			1 => 'Diverses',
			2 => 'Private Nachrichten',
		),
		'M_' => array(
			0 => 'Beiträge',
			1 => 'Themen',
		),
		'G_' => array(
			0 => 'Galerie',
			1 => 'Bild',
		),
		'D_' => array(
			0 => 'Ordner',
			1 => 'Datei',
		),
	),
	
	'A_AUTH_ADMIN'		=> 'Kann Administrator-Berechtigungen verwalten',
	'A_AUTH_DOWNLOAD'	=> 'Kann Download-Berechtigungen verwalten',
	'A_AUTH_FORMS'		=> 'Kann Foren-Berechtigungen verwalten',
	'A_AUTH_GALLERY'	=> 'Kann Galerie-Berechtigungen verwalten',
	'A_AUTH_MODERATOR'	=> 'Kann Moderatoren-Berechtigungen verwalten',
	'A_AUTH_USER'		=> 'Kann Benutzer-Berechtigungen verwalten',
	
	'EMPTY_PAGE'		=> "<html><head><title></title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"></head><body bgcolor=\"#FFFFFF\" text=\"#000000\"></body></html>",
));

$lang = array_merge($lang, array(
	'drop_size'	=> array(
		0 => $lang['SIZE_BY'],
		1 => $lang['SIZE_KB'],
		2 => $lang['SIZE_MB'],
		3 => $lang['SIZE_GB']
	),
	
	'radio:yesno'	=> array(1 => $lang['COMMON_YES'], 0 => $lang['COMMON_NO']),
	'radio:match'	=> array(0 => 'als Auswählpunkte', 1 => 'als Dropdownmenü'),
	'radio:calview'	=> array(0 => 'als Listenansicht', 1 => 'als Blockansicht'),
	'radio:news'	=> array(0 => 'als Listenansicht', 1 => 'als Blockansicht'),
	'radio:acpview'	=> array(1 => 'als Listenansicht', 0 => 'nach Vorgabe der Einstellungen'),
	'radio:caldays'	=> array(0 => 'Sonntag', 1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag', 5 => 'Freitag', 6 => 'Samstag'),
));

?>