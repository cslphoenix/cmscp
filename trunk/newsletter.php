<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');
include($root_path . 'includes/functions_newsletter.php');

$userdata = session_pagestart($user_ip, PAGE_NEWSLETTER);
init_userprefs($userdata);

_debug_post($_POST);

if ( isset($HTTP_POST_VARS['unsubscribe']) || isset($HTTP_GET_VARS['unsubscribe']) )
{
	$mode = 'unsubscribe';
}
else
{
	$mode = 'subscribe';
}

$page_title = $lang['page_newsletter'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'body_newsletter.tpl'));

if ( $mode == 'subscribe' )
{
	$user = ( isset($HTTP_POST_VARS['user_name']) ) ? trim($HTTP_POST_VARS['user_name']) : '';
	$mail = ( isset($HTTP_POST_VARS['user_mail']) ) ? trim($HTTP_POST_VARS['user_mail']) : '';
	
	if ( isset($HTTP_POST_VARS['submit'])  )
	{
		if ( $user && $mail )
		{
			if ( check_mail_subscribe($mail) )
			{
				$key = md5(uniqid(rand(), TRUE));
				
				$sql = 'INSERT INTO ' . NEWSLETTER . " (user_name, user_mail, active, active_key)
					VALUES ('$user', '$mail', 0, '$key')";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_UCP, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_add');
		
				$message = $lang['newsletter_subscribe']
					. '<br /><br />' . sprintf($lang['click_return_index'], '<a href="' . append_sid("index.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				message_die(GENERAL_ERROR, 'Fehler1', '', __LINE__, __FILE__);
			}
		}
		else
		{
			message_die(GENERAL_ERROR, 'Fehler2', '', __LINE__, __FILE__);
		}
	}
	
}
else if ( $mode == 'unsubscribe' )
{
	$mail = ( isset($HTTP_POST_VARS['user_mail']) ) ? trim($HTTP_POST_VARS['user_mail']) : '';
	
	_debug_post(check_mail_unsubscribe($mail));
}
else
{
	message_die(GENERAL_ERROR, 'else', '', __LINE__, __FILE__);
}

//	check_mail_unsubscribe	check_mail_subscribe

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>