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
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_network';
	
	include('./pagestart.php');
	
	load_lang('network');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_NETWORK;
	$url	= POST_NETWORK;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_type	= request('type');
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_network'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_network'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_network.tpl',
		'ajax'		=> 'style/ajax_order.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->assign_block_vars('_input', array());
				
				$template->assign_var_from_handle('AJAX', 'ajax');
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					list($type) = ( isset($_POST['network_type']) ) ? each($_POST['network_type']) : '';
					$name		= ( isset($_POST['network_name']) ) ? str_replace("\'", "'", $_POST['network_name'][$type]) : '';
					
					$data = array(
								'network_name'	=> $name,
								'network_url'	=> '',
								'network_image'	=> '',
								'network_type'	=> $type,
								'network_view'	=> '1',
								'network_order'	=> maxa(NETWORK, 'network_order', "network_type = $type"),
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(NETWORK, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
								'network_name'	=> request('network_name', 2),
								'network_url'	=> request('network_url', 2),
								'network_image'	=> request('network_image', 2),
								'network_type'	=> request('network_type', 0),
								'network_view'	=> request('network_view', 0),
								'network_order'	=> request('network_order', 0) ? request('network_order', 0) : request('network_order_new', 0),
							);
<<<<<<< .mine
							
					$_pic = request_file('network_image');
					$info = ( $data['network_type'] != NETWORK_LINK ) ? ( $data['network_type'] == NETWORK_PARTNER ) ? $lang['partner'] : $lang['sponsor'] : $lang['link'];
					
					$error .= !$data['network_name']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= !$data['network_url']		? ( $error ? '<br />' : '' ) . $lang['msg_empty_url'] : '';
					$error .= !$data['network_type']	? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
					
					$data['network_image'] = ( !request('network_image_delete', 1) ) ? ( ( !$_pic ) ? $data['network_image'] : image_upload($mode, 'image_network', 'network_image', '', $data['network_image'], '', $path_dir, $_pic['temp'], $_pic['name'], $_pic['size'], $_pic['type'], $error) ) : image_delete($data['network_image'], '', $path_dir, 'network_image');
					
					if ( !$error )
					{
						$data['network_order'] = !$data['network_order'] ? maxa(NETWORK, 'network_order', 'network_type = ' . $data['network_type']) : $data['network_order'];
						
						if ( $mode == '_create' )
						{
							$sql = sql(NETWORK, $mode, $data);
							$msg = sprintf($lang['create'], $network_info) . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(NETWORK, $mode, $data, 'network_id', $data_id);
							$msg = sprintf($lang['update'], $info) . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data_id"));
						}
						
						orders(NETWORK, $data_type);
						
						$oCache->deleteCache('dsp_sn_network');
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
				
				( $data['network_image'] ) ? $template->assign_block_vars('_input._image', array()) : false;
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['network']),
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['field'], $data['network_name']),
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['network']),
					'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['network']),
					'L_IMAGE'			=> sprintf($lang['sprintf_image'], $lang['network']),
					'L_URL'				=> $lang['url'],
					'L_TYPE_LINK'		=> $lang['link'],
					'L_TYPE_PARTNER'	=> $lang['partner'],
					'L_TYPE_SPONSOR'	=> $lang['sponsor'],
=======
							
					$_pic = request_file('network_image');
					$info = ( $data['network_type'] != NETWORK_LINK ) ? ( $data['network_type'] == NETWORK_PARTNER ) ? $lang['partner'] : $lang['sponsor'] : $lang['link'];
>>>>>>> .r85
					
<<<<<<< .mine
					'L_VIEW'			=> $lang['common_view'],
					'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
					
					'NAME'				=> $data['network_name'],
					'URL'				=> $data['network_url'],
					'IMAGE'				=> $path_dir . $data['network_image'],
					
					'CUR_TYPE'		=> $data['network_type'],
					'CUR_ORDER'		=> $data['network_order'],

					'S_TYPE_LINK'		=> ( $data['network_type'] == NETWORK_LINK ) ? 'checked="checked"' : '',
					'S_TYPE_PARTNER'	=> ( $data['network_type'] == NETWORK_PARTNER ) ? 'checked="checked"' : '',
					'S_TYPE_SPONSOR'	=> ( $data['network_type'] == NETWORK_SPONSOR ) ? 'checked="checked"' : '',

					'S_VIEW_NO'			=> (!$data['network_view'] ) ? 'checked="checked"' : '',
					'S_VIEW_YES'		=> ( $data['network_view'] ) ? 'checked="checked"' : '',
					
					'S_ORDER'			=> simple_order(NETWORK, $data['network_type'], 'select', $data['network_order']),
					
					'S_ACTION'			=> check_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
=======
					$error .= !$data['network_name']	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= !$data['network_url']		? ( $error ? '<br />' : '' ) . $lang['msg_empty_url'] : '';
					$error .= !$data['network_type']	? ( $error ? '<br />' : '' ) . $lang['msg_select_type'] : '';
					
					$data['network_image'] = ( !request('network_image_delete', 1) ) ? ( ( !$_pic ) ? $data['network_image'] : image_upload($mode, 'image_network', 'network_image', '', $data['network_image'], '', $path_dir, $_pic['temp'], $_pic['name'], $_pic['size'], $_pic['type'], $error) ) : image_delete($data['network_image'], '', $path_dir, 'network_image');
					
					if ( !$error )
					{
						$data['network_order'] = !$data['network_order'] ? maxa(NETWORK, 'network_order', 'network_type = ' . $data['network_type']) : $data['network_order'];
						
						if ( $mode == '_create' )
						{
							$sql = sql(NETWORK, $mode, $data);
							$msg = sprintf($lang['create'], $network_info) . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(NETWORK, $mode, $data, 'network_id', $data_id);
							$msg = sprintf($lang['update'], $info) . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data_id"));
						}
						
						orders(NETWORK, $data_type);
						
						$oCache->deleteCache('dsp_sn_network');
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
				
				( $data['network_image'] ) ? $template->assign_block_vars('_input._image', array()) : false;
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['field'], $data['network_name']),
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['title']),
					'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['title']),
					'L_IMAGE'			=> sprintf($lang['sprintf_image'], $lang['title']),
					'L_URL'				=> $lang['network_url'],
					'L_TYPE_LINK'		=> $lang['network_link'],
					'L_TYPE_PARTNER'	=> $lang['network_partner'],
					'L_TYPE_SPONSOR'	=> $lang['network_sponsor'],
					
					'L_VIEW'			=> $lang['common_view'],
					'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
					
					'NAME'				=> $data['network_name'],
					'URL'				=> $data['network_url'],
					'IMAGE'				=> $path_dir . $data['network_image'],
					
					'CUR_TYPE'		=> $data['network_type'],
					'CUR_ORDER'		=> $data['network_order'],

					'S_TYPE_LINK'		=> ( $data['network_type'] == NETWORK_LINK ) ? 'checked="checked"' : '',
					'S_TYPE_PARTNER'	=> ( $data['network_type'] == NETWORK_PARTNER ) ? 'checked="checked"' : '',
					'S_TYPE_SPONSOR'	=> ( $data['network_type'] == NETWORK_SPONSOR ) ? 'checked="checked"' : '',

					'S_VIEW_NO'			=> (!$data['network_view'] ) ? 'checked="checked"' : '',
					'S_VIEW_YES'		=> ( $data['network_view'] ) ? 'checked="checked"' : '',
					
					'S_ORDER'			=> simple_order(NETWORK, $data['network_type'], 'select', $data['network_order']),
					
					'S_ACTION'			=> check_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
>>>>>>> .r85
				$template->pparse('body');
				
				break;

			case '_order':
				
				update(NETWORK, 'network', $move, $data_id);
				orders(NETWORK, $data_type);
				
				$oCache->deleteCache('dsp_sn_network');
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
			case '_delete':
			
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
					message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['field']));
				}

				$template->pparse('confirm');
				
				break;
			
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
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
	
	if ( $tmp_link )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_link)); $i++ )
		{
			$network_id		= $tmp_link[$i]['network_id'];
			$network_order	= $tmp_link[$i]['network_order'];
				
			$template->assign_block_vars('_display._link_row', array(
				'NAME'		=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$network_id") . '">' . $tmp_link[$i]['network_name'] . '</a>',
				'SHOW'		=> $tmp_link[$i]['network_view'] ? '<img src="' . $images['icon_option_show'] . '" title="' . $lang['common_info_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" title="' . $lang['common_info_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $network_order != '10' )		? '<a href="' . check_sid("$file?mode=_order&amp;type=" . NETWORK_LINK . "&amp;move=-15&amp;$url=$network_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $network_order != $max_link )	? '<a href="' . check_sid("$file?mode=_order&amp;type=" . NETWORK_LINK . "&amp;move=+15&amp;$url=$network_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$network_id") . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$network_id") . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_link', array()); }
	
	if ( $tmp_partner )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_partner)); $i++ )
		{
			$network_id		= $tmp_partner[$i]['network_id'];
			$network_order	= $tmp_partner[$i]['network_order'];
				
			$template->assign_block_vars('_display._partner_row', array(
				'NAME'		=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$network_id") . '">' . $tmp_partner[$i]['network_name'] . '</a>',
				'SHOW'		=> $tmp_partner[$i]['network_view'] ? '<img src="' . $images['icon_option_show'] . '" title="' . $lang['common_info_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" title="' . $lang['common_info_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $network_order != '10' )			? '<a href="' . check_sid("$file?mode=_order&amp;type=" . NETWORK_PARTNER . "&amp;move=-15&amp;$url=$network_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $network_order != $max_partner )	? '<a href="' . check_sid("$file?mode=_order&amp;type=" . NETWORK_PARTNER . "&amp;move=+15&amp;$url=$network_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$network_id") . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$network_id") . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_partner', array()); }
	
	if ( $tmp_sponsor )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_sponsor)); $i++ )
		{
			$network_id		= $tmp_sponsor[$i]['network_id'];
			$network_order	= $tmp_sponsor[$i]['network_order'];
				
			$template->assign_block_vars('_display._sponsor_row', array(
				'NAME'		=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$network_id") . '">' . $tmp_sponsor[$i]['network_name'] . '</a>',
				'SHOW'		=> $tmp_sponsor[$i]['network_view'] ? '<img src="' . $images['icon_option_show'] . '" title="' . $lang['common_info_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" title="' . $lang['common_info_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $network_order != '10' )			? '<a href="' . check_sid("$file?mode=_order&amp;type=" . NETWORK_SPONSOR . "&amp;move=-15&amp;$url=$network_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $network_order != $max_sponsor )	? '<a href="' . check_sid("$file?mode=_order&amp;type=" . NETWORK_SPONSOR . "&amp;move=+15&amp;$url=$network_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$network_id") . '"><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$network_id") . '"><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_sponsor', array());
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>