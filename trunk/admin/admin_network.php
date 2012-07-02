<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_network'] )
	{
		$module['hm_main']['sm_network'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_network';
	
	include('./pagestart.php');
	
	add_lang('network');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_NETWORK;
	$url	= POST_NETWORK;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_type	= request('type', TXT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$dir_path	= $root_path . $settings['path_network']['path'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_network'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_network.tpl',
		'ajax'		=> 'style/ajax_order.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'delete')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
				
				$template->assign_block_vars('input', array());
				
				$template->assign_var_from_handle('AJAX', 'ajax');
				
				$vars = array(
					'network' => array(
						'title1' => 'data_input',
						'network_name'			=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name', 'check' => true),
						'network_url'			=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_url', 'check' => true),
						'network_image'			=> array('validate' => TXT,	'type' => 'upload:network',	'explain' => true, 'params' => $dir_path),
						'network_type'			=> array('validate' => INT,	'type' => 'radio:network',	'explain' => true, 'params' => true),
						'network_view'			=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'network_order'			=> array('validate' => INT,	'type' => 'drop:order',		'explain' => true),
					),
				);
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					list($type) = ( isset($_POST['network_type']) ) ? each($_POST['network_type']) : '';
					$name		= ( isset($_POST['network_name']) ) ? str_replace("\'", "'", $_POST['network_name'][$type]) : '';
					
					$data = array(
						'network_name'	=> $name,
						'network_url'	=> '',
						'network_image'	=> '',
						'network_type'	=> $type,
						'network_view'	=> '1',
						'network_order'	=> '0',
					);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(NETWORK, $data_id, false, 1, true);
				}
				else
				{
					$temp = data(NETWORK, $data_id, false, 1, true);
					$temp = array_keys($temp);
					unset($temp[0]);
					
					debug($_POST);
					
					$data = build_request($temp, $vars, 'network', $error);
					
					debug($data);
					
					/*
					$data = array(
						'network_name'	=> request('network_name', 2),
						'network_url'	=> request('network_url', 5),
						'network_image'	=> request('network_image', 2),
						'network_type'	=> request('network_type', 0),
						'network_view'	=> request('network_view', 0),
						'network_order'	=> request('network_order', 0) ? request('network_order', 0) : request('network_order_new', 0),
					);
					
					$pic	= request_file('network_img');
					
					
					$error .= !$data['network_name']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= !$data['network_url']		? ( $error ? '<br />' : '' ) . $lang['msg_empty_url'] : '';
					$error .= !$data['network_type']	? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
					
					$data['network_image'] = ( !request('network_image_delete', 1) ) ? ( ( !$pic ) ? $data['network_image'] : image_upload($mode, 'image_network', 'network_image', '', $data['network_image'], '', $dir_path, $pic['temp'], $pic['name'], $pic['size'], $pic['type'], $error) ) : image_delete($data['network_image'], '', $dir_path, 'network_image');
					*/
					if ( !$error )
					{
						$info = ( $data['network_type'] != NETWORK_LINK ) ? ( $data['network_type'] == NETWORK_PARTNER ) ? $lang['partner'] : $lang['sponsor'] : $lang['link'];
						
						$data['network_order'] = $data['network_order'] ? $data['network_order'] : maxa(NETWORK, 'network_order', 'network_type = ' . $data['network_type']);
						
						if ( $mode == 'create' )
						{
							$sql = sql(NETWORK, $mode, $data);
							$msg = sprintf($lang['create'], $info) . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(NETWORK, $mode, $data, 'network_id', $data_id);
							$msg = sprintf($lang['update'], $info) . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data_id"));
						}
						
						orders(NETWORK, $data['network_type']);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
						
						log_add(LOG_ADMIN, $log, 'error', $error);
					}
				}
				
				( $data['network_image'] ) ? $template->assign_block_vars('input.image', array()) : false;
				
				build_output($data, $vars, 'input', false, NETWORK);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"current_image\" value=\"{$data['network_image']}\" />";
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'			=> sprintf($lang["sprintf_$mode"], $lang['field'], $data['network_name']),
				#	'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['title']),
				#	'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['title']),
				#	'L_IMAGE'			=> sprintf($lang['sprintf_image'], $lang['title']),
				#	'L_URL'				=> $lang['network_url'],
				#	'L_TYPE_LINK'		=> $lang['network_link'],
				#	'L_TYPE_PARTNER'	=> $lang['network_partner'],
				#	'L_TYPE_SPONSOR'	=> $lang['network_sponsor'],
				
				#	'L_VIEW'			=> $lang['common_view'],
				#	'L_UPLOAD'			=> sprintf($lang['sprintf_upload_info'], str_replace(':', ' x ', $settings['path_network']['dimension']), round($settings['path_network']['filesize']*1024) ),
				#	'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
				
				#	'NAME'				=> $data['network_name'],
				#	'URL'				=> $data['network_url'],
				#	'IMAGE'				=> $dir_path . $data['network_image'],
				
				#	'CUR_TYPE'		=> $data['network_type'],
				#	'CUR_ORDER'		=> $data['network_order'],

				#	'S_TYPE_LINK'		=> ( $data['network_type'] == NETWORK_LINK ) ? 'checked="checked"' : '',
				#	'S_TYPE_PARTNER'	=> ( $data['network_type'] == NETWORK_PARTNER ) ? 'checked="checked"' : '',
				#	'S_TYPE_SPONSOR'	=> ( $data['network_type'] == NETWORK_SPONSOR ) ? 'checked="checked"' : '',

				#	'S_VIEW_NO'			=> (!$data['network_view'] ) ? 'checked="checked"' : '',
				#	'S_VIEW_YES'		=> ( $data['network_view'] ) ? 'checked="checked"' : '',
					
				#	'S_ORDER'			=> simple_order(NETWORK, $data['network_type'], 'select', $data['network_order']),
					
					'S_ACTION'			=> check_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				$template->pparse('body');
				
				break;

			case 'order':
				
				update(NETWORK, 'network', $move, $data_id);
				orders(NETWORK, $data_type);
				
				$oCache->deleteCache('dsp_sn_network');
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
			case 'delete':
			
				$data = data(NETWORK, $data_id, false, 1, 1);
				$info = ( $data['network_type'] != NETWORK_LINK ) ? ( $data['network_type'] == NETWORK_PARTNER ) ? $lang['partner'] : $lang['sponsor'] : $lang['link'];
				
				if ( $data_id && $confirm )
				{
					$sql = sql(NETWORK, $mode, $data, 'network_id', $data_id);
					$msg = sprintf($lang['delete'], $info) . sprintf($lang['return'], check_sid($file), $acp_title);
					
					$oCache->deleteCache('dsp_sn_network');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], sprintf($lang['confirm'], $info), $data['network_name']),
						
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
	
	$fields .= '<input type="hidden" name="mode" value="create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_LINK'	=> $lang['network_link'],
		'L_PARTNER'	=> $lang['network_partner'],
		'L_SPONSOR'	=> $lang['network_sponsor'],
		
		'L_CREATE_LINK'		=> sprintf($lang['sprintf_new_createn'], $lang['network_link']),
		'L_CREATE_PARTNER'	=> sprintf($lang['sprintf_new_createn'], $lang['network_partner']),
		'L_CREATE_SPONSOR'	=> sprintf($lang['sprintf_new_createn'], $lang['network_sponsor']),
				
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max_link		= maxi(NETWORK, 'network_order', 'network_type = ' . NETWORK_LINK);
	$max_partner	= maxi(NETWORK, 'network_order', 'network_type = ' . NETWORK_PARTNER);
	$max_sponsor	= maxi(NETWORK, 'network_order', 'network_type = ' . NETWORK_SPONSOR);
	
	$tmp_link		= data(NETWORK, 'network_type = ' . NETWORK_LINK, 'network_order ASC', 1, false);
	$tmp_partner	= data(NETWORK, 'network_type = ' . NETWORK_PARTNER, 'network_order ASC', 1, false);
	$tmp_sponsor	= data(NETWORK, 'network_type = ' . NETWORK_SPONSOR, 'network_order ASC', 1, false);
	
	if ( !$tmp_link )
	{
		$template->assign_block_vars('display.no_entry_link', array());
	}
	else
	{
		for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, count($tmp_link)); $i++ )
		{
			$id		= $tmp_link[$i]['network_id'];
			$link	= $tmp_link[$i]['network_url'];
			$name	= $tmp_link[$i]['network_name'];
			$view	= $tmp_link[$i]['network_view'];
			$order	= $tmp_link[$i]['network_order'];
				
			$template->assign_block_vars('display.link_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, $name),
				'SHOW'		=> $view ? img('i_icon', 'icon_show', 'common_info_show') : img('i_icon', 'icon_show2', 'common_info_show2'),
				'LINK'		=> $link,
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_MAIN, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_link )	? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_MAIN, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_partner )
	{
		$template->assign_block_vars('display.no_entry_partner', array());
	}
	else
	{
		for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, count($tmp_partner)); $i++ )
		{
			$id		= $tmp_partner[$i]['network_id'];
			$link	= $tmp_partner[$i]['network_url'];
			$name	= $tmp_partner[$i]['network_name'];
			$view	= $tmp_partner[$i]['network_view'];
			$order	= $tmp_partner[$i]['network_order'];
				
			$template->assign_block_vars('display.partner_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, $name),
				'SHOW'		=> $view ? img('i_icon', 'icon_show', 'common_info_show') : img('i_icon', 'icon_show2', 'common_info_show2'),
				'LINK'		=> $link,
				
				'MOVE_UP'	=> ( $order != '10' )			? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_MAIN, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_partner )	? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_MAIN, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_sponsor )
	{
		$template->assign_block_vars('display.no_entry_sponsor', array());
	}
	else
	{
		for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, count($tmp_sponsor)); $i++ )
		{
			$id		= $tmp_sponsor[$i]['network_id'];
			$link	= $tmp_sponsor[$i]['network_url'];
			$name	= $tmp_sponsor[$i]['network_name'];
			$view	= $tmp_sponsor[$i]['network_view'];
			$order	= $tmp_sponsor[$i]['network_order'];
				
			$template->assign_block_vars('display.sponsor_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, $name),
				'SHOW'		=> $view ? img('i_icon', 'icon_show', 'common_info_show') : img('i_icon', 'icon_show2', 'common_info_show2'),
				'LINK'		=> $link,
				
				'MOVE_UP'	=> ( $order != '10' )			? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_MAIN, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_sponsor )	? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_MAIN, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
		
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>