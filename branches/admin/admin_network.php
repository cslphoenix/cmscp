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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_network'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_main']['_submenu_network'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_upload.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/network.php');
	
	$start			= ( request('start') ) ? request('start') : 0;
	$start			= ( $start < 0 ) ? 0 : $start;
	$network_id		= request(POST_NETWORK_URL);
	$network_type	= request('type');
	$confirm		= request('confirm');
	$mode			= request('mode');
	$move			= request('move');
	$path_network	= $root_path . $settings['path_network'] . '/';
	$show_index		= '';
	
	if ( !$userauth['auth_games'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_network.php', true));
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_network.tpl'));
				$template->assign_block_vars('network_edit', array());
				
				if ( $mode == '_create' )
				{
					$network = array (
						'network_name'	=> '',
						'network_url'	=> '',
						'network_image'	=> '',
						'network_type'	=> '1',
						'network_view'	=> '1',
					);
					$new_mode = '_create_save';
				}
				else
				{
					$network = get_data(NETWORK, $network_id, 1);
					$new_mode = '_update_save';
				}
				
				if ( $network['network_image'] )
				{
					$template->assign_block_vars('network_edit.network_image', array());
				}
				
				$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
				$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_NETWORK_URL . '" value="' . $network_id . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['network']),
					'L_NEW_EDIT'		=> sprintf($lang[$ssprintf], $lang['network_field']),
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['network']),
					'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['network']),
					'L_IMAGE'			=> sprintf($lang['sprintf_image'], $lang['network']),
					'L_URL'				=> $lang['network_url'],
					
					'L_TYPE_LINK'		=> $lang['network_link'],
					'L_TYPE_PARTNER'	=> $lang['network_partner'],
					'L_TYPE_SPONSOR'	=> $lang['network_sponsor'],
					
					'L_VIEW'			=> $lang['common_view'],
					'L_IMAGE_DELETE'	=> $lang['common_image_delete'],
					
					'NAME'				=> $network['network_name'],
					'URL'				=> $network['network_url'],
					'IMAGE'				=> $path_network . $network['network_image'],

					'S_TYPE_LINK'		=> ( $network['network_type'] == NETWORK_LINK ) ? ' checked="checked"' : '',
					'S_TYPE_PARTNER'	=> ( $network['network_type'] == NETWORK_PARTNER ) ? ' checked="checked"' : '',
					'S_TYPE_SPONSOR'	=> ( $network['network_type'] == NETWORK_SPONSOR ) ? ' checked="checked"' : '',
					'S_VIEW_YES'		=> ( $network['network_view'] ) ? ' checked="checked"' : '',
					'S_VIEW_NO'			=> ( !$network['network_view'] ) ? ' checked="checked"' : '',
					
					'S_FIELDS'			=> $s_fields,
					'S_ACTION'			=> append_sid('admin_network.php'),
				));
			
				$template->pparse('body');
				
				break;

			case '_create_save':
			
				$network_name	= request('network_name', 'text');
				$network_url	= request('network_url', 'text');
				$network_type	= request('network_type', 'num');
				$network_view	= request('network_view', 'num');
				$network_pic	= request_files('network_image');
				$info			= ( $network_type != NETWORK_LINK ) ? ( $network_type == NETWORK_PARTNER ) ? $lang['network_partner'] : $lang['network_sponsor'] : $lang['network_link'];
				
				$error_msg = '';
				$error_msg .= ( !$network_name ) ? $lang['msg_select_name'] : '';
				$error_msg .= ( !$network_url ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_url'] : '';
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}
				
				$max_row	= get_data_max(NETWORK, 'network_order', 'network_type = ' . $network_type);
				$next_order	= $max_order['max'] + 10;
				
				if ( $network_pic['temp'] )
				{
					$sql_pic = image_upload('_create', 'image_network', 'network_image', '', $network['network_image'], '', $root_path . $settings['path_network'] . '/', $network_pic['temp'], $network_pic['name'], $network_pic['size'], $network_pic['type']);
				}
				else
				{
					$sql_pic = '';
				}
				
				$sql = "INSERT INTO " . NETWORK . " (network_name, network_type, network_url, network_view, network_image, network_order)
							VALUES ('$network_name', '$network_type', '$network_url', '$network_view', '$sql_pic', '$next_order')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = sprintf($lang['create_network'], $info) . sprintf($lang['click_return_network'], '<a href="' . append_sid('admin_network.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NETWORK, sprintf($lang['create_network'], $info));
				message(GENERAL_MESSAGE, $message);

				break;

			case '_update_save':
			
				$network			= get_data(NETWORK, $network_id, 1);
				$network_db_type	= $network['network_type'];
				
				$network_name	= request('network_name', 'text');
				$network_url	= request('network_url', 'text');
				$network_type	= request('network_type', 'num');
				$network_view	= request('network_view', 'num');
				$network_image	= request_files('network_image');
				$info			= ( $network_type != NETWORK_LINK ) ? ( $network_type == NETWORK_PARTNER ) ? $lang['network_partner'] : $lang['network_sponsor'] : $lang['network_link'];
				
				$error_msg = '';
				$error_msg .= ( !$network_name ) ? $lang['msg_select_name'] : '';
				$error_msg .= ( !$network_url ) ? ( $error_msg ? '<br>' : '' ) . $lang['msg_select_url'] : '';
				
				if ( $error_msg )
				{
					message(GENERAL_ERROR, $error_msg . $lang['back']);
				}
				
				if ( $network_type != $network_db_type )
				{
					$max_row	= get_data_max(NETWORK, 'network_order', 'network_type = ' . $network_type);
					$max_order	= $max_row['max'];
					$next_order	= $max_order + 10;
					$sql_order	= ', network_order = ' . $next_order;
					
					orders('network', $network_type);
				}
				else
				{
					$sql_order	= '';
				}
				
				if ( $network_image['temp'] )
				{
					$sql_pic = image_upload('_update', 'image_network', 'network_image', '', $network['network_image'], '', $root_path . $settings['path_network'] . '/', $network_image['temp'], $network_image['name'], $network_image['size'], $network_image['type']);
				}
				else if ( request('network_image_delete') )
				{
					$sql_pic = image_delete($network['network_image'], '', $root_path . $settings['path_network'] . '/', 'network_image');
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
							network_view	= $network_view
							$sql_order
						WHERE network_id = $network_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				  
				$message = sprintf($lang['update_network'], $info)
					. sprintf($lang['click_return_network'], '<a href="' . append_sid('admin_network.php') . '">', '</a>')
					. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_network.php?mode=_update&' . POST_NETWORK_URL . '=' . $network_id) . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NETWORK, 'update_network');
				message(GENERAL_MESSAGE, $message);
	
				break;

			case '_order':
				
				update(NETWORK, 'network', $move, $network_id);
				orders('network', $network_type);
				
				break;
			
			case '_delete':
			
				$network		= get_data(NETWORK, $network_id, 1);
				$network_type	= $network['network_type'];
				$info			= ( $network_type != NETWORK_LINK ) ? ( $network_type == NETWORK_PARTNER ) ? $lang['network_partner'] : $lang['network_sponsor'] : $lang['network_link'];
				
				if ( $network_id && $confirm )
				{	
					$sql = 'DELETE FROM ' . NETWORK . ' WHERE network_id = ' . $network_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = sprintf($lang['delete_network'], $info) . sprintf($lang['click_return_network'], '<a href="' . append_sid('admin_network.php') . '">', '</a>');
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NETWORK, sprintf($lang['delete_network'], $info));
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $network_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_NETWORK_URL . '" value="' . $network_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> sprintf($lang['confirm_delete_network'], $info),
						'L_NO'				=> $lang['common_no'],
						'L_YES'				=> $lang['common_yes'],
						'S_FIELDS'	=> $s_fields,
						'S_ACTION'	=> append_sid('admin_network.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_network']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
			
				message(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_network.tpl'));
	$template->assign_block_vars('display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['network']),
		'L_CREATE'		=> sprintf($lang['sprintf_createn'], $lang['network_field']),
		'L_EXPLAIN'		=> $lang['network_explain'],
		
		'L_LINK'		=> $lang['network_link'],
		'L_PARTNER'		=> $lang['network_partner'],
		'L_SPONSOR'		=> $lang['network_sponsor'],
		
		'L_UPDATE'				=> $lang['common_update'],
		'L_DELETE'				=> $lang['common_delete'],
		'L_SETTINGS'			=> $lang['common_settings'],
		'L_VISIBLE'				=> $lang['common_visible'],
		
		'S_FIELDS'		=> $s_fields,
		'S_CREATE'		=> append_sid('admin_network.php?mode=_create'),
		'S_ACTION'		=> append_sid('admin_network.php'),
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
			$network_id	= $data_link[$i]['network_id'];
				
			$template->assign_block_vars('display.link_row', array(
				'CLASS' 			=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				
				'NAME'		=> $data_link[$i]['network_name'],
				'VISIBLE'	=> ( $data_link[$i]['network_view'] ) ? $images['icon_acp_yes'] : $images['icon_acp_no'],
				
				'MOVE_UP'			=> ( $data_link[$i]['network_order'] != '10' )				? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_LINK . '&amp;move=-15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'			=> ( $data_link[$i]['network_order'] != $max_link['max'] )	? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_LINK . '&amp;move=15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_UPDATE'			=> append_sid('admin_network.php?mode=_update&amp;' . POST_NETWORK_URL . '=' . $network_id),
				'U_DELETE'			=> append_sid('admin_network.php?mode=_delete&amp;' . POST_NETWORK_URL . '=' . $network_id)
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_link', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_partner )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_partner)); $i++ )
		{
			$network_id	= $data_partner[$i]['network_id'];
				
			$template->assign_block_vars('display.partner_row', array(
				'CLASS' 			=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				
				'NAME'		=> $data_partner[$i]['network_name'],
				'VISIBLE'	=> ( $data_partner[$i]['network_view'] ) ? $images['icon_acp_yes'] : $images['icon_acp_no'],
				
				'MOVE_UP'			=> ( $data_partner[$i]['network_order'] != '10' )					? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_PARTNER . '&amp;move=-15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'			=> ( $data_partner[$i]['network_order'] != $max_partner['max'] )	? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_PARTNER . '&amp;move=15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_UPDATE'			=> append_sid('admin_network.php?mode=_update&amp;' . POST_NETWORK_URL . '=' . $network_id),
				'U_DELETE'			=> append_sid('admin_network.php?mode=_delete&amp;' . POST_NETWORK_URL . '=' . $network_id)
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_partner', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_sponsor )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_sponsor)); $i++ )
		{
			$network_id	= $data_sponsor[$i]['network_id'];
				
			$template->assign_block_vars('display.sponsor_row', array(
				'CLASS' 			=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				
				'NAME'		=> $data_sponsor[$i]['network_name'],
				'VISIBLE'	=> ( $data_sponsor[$i]['network_view'] ) ? $images['icon_acp_yes'] : $images['icon_acp_no'],

				'MOVE_UP'			=> ( $data_sponsor[$i]['network_order'] != '10' )					? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_SPONSOR . '&amp;move=-15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'			=> ( $data_sponsor[$i]['network_order'] != $max_sponsor['max'] )	? '<a href="' . append_sid('admin_network.php?mode=_order&amp;type=' . NETWORK_SPONSOR . '&amp;move=15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_UPDATE'			=> append_sid('admin_network.php?mode=_update&amp;' . POST_NETWORK_URL . '=' . $network_id),
				'U_DELETE'			=> append_sid('admin_network.php?mode=_delete&amp;' . POST_NETWORK_URL . '=' . $network_id)
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_sponsor', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>