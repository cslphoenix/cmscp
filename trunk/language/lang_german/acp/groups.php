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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 *	Gruppen / Groups
 *
 */

if ( !defined('IN_CMS') )
{
	exit;
}

$lang['group']					= 'Gruppe';
$lang['groups']					= 'Gruppen';
$lang['group_explain']			= 'Hier kannst du die Gruppen Verwalten';
$lang['group_overview_explain']	= 'Hier sind alle Gruppen aufgelistet die es gibt, leider aus Platzgründen vorerst immer nur 5!';

$lang['group_member']			= 'Gruppenmitglieder';
$lang['group_add_member']		= 'Mitglieder hinzufügen';
$lang['group_edit_member']		= 'Mitglieder bearbeiten';
$lang['group_view_member']		= 'Mitglieder Übersicht';
$lang['group_view_member']		= 'Mitglieder Übersicht';
$lang['group_add_member_ex']	= 'Hier kannst du, Mitglieder hinzufügen. Entweder Benutzer per Login mit Komma getrennt eintragen, <b>oder</b> über das Dropdown-Menü auswahlen!';
$lang['group_name']			= 'Gruppenname';
$lang['group_mod']			= 'Moderator';
$lang['group_membercount']	= 'Mitgliederanzahl';
$lang['group_access']		= 'Gruppenrechte';
$lang['group_type']			= 'Gruppentyp';
$lang['group_description']	= 'Beschreibung';
$lang['group_color']		= 'Gruppenfarbe';
$lang['group_legend']		= 'Legende';
$lang['group_rank']			= 'Gruppenrang';
$lang['group_auth']			= 'Gruppenrechte';
$lang['group_data']			= 'Gruppendaten';
$lang['group_image']		= 'Gruppenbild';
$lang['group_image_upload']		= 'Hochladen';
$lang['group_image_current']	= 'Aktuelles Gruppenbild';

$lang['select_group_mod']	= '&raquo; Gruppenmoderator auswählen';
$lang['Group_DEFAULT']		= 'Vorgabe';
$lang['Group_SPECIAL']		= 'Spezial';
$lang['group_allowed']		= 'Ja';
$lang['group_disallowed']	= 'Nein';

$lang['group_open']		= 'Gruppe: ohne Anfrage';
$lang['group_request']	= 'Gruppe: mit Anfrage';
$lang['group_closed']	= 'Gruppe: geschlossen';
$lang['group_hidden']	= 'Gruppe: versteckt';
$lang['group_system']	= 'Gruppe: System';

$lang['group_option_type'] = array(
	GROUP_SYSTEM	=> $lang['group_system'],
	GROUP_HIDDEN	=> $lang['group_hidden'],
	GROUP_CLOSED	=> $lang['group_closed'],
	GROUP_REQUEST	=> $lang['group_request'],
	GROUP_OPEN		=> $lang['group_open'],
);

$lang['group_option_access'] = array(
	ADMIN	=> $lang['auth_admin'],
	MOD		=> $lang['auth_mod'],
	MEMBER	=> $lang['auth_member'],
	TRIAL	=> $lang['auth_trial'],
	USER	=> $lang['auth_user'],
);

$lang['no_members']		= 'Diese Gruppe hat keine Mitglieder.';
$lang['no_moderators']	= 'Diese Gruppe hat keine Moderatoren.';



?>