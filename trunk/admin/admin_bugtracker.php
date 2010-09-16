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
	
	if ( $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_development']['_submenu_bugtracker'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
		$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	$start			= ( request('start') ) ? request('start') : 0;
	$start			= ( $start < 0 ) ? 0 : $start;
	$sort			= ( request('sort', 1) ) ? request('sort', 1) : 'bugtracker_status_all';
	$bugtracker_id	= request(POST_BUGTRACKER_URL, 0);
	$mode			= request('mode');
	$move			= request('move');
	$confirm		= request('confirm');
	$show_index		= '';
	
	if ( $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $no_header ) ? redirect('admin/' . append_sid('admin_match.php', true)) : false;
	
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
		case '_detail':
			
			$template->set_filenames(array('body' => 'style/acp_bugtracker.tpl'));
			$template->assign_block_vars('detail', array());
			
			$sql = "SELECT	bt.*,
							u1.user_id as user_id1, u1.username as user_name1, u1.user_color as user_color1,
							u2.user_id as user_id2, u2.username as user_name2, u2.user_color as user_color2
						FROM " . BUGTRACKER . " bt
							LEFT JOIN " . USERS . " u1 ON u1.user_id = bt.bugtracker_creator
							LEFT JOIN " . USERS . " u2 ON u2.user_id = bt.bugtracker_editor
						WHERE bugtracker_id = $bugtracker_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$bugtracker = $db->sql_fetchrow($result);
			
			debug($bugtracker);
			
			$s_type = '<select class="postselect" name="bugtracker_editor_type" id="bugtracker_editor_type">';
			foreach ( $lang['bugtracker_error'] as $key => $value )
			{
				$selected = ( $key == $bugtracker['bugtracker_editor_type'] ) ? ' selected="selected"' : '';
				$s_type .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
			$s_type .= '</select>';
			
			$s_status = '<select class="postselect" name="bugtracker_editor_status" id="bugtracker_editor_status">';
			foreach ( $lang['bugtracker_status'] as $key => $value )
			{
				$selected = ( $key == $bugtracker['bugtracker_editor_status'] ) ? ' selected="selected"' : '';
				if ( $key != 'bugtracker_status_all' )
				{
					$s_status .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
				}
			}
			$s_status .= '</select>';
			
			$s_fields = '<input type="hidden" name="mode" value="_detail_save" /><input type="hidden" name="' . POST_BUGTRACKER_URL . '" value="' . $bugtracker_id . '" />';

			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['bugtracker']),
				'L_PROC'		=> sprintf($lang['sprintf_processing'], $lang['bugtracker_field']),
				
				'L_TITLE'		=> $lang['bugtracker_field'],
				
				'L_TYPE'		=> $lang['bugtracker_type'],
				'L_STATUS'		=> $lang['bugtracker_modus'],
				'L_DESC'		=> $lang['common_desc'],
				'L_MESSAGE'		=> $lang['common_message'],
				'L_PHP_SQL'		=> $lang['bugtracker_php_sql'],
				'L_CREATOR'		=> $lang['bugtracker_creator'],
				'L_EDITOR'		=> $lang['bugtracker_editor'],
				'L_VERSION'		=> $lang['bugtracker_version'],
				'L_REPORT'		=> $lang['bugtracker_report'],

				'L_RESET'		=> $lang['common_reset'],
				'L_SUBMIT'		=> $lang['common_submit'],
				
				'TITLE'			=> $bugtracker['bugtracker_title'],
				'TYPE'			=> $lang['bugtracker_error'][$bugtracker['bugtracker_creator_type']],
				'STATUS'		=> $lang['bugtracker_status'][$bugtracker['bugtracker_creator_status']],
				'DESC'			=> $bugtracker['bugtracker_desc'],
				'MESSAGE'		=> $bugtracker['bugtracker_message'],
				'PHP_SQL'		=> $bugtracker['bugtracker_php'] . ' / ' . $bugtracker['bugtracker_sql'],
				'CREATOR'		=> '<span style="color:' . $bugtracker['user_color1'] . '">' . $bugtracker['user_name1'] . '</span>',
				'DATE'			=> create_date($userdata['user_dateformat'], $bugtracker['bugtracker_create'], $userdata['user_timezone']),
				'DATE_CHANGE'	=> ( $bugtracker['bugtracker_update'] ) ? sprintf($lang['bugtracker_change'], create_date($userdata['user_dateformat'], $bugtracker['bugtracker_create'], $userdata['user_timezone'])) : '',
				'EDITOR'		=> ( $bugtracker['user_name2'] ) ? '<span style="color:' . $bugtracker['user_color2'] . '">' . $bugtracker['user_name2'] . '</span>' : '',
				'VERSION'		=> $bugtracker['bugtracker_version'],
				
				
				
				'S_TYPE'		=> $s_type,
				'S_STATUS'		=> $s_status,
				
				'S_FIELDS'		=> $s_fields,
				'S_ACTION'		=> append_sid('admin_bugtracker.php'),
			));
		
			break;

		default:
		
			$template->set_filenames(array('body' => 'style/acp_bugtracker.tpl'));
			$template->assign_block_vars('_display', array());
			
			$s_sort = '<select class="postselect" name="sort" onchange="document.getElementById(\'bugtracker_sort\').submit()">';
			foreach ( $lang['bugtracker_status'] as $key => $value )
			{
				$selected = ( $sort == $key ) ? ' selected="selected"' : '';
				$s_sort .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
			$s_sort .= '</select>';
			
			foreach ( $lang['bugtracker_status'] as $key => $value )
			{
				if ( $key == $sort )
				{
					$order_by = ( $key == 'bugtracker_status_all' ) ? " ORDER BY bt.bugtracker_id DESC" : " WHERE bugtracker_status = '$key' ORDER BY bt.bugtracker_id DESC";
				}
			}
			
			$sql = "SELECT	bt.bugtracker_id, bt.bugtracker_title, bt.bugtracker_creator_type, bt.bugtracker_creator_status, bt.bugtracker_editor_type, bt.bugtracker_editor_status, bt.bugtracker_create,
							u1.user_id as user_id1, u1.username as user_name1, u1.user_color as user_color1,
							u2.user_id as user_id2, u2.username as user_name2, u2.user_color as user_color2
						FROM " . BUGTRACKER . " bt
							LEFT JOIN " . USERS . " u1 ON u1.user_id = bt.bugtracker_creator
							LEFT JOIN " . USERS . " u2 ON u2.user_id = bt.bugtracker_editor
						$order_by";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$bugtracker_data = $db->sql_fetchrowset($result);
			
			if ( $bugtracker_data )
			{
				for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($bugtracker_data)); $i++ )
				{
					$bugtracker_id = $bugtracker_data[$i]['bugtracker_id'];

					$bugtracker_type	= ( $bugtracker_data[$i]['bugtracker_editor_type'] )	? 'bugtracker_editor_type' : 'bugtracker_creator_type';
					$bugtracker_status	= ( $bugtracker_data[$i]['bugtracker_editor_status'] )	? 'bugtracker_editor_status' : 'bugtracker_creator_status';

					$template->assign_block_vars('display.row_bugtracker', array(
						'CLASS' 	=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
						
						'TITLE'		=> $bugtracker_data[$i]['bugtracker_title'],
						'TYPE'		=> $lang['bugtracker_error'][$bugtracker_data[$i][$bugtracker_type]],
						'STATUS'	=> $lang['bugtracker_status'][$bugtracker_data[$i][$bugtracker_status]],
						'DATE'		=> create_date($userdata['user_dateformat'], $bugtracker_data[$i]['bugtracker_create'], $userdata['user_timezone']),
						'CREATOR'	=> '<span style="color:' . $bugtracker_data[$i]['user_color1'] . '">' . $bugtracker_data[$i]['user_name1'] . '</span>',
						'WORKER'	=> '<span style="color:' . $bugtracker_data[$i]['user_color2'] . '">' . $bugtracker_data[$i]['user_name2'] . '</span>',
						
						'U_DELETE'	=> append_sid('admin_bugtracker.php?mode=_delete&amp;' . POST_BUGTRACKER_URL . '=' . $bugtracker_id),
						'U_DETAIL'	=> append_sid('admin_bugtracker.php?mode=_detail&amp;' . POST_BUGTRACKER_URL . '=' . $bugtracker_id),
					));
				}
			}
			else
			{
				$template->assign_block_vars('_display._no_entry', array());
				$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
			}
			
			$current_page = ( !count($bugtracker_data) ) ? 1 : ceil( count($bugtracker_data) / $settings['site_entry_per_page'] );
			
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['bugtracker']),
				'L_NAME'		=> $lang['bugtracker_fields'],
				'L_EXPLAIN'		=> $lang['bugtracker_explain'],
				
				'L_DELETE'		=> $lang['common_delete'],
				'L_DETAIL'		=> $lang['common_details'],
				'L_SETTINGS'	=> $lang['common_settings'],
				
				'PAGINATION'	=> generate_pagination("admin_bugtracker.php?sort=$sort", count($bugtracker_data), $settings['site_entry_per_page'], $start),
				'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ),
				
				'S_SORT'		=> $s_sort,
				'S_ACTION'		=> append_sid('admin_bugtracker.php'),
			));
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}
?>