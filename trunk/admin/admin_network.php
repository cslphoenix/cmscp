<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_network'] )
	{
		$module['_headmenu_01_main']['_submenu_network'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_network';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('network');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_NETWORK;
	$url	= POST_NETWORK_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_type	= request('type');
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_network'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['network']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_network'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_network.tpl'));
				$template->assign_block_vars('_input', array());
				
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
								'network_order'	=> '',
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(NETWORK, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
								'network_name'	=> request('network_name', 2),
								'network_url'	=> request('network_url', 2),
								'network_image'	=> request('network_image', 2),
								'network_type'	=> request('network_type', 0),
								'network_view'	=> request('network_view', 0),
								'network_order'	=> ( request('network_order_new', 0) ) ? request('network_order_new', 0) : request('network_order', 0),
							);
				}
				
				( $data['network_image'] ) ? $template->assign_block_vars('_input._image', array()) : false;
				
				$head = array(
							'0' => array('network_type' => NETWORK_LINK, 'network_name' => $lang['link']),
							'1' => array('network_type' => NETWORK_PARTNER, 'network_name' => $lang['partner']),
							'2' => array('network_type' => NETWORK_SPONSOR, 'network_name' => $lang['sponsor']),
						);
						
				$type = ( $data['network_type'] ) ? " WHERE network_type = " . $data['network_type'] : false;
				
				$sql = "SELECT * FROM " . NETWORK . "$type ORDER BY network_type, network_order ASC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$entrys = $db->sql_fetchrowset($result);
				
				$s_order = "<select class=\"select\" name=\"network_order_new\" id=\"network_order\">";
				$s_order .= "<option selected=\"selected\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
				
				for ( $i = 0; $i < count($head); $i++ )
				{
					$entry = '';
					
					for ( $j = 0; $j < count($entrys); $j++ )
					{
						if ( $head[$i]['network_type'] == $entrys[$j]['network_type'] )
						{
							$entry .= ( $entrys[$j]['network_order'] == 10 ) ? "<option value=\"5\">" . sprintf($lang['sprintf_select_before'], $entrys[$j]['network_name']) . "</option>" : '';
							$entry .= "<option value=\"" . ($entrys[$j]['network_order'] + 5) . "\">" . sprintf($lang['sprintf_select_order'], $entrys[$j]['network_name']) . "</option>";
						}
					}
					
					if ( $entry != '' )
					{
						$s_order .= '<optgroup label="' . $head[$i]['network_name'] . '">';
						$s_order .= $entry;
						$s_order .= '</optgroup>';
					}
				}
				
				$s_order .= '</select>';
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"network_order\" value=" . $data['network_order'] . " />";
				
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
					'L_VIEW'			=> $lang['common_view'],
					'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
					
					'NAME'				=> $data['network_name'],
					'URL'				=> $data['network_url'],
					'IMAGE'				=> $path_dir . $data['network_image'],

					'S_TYPE_LINK'		=> ( $data['network_type'] == NETWORK_LINK ) ? ' checked="checked"' : '',
					'S_TYPE_PARTNER'	=> ( $data['network_type'] == NETWORK_PARTNER ) ? ' checked="checked"' : '',
					'S_TYPE_SPONSOR'	=> ( $data['network_type'] == NETWORK_SPONSOR ) ? ' checked="checked"' : '',
					'S_VIEW_YES'		=> ( $data['network_view'] ) ? ' checked="checked"' : '',
					'S_VIEW_NO'			=> ( !$data['network_view'] ) ? ' checked="checked"' : '',
					
					'S_ORDER'			=> $s_order,
					
					'S_ACTION'			=> append_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				if ( request('submit', 1) )
				{
				#	$network_name	= request('network_name', 2);
				#	$network_url	= request('network_url', 2);
				#	$network_type	= request('network_type', 0);
				#	$network_view	= request('network_view', 0);
				#	$network_image	= request_file('network_image');
				#	$network_info	= ( $network_type != NETWORK_LINK ) ? ( $network_type == NETWORK_PARTNER ) ? $lang['partner'] : $lang['sponsor'] : $lang['link'];
					
				#	$error .= ( !$network_name )	? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
				#	$error .= ( !$network_url )		? ( $error ? '<br />' : '' ) . $lang['msg_select_url'] : '';
				
					$data = array(
								'network_name'	=> request('network_name', 2),
								'network_url'	=> request('network_url', 2),
								'network_type'	=> request('network_type', 0),
								'network_view'	=> request('network_view', 0),
								'network_order'	=> ( request('network_order_new', 0) ) ? request('network_order_new', 0) : request('network_order', 0),
							);
							
					$network_img	= request_file('network_image', 2);
					$network_info	= ( $data['network_type'] != NETWORK_LINK ) ? ( $data['network_type'] == NETWORK_PARTNER ) ? $lang['partner'] : $lang['sponsor'] : $lang['link'];
							
					$data['network_image'] = ( !$network_img ) ? '' : image_upload('_create', 'image_network', 'network_image', '', '', '', $root_path . $settings['path_network'] . '/', $network_img['temp'], $network_img['name'], $network_img['size'], $network_img['type'], $error);
					
					$error .= ( !$data['network_name'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['network_url'] )		? ( $error ? '<br />' : '' ) . $lang['msg_empty_url'] : '';
					$error .= ( !$data['network_type'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_type'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							foreach ( $data as $key => $var )
							{
								$keys[] = $key;
								$vars[] = $var;
							}
							
							$sql = 'INSERT INTO ' . NETWORK . ' (' . implode(', ', $keys) . ') VALUES (\'' . implode('\', \'', $vars) . '\')';
						#	$max = get_data_max(NETWORK, 'network_order', 'network_type = ' . $network_type);
						#	$pic = ( $network_image ) ? image_upload('_create', 'image_network', 'network_image', '', '', '', $root_path . $settings['path_network'] . '/', $network_image['temp'], $network_image['name'], $network_image['size'], $network_image['type']) : '';
						#	$sql = "INSERT INTO " . NETWORK . " (network_name, network_type, network_url, network_view, network_image, network_order)
						#				VALUES ('$network_name', '$network_type', '$network_url', '$network_view', '$pic', " . ($max['max'] + 10) . ")";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = sprintf($lang['create'], $network_info) . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
						}
						else
						{
							if ( $network_type != $data['network_type'] )
							{
								$max_row	= get_data_max(NETWORK, 'network_order', 'network_type = ' . $network_type);
								$next_order	= $max_row['max'] + 10;
								
								orders('network', $network_type);
								
								$sql_order	= ', network_order = ' . $next_order;
							}
							else
							{
								$sql_order	= '';
							}
							
							if ( $network_image )
							{
								$sql_pic = image_upload('_update', 'image_network', 'network_image', '', $data['network_image'], '', $root_path . $settings['path_network'] . '/', $network_image['temp'], $network_image['name'], $network_image['size'], $network_image['type']);
							}
							else if ( request('network_image_delete') )
							{
								$sql_pic = image_delete($data['network_image'], '', $root_path . $settings['path_network'] . '/', 'network_image');
							}
							else
							{
								$sql_pic = '';
							}
							
							$sql = "UPDATE " . NETWORK . " SET
										network_name	= '$network_name',
										network_type	= '$network_type',
										network_url		= '$network_url',
										$sql_pic
										network_view	= '$network_view'
										$sql_order
									WHERE network_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = sprintf($lang['update'], $network_info)
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&$url=$data_id") . '">', '</a>');
						}
						
						#$oCache -> sCachePath = './../cache/';
						#$oCache -> deleteCache('display_subnavi_network');
						
						orders(NETWORK, $data_type);
						
						log_add(LOG_ADMIN, $log, $data['network_name']);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
			
				$template->pparse('body');
				
				break;

			case '_order':
				
				update(NETWORK, 'network', $move, $data_id);
				orders(NETWORK, $data_type);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				#$oCache -> sCachePath = './../cache/';
				#$oCache -> deleteCache('display_subnavi_network');
				
				$index = true;
				
				break;
			
			case '_delete':
			
				$data			= get_data(NETWORK, $data_id, 1);
				$network_info	= ( $data['network_type'] != NETWORK_LINK ) ? ( $data['network_type'] == NETWORK_PARTNER ) ? $lang['partner'] : $lang['sponsor'] : $lang['link'];
				
				if ( $data_id && $confirm )
				{	
					$sql = "DELETE FROM " . NETWORK . " WHERE network_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					#$oCache -> sCachePath = './../cache/';
					#$oCache -> deleteCache('display_subnavi_network');
				
					$message = sprintf($lang['delete'], $network_info) . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					
					log_add(LOG_ADMIN, $log, $mode, $data['network_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], sprintf($lang['confirm'], $network_info), $data['network_name']),
						
						'S_ACTION'	=> append_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['field'])); }

				$template->pparse('body');
				
				break;
			
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_network.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['network']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_createn'], $lang['field']),
		'L_EXPLAIN'	=> $lang['explain'],
		'L_LINK'	=> $lang['link'],
		'L_PARTNER'	=> $lang['partner'],
		'L_SPONSOR'	=> $lang['sponsor'],
		
		'S_CREATE'	=> append_sid("$file?mode=_create"),
		'S_ACTION'	=> append_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max_link		= get_data_max(NETWORK, 'network_order', 'network_type = ' . NETWORK_LINK);
	$max_partner	= get_data_max(NETWORK, 'network_order', 'network_type = ' . NETWORK_PARTNER);
	$max_sponsor	= get_data_max(NETWORK, 'network_order', 'network_type = ' . NETWORK_SPONSOR);
	
#	$tmp_link		= get_data_array(NETWORK, 'network_type = ' . NETWORK_LINK, 'network_order', 'ASC');
#	$tmp_partner	= get_data_array(NETWORK, 'network_type = ' . NETWORK_PARTNER, 'network_order', 'ASC');
#	$tmp_sponsor	= get_data_array(NETWORK, 'network_type = ' . NETWORK_SPONSOR, 'network_order', 'ASC');
	
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
				'NAME'		=> $tmp_link[$i]['network_name'],
				'SHOW'		=> ( $tmp_link[$i]['network_view'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $network_order != '10' ) ? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NETWORK_LINK . "&amp;move=-15&amp;$url=$network_id") .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $network_order != $max_link['max'] ) ? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NETWORK_LINK . "&amp;move=+15&amp;$url=$network_id") .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$network_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$network_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry_link', array()); }
	
	if ( $tmp_partner )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_partner)); $i++ )
		{
			$network_id		= $tmp_partner[$i]['network_id'];
			$network_order	= $tmp_partner[$i]['network_order'];
				
			$template->assign_block_vars('_display._partner_row', array(
				'NAME'		=> $tmp_partner[$i]['network_name'],
				'SHOW'		=> ( $tmp_partner[$i]['network_view'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $network_order != '10' ) ? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NETWORK_PARTNER . "&amp;move=-15&amp;$url=$network_id") .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $network_order != $max_partner['max'] ) ? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NETWORK_PARTNER . "&amp;move=+15&amp;$url=$network_id") .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$network_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$network_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry_partner', array()); }
	
	if ( $tmp_sponsor )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_sponsor)); $i++ )
		{
			$network_id		= $tmp_sponsor[$i]['network_id'];
			$network_order	= $tmp_sponsor[$i]['network_order'];
				
			$template->assign_block_vars('_display._sponsor_row', array(
				'NAME'		=> $tmp_sponsor[$i]['network_name'],
				'SHOW'		=> ( $tmp_sponsor[$i]['network_view'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',

				'MOVE_UP'	=> ( $network_order != '10' ) ? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NETWORK_SPONSOR . "&amp;move=-15&amp;$url=$network_id") .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $network_order != $max_sponsor['max'] ) ? '<a href="' . append_sid("$file?mode=_order&amp;type=" . NETWORK_SPONSOR . "&amp;move=+15&amp;$url=$network_id") .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$network_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$network_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_entry_sponsor', array()); }
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>