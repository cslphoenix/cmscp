<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_news',
		'modes'		=> array(
			'cat'	=> array('title' => 'acp_newscat', 'auth' => array('auth_news', 'auth_news_public')),
			'news'	=> array('title' => 'acp_news', 'auth' => array('auth_news', 'auth_news_public')),
			'send'	=> array('title' => 'acp_send', 'auth' => array('auth_news', 'auth_news_public')),
		),
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$update = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_news';
	
	include('./pagestart.php');
	
	add_lang('news');

	$error	= '';
	$index	= '';
	$fields	= '';
	$time	= time();
	
	$log	= SECTION_NEWS;

	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_newscat'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang["title_$act"]);
	$acp_main	= request('acp_main', TYP);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_news'] && !$userauth['auth_news_public'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel && !$acp_main )	? redirect('admin/' . check_sid($file, true)) : false;
	( $cancel && $acp_main )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_news.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
#	debug($_POST, '_POST');
	
	$mode = ( in_array($mode, array('create', 'update', 'cat_create', 'cat_update', 'move_down', 'move_up', 'switch', 'delete', 'sync')) ) ? $mode : '';
	
	$switch = $settings['switch']['news'];
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				$template->assign_vars(array('IPATH' => $dir_path));
				
				$vars = array(
					'news' => array(
						'title1' => 'input_data',
						'news_title'	=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25', 'required' => 'input_name'),
						'news_cat'		=> array('validate' => INT,	'explain' => false, 'type' => 'drop:images', 'params' => array($dir_path, NEWS_CAT, false), 'required' => 'select_cat'),
						'news_match'	=> array('validate' => INT,	'explain' => false, 'type' => 'drop:match'),
						'news_vote'		=> array('validate' => INT,	'explain' => false, 'type' => 'drop:vote'),
						'news_text'		=> array('validate' => TXT,	'explain' => false, 'type' => 'textarea:40',	'required' => 'input_text', 'params' => TINY_NEWS),
					#	'news_links'	=> array('validate' => ARY,	'explain' => false,	'type' => 'links:20'),
						'news_date'		=> array('validate' => ($switch ? INT : TXT), 'type' => ($switch ? 'drop:datetime' : 'text:25;25'), 'params' => ($switch ? (($mode == 'create') ? $time : '-1') : 'format')),
						'news_public'	=> ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] ) ? array('validate' => INT,	'explain' => false, 'type' => 'radio:yesno') : 'hidden',
						'news_intern'	=> ( $userdata['user_level'] >= TRIAL )		? array('validate' => INT,	'explain' => false, 'type' => 'radio:yesno') : 'hidden',
						'news_comments'	=> ( $settings['comments']['match'] )		? array('validate' => INT,	'explain' => false, 'type' => 'radio:yesno') : 'hidden',
						'news_rate'		=> ( $settings['rating_news']['status'] )	? array('validate' => INT,	'explain' => false, 'type' => 'radio:yesno') : 'hidden',
						'user_id'		=> 'hidden',
						'in_send'		=> 'hidden',
						'in_note'		=> 'hidden',
						'time_create'	=> 'hidden',
						'time_update'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$update )
				{
					$data_sql = array (
						'news_title'	=> request('news_title', TXT),
						'news_cat'		=> '',
						'news_match'	=> '',
						'news_vote'		=> '',
						'news_text'		=> '',
						'news_links'	=> 'a:0:{}',
						'news_date'		=> $time,
						'news_public'	=> 0,
						'news_intern'	=> 0,
						'news_comments'	=> 0,
						'news_rate'		=> 0,
						'user_id'		=> $userdata['user_id'],
						'in_send'		=> '',
						'in_note'		=> '',
						'time_create'	=> $time,
						'time_update'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$update )
				{
					$data_sql = data(NEWS, $data, false, 3, true);
				}
				else
				{
					$data_sql = build_request(NEWS, $vars, $error, $mode);
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$sql = sql(NEWS, $mode, $data_sql);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
							
							$id = $db->sql_nextid();
							
							sql(COMMENT_READ, 'create', array('type' => READ_NEWS, 'type_id' => $id, 'user_id' => $userdata['user_id'], 'read_time' => $time));
						}
						else
						{
							$sql = sql(NEWS, $mode, $data_sql, 'news_id', $data);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&amp;id=$data"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
						
					#	$oCache->deleteCache('data_news');
					#	$oCache->deleteCache('dsp_news');
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(NEWS, $vars, $data_sql);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang["title_$act"]),
					'L_EXPLAIN'	=> $lang['common_required'],
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang["title_$act"], $data_sql['news_title']),
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&amp;id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
				
			case 'cat_create':
			case 'cat_update':
			
				$template->assign_block_vars('input', array());
				$template->assign_vars(array('IPATH' => $dir_path));
				
				$vars = array(
					'cat' => array(
						'title1' => 'input_data',
						'cat_name'	=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25',	'required' => 'input_name'),
						'cat_image'	=> array('validate' => TXT,	'explain' => false, 'type' => 'drop:image',	'params' => array($dir_path, array('.png', '.jpg', '.jpeg', '.gif'), true, false)),
						'cat_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'cat_create' && !$update )
				{
					$data_sql = array(
						'cat_name'	=> request('cat_name', TXT),
						'cat_image'	=> '',
						'cat_order'	=> 0,
					);
				}
				else if ( $mode == 'cat_update' && !$update )
				{
					$data_sql = data(NEWS_CAT, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(NEWS_CAT, $vars, $error, $mode);
					
					if ( !$error )
					{
						if ( $mode == 'cat_create' )
						{
							$data_sql['cat_order'] = maxa(NEWS_CAT, 'cat_order', false);
							
							$sql = sql(NEWS_CAT, $mode, $data_sql);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(NEWS_CAT, $mode, $data_sql, 'cat_id', $data);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);						
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(NEWS_CAT, $vars, $data_sql);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang["title_$act"]),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang["title_$act"], $data_sql['cat_name']),
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
				
			case 'move_up':
			case 'move_down':
			
				moveset(NEWS_CAT, $mode, $order);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
				
			case 'switch':
		
				$data_sql = data(NEWS, $data, false, 1, true);
				
				$public = ( $data['news_public'] ) ? 0 : 1;
				
				sql(NEWS, 'update', array('news_public' => $public), 'news_id', $data);
				
				$oCache->deleteCache('data_news');
				$oCache->deleteCache('dsp_news');
				
				log_add(LOG_ADMIN, $log, $mode, $data);
				
				$index = true;
				
				break;
			
			case 'delete':
			
				$data_sql = data(NEWS, $data, false, 1, true);
			
				if ( $data && $confirm )
				{
					$file = ( $acp_main ) ? check_sid('index.php') : check_sid($file);
					$name = ( $acp_main ) ? $lang['header_acp'] : $acp_title;
				
					$sql = sql(NEWS, $mode, $data_sql, 'news_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $name);
					
					$oCache->deleteCache('data_news');
					$oCache->deleteCache('dsp_news');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
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
	
	switch ( $action )
	{
		case 'news':
		
			$template->assign_block_vars('ndisplay', array());
			
			$fields	= '<input type="hidden" name="mode" value="create" />';
		
			$data = data(NEWS, 'in_send = 0', 'news_date DESC, news_id DESC', 1, false);
			
			if ( !$data )
			{
				$template->assign_block_vars('ndisplay.empty', array());
			}
			else
			{
				$cnt = count($data);
					
				for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt); $i++ )
				{
					$id		= $data[$i]['news_id'];
					$typ	= $data[$i]['news_intern'] ? 'sprintf_intern' : 'sprintf_normal';
					$title	= sprintf($lang[$typ], $data[$i]['news_title']);
					$status	= $data[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
					
					$public	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
					$title	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $data[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
					$update	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $data[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update') : img('i_icon', 'icon_update2', 'common_update');
					$delete	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $data[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete') : img('i_icon', 'icon_update2', 'common_update');
					
					$template->assign_block_vars('ndisplay.row', array(
						'TITLE'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, $data[$i]['news_title']),
					#	'TITLE'		=> "<a href=\"" . check_sid("$file&mode=nupdate&amp;id=$id") . "\">$title</a>",
						'DATE'		=> create_date($userdata['user_dateformat'], $data[$i]['news_date'], $userdata['user_timezone']),
						'STATUS'	=> $status,
						'PUBLIC'	=> $public,
						'UPDATE'	=> $update,
						'DELETE'	=> $delete,
					));
				}
				
				$current_page = ( !$cnt ) ? 1 : ceil( $cnt / $settings['per_page_entry']['acp'] );
					
				$template->assign_vars(array(
					'PAGE_PAGING' => generate_pagination("$file", $cnt, $settings['per_page_entry']['acp'], $start),
					'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ),
				));
			}
		
			break;
			
		case 'cat':
		
			$template->assign_block_vars('cdisplay', array());
			
			$fields	= '<input type="hidden" name="mode" value="cat_create" />';
		
			$cat = data(NEWS_CAT, false, 'cat_order ASC', 1, false);
			$max = array_pop(end($cat));
			
			if ( !$cat )
			{
				$template->assign_block_vars('cdisplay.empty', array());
			}
			else
			{
				$cnt = count($cat);
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					$id		= $cat[$i]['cat_id'];
					$name	= $cat[$i]['cat_name'];
					$order	= $cat[$i]['cat_order'];
		
					$template->assign_block_vars('cdisplay.row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'cat_update', 'id' => $id), $name, $name),
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
						
						'UPDATE'	=> href('a_img', $file, array('mode' => 'cat_update', 'id' => $id), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
					));
				}
			}
		
			break;
			
		case 'newsletter':
		
			break;
			
		case 'send':
		
			$template->assign_block_vars('ndisplay', array());
			
			$fields	= '<input type="hidden" name="mode" value="create" />';
		
			$data = data(NEWS, 'in_send = 0', 'news_date DESC, news_id DESC', 1, false);
			
			if ( !$data )
			{
				$template->assign_block_vars('ndisplay.news_empty', array());
				$template->assign_block_vars('ndisplay.send_empty', array());
			}
			else
			{
				foreach ( $data as $key => $row )
				{
					if ( !$row['in_send'] )
					{
						$news[] = $row;
					}
				}
				
				if ( !$news )
				{
					$template->assign_block_vars('ndisplay.news_empty', array());
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
						
						$public	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
						$title	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
						$update	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update') : img('i_icon', 'icon_update2', 'common_update');
						$delete	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete') : img('i_icon', 'icon_update2', 'common_update');
						
						$template->assign_block_vars('display.news_row', array(
						#	'TITLE'		=> "<a href=\"" . check_sid("$file?mode=_update&amp;$url=$id") . "\">$title</a>",
							'DATE'		=> create_date($userdata['user_dateformat'], $news[$i]['news_date'], $userdata['user_timezone']),
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
						
						$public	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news_public'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
						$title	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
						$update	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update') : img('i_icon', 'icon_update2', 'common_update');
						$delete	= ( $userdata['user_level'] == ADMIN || $userauth['auth_news'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete') : img('i_icon', 'icon_update2', 'common_update');
						
						$list = explode('; ', $send[$i]['in_send']);
						$send = '<a href="javascript:linkTo_UnCryptMailto(\'nbjmup;' . $list[1] . '\');">' . $list[0] . '</a>';
						
						$template->assign_block_vars('display.send_row', array(
							'TITLE'		=> "<a href=\"" . check_sid("$file?mode=_update&amp;$url=$data") . "\">$title</a>",
							'DATE'		=> create_date($userdata['user_dateformat'], $data[$i]['news_date'], $userdata['user_timezone']),
							'SEND'		=> $send,
							'STATUS'	=> $status,
							'PUBLIC'	=> $public,
							'UPDATE'	=> $update,
							'DELETE'	=> $delete,
						));
					}
				}
				
			}
		
			break;
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang["title_$act"]),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang["title_$act"]),
		'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang["title_$act"]),
		
		'L_EXPLAIN'	=> $lang["explain_$act"],
	
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>