<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_games',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_games'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_games';
	
	include('./pagestart.php');
	
	add_lang('games');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$log	= SECTION_GAMES;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_games'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['a_games'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_games.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	debug($_POST, '_POST');
	
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
				#	'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_HEAD'	=> sprintf($lang['sprintf_' . $mode], $lang['title'], $data_sql['game_name']),
					'L_EXPLAIN'	=> $lang['common_required'],
					
					
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
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['game_name']),

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
			include('./page_footer_admin.php');
			exit;
		}
	}

	$template->assign_block_vars('display', array());

	$fields	= '<input type="hidden" name="mode" value="create" />';
	
	$tmp = data(GAMES, '', 'game_order ASC', 1, false);

	if ( !$tmp )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$max = count($tmp);

		foreach ( $tmp as $row )
		{
			$id		= $row['game_id'];
			$name	= $row['game_name'];
			$order	= $row['game_order'];

			$template->assign_block_vars('display.row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
				
				'TAG'		=> $row['game_tag'],
				'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
				
				'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			));
		}
	}

	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],

		'L_NAME'	=> $lang['game_name'],

		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>