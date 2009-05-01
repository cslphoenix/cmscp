<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

$lang['bugtracker']		= 'BugTracker';

//
//	Allgemein
//
$lang['bt_assigned']		= 'Zuordnung';
$lang['bt_unassigned']		= 'nicht zugeordnet';
$lang['bt_status_type']		= 'Status / Art';


//
//	Status für Entwickler
//
$lang['bt_status_all']		= 'Alle Meldungen';
$lang['bt_new']				= 'Neuer Fehler';
$lang['bt_completed']		= 'Erledigt';
$lang['bt_pending']			= 'anstehend/unerledigt';
$lang['bt_processing']		= 'In Bearbeitung';
$lang['bt_notbug']			= 'Kein Fehler';
$lang['bt_noreplace']		= 'Nicht nachproduzierbar';
$lang['bt_wish']			= 'Wunsch';
$lang['bt_improvement']		= 'Verbesserung';

$lang['bt_status'] = array(
	'bt_status_all'		=> $lang['bt_status_all'],
	'bt_new'			=> $lang['bt_new'],
	'bt_completed'		=> $lang['bt_completed'],
	'bt_pending'		=> $lang['bt_pending'],
	'bt_processing'		=> $lang['bt_processing'],
	'bt_notbug'			=> $lang['bt_notbug'],
	'bt_noreplace'		=> $lang['bt_noreplace'],
	'bt_wish'			=> $lang['bt_wish'],
	'bt_improvement'	=> $lang['bt_improvement'],
);

$lang['bt_error_lang']		= 'Fehler: Sprachdatei';
$lang['bt_error_design']	= 'Fehler: Design';
$lang['bt_error_onload']	= 'Fehler: beim Seitenaufruf';
$lang['bt_error_file']		= 'Fehler: in einer Datei';
$lang['bt_error_sql']		= 'Fehler: in einer SQL-Abfrage';
$lang['bt_error_function']	= 'Fehler: in einer Funktion';
$lang['bt_error_acp']		= 'Fehler: im Adminbereich';
$lang['bt_error_mcp']		= 'Fehler: im Moderationsbereich';
$lang['bt_error_ucp']		= 'Fehler: im Benutzerbereich';
$lang['bt_error_msg']		= 'Fehler: einfach nur Fehlermeldung';
$lang['bt_improvement']		= 'Verbesserungsvorschlag';

$lang['bt_error'] = array(
	'bt_error_lang'		=> $lang['bt_error_lang'],
	'bt_error_design'	=> $lang['bt_error_design'],
	'bt_error_onload'	=> $lang['bt_error_onload'],
	'bt_error_file'		=> $lang['bt_error_file'],
	'bt_error_sql'		=> $lang['bt_error_sql'],
	'bt_error_function'	=> $lang['bt_error_function'],
	'bt_error_acp'		=> $lang['bt_error_acp'],
	'bt_error_mcp'		=> $lang['bt_error_mcp'],
	'bt_error_ucp'		=> $lang['bt_error_ucp'],
	'bt_error_msg'		=> $lang['bt_error_msg'],
	'bt_improvement'	=> $lang['bt_improvement'],
);

$lang['bt_add']							= 'Eintrag Erfolgreich';
$lang['bt_edit']						= 'Änderung Erfolgreich';
$lang['click_return_bugtracker']		= '%sHier klicken%s, um zur Übersicht zurückzukehren.';

$lang['bt_create_by']					= 'Erstellt von %s am %s.';

$lang['bt_head_add']		= 'BugTracker :: Neuen Eintrag hinzufügen';
$lang['bt_head_edit']		= 'BugTracker :: Eintrag bearbeiten';
$lang['bt_title']		= 'Titel';
$lang['bt_type']		= 'Art des Fehlers';
$lang['bt_version']		= 'Script Version';
$lang['bt_desc']		= 'kurze Beschreibung';
$lang['bt_message']		= 'Nachricht';
$lang['bt_php']			= 'PHP Version';
$lang['bt_sql']			= 'SQL Version';

$lang['msg_select_title']		= 'Bitte ein Titel eingeben!';
$lang['msg_select_type']		= 'Bitte eine Art des Fehlers auswählen!';
$lang['msg_select_version']		= 'Bitte eine Script Version auswählen!';
$lang['msg_select_desc']		= 'Bitte eine kurze Beschreibung abgeben!';
$lang['msg_select_message']		= 'Nachricht vergessen?';

$lang['bt_head']			= 'BugTracker Administration';
$lang['bt_explain']			= 'Hier sind alle Bugeinträge aufgelistet';
$lang['bt_edit']			= 'Bugeintrag bearbeiten';
$lang['bt_name']			= 'Bugeinträge';

$lang['select_bt_type']		= '&raquo; Fehler auswählen';
$lang['select_bt_version']	= '&raquo; Script Version auswählen';

$lang['bt_details']			= 'BugTracker Details';
$lang['bt_details_to']		= 'BugTracker Details zu: %s';

?>