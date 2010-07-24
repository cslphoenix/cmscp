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
	$filename = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['_headmenu_main']['_submenu_authlist'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_authlist';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');

	load_lang('authlist');
	
	$data_id	= request(POST_AUTHLIST_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		message(GENERAL_ERROR, sprintf($lang['sprintf_auth_fail'], $lang[$current]));
	}
	
	if ( $no_header )
	{
		redirect('admin/' . append_sid('admin_authlist.php', true));
	}

	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_authlist.tpl'));
			$template->assign_block_vars('_input', array());
			
			if ( $mode == '_create' && !(request('submit', 2)) )
			{
				$data = array(
					'authlist_name' => request('authlist_name', 2),
				);
			}
			else if ( $mode == '_update' && !(request('submit', 2)) )
			{
				$data = get_data(AUTHLIST, $data_id, 1);
			}
			else
			{
				$data = array(
					'authlist_name' => request('authlist_name', 2)
				);
			}
			
			$s_fields	= '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $data_id . '" />';
			$s_sprintf	= ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';

			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_INPUT'	=> sprintf($lang[$s_sprintf], $lang['authlist_field'], $data['authlist_name']),
				
				'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['authlist_field']),
				
				'NAME'		=> str_replace('auth_', '', $data['authlist_name']),
				
				'S_FIELDS'	=> $s_fields,
				'S_ACTION'	=> append_sid('admin_authlist.php'),
			));
			
			if ( request('submit', 2) )
			{
				$authlist_name = request('authlist_name', 2);
				
				$error = ( !$authlist_name ) ? $lang['msg_select_name'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$sql = "INSERT INTO " . AUTHLIST . " (authlist_name) VALUES ('auth_" . $authlist_name . "')";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$sql = "ALTER TABLE " . GROUPS . " ADD auth_" . $authlist_name . " TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
						log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'create_authlist');
					}
					else
					{
						$sql = "UPDATE " . AUTHLIST . " SET authlist_name = 'auth_" . $authlist_name . "' WHERE authlist_id = $data_id";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$sql = "ALTER TABLE " . GROUPS . " CHANGE '" . $data['authlist_name'] . "' 'auth_" . $authlist_name . "' TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create_authlist']
							. sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>')
							. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_games.php?mode=_update&amp;' . POST_AUTHLIST_URL . '=' . $data_id) . '">', '</a>');
						log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'create_authlist');
					}
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('authlist');
			
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
				}
			}
			
			break;
		
		case '_delete':
		
			$data = get_data(AUTHLIST, $data_id, 1);
		
			if ( $data_id && $confirm )
			{	
				$sql = "DELETE FROM " . AUTHLIST . " WHERE authlist_id = $data_id";
				if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "ALTER TABLE " . GROUPS . " DROP " . $data['authlist_name'];
				if ( !$db->sql_query($sql, END_TRANSACTION) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('authlist');
				
				$message = $lang['delete_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'delete_authlist');
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $data_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'	=> $lang['common_confirm'],
					'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_authlist'], $data['authlist_name']),
					
					'S_FIELDS'	=> $s_fields,
					'S_ACTION'	=> append_sid('admin_authlist.php'),
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_authlist']);
			}
			
			break;
			
		default:
		
			$template->set_filenames(array('body' => 'style/acp_authlist.tpl'));
			$template->assign_block_vars('_display', array());
			
			$s_fields = '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_CREATE'	=> sprintf($lang['sprintf_creates'], $lang['authlist_field']),
				'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['authlist_field']),
				
				'L_EXPLAIN'	=> $lang['authlist_explain'],
				
				'S_FIELDS'	=> $s_fields,
				'S_CREATE'	=> append_sid('admin_authlist.php?mode=_create'),
				'S_ACTION'	=> append_sid('admin_authlist.php'),
			));
			
			$data = get_data_array(AUTHLIST, '', 'authlist_id', 'ASC');
			
			for ( $i = 0; $i < count($data); $i++ )
			{
				$data_id = $data[$i]['authlist_id'];
				
				$template->assign_block_vars('_display._authlist_row', array(
					'NAME'	=> $data[$i]['authlist_name'],
					
					'U_UPDATE'	=> append_sid('admin_authlist.php?mode=_update&amp;' . POST_AUTHLIST_URL . '=' . $data_id),
					'U_DELETE'	=> append_sid('admin_authlist.php?mode=_delete&amp;' . POST_AUTHLIST_URL . '=' . $data_id),
				));
			}
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>