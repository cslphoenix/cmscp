<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//
//	Start session management
//
$userdata = session_pagestart($user_ip, PAGE_CALENDAR);
init_userprefs($userdata);

$page_title = $lang['Index'];
include($root_path . 'includes/page_header.php');
$template->set_filenames(array('body' => 'userlobby_body.tpl'));

if ( $userdata['session_logged_in'] )
{
	//	Clanwars/Clanwarskommentare
	if ( $userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN )
	{
		$sql = 'SELECT m.* FROM ' . MATCH_TABLE . " m ORDER BY m.match_date";
		$match_data = _cached($sql, 'lobby_match_details_member');
	}
	else
	{
		$sql = 'SELECT m.* FROM ' . MATCH_TABLE . ' m  WHERE match_public = 1 ORDER BY m.match_date';
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
				$sql = 'SELECT mcr.read_time
							FROM ' . MATCH_COMMENTS_TABLE . ' mc
								LEFT JOIN ' . MATCH_COMMENTS_READ_TABLE . ' mcr ON mc.match_id = mcr.match_id
							WHERE mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.match_id = ' . $match_data[$i]['match_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ($db->sql_numrows($result))
				{
					//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
					//	die seit dem letzten mal Lesen geschrieben wurden
					$sql = 'SELECT COUNT(mc.match_comments_id) AS total_comments, mc.match_id, mc.time_create, mcr.read_time
								FROM ' . MATCH_COMMENTS_TABLE . ' mc
									LEFT JOIN ' . MATCH_COMMENTS_READ_TABLE . ' mcr ON mc.match_id = mcr.match_id
								WHERE mc.match_id = ' . $match_data[$i]['match_id'] . ' AND mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.read_time < mc.time_create
							GROUP BY mc.match_id';
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
								FROM ' . MATCH_COMMENTS_TABLE . ' mc
								WHERE mc.match_id = ' . $match_data[$i]['match_id'] . '
							GROUP BY mc.match_id';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				if ($db->sql_numrows($result))
				{
					//	Sind Einträge nun vorhanden werden sie in die Variable
					//	gespeichert und die Anzahl der Beiträge in $count gelagert
					$match_data_unread = $db->sql_fetchrowset($result);
					
					foreach ($match_data_unread as $data )
					{
						$count = $data['total_comments'];
					}
				}
				else
				{
					//	Notwendig, da sonst Noicemeldungen kommen
					$match_data_unread = '';
					$count = '';
				}
				
				$language = ( $count <= 1 ) ? $lang['comment'] : $lang['comments'];
				
				$template->assign_block_vars('lobby_match_new_row', array(
						'MATCH_NAME'		=> $match_data[$i]['match_rival'],
						'MATCH_COMMENTS'	=> ( $count ) ? '<a href="' . append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=".$match_data[$i]['match_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
					
				));
			}
			else if ( $match_data[$i]['match_date'] < time() )
			{
				//	s.o. alles das gleiche Schema nur das die
				//	Wars abgelaufen sind und nur aufgelistet
				//	werden, wenn Kommentare dazu geschrieben werden
				$sql = 'SELECT mcr.read_time
							FROM ' . MATCH_COMMENTS_TABLE . ' mc
								LEFT JOIN ' . MATCH_COMMENTS_READ_TABLE . ' mcr ON mc.match_id = mcr.match_id
							WHERE mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.match_id = ' . $match_data[$i]['match_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				if ($db->sql_numrows($result))
				{		
					$sql = 'SELECT COUNT(mc.match_comments_id) AS total_comments, mc.match_id, mc.time_create, mcr.read_time
								FROM ' . MATCH_COMMENTS_TABLE . ' mc
									LEFT JOIN ' . MATCH_COMMENTS_READ_TABLE . ' mcr ON mc.match_id = mcr.match_id
								WHERE mc.match_id = ' . $match_data[$i]['match_id'] . ' AND mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.read_time < mc.time_create
							GROUP BY mc.match_id';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					$sql = 'SELECT COUNT(mc.match_comments_id) AS total_comments
								FROM ' . MATCH_COMMENTS_TABLE . ' mc
								WHERE mc.match_id = ' . $match_data[$i]['match_id'] . '
							GROUP BY mc.match_id';
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				if ($db->sql_numrows($result))
				{
					$match_data_unread = $db->sql_fetchrowset($result);
					
					foreach ($match_data_unread as $data )
					{
						$count = $data['total_comments'];
					}
				}
				else
				{
					$match_data_unread = '';
					$count = '';
				}
				
				if ( $count )
				{
					$template->assign_block_vars('lobby_match_old', array());
					
					$language = ( $count <= 1 ) ? $lang['comment'] : $lang['comments'];
					
					$template->assign_block_vars('lobby_match_old.lobby_match_old_row', array(
							'MATCH_NAME'		=> $match_data[$i]['match_rival'],
							'MATCH_COMMENTS'	=> ( $count ) ? '<a href="' . append_sid("match.php?mode=matchdetails&amp;" . POST_MATCH_URL . "=".$match_data[$i]['match_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
						
					));
				}

			}	//	Teilung in Alt und Neu
		}	//	Schleife
	}	//	Abfrage ob Daten vorhanden sind
	
	//	Clanwars/Clanwarskommentare
	if ( $userdata['user_level'] == TRAIL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN )
	{
		$template->assign_block_vars('lobby_training', array());
		
		$sql = 'SELECT m.* FROM ' . TRAINING_TABLE . " m ORDER BY m.training_start";
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
					$sql = 'SELECT mcr.read_time
								FROM ' . TRAINING_COMMENTS_TABLE . ' mc
									LEFT JOIN ' . TRAINING_COMMENTS_READ_TABLE . ' mcr ON mc.training_id = mcr.training_id
								WHERE mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.training_id = ' . $training_data[$i]['training_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					if ($db->sql_numrows($result))
					{
						//	Wenn ja, dann Zählt er alle Neuen Beiträge auf
						//	die seit dem letzten mal Lesen geschrieben wurden
						$sql = 'SELECT COUNT(mc.training_comments_id) AS total_comments, mc.training_id, mc.time_create, mcr.read_time
									FROM ' . TRAINING_COMMENTS_TABLE . ' mc
										LEFT JOIN ' . TRAINING_COMMENTS_READ_TABLE . ' mcr ON mc.training_id = mcr.training_id
									WHERE mc.training_id = ' . $training_data[$i]['training_id'] . ' AND mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.read_time < mc.time_create
								GROUP BY mc.training_id';
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
									FROM ' . TRAINING_COMMENTS_TABLE . ' mc
									WHERE mc.training_id = ' . $training_data[$i]['training_id'] . '
								GROUP BY mc.training_id';
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					if ($db->sql_numrows($result))
					{
						//	Sind Einträge nun vorhanden werden sie in die Variable
						//	gespeichert und die Anzahl der Beiträge in $count gelagert
						$training_data_unread = $db->sql_fetchrowset($result);
						
						foreach ($training_data_unread as $data )
						{
							$count = $data['total_comments'];
						}
					}
					else
					{
						//	Notwendig, da sonst Noicemeldungen kommen
						$training_data_unread = '';
						$count = '';
					}
					
					$language = ( $count <= 1 ) ? $lang['comment'] : $lang['comments'];
					
					$template->assign_block_vars('lobby_training.lobby_training_new_row', array(
							'TRAINING_NAME'		=> $training_data[$i]['training_vs'],
							'TRAINING_COMMENTS'	=> ( $count ) ? '<a href="' . append_sid("match.php?mode=matchdetails&amp;" . POST_TRAINING_URL . "=".$training_data[$i]['training_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
						
					));
				}
				else if ( $training_data[$i]['training_start'] < time() )
				{
					//	s.o. alles das gleiche Schema nur das die
					//	Wars abgelaufen sind und nur aufgelistet
					//	werden, wenn Kommentare dazu geschrieben werden
					$sql = 'SELECT mcr.read_time
								FROM ' . TRAINING_COMMENTS_TABLE . ' mc
									LEFT JOIN ' . TRAINING_COMMENTS_READ_TABLE . ' mcr ON mc.training_id = mcr.training_id
								WHERE mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.training_id = ' . $training_data[$i]['training_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					if ($db->sql_numrows($result))
					{		
						$sql = 'SELECT COUNT(mc.training_comments_id) AS total_comments, mc.training_id, mc.time_create, mcr.read_time
									FROM ' . TRAINING_COMMENTS_TABLE . ' mc
										LEFT JOIN ' . TRAINING_COMMENTS_READ_TABLE . ' mcr ON mc.training_id = mcr.training_id
									WHERE mc.training_id = ' . $training_data[$i]['training_id'] . ' AND mcr.user_id = ' . $userdata['user_id'] . ' AND mcr.read_time < mc.time_create
								GROUP BY mc.training_id';
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					else
					{
						$sql = 'SELECT COUNT(mc.training_comments_id) AS total_comments
									FROM ' . TRAINING_COMMENTS_TABLE . ' mc
									WHERE mc.training_id = ' . $training_data[$i]['training_id'] . '
								GROUP BY mc.training_id';
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					if ($db->sql_numrows($result))
					{
						$training_data_unread = $db->sql_fetchrowset($result);
						
						foreach ($training_data_unread as $data )
						{
							$count = $data['total_comments'];
						}
					}
					else
					{
						$training_data_unread = '';
						$count = '';
					}
					
					if ( $count )
					{
						$language = ( $count <= 1 ) ? $lang['comment'] : $lang['comments'];
						
						$template->assign_block_vars('lobby_training.lobby_training_old', array());
						$template->assign_block_vars('lobby_training.lobby_training_old.lobby_training_old_row', array(
								'TRAINING_NAME'		=> $training_data[$i]['training_vs'],
								'TRAINING_COMMENTS'	=> ( $count ) ? '<a href="' . append_sid("match.php?mode=matchdetails&amp;" . POST_TRAINING_URL . "=".$training_data[$i]['training_id']) . '">' .  sprintf($language, $count) . '</a>' : '',
							
						));
					}
	
				}	//	Teilung in Alt und Neu
			}	//	Schleife
		}	//	Abfrage ob Daten vorhanden sind
	}
}
else
{
	message_die(GENERAL_ERROR, $lang['training_denied']);
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>