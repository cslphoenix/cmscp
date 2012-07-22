<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['hm_system']['sm_phpinfo'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);

	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_phpinfo';

	include('./pagestart.php');

	add_lang('types');

	$file	= basename(__FILE__);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN xor !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	$template->set_filenames(array('body' => 'style/acp_phpinfo.tpl'));
	
	ob_start();
	phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_VARIABLES);
	$phpinfo = ob_get_clean();

	$phpinfo = trim($phpinfo);
	
	

	// Here we play around a little with the PHP Info HTML to try and stylise
	// it along phpBB's lines ... hopefully without breaking anything. The idea
	// for this was nabbed from the PHP annotated manual
	preg_match_all('#<body[^>]*>(.*)</body>#si', $phpinfo, $output);

	$output = $output[1][0];

	// expose_php can make the image not exist
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
	#$output = preg_replace_callback('#<a name="([^"]+)">#', array($this, 'remove_spaces'), $output);

	$orig_output = $output;

	preg_match_all('#<div class="center">(.*)</div>#siU', $output, $output);
	$output = (!empty($output[1][0])) ? $output[1][0] : $orig_output;
	
	
	
	
	
	/* http://de2.php.net/manual/de/function.phpinfo.php#84259 */
	/*
	$output = '';
	
	ob_start();
	phpinfo();
#	phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_VARIABLES);
	
	$phpinfo = array('PHP Info' => array());
	
	if ( preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER) )
	{
		
		
		foreach( $matches as $match )
		{
			if ( strlen($match[1]) )
			{
				$phpinfo[$match[1]] = array();
			}
			else if ( isset($match[3]) )
			{
				$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			}
			else
			{
				$phpinfo[end(array_keys($phpinfo))][] = $match[2];
			}
		}
		
		
		
		foreach ( $phpinfo as $name => $section )
		{
			$output .= "<strong>$name</strong><br /><table>";
			
			foreach ( $section as $key => $val)
			{
				if ( is_array($val) )
				{
					$output .= "<tr><td>$key</td><td>$val[0]</td><td>$val[1]</td></tr>\n";
				}
				elseif(is_string($key))
				{
					$val = wordwrap($val, '50', '<br />', true);
					$output .= "<tr><td>$key</td><td>$val</td></tr>\n";
				}
				else
                $output .= "<tr><td>$val</td></tr>\n";
        }
        $output .= "</table><br />";
    }
	 }
	 */
	$template->assign_vars(array(
	#	'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		
		'INFO'	=> $output,
		
		'S_ACTION'	=> check_sid($file),
	#	'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>