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

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_TEAMS);
init_userprefs($userdata);

if ( isset($HTTP_POST_VARS[POST_TEAMS_URL]) || isset($HTTP_GET_VARS[POST_TEAMS_URL]) )
{
	$team_id = ( isset($HTTP_POST_VARS[POST_TEAMS_URL]) ) ? intval($HTTP_POST_VARS[POST_TEAMS_URL]) : intval($HTTP_GET_VARS[POST_TEAMS_URL]);
}
else
{
	$team_id = '';
}

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}
/*
$sid		= ( isset($HTTP_POST_VARS['sid']) ) ? $HTTP_POST_VARS['sid'] : '';
$start		= ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start		= ( $start < 0 ) ? 0 : $start;
$confirm	= ( isset($HTTP_POST_VARS['confirm']) ) ? TRUE : 0;
$cancel		= ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : 0;
*/
$confirm	= ( isset($HTTP_POST_VARS['confirm']) ) ? TRUE : 0;
$cancel		= ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : 0;
$sid		= ( isset($HTTP_POST_VARS['sid']) ) ? $HTTP_POST_VARS['sid'] : '';
$start		= ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start		= ( $start < 0 ) ? 0 : $start;

$is_moderator = FALSE;

//if ( $mode == 'view' && intval($HTTP_GET_VARS[POST_TEAMS_URL]) )
if ( $team_id )
{
//	$page_title = $lang['team'];
	$template->set_filenames(array('body' => 'body_teams.tpl'));
	$template->assign_block_vars('details', array());
	
	if ( isset($HTTP_GET_VARS['validate']) )
	{
		if ( !$userdata['session_logged_in'] )
		{
			redirect(append_sid('login.php?redirect=teams.php&' . POST_TEAMS_URL . '=' . $team_id, true));
		}
	}
	
	$sql = 'SELECT user_id
				FROM ' . TEAMS_USERS . '
				WHERE team_mod = 1
					AND team_id = ' . $team_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'Error getting user list for group', '', __LINE__, __FILE__, $sql);
	}
	$team_mods = $db->sql_fetchrowset($result);
	
	$sql = 'SELECT t.*, g.game_size, g.game_image
				FROM ' . TEAMS . ' t
					LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
				WHERE t.team_id = ' . $team_id;
//	$team = _cached($sql, 'team_details_' . $team_id . '_member', 1);
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$team = $db->sql_fetchrow($result);
	
	// add krams
	if ( !empty($HTTP_POST_VARS['add']) || $mode == 'remove' || isset($HTTP_POST_VARS['approve']) || isset($HTTP_POST_VARS['deny']) || $mode == 'change_level' )
	{
		if ( $team_mods )
		{
			foreach ( $team_mods as $mod => $row )
			{
				if ( $row['user_id'] == $userdata['user_id'] )
				{
					$is_moderator = TRUE;
				}
			}
		}
		
		if ( !$userdata['session_logged_in'] )
		{
			redirect(append_sid('login.php?redirect=teams.php&' . POST_GROUPS_URL . '=' . $team_id, true));
		} 
		else if ( $sid !== $userdata['session_id'] )
		{
			message(GENERAL_ERROR, $lang['Session_invalid']);
		}

		if ( !$is_moderator && $userdata['user_level'] != ADMIN )
		{
			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . append_sid('index.php') . '">'));

			$message = $lang['Not_group_moderator'] . '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.php') . '">', '</a>');
			message(GENERAL_MESSAGE, $message);
		}

		if ( isset($HTTP_POST_VARS['add']) )
		{
			$userid = ( isset($HTTP_POST_VARS['user_id']) ) ? intval($HTTP_POST_VARS['user_id']) : '';
			
			$sql = 'SELECT u.user_id, u.user_email, u.user_lang
						FROM ' . USERS . ' u, ' . TEAMS . ' t, ' . TEAMS_USERS . ' tu
						WHERE u.user_id = ' . $userid;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$user = $db->sql_fetchrow($result);
			
			$user_id	= $user['user_id'];
			$user_email	= $user['user_email'];
			$user_lang	= $user['user_lang'];
			
			$sql = 'INSERT INTO ' . TEAMS_USERS . " (user_id, team_id, rank_id, team_join, team_mod) VALUES ($user_id, $team_id, 0)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			group_set_auth($user_id, $team_id);

			$team_name = $team_info['team_name'];

		/*
			include($root_path . 'includes/class_emailer.php');
			$emailer = new emailer($config['smtp_delivery']);

			$emailer->from($config['page_email']);
			$emailer->replyto($config['page_email']);

			$emailer->use_template('group_added', $user_lang);
			$emailer->email_address($user_email);
			$emailer->set_subject($lang['Group_added']);

			$emailer->assign_vars(array(
				'SITENAME' => $config['page_name'], 
				'GROUP_NAME' => $group_name,
				'EMAIL_SIG' => (!empty($config['page_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $config['page_email_sig']) : '', 

				'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . "=$group_id")
			);
			$emailer->send();
			$emailer->reset();
		*/
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
							WHERE team_id = ' . $team_id . '
								AND team_mod = 1
								AND user_id IN (' . $user_ids . ')';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$team_mods = array();
				while ( $row = $db->sql_fetchrow($result) )
				{
					$team_mods[] = $row['user_id'];
				}
				$db->sql_freeresult($result);

				if ( count($team_mods) > 0)
				{
					$sql = 'UPDATE ' . GROUPS_USERS . '
								SET team_mod = 0
								WHERE team_id = ' . intval($team_id) . '
									AND user_id IN (' . implode(', ', $team_mods) . ')';
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				$sql_in = ( empty($team_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $team_mods) . ')');
				
				$sql = 'UPDATE ' . GROUPS_USERS . '
							SET team_mod = 1
							WHERE team_id = ' . intval($team_id) . '
								AND user_id IN (' . implode(', ', $members_select) . ')' . $sql_in;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				
//					$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . append_sid('teams.php?' . POST_GROUPS_URL . '=' . $team_id) . '">'));
				
				$message = $lang['group_set_mod']
					. '<br><br>' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('teams.php?' . POST_GROUPS_URL . '=' . $team_id) . '">', '</a>')
					. '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.php') . '">', '</a>');
				message(GENERAL_MESSAGE, $message);

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
									AND team_id = ' . $team_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					for( $k = 0; $k < count($members); $k++)
					{
						group_set_auth($members[$k]['user_id'], $team_id);
					}
					
					$sql_select = 'SELECT user_email FROM ' . USERS . ' WHERE user_id IN (' . $sql_in . ')';
				}
				else if ( isset($HTTP_POST_VARS['deny']) || $mode == 'remove' )
				{
					$sql = 'DELETE FROM ' . GROUPS_USERS . ' WHERE user_id IN (' . $sql_in . ') AND team_id = ' . $team_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					for( $i = 0; $i < count($members); $i++ )
					{
						group_reset_auth($members[$i]['user_id'], $team_id);
					}
				}

				//
				// Email users when they are approved
				//
				if ( isset($HTTP_POST_VARS['approve']) )
				{
					if ( !($result = $db->sql_query($sql_select)) )
					{
						message(GENERAL_ERROR, 'Could not get user email information', '', __LINE__, __FILE__, $sql);
					}

					$bcc_list = array();
					while ($row = $db->sql_fetchrow($result))
					{
						$bcc_list[] = $row['user_email'];
					}

					$team_name = $team_info['team_name'];
					
				/*

					include($root_path . 'includes/class_emailer.php');
					$emailer = new emailer($config['smtp_delivery']);

					$emailer->from($config['page_email']);
					$emailer->replyto($config['page_email']);

					for ($i = 0; $i < count($bcc_list); $i++)
					{
						$emailer->bcc($bcc_list[$i]);
					}

					$emailer->use_template('group_approved');
					$emailer->set_subject($lang['Group_approved']);
					
				*/

					$emailer->assign_vars(array(
						'SITENAME' => $config['page_name'], 
						'GROUP_NAME' => $group_name,
						'EMAIL_SIG' => (!empty($config['board_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $config['board_email_sig']) : '', 

						'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . "=$group_id")
					);
					$emailer->send();
					$emailer->reset();
				}
			}
		}
	}

	
//	debug($team);
	
	$sql = 'SELECT u.username, u.user_id, u.user_viewemail, u.user_posts, u.user_regdate, u.user_email, tu.team_mod
				FROM ' . USERS . ' u, ' . TEAMS_USERS . ' tu
				WHERE tu.team_id = ' . $team_id . '
					AND u.user_id = tu.user_id
				ORDER BY u.username';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'Error getting user list for group', '', __LINE__, __FILE__, $sql);
	}
	$team_members = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	$is_team_member = 0;
	if ( count($team_members) )
	{
		for($i = 0; $i < count($team_members); $i++)
		{
			if ( $team_members[$i]['user_id'] == $userdata['user_id'] && $userdata['session_logged_in'] )
			{
				$is_group_member = TRUE; 
			}
			
			if ( $team_members[$i]['user_id'] == $userdata['user_id'] && $team_members[$i]['team_mod'] && $userdata['session_logged_in'] )
			{
				$is_moderator = TRUE;
			}
		}
	}
	
	$teams_mods = $teams_nomods = array();
	
	if ( $team_members )
	{
		foreach ( $team_members as $member => $row )
		{
			if ( $row['team_mod'] )
			{
				$teams_mods[] = $row;
			}
			else
			{
				$teams_nomods[] = $row;
			}
		}
	}
	
	if ( $teams_mods )
	{
		for ( $j = 0; $j < count($teams_mods); $j++ )
		{
			$username	= $teams_mods[$j]['username'];
			$user_id	= $teams_mods[$j]['user_id'];
	
			generate_user_info($teams_mods[$j], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);
	
			$row_class = ( !($j % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('details.mod_row', array(
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
				
				'U_VIEWPROFILE' => append_sid('profile.php?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id)
			));
			
			if ( $is_moderator )
			{
				$template->assign_block_vars('details.mod_row.switch_mod_option', array());
			}
		}
	}
	else
	{
		$template->assign_block_vars('details.switch_no_moderators', array());
		$template->assign_vars(array('L_NO_MODERATORS' => $lang['group_no_moderators']));
	}

	if ( $teams_nomods )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($teams_nomods)); $i++ )
		{
			$username	= $teams_nomods[$i]['username'];
			$user_id	= $teams_nomods[$i]['user_id'];
	
			generate_user_info($teams_nomods[$i], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);
	
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('details.member_row', array(
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
				
				'U_VIEWPROFILE' => append_sid('profile.php?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id)
			));
			
			if ( $is_moderator )
			{
				$template->assign_block_vars('details.member_row.switch_mod_option', array());
			}
		}
	}
	else
	{
		$template->assign_block_vars('details.switch_no_members', array());
		$template->assign_vars(array('L_NO_MEMBERS' => $lang['group_no_members']));
	}
	
	if ( $is_moderator || $userdata['user_level'] == ADMIN )
	{
		$template->assign_block_vars('details.switch_mod_option', array());
	}
	
	$current_page = ( !count($team_members) ) ? 1 : ceil( count($team_members) / $settings['site_entry_per_page'] );
	
	
	$sql_id = '';
	
	if ( $team_members )
	{
		foreach ($team_members as $member )
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
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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
	
	$s_hidden_fields = '<input type="hidden" name="' . POST_TEAMS_URL . '" value="' . $team_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
	
	$template->assign_vars(array(
		'PAGINATION' => generate_pagination('teams.php?' . POST_GROUPS_URL . "=$team_id", count($team_members), $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 
		'L_GOTO_PAGE' => $lang['Goto_page'],
		
		'L_TEAM_VIEW'		=> $lang['team_view'],
		'L_TEAM_MODERATOR'	=> $lang['team_moderator'],
		'L_TEAM_MEMBERS'	=> $lang['team_member'],
								 
								 
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

		'GROUP_NAME' => $team['team_name'],
		'GROUP_DESC' => $team['team_description'],


		'S_HIDDEN_FIELDS' => $s_hidden_fields, 
//		'S_MODE_SELECT' => $select_sort_mode,
//		'S_ORDER_SELECT' => $select_sort_order,
		'S_SELECT_USERS'	=> $select_users,
		'S_SELECT_OPTION'	=> $select_options,
		'S_ACTION' => append_sid('teams.php?' . POST_TEAMS_URL . '=' . $team_id)
	));
	
}
else
{
	$page_title = $lang['teams'];
	
	$template->set_filenames(array('body' => 'body_teams.tpl'));
	$template->assign_block_vars('select', array());
	
	$sql = 'SELECT DISTINCT g.*
				FROM ' . GAMES . ' g, ' . TEAMS . ' t
				WHERE g.game_id = t.team_game
			ORDER BY game_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$games = $db->sql_fetchrowset($result);
//	$games = _cached($sql, 'info_games', 0);

	$sql = 'SELECT *
				FROM ' . TEAMS . '
			ORDER BY team_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$teams = $db->sql_fetchrowset($result);
//	$teams = _cached($sql, 'info_teams', 0);

	for ( $i = 0; $i < count($games); $i++ )
	{
		$game_id = $games[$i]['game_id'];
		
		$template->assign_block_vars('select.game_row', array(
			'L_GAME_NAME'	=> $games[$i]['game_name'],
			'GAME_IMAGE'	=> display_gameicon('12', $games[$i]['game_image']),
		));
															 
		for ( $j = 0; $j < count($teams); $j++ )
		{
			if ( $teams[$j]['team_game'] == $game_id )
			{
				$team_id = $teams[$j]['team_id'];
			
				$template->assign_block_vars('select.game_row.team_row', array(
					'TEAM_NAME'		=> $teams[$j]['team_name'],
					'TEAM_MATCH'	=> '<a href="' . append_sid('match.php?mode=teammatches&amp;' . POST_TEAMS_URL . '=' . $team_id) . '">' . $lang['all_matches'] . '</a>',
					'TEAM_JOINUS'	=> ( $teams[$j]['team_join'] )	? '<a href="' . append_sid('contact.php?mode=joinus&amp;' . POST_TEAMS_URL . '=' . $team_id) . '">' . $lang['match_joinus'] . '</a>'  : '',
					'TEAM_FIGHTUS'	=> ( $teams[$j]['team_fight'] )	? '<a href="' . append_sid('contact.php?mode=fightus&amp;' . POST_TEAMS_URL . '=' . $team_id) . '">' . $lang['match_fightus'] . '</a>'  : '',
					'TO_TEAM'		=> append_sid('teams.php?mode=view&amp;' . POST_TEAMS_URL . '=' . $teams[$j]['team_id']),
				));
			}
		}
	}
}

include($root_path . 'includes/page_header.php');

debug($_POST);

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>