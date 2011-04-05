<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] || $userauth['auth_news_public'] )
	{
		$module['_headmenu_03_news']['_submenu_news'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_news';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('news');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_NEWS;
	$url	= POST_NEWS_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_newscat'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['news']);
	
	if ( $userdata['user_level'] != ADMIN && ( !$userauth['auth_news'] || !$userauth['auth_news_public'] ) )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	function select_cat_name($image_id)
	{
		global $db;
		
		$sql = "SELECT cat_image FROM " . NEWS_CAT . " WHERE cat_id = $image_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$news_cat = $db->sql_fetchrow($result);
		
		$cat_image = ( $news_cat['cat_image'] ) ? $news_cat['cat_image'] : '-1';
		
		return $cat_image;
	}
	
	function select_cat_id($image_name)
	{
		global $db;
		
		$sql = "SELECT cat_id FROM " . NEWS_CAT . " WHERE cat_image = '$image_name'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$news_cat = $db->sql_fetchrow($result);
		
		$cat_id = ( $news_cat['cat_id'] ) ? $news_cat['cat_id'] : '-1';
		
		return $cat_id;
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_news.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !(request('submit', 1)) )
				{
					$data = array (
								'news_title'		=> request('news_title', 2),
								'news_cat'			=> '-1',
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
				else if ( $mode == '_update' && !(request('submit', 1)) )
				{
				#	$data = get_data(NEWS , $data_id, 3);
					$data = data(NEWS, $data_id, false, 3, 1);
					
				#	$cat_image = select_cat_name($data['news_cat']);
					
				#	debug($data);
				#	debug($cat_image);
				
					$data['news_cat'] = $data['cat_image'];
				}
				else
				{
					$news_time_create	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
					$news_time_public	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
					
					$data = array (
								'news_title'		=> request('news_title', 2),
								'news_cat'			=> request('cat_image', 1),
								'news_text'			=> request('news_text', 2),
								'news_url'			=> serialize(request('news_url', 4, URL)),
								'news_link'			=> serialize(request('news_link', 4)),
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
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			
				( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] ) ? $template->assign_block_vars('_input._public', array()) : false;
				
				if ( (is_array($data['news_url']) && is_array($data['news_link'])) || unserialize($data['news_url']) )
				{
					$data['news_url']	= unserialize($data['news_url']);
					$data['news_link']	= unserialize($data['news_link']);
					
					for ( $i = 0; $i < count($data['news_url']); $i++ )
					{
						if ( empty($data['news_url'][$i]) )
						{
								unset($data['news_url'][$i]);
								unset($data['news_link'][$i]);
						}
					}
					
					array_multisort($data['news_url']);
					array_multisort($data['news_link']);
					
					for ( $i = 0; $i < count($data['news_url']); $i++ )
					{
						$template->assign_block_vars('_input._link_row', array(
							'NEWS_URL'	=> $data['news_url'][$i],
							'NEWS_LINK'	=> $data['news_link'][$i],
						));
					}
				}
				else
				{
					$template->assign_vars(array(
						'NEWS_URL'	=> $data['news_url'],
						'NEWS_LINK'	=> $data['news_link'],
					));
				}
				
				$idorimage = ( is_numeric($data['news_cat']) ) ? $data['news_cat'] : select_cat_id($data['news_cat']);
				$imageorid = ( is_numeric($data['news_cat']) ) ? select_cat_id($data['news_cat']) : $data['news_cat'];
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['news']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['news'], $data['news_title']),
					
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['news']),
					'L_CAT'			=> sprintf($lang['sprintf_category'], $lang['news']),
					'L_MATCH'		=> $lang['match'],
					'L_TEXT'		=> sprintf($lang['sprintf_text'], $lang['news']),
					'L_LINK'		=> $lang['link'],
					'L_PUBLIC_TIME'	=> $lang['time'],
					'L_PUBLIC'		=> $lang['public'],
					'L_INTERN'		=> $lang['common_intern'],
					'L_COMMENTS'	=> $lang['common_comments'],
					'L_RATING'		=> sprintf($lang['sprintf_rating'], $lang['news']),
					
					'L_MORE'		=> $lang['common_more'],
					'L_REMOVE'		=> $lang['common_remove'],
					
					'TITLE'			=> $data['news_title'],
					'TEXT'			=> html_entity_decode($data['news_text'], ENT_QUOTES),
	
					'S_RATING_NO'		=> (!$data['news_rating'] )	? 'checked="checked"' : '',
					'S_RATING_YES'		=> ( $data['news_rating'] )	? 'checked="checked"' : '',
					'S_PUBLIC_NO'		=> (!$data['news_public'] )	? 'checked="checked"' : '',
					'S_PUBLIC_YES'		=> ( $data['news_public'] )	? 'checked="checked"' : '',
					'S_COMMENTS_NO'		=> (!$data['news_comments'] )	? 'checked="checked"' : '',
					'S_COMMENTS_YES'	=> ( $data['news_comments'] )	? 'checked="checked"' : '',
					'S_INTERN_NO'		=> (!$data['news_intern'] )	? 'checked="checked"' : '',
					'S_INTERN_YES'		=> ( $data['news_intern'] )	? 'checked="checked"' : '',
					
					'S_DAY'		=> select_date('select', 'day', 'day',		date('d', $data['news_time_public']), $data['news_time_create']),
					'S_MONTH'	=> select_date('select', 'month', 'month',	date('m', $data['news_time_public']), $data['news_time_create']),
					'S_YEAR'	=> select_date('select', 'year', 'year',	date('Y', $data['news_time_public']), $data['news_time_create']),
					'S_HOUR'	=> select_date('select', 'hour', 'hour',	date('H', $data['news_time_public']), $data['news_time_create']),
					'S_MIN'		=> select_date('select', 'min', 'min',		date('i', $data['news_time_public']), $data['news_time_create']),
					
					'IMAGE'			=> ( $mode == '_create' && !(request('submit', 1)) ) ? '' : $path_dir . $imageorid,
					'IMAGE_PATH'	=> $path_dir,
					'IMAGE_DEFAULT'	=> $images['icon_acp_spacer'],
					
					'S_LIST_CAT'	=> select_box_image('select', NEWS_CAT, 'cat', $idorimage),
					'S_LIST_MATCH'	=> _select_match($data['match_id'], '1', 'post'),
					
					'S_ACTION'		=> append_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$match_id			= request('match_id', 0);
					$news_text			= request('news_text', 2);
					$news_title			= request('news_title', 2);
					$news_public		= request('news_public', 0);
					$news_intern		= request('news_intern', 0);
					$news_rating		= request('news_rating', 0);
					$news_cat			= select_cat_id(request('cat_image', 1));
					$news_url			= request('news_url', 4, URL);
					$news_link			= request('news_link', 4);
					$news_time_public	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
					
					if ( $news_url )
					{
						for ( $i = 0; $i < count($news_url); $i++ )
						{
							if ( $news_url[$i] == 'http://' || empty($news_url[$i]) )
							{
								unset($news_url[$i]);
								unset($news_link[$i]);
							}
						}
						
						array_multisort($news_url);
						
						$news_url = serialize($news_url);
						$news_link = serialize($news_link);
					}
					
					$error .= ( !$news_title )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_title'] : '';
					$error .= ( $news_cat == '-1' )	? ( $error ? '<br />' : '' ) . $lang['msg_select_newscat'] : '';
					$error .= ( !$news_text )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_text'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$sql = "INSERT INTO " . NEWS . " (news_title, news_cat, news_text, news_url, news_link, user_id, match_id, news_time_create, news_time_public, news_public, news_intern, news_rating)
										VALUES ('$news_title', '$news_cat', '$news_text', '$news_url', '$news_link', " . $userdata['user_id'] . ", '$match_id', " . time() . ", '$news_time_public', '$news_public', '$news_intern', '$news_rating')";
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
							
							$message = $lang['create'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							$sql = "UPDATE " . NEWS . " SET
										news_title			= '$news_title',
										news_cat			= '$news_cat',
										news_text			= '$news_text',
										news_url			= '$news_url',
										news_link			= '$news_link',
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
							
							$message = $lang['update']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
						}
						
						#$oCache -> sCachePath = './../cache/';
						#$oCache -> deleteCache('_display_navi_news');
						
						log_add(LOG_ADMIN, $log, $mode, $news_title);						
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
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
				
				#$oCache -> sCachePath = './../cache/';
				#$oCache -> deleteCache('_display_navi_news');
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
			case '_delete':
			
				$data = data(NEWS, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{	
					$sql = "DELETE FROM " . NEWS . " WHERE news_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					#$oCache -> sCachePath = './../cache/';
					#$oCache -> deleteCache('_display_navi_news');
					
					$message = $lang['delete'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					
					log_add(LOG_ADMIN, $log, $mode, $data['news_title']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
					
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['news_title']),
						
						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['news'])); }
				
				$template->pparse('body');
				
				break;
			
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_news.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields = '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['news']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['news']),
		'L_NAME'	=> sprintf($lang['sprintf_title'], $lang['news']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> append_sid("$file?mode=_create"),
		'S_ACTION'	=> append_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
#	$tmp = get_data_array(NEWS, '', 'news_id', 'DESC');
	$tmp = data(NEWS, false, false, 1, false);
	
	if ( $tmp )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp)); $i++ )
		{
			$tmp_id = $tmp[$i]['news_id'];
			
			if ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] )
			{
				$name = ( $tmp[$i]['news_public'] ) ? '<img src="' . $images['icon_acp_public'] . '" alt="" />' : '<img src="' . $images['icon_acp_privat'] . '" alt="" />';
				$link = '<a href="' . append_sid("$file?mode=_switch&amp;$url=$tmp_id") .'">' . $name . '</a>';
			}
			else
			{
				$name = '<img src="' . $images['icon_acp_denied'] . '" alt="" />';
				$link = $name;
			}
			
			if ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] || $tmp[$i]['user_id'] == $userdata['user_id'] )
			{
				$update	= '<a href="' . append_sid("$file?mode=_update&amp;$url=$tmp_id") .'"><img src="' . $images['icon_option_update'] . '" alt="" ></a>';
				$delete	= '<a href="' . append_sid("$file?mode=_delete&amp;$url=$tmp_id") .'"><img src="' . $images['icon_option_delete'] . '" alt="" ></a>';
			}
			else
			{
				$update	= $lang['common_update'];
				$delete	= $lang['common_delete'];
			}
			
			$template->assign_block_vars('_display._news_row', array(
				'TITLE'		=> ( $tmp[$i]['news_intern'] ) ? sprintf($lang['sprintf_news_title'], $tmp[$i]['news_title']) : $tmp[$i]['news_title'],
				'STATUS'	=> ( $tmp[$i]['news_public'] ) ? $images['icon_acp_public'] : $images['icon_acp_privat'],
				
				'LINK'		=> $link,
				'UPDATE'	=> $update,
				'DELETE'	=> $delete,
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry', array()); }

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>