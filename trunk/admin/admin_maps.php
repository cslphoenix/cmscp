<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_maps',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_maps', 'auth' => 'auth_maps'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$update = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_maps';
	
	include('./pagestart.php');
	
	add_lang('maps');
	
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
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_maps'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_maps.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	/*
	if ( request('add_map', 1) || request('add_cat', 1) )
	{
		$mode = ( request('add_map', 1) ) ? 'create' : 'create_cat';
	
		if ( $mode == 'create' )
		{
			list($cat_id)	= each($_POST['add_map']);
			$cat_id			= intval($cat_id);
			
			$map_name		= trim(htmlentities(str_replace("\'", "'", $_POST['map_name'][$cat_id]), ENT_COMPAT));
		}
	}
	*/
	debug($_POST);
	
	$mode = ( in_array($mode, array('create', 'update', 'list', 'move_down', 'move_up', 'delete')) ) ? $mode : false;
	
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
						'map_name'	=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25', 'required' => 'input_name', 'check' => true),
						'type'		=> array('validate' => INT,	'explain' => false, 'type' => 'radio:maps', 'params' => array('combi', false)),
						'main'		=> array('validate' => INT,	'explain' => false, 'type' => 'radio:sub', 'trid' => 'main', 'params' => array('ajax', true, 'map_tag')),
						'map_tag'	=> array('validate' => TXT,	'explain' => false, 'type' => 'drop:game', 'trid' => 'map_tag', 'required' => array('select_tag', 'type', 0), 'params' => false, 'check' => true),
						'map_file'	=> array('validate' => TXT,	'explain' => false, 'type' => 'drop:file_img', 'trid' => 'map_file', 'params' => $dir_path),
						'map_info'	=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25', 'trid' => 'map_info'),
						'map_order'	=> 'hidden',
					),
				);
				
				$tmp_cat = data(MAPS, ' main = 0', 'main ASC', 1, false);
				
				foreach ( $tmp_cat as $row )
				{
					$template->assign_block_vars('input.update_image', array(
							'NAME'  => $row['map_tag'],
							'PATH'  => $dir_path . $row['map_tag'],
					));
				}
				
				if ( $mode == 'create' && !$update )
				{
					$data_sql = array(
						'map_name'	=> request('map_name', TXT),
						'type'		=> $type,
						'main'		=> $main,
						'map_tag'	=> '',
						'map_info'	=> '',
						'map_file'	=> '',
						'map_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$update )
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
							
							$tmp = ( $data_sql['map_type'] == 0 ) ? create_folder($dir_path, $data_sql['map_tag'], false) : '';
							
							$sql = sql(MAPS, $mode, $data_sql);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							if ( !$data_sql['type'] && $_POST['current_tag'] != $data_sql['map_tag'] )
							{
								rename($dir_path . $_POST['current_tag'], $dir_path . $data_sql['map_tag']);
							}
														
							$sql = sql(MAPS, $mode, $data_sql, 'map_id', $data);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&amp;id=$data"));
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
				
				build_output(MAPS, $vars, $data_sql);
				
			#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				$fields .= ( !$data_sql['type'] ) ? '<input type="hidden" name="current_tag" value="' . $data_sql['map_tag'] . '" />' : '';
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['titles']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data_sql['map_name']),

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
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data_sql['map_name']),
						
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
						'map_name'	=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25', 'required' => 'input_name',	'check' => true),
						'map_tag'	=> array('validate' => INT,	'explain' => false, 'type' => 'text:25;25', 'required' => 'input_tag',	'check' => true),
						'map_order'	=> array('validate' => INT,	'explain' => false, 'type' => 'drop:order'),
						'map_sub'	=> 'hidden',
					),
				);

				if ( $mode == 'create_cat' && !$update )
				{
					$data_sql = array(
						'map_name'	=> request('map_name', TXT),
						'map_tag'	=> '',
						'map_sub'	=> 0,
						'map_order'	=> 0,
					);
				}
				else if ( $mode == 'update_cat' && !$update )
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
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['titles']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['cat'], $data_sql['map_name']),
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
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	if ( $main )
	{
		$template->assign_block_vars('list', array());
				
		$tmp = data(MAPS, false, 'main ASC, map_order ASC', 1, false);
		
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
		else
		{
			$cat = $map = array();
		}
		
		$cid	= $cat['map_id'];
		$cname	= isset($lang[$cat['map_name']]) ? $lang[$cat['map_name']] : $cat['map_name'];
		
		$template->assign_vars(array(
			'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $cid), $cname, $cname),
			'TAG'		=> $cat['map_tag'],
			
			'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'common_update'),
			'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'common_delete'),
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
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
			$max = array_pop(end($map));
			
			foreach ( $map as $rows )
			{
				$id		= $rows['map_id'];
				$name	= isset($lang[$rows['map_name']]) ? $lang[$rows['map_name']] : $rows['map_name'];
				$info	= $rows['map_info'];
				$pic	= $rows['map_file'];
				$order	= $rows['map_order'];
				
				$template->assign_block_vars('list.row', array(
					'NAME'          => href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
					'FILE'          => $pic,
					'INFO'          => $info,
					
					'MOVE_UP'       => ( $order != '10' ) ? href('a_img', $file, array('mode' => 'list', 'action' => 'order', 'main' => $cid, 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'     => ( $order != $max ) ? href('a_img', $file, array('mode' => 'list', 'action' => 'order', 'main' => $cid, 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
	
					'UPDATE'        => href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'        => href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
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
			$max = array_pop(end($tmp));
			
			foreach ( $tmp as $row )
			{
				$id		= $row['map_id'];
				$tag	= $row['map_tag'];
				$name	= isset($lang[$row['map_name']]) ? $lang[$row['map_name']] : $row['map_name'];
				$order	= $row['map_order'];
				
				$template->assign_block_vars('display.cat', array(
					'NAME'		=> href('a_txt', $file, array('main' => $id), $name, $name),
					'TAG'		=> $tag,
					
					'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => 'order', 'main' => 0, 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => 'order', 'main' => 0, 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
	
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
				));
			}
		}
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['titles']),
		'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['title']),
		'L_NAME'	=> $lang['map_name'],
		'L_EXPLAIN'	=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file?mode=create_cat"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>