<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

if ( defined('DEBUG_SQL_ADMIN') )
{
	$stat_run = new stat_run_class(microtime());
	$stat_run->display();
}

$template->pparse('footer');

$db->sql_close();

exit;

?>