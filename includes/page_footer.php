<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

global $do_gzip_compress, $userdata;


$template->set_filenames(array('overall_footer' => ( empty($gen_simple_header) ) ? 'overall_footer.tpl' : 'simple_footer.tpl'));

$debug = (defined('DEBUG')) ? '[ Debug: On ]' : '[ Debug: Off ]';
$cache = (defined('CACHE')) ? '[ Cache: On ]' : '[ Cache: Off ]';

$template->assign_vars(array(
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

ob_end_flush();

exit;

?>