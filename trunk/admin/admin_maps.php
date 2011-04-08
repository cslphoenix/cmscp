<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_maps'] )
	{
		$module['_headmenu_08_games']['_submenu_maps'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_maps';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('maps');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_MAPS;
	$url	= POST_MAPS_URL;
	$url_c	= POST_MAPS_CAT_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_cat	= request($url_c, 0);
	$data_type	= request('type', 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_maps'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['maps']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_maps'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	if ( request('add_map', 1) || request('add_cat', 1) )
	{
		$mode = ( request('add_map', 1) ) ? '_create' : '_create_cat';
	
		if ( $mode == '_create' )
		{
			list($cat_id)	= each($_POST['add_map']);
			$cat_id			= intval($cat_id);
			
			$map_name		= trim(htmlentities(str_replace("\'", "'", $_POST['map_name'][$cat_id]), ENT_COMPAT));
		}
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':

				$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !(request('submit', 1)) )
				{
					$data = array(
								'map_name'	=> $map_name,
								'cat_id'	=> ( request('cat_id', 2) ) ? request('cat_id', 2) : $cat_id,
								'map_type'	=> '',
								'map_file'	=> '',
								'map_order'	=> '',
							);
					
					$cats = data(MAPS_CAT, $data['cat_id'], false, 1, 1);
				}
				else if ( $mode == '_update' && !(request('submit', 1)) )
				{
					$data = data(MAPS, $data_id, false, 1, 1);
					$cats = data(MAPS_CAT, $data['cat_id'], false, 1, 1);
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
					$cats = data(MAPS_CAT, $data['cat_id'], false, 1, 1);
				}
				
				$tag = $cats['cat_tag'];
			
				$cats_list = data(MAPS_CAT, false, 'cat_order ASC', 1, false);
				
				for ( $j = 0; $j < count($cats_list); $j++ )
				{
					$template->assign_block_vars('_input._cat', array(
						'CAT_ID'	=> $cats_list[$j]['cat_id'],
						'CAT_NAME'	=> $cats_list[$j]['cat_name'],
						
						'S_MARK'	=> ( $data['cat_id'] == $cats_list[$j]['cat_id'] ) ? ' checked="checked"' : '',
					));
				}
			
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"cat_id\" value=\"" . $data['cat_id'] . "\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['maps']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode ], $lang['map'], $data['map_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['maps']),
					'L_FILE'	=> sprintf($lang['sprintf_image'], $lang['maps']),
					'L_TYPE'	=> sprintf($lang['sprintf_type'], $lang['maps']),
					'L_ORDER'	=> $lang['common_order'],
					'L_CAT'		=> sprintf($lang['sprintf_category'], $lang['maps']),

					'NAME'		=> $data['map_name'],
					'TYPE'		=> $data['map_type'],
					
					'PATH'		=> $path_dir . $tag . '/',
					'IMAGE'		=> ( $data['map_file'] ) ? $path_dir . $tag . '/' . $data['map_file'] : $images['icon_acp_spacer'],
					
					'S_FILE'	=> select_box_files('post', 'map_file', $path_dir . $tag, $data['map_file']),
					'S_ORDER'	=> select_order('select', MAPS, $data['cat_id'], $data['map_order']),
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));

				if ( request('submit', 1) )
				{
					$data = array(
								'map_name'	=> request('map_name', 2),
								'cat_id'	=> request('cat_id', 2),
								'map_type'	=> request('map_type', 2),
								'map_file'	=> request('map_file', 2),
								'map_order'	=> request('map_order', 0) ? request('map_order', 0) : request('map_order_new', 0),
							);
							
					$data['map_order'] = ( !$data['map_order'] ) ? maxa(MAPS, 'map_order', 'cat_id = ' . $data['cat_id']) : $data['map_order'];
							
					$error .= ( !$data['map_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['map_type'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_tag'] : '';
					$error .= ( !$data['map_file'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_file'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$db_data = sql(MAPS, $mode, $data);

							$message = $lang['create']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							$db_data = sql(MAPS, $mode, $data, 'map_id', $data_id);

							$message = $lang['update']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
						}
						
						orders(MAPS, $data['cat_id']);

						log_add(LOG_ADMIN, $log, $mode, $db_data);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);

						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');

				break;
			
			case '_order':
			
				update(MAPS, 'map', $move, $data_id);
				orders(MAPS, $data_type);
				
				log_add(LOG_ADMIN, $log, $mode);

				$index = true;

				break;
				
			case '_delete':

				$data = data(MAPS, $data_id, false, 1, 1);

				if ( $data_id && $confirm )
				{
					$db_data = sql(MAPS, $mode, $data, 'map_id', $data_id);

					$message = $lang['delete'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');

					log_add(LOG_ADMIN, $log, $mode, $db_data);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));

					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['map_name']),
						
						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['map'])); }
				
				$template->pparse('body');
				
				break;
				
			case '_create_cat':
			case '_update_cat':

				$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
				$template->assign_block_vars('_input_cat', array());

				if ( $mode == '_create_cat' && !(request('submit', 1)) )
				{
					$data = array(
						'cat_name'	=> request('cat_name', 2),
						'cat_tag'	=> '',
						'cat_order'	=> '',
					);
				}
				else if ( $mode == '_update_cat' && !(request('submit', 1)) )
				{
					$data = data(MAPS_CAT, $data_cat, false, 1, 1);
				}
				else
				{
					$data = array(
						'cat_name'	=> request('cat_name', 2),
						'cat_tag'	=> request('cat_tag', 2),
						'cat_order'	=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
					);
				}

				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$data_cat\" />";
				$fields .= "<input type=\"hidden\" name=\"cat_tag2\" value=\"" . $data['cat_tag'] . "\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['maps']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_cat', '', $mode) ], $lang['cat'], $data['cat_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['cat']),
					'L_TAG'		=> $lang['tag'],
					'L_ORDER'	=> $lang['common_order'],

					'NAME'		=> $data['cat_name'],
					'TAG'		=> $data['cat_tag'],
					'S_ORDER'	=> select_order('select', MAPS_CAT, 'cat', '', '', $data['cat_order']),

					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$data = array(
						'cat_name'	=> request('cat_name', 2),
						'cat_tag'	=> request('cat_tag', 2),
						'cat_order'	=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
					);
					
					$cat_tag2 = request('cat_tag2', 2);
					
					$data['cat_order'] = ( !$data['cat_order'] ) ? maxa(MAPS_CAT, 'cat_order', '') : $data['cat_order'];
					
					$error .= ( !$data['cat_name'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['cat_tag'] )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_tag'] : '';

					if ( !$error )
					{
						if ( $mode == '_create_cat' )
						{
							$path = $path_dir . $data['cat_tag'];		
							
							mkdir("$path", 0755);
							
							$file = 'index.htm';
							$code = $lang['empty_site'];
							
							$create = fopen("$path/$file", "w");
							fwrite($create, $code);
							fclose($create);
							
							$db_data = sql(MAPS_CAT, $mode, $data);
							
							$message = $lang['create_c']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							if ( $data['cat_tag'] != $cat_tag2 )
							{
								rename($path_dir . $cat_tag2, $path_dir . $data['cat_tag']);
							}
							
							$db_data = sql(MAPS_CAT, $mode, $data, 'cat_id', $data_cat);
							
							$message = $lang['update_c']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url_c=$data_cat") . '">', '</a>');
						}
						
						orders(MAPS_CAT);
						
						log_add(LOG_ADMIN, $log, $mode, $db_data);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);

						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');

				break;
				
			case '_order_cat':

				update(MAPS_CAT, 'cat', $move, $data_cat);
				orders(MAPS_CAT);

				log_add(LOG_ADMIN, $log, $mode);

				$index = true;

				break;

			case '_delete_cat':
			
				$data = data(MAPS_CAT, $data_cat, false, 1, 1);
				
				if ( $data_cat && $confirm )
				{
					dir_remove($path_dir . $data['cat_tag'] . '/');
					
					$db_data = sql(MAPS_CAT, $mode, $data, 'cat_id', $data_cat);
					
					$message = $lang['delete_c']
						. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', '</a>');

					log_add(LOG_ADMIN, $log, $mode, $db_data);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_cat && !$confirm )
				{
                    $template->set_filenames(array('body' => 'style/info_confirm.tpl'));
					
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$data_cat\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm_c'], $data['cat_name']),

						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['cat'])); }
				
				$template->pparse('body');
				
				break;
				
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['maps']),
		'L_CREATE_MAP'	=> sprintf($lang['sprintf_new_create'], $lang['map']),
		'L_CREATE_CAT'	=> sprintf($lang['sprintf_new_create'], $lang['cat']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['map']),
		'L_EXPLAIN'		=> $lang['explain'],
		
		'S_CREATE_MAP'	=> append_sid("$file?mode=_create_map"),
		'S_CREATE_CAT'	=> append_sid("$file?mode=_create_cat"),
		'S_ACTION'		=> append_sid($file),
		'S_FIELDS'		=> $fields,
	));
	
	$max = maxi(MAPS_CAT, 'cat_order', '');
	$tmp = data(MAPS_CAT, false, 'cat_order ASC', 1, false);
	
	if ( $tmp )
	{
		$maps = data(MAPS, false, 'cat_id, map_order ASC', 1, false);
		
		for ( $i = 0; $i < count($tmp); $i++ )
		{
			$cat_id = $tmp[$i]['cat_id'];
	
			$sql = "SELECT MAX(map_order) AS max$cat_id FROM " . MAPS . " WHERE cat_id = $cat_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_map = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			$template->assign_block_vars('_display._cat_row', array( 
				'CAT_ID'	=> $cat_id,
				'CAT_NAME'	=> $tmp[$i]['cat_name'],
				'CAT_TAG'	=> $tmp[$i]['cat_tag'],
				
				'MOVE_UP'	=> ( $tmp[$i]['cat_order'] != '10' )	? '<a href="' . append_sid("$file?mode=_order_cat&amp;move=-15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" border="0" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" border="0" />',
				'MOVE_DOWN'	=> ( $tmp[$i]['cat_order'] != $max )	? '<a href="' . append_sid("$file?mode=_order_cat&amp;move=+15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" border="0" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" border="0" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update_cat&amp;$url_c=$cat_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete_cat&amp;$url_c=$cat_id"),

				'S_NAME'	=> "map_name[$cat_id]",
				'S_SUBMIT'	=> "add_map[$cat_id]",
			));
			
			for ( $j = 0; $j < count($maps); $j++ )
			{
				$map_id = $maps[$j]['map_id'];
				$cat_id = $maps[$j]['cat_id'];
				
				if ( $tmp[$i]['cat_id'] == $maps[$j]['cat_id'] )
				{
					$template->assign_block_vars('_display._cat_row._map_row', array(
						'MAP_NAME'	=> $maps[$j]['map_name'],
						'MAP_FILE'	=> $maps[$j]['map_file'],
						'MAP_TYPE'	=> $maps[$j]['map_type'],
						
						'MOVE_UP'	=> ( $maps[$j]['map_order'] != '10' )						? '<a href="' . append_sid("$file?mode=_order&amp;type=$cat_id&amp;$url=$map_id") . '&amp;move=-15"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" border="0" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" border="0" />',
						'MOVE_DOWN'	=> ( $maps[$j]['map_order'] != $max_map['max' . $cat_id] )	? '<a href="' . append_sid("$file?mode=_order&amp;type=$cat_id&amp;$url=$map_id") . '&amp;move=+15"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" border="0" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" border="0" />',
						
						'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$map_id"),
						'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$map_id"),
					));
				}
			}
		}
	}
	else { $template->assign_block_vars('_display._no_entry', array()); }
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');

}

?>