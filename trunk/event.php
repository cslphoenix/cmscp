<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_EVENT);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', 0) ) ? request('start', 0) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_EVENT;
$url	= POST_EVENT;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, 0);	
$mode	= request('mode', 1);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_event.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'error_body.tpl',
));

if ( $mode == '' )
{
	$template->assign_block_vars('_list', array());
	
	$sql = "SELECT * FROM " . EVENT . " ORDER BY event_date DESC, event_id DESC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$events = $db->sql_fetchrowset($result);
	$events = _cached($sql, 'data_event');
	
	$page_title = $lang['event'];
	
	main_header();
	
	if ( !$events )
	{
		$template->assign_block_vars('_list._entry_empty_new', array());
		$template->assign_block_vars('_list._entry_empty_old', array());
	}
	else
	{
		foreach ( $events as $event => $row )
		{
			if ( $userdata['user_level'] >= $row['event_level'] && $row['event_date'] > time() )
			{
				$new[]		= $row;
				$new_ids[]	= $row['event_id'];
				
			}
			else if ( $userdata['user_level'] >= $row['event_level'] && $row['event_date'] < time() )
			{
				$old[]		= $row;
				$old_ids[]	= $row['event_id'];
			}
		}
		
		$count = isset($old) ? count($old) : 0;
		
		if ( !$new )
		{
			$template->assign_block_vars('_list._entry_empty_new', array());
		}
		else
		{
			$sql = "SELECT * FROM " . EVENT_USERS . " WHERE event_id IN (" . implode(', ', $new_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_ary[$row['event_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = 0; $i < count($new); $i++ )
			{
				$event_id	= $new[$i]['event_id'];
				$event_date	= create_date('d.m.Y', $new[$i]['event_date'], $userdata['user_timezone']);
				$event_time	= create_date('H:i', $new[$i]['event_date'], $userdata['user_timezone']);
				$event_dura	= create_date('H:i', $new[$i]['event_duration'], $userdata['user_timezone']);
				
				$css	= 'none';
				$status	= $lang['join_none'];
				
				if ( isset($in_ary[$event_id]) )
				{
					foreach ( $in_ary[$event_id] as $key => $row )
					{
						if ( $key == $userdata['user_id'] )
						{
							switch ( $row )
							{
								case STATUS_NO:		$css = 'no';	$status = $lang['join_not'];	break;
								case STATUS_YES:	$css = 'yes';	$status = $lang['join_yes'];	break;
							}
						}
					}
				}
				
				$template->assign_block_vars('_list._new_row', array(
					'CLASS' 	=> ( $i % 2 ) ? 'row1r' : 'row2r',
					'CSS'		=> $css,
					'STATUS'	=> $status,
					'TITLE'		=> '<a href="' . check_sid("$file?mode=view&amp;$url=$event_id") . '" alt="" />' . $new[$i]['event_title'] . '</a>',
					'DATE'		=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
					
				));
			}
		}
		
		if ( !$old )
		{
			$template->assign_block_vars('_list._entry_empty_old', array());
		}
		else
		{
			$sql = "SELECT * FROM " . EVENT_USERS . " WHERE event_id IN (" . implode(', ', $old_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_ary[$row['event_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, $count); $i++ )
			{
				$event_id	= $old[$i]['event_id'];
				$event_date	= create_date('d.m.Y', $old[$i]['event_date'], $userdata['user_timezone']);
				$event_time	= create_date('H:i', $old[$i]['event_date'], $userdata['user_timezone']);
				$event_dura	= create_date('H:i', $old[$i]['event_duration'], $userdata['user_timezone']);
				
				$css	= 'none';
				$status	= $lang['join_none'];
					
				if ( isset($in_ary[$event_id]) )
				{
					foreach ( $in_ary[$event_id] as $key => $row )
					{
						if ( $key == $userdata['user_id'] )
						{
							switch ( $row )
							{
								case STATUS_NO:		$css = 'no';	$status = $lang['join_not'];	break;
								case STATUS_YES:	$css = 'yes';	$status = $lang['join_yes'];	break;
							}
						}
					}
				}
				
				$template->assign_block_vars('_list._old_row', array(
					'CLASS' 	=> ( $i % 2 ) ? 'row1r' : 'row2r',
					'CSS'		=> $css,
					'STATUS'	=> $status,
					'TITLE'		=> '<a href="' . check_sid("$file?mode=view&amp;$url=$event_id") . '" alt="" />' . $old[$i]['event_title'] . '</a>',
					'DATE'		=> sprintf($lang['sprintf_event'], $event_date, $event_time, $event_dura),
				));
			}
		}
		
		$current_page = !$count ? 1 : ceil( $count / $settings['site_entry_per_page'] );
		
		$template->assign_vars(array(
			'L_HEAD'	=> $lang['event'],
			
			'L_UPCOMING'	=> $lang['event_upcoming'],
			'L_EXPIRED'		=> $lang['event_expired'],
			
			'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
			'PAGE_PAGING'	=> generate_pagination("$file?", $count, $settings['site_entry_per_page'], $start ),
			
			'S_ACTION'	=> check_sid($file),
		));
	}
}
else if ( $mode == 'view' && $data )
{
	$template->assign_block_vars('_view', array());
	
	$sql = "SELECT * FROM " . EVENT . " ORDER BY event_date DESC, event_id DESC";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$event = $db->sql_fetchrowset($result);
	$event = _cached($sql, 'data_event');
	
	foreach ( $event as $key => $row )
	{
		if ( $userdata['user_level'] >= $row['event_level'] && $row['event_id'] == $data )
		{
			$view = $row;
		}
	}
	
	if ( !$view )
	{
		message(GENERAL_ERROR, $lang['msg_event_fail']);
	}
	
	$page_title = sprintf($lang['news_head_info'], $view['event_title']);
	
	main_header();
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] )
	{
		$template->assign_block_vars('_view._update', array(
			'UPDATE'		=> "<a href=\"" . check_sid("admin/admin_event.php?mode=_update&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['event_update'] . "</a>",
			'UPDATE_DETAIL'	=> "<a href=\"" . check_sid("admin/admin_event.php?mode=_detail&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['event_detail'] . "</a>",
		));
	}
	
	if ( $settings['comments_event'] && $event['event_comments'] )
	{
		$template->assign_block_vars('_view._comment', array());
		
		$sql = "SELECT c.*, u.user_id, u.user_name, u.user_color, u.user_email
					FROM " . COMMENT . " c
						LEFT JOIN " . USERS . " u ON c.poster_id = u.user_id
					WHERE c.type_id = $data AND c.type = " . READ_EVENT . "
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
			for ( $i = $start; $i < min($settings['site_comment_per_page'] + $start, count($comments)); $i++ )
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
				
				$user_id = $comment_entry[$i]['user_id'];
	
				$template->assign_block_vars('_view._comments.comments', array(
					'CLASS' 		=> $class,
					'ID' 			=> $comment_entry[$i]['news_comments_id'],
					'COLOR'			=> ( $comment_entry[$i]['user_color'] ) ? 'style="color:' . $comment_entry[$i]['user_color'] . '"' : '',
					'L_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_nick'] : $comment_entry[$i]['user_name'],
					'U_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_email'] : check_sid("profile.php?mode=view&u=$user_id"),
					'MESSAGE'		=> $comment,
					'DATE'			=> create_date($userdata['user_dateformat'], $comment_entry[$i]['time_create'], $userdata['user_timezone']),
					
					'ICON'			=> $icon,
	
					'U_EDIT'		=> check_sid('news.php?mode=edit&amp;' . POST_NEWS . '=' . $comment_entry[$i]['news_id']),
					'U_DELETE'		=> check_sid('news.php?mode=delete&amp;' . POST_NEWS . '=' . $comment_entry[$i]['news_id'])
				));
			}
		
			$current_page = ( !count($comment_entry) ) ? 1 : ceil( count($comment_entry) / $settings['site_comment_per_page'] );
			
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination('news.php?mode=view&amp;' . POST_NEWS . '=' . $news_id, count($comment_entry), $settings['site_comment_per_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['site_comment_per_page'] ) + 1 ), $current_page ), 
			
				'L_GOTO_PAGE' => $lang['Goto_page'])
			);
			
			//	Letzter Kommentareintrag
			//	sort (Sortiert ein Array)
			//	array_pop (Liefert das letzte Element eines Arrays)
			sort($comment_entry);
			$last_entry = array_pop($comment_entry);
		
		}
		
		//	Wer darf Kommentare schreiben?!?
		if ($settings['comments_news_guest'] && !$userdata['session_logged_in'])
		{
			$template->assign_block_vars('_view.news_comments.news_comments_guest', array());
		}
		
		//	Eingeloggte Benutzer können immer kommentieren
		if ($userdata['session_logged_in'])
		{
			$template->assign_block_vars('_view.news_comments.news_comments_member', array());
		}
		
		$error = '';
		$error_msg = '';
		
		//	Erlaubt nach Absenden kein Doppelbeitrag erst nach 20 Sekunden
		if ( isset($HTTP_POST_VARS['submit']) && ( $last_entry['poster_ip'] != $userdata['session_ip'] || $last_entry['time_create']+20 < time() ) )
		{
			//	Laden der Funktion zum eintragen von Kommentaren
			include($root_path . 'includes/functions_post.php');
			
			//	Bei Fehlern wird der Text erneut in die Felder eingetragen
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
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . 'user_nick';
				}
				
				if ( empty($HTTP_POST_VARS['poster_mail']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . 'poster_mail';
				}
				
				unset($_SESSION['captcha']);
			}
				
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
				$sql = "SELECT * FROM " . COMMENT_READ . " WHERE user_id = " . $userdata['user_id'] . " AND type_id = $news_id AND type = " . READ_NEWS;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( $db->sql_numrows($result) )
				{
					$sql = "UPDATE " . COMMENT_READ . " SET read_time = " . time() . " WHERE type_id = $news_id AND user_id = " . $userdata['user_id'];					
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{				
					$sql = "INSERT INTO " . COMMENT_READ . " (type, type_id, user_id, read_time) VALUES (" . READ_NEWS . ", $news_id, " .  $userdata['user_id'] . ", " . time() . ")";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				//	Keine Fehler?
				//	Cache löschung und eintragung des Kommentars
				
				$oCache -> deleteCache('news_details_' . $news_id . '_comments');
				
				_comment_message('add', 'news', $news_id, $userdata['user_id'], $user_ip, $HTTP_POST_VARS['comment'], $poster_nick, $poster_mail, '');
				
				$message = $lang['add_comment'] . sprintf($lang['click_return_news'],  '<a href="' . check_sid('news.php?mode=view&amp;' . POST_NEWS . '=' . $news_id) . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
			}
		}
	}
	
	if ( $userdata['session_logged_in'] )
	{
		$sql = "SELECT read_time FROM " . COMMENT_READ . " WHERE type_id = $data AND type = " . READ_EVENT . " AND user_id = $user";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$unread = $db->sql_fetchrow($result);

		if ( $unread )
		{
			$sql = "UPDATE " . COMMENT_READ . " SET read_time = " . time() . " WHERE type_id = $data AND type = " . READ_EVENT . " AND user_id = $user";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$unreads = false;
		}
		else
		{
			$sql = "INSERT INTO " . COMMENT_READ . " (type, type_id, user_id, read_time) VALUES (" . READ_EVENT . ", $data, $user, $time)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$unreads = true;
		}
	}
	
	$template->assign_vars(array(
		
		'NEWS_TITLE'	=> $page_title,
		'NEWS_TEXT'		=> html_entity_decode($news_details['news_text'], ENT_QUOTES),
								 
	));

}
else
{
	redirect(check_sid($file, true));
}

$template->pparse('body');

main_footer();

?>