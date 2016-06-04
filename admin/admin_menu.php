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
			'pcp'	=> array('title' => 'acp_menu_pcp'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_menu';
	
	include('./pagestart.php');
	
	add_lang('menu');
	acl_auth(array('a_menu', 'a_menu_site'));
	
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
	$acp_title	= sprintf($lang['stf_head'], $lang[$action]);
	
	($cancel) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_menu.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));

	$base = ($settings['smain']['menu_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'delete'))) ? $mode : false;
	
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
						'menu_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name'),
						'menu_lang'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'menu_show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'type'			=> array('validate' => INT,	'explain' => false,	'type' => (($action != 'pcp') ? 'radio:type' : 'radio:navi'), 'params' => array('combi', false, 'main')),
						'main'			=> array('validate' => INT,	'explain' => false,	'type' => $base,		'divbox' => true, 'params' => array(false, true, false)),
						'menu_file'		=> array('validate' => TXT,	'explain' => false, 'type' => 'drop:files', 'divbox' => true, 'params' => (($action != 'pcp') ? array($dir_path, '.php', 'mode', 'menu') : array($root_path, '.php', 'self', 'navi'))),
						'menu_opts'		=> ($action != 'pcp') ? array('validate' => TXT, 'explain' => false, 'type' => 'drop:iopts', 'divbox' => true, 'params' => $dir_path) : 'hidden',
						'menu_intern'	=> ($action != 'pcp') ? 'hidden' : array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno',	'divbox' => true),
						'menu_target'	=> ($action != 'pcp') ? 'hidden' : array('validate' => INT,	'explain' => false,	'type' => 'radio:target',	'divbox' => true, 'params' => array(false, false, false)),
						'action'		=> 'hidden',
						'menu_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$submit )
				{
					if ( $action != 'pcp' )
					{
						$keys = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? key($_POST['submit_module']) : $main ) : 0;
						$name = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? request(array('menu_module', $keys), TXT) : request('menu_label', TXT) ) : request('menu_name', TXT);
						$type = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? 2 : 1 ) : 0;
					}
					else
					{
						$keys = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? key($_POST['submit_module']) : $main ) : 0;
						$name = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? request(array('menu_module', $keys), TXT) : request('menu_label', TXT) ) : request('menu_name', TXT);
						$type = ( !isset($_POST['menu_name']) ) ? 4 : 3;
					}
					
					$data_sql = array(
						'menu_name'		=> $name,
						'type'			=> $type,
						'main'			=> $keys,
						'menu_lang'		=> 0,
						'menu_show'		=> 1,
						'menu_file'		=> '',
						'menu_opts'		=> '',
						'menu_target'	=> 0,
						'menu_intern'	=> 0,
						'action'		=> $action,
						'menu_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(MENU, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(MENU, $vars, $error, $mode);

					if ( !$error )
					{
						if ( !$data_sql['type'] || $data_sql['type'] == 3 )
						{
							$data_sql['main'] = 0;
							
							unset($data_sql['menu_file'], $data_sql['menu_opts']);
						}
						else if ( $data_sql['type'] == 1 )
						{
							unset($data_sql['menu_file'], $data_sql['menu_opts']);
						}
						else if ( $data_sql['type'] == 4 )
						{
							$data_sql['menu_file'] = ($data_sql['menu_target']) ? set_http($data_sql['menu_file']) : './' . $data_sql['menu_file'];
							unset($data_sql['menu_opts']);
						}
						
						if ( $mode == 'create' )
						{
							$data_sql['menu_order'] = maxa(MENU, 'menu_order', "action = '$action' AND main = " . $data_sql['main']);
							
							$sql = sql(MENU, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(MENU, $mode, $data_sql, 'menu_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid("$file&main="), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(MENU, $vars, $data_sql);
				
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], lang($data_sql['menu_name'])),
					'L_EXPLAIN'	=> $lang['com_required'],

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');

				break;
				
			case 'move_up':
			case 'move_down':
			
				move(MENU, $mode, $order, $main, $type, $usub, $action);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;

			case 'delete':

				$data_sql = data(MENU, $data, false, 1, true);
				
				if ( $accept && $data )				
				{
					switch ( $data_sql['type'] )
					{
						case 0:
						
							sql(MENU, $mode, $data_sql, 'menu_id', $data);
							sql(MENU, $mode, $data_sql, 'menu_id', $data);
							
							break;
							
						case 1:
						
							sql(MENU, $mode, $data_sql, 'main', $data);
							
							break;
					}
					
					$sql = sql(MENU, $mode, $data_sql, 'menu_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( !$accept && $data )
				{
					$fields .= build_fields(array(
						'mode'	=> $mode,
						'id'	=> $data,
					));
			
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang["notice_confirm{$data_sql['type']}_delete"], $lang["confirm_{$data_sql['type']}"], lang($data_sql['menu_name'])),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				
				$template->pparse('confirm');

				break;
		}

		if ( $index != true )
		{
			acp_footer();
			exit;
		}
	}
	
	if ( $main )
	{
		$tmp = data(MENU, false, 'main ASC, menu_order ASC', 1, 0);
		
		$cat = $module = $label = array();
		
		if ( $tmp && $action != 'pcp' )
		{
			$template->assign_block_vars('menu', array());
			
			foreach ( $tmp as $row )
			{
				if ( $row['menu_id'] == $main )
				{
					$cat = $row;
				}
				else if ( $row['main'] == $main )
				{
					$label[$row['menu_id']] = $row;
				}
			}
			
			$keys_labels = array_keys($label);
			
			foreach ( $tmp as $row )
			{
				if ( in_array($row['main'], $keys_labels) )
				{
					$module[$row['main']][] = $row;
				}
			}
			
			$cid	= $cat['menu_id'];
			$name	= lang($cat['menu_name']);
			
			$template->assign_vars(array(
				'CAT'		=> href('a_txt', $file, array($file), $lang['acp_overview'], $lang['acp_overview']),
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $cid), $name, $name),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'com_delete'),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			if ( $label )
			{
				$lmax = count($label);
				
				foreach ( $label as $lkey => $lrow )
				{
					$lid	= $lrow['menu_id'];
					$lorder	= $lrow['menu_order'];
					$lname	= lang($lrow['menu_name']);
					
					$template->assign_block_vars('menu.row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $lid), $lname, $lname),
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $lid), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $lid), 'icon_cancel', 'com_delete'),
						
						'MOVE_UP'	=> ( $lorder != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'order' => $lorder), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $lorder != $lmax )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'order' => $lorder), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
						
						'S_NAME'	=> "menu_module[$lid]",
						'S_SUBMIT'	=> "submit_module[$lid]",
					));
					
					if ( isset($module[$lid]) )
					{
						$mmax[$lid] = count($module[$lid]);
						
						foreach ( $module[$lid] as $mrow )
						{
							$mid	= $mrow['menu_id'];
							$msub	= $mrow['main'];
							$morder	= $mrow['menu_order'];
							$mname	= lang($mrow['menu_name']);
							
							$template->assign_block_vars('menu.row.mod', array( 
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $mid), $mname, $mname),
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $mid), 'icon_update', 'common_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $mid), 'icon_cancel', 'com_delete'),
								
								'MOVE_UP'	=> ( $morder != '1' )			? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'usub' => $lid, 'type' => 2, 'order' => $morder), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $morder != $mmax[$lid] )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'usub' => $lid, 'type' => 2, 'order' => $morder), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
							));
						}
					}
				}
			}
			else
			{
				$template->assign_block_vars('menu.empty', array());
			}
		}
		else if ( $tmp && $action == 'pcp' )
		{
			$template->assign_block_vars('navi', array());
			
			$cat = $module = $label = $field = array();
			
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
						$field[] = $row;
					}
				}
				
				$cid = $cat['menu_id'];
			
				$template->assign_vars(array(
					'CAT'		=> href('a_txt', $file, array($file), $lang['acp_overview'], $lang['acp_overview']),
					'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $cid), $cat['menu_name'], $cat['menu_name']),
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'com_delete'),
					
					'S_NAME'	=> "profile_fields[$cid]",
					'S_SUBMIT'	=> "submit_field[$cid]",
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
			}
			
			if ( $field )
			{
				$max = count($field);
				
				foreach ( $field as $row )
				{
					$fid	= $row['menu_id'];
					$order	= $row['menu_order'];
					$name	= isset($lang[$row['menu_name']]) ? $lang[$row['menu_name']] : $row['menu_name'];
					
					$template->assign_block_vars('navi.row', array(
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $fid), $name, $name),
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $fid), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $fid), 'icon_cancel', 'com_delete'),
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'usub' => $cid, 'type' => 4, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'usub' => $cid, 'type' => 4, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
					));
				}
			}
			else
			{
				$template->assign_block_vars('navi.empty', array());
			}
		}
		
		$fields .= '<input type="hidden" name="mode" value="create" />';
		$fields .= '<input type="hidden" name="main" value="' . $main . '" />';
	}
	else
	{
		$template->assign_block_vars('display', array());
		
		$typ = ( $action != 'pcp' ) ? 0 : 3;
		
		$tmp = data(MENU, "WHERE action = '$action' AND type = $typ", 'menu_order ASC', 1, false);
		
		if ( !$tmp )
		{
			$template->assign_block_vars('display.empty', array());
		}
		else
		{
			$max = count($tmp);
		
			foreach ( $tmp as $row )
			{
				$id		= $row['menu_id'];
				$order	= $row['menu_order'];
				$name	= lang($row['menu_name']);
				
				$template->assign_block_vars('display.row', array( 
					'NAME'		=> href('a_txt', $file, array('main' => $id), $name, $name),
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
					
					'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down', 'main' => 0, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				));
			}
		}
				
		$fields	.= '<input type="hidden" name="mode" value="create" />';
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['stf_head'], $lang[$action]),
		'L_EXPLAIN'	=> $lang['explain_' . $action],
		'L_NAME'	=> $lang['type_0'],
		
		'L_CREATE'			=> sprintf($lang['stf_create'], $lang['type_0']),
		'L_CREATE_LABEL'	=> sprintf($lang['stf_create'], $lang['type_1']),
		'L_CREATE_MODULE'	=> sprintf($lang['stf_create'], $lang['type_2']),
		
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');

	acp_footer();
}

?>