<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);

	if ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] )
	{
		$module['hm_clan']['sm_training'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_training';
	
	include('./pagestart.php');
	
	add_lang('training');

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_TRAINING;
	$url	= POST_TRAINING;
	$team	= POST_TEAMS;
	$match	= POST_MATCH;
	$file	= basename(__FILE__);
	$time	= time();
	
	$start		= ( request('start', INT) ) ? request('start', INT) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$team_id	= request($team, INT);
	$match_id	= request($match, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$sort		= request('sort', TXT) ? request('sort', TXT) : '';
	$acp_main	= request('acp_main', INT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
		
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_training'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}

	( $header && !$acp_main )	? redirect('admin/' . check_sid($file, true)) : false;
	( $header && $acp_main )	? redirect('admin/' . check_sid('index.php', true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_training.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'delete')) ) ? $mode : '';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());

			$vars = array(
				'training' => array(
					'title1' => 'input_data',
					'training_vs'		=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name'),
					'team_id'			=> array('validate' => INT,	'type' => 'drop:team',		'explain' => true, 'required' => 'select_team', 'params' => 'request', 'ajax' => 'ajax_listmaps'),
					'match_id'			=> array('validate' => INT,	'type' => 'drop:match',		'explain' => true),
					'training_maps'		=> array('validate' => ARY,	'type' => 'drop:maps', 		'explain' => true, 'required' => 'select_maps'),
					'training_date'		=> array('validate' => INT,	'type' => 'drop:datetime',	'explain' => true, 'params' => ( $mode == 'create' ) ? $time : '-1'),
					'training_duration'	=> array('validate' => INT,	'type' => 'drop:duration',	'explain' => true, 'params' => 'training_date'),
					'training_text'		=> array('validate' => TXT,	'type' => 'textarea:40',	'explain' => true, 'params' => TINY_NORMAL),
					'training_create'	=> array('type' => 'hidden'),
					'training_update'	=> array('type' => 'hidden'),
					'count_comment'		=> array('type' => 'hidden'),
					'training_comments'	=> array('type' => 'hidden'),
				),
			);
			
			if ( $mode == 'create' && !request('submit', TXT) )
			{
				$data = array(
					'training_vs'		=> ( request('training_vs', TXT) ) ? request('training_vs', TXT) : request('vs', TXT),
					'team_id'			=> ( request('team_id', INT) ) ? request('team_id', INT) : $team_id,
					'match_id'			=> request($match, INT),
					'training_maps'		=> '',
					'training_date'		=> $time,
					'training_duration'	=> '',
					'training_text'		=> '',
					'training_create'	=> $time,
					'training_update'	=> $time,
					'count_comment'		=> 0,
					'training_comments'	=> 0,
					
				);
			}
			else if ( $mode == 'update' && !request('submit', TXT) )
			{
				$data = data(TRAINING, $data_id, false, 1, true);
			}
			else
			{
				$data = build_request(TRAINING, $vars, 'training', $error);
				
				if ( !$error )
				{
				#	$data['training_maps'] = is_array($data['training_maps']) ? serialize($data['training_maps']) : array();
					
					if ( $mode == 'create' )
					{
						$sql = sql(TRAINING, $mode, $data);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(TRAINING, $mode, $data, 'training_id', $data_id);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
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
			
			build_output($data, $vars, 'input', false, TRAINING);
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
						
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_INPUT'		=> sprintf($lang["sprintf_$mode"], $lang['title'], $data['training_vs']),
				
				'S_ACTION'		=> check_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			break;
			
		case 'delete':
		
			$data = data(TRAINING, $data_id, false, 1, true);
		
			if ( $data_id && $confirm )
			{
				$file = ( $acp_main ) ? check_sid('index.php') : check_sid($file);
				$name = ( $acp_main ) ? $lang['header_acp'] : $acp_title;
				
				$sql = sql(TRAINING, $mode, $data, 'training_id', $data_id);
				$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $name);
				
			#	sql(COMMENTS, $mode, $data, 'training_id', $data_id);
			#	sql(COMMENTS_READ, $mode, $data, 'training_id', $data_id);
				sql(TRAINING_USERS, $mode, false, 'training_id', $data_id);
					
			#	$oCache -> deleteCache('subnavi_training_*');
				
				log_add(LOG_ADMIN, $log, $mode, $dsql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= "<input type=\"hidden\" name=\"acp_main\" value=\"$acp_main\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['training_vs']),
					
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
			
			$s_sort = $new = $cnt_new = $old = $cnt_old = '';
			
			$teams = data(TEAMS, false, 'team_order', 0, false);
			
			$s_sort .= "<select class=\"selectsmall\" name=\"$team\" onchange=\"if (this.options[this.selectedIndex].value != '') this.form.submit();\">";
			$s_sort .= "<option value=\"0\">" .  sprintf($lang['sprintf_select_format'], $lang['msg_select_team']) . "</option>";
			
			foreach ( $teams as $info => $value )
			{
				$selected = ( $value['team_id'] == $team_id ) ? ' selected="selected"' : '';
				$s_sort .= "<option value=\"" . $value['team_id'] . "\" $selected>" . sprintf($lang['sprintf_select_format'], $value['team_name']) . "</option>";
			}
			
			$s_sort .= "</select>";
			
			$select_id = ( $team_id >= '1' ) ? "AND tr.team_id = $team_id" : '';
			
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
				foreach ( $training as $training => $row )
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
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $vs, $vs),
							'GAME'		=> display_gameicon($new[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $new[$i]['training_date'], $userdata['user_timezone']),
							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
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
					
					for ( $i = $start; $i < $cnt_old; $i++ )
					{
						$id = $old[$i]['training_id'];
						$vs = $old[$i]['training_vs'];
						
						$template->assign_block_vars('display.old_row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $vs, $vs),
							'GAME'		=> display_gameicon($old[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $old[$i]['training_date'], $userdata['user_timezone']),
							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
						));
					}
				}
			}
					
			$current_page = ( !$cnt_old ) ? 1 : ceil($cnt_old/$settings['per_page_entry']['acp']);
			
			$fields .= '<input type="hidden" name="mode" value="create" />';
			
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_CREATE'		=> sprintf($lang['sprintf_create'], $lang['title']),
				'L_EXPLAIN'		=> $lang['explain'],
				'L_UPCOMING'	=> $lang['upcoming'],
				'L_EXPIRED'		=> $lang['expired'],
				
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file?", $cnt_old, $settings['per_page_entry']['acp'], $start ),
				
				'S_SORT'	=> $s_sort,
				'S_TEAMS'	=> select_team($team, false, 'team_id', false),

				'S_CREATE'	=> check_sid("$file?mode=create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>