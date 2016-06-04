<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'title'		=> 'Berechtigungen',
	'explain'	=> 'Verwalten der verschiedenen Berechtigungen für das Forum, Downloads, Galerien, Gruppen und Benutzern.',

	'create'	=> 'Neues Forum hinzugefügt.',
	'update'	=> 'Berechtigungen wurden erfolgreich geändert.',
	'delete'	=> 'Das Forum wurde gelöscht!',
	'confirm'	=> 'dass dieses Forum:',

	'create_c'	=> 'Neue Kategorie hinzugefügt.',
	'update_c'	=> 'Kategoriedaten erfolgreich geändert.',
	'delete_c'	=> 'Die Kategorie wurde gelöscht!',
	'confirm_c'	=> 'dass diese Kategorie:',
	
	'icon'		=> 'Forumicon',
	'sub'		=> 'Subforum',
	'main'		=> 'Hauptforum',
	'copy'		=> 'Rechtekopieren',
	
	'locked'	=> 'Gesperrt',
	'unlocked'	=> 'Offen',
	
	'legend'	=> 'Auflisten',
	'legend_ex'	=> 'Wurde \'Ja\' aktiviert, werden die Unterforen in der Legende angezeigt.',
	
	'auth_all'			=> 'Öffentlich',
	'auth_register'		=> 'Registriert',
	'auth_trial'		=> 'Trial',
	'auth_member'		=> 'Mitglieder',
	'auth_moderator'	=> 'Moderatoren',
	'auth_private'		=> 'Privat',
	'auth_admin'		=> 'Administrator',
	
	'forms_public'		=> 'Öffentlich',
	'forms_register'	=> 'Registriert',
	'forms_trial'		=> 'Trial',
	'forms_member'		=> 'Mitglieder',
	'forms_moderator'	=> 'Moderatoren',
	'forms_privat'		=> 'Privat',
	'forms_admin'		=> 'Administrator',
	'forms_special'		=> 'Individuell',
	'forms_hidden'		=> '%s <span style="font-size:9px">[ Versteckt ]</span>',
	
	'forms_view'			=> 'Ansicht',
	'forms_read'			=> 'Lesen',
	'forms_post'			=> 'Posten',
	'forms_reply'			=> 'Antworten',
	'forms_edit'			=> 'Editieren',
	'forms_delete'			=> 'Löschen',
	'forms_sticky'			=> 'Wichtig',
	'forms_announce'		=> 'Ankündigung',
	'forms_globalannounce'	=> 'Globaleankündigung',
	'forms_poll'			=> 'Umfrage',
	'forms_pollcreate'		=> 'Umfrage erstellen',
	
	'auth_forum' => array(
		'auth_view'				=> 'Ansicht',
		'auth_read'				=> 'Lesen',
		'auth_post'				=> 'Posten',
		'auth_reply'			=> 'Antworten',
		'auth_edit'				=> 'Editieren',
		'auth_delete'			=> 'Löschen',
		'auth_sticky'			=> 'Wichtig',
		'auth_announce'			=> 'Ankündigung',
		'auth_globalannounce'	=> 'Globaleankündigung',
		'auth_poll'				=> 'Umfrage',
		'auth_pollcreate'		=> 'Umfrage erstellen',
	),
	
#	'tabs'	=> array(0 => 'Forum', 1 => 'Beitrag', 2 => 'Umfrage'),
	
	'tabs'	=> array(
		'a_' => array(
			0 => 'Clan',
			1 => 'Benutzer & Gruppen',
			2 => 'Download & Galerie',
			3 => 'Forum & News',
			4 => 'Rechte',
			5 => 'Seite',
			6 => 'System'
		),
		'f_' => array(
			0 => 'Forum',
			1 => 'Beitrag',
			2 => 'Umfrage',
		),
		'u_' => array(
			0 => 'Profile',
			1 => 'Diverses',
			2 => 'Private Nachrichten',
		),
		'm_' => array(
			0 => 'Beiträge',
			1 => 'Themen',
		),
		'g_' => array(
			0 => 'Galerie',
			1 => 'Bild',
		),
		'd_' => array(
			0 => 'Ordner',
			1 => 'Datei',
		),
	),
	
	'a_show'	=> 'Administrator-Berechtigungen',
	'f_show'	=> 'Forum-Berechtigungen',
	'm_show'	=> 'Moderatoren-Berechtigungen',
	'u_show'	=> 'Benutzer-Berechtigungen',
	'd_show'	=> 'Download-Berechtigungen',
	'g_show'	=> 'Galerie-Berechtigungen',
	
	'a_right'	=> 'Administrator-Berechtigungen',
	'f_right'	=> 'Forum-Berechtigungen',
	'm_right'	=> 'Moderatoren-Berechtigungen',
	'u_right'	=> 'Benutzer-Berechtigungen',
	'd_right'	=> 'Download-Berechtigungen',
	'g_right'	=> 'Galerie-Berechtigungen',
	
	'auth_simple'	=> 'Normal',
	'auth_extended'	=> 'Individuell',
	
#	$lang['Forum_ALL'] = 'Alle';
#	$lang['Forum_REG'] = 'Reg';
#	$lang['Forum_PRIVATE'] = 'Privat';
#	$lang['Forum_MOD'] = 'Mods';
#	$lang['Forum_ADMIN'] = 'Admin';

	'closed' => 'Gesperrt',
	'opened' => 'Geöffnet',
	
	'label'		=> 'Label',
	
	'a_permission'			=> 'Administrator-Berechtigung',
	'd_permission'			=> 'Download-Berechtigung',
	'g_permission'			=> 'Galerie-Berechtigung',
	
	'extended_permission'	=> 'erweitere Berechtigung',
	'extended_permission_all'	=> 'erweitere Berechtigung alle',
	
	'label_gallery_full'	=> 'Volle Galerie-Rechte',
	'label_gallery_none'	=> 'Keine Galerie-Rechte',
	
	/* added 19.07 */
	/*	
	'f_view'	=> 'Kann das Forum sehen',
	'f_read'	=> 'Kann das Forum lesen',
	'f_notice'	=> 'Kann Ankündigungen schreiben',
	'f_sticky'	=> 'Kann Wichtige Themen schreiben',
	'f_icons'	=> 'Kann Themenicons verwenden',
	'f_reply'	=> 'Kann antworten auf Themen',
	'f_post'	=> 'Kann Themen eröffnen',
	'm_ownedit'	=> 'Kann eigene Beiträge bearbeiten',
	'm_owndelete'	=> 'Kann eigene Beiträge löschen',
	'm_ownclose'	=> 'Kann eigene Themen schließen',
	'm_report'	=> 'Kann Thema melden',
	'p_view'	=> 'Kann Umfrage sehen',
	'p_create'	=> 'Kann Umfrage erstellen',
	'p_vote'	=> 'Kann an Umfrage teilnehmen',
	'p_change'	=> 'Kann seine Auswahl ändern',
	'p_close'	=> 'Kann Umfrage schließen',
	*/
	
	/* hinzugefügt 02.11.13 */
#	'f_view'			=> 'Kann das Forum sehen',
#	'f_read'			=> 'Kann das Forum lesen',
#	'f_notice'			=> 'Kann Ankündigungen schreiben',
#	'f_sticky'			=> 'Kann Wichtige Themen schreiben',
#	'f_icons'			=> 'Kann Themenicons verwenden',
#	'f_reply'			=> 'Kann antworten auf Themen',
#	'f_post'			=> 'Kann Themen eröffnen',
#	'f_ownedit'			=> 'Kann eigene Beiträge bearbeiten',
#	'f_owndelete'		=> 'Kann eigene Beiträge löschen',
#	'f_report'			=> 'Kann Thema melden',
#	'f_watch'			=> 'Kann Thema beobachten',
#	'f_signatur_allow'	=> 'Kann Signatur verwenden',
#	'f_smilies_allow'	=> 'Kann Smilies verwenden',
#	'f_lock'			=> 'Kann Thema sperren/schließen',
#	'f_create'			=> 'Kann Umfrage erstellen',
#	'f_join'			=> 'Kann Umfrage teilnehmen',
#	'f_change'			=> 'Kann seine Auswahl ändern',
#	'f_close'			=> 'Kann Umfrage schließen',
	
	/* CLAN */
#	'a_cashuser'		=> 'Kann Clankassen verwalten',
#	'a_cashuser_create'	=> 'Kann Benutzer in der Clankasse hinzufügen',
#	'a_cashuser_delete'	=> 'Kann Benutzer in der Clankasse löschen',
#	'a_bankdata'		=> 'Kann Bankdaten hinzufügen',
#	'a_bankdata_delete'	=> 'Kann Bankdaten löschen',
#	'a_fightus'			=> 'Kann FightUs Formulare verwalten',
#	'a_joinus'			=> 'Kann JoinUs Formulare verwalten',
#	'a_match'			=> 'Kann Begegungen verwalten',
#	'a_match_create'	=> 'Kann Begegungen hinzufügen',
#	'a_match_delete'	=> 'Kann Begegungen löschen',
#	'a_match_manage'	=> 'Kann Begegungendetails verwalten',
#	'a_server'			=> 'Kann Server verwalten',
#	'a_server_create'	=> 'Kann Server hinzufügen',
#	'a_server_delete'	=> 'Kann Server löschen',
#	'a_settings_match'	=> 'Kann Begegnungseinstellungen ändern',
#	'a_team'			=> 'Kann Teams verwalten',
#	'a_team_create'		=> 'Kann Team hinzufügen',
#	'a_team_delete'		=> 'Kann Team löschen',
#	'a_team_manage'		=> 'Kann Teammitglieder verwalten',
#	'a_team_ranks'		=> 'Kann Team-Ränge verwalten',
#	'a_training'		=> 'Kann Trainings verwalten',
#	'a_training_create'	=> 'Kann Trainings hinzufügen',
#	'a_training_delete'	=> 'Kann Trainings löschen',
#	'a_training_manage'	=> 'Kann Trainingdetails schließen',
	
	/* BENUTZER & GRUPPEN */
#	'a_user'			=> 'Kann Benutzer verwalten',
#	'a_user_create'		=> 'Kann Benutzer hinzufügen',
#	'a_user_delete'		=> 'Kann Benutzer löschen',
#	'a_user_bans'		=> 'Kann Benutzer sperren',
#	'a_user_disallow'	=> 'Kann Benutzer ablehnen',
#	'a_user_fields'		=> 'Kann Benutzerfelder verwalten',
#	'a_user_ranks'		=> 'Kann Benutzerränge verwalten',
#	'a_user_settings'	=> 'Kann Benutzereinstellungen verwalten',
#	'a_group'			=> 'Kann Gruppen verwalten',
#	'a_group_create'	=> 'Kann Gruppen hinzufügen',
#	'a_group_delete'	=> 'Kann Gruppen löschen',
#	'a_group_manage'	=> 'Kann Gruppenmitglieder verwalten',

	/* DOWNLOAD & GALLERY */
#	'a_download'			=> 'Kann Download verwalten',
#	'a_download_create'		=> 'Kann Download hinzufügen',
#	'a_download_delete'		=> 'Kann Download löschen',
#	'a_download_icons'		=> 'Kann Downloadicons verwalten',
#	'a_settings_download'	=> 'Kann Downloadeinstellungen verwalten',
#	'a_gallery'				=> 'Kann Galerie verwalten',
#	'a_gallery_create'		=> 'Kann Galerie hinzufügen',
#	'a_gallery_delete'		=> 'Kann Galerie löschen',
#	'a_settings_gallery'	=> 'Kann Galerieeinstellungen verwalten',
	
	/* FORUM & NEWS */
#	'a_forum'			=> 'Kann Foren verwalten',
#	'a_forum_create'	=> 'Kann Foren hinzufügen',
#	'a_forum_delete'	=> 'Kann Foren löschen',
#	'a_forum_censors'	=> 'Kann Forenzensuren verwalten',
#	'a_forum_icons'		=> 'Kann Forenicons verwalten',
#	'a_forum_ranks'		=> 'Kann Forenränge verwalten',
#	'a_news'			=> 'Kann News verwalten',
#	'a_news_create'		=> 'Kann News hinzufügen',
#	'a_news_delete'		=> 'Kann News löschen',
#	'a_news_public'		=> 'Kann News-Veröffentlichen verwalten',
#	'a_news_submission'	=> 'Kann News-Einreichung verwalten',
#	'a_newscat'			=> 'Kann Newskategorie verwalten',
#	'a_newscat_create'	=> 'Kann Newskategorie hinzufügen',
#	'a_newscat_delete'	=> 'Kann Newskategorie löschen',
#	'a_newscat_upload'	=> 'Kann Newskategorie Bilder hochladen',
#	'a_newsletter'		=> 'Kann Newsletter verwalten',
	
	/* RECHTE */

#	'a_aauth'			=> 'Kann Administrator-Berechtigungen verwalten',
#	'a_dauth'			=> 'Kann Download-Berechtigungen verwalten',
#	'a_fauth'			=> 'Kann Foren-Berechtigungen verwalten',
#	'a_gauth'			=> 'Kann Galerie-Berechtigungen verwalten',
#	'a_mauth'			=> 'Kann Moderatoren-Berechtigungen verwalten',
#	'a_uauth'			=> 'Kann Benutzer-Berechtigungen verwalten',

#	'a_auth_users'			=> 'Kann Umfrage schließen',
#	'a_auth_groups'			=> 'Kann Umfrage schließen',
#	'a_label'			=> 'Kann Umfrage schließen',
#	'a_label_create'			=> 'Kann Umfrage schließen',
#	'a_label_delete'			=> 'Kann Umfrage schließen',
#	'a_settings'			=> 'Kann Umfrage schließen',
#	'a_settings_calendar'			=> 'Kann Umfrage schließen',
#	'a_settings_module'			=> 'Kann Umfrage schließen',
#	'a_settings_navigation'			=> 'Kann Umfrage schließen',
#	'a_settings_rating'			=> 'Kann Umfrage schließen',
#	'a_settings_smain'			=> 'Kann Umfrage schließen',
#	'a_settings_upload'			=> 'Kann Umfrage schließen',
#	'a_cashtype'			=> 'Kann Umfrage schließen',
#	'a_contact'				=> 'Kann Umfrage schließen',
#	'a_event'				=> 'Kann Ereignisse verwalten',
#	'a_game'				=> 'Kann Clanspiele verwalten',
#	'a_game_create'			=> 'Kann Clanspiele hinzufügen',
#	'a_game_delete'			=> 'Kann Clanspiele löschen',
#	'a_map'					=> 'Kann Karten verwalten',
#	'a_map_create'			=> 'Kann Karte hinzufügen',
#	'a_map_delete'			=> 'Kann Karte löschen',
#	'a_navigation'			=> 'Kann Navigation verwalten',
#	'a_network'				=> 'Kann Umfrage schließen',
#	'a_network_upload'		=> 'Kann Umfrage schließen',
#	'a_rating'				=> 'Kann Umfrage schließen',
#	'a_servertype'			=> 'Kann Umfrage schließen',
#	'a_smileys'				=> 'Kann Umfrage schließen',
#	'a_vote'				=> 'Kann Umfrage schließen',
#	'a_vote_create'			=> 'Kann Umfrage schließen',
#	'a_vote_delete'			=> 'Kann Umfrage schließen',
	
#	'a_database_backup'		=> 'Kann Datenbank-Backups erstellen',
#	'a_database_optimize'	=> 'Kann Datenbank-Optimieren',
#	'a_database_restore'	=> 'Kann Datenbank Backups wiederherstellen',
#	'a_language'			=> 'Kann Sprachen verwalten',
#	'a_log'					=> 'Kann Logeinträge sehen',
#	'a_log_delete'			=> 'Kann Logeinträge löschen',
#	'a_menu'				=> 'Kann Menü verwalten',
#	'a_module'				=> 'Kann Module verwalten',
#	'a_phpinfo'				=> 'Kann PHP-Informationen sehen',
#	'a_style'				=> 'Kann Templates verwalten',
#	'a_style_create'		=> 'Kann Templates hinzufügen',
#	'a_style_delete'		=> 'Kann Templates löschen',
#	'a_style_upload'		=> 'Kann Templates hochladen',

	
	'forms_noneset' => 'keine Standartwerte zugewiesen',
	
	'forum_access_no'				=> 'Kein Zugang',
	'forum_access_only_read'		=> 'Nur lesender Zugriff',
	'forum_access_default'			=> 'Standard-Zugang',
	'forum_access_default_polls'	=> 'Standard-Zugang + Umfragen',
	'forum_access_full'				=> 'Voller Zugang',
	
	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Forum', 2 => 'Subforum'),
	
	'group'			=> 'Gruppe',
	'groups'		=> 'Gruppen',
	'groups_manage'	=> 'Gruppen bearbeiten',
	'groups_added'	=> 'Gruppen hinzufügen',
	
	'users'			=> 'Benutzer',
	'users_manage'	=> 'Benutzer bearbeiten',
	'users_added'	=> 'Benutzer hinzufügen',
	
	'auth_create'	=> 'Berechtigung hinzufügen',
	'auth_update'	=> 'Berechtigung ändern',
	'auth_show'		=> 'Berechtigung anzeigen',
	'auth_delete'	=> 'Berechtigung löschen',
	
	'users_all'		=> 'Alle Benutzer auswählen',
	'groups_all'	=> 'Alle Gruppen auswählen',
	
	'no_legend_user'	=> 'Keine Gruppen vorhanden.',
	'no_legend_group'	=> 'Keine Benutzer vorhanden.',
	
));

$lang = array_merge($lang, array(
	'radio:status'	=> array(0 => $lang['opened'], 1 => $lang['closed']),
	'radio:legend'	=> array(1 => $lang['com_view'], 0 => $lang['com_noview']),
	
));

$field_names = array(
	'auth_view'				=> $lang['forms_view'],
	'auth_read'				=> $lang['forms_read'],
	'auth_post'				=> $lang['forms_post'],
	'auth_reply'			=> $lang['forms_reply'],
	'auth_edit'				=> $lang['forms_edit'],
	'auth_delete'			=> $lang['forms_delete'],
	'auth_sticky'			=> $lang['forms_sticky'],
	'auth_announce'			=> $lang['forms_announce'],
	'auth_globalannounce'	=> $lang['forms_globalannounce'],
	'auth_poll'				=> $lang['forms_poll'],
	'auth_pollcreate'		=> $lang['forms_pollcreate'],
);

?>