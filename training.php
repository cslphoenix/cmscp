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

if ($mode == '' && ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN))
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
	$result_new = $db->sql_query($sql);
	
	$sql = 'SELECT tr.*, t.team_name, g.game_image, g.game_size, m.match_rival
				FROM ' . TRAINING_TABLE . ' tr
					LEFT JOIN ' . TEAMS_TABLE . ' t ON tr.team_id = t.team_id
					LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
					LEFT JOIN ' . MATCH_TABLE . ' m ON m.team_id = tr.team_id
				WHERE training_start < ' . time() . '
			ORDER BY tr.training_start DESC';
	$result_old = $db->sql_query($sql);
	
	$training_entry = $db->sql_fetchrowset($result_old); 
	$training_count = count($training_entry);
	$db->sql_freeresult($result_old);
	
	$color = '';
	
	if (!$db->sql_numrows($result_new))
	{
		$template->assign_block_vars('no_entry_new', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	while ($training_entry = $db->sql_fetchrow($result_new))
	{
		$class = ($color % 2) ? 'row1r' : 'row2r';
		$color++;
		
		$game_size	= $training_entry['game_size'];
		$game_image	= '<img src="' . $root_path . $settings['game_path'] . '/' . $training_entry['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
		
		$training_name	= ($training_entry['match_rival']) ? $lang['training_vs'] . $training_entry['match_rival'] . ' <span style="font-style:italic;">' . $training_entry['training_vs'] . '</span>' : $training_entry['training_vs'];
		
		$template->assign_block_vars('training_row_new', array(
			'CLASS' 		=> $class,
			'MATCH_GAME'	=> $game_image,
			'MATCH_NAME'	=> $training_name,
			'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $training_entry['training_start'], $userdata['user_timezone']),
			'U_DETAILS'		=> append_sid("training.php?mode=trainingdetails&amp;" . POST_TRAINING_URL . "=".$training_entry['training_id'])
		));
	}
	/*
	for($i = $start; $i < min($settings['entry_per_page'] + $start, $training_count); $i++)
	{
		$game_size	= $training_entry[$i]['game_size'];
		$game_image	= '<img src="' . $root_path . $settings['game_path'] . '/' . $training_entry[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
		
		$training_name	= ($training_entry[$i]['match_rival']) ? $lang['training_vs'] . $training_entry[$i]['match_rival'] . ' <span style="font-style:italic;">' . $training_entry[$i]['training_vs'] . '</span>' : $training_entry[$i]['training_vs'];
		
		if ($training_entry[$i]['training_start'] > $time)
		{		
			$template->assign_block_vars('training_row_n', array(
				'CLASS' 		=> $class,
				'MATCH_GAME'	=> $game_image,
				'MATCH_NAME'	=> $training_name,
				'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $training_entry[$i]['training_date'], $userdata['user_timezone']),
				'U_DETAILS'		=> append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=".$training_entry[$i]['training_id'])
			));
		}
		else if ($training_entry[$i]['training_start'] < $time)
		{
			$template->assign_block_vars('training_row_o', array(
				'CLASS' 		=> $class,
				'MATCH_GAME'	=> $game_image,
				'MATCH_NAME'	=> $training_name,
				'MATCH_DATE'	=> create_date($userdata['user_dateformat'], $training_old_entry[$i]['training_date'], $userdata['user_timezone']),
				'U_DETAILS'		=> append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=".$training_old_entry[$i]['training_id'])
			));
		}
	}
	
	if ( !$training_count )
	{
		$template->assign_block_vars('no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	*/
	$current_page = ( !$training_count ) ? 1 : ceil( $training_count / $settings['entry_per_page'] );
	
	$template->assign_vars(array(
		'PAGINATION'	=> generate_pagination("admin_match.php?", $training_count, $settings['entry_per_page'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['entry_per_page'] ) + 1 ), $current_page ), 
		'L_GOTO_PAGE'	=> $lang['Goto_page']
	));
	
	//
	//	Teams
	//
	if (defined('CACHE'))
	{
		$oCache = new Cache;
		
		$sCacheName = 'list_teams_info';
		if ( ($teams = $oCache -> readCache($sCacheName)) === false)
		{
			$sql = 'SELECT t.*, g.*
				FROM ' . TEAMS_TABLE . ' t, ' . GAMES_TABLE . ' g
				WHERE t.team_game = g.game_id
			ORDER BY t.team_order';
			$result_teams = $db->sql_query($sql);
			$teams = $db->sql_fetchrowset($result_teams);
			$db->sql_freeresult($result_teams);
			
			$oCache -> writeCache($sCacheName, $teams);
		}
	}
	else
	{
		$sql = 'SELECT t.*, g.*
				FROM ' . TEAMS_TABLE . ' t, ' . GAMES_TABLE . ' g
				WHERE t.team_game = g.game_id
			ORDER BY t.team_order';
		$result_teams = $db->sql_query($sql);
		$teams = $db->sql_fetchrowset($result_teams);
		$db->sql_freeresult($result_teams);
	}

	$teams_count = count($teams);
	
	for ($i = $start; $i < $teams_count + $start; $i++)
	{
		$class = ($i % 2) ? 'row1r' : 'row2r';
		
		$game_size	= $teams[$i]['game_size'];
		$game_image	= '<img src="' . $root_path . $settings['game_path'] . '/' . $teams[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
		
		$template->assign_block_vars('teams_row', array(
			'CLASS' 		=> $class,
			'TEAM_GAME'		=> $game_image,
			'TEAM_NAME'		=> $teams[$i]['team_name'],
			'ALL_MATCHES'	=> append_sid("match.php?mode=teammatches&amp;" . POST_TEAMS_URL . "=".$teams[$i]['team_id']),
			'TO_TEAM'		=> append_sid("teams.php?mode=show&amp;" . POST_TEAMS_URL . "=".$teams[$i]['team_id'])
		));
	}
	
	if (!$teams_count)
	{
		$template->assign_block_vars('no_entry_team', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
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
else
{
	message_die(GENERAL_ERROR, $lang['training_denied']);
}

//
//	Generate the page
//
$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>