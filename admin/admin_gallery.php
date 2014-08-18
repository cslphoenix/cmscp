<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_gallery',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_gallery'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$update = ( isset($_POST['update']) ) ? true : false;

	$current = 'acp_gallery';
	
	include('./pagestart.php');
	
	add_lang('gallery');
	acl_auth(array('a_gallery', 'a_gallery_create', 'a_gallery_delete'));
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GALLERY;
	$time	= time();
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_gallery'];
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_gallery.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
#	debug($_POST, '_POST');
	
	$base = ($settings['smain']['gallery_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'upload', 'delete', 'overview'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'gallery' => array(
						'title'	=> 'data_input',
						'gallery_name'		=> array('validate' => TXT, 'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name', 'check' => true),
						'type'				=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('combi', false, 'main')),
						'main'				=> array('validate' => INT,	'explain' => false,	'type' => $base,		'divbox' => true, 'params' => array(false, true, false)),
						'copy'				=> array('validate' => INT,	'explain' => false,	'type' => 'drop:copy',	'divbox' => true),
						'gallery_acpview'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:acpview', 'divbox' => true),
						'gallery_picture'	=> array('validate' => INT,	'explain' => false,	'type' => 'upload:file', 'divbox' => true, 'required' => array('select_file', 'type', 1), 'params' => $dir_path),						'gallery_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:35;900', 'required' => 'input_desc'),
						'gallery_comment'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'gallery_rate'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'gallery_filesize'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:10;10', 'divbox' => true),
						'gallery_dimension'	=> array('validate' => INT,	'explain' => false,	'type' => 'double:4;5', 'divbox' => true),
						'gallery_format'	=> array('validate' => INT,	'explain' => false,	'type' => 'double:4;5', 'divbox' => true),
						'gallery_thumbnail'	=> array('validate' => INT,	'explain' => false,	'type' => 'double:4;5', 'divbox' => true),
						
					#	'gallery_picture'	=> 'hidden',
						'gallery_preview'	=> 'hidden',
						'gallery_path'		=> 'hidden',
						'gallery_uploader'	=> 'hidden',
						'time_create'		=> 'hidden',
						'time_update'		=> 'hidden',
						'time_upload'		=> 'hidden',
						'gallery_order'		=> 'hidden',
					)
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$type = ( !isset($_POST['gallery_name']) ) ? 1 : 0;
					
					$data_sql = array(
						'gallery_name'		=> request('gallery_name', TXT),
						'type'				=> $type,
						'main'				=> '',
						'copy'				=> '',
						'gallery_acpview'	=> $settings['gallery']['acpview'],
						'gallery_desc'		=> '',
						'gallery_comment'	=> '1',
						'gallery_rate'		=> '1',
						'gallery_uploader'	=> $userdata['user_id'],
						'gallery_dimension'	=> $settings['gallery']['dimension'],
						'gallery_format'	=> $settings['gallery']['format'],
						'gallery_filesize'	=> $settings['gallery']['filesize'],
						'gallery_thumbnail'	=> $settings['gallery']['preview'],
						'time_create'		=> $time,
						'time_update'		=> $time,
						'time_upload'		=> $time,
						'gallery_order'		=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(GALLERY_NEW, $data, false, 1, true);
					
					$template->assign_vars(array('L_OPTION' => href('a_txt', $file, array('id' => $data), $lang['common_overview'], $lang['common_overview'])));
					
				#	( $data_sql['gallery_pics'] ) ? $template->assign_block_vars('input.overview', array()) : false;
					
				#	$template->assign_block_vars('input.upload', array());
				}
				else
				{
					$data_sql = build_request(GALLERY_NEW, $vars, $error, $mode);
					
					if ( !$error )
					{
						if ( !$data_sql['type'] )
						{
							$data_sql['main'] = 0;
						}
						
					#	$data_sql['gallery_order'] = maxa(GALLERY_NEW, 'gallery_order', false);
						
						if ( $mode == 'create' )
						{
							$data_sql['gallery_path'] = create_folder($dir_path, 'gallery_', true);
							
							$sql = sql(GALLERY_NEW, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(GALLERY_NEW, $mode, $data_sql, 'gallery_id', $data);
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
				
				build_output(GALLERY_NEW, $vars, $data_sql);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['gallery_name']),
					'L_EXPLAIN'	=> $lang['com_required'],
					
					'L_UPLOAD'			=> $lang['common_upload'],
					'L_OVERVIEW'		=> $lang['common_overview'],
					
					'S_UPLOAD'			=> check_sid("$file&mode=upload&id=$data"),
					'S_OVERVIEW'		=> check_sid("$file&mode=overview&id=$data"),
					
					'S_ACTION'			=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'			=> $fields,
				));
				
				$template->pparse('body');
				
				break;
				
			case 'overview':
			
				if ( $submit )
				{
					echo 'submit';
					
					$data_sql = data(GALLERY_NEW, $data, false, 1, true);
					$data_pic = data(GALLERY_NEW, "WHERE main = $data", false, 1, false);
					
					$max_pics = count($data_pic);
					
					list($max_width, $max_height) = explode(':', $data_sql['gallery_dimension']);
					list($preview_width, $preview_height) = explode(':', $data_sql['gallery_thumbnail']);
					
					$pic_file = request_files('ufile');
					
					$nums = count($pic_file['temp']);
					
					if ( $nums > 0 )
					{
						for ( $i = 0; $i < $nums; $i++ )
						{
							$sql_pic[] = gallery_upload($data_sql['gallery_path'], $pic_file['temp'][$i], $pic_file['name'][$i], $pic_file['size'][$i], $pic_file['type'][$i], $max_width, $max_height, $data_sql['gallery_filesize'], $preview_width, $preview_height);
						}
						
						$sql = "UPDATE " . GALLERY_NEW . " SET gallery_picture = gallery_picture + $nums WHERE gallery_id = $main";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$db_ary = array();
						$pic_ary = array();
						
						$next = ( isset($max_pics) ) ? $max_pics : 0;
						
						for ( $i = 0; $i < $nums; $i++ )
						{
							$pic_ary[] = array(
								'gallery_name'		=> "'" . $_POST['title'][$i] . "'",
								'type'				=> 1,
								'main'				=> $main,
								'gallery_filesize'	=> "'" . $sql_pic[$i]['gallery_filesize'] . "'",
								'gallery_picture'	=> "'" . $sql_pic[$i]['gallery_picture'] . "'",
								'gallery_preview'	=> "'" . $sql_pic[$i]['gallery_preview'] . "'",
								'gallery_uploader'	=> $userdata['user_id'],
								'time_upload'		=> time(),
								'gallery_order'		=> $next,
							);
							$next += 1;
						}
										
						foreach ( $pic_ary as $id => $_pic_ary )
						{
							$values = array();
							foreach ( $_pic_ary as $key => $var )
							{
								$values[] = $var;
							}
							$db_ary[] = '(' . implode(', ', $values) . ')';
						}
						
						$sql = "INSERT INTO " . GALLERY_NEW . " (" . implode(', ', array_keys($pic_ary[0])) . ") VALUES " . implode(', ', $db_ary);
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$msg = $lang['upload'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&main=$main"));
						
						log_add(LOG_ADMIN, $log, $mode);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						$error[] = 'blubb';
						
						error('ERROR_BOX', $error);
					}
				}
				else if ( $update )
				{
					echo 'update';
				}
				
				$index = true;
				
				break;
			/*
			case 'overview':
			
				$template->assign_block_vars('overview', array());
				
				if ( $order )
				{
					update(GALLERY_NEW_PIC, 'pic', $move, $data_pic);
					orders(GALLERY_PIC, $data_id);
					
					log_add(LOG_ADMIN, $log, '_order_pic');
				}
				
				$data_gallery	= data(GALLERY_NEW, $data, false, 1, true);
				$data_pics		= data(GALLERY_NEW_PIC, $data_id, 'pic_order ASC', 1, false);
				
				$max = maxi(GALLERY_NEW_PIC, 'gallery_order', '');
				
				if ( $data_pics )
				{
					if ( $cat['preview_list'] )
					{
						$template->assign_block_vars('overview._list', array());
						
						for ( $i = $start; $i < min($cat['per_cols'] + $start, count($data_pics)); $i++ )
						{
							$pic_id	= $pic[$i]['pic_id'];
							$prev	= $dir_path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_preview'];
							$image	= $dir_path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture'];
							
							list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture']);
							
							$template->assign_block_vars('overview._list._gallery_row', array(
								'PIC_ID'	=> $pic[$i]['pic_id'],
								'TITLE'		=> ( $pic[$i]['pic_title'] ) ? $pic[$i]['pic_title'] : 'kein Titel',							
								'PREV'		=> $prev,
								'IMAGE'		=> $image,							
								'WIDTH'		=> $width,
								'HEIGHT'	=> $height,
								'NAME'		=> $pic[$i]['gallery_picture'],
								'SIZE'		=> size_file($pic[$i]['gallery_filesize']),
								
								'ORDER'		=> $pic[$i]['gallery_order'],
								
							#	'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							#	'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
								
								'MOVE_UP'	=> ( $pic[$i]['gallery_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
								'MOVE_DOWN'	=> ( $pic[$i]['gallery_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',
	
							));
						}
					}
					else
					{
						$template->assign_block_vars('overview._preview', array());
						
						for ( $i = $start; $i < min($cat['per_cols'] + $start, count($data_pics)); $i += $cat['per_rows'] )
						{
							if ( count($data_pics) > 0 )
							{
								$template->assign_block_vars('overview._preview._gallery_row', array());
							}
							
							for ( $j = $i; $j < ( $i + $cat['per_rows'] ); $j++ )
							{
								if ( $j >= count($data_pics) )
								{
									break;
								}
								
								$pic_id	= $pic[$i]['pic_id'];
								$prev	= $dir_path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_preview'];
								$image	= $dir_path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture'];
								
								list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture']);
								
								$template->assign_block_vars('overview._preview._galleryrow._gallery_col', array(
									'PIC_ID'	=> $pic[$i]['pic_id'],
									'TITLE'		=> $pic[$i]['pic_title'] ? $pic[$i]['pic_title'] : 'kein Titel',							
									'PREV'		=> $prev,
									'IMAGE'		=> $image,							
									'WIDTH'		=> $width,
									'HEIGHT'	=> $height,
									'NAME'		=> $pic[$i]['gallery_picture'],
									'SIZE'		=> size_file($pic[$i]['gallery_filesize']),
									
									'ORDER'		=> $pic[$i]['gallery_order'],

									'MOVE_UP'	=> ( $pic[$i]['gallery_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
									'MOVE_DOWN'	=> ( $pic[$i]['gallery_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',

								));
							}
						}
					}
					
					$current_page = ( !count($data_pics) ) ? 1 : ceil( count($data_pics) / $cat['per_cols'] );
			
					$template->assign_vars(array(
						'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $cat['per_cols'] ) + 1 ), $current_page ),
						'PAGINATION'	=> generate_pagination("$file?mode=_overview&$url=$data_id", count($data_pics), $cat['per_cols'], $start),
					));
				}
				else
				{
					$template->assign_block_vars('overview._entry_empty', array());
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['stf_head'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['stf_update'], $lang['gallery'], $cat['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['common_overview'],
					
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['pic']),
					'L_WIDTH'		=> $lang['pic_widht'],
					'L_HEIGHT'		=> $lang['pic_height'],
					'L_SIZE'		=> $lang['gallery_filesize'],
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['pic']),
					
					'PICS_PER_LINE'	=> $cat['per_rows'],
					'PREVIEW_WIDHT'	=> $cat['preview_widht'],
					
					'S_UPDATE'		=> check_sid("$file?mode=update&amp;$url=$data_id"),
					'S_UPLOAD'		=> check_sid("$file?mode=upload&amp;$url=$data_id"),
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( $submit )
				{
					$data = get_data(GALLERY_NEW, $data_id, 1);
					
					$pic_id		= request('pics', 1);
					$pic_title	= request('pic_title', 4);
					$pic_order	= request('gallery_order', 4);
					
					foreach ( $pic_order as $o_key => $o_value )
					{
						foreach ( $pic_title as $t_key => $t_value )
						{
							if ( $o_key == $t_key )
							{						
								$sql = "UPDATE " . GALLERY_NEW_PIC . " SET pic_title = '$t_value', pic_order = '$o_value'  WHERE pic_id = $o_key";
								if ( !$db->sql_query($sql) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							}
						}
					}
					
					if ( $pic_id )
					{
						$sql_in = implode(', ', $pic_id);
				
						$sql = "SELECT * FROM " . GALLERY_NEW_PIC . " WHERE pic_id IN ($sql_in) AND gallery_id = $data_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						$pic_data = $db->sql_fetchrowset($result);
						
						for ( $i = 0; $i < count($pic_id); $i++ )
						{
							gallery_delete($pic_data[$i]['gallery_picture'], $pic_data[$i]['gallery_preview'], $dir_path . $data['gallery_path'] . '/');
						}
						
						$sql = "DELETE FROM " . GALLERY_NEW_PIC . " WHERE pic_id IN ($sql_in) AND gallery_id = $data_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$sql = "UPDATE " . GALLERY_NEW . " SET gallery_pics = gallery_pics - " .  count($pic_id) . " WHERE gallery_id = $data_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					orders(GALLERY_NEW_PIC, $data_id);
					
					$message = $lang['update_gallery_pic']
						. sprintf($lang['click_return_gallery_pic'], '<a href="' . check_sid($file) . '">', '</a>')
						. sprintf($lang['return_update'], '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id"));
					log_add(LOG_ADMIN, $log, 'update_gallery_pic');
					message(GENERAL_MESSAGE, $message);
				}
				
				$template->pparse('body');
				
				break;
			
			case 'upload':
			
				$template->assign_block_vars('upload', array());
				
				$data_sql = data(GALLERY_NEW, $data, false, 1, true);
			#	$pics = data(GALLERY_NEW_PIC, $data_id, 'pic_order ASC', 1, false);
			#	$next = maxa(GALLERY_NEW_PIC, 'gallery_order', "gallery_id = $data_id");
				
				( $pics ) ? $template->assign_block_vars('upload._overview', array()) : false;
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['stf_head'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['stf_update'], $lang['gallery'], $data['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['common_overview'],
					
					'S_INPUT'		=> check_sid("$file?mode=update&amp;$url=$data_id"),
					'S_UPDATE'		=> check_sid("$file?mode=update&amp;$url=$data_id"),
					'S_OVERVIEW'	=> check_sid("$file?mode=overview&amp;$url=$data_id"),
					
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				if ( $submit )
				{
					$pic_file = request_files('ufile');
					
					$nums = count($pic_file['temp']);
					
					if ( $nums )
					{
						for ( $i = 0; $i < $nums; $i++ )
						{
							$sql_pic[] = gallery_upload($data['gallery_path'], $pic_file['temp'][$i], $pic_file['name'][$i], $pic_file['size'][$i], $pic_file['type'][$i], $data['max_width'], $data['max_height'], $data['max_filesize'], $data['preview_widht'], $data['preview_height']);
						}
						
					#	debug($sql_pic);
						
						$sql = "UPDATE " . GALLERY_NEW . " SET gallery_pics = gallery_pics + $nums WHERE gallery_id = $data_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$db_ary = array();
						$pic_ary = array();
						
						for ( $i = 0; $i < $nums; $i++ )
						{
							$pic_ary[] = array(
								'pic_title'		=> "'" . $_POST['title'][$i] . "'",
								'gallery_filesize'		=> "'" . $sql_pic[$i]['gallery_filesize'] . "'",
								'gallery_id'	=> $data_id,
								'gallery_picture'	=> "'" . $sql_pic[$i]['gallery_picture'] . "'",
								'gallery_preview'	=> "'" . $sql_pic[$i]['gallery_preview'] . "'",
								'upload_user'	=> $userdata['user_id'],
								'upload_time'	=> time(),
								'gallery_order'		=> $next,
							);
							$next += 10;
						}
										
						foreach ( $pic_ary as $id => $_pic_ary )
						{
							$values = array();
							foreach ( $_pic_ary as $key => $var )
							{
								$values[] = $var;
							}
							$db_ary[] = '(' . implode(', ', $values) . ')';
						}
						
						$sql = "INSERT INTO " . GALLERY_NEW_PIC . " (" . implode(', ', array_keys($pic_ary[0])) . ") VALUES " . implode(', ', $db_ary);
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$msg = $lang['update_u'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						
						log_add(LOG_ADMIN, $log, $mode);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						message(GENERAL_MESSAGE, 'test-error');
					}
				}
				
				$template->pparse('body');
			
				break;
			*/	
			case 'resync':
			
				$data_sql = data(GALLERY_NEW, $data, false, 1, true);
				
				if ( is_dir($dir_path . $data['gallery_path'] . '/') )
				{
					$pics_db = data(GALLERY_NEW_PIC, $data_id, false, 1, false);
					
					$path_files = scandir($dir_path . $data['gallery_path'] . '/');
						
					foreach ( $path_files as $files )
					{
						if ( $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' )
						{
							$pics_dir[] = $files;
						}
					}
					
					for ( $i = 0; $i < count($pics_db); $i++ )
					{
						foreach ( $pics_dir as $pic )
						{
							if ( $pics_db[$i]['gallery_picture'] == $pic )
							{
								$_ary_check[$pics_db[$i]['pic_id']]['gallery_picture'] = $pic;
							}
							else if ( $pics_db[$i]['gallery_preview'] == $pic )
							{
								$_ary_check[$pics_db[$i]['pic_id']]['gallery_preview'] = $pic;
							}
						}
					}
					
					$cnt = isset($_ary_check) ? count($_ary_check) : 0;
				}
				else
				{
					$data['gallery_path'] = create_folder($dir_path, 'gallery_', true);
					$data['gallery_pics'] = 0;
					
					$sql = sql(GALLERY_NEW, 'update', $data, 'gallery_id', $data);
				}
				
			#	$index = true;
				
				break;
				
			case 'delete':
			
				$data_sql = data(GALLERY_NEW, $data_id, false, 1, 1);

				if ( $data && $confirm )
				{	
					$sql = "DELETE FROM " . GALLERY_NEW . " WHERE gallery_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = "DELETE FROM " . GALLERY_NEW_PIC . " WHERE gallery_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					delete_folder($dir_path . $data['gallery_path'] . '/');
					
					$message = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['gallery_name']),
						
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
			
				move(GALLERY_NEW, $mode, $order, $main, $type, $usub, $action);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
		}
		
		if ( $index != true )
		{
			acp_footer();
			exit;
		}
	}
	
	$tmp = data(GALLERY_NEW, false, 'gallery_order ASC', 1, false);
	
	if ( $data )
	{
		$cat = $pic = $ids = array();
		
		$template->assign_block_vars('overview', array());
	
		foreach ( $tmp as $row )
		{
			if ( $row['gallery_id'] == $data )
			{
				$cat = $row;
			}
			else if ( $row['main'] == $data )
			{
				$ids[] = $row['gallery_id'];
				$pic[] = $row;
			}
		}
		
		if ( $settings['rating_gallery']['status'] )
		{
			$sql = "SELECT rate_type_id, rate_value, rate_userid, rate_userip FROM " . RATE . " WHERE rate_type = " . RATE_GALLERY . ( !empty($ids) ? " AND rate_type_id IN (" . implode(', ', $ids)  . ")" : "");
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$tmp_rate[$row['rate_type_id']][] = array(
					'userid' => $row['rate_userid'],
					'userip' => $row['rate_userip'],
					'value' => $row['rate_value']
				);
			}
		}
		
		list($per_rows, $per_cols) = explode(':', $cat['gallery_format']);
		
		$cid	= $cat['gallery_id'];
		$name	= $cat['gallery_name'];
		
		$template->assign_vars(array(
			'CAT'		=> href('a_txt', $file, array($file), $lang['acp_overview'], $lang['acp_overview']),
			'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $cid), $name, $name),
			'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'common_update'),
			'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'com_delete'),
			
			'RATE_LENGTH'	=> $settings['rating_gallery']['number'],
			'RATE_STEP'		=> $settings['rating_gallery']['fullstep'] ? true : false,
			'RATE_MAX'		=> $settings['rating_gallery']['maximal'],
			'RATE_TYPE'		=> $settings['rating_gallery']['images'] ? 'big' : 'small',
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
		
		if ( $pic )
		{
			$cnt = count($pic);
			
			if ( $cat['gallery_acpview'] )
			{
				$template->assign_block_vars('overview.list', array());
				
				for ( $i = $start; $i < min($per_cols + $start, $cnt); $i++ )
				{
					$gid = $pic[$i]['gallery_id'];
					
					list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture']);
					
					$rate_cnt = $rate_sum = $rate_ave = $average = 0;
			
					if ( $settings['rating_gallery']['status'] && $pic[$i]['gallery_rate'] )
					{
						$rate_cnt	= 0;
						$rate_ave	= 0;
						
						if ( isset($tmp_rate[$pic[$i]['gallery_id']]) )
						{
							$tmp_rate_ids = $tmp_rate[$pic[$i]['gallery_id']];
							
							$count_value = 0;
							$userip = $userid = '';
							
							foreach ( $tmp_rate_ids as $rows )
							{
								$count_value += $rows['value'];
							}
		
							$rate_cnt	= count($tmp_rate_ids);
							$rate_sum	= $count_value;
							$rate_ave	= round(($rate_sum/$rate_cnt), 1);
						}
						
						
					}
					
					$template->assign_block_vars('overview.info', array('ID' => $pic[$i]['gallery_id']));
					
					$order = $pic[$i]['gallery_order'];
					
					$template->assign_block_vars('overview.list.gallery_row', array(
						'PIC_ID'	=> $pic[$i]['gallery_id'],
						'TITLE'		=> $pic[$i]['gallery_name'] ? $pic[$i]['gallery_name'] : 'kein Titel',							
						'PREV'		=> $dir_path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_preview'],
						'IMAGE'		=> $dir_path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture'],							
						'WIDTH'		=> $width,
						'HEIGHT'	=> $height,
						'NAME'		=> $pic[$i]['gallery_picture'],
						'SIZE'		=> size_file($pic[$i]['gallery_filesize']),
						
						'R_SUM'		=> ( $rate_ave ) ? $rate_ave : 0,
						'RATING'	=> sprintf('%s %s &oslash; %s/%s', $rate_cnt, $lang['common_rating'], $rate_ave, $settings['rating_gallery']['maximal']),
						'COMMENT'	=> '',
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'usub' => $cid, 'type' => 1, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $order != $cnt )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'usub' => $cid, 'type' => 1, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
					));
				}
			}
			else
			{
				$template->assign_block_vars('overview.preview', array());
				
				for ( $i = $start; $i < min($per_cols + $start, $cnt); $i += $per_rows )
				{
					if ( $cnt > 0 )
					{
						$template->assign_block_vars('overview.preview.gallery_row', array());
					}
					
					for ( $j = $i; $j < ( $i + $per_rows ); $j++ )
					{
						if ( $j >= $cnt )
						{
							break;
						}
						
						$pic_id	= $pic[$i]['gallery_id'];
						$prev	= $dir_path . $cat['gallery_path'] . '/' . $pic[$j]['gallery_preview'];
						$image	= $dir_path . $cat['gallery_path'] . '/' . $pic[$j]['gallery_picture'];
						
						list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $cat['gallery_path'] . '/' . $pic[$j]['gallery_picture']);
						
						$template->assign_block_vars('overview.preview.gallery_row.gallery_col', array(
							'PIC_ID'	=> $pic[$j]['gallery_id'],
							'TITLE'		=> $pic[$j]['gallery_name'] ? $pic[$j]['gallery_name'] : 'kein Titel',							
							'PREV'		=> $prev,
							'IMAGE'		=> $image,							
						#	'WIDTH'		=> $width,
						#	'HEIGHT'	=> $height,
						#	'NAME'		=> $pic[$i]['gallery_picture'],
							'SIZE'		=> size_file($pic[$j]['gallery_filesize']),
							
						#	'ORDER'		=> $pic[$i]['gallery_order'],

						));
					}
				}
			}
			
			$current_page = ( !$cnt ) ? 1 : ceil( $cnt / $per_cols );
	
			$template->assign_vars(array(
				'L_RATING'		=> $lang['rating'],
				'L_COMMENT'		=> $lang['comment'],
			
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $per_cols ) + 1 ), $current_page ),
				'PAGINATION'	=> generate_pagination("$file&main=$main", $cnt, $per_cols, $start),
			));
		}
		else
		{
			$template->assign_block_vars('overview.empty', array());
		}
		
		$fields .= '<input type="hidden" name="mode" value="overview" />';
		$fields .= '<input type="hidden" name="id" value="' . $data . '" />';
		
		$template->assign_vars(array(
			'L_HEAD'	=> sprintf($lang['stf_overview'], $lang['title'], $cat['gallery_name']),
			'L_EXPLAIN'	=> $lang['explain'],
			
			'L_OPTION'	=> href('a_txt', $file, array('mode' => 'update', 'id' => $data), $lang['input_data'], $lang['input_data']),
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	else
	{
		$template->assign_block_vars('display', array());
		
		$fields .= '<input type="hidden" name="mode" value="create" />';
		
		if ( !$tmp )
		{
			$template->assign_block_vars('display.empty', array());
		}
		else
		{
			$cat = $pic = $pic_cnt = array();
			
			foreach ( $tmp as $row )
			{
				if ( !$row['type'] )
				{
					$cat[] = $row;
				}
				else if ( $row['type'] )
				{
					$pic[$row['main']][] = $row;
				}
			}
			
			foreach ( $cat as $row )
			{
				$pic_cnt[$row['gallery_id']] = @count($pic[$row['gallery_id']]);
			}		
			
		#	debug($cat);
		#	debug($pic);
		#	debug($pic_cnt);
			
			$cat_cnt = count($cat);
			
			for ( $i = $start; $i < min($settings['ppe_acp'] + $start, $cat_cnt); $i++ )
			{
				$id		= $cat[$i]['gallery_id'];
				$name	= $cat[$i]['gallery_name'];
				$desc	= $cat[$i]['gallery_desc'];
				$order	= $cat[$i]['gallery_order'];
				
				$size_pics = 0;
			
				if ( isset($pic_cnt[$id]) )
				{
					for ( $j = 0; $j < $pic_cnt[$id]; $j++ )
					{
						$size_pics += $pic[$id][$j]['gallery_filesize'];
					}
					
					$size_pics = _size($size_pics, 1);
				}
				else
				{
					$size_pics = size_dir($dir_path . $cat[$i]['gallery_path']);
				}
			
				$template->assign_block_vars('display.row', array(
					'NAME'	=> href('a_txt', $file, array('id' => $id), $name, $name),
					'INFO'	=> sprintf($lang['sprintf_size-pic'], $size_pics, $cat[$i]['gallery_picture']),
					'DESC'	=> $desc,
	
					'MOVE_UP'	=> ( $order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $order != $cat_cnt )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			
					'OVERVIEW'	=> isset($pic_cnt[$id]) ? href('a_img', $file, array('mode' => '_overview', 'id' => $id), 'icon_overview', 'common_overview') : img('i_icon', 'icon_overview2', 'common_overview'),
					'RESYNC'	=> isset($pic_cnt[$id]) ? href('a_img', $file, array('mode' => '_resync', 'id' => $id), 'icon_resync', 'common_resync') : img('i_icon', 'icon_resync2', 'common_resync'),
					
					'UPLOAD'	=> href('a_img', $file, array('mode' => 'upload', 'id' => $id), 'icon_upload', 'common_upload'),
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
				));
			}
		
			$current_page = ( !$cat_cnt ) ? 1 : ceil( $cat_cnt / $settings['per_page_entry']['acp'] );
			
			$template->assign_vars(array(
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['ppe_acp'] ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file?", $cat_cnt, $settings['ppe_acp'], $start ),
			));
		}
		
		$template->assign_vars(array(
			'L_HEAD'		=> sprintf($lang['stf_head'], $lang['title']),
			'L_CREATE'		=> sprintf($lang['stf_create'], $lang['title']),
			'L_EXPLAIN'		=> $lang['explain'],

			'S_CREATE'	=> check_sid("$file?mode=create"),
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	
	$template->pparse('body');

	acp_footer();
}

?>