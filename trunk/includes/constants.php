<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

// User Levels <- Do not change the values of USER or ADMIN
define('DELETED',	-1);
define('ANONYMOUS',	1);

//	Userlevel
define('ANONYMOUS',	0);
define('GUEST',		0);
define('USER',		1);
define('TRIAL',		2);
define('MEMBER',	3);
define('MOD',		4);
define('ADMIN',		5);

//	Log types
//	- wo gespeichert wird, Admin, Moderation oder Benutzerbereich
define('LOG_USERS',		1);
define('LOG_MOD',		2);
define('LOG_ADMIN',		3);

//	Log Sektion
//	- Unterteilung im Admin, Moderations oder Benutzerbreich
define('LOG_SEK_AUTHLIST',		0);
define('LOG_SEK_COMMENT',		1);
define('LOG_SEK_EVENT',			2);
define('LOG_SEK_FORUM',			3);
define('LOG_SEK_GAME',			4);
define('LOG_SEK_GAMESERVER',	5);
define('LOG_SEK_GROUPS',		6);
define('LOG_SEK_LOG',			7);
define('LOG_SEK_LOGIN',			8);
define('LOG_SEK_MATCH',			9);
define('LOG_SEK_NAVI',			10);
define('LOG_SEK_NETWORK',		11);
define('LOG_SEK_NEWS',			12);
define('LOG_SEK_NEWSCAT',		13);
define('LOG_SEK_NEWSLETTER',	14);
define('LOG_SEK_PROFILE',		15);
define('LOG_SEK_RANK',			16);
define('LOG_SEK_TEAM',			17);
define('LOG_SEK_TEAMSPEAK',		18);
define('LOG_SEK_TRAINING',		19);
define('LOG_SEK_USER',			20);

define('LOG_SEK_CASH',			21);
define('LOG_SEK_GALLERY',		22);

define('LOG_SEK_CHANGELOG',		98);
define('LOG_SEK_BUGTRACKER',	99);

//	Navi types
define('NAVI_MAIN',		1);
define('NAVI_CLAN',		2);
define('NAVI_COM',		3);
define('NAVI_MISC',		4);
define('NAVI_USER',		5);

//	Rank types
define('RANK_PAGE',		1);
define('RANK_FORUM',	2);
define('RANK_TEAM',		3);

//	Network types
define('NETWORK_LINK',		1);
define('NETWORK_PARTNER',	2);
define('NETWORK_SPONSOR',	3);

//	Rank types
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

// Forum state
define('FORUM_UNLOCKED', 0);
define('FORUM_LOCKED', 1);

//	Auth settings
define('AUTH_LIST_ALL',	0);
define('AUTH_ALL',		0);
define('AUTH_REG',		1);//	Benutzer
define('AUTH_TRI',		2);//	Trial
define('AUTH_MEM',		3);//	Member
define('AUTH_MOD',		4);//	Moderatoren
define('AUTH_ACL',		5);//	Privat
define('AUTH_ADM',		6);//	Administratoren

define('AUTH_VIEW',			1);//	sehen
define('AUTH_READ',			2);//	lesen
define('AUTH_POST',			3);//	schreiben
define('AUTH_REPLY',		4);//	antworten
define('AUTH_EDIT',			5);//	bearbeiten
define('AUTH_DELETE',		6);//	löschen
define('AUTH_ANNOUNCE',		7);//	Ankündigungen
define('AUTH_STICKY',		8);//	Wichtig
define('AUTH_POLLCREATE',	9);//	Umfrage erstellen
define('AUTH_POLL',			10);//	an Umfrage teilnehmen

define('AUTH_DISALLOWED',	0);//	nicht erlaubt
define('AUTH_ALLOWED',		1);//	erlaubt
define('AUTH_SPECIAL',		2);//	Special für Benutzer
define('AUTH_DEFAULT',		3);//	Vorgabe Einstellung

define('AUTH_GUEST',		0);//	Gäste
define('AUTH_USER',			1);//	Benutzer
define('AUTH_TRIAL',		2);//	Trialmember
define('AUTH_MEMBER',		3);//	Member
define('AUTH_COLEADER',		4);//	Squadleader
define('AUTH_LEADER',		5);//	Leader
define('AUTH_UPLOAD',		6);//	Uploader

define('URL',	'url');

//	Newsletter Status
define('NL_ADD',		0);
define('NL_CONFIRM',	1);
define('NL_DELETE',		2);


//	Logo Upload
define('LOGO_NONE',		0);
define('LOGO_UPLOAD',	1);
define('LOGO_REMOTE',	2);

// Topic types
define('POST_NORMAL', 0);
define('POST_STICKY', 1);
define('POST_ANNOUNCE', 2);

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

//	Page numbers for session handling
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
define('PAGE_CALENDAR',		-16);
define('PAGE_TEAMS',		-17);
define('PAGE_NEWSLETTER',	-18);
define('PAGE_UCP',			-19);
define('PAGE_RSS',			-20);
define('PAGE_EVENT',		-21);

define('PAGE_CASH',			-22);
define('PAGE_GALLERY',		-23);
define('PAGE_IMPRINT',		-24);


define('PAGE_BUGTRACKER',	-98);
define('PAGE_CHANGELOG',	-99);
//define('PAGE_TOPIC_OFFSET',	5000);


//	URL PARAMETERS
define('POST_AUTHLIST_URL',		'al');
define('POST_CATEGORY_URL',		'c');
define('POST_CONTACT_URL',		'c');
define('POST_FORUM_URL',		'f');
define('POST_GAMES_URL',		'g');
define('POST_GAMESERVER_URL',	'gs');
define('POST_GROUPS_URL',		'g');
define('POST_GALLERY_URL',		'g');
define('POST_GALLERY_PIC_URL',	'gp');
define('POST_LOG_URL',			'l');
define('POST_MATCH_URL',		'm');
define('POST_MATCH_PIC_URL',	'mp');
define('POST_NAVIGATION_URL',	'n');
define('POST_NETWORK_URL',		'n');
define('POST_NEWS_URL',			'n');
define('POST_NEWSCAT_URL',		'nc');
define('POST_NEWSLETTER_URL',	'nl');
define('POST_POST_URL',			'p');
define('POST_PROFILE_URL',		'p');
define('POST_RANKS_URL',		'r');
define('POST_TEAMS_URL',		't');
define('POST_TEAMSPEAK_URL',	't');
define('POST_TOPIC_URL',		't');
define('POST_TRAINING_URL',		'tr');
define('POST_USERS_URL',		'u');
define('POST_EVENT_URL',		'e');
define('POST_CASH_URL',			'c');
define('POST_CASH_USERS_URL',	'cu');

define('POST_BUGTRACKER_URL',	'bt');
define('POST_CHANGELOG_URL',	'cl');

define('POST_DOWNLOAD_URL',		'd');
define('POST_DOWNLOAD_CAT_URL',	'cat');

//	Forum Status
define('FORUM_UNLOCKED',	0);
define('FORUM_LOCKED',		1);

//	Spieler Status
define('STATUS_NONE',		0);
define('STATUS_YES',		1);
define('STATUS_NO',			2);
define('STATUS_REPLACE',	3);

//
//	Table names
//
//	Config und Einstellungen
define('CONFIG',					$db_prefix . 'config');
define('SETTINGS',					$db_prefix . 'settings');

//	sonstiges ;)
define('AUTHLIST',					$db_prefix . 'authlist');
define('BANLIST',					$db_prefix . 'banlist');
define('CONTACT',					$db_prefix . 'contact');
define('DISALLOW',					$db_prefix . 'disallow');
define('EVENT',						$db_prefix . 'event');
define('NAVIGATION',				$db_prefix . 'navigation');	
define('NETWORK',					$db_prefix . 'network');
define('RANKS',						$db_prefix . 'ranks');

//	Session
define('SESSIONS',					$db_prefix . 'sessions');
define('SESSIONS_KEYS',				$db_prefix . 'sessions_keys');

//	Template
define('THEMES',					$db_prefix . 'themes');
define('THEMES_NAME',				$db_prefix . 'themes_name');

//	Benutzer
define('USERS',						$db_prefix . 'users');

//	Galerie

define('GALLERY',					$db_prefix . 'gallery');
define('GALLERY_PIC',				$db_prefix . 'gallery_pic');
define('GALLERY_SETTINGS',			$db_prefix . 'gallery_settings');

//	Gruppen
define('GROUPS',					$db_prefix . 'groups');
define('GROUPS_USERS',				$db_prefix . 'groups_users');

//	Profilefelder
define('PROFILE',					$db_prefix . 'profile');
define('PROFILE_CATEGORY',			$db_prefix . 'profile_category');
define('PROFILE_DATA',				$db_prefix . 'profile_data');

//	News
define('NEWS',						$db_prefix . 'news');
define('NEWSCAT',					$db_prefix . 'newscategory');
define('NEWS_COMMENTS',				$db_prefix . 'news_comments');
define('NEWS_COMMENTS_READ',		$db_prefix . 'news_comments_read');

//	Team + Games(Spiele)
define('GAMES',						$db_prefix . 'game');
define('TEAMS',						$db_prefix . 'teams');
define('TEAMS_USERS',				$db_prefix . 'teams_users');

//	Logs
define('LOGS',						$db_prefix . 'log');
define('ERROR',						$db_prefix . 'log_error');

//	Forum
define('AUTH_ACCESS',				$db_prefix . 'forum_auth_access');
define('CATEGORIES',				$db_prefix . 'forum_cat');
define('FORUMS',					$db_prefix . 'forum_forums');
define('POSTS',						$db_prefix . 'forum_posts');
define('TOPICS',					$db_prefix . 'forum_topics');
define('TOPICS_READ',				$db_prefix . 'forum_topics_read');

define('PRIVMSGS',					$db_prefix . 'privmsgs');

//	Counter
define('COUNTER_COUNTER',			$db_prefix . 'counter_counter');
define('COUNTER_ONLINE',			$db_prefix . 'counter_online');

//	Server
define('SERVER',					$db_prefix . 'server');
define('TEAMSPEAK',					$db_prefix . 'teamspeak');
define('NEWSLETTER',				$db_prefix . 'newsletter');
define('CHANGELOG',					$db_prefix . 'changelog');

define('BUGTRACKER',				$db_prefix . 'bugtracker');
define('BUGTRACKER_COMMENTS',		$db_prefix . 'bugtracker_comments');
define('BUGTRACKER_COMMENTS_READ',	$db_prefix . 'bugtracker_comments_read');


//	Wars/Match
define('MATCH',						$db_prefix . 'match');
define('MATCH_COMMENTS',			$db_prefix . 'match_comments');
define('MATCH_COMMENTS_READ',		$db_prefix . 'match_comments_read');
define('MATCH_DETAILS',				$db_prefix . 'match_details');
define('MATCH_LINEUP',				$db_prefix . 'match_lineup');
define('MATCH_USERS',				$db_prefix . 'match_users');
define('MATCH_MAPS',				$db_prefix . 'match_maps');

//	Training
define('TRAINING',					$db_prefix . 'training');
define('TRAINING_COMMENTS',			$db_prefix . 'training_comments');
define('TRAINING_COMMENTS_READ',	$db_prefix . 'training_comments_read');
define('TRAINING_USERS',			$db_prefix . 'training_users');


define('CASH',						$db_prefix . 'cash');
define('CASH_BANK',					$db_prefix . 'cash_bank');
define('CASH_USERS',				$db_prefix . 'cash_users');

/*	Downloads	*/
define('DOWNLOAD',					$db_prefix . 'downloads');
define('DOWNLOAD_CAT',				$db_prefix . 'downloads_cat');


?>