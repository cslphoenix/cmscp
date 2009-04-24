<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['bugtracker']['bugtracker'] = $filename;
	}
	return;
}
else
{
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	include($root_path . 'includes/functions_admin.php');
	
	if ( $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['bugtracker_fail']);
	}
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_match.php", true));
	}
	
	if ( isset($HTTP_POST_VARS[POST_BUGTRACKER_URL]) || isset($HTTP_GET_VARS[POST_BUGTRACKER_URL]) )
	{
		$bugtracker_id = ( isset($HTTP_POST_VARS[POST_BUGTRACKER_URL]) ) ? intval($HTTP_POST_VARS[POST_BUGTRACKER_URL]) : intval($HTTP_GET_VARS[POST_BUGTRACKER_URL]);
	}
	else
	{
		$bugtracker_id = 0;
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		$mode = '';
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
	
	$show_index = '';
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'edit':
				
				
				
				$bugtracker	= get_data('bugtracker', $bugtracker_id, 0);
				$new_mode	= 'editbugtracker';
				
				$template->set_filenames(array('body' => './../admin/style/acp_bugtracker.tpl'));
				$template->assign_block_vars('bugtracker_edit', array());
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="editbugtracker" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_BUGTRACKER_URL . '" value="' . $bugtracker_id . '" />';

				$template->assign_vars(array(
					'L_bugtracker_HEAD'		=> $lang['bugtracker_head'],
					'L_bugtracker_NEW_EDIT'	=> ($mode == 'add') ? $lang['bugtracker_add'] : $lang['bugtracker_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_bugtracker_NAME'		=> $lang['bugtracker_name'],
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					
					'bugtracker_NAME'				=> str_replace('bugtracker_', '', $bugtracker['bugtracker_name']),
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_bugtracker_ACTION'		=> append_sid("admin_bugtracker.php")
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addbugtracker':
				
				$bugtracker_name = (isset($HTTP_POST_VARS['bugtracker_name'])) ? trim($HTTP_POST_VARS['bugtracker_name']) : '';
				
				if ( $bugtracker_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back'], '');
				}
				
				$sql = 'INSERT INTO ' . bugtracker . " (bugtracker_name) VALUES ('bugtracker_" . $bugtracker_name . "')";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'ALTER TABLE ' . GROUPS . " ADD 'bugtracker_" . $bugtracker_name . "' TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_bugtracker, 'acp_bugtracker_add');
	
				$message = $lang['create_bugtracker'] . '<br><br>' . sprintf($lang['click_return_bugtracker'], '<a href="' . append_sid("admin_bugtracker.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editbugtracker':
			
				$bugtracker = get_data('bugtracker', $bugtracker_id, 0);
				
				$bugtracker_name = (isset($HTTP_POST_VARS['bugtracker_name'])) ? trim($HTTP_POST_VARS['bugtracker_name']) : '';
				
				if ( $bugtracker_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back'], '');
				}
				
				$sql = 'UPDATE ' . bugtracker . '
							SET bugtracker_name = "bugtracker_' . $bugtracker_name . '"
							WHERE bugtracker_id = ' . $bugtracker_id;
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'ALTER TABLE ' . GROUPS . " CHANGE `" . $bugtracker['bugtracker_name'] . "` `bugtracker_" . $bugtracker_name . "` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
							
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_bugtracker, 'acp_bugtracker_edit');
				
				$message = $lang['update_bugtracker'] . '<br><br>' . sprintf($lang['click_return_bugtracker'], '<a href="' . append_sid("admin_bugtracker.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $bugtracker_id && $confirm )
				{	
					$bugtracker = get_data('bugtracker', $bugtracker_id, 0);
					
					$sql = 'DELETE FROM ' . bugtracker . ' WHERE bugtracker_id = ' . $bugtracker_id;
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'ALTER TABLE ' . GROUPS . ' DROP ' . $bugtracker['bugtracker_name'];
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_bugtracker, 'acp_bugtracker_delete', $bugtracker['game_name']);
					
					$message = $lang['delete_bugtracker'] . '<br><br>' . sprintf($lang['click_return_bugtracker'], '<a href="' . append_sid("admin_bugtracker.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $bugtracker_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_BUGTRACKER_URL . '" value="' . $bugtracker_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_bugtracker'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_bugtracker.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_bugtracker']);
				}
				
				$template->pparse('body');
				
				break;
				
			default:
				message_die(GENERAL_ERROR, $lang['no_select_module'], '');
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_bugtracker.tpl'));
	$template->assign_block_vars('display', array());
	
	$s_sort = '<select class="postselect" name="sort" onchange="document.getElementById(\'bt_sort\').submit()">';
	foreach ( $lang['bt_status'] as $key => $value )
	{
		$selected = ( $sort == $key ) ? ' selected="selected"' : '';
		$s_sort .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
	}
	$s_sort .= '</select>';
	
	$template->assign_vars(array(
		'L_BT_HEAD'		=> $lang['bt_head'],
		'L_BT_EXPLAIN'	=> $lang['bt_explain'],
		'L_BT_NAME'		=> $lang['bt_name'],
		
		'L_EDIT'		=> $lang['edit'],
		'L_DELETE'		=> $lang['delete'],
		'L_SETTINGS'	=> $lang['settings'],
		
		'L_DESCRIPTION'	=> $lang['description'],
		'L_TYPE'		=> $lang['type'],
		'L_MESSAGE'		=> $lang['message'],
		'L_STATUS'		=> $lang['status'],
		
		'S_SORT'		=> $s_sort,
		
		'S_BT_ACTION'	=> append_sid("admin_bugtracker.php"),
	));
	
	foreach ( $lang['bt_status'] as $key => $value )
	{
		if ( $key == $sort )
		{
			$order_by = ( $key == 'bt_status_all' ) ? " ORDER BY bt.bugtracker_id DESC" : " WHERE bugtracker_status = '$key' ORDER BY bt.bugtracker_id DESC";
	//		$order_by = ( $key == 'bt_status_all' ) ? " ORDER BY `bt`.`bugtracker_id` $sort_order" : " WHERE bugtracker_status = '$key' ORDER BY `bt`.`bugtracker_id` $sort_order";
		}
	}
	
	$sql = 'SELECT	bt.*,
					u.user_id as user_id1, u.username as username1, u.user_color as user_color1,
					u2.user_id as user_id2, u2.username as username2, u2.user_color as user_color2
				FROM ' . BUGTRACKER . ' bt
					LEFT JOIN ' . USERS . ' u ON u.user_id = bt.bugtracker_creator
					LEFT JOIN ' . USERS . ' u2 ON u2.user_id = bt.bugtracker_worker
				' . $order_by;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$bugtracker_data = $db->sql_fetchrowset($result);
	
	if ( !$bugtracker_data )
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		/*
		CREATE TABLE IF NOT EXISTS `cms_bugtracker` (
  `bugtracker_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `bugtracker_title` varchar(150) COLLATE utf8_bin NOT NULL,
  `bugtracker_description` text COLLATE utf8_bin,
  `bugtracker_message` text COLLATE utf8_bin NOT NULL,
  `bugtracker_php` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `bugtracker_sql` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `bugtracker_creator` mediumint(8) unsigned NOT NULL,
  `bugtracker_worker` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bugtracker_type` varchar(100) COLLATE utf8_bin DEFAULT '0',
  `bugtracker_status` varchar(100) COLLATE utf8_bin DEFAULT '0',
  `bugtracker_create` int(11) unsigned NOT NULL,
  `bugtracker_update` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bugtracker_id`)
)*/
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($bugtracker_data)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
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
			
			$s_status = '<select class="postselect" name="status">';
			foreach ( $lang['bt_status'] as $key => $value )
			{
				$selected = ( $key == $bugtracker_data[$i]['bugtracker_status'] ) ? ' selected="selected"' : '';
				if ( $key != 'bt_status_all' )
				{
					$s_status .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
				}
			}
			$s_status .= '</select>';
			
			$s_type = '<select class="postselect" name="error">';
			foreach ( $lang['bt_error'] as $key => $value )
			{
				$selected = ( $key == $bugtracker_data[$i]['bugtracker_type'] ) ? ' selected="selected"' : '';
				$s_type .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
			$s_type .= '</select>';
			
			$time	= create_date($userdata['user_dateformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone']);	
			
			if ( $config['time_today'] < $bugtracker_data[$i]['bugtracker_create'])
			{ 
				$time = sprintf($lang['today_at'], create_date($config['default_timeformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone'])); 
			}
			else if ( $config['time_yesterday'] < $bugtracker_data[$i]['bugtracker_create'])
			{ 
				$time = sprintf($lang['yesterday_at'], create_date($config['default_timeformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone'])); 
			}
			
			$user	= '<span style="color:' . $bugtracker_data[$i]['user_color1'] . '">' . $bugtracker_data[$i]['username1'] . '</span>';
			$user2	= '<span style="color:' . $bugtracker_data[$i]['user_color2'] . '">' . $bugtracker_data[$i]['username2'] . '</span>';
			
			$template->assign_block_vars('display.bugtracker_row', array(
				'CLASS' 		=> $class,
				
				'BT_ID'			=> $bugtracker_data[$i]['bugtracker_id'],
				'BT_TITLE'		=> $bugtracker_data[$i]['bugtracker_title'],
				
				'BT_DATE'		=> $time,
				'BT_CREATOR'	=> $user,
				'BT_WORKER'		=> $user2,
				
				
				'BT_TYPE'		=> $bt_type,
				'BT_STATUS'		=> $bt_status,
				
				
				
				'BT_DESC'		=> $bugtracker_data[$i]['bugtracker_description'],
				'BT_MESSAGE'	=> $bugtracker_data[$i]['bugtracker_message'],
				
				'U_EDIT'		=> append_sid("admin_bugtracker.php?mode=edit&amp;" . POST_BUGTRACKER_URL . "=" . $bugtracker_data[$i]['bugtracker_id']),
				'U_DELETE'		=> append_sid("admin_bugtracker.php?mode=delete&amp;" . POST_BUGTRACKER_URL . "=" . $bugtracker_data[$i]['bugtracker_id']),
				
				'S_STATUS'		=> $s_status,
				'S_TYPE'		=> $s_type,
			));
		}
	}
	
	$current_page = ( !count($bugtracker_data) ) ? 1 : ceil( count($bugtracker_data) / $settings['site_entry_per_page'] );
	
	$template->assign_vars(array(
		'PAGINATION'	=> generate_pagination("admin_bugtracker.php?sort=$sort", count($bugtracker_data), $settings['site_entry_per_page'], $start),
		'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
	));
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>