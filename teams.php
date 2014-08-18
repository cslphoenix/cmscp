<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_TEAMS);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_TEAM;
$url	= POST_TEAMS;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, INT);	
$mode	= request('mode', TXT);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_teams.tpl',
	'error'		=> 'info_error.tpl',
));

/*
$sid		= ( isset($HTTP_POST_VARS['sid']) ) ? $HTTP_POST_VARS['sid'] : '';
$start		= ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start		= ( $start < 0 ) ? 0 : $start;
$confirm	= ( isset($HTTP_POST_VARS['confirm']) ) ? TRUE : 0;
$cancel		= ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : 0;
*/
#$confirm	= ( isset($HTTP_POST_VARS['confirm']) ) ? TRUE : 0;
#$cancel		= ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : 0;
#$sid		= ( isset($HTTP_POST_VARS['sid']) ) ? $HTTP_POST_VARS['sid'] : '';
#$start		= ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
#$start		= ( $start < 0 ) ? 0 : $start;

#$is_moderator = FALSE;

//if ( $mode == 'view' && intval($HTTP_GET_VARS[POST_TEAMS]) )

if ( !$mode )
{
	$template->assign_block_vars('list', array());
	
	$page_title = $lang['teams'];
	
	main_header();
	
	$sql = "SELECT DISTINCT g.* FROM " . GAMES . " g, " . TEAMS . " t WHERE g.game_id = t.team_game ORDER BY game_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$games = $db->sql_fetchrowset($result);
#	$games = _cached($sql, 'data_games');

	$sql = "SELECT * FROM " . TEAMS . " ORDER BY team_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$teams = $db->sql_fetchrowset($result);
#	$teams = _cached($sql, 'data_teams');

	$cnt_games = count($games);
	$cnt_teams = count($teams);

	for ( $i = 0; $i < $cnt_games; $i++ )
	{
		$game_id = $games[$i]['game_id'];
		
		$template->assign_block_vars('list._game_row', array('L_GAME' => $games[$i]['game_name']));
															 
		for ( $j = 0; $j < $cnt_teams; $j++ )
		{
			$team_id	= $teams[$j]['team_id'];
			$team_game	= $teams[$j]['team_game'];
			
			if ( $team_game == $game_id )
			{
				$template->assign_block_vars('list._gamerow._team_row', array(
					'NAME'		=> '<a href="' . check_sid("$file?mode=view&amp;$url=$team_id") . '">' . $teams[$j]['team_name'] . '</a>',
					'GAME'		=> display_gameicon($games[$i]['game_image']),
					
					'JOINUS'	=> $teams[$j]['team_join']	? '<a href="' . check_sid("contact.php?mode=joinus&amp;$url=$team_id") . '">' . $lang['match_joinus'] . '</a>'  : '',
					'FIGHTUS'	=> $teams[$j]['team_fight']	? '<a href="' . check_sid("contact.php?mode=fightus&amp;$url=$team_id") . '">' . $lang['match_fightus'] . '</a>'  : '',
				));
			}
		}
	}
}
else if ( $mode == 'view' && $data )
{
	$template->assign_block_vars('view', array());
	
	$page_title = $lang['team'];
	
	main_header();
	
	if ( isset($HTTP_GET_VARS['validate']) )
	{
		if ( !$userdata['session_logged_in'] )
		{
			redirect(check_sid('login.php?redirect=teams.php&' . POST_TEAMS . '=' . $team_id, true));
		}
	}
	
	$sql = "SELECT u.user_name, u.user_id, u.user_viewemail, u.user_posts, u.user_regdate, u.user_email, u.user_color, tu.team_mod
				FROM " . USERS . " u, " . TEAMS_USERS . " tu
				WHERE tu.team_id = $data AND u.user_id = tu.user_id
			ORDER BY u.user_name";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$members = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	$cnt_members = count($members);
	
	/* Teamdaten Abfragen */
	$sql = "SELECT t.*, g.game_image FROM " . TEAMS . " t LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$teams = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
#	$teams = _cached($sql, "data_teamsandgames");
	
	foreach ( $teams as $rows )
	{
		if ( $rows['team_id'] == $data )
		{
			$team = $rows;
		}
	}
	
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
			redirect(check_sid('login.php?redirect=teams.php&' . POST_GROUPS . '=' . $team_id, true));
		} 
		else if ( $sid !== $userdata['session_id'] )
		{
			message(GENERAL_ERROR, $lang['Session_invalid']);
		}

		if ( !$is_moderator && $userdata['user_level'] != ADMIN )
		{
			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('index.php') . '">'));

			$message = $lang['Not_group_moderator'] . '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . check_sid('index.php') . '">', '</a>');
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

				'U_GROUPCP' => $server_url . '?' . POST_GROUPS . "=$group_id")
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

				
//					$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('teams.php?' . POST_GROUPS . '=' . $team_id) . '">'));
				
				$message = $lang['group_set_mod']
					. '<br><br>' . sprintf($lang['Click_return_group'], '<a href="' . check_sid('teams.php?' . POST_GROUPS . '=' . $team_id) . '">', '</a>')
					. '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . check_sid('index.php') . '">', '</a>');
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
					
					$sql_select = 'SELECT user_email FROM ' . USERS . ' WHERE user_id IN (' . $sql_in . ')';
				}
				else if ( isset($HTTP_POST_VARS['deny']) || $mode == 'remove' )
				{
					$sql = 'DELETE FROM ' . GROUPS_USERS . ' WHERE user_id IN (' . $sql_in . ') AND team_id = ' . $team_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
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

						'U_GROUPCP' => $server_url . '?' . POST_GROUPS . "=$group_id")
					);
					$emailer->send();
					$emailer->reset();
				}
			}
		}
	}

	
//	
	$is_team_member = 0;
	
	if ( $cnt_members )
	{
		for ( $i = 0; $i < $cnt_members; $i++ )
		{
			if ( $members[$i]['user_id'] == $userdata['user_id'] && $userdata['session_logged_in'] )
			{
				$is_group_member = true; 
			}
			
			if ( $members[$i]['user_id'] == $userdata['user_id'] && $members[$i]['team_mod'] && $userdata['session_logged_in'] )
			{
				$is_moderator = true;
			}
		}
	}
	
	$team_mods = $team_memb = array();
		
	if ( $members )
	{
		foreach ( $members as $member => $row )
		{
			if ( $row['team_mod'] )
			{
				$team_mods[] = $row;
			}
			else
			{
				$team_memb[] = $row;
			}
		}
	}
	
	if ( $team_mods )
	{
		$cnt_mods = count($team_mods);
		
		for ( $j = 0; $j < $cnt_mods; $j++ )
		{
			$user_id	= $team_mods[$j]['user_id'];
			$user_name	= $team_mods[$j]['user_name'];
			
			generate_user_info($team_mods[$j], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);
	
			$row_class = ( !($j % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('view.mod_row', array(
				'ROW_CLASS' => $row_class,
				'USERNAME' => $user_name,
				'JOINED' => $joined,
				'POSTS' => $posts,
				'USER_ID' => $user_id, 
				'PROFILE_IMG' => $profile_img, 
				'PROFILE' => $profile, 
				'PM_IMG' => $pm_img,
				'PM' => $pm,
				'EMAIL_IMG' => $email_img,
				'EMAIL' => $email,
				
				'U_VIEWPROFILE' => check_sid('profile.php?mode=viewprofile&amp;id=' . $user_id)
			));
			
			if ( $is_moderator )
			{
				$template->assign_block_vars('view.modrow.switch_mod_option', array());
			}
		}
	}
	else
	{
		$template->assign_block_vars('view.switch_no_moderators', array());
		$template->assign_vars(array('L_NO_MODERATORS' => $lang['group_no_moderators']));
	}

	if ( $team_memb )
	{
		for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, count($team_memb)); $i++ )
		{
			$user_name	= $team_memb[$i]['user_name'];
			$user_id	= $team_memb[$i]['user_id'];
	
			generate_user_info($team_memb[$i], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);
	
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('view.member_row', array(
				'ROW_CLASS' => $row_class,
				'USERNAME' => $user_name,
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
				
				'U_VIEWPROFILE' => check_sid('profile.php?mode=viewprofile&amp;id=' . $user_id)
			));
			
			if ( $is_moderator )
			{
				$template->assign_block_vars('view.memberrow.switch_mod_option', array());
			}
		}
	}
	else
	{
		$template->assign_block_vars('view.switch_no_members', array());
		$template->assign_vars(array('L_NO_MEMBERS' => $lang['group_no_members']));
	}
	
	if ( $is_moderator || $userdata['user_level'] == ADMIN )
	{
		$template->assign_block_vars('view.switch_mod_option', array());
	}
	
	$current_page = ( !$cnt_members ) ? 1 : ceil($cnt_members/$settings['per_page_entry_site']);
	
	
	$sql_id = '';
	
	if ( $members )
	{
		foreach ($members as $member )
		{
			$ids[] = $member['user_id'];
		}
		
		$sql_id .= " AND NOT user_id IN (" . implode(', ', $ids) . ")";
	}
	
	$sql = 'SELECT user_name, user_id
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
		$select_users .= '<option value="' . $row['user_id'] . '" >&raquo; '. $row['user_name'] . '&nbsp;</option>';
	}
	$select_users .= '</select>';
	
	
	$select_options = '<select class="postselect" name="mode">';
	$select_options .= '<option value="0">&raquo; Option auswählen</option>';
	$select_options .= '<option value="remove">&raquo; Entfernen</option>';
	$select_options .= '<option value="change_level">&raquo; Gruppenrechte geben/nehmen</option>';
	$select_options .= '</select>';
	
	$s_hidden_fields = '<input type="hidden" name="' . POST_TEAMS . '" value="' . $data . '" />';
	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
	
	$template->assign_vars(array(
		'PAGINATION' => generate_pagination('teams.php?' . POST_GROUPS . "=$data", count($members), $settings['per_page_entry_site'], $start),
		'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry_site'] ) + 1 ), $current_page ), 
		'L_GOTO_PAGE' => $lang['Goto_page'],
		
		'L_TEAM_VIEW'		=> $lang['team_view'],
	#	'L_TEAM_MODERATOR'	=> $lang['team_moderator'],
	#	'L_TEAM_MEMBERS'	=> $lang['team_member'],
								 
								 
		'L_GROUP_INFORMATION' => $lang['Group_Information'],
		'L_GROUP_NAME' => $lang['Group_name'],
		'L_GROUP_DESC' => $lang['group_desc'],
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
		'L_FIND_USERNAME' => $lang['Find_user_name'],
		
		'L_JOINED'	=> $lang['Joined'],

		'GROUP_NAME' => $team['team_name'],
	#	'GROUP_DESC' => $team['team_description'],


		'S_HIDDEN_FIELDS' => $s_hidden_fields, 
//		'S_MODE_SELECT' => $select_sort_mode,
//		'S_ORDER_SELECT' => $select_sort_order,
		'S_SELECT_USERS'	=> $select_users,
		'S_SELECT_OPTION'	=> $select_options,
		'S_ACTION' => check_sid('teams.php?' . POST_TEAMS . '=' . $data)
	));
	
}
else
{
	redirect(check_sid($file, true));
}

$template->pparse('body');

main_footer();

?>