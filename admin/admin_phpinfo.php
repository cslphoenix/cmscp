<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_PHPINFO',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'ACP_PHPINFO'),
		),
	);
}
else
{
	define('IN_CMS', true);

	$header	= ( isset($_POST['cancel']) ) ? true : false;
	
	$current = 'acp_phpinfo';

	include('./pagestart.php');

	add_lang('phpinfo');
	acl_auth('A_PHPINFO');

	$file	= basename(__FILE__);
	$_top	= sprintf($lang['STF_HEADER'], $lang['TITLE']);
	
	$template->set_filenames(array(
		'body' => 'style/acp_phpinfo.tpl'
	));
	
	ob_start();
	phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_VARIABLES);
	$phpinfo = ob_get_clean();

	$phpinfo = trim($phpinfo);
	
	function remove_spaces($matches)
	{
		return '<a name="' . str_replace(' ', '_', $matches[1]) . '">';
	}
	
	preg_match_all('#<body[^>]*>(.*)</body>#si', $phpinfo, $output);

	$output = $output[1][0];

	if (preg_match('#<a[^>]*><img[^>]*></a>#', $output))
	{
		$output = preg_replace('#<tr class="v"><td>(.*?<a[^>]*><img[^>]*></a>)(.*?)</td></tr>#s', '<tr class="row1"><td><table class="rows"><tr><td>\2</td><td>\1</td></tr></table></td></tr>', $output);
	}
	else
	{
		$output = preg_replace('#<tr class="v"><td>(.*?)</td></tr>#s', '<tr class="row1"><td><table class="rows"><tr><td>\1</td></tr></table></td></tr>', $output);
	}
	$output = preg_replace('#<table[^>]+>#i', '<table class="phpinfo">', $output);
	$output = preg_replace('#<img border="0"#i', '<img border="0"', $output);
	$output = str_replace(array('class="e"', 'class="v"', 'class="h"', '<hr />', '<font', '</font>'), array('class="row1"', 'class="row2"', '', '', '<span', '</span>'), $output);
	
	// Fix invalid anchor names (eg "module_Zend Optimizer")
#	$output = preg_replace_callback('#<a name="([^"]+)">#', array($this, 'remove_spaces'), $output);

	$orig_output = $output;

	preg_match_all('#<div class="center">(.*)</div>#siU', $output, $output);
	$output = (!empty($output[1][0])) ? $output[1][0] : $orig_output;
	
	$template->assign_vars(array(
		'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
		'L_EXPLAIN'	=> $lang['EXPLAIN'],
		
		'INFO'	=> $output,
	));

	$template->pparse('body');
	
	acp_footer();
}

?>