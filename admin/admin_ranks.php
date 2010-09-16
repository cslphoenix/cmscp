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
 *	@autor:	Sebastian Frickel � 2009, 2010
 *	@code:	Sebastian Frickel � 2009, 2010
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_ranks'] )
	{
		$module['_headmenu_main']['_submenu_ranks'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$no_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_ranks';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('ranks');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$data_id	= request(POST_RANKS_URL, 0);
	$data_type	= request('type');
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	$path_dir	= $root_path . $settings['path_ranks'] . '/';
	$show_index	= '';
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_ranks'] )
	{
		log_add(LOG_ADMIN, LOG_SEK_RANK, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $no_header ) ? redirect('admin/' . append_sid('admin_ranks.php', true)) : false;
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_ranks.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 2) )
				{
					$data = array(
						'rank_title'	=> request('rank_title', 2),
						'rank_type'		=> '1',
						'rank_min'		=> '0',
						'rank_special'	=> '0',
						'rank_image'	=> '',
						'rank_standard'	=> '0',
					);
				}
				else if ( $mode == '_update' && !request('submit', 2) )
				{
					$data = get_data(RANKS, $data_id, 1);
				}
				else
				{
					$data = array(
						'rank_title'	=> request('rank_title', 2),
						'rank_type'		=> request('rank_type', 0),
						'rank_min'		=> request('rank_min', 0),
						'rank_image'	=> request('rank_image', 2),
						'rank_special'	=> request('rank_special', 0),						
						'rank_standard'	=> request('rank_standard', 0),
					);
				}

				$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
				$s_fields .= '<input type="hidden" name="' . POST_RANKS_URL . '" value="' . $data_id . '" />';

				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['rank']),
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['rank'], $data['rank_title']),
					'L_TITLE'			=> sprintf($lang['sprintf_title'], $lang['rank']),
					'L_IMAGE'			=> sprintf($lang['sprintf_image'], $lang['rank']),
					'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['rank']),
					'L_TYPE_PAGE'		=> $lang['rank_page'],
					'L_TYPE_FORUM'		=> $lang['rank_forum'],
					'L_TYPE_TEAM'		=> $lang['rank_team'],
					'L_SPECIAL'			=> $lang['rank_special'],
					'L_MIN'				=> $lang['rank_min'],
					'L_STANDARD'		=> $lang['rank_standard'],
					
					'TITLE'				=> $data['rank_title'],
					'MIN'				=> $data['rank_min'],
					'PIC'				=> ( $data['rank_image'] ) ? $path_dir . $data['rank_image'] : $images['icon_acp_spacer'],
					
					'S_TYPE_PAGE'		=> ( $data['rank_type'] == RANK_PAGE ) ? ' checked="checked"' : '',
					'S_TYPE_FORUM'		=> ( $data['rank_type'] == RANK_FORUM ) ? ' checked="checked"' : '',
					'S_TYPE_TEAM'		=> ( $data['rank_type'] == RANK_TEAM ) ? ' checked="checked"' : '',
					'S_SPECIAL_YES'		=> ( $data['rank_special'] ) ? ' checked="checked"' : '',
					'S_SPECIAL_NO'		=> ( !$data['rank_special'] ) ? ' checked="checked"' : '',
					'S_STANDARD_YES'	=> ( $data['rank_standard'] ) ? ' checked="checked"' : '',
					'S_STANDARD_NO'		=> ( !$data['rank_standard'] ) ? ' checked="checked"' : '',
					
					'S_PATH'			=> $path_dir,
					'S_LIST'			=> select_box_files('ranks', 'post', $path_dir, $data['rank_image']),
					
					'S_FIELDS'			=> $s_fields,
					'S_ACTION'			=> append_sid('admin_ranks.php'),
				));
				
				if ( request('submit', 2) )
				{
					$rank_title		= request('rank_title', 2);
					$rank_image		= request('rank_image', 2);
					$rank_type		= request('rank_type', 0);
					$rank_min		= request('rank_min', 0);
					$rank_special	= request('rank_special', 0);
					$rank_standard	= request('rank_standard', 0);
					
					$error .= ( !$rank_title ) ? $lang['msg_select_title'] : '';
					
					if ( !$error )
					{
						if ( $rank_standard )
						{
							$sql = "UPDATE " . RANKS . " SET rank_standard = 0 WHERE rank_type = $rank_type";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						}
						
						if ( $mode == '_create' )
						{
							$max	= get_data_max(GAMES, 'game_order', '');
							$next	= $max['max'] + 10;
							
							$sql = "INSERT INTO " . RANKS . " (rank_title, rank_type, rank_min, rank_special, rank_standard, rank_image, rank_order)
										VALUES ('$rank_title', '$rank_type', '$rank_min', '$rank_special', '$rank_standard', '$rank_image', '$next')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_rank'] . sprintf($lang['click_return_rank'], '<a href="' . append_sid('admin_ranks.php') . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . RANKS . " SET
										rank_title		= '$rank_title',
										rank_type		= '$rank_type',
										rank_min		= '$rank_min',
										rank_special	= '$rank_special',
										rank_standard	= '$rank_standard',
										rank_image		= '$rank_image'
									WHERE rank_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_rank']
								. sprintf($lang['click_return_rank'], '<a href="' . append_sid('admin_ranks.php') . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid('admin_ranks.php?mode=_update&amp;' . POST_RANKS_URL . '=' . $data_id) . '">', '</a>');
						}
						
						log_add(LOG_ADMIN, LOG_SEK_RANK, $mode, $rank_title);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
			
				$template->pparse('body');
				
				break;
			
			case '_order':
				
				update(RANKS, 'rank', $move, $data_id);
				orders(RANKS, $data_type);
				
				log_add(LOG_ADMIN, LOG_SEK_RANK, $mode);
				
				$show_index = TRUE;
				
				break;
				
			case '_delete':
			
				$data = get_data(RANKS, $data_id, 1);
			
				if ( $data_id && $confirm )
				{	
					$sql = "DELETE FROM " . RANKS . " WHERE rank_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete_rank'] . sprintf($lang['click_return_rank'], '<a href="' . append_sid('admin_ranks.php') . '">', '</a>');
					
					log_add(LOG_ADMIN, LOG_SEK_RANK, $mode, $data['rank_title']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					if ( $data['rank_standard'] )
					{
						message(GENERAL_ERROR, $lang['msg_select_standard'] . $lang['back']);
					}
					else
					{
						$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
			
						$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_RANKS_URL . '" value="' . $data_id . '" />';
			
						$template->assign_vars(array(
							'MESSAGE_TITLE'	=> $lang['common_confirm'],
							'MESSAGE_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_rank'], $data['rank_title']),
							
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
			
				message(GENERAL_ERROR, $lang['msg_no_module_select']);
				
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_ranks.tpl'));
	$template->assign_block_vars('_display', array());
	
	$s_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['rank']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_create'], $lang['rank']),
		'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['rank']),
		
		'L_EXPLAIN'		=> $lang['rank_explain'],
		
		'L_PAGE'		=> $lang['rank_page'],
		'L_FORUM'		=> $lang['rank_forum'],
		'L_TEAM'		=> $lang['rank_team'],
		'L_SPECIAL'		=> $lang['rank_special'],
		'L_STANDARD'	=> $lang['rank_standard'],
		'L_MIN'			=> $lang['rank_min'],
		
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
				
			$template->assign_block_vars('_display._forum_row', array(
				'TITLE'		=> $data_forum[$i]['rank_title'],
				'MIN'		=> ( $data_forum[$i]['rank_special'] == '0' ) ? $data_forum[$i]['rank_min'] : ' - ',
				'SPECIAL'	=> ( $data_forum[$i]['rank_special'] == '1' ) ? $lang['common_yes'] : $lang['common_no'],
				
				'MOVE_UP'	=> ( $data_forum[$i]['rank_order'] != '10' && $data_forum[$i]['rank_special'] )					? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_FORUM . '&amp;move=-15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data_forum[$i]['rank_order'] != $max_forum['max'] && $data_forum[$i]['rank_special'] )	? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_FORUM . '&amp;move=15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid('admin_ranks.php?mode=_update&amp;' . POST_RANKS_URL . '=' . $rank_id),
				'U_DELETE'	=> append_sid('admin_ranks.php?mode=_delete&amp;' . POST_RANKS_URL . '=' . $rank_id),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_forum', array()); }
	
	if ( $data_page )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_page)); $i++ )
		{
			$rank_id = $data_page[$i]['rank_id'];
				
			$template->assign_block_vars('_display._page_row', array(
				'TITLE'		=> $data_page[$i]['rank_title'],
				'STANDARD'	=> ( $data_page[$i]['rank_standard'] ) ? $lang['rank_standard'] : '',
				
				'MOVE_UP'	=> ( $data_page[$i]['rank_order'] != '10' )				? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_PAGE . '&amp;move=-15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data_page[$i]['rank_order'] != $max_page['max'] )	? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_PAGE . '&amp;move=15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid('admin_ranks.php?mode=_update&amp;' . POST_RANKS_URL . '=' . $rank_id),
				'U_DELETE'	=> append_sid('admin_ranks.php?mode=_delete&amp;' . POST_RANKS_URL . '=' . $rank_id),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_page', array()); }
	
	if ( $data_team )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($data_team)); $i++ )
		{
			$rank_id = $data_team[$i]['rank_id'];
				
			$template->assign_block_vars('_display._team_row', array(
				'TITLE'		=> $data_team[$i]['rank_title'],
				'STANDARD'	=> ( $data_team[$i]['rank_standard'] ) ? $lang['rank_standard'] : '',
				
				'MOVE_UP'	=> ( $data_team[$i]['rank_order'] != '10' )				? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_TEAM . '&amp;move=-15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $data_team[$i]['rank_order'] != $max_team['max'] )	? '<a href="' . append_sid('admin_ranks.php?mode=_order&amp;type=' . RANK_TEAM . '&amp;move=15&amp;' . POST_RANKS_URL . '=' . $rank_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid('admin_ranks.php?mode=_update&amp;' . POST_RANKS_URL . '=' . $rank_id),
				'U_DELETE'	=> append_sid('admin_ranks.php?mode=_delete&amp;' . POST_RANKS_URL . '=' . $rank_id),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_team', array()); }
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>