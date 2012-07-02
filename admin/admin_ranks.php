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
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_ranks';
	
	include('./pagestart.php');

	add_lang('ranks');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_RANK;
	$url	= POST_RANKS;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_type	= request('type', INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$dir_path	= $root_path . $settings['path_ranks'];
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_ranks'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
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
	
	$mode = ( in_array($mode, array('create', 'update', 'order', 'delete')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$template->assign_vars(array('PATH' => $dir_path));
								
				$template->assign_var_from_handle('AJAX', 'ajax');
				$template->assign_var_from_handle('UIMG', 'uimg');
				
				$vars = array(
					'ranks' => array(
						'title1' => 'input',
						'rank_name'			=> array('validate' => 'text',	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name'),
						'rank_image'		=> array('validate' => 'image',	'type' => 'drop:image',		'explain' => true),
						'rank_type'			=> array('validate' => 'int',	'type' => 'radio:ranks',	'explain' => true),
						'rank_min'			=> array('validate' => 'int',	'type' => 'text:5:5',		'explain' => true),
						'rank_special'		=> array('validate' => 'int',	'type' => 'radio:yesno',	'explain' => true),
						'rank_order'		=> array('validate' => 'int',	'type' => 'drop:order',		'explain' => true),
					),
				);
				
				if ( $mode == 'create' && !request('submit', TXT) )
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
					#	'rank_order'	=> maxa(RANKS, 'rank_order', "rank_type = $type"),
						'rank_order'	=> '',
					);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
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
												
						if ( $mode == 'create' )
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
				
				build_output($data, $vars, 'input', false, RANKS);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'			=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['rank_name']),
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
					'PIC'				=> $data['rank_image'] ? $dir_path . $data['rank_image'] : $images['icon_spacer'],
					
					'CUR_TYPE'			=> $data['rank_type'],
					'CUR_ORDER'			=> $data['rank_order'],
					
				#	'S_TYPE_PAGE'		=> ( $data['rank_type'] == RANK_PAGE ) ? 'checked="checked"' : '',
				#	'S_TYPE_FORUM'		=> ( $data['rank_type'] == RANK_FORUM ) ? 'checked="checked"' : '',
				#	'S_TYPE_TEAM'		=> ( $data['rank_type'] == RANK_TEAM ) ? 'checked="checked"' : '',
				#	'S_SPECIAL_YES'		=> ( $data['rank_special'] ) ? 'checked="checked"' : '',
				#	'S_SPECIAL_NO'		=> ( !$data['rank_special'] ) ? 'checked="checked"' : '',
				#	'S_STANDARD_YES'	=> ( $data['rank_standard'] ) ? 'checked="checked"' : '',
				#	'S_STANDARD_NO'		=> ( !$data['rank_standard'] ) ? 'checked="checked"' : '',
					
				#	'SHOW_FORMS'		=> ( $data['rank_type'] == RANK_FORUM ) ? '' : 'none',
				#	'SHOW_NORMAL'		=> ( $data['rank_type'] == RANK_FORUM ) ? 'none' : '',
				#	'SHOW_POSTS'		=> ( $data['rank_type'] == RANK_FORUM && !$data['rank_special'] ) ? '' : 'none',
					
				#	'S_LIST'	=> select_box_files('post', 'ranks', $dir_path, $data['rank_image']),
				#	'S_ORDER'	=> simple_order(RANKS, $data['rank_type'], 'select', $data['rank_order']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
			
			case 'order':
				
				update(RANKS, 'rank', $move, $data_id);
				orders(RANKS, $data_type);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case 'delete':
			
				/*	im moment wird der rang einfach gelöscht, sollte aber meldung geben falls rang verwendet wird und löschung sperren!	*/
			
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
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}

				$template->pparse('confirm');
				
				break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
	
	$fields = '<input type="hidden" name="mode" value="create" />';

	$max_f = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_FORUM . ' AND rank_special = 1');
	$max_p = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_PAGE);
	$max_t = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_TEAM);
	
	$tmp_f = data(RANKS, 'rank_type = ' . RANK_FORUM, 'rank_special DESC, rank_order ASC', 1, false);
	$tmp_p = data(RANKS, 'rank_type = ' . RANK_PAGE, 'rank_special DESC, rank_order ASC', 1, false);
	$tmp_t = data(RANKS, 'rank_type = ' . RANK_TEAM, 'rank_special DESC, rank_order ASC', 1, false);

	if ( !$tmp_f )
	{
		$template->assign_block_vars('display.forum_empty', array());
	}
	else
	{
		$cnt_f = count($tmp_f);
		
		for ( $i = 0; $i < $cnt_f; $i++ )
		{
			$id		= $tmp_f[$i]['rank_id'];
			$name	= $tmp_f[$i]['rank_name'];
			$image	= $tmp_f[$i]['rank_image'];
			$order	= $tmp_f[$i]['rank_order'];
			
			$template->assign_block_vars('display.forum_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
				'MIN'		=> ( $tmp_f[$i]['rank_special'] == '0' ) ? $tmp_f[$i]['rank_min'] : ' - ',
				'SPECIAL'	=> ( $tmp_f[$i]['rank_special'] == '1' ) ? $lang['common_yes'] : $lang['common_no'],
				'IMAGE'		=> $image ? img('i_icon', 'icon_image', '') : img('i_icon', 'icon_image2', ''),
				
				'MOVE_UP'	=> ( $tmp_f[$i]['rank_special'] && $order != '10' )		? href('a_img', $file, array('mode' => '_order', 'type' => RANK_FORUM, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $tmp_f[$i]['rank_special'] && $order != $max_f )	? href('a_img', $file, array('mode' => '_order', 'type' => RANK_FORUM, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_p )
	{
		$template->assign_block_vars('display.page_empty', array());
	}
	else
	{
		$cnt_p = count($tmp_p);
		
		for ( $i = 0; $i < $cnt_p; $i++ )
		{
			$id		= $tmp_p[$i]['rank_id'];
			$name	= $tmp_p[$i]['rank_name'];
			$image	= $tmp_p[$i]['rank_image'];
			$order	= $tmp_p[$i]['rank_order'];
				
			$template->assign_block_vars('display.page_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
				'STANDARD'	=> $tmp_p[$i]['rank_standard'] ? $lang['rank_standard'] : '',
				'IMAGE'		=> $image ? img('i_icon', 'icon_image', '') : img('i_icon', 'icon_image2', ''),
				
				'MOVE_UP'	=> ( $order != '10' )	? href('a_img', $file, array('mode' => '_order', 'type' => RANK_PAGE, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_p )	? href('a_img', $file, array('mode' => '_order', 'type' => RANK_PAGE, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_t )
	{
		$template->assign_block_vars('display.team_empty', array());
	}
	else
	{
		$cnt_t = count($tmp_t);
		
		for ( $i = 0; $i < $cnt_t; $i++ )
		{
			$id		= $tmp_t[$i]['rank_id'];
			$name	= $tmp_t[$i]['rank_name'];
			$image	= $tmp_t[$i]['rank_image'];
			$order	= $tmp_t[$i]['rank_order'];
				
			$template->assign_block_vars('display.team_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $name, ''),
				'STANDARD'	=> $tmp_t[$i]['rank_standard'] ? $lang['rank_standard'] : '',
				'IMAGE'		=> $image ? img('i_icon', 'icon_image', '') : img('i_icon', 'icon_image2', ''),
				
				'MOVE_UP'	=> ( $order != '10' )	? href('a_img', $file, array('mode' => '_order', 'type' => RANK_TEAM, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_t )	? href('a_img', $file, array('mode' => '_order', 'type' => RANK_TEAM, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
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
		
		'S_CREATE'		=> check_sid("$file?mode=create"),
		'S_ACTION'		=> check_sid($file),
		'S_FIELDS'		=> $fields,
	));
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>