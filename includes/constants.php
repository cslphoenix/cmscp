<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

define('URL', 'url');
define('ANONYMOUS',	1);

/* Teamspeak Server Types */
define('TS2', 0);
define('TS3', 1);

/* Read Types */
define('READ_NEWS',		1);
define('READ_EVENT',	2);
define('READ_MATCH',	3);
define('READ_TRAINING',	4);

/* Match Result */
define('WIN',	'win');
define('LOSE',	'lose');
define('DRAW',	'draw');

define('TYPE_GAME',		0);
define('TYPE_VOICE',	1);
define('TYPE_OTHER',	2);

/* Cash Types */
define('INTERVAL_MONTH',	0);
define('INTERVAL_WEEKS',	1);
define('INTERVAL_WEEKLY',	2);

/* Userlevel */
define('GUEST',		0);
define('USER',		1);
define('TRIAL',		2);
define('MEMBER',	3);
define('MOD',		4);
define('ADMIN',		5);

/* Log Types */
define('LOG_USERS',		1);
define('LOG_MOD',		2);
define('LOG_ADMIN',		3);

/* Log Sektion */
define('SECTION_AUTHLIST',		0);
define('SECTION_COMMENT',		1);
define('SECTION_EVENT',			2);
define('SECTION_FORUM',			3);
define('SECTION_GAMES',			4);
define('SECTION_GROUPS',		6);
define('SECTION_LOG',			7);
define('SECTION_LOGIN',			8);
define('SECTION_MATCH',			9);
define('SECTION_NAVI',			10);
define('SECTION_NETWORK',		11);
define('SECTION_NEWS',			12);
define('SECTION_NEWSCAT',		13);
define('SECTION_NEWSLETTER',	14);
define('SECTION_PROFILE',		15);
define('SECTION_RANK',			16);
define('SECTION_TEAM',			17);
define('SECTION_TEAMSPEAK',		18);
define('SECTION_TRAINING',		19);
define('SECTION_USER',			20);
define('SECTION_CASH',			21);
define('SECTION_GALLERY',		22);
define('SECTION_MAPS',			23);
define('SECTION_SERVER',		24);
define('SECTION_DOWNLOADS',		25);
define('SECTION_CALENDAR',		100);
define('SECTION_USERS',			200);
define('SECTION_VOTES',			300);

define('SECTION_CHANGELOG',		998);
define('SECTION_BUGTRACKER',	999);

/* Page Numbers for session handling */
define('PAGE_INDEX',		0);
define('PAGE_ADMIN',		-1);
define('PAGE_LOGIN',		-2);
define('PAGE_REGISTER',		-3);
define('PAGE_PROFILE',		-4);
define('PAGE_POSTING',		-5);
define('PAGE_GROUPS',		-6);
define('PAGE_MATCH',		-11);
define('PAGE_TRAINING',		-12);
define('PAGE_CONTACT',		-13);
define('PAGE_FORUM',		-14);
define('PAGE_TEAMSPEAK',	-15);
define('PAGE_TEAMS',		-17);
define('PAGE_NEWSLETTER',	-18);
define('PAGE_UCP',			-19);
define('PAGE_RSS',			-20);
define('PAGE_EVENT',		-21);
define('PAGE_CASH',			-22);
define('PAGE_GALLERY',		-23);
define('PAGE_IMPRINT',		-24);
define('PAGE_NEWS',			1);
define('PAGE_CALENDAR',		100);
define('PAGE_USERS',		200);
define('PAGE_SERVER',		300);

define('PAGE_BUGTRACKER',	-98);
define('PAGE_CHANGELOG',	-99);


/* Navi Types */
define('NAVI_MAIN',		1);
define('NAVI_CLAN',		2);
define('NAVI_COM',		3);
define('NAVI_MISC',		4);
define('NAVI_USER',		5);

/* Rank Types */
define('RANK_PAGE',		1);
define('RANK_FORUM',	2);
define('RANK_TEAM',		3);

/* Network Types */
define('NETWORK_LINK',		1);
define('NETWORK_PARTNER',	2);
define('NETWORK_SPONSOR',	3);

/* Rank Types */
define('CONTACT_NORMAL',	0);
define('CONTACT_FIGHTUS',	1);
define('CONTACT_JOINUS',	2);


//	Benutzeraccount aktivierung
define('USER_ACTIVATION_NONE',	0);
define('USER_ACTIVATION_SELF',	1);
define('USER_ACTIVATION_ADMIN',	2);

//	Gruppeneinstellungen
define('GROUP_OPEN',	0);
define('GROUP_REQUEST',	1);
define('GROUP_CLOSED',	2);
define('GROUP_HIDDEN',	3);
define('GROUP_SYSTEM',	4);


//	Auth settings
define('AUTH_LIST_ALL',	0);
define('AUTH_ALL',		0);
define('AUTH_REG',		1);//	Benutzer
define('AUTH_TRI',		2);//	Trial
define('AUTH_MEM',		3);//	Member
define('AUTH_MOD',		4);//	Moderatoren
define('AUTH_ACL',		5);//	Privat
define('AUTH_ADM',		6);//	Administratoren

define('AUTH_VIEW',				1);//	sehen
define('AUTH_READ',				2);//	lesen
define('AUTH_POST',				3);//	schreiben
define('AUTH_REPLY',			4);//	antworten
define('AUTH_EDIT',				5);//	bearbeiten
define('AUTH_DELETE',			6);//	löschen
define('AUTH_ANNOUNCE',			7);//	Ankündigungen
define('AUTH_GLOBALANNOUNCE',	8);//	Ankündigungen
define('AUTH_STICKY',			9);//	Wichtig
define('AUTH_POLLCREATE',		10);//	Umfrage erstellen
define('AUTH_POLL',				11);//	an Umfrage teilnehmen

define('AUTH_DISALLOWED',	0);//	nicht erlaubt
define('AUTH_ALLOWED',		1);//	erlaubt
define('AUTH_SPECIAL',		2);//	Special für Benutzer
define('AUTH_DEFAULT',		3);//	Vorgabe Einstellung

define('AUTH_GUEST',		0);//	Gast
define('AUTH_USER',			1);//	Benutzer
define('AUTH_TRIAL',		2);//	Trialmember
define('AUTH_MEMBER',		3);//	Member
define('AUTH_COLEADER',		4);//	Squadleader
define('AUTH_LEADER',		5);//	Leader
define('AUTH_UPLOADER',		6);//	Uploader

//	Newsletter Status
define('NL_ADD',		0);
define('NL_CONFIRM',	1);
define('NL_DELETE',		2);

//	Logo Upload
define('LOGO_NONE',		0);
define('LOGO_UPLOAD',	1);
define('LOGO_REMOTE',	2);

/* Topic Types */
define('POST_NORMAL',	0);
define('POST_STICKY',	1);
define('POST_ANNOUNCE',	2);
define('POST_GLOBAL',	3);

//	SQL codes
define('BEGIN_TRANSACTION',	1);
define('END_TRANSACTION',	2);

//	Error codes
define('GENERAL_MESSAGE',	200);
define('GENERAL_ERROR',		202);
define('CRITICAL_MESSAGE',	203);
define('CRITICAL_ERROR',	204);

//	Private messaging
define('PRIVMSGS_READ_MAIL',		0);
define('PRIVMSGS_NEW_MAIL',			1);
define('PRIVMSGS_SENT_MAIL',		2);
define('PRIVMSGS_SAVED_IN_MAIL',	3);
define('PRIVMSGS_SAVED_OUT_MAIL',	4);
define('PRIVMSGS_UNREAD_MAIL',		5);

//	Session parameters
define('SESSION_METHOD_COOKIE',		100);
define('SESSION_METHOD_GET',		101);

define('PROFILE_TEXT',		0);
define('PROFILE_TEXTAREA',	1);
define('PROFILE_NUMMERIC',	2);
define('PROFILE_EMAIL',		3);

/* Page Numbers for session handling */
/*
define('PAGE_INDEX',		0);
define('PAGE_ADMIN',		-1);
define('PAGE_LOGIN',		-2);
define('PAGE_REGISTER',		-3);
define('PAGE_PROFILE',		-4);
define('PAGE_POSTING',		-5);
define('PAGE_GROUPS',		-6);
define('PAGE_MATCH',		-11);
define('PAGE_TRAINING',		-12);
define('PAGE_CONTACT',		-13);
define('PAGE_FORUM',		-14);
define('PAGE_TEAMSPEAK',	-15);
define('PAGE_TEAMS',		-17);
define('PAGE_NEWSLETTER',	-18);
define('PAGE_UCP',			-19);
define('PAGE_RSS',			-20);
define('PAGE_EVENT',		-21);
define('PAGE_CASH',			-22);
define('PAGE_GALLERY',		-23);
define('PAGE_IMPRINT',		-24);
define('PAGE_NEWS',			1);
define('PAGE_CALENDAR',		100);

define('PAGE_BUGTRACKER',	-98);
define('PAGE_CHANGELOG',	-99);
*/

/* URL PARAMETERS */
/* global */
define('POST_CAT',			'cat');
define('POST_PIC',			'pic');
/* special */
define('POST_GAMES',		'games');
define('POST_GALLERY',		'gallery');
define('POST_GROUPS',		'group');
define('POST_NEWS',			'news');
define('POST_EVENT',		'event');

define('POST_AUTHLIST',		'al');
define('POST_CATEGORY',		'c');
define('POST_CONTACT',		'c');
define('POST_FORUM',		'f');
define('POST_FORUM_CAT',	'fc');
define('POST_FORUM_SUB',	'fs');
define('POST_CALENDAR',		'cal');

define('POST_SERVER',		's');
define('POST_LOGS',			'l');
define('POST_MATCH',		'm');
define('POST_MATCH_PIC',	'mp');
define('POST_NAVI',			'n');
define('POST_NETWORK',		'n');
define('POST_NEWSLETTER',	'nl');
define('POST_POST',			'p');
define('POST_PROFILE',		'p');
define('POST_RANKS',		'r');
define('POST_TEAMS',		't');
define('POST_TEAMSPEAK',	't');
define('POST_TOPIC',		't');
define('POST_TRAINING',		'tr');
define('POST_USER',			'u');
define('POST_CASH',			'c');
define('POST_CASH_USER',	'cu');
define('POST_USERS',		'user');
define('POST_BUGTRACKER',	'bt');
define('POST_CHANGELOGS',	'cl');

define('POST_VOTES',		'vote');

define('POST_DOWNLOAD',		'd');
define('POST_DOWNLOAD_CAT',	'cat');
define('POST_MAPS',			'm');
define('POST_MAPS_CAT',		'mc');

//	Forum Status
define('FORUM_UNLOCKED',	0);
define('FORUM_LOCKED',		1);

/* Spieler Status / Teilnahme Status */
define('STATUS_NONE',		0);
define('STATUS_YES',		1);
define('STATUS_NO',			2);
define('STATUS_REPLACE',	3);

/* Tabellennamen */
define('AUTHLIST',			$db_prefix . 'authlist');
define('BANLIST',			$db_prefix . 'banlist');
define('CAPTCHA',			$db_prefix . 'captcha');
define('CASH',				$db_prefix . 'cash');
define('CASH_BANK',			$db_prefix . 'cash_bank');
define('CASH_USER',			$db_prefix . 'cash_users');
define('COMMENT',			$db_prefix . 'comment');
define('COMMENT_READ',		$db_prefix . 'comment_read');
define('CONFIG',			$db_prefix . 'config');
define('CONTACT',			$db_prefix . 'contact');
define('COUNTER_COUNTER',	$db_prefix . 'counter_counter');
define('COUNTER_ONLINE',	$db_prefix . 'counter_online');
define('DISALLOW',			$db_prefix . 'disallow');
define('DOWNLOAD',			$db_prefix . 'downloads');
define('DOWNLOAD_CAT',		$db_prefix . 'downloads_cat');
define('EVENT',				$db_prefix . 'event');
define('EVENT_USERS',		$db_prefix . 'event_users');
define('AUTH_ACCESS',		$db_prefix . 'forum_auth_access');
define('FORUM_CAT',			$db_prefix . 'forum_cat');
define('FORUM',				$db_prefix . 'forum_forums');
define('POSTS',				$db_prefix . 'forum_posts');
define('TOPICS',			$db_prefix . 'forum_topics');
define('TOPICS_READ',		$db_prefix . 'forum_topics_read');
define('GALLERY',			$db_prefix . 'gallery');
define('GALLERY_PIC',		$db_prefix . 'gallery_pic');
define('GALLERY_SETTINGS',	$db_prefix . 'gallery_settings');
define('GAMES',				$db_prefix . 'game');
define('GROUPS',			$db_prefix . 'groups');
define('GROUPS_USERS',		$db_prefix . 'groups_users');
define('LOGS',				$db_prefix . 'log');
define('ERROR',				$db_prefix . 'log_error');
define('MAPS',				$db_prefix . 'maps');
define('MAPS_CAT',			$db_prefix . 'maps_cat');
define('MATCH',				$db_prefix . 'match');
define('MATCH_LINEUP',		$db_prefix . 'match_lineup');
define('MATCH_USERS',		$db_prefix . 'match_users');
define('MATCH_MAPS',		$db_prefix . 'match_maps');
define('NAVI',				$db_prefix . 'navi');	
define('NETWORK',			$db_prefix . 'network');
define('NEWS',				$db_prefix . 'news');
define('NEWSCAT',			$db_prefix . 'newscat');
define('NEWSLETTER',		$db_prefix . 'newsletter');
define('PRIVMSGS',			$db_prefix . 'privmsgs');
define('PROFILE',			$db_prefix . 'profile');
define('PROFILE_CAT',		$db_prefix . 'profile_cat');
define('PROFILE_DATA',		$db_prefix . 'profile_data');
define('RANKS',				$db_prefix . 'ranks');
#define('RATE',				$db_prefix . 'rate');
define('SERVER',			$db_prefix . 'server');
define('SERVER_TYPE',		$db_prefix . 'server_types');
define('SESSIONS',			$db_prefix . 'sessions');
define('SESSIONS_KEYS',		$db_prefix . 'sessions_keys');
define('SETTINGS',			$db_prefix . 'settings');
define('SORT',				$db_prefix . 'sort');
define('TEAMS',				$db_prefix . 'teams');
define('TEAMS_USERS',		$db_prefix . 'teams_users');
define('TEAMSPEAK',			$db_prefix . 'teamspeak');
define('THEMES',			$db_prefix . 'themes');
define('THEMES_NAME',		$db_prefix . 'themes_name');
define('TRAINING',			$db_prefix . 'training');
define('TRAINING_USERS',	$db_prefix . 'training_users');
define('USERS',				$db_prefix . 'users');
define('VOTES',				$db_prefix . 'vote');
define('VOTES_OPTION',		$db_prefix . 'vote_option');
define('VOTES_USERS',		$db_prefix . 'vote_users');

/* only main site */
define('BUGTRACKER',		$db_prefix . 'bugtracker');
define('CHANGELOG',			$db_prefix . 'changelog');						

?>