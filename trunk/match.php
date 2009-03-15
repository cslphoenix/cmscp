<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_MATCH);
init_userprefs($userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_POST_VARS[POST_MATCH_URL]) || isset($HTTP_GET_VARS[POST_MATCH_URL]) )
{
	$match_id = ( isset($HTTP_POST_VARS[POST_MATCH_URL]) ) ? intval($HTTP_POST_VARS[POST_MATCH_URL]) : intval($HTTP_GET_VARS[POST_MATCH_URL]);
}
else
{
	$match_id = '';
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
	$page_title = $lang['match'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'match_body.tpl'));
	
	//
	//	List Matches New
	//
	if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		$sql = 'SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
					FROM ' . MATCH_TABLE . ' m
						LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON m.match_id = tr.match_id
					WHERE m.match_date > ' . time() . '
				ORDER BY m.match_date DESC';
		$match_entry = _cached($sql, 'list_match_open_member');
	}
	else
	{
		$sql = 'SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
					FROM ' . MATCH_TABLE . ' m
						LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON m.match_id = tr.match_id
					WHERE m.match_date > ' . time() . ' AND m.match_public = 1
				ORDER BY m.match_date DESC';
		$match_entry = _cached($sql, 'list_match_open_guest');
	}
	
	if (!$match_entry)
	{
		$template->assign_block_vars('no_entry_n', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['entry_per_page'] + $start, count($match_entry)); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$template->assign_block_vars('match_row_n', array(
				'CLASS' 		=> $class,
				'MATCH_GAME'	=> display_gameicon($match_entry[$i]['game_size'], $match_entry[$i]['game_image']),
				'MATCH_NAME'	=> ($match_entry[$i]['match_public']) ? 'vs. ' . $match_entry[$i]['match_rival'] : 'vs. <span style="font-style:italic;">' . $match_entry[$i]['match_rival'] . '</span>',
				'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $match_entry[$i]['match_date'], $userdata['user_timezone']),
				'U_DETAILS'		=> append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=".$match_entry[$i]['match_id'])
			));
		}
	}
	
	//
	//	List Matches Old
	//
	if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		$sql = 'SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
					FROM ' . MATCH_TABLE . ' m
						LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON m.match_id = tr.match_id
					WHERE m.match_date < ' . time() . '
				ORDER BY m.match_date DESC';
		$match_entry = _cached($sql, 'list_match_close_member');
	}
	else
	{
		$sql = 'SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
					FROM ' . MATCH_TABLE . ' m
						LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON m.match_id = tr.match_id
					WHERE m.match_date < ' . time() . ' AND m.match_public = 1
				ORDER BY m.match_date DESC';
		$match_entry = _cached($sql, 'list_match_close_guest');
	}
	
	if (!$match_entry)
	{
		$template->assign_block_vars('no_entry_o', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['entry_per_page'] + $start, count($match_entry)); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';

			$template->assign_block_vars('match_row_o', array(
				'CLASS' 		=> $class,
				'MATCH_GAME'	=> display_gameicon($match_entry[$i]['game_size'], $match_entry[$i]['game_image']),
				'MATCH_NAME'	=> ($match_entry[$i]['match_public']) ? 'vs. ' . $match_entry[$i]['match_rival'] : 'vs. <span style="font-style:italic;">' . $match_entry[$i]['match_rival'] . '</span>',
				'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $match_entry[$i]['match_date'], $userdata['user_timezone']),
				'U_DETAILS'		=> append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=".$match_entry[$i]['match_id'])
			));
		}
	}
	$current_page = ( !count($match_entry) ) ? 1 : ceil( count($match_entry) / $settings['entry_per_page'] );
	//
	//	List Matches
	//
	
	//
	//	Teams
	//
	$sql = 'SELECT t.team_id, t.team_name, t.team_fight, g.game_size, g.game_image
				FROM ' . TEAMS_TABLE . ' t, ' . GAMES_TABLE . ' g
				WHERE t.team_game = g.game_id
			ORDER BY t.team_order';
	$teams = _cached($sql, 'list_teams_match_info');
	
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
				'FIGHTUS'		=> ( $teams[$i]['team_fight'] ) ? '<a href="' . append_sid("contact.php?mode=fightus&amp;" . POST_TEAMS_URL . "=".$teams[$i]['team_id']) . '">' . $lang['match_fightus'] . '</>'  : '',
			));
		}		
	}
	//
	//	Teams
	//
	
	
	$template->assign_vars(array(
		'L_DETAILS'		=> $lang['match_details'],
		
		'L_TEAMS'		=> $lang['teams'],
		'L_ALL_MATCHES'	=> $lang['all_matches'],
//		'L_FIGHTUS'		=> $lang['match_fightus'],
		'L_TO_TEAM'		=> $lang['to_team'],
		
		'L_UPCOMING'	=> $lang['match_upcoming'],
		'L_EXPIRED'		=> $lang['match_expired'],
		'PAGINATION'	=> generate_pagination("admin_match.php?", count($match_entry), $settings['entry_per_page'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['entry_per_page'] ) + 1 ), $current_page ), 
		'L_GOTO_PAGE'	=> $lang['Goto_page']
	));
}
else if ( $mode == 'matchdetails' && isset($HTTP_GET_VARS[POST_MATCH_URL]))
{	
	session_start();
	
	$page_title = $lang['match_details'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'match_details_body.tpl'));
	
	if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		$sql = 'SELECT	m.*, md.*, t.team_id, t.team_name, g.game_image, ml.match_id AS lineup_match_id, tr.training_vs, tr.training_start
				FROM ' . MATCH_TABLE . ' m
					LEFT JOIN ' . MATCH_DETAILS_TABLE . ' md ON m.match_id = md.match_id
					LEFT JOIN ' . MATCH_LINEUP_TABLE . ' ml ON m.match_id = ml.match_id
					LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
					LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . TRAINING_TABLE . ' tr ON tr.match_id = m.match_id
				WHERE m.match_id = ' . $match_id;
		$row_details = _cached($sql, 'match_details_' . $match_id . '_member', 1);
	}
	else
	{
		$sql = 'SELECT	m.*, md.*, t.team_id, t.team_name, g.game_image, ml.match_id AS lineup_match_id, tr.training_vs, tr.training_start
				FROM ' . MATCH_TABLE . ' m
					LEFT JOIN ' . MATCH_DETAILS_TABLE . ' md ON m.match_id = md.match_id
					LEFT JOIN ' . MATCH_LINEUP_TABLE . ' ml ON m.match_id = ml.match_id
					LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
					LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . TRAINING_TABLE . ' tr ON tr.match_id = m.match_id
				WHERE m.match_id = ' . $match_id . ' AND m.match_public = 1';
		$row_details = _cached($sql, 'match_details_' . $match_id . '_guest', 1);
	}
	
	if (!$row_details)
	{
		message_die(GENERAL_ERROR, 'Falsche ID ?');
	}		
	
	if ($userdata['auth_match'] || $userdata['user_level'] == ADMIN)
	{
		$template->assign_block_vars('match_edit', array(
			'EDIT_MATCH' => '<a href="./admin/admin_match.php?mode=edit&' . POST_MATCH_URL . '=' . $match_id . '&sid=' . $userdata['session_id'] . '" >&raquo; ' . $lang['edit_match'] . '</a>',
			'EDIT_MATCH_DETAILS' => '<a href="./admin/admin_match.php?mode=details&' . POST_MATCH_URL . '=' . $match_id . '&sid=' . $userdata['session_id'] . '" >&raquo; ' . $lang['edit_match_details'] . '</a>'
		));
	}

	$picture_path = $root_path . $settings['match_picture_path'];
	
	if ($row_details['details_mapa'] && $row_details['details_mapa_clan'] && $row_details['details_mapa_clan'])
	{
		$template->assign_block_vars('map_details_a', array());
	}
	if ($row_details['details_mapb'] && $row_details['details_mapb_clan'] && $row_details['details_mapb_clan'])
	{
		$template->assign_block_vars('map_details_b', array());
	}
	if ($row_details['details_mapc'] && $row_details['details_mapc_clan'] && $row_details['details_mapc_clan'])
	{
		$template->assign_block_vars('map_details_c', array());
	}
	if ($row_details['details_mapd'] && $row_details['details_mapd_clan'] && $row_details['details_mapd_clan'])
	{
		$template->assign_block_vars('map_details_d', array());
	}
	
	$map_pic_a	= $row_details['details_map_pic_a'];
	$map_pic_b	= $row_details['details_map_pic_b'];
	$map_pic_c	= $row_details['details_map_pic_c'];
	$map_pic_d	= $row_details['details_map_pic_d'];
	$map_pic_e	= $row_details['details_map_pic_e'];
	$map_pic_f	= $row_details['details_map_pic_f'];
	$map_pic_g	= $row_details['details_map_pic_g'];
	$map_pic_h	= $row_details['details_map_pic_h'];
	
	$pic_a	= $row_details['pic_a_preview'];
	$pic_b	= $row_details['pic_b_preview'];
	$pic_c	= $row_details['pic_c_preview'];
	$pic_d	= $row_details['pic_d_preview'];
	$pic_e	= $row_details['pic_e_preview'];
	$pic_f	= $row_details['pic_f_preview'];
	$pic_g	= $row_details['pic_g_preview'];
	$pic_h	= $row_details['pic_h_preview'];
	
	$pic_a	= ( $map_pic_a ) ? '<a href="' . $picture_path . '/' . $map_pic_a . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_a . '" alt="" border="" /></a>' : '';
	$pic_b	= ( $map_pic_b ) ? '<a href="' . $picture_path . '/' . $map_pic_b . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_b . '" alt="" border="" /></a>' : '';
	$pic_c	= ( $map_pic_c ) ? '<a href="' . $picture_path . '/' . $map_pic_c . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_c . '" alt="" border="" /></a>' : '';
	$pic_d	= ( $map_pic_d ) ? '<a href="' . $picture_path . '/' . $map_pic_d . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_d . '" alt="" border="" /></a>' : '';
	$pic_e	= ( $map_pic_e ) ? '<a href="' . $picture_path . '/' . $map_pic_e . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_e . '" alt="" border="" /></a>' : '';
	$pic_f	= ( $map_pic_f ) ? '<a href="' . $picture_path . '/' . $map_pic_f . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_f . '" alt="" border="" /></a>' : '';
	$pic_g	= ( $map_pic_g ) ? '<a href="' . $picture_path . '/' . $map_pic_g . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_g . '" alt="" border="" /></a>' : '';
	$pic_h	= ( $map_pic_h ) ? '<a href="' . $picture_path . '/' . $map_pic_h . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_h . '" alt="" border="" /></a>' : '';
	
	switch ($row_details['match_categorie'])
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
	
	switch ($row_details['match_type'])
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
	
	switch ($row_details['match_league'])
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
	}
	
	//
	//	Lineup Clan
	//
	if ( $row_details['lineup_match_id'] )
	{
		$template->assign_block_vars('clan', array());
		
		$sql = 'SELECT ml.user_id, ml.status, u.username
					FROM ' . MATCH_LINEUP_TABLE . ' ml, ' . USERS_TABLE . ' u
					WHERE match_id = ' . $match_id . ' AND u.user_id = ml.user_id
				ORDER BY ml.status';
		$result = $db->sql_query($sql);
		
		if (!($row = $db->sql_fetchrow($result)))
		{
			$db->sql_freeresult($result);
			message_die(GENERAL_MESSAGE, 'error', '', __LINE__, __FILE__, $sql);
		}
		
		do
		{
			$type = $row['status'];
			$username_ary[$type][$row['user_id']] = $row['username'];
		}
		while ($row = $db->sql_fetchrow($result));
		$db->sql_freeresult($result);
		
		
		foreach ($username_ary as $type => $row_ary)
		{
			$replace = ($type == '1') ? $lang['replace'] : '';
			
			$clan_players = array();
			foreach ($row_ary as $user_id => $username)
			{
				$clan_players[] = '<a href="profile.php?user=' . $user_id . '">' . $username . '</a>';
			}
			
			$clan_players = $replace . implode(', ', $clan_players);
			
			$template->assign_block_vars('clan.clan_lineup', array(
				'PLAYERS' => $clan_players
			));
		}
	}
	
	if ( $row_details['details_lineup_rival'] )
	{
		$template->assign_block_vars('rival', array());
		
		$template->assign_block_vars('rival.rival_lineup', array(
			'PLAYERS' => $row_details['details_lineup_rival']
		));
	}
	
	if ($userdata['session_logged_in'] && ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN))
	{
		$sql = 'SELECT mu.*, u.username
					FROM ' . MATCH_USERS_TABLE . ' mu, ' . USERS_TABLE . ' u
				WHERE mu.user_id = u.user_id AND match_id = ' . $match_id;
		$result = $db->sql_query($sql);

		$template->assign_block_vars('match_users', array());
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			switch ($row['match_users_status'])
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
			
			$template->assign_block_vars('match_users.match_users_status', array(
				'CLASS' 		=> $class,
				'USERNAME'		=> $row['username'],
				'STATUS'		=> $status,
				'DATE'			=> ($row['match_users_update']) ? $lang['change_on'] . create_date($userdata['user_dateformat'], $row['match_users_update'], $userdata['user_timezone']) : create_date($userdata['user_dateformat'], $row['match_users_create'], $userdata['user_timezone'])
			));
		}
		
		if (!$db->sql_numrows($result))
		{
			$template->assign_block_vars('match_users.no_entry_status', array());
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
		
		if ($db->sql_numrows($result) && $row_details['match_date'] > time())
		{
			$template->assign_block_vars('match_users.users_status', array());
			
			$sql = 'SELECT match_users_status
						FROM ' . MATCH_USERS_TABLE . '
					WHERE user_id = ' . $userdata['user_id'] . ' AND match_id = ' . $match_id;
			$result = $db->sql_query($sql);
			
			$row = $db->sql_fetchrow($result);
			
			$match_users_status = ($row['match_users_status'] == 0) ? '0' : $row['match_users_status'];
			
			$s_hidden_fielda = '<input type="hidden" name="mode" value="change" />';
			$s_hidden_fielda .= '<input type="hidden" name="users_status" value="' . $match_users_status . '" />';
			$s_hidden_fielda .= '<input type="hidden" name="' . POST_MATCH_URL . '" value="' . $match_id . '" />';
			
			$template->assign_vars(array(
				'S_CHECKED_1'		=> ( $row['match_users_status'] == 1 ) ? 'checked="checked"' : '',
				'S_CHECKED_2'		=> ( $row['match_users_status'] == 2 ) ? 'checked="checked"' : '',
				'S_CHECKED_3'		=> ( $row['match_users_status'] == 3 ) ? 'checked="checked"' : '',
				
				'S_HIDDEN_FIELDA'	=> $s_hidden_fielda,
			));
		}
	}
	
	$s_hidden_fieldb = '<input type="hidden" name="mode" value="matchdetails" />';
	$s_hidden_fieldb .= '<input type="hidden" name="' . POST_MATCH_URL . '" value="' . $match_id . '" />';
	
	
	$classa = '';
	$classb = '';
	$classc = '';
	$classd = '';
	
	if		($row_details['details_mapa_clan'] > $row_details['details_mapa_rival']) $classa = 'win';
	else if	($row_details['details_mapa_clan'] < $row_details['details_mapa_rival']) $classa = 'lose';
	else if	($row_details['details_mapa_clan'] = $row_details['details_mapa_rival']) $classa = 'draw';
	
	if		($row_details['details_mapb_clan'] > $row_details['details_mapb_rival']) $classb = 'win';
	else if	($row_details['details_mapb_clan'] < $row_details['details_mapb_rival']) $classb = 'lose';
	else if	($row_details['details_mapb_clan'] = $row_details['details_mapb_rival']) $classb = 'draw';
	
	if		($row_details['details_mapc_clan'] > $row_details['details_mapc_rival']) $classc = 'win';
	else if	($row_details['details_mapc_clan'] < $row_details['details_mapc_rival']) $classc = 'lose';
	else if	($row_details['details_mapc_clan'] = $row_details['details_mapc_rival']) $classc = 'draw';
	
	if		($row_details['details_mapd_clan'] > $row_details['details_mapd_rival']) $classd = 'win';
	else if	($row_details['details_mapd_clan'] < $row_details['details_mapd_rival']) $classd = 'lose';
	else if	($row_details['details_mapd_clan'] = $row_details['details_mapd_rival']) $classd = 'draw';
	
	//	Comments
	if ($settings['comments_matches'] && $row_details['match_comments'])
	{
		$template->assign_block_vars('match_comments', array());
		
		$sql = 'SELECT mc.*, u.username, u.user_email
					FROM ' . MATCH_COMMENTS_TABLE . ' mc
						LEFT JOIN ' . USERS_TABLE . ' u ON mc.poster_id = u.user_id
					WHERE match_id = ' . $match_id . ' ORDER BY time_create DESC';
		$comment_entry = _cached($sql, 'match_' . $match_id . '_comments');
	
		if (!$comment_entry)
		{
			$template->assign_block_vars('match_comments.no_entry', array());
			$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
		}
		else
		{
			for($i = $start; $i < min($settings['comment_per_page'] + $start, count($comment_entry)); $i++)
			{
				$class = ($i % 2) ? 'row1' : 'row2';
				
				$comment = html_entity_decode($comment_entry[$i]['text'], ENT_QUOTES);
	
				$template->assign_block_vars('match_comments.comments', array(
					'CLASS' 		=> $class,
					'ID' 			=> $comment_entry[$i]['match_comments_id'],
					'L_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_nick'] : $comment_entry[$i]['username'],
	//				'U_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_email'] : $comment_entry[$i]['user_email'],	Profil-Link und Mail schreiben an Gast
					'MESSAGE'		=> $comment,
					'DATE'			=> create_date($userdata['user_dateformat'], $comment_entry[$i]['time_create'], $userdata['user_timezone']),
	
					'U_EDIT'		=> append_sid("admin_match.php?mode=edit&amp;" . POST_MATCH_URL . "=".$comment_entry[$i]['match_id']),
					'U_DELETE'		=> append_sid("admin_match.php?mode=delete&amp;" . POST_MATCH_URL . "=".$comment_entry[$i]['match_id'])
				));
			}
		
			$current_page = ( !count($comment_entry) ) ? 1 : ceil( count($comment_entry) / $settings['comment_per_page'] );
			
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=" . $match_id, count($comment_entry), $settings['comment_per_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['comment_per_page'] ) + 1 ), $current_page ), 
			
				'L_GOTO_PAGE' => $lang['Goto_page'])
			);
		
		
		
		sort($comment_entry);
		$last_entry = array_pop($comment_entry);
		
		}
//		if ($settings['comments_matches_guest'] && !$userdata['session_logged_in'] && $last_entry['poster_ip'] != $userdata['session_ip'])
		if ($settings['comments_matches_guest'] && !$userdata['session_logged_in'])
		{
			$template->assign_block_vars('match_comments.match_comments_guest', array());
		}
		
		if ($userdata['session_logged_in'])
		{
			$template->assign_block_vars('match_comments.match_comments_member', array());
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
				$oCache -> deleteCache('match_' . $match_id . '_comments');
				
				_comment_message('add', 'match', $match_id, $userdata['user_id'], $poster_nick, $poster_mail, '', $user_ip, $HTTP_POST_VARS['comment']);
			
				$message = $lang['add_comment'] . '<br /><br />' . sprintf($lang['click_return_match'],  '<a href="' . append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=" . $match_id) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
	}
	
	$template->assign_vars(array(
		
		'L_SUBMIT'				=> $lang['Submit'],
		
		'MATCH_RIVAL'			=> $row_details['match_rival'],
		'U_MATCH_RIVAL_URL'		=> $row_details['match_rival_url'],
		'MATCH_RIVAL_URL'		=> $row_details['match_rival_url'],
		'MATCH_RIVAL_TAG'		=> $row_details['match_rival_tag'],
		
	
		'MATCH_CATEGORIE'		=> $match_categorie,
		'MATCH_TYPE'			=> $match_type,
		'MATCH_LEAGUE_INFO'		=> $match_league,
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
	
		'S_HIDDEN_FIELDB'		=> $s_hidden_fieldb,
		'S_MATCH_ACTION'		=> append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=" . $match_id))
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
		$sql = 'INSERT INTO ' . MATCH_USERS_TABLE . " (match_id, user_id, match_users_status, match_users_create, match_users_update)
			VALUES ($match_id, " . $userdata['user_id'] . ", '" . intval($HTTP_POST_VARS['match_users_status']) . "', '" . time() . "', 0)";
		$result = $db->sql_query($sql);
		
		$message = $lang['update_match_status_add'];

		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'UCP_STATUS_ADD');
	}
	else if ($HTTP_POST_VARS['match_users_status'] != $HTTP_POST_VARS['users_status'])
	{
		$sql = "UPDATE " . MATCH_USERS_TABLE . " SET
					match_users_status		= '" . intval($HTTP_POST_VARS['match_users_status']) . "',
					match_users_update		= '" . time() . "'
				WHERE match_id = $match_id AND user_id = " . $userdata['user_id'];
		$result = $db->sql_query($sql);
		
		$message = $lang['update_match_status_edit'];
		
		_log(LOG_USERS, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_MATCH, 'UCP_STATUS_EDIT');
	}
	else
	{
		$message = $lang['update_match_status_none'];
	}

	$template->assign_vars(array("META" => '<meta http-equiv="refresh" content="3;url=' . append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=" . $match_id) . '">'));
	message_die(GENERAL_MESSAGE, $message);
}
/*
else if ($mode == 'addcomment')
{
	//
	//	Kommentarhinzufügen
	//	
	//	geht soweit nur das bei Falscher eingabe man zurück geschickt wird
	//	und alle Felder bei Gästen wieder leer sind, muss andere möglichkeit her
	//	vorerst auch nur add funktion keine edit oder delete
	//
	//	01.03.2009 Phoenix
	//
	include($root_path . 'includes/functions_post.php');
	
	session_start();
	
	$error = '';
	$error_msg = '';
	
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
	
	if ($error)
	{
		$error_msg .= "<br /><br />" . sprintf($lang['wrong_back'], "<a style='color:#fff; font-weight:bold; font-size:blod;' href=\"" . append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=" . $match_id) . '">', '</a>');
		message_die(GENERAL_ERROR, $error_msg);
	}
	
	$username	= (!$userdata['session_logged_in']) ? trim($HTTP_POST_VARS['poster_nick']) : $userdata['username'];
	$email		= (!$userdata['session_logged_in']) ? trim($HTTP_POST_VARS['poster_mail']) : '';
	
	_comment_message('add', 'match', $match_id, $userdata['user_id'], $username, $email, '', $user_ip, $HTTP_POST_VARS['comment']);
	
	$message = $lang['add_comment'];
	$template->assign_vars(array("META" => '<meta http-equiv="refresh" content="3;url=' . append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=" . $match_id) . '">'));
	message_die(GENERAL_MESSAGE, $message);
}
*/
else if ($mode == 'teammatches' && isset($HTTP_GET_VARS[POST_TEAMS_URL]))
{
	$page_title = $lang['match'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'match_teams_body.tpl'));
	
	$team_id = $HTTP_GET_VARS[POST_TEAMS_URL];
	
	//
	//	Team Details
	//
	$sql = 'SELECT * FROM ' . TEAMS_TABLE . ' WHERE team_id = ' . $team_id;
	if (!($result_team = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain list', '', __LINE__, __FILE__, $sql);
	}
	$teams = $db->sql_fetchrow($result_team);
	$db->sql_freeresult($result_team);
	
	//
	//	List Matches von Team
	//
	$where = ($userdata['session_logged_in']) ? '' : ' AND m.match_public = 1';
	
	$sql = 'SELECT m.*, t.team_name, g.game_image, g.game_size, tr.training_id
				FROM ' . MATCH_TABLE . ' m
					LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
					LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . TRAINING_TABLE . ' tr ON m.match_id = tr.match_id
				WHERE m.team_id = ' . $team_id . $where . '
			ORDER BY m.match_date DESC';
	if (!($result_list = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain list', '', __LINE__, __FILE__, $sql);
	}
	
	$match_entry = $db->sql_fetchrowset($result_list); 
	$match_count = count($match_entry);
	$db->sql_freeresult($result_list);
	
	for($i = $start; $i < min($settings['entry_per_page'] + $start, $match_count); $i++)
	{
		$class = ($color % 2) ? 'row1r' : 'row2r';
		$color++;
		
		$game_size	= $match_entry[$i]['game_size'];
		$game_image	= '<img src="' . $root_path . $settings['game_path'] . '/' . $match_entry[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
		
		$match_name	= ($match_entry[$i]['match_public']) ? 'vs. ' . $match_entry[$i]['match_rival'] : 'vs. <span style="font-style:italic;">' . $match_entry[$i]['match_rival'] . '</span>';
		
		$template->assign_block_vars('match_teams', array(
			'CLASS' 		=> $class,
			'MATCH_GAME'	=> $game_image,
			'MATCH_NAME'	=> $match_name,
			'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $match_entry[$i]['match_date'], $userdata['user_timezone']),
			'U_DETAILS'		=> append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=".$match_entry[$i]['match_id'])
		));
	}
	
	if ( !$match_count )
	{
		$template->assign_block_vars('no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$current_page = ( !$match_count ) ? 1 : ceil( $match_count / $settings['entry_per_page'] );

	$template->assign_vars(array(
		'TEAM_NAME'		=> $teams['team_name']
	));
}
else
{
	redirect(append_sid('match.php', true));
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>