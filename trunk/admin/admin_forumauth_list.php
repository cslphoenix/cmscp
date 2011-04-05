<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_forum_auth'] )
	{
		$module['_headmenu_02_forum']['_submenu_perm_list'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_perm_list';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('forum_auth');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_GAMES;
	$url	= POST_FORUM_URL;
	$url_c	= POST_CATEGORY_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_cat	= request($url_c, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['forum']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_forum_auth'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
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
		$lang['forms_moderator'],	sprintf($lang['forms_hidden'], $lang['forms_moderator']),
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
	
	$field_images = array(
		'auth_view'				=> $images['forms_view'],
		'auth_read'				=> $images['forms_read'],
		'auth_post'				=> $images['forms_post'],
		'auth_reply'			=> $images['forms_reply'],
		'auth_edit'				=> $images['forms_edit'],
		'auth_delete'			=> $images['forms_delete'],
		'auth_sticky'			=> $images['forms_sticky'],
		'auth_announce'			=> $images['forms_announce'],
		'auth_globalannounce'	=> $images['forms_globalannounce'],
		'auth_poll'				=> $images['forms_poll'],
		'auth_pollcreate'		=> $images['forms_pollcreate'],
	);
	
	$forum_auth_levels	= array('all', 'register', 'trial', 'member', 'moderator', 'private', 'admin');
	$forum_auth_const	= array(AUTH_ALL, AUTH_REG, AUTH_TRI, AUTH_MEM, AUTH_MOD, AUTH_ACL, AUTH_ADM);
	
	$cat_id		= ( $data_cat )	? $data_cat : '';
	$cat_sql	= ( $data_cat )	? "AND c.cat_id = $data_cat" : '';
	$forum_id	= ( $data_id )	? $data_id : '';
	$forum_sql	= ( $data_id )	? "AND forum_id = $data_id" : '';
	
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
	if ( isset($HTTP_POST_VARS['submit']) )
	{
		$sql = '';
	
		if ( !empty($forum_id) )
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
	
					if ( $forum_auth_fields[$i] == 'auth_vote' )
					{
						if ( $HTTP_POST_VARS['auth_vote'] == AUTH_ALL )
						{
							$value = AUTH_REG;
						}
					}
	
					$sql .= ( ( $sql != '' ) ? ', ' : '' ) .$forum_auth_fields[$i] . ' = ' . $value;
				}
	
				$sql = "UPDATE " . FORUM . " SET $sql WHERE forum_id = $forum_id";
			}
			
					
			debug($sql);

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
		elseif (!empty($cat_id))
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
	
			$sql = "UPDATE " . FORUM . " SET $sql WHERE cat_id = $cat_id";
	
			if ( $sql != '' )
			{
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Could not update auth table', '', __LINE__, __FILE__, $sql);
				}
			}
	
			$cat_sql = '';
		}
		
		$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . append_sid($file) . '">'));
		$message = $lang['Forum_auth_updated'] . '<br><br>' . sprintf($lang['Click_return_forumauth'],  '<a href="' . append_sid($file) . '">', "</a>");
		message(GENERAL_MESSAGE, $message);
	
	} // End of submit
	
	$sql = "SELECT f.* FROM " . FORUM . " f, " . FORUM_CAT . " c WHERE c.cat_id = f.cat_id $forum_sql $cat_sql ORDER BY c.cat_order ASC, f.forum_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$forum_rows = $db->sql_fetchrowset($result);
	
	if ( empty($forum_id) && empty($cat_id) )
	{
		$template->set_filenames(array('body' => 'style/acp_forum_auth.tpl'));
		$template->assign_block_vars('_auth_list', array());
	
		$template->assign_vars(array(
			
			'L_LEGEND'	=> $lang['legende'],
		
			'L_AUTH_TITLE' => $lang['Permissions_List'],
			'L_AUTH_EXPLAIN' => $lang['Forum_auth_list_explain'],
			'L_FORUM_NAME' => $lang['Forum_name'],
			'S_COLUMN_SPAN' => count($forum_auth_fields)+1,
		));
		
		/*	Legende	*/
		for ( $i = 0; $i < count($forum_auth_fields); $i++ )
		{
			$template->assign_block_vars('_auth_list._title', array(
				'TITLE' => $field_names[$forum_auth_fields[$i]],
				'IMAGE' => $field_images[$forum_auth_fields[$i]],
			));
			
			if ( $i == 6 )
			{
				break;
			}
		}
		
		for ( $i = 7; $i < count($forum_auth_fields); $i++ )
		{
			$template->assign_block_vars('_auth_list._title2', array(
				'TITLE' => $field_names[$forum_auth_fields[$i]],
				'IMAGE' => $field_images[$forum_auth_fields[$i]],
			));
		}
		
		for ( $i = 0; $i < count($forum_auth_levels); $i++ )
		{
			$template->assign_block_vars('_auth_list._auth', array(
				'TITLE' => $lang['auth_' . $forum_auth_levels[$i]],
				'IMAGE' => $images['auth_' . $forum_auth_levels[$i]],
			));
		}
		/*	Legende	*/
		
		for ( $i = 0; $i < count($forum_auth_fields); $i++ )
		{
			$template->assign_block_vars('_auth_list._titles', array(
				'TITLE' => $field_names[$forum_auth_fields[$i]],
				'IMAGE' => $field_images[$forum_auth_fields[$i]],
			));
		}
		
		$sql = "SELECT * FROM " . FORUM_CAT . " ORDER BY cat_order";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
		}
		$cats = $db->sql_fetchrowset($result);
	
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id = $cats[$i]['cat_id'];
	
			$template->assign_block_vars('_auth_list.cat_row', array(
				'CAT_NAME'	=> $cats[$i]['cat_name'],
				'CAT_URL'	=> append_sid("$file?$url_c=$cat_id"),
			));
	
			for ( $j = 0; $j < count($forum_rows); $j++ )
			{
				if ( $cat_id == $forum_rows[$j]['cat_id'] )
				{
					$template->assign_block_vars('_auth_list.cat_row.forum_row', array(
						'CLASS' => ( !($j % 2) ) ? 'class_row1' : 'class_row2',
						'ROW_CLASS' => ( !($j % 2) ) ? 'row_class1' : 'row_class2',
						'FORUM_NAME' => '<a href="'.append_sid('admin_forumauth_list.php?'.$url.'='.$forum_rows[$j]['forum_id']).'">'.$forum_rows[$j]['forum_name'].'</a>')
					);
	
					for ($k=0; $k<count($forum_auth_fields); $k++)
					{
						$item_auth_value = $forum_rows[$j][$forum_auth_fields[$k]];
						for ($l=0; $l<count($forum_auth_const); $l++)
						{
							if ($item_auth_value == $forum_auth_const[$l])
							{
								$item_auth_level = $forum_auth_levels[$l];
								break;
							}
						}
						$template->assign_block_vars('_auth_list.cat_row.forum_row.forum_auth_data', array(
							'CELL_VALUE' => $images['auth_' . $item_auth_level],
							'AUTH_EXPLAIN' => sprintf($lang['auth_forum_explain_' . $forum_auth_fields[$k]], $lang['auth_forum_explain_' . $item_auth_level]))
						);
					}
				}
			}
		}
	}
	elseif ( !empty($forum_id) )
	{
		//
		// Output the authorisation details if an forum id was
		// specified
		//
		$template->set_filenames(array(
			'body' => 'style/auth_forum_body.tpl')
		);
	
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
	
		$s_column_span == 0;
	
		if ( empty($adv) )
		{
			$simple_auth = '<select name="simpleauth">';
	
			for($j = 0; $j < count($simple_auth_types); $j++)
			{
				$selected = ( $matched_type == $j ) ? ' selected="selected"' : '';
				$simple_auth .= '<option value="' . $j . '"' . $selected . '>' . $simple_auth_types[$j] . '</option>';
			}
	
			$simple_auth .= '</select>';
	
			$template->assign_block_vars('forum_auth_titles', array(
				'CELL_TITLE' => $lang['Simple_mode'])
			);
			$template->assign_block_vars('forum_auth_data', array(
				'S_AUTH_LEVELS_SELECT' => $simple_auth)
			);
	
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
				$custom_auth[$j] = '&nbsp;<select name="' . $forum_auth_fields[$j] . '">';
	
				for($k = 0; $k < count($forum_auth_levels); $k++)
				{
					$selected = ( $forum_rows[0][$forum_auth_fields[$j]] == $forum_auth_const[$k] ) ? ' selected="selected"' : '';
					$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . $lang['auth_' . $forum_auth_levels[$k]] . '</option>';
				}
				$custom_auth[$j] .= '</select>&nbsp;';
	
				$cell_title = $field_names[$forum_auth_fields[$j]];
	
				$template->assign_block_vars('forum_auth_titles', array(
					'CELL_TITLE' => $cell_title)
				);
				$template->assign_block_vars('forum_auth_data', array(
					'S_AUTH_LEVELS_SELECT' => $custom_auth[$j])
				);
	
				$s_column_span++;
			}
		}
	
		$adv_mode = ( empty($adv) ) ? '1' : '0';
		$switch_mode = append_sid('admin_forumauth_list.php?' . $url . '=' . $forum_id . "&adv=". $adv_mode);
		$switch_mode_text = ( empty($adv) ) ? $lang['Advanced_mode'] : $lang['Simple_mode'];
		$u_switch_mode = '<a href="' . $switch_mode . '">' . $switch_mode_text . '</a>';
	
		$fields = '<input type="hidden" name="' . $url . '" value="' . $forum_id . '">';
	
		$template->assign_vars(array(
		
			
		
		'FORUM_NAME' => $forum_name,
	
			'L_FORUM' => $lang['Forum'], 
			'L_AUTH_TITLE' => $lang['Auth_Control_Forum'],
			'L_AUTH_EXPLAIN' => $lang['Forum_auth_explain'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
	
			'U_SWITCH_MODE' => $u_switch_mode,
	
			'S_FORUMAUTH_ACTION' => append_sid($file),
			'S_COLUMN_SPAN' => $s_column_span,
			'S_FIELDS' => $fields)
		);
	
	}
	else
	{
		$template->set_filenames(array('body' => 'style/auth_cat_body.tpl'));
		
		for ( $i = 0; $i < count($forum_auth_fields); $i++ )
		{
			$template->assign_block_vars('forum_auth_titles', array(
				'CELL_TITLE' => $field_names[$forum_auth_fields[$i]]
			));
		}
	
		$sql = 'SELECT c.cat_id, c.cat_name, c.cat_order
					FROM ' . FORUM_CAT . ' c
					WHERE c.cat_id = ' . $cat_id . '
				ORDER BY c.cat_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
		}
		$category_rows = $db->sql_fetchrowset($result);
	
		$cat_id = $category_rows[0]['cat_id'];
		$cat_name = $category_rows[0]['cat_name'];
		
		$template->assign_block_vars('cat_row', array(
			'CAT_NAME'	=> $cat_name,
			'CAT_URL'	=> append_sid('admin_forumauth_list.php?' . $url_c . '=' .$cat_id)
		));
	
		for ( $j=0; $j < count($forum_rows); $j++ )
		{
			if ( $cat_id == $forum_rows[$j]['cat_id'] )
			{
				$forum_id	= $forum_rows[$j]['forum_id'];
				$forum_name	= $forum_rows[$j]['forum_name'];
				
				$template->assign_block_vars('cat_row.forum_row', array(
					'FORUM_NAME' => '<a href="' . append_sid('admin_forumauth_list.php?' . $url . '=' . $forum_id) . '">' . $forum_name . '</a>'
				));
				
				for ( $k=0; $k < count($forum_auth_fields); $k++ )
				{
					$item_auth_value = $forum_rows[$j][$forum_auth_fields[$k]];
					
					for ( $l=0; $l < count($forum_auth_const); $l++ )
					{
						if ( $item_auth_value == $forum_auth_const[$l] )
						{
							$item_auth_level = $forum_auth_levels[$l];
							break;
						}
					}
					$template->assign_block_vars('cat_row.forum_row.forum_auth_data', array(
						'CELL_VALUE'	=> $lang['auth_' . $item_auth_level],
						'AUTH_EXPLAIN'	=> sprintf($lang['auth_forum_explain_' . $forum_auth_fields[$k]], $lang['auth_forum_explain_' . $item_auth_level])
					));
				}
			}
		}
	
		//
		// Next generate the information to allow the permissions to be changed
		// Note: We always read from the first forum in the category
		//
		for($j = 0; $j < count($forum_auth_fields); $j++)
		{
			$custom_auth[$j] = '&nbsp;<select name="' . $forum_auth_fields[$j] . '">';
	
			for($k = 0; $k < count($forum_auth_levels); $k++)
			{
				$selected = ( $forum_rows[0][$forum_auth_fields[$j]] == $forum_auth_const[$k] ) ? ' selected="selected"' : '';
				$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . $lang['auth_' . $forum_auth_levels[$k]] . '</option>';
			}
			$custom_auth[$j] .= '</select>&nbsp;';
	
			$template->assign_block_vars('forum_auth_data', array(
				'S_AUTH_LEVELS_SELECT' => $custom_auth[$j])
			);
		}
		
		//
		// Finally pass any remaining items to the template
		//
		$fields = '<input type="hidden" name="' . $url_c . '" value="' . $cat_id . '">';
	
		$template->assign_vars(array(
		
			
			
			'CAT_NAME' => $cat_name,
	
			'L_AUTH_TITLE' => $lang['Auth_Control_Category'],
			'L_AUTH_EXPLAIN' => $lang['Cat_auth_list_explain'],
			'L_CATEGORY' => $lang['Category'],
			'L_FORUM_NAME' => $lang['Forum_name'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
	
			'S_FORUMAUTH_ACTION' => append_sid($file),
			'S_COLUMN_SPAN' => count($forum_auth_fields)+1,
			'S_FIELDS' => $fields)
		);
	
	
	}
}

$template->pparse('body');

include('./page_footer_admin.php');

?>