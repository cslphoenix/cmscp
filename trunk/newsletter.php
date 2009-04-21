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

$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($config['script_path']));
$script_name = ( $script_name != '' ) ? $script_name . '/newsletter.php' : 'newsletter.php';
$server_name = trim($config['server_name']);
$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';
$server_url = $server_protocol . $server_name . $server_port . $script_name;

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = '';
}

if ( isset($HTTP_POST_VARS['unsubscribe']) || isset($HTTP_GET_VARS['unsubscribe']) )
{
	$mode = 'unsubscribe';
}

if ( isset($HTTP_POST_VARS['mail']) || isset($HTTP_GET_VARS['mail']) )
{
	$mail = ( isset($HTTP_POST_VARS['mail']) ) ? $HTTP_POST_VARS['mail'] : $HTTP_GET_VARS['mail'];
	$mail = htmlspecialchars($mail);
}

if ( isset($HTTP_POST_VARS['active_key']) || isset($HTTP_GET_VARS['active_key']) )
{
	$active_key = ( isset($HTTP_POST_VARS['active_key']) ) ? $HTTP_POST_VARS['active_key'] : $HTTP_GET_VARS['active_key'];
	$active_key = htmlspecialchars($active_key);
}

if ( isset($HTTP_POST_VARS['deactive_key']) || isset($HTTP_GET_VARS['deactive_key']) )
{
	$deactive_key = ( isset($HTTP_POST_VARS['deactive_key']) ) ? $HTTP_POST_VARS['deactive_key'] : $HTTP_GET_VARS['deactive_key'];
	$deactive_key = htmlspecialchars($deactive_key);
}

$page_title = $lang['page_newsletter'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'body_newsletter.tpl'));

_debug_post($_POST);
_debug_post($_GET);

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
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				include($root_path . 'includes/emailer.php');
				$emailer = new emailer($config['smtp_delivery']);
	
				$emailer->from($config['page_email']);
				$emailer->cc('');
				$emailer->bcc('');
				
				$emailer->use_template('newsletter_added', $config['default_lang']);
				$emailer->email_address($mail);
				$emailer->set_subject($lang['newsletter_added']);
	
				$emailer->assign_vars(array(
					'SITENAME' => $config['sitename'], 
					'EMAIL_SIG' => (!empty($config['page_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $config['page_email_sig']) : '', 
	
					'U_NEWSLETTER' => $server_url . "?mode=active&mail=$mail&active_key=$active_key",
				));
				$emailer->send();
				$emailer->reset();
				
				_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_add');
		
				$message = $lang['newsletter_subscribe'] . '<br><br>' . sprintf($lang['click_return_index'], '<a href="' . append_sid("index.php") . '">', '</a>');
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
else if ( $mode == 'active' && $mail && $active_key )
{
	$sql = 'SELECT newsletter_mail, active_key
				FROM ' . NEWSLETTER . '
				WHERE newsletter_mail = "' . str_replace("\'", "''", $mail) . '" AND active_key = "' . $active_key . '"';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain user email information.", "", __LINE__, __FILE__, $sql);
	}
			
	if ($row = $db->sql_fetchrow($result))
	{
		$active_key = md5(uniqid(rand(), TRUE));
		
		$sql = 'UPDATE ' . NEWSLETTER . ' SET newsletter_status = 1, active_key = "' . $active_key . '" WHERE newsletter_mail = "' . $row['newsletter_mail'] . '"';
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_add');
		
		$message = $lang['newsletter_subscribe_confirm'] . '<br><br>' . sprintf($lang['click_return_index'], '<a href="' . append_sid("index.php") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	$db->sql_freeresult($result);
	
	_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_add_confirm');
		
	$message = $lang['newsletter_fail'] . '<br><br>' . sprintf($lang['click_return_index'], '<a href="' . append_sid("index.php") . '">', '</a>');
	message_die(GENERAL_ERROR, $message);
}
else if ( $mode == 'unsubscribe' )
{
	if ( $deactive_key = check_mail_unsubscribe($mail) )
	{
		include($root_path . 'includes/emailer.php');
		$emailer = new emailer($config['smtp_delivery']);
	
		$emailer->from($config['page_email']);
		$emailer->cc('');
		$emailer->bcc('');
		
		$emailer->use_template('newsletter_delete', $config['default_lang']);
		$emailer->email_address($mail);
		$emailer->set_subject($lang['newsletter_delete']);
	
		$emailer->assign_vars(array(
			'SITENAME' => $config['sitename'], 
			'EMAIL_SIG' => (!empty($config['page_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $config['page_email_sig']) : '', 
	
			'U_NEWSLETTER' => $server_url . "?mode=delete&mail=$mail&deactive_key=$deactive_key",
		));
		$emailer->send();
		$emailer->reset();
		
		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_delete');
		
		$message = $lang['newsletter_unsubscribe'] . '<br><br>' . sprintf($lang['click_return_index'], '<a href="' . append_sid("index.php") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		message_die(GENERAL_ERROR, 'Falsche', '', __LINE__, __FILE__);
	}
}
else if ( $mode == 'delete' && $mail && $deactive_key )
{
	$sql = 'SELECT newsletter_mail, active_key
				FROM ' . NEWSLETTER . '
				WHERE newsletter_mail = "' . str_replace("\'", "''", $mail) . '" AND active_key = "' . $deactive_key . '"';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain user email information.", "", __LINE__, __FILE__, $sql);
	}
			
	if ($row = $db->sql_fetchrow($result))
	{
		$active_key = md5(uniqid(rand(), TRUE));
		
		$sql = 'DELETE FROM ' . NEWSLETTER . ' WHERE newsletter_mail = "' . $mail . '" AND active_key = "' . $deactive_key . '"';
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		include($root_path . 'includes/emailer.php');
		$emailer = new emailer($config['smtp_delivery']);
	
		$emailer->from($config['page_email']);
		$emailer->cc('');
		$emailer->bcc('');
		
		$emailer->use_template('newsletter_deaccept', $config['default_lang']);
		$emailer->email_address($mail);
		$emailer->set_subject($lang['newsletter_deaccept']);
	
		$emailer->assign_vars(array(
			'SITENAME' => $config['sitename'], 
			'EMAIL_SIG' => (!empty($config['page_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $config['page_email_sig']) : '', 
		));
		$emailer->send();
		$emailer->reset();
		
		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSLETTER, 'ucp_newsletter_delete_confirm');
		
		$message = $lang['newsletter_subscribe_confirm'] . '<br><br>' . sprintf($lang['click_return_index'], '<a href="' . append_sid("index.php") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
		
	$message = $lang['newsletter_fail'] . '<br><br>' . sprintf($lang['click_return_index'], '<a href="' . append_sid("index.php") . '">', '</a>');
	message_die(GENERAL_ERROR, $message);
}
else
{
	redirect(append_sid('newsletter.php', true));
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>