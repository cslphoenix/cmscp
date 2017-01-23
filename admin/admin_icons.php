<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_icons',
		'modes'		=> array(
			'icon'		=> array('title' => 'acp_icons'),
			'smileys'	=> array('title' => 'acp_smileys'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_icons';
	
	include('./pagestart.php');
	
	add_lang('icons');
	acl_auth(array('a_smileys', 'a_forum_icons', 'a_download_icons'));
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$time	= time();
	$log	= SECTION_ICONS;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_icons'];
	$acp_title	= sprintf($lang['stf_header'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_icons.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down'))) ? $mode : 'default';
    $_tpl = ($mode == 'delete') ? 'confirm' : 'body';
	
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
	
	if ( $mode )
	{
		switch ( $mode )
		{
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
					$files = array_diff(scandir($dir_path), array('.', '..', 'index.htm'));
					
					sort($files);
					
					$_path = str_replace($root_path, '', $dir_path);
					
					foreach ( $files as $key => $row )
					{
						list($width, $height, $type, $attr) = getimagesize($dir_path . $row);
						
						$data_sql[$key]['icon_path'] = $_path . $row;
						$data_sql[$key]['icon_width'] = $width;
						$data_sql[$key]['icon_height'] = $height;
					}
				}
				else if ( $mode == 'modify' && !$submit )
				{
#					$data_sql = data(ICONS, false, 'icon_order ASC', 1, false);
					$data_sql = data(ICONS, false, false, 1, false);
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
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(ICONS, $mode, $data_sql, 'icon_id', $data);
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
				
				/*
				foreach ( $files as $row )
				{
					$template->assign_block_vars('append.row', array(
						'IMAGE'		=> '<img src="' . $dir_path . $row . '" alt="">',
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
			#		'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
			#		'L_INPUT'	=> sprintf($lang['stf_' . $mode], $lang['title']),
										
					'S_ACTION'	=> check_sid("$file"),
					'S_FIELDS'	=> $fields,
				));
			
				break;
			
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
				#	'icon_order'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$data_sql = array(
						'server_name'	=> request('server_name', TXT),
						'server_type'	=> 0,
						'server_game'	=> '',
						'server_ip'		=> '',
						'server_port'	=> '',
						'server_live'	=> '',
						'server_pw'		=> '',
						'server_list'	=> 1,
						'server_show'	=> 1,
						'server_own'	=> 1,
						'server_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(ICONS, $data, false, 1, true);
				}
				else
				{
				#	$data_sql = build_request(ICONS, $vars, 'server', $error);
					
					if ( !$error )
					{
						$data['server_order'] = $data['server_order'] ? $data['server_order'] : maxa(ICONS, 'server_order', false);
						
						if ( $mode == 'create' )
						{
							$sql = sql(ICONS, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(ICONS, $mode, $data_sql, 'server_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						orders(ICONS);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
			#	build_output($data, $vars, 'input', false, ICONS);
				
                $fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));

				$template->assign_vars(array(
					'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['server_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			
				break;
			
			case 'list':
			
				$template->assign_block_vars($mode, array());
				
				$data_sql = data(ICONS, false, false, 1, false);
				
			#	debug($data_sql);
				
				$vars = array(
					'icon_path'		=> array('validate' => TXT,	'explain' => false,	'type' => 'info:25'),
					'icon_width'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5;5'),
					'icon_height'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5;5'),
					'icon_posting'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:posting'),
					'icon_download'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:download'),
			#		'gameq_sort'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox'),
				);
				
				build_output_list(ICONS, $vars, $data_sql, $mode, 'title');
				
				$template->assign_vars(array(
				#	'L_HEAD'	=> sprintf($lang['sprintf_' . substr($mode, 6)], $lang['gameq_title'], $data_sql['gameq_name']),
					'L_EXPLAIN'	=> $lang['com_required'],
				
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				break;
				
			case 'delete':
			
				$data_sql = data(ICONS, $data, false, 1, true);
			
				if ( $data && $confirm )
				{
					$sql = sql(ICONS, $mode, $data_sql, 'server_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(ICONS);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['server_name']),

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
			
			case 'move_up':
			case 'move_down':
				
			#	if ( $userauth['a_game_assort'] )
			#	{
					move(ICONS, $mode, $order);
					log_add(LOG_ADMIN, $log, $mode);
			#	}
				
			case 'default':
			
				$template->assign_block_vars('display', array());
				
				$fields .= build_fields(array('mode' => 'create'));
				
				$icons = data(ICONS, false, 'icon_order ASC', 1, false);
				
				if ( !$icons )
				{
					$template->assign_block_vars('display.empty', array());
				}
				else
				{
					$max = count($icons);
					
					foreach ( $icons as $row )
					{
						$id		= $row['icon_id'];
						$path	= $row['icon_path'];
						$order	= $row['icon_order'];
						$width	= $row['icon_width'];
						$height	= $row['icon_height'];
						
						$template->assign_block_vars('display.row', array(
							'SHOW'		=> href('a_img', $file, array('mode' => 'update', 'id' => $id), $root_path . $path, false),
							
							
							'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
						));
						
					#	$servers['server_' . $i] = array($serv[$i]['server_game'], $serv[$i]['server_ip'], $serv[$i]['server_port']);
						
					}
					
				}
				
				$template->assign_vars(array(
					'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
					'L_CREATE'	=> sprintf($lang['stf_create'], $lang['title']),
					'L_NAME'	=> $lang['icon_name'],
					'L_EXPLAIN'	=> $lang['explain'],
					
					'LIST'		=> href('a_txt', $file, array('mode' => 'list'), 'list', 'list'),
					
			#		'S_CREATE'	=> check_sid("$file?mode=create"),
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			
				break;
		}
		$template->pparse('body');
	}
	acp_footer();
}

?>