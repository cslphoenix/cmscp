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
include($root_path . 'includes/functions_newsletter.php');

$userdata = session_pagestart($user_ip, PAGE_NEWSLETTER);
init_userprefs($userdata);

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	if ( isset($HTTP_POST_VARS['unsubscribe']) || isset($HTTP_GET_VARS['unsubscribe']) )
	{
		$mode = 'unsubscribe';
	}
	else
	{
		$mode = '';
	}
}

if ( isset($HTTP_POST_VARS['mail']) || isset($HTTP_GET_VARS['mail']) )
{
	$mail = ( isset($HTTP_POST_VARS['mail']) ) ? $HTTP_POST_VARS['mail'] : $HTTP_GET_VARS['mail'];
	$mail = htmlspecialchars($mail);
}

if ( isset($HTTP_POST_VARS['key_code']) || isset($HTTP_GET_VARS['key_code']) )
{
	$key_code = ( isset($HTTP_POST_VARS['key_code']) ) ? $HTTP_POST_VARS['key_code'] : $HTTP_GET_VARS['key_code'];
	$key_code = htmlspecialchars($key_code);
}

$page_title = $lang['page_newsletter'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'body_newsletter.tpl'));

if ( $mode == 'subscribe' || !$mode )
{
	if ( isset($HTTP_POST_VARS['submit']) )
	{
		if ( $mail )
		{
			if ( check_mail_subscribe($mail) )
			{
				$active_key = md5(uniqid(rand(), TRUE));
				
				$sql = 'INSERT INTO ' . NEWSLETTER . " (newsletter_mail, newsletter_status, active_key)
					VALUES ('$mail', 0, '$active_key')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				include($root_path . 'includes/functions_mail.php');
		
				$mail_data['user_id']			= ANONYMOUS;
				$mail_data['username']			= $lang['guest'];
				$mail_data['user_email']		= $mail;
				$mail_data['user_lang']			= $config['default_lang'];
				$mail_data['user_send_type']	= '0';
				$mail_data['user_notify_pm']	= '0';
				
				_send_notice($mail_data, 'newsletter_subscribe_confirm', 'newsletter', "?mode=active&mail=$mail&key_code=$active_key");
				
				log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_add');
		
				$message = $lang['newsletter_subscribe'] . sprintf($lang['click_return_newsletter'], '<a href="' . append_sid('newsletter.php') . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
			}
			else
			{
				message(GENERAL_ERROR, $lang['nl_mail_invalid'], '');
			}
		}
		else
		{
			message(GENERAL_ERROR, $lang['nl_mail_invalid'], '');
		}
	}
}
else if ( $mode == 'active' && $mail && $key_code )
{
	$sql = 'SELECT newsletter_mail, active_key
				FROM ' . NEWSLETTER . '
				WHERE newsletter_mail = "' . str_replace("\'", "''", $mail) . '" AND active_key = "' . $key_code . '"';
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
			
	if ($row = $db->sql_fetchrow($result))
	{
		$sql = 'UPDATE ' . NEWSLETTER . ' SET newsletter_status = 1 WHERE newsletter_mail = "' . $row['newsletter_mail'] . '"';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}

		include($root_path . 'includes/functions_mail.php');
		
		$mail_data['user_id']			= ANONYMOUS;
		$mail_data['username']			= $lang['guest'];
		$mail_data['user_email']		= $mail;
		$mail_data['user_lang']			= $config['default_lang'];
		$mail_data['user_send_type']	= '0';
		$mail_data['user_notify_pm']	= '0';
		
		_send_notice($mail_data, 'newsletter_subscribe', 'newsletter');
		
		log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_add');
		
		$message = $lang['newsletter_subscribe_confirm'] . sprintf($lang['click_return_newsletter'], '<a href="' . append_sid('newsletter.php') . '">', '</a>');
		message(GENERAL_MESSAGE, $message);
	}
	$db->sql_freeresult($result);
	
	log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_add_confirm');
		
	$message = $lang['newsletter_fail'] . sprintf($lang['click_return_newsletter'], '<a href="' . append_sid('newsletter.php') . '">', '</a>');
	message(GENERAL_ERROR, $message);
}
else if ( $mode == 'unsubscribe' )
{
	if ( $deactive_key = check_mail_unsubscribe($mail) )
	{
		include($root_path . 'includes/functions_mail.php');
		
		$mail_data['user_id']			= ANONYMOUS;
		$mail_data['username']			= $lang['guest'];
		$mail_data['user_email']		= $mail;
		$mail_data['user_lang']			= $config['default_lang'];
		$mail_data['user_send_type']	= '0';
		$mail_data['user_notify_pm']	= '0';
		
		_send_notice($mail_data, 'newsletter_unsubscribe_confirm', 'newsletter', "?mode=delete&mail=$mail&key_code=$deactive_key");
				
		log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_delete');
		
		$message = $lang['newsletter_unsubscribe'] . sprintf($lang['click_return_newsletter'], '<a href="' . append_sid('newsletter.php') . '">', '</a>');
		message(GENERAL_MESSAGE, $message);
	}
	else
	{
		message(GENERAL_ERROR, $lang['nl_mail_invalid'], '');
	}
}
else if ( $mode == 'delete' && $mail && $deactive_key )
{
	$sql = 'SELECT newsletter_mail, active_key
				FROM ' . NEWSLETTER . '
				WHERE newsletter_mail = "' . str_replace("\'", "''", $mail) . '" AND active_key = "' . $deactive_key . '"';
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
			
	if ($row = $db->sql_fetchrow($result))
	{
		$active_key = md5(uniqid(rand(), TRUE));
		
		$sql = 'DELETE FROM ' . NEWSLETTER . ' WHERE newsletter_mail = "' . $mail . '" AND active_key = "' . $deactive_key . '"';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		include($root_path . 'includes/functions_mail.php');
		
		$mail_data['user_id']			= ANONYMOUS;
		$mail_data['username']			= $lang['guest'];
		$mail_data['user_email']		= $mail;
		$mail_data['user_lang']			= $config['default_lang'];
		$mail_data['user_send_type']	= '0';
		$mail_data['user_notify_pm']	= '0';
		
		_send_notice($mail_data, 'newsletter_unsubscribe', 'newsletter');
		
		log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_delete_confirm');
		
		$message = $lang['newsletter_subscribe_confirm'] . sprintf($lang['click_return_newsletter'], '<a href="' . append_sid('newsletter.php') . '">', '</a>');
		message(GENERAL_MESSAGE, $message);
	}
		
	$message = $lang['newsletter_fail'] . sprintf($lang['click_return_newsletter'], '<a href="' . append_sid('newsletter.php') . '">', '</a>');
	message(GENERAL_ERROR, $message);
}
else
{
	redirect(append_sid('newsletter.php', true));
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>