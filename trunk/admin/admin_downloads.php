<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);

	if ( $userdata['user_level'] == ADMIN || $userauth['auth_downloads'] )
	{
		$module['hm_main']['sm_downloads'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);

	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_downloads';

	include('./pagestart.php');

	add_lang('downloads');

	$error	= '';
	$index	= '';
	$fields	= '';

	$log	= SECTION_DOWNLOADS;
	$url	= POST_DOWNLOADS;
	$cat	= POST_DOWNLOADS_CAT;
	$file	= basename(__FILE__);

	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;

	$data_url	= request($url, INT);
	$data_cat	= request($cat, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);

	$dir_path	= $root_path . $settings['path_downloads'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);

	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_download'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}

	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;

	$template->set_filenames(array(
		'body'		=> 'style/acp_downloads.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
		'tiny_mce'	=> 'style/tinymce_normal.tpl',
	));

	if ( request('add_file', 1) || request('add_cat', 1) )
	{
		$mode = ( request('add_file', 1) ) ? 'create' : '_create_cat';

		if ( $mode == 'create' )
		{
			list($cat_id) = each($_POST['add_file']);
			$data_cat = intval($cat_id);

			$name = trim(htmlentities(str_replace("\'", "'", $_POST['file_name'][$cat_id]), ENT_COMPAT));
		}
	}
	

	/* $settings['download_types'] ??? */
	$mine_types = array('meta_application', 'meta_image', 'meta_text', 'meta_video');

#	debug($mode);
#	debug($_GET);
#	debug($_POST);
#	debug($_FILES);

	$mode = ( in_array($mode, array('create', 'update', '_order', 'delete', '_create_cat', '_update_cat', '_order_cat', '_delete_cat')) ) ? $mode : '';

	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
		case 'update':

				$template->assign_block_vars('input', array());

				if ( $mode == 'create' && !request('submit', TXT) )
				{
					$data = array(
						'file_name'		=> $name,
						'cat_id'		=> '',
						'file_order'	=> '',
					);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(DOWNLOADS, $data_url, false, 1, true);
				}
				else
				{
					$data = array(
						'file_name'		=> request('file_name', 2),
						'cat_id'		=> request($cat, 0),
						'file_order'	=> request('file_order', 0) ? request('file_order', 0) : request('file_order_new', 0),
					);
					
					$ufile = request_file('ufile');
					
					$cats = data(DOWNLOADS_CAT, $data_cat, false, 1, true);
					
					$error .= !$data['file_name'] ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					
					if ( $ufile['temp'] )
					{
						$file_info = upload_dl($ufile, $dir_path . $cats['cat_path'], $cats['cat_types'], $cats['cat_maxsize'], $error);
						
						$data['file_filename'] = $file_info['file_filename'];
						$data['file_type'] = $file_info['file_type'];
						$data['file_size'] = $file_info['file_size'];
					}
					else
					{
						$error .= ( $error ? '<br />' : '' ) . $lang['msg_select_file'];
					}
					
					if ( !$error )
					{
						$data['file_order'] = $data['file_order'] ? $data['file_order'] : maxa(DOWNLOADS, 'file_order', "cat_id = " . $data['cat_id']);
												
						if ( $mode == 'create' )
						{
							$sql = sql(DOWNLOADS, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(DOWNLOADS, $mode, $data, 'file_id', $data_url);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(DOWNLOADS);
						
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
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_url\" />";
				$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data_cat\" />";
				
				$template->assign_vars(array(
				#	'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['download_cat']),
				#	'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['download_cat'], $data['cat_title']),
				#	'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['cat']),
				#	'L_DESC'	=> sprintf($lang['sprintf_desc'], $lang['cat']),
				#	'L_ICON'	=> $lang['cat_icon'],

					'NAME'	=> $data['file_name'],
				#	'DESC'		=> $data['cat_desc'],
				
					'S_FIELDS'	=> $fields,
					'S_ACTION'	=> check_sid($file),
				));

				$template->pparse('body');

				break;

			case 'order':

				update(DOWNLOADS_CAT, 'download_cat', $move, $data_cat);
				orders(DOWNLOADS_CAT);

				log_add(LOG_ADMIN, SECTION_DOWNLOADS_CAT, 'acp_download_cat_order');

				$index = true;

				break;

			case 'delete':

				$data = get_data(DOWNLOADS_CAT, $data_cat, 1);

				if ( $data_cat && $confirm )
				{
					$sql = "DELETE FROM " . DOWNLOADS_CAT . " WHERE cat_id = $data_cat";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					$message = $lang['delete_download_cat'] . sprintf($lang['click_return_download_cat'], '<a href="' . check_sid($file));
					log_add(LOG_ADMIN, SECTION_DOWNLOADS_CAT, 'delete_download_cat');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_cat && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));

					$fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . $cat . '" value="' . $data_cat . '" />';

					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['delete_confirm_download_cat'], $data['cat_title']),

						'S_FIELDS'		=> $fields,
						'S_ACTION'		=> check_sid($file),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_download_cat']);
				}

				$template->pparse('body');

				break;

			case 'create_cat':
			case 'update_cat':

				$template->assign_block_vars('cat', array());
				
				if ( $mode == '_create_cat' && !(request('submit', TXT)) )
				{
					$data = array(
						'cat_name'		=> request('cat_name', 2),
						'cat_desc'		=> '',
						'cat_icon'		=> 0,
						'cat_path'		=> '',
						'cat_types'		=> 0,
						'cat_auth'		=> 0,
						'cat_rate'		=> 0,
						'cat_comment'	=> 0,
						'time_create'	=> time(),
						'time_update'	=> 0,
						'time_upload'	=> 0,
						'cat_order'		=> '',
					);
				}
				else if ( $mode == '_update_cat' && !(request('submit', TXT)) )
				{
					$data = data(DOWNLOADS_CAT, $data_cat, false, 1, true);
				}
				else
				{
					$data = array(
						'cat_name'		=> request('cat_name', 2),
						'cat_desc'		=> request('cat_desc', 2),
						'cat_icon'		=> request('cat_icon', 2),
						'cat_path'		=> request('cat_path', 2),
						'cat_types'		=> request('cat_types', 4),
						'cat_auth'		=> request('cat_auth', 0),
						'cat_rate'		=> request('cat_rate', 0),
						'cat_comment'	=> request('cat_comment', 0),
						'time_create'	=> request('time_create', 0),
						'time_update'	=> request('time_update', 0),
						'time_upload'	=> request('time_upload', 0),
						'cat_order'		=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
					);
					
					$data['cat_types'] = is_array($data['cat_types']) ? implode(', ', $data['cat_types']) : '';
					$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(DOWNLOADS_CAT, 'cat_order', '');

					if ( !$error )
					{
						if ( $mode == '_create_cat' )
						{
							$data['cat_path'] = create_folder($dir_path, 'download_', true);

							$sql = sql(DOWNLOADS_CAT, $mode, $data);
							$msg = $lang['create_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(DOWNLOADS_CAT, $mode, $data, 'cat_id', $data_cat);
							$msg = $lang['update_cat'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$cat=$data_cat"));
						}

						orders(DOWNLOADS_CAT);

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
				
				$s_types = '<select class="postsmall" id="cat_types" name="cat_types[]" size="15" multiple="multiple">';

				foreach ( $mine_types as $meta_type )
				{
					$_type = str_replace('meta', 'type', $meta_type);

					$s_types .= "<optgroup label=\"" . sprintf($lang['sprintf_select_format'], $lang[$meta_type]) . "\">";

					foreach ( $lang[$_type] as $type => $mine )
					{
						$marked = ( in_array($mine, explode(', ', $data['cat_types'])) ) ? ' selected="selected"' : '';
						$s_types .= "<option value=\"{$mine}\"$marked>&nbsp;&nbsp;&nbsp;" . sprintf($lang['sprintf_select_format'], $type) . "</option>";
					}
				}

				$s_types .= '</select>';
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data_cat\" />";
				$fields .= "<input type=\"hidden\" name=\"cat_path\" value=\"" . $data['cat_path'] . "\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_cat', '', $mode)], $lang['name_cat'], $data['cat_name']),
					'L_DATA'	=> $lang['data_cat'],

					'L_NAME'	=> $lang['name_cat'],
					'L_DESC'	=> $lang['desc_cat'],
					'L_ICON'	=> $lang['icon_cat'],
					'L_PATH'	=> $lang['path_cat'],
					'L_TYPES'	=> $lang['types_cat'],
					'L_AUTH'	=> $lang['auth_cat'],
					'L_RATE'	=> $lang['rate_cat'],
					'L_COMMENT'	=> $lang['comment_cat'],
					
					'NAME'	=> $data['cat_name'],
					'DESC'	=> $data['cat_desc'],
					'PATH'	=> $data['cat_path'],
					
					'S_ICON'	=> '',
					'S_TYPES'	=> $s_types,
					'S_AUTH'	=> select_level('', 'user_level', 'cat_auth', $data['cat_auth'], 'user_level'),
					'S_RATE'	=> select_level('', 'user_level', 'cat_rate', $data['cat_rate']),
					'S_COMMENT'	=> select_level('', 'user_level', 'cat_comment', $data['cat_comment']),
					
					'S_ORDER'	=> simple_order(DOWNLOADS_CAT, false, 'select', $data['cat_order']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
			
				break;
				
			case 'order_cat':
				
				update(DOWNLOADS_CAT, 'cat', $move, $data_cat);
				orders(DOWNLOADS_CAT);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case 'delete_cat':
			
				$data = data(DOWNLOADS_CAT, $data_cat, false, 1, true);
			
				if ( $data_cat && $confirm )
				{
					$sql = sql(DOWNLOADS_CAT, $mode, $data, 'cat_id', $data_cat);
					$msg = $lang['delete_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					sql(DOWNLOADS, $mode, false, 'cat_id', $data_cat);
					
					delete_folder($dir_path . $data['cat_path'] . '/');

					orders(DOWNLOADS_CAT);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_cat && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data_cat\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['cat_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title_cat']));
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
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['file']),
		'L_CREATE_CAT'	=> sprintf($lang['sprintf_create'], $lang['cat']),
		
		'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['cat']),
		'L_EXPLAIN'		=> $lang['explain'],

		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$max = maxi(DOWNLOADS_CAT, 'cat_order', '');
	$tmp = data(DOWNLOADS_CAT, '', '', 1, false);
	
	if ( $tmp )
	{
		$cnt = count($tmp);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$cid	= $tmp[$i]['cat_id'];
			$cname	= $tmp[$i]['cat_name'];
			$csize	= $tmp[$i]['cat_size'];
			$cfiles	= $tmp[$i]['cat_files'];
			$ctypes	= $tmp[$i]['cat_types'];
			$corder	= $tmp[$i]['cat_order'];
			
			$typ = '';
			
			foreach ( $mine_types as $meta_type )
			{
				$_type = str_replace('meta', 'type', $meta_type);

				foreach ( $lang[$_type] as $type => $mine )
				{
					if ( in_array($mine, explode(', ', $tmp[$i]['cat_types'])) )
					{
						$typ[] = $type;
					}
				}
			}
			
			$typ = is_array($typ) ? implode(', ', $typ) : $typ;
			
			$template->assign_block_vars('display.cat_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => '_update_cat', $cat => $cid), $cname, ''),
				
				'PATH'		=> $dir_path . $tmp[$i]['cat_path'],
				
				'TYPE'		=> $typ,

				'MOVE_UP'	=> ( $corder != '10' ) ? href('a_img', $file, array('mode' => '_order_cat', 'move' => '-15', $cat => $cid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $corder != $max ) ? href('a_img', $file, array('mode' => '_order_cat', 'move' => '+15', $cat => $cid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

				'RESYNC'	=> $files ? href('a_img', $file, array('mode' => '_rescny_cat', $cat => $cid), 'icon_resync', 'common_resync') : img('i_icon', 'icon_resync2', 'common_resync'),
				'UPDATE'	=> href('a_img', $file, array('mode' => '_update_cat', $cat => $cid), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => '_delete_cat', $cat => $cid), 'icon_cancel', 'common_delete'),
				
				'S_NAME'	=> "file_name[$cid]",
				'S_SUBMIT'	=> "add_file[$cid]",
			));
			
			$files = data(DOWNLOADS, "cat_id = $cid", 'cat_id, file_order ASC', 1, false);
			
			$sql = "SELECT MAX(file_order) AS max$cid FROM " . DOWNLOADS . " WHERE cat_id = $cid";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_file = $db->sql_fetchrow($result);
			
			if ( !$files )
			{
				$template->assign_block_vars('display.catrow._empty', array());
			}
			else
			{
				for ( $j = 0; $j < count($files); $j++ )
				{
					$file_id = $files[$j]['file_id'];
					
					if ( $files[$j]['cat_id'] == $cid )
					{
						$template->assign_block_vars('display.catrow._file_row', array(
							'NAME'		=> $files[$j]['file_name'],
							
							'MOVE_UP'	=> ( $files[$j]['file_order'] != '10' )							? '<a href="' . check_sid("$file?mode=_order&amp;cat_type=$cid&amp;move=-15&amp;$url=$file_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
							'MOVE_DOWN'	=> ( $files[$j]['file_order'] != $max_file['max' . $cid] )	? '<a href="' . check_sid("$file?mode=_order&amp;cat_type=$cid&amp;move=+15&amp;$url=$file_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',
							
							'U_RESYNC' => check_sid("$file?mode=_resync&amp;$url=$file_id"),
							'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$file_id") . '"><img src="' . $images['icon_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
							'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$file_id") . '"><img src="' . $images['icon_cancel'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
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