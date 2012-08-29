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
	$update = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_downloads';
	
	include('./pagestart.php');
	
	add_lang('downloads');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_DOWNLOADS;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$cat	= request('cat', TYP);
	$sub	= request('sub', TYP);
	$subs	= request('subs', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_downloads'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['a_menu'] )
	{
		log_add(LOG_ADMIN, SECTION_MENU, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_menu.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$dir_path	= $root_path . $settings['path_downloads'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);

	if ( $userdata['user_level'] != ADMIN && !$userauth['a_download'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}

	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;

	$template->set_filenames(array(
		'body'		=> 'style/acp_downloads.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( isset($_POST['add_file']) || isset($_POST['add_cat']) )
	{
		if ( isset($_POST['add_file']) )
		{
			$mode = 'create';
			$cat_id = key($_POST['add_file']);
			$name = request(array('file_name', $cat_id), TXT);
		}
		else
		{
			$mode = 'create_cat';
			$name = request('file_name', TXT);
		}
	}
	
	/* $settings['download_types'] ??? */
	$mime_types = array('meta_application', 'meta_image', 'meta_text', 'meta_video');
	 
#	debug($mode);
#	debug($_GET);
#	debug($_POST);
#	debug($_FILES);

	debug($_POST);

	$mode = ( in_array($mode, array('create', 'update', 'order', 'delete', 'create_cat', 'update_cat', 'order_cat', 'delete_cat')) ) ? $mode : '';

	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':

				$template->assign_block_vars('input', array());

				$vars = array(
					'file' => array(
						'title'	=> 'data_input',
						'file_name'		=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25',	'required' => 'input_name'),
					#	'cat_id'		=> array('validate' => INT,	'explain' => false, 'type' => 'radio:cat'),
				#		'cat_id'		=> array('validate' => INT,	'explain' => false, 'type' => 'radio:cat', 'params' => true, 'ajax' => 'ajax_order:ajax_order'),
				#		'file_filename'	=> array('validate' => TXT,	'explain' => false, 'type' => 'upload:file','required' => 'select_file'),
				#		'file_order'	=> array('validate' => INT,	'explain' => false, 'type' => 'drop:order'),
				#		'file_type'		=> 'hidden',
				#		'file_size'		=> 'hidden',
					)
				);
				
				if ( $mode == 'create' && !$update )
				{
					$data_sql = array(
						'file_name'		=> $name,
						'cat_id'		=> $cat,
						'file_filename'	=> '',
						'file_type'		=> '',
						'file_size'		=> '',
						'file_order'	=> 0,						
					);
				}
				else if ( $mode == 'update' && !$update )
				{
					$data_sql = data(DOWNLOADS, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(DOWNLOADS, $vars, $error);
					/*
					$data_sql = array(
						'file_name'		=> request('file_name', 2),
						'cat_id'		=> request($cat, 0),
						'file_order'	=> request('file_order', 0) ? request('file_order', 0) : request('file_order_new', 0),
					);
					
					$ufile = request_file('ufile');
					
					$cats = data(DOWNLOADS_CAT, $data, false, 1, true);
					
					$error[] = !$data['file_name'] ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					
					if ( $ufile['temp'] )
					{
						$file_info = upload_dl($ufile, $dir_path . $cats['cat_path'], $cats['cat_types'], $cats['cat_maxsize'], $error);
						
						$data['file_filename'] = $file_info['file_filename'];
						$data['file_type'] = $file_info['file_type'];
						$data['file_size'] = $file_info['file_size'];
					}
					else
					{
						$error[] = ( $error ? '<br />' : '' ) . $lang['msg_select_file'];
					}
					*/
					if ( !$error )
					{
						$data['file_order'] = $data['file_order'] ? $data['file_order'] : maxa(DOWNLOADS, 'file_order', "cat_id = {$data['cat_id']}");
												
						if ( $mode == 'create' )
						{
							$sql = sql(DOWNLOADS, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(DOWNLOADS, $mode, $data_sql, 'file_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&amp;id=$data"));
						}
						
						orders(DOWNLOADS);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(DOWNLOADS, $vars, $data_sql);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
				$template->assign_vars(array(
				#	'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['download_cat']),
				#	'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['download_cat'], $data['cat_title']),
				#	'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['cat']),
				#	'L_DESC'	=> sprintf($lang['sprintf_desc'], $lang['cat']),
				#	'L_ICON'	=> $lang['cat_icon'],

					'NAME'	=> $data['file_name'],
				#	'DESC'		=> $data['cat_desc'],
				
					'S_FIELDS'	=> $fields,
					'S_ACTION'	=> check_sid($file),
				));

				$template->pparse('body');

				break;

			case 'order':

				update(DOWNLOADS_CAT, 'download_cat', $move, $data);
				orders(DOWNLOADS_CAT);

				log_add(LOG_ADMIN, SECTION_DOWNLOADS_CAT, 'acp_download_cat_order');

				$index = true;

				break;

			case 'delete':

				$data = get_data(DOWNLOADS_CAT, $data, 1);

				if ( $data && $confirm )
				{
					$sql = "DELETE FROM " . DOWNLOADS_CAT . " WHERE cat_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}

					$message = $lang['delete_download_cat'] . sprintf($lang['click_return_download_cat'], '<a href="' . check_sid($file));
					log_add(LOG_ADMIN, SECTION_DOWNLOADS_CAT, 'delete_download_cat');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));

					$fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . $cat . '" value="' . $data_id . '" />';

					$template->assign_vars(array(
						'MESSAGE_TITLE'	=> $lang['common_confirm'],
						'MESSAGE_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['delete_confirm_download_cat'], $data['cat_title']),

						'S_FIELDS'		=> $fields,
						'S_ACTION'		=> check_sid($file),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_download_cat']);
				}

				$template->pparse('body');

				break;

			case 'create_cat':
			case 'update_cat':

				$template->assign_block_vars('cat', array());
				
				$vars = array(
					'cat' => array(
						'title' => 'data_input',
						'cat_name'		=> array('validate' => TXT,	'explain' => false, 'type' => 'text:25;25', 'required' => 'input_name'),
				#		'cat_desc'		=> array('validate' => TXT,	'explain' => false, 'type' => 'textarea:40', 'required' => 'input_desc'),
				#		'cat_icon'		=> array('validate' => INT,	'explain' => false, 'type' => 'radio:icons'),
				#		'cat_type'		=> array('validate' => ARY,	'type' => 'drop:dtype', 'required' => 'select_type'),
				#		'cat_auth'		=> array('validate' => ARY,	'type' => 'drop:auth', 'simple_auth' => true),
				#		'cat_rate'		=> array('validate' => INT,	'explain' => false, 'type' => 'radio:yesno'),
				#		'cat_comment'	=> array('validate' => INT,	'explain' => false, 'type' => 'radio:yesno'),
				#		'cat_order'		=> array('validate' => INT,	'explain' => false, 'type' => 'drop:order'),
				#		'cat_path'		=> 'hidden',
				#		'time_create'	=> 'hidden',
				#		'time_update'	=> 'hidden',
				#		'time_upload'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create_cat' && !$update )
				{
					$data_sql = array(
						'cat_name'		=> request('cat_name', 2),
						'cat_desc'		=> '',
						'cat_icon'		=> '',
						'cat_path'		=> '',
						'cat_type'		=> 'a:0:{}',
						'cat_auth'		=> 'a:0:{}',
						'cat_rate'		=> 0,
						'cat_comment'	=> 0,
						'time_create'	=> time(),
						'time_update'	=> 0,
						'time_upload'	=> 0,
						'cat_order'		=> '',
					);
				}
				else if ( $mode == 'update_cat' && !$update )
				{
					$data_sql = data(DOWNLOADS_CAT, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(DOWNLOADS_CAT, $vars, $error);
					
					if ( !$error )
					{
						$data['cat_order'] = $data['cat_order'] ? $data['cat_order'] : maxa(DOWNLOADS_CAT, 'cat_order', '');
						
						if ( $mode == 'create_cat' )
						{
							$data['cat_path'] = create_folder($dir_path, 'download_', true);

							$sql = sql(DOWNLOADS_CAT, $mode, $data_sql);
							$msg = $lang['create_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(DOWNLOADS_CAT, $mode, $data_sql, 'cat_id', $data);
							$msg = $lang['update_cat'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$cat=$data_id"));
						}

						orders(DOWNLOADS_CAT);

						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(DOWNLOADS, $vars, $data, 'cat');
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data\" />";
				$fields .= "<input type=\"hidden\" name=\"cat_path\" value=\"" . $data['cat_path'] . "\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['cat'], $data['cat_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
			
				break;
				
			case 'order_cat':
				
				update(DOWNLOADS_CAT, 'cat', $move, $data);
				orders(DOWNLOADS_CAT);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case 'delete_cat':
			
				$data_sql = data(DOWNLOADS_CAT, $data, false, 1, true);
			
				if ( $data && $confirm )
				{
					$sql = sql(DOWNLOADS_CAT, $mode, $data_sql, 'cat_id', $data);
					$msg = $lang['delete_cat'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					sql(DOWNLOADS, $mode, false, 'cat_id', $data);
					
					delete_folder($dir_path . $data['cat_path'] . '/');

					orders(DOWNLOADS_CAT);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['cat_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title_cat']));
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

	$fields .= '<input type="hidden" name="mode" value="create" />';

	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['file']),
		'L_CREATE_CAT'	=> sprintf($lang['sprintf_create'], $lang['cat']),
		
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
			
			$template->assign_block_vars('display.cat', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update_cat', $cat => $cid), $cname, ''),
				
				'PATH'		=> $dir_path . $tmp[$i]['cat_path'],
				
				'TYPE'		=> $typ,

				'MOVE_UP'	=> ( $corder != '10' ) ? href('a_img', $file, array('mode' => 'order_cat', 'move' => '-15', $cat => $cid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $corder != $max ) ? href('a_img', $file, array('mode' => 'order_cat', 'move' => '+15', $cat => $cid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

				'RESYNC'	=> $cfiles ? href('a_img', $file, array('mode' => 'rescny_cat', $cat => $cid), 'icon_resync', 'common_resync') : img('i_icon', 'icon_resync2', 'common_resync'),
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update_cat', $cat => $cid), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete_cat', $cat => $cid), 'icon_cancel', 'common_delete'),
				
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
				$template->assign_block_vars('display.cat.empty', array());
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
							'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$file_id") . '"><img src="' . $images['icon_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
							'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$file_id") . '"><img src="' . $images['icon_cancel'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
						));
					}
				}
			}
		}
	}

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>