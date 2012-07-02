<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['hm_main']['sm_authlist'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_authlist';
	
	include('./pagestart.php');
	
	add_lang('authlist');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_AUTHLIST;
	$url	= POST_AUTHLIST;
	$file	= basename(__FILE__);
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_authlist.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'delete')) ) ? $mode : '';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			if ( $mode == 'create' && !request('submit', TXT) )
			{
				$data = array('authlist_name' => str_replace('auth_', '', request('authlist_name', 2)));
			}
			else if ( $mode == 'update' && !request('submit', TXT) )
			{
				$data = data(AUTHLIST, $data_id, false, 1, true);
			}
			else
			{
				$data = array('authlist_name' => strtolower('auth_' . str_replace('auth_', '', request('authlist_name', 2))));
				
				$error .= check(AUTHLIST, array('authlist_name' => $data['authlist_name'], 'authlist_id' => $data_id), $error);
					
				if ( !$error )
				{
					$sql = "SELECT group_id, auth_data FROM " . GROUPS . " ORDER BY group_order";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$grp = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					$sql = "SELECT user_id, user_gauth FROM " . USERS;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$usr = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					if ( $mode == 'create' )
					{
						$sql = sql(AUTHLIST, $mode, $data);
					#	$add = sql(GROUPS, 'alter', array('part' => "ADD `" . $data['authlist_name'] . "`", 'type' => "TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'"));
						$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						
						foreach ( $grp as $k => $v )
						{
							$auth[$v['group_id']] = unserialize($v['auth_data']);
							$mauth = array_merge($auth[$v['group_id']], array($data['authlist_name'] => 0));
							$sauth = serialize($mauth);
						
							$sql = "UPDATE " . GROUPS . " SET auth_data = '$sauth' WHERE group_id = " . $v['group_id'];
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
						
						foreach ( $usr as $k => $v )
						{
							$auth[$v['user_id']] = unserialize($v['user_gauth']);
							$mauth = array_merge($auth[$v['user_id']], array($data['authlist_name'] => 0));
							$sauth = serialize($mauth);
						
							$sql = "UPDATE " . USERS . " SET user_gauth = '$sauth' WHERE user_id = " . $v['user_id'];
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}
					else
					{
						$sql = sql(AUTHLIST, $mode, $data, 'authlist_id', $data_id);
					#	$add = sql(GROUPS, 'alter', array('part' => "CHANGE `" . request('old_name', 1) . "` `" . $data['authlist_name'] . "`", 'type' => "TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'"));
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						
						foreach ( $grp as $k => $v )
						{
							$auth[$v['group_id']] = unserialize($v['auth_data']);
							
							foreach ( $auth[$v['group_id']] as $key => $value )
							{
								if ( $key == request('old_name', 2) )
								{
									$nauth[$data['authlist_name']] = $value;
								}
								else
								{
									$nauth[$key] = $value;
								}
							}
							
							$sauth = serialize($nauth);
						
							$sql = "UPDATE " . GROUPS . " SET auth_data = '$sauth' WHERE group_id = " . $v['group_id'];
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
						
						foreach ( $usr as $k => $v )
						{
							$auth[$v['user_id']] = unserialize($v['user_gauth']);
							
							foreach ( $auth[$v['user_id']] as $key => $value )
							{
								if ( $key == request('old_name', 2) )
								{
									$nauth[$data['authlist_name']] = $value;
								}
								else
								{
									$nauth[$key] = $value;
								}
							}
							
							$sauth = serialize($nauth);
						
							$sql = "UPDATE " . USERS . " SET user_gauth = '$sauth' WHERE user_id = " . $v['user_id'];
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
					}
					
					$oCache->deleteCache('data_authlist');
					
					log_add(LOG_ADMIN, $log, $mode);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
					
					log_add(LOG_ADMIN, $log, 'error', $error);
				}
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			$fields .= "<input type=\"hidden\" name=\"old_name\" value=\"" . $data['authlist_name'] . "\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['field'], $data['authlist_name']),
				'L_DATA'	=> $lang['data'],
				
				'L_NAME'	=> $lang['authlist_name'],
				
				'NAME'		=> str_replace('auth_', '', $data['authlist_name']),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'delete':
		
			$data = data(AUTHLIST, $data_id, false, 1, true);
		
			if ( $data_id && $confirm )
			{
				$sql = sql(AUTHLIST, $mode, $data, 'authlist_id', $data_id);
			#	$add = sql(GROUPS, 'alter', array('part' => "DROP ", 'type'	=> $data['authlist_name']));
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				$sql = "SELECT group_id, auth_data FROM " . GROUPS . " ORDER BY group_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$grp = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				$sql = "SELECT user_id, user_gauth FROM " . USERS;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$usr = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				foreach ( $grp as $k => $v )
				{
					$auth[$v['group_id']] = unserialize($v['auth_data']);
					
					foreach ( $auth[$v['group_id']] as $key => $value )
					{
						if ( $key != $data['authlist_name'] )
						{
							$nauth[$key] = $value;
						}
					}
					
					$sauth = serialize($nauth);
				
					$sql = "UPDATE " . GROUPS . " SET auth_data = '$sauth' WHERE group_id = " . $v['group_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				foreach ( $usr as $k => $v )
				{
					$auth[$v['user_id']] = unserialize($v['user_gauth']);
					
					foreach ( $auth[$v['user_id']] as $key => $value )
					{
						if ( $key != $data['authlist_name'] )
						{
							$nauth[$key] = $value;
						}
					}
					
					$sauth = serialize($nauth);
				
					$sql = "UPDATE " . USERS . " SET user_gauth = '$sauth' WHERE user_id = " . $v['user_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
			
				
				$oCache->deleteCache('data_authlist');
				
				log_add(LOG_ADMIN, $log, $mode);
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
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}
			
			$template->pparse('confirm');
			
			break;
			
		default:
		
			$template->assign_block_vars('display', array());
			
			$fields	= '<input type="hidden" name="mode" value="_create" />';
			
			$tmp = data(AUTHLIST, false, 'authlist_id ASC', 0, false);
			$cnt = count($tmp);
			
			for ( $i = 0; $i < $cnt; $i++ )
			{
				$id		= $tmp[$i]['authlist_id'];
				$name	= $tmp[$i]['authlist_name'];

				$template->assign_block_vars('display.row', array(
					'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, $name),
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
				));
			}
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['field']),
				'L_NAME'	=> $lang['authlist_name'],
				'L_EXPLAIN'	=> $lang['explain'],
				
				'S_CREATE'	=> check_sid("$file?mode=_create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
	}
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>