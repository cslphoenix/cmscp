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
	$current	= '_submenu_news';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/news.php');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_NEWS_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$path_dir	= $root_path . $settings['path_newscat'] . '/';
	$show_index	= '';
	
	if ( !$userauth['auth_news'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_news.php', true));
	}
	
	function select_newscat_name($image_id)
	{
		global $db;
		
		$sql = "SELECT newscat_image FROM " . NEWSCAT . " WHERE newscat_id = $image_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$news_cat = $db->sql_fetchrow($result);
		
		$newscat_image = ( $news_cat['newscat_image'] ) ? $news_cat['newscat_image'] : '-1';
;
		
		return $newscat_image;
	}
	
	function select_newscat_id($image_name)
	{
		global $db;
		
		$sql = "SELECT newscat_id FROM " . NEWSCAT . " WHERE newscat_image = '$image_name'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$news_cat = $db->sql_fetchrow($result);
		
		$newscat_id = ( $news_cat['newscat_id'] ) ? $news_cat['newscat_id'] : '-1';
		
		return $newscat_id;
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_news.tpl'));
				$template->assign_block_vars('news_edit', array());
				
				if ( $mode == '_create' && !(request('submit', 2)) )
				{
					$data = array (
						'news_title'		=> request('news_title', 2),
						'news_category'		=> '-1',
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
				}
				else if ( $mode == '_update' && !(request('submit', 2)) )
				{
					$data = get_data(NEWS , $data_id, 3);
					
				#	$newscat_image = select_newscat_name($data['news_category']);
					
					debug($data);
				#	debug($newscat_image);
				
					$data['news_category'] = $data['newscat_image'];
				}
				else
				{
					$news_time_create	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
					$news_time_public	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
					
					$data = array (
						'news_title'		=> request('news_title', 2),
						'news_category'		=> request('newscat_image', 1),
						'news_text'			=> request('news_text', 3),
					#	'news_url'			=> request('news_url', 4),
					#	'news_link'			=> request('news_link', 4),
						'news_url'			=> $_POST['news_url'],
						'news_link'			=> $_POST['news_link'],
						'user_id'			=> request('user_id', 0),
						'match_id'			=> request('match_id', 0),
						'news_time_create'	=> $news_time_create,
						'news_time_public'	=> $news_time_public,
						'news_public'		=> request('news_public', 0),
						'news_intern'		=> request('news_intern', 0),
						'news_comments'		=> request('news_comments', 0),
						'news_rating'		=> request('news_rating', 0),
					);
				}
				
				$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
				$s_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_NEWS_URL . '" value="' . $data_id . '" />';
			
				if ( $userauth['auth_news_public'] || $userdata['user_level'] == ADMIN )
				{
					$template->assign_block_vars('news_edit.public', array());				
				}
				
				/* alter code 
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
				*/
				/*
				if ( $data['news_url'] )
				{
					array_multisort($news_url);
					array_multisort($news_link);
					
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
				
				if ( $data['news_link'] && is_array(unserialize($data['news_link'])) )
				{
					$news_link	= unserialize($data['news_link']);
					$news_url	= unserialize($data['news_url']);
				
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
						'NEWS_NAME'	=> $data['news_link'],
						'NEWS_URL'	=> $data['news_url'],
					));
				}
				*/
				
				$idorimage = ( is_numeric($data['news_category']) ) ? $data['news_category'] : select_newscat_id($data['news_category']);
				$imageorid = ( is_numeric($data['news_category']) ) ? select_newscat_id($data['news_category']) : $data['news_category'];
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['news']),
					'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['news'], $data['news_title']),
					
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['news']),
					'L_CAT'			=> sprintf($lang['sprintf_category'], $lang['news']),
					'L_MATCH'		=> $lang['news_match'],
					'L_TEXT'		=> $lang['news_text'],
					'L_LINK'		=> $lang['news_link'],
					'L_PUBLIC_TIME'	=> $lang['news_public_time'],
					'L_PUBLIC'		=> $lang['news_public'],
					'L_INTERN'		=> $lang['news_intern'],
					'L_COMMENTS'	=> $lang['common_comments'],
					'L_RATING'		=> sprintf($lang['sprintf_rating'], $lang['news']),
					
					'L_MORE'		=> $lang['common_more'],
					'L_REMOVE'		=> $lang['common_remove'],
					
					'TITLE'			=> $data['news_title'],
					'TEXT'			=> html_entity_decode($data['news_text'], ENT_QUOTES),
	
					'S_RATING_NO'		=> ( !$data['news_rating'] )	? ' checked="checked"' : '',
					'S_RATING_YES'		=> ( $data['news_rating'] )		? ' checked="checked"' : '',
					'S_PUBLIC_NO'		=> ( !$data['news_public'] )	? ' checked="checked"' : '',
					'S_PUBLIC_YES'		=> ( $data['news_public'] )		? ' checked="checked"' : '',
					'S_COMMENTS_NO'		=> ( !$data['news_comments'] )	? ' checked="checked"' : '',
					'S_COMMENTS_YES'	=> ( $data['news_comments'] )	? ' checked="checked"' : '',
					'S_INTERN_NO'		=> ( !$data['news_intern'] )	? ' checked="checked"' : '',
					'S_INTERN_YES'		=> ( $data['news_intern'] )		? ' checked="checked"' : '',
					
					'S_DAY'		=> select_date('day', 'day',		date('d', $data['news_time_public'])),
					'S_MONTH'	=> select_date('month', 'month',	date('m', $data['news_time_public'])),
					'S_YEAR'	=> select_date('year', 'year',		date('Y', $data['news_time_public'])),
					'S_HOUR'	=> select_date('hour', 'hour',		date('H', $data['news_time_public'])),
					'S_MIN'		=> select_date('min', 'min',		date('i', $data['news_time_public'])),
					
					'NEWSCAT_PATH'	=> $path_dir,
					'IMAGE'			=> ( $mode == '_create' && !(request('submit', 2)) ) ? '' : $path_dir . $imageorid,
					
					'S_LIST_CAT'	=> select_box('newscat', 'select', $idorimage),
					'S_LIST_MATCH'	=> _select_match($data['match_id'], '1', 'post'),
					
					'S_FIELDS'		=> $s_fields,
					'S_ACTION'		=> append_sid('admin_news.php'),
				));
				
				if ( request('submit', 2) )
				{
					$match_id			= request('match_id', 0);
					$news_text			= request('news_text', 3);
					$news_title			= request('news_title', 2);
					$news_public		= request('news_public', 0);
					$news_intern		= request('news_intern', 0);
					$news_rating		= request('news_rating', 0);
					$news_category		= select_newscat_id(request('newscat_image', 1));
				#	$news_url			= $_POST['news_url'];
				#	$news_link			= $_POST['news_link'];
					$news_time_public	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
					/*	
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
					*/	
					
					$error = '';
					$error .= ( !$news_title )				? $lang['msg_select_title'] : '';
					$error .= ( $news_category == '-1' )	? ( $error ? '<br>' : '' ) . $lang['msg_select_newscat'] : '';
					$error .= ( !$news_text )				? ( $error ? '<br>' : '' ) . $lang['msg_select_text'] : '';
					
					if ( $error )
					{
						$template->set_filenames(array('reg_header' => 'style/error_body.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}					
					else
					{
						if ( $mode == '_create' )
						{
							#	, news_url, news_link
							#	, '$news_url', '$news_link'
							$sql = "INSERT INTO " . NEWS . " (news_title, news_category, news_text, user_id, match_id, news_time_create, news_time_public, news_public, news_intern, news_rating)
										VALUES ('$news_title', '$news_category', '$news_text', " . $userdata['user_id'] . ", '$match_id', " . time() . ", '$news_time_public', '$news_public', '$news_intern', '$news_rating')";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$news_id_start = $db->sql_nextid();
							
							$sql = "INSERT INTO " . NEWS_COMMENTS_READ . " (news_id, user_id, read_time) VALUES ('$news_id_start', " . $userdata['user_id'] . ", " . time() . ")";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$oCache -> sCachePath = './../cache/';
							$oCache -> deleteCache('display_navi_news');
							
							$message = $lang['create_news'] . sprintf($lang['click_return_news'], '<a href="' . append_sid('admin_news.php') . '">', '</a>');
							log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'create_news');
						}
						else
						{
							#	news_url			= '" . serialize($news_url) . "',
							#	news_link			= '" . serialize($news_name) . "',
							
							$sql = "UPDATE " . NEWS . " SET
										news_title			= '$news_title',
										news_category		= '$news_category',
										news_text			= '$news_text',
										match_id			= '$match_id',
										news_time_public	= '$news_time_public',
										news_public			= '$news_public',
										news_intern			= '$news_intern',
										news_rating			= '$news_rating',
										news_time_update	= '" . time() . "'
									WHERE news_id = $data_id";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$oCache -> sCachePath = './../cache/';
							$oCache -> deleteCache('display_navi_news');
							
							$message = $lang['update_news'] . sprintf($lang['click_return_news'], '<a href="' . append_sid('admin_news.php') . '">', '</a>');
							log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'update_news');
						}
						message(GENERAL_MESSAGE, $message);						
					}
				}
				
				$template->pparse('body');
				
				break;
				
			case '_switch':
		
				$data = get_data(NEWS, $data_id, 1);
				
				$public = ( $data['news_public'] ) ? 0 : 1;
				
				$sql = "UPDATE " . NEWS . " SET news_public = $public WHERE news_id =  $data_id";
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
			
				$data = get_data(NEWS, $data_id, 1);
			
				if ( $data_id && $confirm )
				{	
					$sql = "DELETE FROM " . NEWS . " WHERE news_id = $data_id";
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
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_NEWS_URL . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_news'], $data['news_title']),
						
						'S_FIELDS'		=> $s_fields,
						'S_ACTION'		=> append_sid('admin_news.php'),
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
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['news']),
		'L_CREATE'		=> sprintf($lang['sprintf_creates'], $lang['news']),
		'L_NAME'		=> sprintf($lang['sprintf_title'], $lang['news']),
		'L_EXPLAIN'		=> $lang['news_explain'],
		
		'S_FIELDS'		=> $s_fields,
		'S_CREATE'		=> append_sid('admin_news.php?mode=_create'),
		'S_ACTION'		=> append_sid('admin_news.php'),
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