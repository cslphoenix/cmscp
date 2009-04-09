<?php

if ( !defined('IN_CMS') )
{
	die("Hacking attempt");
}

// User Levels <- Do not change the values of USER or ADMIN
define('DELETED',	-1);
define('ANONYMOUS',	-1);

//	Userlevel
define('GUEST',		0);
define('USER',		1);
define('TRIAL',		2);
define('MEMBER',	3);
define('MOD',		4);
define('ADMIN',		5);

//	Log types
//	- wo gespeichert wird, Admin, Moderation oder Benutzerbereich
define('LOG_ADMIN',		0);
define('LOG_MOD',		1);
define('LOG_USERS',		2);

//	Log Sektion
//	- Unterteilung im Admin, Moderations oder Benutzerbreich
define('LOG_SEK_NEWS',		0);
define('LOG_SEK_TEAM',		1);
define('LOG_SEK_RANK',		2);
define('LOG_SEK_USER',		3);
define('LOG_SEK_GAME',		4);
define('LOG_SEK_MATCH',		5);
define('LOG_SEK_TRAINING',	6);
define('LOG_SEK_LOGIN',		7);
define('LOG_SEK_NAVI',		8);
define('LOG_SEK_FORUM',		9);
define('LOG_SEK_COMMENT',	21);

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

//	Rank types
define('CONTACT_NORMAL',	0);
define('CONTACT_FIGHTUS',	1);
define('CONTACT_JOINUS',	2);

//	Server types
define('SERVER_GAME',	1);
define('SERVER_VOICE',	2);

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
define('AUTH_REG',		1);		//	Benutzer
define('AUTH_TRI',		2);		//	Trial
define('AUTH_MEM',		3);		//	Member
define('AUTH_MOD',		4);		//	Moderatoren
define('AUTH_ACL',		5);		//	Privat
define('AUTH_ADM',		6);		//	Administratoren

define('AUTH_VIEW',			1);		//	sehen
define('AUTH_READ',			2);		//	lesen
define('AUTH_POST',			3);		//	schreiben
define('AUTH_REPLY',		4);		//	antworten
define('AUTH_EDIT',			5);		//	bearbeiten
define('AUTH_DELETE',		6);		//	löschen
define('AUTH_ANNOUNCE',		7);		//	Ankündigungen
define('AUTH_STICKY',		8);		//	Wichtig
define('AUTH_POLLCREATE',	9);		//	Umfrage erstellen
define('AUTH_POLL',			10);	//	an Umfrage teilnehmen

define('AUTH_DISALLOWED',	0);		//	nicht erlaubt
define('AUTH_ALLOWED',		1);		//	erlaubt
define('AUTH_SPECIAL',		2);		//	Special für Benutzer
define('AUTH_DEFAULT',		3);		//	Vorgabe Einstellung

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


//	Page numbers for session handling
define('PAGE_INDEX',		0);
define('PAGE_ADMIN',		-1);
define('PAGE_LOGIN',		-2);
define('PAGE_REGISTER',		-3);
define('PAGE_PROFILE',		-4);
define('PAGE_POSTING',		-5);
define('PAGE_GROUPS',		-6);
//define('PAGE_TOPIC_OFFSET',	5000);
define('PAGE_MATCH',		-11);
define('PAGE_TRAINING',		-12);
define('PAGE_CONTACT',		-13);
define('PAGE_FORUM',		-14);
define('PAGE_TEAMSPEAK',	-15);
define('PAGE_CALENDAR',		-16);
define('PAGE_TEAM',			-17);

//	URL PARAMETERS
define('POST_TEAMS_URL',		't');
define('POST_RANKS_URL',		'r');
define('POST_USERS_URL',		'u');
define('POST_LOG_URL',			'l');
define('POST_GAMES_URL',		'g');
define('POST_GROUPS_URL',		'g');
define('POST_MATCH_URL',		'm');
define('POST_TRAINING_URL',		'tr');
define('POST_SERVER_URL',		's');
define('POST_GROUPS_URL',		'g');
define('POST_CONTACT_URL',		'c');
define('POST_NAVIGATION_URL',	'n');
define('POST_NEWS_URL',			'n');
define('POST_NEWSCAT_URL',		'nc');

//	fürs Forum
define('POST_POST_URL',			'p');
define('POST_TOPIC_URL',		't');
define('POST_CAT_URL',			'c');
define('POST_FORUM_URL',		'f');

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
define('CONFIG_TABLE',			$db_prefix.'config');
define('SETTINGS_TABLE',		$db_prefix.'settings');

//	sonstiges ;)

define('BANLIST_TABLE',			$db_prefix.'banlist');
define('CONTACT_TABLE',			$db_prefix.'contact');
define('DISALLOW_TABLE',		$db_prefix.'disallow');
define('EVENTS_TABLE',			$db_prefix.'events');
define('NAVIGATION_TABLE',		$db_prefix.'navigation');
define('RANKS_TABLE',			$db_prefix.'ranks');

//	Session
define('SESSIONS_TABLE',		$db_prefix.'sessions');
define('SESSIONS_KEYS_TABLE',	$db_prefix.'sessions_keys');

//	Template
define('THEMES_TABLE',			$db_prefix.'themes');
define('THEMES_NAME_TABLE',		$db_prefix.'themes_name');

//	Benutzer
define('USERS_TABLE',			$db_prefix.'users');
define('USERS_AUTH_TABLE',		$db_prefix.'users_auth');

//	Gruppen
define('GROUPS_TABLE',			$db_prefix.'groups');
define('GROUPS_TEST_TABLE',		$db_prefix.'groups_test');
define('GROUPS_AUTH_TABLE',		$db_prefix.'groups_auth');
define('GROUPS_USER_TABLE',		$db_prefix.'groups_user');

//	News
define('NEWS_TABLE',				$db_prefix.'news');
define('NEWS_CATEGORY_TABLE',		$db_prefix.'news_category');
define('NEWS_COMMENTS_TABLE',		$db_prefix.'news_comments');
define('NEWS_COMMENTS_READ_TABLE',	$db_prefix.'news_comments_read');

//	Team + Games(Spiele)
define('GAMES_TABLE',			$db_prefix.'game');
define('TEAMS_TABLE',			$db_prefix.'teams');
define('TEAMS_USERS_TABLE',		$db_prefix.'teams_users');

//	Logs
define('LOG_TABLE',				$db_prefix.'log');
define('ERROR_TABLE',			$db_prefix.'log_error');

//	Forum
define('AUTH_ACCESS_TABLE',		$db_prefix.'forum_auth_access');
define('CATEGORIES_TABLE',		$db_prefix.'forum_cat');
define('FORUMS_TABLE',			$db_prefix.'forum_forums');
define('POSTS_TABLE',			$db_prefix.'forum_posts');
define('TOPICS_TABLE',			$db_prefix.'forum_topics');
define('TOPICS_READ_TABLE',		$db_prefix.'forum_topics_read');

//	Counter
define('COUNTER_COUNTER_TABLE',	$db_prefix.'counter_counter');
define('COUNTER_ONLINE_TABLE',	$db_prefix.'counter_online');

//	Server
define('SERVER_TABLE',			$db_prefix.'server');
define('TEAMSPEAK_TABLE',		$db_prefix.'teamspeak');

//	Wars/Match
define('MATCH_TABLE',					$db_prefix.'match');
define('MATCH_COMMENTS_TABLE',			$db_prefix.'match_comments');
define('MATCH_COMMENTS_READ_TABLE',		$db_prefix.'match_comments_read');
define('MATCH_DETAILS_TABLE',			$db_prefix.'match_details');
define('MATCH_LINEUP_TABLE',			$db_prefix.'match_lineup');
define('MATCH_USERS_TABLE',				$db_prefix.'match_users');

//	Training
define('TRAINING_TABLE',				$db_prefix.'training');
define('TRAINING_COMMENTS_TABLE',		$db_prefix.'training_comments');
define('TRAINING_COMMENTS_READ_TABLE',	$db_prefix.'training_comments_read');
define('TRAINING_USERS_TABLE',			$db_prefix.'training_users');

?>