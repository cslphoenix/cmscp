<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_news',
		'modes'		=> array(
			'cat'		=> array('title' => 'acp_newscat'),
			'news'		=> array('title' => 'acp_news'),
			'letter'	=> array('title' => 'acp_newsletter'),
			'send'		=> array('title' => 'acp_send'),
		),
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_news';
	
	include('./pagestart.php');
	
	add_lang('news');
	acl_auth(array('a_news', 'a_news_create', 'a_news_delete', 'a_news_public', 'a_news_submission', 'a_newscat', 'a_newscat_create', 'a_newscat_delete', 'a_newsletter'));

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$time	= time();
	$log	= SECTION_NEWS;
	$file	= basename(__FILE__) . $iadds;

	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_newscat'];
	$acp_title	= sprintf($lang['stf_header'], $lang["title_$action"]);
	$index	= request('index', TYP);
	
	( $cancel && !$index )	? redirect('admin/' . check_sid($file, true)) : false;
	( $cancel && $index )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_news.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));

	$base = $settings['switch']['news'];
	$mode = (in_array($mode, array('create', 'update', 'delete', 'cat_create', 'cat_update', 'cat_delete', 'move_down', 'move_up', 'switch', 'sync'))) ? $mode : false;
    $_tpl = ($mode == 'delete') ? 'confirm' : 'body';
	
#	if ( $mode )
#	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				$template->assign_vars(array('IPATH' => $dir_path));
				
				$vars = array(
					'news' => array(
						'title1' => 'input_data',
						'news_title'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',		'required' => 'input_title'),
						'news_cat'		=> array('validate' => INT,	'explain' => false,	'type' => 'drop:images',	'required' => 'select_newscat', 'params' => array($dir_path, NEWS_CAT, false)),
						'news_match'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:match'),
						'news_vote'		=> array('validate' => INT,	'explain' => false,	'type' => 'drop:vote'),
						'news_text'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40',	'required' => 'input_text', 'params' => TINY_NEWS, 'class' => 'tinymce'),
						'news_links'	=> array('validate' => TXT,	'explain' => false,	'type' => 'links:20'),
						'news_date'		=> array('validate' => ($base ? INT : TXT), 'type' => ($base ? 'drop:datetime' : 'text:25;25'), 'params' => ($base ? (($mode == 'create') ? $time : '-1') : 'format')),
						'news_public'	=> ( $userdata['user_level'] == ADMIN || $userauth['a_news_public'] ) ? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
						'news_intern'	=> ( $userdata['user_level'] >= TRIAL )		? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
						'news_comments'	=> ( $settings['comments']['match'] )		? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
						'news_rate'		=> ( $settings['rating_news']['status'] )	? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
						'user_id'		=> 'hidden',
						'in_send'		=> 'hidden',
						'in_note'		=> 'hidden',
						'time_create'	=> 'hidden',
						'time_update'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$submit )
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
				else if ( $mode == 'update' && !$submit )
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
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
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

                $fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang["title_$action"], lang($data_sql['news_title'])),
					'L_EXPLAIN'	=> $lang['com_required'],
					
					'L_OPTION'	=> implode($lang['com_bull'], $option),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				break;
				
			case 'delete':
			
				$data_sql = data(NEWS, $data, false, 1, true);
				
				if ( $data && $accept && $userauth['a_news_delete'] )
				{
					$file = ( $index ) ? check_sid('admin_index.php') : check_sid($file);
					$name = ( $index ) ? $lang['acp_overview'] : $acp_title;
				
					$sql = sql(NEWS, $mode, $data_sql, 'news_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $name);
					
				#	$oCache->deleteCache('data_news');
				#	$oCache->deleteCache('dsp_news');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data && !$accept && $userauth['a_news_delete'] )
				{
					$fields .= build_fields(array(
						'mode'		=> $mode,
						'id'		=> $data,
						'index'	=> $index,
					));
					
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['training_vs']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}
				
				break;
				
			case 'cat_create':
			case 'cat_update':
			
				$template->assign_block_vars('input', array());
				$template->assign_vars(array('IPATH' => $dir_path));
				
				$vars = array(
					'cat' => array(
						'title1' => 'input_data',
						'cat_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name',	'check' => true),
						'cat_image'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:image',	'params' => array($dir_path, array('.png', '.jpg', '.jpeg', '.gif'), false, false)),
						'cat_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'cat_create' && !$submit && $userauth['a_newscat_create'] )
				{
					$data_sql = array(
						'cat_name'	=> request('cat_name', TXT),
						'cat_image'	=> '',
						'cat_order'	=> 0,
					);
				}
				else if ( $mode == 'cat_update' && !$submit )
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
				
				function mode($mode)
				{
					$_mode = explode('_', $mode);
					
					return $_mode[1];
				}
				
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_header'], $lang["title_$act"]),
					'L_INPUT'	=> sprintf($lang['stf_' . mode($mode)], $lang["title_$act"], $data_sql['cat_name']),
					
					'L_OPTION'	=> implode($lang['com_bull'], $option),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				break;
			
			case 'cat_delete':
			
				$data_sql = data(NEWS, $data, false, 1, true);
				
				if ( $data && $accept && $userauth['a_newscat_delete'] )
				{
					$file = ( $index ) ? check_sid('admin_index.php') : check_sid($file);
					$name = ( $index ) ? $lang['acp_overview'] : $acp_title;
				
					$sql = sql(NEWS, $mode, $data_sql, 'news_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $name);
					
				#	$oCache->deleteCache('data_news');
				#	$oCache->deleteCache('dsp_news');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data && !$accept && $userauth['a_newscat_delete'] )
				{
					$fields .= build_fields(array(
						'mode'		=> $mode,
						'id'		=> $data,
						'index'	=> $index,
					));
					
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['training_vs']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}
				
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
			
			case 'move_up':
			case 'move_down':
			
				if ( $action == 'cat' )
				{
					move(NEWS_CAT, $mode, $order);
					log_add(LOG_ADMIN, $log, $mode);
				}
			
			case 'switch':
			
				if ( $action == 'news' )
				{
					$data_sql = data(NEWS, $data, false, 1, true);
					
					$public = ( $data_sql['news_public'] ) ? 0 : 1;
					
					sql(NEWS, 'update', array('news_public' => $public), 'news_id', $data);
					
					$oCache->deleteCache('data_news');
					$oCache->deleteCache('dsp_news');
					
					log_add(LOG_ADMIN, $log, $mode, $data);
				}
				
			default:
			
				switch ( $action )
				{
					case 'news':
					
						$template->assign_block_vars('ndisplay', array());
						
						$fields = build_fields(array('mode' => 'create'));
						$sqlout = data(NEWS, 'in_send = 0', 'news_date DESC, news_id DESC', 1, false);
						
						if ( !$sqlout )
						{
							$template->assign_block_vars('ndisplay.empty', array());
						}
						else
						{
							$max = count($sqlout);
							
							for ($i = $start; $i < min($settings['ppe_acp'] + $start, $max); $i++)
							{
								$id		= $sqlout[$i]['news_id'];
								$typ	= $sqlout[$i]['news_intern'] ? 'stf_intern' : 'stf_normal';
								$title	= sprintf($lang[$typ], $sqlout[$i]['news_title']);
								$status	= $sqlout[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
								
								$public	= ( $userauth['a_news_public'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
								$title	= ( $userauth['a_news'] )			? ( $sqlout[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
								$submit	= ( $userauth['a_news'] )			? ( $sqlout[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update') : img('i_icon', 'icon_update2', 'com_update') : img('i_icon', 'icon_update2', 'com_update');
								$delete	= ( $userauth['a_news_delete'] )	? ( $sqlout[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete') : img('i_icon', 'icon_update2', 'com_update');
								
					
								$template->assign_block_vars('ndisplay.row', array(
									'TITLE'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, $sqlout[$i]['news_title']),
								#	'TITLE'		=> "<a href=\"" . check_sid("$file&mode=nupdate&amp;id=$id") . "\">$title</a>",
									'DATE'		=> create_date($userdata['user_dateformat'], $sqlout[$i]['news_date'], $userdata['user_timezone']),
									'STATUS'	=> $status,
									'PUBLIC'	=> $public,
									'UPDATE'	=> $submit,
									'DELETE'	=> $delete,
								));
							}
						}
						
						$current_page = ($max) ? ceil($max/$settings['ppe_acp']) : 1;
					
						$template->assign_vars(array(
						#	'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
						#	'L_CREATE'	=> sprintf($lang['stf_create'], $lang['title']),
							
						#	'L_EXPLAIN'	=> $lang['explain'],
						#	'L_NAME'	=> $lang['game_name'],
							
							'PAGE_PAGING' => generate_pagination($file, $max, $settings['ppe_acp'], $start),
							'PAGE_NUMBER' => sprintf($lang['common_page_of'], (floor($start/$settings['ppe_acp'])+1), $current_page),
					
						#	'S_ACTION'	=> check_sid($file),
						#	'S_FIELDS'	=> $fields,
						));
					
						break;
						
					case 'cat':
		
						$template->assign_block_vars('cdisplay', array());
						$fields = build_fields(array('mode' => 'cat_create'));
					
						$sqlout = data(NEWS_CAT, false, 'cat_order ASC', 1, false);
						
						if ( !$sqlout )
						{
							$template->assign_block_vars('cdisplay.empty', array());
						}
						else
						{
							$max = count($sqlout);
							
							foreach ( $sqlout as $row )
							{
								$id		= $row['cat_id'];
								$name	= $row['cat_name'];
								$order	= $row['cat_order'];
					
								$template->assign_block_vars('cdisplay.row', array(
									'NAME'		=> href('a_txt', $file, array('mode' => 'cat_update', 'id' => $id), $name, $name),
									
									'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
									'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
									
									'UPDATE'	=> href('a_img', $file, array('mode' => 'cat_update', 'id' => $id), 'icon_update', 'com_update'),
									'DELETE'	=> href('a_img', $file, array('mode' => 'cat_delete', 'id' => $id), 'icon_cancel', 'com_delete'),
								));
							}
						}
					
						break;
				}
				
				break;
		}
#	}
	/*
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
					
				for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt); $i++)
				{
					$id		= $data[$i]['news_id'];
					$typ	= $data[$i]['news_intern'] ? 'stf_intern' : 'stf_normal';
					$title	= sprintf($lang[$typ], $data[$i]['news_title']);
					$status	= $data[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
					
					$public	= ( $userdata['user_level'] == ADMIN || $userauth['a_news_public'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
					$title	= ( $userdata['user_level'] == ADMIN || $userauth['a_news'] ) ? ( $userdata['user_level'] == ADMIN || $data[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
					$submit	= ( $userdata['user_level'] == ADMIN || $userauth['a_news'] ) ? ( $userdata['user_level'] == ADMIN || $data[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update') : img('i_icon', 'icon_update2', 'com_update') : img('i_icon', 'icon_update2', 'com_update');
					$delete	= ( $userdata['user_level'] == ADMIN || $userauth['a_news_delete'] ) ? ( $userdata['user_level'] == ADMIN || $data[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete') : img('i_icon', 'icon_update2', 'com_update');
					
					$template->assign_block_vars('ndisplay.row', array(
						'TITLE'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, $data[$i]['news_title']),
					#	'TITLE'		=> "<a href=\"" . check_sid("$file&mode=nupdate&amp;id=$id") . "\">$title</a>",
						'DATE'		=> create_date($userdata['user_dateformat'], $data[$i]['news_date'], $userdata['user_timezone']),
						'STATUS'	=> $status,
						'PUBLIC'	=> $public,
						'UPDATE'	=> $submit,
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
						
						'UPDATE'	=> href('a_img', $file, array('mode' => 'cat_update', 'id' => $id), 'icon_update', 'com_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
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
						$typ	= $news[$i]['news_intern'] ? 'stf_intern' : 'stf_normal';
						$title	= sprintf($lang[$typ], $news[$i]['news_title']);
						$status	= $news[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
						
						$public	= ( $userdata['user_level'] == ADMIN || $userauth['auth_npublic'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
						$title	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
						$submit	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update') : img('i_icon', 'icon_update2', 'com_update') : img('i_icon', 'icon_update2', 'com_update');
						$delete	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete') : img('i_icon', 'icon_update2', 'com_update');
						
						$template->assign_block_vars('display.news_row', array(
						#	'TITLE'		=> "<a href=\"" . check_sid("$file?mode=_update&amp;$url=$id") . "\">$title</a>",
							'DATE'		=> create_date($userdata['user_dateformat'], $news[$i]['news_date'], $userdata['user_timezone']),
							'STATUS'	=> $status,
							'PUBLIC'	=> $public,
							'UPDATE'	=> $submit,
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
						$typ	= $send[$i]['news_intern'] ? 'stf_intern' : 'stf_normal';
						$title	= sprintf($lang[$typ], $send[$i]['news_title']);
						$status	= $send[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
						
						$public	= ( $userdata['user_level'] == ADMIN || $userauth['auth_npublic'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
						$title	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
						$submit	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update') : img('i_icon', 'icon_update2', 'com_update') : img('i_icon', 'icon_update2', 'com_update');
						$delete	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete') : img('i_icon', 'icon_cancel2', 'com_delete') : img('i_icon', 'icon_update2', 'com_update');
						
						$list = explode('; ', $send[$i]['in_send']);
						$send = '<a href="javascript:linkTo_UnCryptMailto(\'nbjmup;' . $list[1] . '\');">' . $list[0] . '</a>';
						
						$template->assign_block_vars('display.send_row', array(
							'TITLE'		=> "<a href=\"" . check_sid("$file?mode=_update&amp;$url=$data") . "\">$title</a>",
							'DATE'		=> create_date($userdata['user_dateformat'], $data[$i]['news_date'], $userdata['user_timezone']),
							'SEND'		=> $send,
							'STATUS'	=> $status,
							'PUBLIC'	=> $public,
							'UPDATE'	=> $submit,
							'DELETE'	=> $delete,
						));
					}
				}
				
			}
		
			break;
	}
	*/
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['stf_header'], $lang["title_$action"]),
		'L_CREATE'	=> sprintf($lang['stf_create'], $lang["title_$action"]),
		'L_NAME'	=> $lang["title_$action"],
		
		'L_EXPLAIN'	=> $lang["explain_$action"],
	
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse($_tpl);
	
	acp_footer();
}

?>