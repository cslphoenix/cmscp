<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_GALLERY',
		'CAT'		=> 'SITE',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'ACP_GALLERY',
			'AUTH'	=> array('A_GALLERY', 'A_GALLERY_ASSORT', 'A_GALLERY_CREATE', 'A_GALLERY_DELETE')),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$update = ( isset($_POST['update']) ) ? true : false;

	$current = 'ACP_GALLERY';
	
	include('./pagestart.php');
	
	add_lang('gallery');
	acl_auth(array('A_GALLERY', 'A_GALLERY_ASSORT', 'A_GALLERY_CREATE', 'A_GALLERY_DELETE'));

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$time	= time();
	$log	= SECTION_GALLERY;
	$file	= basename(__FILE__) . $iadds;
	$path	= $root_path . $settings['path']['gallery'];
	$base	= ($settings['smain']['gallery_switch']) ? 'drop:main' : 'radio:main';

	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
#	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
	
	$_tpl	= ($mode === 'delete') ? 'confirm' : 'body';
	$_top	= sprintf($lang['STF_HEADER'], $lang['TITLE']);
	$mode	= (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'upload', 'delete', 'overview', 'resync'))) ? $mode : 'display';
	
	debug($main, '$main');
	debug($mode, '$mode');
	
#	function upload_image($mode, $category, $sql_type, $mode_preview, $cur_img, $pre_img, $path_img, $data_img, &$error, $main = false, $maxsize = false, $types = false)
#	function gallery_upload($path_img, $image_filename, $image_realname, $image_filesize, $image_filetype, $max_width, $max_height, $max_filesize, $pic_preview_widht, $pic_preview_height)
#	gallery_upload($data['gallery_path'], $pic_file['temp'][$i], $pic_file['name'][$i], $pic_file['size'][$i], $pic_file['type'][$i], $data['max_width'], $data['max_height'], $data['max_filesize'], $data['preview_widht'], $data['preview_height']);
	
#	if ( $mode )
#	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'gallery' => array(
						'title'				=> 'INPUT_DATA',
						'gallery_name'		=> array('validate' => TXT, 'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name', 'check' => true),
						'type'				=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('combi', false, 'main')),
						'main'				=> array('validate' => INT,	'explain' => false,	'type' => $base, 'divbox' => true, 'params' => array(false, true, false), 'required' => array('main', 'type', 1)),
						'gallery_acpview'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:acpview', 'divbox' => true),
						'gallery_picture'	=> array('validate' => INT,	'explain' => false,	'type' => 'upload:picture', 'divbox' => true, 'required' => array('select_file', 'type', 1), 'params' => $path),
						'gallery_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:35;900', 'required' => array('input_desc', 'type', 0), 'divbox' => true),
						'gallery_comment'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'gallery_rate'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'gallery_filesize'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:4;10', 'divbox' => true, 'opt' => array('drop')),
						'gallery_dimension'	=> array('validate' => INT,	'explain' => false,	'type' => 'double:4;5', 'divbox' => true),
						'gallery_format'	=> array('validate' => INT,	'explain' => false,	'type' => 'double:4;5', 'divbox' => true),
						'gallery_thumbnail'	=> array('validate' => INT,	'explain' => false,	'type' => 'double:4;5', 'divbox' => true),
						
						'gallery_preview'	=> 'hidden',
						'gallery_type'		=> 'hidden',
						'gallery_path'		=> 'hidden',
						'gallery_uploader'	=> 'hidden',
						'time_create'		=> 'hidden',
						'time_update'		=> 'hidden',
						'time_upload'		=> 'hidden',
						'gallery_order'		=> 'hidden',
					)
				);
				
				$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
				
				if ( $mode == 'create' && !$submit && $userauth['A_GALLERY_CREATE'] )
				{
					$name = ( isset($_POST['gallery_name']) ) ? request('gallery_name', TXT) : request('gallery_picname', TXT);
					$type = ( isset($_POST['gallery_name']) ) ? 0 : 1;
					
					$data_sql = array(
						'gallery_name'		=> $name,
						'type'				=> $type,
						'main'				=> ($main ? $main : 0),
						'gallery_acpview'	=> $settings['gallery']['acpview'],
						'gallery_picture'	=> '',
						'gallery_desc'		=> '',
						'gallery_comment'	=> '1',
						'gallery_rate'		=> '1',
						'gallery_uploader'	=> $userdata['user_id'],
						'gallery_dimension'	=> $settings['gallery']['dimension'],
						'gallery_format'	=> $settings['gallery']['format'],
						'gallery_type'		=> '',
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
					$data_sql = data(GALLERY_NEW, $data, false, 1, 'row');
					
					$template->assign_vars(array('L_OPTION' => href('a_txt', $file, array('id' => $data), $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW'])));
					
				#	( $data_sql['gallery_pics'] ) ? $template->assign_block_vars('input.overview', array()) : false;
					
				#	$template->assign_block_vars('input.upload', array());
				}
				else
				{
					$data_sql = build_request(GALLERY_NEW, $vars, $error, $mode);
					
					debug($data_sql, '$data_sql');
					
					if ( !$error )
					{
						
						if ( !$data_sql['type'] )
						{
							$data_sql['main'] = 0;
						}
						else
						{
							$gallery_picture = $data_sql['gallery_picture'];
							unset($data_sql['gallery_picture']);
						
							$data_sql['gallery_picture'] = $gallery_picture['picture'];
							$data_sql['gallery_preview'] = $gallery_picture['preview'];
							$data_sql['gallery_filesize'] = $gallery_picture['filesize'];
							$data_sql['gallery_type'] = $gallery_picture['type'];
						}
						
						if ( $mode == 'create' && $userauth['A_GALLERY_CREATE'] )
						{
							debug($data_sql, '$data_sql');
							
							if ( !$data_sql['type'] )
							{
								$data_sql['gallery_path'] = create_folder($path, 'gallery_', true);
							}
							
							$sql = sql(GALLERY_NEW, $mode, $data_sql);
							$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
						}
						else if ( $userauth['A_GALLERY'] )
						{
							$sql = sql(GALLERY_NEW, $mode, $data_sql, 'gallery_id', $data);
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
				
				build_output(GALLERY_NEW, $vars, $data_sql);

                $fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));

				$template->assign_vars(array(
					'L_HEADER'		=> msg_head($mode, $lang['TITLE'], $data_sql['gallery_name']),
					'L_EXPLAIN'		=> $lang['COMMON_REQUIRED'],
					
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['COMMON_OVERVIEW'],
					
					'S_UPLOAD'		=> check_sid("$file&mode=upload&id=$data"),
					'S_OVERVIEW'	=> check_sid("$file&mode=overview&id=$data"),
					
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));

				break;
				
			case 'overview':
			
				if ( $submit )
				{
					$data_sql = data(GALLERY_NEW, $data, false, 1, 'row');
					$data_pic = data(GALLERY_NEW, "WHERE main = $data", false, 1, 'set');
					
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
						
						$sql = "UPDATE " . GALLERY_NEW . " SET gallery_picture = gallery_picture + $nums WHERE gallery_id = $data";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$db_ary = array();
						$pic_ary = array();
						
						$next = ( isset($max_pics) ) ? $max_pics : 1;
						
						for ( $i = 0; $i < $nums; $i++ )
						{
							$pic_ary[] = array(
								'gallery_name'		=> "'" . $_POST['title'][$i] . "'",
								'type'				=> 1,
								'main'				=> $data,
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
						
						$msg = $lang['upload'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&main=$main"));
						
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
				
				$data_gallery	= data(GALLERY_NEW, $data, false, 1, 'row');
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
							$prev	= $path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_preview'];
							$image	= $path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture'];
							
							list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path']['gallery'] . '/' . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture']);
							
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
								
							#	'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
							#	'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
								
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
								$prev	= $path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_preview'];
								$image	= $path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture'];
								
								list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path']['gallery'] . '/' . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture']);
								
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
					'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['STF_UPDATE'], $lang['gallery'], $cat['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['COMMON_OVERVIEW'],
					
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['pic']),
					'L_WIDTH'		=> $lang['pic_widht'],
					'L_HEIGHT'		=> $lang['pic_height'],
					'L_SIZE'		=> $lang['gallery_filesize'],
					'L_NAME'		=> sprintf($lang['stf_name'], $lang['pic']),
					
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
							gallery_delete($pic_data[$i]['gallery_picture'], $pic_data[$i]['gallery_preview'], $path . $data['gallery_path'] . '/');
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
						. sprintf($lang['RETURN_UPDATE'], '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id"));
					log_add(LOG_ADMIN, $log, 'update_gallery_pic');
					message(GENERAL_MESSAGE, $message);
				}
				
				$template->pparse('body');
				
				break;
			*/
			case 'upload':
			
				$template->assign_block_vars('upload', array());
				
				$data_sql = data(GALLERY_NEW, $data, false, 1, 'row');
			#	$pics = data(GALLERY_NEW_PIC, $data_id, 'pic_order ASC', 1, false);
			#	$next = maxa(GALLERY_NEW_PIC, 'gallery_order', "gallery_id = $data_id");
				
				( $pics ) ? $template->assign_block_vars('upload._overview', array()) : false;
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['STF_UPDATE'], $lang['gallery'], $data['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['COMMON_OVERVIEW'],
					
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
						
						$msg = $lang['update_u'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
						
						log_add(LOG_ADMIN, $log, $mode);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						message(GENERAL_MESSAGE, 'test-error');
					}
				}
				
				break;
			
			case 'resync':
			
				$sqlout = data(GALLERY_NEW, $data, false, 1, 'row');
			#	debug($data, '$data');
				debug($sqlout, '$sqlout');
				debug($path . $sqlout['gallery_path'] . '/', 'gallery_path');
				
				if ( is_dir($path . $sqlout['gallery_path'] . '/') )
				{
					$_sqlout = data(GALLERY_NEW, 'WHERE main = ' . $data, false, 1, 'set');
				#	debug($_sqlout, '$_sqlout');
				#	$pics_db = data(GALLERY_NEW_PIC, $data_id, false, 1, 'set');
					
					$path_files = scandir($path . $sqlout['gallery_path'] . '/');
					debug($path_files, '$path_files');
					foreach ( $path_files as $files )
					{
						if ( $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' )
						{
							$pics_dir[] = $files;
						}
					}
					
					debug($pics_dir, '$pics_dir');
				#	debug($_sqlout, '$_sqlout');
					
					$_ary_check = '';
					
					foreach ( $pics_dir as $_path_pic )
					{
						debug($_path_pic, '$_path_pic');
						foreach ( $_sqlout as $_db_pic )
						{
						#	debug($_db_pic, '$_db_pic');
						#	debug($_db_pic['gallery_picture'], '1');
							if ( $_db_pic['gallery_picture'] == $_path_pic )
							{
								$_ary_check['gallery_picture'][] = $_db_pic['gallery_picture'];
							}
							else if ( $_db_pic['gallery_preview'] == $_path_pic )
							{
								$_ary_check['gallery_preview'][] = $_db_pic['gallery_picture'];
							}
						}
					}
					
					debug($_ary_check, '$_ary_check', true);
					
					$cnt = isset($_ary_check) ? count($_ary_check) : 0;
					
					debug($cnt, '$cnt');
				}
				else
				{
					$data['gallery_path'] = create_folder($path, 'gallery_', true);
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
					
					delete_folder($path . $data['gallery_path'] . '/');
					
					$message = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['COMMON_CONFIRM'],
						'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data['gallery_name']),
						
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
			
				if ( $userauth['A_GALLERY_ASSORT'] )
				{
					move(GALLERY_NEW, $mode, $order, $main, $type, $usub, $action);
					log_add(LOG_ADMIN, $log, $mode);
				}

			case 'display':
				
				$template->assign_block_vars('display', array());

				$fields = isset($main) ? build_fields(array('mode' => 'create', 'main' => $main)) : build_fields(array('mode' => 'create'));
				$sqlout = data(GALLERY_NEW, false, 'gallery_order ASC', 1, 4);
				
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
							$id		= $row['gallery_id'];
							$pic	= $row['gallery_picture'] ? $row['gallery_picture'] : 0;
							$desc	= $row['gallery_desc'];
							$name	= $row['gallery_name'];
							$order	= $row['gallery_order'];
							$size	= 0;
							
							if ( isset($sqlout['data_id'][$id]) )
							{
								foreach ( $sqlout['data_id'][$id] as $pic_row )
								{
									$size += $pic_row['gallery_filesize'];
								}
								$size = _size($size, 1);
							}
							else
							{
								$size = size_dir($path . $row['gallery_path']);
							}
			
							$template->assign_block_vars('display.row', array(
								'NAME'	=> href('a_txt', $file, array('main' => $id), $name, $name),
								'INFO'	=> sprintf($lang['SPRINTF_SIZE_PIC'], $size, $pic),
								'DESC'	=> $desc,
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
								'UPLOAD'	=> href('a_img', $file, array('mode' => 'upload', 'id' => $id), 'icon_upload', 'common_upload'),
								
								'OVERVIEW'	=> isset($pic[$id]) ? href('a_img', $file, array('mode' => 'overview', 'id' => $id), 'icon_overview', 'COMMON_OVERVIEW') : img('i_icon', 'icon_overview2', 'COMMON_OVERVIEW'),
								
								'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
							));
						}
					}
				}
				else
				{
					$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
					$option[] = href('a_txt', $file, array('mode' => 'resync', 'id' => $main), $lang['COMMON_RESYNC'], $lang['COMMON_RESYNC']);
					
					if ( isset($sqlout['data_id'][$main]) )
					{
						$max_cnt = 0;
						$max_tmp = count($sqlout['data_id'][$main]);
						
						foreach ( $sqlout['data_id'][$main] as $row )
						{
							$main_id	= $row['gallery_id'];
							$main_name	= $row['gallery_name'];
							$main_pre	= $row['gallery_preview'];
                            $main_order	= $row['gallery_order'];
							
							$template->assign_block_vars('display.row', array(
								'NAME'		=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), $path . $sqlout['main'][$main]['gallery_path'] . '/' . $main_pre, $main_name),
								
								'MOVE_UP'	=> ( $main_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'order' => $main_order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ( $main_order != $max_tmp )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'order' => $main_order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),

								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $main_id), 'icon_cancel', 'COMMON_DELETE'),
								
								'S_NAME'	=> "subforum_name[$main_id]",
								'S_SUBMIT'	=> "subforum_submit[$main_id]",
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
					'S_CREATE'	=> !$main ? 'gallery_name' : 'gallery_picname',
		
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
			break;
		}
#	}
	/*
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
			'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'COMMON_UPDATE'),
			'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'COMMON_DELETE'),
			
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
					
					list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path']['gallery'] . '/' . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture']);
					
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
						'PREV'		=> $path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_preview'],
						'IMAGE'		=> $path . $cat['gallery_path'] . '/' . $pic[$i]['gallery_picture'],							
						'WIDTH'		=> $width,
						'HEIGHT'	=> $height,
						'NAME'		=> $pic[$i]['gallery_picture'],
						'SIZE'		=> size_file($pic[$i]['gallery_filesize']),
						
						'R_SUM'		=> ( $rate_ave ) ? $rate_ave : 0,
						'RATING'	=> sprintf('%s %s &oslash; %s/%s', $rate_cnt, $lang['common_rating'], $rate_ave, $settings['rating_gallery']['maximal']),
						'COMMENT'	=> '',
						
						'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'usub' => $cid, 'type' => 1, 'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
						'MOVE_DOWN'	=> ( $order != $cnt )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'usub' => $cid, 'type' => 1, 'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
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
						$prev	= $path . $cat['gallery_path'] . '/' . $pic[$j]['gallery_preview'];
						$image	= $path . $cat['gallery_path'] . '/' . $pic[$j]['gallery_picture'];
						
						list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path']['gallery'] . '/' . $cat['gallery_path'] . '/' . $pic[$j]['gallery_picture']);
						
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
			'L_HEADER'	=> sprintf($lang['stf_overview'], $lang['TITLE'], $cat['gallery_name']),
			'L_EXPLAIN'	=> $lang['EXPLAIN'],
			
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
			$template->assign_block_vars('display.none', array());
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
					$size_pics = size_dir($path . $cat[$i]['gallery_path']);
				}
			
				$template->assign_block_vars('display.row', array(
					'NAME'	=> href('a_txt', $file, array('id' => $id), $name, $name),
					'INFO'	=> sprintf($lang['SPRINTF_SIZE_PIC'], $size_pics, $cat[$i]['gallery_picture']),
					'DESC'	=> $desc,
	
					'MOVE_UP'	=> ( $order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
					'MOVE_DOWN'	=> ( $order != $cat_cnt )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
			
					'OVERVIEW'	=> isset($pic_cnt[$id]) ? href('a_img', $file, array('mode' => 'overview', 'id' => $id), 'icon_overview', 'COMMON_OVERVIEW') : img('i_icon', 'icon_overview2', 'COMMON_OVERVIEW'),
					'RESYNC'	=> isset($pic_cnt[$id]) ? href('a_img', $file, array('mode' => 'resync', 'id' => $id), 'icon_resync', 'common_resync') : img('i_icon', 'icon_resync2', 'common_resync'),
					
					'UPLOAD'	=> href('a_img', $file, array('mode' => 'upload', 'id' => $id), 'icon_upload', 'common_upload'),
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
				));
			}
		
			$current_page = ( !$cat_cnt ) ? 1 : ceil( $cat_cnt / $settings['per_page_entry']['acp'] );
			
			$template->assign_vars(array(
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['ppe_acp'] ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file?", $cat_cnt, $settings['ppe_acp'], $start ),
			));
		}
		
		$template->assign_vars(array(
			'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
			'L_CREATE'		=> sprintf($lang['STF_CREATE'], $lang['TITLE']),
			'L_EXPLAIN'	=> $lang['EXPLAIN'],

			'S_CREATE'	=> check_sid("$file?mode=create"),
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	*/
	$template->pparse($_tpl);
	acp_footer();
}
?>