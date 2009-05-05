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

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_TRAINING);
init_userprefs($userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ( $start < 0 ) ? 0 : $start;

if ( isset($HTTP_POST_VARS[POST_TRAINING_URL]) || isset($HTTP_GET_VARS[POST_TRAINING_URL]) )
{
	$training_id = ( isset($HTTP_POST_VARS[POST_TRAINING_URL]) ) ? intval($HTTP_POST_VARS[POST_TRAINING_URL]) : intval($HTTP_GET_VARS[POST_TRAINING_URL]);
}
else
{
	$training_id = '';
}

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

if ( $mode == '' )
{
	if ( !$userdata['session_logged_in'] )
	{
		redirect(append_sid("login.php?redirect=training.php?mode=$mode"));
	}
	
	$page_title = $lang['training'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'training_body.tpl'));
	
	$sql = 'SELECT tr.*, t.team_name, g.game_image, g.game_size, m.match_rival
				FROM ' . TRAINING . ' tr
					LEFT JOIN ' . TEAMS . ' t ON tr.team_id = t.team_id
					LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . MATCH . ' m ON m.match_id = tr.match_id
				WHERE tr.training_start > ' . time() . '
			ORDER BY tr.training_start DESC';
	$training_entry = _cached($sql, 'training_list_open');
	
	if (!$training_entry)
	{
		$template->assign_block_vars('no_entry_new', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_entry)); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$training_name	= ($training_entry[$i]['match_rival']) ? $lang['training_vs'] . $training_entry[$i]['match_rival'] . ' <span style="font-style:italic;">' . $training_entry[$i]['training_vs'] . '</span>' : $training_entry[$i]['training_vs'];
			
			$template->assign_block_vars('training_row_new', array(
				'CLASS' 		=> $class,
				'TRAINING_GAME'	=> display_gameicon($training_entry[$i]['game_size'], $training_entry[$i]['game_image']),
				'TRAINING_NAME'	=> $training_name,
				'TRAINING_DATE'	=> create_date($userdata['user_dateformat'], $training_entry[$i]['training_start'], $userdata['user_timezone']),
				'U_DETAILS'		=> append_sid('training.php?mode=trainingdetails&amp;' . POST_TRAINING_URL . '=' . $training_entry[$i]['training_id'])
			));
		}
	}
	
	$sql = 'SELECT tr.*, t.team_name, g.game_image, g.game_size, m.match_rival
				FROM ' . TRAINING . ' tr
					LEFT JOIN ' . TEAMS . ' t ON tr.team_id = t.team_id
					LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . MATCH . ' m ON m.match_id = tr.match_id
				WHERE tr.training_start < ' . time() . '
			ORDER BY tr.training_start DESC';
	$training_entry = _cached($sql, 'training_list_close');
	
	if (!$training_entry)
	{
		$template->assign_block_vars('no_entry_old', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_entry)); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$training_name	= ($training_entry[$i]['match_rival']) ? $lang['training_vs'] . $training_entry[$i]['match_rival'] . ' <span style="font-style:italic;">' . $training_entry[$i]['training_vs'] . '</span>' : $training_entry[$i]['training_vs'];
			
			$template->assign_block_vars('training_row_old', array(
				'CLASS' 		=> $class,
				'TRAINING_GAME'	=> display_gameicon($training_entry[$i]['game_size'], $training_entry[$i]['game_image']),
				'TRAINING_NAME'	=> $training_name,
				'TRAINING_DATE'	=> create_date($userdata['user_dateformat'], $training_entry[$i]['training_start'], $userdata['user_timezone']),
				'U_DETAILS'		=> append_sid('training.php?mode=trainingdetails&amp;' . POST_TRAINING_URL . '=' . $training_entry[$i]['training_id'])
			));
		}
	}

	$current_page = ( !count($training_entry) ) ? 1 : ceil( count($training_entry) / $settings['site_entry_per_page'] );
	
	$template->assign_vars(array(
		'PAGINATION'	=> generate_pagination('match.php?', count($training_entry), $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 
		'L_GOTO_PAGE'	=> $lang['Goto_page']
	));
	
	//
	//	Teams
	//
	$sql = 'SELECT t.team_id, t.team_name, t.team_fight, g.game_size, g.game_image
				FROM ' . TEAMS . ' t, ' . GAMES . ' g
				WHERE t.team_game = g.game_id
			ORDER BY t.team_order';
	$teams = _cached($sql, 'training_list_teaminfos');
	
	if (!$teams)
	{
		$template->assign_block_vars('no_entry_team', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < count($teams) + $start; $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$template->assign_block_vars('teams_row', array(
				'CLASS' 		=> $class,
				'TEAM_GAME'		=> display_gameicon($teams[$i]['game_size'], $teams[$i]['game_image']),
				'TEAM_NAME'		=> $teams[$i]['team_name'],
				'ALL_MATCHES'	=> append_sid('match.php?mode=teammatches&amp;' . POST_TEAMS_URL . '=' . $teams[$i]['team_id']),
				'TO_TEAM'		=> append_sid('teams.php?mode=show&amp;' . POST_TEAMS_URL . '=' . $teams[$i]['team_id']),
				'FIGHTUS'		=> append_sid('contact.php?mode=fightus&amp;' . POST_TEAMS_URL . '=' . $teams[$i]['team_id']),
			));
		}		
	}
		
	$template->assign_vars(array(
		'L_LAST_MATCH'	=> $lang['subnavi_last_matches'],
		'L_DETAILS'		=> $lang['match_details'],
		
		'L_TEAMS'		=> $lang['teams'],
		'L_ALL_MATCHES'	=> $lang['all_matches'],
		'L_TO_TEAM'		=> $lang['to_team'],
		
		'L_UPCOMING'	=> $lang['training_upcoming'],
		'L_EXPIRED'		=> $lang['training_expired'],
	));
	
}
else if ( $mode == 'trainingdetails' && isset($HTTP_GET_VARS[POST_TRAINING_URL]))
{	
//	$page_title = $lang['training_details'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'training_details_body.tpl'));
	
	$sql = 'SELECT	tr.*, m.*, t.*, g.*
			FROM ' . TRAINING . ' tr
				LEFT JOIN ' . MATCH . ' m ON m.match_id = tr.match_id
				LEFT JOIN ' . TEAMS . ' t ON tr.team_id = t.team_id
				LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
			WHERE tr.training_id = ' . $training_id;
	$row_details = _cached($sql, 'training_details_' . $training_id, 1);
	
	if ($userauth['auth_match'] || $userdata['user_level'] == ADMIN )
	{
		$template->assign_block_vars('training_edit', array(
			'EDIT_TRAINING' => '<a href="' . append_sid('admin/admin_training.php?mode=edit&' . POST_TRAINING_URL . '=' . $training_id . "&sid=" . $userdata['session_id']) . '" >&raquo; ' . $lang['edit_training'] . '</a>',
		));
	}
	
	$sql = 'SELECT tru.*, u.username
				FROM ' . TRAINING_USERS . ' tru, ' . USERS . ' u
			WHERE tru.user_id = u.user_id AND tru.training_id = ' . $training_id;
	$result = $db->sql_query($sql);

	$template->assign_block_vars('training_users', array());
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		switch ($row['training_users_status'])
		{
			case STATUS_YES:
				$status = $lang['status_yes'];
				$class	= 'yes';
			break;
			case STATUS_NO:
				$status = $lang['status_no'];
				$class	= 'no';
			break;
			case STATUS_REPLACE:
				$status = $lang['status_replace'];
				$class	= 'replace';
			break;
		}
		
		$template->assign_block_vars('training_users.training_users_status', array(
			'CLASS' 		=> $class,
			'USERNAME'		=> $row['username'],
			'STATUS'		=> $status,
			'DATE'			=> ($row['training_users_update']) ? $lang['change_on'] . create_date($userdata['user_dateformat'], $row['training_users_update'], $userdata['user_timezone']) : create_date($userdata['user_dateformat'], $row['training_users_create'], $userdata['user_timezone'])
		));
	}
	
	if (!$db->sql_numrows($result))
	{
		$template->assign_block_vars('training_users.no_entry_status', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	$db->sql_freeresult($result);
	
	$template->assign_vars(array(
		'L_USERNAME'		=> $lang['username'],
		'L_STATUS'			=> $lang['status'],
		'L_STATUS_YES'		=> $lang['status_yes'],
		'L_STATUS_NO'		=> $lang['status_no'],
		'L_STATUS_REPLACE'	=> $lang['status_replace'],
		'L_SET_STATUS'		=> $lang['set_status']
	));
		
	$sql = 'SELECT *
				FROM ' . TEAMS_USERS . '
			WHERE user_id = ' . $userdata['user_id'] . ' AND team_id = ' . $row_details['team_id'];
	$result = $db->sql_query($sql);
	
	if ($db->sql_numrows($result) && $row_details['training_start'] > time())
	{
		$template->assign_block_vars('training_users.users_status', array());
		
		$sql = 'SELECT training_users_status
					FROM ' . TRAINING_USERS . '
				WHERE user_id = ' . $userdata['user_id'] . ' AND training_id = ' . $training_id;
		$result = $db->sql_query($sql);
		
		$row = $db->sql_fetchrow($result);
		
		$training_users_status = ($row['training_users_status'] == 0) ? '0' : $row['training_users_status'];
		
		$s_hidden_fielda = '<input type="hidden" name="mode" value="change" />';
		$s_hidden_fielda .= '<input type="hidden" name="users_status" value="' . $training_users_status . '" />';
		$s_hidden_fielda .= '<input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $training_id . '" />';
		
		$template->assign_vars(array(
			'S_CHECKED_1'		=> ( $row['training_users_status'] == 1 ) ? 'checked="checked"' : '',
			'S_CHECKED_2'		=> ( $row['training_users_status'] == 2 ) ? 'checked="checked"' : '',
			'S_CHECKED_3'		=> ( $row['training_users_status'] == 3 ) ? 'checked="checked"' : '',
			
			'S_HIDDEN_FIELDA'	=> $s_hidden_fielda,
		));
	}
	
	$s_hidden_fieldb = '<input type="hidden" name="mode" value="trainingdetails" />';
	$s_hidden_fieldb .= '<input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $training_id . '" />';
	
	
	//	Comments
	if ($settings['comments_trainings'] && $row_details['training_comments'])
	{
		$template->assign_block_vars('training_comments', array());
		
		$sql = 'SELECT trc.*, u.username, u.user_email
					FROM ' . TRAINING_COMMENTS . ' trc
						LEFT JOIN ' . USERS . ' u ON trc.poster_id = u.user_id
					WHERE trc.training_id = ' . $training_id . ' ORDER BY trc.time_create DESC';
		$comment_entry = _cached($sql, 'training_details_' . $training_id . '_comments');
	
		if (!$comment_entry)
		{
			$template->assign_block_vars('training_comments.no_entry', array());
			$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			$last_entry = array('poster_ip' => '', 'time_create' => '');
		}
		else
		{
			$sql = 'SELECT read_time
						FROM ' . TRAINING_COMMENTS_READ . '
						WHERE user_id = ' . $userdata['user_id'] . ' AND training_id = ' . $training_id;
			$result = $db->sql_query($sql);
			$unread = $db->sql_fetchrow($result);
			
			if ( $db->sql_numrows($result) )
			{
				$unreads = false;
				
				$sql = 'UPDATE ' . TRAINING_COMMENTS_READ . '
							SET read_time = ' . time() . '
						WHERE training_id = ' . $training_id . ' AND user_id = ' . $userdata['user_id'];
				$result = $db->sql_query($sql);
			}
			else
			{
				$unreads = true;
				
				$sql = 'INSERT INTO ' . TRAINING_COMMENTS_READ . ' (training_id, user_id, read_time)
					VALUES (' . $training_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
				$result = $db->sql_query($sql);
			}
			
			for($i = $start; $i < min($settings['site_comment_per_page'] + $start, count($comment_entry)); $i++)
			{
				$class = ($i % 2) ? 'row1' : 'row2';
				
				if ( $userdata['session_logged_in'] )
				{
					if ( $unreads || $unread['read_time'] < $comment_entry[$i]['time_create'])
					{
						$icon = 'images/forum/icon_minipost_new.gif';
					}
					else
					{
						$icon = 'images/forum/icon_minipost.gif';
					}
				}
				else
				{
					$icon = 'images/forum/icon_minipost.gif';
				}
				
				$comment = html_entity_decode($comment_entry[$i]['poster_text'], ENT_QUOTES);
	
				$template->assign_block_vars('training_comments.comments', array(
					'CLASS' 		=> $class,
					'ID' 			=> $comment_entry[$i]['training_comments_id'],
					'L_USERNAME'	=> $comment_entry[$i]['username'],
	//				'U_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_email'] : $comment_entry[$i]['user_email'],	Profil-Link und Mail schreiben an Gast
					'MESSAGE'		=> $comment,
					'DATE'			=> create_date($userdata['user_dateformat'], $comment_entry[$i]['time_create'], $userdata['user_timezone']),
					
					'ICON'			=> $icon,
	
					'U_EDIT'		=> append_sid('training.php?mode=edit&amp;' . POST_TRAINING_URL . '=' . $comment_entry[$i]['training_id']),
					'U_DELETE'		=> append_sid('training.php?mode=delete&amp;' . POST_TRAINING_URL . '=' . $comment_entry[$i]['training_id'])
				));
			}
		
			$current_page = ( !count($comment_entry) ) ? 1 : ceil( count($comment_entry) / $settings['site_comment_per_page'] );
			
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination('training.php?mode=trainingdetails&amp;' . POST_TRAINING_URL . '=' . $training_id, count($comment_entry), $settings['site_comment_per_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_comment_per_page'] ) + 1 ), $current_page ), 
			
				'L_GOTO_PAGE' => $lang['Goto_page'])
			);
		
			sort($comment_entry);
			$last_entry = array_pop($comment_entry);
		}

		if ($userdata['session_logged_in'])
		{
			$template->assign_block_vars('training_comments.training_comments_member', array());
		}
		
		$error = '';
		$error_msg = '';
		
		//	Erlaubt nach Absenden kein Doppelbeitrag erst nach 20 Sekunden
		if ( isset($HTTP_POST_VARS['submit']) && ( $last_entry['poster_ip'] != $userdata['session_ip'] || $last_entry['time_create']+20 < time() ) )
		{
			//	Laden der Funktion zum eintragen von Kommentaren
			include($root_path . 'includes/functions_post.php');
			
			$comment		= (!$userdata['session_logged_in']) ? trim($HTTP_POST_VARS['comment']) : '';
			
			$template->assign_vars(array(
				'COMMENT'		=> $comment,
			));
			
			if ( empty($HTTP_POST_VARS['comment']) )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . 'comment';
			}
	
			if ( $error )
			{
				$template->set_filenames(array('reg_header' => 'error_body.tpl'));
				$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
				$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
			}
	
			if ( !$error )
			{
				//	Test: hier werden/sollen Kommentare als gelesen markiert werden
				$sql = 'SELECT * FROM ' . TRAINING_COMMENTS_READ . ' WHERE training_id = ' . $training_id . ' AND user_id = ' . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( $db->sql_numrows($result) )
				{
					$sql = 'UPDATE ' . TRAINING_COMMENTS_READ . '
								SET read_time = ' . time() . '
							WHERE training_id = ' . $training_id . ' AND user_id = ' . $userdata['user_id'];					
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{				
					$sql = 'INSERT INTO ' . TRAINING_COMMENTS_READ . ' (training_id, user_id, read_time)
						VALUES (' . $training_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				//	Keine Fehler?
				//	Cache löschung und eintragung des Kommentars
				
				$oCache -> deleteCache('training_details_' . $training_id . '_comments');
				
				_comment_message('add', 'training', $training_id, $userdata['user_id'], $user_ip, $HTTP_POST_VARS['comment']);
			
				$message = $lang['add_comment'] . '<br><br>' . sprintf($lang['click_return_training'],  '<a href="' . append_sid('training.php?mode=trainingdetails&amp;' . POST_TRAINING_URL . '=' . $training_id) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
	}
	
	$template->assign_vars(array(
	/*	
		'L_SUBMIT'				=> $lang['Submit'],
		
		'TRAINING_RIVAL'		=> $row_details['training_rival'],
		'U_MATCH_RIVAL_URL'		=> $row_details['training_rival_url'],
		'TRAINING_RIVAL_URL'	=> $row_details['training_rival_url'],
		'TRAINING_RIVAL_TAG'	=> $row_details['training_rival_tag'],
		
	
		'TRAINING_CATEGORIE'	=> $match_categorie,
		'TRAINING_TYPE'			=> $match_type,
		'TRAINING_LEAGUE_INFO'	=> $match_league,
		'SERVER'				=> ($row_details['server']) ? '<a href="hlsw://' . $row_details['server'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
		'SERVER_PW'				=> ($userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN ) ? $row_details['server_pw'] : '',
		'HLTV'					=> ($row_details['server']) ? '<a href="hlsw://' . $row_details['server_hltv'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
		'HLTV_PW'				=> ($userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN ) ? $row_details['server_hltv_pw'] : '',
		
		'MAPC'					=> ($row_details['details_mapc']) ? '' : 'none',
		'MAPD'					=> ($row_details['details_mapd']) ? '' : 'none',
		
		'DETAILS_MAPA'			=> $row_details['details_mapa'],
		'DETAILS_MAPB'			=> $row_details['details_mapb'],
		'DETAILS_MAPC'			=> $row_details['details_mapc'],
		'DETAILS_MAPD'			=> $row_details['details_mapd'],
		
		'DETAILS_COMMENT'		=> $row_details['details_comment'],
	*/
		'S_HIDDEN_FIELDB'		=> $s_hidden_fieldb,
		'S_MATCH_ACTION'		=> append_sid('training.php?mode=trainingdetails&amp;' . POST_TRAINING_URL . '=' . $training_id))
	);
}
else if ($mode == 'change')
{
	//	Status für das Training ändern
	//	wenn noch kein Eintrag wird insert ausgeführt
	//	ansonsten der update befehl
	//	sollte status gleich der in der db sein und der auswahl, wird nichts gespeichert
	if ($HTTP_POST_VARS['users_status'] == '0' || $HTTP_POST_VARS['users_status'] == '')
	{
		$sql = 'INSERT INTO ' . TRAINING_USERS . " (training_id, user_id, training_users_status, training_users_create, training_users_update)
			VALUES ($training_id, " . $userdata['user_id'] . ", '" . intval($HTTP_POST_VARS['training_users_status']) . "', '" . time() . "', 0)";
		$result = $db->sql_query($sql);
		
		$message = $lang['update_training_status_add'];

		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TRAINING, 'UCP_STATUS_ADD');
	}
	else if ($HTTP_POST_VARS['training_users_status'] != $HTTP_POST_VARS['users_status'])
	{
		$sql = "UPDATE " . TRAINING_USERS . " SET
					training_users_status	= '" . intval($HTTP_POST_VARS['training_users_status']) . "',
					training_users_update	= '" . time() . "'
				WHERE training_id			= $training_id AND user_id = " . $userdata['user_id'];
		$result = $db->sql_query($sql);
		
		$message = $lang['update_training_status_edit'];
		
		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_TRAINING, 'UCP_STATUS_EDIT');
	}
	else
	{
		$message = $lang['update_training_status_none'];
	}

	$template->assign_vars(array("META" => '<meta http-equiv="refresh" content="3;url=' . append_sid('training.php?mode=trainingdetails&amp;' . POST_TRAINING_URL . '=' . $training_id) . '">'));
	message_die(GENERAL_MESSAGE, $message);
}
else if ($mode == 'teamtrainings' && isset($HTTP_GET_VARS[POST_TEAMS_URL]))
{
	$page_title = $lang['training'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'training_teams_body.tpl'));
	
	$team_id = $HTTP_GET_VARS[POST_TEAMS_URL];
	
	//
	//	Team Details
	//
	$sql = 'SELECT * FROM ' . TEAMS . ' WHERE team_id = ' . $team_id;
	if (!($result_team = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain list', '', __LINE__, __FILE__, $sql);
	}
	$teams = $db->sql_fetchrow($result_team);
	$db->sql_freeresult($result_team);

	//
	//	List Matches von Team
	//
	$sql = 'SELECT m.training_id, m.training_date, m.training_public, m.training_rival, t.team_name, g.game_image, g.game_size
				FROM ' . MATCH . ' m
					LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
					LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
				WHERE m.team_id = ' . $team_id . '
			ORDER BY m.training_date DESC';
	$trainings_entry = _cached($sql, 'training_list_team_' . $team_id);
	
	if (!$matchs_entry)
	{
		$template->assign_block_vars('no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($trainings_entry)); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$template->assign_block_vars('training_teams', array(
				'CLASS' 		=> $class,
				'MATCH_GAME'	=> display_gameicon($trainings_entry[$i]['game_size'], $trainings_entry[$i]['game_image']),
				'MATCH_NAME'	=> ($trainings_entry[$i]['training_public']) ? 'vs. ' . $trainings_entry[$i]['training_rival'] : 'vs. <span style="font-style:italic;">' . $trainings_entry[$i]['training_rival'] . '</span>',
				'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $trainings_entry[$i]['training_date'], $userdata['user_timezone']),
				'U_DETAILS'		=> append_sid('training.php?mode=trainingdetails&amp;' . POST_MATCH_URL . '=' . $trainings_entry[$i]['training_id'])
			));
		}
	}
	
	$current_page = ( !count($trainings_entry) ) ? 1 : ceil( count($trainings_entry) / $settings['site_entry_per_page'] );

	$template->assign_vars(array(
		'TEAM_NAME'		=> $teams['team_name'],
		'PAGINATION'	=> generate_pagination('training.php?', count($trainings_entry), $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 
		'L_GOTO_PAGE'	=> $lang['Goto_page'],
	));
}
else
{
	redirect(append_sid('training.php', true));
}

if ( $userdata['user_level'] != TRIAL || $userdata['user_level'] != MEMBER || $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_ERROR, $lang['access_denied']);
}

//
//	Generate the page
//
$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>