<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_MATCH);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', 0) ) ? request('start', 0) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

<<<<<<< .mine
$log	= SECTION_MATCH;
$url	= POST_MATCH;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, 0);	
$mode	= request('mode', 1);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_match.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'error_body.tpl',
));

if ( $mode == '' )
=======
$log	= SECTION_MATCH;
$url	= POST_MATCH;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, 0);	
$mode	= request('mode', 1);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_match.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'error_body.tpl',
));

$sql = "SELECT m.*, m.match_rival_lineup AS lineup_rival, t.team_id, t.team_name, g.game_image, g.game_size, ml.match_id AS lineup_clan
			FROM " . MATCH . " m
				LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
				LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				LEFT JOIN " . MATCH_LINEUP . " ml ON m.match_id = ml.match_id
		ORDER BY m.match_date DESC";
#if ( !($result = $db->sql_query($sql)) )
#{
#	message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#}
#$match = $db->sql_fetchrowset($result);
$tmp = _cached($sql, 'data_match');

if ( $data && $tmp )
>>>>>>> .r85
{
<<<<<<< .mine
	$template->assign_block_vars('_list', array());
=======
	$template->assign_block_vars('_view', array());
>>>>>>> .r85
	
<<<<<<< .mine
	$sql = "SELECT m.*, m.match_rival_lineup AS lineup_rival, t.team_id, t.team_name, g.game_image, g.game_size, ml.match_id AS lineup_clan
				FROM " . MATCH . " m
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . MATCH_LINEUP . " ml ON m.match_id = ml.match_id
			ORDER BY m.match_date DESC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$matches = $db->sql_fetchrowset($result);
	$matches = _cached($sql, 'data_match');
	
	$page_title = $lang['match'];
	
	main_header();

	if ( !$matches )
=======
	foreach ( $tmp as $key => $row )
>>>>>>> .r85
	{
<<<<<<< .mine
		$template->assign_block_vars('_list._entry_empty_new', array());
		$template->assign_block_vars('_list._entry_empty_old', array());
	}
	else
	{
		$new = $old = array();
			
		foreach ( $matches as $match => $row )
=======
		if ( $row['match_id'] == $data )
>>>>>>> .r85
		{
<<<<<<< .mine
			if ( $userdata['user_level'] >= TRIAL )
			{
				if ( $row['match_date'] > time() )
				{
					$new[]		= $row;
					$new_ids[]	= $row['match_id'];
				}
				else if ( $row['match_date'] < time() )
				{
					$old[]		= $row;
					$old_ids[]	= $row['match_id'];
				}
			}
			else if ( $row['match_public'] == '1' )
			{
				if ( $row['match_date'] > time() )
				{
					$new[]		= $row;
					$new_ids[]	= $row['match_id'];
				}
				else if ( $row['match_date'] < time() )
				{
					$old[]		= $row;
					$old_ids[]	= $row['match_id'];
				}
			}
=======
			$info = $row;
>>>>>>> .r85
		}
<<<<<<< .mine
		
		$count = isset($old) ? count($old) : 0;
		
		if ( !$new )
		{
			$template->assign_block_vars('_list._entry_empty_new', array());
		}
		else
		{
			$sql = "SELECT * FROM " . MATCH_USERS . " WHERE match_id IN (" . implode(', ', $new_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_ary[$row['match_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = 0; $i < count($new); $i++ )
			{
				$match_id = $new[$i]['match_id'];
				
				$name	= ( $new[$i]['match_public'] ) ? sprintf($lang['sprintf_match_name'], $new[$i]['match_rival_name']) : sprintf($lang['sprintf_match_intern'], $new[$i]['match_rival_name']);
				$css	= 'none';
				$pos	= $lang['join_none'];
				
				if ( isset($in_ary[$match_id][$userdata['user_id']]) )
				{
					switch ( $in_ary[$match_id][$userdata['user_id']] )
					{
						case STATUS_NO:			$css = 'no';		$pos = $lang['no'];			break;
						case STATUS_YES:		$css = 'yes';		$pos = $lang['yes'];		break;
						case STATUS_REPLACE:	$css = 'replace';	$pos = $lang['replace'];	break;
					}
				}
				
				$template->assign_block_vars('_list._new_row', array(
					'CLASS'	=> ( $i % 2 ) ? 'row1r' : 'row2r',
					
					'GAME'	=> display_gameicon($new[$i]['game_size'], $new[$i]['game_image']),
					'NAME'	=> "<a href=\"" . check_sid("$file?mode=view&amp;$url=$match_id") . "\" >$name</a>",
					'DATE'	=> create_date($userdata['user_dateformat'], $new[$i]['match_date'], $userdata['user_timezone']),
					
					'CSS'		=> $css,
					'STATUS'	=> $pos,
				));
			}
		}
		
		if ( $old )
		{
			$template->assign_block_vars('_list._entry_empty_old', array());
		}
		else
		{
			$sql = "SELECT * FROM " . MATCH_USERS . " WHERE match_id IN (" . implode(', ', $old_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_ary[$row['match_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, $count); $i++ )
			{
				$match_id = $old[$i]['match_id'];
				
				$name	= ( $old[$i]['match_public'] ) ? sprintf($lang['sprintf_match_name'], $old[$i]['match_rival_name']) : sprintf($lang['sprintf_match_intern'], $old[$i]['match_rival_name']);
				$css	= 'none';
				$pos	= $lang['join_none'];
				
				if ( isset($in_ary[$match_id][$userdata['user_id']]) )
				{
					switch ( $in_ary[$match_id][$userdata['user_id']] )
					{
						case STATUS_NO:			$css = 'no';		$pos = $lang['no'];			break;
						case STATUS_YES:		$css = 'yes';		$pos = $lang['yes'];		break;
						case STATUS_REPLACE:	$css = 'replace';	$pos = $lang['replace'];	break;
					}
				}
				
				$template->assign_block_vars('_list._old_row', array(
					'CLASS'	=> ( $i % 2 ) ? 'row1r' : 'row2r',
					
					'GAME'	=> display_gameicon($old[$i]['game_size'], $old[$i]['game_image']),
					'NAME'	=> "<a href=\"" . check_sid("$file?mode=view&amp;$url=$match_id") . "\" >$name</a>",
					'DATE'	=> create_date($userdata['user_dateformat'], $old[$i]['match_date'], $userdata['user_timezone']),
					
					'CSS'		=> $css,
					'STATUS'	=> $pos,
				));
			}
		}
=======
>>>>>>> .r85
	}
	
<<<<<<< .mine
	$current_page = ( !$count ) ? 1 : ceil( $count / $settings['site_entry_per_page'] );
	
	$template->assign_vars(array(
		'L_DETAILS'		=> $lang['match_details'],
		'L_TEAMS'		=> $lang['teams'],
		'L_UPCOMING'	=> $lang['match_upcoming'],
		'L_EXPIRED'		=> $lang['match_expired'],
		'L_GOTO_PAGE'	=> $lang['Goto_page'],
		
		'PAGE_PAGING'	=> generate_pagination($file, $count, $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
	));
}
else if ( $mode == 'view' && $data )
{
	$template->assign_block_vars('_view', array());
=======
	if ( !$info )
	{
		message(GENERAL_ERROR, $lang['msg_match_fail']);
	}
	
	$page_title = sprintf($lang['news_head_info'], $info['match_rival_name']);
>>>>>>> .r85
	
<<<<<<< .mine
	$sql = "SELECT m.*, m.match_rival_lineup AS lineup_rival, t.team_id, t.team_name, g.game_image, g.game_size, ml.match_id AS lineup_clan
				FROM " . MATCH . " m
					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . MATCH_LINEUP . " ml ON m.match_id = ml.match_id
			ORDER BY m.match_date DESC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$match = $db->sql_fetchrowset($result);
	$match = _cached($sql, 'data_match');
=======
	main_header();
>>>>>>> .r85
	
<<<<<<< .mine
	foreach ( $match as $key => $row )
=======
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_match'] )
>>>>>>> .r85
	{
<<<<<<< .mine
		if ( $row['match_id'] == $data )
		{
			$view = $row;
		}
	}
	
	if ( !$view )
	{
		message(GENERAL_ERROR, $lang['msg_match_fail']);
	}
	
	$page_title = sprintf($lang['news_head_info'], $view['match_rival_name']);
=======
		$template->assign_block_vars('_view._update', array(
			'UPDATE'		=> "<a href=\"" . check_sid("admin/admin_match.php?mode=_update&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['match_update'] . "</a>",
			'UPDATE_DETAIL'	=> "<a href=\"" . check_sid("admin/admin_match.php?mode=_detail&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['match_detail'] . "</a>",
		));
	}
>>>>>>> .r85
	
<<<<<<< .mine
	main_header();
=======
#	if ( !$userdata['session_logged_in'] )
#	{
#		session_start();
#	}
>>>>>>> .r85
	
<<<<<<< .mine
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_match'] )
=======
	/* Lineup Clan und Lineup Gegner - es fehlt noch der clantag vom clan selber */
	if ( $info['lineup_clan'] || $info['lineup_rival'] )
>>>>>>> .r85
	{
<<<<<<< .mine
		$template->assign_block_vars('_view._update', array(
			'UPDATE'		=> "<a href=\"" . check_sid("admin/admin_match.php?mode=_update&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['match_update'] . "</a>",
			'UPDATE_DETAIL'	=> "<a href=\"" . check_sid("admin/admin_match.php?mode=_detail&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['match_detail'] . "</a>",
		));
	}
	
#	if ( !$userdata['session_logged_in'] )
#	{
#		session_start();
#	}
	
	/* Lineup Clan und Lineup Gegner - es fehlt noch der clantag vom clan selber */
	if ( $view['lineup_clan'] || $view['lineup_rival'] )
	{
		$template->assign_block_vars('_view._lineup', array());

		$sql = "SELECT ml.user_id, ml.status, u.user_name, u.user_color
					FROM " . MATCH_LINEUP . " ml, " . USERS . " u
					WHERE match_id = $data AND u.user_id = ml.user_id
				ORDER BY ml.status";
=======
		$template->assign_block_vars('_view._lineup', array());

		$sql = "SELECT ml.user_id, ml.status, u.user_name, u.user_color
					FROM " . MATCH_LINEUP . " ml, " . USERS . " u
					WHERE match_id = $data AND u.user_id = ml.user_id
				ORDER BY ml.status";
>>>>>>> .r85
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$lineup = $db->sql_fetchrowset($result);
		
		for ( $i = 0; $i < count($lineup); $i++ )
		{
			$player_id		= $lineup[$i]['user_id'];
			$player_name	= $lineup[$i]['user_name'];
			$player_color	= $lineup[$i]['user_color'];

			if ( $lineup[$i]['status'] != 1 )
			{
				$player[] = "<a href=\"profile.php?user=$player_id\"><span style=\"color:$player_color\">$player_name</span></a>";
			}
			else
			{
				$replace[] = "<a href=\"profile.php?user=$player_id\"><span style=\"color:$player_color\">$player_name</span></a>";
			}
        }

        if ( $player )
        {
			$template->assign_block_vars('_view._lineup._clan', array());

			$player = implode(', ', $player);
			$replace = $replace ? implode(', ', $replace) : '';
			
			$clan = ( $player && $replace ) ? sprintf($lang['lineup_players'], $player, $replace) : sprintf($lang['lineup_player'], $player);
		}
		
<<<<<<< .mine
		/* Filter beim eintragen noch bearbeiten damit alle gleich eingetragen sind mit syntax ', ' */
		if ( $view['lineup_rival'] )
=======
		/* Filter beim eintragen noch bearbeiten damit alle gleich eingetragen sind mit syntax ', ' */
		if ( $info['lineup_rival'] )
>>>>>>> .r85
		{
			$template->assign_block_vars('_view._lineup._rival', array());
			
<<<<<<< .mine
			$rivals = explode(', ', $view['lineup_rival']);
			
			foreach ( $rivals as $key => $row )
=======
			$rivals = explode(', ', $info['lineup_rival']);
			
			foreach ( $rivals as $key => $row )
>>>>>>> .r85
			{
<<<<<<< .mine
				$ary[] = $view['match_rival_tag'] . " $row";
=======
				$ary[] = $info['match_rival_tag'] . " $row";
>>>>>>> .r85
			}

			$rival = sprintf($lang['lineup_player'], implode(', ', $ary));
		}
	}
	
	/* Teilnahme - nur sichtbar für eingeloggte und mit dem Status ab Trail sichtbar */
	if ( $userdata['session_logged_in'] && $userdata['user_level'] >= TRIAL )
	{
		$template->assign_block_vars('_view._status', array());

		$sql = "SELECT mu.*, u.user_name, u.user_color
					FROM " . MATCH_USERS . " mu, " . USERS . " u
				WHERE mu.user_id = u.user_id AND mu.match_id = $data";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$status = $db->sql_fetchrowset($result);
		
		if ( $status )
		{
			$template->assign_block_vars('_view._status._entry', array());

			for ( $i = 0; $i < count($status); $i++ )
			{
				$player_id		= $status[$i]['user_id'];
				$player_name	= $status[$i]['user_name'];
				$player_color	= $status[$i]['user_color'];
				
				switch ( $status[$i]['user_status'] )
				{
					case STATUS_NO:			$lng = $lang['status_no'];		$css = 'no';		break;
					case STATUS_YES:		$lng = $lang['status_yes'];		$css = 'yes';		break;
					case STATUS_REPLACE:	$lng = $lang['status_replace'];	$css = 'replace';	break;
				}
				
				$player = "<a href=\"profile.php?user=$player_id\"><span style=\"color:$player_color\">$player_name</span></a>";
				
				$status_update = $status[$i]['status_update'];
				
				$time_update = create_shortdate($userdata['user_dateformat'], $status[$i]['status_update'], $userdata['user_timezone']);
				$time_create = create_shortdate($userdata['user_dateformat'], $status[$i]['status_create'], $userdata['user_timezone']);
				
				$template->assign_block_vars('_view._status._entry._status_row', array(
					'USER'		=> $player,
					'CLASS'		=> $css,
					'STATUS'	=> $lng,
					'DATE'		=> $status_update ? $lang['change_on'] . $time_update : $time_create,
				));
	        }
	    }
		
<<<<<<< .mine
		if ( $view['match_date'] > $time )
=======
		if ( $info['match_date'] > $time )
>>>>>>> .r85
		{
<<<<<<< .mine
			$sql = "SELECT * FROM " . TEAMS_USERS . " WHERE user_id = $user AND team_id = " . $view['team_id'];
=======
			$sql = "SELECT * FROM " . TEAMS_USERS . " WHERE user_id = $user AND team_id = " . $info['team_id'];
>>>>>>> .r85
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$in_team = $db->sql_fetchrow($result);
			
			if ( $in_team )
			{
				$template->assign_block_vars('_view._status._switch', array());

				$sql = "SELECT user_status FROM " . MATCH_USERS . " WHERE user_id = $user AND match_id = $data";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				
				$status = ( !$row['user_status'] ) ? -1 : $row['user_status'];
				
				$fielde .= "<input type=\"hidden\" name=\"smode\" value=\"change\" />";
				$fielde .= "<input type=\"hidden\" name=\"status\" value=\"$status\" />";
				$fielde .= "<input type=\"hidden\" name=\"mode\" value=\"detail\" />";
				$fielde .= "<input type=\"hidden\" name=\"$url\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'S_NO'		=> ( $row['user_status'] == STATUS_NO ) ? 'checked="checked"' : '',
					'S_YES'		=> ( $row['user_status'] == STATUS_YES ) ? 'checked="checked"' : '',
					'S_REPLACE'	=> ( $row['user_status'] == STATUS_REPLACE ) ? 'checked="checked"' : '',
				));
			}
		}
	}
	
	/* Kommentar gelesen ungelesen */
	if ( $userdata['session_logged_in'] )
	{
		$sql = "SELECT read_time FROM " . COMMENT_READ . " WHERE user_id = $user AND type_id = $data AND type = " . READ_MATCH;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$unread = $db->sql_fetchrow($result);
		
		if ( !$unread )
		{
			sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $data, 'user_id' => $user, 'read_time' => $time));
		#	$sql = 'INSERT INTO ' . COMMENT_READ . ' (match_id, user_id, read_time) VALUES (' . $data . ', ' . $userdata['user_id'] . ', ' . time() . ')';
		#	if ( !($result = $db->sql_query($sql)) )
		#	{
		#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		#	}
			$unreads = true;
		}
		else
		{
			sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_MATCH, $data, $user));
		#	$sql = "UPDATE " . COMMENT_READ . " SET read_time = " . time() . " WHERE match_id = $data AND user_id = " . $userdata['user_id'];
		#	if ( !($result = $db->sql_query($sql)) )
		#	{
		#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		#	}
			$unreads = false;
		}
	}
	
<<<<<<< .mine
	/* Kommentarfunktion - Nur wenn die Generelle Funktion aktiviert ist und für das Match selber */
	if ( $settings['comments_matches'] && $view['match_comments'] )
=======
	/* Kommentarfunktion - Nur wenn die Generelle Funktion aktiviert ist und für das Match selber */
	if ( $settings['comments_matches'] && $info['match_comments'] )
>>>>>>> .r85
	{
		$template->assign_block_vars('_view._comment', array());

		$sql = "SELECT c.*, u.user_id, u.user_name, u.user_color, u.user_email
					FROM " . COMMENT . " c
						LEFT JOIN " . USERS . " u ON c.poster_id = u.user_id
					WHERE c.type_id = $data AND c.type = " . READ_MATCH . "
				ORDER BY c.time_create DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$comments = $db->sql_fetchrowset($result);
#		$comments = _cached($sql, 'detail_match_comments_' . $data);
		
		if ( !$comments )
		{
			$template->assign_block_vars('_view._comment._entry_empty', array());
			
			$last_entry = array('poster_ip' => '', 'time_create' => '');
		}
		else
		{
			for  ( $i = $start; $i < min($settings['site_comment_per_page'] + $start, count($comments)); $i++ )
			{
				$css	= ( $i % 2 ) ? 'row1' : 'row2';
				$icon	= ( $userdata['session_logged_in'] ) ? ( $unreads || $unread['read_time'] < $comments[$i]['time_create'] ) ? $images['icon_minipost_new'] : $images['icon_minipost'] : $images['icon_minipost'];
			
				$poster_name = $comments[$i]['poster_nick'] ? $comments[$i]['poster_nick'] : '<font color="' . $comments[$i]['user_color'] . '">' . $comments[$i]['user_name'] . '</font>';
				$poster_link = $comments[$i]['poster_nick'] ? $userdata['session_logged_in'] ? 'mailto:' . $comments[$i]['poster_email'] : $comments[$i]['poster_nick'] : 'profile.php?mode=view&amp;' . POST_USER . '=' . $comments[$i]['poster_id'];
				
				$s_option = '';
				
			#	$poster_msg	= html_entity_decode($comments[$i]['poster_text'], ENT_QUOTES);

			#	if ( $comments[$i]['poster_nick'] )
			#	{
			#		$comment_user = ( $userdata['session_logged_in'] ) ? '<a href="' . check_sid('mailto:' . $comments[$i]['poster_email']) . '">' . $comments[$i]['poster_nick'] . '</a>' : $comments[$i]['poster_nick'];
			#	}
			#	else
			#	{
			#		$comment_user = '<a href="' . check_sid('profile.php?mode=view&amp;' . POST_USER . '=' . $comments[$i]['poster_id']) . '"><font color="' . $comments[$i]['user_color'] . '">' . $comments[$i]['user_name'] . '</font></a>';
			#	}
				
				$template->assign_block_vars('_view._comment._comment_row', array(
					'CSS'	=> $css,
					'ICON'	=> $icon,
					
					'DATE'		=> create_shortdate($userdata['user_dateformat'], $comments[$i]['time_create'], $userdata['user_timezone']),
					'POSTER'	=> "<a href=\"$poster_link\">$poster_name</a>",
					'MESSAGE'	=> $comments[$i]['poster_text'],

					'OPTIONS'	=> $s_option,
				));
			}
		
			$current_page = ( !count($comments) ) ? 1 : ceil( count($comments) / $settings['site_comment_per_page'] );
			
			$template->assign_vars(array(
				'L_GOTO_PAGE'	=> $lang['Goto_page'],
				'PAGINATION'	=> generate_pagination('match.php?mode=detail&amp;' . POST_MATCH . '=' . $data, count($comments), $settings['site_comment_per_page'], $start),
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['site_comment_per_page'] ) + 1 ), $current_page ),
			));
			
			/* Letzter Kommentareintrag */
			sort($comments);
			$last_entry = array_pop($comments);		
		}
		
		/* Kommentare */
		if ( $settings['comments_matches_guest'] && !$userdata['session_logged_in'] )
		{
			$template->assign_block_vars('_view._comment._guest', array());
		}
		
		/* Erlaubt nach Absenden kein Doppelbeitrag erst nach 20 Sekunden */
		/*
		if ( isset($HTTP_POST_VARS['submit']) && ( $last_entry['poster_ip'] != $userdata['session_ip'] || ($last_entry['time_create'] + $settings['spam_comment_match']) < time() ) )
		{
			//	Laden der Funktion zum eintragen von Kommentaren
			include($root_path . 'includes/functions_post.php');
			
			//	Bei Fehlern wird der Text erneut in die Felder eingetragen
			$poster_nick	= (!$userdata['session_logged_in']) ? trim(stripslashes($HTTP_POST_VARS['poster_nick'])) : '';
			$poster_mail	= (!$userdata['session_logged_in']) ? trim(stripslashes($HTTP_POST_VARS['poster_mail'])) : '';
			$poster_hp		= (!$userdata['session_logged_in']) ? trim($HTTP_POST_VARS['poster_hp']) : '';
			$poster_msg		= (!$userdata['session_logged_in']) ? trim($HTTP_POST_VARS['poster_msg']) : '';

			$template->assign_vars(array(
				'POSTER_NICK'	=> $poster_nick,
				'POSTER_MAIL'	=> $poster_mail,
				'POSTER_HP'		=> $poster_hp,
				'POSTER_MSG'	=> $poster_msg,
			));
			
			if ( !$userdata['session_logged_in'] )
			{
				$captcha = $HTTP_POST_VARS['captcha'];
				
				if ( $captcha != $HTTP_SESSION_VARS['captcha'] )
				{
					$error = true;
					$error_msg = 'captcha';
				}
					
				if ( !$poster_nick )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . 'user_nick';
				}
				
				if ( !$poster_mail )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . 'poster_mail';
				}
				
				unset($_SESSION['captcha']);
			}
				
			if ( !$poster_msg )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . 'comment';
			}
	
			if ( $error )
			{
				$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
				$template->assign_var_from_handle('ERROR_BOX', 'error');
			}
	
			if ( !$error )
			{
				//	Test: hier werden/sollen Kommentare als gelesen markiert werden
				sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_MATCH, $data, $user));
				
			#	$sql = 'SELECT * FROM ' . COMMENT_READ . ' WHERE match_id = ' . $data . ' AND user_id = ' . $userdata['user_id'];
			#	if ( !($result = $db->sql_query($sql)) )
			#	{
			#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			#	}
				
			#	if ( $db->sql_numrows($result) )
			#	{
			#		$sql = 'UPDATE ' . COMMENT_READ . '
			#					SET read_time = ' . time() . '
			#				WHERE match_id = ' . $data . ' AND user_id = ' . $userdata['user_id'];					
			#		if ( !($result = $db->sql_query($sql)) )
			#		{
			#			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			#		}
			#	}
			#	else
			#	{				
			#		$sql = 'INSERT INTO ' . COMMENT_READ . ' (match_id, user_id, read_time)
			#			VALUES (' . $data . ', ' . $userdata['user_id'] . ', ' . time() . ')';
			#		if ( !($result = $db->sql_query($sql)) )
			#		{
			#			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			#		}
			#	}
			
				$oCache->deleteCache('detail_match_comments_' . $data);
				
				_comment_message('add', 'match', $data, $userdata['user_id'], $user_ip, $HTTP_POST_VARS['comment'], $poster_nick, $poster_mail, '');
				
				$message = $lang['add_comment'] . sprintf($lang['click_return_match'],  '<a href="' . check_sid('match.php?mode=details&amp;' . POST_MATCH . '=' . $data) . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
			}
		}
		*/
	}
	
	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"detail\" />";
	$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data\" />";
	$fields .= "<input type=\"hidden\" name=\"smode\" value=\"msg\" />";
	
	$template->assign_vars(array(
		'L_MATCH_INFO'	=> $lang['match_info'],
		'L_SUBMIT'		=> $lang['Submit'],
		
		'L_USERNAME'		=> $lang['user_name'],
		'L_STATUS'			=> $lang['status'],
		'L_STATUS_YES'		=> $lang['status_yes'],
		'L_STATUS_NO'		=> $lang['status_no'],
		'L_STATUS_REPLACE'	=> $lang['status_replace'],
		'L_SET_STATUS'		=> $lang['set_status'],
		
		'CLAN'	=> $clan,
		'RIVAL'	=> $rival,
		
<<<<<<< .mine
		'RIVAL_NAME'		=> $view['match_rival_name'],
		'RIVAL_TAG'			=> $view['match_rival_tag'],
	#	'U_MATCH_RIVAL'		=> $view['match_rival_url'],
	#	'MATCH_RIVAL'		=> $view['match_rival_url'],
=======
		'RIVAL_NAME'		=> $info['match_rival_name'],
		'RIVAL_TAG'			=> $info['match_rival_tag'],
	#	'U_MATCH_RIVAL'		=> $info['match_rival_url'],
	#	'MATCH_RIVAL'		=> $info['match_rival_url'],
>>>>>>> .r85

<<<<<<< .mine
	#	'MATCH_CATEGORIE'		=> $match_cat,
	#	'MATCH_TYPE'			=> $match_type,
	#	'MATCH_LEAGUE_INFO'		=> $match_league,
	#	'SERVER'				=> ($view['server']) ? '<a href="hlsw://' . $view['server'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
	#	'SERVER_PW'				=> ( $userdata['user_level'] >= TRIAL ) ? $view['server_pw'] : '',
	#	'HLTV'					=> ($view['server']) ? '<a href="hlsw://' . $view['server_hltv'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
	#	'HLTV_PW'				=> ( $userdata['user_level'] >= TRIAL ) ? $view['server_hltv_pw'] : '',
=======
	#	'MATCH_CATEGORIE'		=> $match_cat,
	#	'MATCH_TYPE'			=> $match_type,
	#	'MATCH_LEAGUE_INFO'		=> $match_league,
	#	'SERVER'				=> ($info['server']) ? '<a href="hlsw://' . $info['server'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
	#	'SERVER_PW'				=> ( $userdata['user_level'] >= TRIAL ) ? $info['server_pw'] : '',
	#	'HLTV'					=> ($info['server']) ? '<a href="hlsw://' . $info['server_hltv'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
	#	'HLTV_PW'				=> ( $userdata['user_level'] >= TRIAL ) ? $info['server_hltv_pw'] : '',
>>>>>>> .r85
		
<<<<<<< .mine
	#	'DETAILS_COMMENT'		=> $view['details_comment'],
=======
	#	'DETAILS_COMMENT'		=> $info['details_comment'],
>>>>>>> .r85
		
		'MATCH_MAIN'			=> '<a href="' . check_sid('match.php') . '">Übersicht</a>',
	
<<<<<<< .mine
		'S_FIELDE'		=> $fielde,
		'S_FIELDS'		=> $fields,
		'S_ACTION'		=> check_sid('match.php?mode=detail&amp;' . POST_MATCH . '=' . $data),
	));
=======
		'S_FIELDE'		=> $fielde,
		'S_FIELDS'		=> $fields,
		'S_ACTION'		=> check_sid('match.php?mode=detail&amp;' . POST_MATCH . '=' . $data),
	));
	
	if ( request('submit', 1) )
	{
		if ( $smode == 'change' )
		{
			$status		= request('status', 0);
			$ustatus	= request('user_status', 1);
			
			if ( $status == -1 )
			{
				$sql = sql(MATCH_USERS, 'create', array('match_id' => $data, 'user_id' => $user, 'user_status' => $ustatus, 'status_create' => $time));
				$msg = $lang['msg_change_status_create'];
				
			#	$sql = 'INSERT INTO ' . MATCH_USERS . " (match_id, user_id, user_status, match_users_create, user_update)
			#		VALUES ($data, " . $userdata['user_id'] . ", '" . intval($HTTP_POST_VARS['user_status']) . "', '" . time() . "', 0)";
			#	if ( !($result = $db->sql_query($sql)) )
			#	{
			#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			#	}
			}
			else if ( $ustatus != $status )
			{
				$sql = sql(MATCH_USERS, 'update', array('user_status' => $ustatus, 'status_update' => $time), array('user_id', 'match_id'), array($user, $data));
				$msg = $lang['msg_change_status_update'];
				
			#	$sql = "UPDATE " . MATCH_USERS . " SET
			#				user_status		= '" . intval($HTTP_POST_VARS['user_status']) . "',
			#				user_update		= '" . time() . "'
			#			WHERE match_id = $data AND user_id = " . $userdata['user_id'];
			#	if ( !$db->sql_query($sql) )
			#	{
			#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			#	}
			}
			else
			{
				$msg = $lang['msg_change_status_none'];
			}
			
			$template->assign_vars(array("META" => '<meta http-equiv="refresh" content="3;url=' . check_sid('match.php?mode=detail&amp;' . POST_MATCH . '=' . $data) . '">'));
			
			log_add(LOG_USERS, SECTION_MATCH, 'uchange');
			message(GENERAL_MESSAGE, $msg);
		}
		else if ( $smode == 'msg' )
		{
			if ( $last_entry['poster_ip'] != $userdata['session_ip'] || ($last_entry['time_create'] + $settings['spam_comment_match']) < $time )
			{
				include($root_path . 'includes/functions_post.php');
				
				if ( !$userdata['session_logged_in'] )
				{
					$sql = "SELECT captcha FROM " . CAPTCHA . " WHERE session_id = '" . $userdata['session_id'] . "' AND captcha_id = '" . md5($user_ip) . "'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$cp = $db->sql_fetchrow($result);
					$captcha = $cp['captcha'];
				
				#	$captcha = $_SESSION['captcha'];
					
					$poster_nick	= request('poster_nick', 2) ? request('poster_nick', 2) : '';
					$poster_mail	= request('poster_mail', 2) ? request('poster_mail', 2) : '';
					$poster_hp		= request('poster_hp', 2) ? request('poster_hp', 2) : '';
					$poster_captcha	= request('captcha', 3) ? request('captcha', 3) : '';
					
					$error .= !$poster_nick ? ( $error ? '<br />' : '' ) . $lang['msg_empty_nick'] : '';
					$error .= !$poster_mail ? ( $error ? '<br />' : '' ) . $lang['msg_empty_mail'] : '';
					$error .= ( $poster_captcha != $captcha  ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_captcha'] : '';
				}
				else
				{
					$poster_nick = $poster_mail = $poster_hp = $poster_captcha = '';
				}
				
				$poster_msg = request('poster_msg', 3) ? request('poster_msg', 3) : '';
				
				$error .= !$poster_msg ? ( $error ? '<br />' : '' ) . $lang['msg_empty_text'] : '';
				
				$template->assign_vars(array(
					'POSTER_NICK'	=> $poster_nick,
					'POSTER_MAIL'	=> $poster_mail,
					'POSTER_HP'		=> $poster_hp,
					'POSTER_MSG'	=> $poster_msg,
				));
				
				if ( !$error )
				{
					$oCache->deleteCache('detail_match_comments_' . $data);
					
					$sql = sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_MATCH, $data, $user));
					$msg = $lang['add_comment'] . sprintf($lang['click_return_match'],  '<a href="' . check_sid('match.php?mode=details&amp;' . POST_MATCH . '=' . $data) . '">', '</a>');
					
					msg_add('match', $data, $user, $poster_msg, $poster_nick, $poster_mail, $poster_hp);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
				}
			}
		}
	}
}
else
{
	$template->assign_block_vars('_list', array());
>>>>>>> .r85
	
<<<<<<< .mine
	if ( request('submit', 1) )
	{
		if ( $smode == 'change' )
=======
#	$sql = "SELECT m.*, m.match_rival_lineup AS lineup_rival, t.team_id, t.team_name, g.game_image, g.game_size, ml.match_id AS lineup_clan
#				FROM " . MATCH . " m
#					LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
#					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
#					LEFT JOIN " . MATCH_LINEUP . " ml ON m.match_id = ml.match_id
#			ORDER BY m.match_date DESC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$matches = $db->sql_fetchrowset($result);
#	$matches = _cached($sql, 'data_match');
	
	$page_title = $lang['match'];
	
	main_header();

	if ( !$tmp )
	{
		$template->assign_block_vars('_list._entry_empty_new', array());
		$template->assign_block_vars('_list._entry_empty_old', array());
	}
	else
	{
		$new = $old = array();
			
		foreach ( $tmp as $keys => $row )
>>>>>>> .r85
		{
<<<<<<< .mine
			$status		= request('status', 0);
			$ustatus	= request('user_status', 1);
=======
			if ( $userdata['user_level'] >= TRIAL )
			{
				if ( $row['match_date'] > $time )
				{
					$new[]		= $row;
					$new_ids[]	= $row['match_id'];
				}
				else if ( $row['match_date'] < $time )
				{
					$old[]		= $row;
					$old_ids[]	= $row['match_id'];
				}
			}
			else if ( $row['match_public'] == '1' )
			{
				if ( $row['match_date'] > $time )
				{
					$new[]		= $row;
					$new_ids[]	= $row['match_id'];
				}
				else if ( $row['match_date'] < $time )
				{
					$old[]		= $row;
					$old_ids[]	= $row['match_id'];
				}
			}
		}
		
	#	debug($new);
	#	debug($old);
		
		$cntnew = count($new);
		$cntold = count($old);
		
		if ( !$new )
		{
			$template->assign_block_vars('_list._entry_empty_new', array());
		}
		else
		{
			$sql = "SELECT * FROM " . MATCH_USERS . " WHERE match_id IN (" . implode(', ', $new_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
>>>>>>> .r85
			
<<<<<<< .mine
			if ( $status == -1 )
			{
				$sql = sql(MATCH_USERS, 'create', array('match_id' => $data, 'user_id' => $user, 'user_status' => $ustatus, 'status_create' => $time));
				$msg = $lang['msg_change_status_create'];
				
			#	$sql = 'INSERT INTO ' . MATCH_USERS . " (match_id, user_id, user_status, match_users_create, user_update)
			#		VALUES ($data, " . $userdata['user_id'] . ", '" . intval($HTTP_POST_VARS['user_status']) . "', '" . time() . "', 0)";
			#	if ( !($result = $db->sql_query($sql)) )
			#	{
			#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			#	}
			}
			else if ( $ustatus != $status )
			{
				$sql = sql(MATCH_USERS, 'update', array('user_status' => $ustatus, 'status_update' => $time), array('user_id', 'match_id'), array($user, $data));
				$msg = $lang['msg_change_status_update'];
				
			#	$sql = "UPDATE " . MATCH_USERS . " SET
			#				user_status		= '" . intval($HTTP_POST_VARS['user_status']) . "',
			#				user_update		= '" . time() . "'
			#			WHERE match_id = $data AND user_id = " . $userdata['user_id'];
			#	if ( !$db->sql_query($sql) )
			#	{
			#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			#	}
			}
			else
			{
				$msg = $lang['msg_change_status_none'];
			}
			
			$template->assign_vars(array("META" => '<meta http-equiv="refresh" content="3;url=' . check_sid('match.php?mode=detail&amp;' . POST_MATCH . '=' . $data) . '">'));
			
			log_add(LOG_USERS, SECTION_MATCH, 'uchange');
			message(GENERAL_MESSAGE, $msg);
=======
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_ary[$row['match_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = 0; $i < $cntnew; $i++ )
			{
				$match_id = $new[$i]['match_id'];
				
				$name	= ( $new[$i]['match_public'] ) ? sprintf($lang['sprintf_match_name'], $new[$i]['match_rival_name']) : sprintf($lang['sprintf_match_intern'], $new[$i]['match_rival_name']);
				$css	= 'none';
				$pos	= $lang['join_none'];
				
				if ( isset($in_ary[$match_id][$userdata['user_id']]) )
				{
					switch ( $in_ary[$match_id][$userdata['user_id']] )
					{
						case STATUS_NO:			$css = 'no';		$pos = $lang['no'];			break;
						case STATUS_YES:		$css = 'yes';		$pos = $lang['yes'];		break;
						case STATUS_REPLACE:	$css = 'replace';	$pos = $lang['replace'];	break;
					}
				}
				
				$template->assign_block_vars('_list._new_row', array(
					'CLASS'	=> ( $i % 2 ) ? 'row1r' : 'row2r',
					
					'GAME'	=> display_gameicon($new[$i]['game_size'], $new[$i]['game_image']),
					'NAME'	=> "<a href=\"" . check_sid("$file?$url=$match_id") . "\" >$name</a>",
					'DATE'	=> create_date($userdata['user_dateformat'], $new[$i]['match_date'], $userdata['user_timezone']),
					
					'CSS'		=> $css,
					'STATUS'	=> $pos,
				));
			}
>>>>>>> .r85
		}
<<<<<<< .mine
		else if ( $smode == 'msg' )
		{
			if ( $last_entry['poster_ip'] != $userdata['session_ip'] || ($last_entry['time_create'] + $settings['spam_comment_match']) < $time )
			{
				include($root_path . 'includes/functions_post.php');
				
				if ( !$userdata['session_logged_in'] )
				{
					$sql = "SELECT captcha FROM " . CAPTCHA . " WHERE session_id = '" . $userdata['session_id'] . "' AND captcha_id = '" . md5($user_ip) . "'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$cp = $db->sql_fetchrow($result);
					$captcha = $cp['captcha'];
				
				#	$captcha = $_SESSION['captcha'];
					
					$poster_nick	= request('poster_nick', 2) ? request('poster_nick', 2) : '';
					$poster_mail	= request('poster_mail', 2) ? request('poster_mail', 2) : '';
					$poster_hp		= request('poster_hp', 2) ? request('poster_hp', 2) : '';
					$poster_captcha	= request('captcha', 3) ? request('captcha', 3) : '';
					
					$error .= !$poster_nick ? ( $error ? '<br />' : '' ) . $lang['msg_empty_nick'] : '';
					$error .= !$poster_mail ? ( $error ? '<br />' : '' ) . $lang['msg_empty_mail'] : '';
					$error .= ( $poster_captcha != $captcha  ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_captcha'] : '';
				}
				else
				{
					$poster_nick = $poster_mail = $poster_hp = $poster_captcha = '';
				}
				
				$poster_msg = request('poster_msg', 3) ? request('poster_msg', 3) : '';
				
				$error .= !$poster_msg ? ( $error ? '<br />' : '' ) . $lang['msg_empty_text'] : '';
				
				$template->assign_vars(array(
					'POSTER_NICK'	=> $poster_nick,
					'POSTER_MAIL'	=> $poster_mail,
					'POSTER_HP'		=> $poster_hp,
					'POSTER_MSG'	=> $poster_msg,
				));
				
				if ( !$error )
				{
					$oCache->deleteCache('detail_match_comments_' . $data);
					
					$sql = sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_MATCH, $data, $user));
					$msg = $lang['add_comment'] . sprintf($lang['click_return_match'],  '<a href="' . check_sid('match.php?mode=details&amp;' . POST_MATCH . '=' . $data) . '">', '</a>');
					
					msg_add('match', $data, $user, $poster_msg, $poster_nick, $poster_mail, $poster_hp);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
				}
			}
		}
=======
		
		if ( !$old )
		{
			$template->assign_block_vars('_list._entry_empty_old', array());
		}
		else
		{
			
			$sql = "SELECT * FROM " . MATCH_USERS . " WHERE match_id IN (" . implode(', ', $old_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_ary[$row['match_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, $cntold); $i++ )
			{
				$match_id = $old[$i]['match_id'];
				
				$name	= ( $old[$i]['match_public'] ) ? sprintf($lang['sprintf_match_name'], $old[$i]['match_rival_name']) : sprintf($lang['sprintf_match_intern'], $old[$i]['match_rival_name']);
				$css	= 'none';
				$pos	= $lang['join_none'];
				
				if ( isset($in_ary[$match_id][$userdata['user_id']]) )
				{
					switch ( $in_ary[$match_id][$userdata['user_id']] )
					{
						case STATUS_NO:			$css = 'no';		$pos = $lang['no'];			break;
						case STATUS_YES:		$css = 'yes';		$pos = $lang['yes'];		break;
						case STATUS_REPLACE:	$css = 'replace';	$pos = $lang['replace'];	break;
					}
				}
				
				$template->assign_block_vars('_list._old_row', array(
					'CLASS'	=> ( $i % 2 ) ? 'row1r' : 'row2r',
					
					'GAME'	=> display_gameicon($old[$i]['game_size'], $old[$i]['game_image']),
					'NAME'	=> "<a href=\"" . check_sid("$file?$url=$match_id") . "\" >$name</a>",
					'DATE'	=> create_date($userdata['user_dateformat'], $old[$i]['match_date'], $userdata['user_timezone']),
					
					'CSS'		=> $css,
					'STATUS'	=> $pos,
				));
			}
		}
>>>>>>> .r85
	}
<<<<<<< .mine
=======
	
	$current_page = ( !$cntold ) ? 1 : ceil( $cntold / $settings['site_entry_per_page'] );
	
	$template->assign_vars(array(
		'L_DETAILS'		=> $lang['match_details'],
		
		'L_UPCOMING'	=> $lang['match_upcoming'],
		'L_EXPIRED'		=> $lang['match_expired'],
		
		'PAGE_PAGING'	=> generate_pagination($file, $cntold, $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
	));
>>>>>>> .r85
}
<<<<<<< .mine
else
{
	redirect(check_sid($file, true));
}
=======
>>>>>>> .r85

$template->pparse('body');

main_footer();

?>