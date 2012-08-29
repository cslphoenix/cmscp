<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_menu',
		'modes'		=> array(
			'acp'	=> array('title' => 'acp_menu_acp'),
			'mcp'	=> array('title' => 'acp_menu_mcp'),
			'ucp'	=> array('title' => 'acp_menu_ucp'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$update = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_menu';
	
	include('./pagestart.php');
	
	add_lang('menu');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_MENU;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . 'admin/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang[$action]);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['a_menu'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_menu.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'list', 'move_down', 'move_up', 'delete')) ) ? $mode : false;
	
	debug($_POST, 'POST');
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':

				$template->assign_block_vars('input', array());
				
				$vars = array(
					'menu' => array(
						'title1' => 'input_data',
						'menu_name'		=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25', 'required' => 'input_name'),
						'type'			=> array('validate' => INT,	'explain' => false, 'type' => 'radio:type',	'params' => array('combi', false, 'main')),
						'main'			=> array('validate' => INT,	'explain' => false, 'type' => 'drop:main', 'trid' => 'main'),
						'menu_file'		=> array('validate' => TXT,	'explain' => false, 'type' => 'drop:files', 'trid' => 'menu_file', 'params' => array($dir_path, '.php', 'mode')),
						'menu_opts'		=> array('validate' => TXT,	'explain' => false, 'type' => 'drop:iopts', 'trid' => 'menu_opts', 'params' => $dir_path),
						'action'		=> 'hidden',
						'menu_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$update )
				{
					$keys = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? key($_POST['submit_module']) : $main ) : 0;
					$name = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? request(array('menu_module', $keys), TXT) : request('menu_label', TXT) ) : request('menu_name', TXT);
					$type = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? 2 : 1 ) : 0;
					
					$data_sql = array(
						'menu_name'		=> $name,
						'type'			=> $type,
						'main'			=> $keys,
						'menu_file'		=> '',
						'menu_opts'		=> '',
						'action'		=> $action,
						'menu_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$update )
				{
					$data_sql = data(MENU2, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(MENU2, $vars, $error, $mode);

					if ( !$error )
					{
						if ( $data_sql['type'] == 0 )
						{
							$data_sql['main'] = 0;
							
							unset($data_sql['menu_file'], $data_sql['menu_opts']);
						}
						else if ( $data_sql['type'] == 1 )
						{
							unset($data_sql['menu_file'], $data_sql['menu_opts']);
						}
						
						if ( $mode == 'create' )
						{
							$data_sql['menu_order'] = maxa(MENU2, 'menu_order', "main = " . $data_sql['main']);
							
							$sql = sql(MENU2, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(MENU2, $mode, $data_sql, 'menu_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&amp;id=$data"));
						}

						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
			#	debug($type, 'type');
			#	debug($main, 'main');
				
			#	debug($data_sql, 'data_sql');
				
				build_output(MENU2, $vars, $data_sql);

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang[$action]),
					'L_EXPLAIN'	=> $lang['common_required'],
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data_sql['menu_name']),

					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');

				break;
				
			case 'move_up':
			case 'move_down':
			
				moveset(MENU2, $mode, $order, $main, $type, $usub);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;

			case 'delete':

				$data_sql = data(MENU, $data, false, 1, true);

				if ( $data && $confirm )
				{
					$sql = sql(MENU, $mode, $data_sql, 'file_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					orders(MENU);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";

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
	
	if ( $main )
	{
		$template->assign_block_vars('list', array());
		
		$tmp = data(MENU2, false, 'main ASC, menu_order ASC', 1, false);
		
		if ( $tmp )
		{
			foreach ( $tmp as $row )
			{
				if ( $row['menu_id'] == $main )
				{
					$cat = $row;
				}
				else if ( $row['main'] == $main )
				{
					$lab[$row['menu_id']] = $row;
				}
			}
			
			$keys_labels = array_keys($lab);
			
			foreach ( $tmp as $row )
			{
				if ( in_array($row['main'], $keys_labels) )
				{
					$mod[$row['main']][] = $row;
				}
			}
		}
		else
		{
			$cat = $mod = $lab = array();
		}
		
		$cid = $cat['menu_id'];
		
		$template->assign_vars(array(
			'CAT'	=> href('a_txt', $file, array('action' => $action), strtoupper($action), strtoupper($action)),
			
			'NAME'	=> isset($lang[$cat['menu_name']]) ? $lang[$cat['menu_name']] : $cat['menu_name'],
			
			'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'common_update'),
			'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'common_delete'),
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
		
	#	debug($lab);
		
		if ( $lab )
		{
			$lmax = array_pop(end($lab));
			
			foreach ( $lab as $lkey => $lrow )
			{
				$lid	= $lrow['menu_id'];
				$lname	= isset($lang[$lrow['menu_name']]) ? $lang[$lrow['menu_name']] : $lrow['menu_name'];
				$lorder	= $lrow['menu_order'];
				
				$template->assign_block_vars('list.row', array(
					'NAME'          => href('a_txt', $file, array('mode' => 'update', 'id' => $lid), $lname, $lname),
					
					'MOVE_UP'       => ( $lorder != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'order' => $lorder), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'     => ( $lorder != $lmax )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'order' => $lorder), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
	
					'UPDATE'        => href('a_img', $file, array('mode' => 'update', 'id' => $lid), 'icon_update', 'common_update'),
					'DELETE'        => href('a_img', $file, array('mode' => 'delete', 'id' => $lid), 'icon_cancel', 'common_delete'),
					
					'S_NAME'	=> "menu_module[$lid]",
					'S_SUBMIT'	=> "submit_module[$lid]",
				
				));
				
				if ( isset($mod[$lid]) )
				{
				#	debug($mod[$lid]);
					$mmax[$lid] = array_pop(end($mod[$lid]));
					
					foreach ( $mod[$lid] as $mrow )
					{
						$mid	= $mrow['menu_id'];
						$msub	= $mrow['main'];
						$mname	= isset($lang[$mrow['menu_name']]) ? $lang[$mrow['menu_name']] : $mrow['menu_name'];
						$morder	= $mrow['menu_order'];
						
						$template->assign_block_vars('list.row.mod', array( 
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $mid), $mname, $mname),
							
							'MOVE_UP'	=> ( $morder != '1' )			? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'usub' => $lid, 'type' => 2, 'order' => $morder), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $morder != $mmax[$lid] )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'usub' => $lid, 'type' => 2, 'order' => $morder), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $mid), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $mid), 'icon_cancel', 'common_delete'),
						));
					}
				}
			}
		}
		else
		{
			$template->assign_block_vars('list.empty', array());
		}
		
		$fields .= '<input type="hidden" name="mode" value="create" />';
		$fields .= '<input type="hidden" name="main" value="' . $main . '" />';
	}
	else
	{
		$template->assign_block_vars('display', array());
		
		$tmp = data(MENU2, "action = '$action' AND type = 0", 'menu_order ASC', 1, false);
		
		if ( $tmp )
		{
			$cnt = count($tmp);
		
			for ( $i = 0; $i < $cnt; $i++ )
			{
				$menu_id	= $tmp[$i]['menu_id'];
				$menu_order	= $tmp[$i]['menu_order'];
				
				$menu_name	= ( isset($lang[$tmp[$i]['menu_name']]) ) ? $lang[$tmp[$i]['menu_name']] : $tmp[$i]['menu_name'];
				
				$template->assign_block_vars('display.cat', array( 
					'NAME'		=> href('a_txt', $file, array('main' => $menu_id), $menu_name, $menu_name),
					
					'MOVE_UP'	=> ( $menu_order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $menu_order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $menu_order != $cnt )	? href('a_img', $file, array('mode' => 'move_down', 'main' => 0, 'order' => $menu_order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
		
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $menu_id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $menu_id), 'icon_cancel', 'common_delete'),
				));
			}
		}
		
		$fields	= '<input type="hidden" name="mode" value="create" />';
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang[$action]),
		'L_EXPLAIN'	=> $lang['explain_' . $action],
		
		'L_CREATE'			=> sprintf($lang['sprintf_create'], $lang['type_0']),
		'L_CREATE_LABEL'	=> sprintf($lang['sprintf_create'], $lang['type_1']),
		'L_CREATE_MODULE'	=> sprintf($lang['sprintf_create'], $lang['type_2']),
		
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>