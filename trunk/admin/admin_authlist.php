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
		$module['main']['authlist'] = $filename;
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
	
	if ( $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_authlist.php', true));
	}
	
	$start		= ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	
	$mode		= request('mode', true);
	$auth_id	= request(POST_AUTHLIST_URL);
	
	switch ( $mode )
	{
		case 'authlist_add':
		case 'authlist_edit':
			
			$template->set_filenames(array('body' => 'style/acp_authlist.tpl'));
			$template->assign_block_vars('authlist_edit', array());
			
			if ( $mode == 'authlist_edit' )
			{
				$authlist = get_data('authlist', $auth_id, 0);
				$new_mode = 'authlist_update';
			}
			else
			{
				$authlist = array('auth_name' => request('auth_name', true));
				$new_mode = 'authlist_create';
			}
			
			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $auth_id . '" />';

			$template->assign_vars(array(
				'L_AUTHLIST_HEAD'		=> $lang['authlist_head'],
				'L_AUTHLIST_NEW_EDIT'	=> ( $mode == 'authlist_add' ) ? $lang['authlist_add'] : $lang['authlist_edit'],
				'L_REQUIRED'			=> $lang['required'],
				
				'L_AUTHLIST_NAME'		=> $lang['authlist_name'],
				
				'L_RESET'				=> $lang['common_reset'],
				'L_SUBMIT'				=> $lang['common_submit'],
				
				'AUTH_NAME'				=> str_replace('auth_', '', $authlist['auth_name']),
				
				'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
				'S_AUTHLIST_ACTION'		=> append_sid('admin_authlist.php'),
			));
		
			$template->pparse('body');
			
			break;
		
		case 'authlist_create':
		
			$auth_name = request('auth_name', true);
			
			if ( $auth_name == '' )
			{
				message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
			}
			else
			{
				$sql = 'INSERT INTO ' . AUTHLIST . " (auth_name) VALUES ('auth_" . $auth_name . "')";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'ALTER TABLE ' . GROUPS . " ADD `auth_" . $auth_name . "` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('authlist');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'acp_authlist_add', $auth_name);
	
				$message = $lang['create_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}

			break;
		
		case 'authlist_update':
		
			$authinfos = get_data('authlist', $auth_id, 0);
			$auth_name = request('auth_name', true);
			
			if ( $auth_name == '' )
			{
				message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
			}
			else
			{
				$sql = 'UPDATE ' . AUTHLIST . ' SET auth_name = "auth_' . $auth_name . '" WHERE auth_id = ' . $auth_id;
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'ALTER TABLE ' . GROUPS . " CHANGE `" . $authinfos['auth_name'] . "` `auth_" . $auth_name . "` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('authlist');
							
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'acp_authlist_edit', $auth_name);
				
				$message = $lang['update_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			
			break;
		
		case 'authlist_delete':
		
			$confirm = isset($HTTP_POST_VARS['confirm']);
			
			if ( $auth_id && $confirm )
			{	
				$authinfos = get_data('authlist', $auth_id, 0);
				
				$sql = 'DELETE FROM ' . AUTHLIST . ' WHERE auth_id = ' . $auth_id;
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'ALTER TABLE ' . GROUPS . ' DROP ' . $authinfos['auth_name'];
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('authlist');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'acp_authlist_delete', $authinfos['auth_name']);
				
				$message = $lang['delete_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else if ( $auth_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$hidden_fields = '<input type="hidden" name="mode" value="authlist_delete" /><input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $auth_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_authlist'],
	
					'L_NO'				=> $lang['common_no'],
					'L_YES'				=> $lang['common_yes'],
					
					'S_HIDDEN_FIELDS'	=> $hidden_fields,
					'S_CONFIRM_ACTION'	=> append_sid('admin_authlist.php'),
				));
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['msg_must_select_authlist']);
			}
			
			$template->pparse('body');
			
			break;
			
		default:
		
			$template->set_filenames(array('body' => 'style/acp_authlist.tpl'));
			$template->assign_block_vars('display', array());
					
			$template->assign_vars(array(
				'L_AUTHLIST_HEAD'		=> $lang['authlist_head'],
				'L_AUTHLIST_EXPLAIN'	=> $lang['authlist_explain'],
				'L_AUTHLIST_NAME'		=> $lang['authlist_name'],
				'L_AUTHLIST_ADD'		=> $lang['authlist_add'],
				
				'L_EDIT'				=> $lang['edit'],
				'L_DELETE'				=> $lang['delete'],
				'L_SETTINGS'			=> $lang['settings'],
				
				'S_AUTHLIST_ACTION'		=> append_sid('admin_authlist.php'),
			));
			
			$sql = 'SELECT * FROM ' . AUTHLIST . ' ORDER BY auth_id';
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$authlist_data = $db->sql_fetchrowset($result);
			
			for ( $i = $start; $i < count($authlist_data); $i++ )
			{
				$class = ($i % 2) ? 'row_class1' : 'row_class2';
				
				$template->assign_block_vars('display.auth_row', array(
					'CLASS' 		=> $class,
					'AUTHNAME'		=> $authlist_data[$i]['auth_name'],
					
					'U_EDIT'		=> append_sid('admin_authlist.php?mode=authlist_edit&amp;' . POST_AUTHLIST_URL . '=' . $authlist_data[$i]['auth_id']),
					'U_DELETE'		=> append_sid('admin_authlist.php?mode=authlist_delete&amp;' . POST_AUTHLIST_URL . '=' . $authlist_data[$i]['auth_id']),
				));
			}
			
			$template->pparse('body');
			
			break;
	}
	
	include('./page_footer_admin.php');
}

?>