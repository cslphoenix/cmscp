<?php

if ( !empty($setmodules) )
{
	return false;
}
else
{
	define('IN_CMS', true);

	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_votes';
	
	include('./pagestart.php');
	
	add_lang('votes');

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_VOTES;
	$url	= POST_VOTES;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', INT);
	
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);
		
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_votes'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['notice_auth_fail'], $lang[$current]));
	}

	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_votes.tpl',
	#	'ajax'		=> 'style/inc_request.tpl',
	#	'tiny'		=> 'style/tinymce_normal.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
		#	$template->assign_vars(array('FILE' => 'ajax_listmaps'));
		#	$template->assign_var_from_handle('AJAX', 'ajax');
		#	$template->assign_var_from_handle('TINYMCE', 'tiny');
			
			if ( $mode == 'create' && !$submit )
			{
				$data_sql = array(
							'vote_title'	=> request('vote_title', 2) ? request('training_vs', 2) : '',
							'topic_id'		=> '',
							'vote_text'		=> '',
							'vote_start'	=> time(),
							'vote_end'		=> time(),
						);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(VOTES, $data, false, 1, 'row');
			}
			else
			{
				$date_start	= mktime(request('shour', 0), request('smin', 0), 00, request('smonth', 0), request('sday', 0), request('syear', 0));
				$date_end	= mktime(request('ehour', 0), request('emin', 0), 00, request('emonth', 0), request('eday', 0), request('eyear', 0));
				
				$data_sql = array(
							'vote_title'	=> request('vote_title', 2),
							'topic_id'		=> request('topic_id', 0),
							'vote_text'		=> request('vote_text', 2),
							'vote_start'	=> request('vote_start', 0),
							'vote_end'		=> request('vote_end', 0),
						);
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['training']),
				'L_INPUT'		=> sprintf($lang['STF_' . strtoupper($mode)], $lang['training'], $data_sql['training_vs']),
				'L_VS'			=> $lang['vs'],
				'L_TEAM'		=> $lang['team'],
				'L_MATCH'		=> $lang['match'],
				'L_DATE'		=> $lang['date'],
				'L_DURATION'	=> $lang['duration'],
				'L_MAPS'		=> $lang['maps'],
				'L_TEXT'		=> $lang['text'],

				'VS'			=> $data_sql['training_vs'],
				'MAPS'			=> $data['training_maps'],
				'TEXT'			=> $data['training_text'],
				
				'S_TEAMS'		=> $s_teams,
				'S_MAPS'		=> $s_select,
				'S_MATCH'		=> select_box('match',	'select', $data['match_id']),
				
				'S_DAY'			=> s_date('selectsmall', 'day',		'day',		date('d', $data['training_date']), $data['training_create']),
				'S_MONTH'		=> s_date('selectsmall', 'month',		'month',	date('m', $data['training_date']), $data['training_create']),
				'S_YEAR'		=> s_date('selectsmall', 'year',		'year',		date('Y', $data['training_date']), $data['training_create']),
				'S_HOUR'		=> s_date('selectsmall', 'hour',		'hour',		date('H', $data['training_date']), $data['training_create']),
				'S_MIN'			=> s_date('selectsmall', 'min',		'min',		date('i', $data['training_date']), $data['training_create']),
				'S_DURATION'	=> s_date('selectsmall', 'duration',	'dmin',		( $data['training_duration'] - $data['training_date'] ) / 60),
				
				'S_ACTION'		=> check_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			if ( $submit )
			{
				$error[] = ( !$data_sql['training_vs'] )				? ( $error ? '<br />' : '' ) . $lang['msg_select_rival'] : '';
				$error[] = ( $data['team_id'] == '-1' )			? ( $error ? '<br />' : '' ) . $lang['msg_select_team'] : '';
				$error[] = ( !$data['training_maps'] )			? ( $error ? '<br />' : '' ) . $lang['NOTICE_SELECT_MAP'] : '';
				$error[] = ( time() >= $data['training_date'] )	? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				$error[] = ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
				
				if ( !$error )
				{
					$data['training_maps'] = is_array($data['training_maps']) ? serialize($data['training_maps']) : array();
					
					if ( $mode == 'create' )
					{
						$sql = sql(VOTES, $mode, $data_sql);
						$msg = $lang['CREATE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else
					{
						$sql = sql(VOTES, $mode, $data_sql, 'training_id', $data);
						$msg = $lang['UPDATE'] . sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
			
			break;
			
		case 'delete':
		
			$data_sql = data(VOTES, $data, false, 1, 'row');
		
			if ( $data && $confirm )
			{
				$sql = sql(VOTES, $mode, $data_sql, 'training_id', $data);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
				
			#	sql(COMMENTS, $mode, $data_sql, 'training_id', $data);
			#	sql(COMMENTS_READ, $mode, $data_sql, 'training_id', $data);
				sql(VOTES_USERS, $mode, false, 'training_id', $data);
					
			#	$oCache -> deleteCache('subnavi_training_*');
				
				log_add(LOG_ADMIN, $log, $mode, $dsql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( $data_id && !$confirm )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['training_vs']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}
			
			$template->pparse('confirm');
			
			break;
						
		default:
		
			$template->assign_block_vars('display', array());
			/*
			$teams = data(TEAMS, false, 'team_order', 0, false);
			
			$s_sort = "<select class=\"selectsmall\" name=\"$team\" onchange=\"if (this.options[this.selectedIndex].value != '') this.form.submit();\">";
			$s_sort .= "<option value=\"0\">" .  sprintf($lang['STF_SELECT_FORMAT'], $lang['msg_select_team']) . "</option>";
			
			foreach ( $teams as $info => $value )
			{
				$selected = ( $value['team_id'] == $team_id ) ? ' selected="selected"' : '';
				$s_sort .= "<option value=\"" . $value['team_id'] . "\" $selected>" . sprintf($lang['STF_SELECT_FORMAT'], $value['team_name']) . "</option>";
			}
			$s_sort .= "</select>";
			
			$fields .= '<input type="hidden" name="mode" value="create" />';
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['training']),
				'L_CREATE'		=> sprintf($lang['sprintf_new_creates'], $lang['training']),
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				'L_UPCOMING'	=> $lang['upcoming'],
				'L_EXPIRED'		=> $lang['expired'],
				
				'S_SORT'	=> $s_sort,
				'S_TEAMS'	=> select_box('team', 'selectsmall', 0),
				
				'S_CREATE'	=> check_sid("$file?mode=create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			$select_id = ( $team_id >= '1' ) ? "AND tr.team_id = $team_id" : '';
			
			$sql = "SELECT tr.*, g.game_image
						FROM " . VOTES . " tr, " . TEAMS . " t, " . GAMES . " g
						WHERE tr.team_id = t.team_id AND t.team_game = g.game_id $select_id
					ORDER BY training_date";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$training = $db->sql_fetchrowset($result);
			
			if ( !$training )
			{
				$template->assign_block_vars('display.entry_empty_new', array());
				$template->assign_block_vars('display.entry_empty_old', array());
			}
			else
			{
				$training_new = $training_old = array();
					
				foreach ( $training as $training => $row )
				{
					if ( $row['training_date'] > time() )
					{
						$training_new[] = $row;
					}
					else if ( $row['training_date'] < time() )
					{
						$training_old[] = $row;
					}
				}
				
				if ( !$training_new )
				{
					$template->assign_block_vars('display.entry_empty_new', array());
				}
				else
				{
					for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, count($training_new)); $i++ )
					{
						$training_id = $training_new[$i]['training_id'];
						
						$template->assign_block_vars('display.training_new_row', array(
							'NAME'	=> $training_new[$i]['training_vs'],
							'GAME'	=> display_gameicon($training_new[$i]['game_size'], $training_new[$i]['game_image']),
							'DATE'	=> create_date($userdata['user_dateformat'], $training_new[$i]['training_date'], $userdata['user_timezone']),
							
							'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$training_id") . '" alt="" /><img src="' . $images['icon_update'] . '" title="' . $lang['COMMON_UPDATE'] . '" alt="" /></a>',
							'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$training_id") . '" alt="" /><img src="' . $images['icon_cancel'] . '" title="' . $lang['COMMON_DELETE'] . '" alt="" /></a>',
						));
					}
				}
				
				if ( !$training_old )
				{
					$template->assign_block_vars('display.entry_empty_old', array());
				}
				else
				{
					for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, count($training_old)); $i++ )
					{
						$training_id = $training_old[$i]['training_id'];
						
						$template->assign_block_vars('display.training_old_row', array(
							'NAME'	=> $training_old[$i]['training_vs'],
							'GAME'	=> display_gameicon($training_old[$i]['game_size'], $training_old[$i]['game_image']),
							'DATE'	=> create_date($userdata['user_dateformat'], $training_old[$i]['training_date'], $userdata['user_timezone']),
							
							'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$training_id") . '" alt="" /><img src="' . $images['icon_update'] . '" title="' . $lang['COMMON_UPDATE'] . '" alt="" /></a>',
							'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$training_id") . '" alt="" /><img src="' . $images['icon_cancel'] . '" title="' . $lang['COMMON_DELETE'] . '" alt="" /></a>',
						));
					}
				}
			}
					
			$current_page = ( !count($training) ) ? 1 : ceil( count($training) / $settings['per_page_entry']['acp'] );
		
			$template->assign_vars(array(
				'PAGE_NUMBER' => ( count($training) ) ? sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ) : '',
				'PAGE_PAGING' => ( count($training) ) ? generate_pagination('admin_match.php?', count($training), $settings['per_page_entry']['acp'], $start) : '',
			));
			*/
			break;
	}

	$template->pparse('body');

	acp_footer();
}

?>