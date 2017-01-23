
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Arrays</title>
</head>

<body style="font-size:11px; font-family:Verdana, Geneva, sans-serif;">

<?php
header('content-type: text/html; charset=ISO-8859-1');
function ary($data, $name = '')
{
$name = ( $name != '' ) ? " :: $name" : '';

$echo = serialize($data);

print "ary $name";
print '<br />';
#	print '<pre style="font-size:10px; font-family:Verdana, Geneva, sans-serif;">';
#	print_r($data);
#	print '</pre>';
print $echo;
print '<br />';
print strlen($echo);
print '<br />';
print '<br />';
}

$ary = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 32), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800),);
ary($ary, 'subnavi_news');

$ary = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 32), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
ary($ary, 'subnavi_last_match');

$ary = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 32), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
ary($ary, 'subnavi_last_topics');

$ary = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 32), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
ary($ary, 'subnavi_downloads');

$ary = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
ary($ary, 'newusers');

$ary = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
ary($ary, 'teams');

$ary = array('show_links' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'show_partner' => array('order' => 20, 'type' => 'radio:yesno', 'value' => 1), 'show_sponsor' => array('order' => 30, 'type' => 'radio:yesno', 'value' => 1), 'cache'  => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time'  => array('order' => 50, 'value' => 1800));
ary($ary, 'network');

$ary = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 60));
ary($ary, 'server');

$ary = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
ary($ary, 'next_match');

$ary = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
ary($ary, 'next_train');



$subnavi_news = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 32), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800),);
$subnavi_last_match = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 32), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
$subnavi_last_topics = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 32), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
$subnavi_downloads = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 32), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
$newusers = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
$teams = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
$network = array('show_links' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'show_partner' => array('order' => 20, 'type' => 'radio:yesno', 'value' => 1), 'show_sponsor' => array('order' => 30, 'type' => 'radio:yesno', 'value' => 1), 'cache'  => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time'  => array('order' => 50, 'value' => 1800));
$server = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 60));
$next_match = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
$next_train = array('show' => array('order' => 10, 'type' => 'radio:yesno', 'value' => 1), 'limit' => array('order' => 20, 'value' => 5), 'length' => array('order' => 30, 'value' => 20), 'cache' => array('order' => 40, 'type' => 'radio:yesno', 'value' => 1), 'time' => array('order' => 50, 'value' => 1800));
ary($next_train, 'next_train');
ary($next_match, 'next_match');
ary($server, 'server');
ary($network, 'network');
ary($teams, 'teams');
ary($newusers, 'newusers');
ary($subnavi_downloads, 'subnavi_downloads');
ary($subnavi_last_topics, 'subnavi_last_topics');
ary($subnavi_last_match, 'subnavi_last_match');
ary($subnavi_news, 'subnavi_news');

$subnavi_news			= array('show' => 1, 'limit' => 5, 'length' => 32, 'cache' => 1, 'time' => 1800,);
$subnavi_last_match		= array('show' => 1, 'limit' => 5, 'length' => 32, 'cache' => 1, 'time' => 1800);
$subnavi_last_topics	= array('show' => 1, 'limit' => 5, 'length' => 32, 'cache' => 1, 'time' => 1800);
$subnavi_downloads		= array('show' => 1, 'limit' => 5, 'length' => 32, 'cache' => 1, 'time' => 1800);
$newusers				= array('show' => 1, 'limit' => 5, 'length' => 20, 'cache' => 1, 'time' => 1800);
$teams					= array('show' => 1, 'limit' => 5, 'length' => 20, 'cache' => 1, 'time' => 1800);
$network				= array('show_links' => 1, 'show_partner' => 1, 'show_sponsor' => 1, 'cache' => 1, 'time' => 1800);
$server					= array('show' => 1, 'limit' => 5, 'length' => 20, 'cache' => 1, 'time' => array('order' => 50, 'value' => 60));
$next_match				= array('show' => 1, 'limit' => 5, 'length' => 20, 'cache' => 1, 'time' => 1800);
$next_train				= array('show' => 1, 'limit' => 5, 'length' => 20, 'cache' => 1, 'time' => 1800);
ary($subnavi_news, 'subnavi_news');
ary($subnavi_last_match, 'subnavi_last_match');
ary($subnavi_last_topics, 'subnavi_last_topics');
ary($subnavi_downloads, 'subnavi_downloads');
ary($newusers, 'newusers');
ary($teams, 'teams');
ary($network, 'network');
ary($server, 'server');
ary($next_match, 'next_match');
ary($next_train, 'next_train');

$games			= array('path' => array('value' => 'images/games/'));
$maps			= array('path' => array('value' => 'images/maps/'));
$newscat		= array('path' => array('value' => 'images/newscat/'));
$ranks			= array('path' => array('value' => 'images/ranks/'));
$downloads		= array('path' => array('value' => 'upload/downloads/'));
$gallery		= array('path' => array('value' => 'upload/gallery/'));
$groups			= array('path' => array('value' => 'upload/groups/'),		'filesize' => array('value' => '1'), 'dimension' => array('type' => 'text:text', 'value' => '100:100'));
$matchs			= array('path' => array('value' => 'upload/matchs/'),		'filesize' => array('value' => '2'), 'dimension' => array('type' => 'text:text', 'value' => '100:100'), 'preview' => array('type' => 'text:text', 'value' => '100:100'));
$path_network	= array('path' => array('value' => 'upload/network/'),		'filesize' => array('value' => '3'), 'dimension' => array('type' => 'text:text', 'value' => '100:100'));
$teams_banner	= array('path' => array('value' => 'upload/teams/'),		'filesize' => array('value' => '4'), 'dimension' => array('type' => 'text:text', 'value' => '100:100'), 'upload' => array('type' => 'radio:yesno', 'value' => '1'));
$teams_logo		= array('path' => array('value' => 'upload/teams/'),		'filesize' => array('value' => '5'), 'dimension' => array('type' => 'text:text', 'value' => '100:100'), 'upload' => array('type' => 'radio:yesno', 'value' => '1'));
$users			= array('path' => array('value' => 'upload/users/'),		'filesize' => array('value' => '6'), 'dimension' => array('type' => 'text:text', 'value' => '100:100'));


$testtt	= array(
			'games'		=> 'images/games/',
			'icons'		=> 'images/icons/',
			'maps'		=> 'images/maps/',
			'newscat'	=> 'images/newscat/',
			'ranks'		=> 'images/ranks/',
			'downloads' => 'upload/downloads/',
			'gallery'	=> 'upload/gallery/',
	#		'groups'	=> array('path' => 'upload/groups/',	'gfilesize' => '1', 'gdimension' => '100:100'),
	#		'matchs'	=> array('path' => 'mupload/matchs/',	'mfilesize' => '2', 'mdimension' => '100:100', 'mpreview' => '100:100'),
	#		'network'	=> array('path' => 'upload/network/',	'filesize' => '3', 'dimension' => '100:100'),
	#		'team_flag'	=> array('path' => 'upload/teams/',		'filesize' => '4', 'dimension' => '100:100', 'upload' => '1'),
	#		'team_logo'	=> array('path' => 'upload/teams/',		'filesize' => '5', 'dimension' => '100:100', 'upload' => '1'),
	#		'users'		=> array('path' => 'upload/users/',		'filesize' => '6', 'dimension' => '100:100'),
			);

ary($testtt, 'paths');

ary($games, 'games');
ary($maps, 'maps');
ary($newscat, 'newscat');
ary($ranks, 'ranks');
ary($downloads, 'downloads');
ary($gallery, 'gallery');
ary($groups, 'groups');
ary($matchs, 'matchs');
ary($path_network, 'path_network');
ary($teams_banner, 'teams_banner');
ary($teams_logo, 'teams_logo');
ary($users, 'users');

$cal	= array('show' => array('type' => 'radio:calview', 'value' => 1), 'time' => array('value' => 1800), 'cache' => array('type' => 'radio:yesno', 'value' => 1), 'start' => array('type' => 'radio:caldays', 'value' => 1), 'bday' => array('type' => 'radio:yesno', 'value' => 1), 'news' => array('type' => 'radio:yesno', 'value' => 1), 'event' => array('type' => 'radio:yesno', 'value' => 1), 'match' => array('type' => 'radio:yesno', 'value' => 1, 'order' => 80), 'train' => array('type' => 'radio:yesno', 'value' => 1, 'order' => 90));
$cal2	= array('show' => 1, 'time' => 1800, 'cache' => 1, 'start' => 1, 'bday' => 1, 'news' => 1, 'event' => 1, 'match' => 1, 'train' => 1);
$mini	= array('show' => array('type' => 'radio:yesno', 'value' => 1), 'time' => array('value' => 1800), 'cache' => array('type' => 'radio:yesno', 'value' => 1), 'start' => array('type' => 'radio:caldays', 'value' => 1), 'bday' => array('type' => 'radio:yesno', 'value' => 1), 'news' => array('type' => 'radio:yesno', 'value' => 1), 'event' => array('type' => 'radio:yesno', 'value' => 1), 'match' => array('type' => 'radio:yesno', 'value' => 1, 'order' => 80), 'train' => array('type' => 'radio:yesno', 'value' => 1, 'order' => 90));
$mini2	= array('show' => 1, 'time' => 1800, 'cache' => 1, 'start' => 1, 'bday' => 1, 'news' => 1, 'event' => 1, 'match' => 1, 'train' => 1);

ary($cal	, 'cal');
ary($cal2	, 'cal');
ary($mini	, 'mini');
ary($mini2	, 'mini');



$lobby = array(
'date'			=> array('value' => 'd.m H:i'),
'news_limit'	=> array('value' => '60'),
'event_limit'	=> array('value' => '60'),
'match_limit'	=> array('value' => '60'),
'train_limit'	=> array('value' => '60'),
'news_future'	=> array('value' => '14'),
'event_future'	=> array('value' => '14'),
'match_future'	=> array('value' => '14'),
'train_future'	=> array('value' => '14'));
ary($lobby, 'lobby');

$spam_comments = array(
'news' => array('value' => '20',),
'event' => array('value' => '20',),
'match' => array('value' => '20',),
'train' => array('value' => '20',));

$per_page_entry = array(
'acp' => array('value' => '45',),
'mcp' => array('value' => '35',),
'ucp' => array('value' => '25',),
'site' => array('value' => '15',),
'index' => array('value' => '5',),
'comments' => array('value' => '2',));

$news_def = array(
'browse' => array('type' => 'opt:news_view', 'value' => '0',), 'limit' => array('order' => 20, 'value' => '5',),
'words' => array('value' => '100',));

$stats = array('counter'  => array('type' => 'radio:yesno', 'value' => 1), 'counter_start'  => array('value' => 1), 'show_header_counter' => array('type' => 'radio:yesno', 'value' => 1), 'show_header_online' => array('type' => 'radio:yesno', 'value' => 1), 'show_navi_counter' => array('type' => 'radio:yesno', 'value' => 1), 'show_navi_online' => array('type' => 'radio:yesno', 'value' => 1));
ary($stats, 'stats');

$match_type = array('show' => 1, 'type_unknown' => array('value' => 'Unbekannt', 'default' => 0), 'type_two' => array('value' => '2on2', 'default' => 0), 'type_three' => array('value' => '3on3', 'default' => 0), 'type_four' => array('value' => '4on4', 'default' => 0), 'type_five' => array('value' => '5on5', 'default' => 1), 'type_six' => array('value' => '6on6', 'default' => 0));
$match_war = array('show' => 1, 'war_fun' => array('value' => 'Fun War', 'default' => 0), 'war_training' => array('value' => 'Training War', 'default' => 0), 'war_clan' => array('value' => 'Clan War', 'default' => 1), 'war_league' => array('value' => 'Liga War', 'default' => 0));
$match_league = array('show' => 1, 'league_nope' => array('value' => 'keine Liga', 'default' => 1, 'url' => array('value' => 'leer')), 'league_esl' => array('value' => 'ESL', 'default' => 0, 'url' => array('value' => 'http://www.esl.eu/')), 'league_sk' => array('value' => 'Stammkneipe', 'default' => 0, 'url' => array('value' => 'http://www.stammkneipe.eu/')), 'league_liga' => array('value' => '0815 Liga', 'default' => 0, 'url' => array('value' => 'http://www.0815liga.eu/')), 'league_lgz' => array('value' => 'Leaguez', 'default' => 0, 'url' => array('value' => 'http://www.lgz.eu/')), 'league_te' => array('value' => 'TE', 'default' => 0, 'url' => array('value' => 'http://www.tactical-esports.de/')), 'league_xgc' => array('value' => 'XGC', 'default' => 0, 'order' => 80, 'url' => array('value' => 'http://www.xgc-online.de/')), 'league_ncsl' => array('value' => 'NCSL', 'default' => 0, 'order' => 90, 'url' => array('value' => 'http://www.ncsl.de/')));


$match_war = array(
'show' => 1,
	'war_fun'		=> array('value' => 'Fun War', 'default' => 0, 'order' => 10),
	'war_training'	=> array('value' => 'Training War', 'default' => 0, 'order' => 20),
	'war_clan'		=> array('value' => 'Clan War', 'default' => 1, 'order' => 30),
	'war_league'	=> array('value' => 'Liga War', 'default' => 0, 'order' => 40)
);

$match_war = array(
'show' => 1,
	'war_fun'		=> array('value' => 'Fun War', 'default' => 0, 'order' => 10),
	'war_training'	=> array('value' => 'Training War', 'default' => 0, 'order' => 20),
	'war_clan'		=> array('value' => 'Clan War', 'default' => 1, 'order' => 30),
	'war_league'	=> array('value' => 'Liga War', 'default' => 0, 'order' => 40)
);

$match_league = array(
'show' => 1,
	'league_nope'	=> array('value' => 'keine Liga', 'default' => 1,	'order' => 10, 'url' => 'leer'),
	'league_esl'	=> array('value' => 'ESL', 'default' => 0,			'order' => 20, 'url' => 'http://www.esl.eu/'),
	'league_sk'		=> array('value' => 'Stammkneipe', 'default' => 0,	'order' => 30, 'url' => 'http://www.stammkneipe.eu/'),
	'league_liga'	=> array('value' => '0815 Liga', 'default' => 0,	'order' => 40, 'url' => 'http://www.0815liga.eu/'),
	'league_lgz'	=> array('value' => 'Leaguez', 'default' => 0,		'order' => 50, 'url' => 'http://www.lgz.eu/'),
	'league_te'		=> array('value' => 'TE', 'default' => 0,			'order' => 60, 'url' => 'http://www.tactical-esports.de/'),
	'league_xgc'	=> array('value' => 'XGC', 'default' => 0,			'order' => 70, 'url' => 'http://www.xgc-online.de/'),
	'league_ncsl'	=> array('value' => 'NCSL', 'default' => 0,			'order' => 80, 'url' => 'http://www.ncsl.de/'));

/*
$smain = array(
	'dl'		=> array('drop' => 1, 'subs' => 1),
	'fields'	=> array('drop' => 1, 'subs' => 1),
	'forum'		=> array('drop' => 1, 'subs' => 1),
	'gallery'	=> array('drop' => 1, 'subs' => 1),
	'label'		=> array('drop' => 1, 'subs' => 1),
	'maps'		=> array('drop' => 1, 'subs' => 1),
	'menu'		=> array('drop' => 1, 'subs' => 1),
);
*/

$smain = array(
#	'dl_drop' => '1',
#	'dl_subs' => '1',
#	'fields_drop' => '1',
#	'fields_subs' => '1',
#	'forum_drop' => '1',
#	'forum_subs' => '1',
#	'gallery_drop' => '1',
#	'gallery_subs' => '1',
#	'label_drop' => '1',
#	'label_subs' => '1',
#	'maps_drop' => '1',
#	'maps_subs' => '1',
#	'menu_drop' => '1',
#	'menu_subs' => '1',
	
	'dl_switch' => 0,
	'dl_entrys' => 1,
	'dl_overview' => 1,
	
	'profile_switch' => 0,
	'profile_entrys' => 1,
	'profile_overview' => 1,
	
	'gallery_switch' => 0,
	'gallery_entrys' => 1,
	'gallery_overview' => 1,
	
	'label_switch' => '1',
	'label_entrys' => '1',
	'label_overview' => 1,
	
	'maps_switch' => '1',
	'maps_entrys' => '1',
	'maps_overview' => '1',
	
	'forum_switch' => 0,
	'forum_entrys' => 1,
	'forum_overview' => 1,
	
	'menu_switch' => 0,
	'menu_entrys' => 1,
	'menu_overview' => 1,
	
#	'dl'		=> array('drop' => '1', 'subs' => '1'),
#	'fields'	=> array('drop' => '1', 'subs' => '1'),
#	'forum'		=> array('drop' => '1', 'subs' => '1'),
#	'gallery'	=> array('drop' => '1', 'subs' => '1'),
#	'label'		=> array('drop' => '1', 'subs' => '1'),
#	'maps'		=> array('drop' => '1', 'subs' => '1'),
#	'menu'		=> array('drop' => '1', 'subs' => '1'),
);

ary($smain, 'smain');

$match_type = array(
'show' => 1,
'type_unknown'	=> array('value' => 'Unbekannt', 'default' => 0, 'order' => 10),
'type_two'		=> array('value' => '2on2', 'default' => 0, 'order' => 20),
'type_three'	=> array('value' => '3on3', 'default' => 0, 'order' => 30),
'type_four'		=> array('value' => '4on4', 'default' => 0, 'order' => 40),
'type_five'		=> array('value' => '5on5', 'default' => 1, 'order' => 50),
'type_six'		=> array('value' => '6on6', 'default' => 0, 'order' => 60));
ary($match_type, 'match_type');

$cash_bank = array(
'bank_holder' => array('value' => 'bank_holder'), 'bank_name' => array('value' => 'bank_name'), 'bank_blz' => array('value' => '12'), 'bank_number' => array('value' => '12'), 'bank_reason' => array('value' => 'bank_reason'));

$cash_bank2 = array(
'bank_holder' => array('value' => ''), 'bank_name' => array('value' => ''), 'bank_blz' => array('value' => ''), 'bank_number' => array('value' => ''), 'bank_reason' => array('value' => ''));

$tmp_auth = array('auth_cash' => 0, 'auth_contact' => 0, 'auth_downloads' => 0, 'auth_event' => 0, 'auth_fightus' => 0, 'auth_forum' => 0, 'auth_forum_perm' => 0, 'auth_gallery' => 0, 'auth_games' => 0, 'auth_groups' => 0, 'auth_groups_perm' => 0, 'auth_joinus' => 0, 'auth_maps' => 0, 'auth_match' => 0, 'auth_navi' => 0, 'auth_network' => 0, 'auth_news' => 0, 'auth_news_public' => 0, 'auth_newscat' => 0, 'auth_newsletter' => 0, 'auth_ranks' => 0, 'auth_server' => 0, 'auth_server_type' => 0, 'auth_teams' => 0, 'auth_teamspeak' => 0, 'auth_themes' => 0, 'auth_training' => 0, 'auth_user' => 0, 'auth_user_perm' => 0, 'auth_votes' => 0);

$gallery_old = array(
'auth_view' => array('type' => 'drop', 'value' => 1), 'auth_edit' => array('type' => 'drop', 'value' => 6), 'auth_delete' => array('type' => 'drop', 'value' => 6), 'auth_rate' => array('type' => 'drop', 'value' => 1), 'auth_upload' => array('type' => 'drop', 'value' => 1), 'max_filesize' => array('value' => 3145728), 'dimension' => array('type' => 'input:input', 'value' => '1024:768'), 'dsp_format' => array('type' => 'input:input', 'value' => '3:12', 'order' => 80), 'preview' => array('type' => 'input:input', 'value' => '100:75', 'order' => 90), 'preview_list' => array('type' => 'opt:opt_gallery', 'value' => 10));

$gallery = array('auth_view' => array('type' => 'dropdown', 'req' => 'int', 'value' => 1), 'auth_edit' => array('type' => 'dropdown', 'req' => 'int', 'value' => 6), 'auth_delete' => array('type' => 'dropdown', 'req' => 'int', 'value' => 6), 'auth_rate' => array('type' => 'dropdown', 'req' => 'int', 'value' => 1), 'auth_upload' => array('type' => 'dropdown', 'req' => 'int', 'value' => 1), 'max_filesize' => array('req' => 'int', 'value' => 3145728), 'dimension' => array('type' => 'text:text', 'req' => 'int', 'value' => '1024:768'), 'dsp_format' => array('type' => 'text:text', 'req' => 'int', 'value' => '3:12', 'order' => 80), 'preview' => array('type' => 'text:text', 'req' => 'int', 'value' => '100:75', 'order' => 90), 'preview_list' => array('type' => 'radio:gallery', 'req' => 'int', 'value' => 10));

$galleryn = array('auth_view' => 1, 'auth_edit' => 6, 'auth_delete' => 6, 'auth_rate' => 1, 'auth_upload' => 1, 'max_filesize' => 3145728, 'dimension' => '1024:768', 'dsp_format' => '3:12', 'preview' => '100:75', 'preview_list' => 1);
$gallerys = array('auth_view' => array('type' => 'dropdown', 'req' => 'int', 'value' => 1), 'auth_edit' => array('type' => 'dropdown', 'req' => 'int', 'value' => 6), 'auth_delete' => array('type' => 'dropdown', 'req' => 'int', 'value' => 6), 'auth_rate' => array('type' => 'dropdown', 'req' => 'int', 'value' => 1), 'auth_upload' => array('type' => 'dropdown', 'req' => 'int', 'value' => 1), 'max_filesize' => array('req' => 'int', 'value' => 3145728), 'dimension' => array('type' => 'text:text', 'req' => 'int', 'value' => '1024:768'), 'dsp_format' => array('type' => 'text:text', 'req' => 'int', 'value' => '3:12', 'order' => 80), 'preview' => array('type' => 'text:text', 'req' => 'int', 'value' => '100:75', 'order' => 90), 'preview_list' => array('type' => 'radio:gallery', 'req' => 'int', 'value' => 10));

ary($galleryn, 'galleryn');
ary($gallerys, 'galleryn');

ary($gallery, 'gallery');
ary($tmp_auth, 'tmp_auth');
ary($cash_bank, 'cash_bank');
ary($cash_bank2, 'cash_bank2');
ary($match_type, 'match_type');
ary($match_war, 'match_war');
ary($match_league, 'match_league');

ary(array(), 'match_type');

# ary($news_def, 'news_def');
# ary($lobby, 'lobby');
# ary($spam_comments, 'spam_comments');
ary($per_page_entry, 'per_page_entry');
# ary($news, 'news');
# ary($last_match, 'last_match');
# ary($last_topics, 'last_topics');
# ary($downloads, 'downloads');
# ary($newusers, 'newusers');
# ary($teams, 'teams');
# ary($network, 'network');
# ary($server, 'server');
# ary($next_match, 'next_match');
# ary($next_train, 'next_train');
# ary($cache, 'cache');
# ary($files, 'files');

#echo substr(md5('GUEST', false), 0, 10) . '<br />';
#echo substr(md5('USER', false), 0, 10) . '<br />';
#echo substr(md5('TRIAL', false), 0, 10) . '<br />';
#echo substr(md5('MEMBER', false), 0, 10) . '<br />';
#echo substr(md5('MOD', false), 0, 10) . '<br />';
#echo substr(md5('ADMIN', false), 0, 10) . '<br />';
#echo substr(md5('NONE', false), 0, 10) . '<br />';

$label_data = fopen("cms_acl_label_data.txt","r+"); 

while ( !feof($label_data) )
{
	$zeile = fgets($label_data, 1000);
	
	$_ary[] = explode("\t", $zeile);
#	echo $zeile . "<br>";
}
fclose($label_data);  

echo "INSERT INTO `cms_acl_label_data` (`label_id`, `auth_option_id`, `auth_value`) VALUES ";

foreach ( $_ary as $_zeile )
{
	echo "(" . implode(', ', $_zeile) . "),";
}

print '<br />';
print '<br />';

$zeile = $_zeile = $_ary = '';

$option = fopen("cms_acl_option.txt","r+"); 

while ( !feof($option) )
{
	$zeile = fgets($option, 1000);
	
	$_ary[] = explode("\t", $zeile);
#	echo $zeile . "<br>";
}
fclose($option);  

echo "INSERT INTO `cms_acl_option` (`auth_option_id`, `auth_option`, `auth_group`) VALUES ";

foreach ( $_ary as $_zeile )
{
	echo "('" . implode("', '", $_zeile) . "'),";
}



?>

</body>
</html>