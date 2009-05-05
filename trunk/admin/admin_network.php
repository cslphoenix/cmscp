<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel � 2009
	* @code:	Sebastian Frickel � 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_network'] || $userdata['user_level'] == ADMIN )
	{
		$module['main']['network'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	include($root_path . 'includes/functions_admin.php');
	
	if ( !$userauth['auth_games'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_network.php', true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_NETWORK_URL]) || isset($HTTP_GET_VARS[POST_NETWORK_URL]) )
	{
		$network_id = ( isset($HTTP_POST_VARS[POST_NETWORK_URL]) ) ? intval($HTTP_POST_VARS[POST_NETWORK_URL]) : intval($HTTP_GET_VARS[POST_NETWORK_URL]);
	}
	else
	{
		$network_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		if ( isset($HTTP_POST_VARS['network_add']) || isset($HTTP_GET_VARS['network_add']) )
		{
			$mode = 'network_add';
			
			list($type) = each($HTTP_POST_VARS['network_add']);
			
			$network_type = intval($type);
			$network_name = stripslashes($HTTP_POST_VARS['network_name'][$type]);
		}
		else
		{
			$mode = '';
		}
	}
	
	$show_index = '';
	
	if ( !empty($mode) )
	{
		switch ($mode)
		{
			case 'network_add':
			case 'network_edit':
			
				$template->set_filenames(array('body' => './../admin/style/acp_network.tpl'));
				$template->assign_block_vars('network_edit', array());
				
				if ( $mode == 'network_edit' )
				{
					$network	= get_data('network', $network_id, 0);
					$new_mode	= 'network_update';
					
					$network_name	= $network['network_name'];
					$network_type	= $network['network_type'];
					$network_url	= $network['network_url'];
				}
				else
				{
					$network = array (
						'network_image'	=> '',
						'network_view'	=> '1',
					);
					
					$network_url = '';

					$new_mode = 'network_create';
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_NETWORK_URL . '" value="' . $network_id . '" />';
				
				$info = ( $network_type != '1' ) ? ( $network_type == '2' ) ? $lang['network_partner'] : $lang['network_sponsor'] : $lang['network_link'];

				$template->assign_vars(array(
					'L_NETWORK_HEAD'		=> $lang['network_head'],
					'L_NETWORK_NEW_EDIT'	=> ( $mode == 'network_add' ) ? sprintf($lang['network_add'], $info) : sprintf($lang['network_edit'], $info),
					'L_REQUIRED'			=> $lang['required'],
					
					'L_NETWORK_NAME'	=> $lang['network_name'],
					'L_NETWORK_TYPE'	=> $lang['network_type'],
					'L_NETWORK_URL'		=> $lang['network_url'],
					'L_NETWORK_IMAGE'	=> $lang['network_image'],
					'L_NETWORK_VIEW'	=> $lang['common_view'],
					'L_TYPE_LINK'		=> $lang['network_link'],
					'L_TYPE_PARTNER'	=> $lang['network_partner'],
					'L_TYPE_SPONSOR'	=> $lang['network_sponsor'],
					
					'L_SUBMIT'	=> $lang['common_submit'],
					'L_RESET'	=> $lang['common_reset'],
					'L_YES'		=> $lang['common_yes'],
					'L_NO'		=> $lang['common_no'],
					
					'NETWORK_NAME'	=> $network_name,
					'NETWORK_URL'	=> $network_url,

					'S_CHECKED_TYPE_LINK'		=> ( $network_type == '1' ) ? ' checked="checked"' : '',
					'S_CHECKED_TYPE_PARTNER'	=> ( $network_type == '2' ) ? ' checked="checked"' : '',
					'S_CHECKED_TYPE_SPONSOR'	=> ( $network_type == '3' ) ? ' checked="checked"' : '',
					'S_CHECKED_VIEW_YES'		=> ( $network['network_view'] ) ? ' checked="checked"' : '',
					'S_CHECKED_VIEW_NO'			=> ( !$network['network_view'] ) ? ' checked="checked"' : '',
					
					'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
					'S_NETWORK_ACTION'	=> append_sid('admin_network.php'),
				));
			
				$template->pparse('body');
				
			break;

			case 'network_create':
			
				$network_name	= ( isset($HTTP_POST_VARS['network_name']) )	? trim($HTTP_POST_VARS['network_name']) : '';
				$network_url	= ( isset($HTTP_POST_VARS['network_url']) )		? trim($HTTP_POST_VARS['network_url']) : '';
				$network_type	= ( isset($HTTP_POST_VARS['network_type']) )	? intval($HTTP_POST_VARS['network_type']) : '';
				$network_view	= ( isset($HTTP_POST_VARS['network_view']) )	? intval($HTTP_POST_VARS['network_view']) : '';
				
				$error = ''; 
				$error_msg = '';
			
				if ( !$network_name )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_name'];
				}
				
				if ( !$network_url )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_url'];
				}
				
				if ( $error )
				{
					message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
				}
				
				$sql = 'SELECT MAX(network_order) AS max_order
							FROM ' . NETWORK . '
							WHERE network_type = ' . $network_type;
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				$sql = 'INSERT INTO ' . NETWORK . " (network_name, network_type, network_url, network_view, network_order)
							VALUES ('" . str_replace("\'", "''", $network_name) . "', $network_type, '" . $network_url . "', $network_view, $next_order)";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NETWORK, 'acp_network_add', $network_name);
				
				$info = ( $network_type != '1' ) ? ( $network_type == '2' ) ? $lang['network_partner'] : $lang['network_sponsor'] : $lang['network_link'];
	
				$message = sprintf($lang['create_network'], $info) . '<br><br>' . sprintf($lang['click_return_network'], '<a href="' . append_sid('admin_network.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;

			case 'network_update':
			
				$row		= get_data('network', $network_id, 0);
				$type		= $row['network_type'];
			
				$network_name	= ( isset($HTTP_POST_VARS['network_name']) )	? trim($HTTP_POST_VARS['network_name']) : '';
				$network_url	= ( isset($HTTP_POST_VARS['network_url']) )		? trim($HTTP_POST_VARS['network_url']) : '';
				$network_type	= ( isset($HTTP_POST_VARS['network_type']) )	? intval($HTTP_POST_VARS['network_type']) : '';
				$network_view	= ( isset($HTTP_POST_VARS['network_view']) )	? intval($HTTP_POST_VARS['network_view']) : '';
				
				$error = ''; 
				$error_msg = '';
			
				if ( !$network_name )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_name'];
				}
				
				if ( !$network_url )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_url'];
				}
				
				if ( $error )
				{
					message_die(GENERAL_ERROR, $error_msg . $lang['back'], '');
				}
				
				$sql_order = '';
				if ( $network_type != $type )
				{
					$sql = 'SELECT MAX(network_order) AS max_order
								FROM ' . NETWORK . '
								WHERE network_type = ' . $type;
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
	
					$max_order = $row['max_order'];
					$next_order = $max_order + 10;
					
					$sql_order .= ', network_order = ' . $next_order;
				}
					
				$sql = "UPDATE " . NETWORK . " SET
							network_name	= '" . str_replace("\'", "''", $network_name) . "',
							network_type	= $network_type,
							network_url		= '" . str_replace("\'", "''", $network_url) . "',
							network_view	= $network_view
							$sql_order
						WHERE network_id = " . intval($HTTP_POST_VARS[POST_NETWORK_URL]);
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NETWORK, 'acp_network_edit');
				
				$info = ( $network_type != '1' ) ? ( $network_type == '2' ) ? $lang['network_partner'] : $lang['network_sponsor'] : $lang['network_link'];
				
				$message = sprintf($lang['update_network'], $info) . '<br><br>' . sprintf($lang['click_return_network'], '<a href="' . append_sid('admin_network.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
				
			case 'order_link':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NETWORK . " SET network_order = network_order + $move WHERE network_id = " . $network_id;
				$result = $db->sql_query($sql);
		
				renumber_order('network', NETWORK_LINK);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NETWORK, 'acp_network_order', NETWORK_LINK);
				
				$show_index = TRUE;
	
				break;
			
			case 'order_partner':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NETWORK . " SET network_order = network_order + $move WHERE network_id = " . $network_id;
				$result = $db->sql_query($sql);
		
				renumber_order('network', NETWORK_PARTNER);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NETWORK, 'acp_network_order', NETWORK_PARTNER);
				
				$show_index = TRUE;
	
				break;
			
			case 'order_sponsor':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NETWORK . " SET network_order = network_order + $move WHERE network_id = " . $network_id;
				$result = $db->sql_query($sql);
		
				renumber_order('network', NETWORK_SPONSOR);
					
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NETWORK, 'acp_network_order', NETWORK_SPONSOR);
				
				$show_index = TRUE;
	
				break;
			
			case 'network_delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				$network		= get_data('network', $network_id, 0);
				$network_type	= $network['network_type'];
				
				$info = ( $network_type != '1' ) ? ( $network_type == '2' ) ? $lang['network_partner'] : $lang['network_sponsor'] : $lang['network_link'];
				
				if ( $network_id && $confirm )
				{	
					$network = get_data('network', $network_id, 0);
				
					$sql = 'DELETE FROM ' . NETWORK . ' WHERE network_id = ' . $network_id;
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NETWORK, 'acp_network_delete', $network['network_name']);
					
					$message = sprintf($lang['delete_network'], $info) . '<br><br>' . sprintf($lang['click_return_network'], '<a href="' . append_sid('admin_network.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}
				else if ( $network_id && !$confirm )
				{
					$template->set_filenames(array('body' => './../admin/style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="network_delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_NETWORK_URL . '" value="' . $network_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> sprintf($lang['confirm_delete_network'], $info),
		
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
		
						'S_CONFIRM_ACTION'	=> append_sid('admin_network.php'),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_network']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
			
				message_die(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_network.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_NETWORK_HEAD'		=> $lang['network_head'],
		'L_NETWORK_EXPLAIN'		=> $lang['network_explain'],
		
		'L_NETWORK_ADD_LINK'	=> sprintf($lang['network_add'], $lang['network_link']),
		'L_NETWORK_ADD_PARTNER'	=> sprintf($lang['network_add'], $lang['network_partner']),
		'L_NETWORK_ADD_SPONSOR'	=> sprintf($lang['network_add'], $lang['network_sponsor']),
		
		'L_NETWORK_LINK'		=> $lang['network_link'],
		'L_NETWORK_PARTNER'		=> $lang['network_partner'],
		'L_NETWORK_SPONSOR'		=> $lang['network_sponsor'],
		
		'L_EDIT'				=> $lang['edit'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'S_NETWORK_ACTION'		=> append_sid('admin_network.php'),
	));
	
	$sql = 'SELECT MAX(network_order) AS max FROM ' . NETWORK . ' WHERE network_type = ' . NETWORK_LINK;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$max_link = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT MAX(network_order) AS max FROM ' . NETWORK . ' WHERE network_type = ' . NETWORK_PARTNER;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$max_partner = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT MAX(network_order) AS max FROM ' . NETWORK . ' WHERE network_type = ' . NETWORK_SPONSOR;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$max_sponsor = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT * FROM ' . NETWORK . ' WHERE network_type = ' . NETWORK_LINK . ' ORDER BY network_order ASC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data_link = $db->sql_fetchrowset($result);
	
	$sql = 'SELECT * FROM ' . NETWORK . ' WHERE network_type = ' . NETWORK_PARTNER . ' ORDER BY network_order ASC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data_partner = $db->sql_fetchrowset($result);
	
	$sql = 'SELECT * FROM ' . NETWORK . ' WHERE network_type = ' . NETWORK_SPONSOR . ' ORDER BY network_order ASC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data_sponsor = $db->sql_fetchrowset($result);
	
	if ( !$data_link )
	{
		$template->assign_block_vars('display.no_entry_link', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_link)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$network_id	= $data_link[$i]['network_id'];
				
			$template->assign_block_vars('display.link_row', array(
				'CLASS' 		=> $class,
				
				'NETWORK_NAME'	=> $data_link[$i]['network_name'],
				'SHOW'			=> ( $data_link[$i]['network_view'] ) ? $images['icon_acp_yes'] : $images['icon_acp_no'],
				
				'MOVE_UP'		=> ( $data_link[$i]['network_order'] != '10' )				? '<a href="' . append_sid('admin_network.php?mode=order_link&amp;move=-15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $data_link[$i]['network_order'] != $max_link['max'] )	? '<a href="' . append_sid('admin_network.php?mode=order_link&amp;move=15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_EDIT'		=> append_sid('admin_network.php?mode=network_edit&amp;' . POST_NETWORK_URL . '=' . $network_id),
				'U_DELETE'		=> append_sid('admin_network.php?mode=network_delete&amp;' . POST_NETWORK_URL . '=' . $network_id)
			));
		}
	}
	
	if ( !$data_partner )
	{
		$template->assign_block_vars('display.no_entry_partner', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_partner)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$network_id	= $data_partner[$i]['network_id'];
				
			$template->assign_block_vars('display.partner_row', array(
				'CLASS' 		=> $class,
				
				'NETWORK_NAME'	=> $data_partner[$i]['network_name'],
				'SHOW'			=> ( $data_partner[$i]['network_view'] ) ? $images['icon_acp_yes'] : $images['icon_acp_no'],
				
				'MOVE_UP'		=> ( $data_partner[$i]['network_order'] != '10' )					? '<a href="' . append_sid('admin_network.php?mode=order_partner&amp;move=-15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $data_partner[$i]['network_order'] != $max_partner['max'] )	? '<a href="' . append_sid('admin_network.php?mode=order_partner&amp;move=15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_EDIT'		=> append_sid('admin_network.php?mode=network_edit&amp;' . POST_NETWORK_URL . '=' . $network_id),
				'U_DELETE'		=> append_sid('admin_network.php?mode=network_delete&amp;' . POST_NETWORK_URL . '=' . $network_id)
			));
		}
	}
	
	if ( !$data_sponsor )
	{
		$template->assign_block_vars('display.no_entry_sponsor', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_sponsor)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$network_id	= $data_sponsor[$i]['network_id'];
				
			$template->assign_block_vars('display.sponsor_row', array(
				'CLASS' 		=> $class,
				
				'NETWORK_NAME'	=> $data_sponsor[$i]['network_name'],
				'SHOW'			=> ( $data_sponsor[$i]['network_view'] ) ? $images['icon_acp_yes'] : $images['icon_acp_no'],
				
				'MOVE_UP'		=> ( $data_sponsor[$i]['network_order'] != '10' )					? '<a href="' . append_sid('admin_network.php?mode=order_sponsor&amp;move=-15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $data_sponsor[$i]['network_order'] != $max_sponsor['max'] )	? '<a href="' . append_sid('admin_network.php?mode=order_sponsor&amp;move=15&amp;' . POST_NETWORK_URL . '=' . $network_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_EDIT'		=> append_sid('admin_network.php?mode=network_edit&amp;' . POST_NETWORK_URL . '=' . $network_id),
				'U_DELETE'		=> append_sid('admin_network.php?mode=network_delete&amp;' . POST_NETWORK_URL . '=' . $network_id)
			));
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>