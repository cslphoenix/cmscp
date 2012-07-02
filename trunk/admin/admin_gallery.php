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
	
	add_lang('gallery');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GALLERY;
	$url	= POST_GALLERY;
	$url_p	= POST_PIC;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_pic	= request($url_p, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	$order		= request('order', INT);
	
	$dir_path	= $root_path . $settings['path_gallery'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	$auth_fields	= array('auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_upload');
	$auth_levels	= array('guest', 'user', 'trial', 'member', 'coleader', 'leader', 'uploader');
	$auth_constants	= array(AUTH_GUEST, AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOADER);
	/* values for rights form upload */
	$auth_lvl_ul	= array('user', 'trial', 'member', 'coleader', 'leader');
	$auth_con_ul	= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER);
	/* values for rights form no guests */
	$auth_lvl_gn	= array('user', 'trial', 'member', 'coleader', 'leader', 'uploader');
	$auth_con_gn	= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOADER);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_gallery'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_gallery.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', '_order', 'delete')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
		case 'update':
			
				$template->assign_block_vars('input', array());
				
				$temp = array('gallery_name', 'gallery_desc', 'auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_upload', 'format', 'dimension', 'filesize', 'preview_list', 'preview', 'gallery_comments', 'gallery_rate', 'gallery_order');
				$vars = array(
					'gallery' => array(
						'tab1'	=> 'gallery',
						'gallery_name'		=> array('validate' => 'text',	'type' => 'text:25:25', 'required' => 'input_name'),
						'gallery_desc'		=> array('validate' => 'text',	'type' => 'textarea:35:900','required' => 'input_desc'),
						'auth_view'			=> array('validate' => 'int',	'type' => 'drop:auth_view'),
						'auth_edit'			=> array('validate' => 'int',	'type' => 'drop:auth_edit'),
						'auth_delete'		=> array('validate' => 'int',	'type' => 'drop:auth_delete'),
						'auth_rate'			=> array('validate' => 'int',	'type' => 'drop:auth_rate'),
						'auth_upload'		=> array('validate' => 'int',	'type' => 'drop:auth_upload'),
						'filesize'			=> array('validate' => 'int',	'type' => 'text:10:10'),
						'dimension'			=> array('validate' => 'int',	'type' => 'double:4:5'),
						'format'			=> array('validate' => 'int',	'type' => 'double:4:5'),
						'preview'			=> array('validate' => 'int',	'type' => 'double:4:5'),
						'preview_list'		=> array('validate' => 'int',	'type' => 'radio:gallery'),
						'gallery_comments'	=> array('validate' => 'int',	'type' => 'radio:yesno'),
						'gallery_rate'		=> array('validate' => 'int',	'type' => 'radio:yesno'),
						'gallery_order'		=> array('validate' => 'int',	'type' => 'drop:order'),
					)
				);
				
				/*
				debug($settings['gallery']);
				
				list($max_width, $max_height) = explode(':', $settings['gallery']['dimension']);
				list($per_rows, $per_cols) = explode(':', $settings['gallery']['dsp_format']);
				list($preview_width, $preview_height) = explode(':', $settings['gallery']['preview']);
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					
					
					
					$data = array(
						'gallery_name'		=> request('gallery_name', 2),
						'gallery_desc'		=> '',
						'gallery_comments'	=> '1',
						'gallery_rate'		=> '1',
						'auth_view'			=> $settings['gallery']['auth_view'],
						'auth_edit'			=> $settings['gallery']['auth_edit'],
						'auth_delete'		=> $settings['gallery']['auth_delete'],
						'auth_rate'			=> $settings['gallery']['auth_rate'],
						'auth_upload'		=> $settings['gallery']['auth_upload'],
						'per_rows'			=> $per_rows,
						'per_cols'			=> $per_cols,
						'max_width'			=> $max_width,
						'max_height'		=> $preview_height,
						'max_filesize'		=> $settings['gallery']['max_filesize'],
						'preview_list'		=> $settings['gallery']['preview_list'],
						'preview_widht'		=> $preview_width,
						'preview_height'	=> $preview_height,
						'gallery_order'		=> '',
					);
				}
				*/
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					$data = array(
						'gallery_name'		=> request('gallery_name', 2),
						'gallery_desc'		=> '',
						'gallery_comments'	=> '1',
						'gallery_rate'		=> '1',
						'auth_view'			=> $settings['gallery']['auth_view'],
						'auth_edit'			=> $settings['gallery']['auth_edit'],
						'auth_delete'		=> $settings['gallery']['auth_delete'],
						'auth_rate'			=> $settings['gallery']['auth_rate'],
						'auth_upload'		=> $settings['gallery']['auth_upload'],
						'dimension'			=> $settings['gallery']['dimension'],
						'format'			=> $settings['gallery']['format'],
						'filesize'			=> $settings['gallery']['filesize'],
						'preview_list'		=> $settings['gallery']['preview_list'],
						'preview'			=> $settings['gallery']['preview'],
						'gallery_order'		=> '',
					);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(GALLERY, $data_id, false, 1, true);
					
					( $data['gallery_pics'] ) ? $template->assign_block_vars('input._overview', array()) : false;
					
					$template->assign_block_vars('input._upload', array());
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
						'per_rows'			=> request('per_rows', 0) ? request('per_rows', 0) : $settings['gallery']['per_rows'],
						'per_cols'			=> request('per_cols', 0) ? request('per_cols', 0) : $settings['gallery']['per_cols'],
						'max_width'			=> request('max_width', 0) ? request('max_width', 0) : $settings['gallery']['max_width'],
						'max_height'		=> request('max_height', 0) ? request('max_height', 0) : $settings['gallery']['max_height'],
						'max_filesize'		=> request('max_filesize', 0) ? request('max_filesize', 0) : $settings['gallery']['max_filesize'],
						'preview_list'		=> ( request('preview_list', 0) < 2 ) ? request('preview_list', 0) : $settings['gallery']['preview_list'],
						'preview_widht'		=> request('preview_widht', 0) ? request('preview_widht', 0) : $settings['gallery']['preview_widht'],
						'preview_height'	=> request('preview_height', 0) ? request('preview_height', 0) : $settings['gallery']['preview_height'],
						'gallery_order'		=> request('gallery_order', 0) ? request('gallery_order', 0) : request('gallery_order_new', 0),
					);
					
					
					
					$error .= ( !$data['gallery_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['gallery_desc'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_desc'] : '';
					
					if ( !$error )
					{
						$data['gallery_order'] = $data['gallery_order'] ? $data['gallery_order'] : maxa(GALLERY, 'gallery_order', false);
						
						if ( $mode == 'create' )
						{
							$data['gallery_path'] = create_folder($dir_path, 'gallery_', true);
							
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
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
						
						log_add(LOG_ADMIN, $log, 'error', $error);
					}
				}
				
				build_output($data, $vars, 'input', false, GALLERY);
				
				for ( $j = 0; $j < count($auth_fields); $j++ )
				{
					$drop_box[$j] = '';
					$drop_box[$j] .= "<select class=\"selectsmall\" name=\"" . $auth_fields[$j] . "\" id=\"" . $auth_fields[$j] . "\">";
					$drop_box[$j] .= "<option value=\"\">" . $lang['msg_select_item'] . "</option>";
					
					if ( $auth_fields[$j] == 'auth_view' || $auth_fields[$j] == 'auth_rate' )
					{
						for ( $k = 0; $k < count($auth_levels); $k++ )
						{
							$selected = ( $data[$auth_fields[$j]] == $auth_constants[$k] ) ? ' selected="selected"' : '';
							$drop_box[$j] .= "<option value=\"" . $auth_constants[$k] . "\"$selected>" . sprintf($lang['sprintf_select_format'], $lang['auth_gallery_' . $auth_levels[$k]]) . "</option>";
						}
					}
					else if ( $auth_fields[$j] == 'auth_edit' || $auth_fields[$j] == 'auth_delete' )
					{
						for ( $l = 0; $l < count($auth_lvl_gn); $l++ )
						{
							$selected = ( $data[$auth_fields[$j]] == $auth_con_gn[$l] ) ? ' selected="selected"' : '';
							$drop_box[$j] .= "<option value=\"" . $auth_con_gn[$l] . "\"$selected>" . sprintf($lang['sprintf_select_format'], $lang['auth_gallery_' . $auth_lvl_gn[$l]]) . "</option>";
						}
					}
					else if ( $auth_fields[$j] == 'auth_upload' )
					{
						for ( $m = 0; $m < count($auth_lvl_ul); $m++ )
						{
							$selected = ( $data[$auth_fields[$j]] == $auth_con_ul[$m] ) ? ' selected="selected"' : '';
							$drop_box[$j] .= "<option value=\"" . $auth_con_ul[$m] . "\"$selected>" . sprintf($lang['sprintf_select_format'], $lang['auth_gallery_' . $auth_lvl_ul[$m]]) . "</option>";
						}
					}
					
					$drop_box[$j] .= "</select>";
					
					$template->assign_block_vars('input._auth', array(
						'TITLE'		=> $lang['auth_gallery'][$auth_fields[$j]],
						'INFO'		=> $auth_fields[$j],
						'SELECT'	=> $drop_box[$j],
					));
				}
			
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'			=> sprintf($lang["sprintf_$mode"], $lang['gallery'], $data['gallery_name']),
					'L_UPLOAD'			=> $lang['common_upload'],
					'L_OVERVIEW'		=> $lang['common_overview'],
					
					'L_NAME'			=> $lang['gallery_name'],
					'L_DESC'			=> $lang['gallery_desc'],
					'L_AUTH'			=> $lang['gallery_auth'],
					'L_RATE'			=> $lang['gallery_rating'],
					'L_COMMENT'			=> $lang['gallery_comments'],
					
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
				
				$template->pparse('body');
				
				break;
			
			case 'overview':
			
				$template->assign_block_vars('overview', array());
				
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
						$template->assign_block_vars('overview._list', array());
						
						for ( $i = $start; $i < min($data_gallery['per_cols'] + $start, count($data_pics)); $i++ )
						{
							$pic_id	= $data_pics[$i]['pic_id'];
							$prev	= $dir_path . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_preview'];
							$image	= $dir_path . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename'];
							
							list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename']);
							
							$template->assign_block_vars('overview._list._gallery_row', array(
								'PIC_ID'	=> $data_pics[$i]['pic_id'],
								'TITLE'		=> ( $data_pics[$i]['pic_title'] ) ? $data_pics[$i]['pic_title'] : 'kein Titel',							
								'PREV'		=> $prev,
								'IMAGE'		=> $image,							
								'WIDTH'		=> $width,
								'HEIGHT'	=> $height,
								'NAME'		=> $data_pics[$i]['pic_filename'],
								'SIZE'		=> size_file($data_pics[$i]['pic_size']),
								
								'ORDER'		=> $data_pics[$i]['pic_order'],
								
							#	'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							#	'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
								
								'MOVE_UP'	=> ( $data_pics[$i]['pic_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
								'MOVE_DOWN'	=> ( $data_pics[$i]['pic_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',
	
							));
						}
					}
					else
					{
						$template->assign_block_vars('overview._preview', array());
						
						for ( $i = $start; $i < min($data_gallery['per_cols'] + $start, count($data_pics)); $i += $data_gallery['per_rows'] )
						{
							if ( count($data_pics) > 0 )
							{
								$template->assign_block_vars('overview._preview._gallery_row', array());
							}
							
							for ( $j = $i; $j < ( $i + $data_gallery['per_rows'] ); $j++ )
							{
								if ( $j >= count($data_pics) )
								{
									break;
								}
								
								$pic_id	= $data_pics[$i]['pic_id'];
								$prev	= $dir_path . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_preview'];
								$image	= $dir_path . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename'];
								
								list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename']);
								
								$template->assign_block_vars('overview._preview._galleryrow._gallery_col', array(
									'PIC_ID'	=> $data_pics[$i]['pic_id'],
									'TITLE'		=> $data_pics[$i]['pic_title'] ? $data_pics[$i]['pic_title'] : 'kein Titel',							
									'PREV'		=> $prev,
									'IMAGE'		=> $image,							
									'WIDTH'		=> $width,
									'HEIGHT'	=> $height,
									'NAME'		=> $data_pics[$i]['pic_filename'],
									'SIZE'		=> size_file($data_pics[$i]['pic_size']),
									
									'ORDER'		=> $data_pics[$i]['pic_order'],

									'MOVE_UP'	=> ( $data_pics[$i]['pic_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
									'MOVE_DOWN'	=> ( $data_pics[$i]['pic_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',

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
					$template->assign_block_vars('overview._entry_empty', array());
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
				
				if ( request('submit', TXT) )
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
							gallery_delete($pic_data[$i]['pic_filename'], $pic_data[$i]['pic_preview'], $dir_path . $data['gallery_path'] . '/');
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
			
			case 'upload':
			
				$template->assign_block_vars('upload', array());
				
				$data = data(GALLERY, $data_id, false, 1, true);
				$pics = data(GALLERY_PIC, $data_id, 'pic_order ASC', 1, false);
				$next = maxa(GALLERY_PIC, 'pic_order', "gallery_id = $data_id");
				
				( $pics ) ? $template->assign_block_vars('upload._overview', array()) : false;
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['sprintf_update'], $lang['gallery'], $data['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['common_overview'],
					
					'S_INPUT'		=> check_sid("$file?mode=_update&amp;$url=$data_id"),
					'S_UPDATE'		=> check_sid("$file?mode=_update&amp;$url=$data_id"),
					'S_OVERVIEW'	=> check_sid("$file?mode=_overview&amp;$url=$data_id"),
					
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', TXT) )
				{
					$pic_file = request_files('ufile');
					
					$nums = count($pic_file['temp']);
					
					if ( $nums )
					{
						for ( $i = 0; $i < $nums; $i++ )
						{
							$sql_pic[] = gallery_upload($data['gallery_path'], $pic_file['temp'][$i], $pic_file['name'][$i], $pic_file['size'][$i], $pic_file['type'][$i], $data['max_width'], $data['max_height'], $data['max_filesize'], $data['preview_widht'], $data['preview_height']);
						}
						
					#	debug($sql_pic);
						
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
				
			case 'order':
				
				update(GALLERY, 'gallery', $move, $data_id);
				orders(GALLERY);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case 'resync':
			
				$data = data(GALLERY, $data_id, false, 1, true);
				
				if ( is_dir($dir_path . $data['gallery_path'] . '/') )
				{
					$pics_db = data(GALLERY_PIC, $data_id, false, 1, false);
					
					$path_files = scandir($dir_path . $data['gallery_path'] . '/');
						
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
					$data['gallery_path'] = create_folder($dir_path, 'gallery_', true);
					$data['gallery_pics'] = 0;
					
					$sql = sql(GALLERY, 'update', $data, 'gallery_id', $data_id);
				}
				
			#	$index = true;
				
				break;
				
			case 'delete':
			
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
					
					delete_folder($dir_path . $data['gallery_path'] . '/');
					
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
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
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
	
	$max = maxi(GALLERY, 'gallery_order', '');
	$tmp = data(GALLERY, false, 'gallery_order ASC', 1, false);
	

	if ( !$tmp )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$cnt = count($tmp);
		
		for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt); $i++ )
		{
			$id		= $tmp[$i]['gallery_id'];
			$name	= $tmp[$i]['gallery_name'];
			$desc	= $tmp[$i]['gallery_desc'];
			$count	= $tmp[$i]['gallery_pics'];
			$order	= $tmp[$i]['gallery_order'];
			
			$size_pics = '';
			
			if ( $count )
			{
				$pics = data(GALLERY_PIC, $id, false, 1, false);
				
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
				$size_pics = size_dir($dir_path . $tmp[$i]['gallery_path']);
			}
			
			$template->assign_block_vars('display.row', array(
				'NAME'	=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
				'INFO'	=> sprintf($lang['sprintf_size-pic'], $size_pics, $tmp[$i]['gallery_pics']),
				'DESC'	=> $desc,

				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

				'OVERVIEW'	=> $count ? href('a_img', $file, array('mode' => '_overview', $url => $id), 'icon_overview', 'common_overview') : img('i_icon', 'icon_overview2', 'common_overview'),
				'RESYNC'	=> $count ? href('a_img', $file, array('mode' => '_resync', $url => $id), 'icon_resync', 'common_resync') : img('i_icon', 'icon_resync2', 'common_resync'),
				
				'UPLOAD'	=> href('a_img', $file, array('mode' => '_upload', $url => $id), 'icon_upload', 'common_upload'),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
				
			
			#	'NAME'	=> "<a href=\"" . check_sid("$file?mode=_update&amp;$url=$gallery_id") . "\">$gallery_name</a>",
			#	'INFO'	=> sprintf($lang['sprintf_size-pic'], $size_pics, $tmp[$i]['gallery_pics']),
			#	'DESC'	=> $tmp[$i]['gallery_desc'],
			#	'DESC'	=> html_entity_decode($tmp[$i]['gallery_desc'], ENT_QUOTES),
				
			#	'MOVE_UP'	=> ( $gallery_order != '10' ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=-15&amp;$url=$gallery_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
			#	'MOVE_DOWN'	=> ( $gallery_order != $max ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=+15&amp;$url=$gallery_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',
				
			#	'OVERVIEW'	=> $tmp[$i]['gallery_pics'] ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$gallery_id") . '"><img src="' . $images['option_overview'] . '" title="' . $lang['common_overview'] . '" alt="" /></a>' : '<img src="' . $images['icon_spacer'] . '" width="16" alt="" />',
			#	'RESYNC'	=> '<a href="' . check_sid("$file?mode=_resync&amp;$url=$gallery_id") . '" alt="" /><img src="' . $images['icon_resync'] . '" title="" alt="" /></a>',
			#	'UPLOAD'	=> '<a href="' . check_sid("$file?mode=_upload&amp;$url=$gallery_id") . '" alt="" /><img src="' . $images['icon_upload'] . '" title="' . $lang['common_upload'] . '" alt="" /></a>',
			#	'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$gallery_id") . '" alt="" /><img src="' . $images['icon_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
			#	'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$gallery_id") . '" alt="" /><img src="' . $images['icon_cancel'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	
	$current_page = ( !$cnt ) ? 1 : ceil( $cnt / $settings['per_page_entry']['acp'] );
		
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['title']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['title']),
		'L_EXPLAIN'		=> $lang['explain'],
		
		'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page),
		'PAGE_PAGING'	=> generate_pagination("$file?", $cnt, $settings['per_page_entry']['acp'], $start ),
		
		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>