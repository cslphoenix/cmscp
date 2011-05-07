<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);

	if ( $userdata['user_level'] == ADMIN || $userauth['auth_training'] )
	{
		$module['_headmenu_05_teams']['_submenu_training'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_training';
	
	include('./pagestart.php');
	
	load_lang('training');

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_TRAINING;
	$url	= POST_TRAINING_URL;
	$team	= POST_TEAMS_URL;
	$match	= POST_MATCH_URL;
	$file	= basename(__FILE__);
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	
	$sort		= ( request('sort', 1) ) ? request('sort', 1) : '';
	$data_id	= request($url, 0);
	$team_id	= request($team, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['training']);
		
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_training'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}

	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body' => 'style/acp_training.tpl',
		'tiny' => 'style/tinymce_normal.tpl',
	));
	
	switch ( $mode )
	{
		case '_create':
		case '_update':
		
			$template->assign_block_vars('_input', array());
			$template->assign_var_from_handle('TINYMCE', 'tiny');
			
			if ( $mode == '_create' && !request('submit', 1) )
			{
				$data = array(
							'training_vs'		=> ( request('training_vs', 2) ) ? request('training_vs', 2) : request('vs', 2),
							'team_id'			=> ( request('team_id', 0) ) ? request('team_id', 0) : $team_id,
							'match_id'			=> request($match),
							'training_maps'		=> '',
							'training_text'		=> '',
							'training_date'		=> time(),
							'training_create'	=> time(),
							'training_duration'	=> '',
						);
			}
			else if ( $mode == '_update' && !request('submit', 1) )
			{
				$data = data(TRAINING, $data_id, false, 1, 1);
			}
			else
			{
				$training_date	= mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$training_dura	= mktime(request('hour', 0), request('min', 0) + request('dmin', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				$data = array(
							'training_vs'		=> request('training_vs', 2),
							'team_id'			=> request('team_id', 0),
							'match_id'			=> request('match_id', 0),
							'training_maps'		=> serialize(request('training_maps', 4)),
							'training_text'		=> request('training_text', 2),
							'training_date'		=> $training_date,
							'training_create'	=> request('training_create', 0),
							'training_duration'	=> ( $training_dura - $training_date ) / 60,
						);
			}
		
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
					$s_select .= "</select>&nbsp;<input type=\"button\" class=\"button2\" value=\"" . $lang['common_more'] . "\" onclick=\"clone(this)\"></div></div>";
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
			
			if ( is_array($data['training_maps']) || $data['training_maps'] )
			{
				$maps = unserialize($data['training_maps']);
				
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
				
						$template->assign_block_vars('_input._maps_row', array('MAPS' => $custom_auth[$j]));
					}
				}
			}
			
			$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
			$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
			$fields .= "<input type=\"hidden\" name=\"training_create\" value=\"" . $data['training_create'] . "\" />";
						
			$template->assign_vars(array(
				'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['training']),
				'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['training'], $data['training_vs']),
				'L_VS'			=> $lang['vs'],
				'L_TEAM'		=> $lang['team'],
				'L_MATCH'		=> $lang['match'],
				'L_DATE'		=> $lang['date'],
				'L_DURATION'	=> $lang['duration'],
				'L_MAPS'		=> $lang['maps'],
				'L_TEXT'		=> $lang['text'],

				'VS'			=> $data['training_vs'],
				'MAPS'			=> $data['training_maps'],
				'TEXT'			=> $data['training_text'],
				
				'S_TEAMS'		=> $s_teams,
				'S_MAPS'		=> $s_select,
				'S_MATCH'		=> select_box('match',	'select', $data['match_id']),
				
				'S_DAY'			=> select_date('selectsmall', 'day',		'day',		date('d', $data['training_date']), $data['training_create']),
				'S_MONTH'		=> select_date('selectsmall', 'month',		'month',	date('m', $data['training_date']), $data['training_create']),
				'S_YEAR'		=> select_date('selectsmall', 'year',		'year',		date('Y', $data['training_date']), $data['training_create']),
				'S_HOUR'		=> select_date('selectsmall', 'hour',		'hour',		date('H', $data['training_date']), $data['training_create']),
				'S_MIN'			=> select_date('selectsmall', 'min',		'min',		date('i', $data['training_date']), $data['training_create']),
				'S_DURATION'	=> select_date('selectsmall', 'duration',	'dmin',		( $data['training_duration'] - $data['training_date'] ) / 60),
				
				'S_ACTION'		=> check_sid($file),
				'S_FIELDS'		=> $fields,
			));
			
			if ( request('submit', 1) )
			{
				$date = mktime(request('hour', 0), request('min', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				$dura = mktime(request('hour', 0), request('min', 0) + request('dmin', 0), 00, request('month', 0), request('day', 0), request('year', 0));
				
				$data = array(
							'training_vs'		=> request('training_vs', 2),
							'team_id'			=> request('team_id', 0),
							'match_id'			=> request('match_id', 0),
							'training_maps'		=> request('training_maps', 4),
							'training_text'		=> request('training_text', 2),
							'training_date'		=> $date,
							'training_create'	=> request('training_create', 0),
							'training_duration'	=> ( $dura - $date ) / 60,
						);
						
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
				
				$data['training_maps'] = isset($maps_new) ? serialize($maps_new) : '';
				
				$error .= ( !$data['training_vs'] )				? ( $error ? '<br />' : '' ) . $lang['msg_select_rival'] : '';
				$error .= ( $data['team_id'] == '-1' )			? ( $error ? '<br />' : '' ) . $lang['msg_select_team'] : '';
				$error .= ( !$data['training_maps'] )			? ( $error ? '<br />' : '' ) . $lang['msg_select_map'] : '';
				$error .= ( time() >= $data['training_date'] )	? ( $error ? '<br />' : '' ) . $lang['msg_select_past'] : '';
				$error .= ( !checkdate(request('month', 0), request('day', 0), request('year', 0)) ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_date'] : '';
				
				if ( !$error )
				{
					if ( $mode == '_create' )
					{
						$db_data = sql(TRAINING, $mode, $data);
							
						$message = $lang['create']
							. sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						$db_data = sql(TRAINING, $mode, $data, 'training_id', $data_id);
						
						$message = $lang['update']
							. sprintf($lang['return'], check_sid($file), $acp_title)
							. sprintf($lang['return_update'], '<a href="' . check_sid("$file?mode=$mode&amp;$url=$data_id"));
					}
					
				#	$oCache -> sCachePath = './../cache/';
				#	$oCache -> deleteCache('cal_sn_' . request('month', 0) . '_member');
				#	$oCache -> deleteCache('subnavi_training_' . request('month', 0));
					
					log_add(LOG_ADMIN, $log, $mode, $db_data);
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
			
			break;
			
		case '_delete':
		
			$data = data(TRAINING, $data_id, false, 1, 1);
		
			if ( $data_id && $confirm )
			{
				$sql = "DELETE FROM " . TRAINING . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_COMMENTS . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_COMMENTS_READ . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$sql = "DELETE FROM " . TRAINING_USERS . " WHERE training_id = $data_id";
				if ( !$db->sql_query($sql, END_TRANSACTION) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
			#	$oCache -> sCachePath = './../cache/';
			#	$oCache -> deleteCache('subnavi_training_*');
				
				$message = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
				
				log_add(LOG_ADMIN, $log, $mode, $data['training_vs']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data_id && !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
	
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['common_confirm'],
					'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['training_vs']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['training']));
			}
			
			break;
						
		default:
		
			$template->set_filenames(array('body' => 'style/acp_training.tpl'));
			$template->assign_block_vars('_display', array());
			
			$teams = data(TEAMS, false, 'team_order', 0, 0);
			
			$s_action = "<select class=\"selectsmall\" name=\"$team\" onchange=\"if (this.options[this.selectedIndex].value != '') this.form.submit();\">";
			$s_action .= "<option value=\"0\">" .  sprintf($lang['sprintf_select_format'], $lang['msg_select_team']) . "</option>";
			
			foreach ( $teams as $info => $value )
			{
				$selected = ( $value['team_id'] == $team_id ) ? ' selected="selected"' : '';
				$s_action .= "<option value=\"" . $value['team_id'] . "\" $selected>" . sprintf($lang['sprintf_select_format'], $value['team_name']) . "</option>";
			}
			$s_action .= "</select>";
			
			$fields = '<input type="hidden" name="mode" value="_create" />';
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['training']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['training']),
				'L_EXPLAIN'	=> $lang['explain'],
				
				'L_UPCOMING'	=> $lang['upcoming'],
				'L_EXPIRED'		=> $lang['expired'],
				
				'S_LIST'	=> $s_action,
				'S_TEAMS'	=> select_box('team', 'selectsmall', 0),
				
				'S_CREATE'	=> check_sid("$file?mode=_create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			$select_id = ( $team_id >= '1' ) ? "AND tr.team_id = $team_id" : '';
			
			$sql = "SELECT tr.*, g.game_image, g.game_size
						FROM " . TRAINING . " tr, " . TEAMS . " t, " . GAMES . " g
						WHERE tr.team_id = t.team_id AND t.team_game = g.game_id $select_id
					ORDER BY training_date";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$training = $db->sql_fetchrowset($result);
			
			if ( $training )
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
				
				if ( $training_new )
				{
					for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_new)); $i++ )
					{
						$training_id = $training_new[$i]['training_id'];
						
						$template->assign_block_vars('_display._training_new_row', array(
							'NAME'		=> $training_new[$i]['training_vs'],
							'IMAGE'		=> display_gameicon($training_new[$i]['game_size'], $training_new[$i]['game_image']),
							'DATE'		=> create_date($userdata['user_dateformat'], $training_new[$i]['training_date'], $userdata['user_timezone']),
							
							'U_UPDATE'	=> check_sid("$file?mode=_update&amp;$url=$training_id"),
							'U_DELETE'	=> check_sid("$file?mode=_delete&amp;$url=$training_id"),
						));
					}
				}
				else
				{
					$template->assign_block_vars('_display._no_entry_new', array());
				}
				
				if ( $training_old )
				{
					for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($training_old)); $i++ )
					{
						$training_id = $training_old[$i]['training_id'];
						
						$template->assign_block_vars('_display._training_old_row', array(
							'NAME'	=> $training_old[$i]['training_vs'],
							'IMAGE'	=> display_gameicon($training_old[$i]['game_size'], $training_old[$i]['game_image']),
							'DATE'	=> create_date($userdata['user_dateformat'], $training_old[$i]['training_date'], $userdata['user_timezone']),
							
							'U_UPDATE'	=> check_sid("$file?mode=_update&amp;$url=$training_id"),
							'U_DELETE'	=> check_sid("$file?mode=_delete&amp;$url=$training_id"),
						));
					}
				}
				else
				{
					$template->assign_block_vars('_display._no_entry_old', array());
				}
			}
			else
			{
				$template->assign_block_vars('_display._no_entry_new', array());
				$template->assign_block_vars('_display._no_entry_old', array());
			}
			
			$current_page = ( !count($training) ) ? 1 : ceil( count($training) / $settings['site_entry_per_page'] );
		
			$template->assign_vars(array(
				'PAGE_NUMBER'	=> ( count($training) ) ? sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ) : '',
				'PAGINATION'	=> ( count($training) ) ? generate_pagination('admin_match.php?', count($training), $settings['site_entry_per_page'], $start) : '',
			));
			
			break;
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>