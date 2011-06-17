<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_newscat'] )
	{
		$module['hm_news']['sm_newscat'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_newscat';
	
	include('./pagestart.php');
	
	load_lang('newscat');

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_NEWSCAT;
	$url	= POST_CAT;
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
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_newscat.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
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
				
				$template->assign_vars(array('PATH' => $path_dir));
				$template->assign_var_from_handle('UIMG', 'uimg');
				
				if ( $mode == '_create' && !(request('submit', 1)) )
				{
					$data = array(
								'cat_name'	=> ( request('cat_name', 2) ) ? request('cat_name', 2) : '',
								'cat_image'	=> '',
								'cat_order'	=> '',
							);
				}
				else if ( $mode == '_update' && !(request('submit', 1)) )
				{
					$data = data(NEWSCAT, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_image'	=> request('cat_image', 1),
								'cat_order'	=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
							);
				}
				
				$sql = "SELECT * FROM " . NEWSCAT . " ORDER BY cat_order ASC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cats = $db->sql_fetchrowset($result);
				
				$cat_image = "<select class=\"select\" name=\"cat_image\" id=\"cat_image\" onchange=\"update_image(this.options[selectedIndex].value);\">";
				$cat_image .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_cat_image']) . "</option>";
				
				for ( $i = 0; $i < count($cats); $i++ )
				{
					$marked = ( $data['cat_image'] == $cats[$i]['cat_image'] ) ? ' selected="selected"' : '';
					$cat_image .= "<option value=\"" . $cats[$i]['cat_image'] . "\"$marked>" . sprintf($lang['sprintf_select_format'], $cats[$i]['cat_name']) . "</option>";
				}
				
				$cat_image .= "</select>";
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['newscat']),
					'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['newscat'], $data['cat_name']),
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['newscat']),
					'L_IMAGE'		=> sprintf($lang['sprintf_image'], $lang['newscat']),
					
					'NAME'			=> $data['cat_name'],
					'IMAGE'			=> $data['cat_image'] ? $path_dir . $data['cat_image'] : $images['icon_acp_spacer'],
					
					'S_ORDER'		=> simple_order(NEWSCAT, false, 'select', $data['cat_order']),
					'S_IMAGE'		=> select_box_files('post', 'cat_image', $path_dir, $data['cat_image']),
										
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$error .= check(NEWSCAT, array('cat_name'	=> $data['cat_name'], 'cat_id'	=> $data_id), $error);
					
					if ( !$error )
					{
						$data['cat_order'] = ( !$data['cat_order'] ) ? maxa(NEWSCAT, 'cat_order', false) : $data['cat_order'];
						
						if ( $mode == '_create' )
						{
							$sql = sql(NEWSCAT, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(NEWSCAT, $mode, $data, 'cat_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(NEWSCAT);
						
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
			
			case '_order':
			
				update(NEWSCAT, 'newscat', $move, $data_id);
				orders(NEWSCAT);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
			case '_delete':
			
				$data = data(NEWSCAT, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{	
					sql(NEWS, 'update', array('news_cat' => ''), 'news_cat', $data_id);
				
					$sql = sql(NEWSCAT, $mode, $data, 'cat_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['cat_name']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['newscat']));
				}
				
				$template->pparse('confirm');
				
				break;
			
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
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
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['newscat']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_create'], $lang['newscat']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['newscat']),
		
		'L_EXPLAIN'	=> $lang['explain'],
	
		'S_CREATE'	=> check_sid("$file?mode=_create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max = maxi(NEWSCAT, 'cat_order', '');
	$cats = data(NEWSCAT, false, 'cat_order ASC', 1, false);
	
	if ( !$cats )
	{
		$template->assign_block_vars('_display._entry_empty', array());
	}
	else
	{
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id		= $cats[$i]['cat_id'];
			$cat_name	= $cats[$i]['cat_name'];
			$cat_order	= $cats[$i]['cat_order'];

			$template->assign_block_vars('_display._cat_row', array(
				'NAME'		=> $cat_name,
				
				'MOVE_UP'	=> ( $cat_order != '10' ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=-15&amp;$url=$cat_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $cat_order != $max ) ? '<a href="' . check_sid("$file?mode=_order&amp;move=+15&amp;$url=$cat_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$cat_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$cat_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
		
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>