<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_authlist',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_authlist'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_authlist';

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
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'delete')) ) ? $mode : '';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'authlist' => array(
					'title' => 'data_input',
					'authlist_name'	=> array('validate' => TXT,	'type' => 'text:25;25', 'explain' => false, 'required' => 'input_name', 'check' => true, 'prefix' => 'auth_'),
				),
			);
			
			if ( $mode == 'create' && !$update )
			{
				$data_sql = array('authlist_name' => str_replace(' ', '_', strtolower(request('authlist_name', TXT))));
			}
			else if ( $mode == 'update' && !$update )
			{
				$data_sql = data(AUTHLIST, $data, false, 1, true);
			}
			else
			{
				$data_sql = build_request(AUTHLIST, $vars, 'authlist', $error);
				
				if ( !$error )
				{
					$sql = "SELECT group_id, group_auth FROM " . GROUPS . " ORDER BY group_order";
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
						$sql = sql(AUTHLIST, $mode, $data_sql);
						$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						
						foreach ( $grp as $k => $v )
						{
							$auth[$v['group_id']] = unserialize($v['group_auth']);
							$mauth = array_merge($auth[$v['group_id']], array($data['authlist_name'] => 0));
							$sauth = serialize($mauth);
						
							$sql = "UPDATE " . GROUPS . " SET group_auth = '$sauth' WHERE group_id = " . $v['group_id'];
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
						$sql = sql(AUTHLIST, $mode, $data_sql, 'authlist_id', $data);
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&amp;id=$data"));
						
						foreach ( $grp as $k => $v )
						{
							$auth[$v['group_id']] = unserialize($v['group_auth']);
							
							foreach ( $auth[$v['group_id']] as $key => $value )
							{
								if ( $key == request('old_name', TXT) )
								{
									$nauth[$data['authlist_name']] = $value;
								}
								else
								{
									$nauth[$key] = $value;
								}
							}
							
							$sauth = serialize($nauth);
						
							$sql = "UPDATE " . GROUPS . " SET group_auth = '$sauth' WHERE group_id = " . $v['group_id'];
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
								if ( $key == request('old_name', TXT) )
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
					error('ERROR_BOX', $error);
				}
			}
			
			build_output($data, $vars, 'input', false, MAPS);
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
			$fields .= "<input type=\"hidden\" name=\"old_name\" value=\"" . $data['authlist_name'] . "\" />";
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['field'], $data['authlist_name']),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'delete':
		
			$data_sql = data(AUTHLIST, $data, false, 1, true);
		
			if ( $data && $confirm )
			{
				$sql = sql(AUTHLIST, $mode, $data_sql, 'authlist_id', $data);
			#	$add = sql(GROUPS, 'alter', array('part' => "DROP ", 'type'	=> $data['authlist_name']));
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				$sql = "SELECT group_id, group_auth FROM " . GROUPS . " ORDER BY group_order";
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
					$auth[$v['group_id']] = unserialize($v['group_auth']);
					
					foreach ( $auth[$v['group_id']] as $key => $value )
					{
						if ( $key != $data['authlist_name'] )
						{
							$nauth[$key] = $value;
						}
					}
					
					$sauth = serialize($nauth);
				
					$sql = "UPDATE " . GROUPS . " SET group_auth = '$sauth' WHERE group_id = " . $v['group_id'];
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
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				
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
			
			$fields	= '<input type="hidden" name="mode" value="create" />';
			
			$tmp = data(AUTHLIST, false, 'authlist_id ASC', 0, false);
			$cnt = count($tmp);
			
			for ( $i = 0; $i < $cnt; $i++ )
			{
				$id		= $tmp[$i]['authlist_id'];
				$name	= $tmp[$i]['authlist_name'];

				$template->assign_block_vars('display.row', array(
					'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'common_delete'),
				));
			}
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['field']),
				'L_NAME'	=> $lang['authlist_name'],
				'L_EXPLAIN'	=> $lang['explain'],
				
				'S_CREATE'	=> check_sid("$file?mode=create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			break;
	}
	
	$template->pparse('body');
	
	include('./page_footer_admin.php');
}

?>