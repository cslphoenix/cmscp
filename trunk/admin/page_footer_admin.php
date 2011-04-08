<?php

global $data;

debug($data);

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

global $do_gzip_compress;

$template->set_filenames(array('page_footer' => 'style/page_footer.tpl'));
$template->assign_vars(array('CMS_VERSION' => $config['page_version']));

if ( defined('DEBUG_SQL_ADMIN') )
{
	$stat_run = new stat_run_class(microtime());
	$stat_run->display();
}

$template->pparse('page_footer');

$db->sql_close();

if( $do_gzip_compress )
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