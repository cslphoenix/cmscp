<?php
/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_forum'] )
	{
		$module['_headmenu_forum']['_submenu_set'] = $filename;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_forum';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');

	load_lang('forums');
	
	$data_id	= request(POST_AUTHLIST_URL, 0);
	$data_cat	= request(POST_CATEGORY_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$show_index	= '';
	$s_fields	= '';
	$error		= '';
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_forum'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_EVENT, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $no_header ) ? redirect('admin/' . append_sid('admin_forums.php', true)) : false;
	
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
		10	=> array(AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_MOD, AUTH_ACL, AUTH_ACL),	//	Privat versteckt
		11	=> array(AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_MOD, AUTH_ADM, AUTH_ADM),	//	Administrator
	);
	
	$simple_auth_types = array(
		$lang['forms_public'],
		$lang['forms_register'],	sprintf($lang['forms_hidden'], $lang['forms_register']),
		$lang['forms_trial'],		sprintf($lang['forms_hidden'], $lang['forms_trial']),
		$lang['forms_member'],		sprintf($lang['forms_hidden'], $lang['forms_member']),
		$lang['forms_mod'],			sprintf($lang['forms_hidden'], $lang['forms_mod']),
		$lang['forms_privat'],		sprintf($lang['forms_hidden'], $lang['forms_privat']),
		$lang['forms_admin'],
	);
	
	$forum_auth_fields = array(
		'auth_view',
		'auth_read',
		'auth_post',
		'auth_reply',
		'auth_edit',
		'auth_delete',
		'auth_sticky',
		'auth_announce',
		'auth_globalannounce',
		'auth_poll',
		'auth_pollcreate',
	);
	
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
	
	$forum_auth_levels	= array('ALL', 'REG', 'TRI', 'MEM', 'MOD', 'ACL', 'ADM');
	$forum_auth_const	= array(AUTH_ALL, AUTH_REG, AUTH_TRI, AUTH_MEM, AUTH_MOD, AUTH_ACL, AUTH_ADM);
	
	// ------------------
	// Begin function block
	//
	function simple_auth($forum_row)
	{
		global $simple_auth_ary, $forum_auth_fields, $lang;
	
		for ( $i = 0; $i < count($simple_auth_ary); $i++ )
		{
			$matched = 1;
			$forum_auth_ary = $simple_auth_ary[$i];
			
			for ( $j = 0; $j < count($forum_auth_ary); $j++ )
			{
				if ( $forum_row[$forum_auth_fields[$j]] != $forum_auth_ary[$j] )
				{
					$matched = 0;
					
					break;
				}
			}
			
			if ( $matched )
			{
				return $i;
			}
		}
		return $i;
	}
	
	function get_info($mode, $id)
	{
		global $db;
	
		switch ( $mode )
		{
			case 'category':
				$table = CATEGORIES;
				$idfield = 'cat_id';
				$namefield = 'cat_title';
				break;
	
			case 'forum':
				$table = FORUMS;
				$idfield = 'forum_id';
				$namefield = 'forum_name';
				break;
	
			default:
				message(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
				break;
		}
		$sql = "SELECT count(*) as total
			FROM $table";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, "Couldn't get Forum/Category information", "", __LINE__, __FILE__, $sql);
		}
		$count = $db->sql_fetchrow($result);
		$count = $count['total'];
	
		$sql = "SELECT *
			FROM $table
			WHERE $idfield = $id"; 
	
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, "Couldn't get Forum/Category information", "", __LINE__, __FILE__, $sql);
		}
	
		if( $db->sql_numrows($result) != 1 )
		{
			message(GENERAL_ERROR, "Forum/Category doesn't exist or multiple forums/categories with ID $id", "", __LINE__, __FILE__);
		}
	
		$return = $db->sql_fetchrow($result);
		$return['number'] = $count;
		return $return;
	}
	
	function get_list($mode, $id, $select)
	{
		global $db;
	
		switch ( $mode )
		{
			case 'category':
				$table = CATEGORIES;
				$idfield = 'cat_id';
				$namefield = 'cat_title';
				break;
	
			case 'forum':
				$table = FORUMS;
				$idfield = 'forum_id';
				$namefield = 'forum_name';
				break;
	
			default:
				message(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
				break;
		}
	
		$sql = "SELECT * FROM $table";
		
		if ( $select == 0 )
		{
			$sql .= " WHERE $idfield <> $id";
		}
		
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, "Couldn't get list of Categories/Forums", "", __LINE__, __FILE__, $sql);
		}
	
		$catlist = '<select name="c" class="post">';
		while ( $row = $db->sql_fetchrow($result) )
		{
			$selected = ( $row[$idfield] == $id ) ? ' selected="selected"' : '';
			$catlist .= '<option value="' . $row[$idfield] . '"' . $selected . '>' . $row[$namefield] . '</option>';
		}
		$catlist .= '</select>';
	
		return($catlist);
	}
	//
	// End function block
	// ------------------
	
	//
	// Begin program proper
	//
	if ( isset($HTTP_POST_VARS['addforum']) || isset($HTTP_POST_VARS['addcategory']) )
	{
		$mode = ( isset($HTTP_POST_VARS['addforum']) ) ? "addforum" : "addcat";
	
		if( $mode == "addforum" )
		{
			list($cat_id) = each($HTTP_POST_VARS['addforum']);
			$cat_id = intval($cat_id);
			// 
			// stripslashes needs to be run on this because slashes are added when the forum name is posted
			//
			$forumname = stripslashes($HTTP_POST_VARS['forumname'][$cat_id]);
		}
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case 'addforum':
			case 'editforum':
			
				//
				// Show form to create/modify a forum
				//
				if ( $mode == 'editforum' )
				{
					$l_title = $lang['Edit_forum'];
					$newmode = 'modforum';
					$buttonvalue = $lang['Update'];
	
					$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);
	
					$row = get_info('forum', $forum_id);
	
					$cat_id = $row['cat_id'];
					$forumname = $row['forum_name'];
					$forumdesc = $row['forum_desc'];
					$forumstatus = $row['forum_status'];
					$s_simple_auth = simple_auth($row);
				}
				else if ( $mode == 'addforum' )
				{
					$l_title = $lang['Create_forum'];
					$newmode = 'createforum';
					$buttonvalue = $lang['Create_forum'];
	
					$forumdesc = '';
					$forumstatus = FORUM_UNLOCKED;
					$forum_id = '';
					$s_simple_auth = 0;
				}
	
				$catlist = get_list('category', $cat_id, TRUE);
	
				$status = ( $forumstatus ) ? 'selected="selected"' : '';
				
				// These two options ($lang['Status_unlocked'] and $lang['Status_locked']) seem to be missing from
				// the language files.
				$lang['Status_unlocked'] = isset($lang['Status_unlocked']) ? $lang['Status_unlocked'] : 'Unlocked';
				$lang['Status_locked'] = isset($lang['Status_locked']) ? $lang['Status_locked'] : 'Locked';
				
				$statuslist = '<select name="forum_status" class="post">';
				$statuslist .= '<option value="' . FORUM_UNLOCKED . '" ' . $status . '>' . $lang['Status_unlocked'] . '</option>';
				$statuslist .= '<option value="' . FORUM_LOCKED . '" ' . $status . '>' . $lang['Status_locked'] . '</option>';
				$statuslist .= '</select>';
				
				$simple_auth = '<select name="simpleauth" class="post">';

				$matched = 0;
				for($j = 0; $j < count($simple_auth_types); $j++)
				{
					if ($j < count($simple_auth_types)  || !$matched)
					{
						$selected = ( $s_simple_auth == $j ) ? ' selected="selected"' : '';
						$simple_auth .= '<option value="' . $j . '"' . $selected . '>' . $simple_auth_types[$j] . '</option>';
					}
					if ($s_simple_auth == $j)
					{
						$matched = 1;
					}
				}
				
				$template->set_filenames(array('body' => 'style/acp_forums.tpl'));
				$template->assign_block_vars('forum_edit', array());
				
				$s_fields = '<input type="hidden" name="mode" value="' . $newmode .'" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
	
				$template->assign_vars(array(
					'S_FORUM_ACTION' => append_sid('admin_forums.php'),
					'L_FORUM_HEAD' => $lang['Forum_admin'], 
					'S_FIELDS' => $s_fields,
					'S_SUBMIT_VALUE' => $buttonvalue, 
					'S_CAT_LIST' => $catlist,
					'S_AUTH_LEVELS_SELECT' => $simple_auth,
					'S_STATUS_LIST' => $statuslist,
	
					'L_FORUM_TITLE' => $l_title, 
					'L_FORUM_EXPLAIN' => $lang['Forum_edit_delete_explain'], 
					'L_FORUM_SETTINGS' => $lang['Forum_settings'], 
					'L_FORUM_NAME' => $lang['Forum_name'], 
					'L_PERMISSIONS' => $lang['Permissions'],
					'L_CATEGORY' => $lang['Category'], 
					'L_FORUM_DESCRIPTION' => $lang['Forum_desc'],
					'L_FORUM_STATUS' => $lang['Forum_status'],
					'L_AUTO_PRUNE' => $lang['Forum_pruning'],
					'L_ENABLED' => $lang['Enabled'],
					'L_PRUNE_DAYS' => $lang['prune_days'],
					'L_PRUNE_FREQ' => $lang['prune_freq'],
					'L_DAYS' => $lang['Days'],
	
					'PRUNE_DAYS' => ( isset($pr_row['prune_days']) ) ? $pr_row['prune_days'] : 7,
					'PRUNE_FREQ' => ( isset($pr_row['prune_freq']) ) ? $pr_row['prune_freq'] : 1,
					'FORUM_NAME' => $forumname,
					'DESCRIPTION' => $forumdesc)
				);
				$template->pparse('body');
				break;
	
			case 'createforum':
				//
				// Create a forum in the DB
				//
				if( trim($HTTP_POST_VARS['forum_name']) == '' )
				{
					message(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
	
				$sql = 'SELECT MAX(forum_order) AS max_order
							FROM ' . FORUMS . '
							WHERE cat_id = ' . intval($HTTP_POST_VARS[POST_CATEGORY_URL]);
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				$sql = 'SELECT MAX(forum_id) AS max_id
							FROM ' . FORUMS;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
	
				$max_id = $row['max_id'];
				$next_id = $max_id + 1;
				
				$forum_auth_ary = $simple_auth_ary[intval($HTTP_POST_VARS['simpleauth'])];
	
				$field_sql = '';
				$value_sql = '';
				for( $i = 0; $i < count($forum_auth_ary); $i++ )
				{
					$field_sql .= ', ' . $forum_auth_fields[$i];
					$value_sql .= ', ' . $forum_auth_ary[$i];
				}
	
				$sql = "INSERT INTO " . FORUMS . " (forum_id, forum_name, cat_id, forum_desc, forum_order, forum_status " . $field_sql . ")
					VALUES ('" . $next_id . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['forum_name']) . "', " . intval($HTTP_POST_VARS[POST_CATEGORY_URL]) . ", '" . str_replace("\'", "''", $HTTP_POST_VARS['forum_desc']) . "', $next_order, " . intval($HTTP_POST_VARS['forum_status']) . $value_sql . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Couldn't insert row in forums table", "", __LINE__, __FILE__, $sql);
				}
				
				log_add(LOG_ADMIN, LOG_SEK_FORUM, 'acp_forum_add');
	
				$message = $lang['create_forum'] . sprintf($lang['click_return_forum'], '<a href="' . append_sid('admin_forums.php') . '">', '</a>');
	
				message(GENERAL_MESSAGE, $message);
	
				break;
	
			case 'modforum':
			
				$forum_id	= intval($HTTP_POST_VARS[POST_FORUM_URL]);
				$get_cat_id	= intval($HTTP_POST_VARS[POST_CATEGORY_URL]);
				$row		= get_info('forum', $forum_id);
				$cat_id		= $row['cat_id'];
				
				$sql_order = '';
				if ( $get_cat_id != $cat_id )
				{
					$sql = 'SELECT MAX(forum_order) AS max_order
								FROM ' . FORUMS . '
								WHERE cat_id = ' . intval($HTTP_POST_VARS[POST_CATEGORY_URL]);
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
		
					$max_order = $row['max_order'];
					$next_order = $max_order + 10;
					
					$sql_order .= ', forum_order = ' . $next_order;
				}
				
				$sql_auth = '';
				if( intval($HTTP_POST_VARS['simpleauth']) < count($simple_auth_types) - 1 )
				{
					$simple_ary = $simple_auth_ary[intval($HTTP_POST_VARS['simpleauth'])];
	
					for($i = 0; $i < count($simple_ary); $i++)
					{
						$sql_auth .= ', ' . $forum_auth_fields[$i] . ' = ' . $simple_ary[$i];
					}
				}
	
				$sql = "UPDATE " . FORUMS . "
							SET
								forum_name = '" . str_replace("\'", "''", $HTTP_POST_VARS['forum_name']) . "',
								cat_id = " . intval($HTTP_POST_VARS[POST_CATEGORY_URL]) . ",
								forum_desc = '" . str_replace("\'", "''", $HTTP_POST_VARS['forum_desc']) . "',
								forum_status = " . intval($HTTP_POST_VARS['forum_status']) . "
								$sql_order $sql_auth
							WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				log_add(LOG_ADMIN, LOG_SEK_FORUM, 'acp_forum_add');
	
				$message = $lang['update_forum'] . sprintf($lang['click_return_forum'], '<a href="' . append_sid('admin_forums.php') . '">', '</a>');
	
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case 'addcat':
				// Create a category in the DB
				if( trim($HTTP_POST_VARS['categoryname']) == '')
				{
					message(GENERAL_ERROR, "Can't create a category without a name");
				}
	
				$sql = "SELECT MAX(cat_order) AS max_order
					FROM " . CATEGORIES;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Couldn't get order number from categories table", "", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
	
				//
				// There is no problem having duplicate forum names so we won't check for it.
				//
				$sql = "INSERT INTO " . CATEGORIES . " (cat_title, cat_order)
					VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['categoryname']) . "', $next_order)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Couldn't insert row in categories table", "", __LINE__, __FILE__, $sql);
				}
	
				$message = $lang['Forums_updated'] . "<br><br>" . sprintf($lang['Click_return_forumadmin'], '<a href="' . append_sid('admin_forums.php') . "\">", "</a>") . "<br><br>" . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.php?pane=right") . "\">", "</a>");
	
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case 'editcat':
				//
				// Show form to edit a category
				//
				$newmode = 'modcat';
				$buttonvalue = $lang['Update'];
	
				$cat_id = intval($HTTP_GET_VARS[POST_CATEGORY_URL]);
	
				$row = get_info('category', $cat_id);
				$cat_title = $row['cat_title'];
	
				$template->set_filenames(array('body' => 'style/acp_forums.tpl'));
				$template->assign_block_vars('category_edit', array());
	
				$s_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="' . POST_CATEGORY_URL . '" value="' . $cat_id . '" />';
	
				$template->assign_vars(array(
					'CAT_TITLE' => $cat_title,
	
					'L_EDIT_CATEGORY' => $lang['Edit_Category'], 
					'L_EDIT_CATEGORY_EXPLAIN' => $lang['Edit_Category_explain'], 
					'L_CATEGORY' => $lang['Category'], 
					'L_FORUM_HEAD' => $lang['Forum_admin'], 
	
					'S_FIELDS' => $s_fields, 
					'S_SUBMIT_VALUE' => $buttonvalue, 
					'S_FORUM_ACTION' => append_sid('admin_forums.php'),
				));
				
				$template->pparse('body');
	
				break;
	
			case 'modcat':
				// Modify a category in the DB
				$sql = "UPDATE " . CATEGORIES . "
					SET cat_title = '" . str_replace("\'", "''", $HTTP_POST_VARS['cat_title']) . "'
					WHERE cat_id = " . intval($HTTP_POST_VARS[POST_CATEGORY_URL]);
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
	
		//		$message = $lang['Forums_updated'] . "<br><br>" . sprintf($lang['Click_return_forumadmin'], '<a href="' . append_sid("admin_forums.php') . "\">", "</a>") . "<br><br>" . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.php?pane=right") . "\">", "</a>");
	
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case 'deleteforum':
				// Show form to delete a forum
				$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);
	
				$select_to = '<select name="to_id">';
				$select_to .= "<option value=\"-1\"$s>" . $lang['Delete_all_posts'] . "</option>\n";
				$select_to .= get_list('forum', $forum_id, 0);
				$select_to .= '</select>';
	
				$buttonvalue = $lang['Move_and_Delete'];
	
				$newmode = 'movedelforum';
	
				$foruminfo = get_info('forum', $forum_id);
				$name = $foruminfo['forum_name'];
	
				$template->set_filenames(array(
					'body' => "admin/forum_delete_body.tpl")
				);
	
				$s_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="from_id" value="' . $forum_id . '" />';
	
				$template->assign_vars(array(
					'NAME' => $name, 
	
					'L_FORUM_DELETE' => $lang['Forum_delete'], 
					'L_FORUM_DELETE_EXPLAIN' => $lang['Forum_delete_explain'], 
					'L_MOVE_CONTENTS' => $lang['Move_contents'], 
					'L_FORUM_NAME' => $lang['Forum_name'], 
	
					"S_HIDDEN_FIELDS" => $s_fields,
					'S_FORUM_ACTION' => append_sid('admin_forums.php'), 
					'S_SELECT_TO' => $select_to,
					'S_SUBMIT_VALUE' => $buttonvalue)
				);
	
				$template->pparse('body');
				break;
	
			case 'movedelforum':
				//
				// Move or delete a forum in the DB
				//
				$from_id = intval($HTTP_POST_VARS['from_id']);
				$to_id = intval($HTTP_POST_VARS['to_id']);
				$delete_old = intval($HTTP_POST_VARS['delete_old']);
	
				// Either delete or move all posts in a forum
				if($to_id == -1)
				{
					// Delete polls in this forum
					$sql = "SELECT v.vote_id 
						FROM " . VOTE_DESC . " v, " . TOPICS . " t 
						WHERE t.forum_id = $from_id 
							AND v.topic_id = t.topic_id";
					if (!($result = $db->sql_query($sql)))
					{
						message(GENERAL_ERROR, "Couldn't obtain list of vote ids", "", __LINE__, __FILE__, $sql);
					}
	
					if ($row = $db->sql_fetchrow($result))
					{
						$vote_ids = '';
						do
						{
							$vote_ids .= (($vote_ids != '') ? ', ' : '') . $row['vote_id'];
						}
						while ($row = $db->sql_fetchrow($result));
	
						$sql = "DELETE FROM " . VOTE_DESC . " 
							WHERE vote_id IN ($vote_ids)";
						$db->sql_query($sql);
	
						$sql = "DELETE FROM " . VOTE_RESULTS . " 
							WHERE vote_id IN ($vote_ids)";
						$db->sql_query($sql);
	
						$sql = "DELETE FROM " . VOTE_USERS . " 
							WHERE vote_id IN ($vote_ids)";
						$db->sql_query($sql);
					}
					$db->sql_freeresult($result);
					
					include($root_path . 'includes/prune.php');
					prune($from_id, 0, true); // Delete everything from forum
				}
				else
				{
					$sql = "SELECT *
						FROM " . FORUMS . "
						WHERE forum_id IN ($from_id, $to_id)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Couldn't verify existence of forums", "", __LINE__, __FILE__, $sql);
					}
	
					if($db->sql_numrows($result) != 2)
					{
						message(GENERAL_ERROR, "Ambiguous forum ID's", "", __LINE__, __FILE__);
					}
					$sql = "UPDATE " . TOPICS . "
						SET forum_id = $to_id
						WHERE forum_id = $from_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Couldn't move topics to other forum", "", __LINE__, __FILE__, $sql);
					}
					$sql = "UPDATE " . POSTS . "
						SET	forum_id = $to_id
						WHERE forum_id = $from_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Couldn't move posts to other forum", "", __LINE__, __FILE__, $sql);
					}
					sync('forum', $to_id);
				}
	
				// Alter Mod level if appropriate - 2.0.4
				$sql = "SELECT ug.user_id 
					FROM " . AUTH_ACCESS . " a, " . USER_GROUP . " ug 
					WHERE a.forum_id <> $from_id 
						AND a.auth_mod = 1
						AND ug.group_id = a.group_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Couldn't obtain moderator list", "", __LINE__, __FILE__, $sql);
				}
	
				if ($row = $db->sql_fetchrow($result))
				{
					$user_ids = '';
					do
					{
						$user_ids .= (($user_ids != '') ? ', ' : '' ) . $row['user_id'];
					}
					while ($row = $db->sql_fetchrow($result));
	
					$sql = "SELECT ug.user_id 
						FROM " . AUTH_ACCESS . " a, " . USER_GROUP . " ug 
						WHERE a.forum_id = $from_id 
							AND a.auth_mod = 1 
							AND ug.group_id = a.group_id
							AND ug.user_id NOT IN ($user_ids)";
					if( !$result2 = $db->sql_query($sql) )
					{
						message(GENERAL_ERROR, "Couldn't obtain moderator list", "", __LINE__, __FILE__, $sql);
					}
						
					if ($row = $db->sql_fetchrow($result2))
					{
						$user_ids = '';
						do
						{
							$user_ids .= (($user_ids != '') ? ', ' : '' ) . $row['user_id'];
						}
						while ($row = $db->sql_fetchrow($result2));
	
						$sql = "UPDATE " . USERS . " 
							SET user_level = " . USER . " 
							WHERE user_id IN ($user_ids) 
								AND user_level <> " . ADMIN;
						$db->sql_query($sql);
					}
					$db->sql_freeresult($result);
	
				}
				$db->sql_freeresult($result2);
	
				$sql = "DELETE FROM " . FORUMS . "
					WHERE forum_id = $from_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Couldn't delete forum", "", __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . AUTH_ACCESS . "
					WHERE forum_id = $from_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Couldn't delete forum", "", __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . PRUNE . "
					WHERE forum_id = $from_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Couldn't delete forum prune information!", "", __LINE__, __FILE__, $sql);
				}
	
		//		$message = $lang['Forums_updated'] . "<br><br>" . sprintf($lang['Click_return_forumadmin'], '<a href="' . append_sid('admin_forums.php') . "\">", "</a>") . "<br><br>" . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.php?pane=right") . "\">", "</a>");
	
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case 'deletecat':
				//
				// Show form to delete a category
				//
				$cat_id = intval($HTTP_GET_VARS[POST_CATEGORY_URL]);
	
				$buttonvalue = $lang['Move_and_Delete'];
				$newmode = 'movedelcat';
				$catinfo = get_info('category', $cat_id);
				$name = $catinfo['cat_title'];
	
				if ($catinfo['number'] == 1)
				{
					$sql = "SELECT count(*) as total
						FROM ". FORUMS;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Couldn't get Forum count", "", __LINE__, __FILE__, $sql);
					}
					$count = $db->sql_fetchrow($result);
					$count = $count['total'];
	
					if ($count > 0)
					{
						message(GENERAL_ERROR, $lang['Must_delete_forums']);
					}
					else
					{
						$select_to = $lang['Nowhere_to_move'];
					}
				}
				else
				{
					$select_to = '<select name="to_id">';
					$select_to .= get_list('category', $cat_id, 0);
					$select_to .= '</select>';
				}
	
				$template->set_filenames(array(
					'body' => "admin/forum_delete_body.tpl")
				);
	
				$s_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="from_id" value="' . $cat_id . '" />';
	
				$template->assign_vars(array(
					'NAME' => $name, 
	
					'L_FORUM_DELETE' => $lang['Forum_delete'], 
					'L_FORUM_DELETE_EXPLAIN' => $lang['Forum_delete_explain'], 
					'L_MOVE_CONTENTS' => $lang['Move_contents'], 
					'L_FORUM_NAME' => $lang['Forum_name'], 
					
					'S_FIELDS' => $s_fields,
					'S_FORUM_ACTION' => append_sid('admin_forums.php'), 
					'S_SELECT_TO' => $select_to,
					'S_SUBMIT_VALUE' => $buttonvalue)
				);
	
				$template->pparse('body');
				break;
	
			case 'movedelcat':
				//
				// Move or delete a category in the DB
				//
				$from_id = intval($HTTP_POST_VARS['from_id']);
				$to_id = intval($HTTP_POST_VARS['to_id']);
	
				if (!empty($to_id))
				{
					$sql = "SELECT *
						FROM " . CATEGORIES . "
						WHERE cat_id IN ($from_id, $to_id)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Couldn't verify existence of categories", "", __LINE__, __FILE__, $sql);
					}
					if($db->sql_numrows($result) != 2)
					{
						message(GENERAL_ERROR, "Ambiguous category ID's", "", __LINE__, __FILE__);
					}
	
					$sql = "UPDATE " . FORUMS . "
						SET cat_id = $to_id
						WHERE cat_id = $from_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Couldn't move forums to other category", "", __LINE__, __FILE__, $sql);
					}
				}
	
				$sql = "DELETE FROM " . CATEGORIES ."
					WHERE cat_id = $from_id";
					
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Couldn't delete category", "", __LINE__, __FILE__, $sql);
				}
	
			//	$message = $lang['Forums_updated'] . "<br><br>" . sprintf($lang['Click_return_forumadmin'], '<a href="' . append_sid('admin_forums.php') . "\">", "</a>") . "<br><br>" . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.php?pane=right") . "\">", "</a>");
	
				message(GENERAL_MESSAGE, $message);
	
				break;
	
			case 'forum_order':
				//
				// Change order of forums in the DB
				//
				$move = intval($HTTP_GET_VARS['move']);
				$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);
	
				$forum_info = get_info('forum', $forum_id);
	
				$cat_id = $forum_info['cat_id'];
	
				$sql = "UPDATE " . FORUMS . "
					SET forum_order = forum_order + $move
					WHERE forum_id = $forum_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Couldn't change category order", "", __LINE__, __FILE__, $sql);
				}
	
				orders('forum', $forum_info['cat_id']);
				$show_index = TRUE;
	
				break;
				
			case 'cat_order':
				
				update(CATEGORIES, 'cat', $move, $data_cat);
				orders(CATEGORIES, -1);
				
				$show_index = TRUE;
	
				break;
	
			case 'forum_sync':
				sync('forum', intval($HTTP_GET_VARS[POST_FORUM_URL]));
				$show_index = TRUE;
	
				break;
	
			default:
				message(GENERAL_MESSAGE, $lang['No_mode']);
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	//
	// Start page proper
	//
	$template->set_filenames(array('body' => 'style/acp_forums.tpl'));
	$template->assign_block_vars('_display', array());
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['forum']),
		
		'L_CREATE_FORUM'	=> sprintf($lang['sprintf_new_creates'], $lang['forum']),
		'L_CREATE_CATEGORY'	=> sprintf($lang['sprintf_new_create'], $lang['category']),
				
	#	'L_FORUM_TITLE'		=> $lang['Forum_admin'], 
	#	'L_FORUM_EXPLAIN'	=> $lang['Forum_admin_explain'], 
	#	'L_CREATE_FORUM'	=> $lang['Create_forum'], 
	#	'L_CREATE_CATEGORY'	=> $lang['Create_category'], 
	#	'L_EDIT'			=> $lang['Edit'], 
	#	'L_DELETE'			=> $lang['Delete'], 
	#	'L_MOVE_UP'			=> $lang['move_up'], 
	#	'L_MOVE_DOWN'		=> $lang['move_down'], 
	#	'L_RESYNC'			=> $lang['Resync'],
		
		'S_ACTION'	=> append_sid('admin_forums.php'),
	));
	
	$sql = 'SELECT MAX(cat_order) AS max FROM ' . CATEGORIES;
	$result = $db->sql_query($sql);
	$max_cat = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT cat_id, cat_title, cat_order
				FROM ' . CATEGORIES . '
				ORDER BY cat_order';
	if( !$q_categories = $db->sql_query($sql) )
	{
		message(GENERAL_ERROR, "Could not query categories list", "", __LINE__, __FILE__, $sql);
	}
	
	if( $total_categories = $db->sql_numrows($q_categories) )
	{
		$category_rows = $db->sql_fetchrowset($q_categories);
		
		$sql = "SELECT *
			FROM " . FORUMS . "
			ORDER BY cat_id, forum_order";
		if(!$q_forums = $db->sql_query($sql))
		{
			message(GENERAL_ERROR, "Could not query forums information", "", __LINE__, __FILE__, $sql);
		}
	
		if( $total_forums = $db->sql_numrows($q_forums) )
		{
			$forum_rows = $db->sql_fetchrowset($q_forums);
		}
		
		//
		// Okay, let's build the index
		//
		$gen_cat = array();
	
		for($i = 0; $i < $total_categories; $i++)
		{
			$cat_id = $category_rows[$i]['cat_id'];
			
			$sql = "SELECT MAX(forum_order) AS max$cat_id FROM " . FORUMS . " WHERE cat_id = $cat_id";
			$result = $db->sql_query($sql);
			$max_forum = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
		#	'MOVE_UP'	=> ( $data[$i]['game_order'] != '10' )			? '<a href="' . append_sid('admin_games.php?mode=_order&amp;move=-15&amp;' . POST_GAMES_URL . '=' . $game_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
		#	'MOVE_DOWN'	=> ( $data[$i]['game_order'] != $maxo['max'] )	? '<a href="' . append_sid('admin_games.php?mode=_order&amp;move=15&amp;' . POST_GAMES_URL . '=' . $game_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
	
			$template->assign_block_vars('_display._cat_row', array( 
				'S_ADD_FORUM_SUBMIT'	=> "addforum[$cat_id]",
				'S_ADD_FORUM_NAME'		=> "forumname[$cat_id]",
				
				'CAT_ID'				=> $cat_id,
				'CAT_DESC'				=> $category_rows[$i]['cat_title'],
				
				'MOVE_UP'				=> ( $category_rows[$i]['cat_order'] != '10' )				? '<a href="' . append_sid('admin_forums.php?mode=cat_order&amp;move=-15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'				=> ( $category_rows[$i]['cat_order'] != $max_cat['max'] )	? '<a href="' . append_sid('admin_forums.php?mode=cat_order&amp;move=15&amp;' . POST_CATEGORY_URL . '=' . $cat_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_CAT_UPDATE'			=> append_sid("admin_forums.php?mode=_updatecat&amp;" . POST_CATEGORY_URL . "=$cat_id"),
				'U_CAT_DELETE'			=> append_sid("admin_forums.php?mode=_deletecat&amp;" . POST_CATEGORY_URL . "=$cat_id"),
				
				'U_CAT_MOVE_UP'			=> append_sid("admin_forums.php?mode=cat_order&amp;move=-15&amp;" . POST_CATEGORY_URL . "=$cat_id"),
				'U_CAT_MOVE_DOWN'		=> append_sid("admin_forums.php?mode=cat_order&amp;move=15&amp;" . POST_CATEGORY_URL . "=$cat_id"),
				'U_VIEWCAT'				=> append_sid($root_path."forum.php?" . POST_CATEGORY_URL . "=$cat_id"))
			);
	
			for($j = 0; $j < $total_forums; $j++)
			{
				$class = ($j % 2) ? 'row_class1' : 'row_class2';
				$forum_id = $forum_rows[$j]['forum_id'];

				if ( $forum_rows[$j]['cat_id'] == $cat_id )
				{
					$icon_up	= ( $forum_rows[$j]['forum_order'] != '10' ) ? $lang['move_up'] : '';
					$icon_down	= ( $forum_rows[$j]['forum_order'] != $max_forum['max'.$cat_id] ) ? $lang['move_down'] : '';
					$simple_auth = $simple_auth_types[simple_auth($forum_rows[$j])];
			
					$template->assign_block_vars('_display._cat_row._forum_row',	array(
						'CLASS'			=> $class,
						'FORUM_NAME' => $forum_rows[$j]['forum_name'],
						'FORUM_DESC' => $forum_rows[$j]['forum_desc'],
						'ROW_COLOR' => $row_color,
						'NUM_TOPICS' => $forum_rows[$j]['forum_topics'],
						'NUM_POSTS' => $forum_rows[$j]['forum_posts'],
						
						'L_MOVE_UP'		=> $icon_up,
						'L_MOVE_DOWN'		=> $icon_down,
						
						'PERMISSIONS' => $simple_auth,
						'U_FORUM_PERMISSIONS' => append_sid('admin_forumauth.php?" . POST_FORUM_URL . "=$forum_id&amp;adv=1'),
	
						'U_VIEWFORUM' => append_sid($root_path."viewforum.php?" . POST_FORUM_URL . "=$forum_id'),
						'U_FORUM_EDIT' => append_sid('admin_forums.php?mode=editforum&amp;" . POST_FORUM_URL . "=$forum_id'),
						'U_FORUM_DELETE' => append_sid('admin_forums.php?mode=deleteforum&amp;" . POST_FORUM_URL . "=$forum_id'),
						'U_FORUM_MOVE_UP' => append_sid('admin_forums.php?mode=forum_order&amp;move=-15&amp;" . POST_FORUM_URL . "=$forum_id'),
						'U_FORUM_MOVE_DOWN' => append_sid('admin_forums.php?mode=forum_order&amp;move=15&amp;" . POST_FORUM_URL . "=$forum_id'),
						'U_FORUM_RESYNC' => append_sid('admin_forums.php?mode=forum_sync&amp;" . POST_FORUM_URL . "=$forum_id"))
					);
	
				}// if ... forumid == catid
				
			} // for ... forums
	
		} // for ... categories
	
	}// if ... total_categories
}

$template->pparse('body');

include('./page_footer_admin.php');

?>