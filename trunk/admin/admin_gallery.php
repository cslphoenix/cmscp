<?php

/*
 *
 *
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
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_gallery'] )
	{
		$module['_headmenu_main']['_submenu_gallery'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_gallery';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('gallery');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_GALLERY_URL, 0);
	$data_pic	= request(POST_GALLERY_PIC_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$order		= request('order', 0);
	$path_dir	= $root_path . $settings['path_gallery'] . '/';
	$show_index	= '';
	$s_fields	= '';
	$error		= '';
	
	$auth_fields	= array('auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_upload');
	$auth_levels	= array('guest', 'user', 'trial', 'member', 'coleader', 'leader', 'upload');
	$auth_constants	= array(AUTH_GUEST, AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOAD);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_gallery'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_GALLERY, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $no_header ) ? redirect('admin/' . append_sid('admin_gallery.php', true)) : false;
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 2) )
				{
					$max = get_data_max(GALLERY, 'gallery_order', '');
					
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
						'gallery_order'		=> $max['max'] + 10,
					);
				}
				else if ( $mode == '_update' && !request('submit', 2) )
				{
					$data = data(GALLERY, $data_id, false, 1, 1);
					
					( $data['gallery_pics'] ) ? $template->assign_block_vars('_input._overview', array()) : false;
					
					$template->assign_block_vars('_input._upload', array());
					
				}
				else
				{
					$data = array(
						'gallery_name'		=> request('gallery_name', 2),
						'gallery_desc'		=> request('gallery_desc', 2),
						'gallery_comments'	=> request('gallery_comments', 0),
						'gallery_rate'		=> request('gallery_rate', 0),
						'auth_view'			=> request('auth_view', 0),
						'auth_edit'			=> request('auth_edit', 0),
						'auth_delete'		=> request('auth_delete', 0),
						'auth_rate'			=> request('auth_rate', 0),
						'auth_upload'		=> request('auth_upload', 0),
						'per_rows'			=> ( request('per_rows', 0) )		? request('per_rows', 0) : $gallery_settings['per_rows'],
						'per_cols'			=> ( request('per_cols', 0) )		? request('per_cols', 0) : $gallery_settings['per_cols'],
						'max_width'			=> ( request('max_width', 0) )		? request('max_width', 0) : $gallery_settings['max_width'],
						'max_height'		=> ( request('max_height', 0) )		? request('max_height', 0) : $gallery_settings['max_height'],
						'max_filesize'		=> ( request('max_filesize', 0) )	? request('max_filesize', 0) : $gallery_settings['max_filesize'],
						'preview_list'		=> ( request('preview_list', 0) )	? request('preview_list', 0) : $gallery_settings['preview_list'],
						'preview_widht'		=> ( request('preview_widht', 0) )	? request('preview_widht', 0) : $gallery_settings['preview_widht'],
						'preview_height'	=> ( request('preview_height', 0) )	? request('preview_height', 0) : $gallery_settings['preview_height'],
						'gallery_order'		=> request('gallery_order', 0),
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
		
				#	$cell_title = $lang['auth_gallery'][$auth_fields[$j]];
			
					$template->assign_block_vars('_input._auth', array(
						'TITLE'		=> $lang['auth_gallery'][$auth_fields[$j]],
						'INFO'		=> $auth_fields[$j],
						'SELECT'	=> $custom_auth[$j],
					));
				}
				
				$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
				$s_fields .= '<input type="hidden" name="gallery_order" value="' . $data['gallery_order'] . '" />';
				$s_fields .= '<input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $data_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['gallery'], $data['gallery_name']),
					'L_UPLOAD'			=> $lang['common_upload'],
					'L_OVERVIEW'		=> $lang['common_overview'],
					
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['gallery']),
					'L_DESC'			=> sprintf($lang['sprintf_desc'], $lang['gallery']),
					'L_AUTH'			=> sprintf($lang['sprintf_auth'], $lang['gallery']),
					'L_RATE'			=> sprintf($lang['sprintf_voting'], $lang['gallery']),
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
					
					'S_LIST_YES'		=> ( $data['preview_list'] ) ? ' checked="checked"' : '',
					'S_LIST_NO'			=> (!$data['preview_list'] ) ? ' checked="checked"' : '',
					'S_RATE_YES'		=> ( $data['gallery_rate'] ) ? ' checked="checked"' : '',
					'S_RATE_NO'			=> (!$data['gallery_rate'] ) ? ' checked="checked"' : '',
					'S_COMMENT_YES'		=> ( $data['gallery_comments'] ) ? ' checked="checked"' : '',
					'S_COMMENT_NO'		=> (!$data['gallery_comments'] ) ? ' checked="checked"' : '',
					
					'S_UPLOAD'			=> append_sid('admin_gallery.php?mode=_upload&amp;' . POST_GALLERY_URL . '=' . $data_id),
					'S_OVERVIEW'		=> append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $data_id),
					
					'S_ACTION'			=> append_sid('admin_gallery.php'),
					'S_FIELDS'			=> $s_fields,
				));
				
				if ( request('submit', 2) )
				{
					$gallery_name		= request('gallery_name', 2);
					$gallery_desc		= request('gallery_desc', 3);
					$gallery_comments	= request('gallery_comments', 0);
					$gallery_rate		= request('gallery_rate', 0);
					$auth_view			= request('auth_view', 0);
					$auth_edit			= request('auth_edit', 0);
					$auth_delete		= request('auth_delete', 0);
					$auth_rate			= request('auth_rate', 0);
					$auth_upload		= request('auth_upload', 0);
					$gallery_order		= request('gallery_order', 0);
					
					$per_rows			= ( request('per_rows', 0) )		? request('per_rows', 0) : $gallery_settings['per_rows'];
					$per_cols			= ( request('per_cols', 0) )		? request('per_cols', 0) : $gallery_settings['per_cols'];
					$max_width			= ( request('max_width', 0) )		? request('max_width', 0) : $gallery_settings['max_width'];
					$max_height			= ( request('max_height', 0) )		? request('max_height', 0) : $gallery_settings['max_height'];
					$max_filesize		= ( request('max_filesize', 0) )	? request('max_filesize', 0) : $gallery_settings['max_filesize'];
					$preview_list		= ( request('preview_list', 0) )	? request('preview_list', 0) : $gallery_settings['preview_list'];
					$preview_widht		= ( request('preview_widht', 0) )	? request('preview_widht', 0) : $gallery_settings['preview_widht'];
					$preview_height		= ( request('preview_height', 0) )	? request('preview_height', 0) : $gallery_settings['preview_height'];
					
					$error .= ( !$gallery_name ) ? ( $error ? '<br />' : '' ) . sprintf($lang['sprintf_msg_select'], sprintf($lang['sprintf_name'], $lang['gallery'])) : '';
					$error .= ( !$gallery_desc ) ? ( $error ? '<br />' : '' ) . sprintf($lang['sprintf_msg_select'], sprintf($lang['sprintf_desc'], $lang['gallery'])) : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$folder	= uniqid('gallery_');
							$path	= $path_dir . $folder;		
							
							mkdir("$path", 0755);
							
							$file = 'index.htm';
							$code = $lang['empty_site'];
							
							$create = fopen("$path/$file", "w");
							fwrite($create, $code);
							fclose($create);
							
							$sql = "INSERT INTO " . GALLERY . " (gallery_name, gallery_desc, gallery_comments, gallery_rate, gallery_path, gallery_create, auth_view, auth_edit, auth_delete, auth_rate, auth_upload, max_filesize, max_height, max_width, per_rows, per_cols, preview_widht, preview_height, gallery_order)
										VALUES ('$gallery_name', '$gallery_desc', '$gallery_comments', '$gallery_rate', '$folder', '" . time() . "', '$auth_view', '$auth_edit', '$auth_delete', '$auth_rate', '$auth_upload', '$max_filesize', '$max_height', '$max_width', '$per_rows', '$per_cols', '$preview_widht', '$preview_height', '" . ( $max['max'] + 10 ) . "')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . GALLERY . " SET
										gallery_name		= '$gallery_name',
										gallery_desc		= '$gallery_desc',
										gallery_comments	= '$gallery_comments',
										gallery_rate		= '$gallery_rate',
										auth_view			= '$auth_view',
										auth_delete			= '$auth_delete',
										auth_rate			= '$auth_rate',
										auth_upload			= '$auth_upload',
										per_rows			= '$per_rows',
										per_cols			= '$per_cols',
										max_width			= '$max_width',
										max_height			= '$max_height',
										max_filesize		= '$max_filesize',
										preview_list		= '$preview_list',
										preview_widht		= '$preview_widht',
										preview_height		= '$preview_height'
									WHERE gallery_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_gallery']
								. sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_gallery.php?mode=_update&amp;' . POST_GALLERY_URL . '=' . $data_id) . '">', '</a>');
						}
						
						log_add(LOG_ADMIN, LOG_SEK_GALLERY, $mode, $gallery_name);
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
			
			case '_overview':
			
				$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
				$template->assign_block_vars('_overview', array());
				
				if ( $order )
				{
					update(GALLERY_PIC, 'pic', $move, $data_pic);
					orders(GALLERY_PIC, $data_id);
					
					log_add(LOG_ADMIN, LOG_SEK_GALLERY, '_order_pic');
				}
				
				$data_gallery	= get_data(GALLERY, $data_id, 1);
				$max_order		= get_data_max(GALLERY_PIC, 'pic_order', '');
				$data_pics		= get_data_array(GALLERY_PIC, 'gallery_id = ' . $data_id, 'pic_order', 'ASC');
				
				$s_fields .= '<input type="hidden" name="mode" value="_overview" />';
				$s_fields .= '<input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $data_id . '" />';
				
				if ( $data_pics )
				{
					if ( $data_gallery['preview_list'] == '1' )
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
								
								'MOVE_UP'	=> ( $data_pics[$i]['pic_order'] != '10' )				? '<a href="' . append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $data_id . '&amp;order=1&amp;move=-15&amp;' . POST_GALLERY_PIC_URL . '=' . $pic_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
								'MOVE_DOWN'	=> ( $data_pics[$i]['pic_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $data_id . '&amp;order=1&amp;move=15&amp;' . POST_GALLERY_PIC_URL . '=' . $pic_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
	
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
								
								$prev	= $path_dir . $data_gallery['gallery_path'] . '/' . $data_pics[$j]['pic_preview'];
								$image	= $path_dir . $data_gallery['gallery_path'] . '/' . $data_pics[$j]['pic_filename'];
								
								list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $data_gallery['gallery_path'] . '/' . $data_pics[$j]['pic_filename']);
								
								$template->assign_block_vars('_overview._preview._gallery_row._gallery_col', array(
									'PIC_ID'	=> $data_pics[$j]['pic_id'],
									'TITLE'		=> ( $data_pics[$j]['pic_title'] ) ? $data_pics[$j]['pic_title'] : 'kein Titel',							
									'PREV'		=> $prev,
									'IMAGE'		=> $image,							
									'WIDTH'		=> $width,
									'HEIGHT'	=> $height,
									'NAME'		=> $data_pics[$j]['pic_filename'],
									'SIZE'		=> size_file($data_pics[$j]['pic_size']),
									
									'ORDER'		=> $data_pics[$i]['pic_order'],

									'MOVE_UP'	=> ( $data_pics[$i]['pic_order'] != '10' )				? '<a href="' . append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $data_id . '&amp;order=1&amp;move=-15&amp;' . POST_GALLERY_PIC_URL . '=' . $pic_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
									'MOVE_DOWN'	=> ( $data_pics[$i]['pic_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $data_id . '&amp;order=1&amp;move=15&amp;' . POST_GALLERY_PIC_URL . '=' . $pic_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',

								));
							}
						}
					}
					
					$current_page = ( !count($data_pics) ) ? 1 : ceil( count($data_pics) / $data_gallery['per_cols'] );
			
					$template->assign_vars(array(
						'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $data_gallery['per_cols'] ) + 1 ), $current_page ),
						'PAGINATION'	=> generate_pagination('admin_gallery.php?mode=_overview&' . POST_GALLERY_URL . '=' . $data_id, count($data_pics), $data_gallery['per_cols'], $start),
					));
				}
				else { $template->assign_block_vars('_overview._no_entry', array()); }
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['sprintf_update'], $lang['gallery'], $data_gallery['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['common_overview'],
					
					'L_WIDTH'		=> $lang['pic_widht'],
					'L_HEIGHT'		=> $lang['pic_height'],
					'L_SIZE'		=> $lang['pic_size'],
					
					'PICS_PER_LINE'	=> $data_gallery['per_rows'],
					'PREVIEW_WIDHT'	=> $data_gallery['preview_widht'],
					
					'S_UPDATE'		=> append_sid('admin_gallery.php?mode=_update&amp;' . POST_GALLERY_URL . '=' . $data_id),
					'S_UPLOAD'		=> append_sid('admin_gallery.php?mode=_upload&amp;' . POST_GALLERY_URL . '=' . $data_id),
					'S_ACTION'		=> append_sid('admin_gallery.php'),
					'S_FIELDS'		=> $s_fields,
				));
				
				if ( request('submit', 2) )
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
							image_gallery_delete($pic_data[$i]['pic_filename'], $pic_data[$i]['pic_preview'], $path_dir . $data['gallery_path'] . '/');
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
						. sprintf($lang['click_return_gallery_pic'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>')
						. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $data_id) . '">', '</a>');
					log_add(LOG_ADMIN, LOG_SEK_GALLERY, 'update_gallery_pic');
					message(GENERAL_MESSAGE, $message);
				}
				
				$template->pparse('body');
				
				break;
			
			case '_upload':
			
				$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
				$template->assign_block_vars('_upload', array());
				
				$data = get_data(GALLERY, $data_id, 1);
				$pics = get_data_array(GALLERY_PIC, 'gallery_id = ' . $data_id, 'upload_time', 'ASC');
				
				if ( $pics )
				{
					$template->assign_block_vars('_upload._overview', array());
				}
				
				$s_fields = '<input type="hidden" name="mode" value="_upload" /><input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $data_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['sprintf_edit'], $lang['gallery'], $data['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['common_overview'],
					
					'S_FIELDS'		=> $s_fields,
					'S_OVERVIEW'	=> append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $data_id),
					'S_INPUT'		=> append_sid('admin_gallery.php?mode=_update&amp;' . POST_GALLERY_URL . '=' . $data_id),
					'S_ACTION'		=> append_sid('admin_gallery.php'),
				));
				
				if ( request('submit', 2) )
				{
					$data = get_data(GALLERY, $data_id, 1);
					$file = request_files('ufile');
					$nums = count($file['temp']);
					
					if ( $nums )
					{
						for ( $i = 0; $i < $nums; $i++ )
						{
							$sql_pic[] = image_gallery_upload($data['gallery_path'], $file['temp'][$i], $file['name'][$i], $file['size'][$i], $file['type'][$i], $data['max_width'], $data['max_height'], $data['max_filesize'], $data['preview_widht'], $data['preview_height']);
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
							$max_row	= get_data_max(GALLERY_PIC, 'pic_order', '');
							$next_order	= $max_row['max'] + 10;
							
							$pic_ary[] = array(
								'pic_title'		=> "'" . $_POST['title'][$i] . "'",
								'pic_size'		=> "'" . $sql_pic[$i]['pic_size'] . "'",
								'gallery_id'	=> $data_id,
								'pic_filename'	=> "'" . $sql_pic[$i]['pic_filename'] . "'",
								'pic_preview'	=> "'" . $sql_pic[$i]['pic_preview'] . "'",
								'upload_user'	=> $userdata['user_id'],
								'upload_time'	=> time(),
								'pic_order'		=> $next_order,
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
						
						$message = $lang['update_gallery_upload'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
						log_add(LOG_ADMIN, LOG_SEK_GALLERY, 'update_gallery_upload');
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						message(GENERAL_MESSAGE, 'test-error');
					}
				}
				
				$template->pparse('body');
			
				break;
			
			case '_upload_save':
			
				$gallery = get_data(GALLERY, $data_id, 1);
				$gallery_image = request_files('ufile');
	
				$image_num = count($gallery_image['temp']);
			
				for ( $i = 0; $i < $image_num; $i++ )
				{
					$sql_pic[] = image_gallery_upload($data['gallery_path'], $gallery_image['temp'][$i], $gallery_image['name'][$i], $gallery_image['size'][$i], $gallery_image['type'][$i], $data['max_width'], $data['max_height'], $data['max_filesize'], $data['preview_widht'], $data['preview_height']);
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
				
				$message = $lang['update_gallery_upload'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
				log_add(LOG_ADMIN, LOG_SEK_GALLERY, 'update_gallery_upload');
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case '_default':
				
				$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
				$template->assign_block_vars('_default', array());
				
				if ( !request('submit', 2) )
				{
					$data = $gallery_settings;
				}
				else
				{
					$data = array(
						'auth_view'			=> ( request('auth_view') ) ? request('auth_view', 0) : $gallery_settings['auth_view'],
						'auth_edit'			=> ( request('auth_edit') ) ? request('auth_edit', 0) : $gallery_settings['auth_edit'],
						'auth_rate'			=> ( request('auth_rate') ) ? request('auth_rate', 0) : $gallery_settings['auth_rate'],
						'auth_delete'		=> ( request('auth_delete') ) ? request('auth_delete', 0) : $gallery_settings['auth_delete'],
						'auth_upload'		=> ( request('auth_upload') ) ? request('auth_upload', 0) : $gallery_settings['auth_upload'],
						'per_rows'			=> ( request('per_rows') ) ? request('per_rows', 0) : $gallery_settings['per_rows'],
						'per_cols'			=> ( request('per_cols') ) ? request('per_cols', 0) : $gallery_settings['per_cols'],
						'max_width'			=> ( request('max_width') ) ? request('max_width', 0) : $gallery_settings['max_width'],
						'max_height'		=> ( request('max_height') ) ? request('max_height', 0) : $gallery_settings['max_height'],
						'max_filesize'		=> ( request('max_filesize') ) ? request('max_filesize', 0) : $gallery_settings['max_filesize'],
						'preview_list'		=> ( request('preview_list') ) ? request('preview_list', 0) : $gallery_settings['preview_list'],
						'preview_widht'		=> ( request('preview_widht') ) ? request('preview_widht', 0) : $gallery_settings['preview_widht'],
						'preview_height'	=> ( request('preview_height') ) ? request('preview_height', 0) : $gallery_settings['preview_height'],
					);
				}
				
				for ( $j = 0; $j < count($auth_fields); $j++ )
				{
					$select[$j] = '<select class="selectsmall" name="' . $auth_fields[$j] . '">';
					
					for ( $k = 0; $k < count($auth_levels); $k++ )
					{
						$disabled = ( $j == '4' && ($k == '0' || $k == '6') ) ? ' disabled' : '';
						$selected = ( $data[$auth_fields[$j]] == $auth_constants[$k] ) ? ' selected="selected"' : '';
						$select[$j] .= '<option value="' . $auth_constants[$k] . '"' . $selected . $disabled . '>' . $lang['auth_gallery_' . $auth_levels[$k]] . '&nbsp;</option>';
					}
					$select[$j] .= '</select>';
					
					$title = $lang['auth_gallery'][$auth_fields[$j]];
			
					$template->assign_block_vars('_default._auth_gallery', array(
						'TITLE'		=> $title,
						'S_SELECT'	=> $select[$j],
					));
				}
				
				$s_fields = '<input type="hidden" name="mode" value="_default" />';
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_CREATE'	=> sprintf($lang['sprintf_new_create'], $lang['gallery']),
					'L_DEFAULT'	=> $lang['common_default'],
					'L_EXPLAIN'	=> $lang['default_explain'],
					
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
					
					'S_LIST_NO'			=> ( !$gallery_settings['preview_list'] )	? ' checked="checked"' : '',
					'S_LIST_YES'		=> ( $gallery_settings['preview_list'] )	? ' checked="checked"' : '',
										
					'S_FIELDS'			=> $s_fields,
					'S_CREATE'			=> append_sid('admin_gallery.php?mode=_create'),
					'S_ACTION'			=> append_sid('admin_gallery.php'),
				));
				
				if ( request('submit', 2) )
				{
					$sql = "SELECT * FROM " . GALLERY_SETTINGS;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					else
					{
						while( $row = $db->sql_fetchrow($result) )
						{
							$config_name	= $row['config_name'];
							$config_value	= $row['config_value'];
							
							$old[$config_name] = $config_value;
							$new[$config_name] = ( request($config_name) ) ? request($config_name, 0) : $old[$config_name];
							
							$sql = "UPDATE " . GALLERY_SETTINGS . " SET config_value = '" . $new[$config_name] . "' WHERE config_name = '$config_name'";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('gallery_settings');
					
					$message = $lang['update_gallery_default']
						. sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>')
						. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_gallery.php?mode=_default') . '">', '</a>');
					log_add(LOG_ADMIN, LOG_SEK_GALLERY, 'update_gallery_default');
					message(GENERAL_MESSAGE, $message);
				}
				
				$template->pparse('body');
				
				break;
				
			case '_resync':
			
				orders(GALLERY_PIC, $data_id);
					
				log_add(LOG_ADMIN, LOG_SEK_GALLERY, 'acp_pic_order');
				
				$show_index = TRUE;
				
				break;
				
			case '_order':
				
				update(GALLERY, 'gallery', $move, $data_id);
				orders(GALLERY);
				
				log_add(LOG_ADMIN, LOG_SEK_GALLERY, 'acp_gallery_order');
				
				$show_index = TRUE;
				
				break;
				
			case '_delete':
			
				$data = get_data(GALLERY, $data_id, 1);

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
					
					dir_remove($path_dir . $data['gallery_path'] . '/');
					
					$message = $lang['delete_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
					log_add(LOG_ADMIN, LOG_SEK_GALLERY, 'delete_gallery');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_gallery'], $data['gallery_name']),
						
						'S_FIELDS'	=> $s_fields,
						'S_ACTION'	=> append_sid('admin_gallery.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_gallery']);
				}
				
				$template->pparse('body');
			
				break;
		}
		
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
	$template->assign_block_vars('_display', array());
	
	$s_fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_create'], $lang['gallery']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['gallery']),
		'L_EXPLAIN'		=> $lang['gallery_explain'],
		'L_DEFAULT'		=> $lang['common_default'],
		
		'I_UPLOAD'		=> '<img src="' . $images['option_upload'] . '" title="' . $lang['common_upload'] . '" alt="" >',
		'I_OVERVIEW'	=> '<img src="' . $images['option_overview'] . '" title="' . $lang['common_overview'] . '" alt="" >',

		'S_DEFAULT'	=> append_sid('admin_gallery.php?mode=_default'),
		'S_CREATE'	=> append_sid('admin_gallery.php?mode=_create'),
		'S_ACTION'	=> append_sid('admin_gallery.php'),
		'S_FIELDS'	=> $s_fields,
	));
	
	$gallery_max	= get_data_max(GALLERY, 'gallery_order', '');
	$gallery_data	= get_data_array(GALLERY, '', 'gallery_order', 'ASC');
	
	if ( $gallery_data )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($gallery_data)); $i++ )
		{
			$template->assign_block_vars('_display._gallery_row', array(
				'NAME'		=> ( $gallery_data[$i]['gallery_pics'] ) ? '<a href="' . append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $gallery_data[$i]['gallery_id']) . '">' . $gallery_data[$i]['gallery_name'] . '</a>' : $gallery_data[$i]['gallery_name'],
				'INFO'		=> sprintf($lang['sprintf_size-pic'], size_dir($root_path . $settings['path_gallery'] . '/' . $gallery_data[$i]['gallery_path'] . '/'), $gallery_data[$i]['gallery_pics']),
				'DESC'		=> html_entity_decode($gallery_data[$i]['gallery_desc'], ENT_QUOTES),
				'OVERVIEW'	=> ( $gallery_data[$i]['gallery_pics'] ) ? '<a href="' . append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $gallery_data[$i]['gallery_id']) . '"><img src="' . $images['option_overview'] . '" title="' . $lang['common_overview'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_spacer'] . '" width="16" alt="" />',
				
				'RESYNC'	=> '<a href="' . append_sid('admin_gallery.php?mode=_resync&amp;' . POST_GALLERY_URL . '=' . $gallery_data[$i]['gallery_id']) . '"><img src="' . $images['refresh'] . '" title="" alt="" /></a>',
				
				'MOVE_UP'	=> ( $gallery_data[$i]['gallery_order'] != '10' )					? '<a href="' . append_sid('admin_gallery.php?mode=_order&amp;move=-15&amp;' . POST_GALLERY_URL . '=' . $gallery_data[$i]['gallery_id']) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $gallery_data[$i]['gallery_order'] != $gallery_max['max'] )	? '<a href="' . append_sid('admin_gallery.php?mode=_order&amp;move=15&amp;' . POST_GALLERY_URL . '=' . $gallery_data[$i]['gallery_id']) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPLOAD'	=> append_sid('admin_gallery.php?mode=_upload&amp;' . POST_GALLERY_URL . '=' . $gallery_data[$i]['gallery_id']),
				'U_UPDATE'	=> append_sid('admin_gallery.php?mode=_update&amp;' . POST_GALLERY_URL . '=' . $gallery_data[$i]['gallery_id']),
				'U_DELETE'	=> append_sid('admin_gallery.php?mode=_delete&amp;' . POST_GALLERY_URL . '=' . $gallery_data[$i]['gallery_id']),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry', array()); }
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>