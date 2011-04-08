<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_newscat'] )
	{
		$module['_headmenu_03_news']['_submenu_newscat'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_newscat';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('newscat');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_NEWS_CAT;
	$url	= POST_NEWS_CAT_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_newscat'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['newscat']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_newscat'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
		
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_newscat.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !(request('submit', 1)) )
				{
					$data = array(
								'cat_title'	=> ( request('cat_title', 2) ) ? request('cat_title', 2) : '',
								'cat_image'	=> '',
								'cat_order'	=> '',
							);
				}
				else if ( $mode == '_update' && !(request('submit', 1)) )
				{
					$data = data(NEWS_CAT, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
								'cat_title'	=> request('cat_title', 2),
								'cat_image'	=> request('cat_image', 2),
								'cat_order'	=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
							);
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['newscat']),
					'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['newscat'], $data['cat_title']),
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['newscat']),
					'L_IMAGE'		=> sprintf($lang['sprintf_image'], $lang['newscat']),
					'L_ORDER'		=> $lang['common_order'],
					
					'TITLE'			=> $data['cat_title'],
					'IMAGE'			=> ( $data['cat_image'] ) ? $path_dir . $data['cat_image'] : $images['icon_acp_spacer'],
					'IMAGE_PATH'	=> $path_dir,
					'IMAGE_DEFAULT'	=> $images['icon_acp_spacer'],
					
					'S_ORDER'		=> select_order('select', NEWS_CAT, 'newscat', $data['cat_order']),
					'S_IMAGE'		=> select_box_files('post', 'cat_image', $path_dir, $data['cat_image']),
										
					'S_ACTION'		=> append_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$data = array(
								'cat_title'	=> request('cat_title', 2),
								'cat_image'	=> request('cat_image', 2),
								'cat_order'	=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
							);
							
					$data['cat_order'] = ( !$data['cat_order'] ) ? maxa(NEWS_CAT, 'cat_order', false) : $data['cat_order'];
							
					$error .= ( !$data['cat_title'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_title'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$db_data = sql(NEWS_CAT, $mode, $data);
							
							$message = $lang['create']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							$db_data = sql(NEWS_CAT, $mode, $data, 'cat_id', $data_id);
							
							$message = $lang['update']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
						}
						
						orders(NEWS_CAT);
						
						log_add(LOG_ADMIN, $log, $mode, $db_data);						
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
			
				$template->pparse('body');
				
				break;
			
			case '_order':
			
				update(NEWS_CAT, 'cat', $move, $data_id);
				orders(NEWS_CAT);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
			case '_delete':
			
				$data = data(NEWS_CAT, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{	
					$sql = "UPDATE " . NEWS . " SET news_cat = '' WHERE news_cat = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$db_data = sql(NEWS_CAT, $mode, $data, 'cat_id', $data_id);
					
					$message = $lang['delete']
						. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					
					log_add(LOG_ADMIN, $log, $mode, $db_data);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
					
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['cat_title']),
						
						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['newscat'])); }
				
				$template->pparse('body');
				
				break;
			
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
		
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
			
	$template->set_filenames(array('body' => 'style/acp_newscat.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(	 
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['newscat']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_create'], $lang['newscat']),
		'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['newscat']),
		
		'L_EXPLAIN'	=> $lang['explain'],
	
		'S_CREATE'	=> append_sid("$file?mode=_create"),
		'S_ACTION'	=> append_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max = maxi(NEWS_CAT, 'cat_order', '');
	$tmp = data(NEWS_CAT, false, 'cat_order ASC', 1, false);
	
	if ( $tmp )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp)); $i++ )
		{
			$cat_id = $tmp[$i]['cat_id'];
			$cat_title = $tmp[$i]['cat_title'];
			$cat_order = $tmp[$i]['cat_order'];

			$template->assign_block_vars('_display._cat_row', array(
				'TITLE'		=> $cat_title,
				
				'MOVE_UP'	=> ( $cat_order != '10' ) ? '<a href="' . append_sid("$file?mode=_order&amp;move=-15&amp;$url=$cat_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $cat_order != $max ) ? '<a href="' . append_sid("$file?mode=_order&amp;move=+15&amp;$url=$cat_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$cat_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$cat_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry', array()); }
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>