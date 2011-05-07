<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_UCP);
init_userprefs($userdata);

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

if ( $userdata['session_logged_in'] )
{
	if ( $mode == 'lobby' || $mode == '' )
	{
		$page_title = $lang['Index'];
		include($root_path . 'includes/page_header.php');
		
		$template->assign_block_vars('lobby', array());
		$template->set_filenames(array('body' => 'body_ucp.tpl'));
		
		//
		//	News / Newskommentare
		//
		$sql = 'SELECT *
					FROM ' . NEWS . '
					WHERE news_time_create > ' . (time() - $settings['lobby_limit_news']) . '
				ORDER BY news_time_public DESC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$news_data = $db->sql_fetchrowset($result);
//		$news_data = _cached($sql, 'lobby_news');
		
		if ( $news_data )
		{
			$news_member = $news_guest = array();
			
			foreach ( $news_data as $news => $row )
			{
				if ( $userdata['user_level'] >= TRIAL )
				{
					$news_member[] = $row;
				}
				else if ( $row['news_intern'] == '0' )
				{
					$news_guest[] = $row;
				}
			}
			
			if ( $userdata['user_level'] >= TRIAL )
			{
				$news_data = $news_member;
			}
			else
			{
				$news_data = $news_guest;
			}
		
			//
			//	Schleife zum durchlaufen alle Wars
			//	bei hoher Anzahl längere Wartezeit <- entfällt da ein Tagelimit gesetzt wird
			//
			for ( $i = 0; $i < count($news_data); $i++ )
			{
				//
				//	Überprüfung ob der Benutzer die News gesehen hat
				//
				$sql = 'SELECT read_time
							FROM ' . NEWS_COMMENTS_READ . '
							WHERE user_id = ' . $userdata['user_id'] . '
								AND news_id = ' . $news_data[$i]['news_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
							
				if ( $db->sql_numrows($result) )
				{
					//
					//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
					//	die seit dem letzten mal Lesen geschrieben wurden
					//
					$sql = 'SELECT COUNT(nc.news_comments_id) AS total_comments
								FROM ' . NEWS_COMMENTS . ' nc
									LEFT JOIN ' . NEWS_COMMENTS_READ . ' ncr ON nc.news_id = ncr.news_id
								WHERE nc.news_id = ' . $news_data[$i]['news_id'] . '
									AND ncr.user_id = ' . $userdata['user_id'] . '
									AND ncr.read_time < nc.time_create';
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$news_data_unread = $db->sql_fetchrow($result);
					
					$count	= $news_data_unread['total_comments'];
					$unread = false;
				}
				else
				{
					//
					//	Wenn noch kein Eintrag vorhanden ist, wird die Anzahl ausgelesen.
					//	SQL Abfrage durch hochzählen erspart.
					//
					$count	= $news_data[$i]['news_comment'];
					$unread = true;
				}
				
				if ( $count != '0' || $unread )
				{
					$language	= ( $count == '1' ) ? $lang['common_num_comment'] : $lang['common_num_comments'];
					
					$template->assign_block_vars('lobby.news_new_row', array(
						'NEWS_NAME'		=> cut_string($news_data[$i]['news_title'], $settings['cut_news_lobby']),
						'NEWS_COMMENTS'	=> ( $count ) ? '<a href="' . check_sid('news.php?mode=view&amp;' . POST_NEWS_URL . '=' . $news_data[$i]['news_id']) . '">' . sprintf($language, $count) . '</a>' : '<a href="' . check_sid('news.php?mode=view&amp;' . POST_NEWS_URL . '=' . $news_data[$i]['news_id']) . '">' . $lang['common_unread'] . '</a>',
					));
				}
			}
		}
		
		//
		//	Clanwars/Clanwarskommentare
		//
		$sql = 'SELECT *
					FROM ' . MATCH . '
					WHERE match_create > ' . (time() - $settings['lobby_limit_match']) . '
				ORDER BY match_date';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$match_data = $db->sql_fetchrowset($result);
//		$match_data = _cached($sql, 'lobby_match');
		
		$match_member = $match_guest = array();
		
		if ( $match_data )
		{
			foreach ( $match_data as $match => $row )
			{
				if ( $userdata['user_level'] >= TRIAL )
				{
					$match_member[] = $row;
				}
				else if ( $row['match_public'] == '0' )
				{
					$match_guest[] = $row;
				}
			}
			
			if ( $userdata['user_level'] >= TRIAL )
			{
				$match_data = $match_member;
			}
			else
			{
				$match_data = $match_guest;
			}
			
			//
			//	Schleife zum durchlaufen alle Wars
			//	bei hoher Anzahl längere Wartezeit <- entfällt da ein Tagelimit gesetzt wird
			//
			for ( $i = 0; $i < count($match_data); $i++ )
			{
				//
				//	Aussortierung, Nicht Abgelaufene Wars werden
				//	Angezeigt bis Sie abgelaufen sind
				//
				if ( $match_data[$i]['match_date'] > time() )
				{
					//
					//	Überprüfung ob der Benutzer die Kommentare gesehen hat
					//
					$sql = 'SELECT read_time
								FROM ' . MATCH_COMMENTS_READ . '
								WHERE user_id = ' . $userdata['user_id'] . '
									AND match_id = ' . $match_data[$i]['match_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					if ( $db->sql_numrows($result) )
					{
						//
						//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
						//	die seit dem letzten mal Lesen geschrieben wurden
						//
						$sql = 'SELECT COUNT(mc.match_comments_id) AS total_comments
									FROM ' . MATCH_COMMENTS . ' mc
										LEFT JOIN ' . MATCH_COMMENTS_READ . ' mcr ON mc.match_id = mcr.match_id
									WHERE mc.match_id = ' . $match_data[$i]['match_id'] . '
										AND mcr.user_id = ' . $userdata['user_id'] . '
										AND mcr.read_time < mc.time_create';
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						$match_data_unread = $db->sql_fetchrow($result);
					
						$count = $match_data_unread['total_comments'];
					}
					else
					{
						//
						//	Wenn noch kein Eintrag vorhanden ist, wird die Anzahl ausgelesen.
						//	SQL Abfrage durch hochzählen erspart.
						//
						$count	= $match_data[$i]['match_comment'];
					}
					
					$language = ( $count == '1' ) ? $lang['common_num_comment'] : $lang['common_num_comments'];
					
					$template->assign_block_vars('match_new_row', array(
						'MATCH_NAME'		=> $match_data[$i]['match_rival_name'],
						'MATCH_COMMENTS'	=> ( $count ) ? '<a href="' . check_sid('match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_data[$i]['match_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
					));
				}
				else if ( $match_data[$i]['match_date'] < time() )
				{
					//
					//	Überprüfung ob der Benutzer die Kommentare gesehen hat
					//
					$sql = 'SELECT read_time
								FROM ' . MATCH_COMMENTS_READ . '
								WHERE user_id = ' . $userdata['user_id'] . '
									AND match_id = ' . $match_data[$i]['match_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					if ( $db->sql_numrows($result) )
					{
						//
						//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
						//	die seit dem letzten mal Lesen geschrieben wurden
						//
						$sql = 'SELECT COUNT(mc.match_comments_id) AS total_comments
									FROM ' . MATCH_COMMENTS . ' mc
										LEFT JOIN ' . MATCH_COMMENTS_READ . ' mcr ON mc.match_id = mcr.match_id
									WHERE mc.match_id = ' . $match_data[$i]['match_id'] . '
										AND mcr.user_id = ' . $userdata['user_id'] . '
										AND mcr.read_time < mc.time_create';
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$match_data_unread = $db->sql_fetchrow($result);
						
						$count = $match_data_unread['total_comments'];
					}
					else
					{
						//
						//	Wenn noch kein Eintrag vorhanden ist, wird die Anzahl ausgelesen.
						//	SQL Abfrage durch hochzählen erspart.
						//
						$count	= $match_data[$i]['match_comment'];
					}
					
					if ( $count )
					{
						$language = ( $count == '1' ) ? $lang['common_num_comment'] : $lang['common_num_comments'];
						
						$template->assign_block_vars('match_old', array());
						$template->assign_block_vars('match_old.match_old_row', array(
							'MATCH_NAME'		=> $match_data[$i]['match_rival_name'],
							'MATCH_COMMENTS'	=> ( $count ) ? '<a href="' . check_sid('match.php?mode=details&amp;' . POST_MATCH_URL . '=' . $match_data[$i]['match_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
						));
					}
	
				}	//	Teilung in Alt und Neu
			}	//	Schleife
		}	//	Abfrage ob Daten vorhanden sind
		
		//
		//	Training/Trainingskommentare
		//
		if ( $userdata['user_level'] >= TRIAL )
		{
			$template->assign_block_vars('training', array());
			
			$sql = 'SELECT *
						FROM ' . TRAINING . '
						WHERE training_create > ' . (time() - $settings['lobby_limit_training']) . '
					ORDER BY training_date';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$training_data = $db->sql_fetchrowset($result);
//			$training_data = _cached($sql, 'lobby_training');
		
			if ( $training_data )
			{
				//
				//	Schleife zum durchlaufen alle Wars
				//	bei hoher Anzahl längere Wartezeit <- entfällt da ein Tagelimit gesetzt wird
				//
				for ( $i = 0; $i < count($training_data); $i++ )
				{
					//
					//	Aussortierung, Nicht Abgelaufene Wars werden
					//	Angezeigt bis Sie abgelaufen sind
					//
					if ( $training_data[$i]['training_date'] > time() )
					{
						//
						//	Überprüfung ob der Benutzer die Kommentare gesehen hat
						//
						$sql = 'SELECT read_time
									FROM ' . TRAINING_COMMENTS_READ . '
									WHERE user_id = ' . $userdata['user_id'] . '
										AND training_id = ' . $training_data[$i]['training_id'];
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( $db->sql_numrows($result) )
						{
							//
							//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
							//	die seit dem letzten mal Lesen geschrieben wurden
							//
							$sql = 'SELECT COUNT(mc.training_comments_id) AS total_comments
										FROM ' . TRAINING_COMMENTS . ' mc
											LEFT JOIN ' . TRAINING_COMMENTS_READ . ' mcr ON mc.training_id = mcr.training_id
										WHERE mc.training_id = ' . $training_data[$i]['training_id'] . '
											AND mcr.user_id = ' . $userdata['user_id'] . '
											AND mcr.read_time < mc.time_create';
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							$training_data_unread = $db->sql_fetchrowset($result);
							
							$count = $training_data_unread['total_comments'];
						}
						else
						{
							//
							//	Wenn noch kein Eintrag vorhanden ist, wird die Anzahl ausgelesen.
							//	SQL Abfrage durch hochzählen erspart.
							//
							$count = $training_data[$i]['training_comment'];
						}
						
						$language = ( $count == '1' ) ? $lang['common_num_comment'] : $lang['common_num_comments'];
						
						$template->assign_block_vars('training.training_new_row', array(
							'TRAINING_NAME'		=> $training_data[$i]['training_vs'],
							'TRAINING_COMMENTS'	=> ( $count ) ? '<a href="' . check_sid('match.php?mode=details&amp;' . POST_TRAINING_URL . '=' . $training_data[$i]['training_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
						));
					}
					else if ( $training_data[$i]['training_date'] < time() )
					{
						//
						//	Überprüfung ob der Benutzer die Kommentare gesehen hat
						//
						$sql = 'SELECT read_time
									FROM ' . TRAINING_COMMENTS_READ . '
									WHERE user_id = ' . $userdata['user_id'] . '
										AND training_id = ' . $training_data[$i]['training_id'];
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						if ( $db->sql_numrows($result) )
						{
							//
							//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
							//	die seit dem letzten mal Lesen geschrieben wurden
							//		
							$sql = 'SELECT COUNT(mc.training_comments_id) AS total_comments
										FROM ' . TRAINING_COMMENTS . ' mc
											LEFT JOIN ' . TRAINING_COMMENTS_READ . ' mcr ON mc.training_id = mcr.training_id
										WHERE mc.training_id = ' . $training_data[$i]['training_id'] . '
											AND mcr.user_id = ' . $userdata['user_id'] . '
											AND mcr.read_time < mc.time_create';
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							$training_data_unread = $db->sql_fetchrowset($result);
							
							$count = $training_data_unread['total_comments'];
						}
						else
						{
							//
							//	Wenn noch kein Eintrag vorhanden ist, wird die Anzahl ausgelesen.
							//	SQL Abfrage durch hochzählen erspart.
							//
							$count = $training_data[$i]['training_comment'];
						}
						
						if ( $count )
						{
							$language = ( $count == '1' ) ? $lang['common_num_comment'] : $lang['common_num_comments'];
							
							$template->assign_block_vars('training.training_old', array());
							$template->assign_block_vars('training.training_old.training_old_row', array(
								'TRAINING_NAME'		=> $training_data[$i]['training_vs'],
								'TRAINING_COMMENTS'	=> ( $count ) ? '<a href="' . check_sid('match.php?mode=details&amp;' . POST_TRAINING_URL . '=' . $training_data[$i]['training_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
								
							));
						}
					}	//	Teilung in Alt und Neu
				}	//	Schleife
			}	//	Abfrage ob Daten vorhanden sind
		}
		
		$template->assign_vars(array(
			'L_HEAD_UCP'		=> $lang['ucp_main'],
			'L_STATUS'			=> $lang['status'],
			'L_STATUS_YES'		=> $lang['status_yes'],
			'L_STATUS_NO'		=> $lang['status_no'],
			'L_STATUS_REPLACE'	=> $lang['status_replace'],
			'L_SET_STATUS'		=> $lang['set_status']
		));
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
					'CATEGORY_NAME'			=> $category_rows[$i]['category_name'],
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

include($root_path . 'includes/page_tail.php');

?>