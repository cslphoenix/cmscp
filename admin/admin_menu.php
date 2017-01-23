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
	$acp_title	= sprintf($lang['stf_header'], $lang[$action]);
	
	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_menu.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));

	$base = ($settings['smain']['menu_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'delete'))) ? $mode : 'display';
	$_tpl = ($mode == 'delete') ? 'confirm' : 'body';
	
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
				
				if ( $mode == 'create' && !$submit && $userauth['a_menu'] )
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
						
						if ( $mode == 'create' && $userauth['a_menu'] )
						{
							$data_sql['menu_order'] = maxa(MENU, 'menu_order', "action = '$action' AND main = " . $data_sql['main']);
							
							$sql = sql(MENU, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else if ( $userauth['a_menu'] )
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
				
				$option[] = href('a_txt', $file, ($main ? array('main' => $data_sql['main']) : false), $lang['common_overview'], $lang['common_overview']);

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], lang($data_sql['menu_name'])),
					'L_EXPLAIN'	=> $lang['com_required'],
					
					'L_OPTION'	=> implode($lang['com_bull'], $option),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				break;
				
			case 'delete':

				$data_sql = data(MENU, $data, false, 1, true);
				
				if ( $accept && $data )				
				{
					switch ( $data_sql['type'] )
					{
						case 0:
						
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
				
			case 'move_up':
			case 'move_down':
			
				move(MENU, $mode, $order, $main, $type, $usub, $action);
				log_add(LOG_ADMIN, $log, $mode);
				
            case 'display':

				$template->assign_block_vars('display', array());

				$fields .= isset($main) ? build_fields(array('mode' => 'create', 'main' => $main)) : build_fields(array('mode' => 'create'));
				$sqlout = data(MENU, "WHERE action = '$action'", 'main ASC, menu_order ASC', 1, 4);

				if ( !$main )
				{
					if ( !$sqlout['main'] )
					{
						$template->assign_block_vars('display.empty', array());
					}
					else
					{
						$max = count($sqlout['main']);

						foreach ( $sqlout['main'] as $row )
						{
							$menu_id	= $row['menu_id'];
							$menu_name	= lang($row['menu_name']);
                            $menu_order	= $row['menu_order'];

							$template->assign_block_vars('display.row', array(
								'NAME'		=> href('a_txt', $file, array('main' => $menu_id), $menu_name, $menu_name),
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $menu_id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $menu_id), 'icon_cancel', 'com_delete'),

								'MOVE_UP'	=> ( $menu_order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $menu_order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $menu_order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $menu_order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
							));
						}
						
						$template->assign_block_vars('display.main', array());
					}
				}
                else
				{
					if ( isset($sqlout['data_id'][$main]) )
					{
						$max_cnt = 0;
						$max_tmp = count($sqlout['data_id'][$main]);
						
						foreach ( $sqlout['data_id'][$main] as $row )
						{
							$main_id	= $row['menu_id'];
							$main_name	= lang($row['menu_name']);
                            $main_order	= $row['menu_order'];

							$template->assign_block_vars('display.row', array(
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $main_id), $main_name, $main_name),
								
								'MOVE_UP'	=> ( $main_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'order' => $main_order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $main_order != $max_tmp )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'order' => $main_order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $main_id), 'icon_cancel', 'com_delete'),
								
								'S_NAME'	=> "menu_module[$main_id]",
								'S_SUBMIT'	=> "submit_module[$main_id]",
								
							#	'S_NAME'	=> "subforum_name[$main_id]",
							#	'S_SUBMIT'	=> "subforum_submit[$main_id]",
							));
							
							$max_cnt++;
							
							if ( $max_tmp != $max_cnt )
							{
								$template->assign_block_vars('display.row.br_empty', array());
							}
							else
							{
								$template->assign_block_vars('display.row.br_empty2', array());
							}

							if ( $row['type'] == 1 )
							{
								if ( isset($sqlout['data_id'][$main_id]) )
								{
									$sub_max = count($sqlout['data_id'][$main_id]);
									
		                            foreach ( $sqlout['data_id'][$main_id] as $subrow )
									{
										$sub_id		= $subrow['menu_id'];
										$sub_name	= lang($subrow['menu_name']);
			                            $sub_order	= $subrow['menu_order'];
										
										$template->assign_block_vars('display.row.subrow', array(
											'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $sub_id), $sub_name, $sub_name),
											
											'MOVE_UP'	=> ( $sub_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'usub' => $main_id, 'type' => 2, 'order' => $sub_order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
											'MOVE_DOWN'	=> ( $sub_order != $sub_max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'usub' => $main_id, 'type' => 2, 'order' => $sub_order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
											
											'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $sub_id), 'icon_update', 'com_update'),
											'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $sub_id), 'icon_cancel', 'com_delete'),
										));
									}
									$template->assign_block_vars('display.row.subrow.sub', array());
								}
							}
						}
						$template->assign_block_vars('display.label', array());
					}
					else
					{
						$template->assign_block_vars('display.empty', array());
					}
				}
				
				if ( $main )
				{
					$main_info = sqlout_id($sqlout['main'], $main, 'menu_id');
					$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
					$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $main_info['id']), sprintf($lang['stf_update'], $lang['main'], lang($main_info['name'])), lang($main_info['name']));
				}
				else
				{
					$option[] = '';
				}
				
				debug($main, 'main');

                $template->assign_vars(array(
    				'L_HEAD'	=> sprintf($lang['stf_header'], $lang[$action]),
					'L_EXPLAIN'	=> $lang['explain_' . $action],
					'L_NAME'	=> $lang['type_0'],
					
					'CREATE'			=> sprintf($lang['stf_create'], (( !$main ) ? $lang['type_0'] : $lang['type_1'])),

					'L_CREATE'			=> sprintf($lang['stf_create'], $lang['type_0']),
					'L_CREATE_LABEL'	=> sprintf($lang['stf_create'], $lang['type_1']),
					'L_CREATE_MODULE'	=> sprintf($lang['stf_create'], $lang['type_2']),
					
					'L_OPTION'	=> implode($lang['com_bull'], $option),
					
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