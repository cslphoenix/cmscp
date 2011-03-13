<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 */

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_network'] )
	{
		$module['_headmenu_main']['_submenu_network'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_network';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('network');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_NETWORK_URL, 0);
	$data_type	= request('type');
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_network'] . '/';
	$s_index	= '';
	$s_fields	= '';
	$error		= '';
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_network'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_NETWORK, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $s_header ) ? redirect('admin/' . append_sid('admin_network.php', true)) : false;
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_network.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 2) )
				{
					list($type) = each($_POST['network_type']);
					
					$data = array(
						'network_name'	=> '',
						'network_url'	=> '',
						'network_image'	=> '',
						'network_type'	=> $type,
						'network_view'	=> '1',
						'network_order'	=> '',
					);
				}
				else if ( $mode == '_update' && !request('submit', 2) )
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
						'network_order'	=> request('network_order', 0),
					);
				}
				
				$data['network_image'] ? $template->assign_block_vars('_input._image', array()) : false;
				
				$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
				$s_fields .= '<input type="hidden" name="network_order" value="' . $data['network_order'] . '" />';
				$s_fields .= '<input type="hidden" name="' . POST_NETWORK_URL . '" value="' . $data_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['network']),
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['network_field'], $data['network_name']),
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['network']),
					'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['network']),
					'L_IMAGE'			=> sprintf($lang['sprintf_image'], $lang['network']),
					'L_URL'				=> $lang['network_url'],
					'L_TYPE_LINK'		=> $lang['network_link'],
					'L_TYPE_PARTNER'	=> $lang['network_partner'],
					'L_TYPE_SPONSOR'	=> $lang['network_sponsor'],
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
					
					'S_ACTION'			=> append_sid('admin_network.php'),
					'S_FIELDS'			=> $s_fields,
				));
				
				if ( request('submit', 2) )
				{
					$network_name	= request('network_name', 2);
					$network_url	= request('network_url', 2);
					$network_type	= request('network_type', 0);
					$network_view	= request('network_view', 0);
					$network_image	= request_file('network_image');
					$network_info	= ( $network_type != NETWORK_LINK ) ? ( $network_type == NETWORK_PARTNER ) ? $lang['network_partner'] : $lang['network_sponsor'] : $lang['network_link'];
					
					$error .= ( !$network_name )	? ( $error ? '<br>' : '' ) . $lang['msg_select_name'] : '';
					$error .= ( !$network_url )		? ( $error ? '<br>' : '' ) . $lang['msg_select_url'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$max = get_data_max(NETWORK, 'network_order', 'network_type = ' . $network_type);
							
							$sql_pic = ( $network_image ) ? image_upload('_create', 'image_network', 'network_image', '', '', '', $root_path . $settings['path_network'] . '/', $network_image['temp'], $network_image['name'], $network_image['size'], $network_image['type']) : '';
							
							$sql = "INSERT INTO " . NETWORK . " (network_name, network_type, network_url, network_view, network_image, network_order)
										VALUES ('$network_name', '$network_type', '$network_url', '$network_view', '$sql_pic', " . ($max['max'] + 10) . ")";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = sprintf($lang['create_network'], $network_info) . sprintf($lang['click_return_network'], '<a href="' . append_sid('admin_network.php') . '">', '</a>');
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
							
							$message = sprintf($lang['update_network'], $network_info)
								. sprintf($lang['click_return_network'], '<a href="' . append_sid('admin_network.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_network.php?mode=_update&' . POST_NETWORK_URL . '=' . $data_id) . '">', '</a>');
						}
						
						$oCache -> sCachePath = './../cache/';
						$oCache -> deleteCache('display_subnavi_network');
						
						log_add(LOG_ADMIN, LOG_SEK_NETWORK, $network_name);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
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
				
				log_add(LOG_ADMIN, LOG_SEK_NETWORK, $mode);
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('display_subnavi_network');
				
				$s_index = TRUE;
				
				break;
			
			case '_delete':
			
				$data			= get_data(NETWORK, $data_id, 1);
				$network_info	= ( $data['network_type'] != NETWORK_LINK ) ? ( $data['network_type'] == NETWORK_PARTNER ) ? $lang['network_partner'] : $lang['network_sponsor'] : $lang['network_link'];
				
				if ( $data_id && $confirm )
				{	
					$sql = "DELETE FROM " . NETWORK . " WHERE network_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('display_subnavi_network');
				
					$message = sprintf($lang['delete_network'], $network_info) . sprintf($lang['click_return_network'], '<a href="' . append_sid('admin_network.php') . '">', '</a>');
					
					log_add(LOG_ADMIN, LOG_SEK_NETWORK, $mode, $data['network_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields .= '<input type="hidden" name="mode" value="_delete" />';
					$s_fields .= '<input type="hidden" name="' . POST_NETWORK_URL . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], sprintf($lang['delete_confirm_network'], $network_info), $data['network_name']),
						
						'S_ACTION'	=> append_sid('admin_network.php'),
						'S_FIELDS'	=> $s_fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['network_field']));
				}

				$template->pparse('body');
				
				break;
			
			default:
			
				message(GENERAL_ERROR, $lang['msg_no_module_select']);
				
				break;
		}
	
		if ( $s_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_network.tpl'));
	$template->assign_block_vars('_display', array());
	
	$s_fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['network']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_createn'], $lang['network_field']),
		'L_EXPLAIN'	=> $lang['network_explain'],
		'L_LINK'	=> $lang['network_link'],
		'L_PARTNER'	=> $lang['network_partner'],
		'L_SPONSOR'	=> $lang['network_sponsor'],
		
		'S_CREATE'	=> append_sid('admin_network.php?mode=_create'),
		'S_ACTION'	=> append_sid('admin_network.php'),
		'S_FIELDS'	=> $s_fields,
	));
	
	$max_link		= get_data_max(NETWORK, 'network_order', 'network_type = ' . NETWORK_LINK);
	$max_partner	= get_data_max(NETWORK, 'network_order', 'network_type = ' . NETWORK_PARTNER);
	$max_sponsor	= get_data_max(NETWORK, 'network_order', 'network_type = ' . NETWORK_SPONSOR);
	
	$data_link		= get_data_array(NETWORK, 'network_type = ' . NETWORK_LINK, 'network_order', 'ASC');
	$data_partner	= get_data_array(NETWORK, 'network_type = ' . NETWORK_PARTNER, 'network_order', 'ASC');
	$data_sponsor	= get_data_array(NETWORK, 'network_type = ' . NETWORK_SPONSOR, 'network_order', 'ASC');
	
	if ( $data_link )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_link)); $i++ )
		{
			$network_id		= $data_link[$i]['network_id'];
			$network_order	= $data_link[$i]['network_order'];
				
			$template->assign_block_vars('_display._link_row', array(
				'NAME'		=> $data_link[$i]['network_name'],
				'SHOW'		=> ( $data_link[$i]['network_view'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $network_order != '10' )				? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_LINK . '&amp;move=-15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $network_order != $max_link['max'] )	? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_LINK . '&amp;move=+15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid('admin_network.php?mode=_update&amp;' . POST_NETWORK_URL . '=' . $network_id),
				'U_DELETE'	=> append_sid('admin_network.php?mode=_delete&amp;' . POST_NETWORK_URL . '=' . $network_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_link', array());
	}
	
	if ( $data_partner )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_partner)); $i++ )
		{
			$network_id		= $data_partner[$i]['network_id'];
			$network_order	= $data_partner[$i]['network_order'];
				
			$template->assign_block_vars('_display._partner_row', array(
				'NAME'		=> $data_partner[$i]['network_name'],
				'SHOW'		=> ( $data_partner[$i]['network_view'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',
				
				'MOVE_UP'	=> ( $network_order != '10' )					? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_PARTNER . '&amp;move=-15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $network_order != $max_partner['max'] )	? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_PARTNER . '&amp;move=+15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid('admin_network.php?mode=_update&amp;' . POST_NETWORK_URL . '=' . $network_id),
				'U_DELETE'	=> append_sid('admin_network.php?mode=_delete&amp;' . POST_NETWORK_URL . '=' . $network_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry_partner', array());
	}
	
	if ( $data_sponsor )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_sponsor)); $i++ )
		{
			$network_id		= $data_sponsor[$i]['network_id'];
			$network_order	= $data_sponsor[$i]['network_order'];
				
			$template->assign_block_vars('_display._sponsor_row', array(
				'NAME'		=> $data_sponsor[$i]['network_name'],
				'SHOW'		=> ( $data_sponsor[$i]['network_view'] ) ? '<img src="' . $images['icon_option_show'] . '" alt="" />' : '<img src="' . $images['icon_option_show2'] . '" alt="" />',

				'MOVE_UP'	=> ( $network_order != '10' )					? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_SPONSOR . '&amp;move=-15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $network_order != $max_sponsor['max'] )	? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_SPONSOR . '&amp;move=+15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid('admin_network.php?mode=_update&amp;' . POST_NETWORK_URL . '=' . $network_id),
				'U_DELETE'	=> append_sid('admin_network.php?mode=_delete&amp;' . POST_NETWORK_URL . '=' . $network_id),
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