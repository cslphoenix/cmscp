<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_UCP);
init_userprefs($userdata);

$time = time();

<<<<<<< .mine
$mode	= request('mode', 1);
$smode	= request('smode', 1);

$url_news	= POST_NEWS;
$url_event	= POST_EVENT;
$url_match	= POST_MATCH;

$user	= $userdata['user_id'];

=======
$mode	= request('mode', 1);
$smode	= request('smode', 1);

$url_news	= POST_NEWS;
$url_event	= POST_EVENT;
$url_match	= POST_MATCH;

$user	= $userdata['user_id'];

$page_title = $lang['Index'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'body_ucp.tpl'));

>>>>>>> .r85
if ( $userdata['session_logged_in'] )
{
	if ( $mode == 'lobby' || $mode == '' )
	{
<<<<<<< .mine
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
		
		$page_title = $lang['Index'];
		include($root_path . 'includes/page_header.php');
		
=======
>>>>>>> .r85
		$template->assign_block_vars('lobby', array());
		
<<<<<<< .mine
		/* News / Newskommentare */
		$sql = "SELECT * FROM " . NEWS . " WHERE news_time_public > " . ($time - $settings['lobby_limit_news']) . " ORDER BY news_time_public DESC";
		if ( !($result = $db->sql_query($sql)) )
=======
		if ( $smode == 'switch' )
>>>>>>> .r85
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
<<<<<<< .mine
		$news = $db->sql_fetchrowset($result);
//		$news = _cached($sql, 'ly_news');
=======
>>>>>>> .r85
		
<<<<<<< .mine
		if ( $news )
=======
		/* News / Newskommentare */
		$sql = "SELECT * FROM " . NEWS . " WHERE news_time_public > " . ($time - $settings['lobby_limit_news']) . " ORDER BY news_time_public DESC";
		$news = _cached($sql, 'ly_news');
		
		if ( $news )
>>>>>>> .r85
		{
<<<<<<< .mine
			$count = $show = $ary_id = $ary_data = '';
=======
			$new_ids = $new_ary = '';
>>>>>>> .r85
			
			foreach ( $news as $key => $row )
			{
				if ( $userdata['user_level'] >= TRIAL )
				{
<<<<<<< .mine
					$ary_id[] = $row['news_id'];
					$ary_data[] = $row;
=======
					$new_ids[] = $row['news_id'];
					$new_ary[] = $row;
>>>>>>> .r85
				}
				else if ( $row['news_intern'] == '0' )
				{
<<<<<<< .mine
					$ary_id[] = $row['news_id'];
					$ary_data[] = $row;
=======
					$new_ids[] = $row['news_id'];
					$new_ary[] = $row;
>>>>>>> .r85
				}
			}
			
<<<<<<< .mine
			$ary_new_news = $ary_id;
			
			if ( $ary_id )
=======
			$new_news_ids = $new_ids;
			
			if ( $new_ids )
>>>>>>> .r85
			{
<<<<<<< .mine
				$count = ucp_read(READ_NEWS, $ary_id, $user);
				
				for ( $i = 0; $i < count($ary_data); $i++ )
				{
					$news_id = $ary_data[$i]['news_id'];
					$news_title = cut_string($ary_data[$i]['news_title'], $settings['cut_news_lobby']);
					$news_count = $count[$news_id];
					
					$counts = ( $news_count != 'unread' ) ? ( $news_count != '0' ) ? $news_count : '' : $ary_data[$i]['news_comment'];
					$unread = ( $news_count != 'unread' ) ? ( $news_count != '0' ) ? true : false : true;
					
					if ( $unread )
					{
						( !$show ) ? $template->assign_block_vars('lobby._news', array()) : false;
						
						$comment = ( $counts != '1' ) ? ( $counts > 1 ? sprintf($lang['common_num_comments'], $counts) : $lang['common_unread'] ) : sprintf($lang['common_num_comment'], $counts);
						
						$template->assign_block_vars('lobby._news._news_row', array(
							'DATE'		=> create_date($settings['lobby_dateformat'], $ary_data[$i]['news_time_public'], $userdata['user_timezone']),
							'TITLE'		=> "<a href=\"" . check_sid("news.php?mode=view&amp;$url_news=$news_id") . "\">$news_title</a>",
							'COMMENT'	=> "<a href=\"" . check_sid("news.php?mode=view&amp;$url_news=$news_id") . "\">$comment</a>",
						));
						
						$show++;
					}
				}
=======
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
>>>>>>> .r85
			}
<<<<<<< .mine
		}
			
		/* Event / Eventkommentare */
		$sql = "SELECT * FROM " . EVENT . " WHERE event_date > " . ($time - $settings['lobby_limit_news']) . " ORDER BY event_date DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$event = $db->sql_fetchrowset($result);
//		$event = _cached($sql, 'ly_event');
=======
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
>>>>>>> .r85
<<<<<<< .mine
		
		if ( $event )
		{
			$count = $ary_new = $ary_old = $ary_new_data = $ary_old_data = '';
			
			foreach ( $event as $key => $row )
=======
			
			$new_event_ids = $new_ids;
			$old_event_ids = $old_ids;
			
			if ( $new_ids )
>>>>>>> .r85
			{
<<<<<<< .mine
				if ( $userdata['user_level'] >= $row['event_level'] )
=======
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
>>>>>>> .r85
				{
					if ( $row['event_date'] > $time )
					{
						$ary_new[] = $row['event_id'];
						$ary_new_data[] = $row;
					}
					else
					{
						$ary_old[] = $row['event_id'];
						$ary_old_data[] = $row;
					}
				}
<<<<<<< .mine
			}
			
			foreach ( $ary_new_data as $key => $array )
			{
				$ary_new[$key] = $array['event_id'];
			} 

			array_multisort($ary_new, SORT_DESC, SORT_NUMERIC, $ary_new_data);  
			
			$ary_new_event = $ary_new;
			$ary_old_event = $ary_old;
			
			$sql = "SELECT * FROM " . EVENT_USERS . " WHERE event_id IN (" . implode(', ', $ary_new) . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$in_ary[$row['event_id']][$row['user_id']] = $row['user_status'];
			}
			
			if ( $ary_new )
			{
				$show = 0;
				$count = '';
				$count = ucp_read(READ_EVENT, $ary_new, $user);
				
				for ( $i = 0; $i < count($ary_new_data); $i++ )
=======
				
				while ( $row = $db->sql_fetchrow($result) )
>>>>>>> .r85
				{
<<<<<<< .mine
					$event_id = $ary_new_data[$i]['event_id'];
					$event_title = $ary_new_data[$i]['event_title'];
					$event_count = $count[$event_id];
					
					$css = 'none';
					
					if ( isset($in_ary[$event_id]) )
=======
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
>>>>>>> .r85
					{
<<<<<<< .mine
						foreach ( $in_ary[$event_id] as $key => $row )
						{
							if ( $key == $userdata['user_id'] )
							{
								switch ( $row )
								{
									case STATUS_NO:		$css = 'no';	break;
									case STATUS_YES:	$css = 'yes';	break;
								}
							}
						}
=======
						switch ( $in_event[$event_id][$userdata['user_id']] )
						{
							case STATUS_NO:		$css = 'no';	break;
							case STATUS_YES:	$css = 'yes';	break;
						}
>>>>>>> .r85
					}
					
<<<<<<< .mine
					if ( $event_count == '0' )
					{
						$comment = '';
					}
					else if ( $event_count == 'unread' )
					{
						$comment = $lang['common_unread'];
					}
					else
					{
						$comment = ( $event_count > 1 ) ? sprintf($lang['common_num_comments'], $event_count) : sprintf($lang['common_num_comment'], $event_count);
					}
					
					( !$show ) ? $template->assign_block_vars('lobby._event_new', array()) : false;
					
					$template->assign_block_vars('lobby._event_new._event_new_row', array(
						'DATE'		=> create_shortdate($settings['lobby_dateformat'], $ary_new_data[$i]['event_date'], $userdata['user_timezone']),
						'TITLE'		=> "<a href=\"" . check_sid("event.php?mode=detail&amp;e=$event_id") . "\">$event_title</a>",
						'COMMENT'	=> "<a href=\"" . check_sid("event.php?mode=detail&amp;e=$event_id") . "\">$comment</a>",
						'OPTION'	=> "<a href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=event&amp;id=$event_id&amp;op=1") . "\" class=\"$css\">" . $lang['yes'] . "</a>&nbsp;&bull;&nbsp;<a class=\"$css\" href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=event&amp;id=$event_id&amp;op=2") . "\">" . $lang['no'] . "</a>",
					));
					
					$show++;
=======
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
>>>>>>> .r85
				}
<<<<<<< .mine
			}
			
			if ( $ary_old )
			{
				$show = 0;
				$count = '';
				$count = ucp_read(READ_EVENT, $ary_old, $user);
=======
			}
			
			if ( $old_ids )
			{
				$show = 0;
				$counts = '';
				$event_count = '';
>>>>>>> .r85
				
<<<<<<< .mine
				for ( $i = 0; $i < count($ary_old_data); $i++ )
=======
				$count = ucp_read(READ_EVENT, $old_ids, $user);
				
			#	debug($count);
				
				for ( $i = 0; $i < count($old_ary); $i++ )
>>>>>>> .r85
				{
<<<<<<< .mine
					$event_id = $ary_old_data[$i]['event_id'];
=======
					$event_id		= $old_ary[$i]['event_id'];
					$event_title	= $old_ary[$i]['event_title'];
					$event_count	= $count[$event_id];
					$event_comments	= ( $old_ary[$i]['event_comment'] != '0' ) ? $old_ary[$i]['event_comment'] : false;
>>>>>>> .r85
					
<<<<<<< .mine
					$event_count = $count[$event_id];
					
					$counts = ( $event_count != 'unread' ) ? ( $event_count != '0' ) ? $event_count : '' : $ary_old_data[$i]['event_comment'];
					$unread = ( $event_count != 'unread' ) ? ( $event_count != '0' ) ? true : false : true;
					/*
					if ( $event_count == '0' )
					{
						$unread = false;
						$comment = '';
					}
					else if ( $event_count == 'unread' )
					{
						$unread = true;
						$comment = $lang['common_unread'];
					}
					else
					{
						$unread = true;
						$comment = ( $event_count > 1 ) ? sprintf($lang['common_num_comments'], $event_count) : sprintf($lang['common_num_comment'], $event_count);
					}
					*/
					if ( $unread )
					{
						( !$show ) ? $template->assign_block_vars('lobby._event_old', array()) : false;
						
						$template->assign_block_vars('lobby._event_old._event_old_row', array(
							'DATE'		=> create_date($settings['lobby_dateformat'], $ary_old_data[$i]['event_date'], $userdata['user_timezone']),
							'TITLE'		=> "<a href=\"" . check_sid("event.php?mode=detail&amp;e=$event_id") . "\">$event_title</a>",
							'COMMENT'	=> "<a href=\"" . check_sid("event.php?mode=detail&amp;e=$event_id") . "\">$comment</a>",
						));
						
						$show++;
					}
=======
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
>>>>>>> .r85
				}
			}
		}
<<<<<<< .mine

		/* Match / Matchkommentare */
		$sql = "SELECT * FROM " . MATCH . " WHERE match_date > " . ($time - $settings['lobby_limit_news']) . " ORDER BY match_date DESC";
		if ( !($result = $db->sql_query($sql)) )
=======

		/* Match / Matchkommentare */
		$sql = "SELECT * FROM " . MATCH . " WHERE match_date > " . ($time - $settings['lobby_limit_news']) . " ORDER BY match_date DESC";
		$match = _cached($sql, 'ly_match');

		if ( $match )
>>>>>>> .r85
		{
<<<<<<< .mine
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$match = $db->sql_fetchrowset($result);
//		$match = _cached($sql, 'ly_match');

		if ( $match )
		{
			$count = $ary_new = $ary_old = $data_new = $data_old = '';
			
			foreach ( $match as $key => $row )
=======
			$new_ids = $old_ids = $new_ary = $old_ary = '';
			
			foreach ( $match as $key => $row )
>>>>>>> .r85
			{
				if ( $userdata['user_level'] >= TRIAL )
				{
<<<<<<< .mine
					if ( $row['match_date'] > $time )
					{
						$ary_new[] = $row['match_id'];
						$data_new[] = $row;
					}
					else
					{
						$ary_old[] = $row['match_id'];
						$data_old[] = $row;
					}
=======
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
>>>>>>> .r85
				}
				else if ( $row['match_public'] == '1' )
				{
<<<<<<< .mine
					if ( $row['match_date'] > $time )
					{
						$ary_new[] = $row['match_id'];
						$data_new[] = $row;
					}
					else
					{
						$ary_old[] = $row['match_id'];
						$data_old[] = $row;
					}
=======
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
>>>>>>> .r85
				}
			}
			
<<<<<<< .mine
			$ary_new_match = $ary_new;
			$ary_old_match = $ary_old;
=======
			$new_match_ids = $new_ids;
			$old_match_ids = $old_ids;
>>>>>>> .r85
			
<<<<<<< .mine
			if ( $ary_new )
=======
			if ( $new_ids )
>>>>>>> .r85
			{
<<<<<<< .mine
				$sql = "SELECT * FROM " . MATCH_USERS . " WHERE match_id IN (" . implode(', ', $ary_new) . ")";
				if ( !($result = $db->sql_query($sql)) )
=======
				$show = 0;
				$count = '';
				$counts = '';
				
				$sql = "SELECT * FROM " . MATCH_USERS . " WHERE match_id IN (" . implode(', ', $new_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
>>>>>>> .r85
				{
<<<<<<< .mine
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$in_match[$row['match_id']][$row['user_id']] = $row['user_status'];
				}
				
				$show = 0;
				$count = '';
				$count = ucp_read(READ_MATCH, $ary_new, $user);
				
				for ( $i = 0; $i < count($data_new); $i++ )
				{
					$css = 'none';
=======
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
>>>>>>> .r85
					
<<<<<<< .mine
					$match_id = $data_new[$i]['match_id'];
					$match_rival = $data_new[$i]['match_rival_name'];
					$match_count = $count[$match_id];
					
					if ( isset($in_match[$match_id]) )
=======
					$match_id = $new_ary[$i]['match_id'];
					$match_rival = $new_ary[$i]['match_rival_name'];
					$match_count = $count[$match_id];
					
					if ( isset($in_match[$match_id]) )
>>>>>>> .r85
					{
<<<<<<< .mine
						foreach ( $in_match[$match_id] as $key => $row )
=======
						switch ( $in_match[$match_id][$userdata['user_id']] )
>>>>>>> .r85
						{
<<<<<<< .mine
							if ( $key == $userdata['user_id'] )
							{
								switch ( $row )
								{
									case STATUS_NO:			$css = 'no';		break;
									case STATUS_YES:		$css = 'yes';		break;
									case STATUS_REPLACE:	$css = 'replace';	break;
								}
							}
=======
							case STATUS_NO:			$css = 'no';		break;
							case STATUS_YES:		$css = 'yes';		break;
							case STATUS_REPLACE:	$css = 'replace';	break;
>>>>>>> .r85
						}
					}
					
<<<<<<< .mine
					if ( $match_count == '0' )
					{
						$comment = '';
=======
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
>>>>>>> .r85
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
					
<<<<<<< .mine
					$template->assign_block_vars('lobby._match_new._match_new_row', array(
						'DATE'		=> create_date($settings['lobby_dateformat'], $data_new[$i]['match_date'], $userdata['user_timezone']),
						'TITLE'		=> "<a href=\"" . check_sid("match.php?mode=detail&amp;" . POST_MATCH . "=$match_id") . "\">$match_rival</a>",
						'COMMENT'	=> "<a href=\"" . check_sid("match.php?mode=detail&amp;" . POST_MATCH . "=$match_id") . "\">$comment</a>",
						'OPTION'	=> "<a href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=match&amp;id=$match_id&amp;op=" . STATUS_YES) . "\" class=\"$css\">" . $lang['yes'] . "</a>&nbsp;&bull;&nbsp;<a class=\"$css\" href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=match&amp;id=$match_id&amp;op=" . STATUS_NO) . "\">" . $lang['no'] . "</a>&nbsp;&bull;&nbsp;<a class=\"$css\" href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=match&amp;id=$match_id&amp;op=" . STATUS_REPLACE) . "\">" . $lang['replace'] . "</a>",
=======
					$template->assign_block_vars('lobby._match_new._match_new_row', array(
						'DATE'		=> create_date($settings['lobby_dateformat'], $new_ary[$i]['match_date'], $userdata['user_timezone']),
						'TITLE'		=> "<a href=\"" . check_sid("match.php?mode=detail&amp;" . POST_MATCH . "=$match_id") . "\">$match_rival</a>",
						'COMMENT'	=> "<a href=\"" . check_sid("match.php?mode=detail&amp;" . POST_MATCH . "=$match_id") . "\">$comment</a>",
						'OPTION'	=> "<a href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=match&amp;id=$match_id&amp;op=" . STATUS_YES) . "\" class=\"$css\">" . $lang['yes'] . "</a>&nbsp;&bull;&nbsp;<a class=\"$css\" href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=match&amp;id=$match_id&amp;op=" . STATUS_NO) . "\">" . $lang['no'] . "</a>&nbsp;&bull;&nbsp;<a class=\"$css\" href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=switch&amp;type=match&amp;id=$match_id&amp;op=" . STATUS_REPLACE) . "\">" . $lang['replace'] . "</a>",
>>>>>>> .r85
					));
					
					$show++;
				}
<<<<<<< .mine
			}
			
			if ( $ary_old )
			{
				$show = 0;
				$count = '';
				$count = ucp_read(READ_MATCH, $ary_old, $user);
				
				for ( $i = 0; $i < count($data_old); $i++ )
=======
			}
			
			if ( $old_ids )
			{
				$show = 0;
				$count = '';
				$counts = '';
				
				$count = ucp_read(READ_MATCH, $old_ids, $user);
				
				for ( $i = 0; $i < count($old_ary); $i++ )
>>>>>>> .r85
				{
<<<<<<< .mine
					$match_id = $data_old[$i]['match_id'];
					$match_rival = $data_old[$i]['match_rival_name'];
					$match_count = $count[$match_id];
					/*
					if ( $match_count == '0' )
=======
					$match_id = $old_ary[$i]['match_id'];
					$match_rival = $old_ary[$i]['match_rival_name'];
					$match_count = $count[$match_id];
					/*
					if ( $match_count == '0' )
>>>>>>> .r85
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
<<<<<<< .mine
					*/
					$counts = ( $match_count != 'unread' ) ? ( $match_count != '0' ) ? $match_count : '' : $data_old[$i]['match_comment'];
					$unread = ( $match_count != 'unread' ) ? ( $match_count != '0' ) ? true : false : true;
=======
					*/
					$counts = ( $match_count != 'unread' ) ? ( $match_count != '0' ) ? $match_count : '' : $old_ary[$i]['match_comment'];
					$unread = ( $match_count != 'unread' ) ? ( $match_count != '0' ) ? true : false : true;
>>>>>>> .r85
					
					if ( $unread )
					{
						( !$show ) ? $template->assign_block_vars('lobby._match_old', array()) : false;
						
<<<<<<< .mine
						$comment = ( $counts != '1' ) ? ( $counts > 1 ? sprintf($lang['common_num_comments'], $counts) : $lang['common_unread'] ) : sprintf($lang['common_num_comment'], $counts);
						
						$template->assign_block_vars('lobby._match_old._match_old_row', array(
							'DATE'		=> create_date($settings['lobby_dateformat'], $data_old[$i]['match_date'], $userdata['user_timezone']),
							'TITLE'		=> "<a href=\"" . check_sid("match.php?mode=detail&amp;e=$match_id") . "\">$match_rival</a>",
							'COMMENT'	=> "<a href=\"" . check_sid("match.php?mode=detail&amp;e=$match_id") . "\">$comment</a>",
=======
						$comment = ( $counts != '1' ) ? ( $counts > 1 ? sprintf($lang['common_num_comments'], $counts) : $lang['common_unread'] ) : sprintf($lang['common_num_comment'], $counts);
						
						$template->assign_block_vars('lobby._match_old._match_old_row', array(
							'DATE'		=> create_date($settings['lobby_dateformat'], $old_ary[$i]['match_date'], $userdata['user_timezone']),
							'TITLE'		=> "<a href=\"" . check_sid("match.php?mode=detail&amp;e=$match_id") . "\">$match_rival</a>",
							'COMMENT'	=> "<a href=\"" . check_sid("match.php?mode=detail&amp;e=$match_id") . "\">$comment</a>",
>>>>>>> .r85
						));
						
						$show++;
					}
				}
			}
		}
		
		/* Training / Trainingskommentare */
		if ( $userdata['user_level'] >= TRIAL )
		{
<<<<<<< .mine
			$sql = "SELECT * FROM " . TRAINING . " WHERE training_date > " . (time() - $settings['lobby_limit_training']) . " ORDER BY training_date";
			if ( !($result = $db->sql_query($sql)) )
=======
			$sql = "SELECT * FROM " . TRAINING . " WHERE training_date > " . (time() - $settings['lobby_limit_training']) . " ORDER BY training_date";
			$training = _cached($sql, 'ly_training');
			
			if ( $training )
>>>>>>> .r85
			{
<<<<<<< .mine
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$training = $db->sql_fetchrowset($result);
			
			if ( $training )
			{
				$ary_new = $ary_old = $ary_new_data = $ary_old_data = '';
				
				foreach ( $training as $key => $row )
				{
					$ary_new[] = $row['training_id'];
					$ary_data[] = $row;
				}
			}
		}
		
	#	$unread_news	= implode(',', $ary_new_news);
	#	$unread_events	= implode(',', $ary_new_event);
		
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
			$diff_news			= ucp_diff(READ_NEWS, $ary_new_news, $user);
			$diff_events_new	= is_array($ary_new_event) ? ucp_diff(READ_EVENT, $ary_new_event, $user) : '';
			$diff_events_old	= is_array($ary_old_event) ? ucp_diff(READ_EVENT, $ary_old_event, $user) : '';
			$diff_match_new		= is_array($ary_new_match) ? ucp_diff(READ_MATCH, $ary_new_match, $user) : '';
			$diff_match_old		= is_array($ary_old_match) ? ucp_diff(READ_MATCH, $ary_old_match, $user) : '';
			
			if ( empty($diff_news['read']) && empty($diff_news['unread']) )
			{
				foreach ( $ary_new_news as $key )
=======
				$new_ids = $old_ids = $new_ary = $old_ary = '';
				
				foreach ( $training as $key => $row )
>>>>>>> .r85
				{
<<<<<<< .mine
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
				foreach ( $ary_new_event as $key )
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
				foreach ( $ary_old_event as $key )
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
				if ( is_array($ary_new_match) )
				{
					foreach ( $ary_new_match as $key )
=======
					if ( $row['training_date'] > $time )
>>>>>>> .r85
					{
<<<<<<< .mine
						sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time));
=======
						$new_ids[] = $row['training_id'];
						$new_ary[] = $row;
>>>>>>> .r85
					}
<<<<<<< .mine
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
				foreach ( $ary_old_match as $key )
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
=======
					else
					{
						$old_ids[] = $row['training_id'];
						$old_ary[] = $row;
					}
				}
			}
			
		#	debug($new_ids);
		#	debug($old_ids);
>>>>>>> .r85
		}
<<<<<<< .mine
=======
		
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
>>>>>>> .r85
		
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