<?php

function error($tpl_box, $error_ary)
{
	global $template, $log;
	
	if ( is_array($error_ary) )
	{
		foreach ( $error_ary as $row )
		{
			if ( $row != '' )
			{
				$error[] = $row;
			}
		}
		
		if ( isset($error) )
		{
			$error_msg = implode('<br />', $error);
			
			$template->set_filenames(array('error' => 'style/info_error.tpl'));
			$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
			$template->assign_var_from_handle($tpl_box, 'error');
								
			log_add(LOG_ADMIN, $log, 'error', $error);
		}
	}
}

function build_request_list($vars, &$error)
{
#	global $db, $url, $lang, $_POST;

	foreach ( $vars as $name => $opt )
	{
		$request[$name] = request($name, ARY, $opt['validate']);
	}
	
	return $request;
}

function build_request($name, $vars, $multi, &$error, $sql_add = false)
{
	global $db, $url, $lang, $_POST;
	
	if ( is_bool($multi) )
	{
		foreach ( $vars[$name] as $key_vars => $row_vars )
		{
			if ( strpos($key_vars, 'tab') !== false )
			{
				continue;
			}
			
			if ( !is_array($_POST[$name][$key_vars]) )
			{
				$request[$key_vars] = request(array($name, $key_vars), $vars[$name][$key_vars]['validate']);
			}
			else
			{
				debug($key_vars, '!is_array');
				
				if ( strpos($key_vars, 'dimension') !== false || strpos($key_vars, 'format') !== false || strpos($key_vars, 'preview') !== false )
				{
					$request[$key_vars] = sprintf('%s:%s', request(array($name, $key_vars, 0), $vars[$name][$key_vars]['validate']), request(array($name, $key_vars, 1), $vars[$name][$key_vars]['validate']));
				}
				else if ( strpos($key_vars, 'auth') !== false )
				{
					$request[$key_vars] = serialize(request(array($name, $key_vars), $vars[$name][$key_vars]['validate']));
				}
				else
				{
					$request[$key_vars] = request(array($name, $key_vars), $vars[$name][$key_vars]['validate']);
				}
			}
		}
	}
	else
	{
		$sql = 'SHOW FIELDS FROM ' . $name;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp = $db->sql_fetchrowset($result);
		
		foreach ( $tmp as $row )
		{
			$temp[] = $row['Field'];
		}
		unset($temp[0]);
		
		if ( $sql_add && $_POST['mode'] == 'create' )
		{
			$sqla = is_array($sql_add) ? $sql_add : array($sql_add);
			$temp = array_merge($temp, $sqla);
		}
		
		foreach ( $temp as $rows )
		{
			/* nur vars die auch angeben sind überprüfen, sonst NOTICE Warnung */
			if ( isset($vars[$multi][$rows]) )
			{
				$check	= isset($vars[$multi][$rows]['check'])	? true : false;
				$prefix = isset($vars[$multi][$rows]['prefix']) ? $vars[$multi][$rows]['prefix'] : false;
			#	$requir = isset($vars[$multi][$rows]['required']) ? $vars[$multi][$rows]['required'] : false;
				
			#	debug($requir);
				
				if ( strpos($rows, '_order') !== false ) 
				{
					$order = str_replace('_order', '_order_new', $rows);
					
					$request[$rows] = request(array($multi, $order), $vars[$multi][$rows]['validate']);
				}
				else if ( strpos($rows, 'dimension') !== false || strpos($rows, 'format') !== false || strpos($rows, 'preview') !== false )
				{
					$request[$rows] = sprintf('%s:%s', request(array($multi, $rows, 0), $vars[$multi][$rows]['validate']), request(array($multi, $rows, 1), $vars[$multi][$rows]['validate']));
				}
				else if ( $vars[$multi][$rows]['type'] == 'hidden' ) 
				{
					$request[$rows] = request(array($multi, $rows), TXT);
				}
				else if ( $prefix )
				{
					$request[$rows] = str_replace(' ', '_', request(array($multi, $rows), $vars[$multi][$rows]['validate']));
				}
				else
				{
					$request[$rows] = request(array($multi, $rows), $vars[$multi][$rows]['validate']);
				}
				
				if ( isset($vars[$multi][$rows]['required']) && $vars[$multi][$rows]['type'] != 'hidden' )
				{
					$request[$rows] = $prefix ? str_replace($prefix, '', $request[$rows]) : $request[$rows];
					
					if ( ( $request[$rows] == '' || $request[$rows] == '-1' ) )
					{
						$error[] = $vars[$multi][$rows]['required'];
						$check = false;
					}
					
					$request[$rows] = $prefix ? $prefix . $request[$rows] : $request[$rows];
				}
			
				if ( $check )
				{
					$sql = "SELECT * FROM $name";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					while ( $match = $db->sql_fetchrow($result) )
					{
						$field_name = array_shift(array_keys($match));
						$field_id = request($url, INT);
						
						if ( strtolower($match[$rows]) == strtolower($request[$rows]) && $match[$field_name] != $field_id )
						{
							$error[] = sprintf($lang['sql_duplicate'], $rows, $match[$rows]);
						}					
					}
				}
				
				/* setzt die jeweilige Zeit */
				if ( $_POST['mode'] == 'create' && ( $vars[$multi][$rows]['type'] == 'hidden' && strpos($rows, 'create') !== false ) )
				{
					$request[$rows] = time();
				}
				
				if ( $_POST['mode'] == 'update' && ( $vars[$multi][$rows]['type'] == 'hidden' && strpos($rows, 'update') !== false ) )
				{
					$request[$rows] = time();
				}
				
				if ( $_POST['mode'] == 'upload' && ( $vars[$multi][$rows]['type'] == 'hidden' && strpos($rows, 'upload') !== false ) )
				{
					$request[$rows] = time();
				}
			
				/* für News
				if ( isset($vars[$multi][$rows]['params']) )
				{
					if ( strpos($vars[$multi][$rows]['params'], ':') !== false )
					{
						list($opt, $var) = explode(':', $vars[$multi][$rows]['params']);
					
						switch ( $opt )
						{
							case 'prefix': $request[$rows] = $var . str_replace($var, '', $request[$rows]); break;
						}
					}
					
					if ( strpos($vars[$multi][$rows]['params'], '~') !== false )
					{
						$link_url = request(array($multi, $rows, 'url'), 'array', TXT);
						$name_url = request(array($multi, $rows, 'name'), 'array', TXT);
						
						$request[$rows] = sprintf('%s~%s', serialize($link_url), serialize($name_url));
					}
				}
				*/
				
				if ( $vars[$multi][$rows]['type'] == 'drop:datetime' || $vars[$multi][$rows]['type'] == 'drop:duration' )
				{
				#	$prefix = isset($vars[$multi][$rows]['prefix']) ? $vars[$multi][$rows]['prefix'] : '';
					
					$hour	= request("{$prefix}hour", INT);
					$min	= request("{$prefix}min", INT);
					$dura	= request("{$prefix}duration", INT);
					$month	= request("{$prefix}month", INT);
					$day	= request("{$prefix}day", INT);
					$year	= request("{$prefix}year", INT);
					
					if ( $vars[$multi][$rows]['type'] == 'drop:datetime' )
					{
						$request[$rows] = mktime($hour, $min, 00, $month, $day, $year);
						
						if ( !checkdate($month, $day, $year) )
						{
							$error[] = $lang['msg_select_date'];
						}
						
						if ( $_POST['mode'] == 'create' && time() >= $request[$rows] )
						{
							$error[] = $lang['msg_select_past'];
						}
					}
					else
					{
						$request[$rows] = mktime($hour, $min + $dura, 00, $month, $day, $year);
					}
				}
				
				if ( strpos($vars[$multi][$rows]['type'], 'upload:') !== false )
				{
					list($type, $info) = explode(':', $vars[$multi][$rows]['type']);
					
					$upload_pic	= request_file("{$info}_img");
					
					$request[$rows] = $upload_pic ? image_upload('single', 'network', $rows, '', request('current_image', TXT), '', $vars[$multi][$rows]['params'], $upload_pic['temp'], $upload_pic['name'], $upload_pic['size'], $upload_pic['type'], $error) : '';
				}
				
				if ( in_array($rows, array('group_auth', 'gallery_auth', 'cat_type', 'cat_auth')) && in_array($name, array(GALLERY, GROUPS, DOWNLOADS_CAT)) )
				{
					$request[$rows] = is_array($request[$rows]) ? serialize($request[$rows]) : serialize(array($request[$rows]));
				}
				
				if ( in_array($rows, array('user_month')) && in_array($name, array(CASH)) )
				{
					$request[$rows] = is_array($request[$rows]) ? implode(', ', $request[$rows]) : $request[$rows];
				}
				
				if ( in_array($rows, array('training_maps')) )
				{
					$tmp_map = '';
					
					if ( is_array($request[$rows]) )
					{					
						foreach ( $request[$rows] as $map )
						{
							if ( $map != '-1' )
							{
								$tmp_map[] = $map;
							}
						}
					}
					else
					{
						$tmp_map = ( $request[$rows] != '-1' ) ? array($request[$rows]) : array();
					}
					
					$request[$rows] = serialize($tmp_map);
				}
			}
		}
	}
	
	return $request;
}

function build_output_list($data, $vars, $tpl, $settings_name)
{
#	global $db, $lang, $template, $settings, $userdata, $dir_path;
	global $db, $lang, $template, $settings, $root_path, $tbl, $mode;
	
#	debug($data, 'first');
	
	if ( $mode == 'append' )
	{
		$sql = 'SELECT * FROM ' . $tbl;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp = $db->sql_fetchrowset($result);
		
		debug($data);
		debug($tmp);
		$moep = array_diff($data, $tmp);
		debug($moep);
		
	#	debug($tmp);
		
	#	while ( $row = $db->sql_fetchrow($result) )
	#	{
	#		debug($row['icon_path']);
			
		#	foreach ( $data as $rows )
		#	{
		#		if ( in_array($row['icon_path'], $row) )
		#		{
		#			debug($row);
		#		}
		#	}
	#	}
	}
			
	
	
	if ( $data )
	{
		foreach ( $data as $keys => $rows )
		{
			$template->assign_block_vars("$tpl.rows", array(
				'SYMBOL' => '<img src="' . $root_path . $rows['icon_path'] . '" alt="">',
			));
			
		#	debug($rows);
			
			foreach ( $vars as $vkey => $vrow )
			{
				$option = '';
				
				if ( substr_count($vrow['type'], ':') >= 2 )
				{
					list($type, $size, $max) = explode(':', $vrow['type']);
				}
				else if ( substr_count($vrow['type'], ':') == 1 )
				{
					list($type, $typ) = explode(':', $vrow['type']);
				}
				else
				{
					$type = $vrow['type'];
				}
				
				switch ( $type )
				{
					case 'checkbox':	$option .= '<input type="checkbox" name="' . $vkey . '[' . (isset($rows['icon_id']) ? $rows['icon_id'] : '') . ']" id="' . $vkey . '" value="' . (isset($rows[$vkey]) ? ($rows[$vkey] ? 1 : 2) : 1) . '" />';		break;					
					case 'text':		$option .= '<input type="text" size="' . $size . '" maxlength="' . $max . '" name="' . $vkey . '[' . (isset($rows['icon_id']) ? $rows['icon_id'] : '') . ']" id="' . $vkey . '" value="' . $rows[$vkey] . '" />';	break;
					case 'info':		$option .= $rows[$vkey] . '<input type="hidden" name="' . $vkey . '[' . (isset($rows['icon_id']) ? $rows['icon_id'] : '') . ']" id="' . $vkey . '" value="' . $rows[$vkey] . '" />';		break;			
					default: $option .= 'default'; break;					
				}
				
				$template->assign_block_vars("$tpl.rows.option", array(
					'OPTION' => $option,
				));
			}
		}
		
		foreach ( $vars as $vkey => $vrow )
		{
			$template->assign_block_vars("$tpl.option", array(
				'OPTION' => $vkey,
			));
		}
	}
	else
	{
		echo 'doep';
	}
		
	return;
}



function build_output($data, $vars, $tpl, $multi = false, $dbsql = false)
{
	global $db, $root_path, $lang, $template, $settings, $userdata, $dir_path;
	
	$time = time();
	
	if ( !$multi )
	{
		foreach ( $data as $data_key => $data_value )
		{
			$sql_data[$data_key] = $data_value;
		}
	}
	
#	debug($vars);
#	debug($data);
	
	foreach ( $vars as $vars_key => $vars_value )
	{
		if ( $multi )
		{
			foreach ( $data as $data_key => $data_value )
			{
				if ( $vars_key == $data_key )
				{
					$sql_data[$data_key] = unserialize($data_value);
				}
			}
		}
		
		$template->assign_block_vars("$tpl.row", array(
			'KEY'		=> $vars_key,
			'L_NAME'	=> isset($lang[$vars_key]) ? $lang[$vars_key] : $vars_key,
		));
		
		foreach ( $vars_value as $vars_opt => $vars_type )
		{
			if ( !is_array($vars_type) )
			{
				$template->assign_block_vars("$tpl.row.tab", array('L_LANG' => isset($lang[$vars_type]) ? $lang[$vars_type] : $vars_type));
				/*
				if ( $vars_type != 'hidden' )
				{
					$template->assign_block_vars("$tpl.row.tab", array('L_LANG' => isset($lang[$vars_type]) ? $lang[$vars_type] : $vars_type));
				}
				*/
			}
			else if ( $vars_type['type'] == 'hidden' )
			{
				$tmp_opts = isset($vars_type['opt']) ? $vars_type['opt'] : '';
				$tmp_data = $multi ? @$sql_data[$vars_key][$vars_opt] : @$sql_data[$vars_opt];
				$tmp_meta = $vars_key;
				$tmp_name = $vars_opt;
				$hidden = '<input type="hidden" name="' . sprintf('%s[%s]', $tmp_meta, $tmp_name) . '" value="' . $tmp_data . '" />';
				
				$template->assign_block_vars("$tpl.row.hidden", array('HIDDEN' => $hidden));
			}
			else
			{
				/* Fehlerunterdrückung für Daten beim War um das Training zuerstellen (@). */
				$tmp_type = $vars_type['type'];
				$tmp_opts = isset($vars_type['opt']) ? $vars_type['opt'] : '';
				$tmp_data = $multi ? @$sql_data[$vars_key][$vars_opt] : @$sql_data[$vars_opt];
				$tmp_meta = $vars_key;
				$tmp_name = $vars_opt;
				$tmp_parm = isset($vars_type['params']) ? $vars_type['params'] : '';
				
				$lngs = isset($lang[$vars_opt]) ? $lang[$vars_opt] : $vars_opt;
			
				$opt = '';
				
				$pos = strpos($tmp_type, ':');
				$str = substr($tmp_type, 0, $pos);
				
				if ( isset($vars_type['ajax']) )
				{
					list($ajaxphp, $ajaxtpl) = explode(':', $vars_type['ajax']);
					
					$template->set_filenames(array('ajax'	=> "style/{$ajaxtpl}.tpl"));
					$template->assign_vars(array('FILE'		=> $ajaxphp));
					$template->assign_var_from_handle('AJAX', 'ajax');
				}
				
				if ( $str == 'ajax' )
				{
					list($type, $size) = explode(':', $tmp_type);
					
					if ( substr_count($tmp_type, ':') >= 1 )
					{
						list($typ, $new, $level) = explode(':', $tmp_parm);
					}
					else
					{
						$typ = $tmp_parm;
					}
					
					$ajax_file = ( $typ != 'server' ) ? ( $typ == 'rival' ) ? 'ajax_rival.php' : 'ajax_user.php' : 'ajax_gs.php';
					
					$opt .= '<input type="text" size="' . $size . '" name="' . sprintf('%s[%s]', $tmp_meta, $tmp_name) . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '" value="' . $tmp_data . '" onkeyup="look_' . $tmp_name . '(this.value' . (( substr_count($tmp_parm, ':') >= 1 ) ? sprintf(', %s, %s', $new, $level) : '')  . ');" onblur="set_' . $tmp_name . '();" autocomplete="off"><div class="suggestionsBox" id="' . $tmp_name . '" style="display:none;"><div class="suggestionList" id="auto_' . $tmp_name . '"></div></div>';
					
					$template->assign_block_vars("$tpl.ajax", array('NAME' => $tmp_name, 'FILE' => $ajax_file));
				}
				else if ( $str == 'check' )
				{
					$monate = array(
						'1'		=> $lang['datetime']['month_01'],
						'2'		=> $lang['datetime']['month_02'],
						'3'		=> $lang['datetime']['month_03'],
						'4'		=> $lang['datetime']['month_04'],
						'5'		=> $lang['datetime']['month_05'],
						'6'		=> $lang['datetime']['month_06'],
						'7'		=> $lang['datetime']['month_07'],
						'8'		=> $lang['datetime']['month_08'],
						'9'		=> $lang['datetime']['month_09'],
						'10'	=> $lang['datetime']['month_10'],
						'11'	=> $lang['datetime']['month_11'],
						'12'	=> $lang['datetime']['month_12'],
					);
					
					$switch = '';
					
					for ( $i = 1; $i < 13; $i++ )
					{
						$check = '';
					
						if ( in_array($i, explode(', ', $tmp_data)) )
						{
							$check = ' checked="checked"';
						}
							
						$switch[] = '<label><input type="checkbox" name="' . sprintf('%s[%s][%s]', $tmp_meta, $tmp_name, $i) . '"' . $check . ' value="' . $i . '">&nbsp;' . $monate[$i] . '</label>';
					}
					
					$opt .= implode('<br />', $switch);
				}
				else if ( $str == 'double' )
				{
					list($valuea, $valueb) = explode(':', $tmp_data);
					list($type, $size, $max) = explode(':', $tmp_type);
						
					$opt .= '<input type="text" size="' . $size . '" maxlength="' . $max . '" name="' . sprintf('%s[%s][]', $tmp_meta, $tmp_name) . '" value="' . $valuea . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '" /><span style="padding:4px;">&bull;</span>';
					$opt .= '<input type="text" size="' . $size . '" maxlength="' . $max . '" name="' . sprintf('%s[%s][]', $tmp_meta, $tmp_name) . '" value="' . $valueb . '" />';
				}
				else if ( $str == 'drop' )
				{
					list($type, $typ) = explode(':', $tmp_type);
						
					if ( strpos($tmp_type, 'auth_') !== false )
					{
						$type = $typ;
						$typ = 'auth';
					}
					
					$pre = isset($vars_type['prefix']) ? $vars_type['prefix'] : '';
					
					switch ( $typ )
					{
						case 'authcopy':	
						case 'forum':
							
							$tmp_db = data(FORMS, false, 'forum_sub ASC, forum_order ASC', 1, false);
							
							if ( isset($tmp_db) )
							{
								foreach ( $tmp_db as $rows )
								{
									if ( $rows['forum_sub'] == '0' )
									{
										$forum_cat[$rows['forum_id']] = $rows;
									}
									else if ( $rows['forum_sub'] < 0 )
									{
										$forum_frs[$rows['forum_sub']*(-1)][$rows['forum_id']] = $rows;
									}
									else
									{
										$forum_sub[$rows['forum_sub']][$rows['forum_id']] = $rows;
									}
								}
							}
							
							ksort($forum_frs);
							ksort($forum_sub);
							
							$opt .= '<select name="' . sprintf('%s[%s]', $tmp_meta, $tmp_name) . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '">';
							$opt .= ($typ == 'authcopy') ? '<option value="" selected="selected">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_file']) . '</option>' : '';
							
							foreach ( $forum_cat as $ckey => $crow )
							{
								$default = $tmp_data < 0 ? $tmp_data * -1 : '';
								$mark = ( $default == $crow['forum_id'] ) ? ' selected="selected"' : '';
								
								$opt .= '<option value="' . (($typ == 'forum') ? $crow['forum_id'] * -1 : $crow['forum_id']) . '"' . $mark . '>' . $crow['forum_name'] . '</option>';
									
								if ( isset($forum_frs[$ckey]) )
								{
									foreach ( $forum_frs[$ckey] as $fkey => $frow )
									{
										$mark = ( $tmp_data == $frow['forum_id'] ) ? ' selected="selected"' : '';
										$shut = ( $data['forum_id'] == $frow['forum_id'] ) ? ' disabled="disabled"': '';
										
										$opt .= '<option value="' . $frow['forum_id'] . '"' . $mark . $shut . '>&nbsp; &nbsp;' . $frow['forum_name'] . '</option>';
										
										if ( isset($forum_sub[$fkey]) )
										{
											foreach ( $forum_sub[$fkey] as $skey => $srow )
											{
												$opt .= '<option value="' . $srow['forum_id'] . '"' . (($typ == 'authcopy') ? (($data['forum_id'] == $srow['forum_id']) ? ' disabled="disabled"' : '') : ' disabled="disabled"') . '>&nbsp; &nbsp;&nbsp; &nbsp;' . $srow['forum_name'] . '</option>';
											}
										}
									}
								}
							}
							
							$opt .= '</select>';
							
							break;
							
						case 'server':
							
							$data = data(SERVER_TYPE, "type_sort = '0'", false, 1, false);

							$opt .= '<select name="' . sprintf('%s[%s]', $tmp_meta, $tmp_name) . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '">';
							
							foreach ( $data as $row )
							{
								$selected = ( $tmp_data == $row['type_game'] ) ? ' selected="selected"' : '';
								$opt .= "<option value=\"" . $row['type_game'] . "\" $selected>" . sprintf($lang['sprintf_select_format'], $row['type_name']) . "</option>";
							}
							$opt .= "</select>";
						
							break;
							
						case 'files':
						
							$tmp_data = str_replace('./', '', $tmp_data);
						
							$folder = $root_path;
							$files = scandir($folder);
							
							$opt .= '<select name="navi_url" id="navi_url" onchange="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '.value = this.value;">';
							$opt .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_file']) . "</option>";
							
							foreach ( $files as $sfile )
							{
								if ( strstr($sfile, '.php') )
								{
									$selected = ( $sfile == $tmp_data ) ? 'selected="selected"' : '';
									$opt .= "<option value=\"$sfile\" $selected>" . sprintf($lang['sprintf_select_format'], $sfile) . "</option>";
								}
							}
							$opt .= '</select>';
							$opt .= '&nbsp;<input type="text" size="25" name="' . sprintf('%s[%s]', $tmp_meta, $tmp_name) . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '" value="' . $tmp_data . '" />';
						
							break;
							
						case 'maps':
						
						#	debug($tmp_data);
						
							$opt .= '<div id="close">';
							
							if ( $data['team_id'] > 0 )
							{
								$sql = "SELECT m.*
											FROM " . MAPS . " m
												LEFT JOIN " . TEAMS . " t ON t.team_id = {$data['team_id']}
												LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
										WHERE m.map_tag = g.game_tag";
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$cat = $db->sql_fetchrow($result);
								
								if ( $cat )
								{
									$sql = "SELECT * FROM " . MAPS . " WHERE map_sub = {$cat['map_id']} ORDER BY map_order ASC";
									if ( !($result = $db->sql_query($sql)) )
									{
										message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
									}
									$maps = $db->sql_fetchrowset($result);
									
									if ( $maps )
									{
										$opt .= '<div><div><select name="training[training_maps][]" id="training_training_maps">';
										$opt .= '<option selected="selected" value="-1">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . '</option>';
										
										for ( $j = 0; $j < count($maps); $j++ )
										{
											$map_id		= $maps[$j]['map_id'];
											$map_name	= $maps[$j]['map_name'];
								
											$opt .= '<option value="' . $map_id . '">' . sprintf($lang['sprintf_select_format'], $map_name) . '</option>';
										}
										
										$opt .= '</select>&nbsp;<input type="button" class="more" value="' . $lang['common_more'] . '" onclick="clone(this)"></div></div>';
									}
									else
									{
										$opt .= sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
									}
								}
								else
								{
									$opt .= sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
								}
							}
							else
							{
								$opt .= sprintf($lang['sprintf_select_format'], $lang['msg_select_team_first']);
							}
							
							$opt .= '</div><div id="ajax_content"></div>';
							
							
							break;
					#	case 'auth':		$opt .= select_auth($tmp_data, $tmp_meta, $tmp_name, $type); break;
						case 'auth':
						
							$info = select_auth($tmp_data, $tmp_meta, $tmp_name, $type);
							$opt .= $info[0];
						
							break;
						case 'match':		$opt .= select_match($tmp_data, $tmp_meta, $tmp_name); break;
						case 'newscat':		$opt .= select_newscat($tmp_data, $tmp_meta, $tmp_name, $tmp_parm); break;
						case 'disable':		$opt .= page_mode_select($tmp_data, "{$tmp_meta}[page_disable_mode]"); break;
						case 'lang':		$opt .= select_lang($tmp_data, "{$tmp_meta}[default_lang]", "language"); break;
						case 'style':		$opt .= select_style($tmp_data, "{$tmp_meta}[default_style]", "../templates"); break;
						case 'tz':			$opt .= select_tz($tmp_data, "{$tmp_meta}[default_timezone]"); break;
					
						case 'userlevel':	$opt .= select_level($tmp_data, "{$tmp_meta}[$tmp_name]", $tmp_parm); break;
						
						case 'datetime':	$opt .= sprintf('%s.%s.%s - %s:%s',
												select_date('select', 'day',	"{$pre}day",	date('d', is_numeric($tmp_parm) ? $tmp_data : $time), $time),
												select_date('select', 'month',	"{$pre}month",	date('m', is_numeric($tmp_parm) ? $tmp_data : $time), $time),
												select_date('select', 'year',	"{$pre}year",	date('Y', is_numeric($tmp_parm) ? $tmp_data : $time), $time),
												select_date('select', 'hour',	"{$pre}hour",	date('H', is_numeric($tmp_parm) ? $tmp_data : $time), $time),
												select_date('select', 'min',	"{$pre}min",	date('i', is_numeric($tmp_parm) ? $tmp_data : $time), $time));
										break;
						
						case 'duration':	$opt .= select_date('select', 'duration', "{$pre}duration", ( $tmp_data - $data[$tmp_parm] ) / 60); break;
						
						case 'image':		$opt .= select_image($tmp_data, $tmp_meta, $tmp_name, $tmp_parm, ( strpos($tmp_name, 'cat') !== false ) ? 0 : 1); break;
						case 'game':		$opt .= select_games($tmp_data, $tmp_meta, $tmp_name, $tmp_parm); break;
						
						case 'team':		$opt .= select_team($tmp_data, $tmp_meta, $tmp_name, $tmp_parm); break;
						case 'match_type':	$opt .= match_types($tmp_data, $tmp_meta, $tmp_name); break;
						case 'match_war':	$opt .= match_types($tmp_data, $tmp_meta, $tmp_name); break;
						case 'match_league':$opt .= match_types($tmp_data, $tmp_meta, $tmp_name); break;
							
					#	case 'order':		$opt .= simple_order($dbsql, 'game_id != -1', '', $tmp_data); break;
					
						case 'gaccess':	if ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $data['group_access'] )
										{
											$opt .= '<select name="' . sprintf('%s[%s][]', $tmp_meta, $tmp_name) . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '">';
											$opt .= '<optgroup label="' . sprintf($lang['sprintf_select_format'], $lang['msg_select_user_level']) . '">';
											
											foreach ( $lang['group_access_ary'] as $level => $name )
											{
												$mark = ( $level == $tmp_data ) ? 'selected="selected"' : "";
												$prot = ( $level > $userdata['user_level'] ) ? 'disabled="disabled"' : "";
												$opt .= '<option value="' . $level . '" ' . $mark . $prot . '>' . sprintf($lang['sprintf_select_format'], $name) . '</option>';
											}
											
											$opt .= '</optgroup></select>';
										}
										else
										{
											$opt .= $lang['group_access_ary'][$data['group_access']];
											$opt .= '<input type="hidden" name="group[group_type]" value="' . $data['group_access'] . '" />';
										}
										
										
										break;
						
						case 'gtype':	if ( $tmp_data != GROUP_SYSTEM )
										{
											$opt .= '<select name="' . sprintf('%s[%s][]', $tmp_meta, $tmp_name) . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '">';
											$opt .= '<optgroup label="' . sprintf($lang['sprintf_select_format'], $lang['msg_select_level']) . '">';
											
											foreach ( $lang['group_type_ary'] as $level => $name )
											{
												$mark = ( $level == $tmp_data ) ? 'selected="selected"' : "";
												$prot = ( $level == GROUP_SYSTEM ) ? 'disabled="disabled"' : "";
												$opt .= '<option value="' . $level . '" ' . $mark . $prot . '>' . sprintf($lang['sprintf_select_format'], $name) . '</option>';
											}
											
											$opt .= '</optgroup></select>';
										}
										else
										{
											$opt .= $lang['group_system'];
											$opt .= '<input type="hidden" name="group[group_type]" value="' . GROUP_SYSTEM . '" />';
										}
										
							break;
						
						case 'dtype':
						
							$mime_type = array('meta_application', 'meta_image', 'meta_text', 'meta_video');
							$tmp_data = unserialize($tmp_data);
							
							$opt .= '<select name="' . sprintf('%s[%s][]', $tmp_meta, $tmp_name) . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '" size="15" multiple="multiple">';
							
							foreach ( $mime_type as $meta_type )
							{
								$lang_type = str_replace('meta', 'type', $meta_type);
			
								$opt .= "<optgroup label=\"" . sprintf($lang['sprintf_select_format'], $lang[$meta_type]) . "\">";
								
								foreach ( $lang[$lang_type] as $key => $row )
								{
									$marked = ( in_array($key, $tmp_data) ) ? ' selected="selected"' : '';
									$opt .= '<option value="' . $key . '"' . $marked . '>' . sprintf($lang['sprintf_select_format'], $key) . '</option>';
								}
							}
			
							$opt .= '</select>';
						
							break;
										
						case 'ranks':
						
						#	$order = ( $tmp_parm != RANK_FORUM ) ? 'rank_order' : 'rank_min';

							$sql = 'SELECT rank_id, rank_name, rank_order FROM ' . RANKS . ' WHERE rank_type = ' . $tmp_parm . ' ORDER BY ' . (( $tmp_parm != RANK_FORUM ) ? 'rank_order' : 'rank_min');
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
						
							$opt .= '<select name="' . sprintf('%s[%s]', $tmp_meta, 'rank_id') . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '">';
							$opt .= '<option value="">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_rank']) . '</option>';
							
							while ( $row = $db->sql_fetchrow($result) )
							{
								$selected = ( $row['rank_id'] == $tmp_data ) ? ' selected="selected"' : '';
								$opt .= '<option value="' . $row['rank_id'] . '"' . $selected . '>' . sprintf($lang['sprintf_select_format'], $row['rank_name']) . '</option>';
							}
							$opt .= '</select>';
						
							break;
				
						case 'order':
						
							switch ( $dbsql )
							{
								case FIELDS:	$opt .= simple_order($dbsql, "field_sub = {$data['field_sub']}", $tmp_data, $vars_key, $tmp_meta); break;
								case NETWORK:	$opt .= simple_order($dbsql, "network_type = {$data['network_type']}", $tmp_data, $vars_key, $tmp_meta); break;
								case RANKS:		$opt .= simple_order($dbsql, "rank_type = {$data['rank_type']}", $tmp_data, $vars_key, $tmp_meta); break;
								case MAPS:		$opt .= simple_order($dbsql, "map_sub = {$data['map_sub']}", $tmp_data, $vars_key, $tmp_meta); break;
								case NAVI:		$opt .= simple_order($dbsql, "navi_type = {$data['navi_type']}", $tmp_data, $vars_key, $tmp_meta); break;
								case FORMS:		$opt .= simple_order($dbsql, "forum_sub = {$data['forum_sub']}", $tmp_data, $vars_key, $tmp_meta); break;
								
								/*
								case GALLERY:	$opt .= simple_order($dbsql, '', $tmp_data, $vars_key, $tmp_meta); break;
								case GAMES:		$opt .= simple_order($dbsql, '', $tmp_data, $vars_key, $tmp_meta); break;
								case GROUPS:	$opt .= simple_order($dbsql, '', $tmp_data, $vars_key, $tmp_meta); break;
								case NEWS_CAT:	$opt .= simple_order($dbsql, '', $tmp_data, $vars_key, $tmp_meta); break;
								case TEAMS:		$opt .= simple_order($dbsql, '', $tmp_data, $vars_key, $tmp_meta); break;
								*/
								
								case SERVER:
								case GALLERY:
								case GAMES:
								case GROUPS:
								case NEWS_CAT:
								case DOWNLOADS_CAT:
								case TEAMS:		$opt .= simple_order($dbsql, '', $tmp_data, $vars_key, $tmp_meta); break;
								
								
								default:		$opt .= $dbsql; break;
							}
							
						break;
					}
				}
				else if ( $str == 'links' )
				{
					list($valuea, $valueb) = explode('~', $tmp_data);
					list($type, $size) = explode(':', $tmp_type);
					
					$valuea = unserialize($valuea);
					$valueb = unserialize($valueb);
					
					if ( isset($valuea) )
					{
						foreach ( $valuea as $akey => $rowa )
						{
							$opt .= "<div><ul><input type=\"text\" name=\"{$tmp_meta}[$tmp_name][url][]\" value=\"$rowa\"><span style=\"padding:4px;\">&bull;</span><input type=\"text\" name=\"{$tmp_meta}[$tmp_name][name][]\" value=\"$valueb[$akey]\">&nbsp;<input class=\"more\" type=\"button\" value=\"{$lang['common_remove']}\" onClick=\"this.parentNode.parentNode.removeChild(this.parentNode)\"></ul></div>";
						}
					}
					
					
					$opt .= "<div><div><input type=\"text\" size=\"$size\" name=\"{$tmp_meta}[$tmp_name][url][]\" value=\"\" id=\"{$tmp_meta}_{$tmp_name}\" /><span style=\"padding:4px;\">&bull;</span>";
					$opt .= "<input type=\"text\" size=\"$size\" maxlength=\"$max\" name=\"{$tmp_meta}[$tmp_name][name][]\" value=\"\" />&nbsp;<input class=\"more\" type=\"button\" value=\"{$lang['common_more']}\"onclick=\"clone(this)\"></div></div>";
				}
				else if ( $str == 'radio' )
				{
					list($type, $typ) = explode(':', $tmp_type);
					
					$tmp_lang = '';
					
					if ( isset($lang[$tmp_type]) )
					{
						$tmp_lang = $lang[$tmp_type];
					}
					else if ( in_array($typ, array('icons')) )
					{
						$tmp_lang = $lang['radio:yesno'];
					}
					else
					{
						$tmp_db = data($dbsql, "$tmp_name = 0", "{$vars_key}_order ASC", 1, false);
						
						if ( $tmp_db )
						{
							foreach ( $tmp_db as $tmp_key => $tmp_value )
							{
								$tmp_lang[$tmp_value["{$vars_key}_id"]] = $tmp_value["{$vars_key}_name"];
							}
						}
					}
					
					$first = true;
					$click = false;
					
					foreach ( $tmp_lang as $var => $lng )
					{
						if ( $tmp_parm )
						{
							$click		= true;
							$data_sub	= $data[$vars_opt];
							$data_order	= isset($data["{$vars_key}_order"]) ? $data["{$vars_key}_order"] : 0;
						}
						
						$idcheck = ( $first ) ? "id=\"{$tmp_meta}_{$tmp_name}\"" : '';
						$checked = ( $tmp_data == $var ) ? 'checked="checked"' : '';
					#	$onclick = ( $click ) ?  "onclick=\"setRequest('$dbsql', $var, $data_sub, $data_order); rank_posts(this.value); user_interval(this.value); \"" : '';
						$onclick = ( $click ) ?  "onclick=\"setRequest('$dbsql', $var, $data_sub, $data_order); \"" : '';
						
						$opt .= "<label><input type=\"radio\" name=\"{$tmp_meta}[$tmp_name]\" value=\"$var\" $checked $idcheck $onclick/>&nbsp;$lng</label>";
					#	$opt .= ( strpos($tmp_type, 'caldays') !== false ) ? "<br />" : "<span style=\"padding:4px;\"></span>";
						$opt .= ( count($tmp_lang) > 4 || in_array($tmp_meta, array('cash', 'user')) ) ? "<br />" : "<span style=\"padding:4px;\"></span>";
						
						$first = false;
					}
				}
				else if ( $str == 'radios' )
				{
					$sql = 'SELECT authlist_name FROM ' . AUTHLIST . ' ORDER BY authlist_name ASC';
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$rows = $db->sql_fetchrowset($result);
					
					foreach ( $rows as $value )
					{
						$fields[] = $value['authlist_name'];
					}
					
					$cnt = count($fields);
					$utd = unserialize($tmp_data);
					
				#	debug($utd, 'utd');

					if ( empty($utd) )
					{
						foreach ( $rows as $value )
						{
							$utd[$value['authlist_name']] = 0;
						}
					}
					
					for ( $i = 0; $i < $cnt; $i++ )
					{
				#		debug($utd[$fields[$i]], $fields[$i]);
						
						if ( $userdata['user_level'] == ADMIN || $userdata['user_level'] > $data['group_access'] )
						{
							$selected_yes	= ( $utd[$fields[$i]] == AUTH_ALLOWED ) ? ' checked="checked"' : '';
							$selected_no	= ( $utd[$fields[$i]] == AUTH_DISALLOWED ) ? ' checked="checked"' : '';
						}
						else if ( $userdata['user_level'] <= $data['group_access'] )
						{
							$selected_yes	= ( $utd[$fields[$i]] == AUTH_ALLOWED ) ? ' checked="checked" disabled' : ' disabled';
							$selected_no	= ( $utd[$fields[$i]] == AUTH_DISALLOWED ) ? ' checked="checked" disabled' : ' disabled';
						}
						
						$opt .= '<label><input type="radio" name="' . sprintf('%s[%s][%s]', $tmp_meta, $tmp_name, $fields[$i]) . '" id="' . $fields[$i] . '" value="1"' . $selected_yes . '>&nbsp;' . $lang['group_allowed'] . '</label><span style="padding:4px;"></span>';
						$opt .= '<label><input type="radio" name="' . sprintf('%s[%s][%s]', $tmp_meta, $tmp_name, $fields[$i]) . '" id="deactivated" value="0"' . $selected_no . '>&nbsp;' . $lang['group_disallowed'] . ' &bull; ' . $lang['auths'][$fields[$i]] . '</label>';
						$opt .= ( $cnt != $i+1 ) ? '<br />' : '<br /><a href="#" onClick="check_yes(); return false;">' . $lang['mark_yes'] . '</a> &bull; <a href="#" onClick="check_no(); return false;">' . $lang['mark_no'] . '</a>';
						
					#	$template->assign_block_vars('input._auth', array(
					#		'TITLE'		=> $lang['auths'][$fields[$i]],
					#		'FIELDS'	=> $fields[$i],
					#		'SELECT'	=> $select[$i],
					#	));
						
						$template->assign_block_vars("$tpl.auth", array('FIELDS' => $fields[$i]));
					}
				}
				else if ( $str == 'text' )
				{
					list($type, $size, $max) = explode(':', $tmp_type);
						
					if ( is_array($tmp_data) )
					{
						( count($tmp_data) == 4 ) ?
						list($value, $default, $order, $url) = array_values($tmp_data) :
						list($value, $default, $order) = array_values($tmp_data);
					}
					else
					{
				#		$value = ( strpos($tmp_name, 'filesize') !== false ) ? ($tmp_data/1048576) : $tmp_data;
						$value = $tmp_data;
					}
					
					$opt .= '<input type="text"' . ((isset($vars_type['class'])) ? ' class="' . $vars_type['class'] . '"' : '') . ' size="' . $size . '" maxlength="' . $max . '" name="' . sprintf('%s[%s]', $tmp_meta, $tmp_name) . '" id="' . sprintf('%s_%s', $tmp_meta, $tmp_name) . '" value="' . $value . '" />&nbsp;';
					$opt .= ( $tmp_name == 'path' ) ? is_writable($root_path . $tmp_data) ? img('i_iconn', 'icon_accept', '') : img('i_iconn', 'icon_cancel', '') : '';
					
					if ( $tmp_opts )
					{
						$checked = ( $default ) ? ' checked="checked"' : '';
						
						$opt .= ( in_array('url',	$tmp_opts) ) ? "<input type=\"text\" size=\"$size\" maxlength=\"$max\" name=\"{$tmp_meta}[$tmp_name]\" id=\"{$tmp_meta}_{$tmp_name}\" value=\"$url\" />&nbsp;" : '';
						$opt .= ( in_array('drop',	$tmp_opts) ) ? "" : "";
						$opt .= ( in_array('radio',	$tmp_opts) ) ? "<input type=\"radio\" name=\"{$tmp_meta}_default\" value=\"$tmp_name\"$checked>" : '';
					}
				}
				else if ( $str == 'textarea' )
				{
					if ( isset($tmp_parm) )
					{
						if ( $tmp_parm == TINY_NORMAL )
						{
							$template->set_filenames(array('tiny_normal' => 'style/tinymce_normal.tpl'));
							$template->assign_var_from_handle('TINYMCE', 'tiny_normal');
						}
						else
						{
							$template->set_filenames(array('tiny_news' => 'style/tinymce_news.tpl'));
							$template->assign_var_from_handle('TINYMCE', 'tiny_news');
						}
					
					}
					
					if ( substr_count($tmp_type, ':') >= 3 )
					{
						list($type, $cols, $max) = explode(':', $tmp_type);
						
						$opt .= "<textarea cols=\"$cols\" maxlength=\"$max\" name=\"{$tmp_meta}[$tmp_name]\" id=\"{$tmp_meta}_{$tmp_name}\">$tmp_data</textarea>";
					}
					else
					{
						list($type, $cols) = explode(':', $tmp_type);
						
						$opt .= "<textarea cols=\"$cols\" name=\"{$tmp_meta}[$tmp_name]\" id=\"{$tmp_meta}_{$tmp_name}\">$tmp_data</textarea>";
					}
				}
				else if ( $str == 'upload' )
				{
					list($type, $info) = explode(':', $tmp_type);
					
					$filesize	= $settings["path_$info"]['filesize']	? $settings["path_$info"]['filesize'] : '';
					$dimension	= $settings["path_$info"]['dimension']	? $settings["path_$info"]['dimension'] : '';				
				
				# sprintf($lang['sprintf_upload_info'], str_replace(':', ' x ', $settings['path_network']['dimension']), round($settings['path_network']['filesize']*1024) )
				
					$opt .= "<input type=\"file\" name=\"{$info}_img\">";
					$opt .= ( $filesize && $dimension ) ? "<br /><span class=\"small\">" . sprintf($lang['sprintf_upload_info'], str_replace(':', ' x ', $dimension), round($filesize*1024)) . "</span>" : '';
					$opt .= ( $tmp_data ) ? "<br /><br /><img src=\"$dir_path$tmp_data\" alt=\"\" /><br /><br /><label><input type=\"checkbox\" name=\"{$tmp_name}_delete\">&nbsp;{$lang['common_image_delete']}</label>" : '';
					
				# <br /><br /><img src="{IMAGE}" alt="" /><br /><br /><label><input type="checkbox" name="network_image_delete">&nbsp;{L_IMAGE_DELETE}</label>
				
				}
				else
				{
					$opt .= "else opt";
				}
				
				$explain = isset($vars_type['explain']) ? "{$vars_opt}_explain" : '';
				
				$template->assign_block_vars("$tpl.row.tab.option", array(
					'L_NAME'	=> $lngs,
					'ID'		=> isset($vars_type['trid']) ? 'id="' . $tmp_name . '" style="display:' . ( isset($data[$vars_type['trid']]) ? $data[$vars_type['trid']] <= 0 ? 'none' : '' : '') . ';"' : '',
					'CSS'		=> isset($vars_type['required']) ? 'row1r' : 'row1',
					'LABEL'		=> "{$vars_key}_{$vars_opt}",
					'OPTION'	=> $opt,
					'AUTH'		=> isset($vars_type['simple_auth']) ? '<br /><br />' . $info[1] : '',
					'EXPLAIN'	=> ( isset($vars_type['explain']) && isset($lang[$explain]) ) ? "title=\"{$lang[$explain]}\"" : '',
				));
			}
		}
	}
}

function select_navi($type, $data, $tpl)
{
	global $lang, $db, $settings;

	$settings_key = array_keys($settings);
	
	foreach ( $settings_key as $row )
	{
		if ( strpos($row, 'module_') !== false )
		{
			$mod[] = $row;
		}
	}
	
	$cnt = count($data);
	$select = '';
	
	for ( $i = 0; $i < $cnt; $i++ )
	{
		$select .= ( in_array($type, array('navi_left', 'navi_right')) ) ? '<div><div>' : '';
		$select .= "<select name=\"{$tpl}[$type][]\" id=\"\">";
		$select .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_item']) . "</option>";
		
		for ( $j = 0; $j < count($mod); $j++ )
		{
			$marked = ( $data[$i] == $mod[$j] ) ? ' selected="selected"' : '';
			$select .= "<option value=\"{$mod[$j]}\"$marked>" . sprintf($lang['sprintf_select_format'], $mod[$j]) . "</option>";
		}
		
		$select .= "</select>";
		$select .= ( in_array($type, array('navi_left', 'navi_right')) ) ?  "&nbsp;<input type=\"button\" class=\"more\" value=\"" . $lang['common_more'] . "\" onclick=\"clone(this)\"></div></div>" : '<br />';
	}
	
	return $select;
}

function get_version($host, $directory, $filename, &$errstr, &$errno, $port = 80, $timeout = 2)
{
	global $oCache;
	
	/*
		Versionsabfrage Idee von phpBB / andere Scripte
		
		@param: string	$host		example: cms-phoenix.de
		@param: string	$directory	example: /updatecheck
		@param: string	$filename	example: version.txt
		@param: string	$errstr		example: &$errstr
		@param: string	$errno		example: &$errno
		@param: string	$port		example: 80
		@param: string	$timeout	example: 2
	*/
	
	$sCacheName = 'version_cms';
	
	if ( ($info = $oCache -> readCache($sCacheName)) === false)
	{
		if ( $fsock = @fsockopen($host, $port, $errno, $errstr, $timeout))
		{
			@fputs($fsock, "GET $directory/$filename HTTP/1.1\r\n");
			@fputs($fsock, "HOST: $host\r\n");
			@fputs($fsock, "Connection: close\r\n\r\n");
	
			$get_info = false;
			
			while ( !@feof($fsock) )
			{
				if ( $get_info )
				{
					$info .= @fread($fsock, 1024);
				}
				else
				{
					if ( @fgets($fsock, 1024) == "\r\n" )
					{
						$get_info = true;
					}
				}
			}
			@fclose($fsock);
	
			$info = explode("\n", $info);
		}
		else
		{
			$info = 'fail host';
		}
		
		$oCache->writeCache($sCacheName, $info);
	}
	
	return $info;
}

function parsePHPInfo()
{
	/*	notwendig für die PHP/MySQL Supportinfos
	
		von http://www.php.net/manual/en/function.phpinfo.php#70306 - parse php modules from phpinfo
	
		alternativen:
		
		get a module setting
		function getModuleSetting($pModuleName,$pSetting)
		{
			$vModules = parsePHPModules();
			
			return $vModules[$pModuleName][$pSetting];
		}
	
		// Sample Usage
		debug(getModuleSetting('apache2handler','Apache Version')); // returns "bundled (2.0.28 compatible)"
		debug(getModuleSetting('Core','register_globals'));
	
		ob_start () ;
		phpinfo () ;
		$pinfo = ob_get_contents () ;
		ob_end_clean () ;
		
		// the name attribute "module_Zend Optimizer" of an anker-tag is not xhtml valide, so replace it with "module_Zend_Optimizer"
		echo '<div id="phpinfo">' . ( str_replace ( "module_Zend Optimizer", "module_Zend_Optimizer", preg_replace ( '%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo ) ) ) . '</div>';
	
		http://www.php.net/manual/en/function.phpinfo.php#106862
	
		function phpinfo_array()
		{
			ob_start();
			phpinfo();
			$info_arr = array();
			$info_lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
			$cat = "General";
			foreach($info_lines as $line)
			{
				// new cat?
				preg_match("~<h2>(.*)</h2>~", $line, $title) ? $cat = $title[1] : null;
				if(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
				{
					$info_arr[$cat][$val[1]] = $val[2];
				}
				elseif(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
				{
					$info_arr[$cat][$val[1]] = array("local" => $val[2], "master" => $val[3]);
				}
			}
			return $info_arr;
		}
		
		echo "<pre>".print_r(phpinfo_array(), 1)."</pre>";
	*/
	
	ob_start();
	phpinfo();
	$s = ob_get_contents();
	ob_end_clean();
	
	$s = strip_tags($s,'<h2><th><td>');
	$s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
	$s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
	$vTmp = preg_split('/(<h2>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);
	
	$vModules = array();
	
	for ( $i = 1; $i < count($vTmp); $i++ )
	{
		if ( preg_match('/<h2>([^<]+)<\/h2>/',$vTmp[$i],$vMat) )
		{
			$vName = trim($vMat[1]);
			$vTmp2 = explode("\n",$vTmp[$i+1]);
			
			foreach ( $vTmp2 AS $vOne )
			{
				$vPat = '<info>([^<]+)<\/info>';
				$vPat3 = "/$vPat\s*$vPat\s*$vPat/";
				$vPat2 = "/$vPat\s*$vPat/";
				
				if ( preg_match($vPat3,$vOne,$vMat) )
				{
					// 3cols
					$vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
				}
				else if ( preg_match($vPat2,$vOne,$vMat) )
				{
					// 2cols
					$vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
				}
			}
		}
	}
	
	return $vModules;
}

function size_round($size, $round)
{
	global $lang;
	
	/*
		Größe anzeigen mit Komma
		
		@param: int		$size		example: 12356
		@param: int		$round		example: 2
	*/
	
	$return = 0;
	
	if ( $size >= 1073741824 )
	{
		$return = round($size/1073741824, $round) . $lang['size_gb'];
	}
	else if ( $size >= 1048576 )
	{
		$return = round($size/1048576, $round) . $lang['size_mb'];
	}
	else if ( $size >= 1024 )
	{
		$return = round($size/1024, $round) . $lang['size_kb'];
	}
	else
	{
		$return = round($size, $round) . $lang['size_by'];
	}
	
	return $return;
}

function match_check_image($path, $count, $pics, $maps)
{
	global $root_path;
	
	$check = '';
	
	for ( $i = 0; $i < $count; $i++ )
	{
		foreach ( $pics as $key => $pic )
		{
			if ( $pic == $maps[$i]['map_picture'] )
			{
				$check['in']['picture'][] = ( file_exists($path . '/' . $maps[$i]['map_picture']) ) ? $maps[$i]['map_picture'] : 'nicht vorhanden';
				unset($pics[$key]);
			}
			
			if ( $pic == $maps[$i]['map_preview'] )
			{
				$check['in']['preview'][] = ( file_exists($path . '/' . $maps[$i]['map_preview']) ) ? $maps[$i]['map_preview'] : 'nicht vorhanden';
				unset($pics[$key]);
			}
		}
	}
	
	if ( $pics )
	{
		foreach ( $pics as $pic )
		{
			$check['out'][] = $pic;
		}
	}
	
	return $check;
}

/*
 *	Prüft Daten ob Leer und ob sie schon in der DB vorhanden sind!
 *
 *	@param: string	$tbl		example: games
 *	@param: string	$ary		example: Daten zum Überprüfen, mit ID!
 *	@param: string	$err		example: Fehler
 *
 */
function check($tbl, $ary, $err)
{
	global $db, $lang;

	$return = '';
	
	$_id = array_pop($ary);
	
	foreach ( $ary as $key => $val )
	{
		$tmp = explode("_", $key); 
		$end = array_pop($tmp);
		
		if ( $val )
		{
			$sql = "SELECT * FROM $tbl WHERE LOWER($key) LIKE '%" . strtolower($val) . "%'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$entry = $db->sql_fetchrowset($result);
			
			if ( $entry )
			{
				for ( $i = 0; $i < count($entry); $i++ )
				{
					if ( array_shift($entry[$i]) != $_id )
					{
						$return .= ( $return || $err ? '<br />' : '' ) . $lang['msg_available_' . $end];
					}
				}
			}
		}
		else
		{
			$return .= isset($val) ? ( $return || $err ? '<br />' : '' ) . $lang['msg_empty_' . $end] : '';
		}
	}
	
	return $return;
}

function maxi($table, $order, $where)
{
	global $db;
	
	$where_to = ( $where ) ? "WHERE $where" : "";
	
	$sql = "SELECT MAX($order) AS $order FROM $table $where_to";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return $return[$order];
}

function maxa($table, $order, $where)
{
	global $db;
	
	$where_to = ( $where ) ? "WHERE $where" : false;
	
	$sql = "SELECT MAX($order) AS $order FROM $table $where_to";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrow($result);
	
	return $return[$order] + 10;
}

function update($table, $index, $move, $index_id)
{
	global $db;
	
	$sql = "UPDATE $table SET " . $index . "_order = " . $index . "_order+{$move} WHERE " . $index . "_id = $index_id";
	if ( !$db->sql_query($sql) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
}

function orders_new($mode, $type, $id)
{
	global $db;

	switch ( $mode )
	{
		case FORUM:
			$idfield	= 'forum_id';
			$orderfield	= 'forum_order';
			$typefield	= 'cat_id';
			break;
	}

	$sql = "SELECT $idfield, $orderfield FROM $mode";
	
	switch ( $type )
	{
		case 'cat':
			$sql .= " WHERE ";
			break;
			
		case 'forum':
			$sql .= " WHERE cat_id = $id";
			break;
			
		case 'sub':
			$sql .= " WHERE forum_sub = $id";
			break;
	}
	
	/*
	if ( $type == '-1' )
	{
		$sql .= " WHERE $idfield != $type";
	}
	else if ( $type != '' )
	{
		$sql .= " WHERE $typefield = $type";
		$sql .= ( $mode == 'ranks' && $type == RANK_FORUM ) ?  ' AND rank_special = 1' : '';
	}
	*/
	$sql .= " ORDER BY $orderfield ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$i = 10;

	while ( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $mode SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$i += 10;
	}
}

#function orders($mode, $type = false, $subs = false)
function orders($mode, $type = false)
{
	global $db;
	
	if ( in_array($mode, array(MENU_CAT, DOWNLOADS_CAT, NEWS_CAT, MAPS_CAT, FORUM_CAT, PROFILE_CAT)) )
	{
		$idfield = 'cat_id';
		$orderfield	= 'cat_order';
	}

	switch ( $mode )
	{
		case FORMS:			$idfield = 'forum_id';		$orderfield = 'forum_order';	$typefield = 'forum_sub'; /* $subfield = 'forum_sub';*/	break;
		
		case GAMES:			$idfield = 'game_id';		$orderfield = 'game_order';		break;
		case SERVER:		$idfield = 'server_id';		$orderfield	= 'server_order';	break;
		case TEAMS:			$idfield = 'team_id';		$orderfield = 'team_order';		break;
		case MATCH_MAPS:	$idfield = 'map_id';		$orderfield = 'map_order';		break;
		case GALLERY:		$idfield = 'gallery_id';	$orderfield = 'gallery_order';	break;
		
		case MENU2:			$idfield = 'menu_id';		$orderfield = 'menu_order';		$typefield = 'menu_sub';			break;
		
		case DOWNLOADS:		$idfield = 'file_id';		$orderfield = 'file_order';		$typefield = 'cat_id';				break;
		case MENU:			$idfield = 'file_id';		$orderfield = 'file_order';		$typefield = 'cat_id';				break;
		case FORUM:			$idfield = 'forum_id';		$orderfield = 'forum_order';	$typefield = 'cat_id';				break;
		case MAPS:			$idfield = 'map_id';		$orderfield	= 'map_order';		$typefield = 'map_sub';				break;
		case GALLERY_PIC:	$idfield = 'pic_id';		$orderfield = 'pic_order';		$typefield = 'gallery_id';			break;
		case RANKS:			$idfield = 'rank_id';		$orderfield = 'rank_order';		$typefield = 'rank_type';			break;
		
		case FIELDS;		$idfield = 'field_id';		$orderfield = 'field_order';	$typefield = 'field_sub';			break;
		
		case PROFILE;		$idfield = 'profile_id';	$orderfield = 'profile_order';	$typefield = 'profile_cat';			break;
		case NAVI:			$idfield = 'navi_id';		$orderfield = 'navi_order';		$typefield = 'navi_type';			break;
		case GROUPS:		$idfield = 'group_id';		$orderfield	= 'group_order';										break;
		case NETWORK:		$idfield = 'network_id';	$orderfield = 'network_order';	$typefield = 'network_type';		break;
		case SERVER:		$idfield = 'server_id';		$orderfield = 'server_order';	$typefield = 'server_type';			break;
		case NAVI:			$idfield = 'navi_id';		$orderfield = 'navi_order';		$typefield = 'navi_type';			break;
		case FORUM:			$idfield = 'forum_id';		$orderfield = 'forum_order';	$typefield = 'cat_id';				break;
	}
	
	$sql = "SELECT $idfield, $orderfield FROM $mode";

	if ( $type != '' || $type == '0' )
	{
		$sql .= " WHERE $typefield = $type";
		$sql .= ( $mode == 'ranks' && $type == RANK_FORUM ) ?  ' AND rank_special = 1' : '';
	}
	
#	if ( $subs != '' || $subs == '0' )
#	{
#		$sql .= " AND $subfield = $subs";
#	}
	
	$sql .= " ORDER BY $orderfield ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$i = 10;

	while ( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $mode SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$i += 10;
	}
}

function size_dir($path)
{
	global $lang;
	
	$size = 0;

	if ( $dir = @opendir($path) )
	{
		while ( $file = @readdir($dir) )
		{
			if ( $file != '.' && $file != '..' && $file != 'index.htm' && $file != '.htaccess' && !strstr($file, '_preview') )
			{
				$size += @filesize($path . "/" . $file);
			}
		}
		@closedir($dir);

		//
		// This bit of code translates the avatar directory size into human readable format
		// Borrowed the code from the PHP.net annoted manual, origanally written by:
		// Jesse (jesse@jess.on.ca)
		//
		if ( $size >= 1048576 )
		{
			$size = round($size/1048576) . " MB";
		}
		else if( $size >= 1024 )
		{
			$size = round($size/1024) . " KB";
		}
		else
		{
			$size = $size . " Bytes";
		}
	}
	else
	{
		$size = $lang['msg_unavailable_size_dir'];
	}
	
	return $size;
}

function size_dir2($tmp_path)
{
	global $root_path, $lang;
	
	$path = $root_path . $tmp_path;
	$size = 0;	
	$dirs = is_dir($path) ? array_diff(scandir($path), array('.', '..', 'index.htm', '.svn', 'Thumbs.db', '.htaccess')) : false;
	
	if ( $dirs )
	{
		foreach ( $dirs as $subdir )
		{
			if ( is_dir($path . $subdir) )
			{
				$subdirs = array_diff(scandir($path . $subdir), array('.', '..', 'index.htm', '.svn', 'Thumbs.db', '.htaccess'));
				
				foreach ( $subdirs as $file )
				{
					if ( !strpos($file, '_preview') )
					{
						$size += @filesize($path . $subdir . '/' . $file);
					}
				}
			}
			else
			{
				if ( !strpos($subdir, '_preview') )
				{
					$size += @filesize($path . $subdir);
				}
			}
		}
		$size = size_round($size, 2);
	}
	
	$size = ( $size != 0 ) ? $size : $lang['msg_sizedir_empty'];
	
	return $size;
}

function size_file($file)
{
	$size = 0;
	
	if ( $file >= 1048576 )
	{
		$size = round($file / 1048576 * 100) / 100 . " MB";
	}
	else if( $file >= 1024 )
	{
		$size = round($file / 1024 * 100) / 100 . " KB";
	}
	else
	{
		$size = $size . " Bytes";
	}
	
	return $size;
}

function _size($size, $round = '')
{
	global $lang;
	
	$return = 0;
	
	if ( $size >= 1073741824 )
	{
		$return = round($size/1073741824, $round) . $lang['size_gb'];
	}
	else if ( $size >= 1048576 )
	{
		$return = round($size/1048576, $round) . $lang['size_mb'];
	}
	else if ( $size >= 1024 )
	{
		$return = round($size/1024, $round) . $lang['size_kb'];
	}
	else
	{
		$return = round($size, $round) . $lang['size_by'];
	}
	
	return $return;
}

/*
 *	Ordner erstellen. match, gallery
 *
 *	@param: string	$path		example: ./../upload/matchs/
 *	@param: string	$name		example: 01022001_
 *	@param: both	$cryp		example: true or false
 *
 */
function create_folder($path, $name, $cryp)
{
	global $lang;
	
	$folder_name = ( $cryp ) ? uniqid($name) : $name;
	$folder_path = $path . $folder_name;		
	
	mkdir("$folder_path", 0755);
	
	$file	= 'index.htm';
	$code	= $lang['empty_site'];
	$create	= fopen("$folder_path/$file", "w");
	
	fwrite($create, $code);
	fclose($create);
	
	return $folder_name;
}

function delete_folder($path)
{
	$dir = opendir($path);
	
	while ( $entry = readdir($dir) )
	{
		if ( $entry == '..' || $entry == '.' )
		{
			continue;
		}
		
		if ( is_dir($path . $entry) )
		{
			delete_folder($path . $entry . "/");
		}
		else
		{
			unlink($path . $entry);
		}
	}
	closedir($dir);
	rmdir($path);
}

function set_http(&$website)
{
	if ( !preg_match('#^http[s]?:\/\/#i', $website) )
	{
		$website = 'http://' . $website;
	}
	
	return $website;
}

function set_chmod($host, $port, $user, $pass, $path, $file, $perms)
{
	global $root_path, $db, $config, $settings, $lang;
	
	$conn = ftp_connect($host, $port, 3);
	
	if (!$conn) die('Verbindung zu ftp.example.com konnte nicht aufgebaut werden');
	
	// Login mit Benutzername und Passwort
	if (!ftp_login($conn, $user, $pass)) die('Fehler beim Login zu ftp.example.com');
	
	// Kommando "SITE CHMOD 0600 /home/user/privatefile" an den Server senden */
	if (ftp_site($conn, 'CHMOD 0' . $perms . ' ' . $path))
	{
		echo "Kommando erfolgreich ausgeführt.\n";
	}
	else
	{
		die('Kommando fehlgeschlagen.');
//		message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
	}
}

/*
 *	ID/Namen abfrage für DropDown Menüs
 *
 *	@param: string	$type	example: GAMES
 *	@param: string	$mode	example: 'name' / id
 *	@param: string	$select	example: 1 / 'cs.png'
 */
function search_image($type, $mode, $select)
{
	global $db;
	
	switch ( $mode )
	{
		case 'name':
		
			switch ( $type )
			{
				case GAMES:
					
					$sql = "SELECT game_image FROM $type WHERE game_id = $select";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
					
				case NEWS_CAT:
				
					$sql = "SELECT cat_image FROM $type WHERE cat_id = $select";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
			}
			
			break;
			
		case 'id':
		
			switch ( $type )
			{
				case GAMES:
					
					$sql = "SELECT game_id FROM $type WHERE game_image = '$select'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
					
				case NEWS_CAT:
				
					$sql = "SELECT cat_id FROM $type WHERE cat_image = '$select'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
			}
			
			break;
	}
	
	$tmp = $db->sql_fetchrow($result);
	$key = array_keys($tmp);
	$msg = $tmp[$key[0]];

	return $msg;
}

function search_user($type, $select)
{
	global $db;
	
	switch ( $type )
	{
		case 'name':	$sql = "SELECT user_name FROM " . USERS . " WHERE user_id = $select";	break;
		case 'id':		$sql = "SELECT user_id FROM " . USERS . " WHERE user_name = '$select'";	break;
	}
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$tmp = $db->sql_fetchrow($result);
	$key = array_keys($tmp);
	$msg = $tmp[$key[0]];

	return $msg;
}

/*
 *	Soll die Abfragen der Datenbank einfacher machen!
 *
 *	@param: string	$s_table	example: GAMES
 *	@param: string	$s_where	example: game_id != -1
 *	@param: string	$s_order	example: game_order ASC
 *	@param:	int		$s_sql		example: 1
 *	@param: both	$s_fetch	example: true or false 
 *
 */
function data($s_table, $s_where, $s_order, $s_sql, $s_fetch)
{
	global $db, $lang;
	
	switch ( $s_table )
	{
		case ICONS:			$field_id = 'icon_id';		break;
		case MAPS:			$field_id = 'map_id';		break;
		
		
		case AUTHLIST:		$field_id = 'authlist_id';	break;
		case EVENT:			$field_id = 'event_id';		break;
		case ERROR:			$field_id = 'error_id';		break;
		
		
		case MENU2:			$field_id = 'menu_id';		break;
		
		case MENU_CAT:		$field_id = 'cat_id';		break;
		case MENU:			$field_id = 'file_id';		break;
		
		case BANLIST:		$field_id = 'ban_id';		break;
		case CASH:			$field_id = 'cash_id';		break;
		case CONTACT:		$field_id = 'contact_id';	break;
		case DISALLOW:		$field_id = 'disallow_id';	break;
		case DOWNLOADS:		$field_id = 'file_id';		break;
		case DOWNLOADS_CAT:	$field_id = 'cat_id';		break;
		
		
		case FORMS:			$field_id = 'forum_id';		break;
		case FORMS_AUTH:	$field_id = array('group_id', 'forum_id');		break;
		
		
		case FORUM:			$field_id = 'forum_id';		break;
		case FORUM_CAT:		$field_id = 'cat_id';		break;
		case GALLERY:		$field_id = 'gallery_id';	break;			
		case GALLERY_PIC:	$field_id = 'gallery_id';	break;
		case GAMES:			$field_id = 'game_id';		break;
		case GROUPS:		$field_id = 'group_id';		break;
	#	case GROUPS_USERS:	$field_id = 'group_user_id';break;
		case LOGS:			$field_id = 'log_id';		break;
		case ERROR:			$field_id = 'error_id';		break;
		
		case MATCH_MAPS:	$field_id = 'map_id';		break;
		case NAVI:			$field_id = 'navi_id';		break;
		case NETWORK:		$field_id = 'network_id';	break;
		case NEWS_CAT:		$field_id = 'cat_id';		break;
		case NEWSLETTER:	$field_id = 'newsletter_id';break;
		case PROFILE:		$field_id = 'profile_id';	break;
		case PROFILE_CAT:	$field_id = 'cat_id';		break;
		case PROFILE_DATA:	$field_id = 'user_id';		break;
		
		case FIELDS:		$field_id = 'field_id';		break;
		case FIELDS_DATA:	$field_id = 'user_id';		break;
		
		case RANKS:			$field_id = 'rank_id';		break;
	#	case RATE:			$field_id = 'rate_id';		break;	
		case SERVER:		$field_id = 'server_id';	break;
		case SERVER_TYPE:	$field_id = 'type_id';		break;
		case TEAMSPEAK:		$field_id = 'teamspeak_id';	break;	
		case THEMES:		$field_id = 'themes_id';	break;
		case TRAINING:		$field_id = 'training_id';	break;
		case USERS:			$field_id = 'user_id';		break;
		
		case CASH_USER:		$field_id = 'cash_user_id';	$field_link = 'user_id';	$table_link = USERS;	$field_id2 = 'user_id';	break;
	#	case MAPS:			$field_id = 'map_id';		$field_link = 'cat_id';		$table_link = MAPS_CAT;	$field_id2 = 'cat_id';	break;
		case NEWS:			$field_id = 'news_id';		$field_link = 'news_cat';	$table_link = NEWS_CAT;	$field_id2 = 'cat_id';	break;
		case TEAMS:			$field_id = 'team_id';		$field_link = 'team_game';	$table_link = GAMES;	$field_id2 = 'game_id';	break;
#		case MATCH:			$field_id = 'match_id';		$field_link = 'team_id';	$table_link = TEAMS;	$field_id2 = 'team_id'; $field_link = 'team_game';	$table_link = GAMES;	$field_id2 = 'game_id';break;
		/* only match for index? */
		case MATCH:			( $s_sql == 4 ) ? $tmp_ary = array('s_table1' => MATCH, 'field_id1' => 'match_id', 'field_link1' => 'team_id', 's_table2' => TEAMS, 'field_id2' => 'team_id', 'field_link2' => 'team_game', 's_table3' => GAMES, 'field_id3' => 'game_id') : $field_id = 'match_id'; $field_link = 'team_id';
			break;
			
		case MATCH_TYPE:	$ary = array('s_table1' => MATCH_TYPE, 'name' => 'type_name', 'value' => 'type_value'); break;
		
		case SETTINGS:		$ary = array('s_table1' => SETTINGS, 'name' => 'settings_name', 'value' => 'settings_value'); break;

		default: message(GENERAL_ERROR, 'Error Data Mode: ' . $s_table);	break;
	}
	
#	$ary = array('type', 'cat', 'sub', '-1', 'user_name', 'user_id', 'level', 'live');
#	if ( strstr($s_where, in_array($s_where, $ary)) )
	/* strstr($s_where, 'WHERE') ist vielleicht nützlicher */
	if ( is_array($s_where) )
	{
		$where = "WHERE {$field_id[0]} = {$s_where[0]} AND {$field_id[1]} = {$s_where[1]}";
	}
	else if (
		strstr($s_where, '-1') ||
		strstr($s_where, 'cat') ||
		strstr($s_where, 'sub') ||
		strstr($s_where, 'live') ||
		strstr($s_where, 'type') ||
		strstr($s_where, 'level') ||
		strstr($s_where, 'in_send') ||
		strstr($s_where, 'user_id') ||
		strstr($s_where, 'user_name') ||
		strstr($s_where, 'match_id')
		)
	{
		$where = "WHERE $s_where";
	}
	else if ( strstr($s_where, 'WHERE') )
	{
		$where = $s_where;
	}
	else if ( $s_where )
	{
		$where = "WHERE $field_id = $s_where";
	}
	else
	{
		$where = '';
	}
	
#	$where = ( preg_match('/rank_type/i', $s_where) ) ? "WHERE $s_where" : false;
#	$where = ( $s_where ) ? "WHERE $field_id = $s_where" : '';
#	$where = ( $s_where == '-1' ) ? $field_where : false;
	$order = ( $s_order ) ? "ORDER BY $s_order" : '';
	
	switch ( $s_sql )
	{
		case 0: $sql = "SELECT * FROM $s_table $order"; break;
		case 1: $sql = "SELECT * FROM $s_table $where $order"; break;
		case 2: $sql = "SELECT t1.*, t2.* FROM $s_table t1, $table_link t2 WHERE t1.$field_id = $s_where AND t1.$field_link = t2.$field_id2"; break;
		case 3: $sql = "SELECT t1.*, t2.* FROM $s_table t1 LEFT JOIN $table_link t2 ON t1.$field_link = t2.$field_id2 WHERE t1.$field_id = $s_where"; break;
		/* match only ? */
		case 4: $sql = "SELECT m.*, t.*, g.* FROM {$tmp_ary['s_table1']} m LEFT JOIN {$tmp_ary['s_table2']} t ON m.{$tmp_ary['field_link1']} = t.{$tmp_ary['field_id2']} LEFT JOIN {$tmp_ary['s_table3']} g ON t.{$tmp_ary['field_link2']} = g.{$tmp_ary['field_id3']} $order"; break;
		case 5: $sql = "SELECT * FROM {$ary['s_table1']} $where"; break;
		default: message(GENERAL_ERROR, 'Wrong mode for data', '', __LINE__, __FILE__); break;
	}
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $s_fetch == '1' )
	{
		$return = $db->sql_fetchrow($result);
	}
	else if ( $s_fetch == '2' )
	{
		while ( $row = $db->sql_fetchrow($result) )
		{
			$return[$row[$ary['name']]] = unserialize($row[$ary['value']]);
		}
	}
	else
	{
		$return = $db->sql_fetchrowset($result);
	}
		
	return $return;
}

?>