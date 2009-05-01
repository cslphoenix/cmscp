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

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_news'] || $userdata['user_level'] == ADMIN)
	{
		$module['news']['news'] = $filename;
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
	include($root_path . 'includes/functions_admin.php');
	include($root_path . 'includes/functions_selects.php');
	
	if (!$userauth['auth_news'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid("admin_news.php", true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_NEWS_URL]) || isset($HTTP_GET_VARS[POST_NEWS_URL]) )
	{
		$news_id = ( isset($HTTP_POST_VARS[POST_NEWS_URL]) ) ? intval($HTTP_POST_VARS[POST_NEWS_URL]) : intval($HTTP_GET_VARS[POST_NEWS_URL]);
	}
	else
	{
		$news_id = 0;
	}
	
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
	
	if ( !empty($mode) )
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
			
				if ( $mode == 'edit' )
				{
					$news		= get_data('news_newscat', $news_id, 2 );
					$new_mode	= 'editnews';
				}
				else if ( $mode == 'add' )
				{
					$news = array (
						'news_title'		=> ( isset($HTTP_POST_VARS['news_title']) ) ? trim($HTTP_POST_VARS['news_title']) : '',
						'news_category'		=> '0',
						'news_text'			=> '',
						'news_url'			=> '',
						'news_link'			=> '',
						'user_id'			=> '',
						'match_id'			=> '',
						'news_time_create'	=> time(),
						'news_time_update'	=> '',
						'news_time_public'	=> time(),
						'news_public'		=> '0',
						'news_intern'		=> '0',
						'news_comments'		=> '1',
						'news_rating'		=> '0',
					);

					$new_mode = 'addnews';
				}

				$template->set_filenames(array('body' => './../admin/style/acp_news.tpl'));
				$template->assign_block_vars('news_edit', array());
				
				if ( $userauth['auth_news_public'] || $userdata['user_level'] == ADMIN )
				{
					$template->assign_block_vars('news_edit.public', array());				
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_NEWS_URL . '" value="' . $news_id . '" />';
				
				if ( $news['news_link'] && is_array(unserialize($news['news_link'])) )
				{
					$news_link	= unserialize($news['news_link']);
					$news_url	= unserialize($news['news_url']);
				
					for ( $i = 0; $i < count($news_link); $i++ )
					{
						$template->assign_block_vars('news_edit.link_row', array(
							'NEWS_NAME'	=> $news_link[$i],
							'NEWS_URL'	=> $news_url[$i],
						));
					}
				}
				else
				{
					$template->assign_vars(array(
						'NEWS_NAME'	=> $news['news_link'],
						'NEWS_URL'	=> $news['news_url'],
					));
				}
				
				$template->assign_vars(array(
					'L_NEWS_HEAD'				=> $lang['news_head'],
					'L_NEWS_NEW_EDIT'			=> ($mode == 'add') ? $lang['news_add'] : $lang['news_edit'],
					'L_REQUIRED'				=> $lang['required'],
					
					'L_NEWS_NAME'				=> $lang['news_title'],
					'L_NEWSCAT'					=> $lang['news_category'],
					'L_NEWS_MATCH'				=> $lang['news_match'],
					'L_NEWS_TEXT'				=> $lang['news_text'],
					'L_NEWS_LINK'				=> $lang['news_link'],
					'L_NEWS_LINK_EXPLAIN'		=> $lang['news_link_explain'],
					'L_NEWS_PUBLIC_TIME'		=> $lang['news_public_time'],
					'L_NEWS_PUBLIC'				=> $lang['news_public'],
					'L_NEWS_INTERN'				=> $lang['news_intern'],
					'L_NEWS_COMMENTS'			=> $lang['news_comments'],
					'L_NEWS_RATING'				=> $lang['news_rating'],
									
					'L_SUBMIT'					=> $lang['Submit'],
					'L_RESET'					=> $lang['Reset'],
					'L_YES'						=> $lang['Yes'],
					'L_NO'						=> $lang['No'],
					
					'NEWS_TITLE'				=> $news['news_title'],
					'NEWS_TEXT'					=> html_entity_decode($news['news_text'], ENT_QUOTES),
					'NEWSCAT_IMAGE'				=> ( $mode != 'add' ) ? ( $news['news_category_image'] ) ? $root_path . $settings['path_news_category'] . '/' . $news['news_category_image'] : $images['icon_acp_spacer'] : $images['icon_acp_spacer'],

					'S_CHECKED_RATING_YES'		=> ( $news['news_rating'] ) ? ' checked="checked"' : '',
					'S_CHECKED_RATING_NO'		=> ( !$news['news_rating'] ) ? ' checked="checked"' : '',
					'S_CHECKED_PUBLIC_YES'		=> ( $news['news_public'] ) ? ' checked="checked"' : '',
					'S_CHECKED_PUBLIC_NO'		=> ( !$news['news_public'] ) ? ' checked="checked"' : '',
					'S_CHECKED_COMMENTS_YES'	=> ( $news['news_comments'] ) ? ' checked="checked"' : '',
					'S_CHECKED_COMMENTS_NO'		=> ( !$news['news_comments'] ) ? ' checked="checked"' : '',
					'S_CHECKED_INTERN_YES'		=> ( $news['news_intern'] ) ? ' checked="checked"' : '',
					'S_CHECKED_INTERN_NO'		=> ( !$news['news_intern'] ) ? ' checked="checked"' : '',
					
					'S_DAY'						=> _select_date('day', 'day',		date('d', $news['news_time_public'])),
					'S_MONTH'					=> _select_date('month', 'month',	date('m', $news['news_time_public'])),
					'S_YEAR'					=> _select_date('year', 'year',		date('Y', $news['news_time_public'])),
					'S_HOUR'					=> _select_date('hour', 'hour',		date('H', $news['news_time_public'])),
					'S_MIN'						=> _select_date('min', 'min',		date('i', $news['news_time_public'])),
					
					'NEWSCAT_PATH'				=> $root_path . $settings['path_news_category'],
					
					'S_NEWSCAT_LIST'			=> _select_newscat($news['news_category']),
					'S_NEWS_MATCH_LIST'			=> _select_match($news['match_id'], '1', 'post'),
					
					'S_HIDDEN_FIELDS'			=> $s_hidden_fields,
					'S_NEWS_ACTION'				=> append_sid("admin_news.php")
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addnews':
			
				$news_title			= ( isset($HTTP_POST_VARS['news_title']) )	? trim($HTTP_POST_VARS['news_title']) : '';
				$news_category		= ( isset($HTTP_POST_VARS['news_category_image']) )	? trim($HTTP_POST_VARS['news_category_image']) : '';
				$news_text			= ( isset($HTTP_POST_VARS['news_text']) )	? htmlentities($HTTP_POST_VARS['news_text'], ENT_QUOTES) : '';
				$news_url			= ( isset($HTTP_POST_VARS['news_url']) )	? serialize(array_filter($HTTP_POST_VARS['news_url'])) : '';
				$news_link			= ( isset($HTTP_POST_VARS['news_name']) )	? serialize(array_filter($HTTP_POST_VARS['news_name'])) : '';
				$match_id			= ( isset($HTTP_POST_VARS['match_id']) )	? intval($HTTP_POST_VARS['match_id']) : '';
				$user_id			= $userdata['user_id'];
				$news_time_public	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				$news_public		= ( isset($HTTP_POST_VARS['news_public']) )	? intval($HTTP_POST_VARS['news_public']) : 0;
				$news_intern		= ( $HTTP_POST_VARS['news_intern'] == 1 )	? 1 : 0;
				$news_rating		= ( $HTTP_POST_VARS['news_rating'] == 1 )	? 1 : 0;
				
				if ( !empty($news_category) )
				{
					$sql = 'SELECT news_category_id FROM ' . NEWS_CATEGORY . " WHERE news_category_image = '$news_category'";
					$result = $db->sql_query($sql);
					
					if (!($news_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
					
					$news_category = ($news_info['news_category_id']) ? $news_info['news_category_id'] : '0';
				}
				else
				{
					$news_category = '0';
				}
				
				if ( $news_title == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_title'] . $lang['back'], '');
				}
	
				$sql = 'INSERT INTO ' . NEWS . " (news_title, news_category, news_text, news_url, news_link, user_id, match_id, news_time_create, news_time_public, news_public, news_intern, news_rating)
					VALUES ('" . $news_title . "', $news_category, '" . $news_text . "', '" . $news_url . "', '" . $news_link . "', $user_id, $match_id, '" . time() . "', $news_time_public, $news_public, $news_intern, $news_rating)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
				}
				
				$news_id_start = $db->sql_nextid();
				
				$sql = 'INSERT INTO ' . NEWS_COMMENTS_READ . ' (news_id, user_id, read_time)
					VALUES (' . $news_id_start . ', ' . $userdata['user_id'] . ', ' . time() . ')';
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_news_add', $news_title);
	
				$message = $lang['create_news'] . '<br><br>' . sprintf($lang['click_return_news'], '<a href="' . append_sid("admin_news.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editnews':
			
				$news = get_data('news_newscat', $news_id, 2);
				
				$news_title			= ( isset($HTTP_POST_VARS['news_title']) )	? trim($HTTP_POST_VARS['news_title']) : '';
				$news_category		= ( isset($HTTP_POST_VARS['news_category_image']) )	? trim($HTTP_POST_VARS['news_category_image']) : '';
				$news_text			= ( isset($HTTP_POST_VARS['news_text']) )	? htmlentities($HTTP_POST_VARS['news_text'], ENT_QUOTES) : '';
				$news_url			= ( isset($HTTP_POST_VARS['news_url']) )	? array_filter($HTTP_POST_VARS['news_url']) : '';
				$news_link			= ( isset($HTTP_POST_VARS['news_name']) )	? serialize(array_filter($HTTP_POST_VARS['news_name'])) : '';
				$match_id			= ( isset($HTTP_POST_VARS['match_id']) )	? intval($HTTP_POST_VARS['match_id']) : '';
				$news_time_public	= mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				$news_public		= ( isset($HTTP_POST_VARS['news_public']) )	? intval($HTTP_POST_VARS['news_public']) : $news['news_public'];
				$news_intern		= ( $HTTP_POST_VARS['news_intern'] == 1 )	? 1 : 0;
				$news_rating		= ( $HTTP_POST_VARS['news_rating'] == 1 )	? 1 : 0;
				
				array_walk($news_url, 'set_http');
				
				$news_url = serialize($news_url);
				
				if ( !empty($news_category) )
				{
					$sql = 'SELECT * FROM ' . NEWS_CATEGORY . " WHERE news_category_image = '$news_category'";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					if (!($news_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['team_not_exist']);
					}
					
					$news_category = ($news_info['news_category_id']) ? $news_info['news_category_id'] : '0';
				}
				else
				{
					$news_category = '0';
				}
				
				if ( $news_title == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_title'] . $lang['back'], '');
				}

				$sql = "UPDATE " . NEWS . " SET
							news_title			= '" . $news_title . "',
							news_category			= $news_category,
							news_text			= '" . $news_text . "',
							news_url			= '" . $news_url . "',
							news_link			= '" . $news_link . "',
							match_id			= $match_id,
							news_time_public	= $news_time_public,
							news_public			= $news_public,
							news_intern			= $news_intern,
							news_rating			= $news_rating,
							news_time_update	= '" . time() . "'
						WHERE news_id = " . intval($HTTP_POST_VARS[POST_NEWS_URL]);
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_news_edit');
				
				$message = $lang['update_news'] . '<br><br>' . sprintf($lang['click_return_news'], '<a href="' . append_sid("admin_news.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
				
			case 'public':
			case 'privat':
			
				$news = get_data('news', $news_id, 0);
				
				if ( !$news['news_public'] )
				{
					$sql = "UPDATE " . NEWS . " SET
								news_public = 1
							WHERE news_id = " . $news_id;
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					$sql = "UPDATE " . NEWS . " SET
								news_public = 0
							WHERE news_id = " . $news_id;
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_news_pubblic');
				
				$show_index = TRUE;
			
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $news_id && $confirm )
				{	
					$news = get_data('news', $news_id, 0);
					
					$sql = 'DELETE FROM ' . NEWS . ' WHERE news_id = ' . $news_id;
					$result = $db->sql_query($sql);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_news_delete', $news['news_title']);
					
					$message = $lang['delete_news'] . '<br><br>' . sprintf($lang['click_return_news'], '<a href="' . append_sid("admin_news.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $news_id && !$confirm )
				{
					$template->set_filenames(array('body' => './../admin/style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_NEWS_URL . '" value="' . $news_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_news'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_news.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_news']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
			
				message_die(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_news.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_NEWS_TITLE'		=> $lang['news_head'],
		'L_NEWS_EXPLAIN'	=> $lang['news_explain'],
		'L_NEWS_NAME'		=> $lang['news_name'],
		'L_NEWS_ADD'		=> $lang['news_add'],
		'L_EDIT'			=> $lang['edit'],
		'L_SETTINGS'		=> $lang['settings'],
		'L_DELETE'			=> $lang['delete'],
		'L_MOVE_UP'			=> $lang['Move_up'], 
		'L_MOVE_DOWN'		=> $lang['Move_down'],
		'S_TEAM_ACTION'		=> append_sid("admin_news.php")
	));
	
	$sql = 'SELECT * FROM ' . NEWS . ' ORDER BY news_id DESC';
	$result = $db->sql_query($sql);
	$news_data = $db->sql_fetchrowset($result);
	
	if ( !$news_data )
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($news_data)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			if ( $userauth['auth_news_public'] || $userdata['user_level'] == ADMIN )
			{
				$public = ( $news_data[$i]['news_public'] ) ? '<a href="' . append_sid("admin_news.php?mode=public&amp;" . POST_NEWS_URL . "=" . $news_data[$i]['news_id']) .'"><img src="' . $images['icon_acp_public'] . '" alt=""></a>' : '<a href="' . append_sid("admin_news.php?mode=privat&amp;" . POST_NEWS_URL . "=" . $news_data[$i]['news_id']) .'"><img src="' . $images['icon_acp_privat'] . '" alt=""></a>';
			}
			else
			{
				$public = '<img src="' . $images['icon_acp_denied'] . '" alt="">';
			}
			
			if ( $news_data[$i]['user_id'] == $userdata['user_id'] || $userdata['user_level'] == ADMIN )
			{
				$edit	= '<a href="' . append_sid("admin_news.php?mode=edit&amp;" . POST_NEWS_URL . "=" . $news_data[$i]['news_id']) .'">' . $lang['edit'] . '</a>';
				$delete	= '<a href="' . append_sid("admin_news.php?mode=delete&amp;" . POST_NEWS_URL . "=" . $news_data[$i]['news_id']) .'">' . $lang['delete'] . '</a>';
			}
			else
			{
				$edit	= $lang['edit'];
				$delete	= $lang['delete'];
			}
			
			$template->assign_block_vars('display.news_row', array(
				'CLASS' 		=> $class,
				'NAME'			=> ( $news_data[$i]['news_intern'] ) ? '<em><b>' . $news_data[$i]['news_title'] . '</b></em>' : $news_data[$i]['news_title'],
				'STATUS'		=> ( $news_data[$i]['news_public'] ) ? '<img src="' . $images['icon_acp_public'] . '" alt="">' : '<img src="' . $images['icon_acp_privat'] . '" alt="">',
//				$page_title = ($mode != 'contact') ? ($mode == 'joinus') ? $lang['contact_joinus'] : $lang['contact_fightus'] : $lang['contact'];
				'PUBLIC'		=> $public,
				
				'DELETE'		=> $delete,
				'EDIT'		=> $edit,
			));
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>