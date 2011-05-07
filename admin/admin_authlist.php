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
	
	load_lang('authlist');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_AUTHLIST;
	$url	= POST_AUTHLIST_URL;
	$file	= basename(__FILE__);
	
	$oCache -> sCachePath = $root_path . 'cache/';
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['authlist']);
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_authlist.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->assign_block_vars('_input', array());
			
			if ( $mode == '_create' && !request('submit', 1) )
			{
				$data = array('authlist_name' => str_replace('auth_', '', request('authlist_name', 2)));
			}
			else if ( $mode == '_update' && !request('submit', 1) )
			{
				$data = data(AUTHLIST, $data_id, false, 1, true);
			}
			else
			{
				$data = array('authlist_name' => str_replace('auth_', '', request('authlist_name', 2)));
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			$fields .= "<input type=\"hidden\" name=\"old_name\" value=\"" . $data['authlist_name'] . "\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['field'], $data['authlist_name']),
				'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['field']),
				
				'NAME'		=> str_replace('auth_', '', $data['authlist_name']),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			if ( request('submit', 1) )
			{
				$data['authlist_name'] = strtolower('auth_' . $data['authlist_name']);
				
				$_ary = array(
							'authlist_name' => $data['authlist_name'],
							'authlist_id' => $data_id,
						);
				
				$error .= check(AUTHLIST, $_ary, $error);
					
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$sql = sql(AUTHLIST, $mode, $data);
						
						$_ary = array(
									'part'	=> "ADD `" . $data['authlist_name'] . "`",
									'type'	=> "TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'",
								);
						$add = sql(GROUPS, 'alter', $_ary);
						$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(AUTHLIST, $mode, $data, 'authlist_id', $data_id);
						
						$_ary = array(
									'part'	=> "CHANGE `" . request('old_name', 1) . "` `" . $data['authlist_name'] . "`",
									'type'	=> "TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'",
								);
						$add = sql(GROUPS, 'alter', $_ary);
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
					}
					
					$oCache -> deleteCache('authlist');
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					log_add(LOG_ADMIN, $log, $mode, $error);
					
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
				}
			}
			
			break;
		
		case '_delete':
		
			$data = data(AUTHLIST, $data_id, false, 1, true);
		
			if ( $data_id && $confirm )
			{
				$sql = sql(AUTHLIST, $mode, $data, 'authlist_id', $data_id);
				
				$_ary = array(
							'part'	=> "DROP ",
							'type'	=> $data['authlist_name'],
						);
				$add = sql(GROUPS, 'alter', $_ary);
				
				$oCache->deleteCache('authlist');
				
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
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
				message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['authlist_field']));
			}
			
			$template->pparse('confirm');
			
			break;
			
		default:
		
			$template->assign_block_vars('_display', array());
			
			$fields .= '<input type="hidden" name="mode" value="_create" />';
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['authlist']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['field']),
				'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['field']),
				'L_EXPLAIN'	=> $lang['explain'],
				
				'S_CREATE'	=> check_sid("$file?mode=_create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			$authlist = data(AUTHLIST, false, 'authlist_id ASC', 0, false);
			
			for ( $i = 0; $i < count($authlist); $i++ )
			{
				$authlist_id = $authlist[$i]['authlist_id'];

				$template->assign_block_vars('_display._authlist_row', array(
					'NAME'		=> $authlist[$i]['authlist_name'],
					
					'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$authlist_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
					'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$authlist_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
				));
			}
			
			break;
	}
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>