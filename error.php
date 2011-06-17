<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php'); 

if ( empty($lang) )
{
	if ( !empty($config['default_lang']) ) 
	{
		include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_main.php');
	}
	else
	{
		include($root_path . 'language/lang_english/lang_main.php');
	}
}

$code = request('code', 2);

switch ( $code )
{
	case 401:
	case 403:
	case 404:
	case 500: 
		
		$desc_msg = $lang['desc_' . $code];
		$code_msg = $lang['Error'] . ": " . $lang['code_' . $code];
	
		break;
	
	default: $desc_msg = $lang['unknown_error']; break;
}

message(GENERAL_ERROR, $desc_msg, $code_msg);

?>