<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_MATCH);
init_userprefs($userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ( $start < 0 ) ? 0 : $start;

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

if ( $mode == '' )
{
	$page_title = $lang['news'];
	include($root_path . 'includes/page_header.php');
	
	$template->set_filenames(array('body' => 'body_news.tpl'));
	$template->assign_block_vars('show', array());
	
	//
	//	List News
	//
	if ( $userdata['user_level'] >= TRIAL )
	{
		$sql = 'SELECT n.*, nc.cat_name, nc.cat_image, u.user_name, u.user_color, m.*, t.team_name, g.game_image, g.game_size
					FROM ' . NEWS . ' n
						LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
						LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
						LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . NEWSCAT . ' nc ON n.news_cat = nc.cat_id
					WHERE n.news_time_public < ' . time() . ' AND news_public = 1
				ORDER BY n.news_time_public DESC, n.news_id DESC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$news_data = $db->sql_fetchrowset($result);
//		$news_data = _cached($sql, 'news_list_member');
	}
	else
	{
		$sql = 'SELECT n.*, nc.cat_name, nc.cat_image, u.user_name, u.user_color, m.*, t.team_name, g.game_image, g.game_size
					FROM ' . NEWS . ' n
						LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
						LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
						LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . NEWSCAT . ' nc ON n.news_cat = nc.cat_id
					WHERE n.news_time_public < ' . time() . ' AND n.news_intern = 0 AND news_public = 1
				ORDER BY n.news_time_public DESC, n.news_id DESC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$news_data = $db->sql_fetchrowset($result);
//		$news_data = _cached($sql, 'news_list_guest');
	}
	
	if ( !$news_data )
	{
		$template->assign_block_vars('show.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['news_limit'] + $start, count($news_data)); $i++ )
		{
			$news_date = create_date($userdata['user_dateformat'], $news_data[$i]['news_time_public'], $userdata['user_timezone']); 
			
			if ( $config['time_today'] < $news_data[$i]['news_time_public'])
			{ 
				$news_date = sprintf($lang['today_at'], create_date($config['default_timeformat'], $news_data[$i]['news_time_public'], $userdata['user_timezone'])); 
			}
			else if ( $config['time_yesterday'] < $news_data[$i]['news_time_public'])
			{ 
				$news_date = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $news_data[$i]['news_time_public'], $userdata['user_timezone'])); 
			}
			
			$template->assign_block_vars('show.news_row', array(
				'NEWS_ID'			=> $news_data[$i]['news_id'],
				'NEWS_TITLE'		=> $news_data[$i]['news_title'],
				'NEWS_TEXT'			=> html_entity_decode($news_data[$i]['news_text'], ENT_QUOTES),
				'NEWS_COMMENTS'		=> $news_data[$i]['news_comment'],
				'NEWS_AUTHOR'		=> '<a href="' . check_sid('profile.php?mode=view&amp;' . POST_USER_URL . '=' . $news_data[$i]['user_id']) . '" style="color:' . $news_data[$i]['user_color'] . '"><b>' . $news_data[$i]['user_name'] . '</b></a>',
				'NEWS_PUBLIC_TIME'	=> $news_date,
				
				
				'NEWSCAT_TITLE'		=> ( $news_data[$i]['cat_name'] ) ? $news_data[$i]['cat_name'] : '',
				'NEWSCAT_IMAGE'		=> ( $news_data[$i]['cat_image'] ) ? $root_path . $settings['path_newscat'] . '/' . $news_data[$i]['cat_image'] : '',
				
				'U_NEWS'			=> check_sid('news.php?mode=view&amp;' . POST_NEWS_URL . '=' . $news_data[$i]['news_id']),
				
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
				
				$template->assign_block_vars('show.news_row.links', array(
					'L_LINK'	=> ( count($news_url) > 1 ) ? $lang['news_info_urls'] : $lang['news_info_url'],
					'NEWS_LINK'	=> $links,
				));
			}
			
			if ( $news_data[$i]['match_id'] )
			{
				$template->assign_block_vars('show.news_row.match', array(
					
				));
			}
		}
		
		if ( $settings['news_browse'] )
		{
			$current_page = ( !count($news_data) ) ? 1 : ceil( count($news_data) / $settings['news_limit'] );
				
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination('news.php', count($news_data), $settings['news_limit'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['news_limit'] ) + 1 ), $current_page ), 
			
				'L_GOTO_PAGE' => $lang['Goto_page'],
			));
		}
	}
	
}
else if ( $mode == 'view' && isset($HTTP_GET_VARS[POST_NEWS_URL]))
{
	session_start();
	$template->set_filenames(array('body' => 'body_news.tpl'));
	$template->assign_block_vars('details', array());
	
	if ( $userdata['user_level'] >= TRIAL )
	{
		$sql = 'SELECT n.*, u.user_name, u.user_color
					FROM ' . NEWS . ' n
						LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
						LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
						LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
					WHERE n.news_time_public < ' . time() . ' AND n.news_id = ' . $news_id . ' AND news_public = 1
				ORDER BY n.news_time_public DESC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$news_details = $db->sql_fetchrow($result);
//		$news_details = _cached($sql, 'news_details_' . $news_id . '_member', 1);
	}
	else
	{
		$sql = 'SELECT n.*, u.user_name, u.user_color, m.*, t.team_name, g.game_image, g.game_size
					FROM ' . NEWS . ' n
						LEFT JOIN ' . USERS . ' u ON n.user_id = u.user_id
						LEFT JOIN ' . MATCH . ' m ON n.match_id = m.match_id
						LEFT JOIN ' . TEAMS . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id

					WHERE n.news_time_public < ' . time() . ' AND n.news_intern = 0 AND n.news_id = ' . $news_id . ' AND news_public = 1
				ORDER BY n.news_time_public DESC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$news_details = $db->sql_fetchrow($result);
//		$news_details = _cached($sql, 'news_details_' . $news_id . '_guest', 1);
	}
	
	if ( !$news_details )
	{
		message(GENERAL_ERROR, 'Falsche ID ?');
	}
	
	$page_title = sprintf($lang['news_head_info'], $news_details['news_title']);
	include($root_path . 'includes/page_header.php');
	
	//	Kommentarfunktion
	//	Nur wenn die Generelle Funktion aktiviert ist und für das Match selber
	if ( $settings['comments_news'] && $news_details['news_comments'] )
	{
		$template->assign_block_vars('details.news_comments', array());
		
		$sql = 'SELECT mc.*, u.user_id, u.user_name, u.user_color, u.user_email
					FROM ' . NEWS_COMMENTS . ' mc
						LEFT JOIN ' . USERS . ' u ON mc.poster_id = u.user_id
					WHERE news_id = ' . $news_id . ' ORDER BY time_create DESC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$comment_entry = $db->sql_fetchrowset($result);
//		$comment_entry = _cached($sql, 'news_details_' . $news_id . '_comments');

		if ( $userdata['session_logged_in'] )
		{
			$sql = 'SELECT read_time
						FROM ' . NEWS_COMMENTS_READ . '
						WHERE user_id = ' . $userdata['user_id'] . ' AND news_id = ' . $news_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$unread = $db->sql_fetchrow($result);

			if ( $db->sql_numrows($result) )
			{
				$unreads = false;
				
				$sql = 'UPDATE ' . NEWS_COMMENTS_READ . '
							SET read_time = ' . time() . '
						WHERE news_id = ' . $news_id . ' AND user_id = ' . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
			else
			{
				$unreads = true;
				
				$sql = 'INSERT INTO ' . NEWS_COMMENTS_READ . ' (news_id, user_id, read_time)
					VALUES (' . $news_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
		}
		
		if (!$comment_entry)
		{
			$template->assign_block_vars('details.news_comments.no_entry', array());
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
				
				$user_id = $comment_entry[$i]['user_id'];
	
				$template->assign_block_vars('details.news_comments.comments', array(
					'CLASS' 		=> $class,
					'ID' 			=> $comment_entry[$i]['news_comments_id'],
					'COLOR'			=> ( $comment_entry[$i]['user_color'] ) ? 'style="color:' . $comment_entry[$i]['user_color'] . '"' : '',
					'L_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_nick'] : $comment_entry[$i]['user_name'],
					'U_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_email'] : check_sid("profile.php?mode=view&u=$user_id"),
					'MESSAGE'		=> $comment,
					'DATE'			=> create_date($userdata['user_dateformat'], $comment_entry[$i]['time_create'], $userdata['user_timezone']),
					
					'ICON'			=> $icon,
	
					'U_EDIT'		=> check_sid('news.php?mode=edit&amp;' . POST_NEWS_URL . '=' . $comment_entry[$i]['news_id']),
					'U_DELETE'		=> check_sid('news.php?mode=delete&amp;' . POST_NEWS_URL . '=' . $comment_entry[$i]['news_id'])
				));
			}
		
			$current_page = ( !count($comment_entry) ) ? 1 : ceil( count($comment_entry) / $settings['site_comment_per_page'] );
			
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination('news.php?mode=view&amp;' . POST_NEWS_URL . '=' . $news_id, count($comment_entry), $settings['site_comment_per_page'], $start),
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
		if ($settings['comments_news_guest'] && !$userdata['session_logged_in'])
		{
			$template->assign_block_vars('details.news_comments.news_comments_guest', array());
		}
		
		//	Eingeloggte Benutzer können immer kommentieren
		if ($userdata['session_logged_in'])
		{
			$template->assign_block_vars('details.news_comments.news_comments_member', array());
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
				$sql = 'SELECT * FROM ' . NEWS_COMMENTS_READ . ' WHERE news_id = ' . $news_id . ' AND user_id = ' . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( $db->sql_numrows($result) )
				{
					$sql = 'UPDATE ' . NEWS_COMMENTS_READ . '
								SET read_time = ' . time() . '
							WHERE news_id = ' . $news_id . ' AND user_id = ' . $userdata['user_id'];					
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{				
					$sql = 'INSERT INTO ' . NEWS_COMMENTS_READ . ' (news_id, user_id, read_time)
								VALUES (' . $news_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				//	Keine Fehler?
				//	Cache löschung und eintragung des Kommentars
				
				$oCache -> deleteCache('news_details_' . $news_id . '_comments');
				
				_comment_message('add', 'news', $news_id, $userdata['user_id'], $user_ip, $HTTP_POST_VARS['comment'], $poster_nick, $poster_mail, '');
				
				$message = $lang['add_comment'] . sprintf($lang['click_return_news'],  '<a href="' . check_sid('news.php?mode=view&amp;' . POST_NEWS_URL . '=' . $news_id) . '">', '</a>');
				message(GENERAL_MESSAGE, $message);
			}
		}
	}
	
	$template->assign_vars(array(
		
		'NEWS_TITLE'	=> $page_title,
		'NEWS_TEXT'		=> html_entity_decode($news_details['news_text'], ENT_QUOTES),
								 
	));

}
else
{
	redirect(check_sid('news.php', true));
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>