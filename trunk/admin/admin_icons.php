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
	
	$current	= 'acp_icons';
	
	include('./pagestart.php');
	
	add_lang('icons');
	acl_auth(array('a_smileys', 'a_forum_icons', 'a_download_icons'));
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_ICONS;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_icons'];
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_icons.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( isset($_POST['append']) || isset($_POST['modify']) )
	{
		$mode = ( isset($_POST['append']) ) ? 'append' : 'modify';
	}

#	debug($_POST, '_POST');	
	
	$mode = (in_array($mode, array('append', 'modify', 'create', 'list', 'update', 'order', 'delete'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'append':
			case 'modify':
			
				$template->assign_block_vars('append', array());
				
				$vars = array(
					'icon_path'		=> array('validate' => TXT,	'explain' => false,	'type' => 'info'),
					'icon_width'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5:5'),
					'icon_height'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:5:5'),
					'icon_posting'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:posting'),
					'icon_download'	=> array('validate' => INT,	'explain' => false,	'type' => 'checkbox:download'),
				#	'icon_order'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
				);
				
				if ( $mode == 'append' && !$submit )
				{
					$files = array_diff(scandir($dir_path), array('.', '..', 'index.htm'));
					
					sort($files);
					
					foreach ( $files as $key => $row )
					{
						list($width, $height, $type, $attr) = getimagesize($dir_path . $row);
						
						$data[$key]['icon_path'] = str_replace($root_path, '', $dir_path) . $row;
						$data[$key]['icon_width'] = $width;
						$data[$key]['icon_height'] = $height;
					}
				}
				
				else if ( $mode == 'modify' && !$submit )
				{
					$data_sql = data(ICONS, false, 'icon_order ASC', 1, false);
				}
				
				else
				{
				#	$data_sql = build_request_list($vars, $error);
					
				#	debug($data, 'request_list');
					
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
					#		$sql = sql(ICONS, $mode, $data_sql, 'server_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
					#	orders(ICONS);
						
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
				
			#	build_output_list($data, $vars, 'append', 'path_icons');
				
			#	build_output($data, $vars, 'append', false, ICONS);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['stf_' . $mode], $lang['title']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			
				$template->pparse('body');
			
				break;
			
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'server' => array(
						'title'	=> 'data_input',
						'server_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'server_game'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:server', 'required' => 'input_game'),
						'server_ip'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_ip'),
						'server_port'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:10:10', 'required' => 'input_port'),
						'server_pw'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:10:10'),
						'server_live'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						'server_list'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						'server_show'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						'server_own'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
						'server_order'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:order'),
						'server_type'	=> 'hidden',
					)
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
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['server_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			
				$template->pparse('body');
				
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
				
				build_output_list(ICONS, $vars, $data_sql, $mode);
				
				$template->assign_vars(array(
				#	'L_HEAD'	=> sprintf($lang['sprintf_' . substr($mode, 6)], $lang['gameq_title'], $data_sql['gameq_name']),
					'L_EXPLAIN'	=> $lang['com_required'],
				
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
			
				break;
			
			
				
			case 'order':
				
				update(ICONS, 'server', $move, $data_id);
				orders(ICONS);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
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
		}
	
		if ( $index != true )
		{
			acp_footer();
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
	
#	$fields = '<input type="hidden" name="mode" value="create" />';
	
	$tmp = data(ICONS, false, 'icon_order ASC', 1, false);
	
	if ( !$tmp )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$cnt = count($tmp);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$id		= $tmp[$i]['icon_id'];
			$path	= $tmp[$i]['icon_path'];
			$order	= $tmp[$i]['icon_order'];
			$width	= $tmp[$i]['icon_width'];
			$height	= $tmp[$i]['icon_height'];
			
			$template->assign_block_vars('display.row', array(
				'SHOW'		=> href('a_img', $file, array('mode' => 'update', 'id' => $id), $path, ''),
				
				
				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
			));
			
		#	$servers['server_' . $i] = array($serv[$i]['server_game'], $serv[$i]['server_ip'], $serv[$i]['server_port']);
			
		}
		
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['stf_create'], $lang['title']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'LIST'		=> href('a_txt', $file, array('mode' => 'list'), 'list', 'list'),
		
#		'S_CREATE'	=> check_sid("$file?mode=create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
		
	$template->pparse('body');

	acp_footer();
}

?>