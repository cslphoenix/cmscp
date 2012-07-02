<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_EVENT);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_EVENT;
$url	= POST_EVENT;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, INT);	
$mode	= request('mode', TXT);
$smode	= request('smode', 1);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_event.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'error_body.tpl',
));

#add_lang('lang_event');

$sql = "SELECT * FROM " . EVENT . " ORDER BY event_date DESC, event_id DESC";
$tmp = _cached($sql, 'data_event');

if ( $data && $tmp )
{
	$template->assign_block_vars('view', array());
	
	foreach ( $tmp as $row )
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
	
	main_header($page_title);
/*	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_event'] )
	{
		$template->assign_block_vars('view._update', array(
			'UPDATE'		=> "<a href=\"" . check_sid("admin/admin_event.php?mode=_update&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['event_update'] . "</a>",
			'UPDATE_DETAIL'	=> "<a href=\"" . check_sid("admin/admin_event.php?mode=_detail&amp;$url=$data&amp;sid=" . $userdata['session_id']) . "\">" . $lang['event_detail'] . "</a>",
		));
	}
*/
	
	if ( $settings['comments']['event'] && $view['event_comments'] )
	{
		$template->assign_block_vars('view._comment', array());
		
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
		
		if ( !$comments )
		{
			$template->assign_block_vars('view._comment._empty', array());
			
			$last_entry = array('poster_ip' => '', 'time_create' => '');
		}
		else
		{
			$cnt = count($comments);
			
			$sql = "SELECT read_time FROM " . COMMENT_READ . " WHERE user_id = $user AND type_id = $data AND type = " . READ_EVENT;
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
				$link = $comments[$i]['poster_nick'] ? $userdata['session_logged_in'] ? 'mailto:' . $comments[$i]['poster_email'] : $comments[$i]['poster_nick'] : 'profile.php?mode=view&amp;' . POST_USER . '=' . $comments[$i]['poster_id'];
				
				$s_option = '';
				
				$template->assign_block_vars('view._comment._row', array(
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
			
			//	Letzter Kommentareintrag
			//	sort (Sortiert ein Array)
			//	array_pop (Liefert das letzte Element eines Arrays)
			sort($comments);
			$last_entry = array_pop($comments);
		}
		
		/* Kommentare für Gäste */
		if ( $settings['comments']['news_guest'] && !$userdata['session_logged_in'] )
		{
			$template->assign_block_vars('view._comment._guest', array());
		}
		
		if ( request('submit', TXT) && ( $last_entry['poster_ip'] != $userdata['session_ip'] || ($last_entry['time_create'] + $settings['spam_comments']['event']) < $time ) )
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
				$template->assign_vars(array('META' => "<meta http-equiv=\"refresh\" content=\"3;url=$file?$url=$data\">"));
				
				$msg = $lang['add_comment'] . sprintf($lang['click_return_news'],  '<a href="' . check_sid("$file?$url=$data") . '">', '</a>');
				
				sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_EVENT, $data, $user));
				msg_add(EVENT, $data, $user, $poster_msg, $poster_nick, $poster_mail, $poster_hp);
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
	
	if ( $userdata['session_logged_in'] )
	{
		$sql = "SELECT read_time FROM " . COMMENT_READ . " WHERE user_id = $user AND type_id = $data AND type = " . READ_EVENT;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$unread = $db->sql_fetchrow($result);
		
		( $unread ) ? sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_EVENT, $data, $user)) : sql(COMMENT_READ, 'create', array('user_id' => $user, 'type_id' => $data, 'type' => READ_EVENT, 'read_time' => $time));
	}
	
	$template->assign_vars(array(
	
		'L_EVENT_TEXT'	=> html_entity_decode($view['event_desc'], ENT_QUOTES),
		
	#	'NEWS_TITLE'	=> $page_title,
	#	'NEWS_TEXT'		=> html_entity_decode($event['news_text'], ENT_QUOTES),
								 
	));

}
else
{
	$template->assign_block_vars('list', array());
	
	$page_title = $lang['header_event'];
	
	main_header($page_title);
	
	if ( !$tmp )
	{
		$template->assign_block_vars('list._empty_new', array());
		$template->assign_block_vars('list._empty_old', array());
	}
	else
	{
		$new = $old = '';
		
		foreach ( $tmp as $row )
		{
			if ( $userdata['user_level'] >= $row['event_level'] && $row['event_date'] > $time )
			{
				$new[]		= $row;
				$new_ids[]	= $row['event_id'];
				
			}
			else if ( $userdata['user_level'] >= $row['event_level'] && $row['event_date'] < $time )
			{
				$old[]		= $row;
				$old_ids[]	= $row['event_id'];
			}
			
			$ids[] = $row['event_id'];
		}
		
		/* event comments
		
		if ( $ids )
		{
			$sql = "SELECT event_id, event_comment AS count FROM " . EVENT . " WHERE event_id IN (" . implode(', ', $ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$cnt_comment[$row['event_id']] = $row['count'];
			}
		}
		*/
		
		if ( !$new )
		{
			$template->assign_block_vars('list._empty_new', array());
		}
		else
		{
			$cnt_new = count($new);
			
			/* event users */
			$sql = "SELECT * FROM " . LISTS . " WHERE type = " . TYPE_EVENT . " AND type_id IN (" . implode(', ', $new_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_new[$row['type_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = 0; $i < $cnt_new; $i++ )
			{
				$id		= $new[$i]['event_id'];
				$date	= create_date('d.m.Y', $new[$i]['event_date'], $userdata['user_timezone']);
				$time	= create_date('H:i', $new[$i]['event_date'], $userdata['user_timezone']);
				$dura	= create_date('H:i', $new[$i]['event_duration'], $userdata['user_timezone']);
				
				$css	= isset($in_new[$id][$user]) ? ( $in_new[$id][$user] == STATUS_NO ) ? 'no' : 'yes' : 'none';
				$pos	= isset($in_new[$id][$user]) ? ( $in_new[$id][$user] == STATUS_NO ) ? $lang['join_not'] : $lang['join_yes'] : $lang['join_none'];

				$template->assign_block_vars('list._new_row', array(
					'CLASS' => ( $i % 2 ) ? $theme['td_class1'] : $theme['td_class2'],

					'TITLE'	=> '<a href="' . check_sid("$file?$url=$id") . '" alt="" />' . $new[$i]['event_title'] . '</a>',
					'DATE'	=> sprintf($lang['sprintf_event'], $date, $time, $dura),
					
					'CSS'	=> $css,
					'POS'	=> $pos,
				));
			}
		}
		
		if ( !$old )
		{
			$template->assign_block_vars('list._empty_old', array());
		}
		else
		{
			$cnt_old = count($old);
			
			/* event users */
		#	$sql = "SELECT * FROM " . EVENT_USERS . " WHERE event_id IN (" . implode(', ', $old_ids) . ")";
			$sql = "SELECT * FROM " . LISTS . " WHERE type = " . TYPE_EVENT . " AND type_id IN (" . implode(', ', $new_ids) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_old[$row['type_id']][$row['user_id']] = $row['user_status'];
			}
			
			for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, $cnt_old); $i++ )
			{
				$id		= $old[$i]['event_id'];
				$date	= create_date('d.m.Y', $old[$i]['event_date'], $userdata['user_timezone']);
				$time	= create_date('H:i', $old[$i]['event_date'], $userdata['user_timezone']);
				$dura	= create_date('H:i', $old[$i]['event_duration'], $userdata['user_timezone']);
				
				$css	= isset($in_old[$id][$user]) ? ( $in_old[$id][$user] == STATUS_NO ) ? 'no' : 'yes' : 'none';
				$pos	= isset($in_old[$id][$user]) ? ( $in_old[$id][$user] == STATUS_NO ) ? $lang['join_not'] : $lang['join_yes'] : $lang['join_none'];
				
				$template->assign_block_vars('list._old_row', array(
					'CLASS' => ( $i % 2 ) ? $theme['td_class1'] : $theme['td_class2'],

					'TITLE'	=> '<a href="' . check_sid("$file?$url=$id") . '" alt="" />' . $old[$i]['event_title'] . '</a>',
					'DATE'	=> sprintf($lang['sprintf_event'], $date, $time, $dura),
					
					'CSS'	=> $css,
					'POS'	=> $pos,
				));
			}
		}
		
		$current_page = $cnt_old ? ceil($cnt_old/$settings['per_page_entry_site']) : 1;
		
		$template->assign_vars(array(
			'L_HEAD'		=> $page_title,
			
			'L_UPCOMING'	=> $lang['event_upcoming'],
			'L_EXPIRED'		=> $lang['event_expired'],
			
			'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry_site'] ) + 1 ), $current_page ),
			'PAGE_PAGING'	=> generate_pagination("$file?", $cnt_old, $settings['per_page_entry_site'], $start ),
			
			'S_ACTION'		=> check_sid($file),
		));
	}
}

$template->pparse('body');

main_footer();

?>