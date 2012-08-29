<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_newscat',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_newscat', 'auth' => 'auth_newscat'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$update = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_newscat';
	
	include('./pagestart.php');
	
	add_lang('newscat');

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_NEWS_CAT;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', INT);
	
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
	#	'uimg'		=> 'style/inc_java_img.tpl',
	#	'error'		=> 'style/info_error.tpl',
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
				$template->assign_vars(array('IPATH' => $dir_path));
				
				$vars = array(
					'newscat' => array(
						'title1' => 'input_data',
						'cat_name'	=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25',	'required' => 'input_name'),
						'cat_image'	=> array('validate' => TXT,	'explain' => false, 'type' => 'drop:image',	'params' => array($dir_path, array('.png', '.jpg', '.jpeg', '.gif'), true, false)),
						'cat_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$update )
				{
					$data_sql = array(
						'cat_name'	=> request('cat_name', TXT),
						'cat_image'	=> '',
						'cat_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$update )
				{
					$data_sql = data(NEWS_CAT, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(NEWS_CAT, $vars, $error, $mode);
					
					if ( !$error )
					{
					#	$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(NEWS_CAT, 'cat_order', false);
					#	$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(NETWORK, 'cat_order', false);
						
						if ( $mode == 'create' )
						{
							$sql = sql(NEWS_CAT, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(NEWS_CAT, $mode, $data_sql, 'cat_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&amp;id=$data"));
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
				
				build_output(NEWS_CAT, $vars, $data_sql);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data_sql['cat_name']),
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
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
			
				$data_sql = data(NEWS_CAT, $data, false, 1, true);
			
				if ( $data && $confirm )
				{	
					sql(NEWS, 'update', array('news_cat' => ''), 'news_cat', $data_id);
				
					$sql = sql(NEWS_CAT, $mode, $data_sql, 'cat_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(NEWS_CAT);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
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
		$cnt = count($cats);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$id		= $cats[$i]['cat_id'];
			$name	= $cats[$i]['cat_name'];
			$order	= $cats[$i]['cat_order'];

			$template->assign_block_vars('display.row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, ''),
				
				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => 'order', 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => 'order', 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	$template->assign_vars(array(	 
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['title']),
		'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
		
		'L_EXPLAIN'	=> $lang['explain'],
	
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
		
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>