<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_BUGTRACKER);
init_userprefs($userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_POST_VARS[POST_BUGTRACKER_URL]) || isset($HTTP_GET_VARS[POST_BUGTRACKER_URL]) )
{
	$bugtracker_id = ( isset($HTTP_POST_VARS[POST_BUGTRACKER_URL]) ) ? intval($HTTP_POST_VARS[POST_BUGTRACKER_URL]) : intval($HTTP_GET_VARS[POST_BUGTRACKER_URL]);
}
else
{
	$bugtracker_id = 0;
}

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	if ( isset($HTTP_GET_VARS['add']) || isset($HTTP_POST_VARS['add']) )
	{
		$mode = 'add';
	}
	else
	{
		$mode = 'list';
	}
}

if ( isset($HTTP_GET_VARS['sort']) || isset($HTTP_POST_VARS['sort']) )
{
	$sort = ( isset($HTTP_POST_VARS['sort']) ) ? htmlspecialchars($HTTP_POST_VARS['sort']) : htmlspecialchars($HTTP_GET_VARS['sort']);
}
else
{
	$sort = 'bt_status_all';
}

if ( isset($HTTP_POST_VARS['order']) )
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if( isset($HTTP_GET_VARS['order']) )
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'DESC';
}

$s_sort = '<select class="postselect" name="sort" onchange="document.getElementById(\'bt_sort\').submit()">';
foreach ( $lang['bt_status'] as $key => $value )
{
	$selected = ( $sort == $key ) ? ' selected="selected"' : '';
	$s_sort .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
}
$s_sort .= '</select>';

$s_sort_order = '<select class="postselect" name="order">';
if ( $sort_order == 'ASC' )
{
	$s_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$s_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$s_sort_order .= '</select>';

foreach ( $lang['bt_status'] as $key => $value )
{
	if ( $key == $sort )
	{
		$order_by = ( $key == 'bt_status_all' ) ? " ORDER BY bt.bugtracker_id DESC" : " WHERE bugtracker_status = '$key' ORDER BY bt.bugtracker_id DESC";
//		$order_by = ( $key == 'bt_status_all' ) ? " ORDER BY `bt`.`bugtracker_id` $sort_order" : " WHERE bugtracker_status = '$key' ORDER BY `bt`.`bugtracker_id` $sort_order";
	}
}

$page_title = $lang['bugtracker'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'body_bugtracker.tpl'));

if ( $mode == 'list' )
{
	$template->assign_block_vars('list', array());
	
	$sql = 'SELECT	u.user_id as user_id1, u.username as username1, u.user_color as user_color1,
					u2.user_id as user_id2, u2.username as username2, u2.user_color as user_color2,
					bt.*
				FROM ' . BUGTRACKER . ' bt
					LEFT JOIN ' . USERS . ' u ON u.user_id = bt.bugtracker_creator
					LEFT JOIN ' . USERS . ' u2 ON u2.user_id = bt.bugtracker_worker
				' . $order_by;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
	}
	$bugtracker_data = $db->sql_fetchrowset($result);
//	$bugtracker_data = _cached($sql, 'bugtracker_list');

	if ( !$bugtracker_data )
	{
		$template->assign_block_vars('list.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($bugtracker_data)); $i++)
		{
			$class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$time	= create_date($userdata['user_dateformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone']);
			if ( $config['time_today'] < $bugtracker_data[$i]['bugtracker_create'])
			{ 
				$time = sprintf($lang['today_at'], create_date($config['default_timeformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone'])); 
			}
			else if ( $config['time_yesterday'] < $bugtracker_data[$i]['bugtracker_create'])
			{ 
				$time = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone'])); 
			}
			$user	= '<a href="' . append_sid("profile.php?mode=view&amp;" . POST_USERS_URL . "=" . $bugtracker_data[$i]['user_id1']) . '" style="color:' . $bugtracker_data[$i]['user_color1'] . '">' . $bugtracker_data[$i]['username1'] . '</a>';
			$user2	= '<a href="' . append_sid("profile.php?mode=view&amp;" . POST_USERS_URL . "=" . $bugtracker_data[$i]['user_id2']) . '" style="color:' . $bugtracker_data[$i]['user_color1'] . '">' . $bugtracker_data[$i]['username2'] . '</a>';
			
			foreach ( $lang['bt_error'] as $key_t => $value_t )
			{
				if ( $key_t == $bugtracker_data[$i]['bugtracker_type'] )
				{
					$bt_type = $value_t;
				}
			}
			
			foreach ( $lang['bt_status'] as $key_s => $value_s )
			{
				if ( $key_s == $bugtracker_data[$i]['bugtracker_status'] )
				{
					$bt_status = $value_s;
				}
			}
			
			$template->assign_block_vars('list.bt_row', array(
				'CLASS' 		=> $class,
				'CLASS_STATUS'	=> $bugtracker_data[$i]['bugtracker_status'],
				
				'BT_ID'			=> $bugtracker_data[$i]['bugtracker_id'],
				'BT_TITLE'		=> $bugtracker_data[$i]['bugtracker_title'],
				'BT_DESC'		=> $bugtracker_data[$i]['bugtracker_description'],
				'BT_MESSAGE'	=> $bugtracker_data[$i]['bugtracker_message'],
				'BT_CREATE'		=> sprintf($lang['bt_create_by'], $user, $time),
				
				'BT_WORKER'		=> ( $bugtracker_data[$i]['bugtracker_worker'] ) ? $user2 : $lang['bt_unassigned'],
//				'BT_EDIT'		=> '<a href="' . append_sid("bugtracker.php?mode=edit&amp;" . POST_BUGTRACKER_URL . "=" . $bugtracker_data[$i]['bugtracker_id']) . '">edit ' . $bugtracker_data[$i]['username2'] . '</a>',
				
				'BT_TYPE'		=> $bt_type,
				'BT_STATUS'		=> $bt_status,
				
				'U_DETAILS'		=> append_sid("bugtracker.php?mode=view&amp;" . POST_BUGTRACKER_URL . "=" . $bugtracker_data[$i]['bugtracker_id']),
				
			));
		}
	}
	
	$current_page = ( !count($bugtracker_data) ) ? 1 : ceil( count($bugtracker_data) / $settings['site_entry_per_page'] );
	
	$template->assign_vars(array(
		'L_GOTO_PAGE'			=> $lang['Goto_page'],
		'L_BUGTRACKER'			=> $lang['bugtracker'],
		'L_ASSIGNED'			=> $lang['bt_assigned'],
		'L_STATUS_TYPE'			=> $lang['bt_status_type'],
		
		'PAGINATION'			=> generate_pagination("bugtracker.php?sort=$sort&amp;order=$sort_order", count($bugtracker_data), $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER'			=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
		
		'S_BUGTRACKER_ORDER'	=> $s_sort_order,
		'S_BUGTRACKER_SORT'		=> $s_sort,
		'S_BUGTRACKER_ACTION'	=> append_sid("bugtracker.php"),
	));
}
else if ( ( $mode == 'add' || ( $mode == 'edit' && $bugtracker_id ) ) && $userdata['session_logged_in'] )
{
	include($root_path . 'includes/functions_post.php');
	include($root_path . 'includes/functions_bugtracker.php');
	
	$template->assign_block_vars('entry', array());
	
	$bt_type = '';
	$s_hidden_field = '<input type="hidden" name="mode" value="add" />';
	
	if ( $mode == 'edit' )
	{
		$sql = 'SELECT *
					FROM ' . BUGTRACKER . '
					WHERE bugtracker_id = ' . $bugtracker_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
		}
		$bugtracker_data = $db->sql_fetchrow($result);
		
		$template->assign_vars(array(
			'BT_TITLE'		=> $bugtracker_data['bugtracker_title'],
			'BT_DESC'		=> $bugtracker_data['bugtracker_description'],
			'BT_PHP'		=> $bugtracker_data['bugtracker_php'],
			'BT_SQL'		=> $bugtracker_data['bugtracker_sql'],
			'BT_MESSAGE'	=> $bugtracker_data['bugtracker_message'],
			
		));
		
		$bt_type = $bugtracker_data['bugtracker_type'];
		
		$s_hidden_field = '<input type="hidden" name="mode" value="edit" />';
		$s_hidden_field .= '<input type="hidden" name="' . POST_BUGTRACKER_URL . '" value="' . $bugtracker_id . '" />';
	}
	
	if ( isset($HTTP_POST_VARS['submit']) )
	{
		_debug_poste($_POST);
		
		$error = '';
		$error_msg = '';
		
		$bt_title	= ( isset($HTTP_POST_VARS['bt_title']) )	? trim($HTTP_POST_VARS['bt_title']) : '';
		$bt_desc	= ( isset($HTTP_POST_VARS['bt_desc']) )		? trim($HTTP_POST_VARS['bt_desc']) : '';
		$bt_type	= ( isset($HTTP_POST_VARS['bt_type']) )		? trim($HTTP_POST_VARS['bt_type']) : '';
		$bt_php		= ( isset($HTTP_POST_VARS['bt_php']) )		? trim($HTTP_POST_VARS['bt_php']) : '';
		$bt_sql		= ( isset($HTTP_POST_VARS['bt_sql']) )		? trim($HTTP_POST_VARS['bt_sql']) : '';
		$bt_message	= ( isset($HTTP_POST_VARS['bt_message']) )	? trim($HTTP_POST_VARS['bt_message']) : '';
		
		$template->assign_vars(array(
			'BT_TITLE'		=> $bt_title,
			'BT_DESC'		=> $bt_desc,
			'BT_PHP'		=> $bt_php,
			'BT_SQL'		=> $bt_sql,
			'BT_MESSAGE'	=> $bt_message,
		));
		
		if ( empty($bt_title) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['msg_select_title'];
		}
		
		if ( empty($bt_desc) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['msg_select_desc'];
		}
			
		if ( empty($bt_type) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['msg_select_type'];
		}
		
		if ( empty($bt_message) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['msg_select_message'];
		}

		if ( $error )
		{
			$template->set_filenames(array('reg_header' => 'error_body.tpl'));
			$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
			$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
		}

		if ( !$error )
		{
		//	$oCache -> deleteCache('bugtracker_list');
			if ( $mode == 'add' )
			{
				bt_add($userdata['user_id'], $bt_title, $bt_desc, $bt_type, $bt_php, $bt_sql, $bt_message);
			}
			else
			{
				bt_edit($bugtracker_id, $bt_title, $bt_desc, $bt_type, $bt_php, $bt_sql, $bt_message);
			}
			
			$message = ( $mode == 'add' ) ? $lang['bt_add'] : $lang['bt_edit'];
			$message .= '<br /><br />' . sprintf($lang['click_return_bugtracker'],  '<a href="' . append_sid("bugtracker.php") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	
	$template->assign_vars(array(
		'L_TITLE'				=> $lang['bt_title'],
		'L_DESC'				=> $lang['bt_desc'],
		'L_TYPE'				=> $lang['bt_type'],
		'L_PHP'					=> $lang['bt_php'],
		'L_SQL'					=> $lang['bt_sql'],
		'L_MESSAGE'				=> $lang['bt_message'],
		
		'L_SUBMIT'				=> $lang['Submit'],
		
		'S_TYPE'				=> bt_type($bt_type),
		'S_HIDDEN_FIELD'		=> $s_hidden_field,
		'S_BUGTRACKER_ACTION'	=> append_sid("bugtracker.php"),
	));
	
	/*
	//	Kommentarfunktion
	//	Nur wenn die Generelle Funktion aktiviert ist und für das Match selber
	if ($settings['comments_matches'] && $row_details['match_comments'])
	{
		$template->assign_block_vars('match_comments', array());
		
		$sql = 'SELECT mc.*, u.username, u.user_email
					FROM ' . MATCH_COMMENTS . ' mc
						LEFT JOIN ' . USERS . ' u ON mc.poster_id = u.user_id
					WHERE match_id = ' . $match_id . ' ORDER BY time_create DESC';
		$comment_entry = _cached($sql, 'match_details_' . $match_id . '_comments');
		
		if (!$comment_entry)
		{
			$template->assign_block_vars('match_comments.no_entry', array());
			$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			$last_entry = array('poster_ip' => '', 'time_create' => '');
		}
		else
		{
			if ( $userdata['session_logged_in'] )
			{
				//	SQL Abfrage verkleinert, voher für jeden Beitrag eine Zeit, die aber immer gleich war
				$sql = 'SELECT read_time
							FROM ' . MATCH_COMMENTS_READ . '
							WHERE user_id = ' . $userdata['user_id'] . ' AND match_id = ' . $match_id;
				$result = $db->sql_query($sql);
				$unread = $db->sql_fetchrow($result);
				
				if ( $db->sql_numrows($result) )
				{
					$unreads = false;
					
					$sql = 'UPDATE ' . MATCH_COMMENTS_READ . '
								SET read_time = ' . time() . '
							WHERE match_id = ' . $match_id . ' AND user_id = ' . $userdata['user_id'];
					$result = $db->sql_query($sql);
				}
				else
				{
					$unreads = true;
					
					$sql = 'INSERT INTO ' . MATCH_COMMENTS_READ . ' (match_id, user_id, read_time)
						VALUES (' . $match_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
					$result = $db->sql_query($sql);
				}
			}
			
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
	
				$template->assign_block_vars('match_comments.comments', array(
					'CLASS' 		=> $class,
					'ID' 			=> $comment_entry[$i]['match_comments_id'],
					'L_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_nick'] : $comment_entry[$i]['username'],
	//				'U_USERNAME'	=> ($comment_entry[$i]['poster_nick']) ? $comment_entry[$i]['poster_email'] : $comment_entry[$i]['user_email'],	Profil-Link und Mail schreiben an Gast
					'MESSAGE'		=> $comment,
					'DATE'			=> create_date($userdata['user_dateformat'], $comment_entry[$i]['time_create'], $userdata['user_timezone']),
					
					'ICON'			=> $icon,
	
					'U_EDIT'		=> append_sid("match.php?mode=edit&amp;" . POST_MATCH_URL . "=" . $comment_entry[$i]['match_id']),
					'U_DELETE'		=> append_sid("match.php?mode=delete&amp;" . POST_MATCH_URL . "=" . $comment_entry[$i]['match_id'])
				));
			}
		
			$current_page = ( !count($comment_entry) ) ? 1 : ceil( count($comment_entry) / $settings['site_comment_per_page'] );
			
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=" . $match_id, count($comment_entry), $settings['site_comment_per_page'], $start),
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
//		if ($settings['comments_matches_guest'] && !$userdata['session_logged_in'] && $last_entry['poster_ip'] != $userdata['session_ip'])
		if ($settings['comments_matches_guest'] && !$userdata['session_logged_in'])
		{
			$template->assign_block_vars('match_comments.match_comments_guest', array());
		}
		
		//	Eingeloggte Benutzer können immer kommentieren
		if ($userdata['session_logged_in'])
		{
			$template->assign_block_vars('match_comments.match_comments_member', array());
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
				$sql = 'SELECT * FROM ' . MATCH_COMMENTS_READ . ' WHERE match_id = ' . $match_id . ' AND user_id = ' . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ( $db->sql_numrows($result) )
				{
					$sql = 'UPDATE ' . MATCH_COMMENTS_READ . '
								SET read_time = ' . time() . '
							WHERE match_id = ' . $match_id . ' AND user_id = ' . $userdata['user_id'];					
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{				
					$sql = 'INSERT INTO ' . MATCH_COMMENTS_READ . ' (match_id, user_id, read_time)
						VALUES (' . $match_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				//	Keine Fehler?
				//	Cache löschung und eintragung des Kommentars
				$oCache -> deleteCache('match_details_' . $match_id . '_comments');
				
				_comment_message('add', 'match', $match_id, $userdata['user_id'], $user_ip, $HTTP_POST_VARS['comment'], $poster_nick, $poster_mail, '');
				
				$message = $lang['add_comment'] . '<br /><br />' . sprintf($lang['click_return_match'],  '<a href="' . append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=" . $match_id) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
	}
	*/
	
}
else
{
	redirect(append_sid('bugtracker.php', true));
}




$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>