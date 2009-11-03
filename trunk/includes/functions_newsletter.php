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

function check_mail_subscribe($email)
{
	global $db, $lang;
	
	if ( preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*?[a-z]+$/is', $email) )
	{
		if ( domain_exists($email) )
		{
			$sql = 'SELECT ban_email
						FROM ' . BANLIST;
			if ($result = $db->sql_query($sql))
			{
				if ($row = $db->sql_fetchrow($result))
				{
					do
					{
						$match_email = str_replace('*', '.*?', $row['ban_email']);
						if (preg_match('/^' . $match_email . '$/is', $mail))
						{
							$db->sql_freeresult($result);
							message_die(GENERAL_ERROR, $lang['nl_mail_banned'], '');
						}
					}
					while($row = $db->sql_fetchrow($result));
				}
			}
			$db->sql_freeresult($result);
			
			$sql = 'SELECT newsletter_mail
						FROM ' . NEWSLETTER . '
						WHERE newsletter_mail = "' . str_replace("\'", "''", $email) . '"';
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			if ( $row = $db->sql_fetchrow($result) )
			{
				message_die(GENERAL_ERROR, $lang['nl_mail_taken'], '');
			}
			$db->sql_freeresult($result);
			
			return true;
		}
	}
	message_die(GENERAL_ERROR, $lang['nl_mail_invalid'], '');
}

function check_mail_unsubscribe($mail)
{
	global $db;
	
	if ( preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*?[a-z]+$/is', $mail) )
	{
		$sql = 'SELECT newsletter_mail
					FROM ' . NEWSLETTER . '
					WHERE newsletter_mail = "' . str_replace("\'", "''", $mail) . '"';
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		if ( $row = $db->sql_fetchrow($result) )
		{
			$key_code = md5(uniqid(rand(), TRUE));
			
			$sql = 'UPDATE ' . NEWSLETTER . ' SET active_key = "' . $key_code . '" WHERE newsletter_mail = "' . $row['newsletter_mail'] . '"';
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			return $key_code;
		}
		$db->sql_freeresult($result);
		
		message_die(GENERAL_ERROR, $lang['nl_mail_invalid'], '');
	}
	message_die(GENERAL_ERROR, $lang['nl_mail_invalid'], '');
}

function domain_exists($email, $record = 'MX')
{
	list($user, $domain) = split('@', $email);
	
	if ( checkdnsrr($domain, $record) )
	{
		return true;
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['nl_mail_invalid'], '');
	}
}

if (!function_exists('checkdnsrr') )
{
	function checkdnsrr($host, $type='')
	{
		if ( !empty($host) )
		{
			$type = (empty($type)) ? 'MX' :  $type;
			
			exec('nslookup -type='.$type.' '.escapeshellcmd($host), $result);
			$it = new ArrayIterator($result);
			
			foreach (new RegexIterator($it, '~^'.$host.'~', RegexIterator::GET_MATCH) as $result)
			{
				if ( $result )
				{
					return true;
				}                
			}
		}
		message_die(GENERAL_ERROR, $lang['nl_mail_invalid'], '');
    }
}


?>