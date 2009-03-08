<?php

//
//	Neuste Mitglieder
//
function display_teams()
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_teams'] )
	{
		$template->assign_block_vars('teams', array());
	}
	
	$sql = 'SELECT t.team_id, t.team_name, t.team_fight, g.game_size, g.game_image
				FROM ' . TEAMS_TABLE . ' t, ' . GAMES_TABLE . ' g
				WHERE t.team_game = g.game_id AND team_navi = 1
			ORDER BY t.team_order';
	$teams = _cached($sql, 'subnavi_teams');
	
	for ($i = 0; $i < count($teams); $i++)
	{
		$template->assign_block_vars('teams.teams_row', array(
			'TEAM_GAME'		=> display_gameicon($teams[$i]['game_size'], $teams[$i]['game_image']),
			'TEAM_NAME'		=> $teams[$i]['team_name'],
			'TO_TEAM'		=> append_sid("teams.php?mode=show&amp;" . POST_TEAMS_URL . "=".$teams[$i]['team_id']),
		));
	}
	
	$template->assign_vars(array(
		'L_TEAMS'		=> $lang['teams'],
		'L_TO_TEAM'		=> $lang['to_team'],
	));
	
	return;
}

//
//	Neuste Mitglieder
//
function display_newusers()
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_newusers'] )
	{
		$template->assign_block_vars('new_users', array());
	}
	
	$sql = 'SELECT user_id, username FROM ' . USERS_TABLE . ' WHERE user_id != -1 ORDER BY user_regdate ASC LIMIT 0, ' . $settings['subnavi_newusers_limit'];
	$users = _cached($sql, 'new_users', 0, 3600);
	
	for ($i = 0; $i < count($users); $i++)
	{
		$template->assign_block_vars('new_users.user_row', array(
			'USERNAME'		=> $users[$i]['username'],
			'U_USERNAME'	=> append_sid("profile.php?mode=views&amp;" . POST_USERS_URL . "=".$users[$i]['user_id']),
		));
	}
	return;
}

//
//	Teambilder
//
function display_gameicon($game_size, $game_image)
{
	global $root_path, $settings;
	
	$image	= '<img src="' . $root_path . $settings['game_path'] . '/' . $game_image . '" alt="' . $game_image . '" title="' . $game_image . '" width="' . $game_size . '" height="' . $game_size . '" >';

	return $image;
}

//
//	Minikalender
//
function display_minical()
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $settings['subnavi_minical'] )
	{
		$template->assign_block_vars('minical', array());
	}

	//$heute = date("d-m-Y"); // Format: z. B. 01-09-2002
	//$heute_a = date("j.n.Y"); // anderes Format: z. B. 1.9.2002
	//$heute_d = date("j");
	
	$tag			= date("d", time()); // Heutiger Tag: z. B. "1"
	$tag_der_woche	= date("w"); // Welcher Tag in der Woch: z. B. "0 / Sonntag"
	$tage_im_monat	= date("t"); // Anzahl der Tage im Monat: z. B. "31"
	$monat			= date("m", time());
	$jahr			= date("Y", time());
	$erster			= date("w", mktime(0, 0, 0, $monat, 1, $jahr)); // Der erste Tag im Monat: z. B. "5 / Freitag"
	$arr_woche_kurz	= array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
	
	$monate = array(
		'01'	=> 'Januar',
		'02'	=> 'Feber',
		'03'	=> 'M&auml;rz',
		'04'	=> 'April',
		'05'	=> 'Mai',
		'06'	=> 'Juni',
		'07'	=> 'Juli',
		'08'	=> 'August',
		'09'	=> 'September',
		'10'	=> 'Oktober',
		'11'	=> 'November',
		'12'	=> 'Dezember'
	);
	
	$month = $monate[$monat];
	
	// wochenstart
	// 0=Sonntag; 1=Montag; 2=Dienstag; 3=Mittwoch; 4=Donnerstag; 5=Freitag; 6=Samstag
	$ws = 1;
	// "woche beginnt mit" - array verschiebung
	$edmk = $arr_woche_kurz[$erster];
	$wbmk = $arr_woche_kurz;
	for ( $i=0; $i<$ws; $i++ )
	{
		$wechsel = array_shift($wbmk);
		$wbmk[] = $wechsel;
	}
	$wbmk_wechsel = array_flip($wbmk);
	
	$days = '';
	for ( $i=0; $i<7; $i++ )
	{
		$days .= '<td align="center">' . $wbmk[$i] . '</td>';
	}
	
	// berechnung der monatstabelle
	// zuerst die unbenutzten tage
	$day = '';
	for ( $i=0; $i<$wbmk_wechsel[$edmk]; $i++)
	{
		$day .= '<td></td>';
	}
	
	// ab hier benutzte tage
	$wcs = $wbmk_wechsel[$edmk];
	for ( $i=1; $i<$tage_im_monat+1; $i++ )
	{
		if ($i < 10)
		{
			$i = '0'.$i;
		}
		
		$sql = 'SELECT * FROM ' . MATCH_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
		$day_rows_w[$i] = _cached($sql, $monat.'-'. $i . '_w_kalender');
		
		
		$sql = 'SELECT * FROM ' . TRAINING_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_start), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'";
		$day_rows_t[$i] = _cached($sql, $monat.'-'. $i . '_t_kalender');
		
		
		if ( is_array($day_rows_w[$i]) )
		{
			$day .= '<td align="center"><a class="f_popup" href="#"><b>' . $i . '<span>match '.count($day_rows_w[$i]).' </span></b></a></td>';
		}
		else if ( is_array($day_rows_t[$i]) )
		{
			for ( $k=0; $k< count($day_rows_t[$i]); $k++ )
			{
				$details[] = $k+1 . ': ' . $day_rows_t[$i][$k]['training_vs'];
			}
			
			$details = implode('<br />', $details);
			
			$day .= '<td align="center"><a class="f_popup" href="#"><b>' . $i . '<span>Training:<br />'.$details.' </span></b></a></td>';
		}
		else if ( $i == $tag )
		{
			$day .= '<td align="center" style="color:#F00">' . $i . '</td>';
		}
		else
		{
			$day .= '<td align="center">' . $i . '</td>';
		}
		/*
		if ($i == $tag)
		{
			$day .= '<td align="center" style="color:#F00">' . $i . '</td>';
		}
		else
		{
			$day .= '<td align="center">' . $i . '</td>';
		}
		*/
		if ( $wcs < 7 )
		{
			$wcs++;
		}
		
		if ( $wcs == 7 )
		{
			$day .= '</tr><tr>';
			$wcs = 0;
		}
	}
	
	for ( $wcs; $wcs<7; $wcs++ )
	{
		$day .= '<td></td>';
	}
	
	$sql = 'SELECT * FROM ' . MATCH_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(match_date), '%m') = '".$monat."'";
	$day_rows_w[] = _cached($sql, $monat.'_w_kalender');
	
	for ( $k=0; $k< count($day_rows_w[$i]); $k++ )
	{
		$details = $k+1 . '. ' . substr($day_rows_w[$i][$k]['match_rival'], 0, 8) . ' ...';
		$date = create_date('d H:i', $day_rows_w[$i][$k]['match_date'], $userdata['user_timezone']);
		
		$template->assign_block_vars('minical.match', array(
			'NAME'	=> $details,
			'DATE'	=> $date,
		));
	}
	
	
	$sql = 'SELECT * FROM ' . TRAINING_TABLE . " WHERE DATE_FORMAT(FROM_UNIXTIME(training_start), '%m') = '".$monat."'";
	$day_rows_t[] = _cached($sql, $monat.'_t_kalender');
	
	for ( $k=0; $k< count($day_rows_t[$i]); $k++ )
	{
		$details = $k+1 . '. ' . substr($day_rows_t[$i][$k]['training_vs'], 0, 8) . ' ...';
		$date = create_date('d H:i', $day_rows_t[$i][$k]['training_start'], $userdata['user_timezone']);
		
		$template->assign_block_vars('minical.training', array(
			'NAME'	=> $details,
			'DATE'	=> $date,
		));
	}
	
	$template->assign_vars(array(
		'MONTH'		=> $month,
		'DAYS'		=> $days,
		'DAY'		=> $day,
	));
}

//
//	Navi
//
function display_navi()
{
	global $db, $root_path, $settings, $template, $userdata, $lang;
	
	if ( $userdata['session_logged_in'] )
	{
		$template->assign_block_vars('navi_user', array());
		
		$sql = 'SELECT * FROM ' . NAVI_TABLE . ' WHERE navi_show = 1';
		
		$width = 371;
	}
	else
	{
		$sql = 'SELECT * FROM ' . NAVI_TABLE . ' WHERE navi_show = 1 AND navi_intern != 1 AND navi_type != ' . NAVI_USER;
		
		$width = 492;
	}
	$result = $db->sql_query($sql);
	
	$template->assign_block_vars('navi_main', array());
	$template->assign_block_vars('navi_clan', array());
	$template->assign_block_vars('navi_community', array());
	$template->assign_block_vars('navi_misc', array());
	
	while ( $navi = $db->sql_fetchrow($result) )
	{
		switch ($navi['navi_type'])
		{
			case NAVI_MAIN:
				$row_type = 'navi_main.navi_main_row';
			break;
			case NAVI_CLAN:
				$row_type = 'navi_clan.navi_clan_row';
			break;
			case NAVI_COM:
				$row_type = 'navi_community.navi_community_row';
			break;
			case NAVI_MISC:
				$row_type = 'navi_misc.navi_misc_row';
			break;
			case NAVI_USER:
				$row_type = 'navi_user.navi_user_row';
			break;
		}
		
		switch ($navi['navi_target'])
		{
			case 0:
				$navi_target = '_self';
			break;
			case 1:
				$navi_target = '_blank';
			break;
		}
		
		$navi_lang = ($navi['navi_lang']) ? $lang[$navi['navi_name']] : $navi['navi_name'];

		$template->assign_block_vars($row_type, array(
			'NAVI_NAME'		=> $navi_lang,
			'NAVI_URL'		=> $navi['navi_url'],
			'NAVI_TARGET'	=> $navi_target,
		));
	}

	$template->assign_vars(array(
		'WIDTH'		=> $width,
	));
	
}

//
//	Last Matches
//
function display_last_matches()
{
	global $db, $root_path, $oCache, $settings, $template, $userdata, $lang;
	
	if ($userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		$sql = 'SELECT m.*, md.*, t.team_name, g.game_image, g.game_size, tr.training_id
					FROM ' . MATCH_TABLE . ' m
						LEFT JOIN ' . MATCH_DETAILS_TABLE . ' md ON m.match_id = md.match_id
						LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON m.match_id = tr.match_id
					WHERE m.match_date < ' . time() . '
				ORDER BY m.match_date ASC LIMIT 0,' . $settings['last_matches'];
		$match_last = _cached($sql, 'head_match_member');
	}
	else
	{
		$sql = 'SELECT m.*, md.*, t.team_name, g.game_image, g.game_size, tr.training_id
					FROM ' . MATCH_TABLE . ' m
						LEFT JOIN ' . MATCH_DETAILS_TABLE . ' md ON m.match_id = md.match_id
						LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON m.match_id = tr.match_id
					WHERE m.match_date < ' . time() . ' AND m.match_public = 1
				ORDER BY m.match_date ASC LIMIT 0,' . $settings['last_matches'];
		$match_last = _cached($sql, 'head_match_guest');
	}
	
	if (!$match_last)
	{
		$template->assign_block_vars('no_entry_last', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = 0; $i < count($match_last); $i++)
		{
			$class = ($i % 2) ? 'row1r' : 'row2r';
			
			$clan	= $match_last[$i]['details_mapa_clan'] + $match_last[$i]['details_mapb_clan'];
			$rival	= $match_last[$i]['details_mapa_rival'] + $match_last[$i]['details_mapb_rival'];
			
			if		($clan > $rival) $class_result = 'win';
			else if	($clan < $rival) $class_result = 'lose';
			else if	($clan = $rival) $class_result = 'draw';
			else	$class_result = '';
			
			$match_rival = substr($match_last[$i]['match_rival'], 0, 15) . ' ...';
			
			$game_size	= $match_last[$i]['game_size'];
			$game_image	= '<img src="' . $root_path . $settings['game_path'] . '/' . $match_last[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
			
			$match_name	= ($match_last[$i]['match_public']) ? 'vs. ' . $match_rival : 'vs. <span style="font-style:italic;">' . $match_rival . '</span>';
			
			$template->assign_block_vars('match_row', array(
				'CLASS' 		=> $class,
				'CLASS_RESULT'	=> $class_result,
				'MATCH_GAME'	=> $game_image,
				'MATCH_NAME'	=> $match_name,
				'MATCH_RESULT'	=> $clan . ':' . $rival,
				'U_DETAILS'		=> append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=".$match_last[$i]['match_id']),
			));
		}
	}
	
	$template->assign_vars(array(
		'L_DETAILS'		=> $lang['match_details'],
		'L_LAST_MATCH'	=> $lang['last_matches'],
	));
	
	return;
}

?>