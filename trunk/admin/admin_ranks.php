<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_ranks',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_ranks'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_ranks';
	
	include('./pagestart.php');

	add_lang('ranks');

	$error = $index	= $fields = '';
	
	$log	= SECTION_RANK;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_ranks'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['a_ranks'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_ranks.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down'))) ? $mode : false;
	
	debug($_POST, '_POST');
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				$template->assign_vars(array('IPATH' => $dir_path));
								
				$vars = array(
					'rank' => array(
						'title1' => 'input_data',
						'rank_name'			=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'rank_image'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:image', 'params' => array($dir_path, array('.png', '.jpg', '.jpeg', '.gif'), true, true)),
						'rank_type'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:ranks', 'params' => array('type', true, false)),
						'rank_special'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'params' => array(false, false, false), 'divbox' => true),
						'rank_standard'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'params' => array(false, false, false), 'divbox' => true),
						'rank_min'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:5;5', 'divbox' => true),
						'rank_order'		=> 'hidden',
					)
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$type = ( isset($_POST['rank_type']) ) ? key($_POST['rank_type']) : '';
					$name = ( isset($_POST['rank_name']) ) ? $_POST['rank_name'][$type] : '';
					
					$data_sql = array(
						'rank_name'		=> $name,
						'rank_type'		=> $type,
						'rank_min'		=> 0,
						'rank_special'	=> 0,
						'rank_image'	=> '',
						'rank_standard'	=> 0,
						'rank_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(RANKS, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(RANKS, $vars, $error, $mode);
					
					if ( !$error )
					{
						($data_sql['rank_standard']) ? sql(RANKS, 'update', array('rank_standard' => '0'), 'rank_type', $data_sql['rank_type']) : '';
												
						if ( $mode == 'create' )
						{
							$data_sql['rank_order'] = maxa(RANKS, 'rank_order', 'rank_type = ' . $data_sql['rank_type']);
							
							$sql = sql(RANKS, $mode, $data_sql);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(RANKS, $mode, $data_sql, 'rank_id', $data);
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
				
				build_output(RANKS, $vars, $data_sql);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_' . $mode], $lang['title'], lang($data_sql['rank_name'])),
					'L_EXPLAIN'	=> $lang['common_required'],
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
				
			case 'move_up':
			case 'move_down':
			
				move(RANKS, $mode, $order, false, false, false, false, $type);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
			
			case 'delete':
			
				/*	im moment wird der rang einfach gelöscht, sollte aber meldung geben falls rang verwendet wird und löschung sperren!	*/
			
				$data_sql = data(RANKS, $data, false, 1, true);
			
				if ( $data && $confirm )
				{
					$sql = sql(RANKS, $mode, $data_sql, 'rank_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(RANKS, $data['rank_type']);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					if ( $data['rank_standard'] )
					{
						message(GENERAL_ERROR, $lang['msg_select_standard'] . $lang['back']);
					}
					else
					{
						$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
						$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";			
			
						$template->assign_vars(array(
							'M_TITLE'	=> $lang['common_confirm'],
							'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['rank_name']),
							
							'S_ACTION'	=> check_sid($file),
							'S_FIELDS'	=> $fields,
						));
					}
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
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
	
	$fields = '<input type="hidden" name="mode" value="create" />';

	$max_f = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_FORUM . ' AND rank_special = 1');
	$max_p = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_PAGE);
	$max_t = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_TEAM);
	
	$tmp_f = data(RANKS, 'rank_type = ' . RANK_FORUM, 'rank_special DESC, rank_order ASC', 1, false);
	$tmp_p = data(RANKS, 'rank_type = ' . RANK_PAGE, 'rank_special DESC, rank_order ASC', 1, false);
	$tmp_t = data(RANKS, 'rank_type = ' . RANK_TEAM, 'rank_special DESC, rank_order ASC', 1, false);

	if ( !$tmp_f )
	{
		$template->assign_block_vars('display.forum_empty', array());
	}
	else
	{
		$cnt_f = count($tmp_f);
		
		for ( $i = 0; $i < $cnt_f; $i++ )
		{
			$id		= $tmp_f[$i]['rank_id'];
			$name	= $tmp_f[$i]['rank_name'];
			$image	= $tmp_f[$i]['rank_image'];
			$order	= $tmp_f[$i]['rank_order'];
			
			$template->assign_block_vars('display.forum_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, ''),
				'MIN'		=> ( $tmp_f[$i]['rank_special'] == '0' ) ? $tmp_f[$i]['rank_min'] : ' - ',
				'SPECIAL'	=> ( $tmp_f[$i]['rank_special'] == '1' ) ? $lang['common_yes'] : $lang['common_no'],
				'IMAGE'		=> $image ? img('i_icon', 'icon_image', '') : img('i_icon', 'icon_image2', ''),
				
				'MOVE_UP'	=> ( $tmp_f[$i]['rank_special'] && $order != '10' )		? href('a_img', $file, array('mode' => 'move_up',	'type' => RANK_FORUM, 'id' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $tmp_f[$i]['rank_special'] && $order != $max_f )	? href('a_img', $file, array('mode' => 'move_down', 'type' => RANK_FORUM, 'id' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_p )
	{
		$template->assign_block_vars('display.page_empty', array());
	}
	else
	{
		$cnt_p = count($tmp_p);
		
		for ( $i = 0; $i < $cnt_p; $i++ )
		{
			$id		= $tmp_p[$i]['rank_id'];
			$name	= $tmp_p[$i]['rank_name'];
			$image	= $tmp_p[$i]['rank_image'];
			$order	= $tmp_p[$i]['rank_order'];
				
			$template->assign_block_vars('display.page_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, ''),
				'STANDARD'	=> $tmp_p[$i]['rank_standard'] ? $lang['rank_standard'] : '',
				'IMAGE'		=> $image ? img('i_icon', 'icon_image', '') : img('i_icon', 'icon_image2', ''),
				
				'MOVE_UP'	=> ( $order != '10' )	? href('a_img', $file, array('mode' => 'order', 'type' => RANK_PAGE, 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_p )	? href('a_img', $file, array('mode' => 'order', 'type' => RANK_PAGE, 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_t )
	{
		$template->assign_block_vars('display.team_empty', array());
	}
	else
	{
		$cnt_t = count($tmp_t);
		
		for ( $i = 0; $i < $cnt_t; $i++ )
		{
			$id		= $tmp_t[$i]['rank_id'];
			$name	= $tmp_t[$i]['rank_name'];
			$image	= $tmp_t[$i]['rank_image'];
			$order	= $tmp_t[$i]['rank_order'];
				
			$template->assign_block_vars('display.team_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, ''),
				'STANDARD'	=> $tmp_t[$i]['rank_standard'] ? $lang['rank_standard'] : '',
				'IMAGE'		=> $image ? img('i_icon', 'icon_image', '') : img('i_icon', 'icon_image2', ''),
				
				'MOVE_UP'	=> ( $order != '10' )	? href('a_img', $file, array('mode' => 'order', 'type' => RANK_TEAM, 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_t )	? href('a_img', $file, array('mode' => 'order', 'type' => RANK_TEAM, 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_PAGE'		=> $lang['rank_1'],
		'L_FORUM'		=> $lang['rank_2'],
		'L_TEAM'		=> $lang['rank_3'],
		
		'L_SPECIAL'		=> $lang['rank_special'],
		'L_STANDARD'	=> $lang['rank_standard'],
		'L_MIN'			=> $lang['rank_min'],
		
		'L_CREATE_PAGE'		=> sprintf($lang['sprintf_create'], $lang['type_1']),
		'L_CREATE_FORUM'	=> sprintf($lang['sprintf_create'], $lang['type_2']),
		'L_CREATE_TEAM'		=> sprintf($lang['sprintf_create'], $lang['type_3']),
		
		'S_ACTION'		=> check_sid($file),
		'S_FIELDS'		=> $fields,
	));
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>