<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_label',
		'modes'		=> array(
			'admin'	=> array('title' => 'acp_label_admin'),
			'forum'	=> array('title' => 'acp_label_forum'),
			'mod'	=> array('title' => 'acp_label_mod'),
			'user'	=> array('title' => 'acp_label_user'),
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
	
	add_lang('label');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_LABEL;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['a_menu'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_label.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	debug($_POST, 'POST');
	
	$base = ($settings['smain']['label_drop']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'list', 'move_down', 'move_up', 'delete'))) ? $mode : false;

	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':

				$template->assign_block_vars('input', array());
				
				$vars = array(
					'label' => array(
						'title1' => 'input_data',
						'label_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'label_desc'	=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:25', 'required' => 'input_desc'),
					#	'label_type'	=> array('validate' => TXT,	'explain' => false,	'type' => 'switch:type', 'params' => $action),
						'label_type'	=> 'hidden',
						'label_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$submit )
				{
				#	$keys = ( !isset($_POST['label_name']) ) ? (( isset($_POST['submit_module']) ) ? key($_POST['submit_module']) : $main ) : 0;
				#	$name = ( !isset($_POST['label_name']) ) ? (( isset($_POST['submit_module']) ) ? request(array('label_module', $keys), TXT) : request('label_label', TXT) ) : request('label_name', TXT);
				#	$type = ( !isset($_POST['label_name']) ) ? (( isset($_POST['submit_module']) ) ? 2 : 1 ) : 0;
					
					$data_sql = array(
						'label_name'	=> request('label_name', TXT),
						'label_desc'	=> '',
						'label_type'	=> $action[0] . '_',
						'label_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(ACL_LABEL, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(ACL_LABEL, $vars, $error, $mode);

					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$data_sql['label_order'] = maxa(ACL_LABEL, 'label_order', false);
							
							$sql = sql(ACL_LABEL, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(ACL_LABEL, $mode, $data_sql, 'label_id', $data);
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
				
				build_output(ACL_LABEL, $vars, $data_sql);

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_EXPLAIN'	=> $lang['common_required'],
					'L_INPUT'	=> sprintf($lang['sprintf_' . $mode], $lang['title'], $data_sql['label_name']),

					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');

				break;
				
			case 'move_up':
			case 'move_down':
			
				move(ACL_LABEL, $mode, $order, false, false, false, false, $type);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;

			case 'delete':

				$data_sql = data(MENU, $data, false, 1, true);

				if ( $data && $confirm )
				{
					$sql = sql(MENU, $mode, $data_sql, 'file_id', $data);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);

					orders(MENU);

					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";

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
	
	$tmp = data(ACL_LABEL, false, 'label_order ASC', 1, false);
	
	if ( $tmp )
	{
		foreach ( $tmp as $row )
		{
			$tmp_act = $action[0] . '_';
			
			if ( trim($row['label_type'], '_') == $action[0] )
			{
				$tmp_data[] = $row;
			}
		}
	}
	
	if ( !$tmp_data )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$cnt = count($tmp_data);
		
		foreach ( $tmp_data as $row )
		{
			$id		= $row['label_id'];
			$order	= $row['label_order'];
			$name	= isset($lang[$row['label_name']]) ? $lang[$row['label_name']] : $row['label_name'];
			$tact	= $action[0] . '_';
			
			$template->assign_block_vars('display.row', array( 
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				
				'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'type' => $tact, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $cnt )	? href('a_img', $file, array('mode' => 'move_down', 'type' => $tact, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
	
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	$fields	= '<input type="hidden" name="mode" value="create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		
	#	'L_CREATE'			=> sprintf($lang['sprintf_create'], $lang['type_0']),
	#	'L_CREATE_LABEL'	=> sprintf($lang['sprintf_create'], $lang['type_1']),
	#	'L_CREATE_MODULE'	=> sprintf($lang['sprintf_create'], $lang['type_2']),
		
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>