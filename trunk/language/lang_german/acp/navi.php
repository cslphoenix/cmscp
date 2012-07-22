<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Navigation',
	'titles'	=> 'Subnavieinstellungen',
	'field'		=> 'Navi-Link',
	'explain'	=> 'Hier kannst du die Navigation Verwalten. Einträge die wie das <em><strong>Beispiel</strong></em> aussehen sind Intern, also nur für eingeloggte Personen.',
	
	'create'	=> 'Neuen Navi-Link hinzugefügt.',
	'update'	=> 'Navidaten erfolgreich geändert.',
	'delete'	=> 'Der Navi-Link wurde gelöscht!',
	'confirm'	=> 'das der Navi-Link:',
	
	'navi_main'	=> 'Main Navi',
	'navi_clan'	=> 'Clan Navi',
	'navi_com'	=> 'Community Navi',
	'navi_misc'	=> 'Misc Navi',
	'navi_user'	=> 'Benutzer Navi',
	
	'url'			=> 'Navi-Link URL',
	'target'		=> 'Ziel',
	'target_new'	=> 'Neue Seite',
	'target_self'	=> 'Selbe Seite',
	
	'update_sub'	=> 'Subnavigation erfolgreich geändert.',
	'return_sub'	=> '<br /><br /><strong>%s&laquo; Subnavigations Administration%s</strong>',
	
	
	'news_limit'			=> 'Anzahl an Nachrichtentiteln',
	'news_limit_explain'	=> 'Die Zahl angeben, wieviele News untereinander angezeigt werden sollen.',
	'news_length'			=> 'Anzahl der Zeichen vom Nachrichtentitel',
	'news_length_explain'	=> 'Maximale Anzahl an Zeichen die angezeigt werden, der Text wird bei Überschreitung gekürzt und mit ... ergänzt.',
	'match_length'			=> 'Anzahl der Zeichen vom Name der Gegner',
	'match_length_explain'	=> 'Maximale Anzahl an Zeichen die angezeigt werden, der Text wird bei Überschreitung gekürzt und mit ... ergänzt.',
	'match_limit'			=> 'Anzahl der Begegnungen',
	'match_limit_explain'	=> 'Die Zahl angeben, wieviele Begegnungen untereinander angezeigt werden sollen.',

	'user_cache'	=> 'Cachedauer in Sekunden',
	'user_length'	=> 'Anzahl der Zeichen vom Benutzername',
	'user_limit'	=> 'Anzahl an Benutzern',
	'user_show'		=> 'Letzten Neuen Benutzer anzeigen',
	'teams_show'	=> 'Teams anzeigen',
	'teams_length'	=> 'Teamnamenlänge',
	
	'newusers_show'				=> 'Benutzer anzeigen',
	'newusers_show_explain'		=> '',
	'newusers_cache'			=> 'Speicherdauer',
	'newusers_cache_explain'	=> '',
	'newusers_limit'			=> 'Anzahl an Benutzern',
	'newusers_limit_explain'	=> '',
	'newusers_length'			=> 'Anzahl an Buchstaben',
	'newusers_length_explain'	=> '',
	'teams_show_explain'		=> '',
	'teams_limit'				=> 'Anzahl an Teams',
	'teams_limit_explain'		=> '',
	'teams_length_explain'		=> '',

));

$lang = array_merge($lang, array(
	
	'radio:target'	=> array(1 => $lang['target_new'], 0 => $lang['target_self']),
	'radio:type'	=> array(1 => $lang['main'], 2 => $lang['clan'], 3 => $lang['com'], 4 => $lang['misc'], 5 => $lang['user']),

));


/*
$lang['click_return_navigation']		= '<br><br><strong>%s&laquo; Navigations Administration%s</strong>';		
$lang['click_return_navigation_set']	= '<br><br><strong>%s&laquo; Subnavigations Administration%s</strong>';			
$lang['delete_navigation']			= 'Der Link wurde gelöscht!';
$lang['delete_confirm_navigation']	= 'das der Link:';
$lang['update_navigation']			= 'Link erfolgreich geändert';
$lang['update_navigation_set']		= 'Subnavigation erfolgreich geändert';		
$lang['create_navigation']			= 'Neuen Link hinzugefügt.';


$lang['navi']			= 'Navigation';
$lang['field']			= 'Navi-Link';

$lang['navi_settings']		= 'Subnavieinstellungen';
$lang['navi_explain']		= 'Hier kannst du die Navigation Verwalten. Einträge die wie das <em><strong>Beispiel</strong></em> aussehen sind Intern, also nur für eingeloggte Personen.';

$lang['navi_url']			= 'Navi-Link URL';
$lang['navi_target']		= 'Ziel';
$lang['navi_target_new']	= 'Neue Seite';
$lang['navi_target_self']	= 'Selbe Seite';

$lang['navi_main']			= 'Main Navi';
$lang['navi_clan']			= 'Clan Navi';
$lang['navi_com']			= 'Community Navi';
$lang['navi_misc']			= 'Misc Navi';
$lang['navi_user']			= 'Benutzer Navi';



$lang['limit_news'] = 'Anzahl an Nachrichtentiteln';
$lang['limit_news_explain'] = 'Die Zahl angeben, wieviele News untereinander angezeigt werden sollen.';
$lang['length_news'] = 'Anzahl der Zeichen vom Nachrichtentitel';
$lang['length_news_explain'] = 'Maximale Anzahl an Zeichen die angezeigt werden, der Text wird bei Überschreitung gekürzt und mit ... ergänzt.';
$lang['length_match'] = 'Anzahl der Zeichen vom Name der Gegner';
$lang['length_match_explain'] = 'Maximale Anzahl an Zeichen die angezeigt werden, der Text wird bei Überschreitung gekürzt und mit ... ergänzt.';
$lang['limit_match'] = 'Anzahl der Begegnungen';
$lang['limit_match_explain'] = 'Die Zahl angeben, wieviele Begegnungen untereinander angezeigt werden sollen.';

$lang['subnavi_user_cache'] = 'Cachedauer in Sekunden';
$lang['subnavi_user_length'] = 'Anzahl der Zeichen vom Benutzername';
$lang['subnavi_user_limit'] = 'Anzahl an Benutzern';
$lang['subnavi_user_show'] = 'Letzten Neuen Benutzer anzeigen';
$lang['subnavi_teams_show'] = 'Teams anzeigen';
$lang['length_teams'] = 'Teamnamenlänge';
*/
?>