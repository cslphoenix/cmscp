<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_downloads',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_downloads'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_downloads';
	
	include('./pagestart.php');
	
	add_lang('downloads');
	acl_auth(array('a_download', 'a_download_assort', 'a_download_create', 'a_download_delete'));

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
	$acp_title	= sprintf($lang['stf_header'], $lang['title']);

	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;

	$template->set_filenames(array(
		'body'		=> 'style/acp_downloads.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));

	/* $settings['download_types'] ??? */
	$mime_types = array('meta_application', 'meta_image', 'meta_text', 'meta_video');
	
	$base = ($settings['smain']['dl_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'delete'))) ? $mode : 'default';
	$_tpl = ($mode == 'delete') ? 'confirm' : 'body';

	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':

				$template->assign_block_vars('input', array());
				
				$vars = array(
					'dl' => array(
						'title'	=> 'data_input',
						'dl_name'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name'),
						'type'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',		'params' => array('combi', false, 'main')),
						'main'			=> array('validate' => INT,	'explain' => false,	'type' => $base,			'divbox' => true, 'params' => array(false, true, false)),
						'dl_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:25',	'divbox' => true, 'required' => array('input_desc', 'type', 0)),
						'dl_file'		=> array('validate' => INT,	'explain' => false,	'type' => 'upload:file',	'divbox' => true, 'required' => array('select_file', 'type', 1), 'params' => $dir_path),
						'dl_icon'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:icons',	'divbox' => true, 'params' => array(false, false, false)),
						'dl_types'		=> array('validate' => ARY,	'explain' => false,	'type' => 'select:types',	'divbox' => true),
						'dl_maxsize'	=> array('validate' => INT,	'explain' => true,	'type' => 'text:10;10',		'divbox' => true, 'required' => array('input_maxsize', 'type', 0)),
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
				
				if ( $mode == 'create' && !$submit && $userauth['a_download_create'] )
				{
					$name = ( isset($_POST['dl_name']) ) ? request('dl_name', TXT) : request('dl_filename', TXT);
					$type = ( isset($_POST['dl_name']) ) ? 0 : 1 ;
					
					$data_sql = array(
						'dl_name'		=> $name,
						'type'			=> $type,
						'main'			=> ($main ? $main : 0),
						'dl_desc'		=> '',
						'dl_file'		=> '',
						'dl_icon'		=> 0,
						'dl_types'		=> 'a:0:{}',
						'dl_maxsize'	=> 0,
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
					$data_sql = data(DOWNLOAD, $data, false, 1, true);
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
							
							debug($t_info, 't_info');
							
							$data_sql['dl_file'] = $t_info[0];
							$data_sql['dl_type'] = (isset($t_info[1])) ? $t_info[1] : $data_sql['dl_type'];
							$data_sql['dl_size'] = (isset($t_info[2])) ? $t_info[2] : $data_sql['dl_size'];
						}
						
						if ( $mode == 'create' && $userauth['a_download_create'] )
						{
							$data_sql['dl_order'] = maxa(DOWNLOAD, 'dl_order', 'main = ' . $data_sql['main']);
							$data_sql['dl_file'] =  ( !$data_sql['type'] ) ? create_folder($dir_path, 'dl_', true) : $data_sql['dl_file'];
							
							$sql = sql(DOWNLOAD, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else if ( $userauth['a_download'] )
						{
							$sql = sql(DOWNLOAD, $mode, $data_sql, 'dl_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
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
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['dl_name']),
					'L_EXPLAIN'	=> $lang['com_required'],
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));

				break;
			
			case 'delete':

				$data_sql = data(DOWNLOAD, $data, false, 1, true);

				if ( $data && $accept && $userauth['a_download_delete'] )
				{
					$sql = sql(DOWNLOAD, $mode, $data_sql, 'download_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					orders(DOWNLOAD);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data && !$accept && $userauth['a_download_delete'] )
				{
					$fields .= build_fields(array(
						'mode'	=> $mode,
						'id'	=> $data,
					));
					
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['dl_name']),

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
			
				if ( $userauth['a_download_assort'] )
				{
					move(DOWNLOAD, $mode, $order, $main, $type, $usub, $action);
					log_add(LOG_ADMIN, $log, $mode);
				}
				
			default:
			
				$template->assign_block_vars('display', array());
			
				$fields .= isset($main) ? build_fields(array('mode' => 'create', 'main' => $main)) : build_fields(array('mode' => 'create'));

				$down = data(DOWNLOAD, false, 'main ASC, dl_order ASC', 0, 4);
				
				if ( !$main )
				{
					if ( !$down['main'] )
					{
						$template->assign_block_vars('display.empty', array());
					}
					else
					{
						$max = count($down['main']);
				
						foreach ( $down['main'] as $row )
						{
							$dl_id		= $row['dl_id'];
							$dl_order	= $row['dl_order'];
							$dl_name	= $row['dl_name'];
							
							$template->assign_block_vars('display.row', array(
								'NAME'		=> href('a_txt', $file, array('main' => $dl_id), $dl_name, $dl_name),
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $dl_id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $dl_id), 'icon_cancel', 'com_delete'),
								
								'MOVE_UP'	=> ( $dl_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $dl_order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $dl_order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $dl_order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
							));
						}
					}
				}
				else
				{
					if ( isset($down['data_id'][$main]) )
					{
						foreach ( $down['data_id'][$main] as $row )
						{
							$dl_id		= $row['dl_id'];
							$dl_name	= $row['dl_name'];
							
							$template->assign_block_vars('display.row', array( 
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $dl_id), $dl_name, $dl_name),
								'DATE'		=> $row['change_file'],
								'INFO'		=> $row['change_user'],
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $dl_id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $dl_id), 'icon_cancel', 'com_delete'),
							));
						}
					}
					else
					{
						$template->assign_block_vars('display.empty', array());
					}
				}
				
				/*
			
				if ( $main )
				{
					$template->assign_block_vars('list', array());
					
					$tmp = data(DOWNLOAD, false, 'main ASC, dl_order ASC', 1, false);
					
					$cat = $dls = array();
					
					if ( $tmp )
					{
						foreach ( $tmp as $row )
						{
							if ( $row['dl_id'] == $main )
							{
								$cat = $row;
							}
							else if ( $row['main'] == $main )
							{
								$dls[] = $row;
							}
						}
						
						$cid = $cat['dl_id'];
					
						$template->assign_vars(array(
							'CAT'		=> href('a_txt', $file, array('action' => $action), $lang['acp_overview'], $lang['acp_overview']),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'com_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'com_delete'),
							
							'NAME'		=> isset($lang[$cat['dl_name']]) ? $lang[$cat['dl_name']] : $cat['dl_name'],
							
							'S_ACTION'	=> check_sid($file),
							'S_FIELDS'	=> $fields,
						));
					}
					
					if ( $dls )
					{
						$max = count($dls);
						
						foreach ( $dls as $row )
						{
							$id		= $row['dl_id'];
							$name	= isset($ang[$row['dl_name']]) ? $ang[$row['dl_name']] : $row['dl_name'];
							$order	= $row['dl_order'];
							
							$template->assign_block_vars('list.row', array(
								'NAME'          => href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
								
								'MOVE_UP'       => ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'     => ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
								'UPDATE'        => href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
								'DELETE'        => href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
							));
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
					
					$tmp = data(DOWNLOAD, 'WHERE type = 0', 'dl_order ASC', 1, false);
					
					if ( !$tmp )
					{
						$template->assign_block_vars('display.empty', array());
					}
					else
					{
						$cnt = count($tmp);
					
						for ( $i = 0; $i < $cnt; $i++ )
						{
							$dl_id		= $tmp[$i]['dl_id'];
							$dl_order	= $tmp[$i]['dl_order'];
							$dl_name	= $tmp[$i]['dl_name'];
							
							$template->assign_block_vars('display.row', array(
								'NAME'		=> href('a_txt', $file, array('main' => $dl_id), $dl_name, $dl_name),
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $dl_id), 'icon_update', 'com_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $dl_id), 'icon_cancel', 'com_delete'),
								
								'MOVE_UP'	=> ( $dl_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $dl_order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $dl_order != $cnt )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $dl_order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
							));
						}
					}
					
					$fields	.= '<input type="hidden" name="mode" value="create" />';
				}
				*/
				$template->assign_vars(array(
					'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
					'L_EXPLAIN'	=> $lang['explain'],
					
					'L_NAME'	=> $lang['type_0'],
					
					'L_CREATE'		=> sprintf($lang['stf_create'], $lang['type_0']),
					'L_CREATE_FILE'	=> sprintf($lang['stf_create'], $lang['type_1']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
			#	$template->pparse('body');
				
				/*
			
				$template->assign_block_vars('display', array());
			
				$fields .= '<input type="hidden" name="mode" value="create" />';
			
				$template->assign_vars(array(
					'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
					'L_CREATE'		=> sprintf($lang['stf_create'], $lang['file']),
					'L_CREATE_CAT'	=> sprintf($lang['stf_create'], $lang['cat']),
					
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['cat']),
					'L_EXPLAIN'		=> $lang['explain'],
			
					'S_CREATE'	=> check_sid("$file?mode=create"),
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			
				$max = maxi(DOWNLOADS_CAT, 'cat_order', '');
				$tmp = data(DOWNLOADS_CAT, '', '', 1, false);
				
				if ( $tmp )
				{
					$cnt = count($tmp);
					
					for ( $i = 0; $i < $cnt; $i++ )
					{
						$cid	= $tmp[$i]['cat_id'];
						$cname	= $tmp[$i]['cat_name'];
						$csize	= $tmp[$i]['cat_size'];
						$cfiles	= $tmp[$i]['cat_files'];
						$ctype	= unserialize($tmp[$i]['cat_type']);
						$corder	= $tmp[$i]['cat_order'];
						
						$typ = '';
						
						foreach ( $mime_types as $meta_type )
						{
							$_type = str_replace('meta', 'type', $meta_type);
			
							foreach ( $lang[$_type] as $type => $mime )
							{
								if ( in_array($type, $ctype) )
								{
									$typ[] = $type;
								}
							}
						}
						
						$typ = is_array($typ) ? implode(', ', $typ) : $typ;
						
						$template->assign_block_vars('display.row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update_cat', $cat => $cid), $cname, ''),
							
							'PATH'		=> $dir_path . $tmp[$i]['cat_path'],
							
							'TYPE'		=> $typ,
			
							'MOVE_UP'	=> ( $corder != '10' ) ? href('a_img', $file, array('mode' => 'order_cat', 'move' => '-15', $cat => $cid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $corder != $max ) ? href('a_img', $file, array('mode' => 'order_cat', 'move' => '+15', $cat => $cid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			
							'RESYNC'	=> $cfiles ? href('a_img', $file, array('mode' => 'rescny_cat', $cat => $cid), 'icon_resync', 'common_resync') : img('i_icon', 'icon_resync2', 'common_resync'),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update_cat', $cat => $cid), 'icon_update', 'com_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete_cat', $cat => $cid), 'icon_cancel', 'com_delete'),
							
							'S_NAME'	=> "file_name[$cid]",
							'S_SUBMIT'	=> "add_file[$cid]",
						));
						
						$files = data(DOWNLOADS, "cat_id = $cid", 'cat_id, file_order ASC', 1, false);
						
						$sql = "SELECT MAX(file_order) AS max$cid FROM " . DOWNLOADS . " WHERE cat_id = $cid";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						$max_file = $db->sql_fetchrow($result);
						
						if ( !$files )
						{
							$template->assign_block_vars('display.row.empty', array());
						}
						else
						{
							for ( $j = 0; $j < count($files); $j++ )
							{
								$file_id = $files[$j]['file_id'];
								
								if ( $files[$j]['cat_id'] == $cid )
								{
									$template->assign_block_vars('display.cat.file', array(
										'NAME'		=> $files[$j]['file_name'],
										
										'MOVE_UP'	=> ( $files[$j]['file_order'] != '10' )							? '<a href="' . check_sid("$file?mode=_order&amp;cat_type=$cid&amp;move=-15&amp;$url=$file_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
										'MOVE_DOWN'	=> ( $files[$j]['file_order'] != $max_file['max' . $cid] )	? '<a href="' . check_sid("$file?mode=_order&amp;cat_type=$cid&amp;move=+15&amp;$url=$file_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',
										
										'U_RESYNC' => check_sid("$file?mode=_resync&amp;$url=$file_id"),
										'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$file_id") . '"><img src="' . $images['icon_update'] . '" title="' . $lang['com_update'] . '" alt="" /></a>',
										'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$file_id") . '"><img src="' . $images['icon_cancel'] . '" title="' . $lang['com_delete'] . '" alt="" /></a>',
									));
								}
							}
						}
					}
				}
				*/
			
				break;
		}
		
		$template->pparse('body');
	}
	
	acp_footer();
}

?>