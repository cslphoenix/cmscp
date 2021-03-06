<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_IMPRINT);
init_userprefs($userdata);

$template->set_filenames(array('body' => 'body_imprint.tpl'));

$template->assign_vars(array(
	'L_IMPRINT'			=> $lang['imprint'],
	'L_IMPRINT_NAME'	=> $lang['imprint_name'],
	'L_IMPRINT_EMAIL'	=> $lang['imprint_email'],
	
	'L_WM_NAME'			=> $lang['wm_name'],
	'L_WM_STREET'		=> $lang['wm_street'],
	'L_WM_PLACE'		=> $lang['wm_place'],
	'L_WM_ZIPCODE'		=> $lang['wm_zipcode'],
	'L_WM_EMAIL'		=> $lang['wm_email'],
	
	'L_DISCLAIMER'				=> $lang['disclaimer'],
	'L_LIABILITY_CONTENTS'		=> $lang['liability_contents'],
	'L_LIABILITY_CONTENTS_TEXT'	=> $lang['liability_contents_text'],
	'L_LIABILITY_LINKS'			=> $lang['liability_links'],
	'L_LIABILITY_LINKS_TEXT'	=> $lang['liability_links_text'],
	'L_COPYRIGHT'				=> $lang['copyright'],
	'L_COPYRIGHT_TEXT'			=> $lang['copyright_text'],
	'L_PRICACY_POLICY'			=> $lang['privacy_policy'],
	'L_PRICACY_POLICY_TEXT'		=> $lang['privacy_policy_text'],
	
));

main_header();

$template->pparse('body');

main_footer();

?>