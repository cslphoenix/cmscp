<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_TEAM);
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

if ( !$mode )
{
	$page_title = $lang['teams'];
	$template->set_filenames(array('body' => 'teams_body.tpl'));

	$sql = "SELECT g.* FROM cms_game g, cms_teams t WHERE g.game_id = t.team_game ORDER BY game_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$games = $db->sql_fetchrowset($result);
	
	$sql = "SELECT t.*, m.match_id FROM cms_teams t
				LEFT JOIN cms_match m ON m.match_id = t.team_id
			ORDER BY team_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$teams = $db->sql_fetchrowset($result);
	
	_debug_post(array_keys($games));
	
	//	Multi-Array in einfaches Array
	foreach ($games as $game)
	{
		$game_ids[] = implode(', ', $game);
	}
	
	//	Löschen der Doppelten Einträge
	$games = array_unique($game_ids);

	//	Einfaches Array in Multi-Array
	foreach ($games as $game)
	{
		$cleargame[] = explode(', ', $game);
	}
	
//	_debug_post($cleargame);
	
	for($i = 0; $i < count($cleargame); $i++)
	{
		$game_id = $cleargame[$i]['0'];
		$template->assign_block_vars('game_row', array(
			'L_GAME_NAME'	=> $cleargame[$i]['1'],
			'GAME_IMAGE'	=> display_gameicon($cleargame[$i]['3'], $cleargame[$i]['2']),
		));
															 
		for($j = 0; $j < count($teams); $j++)
		{
			if ( $teams[$j]['team_game'] == $game_id )
			{
				$template->assign_block_vars('game_row.team_row', array(
					'TEAM_NAME'		=> $teams[$j]['team_name'],
					'TEAM_MATCH'	=> ($teams[$j]['match_id'])		? '<a href="' . append_sid("match.php?mode=teammatches&amp;" . POST_TEAMS_URL . "=".$teams[$j]['team_id']) . '">' . $lang['all_matches'] . '</a>'  : '',
					'TEAM_JOINUS'	=> ($teams[$j]['team_join'])	? '<a href="' . append_sid("contact.php?mode=joinus&amp;" . POST_TEAMS_URL . "=".$teams[$j]['team_id']) . '">' . $lang['match_joinus'] . '</a>'  : '',
					'TEAM_FIGHTUS'	=> ($teams[$j]['team_fight'])	? '<a href="' . append_sid("contact.php?mode=fightus&amp;" . POST_TEAMS_URL . "=".$teams[$j]['team_id']) . '">' . $lang['match_fightus'] . '</a>'  : '',
					'TO_TEAM'		=> append_sid("teams.php?mode=view&amp;" . POST_TEAMS_URL . "=".$teams[$j]['team_id']),
				));
			}
		}
	}
}
else if ( $mode == 'view' && intval($HTTP_GET_VARS[POST_TEAMS_URL]) )
{
//	$page_title = $lang['team'];
	$template->set_filenames(array('body' => 'team_body.tpl'));
	
	if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		$sql = 'SELECT t.*, g.game_size, g.game_image, m.match_id
					FROM ' . TEAMS_TABLE . ' t
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . MATCH_TABLE . ' m ON t.team_id = m.team_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON tr.team_id = t.team_id
					WHERE t.team_id = ' . $team_id . '';
//		$team = _cached($sql, 'team_details_' . $team_id . '_member', 1);
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$team = $db->sql_fetchrow($result);
	}
	else
	{
		$sql = 'SELECT t.*, g.game_size, g.game_image
					FROM ' . TEAMS_TABLE . ' t
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . MATCH_TABLE . ' m ON m.team_id = t.team_id
					WHERE t.team_id = ' . $team_id . ' AND t.team_view = 1';
//		$team = _cached($sql, 'team_details_' . $team_id . '_guest', 1);
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$team = $db->sql_fetchrow($result);
	}

}
else
{
	redirect(append_sid('teams.php', true));
}

include($root_path . 'includes/page_header.php');

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>