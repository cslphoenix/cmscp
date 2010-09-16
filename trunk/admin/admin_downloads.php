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
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_download'] )
	{
		$module['_headmenu_main']['_submenu_downloads'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_downloads';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('downloads');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_DOWNLOAD_URL, 0);
	$data_cat	= request(POST_DOWNLOAD_CAT_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_downloads'] . '/';
	$show_index	= '';
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_download'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_DOWNLOAD, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $no_header ) ? redirect('admin/' . append_sid('admin_downloads.php', true)) : false;
	
	/*	was ein mist ....	*/
	if ( isset($_POST['_create_file']) || isset($_POST['_create_file']) )
	{
		$mode = request('_create_file', 1);
	
		if( $mode == '_create_file' )
		{
			list($cat_id) = each($_POST['_create_file']);
			$data_cat = intval($cat_id);
			
			debug($data_cat);
			// 
			// stripslashes needs to be run on this because slashes are added when the forum name is posted
			//
		#	$forumname = stripslashes($_POST['forumname'][$cat_id]);
		}
	}

	debug($mode);
	debug($_GET);
	debug($_POST);
#	debug($_FILES);
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_downloads.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 2) )
				{
					$data = array(
						'cat_title'	=> request('cat_title', 2),
						'cat_desc'	=> '',
						'cat_icon'	=> '-1',
						'cat_order'	=> '',
					);
				}
				else if ( $mode == '_update' && !request('submit', 2) )
				{
					$data = get_data(DOWNLOAD_CAT, $data_cat, 1);
				}
				else
				{
					$data = array(
						'cat_title'	=> request('cat_title', 2),
						'cat_desc'	=> request('cat_desc', 2),
						'cat_icon'	=> request('cat_icon', 2),
						'cat_order'	=> request('cat_order', 0),
					);
				}

				$s_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_DOWNLOAD_CAT_URL . '" value="' . $data_cat . '" />';

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['download_cat']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['download_cat'], $data['cat_title']),
					'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['cat']),
					'L_DESC'	=> sprintf($lang['sprintf_desc'], $lang['cat']),
					'L_ICON'	=> $lang['cat_icon'],
					
					'TITLE'		=> $data['cat_title'],
					'DESC'		=> $data['cat_desc'],
					
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_downloads.php'),
				));
				
				if ( request('submit', 2) )
				{
					$cat_title		= request('cat_title', 2);
					$cat_desc		= request('cat_desc', 3);
					$cat_icon		= request('cat_icon', 0);
					
					$error = ( !$cat_title ) ? $lang['msg_select_title'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$max_row	= get_data_max(DOWNLOAD_CAT, 'cat_order', '');
							$next_order	= $max_row['max'] + 10;
							
							$sql = "INSERT INTO " . DOWNLOAD_CAT . " (cat_title, cat_desc, cat_icon, cat_order)
										VALUES ('$cat_title', '$cat_desc', '$cat_icon', '$next_order')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_download_cat'] . sprintf($lang['click_return_rank'], '<a href="' . append_sid('admin_downloads.php') . '">', '</a>');
							log_add(LOG_ADMIN, LOG_SEK_DOWNLOAD_CAT, 'create_download_cat');
						}
						else
						{
							$sql = "UPDATE " . DOWNLOAD_CAT . " SET
										cat_title	= '$cat_title',
										cat_desc	= '$cat_desc',
										cat_icon	= '$cat_icon',
										cat_order	= '$cat_order',
									WHERE cat_id = $data_cat";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_download_cat']
								. sprintf($lang['click_return_download_cat'], '<a href="' . append_sid('admin_downloads.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_downloads.php?mode=_update&amp;' . POST_DOWNLOAD_CAT_URL . '=' . $data_cat) . '">', '</a>');
							log_add(LOG_ADMIN, LOG_SEK_DOWNLOAD_CAT, 'update_download_cat');
						}
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
				
				update(DOWNLOAD_CAT, 'download_cat', $move, $data_cat);
				orders(DOWNLOAD_CAT);
				
				log_add(LOG_ADMIN, LOG_SEK_DOWNLOAD_CAT, 'acp_download_cat_order');
				
				$show_index = TRUE;
				
				break;
				
			case '_delete':
			
				$data = get_data(DOWNLOAD_CAT, $data_cat, 1);
			
				if ( $data_cat && $confirm )
				{	
					$sql = "DELETE FROM " . DOWNLOAD_CAT . " WHERE cat_id = $data_cat";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete_download_cat'] . sprintf($lang['click_return_download_cat'], '<a href="' . append_sid('admin_downloads.php') . '">', '</a>');
					log_add(LOG_ADMIN, LOG_SEK_DOWNLOAD_CAT, 'delete_download_cat');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_cat && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
			
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_DOWNLOAD_CAT_URL . '" value="' . $data_cat . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_download_cat'], $data['cat_title']),
						
						'S_FIELDS'		=> $s_fields,
						'S_ACTION'		=> append_sid('admin_downloads.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_download_cat']);
				}
					
				$template->pparse('body');
				
				break;
	
			default:
			
				message(GENERAL_ERROR, $lang['msg_no_module_select']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_downloads.tpl'));
	$template->assign_block_vars('_display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['download']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_create'], $lang['download_cat']),
		'L_CREATE_FILE'	=> sprintf($lang['sprintf_new_create'], $lang['download']),
		'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['download_cat']),
		'L_EXPLAIN'		=> $lang['download_explain'],
		
		'S_FIELDS'		=> $s_fields,
		'S_CREATE'		=> append_sid('admin_downloads.php?mode=_create'),
		'S_ACTION'		=> append_sid('admin_downloads.php'),
	));
	
	$max	= get_data_max(DOWNLOAD_CAT, 'cat_order', '');
	$data	= get_data_array(DOWNLOAD_CAT, '', 'cat_order', 'ASC');
	
	if ( $data )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data)); $i++ )
		{
			$cat_id = $data[$i]['cat_id'];
				
			$template->assign_block_vars('_display._dl_cat_row', array(
				'ID'		=> $cat_id,													   
				'TITLE'		=> $data[$i]['cat_title'],
				'FILES'		=> $data[$i]['cat_files'],
				'DESC'		=> $data[$i]['cat_desc'],
				
				'MOVE_UP'	=> ( $data[$i]['cat_order'] != '10' )			? '<a href="' . append_sid('admin_downloads.php?mode=_order&amp;move=-15&amp;' . POST_DOWNLOAD_CAT_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data[$i]['cat_order'] != $max['max'] )	? '<a href="' . append_sid('admin_downloads.php?mode=_order&amp;move=15&amp;' . POST_DOWNLOAD_CAT_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE' => append_sid('admin_downloads.php?mode=_update&amp;' . POST_DOWNLOAD_CAT_URL . '=' . $cat_id),
				'U_DELETE' => append_sid('admin_downloads.php?mode=_delete&amp;' . POST_DOWNLOAD_CAT_URL . '=' . $cat_id),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry', array()); }
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>