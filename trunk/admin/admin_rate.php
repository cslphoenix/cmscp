<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_rate',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_rate'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_rate';
	
	include('./pagestart.php');
	
	add_lang('rate');
	acl_auth('a_rating');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$log	= SECTION_RATE;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_rate.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
#	debug($_POST, '_POST');
	
	$mode = (in_array($mode, array('create', 'update', 'move_up', 'move_down', 'delete'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'game' => array(
						'title' => 'input_data',
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
					$data_sql = data(GAMES, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(GAMES, $vars, $error, $mode);
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$data_sql['game_order'] = maxa(GAMES, 'game_order', false);
							
							$sql = sql(GAMES, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(GAMES, $mode, $data_sql, 'game_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
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
				#	'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['game_name']),
					'L_EXPLAIN'	=> $lang['com_required'],
					
					
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

				$data_sql = data(GAMES, $data, false, 1, true);

				if ( $data && $confirm )
				{
					$sql = sql(GAMES, $mode, $data_sql, 'game_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					orders(GAMES);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['game_name']),

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
		}

		if ( $index != true )
		{
			acp_footer();
			exit;
		}
	}

	$template->assign_block_vars('display', array());

	$fields	= '<input type="hidden" name="mode" value="create" />';
	
	$sql = "SELECT n.news_id, n.news_title, n.news_date, nc.cat_name
				FROM " . NEWS . " n
			LEFT JOIN " . NEWS_CAT . " nc ON n.news_cat = nc.cat_id
				ORDER BY n.news_date DESC LIMIT 0, 5";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$n_rate = array();
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$n_rate[$row['news_id']]['news_title']	= $row['news_title'];
		$n_rate[$row['news_id']]['cat_name']	= $row['cat_name'];
		$n_rate[$row['news_id']]['news_date']	= $row['news_date'];
	}
	
	$sql = "SELECT * FROM " . RATE . " WHERE rate_type = " . RATE_NEWS . " AND rate_type_id IN (" . implode(', ', array_keys($n_rate)) . ") ORDER BY rate_id DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$n_data = array();
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$n_data[$row['rate_type_id']]['rate'] = $row['rate_id'];
		$n_data[$row['rate_type_id']]['user'][] = array('userid' => $row['rate_userid'], 'userip' => $row['rate_userip'], 'time' => $row['rate_time'], 'value' => $row['rate_value']);
	}
	$db->sql_freeresult($result);
	
	if ( !$n_rate )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		foreach ( $n_rate as $key => $row )
		{
			$count_value = 0;
			
		#	debug($n_data[$key]['user'], 'key');
			
			foreach ( $n_data[$key]['user'] as $rate )
			{
				$count_value += $rate['value'];
			}
			
			$rate_cnt	= count($n_data[$key]['user']);
			$rate_sum	= $count_value;
			$rate_ave	= round(($rate_sum/$rate_cnt), 1);
			
			$template->assign_block_vars('display.news', array(
				'NAME'		=> $row['news_title'],
			#	'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
			#	'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
				
				'RATE'		=> sprintf('%s %s &oslash; %s/%s', $rate_cnt, $lang['common_rating'], $rate_ave, $settings['rating_news']['maximal']),
			));
			
			if ( isset($n_data[$key]['user']) )
			{
				foreach ( $n_data[$key]['user'] as $drow )
				{
					$template->assign_block_vars('display.news.rate', array(
						'NAME'		=> $drow['userid'],
					#	'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
					#	'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					#	'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
						
					#	'TAG'		=> $row['game_tag'],
					#	'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
						
					#	'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					#	'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
					));
				}
			}
		
		}	
			
				
		#	$id		= $row['game_id'];
		#	$name	= $row['game_name'];
		#	$order	= $row['game_order'];
		/*
			$template->assign_block_vars('display.news', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
				
				'TAG'		=> $row['game_tag'],
				'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
				
				'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			));
			
			$template->assign_block_vars('display.gallery', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
				
				'TAG'		=> $row['game_tag'],
				'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
				
				'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			));
			
			$template->assign_block_vars('display.download', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
				
				'TAG'		=> $row['game_tag'],
				'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
				
				'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			));
		*/
		
		
	#	debug($rate_data_n);
	#	debug($rate_data_d);
	#	debug($rate_data_g);
	}

	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['stf_create'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],

		'L_NAME'	=> $lang['game_name'],

		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');

	acp_footer();
}

?>