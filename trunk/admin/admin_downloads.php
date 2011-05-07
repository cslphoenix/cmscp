<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_download'] )
	{
		$module['_headmenu_01_main']['_submenu_downloads'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_downloads';
	
	include('./pagestart.php');
	
	load_lang('downloads');

	$error	= '';
	$index	= '';
	$log	= LOG_SEK_GAMES;
	$url	= POST_DOWNLOAD_URL;
	$url_c	= POST_DOWNLOAD_CAT_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_downloads'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['download']);
	$fields	= '';

	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_download'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_DOWNLOAD, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
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
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_downloads.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					$data = array(
						'cat_title'	=> request('cat_title', 2),
						'cat_desc'	=> '',
						'cat_icon'	=> '-1',
						'cat_order'	=> '',
					);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
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

				$fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . $url_c . '" value="' . $data_cat . '" />';

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['download_cat']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['download_cat'], $data['cat_title']),
					'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['cat']),
					'L_DESC'	=> sprintf($lang['sprintf_desc'], $lang['cat']),
					'L_ICON'	=> $lang['cat_icon'],
					
					'TITLE'		=> $data['cat_title'],
					'DESC'		=> $data['cat_desc'],
					
					'S_FIELDS'	=> $fields,
					'S_ACTION'	=> check_sid($file),
				));
				
				if ( request('submit', 1) )
				{
					$cat_title		= request('cat_title', 2);
					$cat_desc		= request('cat_desc', 3);
					$cat_icon		= request('cat_icon', 0);
					
					$error = ( !$cat_title ) ? $lang['msg_empty_title'] : '';
					
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
							
							$message = $lang['create_download_cat'] . sprintf($lang['click_return_rank'], '<a href="' . check_sid($file));
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
								. sprintf($lang['return_sub'], '<a href="' . check_sid($file) . '">', '</a>')
								. sprintf($lang['return_update'], '<a href="' . check_sid("$file?mode=_update&amp;$url_c=$data_cat"));
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
				
				$index = true;
				
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
				
					$message = $lang['delete_download_cat'] . sprintf($lang['click_return_download_cat'], '<a href="' . check_sid($file));
					log_add(LOG_ADMIN, LOG_SEK_DOWNLOAD_CAT, 'delete_download_cat');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_cat && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
			
					$fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . $url_c . '" value="' . $data_cat . '" />';
		
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
	
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_downloads.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'		=> $acp_title,
		'L_CREATE'		=> sprintf($lang['sprintf_new_create'], $lang['download_cat']),
		'L_CREATE_FILE'	=> sprintf($lang['sprintf_new_create'], $lang['download']),
		'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['download_cat']),
		'L_EXPLAIN'		=> $lang['download_explain'],
		
		'S_CREATE'		=> check_sid("$file?mode=_create"),
		'S_ACTION'		=> check_sid($file),
		'S_FIELDS'		=> $fields,
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
				
				'MOVE_UP'	=> ( $data[$i]['cat_order'] != '10' )			? '<a href="' . check_sid("$file?mode=_order&amp;move=-15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data[$i]['cat_order'] != $max['max'] )	? '<a href="' . check_sid("$file?mode=_order&amp;move=+15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE' => check_sid("$file?mode=_update&amp;$url_c=$cat_id"),
				'U_DELETE' => check_sid("$file?mode=_delete&amp;$url_c=$cat_id"),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry', array());
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>