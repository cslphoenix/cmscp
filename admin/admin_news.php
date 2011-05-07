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
	
	$oCache -> sCachePath = $root_path . 'cache/';
	
	if ( $userdata['user_level'] != ADMIN && ( !$userauth['auth_news'] || !$userauth['auth_news_public'] ) )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_news.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
		'tiny'		=> 'style/tinymce_news.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->assign_block_vars('_input', array());
				
				$template->assign_vars(array('PATH' => $path_dir));
				$template->assign_var_from_handle('UIMG', 'uimg');
				$template->assign_var_from_handle('TINYMCE', 'tiny');
				
				if ( $mode == '_create' && !(request('submit', 1)) )
				{
					$data = array (
								'news_title'		=> request('news_title', 2),
								'news_cat'			=> '',
								'news_text'			=> '',
								'news_url'			=> '',
								'news_link'			=> '',
								'user_id'			=> $userdata['user_id'],
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
					$data = data(NEWS, $data_id, false, 3, 1);
					
				#	$data['news_cat'] = select_cat_name($data['news_cat']);
					$data['news_cat'] = search_image(NEWSCAT, 'name', $data['news_cat']);
					$data['news_url'] = isset($data['news_url']) ? unserialize($data['news_url']) : array();
					$data['news_link'] = isset($data['news_link']) ? unserialize($data['news_link']) : array();
				}
				else
				{
					$data = array (
								'news_title'		=> request('news_title', 1),
								'news_cat'			=> request('cat_image', 1),
								'news_text'			=> request('news_text', 2),
								'news_url'			=> request('news_url', 4, URL),
								'news_link'			=> request('news_link', 4),
								'user_id'			=> request('user_id', 0),
								'match_id'			=> request('match_id', 0),
								'news_time_create'	=> time(),
								'news_time_public'	=> mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0)),
								'news_public'		=> request('news_public', 0),
								'news_intern'		=> request('news_intern', 0),
								'news_comments'		=> request('news_comments', 0),
								'news_rating'		=> request('news_rating', 0),
							);
					
					if ( $data['news_url'] )
					{
						$_ary_url = '';
						$_ary_link = '';
						$news_url = $data['news_url'];
						$news_link = $data['news_link'];
						
						for ( $i = 0; $i < count($news_url); $i++ )
						{
							if ( empty($news_url[$i]) || $news_url[$i] == 'http://' )
							{
								false;
							}
							else
							{
								$_ary_url[] = $news_url[$i];
								$_ary_link[] = $news_link[$i];
							}
						}
						
						$data['news_url'] = is_array($_ary_url) ? $_ary_url : array();
						$data['news_link'] = is_array($_ary_link) ? $_ary_link : array();
					}
				}
				
				if ( $data['news_url'] )
				{
					for ( $i = 0; $i < count($data['news_url']); $i++ )
					{
						$template->assign_block_vars('_input._link_row', array(
							'NEWS_URL'	=> $data['news_url'][$i],
							'NEWS_LINK'	=> $data['news_link'][$i],
						));
					}
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"news_time_create\" value=\"" . $data['news_time_create'] . "\" />";
			
				( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] ) ? $template->assign_block_vars('_input._public', array()) : false;
												
				$sql = "SELECT * FROM " . NEWSCAT . " ORDER BY cat_order ASC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cats = $db->sql_fetchrowset($result);
				
				$cat_image = "<select class=\"select\" name=\"cat_image\" id=\"cat_image\" onchange=\"update_image(this.options[selectedIndex].value);\">";
				$cat_image .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_cat_image']) . "</option>";
				
				for ( $i = 0; $i < count($cats); $i++ )
				{
					$marked = ( $data['news_cat'] == $cats[$i]['cat_image'] ) ? ' selected="selected"' : '';
					$cat_image .= "<option value=\"" . $cats[$i]['cat_image'] . "\"$marked>" . sprintf($lang['sprintf_select_format'], $cats[$i]['cat_name']) . "</option>";
				}
				
				$cat_image .= "</select>";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['news']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['news'], $data['news_title']),
					
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['news']),
					'L_CAT'			=> sprintf($lang['sprintf_cat'], $lang['news']),
					'L_MATCH'		=> $lang['match'],
					'L_TEXT'		=> sprintf($lang['sprintf_text'], $lang['news']),
					'L_LINK'		=> $lang['link'],
					'L_PUBLIC_TIME'	=> $lang['time'],
					'L_PUBLIC'		=> $lang['public'],
					'L_INTERN'		=> $lang['common_intern'],
					'L_COMMENTS'	=> $lang['common_comments'],
					'L_RATING'		=> sprintf($lang['sprintf_rating'], $lang['news']),
					
					'TITLE'			=> $data['news_title'],
					'TEXT'			=> html_entity_decode($data['news_text'], ENT_QUOTES),
	
					'S_RATING_NO'		=> (!$data['news_rating'] ) ? 'checked="checked"' : '',
					'S_RATING_YES'		=> ( $data['news_rating'] ) ? 'checked="checked"' : '',
					'S_PUBLIC_NO'		=> (!$data['news_public'] ) ? 'checked="checked"' : '',
					'S_PUBLIC_YES'		=> ( $data['news_public'] ) ? 'checked="checked"' : '',
					'S_INTERN_NO'		=> (!$data['news_intern'] ) ? 'checked="checked"' : '',
					'S_INTERN_YES'		=> ( $data['news_intern'] ) ? 'checked="checked"' : '',
					'S_COMMENTS_NO'		=> (!$data['news_comments'] ) ? 'checked="checked"' : '',
					'S_COMMENTS_YES'	=> ( $data['news_comments'] ) ? 'checked="checked"' : '',
					
					'S_DAY'		=> select_date('select', 'day', 'day',		date('d', $data['news_time_public']), time()),
					'S_MONTH'	=> select_date('select', 'month', 'month',	date('m', $data['news_time_public']), time()),
					'S_YEAR'	=> select_date('select', 'year', 'year',	date('Y', $data['news_time_public']), time()),
					'S_HOUR'	=> select_date('select', 'hour', 'hour',	date('H', $data['news_time_public']), time()),
					'S_MIN'		=> select_date('select', 'min', 'min',		date('i', $data['news_time_public']), time()),
					
					'IMAGE'			=> $path_dir . $data['news_cat'],
					
					
				#	'S_LIST_CAT'	=> select_box_image('select', NEWSCAT, 'cat', $idorimage),
					'S_LIST_CAT'	=> $cat_image,
					'S_LIST_MATCH'	=> _select_match($data['match_id'], '1', 'post'),
					
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$error .= ( !$data['news_title'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_title'] : '';
					$error .= ( !$data['news_cat'] )	? ( $error ? '<br />' : '' ) . $lang['msg_select_cat'] : '';
					$error .= ( !$data['news_text'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_text'] : '';
					
					if ( !$error )
					{
						if ( $data['news_url'] )
						{
							$_ary_url = '';
							$_ary_link = '';
							$news_url = $data['news_url'];
							$news_link = $data['news_link'];
							
							for ( $i = 0; $i < count($news_url); $i++ )
							{
								if ( empty($news_url[$i]) || $news_url[$i] == 'http://' )
								{
									false;
								}
								else
								{
									$_ary_url[] = $news_url[$i];
									$_ary_link[] = $news_link[$i];
								}
							}
							
							$data['news_url'] = serialize($_ary_url);
							$data['news_link'] = serialize($_ary_link);
						}
						else
						{
							$data['news_url'] = serialize($data['news_url']);
							$data['news_link'] = serialize($data['news_link']);
						}
						
						$data['user_id'] = $userdata['user_id'];
					#	$data['news_cat'] = select_cat_id($data['news_cat']);
						$data['news_cat'] = search_image(NEWSCAT, 'id', $data['news_cat']);
						
						if ( $mode == '_create' )
						{
							$sql = sql(NEWS, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
							
							sql(NEWS_COMMENTS_READ, 'create', array('news_id' => $db->sql_nextid(), 'user_id' => $userdata['user_id'], 'read_time' => time()));
						}
						else
						{
							$data['news_time_update'] = time();
							
							$sql = sql(NEWS, $mode, $data, 'news_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						$oCache->deleteCache('dsp_news');
						
						log_add(LOG_ADMIN, $log, $mode, $sql);						
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}					
				}
				
				$template->pparse('body');
				
				break;
				
			case '_switch':
		
				$data = data(NEWS, $data_id, false, 1, true);
				
				$public = ( $data['news_public'] ) ? 0 : 1;
				
				sql(NEWS, 'update', array('news_public' => $public), 'news_id', $data_id);
				
				$oCache->deleteCache('dsp_news');
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
			case '_delete':
			
				$data = data(NEWS, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{
					$sql = sql(NEWS, $mode, $data, 'news_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					$oCache->deleteCache('dsp_news');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['news_title']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['news']));
				}
				
				$template->pparse('confirm');
				
				break;
			
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['news']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['news']),
		'L_NAME'	=> sprintf($lang['sprintf_title'], $lang['news']),
		
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$news = data(NEWS, false, 'news_time_create ASC', 1, false);
	
	if ( !$news )
	{
		$template->assign_block_vars('_display._entry_empty', array());
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($news)); $i++ )
		{
			$news_id = $news[$i]['news_id'];
			
			if ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] )
			{
				$name = ( $news[$i]['news_public'] ) ? '<img src="' . $images['icon_acp_public'] . '" alt="" />' : '<img src="' . $images['icon_acp_privat'] . '" alt="" />';
				$link = ' <a href="' . check_sid("$file?mode=_switch&amp;$url=$news_id") .'">' . $name . '</a>';
			}
			else
			{
				$name = '<img src="' . $images['icon_acp_denied'] . '" alt="" />';
				$link = $name;
			}
			
			if ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] || $news[$i]['user_id'] == $userdata['user_id'] )
			{
				$update	= ' <a href="' . check_sid("$file?mode=_update&amp;$url=$news_id") . '"><img src="' . $images['icon_option_update'] . '" alt="" ></a>';
				$delete	= ' <a href="' . check_sid("$file?mode=_delete&amp;$url=$news_id") . '"><img src="' . $images['icon_option_delete'] . '" alt="" ></a>';
			}
			else
			{
				$update	= $lang['common_update'];
				$delete	= $lang['common_delete'];
			}
			
			$title	= $news[$i]['news_intern'] ? sprintf($lang['sprintf_news_title'], $news[$i]['news_title']) : $news[$i]['news_title'];
			$public	= $news[$i]['news_public'] ? $images['icon_acp_public'] : $images['icon_acp_privat'];
			
			$template->assign_block_vars('_display._news_row', array(
				'TITLE'		=> "<a href=\"" . check_sid("$file?mode=_update&amp;$url=$news_id") . "\">$title</a>",
				'STATUS'	=> $public,
				
				'LINKS'		=> $link . $update . $delete,
			));
		}
	}
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>