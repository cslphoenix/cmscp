<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_gallery'] )
	{
		$module['hm_main']['sm_gallery'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_gallery';
	
	include('./pagestart.php');
	
	load_lang('gallery');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GALLERY;
	$url	= POST_GALLERY;
	$url_p	= POST_PIC;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_pic	= request($url_p, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$order		= request('order', 0);
	
	$path_dir	= $root_path . $settings['path_gallery'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['gallery']);
	
	$auth_fields	= array('auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_upload');
	$auth_levels	= array('guest', 'user', 'trial', 'member', 'coleader', 'leader', 'uploader');
	$auth_constants	= array(AUTH_GUEST, AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOADER);
	/* values for rights form uoload */
	$auth_lvl_ul	= array('user', 'trial', 'member', 'coleader', 'leader');
	$auth_con_ul	= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER);
	/* values for rights form no guest */
	$auth_lvl_gn	= array('user', 'trial', 'member', 'coleader', 'leader', 'uploader');
	$auth_con_gn	= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOADER);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_gallery'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_gallery.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$data = array(
								'gallery_name'		=> request('gallery_name', 2),
								'gallery_desc'		=> '',
								'gallery_comments'	=> '1',
								'gallery_rate'		=> '1',
								'auth_view'			=> $gallery_settings['auth_view'],
								'auth_edit'			=> $gallery_settings['auth_edit'],
								'auth_delete'		=> $gallery_settings['auth_delete'],
								'auth_rate'			=> $gallery_settings['auth_rate'],
								'auth_upload'		=> $gallery_settings['auth_upload'],
								'per_rows'			=> $gallery_settings['per_rows'],
								'per_cols'			=> $gallery_settings['per_cols'],
								'max_width'			=> $gallery_settings['max_width'],
								'max_height'		=> $gallery_settings['max_height'],
								'max_filesize'		=> $gallery_settings['max_filesize'],
								'preview_list'		=> $gallery_settings['preview_list'],
								'preview_widht'		=> $gallery_settings['preview_widht'],
								'preview_height'	=> $gallery_settings['preview_height'],
								'gallery_order'		=> '',
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(GALLERY, $data_id, false, 1, 1);
					
					( $data['gallery_pics'] ) ? $template->assign_block_vars('_input._overview', array()) : false;
					
					$template->assign_block_vars('_input._upload', array());
				}
				else
				{
					$data = array(
								'gallery_name'		=> request('gallery_name', 2),
								'gallery_desc'		=> request('gallery_desc', 3),
								'gallery_comments'	=> request('gallery_comments', 0),
								'gallery_rate'		=> request('gallery_rate', 0),
								'auth_view'			=> request('auth_view', 0),
								'auth_edit'			=> request('auth_edit', 0),
								'auth_delete'		=> request('auth_delete', 0),
								'auth_rate'			=> request('auth_rate', 0),
								'auth_upload'		=> request('auth_upload', 0),
								'per_rows'			=> request('per_rows', 0) ? request('per_rows', 0) : $gallery_settings['per_rows'],
								'per_cols'			=> request('per_cols', 0) ? request('per_cols', 0) : $gallery_settings['per_cols'],
								'max_width'			=> request('max_width', 0) ? request('max_width', 0) : $gallery_settings['max_width'],
								'max_height'		=> request('max_height', 0) ? request('max_height', 0) : $gallery_settings['max_height'],
								'max_filesize'		=> request('max_filesize', 0) ? request('max_filesize', 0) : $gallery_settings['max_filesize'],
								'preview_list'		=> ( request('preview_list', 0) < 2 ) ? request('preview_list', 0) : $gallery_settings['preview_list'],
								'preview_widht'		=> request('preview_widht', 0) ? request('preview_widht', 0) : $gallery_settings['preview_widht'],
								'preview_height'	=> request('preview_height', 0) ? request('preview_height', 0) : $gallery_settings['preview_height'],
								'gallery_order'		=> request('gallery_order', 0) ? request('gallery_order', 0) : request('gallery_order_new', 0),
							);
				}
				
				for ( $j = 0; $j < count($auth_fields); $j++ )
				{
					$custom_auth[$j] = '<select class="selectsmall" name="' . $auth_fields[$j] . '" id="' . $auth_fields[$j] . '">';
					
					for ( $k = 0; $k < count($auth_levels); $k++ )
					{
						$disabled = ( $j == '4' && ( $k == '0' || $k == '6' ) ) ? ' disabled' : '';
						$selected = ( $data[$auth_fields[$j]] == $auth_constants[$k] ) ? ' selected="selected"' : '';
						$custom_auth[$j] .= '<option value="' . $auth_constants[$k] . '"' . $selected . $disabled . '>' . sprintf($lang['sprintf_select_format'], $lang['auth_gallery_' . $auth_levels[$k]]) . '</option>';
					}
					$custom_auth[$j] .= '</select>';
			
					$template->assign_block_vars('_input._auth', array(
						'TITLE'		=> $lang['auth_gallery'][$auth_fields[$j]],
						'INFO'		=> $auth_fields[$j],
						'SELECT'	=> $custom_auth[$j],
					));
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['gallery'], $data['gallery_name']),
					'L_UPLOAD'			=> $lang['common_upload'],
					'L_OVERVIEW'		=> $lang['common_overview'],
					
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['gallery']),
					'L_DESC'			=> sprintf($lang['sprintf_desc'], $lang['gallery']),
					'L_AUTH'			=> sprintf($lang['sprintf_auth'], $lang['gallery']),
					'L_RATE'			=> sprintf($lang['sprintf_rating'], $lang['gallery']),
					'L_COMMENT'			=> sprintf($lang['sprintf_comments'], $lang['gallery']),
					
					'L_LIST'			=> $lang['list'],
					'L_PREVIEW'			=> $lang['preview'],
					
					'L_PER_ROWS'		=> $lang['per_rows'],
					'L_PER_COLS'		=> $lang['per_cols'],
					'L_MAX_WIDTH'		=> $lang['max_width'],
					'L_MAX_HEIGHT'		=> $lang['max_height'],
					'L_MAX_FILESIZE'	=> $lang['max_filesize'],
					'L_PREVIEW_LIST'	=> $lang['preview_list'],
					'L_PREVIEW_WIDHT'	=> $lang['preview_widht'],
					'L_PREVIEW_HEIGHT'	=> $lang['preview_height'],
					
					'NAME'				=> $data['gallery_name'],
					'DESC'				=> $data['gallery_desc'],
					'PER_ROWS'			=> $data['per_rows'],
					'PER_COLS'			=> $data['per_cols'],
					'MAX_WIDTH'			=> $data['max_width'],
					'MAX_HEIGHT'		=> $data['max_height'],
					'MAX_FILESIZE'		=> $data['max_filesize'],
					'PREVIEW_WIDHT'		=> $data['preview_widht'],
					'PREVIEW_HEIGHT'	=> $data['preview_height'],
					
					'S_LIST_NO'			=> (!$data['preview_list'] ) ? ' checked="checked"' : '',
					'S_LIST_YES'		=> ( $data['preview_list'] ) ? ' checked="checked"' : '',					
					'S_RATE_NO'			=> (!$data['gallery_rate'] ) ? ' checked="checked"' : '',
					'S_RATE_YES'		=> ( $data['gallery_rate'] ) ? ' checked="checked"' : '',
					'S_COMMENT_NO'		=> (!$data['gallery_comments'] ) ? ' checked="checked"' : '',
					'S_COMMENT_YES'		=> ( $data['gallery_comments'] ) ? ' checked="checked"' : '',
									
					'S_UPLOAD'			=> check_sid("$file?mode=_upload&amp;$url=$data_id"),
					'S_OVERVIEW'		=> check_sid("$file?mode=_overview&amp;$url=$data_id"),
					
					'S_ACTION'			=> check_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$error .= ( !$data['gallery_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['gallery_desc'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_desc'] : '';
					
					if ( !$error )
					{
						$data['gallery_order'] = ( !$data['gallery_order'] ) ? maxa(GALLERY, 'gallery_order', false) : $data['gallery_order'];
						
						if ( $mode == '_create' )
						{
							$data['gallery_path'] = create_folder($path_dir, 'gallery_', true);
							
							$sql = sql(GALLERY, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(GALLERY, $mode, $data, 'gallery_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
				
				$template->pparse('body');
				
				break;
			
			case '_overview':
			
				$template->assign_block_vars('_overview', array());
				
				if ( $order )
				{
					update(GALLERY_PIC, 'pic', $move, $data_pic);
					orders(GALLERY_PIC, $data_id);
					
					log_add(LOG_ADMIN, $log, '_order_pic');
				}
				
			
				$data_gallery	= data(GALLERY, $data_id, false, 1, true);
				$data_pics		= data(GALLERY_PIC, $data_id, 'pic_order ASC', 1, false);
				
				$max = maxi(GALLERY_PIC, 'pic_order', '');
				
				if ( $data_pics )
				{
					if ( $data_gallery['preview_list'] )
					{
						$template->assign_block_vars('_overview._list', array());
						
						for ( $i = $start; $i < min($data_gallery['per_cols'] + $start, count($data_pics)); $i++ )
						{
							$pic_id	= $data_pics[$i]['pic_id'];
							$prev	= $path_dir . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_preview'];
							$image	= $path_dir . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename'];
							
							list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename']);
							
							$template->assign_block_vars('_overview._list._gallery_row', array(
								'PIC_ID'	=> $data_pics[$i]['pic_id'],
								'TITLE'		=> ( $data_pics[$i]['pic_title'] ) ? $data_pics[$i]['pic_title'] : 'kein Titel',							
								'PREV'		=> $prev,
								'IMAGE'		=> $image,							
								'WIDTH'		=> $width,
								'HEIGHT'	=> $height,
								'NAME'		=> $data_pics[$i]['pic_filename'],
								'SIZE'		=> size_file($data_pics[$i]['pic_size']),
								
								'ORDER'		=> $data_pics[$i]['pic_order'],
								
								'MOVE_UP'	=> ( $data_pics[$i]['pic_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
								'MOVE_DOWN'	=> ( $data_pics[$i]['pic_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
	
							));
						}
					}
					else
					{
						$template->assign_block_vars('_overview._preview', array());
						
						for ( $i = $start; $i < min($data_gallery['per_cols'] + $start, count($data_pics)); $i += $data_gallery['per_rows'] )
						{
							if ( count($data_pics) > 0 )
							{
								$template->assign_block_vars('_overview._preview._gallery_row', array());
							}
							
							for ( $j = $i; $j < ( $i + $data_gallery['per_rows'] ); $j++ )
							{
								if ( $j >= count($data_pics) )
								{
									break;
								}
								
								$pic_id	= $data_pics[$i]['pic_id'];
								$prev	= $path_dir . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_preview'];
								$image	= $path_dir . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename'];
								
								list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename']);
								
								$template->assign_block_vars('_overview._preview._gallery_row._gallery_col', array(
									'PIC_ID'	=> $data_pics[$i]['pic_id'],
									'TITLE'		=> $data_pics[$i]['pic_title'] ? $data_pics[$i]['pic_title'] : 'kein Titel',							
									'PREV'		=> $prev,
									'IMAGE'		=> $image,							
									'WIDTH'		=> $width,
									'HEIGHT'	=> $height,
									'NAME'		=> $data_pics[$i]['pic_filename'],
									'SIZE'		=> size_file($data_pics[$i]['pic_size']),
									
									'ORDER'		=> $data_pics[$i]['pic_order'],

									'MOVE_UP'	=> ( $data_pics[$i]['pic_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
									'MOVE_DOWN'	=> ( $data_pics[$i]['pic_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

								));
							}
						}
					}
					
					$current_page = ( !count($data_pics) ) ? 1 : ceil( count($data_pics) / $data_gallery['per_cols'] );
			
					$template->assign_vars(array(
						'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $data_gallery['per_cols'] ) + 1 ), $current_page ),
						'PAGINATION'	=> generate_pagination("$file?mode=_overview&$url=$data_id", count($data_pics), $data_gallery['per_cols'], $start),
					));
				}
				else
				{
					$template->assign_block_vars('_overview._entry_empty', array());
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['sprintf_update'], $lang['gallery'], $data_gallery['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['common_overview'],
					
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['pic']),
					'L_WIDTH'		=> $lang['pic_widht'],
					'L_HEIGHT'		=> $lang['pic_height'],
					'L_SIZE'		=> $lang['pic_size'],
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['pic']),
					
					'PICS_PER_LINE'	=> $data_gallery['per_rows'],
					'PREVIEW_WIDHT'	=> $data_gallery['preview_widht'],
					
					'S_UPDATE'		=> check_sid("$file?mode=_update&amp;$url=$data_id"),
					'S_UPLOAD'		=> check_sid("$file?mode=_upload&amp;$url=$data_id"),
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$data = get_data(GALLERY, $data_id, 1);
					
					$pic_id		= request('pics', 1);
					$pic_title	= request('pic_title', 4);
					$pic_order	= request('pic_order', 4);
					
					foreach ( $pic_order as $o_key => $o_value )
					{
						foreach ( $pic_title as $t_key => $t_value )
						{
							if ( $o_key == $t_key )
							{						
								$sql = "UPDATE " . GALLERY_PIC . " SET pic_title = '$t_value', pic_order = '$o_value'  WHERE pic_id = $o_key";
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
						}
					}
					
					if ( $pic_id )
					{
						$sql_in = implode(', ', $pic_id);
				
						$sql = "SELECT * FROM " . GALLERY_PIC . " WHERE pic_id IN ($sql_in) AND gallery_id = $data_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						$pic_data = $db->sql_fetchrowset($result);
						
						for ( $i = 0; $i < count($pic_id); $i++ )
						{
							gallery_delete($pic_data[$i]['pic_filename'], $pic_data[$i]['pic_preview'], $path_dir . $data['gallery_path'] . '/');
						}
						
						$sql = "DELETE FROM " . GALLERY_PIC . " WHERE pic_id IN ($sql_in) AND gallery_id = $data_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$sql = "UPDATE " . GALLERY . " SET gallery_pics = gallery_pics - " .  count($pic_id) . " WHERE gallery_id = $data_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					orders(GALLERY_PIC, $data_id);
					
					$message = $lang['update_gallery_pic']
						. sprintf($lang['click_return_gallery_pic'], '<a href="' . check_sid($file) . '">', '</a>')
						. sprintf($lang['return_update'], '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id"));
					log_add(LOG_ADMIN, $log, 'update_gallery_pic');
					message(GENERAL_MESSAGE, $message);
				}
				
				$template->pparse('body');
				
				break;
			
			case '_upload':
			
				$template->assign_block_vars('_upload', array());
				
				$data = data(GALLERY, $data_id, false, 1, true);
				$pics = data(GALLERY_PIC, $data_id, 'pic_order ASC', 1, false);
				$next = maxa(GALLERY_PIC, 'pic_order', "gallery_id = $data_id");
				
				( $pics ) ? $template->assign_block_vars('_upload._overview', array()) : false;
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['sprintf_update'], $lang['gallery'], $data['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['common_overview'],
					
					'S_INPUT'		=> check_sid("$file?mode=_update&amp;$url=$data_id"),
					'S_OVERVIEW'	=> check_sid("$file?mode=_overview&amp;$url=$data_id"),
					
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$pic_file = request_files('ufile');
					
					$nums = count($pic_file['temp']);
					
					if ( $nums )
					{
						for ( $i = 0; $i < $nums; $i++ )
						{
							$sql_pic[] = gallery_upload($data['gallery_path'], $pic_file['temp'][$i], $pic_file['name'][$i], $pic_file['size'][$i], $pic_file['type'][$i], $data['max_width'], $data['max_height'], $data['max_filesize'], $data['preview_widht'], $data['preview_height']);
						}
						
						$sql = "UPDATE " . GALLERY . " SET gallery_pics = gallery_pics + $nums WHERE gallery_id = $data_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$db_ary = array();
						$pic_ary = array();
						
						for ( $i = 0; $i < $nums; $i++ )
						{
							$pic_ary[] = array(
								'pic_title'		=> "'" . $_POST['title'][$i] . "'",
								'pic_size'		=> "'" . $sql_pic[$i]['pic_size'] . "'",
								'gallery_id'	=> $data_id,
								'pic_filename'	=> "'" . $sql_pic[$i]['pic_filename'] . "'",
								'pic_preview'	=> "'" . $sql_pic[$i]['pic_preview'] . "'",
								'upload_user'	=> $userdata['user_id'],
								'upload_time'	=> time(),
								'pic_order'		=> $next,
							);
							$next += 10;
						}
										
						foreach ( $pic_ary as $id => $_pic_ary )
						{
							$values = array();
							foreach ( $_pic_ary as $key => $var )
							{
								$values[] = $var;
							}
							$db_ary[] = '(' . implode(', ', $values) . ')';
						}
						
						$sql = "INSERT INTO " . GALLERY_PIC . " (" . implode(', ', array_keys($pic_ary[0])) . ") VALUES " . implode(', ', $db_ary);
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$msg = $lang['update_u'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						
						log_add(LOG_ADMIN, $log, $mode);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						message(GENERAL_MESSAGE, 'test-error');
					}
				}
				
				$template->pparse('body');
			
				break;
			/*
			case '_upload_save':
			
				$gallery = get_data(GALLERY, $data_id, 1);
				$gallery_image = request_files('ufile');
	
				$image_num = count($gallery_image['temp']);
			
				for ( $i = 0; $i < $image_num; $i++ )
				{
					$sql_pic[] = gallery_upload($data['gallery_path'], $gallery_image['temp'][$i], $gallery_image['name'][$i], $gallery_image['size'][$i], $gallery_image['type'][$i], $data['max_width'], $data['max_height'], $data['max_filesize'], $data['preview_widht'], $data['preview_height']);
				}
				
				$sql = "UPDATE " . GALLERY . " SET gallery_pics = gallery_pics + $image_num WHERE gallery_id = $data_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$db_ary = array();
				$pic_ary = array();
				
				for ( $i = 0; $i < $image_num; $i++ )
				{
					$pic_ary[] = array(
						'pic_title'		=> "'" . $_POST['title'][$i] . "'",
						'pic_size'		=> "'" . $sql_pic[$i]['pic_size'] . "'",
						'gallery_id'	=> $data_id,
						'pic_filename'	=> "'" . $sql_pic[$i]['pic_filename'] . "'",
						'pic_preview'	=> "'" . $sql_pic[$i]['pic_preview'] . "'",
						'upload_user'	=> $userdata['user_id'],
						'upload_time'	=> time(),
					);
				}
								
				foreach ( $pic_ary as $id => $_pic_ary )
				{
					$values = array();
					foreach ( $_pic_ary as $key => $var )
					{
						$values[] = $var;
					}
					$db_ary[] = '(' . implode(', ', $values) . ')';
				}
				
				$sql = "INSERT INTO " . GALLERY_PIC . " (" . implode(', ', array_keys($pic_ary[0])) . ") VALUES " . implode(', ', $db_ary);
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$msg = $lang['update_u'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $msg);
	
				break;
			*/	
			case '_default':
				
				$template->assign_block_vars('_default', array());
				
				if ( !request('submit', 1) )
				{
					$data = $gallery_settings;
				}
				else
				{
					$data = array(
								'auth_view'			=> ( request('auth_view') )			? request('auth_view', 0) : $gallery_settings['auth_view'],
								'auth_edit'			=> ( request('auth_edit') )			? request('auth_edit', 0) : $gallery_settings['auth_edit'],
								'auth_rate'			=> ( request('auth_rate') )			? request('auth_rate', 0) : $gallery_settings['auth_rate'],
								'auth_delete'		=> ( request('auth_delete') )		? request('auth_delete', 0) : $gallery_settings['auth_delete'],
								'auth_upload'		=> ( request('auth_upload') )		? request('auth_upload', 0) : $gallery_settings['auth_upload'],
								'per_rows'			=> ( request('per_rows') )			? request('per_rows', 0) : $gallery_settings['per_rows'],
								'per_cols'			=> ( request('per_cols') )			? request('per_cols', 0) : $gallery_settings['per_cols'],
								'max_width'			=> ( request('max_width') )			? request('max_width', 0) : $gallery_settings['max_width'],
								'max_height'		=> ( request('max_height') )		? request('max_height', 0) : $gallery_settings['max_height'],
								'max_filesize'		=> ( request('max_filesize') )		? request('max_filesize', 0) : $gallery_settings['max_filesize'],
								'preview_list'		=> ( request('preview_list') )		? request('preview_list', 0) : $gallery_settings['preview_list'],
								'preview_widht'		=> ( request('preview_widht') )		? request('preview_widht', 0) : $gallery_settings['preview_widht'],
								'preview_height'	=> ( request('preview_height') )	? request('preview_height', 0) : $gallery_settings['preview_height'],
							);
				}
				
				for ( $j = 0; $j < count($auth_fields); $j++ )
				{
					$drop_box[$j] = "<select class=\"selectsmall\" name=\"$auth_fields[$j]\" id=\"$auth_fields[$j]\">";
					
					$drop_box[$j] .= "<option value=\"\">" . $lang['msg_select_item'] . "</option>";
					
					if ( $auth_fields[$j] == 'auth_view' || $auth_fields[$j] == 'auth_rate' )
					{
						for ( $k = 0; $k < count($auth_levels); $k++ )
						{
							$selected = ( $data[$auth_fields[$j]] == $auth_constants[$k] ) ? ' selected="selected"' : '';
							$drop_box[$j] .= "<option value=\"$auth_constants[$k]\"$selected>" . sprintf($lang['sprintf_select_format'], $lang['auth_gallery_' . $auth_levels[$k]]) . "</option>";
						}
					}
					else if ( $auth_fields[$j] == 'auth_edit' || $auth_fields[$j] == 'auth_delete' )
					{
						for ( $l = 0; $l < count($auth_lvl_gn); $l++ )
						{
							$selected = ( $data[$auth_fields[$j]] == $auth_con_gn[$l] ) ? ' selected="selected"' : '';
							$drop_box[$j] .= "<option value=\"$auth_con_gn[$l]\"$selected>" . sprintf($lang['sprintf_select_format'], $lang['auth_gallery_' . $auth_lvl_gn[$l]]) . "</option>";
						}
					}
					else if ( $auth_fields[$j] == 'auth_upload' )
					{
						for ( $m = 0; $m < count($auth_lvl_ul); $m++ )
						{
							$selected = ( $data[$auth_fields[$j]] == $auth_con_ul[$m] ) ? ' selected="selected"' : '';
							$drop_box[$j] .= "<option value=\"$auth_con_ul[$m]\"$selected>" . sprintf($lang['sprintf_select_format'], $lang['auth_gallery_' . $auth_lvl_ul[$m]]) . "</option>";
						}
					}
					
					$drop_box[$j] .= "</select>";
					
					$template->assign_block_vars('_default._auth_gallery', array(
						'L_NAME'	=> $lang['auth_gallery'][$auth_fields[$j]],
						'FIELD'		=> $auth_fields[$j],
						'S_DROP'	=> $drop_box[$j],
					));
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_CREATE'		=> sprintf($lang['sprintf_new_create'], $lang['gallery']),
					'L_DEFAULT'		=> $lang['common_default'],
					'L_EXPLAIN'		=> $lang['explain_d'],
					
					'L_AUTH'			=> sprintf($lang['sprintf_auth'], $lang['gallery']),
					'L_PER_ROWS'		=> $lang['per_rows'],
					'L_PER_COLS'		=> $lang['per_cols'],
					'L_MAX_WIDTH'		=> $lang['max_width'],
					'L_MAX_HEIGHT'		=> $lang['max_height'],
					'L_MAX_FILESIZE'	=> $lang['max_filesize'],
					'L_PREVIEW_LIST'	=> $lang['preview_list'],
					'L_PREVIEW_WIDHT'	=> $lang['preview_widht'],
					'L_PREVIEW_HEIGHT'	=> $lang['preview_height'],
					
					'L_LIST'			=> $lang['list'],
					'L_PREVIEW'			=> $lang['preview'],
					
					'AUTH_VIEW'			=> $gallery_settings['auth_view'],
					'AUTH_EDIT'			=> $gallery_settings['auth_edit'],
					'AUTH_DELETE'		=> $gallery_settings['auth_delete'],
					'AUTH_RATE'			=> $gallery_settings['auth_rate'],
					'AUTH_UPLOAD'		=> $gallery_settings['auth_upload'],
					'PER_ROWS'			=> $gallery_settings['per_rows'],
					'PER_COLS'			=> $gallery_settings['per_cols'],
					'MAX_WIDTH'			=> $gallery_settings['max_width'],
					'MAX_HEIGHT'		=> $gallery_settings['max_height'],
					'MAX_FILESIZE'		=> $gallery_settings['max_filesize'],
					'PREVIEW_WIDHT'		=> $gallery_settings['preview_widht'],
					'PREVIEW_HEIGHT'	=> $gallery_settings['preview_height'],
					
					'S_LIST_NO'			=> (!$gallery_settings['preview_list'] ) ? 'checked="checked"' : '',
					'S_LIST_YES'		=> ( $gallery_settings['preview_list'] ) ? 'checked="checked"' : '',
										
					'S_CREATE'			=> check_sid("$file?mode=_create"),
					'S_ACTION'			=> check_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$sql = "SELECT * FROM " . GALLERY_SETTINGS;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					else
					{
						while ( $row = $db->sql_fetchrow($result) )
						{
							$config_name	= $row['config_name'];
							$config_value	= $row['config_value'];
							
							$old[$config_name] = $config_value;
							$new[$config_name] = ( request($config_name, 1) ) ? request($config_name, 0) : $old[$config_name];
							
							$sql = "UPDATE " . GALLERY_SETTINGS . " SET config_value = '$new[$config_name]' WHERE config_name = '$config_name'";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}
					
					$oCache->deleteCache('gallery');
					
					$msg = $lang['update_d'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=_default"));
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				
				$template->pparse('body');
				
				break;
				
			case '_order':
				
				update(GALLERY, 'gallery', $move, $data_id);
				orders(GALLERY);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_resync':
			
				$data = data(GALLERY, $data_id, false, 1, true);
				
				if ( is_dir($path_dir . $data['gallery_path'] . '/') )
				{
					$pics_db = data(GALLERY_PIC, $data_id, false, 1, false);
					
					$path_files = scandir($path_dir . $data['gallery_path'] . '/');
						
					foreach ( $path_files as $files )
					{
						if ( $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' )
						{
							$pics_dir[] = $files;
						}
					}
					
					for ( $i = 0; $i < count($pics_db); $i++ )
					{
						foreach ( $pics_dir as $pic )
						{
							if ( $pics_db[$i]['pic_filename'] == $pic )
							{
								$_ary_check[$pics_db[$i]['pic_id']]['pic_filename'] = $pic;
							}
							else if ( $pics_db[$i]['pic_preview'] == $pic )
							{
								$_ary_check[$pics_db[$i]['pic_id']]['pic_preview'] = $pic;
							}
						}
					}
					
					$cnt = isset($_ary_check) ? count($_ary_check) : 0;
				}
				else
				{
					$data['gallery_path'] = create_folder($path_dir, 'gallery_', true);
					$data['gallery_pics'] = 0;
					
					$sql = sql(GALLERY, 'update', $data, 'gallery_id', $data_id);
				}
				
			#	$index = true;
				
				break;
				
			case '_delete':
			
				$data = data(GALLERY, $data_id, false, 1, 1);

				if ( $data_id && $confirm )
				{	
					$sql = "DELETE FROM " . GALLERY . " WHERE gallery_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = "DELETE FROM " . GALLERY_PIC . " WHERE gallery_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					delete_folder($path_dir . $data['gallery_path'] . '/');
					
					$message = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['gallery_name']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_gallery']);
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
	
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_create'], $lang['gallery']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['gallery']),
		'L_EXPLAIN'		=> $lang['explain'],
		'L_DEFAULT'		=> $lang['common_default'],
		
		'S_DEFAULT'	=> check_sid("$file?mode=_default"),
		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max	= maxi(GALLERY, 'gallery_order', '');
	$data	= data(GALLERY, false, 'gallery_order ASC', 1, false);

	if ( $data )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data)); $i++ )
		{
			$gallery_id		= $data[$i]['gallery_id'];
			$gallery_name	= $data[$i]['gallery_name'];
			$gallery_order	= $data[$i]['gallery_order'];
			
			$size_pics = '';
			
			if ( $data[$i]['gallery_pics'] )
			{
				$pics = data(GALLERY_PIC, $gallery_id, false, 1, false);
				
				if ( $pics )
				{
					for ( $j = 0; $j < count($pics); $j++ )
					{
						$size_pics += $pics[$j]['pic_size'];
					}
					
					$size_pics = _size($size_pics, 1);
				}
			}
			else
			{
				$size_pics = size_dir($path_dir . $data[$i]['gallery_path'] . '/');
			}
			
			$template->assign_block_vars('_display._gallery_row', array(
				'NAME'	=> ( $data[$i]['gallery_pics'] ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$gallery_id") . '">' . $gallery_name . '</a>' : $gallery_name,
				'INFO'	=> sprintf($lang['sprintf_size-pic'], $size_pics, $data[$i]['gallery_pics']),
				'DESC'	=> html_entity_decode($data[$i]['gallery_desc'], ENT_QUOTES),
				
				'MOVE_UP'	=> ( $gallery_order != '10' ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=-15&amp;$url=$gallery_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $gallery_order != $max ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=+15&amp;$url=$gallery_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'OVERVIEW'	=> ( $data[$i]['gallery_pics'] ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$gallery_id") . '"><img src="' . $images['option_overview'] . '" title="' . $lang['common_overview'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_spacer'] . '" width="16" alt="" />',
				'RESYNC'	=> '<a href="' . check_sid("$file?mode=_resync&amp;$url=$gallery_id") . '" alt="" /><img src="' . $images['refresh'] . '" title="" alt="" /></a>',
				'UPLOAD'	=> '<a href="' . check_sid("$file?mode=_upload&amp;$url=$gallery_id") . '" alt="" /><img src="' . $images['option_upload'] . '" title="' . $lang['common_upload'] . '" alt="" /></a>',
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$gallery_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$gallery_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._entry_empty', array());
	}
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>