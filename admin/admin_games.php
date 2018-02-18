<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_GAMES',
		'CAT'		=> 'CLAN',
		'MODES'		=> array(
			'MAIN'	=> array(
				'TITLE'	=> 'ACP_GAMES',
				'AUTH'	=> 'A_GAME'
			),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$current = $log = 'ACP_GAMES';
	
	include('./pagestart.php');
	
	add_lang('games');
	add_tpls('acp_games');
	acl_auth('A_GAME');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$file	= basename(__FILE__) . $iadds;
	$path	= $root_path . $settings['path']['games'];
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$cancel = ( isset($_POST['cancel']) ) ? redirect('admin/' . check_sid($file)) : false;
#	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	
	$mode	= ( in_array($mode, array('create', 'delete', 'move_down', 'move_up', 'list', 'update')) ) ? $mode : false;
	
	$_top	= sprintf($lang['STF_HEADER'], $lang['TITLE']);
	$_tpl	= ($mode === 'delete') ? 'confirm' : 'body';

	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'game' => array(
					'title'			=> 'INPUT_DATA',
					'game_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name',	'check' => true),
					'game_tag'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:gameq',	'required' => 'select_tag',	'check' => true, 'params' => '0'),
					'game_image'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:image',	'params' => array($path, array('.png', '.jpg', '.jpeg', '.gif'), true)),
					'game_order'	=> 'hidden',
				),
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'game_name'		=> request('game_name', TXT),
					'game_image'	=> '',
					'game_image'	=> '',
					'game_order'	=> 0,
				);
			}
			else if ( $mode === 'update' && !$submit )
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
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['game_name']),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
			
		case 'list':
		
			$template->assign_block_vars($mode, array());
			
			$vars = array(
				'game_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name',	'check' => true),
				'game_tag'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:gameq',	'required' => 'select_tag',	'check' => true, 'params' => '0'),
				'game_image'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:image',	'params' => array($path, array('.png', '.jpg', '.jpeg', '.gif'), true)),
			
			);
			
			if ( $mode == 'list' && !$submit )
			{
				$data_sql = data(GAMES, false, 'game_order', 1, 'set');
			}
			else
			{
				$data_sql = build_request_list(GAMES, $vars, $error, 'game_id');
				
				if ( !$error )
				{
					if ( $data_sql )
					{
						foreach ( $data_sql as $key => $row )
						{
							foreach ( $row as $name => $info )
							{
								$ary[$key][] = "$name = '$info'";
							}
							$implode = implode(', ', $ary[$key]);
							
							debug($implode, '$implode');
						
						#	$sql = "UPDATE " . MAPS . " SET $implode WHERE map_id = $key";
						#	if ( !$db->sql_query($sql) )
						#	{
						#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						#	}
						}
						
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					}
					else
					{
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					}
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output_list(GAMES, $vars, $data_sql, $mode);
						
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
			#	'L_INPUT'	=> sprintf($lang['STF_' . strtoupper($mode)], $lang['TITLE']),
									
				'S_ACTION'	=> check_sid("$file"),
				'S_FIELDS'	=> $fields,
			));
		
			break;
			
		case 'delete':
		
			$del = array(
				'field' => 'game_id',
				'table'	=> GAMES,
				'name'	=> 'game_name'
			);
			
			$sqlout = data($del['table'], $data, false, 1, 'row');

			if ( $data && $accept && $sqlout )
			{
				$sql = sql($del['table'], $mode, $sqlout, $del['field'], $data);
				$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);

				orders($del['table']);

				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $sqlout[$del['name']]),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}

			break;
		
		case 'move_up':
		case 'move_down':
		
			move(GAMES, $mode, $order);
			log_add(LOG_ADMIN, $log, $mode);
			
		default:
		
			$template->assign_block_vars('display', array());

			$fields = build_fields(array('mode' => 'create'));
			$sqlout = data(GAMES, false, 'game_order ASC', 1, 'set');
			
			if ( !$sqlout )
			{
				$template->assign_block_vars('display.none', array());
			}
			else
			{
				$max = count($sqlout);
		
				foreach ( $sqlout as $row )
				{
					$id		= $row['game_id'];
					$name	= $row['game_name'];
					$order	= $row['game_order'];
		
					$template->assign_block_vars('display.row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						
						'TAG'		=> $row['game_tag'],
						'GAME'		=> $row['game_image'] ? display_gameicon($row['game_image']) : img('i_icon', 'icon_spacer', ''),
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
						'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
					));
				}
			}
		
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['TITLE']),
				
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				'L_NAME'	=> $lang['GAME_NAME'],
				
				'H_ALL'		=> href('a_txt', $file, array('mode' => 'list'), $lang['COMMON_ALL_UPDATE'], $lang['COMMON_ALL_UPDATE']),
		
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
		break;			
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>