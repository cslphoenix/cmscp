<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_TRAINING);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_TRAINING;
$url	= POST_TRAINING;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, INT);
$mode	= request('mode', TXT);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_training.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'info_error.tpl',
));

main_header();

if ( !$mode && $userdata['session_logged_in'] )
{
	$template->assign_block_vars('list', array());
	
	$sql = "SELECT tr.*, t.team_name, g.game_image, m.match_rival_name
				FROM " . TRAINING . " tr
					LEFT JOIN " . TEAMS . " t ON tr.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . MATCH . " m ON m.match_id = tr.match_id
			ORDER BY tr.training_date DESC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$training = $db->sql_fetchrowset($result);
	$training = _cached($sql, 'data_training');

	$cnt_all = '';
	
	if ( !$training )
	{
		$template->assign_block_vars('list._entry_empty_new', array());
		$template->assign_block_vars('list._entry_empty_old', array());
	}
	else
	{
		$new = $old = array();
			
		foreach ( $training as $train => $row )
		{
			if ( $row['training_date'] > time() )
			{
				$new[]		= $row;
				$new_ids[]	= $row['training_id'];
			}
			else if ( $row['training_date'] < time() )
			{
				$old[]		= $row;
				$old_ids[]	= $row['training_id'];
			}
		}
		
		$cnt_all = count($new) + count($old);
		$cnt_new = count($new);
		$cnt_old = count($old);
		
		$new_sepp = $settings['per_page_entry_site'];
		$new_start = ( $start != 0 ) ? $start - $cnt_new : $start;
		$old_sepp = $settings['per_page_entry_site'] - $cnt_new;
		$old_start = ( $start != 0 ) ? $start - $cnt_new : $start;
		
		if ( !$new )
		{
			$template->assign_block_vars('list._entry_empty_new', array());
		}
		else
		{
		#	for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, count($new)); $i++ )
			for ( $i = $new_start; $i < min($new_sepp + $start, $cnt_new); $i++ )
			{
				$training_id = $new[$i]['training_id'];

				$sql = "SELECT * FROM " . TRAINING_USERS . " WHERE training_id IN (" . implode(', ', $new_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$in_ary[$row['training_id']][$row['user_id']] = $row['user_status'];
				}
				
				if ( isset($in_ary[$training_id]) )
				{
					foreach ( $in_ary[$training_id] as $key => $row )
					{
						if ( $key == $userdata['user_id'] )
						{
							switch ( $row )
							{
								case STATUS_NO:			$css = 'no';		$status = $lang['no'];		break;
								case STATUS_YES:		$css = 'yes';		$status = $lang['yes'];		break;
								case STATUS_REPLACE:	$css = 'replace';	$status = $lang['replace'];	break;
							}
						}
					}
				}
				else
				{
					$css	= 'none';
					$status	= $lang['join_none'];
				}
				
				$template->assign_block_vars('list._new_row', array(
					'CLASS' 		=> ( $i % 2 ) ? 'row1r' : 'row2r',
					'CSS'			=> $css,
					'STATUS'		=> $status,
					'GAME'	=> display_gameicon($new[$i]['game_image']),
					'NAME'	=> sprintf($lang[$match_typ], $new[$i]['match_rival_name']),
					'DATE'	=> create_date($userdata['user_dateformat'], $new[$i]['match_date'], $userdata['user_timezone']),
					'U_DETAILS'		=> check_sid('match.php?mode=detail&amp;' . POST_MATCH . '=' . $new[$i]['match_id']),
				));
			}
		}
		
		if ( !$old )
		{
			$template->assign_block_vars('list._entry_empty_old', array());
		}
		else
		{
		#	for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, count($old)); $i++ )
			for ( $i = $old_start; $i < min($old_sepp + $start, $cnt_old); $i++ )
			{
				$training_id = $old[$i]['training_id'];

				$sql = "SELECT * FROM " . TRAINING_USERS . " WHERE training_id IN (" . implode(', ', $old_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$in_ary[$row['training_id']][$row['user_id']] = $row['user_status'];
				}
				
				if ( isset($in_ary[$training_id]) )
				{
					foreach ( $in_ary[$training_id] as $key => $row )
					{
						if ( $key == $userdata['user_id'] )
						{
							switch ( $row )
							{
								case STATUS_NO:			$css = 'no';		$status = $lang['no'];		break;
								case STATUS_YES:		$css = 'yes';		$status = $lang['yes'];		break;
								case STATUS_REPLACE:	$css = 'replace';	$status = $lang['replace'];	break;
							}
						}
					}
				}
				else
				{
					$css	= 'none';
					$status	= $lang['join_none'];
				}
				
				$template->assign_block_vars('list._old_row', array(
					'CLASS' 		=> ( $i % 2 ) ? 'row1r' : 'row2r',
					'CSS'			=> $css,
					'STATUS'		=> $status,
					'GAME'	=> display_gameicon($old[$i]['game_image']),
					'NAME'	=> $old[$i]['match_rival_name'],
					'DATE'	=> create_date($userdata['user_dateformat'], $old[$i]['training_date'], $userdata['user_timezone']),
					'U_DETAILS'		=> check_sid('match.php?mode=detail&amp;' . POST_MATCH . '=' . $old[$i]['match_id'])
				));
			}
		}
	}
	
	$current_page = ( !$cnt_all ) ? 1 : ceil( $cnt_all / $settings['per_page_entry_site'] );
	
	$template->assign_vars(array(
		'L_DETAILS'		=> $lang['match_details'],
		'L_TEAMS'		=> $lang['teams'],
		'L_UPCOMING'	=> $lang['match_upcoming'],
		'L_EXPIRED'		=> $lang['match_expired'],
		'L_GOTO_PAGE'	=> $lang['Goto_page'],
		
		'PAGE_PAGING'	=> generate_pagination('match.php?', $cnt_all, $settings['per_page_entry_site'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry_site'] ) + 1 ), $current_page ),
	));
}
else if ( $mode == 'trainingdetails' && isset($HTTP_GET_VARS[POST_TRAINING]))
{	
//	$page_title = $lang['training_details'];
	main_header();
	
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
			'EDIT_TRAINING' => '<a href="' . check_sid('admin/admin_training.php?mode=edit&' . POST_TRAINING . '=' . $training_id . "&sid=" . $userdata['session_id']) . '" >&raquo; ' . $lang['edit_training'] . '</a>',
		));
	}
	
	$sql = 'SELECT tru.*, u.user_name
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
			'USERNAME'		=> $row['user_name'],
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
		'L_USERNAME'		=> $lang['user_name'],
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
	
	if ($db->sql_numrows($result) && $row_details['training_date'] > time())
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
		$s_hidden_fielda .= '<input type="hidden" name="' . POST_TRAINING . '" value="' . $training_id . '" />';
		
		$template->assign_vars(array(
			'S_CHECKED_1'		=> ( $row['training_users_status'] == 1 ) ? 'checked="checked"' : '',
			'S_CHECKED_2'		=> ( $row['training_users_status'] == 2 ) ? 'checked="checked"' : '',
			'S_CHECKED_3'		=> ( $row['training_users_status'] == 3 ) ? 'checked="checked"' : '',
			
			'S_HIDDEN_FIELDA'	=> $s_hidden_fielda,
		));
	}
	
	$s_hidden_fieldb = '<input type="hidden" name="mode" value="trainingdetails" />';
	$s_hidden_fieldb .= '<input type="hidden" name="' . POST_TRAINING . '" value="' . $training_id . '" />';
	
	
	//	Comments
	if ($settings['comments_trainings'] && $row_details['training_comments'])
	{
		$template->assign_block_vars('training_comments', array());
		
		$sql = 'SELECT trc.*, u.user_name, u.user_email
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
			
			for($i = $start; $i < min($settings['per_page_entry_site'] + $start, count($comment_entry)); $i++)
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
					'L_USERNAME'	=> $comment_entry[$i]['user_name'],
	//				'U_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_email'] : $comment_entry[$i]['user_email'],	Profil-Link und Mail schreiben an Gast
					'MESSAGE'		=> $comment,
					'DATE'			=> create_date($userdata['user_dateformat'], $comment_entry[$i]['time_create'], $userdata['user_timezone']),
					
					'ICON'			=> $icon,
	
					'U_EDIT'		=> check_sid('training.php?mode=edit&amp;' . POST_TRAINING . '=' . $comment_entry[$i]['training_id']),
					'U_DELETE'		=> check_sid('training.php?mode=delete&amp;' . POST_TRAINING . '=' . $comment_entry[$i]['training_id'])
				));
			}
		
			$current_page = ( !count($comment_entry) ) ? 1 : ceil( count($comment_entry) / $settings['per_page_entry_site'] );
			
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination('training.php?mode=trainingdetails&amp;' . POST_TRAINING . '=' . $training_id, count($comment_entry), $settings['per_page_entry_site'], $start),
				'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry_site'] ) + 1 ), $current_page ), 
			
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
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( $db->sql_numrows($result) )
				{
					$sql = 'UPDATE ' . TRAINING_COMMENTS_READ . '
								SET read_time = ' . time() . '
							WHERE training_id = ' . $training_id . ' AND user_id = ' . $userdata['user_id'];					
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{				
					$sql = 'INSERT INTO ' . TRAINING_COMMENTS_READ . ' (training_id, user_id, read_time)
						VALUES (' . $training_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				//	Keine Fehler?
				//	Cache löschung und eintragung des Kommentars
				
				$oCache -> deleteCache('training_details_' . $training_id . '_comments');
				
				_comment_message('add', 'training', $training_id, $userdata['user_id'], $user_ip, $HTTP_POST_VARS['comment']);
			
				$message = $lang['add_comment'] . sprintf($lang['click_return_training'],  '<a href="' . check_sid('training.php?mode=trainingdetails&amp;' . POST_TRAINING . '=' . $training_id) . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
			}
		}
	}
	
	$template->assign_vars(array(
	/*	
		'L_SUBMIT'				=> $lang['Submit'],
		
		'TRAINING_RIVAL'		=> $row_details['training_rival'],
		'U_MATCH_RIVAL'		=> $row_details['training_rival_url'],
		'TRAINING_RIVAL'	=> $row_details['training_rival_url'],
		'TRAINING_RIVAL_TAG'	=> $row_details['training_rival_tag'],
		
	
		'TRAINING_CATEGORIE'	=> $match_cat,
		'TRAINING_TYPE'			=> $match_type,
		'TRAINING_LEAGUE_INFO'	=> $match_league,
		'SERVER'				=> ($row_details['server']) ? '<a href="hlsw://' . $row_details['server'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
		'SERVER_PW'				=> ( $userdata['user_level'] >= TRIAL ) ? $row_details['server_pw'] : '',
		'HLTV'					=> ($row_details['server']) ? '<a href="hlsw://' . $row_details['server_hltv'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
		'HLTV_PW'				=> ( $userdata['user_level'] >= TRIAL ) ? $row_details['server_hltv_pw'] : '',
		
		'MAPC'					=> ($row_details['details_mapc']) ? '' : 'none',
		'MAPD'					=> ($row_details['details_mapd']) ? '' : 'none',
		
		'DETAILS_MAPA'			=> $row_details['details_mapa'],
		'DETAILS_MAPB'			=> $row_details['details_mapb'],
		'DETAILS_MAPC'			=> $row_details['details_mapc'],
		'DETAILS_MAPD'			=> $row_details['details_mapd'],
		
		'DETAILS_COMMENT'		=> $row_details['details_comment'],
	*/
		'S_HIDDEN_FIELDB'		=> $s_hidden_fieldb,
		'S_MATCH_ACTION'		=> check_sid('training.php?mode=trainingdetails&amp;' . POST_TRAINING . '=' . $training_id))
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

		log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], SECTION_TRAINING, 'UCP_STATUS_ADD');
	}
	else if ($HTTP_POST_VARS['training_users_status'] != $HTTP_POST_VARS['users_status'])
	{
		$sql = "UPDATE " . TRAINING_USERS . " SET
					training_users_status	= '" . intval($HTTP_POST_VARS['training_users_status']) . "',
					training_users_update	= '" . time() . "'
				WHERE training_id			= $training_id AND user_id = " . $userdata['user_id'];
		$result = $db->sql_query($sql);
		
		$message = $lang['update_training_status_edit'];
		
		log_add(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], SECTION_TRAINING, 'UCP_STATUS_EDIT');
	}
	else
	{
		$message = $lang['update_training_status_none'];
	}

	$template->assign_vars(array("META" => '<meta http-equiv="refresh" content="3;url=' . check_sid('training.php?mode=trainingdetails&amp;' . POST_TRAINING . '=' . $training_id) . '">'));
	message(GENERAL_MESSAGE, $message);
}
else if ($mode == 'teamtrainings' && isset($HTTP_GET_VARS[POST_TEAMS]))
{
	$page_title = $lang['training'];
	main_header();
	
	$template->set_filenames(array('body' => 'training_teams_body.tpl'));
	
	$team_id = $HTTP_GET_VARS[POST_TEAMS];
	
	//
	//	Team Details
	//
	$sql = 'SELECT * FROM ' . TEAMS . ' WHERE team_id = ' . $team_id;
	if (!($result_team = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'Could not obtain list', '', __LINE__, __FILE__, $sql);
	}
	$teams = $db->sql_fetchrow($result_team);
	$db->sql_freeresult($result_team);

	//
	//	List Matches von Team
	//
	$sql = 'SELECT m.training_id, m.training_date, m.training_public, m.training_rival, t.team_name, g.game_image
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
		for($i = $start; $i < min($settings['per_page_entry_site'] + $start, count($trainings_entry)); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$template->assign_block_vars('training_teams', array(
				'CLASS' 		=> $class,
				'MATCH_GAME'	=> display_gameicon($trainings_entry[$i]['game_image']),
				'MATCH_NAME'	=> ($trainings_entry[$i]['training_public']) ? 'vs. ' . $trainings_entry[$i]['training_rival'] : 'vs. <span style="font-style:italic;">' . $trainings_entry[$i]['training_rival'] . '</span>',
				'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $trainings_entry[$i]['training_date'], $userdata['user_timezone']),
				'U_DETAILS'		=> check_sid('training.php?mode=trainingdetails&amp;' . POST_MATCH . '=' . $trainings_entry[$i]['training_id'])
			));
		}
	}
	
	$current_page = ( !count($trainings_entry) ) ? 1 : ceil( count($trainings_entry) / $settings['per_page_entry_site'] );

	$template->assign_vars(array(
		'TEAM_NAME'		=> $teams['team_name'],
		'PAGINATION'	=> generate_pagination('training.php?', count($trainings_entry), $settings['per_page_entry_site'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry_site'] ) + 1 ), $current_page ), 
		'L_GOTO_PAGE'	=> $lang['Goto_page'],
	));
}
else
{
	redirect(check_sid($file, true));
}

if ( $userdata['user_level'] <= TRIAL )
{
	message(GENERAL_ERROR, $lang['access_denied']);
}

$template->pparse('body');

main_footer();

?>