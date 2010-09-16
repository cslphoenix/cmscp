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
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 *	Berechtigungsfelder
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_maps'] )
	{
		$module['_headmenu_games']['_submenu_maps'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_maps';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');

	load_lang('maps');
	
#	debug($_GET);
#	debug($_POST);
	
	$data_id	= request(POST_MAPS_URL, 0);
	$data_cat	= request(POST_MAPS_CAT_URL, 0);
	$data_type	= request('type', 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_maps'] . '/';
	$show_index	= '';
	$s_fields	= '';
	$error		= '';

	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_maps'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_MAPS, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $no_header ) ? redirect('admin/' . append_sid('admin_maps.php', true)) : false;

	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':

				$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
				$template->assign_block_vars('_input', array());

				if ( $mode == '_create' && !(request('submit', 2)) )
				{
					$max = get_data_max(MAPS_CAT, 'cat_order', '');
					
					$data = array(
						'cat_name'	=> request('cat_name', 2),
						'cat_tag'	=> '',
						'cat_order'	=> $max['max'] + 10,
					);
				}
				else if ( $mode == '_update' && !(request('submit', 2)) )
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
				$s_fields .= '<input type="hidden" name="cat_order" value="' . $data['cat_order'] . '" />';
				$s_fields .= '<input type="hidden" name="' . POST_MAPS_CAT_URL . '" value="' . $data_cat . '" />';

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['maps']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['maps'], $data['cat_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['maps']),

					'NAME'		=> $data['cat_name'],
					'TAG'		=> $data['cat_tag'],

				#	'S_CAT'		=> select_cat(),

					'S_ACTION'	=> append_sid('admin_maps.php'),
					'S_FIELDS'	=> $s_fields,
				));

				if ( request('submit', 2) )
				{
					$cat_name	= request('cat_name', 2);
					$cat_tag	= request('cat_tag', 2);
					$cat_order	= request('cat_order', 2);
					
					$error .= ( !$cat_name ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
					$error .= ( !$cat_tag ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_tag'] : '';

					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$sql = "INSERT INTO " . MAPS_CAT . " (cat_name, cat_tag, cat_order)
										VALUES ('$cat_name', '$cat_tag', '$cat_order')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}

							$message = $lang['create_maps_cat'] . sprintf($lang['click_return_maps'], '<a href="' . append_sid('admin_maps.php') . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . MAPS_CAT . " SET
										cat_name	= '$cat_name',
										cat_tag		= '$cat_tag',
										cat_order	= '$cat_order'
									WHERE cat_id = $data_cat";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}

							$message = $lang['update_maps_cat']
								. sprintf($lang['click_return_maps'], '<a href="' . append_sid('admin_maps.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_maps.php?mode=' . $mode . '&amp;' . POST_MAPS_CAT_URL . '=' . $data_cat) . '">', '</a>');
						}

						log_add(LOG_ADMIN, LOG_SEK_MAPS, $mode, $cat_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');

				break;

			case '_order':

				update(MAPS_CAT, 'cat', $move, $data_cat);
				orders(MAPS_CAT);

				log_add(LOG_ADMIN, LOG_SEK_MAPS, $mode);

				$show_index = true;

				break;

			case '_delete':

				$data = get_data(MAPS_CAT, $data_cat, 1);

				if ( $data_id && $confirm )
				{
					$sql = "DELETE FROM " . MAPS_CAT . " WHERE cat_id = $data_cat";
					if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					$message = $lang['delete_maps'] . sprintf($lang['click_return_maps'], '<a href="' . append_sid('admin_maps.php') . '">', '</a>');

					log_add(LOG_ADMIN, LOG_SEK_MAPS, $mode, $data['cat_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));

					$s_fields .= '<input type="hidden" name="mode" value="_delete" />';
					$s_fields .= '<input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $data_id . '" />';

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_maps'], $data['cat_name']),

						'S_ACTION'	=> append_sid('admin_maps.php'),
						'S_FIELDS'	=> $s_fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['maps']));
				}
				
				break;

			case '_list':
			
				$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
				$template->assign_block_vars('_list', array());
				
				$dirs	= scandir($path_dir);
				$max	= get_data_max(MAPS, 'map_order', '');
				$data	= get_data_array(MAPS, 'cat_id = ' . $data_cat, 'map_order', 'ASC');
				
				if ( $data )
				{
					for ( $i = 0; $i < count($data); $i++ )
					{
						$data_id	= $data[$i]['map_id'];
						$data_cat	= $data[$i]['cat_id'];
						
						$template->assign_block_vars('_list._map_row', array(
							'NAME'		=> $data[$i]['map_name'],
							'FILE'		=> $data[$i]['map_file'],
							
							'MOVE_UP'	=> ( $data[$i]['map_order'] != '10' )			? '<a href="' . append_sid('admin_maps.php?mode=_order_map&amp;type=' . $data_cat . '&amp;move=-15&amp;' . POST_MAPS_URL . '=' . $data_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
							'MOVE_DOWN'	=> ( $data[$i]['map_order'] != $max['max'] )	? '<a href="' . append_sid('admin_maps.php?mode=_order_map&amp;type=' . $data_cat . '&amp;move=15&amp;' . POST_MAPS_URL . '=' . $data_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
							
							'U_UPDATE'	=> append_sid('admin_maps.php?mode=_update_map&amp;' . POST_MAPS_URL . '=' . $data_id),
							'U_DELETE'	=> append_sid('admin_maps.php?mode=_delete_map&amp;' . POST_MAPS_URL . '=' . $data_id),
						));
					}
				}
				else { $template->assign_block_vars('_list._no_entry', array()); }

			#	$select = '<select class="selectsmall" name="' . $type . '" id="' . $type . '" onchange="update_image(this.options[selectedIndex].value);">';
			#	$select = '<select class="selectsmall" name="dir" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
				$select = '<select class="selectsmall" name="dir">';
				$select .= '<option value="">&raquo;&nbsp;' . $lang['msg_select_dir'] . '&nbsp;</option>';
				
				foreach ( $dirs as $dir )
				{
					if ( $dir != '.' && $dir != '..' && $dir != 'index.htm' && $dir != '.svn' )
					{
						$select .= '<option value="' . $path_dir . $dir . '">' . $path_dir . $dir . '&nbsp;</option>';
					}
				}
				$select .= '</select>';
				
				$s_fields = '<input type="hidden" name="mode" value="_list_create" /><input type="hidden" name="' . POST_MAPS_CAT_URL . '" value="' . $data_cat . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['maps']),
					'L_CREATE'	=> sprintf($lang['sprintf_new_create'], $lang['map_cat']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['map_cat']),
					'L_EXPLAIN'	=> $lang['maps_explain'],

					'S_DIR'		=> $select,

					'S_FIELDS'	=> $s_fields,
					'S_CREATE'	=> append_sid('admin_maps.php?mode=_create'),
					'S_ACTION'	=> append_sid('admin_maps.php'),
				));

				$template->pparse('body');

				break;
			
			case '_list_create':
			
				$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
				$template->assign_block_vars('_list_create', array());
				
				$num	= 1;
				$path	= request('dir', 1);
				$file	= scandir($path);
				$data	= get_data_array(MAPS, 'cat_id = ' . $data_cat, 'map_order', 'ASC');
				
				foreach ( $file as $key => $map )
				{
					if ( preg_match('/^.+\.(jpg|jpeg|png|gif)$/i', $map) )
					{
						for ( $i = 0; $i < count($data); $i++ )
						{
							if ( $map == $data[$i]['map_file'] )
							{
								unset($file[$key]);
							}
						}
					}
				}
				
				sort($file);
				
				if ( !empty($file) )
				{
					$template->assign_block_vars('_list_create._no_entry', array());
				}
				
				for ( $i = 0; $i < count($file); $i++ )
				{
					if ( preg_match('/^.+\.(jpg|jpeg|png|gif)$/i', $file[$i]) )
					{
						list($width, $height, $type, $attr) = getimagesize($path . '/' . $file[$i]);
						
						$template->assign_block_vars('_list_create._file', array(
							'NUM'		=> $num,
							'NAME'		=> substr($file[$i], 0, strrpos($file[$i], '.')),
							'FILE'		=> $file[$i],
							'IMG'		=> $path . '/' . $file[$i],
							'IMG_WIDTH'	=> $width / 4,
						));
						
						$num++;
					}
				}
				
				$s_fields = '<input type="hidden" name="mode" value="_list_create" /><input type="hidden" name="' . POST_MAPS_CAT_URL . '" value="' . $data_cat . '" /><input type="hidden" name="dir" value="' . $path . '" />';
				
				$template->assign_vars(array(
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_maps.php'),
				));
				
				if ( request('submit', 1) )
				{
					$max	= get_data_max(MAPS, 'map_order', '');
					$order	= $max['max'] + 10;
					
					$nums	= request('num', 4);
					$name	= request('name', 4);
					$file	= request('file', 4);
					
					for ( $i = 0; $i < count($nums); $i++ )
					{
						$sql = "INSERT INTO " . MAPS . " (cat_id, map_name, map_file, map_order)
									VALUES ($data_cat, '" .  $name[$nums[$i]] . "', '" . $file[$nums[$i]] . "', $order)";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$order += 10;
					}
					
					$message = $lang['create_maps_cat'] . sprintf($lang['click_return_maps'], '<a href="' . append_sid('admin_maps.php') . '">', '</a>');
					message(GENERAL_MESSAGE, $message);
				}
				
				$template->pparse('body');
				
				break;
				
			case '_update_map':

				$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
				$template->assign_block_vars('_input_map', array());
				
				debug($_POST);
				
				if ( $mode == '_update_map' && !(request('submit', 2)) )
				{
					$data = get_data(MAPS, $data_id, 1);
				}
				else
				{
					$data = array(
						'cat_id'	=> request('cat_id', 0),
						'map_name'	=> request('map_name', 2),
						'map_order'	=> request('map_order', 0),
					);
				}
				
				$s_fields = '<input type="hidden" name="mode" value="_update_map" /><input type="hidden" name="' . POST_MAPS_URL . '" value="' . $data_id . '" />';

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['maps']),
				#	'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['maps'], $data['cat_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['maps']),

					'NAME'		=> $data['map_name'],
					
					'S_CAT'		=> select_cat('select', MAPS_CAT, 'cat', $data['cat_id']),
				#	'S_ORDER'	=> select_order('select', MAPS, 'cat', 'map', $data['cat_id'], $data['map_order']),
					
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_maps.php'),
				));

				if ( request('submit', 2) )
				{
					$cat_id		= request('cat_id', 0);
					$map_name	= request('map_name', 2);
					$map_order	= request('map_order', 2);
					
					$error = ( !$map_name ) ? $lang['msg_select_name'] : '';
					$error .= ( !$map_file ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_file'] : '';

					if ( !$error )
					{
						$sql = "UPDATE " . MAPS . " SET
									cat_id		= '$cat_id',
									map_name	= '$map_name',
									map_order	= '$map_order'
								WHERE map_id = $data_id";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}

						$message = $lang['update_maps_cat']
							. sprintf($lang['click_return_maps'], '<a href="' . append_sid('admin_maps.php') . '">', '</a>')
							. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_maps.php?mode=' . $mode . '&amp;' . POST_MAPS_CAT_URL . '=' . $data_cat) . '">', '</a>');
							
						log_add(LOG_ADMIN, LOG_SEK_MAPS, $mode, $cat_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');

				break;
			
			case '_delete_map':

				$data = get_data(MAPS, $data_id, 1);

				if ( $data_id && $confirm )
				{
					$sql = "DELETE FROM " . MAPS . " WHERE map_id = $data_id";
					if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					$message = $lang['delete_maps'] . sprintf($lang['click_return_maps'], '<a href="' . append_sid('admin_maps.php') . '">', '</a>');

					log_add(LOG_ADMIN, LOG_SEK_MAPS, $mode, $data['map_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));

					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $data_id . '" />';

					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_maps'], $data['map_name']),
						
						'S_FIELDS'		=> $s_fields,
						'S_ACTION'		=> append_sid('admin_maps.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_maps']);
				}
				
				$template->pparse('body');
				
				break;
				
			default:
				
				message(GENERAL_ERROR, $lang['no_mode']);
				
				break;
		}
	
		if ( $show_index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_maps.tpl'));
	$template->assign_block_vars('_display', array());
	
	$max	= get_data_max(MAPS_CAT, 'cat_order', '');
	$data	= get_data_array(MAPS_CAT, '', 'cat_order', 'ASC');
	
	if ( $data )
	{
		for ( $i = 0; $i < count($data); $i++ )
		{
			$cat_id = $data[$i]['cat_id'];
			
			$template->assign_block_vars('_display._cat_row', array(
				'NAME'		=> $data[$i]['cat_name'],
				'TAG'		=> $data[$i]['cat_tag'],
				
				'MOVE_UP'	=> ( $data[$i]['cat_order'] != '10' )			? '<a href="' . append_sid('admin_maps.php?mode=_order&amp;move=-15&amp;' . POST_MAPS_CAT_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data[$i]['cat_order'] != $max['max'] )	? '<a href="' . append_sid('admin_maps.php?mode=_order&amp;move=15&amp;' . POST_MAPS_CAT_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_LIST'	=> append_sid('admin_maps.php?mode=_list&amp;' . POST_MAPS_CAT_URL . '=' . $cat_id),
				'U_UPDATE'	=> append_sid('admin_maps.php?mode=_update&amp;' . POST_MAPS_CAT_URL . '=' . $cat_id),
				'U_DELETE'	=> append_sid('admin_maps.php?mode=_delete&amp;' . POST_MAPS_CAT_URL . '=' . $cat_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry', array());
	}
	
	$s_fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['maps']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_create'], $lang['map_cat']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['map_cat']),
		'L_EXPLAIN'	=> $lang['maps_explain'],
		
		'S_CREATE'	=> append_sid('admin_maps.php?mode=_create'),
		'S_ACTION'	=> append_sid('admin_maps.php'),
		'S_FIELDS'	=> $s_fields,
	));
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');

}

?>