<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'group'		=> 'Gruppe',
	'groups'	=> 'Gruppen',
	'explain'	=> 'Hier kannst du die Gruppen Verwalten.',
	'explain_o'	=> 'Hier sind alle Gruppen aufgelistet die es gibt.',
	
	'create'	=> 'Neue Gruppe hinzugefügt.',
	'update'	=> 'Gruppendaten erfolgreich geändert.',
	'delete'	=> 'Die Gruppe wurde gelöscht!',
	'confirm'	=> 'das diese Gruppe:',
	
	'update_change'	=> 'Rechte wurden erfolgreich geändert.',
	
	'auth'		=> 'Gruppenrechte',
	'data'		=> 'Gruppendaten',
	'image'		=> 'Gruppenbild',

	'access'	=> 'Gruppenrechte',
	'mod'		=> 'Moderator',

	'type'		=> 'Gruppentyp',
	'legend'	=> 'Legende',
	'color'		=> 'Gruppenfarbe',
	'rank'		=> 'Gruppenrang',
	'upload'	=> 'Hochladen',
	'current'	=> 'Aktuelles Gruppenbild',


	'count'		=> 'Mitgliederanzahl',
	'view_member'	=> 'Mitglieder Übersicht',
	
	'group_allowed'		=> 'Ja',
	'group_disallowed'	=> 'Nein',
	
	'select_group_mod'	=> '&raquo; Gruppenmoderator auswählen',
	'Group_DEFAULT'	=> 'Vorgabe',
	'Group_SPECIAL'	=> 'Spezial',
	

	'group_open'		=> 'Gruppe: ohne Anfrage',
	'group_request'		=> 'Gruppe: mit Anfrage',
	'group_closed'		=> 'Gruppe: geschlossen',
	'group_hidden'		=> 'Gruppe: versteckt',
	'group_system'		=> 'Gruppe: System',
	
	'change'			=> 'Rechte geben/nehmen',
	'request_agree'		=> 'Antrag zustimmen',
	'request_deny'		=> 'Antrag verweigern',
	
	'pending_members'	=> 'wartende Mitglieder',
	
	'group_access'		=> array(
		USER	=> 'Benutzer',
		TRIAL	=> 'Trial',
		MEMBER	=> 'Member',
		MOD		=> 'Moderator',
		ADMIN	=> 'Administrator',
	),
	
	'group_type'	=> array(
		GROUP_OPEN		=> 'Gruppe: ohne Anfrage',
		GROUP_REQUEST	=> 'Gruppe: mit Anfrage',
		GROUP_CLOSED	=> 'Gruppe: geschlossen',
		GROUP_HIDDEN	=> 'Gruppe: versteckt',
		GROUP_SYSTEM	=> 'Gruppe: System',
	),

));





/*
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
$lang['group_desc']	= 'Beschreibung';
$lang['group_color']		= 'Gruppenfarbe';
$lang['group_legend']		= 'Legende';
$lang['group_rank']			= 'Gruppenrang';
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
*/



?>