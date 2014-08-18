<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_training',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_training'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_training';
	
	include('./pagestart.php');
	
	add_lang('training');
	acl_auth(array('a_training', 'a_training_create', 'a_training_delete', 'a_training_manage'));
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$log	= SECTION_TRAINING;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$sub	= request('sub', TYP);
	$subs	= request('subs', TYP);
	$mode	= request('mode', TYP);
	$sort	= request('sort', TYP) ;
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$m_id	= request('m_id', INT);
	$t_id	= request('t_id', INT);
	
	$acp_main	= request('acp_main', INT);
	
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	( $cancel && !$acp_main )	? redirect('admin/' . check_sid(basename(__FILE__) . $adds, true)) : false;
	( $cancel && $acp_main )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_training.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$base = $settings['switch']['training'];
	$comt = $settings['comments']['training'];
	$mode = (in_array($mode, array('create', 'update', 'delete'))) ? $mode : false;
	
	switch ( $mode )
	{
		case 'create':	acl_auth('a_training_create');
		case 'update':	acl_auth('a_training');
		
			$template->assign_block_vars('input', array());

			$vars = array(
				'training' => array(
					'title' => 'input_data',
					'training_vs'		=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25',		'required' => 'input_rival'),
					'team_id'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:team',		'required' => 'select_team', 'params' => array('request', 'training_maps')),
					'match_id'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:match'),
					'training_maps'		=> array('validate' => ARY,	'explain' => false,	'type' => 'drop:maps',		'required' => 'select_maps'),
				#	'training_date'		=> array('validate' => INT,	'explain' => false,	'type' => 'drop:datetime',	'params' => ( $mode == 'create' ) ? $time : '-1'),
					'training_date'		=> array('validate' => ($base ? INT : TXT), 'type' => ($base ? 'drop:datetime' : 'text:25;25'), 'params' => ($base ? (($mode == 'create') ? $time : '-1') : 'format')),
					'training_duration'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:duration',	'params' => 'training_date'),
					'training_text'		=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40',	'params' => TINY_NORMAL, 'class' => 'tinymce'),
					'training_comments'	=> ( $comt ) ? array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno') : 'hidden',
					'time_create'		=> 'hidden',
					'time_update'		=> 'hidden',
				),
			);
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
					'training_vs'		=> ( request('training_vs', TXT) ) ? request('training_vs', TXT) : request('vs', TXT),
					'team_id'			=> $t_id,
					'match_id'			=> $m_id,
					'training_maps'		=> 'a:0:{}',
					'training_date'		=> $time,
					'training_duration'	=> '',
					'training_text'		=> '',
					'training_comments'	=> $settings['comments']['training'],
					'time_create'		=> $time,
					'time_update'		=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(TRAINING, $data, false, 1, true);
			}
			else
			{
				$data_sql = build_request(TRAINING, $vars, $error, $mode);
				
				if ( !$error )
				{
				#	$data_sql['training_maps'] = is_array($data_sql['training_maps']) ? serialize($data_sql['training_maps']) : array();
					
					if ( $mode == 'create' )
					{
						$sql = sql(TRAINING, $mode, $data_sql);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(TRAINING, $mode, $data_sql, 'training_id', $data);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
					}
					
				#	$oCache -> deleteCache('cal_sn_' . request('month', 0) . '_member');
				#	$oCache -> deleteCache('subnavi_training_' . request('month', 0));
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
			}
			
			build_output(TRAINING, $vars, $data_sql);
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['training_vs']),
				'L_EXPLAIN'	=> $lang['com_required'],
				
				'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'	=> $fields,
			));
			
			break;
			
		case 'delete':	acl_auth('a_training_delete');
		
			$data_sql = data(TRAINING, $data, false, 1, true);
		
			if ( $data && $confirm )
			{
				$file = ( $acp_main ) ? check_sid('index.php') : check_sid($file);
				$name = ( $acp_main ) ? $lang['acp_overview'] : $acp_title;
				
				$sql = sql(TRAINING, $mode, $data_sql, 'training_id', $data);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $name);
				
			#	sql(COMMENTS, $mode, $data_sql, 'training_id', $data);
			#	sql(COMMENTS_READ, $mode, $data_sql, 'training_id', $data);
				sql(TRAINING_USERS, $mode, false, 'training_id', $data);
					
			#	$oCache -> deleteCache('subnavi_training_*');
				
				log_add(LOG_ADMIN, $log, $mode, $dsql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				$fields .= "<input type=\"hidden\" name=\"acp_main\" value=\"$acp_main\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['com_confirm'],
					'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data_sql['training_vs']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
			}
			
			$template->pparse('confirm');
			
			break;
						
		default:
		
			$template->assign_block_vars('display', array());
			
			$new = $cnt_new = $old = $cnt_old = '';
			
			$select_id = ( $t_id > 0 ) ? "AND tr.team_id = $t_id" : '';
			
			$sql = "SELECT tr.*, g.game_image
						FROM " . TRAINING . " tr, " . TEAMS . " t, " . GAMES . " g
							WHERE tr.team_id = t.team_id AND t.team_game = g.game_id $select_id
						ORDER BY training_date";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$training = $db->sql_fetchrowset($result);
			
			if ( !$training )
			{
				$template->assign_block_vars('display.new_empty', array());
				$template->assign_block_vars('display.old_empty', array());
			}
			else
			{
				foreach ( $training as $data => $row )
				{
					if ( $row['training_date'] > time() )
					{
						$new[] = $row;
					}
					else if ( $row['training_date'] < time() )
					{
						$old[] = $row;
					}
				}
				
				if ( !$new )
				{
					$template->assign_block_vars('display.new_empty', array());
				}
				else
				{
					$cnt_new = count($new);
					
					for ( $i = 0; $i < $cnt_new; $i++ )
					{
						$id = $new[$i]['training_id'];
						$vs = $new[$i]['training_vs'];
						
						$template->assign_block_vars('display.new_row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $vs, $vs),
							'GAME'		=> display_gameicon($new[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $new[$i]['training_date'], $userdata['user_timezone']),
							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
						));
					}
				}
				
				if ( !$old )
				{
					$template->assign_block_vars('display.old_empty', array());
				}
				else
				{
					$cnt_old = count($old);
					
				#	for ( $j = $start; $j < $cnt_old; $j++ )
					for ( $j = $start; $j < min($settings['ppe_acp'] + $start, $cnt_old); $j++ )
					{
						$id = $old[$j]['training_id'];
						$vs = $old[$j]['training_vs'];
						
						$template->assign_block_vars('display.old_row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $id), $vs, $vs),
							'GAME'		=> display_gameicon($old[$j]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $old[$j]['training_date'], $userdata['user_timezone']),
							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
						));
					}
				}
			}
					
			$current_page = ( !$cnt_old ) ? 1 : ceil($cnt_old/5);
			
			$fields = '<input type="hidden" name="mode" value="create" />';
			
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['stf_head'], $lang['title']),
				'L_CREATE'		=> sprintf($lang['stf_create'], $lang['title']),
				'L_EXPLAIN'		=> $lang['explain'],
				'L_UPCOMING'	=> $lang['upcoming'],
				'L_EXPIRED'		=> $lang['expired'],
				
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / 5 ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination($file, $cnt_old, 5, $start ),
				
			#	'S_SORT'	=> select_team($t_id, '', 't_id', 'submit', 'selectsmall'),
			#	'S_TEAMS'	=> select_team($t_id, false, 't_id', false),
				
				'S_SORT'	=> select_team($t_id, '', 't_id', 'submit', 'selectsmall'),
				'S_TEAM'	=> select_team($t_id, '', 't_id', false, 'selectsmall'),

				'S_CREATE'	=> check_sid("$file&mode=create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}

	$template->pparse('body');

	acp_footer();
}

?>