<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

//
// Show the overall footer.
//
$template->set_filenames(array('page_footer' => 'style/page_footer.tpl'));
$template->assign_vars(array('CMS_VERSION' => $config['page_version']));

if ( defined('DEBUG_SQL_ADMIN') )
{
	$stat_run = new stat_run_class(microtime());
	$stat_run->display();
}

$template->pparse('page_footer');

//
// Close our DB connection.
//
$db->sql_close();

exit;

?>