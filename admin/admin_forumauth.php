<?php

if( !empty($setmodules) )
{
	$module['sm_forumauth'] = array(
		'filename'	=> basename(__FILE__),
		'modes'		=> array(
			'index'	=> 'sm_forumauth',
		),
	);
}
else
{
	define('IN_CMS', true);
	
	//
	// Load default header
	//
	$root_path = './../';
	require('./pagestart.php');
	
	add_lang('forum_auth');
	
	if ( !$userauth['auth_forum_perm'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	//
	// Start program - define vars
	//
	//                View       Read      Post      Reply     Edit     Delete    Sticky   Announce Globalannounce Poll Pollcreate
	$simple_auth_ary = array(
		0	=> array(AUTH_ALL, AUTH_ALL, AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_REG),	//	Öffentlich
		1	=> array(AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_MOD),	//	Benutzer
		2	=> array(AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_MOD),	//	Benutzer versteckt
		3	=> array(AUTH_REG, AUTH_REG, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_TRI, AUTH_MOD),	//	Trail
		4	=> array(AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_TRI, AUTH_MOD),	//	Trail versteckt
		5	=> array(AUTH_REG, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MOD, AUTH_MEM, AUTH_MEM),	//	Member
		6	=> array(AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MOD, AUTH_MEM, AUTH_MEM),	//	Member versteckt		
		7	=> array(AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD),	//	Moderatoren
		8	=> array(AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD),	//	Moderatoren versteckt
		9	=> array(AUTH_REG, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_MOD, AUTH_ACL, AUTH_ACL),	//	Privat
		10	=> array(AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL),	//	Privat versteckt
		11	=> array(AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM),	//	Administrator
	);
	
	$simple_auth_types = array(
		$lang['forms_public'],
		$lang['forms_register'],	sprintf($lang['forms_hidden'], $lang['forms_register']),
		$lang['forms_trial'],		sprintf($lang['forms_hidden'], $lang['forms_trial']),
		$lang['forms_member'],		sprintf($lang['forms_hidden'], $lang['forms_member']),
		$lang['forms_mod'],			sprintf($lang['forms_hidden'], $lang['forms_mod']),
		$lang['forms_privat'],		sprintf($lang['forms_hidden'], $lang['forms_privat']),
		$lang['forms_admin'],
		$lang['forms_special'],
	);
	
	$forum_auth_fields = array('auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_globalannounce', 'auth_poll', 'auth_pollcreate');
	
	$field_names = array(
		'auth_view'				=> $lang['forms_view'],
		'auth_read'				=> $lang['forms_read'],
		'auth_post'				=> $lang['forms_post'],
		'auth_reply'			=> $lang['forms_reply'],
		'auth_edit'				=> $lang['forms_edit'],
		'auth_delete'			=> $lang['forms_delete'],
		'auth_sticky'			=> $lang['forms_sticky'],
		'auth_announce'			=> $lang['forms_announce'],
		'auth_globalannounce'	=> $lang['forms_globalannounce'],
		'auth_poll'				=> $lang['forms_poll'],
		'auth_pollcreate'		=> $lang['forms_pollcreate'],
	);
	
	$forum_auth_levels	= array('all', 'reg', 'trial', 'member', 'moderator', 'private', 'admin');
	$forum_auth_const	= array(AUTH_ALL, AUTH_REG, AUTH_TRI, AUTH_MEM, AUTH_MOD, AUTH_ACL, AUTH_ADM);
	
	if(isset($HTTP_GET_VARS[POST_FORUM]) || isset($HTTP_POST_VARS[POST_FORUM]))
	{
		$forum_id = (isset($HTTP_POST_VARS[POST_FORUM])) ? intval($HTTP_POST_VARS[POST_FORUM]) : intval($HTTP_GET_VARS[POST_FORUM]);
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
					$sql = "UPDATE " . FORUM . " SET $sql WHERE forum_id = $forum_id";
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
	
				$sql = "UPDATE " . FORUM . " SET $sql WHERE forum_id = $forum_id";
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
			'META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('admin_forumauth.php?' . POST_FORUM . '=' . $forum_id) . '">')
		);
		$message = $lang['Forum_auth_updated'] . '<br><br>' . sprintf($lang['Click_return_forumauth'],  '<a href="' . check_sid('admin_forumauth.php') . '">', "</a>");
		message(GENERAL_MESSAGE, $message);
	
	} // End of submit
	
	//
	// Get required information, either all forums if
	// no id was specified or just the requsted if it
	// was
	//
	$sql = "SELECT f.*
		FROM " . FORUM . " f, " . FORUM_CAT . " c
		WHERE c.cat_id = f.cat_id
		$forum_sql
		ORDER BY c.cat_order ASC, f.forum_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, "Couldn't obtain forum list", "", __LINE__, __FILE__, $sql);
	}
	
	$forum_rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	if ( empty($forum_id) )
	{
		//
		// Output the selection table if no forum id was
		// specified
		//
		$template->set_filenames(array('body' => 'style/acp_auth.tpl'));
		$template->assign_block_vars('display', array());
//		$template->set_filenames(array(
//			'body' => './../admin/style/auth_select_body.tpl')
//		);
	
		$select_list = '<select name="' . POST_FORUM . '" class="post">';
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
	
			'S_ACTION' => check_sid('admin_forumauth.php'),
			'S_SELECT' => $select_list)
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
	
			for($j = 0; $j < count($simple_auth_types) - 1; $j++)
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
					$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . sprintf($lang['sprintf_select_format'], $lang['auth_' . $forum_auth_levels[$k]]) . '</option>';
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
		$switch_mode = check_sid('admin_forumauth.php?' . POST_FORUM . '=' . $forum_id . "&adv=". $adv_mode);
		$switch_mode_text = ( empty($adv) ) ? $lang['Advanced_mode'] : $lang['Simple_mode'];
		$u_switch_mode = '<a href="' . $switch_mode . '">' . $switch_mode_text . '</a>';
	
		$fields = '<input type="hidden" name="' . POST_FORUM . '" value="' . $forum_id . '">';
	
		$template->assign_vars(array(
			'FORUM_NAME' => $forum_name,
	
			'L_FORUM' => $lang['Forum'], 
			'L_AUTH_TITLE' => $lang['Auth_Control_Forum'],
			'L_AUTH_EXPLAIN' => $lang['Forum_auth_explain'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
	
			'U_SWITCH_MODE' => $u_switch_mode,
	
			'S_FORUMAUTH_ACTION' => check_sid('admin_forumauth.php'),
			'S_COLUMN_SPAN' => $s_column_span,
			'S_FIELDS' => $fields)
		);
	
	}
}

$template->pparse('body');

include('./page_footer_admin.php');

?>