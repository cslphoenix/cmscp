<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'title'		=> 'Berechtigungen',
	'explain'	=> 'Verwalten der verschiedenen Berechtigungen f�r das Forum, Downloads, Galerien, Gruppen und Benutzern.',

	'create'	=> 'Neues Forum hinzugef�gt.',
	'update'	=> 'Forumdaten erfolgreich ge�ndert.',
	'delete'	=> 'Das Forum wurde gel�scht!',
	'confirm'	=> 'dass dieses Forum:',

	'create_c'	=> 'Neue Kategorie hinzugef�gt.',
	'update_c'	=> 'Kategoriedaten erfolgreich ge�ndert.',
	'delete_c'	=> 'Die Kategorie wurde gel�scht!',
	'confirm_c'	=> 'dass diese Kategorie:',
	
	'icon'		=> 'Forumicon',
	'sub'		=> 'Subforum',
	'main'		=> 'Hauptforum',
	'copy'		=> 'Rechtekopieren',
	
	'locked'	=> 'Gesperrt',
	'unlocked'	=> 'Offen',
	
	'legend'	=> 'Auflisten',
	'legend_ex'	=> 'Wurde \'Ja\' aktiviert, werden die Unterforen in der Legende angezeigt.',
	
	'auth_all'			=> '�ffentlich',
	'auth_register'		=> 'Registriert',
	'auth_trial'		=> 'Trial',
	'auth_member'		=> 'Mitglieder',
	'auth_moderator'	=> 'Moderatoren',
	'auth_private'		=> 'Privat',
	'auth_admin'		=> 'Administrator',
	
	'forms_public'		=> '�ffentlich',
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
	'forms_delete'			=> 'L�schen',
	'forms_sticky'			=> 'Wichtig',
	'forms_announce'		=> 'Ank�ndigung',
	'forms_globalannounce'	=> 'Globaleank�ndigung',
	'forms_poll'			=> 'Umfrage',
	'forms_pollcreate'		=> 'Umfrage erstellen',
	
	'auth_forum' => array(
		'auth_view'				=> 'Ansicht',
		'auth_read'				=> 'Lesen',
		'auth_post'				=> 'Posten',
		'auth_reply'			=> 'Antworten',
		'auth_edit'				=> 'Editieren',
		'auth_delete'			=> 'L�schen',
		'auth_sticky'			=> 'Wichtig',
		'auth_announce'			=> 'Ank�ndigung',
		'auth_globalannounce'	=> 'Globaleank�ndigung',
		'auth_poll'				=> 'Umfrage',
		'auth_pollcreate'		=> 'Umfrage erstellen',
	),
	
	'tabs'	=> array(0 => 'Forum', 1 => 'Beitrag', 2 => 'Umfrage'),
	
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
			0 => 'Beitr�ge',
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
	'opened' => 'Ge�ffnet',
	
	'label'		=> 'Label',
	
	'a_permission'			=> 'Administrator-Berechtigung',
	'd_permission'			=> 'Download-Berechtigung',
	'g_permission'			=> 'Galerie-Berechtigung',
	
	'extended_permission'	=> 'erweitere Berechtigung',
	
	'label_gallery_full'	=> 'Volle Galerie-Rechte',
	'label_gallery_none'	=> 'Keine Galerie-Rechte',
	
	/* added 19.07 */
	/*	
	'f_view'	=> 'Kann das Forum sehen',
	'f_read'	=> 'Kann das Forum lesen',
	'f_notice'	=> 'Kann Ank�ndigungen schreiben',
	'f_sticky'	=> 'Kann Wichtige Themen schreiben',
	'f_icons'	=> 'Kann Themenicons verwenden',
	'f_reply'	=> 'Kann antworten auf Themen',
	'f_post'	=> 'Kann Themen er�ffnen',
	'm_ownedit'	=> 'Kann eigene Beitr�ge bearbeiten',
	'm_owndelete'	=> 'Kann eigene Beitr�ge l�schen',
	'm_ownclose'	=> 'Kann eigene Themen schlie�en',
	'm_report'	=> 'Kann Thema melden',
	'p_view'	=> 'Kann Umfrage sehen',
	'p_create'	=> 'Kann Umfrage erstellen',
	'p_vote'	=> 'Kann an Umfrage teilnehmen',
	'p_change'	=> 'Kann seine Auswahl �ndern',
	'p_close'	=> 'Kann Umfrage schlie�en',
	*/
	
	/* hinzugef�gt 02.11.13 */
	'f_view'			=> 'Kann das Forum sehen',
	'f_read'			=> 'Kann das Forum lesen',
	'f_notice'			=> 'Kann Ank�ndigungen schreiben',
	'f_sticky'			=> 'Kann Wichtige Themen schreiben',
	'f_icons'			=> 'Kann Themenicons verwenden',
	'f_reply'			=> 'Kann antworten auf Themen',
	'f_post'			=> 'Kann Themen er�ffnen',
	'f_ownedit'			=> 'Kann eigene Beitr�ge bearbeiten',
	'f_owndelete'		=> 'Kann eigene Beitr�ge l�schen',
	'f_report'			=> 'Kann Thema melden',
	'f_watch'			=> 'Kann Thema beobachten',
	'f_signatur_allow'	=> 'Kann Signatur verwenden',
	'f_smilies_allow'	=> 'Kann Smilies verwenden',
	'f_lock'			=> 'Kann Thema sperren/schlie�en',
	'f_create'			=> 'Kann Umfrage erstellen',
	'f_join'			=> 'Kann Umfrage teilnehmen',
	'f_change'			=> 'Kann seine Auswahl �ndern',
	'f_close'			=> 'Kann Umfrage schlie�en',
	
	/* hinzugef�gt 02.11.13 */
	'a_cashuser'			=> 'Kann Umfrage schlie�en',
	'a_cashuser_create'			=> 'Kann Umfrage schlie�en',
	'a_cashuser_delete'			=> 'Kann Umfrage schlie�en',
	'a_fightus'			=> 'Kann Umfrage schlie�en',
	'a_joinus'			=> 'Kann Umfrage schlie�en',
	'a_match'			=> 'Kann Umfrage schlie�en',
	'a_match_create'			=> 'Kann Umfrage schlie�en',
	'a_match_delete'			=> 'Kann Umfrage schlie�en',
	'a_match_manage'			=> 'Kann Umfrage schlie�en',
	'a_server'			=> 'Kann Server verwalten',
	'a_server_create'			=> 'Kann Server hinzuf�gen',
	'a_server_delete'			=> 'Kann Server l�schen',
	'a_settings_match'			=> 'Kann Begegnungseinstellungen �ndern',
	'a_team'			=> 'Kann Teams verwalten',
	'a_team_create'			=> 'Kann Team hinzuf�gen',
	'a_team_delete'			=> 'Kann Team l�schen',
	'a_team_manage'			=> 'Kann Teammitglieder verwalten',
	'a_team_ranks'			=> 'Kann Team R�nge einstellen',
	'a_training'			=> 'Kann Trainings verwalten',
	'a_training_create'			=> 'Kann Trainings schlie�en',
	'a_training_delete'			=> 'Kann Umfrage schlie�en',
	'a_training_manage'			=> 'Kann TrainingsUmfrage schlie�en',
	'a_user'			=> 'Kann Umfrage schlie�en',
	'a_user_create'			=> 'Kann Umfrage schlie�en',
	'a_user_delete'			=> 'Kann Umfrage schlie�en',
	'a_user_bans'			=> 'Kann Umfrage schlie�en',
	'a_user_disallow'			=> 'Kann Umfrage schlie�en',
	'a_user_fields'			=> 'Kann Umfrage schlie�en',
	'a_user_ranks'			=> 'Kann Umfrage schlie�en',
	'a_user_settings'			=> 'Kann Umfrage schlie�en',
	'a_group'			=> 'Kann Umfrage schlie�en',
	'a_group_create'			=> 'Kann Umfrage schlie�en',
	'a_group_delete'			=> 'Kann Umfrage schlie�en',
	'a_group_manage'			=> 'Kann Umfrage schlie�en',
	'a_download'			=> 'Kann Umfrage schlie�en',
	'a_download_create'			=> 'Kann Umfrage schlie�en',
	'a_download_delete'			=> 'Kann Umfrage schlie�en',
	'a_download_icons'			=> 'Kann Umfrage schlie�en',
	'a_settings_download'			=> 'Kann Umfrage schlie�en',
	'a_gallery'			=> 'Kann Umfrage schlie�en',
	'a_gallery_create'			=> 'Kann Umfrage schlie�en',
	'a_gallery_delete'			=> 'Kann Umfrage schlie�en',
	'a_settings_gallery'			=> 'Kann Umfrage schlie�en',
	'a_forum'			=> 'Kann Umfrage schlie�en',
	'a_forum_create'			=> 'Kann Umfrage schlie�en',
	'a_forum_delete'			=> 'Kann Umfrage schlie�en',
	'a_forum_censors'			=> 'Kann Umfrage schlie�en',
	'a_forum_icons'			=> 'Kann Umfrage schlie�en',
	'a_forum_ranks'			=> 'Kann Umfrage schlie�en',
	'a_news'			=> 'Kann Umfrage schlie�en',
	'a_news_create'			=> 'Kann Umfrage schlie�en',
	'a_news_delete'			=> 'Kann Umfrage schlie�en',
	'a_news_public'			=> 'Kann Umfrage schlie�en',
	'a_news_submission'			=> 'Kann Umfrage schlie�en',
	'a_newscat'			=> 'Kann Umfrage schlie�en',
	'a_newscat_create'			=> 'Kann Umfrage schlie�en',
	'a_newscat_delete'			=> 'Kann Umfrage schlie�en',
	'a_newscat_upload'			=> 'Kann Umfrage schlie�en',
	'a_newsletter'			=> 'Kann Umfrage schlie�en',
	'a_aauth'			=> 'Kann Umfrage schlie�en',
	'a_dauth'			=> 'Kann Umfrage schlie�en',
	'a_fauth'			=> 'Kann Umfrage schlie�en',
	'a_gauth'			=> 'Kann Umfrage schlie�en',
	'a_mauth'			=> 'Kann Umfrage schlie�en',
	'a_uauth'			=> 'Kann Umfrage schlie�en',
	'a_auth_users'			=> 'Kann Umfrage schlie�en',
	'a_auth_groups'			=> 'Kann Umfrage schlie�en',
	'a_label'			=> 'Kann Umfrage schlie�en',
	'a_label_create'			=> 'Kann Umfrage schlie�en',
	'a_label_delete'			=> 'Kann Umfrage schlie�en',
	'a_settings'			=> 'Kann Umfrage schlie�en',
	'a_settings_calendar'			=> 'Kann Umfrage schlie�en',
	'a_settings_module'			=> 'Kann Umfrage schlie�en',
	'a_settings_navigation'			=> 'Kann Umfrage schlie�en',
	'a_settings_rating'			=> 'Kann Umfrage schlie�en',
	'a_settings_smain'			=> 'Kann Umfrage schlie�en',
	'a_settings_upload'			=> 'Kann Umfrage schlie�en',
	'a_cashtype'			=> 'Kann Umfrage schlie�en',
	'a_contact'				=> 'Kann Umfrage schlie�en',
	'a_event'				=> 'Kann Ereignisse verwalten',
	'a_game'				=> 'Kann Clanspiele verwalten',
	'a_game_create'			=> 'Kann Clanspiele hinzuf�gen',
	'a_game_delete'			=> 'Kann Clanspiele l�schen',
	'a_map'					=> 'Kann Karten verwalten',
	'a_map_create'			=> 'Kann Karte hinzuf�gen',
	'a_map_delete'			=> 'Kann Karte l�schen',
	'a_navigation'			=> 'Kann Navigation verwalten',
	'a_network'				=> 'Kann Umfrage schlie�en',
	'a_network_upload'		=> 'Kann Umfrage schlie�en',
	'a_rating'				=> 'Kann Umfrage schlie�en',
	'a_servertype'			=> 'Kann Umfrage schlie�en',
	'a_smileys'				=> 'Kann Umfrage schlie�en',
	'a_vote'				=> 'Kann Umfrage schlie�en',
	'a_vote_create'			=> 'Kann Umfrage schlie�en',
	'a_vote_delete'			=> 'Kann Umfrage schlie�en',
	
	'a_database_backup'		=> 'Kann Datenbank-Backups erstellen',
	'a_database_optimize'	=> 'Kann Datenbank-Optimieren',
	'a_database_restore'	=> 'Kann Datenbank Backups wiederherstellen',
	'a_language'			=> 'Kann Sprachen verwalten',
	'a_log'					=> 'Kann Logeintr�ge sehen',
	'a_log_delete'			=> 'Kann Logeintr�ge l�schen',
	'a_menu'				=> 'Kann Men� verwalten',
	'a_module'				=> 'Kann Module verwalten',
	'a_phpinfo'				=> 'Kann PHP-Informationen sehen',
	'a_style'				=> 'Kann Templates verwalten',
	'a_style_create'		=> 'Kann Templates hinzuf�gen',
	'a_style_delete'		=> 'Kann Templates l�schen',
	'a_style_upload'		=> 'Kann Templates hochladen',

	
	'forms_noneset' => 'keine Standartwerte zugewiesen',
	
	'forum_access_no'				=> 'Kein Zugang',
	'forum_access_only_read'		=> 'Nur lesender Zugriff',
	'forum_access_default'			=> 'Standard-Zugang',
	'forum_access_default_polls'	=> 'Standard-Zugang + Umfragen',
	'forum_access_full'				=> 'Voller Zugang',
	
	'radio:type'	=> array(0 => 'Kategorie', 1 => 'Forum', 2 => 'Subforum'),
	
	'groups'		=> 'Gruppen',
	'groups_manage'	=> 'Gruppen bearbeiten',
	'groups_added'	=> 'Gruppen hinzuf�gen',
	
	'users'			=> 'Benutzer',
	'users_manage'	=> 'Benutzer bearbeiten',
	'users_added'	=> 'Benutzer hinzuf�gen',
	
	'auth_create'	=> 'Berechtigung hinzuf�gen',
	'auth_update'	=> 'Berechtigung �ndern',
	'auth_delete'	=> 'Berechtigung l�schen',
	
	'users_all'		=> 'Alle Benutzer ausw�hlen',
	'groups_all'	=> 'Alle Gruppen ausw�hlen',
	
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