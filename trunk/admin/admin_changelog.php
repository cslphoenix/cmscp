<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['hm_dev']['sm_changelog'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_games';
	
	include('./pagestart.php');
	
	add_lang('games');

	$error	= '';
	$index	= '';
	$log	= SECTION_GAMES;
	$url	= POST_GAMES;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	$dir_path	= $root_path . $settings['path_games'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['game']);
	$fields	= '';
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, SECTION_CASH, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid('admin_changelog.php', true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_changelog.tpl',
	));

	switch ( $mode )
	{
		case 'create':
		case 'update':
	
			$template->assign_block_vars('input', array());
			
			if ( $mode == 'create' )
			{
				$authlist = array(
					'authlist_name' => request('authlist_name', 2),
				);
				$new_mode = '_create_save';
			}
			else
			{
				$authlist = get_data('authlist', $authlist_id, INT);
				$new_mode = '_update_save';
			}
			
			$ssprintf = ( $mode == 'create' ) ? 'sprintf_add' : 'sprintf_edit';
			$fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_AUTHLIST . '" value="' . $authlist_id . '" />';

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['authlist_field']),
				'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['authlist_field']),
				
				'L_RESET'		=> $lang['common_reset'],
				'L_SUBMIT'		=> $lang['common_submit'],
				
				'NAME'			=> str_replace('auth_', '', $authlist['authlist_name']),
				
				'S_FIELDS'		=> $fields,
				'S_ACTION'		=> check_sid('admin_authlist.php'),
			));
			
			break;
		
		case 'create_save':
		
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
			
			$message = $lang['create_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . check_sid('admin_authlist.php'));
			log_add(LOG_ADMIN, SECTION_AUTHLIST, 'create_authlist');
			message(GENERAL_MESSAGE, $message);

			break;
		
		case 'update_save':
		
			$authlist		= get_data('authlist', $authlist_id, INT);
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
				. sprintf($lang['click_return_authlist'], '<a href="' . check_sid('admin_authlist.php') . '">', '</a>')
				. sprintf($lang['return_update'], '<a href="' . check_sid("admin_authlist.php?mode=_update&amp;' . POST_AUTHLIST . '=' . $authlist_id"));
			log_add(LOG_ADMIN, SECTION_AUTHLIST, 'update_authlist');
			message(GENERAL_MESSAGE, $message);
			
			break;
		
		case 'delete':
		
			$authlist = get_data('authlist', $authlist_id, INT);
		
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
				
				$message = $lang['delete_authlist'] . sprintf($lang['click_return_authlist'], '<a href="' . check_sid('admin_authlist.php'));
				log_add(LOG_ADMIN, SECTION_AUTHLIST, 'delete_authlist');
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $authlist_id && !$confirm )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['authlist_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}
			
			$template->pparse('confirm');
			
			break;
			
		default:
		
			$template->assign_block_vars('display', array());
			
			$fields .= '<input type="hidden" name="mode" value="_create" />';
					
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['changelog']),
				'L_CREATE'		=> sprintf($lang['sprintf_new_creates'], $lang['changelog']),
				'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['changelog']),
				'L_EXPLAIN'		=> $lang['changelog_explain'],
				
				'S_CREATE'		=> check_sid("$file?mode=_create"),
				'S_ACTION'		=> check_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			$authlist_data = get_data_array(AUTHLIST, '', 'authlist_id', 'ASC');
			
			for ( $i = 0; $i < count($authlist_data); $i++ )
			{
				$authlist_id = $authlist_data[$i]['authlist_id'];
				
				$template->assign_block_vars('display.row_authlist', array(
					'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
					
					'AUTHLIST_NAME'	=> $authlist_data[$i]['authlist_name'],
					
					'U_UPDATE'		=> check_sid("admin_authlist.php?mode=_update&amp;' . POST_AUTHLIST . '=' . $authlist_id"),
					'U_DELETE'		=> check_sid("admin_authlist.php?mode=_delete&amp;' . POST_AUTHLIST . '=' . $authlist_id"),
				));
			}
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>