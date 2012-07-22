<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_newscat'] )
	{
		$module['hm_system']['sm_newscat'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_newscat';
	
	include('./pagestart.php');
	
	add_lang('newscat');

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_NEWS_CAT;
	$url	= POST_CAT;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$dir_path	= $root_path . $settings['path_newscat'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_newscat'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_newscat.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'order', 'delete')) ) ? $mode : '';
		
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$template->assign_vars(array('PATH' => $dir_path));
				$template->assign_var_from_handle('UIMG', 'uimg');
				
				$vars = array(
					'newscat' => array(
						'title1' => 'input_data',
						'cat_name'	=> array('validate' => 'text',	'type' => 'text:25:25',	'explain' => true,	'required' => 'input_name'),
						'cat_image'	=> array('validate' => 'text',	'type' => 'drop:image', 'explain' => true,	'params' => $dir_path),
						'cat_order'	=> array('validate' => 'int',	'type' => 'drop:order',	'explain' => true),
					),
				);
				
				if ( $mode == 'create' && !(request('submit', TXT)) )
				{
					$data = array(
						'cat_name'	=> ( request('cat_name', 2) ) ? request('cat_name', 2) : '',
						'cat_image'	=> '',
						'cat_order'	=> '',
					);
				}
				else if ( $mode == 'update' && !(request('submit', TXT)) )
				{
					$data = data(NEWS_CAT, $data_id, false, 1, true);
				}
				else
				{
					$data = build_request(NEWS_CAT, $vars, 'newscat', $error);
					
					if ( !$error )
					{
						$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(NEWS_CAT, 'cat_order', false);
					#	$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(NETWORK, 'cat_order', false);
						
						if ( $mode == 'create' )
						{
							$sql = sql(NEWS_CAT, $mode, $data);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(NEWS_CAT, $mode, $data, 'cat_id', $data_id);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(NEWS_CAT);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);						
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output($data, $vars, 'input', false, NEWS_CAT);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['cat_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
			
			case 'order':
			
				update(NEWS_CAT, 'cat', $move, $data_id);
				orders(NEWS_CAT);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
			case 'delete':
			
				$data = data(NEWS_CAT, $data_id, false, 1, true);
			
				if ( $data_id && $confirm )
				{	
					sql(NEWS, 'update', array('news_cat' => ''), 'news_cat', $data_id);
				
					$sql = sql(NEWS_CAT, $mode, $data, 'cat_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(NEWS_CAT);
					
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
	
	$fields	= '<input type="hidden" name="mode" value="create" />';
	
	$max	= maxi(NEWS_CAT, 'cat_order', '');
	$cats	= data(NEWS_CAT, false, 'cat_order ASC', 1, false);
	
	if ( !$cats )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$count = count($cats);
		
		for ( $i = 0; $i < $count; $i++ )
		{
			$id		= $cats[$i]['cat_id'];
			$name	= $cats[$i]['cat_name'];
			$order	= $cats[$i]['cat_order'];

			$template->assign_block_vars('display.row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
				
				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => 'order', 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => 'order', 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	$template->assign_vars(array(	 
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['title']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
		
		'L_EXPLAIN'	=> $lang['explain'],
	
		'S_CREATE'	=> check_sid("$file?mode=create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
		
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>