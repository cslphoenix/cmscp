<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'TITLE'		=> 'Berechtigungen',
	'EXPLAIN'	=> 'Verwalten der verschiedenen Berechtigungen für das Forum, Downloads, Galerien, Gruppen und Benutzern.',

#	'auth_simple'	=> 'Normal',
#	'auth_extended'	=> 'Individuell',
	
	'LABEL' => 'Label',
	
	'EXTENDED_PERMISSION'		=> 'Erweitere Berechtigung',
	'EXTENDED_PERMISSION_ALL'	=> 'Erweitere Berechtigung alle',
	
	'F_READ'			=> 'Kann das Forum lesen',
	'F_NOTICE'			=> 'Kann Ankündigungen schreiben',
	'F_STICKY'			=> 'Kann Wichtige Themen schreiben',
	'F_ICONS'			=> 'Kann Themenicons verwenden',
	'F_REPLY'			=> 'Kann antworten auf Themen',
	'F_POST'			=> 'Kann Themen eröffnen',
	'F_OWNEDIT'			=> 'Kann eigene Beiträge bearbeiten',
	'F_OWNDELETE'		=> 'Kann eigene Beiträge löschen',
	'F_REPORT'			=> 'Kann Thema melden',
	'F_WATCH'			=> 'Kann Thema beobachten',
	'F_SIGNATUR_ALLOW'	=> 'Kann Signatur verwenden',
	'F_SMILIES_ALLOW'	=> 'Kann Smilies verwenden',
	'F_LOCK'			=> 'Kann Thema sperren/schließen',
	'F_CREATE'			=> 'Kann Umfrage erstellen',
	'F_JOIN'			=> 'Kann Umfrage teilnehmen',
	'F_CHANGE'			=> 'Kann seine Auswahl ändern',
	'F_CLOSE'			=> 'Kann Umfrage schließen',
	
	'D_VIEW'			=> 'Kann Downloads sehen.',
	'D_MANAGE'			=> 'Kann Downloads verwalten.',
	'D_DELETE'			=> 'Kann Downloads löschen.',
	'D_APPROVE'			=> 'Kann Downloads genehmigen.',
	'D_COMMENT'			=> 'Kann Kommentare verfassen.',
	'D_RATE'			=> 'Kann Downloads bewerten.',
	'D_REPORT'			=> 'Kann Downloads melden.',
	'D_UPLOAD'			=> 'Kann Downloads hochladen.',
	'D_OWNUPDATE'		=> 'Kann eigene Downloads bearbeiten.',
	'D_OWNDELETE'		=> 'Kann eigene Downloads löschen.',
	
	'G_VIEW'			=> 'Kann Galerie sehen.',
	'G_MANAGE'			=> 'Kann Galerie verwalten.',		
	'G_DELETE'			=> 'Kann Galerie löschen.',
	'G_APPROVE'			=> 'Kann Galerie genehmigen.',
	'G_WATERMARK'		=> 'Kann Galerie Wasserzeichen ändern.',
	'G_COMMENT'			=> 'Kann Kommentare verfassen.',
	'G_RATE'			=> 'Kann Galerie bewerten.',
	'G_REPORT'			=> 'Kann Galerien melden.',
	'G_UPLOAD'			=> 'Kann Bilder hochladen.',
	'G_OWNUPDATE'		=> 'Kann eigene Bilder bearbeiten.',
	'G_OWNDELETE'		=> 'Kann eigene Bilder löschen.',
	
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

	
#	'forms_noneset' => 'keine Standartwerte zugewiesen',
	
#	'forum_access_no'				=> 'Kein Zugang',
#	'forum_access_only_read'		=> 'Nur lesender Zugriff',
#	'forum_access_default'			=> 'Standard-Zugang',
#	'forum_access_default_polls'	=> 'Standard-Zugang + Umfragen',
#	'forum_access_full'				=> 'Voller Zugang',
	
#	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Forum', 2 => 'Subforum'),
	
#	'group'			=> 'Gruppe',

	'FORUMS_ALL'	=> 'Alle Foren auswählen',
	
	'GROUPS'		=> 'Gruppen',
	'GROUPS_ALL'	=> 'Alle Gruppen auswählen',
	'GROUPS_MANAGE'	=> 'Gruppen bearbeiten',
	'GROUPS_ADDED'	=> 'Gruppen hinzufügen',
	
	'USERS'			=> 'Benutzer',
	'USERS_ALL'		=> 'Alle Benutzer auswählen',
	'USERS_MANAGE'	=> 'Benutzer bearbeiten',
	'USERS_ADDED'	=> 'Benutzer hinzufügen',
	
	'AUTH_CREATE'	=> 'Berechtigung hinzufügen',
	'AUTH_UPDATE'	=> 'Berechtigung ändern',
	'AUTH_SHOW'		=> 'Berechtigung anzeigen',
	'AUTH_DELETE'	=> 'Berechtigung löschen',
	
	'LEGEND_NONE_USER'	=> 'Keine Gruppen vorhanden.',
	'LEGEND_NONE_GROUP'	=> 'Keine Benutzer vorhanden.',
	
	'SELECT_COPY'	=> 'Kopieren von ...',
	
));

$lang = array_merge($lang, array(
#	'radio:status'	=> array(0 => $lang['opened'], 1 => $lang['closed']),
	'radio:legend'	=> array(1 => $lang['COMMON_VIEW'], 0 => $lang['COMMON_NONE_VIEW']),
	
));
/*
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
*/
?>