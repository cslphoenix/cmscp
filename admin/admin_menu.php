<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_MENU',
		'CAT'		=> 'SETTINGS',
		'MODES'		=> array(
			'ACP'	=> array('TITLE' => 'ACP_MENU_ACP'),
			'MCP'	=> array('TITLE' => 'ACP_MENU_MCP'),
			'UCP'	=> array('TITLE' => 'ACP_MENU_UCP'),
			'PCP'	=> array('TITLE' => 'ACP_MENU_PCP'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_MENU';
	
	include('./pagestart.php');
	
	add_lang('menu');
	acl_auth(array('A_MENU', 'A_MENU_SITE'));
	
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
	$action	= strtoupper(request('action', TYP));
	
	$cancel ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
	$base = ($settings['smain']['menu_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'delete'))) ? $mode : 'display';
	$path = $root_path . 'admin/';
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';
	$_top = sprintf($lang['STF_HEADER'], $lang[$action]);
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'menu' => array(
						'title'		=> 'INPUT_DATA',
						'menu_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name'),
						'menu_show'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'type'			=> array('validate' => INT,	'explain' => false,	'type' => (($action != 'PCP') ? 'radio:type' : 'radio:navi'), 'params' => array('combi', false, 'main')),
						'main'			=> array('validate' => INT,	'explain' => false,	'type' => $base, 'divbox' => true, 'params' => array(false, true, false), 'required' => array('main', 'type', array(1, 2))),
						'menu_file'		=> array('validate' => TXT,	'explain' => false, 'type' => 'drop:files', 'divbox' => true, 'params' => (($action != 'PCP') ? array($path, '.php', 'mode', 'menu') : array($root_path, '.php', 'self', 'navi'))),
						'menu_opts'		=> ($action != 'PCP') ? array('validate' => TXT, 'explain' => false, 'type' => 'drop:iopts', 'divbox' => true, 'params' => $path) : 'hidden',
						'menu_intern'	=> ($action != 'PCP') ? 'hidden' : array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno',	'divbox' => true),
						'menu_target'	=> ($action != 'PCP') ? 'hidden' : array('validate' => INT,	'explain' => false,	'type' => 'radio:target',	'divbox' => true, 'params' => array(false, false, false)),
						'action'		=> 'hidden',
						'menu_order'	=> 'hidden',
					),
				);
				
				$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
				
				if ( $mode == 'create' && !$submit )
				{
					if ( $action != 'PCP' )
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
					#	'menu_lang'		=> 0,
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
					$data_sql = data(MENU, $data, false, 1, 'row');
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
						
						if ( $mode == 'create' && $userauth['A_MENU'] )
						{
							$data_sql['menu_order'] = _max(MENU, 'menu_order', "action = '$action' AND main = " . $data_sql['main']);
							
							$sql = sql(MENU, $mode, $data_sql);
							$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
						}
						else if ( $userauth['A_MENU'] )
						{
							$sql = sql(MENU, $mode, $data_sql, 'menu_id', $data);
							$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
				
			#	$option[] = href('a_txt', $file, ($data_sql['main'] ? array('main' => $data_sql['main']) : false), $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);

				$template->assign_vars(array(
					'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['menu_name']),
					'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
					
					'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				break;
				
			case 'delete':

				$data_sql = data(MENU, $data, false, 1, 'row');
				
				if ( $accept && $data )				
				{
					switch ( $data_sql['type'] )
					{
						case 0:	sql(MENU, $mode, $data_sql, 'menu_id', $data);	break;
						case 1:	sql(MENU, $mode, $data_sql, 'main', $data);	break;
					}
					
					$sql = sql(MENU, $mode, $data_sql, 'menu_id', $data);
					$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					
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
						'M_TITLE'	=> $lang['COMMON_CONFIRM'],
						'M_TEXT'	=> sprintf($lang["notice_confirm{$data_sql['type']}_delete"], $lang["confirm_{$data_sql['type']}"], langs($data_sql['menu_name'])),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				
				$template->pparse('confirm');

				break;
				
			case 'move_up':
			case 'move_down':
			
				move(MENU, $mode, $order, $main, $type, $usub, $action);
				log_add(LOG_ADMIN, $log, $mode);
				
            case 'display':
			
				$display = ( $action != 'PCP' ) ? 'display' : 'navigation';
				
				$template->assign_block_vars($display, array());

				$fields = isset($main) ? build_fields(array('mode' => 'create', 'main' => $main)) : build_fields(array('mode' => 'create'));
				$sqlout = data(MENU, "WHERE action = '$action'", 'main ASC, menu_order ASC', 1, 4);
				
			#	debug($sqlout, '$sqlout');
				
				if ( !$main )
				{
					$option[] = '';
					
					if ( !$sqlout['main'] )
					{
						$template->assign_block_vars($display . '.empty', array());
					}
					else
					{
						$max = count($sqlout['main']);

						foreach ( $sqlout['main'] as $row )
						{
							$menu_id	= $row['menu_id'];
							$menu_name	= langs($row['menu_name']);
                            $menu_order	= $row['menu_order'];
							
							$template->assign_block_vars($display . '.row', array(
								'NAME'		=> href('a_txt', $file, array('main' => $menu_id), $menu_name, $menu_name),
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $menu_id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $menu_id), 'icon_cancel', 'COMMON_DELETE'),

								'MOVE_UP'	=> ( $menu_order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $menu_order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $menu_order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $menu_order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
							));
						}
						
						$template->assign_block_vars($display . '.main', array());
					}
				}
                else
				{
					$main_info = sqlout_id($sqlout['main'], $main, 'menu_id');
					$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
					$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $main_info['id']), sprintf($lang['STF_UPDATE'], $lang['TYPE_0'], langs($main_info['name'])), langs($main_info['name']));
					
					if ( isset($sqlout['data_id'][$main]) )
					{
						$max_cnt = 0;
						$max_tmp = count($sqlout['data_id'][$main]);
						
						foreach ( $sqlout['data_id'][$main] as $row )
						{
							$main_id	= $row['menu_id'];
							$main_name	= langs($row['menu_name']);
                            $main_order	= $row['menu_order'];

							$template->assign_block_vars($display . '.row', array(
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $main_id), $main_name, $main_name),
								
								'MOVE_UP'	=> ( $main_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'order' => $main_order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $main_order != $max_tmp )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'order' => $main_order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),

								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $main_id), 'icon_cancel', 'COMMON_DELETE'),
								
								'S_NAME'	=> "menu_module[$main_id]",
								'S_SUBMIT'	=> "submit_module[$main_id]",
							));
							
							$max_cnt++;
							
							if ( $max_tmp != $max_cnt )
							{
								if ( $action != 'PCP' )
								{
									$template->assign_block_vars($display . '.row.br_empty', array());
								}
							}
							else
							{
								$template->assign_block_vars($display . '.row.br_empty2', array());
							}

							if ( $row['type'] == 1 )
							{
								if ( isset($sqlout['data_id'][$main_id]) )
								{
									$sub_max = count($sqlout['data_id'][$main_id]);
									
		                            foreach ( $sqlout['data_id'][$main_id] as $subrow )
									{
										$sub_id		= $subrow['menu_id'];
										$sub_name	= langs($subrow['menu_name']);
			                            $sub_order	= $subrow['menu_order'];
										
										$template->assign_block_vars($display . '.row.subrow', array(
											'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $sub_id), $sub_name, $sub_name),
											
											'MOVE_UP'	=> ( $sub_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'usub' => $main_id, 'type' => 2, 'order' => $sub_order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
											'MOVE_DOWN'	=> ( $sub_order != $sub_max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'usub' => $main_id, 'type' => 2, 'order' => $sub_order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
											
											'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $sub_id), 'icon_update', 'COMMON_UPDATE'),
											'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $sub_id), 'icon_cancel', 'COMMON_DELETE'),
										));
									}
								}
							}
						}
					}
					else
					{
						$template->assign_block_vars($display . '.empty', array());
					}
				}
				
				$template->assign_vars(array(
    				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang[$action]),
					'L_EXPLAIN'	=> $lang['EXPLAIN_' . $action],
					'L_NAME'	=> $lang['TYPE_0'],
					
					'CREATE'			=> sprintf($lang['STF_CREATE'], (( !$main ) ? $lang['TYPE_0'] : $lang['TYPE_1'])),
					'L_CREATE'			=> sprintf($lang['STF_CREATE'], $lang['TYPE_0']),
					'L_CREATE_LABEL'	=> sprintf($lang['STF_CREATE'], $lang['TYPE_1']),
					'L_CREATE_MODULE'	=> sprintf($lang['STF_CREATE'], $lang['TYPE_2']),
					
					'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
					
					'S_NAME'	=> $main ? 'menu_label' : 'menu_name',

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				break;
		}
	}
	$template->pparse($_tpl);
    acp_footer();
}

?>