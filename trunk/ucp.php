<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_UCP);
init_userprefs($userdata);

$time = time();

$mode	= request('mode', 1);
$smode	= request('smode', 1);

$url_news	= POST_NEWS;
$url_event	= POST_EVENT;
$url_match	= POST_MATCH;

$user	= $userdata['user_id'];

$page_title = $lang['Index'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'body_ucp.tpl'));

if ( $userdata['session_logged_in'] )
{
	if ( $mode == 'lobby' || $mode == '' )
	{
		$template->assign_block_vars('lobby', array());
		
		if ( $smode == 'switch' )
		{
			$type	= request('type', 2);
			$dataid	= request('id', 2);
			$option	= request('op', 2);
			
			$ids = ( $type != 'event' ) ? ( $type == 'match' ) ? 'match_id' : 'training_id' : 'event_id';
			$tbl = ( $type != 'event' ) ? ( $type == 'match' ) ? MATCH_USERS : TRAINING_USERS : EVENT_USERS;
			
			$sql = "SELECT * FROM $tbl  WHERE user_id = $user AND $ids = $dataid";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			
			( $row ) ? sql($tbl, 'update', array('user_status' => $option, 'status_update' => $time), array('user_id', $ids), array($user, $dataid)) : sql($tbl, 'create', array($ids => $dataid, 'user_id' => $user, 'user_status' => $option, 'status_create' => $time));
		}
		
		/* News / Newskommentare */
		$sql = "SELECT * FROM " . NEWS . " WHERE news_time_public > " . ($time - $settings['lobby_limit_news']) . " ORDER BY news_time_public DESC";
		$news = _cached($sql, 'ly_news');
		
		if ( $news )
		{
			$new_ids = $new_ary = '';
			
			foreach ( $news as $key => $row )
			{
				if ( $userdata['user_level'] >= TRIAL )
				{
					$new_ids[] = $row['news_id'];
					$new_ary[] = $row;
				}
				else if ( $row['news_intern'] == '0' )
				{
					$new_ids[] = $row['news_id'];
					$new_ary[] = $row;
				}
			}
			
			$new_news_ids = $new_ids;
			
			if ( $new_ids )
			{
				$show = 0;
				$count = '';
				$counts = '';
				
				$count = ucp_read(READ_NEWS, $new_ids, $user);
				$cntns = count($new_ary);
				
				for ( $i = 0; $i < $cntns; $i++ )
				{
					$news_id	= $new_ary[$i]['news_id'];
					$news_title	= cut_string($new_ary[$i]['news_title'], $settings['cut_news_lobby']);
					$news_count	= $count[$news_id];
					
					$counts = ( $news_count != 'unread' ) ? ( $news_count != '0' ) ? $news_count : '' : $new_ary[$i]['news_comment'];
					$unread = ( $news_count != 'unread' ) ? ( $news_count != '0' ) ? true : false : true;
					
					if ( $unread )
					{
						( !$show ) ? $template->assign_block_vars('lobby._news', array()) : false;
						
						$comment = ( $counts != '1' ) ? ( $counts > 1 ? sprintf($lang['common_num_comments'], $counts) : $lang['common_unread'] ) : sprintf($lang['common_num_comment'], $counts);
						
						$template->assign_block_vars('lobby._news._news_row', array(
							'DATE'		=> create_date($settings['lobby_dateformat'], $new_ary[$i]['news_time_public'], $userdata['user_timezone']),
							'TITLE'		=> "<a href=\"" . check_sid("news.php?mode=view&amp;$url_news=$news_id") . "\">$news_title</a>",
							'COMMENT'	=> "<a href=\"" . check_sid("news.php?mode=view&amp;$url_news=$news_id") . "\">$comment</a>",
						));
						
						$show++;
					}
				}
			}
		}
			
		/* Event / Eventkommentare */
		$sql = "SELECT * FROM " . EVENT . " WHERE event_date > " . ($time - $settings['lobby_limit_news']) . " ORDER BY event_date ASC";
		$event = _cached($sql, 'ly_event');
		
		if ( $event )
		{
			$url = POST_EVENT;
			
			$new_ids = $old_ids = $new_ary = $old_ary = '';
			
			foreach ( $event as $key => $row )
			{
				if ( $userdata['user_level'] >= $row['event_level'] )
				{
					if ( $row['event_date'] > $time )
					{
						$new_ids[] = $row['event_id'];
						$new_ary[] = $row;
					}
					else
					{
						$old_ids[] = $row['event_id'];
						$old_ary[] = $row;
					}
				}
			}
			
			$new_event_ids = $new_ids;
			$old_event_ids = $old_ids;
			
			if ( $new_ids )
			{
				$show = 0;
				$count = '';
				$counts = '';
				
			#	foreach ( $new_ary as $key => $array )
			#	{
			#		$new_ids[$key] = $array['event_id'];
			#	} 
			#	array_multisort($new_ids, SORT_ASC, SORT_NUMERIC, $new_ary);  
				
				$sql = "SELECT * FROM " . EVENT_USERS . " WHERE event_id IN (" . implode(', ', $new_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$in_event[$row['event_id']][$row['user_id']] = $row['user_status'];
				}
				
				$count = ucp_read(READ_EVENT, $new_ids, $user);
				$cntet = count($new_ary);
				
				for ( $i = 0; $i < $cntet; $i++ )
				{
					$event_id		= $new_ary[$i]['event_id'];
					$event_title	= $new_ary[$i]['event_title'];
					$event_count	= $count[$event_id];
					$event_comments	= ( $new_ary[$i]['event_comment'] != '0' ) ? $new_ary[$i]['event_comment'] : false;
					
					$css = 'none';
					
					if ( isset($in_ary[$event_id]) )
					{
						switch ( $in_event[$event_id][$userdata['user_id']] )
						{
							case STATUS_NO:		$css = 'no';	break;
							case STATUS_YES:	$css = 'yes';	break;
						}
					}
					
					switch ( $event_count )
					{
						case '0':
							$unread = false;
							$comment = '';
							
							break;
							
						case 'unread':
							$unread = true;
							$comment = ( $event_comments ) ? ( $event_comments >= 2 )? sprintf($lang['common_num_comments'], $event_comments) : sprintf($lang['common_num_comment'], $event_comments) : $lang['common_unread'];
							
							break;
							
						default:
							
							$unread = true;
							$comment = ( $event_count >= 2 ) ? sprintf($lang['common_num_comments'], $event_count) : sprintf($lang['common_num_comment'], $event_count);
							
							break;							
					}
					
					( !$show ) ? $template->assign_block_vars('lobby._event_new', array()) : false;
					
					$template->assign_block_vars('lobby._event_new._event_new_row', array(
						'DATE'		=> create_shortdate($settings['lobby_dateformat'], $new_ary[$i]['event_date'], $userdata['user_timezone']),
						'TITLE'		=> "<a href=\"" . check_sid("event.php?$url=$event_id") . "\">$event_title</a>",
						'COMMENT'	=> "<a href=\"" . check_sid("event.php?$url=$event_id") . "\">$comment</a>",
						'OPTION'	=> "<a href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=$url&amp;id=$event_id&amp;op=1") . "\" class=\"$css\">" . $lang['yes'] . "</a>&nbsp;&bull;&nbsp;<a class=\"$css\" href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=event&amp;id=$event_id&amp;op=2") . "\">" . $lang['no'] . "</a>",
					));
					
					$show++;
				}
			}
			
			if ( $old_ids )
			{
				$show = 0;
				$counts = '';
				$event_count = '';
				
				$count = ucp_read(READ_EVENT, $old_ids, $user);
				
			#	debug($count);
				
				for ( $i = 0; $i < count($old_ary); $i++ )
				{
					$event_id		= $old_ary[$i]['event_id'];
					$event_title	= $old_ary[$i]['event_title'];
					$event_count	= $count[$event_id];
					$event_comments	= ( $old_ary[$i]['event_comment'] != '0' ) ? $old_ary[$i]['event_comment'] : false;
					
					switch ( $event_count )
					{
						case '0':
							$unread = false;
							$comment = '';
							
							break;
							
						case 'unread':
							$unread = true;
							$comment = ( $event_comments ) ? ( $event_comments >= 2 )? sprintf($lang['common_num_comments'], $event_comments) : sprintf($lang['common_num_comment'], $event_comments) : $lang['common_unread'];
							
							break;
							
						default:
							
							$unread = true;
							$comment = ( $event_count >= 2 ) ? sprintf($lang['common_num_comments'], $event_count) : sprintf($lang['common_num_comment'], $event_count);
							
							break;							
					}
					
					if ( $unread )
					{
						( !$show ) ? $template->assign_block_vars('lobby._event_old', array()) : false;
						
						$template->assign_block_vars('lobby._event_old._event_old_row', array(
							'DATE'		=> create_date($settings['lobby_dateformat'], $old_ary[$i]['event_date'], $userdata['user_timezone']),
							'TITLE'		=> "<a href=\"" . check_sid("event.php?mode=view&amp;$url=$event_id") . "\">$event_title</a>",
							'COMMENT'	=> "<a href=\"" . check_sid("event.php?mode=view&amp;$url=$event_id") . "\">$comment</a>",
						));
						
						$show++;
					}
				}
			}
		}

		/* Match / Matchkommentare */
		$sql = "SELECT * FROM " . MATCH . " WHERE match_date > " . ($time - $settings['lobby_limit_news']) . " ORDER BY match_date DESC";
		$match = _cached($sql, 'ly_match');

		if ( $match )
		{
			$new_ids = $old_ids = $new_ary = $old_ary = '';
			
			foreach ( $match as $key => $row )
			{
				if ( $userdata['user_level'] >= TRIAL )
				{
					if ( $row['match_date'] > $time )
					{
						$new_ids[] = $row['match_id'];
						$new_ary[] = $row;
					}
					else
					{
						$old_ids[] = $row['match_id'];
						$old_ary[] = $row;
					}
				}
				else if ( $row['match_public'] == '1' )
				{
					if ( $row['match_date'] > $time )
					{
						$new_ids[] = $row['match_id'];
						$new_ary[] = $row;
					}
					else
					{
						$old_ids[] = $row['match_id'];
						$old_ary[] = $row;
					}
				}
			}
			
			$new_match_ids = $new_ids;
			$old_match_ids = $old_ids;
			
			if ( $new_ids )
			{
				$show = 0;
				$count = '';
				$counts = '';
				
				$sql = "SELECT * FROM " . MATCH_USERS . " WHERE match_id IN (" . implode(', ', $new_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$in_match[$row['match_id']][$row['user_id']] = $row['user_status'];
				}
				
				$count = ucp_read(READ_MATCH, $new_ids, $user);
				
				for ( $i = 0; $i < count($new_ary); $i++ )
				{
					$css = 'none';
					
					$match_id = $new_ary[$i]['match_id'];
					$match_rival = $new_ary[$i]['match_rival_name'];
					$match_count = $count[$match_id];
					
					if ( isset($in_match[$match_id]) )
					{
						switch ( $in_match[$match_id][$userdata['user_id']] )
						{
							case STATUS_NO:			$css = 'no';		break;
							case STATUS_YES:		$css = 'yes';		break;
							case STATUS_REPLACE:	$css = 'replace';	break;
						}
					}
					
				#	if ( isset($in_match[$match_id]) )
				#	{
				#		foreach ( $in_match[$match_id] as $key => $row )
				#		{
				#			if ( $key == $userdata['user_id'] )
				#			{
				#				switch ( $row )
				#				{
				#					case STATUS_NO:			$css = 'no';		break;
				#					case STATUS_YES:		$css = 'yes';		break;
				#					case STATUS_REPLACE:	$css = 'replace';	break;
				#				}
				#			}
				#		}
				#	}
					
					if ( $match_count == '0' )
					{
						$comment = '';
					}
					else if ( $match_count == 'unread' )
					{
						$comment = $lang['common_unread'];
					}
					else
					{
						$comment = ( $match_count > 1 ) ? sprintf($lang['common_num_comments'], $match_count) : sprintf($lang['common_num_comment'], $match_count);
					}
					
					( !$show ) ? $template->assign_block_vars('lobby._match_new', array()) : false;
					
					$template->assign_block_vars('lobby._match_new._match_new_row', array(
						'DATE'		=> create_date($settings['lobby_dateformat'], $new_ary[$i]['match_date'], $userdata['user_timezone']),
						'TITLE'		=> "<a href=\"" . check_sid("match.php?mode=detail&amp;" . POST_MATCH . "=$match_id") . "\">$match_rival</a>",
						'COMMENT'	=> "<a href=\"" . check_sid("match.php?mode=detail&amp;" . POST_MATCH . "=$match_id") . "\">$comment</a>",
						'OPTION'	=> "<a href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=match&amp;id=$match_id&amp;op=" . STATUS_YES) . "\" class=\"$css\">" . $lang['yes'] . "</a>&nbsp;&bull;&nbsp;<a class=\"$css\" href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=match&amp;id=$match_id&amp;op=" . STATUS_NO) . "\">" . $lang['no'] . "</a>&nbsp;&bull;&nbsp;<a class=\"$css\" href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=match&amp;id=$match_id&amp;op=" . STATUS_REPLACE) . "\">" . $lang['replace'] . "</a>",
					));
					
					$show++;
				}
			}
			
			if ( $old_ids )
			{
				$show = 0;
				$count = '';
				$counts = '';
				
				$count = ucp_read(READ_MATCH, $old_ids, $user);
				
				for ( $i = 0; $i < count($old_ary); $i++ )
				{
					$match_id = $old_ary[$i]['match_id'];
					$match_rival = $old_ary[$i]['match_rival_name'];
					$match_count = $count[$match_id];
					/*
					if ( $match_count == '0' )
					{
						$unread = false;
						$comment = '';
					}
					else if ( $match_count == 'unread' )
					{
						$unread = true;
						$comment = $lang['common_unread'];
					}
					else
					{
						$unread = true;
						$comment = ( $event_count > 1 ) ? sprintf($lang['common_num_comments'], $match_count) : sprintf($lang['common_num_comment'], $match_count);
					}
					*/
					$counts = ( $match_count != 'unread' ) ? ( $match_count != '0' ) ? $match_count : '' : $old_ary[$i]['match_comment'];
					$unread = ( $match_count != 'unread' ) ? ( $match_count != '0' ) ? true : false : true;
					
					if ( $unread )
					{
						( !$show ) ? $template->assign_block_vars('lobby._match_old', array()) : false;
						
						$comment = ( $counts != '1' ) ? ( $counts > 1 ? sprintf($lang['common_num_comments'], $counts) : $lang['common_unread'] ) : sprintf($lang['common_num_comment'], $counts);
						
						$template->assign_block_vars('lobby._match_old._match_old_row', array(
							'DATE'		=> create_date($settings['lobby_dateformat'], $old_ary[$i]['match_date'], $userdata['user_timezone']),
							'TITLE'		=> "<a href=\"" . check_sid("match.php?mode=detail&amp;e=$match_id") . "\">$match_rival</a>",
							'COMMENT'	=> "<a href=\"" . check_sid("match.php?mode=detail&amp;e=$match_id") . "\">$comment</a>",
						));
						
						$show++;
					}
				}
			}
		}
		
		/* Training / Trainingskommentare */
		if ( $userdata['user_level'] >= TRIAL )
		{
			$sql = "SELECT * FROM " . TRAINING . " WHERE training_date > " . (time() - $settings['lobby_limit_training']) . " ORDER BY training_date";
			$training = _cached($sql, 'ly_training');
			
			if ( $training )
			{
				$new_ids = $old_ids = $new_ary = $old_ary = '';
				
				foreach ( $training as $key => $row )
				{
					if ( $row['training_date'] > $time )
					{
						$new_ids[] = $row['training_id'];
						$new_ary[] = $row;
					}
					else
					{
						$old_ids[] = $row['training_id'];
						$old_ary[] = $row;
					}
				}
			}
			
		#	debug($new_ids);
		#	debug($old_ids);
		}
		
	#	$unread_news	= implode(',', $new_news_ids);
	#	$unread_events	= implode(',', $new_event_ids);
		
		$template->assign_vars(array(
	#		'L_HEAD'		=> $lang['ucp_main'],
			'L_HEAD'		=> 'Benutzer Center',
			'L_NEWS'		=> $lang['lobby_news'],
			'L_UP_EVENT'	=> $lang['event_upcoming'],
			'L_EX_EVENT'	=> $lang['event_expired'],
			
			'L_ALL_UNREAD'	=> 'alles als gelesen markieren',
			'U_ALL_UNREAD'	=> "<a href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=all_unread") . "\">alles als gelesen markieren</a>",
		));
		
		if ( $smode == 'all_unread' )
		{
			$diff_news			= ucp_diff(READ_NEWS, $new_news_ids, $user);
			$diff_events_new	= is_array($new_event_ids) ? ucp_diff(READ_EVENT, $new_event_ids, $user) : '';
			$diff_events_old	= is_array($old_event_ids) ? ucp_diff(READ_EVENT, $old_event_ids, $user) : '';
			$diff_match_new		= is_array($new_match_ids) ? ucp_diff(READ_MATCH, $new_match_ids, $user) : '';
			$diff_match_old		= is_array($old_match_ids) ? ucp_diff(READ_MATCH, $old_match_ids, $user) : '';
			
			if ( empty($diff_news['read']) && empty($diff_news['unread']) )
			{
				foreach ( $new_news_ids as $key )
				{
					sql(COMMENT_READ, 'create', array('type' => READ_NEWS, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
				}
			}
			else if ( $diff_news['read'] || $diff_news['unread'] )
			{
				foreach ( $diff_news['read'] as $key )
				{
					sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_NEWS, $key, $user));
				}
				
				foreach ( $diff_news['unread'] as $key )
				{
					sql(COMMENT_READ, 'create', array('type' => READ_NEWS, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
				}
			}
		
			if ( empty($diff_events_new['read']) && empty($diff_events_new['unread']) )
			{
				foreach ( $new_event_ids as $key )
				{
					sql(COMMENT_READ, 'create', array('type' => READ_EVENT, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
				}
			}
			else if ( $diff_events_old['read'] || $diff_events_old['unread'] )
			{
				foreach ( $diff_events_new['read'] as $key )
				{
					sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_EVENT, $key, $user));
				}
				
				foreach ( $diff_events_new['unread'] as $key )
				{
					sql(COMMENT_READ, 'create', array('type' => READ_EVENT, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
				}
			}
			
			if ( empty($diff_events_old['read']) && empty($diff_events_old['unread']) )
			{
				foreach ( $old_event_ids as $key )
				{
					sql(COMMENT_READ, 'create', array('type' => READ_EVENT, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
				}
			}
			else if ( $diff_events_old['read'] || $diff_events_old['unread'] )
			{
				foreach ( $diff_events_old['read'] as $key )
				{
					sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_EVENT, $key, $user));
				}
				
				foreach ( $diff_events_old['unread'] as $key )
				{
					sql(COMMENT_READ, 'create', array('type' => READ_EVENT, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
				}
			}
			
			if ( empty($diff_match_new['read']) && empty($diff_match_new['unread']) )
			{
				if ( is_array($new_match_ids) )
				{
					foreach ( $new_match_ids as $key )
					{
						sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
					}
				}
			}
			else if ( $diff_match_new['read'] || $diff_match_new['unread'] )
			{
				foreach ( $diff_match_new['read'] as $key )
				{
					sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_MATCH, $key, $user));
				}
				
				foreach ( $diff_match_new['unread'] as $key )
				{
					sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
				}
			}
			
			if ( empty($diff_match_old['read']) && empty($diff_match_old['unread']) )
			{
				foreach ( $old_match_ids as $key )
				{
					sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
				}
			}
			else if ( $diff_match_old['read'] || $diff_match_old['unread'] )
			{
				foreach ( $diff_match_old['read'] as $key )
				{
					sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_MATCH, $key, $user));
				}
				
				foreach ( $diff_match_old['unread'] as $key )
				{
					sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
				}
			}
			
			$msg = 'alles als gelesen markiert.';
			
			message(GENERAL_MESSAGE, $msg);
		}
	}
	else if ( $mode == 'profile_edit' )
	{
		$page_title = $lang['Index'];
		include($root_path . 'includes/page_header.php');
		
		$template->assign_block_vars('profile_edit', array());
		$template->set_filenames(array('body' => 'body_ucp.tpl'));
		
		$sql = 'SELECT * FROM ' . PROFILE_DATA . ' WHERE user_id = ' . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$user_data = $db->sql_fetchrow($result);

		$sql = 'SELECT * FROM ' . PROFILE_CAT . ' ORDER BY cat_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		if ( $total_categories = $db->sql_numrows($result) )
		{
			$category_rows = $db->sql_fetchrowset($result);
			
			$sql = 'SELECT *
						FROM ' . PROFILE . '
						ORDER BY profile_cat, profile_order';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		
			if ( $total_profile = $db->sql_numrows($result) )
			{
				$profile_rows = $db->sql_fetchrowset($result);
			}
			
			for ( $i = 0; $i < $total_categories; $i++ )
			{
				$cat_id = $category_rows[$i]['cat_id'];
		
				$template->assign_block_vars('profile_edit.catrow', array( 
					'CATEGORY_ID'			=> $cat_id,
					'CATEGORY_NAME'			=> $category_rows[$i]['cat_name'],
				));
				
				for($j = 0; $j < $total_profile; $j++)
				{
					$profile_id = $profile_rows[$j]['profile_id'];
					
					if ( $profile_rows[$j]['profile_cat'] == $cat_id )
					{
						$value = $user_data[$profile_rows[$j]['profile_field']];
						
						if ( $profile_rows[$j]['profile_type'] )
						{
							$field = '<textarea class="textarea" name="' . $profile_rows[$j]['profile_field'] . '" rows="5" cols="50" >' . $value . '</textarea>';
						}
						else
						{
							$field = '<input type="text" class="post" name="'.$profile_rows[$j]['profile_field'].'" value="'.$value.'">';
						}
						
						$template->assign_block_vars('profile_edit.catrow.profilerow',	array(
							'NAME'	=> $profile_rows[$j]['profile_name'],
							'FIELD' => $field,
						));
					}
				}
			}
		}
		
	}
}
else
{
	if ( !$userdata['session_logged_in'] )
	{
		redirect(check_sid('login.php?redirect=ucp.php', true));
	}
}

$template->pparse('body');

main_footer();

?>