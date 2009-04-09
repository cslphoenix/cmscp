<?php

$lang['Assigned_groups'] = 'Assigned Groups';
$lang['Membership_pending'] = '(membership pending: click \'YES\' to approve or \'NO\' to deny)';
$lang['Email_notification'] = 'Email notification if user is added to any groups';

$lang['user_head']			= 'Benutzer Administration';
$lang['user_explain']		= 'Hier kannst du die Daten und Optionen eines Nutzers �ndern. Um die Befugnisse eines Benutzers zu �ndern, benutze bitte die Benutzer- und Gruppenkontrolle.';
$lang['user_add']			= 'Neue Benutzer erstellen';
$lang['user_new_add']		= 'Benutzer hinzuf�gen';
$lang['user_edit']			= 'Benutzer bearbeiten';

$lang['user_group']			= 'Benutzergruppen & Teams';
$lang['user_groups']		= 'Benutzergruppen';
$lang['user_teams']			= 'Teams';
$lang['user_auths']			= 'Seitenberechtigung';
$lang['user_auths_explain']	= 'Hier kann jeder Benutzer individuell eingestellt werden, Spezial hei�t, er hat immer diese Rechte, egal wie die Gruppe oder Gruppen eingestellt sind von den Rechten!';

$lang['user_look_up']		= 'Benutzer ausw�hlen';

$lang['auth_contact']		= 'Kontakt';
$lang['auth_fightus']		= 'Fightus';
$lang['auth_forum']			= 'Forum';
$lang['auth_forum_auth']	= 'Forumberechtigung';
$lang['auth_games']			= 'Spiele';
$lang['auth_groups']		= 'Gruppen';
$lang['auth_joinus']		= 'Joinus';
$lang['auth_match']			= 'Match/Wars';
$lang['auth_navi']			= 'Navigation';
$lang['auth_news']			= 'News';
$lang['auth_news_public']	= 'News ver�ffentlichen';
$lang['auth_newscat']		= 'News Kategorien';
$lang['auth_ranks']			= 'R�nge';
$lang['auth_server']		= 'Server';
$lang['auth_teams']			= 'Teams';
$lang['auth_teamspeak']		= 'Teamspeak';
$lang['auth_training']		= 'Training';
$lang['auth_user']			= 'Benutzer';

$lang['group_open']		= 'Gruppe: ohne Anfrage';
$lang['group_quest']	= 'Gruppe: mit Anfrage';
$lang['group_closed']	= 'Gruppe: geschlossen';
$lang['group_hidden']	= 'Gruppe: versteckt';
$lang['group_system']	= 'Gruppe: System';

$lang['auth_guest']		= 'Gast';
$lang['auth_user']		= 'Benutzer';
$lang['auth_trial']		= 'Trial';
$lang['auth_member']	= 'Member';
$lang['auth_mod']		= 'Moderator';
$lang['auth_admin']		= 'Administrator';


$lang['group_type_opt']		= array(GROUP_OPEN => $lang['group_open'], GROUP_REQUEST => $lang['group_quest'], GROUP_CLOSED => $lang['group_closed'], GROUP_HIDDEN => $lang['group_hidden']);
$lang['group_access_opt']	= array(ADMIN => $lang['auth_admin'], MOD => $lang['auth_mod'], MEMBER => $lang['auth_member'], TRIAL => $lang['auth_trial'], USER => $lang['auth_user']);

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
//	Gruppen
//
$lang['group_head']			= 'Gruppen Administration';
$lang['group_explain']		= 'Hier kannst du die Gruppen Verwalten';
$lang['group_add']			= 'Neue Gruppe erstellen';
$lang['group_new_add']		= 'Gruppe hinzuf�gen';
$lang['group_edit']			= 'Gruppe bearbeiten';

$lang['group_name']			= 'Gruppenname';
$lang['group_access']		= 'Gruppenrechte';
$lang['group_type']			= 'Gruppentyp';
$lang['group_description']	= 'Beschreibung';
$lang['group_color']		= 'Gruppenfarbe';


//
//	News / Kategorie
//
$lang['news_head']			= 'News Administration';
$lang['news_explain']		= 'Hier kannst du die News Verwalten';
$lang['news_add']			= 'Neue News erstellen';
$lang['news_new_add']		= 'News hinzuf�gen';
$lang['news_edit']			= 'News bearbeiten';

$lang['newscat_head']		= 'Newskategorie Administration';
$lang['newscat_explain']	= 'Hier kannst du die Newskategorien Verwalten';
$lang['newscat_add']		= 'Neue Newskategorie erstellen';
$lang['newscat_new_add']	= 'Newskategorie hinzuf�gen';
$lang['newscat_edit']		= 'Newskategorie bearbeiten';

$lang['news_title']			= 'Newstitel';
$lang['news_category']		= 'Newskategorie';
$lang['news_match']			= 'Match einbinden';
$lang['news_text']			= 'Newstext';
$lang['news_link']			= 'Link URL / Name';
$lang['news_link_explain']	= 'Link beschreibung';
$lang['news_public']		= '�ffentlich';
$lang['news_public_time']	= 'Zeit & Datum zum ver�ffentlichen';
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

$lang['newscat_title']		= 'Name';
$lang['newscat_image']		= 'Bild';

//
//	Navigation
//
$lang['navi_head']		= 'Navigation Administration';
$lang['navi_explain']	= 'Hier kannst du die Navigation Verwalten';

$lang['navi_add']		= 'Neuen Link erstellen';
$lang['navi_new_add']	= 'Link hinzuf�gen';
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

//
//	Befugnisse
//
$lang['Public']			= '�ffentlich';
$lang['Private']		= 'Privat';
$lang['Trial']			= 'Trial';
$lang['Member']			= 'Member';
$lang['Registered']		= 'Registriert';
$lang['Administrators']	= 'Administratoren';
$lang['Hidden']			= 'Versteckt';

$lang['View'] = 'Ansicht';
$lang['Read'] = 'Lesen';
$lang['Post'] = 'Posten';
$lang['Reply'] = 'Antworten';
$lang['Edit'] = 'Editieren';
$lang['Delete'] = 'L�schen';
$lang['Sticky'] = 'Wichtig';
$lang['Announce'] = 'Ank�ndigung';
$lang['Poll'] = 'Umfrage';
$lang['Pollcreate'] = 'Umfrage erstellen';

$lang['Forum_ALL']		= 'Alle';
$lang['Forum_REG']		= 'Reg';
$lang['Forum_TRI']		= 'Trial';
$lang['Forum_MEM']		= 'Member';
$lang['Forum_MOD']		= 'Mods';
$lang['Forum_ACL']		= 'Privat';
$lang['Forum_ADM']		= 'Admin';

$lang['Group_DEFAULT']		= 'Vorgabe';
$lang['Group_DISALLOWED']	= 'Nein';
$lang['Group_ALLOWED']		= 'Ja';
$lang['Group_SPECIAL']		= 'Spezial';


//
//
//
$lang['contact_overview']			= 'Kontakt �bersicht';
$lang['contact_head_normal']		= 'Kontakt-Eintr�ge';
$lang['contact_head_fightus']		= 'FightUs-Eintr�ge';
$lang['contact_head_joinus']		= 'JoinUs-Eintr�ge';
$lang['contact_normal']				= 'Kontakt';
$lang['contact_fightus']			= 'FightUs';
$lang['contact_joinus']				= 'JoinUs';

$lang['contact_type_open']			= 'Offen';
$lang['contact_type_edit']			= 'Bearbeitung';
$lang['contact_type_close']			= 'Geschlossen';

//
//	Ranks
//
$lang['training_head']			= 'Training Administration';
$lang['training_explain']		= 'Hier kannst du Trainings der Teams Verwalten';

$lang['training_add']			= 'Neues Training erstellen';
$lang['training_new_add']		= 'Training hinzuf�gen';
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

$lang['training_create']		= 'Neues Training hinzuggef�gt.';
$lang['training_update']		= 'Training ge�ndert.';



//
// Admin Header
//
$lang['welcome']	= 'Adminbereich des CMSCP vom Clan ....';
$lang['session']	= 'Verlassen';
$lang['logout']		= 'Abmelden';
$lang['index']		= '�bersicht';
$lang['page']		= 'WebSeite';


//
// Menue
//
$lang['teams']			= 'Teams';
$lang['ranks']			= 'R�nge';
$lang['main']			= 'Allgemein';
$lang['logs']			= 'Protokolle';
$lang['match']			= 'Begegnungen';
$lang['teams_over']		= '�bersicht';
$lang['ranks_over']		= 'R�nge';
$lang['games_over']		= 'Spiele';
$lang['contact']		= 'Kontakt';
$lang['contact_over']	= 'Kontakt - �bersicht';
$lang['joinus']			= 'JoinUs';
$lang['fighus']			= 'FightUs';
$lang['teams']			= 'Teams';
$lang['set']			= 'Einstellungen';
$lang['set_ftp']		= 'FTP-Rechte';
$lang['logs_over']		= '�bersicht';
$lang['logs_admin']		= 'Protokoll - Admin';
$lang['logs_member']	= 'Protokoll - Member';
$lang['logs_user']		= 'Protokoll - User';
$lang['logs_db']		= 'Protokoll - DB-Fehler';
$lang['match_over']		= '�bersicht';
$lang['training']		= 'Training';
$lang['server']			= 'Server';
$lang['navi']			= 'Navigation';
$lang['permissions']	= 'Befugnisse';
$lang['forums']			= 'Forum';
$lang['users']			= 'Benutzer';
$lang['groups']			= 'Gruppen';
$lang['news']			= 'News';
$lang['newscat']		= 'Newskategorie';






//
// Allgemein
//
$lang['required']		= 'Mit * markierte Felder sind erforderlich';
$lang['delete']			= 'L�schen';
$lang['option_select']	= 'Option w�hlen';

$lang['setting']		= 'Einstellung';
$lang['settings']		= 'Einstellungen';
$lang['username']		= 'Benutzername';
$lang['register']		= 'Anmeldedatum';
$lang['joined']			= 'Beigetreten';
$lang['edit']			= 'Bearbeiten';
$lang['rank']			= 'Rang im Team';


$lang['mark_all']		= 'Alle markieren';
$lang['mark_deall']		= 'Keine Auswahl';

$lang['no_mode']		= 'Bitte eine g�ltige Aktion w�hlen.<br /><br /><a style="color:#fff; font-weight:bold; font-size:blod;" href="javascript:history.back(1)">Zur&uuml;ck</a>';

//
//	Auswahl
//
$lang['must_select_team']		= 'W�hle ein Team aus';
$lang['must_select_rank']		= 'W�hle ein Rang aus';
$lang['must_select_game']		= 'W�hle ein Spiel aus';
$lang['must_select_training']	= 'W�hle ein Training aus';
$lang['must_select_news']		= 'W�hle eine News aus';
$lang['must_select_newscat']	= 'W�hle eine Newskategorie aus';
$lang['must_select_']			= 'W�hle ein aus';

//
//	Erstellen und Erneuern
//
$lang['create_team']		= 'Neues Team hinzuggef�gt.';
$lang['create_match']		= 'Neues Match hinzuggef�gt.';
$lang['create_rank']		= 'Neuer Rang hinzuggef�gt.';
$lang['create_game']		= 'Neues Spiel hinzuggef�gt.';
$lang['create_training']	= 'Neues Training hinzuggef�gt.';
$lang['create_news']		= 'Neue News hinzuggef�gt.';
$lang['create_newscat']		= 'Neue Newskategorie hinzuggef�gt.';
$lang['create_navigation']	= 'Neuen Link hinzuggef�gt.';
$lang['create_forum']		= 'Neues Forum hinzugef�gt.';
$lang['create_group']		= 'Neue Gruppe hinzuggef�gt.';
$lang['create_']			= 'Neues  hinzuggef�gt.';

$lang['update_team']		= 'Teamdaten erfolgreich ge�ndert';
$lang['update_match']		= 'Matchdaten erfolgreich ge�ndert';
$lang['update_rank']		= 'Rangdaten erfolgreich ge�ndert';
$lang['update_game']		= 'Spieldaten erfolgreich ge�ndert';
$lang['update_training']	= 'Trainingsdaten erfolgreich ge�ndert';
$lang['update_news']		= 'Newsdaten erfolgreich ge�ndert';
$lang['update_newscat']		= 'Newskategoriedaten erfolgreich ge�ndert';
$lang['update_navigation']	= 'Link erfolgreich ge�ndert';
$lang['update_forum']		= 'Forumdaten erfolgreich ge�ndert';
$lang['update_group']		= 'Gruppendaten erfolgreich ge�ndert';
$lang['update_']			= 'daten erfolgreich ge�ndert';


//
//	Best�tigen und L�schen
//
$lang['confirm']				= 'Best�tigen';

$lang['confirm_delete_team']		= 'Bist du sicher, das dieses Team gel�scht werden soll?';
$lang['confirm_delete_rank']		= 'Bist du sicher, das dieser Rang gel�scht werden soll?';
$lang['confirm_delete_game']		= 'Bist du sicher, das dieses Spiel gel�scht werden soll?';
$lang['confirm_delete_training']	= 'Bist du sicher, das dieses Training gel�scht werden soll?';
$lang['confirm_delete_news']		= 'Bist du sicher, das die News gel�scht werden soll?';
$lang['confirm_delete_newscat']		= 'Bist du sicher, das die Newskategorie gel�scht werden soll?';
$lang['confirm_delete_navigation']	= 'Bist du sicher, das der Link gel�scht werden soll?';
$lang['confirm_delete_group']		= 'Bist du sicher, das die Gruppe gel�scht werden soll?';

$lang['delete_team']				= 'Das Team wurde gel�scht';
$lang['delete_rank']				= 'Der Rang wurde gel�scht';
$lang['delete_game']				= 'Das Spiel wurde gel�scht';
$lang['delete_training']			= 'Der Rang wurde gel�scht';
$lang['delete_news']				= 'Die News wurde gel�scht';
$lang['delete_newscat']				= 'Der Newskategorie wurde gel�scht';
$lang['delete_navigation']			= 'Der Link wurde gel�scht';
$lang['delete_group']				= 'Die Gruppe wurde gel�scht';


//
//	Clicks
//
$lang['click_admin_index']			= 'Klicke %shier%s, um zum Adminstart zur�ckzukehren';
$lang['click_return_team']			= 'Klicke %shier%s, um zur Team Administration zur�ckzukehren';
$lang['click_return_team_member']	= 'Klicke %shier%s, um zur Teammember Administration zur�ckzukehren';
$lang['click_return_rank']			= 'Klicke %shier%s, um zur Rang Administration zur�ckzukehren';
$lang['click_return_set']			= 'Klicke %shier%s, um zur Einstelluns Administration zur�ckzukehren';
$lang['click_return_game']			= 'Klicke %shier%s, um zur Spiel Administration zur�ckzukehren';
$lang['click_return_match']			= 'Klicke %shier%s, um zur Match Administration zur�ckzukehren';
$lang['click_return_match_details']	= 'Klicke %shier%s, um zur Matchdeatils Administration zur�ckzukehren';
$lang['click_return_details']		= 'Klicke %shier%s, um zur Detail Administration zur�ckzukehren';
$lang['click_return_training']		= 'Klicke %shier%s, um zur Trainings Administration zur�ckzukehren';
$lang['click_return_news']			= 'Klicke %shier%s, um zur News Administration zur�ckzukehren';
$lang['click_return_newscat']		= 'Klicke %shier%s, um zur Newskategorie Administration zur�ckzukehren';
$lang['click_return_navigation']	= 'Klicke %shier%s, um zur Navigations Administration zur�ckzukehren';
$lang['click_return_forum']			= 'Klicke %shier%s, um zur Forum Administration zur�ckzukehren';
$lang['click_return_group']			= 'Klicke %shier%s, um zur Gruppen Administration zur�ckzukehren';


//
//	Errors
//


$lang['DB_errors']					= 'DB-Fehler';
$lang['No_errors_found']			= 'Keine Fehler gefunden bzw. es sind bisher keine Eintr�ge in der Datenbank hinterlegt!';
$lang['Click_return_error_log']		= 'Klicke %shier%s, um zur �bersicht zur�ckzukehren';
$lang['Could_not_delete_id']		= 'Eintrag konnte nicht gel�scht werden!';
$lang['Could_not_delete_all_id']	= 'Eintr�ge konnte nicht gel�scht werden!';
$lang['Id_deleted']					= 'Eintrag wurde gel�scht!';
$lang['Id_all_deleted']				= 'Alle Eintr�ge wurden gel�scht!';

//
//	Teams
//
$lang['team_head']			= 'Team Administration';
$lang['team_explain']		= 'Hier kannst du die Teams der Seite �berwachen. Du kannst bestehende Gruppen l�schen, editieren oder neue anlegen.';
$lang['team_add_member_ex']	= 'Hier kannst du, Member hinzuf�gen, bitte schreibe den Loginname mit Komma getrennt auf!';

$lang['team_add']			= 'Neues Team erstellen';
$lang['team_new_add']		= 'Team hinzuf�gen';
$lang['team_edit']			= 'Team bearbeiten';
$lang['team_add_member']	= 'Member hinzuf�gen';
$lang['team_edit_member']	= 'Member bearbeiten';
$lang['team_view_member']	= 'Member �bersicht';

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
$lang['team_menu_setting']	= 'Men� Einstellungen';

$lang['member_empty']		= 'Keine Mitglieder vorhanden.';


$lang['team_empty'] = 'Es sind keine Teams vorhanden.';

$lang['team_membercount'] = 'Memberanzahl';
$lang['team_member'] = 'Mitglieder';

$lang['team_add_member'] = 'Member hinzugef�gt';
$lang['team_del_member'] = 'Member gel�scht';
$lang['team_change_member'] = 'Rang ge�ndert';
$lang['status_set'] = '<strong>Status %s setzen</strong>';

$lang['team_create'] = 'Neues Team hinzuggef�gt.';
$lang['team_update'] = 'Update des Teams erfolgt.';

//
//	Ranks
//
$lang['rank_head']		= 'Rang Administration';
$lang['rank_explain']	= 'Hier kannst du die R�nge der Seite Verwalten. Du kannst bestehende R�nge l�schen, editieren oder neue anlegen.';

$lang['rank_add']			= 'Neuen Rang erstellen';
$lang['rank_new_add']		= 'Rang hinzuf�gen';
$lang['rank_edit']			= 'Rang bearbeiten';
$lang['rank_empty']			= 'Es sind keine R�nge vorhanden.';
$lang['rank_title']			= 'Rangtitle';
$lang['rank_page']			= 'Seitenrang';
$lang['rank_forum']			= 'Forumrang';
$lang['rank_team']			= 'Teamrang';
$lang['rank_special']		= 'Spezial Rang';
$lang['rank_min']			= 'Beitr�ge';
$lang['rank_image']			= 'Rangbild';
$lang['rank_type']			= 'Rangtype';

//
//	Games
//
$lang['game_head']		= 'Spieladministration';
$lang['game_explain']	= 'Hier kannst du die Spiele der Seite Verwalten. Du kannst bestehende Spiele l�schen, editieren oder neue anlegen.';

$lang['game_add']			= 'Neues Spiel erstellen';
$lang['game_new_add']		= 'Spiel hinzuf�gen';
$lang['game_edit']			= 'Spiel bearbeiten';
$lang['game_empty']			= 'Es sind keine Spiele vorhanden.';
$lang['game_title']			= 'Spielname';

$lang['game_size']			= 'Bildgr��e';
$lang['game_image']			= 'Spielicon';

$lang['game_update'] = 'Update des Spiels erfolgt.';
$lang['game_create'] = 'Neues Spiel hinzuggef�gt.';
$lang['game_update'] = 'Update des Spiels erfolgt.';
$lang['game_empty'] = 'Es sind keine Spiele vorhanden.';

//
//	Match
//
$lang['match_head']				= 'Match Administration';
$lang['match_explain']			= 'Hier k�nnen Wars verwaltet werden.';
$lang['match_details_explain']	= 'Hier k�nnen die Details f�r den War eingetragen und ver�ndert werden.';

$lang['match_add']			= 'Neues Match erstellen';
$lang['match_new_add']		= 'Match hinzuf�gen';
$lang['match_edit']			= 'Match bearbeiten';
$lang['match_update']		= 'Match ge�ndert.';
$lang['match_upcoming']		= 'Anstehende Match';
$lang['match_expired']		= 'Abgelaufen Match';

$lang['match_team']			= 'Wechles Team';
$lang['match_type']			= 'XonX';
$lang['match_categorie']	= 'Match Art';
$lang['match_league']		= 'Liga';
$lang['match_league_url']	= 'Liga Link';
$lang['league_match']		= 'Ligamatchlink';
$lang['match_date']			= 'Match Datum';
$lang['match_public']		= 'Match �ffentlich?';
$lang['match_comments']		= 'Kommentare erlauben?';
$lang['match_rival']		= 'Gegner Name';
$lang['match_rival_tag']	= 'Gegner Clantag';
$lang['match_rival_url']	= 'Gegner Homepage';
$lang['match_server']		= 'Server';
$lang['match_serverpw']		= 'Server PW';
$lang['match_hltv']			= 'HLTV-Server';
$lang['match_hltvpw']		= 'HLTV-Server PW';
$lang['match_text']			= 'Match Bericht';
$lang['match_interest_reset'] = 'Teilnahme zur�cksetzen?';

$lang['match_map_more']			= 'weitere Map';
$lang['match_map_close']		= 'schlie�en';

$lang['match_rival_lineup']		= 'Gegner Lineup';
$lang['match_details_map']		= 'Map';
$lang['match_details_mappic']	= 'Mapbild hochladen';
$lang['match_details_points']	= 'Punkte';
$lang['match_details_info']		= 'Wardetails in der �bersicht';
$lang['match_details_comment']	= 'Match Kommentar';
$lang['match_lineup']			= 'Clan Lineup';
$lang['match_lineup_add_yes']	= 'Spieler hinzugef�gt';
$lang['match_lineup_del_yes']	= 'Spieler gel�scht';
$lang['match_lineup_add']		= 'Spieler hinzuf�gen';
$lang['match_lineup_explain']	= 'Spieler einfach mit gedr�ckter STRG Taste ausw�hlen und Absenden.';
$lang['match_lineup_status']	= 'Spielerstatus';
$lang['match_lineup_add']		= 'Spieler hinzugef�gt';
$lang['match_lineup_del']		= 'Spieler gel�scht';
$lang['match_lineup_no_users']	= 'Bitte Spieler ausw�hlen die noch nicht eingetragen sind.';
$lang['match_lineup_change']	= 'Spielerliste ver�ndert';


$lang['select_team']		= 'Team ausw�hlen';

$lang['add_train']			= 'hinzuf�gen';
$lang['edit_train']			= '�ndern';
$lang['match_player']		= 'Spieler';
$lang['match_replace']		= 'Ersatz';


$lang['select_categorie']	= 'Matchtyp ausw�hlen';
$lang['select_categorie1']	= 'Fun War';
$lang['select_categorie2']	= 'Clan War';
$lang['select_categorie3']	= 'Liga War';
$lang['select_categorie4']	= 'Train War';

$lang['select_type']		= 'XonX ausw�hlen';
$lang['select_type1']		= 'Unbekannt';
$lang['select_type2']		= '2on2';
$lang['select_type3']		= '3on3';
$lang['select_type4']		= '4on4';
$lang['select_type5']		= '5on5';
$lang['select_type6']		= '6on6';

$lang['select_league']		= 'Liga ausw�hlen';
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
//	Settings
//
$lang['title_set']				= 'Einstellungen der Seite';
$lang['explain_set']			= 'Hier werden alle wichtigen Einstellungen f�r die Seite gemacht.';

//	Haupteinstellungen
$lang['settings_general']			= 'Seite';
$lang['settings_general_explain']	= 'alle wichtigen Einstellungen die die Seite betreffen.';
$lang['settings_upload']			= 'Upload Allgemein';
$lang['settings_upload_explain']	= 'R�ne und Rote Punkte symbolisieren, ob ein Verzeichnis Vorhanden ist und ob Schreibrecht vorliegen.';
$lang['settings_team_logo']			= 'Team Logo Upload';
$lang['settings_team_logo_explain']	= 'Hier k�nnen spezielle Parameter f�r den Upload von Teamlogos bestimmt werden.';

//	Allgeminer Block
$lang['server_name']				= 'Domainname';
$lang['server_name_explain']		= 'Der Name der Domain, auf dem die Seite l�uft';
$lang['server_port']				= 'Server Port';
$lang['server_port_explain']		= 'Der Port, unter dem dein Server l�uft, normalerweise 80. �ndere dies nur, wenn es ein anderer Port ist';
$lang['script_pfad']				= 'Scriptpfad';
$lang['script_pfad_explain']		= 'Der Pfad zum CMS, relativ zum Domainnamen';
$lang['site_name']					= 'Name der Seite';
$lang['site_name_explain']			= 'Wird auf jeder Seite angezeigt.';
$lang['site_description']			= 'Beschreibung der Seite';
$lang['disable_page']				= 'Seite deaktivieren';
$lang['disable_page_explain']		= 'Hiermit sperrst du die Seite f�r alle Benutzer. Administratoren k�nnen auf den Administrations-Bereich zugreifen, wenn die Seite gesperrt ist.';
$lang['disable_page_reason']		= 'Deaktivierungsgrund';
$lang['disable_page_mode']			= 'Deaktivert f�r Level';

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
$lang['team_logo_size']				= 'Team Logo Gr��e';
$lang['team_logo_size_explain']		= 'Team Logo Gr��e Beschreibung';

$lang['team_logos_upload']			= 'Team Logo Upload';
$lang['team_logos_upload_explain']	= 'Team Logo';
$lang['team_logos_file']				= 'Team Logo Datei';
$lang['team_logos_file_explain']		= 'Team Logo Dateibeschreibung';
$lang['team_logos_size']				= 'Team Logo Gr��e';
$lang['team_logos_size_explain']		= 'Team Logo Gr��e Beschreibung';



//	Server
$lang['server_head']		= 'Serveradministration';
$lang['server_explain']		= 'Hier kannst du die Server (Game/Voice) der Seite �berwachen. Du kannst bestehende Server l�schen, editieren oder neue anlegen.';

$lang['server_add']			= 'Neuen Server eintragen';
$lang['server_new_add']		= 'Server eintragen';
$lang['server_edit']		= 'Server bearbeiten';

$lang['server_game']		= 'Gameserver';
$lang['server_voice']		= 'Voiceserver';
$lang['']		= '';
$lang['']		= '';
$lang['']		= '';
$lang['']		= '';
$lang['']		= '';
$lang['']		= '';

$lang['select_live']		= 'Live Status ausw�hlen';

//
//	Game
//
$lang['game_select']	= 'Spiel ausw�hlen';

//
//	Training
//
$lang['select_match']	= 'Match ausw�hlen';


//
//	Meldungen
//
$lang['empty_name']			= 'Bitte einen Namen eingeben!';
$lang['empty_title']		= 'Bitte einen Titel eingeben!';
$lang['no_select_module']	= 'Kein Modul ausgew�hlt!';
$lang['auth_fail']			= 'Keine Berechtiung f�r dieses Modul';
$lang['wrong_filetype']		= 'Falscher Dateityp!';
$lang['wrong_back']			= '<br /><br /><a style="color:#fff; font-weight:bold; font-size:blod;" href="javascript:history.back(1)">&laquo; Zur&uuml;ck</a>';
$lang['no_entry']			= 'Keine Daten Vorhanden';
$lang['id_nonexistent']		= 'ID nicht vorhanden!';

//	Teams

$lang['team_not_exist']			= 'Das Team ist nicht vorhanden!';
$lang['create_no_name']			= 'Namen f�r das Team eingeben!';
$lang['team_no_new'] = 'Keinen Neuen Member';
$lang['team_no_select'] = 'Keine Member ausgew�hlt!';

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

$lang['select_fail_team']			= 'Bitte ein Team ausw�hlen';
$lang['select_fail_type']			= 'Bitte ein Type ausw�hlen';
$lang['select_fail_cate']			= 'Bitte eine Spielkategorie ausw�hlen';
$lang['select_fail_league']			= 'Bitte eine Liga ausw�hlen';
$lang['select_fail_duration']		= 'Bitte eine Zeitdauer w�hlen';
$lang['select_fail_date']			= 'Bitte ein g�ltiges Datum w�hlen';
$lang['select_fail_map']			= 'Bitte ein Map angeben';


?>