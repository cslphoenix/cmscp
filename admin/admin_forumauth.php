<?php
/***************************************************************************
 *                            admin_forumauth.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: admin_forumauth.php 4876 2004-03-25 15:57:20Z acydburn $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	if ( $userauth['auth_forum_auth'] || $userdata['user_level'] == ADMIN )
	{
		$module['forums']['permissions'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	//
	// Load default header
	//
	$root_path = './../';
	require('./pagestart.php');
	
	if ( !$userauth['auth_forum_auth'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	//
	// Start program - define vars
	//
	//                View      Read      Post      Reply     Edit     Delete    Sticky   Announce    Poll   Pollcreate
	$simple_auth_ary = array(
		0  => array(AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_MOD),	//	Benutzer
		1  => array(AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_MOD),	//	Benutzer versteckt
		2  => array(AUTH_REG, AUTH_REG, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_MOD, AUTH_MOD, AUTH_TRI, AUTH_MOD),	//	Trail
		3  => array(AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_MOD, AUTH_MOD, AUTH_TRI, AUTH_MOD),	//	Trail versteckt
		4  => array(AUTH_REG, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM),	//	Member
		5  => array(AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM),	//	Member versteckt		
		6  => array(AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD),	//	Moderatoren
		7  => array(AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD),	//	Moderatoren versteckt
		8  => array(AUTH_REG, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_ACL, AUTH_ACL),	//	Privat
		9  => array(AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_ACL, AUTH_ACL),	//	Privat versteckt
	);
	
	$simple_auth_types = array(
		$lang['Registered'],	$lang['Registered'] . ' [' . $lang['Hidden'] . ']',
		$lang['Trial'],			$lang['Trial'] . ' [' . $lang['Hidden'] . ']',
		$lang['Member'],		$lang['Member'] . ' [' . $lang['Hidden'] . ']',
		$lang['Moderators'],	$lang['Moderators'] . ' [' . $lang['Hidden'] . ']',
		$lang['Private'],		$lang['Private'] . ' [' . $lang['Hidden'] . ']');
	
	$forum_auth_fields = array('auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_poll', 'auth_pollcreate');
	
	$field_names = array(
		'auth_view'			=> $lang['View'],
		'auth_read'			=> $lang['Read'],
		'auth_post'			=> $lang['Post'],
		'auth_reply'		=> $lang['Reply'],
		'auth_edit'			=> $lang['Edit'],
		'auth_delete'		=> $lang['Delete'],
		'auth_sticky'		=> $lang['Sticky'],
		'auth_announce'		=> $lang['Announce'], 
		'auth_poll'			=> $lang['Poll'], 
		'auth_pollcreate'	=> $lang['Pollcreate']
	);
	
	$forum_auth_levels	= array('ALL', 'REG', 'TRI', 'MEM', 'MOD', 'ACL', 'ADM');
	$forum_auth_const	= array(AUTH_ALL, AUTH_REG, AUTH_TRI, AUTH_MEM, AUTH_MOD, AUTH_ACL, AUTH_ADM);
	
	if(isset($HTTP_GET_VARS[POST_FORUM_URL]) || isset($HTTP_POST_VARS[POST_FORUM_URL]))
	{
		$forum_id = (isset($HTTP_POST_VARS[POST_FORUM_URL])) ? intval($HTTP_POST_VARS[POST_FORUM_URL]) : intval($HTTP_GET_VARS[POST_FORUM_URL]);
		$forum_sql = "AND forum_id = $forum_id";
	}
	else
	{
		unset($forum_id);
		$forum_sql = '';
	}
	
	if( isset($HTTP_GET_VARS['adv']) )
	{
		$adv = intval($HTTP_GET_VARS['adv']);
	}
	else
	{
		unset($adv);
	}
	
	//
	// Start program proper
	//
	if( isset($HTTP_POST_VARS['submit']) )
	{
		$sql = '';
	
		if(!empty($forum_id))
		{
			if(isset($HTTP_POST_VARS['simpleauth']))
			{
				$simple_ary = $simple_auth_ary[intval($HTTP_POST_VARS['simpleauth'])];
	
				for($i = 0; $i < count($simple_ary); $i++)
				{
					$sql .= ( ( $sql != '' ) ? ', ' : '' ) . $forum_auth_fields[$i] . ' = ' . $simple_ary[$i];
				}
	
				if (is_array($simple_ary))
				{
					$sql = "UPDATE " . FORUMS . " SET $sql WHERE forum_id = $forum_id";
				}
			}
			else
			{
				for($i = 0; $i < count($forum_auth_fields); $i++)
				{
					$value = intval($HTTP_POST_VARS[$forum_auth_fields[$i]]);
	
					if ( $forum_auth_fields[$i] == 'auth_poll' )
					{
						if ( $HTTP_POST_VARS['auth_poll'] == AUTH_ALL )
						{
							$value = AUTH_REG;
						}
					}
	
					$sql .= ( ( $sql != '' ) ? ', ' : '' ) .$forum_auth_fields[$i] . ' = ' . $value;
				}
	
				$sql = "UPDATE " . FORUMS . " SET $sql WHERE forum_id = $forum_id";
			}
	
			if ( $sql != '' )
			{
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Could not update auth table', '', __LINE__, __FILE__, $sql);
				}
			}
	
			$forum_sql = '';
			$adv = 0;
		}
	
		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid('admin_forumauth.php?' . POST_FORUM_URL . '=' . $forum_id) . '">')
		);
		$message = $lang['Forum_auth_updated'] . '<br><br>' . sprintf($lang['Click_return_forumauth'],  '<a href="' . append_sid('admin_forumauth.php') . '">', "</a>");
		message(GENERAL_MESSAGE, $message);
	
	} // End of submit
	
	//
	// Get required information, either all forums if
	// no id was specified or just the requsted if it
	// was
	//
	$sql = "SELECT f.*
		FROM " . FORUMS . " f, " . CATEGORIES . " c
		WHERE c.cat_id = f.cat_id
		$forum_sql
		ORDER BY c.cat_order ASC, f.forum_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, "Couldn't obtain forum list", "", __LINE__, __FILE__, $sql);
	}
	
	$forum_rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	if( empty($forum_id) )
	{
		//
		// Output the selection table if no forum id was
		// specified
		//
		$template->set_filenames(array('body' => 'style/acp_auth.tpl'));
		$template->assign_block_vars('_display', array());
//		$template->set_filenames(array(
//			'body' => './../admin/style/auth_select_body.tpl')
//		);
	
		$select_list = '<select name="' . POST_FORUM_URL . '" class="post">';
		for($i = 0; $i < count($forum_rows); $i++)
		{
			$select_list .= '<option value="' . $forum_rows[$i]['forum_id'] . '">' . $forum_rows[$i]['forum_name'] . '</option>';
		}
		$select_list .= '</select>';
	
		$template->assign_vars(array(
			'L_AUTH_TITLE' => $lang['Auth_Control_Forum'],
			'L_AUTH_EXPLAIN' => $lang['Forum_auth_explain'],
			'L_AUTH_SELECT' => $lang['Select_a_Forum'],
			'L_LOOK_UP' => $lang['Look_up_Forum'],
	
			'S_AUTH_ACTION' => append_sid('admin_forumauth.php'),
			'S_AUTH_SELECT' => $select_list)
		);
	
	}
	else
	{
		//
		// Output the authorisation details if an id was
		// specified
		//
		$template->set_filenames(array('body' => 'style/acp_auth.tpl'));
		$template->assign_block_vars('auth_forum', array());
//		$template->set_filenames(array(
//			'body' => './../admin/style/auth_forum_body.tpl')
//		);
	
		$forum_name = $forum_rows[0]['forum_name'];
	
		@reset($simple_auth_ary);
		while( list($key, $auth_levels) = each($simple_auth_ary))
		{
			$matched = 1;
			for($k = 0; $k < count($auth_levels); $k++)
			{
				$matched_type = $key;
	
				if ( $forum_rows[0][$forum_auth_fields[$k]] != $auth_levels[$k] )
				{
					$matched = 0;
				}
			}
	
			if ( $matched )
			{
				break;
			}
		}
	
		//
		// If we didn't get a match above then we
		// automatically switch into 'advanced' mode
		//
		if ( !isset($adv) && !$matched )
		{
			$adv = 1;
		}
	
		$s_column_span = '0';
	
		if ( empty($adv) )
		{
			$simple_auth = '<select name="simpleauth" class="post">';
	
			for($j = 0; $j < count($simple_auth_types); $j++)
			{
				$selected = ( $matched_type == $j ) ? ' selected="selected"' : '';
				$simple_auth .= '<option value="' . $j . '"' . $selected . '>' . $simple_auth_types[$j] . '</option>';
			}
	
			$simple_auth .= '</select>';
	
			$template->assign_block_vars('auth_forum.forum_auth_titles', array('CELL_TITLE' => $lang['Simple_mode']));
			$template->assign_block_vars('auth_forum.forum_auth_data', array('S_AUTH_LEVELS_SELECT' => $simple_auth));
	
			$s_column_span++;
		}
		else
		{
			//
			// Output values of individual
			// fields
			//
			for($j = 0; $j < count($forum_auth_fields); $j++)
			{
				$custom_auth[$j] = '&nbsp;<select class="postselect" name="' . $forum_auth_fields[$j] . '">';
	
				for($k = 0; $k < count($forum_auth_levels); $k++)
				{
					$selected = ( $forum_rows[0][$forum_auth_fields[$j]] == $forum_auth_const[$k] ) ? ' selected="selected"' : '';
					$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . $lang['Forum_' . $forum_auth_levels[$k]] . '</option>';
				}
				$custom_auth[$j] .= '</select>&nbsp;';
	
				$cell_title = $field_names[$forum_auth_fields[$j]];
	
				
				$template->assign_block_vars('auth_forum.forum_auth_data', array(
					'CELL_TITLE' => $cell_title,
					'S_AUTH_LEVELS_SELECT' => $custom_auth[$j],
				));
	
				$s_column_span++;
			}
		}
		
		$adv_mode = ( empty($adv) ) ? '1' : '0';
		$switch_mode = append_sid('admin_forumauth.php?' . POST_FORUM_URL . '=' . $forum_id . "&adv=". $adv_mode);
		$switch_mode_text = ( empty($adv) ) ? $lang['Advanced_mode'] : $lang['Simple_mode'];
		$u_switch_mode = '<a href="' . $switch_mode . '">' . $switch_mode_text . '</a>';
	
		$s_fields = '<input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '">';
	
		$template->assign_vars(array(
			'FORUM_NAME' => $forum_name,
	
			'L_FORUM' => $lang['Forum'], 
			'L_AUTH_TITLE' => $lang['Auth_Control_Forum'],
			'L_AUTH_EXPLAIN' => $lang['Forum_auth_explain'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
	
			'U_SWITCH_MODE' => $u_switch_mode,
	
			'S_FORUMAUTH_ACTION' => append_sid('admin_forumauth.php'),
			'S_COLUMN_SPAN' => $s_column_span,
			'S_FIELDS' => $s_fields)
		);
	
	}
}

$template->pparse('body');

include('./page_footer_admin.php');

?>