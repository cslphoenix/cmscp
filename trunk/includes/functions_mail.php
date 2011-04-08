<?php

function _send_notice($mail_data, $type, $file, $urlstring = '')
{
	global $db, $config, $settings, $lang, $emailer, $root_path, $user_ip;
	
	include($root_path . 'includes/class_emailer.php');
	
	$user_id			= $mail_data['user_id'];
	$user_name			= $mail_data['username'];
	$user_email			= $mail_data['user_email'];
	$user_lang			= $mail_data['user_lang'];
	$user_send_type		= $mail_data['user_send_type'];
	$user_notify_pm		= $mail_data['user_notify_pm'];

	$page_name			= $config['page_name'];
	$page_email			= $config['page_email'];
	$page_email_sig		= $config['page_email_sig'];
	$smtp_delivery		= $config['smtp_delivery'];
	
	if ( $user_send_type )
	{
		$privmsg_subject = $lang[$type];
		$privmsg_message = sprintf($lang[$type . '_msg'], $user_name, $page_name, '<a href=' . append_sid(server_url($file)) . '>', '</a>', $page_email_sig);

		$sql = 'INSERT INTO ' . PRIVMSGS . '
				(
					privmsgs_type,
					privmsgs_from_userid,
					privmsgs_to_userid,
					privmsgs_subject,
					privmsgs_text,
					privmsgs_date,
					privmsgs_ip,
					privmsgs_enable_html,
					privmsgs_enable_bbcode,
					privmsgs_enable_smilies
				)
				VALUES
				(
					' . PRIVMSGS_NEW_MAIL . ',
					0,
					' . $user_id . ',
					"' . $privmsg_subject . '",
					"' . $privmsg_message . '",
					' . time() . ',
					"' . $user_ip . '",
					1,
					1,
					1
				)';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	
		$sql = 'UPDATE ' . USERS . ' SET user_new_privmsg = user_new_privmsg + 1 WHERE user_id = ' . $user_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}

		if ( $user_notify_pm )
		{
			$emailer = new emailer($smtp_delivery);
	
			$emailer->from($page_email);
			$emailer->replyto($user_email);
			$emailer->cc('');
			$emailer->bcc('');
			$emailer->use_template('privmsg_notify', $user_lang);
			$emailer->email_address($user_email);
			$emailer->set_subject($lang['Notification_subject']);

			$emailer->assign_vars(array(
				'SITENAME'		=> $page_name,
				'USERNAME'		=> $user_name,
				'EMAIL_SIG'		=> (!empty($page_email_sig)) ? str_replace('<br>', "\n", "-- \n" . $page_email_sig) : '', 
				'U_INBOX'		=> server_url('privmsg') . '?folder=inbox'
			));
			
			$emailer->send();
			$emailer->reset();
		}
	}
	else
	{
		$emailer = new emailer($smtp_delivery);
	
		$emailer->from($page_email);
		$emailer->replyto($user_email);
		$emailer->cc('');
		$emailer->bcc('');
		$emailer->use_template($type, $user_lang);
		$emailer->email_address($user_email);
		$emailer->set_subject($lang[$type]);
		
		$emailer->assign_vars(array(
			'SITENAME'	=> $page_name,
			'USERNAME'	=> $user_name,
			'EMAIL_SIG'	=> (!empty($page_email_sig)) ? str_replace('<br>', "\n", "-- \n" . $page_email_sig) : '', 
			'U_URL'		=> server_url($file) . $urlstring,
		));
		
		$emailer->send();
		$emailer->reset();
	}
	
	
	
	return;
}

function server_url($file)
{
	global $db, $config;
	
	$script_name		= preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($config['page_path']));
	$script_name		= ( $script_name != '' ) ? $script_name . '/' . $file . '.php' : $file . '.php';
	$server_name		= trim($config['server_name']);
	$server_protocol	= ( $config['cookie_secure'] ) ? 'https://' : 'http://';
	$server_port		= ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';
	$server_url			= $server_protocol . $server_name . $server_port . $script_name;
	
	return $server_url;
}

?>