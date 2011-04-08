<?php

define('IN_CMS', true);

$root_path = './';
include($root_path . 'common.php'); 

if (empty($lang))
{
	if (!empty($config['default_lang'])) 
	{
		include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_main.php');
	}
	else
	{
		include($root_path . 'language/lang_english/lang_main.php');
	}
}

$code = $_GET['code'];

switch ($code)
{
	case '401':
	case '403':
	case '404':
	case '500': 
		$message = $lang[$code . '_Description'];
		$message_title = $lang['Error'] . ":sd " . $lang[$code];
	break;
	default:
		$message = $lang['Unknown_error'];
	break;
}

message(GENERAL_ERROR, $message, $message_title);

?>