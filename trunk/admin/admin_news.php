<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_news'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_news']['_submenu_news'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	$start		= ( request('start') ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$news_id	= request(POST_NEWS_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$show_index = '';
	
	
	if ( !$userauth['auth_news'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_news.php', true));
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_news.tpl'));
				$template->assign_block_vars('news_edit', array());
			
				if ( $mode == '_update' )
				{
					$news		= get_data('news', $news_id, 2);
					$new_mode	= '_update_save';
				}
				else
				{
					$news = array (
						'news_title'		=> request('news_title', 2),
						'news_category'		=> '0',
						'news_text'			=> '',
						'news_url'			=> '',
						'news_link'			=> '',
						'user_id'			=> '',
						'match_id'			=> '',
						'news_time_create'	=> time(),
						'news_time_public'	=> time(),
						'news_public'		=> '0',
						'news_intern'		=> '0',
						'news_comments'		=> '1',
						'news_rating'		=> '0',
					);
					$new_mode = '_create_save';
				}
	
				if ( $userauth['auth_news_public'] || $userdata['user_level'] == ADMIN )
				{
					$template->assign_block_vars('news_edit.public', array());				
				}
				
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
				
				$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_NEWS_URL . '" value="' . $news_id . '" />';
				
				$template->assign_vars(array(
					
					'L_NEWS_HEAD'				=> sprintf($lang['sprintf_head'], $lang['news']),
					'L_NEWS_NEW_EDIT'			=> ( $mode == '_create' ) ? sprintf($lang['sprintf_add'], $lang['news']) : sprintf($lang['sprintf_edit'], $lang['news']),
					'L_REQUIRED'				=> $lang['required'],
					
					'L_NEWS_TITLE'				=> sprintf($lang['sprintf_title'], $lang['news']),
					
					'L_NEWS_CAT'				=> sprintf($lang['sprintf_category'], $lang['news']),
					'L_NEWS_MATCH'				=> $lang['news_match'],
					'L_NEWS_TEXT'				=> $lang['news_text'],
					'L_NEWS_LINK'				=> $lang['news_link'],
					'L_NEWS_PUBLIC_TIME'		=> $lang['news_public_time'],
					'L_NEWS_PUBLIC'				=> $lang['news_public'],
					'L_NEWS_INTERN'				=> $lang['news_intern'],
					'L_NEWS_COMMENTS'			=> $lang['common_comments'],
					'L_NEWS_RATING'				=> sprintf($lang['sprintf_rating'], $lang['news']),
					
					'L_NO'						=> $lang['No'],
					'L_YES'						=> $lang['Yes'],
					'L_RESET'					=> $lang['Reset'],				
					'L_SUBMIT'					=> $lang['Submit'],
					
					'L_MORE'				=> $lang['common_more'],
					'L_REMOVE'				=> $lang['common_remove'],
					
					'NEWS_TITLE'				=> $news['news_title'],
					'NEWS_TEXT'					=> html_entity_decode($news['news_text'], ENT_QUOTES),
	#				'NEWSCAT_IMAGE'				=> ( $mode != '_create' ) ? ( $news['newscat_image'] ) ? $root_path . $settings['path_news_category'] . '/' . $news['newscat_image'] : $images['icon_acp_spacer'] : $images['icon_acp_spacer'],
	
					'S_RATING_YES'		=> ( $news['news_rating'] ) ? ' checked="checked"' : '',
					'S_RATING_NO'		=> ( !$news['news_rating'] ) ? ' checked="checked"' : '',
					'S_PUBLIC_YES'		=> ( $news['news_public'] ) ? ' checked="checked"' : '',
					'S_PUBLIC_NO'		=> ( !$news['news_public'] ) ? ' checked="checked"' : '',
					'S_COMMENTS_YES'	=> ( $news['news_comments'] ) ? ' checked="checked"' : '',
					'S_COMMENTS_NO'		=> ( !$news['news_comments'] ) ? ' checked="checked"' : '',
					'S_INTERN_YES'		=> ( $news['news_intern'] ) ? ' checked="checked"' : '',
					'S_INTERN_NO'		=> ( !$news['news_intern'] ) ? ' checked="checked"' : '',
					
					'S_DAY'						=> select_date('day', 'day',		date('d', $news['news_time_public'])),
					'S_MONTH'					=> select_date('month', 'month',	date('m', $news['news_time_public'])),
					'S_YEAR'					=> select_date('year', 'year',		date('Y', $news['news_time_public'])),
					'S_HOUR'					=> select_date('hour', 'hour',		date('H', $news['news_time_public'])),
					'S_MIN'						=> select_date('min', 'min',		date('i', $news['news_time_public'])),
					
					'NEWSCAT_PATH'				=> $root_path . $settings['path_news_category'],
					
					'S_NEWS_CAT_LIST'			=> select_box('news_category', 'select', $news['news_category']),
					'S_NEWS_MATCH_LIST'			=> _select_match($news['match_id'], '1', 'post'),
					
					'S_FIELDS'			=> $s_fields,
					'S_NEWS_ACTION'				=> append_sid('admin_news.php'),
				));
				
				$template->pparse('body');
			
				break;
			
			case '_create_save':
			
				$match_id			= request('match_id', 0);
				$news_text			= request('news_text', 2);
				$news_title			= request('news_title', 2);
				$news_public		= request('news_public', 0);
				$news_intern		= request('news_intern', 0);
				$news_rating		= request('news_rating', 0);
				$news_category		= request('newscat_image', 2);
				$news_url			= request('news_url');
				$news_name			= request('news_name');
				$news_time_public	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				for ( $i = 0; $i < count($news_url); $i++ )
				{
					if ( empty($news_url[$i]) )
					{
						unset($news_url[$i]);
						unset($news_name[$i]);
					}
				}
				
				if ( $news_url )
				{
					array_multisort($news_url);
					array_multisort($news_name);
					
					for ( $j = 0; $j < count($news_url); $j++ )
					{	
						if ( !preg_match('#^http[s]?:\/\/#i', $news_url[$j]) )
						{
							$news_url[$j] = 'http://' . $news_url[$j];
						}
					}
					
					$news_url = serialize($news_url);
					$news_name = serialize($news_name);
				}
				else
				{
					$news_url = '';
					$news_name = '';
				}
				
				if ( $news_category )
				{
					$sql = "SELECT newscat_id FROM " . NEWSCAT . " WHERE newscat_image = '$news_category'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$news_cat = $db->sql_fetchrow($result);
					
					$news_category = ( $news_cat['newscat_id'] ) ? $news_cat['newscat_id'] : '0';
				}
				
				$error_msg = '';
				$error_msg .= ( !$news_title ) ? $lang['msg_select_title'] : '';
				$error_msg .= ( !$news_category ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_category'] : '';
				$error_msg .= ( !$news_text ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_text'] : '';
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}
				
				$sql = "INSERT INTO " . NEWS . " (news_title, news_category, news_text, news_url, news_link, user_id, match_id, news_time_create, news_time_public, news_public, news_intern, news_rating)
							VALUES ('$news_title', '$news_category', '$news_text', '$news_url', '$news_name', " . $userdata['user_id'] . ", '$match_id', " . time() . ", '$news_time_public', '$news_public', '$news_intern', '$news_rating')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$news_id_start = $db->sql_nextid();
				
				$sql = "INSERT INTO " . NEWS_COMMENTS_READ . " (news_id, user_id, read_time)
							VALUES ('$news_id_start', " . $userdata['user_id'] . ", " . time() . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('display_navi_news');
				
				$message = $lang['create_news'] . sprintf($lang['click_return_news'], '<a href="' . append_sid('admin_news.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'create_news');
				message(GENERAL_MESSAGE, $message);
	
				break;
			
			case '_update_save':
			
				$match_id			= request('match_id', 0);
				$news_text			= request('news_text', 2);
				$news_title			= request('news_title', 2);
				$news_public		= request('news_public', 0);
				$news_intern		= request('news_intern', 0);
				$news_rating		= request('news_rating', 0);
				$news_category		= request('newscat_image', 2);
				$news_url			= request('news_url');
				$news_name			= request('news_name');
				$news_time_public	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				for ( $i = 0; $i < count($news_url); $i++ )
				{
					if ( empty($news_url[$i]) )
					{
						unset($news_url[$i]);
						unset($news_name[$i]);
					}
				}
				
				if ( $news_url )
				{
					array_multisort($news_url);
					array_multisort($news_name);
					
					for ( $j = 0; $j < count($news_url); $j++ )
					{	
						if ( !preg_match('#^http[s]?:\/\/#i', $news_url[$j]) )
						{
							$news_url[$j] = 'http://' . $news_url[$j];
						}
					}
					
					$news_url = serialize($news_url);
					$news_name = serialize($news_name);
				}
				else
				{
					$news_url = '';
					$news_name = '';
				}
				
				if ( $news_category )
				{
					$sql = "SELECT newscat_id FROM " . NEWSCAT . " WHERE newscat_image = '$news_category'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$news_cat = $db->sql_fetchrow($result);
					
					$news_category = ( $news_cat['newscat_id'] ) ? $news_cat['newscat_id'] : '0';
				}
				
				$error_msg = '';
				$error_msg .= ( !$news_title ) ? $lang['msg_select_title'] : '';
				$error_msg .= ( !$news_category ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_category'] : '';
				$error_msg .= ( !$news_text ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_text'] : '';
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}
	
				$sql = "UPDATE " . NEWS . " SET
							news_title			= '$news_title',
							news_category		= '$news_category',
							news_text			= '$news_text',
							news_url			= '" . serialize($news_url) . "',
							news_link			= '" . serialize($news_name) . "',
							match_id			= '$match_id',
							news_time_public	= '$news_time_public',
							news_public			= '$news_public',
							news_intern			= '$news_intern',
							news_rating			= '$news_rating',
							news_time_update	= '" . time() . "'
						WHERE news_id = $news_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('display_navi_news');
				
				$message = $lang['update_news'] . sprintf($lang['click_return_news'], '<a href="' . append_sid('admin_news.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'update_news');
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case '_switch':
		
				$news = get_data('news', $news_id, 0);
				
				$public = ( $news['news_public'] ) ? 0 : 1;
				
				$sql = "UPDATE " . NEWS . " SET news_public = $public WHERE news_id =  $news_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('display_navi_news');
				
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'change_news_public');
				
				$show_index = TRUE;
				
				break;
			
			case '_delete':
			
				if ( $news_id && $confirm )
				{	
				#	$news = get_data('news', $news_id, 0);
					
					$sql = 'DELETE FROM ' . NEWS . ' WHERE news_id = ' . $news_id;
					if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('display_navi_news');
					
					$message = $lang['delete_news'] . sprintf($lang['click_return_news'], '<a href="' . append_sid('admin_news.php') . '">', '</a>');
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'delete_news');
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( $news_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_NEWS_URL . '" value="' . $news_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_news'],
						'L_NO'				=> $lang['common_no'],
						'L_YES'				=> $lang['common_yes'],
						'S_ACTION'	=> append_sid('admin_news.php'),
						'S_FIELDS'	=> $s_fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_news']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
				
				message(GENERAL_ERROR, $lang['no_mode']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_news.tpl'));
	$template->assign_block_vars('display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_NEWS_HEAD'		=> sprintf($lang['sprintf_head'], $lang['news']),
		'L_NEWS_CREATE'		=> sprintf($lang['sprintf_create'], $lang['news']),
		'L_NEWS_NAME'		=> sprintf($lang['sprintf_title'], $lang['news']),
		'L_NEWS_EXPLAIN'	=> $lang['news_explain'],
		
		'L_UPDATE'			=> $lang['common_update'],
		'L_DELETE'			=> $lang['common_delete'],
		'L_SETTINGS'		=> $lang['common_settings'],
		
		'S_FIELDS'	=> $s_fields,
		'S_NEWS_CREATE'		=> append_sid('admin_news.php?mode=_create'),
		'S_NEWS_ACTION'		=> append_sid('admin_news.php'),
	));
	
	$news_data = get_data_array(NEWS, '', 'news_id', 'DESC');
	
	if ( $news_data )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($news_data)); $i++ )
		{
			if ( $userauth['auth_news_public'] || $userdata['user_level'] == ADMIN )
			{
				$name = ( $news_data[$i]['news_public'] ) ? '<img src="' . $images['icon_acp_public'] . '" alt="">' : '<img src="' . $images['icon_acp_privat'] . '" alt="">';
				$link = '<a href="' . append_sid('admin_news.php?mode=_switch&amp;' . POST_NEWS_URL . '=' . $news_data[$i]['news_id']) .'">' . $name . '</a>';
			}
			else
			{
				$name = '<img src="' . $images['icon_acp_denied'] . '" alt="">';
				$link = $name;
			}
			
			if ( $userauth['auth_news_public'] || $userdata['user_level'] == ADMIN || $news_data[$i]['user_id'] == $userdata['user_id'] )
			{
				$update	= '<a href="' . append_sid('admin_news.php?mode=_update&amp;' . POST_NEWS_URL . '=' . $news_data[$i]['news_id']) .'">' . $lang['common_update'] . '</a>';
				$delete	= '<a href="' . append_sid('admin_news.php?mode=_delete&amp;' . POST_NEWS_URL . '=' . $news_data[$i]['news_id']) .'">' . $lang['common_delete'] . '</a>';
			}
			else
			{
				$update	= $lang['common_update'];
				$delete	= $lang['common_delete'];
			}
			
			$template->assign_block_vars('display.news_row', array(
				'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				
				'NEWS_TITLE'	=> ( $news_data[$i]['news_intern'] ) ? sprintf($lang['sprintf_news_title'], $news_data[$i]['news_title']) : $news_data[$i]['news_title'],
				'NEWS_STATUS'	=> ( $news_data[$i]['news_public'] ) ? $images['icon_acp_public'] : $images['icon_acp_privat'],
				
				'NEWS_LINK'		=> $link,
				'NEWS_UPDATE'	=> $update,
				'NEWS_DELETE'	=> $delete,
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>