<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_maps'] )
	{
		$module['hm_games']['sm_maps2'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_maps';
	
	include('./pagestart.php');
	
	add_lang('maps');

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_MAPS;
	$url	= POST_MAPS;
	$cat	= POST_MAPS_CAT;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_cat	= request($cat, INT);
	$data_type	= request('type', INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$dir_path	= $root_path . $settings['path_maps'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_maps'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_maps.tpl',
		'ajax'		=> 'style/inc_request.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( request('add_map', 1) || request('add_cat', 1) )
	{
		$mode = ( request('add_map', 1) ) ? 'create' : '_create_cat';
	
		if ( $mode == 'create' )
		{
			list($cat_id)	= each($_POST['add_map']);
			$cat_id			= intval($cat_id);
			
			$map_name		= trim(htmlentities(str_replace("\'", "'", $_POST['map_name'][$cat_id]), ENT_COMPAT));
		}
	}
	
	$mode = ( in_array($mode, array('create', 'update', '_order', 'delete', '_create_cat', '_update_cat', '_order_cat', '_delete_cat')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
		case 'update':

				$template->assign_block_vars('input', array());
				
				$template->assign_vars(array('FILE' => 'ajax_maps'));
				$template->assign_var_from_handle('AJAX', 'ajax');
				
				if ( $mode == 'create' && !(request('submit', TXT)) )
				{
					$data = array(
						'map_name'	=> isset($map_name) ? $map_name : '',
						'cat_id'	=> request('cat_id', 2) ? request('cat_id', 2) : $cat_id,
						'map_type'	=> '',
						'map_file'	=> '',
						'map_order'	=> '',
					);
				}
				else if ( $mode == 'update' && !(request('submit', TXT)) )
				{
					$data = data(MAPS, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
						'map_name'	=> request('map_name', 2),
						'cat_id'	=> request('cat_id', 2),
						'map_type'	=> request('map_type', 2),
						'map_file'	=> request('map_file', 2),
						'map_order'	=> request('map_order', 0) ? request('map_order', 0) : request('map_order_new', 0),
					);
					
					$error .= check(MAPS, array('map_name' => $data['map_name'], 'map_file' => $data['map_file'], 'map_id' => $data_id), $error);
					$error .= ( !$data['map_type'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_type'] : '';
					
					if ( !$error )
					{
						$data['map_order'] = $data['map_order'] ? $data['map_order'] : maxa(MAPS, 'map_order', 'cat_id = ' . $data['cat_id']);
						
						if ( $mode == 'create' )
						{
							$sql = sql(MAPS, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(MAPS, $mode, $data, 'map_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(MAPS, $data['cat_id']);

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
				
				$cats = data(MAPS_CAT, $data['cat_id'], false, 1, true);
				$tag['cat_tag'] = $cats['cat_tag'];
			
				$cats_list = data(MAPS_CAT, false, 'cat_order ASC', 1, false);
				
				for ( $j = 0; $j < count($cats_list); $j++ )
				{
					$template->assign_block_vars('input._cat', array(
						'CAT_ID'	=> $cats_list[$j]['cat_id'],
						'CAT_NAME'	=> $cats_list[$j]['cat_name'],
						
						'S_MARK'	=> ( $data['cat_id'] == $cats_list[$j]['cat_id'] ) ? ' checked="checked"' : '',
					));
					
					$template->assign_block_vars('input._update_image', array(
						'NAME'	=> $cats_list[$j]['cat_tag'],
						'PATH'	=> $dir_path . $cats_list[$j]['cat_tag'],
					));
				}
				
				$path_files = scandir($dir_path . $tag['cat_tag']);
				
				if ( $path_files )
				{
					$maps = '<select class="post" name="map_file" id="map_file" onchange="update_' . $tag['cat_tag'] . '(this.options[selectedIndex].value);">';
					$maps .= '<option value="./../style/images/spacer.gif">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_map_file']) . '</option>';
					
					$clear_files = '';
					$endung = array('png', 'jpg', 'jpeg', 'gif');
					
					foreach ( $path_files as $files )
					{
						if ( $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' && $files != 'spacer.gif' )
						{
							if ( in_array(substr($files, -3), $endung) )
							{
								$clear_files[] = $files;
							}
						}
					}
					
					if ( $clear_files )
					{
						foreach ( $clear_files as $files )
						{
							$filter = str_replace(substr($files, strrpos($files, '.')), "", $files);
					
							$marked = ( $files == $data['map_file'] ) ? ' selected="selected"' : '';
							$maps .= '<option value="' . $files . '" ' . $marked . '>' . sprintf($lang['sprintf_select_format'], $filter) . '</option>';
						}
						$maps .= '</select>';
					}
					else
					{
						$maps = $lang['no_entry'];
					}
				}
				else
				{
					$maps = $lang['no_entry'];
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['titles']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['map_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
					'L_FILE'	=> sprintf($lang['sprintf_image'], $lang['title']),
					'L_TYPE'	=> sprintf($lang['sprintf_type'], $lang['title']),
					'L_CAT'		=> sprintf($lang['sprintf_cat'], $lang['title']),
					
					'NAME'		=> $data['map_name'],
					'TYPE'		=> $data['map_type'],
					
					'PATH'		=> $dir_path . $tag['cat_tag'] . '/',
					'IMAGE'		=> ( $data['map_file'] ) ? $dir_path . $tag['cat_tag'] . '/' . $data['map_file'] : './../admin/style/images/spacer.gif',
					
					'S_FILE'	=> $maps,
					
				#	'S_FILE'	=> select_box_files('post', 'map_file', $dir_path . $data['cat_tag'], $data['map_file']),
					'S_ORDER'	=> simple_order(MAPS, $data['cat_id'], 'select', $data['map_order']),
				#	'S_ORDER'	=> select_order('select', MAPS, $data['cat_id'], $data['map_order']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');

				break;
			
			case 'order':
			
				update(MAPS, 'map', $move, $data_id);
				orders(MAPS, $data_type);
				
				log_add(LOG_ADMIN, $log, $mode);

				$index = true;

				break;
				
			case 'delete':

				$data = data(MAPS, $data_id, false, 1, true);

				if ( $data_id && $confirm )
				{
					$sql = sql(MAPS, $mode, $data, 'map_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['map_name']),
						
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
				
			case 'create_cat':
			case 'update_cat':

				$template->assign_block_vars('input_cat', array());
				
				$template->assign_vars(array('FILE' => 'ajax_maps_cat'));
				$template->assign_var_from_handle('AJAX', 'ajax');

				if ( $mode == '_create_cat' && !(request('submit', TXT)) )
				{
					$data = array(
						'cat_name'		=> request('cat_name', 2),
						'cat_tag'		=> '',
						'cat_display'	=> '0',
						'cat_order'		=> '',
					);
				}
				else if ( $mode == '_update_cat' && !(request('submit', TXT)) )
				{
					$data = data(MAPS_CAT, $data_cat, false, 1, true);
				}
				else
				{
					$data = array(
						'cat_name'		=> request('cat_name', 2),
						'cat_tag'		=> strtolower(request('cat_tag', 2)),
						'cat_display'	=> request('cat_display', 0),
						'cat_order'		=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
					);
					
					$cur_tag = request('current_tag', 1);
					
					$error .= check(MAPS_CAT, array('cat_name' => $data['cat_name'], 'cat_tag' => $data['cat_tag'], 'cat_id' => $data_cat), $error);
					
					if ( !$error )
					{
						$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(MAPS_CAT, 'cat_order', '');
						
						if ( $mode == '_create_cat' )
						{
							create_folder($dir_path, $data['cat_tag'], false);
							
							$sql = sql(MAPS_CAT, $mode, $data);
							$msg = $lang['create_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							if ( $data['cat_tag'] != $cur_tag )
							{
								rename($dir_path . $cur_tag, $dir_path . $data['cat_tag']);
							}
							
							$sql = sql(MAPS_CAT, $mode, $data, 'cat_id', $data_cat);
							$msg = $lang['update_cat'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$cat=$data_cat"));
						}
						
						orders(MAPS_CAT);
						
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

				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data_cat\" />";
				$fields .= "<input type=\"hidden\" name=\"current_tag\" value=\"" . $data['cat_tag'] . "\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['titles']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_cat', '', $mode) ], $lang['cat'], $data['cat_name']),
					'L_NAME'	=> $lang['cat_name'],
					'L_TAG'		=> $lang['cat_tag'],
					'L_DISPLAY'	=> $lang['cat_display'],
					'L_ORDER'	=> $lang['common_order'],

					'NAME'		=> $data['cat_name'],
					'TAG'		=> $data['cat_tag'],
				
					'S_DISPLAY_NO'	=> (!$data['cat_display'] ) ? 'checked="checked"' : '',
					'S_DISPLAY_YES'	=> ( $data['cat_display'] ) ? 'checked="checked"' : '',
					
					'S_ORDER'	=> simple_order(MAPS_CAT, '', 'select', $data['cat_order']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');

				break;
				
			case 'order_cat':

				update(MAPS_CAT, 'cat', $move, $data_cat);
				orders(MAPS_CAT);

				log_add(LOG_ADMIN, $log, $mode);

				$index = true;

				break;

			case 'delete_cat':
			
				$data = data(MAPS_CAT, $data_cat, false, 1, true);
				
				if ( $data_cat && $confirm )
				{
					delete_folder($dir_path . $data['cat_tag'] . '/');
					
					$sql = sql(MAPS_CAT, $mode, $data, 'cat_id', $data_cat);
					$msg = $lang['delete_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(MAPS_CAT);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_cat && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data_cat\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm_cat'], $data['cat_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['cat']));
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
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['titles']),
		'L_CREATE_MAP'	=> sprintf($lang['sprintf_create'], $lang['title']),
		'L_CREATE_CAT'	=> sprintf($lang['sprintf_create'], $lang['cat']),
		'L_NAME'		=> $lang['map_name'],
		'L_EXPLAIN'		=> $lang['explain'],
		
		'S_CREATE_MAP'	=> check_sid("$file?mode=_create_map"),
		'S_CREATE_CAT'	=> check_sid("$file?mode=_create_cat"),
		'S_ACTION'		=> check_sid($file),
		'S_FIELDS'		=> $fields,
	));
	
	$max = maxi(MAPS_CAT, 'cat_order', '');
	$tmp = data(MAPS_CAT, false, 'cat_order ASC', 1, false);
	
	if ( $tmp )
	{
		$cnt_cats = count($tmp);
		
		for ( $i = 0; $i < $cnt_cats; $i++ )
		{
			$cat_id = $tmp[$i]['cat_id'];
			$name	= $tmp[$i]['cat_name'];
			$order	= $tmp[$i]['cat_order'];
			
			$maps = data(MAPS, "cat_id = $cat_id", 'map_order ASC', 1, false);
			
			$cnt_maps = $maps ? count($maps) : 0;
			
			$template->assign_block_vars('display.cat_row', array( 
				'NAME'		=> href('a_txt', $file, array('mode' => '_update_cat', $cat => $cat_id), $name, ''),
				
				'ID'		=> $cat_id,
				'TAG'		=> $tmp[$i]['cat_tag'],
				
				'MAPS'		=> ( $cnt_maps == '1' ) ? sprintf($lang['sprintf_count_maps'], $cnt_maps, $lang['title']) : sprintf($lang['sprintf_count_maps'], $cnt_maps, $lang['titles']),
				
				'DISPLAY'	=> $tmp[$i]['cat_display'] ? '' : 'none',
				'IMAGE'		=> $tmp[$i]['cat_display'] ? 'collapse' : 'expand',
				
				'MOVE_UP'	=> ( $order != '10' )	? href('a_img', $file, array('mode' => '_order_cat', 'move' => '-15', $cat => $cat_id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => '_order_cat', 'move' => '+15', $cat => $cat_id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
								
				'UPDATE'	=> href('a_img', $file, array('mode' => '_update_cat', $cat => $cat_id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => '_delete_cat', $cat => $cat_id), 'icon_cancel', 'common_delete'),
				
				'S_NAME'	=> "map_name[$cat_id]",
				'S_SUBMIT'	=> "add_map[$cat_id]",
			));
			
			if ( !$maps )
			{
				$template->assign_block_vars('display.catrow._empty', array());
			}
			else
			{
				
				
				$sql = "SELECT MAX(map_order) AS max$cat_id FROM " . MAPS . " WHERE cat_id = $cat_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$max_map = $db->sql_fetchrow($result);
			
				for ( $j = 0; $j < $cnt_maps; $j++ )
				{
					$id		= $maps[$j]['map_id'];
					$cat_id	= $maps[$j]['cat_id'];
					$name	= $maps[$j]['map_name'];
					$order	= $maps[$j]['map_order'];
					
					if ( $tmp[$i]['cat_id'] == $maps[$j]['cat_id'] )
					{
						$template->assign_block_vars('display.catrow._map_row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
							'FILE'		=> $maps[$j]['map_file'],
							'TYPE'		=> $maps[$j]['map_type'],
							
							'MOVE_UP'	=> ( $order != '10' )						? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'type' => $cat_id, $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $order != $max_map['max' . $cat_id] )	? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'type' => $cat_id, $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
						));
					}
				}
			}
		}
	}
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');

}

?>