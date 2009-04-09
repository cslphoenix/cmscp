<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

global $do_gzip_compress, $userdata;

	$group_auth_fields = array(
		'auth_contact',
		'auth_fightus',
		'auth_forum',
		'auth_forum_auth',
		'auth_games',
		'auth_groups',
		'auth_joinus',
		'auth_match',
		'auth_navi',
		'auth_news',
		'auth_news_public',
		'auth_newscat',
		'auth_ranks',
		'auth_server',
		'auth_teams',
		'auth_teamspeak',
		'auth_training',
		'auth_user',
	);
	
	$split = '';
	for ( $i = 0; $i < count($group_auth_fields); $i++ )
	{
		$split .= ' || $auth[\'' . $group_auth_fields[$i] . '\']';
	}

//_debug_post($auth);

//
// Show the overall footer.
//
$admin_link =	(	$userdata['user_level'] == ADMIN || $split ) ? '<a href="admin/index.php?sid=' . $userdata['session_id'] . '">' . $lang['Admin_panel'] . '</a><br /><br />' : '';

$template->set_filenames(array('overall_footer' => ( empty($gen_simple_header) ) ? 'overall_footer.tpl' : 'simple_footer.tpl'));

$debug = (defined('DEBUG')) ? '[ Debug: on ]' : '[ Debug: off ]';
$cache = (defined('CACHE')) ? '[ Cache: on ]' : '[ Cache: off ]';

$template->assign_vars(array(
	'ADMIN_LINK'	=> $admin_link,
	'DEBUG'			=> $debug,
	'CACHE'			=> $cache,
));

if ( empty($gen_simple_header) && defined('DEBUG_SQL') )
{
	// send run stat (page generation, sql time, requests dump...)
	$stat_run = new stat_run_class(microtime());
	$stat_run->display();
}

$template->pparse('overall_footer');

//
// Close our DB connection.
//
$db->sql_close();

//
// Compress buffered output if required and send to browser
//
if ( $do_gzip_compress )
{
	//
	// Borrowed from php.net!
	//
	$gzip_contents = ob_get_contents();
	ob_end_clean();

	$gzip_size = strlen($gzip_contents);
	$gzip_crc = crc32($gzip_contents);

	$gzip_contents = gzcompress($gzip_contents, 9);
	$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	echo $gzip_contents;
	echo pack('V', $gzip_crc);
	echo pack('V', $gzip_size);
}

exit;

?>