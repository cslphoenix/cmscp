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

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	include($root_path . 'includes/functions_admin.php');
	include($root_path . 'includes/functions_selects.php');
	
	if ( !$userauth['auth_gallery'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_gallery.php', true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_GALLERY_URL]) || isset($HTTP_GET_VARS[POST_GALLERY_URL]) )
	{
		$gallery_id = ( isset($HTTP_POST_VARS[POST_GALLERY_URL]) ) ? intval($HTTP_POST_VARS[POST_GALLERY_URL]) : intval($HTTP_GET_VARS[POST_GALLERY_URL]);
	}
	else
	{
		$gallery_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
	}
	else
	{
		if ( isset($HTTP_POST_VARS['gallery_add']) || isset($HTTP_GET_VARS['gallery_add']) )
		{
			$mode = 'gallery_add';
		}
		else
		{
			$mode = '';
		}
	}
	
	$gallery_auth_fields	= array('auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_upload');
	$gallery_auth_levels	= array('USER', 'TRIAL', 'MEMBER', 'COLEADER', 'LEADER', 'UPLOAD');
	$gallery_auth_const		= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOAD);
	
	$show_index = '';
		
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case 'gallery_add':
			case 'gallery_edit':
			
				$template->set_filenames(array('body' => 'style/acp_gallery.tpl'));
				$template->assign_block_vars('gallery_edit', array());
				
				if ( $mode == 'gallery_edit' )
				{
					$gallery	= get_data('gallery', $gallery_id, 0);
					$new_mode	= 'gallery_update';
				}
				else
				{
					$gallery = array (
						'gallery_name'		=> request('gallery_name', true),
						'gallery_desc'		=> '',
						'gallery_comments'	=> '1',
						'gallery_rate'		=> '1',
						'auth_view'			=> '0',
						'auth_edit'			=> '1',
						'auth_delete'		=> '2',
						'auth_rate'			=> '3',
						'auth_upload'		=> '5',						
					);
	
					$new_mode = 'gallery_create';
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
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $gallery_id . '" />';
				
				$template->assign_vars(array(
					'L_GALLERY_HEAD'		=> $lang['gallery_head'],
					'L_GALLERY_NEW_EDIT'	=> ( $mode == 'gallery_add' ) ? $lang['gallery_add'] : $lang['gallery_edit'],
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
			
			case 'gallery_create':
			
				$gallery_name		= request('gallery_name', true);
				$gallery_desc		= request('gallery_desc', true);
				$gallery_comments	= request('gallery_comments');
				$gallery_rate		= request('gallery_rate');
				$auth_view			= request('auth_view');
				$auth_edit			= request('auth_edit');
				$auth_delete		= request('auth_delete');
				$auth_rate			= request('auth_rate');
				$auth_upload		= request('auth_upload');
				
				$error = ''; 
				$error_msg = '';
			
				if ( !$gallery_name )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_name'];
				}
				
				if ( $error )
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
								
				$sql = 'INSERT INTO ' . GALLERY . " (gallery_name, gallery_desc, gallery_comments, gallery_rate, gallery_create, auth_view, auth_edit, auth_delete, auth_rate, auth_upload, gallery_order)
					VALUES ('" . str_replace("\'", "''", $gallery_name) . "', '" . htmlentities($gallery_desc, ENT_QUOTES) . "', '$gallery_comments', '$gallery_rate', '" . time() . "', '$auth_view', '$auth_edit', '$auth_delete', '$auth_rate', '$auth_upload', $next_order)";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'acp_gallery_add', $gallery_name);
	
				$message = $lang['create_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'gallery_update':
			
				$gallery_title		= ( isset($HTTP_POST_VARS['gallery_title']) )			? trim($HTTP_POST_VARS['gallery_title']) : '';
				
				$error = ''; 
				$error_msg = '';
			
				if ( !$gallery_title )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_title'];
				}
				
				if ( $error )
				{
					message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
				}
				
				$sql = "UPDATE " . GALLERY . " SET
							gallery_title			= '" . str_replace("\'", "''", $gallery_title) . "',
						WHERE gallery_id = " . $gallery_id;
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'acp_gallery_edit');
				
				$message = $lang['update_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'gallery_delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $gallery_id && $confirm )
				{	
					$gallery = get_data('gallery', $gallery_id, 0);
				
					$sql = 'DELETE FROM ' . GALLERY . ' WHERE gallery_id = ' . $gallery_id;
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_GALLERY, 'acp_gallery_delete', $gallery['gallery_title']);
					
					$message = $lang['delete_gallery'] . sprintf($lang['click_return_gallery'], '<a href="' . append_sid('admin_gallery.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}
				else if ( $gallery_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="gallery_delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_GALLERY_URL . '" value="' . $gallery_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_gallery'],
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid('admin_gallery.php'),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_gallery']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
					
				message_die(GENERAL_ERROR, $lang['no_mode']);
					
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
			
	$template->assign_vars(array(
		'L_GALLERY_HEAD'		=> $lang['gallery_head'],
		'L_GALLERY_EXPLAIN'		=> $lang['gallery_explain'],
		'L_GALLERY_NAME'		=> $lang['gallery_name'],
		
		'L_GALLERY_ADD'			=> $lang['gallery_add'],
		
		'L_EDIT'				=> $lang['edit'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'S_GALLERY_ACTION'		=> append_sid('admin_gallery.php'),
	));
	
	$sql = 'SELECT * FROM ' . GALLERY . ' ORDER BY gallery_order';
	if ( !($result = $db->sql_query($sql)) ) 
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$gallery_data = $db->sql_fetchrowset($result);
	
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
			$gallery_date	= create_date($userdata['user_dateformat'], $gallery_data[$i]['gallery_create'], $userdata['user_timezone']);
			
/*
			if ( $config['time_today'] < $gallery_data[$i]['gallery_create'])
			{ 
				$gallery_date = sprintf($lang['today_at'], create_date($config['default_timeformat'], $gallery_data[$i]['gallery_create'], $userdata['user_timezone'])); 
			}
			else if ( $config['time_yesterday'] < $gallery_data[$i]['gallery_create'])
			{ 
				$gallery_date = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $gallery_data[$i]['gallery_create'], $userdata['user_timezone'])); 
			}
*/
				
			$template->assign_block_vars('display.gallery_row', array(
				'CLASS' 		=> $class,
				
				'GALLERY_TITLE'	=> $gallery_data[$i]['gallery_name'],
				'GALLERY_DATE'	=> $gallery_date,
				
				'U_EDIT'		=> append_sid('admin_gallery.php?mode=gallery_edit&amp;' . POST_GALLERY_URL . '=' . $gallery_id),
				'U_DELETE'		=> append_sid('admin_gallery.php?mode=gallery_delete&amp;' . POST_GALLERY_URL . '=' . $gallery_id)
			));
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>