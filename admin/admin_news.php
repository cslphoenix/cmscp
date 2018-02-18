<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_NEWS',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'CAT'		=> array('TITLE'		=> 'ACP_NEWSCAT'),
			'NEWS'		=> array('TITLE'		=> 'ACP_NEWS'),
			'LETTER'	=> array('TITLE'		=> 'ACP_NEWSLETTER'),
			'SEND'		=> array('TITLE'		=> 'ACP_SEND'),
		),
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_NEWS';
	
	include('./pagestart.php');
	
	add_lang('news');
	acl_auth(array('A_NEWS', 'A_NEWS_CREATE', 'A_NEWS_PUBLIC', 'A_NEWS_SUBMISSION', 'A_NEWSCAT', 'A_NEWSCAT_ASSORT', 'A_NEWSCAT_CREATE', 'A_NEWSCAT_DELETE', 'A_NEWSLETTER'));

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
	$index	= request('index', TYP);
	
	( $cancel && !$index )	? redirect('admin/' . check_sid($file, true)) : false;
	( $cancel && $index )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
	$base = $settings['switch']['news'];
	$mode = (in_array($mode, array('create', 'update', 'delete', 'cat_create', 'cat_update', 'cat_delete', 'move_down', 'move_up', 'switch', 'sync'))) ? $mode : false;
	$path = $root_path . $settings['path']['newscat'];
    $_tpl = ($mode === 'delete') ? 'confirm' : 'body';
	$_top = sprintf($lang['STF_HEADER'], $lang["TITLE_" . strtoupper($action)]);
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			$template->assign_vars(array('IPATH' => $path));
			
			$vars = array(
				'news' => array(
					'title'		=> 'INPUT_DATA',
					'news_title'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;50',		'required' => 'input_title'),
					'news_cat'		=> array('validate' => INT,	'explain' => false,	'type' => 'drop:images',	'required' => 'select_newscat', 'params' => array($path, NEWS_CAT, false)),
					'news_match'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:match'),
					'news_vote'		=> array('validate' => INT,	'explain' => false,	'type' => 'drop:vote'),
					'news_text'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40',	'required' => 'input_text', 'params' => TINY_NEWS, 'class' => 'tinymce'),
					'news_links'	=> array('validate' => TXT,	'explain' => false,	'type' => 'links:20'),
					'news_date'		=> array('validate' => ($base ? INT : TXT), 'type' => ($base ? 'drop:datetime' : 'text:25;25'), 'params' => ($base ? (($mode == 'create') ? $time : '-1') : 'format')),
					'news_public'	=> ( $userdata['user_level'] == ADMIN || $userauth['A_NEWS_PUBLIC'] ) ? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
					'news_intern'	=> ( $userdata['user_level'] >= TRIAL )		? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
					'news_comments'	=> ( $settings['comments']['match'] )		? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
					'news_rate'		=> ( $settings['rating_news']['status'] )	? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
					'user_id'		=> 'hidden',
					'in_send'		=> 'hidden',
					'in_note'		=> 'hidden',
					'time_create'	=> 'hidden',
					'time_update'	=> 'hidden',
					'count_comment'	=> 'hidden',
					'news_views'	=> 'hidden',
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
					'count_comment'	=> 0,
					'news_views'	=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(NEWS, $data, false, 3, 'row');
			}
			else
			{
				$data_sql = build_request(NEWS, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'create' )
					{
						$sql = sql(NEWS, $mode, $data_sql);
						$msg = $lang['CREATE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
						
						$id = $db->sql_nextid();
						
						sql(COMMENT_READ, 'create', array('type' => READ_NEWS, 'type_id' => $id, 'user_id' => $userdata['user_id'], 'read_time' => $time));
					}
					else
					{
						$sql = sql(NEWS, $mode, $data_sql, 'news_id', $data);
						$msg = $lang['UPDATE'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_' . strtoupper($mode)], $lang["TITLE_" . strtoupper($action)], $data_sql['news_title']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'delete':
		
			$data_sql = data(NEWS, $data, false, 1, 'row');
			
			if ( $data && $accept && $userauth['A_NEWS_DELETE'] )
			{
				$file = ( $index ) ? check_sid('index.php') : check_sid($file);
				$name = ( $index ) ? $lang['acp_overview'] : $_top;
			
				$sql = sql(NEWS, $mode, $data_sql, 'news_id', $data);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $name);
				
			#	$oCache->deleteCache('data_news');
			#	$oCache->deleteCache('dsp_news');
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept && $userauth['A_NEWS_DELETE'] )
			{
				$fields .= build_fields(array(
					'mode'		=> $mode,
					'id'		=> $data,
					'index'	=> $index,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['training_vs']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}
			
			break;
			
		case 'cat_create':
		case 'cat_update':
		
			$template->assign_block_vars('input', array());
			$template->assign_vars(array('IPATH' => $path));
			
			$vars = array(
				'cat' => array(
					'title'		=> 'INPUT_DATA_CAT',
					'cat_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name',	'check' => true),
					'cat_image'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:image',	'params' => array($path, array('.png', '.jpg', '.jpeg', '.gif'), false, false)),
					'cat_order'	=> 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			if ( $mode == 'cat_create' && !$submit && $userauth['A_NEWSCAT_CREATE'] )
			{
				$data_sql = array(
					'cat_name'	=> request('cat_name', TXT),
					'cat_image'	=> '',
					'cat_order'	=> 0,
				);
			}
			else if ( $mode == 'cat_update' && !$submit )
			{
				$data_sql = data(NEWS_CAT, $data, false, 1, 'row');
			}
			else
			{
				$data_sql = build_request(NEWS_CAT, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'cat_create' && $userauth['A_NEWSCAT_CREATE'] )
					{
						$data_sql['cat_order'] = _max(NEWS_CAT, 'cat_order', false);
						
						$sql = sql(NEWS_CAT, $mode, $data_sql);
						$msg = $lang['CREATE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else if ( $userauth['A_NEWSCAT'] )
					{
						$sql = sql(NEWS_CAT, $mode, $data_sql, 'cat_id', $data);
						$msg = $lang['UPDATE'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head(exp_mode($mode), $lang["TITLE_" . strtoupper($action)], $data_sql['cat_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
		
		case 'cat_delete':
		
			$data_sql = data(NEWS, $data, false, 1, 'row');
			
			if ( $data && $accept && $userauth['a_newscat_delete'] )
			{
				$file = ( $index ) ? check_sid('index.php') : check_sid($file);
				$name = ( $index ) ? $lang['acp_overview'] : $_top;
			
				$sql = sql(NEWS, $mode, $data_sql, 'news_id', $data);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $name);
				
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
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['training_vs']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
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
				$data_sql = data(NEWS, $data, false, 1, 'row');
				
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
					$sqlout = data(NEWS, 'in_send = 0', 'news_date DESC, news_id DESC', 1, 'set');
					
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
							$typ	= $sqlout[$i]['news_intern'] ? 'STF_INTERN' : 'STF_NORMAL';
							$title	= sprintf($lang[$typ], $sqlout[$i]['news_title']);
							$status	= $sqlout[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
							
							$public	= ( $userauth['A_NEWS_PUBLIC'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
							$title	= ( $userauth['A_NEWS'] )			? ( $sqlout[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
							$submit	= ( $userauth['A_NEWS'] )			? ( $sqlout[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE');
							$delete	= ( $userauth['A_NEWS_DELETE'] )	? ( $sqlout[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE') : img('i_icon', 'icon_update2', 'COMMON_DELETE');
							
				
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
					#	'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
					#	'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['TITLE']),
						
					#	'L_EXPLAIN'	=> $lang['EXPLAIN'],
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
				
					$sqlout = data(NEWS_CAT, false, 'cat_order ASC', 1, 'set');
					
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
								
								'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'cat_update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'cat_delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							));
						}
					}
				
					break;
			}
			
			break;
	}

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
					$typ	= $data[$i]['news_intern'] ? 'STF_INTERN' : 'STF_NORMAL';
					$title	= sprintf($lang[$typ], $data[$i]['news_title']);
					$status	= $data[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
					
					$public	= ( $userdata['user_level'] == ADMIN || $userauth['A_NEWS_PUBLIC'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
					$title	= ( $userdata['user_level'] == ADMIN || $userauth['A_NEWS'] ) ? ( $userdata['user_level'] == ADMIN || $data[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
					$submit	= ( $userdata['user_level'] == ADMIN || $userauth['A_NEWS'] ) ? ( $userdata['user_level'] == ADMIN || $data[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE');
					$delete	= ( $userdata['user_level'] == ADMIN || $userauth['A_NEWS_DELETE'] ) ? ( $userdata['user_level'] == ADMIN || $data[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE');
					
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
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
						'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
						
						'UPDATE'	=> href('a_img', $file, array('mode' => 'cat_update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
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
						$typ	= $news[$i]['news_intern'] ? 'STF_INTERN' : 'STF_NORMAL';
						$title	= sprintf($lang[$typ], $news[$i]['news_title']);
						$status	= $news[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
						
						$public	= ( $userdata['user_level'] == ADMIN || $userauth['auth_npublic'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
						$title	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
						$submit	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE');
						$delete	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $news[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE');
						
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
						$typ	= $send[$i]['news_intern'] ? 'STF_INTERN' : 'STF_NORMAL';
						$title	= sprintf($lang[$typ], $send[$i]['news_title']);
						$status	= $send[$i]['news_public'] ? img('i_icon', 'icon_news_public', '') : img('i_icon', 'icon_news_privat', '');
						
						$public	= ( $userdata['user_level'] == ADMIN || $userauth['auth_npublic'] )	? href('a_txt', $file, array('mode' => 'switch', 'id' => $id), $status, '') : img('i_icon', 'icon_news_denied', '');
						$title	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_txt', $file, array('mode' => 'update', 'id' => $id), $title, '') : $title : $title;
						$submit	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE');
						$delete	= ( $userdata['user_level'] == ADMIN || $userauth['auth_nmanage'] ) ? ( $userdata['user_level'] == ADMIN || $send[$i]['user_id'] == $userdata['user_id'] ) ? href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE') : img('i_icon', 'icon_cancel2', 'COMMON_DELETE') : img('i_icon', 'icon_update2', 'COMMON_UPDATE');
						
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
		'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang["TITLE_" . strtoupper($action)]),
		'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang["TITLE_" . strtoupper($action)]),
		'L_NAME'	=> $lang["TITLE_" . strtoupper($action)],
		
		'L_EXPLAIN'	=> $lang["EXPLAIN_" . strtoupper($action)],
	
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse($_tpl);
	acp_footer();
}

?>