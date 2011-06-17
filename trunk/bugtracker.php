<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_BUGTRACKER);
init_userprefs($userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ( $start < 0 ) ? 0 : $start;

if ( isset($HTTP_POST_VARS[POST_BUGTRACKER]) || isset($HTTP_GET_VARS[POST_BUGTRACKER]) )
{
	$bugtracker_id = ( isset($HTTP_POST_VARS[POST_BUGTRACKER]) ) ? intval($HTTP_POST_VARS[POST_BUGTRACKER]) : intval($HTTP_GET_VARS[POST_BUGTRACKER]);
}
else
{
	$bugtracker_id = 0;
}

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	if ( isset($HTTP_GET_VARS['add']) || isset($HTTP_POST_VARS['add']) )
	{
		$mode = 'add';
	}
	else
	{
		$mode = 'list';
	}
}

if ( isset($HTTP_GET_VARS['sort']) || isset($HTTP_POST_VARS['sort']) )
{
	$sort = ( isset($HTTP_POST_VARS['sort']) ) ? htmlspecialchars($HTTP_POST_VARS['sort']) : htmlspecialchars($HTTP_GET_VARS['sort']);
}
else
{
	$sort = 'bt_status_all';
}

if ( isset($HTTP_POST_VARS['order']) )
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if( isset($HTTP_GET_VARS['order']) )
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'DESC';
}

$s_sort = '<select class="postselect" name="sort" onchange="document.getElementById(\'bt_sort\').submit()">';
foreach ( $lang['bt_status'] as $key => $value )
{
	$selected = ( $sort == $key ) ? ' selected="selected"' : '';
	$s_sort .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
}
$s_sort .= '</select>';

$s_sort_order = '<select class="postselect" name="order">';
if ( $sort_order == 'ASC' )
{
	$s_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$s_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$s_sort_order .= '</select>';

foreach ( $lang['bt_status'] as $key => $value )
{
	if ( $key == $sort )
	{
		$order_by = ( $key == 'bt_status_all' ) ? " ORDER BY bt.bugtracker_id DESC" : " WHERE bugtracker_status = '$key' ORDER BY bt.bugtracker_id DESC";
//		$order_by = ( $key == 'bt_status_all' ) ? " ORDER BY `bt`.`bugtracker_id` $sort_order" : " WHERE bugtracker_status = '$key' ORDER BY `bt`.`bugtracker_id` $sort_order";
	}
}

$template->set_filenames(array('body' => 'body_bugtracker.tpl'));

if ( $mode == 'list' )
{
	$page_title = $lang['bugtracker'];
	include($root_path . 'includes/page_header.php');
	
	$template->assign_block_vars('list', array());
	
	$sql = 'SELECT	u.user_id as user_id1, u.user_name as user_name1, u.user_color as user_color1,
					u2.user_id as user_id2, u2.user_name as user_name2, u2.user_color as user_color2,
					bt.*
				FROM ' . BUGTRACKER . ' bt
					LEFT JOIN ' . USERS . ' u ON u.user_id = bt.bugtracker_creator
					LEFT JOIN ' . USERS . ' u2 ON u2.user_id = bt.bugtracker_worker
				' . $order_by;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$bugtracker_data = $db->sql_fetchrowset($result);
//	$bugtracker_data = _cached($sql, 'bugtracker_list');

	if ( !$bugtracker_data )
	{
		$template->assign_block_vars('list.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($bugtracker_data)); $i++)
		{
			$class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$time	= create_date($userdata['user_dateformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone']);
			if ( $config['time_today'] < $bugtracker_data[$i]['bugtracker_create'])
			{ 
				$time = sprintf($lang['today_at'], create_date($config['default_timeformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone'])); 
			}
			else if ( $config['time_yesterday'] < $bugtracker_data[$i]['bugtracker_create'])
			{ 
				$time = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone'])); 
			}
			$user	= '<a href="' . check_sid('profile.php?' . POST_USER . '=' . $bugtracker_data[$i]['user_id1']) . '" style="color:' . $bugtracker_data[$i]['user_color1'] . '">' . $bugtracker_data[$i]['user_name1'] . '</a>';
			$user2	= '<a href="' . check_sid('profile.php?' . POST_USER . '=' . $bugtracker_data[$i]['user_id2']) . '" style="color:' . $bugtracker_data[$i]['user_color1'] . '">' . $bugtracker_data[$i]['user_name2'] . '</a>';
			
			foreach ( $lang['bt_error'] as $key_t => $value_t )
			{
				if ( $key_t == $bugtracker_data[$i]['bugtracker_type'] )
				{
					$bt_type = $value_t;
				}
			}
			
			foreach ( $lang['bt_status'] as $key_s => $value_s )
			{
				if ( $key_s == $bugtracker_data[$i]['bugtracker_status'] )
				{
					$bt_status = $value_s;
				}
			}
			
			$template->assign_block_vars('list.bt_row', array(
				'CLASS' 		=> $class,
				'CLASS_STATUS'	=> $bugtracker_data[$i]['bugtracker_status'],
				
				'BT_ID'			=> $bugtracker_data[$i]['bugtracker_id'],
				'BT_TITLE'		=> $bugtracker_data[$i]['bugtracker_title'],
				'BT_DESC'		=> $bugtracker_data[$i]['bugtracker_description'],
				'BT_MESSAGE'	=> $bugtracker_data[$i]['bugtracker_message'],
				'BT_CREATE'		=> sprintf($lang['bt_create_by'], $user, $time),
				
				'BT_WORKER'		=> ( $bugtracker_data[$i]['bugtracker_worker'] ) ? $user2 : $lang['bt_unassigned'],
//				'BT_EDIT'		=> '<a href="' . check_sid('bugtracker.php?mode=edit&amp;' . POST_BUGTRACKER . '=' . $bugtracker_data[$i]['bugtracker_id']) . '">edit ' . $bugtracker_data[$i]['user_name2'] . '</a>',
				
				'BT_TYPE'		=> $bt_type,
				'BT_STATUS'		=> $bt_status,
				
				'U_DETAILS'		=> check_sid('bugtracker.php?mode=details&amp;' . POST_BUGTRACKER . '=' . $bugtracker_data[$i]['bugtracker_id']),
				
			));
		}
	}
	
	$current_page = ( !count($bugtracker_data) ) ? 1 : ceil( count($bugtracker_data) / $settings['site_entry_per_page'] );
	
	$template->assign_vars(array(
		'L_GOTO_PAGE'			=> $lang['Goto_page'],
		'L_BUGTRACKER'			=> $lang['bugtracker'],
		'L_ASSIGNED'			=> $lang['bt_assigned'],
		'L_STATUS_TYPE'			=> $lang['bt_status_type'],
		
		'PAGINATION'			=> generate_pagination("bugtracker.php?sort=$sort&amp;order=$sort_order", count($bugtracker_data), $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER'			=> sprintf($lang['common_page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
		
		'S_BUGTRACKER_ORDER'	=> $s_sort_order,
		'S_BUGTRACKER_SORT'		=> $s_sort,
		'S_BUGTRACKER_ACTION'	=> check_sid('bugtracker.php'),
	));
}
else if ( ( $mode == 'add' || ( $mode == 'edit' && $bugtracker_id ) ) )
{
	include($root_path . 'includes/functions_post.php');
	include($root_path . 'includes/functions_bugtracker.php');
	
	if ( !$userdata['session_logged_in'] )
	{
		redirect(check_sid("login.php?redirect=bugtracker.php?mode=$mode"));
	}
	
	$page_title = $lang['bugtracker'];
	include($root_path . 'includes/page_header.php');
	
	$template->assign_block_vars('entry', array());
	
	$bt_type = $bt_version = '';
	$s_hidden_field = '<input type="hidden" name="mode" value="add" />';
	
	if ( $mode == 'edit' )
	{
		$sql = 'SELECT *
					FROM ' . BUGTRACKER . '
					WHERE bugtracker_id = ' . $bugtracker_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$bugtracker_data = $db->sql_fetchrow($result);
		
		$template->assign_vars(array(
			'BT_TITLE'		=> $bugtracker_data['bugtracker_title'],
			'BT_DESC'		=> $bugtracker_data['bugtracker_description'],
			'BT_PHP'		=> $bugtracker_data['bugtracker_php'],
			'BT_SQL'		=> $bugtracker_data['bugtracker_sql'],
			'BT_MESSAGE'	=> $bugtracker_data['bugtracker_message'],
			
		));
		
		$bt_type	= $bugtracker_data['bugtracker_type'];
		$bt_version	= $bugtracker_data['bugtracker_version'];
		
		$s_hidden_field = '<input type="hidden" name="mode" value="edit" />';
		$s_hidden_field .= '<input type="hidden" name="' . POST_BUGTRACKER . '" value="' . $bugtracker_id . '" />';
	}
	
	if ( isset($HTTP_POST_VARS['submit']) )
	{
		$error = '';
		$error_msg = '';
		
		$bt_title	= ( isset($HTTP_POST_VARS['bt_title']) )	? trim($HTTP_POST_VARS['bt_title']) : '';
		$bt_desc	= ( isset($HTTP_POST_VARS['bt_desc']) )		? trim($HTTP_POST_VARS['bt_desc']) : '';
		$bt_type	= ( isset($HTTP_POST_VARS['bt_type']) )		? trim($HTTP_POST_VARS['bt_type']) : '';
		$bt_version	= ( isset($HTTP_POST_VARS['bt_version']) )	? trim($HTTP_POST_VARS['bt_version']) : '';
		$bt_php		= ( isset($HTTP_POST_VARS['bt_php']) )		? trim($HTTP_POST_VARS['bt_php']) : '';
		$bt_sql		= ( isset($HTTP_POST_VARS['bt_sql']) )		? trim($HTTP_POST_VARS['bt_sql']) : '';
		$bt_message	= ( isset($HTTP_POST_VARS['bt_message']) )	? trim($HTTP_POST_VARS['bt_message']) : '';
		
		$template->assign_vars(array(
			'BT_TITLE'		=> $bt_title,
			'BT_DESC'		=> $bt_desc,
			'BT_PHP'		=> $bt_php,
			'BT_SQL'		=> $bt_sql,
			'BT_MESSAGE'	=> $bt_message,
		));
		
		if ( empty($bt_title) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_empty_title'];
		}
		
		if ( empty($bt_desc) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_desc'];
		}
			
		if ( empty($bt_type) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_type'];
		}
		
		if ( empty($bt_version) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_version'];
		}
		
		if ( empty($bt_message) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['msg_select_message'];
		}

		if ( $error )
		{
			$template->set_filenames(array('reg_header' => 'error_body.tpl'));
			$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
			$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
		}

		if ( !$error )
		{
		//	$oCache -> deleteCache('bugtracker_list');
			if ( $mode == 'add' )
			{
				bt_add($userdata['user_id'], $bt_title, $bt_desc, $bt_type, $bt_php, $bt_sql, $bt_message);
			}
			else
			{
				bt_edit($bugtracker_id, $bt_title, $bt_desc, $bt_type, $bt_php, $bt_sql, $bt_message);
			}
			
			$message = ( $mode == 'add' ) ? $lang['bt_add'] : $lang['bt_edit'];
			$message .= '<br><br>' . sprintf($lang['click_return_bugtracker'],  '<a href="' . check_sid('bugtracker.php') . '">', '</a>');
			message(GENERAL_MESSAGE, $message);
		}
	}
	
	$template->assign_vars(array(
		'L_HEAD_ADD_EDIT'		=> ( $mode == 'add' ) ? $lang['bt_head_add'] : $lang['bt_head_edit'],
		'L_REQUIRED'			=> $lang['required'],
		'L_TITLE'				=> $lang['bt_title'],
		'L_DESC'				=> $lang['bt_desc'],
		'L_TYPE'				=> $lang['bt_type'],
		'L_VERSION'				=> $lang['bt_version'],
		'L_PHP'					=> $lang['bt_php'],
		'L_SQL'					=> $lang['bt_sql'],
		'L_MESSAGE'				=> $lang['bt_message'],
		
		'L_SUBMIT'				=> $lang['Submit'],
		
		'S_TYPE'				=> bt_type($bt_type),
		'S_VERSION'				=> bt_version($bt_version),
		'S_HIDDEN_FIELD'		=> $s_hidden_field,
		'S_BUGTRACKER_ACTION'	=> check_sid('bugtracker.php'),
	));
}
else if ( $mode == 'details' && $bugtracker_id )
{
	$page_title = $lang['bt_details'];
	include($root_path . 'includes/page_header.php');
	
	$template->assign_block_vars('details', array());
	
	$sql = 'SELECT	u.user_id as user_id1, u.user_name as user_name1, u.user_color as user_color1,
					u2.user_id as user_id2, u2.user_name as user_name2, u2.user_color as user_color2,
					bt.*
				FROM ' . BUGTRACKER . ' bt
					LEFT JOIN ' . USERS . ' u ON u.user_id = bt.bugtracker_creator
					LEFT JOIN ' . USERS . ' u2 ON u2.user_id = bt.bugtracker_worker
				WHERE bt.bugtracker_id = ' . $bugtracker_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$details = $db->sql_fetchrow($result);
	
	if ( !$details )
	{
		message(GENERAL_ERROR, 'Falsche ID ?');
	}
	
	$template->assign_block_vars('details.bt_comment', array());
	
	$sql = 'SELECT btc.*, u.user_name, u.user_color, u.user_email
				FROM ' . BUGTRACKER_COMMENTS . ' btc
					LEFT JOIN ' . USERS . ' u ON btc.poster_id = u.user_id
				WHERE bugtracker_id = ' . $bugtracker_id . '
			ORDER BY time_create DESC';
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$comment_entry = $db->sql_fetchrowset($result);
//	$comment_entry = _cached($sql, 'bugtracker_details_' . $bugtracker_id . '_comments');
	
	if ( !$comment_entry )
	{
		$template->assign_block_vars('details.bt_comment.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
		
		$last_entry = array('poster_ip' => '', 'time_create' => '');
	}
	else
	{
		if ( $userdata['session_logged_in'] )
		{
			$sql = 'SELECT read_time
						FROM ' . BUGTRACKER_COMMENTS_READ . '
						WHERE user_id = ' . $userdata['user_id'] . '
							AND bugtracker_id = ' . $bugtracker_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$unread = $db->sql_fetchrow($result);
			
			if ( $db->sql_numrows($result) )
			{
				$unreads = false;
				
				$sql = 'UPDATE ' . BUGTRACKER_COMMENTS_READ . '
							SET read_time = ' . time() . '
						WHERE bugtracker_id = ' . $bugtracker_id . ' AND user_id = ' . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
			else
			{
				$unreads = true;
				
				$sql = 'INSERT INTO ' . BUGTRACKER_COMMENTS_READ . ' (bugtracker_id, user_id, read_time)
					VALUES (' . $bugtracker_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
		}
		
		for ( $i = $start; $i < min($settings['site_comment_per_page'] + $start, count($comment_entry)); $i++ )
		{
			$class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			if ( $userdata['session_logged_in'] )
			{
				$icon = ( $unreads || $unread['read_time'] < $comment_entry[$i]['time_create'] ) ? $images['icon_minipost_new'] : $images['icon_minipost'];
			}
			else
			{
				$icon = $images['icon_minipost'];
			}

			$comment = html_entity_decode($comment_entry[$i]['poster_text'], ENT_QUOTES);
			$userurl = '<a href="' . check_sid('profile.php?mode=details&amp;' . POST_USER . '=' . $row['user_id']) . '"' . $comment_entry[$i]['user_color'] .'>' . $comment_entry[$i]['user_name'] . '</a>';

			$template->assign_block_vars('details.bt_comment.row', array(
				'CLASS' 		=> $class,
				'ID' 			=> $comment_entry[$i]['bugtracker_comments_id'],
				'USER'		=> $userurl,
				'MESSAGE'		=> $comment,
				'DATE'			=> create_date($userdata['user_dateformat'], $comment_entry[$i]['time_create'], $userdata['user_timezone']),
				'ICON'			=> $icon,

				'U_EDIT'		=> check_sid('match.php?mode=edit&amp;' . POST_MATCH . '=' . $comment_entry[$i]['bugtracker_id']),
				'U_DELETE'		=> check_sid('match.php?mode=delete&amp;' . POST_MATCH . '=' . $comment_entry[$i]['bugtracker_id'])
			));
		}
	
		$current_page = ( !count($comment_entry) ) ? 1 : ceil( count($comment_entry) / $settings['site_comment_per_page'] );
		
		$template->assign_vars(array(
			'L_GOTO_PAGE'	=> $lang['Goto_page'],
			'PAGINATION'	=> generate_pagination('bugtracker.php?mode=details&amp;' . POST_BUGTRACKER . '=' . $bugtracker_id, count($comment_entry), $settings['site_comment_per_page'], $start),
			'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['site_comment_per_page'] ) + 1 ), $current_page ), 
		));
		
		sort($comment_entry);
		$last_entry = array_pop($comment_entry);
	}
		
	if ( $userdata['session_logged_in' ] )
	{
		$template->assign_block_vars('details.bt_comment.add', array());
	}
		
	$error = '';
	$error_msg = '';
	
	if ( isset($HTTP_POST_VARS['submit']) && ( $last_entry['poster_ip'] != $userdata['session_ip'] || $last_entry['time_create']+20 < time() ) )
	{
		include($root_path . 'includes/functions_post.php');
		
		$comment		= (!$userdata['session_logged_in']) ? trim($HTTP_POST_VARS['comment']) : '';
		
		$template->assign_vars(array(
			'COMMENT'		=> $comment,
		));
		
		if ( empty($HTTP_POST_VARS['comment']) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . 'comment';
		}

		if ( $error )
		{
			$template->set_filenames(array('reg_header' => 'error_body.tpl'));
			$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
			$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
		}

		if ( !$error )
		{
			$sql = 'SELECT *
						FROM ' . BUGTRACKER_COMMENTS_READ . '
						WHERE bugtracker_id = ' . $bugtracker_id . '
							AND user_id = ' . $userdata['user_id'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			if ( $db->sql_numrows($result) )
			{
				$sql = 'UPDATE ' . BUGTRACKER_COMMENTS_READ . '
							SET read_time = ' . time() . '
						WHERE bugtracker_id = ' . $bugtracker_id . ' AND user_id = ' . $userdata['user_id'];					
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
			else
			{				
				$sql = 'INSERT INTO ' . BUGTRACKER_COMMENTS_READ . ' (bugtracker_id, user_id, read_time)
					VALUES (' . $bugtracker_id . ', ' . $userdata['user_id'] . ', ' . time() . ')';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}

			$oCache -> deleteCache('bugtracker_details_' . $bugtracker_id . '_comments');
			_comment_message('add', 'bugtracker', $bugtracker_id, $userdata['user_id'], $user_ip, $HTTP_POST_VARS['comment']);
			
			$message = $lang['add_comment'] . sprintf($lang['click_return_bugtracker'],  '<a href="' . check_sid('bugtracker.php?mode=details&amp;' . POST_BUGTRACKER . '=' . $bugtracker_id) . '">', '</a>');
			message(GENERAL_MESSAGE, $message);
		}
	}
	
	$template->assign_vars(array(
								 
		'L_COMMENT'				=> $lang['common_comment'],
		'L_COMMENTS'			=> $lang['common_comments'],
		'L_COMMENT_ADD'			=> $lang['common_comment_add'],
		
		'L_INFO'				=> sprintf($lang['bt_details_to'], $details['bugtracker_title']),
		
//		'L_SUBMIT'				=> $lang['Submit'],
//		
//		'MATCH_RIVAL'			=> $details['bugtracker_rival'],
//		'U_MATCH_RIVAL'		=> $details['bugtracker_rival_url'],
//		'MATCH_RIVAL'		=> $details['bugtracker_rival_url'],
//		'MATCH_RIVAL_TAG'		=> $details['bugtracker_rival_tag'],
//		
//	
//		'MATCH_CATEGORIE'		=> $bugtracker_categorie,
//		'MATCH_TYPE'			=> $bugtracker_type,
//		'MATCH_LEAGUE_INFO'		=> $bugtracker_league,
//		'SERVER'				=> ($details['server']) ? '<a href="hlsw://' . $details['server'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
//		'SERVER_PW'				=> ($userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN ) ? $details['server_pw'] : '',
//		'HLTV'					=> ($details['server']) ? '<a href="hlsw://' . $details['server_hltv'] . '">' . $lang['hlsw'] . '</a>' : ' - ',
//		'HLTV_PW'				=> ($userdata['user_level'] == TRIAL || $userdata['user_level'] == MEMBER || $userdata['user_level'] == ADMIN ) ? $details['server_hltv_pw'] : '',
//		
//		
//		
//		'DETAILS_COMMENT'		=> $details['details_comment'],
//		
//		'MATCH_MAIN'			=> '<a href="' . check_sid('match.php') . '">&raquo; Übersicht</a>',
//	
//		'S_HIDDEN_FIELDB'		=> $s_hidden_fieldb,
//		'S_MATCH_ACTION'		=> check_sid('match.php?mode=details&amp;' . POST_MATCH . '=' . $bugtracker_id)
	));
}
else
{
	redirect(check_sid('bugtracker.php', true));
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>