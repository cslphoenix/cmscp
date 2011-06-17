<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_download'] )
	{
		$module['hm_main']['sm_downloads'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_downloads';
	
	include('./pagestart.php');
	
	load_lang('downloads');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_DOWNLOADS;
	$url	= POST_DOWNLOAD;
	$url_c	= POST_DOWNLOAD_CAT;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_cat	= request($url_c, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_downloads'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_download'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
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
		$mode = ( request('add_file', 1) ) ? '_create' : '_create_cat';
	
		if ( $mode == '_create' )
		{
			list($cat_id) = each($_POST['add_file']);
			$cat_id = intval($cat_id);
			
			$name = trim(htmlentities(str_replace("\'", "'", $_POST['file_name'][$cat_id]), ENT_COMPAT));
		}
	}
	
#	debug($mode);
#	debug($_GET);
#	debug($_POST);
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
				#	$data = get_data(DOWNLOAD_CAT, $data_cat, 1);
					$data = data(DOWNLOAD_CAT, $data_id, false, 1, true);
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
				#	'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['download_cat']),
				#	'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['download_cat'], $data['cat_title']),
				#	'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['cat']),
				#	'L_DESC'	=> sprintf($lang['sprintf_desc'], $lang['cat']),
				#	'L_ICON'	=> $lang['cat_icon'],
					
				#	'TITLE'		=> $data['cat_title'],
				#	'DESC'		=> $data['cat_desc'],
					
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
							log_add(LOG_ADMIN, SECTION_DOWNLOAD_CAT, 'create_download_cat');
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
							log_add(LOG_ADMIN, SECTION_DOWNLOAD_CAT, 'update_download_cat');
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
				
				log_add(LOG_ADMIN, SECTION_DOWNLOAD_CAT, 'acp_download_cat_order');
				
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
					log_add(LOG_ADMIN, SECTION_DOWNLOAD_CAT, 'delete_download_cat');
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
				
			case '_create_cat':
			case '_update_cat':
			
				$template->assign_block_vars('_input_cat', array());
				
				if ( $mode == '_create_cat' && !(request('submit', 1)) )
				{
					$data = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_icon'	=> request('cat_icon', 0),
								'cat_desc'	=> request('cat_desc', 2),
								'cat_order'	=> '',
							);
				}
				else if ( $mode == '_update_cat' && !(request('submit', 1)) )
				{
					$data = data(DOWNLOAD_CAT, $data_cat, false, 1, true);
				}
				else
				{
					$data = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_icon'	=> request('cat_icon', 0),
								'cat_desc'	=> request('cat_desc', 2),
								'cat_order'	=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
							);
							
					$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(DOWNLOAD_CAT, 'cat_order', '');
					
					$error .= ( !$data['cat_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create_cat' )
						{
							$sql = sql(DOWNLOAD_CAT, $mode, $data);
							$msg = $lang['create_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(DOWNLOAD_CAT, $mode, $data, 'cat_id', $data_cat);
							$msg = $lang['update_cat'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url_c=$data_cat"));
						}
						
						orders(DOWNLOAD_CAT);
						
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
				$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$data_cat\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_cat', '', $mode)], $lang['title_cat'], $data['cat_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title_cat']),
					
					'NAME'		=> $data['cat_name'],
					'ICON'		=> $data['cat_icon'],
					'DESC'		=> $data['cat_desc'],
					
					'S_ORDER'	=> simple_order(DOWNLOAD_CAT, false, 'select', $data['cat_order']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
			
				break;
				
			case '_order_cat':
				
				update(FORUM_CAT, 'cat', $move, $data_cat);
				orders(FORUM_CAT);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete_cat':
			
				$data = data(FORUM_CAT, $data_cat, false, 1, 1);
			
				if ( $data_cat && $confirm )
				{
					$db_data = sql(FORUM_CAT, $mode, $data, 'cat_id', $data_cat);
					
					$message = $lang['delete_c']
						. sprintf($lang['return'], check_sid($file), $acp_title);
					
				#	$oCache -> sCachePath = './../cache/';
				#	$oCache -> deleteCache('_display_navi_news');
					
					orders(PROFILE_CAT);
					
					log_add(LOG_ADMIN, $log, $mode, $db_data);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_cat && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$data_cat\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm_c'], $data['cat_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['forum_cat'])); }
				
				$template->pparse('body');
				
				break;
	
		#	default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
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
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE_CAT'	=> sprintf($lang['sprintf_new_create'], $lang['title_cat']),
		'L_CREATE_FILE'	=> sprintf($lang['sprintf_new_create'], $lang['title']),
		'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['title_cat']),
		'L_EXPLAIN'		=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max = maxi(DOWNLOAD_CAT, 'cat_order', '');
	$cats = data(DOWNLOAD_CAT, false, 'cat_order ASC', 1, false);
	
	if ( $cats )
	{
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id = $cats[$i]['cat_id'];
			
			$files = data(DOWNLOAD, 'cat_id = ' . $cat_id, 'cat_id, file_order ASC', 1, false);
			
			$sql = "SELECT MAX(file_order) AS max$cat_id FROM " . DOWNLOAD . " WHERE cat_id = $cat_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_file = $db->sql_fetchrow($result);
				
			$template->assign_block_vars('_display._cat_row', array(
				'ID'		=> $cat_id,													   
				'NAME'		=> $cats[$i]['cat_name'],
				'FILES'		=> $cats[$i]['cat_files'],
				'DESC'		=> $cats[$i]['cat_desc'],
				
				'MOVE_UP'	=> ( $cats[$i]['cat_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=-15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $cats[$i]['cat_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=+15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update_cat&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete_cat&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
			
			if ( !$files )
			{
				$template->assign_block_vars('_display._cat_row._entry_empty', array());
			}
			else
			{
				for ( $j = 0; $j < count($files); $j++ )
				{
					$file_id = $files[$j]['file_id'];
					
					if ( $files[$j]['cat_id'] == $cat_id )
					{
						$template->assign_block_vars('_display._cat_row._file_row', array(
							'NAME'		=> $files[$j]['file_name'],
							
							'MOVE_UP'	=> ( $files[$j]['file_order'] != '10' )							? '<a href="' . check_sid("$file?mode=_order&amp;cat_type=$cat_id&amp;move=-15&amp;$url=$file_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
							'MOVE_DOWN'	=> ( $files[$j]['file_order'] != $max_file['max' . $cat_id] )	? '<a href="' . check_sid("$file?mode=_order&amp;cat_type=$cat_id&amp;move=+15&amp;$url=$file_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
							
							'U_RESYNC' => check_sid("$file?mode=_resync&amp;$url=$file_id"),
							'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$file_id") . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
							'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$file_id") . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
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