<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_RANKS',
		'CAT'		=> 'SETTINGS',
		'MODES'		=> array(
			'OVERVIEW'	=> array('TITLE' => 'ACP_OVERVIEW'),
			'FORUM'		=> array('TITLE' => 'ACP_RANKS_FORUM'),
			'PAGE'		=> array('TITLE' => 'ACP_RANKS_PAGE'),
			'TEAM'		=> array('TITLE' => 'ACP_RANKS_TEAM'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_RANKS';
	
	include('./pagestart.php');

	add_lang('ranks');
	acl_auth(array('A_TEAM_RANKS', 'A_FORUM_RANKS', 'A_USER_RANKS'));

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_RANK;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);

	$cancel ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down'))) ? $mode : false;
	$path = $root_path . $settings['path']['ranks'];
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);
	
	debug($path, '$path');

	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			$template->assign_vars(array('IPATH' => $path));
			
			$vars = array(
				'rank' => array(
					'title' => 'INPUT_DATA',
					'rank_name'			=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'rank_image'		=> array('validate' => TXT,	'explain' => false,	'type' => 'drop:image', 'params' => array($path, array('.png', '.jpg', '.jpeg', '.gif'), false)),
					'rank_type'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:ranks', 'params' => array('type', true, false)),
					'rank_special'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:special', 'params' => array('type', false, false), 'divbox' => true),
					'rank_standard'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'params' => array(false, false, false), 'divbox' => true),
					'rank_min'			=> array('validate' => INT,	'explain' => false,	'type' => 'text:5;5', 'divbox' => true),
					'rank_order'		=> 'hidden',
				)
			);
			
			$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
			
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
				$data_sql = data(RANKS, $data, false, 1, 'row');
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
						$data_sql['rank_order'] = _max(RANKS, 'rank_order', "rank_type = {$data_sql['rank_type']}");
						
						$sql = sql(RANKS, $mode, $data_sql);
						$msg = $lang['CREATE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else
					{
						$sql = sql(RANKS, $mode, $data_sql, 'rank_id', $data);
						$msg = $lang['UPDATE'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['rank_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'	=> $fields,
			));
			
			break;
		
		case 'delete':
		
			/*	im moment wird der rang einfach gelöscht, sollte aber meldung geben falls rang verwendet wird und löschung sperren!	*/
		
			$data_sql = data(RANKS, $data, false, 1, 'row');
		
			if ( $data && $confirm )
			{
				$sql = sql(RANKS, $mode, $data_sql, 'rank_id', $data);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
				
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
						'M_TITLE'	=> $lang['COMMON_CONFIRM'],
						'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data['rank_name']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}

			break;
				
		case 'move_up':
		case 'move_down':
		
			move(RANKS, $mode, $order, false, false, false, false, $type, $action);
			log_add(LOG_ADMIN, $log, $mode);
		
		default:
		
			$template->assign_block_vars('display', array());
			
			$fields = build_fields(array('mode' => 'create'));
			
			switch ( $action )
			{
				case 'overview':$sqlout = data(RANKS, false, 'rank_type ASC ,rank_order ASC', 1, 'group', false, false, 'rank_type');	break;
				case 'page':	$sqlout = data(RANKS, 'WHERE rank_type = ' . RANK_PAGE, 'rank_order ASC', 1, 'group', false, false, 'rank_type');	break;
				case 'forum':	$sqlout = data(RANKS, 'WHERE rank_type = ' . RANK_FORUM, 'rank_order ASC', 1, 'group', false, false, 'rank_type');	break;
				case 'team':	$sqlout = data(RANKS, 'WHERE rank_type = ' . RANK_TEAM, 'rank_order ASC', 1, 'group', false, false, 'rank_type');	break;
			}
			
			if ( !$sqlout )
			{
				$template->assign_block_vars('display.no_entry', array(
					'RANKS'	=> ( $action != 'page' ) ? (( $action == 'forum' ) ? $lang['FORUM'] : (( $action == 'team' ) ? $lang['TEAM'] : $lang['OVERVIEW'])) : $lang['PAGE'],
				));
			}
			else
			{
				$max_cnt = 0;
				$max_tmp = count($sqlout);
				
				foreach ( $sqlout as $typ => $row )
				{
					if ( $row )
					{
						$template->assign_block_vars('display.typ', array(
							'RANKS'	=> ( $typ != 1 ) ? (( $typ == 2 ) ? $lang['forum'] : $lang['team']) : $lang['page'],
						));
						
						$max = count($row);
						
						foreach ( $row as $tmp_row )
						{
							$id		= $tmp_row['rank_id'];
							$name	= $tmp_row['rank_name'];
							$image	= $tmp_row['rank_image'];
							$order	= $tmp_row['rank_order'];
							
							$template->assign_block_vars('display.typ.row', array(
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $name, $name),
								'SPECIAL'	=> ($tmp_row['rank_standard']) ? $lang['RANK_STANDARD'] : ($tmp_row['rank_min'] ? $tmp_row['rank_min'] : ' - '),
								'IMAGE'		=> ($image) ? img('i_icon', 'icon_image', 'icon_image') : img('i_icon', 'icon_image2', 'icon_image2'),
								
								'MOVE_UP'	=> ($order != '1'	&& (($action == 'forum') ? !$tmp_row['rank_min'] : true))	? href('a_img', $file, array('mode' => 'move_up',	'type' => $typ, 'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
								'MOVE_DOWN'	=> ($order != $max	&& (($action == 'forum') ? !$tmp_row['rank_min'] : true))	? href('a_img', $file, array('mode' => 'move_down', 'type' => $typ, 'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
								
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),
							));
						}
					}
											
					$max_cnt++;
					
					if ( $max_tmp != $max_cnt )
					{
						$template->assign_block_vars('display.typ.br_empty', array());
					}
				}
			}
		
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang[strtoupper($action)]),
				'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang[( $action != 'page' ) ? (( $action == 'forum' ) ? 'FORUM': (( $action == 'team' ) ? 'TEAM' : 'TITLE')) : 'PAGE']),
				
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>