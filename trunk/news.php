<?php

/*
 *
 *	match.php
 *
 *	bis auf die Überprüfung der Mailadresse
 *	und Validierung der Homepage alles fertig
 *
 *	Logfunktion bis auf Dateninhalt was geändert wurde
 *
 *	17.03.2009 kommentiert
 *
*/

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_MATCH);
init_userprefs($userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_POST_VARS[POST_NEWS_URL]) || isset($HTTP_GET_VARS[POST_NEWS_URL]) )
{
	$news_id = ( isset($HTTP_POST_VARS[POST_NEWS_URL]) ) ? intval($HTTP_POST_VARS[POST_NEWS_URL]) : intval($HTTP_GET_VARS[POST_NEWS_URL]);
}
else
{
	$news_id = '';
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
	$page_title = $lang['news'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'news_body.tpl'));
	
	//
	//	List News
	//
	if ($userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		$sql = 'SELECT n.*, nc.news_category_title, nc.news_category_image, u.username, u.user_color, m.*, md.*, t.team_name, g.game_image, g.game_size
					FROM ' . NEWS . ' n
						LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
						LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
						LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . MATCH_DETAILS . ' md ON m.match_id = md.match_id
						LEFT JOIN ' . NEWS_CATEGORY . ' nc ON n.news_category = nc.news_category_id
					WHERE n.news_time_public < ' . time() . ' AND news_public = 1
				ORDER BY n.news_time_public DESC';
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
		}
		$news_data = $db->sql_fetchrowset($result);
//		$news_data = _cached($sql, 'news_list_member');
	}
	else
	{
		$sql = 'SELECT n.*, nc.news_category_title, nc.news_category_image, u.username, u.user_color, m.*, md.*, t.team_name, g.game_image, g.game_size
					FROM ' . NEWS . ' n
						LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
						LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
						LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . MATCH_DETAILS . ' md ON m.match_id = md.match_id
						LEFT JOIN ' . NEWS_CATEGORY . ' nc ON n.news_category = nc.news_category_id
					WHERE n.news_time_public < ' . time() . ' AND n.news_intern = 0 AND news_public = 1
				ORDER BY n.news_time_public DESC';
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
		}
		$news_data = $db->sql_fetchrowset($result);
//		$news_data = _cached($sql, 'news_list_guest');
	}
	
//	_debug_post($news_data);
	
	if ( !$news_data )
	{
		$template->assign_block_vars('no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($news_data)); $i++)
		{
			$class = ($i % 2) ? 'row1' : 'row2';
			$news_date = create_date($userdata['user_dateformat'], $news_data[$i]['news_time_public'], $userdata['user_timezone']); 
			
			if ( $config['time_today'] < $news_data[$i]['news_time_public'])
			{ 
				$news_date = sprintf($lang['today_at'], create_date($config['default_timeformat'], $news_data[$i]['news_time_public'], $userdata['user_timezone'])); 
			}
			else if ( $config['time_yesterday'] < $news_data[$i]['news_time_public'])
			{ 
				$news_date = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $news_data[$i]['news_time_public'], $userdata['user_timezone'])); 
			}
			
			$template->assign_block_vars('news_row', array(
				'CLASS' 			=> $class,
				'NEWS_ID'			=> $news_data[$i]['news_id'],
				'NEWS_TITLE'		=> $news_data[$i]['news_title'],
				'NEWS_TEXT'			=> html_entity_decode($news_data[$i]['news_text'], ENT_QUOTES),
				'NEWS_COMMENTS'		=> $news_data[$i]['news_comment'],
				'NEWS_AUTHOR'		=> '<a href="' . append_sid("profile.php?mode=view&amp;" . POST_USERS_URL . "=" . $news_data[$i]['user_id']) . '" style="color:' . $news_data[$i]['user_color'] . '"><b>' . $news_data[$i]['username'] . '</b></a>',
				'NEWS_PUBLIC_TIME'	=> $news_date,
				
				
				'NEWSCAT_TITLE'		=> ( $news_data[$i]['news_category_title'] ) ? $news_data[$i]['news_category_title'] : '',
				'NEWSCAT_IMAGE'		=> ( $news_data[$i]['news_category_image'] ) ? $root_path . $settings['path_news_category'] . '/' . $news_data[$i]['news_category_image'] : '',
				
				'U_NEWS'			=> append_sid("news.php?mode=view&amp;" . POST_NEWS_URL . "=" . $news_data[$i]['news_id']),
				
			));
			
			if ( unserialize($news_data[$i]['news_url']) )
			{
				$links		= array();
				$news_url	= unserialize($news_data[$i]['news_url']);
				$news_link	= unserialize($news_data[$i]['news_link']);
				
				foreach ( $news_url as $u_key => $u_value )
				{
					foreach ( $news_link as $l_key => $l_value )
					{
						if ( $u_key == $l_key )
						{
							$links[] .= '<a href="' . $u_value . '" target="_new">' . $l_value . '</a>';
						}
					}
				}
				
				$links = implode(', ', $links);
					
	//			_debug_post($links);
				
				$template->assign_block_vars('news_row.links', array(
					'L_LINK'	=> ( count($news_url) > 1 ) ? $lang['news_info_urls'] : $lang['news_info_url'],
					'NEWS_LINK'	=> $links,
				));
			}
			
			if ( $news_data[$i]['match_id'] )
			{
				$template->assign_block_vars('news_row.match', array(
					
				));
			}
		}
		
		$current_page = ( !count($news_data) ) ? 1 : ceil( count($news_data) / $settings['site_comment_per_page'] );
			
		$template->assign_vars(array(
			'PAGINATION' => generate_pagination("news.php?" . POST_NEWS_URL . "=" . $news_data, count($news_data), $settings['site_comment_per_page'], $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_comment_per_page'] ) + 1 ), $current_page ), 
		
			'L_GOTO_PAGE' => $lang['Goto_page'])
		);
	}
	
}
else if ( $mode == 'view' && isset($HTTP_GET_VARS[POST_NEWS_URL]))
{
	session_start();
	
	if ($userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN)
	{
		$sql = 'SELECT n.*, u.username, u.user_color
					FROM ' . NEWS . ' n
						LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
						LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
						LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . MATCH_DETAILS . ' md ON m.match_id = md.match_id
					WHERE n.news_time_public < ' . time() . ' AND n.news_id = ' . $news_id . ' AND news_public = 1
				ORDER BY n.news_time_public DESC';
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
		}
		$news_info = $db->sql_fetchrow($result);
//		$news_info = _cached($sql, 'news_view_' . $news_id . '_member', 1);
	}
	else
	{
		$sql = 'SELECT n.*, u.username, u.user_color, m.*, md.*, t.team_name, g.game_image, g.game_size
					FROM ' . NEWS . ' n
						LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
						LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
						LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . MATCH_DETAILS . ' md ON m.match_id = md.match_id
					WHERE n.news_time_public < ' . time() . ' AND n.news_intern = 0 AND n.news_id = ' . $news_id . ' AND news_public = 1
				ORDER BY n.news_time_public DESC';
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
		}
		$news_info = $db->sql_fetchrow($result);
//		$news_info = _cached($sql, 'news_view_' . $news_id . '_guest', 1);
	}
	
	if (!$news_info)
	{
		message_die(GENERAL_ERROR, 'Falsche ID ?');
	}
	
	$page_title = sprintf($lang['news_head_info'], $news_info['news_title']);
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'news_view_body.tpl'));
	
	//	Kommentarfunktion
	//	Nur wenn die Generelle Funktion aktiviert ist und für das Match selber
	if ($settings['comments_news'] && $news_info['news_comments'])
	{
		$template->assign_block_vars('news_comments', array());
		
		$sql = 'SELECT mc.*, u.username, u.user_email
					FROM ' . NEWS_COMMENTS . ' mc
						LEFT JOIN ' . USERS . ' u ON mc.poster_id = u.user_id
					WHERE news_id = ' . $news_id . ' ORDER BY time_create DESC';
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
		}
		$comment_entry = $db->sql_fetchrowset($result);
//		$comment_entry = _cached($sql, 'news_details_' . $news_id . '_comments');

		if ( $userdata['session_logged_in'] )
		{
			$sql = 'SELECT read_time
						FROM ' . NEWS_COMMENTS_READ . '
						WHERE user_id = ' . $userdata['user_id'] . ' AND news_id = ' . $news_id;
			$result = $db->sql_query($sql);
			$unread = $db->sql_fetchrow($result);

			if ( $db->sql_numrows($result) )
			{
				$unreads = false;
				
				$sql = 'UPDATE ' . NEWS_COMMENTS_READ . '
							SET read_time = ' . time() . '
						WHERE news_id = ' . $news_id . ' AND user_id = ' . $userdata['user_id'];
				$result = $db->sql_query($sql);
			}
			else
			{
				$unreads = true;
				
				$sql = 'INSERT INTO ' . NEWS_COMMENTS_READ . ' (news_id, user_id, read_time)
					VALUES (' . $news_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
				$result = $db->sql_query($sql);
			}
		}
		
		if (!$comment_entry)
		{
			$template->assign_block_vars('news_comments.no_entry', array());
			$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			$last_entry = array('poster_ip' => '', 'time_create' => '');
		}
		else
		{
			for($i = $start; $i < min($settings['site_comment_per_page'] + $start, count($comment_entry)); $i++)
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
	
				$template->assign_block_vars('news_comments.comments', array(
					'CLASS' 		=> $class,
					'ID' 			=> $comment_entry[$i]['news_comments_id'],
					'L_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_nick'] : $comment_entry[$i]['username'],
	//				'U_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_email'] : $comment_entry[$i]['user_email'],	Profil-Link und Mail schreiben an Gast
					'MESSAGE'		=> $comment,
					'DATE'			=> create_date($userdata['user_dateformat'], $comment_entry[$i]['time_create'], $userdata['user_timezone']),
					
					'ICON'			=> $icon,
	
					'U_EDIT'		=> append_sid("news.php?mode=edit&amp;" . POST_NEWS_URL . "=" . $comment_entry[$i]['news_id']),
					'U_DELETE'		=> append_sid("news.php?mode=delete&amp;" . POST_NEWS_URL . "=" . $comment_entry[$i]['news_id'])
				));
			}
		
			$current_page = ( !count($comment_entry) ) ? 1 : ceil( count($comment_entry) / $settings['site_comment_per_page'] );
			
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination("news.php?mode=view&amp;" . POST_NEWS_URL . "=" . $news_id, count($comment_entry), $settings['site_comment_per_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_comment_per_page'] ) + 1 ), $current_page ), 
			
				'L_GOTO_PAGE' => $lang['Goto_page'])
			);
			
			//	Letzter Kommentareintrag
			//	sort (Sortiert ein Array)
			//	array_pop (Liefert das letzte Element eines Arrays)
			sort($comment_entry);
			$last_entry = array_pop($comment_entry);
		
		}
		
		//	Wer darf Kommentare schreiben?!?
//		if ($settings['comments_news_guest'] && !$userdata['session_logged_in'] && $last_entry['poster_ip'] != $userdata['session_ip'])
		if ($settings['comments_news_guest'] && !$userdata['session_logged_in'])
		{
			$template->assign_block_vars('news_comments.news_comments_guest', array());
		}
		
		//	Eingeloggte Benutzer können immer kommentieren
		if ($userdata['session_logged_in'])
		{
			$template->assign_block_vars('news_comments.news_comments_member', array());
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
				//	Test: hier werden/sollen Kommentare als gelesen markiert werden
				$sql = 'SELECT * FROM ' . NEWS_COMMENTS_READ . ' WHERE news_id = ' . $news_id . ' AND user_id = ' . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( $db->sql_numrows($result) )
				{
					$sql = 'UPDATE ' . NEWS_COMMENTS_READ . '
								SET read_time = ' . time() . '
							WHERE news_id = ' . $news_id . ' AND user_id = ' . $userdata['user_id'];					
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{				
					$sql = 'INSERT INTO ' . NEWS_COMMENTS_READ . ' (news_id, user_id, read_time)
						VALUES (' . $news_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				//	Keine Fehler?
				//	Cache löschung und eintragung des Kommentars
				
				$oCache -> deleteCache('news_details_' . $news_id . '_comments');
				
				_comment_message('add', 'news', $news_id, $userdata['user_id'], $user_ip, $HTTP_POST_VARS['comment'], $poster_nick, $poster_mail, '');
				
				$message = $lang['add_comment'] . '<br /><br />' . sprintf($lang['click_return_news'],  '<a href="' . append_sid("news.php?mode=view&amp;" . POST_NEWS_URL . "=" . $news_id) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
	}
	
	$template->assign_vars(array(
		
		'NEWS_TITLE'	=> $page_title,
		'NEWS_TEXT'		=> html_entity_decode($news_info['news_text'], ENT_QUOTES),
								 
	));

}
else
{
	redirect(append_sid('news.php', true));
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>