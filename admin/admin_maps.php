<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_maps',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_maps'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_maps';
	
	include('./pagestart.php');
	
	add_lang('maps');
	acl_auth(array('a_map_create', 'a_map_update', 'a_map_delete', 'a_map_assort', 'a_map_manage'));
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_MAPS;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	
	$dir_path	= $root_path . $settings['path_maps'];
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_maps.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
#	debug($_POST, '_POST');
	
	$mode = (in_array($mode, array('create', 'update', 'list', 'move_down', 'move_up', 'delete'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'map' => array(
						'title' => 'data_input',
						'map_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name', 'check' => true),
						'type'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('combi', false, 'main')),
						'main'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:sub',	'divbox' => true, 'params' => array('ajax', true, 'map_tag')),
					#	'map_tag'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:images','divbox' => true, 'params' => array($dir_path, GAMES, false), 'required' => array('select_tag', 'type', 0), 'check' => true),
						'map_tag'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:tags',	'divbox' => true, 'params' => array($dir_path, GAMES, false), 'required' => array('select_tag', 'type', 0), 'check' => true),
						'map_file'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:file',	'divbox' => true, 'params' => $dir_path),
					#	'map_file'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'divbox' => true),
						'map_info'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'divbox' => true),
						'map_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$name = (isset($_POST['map_name'])) ? request('map_name', TXT) : request('map_map', TXT);
					$type = (isset($_POST['map_name'])) ? 0 : 1;
					
					$data_sql = array(
						'map_name'	=> $name,
						'type'		=> $type,
						'main'		=> $main,
						'map_tag'	=> '',
						'map_info'	=> '',
						'map_file'	=> '',
						'map_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(MAPS, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(MAPS, $vars, $error, $mode);
					
				#	debug(substr($data_sql['map_tag'], 0, strrpos($data_sql['map_tag'], '.')));
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$data_sql['map_order'] = maxa(MAPS, 'map_order', $data['main']);
							
							(!$data_sql['type']) ? create_folder($dir_path, $data_sql['map_tag'], false) : '';
							
							$sql = sql(MAPS, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							if ( !$data_sql['type'] && $_POST['current_tag'] != $data_sql['map_tag'] )
							{
								rename($dir_path . request('current_tag', TXT), $dir_path . $data_sql['map_tag']);
							}
														
							$sql = sql(MAPS, $mode, $data_sql, 'map_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						orders(MAPS, $data['main']);

						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				$tmp_cat = data(MAPS, 'WHERE main = 0', 'main ASC', 1, false);
				
			#	debug($tmp_cat);
				
				foreach ( $tmp_cat as $row )
				{
					$template->assign_block_vars('input.update_image', array(
						'NAME'  => $row['map_tag'],
						'PATH'  => $dir_path . $row['map_tag'],
					));
				}
				
				build_output(MAPS, $vars, $data_sql);
				
			#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				$fields .= ( !$data_sql['type'] ) ? '<input type="hidden" name="current_tag" value="' . $data_sql['map_tag'] . '" />' : '';
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['map_name']),
					'L_EXPLAIN'	=> $lang['com_required'],

					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');

				break;
		/*	
			case 'order':
			
				update(MAPS, 'map', $move, $data_id);
				orders(MAPS, $data_sub);
				
				log_add(LOG_ADMIN, $log, $mode);

				$index = true;

				break;
		*/		
			case 'move_up':
			case 'move_down':
			
			#	debug($main, 'main');
			#	debug($type, 'type');
			#	debug($usub, 'usub');
			#	debug($action, 'action');
			
				move(MAPS, $mode, $order, $main, $type, $usub);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;

			case 'delete':

				$data_sql = data(MAPS, $data, false, 1, true);

				if ( $data && $confirm )
				{
					$sql = sql(MAPS, $mode, $data_sql, 'map_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['map_name']),
						
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
		/*		
			case 'create_cat':
			case 'update_cat':

				$template->assign_block_vars('input_cat', array());
				
			#	$template->assign_vars(array('FILE' => 'ajax_maps_cat'));
			#	$template->assign_var_from_handle('AJAX', 'ajax');
			
				$vars = array(
					'map' => array(
						'title' => 'data_input',
						'map_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name',	'check' => true),
						'map_tag'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_tag',	'check' => true),
						'map_order'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:order'),
						'map_sub'	=> 'hidden',
					),
				);

				if ( $mode == 'create_cat' && !$submit )
				{
					$data_sql = array(
						'map_name'	=> request('map_name', TXT),
						'map_tag'	=> '',
						'map_sub'	=> 0,
						'map_order'	=> 0,
					);
				}
				else if ( $mode == 'update_cat' && !$submit )
				{
					$data_sql = data(MAPS, $data, false, 1, true);
				}
				else
				{
			#		$data_sql = build_request(MAPS, $vars, 'map', $error);

				#	$data_sql = array(
				#		'cat_name'		=> request('cat_name', 2),
				#		'cat_tag'		=> strtolower(request('cat_tag', 2)),
				#		'cat_display'	=> request('cat_display', 0),
				#		'cat_order'		=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
				#	);
				#	
				#	$cur_tag = request('current_tag', 1);
				#	
				#	$error[] = check(MAPS_CAT, array('cat_name' => $data['cat_name'], 'cat_tag' => $data['cat_tag'], 'cat_id' => $data_cat), $error);

					if ( !$error )
					{
					#	$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(MAPS_CAT, 'cat_order', '');
					#	$data_sql['map_order'] = $data_sql['map_order'] ? $data_sql['map_order'] : maxa(MAPS, 'map_order', "map_sub = {$data_sql['map_sub']}");
						
						if ( $mode == 'create_cat' )
						{
							create_folder($dir_path, $data['cat_tag'], false);
							
							$sql = sql(MAPS, $mode, $data_sql);
							$msg = $lang['create_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							if ( $data['cat_tag'] != $cur_tag )
							{
								rename($dir_path . $cur_tag, $dir_path . $data['cat_tag']);
							}
							
							$sql = sql(MAPS, $mode, $data_sql, 'cat_id', $data_cat);
							$msg = $lang['update_cat'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$cat=$data_cat"));
						}
						
						orders(MAPS, $data_sql['map_sub']);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}							
				}
				
				build_output($data, $vars, 'input_cat', false, MAPS);

				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				$fields .= "<input type=\"hidden\" name=\"current_tag\" value=\"" . $data_sql['map_tag'] . "\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['stf_' . $mode], $lang['cat'], $data_sql['map_name']),
				#	'L_NAME'	=> $lang['cat_name'],
				#	'L_TAG'		=> $lang['cat_tag'],
				#	'L_DISPLAY'	=> $lang['cat_display'],
				#	'L_ORDER'	=> $lang['common_order'],

				#	'NAME'		=> $data['cat_name'],
				#	'TAG'		=> $data['cat_tag'],
				
				#	'S_DISPLAY_NO'	=> (!$data['cat_display'] ) ? 'checked="checked"' : '',
				#	'S_DISPLAY_YES'	=> ( $data['cat_display'] ) ? 'checked="checked"' : '',
					
				#	'S_ORDER'	=> simple_order(MAPS_CAT, '', 'select', $data['cat_order']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');

				break;
		*/		
			
		}
	
		if ( $index != true )
		{
			acp_footer();
			exit;
		}
	}
	
	if ( $main )
	{
		$template->assign_block_vars('list', array());
				
		$tmp = data(MAPS, false, 'main ASC, map_order ASC', 1, false);
		
		$cat = $map = array();
		
		if ( $tmp )
		{
			foreach ( $tmp as $row )
			{
				if ( $row['map_id'] == $main )
				{
					$cat = $row;
				}
				else if ( $row['main'] == $main )
				{
					$map[] = $row;
				}
			}
		}
		
		$cid	= $cat['map_id'];
		$ctag	= $cat['map_tag'];
		$cname	= isset($lang[$cat['map_name']]) ? $lang[$cat['map_name']] : $cat['map_name'];
		
		$template->assign_vars(array(
			'OPTION'	=> href('a_txt', $file, array($file), $lang['common_overview'], ''),
			'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $cid), $cname, $cname),
			'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'common_update'),
			'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'com_delete'),
		));
		
	#	$cid = $cat['map_id'];
		
	#	$template->assign_vars(array(
	#		'NAME'	=> $cat['map_name'],
	#		'TAG'	=> $cat['map_tag'],
	#		
	#		'S_ACTION'	=> check_sid($file),
	#		'S_FIELDS'	=> $fields,
	#	));
		
		if ( $map )
		{
			$max = count($map);
			
			foreach ( $map as $row )
			{
				$id		= $row['map_id'];
				$order	= $row['map_order'];
				$name	= isset($lang[$row['map_name']]) ? $lang[$row['map_name']] : $row['map_name'];
				
				$template->assign_block_vars('list.row', array(
					'NAME'          => href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
					'UPDATE'        => href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'        => href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
					
					'FILE'          => $row['map_file'],
					'INFO'          => $row['map_info'],
					
					'MOVE_UP'       => ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'usub' => $cid, 'type' => 1, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'     => ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'usub' => $cid, 'type' => 1, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
	
					
				));
			}
		}
		else
		{
			$template->assign_block_vars('list.empty', array());
		}
		
		$fields .= '<input type="hidden" name="mode" value="create" />';
		$fields .= '<input type="hidden" name="main" value="' . $main . '" />';
	}
	else
	{
		$template->assign_block_vars('display', array());

		if ( $tmp = data(MAPS, 'WHERE main = 0', 'main ASC, map_order ASC', 1, false) )
		{
			$max = count($tmp);
			
			foreach ( $tmp as $row )
			{
				$id		= $row['map_id'];
				$order	= $row['map_order'];
				$name	= isset($lang[$row['map_name']]) ? $lang[$row['map_name']] : $row['map_name'];
				
				$template->assign_block_vars('display.cat', array(
					'NAME'		=> href('a_txt', $file, array('main' => $id), $name, $name),
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
				
					'TAG'		=> $row['map_tag'],
					
					'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				));
			}
		}
		
		$fields .= '<input type="hidden" name="mode" value="create" />';
	}
	
#	debug($lang);
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['stf_create'], ($main ? $lang['title'] : $lang['main'])),
		'L_NAME'	=> $lang['type_0'],
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file&mode=create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');
	
	acp_footer();
}

?>