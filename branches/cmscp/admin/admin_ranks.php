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
	
	if ( $userauth['auth_ranks'] || $userdata['user_level'] == ADMIN )
	{
		$module['_headmenu_main']['_submenu_ranks'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	include($root_path . 'language/lang_' . $userdata['user_lang'] . '/acp/ranks.php');
	
	$start		= ( request('start') ) ? request('start') : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$rank_id	= request(POST_RANKS_URL);
	$rank_type	= request('type');
	$confirm	= request('confirm');
	$mode		= request('mode');
	$move		= request('move');
	$show_index	= '';
	
	if ( !$userauth['auth_games'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_ranks.php', true));
	}
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_ranks.tpl'));
				$template->assign_block_vars('ranks_edit', array());

				if ( $mode == '_create' )
				{
					$rank = array (
						'rank_title'	=> request('rank_title', 'text'),
						'rank_type'		=> '1',
						'rank_min'		=> '0',
						'rank_special'	=> '0',
						'rank_image'	=> '',
						'rank_order'	=> '',
						'rank_standard'	=> '0',
					);
					$new_mode = '_create_save';
				}
				else
				{
					$rank = get_data(RANKS, $rank_id, 1);
					$new_mode = '_update_save';
				}
				
				$folder = $root_path . $settings['path_ranks'];
				$files = scandir($folder);
				
				$filename_list = '<select name="rank_image" id="rank_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
				$filename_list .= '<option value="">----------</option>';
				
				foreach ( $files as $file )
				{
					if ( $file != '.' && $file != '..' && $file != 'index.htm' && $file != '.svn' && $file != 'spacer.gif' )
					{
						$selected = ( $file == $rank['rank_image'] ) ? ' selected="selected"' : '';
						$filename_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$filename_list .= '</select>';
				
				$to_ranks = $root_path . $settings['path_ranks'] . '/';
				$ssprintf = ( $mode == '_create' ) ? 'sprintf_add' : 'sprintf_edit';
				$s_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_RANKS_URL . '" value="' . $rank_id . '" />';

				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['rank']),
					'L_NEW_EDIT'		=> sprintf($lang[$ssprintf], $lang['rank']),
					'L_NAME'			=> sprintf($lang['sprintf_title'], $lang['rank']),
					'L_IMAGE'			=> sprintf($lang['sprintf_image'], $lang['rank']),
					'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['rank']),
					
					'L_TYPE_PAGE'		=> $lang['rank_page'],
					'L_TYPE_FORUM'		=> $lang['rank_forum'],
					'L_TYPE_TEAM'		=> $lang['rank_team'],
					'L_SPECIAL'			=> $lang['rank_special'],
					'L_MIN'				=> $lang['rank_min'],
					'L_STANDARD'		=> $lang['rank_standard'],
					
					'TITLE'				=> $rank['rank_title'],
					'MIN'				=> $rank['rank_min'],
					'IMAGE'				=> ( $mode == '_update' ) ? ( $rank['rank_image'] ) ? $to_ranks . $rank['rank_image'] : $images['icon_acp_spacer'] : $images['icon_acp_spacer'],
					'IMAGE_PATH'		=> $to_ranks,
					'IMAGE_DEFAULT'		=> $images['icon_acp_spacer'],
					
					'S_TYPE_PAGE'		=> ( $rank['rank_type'] == RANK_PAGE ) ? ' checked="checked"' : '',
					'S_TYPE_FORUM'		=> ( $rank['rank_type'] == RANK_FORUM ) ? ' checked="checked"' : '',
					'S_TYPE_TEAM'		=> ( $rank['rank_type'] == RANK_TEAM ) ? ' checked="checked"' : '',
					'S_SPECIAL_YES'		=> ( $rank['rank_special'] ) ? ' checked="checked"' : '',
					'S_SPECIAL_NO'		=> ( !$rank['rank_special'] ) ? ' checked="checked"' : '',
					'S_STANDARD_YES'	=> ( $rank['rank_standard'] ) ? ' checked="checked"' : '',
					'S_STANDARD_NO'		=> ( !$rank['rank_standard'] ) ? ' checked="checked"' : '',
					
					'S_FILENAME_LIST'	=> $filename_list,
					'S_FIELDS'			=> $s_fields,
					'S_ACTION'			=> append_sid('admin_ranks.php'),
				));
			
				$template->pparse('body');
				
				break;
			
			case '_create_save':
			
				$rank_title		= request('rank_title', 'text');
				$rank_image		= request('rank_image', 'text');
				$rank_type		= request('rank_type', 'num');
				$rank_min		= request('rank_min', 'num');
				$rank_special	= request('rank_special', 'num');
				$rank_standard	= request('rank_standard', 'num');
				
				if ( !$rank_title )
				{
					message(GENERAL_ERROR, $lang['msg_select_title'] . $lang['back']);
				}
				
				$max_row	= get_data_max(RANKS, 'rank_order', 'rank_type = ' . $rank_type);
				$next_order	= $max_row['max'] + 10;
				
				if ( $rank_standard )
				{
					$sql = "UPDATE " . RANKS . " SET rank_standard	= 0 WHERE rank_type = $rank_type";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
				
				$sql = "INSERT INTO " . RANKS . " (rank_title, rank_type, rank_min, rank_special, rank_standard, rank_image, rank_order)
							VALUES ('$rank_title', '$rank_type', '$rank_min', '$rank_special', '$rank_standard', '$rank_image', '$next_order')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['create_rank'] . sprintf($lang['click_return_rank'], '<a href="' . append_sid('admin_ranks.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'create_rank');
				message(GENERAL_MESSAGE, $message);

				break;
			
			case '_update_save':
			
				$rank_title		= request('rank_title', 'text');
				$rank_image		= request('rank_image', 'text');
				$rank_type		= request('rank_type', 'num');
				$rank_min		= request('rank_min', 'num');
				$rank_special	= request('rank_special', 'num');
				$rank_standard	= request('rank_standard', 'num');
				
				if ( !$rank_title )
				{
					message(GENERAL_ERROR, $lang['msg_select_title'] . $lang['back']);
				}
				
				if ( $rank_standard )
				{
					$sql = "UPDATE " . RANKS . " SET rank_standard	= 0 WHERE rank_type = $rank_type";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
					
				$sql = "UPDATE " . RANKS . " SET
							rank_title		= '$rank_title',
							rank_type		= '$rank_type',
							rank_min		= '$rank_min',
							rank_special	= '$rank_special',
							rank_standard	= '$rank_standard',
							rank_image		= '$rank_image'
						WHERE rank_id = $rank_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['update_rank']
					. sprintf($lang['click_return_rank'], '<a href="' . append_sid('admin_ranks.php') . '">', '</a>')
					. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_ranks.php?mode=_update&amp;' . POST_RANKS_URL . '=' . $rank_id) . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'update_rank');
				message(GENERAL_MESSAGE, $message);
	
				break;
				
			case '_order':
				
				update(RANKS, 'rank', $move, $rank_id);
				orders('ranks', $rank_type);
				
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'acp_game_order');
				
				$show_index = TRUE;
				
				break;
				
			case '_delete':
			
				$rank = get_data(RANKS, $rank_id, 1);
			
				if ( $rank_id && $confirm )
				{	
					$sql = "DELETE FROM " . RANKS . " WHERE rank_id = $rank_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete_rank'] . sprintf($lang['click_return_rank'], '<a href="' . append_sid('admin_ranks.php') . '">', '</a>');
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_RANK, 'delete_rank');
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $rank_id && !$confirm )
				{
					if ( $rank['rank_standard'] )
					{
						message(GENERAL_ERROR, $lang['msg_select_standard'] . $lang['back']);
					}
					else
					{
						$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
			
						$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_RANKS_URL . '" value="' . $rank_id . '" />';
			
						$template->assign_vars(array(
							'MESSAGE_TITLE'		=> $lang['common_confirm'],
							'MESSAGE_TEXT'		=> $lang['confirm_delete_rank'],
							'L_NO'				=> $lang['common_no'],
							'L_YES'				=> $lang['common_yes'],
							'S_FIELDS'	=> $s_fields,
							'S_ACTION'	=> append_sid('admin_ranks.php'),
						));
					}
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_rank']);
				}
					
				$template->pparse('body');
				
				break;
	
			default:
			
				message(GENERAL_ERROR, $lang['no_select_module']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_ranks.tpl'));
	$template->assign_block_vars('display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['rank']),
		'L_CREATE'		=> sprintf($lang['sprintf_creates'], $lang['rank']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['rank']),
		'L_EXPLAIN'		=> $lang['rank_explain'],
		
		'L_PAGE'		=> $lang['rank_page'],
		'L_FORUM'		=> $lang['rank_forum'],
		'L_TEAM'		=> $lang['rank_team'],
		'L_SPECIAL'		=> $lang['rank_special'],
		'L_STANDARD'	=> $lang['rank_standard'],
		'L_MIN'			=> $lang['rank_min'],
		
		'L_UPDATE'		=> $lang['common_update'],
		'L_DELETE'		=> $lang['common_delete'],
		'L_SETTINGS'	=> $lang['common_settings'],
		
		'S_FIELDS'		=> $s_fields,
		'S_CREATE'		=> append_sid('admin_ranks.php?mode=_create'),
		'S_ACTION'		=> append_sid('admin_ranks.php'),
	));
	
	$max_forum	= get_data_max(RANKS, 'rank_order', 'rank_type = ' . RANK_FORUM . ' AND rank_special = 1');
	$max_page	= get_data_max(RANKS, 'rank_order', 'rank_type = ' . RANK_PAGE);
	$max_team	= get_data_max(RANKS, 'rank_order', 'rank_type = ' . RANK_TEAM);
	
	$data_forum	= get_data_array(RANKS, 'rank_type = ' . RANK_FORUM, 'rank_special DESC, rank_order', 'ASC');
	$data_page	= get_data_array(RANKS, 'rank_type = ' . RANK_PAGE, 'rank_special DESC, rank_order', 'ASC');
	$data_team	= get_data_array(RANKS, 'rank_type = ' . RANK_TEAM, 'rank_special DESC, rank_order', 'ASC');
	
	if ( $data_forum )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_forum)); $i++ )
		{
			$rank_id = $data_forum[$i]['rank_id'];
				
			$template->assign_block_vars('display.forum_row', array(
				'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				
				'TITLE'	=> $data_forum[$i]['rank_title'],
				'MIN'		=> ( $data_forum[$i]['rank_special'] == '0' ) ? $data_forum[$i]['rank_min'] : ' - ',
				'SPECIAL'	=> ( $data_forum[$i]['rank_special'] == '1' ) ? $lang['common_yes'] : $lang['common_no'],
				
				'MOVE_UP'		=> ( $data_forum[$i]['rank_order'] != '10' && $data_forum[$i]['rank_special'] )					? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_FORUM . '&amp;move=-15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $data_forum[$i]['rank_order'] != $max_forum['max'] && $data_forum[$i]['rank_special'] )	? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_FORUM . '&amp;move=15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_UPDATE'		=> append_sid('admin_ranks.php?mode=_update&amp;' . POST_RANKS_URL . '=' . $rank_id),
				'U_DELETE'		=> append_sid('admin_ranks.php?mode=_delete&amp;' . POST_RANKS_URL . '=' . $rank_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_forum', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_page )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_page)); $i++ )
		{
			$rank_id = $data_page[$i]['rank_id'];
				
			$template->assign_block_vars('display.page_row', array(
				'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				
				'TITLE'	=> $data_page[$i]['rank_title'],
				'STANDARD'	=> ( $data_page[$i]['rank_standard'] ) ? '*' : '',
				
				'MOVE_UP'		=> ( $data_page[$i]['rank_order'] != '10' )				? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_PAGE . '&amp;move=-15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $data_page[$i]['rank_order'] != $max_page['max'] )	? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_PAGE . '&amp;move=15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_UPDATE'		=> append_sid('admin_ranks.php?mode=_update&amp;' . POST_RANKS_URL . '=' . $rank_id),
				'U_DELETE'		=> append_sid('admin_ranks.php?mode=_delete&amp;' . POST_RANKS_URL . '=' . $rank_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_page', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	if ( $data_team )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_team)); $i++ )
		{
			$rank_id = $data_team[$i]['rank_id'];
				
			$template->assign_block_vars('display.team_row', array(
				'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				
				'TITLE'	=> $data_team[$i]['rank_title'],
				'STANDARD'	=> ( $data_team[$i]['rank_standard'] ) ? '*' : '',
				
				'MOVE_UP'		=> ( $data_team[$i]['rank_order'] != '10' )				? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_TEAM . '&amp;move=-15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $data_team[$i]['rank_order'] != $max_team['max'] )	? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_TEAM . '&amp;move=15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_UPDATE'		=> append_sid('admin_ranks.php?mode=_update&amp;' . POST_RANKS_URL . '=' . $rank_id),
				'U_DELETE'		=> append_sid('admin_ranks.php?mode=_delete&amp;' . POST_RANKS_URL . '=' . $rank_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry_team', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>