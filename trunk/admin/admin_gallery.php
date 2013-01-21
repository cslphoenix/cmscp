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

	$current = 'acp_gallery';
	
	include('./pagestart.php');
	
	add_lang('gallery');
	
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
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_gallery'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_gallery.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	debug($_POST, '_POST');
	
	$base = ($settings['smain']['gallery_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'upload', 'delete'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				/*
				CREATE TABLE IF NOT EXISTS `cms_gallery_new` (
				  `gallery_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
				  `gallery_name` varchar(50) COLLATE utf8_bin NOT NULL,
				  `type` tinyint(1) unsigned NOT NULL,
				  `main` mediumint(8) unsigned NOT NULL,
				  `gallery_desc` text COLLATE utf8_bin NOT NULL,
				  `gallery_comment` tinyint(1) unsigned NOT NULL,
				  `gallery_rate` tinyint(1) unsigned NOT NULL,
				  `gallery_filesize` mediumint(6) unsigned NOT NULL,
				  `gallery_uploader` mediumint(8) unsigned NOT NULL,
				  `gallery_path` varchar(50) COLLATE utf8_bin NOT NULL,
				  `gallery_picture` varchar(50) COLLATE utf8_bin NOT NULL,
				  `gallery_preview` varchar(50) COLLATE utf8_bin NOT NULL,
				  `gallery_dimension` varchar(50) COLLATE utf8_bin NOT NULL,
				  `gallery_format` varchar(50) COLLATE utf8_bin NOT NULL,
				  `gallery_thumbnail` varchar(50) COLLATE utf8_bin NOT NULL,
				  `time_create` int(11) unsigned NOT NULL,
				  `time_update` int(11) unsigned NOT NULL,
				  `time_upload` int(11) unsigned NOT NULL,
				  `gallery_order` mediumint(8) unsigned NOT NULL,
				  PRIMARY KEY (`gallery_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
				*/
				
				$vars = array(
					'gallery' => array(
						'title'	=> 'data_input',
						'gallery_name'		=> array('validate' => TXT, 'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name', 'check' => true),
						'type'				=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('combi', false, 'main')),
						'main'				=> array('validate' => INT,	'explain' => false,	'type' => $base,		'divbox' => true, 'params' => array(false, true, false)),
						'copy'				=> array('validate' => INT,	'explain' => false,	'type' => 'drop:copy',	'divbox' => true),
						'gallery_desc'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:35;900', 'required' => 'input_desc'),
						'gallery_comments'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'gallery_rate'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
						'gallery_filesize'	=> array('validate' => INT,	'explain' => false,	'type' => 'text:10;10', 'divbox' => true),
						'gallery_dimension'	=> array('validate' => INT,	'explain' => false,	'type' => 'double:4;5', 'divbox' => true),
						'gallery_format'	=> array('validate' => INT,	'explain' => false,	'type' => 'double:4;5', 'divbox' => true),
						'gallery_thumbnail'	=> array('validate' => INT,	'explain' => false,	'type' => 'double:4;5', 'divbox' => true),
						
						'gallery_picture'	=> 'hidden',
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
					$data_sql = array(
						'gallery_name'		=> request('gallery_name', TXT),
						'type'				=> '',
						'main'				=> '',
						'copy'				=> '',
						'gallery_desc'		=> '',
						'gallery_comments'	=> '1',
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
					
				#	( $data_sql['gallery_pics'] ) ? $template->assign_block_vars('input.overview', array()) : false;
					
				#	$template->assign_block_vars('input.upload', array());
				}
				else
				{
					$data_sql = build_request(GALLERY_NEW, $vars, $error, $mode);
					
					if ( !$error )
					{
						$data_sql['gallery_order'] = maxa(GALLERY_NEW, 'gallery_order', false);
						
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
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'			=> sprintf($lang['sprintf_' . $mode], $lang['gallery'], $data['gallery_name']),
					
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
			
				$template->assign_block_vars('overview', array());
				
				if ( $order )
				{
					update(GALLERY_NEW_PIC, 'pic', $move, $data_pic);
					orders(GALLERY_PIC, $data_id);
					
					log_add(LOG_ADMIN, $log, '_order_pic');
				}
				
				$data_gallery	= data(GALLERY_NEW, $data, false, 1, true);
				$data_pics		= data(GALLERY_NEW_PIC, $data_id, 'pic_order ASC', 1, false);
				
				$max = maxi(GALLERY_NEW_PIC, 'pic_order', '');
				
				if ( $data_pics )
				{
					if ( $data_gallery['preview_list'] )
					{
						$template->assign_block_vars('overview._list', array());
						
						for ( $i = $start; $i < min($data_gallery['per_cols'] + $start, count($data_pics)); $i++ )
						{
							$pic_id	= $data_pics[$i]['pic_id'];
							$prev	= $dir_path . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_preview'];
							$image	= $dir_path . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename'];
							
							list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename']);
							
							$template->assign_block_vars('overview._list._gallery_row', array(
								'PIC_ID'	=> $data_pics[$i]['pic_id'],
								'TITLE'		=> ( $data_pics[$i]['pic_title'] ) ? $data_pics[$i]['pic_title'] : 'kein Titel',							
								'PREV'		=> $prev,
								'IMAGE'		=> $image,							
								'WIDTH'		=> $width,
								'HEIGHT'	=> $height,
								'NAME'		=> $data_pics[$i]['pic_filename'],
								'SIZE'		=> size_file($data_pics[$i]['pic_size']),
								
								'ORDER'		=> $data_pics[$i]['pic_order'],
								
							#	'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							#	'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
								
								'MOVE_UP'	=> ( $data_pics[$i]['pic_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
								'MOVE_DOWN'	=> ( $data_pics[$i]['pic_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',
	
							));
						}
					}
					else
					{
						$template->assign_block_vars('overview._preview', array());
						
						for ( $i = $start; $i < min($data_gallery['per_cols'] + $start, count($data_pics)); $i += $data_gallery['per_rows'] )
						{
							if ( count($data_pics) > 0 )
							{
								$template->assign_block_vars('overview._preview._gallery_row', array());
							}
							
							for ( $j = $i; $j < ( $i + $data_gallery['per_rows'] ); $j++ )
							{
								if ( $j >= count($data_pics) )
								{
									break;
								}
								
								$pic_id	= $data_pics[$i]['pic_id'];
								$prev	= $dir_path . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_preview'];
								$image	= $dir_path . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename'];
								
								list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $data_gallery['gallery_path'] . '/' . $data_pics[$i]['pic_filename']);
								
								$template->assign_block_vars('overview._preview._galleryrow._gallery_col', array(
									'PIC_ID'	=> $data_pics[$i]['pic_id'],
									'TITLE'		=> $data_pics[$i]['pic_title'] ? $data_pics[$i]['pic_title'] : 'kein Titel',							
									'PREV'		=> $prev,
									'IMAGE'		=> $image,							
									'WIDTH'		=> $width,
									'HEIGHT'	=> $height,
									'NAME'		=> $data_pics[$i]['pic_filename'],
									'SIZE'		=> size_file($data_pics[$i]['pic_size']),
									
									'ORDER'		=> $data_pics[$i]['pic_order'],

									'MOVE_UP'	=> ( $data_pics[$i]['pic_order'] != '10' ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=-15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
									'MOVE_DOWN'	=> ( $data_pics[$i]['pic_order'] != $max ) ? '<a href="' . check_sid("$file?mode=_overview&amp;$url=$data_id&amp;order=1&amp;move=+15&amp;$url_p=$pic_id") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',

								));
							}
						}
					}
					
					$current_page = ( !count($data_pics) ) ? 1 : ceil( count($data_pics) / $data_gallery['per_cols'] );
			
					$template->assign_vars(array(
						'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $data_gallery['per_cols'] ) + 1 ), $current_page ),
						'PAGINATION'	=> generate_pagination("$file?mode=_overview&$url=$data_id", count($data_pics), $data_gallery['per_cols'], $start),
					));
				}
				else
				{
					$template->assign_block_vars('overview._entry_empty', array());
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['sprintf_update'], $lang['gallery'], $data_gallery['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['common_overview'],
					
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['pic']),
					'L_WIDTH'		=> $lang['pic_widht'],
					'L_HEIGHT'		=> $lang['pic_height'],
					'L_SIZE'		=> $lang['pic_size'],
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['pic']),
					
					'PICS_PER_LINE'	=> $data_gallery['per_rows'],
					'PREVIEW_WIDHT'	=> $data_gallery['preview_widht'],
					
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
					$pic_order	= request('pic_order', 4);
					
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
							gallery_delete($pic_data[$i]['pic_filename'], $pic_data[$i]['pic_preview'], $dir_path . $data['gallery_path'] . '/');
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
			#	$next = maxa(GALLERY_NEW_PIC, 'pic_order', "gallery_id = $data_id");
				
				( $pics ) ? $template->assign_block_vars('upload._overview', array()) : false;
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['gallery']),
					'L_INPUT'		=> sprintf($lang['sprintf_update'], $lang['gallery'], $data['gallery_name']),
					'L_UPLOAD'		=> $lang['common_upload'],
					'L_OVERVIEW'	=> $lang['common_overview'],
					
					'S_INPUT'		=> check_sid("$file?mode=_update&amp;$url=$data_id"),
					'S_UPDATE'		=> check_sid("$file?mode=_update&amp;$url=$data_id"),
					'S_OVERVIEW'	=> check_sid("$file?mode=_overview&amp;$url=$data_id"),
					
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
								'pic_size'		=> "'" . $sql_pic[$i]['pic_size'] . "'",
								'gallery_id'	=> $data_id,
								'pic_filename'	=> "'" . $sql_pic[$i]['pic_filename'] . "'",
								'pic_preview'	=> "'" . $sql_pic[$i]['pic_preview'] . "'",
								'upload_user'	=> $userdata['user_id'],
								'upload_time'	=> time(),
								'pic_order'		=> $next,
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
							if ( $pics_db[$i]['pic_filename'] == $pic )
							{
								$_ary_check[$pics_db[$i]['pic_id']]['pic_filename'] = $pic;
							}
							else if ( $pics_db[$i]['pic_preview'] == $pic )
							{
								$_ary_check[$pics_db[$i]['pic_id']]['pic_preview'] = $pic;
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
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['gallery_name']),
						
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
	
	$template->assign_block_vars('display', array());
	
	$max = maxi(GALLERY_NEW, 'gallery_order', '');
	$tmp = data(GALLERY_NEW, false, 'gallery_order ASC', 1, false);
	

	if ( !$tmp )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$cnt = count($tmp);
		
		for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt); $i++ )
		{
			$id		= $tmp[$i]['gallery_id'];
			$name	= $tmp[$i]['gallery_name'];
			$desc	= $tmp[$i]['gallery_desc'];
		#	$count	= $tmp[$i]['gallery_pics'];
			$order	= $tmp[$i]['gallery_order'];
			
			$size_pics = '';
			
			if ( $count )
			{
				$pics = data(GALLERY_NEW_PIC, $id, false, 1, false);
				
				if ( $pics )
				{
					for ( $j = 0; $j < count($pics); $j++ )
					{
						$size_pics += $pics[$j]['pic_size'];
					}
					
					$size_pics = _size($size_pics, 1);
				}
			}
			else
			{
				$size_pics = size_dir($dir_path . $tmp[$i]['gallery_path']);
			}
			
			$template->assign_block_vars('display.row', array(
				'NAME'	=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				'INFO'	=> sprintf($lang['sprintf_size-pic'], $size_pics, $tmp[$i]['gallery_pics']),
				'DESC'	=> $desc,

				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', 'id' => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', 'id' => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

				'OVERVIEW'	=> $count ? href('a_img', $file, array('mode' => '_overview', 'id' => $id), 'icon_overview', 'common_overview') : img('i_icon', 'icon_overview2', 'common_overview'),
				'RESYNC'	=> $count ? href('a_img', $file, array('mode' => '_resync', 'id' => $id), 'icon_resync', 'common_resync') : img('i_icon', 'icon_resync2', 'common_resync'),
				
				'UPLOAD'	=> href('a_img', $file, array('mode' => 'upload', 'id' => $id), 'icon_upload', 'common_upload'),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
			));
		}
		
		$current_page = ( !$cnt ) ? 1 : ceil( $cnt / $settings['per_page_entry']['acp'] );
		
		$template->assign_vars(array(
			'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page),
			'PAGE_PAGING'	=> generate_pagination("$file?", $cnt, $settings['per_page_entry']['acp'], $start ),
		));
	}
	
	
		
	$fields .= '<input type="hidden" name="mode" value="create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['title']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['title']),
		'L_EXPLAIN'		=> $lang['explain'],
		
		'S_CREATE'	=> check_sid("$file?mode=create"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>