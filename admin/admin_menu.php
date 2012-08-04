<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_menu',
		'modes'		=> array(
			'acp'		=> array('title' => 'acp_menu_acp', 'auth' => ''),
			'mcp'		=> array('title' => 'acp_menu_mcp', 'auth' => ''),
			'ucp'		=> array('title' => 'acp_menu_ucp', 'auth' => ''),
		),
	);
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_menu';
	
	include('./pagestart.php');
	
	add_lang('menu');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$tbl	= MENU2;
	$log	= SECTION_MENU;
	$url	= POST_MENU;
	$sub	= POST_SUB;
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_sub	= request($sub, INT);
	$action		= request('action', TXT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', INT);
	$process	= request('process', TXT);
	
	$dir_path	= $root_path . 'admin/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_menu.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'list', 'order', 'delete')) ) ? $mode : false;
	
	debug($_POST, 'post');

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
						'menu_name'		=> array('validate' => TXT,	'type' => 'text:25;25',		'explain' => false, 'required' => 'input_name'),
						'menu_type'		=> array('validate' => INT,	'type' => 'drop:menu',		'explain' => false),
						'menu_sub'		=> array('validate' => INT,	'type' => 'drop:sub',		'explain' => false, 'trid' => 'menu_sub'),
						'menu_file'		=> array('validate' => TXT,	'type' => 'drop:dfile',		'explain' => false, 'trid' => 'menu_file', 'params' => array($dir_path, 'php', 'drop')),
						'menu_opts'		=> array('validate' => TXT,	'type' => 'drop:info',		'explain' => false, 'trid' => 'menu_opts', 'params' => array($dir_path)),
						'menu_class'	=> 'hidden',
						'menu_order'	=> 'hidden',
					),
				);
				
				$keys = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? key($_POST['submit_module']) : $data_sub ) : 0;
				$name = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? request(array('menu_module', $keys), TXT) : request('menu_label', TXT) ) : request('menu_name', TXT);
				$type = ( !isset($_POST['menu_name']) ) ? (( isset($_POST['submit_module']) ) ? 2 : 1 ) : 0;
				
				if ( $mode == 'create' && !(request('submit', TXT)) )
				{
					$data = array(
						'menu_name'		=> $name,
						'menu_type'		=> $type,
						'menu_sub'		=> ( $type == 2 ) ? $keys * (-1) : $keys,
						'menu_file'		=> '',
						'menu_opts'		=> '',
						'menu_class'	=> $action,
						'menu_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !(request('submit', TXT)) )
				{
					$data = data($tbl, $data_id, false, 1, true);
				}
				else
				{
					$data = build_request($vars, $error);
					
					debug($data, 'datas');
				
					if ( $data['menu_type'] == TYPE_CATEGORY )
					{
						$data['menu_sub'] = 0;
						unset($data['menu_file'], $data['menu_opts'], $data['menu_auth']);
					}
					else if ( $data['menu_type'] == TYPE_LABEL )
					{
						unset($data['menu_file'], $data['menu_opts'], $data['menu_auth']);
					}
					
					if ( !$error )
					{
						$data['menu_order'] = $data['menu_order'] ? $data['menu_order'] : maxa($tbl, 'menu_order', "menu_sub = " . $data['menu_sub']);
						
						if ( $mode == 'create' )
						{
							$sql = sql($tbl, $mode, $data);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql($tbl, $mode, $data, 'menu_id', $data_id);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&amp;$url=$data_id"));
						}

						orders($tbl, $data['menu_sub']);

						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output($vars, $data);

				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $action),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['menu_name']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');

				break;
				
			case 'list':
			
				$template->assign_block_vars('list', array());
				
				if ( $process == 'order' )
				{
					update($tbl, 'menu', $move, $data_id);
					orders($tbl, $data_sub);
					
					log_add(LOG_ADMIN, $log, 'order_menu');
				}
				
				$tmp = data($tbl, false, 'menu_sub ASC, menu_order ASC', 1, false);
				
			#	debug($data_sub, 'data_sub');
				
				if ( $tmp )
				{
					foreach ( $tmp as $row )
					{
						if ( $row['menu_id'] == $data_sub )
						{
							$cat = $row;
						}
						else if ( $row['menu_sub'] == $data_sub * -1 )
						{
							$lab[$row['menu_id']] = $row;
						}
					}
					
					$keys_labels = array_keys($lab);
					
					foreach ( $tmp as $row )
					{
						if ( in_array($row['menu_sub'], $keys_labels) )
						{
							$mod[$row['menu_sub']][] = $row;
						}
					}
				}
				else
				{
					$cat = $mod = $lab = array();
				}
				
			#	debug($cat, 'cat');
			#	debug($mod, 'mod');
			#	debug($lab, 'lab');
				
				$cid = $cat['menu_id'];
				
				$template->assign_vars(array(
					'CAT'	=> href('a_txt', $file, array('action' => 'acp'), strtoupper($action), strtoupper($action)),
					'NAME'	=> isset($lang[$cat['menu_name']]) ? $lang[$cat['menu_name']] : $cat['menu_name'],
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( $lab )
				{
					$lmax = array_pop(end($lab));
					
					foreach ( $lab as $lrow )
					{
						$lid	= $lrow['menu_id'];
						$lname	= isset($lang[$lrow['menu_name']]) ? $lang[$lrow['menu_name']] : $lrow['menu_name'];
						$lorder	= $lrow['menu_order'];
						
						$template->assign_block_vars('list.row', array(
							'NAME'          => href('a_txt', $file, array('mode' => 'update', $url => $lid), $lname, $lname),
							
							'MOVE_UP'       => ( $lorder != '10' )	? href('a_img', $file, array('mode' => 'list', 'process' => 'order', 'move' => '-15', $sub => $cid, $url => $lid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'     => ( $lorder != $lmax )	? href('a_img', $file, array('mode' => 'list', 'process' => 'order', 'move' => '+15', $sub => $cid, $url => $lid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			
							'UPDATE'        => href('a_img', $file, array('mode' => 'update', $url => $lid), 'icon_update', 'common_update'),
							'DELETE'        => href('a_img', $file, array('mode' => 'delete', $url => $lid), 'icon_cancel', 'common_delete'),
							
							'S_NAME'	=> "menu_module[$lid]",
							'S_SUBMIT'	=> "submit_module[$lid]",
						
						));
						
						if ( isset($mod[$lid]) )
						{
						#	debug($mod[$lid]);
							$mmax[$lid] = array_pop(end($mod[$lid]));
							
							foreach ( $mod[$lid] as $mrow )
							{
								$mid	= $mrow['menu_id'];
								$msub	= $mrow['menu_sub'];
								$mname	= isset($lang[$mrow['menu_name']]) ? $lang[$mrow['menu_name']] : $mrow['menu_name'];
								$morder	= $mrow['menu_order'];
								
								$template->assign_block_vars('list.row.mod', array( 
									'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $mid), $mname, $mname),
									
									'MOVE_UP'	=> ( $morder != '10' )			? href('a_img', $file, array('mode' => 'list', 'process' => 'order', 'move' => '-15', $sub => $lid, $url => $mid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
									'MOVE_DOWN'	=> ( $morder != $mmax[$lid] )	? href('a_img', $file, array('mode' => 'list', 'process' => 'order', 'move' => '+15', $sub => $lid, $url => $mid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
						
									'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $mid), 'icon_update', 'common_update'),
									'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $mid), 'icon_cancel', 'common_delete'),
								));
							}
						}
					}
				}
				else
				{
					$template->assign_block_vars('list.empty', array());
				}
				
				$fields .= '<input type="hidden" name="mode" value="create" />';
				$fields .= '<input type="hidden" name="' . $sub . '" value="' . $data_sub . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					
					'L_CREATE_LABEL'	=> sprintf($lang['sprintf_create'], $lang['type_1']),
					'L_CREATE_MODULE'	=> sprintf($lang['sprintf_create'], $lang['type_2']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
			
				break;

			case 'order':

				update($tbl, 'menu', $move, $data_id);
				orders($tbl, $data_sub);

				log_add(LOG_ADMIN, $log, $mode);

				$index = true;

				break;

			case 'delete':

				$data = data(MENU, $data_id, false, 1, true);

				if ( $data_id && $confirm )
				{
					$sql = sql(MENU, $mode, $data, 'file_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					orders(MENU);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['file_name']),

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
	
	$tmp = data($tbl, "menu_class = '$action' AND menu_type = 0", 'menu_order ASC', 1, false);
	
	debug($tmp);
	
	if ( $tmp )
	{
		foreach ( $tmp as $row )
		{
			if ( $row['menu_sub'] == '0' )
			{
				$cat[$row['menu_id']] = $row;
			}
		#	else if ( $row['menu_sub'] < 0 )
		#	{
		#		$mod[$row['menu_sub']*(-1)][$row['menu_id']] = $row;
		#	}
		#	else
		#	{
		#		$lab[$row['menu_sub']][$row['menu_id']] = $row;
		#	}
		}
		
		$cmax = array_pop(end($cat));
		
		foreach ( $cat as $ckey => $crow )
		{
			$cid	= $crow['menu_id'];
			$csub	= $crow['menu_sub'];
			$cname	= isset($lang[$crow['menu_name']]) ? $lang[$crow['menu_name']] : $crow['menu_name'];
			$corder	= $crow['menu_order'];
			
			$template->assign_block_vars('display.cat', array( 
				'NAME'		=> href('a_txt', $file, array('mode' => 'list', $sub => $cid), $cname, $cname),
				
				'MOVE_UP'	=> ( $corder != '10' )	? href('a_img', $file, array('mode' => 'order', 'move' => '-15', $sub => 0, $url => $cid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $corder != $cmax )	? href('a_img', $file, array('mode' => 'order', 'move' => '+15', $sub => 0, $url => $cid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
	
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $cid), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $cid), 'icon_cancel', 'common_delete'),
			));
			/*
			if ( isset($lab[$ckey]) )
			{
				$lmax[$ckey] = array_pop(end($lab[$ckey]));
				
				foreach ( $lab[$ckey] as $lkey => $lrow )
				{
					$lid	= $lrow['menu_id'];
					$lsub	= $lrow['menu_sub'];
					$lname	= isset($lang[$lrow['menu_name']]) ? $lang[$lrow['menu_name']] : $lrow['menu_name'];
					$lorder	= $lrow['menu_order'];
					
					$template->assign_block_vars('display.cat.lab', array( 
						'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $lid), $lname, $lname),
						
						'MOVE_UP'	=> ( $lorder != '10' )			? href('a_img', $file, array('mode' => 'order', 'move' => '-15', $sub => $lsub, $url => $lid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
						'MOVE_DOWN'	=> ( $lorder != $lmax[$ckey] )	? href('a_img', $file, array('mode' => 'order', 'move' => '+15', $sub => $lsub, $url => $lid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			
						'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $lid), 'icon_update', 'common_update'),
						'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $lid), 'icon_cancel', 'common_delete'),
					));
					
					if ( isset($mod[$lkey]) )
					{
						$smax[$lkey] = array_pop(end($mod[$lkey]));
						
						foreach ( $mod[$lkey] as $skey => $srow )
						{
							$sid	= $srow['menu_id'];
							$ssub	= $srow['menu_sub'];
							$sname	= isset($lang[$srow['menu_name']]) ? $lang[$srow['menu_name']] : $srow['menu_name'];
							$sorder	= $srow['menu_order'];
							
							$template->assign_block_vars('display.cat.lab.sub', array( 
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $sid), $sname, $sname),
								
								'MOVE_UP'	=> ( $sorder != '10' )			? href('a_img', $file, array('mode' => 'order', 'move' => '-15', $sub => $ssub, $url => $sid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
								'MOVE_DOWN'	=> ( $sorder != $smax[$lkey] )	? href('a_img', $file, array('mode' => 'order', 'move' => '+15', $sub => $ssub, $url => $sid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
					
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $sid), 'icon_update', 'common_update'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $sid), 'icon_cancel', 'common_delete'),
							));
						}
					}
				}
			}
			*/
		}
	}
	
	$fields	= '<input type="hidden" name="mode" value="create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $action),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['type_0']),

		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>