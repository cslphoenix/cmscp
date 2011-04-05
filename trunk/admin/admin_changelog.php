<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['_headmenu_07_development']['_submenu_changelog'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_games';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('games');
	
	$error	= '';
	$index	= '';
	$log	= LOG_SEK_GAMES;
	$url	= POST_GAMES_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_games'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['game']);
	$fields	= '';
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_CASH, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid('admin_changelog.php', true)) : false;

	switch ( $mode )
	{
		case '_create':
		case '_update':
	
			$template->set_filenames(array('body' => 'style/acp_changelog.tpl'));
			$template->assign_block_vars('_input', array());
			
			if ( $mode == '_create' )
			{
				$authlist = array(
					'authlist_name' => request('authlist_name', 2),
				);
				$new_mode = '_create_save';
			}
			else
			{
				$authlist = get_data('authlist', $authlist_id, 0);
				$new_mode = '_update_save';
			}
			
			$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
			$fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $authlist_id . '" />';

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['authlist_field']),
				'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['authlist_field']),
				
				'L_RESET'		=> $lang['common_reset'],
				'L_SUBMIT'		=> $lang['common_submit'],
				
				'NAME'			=> str_replace('auth_', '', $authlist['authlist_name']),
				
				'S_FIELDS'		=> $fields,
				'S_ACTION'		=> append_sid('admin_authlist.php'),
			));
			
			break;
		
		case '_create_save':
		
			$authlist_name = request('authlist_name', 2);
			
			if ( !$authlist_name )
			{
				message(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
			}

			$sql = "INSERT INTO " . AUTHLIST . " (authlist_name) VALUES ('auth_" . $authlist_name . "')";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "ALTER TABLE " . GROUPS . " ADD auth_" . $authlist_name . " TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			#$oCache -> sCachePath = './../cache/';
			#$oCache -> deleteCache('authlist');
			
			$message = $lang['create_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
			log_add(LOG_ADMIN, LOG_SEK_AUTHLIST, 'create_authlist');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case '_update_save':
		
			$authlist		= get_data('authlist', $authlist_id, 0);
			$authlist_name	= request('authlist_name', 2);
			
			if ( !$authlist_name )
			{
				message(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
			}
				
			$sql = "UPDATE " . AUTHLIST . " SET authlist_name = 'auth_" . $authlist_name . "' WHERE authlist_id = $authlist_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "ALTER TABLE " . GROUPS . " CHANGE '" . $authlist['authlist_name'] . "' 'auth_" . $authlist_name . "' TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			#$oCache -> sCachePath = './../cache/';
			#$oCache -> deleteCache('authlist');
						
			$message = $lang['update_authlist']
				. sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>')
				. sprintf($lang['return_update'], '<a href="' . append_sid("admin_authlist.php?mode=_update&amp;' . POST_AUTHLIST_URL . '=' . $authlist_id") . '">', '</a>');
			log_add(LOG_ADMIN, LOG_SEK_AUTHLIST, 'update_authlist');
			message(GENERAL_MESSAGE, $message);
			
			break;
		
		case '_delete':
		
			$authlist = get_data('authlist', $authlist_id, 0);
		
			if ( $authlist_id && $confirm )
			{	
				$sql = "DELETE FROM " . AUTHLIST . " WHERE authlist_id = $authlist_id";
				if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "ALTER TABLE " . GROUPS . " DROP " . $authlist['authlist_name'];
				if ( !($result = $db->sql_query($sql, END_TRANSACTION)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				#$oCache -> sCachePath = './../cache/';
				#$oCache -> deleteCache('authlist');
				
				$message = $lang['delete_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . append_sid('admin_authlist.php') . '">', '</a>');
				log_add(LOG_ADMIN, LOG_SEK_AUTHLIST, 'delete_authlist');
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $authlist_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_AUTHLIST_URL . '" value="' . $authlist_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['common_confirm'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_authlist'],
					'L_NO'				=> $lang['common_no'],
					'L_YES'				=> $lang['common_yes'],
					'S_FIELDS'	=> $fields,
					'S_ACTION'	=> append_sid('admin_authlist.php'),
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_authlist']);
			}
			
			break;
			
		default:
		
			$template->set_filenames(array('body' => 'style/acp_changelog.tpl'));
			$template->assign_block_vars('_display', array());
			
			$fields = '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['changelog']),
				'L_CREATE'		=> sprintf($lang['sprintf_new_creates'], $lang['changelog']),
				'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['changelog']),
				'L_EXPLAIN'		=> $lang['changelog_explain'],
				
				'S_FIELDS'		=> $fields,
				'S_CREATE'		=> append_sid('admin_changelog.php?mode=_create'),
				'S_ACTION'		=> append_sid('admin_changelog.php'),
			));
			
			$authlist_data = get_data_array(AUTHLIST, '', 'authlist_id', 'ASC');
			
			for ( $i = 0; $i < count($authlist_data); $i++ )
			{
				$authlist_id = $authlist_data[$i]['authlist_id'];
				
				$template->assign_block_vars('display.row_authlist', array(
					'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
					
					'AUTHLIST_NAME'	=> $authlist_data[$i]['authlist_name'],
					
					'U_UPDATE'		=> append_sid("admin_authlist.php?mode=_update&amp;' . POST_AUTHLIST_URL . '=' . $authlist_id"),
					'U_DELETE'		=> append_sid("admin_authlist.php?mode=_delete&amp;' . POST_AUTHLIST_URL . '=' . $authlist_id"),
				));
			}
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>