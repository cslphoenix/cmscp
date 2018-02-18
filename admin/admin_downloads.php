<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_DOWNLOADS',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'ACP_DOWNLOADS'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_DOWNLOADS';
	
	include('./pagestart.php');
	
	add_lang('downloads');
	acl_auth(array('A_DOWNLOAD', 'A_DOWNLOAD_ASSORT', 'A_DOWNLOAD_CREATE', 'A_DOWNLOAD_DELETE'));

	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_DOWNLOADS;
	$time	= time();
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$cat	= request('cat', TYP);
	$sub	= request('sub', TYP);
	$subs	= request('subs', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$usub	= request('usub', TYP);
	
	$dir_path	= $root_path . $settings['path_downloads'];
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);

	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;

	$template->set_filenames(array('body' => "style/$current.tpl"));

	/* $settings['download_types'] ??? */
	$mime_types = array('meta_application', 'meta_image', 'meta_text', 'meta_video');
	
	$base = ($settings['smain']['dl_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'delete'))) ? $mode : 'default';
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';

	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':

				$template->assign_block_vars('input', array());
				
				$vars = array(
					'dl' => array(
						'title'	=> 'INPUT_DATA',
						'dl_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name'),
						'type'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',		'params' => array('combi', false, 'main')),
						'main'			=> array('validate' => INT,	'explain' => false,	'type' => $base,			'divbox' => true, 'params' => array(false, true, false)),
						'dl_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:25',	'divbox' => true, 'required' => array('input_desc', 'type', 0)),
						'dl_file'		=> array('validate' => INT,	'explain' => false,	'type' => 'upload:file',	'divbox' => true, 'required' => array('select_file', 'type', 1), 'params' => $dir_path),
						'dl_icon'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:icons',	'divbox' => true, 'params' => array(false, false, false)),
						'dl_types'		=> array('validate' => ARY,	'explain' => false,	'type' => 'select:types',	'divbox' => true),
						'dl_maxsize'	=> array('validate' => INT,	'explain' => true,	'type' => 'text:10;10',		'divbox' => true, 'required' => array('input_maxsize', 'type', 0), 'opt' => array('drop')),
						'dl_rate'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno',	'divbox' => true),
						'dl_comment'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno',	'divbox' => true),
						'dl_type'		=> 'hidden',
						'dl_size'		=> 'hidden',
						'dl_uploader'	=> 'hidden',
						'time_create'	=> 'hidden',
						'time_upload'	=> 'hidden',
						'time_update'	=> 'hidden',
						'dl_order'		=> 'hidden',
					)
				);
				
				if ( $mode == 'create' && !$submit && $userauth['A_DOWNLOAD_CREATE'] )
				{
					$name = ( isset($_POST['dl_name']) ) ? request('dl_name', TXT) : request('dl_filename', TXT);
					$type = ( isset($_POST['dl_name']) ) ? 0 : 1;
					
					$data_sql = array(
						'dl_name'		=> $name,
						'type'			=> $type,
						'main'			=> ($main ? $main : 0),
						'dl_desc'		=> '',
						'dl_file'		=> '',
						'dl_icon'		=> 0,
						'dl_types'		=> 'a:0:{}',
						'dl_maxsize'	=> '0;2',
						'dl_size'		=> '',
						'dl_rate'		=> 1,
						'dl_comment'	=> 1,
						'dl_type'		=> '',
						'dl_uploader'	=> $userdata['user_id'],
						'time_create'	=> $time,
						'time_upload'	=> $time,
						'time_update'	=> $time,						
						'dl_order'		=> 0,						
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(DOWNLOAD, $data, false, 1, 'row');
				}
				else
				{
					$data_sql = build_request(DOWNLOAD, $vars, $error, $mode);
					
					if ( !$error )
					{
						if ( !$data_sql['type'] )
						{
							$data_sql['main'] = 0;
						}
						else
						{
							$t_info = @explode(';', $data_sql['dl_file']);
							
						#	debug($t_info, 't_info');
							
							$data_sql['dl_file'] = $t_info[0];
							$data_sql['dl_type'] = (isset($t_info[1])) ? $t_info[1] : $data_sql['dl_type'];
							$data_sql['dl_size'] = (isset($t_info[2])) ? $t_info[2] : $data_sql['dl_size'];
						}
						
						if ( $mode == 'create' && $userauth['A_DOWNLOAD_CREATE'] )
						{
							$data_sql['dl_order'] = _max(DOWNLOAD, 'dl_order', 'main = ' . $data_sql['main']);
							$data_sql['dl_file'] =  ( !$data_sql['type'] ) ? create_folder($dir_path, 'dl_', true) : $data_sql['dl_file'];
							
							$sql = sql(DOWNLOAD, $mode, $data_sql);
							$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
						}
						else if ( $userauth['A_DOWNLOAD'] )
						{
							$sql = sql(DOWNLOAD, $mode, $data_sql, 'dl_id', $data);
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
				
				build_output(DOWNLOAD, $vars, $data_sql);
				
				$template->assign_vars(array(
					'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['dl_name']),
					'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));

				break;
			
			case 'delete':

				$data_sql = data(DOWNLOAD, $data, false, 1, 'row');

				if ( $data && $accept && $userauth['A_DOWNLOAD_DELETE'] )
				{
					$sql = sql(DOWNLOAD, $mode, $data_sql, 'download_id', $data);
					$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);

					orders(DOWNLOAD);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data && !$accept && $userauth['A_DOWNLOAD_DELETE'] )
				{
					$fields .= build_fields(array(
						'mode'	=> $mode,
						'id'	=> $data,
					));
					
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['COMMON_CONFIRM'],
						'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['dl_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
				}

				$template->pparse('confirm');

				break;
				
			case 'move_up':
			case 'move_down':
			
				if ( $userauth['A_DOWNLOAD_ASSORT'] )
				{
					move(DOWNLOAD, $mode, $order, $main, $type, $usub, $action);
					log_add(LOG_ADMIN, $log, $mode);
				}
				
			default:
			
				$template->assign_block_vars('display', array());
			
				$fields = isset($main) ? build_fields(array('mode' => 'create', 'main' => $main)) : build_fields(array('mode' => 'create'));
				$sqlout = data(DOWNLOAD, false, 'main ASC, dl_order ASC', 0, 4);
				
				if ( !$main )
				{
					$option[] = '';
					
					if ( !$sqlout['main'] )
					{
						$template->assign_block_vars('display.none', array());
					}
					else
					{
						$max = count($sqlout['main']);
				
						foreach ( $sqlout['main'] as $row )
						{
							$dl_id		= $row['dl_id'];
							$dl_order	= $row['dl_order'];
							$dl_name	= $row['dl_name'];
							$types		= ( $row['dl_types'] != 'a:1:{i:0;s:0:"";}') ? implode(', ', unserialize($row['dl_types'])) : $lang['DL_ALL_FILES'];
							
							$template->assign_block_vars('display.row', array(
								'NAME'		=> href('a_txt', $file, array('main' => $dl_id), $dl_name, $dl_name),
								'TYPES'		=> sprintf($lang['STF_SELECT_MENU2'], $types),
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $dl_id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $dl_id), 'icon_cancel', 'COMMON_DELETE'),
								
								'MOVE_UP'	=> ( $dl_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $dl_order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $dl_order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $dl_order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
							));
						}
					}
				}
				else
				{
					$main_info = sqlout_id($sqlout['main'], $main, 'dl_id');
					$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
					$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $main_info['id']), sprintf($lang['STF_UPDATE'], $lang['TYPE_0'], $main_info['name']), $main_info['name']);
					
					if ( isset($sqlout['data_id'][$main]) )
					{
						foreach ( $sqlout['data_id'][$main] as $row )
						{
							$dl_id		= $row['dl_id'];
							$dl_name	= $row['dl_name'];
							
							$template->assign_block_vars('display.row', array( 
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $dl_id), $dl_name, $dl_name),
								'DATE'		=> $row['dl_file'],
								'INFO'		=> $row['dl_uploader'],
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $dl_id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $dl_id), 'icon_cancel', 'COMMON_DELETE'),
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
					'L_EXPLAIN'	=> $lang['EXPLAIN'],
					
					'L_NAME'	=> (!$main ? $lang['TYPE_0'] : $lang['TYPE_1']),
					'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
					'L_CREATE'	=> sprintf($lang['STF_CREATE'], ( !$main ? $lang['TYPE_0'] : $lang['TYPE_1'])),

					'S_CREATE'	=> (!$main ? 'dl_name' : 'dl_filename'),					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				break;
		}
		$template->pparse($_tpl);
	}
	acp_footer();
}

?>