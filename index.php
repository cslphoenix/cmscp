<?php

define('IN_CMSCP', true);
$root_path = './';
include($root_path . 'common.php');

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

include($root_path . 'includes/page_header.php');

$sql = "SELECT user_id FROM " . USERS_TABLE;
if( !($result = sql_query($sql)) )
{
	message_die(CRITICAL_ERROR, "fehler in der abfrage", "", __LINE__, __FILE__, $sql);
}

while($row = _fetch($result))
{
	$class = ($color % 2) ? "contentMainSecond" : "contentMainFirst";
	$color++;

	$list .= tpl("list_index.tpl", array(
		"class"		=> $class,
		'user_id'	=> $row['user_id']
	));
}

$index = tpl("index.tpl", array(
		'list' => $list
));

include($root_path . 'includes/page_footer.php');

?>