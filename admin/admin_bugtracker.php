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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

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
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_match.php', true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	$bugtracker_id = request(POST_BUGTRACKER_URL);
		
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
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
	
	switch ( $mode )
	{
		default:
		
			$template->set_filenames(array('body' => 'style/acp_bugtracker.tpl'));
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
				
				'L_EDIT'		=> $lang['common_edit'],
				'L_DELETE'		=> $lang['common_delete'],
				'L_SETTINGS'	=> $lang['settings'],
				
				'L_DESCRIPTION'	=> $lang['description'],
				'L_TYPE'		=> $lang['type'],
				'L_MESSAGE'		=> $lang['message'],
				'L_STATUS'		=> $lang['status'],
				
				'S_SORT'		=> $s_sort,
				
				'S_BT_ACTION'	=> append_sid('admin_bugtracker.php'),
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
						
						'U_EDIT'		=> append_sid('admin_bugtracker.php?mode=edit&amp;' . POST_BUGTRACKER_URL . '=' . $bugtracker_data[$i]['bugtracker_id']),
						'U_DELETE'		=> append_sid('admin_bugtracker.php?mode=delete&amp;' . POST_BUGTRACKER_URL . '=' . $bugtracker_data[$i]['bugtracker_id']),
						
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
			
			break;
	}
	include('./page_footer_admin.php');
}
?>