<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);

	if ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] )
	{
		$module['hm_teams']['sm_training'] = $root_file;
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
		'ajax'		=> 'style/inc_request.tpl',
		'tiny'		=> 'style/tinymce_normal.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = ( in_array($mode, array('create', 'update', 'delete')) ) ? $mode : '';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$template->assign_vars(array('FILE' => 'ajax_listmaps'));
			$template->assign_var_from_handle('AJAX', 'ajax');
			$template->assign_var_from_handle('TINYMCE', 'tiny');
			
			$vars = array(
				'training' => array(
					'title1' => 'input_data',
					'training_vs'		=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name'),
					'team_id'			=> array('validate' => INT,	'type' => 'drop:team',		'explain' => true, 'required' => 'select_team'),
					'match_id'			=> array('validate' => INT,	'type' => 'drop:match',		'explain' => true),
					'training_maps'		=> array('validate' => ARY,	'type' => 'drop:maps', 		'explain' => true, 'required' => 'select_maps'),
					'training_date'		=> array('validate' => INT,	'type' => 'drop:datetime',	'explain' => true, 'params' => $time),
					'training_duration'	=> array('validate' => INT,	'type' => 'drop:duration',	'explain' => true, 'params' => $time),
					'training_text'		=> array('validate' => TXT,	'type' => 'textarea:40',	'explain' => true),
					'training_create'	=> 'hidden',
				),
			);
			
			if ( $mode == 'create' && !request('submit', TXT) )
			{
				$data = array(
					'training_vs'		=> ( request('training_vs', TXT) ) ? request('training_vs', TXT) : request('vs', TXT),
					'team_id'			=> ( request('team_id', INT) ) ? request('team_id', INT) : $team_id,
					'match_id'			=> request($match, INT),
					'training_maps'		=> '',
					'training_text'		=> '',
					'training_date'		=> $time,
					'training_create'	=> $time,
					'training_duration'	=> '',
				);
			}
			else if ( $mode == 'update' && !request('submit', TXT) )
			{
				$data = data(TRAINING, $data_id, false, 1, true);
				
		#		$data['training_maps'] = unserialize($data['training_maps']);
			}
			else
			{
				$temp = data(TRAINING, $data_id, false, 1, true);
				$temp = array_keys($temp);
				unset($temp[0]);
				
				$data = build_request($temp, $vars, 'training', $error);
				
				/*
				$training_date	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$training_dura	= mktime(request('hour', 0), request('min', 0) + request('dmin', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				$data = array(
							'training_vs'		=> request('training_vs', 2),
							'team_id'			=> request('team_id', 0),
							'match_id'			=> request('match_id', 0),
							'training_maps'		=> request('training_maps', 4),
							'training_text'		=> request('training_text', 2),
							'training_date'		=> $training_date,
							'training_create'	=> request('training_create', 0),
							'training_duration'	=> ( $training_dura - $training_date ) / 60,
						);
						
				$error .= ( !$data['training_vs'] )				? ( $error ? '<br />' : '' ) . $lang['msg_select_rival'] : '';
				$error .= ( $data['team_id'] == '-1' )			? ( $error ? '<br />' : '' ) . $lang['msg_select_team'] : '';
				$error .= ( !$data['training_maps'] )			? ( $error ? '<br />' : '' ) . $lang['msg_select_map'] : '';
				$error .= ( time() >= $data['training_date'] )	? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
				
				if ( !$error )
				{
					$data['training_maps'] = is_array($data['training_maps']) ? serialize($data['training_maps']) : array();
					
					if ( $mode == 'create' )
					{
						$sql = sql(TRAINING, $mode, $data);
						$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$sql = sql(TRAINING, $mode, $data, 'training_id', $data_id);
						$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
					}
					
				#	$oCache -> deleteCache('cal_sn_' . request('month', 0) . '_member');
				#	$oCache -> deleteCache('subnavi_training_' . request('month', 0));
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					$template->assign_vars(array('ERROR_MESSAGE' => $error));
					$template->assign_var_from_handle('ERROR_BOX', 'error');
					
					log_add(LOG_ADMIN, $log, 'error', $error);
				}
				
				if ( $data['training_maps'] )
				{
					$ary_maps = '';
					
					$maps = $data['training_maps'];
					
					for ( $i = 0; $i < count($maps); $i++ )
					{
						if ( empty($maps[$i]) || $maps[$i] == '0' )
						{
							false;
						}
						else
						{
							$ary_maps[] = $maps[$i];
						}
					}
					
					$data['training_maps'] = is_array($ary_maps) ? $ary_maps : array();
				}
				*/
				
				
			}
			
			build_output($data, $vars, 'input', false, TRAINING);
			
			/*		
			$sql = 'SELECT team_id, team_name FROM ' . TEAMS . ' ORDER BY team_order';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$teams = $db->sql_fetchrowset($result);
			
			$s_teams = '';
				
			if ( $teams )
			{	
				$s_teams .= "<select class=\"select\" name=\"team_id\" id=\"team_id\" onchange=\"setRequest(this.options[selectedIndex].value);\">";	
				$s_teams .= "<option value=\"-1\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_team']) . "</option>";
				
				foreach ( $teams as $info => $value )
				{
					$selected = ( $data['team_id'] == $value['team_id'] ) ? ' selected="selected"' : '';
					$s_teams .= "<option value=\"" . $value['team_id'] . "\"$selected>" . sprintf($lang['sprintf_select_format'], $value['team_name']) . "</option>";
				}
				
				$s_teams .= "</select>";
			}
			
			if ( $data['team_id'] > 0 )
			{
				$sql = "SELECT mc.*
							FROM " . MAPS_CAT . " mc
								LEFT JOIN " . TEAMS . " t ON t.team_id = " . $data['team_id'] . "
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
						WHERE mc.cat_tag = g.game_tag";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cats = $db->sql_fetchrow($result);
				
				$maps = $cats ? data(MAPS, "cat_id = " . $cats['cat_id'], 'map_order ASC', 1, false) : false;
				
				$s_select = '';
				
				if ( $maps )
				{
					$s_select .= "<div><div><select class=\"selectsmall\" name=\"training_maps[]\" id=\"training_maps\">";
					$s_select .= "<option selected=\"selected\" value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . "</option>";
					
					$cat_id		= $cats['cat_id'];
					$cat_name	= $cats['cat_name'];
					
					$s_maps = '';
					
					for ( $j = 0; $j < count($maps); $j++ )
					{
						$map_id		= $maps[$j]['map_id'];
						$map_cat	= $maps[$j]['cat_id'];
						$map_name	= $maps[$j]['map_name'];

						$s_maps .= ( $cat_id == $map_cat ) ? "<option value=\"$map_id\">" . sprintf($lang['sprintf_select_format'], $map_name) . "</option>" : '';
					}
					
					$s_select .= ( $s_maps != '' ) ? "<optgroup label=\"$cat_name\">$s_maps</optgroup>" : '';
					$s_select .= "</select>&nbsp;<input type=\"button\" class=\"more\" value=\"" . $lang['common_more'] . "\" onclick=\"clone(this)\"></div></div>";
				}
				else
				{
					$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
				}
			}
			else
			{
				$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_select_team_first']);
			}
			*/
			/*
			if ( $data['training_maps'] )
			{
				$maps = $data['training_maps'];
				
				$sql = "SELECT mc.*
							FROM " . MAPS_CAT . " mc
								LEFT JOIN " . TEAMS . " t ON t.team_id = " . $data['team_id'] . "
								LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
						WHERE mc.cat_tag = g.game_tag";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cats = $db->sql_fetchrow($result);
				
				$data_maps = data(MAPS, 'cat_id = ' . $cats['cat_id'], 'map_order ASC', 1, false);
				
				for ( $i = 0; $i < count($maps); $i++ )
				{
					for ( $k = 0; $k < count($data_maps); $k++ )
					{
						if ( empty($maps[$i]) || $maps[$i] == '0' )
						{
							false;
						}
						else if ( $maps[$i] != $data_maps[$k]['map_id'] )
						{
							false;
						}
						else
						{
							$maps_new[] = $maps[$i];
						}
					}
				}
				
				if ( isset($maps_new) )
				{
					for ( $j = 0; $j < count($maps_new); $j++ )
					{
						$custom_auth[$j] = "<select class=\"selectsmall\" name=\"training_maps[]\">";
						$custom_auth[$j] .= "<option selected=\"selected\" value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . "</option>";
						
						$cat_id		= $cats['cat_id'];
						$cat_name	= $cats['cat_name'];
						
						$s_maps = '';
						
						for ( $k = 0; $k < count($data_maps); $k++ )
						{
							$selected = ( $maps_new[$j] == $data_maps[$k]['map_id'] ) ? ' selected="selected"' : '';
							$s_maps .= '<option value="' . $data_maps[$k]['map_id'] . '"' . $selected . '>' . sprintf($lang['sprintf_select_format'], $data_maps[$k]['map_name']) . '</option>';
						}
						
						$custom_auth[$j] .= ( $s_maps != '' ) ? "<optgroup label=\"$cat_name\">$s_maps</optgroup>" : '';
						$custom_auth[$j] .= '</select>&nbsp;';
				
						$template->assign_block_vars('input._maps_row', array('MAPS' => $custom_auth[$j]));
					}
				}
			}
			*/
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			$fields .= "<input type=\"hidden\" name=\"training_create\" value=\"" . $data['training_create'] . "\" />";
						
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
				'L_INPUT'		=> sprintf($lang["sprintf_$mode"], $lang['training'], $data['training_vs']),
				'L_VS'			=> $lang['train_vs'],
				'L_TEAM'		=> $lang['train_team'],
				'L_MATCH'		=> $lang['train_match'],
				'L_DATE'		=> $lang['train_date'],
				'L_DURATION'	=> $lang['train_dura'],
				'L_MAPS'		=> $lang['train_maps'],
				'L_TEXT'		=> $lang['train_text'],

				'VS'			=> $data['training_vs'],
				'MAPS'			=> $data['training_maps'],
				'TEXT'			=> $data['training_text'],
				
			#	'S_TEAMS'		=> $,
			#	'S_MAPS'		=> $s_select,
			#	'S_MATCH'		=> select_box(MATCH, $data['match_id']),
				
			#	'S_DAY'			=> select_date('selectsmall', 'day',		'day',		date('d', $data['training_date']), $data['training_date']),
			#	'S_MONTH'		=> select_date('selectsmall', 'month',		'month',	date('m', $data['training_date']), $data['training_date']),
			#	'S_YEAR'		=> select_date('selectsmall', 'year',		'year',		date('Y', $data['training_date']), $data['training_date']),
			#	'S_HOUR'		=> select_date('selectsmall', 'hour',		'hour',		date('H', $data['training_date']), $data['training_date']),
			#	'S_MIN'			=> select_date('selectsmall', 'min',		'min',		date('i', $data['training_date']), $data['training_date']),
			#	'S_DURATION'	=> select_date('selectsmall', 'duration',	'dmin',		( $data['training_duration'] - $data['training_date'] ) / 60),
				
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
				'L_CREATE'		=> sprintf($lang['sprintf_new_creates'], $lang['training']),
				'L_EXPLAIN'		=> $lang['explain'],
				'L_UPCOMING'	=> $lang['upcoming'],
				'L_EXPIRED'		=> $lang['expired'],
				
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page),
				'PAGE_PAGING'	=> generate_pagination("$file?", $cnt_old, $settings['per_page_entry']['acp'], $start ),
				
				'S_SORT'	=> $s_sort,
				'S_TEAMS'	=> select_team('', 'team', '', 'team_id', $team),

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