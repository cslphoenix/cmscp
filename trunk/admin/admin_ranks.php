<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_ranks'] )
	{
		$module['_headmenu_01_main']['_submenu_ranks'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_ranks';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('ranks');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_RANK;
	$url	= POST_RANKS_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_ranks'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['rank']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_ranks'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . append_sid($file, true)) : false;
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_ranks.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					list($type) = ( isset($_POST['rank_type']) ) ? each($_POST['rank_type']) : '';
					$title		= ( isset($_POST['rank_title']) ) ? str_replace("\'", "'", $_POST['rank_title'][$type]) : '';
					
					$data = array(
								'rank_title'	=> $title,
								'rank_type'		=> $type,
								'rank_min'		=> '0',
								'rank_special'	=> '0',
								'rank_image'	=> '',
								'rank_standard'	=> '0',
								'rank_order'	=> '',
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(RANKS, $data_id, false, 1, 1);
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
								'rank_order'	=> request('rank_order', 0) ? request('rank_order', 0) : request('rank_order_new', 0),
							);
				}
				
				$head = array(
							'0' => array('rank_type' => RANK_PAGE, 'rank_name' => $lang['page']),
							'1' => array('rank_type' => RANK_FORUM, 'rank_name' => $lang['forum']),
							'2' => array('rank_type' => RANK_TEAM, 'rank_name' => $lang['team']),
						);
				
				$type = ( $data['rank_type'] ) ? " WHERE rank_type = " . $data['rank_type'] : false;
				
				$sql = "SELECT * FROM " . RANKS . "$type ORDER BY rank_type, rank_order ASC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$entrys = $db->sql_fetchrowset($result);
				
				$s_order = "<select class=\"select\" name=\"rank_order_new\" id=\"rank_order\">";
				$s_order .= "<option selected=\"selected\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
				
				for ( $i = 0; $i < count($head); $i++ )
				{
					$entry = '';

					for ( $j = 0; $j < count($entrys); $j++ )
					{
						if ( $head[$i]['rank_type'] == $entrys[$j]['rank_type'] )
						{
							$entry .= ( $entrys[$j]['rank_order'] == 10 ) ? "<option value=\"5\">" . sprintf($lang['sprintf_select_before'], $entrys[$j]['rank_title']) . "</option>" : '';
							$entry .= "<option value=\"" . ( $entrys[$j]['rank_order'] + 5 ) . "\">" . sprintf($lang['sprintf_select_order'], $entrys[$j]['rank_title']) . "</option>";
						}
					}
					
					if ( $entry != '' )
					{
						$s_order .= '<optgroup label="' . $head[$i]['rank_name'] . '">';
						$s_order .= $entry;
						$s_order .= '</optgroup>';
					}
				}
				
				$s_order .= '</select>';

				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['rank']),
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['rank'], $data['rank_title']),
					'L_TITLE'			=> sprintf($lang['sprintf_title'], $lang['rank']),
					'L_IMAGE'			=> sprintf($lang['sprintf_image'], $lang['rank']),
					'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['rank']),
					'L_TYPE_PAGE'		=> $lang['page'],
					'L_TYPE_FORUM'		=> $lang['forum'],
					'L_TYPE_TEAM'		=> $lang['team'],
					'L_SPECIAL'			=> $lang['special'],
					'L_MIN'				=> $lang['min'],
					'L_STANDARD'		=> $lang['standard'],
					
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
					
					'S_ORDER'			=> $s_order,
					
					'S_PATH'			=> $path_dir,
					'S_LIST'			=> select_box_files('post', 'ranks', $path_dir, $data['rank_image']),
					
					'S_ACTION'			=> append_sid($file),
					'S_FIELDS'			=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$rank_title		= request('rank_title', 2);
					$rank_image		= request('rank_image', 2);
					$rank_type		= request('rank_type', 0);
					$rank_min		= request('rank_min', 0);
					$rank_special	= request('rank_special', 0);
					$rank_standard	= request('rank_standard', 0);
					
					$error .= ( !$rank_title ) ? $lang['msg_empty_title'] : '';
					
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
							
							$message = $lang['create'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
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
							
							$message = $lang['update']
								. sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>')
								. sprintf($lang['return_update'], '<a href="' . append_sid("$file?mode=$mode&amp;$url=$data_id") . '">', '</a>');
						}
						
						log_add(LOG_ADMIN, $log, $mode, $rank_title);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
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
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete':
			
				$data = data(RANKS, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{	
					$sql = "DELETE FROM " . RANKS . " WHERE rank_id = $data_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete'] . sprintf($lang['return'], '<a href="' . append_sid($file) . '">', $acp_title, '</a>');
					
					log_add(LOG_ADMIN, $log, $mode, $data['rank_title']);
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
						
						$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
						$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";			
			
						$template->assign_vars(array(
							'M_TITLE'	=> $lang['common_confirm'],
							'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['confirm'], $data['rank_title']),
							
							'S_ACTION'	=> append_sid($file),
							'S_FIELDS'	=> $fields,
						));
					}
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['sprintf_must_select'], $lang['rank'])); }

				$template->pparse('body');
				
				break;
	
			default: message(GENERAL_ERROR, $lang['msg_no_module_select']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_ranks.tpl'));
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['rank']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_createn'], $lang['rank']),
		'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['rank']),
		
		'L_EXPLAIN'		=> $lang['explain'],
		
		'L_PAGE'		=> $lang['page'],
		'L_FORUM'		=> $lang['forum'],
		'L_TEAM'		=> $lang['team'],
		'L_SPECIAL'		=> $lang['special'],
		'L_STANDARD'	=> $lang['standard'],
		'L_MIN'			=> $lang['min'],
		
		'S_CREATE'		=> append_sid("$file?mode=_create"),
		'S_ACTION'		=> append_sid($file),
		'S_FIELDS'		=> $fields,
	));
	
	$max_forum	= maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_FORUM . ' AND rank_special = 1');
	$max_page	= maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_PAGE);
	$max_team	= maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_TEAM);
	
	$tmp_forum	= data(RANKS, 'rank_type = ' . RANK_FORUM, 'rank_special DESC, rank_order ASC', 1, false);
	$tmp_page	= data(RANKS, 'rank_type = ' . RANK_PAGE, 'rank_special DESC, rank_order ASC', 1, false);
	$tmp_team	= data(RANKS, 'rank_type = ' . RANK_TEAM, 'rank_special DESC, rank_order ASC', 1, false);

	if ( $tmp_forum )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_forum)); $i++ )
		{
			$rank_id = $tmp_forum[$i]['rank_id'];
				
			$template->assign_block_vars('_display._forum_row', array(
				'TITLE'		=> $tmp_forum[$i]['rank_title'],
				'MIN'		=> ( $tmp_forum[$i]['rank_special'] == '0' ) ? $tmp_forum[$i]['rank_min'] : ' - ',
				'SPECIAL'	=> ( $tmp_forum[$i]['rank_special'] == '1' ) ? $lang['common_yes'] : $lang['common_no'],
				
				'MOVE_UP'	=> ( $tmp_forum[$i]['rank_special'] && $tmp_forum[$i]['rank_order'] != '10' )		? '<a href="' . append_sid("$file?mode=_order&amp;type=" . RANK_FORUM . "&amp;move=-15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $tmp_forum[$i]['rank_special'] && $tmp_forum[$i]['rank_order'] != $max_forum )	? '<a href="' . append_sid("$file?mode=_order&amp;type=" . RANK_FORUM . "&amp;move=+15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$rank_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$rank_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_forum', array()); }
	
	if ( $tmp_page )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_page)); $i++ )
		{
			$rank_id = $tmp_page[$i]['rank_id'];
				
			$template->assign_block_vars('_display._page_row', array(
				'TITLE'		=> $tmp_page[$i]['rank_title'],
				'STANDARD'	=> $tmp_page[$i]['rank_standard'] ? $lang['standard'] : '',
				
				'MOVE_UP'	=> ( $tmp_page[$i]['rank_order'] != '10' )		? '<a href="' . append_sid("$file?mode=_order&amp;type=" . RANK_PAGE . "&amp;move=-15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $tmp_page[$i]['rank_order'] != $max_page )	? '<a href="' . append_sid("$file?mode=_order&amp;type=" . RANK_PAGE . "&amp;move=+15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$rank_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$rank_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_page', array()); }
	
	if ( $tmp_team )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($tmp_team)); $i++ )
		{
			$rank_id = $tmp_team[$i]['rank_id'];
				
			$template->assign_block_vars('_display._team_row', array(
				'TITLE'		=> $tmp_team[$i]['rank_title'],
				'STANDARD'	=> $tmp_team[$i]['rank_standard'] ? $lang['standard'] : '',
				
				'MOVE_UP'	=> ( $tmp_team[$i]['rank_order'] != '10' )		? '<a href="' . append_sid("$file?mode=_order&amp;type=" . RANK_TEAM . "&amp;move=-15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $tmp_team[$i]['rank_order'] != $max_team )	? '<a href="' . append_sid("$file?mode=_order&amp;type=" . RANK_TEAM . "&amp;move=+15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_UPDATE'	=> append_sid("$file?mode=_update&amp;$url=$rank_id"),
				'U_DELETE'	=> append_sid("$file?mode=_delete&amp;$url=$rank_id"),
			));
		}
	}
	else { $template->assign_block_vars('_display._no_team', array()); }
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>