<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_NETWORK',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'ACP_NETWORK'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_NETWORK';
	
	include('./pagestart.php');
	
	add_lang('network');
	acl_auth('A_NETWORK');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$log	= SECTION_NETWORK;
	$file	= basename(__FILE__) . $iadds;

	$data	= request('id', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$cancel ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down'))) ? $mode : 'default';
	$path = $root_path . $settings['path_network']['path'];
    $_tpl = ($mode === 'delete') ? 'confirm' : 'body';
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);

	switch ( $mode )
	{
		case 'create':
		case 'update':
			
			$template->assign_block_vars('input', array());

			$vars = array(
				'network' => array(
					'title'	=> 'INPUT_DATA',
					'network_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'network_url'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_url'),
					'network_image'	=> array('validate' => TXT,	'explain' => false,	'type' => 'upload:image',	'params' => array($path, 'network')),
					'network_type'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:network',	'params' => array(false, true, false)),
					'network_view'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno'),
					'network_order'	=> 'hidden',
				),
			);

			if ( $mode == 'create' && !$submit )
			{
				list($type) = ( isset($_POST['network_type']) ) ? each($_POST['network_type']) : '';
				$name		= ( isset($_POST['network_name']) ) ? str_replace("\'", "'", $_POST['network_name'][$type]) : '';
				
				$data_sql = array(
					'network_name'	=> $name,
					'network_url'	=> '',
					'network_image'	=> '',
					'network_type'	=> $type,
					'network_view'	=> '1',
					'network_order'	=> '0',
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(NETWORK, $data, false, 1, 'row');
			}
			else
			{
		#		debug($_POST);
				
				$data_sql = build_request(NETWORK, $vars, $error, $mode);
				
		#		debug($data);
				
				/*
				$data_sql = array(
					'network_name'	=> request('network_name', 2),
					'network_url'	=> request('network_url', 5),
					'network_image'	=> request('network_image', 2),
					'network_type'	=> request('network_type', 0),
					'network_view'	=> request('network_view', 0),
					'network_order'	=> request('network_order', 0) ? request('network_order', 0) : request('network_order_new', 0),
				);
				
				$pic	= request_file('network_img');
				
				
				$error[] = !$data['network_name']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
				$error[] = !$data['network_url']		? ( $error ? '<br />' : '' ) . $lang['msg_empty_url'] : '';
				$error[] = !$data['network_type']	? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
				
				$data['network_image'] = ( !request('network_image_delete', 1) ) ? ( ( !$pic ) ? $data['network_image'] : upload_image($mode, 'image_network', 'network_image', '', $data['network_image'], '', $path, $pic['temp'], $pic['name'], $pic['size'], $pic['type'], $error) ) : image_delete($data['network_image'], '', $path, 'network_image');
				*/
				if ( !$error )
				{
					$info = ( $data_sql['network_type'] != NETWORK_LINK ) ? ( $data_sql['network_type'] == NETWORK_PARTNER ) ? $lang['partner'] : $lang['sponsor'] : $lang['link'];
					
					if ( $mode == 'create' )
					{
						$data_sql['network_order'] = _max(NETWORK, 'network_order', 'network_type = ' . $data_sql['network_type']);
						
						$sql = sql(NETWORK, $mode, $data_sql);
						$msg = sprintf($lang['CREATE'], $info) . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else
					{
						$sql = sql(NETWORK, $mode, $data_sql, 'network_id', $data);
						$msg = sprintf($lang['UPDATE'], $info) . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			( $data_sql['network_image'] ) ? $template->assign_block_vars('input.image', array()) : false;
			
			build_output(NETWORK, $vars, $data_sql);

			$fields .= build_fields(array(
				'mode'	=> $mode,
				'id'	=> $data,
			));

			
		#	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
		#	$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		#	$fields .= "<input type=\"hidden\" name=\"current_image\" value=\"{$data_sql['network_image']}\" />";
			
			$template->assign_vars(array(
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['network_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
			
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'delete':
		
			$data_sql = data(NETWORK, $data_id, false, 1, 'set');
			$info = ( $data['network_type'] != NETWORK_LINK ) ? ( $data['network_type'] == NETWORK_PARTNER ) ? $lang['partner'] : $lang['sponsor'] : $lang['link'];
			
			if ( $data && $confirm )
			{
				$sql = sql(NETWORK, $mode, $data_sql, 'network_id', $data);
				$msg = sprintf($lang['DELETE'], $info) . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
				
				$oCache->deleteCache('dsp_sn_network');
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], sprintf($lang['CONFIRM'], $info), $data['network_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}

			break;

		case 'move_up':
		case 'move_down':
			
			move(NETWORK, $mode, $order, false, 'network_type', $type);
			log_add(LOG_ADMIN, $log, $mode);
			
		case 'default':
		
			$template->assign_block_vars('display', array());

			$fields = build_fields(array('mode' => 'create'));
			$sqlout = data(NETWORK, '', 'network_order ASC', 1, 'group', false, false, 'network_type');
			
			foreach ( $sqlout as $type => $row )
			{
				if ( !$row )
				{
					$template->assign_block_vars('display.no_entry_' . $type, array());
				}
				else
				{
					$max = count($row);

					foreach ( $row as $tmp_row )
					{
						$id		= $tmp_row['network_id'];
						$link	= $tmp_row['network_url'];
						$name	= $tmp_row['network_name'];
						$view	= $tmp_row['network_view'];
						$order	= $tmp_row['network_order'];
							
						$template->assign_block_vars('display.row_' . $type, array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
							'SHOW'		=> $view ? img('i_icon', 'icon_show', 'common_info_show') : img('i_icon', 'icon_show2', 'common_info_show2'),
							'LINK'		=> $link,
							
							'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'type' => $type, 'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
							'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'type' => $type, 'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
					
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
						));
					}
				}
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				
				'L_LINK'	=> $lang['NETWORK_LINK'],
				'L_PARTNER'	=> $lang['NETWORK_PARTNER'],
				'L_SPONSOR'	=> $lang['NETWORK_SPONSOR'],
				
				'L_CREATE_LINK'		=> sprintf($lang['STF_CREATE'], $lang['NETWORK_LINK']),
				'L_CREATE_PARTNER'	=> sprintf($lang['STF_CREATE'], $lang['NETWORK_PARTNER']),
				'L_CREATE_SPONSOR'	=> sprintf($lang['STF_CREATE'], $lang['NETWORK_SPONSOR']),
						
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>