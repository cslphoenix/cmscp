<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_MATCH);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_MATCH;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request('id', INT);	
$mode	= request('mode', TYP);
$smode	= request('smode', TYP);

$dir_path	= $root_path . $settings['path_matchs']['path'];

$error	= '';
$fields	= '';

$submit	= ( isset($_POST['submit']) ) ? true : false;

$template->set_filenames(array(
	'body'		=> 'body_match.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'error_body.tpl',
));

add_lang('lang_match');

$sql = "SELECT DISTINCT m.*, m.match_rival_lineup AS lineup_rival, t.team_id, t.team_name, g.game_image, l.type_id AS lineup_clan
			FROM " . MATCH . " m
				LEFT JOIN " . TEAMS . " t ON m.team_id = t.team_id
				LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				LEFT JOIN " . LISTS . " l ON m.match_id = l.type_id AND type = " . TYPE_MATCH . "
		ORDER BY m.match_date DESC";
#if ( !($result = $db->sql_query($sql)) )
#{
#	message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#}
#$tmp = $db->sql_fetchrowset($result);
$tmp = _cached($sql, 'data_match');

debug($_POST);

if ( $data && $tmp )
{
	$template->assign_block_vars('view', array());
	
	foreach ( $tmp as $row )
	{
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
	
	main_header($page_title);
	
#	if ( $userdata['user_level'] == ADMIN || $userauth['auth_match'] )
#	{
#		$template->assign_block_vars('view.update', array(
#			'UPDATE'		=> "<a href=\"" . check_sid("admin/admin_match.php?mode=_update&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['match_update'] . "</a>",
#			'UPDATE_DETAIL'	=> "<a href=\"" . check_sid("admin/admin_match.php?mode=_detail&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['match_detail'] . "</a>",
#		));
#	}
	
	$lineup_clan = $lineup_rival = $player = $replace = '';
	
	/* Lineup Clan und Lineup Gegner - es fehlt noch der clantag vom clan selber */
	if ( $view['lineup_clan'] || $view['lineup_rival'] )
	{
		$template->assign_block_vars('view.lineup', array());
		
		$sql = "SELECT ml.user_id, ml.user_status, u.user_name, u.user_color
					FROM " . LISTS . " ml, " . USERS . " u
					WHERE type_id = $data AND type = " . TYPE_MLINE . " AND u.user_id = ml.user_id
				ORDER BY ml.user_status";
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
				$player[] = "<a href=\"profile.php?u=$player_id\"><span style=\"color:$player_color\">$player_name</span></a>";
			}
			else
			{
				$replace[] = "<a href=\"profile.php?u=$player_id\"><span style=\"color:$player_color\">$player_name</span></a>";
			}
        }

        if ( $player )
        {
			$template->assign_block_vars('view.lineup.clan', array());

			$player = implode(', ', $player);
			$replace = $replace ? implode(', ', $replace) : '';
			
			$lineup_clan = ( $player && $replace ) ? sprintf($lang['lineup_players'], $player, $replace) : sprintf($lang['lineup_player'], $player);
		}
		
		/* Filter beim eintragen noch bearbeiten damit alle gleich eingetragen sind mit syntax ', ' */
		if ( $view['lineup_rival'] )
		{
			$template->assign_block_vars('view.lineup.rival', array());
			
			$rivals = explode(', ', $view['lineup_rival']);
			
			foreach ( $rivals as $row )
			{
				$ary[] = $view['match_rival_tag'] . " $row";
			}

			$lineup_rival = sprintf($lang['lineup_player'], implode(', ', $ary));
		}
	}
	
	$fielde = '';
	
	if ( $view['match_hltv_ip'] )
	{
		$template->assign_block_vars('view.hltv', array(
			'HLTV'		=> '<a href="hlsw://' . $view['match_hltv_ip'] . '">' . $lang['hltv'] . '</a>',
			'HLTV_PW'	=> ( $userdata['user_level'] >= TRIAL ) ? $view['match_hltv_pw'] : '',
		));
	}
	
	$sql = "SELECT mm.map_round, mm.map_points_home, mm.map_points_rival, mm.map_picture, mm.map_preview, m.map_name
				FROM " . MATCH_MAPS . " mm
					LEFT JOIN " . MAPS . " m ON mm.map_name = m.map_id
				WHERE mm.match_id = {$view['match_id']} ORDER BY mm.match_id, mm.map_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$map = $db->sql_fetchrowset($result);
	
	if ( $map )
	{
		$home = '';
		$rival = '';
		$path = $dir_path . $view['match_path'];
		
		foreach ( $map as $info )
		{
			$round[$info['map_round']] = $info['map_round'];
			
			$home[$info['map_round']] = isset($home[$info['map_round']]) ? $home[$info['map_round']] + $info['map_points_home'] : $info['map_points_home'];
			$rival[$info['map_round']] = isset($rival[$info['map_round']]) ? $rival[$info['map_round']] + $info['map_points_rival'] : $info['map_points_rival'];
			
			$pic[$info['map_round']][] = array($info['map_picture'], $info['map_preview']);
		}
		
		$round = array_unique($round);

		foreach ( $round as $rnd )
		{
			$hpoint = isset($home[$rnd]) ? $home[$rnd] : 0;
			$rpoint = isset($rival[$rnd]) ? $rival[$rnd] : 0;
			
			if ( isset($pic[$rnd]) )
			{
				$cnt = 0;
				$picture = $preview = $pics[$rnd] = '';
				
				foreach ( $pic[$rnd] as $picinfo )
				{
					list($picture, $preview) = $picinfo;
					
					$pics[$rnd] .= "<a href=\"{$path}/{$picture}\" rel=\"lightbox\"><img src=\"{$path}/{$preview}\" alt=\"\" /></a>";
					$pics[$rnd] .= ( $cnt % 2 ) ? '<br />' : '';
					
					$cnt++;
				}
			}
			else
			{
				$pics[$rnd] = '';
			}
			
			$template->assign_block_vars('view.maps', array(
				'ROUND'		=> $rnd,
				'PICS'		=> $pics[$rnd],
				'POINTS'	=> sprintf('%d:%d', $hpoint, $rpoint),
			));
			
			
			
			#$cnt_show = false;
			
			$show = 0;
			
			foreach ( $map as $rows )
			{
			#	debug($cnt_show, "$rows");
				
				if ( $rnd == $rows['map_round'] )
				{
					$phome	= $rows['map_points_home'];
					$prival	= $rows['map_points_rival'];
					
				#	$show ? false : $template->assign_block_vars('lobby.news', array());
					
					
					$template->assign_block_vars('view.maps.row', array(
						
						'POINTS_HOME'	=> $phome,
						'POINTS_RIVAL'	=> $prival,
					));
					
					$show++;
				}
				
				
			}
		}
	}
	
	/* Teilnahme - nur sichtbar für eingeloggte und mit dem Status ab Trail sichtbar */
	if ( $userdata['session_logged_in'] && $userdata['user_level'] >= TRIAL )
	{
		$template->assign_block_vars('view.status', array());

		$sql = "SELECT mu.*, u.user_name, u.user_color
					FROM " . LISTS . " mu, " . USERS . " u
				WHERE mu.user_id = u.user_id AND type = " . TYPE_MATCH . " AND mu.type_id = $data";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$status = $db->sql_fetchrowset($result);
		
		debug($status, 'status');
		
		if ( $status )
		{
			$template->assign_block_vars('view.status.entry', array());

			for ( $i = 0; $i < count($status); $i++ )
			{
				$player_id		= $status[$i]['user_id'];
				$player_name	= $status[$i]['user_name'];
				$player_color	= $status[$i]['user_color'];
				
				$css = ( $status[$i]['user_status'] != STATUS_NO ) ? ( $status[$i]['user_status'] == STATUS_YES ) ? 'yes' : 'replace' : 'no';
				$lng = ( $status[$i]['user_status'] != STATUS_NO ) ? ( $status[$i]['user_status'] == STATUS_YES ) ? $lang['status_yes'] : $lang['status_replace'] : $lang['status_no'];
				
				
				$player = "<a href=\"profile.php?user=$player_id\"><span style=\"color:$player_color\">$player_name</span></a>";
				
				$status_update = $status[$i]['time_update'];
				
				$time_update = create_shortdate($userdata['user_dateformat'], $status[$i]['time_update'], $userdata['user_timezone']);
				$time_create = create_shortdate($userdata['user_dateformat'], $status[$i]['time_create'], $userdata['user_timezone']);
				
				$template->assign_block_vars('view.status.entry.row', array(
					'USER'		=> $player,
					'CLASS'		=> $css,
					'STATUS'	=> $lng,
					'DATE'		=> $status_update ? $lang['change_on'] . $time_update : $time_create,
				));
	        }
	    }
		
		if ( $view['match_date'] > $time )
		{
			$sql = "SELECT * FROM " . LISTS . " WHERE user_id = $user AND type = " . TYPE_TEAM . " AND type_id = " . $view['team_id'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			if ( $db->sql_numrows($result) )
			{
				$template->assign_block_vars('view.status.switch', array());

				$sql = "SELECT user_status FROM " . LISTS . " WHERE type = " . TYPE_MATCH . " AND user_id = $user AND type_id = $data";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				
				$status = ( !$row['user_status'] ) ? -1 : $row['user_status'];
				
				$fielde .= "<input type=\"hidden\" name=\"smode\" value=\"change\" />";
				$fielde .= "<input type=\"hidden\" name=\"status\" value=\"$status\" />";
				$fielde .= "<input type=\"hidden\" name=\"mode\" value=\"detail\" />";
				$fielde .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
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
	
	/* Kommentarfunktion - Nur wenn die Generelle Funktion aktiviert ist und für das Match selber */
	if ( $settings['comments']['match'] && $view['match_comments'] )
	{
		$template->assign_block_vars('view.comment', array());

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
		
		if ( !$comments )
		{
			$template->assign_block_vars('view.comment.empty', array());
			
			$last_entry = array('poster_ip' => '', 'time_create' => '');
		}
		else
		{
			$cnt = count($comments);
			
			$sql = "SELECT read_time FROM " . COMMENT_READ . " WHERE user_id = $user AND type_id = $data AND type = " . READ_MATCH;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$unread = $db->sql_fetchrow($result);
			
			$unreads = ( !$unread ) ? true : false;
			
			for ( $i = $start; $i < min($settings['ppec'] + $start, $cnt); $i++ )
			{
				$icon = ( $userdata['session_logged_in'] ) ? ( $unreads || ( $unread['read_time'] < $comments[$i]['time_create'] ) ) ? $images['icon_minipost_new'] : $images['icon_minipost'] : $images['icon_minipost'];
				$name = $comments[$i]['poster_nick'] ? $comments[$i]['poster_nick'] : '<font color="' . $comments[$i]['user_color'] . '">' . $comments[$i]['user_name'] . '</font>';
				$link = $comments[$i]['poster_nick'] ? $userdata['session_logged_in'] ? 'mailto:' . $comments[$i]['poster_email'] : $comments[$i]['poster_nick'] : 'profile.php?mode=view&amp;id=' . $comments[$i]['poster_id'];
				
				$s_option = '';
				
				$template->assign_block_vars('view.comment.row', array(
					'CLASS'	=> ( $i % 2 ) ? $theme['td_class1'] : $theme['td_class2'],
					'ICON'	=> $icon,

					'DATE'		=> create_shortdate($userdata['user_dateformat'], $comments[$i]['time_create'], $userdata['user_timezone']),
					'POSTER'	=> "<a href=\"$link\">$name</a>",
					'MESSAGE'	=> $comments[$i]['poster_text'],

					'OPTIONS'	=> $s_option,
				));
			}
		
			$current_page = $cnt ? ceil($cnt/$settings['ppec']) : 1;
			
			$template->assign_vars(array(
				'PAGE_PAGING' => generate_pagination("$file?$url=$data", $cnt, $settings['ppec'], $start),
				'PAGE_NUMBER' => sprintf($lang['common_page_of'], (floor($start/$settings['ppec'])+1), $current_page),
			));
			
			/* Letzter Kommentareintrag */
			sort($comments);
			$last_entry = array_pop($comments);		
		}
		
		/* Kommentare für Gäste */
		if ( $settings['comments']['match_guest'] && !$userdata['session_logged_in'] )
		{
			$template->assign_block_vars('view.comment.guest', array());
		}
	#	if ( request('submit', TXT) && ( $last_entry['poster_ip'] != $userdata['session_ip'] || ($last_entry['time_create'] + $settings['spam_comments']['match']) < $time ) )
		if ( request('submit', TXT) && $smode == 'msg' && ( $last_entry['poster_ip'] != $userdata['session_ip'] || ($last_entry['time_create'] + $settings['spam_comments']['news']) < $time ) )
		{
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
				sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_MATCH, $data, $user));
				
				$msg = $lang['add_comment'] . sprintf($lang['click_return_news'],  '<a href="' . check_sid("$file?$url=$data") . '">', '</a>');
				
				msg_add(MATCH, $data, $user, $poster_msg, $poster_nick, $poster_mail, $poster_hp);
				message(GENERAL_MESSAGE, $msg);
			}
			else
			{
				$template->assign_vars(array('ERROR_MESSAGE' => $error));
				$template->assign_var_from_handle('ERROR_BOX', 'error');
			}
		}
		
		
		$template->assign_vars(array(
			'L_COMMENT'		=> $lang['common_comment'],
			'L_SUBMIT'		=> $lang['common_submit'],
			'L_RESET'		=> $lang['common_reset'],
		));
		
		$template->assign_var_from_handle('COMMENTS', 'comments');
	}
	
	if ( $submit )
	{
		debug($_POST, 'POST');
		
		if ( $smode == 'change' )
		{
			$status		= request('status', INT);
			$ustatus	= request('user_status', 1);
			
			if ( $status == -1 )
			{
				$sql = sql(LISTS, 'create', array('type' => TYPE_MATCH, 'type_id' => $data, 'user_id' => $user, 'user_status' => $ustatus, 'time_create' => $time));
				$msg = $lang['msg_change_status_create'];
			}
			else if ( $ustatus != $status )
			{
				$sql = sql(LISTS, 'update', array('user_status' => $ustatus, 'time_update' => $time), array('user_id', 'type_id'), array($user, $data));
				$msg = $lang['msg_change_status_update'];
			}
			else
			{
				$msg = $lang['msg_change_status_none'];
			}
			
			$template->assign_vars(array("META" => '<meta http-equiv="refresh" content="3;url=' . check_sid("$file?$url=$data") . '">'));
		
			log_add(LOG_USERS, SECTION_MATCH, 'uchange');
		#	message(GENERAL_MESSAGE, $msg);
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
					$msg = $lang['add_comment'] . sprintf($lang['click_return_match'],  '<a href="' . check_sid('match.php?mode=details&amp;id=' . $data) . '">', '</a>');
					
					msg_add(MATCH, $data, $user, $poster_msg, $poster_nick, $poster_mail, $poster_hp);
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
	
#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"detail\" />";
#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
	$fields .= "<input type=\"hidden\" name=\"smode\" value=\"msg\" />";
	
	$template->assign_vars(array(
		'L_MATCH_INFO'	=> $lang['match_info'],
		
		'L_USERNAME'		=> $lang['user_name'],
		'L_STATUS'			=> $lang['status'],
		'L_STATUS_YES'		=> $lang['status_yes'],
		'L_STATUS_NO'		=> $lang['status_no'],
		'L_STATUS_REPLACE'	=> $lang['status_replace'],
		'L_SET_STATUS'		=> $lang['set_status'],
		
		'CLAN'	=> $lineup_clan,
		'RIVAL'	=> $lineup_rival,
		
		'L_COMMENT'	=> 'test',
		
		'RIVAL_NAME'		=> $view['match_rival_name'],
		'RIVAL_TAG'			=> $view['match_rival_tag'],
	#	'U_MATCH_RIVAL'		=> $view['match_rival_url'],
	#	'MATCH_RIVAL'		=> $view['match_rival_url'],
		'MATCH_CATEGORIE'		=> $lang['match_war'][$view['match_war']],
		'MATCH_TYPE'			=> $lang['match_type'][$view['match_type']],
		'MATCH_LEAGUE_INFO'		=> $lang['match_league'][$view['match_league']]['name'],
		'SERVER_IP'				=> $view['match_server_ip'] ? '<a href="hlsw://' . $view['match_server_ip'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
		'SERVER_PW'				=> ( $userdata['user_level'] >= TRIAL ) ? $view['match_server_pw'] : '',
		
	#	'DETAILS_COMMENT'		=> $view['details_comment'],
		
		'MATCH_MAIN'			=> '<a href="' . check_sid('match.php') . '">Übersicht</a>',
		
		'L_SUBMIT'	=> $lang['common_submit'],
		'L_RESET'	=> $lang['common_reset'],
		
		'S_FIELDE'		=> $fielde,
		'S_FIELDS'		=> $fields,
		'S_ACTION'		=> check_sid("$file?id=$data"),
	));
}
else
{
	$template->assign_block_vars('list', array());
	
	$page_title = $lang['match'];
	
	main_header($page_title);
	
	if ( !$tmp )
	{
		$template->assign_block_vars('list.entry_empty_new', array());
		$template->assign_block_vars('list.entry_empty_old', array());
	}
	else
	{
		$new = $old = array();
			
		foreach ( $tmp as $keys => $row )
		{
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

		if ( !$new )
		{
			$template->assign_block_vars('list.entry_empty_new', array());
		}
		else
		{
			$cntnew = count($new);
			
			$sql = "SELECT * FROM " . LISTS . " WHERE type = " . TYPE_MATCH . " AND type_id IN (" . implode(', ', $new_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_ary[$row['match_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = 0; $i < $cntnew; $i++ )
			{
				$match_id = $new[$i]['match_id'];
				
				$name	= ( $new[$i]['match_public'] ) ? sprintf($lang['sprintf_match_name'], $new[$i]['match_rival_name']) : sprintf($lang['sprintf_match_intern'], $new[$i]['match_rival_name']);
				$css	= isset($in_ary[$match_id][$user]) ? ( $in_ary[$match_id][$user] != STATUS_NO ) ? ( $in_ary[$match_id][$user] == STATUS_YES ) ? 'yes' : 'replace' : 'no' : 'none';
				$pos	= isset($in_ary[$match_id][$user]) ? ( $in_ary[$match_id][$user] != STATUS_NO ) ? ( $in_ary[$match_id][$user] == STATUS_YES ) ? $lang['yes'] : $lang['replace'] : $lang['no'] : $lang['join_none'];
				
				$template->assign_block_vars('list.new_row', array(
					'CLASS'	=> ( $i % 2 ) ? 'row1' : 'row2',
					
					'GAME'	=> display_gameicon($new[$i]['game_image']),
					'NAME'	=> "<a href=\"" . check_sid("$file?id=$match_id") . "\" >$name</a>",
					'DATE'	=> create_date($userdata['user_dateformat'], $new[$i]['match_date'], $userdata['user_timezone']),
					
					'CSS'		=> $css,
					'STATUS'	=> $pos,
				));
			}
		}
		
		if ( !$old )
		{
			$template->assign_block_vars('list.entry_empty_old', array());
		}
		else
		{
			$cntold = count($old);
			
			$sql = "SELECT * FROM " . LISTS . " WHERE type = " . TYPE_MATCH . " AND type_id IN (" . implode(', ', $old_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_ary[$row['match_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, $cntold); $i++ )
			{
				$match_id = $old[$i]['match_id'];
				
				$name	= ( $old[$i]['match_public'] ) ? sprintf($lang['sprintf_match_name'], $old[$i]['match_rival_name']) : sprintf($lang['sprintf_match_intern'], $old[$i]['match_rival_name']);
				$css	= isset($in_ary[$match_id][$user]) ? ( $in_ary[$match_id][$user] != STATUS_NO ) ? ( $in_ary[$match_id][$user] == STATUS_YES ) ? 'yes' : 'replace' : 'no' : 'none';
				$pos	= isset($in_ary[$match_id][$user]) ? ( $in_ary[$match_id][$user] != STATUS_NO ) ? ( $in_ary[$match_id][$user] == STATUS_YES ) ? $lang['yes'] : $lang['replace'] : $lang['no'] : $lang['join_none'];
				
				$template->assign_block_vars('list.old_row', array(
					'CLASS'	=> ( $i % 2 ) ? 'row1' : 'row2',
					
					'GAME'	=> display_gameicon($old[$i]['game_image']),
					'NAME'	=> "<a href=\"" . check_sid("$file?id=$match_id") . "\" >$name</a>",
					'DATE'	=> create_date($userdata['user_dateformat'], $old[$i]['match_date'], $userdata['user_timezone']),
					
					'CSS'		=> $css,
					'STATUS'	=> $pos,
				));
			}
		}
	}
	
	$current_page = ( !$cntold ) ? 1 : ceil( $cntold / $settings['per_page_entry_site'] );
	
	$template->assign_vars(array(
		'L_DETAILS'		=> $lang['match_details'],
		
		'L_UPCOMING'	=> $lang['match_upcoming'],
		'L_EXPIRED'		=> $lang['match_expired'],
		
		'PAGE_PAGING'	=> generate_pagination($file, $cntold, $settings['per_page_entry_site'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry_site'] ) + 1 ), $current_page ),
	));
}

$template->pparse('body');

main_footer();

?>