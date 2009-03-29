<?php

/***

	
	admin_news.php
	
	Erstellt von Phoenix
	
	
***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($userdata['auth_news'] || $userdata['user_level'] == ADMIN)
	{
		$module['main']['news'] = $filename;
	}
	return;
}
else
{
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	include($root_path . 'includes/functions_selects.php');
	
	if (!$userdata['auth_news'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_news.php", true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	
	//	ID Abfrage
	if ( isset($HTTP_POST_VARS[POST_NEWS_URL]) || isset($HTTP_GET_VARS[POST_NEWS_URL]) )
	{
		$news_id = ( isset($HTTP_POST_VARS[POST_NEWS_URL]) ) ? intval($HTTP_POST_VARS[POST_NEWS_URL]) : intval($HTTP_GET_VARS[POST_NEWS_URL]);
	}
	else
	{
		$news_id = 0;
	}
	
	//	mode Abfrage
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		$mode = '';
	}
	
	$show_index = '';
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
				
				if ( $mode == 'edit' )
				{
					//	Infos der Spiele
					$sql = 'SELECT  n.*, nc.*
								FROM ' . NEWS_TABLE . ' n
									LEFT JOIN ' . NEWS_CATEGORIE_TABLE . ' nc ON n.news_categorie = nc.news_categorie_id
								WHERE news_id = ' . $news_id;
					$result = $db->sql_query($sql);
			
					if ( !($news = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['news_not_exist']);
					}
			
					$new_mode = 'editnews';
				}
				else if ( $mode == 'add' )
				{
					//	Start Werte setzen
					$news = array (
						'news_title'		=> ( isset($HTTP_POST_VARS['news_title']) ) ? trim($HTTP_POST_VARS['news_title']) : '',
						'news_categorie'	=> '0',
						'news_text'			=> '',
						'news_url1'			=> '',
						'news_link1'		=> '',
						'news_url2'			=> '',
						'news_link2'		=> '',
						'user_id'			=> '',
						'match_id'			=> '',
						'news_time_create'	=> time(),
						'news_time_update'	=> '',
						'news_time_public'	=> time(),
						'news_public'		=> '',
						'news_intern'		=> '0',
						'news_comments'		=> '1',
						'news_rating'		=> '0',
					);

					$new_mode = 'addnews';
				}
				//	Template definieren
				$template->set_filenames(array('body' => './../admin/style/news_edit_body.tpl'));

				//	Unsichtbare Felder für andere Infos
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_NEWS_URL . '" value="' . $news_id . '" />';

				//	Variablen zur Ausgabe
				$template->assign_vars(array(
					'L_NEWS_HEAD'			=> $lang['news_head'],
					'L_NEWS_NEW_EDIT'		=> ($mode == 'add') ? $lang['news_add'] : $lang['news_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_NEWS_NAME'			=> $lang['news_title'],
					'L_NEWS_KAT'			=> $lang['news_categorie'],
					'L_NEWS_TEXT'			=> $lang['news_text'],
					'L_NEWS_LINK'			=> $lang['news_link'],
					'L_NEWS_LINK_EXPLAIN'	=> $lang['news_link_explain'],
					'L_NEWS_PUBLIC_TIME'	=> $lang['news_public_time'],
					'L_NEWS_PUBLIC'			=> $lang['news_public'],
					'L_NEWS_INTERN'			=> $lang['news_intern'],
					'L_NEWS_COMMENTS'		=> $lang['news_comments'],
									
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'NEWS_TITLE'			=> $news['news_title'],
					'NEWS_TEXT'				=> html_entity_decode($news['news_text'], ENT_QUOTES),
					'NEWSCAT_IMAGE'			=> ($mode == 'add') ? $root_path . 'images/spacer.gif' : $root_path . $settings['news_categorie_path'] . '/' . $news['news_categorie_image'],

					'NEWS_LINK1'			=> $news['news_link1'],
					'NEWS_LINK2'			=> $news['news_link2'],
					'NEWS_URL1'				=> $news['news_url1'],
					'NEWS_URL2'				=> $news['news_url2'],
					
					'S_CHECKED_RATING_YES'		=> ( $news['news_rating'] ) ? ' checked="checked"' : '',
					'S_CHECKED_RATING_NO'		=> ( !$news['news_rating'] ) ? ' checked="checked"' : '',
					'S_CHECKED_PUBLIC_YES'		=> ( $news['news_public'] ) ? ' checked="checked"' : '',
					'S_CHECKED_PUBLIC_NO'		=> ( !$news['news_public'] ) ? ' checked="checked"' : '',
					'S_CHECKED_COMMENTS_YES'	=> ( $news['news_comments'] ) ? ' checked="checked"' : '',
					'S_CHECKED_COMMENTS_NO'		=> ( !$news['news_comments'] ) ? ' checked="checked"' : '',
					'S_CHECKED_INTERN_YES'		=> ( $news['news_intern'] ) ? ' checked="checked"' : '',
					'S_CHECKED_INTERN_NO'		=> ( !$news['news_intern'] ) ? ' checked="checked"' : '',
					
					'S_DAY'					=> _select_date('day', 'day',		date('d', $news['news_time_public'])),
					'S_MONTH'				=> _select_date('month', 'month',	date('m', $news['news_time_public'])),
					'S_YEAR'				=> _select_date('year', 'year',		date('Y', $news['news_time_public'])),
					'S_HOUR'				=> _select_date('hour', 'hour',		date('H', $news['news_time_public'])),
					'S_MIN'					=> _select_date('min', 'min',		date('i', $news['news_time_public'])),
					
					'NEWSCAT_PATH'			=> $root_path . $settings['news_categorie_path'],
					
					'S_NEWSCAT_LIST'		=> _select_newscat($news['news_categorie']),
					'S_NEWS_MATCH_LIST'		=> _select_match($news['match_id'], '1', 'post'),
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_TEAM_ACTION'			=> append_sid("admin_news.php")
				));
			
				// Template ausgabe
				$template->pparse('body');
				
			break;
			
			case 'addnews':

				$news_title			= ( isset($HTTP_POST_VARS['news_title']) )	? trim($HTTP_POST_VARS['news_title']) : '';
				$news_categorie		= ( isset($HTTP_POST_VARS['news_categorie_image']) )	? trim($HTTP_POST_VARS['news_categorie_image']) : '';
				$news_text			= ( isset($HTTP_POST_VARS['news_text']) )	? htmlentities($HTTP_POST_VARS['news_text'], ENT_QUOTES) : '';
				$news_url1			= ( isset($HTTP_POST_VARS['news_url1']) )	? trim($HTTP_POST_VARS['news_url1']) : '';
				$news_link1			= ( isset($HTTP_POST_VARS['news_link1']) )	? trim($HTTP_POST_VARS['news_link1']) : '';
				$news_url2			= ( isset($HTTP_POST_VARS['news_url2']) )	? trim($HTTP_POST_VARS['news_url2']) : '';
				$news_link2			= ( isset($HTTP_POST_VARS['news_link2']) )	? trim($HTTP_POST_VARS['news_link2']) : '';
				$match_id			= ( isset($HTTP_POST_VARS['match_id']) )	? intval($HTTP_POST_VARS['match_id']) : '';
				$user_id			= $userdata['user_id'];
				$news_time_public	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				$news_public		= ( $HTTP_POST_VARS['news_public'] == 1 )	? 1 : 0;
				$news_intern		= ( $HTTP_POST_VARS['news_intern'] == 1 )	? 1 : 0;
				$news_rating		= ( $HTTP_POST_VARS['news_rating'] == 1 )	? 1 : 0;
				
				if ( !empty($news_categorie) )
				{
					$sql = 'SELECT news_categorie_id FROM ' . NEWS_CATEGORIE_TABLE . " WHERE news_categorie_image = '$news_categorie'";
					$result = $db->sql_query($sql);
					
					if (!($news_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
					
					$news_categorie = ($news_info['news_categorie_id']) ? $news_info['news_categorie_id'] : '0';
				}
				else
				{
					$news_categorie = '0';
				}
				
				if( $news_title == '' )
				{
					message_die(GENERAL_ERROR, $lang['news_not_exist'], '', __LINE__, __FILE__);
				}
	
				// There is no problem having duplicate forum names so we won't check for it.
				$sql = 'INSERT INTO ' . NEWS_TABLE . " (news_title, news_categorie, news_text, news_url1, news_link1, news_url2, news_link2, user_id, match_id, news_time_create, news_time_public, news_public, news_intern, news_rating)
					VALUES ('" . $news_title . "', $news_categorie, '" . $news_text . "', '" . $news_url1 . "', '" . $news_link1 . "', '" . $news_url2 . "', '" . $news_link2 . "', $user_id, $match_id, '" . time() . "', $news_time_public, $news_public, $news_intern, $news_rating)";
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
				}
				
				$news_id_start = $db->sql_nextid();
				
				$sql = 'INSERT INTO ' . NEWS_COMMENTS_READ_TABLE . ' (news_id, user_id, read_time)
					VALUES (' . $news_id_start . ', ' . $userdata['user_id'] . ', ' . time() . ')';
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_news_add', $news_title);
	
				$message = $lang['news_create'] . '<br /><br />' . sprintf($lang['click_return_news'], "<a href=\"" . append_sid("admin_news.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editnews':
			
				$news_title			= ( isset($HTTP_POST_VARS['news_title']) )	? trim($HTTP_POST_VARS['news_title']) : '';
				$news_categorie		= ( isset($HTTP_POST_VARS['news_categorie_image']) )	? trim($HTTP_POST_VARS['news_categorie_image']) : '';
				$news_text			= ( isset($HTTP_POST_VARS['news_text']) )	? htmlentities($HTTP_POST_VARS['news_text'], ENT_QUOTES) : '';
				$news_url1			= ( isset($HTTP_POST_VARS['news_url1']) )	? trim($HTTP_POST_VARS['news_url1']) : '';
				$news_link1			= ( isset($HTTP_POST_VARS['news_link1']) )	? trim($HTTP_POST_VARS['news_link1']) : '';
				$news_url2			= ( isset($HTTP_POST_VARS['news_url2']) )	? trim($HTTP_POST_VARS['news_url2']) : '';
				$news_link2			= ( isset($HTTP_POST_VARS['news_link2']) )	? trim($HTTP_POST_VARS['news_link2']) : '';
				$match_id			= ( isset($HTTP_POST_VARS['match_id']) )	? intval($HTTP_POST_VARS['match_id']) : '';
				$news_time_public	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				$news_public		= ( $HTTP_POST_VARS['news_public'] == 1 )	? 1 : 0;
				$news_intern		= ( $HTTP_POST_VARS['news_intern'] == 1 )	? 1 : 0;
				$news_rating		= ( $HTTP_POST_VARS['news_rating'] == 1 )	? 1 : 0;
				
				if ( !empty($news_categorie) )
				{
					$sql = 'SELECT * FROM ' . NEWS_CATEGORIE_TABLE . " WHERE news_categorie_image = '$news_categorie'";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					if (!($news_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
					
					$news_categorie = ($news_info['news_categorie_id']) ? $news_info['news_categorie_id'] : '0';
				}
				else
				{
					$news_categorie = '0';
				}
				
				if( $news_title == '' )
				{
					message_die(GENERAL_MESSAGE, $lang['news_not_exist'], '', __LINE__, __FILE__);
				}

//				$log_data = ($team_info['team_name'] != $HTTP_POST_VARS['team_name']) ? $team_info['team_name'] . '>' . str_replace("\'", "''", $HTTP_POST_VARS['team_name'])  : '';
				$sql = "UPDATE " . NEWS_TABLE . " SET
							news_title			= '" . $news_title . "',
							news_categorie			= $news_categorie,
							news_text			= '" . $news_text . "',
							news_url1			= '" . $news_url1 . "',
							news_link1			= '" . $news_link1 . "',
							news_url2			= '" . $news_url2 . "',
							news_link2			= '" . $news_link2 . "',
							match_id			= $match_id,
							news_time_public	= $news_time_public,
							news_public			= $news_public,
							news_intern			= $news_intern,
							news_rating			= $news_rating,
							news_time_update	= '" . time() . "'
						WHERE news_id = " . intval($HTTP_POST_VARS[POST_NEWS_URL]);
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_news_edit');
				
				$message = $lang['news_update'] . '<br /><br />' . sprintf($lang['click_return_news'], "<a href=\"" . append_sid("admin_news.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $news_id && $confirm )
				{	
					$sql = 'SELECT * FROM ' . NEWS_TABLE . " WHERE news_id = $news_id";
					$result = $db->sql_query($sql);
			
					if ( !($team_info = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['news_not_exist']);
					}
				
					$sql = 'DELETE FROM ' . NEWS_TABLE . " WHERE news_id = $news_id";
					$result = $db->sql_query($sql);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, ACP_NEWS_DELETE, $team_info['news_title']);
					
					$message = $lang['team_delete'] . '<br /><br />' . sprintf($lang['click_return_news'], "<a href=\"" . append_sid("admin_news.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $news_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_NEWS_URL . '" value="' . $news_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_news'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_news.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['must_select_news']);
				}
				
				$template->pparse("body");
				
				break;
			
			default:
				message_die(GENERAL_ERROR, 'kein modul');
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/news_body.tpl'));
			
	$template->assign_vars(array(
		'L_NEWS_TITLE'			=> $lang['news_head'],
		'L_NEWS_EXPLAIN'		=> $lang['news_explain'],
		
		'L_NEWS_ADD'			=> $lang['news_add'],
		
		'L_SETTING'				=> $lang['setting'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'L_MOVE_UP'				=> $lang['Move_up'], 
		'L_MOVE_DOWN'			=> $lang['Move_down'], 
		
		'ICON_MOVE_UP'			=> '<img src="./../admin/style/images/icon_arrow_up.png" alt="Up" title="" width="12" height="12" />',
		'ICON_MOVE_DOWN'		=> '<img src="./../admin/style/images/icon_arrow_down.png" alt="Down" title="" width="12" height="12" />',
		
		'S_TEAM_ACTION'		=> append_sid("admin_news.php")
	));
	
	$sql = 'SELECT * FROM ' . NEWS_TABLE . ' ORDER BY news_id DESC';
	$result = $db->sql_query($sql);
	$news_data = $db->sql_fetchrowset($result);
	
	if ( !$news_data )
	{
		$template->assign_block_vars('no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['entry_per_page'] + $start, count($news_data)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$template->assign_block_vars('news_row', array(
				'CLASS' 		=> $class,
				'NAME'			=> ( $news_data[$i]['news_intern'] ) ? '<em><b>' . $news_data[$i]['news_title'] . '</b></em>' : $news_data[$i]['news_title'],
				
				'U_PUBLIC'		=> append_sid("admin_news.php?mode=public&amp;" . POST_NEWS_URL . "=" . $news_data[$i]['news_id']),
				'U_DELETE'		=> append_sid("admin_news.php?mode=delete&amp;" . POST_NEWS_URL . "=" . $news_data[$i]['news_id']),
				'U_EDIT'		=> append_sid("admin_news.php?mode=edit&amp;" . POST_NEWS_URL . "=" . $news_data[$i]['news_id']),
			));
		}
	}
	
	$template->pparse("body");
			
	include('./page_footer_admin.php');
}
?>