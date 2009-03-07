<?php

if ( !defined('IN_CMS') )
{
	die("Hacking attempt");
}

// User Levels <- Do not change the values of USER or ADMIN
define('DELETED', -1);
define('ANONYMOUS', -1);

define('GUEST',		0);
define('USER',		1);
define('TRAIL',		2);
define('MEMBER',	3);
define('ADMIN',		4);

// Log types
define('LOG_ADMIN',		0);
define('LOG_MOD',		1);
define('LOG_USERS',		2);

// Log Sektion
define('LOG_SEK_NEWS',		0);
define('LOG_SEK_TEAM',		1);
define('LOG_SEK_RANK',		2);
define('LOG_SEK_USER',		3);
define('LOG_SEK_GAME',		4);
define('LOG_SEK_MATCH',		5);
define('LOG_SEK_TRAINING',	6);
define('LOG_SEK_LOGIN',		7);


define('LOG_SEK_COMMENT',	21);

define('NAVI_MAIN',		1);
define('NAVI_CLAN',		2);
define('NAVI_COM',		3);
define('NAVI_MISC',		4);
define('NAVI_USER',		5);


// Rank types
define('RANK_PAGE',		1);
define('RANK_FORUM',	2);
define('RANK_TEAM',		3);

// Server types
define('SERVER_GAME',	1);	// Game-Server
define('SERVER_VOICE',	2);	// Voice-Server



// User related
define('USER_ACTIVATION_NONE', 0);
define('USER_ACTIVATION_SELF', 1);
define('USER_ACTIVATION_ADMIN', 2);


define('LOGO_NONE', 0);
define('LOGO_UPLOAD', 1);
define('LOGO_REMOTE', 2);

// SQL codes
define('BEGIN_TRANSACTION', 1);
define('END_TRANSACTION', 2);


// Error codes
define('GENERAL_MESSAGE', 200);
define('GENERAL_ERROR', 202);
define('CRITICAL_MESSAGE', 203);
define('CRITICAL_ERROR', 204);


// Private messaging
define('PRIVMSGS_READ_MAIL', 0);
define('PRIVMSGS_NEW_MAIL', 1);
define('PRIVMSGS_SENT_MAIL', 2);
define('PRIVMSGS_SAVED_IN_MAIL', 3);
define('PRIVMSGS_SAVED_OUT_MAIL', 4);
define('PRIVMSGS_UNREAD_MAIL', 5);


// Session parameters
define('SESSION_METHOD_COOKIE', 100);
define('SESSION_METHOD_GET', 101);


// Page numbers for session handling
define('PAGE_INDEX',		0);
define('PAGE_ADMIN',		-1);
define('PAGE_LOGIN',		-2);
define('PAGE_REGISTER',		-3);
define('PAGE_PROFILE',		-4);
define('PAGE_TOPIC_OFFSET',	5000);

define('PAGE_MATCH',		-11);
define('PAGE_TRAINING',		-12);
define('PAGE_CONTACT',		-13);
define('PAGE_FORUM',		-14);
define('PAGE_TEAMSPEAK',	-15);

// URL PARAMETERS
define('POST_TEAMS_URL',	't');
define('POST_RANKS_URL',	'r');
define('POST_USERS_URL',	'u');
define('POST_LOG_URL',		'l');
define('POST_GAMES_URL',	'g');
define('POST_MATCH_URL',	'm');
define('POST_TRAINING_URL',	'tr');
define('POST_SERVER_URL',	's');

define('POST_TOPIC_URL', 't');
define('POST_CAT_URL', 'c');
define('POST_FORUM_URL', 'f');

// Forum state
define('FORUM_UNLOCKED', 0);
define('FORUM_LOCKED', 1);


define('STATUS_NONE',		0);
define('STATUS_YES',		1);
define('STATUS_NO',			2);
define('STATUS_REPLACE',	3);


// Table names
define('CONFIG_TABLE',			$db_prefix.'config');
define('SETTINGS_TABLE',		$db_prefix.'settings');

define('BANLIST_TABLE',			$db_prefix.'banlist');
define('DISALLOW_TABLE',		$db_prefix.'disallow');

define('RANKS_TABLE',			$db_prefix.'ranks');
define('GAMES_TABLE',			$db_prefix.'game');

define('SESSIONS_TABLE',		$db_prefix.'sessions');
define('SESSIONS_KEYS_TABLE',	$db_prefix.'sessions_keys');

define('THEMES_TABLE',			$db_prefix.'themes');
define('THEMES_NAME_TABLE',		$db_prefix.'themes_name');

define('USERS_TABLE',			$db_prefix.'users');
define('USERS_AUTH_TABLE',		$db_prefix.'users_auth');
define('USER_GROUP_TABLE',		$db_prefix.'user_group');

define('NEWS_TABLE',			$db_prefix.'news');
define('NEWS_CATEGORIE_TABLE',	$db_prefix.'news_categorie');
define('NEWS_COMMENTS_TABLE',	$db_prefix.'news_comments');

define('TEAMS_TABLE',			$db_prefix.'teams');
define('TEAMS_USERS_TABLE',		$db_prefix.'teams_users');

define('MATCH_TABLE',			$db_prefix.'match');
define('MATCH_COMMENTS_TABLE',	$db_prefix.'match_comments');
define('MATCH_DETAILS_TABLE',	$db_prefix.'match_details');
define('MATCH_LINEUP_TABLE',	$db_prefix.'match_lineup');
define('MATCH_USERS_TABLE',		$db_prefix.'match_users');

define('TRAINING_TABLE',		$db_prefix.'training');
define('TRAINING_COM_TABLE',	$db_prefix.'training_comments');
define('TRAINING_USERS_TABLE',	$db_prefix.'training_users');

define('SERVER_TABLE',			$db_prefix.'server');
	   
define('NAVI_TABLE',			$db_prefix.'navi');
define('CONTACT_TABLE',			$db_prefix.'contact');

define('LOG_TABLE',				$db_prefix.'log');
define('ERROR_TABLE',			$db_prefix.'log_error');

define('FORUM_CATEGORIE_TABLE',	$db_prefix.'forum_cat');
define('FORUM_FORUMS_TABLE',	$db_prefix.'forum_forums');

define('COUNTER_COUNTER_TABLE',	$db_prefix.'counter_counter');
define('COUNTER_ONLINE_TABLE',	$db_prefix.'counter_online');

?>