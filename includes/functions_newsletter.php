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

function check_mail_unsubscribe($mail)
{
	global $db;
	
	if ( preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*?[a-z]+$/is', $mail) )
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
						return array('error' => true, 'error_msg' => $lang['Email_banned']);
					}
				}
				while($row = $db->sql_fetchrow($result));
			}
		}
		$db->sql_freeresult($result);
		
		$sql = 'SELECT user_mail
					FROM ' . NEWSLETTER . '
					WHERE user_mail = "' . str_replace("\'", "''", $mail) . '"';
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain user email information.", "", __LINE__, __FILE__, $sql);
		}
		
		if ($row = $db->sql_fetchrow($result))
		{
			$key = md5(uniqid(rand(), TRUE));
			
			$sql = 'UPDATE ' . NEWSLETTER . ' SET active_key = "' . $key . '" WHERE user_mail = "' . $row['user_mail'] . '"';
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			return $key;
		}
		$db->sql_freeresult($result);
		
		return false;
	}
	return false;
}

function check_mail_subscribe($email)
{
	global $db;
	
	if ( preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*?[a-z]+$/is', $email) )
	{
		if ( domain_exists($email) )
		{
			$sql = 'SELECT user_mail
						FROM ' . NEWSLETTER . '
						WHERE user_mail = "' . str_replace("\'", "''", $email) . '"';
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Couldn't obtain user email information.", "", __LINE__, __FILE__, $sql);
			}
			
			if ($row = $db->sql_fetchrow($result))
			{
				return false;
			}
			$db->sql_freeresult($result);
			
			return true;
		}
	}
	return false;
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
		return false;
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
		return false;
    }
}

?>