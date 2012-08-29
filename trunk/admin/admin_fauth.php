<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_fauth',
		'modes'		=> array(
			'main'		=> array('title' => 'acp_fauth'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_forum';
	
	include('./pagestart.php');
	
	add_lang('forums');

	$index	= '';
	$fields	= '';
	
	$log	= SECTION_FORUM;
	$url	= POST_FORUM;
	
	$frs	= POST_FORUM;
	$grp	= POST_GROUPS;
	
	$f = request(POST_FORUM, ARY, INT);
	$g = request(POST_GROUPS, ARY, INT);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_forum'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body' => 'style/acp_auth.tpl',
	#	'tiny' => 'style/tinymce_news.tpl',
	#	'uimg' => 'style/inc_java_img.tpl',					
	));
	
	$forum_auth_fields = array('f_view', 'f_read', 'f_notice', 'f_sticky', 'f_icons', 'f_reply', 'f_post', 'm_ownedit', 'm_owndelete', 'm_ownclose', 'm_report', 'p_view', 'p_create', 'p_vote', 'p_change', 'p_close');

	$simple_auth_ary = array(
		1	=> array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0),	//	Kein Zugang
		2	=> array(1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0),	//	Nur lesender Zugriff
		3	=> array(1, 1, 0, 0, 1, 1, 1, 1, 0, 0, 1, 1, 0, 1, 0, 0),	//	Standard-Zugang
		4	=> array(1, 1, 0, 0, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 0, 0),	//	Standard-Zugang + Umfragen
		5	=> array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),	//	Voller Zugang
	);
	
	$forum_auth_levels	= array('all', 'register', 'trial', 'member', 'moderator', 'admin');
	$forum_auth_const	= array(AUTH_ALL, AUTH_REG, AUTH_TRI, AUTH_MEM, AUTH_MOD, AUTH_ADM);
	
	$field_names = array(
		'f_view' => $lang['f_view'],
		'f_read' => $lang['f_read'],
		'f_notice' => $lang['f_notice'],
		'f_sticky' => $lang['f_sticky'],
		'f_icons' => $lang['f_icons'],
		'f_reply' => $lang['f_reply'],
		'f_post' => $lang['f_post'],
		'm_ownedit' => $lang['m_ownedit'],
		'm_owndelete' => $lang['m_owndelete'],
		'm_ownclose' => $lang['m_ownclose'],
		'm_report' => $lang['m_report'],
		'p_view' => $lang['p_view'],
		'p_create' => $lang['p_create'],
		'p_vote' => $lang['p_vote'],
		'p_change' => $lang['p_change'],
		'p_close' => $lang['p_close']
	);
	
	$simple_auth_types = array(
		$lang['forms_noneset'],
		$lang['forum_access_no'],
		$lang['forum_access_only_read'],
		$lang['forum_access_default'],
		$lang['forum_access_default_polls'],
		$lang['forum_access_full'],
	);
	
	$groups	= ( is_array($g) ) ? $g : array($g);
	$forums	= ( is_array($f) ) ? $f : array($f);
	
	debug($_POST);
#	debug($data);

#	debug($groups);
	debug($forums);

	if ( request('sel_f', TXT) && $forums )
	{
		$template->assign_block_vars('select', array());
		
		$group_in = $group_out = '';
		
		$sql = 'SELECT * FROM ' . FORMS_AUTH . ' WHERE forum_id IN (' . implode(', ', $forums) . ')';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$groups_in[] = $row['group_id'];
		}
		
		$sql = 'SELECT * FROM ' . GROUPS . ' ORDER BY group_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			if ( in_array($row['group_id'], $groups_in) )
			{
				$auth_in[] = $row;
			}
			else
			{
				$auth_out[] = $row;
			}
		}
		
		if (  $auth_in )
		{
			$group_in = '<select name="' . $grp . '[]" multiple="multiple" size="5">';
			
			foreach ( $auth_in as $row ) { $group_in .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>'; }
			
			$group_in .= '</select>';
		}
		
		if ( $group_out )
		{
			$group_out = '<select name="' . $grp . '[]" multiple="multiple" size="5">';
			
			foreach ( $auth_out as $row ) { $group_out .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>'; }
			
			$group_out .= '</select>';
		}
		
		foreach ( $forums as $row ) { $fields .= '<input type="hidden" name="' . $frs . '[]" value="' . $row . '" />'; }
		
		$template->assign_vars(array(
			'S_GROUPS_IN'	=> $group_in,
			'S_GROUPS_OUT'	=> $group_out,
		
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	else if ( $forums && $groups && ( request('grp_create', TXT) || request('grp_update', TXT) ) )
	{
		$template->assign_block_vars('auth_forum', array());
		
		$sql = 'SELECT group_id, group_name FROM ' . GROUPS . ' WHERE group_id IN (' . implode(', ', $groups) . ') ORDER BY group_order ASC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$group[$row['group_id']] = $row;
		}
		
		$sql = 'SELECT forum_id, forum_sub, forum_name, forum_order FROM ' . FORMS . ' WHERE forum_id IN (' . implode(', ', $forums) . ') ORDER BY forum_sub ASC, forum_order ASC';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$forum_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		$sql = 'SELECT * FROM ' . FORMS_AUTH . ' WHERE group_id IN (' . implode(', ', $groups) . ') AND forum_id IN (' . implode(', ', $forums) . ')';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$forum_auths[$row['forum_id']][$row['group_id']] = $row;
		}
		
	#	$forum_auths = $db->sql_fetchrowset($result);
	#	debug($forum_auths);
		
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
		
		function empty_fields($data)
		{
			foreach ( $data as $row )
			{
				$return[$row] = 0;
			}
			return $return;
		}
		
		function reset_fields($data)
		{
			foreach ( $data as $key => $row )
			{
				if ( !in_array($key, array('forum_id', 'group_id', 'mod')) )
				{
					$return[$key] = $row;
				}
			}
			return $return;					
		}
		
		function simple_auth($data)
		{
			global $simple_auth_ary, $forum_auth_fields, $lang;
			
			foreach ( $simple_auth_ary as $id => $row )
			{
				$matched = 1;
				
				$forum_auth_ary = $simple_auth_ary[$id];
				
				foreach ( $forum_auth_ary as $akey => $arow )
				{
					if ( $data[$forum_auth_fields[$akey]] != $forum_auth_ary[$akey] )
					{
						$matched = 0;
						$id = 0;
						break;
					}
				}
				
				if ( $matched )
				{
					return $id;
				}
			}
			return $id;
		}
		
		foreach ( $simple_auth_ary as $id => $ary )
		{
			$template->assign_block_vars('auth_forum.set_perm', array(
				'ID' => $id,
				'OPTION' => 'test',
			));
			
			foreach ( $forum_auth_fields as $key => $row )
			{
				$template->assign_block_vars('auth_forum.set_perm.row', array(
					'FIELD' => $row,
					'VALUE' => $ary[$key] ? '_y' : '_n',
				));
			}				
		}
		
		$auth_temp = '';
		
		foreach ( $forms as $forum_data )
		{
			$forum_id	= $forum_data['forum_id'];
			$forum_name = $forum_data['forum_name'];
			
			$template->assign_block_vars('auth_forum.row', array('NAME' => $forum_name));
			
			foreach ( $group as $group_id => $group_data )
			{
				$group_name = $group_data['group_name'];
				$group_auth = isset($forum_auths[$forum_id][$group_id]) ? reset_fields($forum_auths[$forum_id][$group_id]) : empty_fields($forum_auth_fields);
				$group_temp	= simple_auth($group_auth);
				
				
				$simple_auth = '<select class="selectsmall" name="' . sprintf('simpleauth%sg%s', $forum_id, $group_id) . '" id="' . sprintf('s%sg%s', $forum_id, $group_id) . '" onchange="set_permission(this.options[selectedIndex].value, \'' . 'f' . $forum_id . '\', \'' . 'g' . $group_id . '\');">';
				
				foreach ( $simple_auth_types as $tkey => $trows )
				{
					$selected = ( $group_temp == $tkey ) ? ' selected="selected"' : '';
					$simple_auth .= '<option value="' . $tkey . '"' . $selected . '>' . $simple_auth_types[$tkey] . '</option>';
				}
				
				$simple_auth .= '</select>';
				
				
				$template->assign_block_vars('auth_forum.row.group', array(
					'NAME'		=> $group_name,
					'TOGGLE'	=> sprintf('f[%s][%s]', $forum_id, $group_id),
					'SIMPLE'	=> $simple_auth,
				));
				
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
	else
	{
		$template->assign_block_vars('display', array());
		
	#	$sql = 'SELECT forum_id, forum_sub, forum_name, forum_order FROM ' . FORMS . ' WHERE forum_id IN (' . implode(', ', $forums) . ') ORDER BY forum_sub ASC, forum_order ASC';
		$sql = 'SELECT forum_id, forum_sub, forum_name, forum_order FROM ' . FORMS . ' ORDER BY forum_sub ASC, forum_order ASC';
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
				if ( $rows['forum_sub'] == '0' )
				{
					$forum_cat[$rows['forum_id']] = $rows;
				}
				else if ( $rows['forum_sub'] < 0 )
				{
					$forum_frs[$rows['forum_sub']*(-1)][$rows['forum_id']] = $rows;
				}
				else
				{
					$forum_sub[$rows['forum_sub']][$rows['forum_id']] = $rows;
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
}

$template->pparse('body');

include('./page_footer_admin.php');

?>