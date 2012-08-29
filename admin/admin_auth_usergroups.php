<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_auth',
		'modes'		=> array(
			'groups'	=> array('title' => 'acp_auth_groups'),
			'users'		=> array('title' => 'acp_auth_users'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_auth';
	
	include('./pagestart.php');
	
	add_lang('auth_usergroups');

	$index	= '';
	$fields	= '';
	
	$log	= SECTION_AUTH;
	$url	= POST_AUTH_USERGROUPS;
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data	= request($url, INT);
	$mode	= request('action', TXT);
	$right	= request('right', TXT);
	$type	= request('type', INT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body' => 'style/acp_auth_usergroups.tpl',
	));
	
#	debug($gauthlist);
#	debug($forum_auth_fields);

#	$groups	= ( is_array($g) ) ? $g : array($g);
#	$forums	= ( is_array($f) ) ? $f : array($f);
	
#	debug($_POST);
#	debug($data);
	
#	debug($groups);
#	debug($forums);

	if ( $data && !$update )
	{
		$template->assign_block_vars('auth_show', array());
		
		$load_var_array = ( $right != 'admin' ) ? (( $right == 'mod' ) ? 'a_' : 'a_') : 'a_';
	
		$sql = 'SELECT * FROM cms_acl_label WHERE label_type = "' . $load_var_array . '"';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$label = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		$sql = 'SELECT * FROM cms_acl_fields WHERE field_name LIKE "%' . $load_var_array . '%"';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$acl_fields[$row['field_id']] = $row['field_name'];
			$grouped[$row['field_type']][] = $row['field_name'];
		}
		
		$sql = 'SELECT * FROM cms_acl_options';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$options = array();
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$options[$row['option_id']][$row['field_id']] = $row['auth_value'];
		}
		
		foreach ( $label as $rows )
		{
			$label_id = $rows['label_id'];
			
			$template->assign_block_vars('auth_show.set_perm', array(
				'ID' => $label_id,
				'OPTION' => $rows['label_name'],
			));
			
			foreach ( $acl_fields as $key => $name )
			{
				$template->assign_block_vars('auth_show.set_perm.row', array(
					'FIELD' => $name,
					'VALUE' => ( $options[$label_id][$key] ) ? '_y' : '_n',
				));
			}				
		}
		
		$access = array();
		$tlabel = '';
		
		if ( $type )
		{
			$tmp_data = data(GROUPS, $data, false, 1, true);
			
			$sql = 'SELECT * FROM cms_acl_groups WHERE group_id = "' . $data . '" AND forum_id = 0';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$tmp_data = data(USERS, $data, false, 1, true);
			
			$sql = 'SELECT * FROM cms_acl_users WHERE user_id = "' . $data . '" AND forum_id = 0';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		}
		
		if ( $data_access = $db->sql_fetchrowset($result) )
		{
			foreach ( $data_access as $rows )
			{
				if ( $rows['label_id'] != 0 )
				{
					if ( isset($options[$rows['label_id']]) )
					{
						foreach ( $options[$rows['label_id']] as $key => $row )
						{
							$access[$acl_fields[$key]] = $row;
						}
					}
					
					$tlabel = $rows['label_id'];
				}
				
				if ( $rows['field_id'] != 0 )
				{
					$access[$acl_fields[$rows['field_id']]] = $rows['auth_value'];
				}
			}
		}
		
		$add_lang = array('label_id' => 0, 'label_name' => 'no select', 'label_type' => $load_var_array);
		
		array_unshift($label, $add_lang);
	
		$simple_auth = '<select name="label" id="' . sprintf('sa%s', $data) . '" onchange="set_permission(this.options[selectedIndex].value, \'' . $right . '\');">';
		
		foreach ( $label as $rows )
		{
			$selected = ( $tlabel == $rows['label_id'] ) ? ' selected="selected"' : '';
			$simple_auth .= '<option value="' . $rows['label_id'] . '"' . $selected . '>' . $rows['label_name'] . '</option>';
		}
		
		$simple_auth .= '</select>';
			
		foreach ( $grouped as $cat => $rows )
		{
			$template->assign_block_vars('auth_show.cats', array(
				'CAT' => $cat,
				'NAME' => $cat,
			));
			
			foreach ( $rows as $row )
			{
				$opt = '<label><input type="radio" name="' . $row . '" id="' . sprintf('%s[%s]_y', $row, $right) . '"' . (( @$access[$row] == 1 ) ? ' checked="checked"' : '') . ' onclick="reset_simpleauth(\'' . sprintf('sa%s', $data) . '\');" value="1" />&nbsp;' . $lang['common_yes'] . '</label><span style="padding:4px;"></span>';
				$opt .= '<label><input type="radio" name="' . $row . '" id="' . sprintf('%s[%s]_n', $row, $right) . '"' . (( @$access[$row] == 0 ) ? ' checked="checked"' : '') . ' onclick="reset_simpleauth(\'' . sprintf('sa%s', $data) . '\');" value="0" />&nbsp;' . $lang['common_no'] . '</label><span style="padding:4px;"></span>';
				$opt .= '<label><input type="radio" name="' . $row . '" id="' . sprintf('%s[%s]_u', $row, $right) . '"' . (( @$access[$row] == -1 ) ? ' checked="checked"' : '') . ' onclick="reset_simpleauth(\'' . sprintf('sa%s', $data) . '\');" value="-1" />&nbsp;' . $lang['common_out'] . '</label>';
			
			
				$template->assign_block_vars('auth_show.cats.auths', array(
					'LANG' => $row,
					'AUTH' => $opt,
				));
			}
		}
		
		$fields .= '<input type="hidden" name="type" value="' . $type . '" />';
		$fields .= '<input type="hidden" name="right" value="' . $right . '" />';
		$fields .= '<input type="hidden" name="id" value="' . $data . '" />';
		
		$template->assign_vars(array(
			'NAME' => $right,
			
			'SIMPLE'	=> $simple_auth,
		
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	else if ( $data && $update )
	{
	#	debug($_POST);
		
		$sql = 'SELECT * FROM ' . ACL_FIELDS . ' WHERE field_name LIKE "%' . ( ( $right != 'admin' ) ? (( $right == 'mod' ) ? 'm_' : 'u_') : 'a_' ) . '%"';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$acl_fields[$row['field_id']] = $row['field_name'];
		}
		
		if ( $type )
		{
			$sql = 'SELECT * FROM ' . ACL_GROUPS . ' WHERE group_id = "' . $data . '" AND forum_id = 0';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = 'SELECT * FROM ' . ACL_USERS . ' WHERE user_id = "' . $data . '" AND forum_id = 0';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		}
		$data_access = $db->sql_fetchrowset($result);
		
	#	if (  )
	#	{
			$label = request('label', INT);
		#	debug($label, 'label', true);
		#	debug($data_access);
		
			$sql = 'DELETE FROM ' . ( $type ? ACL_GROUPS : ACL_USERS ) . ' WHERE ' . ( $type ? 'group_id' : 'user_id' ) . ' = "' . $data . '" AND forum_id = 0';
			if ( !$db->sql_query($sql) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			if ( $label )
			{
				$sql = 'INSERT INTO ' . ( $type ? ACL_GROUPS . '(group_id, ' : ACL_USERS . '(user_id, ' ) . 'forum_id, field_id, label_id, auth_value) VALUES (' . $data . ', 0, 0, ' . $label . ', 0)';
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
			else
			{
				$tmp_auth = '';
				
				foreach ( $acl_fields as $key => $name )
				{
					if ( isset($_POST[$name]) )
					{
						$tmp_field = request($name, INT);
						
						if ( $tmp_field != 0 )
						{
							$tmp_auth[$key] = $tmp_field;
						}
					}
				}
				
				if ( $tmp_auth )
				{
					foreach ( $tmp_auth as $key => $value )
					{
						$sql = 'INSERT INTO ' . ( $type ? ACL_GROUPS . '(group_id, ' : ACL_USERS . '(user_id, ' ) . 'forum_id, field_id, label_id, auth_value) VALUES (' . $data . ', 0, ' . $key . ', 0, ' . $value . ')';
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
						
				}
			}
	#	}
		/*
		$sql = 'SELECT * FROM ' . FORMS_AUTH . ' WHERE group_id IN (' . implode(', ', $groups) . ') AND forum_id IN (' . implode(', ', $forums) . ')';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$forum_auths[$row['forum_id']][$row['group_id']] = $row;
		}
		
		foreach ( $forums as $forum_id )
		{
			$temp_data_forum = $_POST["f$forum_id"];
			
		#	debug($temp_data_forum);
		
			foreach ( $groups as $group_id )
			{
				$temp_data_group = $temp_data_forum[$group_id];
				$temp_data_check = isset($forum_auths[$forum_id][$group_id]) ? true : false;
				
				if ( $temp_data_check )
				{
					$sql = sql(FORMS_AUTH, 'update', $temp_data_group, array('group_id', 'forum_id'), array($group_id, $forum_id));
				}
				else
				{
					$temp_data_group = array_merge($temp_data_group, array('forum_id' => $forum_id, 'group_id' => $group_id));
					$sql = sql(FORMS_AUTH, 'create', $temp_data_group);
				}
			}
		}
		*/
	#	$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('admin_forumauth.php?' . POST_FORUM . '=' . $forum_id) . '">'));
	#	$message = $lang['Forum_auth_updated'] . '<br><br>' . sprintf($lang['Click_return_forumauth'],  '<a href="' . check_sid('admin_forumauth.php') . '">', "</a>");
	#	message(GENERAL_MESSAGE, $message);
	}
	else
	{
		$template->assign_block_vars('display', array());
		
	#	$type = ( $type == 'groups' ) ? GROUPS : USERS;
		
		if ( $mode == 'groups' )
		{
			$tmp = data(GROUPS, '', 'group_order ASC', 1, false);
			
			$select = '<select name="id">';
				
			foreach ( $tmp as $row )
			{
				$select .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
			}
			
			$select .= '</select>';
			
			$fields .= '<input type="hidden" name="type" value="1" />';
		}
		else
		{
			$tmp = data(USERS, 'user_id != 1', 'user_id DESC', 1, false);
			
			$select = '<select name="id">';
				
			foreach ( $tmp as $row )
			{
				$select .= '<option value="' . $row['user_id'] . '">' . $row['user_name'] . '</option>';
			}
			
			$select .= '</select>';
			
			$fields .= '<input type="hidden" name="type" value="0" />';
		}
		
		$select .= '&nbsp;<select name="right">';
		
		foreach ( $lang['right_select'] as $key => $row )
		{
			$select .= '<option value="' . $key . '"' . (( $key == 'admin' ) ? ' selected="selected"' : '') . '>' . $row . '</option>';
		}
		$select .= '</select>';
		
	#	debug($tmp);
	#	debug($select);
		
		$template->assign_vars(array(
			'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
			'L_EXPLAIN'	=> $lang['explain'],
	
			'S_SELECT' => $select,
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
}

$template->pparse('body');

include('./page_footer_admin.php');

?>