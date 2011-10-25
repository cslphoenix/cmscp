<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_USERS);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', 0) ) ? request('start', 0) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_USERS;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$mode	= request('mode', 1);
$data	= request('id', 0);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_users.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'info_error.tpl',
));

main_header();

if ( in_array($mode, array('m', 'u')) )
{
	$template->assign_block_vars('list', array());
	
	$sql = "SELECT * FROM " . USERS . " WHERE ";
	
	switch ($mode)
	{
		case 'u': $sql .= "user_id <> " . ANONYMOUS;	break;
		case 'm': $sql .= "user_level >= 2";			break;
	}

	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$users = $db->sql_fetchrowset($result);
	$count = count($users);
	
	$ugroups = $uteams = '';
	
	if ( $settings['userlist_groups'] )
	{
		$template->assign_block_vars('list._groups', array());
		
		$sql = "SELECT g.group_id, g.group_name, g.group_type, gu.user_id
					FROM " . GROUPS . " g
						LEFT JOIN " . GROUPS_USERS . " gu ON gu.group_id = g.group_id
					WHERE g.group_single_user = 0
				ORDER BY g.group_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			if ( $row['group_type'] != GROUP_HIDDEN )
			{
				$groups[$row['user_id']][] = array('group_id' => $row['group_id'], 'group_name' => $row['group_name']);
			}
		}
	}
	
	if ( $settings['userlist_teams'] )
	{
		$template->assign_block_vars('list._teams', array());
		
		$sql = "SELECT t.team_id, t.team_name, tu.user_id, g.game_image, g.game_size
					FROM " . TEAMS . " t
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
						LEFT JOIN " . TEAMS_USERS . " tu ON tu.team_id = t.team_id
				ORDER BY t.team_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$teams[$row['user_id']][] = array('team_id' => $row['team_id'], 'team_name' => $row['team_name'], 'game_image' => $row['game_image'], 'game_size' => $row['game_size']);
		}
	}
	
	for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, $count); $i++ )
	{
		$user_id = $users[$i]['user_id'];
	
		gen_userinfo($users[$i], $username);
		
		if ( isset($groups[$user_id]) )
		{
			foreach ( $groups[$user_id] as $row )
			{
				$g_ary[$user_id][] =  '<a href="' . check_sid('groups.php?' . POST_GROUPS . '=' . $row['group_id']) . '">' . $row['group_name'] . '</a>';
			}
		
			$ugroups = implode('<br />', $g_ary[$user_id]);
		}
		
		if ( isset($teams[$user_id]) )
		{
			foreach ( $teams[$user_id] as $row )
			{
				$game = display_gameicon($row['game_size'], $row['game_image']);
				$t_ary[$user_id][] =  $game . ' <a href="' . check_sid('teams.php?' . POST_TEAMS . '=' . $row['team_id']) . '">' . $row['team_name'] . '</a>';
			}
		
			$uteams = implode('<br />', $t_ary[$user_id]);
		}
		
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
		$template->assign_block_vars('list._row', array(
			'ROW_COLOR'	=> '#' . $row_color,
			'ROW_CLASS'	=> $row_class,
			
			'USERNAME'	=> $username,
			
			'GROUPS'	=> $ugroups,
			'TEAMS'		=> $uteams,
		));
		
		if ( $settings['userlist_groups'] )
		{
			$template->assign_block_vars('list._row._groups', array());
		}
		
		if ( $settings['userlist_teams'] )
		{
			$template->assign_block_vars('list._row._teams', array());
		}
	}
	
	$template->assign_vars(array(
	#	'PAGINATION' => $pagination,
	#	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $config['site_entry_per_page'] ) + 1 ), ceil( $total_members / $config['site_entry_per_page'] )),
	
	#	'L_GOTO_PAGE' => $lang['Goto_page']
	));
}
else if ( in_array($mode, array('g', 't')) )
{
	if ( $data )
	{
		$template->assign_block_vars('_block', array());
		
		switch( $mode )
		{
			case 'g':
				$tbl_sql	= GROUPS;
				$tbl_usr	= GROUPS_USERS;
				$tbl_id		= 'group_id';
				$tbl_where	= ', tu.group_mod, tu.user_pending';
				
				break;
			
			case 't':
				$tbl_sql	= TEAMS;
				$tbl_usr	= TEAMS_USERS;
				$tbl_id		= 'team_id';
				$tbl_where	= ', tu.team_mod';
				
				break;
		}
		
		$sql = "SELECT * FROM $tbl_sql WHERE $tbl_id = $data";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$info = $db->sql_fetchrowset($result);
		
		$sql = "SELECT u.* $tbl_where FROM $tbl_usr tu
					LEFT JOIN " . USERS . " u ON u.user_id = tu.user_id
				 WHERE $tbl_id = $data";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$users = $db->sql_fetchrowset($result);
		$count = count($users);
		
		if ( $users )
		{
			foreach ( $users as $key => $value )
			{
				if ( $mode == 'g' )
				{
					if ( $value['group_mod'] )
					{
						$ary_mod[] = $value;
					}
					else if ( $value['user_pending'] )
					{
						$ary_pen[] = $value;
					}
					else
					{
						$ary_mem[] = $value;
					}
				}
				else
				{
					if ( $value['team_mod'] )
					{
						$ary_mod[] = $value;
					}
					else
					{
						$ary_mem[] = $value;
					}
				}
			}
		}
		
		$cnt_mod = count($ary_mod);
		$cnt_mem = count($ary_mem);
		$cnt_mem = isset($ary_pen) ? count($ary_pen) : '';
		
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, $cnt_mod); $i++ )
		{
			$user_id = $ary_mod[$i]['user_id'];
			
			gen_userinfo($ary_mod[$i], $username);
			
			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$template->assign_block_vars('_block._mod', array(
				'ROW_COLOR'	=> '#' . $row_color,
				'ROW_CLASS'	=> $row_class,
				
				'USERNAME'	=> $username,
			));
		}
	}
	else
	{
		switch( $mode )
		{
			case 'g':
			
				$template->assign_block_vars('_listg', array());
				
				$sql = "SELECT g.group_id, g.group_name, g.group_type, g.group_desc, gu.user_pending
							FROM " . GROUPS . " g, " . GROUPS_USERS . " gu
						WHERE gu.user_id = " . $userdata['user_id'] . " AND gu.group_id = g.group_id AND g.group_single_user <> 1
						ORDER BY g.group_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$groups = $db->sql_fetchrowset($result);
				
				$is_pending = $is_member = array();
				
				if ( $groups )
				{
					$template->assign_block_vars('_listg._in_groups', array());
				
					foreach ( $groups as $group => $row )
					{
						$in_group[] = $row['group_id'];
				
						if ( $row['user_pending'] )
						{
							$is_pending[] = $row;
						}
						else
						{
							$is_member[] = $row;
						}
					}
				
					if ( $is_member )
					{
						$template->assign_block_vars('_listg._in_groups._is_member', array());
				
						for ( $i = 0; $i < count($is_member); $i++ )
						{
							switch ( $is_member[$i]['group_type'] )
							{
								case GROUP_OPEN:	$group_type = $lang['Group_open'];		break;
								case GROUP_REQUEST:	$group_type = $lang['Group_quest'];		break;
								case GROUP_CLOSED:	$group_type = $lang['Group_closed'];	break;
								case GROUP_HIDDEN:	$group_type = $lang['Group_hidden'];	break;
								case GROUP_SYSTEM:	$group_type = $lang['Group_system'];	break;
							}
							
							$template->assign_block_vars('_listg._in_groups._is_member._row', array(
								'NAME' => "<a href=" . check_sid("$file?mode=g&amp;id=" . $is_member[$i]['group_id']) . ">" . $is_member[$i]['group_name'] . "</a>",
								'DESC' => ( $is_member[$i]['group_desc'] ) ? ' :: ' . $is_member[$i]['group_desc'] : '',
								'TYPE' => $group_type,
							));
						}
					}
				
					if ( $is_pending )
					{
						$template->assign_block_vars('_listg._in_groups._is_pending', array());
					
						for ($i = 0; $i < count($is_pending); $i++)
						{
							switch ( $is_pending[$i]['group_type'] )
							{
								case GROUP_OPEN:	$group_type = $lang['Group_open'];		break;
								case GROUP_REQUEST:	$group_type = $lang['Group_quest'];		break;
								case GROUP_CLOSED:	$group_type = $lang['Group_closed'];	break;
								case GROUP_HIDDEN:	$group_type = $lang['Group_hidden'];	break;
								case GROUP_SYSTEM:	$group_type = $lang['Group_system'];	break;
							}
					
							$template->assign_block_vars('_listg._in_groups._is_pending._row', array(
								'NAME' => "<a href=" . check_sid("$file?mode=g&amp;id=" . $is_pending[$i]['group_id']) . ">" . $is_pending[$i]['group_name'] . "</a>",
								'DESC' => ( $is_pending[$i]['group_desc'] ) ? ' :: ' . $is_pending[$i]['group_desc'] : '',
								'TYPE' => $group_type,
							));
						}
					}
				}
			
				$ignore_group = ( isset($in_group) ) ? ( count($in_group) ) ? 'AND group_id NOT IN (' . implode(', ', $in_group) . ')' : '' : '';
				
				$sql = "SELECT group_id, group_name, group_type, group_desc
							FROM " . GROUPS . "
						WHERE group_single_user <> 1 $ignore_group
						ORDER BY group_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$no_group = $db->sql_fetchrowset($result);
			
				if ( $no_group )
				{
					$template->assign_block_vars('_listg._no_group', array());
					
					for ( $i = 0; $i < count($no_group); $i++ )
					{
						switch ( $no_group[$i]['group_type'] )
						{
							case GROUP_OPEN:	$group_type = $lang['Group_open'];		break;
							case GROUP_REQUEST:	$group_type = $lang['Group_quest'];		break;
							case GROUP_CLOSED:	$group_type = $lang['Group_closed'];	break;
							case GROUP_HIDDEN:	$group_type = $lang['Group_hidden'];	break;
							case GROUP_SYSTEM:	$group_type = $lang['Group_system'];	break;
						}
						
						if ( $no_group[$i]['group_type'] != GROUP_HIDDEN )
						{
							$template->assign_block_vars('_listg._no_group._row', array(
								'NAME' => "<a href=" . check_sid("$file?mode=g&amp;id=" . $no_group[$i]['group_id']) . ">" . $no_group[$i]['group_name'] . "</a>",
								'DESC' => ( $no_group[$i]['group_desc'] ) ? ' :: ' . $no_group[$i]['group_desc'] : '',
								'TYPE' => $group_type,
							));
						}
					}
				}
					
				$template->assign_vars(array(
					'L_MAIN'	=> $page_title,
					'L_CUR'		=> $lang['grp_cur_member'],
					'L_PEN'		=> $lang['grp_pen_member'],
					'L_NON'		=> $lang['grp_non_member'],
				));
				
				break;
			
			case 't':
			
				$template->assign_block_vars('_listt', array());
				
				$sql = "SELECT DISTINCT g.* FROM " . GAMES . " g, " . TEAMS . " t WHERE g.game_id = t.team_game ORDER BY game_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$games = $db->sql_fetchrowset($result);
			//	$games = _cached($sql, 'data_games');
			
				$sql = "SELECT * FROM " . TEAMS . " ORDER BY team_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$teams = $db->sql_fetchrowset($result);
			//	$teams = _cached($sql, 'data_teams');
				
				for ( $i = 0; $i < count($games); $i++ )
				{
					$game_id = $games[$i]['game_id'];
					
					$template->assign_block_vars('_listt._game_row', array('L_GAME' => $games[$i]['game_name']));
																		 
					for ( $j = 0; $j < count($teams); $j++ )
					{
						$team_id	= $teams[$j]['team_id'];
						$team_game	= $teams[$j]['team_game'];
						
						if ( $team_game == $game_id )
						{
							$template->assign_block_vars('_listt._game_row._team_row', array(
								'NAME'		=> '<a href="' . check_sid("$file?mode=t&amp;id=$team_id") . '">' . $teams[$j]['team_name'] . '</a>',
								'GAME'		=> display_gameicon($games[$i]['game_size'], $games[$i]['game_image']),
								
							#	'JOINUS'	=> $teams[$j]['team_join']	? '<a href="' . check_sid("contact.php?mode=joinus&amp;$url=$team_id") . '">' . $lang['match_joinus'] . '</a>'  : '',
							#	'FIGHTUS'	=> $teams[$j]['team_fight']	? '<a href="' . check_sid("contact.php?mode=fightus&amp;$url=$team_id") . '">' . $lang['match_fightus'] . '</a>'  : '',
							));
						}
					}
				}
				
				break;
		}
	}	
}
else
{
	$template->assign_block_vars('default', array());
	
	echo 'default';
}

/*
	    if ( isset($teams[$user_id]) )
		{
			foreach ( $teams[$user_id] as $row )
			{
				$game = display_gameicon($row['game_size'], $row['game_image']);
				$t_ary[$user_id][] =  $game . ' <a href="' . check_sid('teams.php?' . POST_TEAMS . '=' . $row['team_id']) . '">' . $row['team_name'] . '</a>';
			}
		
			$uteams = implode('<br />', $t_ary[$user_id]);
		}
		
		if ( isset($groups[$user_id]) )
		{
			foreach ( $groups[$user_id] as $row )
			{
				$g_ary[$user_id][] =  '<a href="' . check_sid('groups.php?' . POST_GROUPS . '=' . $row['group_id']) . '">' . $row['group_name'] . '</a>';
			}
		
			$ugroups = implode('<br />', $g_ary[$user_id]);
		}
		*/

/*
	$sql = "SELECT * FROM " . USERS . " WHERE user_id <> " . ANONYMOUS . " $letter_sql ORDER BY $order_by";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$users = $db->sql_fetchrowset($result);

	$sql = "SELECT t.team_id, t.team_name, tu.user_id, g.game_image, g.game_size
				FROM " . TEAMS . " t
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . TEAMS_USERS . " tu ON tu.team_id = t.team_id
			ORDER BY t.team_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$teams[$row['user_id']][] = array('team_id' => $row['team_id'], 'team_name' => $row['team_name'], 'game_image' => $row['game_image'], 'game_size' => $row['game_size']);
	}
	
	$sql = "SELECT g.group_id, g.group_name, g.group_type, gu.user_id
				FROM " . GROUPS . " g
					LEFT JOIN " . GROUPS_USERS . " gu ON gu.group_id = g.group_id
			WHERE g.group_single_user = 0
			ORDER BY g.group_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		if ( $row['group_type'] != GROUP_HIDDEN )
		{
			$groups[$row['user_id']][] = array('group_id' => $row['group_id'], 'group_name' => $row['group_name']);
		}
	}
	

$count = count($users);

for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, $count); $i++ )
{
	$user_id = $users[$i]['user_id'];

	gen_userinfo($users[$i], $username);
	
	$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

    $uteams = $ugroups = '';

    if ( isset($teams[$user_id]) )
	{
		foreach ( $teams[$user_id] as $row )
		{
			$game = display_gameicon($row['game_size'], $row['game_image']);
			$t_ary[$user_id][] =  $game . ' <a href="' . check_sid('teams.php?' . POST_TEAMS . '=' . $row['team_id']) . '">' . $row['team_name'] . '</a>';
		}

		$uteams = implode('<br />', $t_ary[$user_id]);
	}

	if ( isset($groups[$user_id]) )
	{
		foreach ( $groups[$user_id] as $row )
		{
			$g_ary[$user_id][] =  '<a href="' . check_sid('groups.php?' . POST_GROUPS . '=' . $row['group_id']) . '">' . $row['group_name'] . '</a>';
		}

		$ugroups = implode('<br />', $g_ary[$user_id]);
	}

	$template->assign_block_vars('memberrow', array(
		'ROW_COLOR'	=> '#' . $row_color,
		'ROW_CLASS'	=> $row_class,
		'USERNAME'	=> $username,

		'TEAMS'		=> $uteams,
		'GROUPS'	=> $ugroups,
	));
}

#$pagination = generate_pagination("memberlist.php?mode=$mode&amp;order=$sort_order&amp;letter=$by_letter", $total_members, $config['site_entry_per_page'], $start). '&nbsp;';

$template->assign_vars(array(
#	'PAGINATION' => $pagination,
#	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $config['site_entry_per_page'] ) + 1 ), ceil( $total_members / $config['site_entry_per_page'] )),

#	'L_GOTO_PAGE' => $lang['Goto_page']
));
*/
$template->pparse('body');

main_footer();

?>