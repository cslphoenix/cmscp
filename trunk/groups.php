<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_GROUPS);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_GROUPS;
$url	= POST_GROUPS;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, INT);
$sid	= request('sid', 1);
$mode	= request('mode', TXT);
$cancel	= request('cancel', 1);
$confirm= request('confirm', TXT);

//	Link
$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($config['page_path']));
$script_name = ( $script_name != '' ) ? $script_name . '/groups.php' : 'groups.php';
$server_name = trim($config['server_name']);
$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';
$server_url = $server_protocol . $server_name . $server_port . $script_name;

$is_moderator = false;

$template->set_filenames(array('body' => 'body_groups.tpl'));

if ( $data )
{
	$template->assign_block_vars('view', array());
	
	$sql = "SELECT * FROM " . GROUPS . " ORDER BY group_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$groups = $db->sql_fetchrowset($result);
#	$groups = _cached($sql, 'data_groups');
	
	foreach ( $groups as $key => $row )
	{
		if ( $row['group_id'] == $data )
		{
			$view = $row;
		}
	}
	
	if ( !$view )
	{
		message(GENERAL_ERROR, $lang['msg_group_fail']);
	}
	
	$page_title = sprintf($lang['header_sprintf'], $lang['header_group'], $view['group_name']);
	
	main_header();
	
	$sql = "SELECT u.user_name, u.user_id, u.user_viewemail, u.user_posts, u.user_regdate, u.user_email, gu.user_pending, gu.group_mod
				FROM " . USERS . " u, " . GROUPS_USERS . " gu
			WHERE gu.group_id = $data AND gu.user_id = u.user_id
			ORDER BY u.user_name";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$members = $db->sql_fetchrowset($result);
	
	$ary_mod = array();
	$ary_mem = array();
	$ary_pen = array();
	
	if ( $members )
	{
		foreach ( $members as $key => $row )
		{
			if ( $row['group_mod'] )
			{
				$ary_mod[] = $row;
			}
			else if ( $row['user_pending'] )
			{
				$ary_pen[] = $row;
			}
			else
			{
				$ary_mem[] = $row;
			}
			
			$ary_in[] = $row['user_id'];
		}
	}
	
	$is_member	= '';
	$is_pending = '';
	
	if ( $ary_mod )
	{
		for ( $i = 0; $i < count($ary_mod); $i++ )
		{
			if ( $userdata['session_logged_in'] && $ary_mod[$i]['user_id'] == $userdata['user_id'] )
			{
				$is_moderator = true;
			}
		}
	}
	
	if ( $ary_mem )
	{
		for ( $i = 0; $i < count($ary_mem); $i++ )
		{
			if ( $userdata['session_logged_in'] && $ary_mem[$i]['user_id'] == $userdata['user_id'] )
			{
				$is_member = true;
			}
		}
	}

	if ( $ary_pen )
	{
		for ( $i = 0; $i < count($ary_pen); $i++)
		{
			if ( $userdata['session_logged_in'] && $ary_pen[$i]['user_id'] == $userdata['user_id'] )
			{
				$is_pending = true;
			}
		}
	}

#	if ( request('validate', 1) )
#	{
#		if ( !$userdata['session_logged_in'] )
#		{
#			redirect(check_sid('login.php?redirect=groups.php&' . POST_GROUPS . '=' . $data, true));
#		}
#	}
	
#	$sql = "SELECT user_id FROM " . GROUPS_USERS . " WHERE group_mod = 1 AND group_id = $data";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'Error getting user list for group', '', __LINE__, __FILE__, $sql);
#	}
#	$group_mods = $db->sql_fetchrowset($result);

	if ( request('add', 1) || request('approve', 1) || request('deny', 1) || $mode == 'remove' || $mode == 'change_level' )
	{
		if ( !$userdata['session_logged_in'] )
		{
			redirect(check_sid('login.php?redirect=groups.php&' . POST_GROUPS . '=' . $data, true));
		} 
		else if ( $sid !== $userdata['session_id'] )
		{
			message(GENERAL_ERROR, $lang['Session_invalid']);
		}

		if ( $userdata['user_level'] != ADMIN && !$is_moderator )
		{
		#	$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('index.php') . '">'));

			$msg = $lang['Not_group_moderator'] . '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . check_sid('index.php') . '">', '</a>');
			
			message(GENERAL_MESSAGE, $msg);
		}

		if ( request('add', 1) )
		{
			$user_new_id = ( request('user_id', 0) ) ? request('user_id', 0) : '';
			
			$sql = "SELECT user_id, user_email, user_lang FROM " . USERS . " WHERE user_id = $user_new_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$tmp_user = $db->sql_fetchrow($result);
			
			$user_id	= $tmp_user['user_id'];
			$user_email	= $tmp_user['user_email'];
			$user_lang	= $tmp_user['user_lang'];
			
			$sql = "INSERT INTO " . GROUPS_USERS . " (user_id, group_id, user_pending) VALUES ($user_id, $data, 0)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			group_set_auth($user_id, $data);

			$group_name = $view['group_name'];

		#	include($root_path . 'includes/class_emailer.php');
		#	$emailer = new emailer($config['smtp_delivery']);

		#	$emailer->from($config['page_email']);
		#	$emailer->replyto($config['page_email']);

		#	$emailer->use_template('group_added', $user_lang);
		#	$emailer->email_address($user_email);
		#	$emailer->set_subject($lang['Group_added']);

		#	$emailer->assign_vars(array(
		#		'SITENAME' => $config['page_name'], 
		#		'GROUP_NAME' => $group_name,
		#		'EMAIL_SIG' => (!empty($config['page_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $config['page_email_sig']) : '', 
		#		'U_GROUPCP' => $server_url . '?' . POST_GROUPS . "=$data")
		#	);
		#	$emailer->send();
		#	$emailer->reset();
		}
		else if ( $mode == 'change_level' )
		{
			$members_select = array();
			$members_mark = count($HTTP_POST_VARS['members']);
			
			for ( $i = 0; $i < $members_mark; $i++ )
			{
				if ( intval($HTTP_POST_VARS['members'][$i]) )
				{
					$members_select[] = intval($HTTP_POST_VARS['members'][$i]);
				}
			}
			
			if ( count($members_select) > 0 )
			{
				$user_ids = implode(', ', $members_select);
				
				$sql = 'SELECT user_id
							FROM ' . GROUPS_USERS . '
							WHERE group_id = ' . $data . '
								AND group_mod = 1
								AND user_id IN (' . $user_ids . ')';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$group_mods = array();
				while ( $row = $db->sql_fetchrow($result) )
				{
					$group_mods[] = $row['user_id'];
				}
				$db->sql_freeresult($result);

				if ( count($group_mods) > 0)
				{
					$sql = 'UPDATE ' . GROUPS_USERS . '
								SET group_mod = 0
								WHERE group_id = ' . intval($data) . '
									AND user_id IN (' . implode(', ', $group_mods) . ')';
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				$sql_in = ( empty($group_mods ) ? '' : ' AND NOT user_id IN (' . implode(', ', $group_mods) . ')');
				
				$sql = 'UPDATE ' . GROUPS_USERS . '
							SET group_mod = 1
							WHERE group_id = ' . intval($data) . '
								AND user_id IN (' . implode(', ', $members_select) . ')' . $sql_in;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}

				
//					$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('groups.php?' . POST_GROUPS . '=' . $data) . '">'));
				
				$message = $lang['group_set_mod']
					. '<br><br>' . sprintf($lang['Click_return_group'], '<a href="' . check_sid('groups.php?' . POST_GROUPS . '=' . $data) . '">', '</a>')
					. '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . check_sid('index.php') . '">', '</a>');
				message(GENERAL_MESSAGE, $message);

			}
		}
		else 
		{
			if ( ( ( isset($HTTP_POST_VARS['approve']) || isset($HTTP_POST_VARS['deny']) ) && isset($HTTP_POST_VARS['pending_members']) ) || ( $mode == 'remove' && isset($HTTP_POST_VARS['members']) ) )
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
				else if ( isset($HTTP_POST_VARS['deny']) || $mode == 'remove' )
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

					$group_name = $view['group_name'];

					include($root_path . 'includes/class_emailer.php');
					$emailer = new emailer($config['smtp_delivery']);

					$emailer->from($config['page_email']);
					$emailer->replyto($config['page_email']);

					for ($i = 0; $i < count($bcc_list); $i++)
					{
						$emailer->bcc($bcc_list[$i]);
					}

					$emailer->use_template('group_approved');
					$emailer->set_subject($lang['Group_approved']);

					$emailer->assign_vars(array(
						'SITENAME' => $config['page_name'], 
						'GROUP_NAME' => $group_name,
						'EMAIL_SIG' => (!empty($config['board_email_sig'])) ? str_replace('<br>', "\n", "-- \n" . $config['board_email_sig']) : '', 

						'U_GROUPCP' => $server_url . '?' . POST_GROUPS . "=$data")
					);
					$emailer->send();
					$emailer->reset();
				}
			}
		}
	}
	
	if ( $is_moderator )
	{
		if ( $view['group_type'] != GROUP_SYSTEM )
		{
			$template->assign_block_vars('view.switch_unsubscribe_group_input', array());
		}
		
		$group_details = $lang['Are_group_moderator'];
		$s_fields = '<input type="hidden" name="' . POST_GROUPS . '" value="' . $data . '" />';
	}
	else if ( $is_member || $is_pending )
	{
		if ( $view['group_type'] != GROUP_SYSTEM )
		{
			$template->assign_block_vars('view.switch_unsubscribe_group_input', array());
		}

		$group_details =  ( $is_pending ) ? $lang['Pending_this_group'] : $lang['Member_this_group'];
		$s_fields = '<input type="hidden" name="' . POST_GROUPS . '" value="' . $data . '" />';
	}
	else if ( $userdata['user_id'] == ANONYMOUS )
	{
		$group_details =  $lang['Login_to_join'];
		$s_fields = '';
	}
	else
	{
		if ( $view['group_type'] == GROUP_OPEN )
		{
			$template->assign_block_vars('view.switch_subscribe_group_input', array());

			$group_details =  $lang['This_open_group'];
			$s_fields = '<input type="hidden" name="' . POST_GROUPS . '" value="' . $data . '" />';
		}
		else if ( $view['group_type'] == GROUP_REQUEST )
		{
			$template->assign_block_vars('view.switch_subscribe_group_input', array());
			
			$group_details =  $lang['This_request_group'];
			$s_fields = '<input type="hidden" name="' . POST_GROUPS . '" value="' . $data . '" />';
		}
		else if ( $view['group_type'] == GROUP_CLOSED )
		{
			$group_details =  $lang['This_closed_group'];
			$s_fields = '';
		}
		else if ( $view['group_type'] == GROUP_HIDDEN )
		{
			$group_details =  $lang['This_hidden_group'];
			$s_fields = '';
		}
		else if ( $view['group_type'] == GROUP_SYSTEM )
		{
			$group_details =  $lang['This_system_group'];
			$s_fields = '';
		}
	}
	
	$s_opt = '';
	$s_opt .= "<select class=\"postselect\" name=\"mode\">";
	$s_opt .= "<option value=\"0\">&raquo; Option auswählen</option>";
	$s_opt .= "<option value=\"remove\">&raquo; Entfernen</option>";
	$s_opt .= "<option value=\"change_level\">&raquo; Gruppenrechte geben/nehmen</option>";
	$s_opt .= "</select>";
	
	$colspan = '';
	
	if ( $view['group_type'] != GROUP_HIDDEN || $is_member || $is_moderator || $userdata['user_level'] == ADMIN )
	{
		if ( !$ary_mod )
		{
			$template->assign_block_vars('view.switch_no_moderators', array());
		}
		else
		{
			if ( $userdata['user_level'] == ADMIN && $view['group_type'] != GROUP_SYSTEM )
			{
				$template->assign_block_vars('view._switch_admin', array());
			#	$template->assign_block_vars('view._switch_moderator', array());
				
			#	$template->assign_block_vars('view._switch_opt_adm', array());
			#	$template->assign_block_vars('view._switch_opt_mod', array());
				
				$colspan = '';
			}
			else
			{
				$colspan = 'colspan="2"';
			}
			
			for ( $i = 0; $i < count($ary_mod); $i++ )
			{
				$user_name	= $ary_mod[$i]['user_name'];
				$user_id	= $ary_mod[$i]['user_id'];
		
				generate_user_info($ary_mod[$i], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);
		
				$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
				$template->assign_block_vars('view._moderator_row', array(
					'ROW_COLOR' => '#' . $row_color,
					'ROW_CLASS' => $row_class,
					'USERNAME' => $user_name,
					'JOINED' => $joined,
					'POSTS' => $posts,
					'USER_ID' => $user_id, 
					'PROFILE_IMG' => $profile_img, 
					'PROFILE' => $profile, 
					'PM_IMG' => $pm_img,
					'PM' => $pm,
					'EMAIL_IMG' => $email_img,
					'EMAIL' => $email,
					
					'U_VIEWPROFILE' => check_sid('profile.php?mode=viewprofile&amp;' . POST_USER . '=' . $user_id))
				);
				
				if ( $userdata['user_level'] == ADMIN && $view['group_type'] != GROUP_SYSTEM )	
			#	if ( ( $is_moderator || $userdata['user_level'] == ADMIN ) && $view['group_type'] != GROUP_SYSTEM )
				{
					$template->assign_block_vars('view._moderatorrow._switch_admin', array());
				}
			}	
		}

		if ( !$ary_mem )
		{
			$template->assign_block_vars('view.switch_no_members', array());
		}
		else
		{
			if ( ( $is_moderator || $userdata['user_level'] == ADMIN ) && $view['group_type'] != GROUP_SYSTEM )
			{
				$template->assign_block_vars('view._switch_moderator', array());
			}
			
			for ( $j = $start; $j < min($settings['per_page_entry_site'] + $start, count($ary_mem)); $j++ )
			{
				$user_name	= $ary_mem[$j]['user_name'];
				$user_id	= $ary_mem[$j]['user_id'];
		
				generate_user_info($ary_mem[$j], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);
		
				$row_color = ( !($j % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
				$row_class = ( !($j % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
				
				$template->assign_block_vars('view._member_row', array(
					'ROW_COLOR' => '#' . $row_color,
					'ROW_CLASS' => $row_class,
					'USERNAME' => $user_name,
					'FROM' => $from,
					'JOINED' => $joined,
					'POSTS' => $posts,
					'USER_ID' => $user_id, 
					'AVATAR_IMG' => $poster_avatar,
					'PROFILE_IMG' => $profile_img, 
					'PROFILE' => $profile, 
					'SEARCH_IMG' => $search_img,
					'SEARCH' => $search,
					'PM_IMG' => $pm_img,
					'PM' => $pm,
					'EMAIL_IMG' => $email_img,
					'EMAIL' => $email,
					'WWW_IMG' => $www_img,
					'WWW' => $www,
					'ICQ_STATUS_IMG' => $icq_status_img,
					'ICQ_IMG' => $icq_img, 
					'ICQ' => $icq, 
					'AIM_IMG' => $aim_img,
					'AIM' => $aim,
					'MSN_IMG' => $msn_img,
					'MSN' => $msn,
					'YIM_IMG' => $yim_img,
					'YIM' => $yim,
					
					'U_VIEWPROFILE' => check_sid('profile.php?mode=viewprofile&amp;' . POST_USER . '=' . $user_id))
				);
				
				if ( ( $is_moderator || $userdata['user_level'] == ADMIN ) && $view['group_type'] != GROUP_SYSTEM )
				{
					$template->assign_block_vars('view._memberrow._switch_moderator', array());
				}
			}
		}
	
		$current_page = ( !count($ary_mem) ) ? 1 : ceil( count($ary_mem) / $settings['per_page_entry_site'] );

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination('groups.php?' . POST_GROUPS . '=' . $data, count($ary_mem), $settings['per_page_entry_site'], $start),
			'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry_site'] ) + 1 ), $current_page ), 
			'L_GOTO_PAGE' => $lang['Goto_page']
		));

		if ( $view['group_type'] == GROUP_HIDDEN && !$is_member && !$is_moderator )
		{
			$template->assign_block_vars('view.switch_hidden_group', array());
			$template->assign_vars(array('L_HIDDEN_MEMBERS' => $lang['Group_hidden_members']));
		}

		if ( $ary_pen && ( $userdata['user_level'] == ADMIN || $is_moderator ) )
		{
			$template->assign_block_vars('view._pending', array());
				
			for ( $k = 0; $k < count($ary_pen); $k++ )
			{
				$user_name	= $ary_pen[$k]['user_name'];
				$user_id	= $ary_pen[$k]['user_id'];

				generate_user_info($ary_pen[$k], $config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim);

				$row_color = ( !($k % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
				$row_class = ( !($k % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$user_select = '<input type="checkbox" name="member[]" value="' . $user_id . '">';

				$template->assign_block_vars('view._pending._pending_row', array(
					'ROW_CLASS' => $row_class,
					'ROW_COLOR' => '#' . $row_color, 
					'USERNAME' => $user_name,
					'FROM' => $from,
					'JOINED' => $joined,
					'POSTS' => $posts,
					'USER_ID' => $user_id, 
					'AVATAR_IMG' => $poster_avatar,
					'PROFILE_IMG' => $profile_img, 
					'PROFILE' => $profile, 
					'SEARCH_IMG' => $search_img,
					'SEARCH' => $search,
					'PM_IMG' => $pm_img,
					'PM' => $pm,
					'EMAIL_IMG' => $email_img,
					'EMAIL' => $email,
					'WWW_IMG' => $www_img,
					'WWW' => $www,
					'ICQ_STATUS_IMG' => $icq_status_img,
					'ICQ_IMG' => $icq_img, 
					'ICQ' => $icq, 
					'AIM_IMG' => $aim_img,
					'AIM' => $aim,
					'MSN_IMG' => $msn_img,
					'MSN' => $msn,
					'YIM_IMG' => $yim_img,
					'YIM' => $yim,
					
					'U_VIEWPROFILE' => check_sid('profile.php?mode=viewprofile&amp;' . POST_USER . '=' . $user_id))
				);
			}
		}

		if ( $is_moderator || $userdata['user_level'] == ADMIN && $view['group_type'] != GROUP_SYSTEM )
		{
			$template->assign_block_vars('view.switch_add_member', array());
		}
	}
	else
	{
		$template->assign_block_vars('view.switch_no_moderators', array());
		$template->assign_block_vars('view.switch_no_members', array());
	}
	
	$s_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

	$template->assign_vars(array(
		'L_MAIN' => $page_title,
		
		'L_GROUP_INFORMATION' => $lang['Group_Information'],
		'L_GROUP_NAME' => $lang['Group_name'],
		'L_GROUP_DESC' => $lang['group_desc'],
		'L_GROUP_TYPE' => $lang['Group_type'],
		'L_GROUP_MEMBERSHIP' => $lang['Group_membership'],
		'L_SUBSCRIBE' => $lang['Subscribe'],
		'L_UNSUBSCRIBE' => $lang['Unsubscribe'],
		'L_JOIN_GROUP' => $lang['Join_group'], 
		'L_UNSUBSCRIBE_GROUP' => $lang['Unsubscribe'], 
		'L_GROUP_OPEN' => $lang['Group_open'],
		'L_GROUP_REQUEST' => $lang['Group_quest'],
		'L_GROUP_CLOSED' => $lang['Group_closed'],
		'L_GROUP_HIDDEN' => $lang['Group_hidden'], 
		'L_UPDATE' => $lang['Update'], 
		'L_GROUP_MODERATOR' => $lang['Group_Moderator'], 
		'L_GROUP_MEMBERS' => $lang['Group_Members'], 
		'L_PENDING_MEMBERS' => $lang['Pending_members'], 
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'], 
		'L_PM' => $lang['Private_Message'], 
		'L_EMAIL' => $lang['Email'], 
		'L_POSTS' => $lang['Posts'], 
		'L_WEBSITE' => $lang['Website'],
		'L_FROM' => $lang['Location'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],
		'L_SUBMIT' => $lang['Sort'],
		'L_AIM' => $lang['AIM'],
		'L_YIM' => $lang['YIM'],
		'L_MSNM' => $lang['MSNM'],
		'L_ICQ' => $lang['ICQ'],
		'L_SELECT' => $lang['Select'],
		'L_REMOVE_SELECTED' => $lang['Remove_selected'],
		'L_ADD_MEMBER' => $lang['Add_member'],
		'L_FIND_USERNAME' => $lang['Find_user_name'],
		
		'L_JOINED'	=> $lang['Joined'],
		
		'L_SELECT' => $lang['Select'],
					'L_APPROVE_SELECTED' => $lang['Approve_selected'],
					'L_DENY_SELECTED' => $lang['Deny_selected'],

		'GROUP_NAME' => $view['group_name'],
		'GROUP_DESC' => $view['group_desc'],
		'GROUP_DETAILS' => $group_details,
		
		
		'COLSPAN'	=> $colspan,
		
		
		'L_NO_MODERATORS' => $lang['group_no_moderators'],
		'L_NO_MEMBERS' => $lang['group_no_members'],


		'S_FIELDS' => $s_fields, 
//		'S_MODE_SELECT' => $select_sort_mode,
//		'S_ORDER_SELECT' => $select_sort_order,
	#	'S_SELECT_USERS'	=> $select_users,
		'S_SELECT_OPTION'	=> $s_opt,
		'S_GROUPS_ACTION' => check_sid('groups.php?' . POST_GROUPS . '=' . $data)
	));
}
else if ( $data && request('joingroup', 1) )
{
	if ( !$userdata['session_logged_in'] )
	{
		redirect(check_sid('login.php?redirect=groups.php&' . POST_GROUPS . '=' . $data, true));
	}
	else if ( $sid !== $userdata['session_id'] )
	{
		message(GENERAL_ERROR, $lang['Session_invalid']);
	}
	
	$sql = 'SELECT group_id, group_type
				FROM ' . GROUPS . '
				WHERE group_id = ' . $data . '
					AND group_type <> ' . GROUP_HIDDEN;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $row = $db->sql_fetchrow($result) )
	{
		$sql = 'SELECT user_id
					FROM ' . GROUPS_USERS . '
					WHERE group_id = ' . $row['group_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$row_users = $db->sql_fetchrowset($result);
	
		if ( $row['group_type'] == GROUP_OPEN || $row['group_type'] == GROUP_REQUEST )
		{
			if ( $row_users )
			{
				foreach ( $row_users as $users => $user_id )
				{
					if ( $userdata['user_id'] == $user_id )
					{
						$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('group.php') . '">'));
						
						$message = $lang['Already_member_group'] . '<br><br>' . sprintf($lang['Click_return_group'], '<a href="' . check_sid('groupcp.$phpEx?' . POST_GROUPS . '=' . $data) . '">', '</a>') . '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . check_sid('group.php') . '">', '</a>');
						message(GENERAL_MESSAGE, $message);
					}
				}
			}
			
			if ( $row['group_type'] == GROUP_OPEN )
			{
				$sql = 'INSERT INTO ' . GROUPS_USERS . ' (group_id, user_id, user_pending) VALUES (' . $data . ', ' . $userdata['user_id'] . ', 0)';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				group_set_auth($userdata['user_id'], $data);
			}
			else if ( $row['group_type'] == GROUP_REQUEST )
			{
				$sql = 'INSERT INTO ' . GROUPS_USERS . ' (group_id, user_id, user_pending) VALUES (' . $data . ', ' . $userdata['user_id'] . ', 1)';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = 'SELECT u.user_id, u.user_name, u.user_email, u.user_lang, u.user_send_type, u.user_notify_pm, g.group_name
							FROM ' . USERS . ' u, ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
							WHERE u.user_id = gu.user_id
								AND g.group_id = gu.group_id
								AND gu.group_mod = 1
								AND gu.group_id = ' . $data;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Error getting group moderator data", "", __LINE__, __FILE__, $sql);
				}
				$mail_data = $db->sql_fetchrow($result);
				
				include($root_path . 'includes/functions_mail.php');
			#	send_notice($mail_data, 'group_request', 'groups');
			}
			
//			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('index.php') . '">'));
			
			$message = ( $row['group_type'] == GROUP_REQUEST ) ? $lang['group_msg_request'] : $lang['group_msg_open'];
			$message .= '<br><br>' . sprintf($lang['Click_return_group'], '<a href="' . check_sid('groups.php?' . POST_GROUPS . '=' . $data) . '">', '</a>');
			message(GENERAL_MESSAGE, $message);
		}
		else if ( $group['group_type'] == GROUP_CLOSED || $group['group_type'] == GROUP_HIDDEN || $group['group_type'] == GROUP_SYSTEM )
		{
//			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('index.php') . '">'));
			
			$message = $lang['This_closed_group'] . '<br><br>' . sprintf($lang['Click_return_group'], '<a href="' . check_sid('groups.php?' . POST_GROUPS . '=' . $data) . '">', '</a>') . '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . check_sid('index.php') . '">', '</a>');
			message(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
		message(GENERAL_MESSAGE, $lang['No_groups_exist'] . 'test'); 
	}
}
else if ( $data && isset($HTTP_POST_VARS['unsubpending']) || isset($HTTP_POST_VARS['unsub']) )
{
	//
	// Second, unsubscribing from a group
	// Check for confirmation of unsub.
	//
	if ( $cancel )
	{
		redirect(check_sid('groups.php', true));
	}
	else if ( !$userdata['session_logged_in'] )
	{
		redirect(check_sid('login.php?redirect=groups.php&' . POST_GROUPS . '=' . $data, true));
	}
	else if ( $sid !== $userdata['session_id'] )
	{
		message(GENERAL_ERROR, $lang['Session_invalid']);
	}

	if ( $confirm )
	{
		$sql = 'DELETE FROM ' . GROUPS_USERS . ' WHERE user_id = ' . $userdata['user_id'] . ' AND group_id = ' . $data;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		group_reset_auth($userdata['user_id'], $data);
		
//		$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid('index.php') . '">'));

		$message = $lang['Unsub_success']
			. '<br><br>' . sprintf($lang['Click_return_group'], '<a href="' . check_sid('groups.php?' . POST_GROUPS . '=' . $data) . '">', '</a>')
			. '<br><br>' . sprintf($lang['Click_return_index'], '<a href="' . check_sid('index.php') . '">', '</a>');
		message(GENERAL_MESSAGE, $message);
	}
	else
	{
		$unsub_msg = ( isset($HTTP_POST_VARS['unsub']) ) ? $lang['Confirm_unsub'] : $lang['Confirm_unsub_pending'];

		$s_fields = '<input type="hidden" name="' . POST_GROUPS . '" value="' . $data . '" /><input type="hidden" name="unsub" value="1" />';
		$s_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

		$page_title = $lang['Group_Control_Panel'];
		main_header();

		$template->set_filenames(array('confirm' => 'confirm_body.tpl'));

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $unsub_msg,
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'S_CONFIRM_ACTION' => check_sid('groups.php'),
			'S_FIELDS' => $s_fields)
		);

		$template->pparse('confirm');

		main_footer();
	}
}
else
{
	$template->assign_block_vars('list', array());
	
	$page_title = $lang['cp_groups'];
	main_header();
	
	$sql = "SELECT g.group_id, g.group_name, g.group_type, g.group_desc, gu.user_pending
				FROM " . GROUPS . " g, " . GROUPS_USERS . " gu
			WHERE gu.user_id = " . $userdata['user_id'] . " AND gu.group_id = g.group_id
			ORDER BY g.group_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$groups = $db->sql_fetchrowset($result);
	
	$is_pending = $is_member = array();
	
	if ( $groups )
	{
		$template->assign_block_vars('list._in_groups', array());
	
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
			$template->assign_block_vars('list._in_groups._is_member', array());
	
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
				
				$template->assign_block_vars('list._in_groups._is_member._row', array(
					'NAME' => "<a href=" . check_sid("$file?" . POST_GROUPS . "=" . $is_member[$i]['group_id']) . ">" . $is_member[$i]['group_name'] . "</a>",
					'DESC' => ( $is_member[$i]['group_desc'] ) ? ' :: ' . $is_member[$i]['group_desc'] : '',
					'TYPE' => $group_type,
				));
			}
		}
	
		if ( $is_pending )
		{
			$template->assign_block_vars('list._in_groups._is_pending', array());
		
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
		
				$template->assign_block_vars('list._in_groups._is_pending._row', array(
					'NAME' => "<a href=" . check_sid("$file?" . POST_GROUPS . "=" . $is_pending[$i]['group_id']) . ">" . $is_pending[$i]['group_name'] . "</a>",
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
		$template->assign_block_vars('list._no_group', array());
		
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
				$template->assign_block_vars('list._no_group._row', array(
					'NAME' => "<a href=" . check_sid("$file?" . POST_GROUPS . "=" . $no_group[$i]['group_id']) . ">" . $no_group[$i]['group_name'] . "</a>",
					'DESC' => ( $no_group[$i]['group_desc'] ) ? ' :: ' . $no_group[$i]['group_desc'] : '',
					'TYPE' => $group_type,
				));
			}
		}
	}
		
	$template->assign_vars(array(
		'L_MAIN'	=> $page_title,
		
		'L_CUR'		=> $lang['grp_cur_member'],
		'L_PEN'		=> $lang['grp_pen_member'],
		'L_NON'		=> $lang['grp_non_member'],
		
	#	'L_GROUP_MEMBERSHIP_DETAILS' => $lang['Group_member_details'],
	#	'L_JOIN_A_GROUP' => $lang['Group_member_join'],
	#	'L_YOU_BELONG_GROUPS' => $lang['Current_memberships'],
	#	'L_SELECT_A_GROUP' => $lang['Non_member_groups'],
	#	'L_PENDING_GROUPS' => $lang['Memberships_pending'],
	#	'L_SUBSCRIBE' => $lang['Subscribe'],
	#	'L_UNSUBSCRIBE' => $lang['Unsubscribe'],
	#	'L_VIEW_INFORMATION' => $lang['View_Information']
	));
}

$template->pparse('body');

main_footer();

?>