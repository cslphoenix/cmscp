<?php

if ( isset($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_games'] )
	{
		$module['hm_games']['sm_games'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_games';
	
	include('./pagestart.php');
	
	add_lang('games');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GAMES;
	$url	= POST_GAMES;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$dir_path	= $root_path . $settings['path_games'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_games'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_games.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'order', 'delete')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$template->assign_vars(array('PATH' => $dir_path));
				$template->assign_var_from_handle('UIMG', 'uimg');
				
				$vars = array(
					'game' => array(
						'title' => 'input',
						'game_name'		=> array('validate' => 'text',	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name',	'check' => true),
						'game_tag'		=> array('validate' => 'text',	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_tag',	'check' => true),
						'game_image'	=> array('validate' => 'text',	'type' => 'drop:image',		'explain' => true, 'params' => $dir_path),
						'game_order'	=> array('validate' => 'int',	'type' => 'drop:order',		'explain' => true),
					),
				);
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					$data = array(
						'game_name'		=> request('game_name', TXT),
						'game_tag'		=> '',
						'game_image'	=> '',
						'game_order'	=> '',
					);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(GAMES, $data_id, false, 1, true);
				}
				else
				{
					$temp = data(GAMES, $data_id, false, 1, true);
					$temp = array_keys($temp);
					unset($temp[0]);
					
					$data = build_request($temp, $vars, 'game', $error);
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$sql = sql(GAMES, $mode, $data);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(GAMES, $mode, $data, 'game_id', $data_id);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(GAMES);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
						
						log_add(LOG_ADMIN, $log, 'error', $error);
					}
				}
				
				build_output($data, $vars, 'input', false, GAMES);

				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['game_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');

				break;

			case 'order':

				update(GAMES, 'game', $move, $data_id);
				orders(GAMES);

				log_add(LOG_ADMIN, $log, $mode);

				$index = true;

				break;

			case 'delete':

				$data = data(GAMES, $data_id, false, 1, true);

				if ( $data_id && $confirm )
				{
					$sql = sql(GAMES, $mode, $data, 'game_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					orders(GAMES);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

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
	
	$max = maxi(GAMES, 'game_order', '');
	$tmp = data(GAMES, '', 'game_order ASC', 1, false);

	if ( !$tmp )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$cnt = count($tmp);

		for ( $i = 0; $i < $cnt; $i++ )
		{
			$id		= $tmp[$i]['game_id'];
			$name	= $tmp[$i]['game_name'];
			$order	= $tmp[$i]['game_order'];

			$template->assign_block_vars('display.row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, $name),
				'TAG'		=> $tmp[$i]['game_tag'],
				'GAME'		=> $tmp[$i]['game_image'] ? display_gameicon($tmp[$i]['game_image']) : img('i_icon', 'icon_spacer', ''),
				
				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => 'order', 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => 'order', 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}

	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],

		'L_NAME'	=> $lang['game_name'],

		'S_CREATE'	=> check_sid("$file?mode=create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>