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
		$module['main']['gallery'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', 1);
	
	$root_path	= './../';
	$cancel		= ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/upload.php');
	include($root_path . 'includes/acp/selects.php');
	include($root_path . 'includes/acp/functions.php');
	
	$start		= ( request('start') ) ? request('start', 'num') : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$gallery_id	= request(POST_GALLERY_URL);
	$confirm	= request('confirm');
	$mode		= request('mode');	

	$gallery_auth_fields	= array('auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_upload');
	$gallery_auth_levels	= array('USER', 'TRIAL', 'MEMBER', 'COLEADER', 'LEADER', 'UPLOAD');
	$gallery_auth_const		= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOAD);
	
	if ( !$userauth['auth_gallery'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_gallery.php', true));
	}
	
	switch ( $mode )
	{
		case '_add':
		case '_edit':
		
			$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
			$template->assign_block_vars('gallery_edit', array());
			
			if ( $mode == '_edit' )
			{
				$gallery	= get_data('gallery', $gallery_id, 0);
				$new_mode	= '_update';
			}
			else
			{
				$gallery = array (
					'gallery_name'		=> request('gallery_name', 'text'),
					'gallery_desc'		=> '',
					'gallery_comments'	=> '1',
					'gallery_rate'		=> '1',
					'auth_view'			=> '0',
					'auth_edit'			=> '1',
					'auth_delete'		=> '2',
					'auth_rate'			=> '3',
					'auth_upload'		=> '5',						
				);

				$new_mode = '_create';
			}
			
			
			for ( $j = 0; $j < count($gallery_auth_fields); $j++ )
			{
				$custom_auth[$j] = '<select class="post" name="' . $gallery_auth_fields[$j] . '">';

				for ( $k = 0; $k < count($gallery_auth_levels); $k++ )
				{
					$selected = ( $gallery[$gallery_auth_fields[$j]] == $gallery_auth_const[$k] ) ? ' selected="selected"' : '';
					$custom_auth[$j] .= '<option value="' . $gallery_auth_const[$k] . '"' . $selected . '>' . $lang['Gallery_' . $gallery_auth_levels[$k]] . '</option>';
				}
				$custom_auth[$j] .= '</select>&nbsp;';
	
				$cell_title = $lang['auth_gallery'][$gallery_auth_fields[$j]];
		
				$template->assign_block_vars('gallery_edit.gallery_auth_data', array(
					'CELL_TITLE'			=> $cell_title,
					'S_AUTH_LEVELS_SELECT'	=> $custom_auth[$j],
				));
			}
			
			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $gallery_id . '" />';
			
			$template->assign_vars(array(
				'L_GALLERY_HEAD'		=> $lang['gallery_head'],
				'L_GALLERY_NEW_EDIT'	=> ( $mode == '_add' ) ? $lang['gallery_add'] : $lang['gallery_edit'],
				'L_REQUIRED'			=> $lang['required'],
				
				'L_GALLERY_NAME'		=> $lang['gallery_name'],
				'L_GALLERY_AUTH'		=> $lang['gallery_auth'],
				'L_GALLERY_DESC'		=> $lang['gallery_desc'],
				'L_GALLERY_COMMENT'		=> $lang['gallery_comment'],
				'L_GALLERY_RATE'		=> $lang['gallery_rate'],
				
				'L_NO'					=> $lang['common_no'],
				'L_YES'					=> $lang['common_yes'],
				'L_RESET'				=> $lang['common_reset'],
				'L_SUBMIT'				=> $lang['common_submit'],
				
				'GALLERY_NAME'			=> $gallery['gallery_name'],
				'GALLERY_DESC'			=> $gallery['gallery_desc'],
				
				'S_CHECKED_COMMENT_YES'	=> ( $gallery['gallery_comments'] ) ? ' checked="checked"' : '',
				'S_CHECKED_COMMENT_NO'	=> ( !$gallery['gallery_comments'] ) ? ' checked="checked"' : '',
				
				'S_CHECKED_RATE_YES'	=> ( $gallery['gallery_rate'] ) ? ' checked="checked"' : '',
				'S_CHECKED_RATE_NO'		=> ( !$gallery['gallery_rate'] ) ? ' checked="checked"' : '',
									
				'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
				'S_GALLERY_ACTION'		=> append_sid('admin_gallery.php'),
			));
		
			$template->pparse('body');
			
			break;
		
		case '_create':
		
			$gallery_name		= request('gallery_name', 'text');
			$gallery_desc		= request('gallery_desc', 'textfeld_clean');
			$gallery_comments	= request('gallery_comments', 'num');
			$gallery_rate		= request('gallery_rate', 'num');
			$auth_view			= request('auth_view', 'num');
			$auth_edit			= request('auth_edit', 'num');
			$auth_delete		= request('auth_delete', 'num');
			$auth_rate			= request('auth_rate', 'num');
			$auth_upload		= request('auth_upload', 'num');
			
			$error_msg = '';
			$error_msg .= ( !$gallery_name ) ? $lang['msg_select_title'] : '';
			$error_msg .= ( !$gallery_desc ) ? '<br>' . $lang['msg_select_description'] : '';
			
			if ( $error_msg )
			{
				message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
			}
			
			$sql = 'SELECT MAX(gallery_order) AS max_order FROM ' . GALLERY;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$next_order = $max_order + 10;
			
			$path = uniqid('gallery_');
			
			mkdir($root_path . $settings['path_gallery'] . '/' . $path, '0755');
			
			$file = 'index.htm';
			$code = $lang['empty_site'];
			
			$create = fopen($root_path . $settings['path_gallery'] . '/' . $path . '/' .$file, "w");
			fwrite($create, $code);
			fclose($create); 
			
			$sql = 'INSERT INTO ' . GALLERY . " (gallery_name, gallery_desc, gallery_comments, gallery_rate, gallery_path, gallery_create, auth_view, auth_edit, auth_delete, auth_rate, auth_upload, gallery_order)
				VALUES ('" . str_replace("\'", "''", $gallery_name) . "', '" . $gallery_desc . "', '$gallery_comments', '$gallery_rate', '$path', '" . time() . "', '$auth_view', '$auth_edit', '$auth_delete', '$auth_rate', '$auth_upload', $next_order)";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'acp_gallery_add', $gallery_name);

			$message = $lang['create_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);

			break;
		
		case '_update':
		
			$gallery_name		= request('gallery_name', 'text');
			$gallery_desc		= request('gallery_desc', 'textfeld_clean');
			$gallery_comments	= request('gallery_comments', 'num');
			$gallery_rate		= request('gallery_rate', 'num');
			$auth_view			= request('auth_view', 'num');
			$auth_edit			= request('auth_edit', 'num');
			$auth_delete		= request('auth_delete', 'num');
			$auth_rate			= request('auth_rate', 'num');
			$auth_upload		= request('auth_upload', 'num');
			
			$error_msg = '';
			$error_msg .= ( !$gallery_name ) ? $lang['msg_select_title'] : '';
			$error_msg .= ( !$gallery_desc ) ? '<br>' . $lang['msg_select_description'] : '';
			
			if ( $error_msg )
			{
				message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
			}
			
			$sql = "UPDATE " . GALLERY . " SET
						gallery_name			= '" . str_replace("\'", "''", $gallery_name) . "',
						gallery_desc			= '" . $gallery_desc . "'
					WHERE gallery_id = " . $gallery_id;
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'acp_gallery_edit');
			
			$message = $lang['update_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case '_upload':
		
			$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
			$template->assign_block_vars('gallery_upload', array());
			
			$gallery = get_data('gallery', $gallery_id, 0);
			$s_hidden_fields = '<input type="hidden" name="mode" value="_save" /><input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $gallery_id . '" />';
			
			$template->assign_vars(array(
				'L_GALLERY_HEAD'		=> $lang['gallery_head'],
				'L_GALLERY_NEW_EDIT'	=> ( $mode == '_add' ) ? $lang['gallery_add'] : $lang['gallery_edit'],
				'L_REQUIRED'			=> $lang['required'],
				
				'L_GALLERY_NAME'		=> $lang['gallery_name'],
				'L_GALLERY_AUTH'		=> $lang['gallery_auth'],
				'L_GALLERY_DESC'		=> $lang['gallery_desc'],
				'L_GALLERY_COMMENT'		=> $lang['gallery_comment'],
				'L_GALLERY_RATE'		=> $lang['gallery_rate'],
				
				'L_NO'					=> $lang['common_no'],
				'L_YES'					=> $lang['common_yes'],
				'L_RESET'				=> $lang['common_reset'],
				'L_SUBMIT'				=> $lang['common_submit'],
				
				'GALLERY_NAME'			=> $gallery['gallery_name'],
				'GALLERY_DESC'			=> $gallery['gallery_desc'],
				
				'S_CHECKED_COMMENT_YES'	=> ( $gallery['gallery_comments'] ) ? ' checked="checked"' : '',
				'S_CHECKED_COMMENT_NO'	=> ( !$gallery['gallery_comments'] ) ? ' checked="checked"' : '',
				
				'S_CHECKED_RATE_YES'	=> ( $gallery['gallery_rate'] ) ? ' checked="checked"' : '',
				'S_CHECKED_RATE_NO'		=> ( !$gallery['gallery_rate'] ) ? ' checked="checked"' : '',
									
				'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
				'S_GALLERY_ACTION'		=> append_sid('admin_gallery.php'),
			));
		
			$template->pparse('body');
			
			break;
		
		case '_save':
		
			debug($HTTP_POST_FILES);
			debug($_POST);
			
			$gallery = get_data('gallery', $gallery_id, 0);

			$image_num = count($HTTP_POST_FILES['ufile']['name']);
		
			for ( $i = 0; $i < $image_num; $i++ )
			{
				$pic_tempname	= ( !empty($HTTP_POST_FILES['ufile']['tmp_name'][$i]) )	? $HTTP_POST_FILES['ufile']['tmp_name'][$i] : '';
				$pic_name		= ( !empty($HTTP_POST_FILES['ufile']['name'][$i]) )		? $HTTP_POST_FILES['ufile']['name'][$i] : '';
				$pic_filetype	= ( !empty($HTTP_POST_FILES['ufile']['type'][$i]) )		? $HTTP_POST_FILES['ufile']['type'][$i] : '';
				$pic_size		= ( !empty($HTTP_POST_FILES['ufile']['size'][$i]) )		? $HTTP_POST_FILES['ufile']['size'][$i] : 0;
				
				$test_sql[] = image_upload('image_gallery', $gallery['gallery_path'], $pic_tempname, $pic_name, $pic_size, $pic_filetype);
			}
			
			debug(count($test_sql));
			
			debug($test_sql);
			
			break;
		
		case '_delete':
		
			if ( $gallery_id && $confirm )
			{	
				$gallery = get_data('gallery', $gallery_id, 0);
			
				$sql = 'DELETE FROM ' . GALLERY . ' WHERE gallery_id = ' . $gallery_id;
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'acp_gallery_delete', $gallery['gallery_name']);
				
				$message = $lang['delete_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else if ( $gallery_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$hidden_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $gallery_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_gallery'],
	
					'L_NO'				=> $lang['common_no'],
					'L_YES'				=> $lang['common_yes'],
					
					'S_HIDDEN_FIELDS'	=> $hidden_fields,
					'S_CONFIRM_ACTION'	=> append_sid('admin_gallery.php'),
				));
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['msg_must_select_gallery']);
			}
			
			$template->pparse('body');
		
			break;
	
		default:
	
			$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
			$template->assign_block_vars('display', array());
			
			$hidden_fields = '<input type="hidden" name="mode" value="_add" />';
					
			$template->assign_vars(array(
				'L_GALLERY_HEAD'		=> $lang['gallery_head'],
				'L_GALLERY_EXPLAIN'		=> $lang['gallery_explain'],
				'L_GALLERY_NAME'		=> $lang['gallery_name'],
				'L_GALLERY_ADD'			=> $lang['gallery_add'],
				
				'L_EDIT'				=> $lang['common_edit'],
				'L_DELETE'				=> $lang['common_delete'],
				'L_UPLOAD'				=> $lang['common_upload'],
				'L_SETTINGS'			=> $lang['settings'],
				
				'S_HIDDEN_FIELDS'		=> $hidden_fields,
				'S_GALLERY_ACTION'		=> append_sid('admin_gallery.php'),
			));
			
			$gallery_data = get_data_array(GALLERY, 'gallery_order');
			
			if ( !$gallery_data )
			{
				$template->assign_block_vars('display.no_entry', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			else
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($gallery_data)); $i++ )
				{
					$class = ($i % 2) ? 'row_class1' : 'row_class2';
					
					$gallery_id	= $gallery_data[$i]['gallery_id'];
					
					$template->assign_block_vars('display.gallery_row', array(
						'CLASS' 		=> $class,
						
						'GALLERY_NAME'	=> $gallery_data[$i]['gallery_name'],
						'GALLERY_INFO'	=> sprintf($lang['gallery_sprintf_size-pic'], size_dir($root_path . $settings['path_gallery'] . '/' . $gallery_data[$i]['gallery_path'] . '/'), $gallery_data[$i]['gallery_pics']),
						'GALLERY_DESC'	=> html_entity_decode($gallery_data[$i]['gallery_desc'], ENT_QUOTES),
						
						
						'U_UPLOAD'		=> append_sid('admin_gallery.php?mode=_upload&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
						'U_EDIT'		=> append_sid('admin_gallery.php?mode=_edit&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
						'U_DELETE'		=> append_sid('admin_gallery.php?mode=_delete&amp;' . POST_GALLERY_URL . '=' . $gallery_id)
					));
				}
			}
			
			$template->pparse('body');
		
			break;
	}
	include('./page_footer_admin.php');
}

?>