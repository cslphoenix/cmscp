<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

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

message_die(GENERAL_ERROR, $message, $message_title);

?>