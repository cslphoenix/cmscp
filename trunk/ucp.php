<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_UCP);
init_userprefs($userdata);

$time = time();
$file = basename(__FILE__);

$mode	= request('mode', TXT);
$smode	= request('smode', TXT);

#$url_news	= POST_NEWS;
#$url_event	= POST_EVENT;
#$url_match	= POST_MATCH;

$error	= '';
$user	= $userdata['user_id'];

$page_title = $lang['main_ucp'];

main_header($page_title);

include($root_path . 'includes/functions_lobby.php');

$lobby_dateformat		= $settings['lobby']['date'];
$lobby_limit_news		= $settings['lobby']['news_limit']*86400;
$lobby_limit_event		= $settings['lobby']['event_limit']*86400;
$lobby_limit_match		= $settings['lobby']['match_limit']*86400;
$lobby_limit_training	= $settings['lobby']['train_limit']*86400;

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
			
		#	$ids = ( $type != 'event' ) ? ( $type == 'match' ) ? 'match_id' : 'training_id' : 'event_id';
		#	$tbl = ( $type != 'event' ) ? ( $type == 'match' ) ? MATCH_USERS : TRAINING_USERS : EVENT_USERS;
			
			$sql = "SELECT * FROM " . LISTS . " WHERE type = $type AND user_id = $user AND type_id = $dataid";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			
		#	debug($row);
			
			if ( $row )
			{
				sql(LISTS, 'update', array('user_status' => $option, 'time_update' => $time), array('user_id', 'type', 'type_id'), array($user, $type, $dataid));
			}
			else
			{
				sql(LISTS, 'create', array('type' => $type, 'type_id' => $dataid, 'user_id' => $user, 'user_status' => $option, 'time_create' => $time));
			}
		}
		
		$ntime = $time - $lobby_limit_news;
		
		/* News / Newskommentare */
		$sql = "SELECT * FROM " . NEWS . " WHERE news_date > $ntime ORDER BY news_date DESC";
		$tmp = _cached($sql, 'ly_news');
		
		if ( $tmp )
		{
			$new_ids = $new_ary = '';
			
			foreach ( $tmp as $row )
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
			
			$show = 0;
			$new_news = $new_ids;
			
			if ( $new_ids )
			{
				$cntns = count($new_ary);
				$count = ucp_read(READ_NEWS, $new_ids, $user);
				
				for ( $i = 0; $i < $cntns; $i++ )
				{
					$nid		= $new_ary[$i]['news_id'];
					$ntitle		= $new_ary[$i]['news_title'];
					$ncount		= $count[$nid];
					$ncomments	= ( $new_ary[$i]['count_comment'] != '0' ) ? $new_ary[$i]['count_comment'] : false;
					
					switch ( $ncount )
					{
						case '0':	$unread = false;	$comment = false; break;
						case 'u':	$unread = true;		$comment = $ncomments ? ( $ncomments >= 2 ) ? sprintf($lang['common_num_comments'], $ncomments) : sprintf($lang['common_num_comment'], $ncomments) : $lang['common_unread']; break;
						default:	$unread = true;		$comment = ( $ncount >= 2 ) ? sprintf($lang['common_num_comments'], $ncount) : sprintf($lang['common_num_comment'], $ncount); break;                                                  
					}
					
					if ( $unread )
					{
						$show ? false : $template->assign_block_vars('lobby.news', array());
						$show++;
						
						$template->assign_block_vars('lobby.news.news_row', array(
							'DATE'	=> create_date($lobby_dateformat, $new_ary[$i]['news_date'], $userdata['user_timezone']),
							'DATEI'	=> create_date($userdata['user_dateformat'], $new_ary[$i]['news_date'], $userdata['user_timezone']),
							'TITLE'	=> href('a_txt_ly', 'news.php', array('id' => $nid), $ntitle, $ntitle, $comment),
						));
					}
				}
			}
		}
		
		/* Event / Eventkommentare */
		$sql = "SELECT * FROM " . EVENT . " WHERE event_date > " . ($time - $lobby_limit_news) . " ORDER BY event_date ASC";
		$tmp = _cached($sql, 'ly_event');
		
		if ( $tmp )
		{
			$template->assign_block_vars('lobby.event', array());
			
			$new_ids = $old_ids = $new_ary = $old_ary = '';
			
			foreach ( $tmp as $row )
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
			
			$new_event = $new_ids;
			$old_event = $old_ids;
			
			if ( $new_ids )
			{
				$sql = "SELECT * FROM " . LISTS . " WHERE type = " . TYPE_EVENT . " AND type_id IN (" . implode(', ', $new_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$in_event[$row['type_id']][$row['user_id']] = $row['user_status'];
				}
				
				$show	= 0;
				$cntet	= count($new_ary);
				$count	= ucp_read(READ_EVENT, $new_ids, $user);
				
				if ( $cntet )
				{
					$template->assign_block_vars('lobby.event.new', array());
				
					for ( $i = 0; $i < $cntet; $i++ )
					{
						$eid		= $new_ary[$i]['event_id'];
						$etitle		= $new_ary[$i]['event_title'];
						$ecount		= $count[$eid];
						$ecomments	= ( $new_ary[$i]['count_comment'] != '0' ) ? $new_ary[$i]['count_comment'] : false;
						
						$css = isset($in_event[$eid][$user]) ? ( $in_event[$eid][$user] == STATUS_NO ) ? 'no' : 'yes' : 'none';
						
						switch ( $ecount )
						{
							case '0':	$unread = false;	$comment = false; break;
							case 'u':	$unread = true;		$comment = $ecomments ? ( $ecomments >= 2 ) ? sprintf($lang['common_num_comments'], $ecomments) : sprintf($lang['common_num_comment'], $ecomments) : $lang['common_unread']; break;
							default:	$unread = true;		$comment = ( $ecount >= 2 ) ? sprintf($lang['common_num_comments'], $ecount) : sprintf($lang['common_num_comment'], $ecount); break;                                                  
						}
						
						$switch = ( $comment ) ? 'a_txt_ly' : 'a_txt';
						
						$template->assign_block_vars('lobby.event.new.new_row', array(
							'DATE'		=> create_date($lobby_dateformat, $new_ary[$i]['event_date'], $userdata['user_timezone']),
							'TITLE'		=> href($switch, 'event.php', array('id' => $eid), $etitle, $etitle, $comment),
							'OPTION'	=> href('a_css', $file, array('mode' => 'lobby', 'smode' => 'switch', 'type' => TYPE_EVENT, 'op' => 1, 'id' => $eid), $css, $lang['yes']) . '&nbsp;&bull;&nbsp;' . href('a_css', $file, array('mode' => 'lobby', 'smode' => 'switch', 'type' => TYPE_EVENT, 'op' => 2, 'id' => $eid), $css, $lang['no']),
						));
					}
				}
			}
			
			if ( $old_ids )
			{
				$show = 0;
				$cntet = count($old_ary);
				$count = ucp_read(READ_EVENT, $old_ids, $user);
				
				for ( $i = 0; $i < $cntet; $i++ )
				{
					$eid		= $old_ary[$i]['event_id'];
					$etitle		= $old_ary[$i]['event_title'];
					$ecount		= $count[$eid];
					$ecomments	= ( $old_ary[$i]['count_comment'] != '0' ) ? $old_ary[$i]['count_comment'] : false;
					
					switch ( $ecount )
					{
						case '0':	$unread = false;	$comment = false; break;
						case 'u':	$unread = true;		$comment = $ecomments ? ( $ecomments >= 2 ) ? sprintf($lang['common_num_comments'], $ecomments) : sprintf($lang['common_num_comment'], $ecomments) : $lang['common_unread']; break;
						default:	$unread = true;		$comment = ( $ecount >= 2 ) ? sprintf($lang['common_num_comments'], $ecount) : sprintf($lang['common_num_comment'], $ecount); break;							
					}
					
					if ( $unread )
					{
						$show ? false : $template->assign_block_vars('lobby.event.old', array());
						$show++;
						
						$template->assign_block_vars('lobby.event.old.old_row', array(
							'DATE'	=> create_date($lobby_dateformat, $old_ary[$i]['event_date'], $userdata['user_timezone']),
							'TITLE'	=> href('a_txt_ly', 'event.php', array('id' => $eid), $etitle, $etitle, $comment),
						));
					}
				}
			}
		}

		/* Match / Matchkommentare */
		$sql = "SELECT * FROM " . MATCH . " WHERE match_date > " . ($time - $lobby_limit_news) . " ORDER BY match_date DESC";
		$match = _cached($sql, 'ly_match');

		if ( $match )
		{
			$template->assign_block_vars('lobby.match', array());
			
			$show = 0;
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
			
			$new_match = $new_ids;
			$old_match = $old_ids;
			
			if ( $new_ids )
			{
				$template->assign_block_vars('lobby.match.new', array());
				
				$sql = "SELECT * FROM " . LISTS . " WHERE type = " . TYPE_MATCH . " AND type_id IN (" . implode(', ', $new_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql); }
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$in_match[$row['type_id']][$row['user_id']] = $row['user_status'];
				}
				
				$cntmh = count($new_ary);
				$count = ucp_read(READ_MATCH, $new_ids, $user);
				
				for ( $i = 0; $i < $cntmh; $i++ )
				{	
					$mid		= $new_ary[$i]['match_id'];
					$mrival		= $new_ary[$i]['match_rival_name'];
					$mcount		= $count[$mid];
					$mcomments	= ( $new_ary[$i]['count_comment'] != '0' ) ? $new_ary[$i]['count_comment'] : false;
					
					$css = isset($in_match[$mid][$user]) ? ( $in_match[$mid][$user] != STATUS_NO ) ? ( $in_match[$mid][$user] == STATUS_YES ) ? 'yes' : 'replace' : 'no' : 'none';
					
					switch ( $mcount )
					{
						case '0':	$unread = false;	$comment = ''; break;
						case 'u':	$unread = true;		$comment = $mcomments ? ( $mcomments >= 2 ) ? sprintf($lang['common_num_comments'], $mcomments) : sprintf($lang['common_num_comment'], $mcomments) : $lang['common_unread']; break;
						default:	$unread = true;		$comment = ( $mcount >= 2 ) ? sprintf($lang['common_num_comments'], $mcount) : sprintf($lang['common_num_comment'], $mcount); break;
					}
					
					$switch = ( $comment ) ? 'a_txt_ly' : 'a_txt';
					
					$template->assign_block_vars('lobby.match.new.new_row', array(
						'DATE'		=> create_date($lobby_dateformat, $new_ary[$i]['match_date'], $userdata['user_timezone']),
						'TITLE'		=> href($switch, 'match.php', array('id' => $mid), $mrival, $mrival, $comment),
						'OPTION'	=> href('a_css', $file, array('mode' => 'lobby', 'smode' => 'switch', 'type' => TYPE_MATCH, 'op' => STATUS_YES, 'id' => $mid), $css, $lang['yes']) . '&nbsp;&bull;&nbsp;' . href('a_css', $file, array('mode' => 'lobby', 'smode' => 'switch', 'type' => TYPE_MATCH, 'op' => STATUS_NO, 'id' => $mid), $css, $lang['no']) . '&nbsp;&bull;&nbsp;' . href('a_css', $file, array('mode' => 'lobby', 'smode' => 'switch', 'type' => TYPE_MATCH, 'op' => STATUS_REPLACE, 'id' => $mid), $css, $lang['replace']),
					));
				}
			}
			
			if ( $old_ids )
			{
				$cntmh = count($old_ary);
				$count = ucp_read(READ_MATCH, $old_ids, $user);
				
				for ( $i = 0; $i < $cntmh; $i++ )
				{
					$mid		= $old_ary[$i]['match_id'];
					$mrival		= $old_ary[$i]['match_rival_name'];
					$mcount		= $count[$mid];
					$mcomments	= ( $old_ary[$i]['count_comment'] != '0' ) ? $old_ary[$i]['count_comment'] : false;
					
					switch ( $mcount )
					{
						case '0':	$unread = false;	$comment = ''; break;
						case 'u':	$unread = true;		$comment = $mcomments ? ( $mcomments >= 2 ) ? sprintf($lang['common_num_comments'], $mcomments) : sprintf($lang['common_num_comment'], $mcomments) : $lang['common_unread']; break;
						default:	$unread = true;		$comment = ( $mcount >= 2 ) ? sprintf($lang['common_num_comments'], $mcount) : sprintf($lang['common_num_comment'], $mcount); break;
					}
					
					if ( $unread )
					{
						$show ? false : $template->assign_block_vars('lobby.match.old', array());
						$show++;
						
						$template->assign_block_vars('lobby.match.old.old_row', array(
							'DATE'	=> create_date($lobby_dateformat, $old_ary[$i]['match_date'], $userdata['user_timezone']),
							'TITLE'	=> href('a_txt_ly', 'match.php', array('id' => $mid), $etitle, $mrival, $comment),
						));
					}
				}
			}
		}
		
		/* Training / Trainingskommentare */
		if ( $userdata['user_level'] >= TRIAL )
		{
			$sql = "SELECT * FROM " . TRAINING . " WHERE training_date > " . ($time - $lobby_limit_training) . " ORDER BY training_date DESC";
			$tmp = _cached($sql, 'ly_training');
			
			if ( $tmp )
			{
				$template->assign_block_vars('lobby.train', array());
				
				$new_ids = $old_ids = $new_ary = $old_ary = '';
				
				foreach ( $tmp as $row )
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
				
				$new_train = $new_ids;
				$old_train = $old_ids;
				
				if ( $new_ids )
				{
					
					$sql = "SELECT * FROM " . LISTS . " WHERE type = " . TYPE_TRAINING . " AND type_id IN (" . implode(', ', $new_ids) . ")";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					while ( $row = $db->sql_fetchrow($result) )
					{
						$in_train[$row['type_id']][$row['user_id']] = $row['user_status'];
					}
					
					$cnttg	= count($new_ids);
					$count	= ucp_read(READ_TRAINING, $new_ids, $user);
					
					if ( $cnttg )
					{
						$template->assign_block_vars('lobby.train.new', array());
					
						for ( $i = 0; $i < $cnttg; $i++ )
						{
							$tid		= $new_ary[$i]['training_id'];
							$tvs		= $new_ary[$i]['training_vs'];
							$tcount 	= $count[$tid];
							$tcomments	= ( $new_ary[$i]['count_comment'] != '0' ) ? $new_ary[$i]['count_comment'] : false;
						
							$css = isset($in_train[$tid][$user]) ? ( $in_train[$tid][$user] != STATUS_NO ) ? ( $in_train[$tid][$user] == STATUS_YES ) ? 'yes' : 'replace' : 'no' : 'none';
						
							switch ( $tcount )
							{
								case '0':	$unread = false;	$comment = ''; break;
								case 'u':	$unread = true;		$comment = $tcomments ? ( $tcomments >= 2 ) ? sprintf($lang['common_num_comments'], $tcomments) : sprintf($lang['common_num_comment'], $tcomments) : $lang['common_unread']; break;
								default:	$unread = true;		$comment = ( $tcount >= 2 ) ? sprintf($lang['common_num_comments'], $tcount) : sprintf($lang['common_num_comment'], $tcount); break;
							}
							
							$switch = ( $comment ) ? 'a_txt_ly' : 'a_txt';
							
							$template->assign_block_vars('lobby.train.new.new_row', array(
								'DATE'		=> create_date($lobby_dateformat, $new_ary[$i]['training_date'], $userdata['user_timezone']),
								'TITLE'		=> href($switch, 'training.php', array('id' => $tid), $tvs, $tvs, $comment),
								'OPTION'	=> href('a_css', $file, array('mode' => 'lobby', 'smode' => 'switch', 'type' => TYPE_TRAINING, 'op' => STATUS_YES, 'id' => $tid), $css, $lang['yes']) . '&nbsp;&bull;&nbsp;' . href('a_css', $file, array('mode' => 'lobby', 'smode' => 'switch', 'type' => TYPE_TRAINING, 'op' => STATUS_NO, 'id' => $tid), $css, $lang['no']) . '&nbsp;&bull;&nbsp;' . href('a_css', $file, array('mode' => 'lobby', 'smode' => 'switch', 'type' => TYPE_TRAINING, 'op' => STATUS_REPLACE, 'id' => $tid), $css, $lang['replace']),
							));
						}
					}
				}
				
				if ( $old_ids )
				{
					$show	= 0;
					$cnttg	= count($old_ary);
					$count	= ucp_read(READ_TRAINING, $old_ids, $user);
					
					for ( $i = 0; $i < $cnttg; $i++ )
					{
						$tid		= $old_ary[$i]['training_id'];
						$tvs		= $old_ary[$i]['training_vs'];
						$tcount		= $count[$tid];
						$tcomments	= ( $old_ary[$i]['count_comment'] != '0' ) ? $old_ary[$i]['count_comment'] : false;
						
						switch ( $tcount )
						{
							case '0':	$unread = false;	$comment = ''; break;
							case 'u':	$unread = true;		$comment = $mcomments ? ( $mcomments >= 2 ) ? sprintf($lang['common_num_comments'], $mcomments) : sprintf($lang['common_num_comment'], $mcomments) : $lang['common_unread']; break;
							default:	$unread = true;		$comment = ( $mcount >= 2 ) ? sprintf($lang['common_num_comments'], $mcount) : sprintf($lang['common_num_comment'], $mcount); break;
						}
						
						if ( $unread )
						{
							if ( !$show ) { $template->assign_block_vars('lobby.train.old', array()); }
														
							$template->assign_block_vars('lobby.train.old.old_row', array(
								'DATE'	=> create_date($lobby_dateformat, $old_ary[$i]['training_date'], $userdata['user_timezone']),
								'TITLE'	=> href('a_txt_ly', 'training.php', array('id' => $tid), $tvs, $tvs, $comment),
							));
							
							$show++;
						}
					}
				}
				
			}
		}
		
		$template->assign_vars(array(
			'L_HEAD'		=> $lang['main_ucp'],
			
			'L_NEWS'		=> $lang['lobby_news'],
			'L_EVENT'		=> $lang['cal_events'],
			'L_MATCH'		=> $lang['cal_matchs'],
			'L_TRAIN'		=> $lang['cal_trainings'],
			
			'L_UPCOMING'	=> $lang['upcoming'],
			'L_EXPIRED'		=> $lang['expired'],
			
			'L_UNREAD'		=> $lang['unread_txt'],
			
			'UNREAD_NEWS'	=> href('a_txt', 'ucp.php', array('mode' => 'lobby', 'smode' => 'unread_news'), $lang['unread_txt']),
			'UNREAD_EVENT'	=> href('a_txt', 'ucp.php', array('mode' => 'lobby', 'smode' => 'unread_event'), $lang['unread_txt']),
			'UNREAD_MATCH'	=> href('a_txt', 'ucp.php', array('mode' => 'lobby', 'smode' => 'unread_match'), $lang['unread_txt']),
			'UNREAD_TRAIN'	=> href('a_txt', 'ucp.php', array('mode' => 'lobby', 'smode' => 'unread_train'), $lang['unread_txt']),
						
			'L_ALL_UNREAD'	=> 'alles als gelesen markieren',
			'U_ALL_UNREAD'	=> "<a href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=unread_all") . "\">alles als gelesen markieren</a>",
		));
		
		if ( $smode == 'unread_all' || $smode == 'unread_news' || $smode == 'unread_event' || $smode == 'unread_match' || $smode == 'unread_train' )
		{
			switch ( $smode )
			{
				case 'unread_all':
				case 'unread_news':
				
					if ( isset($new_news) )
					{
						$diff_news = ucp_diff(READ_NEWS, $new_news, $user);
						
						if ( empty($diff_news['r']) && empty($diff_news['u']) )
						{
							foreach ( $new_news as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_NEWS, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
						}
						else if ( $diff_news['r'] || $diff_news['u'] )
						{
							foreach ( $diff_news['r'] as $key ) { sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_NEWS, $key, $user)); }
							foreach ( $diff_news['u'] as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_NEWS, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
						}
					}
					if ( $smode != 'unread_all' )
					{
						break;
					}
				
				case 'unread_all':
				case 'unread_event':
				
					$diff_events_new = is_array($new_event) ? ucp_diff(READ_EVENT, $new_event, $user) : '';
					$diff_events_old = is_array($old_event) ? ucp_diff(READ_EVENT, $old_event, $user) : '';
					
					if ( empty($diff_events_new['r']) && empty($diff_events_new['u']) )
					{
						foreach ( $new_event as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_EVENT, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					else if ( $diff_events_old['r'] || $diff_events_old['u'] )
					{
						foreach ( $diff_events_new['r'] as $key ) { sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_EVENT, $key, $user)); }
						foreach ( $diff_events_new['u'] as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_EVENT, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					
					if ( empty($diff_events_old['r']) && empty($diff_events_old['u']) )
					{
						foreach ( $old_event as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_EVENT, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					else if ( $diff_events_old['r'] || $diff_events_old['u'] )
					{
						foreach ( $diff_events_old['r'] as $key ) { sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_EVENT, $key, $user)); }
						foreach ( $diff_events_old['u'] as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_EVENT, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					
					if ( $smode != 'unread_all' )
					{
						break;
					}
				
				case 'unread_all':
				case 'unread_match':
				
					$diff_match_new = is_array($new_match) ? ucp_diff(READ_MATCH, $new_match, $user) : '';
					$diff_match_old = is_array($old_match) ? ucp_diff(READ_MATCH, $old_match, $user) : '';
					
					if ( empty($diff_match_new['r']) && empty($diff_match_new['u']) )
					{
						foreach ( $new_match as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					else if ( $diff_match_new['r'] || $diff_match_new['u'] )
					{
						foreach ( $diff_match_new['r'] as $key ) { sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_MATCH, $key, $user)); }
						foreach ( $diff_match_new['u'] as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					
					if ( empty($diff_match_old['r']) && empty($diff_match_old['u']) )
					{
						foreach ( $old_match as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					else if ( $diff_match_old['r'] || $diff_match_old['u'] )
					{
						foreach ( $diff_match_old['r'] as $key ) { sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_MATCH, $key, $user)); }
						foreach ( $diff_match_old['u'] as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_MATCH, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					
					if ( $smode != 'unread_all' )
					{
						break;
					}
					
				case 'unread_all':
				case 'unread_train':
				
					$diff_train_new = is_array($new_train) ? ucp_diff(READ_TRAINING, $new_train, $user) : '';
					$diff_train_old = is_array($old_train) ? ucp_diff(READ_TRAINING, $old_train, $user) : '';
					
					if ( empty($diff_train_new['r']) && empty($diff_train_new['u']) )
					{
						foreach ( $new_train as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_TRAINING, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					else if ( $diff_train_new['r'] || $diff_train_new['u'] )
					{
						foreach ( $diff_train_new['r'] as $key ) { sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_TRAINING, $key, $user)); }
						foreach ( $diff_train_new['u'] as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_TRAINING, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					
					if ( empty($diff_train_old['r']) && empty($diff_train_old['u']) )
					{
						foreach ( $old_train as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_TRAINING, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					else if ( $diff_train_old['r'] || $diff_train_old['u'] )
					{
						foreach ( $diff_train_old['r'] as $key ) { sql(COMMENT_READ, 'update', array('read_time' => $time), array('type', 'type_id', 'user_id'), array(READ_TRAINING, $key, $user)); }
						foreach ( $diff_train_old['u'] as $key ) { sql(COMMENT_READ, 'create', array('type' => READ_TRAINING, 'type_id' => $key, 'user_id' => $user, 'read_time' => $time)); }
					}
					
					if ( $smode != 'unread_all' )
					{
						break;
					}
			}

			$msg = $lang[$smode] . sprintf($lang['return'], check_sid("$file?mode=lobby"), $lang['main_ucp']);
			
			message(GENERAL_MESSAGE, $msg);
		}
	}
	else if ( $mode == 'profile_edit' )
	{
	#	$page_title = $lang['Index'];
		main_header();
		
		$template->assign_block_vars('update', array());
		$template->set_filenames(array('body' => 'body_ucp.tpl'));
		
		$sql = 'SELECT * FROM ' . FIELDS_DATA . ' WHERE user_id = ' . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$user_data = $db->sql_fetchrow($result);
/*
		$sql = 'SELECT * FROM ' . PROFILE_CAT . ' ORDER BY cat_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$cat = $db->sql_fetchrowset($result);
		
		$sql = 'SELECT * FROM ' . PROFILE . ' ORDER BY profile_cat, profile_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$profile[$row['profile_cat']][] = $row;
		}
	#	debug($profile);
*/
		$sql = 'SELECT * FROM ' . FIELDS . ' ORDER BY field_sub ASC, field_order ASC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			if ( !$row['field_sub'] )
			{
				$db_cat[$row['field_id']] = $row['field_name'];
			}
			else
			{
				$db_sub[$row['field_sub']][] = $row;
			}
		}
	
		if ( isset($db_sub) )
		{
			foreach ( $db_sub as $cat => $row )
			{
				foreach ( $row as $name => $details )
				{
					$max_sub[$cat] = $details['field_order'];
				}
			}
		}
		
	#	debug($db_cat);
	#	debug($db_sub);
		
		if ( $db_cat )
		{
			foreach ( $db_cat as $key => $value )
			{
				if ( isset($db_sub[$key]) )
				{
					$template->assign_block_vars("update.cat", array('CAT_NAME' => $value));
					
					foreach ( $db_sub[$key] as $field => $row )
					{
						$name	= $row['field_name'];
						$field	= $row['field_field'];
						$req	= $row['field_required'] ? 'r' : '';
						$value	= request($field, 'text') ? request($field, 'text') : $user_data[$field];
						
						$request['user_id'] = $user;
						$request[$field] = $value;
					
						if ( $row['field_required'] )
						{
							$error .= !$value ? ( $error ? '<br />' : '' ) . sprintf($lang['msg_select_profile_field'], $name) : '';
						}
						
						if ( $row['field_type'] == 0 )
						{
							$input = "<input type=\"text\" name=\"$field\" id=\"$field\" value=\"$value\" />";
						}
						else if ( $row['field_type'] == 1 )
						{
							$input = "<textarea name=\"$field\" id=\"$field\" cols=\"30\" />$value</textarea>";
						}
						else if ( $row['field_type'] == 2 )
						{
							$checked_yes	= ( $value == 1 ) ? 'checked="checked"' : '';
							$checked_no		= ( $value == 0 ) ? 'checked="checked"' : '';
							$input = "<label><input type=\"radio\" name=\"$field\" value=\"1\" $checked_yes/>&nbsp;{$lang['common_yes']}</label><span style=\"padding:4px;\"></span><label><input type=\"radio\" name=\"$field\" value=\"0\" $checked_no/>&nbsp;{$lang['common_no']}</label>";
						}
						
						$template->assign_block_vars("update.cat.field", array(
							'REQ'	=> $req,
							'NAME'	=> "<label for=\"$field\">$name</label>",
							'INPUT' => $input,
						));
					}
				}
			}
		}
		/*
		if ( $cat )
		{
			$cnt_cat = count($cat);
			
			for ( $i = 0; $i < $cnt_cat; $i++ )
			{
				$cid	= $cat[$i]['cat_id'];
				$cname	= $cat[$i]['cat_name'];
				
				if ( isset($profile[$cid]) )
				{
					$template->assign_block_vars('profile_edit.catrow', array( 
						'CATEGORY_ID'	=> $cid,
						'CATEGORY_NAME'	=> $cname,
					));
					
					$cnt_profile = count($profile[$cid]);
					
					for ( $j = 0; $j < $cnt_profile; $j++ )
					{
						$profile_id = $profile[$cid][$j]['profile_id'];
						
						if ( $profile[$cid][$j]['profile_cat'] == $cid )
						{
							$value = $user_data[$profile[$cid][$j]['profile_field']];
							
							if ( $profile[$cid][$j]['profile_type'] )
							{
								$field = '<textarea class="textarea" name="' . $profile[$cid][$j]['profile_field'] . '" rows="5" cols="50" >' . $value . '</textarea>';
							}
							else
							{
								$field = '<input type="text" name="'.$profile[$cid][$j]['profile_field'].'" value="'.$value.'">';
							}
							
							$template->assign_block_vars('profile_edit.catrow.profilerow',	array(
								'NAME'	=> $profile[$cid][$j]['profile_name'],
								'FIELD' => $field,
							));
						}
					}
				}
			}
		}
		*/
		$template->assign_vars(array(
	#		'L_HEAD'		=> $lang['ucp_main'],
			'L_HEAD'		=> 'Benutzer Center',
			
			
			'L_ALL_UNREAD'	=> 'alles als gelesen markieren',
			'U_ALL_UNREAD'	=> "<a href=\"" . check_sid("ucp.php?mode=lobby&amp;smode=unread_all") . "\">alles als gelesen markieren</a>",
		));
		
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