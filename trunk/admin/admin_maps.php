<?php

/*
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010, 2011
 *	@code:	Sebastian Frickel © 2009, 2010, 2011
 *
 *	Karten
 *
 */

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_maps'] )
	{
		$module['_headmenu_games']['_submenu_maps'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$s_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_maps';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');

	load_lang('maps');
	
	$data_id	= request(POST_MAPS_URL, 0);
	$data_cat	= request(POST_MAPS_CAT_URL, 0);
	$data_type	= request('type', 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_maps'] . '/';
	$root_file	= basename(__FILE__);
	
	$error		= '';
	$s_index	= '';
	$s_fields	= '';
	
	$p_url		= POST_MAPS_URL;
	$p_cat_url	= POST_MAPS_CAT_URL;
	$l_sec		= LOG_SEK_MAPS;
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_maps'] )
	{
		log_add(LOG_ADMIN, $l_sec, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $s_header ) ? redirect('admin/' . append_sid($root_file, true)) : false;
	
	if ( request('add_map', 1) || request('add_cat', 1) )
	{
		$mode = ( request('add_map', 1) ) ? '_create_map' : '_create_cat';
	
		if ( $mode == '_create_map' )
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
			case '_create_cat':
			case '_update_cat':

				$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
				$template->assign_block_vars('_input_cat', array());

				if ( $mode == '_create_cat' && !(request('submit', 2)) )
				{
					$max = get_data_max(MAPS_CAT, 'cat_order', '');
					
					$data = array(
						'cat_name'	=> request('cat_name', 2),
						'cat_tag'	=> '',
						'cat_order'	=> $max['max'] + 10,
					);
				}
				else if ( $mode == '_update_cat' && !(request('submit', 2)) )
				{
					$data = get_data(MAPS_CAT, $data_cat, 1);
				}
				else
				{
					$data = array(
						'cat_name'	=> request('cat_name', 2),
						'cat_tag'	=> request('cat_tag', 2),
						'cat_order'	=> request('cat_order', 2),
					);
				}

				$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
				$s_fields .= '<input type="hidden" name="cat_tag2" value="' . $data['cat_tag'] . '" />';
				$s_fields .= '<input type="hidden" name="cat_order" value="' . $data['cat_order'] . '" />';
				$s_fields .= '<input type="hidden" name="' . $p_cat_url . '" value="' . $data_cat . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['maps']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_cat', '', $mode) ], $lang['cat'], $data['cat_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['cat']),
					'L_TAG'		=> $lang['tag'],
					'L_ORDER'	=> $lang['common_order'],

					'NAME'		=> $data['cat_name'],
					'TAG'		=> $data['cat_tag'],
					'S_ORDER'	=> select_order('select', MAPS_CAT, 'cat', '', '', $data['cat_order']),

					'S_ACTION'	=> append_sid($root_file),
					'S_FIELDS'	=> $s_fields,
				));

				if ( request('submit', 2) )
				{
					$cat_name	= request('cat_name', 2);
					$cat_tag	= request('cat_tag', 2);
					$cat_tag2	= request('cat_tag2', 2);
					$cat_order	= ( request('cat_order_new', 0) ) ? request('cat_order_new', 0) : request('cat_order', 0);
					
					$error .= ( !$cat_name ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
					$error .= ( !$cat_tag ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_tag'] : '';

					if ( !$error )
					{
						if ( $mode == '_create_cat' )
						{
							$path = $path_dir . $cat_tag;		
							
							mkdir("$path", 0755);
							
							$file = 'index.htm';
							$code = $lang['empty_site'];
							
							$create = fopen("$path/$file", "w");
							fwrite($create, $code);
							fclose($create);
							
							$sql = "INSERT INTO " . MAPS_CAT . " (cat_name, cat_tag, cat_order) VALUES ('$cat_name', '$cat_tag', '$cat_order')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$data_id = $db->sql_nextid();
							$message = $lang['create_map_cat'] . sprintf($lang['click_return_map'], '<a href="' . append_sid($root_file) . '">', '</a>');
						}
						else
						{
							if ( $cat_tag != $cat_tag2 )
							{
								rename($path_dir . $cat_tag2, $path_dir . $cat_tag);
							}
							
							$sql = "UPDATE " . MAPS_CAT . " SET
										cat_name	= '$cat_name',
										cat_tag		= '$cat_tag',
										cat_order	= '$cat_order'
									WHERE cat_id = $data_cat";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_map_cat']
								. sprintf($lang['click_return_map'], '<a href="' . append_sid($root_file) . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid($root_file . '?mode=' . $mode . '&amp;' . $p_cat_url . '=' . $data_cat) . '">', '</a>');
						}
						
						orders(MAPS_CAT);
						
						log_add(LOG_ADMIN, $l_sec, $mode, $cat_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $l_sec, $mode, $error);

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

				log_add(LOG_ADMIN, $l_sec, $mode);

				$s_index = true;

				break;

			case '_delete_cat':
			
				$data = data(MAPS_CAT, $data_cat, false, 1, 1);
				
				if ( $data_cat && $confirm )
				{
					dir_remove($path_dir . $data['cat_tag'] . '/');
					
					$sql = "DELETE FROM " . MAPS_CAT . " WHERE cat_id = $data_cat";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					$message = $lang['delete_map_cat'] . sprintf($lang['click_return_map'], '<a href="' . append_sid($root_file) . '">', '</a>');

					log_add(LOG_ADMIN, $l_sec, $mode, $data['cat_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_cat && !$confirm )
				{
                    $template->set_filenames(array('body' => 'style/info_confirm.tpl'));

					$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
					$s_fields .= '<input type="hidden" name="' . $p_cat_url . '" value="' . $data_cat . '" />';

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_map_cat'], $data['cat_name']),

						'S_ACTION'	=> append_sid($root_file),
						'S_FIELDS'	=> $s_fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['cat'])); }
				
				$template->pparse('body');
				
				break;
				
			case '_create_map':
			case '_update_map':

				$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
				$template->assign_block_vars('_input_map', array());
				
				if ( $mode == '_create_map' && !(request('submit', 2)) )
				{
					$max	= get_data_max(MAPS, 'map_order', '');
					$data	= array(
								'cat_id'	=> ( request('cat_id', 2) ) ? request('cat_id', 2) : $cat_id,
								'map_name'	=> $map_name,
								'map_type'	=> '',
								'map_file'	=> '',
								'map_order'	=> $max['max'] + 10,
							);
					$cat	= get_data(MAPS_CAT, $cat_id, 1);
				}
				else if ( $mode == '_update_map' && !(request('submit', 2)) )
				{
					$data	= get_data(MAPS, $data_id, 1);
					$cat	= get_data(MAPS_CAT, $data['cat_id'], 1);
				}
				else
				{
					$data	= array(
								'cat_id'	=> request('cat_id', 2),
								'map_name'	=> request('map_name', 2),
								'map_type'	=> request('map_type', 2),
								'map_file'	=> request('map_file', 2),
								'map_order'	=> request('map_order', 2),
							);
					$cat	= get_data(MAPS_CAT, $data['cat_id'], 1);
				}
				
				$tag = $cat['cat_tag'];
				$data_cat = get_data_array(MAPS_CAT, '', 'cat_order', 'ASC');
				
				for ( $j = 0; $j < count($data_cat); $j++ )
				{
					$template->assign_block_vars('_input_map._input_cat', array(
						'CAT_ID'	=> $data_cat[$j]['cat_id'],
						'CAT_NAME'	=> $data_cat[$j]['cat_name'],
						
						'S_MARK'	=> ( $data['cat_id'] == $data_cat[$j]['cat_id'] ) ? ' checked="checked"' : '',
					));
				}
				
				$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
				$s_fields .= '<input type="hidden" name="cat_id" value="' . $data['cat_id'] . '" />';
				$s_fields .= '<input type="hidden" name="cat_order" value="' . $data['map_order'] . '" />';
				$s_fields .= '<input type="hidden" name="' . $p_url . '" value="' . $data_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['maps']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_map', '', $mode) ], $lang['map'], $data['map_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['maps']),
					'L_FILE'	=> sprintf($lang['sprintf_image'], $lang['maps']),
					'L_TYPE'	=> sprintf($lang['sprintf_type'], $lang['maps']),
					'L_ORDER'	=> $lang['common_order'],
					'L_CAT'		=> $lang['cat'],

					'NAME'		=> $data['map_name'],
					'TYPE'		=> $data['map_type'],
					
					'PATH'		=> $path_dir . $tag . '/',
					'IMAGE'		=> ( $data['map_file'] ) ? $path_dir . $tag . '/' . $data['map_file'] : $images['icon_acp_spacer'],
					
					'S_FILE'	=> select_box_files('map_file', 'post', $path_dir . $tag, $data['map_file']),
					'S_ORDER'	=> select_order('select', MAPS, $data['cat_id'], $data['map_order']),
					'S_ACTION'	=> append_sid($root_file),
					'S_FIELDS'	=> $s_fields,
				));

				if ( request('submit', 2) )
				{
					$cat_id		= request('cat_id', 2);
					$map_name	= request('map_name', 2);
					$map_type	= request('map_type', 2);
					$map_file	= request('map_file', 2);
					$map_order	= ( request('map_order_new', 0) ) ? request('map_order_new', 0) : request('map_order', 0);
					
					$error .= ( !$map_name ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
					$error .= ( !$map_type ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
					$error .= ( !$map_file ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_file'] : '';

					if ( !$error )
					{
						if ( $mode == '_create_map' )
						{
							$sql = "INSERT INTO " . MAPS . " (cat_id, map_name, map_type, map_file, map_order) VALUES ('$cat_id', '$map_name', '$map_type', '$map_file', '$map_order')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}

							$data_id = $db->sql_nextid();
							$message = $lang['create_map'] . sprintf($lang['click_return_map'], '<a href="' . append_sid($root_file) . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . MAPS . " SET
										cat_id		= '$cat_id',
										map_name	= '$map_name',
										map_type	= '$map_type',
										map_file	= '$map_file',
										map_order	= '$map_order'
									WHERE map_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}

							$message = $lang['update_map']
								. sprintf($lang['click_return_map'], '<a href="' . append_sid($root_file) . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid($root_file . '?mode=' . $mode . '&amp;' . $p_url . '=' . $data_id) . '">', '</a>');
						}
						
						orders(MAPS, $cat_id);

						log_add(LOG_ADMIN, $l_sec, $mode, $map_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $l_sec, $mode, $error);

						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');

				break;
				
			case '_delete_map':

				$data = data(MAPS, $data_id, false, 1, 1);

				if ( $data_id && $confirm )
				{
					$sql = "DELETE FROM " . MAPS . " WHERE map_id = $data_id";
					if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					$message = $lang['delete_map'] . sprintf($lang['click_return_map'], '<a href="' . append_sid($root_file) . '">', '</a>');

					log_add(LOG_ADMIN, $l_sec, $mode, $data['map_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));

					$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
					$s_fields .= '<input type="hidden" name="' . $p_url . '" value="' . $data_id . '" />';

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_map'], $data['map_name']),
						
						'S_ACTION'	=> append_sid($root_file),
						'S_FIELDS'	=> $s_fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['map'])); }
				
				$template->pparse('body');
				
				break;
				
			case '_order_map':
			
				update(MAPS, 'map', $move, $data_id);
				orders(MAPS, $data_type);
				
				log_add(LOG_ADMIN, $l_sec, $mode);

				$s_index = true;

				break;
				
			default:
				
				message(GENERAL_ERROR, $lang['no_mode']);
				
				break;
		}
	
		if ( $s_index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
	$template->assign_block_vars('_display', array());
	
	$max	= get_data_max(MAPS_CAT, 'cat_order', '');
	$cats	= get_data_array(MAPS_CAT, '', 'cat_order', 'ASC');
	
	if ( $cats )
	{
		$maps = get_data_array(MAPS, '', 'cat_id, map_order', 'ASC');
		
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id = $cats[$i]['cat_id'];
	
			$sql = "SELECT MAX(map_order) AS max$cat_id FROM " . MAPS . " WHERE cat_id = $cat_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_map = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			$template->assign_block_vars('_display._cat_row', array( 
				'CAT_ID'	=> $cat_id,
				'CAT_NAME'	=> $cats[$i]['cat_name'],
				'CAT_TAG'	=> $cats[$i]['cat_tag'],
				
				'MOVE_UP'	=> ( $cats[$i]['cat_order'] != '10' )			? '<a href="' . append_sid($root_file . '?mode=_order_cat&amp;move=-15&amp;' . $p_cat_url . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" border="0" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" border="0" />',
				'MOVE_DOWN'	=> ( $cats[$i]['cat_order'] != $max['max'] )	? '<a href="' . append_sid($root_file . '?mode=_order_cat&amp;move=15&amp;' . $p_cat_url . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" border="0" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" border="0" />',
				
				'U_UPDATE'	=> append_sid($root_file . '?mode=_update_cat&amp;' . $p_cat_url . '=' . $cat_id),
				'U_DELETE'	=> append_sid($root_file . '?mode=_delete_cat&amp;' . $p_cat_url . '=' . $cat_id),

				'S_NAME'	=> "map_name[$cat_id]",
				'S_SUBMIT'	=> "add_map[$cat_id]",
				
			));
			
			for ( $j = 0; $j < count($maps); $j++ )
			{
				$map_id = $maps[$j]['map_id'];
				
				if ( $cats[$i]['cat_id'] == $maps[$j]['cat_id'] )
				{
					$template->assign_block_vars('_display._cat_row._map_row', array(
						'MAP_NAME'	=> $maps[$j]['map_name'],
						'MAP_FILE'	=> $maps[$j]['map_file'],
						'MAP_TYPE'	=> $maps[$j]['map_type'],
						
						'MOVE_UP'	=> ( $maps[$j]['map_order'] != '10' )						? '<a href="' . append_sid($root_file . '?mode=_order_map&amp;type=' . $maps[$j]['cat_id'] . '&amp;' . $p_url . '=' . $map_id ) . '&amp;move=-15"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" border="0" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" border="0" />',
						'MOVE_DOWN'	=> ( $maps[$j]['map_order'] != $max_map['max' . $cat_id] )	? '<a href="' . append_sid($root_file . '?mode=_order_map&amp;type=' . $maps[$j]['cat_id'] . '&amp;' . $p_url . '=' . $map_id ) . '&amp;move=15"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" border="0" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" border="0" />',
						
						'U_UPDATE'	=> append_sid($root_file . '?mode=_update_map&amp;' . $p_url . '=' . $map_id),
						'U_DELETE'	=> append_sid($root_file . '?mode=_delete_map&amp;' . $p_url . '=' . $map_id),
					));
				}
			}
		}
	}
	else { $template->assign_block_vars('_display._no_entry', array()); }
	
	$s_fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['maps']),
		'L_CREATE_MAP'	=> sprintf($lang['sprintf_new_create'], $lang['map']),
		'L_CREATE_CAT'	=> sprintf($lang['sprintf_new_create'], $lang['cat']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['map']),
		'L_EXPLAIN'		=> $lang['explain'],
		
		'S_CREATE_MAP'	=> append_sid($root_file . '?mode=_create_map'),
		'S_CREATE_CAT'	=> append_sid($root_file . '?mode=_create_cat'),
		'S_ACTION'		=> append_sid($root_file),
		'S_FIELDS'		=> $s_fields,
	));
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');

}

?>