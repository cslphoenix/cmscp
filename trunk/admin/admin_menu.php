<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['hm_main']['sm_menu'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_menu';
	
	include('./pagestart.php');
	
	add_lang('menu');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_MENU;
	$url	= 'f';
	$cat	= 'c';
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_cat	= request($cat, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . str_replace('sm', '', $current));
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_menu.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	debug($_POST);

	$mode = ( in_array($mode, array('_create', '_update', '_order', '_delete', '_create_cat', '_update_cat', '_order_cat', '_delete_cat')) ) ? $mode : '';

	if ( $mode )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':

				$template->assign_block_vars('_input', array());

				if ( $mode == '_create' && !(request('submit', 1)) )
				{
					$data = array(
						'file_name'		=> request('file_name', 2),
						'cat_id'		=> request('cat_id', 0),
						'file_order'	=> '',
					);
				}
				else if ( $mode == '_update' && !(request('submit', 1)) )
				{
					$data = data(MENU, $data_cat, false, 1, true);
				}
				else
				{
					$data = array(
						'file_name'		=> request('file_name', 2),
						'cat_id'		=> request('cat_id', 0),
						'file_order'	=> request('file_order', 0) ? request('file_order', 0) : request('file_order_new', 0),
					);

					if ( !$error )
					{
						$data['file_order'] = $data['file_order'] ? $data['file_order'] : maxa(MENU, 'file_order', '');

						if ( $mode == '_create' )
						{
							$sql = sql(MENU, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(MENU, $mode, $data, 'file_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$cat=$data_cat"));
						}

						orders(MENU, $data['cat_id']);

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
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"cat_id\" value=\"{$data['cat_id']}\" />";
				$fields .= "<input type=\"hidden\" name=\"file_name\" value=\"{$data['file_name']}\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf$mode"], $lang['title'], $data['file_name']),
					'L_DATA'	=> $lang['data'],

					'L_NAME'	=> $lang['file_name'],
					
					'NAME'	=> isset($lang[$data['file_name']]) ? sprintf($lang['sprintf_empty_line'], $data['file_name'], $lang[$data['file_name']]) : $data['file_name'],

					'S_ORDER'	=> simple_order(MENU, "cat_id = " . $data['cat_id'], 'select', $data['file_order']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');

				break;

			case '_order':

				update(MENU_CAT, 'cat', $move, $data_id);
				orders(MENU_CAT);

				log_add(LOG_ADMIN, $log, $mode);

				$index = true;

				break;

			case '_delete':

				$data = data(GAMES, $data_id, false, 1, true);

				if ( $data_id && $confirm )
				{
					$sql = sql(GAMES, $mode, $data, 'game_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					orders(GAMES, '-1');

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['game_name']),

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

			case '_create_cat':
			case '_update_cat':

				$template->assign_block_vars('_cat', array());

				if ( $mode == '_create_cat' && !(request('submit', 1)) )
				{
					$data = array(
						'cat_name'	=> request('cat_name', 2),
						'cat_order'	=> '',
					);
				}
				else if ( $mode == '_update_cat' && !(request('submit', 1)) )
				{
					$data = data(MENU_CAT, $data_cat, false, 1, true);
				}
				else
				{
					$data = array(
						'cat_name'	=> request('cat_name', 2),
						'cat_order'	=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
					);

					if ( !$error )
					{
						$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(MENU_CAT, 'cat_order', '');

						if ( $mode == '_create_cat' )
						{
							$sql = sql(MENU_CAT, $mode, $data);
							$msg = $lang['create_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(MENU_CAT, $mode, $data, 'cat_id', $data_cat);
							$msg = $lang['update_cat'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$cat=$data_cat"));
						}
						
						orders(MENU_CAT);

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
				$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data_cat\" />";
				$fields .= "<input type=\"hidden\" name=\"cat_name\" value=\"" . $data['cat_name'] . "\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_cat', '', $mode) ], $lang['title_cat'], $data['cat_name']),
					'L_DATA'	=> $lang['data_cat'],
					'L_NAME'	=> $lang['cat_name'],

					'NAME'	=> isset($lang[$data['cat_name']]) ? sprintf($lang['sprintf_empty_line'], $data['cat_name'], $lang[$data['cat_name']]) : $data['cat_name'],
					
					'S_ORDER'	=> simple_order(MENU_CAT, '', 'select', $data['cat_order']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');

				break;
		}

		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}

	$template->assign_block_vars('_display', array());

	$max = maxi(MENU_CAT, 'cat_order', '');
	$tmp = data(MENU_CAT, false, 'cat_order ASC', 1, false);

	/*	Dateien auslesen */
	$setmodules = 1;
	unset($module);

	$tmp_dir = scandir('./');
	
	foreach ( $tmp_dir as $files )
	{
		if ( preg_match("/^admin_.*?\.php$/", $files) )
		{
			include('./' . $files);
		}
	}
	
	unset($setmodules);
	
	/* Datenbankeinträge in Array bringen */
	if ( $tmp )
	{
		foreach ( $tmp as $row )
		{
			$_tmp[$row['cat_name']] = $row['cat_name'];
		}
	}
	else
	{
		$tmp = array();
		$_tmp = array();
	}
	
	/* Moduleeinträge in Array bringen */
	foreach ( $module as $key => $row )
	{
		$_mod[$key] = $key;
	}

	/* Einträge vergleich und DB Array erweitern */
	if ( $_ary = array_diff($_mod, $_tmp) )
	{
		foreach ( $_ary as $rows )
		{
			$_new_ary[] = array('cat_name' => $rows, 'file_info' => $module[$rows]);
		}
		
		if ( $_new_ary )
		{
			$tmp_check = array_merge($tmp, $_new_ary);
		}
	}
	else
	{
		$tmp_check = $tmp;
	}
	
	if ( $tmp_check )
	{
		$cnt = count($tmp_check);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$cid	= isset($tmp_check[$i]['cat_id']) ? $tmp_check[$i]['cat_id'] : '';
			$cname	= isset($lang[$tmp_check[$i]['cat_name']]) ? $lang[$tmp_check[$i]['cat_name']] : $tmp_check[$i]['cat_name'];
			$corder	= isset($tmp_check[$i]['cat_order']) ? $tmp_check[$i]['cat_order'] : '';
			
			$sub_check[$i] = '';
			
			$cat_name = $tmp_check[$i]['cat_name'];
			
			if ( $cid )
			{
				$subs[$i] = data(MENU, "cat_id = $cid", 'cat_id ASC, file_order ASC', 1, false);
			#	$smax[$i] = maxi(MENU, "profile_cat = $cat_id", '');
				$sql = "SELECT MAX(file_order) AS max$cid FROM " . MENU . " WHERE cat_id = $cid";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$maxs[$i] = $db->sql_fetchrow($result);
				
				if ( $subs[$i] )
				{
					foreach ( $subs[$i] as $rows )
					{
						$_sub[$i][$rows['file_name']] = $rows['file_name'];
					}
				}
				else
				{
					$_sub[$i] = array();
				}
				
				foreach ( $module[$cat_name] as $key => $rows )
				{
					$_mos[$i][$key] = $key;
				}
				
				if ( $_ary_sub_diff[$i] = array_diff($_mos[$i], $_sub[$i]) )
				{
					foreach ( $_ary_sub_diff[$i] as $rows )
					{
						$_new_sub_ary[$i][] = array('cat_id' => $cid, 'file_name' => $rows);
					}
					
					if ( $_new_sub_ary[$i] && $subs[$i] )
					{
						$sub_check[$i] = array_merge($subs[$i], $_new_sub_ary[$i]);
					}
					else
					{
						$sub_check[$i] = $_new_sub_ary[$i];
					}
				}
				else
				{
					$sub_check[$i] = $subs[$i];
				}
			}
			
			$template->assign_block_vars('_display._row', array(
				'NAME'		=> $cid ? href('a_txt', $file, array('?mode' => '_update', $url => $cid), $cname, $cname) : $cname,
				'MOVE_UP'	=> $cid ? ( $corder != '10' ) ? href('a_img', $file, array('?mode' => '_order', 'move' => '-15', $url => $cid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> $cid ? ( $corder != $max ) ? href('a_img', $file, array('?mode' => '_order', 'move' => '+15', $url => $cid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				'CREATE'	=> $cid ? img('i_icon', 'icon_add2', 'common_create') : href('a_img', $file, array('?mode' => '_create_cat', 'cat_name' => $cat_name), 'icon_add', 'common_create'),
				'UPDATE'	=> $cid ? href('a_img', $file, array('?mode' => '_update_cat', $cat => $cid), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
				'DELETE'	=> $cid ? href('a_img', $file, array('?mode' => '_delete_cat', $cat => $cid), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
			));
			
		#	debug($sub_check[$i], 'sub_check');

			if ( $sub_check[$i] )
			{
				$cnt_sub = count($sub_check[$i]);
				
			#	debug($cnt_sub, 'cnt_sub');
			
			#	debug($sub_check[$i]);
				
				for ( $j = 0; $j < $cnt_sub; $j++ )
				{
					$fid	= isset($sub_check[$i][$j]['file_id']) ? $sub_check[$i][$j]['file_id'] : '';
					$fcat	= isset($sub_check[$i][$j]['cat_id']) ? $sub_check[$i][$j]['cat_id'] : '';
					$fname	= isset($lang[$sub_check[$i][$j]['file_name']]) ? $lang[$sub_check[$i][$j]['file_name']] : $sub_check[$i][$j]['file_name'];
					$forder	= isset($sub_check[$i][$j]['file_order']) ? $sub_check[$i][$j]['file_order'] : '';
					
					$file_name = $sub_check[$i][$j]['file_name'];

					if ( $cid == $fcat )
					{
						$template->assign_block_vars('_display._row._file', array(
							'NAME'		=> $fid ? href('a_txt', $file, array('?mode' => '_update', $url => $fid), $fname, $fname) : $fname,
							'MOVE_UP'	=> $fid ? ( $forder != '10' ) ? href('a_img', $file, array('?mode' => '_order', 'move' => '-15', $url => $fid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> $fid ? ( $forder != $maxs[$i]['max' . $cid] ) ? href('a_img', $file, array('?mode' => '_order', 'move' => '+15', $url => $fid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
							'CREATE'	=> $fid ? img('i_icon', 'icon_add2', 'common_create') : href('a_img', $file, array('?mode' => '_create', 'cat_id' => $cid, 'file_name' => $file_name), 'icon_add', 'common_create'),
							'UPDATE'	=> $fid ? href('a_img', $file, array('?mode' => '_update', $cat => $cid, $url => $fid), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
							'DELETE'	=> $fid ? href('a_img', $file, array('?mode' => '_delete', $cat => $cid, $url => $fid), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
						));
					}
				}
			}
		}
	
		
	}

	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],

		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>