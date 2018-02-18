<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_RATE',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'ACP_RATE'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_RATE';
	
	include('./pagestart.php');
	
	add_lang('rate');
	acl_auth('A_RATING');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_RATE;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);
	
	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
	
	$mode = (in_array($mode, array('create', 'delete', 'move_up', 'move_down', 'update'))) ? $mode : false;
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'game' => array(
						'title'	=> 'INPUT_DATA',
						'game_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name',	'check' => true),
						'game_tag'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:server','required' => 'select_tag',	'check' => true, 'params' => true),
						'game_image'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:image',	'params' => array($dir_path, array('.png', '.jpg', '.jpeg', '.gif'), true, true)),
						'game_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$data_sql = array(
						'game_name'		=> request('game_name', TXT),
						'game_tag'		=> '',
						'game_image'	=> '',
						'game_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(GAMES, $data, false, 1, 'row');
				}
				else
				{
					$data_sql = build_request(GAMES, $vars, $error, $mode);
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$data_sql['game_order'] = _max(GAMES, 'game_order', false);
							
							$sql = sql(GAMES, $mode, $data_sql);
							$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
						}
						else
						{
							$sql = sql(GAMES, $mode, $data_sql, 'game_id', $data);
							$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(GAMES, $vars, $data_sql);

				$template->assign_vars(array(
				#	'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
					'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['game_name']),
					'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
					
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');

				break;
			
			case 'move_up':
			case 'move_down':
			
				move(GAMES, $mode, $order);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;

			case 'delete':

				$data_sql = data(GAMES, $data, false, 1, 'row');

				if ( $data && $confirm )
				{
					$sql = sql(GAMES, $mode, $data_sql, 'game_id', $data);
					$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);

					orders(GAMES);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['COMMON_CONFIRM'],
						'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data['game_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
				}

				$template->pparse('confirm');

				break;
		}

		if ( $index != true )
		{
			acp_footer();
			exit;
		}
	}

	$template->assign_block_vars('display', array());
	
	$fields = build_fields(array('mode' => 'create'));
#	$sqlout = data(NEWS, false, 'game_order ASC', 1, false);

	$news_rating = $rating = array();

	$sql = "SELECT n.news_id, n.news_title, n.news_date, nc.cat_name
				FROM " . NEWS . " n
			LEFT JOIN " . NEWS_CAT . " nc ON n.news_cat = nc.cat_id
				WHERE n.news_public != 0
				ORDER BY n.news_date DESC, n.news_id DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		$news_rating[$row['news_id']] = $row;
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT * FROM " . RATE . " WHERE rate_type_id IN (" . implode(', ', array_keys($news_rating)) . ") ORDER BY rate_id DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		$rating[$row['rate_type']][$row['rate_type_id']][] = $row;
	}
	$db->sql_freeresult($result);
	
	debug($news_rating, 'news_rating');
	debug($rating, 'rating');

	if ( !$news_rating )
	{
		$template->assign_block_vars('display.none', array());
	}
	else
	{
		if ( $rating )
		{	
			foreach ( $rating as $_type => $_row_type )
			{
			#	if ( $_type == RATE_NEWS && is_array($_row_type) )
			#	{
					foreach ( $_row_type as $_type_id => $_row_users )
					{
						foreach ( $_row_users as $row )
						{
							@$cnt_value[$_type][$_type_id] += $row['rate_value'];
						}
					}
			#	}
			}
		}
		else
		{
			$cnt_value = '';
		}
		
	#	debug($cnt_value, 'test');
		
		foreach ( $news_rating as $row )
		{
			$template->assign_block_vars('display.news.row', array(
				'NAME'		=> $row['news_title'],
			#	'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
			#	'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
				
				'RATE'		=> sprintf('%s %s &oslash; %s/%s', $cnt_value[RATE_NEWS], $lang['common_rating'], $rate_ave, $settings['rating_news']['maximal']),
			));
		#	$count_value = 0;
			
		#	debug($n_data[$key]['user'], 'key');
			
		#	foreach ( $n_data[$key]['user'] as $rate )
		#	{
		#		$count_value += $rate['value'];
		#	}
			
		#	$rate_cnt	= count($n_data[$key]['user']);
		#	$rate_sum	= $count_value;
		#	$rate_ave	= round(($rate_sum/$rate_cnt), 1);
			
		#	$template->assign_block_vars('display.news', array(
		#		'NAME'		=> $row['news_title'],
			#	'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
			#	'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
				
		#		'RATE'		=> sprintf('%s %s &oslash; %s/%s', $rate_cnt, $lang['common_rating'], $rate_ave, $settings['rating_news']['maximal']),
		#	));
			
		#	if ( isset($n_data[$key]['user']) )
		#	{
		#		foreach ( $n_data[$key]['user'] as $drow )
		#		{
		#			$template->assign_block_vars('display.news.rate', array(
		#				'NAME'		=> $drow['userid'],
					#	'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
					#	'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
					#	'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						
					#	'TAG'		=> $row['game_tag'],
					#	'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
						
					#	'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
					#	'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
		#			));
		#		}
		#	}
		
		}	
			
				
		#	$id		= $row['game_id'];
		#	$name	= $row['game_name'];
		#	$order	= $row['game_order'];
		/*
			$template->assign_block_vars('display.news', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
				
				'TAG'		=> $row['game_tag'],
				'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
				
				'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
				'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
			));
			
			$template->assign_block_vars('display.gallery', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
				
				'TAG'		=> $row['game_tag'],
				'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
				
				'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
				'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
			));
			
			$template->assign_block_vars('display.download', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
				
				'TAG'		=> $row['game_tag'],
				'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
				
				'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
				'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
			));
		*/
		
		
	#	debug($rate_data_n);
	#	debug($rate_data_d);
	#	debug($rate_data_g);
	}

	$template->assign_vars(array(
		'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
		'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['TITLE']),
		'L_EXPLAIN'	=> $lang['EXPLAIN'],

		'L_NAME'	=> $lang['game_name'],

		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse($_tpl);
	acp_footer();
}

?>