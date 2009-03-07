<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_CONTACT);
init_userprefs($userdata);

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

if ( $mode == '' || $mode == 'joinus' || $mode == 'fightus' )
{
	$page_title = ($mode != '') ? ($mode == 'joinus') ? $lang['contact_joinus'] : $lang['contact_fightus'] : $lang['contact'];
	
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'contact_body.tpl'));

}
/*
else if ()
{
	$page_title = $lang['contact_joinus'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'contact_join_body.tpl'));
	
}
else if ($mode == 'fightus')
{
	$page_title = $lang['contact_fightus'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'contact_fight_body.tpl'));
	
}
*/
else
{
	redirect(append_sid('contact.php', true));
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>