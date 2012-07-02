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
	$url	= POST_MENU;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_type	= request('sub', INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_menu.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', '_order', 'delete', '_create_cat', '_update_cat', '_order_cat', '_delete_cat')) ) ? $mode : '';

	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
		case 'update':

				$template->assign_block_vars('input', array());

				if ( $mode == 'create' && !(request('submit', TXT)) )
				{
					$data = array(
						'menu_name'		=> request('menu_name', 2),
						'menu_sub'		=> request('menu_sub', 0),
						'menu_hash'		=> request('menu_hash', 2),
						'menu_order'	=> '',
					);
				}
				else if ( $mode == 'update' && !(request('submit', TXT)) )
				{
					$data = data(MENU2, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
						'menu_name'		=> request('menu_name', 2),
						'menu_sub'		=> request('menu_sub', 0),
						'menu_hash'		=> request('menu_hash', 2),
						'menu_order'	=> request('menu_order', 0) ? request('menu_order', 0) : request('menu_order_new', 0),
					);
					
					if ( !$error )
					{
						$data['menu_order'] = $data['menu_order'] ? $data['menu_order'] : maxa(MENU2, 'menu_order', $data['menu_sub']);

						if ( $mode == 'create' )
						{
							$sql = sql(MENU2, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(MENU2, $mode, $data, 'menu_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}

						orders(MENU2, $data['menu_sub']);

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
				$fields .= "<input type=\"hidden\" name=\"menu_sub\" value=\"{$data['menu_sub']}\" />";
				$fields .= "<input type=\"hidden\" name=\"menu_name\" value=\"{$data['menu_name']}\" />";
				$fields .= "<input type=\"hidden\" name=\"menu_hash\" value=\"{$data['menu_hash']}\" />";
				
				$order = ( $data['menu_sub'] ) ? "menu_sub = {$data['menu_sub']}" : '';

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf$mode"], $lang['title'], $data['menu_name']),
					'L_DATA'	=> $lang['data'],

					'L_NAME'	=> $lang['menu_name'],
					'L_LANG'	=> $lang['menu_lang'],
					
					'NAME'		=> $data['menu_name'],
					'LANG'		=> isset($lang[$data['menu_name']]) ? $lang[$data['menu_name']] : $data['menu_name'],

					'S_ORDER'	=> simple_order(MENU2, "menu_sub = {$data['menu_sub']}", '', $data['menu_order']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');

				break;

			case 'order':

				update(MENU2, 'menu', $move, $data_id);
				orders(MENU2, $data_type);

				log_add(LOG_ADMIN, $log, $mode);

				$index = true;

				break;

			case 'delete':

				$data = data(MENU, $data_id, false, 1, true);

				if ( $data_id && $confirm )
				{
					$sql = sql(MENU, $mode, $data, 'file_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					orders(MENU);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['file_name']),

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
	
	$tmp = data(MENU2, false, 'menu_order ASC', 1, false);
	
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
			if ( !$row['menu_sub'] )
			{
				$db_cat[$row['menu_name']]		= $row['menu_name'];
				$db_cat_data[$row['menu_name']]	= sprintf('%s:%s', $row['menu_id'], $row['menu_order']);
			}
			else
			{
				$db_sub[$row['menu_sub']][$row['menu_name']] = $row['menu_name'];
				$db_sub_data[$row['menu_sub']][$row['menu_name']] = sprintf('%s:%s', $row['menu_id'], $row['menu_order']);
			}
		}
		
		if ( isset($db_sub_data) )
		{
			foreach ( $db_sub_data as $k => $v )
			{
				list($sub, $max_sub[$k]) = explode(':', end($v));
			}
		}
	}
	else
	{
		$db_cat = $db_sub = $db_cat_data = $db_sub_data = array();
	}
	
	
	
#	debug($db_cat);
#	debug($db_sub);
#	debug($db_cat_data);
#	debug($db_sub_data);

	
	/* Moduleeinträge in Array bringen */
	foreach ( $module as $key => $row )
	{
		$mod_cat[$key] = $key;
		
		foreach ( $row as $rkey => $rrow )
		{
			$mod_sub[$key][$rkey] = $rkey;
			$mod_sub_data[$key][$rkey] = substr(md5($rrow), 0, 10);
		}
	}
	
#	debug($mod_cat, false, 'mod_cat');
#	debug($mod_sub, false, 'mod_sub');
#	debug($mod_sub_data, false, 'mod_sub_data');

	if ( $new_ary = array_diff($mod_cat, $db_cat) )
	{
		foreach ( $new_ary as $rows )
		{
			$ary_new[$rows] = array('menu_id' => false, 'menu_sub' => $module[$rows]);
		}
		
		if ( $ary_new )
		{
			$tmp_check = array_merge($db_cat_data, $ary_new);
		}
	}
	else
	{
		$tmp_check = $tmp;
	}
	
	list($cat, $max) = explode(':', end($db_cat_data));
	
	
	if ( $tmp_check )
	{
		foreach ( $tmp_check as $check_menu => $check_sub )
		{
			$cname = isset($lang[$check_menu]) ? $lang[$check_menu] : $check_menu;
			
			if ( isset($db_cat_data[$check_menu]) )
			{
				list($cid, $corder) = explode(':', $db_cat_data[$check_menu]);
			}
			else
			{
				$cid	= '';
				$corder	= '';
			}
			
			$template->assign_block_vars('display.row', array(
				'NAME'		=> $cid ? href('a_txt', $file, array('mode' => 'update', $url => $cid), $cname, $cname) : $cname,
				'MOVE_UP'	=> $cid ? ( $corder != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'sub' => 0, $url => $cid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> $cid ? ( $corder != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'sub' => 0, $url => $cid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				'CREATE'	=> $cid ? img('i_icon', 'icon_add2', 'common_create') : href('a_img', $file, array('mode' => 'create', 'menu_name' => $check_menu), 'icon_add', 'common_create'),
				'UPDATE'	=> $cid ? href('a_img', $file, array('mode' => 'update', $url => $cid), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
				'DELETE'	=> $cid ? href('a_img', $file, array('mode' => 'delete', $url => $cid), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
			));
			
		#	debug($db_sub[$cid]);
		#	debug($mod_sub[$check_menu]);
		
		#	$sub_diff[$cid] = array_diff($mod_sub[$check_menu], $db_sub[$cid]);
		
		#	debug($sub_diff[$cid]);
		
		#	debug($mod_sub[$check_menu], false, 'mod_sub');
		#	debug($db_sub[$cid], false, 'db_sub');
			
			$subdb	= isset($db_sub[$cid]) ? $db_sub[$cid] : array();
			$submod	= isset($mod_sub[$check_menu]) ? $mod_sub[$check_menu] : array();
			
			if ( $sub_diff[$check_menu] = array_diff($submod, $subdb) )
			{
				foreach ( $sub_diff[$check_menu] as $rows )
				{
					$new_sub[$check_menu][$rows] = $mod_sub_data[$check_menu][$rows];
				}
				
				if ( isset($new_sub[$check_menu]) && isset($db_sub_data[$cid]) )
				{
					$sub_check[$check_menu] = array_merge($db_sub_data[$cid], $new_sub[$check_menu]);
				}
				else
				{
					$sub_check[$check_menu] = $new_sub[$check_menu];
				}
			}
			else
			{
				$sub_check[$check_menu] = $db_sub_data[$cid];
			}
			
			if ( isset($sub_check[$check_menu]) )
			{
				foreach ( $sub_check[$check_menu] as $subkey => $subvalue )
				{
					$fname = isset($lang[$subkey]) ? $lang[$subkey] : $subkey;
					
					if ( strlen($subvalue) < 9 )
					{
						$tmp = explode(':', $subvalue);
						
						$fid	= $tmp[0];
						$forder	= $tmp[1];
					}
					else
					{
						$fid	= '';
						$forder	= '';
						$ffile	= $subkey;
						$fhash	= $subvalue;
					}
					
					$template->assign_block_vars('display.row.file', array(
						'NAME'		=> $fid ? href('a_txt', $file, array('mode' => 'update', $url => $fid), $fname, $fname) : $fname,
					#	'FILE'		=> $sub_check[$i][$j]['file_name'],
						'MOVE_UP'	=> $fid ? ( $forder != '10' )			? href('a_img', $file, array('mode' => '_order', 'sub' => $cid, 'move' => '-15', $url => $fid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> $fid ? ( $forder != $max_sub[$cid] )	? href('a_img', $file, array('mode' => '_order', 'sub' => $cid, 'move' => '+15', $url => $fid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
						'CREATE'	=> $fid ? img('i_icon', 'icon_add2', 'common_create') : href('a_img', $file, array('mode' => 'create', 'menu_sub' => $cid, 'menu_name' => $ffile, 'menu_hash' => $fhash), 'icon_add', 'common_create'),
						'UPDATE'	=> $fid ? href('a_img', $file, array('mode' => 'update', $url => $fid), 'icon_update', 'common_update') : img('i_icon', 'icon_update2', 'common_update'),
						'DELETE'	=> $fid ? href('a_img', $file, array('mode' => 'delete', $url => $fid), 'icon_cancel', 'common_delete') : img('i_icon', 'icon_cancel2', 'common_delete'),
					));
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