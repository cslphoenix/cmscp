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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_gallery'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_main']['_submenu_gallery'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/gallery.php');
	
	$start			= ( request('start') ) ? request('start') : 0;
	$start			= ( $start < 0 ) ? 0 : $start;
	$gallery_id		= request(POST_GALLERY_URL);
	$gallery_pic_id	= request(POST_GALLERY_PIC_URL);
	$confirm		= request('confirm');
	$mode			= request('mode');
	$move			= request('move');
	$path_gallery	= $root_path . $settings['path_gallery'] . '/';
	$show_index		= '';

	$gallery_auth_fields	= array('auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_upload');
	$gallery_auth_levels	= array('guest', 'user', 'upload', 'trial', 'member', 'coleader', 'leader');
	$gallery_auth_const		= array(AUTH_GUEST, AUTH_USER, AUTH_UPLOAD, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER);
	
	if ( !$userauth['auth_gallery'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_gallery.php', true));
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
				$template->assign_block_vars('gallery_edit', array());
				
				if ( $mode == '_create' )
				{
					$gallery = array (
						'gallery_name'			=> request('gallery_name', 'text'),
						'gallery_desc'			=> '',
						'gallery_comments'		=> '1',
						'gallery_rate'			=> '1',
						'auth_view'				=> $gallery_settings['auth_view'],
						'auth_edit'				=> $gallery_settings['auth_edit'],
						'auth_delete'			=> $gallery_settings['auth_delete'],
						'auth_rate'				=> $gallery_settings['auth_rate'],
						'auth_upload'			=> $gallery_settings['auth_upload'],
						'max_width'				=> $gallery_settings['max_width'],
						'max_height'			=> $gallery_settings['max_height'],
						'max_filesize'			=> $gallery_settings['max_filesize'],
						'pics_per_line'			=> $gallery_settings['pics_per_line'],
						'pics_per_page'			=> $gallery_settings['pics_per_page'],
						'pic_preview_widht'		=> $gallery_settings['pic_preview_widht'],
						'pic_preview_height'	=> $gallery_settings['pic_preview_height'],
					);
					$new_mode = '_create_save';
				}
				else
				{
					$gallery = get_data(GALLERY, $gallery_id, 1);
					$new_mode = '_update_save';
					
					if ( $gallery['gallery_pics'] )
					{
						$template->assign_block_vars('gallery_edit.overview', array());
					}
					$template->assign_block_vars('gallery_edit.upload', array());
				}
				
				for ( $j = 0; $j < count($gallery_auth_fields); $j++ )
				{
					$custom_auth[$j] = '<select class="selectsmall" name="' . $gallery_auth_fields[$j] . '">';
	
					for ( $k = 0; $k < count($gallery_auth_levels); $k++ )
					{
						$selected = ( $gallery[$gallery_auth_fields[$j]] == $gallery_auth_const[$k] ) ? ' selected="selected"' : '';
						$custom_auth[$j] .= '<option value="' . $gallery_auth_const[$k] . '"' . $selected . '>' . $lang['auth_gallery_' . $gallery_auth_levels[$k]] . '&nbsp;</option>';
					}
					$custom_auth[$j] .= '</select>&nbsp;';
		
					$cell_title = $lang['auth_gallery'][$gallery_auth_fields[$j]];
			
					$template->assign_block_vars('gallery_edit.gallery_auth_data', array(
						'CELL_TITLE'			=> $cell_title,
						'S_AUTH_LEVELS_SELECT'	=> $custom_auth[$j],
					));
				}
				
				$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $gallery_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_NEW_EDIT'	=> ( $mode == '_create' ) ? sprintf($lang['sprintf_add'], $lang['gallery']) : sprintf($lang['sprintf_edit'], $lang['gallery']),
					'L_OVERVIEW'	=> sprintf($lang['sprintf_overview'], $lang['gallery']),
					'L_UPLOAD'		=> sprintf($lang['sprintf_upload'], $lang['gallery']),
					
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['gallery']),
					'L_AUTH'		=> $lang['gallery_auth'],
					'L_DESC'		=> sprintf($lang['sprintf_desc'], $lang['gallery']),
					'L_COMMENT'		=> $lang['gallery_comment'],
					'L_RATE'		=> $lang['gallery_rate'],
					
					'L_MAX_WIDTH'			=> $lang['gallery_max_width'],
					'L_MAX_HEIGHT'			=> $lang['gallery_max_height'],
					'L_MAX_FILESIZE'		=> $lang['gallery_max_filesize'],
					'L_PICS_PER_LINE'		=> $lang['pics_per_line'],
					'L_PICS_PER_PAGE'		=> $lang['pics_per_page'],
					'L_PIC_PREVIEW_WIDHT'	=> $lang['pic_preview_widht'],
					'L_PIC_PREVIEW_HEIGHT'	=> $lang['pic_preview_height'],
					
					'L_NO'					=> $lang['common_no'],
					'L_YES'					=> $lang['common_yes'],
					'L_RESET'				=> $lang['common_reset'],
					'L_SUBMIT'				=> $lang['common_submit'],
					
					'NAME'			=> $gallery['gallery_name'],
					'DESC'			=> $gallery['gallery_desc'],
					'WIDTH'			=> $gallery['max_width'],
					'HEIGHT'		=> $gallery['max_height'],
					'FILESIZE'		=> $gallery['max_filesize'],
					'PICS_PER_LINE'			=> $gallery['pics_per_line'],
					'PICS_PER_PAGE'			=> $gallery['pics_per_page'],
					'PIC_PREVIEW_WIDHT'		=> $gallery['pic_preview_widht'],
					'PIC_PREVIEW_HEIGHT'	=> $gallery['pic_preview_height'],
					
					'S_COMMENT_YES'	=> ( $gallery['gallery_comments'] ) ? ' checked="checked"' : '',
					'S_COMMENT_NO'	=> ( !$gallery['gallery_comments'] ) ? ' checked="checked"' : '',
					
					'S_RATE_YES'	=> ( $gallery['gallery_rate'] ) ? ' checked="checked"' : '',
					'S_RATE_NO'		=> ( !$gallery['gallery_rate'] ) ? ' checked="checked"' : '',
										
					'S_FIELDS'		=> $s_fields,
					'S_OVERVIEW'	=> append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
					'S_UPLOAD'		=> append_sid('admin_gallery.php?mode=_upload&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
					'S_ACTION'		=> append_sid('admin_gallery.php'),
				));
			
				$template->pparse('body');
				
				break;
			
			case '_create_save':
			
				$gallery_name		= request('gallery_name', 'text');
				$gallery_desc		= request('gallery_desc', 'text_clean');
				$gallery_comments	= request('gallery_comments', 'num');
				$gallery_rate		= request('gallery_rate', 'num');
				$auth_view			= request('auth_view', 'num');
				$auth_edit			= request('auth_edit', 'num');
				$auth_delete		= request('auth_delete', 'num');
				$auth_rate			= request('auth_rate', 'num');
				$auth_upload		= request('auth_upload', 'num');
				
				$max_width			= ( request('max_widht', 'num') ) ? request('max_widht', 'num') : $gallery_settings['max_width'];
				$max_height			= ( request('max_height', 'num') ) ? request('max_height', 'num') : $gallery_settings['max_height'];
				$max_filesize		= ( request('max_filesize', 'num') ) ? request('max_filesize', 'num') : $gallery_settings['max_filesize'];
				$pics_per_line		= ( request('pics_per_line', 'num') ) ? request('pics_per_line', 'num') : $gallery_settings['pics_per_line'];
				$pics_per_page		= ( request('pics_per_page', 'num') ) ? request('pics_per_page', 'num') : $gallery_settings['pics_per_page'];
				$pic_preview_widht	= ( request('pic_preview_widht', 'num') ) ? request('pic_preview_widht', 'num') : $gallery_settings['pic_preview_widht'];
				$pic_preview_height	= ( request('pic_preview_height', 'num') ) ? request('pic_preview_height', 'num') : $gallery_settings['pic_preview_height'];
				
				$error_msg = '';
				$error_msg .= ( !$gallery_name ) ? $lang['msg_select_title'] : '';
				$error_msg .= ( !$gallery_desc ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_description'] : '';
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}
				
				$max_row	= get_data_max(GALLERY, 'gallery_order', '');
				$max_order	= $max_row['max'];
				$next_order = $max_order + 10;
				
				$folder	= uniqid('gallery_');
				$path	= $root_path . $settings['path_gallery'] . '/' . $folder;		
				
				mkdir("$path", 0755);
				
				$file = 'index.htm';
				$code = $lang['empty_site'];
				
				$create = fopen("$path/$file", "w");
				fwrite($create, $code);
				fclose($create);
				
				$sql = 'INSERT INTO ' . GALLERY . " (gallery_name, gallery_desc, gallery_comments, gallery_rate, gallery_path, gallery_create, auth_view, auth_edit, auth_delete, auth_rate, auth_upload, max_filesize, max_height, max_width, pics_per_line, pics_per_page, pic_preview_widht, pic_preview_height, gallery_order)
					VALUES ('" . str_replace("\'", "''", $gallery_name) . "', '$gallery_desc', '$gallery_comments', '$gallery_rate', '$folder', '" . time() . "', '$auth_view', '$auth_edit', '$auth_delete', '$auth_rate', '$auth_upload', '$max_filesize', '$max_height', '$max_width', '$pics_per_line', '$pics_per_page', '$pic_preview_widht', '$pic_preview_height', '$next_order')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['create_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'create_gallery');
				message(GENERAL_MESSAGE, $message);
	
				break;
			
			case '_update_save':
			
				$gallery_name		= request('gallery_name', 'text');
				$gallery_desc		= request('gallery_desc', 'text_clean');
				$gallery_comments	= request('gallery_comments', 'num');
				$gallery_rate		= request('gallery_rate', 'num');
				$auth_view			= request('auth_view', 'num');
				$auth_edit			= request('auth_edit', 'num');
				$auth_delete		= request('auth_delete', 'num');
				$auth_rate			= request('auth_rate', 'num');
				$auth_upload		= request('auth_upload', 'num');
				
				$max_width			= ( request('max_widht', 'num') ) ? request('max_widht', 'num') : $gallery_settings['max_width'];
				$max_height			= ( request('max_height', 'num') ) ? request('max_height', 'num') : $gallery_settings['max_height'];
				$max_filesize		= ( request('max_filesize', 'num') ) ? request('max_filesize', 'num') : $gallery_settings['max_filesize'];
				$pics_per_line		= ( request('pics_per_line', 'num') ) ? request('pics_per_line', 'num') : $gallery_settings['pics_per_line'];
				$pics_per_page		= ( request('pics_per_page', 'num') ) ? request('pics_per_page', 'num') : $gallery_settings['pics_per_page'];
				$pic_preview_widht	= ( request('pic_preview_widht', 'num') ) ? request('pic_preview_widht', 'num') : $gallery_settings['pic_preview_widht'];
				$pic_preview_height	= ( request('pic_preview_height', 'num') ) ? request('pic_preview_height', 'num') : $gallery_settings['pic_preview_height'];
				
				$error_msg = '';
				$error_msg .= ( !$gallery_name ) ? $lang['msg_select_title'] : '';
				$error_msg .= ( !$gallery_desc ) ? '<br>' . $lang['msg_select_description'] : '';
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}
				
				$sql = "UPDATE " . GALLERY . " SET
							gallery_name		= '" . str_replace("\'", "''", $gallery_name) . "',
							gallery_desc		= '" . $gallery_desc . "',
							gallery_comments	= $gallery_comments,
							gallery_rate		= $gallery_rate,
							auth_view			= $auth_view,
							auth_delete			= $auth_delete,
							auth_rate			= $auth_rate,
							auth_upload			= $auth_upload,
							max_width			= $max_width,
							max_height			= $max_height,
							max_filesize		= $max_filesize,
							pics_per_line		= $pics_per_line,
							pics_per_page		= $pics_per_page,
							pic_preview_widht	= $pic_preview_widht,
							pic_preview_height	= $pic_preview_height
						WHERE gallery_id = $gallery_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['update_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'update_gallery');
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case '_upload':
			
				$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
				$template->assign_block_vars('gallery_upload', array());
				
				$gallery = get_data('gallery', $gallery_id, 0);
				$gallery_data	= get_data_array(GALLERY_PIC, 'gallery_id = ' . $gallery_id, 'upload_time', 'ASC');
				
				if ( $gallery_data )
				{
					$template->assign_block_vars('gallery_upload.overview', array());
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="_upload_save" /><input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $gallery_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_EDIT'		=> sprintf($lang['sprintf_edit'], $lang['gallery']),
					'L_OVERVIEW'	=> sprintf($lang['sprintf_overview'], $lang['gallery']),
					'L_UPLOAD'		=> sprintf($lang['sprintf_upload'], $lang['gallery']),
					'L_REQUIRED'			=> $lang['required'],
					
					'L_UPLOAD'				=> $lang['common_upload'],
					
					'L_NO'					=> $lang['common_no'],
					'L_YES'					=> $lang['common_yes'],
					'L_RESET'				=> $lang['common_reset'],
					'L_SUBMIT'				=> $lang['common_submit'],
										
					'S_FIELDS'		=> $s_hidden_fields,
					'S_EDIT'		=> append_sid('admin_gallery.php?mode=_update&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
					'S_OVERVIEW'	=> append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
					'S_ACTION'		=> append_sid('admin_gallery.php'),
				));
			
				$template->pparse('body');
				
				break;
			
			case '_upload_save':
			
				$gallery = get_data('gallery', $gallery_id, 0);
	
				$image_num = count($_FILES['ufile']['name']);
			
				for ( $i = 0; $i < $image_num; $i++ )
				{
					$pic_tempname	= ( !empty($_FILES['ufile']['tmp_name'][$i]) )	? $_FILES['ufile']['tmp_name'][$i] : '';
					$pic_name		= ( !empty($_FILES['ufile']['name'][$i]) )		? $_FILES['ufile']['name'][$i] : '';
					$pic_filetype	= ( !empty($_FILES['ufile']['type'][$i]) )		? $_FILES['ufile']['type'][$i] : '';
					$pic_size		= ( !empty($_FILES['ufile']['size'][$i]) )		? $_FILES['ufile']['size'][$i] : 0;
					$sql_pic[]		= image_gallery_upload($gallery['gallery_path'], $pic_tempname, $pic_name, $pic_size, $pic_filetype, $gallery['max_width'], $gallery['max_height'], $gallery['max_filesize'], $gallery['pic_preview_widht'], $gallery['pic_preview_height']);
				}
				
				$sql = "UPDATE " . GALLERY . " SET gallery_pics = gallery_pics + $image_num WHERE gallery_id = $gallery_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$pic_ary = array();
				
				for ( $i = 0; $i < $image_num; $i++ )
				{
					$pic_ary[] = array(
						'pic_title'		=> "'" . $_POST['title'][$i] . "'",
						'pic_size'		=> "'" . $sql_pic[$i]['pic_size'] . "'",
						'gallery_id'	=> $gallery_id,
						'pic_filename'	=> "'" . $sql_pic[$i]['pic_filename'] . "'",
						'pic_preview'	=> "'" . $sql_pic[$i]['pic_preview'] . "'",
						'upload_user'	=> $userdata['user_id'],
						'upload_time'	=> time(),
					);
				}
				
				/*
				foreach ( $sql_pic as $pic_id => $pic )
				{
					foreach ( $_POST['title'] as $title_id => $title_value )
					{
						if ( $pic_id == $title_id )
						{
							$pic_ary[] = array(
								'pic_title'		=> "'" . $title_value . "'",
								'pic_size'		=> "'" . $pic['pic_size'] . "'",
								'gallery_id'	=> $gallery_id,
								'pic_filename'	=> "'" . $pic['pic_filename'] . "'",
								'pic_preview'	=> "'" . $pic['pic_preview'] . "'",
								'upload_user'	=> $userdata['user_id'],
								'upload_time'	=> time(),
							);
						}		
					}
				}
				*/
				
				$db_ary = array();
				
				foreach ( $pic_ary as $id => $_pic_ary )
				{
					$values = array();
					foreach ( $_pic_ary as $key => $var )
					{
						$values[] = $var;
					}
					$db_ary[] = '(' . implode(', ', $values) . ')';
				}
				
				$sql = 'INSERT INTO ' . GALLERY_PIC . ' (' . implode(', ', array_keys($pic_ary[0])) . ') VALUES ' . implode(', ', $db_ary);
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['update_gallery_upload'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'update_gallery_upload');
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case '_overview':
			
				$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
				$template->assign_block_vars('gallery_overview', array());
				
				$gallery		= get_data('gallery', $gallery_id, 0);
				$gallery_data	= get_data_array(GALLERY_PIC, 'gallery_id = ' . $gallery_id, 'upload_time', 'ASC');
				$s_hidden_fields = '<input type="hidden" name="mode" value="_update_overview" /><input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $gallery_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_EDIT'		=> sprintf($lang['sprintf_edit'], $lang['gallery']),
					'L_OVERVIEW'	=> sprintf($lang['sprintf_overview'], $lang['gallery']),
					'L_UPLOAD'		=> sprintf($lang['sprintf_upload'], $lang['gallery']),
					'L_REQUIRED'			=> $lang['required'],
					
					'L_WIDTH'				=> $lang['pic_widht'],
					'L_HEIGHT'				=> $lang['pic_height'],
					'L_SIZE'				=> $lang['pic_size'],
					
					'L_MARK_ALL'			=> $lang['mark_all'],
					'L_MARK_DEALL'			=> $lang['mark_deall'],
					'L_DELETE'				=> $lang['common_delete'],
	
					'PICS_PER_LINE'			=> $gallery['pics_per_line'],
					
					'S_FIELDS'		=> $s_hidden_fields,
					'S_EDIT'		=> append_sid('admin_gallery.php?mode=_update&' . POST_GALLERY_URL . '=' .$gallery_id),
					'S_UPLOAD'		=> append_sid('admin_gallery.php?mode=_upload&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
					'S_ACTION'		=> append_sid('admin_gallery.php'),
				));
				
				if ( !$gallery_data )
				{
					$template->assign_block_vars('gallery_overview.no_entry', array());
					$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
				}
				else
				{
					for ( $i = $start; $i < min($gallery['pics_per_page'] + $start, count($gallery_data)); $i += $gallery['pics_per_line'] )
	#				for ( $i = 0; $i < count($gallery_data); $i += '3')
					{
						if ( count($gallery_data) > 0 )
						{
							$template->assign_block_vars('gallery_overview.gallery_row', array());
						}
						
						for ( $j = $i; $j < ( $i + $gallery['pics_per_line'] ); $j++ )
						{
							if ( $j >= count($gallery_data) )
							{
								break;
							}
							
							$class = ($j % 2) ? 'row_class1' : 'row_class2';
							
							$prev	= $root_path . $settings['path_gallery'] . '/' . $gallery['gallery_path'] . '/' . $gallery_data[$j]['pic_preview'];
							$image	= $root_path . $settings['path_gallery'] . '/' . $gallery['gallery_path'] . '/' . $gallery_data[$j]['pic_filename'];
							
							list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $gallery['gallery_path'] . '/' . $gallery_data[$j]['pic_filename']);
							
							$template->assign_block_vars('gallery_overview.gallery_row.gallery_col', array(
								'CLASS' 	=> $class,
								
								'PIC_ID'	=> $gallery_data[$j]['pic_id'],
								'TITLE'		=> ( $gallery_data[$j]['pic_title'] ) ? $gallery_data[$j]['pic_title'] : 'kein Titel',							
								'PREV'		=> $prev,
								'IMAGE'		=> $image,							
								'WIDTH'		=> $width,
								'HEIGHT'	=> $height,
								'SIZE'		=> size_file($gallery_data[$j]['pic_size']),
							));
						}
					}
					
					$current_page = ( !count($gallery_data) ) ? 1 : ceil( count($gallery_data) / $gallery['pics_per_page'] );
			
					$template->assign_vars(array(
						'L_GOTO_PAGE'	=> $lang['Goto_page'],
						'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $gallery['pics_per_page'] ) + 1 ), $current_page ),
						'PAGINATION'	=> generate_pagination('admin_gallery.php?mode=_overview&' . POST_GALLERY_URL . '=' . $gallery_id, count($gallery_data), $gallery['pics_per_page'], $start),
					));
				}
			
				$template->pparse('body');
				
				break;
				
			case '_update_overview':
			
				$pics = request('pics', 'only');
				
				if ( !$pics )
				{
					message(GENERAL_ERROR, $lang['msg_select_pics']);
				}
							
				$gallery = get_data('gallery', $gallery_id, 0);
				$image_num = count($pics);
				
				$sql_in = implode(', ', $pics);
				
				$sql = "SELECT * FROM " . GALLERY_PIC . " WHERE pic_id IN ($sql_in) AND gallery_id = $gallery_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$pic_data = $db->sql_fetchrowset($result);
				
				for ( $i = 0; $i < $image_num; $i++ )
				{
					image_gallery_delete($pic_data[$i]['pic_filename'], $pic_data[$i]['pic_preview'], $root_path . $settings['path_gallery'] . '/' . $gallery['gallery_path'] . '/');
				}
				
				$sql = "DELETE FROM " . GALLERY_PIC . " WHERE pic_id IN ($sql_in) AND gallery_id = $gallery_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "UPDATE " . GALLERY . " SET gallery_pics = gallery_pics - $image_num WHERE gallery_id = $gallery_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['delete_gallery_pic'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'delete_gallery_pic');
				message(GENERAL_MESSAGE, $message);
			
				break;
				
			case '_default':
				
				$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
				$template->assign_block_vars('gallery_default', array());
				
				for ( $j = 0; $j < count($gallery_auth_fields); $j++ )
				{
					$custom_auth[$j] = '<select class="selectsmall" name="' . $gallery_auth_fields[$j] . '">';
	
					for ( $k = 0; $k < count($gallery_auth_levels); $k++ )
					{
						$selected = ( $gallery_settings[$gallery_auth_fields[$j]] == $gallery_auth_const[$k] ) ? ' selected="selected"' : '';
						$custom_auth[$j] .= '<option value="' . $gallery_auth_const[$k] . '"' . $selected . '>' . $lang['auth_gallery_' . $gallery_auth_levels[$k]] . '</option>';
					}
					$custom_auth[$j] .= '</select>&nbsp;';
					
					$cell_title = $lang['auth_gallery'][$gallery_auth_fields[$j]];
			
					$template->assign_block_vars('gallery_default.gallery_auth_data', array(
						'CELL_TITLE'			=> $cell_title,
						'S_AUTH_LEVELS_SELECT'	=> $custom_auth[$j],
					));
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="_update_default" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_DEFAULT'		=> $lang['gallery_default'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_AUTH'		=> $lang['gallery_auth'],
					'L_MAX_WIDTH'			=> $lang['gallery_max_width'],
					'L_MAX_HEIGHT'			=> $lang['gallery_max_height'],
					'L_MAX_FILESIZE'		=> $lang['gallery_max_filesize'],
					'L_AUTH_VIEW'			=> $lang['gallery_auth_view'],
					'L_AUTH_EDIT'			=> $lang['gallery_auth_edit'],
					'L_AUTH_DELETE'			=> $lang['gallery_auth_delete'],
					'L_AUTH_RATE'			=> $lang['gallery_auth_rate'],
					'L_AUTH_UPLOAD'			=> $lang['gallery_auth_upload'],
					
					'L_PIC_PREVIEW_WIDHT'	=> $lang['pic_preview_widht'],
					'L_PIC_PREVIEW_HEIGHT'	=> $lang['pic_preview_height'],
					
					'L_PICS_PER_LINE'		=> $lang['pics_per_line'],
					'L_PICS_PER_PAGE'		=> $lang['pics_per_page'],
					
					
					'L_RESET'				=> $lang['common_reset'],
					'L_SUBMIT'				=> $lang['common_submit'],
					
					'WIDTH'			=> $gallery_settings['max_width'],
					'HEIGHT'		=> $gallery_settings['max_height'],
					'FILESIZE'		=> $gallery_settings['max_filesize'],
					'AUTH_VIEW'		=> $gallery_settings['auth_view'],
					'AUTH_EDIT'		=> $gallery_settings['auth_edit'],
					'AUTH_DELETE'	=> $gallery_settings['auth_delete'],
					'AUTH_RATE'		=> $gallery_settings['auth_rate'],
					'AUTH_UPLOAD'	=> $gallery_settings['auth_upload'],
					
					'PIC_PREVIEW_WIDHT'		=> $gallery_settings['pic_preview_widht'],
					'PIC_PREVIEW_HEIGHT'	=> $gallery_settings['pic_preview_height'],
					
					'PICS_PER_LINE'			=> $gallery_settings['pics_per_line'],
					'PICS_PER_PAGE'			=> $gallery_settings['pics_per_page'],
										
					'S_FIELDS'		=> $s_hidden_fields,
					'S_ACTION'		=> append_sid('admin_gallery.php'),
				));
			
				$template->pparse('body');
				
				break;
				
			case '_update_default':
			
				$sql = 'SELECT * FROM ' . GALLERY_SETTINGS;
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
						
						$default_config[$config_name]	= str_replace("'", "\'", $config_value);
						$new[$config_name]				= $HTTP_POST_VARS[$config_name];
						
						$sql = "UPDATE " . GALLERY_SETTINGS . " SET config_value = '" . str_replace("\'", "''", $new[$config_name]) . "' WHERE config_name = '$config_name'";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
						}
					}
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('gallery_settings');
				
				$message = $lang['update_gallery_default'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'delete_gallery_pic');
				message(GENERAL_MESSAGE, $message);
			
				break;
			
			case '_delete':
			
				if ( $gallery_id && $confirm )
				{	
					$gallery = get_data(GALLERY, $gallery_id, 1);
					
					dir_remove($root_path . $settings['path_gallery'] . '/' . $gallery['gallery_path'] . '/');
				
					$sql = 'DELETE FROM ' . GALLERY . ' WHERE gallery_id = ' . $gallery_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . GALLERY_PIC . ' WHERE gallery_id = ' . $gallery_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['delete_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'delete_gallery');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $gallery_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $gallery_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_gallery'],
						'L_NO'				=> $lang['common_no'],
						'L_YES'				=> $lang['common_yes'],
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
	
		default:
				
				message(GENERAL_ERROR, $lang['no_mode']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
	$template->assign_block_vars('display', array());
	
	$gallery_max = get_data_max(GALLERY, 'gallery_order', '');
	$gallery_data = get_data_array(GALLERY, '', 'gallery_order', 'ASC');
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
		'L_DEFAULT'		=> $lang['gallery_default'],
		'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['gallery']),
		'L_EXPLAIN'		=> $lang['gallery_explain'],
		
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['gallery']),
		
		'L_UPLOAD'		=> $lang['common_upload'],
		
		'S_FIELDS'		=> $s_fields,
		'S_DEFAULT'		=> append_sid('admin_gallery.php?mode=_default'),
		'S_CREATE'		=> append_sid('admin_gallery.php?mode=_create'),
		'S_ACTION'		=> append_sid('admin_gallery.php'),
	));
	
	if ( $gallery_data )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($gallery_data)); $i++ )
		{
			$gallery_id	= $gallery_data[$i]['gallery_id'];

			$template->assign_block_vars('display.gallery_row', array(
				'CLASS' 	=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				
				'NAME'		=> $gallery_data[$i]['gallery_name'],
				'INFO'		=> sprintf($lang['gallery_sprintf_size-pic'], size_dir($root_path . $settings['path_gallery'] . '/' . $gallery_data[$i]['gallery_path'] . '/'), $gallery_data[$i]['gallery_pics']),
				'DESC'		=> html_entity_decode($gallery_data[$i]['gallery_desc'], ENT_QUOTES),
				'OVERVIEW'	=> ( $gallery_data[$i]['gallery_pics'] ) ? '<a href="' . append_sid('admin_gallery.php?mode=_overview&amp;' . POST_GALLERY_URL . '=' . $gallery_id) . '">' . $lang['common_overview'] . '</a>' : $lang['common_overview'],
				
				'MOVE_UP'	=> ( $gallery_data[$i]['gallery_order'] != '10' )					? '<a href="' . append_sid('admin_gallery.php?mode=_order&amp;move=-15&amp;' . POST_GALLERY_URL . '=' . $gallery_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'	=> ( $gallery_data[$i]['gallery_order'] != $gallery_max['max'] )	? '<a href="' . append_sid('admin_gallery.php?mode=_order&amp;move=15&amp;' . POST_GALLERY_URL . '=' . $gallery_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_UPLOAD'	=> append_sid('admin_gallery.php?mode=_upload&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
				'U_UPDATE'	=> append_sid('admin_gallery.php?mode=_update&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
				'U_DELETE'	=> append_sid('admin_gallery.php?mode=_delete&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>