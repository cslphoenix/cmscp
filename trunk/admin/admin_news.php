<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] || $userauth['auth_news_public'] )
	{
		$module['hm_news']['sm_news'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_news';
	
	include('./pagestart.php');
	
	add_lang('news');

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_NEWS;
	$url	= POST_NEWS;
	$file	= basename(__FILE__);
	$time	= time();
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	$acp_main	= request('acp_main', INT);
	
	$dir_path	= $root_path . $settings['path_newscat'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_news'] && !$userauth['auth_news_public'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header && !$acp_main )	? redirect('admin/' . check_sid($file, true)) : false;
	( $header && $acp_main )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_news.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
		'tiny'		=> 'style/tinymce_news.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', '_switch', 'delete', 'sync')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$template->assign_vars(array('PATH' => $dir_path));
				$template->assign_var_from_handle('UIMG', 'uimg');
				$template->assign_var_from_handle('TINYMCE', 'tiny');
				
				debug($_POST);
				
				$vars = array(
					'news' => array(
						'title1' => 'input_data',
						'news_title'		=> array('validate' => 'text',	'type' => 'text:25:25',		'explain' => true,	'required' => 'input_name'),
						'news_cat'			=> array('validate' => 'int',	'type' => 'drop:newscat',	'explain' => true,	'required' => 'select_cat', 'params' => $dir_path),
						'news_text'			=> array('validate' => 'text',	'type' => 'textarea:80',		'explain' => true,	'required' => 'input_text'),
						'news_match'		=> array('validate' => 'int',	'type' => 'drop:match',		'explain' => true),
						'news_links'		=> array('validate' => 'ary',	'type' => 'links:20',		'explain' => true,	'params' => '~'),
						'news_time_public'	=> array('validate' => 'int',	'type' => 'drop:datetime',	'explain' => true,	'params' => $time),
						'news_public'		=> ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] ) ? array('validate' => 'int',	'type' => 'radio:yesno',	'explain' => true) : 'hidden',
						'news_intern'		=> array('validate' => 'int',	'type' => 'radio:yesno',	'explain' => true),
						'news_comments'		=> array('validate' => 'int',	'type' => 'radio:yesno',	'explain' => true),
						'news_rating'		=> array('validate' => 'int',	'type' => 'radio:yesno',	'explain' => true),
						'user_id'			=> 'hidden',
						'news_time_create'	=> 'hidden',
						'in_send'	=> 'hidden',
						'in_note'	=> 'hidden',
						'news_time_update'	=> 'hidden',
						'count_comment'	=> 'hidden',
						'news_views'	=> 'hidden',
						'news_rate_score'	=> 'hidden',
						'news_rate_voter'	=> 'hidden',
						'news_rate_date'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !(request('submit', TXT)) )
				{
					$data = array (
						'news_title'		=> request('news_title', 2),
						'news_cat'			=> '',
						'news_text'			=> '',
						'news_links'		=> '~',
						'user_id'			=> $userdata['user_id'],
						'news_match'		=> '',
						'news_public'		=> '0',
						'news_intern'		=> '0',
						'news_comments'		=> '1',
						'news_rating'		=> '0',
						'news_time_create'	=> $time,
						'news_time_public'	=> $time,
						
						'in_send'	=> '',
						'in_note'	=> '',
						'news_time_update'	=> '',
						'count_comment'	=> '',
						'news_views'	=> '',
						'news_rate_score'	=> '',
						'news_rate_voter'	=> '',
						'news_rate_date'	=> '',
					);
				}
				else if ( $mode == 'update' && !(request('submit', TXT)) )
				{
					$data = data(NEWS, $data_id, false, 3, 1);
					
				#	$data['news_cat'] = select_cat_name($data['news_cat']);
				#	$data['news_cat'] = search_image(NEWS_CAT, 'name', $data['news_cat']);
				#	$data['news_url'] = isset($data['news_url']) ? unserialize($data['news_url']) : array();
				#	$data['news_link'] = isset($data['news_link']) ? unserialize($data['news_link']) : array();
				}
				else
				{
					$temp = data(NEWS, $data_id, false, 1, true);
					$temp = array_keys($temp);
					unset($temp[0]);
					
					$data = build_request($temp, $vars, 'news', $error);
					
					debug($data);
					/*
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
					*/
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
						}
						else
						{
						}
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
				
				build_output($data, $vars, 'input', false, NEWS);
				
				/*
				if ( $data['news_url'] )
				{
					for ( $i = 0; $i < count($data['news_url']); $i++ )
					{
						$template->assign_block_vars('input.link_row', array(
							'NEWS'	=> $data['news_url'][$i],
							'NEWS_LINK'	=> $data['news_link'][$i],
						));
					}
				}
				
				( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] ) ? $template->assign_block_vars('input.public', array()) : false;
												
				$sql = "SELECT * FROM " . NEWS_CAT . " ORDER BY cat_order ASC";
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
				*/
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"news_time_create\" value=\"" . $data['news_time_create'] . "\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['news_title']),
					
				#	'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['title']),
				#	'L_CAT'			=> sprintf($lang['sprintf_cat'], $lang['title']),
				#	'L_MATCH'		=> $lang['match'],
				#	'L_TEXT'		=> sprintf($lang['sprintf_text'], $lang['title']),
				#	'L_LINK'		=> $lang['link'],
				#	'L_PUBLIC_TIME'	=> $lang['time'],
				#	'L_PUBLIC'		=> $lang['public'],
				#	'L_INTERN'		=> $lang['common_intern'],
				#	'L_COMMENTS'	=> $lang['common_comments'],
				#	'L_RATING'		=> sprintf($lang['sprintf_rating'], $lang['title']),
					
				#	'TITLE'			=> $data['news_title'],
				#	'TEXT'			=> html_entity_decode($data['news_text'], ENT_QUOTES),
	
				#	'S_RATING_NO'		=> (!$data['news_rating'] ) ? 'checked="checked"' : '',
				#	'S_RATING_YES'		=> ( $data['news_rating'] ) ? 'checked="checked"' : '',
				#	'S_PUBLIC_NO'		=> (!$data['news_public'] ) ? 'checked="checked"' : '',
				#	'S_PUBLIC_YES'		=> ( $data['news_public'] ) ? 'checked="checked"' : '',
				#	'S_INTERN_NO'		=> (!$data['news_intern'] ) ? 'checked="checked"' : '',
				#	'S_INTERN_YES'		=> ( $data['news_intern'] ) ? 'checked="checked"' : '',
				#	'S_COMMENTS_NO'		=> (!$data['news_comments'] ) ? 'checked="checked"' : '',
				#	'S_COMMENTS_YES'	=> ( $data['news_comments'] ) ? 'checked="checked"' : '',
					
				#	'S_DAY'		=> select_date('select', 'day', 'day',		date('d', $data['news_time_public']), time()),
				#	'S_MONTH'	=> select_date('select', 'month', 'month',	date('m', $data['news_time_public']), time()),
				#	'S_YEAR'	=> select_date('select', 'year', 'year',	date('Y', $data['news_time_public']), time()),
				#	'S_HOUR'	=> select_date('select', 'hour', 'hour',	date('H', $data['news_time_public']), time()),
				#	'S_MIN'		=> select_date('select', 'min', 'min',		date('i', $data['news_time_public']), time()),
					
				#	'IMAGE'			=> $dir_path . $data['news_cat'],
					
					
				#	'S_LIST_CAT'	=> select_box_image('select', NEWS_CAT, 'cat', $idorimage),
				#	'S_LIST_CAT'	=> $cat_image,
				#	'S_LIST_MATCH'	=> _select_match($data['match_id'], '1', 'post'),
					
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				/*
				if ( request('submit', TXT) )
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
						$data['news_cat'] = search_image(NEWS_CAT, 'id', $data['news_cat']);
						
						if ( $mode == 'create' )
						{
							$sql = sql(NEWS, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
							
							$id = $db->sql_nextid();
							
							sql(COMMENT_READ, 'create', array('type_id' => $id, 'user_id' => $userdata['user_id'], 'type' => READ_NEWS, 'read_time' => time()));
						#	sql(COMMENT_COUNT, 'create', array('type_id' => $id, 'type' => READ_NEWS));
						}
						else
						{
							$data['news_time_update'] = time();
							
							$sql = sql(NEWS, $mode, $data, 'news_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						$oCache->deleteCache('data_news');
						$oCache->deleteCache('dsp_news');
						
						log_add(LOG_ADMIN, $log, $mode, $sql);						
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}					
				}
				*/
				$template->pparse('body');
				
				break;
				
			case 'switch':
		
				$data = data(NEWS, $data_id, false, 1, true);
				
				$public = ( $data['news_public'] ) ? 0 : 1;
				
				sql(NEWS, 'update', array('news_public' => $public), 'news_id', $data_id);
				
				$oCache->deleteCache('data_news');
				$oCache->deleteCache('dsp_news');
				
				log_add(LOG_ADMIN, $log, $mode, $data_id);
				
				$index = true;
				
				break;
			
			case 'delete':
			
				$data = data(NEWS, $data_id, false, 1, true);
			
				if ( $data_id && $confirm )
				{
					$file = ( $acp_main ) ? check_sid('index.php') : check_sid($file);
					$name = ( $acp_main ) ? $lang['header_acp'] : $acp_title;
				
					$sql = sql(NEWS, $mode, $data, 'news_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $name);
					
					$oCache->deleteCache('data_news');
					$oCache->deleteCache('dsp_news');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
					$fields .= "<input type=\"hidden\" name=\"acp_main\" value=\"$acp_main\" />";
	
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['news_title']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}
				
				$template->pparse('confirm');
				
				break;
				
			case 'sync':
			
				$sql = "SELECT news_id, count_comment FROM " . NEWS . " ORDER BY news_id DESC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$news = $db->sql_fetchrowset($result);
				
				foreach ( $news as $row )
				{
					$news_id[] = $row['news_id'];
					$news_count[$row['news_id']] = $row['count_comment'];
				}
				
				$sql = "SELECT type_id, COUNT(comment_id) AS count FROM " . COMMENT . " WHERE type = " . READ_NEWS . " AND type_id IN (" . implode(', ', $news_id) . ") GROUP BY type_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$current = $db->sql_fetchrowset($result);
				
				foreach ( $current as $row )
				{
					$current_comments[$row['type_id']] = $row['count'];
				}
				
				ksort($news_count);
				ksort($current_comments);
				
				foreach ( $news_count as $id => $count )
				{
					$news_count[$id] = ( isset($current_comments[$id]) ) ? $current_comments[$id] : 0;
				}
				
				break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
	
	$fields	= '<input type="hidden" name="mode" value="create" />';
	
	$data	= data(NEWS, false, 'news_time_public DESC, news_id DESC', 1, false);
	
	if ( !$data )
	{
		$template->assign_block_vars('display.news_empty', array());
		$template->assign_block_vars('display.send_empty', array());
	}
	else
	{
		foreach ( $data as $key => $row )
		{
			if ( $row['in_send'] )
			{
				$send[] = $row;
			}
			else
			{
				$news[] = $row;
			}
		}
		
		if ( !$news )
		{
			$template->assign_block_vars('display.news_empty', array());
		}
		else
		{
			$cnt_news = count($news);
			
			for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt_news); $i++ )
			{
				$id		= $news[$i]['news_id'];
				$typ	= $news[$i]['news_intern'] ? 'sprintf_intern' : 'sprintf_normal';
				$title	= sprintf($lang[$typ], $news[$i]['news_title']);
				$status	= $news[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
				
				$public	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] )	? href('a_txt', $file, array('mode' => '_switch', $url => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
				$title	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', $url => $id), $title, '') : $title : $title;
				$update	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update') : img('i_icon', 'icon_update2', 'common_update');
				$delete	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete') : img('i_icon', 'icon_update2', 'common_update');
				
				$template->assign_block_vars('display.news_row', array(
					'TITLE'		=> "<a href=\"" . check_sid("$file?mode=_update&amp;$url=$id") . "\">$title</a>",
					'DATE'		=> create_date($userdata['user_dateformat'], $news[$i]['news_time_public'], $userdata['user_timezone']),
					'STATUS'	=> $status,
					'PUBLIC'	=> $public,
					'UPDATE'	=> $update,
					'DELETE'	=> $delete,
				));
			}
			
			$current_page = ( !$cnt_news ) ? 1 : ceil( $cnt_news / $settings['per_page_entry']['acp'] );
				
			$template->assign_vars(array(
				'PAGE_PAGING' => generate_pagination("$file?", $cnt_news, $settings['per_page_entry']['acp'], $start),
				'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ),
			));
		}
		
		if ( !$send )
		{
			$template->assign_block_vars('display.send_empty', array());
		}
		else
		{
			$cnt_send = count($send);
			
			for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt_send); $i++ )
			{
				$id		= $send[$i]['news_id'];
				$typ	= $send[$i]['news_intern'] ? 'sprintf_intern' : 'sprintf_normal';
				$title	= sprintf($lang[$typ], $send[$i]['news_title']);
				$status	= $send[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
				
				$public	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] )	? href('a_txt', $file, array('mode' => '_switch', $url => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
				$title	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', $url => $id), $title, '') : $title : $title;
				$update	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update') : img('i_icon', 'icon_update2', 'common_update');
				$delete	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete') : img('i_icon', 'icon_update2', 'common_update');
				
				$list = explode('; ', $send[$i]['in_send']);
				$send = '<a href="javascript:linkTo_UnCryptMailto(\'nbjmup;' . $list[1] . '\');">' . $list[0] . '</a>';
				
				$template->assign_block_vars('display.send_row', array(
					'TITLE'		=> "<a href=\"" . check_sid("$file?mode=_update&amp;$url=$data_id") . "\">$title</a>",
					'DATE'		=> create_date($userdata['user_dateformat'], $data[$i]['news_time_public'], $userdata['user_timezone']),
					'SEND'		=> $send,
					'STATUS'	=> $status,
					'PUBLIC'	=> $public,
					'UPDATE'	=> $update,
					'DELETE'	=> $delete,
				));
			}
		}
		
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['title']),
		'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['title']),
		
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file?mode=create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>