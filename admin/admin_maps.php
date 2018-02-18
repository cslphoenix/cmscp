<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_MAPS',
		'CAT'		=> 'CLAN',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'ACP_MAPS',	'AUTH'	=> 'A_MAP'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$current = $log = 'ACP_MAPS';
	
	include('./pagestart.php');
	
	add_lang('maps');
	add_tpls('acp_maps');
	acl_auth('A_MAP');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$file	= basename(__FILE__) . $iadds;
	$path	= $root_path . $settings['path']['maps'];
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$cancel = ( isset($_POST['cancel']) ) ? redirect('admin/' . check_sid($file)) : false;
	
	$mode	= ( in_array($mode, array('create', 'delete', 'list', 'export', 'move_down', 'move_up', 'update', 'upload')) ) ? $mode : false;
	
	$_top	= sprintf($lang['STF_HEADER'], $lang['TITLE']);
	$_tpl	= ($mode === 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'map' => array(
					'title'		=> 'INPUT_DATA',
					'map_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name', 'check' => true),
					'type'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('combi', false, 'main')),
					'main'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:sub',	'divbox' => true, 'params' => array('ajax', true, 'map_tag'), 'required' => array('main', 'type', 1)),
					'map_tag'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:tags',	'divbox' => true, 'params' => array($path, GAMES, false), 'required' => array('select_tag', 'type', 0), 'check' => true),
					'map_file'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:file',	'divbox' => true, 'params' => $path),
					'map_info'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'divbox' => true),
					'map_order'	=> 'hidden',
				),
			);
			
			if ( $mode == 'create' && !$submit && $userauth['A_MAP'] )
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
				$data_sql = data(MAPS, $data, false, 1, 'row');
			}
			else
			{
				$data_sql = build_request(MAPS, $vars, $error, $mode);
				
				if ( !$error )
				{
					if ( $mode == 'create' && $userauth['A_MAP'] )
					{
						$data_sql['map_order'] = _max(MAPS, 'map_order', " main = " . $data_sql['main']);
						
						(!$data_sql['type']) ? create_folder($path, $data_sql['map_tag'], false) : '';
						
						$sql = sql(MAPS, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else if ( $userauth['A_MAP'] )
					{
						if ( !$data_sql['type'] && $_POST['current_tag'] != $data_sql['map_tag'] )
						{
							rename($path . request('current_tag', TXT), $path . $data_sql['map_tag']);
						}
													
						$sql = sql(MAPS, $mode, $data_sql, 'map_id', $data);
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					}
					
				#	orders(MAPS, $data['main']);
				
			#	debug($sql, 'sql');

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			$tmp_cat = data(MAPS, 'WHERE main = 0', 'main ASC', 1, 'set');
			
			foreach ( $tmp_cat as $row )
			{
				$template->assign_block_vars('input.update_image', array(
					'NAME'  => $row['map_tag'],
					'PATH'  => $path . $row['map_tag'],
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
			
			$option[] = href('a_txt', $file, ($main || $data_sql['main']  ? array('main' => $data_sql['main']) : false), $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['map_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
			
		case 'upload':
		
			$template->assign_block_vars($mode, array());
			
			$data_sql = data(MAPS, "WHERE map_id = $data", false, 1, 'row');

			if ( $submit )
			{
				$map_file	= request_files('ufile');
				$map_name	= request('map_name', ARY);
				$map_order	= _max(MAPS, 'map_order', "main = $data");
				
				if ( $map_name )
				{
					for ( $i = 0; $i < count($map_name); $i++ )
					{
						if ( $map_name[$i] )
						{
							if ( $map_file['temp'][$i] )
							{
								$pic_ary[$map_name[$i]] = upload_image($mode, 'map_pic', '', false, '', '', $path . $data_sql['map_tag'], array('temp' => $map_file['temp'][$i], 'name' => $map_file['name'][$i], 'type' => $map_file['type'][$i], 'size' => $map_file['size'][$i]), $error);
							}
						}
					}
					
					$map_ary = '';
					
					for ( $i = 0; $i < count($map_name); $i++ )
					{
						if ( $map_name[$i] )
						{
							$map_ary[] = array(
								'main'		=> $data,
								'type'		=> 1,
								'map_name'	=> $map_name[$i],
								'map_file'	=> isset($pic_ary[$map_name[$i]]) ? $pic_ary[$map_name[$i]] : '',
								'map_order'	=> $map_order,
							);
							$map_order += 1;
						}
					}
					
					if ( $map_ary )
					{
						for ( $i = 0; $i < count($map_ary); $i++ )
						{
							$_sql[] = sql(MAPS, 'create', $map_ary[$i]);
						}
					}
					else
					{
						$error[] = ( $error ? '<br />' : '' ) . $lang['NOTICE_SELECT_MAP'];
					}
					
					if ( !$error )
					{
						$lang_type = 'create_map';
					
						log_add(LOG_ADMIN, $log, $smode, $_sql);
						right('ERROR_BOX', langs($lang_type));
					}
					else
					{
						error('ERROR_BOX_UPLOAD', $error);
						log_add(LOG_ADMIN, $log, 'error', $error);
					}
				}
			}
			
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
			#	'L_INPUT'	=> sprintf($lang['STF_' . strtoupper($mode)], $lang['TITLE']),
									
				'S_ACTION'	=> check_sid("$file"),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'list':
		
			$template->assign_block_vars($mode, array());
			
			$data_cat = data(MAPS, "WHERE map_id = $data", false, 1, 'row');
			
			$vars = array(
				'map_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name', 'check' => true),
				'map_file'	=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:file', 'params' => $path . $data_cat['map_tag']),
				'map_info'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25'),
			);
			
			if ( $mode == 'list' && !$submit )
			{
				$data_sql = data(MAPS, "WHERE main = $data", false, 1, 'set');
			}
			else
			{
				$data_sql = build_request_list(MAPS, $vars, $error, 'map_id');
			
				if ( !$error )
				{
					if ( $data_sql )
					{
						foreach ( $data_sql as $key => $row )
						{
							foreach ( $row as $name => $info )
							{
								$ary[$key][] = "$name = '$info'";
							}
							$implode = implode(', ', $ary[$key]);
						
							$sql = "UPDATE " . MAPS . " SET $implode WHERE map_id = $key";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
						
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					}
					else
					{
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					}
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output_list(MAPS, $vars, $data_sql, $mode);
						
			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
			#	'L_INPUT'	=> sprintf($lang['STF_' . strtoupper($mode)], $lang['TITLE']),
									
				'S_ACTION'	=> check_sid("$file"),
				'S_FIELDS'	=> $fields,
			));
		
			break;
		
		/*	
		case 'export':
		
			$sql = "SELECT *
				FROM " . MAPS . "
				ORDER BY map_order";
			$result = $db->sql_query($sql);

			$pak = '';
			while ($row = $db->sql_fetchrow($result))
			{
				$pak .= "'" . addslashes($row[$fields . '_url']) . "', ";
				$pak .= "'" . addslashes($row[$fields . '_width']) . "', ";
				$pak .= "'" . addslashes($row[$fields . '_height']) . "', ";
				$pak .= "'" . addslashes($row['display_on_posting']) . "', ";

				if ($mode == 'smilies')
				{
					$pak .= "'" . addslashes($row['emotion']) . "', ";
					$pak .= "'" . addslashes($row['code']) . "', ";
				}

				$pak .= "\n";
			}
			$db->sql_freeresult($result);
			
		#	$msg = 'test';

		#	log_add(LOG_ADMIN, $log, $mode);
		#	message(GENERAL_MESSAGE, $msg);
		
			break;
		*/
		
		case 'delete':
		
			$del = array(
				'field' => 'map_id',
				'table'	=> MAPS,
				'name'	=> 'map_name'
			);
			
			$sqlout = data($del['table'], $data, false, 1, 'row');
			
			if ( $data && $accept && $sqlout )
			{
				$sql = sql($del['table'], $mode, $sqlout, $del['field'], $data);
				$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
				
				if ( $sqlout['type'] == 0 )
				{
					orders($del['table'], 'main', 1);
					delete_folder($path . $sqlout['map_tag'] . '/');
				}
				else
				{
					orders($del['table'], 'main', $sqlout['main']);
					/* unlink für dateien noch einrichten */
				}

				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data && !$accept )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $sqlout[$del['name']]),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['MAIN']));
			}
			
			break;

		case 'move_up':
		case 'move_down':
		
			move(MAPS, $mode, $order, $main, $type, $usub);
			log_add(LOG_ADMIN, $log, $mode);
			
		default:
		
			$template->assign_block_vars('display', array());
			
			$fields = isset($main) ? build_fields(array('mode' => 'create', 'main' => $main)) : build_fields(array('mode' => 'create'));
			$sqlout = data(MAPS, false, 'main ASC, map_order ASC', 1, 4);

			if ( !$main )
			{
				$option[] = '';
				
				if ( !$sqlout['main'] )
				{
					$template->assign_block_vars('display.none', array());
				}
				else
				{
					$cnt = count($sqlout['main']);
					$max = count($sqlout['data_id']);
					
					foreach ( $sqlout['main'] as $row )
					{
						$main_id	= $row['map_id'];
						$main_cat	= $row['map_tag'];
						$main_name	= langs($row['map_name']);
						$main_order	= $row['map_order'];

						$template->assign_block_vars('display.row', array(
							'NAME'		=> href('a_txt', $file, array('main' => $main_id), $main_name, $main_name),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $main_id), 'icon_cancel', 'COMMON_DELETE'),

							'INFO'		=> $row['map_tag'],

							'MOVE_UP'	=> ( $main_order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $main_order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
							'MOVE_DOWN'	=> ( $main_order != $cnt )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $main_order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
						));
					}

					$template->assign_block_vars('display.main', array());
					
				}
			}
			else
			{
				$main_info = sqlout_id($sqlout['main'], $main, 'map_id');
				$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
				$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $main_info['id']), sprintf($lang['STF_UPDATE'], $lang['TYPE_0'], $main_info['name']), $main_info['name']);
				$option[] = href('a_txt', $file, array('mode' => 'upload', 'id' => $main_info['id']), sprintf($lang['STF_UPLOAD'], $lang['TYPE_0'], $main_info['name']), $main_info['name']);
									
				if ( isset($sqlout['data_id'][$main]) )
				{
					$option[] = href('a_txt', $file, array('mode' => 'list', 'id' => $main_info['id']), sprintf($lang['STF_LIST'], $lang['TYPE_0'], $main_info['name']), $main_info['name']);
				#	$option[] = href('a_txt', $file, array('mode' => 'export', 'id' => $main_info['id']), sprintf($lang['STF_LIST'], $lang['TYPE_0'], $main_info['name']), $main_info['name']);
					
					$cnt = count($sqlout['data_id'][$main]);
					
					foreach ( $sqlout['data_id'][$main] as $row )
					{
						$main_id	= $row['map_id'];
						$main_name	= langs($row['map_name']);
						$main_order	= $row['map_order'];

						$template->assign_block_vars('display.row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $main_id), $main_name, $main_name),
							
							'MOVE_UP'	=> ( $main_order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'order' => $main_order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
							'MOVE_DOWN'	=> ( $main_order != $cnt )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'order' => $main_order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
							
							'INFO'		=> sprintf('%s :: %s', $row['map_file'], $row['map_info']),

							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $main_id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
				else
				{
					$template->assign_block_vars('display.none', array());
				}					
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], (!$main ? $lang['TYPE_0'] : $lang['TYPE_1'])),
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				'L_NAME'	=> (!$main ? $lang['TYPE_0'] : $lang['TYPE_1']),
				
				'S_CREATE'	=> (!$main ? 'cat_name' : 'map_name'),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

		break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>