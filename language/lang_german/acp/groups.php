<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'title'		=> 'Benutzergruppe',
	'titles'	=> 'Benutzergruppen',
	
	'explain'	=> 'Hier kannst du die Gruppen Verwalten.',
	'explain_o'	=> 'Hier sind alle Gruppen aufgelistet die es gibt.',
	
	'update_change'	=> 'Rechte wurden erfolgreich geändert.',
	
	'input_data'	=> 'Gruppendaten',
	
	'group_name'	=> 'Name',
	'group_mod'		=> 'Moderator',
	'group_access'	=> 'Zugriffslevel',
	'group_type'	=> 'Anfragentyp',
	'group_desc'	=> 'Beschreibung',
	'group_color'	=> 'Farbe',
	'group_legend'	=> 'Legende',
	'group_rank'	=> 'Rang',
	'group_image'	=> 'Bild',
	'group_order'	=> 'Reihenfolge',
	
	'count'		=> 'Mitgliederanzahl',
	'view_member'	=> 'Mitglieder Übersicht',
	
	'group_allowed'		=> 'Ja',
	'group_disallowed'	=> 'Nein',
	
	'select_group_mod'	=> '&raquo; Gruppenmoderator auswählen',
	'Group_DEFAULT'	=> 'Vorgabe',
	'Group_SPECIAL'	=> 'Spezial',
	

	'group_open'		=> 'Offen',
	'group_request'		=> 'Anfragen',
	'group_closed'		=> 'Geschlossen',
	'group_hidden'		=> 'Versteckt',
	'group_system'		=> 'System',
	
	'change'			=> 'Rechte geben/nehmen',
	'request_agree'		=> 'Antrag zustimmen',
	'request_deny'		=> 'Antrag verweigern',
	
	'pending_members'	=> 'wartende Mitglieder',
	
	'member'		=> 'Gruppenmitglied',
	'members'		=> 'Gruppenmitglieder',
	'moderator'		=> 'Gruppenmoderator',
	'moderators'	=> 'Gruppenmoderatoren',
	
	

));

$lang = array_merge($lang, array(
	
	'group_access_ary'	=> array(
		USER	=> 'Benutzer',
		TRIAL	=> 'Trial',
		MEMBER	=> 'Member',
		MOD		=> 'Moderator',
		ADMIN	=> 'Administrator',
	),
	
	'group_type_ary'	=> array(
		GROUP_OPEN		=> $lang['group_open'],
		GROUP_REQUEST	=> $lang['group_request'],
		GROUP_CLOSED	=> $lang['group_closed'],
		GROUP_HIDDEN	=> $lang['group_hidden'],
		GROUP_SYSTEM	=> $lang['group_system'],
	),
	
	'radio:type'	=> array(
		GROUP_OPEN		=> $lang['group_open'],
		GROUP_REQUEST	=> $lang['group_request'],
		GROUP_CLOSED	=> $lang['group_closed'],
		GROUP_HIDDEN	=> $lang['group_hidden'],
		GROUP_SYSTEM	=> $lang['group_system'],
	),
));

$lang = array_merge($lang, array(
	
	'radio:legend'	=> array(1 => $lang['common_view'], 0 => $lang['common_noview']),
	
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