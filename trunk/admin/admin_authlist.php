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
 *	- @autor:	Sebastian Frickel � 2009
 *	- @code:	Sebastian Frickel � 2009
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
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/authlist.php');
	
	$authlist_id	= request(POST_AUTHLIST_URL);
	$confirm		= request('confirm');
	$mode			= request('mode');
	
	if ( $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_authlist.php', true));
	}

	switch ( $mode )
	{
		case '_create':
		case '_update':
	
			$template->set_filenames(array('body' => 'style/acp_authlist.tpl'));
			$template->assign_block_vars('authlist_edit', array());
			
			if ( $mode == '_create' )
			{
				$authlist = array('authlist_name' => request('authlist_name', 'text'));
				$new_mode = '_create_save';
			}
			else
			{
				$authlist = get_data(AUTHLIST, $authlist_id, 1);
				$new_mode = '_update_save';
			}
			
			$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
			$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $authlist_id . '" />';

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_NEW_EDIT'	=> sprintf($lang[$ssprintf], $lang['authlist_field']),
				'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['authlist_field']),
				
				'NAME'			=> str_replace('auth_', '', $authlist['authlist_name']),
				
				'S_FIELDS'		=> $s_fields,
				'S_ACTION'		=> append_sid('admin_authlist.php'),
			));
			
			break;
		
		case '_create_save':
		
			$authlist_name = request('authlist_name', 'text');
			
			if ( !$authlist_name )
			{
				message(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
			}

			$sql = "INSERT INTO " . AUTHLIST . " (authlist_name) VALUES ('auth_" . $authlist_name . "')";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "ALTER TABLE " . GROUPS . " ADD auth_" . $authlist_name . " TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$oCache -> sCachePath = './../cache/';
			$oCache -> deleteCache('authlist');
			
			$message = $lang['create_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'create_authlist');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_update_save':
		
			$authlist		= get_data(AUTHLIST, $authlist_id, 1);
			$authlist_name	= request('authlist_name', 'text');
			
			if ( !$authlist_name )
			{
				message(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
			}
				
			$sql = "UPDATE " . AUTHLIST . " SET authlist_name = 'auth_" . $authlist_name . "' WHERE authlist_id = $authlist_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "ALTER TABLE " . GROUPS . " CHANGE '" . $authlist['authlist_name'] . "' 'auth_" . $authlist_name . "' TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$oCache -> sCachePath = './../cache/';
			$oCache -> deleteCache('authlist');
						
			$message = $lang['update_authlist']
				. sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>')
				. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_authlist.php?mode=_update&amp;' . POST_AUTHLIST_URL . '=' . $authlist_id) . '">', '</a>');
			log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'update_authlist');
			message(GENERAL_MESSAGE, $message);
			
			break;
		
		case '_delete':
		
			$authlist = get_data(AUTHLIST, $authlist_id, 1);
		
			if ( $authlist_id && $confirm )
			{	
				$sql = "DELETE FROM " . AUTHLIST . " WHERE authlist_id = $authlist_id";
				if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "ALTER TABLE " . GROUPS . " DROP " . $authlist['authlist_name'];
				if ( !($result = $db->sql_query($sql, END_TRANSACTION)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('authlist');
				
				$message = $lang['delete_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'delete_authlist');
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $authlist_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $authlist_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'	=> $lang['common_confirm'],
					'MESSAGE_TEXT'	=> $lang['confirm_delete_authlist'],
					
					'S_FIELDS'		=> $s_fields,
					'S_ACTION'		=> append_sid('admin_authlist.php'),
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_authlist']);
			}
			
			break;
			
		default:
		
			$template->set_filenames(array('body' => 'style/acp_authlist.tpl'));
			$template->assign_block_vars('display', array());
			
			$s_fields = '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_CREATE'		=> sprintf($lang['sprintf_creates'], $lang['authlist_field']),
				'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['authlist_field']),
				'L_EXPLAIN'		=> $lang['authlist_explain'],
				
				'S_FIELDS'		=> $s_fields,
				'S_CREATE'		=> append_sid('admin_authlist.php?mode=_create'),
				'S_ACTION'		=> append_sid('admin_authlist.php'),
			));
			
			$authlist_data = get_data_array(AUTHLIST, '', 'authlist_id', 'ASC');
			
			for ( $i = 0; $i < count($authlist_data); $i++ )
			{
				$authlist_id = $authlist_data[$i]['authlist_id'];
				
				$template->assign_block_vars('display.row_authlist', array(
					'CLASS' 	=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
					
					'NAME'		=> $authlist_data[$i]['authlist_name'],
					
					'U_UPDATE'	=> append_sid('admin_authlist.php?mode=_update&amp;' . POST_AUTHLIST_URL . '=' . $authlist_id),
					'U_DELETE'	=> append_sid('admin_authlist.php?mode=_delete&amp;' . POST_AUTHLIST_URL . '=' . $authlist_id),
				));
			}
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>