<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_ICONS',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'ICONS'		=> array('TITLE' => 'ACP_ICONS'		, 'AUTH' => 'A_ICONS'),
			'SMILEY'	=> array('TITLE' => 'ACP_SMILEYS'	, 'AUTH' => 'A_ICONS'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$current = $log = 'ACP_ICONS';
	
	include('./pagestart.php');
	
	add_lang('icons');
	add_tpls('ACP_ICONS');
	acl_auth(array('A_ICONS', 'A_SMILIES'));
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$time	= time();
	$file	= basename(__FILE__) . $iadds;
	
	
	$path	= $root_path . $settings['path']['icons'];
	
	debug($path);
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$cancel = ( isset($_POST['cancel']) ) ? redirect('admin/' . check_sid($file)) : false;
	
	$mode	= ( in_array($mode, array('create', 'delete', 'list', 'move_down', 'move_up', 'update')) ) ? $mode : false;
	
	$_tpl	= ($mode === 'delete') ? 'confirm' : 'body';
	$_top	= sprintf($lang['STF_HEADER'], $lang['TITLE']);
	
	if ( isset($_POST['append']) || isset($_POST['modify']) )
	{
		$mode = ( isset($_POST['append']) ) ? 'append' : 'modify';
	}
	
#	function check_db($vars)
#	{
#		foreach ( $vars as $row )
#		{
#			$return[$row['icon_id']] = $row['icon_path'];
#		}
#		
#		return $return;
#	}

#	$mode = (in_array($mode, array('append', 'modify', 'create', 'list', 'update', 'order', 'delete'))) ? $mode : false;
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'icon_show'		=> array('validate' => TXT,	'explain' => false,	'type' => 'info'),
				'icon_path'		=> array('validate' => TXT,	'explain' => false,	'type' => 'info'),
				'icon_width'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5:5'),
				'icon_height'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5:5'),
				'icon_posting'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:posting'),
				'icon_download'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:download'),
				'icon_order'	=> 'hidden',
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			if ( $mode == 'create' && !$submit && $userauth['a_icons'] )
			{
				$data_sql = array(
					'icon_show'		=> '',
					'icon_path'		=> '',
					'icon_width'	=> '',
					'icon_height'	=> '',
					'icon_posting'	=> '',
					'icon_download'	=> '',
					'icon_order'	=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(ICONS, $data, false, 1, 'row');
			}
			else
			{
				$data_sql = build_request(ICONS, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'create' && $userauth['a_icons'] )
					{
						$data_sql['icon_order'] = _max(ICONS, 'icon_order', false);
						
						$sql = sql(ICONS, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else if ( $userauth['a_icons'] )
					{
						$sql = sql(ICONS, $mode, $data_sql, 'icon_id', $data);
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
			
			build_output(ICONS, $vars, $data_sql);
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));

			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
			
		case 'append':
		case 'modify':
		
			$template->assign_block_vars($mode, array());
			
			$vars = array(
				'icon_icon'		=> array('validate' => '',	'explain' => false,	'type' => 'icon:25', 'params' => 'icon_path'),
				'icon_path'		=> array('validate' => TXT,	'explain' => false,	'type' => 'info:25'),
				'icon_width'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5;5'),
				'icon_height'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5;5'),
				'icon_topic'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:posting'),
				'icon_download'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:download'),
		#		'icon_order'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox'),
			);
			
			if ( $mode == 'append' && !$submit )
			{
				$files = array_diff(scandir($path), array('.', '..', 'index.htm'));
				
				sort($files);
				
				$_path = str_replace($root_path, '', $path);
				
				foreach ( $files as $key => $row )
				{
					list($width, $height, $type, $attr) = getimagesize($path . $row);
					
					$data_sql[$key]['icon_path'] = $_path . $row;
					$data_sql[$key]['icon_width'] = $width;
					$data_sql[$key]['icon_height'] = $height;
				}
			}
			else if ( $mode == 'modify' && !$submit )
			{
#					$data_sql = data(ICONS, false, 'icon_order ASC', 1, false);
				$data_sql = data(ICONS, false, false, 1, 'set');
			#	$check_db = check_db($data_sql);
				
			#	debug($check_db, 'check_db');
			}
			
			else
			{
			#	$data_sql = build_request_list($vars, $error);
				$data_sql = build_request_list(ICONS, $vars, $error, 'icon_id');
				
				debug($data_sql, 'data_sql');
				
				if ( !$error )
				{
				#	$data['server_order'] = $data['server_order'] ? $data['server_order'] : maxa(ICONS, 'server_order', false);
					
					if ( $mode == 'append' )
					{
				#		$sql = sql(ICONS, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else
					{
						$sql = sql(ICONS, $mode, $data_sql, 'icon_id', $data);
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
			
			/*
			foreach ( $files as $row )
			{
				$template->assign_block_vars('append.row', array(
					'IMAGE'		=> '<img src="' . $path . $row . '" alt="">',
					'PATH'		=> $settings['path_icons']['path'] . $row,
					'WIDTH'		=> '<input type="text" value="' . $settings['path_icons']['width'] . '" size="3">',
					'HEIGHT'	=> '<input type="text" value="' . $settings['path_icons']['height'] . '" size="3">',
					'TOPIC'		=> '<input type="checkbox" value="' . $settings['path_icons']['width'] . '" size="3">',
					'DOWNLOAD'	=> '<input type="checkbox" value="' . $settings['path_icons']['width'] . '" size="3">',
					'ORDER'		=> '',
				));
			}
			*/
		#	debug($data);
		
		#	debug($mode, 'mode2');
			
		#	build_output_list($data, $vars, 'append', 'path_icons');
		#	build_output_list(ICONS, $vars, $data_sql, 'append', $mode);
			build_output_list(ICONS, $vars, $data_sql, $mode);
			
		#	build_output($data, $vars, 'append', false, ICONS);
			
		#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
		#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
			$fields .= build_fields(array(
					'mode'	=> $mode,
				#	'i'		=> $ityp,
				#	'id'	=> $check_db,
				));
			
			$template->assign_vars(array(
		#		'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
		#		'L_INPUT'	=> sprintf($lang['STF_' . strtoupper($mode)], $lang['TITLE']),
									
				'S_ACTION'	=> check_sid("$file"),
				'S_FIELDS'	=> $fields,
			));
		
			break;
		
		case 'list':
		
			$template->assign_block_vars($mode, array());
			
			switch ($action)
			{
				case 'icons':
					$table = ICONS;
					$lang = 'ICONS';
					$fields = 'icons';
					$img_path = $config['icons_path'];
				break;
				
				case 'smilies':
					$table = SMILIES;
					$lang = 'SMILIES';
					$fields = 'smiley';
					$img_path = $config['smilies_path'];
				break;
	
				
			}
			
			$data_sql = data(ICONS, false, false, 1, 'set');
			
		#	debug($data_sql);
			
			$vars = array(
				'icon_path'		=> array('validate' => TXT,	'explain' => false,	'type' => 'info:25'),
				'icon_width'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5;5'),
				'icon_height'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5;5'),
				'icon_posting'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:posting'),
				'icon_download'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:download'),
				'icon_order'	=> 'hidden',
			);
			
			build_output_list(ICONS, $vars, $data_sql, $mode, 'title');
			
			$template->assign_vars(array(
			#	'L_HEAD'	=> sprintf($lang['sprintf_' . substr($mode, 6)], $lang['gameq_title'], $data_sql['gameq_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
			
				'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'delete':
			
			$_del = array(
				'field' => 'icon_id',
				'table'	=> ICONS,
				'name'	=> 'game_name'
			);
		
			$sqlout = data($_del['table'], $data, false, 1, 'row');

			if ( $data && $accept )
			{
				$sql = sql($_del['table'], $mode, $sqlout, $_del['field'], $data);
				$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);

				orders($_del['table']);

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
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $sqlout[$_del['name']]),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}
		
			$data_sql = data(ICONS, $data, false, 1, 'row');
		
			if ( $data && $confirm )
			{
				$sql = sql(ICONS, $mode, $data_sql, 'server_id', $data);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
				
				orders(ICONS);
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data['server_name']),

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
		
		case 'move_up':
		case 'move_down':
			
			move(ICONS, $mode, $order);
			log_add(LOG_ADMIN, $log, $mode);
			
		default:
		
			$template->assign_block_vars('display', array());
			
			debug($action);
			
			$option[] = href('a_txt', $file, array('mode' => 'list'), sprintf($lang['STF_LIST'], $lang[strtoupper($action)], $lang[strtoupper($action)]), $lang[strtoupper($action)]);
		
			switch ( $action )
			{
				case 'icons':
				
					$fields = build_fields(array('mode' => 'create'));
					$sqlout = data(ICONS, false, 'icon_order ASC', 1, 'set');
					
					if ( !$sqlout )
					{
						$template->assign_block_vars('display.none', array());
					}
					else
					{
						$cnt = count($sqlout);
						
						foreach ( $sqlout as $row )
						{
							$id		= $row['icon_id'];
							$path	= $row['icon_path'];
							$order	= $row['icon_order'];
							
							$template->assign_block_vars('display.row', array(
								'SHOW'		=> href('a_img', $file, array('mode' => 'update', 'id' => $id), $root_path . $path, false),
								
								'MOVE_UP'	=> ( $order != '1' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $order != $cnt ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							));
						}
					}
				
					break;
					
				case 'smiley':
				
					$template->assign_block_vars('display.smilies', array());
				
					$fields = build_fields(array('mode' => 'create'));
					$sqlout = data(SMILIES, false, 'smile_order ASC', 1, 'set');
					
					if ( !$sqlout )
					{
						$template->assign_block_vars('display.none', array());
					}
					else
					{
						$cnt = count($sqlout);
						
						foreach ( $sqlout as $row )
						{
							$id		= $row['smile_id'];
							$path	= $row['smile_url'];
							$order	= $row['smile_order'];
						
							$template->assign_block_vars('display.row', array(
								'SHOW'		=> href('a_img', $file, array('mode' => 'update', 'id' => $id), $root_path . $settings['path']['smilies'] . $path, false),
								
								'CODE'		=> $row['code'],
								'EMOTION'	=> $row['emotion'],
								
								'MOVE_UP'	=> ( $order != '1' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $order != $cnt ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							));
							
							$template->assign_block_vars('display.row.smilies', array(
								'CODE'		=> $row['code'],
								'EMOTION'	=> $row['emotion'],
							));
						}
					}
				
					break;
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang[strtoupper($action)]),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang[strtoupper($action)]),
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				'L_NAME'	=> $lang[strtoupper($action)],
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				
				'L_CODE'	=> $lang['CODE'],
				'L_EMOTION'	=> $lang['EMOTION'],
				
				'LIST'		=> href('a_txt', $file, array('mode' => 'list'), 'list', 'list'),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>