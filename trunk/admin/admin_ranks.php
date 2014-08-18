<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_ranks',
		'cat'		=> 'settings',
		'modes'		=> array(
			'overview'	=> array('title' => 'acp_overview'),
			'forum'		=> array('title' => 'acp_ranks_forum'),
			'page'		=> array('title' => 'acp_ranks_page'),
			'team'		=> array('title' => 'acp_ranks_team'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_ranks';
	
	include('./pagestart.php');

	add_lang('ranks');
	acl_auth(array('a_team_ranks', 'a_forum_ranks', 'a_user_ranks'));

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_RANK;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$dir_path	= $root_path . $settings['path_ranks'];
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_ranks.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));

#	debug($_POST, '_POST');	

#	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down'))) ? $mode : false;

	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				$template->assign_vars(array('IPATH' => $dir_path));
				
				$vars = array(
					'rank' => array(
						'title1' => 'input_data',
						'rank_name'			=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'rank_image'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:image', 'params' => array($dir_path, array('.png', '.jpg', '.jpeg', '.gif'), true, true)),
						'rank_type'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:ranks', 'params' => array('type', true, false)),
						'rank_special'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno2', 'params' => array('type', false, false), 'divbox' => true),
						'rank_standard'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'params' => array(false, false, false), 'divbox' => true),
						'rank_min'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:5;5', 'divbox' => true),
						'rank_order'		=> 'hidden',
					)
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$data_sql = array(
						'rank_name'		=> request('rank_name', TXT),
						'rank_type'		=> ($action != 'team' ? ($action == 'page' ? RANK_PAGE : RANK_FORUM) : RANK_TEAM),
						'rank_min'		=> 0,
						'rank_special'	=> 5,
						'rank_image'	=> '',
						'rank_standard'	=> 0,
						'rank_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(RANKS, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(RANKS, $vars, $error, $mode);
					
					if ( !$error )
					{
						($data_sql['rank_standard']) ? sql(RANKS, 'update', array('rank_standard' => '0'), 'rank_type', $data_sql['rank_type']) : '';
						
						if ( $data_sql['rank_type'] == RANK_FORUM )
						{
							$data_sql['rank_min']	= ($data_sql['rank_special'] == 4 ? 0 : $data_sql['rank_min']);
							$data_sql['rank_order']	= ($data_sql['rank_special'] == 4 ? 0 : $data_sql['rank_order']);
						}
						
						if ( $mode == 'create' )
						{
							$data_sql['rank_order'] = maxa(RANKS, 'rank_order', "rank_type = {$data_sql['rank_type']}");
							
							$sql = sql(RANKS, $mode, $data_sql);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(RANKS, $mode, $data_sql, 'rank_id', $data);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(RANKS, $vars, $data_sql);
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], lang($data_sql['rank_name'])),
					'L_EXPLAIN'	=> $lang['com_required'],
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
				
			case 'move_up':
			case 'move_down':
			
				move(RANKS, $mode, $order, false, false, false, false, $type, $action);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
			
			case 'delete':
			
				/*	im moment wird der rang einfach gelöscht, sollte aber meldung geben falls rang verwendet wird und löschung sperren!	*/
			
				$data_sql = data(RANKS, $data, false, 1, true);
			
				if ( $data && $confirm )
				{
					$sql = sql(RANKS, $mode, $data_sql, 'rank_id', $data);
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
						$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";			
			
						$template->assign_vars(array(
							'M_TITLE'	=> $lang['com_confirm'],
							'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['rank_name']),
							
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
			acp_footer();
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
	
	$fields = '<input type="hidden" name="mode" value="create" />';
	
	switch ( $action )
	{
		case 'page':
		
			$max = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_PAGE);
			$tmp = data(RANKS, 'WHERE rank_type = ' . RANK_PAGE, 'rank_order ASC', 1, false);
			$typ = RANK_PAGE;
		
			break;
			
		case 'forum':
		
			$max = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_FORUM . ' AND (rank_special = 4 OR rank_special = 5)');
		#	$max = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_FORUM . ' AND (rank_special = 4 OR rank_special = 5)');
			$tmp = data(RANKS, 'WHERE rank_type = ' . RANK_FORUM, 'rank_special ASC, rank_min ASC, rank_order ASC', 1, false);
			$typ = RANK_FORUM;
		
			break;
			
		case 'team':
		
			$max = maxi(RANKS, 'rank_order', 'rank_type = ' . RANK_TEAM);
			$tmp = data(RANKS, 'WHERE rank_type = ' . RANK_TEAM, 'rank_order ASC', 1, false);
			$typ = RANK_TEAM;
		
			break;
	}
	
	if ( !$tmp )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		$cnt = count($tmp);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$id		= $tmp[$i]['rank_id'];
			$name	= $tmp[$i]['rank_name'];
			$image	= $tmp[$i]['rank_image'];
			$order	= $tmp[$i]['rank_order'];
			
			$template->assign_block_vars('display.row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
				'SPECIAL'	=> ($tmp[$i]['rank_standard']) ? $lang['rank_standard'] : ($tmp[$i]['rank_min'] ? $tmp[$i]['rank_min'] : ' - '),
				'IMAGE'		=> ($image) ? img('i_icon', 'icon_image', 'icon_image') : img('i_icon', 'icon_image2', 'icon_image2'),
				
				'MOVE_UP'	=> ($order != '1'	&& (($action == 'forum') ? !$tmp[$i]['rank_min'] : true))	? href('a_img', $file, array('mode' => 'move_up',	'type' => $typ, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ($order != $max	&& (($action == 'forum') ? !$tmp[$i]['rank_min'] : true))	? href('a_img', $file, array('mode' => 'move_down', 'type' => $typ, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
			));
		}
	}

	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['stf_head'], $lang[$action]),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_CREATE'	=> sprintf($lang['stf_create'], $lang[$action]),
		'L_RANKS'	=> $lang['ranks'],
		
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$template->pparse('body');
			
	acp_footer();
}

?>