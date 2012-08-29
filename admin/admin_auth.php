<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_fauth',
		'modes'		=> array(
			'forum'		=> array('title' => 'acp_auth_forum'),
			'group'		=> array('title' => 'acp_auth_group'),
			'mod'		=> array('title' => 'acp_auth_mod'),
			'user'		=> array('title' => 'acp_auth_user'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$update = ( isset($_POST['submit']) ) ? true : false;
	$grpadd = ( isset($_POST['grp_add']) ) ? true : false;
	$grpchg = ( isset($_POST['grp_chg']) ) ? true : false;
	$usradd = ( isset($_POST['usr_add']) ) ? true : false;
	$usrchg = ( isset($_POST['usr_chg']) ) ? true : false;
	
	$current = 'acp_forum';
	
	include('./pagestart.php');
	
	add_lang('auth');

	$error	= '';
	$index	= '';
	$fields = '';
	
#	debug($_POST);
	
	$log	= SECTION_AUTH;
	
#	$forum	= POST_FORUM;
#	$group	= POST_GROUPS;
#	$users	= POST_USERS;
#	$mods	= POST_MOD;
	
	$start	= request('start', INT);
	$action	= request('action', TYP);
	$type	= request('type', TYP);
	
	$all_forums = request('all_forums', TYP);
	$forum_id	= request('forum_id', ARY, INT);
	
	$all_groups = request('all_groups', TYP);
	$group_id	= request('group_id', INT);

	$username	= request('username', TXT);
	$usernames	= request('usernames', ARY, TXT);
	$user_id	= request('user_id', INT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_forum'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array('body' => 'style/acp_auths.tpl'));
	
	if ( !$update && !$grpadd && !$grpchg && !$usradd && !$usrchg )
	{
	#	debug($grpadd);
	#	debug($grpchg);
	#	debug($usradd);
	#	debug($usrchg);
		
		$template->assign_block_vars('display', array());
		
		switch ( $action )
		{
			case 'group':
			case 'users':
			
				$type = '';
			
				break;
				
			case 'forum':
			case 'mod':
			
				$type = 'all';
			
				$sql = 'SELECT forum_id, main, type, forum_name, forum_order FROM ' . FORMS . ' ORDER BY main ASC, forum_order ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$forums = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
				if ( isset($forums) )
				{
					foreach ( $forums as $rows )
					{
						if ( !$rows['type'] )
						{
							$cat[$rows['forum_id']] = $rows;
						}
						else if ( $rows['type'] == 1 )
						{
							$forum[$rows['main']][$rows['forum_id']] = $rows;
						}
						else
						{
							$subs[$rows['main']][$rows['forum_id']] = $rows;
						}
					}
					
					ksort($forum);
					ksort($subs);
				}
				
				$select = '<select class="normal" name="forum_id[]" multiple="multiple" size="15">';
				
				foreach ( $cat as $ckey => $crow )
				{
					$select .= '<option value="' . $crow['forum_id'] . '">' . $crow['forum_name'] . '</option>';
						
					if ( isset($forum[$ckey]) )
					{
						foreach ( $forum[$ckey] as $fkey => $frow )
						{
							$select .= '<option value="' . $frow['forum_id'] . '">&nbsp; &nbsp;' . $frow['forum_id'] . $frow['forum_name'] . '</option>';
							
							if ( isset($subs[$fkey]) )
							{
								foreach ( $subs[$fkey] as $skey => $srow )
								{
									$select .= '<option value="' . $srow['forum_id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $srow['forum_name'] . '</option>';
								}
							}
						}
					}
				}
				
				$select .= '</select>';

				break;
		}
		
		$fields .= '<input type="hidden" name="type" value="' . ( $type )  . '" />';
		
		$template->assign_vars(array(
			'S_SELECT' => $select,
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	else if ( $forum_id && $update )
	{
		debug($_POST);
	}
	
	/*
	if ( request('sel_f', TXT) && $forums )
	{
		$template->assign_block_vars('select', array());
		
		$group_in = $group_out = $user_in = $user_out = '';
		
		$forums_group = $forums_user = $auth_group_in = $auth_group_out = $auth_user_in = $auth_user_out = array();
		$group_ids = $user_ids = array();
		
		$sql = 'SELECT * FROM ' . AUTH_GROUPS . ' WHERE forum_id IN (' . implode(', ', $forums) . ')';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$forums_group[] = $row;
			$group_ids[] = $row['group_id'];
		}
		
		$sql = 'SELECT * FROM ' . AUTH_USERS . ' WHERE forum_id IN (' . implode(', ', $forums) . ')';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$forums_user[] = $row;
			$user_ids[] = $row['user_id'];
		}
		
	#	debug($forums_group, 'forums_group');
	#	debug($forums_user, 'forums_user');
		
		$sql = 'SELECT * FROM ' . GROUPS . ' ORDER BY group_order ASC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			if ( in_array($row['group_id'], $group_ids) )
			{
				$auth_group_in[] = $row;
			}
			else
			{
				$auth_group_out[] = $row;
			}
		}
		
		$sql = 'SELECT * FROM ' . USERS;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			if ( in_array($row['user_id'], $forums_user) )
			{
				$auth_user_in[] = $row;
			}
			else
			{
				$auth_user_out[] = $row;
			}
		}
		
		if (  $auth_group_in )
		{
			$group_in = '<select name="' . $grp . '[]" multiple="multiple" size="5">';
			
			foreach ( $auth_group_in as $row ) { $group_in .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>'; }
			
			$group_in .= '</select>';
		}
		
		if ( $auth_group_out )
		{
			$group_out = '<select name="' . $grp . '[]" multiple="multiple" size="5">';
			
			foreach ( $auth_group_out as $row ) { $group_out .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>'; }
			
			$group_out .= '</select>';
		}
		
		if (  $auth_user_in )
		{
			$user_in = '<select name="' . $grp . '[]" multiple="multiple" size="5">';
			
			foreach ( $auth_user_in as $row ) { $user_in .= '<option value="' . $row['user_id'] . '">' . $row['user_name'] . '</option>'; }
			
			$user_in .= '</select>';
		}
		
		if ( $auth_user_out )
		{
			$user_out = '<select name="' . $grp . '[]" multiple="multiple" size="5">';
			
			foreach ( $auth_user_out as $row ) { $user_out .= '<option value="' . $row['user_id'] . '">' . $row['user_name'] . '</option>'; }
			
			$user_out .= '</select>';
		}
		
		foreach ( $forums as $row ) { $fields .= '<input type="hidden" name="' . $frs . '[]" value="' . $row . '" />'; }
		
		$template->assign_vars(array(
			'S_GROUP_IN'	=> $group_in,
			'S_GROUP_OUT'	=> $group_out,
			
			'S_USER_IN'		=> $user_in,
			'S_USER_OUT'	=> $user_out,
		
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	else if ( $types && ( request('grp_create', TXT) || request('grp_update', TXT) ) )
	{
		$template->assign_block_vars('auth_forum', array());
		
		switch ( $right )
		{
			case 'admin':	$load_var = 'a_'; break;
			case 'mod':		$load_var = 'm_'; break;
			case 'user':	$load_var = 'u_'; break;
			default:		$load_var = 'f_'; break;
		}
		
		$type = ( request('grp_create', TXT) || request('grp_update', TXT) ) ? GROUPS : USERS;
		$type_id = ( request('grp_create', TXT) || request('grp_update', TXT) ) ? 'group_id' : 'user_id';
		
		
		$sql = 'SELECT * FROM ' . ACL_LABEL . ' WHERE label_type = "' . $load_var . '"';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$acl_labels[] = $row;
			$labels_ids[] = $row['label_id'];
		}
		$db->sql_freeresult($result);
		
		$sql = 'SELECT f.*, o.* FROM ' . ACL_FIELDS . ' f, ' . ACL_OPTIONS . ' o WHERE f.field_name LIKE "' . $load_var . '%" AND option_id IN (' . implode(', ', $labels_ids) . ')';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	
		while ( $row = $db->sql_fetchrow($result) )
		{
			$acl_fields[$row['field_id']] = $row['field_name'];
			$acl_option[$row['option_id']][$row['field_name']] = $row['auth_value'];
			$acl_groups[$row['field_type']][$row['field_name']] = $row['field_name'];
		}
		$db->sql_freeresult($result);
		
		$acl_group = $acl_groups;
		unset($acl_groups);
		
		foreach ( $acl_group as $grouped => $vars )
		{
			foreach ( $vars as $keys )
			{
				$acl_groups[$grouped][] = $keys;
			}
		#	$sort[$grouped] = array_unique($vars, SORT_STRING);
		}
		
		foreach ( $acl_labels as $rows )
		{
			$template->assign_block_vars('auth_forum.set_perm', array(
				'ID'		=> $rows['label_id'],
				'OPTION'	=> $rows['label_name'],
			));
			
			foreach ( $acl_option[$rows['label_id']] as $acl_field => $value )
			{
				$template->assign_block_vars('auth_forum.set_perm.row', array(
					'FIELD' => $acl_field,
					'VALUE' => ( $value != -1 ) ? ( $value == 1 ) ? '_y' : '_u' : '_n',
				));
			}				
		}
		
		$sql = "SELECT * FROM $type WHERE $type_id IN (" . implode(", ", $groups) . ") ORDER BY group_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$group = array();
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$group[$row['group_id']] = $row;
		}
		
		$sql = 'SELECT forum_id, main, forum_name, forum_order FROM ' . FORMS . ' WHERE forum_id IN (' . implode(', ', $forums) . ') ORDER BY main ASC, forum_order ASC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$forum_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		$sql = 'SELECT * FROM ' . ACL_GROUPS . ' WHERE group_id IN (' . implode(', ', $groups) . ') AND forum_id IN (' . implode(', ', $forums) . ')';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$access = '';
		$access_label = '';
		
		while ( $rows = $db->sql_fetchrow($result) )
		{
			if ( $rows['label_id'] != 0 )
			{
				if ( isset($acl_option[$rows['label_id']]) )
				{
					$access[$rows['forum_id']][$rows['group_id']] = $acl_option[$rows['label_id']];
				}
				
				$access_label[$rows['forum_id']][$rows['group_id']] = $rows['label_id'];
			}

			if ( $rows['field_id'] != 0 )
			{
				$access[$rows['forum_id']][$rows['group_id']][$acl_fields[$rows['field_id']]] = $rows['auth_value'];
			}
		}
		
		foreach ( $forums as $key )
		{
			foreach ( $forum_rows as $rows )
			{
				if ( $key == $rows['forum_id'] )
				{
					$forms[] = $rows;
				}
			}
		}
		
	#	@debug($access);
		
		$add_lang = array('label_id' => 0, 'label_name' => 'no select', 'label_type' => $load_var);
		
		array_unshift($acl_labels, $add_lang);
	
		foreach ( $forms as $forum_data )
		{
			$forum_id	= $forum_data['forum_id'];
			$forum_name = $forum_data['forum_name'];
			
			$template->assign_block_vars('auth_forum.row', array(
				'NAME' => $forum_name
			));
			
			foreach ( $group as $group_id => $group_data )
			{
				$group_name = $group_data['group_name'];
			#	$group_auth = isset($forum_auths[$forum_id][$group_id]) ? reset_fields($forum_auths[$forum_id][$group_id]) : empty_fields($forum_auth_fields);
			#	$group_temp	= simple_auth($group_auth);
				
				
				
				$simple_auth = '<select name="' . sprintf('simpleauth%sg%s', $forum_id, $group_id) . '" id="' . sprintf('s%sg%s', $forum_id, $group_id) . '" onchange="set_permission(this.options[selectedIndex].value, \'' . $forum_id . '\', \'' . $group_id . '\');">';
				
				foreach ( $acl_labels as $options )
				{
			#		debug($options);
			#		@debug($access_label[$forum_id][$group_id]);
					$selected = ( @$access_label[$forum_id][$group_id] == $options['label_id'] ) ? ' selected="selected"' : '';
					$simple_auth .= '<option value="' . $options['label_id'] . '"' . $selected . '>' . $options['label_name'] . '</option>';
				}
				
				$simple_auth .= '</select>';
				
			#	$row_format = sprintf('f[%s][%s]', $forum_id, $group_id);
				$row_switch = sprintf('s%sg%s', $forum_id, $group_id);
				
				$template->assign_block_vars('auth_forum.row.group', array(
					'NAME'		=> $group_name,
					'TOGGLE'	=> $row_switch,
			#		'SWITCH'	=> $row_switch,
					'SIMPLE'	=> $simple_auth,
					'AUTHS'		=> 'auths' . $forum_id . $group_id,
				));
				
				foreach ( $acl_groups as $cat => $rows )
				{
					$template->assign_block_vars('auth_forum.row.group.cats', array(
						'CAT'	=> $cat,
						'NAME'	=> $lang['tabs'][$cat],
					#	'AUTH'	=> $images['icon_accept'],
					#	'AUTH'	=> ( $access ) ? ' class="icon_accept"' : '',
						
						'OPTIONS' => "options{$forum_id}{$group_id}{$cat}",
					));
					
					foreach ( $rows as $row )
					{
						$row_format = sprintf('set[%s][%s][%s]', $forum_id, $group_id, $row);
						
					#	$opt = '';
					#	$opt .= '<span style="padding:4px;"></span>';
					#	$opt .= '<span style="padding:4px;"></span>';
					#	$opt .= '';
					
					
						$template->assign_block_vars('auth_forum.row.group.cats.auths', array(
							'LANG' => $row,
						#	'AUTH' => $opt,
							'OPT_YES'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s[%s][%s]_y', $row, $forum_id, $group_id) . '" onclick="reset_simpleauth(\'' . sprintf('s%sg%s', $forum_id, $group_id) . '\');" value="1"' . (( @$access[$forum_id][$group_id][$row] == 1 ) ? ' checked="checked"' : '') . ' />&nbsp;' . $lang['common_yes'] . '</label>',
							'OPT_UNSET'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s[%s][%s]_u', $row, $forum_id, $group_id) . '" onclick="reset_simpleauth(\'' . sprintf('s%sg%s', $forum_id, $group_id) . '\');" value="0"' . (( @$access[$forum_id][$group_id][$row] == 0 ) ? ' checked="checked"' : '') . ' />&nbsp;' . $lang['common_no'] . '</label>',
							'OPT_NEVER'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s[%s][%s]_n', $row, $forum_id, $group_id) . '" onclick="reset_simpleauth(\'' . sprintf('s%sg%s', $forum_id, $group_id) . '\');" value="-1"' . (( @$access[$forum_id][$group_id][$row] == -1 ) ? ' checked="checked"' : '') . ' />&nbsp;' . $lang['common_never'] . '</label>',
						));
					}
				}
				/asdd
				foreach ( $group_auth as $key_name => $value )
				{
					$opt = '';
					
					if ( strpos($key_name, 'f_') !== false ) 
					{
						$mod = 'fauth';
					}
					else if ( strpos($key_name, 'm_') !== false )
					{
						$mod = 'mauth';
					}
					else if ( strpos($key_name, 'p_') !== false )
					{
						$mod = 'pauth';
					}
					
					$opt .= '<label><input type="radio" name="' . sprintf('f%s[%s][%s]', $forum_id, $group_id, $key_name) . '" id="' . sprintf('%s[f%s][g%s]_y', $key_name, $forum_id, $group_id) . '"' . (( $value == 1 ) ? ' checked="checked"' : '') . ' onclick="reset_simpleauth(\'' . sprintf('s%sg%s', $forum_id, $group_id) . '\');" value="1" />&nbsp;' . $lang['common_yes'] . '</label><span style="padding:4px;"></span>';
					$opt .= '<label><input type="radio" name="' . sprintf('f%s[%s][%s]', $forum_id, $group_id, $key_name) . '" id="' . sprintf('%s[f%s][g%s]_n', $key_name, $forum_id, $group_id) . '"' . (( $value == 0 ) ? ' checked="checked"' : '') . ' onclick="reset_simpleauth(\'' . sprintf('s%sg%s', $forum_id, $group_id) . '\');" value="0" />&nbsp;' . $lang['common_no'] . '</label>';
						
					
					if ( isset($mod) )
					{
						$template->assign_block_vars('auth_forum.row.group.' . $mod, array(
							'AUTH' => $opt,
							'LANG' => $lang[$key_name],
						));
					}
				}
				adasd/
			}
		}
		
		
		foreach ( $groups as $row ) { $fields	.= '<input type="hidden" name="g[]" value="' . $row . '" />'; }
		foreach ( $forums as $row ) { $fields	.= '<input type="hidden" name="f[]" value="' . $row . '" />'; }
		
		$template->assign_vars(array(
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	else if ( $forums && $groups && $update )
	{
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
	
	#	$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('admin_forumauth.php?' . $frs . '=' . $forum_id) . '">'));
		$message = $lang['Forum_auth_updated'] . '<br><br>' . sprintf($lang['Click_return_forumauth'],  '<a href="' . check_sid('admin_forumauth.php') . '">', "</a>");
		message(GENERAL_MESSAGE, $message);
	}
	*/
	/*
	else
	{
		$template->assign_block_vars('display', array());
		
	#	$sql = 'SELECT forum_id, forum_sub, forum_name, forum_order FROM ' . FORMS . ' WHERE forum_id IN (' . implode(', ', $forums) . ') ORDER BY forum_sub ASC, forum_order ASC';
		$sql = 'SELECT forum_id, main, type, forum_name, forum_order FROM ' . FORMS . ' ORDER BY main ASC, forum_order ASC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$forum_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		if ( isset($forum_rows) )
		{
			foreach ( $forum_rows as $rows )
			{
				if ( !$rows['type'] )
				{
					$forum_cat[$rows['forum_id']] = $rows;
				}
				else if ( $rows['type'] == 1 )
				{
					$forum_frs[$rows['main']][$rows['forum_id']] = $rows;
				}
				else
				{
					$forum_sub[$rows['main']][$rows['forum_id']] = $rows;
				}
			}
		}
		
		ksort($forum_frs);
		ksort($forum_sub);
		
		$forms = '<select name="' . $frs . '[]" multiple="multiple" size="15">';
		
		foreach ( $forum_cat as $ckey => $crow )
		{
			$forms .= '<option disabled="disabled">' . $crow['forum_name'] . '</option>';
				
			if ( isset($forum_frs[$ckey]) )
			{
				foreach ( $forum_frs[$ckey] as $fkey => $frow )
				{
					$forms .= '<option value="' . $frow['forum_id'] . '">&nbsp; &nbsp;' . $frow['forum_id'] . $frow['forum_name'] . '</option>';
					
					if ( isset($forum_sub[$fkey]) )
					{
						foreach ( $forum_sub[$fkey] as $skey => $srow )
						{
							$forms .= '<option value="' . $srow['forum_id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $srow['forum_name'] . '</option>';
						}
					}
				}
			}
		}
		
		$forms .= '</select>';
		
		$template->assign_vars(array(
			'S_FORMS' => $forms,
			
			'S_ACTION'	=> check_sid($file),
	#		'S_FIELDS'	=> $fields,
		));
	}
	*/
}

$template->pparse('body');

include('./page_footer_admin.php');

?>