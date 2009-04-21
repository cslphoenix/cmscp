<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//
//	Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

//
//	Start output of page
//
$page_title = $lang['Index'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'body_server.tpl'));

require_once 'GameQ.php';

$servers = array(
    'server 1' => array('cssource', '78.46.67.110', 27045),
    'server 2' => array('bf2', '78.46.67.110'),
);

// Initialize the class
$gq = new GameQ;

// Add the servers we just defined
$gq->addServers($servers);

// Request the data, and display it
try {
    $data = $gq->requestData();
    _debug_post($data);
}

// Catch any errors that might have occurred
catch (GameQ_Exception $e) {
    echo 'An error occurred.';
}


$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>