<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_maps',
		'cat'       => 'clan',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_maps'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_maps';
	
	include('./pagestart.php');
	
	add_lang('maps');
	acl_auth(array('a_map_create', 'a_map_update', 'a_map_delete', 'a_map_assort', 'a_map_manage'));
	
	$error	= '';
	$index	= '';
	$fields = '';

	$log	= SECTION_MAPS;
	$file	= basename(__FILE__) . $iadds;

	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	
	$dir_path	= $root_path . $settings['path_maps'];
	$acp_title	= sprintf($lang['stf_header'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_maps.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));

	$mode = (in_array($mode, array('create', 'update', 'list', 'move_down', 'move_up', 'delete'))) ? $mode : 'display';
	$_tpl = ($mode == 'delete') ? 'confirm' : 'body';

	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'map' => array(
						'title' => 'data_input',
						'map_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name', 'check' => true),
						'type'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('combi', false, 'main')),
						'main'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:sub',	'divbox' => true, 'params' => array('ajax', true, 'map_tag')),
						'map_tag'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:tags',	'divbox' => true, 'params' => array($dir_path, GAMES, false), 'required' => array('select_tag', 'type', 0), 'check' => true),
						'map_file'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:file',	'divbox' => true, 'params' => $dir_path),
						'map_info'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'divbox' => true),
						'map_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$submit && $userauth['a_map_create'] )
				{
					$name = (isset($_POST['map_name'])) ? request('map_name', TXT) : request('cat_name', TXT);
					$type = (isset($_POST['map_name'])) ? 1 : 0;
					
					$data_sql = array(
						'map_name'	=> $name,
						'type'		=> $type,
						'main'		=> $main,
						'map_tag'	=> '',
						'map_info'	=> '',
						'map_file'	=> '',
						'map_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(MAPS, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(MAPS, $vars, $error, $mode);

					if ( !$error )
					{
						if ( $mode == 'create' && $userauth['a_map_update'] )
						{
							$data_sql['map_order'] = maxa(MAPS, 'map_order', $data['main']);
							
							(!$data_sql['type']) ? create_folder($dir_path, $data_sql['map_tag'], false) : '';
							
							$sql = sql(MAPS, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else if ( $userauth['a_game'] )
						{
							if ( !$data_sql['type'] && $_POST['current_tag'] != $data_sql['map_tag'] )
							{
								rename($dir_path . request('current_tag', TXT), $dir_path . $data_sql['map_tag']);
							}
														
							$sql = sql(MAPS, $mode, $data_sql, 'map_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						orders(MAPS, $data['main']);

						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				$tmp_cat = data(MAPS, 'WHERE main = 0', 'main ASC', 1, false);

				foreach ( $tmp_cat as $row )
				{
					$template->assign_block_vars('input.update_image', array(
						'NAME'  => $row['map_tag'],
						'PATH'  => $dir_path . $row['map_tag'],
					));
				}
				
				build_output(MAPS, $vars, $data_sql);

                $fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));

				if ( !$data_sql['type'] )
				{
                   $fields .= build_fields(array('current_tag' => $data_sql['map_tag']));
				}
				
#				$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
				$option[] = href('a_txt', $file, ($main ? array('main' => $data_sql['main']) : false), $lang['common_overview'], $lang['common_overview']);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['map_name']),
					'L_EXPLAIN'	=> $lang['com_required'],
					
					'L_OPTION'	=> implode($lang['com_bull'], $option),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				break;
				
			case 'delete':

				$data_sql = data(MAPS, $data, false, 1, true);

				if ( $data && $confirm )
				{
					$sql = sql(MAPS, $mode, $data_sql, 'map_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['map_name']),
						
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

			case 'move_up':
			case 'move_down':
			
				move(MAPS, $mode, $order, $main, $type, $usub);
				log_add(LOG_ADMIN, $log, $mode);
				
			case 'display':

                $template->assign_block_vars('display', array());

				$fields = isset($main) ? build_fields(array('mode' => 'create', 'main' => $main)) : build_fields(array('mode' => 'create'));
				$sqlout = data(MAPS, false, 'main ASC, map_order ASC', 1, 4);
				
			#	debug($sqlout);

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
							$main_id	= $row['map_id'];
							$main_cat	= $row['map_tag'];
							$main_name	= lang($row['map_name']);
                            $main_order	= $row['map_order'];

							$template->assign_block_vars('display.row', array(
       							'NAME'		=> href('a_txt', $file, array('main' => $main_id), $main_name, $main_name),
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $main_id), 'icon_cancel', 'com_delete'),

								'INFO'		=> $row['map_tag'],

								'MOVE_UP'	=> ( $main_order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $main_order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $main_order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $main_order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
							));
						}

						$template->assign_block_vars('display.main', array());
					}
				}
				else
				{
					if ( isset($sqlout['data_id'][$main]) )
					{
						$main_max = count($sqlout['data_id'][$main]);
						
						foreach ( $sqlout['data_id'][$main] as $row )
						{
							$main_id	= $row['map_id'];
							$main_name	= lang($row['map_name']);
                            $main_order	= $row['map_order'];

							$template->assign_block_vars('display.row', array(
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $main_id), $main_name, $main_name),
								
								'MOVE_UP'	=> ( $main_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'order' => $main_order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $main_order != $main_max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'order' => $main_order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
								
								'INFO'		=> sprintf('%s :: %s', $row['map_file'], $row['map_info']),

								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $main_id), 'icon_cancel', 'com_delete'),
							));
						}
					}
					else
					{
						$template->assign_block_vars('display.empty', array());
					}
				}
				
				if ( $main )
				{
					$main_info = sqlout_id($sqlout['main'], $main, 'map_id');
					$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
					$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $main_info['id']), sprintf($lang['stf_update'], $lang['main'], $main_info['name']), $main_info['name']);
				}
				else
				{
					$option[] = '';
				}

				$template->assign_vars(array(
					'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
					'L_CREATE'	=> sprintf($lang['stf_create'], ($main ? $lang['title'] : $lang['main'])),
					'L_NAME'	=> $lang['type_0'],
					'L_EXPLAIN'	=> $lang['explain'],
					'L_OPTION'	=> implode($lang['com_bull'], $option),
					
					'S_CREATE'	=> (!$main ? 'cat_name' : 'map_name'),
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