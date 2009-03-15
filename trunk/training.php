<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_TRAINING);
init_userprefs($userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

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

if ($mode == '')
{
	$page_title = $lang['training'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'training_body.tpl'));
	
	$sql = 'SELECT tr.*, t.team_name, g.game_image, g.game_size, m.match_rival
				FROM ' . TRAINING_TABLE . ' tr
					LEFT JOIN ' . TEAMS_TABLE . ' t ON tr.team_id = t.team_id
					LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . MATCH_TABLE . ' m ON m.team_id = tr.team_id
				WHERE training_start > ' . time() . '
			ORDER BY tr.training_start DESC';
	$training_entry = _cached($sql, 'list_training_open');
	
	if (!$training_entry)
	{
		$template->assign_block_vars('no_entry_new', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['entry_per_page'] + $start, count($training_entry)); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$training_name	= ($training_entry[$i]['match_rival']) ? $lang['training_vs'] . $training_entry[$i]['match_rival'] . ' <span style="font-style:italic;">' . $training_entry[$i]['training_vs'] . '</span>' : $training_entry[$i]['training_vs'];
			
			$template->assign_block_vars('training_row_new', array(
				'CLASS' 		=> $class,
				'TRAINING_GAME'	=> display_gameicon($training_entry[$i]['game_size'], $training_entry[$i]['game_image']),
				'TRAINING_NAME'	=> $training_name,
				'TRAINING_DATE'	=> create_date($userdata['user_dateformat'], $training_entry[$i]['training_start'], $userdata['user_timezone']),
				'U_DETAILS'		=> append_sid("training.php?mode=trainingdetails&amp;" . POST_TRAINING_URL . "=".$training_entry[$i]['training_id'])
			));
		}
	}
	
	$sql = 'SELECT tr.*, t.team_name, g.game_image, g.game_size, m.match_rival
				FROM ' . TRAINING_TABLE . ' tr
					LEFT JOIN ' . TEAMS_TABLE . ' t ON tr.team_id = t.team_id
					LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . MATCH_TABLE . ' m ON m.team_id = tr.team_id
				WHERE training_start < ' . time() . '
			ORDER BY tr.training_start DESC';
	$training_entry = _cached($sql, 'list_training_close');
	
	if (!$training_entry)
	{
		$template->assign_block_vars('no_entry_old', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['entry_per_page'] + $start, count($training_entry)); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$training_name	= ($training_entry[$i]['match_rival']) ? $lang['training_vs'] . $training_entry[$i]['match_rival'] . ' <span style="font-style:italic;">' . $training_entry[$i]['training_vs'] . '</span>' : $training_entry[$i]['training_vs'];
			
			$template->assign_block_vars('training_row_old', array(
				'CLASS' 		=> $class,
				'TRAINING_GAME'	=> display_gameicon($training_entry[$i]['game_size'], $training_entry[$i]['game_image']),
				'TRAINING_NAME'	=> $training_name,
				'TRAINING_DATE'	=> create_date($userdata['user_dateformat'], $training_entry[$i]['training_start'], $userdata['user_timezone']),
				'U_DETAILS'		=> append_sid("training.php?mode=trainingdetails&amp;" . POST_TRAINING_URL . "=".$training_entry[$i]['training_id'])
			));
		}
	}

	$current_page = ( !count($training_entry) ) ? 1 : ceil( count($training_entry) / $settings['entry_per_page'] );
	
	$template->assign_vars(array(
		'PAGINATION'	=> generate_pagination("admin_match.php?", count($training_entry), $settings['entry_per_page'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['entry_per_page'] ) + 1 ), $current_page ), 
		'L_GOTO_PAGE'	=> $lang['Goto_page']
	));
	
	//
	//	Teams
	//
	$sql = 'SELECT t.team_id, t.team_name, t.team_fight, g.game_size, g.game_image
				FROM ' . TEAMS_TABLE . ' t, ' . GAMES_TABLE . ' g
				WHERE t.team_game = g.game_id
			ORDER BY t.team_order';
	$teams = _cached($sql, 'list_teams_training_info');
	
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
				'ALL_MATCHES'	=> append_sid("match.php?mode=teammatches&amp;" . POST_TEAMS_URL . "=".$teams[$i]['team_id']),
				'TO_TEAM'		=> append_sid("teams.php?mode=show&amp;" . POST_TEAMS_URL . "=".$teams[$i]['team_id']),
				'FIGHTUS'		=> append_sid("contact.php?mode=fightus&amp;" . POST_TEAMS_URL . "=".$teams[$i]['team_id']),
			));
		}		
	}
	//
	//	Teams
	//
		
	$template->assign_vars(array(
		'L_LAST_MATCH'	=> $lang['last_matches'],
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
	$page_title = $lang['training_details'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'training_details_body.tpl'));
	
	$sql = 'SELECT	tr.*, m.*, t.*, g.*
			FROM ' . TRAINING_TABLE . ' tr
				LEFT JOIN ' . MATCH_TABLE . ' m ON m.match_id = tr.match_id
				LEFT JOIN ' . TEAMS_TABLE . ' t ON tr.team_id = t.team_id
				LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
			WHERE tr.training_id = ' . $training_id;
	$row_details = _cached($sql, 'training_details_' . $training_id, 1);
	
	
	if ($userdata['auth_match'] || $userdata['user_level'] == ADMIN)
	{
		$template->assign_block_vars('training_edit', array(
			'EDIT_TRAINING' => '<a href="./admin/admin_match.php?mode=edit&' . POST_TRAINING_URL . '=' . $training_id . '&sid=' . $userdata['session_id'] . '" >&raquo; ' . $lang['edit_training'] . '</a>',
		));
	}

	
	/*switch ($row_details['training_categorie'])
	{
		case '1':
			$match_categorie = $lang['select_categorie1'];
		break;
		case '2':
			$match_categorie = $lang['select_categorie2'];
		break;
		case '3':
			$match_categorie = $lang['select_categorie3'];
		break;
		case '4':
			$match_categorie = $lang['select_categorie4'];
		break;
		case '5':
			$match_categorie = $lang['select_categorie5'];
		break;
	}
	
	switch ($row_details['training_type'])
	{
		case '1':
			$match_type = $lang['select_type1'];
		break;
		case '2':
			$match_type = $lang['select_type2'];
		break;
		case '3':
			$match_type = $lang['select_type3'];
		break;
		case '4':
			$match_type = $lang['select_type4'];
		break;
		case '5':
			$match_type = $lang['select_type5'];
		break;
		case '6':
			$match_type = $lang['select_type6'];
		break;
	}
	
	switch ($row_details['training_league'])
	{
		case '1':
			$match_league = '<a href="' . $lang['select_league1i'] . '">' . $lang['select_league1'] . '</a>';
		break;
		case '2':
			$match_league = '<a href="' . $lang['select_league2i'] . '">' . $lang['select_league2'] . '</a>';
		break;
		case '3':
			$match_league = '<a href="' . $lang['select_league3i'] . '">' . $lang['select_league3'] . '</a>';
		break;
		case '4':
			$match_league = '<a href="' . $lang['select_league4i'] . '">' . $lang['select_league4'] . '</a>';
		break;
		case '5':
			$match_league = '<a href="' . $lang['select_league5i'] . '">' . $lang['select_league5'] . '</a>';
		break;
		case '6':
			$match_league = '<a href="' . $lang['select_league6i'] . '">' . $lang['select_league6'] . '</a>';
		break;
		case '7':
			$match_league = '<a href="' . $lang['select_league7i'] . '">' . $lang['select_league7'] . '</a>';
		break;
		case '8':
			$match_league = $lang['select_league8'];
		break;
	}*/
	
	$sql = 'SELECT mu.*, u.username
				FROM ' . TRAINING_USERS_TABLE . ' mu, ' . USERS_TABLE . ' u
			WHERE mu.user_id = u.user_id AND training_id = ' . $training_id;
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
		
		$template->assign_block_vars('training_users.match_users_status', array(
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
				FROM ' . TEAMS_USERS_TABLE . '
			WHERE user_id = ' . $userdata['user_id'] . ' AND team_id = ' . $row_details['team_id'];
	$result = $db->sql_query($sql);
	
	if ($db->sql_numrows($result) && $row_details['training_date'] > time())
	{
		$template->assign_block_vars('training_users.users_status', array());
		
		$sql = 'SELECT match_users_status
					FROM ' . TRAINING_USERS_TABLE . '
				WHERE user_id = ' . $userdata['user_id'] . ' AND training_id = ' . $training_id;
		$result = $db->sql_query($sql);
		
		$row = $db->sql_fetchrow($result);
		
		$match_users_status = ($row['training_users_status'] == 0) ? '0' : $row['training_users_status'];
		
		$s_hidden_fielda = '<input type="hidden" name="mode" value="change" />';
		$s_hidden_fielda .= '<input type="hidden" name="users_status" value="' . $match_users_status . '" />';
		$s_hidden_fielda .= '<input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $training_id . '" />';
		
		$template->assign_vars(array(
			'S_CHECKED_1'		=> ( $row['training_users_status'] == 1 ) ? 'checked="checked"' : '',
			'S_CHECKED_2'		=> ( $row['training_users_status'] == 2 ) ? 'checked="checked"' : '',
			'S_CHECKED_3'		=> ( $row['training_users_status'] == 3 ) ? 'checked="checked"' : '',
			
			'S_HIDDEN_FIELDA'	=> $s_hidden_fielda,
		));
	}
	
	$s_hidden_fieldb = '<input type="hidden" name="mode" value="matchdetails" />';
	$s_hidden_fieldb .= '<input type="hidden" name="' . POST_TRAINING_URL . '" value="' . $training_id . '" />';
	
	
	//	Comments
	if ($settings['comments_trainings'] && $row_details['training_comments'])
	{
		$template->assign_block_vars('training_comments', array());
		
		$sql = 'SELECT mc.*, u.username, u.user_email
					FROM ' . MATCH_COMMENTS_TABLE . ' mc
						LEFT JOIN ' . USERS_TABLE . ' u ON mc.poster_id = u.user_id
					WHERE training_id = ' . $training_id . ' ORDER BY time_create DESC';
		$comment_entry = _cached($sql, 'training_' . $training_id . '_comments');
	
		if (!$comment_entry)
		{
			$template->assign_block_vars('training_comments.no_entry', array());
			$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
		}
		else
		{
			for($i = $start; $i < min($settings['comment_per_page'] + $start, count($comment_entry)); $i++)
			{
				$class = ($i % 2) ? 'row1' : 'row2';
				
				$comment = html_entity_decode($comment_entry[$i]['text'], ENT_QUOTES);
	
				$template->assign_block_vars('training_comments.comments', array(
					'CLASS' 		=> $class,
					'ID' 			=> $comment_entry[$i]['training_comments_id'],
					'L_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_nick'] : $comment_entry[$i]['username'],
	//				'U_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_email'] : $comment_entry[$i]['user_email'],	Profil-Link und Mail schreiben an Gast
					'MESSAGE'		=> $comment,
					'DATE'			=> create_date($userdata['user_dateformat'], $comment_entry[$i]['time_create'], $userdata['user_timezone']),
	
					'U_EDIT'		=> append_sid("admin_match.php?mode=edit&amp;" . POST_TRAINING_URL . "=".$comment_entry[$i]['training_id']),
					'U_DELETE'		=> append_sid("admin_match.php?mode=delete&amp;" . POST_TRAINING_URL . "=".$comment_entry[$i]['training_id'])
				));
			}
		
			$current_page = ( !count($comment_entry) ) ? 1 : ceil( count($comment_entry) / $settings['comment_per_page'] );
			
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination("match.php?mode=matchdetails&amp;" . POST_TRAINING_URL . "=" . $training_id, count($comment_entry), $settings['comment_per_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['comment_per_page'] ) + 1 ), $current_page ), 
			
				'L_GOTO_PAGE' => $lang['Goto_page'])
			);
		
		
		
		sort($comment_entry);
		$last_entry = array_pop($comment_entry);
		
		}
//		if ($settings['comments_matches_guest'] && !$userdata['session_logged_in'] && $last_entry['poster_ip'] != $userdata['session_ip'])
		if ($settings['comments_matches_guest'] && !$userdata['session_logged_in'])
		{
			$template->assign_block_vars('training_comments.match_comments_guest', array());
		}
		
		if ($userdata['session_logged_in'])
		{
			$template->assign_block_vars('training_comments.match_comments_member', array());
		}
		
		$error = FALSE;
		$error_msg = '';
		
		if ( isset($HTTP_POST_VARS['submit']) )
		{
			include($root_path . 'includes/functions_post.php');
			
			$poster_nick	= (!$userdata['session_logged_in']) ? trim(stripslashes($HTTP_POST_VARS['poster_nick'])) : '';
			$poster_mail	= (!$userdata['session_logged_in']) ? trim(stripslashes($HTTP_POST_VARS['poster_mail'])) : '';
			$poster_hp		= (!$userdata['session_logged_in']) ? trim($HTTP_POST_VARS['poster_hp']) : '';
			$comment		= (!$userdata['session_logged_in']) ? trim($HTTP_POST_VARS['comment']) : '';
			
			$template->assign_vars(array(
				'POSTER_NICK'	=> $poster_nick,
				'POSTER_MAIL'	=> $poster_mail,
				'POSTER_HP'		=> $poster_hp,
				'COMMENT'		=> $comment,
			));
			
			if (!$userdata['session_logged_in'])
			{
				$captcha = $HTTP_POST_VARS['captcha'];
				
				if ($captcha != $HTTP_SESSION_VARS['captcha'])
				{
					$error = true;
					$error_msg = 'captcha';
				}
					
				if ( empty($HTTP_POST_VARS['poster_nick']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . 'user_nick';
				}
				
				if ( empty($HTTP_POST_VARS['poster_mail']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . 'poster_mail';
				}
				
				unset($_SESSION['captcha']);
			}
				
			if ( empty($HTTP_POST_VARS['comment']) )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . 'comment';
			}
	
			if ( $error )
			{
				$template->set_filenames(array('reg_header' => 'error_body.tpl'));
				$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
				$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
			}
	
			if ( !$error )
			{
				$oCache -> deleteCache('training_' . $training_id . '_comments');
				
				_comment_message('add', 'match', $training_id, $userdata['user_id'], $poster_nick, $poster_mail, '', $user_ip, $HTTP_POST_VARS['comment']);
			
				$message = $lang['add_comment'] . '<br /><br />' . sprintf($lang['click_return_match'],  '<a href="' . append_sid("match.php?mode=matchdetails&amp;" . POST_TRAINING_URL . "=" . $training_id) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
	}
	
	$template->assign_vars(array(
	/*	
		'L_SUBMIT'				=> $lang['Submit'],
		
		'TRAINING_RIVAL'			=> $row_details['training_rival'],
		'U_MATCH_RIVAL_URL'		=> $row_details['training_rival_url'],
		'TRAINING_RIVAL_URL'		=> $row_details['training_rival_url'],
		'TRAINING_RIVAL_TAG'		=> $row_details['training_rival_tag'],
		
	
		'TRAINING_CATEGORIE'		=> $match_categorie,
		'TRAINING_TYPE'			=> $match_type,
		'TRAINING_LEAGUE_INFO'		=> $match_league,
		'SERVER'				=> ($row_details['server']) ? '<a href="hlsw://' . $row_details['server'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
		'SERVER_PW'				=> ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN) ? $row_details['server_pw'] : '',
		'HLTV'					=> ($row_details['server']) ? '<a href="hlsw://' . $row_details['server_hltv'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
		'HLTV_PW'				=> ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN) ? $row_details['server_hltv_pw'] : '',
		
		'MAPC'					=> ($row_details['details_mapc']) ? '' : 'none',
		'MAPD'					=> ($row_details['details_mapd']) ? '' : 'none',
		
		'DETAILS_MAPA'			=> $row_details['details_mapa'],
		'DETAILS_MAPB'			=> $row_details['details_mapb'],
		'DETAILS_MAPC'			=> $row_details['details_mapc'],
		'DETAILS_MAPD'			=> $row_details['details_mapd'],
		
		'CLASSA'		=> $classa,
		'CLASSB'		=> $classb,
		'CLASSC'		=> $classc,
		'CLASSD'		=> $classd,
		
		
		'DETAILS_SOCRE_A'		=> $row_details['details_mapa_clan'],
		'DETAILS_SOCRE_C'		=> $row_details['details_mapb_clan'],
		'DETAILS_SOCRE_E'		=> $row_details['details_mapc_clan'],
		'DETAILS_SOCRE_G'		=> $row_details['details_mapd_clan'],
		
		'DETAILS_SOCRE_B'		=> $row_details['details_mapa_rival'],
		'DETAILS_SOCRE_D'		=> $row_details['details_mapb_rival'],
		'DETAILS_SOCRE_F'		=> $row_details['details_mapc_rival'],
		'DETAILS_SOCRE_H'		=> $row_details['details_mapd_rival'],

		'DETAILS_PIC_A'			=> $pic_a,
		'DETAILS_PIC_B'			=> $pic_b,
		'DETAILS_PIC_C'			=> $pic_c,
		'DETAILS_PIC_D'			=> $pic_d,
		'DETAILS_PIC_E'			=> $pic_e,
		'DETAILS_PIC_F'			=> $pic_f,
		'DETAILS_PIC_G'			=> $pic_g,
		'DETAILS_PIC_H'			=> $pic_h,
		
		'DETAILS_COMMENT'		=> $row_details['details_comment'],
	*/
		'S_HIDDEN_FIELDB'		=> $s_hidden_fieldb,
		'S_MATCH_ACTION'		=> append_sid("match.php?mode=matchdetails&amp;" . POST_TRAINING_URL . "=" . $training_id))
	);
}
else if ($mode == 'change')
{
	//
	//	Status für das Match ändern
	//
	//	wenn noch kein Eintrag wird insert ausgeführt
	//	ansonsten der update befehl
	//	sollte status gleich der in der db sein und der auswahl, wird nichts gespeichert
	//
	//	01.03.2009 Phoenix
	//
	if ($HTTP_POST_VARS['users_status'] == '0' || $HTTP_POST_VARS['users_status'] == '')
	{
		$sql = 'INSERT INTO ' . TRAINING_USERS_TABLE . " (training_id, user_id, match_users_status, match_users_create, match_users_update)
			VALUES ($training_id, " . $userdata['user_id'] . ", '" . intval($HTTP_POST_VARS['training_users_status']) . "', '" . time() . "', 0)";
		$result = $db->sql_query($sql);
		
		$message = $lang['update_match_status_add'];

		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'UCP_STATUS_ADD');
	}
	else if ($HTTP_POST_VARS['training_users_status'] != $HTTP_POST_VARS['users_status'])
	{
		$sql = "UPDATE " . TRAINING_USERS_TABLE . " SET
					match_users_status		= '" . intval($HTTP_POST_VARS['training_users_status']) . "',
					match_users_update		= '" . time() . "'
				WHERE training_id = $training_id AND user_id = " . $userdata['user_id'];
		$result = $db->sql_query($sql);
		
		$message = $lang['update_match_status_edit'];
		
		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'UCP_STATUS_EDIT');
	}
	else
	{
		$message = $lang['update_match_status_none'];
	}

	$template->assign_vars(array("META" => '<meta http-equiv="refresh" content="3;url=' . append_sid("match.php?mode=matchdetails&amp;" . POST_TRAINING_URL . "=" . $training_id) . '">'));
	message_die(GENERAL_MESSAGE, $message);
}
else
{
	redirect(append_sid('training.php', true));
}

if (!$userdata['user_level'] == TRAIL || !$userdata['user_level'] == MEMBER || !$userdata['user_level'] == ADMIN)
{
	message_die(GENERAL_ERROR, $lang['training_denied']);
}

//
//	Generate the page
//
$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>