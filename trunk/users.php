<?php

define('IN_CMS', true);

$root_path = './';

include("{$root_path}common.php");

$userdata = session_pagestart($user_ip, PAGE_USERS);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_USER;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$mode	= request('mode', TXT);
$smode	= request('smode', 1);
$data	= request('id', INT);
$sid	= request('sid', 1);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_users.tpl',
	'error'		=> 'info_error.tpl',
));

add_lang('lang_users');

$type = ( $mode == 'g' ) ? 'g' : 't';

$settings['userlist_private']	= $settings['userlist']['private'];
$settings['userlist_mail']		= $settings['userlist']['mail'];
$settings['userlist_teams']		= $settings['userlist']['teams'];
$settings['userlist_groups']	= $settings['userlist']['groups'];

if ( in_array($mode, array('m', 'u')) )
{
	/* template bereich */
	$template->assign_block_vars('list', array());
	
	/* user auslesen */
	$sql = "SELECT * FROM " . USERS . " WHERE ";
	$sql .= ( $mode == 'm' ) ? "user_level >= 2" : "user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$users = $db->sql_fetchrowset($result);
	
	$ugroups = $uteams = '';
	
	if ( $settings['userlist_groups'] )
	{
		$template->assign_block_vars('list._groups', array());
		
		$sql = "SELECT g.group_id, g.group_name, g.group_type, ul.user_id
					FROM " . GROUPS . " g
						LEFT JOIN " . LISTS . " ul ON ul.type_id = g.group_id
					WHERE ul.type = " . TYPE_GROUP . "
				ORDER BY g.group_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			if ( $row['group_type'] != GROUP_HIDDEN )
			{
				$groups[$row['user_id']][] = array('group_id' => $row['group_id'], 'group_name' => $row['group_name']);
			}
		}
		
		/* cache */
	}
	
	if ( $settings['userlist_teams'] )
	{
		$template->assign_block_vars('list._teams', array());
		
		$sql = "SELECT t.team_id, t.team_name, ul.user_id, g.game_image
					FROM " . TEAMS . " t
						LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
						LEFT JOIN " . LISTS . " ul ON ul.type_id = t.team_id
					WHERE  ul.type = " . TYPE_TEAM . "
				ORDER BY t.team_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$teams[$row['user_id']][] = array('team_id' => $row['team_id'], 'team_name' => $row['team_name'], 'game_image' => $row['game_image']);
		}
		
		/* cache */
	}
	
	$cnt = count($users);
	
	for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, $cnt); $i++ )
	{
		$user_id = $users[$i]['user_id'];
		
		/* benutzerinfos generieren */
		gen_userinfo($users[$i], $username, $join, $posts, $comments);
		
		/* prüfen um Benutzer in der Gruppe ist */
		if ( isset($groups[$user_id]) )
		{
			foreach ( $groups[$user_id] as $row )
			{
				$g_ary[$user_id][] =  '<a href="' . check_sid("$file?mode=g&id={$row['group_id']}") . '">' . $row['group_name'] . '</a>';
			}
		
			$ugroups = implode('<br />', $g_ary[$user_id]);
		}
		
		/* prüfen um Benutzer in dem Team ist */
		if ( isset($teams[$user_id]) )
		{
			foreach ( $teams[$user_id] as $row )
			{
				$game = display_gameicon($row['game_image']);
				$t_ary[$user_id][] =  $game . ' <a href="' . check_sid("$file?mode=t&id={$row['team_id']}") . '">' . $row['team_name'] . '</a>';
			}
		
			$uteams = implode('<br />', $t_ary[$user_id]);
		}
		
		$template->assign_block_vars('list._row', array(
			'CLASS'		=> ( $i % 2 ) ? $theme['td_class1'] : $theme['td_class2'],
			'USERNAME'	=> $username,
			'GROUPS'	=> $ugroups,
			'TEAMS'		=> $uteams,
		));
		
		if ( $settings['userlist_groups'] )
		{
			$template->assign_block_vars('list.row._groups', array());
		}
		
		if ( $settings['userlist_teams'] )
		{
			$template->assign_block_vars('list.row._teams', array());
		}
	}
	
	$template->assign_vars(array(
	#	'PAGINATION' => $pagination,
	#	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $config['per_page_entry_site'] ) + 1 ), ceil( $total_members / $config['per_page_entry_site'] )),
	));
}
else if ( in_array($mode, array('g', 't')) )
{
	if ( $data )
	{
		$template->assign_block_vars('block', array());
		
		$s_opt = '';
		
		switch ( $mode )
		{
			case 'g':
				$tbl_sql	= GROUPS;
			#	$tbl_usr	= GROUPS_USERS;
				$tbl_type	= TYPE_GROUP;
				$tbl_id		= 'group_id';
				$tbl_where	= ', ul.user_status, ul.user_pending';
				$page_title = $lang['main_group'];
				
				break;
			
			case 't':
				$tbl_sql	= TEAMS;
			#	$tbl_usr	= TEAMS_USERS;
				$tbl_type	= TYPE_TEAM;
				$tbl_id		= 'team_id';
				$tbl_where	= ', ul.user_status, ul.time_create';
				$page_title = $lang['main_team'];
				
				break;
		}
		
		$sql = "SELECT * FROM $tbl_sql WHERE $tbl_id = $data";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$info = $db->sql_fetchrow($result);
		
		$sql = "SELECT u.* $tbl_where
					FROM " . LISTS . " ul
						LEFT JOIN " . USERS . " u ON u.user_id = ul.user_id
				 WHERE ul.type = $tbl_type AND ul.type_id = $data";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$users = $db->sql_fetchrowset($result);
		$count = count($users);
		
		if ( $users )
		{
			foreach ( $users as $value )
			{
				if ( $mode == 'g' )
				{
					if ( $value['user_status'] )
					{
						$ary_mod[] = $value;
						$_arymod[] = $value['user_id'];
					}
					else if ( $value['user_pending'] )
					{
						$ary_pen[] = $value;
						$_arypen[] = $value['user_id'];
					}
					else
					{
						$ary_mem[] = $value;
						$_arymem[] = $value['user_id'];
					}
				}
				else
				{
					if ( $value['user_status'] )
					{
						$ary_mod[] = $value;
						$_arymod[] = $value['user_id'];
					}
					else
					{
						$ary_mem[] = $value;
						$_arymem[] = $value['user_id'];
					}
				}
				
				$in_type[] = $value['user_id'];
			}
		}
		
		$is_mod = false;
		$is_mem = false;
		$is_pen = false;
		
		$cnt_mod = isset($ary_mod) ? count($ary_mod) : '';
		$cnt_mem = isset($ary_mem) ? count($ary_mem) : '';
		$cnt_pen = isset($ary_pen) ? count($ary_pen) : '';
		
		$lng_mod = ( $cnt_mod > 1 ) ? 'mods' : 'mod';
		$lng_mem = ( $cnt_mem > 1 ) ? 'mems' : 'mem';
		
		if ( $userdata['session_logged_in'] )
		{
			$is_mod = isset($_arymod) ? in_array($userdata['user_id'], $_arymod) ? true : false : false;
			$is_mem = isset($_arymem) ? in_array($userdata['user_id'], $_arymem) ? true : false : false;
			$is_pen = isset($_arypen) ? in_array($userdata['user_id'], $_arypen) ? true : false : false;
		}
		
		if ( request('add', 1) || request('approve', 1) || request('deny', 1) || $smode == '_user_delete' || $smode == '_user_level' )
		{
			$type_id	= ( $mode == 'g' ) ? 'group_id' : 'team_id';
			$type_mod	= ( $mode == 'g' ) ? 'group_mod' : 'team_mod';
			$type_users = ( $mode == 'g' ) ? GROUPS_USERS : TEAMS_USERS;
				
			if ( !$userdata['session_logged_in'] )
			{
				redirect(check_sid("$file?redirect=mode&$type&amp;id=$data", true));
			} 
			else if ( $sid !== $userdata['session_id'] )
			{
				message(GENERAL_ERROR, $lang['Session_invalid']);
			}
	
			if ( $userdata['user_level'] != ADMIN && !$is_mod )
			{
				$msg = $lang['Not_group_moderator'] . '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . check_sid('index.php') . '">', '</a>');
			}
			
			debug($_POST);
			
			if ( request('add', 1) )
			{
				$user_name = ( request('user_name', 2) ) ? request('user_name', 2) : '';
				
				$sql = "SELECT user_id, user_email, user_lang FROM " . USERS . " WHERE user_name = '$user_name'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$tmp_user = $db->sql_fetchrow($result);
				
				$user_id	= $tmp_user['user_id'];
				$user_email	= $tmp_user['user_email'];
				$user_lang	= $tmp_user['user_lang'];
				
				if ( !in_array($user_id, $in_type) )
				{				
					$sql = "INSERT INTO $type_users (user_id, $type_id, user_pending) VALUES ($user_id, $data, 0)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					( $mode == 'g' ) ? group_set_auth($user_id, $data) : '';
					
					$msg = 'user eingetragen';
				}
				else
				{
					$msg = 'user vorhanden';
				}
			}
			
			
			if ( $smode == '_user_level' )
			{
				$members_select = array();
				$members_mark = count($_POST['members']);
				
				for ( $i = 0; $i < $members_mark; $i++ )
				{
					if ( intval($_POST['members'][$i]) )
					{
						$members_select[] = intval($_POST['members'][$i]);
					}
				}
				
				if ( count($members_select) > 0 )
				{
					$user_ids = implode(', ', $members_select);
					
					$sql = "SELECT user_id FROM $type_users WHERE $type_id = $data AND $type_mod = 1 AND user_id IN ($user_ids)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					while ( $row = $db->sql_fetchrow($result) )
					{
						$mods[] = $row['user_id'];
					}
					$db->sql_freeresult($result);
	
					if ( count($mods) > 0 )
					{
						$sql = "UPDATE $type_users SET $type_mod = 0 WHERE $type_id = $data AND user_id IN (" . implode(', ', $mods) . ")";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
					}
					
					$sql_in = ( empty($mods) ? '' : ' AND NOT user_id IN (' . implode(', ', $mods) . ')');
					
					$sql = "UPDATE $type_users SET $type_mod = 1 WHERE $type_id = $data AND user_id IN (" . implode(", ", $members_select) . ")" . $sql_in;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$msg = $lang['group_set_mod'] . '<br><br>' . sprintf($lang['Click_return_group'], '<a href="' . check_sid("$file?mode=$mode&id=$data") . '">', '</a>');
				}
			}
			
			if ( ( ( isset($HTTP_POST_VARS['approve']) || isset($HTTP_POST_VARS['deny']) ) && isset($HTTP_POST_VARS['pending_members']) ) || ( $smode == '_user_delete' && isset($HTTP_POST_VARS['members']) ) )
			{
				$members = ( isset($HTTP_POST_VARS['approve']) || isset($HTTP_POST_VARS['deny']) ) ? $HTTP_POST_VARS['pending_members'] : $HTTP_POST_VARS['members'];

				$sql_in = '';
				for($i = 0; $i < count($members); $i++)
				{
					$sql_in .= ( ( $sql_in != '' ) ? ', ' : '' ) . intval($members[$i]);
				}

				if ( isset($HTTP_POST_VARS['approve']) )
				{
					$sql = 'UPDATE ' . GROUPS_USERS . '
								SET user_pending = 0
								WHERE user_id IN (' . $sql_in . ')
									AND group_id = ' . $data;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					for( $k = 0; $k < count($members); $k++)
					{
						group_set_auth($members[$k]['user_id'], $data);
					}
					
					$sql_select = 'SELECT user_email FROM ' . USERS . ' WHERE user_id IN (' . $sql_in . ')';
				}
				else if ( isset($HTTP_POST_VARS['deny']) || $smode == '_user_delete' )
				{
					$sql = 'DELETE FROM ' . GROUPS_USERS . ' WHERE user_id IN (' . $sql_in . ') AND group_id = ' . $data;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					for( $i = 0; $i < count($members); $i++ )
					{
						group_reset_auth($members[$i]['user_id'], $data);
					}
				}

				//
				// Email users when they are approved
				//
				if ( isset($HTTP_POST_VARS['approve']) )
				{
					if ( !($result = $db->sql_query($sql_select)) )
					{
						message(GENERAL_ERROR, 'Could not get user email information', '', __LINE__, __FILE__, $sql);
					}

					$bcc_list = array();
					while ($row = $db->sql_fetchrow($result))
					{
						$bcc_list[] = $row['user_email'];
					}
				}
			}
			
		#	$template->assign_vars(array('META' => "<meta http-equiv=\"refresh\" content=\"3;url=$file?mode=$mode&id=$data\">"));
			
			message(GENERAL_MESSAGE, $msg);
		}
		
		$s_opt .= "<select class=\"postselect\" name=\"smode\" id=\"smode\" onchange=\"setRequest(this.options[selectedIndex].value);\">";
		$s_opt .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['common_select_option']) . "</option>";
		$s_opt .= "<option value=\"_user_level\">" . sprintf($lang['sprintf_select_format'], ( $mode == 'g' ) ? $lang['rights_groups'] : $lang['rights_teams']) . "</option>";
		$s_opt .= ( $mode == 'g' ) ? "" : "<option value=\"_user_setrank\">" . sprintf($lang['sprintf_select_format'], $lang['select_rank']) . "</option>";
		$s_opt .= "<option value=\"_user_delete\">" . sprintf($lang['sprintf_select_format'], $lang['common_delete']) . "</option>";
		$s_opt .= "</select>";
		
	#	$s_opt = "<select class=\"postselect\" name=\"smode\">";
	#	$s_opt .= "<option value=\"\">&raquo; Option ausw&auml;hlen</option>";
	#	$s_opt .= "<option value=\"_user_delete\">&raquo; Entfernen</option>";
	#	$s_opt .= ( $mode == 'g' ) ? "" : "<option value=\"_user_rank\">&raquo; Rang setzen:</option>";
	#	$s_opt .= "<option value=\"_user_level\">&raquo; Gruppenrechte geben/nehmen</option>";
	#	$s_opt .= "</select>";
		
		$colspan = '';
		$detail = '';
				
		if ( isset($info['group_type']) )
		{
			$hidden = ( $info['group_type'] != GROUP_HIDDEN );
			$system = ( $info['group_type'] != GROUP_SYSTEM );
			
			if ( $is_mod )
			{
				if ( $system ) { $template->assign_block_vars('block.switch_unsubscribe_group_input', array()); }
				
				$detail = $lang['Are_group_moderator'];
			}
			else if ( $is_mem || $is_pen )
			{
				if ( $system ) { $template->assign_block_vars('block.switch_unsubscribe_group_input', array()); }
		
				$detail =  $is_pen ? $lang['Pending_this_group'] : $lang['Member_this_group'];
			}
			else if ( $userdata['user_id'] == ANONYMOUS )
			{
				$detail =  $lang['Login_to_join'];
			}
			else
			{
				switch ( $info['group_type'] )
				{
					case GROUP_OPEN:	$detail =  $lang['This_open_group'];	$template->assign_block_vars('block.switch_subscribe_group_input', array());	break;
					case GROUP_REQUEST:	$detail =  $lang['This_request_group'];	$template->assign_block_vars('block.switch_subscribe_group_input', array());	break;
					case GROUP_CLOSED:	$detail =  $lang['This_closed_group'];	break;
					case GROUP_HIDDEN:	$detail =  $lang['This_hidden_group'];	break;
					case GROUP_SYSTEM:	$detail =  $lang['This_system_group'];	break;
				}	
			}
		}
		else
		{
			$hidden = true;
			$system = true;
		}
		
		if ( $userdata['user_level'] == ADMIN || $is_mod || $is_mem || $hidden )
		{
			if ( !$cnt_mod )
			{
				$template->assign_block_vars('block._no_mod', array());
			}
			else
			{
				$template->assign_block_vars('block._mod', array());
				
				if ( $userdata['user_level'] == ADMIN && $system )	
				{
					$template->assign_block_vars('block._mod._switch_admin', array());
				}
				
				for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, $cnt_mod); $i++ )
				{
					$user_id = $ary_mod[$i]['user_id'];
					
					gen_userinfo($ary_mod[$i], $username, $join, $posts, $comments);
					
					$template->assign_block_vars('block._mod._row', array(
						'CLASS'	=> ($i % 2) ? $theme['td_class1'] : $theme['td_class2'],
						
						'USER'	=> $user_id,
						'NAME'	=> $username,
						'JOIN'	=> $join,
						
						'POSTS'	=> $posts,
						'COMMENTS' => $comments,
					));
					
					if ( $userdata['user_level'] == ADMIN && $system )	
					{
						$template->assign_block_vars('block._mod.row._switch_admin', array());
					}
				}
			}
			
			if ( !$cnt_mem )
			{
				$template->assign_block_vars('block._no_mem', array());
			}
			else
			{
				$template->assign_block_vars('block._mem', array());
				
				if ( ( $userdata['user_level'] == ADMIN || $is_mod ) && $system )
				{
					$template->assign_block_vars('block._mem._switch_moderator', array());
				}
				
				for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, $cnt_mem); $i++ )
				{
					$user_id = $ary_mem[$i]['user_id'];
					
					gen_userinfo($ary_mem[$i], $username, $join, $posts, $comments);
					
					$template->assign_block_vars('block._mem._row', array(
						'CLASS'	=> ($i % 2) ? $theme['td_class1'] : $theme['td_class2'],
						
						'USER'	=> $user_id,
						'NAME'	=> $username,
						'JOIN'	=> $join,
						
						'POSTS'	=> $posts,
						'COMMENTS' => $comments,
					));
					
					if ( ( $userdata['user_level'] == ADMIN || $is_mod ) && $system )
					{
						$template->assign_block_vars('block._mem.row._switch_moderator', array());
					}
				}
			}
			
			if ( $cnt_pen && ( $is_mod || $userdata['user_level'] == ADMIN ) )
			{
				for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, $cnt_pen); $i++ )
				{
					$user_id = $ary_pen[$i]['user_id'];
					
					gen_userinfo($ary_pen[$i], $username, $join, $posts, $comments);
					
					$template->assign_block_vars('block._pen', array(
						'CLASS'	=> ($i % 2) ? $theme['td_class1'] : $theme['td_class2'],
						
						'USER'	=> $user_id,
						'NAME'	=> $username,
						'JOIN'	=> $join,
						
						'POSTS'	=> $posts,
						'COMMENTS' => $comments,
					));
				}
			}
		}
		
		if ( !$is_mem && !$is_mod && !$hidden )
		{
			$template->assign_block_vars('block._hidden_group', array());
			$template->assign_vars(array('L_HIDDEN_MEMBERS' => $lang['Group_hidden_members']));
		}
		
		if ( $is_mod || $userdata['user_level'] == ADMIN && $system )
		{
			$template->assign_block_vars('block._add_member', array());
		}
		
		$fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
		$fields .= '<input type="hidden" name="id" value="' . $data . '" />';
		$fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
		
		$template->assign_vars(array(
			'TYPE'		=> sprintf('%s: %s', $page_title, ( $mode == 'g' ) ? $info['group_name'] : $info['team_name']),
			'OVERVIEW'	=> href('a_txt', $file, array('mode' => $type), $lang['common_overview']), 
					
			'DETAILS' => $detail,
			
			'TYPE_ID' => $data,
			'TYPE_MODE' => $mode,
			
			
			'L_ADD_MEMBER' => $lang['Add_member'],
			'L_JOIN_GROUP' => $lang['Join_group'],
			'L_UNSUBSCRIBE_GROUP' => $lang['Unsubscribe'],
			
			'L_MOD'	=> $lang[$lng_mod],
			'L_MEM'	=> $lang[$lng_mem],
			
			'L_POSTS'		=> $lang['posts'],
			'L_COMMENTS'	=> $lang['comments'],
			'L_JOINED'		=> ( $mode == 'g' ) ? $lang['registered'] : $lang['joined'],
			
			'S_FIELDS'	=> $fields,
			'S_OPTION'	=> $s_opt,
			'S_ACTION'	=> check_sid("$file?" . POST_GROUPS . "=$data"),
		));
	}
	else
	{
		switch ( $mode )
		{
			case 'g':
			
				$template->assign_block_vars('listg', array());
				
				$sql = "SELECT g.group_id, g.group_name, g.group_type, g.group_desc, ul.user_pending
							FROM " . GROUPS . " g, " . LISTS . " ul
						WHERE ul.type = " . TYPE_GROUP . " AND ul.user_id = " . $userdata['user_id'] . " AND ul.type_id = g.group_id
						ORDER BY g.group_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$groups = $db->sql_fetchrowset($result);
				
				$is_pending = $is_member = array();
				
				if ( $groups )
				{
					$template->assign_block_vars('listg._in_groups', array());
				
					foreach ( $groups as $group => $row )
					{
						$in_group[] = $row['group_id'];
				
						if ( $row['user_pending'] )
						{
							$is_pending[] = $row;
						}
						else
						{
							$is_member[] = $row;
						}
					}
				
					if ( $is_member )
					{
						$template->assign_block_vars('listg._in_groups._is_member', array());
				
						for ( $i = 0; $i < count($is_member); $i++ )
						{
							switch ( $is_member[$i]['group_type'] )
							{
								case GROUP_OPEN:	$group_type = $lang['Group_open'];		break;
								case GROUP_REQUEST:	$group_type = $lang['Group_quest'];		break;
								case GROUP_CLOSED:	$group_type = $lang['Group_closed'];	break;
								case GROUP_HIDDEN:	$group_type = $lang['Group_hidden'];	break;
								case GROUP_SYSTEM:	$group_type = $lang['Group_system'];	break;
							}
							
							$template->assign_block_vars('listg._in_groups._is_member._row', array(
								'CLASS'	=> ( $i % 2 ) ? $theme['td_class1'] : $theme['td_class2'],
								'NAME' => "<a href=" . check_sid("$file?mode=g&amp;id=" . $is_member[$i]['group_id']) . ">" . $is_member[$i]['group_name'] . "</a>",
								'DESC' => ( $is_member[$i]['group_desc'] ) ? ' :: ' . $is_member[$i]['group_desc'] : '',
								'TYPE' => $group_type,
							));
						}
					}
				
					if ( $is_pending )
					{
						$template->assign_block_vars('listg._in_groups._is_pending', array());
					
						for ($i = 0; $i < count($is_pending); $i++)
						{
							switch ( $is_pending[$i]['group_type'] )
							{
								case GROUP_OPEN:	$group_type = $lang['Group_open'];		break;
								case GROUP_REQUEST:	$group_type = $lang['Group_quest'];		break;
								case GROUP_CLOSED:	$group_type = $lang['Group_closed'];	break;
								case GROUP_HIDDEN:	$group_type = $lang['Group_hidden'];	break;
								case GROUP_SYSTEM:	$group_type = $lang['Group_system'];	break;
							}
					
							$template->assign_block_vars('listg._in_groups._is_pending._row', array(
								'CLASS'	=> ( $i % 2 ) ? $theme['td_class1'] : $theme['td_class2'],
								'NAME' => "<a href=" . check_sid("$file?mode=g&amp;id=" . $is_pending[$i]['group_id']) . ">" . $is_pending[$i]['group_name'] . "</a>",
								'DESC' => ( $is_pending[$i]['group_desc'] ) ? ' :: ' . $is_pending[$i]['group_desc'] : '',
								'TYPE' => $group_type,
							));
						}
					}
				}
			
				$ignore_group = ( isset($in_group) ) ? ( count($in_group) ) ? 'WHERE group_id NOT IN (' . implode(', ', $in_group) . ')' : '' : '';
				
				$sql = "SELECT group_id, group_name, group_type, group_desc
							FROM " . GROUPS . "
						$ignore_group
						ORDER BY group_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$no_group = $db->sql_fetchrowset($result);
			
				if ( $no_group )
				{
					$template->assign_block_vars('listg._no_group', array());
					
					for ( $i = 0; $i < count($no_group); $i++ )
					{
						switch ( $no_group[$i]['group_type'] )
						{
							case GROUP_OPEN:	$group_type = $lang['Group_open'];		break;
							case GROUP_REQUEST:	$group_type = $lang['Group_quest'];		break;
							case GROUP_CLOSED:	$group_type = $lang['Group_closed'];	break;
							case GROUP_HIDDEN:	$group_type = $lang['Group_hidden'];	break;
							case GROUP_SYSTEM:	$group_type = $lang['Group_system'];	break;
						}
						
						if ( $no_group[$i]['group_type'] != GROUP_HIDDEN )
						{
							$template->assign_block_vars('listg._no_group._row', array(
								'CLASS'	=> ( $i % 2 ) ? $theme['td_class1'] : $theme['td_class2'],
								'NAME' => "<a href=" . check_sid("$file?mode=g&amp;id=" . $no_group[$i]['group_id']) . ">" . $no_group[$i]['group_name'] . "</a>",
								'DESC' => ( $no_group[$i]['group_desc'] ) ? ' :: ' . $no_group[$i]['group_desc'] : '',
								'TYPE' => $group_type,
							));
						}
					}
				}
					
				$template->assign_vars(array(
					
					'L_CUR'		=> $lang['grp_cur_member'],
					'L_PEN'		=> $lang['grp_pen_member'],
					'L_NON'		=> $lang['grp_non_member'],
				));
				
				break;
			
			case 't':
			
				$template->assign_block_vars('listt', array());
				
				$sql = "SELECT DISTINCT g.* FROM " . GAMES . " g, " . TEAMS . " t WHERE g.game_id = t.team_game ORDER BY game_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$games = $db->sql_fetchrowset($result);
			//	$games = _cached($sql, 'data_games');
			
				$sql = "SELECT * FROM " . TEAMS . " ORDER BY team_order";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$teams = $db->sql_fetchrowset($result);
			//	$teams = _cached($sql, 'data_teams');
			
				$cnt_games = count($games);
				$cnt_teams = count($teams);
				
				for ( $i = 0; $i < $cnt_games; $i++ )
				{
					$class = 0;	
					$game_id = $games[$i]['game_id'];
					
					$template->assign_block_vars('listt._game_row', array('L_GAME' => $games[$i]['game_name']));
																	 
					for ( $j = 0; $j < $cnt_teams; $j++ )
					{
						$team_id	= $teams[$j]['team_id'];
						$team_game	= $teams[$j]['team_game'];
						
						if ( $team_game == $game_id )
						{
							$template->assign_block_vars('listt._gamerow._team_row', array(
								'CLASS'	=> ( $class % 2 ) ? $theme['td_class1'] : $theme['td_class2'],
								'NAME'	=> '<a href="' . check_sid("$file?mode=t&amp;id=$team_id") . '">' . $teams[$j]['team_name'] . '</a>',
								'GAME'	=> display_gameicon($games[$i]['game_size'], $games[$i]['game_image']),
								
							#	'JOINUS'	=> $teams[$j]['team_join']	? '<a href="' . check_sid("contact.php?mode=joinus&amp;$url=$team_id") . '">' . $lang['match_joinus'] . '</a>'  : '',
							#	'FIGHTUS'	=> $teams[$j]['team_fight']	? '<a href="' . check_sid("contact.php?mode=fightus&amp;$url=$team_id") . '">' . $lang['match_fightus'] . '</a>'  : '',
							));
							
							$class++;
						}
					}
				}
				
				break;
		}
	}	
}
else
{
	$template->assign_block_vars('default', array());
	
	echo 'default';
}

main_header();

$template->pparse('body');

main_footer();

?>