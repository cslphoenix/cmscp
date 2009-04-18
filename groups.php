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
	* @code:	Original Code von groupcp.php by phpBB2 © 2001 The phpBB Group
				   
***/

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_GROUPS);
init_userprefs($userdata);

//	Link
$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($config['script_path']));
$script_name = ( $script_name != '' ) ? $script_name . '/groups.php' : 'groups.php';
$server_name = trim($config['server_name']);
$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';
$server_url = $server_protocol . $server_name . $server_port . $script_name;

if ( isset($HTTP_GET_VARS[POST_GROUPS_URL]) || isset($HTTP_POST_VARS[POST_GROUPS_URL]) )
{
	$group_id = ( isset($HTTP_POST_VARS[POST_GROUPS_URL]) ) ? intval($HTTP_POST_VARS[POST_GROUPS_URL]) : intval($HTTP_GET_VARS[POST_GROUPS_URL]);
}
else
{
	$group_id = '';
}

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = '';
}

$confirm = ( isset($HTTP_POST_VARS['confirm']) ) ? TRUE : 0;
$cancel = ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : 0;
$sid = ( isset($HTTP_POST_VARS['sid']) ) ? $HTTP_POST_VARS['sid'] : '';
$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$is_moderator = FALSE;

if ( isset($HTTP_POST_VARS['joingroup']) && $group_id )
{
	//
	// First, joining a group
	// If the user isn't logged in redirect them to login
	//
	if ( !$userdata['session_logged_in'] )
	{
		redirect(append_sid("login.php?redirect=groups.php&" . POST_GROUPS_URL . "=$group_id", true));
	}
	else if ( $sid !== $userdata['session_id'] )
	{
		message_die(GENERAL_ERROR, $lang['Session_invalid']);
	}
	
	$sql = 'SELECT gu.user_id, g.group_type
		FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
		WHERE g.group_id = ' . $group_id . '
			AND g.group_type <> ' . GROUP_HIDDEN . '
			AND gu.group_id = g.group_id';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $row = $db->sql_fetchrow($result))
	{
		if ( $row['group_type'] == GROUP_OPEN || $row['group_type'] == GROUP_REQUEST )
		{
			if ( $userdata['user_id'] == $row['user_id'] )
			{
				$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("index.$phpEx") . '">'));
				
				$message = $lang['Already_member_group'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=$group_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			
			if ( $row['group_type'] == GROUP_OPEN )
			{
				$sql = 'INSERT INTO ' . GROUPS_USERS . ' (group_id, user_id, user_pending) VALUES (' . $group_id . ', ' . $userdata['user_id'] . ', 0)';
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				group_set_auth($userdata['user_id'], $group_id);
			}
			else if ( $row['group_type'] == GROUP_REQUEST )
			{
				$sql = 'INSERT INTO ' . GROUPS_USERS . ' (group_id, user_id, user_pending) VALUES (' . $group_id . ', ' . $userdata['user_id'] . ', 1)';
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				//
				//	Hinweis: PN Funktion hinzufügen!!!
				//
				$sql = 'SELECT u.user_email, u.username, u.user_lang, g.group_name
							FROM ' . USERS . ' u, ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
							WHERE u.user_id = gu.user_id
								AND g.group_id = gu.group_id
								AND gu.group_mod = 1
								AND gu.group_id = ' . $group_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Error getting group moderator data", "", __LINE__, __FILE__, $sql);
				}
			
				while ($email = $db->sql_fetchrow($result))
				{
					include($root_path . 'includes/emailer.php');
					$emailer = new emailer($config['smtp_delivery']);
					
					$emailer->from($config['page_email']);
					$emailer->replyto($config['page_email']);
					$emailer->use_template('group_request', $email['user_lang']);
					$emailer->email_address($email['user_email']);
					$emailer->set_subject($lang['Group_request']);
					
					$emailer->assign_vars(array(
						'SITENAME' => $config['sitename'],
						'GROUP_MODERATOR' => $email['username'],
						'EMAIL_SIG' => (!empty($config['page_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $config['page_email_sig']) : '', 
						'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . "=$group_id&validate=true",
					));
					
					$emailer->send();
					$emailer->reset();
				}
			}
			
//			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . append_sid('index.php') . '">'));
			
			$message = ( $row['group_type'] == GROUP_REQUEST ) ? $lang['group_msg_request'] : $lang['group_msg_open'];
			$message .= '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid("groups.php?" . POST_GROUPS_URL . "=$group_id") . '">', '</a>')
				. '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else if ( $group['group_type'] == GROUP_CLOSED || $group['group_type'] == GROUP_HIDDEN || $group['group_type'] == GROUP_SYSTEM )
		{
//			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . append_sid('index.php') . '">'));
			
			$message = $lang['This_closed_group'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid("groups.php?" . POST_GROUPS_URL . "=$group_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_groups_exist']); 
	}
}
else if ( isset($HTTP_POST_VARS['unsub']) || isset($HTTP_POST_VARS['unsubpending']) && $group_id )
{
	//
	// Second, unsubscribing from a group
	// Check for confirmation of unsub.
	//
	if ( $cancel )
	{
		redirect(append_sid("groups.php", true));
	}
	else if ( !$userdata['session_logged_in'] )
	{
		redirect(append_sid("login.php?redirect=groups.php&" . POST_GROUPS_URL . "=$group_id", true));
	}
	else if ( $sid !== $userdata['session_id'] )
	{
		message_die(GENERAL_ERROR, $lang['Session_invalid']);
	}

	if ( $confirm )
	{
		$sql = 'DELETE FROM ' . GROUPS_USERS . ' WHERE user_id = ' . $userdata['user_id'] . ' AND group_id = ' . $group_id;
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		group_reset_auth($userdata['user_id'], $group_id);
		
//		$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . append_sid('index.php') . '">'));

		$message = $lang['Unsub_success']
			. '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid("groups.php?" . POST_GROUPS_URL . "=$group_id") . '">', '</a>')
			. '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.php') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$unsub_msg = ( isset($HTTP_POST_VARS['unsub']) ) ? $lang['Confirm_unsub'] : $lang['Confirm_unsub_pending'];

		$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" /><input type="hidden" name="unsub" value="1" />';
		$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

		$page_title = $lang['Group_Control_Panel'];
		include($root_path . 'includes/page_header.php');

		$template->set_filenames(array('confirm' => 'confirm_body.tpl'));

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $unsub_msg,
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'S_CONFIRM_ACTION' => append_sid("groups.php"),
			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('confirm');

		include($root_path . 'includes/page_tail.php');
	}
}
else if ( $group_id )
{
	if ( isset($HTTP_GET_VARS['validate']) )
	{
		if ( !$userdata['session_logged_in'] )
		{
			redirect(append_sid("login.php?redirect=groups.php&" . POST_GROUPS_URL . "=$group_id", true));
		}
	}
	
	$sql = 'SELECT user_id
				FROM ' . GROUPS_USERS . '
				WHERE group_mod = 1
					AND group_id = ' . $group_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting user list for group', '', __LINE__, __FILE__, $sql);
	}
	$group_mods = $db->sql_fetchrowset($result);

	$sql = 'SELECT *
				FROM ' . GROUPS . '
				WHERE group_id = ' . $group_id . '
					AND group_type <> ' . GROUP_HIDDEN;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get moderator information', '', __LINE__, __FILE__, $sql);
	}
//	$group_info = _cached($sql, 'groups' . $group_id, 1);
//	if ( $group_info )
	if ( $group_info = $db->sql_fetchrow($result) )
	{
		if ( !empty($HTTP_POST_VARS['add']) || $mode == 'remove' || isset($HTTP_POST_VARS['approve']) || isset($HTTP_POST_VARS['deny']) || $mode == 'change_level' )
		{
			if ( $group_mods )
			{
				foreach ( $group_mods as $mod => $row )
				{
					if ( $row['user_id'] == $userdata['user_id'] )
					{
						$is_moderator = TRUE;
					}
				}
			}
			
			if ( !$userdata['session_logged_in'] )
			{
				redirect(append_sid("login.php?redirect=groups.php&" . POST_GROUPS_URL . "=$group_id", true));
			} 
			else if ( $sid !== $userdata['session_id'] )
			{
				message_die(GENERAL_ERROR, $lang['Session_invalid']);
			}

			if ( !$is_moderator && $userdata['user_level'] != ADMIN )
			{
//				$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . append_sid('index.php') . '">'));

				$message = $lang['Not_group_moderator'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}

			if ( isset($HTTP_POST_VARS['add']) )
			{
				$userid = ( isset($HTTP_POST_VARS['user_id']) ) ? intval($HTTP_POST_VARS['user_id']) : '';
				
				$sql = 'SELECT u.user_id, u.user_email, u.user_lang
							FROM ' . USERS . ' u, ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
							WHERE u.user_id = ' . $userid;
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$user = $db->sql_fetchrow($result);
				
				$user_id	= $user['user_id'];
				$user_email	= $user['user_email'];
				$user_lang	= $user['user_lang'];
				
				$sql = 'INSERT INTO ' . GROUPS_USERS . " (user_id, group_id, user_pending) VALUES ($user_id, $group_id, 0)";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				group_set_auth($user_id, $group_id);

				$group_name = $group_info['group_name'];

				include($root_path . 'includes/emailer.php');
				$emailer = new emailer($config['smtp_delivery']);

				$emailer->from($config['page_email']);
				$emailer->replyto($config['page_email']);

				$emailer->use_template('group_added', $user_lang);
				$emailer->email_address($user_email);
				$emailer->set_subject($lang['Group_added']);

				$emailer->assign_vars(array(
					'SITENAME' => $config['sitename'], 
					'GROUP_NAME' => $group_name,
					'EMAIL_SIG' => (!empty($config['page_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $config['page_email_sig']) : '', 

					'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . "=$group_id")
				);
				$emailer->send();
				$emailer->reset();
			}
			else if ( $mode == 'change_level' )
			{
				$members_select = array();
				$members_mark = count($HTTP_POST_VARS['members']);
				
				for ( $i = 0; $i < $members_mark; $i++ )
				{
					if ( intval($HTTP_POST_VARS['members'][$i]) )
					{
						$members_select[] = intval($HTTP_POST_VARS['members'][$i]);
					}
				}
				
				if ( count($members_select) > 0 )
				{
					$user_ids = implode(', ', $members_select);
					
					$sql = 'SELECT user_id
								FROM ' . GROUPS_USERS . '
								WHERE group_id = ' . $group_id . '
									AND group_mod = 1
									AND user_id IN (' . $user_ids . ')';
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$group_mods = array();
					while ( $row = $db->sql_fetchrow($result) )
					{
						$group_mods[] = $row['user_id'];
					}
					$db->sql_freeresult($result);

					if ( count($group_mods) > 0)
					{
						$sql = 'UPDATE ' . GROUPS_USERS . '
									SET group_mod = 0
									WHERE group_id = ' . intval($group_id) . '
										AND user_id IN (' . implode(', ', $group_mods) . ')';
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					$sql_in = ( empty($group_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $group_mods) . ')');
					
					$sql = 'UPDATE ' . GROUPS_USERS . '
								SET group_mod = 1
								WHERE group_id = ' . intval($group_id) . '
									AND user_id IN (' . implode(', ', $members_select) . ')' . $sql_in;
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					
//					$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("groups.php?" . POST_GROUPS_URL . "=$group_id") . '">'));
					
					$message = $lang['group_set_mod']
						. '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid("groups.php?" . POST_GROUPS_URL . "=$group_id") . '">', '</a>')
						. '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);

				}
			}
			else 
			{
				if ( ( ( isset($HTTP_POST_VARS['approve']) || isset($HTTP_POST_VARS['deny']) ) && isset($HTTP_POST_VARS['pending_members']) ) || ( $mode == 'remove' && isset($HTTP_POST_VARS['members']) ) )
				{
					$members = ( isset($HTTP_POST_VARS['approve']) || isset($HTTP_POST_VARS['deny']) ) ? $HTTP_POST_VARS['pending_members'] : $HTTP_POST_VARS['members'];

					$sql_in = '';
					for($i = 0; $i < count($members); $i++)
					{
						$sql_in .= ( ( $sql_in != '' ) ? ', ' : '' ) . intval($members[$i]);
					}

					if ( isset($HTTP_POST_VARS['approve']) )
					{
						$sql = 'UPDATE ' . GROUPS_USERS . '
									SET user_pending = 0
									WHERE user_id IN (' . $sql_in . ')
										AND group_id = ' . $group_id;
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						for( $k = 0; $k < count($members); $k++)
						{
							group_set_auth($members[$k]['user_id'], $group_id);
						}
						
						$sql_select = 'SELECT user_email FROM ' . USERS . ' WHERE user_id IN (' . $sql_in . ')';
					}
					else if ( isset($HTTP_POST_VARS['deny']) || $mode == 'remove' )
					{
						$sql = 'DELETE FROM ' . GROUPS_USERS . ' WHERE user_id IN (' . $sql_in . ') AND group_id = ' . $group_id;
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						for( $i = 0; $i < count($members); $i++ )
						{
							group_reset_auth($members[$i]['user_id'], $group_id);
						}
					}

					//
					// Email users when they are approved
					//
					if ( isset($HTTP_POST_VARS['approve']) )
					{
						if ( !($result = $db->sql_query($sql_select)) )
						{
							message_die(GENERAL_ERROR, 'Could not get user email information', '', __LINE__, __FILE__, $sql);
						}

						$bcc_list = array();
						while ($row = $db->sql_fetchrow($result))
						{
							$bcc_list[] = $row['user_email'];
						}

						$group_name = $group_info['group_name'];

						include($root_path . 'includes/emailer.php');
						$emailer = new emailer($config['smtp_delivery']);

						$emailer->from($config['page_email']);
						$emailer->replyto($config['page_email']);

						for ($i = 0; $i < count($bcc_list); $i++)
						{
							$emailer->bcc($bcc_list[$i]);
						}

						$emailer->use_template('group_approved');
						$emailer->set_subject($lang['Group_approved']);

						$emailer->assign_vars(array(
							'SITENAME' => $config['sitename'], 
							'GROUP_NAME' => $group_name,
							'EMAIL_SIG' => (!empty($config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $config['board_email_sig']) : '', 

							'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . "=$group_id")
						);
						$emailer->send();
						$emailer->reset();
					}
				}
			}
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_groups_exist']);
	}
	
	$page_title = $lang['Group_Control_Panel'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'body_groups.tpl'));
	$template->assign_block_vars('details', array());
	
	$sql = 'SELECT u.username, u.user_id, u.user_viewemail, u.user_posts, u.user_regdate, u.user_email, gu.user_pending, gu.group_mod
				FROM ' . USERS . ' u, ' . GROUPS_USERS . ' gu
				WHERE gu.group_id = ' . $group_id . '
					AND u.user_id = gu.user_id
					AND gu.user_pending = 0
				ORDER BY u.username';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting user list for group', '', __LINE__, __FILE__, $sql);
	}
	$group_members = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	$sql = 'SELECT u.username, u.user_id, u.user_viewemail, u.user_posts, u.user_regdate, u.user_email
				FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu, ' . USERS . ' u
				WHERE gu.group_id = ' . $group_id . '
					AND g.group_id = gu.group_id
					AND gu.user_pending = 1
					AND u.user_id = gu.user_id
				ORDER BY u.username';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting user pending information', '', __LINE__, __FILE__, $sql);
	}

	$modgroup_pending_list = $db->sql_fetchrowset($result);
	$modgroup_pending_count = count($modgroup_pending_list);
	$db->sql_freeresult($result);
	
	$is_group_member = 0;
	if ( count($group_members) )
	{
		for($i = 0; $i < count($group_members); $i++)
		{
			if ( $group_members[$i]['user_id'] == $userdata['user_id'] && $userdata['session_logged_in'] )
			{
				$is_group_member = TRUE; 
			}
			
			if ( $group_members[$i]['user_id'] == $userdata['user_id'] && $group_members[$i]['group_mod'] && $userdata['session_logged_in'] )
			{
				$is_moderator = TRUE;
			}
		}
	}

	$is_group_pending_member = 0;
	if ( $modgroup_pending_count )
	{
		for($i = 0; $i < $modgroup_pending_count; $i++)
		{
			if ( $modgroup_pending_list[$i]['user_id'] == $userdata['user_id'] && $userdata['session_logged_in'] )
			{
				$is_group_pending_member = TRUE;
			}
		}
	}

	if ( $is_moderator )
	{
		if ( $group_info['group_type'] != GROUP_SYSTEM )
		{
			$template->assign_block_vars('details.switch_unsubscribe_group_input', array());
		}
		
		$group_details = $lang['Are_group_moderator'];
		$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
	}
	else if ( $is_group_member || $is_group_pending_member )
	{
		if ( $group_info['group_type'] != GROUP_SYSTEM )
		{
			$template->assign_block_vars('details.switch_unsubscribe_group_input', array());
		}

		$group_details =  ( $is_group_pending_member ) ? $lang['Pending_this_group'] : $lang['Member_this_group'];
		$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
	}
	else if ( $userdata['user_id'] == ANONYMOUS )
	{
		$group_details =  $lang['Login_to_join'];
		$s_hidden_fields = '';
	}
	else
	{
		if ( $group_info['group_type'] == GROUP_OPEN )
		{
			$template->assign_block_vars('details.switch_subscribe_group_input', array());

			$group_details =  $lang['This_open_group'];
			$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
		}
		else if ( $group_info['group_type'] == GROUP_REQUEST )
		{
			$template->assign_block_vars('details.switch_subscribe_group_input', array());
			
			$group_details =  $lang['This_request_group'];
			$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
		}
		else if ( $group_info['group_type'] == GROUP_CLOSED )
		{
			$group_details =  $lang['This_closed_group'];
			$s_hidden_fields = '';
		}
		else if ( $group_info['group_type'] == GROUP_HIDDEN )
		{
			$group_details =  $lang['This_hidden_group'];
			$s_hidden_fields = '';
		}
		else if ( $group_info['group_type'] == GROUP_SYSTEM )
		{
			$group_details =  $lang['This_system_group'];
			$s_hidden_fields = '';
		}
	}
	
	
/*	
	$template->set_filenames(array(
		'info'			=> 'groupcp_info_body.tpl', 
		'pendinginfo'	=> 'groupcp_pending_info.tpl')
	);
*/	
	$sql_id = '';
	
	if ( $group_members )
	{
		foreach ($group_members as $member )
		{
			$ids[] = $member['user_id'];
		}
		
		$sql_id .= " AND NOT user_id IN (" . implode(', ', $ids) . ")";
	}
	
	$sql = 'SELECT username, user_id
				FROM ' . USERS . '
				WHERE user_id <> ' . ANONYMOUS . $sql_id;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
	}
	
	$select_users = '<select class="postselect" name="user_id">';
	$select_users .= '<option value="0">&raquo; Benutzer auswählen</option>';

	while ($row = $db->sql_fetchrow($result))
	{
		$select_users .= '<option value="' . $row['user_id'] . '" >&raquo; '. $row['username'] . '&nbsp;</option>';
	}
	$select_users .= '</select>';
	
	
	$select_options = '<select class="postselect" name="mode">';
	$select_options .= '<option value="0">&raquo; Option auswählen</option>';
	$select_options .= '<option value="remove">&raquo; Entfernen</option>';
	$select_options .= '<option value="change_level">&raquo; Gruppenrechte geben/nehmen</option>';
	$select_options .= '</select>';
	
	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

	$template->assign_vars(array(
		'L_GROUP_INFORMATION' => $lang['Group_Information'],
		'L_GROUP_NAME' => $lang['Group_name'],
		'L_GROUP_DESC' => $lang['Group_description'],
		'L_GROUP_TYPE' => $lang['Group_type'],
		'L_GROUP_MEMBERSHIP' => $lang['Group_membership'],
		'L_SUBSCRIBE' => $lang['Subscribe'],
		'L_UNSUBSCRIBE' => $lang['Unsubscribe'],
		'L_JOIN_GROUP' => $lang['Join_group'], 
		'L_UNSUBSCRIBE_GROUP' => $lang['Unsubscribe'], 
		'L_GROUP_OPEN' => $lang['Group_open'],
		'L_GROUP_REQUEST' => $lang['Group_quest'],
		'L_GROUP_CLOSED' => $lang['Group_closed'],
		'L_GROUP_HIDDEN' => $lang['Group_hidden'], 
		'L_UPDATE' => $lang['Update'], 
		'L_GROUP_MODERATOR' => $lang['Group_Moderator'], 
		'L_GROUP_MEMBERS' => $lang['Group_Members'], 
		'L_PENDING_MEMBERS' => $lang['Pending_members'], 
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'], 
		'L_PM' => $lang['Private_Message'], 
		'L_EMAIL' => $lang['Email'], 
		'L_POSTS' => $lang['Posts'], 
		'L_WEBSITE' => $lang['Website'],
		'L_FROM' => $lang['Location'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],
		'L_SUBMIT' => $lang['Sort'],
		'L_AIM' => $lang['AIM'],
		'L_YIM' => $lang['YIM'],
		'L_MSNM' => $lang['MSNM'],
		'L_ICQ' => $lang['ICQ'],
		'L_SELECT' => $lang['Select'],
		'L_REMOVE_SELECTED' => $lang['Remove_selected'],
		'L_ADD_MEMBER' => $lang['Add_member'],
		'L_FIND_USERNAME' => $lang['Find_username'],
		
		'L_JOINED'	=> $lang['Joined'],

		'GROUP_NAME' => $group_info['group_name'],
		'GROUP_DESC' => $group_info['group_description'],
		'GROUP_DETAILS' => $group_details,


		'S_HIDDEN_FIELDS' => $s_hidden_fields, 
//		'S_MODE_SELECT' => $select_sort_mode,
//		'S_ORDER_SELECT' => $select_sort_order,
		'S_SELECT_USERS'	=> $select_users,
		'S_SELECT_OPTION'	=> $select_options,
		'S_GROUPS_ACTION' => append_sid("groups.php?" . POST_GROUPS_URL . "=$group_id")
	));

	$groups_mods = $groups_nomods = array();
	
	if ( $group_members )
	{
		foreach ( $group_members as $member => $row )
		{
			if ( $row['group_mod'] )
			{
				$groups_mods[] = $row;
			}
			else
			{
				$groups_nomods[] = $row;
			}
		}
	}
	
	if ( $groups_mods )
	{
		if ( $userdata['user_level'] == ADMIN && $group_info['group_type'] != GROUP_SYSTEM )
		{
			$template->assign_block_vars('details.switch_h_admin_option', array());
			$template->assign_block_vars('details.switch_h_mod_option', array());
		}
		
		for ( $j = 0; $j < count($groups_mods); $j++ )
		{
			$username	= $groups_mods[$j]['username'];
			$user_id	= $groups_mods[$j]['user_id'];
	
			generate_user_info($groups_mods[$j], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);
	
			if ( $group_info['group_type'] != GROUP_HIDDEN || $is_group_member || $is_moderator || $userdata['user_level'] == ADMIN )
			{
				$row_color = ( !($j % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
				$row_class = ( !($j % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
				$template->assign_block_vars('details.mod_row', array(
					'ROW_COLOR' => '#' . $row_color,
					'ROW_CLASS' => $row_class,
					'USERNAME' => $username,
					'JOINED' => $joined,
					'POSTS' => $posts,
					'USER_ID' => $user_id, 
					'PROFILE_IMG' => $profile_img, 
					'PROFILE' => $profile, 
					'PM_IMG' => $pm_img,
					'PM' => $pm,
					'EMAIL_IMG' => $email_img,
					'EMAIL' => $email,
					
					'U_VIEWPROFILE' => append_sid("profile.php?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id"))
				);
	
				if ( $userdata['user_level'] == ADMIN && $group_info['group_type'] != GROUP_SYSTEM )
				{
					$template->assign_block_vars('details.mod_row.switch_admin_option', array());
				}
			}
		}
	}
	else
	{
		$template->assign_block_vars('details.switch_no_moderators', array());
		$template->assign_vars(array('L_NO_MODERATORS' => $lang['group_no_moderators']));
	}

	if ( $groups_nomods )
	{
		if ( $is_moderator && $group_info['group_type'] != GROUP_SYSTEM )
		{
			$template->assign_block_vars('details.switch_h_mod_option', array());
		}
		
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($groups_nomods)); $i++ )
		{
			$username	= $groups_nomods[$i]['username'];
			$user_id	= $groups_nomods[$i]['user_id'];
	
			generate_user_info($groups_nomods[$i], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);
	
			if ( $group_info['group_type'] != GROUP_HIDDEN || $is_group_member || $is_moderator )
			{
				$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
				$template->assign_block_vars('details.member_row', array(
					'ROW_COLOR' => '#' . $row_color,
					'ROW_CLASS' => $row_class,
					'USERNAME' => $username,
					'FROM' => $from,
					'JOINED' => $joined,
					'POSTS' => $posts,
					'USER_ID' => $user_id, 
					'AVATAR_IMG' => $poster_avatar,
					'PROFILE_IMG' => $profile_img, 
					'PROFILE' => $profile, 
					'SEARCH_IMG' => $search_img,
					'SEARCH' => $search,
					'PM_IMG' => $pm_img,
					'PM' => $pm,
					'EMAIL_IMG' => $email_img,
					'EMAIL' => $email,
					'WWW_IMG' => $www_img,
					'WWW' => $www,
					'ICQ_STATUS_IMG' => $icq_status_img,
					'ICQ_IMG' => $icq_img, 
					'ICQ' => $icq, 
					'AIM_IMG' => $aim_img,
					'AIM' => $aim,
					'MSN_IMG' => $msn_img,
					'MSN' => $msn,
					'YIM_IMG' => $yim_img,
					'YIM' => $yim,
					
					'U_VIEWPROFILE' => append_sid("profile.php?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id"))
				);
				
				if ( $is_moderator || $userdata['user_level'] == ADMIN && $group_info['group_type'] != GROUP_SYSTEM )
				{
					$template->assign_block_vars('details.member_row.switch_mod_option', array());
				}
			}
		}
	}
	else
	{
		$template->assign_block_vars('details.switch_no_members', array());
		$template->assign_vars(array('L_NO_MEMBERS' => $lang['group_no_members']));
	}
	
	$current_page = ( !count($group_members) ) ? 1 : ceil( count($group_members) / $settings['site_entry_per_page'] );

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("groups.php?" . POST_GROUPS_URL . "=$group_id", count($group_members), $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 
		'L_GOTO_PAGE' => $lang['Goto_page']
	));

	if ( $group_info['group_type'] == GROUP_HIDDEN && !$is_group_member && !$is_moderator )
	{
		$template->assign_block_vars('details.switch_hidden_group', array());
		$template->assign_vars(array('L_HIDDEN_MEMBERS' => $lang['Group_hidden_members']));
	}

	if ( $modgroup_pending_count )
	{
		if ( $is_moderator || $userdata['user_level'] == ADMIN )
		{
			$template->assign_block_vars('details.pending', array());
		
		
			for ( $i = 0; $i < $modgroup_pending_count; $i++ )
			{
				$username = $modgroup_pending_list[$i]['username'];
				$user_id = $modgroup_pending_list[$i]['user_id'];

				generate_user_info($modgroup_pending_list[$i], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);

				$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$user_select = '<input type="checkbox" name="member[]" value="' . $user_id . '">';

				$template->assign_block_vars('details.pending.pending_row', array(
					'ROW_CLASS' => $row_class,
					'ROW_COLOR' => '#' . $row_color, 
					'USERNAME' => $username,
					'FROM' => $from,
					'JOINED' => $joined,
					'POSTS' => $posts,
					'USER_ID' => $user_id, 
					'AVATAR_IMG' => $poster_avatar,
					'PROFILE_IMG' => $profile_img, 
					'PROFILE' => $profile, 
					'SEARCH_IMG' => $search_img,
					'SEARCH' => $search,
					'PM_IMG' => $pm_img,
					'PM' => $pm,
					'EMAIL_IMG' => $email_img,
					'EMAIL' => $email,
					'WWW_IMG' => $www_img,
					'WWW' => $www,
					'ICQ_STATUS_IMG' => $icq_status_img,
					'ICQ_IMG' => $icq_img, 
					'ICQ' => $icq, 
					'AIM_IMG' => $aim_img,
					'AIM' => $aim,
					'MSN_IMG' => $msn_img,
					'MSN' => $msn,
					'YIM_IMG' => $yim_img,
					'YIM' => $yim,
					
					'U_VIEWPROFILE' => append_sid("profile.php?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id"))
				);
			}

			$template->assign_vars(array(
				'L_SELECT' => $lang['Select'],
				'L_APPROVE_SELECTED' => $lang['Approve_selected'],
				'L_DENY_SELECTED' => $lang['Deny_selected'])
			);
		
		}
	}

	if ( $is_moderator || $userdata['user_level'] == ADMIN && $group_info['group_type'] != GROUP_SYSTEM )
	{
		$template->assign_block_vars('details.switch_add_member', array());
	}
	
	$template->pparse('body');
}
else
{
	$page_title = $lang['Group_Control_Panel'];
	include($root_path . 'includes/page_header.php');
	
//	$template->set_filenames(array('user' => 'groupcp_user_body.tpl'));
	$template->set_filenames(array('body' => 'body_groups.tpl'));
	$template->assign_block_vars('select', array());

	if ( $userdata['session_logged_in'] )
	{
		$sql = 'SELECT g.group_id, g.group_name, g.group_type, g.group_description, gu.user_pending
					FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
					WHERE gu.user_id = ' . $userdata['user_id'] . '
						AND gu.group_id = g.group_id
						AND g.group_single_user <> ' . TRUE . '
					ORDER BY g.group_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
		}
		$groups_in = $db->sql_fetchrowset($result);
		
		$groups_member = $groups_pending = array();
		
		if ( $groups_in )
		{
			$template->assign_block_vars('select.joined', array());
			
			foreach ( $groups_in as $group => $row )
			{
				$in_group[] = $row['group_id'];
				
				if ( $row['user_pending'] )
				{
					$groups_pending[] = $row;
				}
				else
				{
					$groups_member[] = $row;
				}
			}
				
			if ( $groups_member )
			{
				$template->assign_block_vars('select.joined.member', array());
				
				for ($i = 0; $i < count($groups_member); $i++)
				{
					switch ( $groups_member[$i]['group_type'] )
					{
						case GROUP_OPEN:
							$group_type = $lang['Group_open'];
						break;
						
						case GROUP_REQUEST:
							$group_type = $lang['Group_quest'];
						break;
					
						case GROUP_CLOSED:
							$group_type = $lang['Group_closed'];
						break;
						
						case GROUP_HIDDEN:
							$group_type = $lang['Group_hidden'];
						break;
						
						case GROUP_SYSTEM:
							$group_type = $lang['Group_system'];
						break;
					}
					
					$template->assign_block_vars('select.joined.member.grouprow', array(
						'U_GROUP' => append_sid('groups.php?' . POST_GROUPS_URL . '=' . $groups_member[$i]['group_id']),
						'GROUP_NAME' => $groups_member[$i]['group_name'],
						'GROUP_DESC' => $groups_member[$i]['group_description'],
						'GROUP_TYPE' => $group_type,
					));
				}
			}
			
			if ( $groups_pending )
			{
				$template->assign_block_vars('select.joined.pending', array());
				
				for ($i = 0; $i < count($groups_member); $i++)
				{
					switch ( $groups_pending[$i]['group_type'] )
					{
						case GROUP_OPEN:
							$group_type = $lang['Group_open'];
						break;
						
						case GROUP_REQUEST:
							$group_type = $lang['Group_quest'];
						break;
					
						case GROUP_CLOSED:
							$group_type = $lang['Group_closed'];
						break;
						
						case GROUP_HIDDEN:
							$group_type = $lang['Group_hidden'];
						break;
						
						case GROUP_SYSTEM:
							$group_type = $lang['Group_system'];
						break;
					}
					
					$template->assign_block_vars('select.joined.pending.grouprow', array(
						'U_GROUP' => append_sid('groups.php?' . POST_GROUPS_URL . '=' . $groups_pending[$i]['group_id']),
						'GROUP_NAME' => $groups_pending[$i]['group_name'],
						'GROUP_DESC' => $groups_pending[$i]['group_description'],
						'GROUP_TYPE' => $group_type,
					));
				}
			}
		}
	}

	$ignore_group_sql = isset($in_group) ? ( count($in_group) ) ? 'AND group_id NOT IN (' . implode(', ', $in_group) . ')' : '' : '';
	$sql = 'SELECT group_id, group_name, group_type, group_description
				FROM ' . GROUPS . ' g
				WHERE group_single_user <> ' . TRUE . "
					$ignore_group_sql
				ORDER BY g.group_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
	}
	$groups_out = $db->sql_fetchrowset($result);

	while( $row = $db->sql_fetchrow($result) )
	{
		if  ( $row['group_type'] != GROUP_HIDDEN || $userdata['user_level'] == ADMIN )
		{
			$group_list_out[$row['group_id']] = $row;
		}
	}
	
	if ( $groups_out )
	{
		$template->assign_block_vars('select.remaining', array());
		
		for ($i = 0; $i < count($groups_out); $i++)
		{
			switch( $groups_out[$i]['group_type'] )
			{
				case GROUP_OPEN:
					$group_type = $lang['Group_open'];
				break;
				
				case GROUP_REQUEST:
					$group_type = $lang['Group_quest'];
				break;
			
				case GROUP_CLOSED:
					$group_type = $lang['Group_closed'];
				break;
				
				case GROUP_HIDDEN:
					$group_type = $lang['Group_hidden'];
				break;
				
				case GROUP_SYSTEM:
					$group_type = $lang['Group_system'];
				break;
			}
			
			$template->assign_block_vars('select.remaining.grouprow', array(
				'U_GROUP' => append_sid('groups.php?' . POST_GROUPS_URL . '=' . $groups_out[$i]['group_id']),
				'GROUP_NAME' => $groups_out[$i]['group_name'],
				'GROUP_DESC' => $groups_out[$i]['group_description'],
				'GROUP_TYPE' => $group_type,
			));
		}
	}
		
	$template->assign_vars(array(
		'L_GROUP_MEMBERSHIP_DETAILS' => $lang['Group_member_details'],
		'L_JOIN_A_GROUP' => $lang['Group_member_join'],
		'L_YOU_BELONG_GROUPS' => $lang['Current_memberships'],
		'L_SELECT_A_GROUP' => $lang['Non_member_groups'],
		'L_PENDING_GROUPS' => $lang['Memberships_pending'],
		'L_SUBSCRIBE' => $lang['Subscribe'],
		'L_UNSUBSCRIBE' => $lang['Unsubscribe'],
		'L_VIEW_INFORMATION' => $lang['View_Information']
	));
	
	$template->pparse('body');
}

include($root_path . 'includes/page_tail.php');

?>