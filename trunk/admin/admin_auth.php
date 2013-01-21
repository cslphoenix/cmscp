<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_permission',
		'modes'		=> array(
			'group_forums'	=> array('title' => 'acp_group_forums'),	// group -> f_ m_
			'user_forums'	=> array('title' => 'acp_user_forums'),		// user -> f_ m_
			'mod_all'		=> array('title' => 'acp_mod_all'),			// foren -> users/groups m_
			'forum_all'		=> array('title' => 'acp_forum_all'),		// foren -> users/groups f_
			'group_perm'	=> array('title' => 'acp_group_perm'),		// group -> a_ m_ u_ d_ g_
			'user_perm'		=> array('title' => 'acp_user_perm'),		// user -> a_ m_ u_ d_ g_
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$update = ( isset($_POST['update']) ) ? true : false;
	
	$current = 'acp_permission';
	
	include('./pagestart.php');
	
	add_lang('permission');

	$error	= '';
	$index	= '';
	$fields = '';
	
	$log	= SECTION_AUTH;
	
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
	
	debug($_POST, '_POST');

#	debug($type, 'type');
	
	$acp_title = sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_forum'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array('body' => 'style/acp_auths.tpl'));
	
	function check_ids(&$ids)
	{
		global $db;
		
		$sql = 'SELECT forum_id FROM ' . FORUM . ' WHERE type != 0';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$real_ids[] = $row['forum_id'];
		}
		
		if ( $ids )
		{
			foreach ( $ids as $row )
			{
				if ( in_array($row, $real_ids) )
				{
					$new[] = $row;
				}
			}
		}
		$new = (isset($new)) ? $new : false;
		
		return $new;
	}
	
	function construct_permission_box($ids, $forums, $mode, $block, $options, $type)
	{
		global $db, $lang, $template;
		
	#	debug($options, 'options');
	#	debug($type, 'type');
		
		$sql = 'SELECT * FROM ' . ACL_LABEL . ' WHERE label_type = "' . $type . '"';
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
		
		$sql = 'SELECT f.field_id, f.field_name, f.field_type, o.option_id, o.auth_value FROM ' . ACL_FIELDS . ' f, ' . ACL_OPTIONS . ' o WHERE f.field_name LIKE "' . $type . '%" AND option_id IN (' . implode(', ', $labels_ids) . ')';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$acl_fields[$row['field_name']] = $row['field_id'];
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
		}
		
		foreach ( $acl_labels as $rows )
		{
			$template->assign_block_vars("$block.settings", array(
				'ID'		=> $rows['label_id'],
				'OPTION'	=> $rows['label_name'],
			));
			
			if ( isset($acl_option[$rows['label_id']]) )
			{
				foreach ( $acl_option[$rows['label_id']] as $acl_field => $value )
				{
					$template->assign_block_vars("$block.settings.row", array(
						'FIELD' => $acl_field,
						'VALUE' => ($value == 1) ? '_y' : (($value == -1) ? '_n' : '_u'),
					));
				}
			}
			else
			{
				foreach ( $acl_fields as $acl_field => $value )
				{
					$template->assign_block_vars("$block.settings.row", array(
						'FIELD' => $acl_field,
						'VALUE' => '_u',
					));
				}
			}
		}
		
		list($tmp_mode, $tmp_opts) = explode('_', $mode);
		
		switch ( $tmp_mode )
		{
			case 'group':
			
				$sql = 'SELECT * FROM ' . GROUPS . ' WHERE group_id IN (' . (is_array($ids) ? implode(', ', $ids) : $ids ) . ') ORDER BY group_order ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$group = array();
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$group[$row['group_id']] = $row;
				}
				
				$sql = 'SELECT * FROM ' . ACL_GROUPS . ' WHERE group_id IN (' . (is_array($ids) ? implode(', ', $ids) : $ids ) . ') AND forum_id IN (' . (is_array($forums) ? implode(', ', $forums) : $forums ) . ')';
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
				
				debug($access);

				break;
		}
		
		$s_action = '';
				
	#	if ( is_array($options) )
	#	{
	#		$s_action = '<select class="postselect" name="type" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
	#		$s_action = '<select name="type">';
	#		
	#		foreach ( $options as $opts )
	#		{
			#	$selected = ( @$access_label[$ids] == $options['label_id'] ) ? ' selected="selected"' : '';
	#			$s_action .= '<option value="' . $opts . '">' . lang("{$opts}right") . '</option>';
	#		}
	#		
	#		$s_action .= '</select>';
	#	}
		
		switch ( $type )
		{
			case 'f_':
			case 'm_':
				
				$sql = 'SELECT forum_id, main, forum_name, forum_order FROM ' . FORUM . ' WHERE forum_id IN (' . (is_array($forums) ? implode(', ', $forums) : $forums ) . ') ORDER BY main ASC, forum_order ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$forum_rows = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				
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
				
				$add_lang = array('label_id' => 0, 'label_name' => 'no select', 'label_type' => $type);
				array_unshift($acl_labels, $add_lang);
				
				foreach ( $forms as $forum_data )
				{
					$forum_id	= $forum_data['forum_id'];
					$forum_name = $forum_data['forum_name'];
					
					$template->assign_block_vars("$block.row", array(
						'NAME' => $forum_name,
					));
					
					foreach ( $group as $group_id => $group_data )
					{
						$group_name = $group_data['group_name'];
		
						$simple_auth = '<select name="' . sprintf('l_%s[%s]', $forum_id, $group_id) . '" id="' . sprintf('s%sg%s', $forum_id, $group_id) . '" onchange="set_permission(this.options[selectedIndex].value, \'' . $forum_id . '\', \'' . $group_id . '\');">';
						
						foreach ( $acl_labels as $labels )
						{
							$selected = ( @$access_label[$forum_id][$group_id] == $labels['label_id'] ) ? ' selected="selected"' : '';
							$simple_auth .= '<option value="' . $labels['label_id'] . '"' . $selected . '>' . $labels['label_name'] . '</option>';
						}
						
						$simple_auth .= '</select>';
						
						$row_switch = sprintf('s%sg%s', $forum_id, $group_id);
						
						$template->assign_block_vars("$block.row.group", array(
							'NAME'		=> $group_name,
							'TOGGLE'	=> $row_switch,
							'SIMPLE'	=> $simple_auth,
							'AUTHS'		=> 'auths' . $forum_id . $group_id,
						));
						
						foreach ( $acl_groups as $cat => $rows )
						{
							$template->assign_block_vars("$block.row.group.cats", array(
								'CAT'	=> $cat,
								'NAME'	=> $lang['tabs'][$type][$cat],
								'OPTIONS' => "options{$forum_id}{$group_id}{$cat}",
							));
							
							foreach ( $rows as $row )
							{
								$row_format = sprintf('f_%s[%s][%s]', $forum_id, $group_id, $row);
								
								$template->assign_block_vars("$block.row.group.cats.auths", array(
									'LANG'		=> $row,
									'OPT_YES'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s[%s][%s]_y', $row, $forum_id, $group_id) . '" onclick="reset_simpleauth(\'' . sprintf('s%sg%s', $forum_id, $group_id) . '\');" value="1"' . (( @$access[$forum_id][$group_id][$row] == 1 ) ? ' checked="checked"' : '') . ' /></label>',
									'OPT_UNSET'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s[%s][%s]_u', $row, $forum_id, $group_id) . '" onclick="reset_simpleauth(\'' . sprintf('s%sg%s', $forum_id, $group_id) . '\');" value="0"' . (( @$access[$forum_id][$group_id][$row] == 0 ) ? ' checked="checked"' : '') . ' /></label>',
									'OPT_NEVER'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s[%s][%s]_n', $row, $forum_id, $group_id) . '" onclick="reset_simpleauth(\'' . sprintf('s%sg%s', $forum_id, $group_id) . '\');" value="-1"' . (( @$access[$forum_id][$group_id][$row] == -1 ) ? ' checked="checked"' : '') . ' /></label>',
								));
							}
						}
					}
				}
			
				break;
			
			case 'a_':
			case 'm_':
			case 'u_':
			case 'd_':
			case 'g_':
			#	debug($group);
			#	$group_name = $group_data['group_name'];
		
				$simple_auth = '<select name="' . sprintf('simpleauthg%s', $ids) . '" id="' . sprintf('g%s', $ids) . '" onchange="set_permission(this.options[selectedIndex].value, \'' . $ids . '\');">';
				
				foreach ( $acl_labels as $options )
				{
					$selected = ( @$access_label[$ids] == $options['label_id'] ) ? ' selected="selected"' : '';
					$simple_auth .= '<option value="' . $options['label_id'] . '"' . $selected . '>' . $options['label_name'] . '</option>';
				}
				
				$simple_auth .= '</select>';
				
				$row_switch = sprintf('g%s', $ids);
				
				foreach ( $acl_groups as $cat => $rows )
				{
					$template->assign_block_vars("$block.cats", array(
						'CAT'		=> $cat,
						'NAME'		=> $lang['tabs'][$type][$cat],
						'OPTIONS'	=> "options0{$ids}{$cat}",
					));
					
					foreach ( $rows as $row )
					{
						$template->assign_block_vars("$block.cats.auths", array(
							'LANG'		=> $row,
							'OPT_YES'	=> '<label><input type="radio" name="' . $row . '" id="' . sprintf('%s[%s]_y', $row, $ids) . '" onclick="reset_simpleauth(\'' . sprintf('g%s', $ids) . '\');" value="1"' . (( @$access[$ids][$row] == 1 ) ? ' checked="checked"' : '') . ' /></label>',
							'OPT_UNSET'	=> '<label><input type="radio" name="' . $row . '" id="' . sprintf('%s[%s]_u', $row, $ids) . '" onclick="reset_simpleauth(\'' . sprintf('g%s', $ids) . '\');" value="0"' . (( @$access[$ids][$row] == 0 ) ? ' checked="checked"' : '') . ' /></label>',
							'OPT_NEVER'	=> '<label><input type="radio" name="' . $row . '" id="' . sprintf('%s[%s]_n', $row, $ids) . '" onclick="reset_simpleauth(\'' . sprintf('g%s', $ids) . '\');" value="-1"' . (( @$access[$ids][$row] == -1 ) ? ' checked="checked"' : '') . ' /></label>',
						));
					}
				}
				
				$template->assign_vars(array(
					'NAME' => $group[$ids]['group_name'],
					
					'SIMPLE'	=> $simple_auth,
					'TOGGLE'	=> $row_switch,
					'AUTHS'		=> 'auths' . $ids,
				));
			
				break;
		}
		
	#	debug($s_action);
		
		$template->assign_vars(array(
			'L_EXPLAIN' => $s_action,
			'S_HIDDEN'	=> '<input type="hidden" name="' . (($mode == 'user_forums') ? 'u' : 'g') . '" value="' . $ids . '"><input type="hidden" name="f" value="' . (($forums) ? implode(', ', $forums) : '') . '">',
		));
	}
	
	function select_type($type, $output)
	{
		global $db;
		
		switch ( $type )
		{
			case 'user':	$params = array(USERS, 'user_id', 'user_name', 'user_id'); break;
			case 'group':	$params = array(GROUPS, 'group_id', 'group_name', 'group_order'); break;
			case 'forums':	$params = array(FORUM, 'forum_id', 'forum_name', 'main ASC, forum_order'); break;
		}
		
		$sql = 'SELECT * FROM ' . $params[0] . ' ORDER BY ' . $params[3] . ' ASC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp_data = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		$select = $type;
		
		switch ( $output )
		{
			case 'drop':
				
				$select .= '<select name="group_id">';
				
				foreach ( $tmp_data as $row )
				{
					$select .= '<option value="' . $row[$params[1]] . '">' . $row[$params[2]] . '</option>';
				}
				
				$select .= '</select>';
				
				break;
				
			case 'input':
			
				$select .= '<input type="text" value="">';
				
				break;
				
			case 'select':
			
				foreach ( $tmp_data as $row )
				{
					if ( !$row['type'] )
					{
						$_cat[$row[$params[1]]] = $row;
					}
					else if ( $row['type'] == 1 )
					{
						$_main[$row['main']][$row[$params[1]]] = $row;
					}
					else
					{
						$_sub[$row['main']][$row[$params[1]]] = $row;
					}
				}
			
				foreach ( $_cat as $ckey => $crow )
				{
					$new[] = array($params[1] => $crow[$params[1]], $params[2] => lang($crow[$params[2]]), 'type' => $crow['type']);
			
					if ( isset($_main[$ckey]) )
					{
						foreach ( $_main[$ckey] as $mkey => $mrow )
						{
							$new[] = array($params[1] => $mrow[$params[1]], $params[2] => lang($mrow[$params[2]]), 'type' => $mrow['type']);
							
							if ( isset($_sub[$mkey]) )
							{
								foreach ( $_sub[$mkey] as $skey => $srow )
								{
									$new[] = array($params[1] => $srow[$params[1]], $params[2] => lang($srow[$params[2]]), 'type' => $srow['type']);
								}
							}
						}
					}
				}
				
				$select .= '<select name="forum_id[]" multiple="multiple" size="10">';
				
				foreach ( $new as $row )
				{
					$select .= '<option value="' . $row[$params[1]] . '">' . ((!$row['type']) ? '' : (($row['type'] == 1) ? '&nbsp; &nbsp;' : '&nbsp; &nbsp;&nbsp; &nbsp;')) . $row[$params[2]] . '</option>';
				}
				
				$select .= '</select>';
				
				break;
		}
		
		return $select;
	}
	
	if ( $action )
	{
		switch ( $action )
		{
			case 'user_forums':
			case 'group_forums':
			
				$options	= array('f_', 'm_');
				$permission	= ($action == 'user_forums') ? array('user', 'input') : array('group', 'drop');
				$page_title	= ($action == 'user_forums') ? 'acp_user_perm' : 'acp_group_perm';
				$select_ids = ($action == 'user_forums') ? $user_id : $group_id;
				$block	= 'permission';
				
				$forum_id = check_ids($forum_id);
				
				if ( !$select_ids )
				{
					$template->assign_block_vars('display', array());
					
					$template->assign_vars(array(
						'S_SELECT' => select_type($permission[0], $permission[1])
					));
				}
				else if ( $select_ids && !$forum_id )
				{
					$template->assign_block_vars('display', array());
					
					$template->assign_vars(array(
						'S_SELECT' => select_type('forums', 'select'),
						'S_HIDDEN' => '<input type="hidden" name="' . (($action == 'user_forums') ? 'user_id' : 'group_id') . '" value="' . $select_ids . '">',
					));
				}
				else if ( $select_ids && $forum_id )
				{
					$template->assign_block_vars($block, array());
				#	debug($forum_id, '$forum_id');
				#	debug(check_ids($forum_id));
					construct_permission_box($select_ids, $forum_id, $action, $block, $options, $options[0]);
				}
				
				break;
			
			case 'group_perm':
			case 'user_perm':
		
				$options = array('a_', 'm_', 'u_', 'd_', 'g_');
				$permission	= ($action == 'user_perm') ? array('user', 'input') : array('group', 'drop');
				$page_title	= ($action == 'user_perm') ? 'acp_user_perm' : 'acp_group_perm';
				$select_ids = ($action == 'user_perm') ? $user_id : $group_id;
				$block	= 'auth_show';
				
				if ( !$select_ids )
				{
					$template->assign_block_vars('display', array());
					$template->assign_vars(array('S_SELECT' => select_type($permission[0], $permission[1])));
				}
				else if ( $select_ids )
				{
					$template->assign_block_vars($block, array());
					construct_permission_box($select_ids, 0, $action, $block, $options, 'a_');
				}
				
				break;
		}
	}
	
	if ( $update )
	{
	#	debug($_POST, '$_POST');
	#	debug($_POST['f'], 'forum_id');
	}
}

$template->pparse('body');

include('./page_footer_admin.php');

?>