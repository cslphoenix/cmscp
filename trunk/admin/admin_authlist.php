<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['_headmenu_01_main']['_submenu_authlist'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_authlist';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('authlist');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_AUTHLIST;
	$url	= POST_AUTHLIST_URL;
	$file	= basename(__FILE__);
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['authlist']);
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->set_filenames(array('body' => 'style/acp_authlist.tpl'));
			$template->assign_block_vars('_input', array());
			
			if ( $mode == '_create' && !request('submit', 1) )
			{
				$data = array('authlist_name' => request('authlist_name', 2));
			}
			else if ( $mode == '_update' && !request('submit', 1) )
			{
				$data = data(AUTHLIST, $data_id, '', 1, 1);
			}
			else
			{
				$data = array('authlist_name' => request('authlist_name', 2));
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['field'], $data['authlist_name']),
				'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['field']),
				
				'NAME'		=> str_replace('auth_', '', $data['authlist_name']),
				
				'S_ACTION'	=> append_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			if ( request('submit', 1) )
			{
			#	$authlist_name = request('authlist_name', 2);
				
			#	$error .= ( !$authlist_name ) ? ( $error ? '<br />' : '' ) .  sprintf($lang['sprintf_name'], $lang['authlist_field']) : '';
				
				$data = array('authlist_name' => request('authlist_name', 2));
				
				$error .= ( !$data['authlist_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';	

				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$sql = "INSERT INTO " . AUTHLIST . " (authlist_name) VALUES ('auth_" . $authlist_name . "')";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$sql = "ALTER TABLE " . GROUPS . " ADD auth_" . $authlist_name . " TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['create'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					}
					else
					{
						$sql = "UPDATE " . AUTHLIST . " SET authlist_name = 'auth_" . $authlist_name . "' WHERE authlist_id = $data_id";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$sql = "ALTER TABLE " . GROUPS . " CHANGE '" . $data['authlist_name'] . "' 'auth_" . $authlist_name . "' TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						
						$message = $lang['update']
							. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
							. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
					}
					
					#$oCache -> sCachePath = './../cache/';
					#$oCache -> deleteCache('authlist');
					
					log_add(LOG_ADMIN, $log, $mode, $data['authlist_name']);
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
			
			break;
		
		case '_delete':
		
			$data = data(AUTHLIST, $data_id, '', 1, 1);
		
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
				
				#$oCache -> sCachePath = './../cache/';
				#$oCache -> deleteCache('authlist');
				
				$message = $lang['delete'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
				
				log_add(LOG_ADMIN, $log, $mode, $data['authlist_name']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));

				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['authlist_name']),
					
					'S_ACTION'	=> append_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['authlist_field'])); }
			
			break;
			
		default:
		
			$template->set_filenames(array('body' => 'style/acp_authlist.tpl'));
			$template->assign_block_vars('_display', array());
			
			$authlist = data(AUTHLIST, '', 'authlist_name ASC', 0, 0);
			
			$fields .= '<input type="hidden" name="mode" value="_create" />';
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['field']),
				'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['field']),
				'L_EXPLAIN'	=> $lang['explain'],
				
				'S_CREATE'	=> append_sid("$file?mode=_create"),
				'S_ACTION'	=> append_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			for ( $i = 0; $i < count($authlist); $i++ )
			{
				$authlist_id = $authlist[$i]['authlist_id'];

				$template->assign_block_vars('_display._authlist_row', array(
					'NAME'		=> $authlist[$i]['authlist_name'],
					
					'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$authlist_id"),
					'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$authlist_id"),
				));
			}
			
			break;
	}
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>