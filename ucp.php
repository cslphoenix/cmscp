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

$userdata = session_pagestart($user_ip, PAGE_UCP);
init_userprefs($userdata);

if ( $userdata['session_logged_in'] )
{
	$page_title = $lang['Index'];
	include($root_path . 'includes/page_header.php');
	$template->set_filenames(array('body' => 'body_ucp.tpl'));
	//
	//	News / Newskommentare
	//
	if ( $userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN )
	{
		$sql = 'SELECT n.* FROM ' . NEWS . " n ORDER BY n.news_time_public DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
		}
		$news_data = $db->sql_fetchrowset($result);
	//	$news_data = _cached($sql, 'news_lobby_member');
	}
	else
	{
		$sql = 'SELECT n.* FROM ' . NEWS . ' n WHERE n.news_intern = 0 ORDER BY n.news_time_public';
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
		}
		$news_data = $db->sql_fetchrowset($result);
	//	$match_data = _cached($sql, 'news_lobby_guest');
	}
	
	if ( $news_data )
	{
		//	Schleife zum durchlaufen alle Wars
		//	bei hoher Anzahl längere Wartezeit
		for ( $i = 0; $i < count($news_data); $i++ )
		{
			//	Überprüfung ob der Benutzer die News gesehen hat
			$sql = 'SELECT read_time
						FROM ' . NEWS_COMMENTS_READ . '
						WHERE user_id = ' . $userdata['user_id'] . ' AND news_id = ' . $news_data[$i]['news_id'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			if ($db->sql_numrows($result))
			{
				//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
				//	die seit dem letzten mal Lesen geschrieben wurden
				$sql = 'SELECT COUNT(nc.news_comments_id) AS total_comments
							FROM ' . NEWS_COMMENTS . ' nc
								LEFT JOIN ' . NEWS_COMMENTS_READ . ' ncr ON nc.news_id = ncr.news_id
							WHERE nc.news_id = ' . $news_data[$i]['news_id'] . ' AND ncr.user_id = ' . $userdata['user_id'] . ' AND ncr.read_time < nc.time_create';
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$unread = false;
			}
			else
			{
				//	Wenn noch kein Eintrag vorhanden ist,
				//	Zählt er alle Beiträge die in diesem
				//	Thema geschrieben wurden
				$sql = 'SELECT COUNT(nc.news_comments_id) AS total_comments
							FROM ' . NEWS_COMMENTS . ' nc
							WHERE nc.news_id = ' . $news_data[$i]['news_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$unread = true;
			}
			
			//	Sind Einträge nun vorhanden werden sie in die Variable
			//	gespeichert und die Anzahl der Beiträge in $count gelagert
			$news_data_unread = $db->sql_fetchrow($result);
			
			if ( $news_data_unread['total_comments'] != '0' || $unread )
			{
				$count		= $news_data_unread['total_comments'];
				$language	= ( $count >= 1 ) ? $lang['comment'] : $lang['comments'];
				$news_title	= (strlen($news_data[$i]['news_title']) < 25) ? $news_data[$i]['news_title'] : substr($news_data[$i]['news_title'], 0, 22) . '...';
								
				$template->assign_block_vars('lobby_news_new_row', array(
						'NEWS_NAME'		=> $news_title,
						'NEWS_COMMENTS'	=> ( $count ) ? '<a href="' . append_sid('news.php?mode=view&amp;' . POST_NEWS_URL . '=' . $news_data[$i]['news_id']) . '">' .  sprintf($language, $count) . '</a>' : '<a href="' . append_sid('news.php?mode=view&amp;' . POST_NEWS_URL . '=' . $news_data[$i]['news_id']) . '">Ungelesen</a>',
				));
			}
		}
	}
	
	//	Fehler enthalten bei den Abfragen, bei News sind diese nicht mehr vorhanden!
	//	Clanwars/Clanwarskommentare
	if ( $userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN )
	{
		$sql = 'SELECT m.* FROM ' . MATCH . " m ORDER BY m.match_date";
		$match_data = _cached($sql, 'lobby_match_details_member');
	}
	else
	{
		$sql = 'SELECT m.* FROM ' . MATCH . ' m  WHERE match_public = 1 ORDER BY m.match_date';
		$match_data = _cached($sql, 'lobby_match_details_guest');
	}
	
	if ( $match_data )
	{
		//	Schleife zum durchlaufen alle Wars
		//	bei hoher Anzahl längere Wartezeit
		for ( $i = 0; $i < count($match_data); $i++ )
		{
			//	Aussortierung, Nicht Abgelaufene Wars werden
			//	Angezeigt bis Sie abgelaufen sind
			if ( $match_data[$i]['match_date'] > time() )
			{
				//	Überprüfung ob der Benutzer die Kommentare gesehen hat
				$sql = 'SELECT read_time
							FROM ' . MATCH_COMMENTS_READ . '
							WHERE user_id = ' . $userdata['user_id'] . ' AND match_id = ' . $match_data[$i]['match_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ($db->sql_numrows($result))
				{
					//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
					//	die seit dem letzten mal Lesen geschrieben wurden
					$sql = 'SELECT COUNT(mc.match_comments_id) AS total_comments
								FROM ' . MATCH_COMMENTS . ' mc
									LEFT JOIN ' . MATCH_COMMENTS_READ . ' mcr ON mc.match_id = mcr.match_id
								WHERE mc.match_id = ' . $match_data[$i]['match_id'] . ' AND mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.read_time < mc.time_create';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					//	Wenn noch kein Eintrag vorhanden ist,
					//	Zählt er alle Beiträge die in diesem
					//	Thema geschrieben wurden
					$sql = 'SELECT COUNT(mc.match_comments_id) AS total_comments
								FROM ' . MATCH_COMMENTS . ' mc
								WHERE mc.match_id = ' . $match_data[$i]['match_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				//	Sind Einträge nun vorhanden werden sie in die Variable
				//	gespeichert und die Anzahl der Beiträge in $count gelagert
				$match_data_unread = $db->sql_fetchrowset($result);
				
				foreach ($match_data_unread as $data )
				{
					$count = $data['total_comments'];
				}
				
				$language = ( $count <= 1 ) ? $lang['comment'] : $lang['comments'];
				
				$template->assign_block_vars('lobby_match_new_row', array(
						'MATCH_NAME'		=> $match_data[$i]['match_rival'],
						'MATCH_COMMENTS'	=> ( $count ) ? '<a href="' . append_sid('match.php?mode=matchdetails&amp;' . POST_MATCH_URL . '=' . $match_data[$i]['match_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
				));
			}
			else if ( $match_data[$i]['match_date'] < time() )
			{
				//	s.o. alles das gleiche Schema nur das die
				//	Wars abgelaufen sind und nur aufgelistet
				//	werden, wenn Kommentare dazu geschrieben werden
				$sql = 'SELECT read_time
							FROM ' . MATCH_COMMENTS_READ . '
							WHERE user_id = ' . $userdata['user_id'] . ' AND match_id = ' . $match_data[$i]['match_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ($db->sql_numrows($result))
				{		
					$sql = 'SELECT COUNT(mc.match_comments_id) AS total_comments
								FROM ' . MATCH_COMMENTS . ' mc
									LEFT JOIN ' . MATCH_COMMENTS_READ . ' mcr ON mc.match_id = mcr.match_id
								WHERE mc.match_id = ' . $match_data[$i]['match_id'] . ' AND mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.read_time < mc.time_create';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					$sql = 'SELECT COUNT(mc.match_comments_id) AS total_comments
								FROM ' . MATCH_COMMENTS . ' mc
								WHERE mc.match_id = ' . $match_data[$i]['match_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				$match_data_unread = $db->sql_fetchrowset($result);
					
				foreach ($match_data_unread as $data )
				{
					$count = $data['total_comments'];
				}
				
				if ( $count )
				{
					$language = ( $count <= 1 ) ? $lang['comment'] : $lang['comments'];
					
					$template->assign_block_vars('lobby_match_old', array());
					$template->assign_block_vars('lobby_match_old.lobby_match_old_row', array(
							'MATCH_NAME'		=> $match_data[$i]['match_rival'],
							'MATCH_COMMENTS'	=> ( $count ) ? '<a href="' . append_sid('match.php?mode=matchdetails&amp;' . POST_MATCH_URL . '=' . $match_data[$i]['match_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
						
					));
				}

			}	//	Teilung in Alt und Neu
		}	//	Schleife
	}	//	Abfrage ob Daten vorhanden sind
	
	//	Training/Trainingskommentare
	if ( $userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN )
	{
		$template->assign_block_vars('lobby_training', array());
		
		$sql = 'SELECT m.* FROM ' . TRAINING . " m ORDER BY m.training_start";
		$training_data = _cached($sql, 'lobby_training_details');
	
		if ( $training_data )
		{
			//	Schleife zum durchlaufen alle Wars
			//	bei hoher Anzahl längere Wartezeit
			for ( $i = 0; $i < count($training_data); $i++ )
			{
				//	Aussortierung, Nicht Abgelaufene Wars werden
				//	Angezeigt bis Sie abgelaufen sind
				if ( $training_data[$i]['training_start'] > time() )
				{
					//	Überprüfung ob der Benutzer die Kommentare gesehen hat
					$sql = 'SELECT read_time
								FROM ' . TRAINING_COMMENTS_READ . '
								WHERE user_id = ' . $userdata['user_id'] . ' AND training_id = ' . $training_data[$i]['training_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					if ($db->sql_numrows($result))
					{
						//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
						//	die seit dem letzten mal Lesen geschrieben wurden
						$sql = 'SELECT COUNT(mc.training_comments_id) AS total_comments
									FROM ' . TRAINING_COMMENTS . ' mc
										LEFT JOIN ' . TRAINING_COMMENTS_READ . ' mcr ON mc.training_id = mcr.training_id
									WHERE mc.training_id = ' . $training_data[$i]['training_id'] . ' AND mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.read_time < mc.time_create';
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					else
					{
						//	Wenn noch kein Eintrag vorhanden ist,
						//	Zählt er alle Beiträge die in diesem
						//	Thema geschrieben wurden
						$sql = 'SELECT COUNT(mc.training_comments_id) AS total_comments
									FROM ' . TRAINING_COMMENTS . ' mc
									WHERE mc.training_id = ' . $training_data[$i]['training_id'];
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					//	Sind Einträge nun vorhanden werden sie in die Variable
					//	gespeichert und die Anzahl der Beiträge in $count gelagert
					$training_data_unread = $db->sql_fetchrowset($result);
					
					foreach ($training_data_unread as $data )
					{
						$count = $data['total_comments'];
					}

					$language = ( $count <= 1 ) ? $lang['comment'] : $lang['comments'];
					
					$template->assign_block_vars('lobby_training.lobby_training_new_row', array(
							'TRAINING_NAME'		=> $training_data[$i]['training_vs'],
							'TRAINING_COMMENTS'	=> ( $count ) ? '<a href="' . append_sid('match.php?mode=matchdetails&amp;' . POST_TRAINING_URL . '=' . $training_data[$i]['training_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
					));
				}
				else if ( $training_data[$i]['training_start'] < time() )
				{
					//	s.o. alles das gleiche Schema nur das die
					//	Wars abgelaufen sind und nur aufgelistet
					//	werden, wenn Kommentare dazu geschrieben werden
					$sql = 'SELECT read_time
								FROM ' . TRAINING_COMMENTS_READ . '
								WHERE user_id = ' . $userdata['user_id'] . ' AND training_id = ' . $training_data[$i]['training_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					if ($db->sql_numrows($result))
					{		
						$sql = 'SELECT COUNT(mc.training_comments_id) AS total_comments
									FROM ' . TRAINING_COMMENTS . ' mc
										LEFT JOIN ' . TRAINING_COMMENTS_READ . ' mcr ON mc.training_id = mcr.training_id
									WHERE mc.training_id = ' . $training_data[$i]['training_id'] . ' AND mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.read_time < mc.time_create';
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					else
					{
						$sql = 'SELECT COUNT(mc.training_comments_id) AS total_comments
									FROM ' . TRAINING_COMMENTS . ' mc
									WHERE mc.training_id = ' . $training_data[$i]['training_id'];
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					$training_data_unread = $db->sql_fetchrowset($result);
											
					if ( $count )
					{
						$language = ( $count <= 1 ) ? $lang['comment'] : $lang['comments'];
						
						$template->assign_block_vars('lobby_training.lobby_training_old', array());
						$template->assign_block_vars('lobby_training.lobby_training_old.lobby_training_old_row', array(
								'TRAINING_NAME'		=> $training_data[$i]['training_vs'],
								'TRAINING_COMMENTS'	=> ( $count ) ? '<a href="' . append_sid('match.php?mode=matchdetails&amp;' . POST_TRAINING_URL . '=' . $training_data[$i]['training_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
							
						));
					}
				}	//	Teilung in Alt und Neu
			}	//	Schleife
		}	//	Abfrage ob Daten vorhanden sind
	}
	
	$template->assign_vars(array(
		'L_UCP'				=> $lang['ucp_main'],
		'L_STATUS'			=> $lang['status'],
		'L_STATUS_YES'		=> $lang['status_yes'],
		'L_STATUS_NO'		=> $lang['status_no'],
		'L_STATUS_REPLACE'	=> $lang['status_replace'],
		'L_SET_STATUS'		=> $lang['set_status']
	));
}
else
{
	if ( !$userdata['session_logged_in'] )
	{
		redirect(append_sid('login.php?redirect=ucp.php'));
	}
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>