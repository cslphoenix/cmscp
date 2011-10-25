<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_ranks'] )
	{
		$module['hm_main']['sm_ranks'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_ranks';
	
	include('./pagestart.php');

	load_lang('ranks');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_RANK;
	$url	= POST_RANKS;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . $settings['path_ranks'] . '/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_ranks'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_ranks.tpl',
		'ajax'		=> 'style/ajax_order.tpl',
		'uimg'		=> 'style/inc_java_img.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->assign_block_vars('_input', array());
				
				$template->assign_vars(array('PATH' => $path_dir));
								
				$template->assign_var_from_handle('AJAX', 'ajax');
				$template->assign_var_from_handle('UIMG', 'uimg');
				
				if ( $mode == '_create' && !request('submit', 1) )
				{
					list($type) = ( isset($_POST['rank_type']) ) ? each($_POST['rank_type']) : '';
					$name		= ( isset($_POST['rank_name']) ) ? str_replace("\'", "'", $_POST['rank_name'][$type]) : '';
					
					$data = array(
								'rank_name'		=> $name,
								'rank_type'		=> $type,
								'rank_min'		=> '0',
								'rank_special'	=> '0',
								'rank_image'	=> '',
								'rank_standard'	=> '0',
								'rank_order'	=> maxa(RANKS, 'rank_order', "rank_type = $type"),
							);
				}
				else if ( $mode == '_update' && !request('submit', 1) )
				{
					$data = data(RANKS, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
								'rank_name'		=> request('rank_name', 2),
								'rank_type'		=> request('rank_type', 0),
								'rank_min'		=> request('rank_min', 0),
								'rank_image'	=> request('rank_image', 2),
								'rank_special'	=> request('rank_special', 0),						
								'rank_standard'	=> request('rank_standard', 0),
								'rank_order'	=> request('rank_order', 0) ? request('rank_order', 0) : request('rank_order_new', 0),
							);
					
					$error .= check(RANKS, array('rank_name' => $data['rank_name'], 'rank_id' => $data_id), $error);
					
					if ( !$error )
					{
						$data['rank_order'] = !$data['rank_order'] ? maxa(RANKS, 'rank_order', 'rank_type = ' . $data['rank_type']) : $data['rank_order'];
						( $data['rank_standard'] ) ? sql(RANKS, 'update', array('rank_standard' => '0'), 'rank_type', $data['rank_type']) : '';
												
						if ( $mode == '_create' )
						{
							$sql = sql(RANKS, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(RANKS, $mode, $data, 'rank_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(RANKS, $data['rank_type']);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";

				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'			=> sprintf($lang['sprintf' . $mode], $lang['title'], $data['rank_name']),
					'L_NAME'			=> sprintf($lang['sprintf_name'], $lang['title']),
					'L_IMAGE'			=> sprintf($lang['sprintf_image'], $lang['title']),
					'L_TYPE'			=> sprintf($lang['sprintf_type'], $lang['title']),
					'L_TYPE_PAGE'		=> $lang['rank_page'],
					'L_TYPE_FORUM'		=> $lang['rank_forum'],
					'L_TYPE_TEAM'		=> $lang['rank_team'],
					'L_SPECIAL'			=> $lang['rank_special'],
					'L_MIN'				=> $lang['rank_min'],
					'L_STANDARD'		=> $lang['rank_standard'],
					
					'NAME'				=> $data['rank_name'],
					'MIN'				=> $data['rank_min'],
					'PIC'				=> ( $data['rank_image'] ) ? $path_dir . $data['rank_image'] : $images['icon_acp_spacer'],
					
					'CUR_TYPE'			=> $data['rank_type'],
					'CUR_ORDER'			=> $data['rank_order'],
					
					'S_TYPE_PAGE'		=> ( $data['rank_type'] == RANK_PAGE ) ? 'checked="checked"' : '',
					'S_TYPE_FORUM'		=> ( $data['rank_type'] == RANK_FORUM ) ? 'checked="checked"' : '',
					'S_TYPE_TEAM'		=> ( $data['rank_type'] == RANK_TEAM ) ? 'checked="checked"' : '',
					'S_SPECIAL_YES'		=> ( $data['rank_special'] ) ? 'checked="checked"' : '',
					'S_SPECIAL_NO'		=> ( !$data['rank_special'] ) ? 'checked="checked"' : '',
					'S_STANDARD_YES'	=> ( $data['rank_standard'] ) ? 'checked="checked"' : '',
					'S_STANDARD_NO'		=> ( !$data['rank_standard'] ) ? 'checked="checked"' : '',
					
					'SHOW_FORMS'		=> ( $data['rank_type'] == '2' ) ? '' : 'none',
					'SHOW_NORMAL'		=> ( $data['rank_type'] == '2' ) ? 'none' : '',
					'SHOW_SPECIAL'		=> ( $data['rank_special'] ) ? 'none' : '',
					
					'S_LIST'	=> select_box_files('post', 'ranks', $path_dir, $data['rank_image']),
					'S_ORDER'	=> simple_order(RANKS, $data['rank_type'], 'select', $data['rank_order']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
<<<<<<< .mine
				if ( request('submit', 1) )
				{
					$error .= check(RANKS, array('rank_name' => $data['rank_name'], 'rank_id' => $data_id), $error);
					
					if ( !$error )
					{
						$data['rank_order'] = !$data['rank_order'] ? maxa(RANKS, 'rank_order', 'rank_type = ' . $data['rank_type']) : $data['rank_order'];
						( $data['rank_standard'] ) ? sql(RANKS, 'update', array('rank_standard' => '0'), 'rank_type', $data['rank_type']) : '';
												
						if ( $mode == '_create' )
						{
							$sql = sql(RANKS, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(RANKS, $mode, $data, 'rank_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						orders(RANKS, $data['rank_type']);
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
			
=======
>>>>>>> .r85
				$template->pparse('body');
				
				break;
			
			case '_order':
				
				update(RANKS, 'rank', $move, $data_id);
				orders(RANKS, $data_type);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete':
			
				/*
				 *	im moment wird der rang einfach gelöscht,
				 *	sollte aber meldung geben falls rang verwendet
				 *	wird und löschung sperren!
				 */
			
				$data = data(RANKS, $data_id, false, 1, true);
			
				if ( $data_id && $confirm )
				{
					$sql = sql(RANKS, $mode, $data, 'rank_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders(RANKS, $data['rank_type']);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data_id && !$confirm )
				{
					if ( $data['rank_standard'] )
					{
						message(GENERAL_ERROR, $lang['msg_select_standard'] . $lang['back']);
					}
					else
					{
						$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
						$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";			
			
						$template->assign_vars(array(
							'M_TITLE'	=> $lang['common_confirm'],
							'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['rank_name']),
							
							'S_ACTION'	=> check_sid($file),
							'S_FIELDS'	=> $fields,
						));
					}
				}
				else
				{
					message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['rank']));
				}

				$template->pparse('confirm');
				
				break;
	
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('_display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'		=> sprintf($lang['sprintf_new_createn'], $lang['title']),
		'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['title']),
		'L_EXPLAIN'		=> $lang['explain'],
		
		'L_PAGE'		=> $lang['rank_page'],
		'L_FORUM'		=> $lang['rank_forum'],
		'L_TEAM'		=> $lang['rank_team'],
		'L_SPECIAL'		=> $lang['rank_special'],
		'L_STANDARD'	=> $lang['rank_standard'],
		'L_MIN'			=> $lang['rank_min'],
		
		'S_CREATE'		=> check_sid("$file?mode=_create"),
		'S_ACTION'		=> check_sid($file),
		'S_FIELDS'		=> $fields,
	));
	
	$max_f = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_FORUM . ' AND rank_special = 1');
	$max_p = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_PAGE);
	$max_t = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_TEAM);
	
	$tmp_forum	= data(RANKS, 'rank_type = ' . RANK_FORUM, 'rank_special DESC, rank_order ASC', 1, false);
	$tmp_page	= data(RANKS, 'rank_type = ' . RANK_PAGE, 'rank_special DESC, rank_order ASC', 1, false);
	$tmp_team	= data(RANKS, 'rank_type = ' . RANK_TEAM, 'rank_special DESC, rank_order ASC', 1, false);

	if ( !$tmp_forum )
	{
		$template->assign_block_vars('_display._entry_empty_forum', array());
	}
	else
	{
		for ( $i = 0; $i < count($tmp_forum); $i++ )
		{
			$rank_id	= $tmp_forum[$i]['rank_id'];
			$rank_name	= $tmp_forum[$i]['rank_name'];
			$rank_order	= $tmp_forum[$i]['rank_order'];
				
			$template->assign_block_vars('_display._forum_row', array(
				'NAME'		=> $rank_name,
				'MIN'		=> ( $tmp_forum[$i]['rank_special'] == '0' ) ? $tmp_forum[$i]['rank_min'] : ' - ',
				'SPECIAL'	=> ( $tmp_forum[$i]['rank_special'] == '1' ) ? $lang['common_yes'] : $lang['common_no'],
				
				'MOVE_UP'	=> ( $tmp_forum[$i]['rank_special'] && $rank_order != '10' )	? '<a href="' . check_sid("$file?mode=_order&amp;type=" . RANK_FORUM . "&amp;move=-15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $tmp_forum[$i]['rank_special'] && $rank_order != $max_f )	? '<a href="' . check_sid("$file?mode=_order&amp;type=" . RANK_FORUM . "&amp;move=+15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$rank_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$rank_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	
	if ( !$tmp_page )
	{
		$template->assign_block_vars('_display._entry_empty_page', array());
	}
	else
	{
		for ( $i = 0; $i < count($tmp_page); $i++ )
		{
			$rank_id	= $tmp_page[$i]['rank_id'];
			$rank_name	= $tmp_page[$i]['rank_name'];
			$rank_order	= $tmp_page[$i]['rank_order'];
				
			$template->assign_block_vars('_display._page_row', array(
<<<<<<< .mine
				'NAME'		=> $rank_name,
				'STANDARD'	=> $tmp_page[$i]['rank_standard'] ? $lang['standard'] : '',
=======
				'NAME'		=> $rank_name,
				'STANDARD'	=> $tmp_page[$i]['rank_standard'] ? $lang['rank_standard'] : '',
>>>>>>> .r85
				
				'MOVE_UP'	=> ( $rank_order != '10' )		? '<a href="' . check_sid("$file?mode=_order&amp;type=" . RANK_PAGE . "&amp;move=-15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $rank_order != $max_p )	? '<a href="' . check_sid("$file?mode=_order&amp;type=" . RANK_PAGE . "&amp;move=+15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$rank_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$rank_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	
	if ( !$tmp_team )
	{
		$template->assign_block_vars('_display._entry_empty_team', array());
	}
	else
	{
		for ( $i = 0; $i < count($tmp_team); $i++ )
		{
			$rank_id	= $tmp_team[$i]['rank_id'];
			$rank_name	= $tmp_team[$i]['rank_name'];
			$rank_order	= $tmp_team[$i]['rank_order'];
				
			$template->assign_block_vars('_display._team_row', array(
<<<<<<< .mine
				'NAME'		=> $rank_name,
				'STANDARD'	=> $tmp_team[$i]['rank_standard'] ? $lang['standard'] : '',
=======
				'NAME'		=> $rank_name,
				'STANDARD'	=> $tmp_team[$i]['rank_standard'] ? $lang['rank_standard'] : '',
>>>>>>> .r85
				
				'MOVE_UP'	=> ( $rank_order != '10' )		? '<a href="' . check_sid("$file?mode=_order&amp;type=" . RANK_TEAM . "&amp;move=-15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $rank_order != $max_t )	? '<a href="' . check_sid("$file?mode=_order&amp;type=" . RANK_TEAM . "&amp;move=+15&amp;$url=$rank_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$rank_id") . '" alt="" /><img src="' . $images['icon_option_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
				'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$rank_id") . '" alt="" /><img src="' . $images['icon_option_delete'] . '" title="' . $lang['common_delete'] . '" alt="" /></a>',
			));
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>