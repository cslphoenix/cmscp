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

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ( $userdata['user_level'] == ADMIN )
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
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_match.php", true));
	}
	
	if ( isset($HTTP_POST_VARS[POST_AUTHLIST_URL]) || isset($HTTP_GET_VARS[POST_AUTHLIST_URL]) )
	{
		$auth_id = ( isset($HTTP_POST_VARS[POST_AUTHLIST_URL]) ) ? intval($HTTP_POST_VARS[POST_AUTHLIST_URL]) : intval($HTTP_GET_VARS[POST_AUTHLIST_URL]);
	}
	else
	{
		$auth_id = 0;
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		$mode = '';
	}
	
	$show_index = '';
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
				
				if ( $mode == 'edit' )
				{
					$authlist	= get_data('authlist', $auth_id, 0);
					$new_mode	= 'editauth';
				}
				else if ( $mode == 'add' )
				{
					$authlist = array (
						'auth_name'	=> trim($HTTP_POST_VARS['auth_name']),
					);

					$new_mode = 'addauth';
				}
				
				$template->set_filenames(array('body' => './../admin/style/acp_authlist.tpl'));
				$template->assign_block_vars('authlist_edit', array());
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $auth_id . '" />';

				$template->assign_vars(array(
					'L_AUTHLIST_HEAD'		=> $lang['authlist_head'],
					'L_AUTHLIST_NEW_EDIT'	=> ($mode == 'add') ? $lang['authlist_add'] : $lang['authlist_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_AUTHLIST_NAME'		=> $lang['authlist_name'],
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					
					'AUTH_NAME'				=> str_replace('auth_', '', $authlist['auth_name']),
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_AUTHLIST_ACTION'		=> append_sid("admin_authlist.php")
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addauth':
				
				$auth_name = (isset($HTTP_POST_VARS['auth_name'])) ? trim($HTTP_POST_VARS['auth_name']) : '';
				
				if ( $auth_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back'], '');
				}
				
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
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'acp_authlist_add');
	
				$message = $lang['create_authlist'] . '<br><br>' . sprintf($lang['click_return_authlist'], '<a href="' . append_sid("admin_authlist.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editauth':
			
				$authlist = get_data('authlist', $auth_id, 0);
				
				$auth_name = (isset($HTTP_POST_VARS['auth_name'])) ? trim($HTTP_POST_VARS['auth_name']) : '';
				
				if ( $auth_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back'], '');
				}
				
				$sql = 'UPDATE ' . AUTHLIST . '
							SET auth_name = "auth_' . $auth_name . '"
							WHERE auth_id = ' . $auth_id;
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'ALTER TABLE ' . GROUPS . " CHANGE `" . $authlist['auth_name'] . "` `auth_" . $auth_name . "` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$oCache -> sCachePath = './../cache/';
				$oCache -> deleteCache('authlist');
							
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'acp_authlist_edit');
				
				$message = $lang['update_authlist'] . '<br><br>' . sprintf($lang['click_return_authlist'], '<a href="' . append_sid("admin_authlist.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $auth_id && $confirm )
				{	
					$authlist = get_data('authlist', $auth_id, 0);
					
					$sql = 'DELETE FROM ' . AUTHLIST . ' WHERE auth_id = ' . $auth_id;
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'ALTER TABLE ' . GROUPS . ' DROP ' . $authlist['auth_name'];
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$oCache -> sCachePath = './../cache/';
					$oCache -> deleteCache('authlist');
					
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_AUTHLIST, 'acp_authlist_delete', $authlist['auth_name']);
					
					$message = $lang['delete_authlist'] . '<br><br>' . sprintf($lang['click_return_authlist'], '<a href="' . append_sid("admin_authlist.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $auth_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $auth_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_authlist'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_authlist.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_authlist']);
				}
				
				$template->pparse('body');
				
				break;
				
			default:
				message_die(GENERAL_ERROR, $lang['no_select_module'], '');
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_authlist.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_AUTHLIST_HEAD'		=> $lang['authlist_head'],
		'L_AUTHLIST_EXPLAIN'	=> $lang['authlist_explain'],
		'L_AUTHLIST_NAME'		=> $lang['authlist_name'],
		'L_AUTHLIST_ADD'		=> $lang['authlist_add'],
		
		'L_EDIT'				=> $lang['edit'],
		'L_DELETE'				=> $lang['delete'],
		'L_SETTINGS'			=> $lang['settings'],
		
		'S_AUTHLIST_ACTION'		=> append_sid("admin_authlist.php")
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
			
			'U_EDIT'		=> append_sid("admin_authlist.php?mode=edit&amp;" . POST_AUTHLIST_URL . "=" . $authlist_data[$i]['auth_id']),
			'U_DELETE'		=> append_sid("admin_authlist.php?mode=delete&amp;" . POST_AUTHLIST_URL . "=" . $authlist_data[$i]['auth_id']),
		));
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>