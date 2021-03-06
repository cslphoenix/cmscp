<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_PERMISSION',
		'CAT'		=> 'PERMISSION',
		'MODES'		=> array(
			'FORUMS_ALL'			=> array('TITLE' => 'ACP_FORUMS_ALL'),			// foren	-> users/groups	-> f_ m_
			'FORUMS_MOD'			=> array('TITLE' => 'ACP_FORUMS_MOD'),			// foren	-> users/groups	-> m_ f_
			'FORUMS_GROUP'			=> array('TITLE' => 'ACP_FORUMS_GROUP'),		// group	-> f_ m_
			'FORUMS_USER'			=> array('TITLE' => 'ACP_FORUMS_USER'),			// user		-> f_ m_

			'PERMISSION_ADMIN'		=> array('TITLE' => 'ACP_PERMISSION_ADMIN'),	// users/groups -> a_
			'PERMISSION_DOWNLOAD'	=> array('TITLE' => 'ACP_PERMISSION_DOWNLOAD'),	// users/groups -> d_
			'PERMISSION_GALLERY'	=> array('TITLE' => 'ACP_PERMISSION_GALLERY'),	// users/groups -> g_
			'PERMISSION_MOD'		=> array('TITLE' => 'ACP_PERMISSION_MOD'),		// users/groups -> m_
			'PERMISSION_USERS'		=> array('TITLE' => 'ACP_PERMISSION_USERS'),	// users/groups	-> u_

			'PERMISSION_GROUP'		=> array('TITLE' => 'ACP_PERMISSION_GROUP'),	// group	-> a_ m_ u_ d_ g_
			'PERMISSION_USER'		=> array('TITLE' => 'ACP_PERMISSION_USER'),		// user		-> a_ m_ u_ d_ g_

			'SHOW_ADMIN'			=> array('TITLE' => 'ACP_SHOW_ADMIN'),			// users/groups -> a_
			'SHOW_FORUM'			=> array('TITLE' => 'ACP_SHOW_FORUM'),			// users/groups -> f_
		#	'SHOW_GROUP'			=> array('TITLE' => 'ACP_SHOW_GROUP'),			// users/groups	-> a_ m_ u_ d_ g_
		)
	);
}
else
{
	define('IN_CMS', true);

	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	$update = ( isset($_POST['update']) ) ? true : false;
	$delete = ( isset($_POST['submit_delete']) ) ? true : false;

	$current = 'ACP_PERMISSION';

	include('./pagestart.php');

	add_lang(array('permission', 'labels'));
	acl_auth(array('A_AUTH_ADMIN', 'A_AUTH_DOWNLOAD', 'A_AUTH_FORMS', 'A_AUTH_GALLERY', 'A_AUTH_MODERATOR', 'A_AUTH_USER'));

	$error	= '';
	$index	= '';
	$fields = '';

	$log	= SECTION_PERMISSION;
    $file	= basename(__FILE__) . $iadds;

	$data	= request('id', INT);
	$start	= request('start', INT);
	$action	= request('action', TYP);
	$type	= request('type', TYP);
	$mode	= request('mode', TYP);

	$all_forums = request('all_forums', TYP);
	$all_groups = request('all_groups', TYP);
	$all_users	= request('all_users', TYP);

	$ug_type	= request('ug_type', TYP);

	$forum_id	= request('forum_id', ARY, INT);
	$ug_id		= request('ug_id', ARY, INT);

	$user_id	= request('user_id', INT);
	$user_name	= request('user_name', TXT);
	$user_names	= request('user_names', ARY, TXT);
	
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);
	
	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;

	$template->set_filenames(array('body' => "style/$current.tpl"));

	function construct_show_box($ug_ids, $forum_ids, $options, $type)
	{
		global $db, $lang, $template, $fields, $action;
		global $ug_type, $mode, $file;
		global $userauth;
		
		$type = ($mode ? $mode : $options[0]);

		$acl_label		= acl_label($type);
		$acl_auth_group = acl_auth_group($type);
		$acl_field_id	= acl_field($type, '');
		$acl_field_name = acl_field($type, 'name');
		$acl_label_ids	= array_keys($acl_label);
		$acl_label_data	= acl_label_data($acl_label_ids);

		switch ( $ug_type )
		{
			case 'group':

				$access_action	= array(ACL_GROUPS, GROUPS, 'group_id', 'group_name', 'group_order');

				$sql = "SELECT u.user_id, u.user_name, u.user_color, l.type_id as group_id, u.user_founder FROM " . LISTS . " l
							LEFT JOIN " . USERS . " u ON l.user_id = u.user_id
						WHERE l.type = " . TYPE_GROUP . " AND l.type_id IN (" . (is_array($ug_ids) ? implode(', ', $ug_ids) : $ug_ids) . ") ORDER BY u.user_name";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
					$row['user_color'] = ($row['user_color'] == '#FFFFFF') ? '#000000' : $row['user_color'];
					$temp_info[$row['user_id']] = array('name' => $row['user_name'], 'color' => $row['user_color']);
					$temp_user[$row['group_id']][$row['user_id']] = $row['user_founder'];
					$info[$row['group_id']][] = $row['user_id'];
					$u_founder[$row['user_id']] = $row['user_founder'];
				}
				$db->sql_freeresult($result);

				break;

			case 'user':

				$access_action = array(ACL_USERS, USERS, 'user_id', 'user_name', 'user_name', 'user_founder');

				$sql = "SELECT type_id as group_id, user_id FROM " . LISTS . "
							WHERE type = " . TYPE_GROUP . " AND user_pending = 0 AND user_id IN (" . (is_array($ug_ids) ? implode(', ', $ug_ids) : $ug_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
					$group_ids[] = $row['group_id'];
					$info[$row['user_id']][] = $row['group_id'];
				}
				$db->sql_freeresult($result);

				$group_ids = array_unique($group_ids);

				$sql = "SELECT group_id, group_name, group_color FROM " . GROUPS . "
							WHERE group_id IN (" . (is_array($group_ids) ? implode(', ', $group_ids) : $group_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
					$row['group_color'] = ($row['group_color'] == '#FFFFFF') ? '#000000' : $row['group_color'];
					$temp_info[$row['group_id']] = array('name' => $row['group_name'], 'color' => $row['group_color']);
				}
				$db->sql_freeresult($result);

				$sql = "SELECT aclg.* FROM " . ACL_GROUPS . " aclg
							LEFT JOIN " . GROUPS . " g on g.group_id = aclg.group_id
						WHERE aclg.group_id IN (" . (is_array($group_ids) ? implode(', ', $group_ids) : $group_ids) . ") AND forum_id IN (" . (is_array($forum_ids) ? implode(', ', $forum_ids) : $forum_ids) . ")
							ORDER BY g.group_order ASC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $rows = $db->sql_fetchrow($result) )
				{
					if ( $rows['label_id'] != 0 )
					{
						if ( isset($acl_label_data[$rows['label_id']]) )
						{
							foreach ( $acl_label_data[$rows['label_id']] as $key => $row )
							{
								$access_group[$rows['forum_id']][$rows['group_id']][$key] = $row;
							}
						}
					}

					if ( $rows['auth_option_id'] != 0 )
					{
						$access_group[$rows['forum_id']][$rows['group_id']][$acl_field_name[$rows['auth_option_id']]] = $rows['auth_value'];
					}
				}
				$db->sql_freeresult($result);

				if ( !isset($access_group) )
				{
					foreach ( $forum_ids as $forum_id )
					{
						foreach ( $group_ids as $group_id )
						{
							foreach ( $acl_field_name as $f_name )
							{
								$access_group[$forum_id][$group_id][$f_name] = 0;
							}
						}
					}
				}
				break;
		}

		$sql = "SELECT $access_action[2] as ugid, $access_action[3] as name" . (isset($access_action[5]) ? ", $access_action[5]" : '') . " FROM $access_action[1] WHERE $access_action[2] IN (" . (is_array($ug_ids) ? implode(', ', $ug_ids) : $ug_ids) . ") ORDER BY $access_action[4] ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}

		$ugs = array();

		while ( $row = $db->sql_fetchrow($result) )
		{
			$ugs[$row['ugid']] = $row['name'];

			if ( isset($access_action[5]) )
			{
				$u_founder[$row['ugid']] = $row[$access_action[5]];
			}
		}
		
		$access = access($access_action[0], array($access_action[2], $ug_ids), $forum_ids, $acl_label_data, $acl_field_name);
		
		$grp_info = $u_a = array();
		
		if ( $access )
		{
			foreach ( $access as $forum_id => $users )
			{
				foreach ( $users as $u_id => $u_data )
				{
					$forum_access[$forum_id][] = $u_id;

					foreach ( $u_data as $r_field => $r_value )
					{
						$main	= ( sizeof($forum_ids) > 1 && sizeof($ug_ids) < 2 ) ? $u_id : $forum_id;
						$parent	= ( sizeof($forum_ids) > 1 && sizeof($ug_ids) < 2 ) ? $forum_id : $u_id;

						if ( isset($urs_access[$forum_id][$u_id][$r_field]) )
						{
							if ( $urs_access[$forum_id][$u_id][$r_field] == 1 )
							{
								if ( $r_value == 1 )
								{
									$urs_access[$forum_id][$u_id][$r_field] = '1';
								}
								else if ( $r_value == -1 )
								{
									$urs_access[$forum_id][$u_id][$r_field] = '-1';
								}
								else
								{
									$urs_access[$forum_id][$u_id][$r_field] = '1';
								}
							}

							if ( $urs_access[$forum_id][$u_id][$r_field] == -1 )
							{
								if ( $r_value == 1 )
								{
									$urs_access[$forum_id][$u_id][$r_field] = '-1';
								}
								else if ( $r_value == -1 )
								{
									$urs_access[$forum_id][$u_id][$r_field] = '-1';
								}
								else
								{
									$urs_access[$forum_id][$u_id][$r_field] = '-1';
								}
							}

							if ( $urs_access[$forum_id][$u_id][$r_field] == 0 )
							{
								if ( $r_value == 1 )
								{
									$urs_access[$forum_id][$u_id][$r_field] = '1';
								}
								else if ( $r_value == -1 )
								{
									$urs_access[$forum_id][$u_id][$r_field] = '-1';
								}
								else
								{
									$urs_access[$forum_id][$u_id][$r_field] = '0';
								}
							}
						}
						else
						{
							$urs_access[$forum_id][$u_id][$r_field] = $r_value;
						}
						
						if ( $ug_type == 'user' )
						{
							if ( isset($u_founder[$u_id]) && $u_founder[$u_id] == 1 )
							{
								$urs_access[$forum_id][$u_id][$r_field] = '1';
								$grp_info[$main][$parent][$r_field][$lang['PERMISSION_FOUNDER']] = $lang['PERM_1'];
							}

							if ( $r_value != 0 )
							{
								$grp_info[$main][$parent][$r_field][$lang['PERMISSION_USER']] = $lang['PERM_' . $r_value];
							}
						}
						else
						{
							if ( $r_value != 0 )
							{
								$grp_info[$forum_id][$u_id][$r_field][$lang['PERMISSION_GROUP']] = $lang['PERM_' . $r_value];
							}
						}
					}
				}
			}
		}
		
		$overview = $cnt_f = $cnt_g = '';
				
		foreach ( $forum_access as $a_id => $a_row )
		{
			foreach ( $a_row as $row_id )
			{
				$cnt_f += 1;
				
				foreach ( $info as $key => $value )
				{
					$cnt_g +=1;

					if ( $row_id == $key )
					{
						$overview[$a_id][$row_id] = $value;
					}
					
					if ( $ug_type == 'group' && $action == 'show_forum' )
					{
						$overview[$a_id][$row_id] = array();
					}
				}
			}
		}
		
		if ( !$overview )
		{
			foreach ( $forum_access as $a_id => $a_row )
			{
				$cnt_f += 1;
				
				foreach ( $a_row as $row_id )
				{
					$cnt_g +=1;
					
					foreach ( $info as $key => $value )
					{
						$overview[$a_id][$row_id] = $value;
					}
				}
			}
		}
		
	#	debug($info, '$info');
	#	debug($forum_access, '$forum_access');
	#	debug($overview, '$overview');
		
		if ( $overview && isset($access_group) )
		{
			foreach ( $overview as $forum_id => $users )
			{
				foreach ( $users as $user_id => $groups )
				{
					if ( $groups )
					{
						foreach ( $groups as $group_id )
						{
							if ( isset($access_group[$forum_id][$group_id]) )
							{
								$main	= ( sizeof($forum_ids) > 1 && sizeof($ug_ids) < 2 ) ? $user_id : $forum_id;
								$parent	= ( sizeof($forum_ids) > 1 && sizeof($ug_ids) < 2 ) ? $forum_id : $user_id;
								
								foreach ( $access_group[$forum_id][$group_id] as $r_field => $r_value )
								{
									if ( isset($grp_access[$forum_id][$user_id][$r_field]) )
									{
										if ( $grp_access[$forum_id][$user_id][$r_field] == 1 )
										{
											if ( $r_value == 1 )
											{
												$grp_access[$forum_id][$user_id][$r_field] = '1';
											}
											else if ( $r_value == -1 )
											{
												$grp_access[$forum_id][$user_id][$r_field] = '-1';
											}
											else
											{
												$grp_access[$forum_id][$user_id][$r_field] = '1';
											}
										}
	
										if ( $grp_access[$forum_id][$user_id][$r_field] == -1 )
										{
											if ( $r_value == 1 )
											{
												$grp_access[$forum_id][$user_id][$r_field] = '-1';
											}
											else if ( $r_value == -1 )
											{
												$grp_access[$forum_id][$user_id][$r_field] = '-1';
											}
											else
											{
												$grp_access[$forum_id][$user_id][$r_field] = '-1';
											}
										}
	
										if ( $grp_access[$forum_id][$user_id][$r_field] == 0 )
										{
											if ( $r_value == 1 )
											{
												$grp_access[$forum_id][$user_id][$r_field] = '1';
											}
											else if ( $r_value == -1 )
											{
												$grp_access[$forum_id][$user_id][$r_field] = '-1';
											}
											else
											{
												$grp_access[$forum_id][$user_id][$r_field] = '0';
											}
										}
									}
									else
									{
										$grp_access[$forum_id][$user_id][$r_field] = $r_value;
									}
	
									if ( $u_founder[$user_id] )
									{
										$grp_access[$forum_id][$user_id][$r_field] = '1';
										$grp_info[$main][$parent][$r_field][$lang['PERMISSION_FOUNDER']] = $lang['PERM_1'];
									}
	
									if ( $r_value != 0 )
									{
										$grp_info[$main][$parent][$r_field][$temp_info[$group_id]['name']] = $lang['PERM_' . $r_value];
									}
								}
							}
						}
					}
				}
			}
		}
		
		foreach ( $acl_field_name as $f_name )
		{
			if ( $overview )
			{
				foreach ( $overview as $forum_id => $users )
				{
					foreach ( $users as $user_id => $user_groups )
					{
						$main	= ( $cnt_f > 1 && $cnt_g < 2 ) ? $user_id : $forum_id;
						$parent	= ( $cnt_f > 1 && $cnt_g < 2 ) ? $forum_id : $user_id;
						
						if ( isset($urs_access[$forum_id][$user_id][$f_name]) )
						{
							if ( $urs_access[$forum_id][$user_id][$f_name] == '1' )
							{
								if ( isset($u_a[$main][$parent][$f_name]) )
								{
									if ( $u_a[$main][$parent][$f_name] == '-1' )
									{
										$u_a[$main][$parent][$f_name] = '-1';
									}
									else if ( $u_a[$main][$parent][$f_name] == '0' )
									{
										$u_a[$main][$parent][$f_name] = '1';
									}
								}
								else
								{
									$u_a[$main][$parent][$f_name] = '1';
								}
							}
							else if ( $urs_access[$forum_id][$user_id][$f_name] == '0' )
							{
								if ( isset($u_a[$main][$parent][$f_name]) )
								{
									if ( $u_a[$main][$parent][$f_name] == '-1' )
									{
										$u_a[$main][$parent][$f_name] = '-1';
									}
									else if ( $u_a[$main][$parent][$f_name] == '1' )
									{
										$u_a[$main][$parent][$f_name] = '1';
									}
								}
								else
								{
									$u_a[$main][$parent][$f_name] = '0';
								}
							}
							else
							{
								$u_a[$main][$parent][$f_name] = '-1';
							}
						}
	
						if ( isset($grp_access[$forum_id][$user_id][$f_name]) )
						{
							if ( $grp_access[$forum_id][$user_id][$f_name] == '1' )
							{
								if ( isset($u_a[$main][$parent][$f_name]) )
								{
									if ( $u_a[$main][$parent][$f_name] == '-1' )
									{
										$u_a[$main][$parent][$f_name] = '-1';
									}
									else if ( $u_a[$main][$parent][$f_name] == '0' )
									{
										$u_a[$main][$parent][$f_name] = '1';
									}
								}
								else
								{
									$u_a[$main][$parent][$f_name] = '1';
								}
							}
							else if ( $grp_access[$forum_id][$user_id][$f_name] == '0' )
							{
								if ( isset($u_a[$main][$parent][$f_name]) )
								{
									if ( $u_a[$main][$parent][$f_name] == '-1' )
									{
										$u_a[$main][$parent][$f_name] = '-1';
									}
									else if ( $u_a[$main][$parent][$f_name] == '1' )
									{
										$u_a[$main][$parent][$f_name] = '1';
									}
								}
								else
								{
									$u_a[$main][$parent][$f_name] = '0';
								}
							}
							else
							{
								$u_a[$main][$parent][$f_name] = '-1';
							}
						}
					}
				}
			}
		}
		
	#	debug($access, 'access');
	#	debug($urs_access, '$urs_access');
	#	debug($grp_access, '$grp_access');
	#	debug($u_a, '$u_a');

		switch ( $action )
		{
			case strpos($action, 'forum') !== false:	$_action = 'forum'; break;
			case strpos($action, 'show') !== false:		$_action = 'show'; break;
		}

		switch ( $_action )
		{
			case 'show':

				$forums[0] = langs($type . strtoupper($_action));
				
				break;

			case 'forum':

				$forum_data = $forums = array();

				$sql = 'SELECT forum_id, forum_name, type, main FROM ' . FORUM . '
							WHERE type != 0 AND forum_id IN (' . (is_array($forum_ids) ? implode(', ', $forum_ids) : $forum_ids) . ')
						ORDER BY main ASC, forum_order ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
					$forum_data[$row['forum_id']] = $row;
				}
				$db->sql_freeresult($result);

				/* Foren werden sortiert so wie sie in der Struktur angezeigt werden */
				foreach ( $forum_data as $f_key => $f_row )
				{
					if ( $f_row['type'] == 1 )
					{
						$_main[$f_row['forum_id']] = $f_row['forum_name'];
					}
					else
					{
						$_subs[$f_row['main']][$f_row['forum_id']] = $f_row['forum_name'];
					}
				}

				if ( isset($_main) )
				{
					foreach ( $_main as $forum_id => $forum_name )
					{
						$forums[$forum_id] = $forum_name;

						if ( isset($_subs[$forum_id]) )
						{
							foreach ( $_subs[$forum_id] as $sub_id => $sub_name )
							{
								$forums[$sub_id] = $sub_name;
							}
						}
					}
				}

				if ( isset($_subs) )
				{
					foreach ( $_subs as $forum_id => $sub_forum )
					{
						foreach ( $sub_forum as  $sub_id => $sub_name )
						{
							$forums[$sub_id] = $sub_name;
						}
					}
				}

				break;
		}

		$s_options = '';

		if ( is_array($options) && count($options) > 1 )
		{
			/* änderung auf oki button drücken zum wechsel */
			$s_options .= '<select name="mode" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';

			foreach ( $options as $opts )
			{
				$selected = ( $opts == $type ) ? ' selected="selected"' : '';
				$s_options .= '<option value="' . $opts . '"' . $selected . '>' . langs("{$opts}right") . '</option>';
			}

			$s_options .= '</select>';
		}
		
		if ( $overview )
		{
			foreach ( $overview as $forum_id => $value )
			{
				/*	value = user_ids / group_ids	*/
				foreach ( $value as $value_id => $value_value )
				{
					$l_main		= ( $cnt_f > 1 && $cnt_g < 2 ) ? $value_id : $forum_id;
					$l_parent	= ( $cnt_f > 1 && $cnt_g < 2 ) ? $forum_id : $value_id;
					$l_info		= ( $cnt_f > 1 && $cnt_g < 2 ) ? true : false;
					
					foreach ( $value_value as $info_id )
					{
						$legend[$l_main][$l_parent][] = href('a_style', $file, array('ug_type' => ($ug_type == 'user' ? 'group' : 'user'), 'ug_id' => $info_id), $temp_info[$info_id]['color'], $temp_info[$info_id]['name']);
					}

					$legend[$l_main][$l_parent] =  (isset($legend[$l_main][$l_parent]) ? (($ug_type == 'user' ? sprintf('<b>%s</b>', $lang['GROUPS']) : sprintf('<b>%s</b>', $lang['USERS'])) . ': ') . implode(', ', $legend[$l_main][$l_parent]) : false);
				}
			}
		}
		
		$main	= ( sizeof($forums) > 1 && sizeof($ugs) < 2 ) ? $ugs : $forums;
		$parent	= ( sizeof($forums) > 1 && sizeof($ugs) < 2 ) ? $forums : $ugs;
		
		foreach ( $main as $m_id => $m_data )
		{
			$template->assign_block_vars('view.row', array(
				'INFO' => ( $l_info ) ? $legend[$m_id] : '',
				'NAME' => $m_data . ' :: '  . $m_id,
			));

			foreach ( $parent as $p_id => $p_data )
			{
				$row_switch = sprintf('s%sg%s', $m_id, $p_id);

				$test_auth[] = 'auths' . $m_id . $p_id;

				$template->assign_block_vars('view.row.parent', array(
					'NAME'		=> $p_data . ' :: '  . $p_id,
					'TOGGLE'	=> $row_switch,
				#	'LABEL'		=> '<label for="' . $row_switch . '">' . $lang['LABEL'] . '</label>',
					'AUTHS'		=> 'auths' . $m_id . $p_id,
					'INFO'		=> ( !$l_info ) ? ((@$legend[$m_id][$p_id]) ? @$legend[$m_id][$p_id] : (($ug_type == 'user') ? $lang['LEGEND_NONE_USER'] : $lang['LEGEND_NONE_GROUP'])) : '',
				));

				foreach ( $acl_auth_group as $cat => $rows )
				{
					$template->assign_block_vars('view.row.parent.cats', array(
						'CAT'	=> $cat,
						'NAME'	=> $lang['TABS'][$type][$cat],
						'OPTIONS' => "options{$m_id}{$p_id}{$cat}",
					));

					$test_options[$cat][] = "options{$m_id}{$p_id}{$cat}";

					foreach ( $rows as $row )
					{
						$row_format = sprintf('%s%s[%s][%s]', $type, $m_id, $p_id, $row);

						if ( isset($grp_info[$m_id][$p_id][$row]) && is_array($grp_info[$m_id][$p_id][$row]) )
						{
							foreach ( $grp_info[$m_id][$p_id][$row] as $g_id => $g_value )
							{
								$grp_info_grp[$m_id][$p_id][$row][$g_id] = $g_id . ': ' .$g_value;
							}

							$grp_info_grp[$m_id][$p_id][$row] = implode('<br />', $grp_info_grp[$m_id][$p_id][$row]);
						}

						$template->assign_block_vars('view.row.parent.cats.auths', array(
							'OPT_NAME'	=> langs($row),
							'OPT_INFO'	=> isset($grp_info_grp[$m_id][$p_id][$row]) ? img('i_icon', 'icon_details', $grp_info_grp[$m_id][$p_id][$row]) : '',
							'CSS_YES'	=> ( @$u_a[$m_id][$p_id][$row] == '1' ) ? 'bggreen' : '',
							'CSS_NO'	=> ( @$u_a[$m_id][$p_id][$row] != '1' ) ? 'bgred' : '',
						));
					}
				}
			}
		}

		$fields .= '<input type="hidden" name="type" value="' .  ($mode ? $mode : $type) . '" />';
		$fields .= '<input type="hidden" name="ug_type" value="' . $ug_type . '" />';

		if ( is_array($ug_ids) )
		{
			foreach ( $ug_ids as $ug )
			{
				$fields .= '<input type="hidden" name="ug_id[]" value="' . $ug . '">';
			}
		}

		if ( is_array($forum_ids) )
		{
			foreach ( $forum_ids as $forum )
			{
				$fields .= '<input type="hidden" name="forum_id[]" value="' . $forum . '">';
			}
		}

		$template->assign_vars(array(
	#		'L_LABEL'	=> $lang['label'],
	#		'GROUPS_USERS'	=> ($ug_type == 'user' ? $lang['groups'] : $lang['users']) . ': ' . $legend,

			'BLUBB'		=> implode("'); toggle('", $test_auth),

			'S_OPTIONS' => $s_options,
			'S_HIDDEN'	=> $fields,
		));
	}

	function construct_permission_box($ug_ids, $forum_ids, $options, $type)
	{
		global $db, $lang, $template, $fields, $action;
		global $ug_type, $mode, $file, $ug_id;

		$type = ($mode ? $mode : $options[0]);

		$acl_label		= acl_label($type);
		$acl_auth_group = acl_auth_group($type);
		$acl_field_id	= acl_field($type, '');
		$acl_field_name = acl_field($type, 'name');
		$acl_label_ids	= array_keys($acl_label);
		$acl_label_data	= acl_label_data($acl_label_ids);

		foreach ( $acl_label as $rows )
		{
			$template->assign_block_vars('permission.settings', array(
				'ID'		=> $rows['label_id'],
				'OPTION'	=> $rows['label_name'],
			));

			if ( isset($acl_label_data[$rows['label_id']]) )
			{
				foreach ( $acl_label_data[$rows['label_id']] as $acl_field => $value )
				{
					$template->assign_block_vars('permission.settings.row', array(
						'FIELD' => $acl_field,
						'VALUE' => ($value == 1) ? '_y' : (($value == -1) ? '_n' : '_u'),
					));
				}
			}
			else
			{
				foreach ( $acl_field_id as $acl_field => $value )
				{
					$template->assign_block_vars('permission.settings.row', array(
						'FIELD' => $value,
						'VALUE' => '_u',
					));
				}
			}
		}

		switch ( $ug_type )
		{
			case 'group':	$access_action	= array(ACL_GROUPS, GROUPS, 'group_id', 'group_name', 'group_order');	break;
			case 'user':	$access_action	= array(ACL_USERS, USERS, 'user_id', 'user_name', 'user_name');	break;
		}

		$sql = "SELECT $access_action[2] as ugid, $access_action[3] as name FROM $access_action[1] WHERE $access_action[2] IN (" . (is_array($ug_ids) ? implode(', ', $ug_ids) : $ug_ids) . ") ORDER BY $access_action[4] ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}

		$ugs = array();

		while ( $row = $db->sql_fetchrow($result) )
		{
			$ugs[$row['ugid']] = $row['name'];
		}
		
		$ugs_ids = array_keys($ugs);
		
	#	$access			= access($access_action[0], array($access_action[2], $ug_ids), $forum_ids, $acl_label_data, $acl_field_name);
		$access			= access($access_action[0], array($access_action[2], $ugs_ids), $forum_ids, $acl_label_data, $acl_field_name);
		$access_label	= access_label($access_action[0], array($access_action[2], $ug_ids), $forum_ids, $acl_label_ids);

		$_action = ( strpos($action, 'permission_') !== false ) ? 'permission' : 'forums';

		switch ( $_action )
		{
			case 'permission':

				$forums[0] = langs($type . strtoupper($_action));

				break;

			case 'forums':

				$forum_data = $forums = array();

			#	$sql = 'SELECT forum_id as ugid, type, main, forum_name as name FROM ' . FORUM . '
			#				WHERE type != 0 AND forum_id IN (' . (is_array($forum_ids) ? implode(', ', $forum_ids) : $forum_ids) . ')
			#			ORDER BY main ASC, forum_order ASC';
				$sql = 'SELECT forum_id, forum_name, type, main FROM ' . FORUM . '
							WHERE type != 0 AND forum_id IN (' . (is_array($forum_ids) ? implode(', ', $forum_ids) : $forum_ids) . ')
						ORDER BY main ASC, forum_order ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
			#		$forum_data[$row['ugid']] = $row['name'];
			#		$forum_sort[] = $row;
					$forum_data[$row['forum_id']] = $row;
				}
				$db->sql_freeresult($result);

			#	foreach ( $forum_data as $f_key => $f_row )
			#	{
			#		if ( in_array($f_key, $forum_ids) )
			#		{
			#			$forums[$f_key] = $f_row;
			#		}
			#	}
			
				foreach ( $forum_data as $f_key => $f_row )
				{
					if ( $f_row['type'] == 1 )
					{
						$_main[$f_row['forum_id']] = $f_row['forum_name'];
					}
					else
					{
						$_subs[$f_row['main']][$f_row['forum_id']] = $f_row['forum_name'];
					}
				}
				
				if ( isset($_main) )
				{
					foreach ( $_main as $forum_id => $forum_name )
					{
						$forums[$forum_id] = $forum_name;

						if ( isset($_subs[$forum_id]) )
						{
							foreach ( $_subs[$forum_id] as $sub_id => $sub_name )
							{
								$forums[$sub_id] = $sub_name;
							}
						}
					}
				}

				if ( isset($_subs) )
				{
					foreach ( $_subs as $forum_id => $sub_forum )
					{
						foreach ( $sub_forum as  $sub_id => $sub_name )
						{
							$forums[$sub_id] = $sub_name;
						}
					}
				}

				break;
		}

		$s_options = '';
		
	#	if ( is_array($options) && count($options) > 1 )
	#	{
	#		/* änderung auf oki button drücken zum wechsel */
	#		$s_options .= '<select name="mode" onchange="if (this.options[this.selectedIndex].value != \'\') this.form.submit();">';
	#		
	#		foreach ( $options as $opts )
	#		{
	#			$selected = ( $opts == $type ) ? ' selected="selected"' : '';
	#			$s_options .= '<option value="' . $opts . '"' . $selected . ' title="">' . langs("{$opts}right") . '</option>';
	#		}
	#		$s_options .= '</select>';
	#	}
		
		$sort = '';
		
		debug($options, '$options');
		
		if ( count($options) > 1 )
		{
			
		foreach ( $options as $opts )
		{
			$sort[] = href((($type == $opts) ? 'AHREF_TXT_B' : 'AHREF_TXT'), $file, array('ug_type' => $ug_type, 'action' => 'permission_group', 'mode' => $opts, 'ug_id' => $ug_id), langs("{$opts}right"), langs("{$opts}right"));
		}
				
		$sort = implode(', ', $sort);
		}

		/* SPRACHFILES ANPASSEN UND ERSTELLEN !!! */
		$add_lang = array('label_id' => 0, 'label_name' => $lang['NO_SELECT'], 'label_desc' => $lang['NO_SELECT_DESC'], 'label_type' => $type);
		array_unshift($acl_label, $add_lang);

	#	$add_lang = array('label_id' => 0, 'label_name' => 'no select', 'label_type' => $type, 'label_desc' => 'reset');
	#	array_unshift($acl_label, $add_lang);

		$main	= ( sizeof($forums) > 1 && sizeof($ugs) < 2 ) ? $ugs : $forums;
		$parent	= ( sizeof($forums) > 1 && sizeof($ugs) < 2 ) ? $forums : $ugs;
		
		foreach ( $main as $m_id => $m_data )
		{
			$template->assign_block_vars('permission.row', array('NAME' => $m_data));
			
			foreach ( $parent as $p_id => $p_data )
			{
				$simple_auth = '<select name="' . sprintf('sa[%s_%s]', $m_id, $p_id) . '" id="' . sprintf('s%sg%s', $m_id, $p_id) . '" onchange="set_permission(this.options[selectedIndex].value, \'' . $m_id . '\', \'' . $p_id . '\');">';

				foreach ( $acl_label as $labels )
				{
					$selected = ( (isset($access_label[$m_id][$p_id]) ? @$access_label[$m_id][$p_id] : @$access_label[$p_id][$m_id]) == $labels['label_id'] ) ? ' selected="selected"' : '';
					$simple_auth .= '<option title="' . langs($labels['label_desc']) . '" value="' . $labels['label_id'] . '"' . $selected . '>' . langs($labels['label_name']) . '</option>';
				}

				$simple_auth .= '</select>';

				$row_switch = sprintf('s%sg%s', $m_id, $p_id);

				$template->assign_block_vars('permission.row.parent', array(
					'NAME'		=> $p_data,
					'TOGGLE'	=> $row_switch,
					'LABEL'		=> '<label for="' . $row_switch . '">' . $lang['LABEL'] . '</label>',
					'SIMPLE'	=> $simple_auth,
					'AUTHS'		=> 'auths' . $m_id . $p_id,
				));

				foreach ( $acl_auth_group as $cat => $rows )
				{
					$template->assign_block_vars('permission.row.parent.cats', array(
						'CAT'	=> $cat,
						'NAME'	=> $lang['TABS'][$type][$cat],
						'OPTIONS' => "options{$m_id}{$p_id}{$cat}",
					));

					foreach ( $rows as $row )
					{
						$row_format = sprintf('%s%s[%s][%s]', $type, $m_id, $p_id, $row);
						
						$template->assign_block_vars('permission.row.parent.cats.auths', array(
							'OPT_NAME'	=> langs($row),
							'OPT_YES'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s[%s][%s]_y', $row, $m_id, $p_id) . '" onclick="reset_simpleauth(\'' . $row_switch . '\');" value="1"' . (( @$access[$m_id][$p_id][$row] == 1 ) ? ' checked="checked"' : '') . ' /></label>',
							'OPT_UNSET'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s[%s][%s]_u', $row, $m_id, $p_id) . '" onclick="reset_simpleauth(\'' . $row_switch . '\');" value="0"' . (( @$access[$m_id][$p_id][$row] == 0 ) ? ' checked="checked"' : '') . ' /></label>',
							'OPT_NEVER'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s[%s][%s]_n', $row, $m_id, $p_id) . '" onclick="reset_simpleauth(\'' . $row_switch . '\');" value="-1"' . (( @$access[$m_id][$p_id][$row] == -1 ) ? ' checked="checked"' : '') . ' /></label>',
						));
					}
				}
			}
		}

		$fields .= '<input type="hidden" name="type" value="' .  ($mode ? $mode : $type) . '" />';
		$fields .= '<input type="hidden" name="ug_type" value="' . $ug_type . '" />';

		if ( is_array($ug_ids) )
		{
			foreach ( $ug_ids as $ug )
			{
				$fields .= '<input type="hidden" name="ug_id[]" value="' . $ug . '">';
			}
		}

		if ( is_array($forum_ids) )
		{
			foreach ( $forum_ids as $forum )
			{
				$fields .= '<input type="hidden" name="forum_id[]" value="' . $forum . '">';
			}
		}

		$template->assign_vars(array(
	#		'L_LABEL'	=> $lang['label'],
			'S_OPTIONS' => $s_options,
			'L_SWITCH'	=> $sort ? sprintf($lang['STF_COMMON_SWITCH'], $sort) : '',
			'S_HIDDEN'	=> $fields,
		));
	}

	function select_type($stype, $output)
	{
		global $db;
		global $ug_id, $forum_id;
		global $template;
		global $label_ids;
		global $options;
		global $lang;
		
		$label_ids	= array_keys(acl_label((is_array($options) ? $options[0] : $options)));
		$acl_field	= acl_field((is_array($options) ? $options[0] : $options), '');

		switch ( $stype )
		{
			case 'user':

				$params = array('user_id', 'user_name');

				$sql = 'SELECT * FROM ' . USERS . ' ORDER BY user_id ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$sql_data = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);

				break;

			case 'group':

				$params = array('group_id', 'group_name');

				$sql = 'SELECT * FROM ' . GROUPS . ' ORDER BY group_order ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$sql_data = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);

				break;

			case 'forums':

				$params = array('forum_id', 'forum_name');

				$sql = 'SELECT * FROM ' . FORUM . ' ORDER BY main ASC, forum_order ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$sql_data = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);

				break;

			case 'usergroups':

				$sql_data_group = $sql_data_groups = $sql_data_users = array();

				$params = array('group_id', 'group_name', 'user_id', 'user_name');

				/* group start */
				$sql = 'SELECT group_id
							FROM ' . ACL_GROUPS . '
								WHERE forum_id IN (' . (is_array($forum_id) ? implode(', ', $forum_id) : $forum_id) . ')
									AND (label_id IN (' . implode(', ', $label_ids) . ') OR auth_option_id IN (' . implode(', ', $acl_field) . '))
							ORDER BY group_id ASC, forum_id ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
					$sql_data_group[] = $row['group_id'];
				}
				$db->sql_freeresult($result);

				$sql = 'SELECT group_id, group_name, group_order FROM ' . GROUPS . ' ORDER BY group_order ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
					$sql_data_groups[$row['group_id']] = $row;
				}
				$db->sql_freeresult($result);
				/* group end */

				/* user start */
				$sql = 'SELECT acl.user_id, u.user_name, u.user_color
							FROM ' . ACL_USERS . ' acl
								LEFT JOIN ' . USERS . ' u ON u.user_id = acl.user_id
								WHERE forum_id IN (' . (is_array($forum_id) ? implode(', ', $forum_id) : $forum_id) . ')
									AND ( label_id IN (' . implode(', ', $label_ids) . ') OR auth_option_id IN (' . implode(', ', $acl_field) . ') )
							ORDER BY user_id ASC, forum_id ASC';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
					$sql_data_users[$row['user_id']] = $row;
				}
				$db->sql_freeresult($result);
				/* user end */

				$sql_data = array($sql_data_group, $sql_data_groups, $sql_data_users);

				break;
		}

		$s_select = '';

		switch ( $output )
		{
			case 'drop':

				$s_select .= '<select name="ug_id[]">';

				foreach ( $sql_data as $row )
				{
					$s_select .= '<option value="' . $row[$params[0]] . '">' . $row[$params[1]] . '</option>';
				}

				$s_select .= '</select>';

				break;

			case 'input':

				$s_select .= '<input type="text" name="' . $params[1] . '" id="' . $params[1] . '" value="" onkeyup="lookup(this.value, 1, 0);" onblur="fill();" autocomplete="off">';
				$s_select .= '<div class="suggestionsBox" id="suggestions" style="display:none;"><div class="suggestionList" id="autoSuggestionsList"></div></div>';

				break;

			case 'selects':

				list($data_group, $data_groups, $data_users) = $sql_data;

				$group_acl = $group_none = array();

				foreach ( $data_groups as $g_id => $g_row )
				{
					if ( in_array($g_id, array_unique($data_group)) )
					{
						$group_acl[] = $g_row;
					}
					else
					{
						$group_none[] = $g_row;
					}
				}

				$s_user_acl = $s_group_acl = $s_group_none = $s_group_copy = '';

				/* s_user_acl // start */
				$s_user_acl .= '<select name="ug_id[]" multiple="multiple" size="5" style="width: 100%;">';

				foreach ( $data_users as $row )
				{
					$s_user_acl .= '<option value="' . $row[$params[2]] . '">' . $row[$params[3]] . '</option>';
				}

				$s_user_acl .= '</select>';

				foreach ( $data_users as $row )
				{
					$s_user_acl .= '<input type="hidden" name="users[]" value="' . $row[$params[2]] . '">';
				}

				/* s_group_acl // start */
				$s_group_acl .= '<select name="ug_id[]" multiple="multiple" size="5" style="width: 100%;">';

				foreach ( $group_acl as $row )
				{
					$s_group_acl .= '<option value="' . $row[$params[0]] . '">' . $row[$params[1]] . '</option>';
				}

				$s_group_acl .= '</select>';

				foreach ( $group_acl as $row )
				{
					$s_group_acl .= '<input type="hidden" name="groups[]" value="' . $row[$params[0]] . '">';
				}
				
				$s_group_none .= '<select name="ug_id[]" multiple="multiple" size="5" style="width: 100%;">';

				foreach ( $group_none as $row )
				{
					$s_group_none .= '<option value="' . $row[$params[0]] . '">' . $row[$params[1]] . '</option>';
				}

				$s_group_none .= '</select>';
				
				$s_group_copy .= '<select name="copy">';
				$s_group_copy .= '<option value="0">noch keine Funktion hinterlegt</option>';
				$s_group_copy .= '<option value="0">' . $lang['SELECT_COPY'] . '</option>';
				
				foreach ( $group_acl as $row )
				{
					$s_group_copy .= '<option value="' . $row[$params[0]] . '">' . $row[$params[1]] . '</option>';
				}
				
				$s_group_copy .= '</select>&nbsp;';

				$s_select = array($s_user_acl, $s_group_acl, $s_group_none, $s_group_copy);

				break;

			case 'select':

				foreach ( $sql_data as $row )
				{
					if ( !$row['type'] )
					{
						$_cat[$row[$params[0]]] = $row;
					}
					else if ( $row['type'] == 1 )
					{
						$_main[$row['main']][$row[$params[0]]] = $row;
					}
					else
					{
						$_sub[$row['main']][$row[$params[0]]] = $row;
					}
				}

				foreach ( $_cat as $ckey => $crow )
				{
					$new[] = array($params[0] => $crow[$params[0]], $params[1] => langs($crow[$params[1]]), 'type' => $crow['type']);

					if ( isset($_main[$ckey]) )
					{
						foreach ( $_main[$ckey] as $mkey => $mrow )
						{
							$new[] = array($params[0] => $mrow[$params[0]], $params[1] => langs($mrow[$params[1]]), 'type' => $mrow['type']);

							if ( isset($_sub[$mkey]) )
							{
								foreach ( $_sub[$mkey] as $skey => $srow )
								{
									$new[] = array($params[0] => $srow[$params[0]], $params[1] => langs($srow[$params[1]]), 'type' => $srow['type']);
								}
							}
						}
					}
				}

				$s_select .= '<label><input type="checkbox" class="radio" name="all_forums" value="1" />&nbsp;' . $lang['FORUMS_ALL'] . '</label><br />';
				$s_select .= '<select name="forum_id[]" multiple="multiple" size="10">';

				foreach ( $new as $row )
				{
					$s_select .= '<option value="' . $row[$params[0]] . '">' . ((!$row['type']) ? '' : (($row['type'] == 1) ? '&nbsp; &nbsp;' : '&nbsp; &nbsp;&nbsp; &nbsp;')) . $row[$params[1]] . '</option>';
				}
				
				foreach ( $new as $row )
				{
					$s_select .= '<input type="hidden" name="forums[]" value="' . $row[$params[0]] . '">';
				}

				$s_select .= '</select>';

				break;
		}

		return $s_select;
	}

	if ( $action == !$delete )
	{
		switch ( $action )
		{
			case 'show_forum':

				$options	= array('F_', 'M_');
				$permission	= array('usergroups', 'selects');
				$page_title	= 'acp_user_perm';
			#	$forum_id	= check_ids($forum_id);
				
				if ( $forum_id || $all_forums )
				{
					if ( $forum_id )
					{
						$forum_id = check_ids($forum_id);
					}
					else
					{
						$forum_id = request('forums', ARY);
					}
				}

				if ( $all_groups || $all_users )
				{
					if ( $all_groups )
					{
						$ug_id = request('groups', ARY);
					}
					else
					{
						$ug_id = request('users', ARY);
					}
				}

				if ( !$forum_id )
				{
					$template->assign_block_vars('display', array());
					$template->assign_vars(array('S_SELECT' => select_type('forums', 'select')));
				}
				else if ( $forum_id && !$ug_id )
				{
					$template->assign_block_vars('show', array());

					$s_select = select_type($permission[0], $permission[1]);

					if ( is_array($forum_id) )
					{
						foreach ( $forum_id as $forum )
						{
							$fields .= '<input type="hidden" name="forum_id[]" value="' . $forum . '">';
						}
					}

					$template->assign_vars(array(
						'S_USER_UPDATE'		=> $s_select[0],
						'S_USER_CREATE'		=> '',
						'S_GROUP_UPDATE'	=> $s_select[1],
						'S_GROUP_CREATE'	=> $s_select[2],

						'S_HIDDEN'			=> $fields,
					));
				}
				else if ( $forum_id && $ug_id )
				{
					$template->assign_block_vars('view', array());

					construct_show_box($ug_id, $forum_id, $options, ($mode ? $mode : $options[0]));
				}

				break;

			case 'show_admin':
			case 'show_download':
			case 'show_gallery':

				$options	= array(substr(strtoupper($action), strpos($action, '_')+1, 1) . '_');
				$permission	= array('usergroups', 'selects');

				if ( $all_groups || $all_users )
				{
					if ( $all_groups )
					{
						$ug_id = request('groups', ARY);
					}
					else
					{
						$ug_id = request('users', ARY);
					}
				}

				if ( !$ug_id )
				{
					$template->assign_block_vars('show', array());

					$s_select = select_type($permission[0], $permission[1]);

					$template->assign_vars(array(
						'S_USER_UPDATE'		=> $s_select[0],
						'S_USER_CREATE'		=> '',
						'S_GROUP_UPDATE'	=> $s_select[1],
						'S_GROUP_CREATE'	=> $s_select[2],
					));
				}
				else if ( $ug_id )
				{
					$template->assign_block_vars('view', array());

					construct_show_box($ug_id, $forum_id, $options, ($mode ? $mode : $options[0]));
				}

				break;

			case 'permission_admin':
			case 'permission_download':
			case 'permission_gallery':

				$options	= array(substr(strtoupper($action), strpos($action, '_')+1, 1) . '_');
				$permission	= array('usergroups', 'selects');
				
				if ( $user_names )
				{
					$result = get_user_name_id($user_ids, $user_names);

					if (!sizeof($user_ids) || $result !== false)
					{
						return 'msg_select_users';
					}

					$ug_id = $user_ids;
				}

				if ( $all_groups || $all_users )
				{
					if ( $all_groups )
					{
						$ug_id = request('groups', ARY);
					}
					else
					{
						$ug_id = request('users', ARY);
					}
				}

				if ( !$ug_id )
				{
					$template->assign_block_vars('usergroups', array());

					$s_select = select_type($permission[0], $permission[1]);
					
					$template->assign_vars(array(
						'S_USER_UPDATE'		=> $s_select[0],
						'S_USER_CREATE'		=> '',
						'S_GROUP_UPDATE'	=> $s_select[1],
						'S_GROUP_CREATE'	=> $s_select[2],
						'S_GROUP_COPY'		=> $s_select[3],
					));
				}
				else if ( $ug_id )
				{
					$template->assign_block_vars('permission', array());

					construct_permission_box($ug_id, $forum_id, $options, ($mode ? $mode : $options[0]));
				}

				break;

			case 'permission_group':

				$options	= array('A_', 'D_', 'G_');
				$permission	= array('group', 'drop');
				$page_title	= 'acp_permission_group';

				if ( !$ug_id )
				{
					$template->assign_block_vars('display', array());

					$fields .= '<input type="hidden" name="ug_type" value="group">';

					$template->assign_vars(array(
						'S_SELECT'	=> select_type($permission[0], $permission[1]),
						'S_HIDDEN'	=> $fields,
					));
				}
				else if ( $ug_id )
				{
					$template->assign_block_vars('permission', array());

					construct_permission_box($ug_id, $forum_id, $options, ($mode ? $mode : $options[0]));
				}

				break;

			case 'permission_user':

				$options	= array('U_');
				$permission	= array('user', 'input');
				$page_title	= 'acp_user_perm';

				if ( $user_name )
				{
					$result = get_user_name_id($user_id, $user_name);

					if (!sizeof($user_id) || $result !== false)
					{
						return 'msg_select_users';
					}

					$ug_id = $user_id;
				}

				if ( !$ug_id )
				{
					$template->assign_block_vars('display', array());

					$fields .= '<input type="hidden" name="ug_type" value="user">';

					$template->assign_vars(array(
						'S_SELECT'	=> select_type($permission[0], $permission[1]),
						'S_HIDDEN'	=> $fields,
					));
				}
				else if ( $ug_id )
				{
					$template->assign_block_vars('permission', array());

					construct_permission_box($ug_id, $forum_id, $options, ($mode ? $mode : $options[0]));
				}

				break;

			case 'forums_all':
			case 'forums_mod':

				$options	= ($action == 'forums_all') ? array('F_', 'M_') : array('M_', 'F_');
				$permission	= array('usergroups', 'selects');
				$page_title	= ($action == 'forums_all') ? 'acp_user_perm' : 'acp_group_perm';
			#	$forum_id	= check_ids($forum_id);
			
			#	debug($all_forums, '$all_forums');
			
				if ( $forum_id || $all_forums )
				{
					if ( $forum_id )
					{
						$forum_id = check_ids($forum_id);
					}
					else
					{
						$forum_id = check_ids(request('forum_id', ARY));
					}
				}

				if ( $user_names )
				{
					$result = get_user_name_id($user_ids, $user_names);

					if (!sizeof($user_ids) || $result !== false)
					{
						return 'msg_select_users';
					}

					$ug_id = $user_ids;
				}

				if ( !$forum_id )
				{
					$template->assign_block_vars('display', array());
					$template->assign_vars(array('S_SELECT' => select_type('forums', 'select')));

				}
				else if ( $forum_id && !$ug_id )
				{
					$template->assign_block_vars('usergroups', array());

					$s_select = select_type($permission[0], $permission[1]);

					if ( is_array($forum_id) )
					{
						foreach ( $forum_id as $forum )
						{
							$fields .= '<input type="hidden" name="forum_id[]" value="' . $forum . '">';
						}
					}

					$template->assign_vars(array(
						'S_USER_UPDATE'		=> $s_select[0],
						'S_USER_CREATE'		=> '',
						'S_GROUP_UPDATE'	=> $s_select[1],
						'S_GROUP_CREATE'	=> $s_select[2],

						'S_HIDDEN'			=> $fields,
					));
				}
				else if ( $forum_id && $ug_id )
				{
					$template->assign_block_vars('permission', array());

					construct_permission_box($ug_id, $forum_id, $options, ($mode ? $mode : $options[0]));
				}

				break;

			case 'forums_user':
			case 'forums_group':

				$options	= array('F_');
				$permission	= ($action == 'forums_user') ? array('user', 'input') : array('group', 'drop');
				$page_title	= ($action == 'forums_user') ? 'acp_user_perm' : 'acp_group_perm';
				$forum_id	= check_ids($forum_id);

				if ( $user_name )
				{
					$result = get_user_name_id($user_id, $user_name);

					if (!sizeof($user_id) || $result !== false)
					{
						return 'msg_select_user';
					}

					$ug_id = $user_id;
				}

				if ( !$ug_id )
				{
					$template->assign_block_vars('display', array());
					$template->assign_vars(array('S_SELECT' => select_type($permission[0], $permission[1])));
				}
				else if ( $ug_id && !$forum_id )
				{
					$template->assign_block_vars('display', array());

					$fields .= '<input type="hidden" name="ug_id[]" value="' . $ug_id[0] . '">';
					$fields .= '<input type="hidden" name="ug_type" value="' . (($action == 'forums_user') ? 'user' : 'group') . '">';

					$template->assign_vars(array(
						'S_SELECT'	=> select_type('forums', 'select'),
						'S_HIDDEN'	=> $fields,
					));
				}
				else if ( $ug_id && $forum_id )
				{
					$template->assign_block_vars('permission', array());

					construct_permission_box($ug_id, $forum_id, $options, ($mode ? $mode : $options[0]));
				}

				break;
		}
	}

	if ( $delete )
	{
		debug($_POST);
	}

	if ( $update )
	{
		$simpleauth = $_POST['sa'];
		$acl_label	= acl_label(($type ? $type : (is_array($options) ? $options[0] : $options)));
		$acl_field	= acl_field(($type ? $type : (is_array($options) ? $options[0] : $options)), '');
		$label_ids	= array_keys($acl_label);

		switch ( $ug_type )
		{
			case 'group':	$access_action = array(ACL_GROUPS, 'group_id');	break;
			case 'user':	$access_action = array(ACL_USERS, 'user_id');	break;
		}

		foreach ( $simpleauth as $fug => $label_key )
		{
			list($main, $parent) = explode('_', $fug);

			if ( isset($sa_type) )
			{
				switch ($sa_type)
				{
					case 'type1':

						$forum = $parent;
						$ug_id = $main;

						break;

					case 'type2':

						$forum = $main;
						$ug_id = $parent;

						break;
				}
			}
			else
			{
				if ( sizeof($forum_id) > 1 && sizeof($ug_id) < 2 )
				{
					$forum = $parent;
					$ug_id = $main;

					$sa_type = 'type1';
				}
				else
				{
					$forum = $main;
					$ug_id = $parent;

					$sa_type = 'type2';
				}
			}

			if ( $label_key != 0 )
			{
				$sql = "SELECT * FROM $access_action[0] WHERE $access_action[1] = $ug_id AND forum_id = $forum AND label_id = 0 AND auth_option_id IN (" . implode(', ', array_values($acl_field)) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				if ( $db->sql_numrows($result) )
				{
					while ( $row = $db->sql_fetchrow($result) )
					{
						$_entrys[] = $row['auth_option_id'];
					}

					$sql = "DELETE FROM $access_action[0] WHERE $access_action[1] = $ug_id AND forum_id = $forum AND auth_option_id IN (" . implode(', ', $_entrys) . ")";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}

				$sql = "SELECT * FROM $access_action[0] WHERE $access_action[1] = $ug_id AND forum_id = $forum AND label_id IN (" . implode(', ', $label_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				if ( $db->sql_numrows($result) )
				{
					$row = $db->sql_fetchrow($result);

					$sql = "UPDATE $access_action[0] SET label_id = $label_key WHERE $access_action[1] = $ug_id AND forum_id = $forum AND label_id = $row[label_id]";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					$sql = "INSERT INTO $access_action[0] ($access_action[1], forum_id, label_id) VALUES ($ug_id, $forum, $label_key)";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
			}
			else
			{
				$sql = "SELECT * FROM $access_action[0] WHERE $access_action[1] = $ug_id AND forum_id = $forum AND label_id IN (" . implode(', ', $label_ids) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				if ( $db->sql_numrows($result) )
				{
					$sql = "DELETE FROM $access_action[0] WHERE $access_action[1] = $ug_id AND forum_id = $forum AND label_id IN (" . implode(', ', $label_ids) . ")";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}

				$sql = "SELECT * FROM $access_action[0] WHERE $access_action[1] = $ug_id AND forum_id = $forum AND label_id = 0 AND auth_option_id IN (" . implode(', ', array_values($acl_field)) . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				while ( $row = $db->sql_fetchrow($result) )
				{
					$_entrys[$row['auth_option_id']] = $row['auth_value'];
				}

				$n_option_insert = $n_option_update = $n_option_delete = array();

				if ( isset($_POST[$type . $forum][$ug_id]) )
				{
					foreach ( $_POST[$type . $forum][$ug_id] as $name => $value )
					{
						if ( isset($_entrys[$acl_field[$name]]) && $value != 0 )
						{
							$n_option_update[] = array(
								$access_action[1]	=> $ug_id,
								'forum_id'			=> $forum,
								'auth_option_id'	=> $acl_field[$name],
								'auth_value'		=> $value,
							);
						}
						else if ( isset($_entrys[$acl_field[$name]]) )
						{
							$n_option_delete[] = $acl_field[$name];
						}
						else if ( $value != 0 )
						{
							$n_option_insert[] = array(
								$access_action[1]	=> $ug_id,
								'forum_id'			=> $forum,
								'auth_option_id'	=> $acl_field[$name],
								'auth_value'		=> $value,
							);
						}

					}
				}

				if ( $n_option_update )
				{
					foreach ( $n_option_update as $_option_update )
					{
						$_option_update_end = array_pop($_option_update);

						$sql = "UPDATE $access_action[0] SET auth_value = $_option_update_end WHERE $access_action[1] = {$_option_update[$access_action[1]]} AND forum_id = {$_option_update['forum_id']} AND auth_option_id = {$_option_update['auth_option_id']}";
						if ( !$db->sql_query($sql) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
				}

				if ( $n_option_insert )
				{
					$ary = array();

					foreach ( $n_option_insert as $_key => $_option )
					{
						$values = array();

						foreach ( $_option as $_k => $_v )
						{
							$values[] = $_v;
						}
						$ary[] = '(' . implode(', ', $values) . ')';
					}

					$sql = "INSERT INTO $access_action[0] (" . implode(', ', array_keys($n_option_insert[0])) . ") VALUES " . implode(', ', $ary);
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}

				if ( $n_option_delete )
				{
					$sql = "DELETE FROM $access_action[0] WHERE $access_action[1] = $ug_id AND forum_id = $forum AND auth_option_id IN (" . implode(', ', $n_option_delete) . ")";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}

		$msg = $lang['UPDATE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);

		log_add(LOG_ADMIN, $log, $mode);
		message(GENERAL_MESSAGE, $msg);

	}

	$template->assign_vars(array(
		'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
		'L_EXPLAIN'	=> $lang['EXPLAIN'],
	#	'L_INPUT'	=> msg_head($mode, $lang['TITLE'], $data_sql['label_name']),
	#	'L_OPTIONS'	=> $s_options,

		'L_GROUPS'			=> $lang['GROUPS'],
		'L_GROUPS_ALL'		=> $lang['GROUPS_ALL'],
		'L_GROUPS_ADDED'	=> $lang['GROUPS_ADDED'],
		'L_GROUPS_MANAGE'	=> $lang['GROUPS_MANAGE'],
		
		'L_USERS'			=> $lang['USERS'],
		'L_USERS_ALL'		=> $lang['USERS_ALL'],
		'L_USERS_ADDED'		=> $lang['USERS_ADDED'],
		'L_USERS_MANAGE'	=> $lang['USERS_MANAGE'],
		
		'L_AUTH_CREATE' => $lang['AUTH_CREATE'],
		'L_AUTH_UPDATE' => $lang['AUTH_UPDATE'],
		'L_AUTH_SHOW'	=> $lang['AUTH_SHOW'],
		'L_AUTH_DELETE' => $lang['AUTH_DELETE'],

		'L_PERMISSION'	=> $lang['EXTENDED_PERMISSION'],
		'L_PERMISSION_ALL'	=> $lang['EXTENDED_PERMISSION_ALL'],
		'L_VIEW_AUTH'	=> $lang['COMMON_AUTH'],

		'S_ACTION'	=> check_sid("$file"),
		'S_FIELDS'	=> $fields,
	));
}

$template->pparse('body');

acp_footer();

?>