<?php

function build_request_list($tbl, $vars, &$error, $field_id)
{
	global $db, $lang;
	
	if ( $field_id == 'icon_id' )
	{
		unset($vars['icon_icon']);
	}
	
	list($field, $id) = explode('_', $field_id);
	
	$sql = "SELECT $field_id, " . implode(', ', array_keys($vars)) . " FROM $tbl
				WHERE $field_id IN (" . implode(', ', array_keys($_POST[$field])) . ")";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$update[$row[$field_id]] = false;
		
		foreach ( array_keys($vars) as $key )
		{
			$data[$row[$field_id]][$key] = $row[$key];
			
			if ( $row[$key] != $_POST[$field][$row[$field_id]][$key] )
			{
				$update[$row[$field_id]] = true;
			}
		}
	}
	
	$request = '';
	
	foreach ( array_keys($_POST[$field]) as $key )
	{
		if ( $update[$key] )
		{
			foreach ( $vars as $name => $info )
			{
				$val = isset($vars[$name]['validate']) ? $vars[$name]['validate'] : false;				
				$req = isset($vars[$name]['required']) ? $vars[$name]['required'] : false;
				
				$request[$key][$name] = request(array($field, $key, $name), $val);
			}
		}
	}
	
	return $request;
}

#	$data_sql = build_request($tbl, $vars, 'game', $error);
#	$data_sql = build_request($vars, $error);

#function build_request($name, $vars, $multi, &$error, $sql_add = false)
function build_request($tbl, $vars, &$error, $mode, $name = false, $sql_add = false)
{
	global $db, $lang, $settings;
	
	$request = '';
	
#	debug($vars, 'vars');
#	debug($name, 'name');
	
	if ( $name )
	{
		if ( isset($vars[$name]) )
		{
			foreach ( $vars[$name] as $key_vars => $row_vars )
			{
				if ( strpos($key_vars, 'tab') !== false )
				{
					continue;
				}
				
				if ( is_array($_POST[$name][$key_vars]) )
				{
					if ( strpos($key_vars, 'dimension') !== false || strpos($key_vars, 'format') !== false || strpos($key_vars, 'preview') !== false )
					{
						$request[$key_vars] = sprintf('%s:%s', request(array($name, $key_vars, 0), $vars[$name][$key_vars]['validate']), request(array($name, $key_vars, 1), $vars[$name][$key_vars]['validate']));
					}
					else if ( strpos($key_vars, 'auth') !== false )
					{
						$request[$key_vars] = serialize(request(array($name, $key_vars), $vars[$name][$key_vars]['validate']));
					}
					else if ( strpos($key_vars, 'navi') !== false )
					{
						$request[$key_vars] = serialize(request(array($name, $key_vars), $vars[$name][$key_vars]['validate']));
						
					#	debug($request[$key_vars], $key_vars);
					}
					else
					{
						$request[$key_vars] = request(array($name, $key_vars), $vars[$name][$key_vars]['validate']);
					}
					
				#	debug($request[$key_vars], 'request key vars');
				}
				else
				{
					if ( $key_vars != 'show' && ( $name == 'match_type' || $name == 'match_war' || $name == 'match_league' ))
					{
						$value		= request(array($name, $key_vars), $vars[$name][$key_vars]['validate']);
						$order		= request(array("{$name}_order", $key_vars), INT);
						$default	= ( request("{$name}_default", TXT) == $key_vars ) ? 1 : 0;
						$url		= ( request(array("{$name}_url", $key_vars), TXT) ) ? request(array("{$name}_url", $key_vars), TXT) : false;
						
						$request[$key_vars] = array('value' => $value, 'order' => $order, 'default' => $default);
						$request[$key_vars] = ( empty($url) ) ? $request[$key_vars] : array_merge($request[$key_vars], array('url' => $url));
					}
					else
					{
						$request[$key_vars] = request(array($name, $key_vars), $vars[$name][$key_vars]['validate']);
						
					#	debug($request[$key_vars], 'request key vars');
					}				
				}
			}
		}
	}
	else
	{
		$multi = key($vars);
		
		$sql = 'SHOW FIELDS FROM ' . $tbl;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp = $db->sql_fetchrowset($result);
		
	#	debug($tmp, 'tmp');
		
		foreach ( $tmp as $row )
		{
			$temp[] = $row['Field'];
		}
		unset($temp[0]);
		
		if ( $sql_add && in_array($mode, array('create', 'news_create', 'cat_create', 'bankdata')) )
		{
			$sqla = is_array($sql_add) ? $sql_add : array($sql_add);
			$temp = array_merge($temp, $sqla);
		}
		
		foreach ( $temp as $rows )
		{
			/* nur vars die auch angeben sind überprüfen, sonst NOTICE Warnung */
			if ( isset($vars[$multi][$rows]) && is_array($vars[$multi][$rows]) )
			{
				$type	= isset($vars[$multi][$rows]['type'])	? $vars[$multi][$rows]['type'] : $vars[$multi][$rows];
				$check	= isset($vars[$multi][$rows]['check'])	? true : false;
				$prefix = isset($vars[$multi][$rows]['prefix']) ? $vars[$multi][$rows]['prefix'] : false;
			#	$requir = isset($vars[$multi][$rows]['required']) ? $vars[$multi][$rows]['required'] : false;
			
				$val = isset($vars[$multi][$rows]['validate']) ? $vars[$multi][$rows]['validate'] : false;				
				$req = isset($vars[$multi][$rows]['required']) ? $vars[$multi][$rows]['required'] : false;
				
				/* upload bei Downloads, Gallery */
				$type_typ = request(array($multi, 'type'), INT);
				$type_typ = ($type_typ) ? $type_typ : true;
				/* upload bei Downloads, Gallery */
				
				/* teilt den string, für bessere und schneller verarbeitung */
				@list($tbl_name, $tbl_type) = explode('_', $rows);
				
				/* order rausgenommen, könnte aber später wieder eingebunden werden, falls die mehrheit es wünscht */
				/*
				if ( strpos($rows, '_order') !== false ) 
				{
					$order = str_replace('_order', '_order_new', $rows);
					
					$request[$rows] = request(array($multi, $order), $vars[$multi][$rows]['validate']);
				}
				*/
				
			#	if ( strpos($rows, 'dimension') !== false || strpos($rows, 'format') !== false || strpos($rows, 'preview') !== false )
				if ( in_array($tbl_type, array('dimension', 'format', 'thumbnail')) )
				{
					$request[$rows] = sprintf('%s:%s', request(array($multi, $rows, 0), $val), request(array($multi, $rows, 1), $val));
				}
				else if ( $prefix )
				{
					$request[$rows] = str_replace(' ', '_', request(array($multi, $rows), $val));
				}
				else if ( in_array($rows, array('news_cat', 'team_game')) )
				{
					$request[$rows] = search_image($vars[$multi][$rows]['params'][1], 'id', request(array($multi, $rows), TXT));
				}
				else
				{
					$request[$rows] = request(array($multi, $rows), $val);
				#	debug($request[$rows], "$rows", true);
				}
				
				
				@list($type_main, $type_sub) = explode(':', $type);
				#	debug($type, 'type');
				#	debug($type_typ, 'type_typ');
				#	debug($tbl_type, 'tbl_type');
					
				#	debug("upload:$tbl_type", 'upload:$tbl_type');
				
				/* Upload Block */
			#	if ( in_array($type, array("upload:$tbl_type", "upload:flag", "upload:logo")) && $type_typ )
				if ( $type_main == 'upload' && in_array($tbl_type, array('flag', 'logo', 'image')) && $type_typ )
				{
				#	debug('if true');
				#	debug($type, 'type');
				#	debug($type_typ, 'type_typ');
				#	debug($tbl_type, 'tbl_type');
					
					$type	= str_replace(':', '_', $type);
					$upload = request_file("upload:$rows");
					$delete = request("{$rows}_delete", TYP);
					$current = request("current:$rows", TYP);
					
				#	debug($upload, $rows);
					
				#	debug($type, 'type');
				#	debug($upload, 'upload');
					
					if ( $tbl_type == 'file' )
					{
						$main = request(array($multi, 'main'), INT);
						
						$sql = "SELECT dl_file, dl_types, dl_size FROM " . DOWNLOAD . " WHERE dl_id = $main";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						$match = $db->sql_fetchrow($result);
						
						$path = $vars[$multi][$rows]['params'] . $match['dl_file'];
						
						if ( $upload )
						{
							$request[$rows] = upload_file($upload, $current, $path, unserialize($match['dl_types']), $match['dl_size'], $error);
							
						#	$request['dl_file'] = $trequest[0];
						#	$request['dl_type'] = $trequest[1];
						#	$request['dl_size'] = $trequest[2];
						}
						else
						{
							$request[$rows] = $current;
						}
						
						debug($request[$rows], 'file');
					}
					else
					{
						if ( $delete && !$upload )
						{
							image_delete($current, '', (is_array($vars[$multi][$rows]['params']) ? $vars[$multi][$rows]['params'][0] : $vars[$multi][$rows]['params']), false);
						}
						else if ( $upload )
						{
							$request[$rows] = upload_image(true, $rows, $rows, false, $current, false, (is_array($vars[$multi][$rows]['params']) ? $vars[$multi][$rows]['params'][0] : $vars[$multi][$rows]['params']), $upload, $error);
							
					#		debug($vars[$multi][$rows]['params']);
						}
						else
						{
							$request[$rows] = $current;
						}
						
					#	debug($request[$rows], 'else');
					}
					
					
				}
				
				if ( is_array($req) )
				{
					$required = $req[0];
					$required_check = ( $_POST[$multi][$req[1]] != $req[2] ) ? false : true;
				}
				else
				{
					$required = $req;
					$required_check = true;
				}
			
				if ( $required && $required_check )
			#	if ( $required && $required_check && $type != 'hidden' )
				{
					$request[$rows] = $prefix ? str_replace($prefix, '', $request[$rows]) : $request[$rows];
					
					
				#	debug($request[$rows] == '', $rows . '\'\'');
				#	debug($request[$rows] == '-1', $rows . '-1');
				
				#	$error = (isset($error)) ? $error : array();
					
					if ( $request[$rows] == '' || $request[$rows] == '-1' )
					{
						$error[] = lang("error_$required");
						$check = false;
					}
					
					$request[$rows] = $prefix ? $prefix . $request[$rows] : $request[$rows];
				}
				
				if ( $check && $required_check )
				{
					$sql = "SELECT * FROM $tbl";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					while ( $match = $db->sql_fetchrow($result) )
					{
						$field_name = array_shift(array_keys($match));
						$field_id = request('id', INT);
						
					#	$error = (isset($error)) ? $error : array();
						
						if ( strtolower($match[$rows]) == strtolower($request[$rows]) && $match[$field_name] != $field_id )
						{
							$error[] = sprintf($lang['sql_duplicate'], $lang[$rows], $match[$rows]);
						}					
					}
				}
				
			#	setzt die jeweilige Zeit
			#	if ( $tbl_name == 'time' && $mode == $tbl_type && $type == 'hidden' )
			#	{
			#		$request[$rows] = time();
			#	}
				
			#	if ( $tbl_name == 'time' )
			#	{
			#		if ( $mode == 'create' && ( $type == 'hidden' && $tbl_type == 'create' ) )
			#		{
			#			$request[$rows] = time();
			#		}
			#		
			#		if ( $mode == 'update' && ( $type == 'hidden' && $tbl_type == 'update' ) )
			#		{
			#			$request[$rows] = time();
			#		}
			#		
			#		if ( $mode == 'upload' && ( $type == 'hidden' && $tbl_type == 'upload' ) )
			#		{
			#			$request[$rows] = time();
			#		}
			#	}
				
				/* News Block */
				if ( $tbl_type == 'links' )
				{
					$compress	= array();
					$news_links	= request(array($multi, $rows), TXT);

					foreach ( $news_links['link'] as $key => $row )
					{
						if ( $row != '' &&  $news_links['name'][$key] != '' )
						{
							if ( !preg_match('#^http[s]?:\/\/#i', $row) )
							{
								$row = 'http://' . $row;
							}
					
							if ( !preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $row) )
							{
								$row = '';
							}
							
							$compress[] = sprintf('%s|%s', $row, $news_links['name'][$key]);
						}
					}
					
					$request[$rows] = serialize($compress);
				}
				
				if ( in_array($tbl_type, array('date', 'duration')) )
				{
					if ( $required_check )
					{
						$tparams = $vars[$multi][$rows]['params'];
						
						if ( $type == 'drop:datetime' || ( $settings['switch'][$tbl_name] && $type == 'drop:duration' ) )
						{
							$hour	= request("{$prefix}hour", INT);
							$min	= request("{$prefix}min", INT);
							$dura	= request("{$prefix}duration", INT);
							$month	= request("{$prefix}month", INT);
							$day	= request("{$prefix}day", INT);
							$year	= request("{$prefix}year", INT);
						}
						else if ( $tparams == 'format' )
						{
							list($date, $datetime) = explode(' ', $request[$prefix . $rows]);
							list($day, $month, $year) = explode('.', $date);
							list($hour, $min) = explode(':', $datetime);
						}
						
						if ( $type == 'drop:datetime' || $vars[$multi][$rows]['params'] == 'format' )
						{
							$request[$rows] = mktime($hour, $min, 00, $month, $day, $year);
							
							if ( !checkdate($month, $day, $year) )
							{
								$error[] = $lang['msg_select_date'];
							}
							
							if ( $mode == 'create' && time() >= $request[$rows] )
							{
								$error[] = $lang['error_select_past'];
							}
						}
						else
						{
							$request[$rows] = mktime($hour, $min + request("{$prefix}duration", INT), 00, $month, $day, $year);
						}
					}
					else
					{
						$request[$rows] = '';
					}
				}
				
				
			#	if ( in_array($rows, array('cat_type', 'cat_auth')) && in_array($name, array(GALLERY, GROUPS, DOWNLOADS_CAT)) )
			#	{
			#		$request[$rows] = is_array($request[$rows]) ? serialize($request[$rows]) : serialize(array($request[$rows]));
			#	}
				
			#	if ( in_array($rows, array('user_month')) && in_array($name, array(CASH)) )
			#	{
			#		$request[$rows] = is_array($request[$rows]) ? implode(', ', $request[$rows]) : $request[$rows];
			#	}
				
				if ( in_array($rows, array('training_maps', 'dl_types', 'cash_month', 'cash_paid')) )
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
			else
			{
				@list($tbl_name, $tbl_type) = explode('_', $rows);
				
				$request[$rows] = request(array($multi, $rows), TXT);
				
			#	debug($mode, 'mode');
			#	debug($tbl_type, 'tbl_type');
				
				if ( $tbl_name == 'time' && $mode == $tbl_type )
				{
					$request[$rows] = time();
				}
			}
		}
	}
	#serialize
	debug($request, 'function request return');

	return $request;
}

/*
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
		
	#	debug($data);
	#	debug($tmp);
		$moep = array_diff($data, $tmp);
	#	debug($moep);
		
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
			$template->assign_block_vars("$tpl.rows", array('SYMBOL' => '<img src="' . $root_path . $rows['icon_path'] . '" alt="">'));
			
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
					default:			$option .= 'default'; break;					
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
*/

function build_output_list($tbl, $vars, $data, $tpl, $string = '')
{
	global $db, $root_path, $lang, $template, $settings;
	
	foreach ( $vars as $name => $var )
	{
		if ( !is_array($var) )
		{
			continue;
		}
		
		$template->assign_block_vars("$tpl.name_option", array(
		#	'NAME' => str_replace($lang[$string], '', lang($name))
			'NAME' => lang($name),
		));
	}
	
#	debug($tpl, 'tpl');
	
	for ( $i = 0; $i < count($data); $i++ )
	{
		$template->assign_block_vars("$tpl.row", array());
		
		foreach ( $vars as $vars_opt => $vars_type )
		{
			if ( !is_array($vars_type) )
			{
				if ( $vars_type != 'hidden' )
				{
					$template->assign_block_vars("$tpl.row.tab", array(
						'L_LANG' => isset($lang[$vars_type]) ? $lang[$vars_type] : $vars_type
					));	
				}
				else
				{
					$tdata = @$data[$i][$name];
				#	$tdata = $multi ? @$sql_data[$vars_key][$vars_opt] : @$sql_data[$vars_opt];
					
					$tmp_opts = isset($vars_type['opt']) ? $vars_type['opt'] : '';
					
					$tmeta = $vars_key;
					$tname = $vars_opt;
					$hidden = '<input type="hidden" name="' . sprintf('%s[%s]', $tmeta, $tname) . '" value="' . $tdata . '" />';
					
					$template->assign_block_vars("$tpl.row.hidden", array('HIDDEN' => $hidden));
				}
			}
			else
			{
				$ttype = $vars_type['type'];
				$tdata = @$data[$i][$vars_opt];
				
				list($vars_key, $ttname) = explode('_', $name);
				
				$tmeta = $vars_key;
				$tname = $vars_opt;
				
				$tparams = isset($vars_type['params']) ? $vars_type['params'] : false;
				
				$lngs = isset($lang[$vars_opt]) ? $lang[$vars_opt] : $vars_opt;
				
			#	list($type, $option) = ( strpos($string,':') !== false ) ? explode(':', $ttype) : $ttype;
				list($type, $option) = explode(':', $ttype);
								
				$f_id	= sprintf('%s_%s', $tmeta, $tname);
				$f_name	= sprintf('%s[%s][%s]', $tmeta, $data[$i][key($data[$i])], $tname);
				
				$return = '';
			#	debug($tdata);
				
				switch ( $type )
				{
					case 'checkbox':
					
						$checked = ($tdata) ? ' checked="checked"' : '';
					
						$return .= '<input type="hidden" name="' . $f_name . '" id="' . $f_id . '" value="0" />';
						$return .= '<input type="checkbox"' . $checked . ' name="' . $f_name . '" id="' . $f_id . '" value="1" />';
						
						break;
					
					case 'text':
						
						list($size, $max) = explode(';', $option);
					
						if ( is_array($tdata) )
						{
							( count($tdata) == 4 ) ? list($value, $order, $default, $url) = array_values($tdata) : list($value, $order, $default) = array_values($tdata);
						}
						else if ( isset($opt['params']) && $opt['params'] == 'format' )
						{
							$value = ( $tdata != '' ) ? date('d.m.Y H:i', $tdata) : (int) 0;
						}
						else
						{
							$value = $tdata;
						}
						
						/* für Kommentarfunktion: placeholder="Kommentar schreiben ..." */
						
						$return .= '<input type="text"' . ( isset($opt['class'] ) ? ' class="' . $opt['class'] . '"' : '') . ' size="' . $size . '" maxlength="' . $max . '" name="' . $f_name . '" id="' . $f_id . '" value="' . $value . '" />';
						
						break;
						
					case 'info':
					
						$return .= $tdata . '<input type="hidden" name="' . $f_name . '" id="' . $f_id . '" value="' . $tdata . '" />';
						
						break;
						
					case 'icon':
					
					#	debug($data[$i][$tparams], 'data');
					#	debug($tdata[$i][$tparams], 'tdata');
					#	debug($tparams, 'tparams');
					
						$return .= '<img class="icon" src="' . $root_path . $data[$i][$tparams] . '" title="" alt="" />';
					#	debug($data[$tparams], 'data');
					#	debug($tdata[$tparams], 'data');
						break;
				}
				
				$template->assign_block_vars("$tpl.row.type_option", array(
				#	'L_NAME'	=> $lngs,
				#	'DIV_START'	=> (isset($vars_type['divbox'])) ? '<div id="' . $tname . '" style="display:' . $none . ';">' : '',
				#	'DIV_END'	=> (isset($vars_type['divbox'])) ? '</div>' : '',
				#	'CSS'		=> $css,
				#	'LABEL'		=> "{$vars_key}_{$vars_opt}",
					'TYPE'	=> $return,
				#	'EXPLAIN'	=> ( isset($vars_type['explain']) && isset($lang[$explain]) ) ? ' title="' . $lang[$explain] . '"' : '',
				));
			}
		}
	}
}

function build_output($tbl, $vars, $data, $tpl = 'input', $multi = false)
{
	global $db, $root_path, $lang, $template, $settings, $userdata, $dir_path, $mode, $action;
	
	$time = time();
	
	if ( !$multi )
	{
		foreach ( $data as $data_key => $data_value )
		{
			$sql_data[$data_key] = $data_value;
		}
	}
	
	foreach ( $vars as $vars_key => $vars_value )
	{
		if ( $multi )
		{
			$sql_data = '';
			
			$keys = array_keys($vars_value);
			
			foreach ( $data as $data_key => $data_value )
			{
				if ( $vars_key == $data_key )
				{
					$sql_data[$data_key] = unserialize($data_value);
				}
			}
		}
		
		$template->assign_block_vars("$tpl.row", array('L_NAME' => lang($vars_key)));
		
		foreach ( $vars_value as $vars_opt => $vars_type )
		{
	#		debug($vars_opt);
			if ( !is_array($vars_type) )
			{
				if ( $vars_type != 'hidden' )
				{
					$template->assign_block_vars("$tpl.row.tab", array('L_LANG' => isset($lang[$vars_type]) ? $lang[$vars_type] : $vars_type));	
				}
				else
				{
					$tdata = $multi ? @$sql_data[$vars_key][$vars_opt] : @$sql_data[$vars_opt];
					
					$tmp_opts = isset($vars_type['opt']) ? $vars_type['opt'] : '';
					
					$tmeta = $vars_key;
					$tname = $vars_opt;
					$hidden = '<input type="hidden" name="' . sprintf('%s[%s]', $tmeta, $tname) . '" value="' . $tdata . '" />';
					
					$template->assign_block_vars("$tpl.row.hidden", array('HIDDEN' => $hidden));
				}
			}
			else
			{
				/* Fehlerunterdrückung für Daten beim War um das Training zuerstellen (@). */
				$tmp_opts = isset($vars_type['opt']) ? $vars_type['opt'] : '';
				
				$ttype = $vars_type['type'];
				$tdata = ( $multi ) ? @$sql_data[$vars_key][$vars_opt] : @$sql_data[$vars_opt];
				$tmeta = $vars_key;
				$tname = $vars_opt;
				
			#	debug($vars_key, '$tmeta');
				
				$tparams = isset($vars_type['params']) ? $vars_type['params'] : false;
				
				$lngs = isset($lang[$vars_opt]) ? $lang[$vars_opt] : $vars_opt;
				
				$f_id	= sprintf('%s_%s', $tmeta, $tname);
				$f_name	= sprintf('%s[%s]', $tmeta, $tname);
				
			
				$opt = '';
				$css = '';
				
				$explain = isset($vars_type['explain']) ? "{$vars_opt}_explain" : '';
				
			#	$pos = strpos($ttype, ':');
			#	$str = substr($ttype, 0, $pos);
			
				list($type, $option) = explode(':', $ttype);
				
			#	debug($type, 'type');
			#	debug($option, 'option');
				/*
				if ( isset($vars_type['ajax']) )
				{
					list($ajaxphp, $ajaxtpl) = explode(':', $vars_type['ajax']);
					
					$template->set_filenames(array('ajax'	=> "style/{$ajaxtpl}.tpl"));
					$template->assign_vars(array('FILE'		=> $ajaxphp));
					$template->assign_var_from_handle('AJAX', 'ajax');
				}
				*/
				
				switch ( $type )
				{
					case 'ajax':	/* cash, match, (groups) */
					
						list($size, $max) = explode(';', $option);
						
						if ( is_array($vars_type['params']) )
						{
							list($typ, $new, $level) = explode(':', $vars_type['params'][0]);
						}
						else
						{
							$typ = $vars_type['params'];
						}
						
						$ajax_file = ( $typ != 'server' ) ? ( $typ == 'rival' ) ? 'ajax_rival.php' : 'ajax_user.php' : 'ajax_gs.php';
						
						$opt .= '<input type="text" size="' . $size . '" maxlength="' . $max . '" name="' . $f_name . '" id="' . $f_id . '" value="' . $tdata . '" onkeyup="look_' . $tname . '(this.value' . (( substr_count($vars_type['params'][0], ':') >= 1 ) ? sprintf(', %s, %s', $new, $level) : '')  . ');" onblur="set_' . $tname . '();" autocomplete="off"><div class="suggestionsBox" id="' . $tname . '" style="display:none;"><div class="suggestionList" id="auto_' . $tname . '"></div></div>';
						
						$template->assign_block_vars("$tpl.ajax", array('NAME' => $tname, 'FILE' => $ajax_file));
				
						break;
						
					case 'check':	/* cash */
					
					#	debug($mode, 'mode');
					#	debug($tparams, 'tparams');
					
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
						
							if ( in_array($i, unserialize($tdata)) )
							{
								$check = ' checked="checked"';
							}
							
							$switch[] = '<label><input type="checkbox" name="' . sprintf('%s[%s][%s]', $tmeta, $tname, $i) . '"' . $check . ' value="' . $i . '">&nbsp;' . $monate[$i] . '</label>';
						}
						
						$opt .= implode('<br />', $switch);
					
						break;
						
					case 'double':
					
						$f_name	= sprintf('%s[%s][]', $tmeta, $tname);
					
						list($value_a, $value_b) = explode(':', $tdata);
						list($size, $max) = explode(';', $option);
							
						$opt .= '<input type="text" size="' . $size . '" maxlength="' . $max . '" name="' . $f_name . '" value="' . $value_a . '" id="' . $f_id . '" />';
						$opt .= '<span style="padding:4px;">&bull;</span>';
						$opt .= '<input type="text" size="' . $size . '" maxlength="' . $max . '" name="' . $f_name . '" value="' . $value_b . '" />';
						
						break;
						
					case 'drop':
					
						$pre = isset($vars_type['prefix']) ? $vars_type['prefix'] : '';
						
						switch ( $option )
						{
							case 'navi_left':       
							case 'navi_top':        
							case 'navi_right':	$opt = select_navi($option, $tdata, $tmeta); break;
							
							/* acp_games, acp_server // tdata = default, tmeta/tname = name und id, params = game/voice server */
						#	case 'server':	$opt .= s_types($tdata, $tmeta, $tname, $vars_type['params']); break;
							case 'gameq':	$opt .= s_gameq($tdata, $tmeta, $tname, (isset($data[$tparams]) ? $data[$tparams] : $tparams)); break;	/* games */
							case 'main':	$opt .= s_main($tdata, $tmeta, $tname, $data); break;
								
							case 'copy':	
						#	case 'forms':	$opt .= select_forms($tdata, $tmeta, $tname, $option);	break;
							case 'forms':	$opt .= s_copy($tdata, $tmeta, $tname, $option);	break;
							
							case 'file':
							
								if ( isset($data['main']) && $data['main'] )
								{
									$tmpdb	= data(MAPS, 'WHERE map_id = ' . $data['main'], 'main ASC, map_order ASC', 1, true);
									$files	= array_diff(scandir($vars_type['params'] . $tmpdb['map_tag']), array('.', '..', '.htaccess', 'index.htm', '.svn', 'spacer.gif'));
									$format	= array('png', 'jpg', 'jpeg', 'gif');
									
									if ( $files )
									{
										$tmp_files = array();
										foreach ( $files as $row )
										{
											if ( $row != '.' && $row != '..' && $row != 'index.htm' && $row != '.svn' && $row != 'spacer.gif' )
											{
												if ( in_array(substr($row, -3), $format) )
												{
													$tmp_files[] = $row;
												}
											}
										}
																			
										if ( $tmp_files )
										{
											$img_path = $root_path . $settings['path_maps'] . $tmpdb['map_tag'] . '/';
											
											$current_image = '';
											
											$opt .= '<div id="close"><select name="' . $f_name . '" id="' . $f_id . '" onkeyup="update_image_' . $tmpdb['map_tag'] . '(this.options[selectedIndex].value);" onchange="update_image_' . $tmpdb['map_tag'] . '(this.options[selectedIndex].value);">';
											
											foreach ( $tmp_files as $row )
											{
												if ( $tdata == $row )
												{
													$current_image = $img_path . $row;
												}
												
												$mark = ( $tdata == $row ) ? ' selected="selected"' : '';
												$mask = str_replace(substr($row, strrpos($row, '.')), "", $row);
									
												$opt .= '<option value="' . $row . '"' . $mark . '>' . sprintf($lang['stf_select_format'], $mask) . '</option>';
											}
											
											$opt .= '</select><br /><img src="' . $current_image . '" id="image" alt="" /></div><div id="ajax_content"></div>';
										}
										else
										{
											$opt .= '<div id="close">select cat oder keine bilder vorhanden</div><div id="ajax_content"></div>';
										}
									}
									else
									{
										$opt .= '<div id="close">select cat oder keine bilder vorhanden</div><div id="ajax_content"></div>';
									}
								}
								else
								{
									$opt .= '<div id="close">select cat oder keine bilder vorhanden</div><div id="ajax_content"></div>';
								}
								
								break;
							
							case 'files':
								
								$folder = $vars_type['params'][0];
								$filter = $vars_type['params'][1];
								$change = $vars_type['params'][2];
								$ending = false;
							
								switch ( $vars_type['params'][3] )
								{
									case 'navi':
									
										$files = scandir($folder);
										$tdata = str_replace('./', '', $tdata);
										
										$nfiles['dir'] = $files;
									
										break;
									
									case 'menu':
									
										$format = '%s [%s]';
										
										$setmodules = 1;
										
										$files = scandir($folder);
										
										foreach ( $files as $key => $tmp_file )
										{
											if ( preg_match('/^admin_.*?\.php$/', $tmp_file) )
											{
											#	debug($tmp_file);
												$modules[$key] = include('./' . $tmp_file);
												
												if ( !is_array($modules[$key]) )
												{
													unset($modules[$key]);
												}
											}
										}
										
										sort($modules);
										
									#	unset($setmodules);
										
										foreach ( $modules as $row )
										{
											$cat = isset($row['cat']) ? $row['cat'] : 'none';
											$nfiles[$cat][] = array($row['title'] => $row['filename']);
										}
										
										unset($setmodules, $modules);
										
										break;
								}
								
								switch ( $change )
								{
									case 'mode':	$opt .= '<select name="' . $f_name . '" id="' . $f_id . '" onchange="display_modes(this.value);">'; break;
									case 'self':	$opt .= '<select name="' . $f_name . '" id="' . sprintf('%s', $tname) . '"  onchange="' . $f_id . '.value = this.value;">';
													$opt .= '<option value="">' . sprintf($lang['stf_select_format'], $lang['notice_select_file']) . '</option>';
													$ending = true;
										break;
										
									default: $opt .= '<select name="' . $f_name . '" id="' . $f_id . '">'; break;
								}
								
							#	debug($nfiles, 'nfiles');
								
								foreach ( $nfiles as $group => $files )
								{
									$opt .= '<optgroup label="' . lang($group) . '">';
									
									foreach ( $files as $file )
									{
									
									if ( is_array($file) )
									{
										$temp_lng = isset($lang[key($file)]) ? $lang[key($file)] : key($file);
										$temp_lang = sprintf($format, $temp_lng, str_replace($filter, '', current($file)));
										$temp_file = current($file);
									}
									else
									{
										$temp_lang = str_replace($filter, '', $file);
										$temp_file = $file;
									}
																	
									if ( strstr($temp_file, $filter) )
									{
										$mark = ( $tdata == $temp_file ) ? ' selected="selected"' : '';
										$opt .= '<option value="' . $temp_file . '"' . $mark . '>' . $temp_lang . '</option>';
									}
									}
									
									$opt .= '</optgroup>';
								}
								$opt .= '</select>';
								
								$opt .= ($ending) ? '&nbsp;<input type="text" size="25" name="' . $f_name . '" id="' . $f_id . '" value="' . $tdata . '" />' : '';
								
								break;
							
							case 'image':
							
								/* games */
							
								$folder = $vars_type['params'][0];
								$filter = $vars_type['params'][1];
								$layout = isset($vars_type['params'][2]) ? $vars_type['params'][2] : '';
								$cimage = '';
								
								$template->assign_vars(array('IPATH' => $folder));
								
								$files = array_diff(scandir($folder), array('.', '..', '.htaccess', '.htm', '.svn', 'spacer.gif'));
								
								$opt .= '<select name="' . $f_name . '" id="' . $f_id . '" onchange="update_image(this.options[selectedIndex].value);">';
								$opt .= '<option value="">' . sprintf($lang['stf_select_format'], $lang['notice_select_image']) . '</option>';
								
								foreach ( $files as $tfile )
								{
									if ( in_array(substr($tfile, strrpos($tfile, '.')), $filter) )
									{
										$lng = substr($tfile, 0, strrpos($tfile, '.'));
										
										if ( $tdata == $tfile )
										{
											$cimage = $folder . $tfile;
										}
										
										$opt .= '<option value="' . $tfile . '"' . (( $tdata == $tfile ) ? ' selected="selected"' : '') . '>' . sprintf($lang['stf_select_format'], $lng) . '</option>';
									}
								}
								
								$opt .= '</select>';
								
								$opt .= $layout ? '&nbsp;<img src="' . $cimage . '" id="image" alt="" height="16" />' : '<br /><img class="img" src="' . $cimage . '" id="image" alt="" />';
								
								break;
								
							case 'images':
								
								$folder = $vars_type['params'][0];
								$db_sql = $vars_type['params'][1];
								$layout = $vars_type['params'][2];
								
						#		debug($layout, '$layout');
								
								$sql = 'SHOW FIELDS FROM ' . $db_sql;
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								while ( $row = $db->sql_fetchrow($result) )
								{
									$temp[] = $row['Field'];
								}
								
								$field_id		= array_shift($temp);
								$field_name		= array_shift($temp);
								$field_image	= array_shift($temp);
								$field_order	= array_pop($temp);
								
								$sql = 'SELECT * FROM ' . $db_sql . ' ORDER BY ' . $field_order . ' ASC';
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$db_tmp = $db->sql_fetchrowset($result);
								
							#	debug($db_tmp, 'db_tmp');
							#	debug($tdata, 'tdata');
								
								$opt .= '<select name="' . $f_name . '" id="' . $f_id . '"' . ( (isset($layout)) ? ' onchange="update_image(this.options[selectedIndex].value);"' : '') . '>';
								$opt .= '<option value="">' . sprintf($lang['stf_select_format'], $lang['notice_select' . str_replace($tmeta, '', $tname) . '_image']) . '</option>';
								
								$cimage = '';
								
								foreach ( $db_tmp as $row )
								{
									if ( $tdata == $row[$field_id] )
									{
										$cimage = $folder . $row[$field_image];
									}
									
								#	$lng = substr($tfile, 0, strrpos($tfile, '.'));
									
								#	debug($row[$field_image], 'field_id');
									
									$opt .= '<option value="' . $row[$field_image] . '"' . (( $tdata == $row[$field_id] ) ? ' selected="selected"' : '') . '>' . sprintf($lang['stf_select_format'], $row[$field_name]) . '</option>';
								}
								
								$opt .= '</select>';
								$opt .= ( $layout ) ? '&nbsp;<img src="' . $cimage . '" id="image" alt="" height="16" />' : '<br /><img class="img" src="' . $cimage . '" id="image" alt="" />';
							
								break;
								
							case 'tags':
							
								$folder = $vars_type['params'][0];
								$db_sql = $vars_type['params'][1];
								
								$sql = 'SHOW FIELDS FROM ' . $db_sql;
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								
								while ( $row = $db->sql_fetchrow($result) )
								{
									$temp[] = $row['Field'];
								}
								
								$field_id		= array_shift($temp);
								$field_name		= array_shift($temp);
								$field_image	= array_shift($temp);
								$field_order	= array_pop($temp);
								
								$sql = 'SELECT * FROM ' . $db_sql . ' ORDER BY ' . $field_order . ' ASC';
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$db_tmp = $db->sql_fetchrowset($result);
								
								$opt .= '<select name="' . $f_name . '" id="' . $f_id . '">';
								$opt .= '<option value="">' . sprintf($lang['stf_select_format'], $lang['msg_select' . str_replace($tmeta, '', $tname) . '_image']) . '</option>';
								
								$cimage = '';
								
								foreach ( $db_tmp as $row )
								{
									if ( $tdata == $row[$field_id] )
									{
										$cimage = $folder . $row[$field_image];
									}
									
								#	debug($row[$field_image], 'field_id');
									
									$opt .= '<option value="' . substr($row[$field_image], 0, strrpos($row[$field_image], '.')) . '"' . (( $tdata == substr($row[$field_image], 0, strrpos($row[$field_image], '.')) ) ? ' selected="selected"' : '') . '>' . sprintf($lang['stf_select_format'], $row[$field_name]) . '</option>';
								}
								
								$opt .= '</select>';
															
								break;
							
							case 'game':	$opt = select_games($tdata, $tmeta, $tname, $vars_type['params']); break;	
							case 'iopts':
							
								$folder = $vars_type['params'];
							
								$setmodules = 1;
								
								$files = scandir($folder);
								
								foreach ( $files as $key => $tmp_file )
								{
									if ( preg_match('/^admin_.*?\.php$/', $tmp_file) )
									{
										$modules[$key] = include('./' . $tmp_file);
										
										if ( !is_array($modules[$key]) )
										{
											unset($modules[$key]);
										}
									}
								}
								
								sort($modules);
								
							#	unset($setmodules);
								
								foreach ( $modules as $module_info )
								{
									$template->assign_block_vars("$tpl.m_names", array('A_NAME' => $module_info['filename']));
									
									foreach ( $module_info['modes'] as $key => $row )
									{
										$template->assign_block_vars("$tpl.m_names.modes", array(
											'A_OPTION' => lang($row['title']),
											'A_VALUE' => $key
										));
									}
								}
								
							#	foreach ( $modules as $key => $_modules )
							#	{
							#		$_new[$key]['file'] = $_modules['filename'];
							#		$_new[$key]['name'] = $_modules['title'];
							#		
							#		foreach ( $_modules['modes'] as $_mode => $_modes )
							#		{
							#			$_new[$key]['mode'][$_mode] = $_modes['title'];
							#		}
							#	}
							#	
							#	debug(serialize($_new));
								
								$opt .= '<select name="' . $f_name . '" id="' . $f_id . '">';
								
								$first_file = current($modules[0]);
								
								foreach ( $modules as $module_info )
								{
									$tmp_file = !empty($data['menu_file']) ? $data['menu_file'] : $first_file;
									
									if ( $module_info['filename'] == $tmp_file )
									{
										foreach ( $module_info['modes'] as $keys => $rows )
										{
											$mark = ( $keys == $tdata ) ? ' selected="selected"' : "";
											$opt .= '<option value="' . $keys . '"' . $mark . '>' . lang($rows['title']) . '</option>';
										}
									}
									
									
								}
								
								$opt .= '</select>';
								
								unset($setmodules, $modules);
								
								break;
							
							case 'maps':		$opt = select_maps($tdata, $tmeta, $tname, $data);	break;
												/* match */
							case 'match':		$opt = select_match($tdata, $tmeta, $tname); break;
							case 'disable':		$opt = page_mode_select(unserialize($tdata), "{$tmeta}[page_disable_mode]"); break;
							case 'lang':		$opt = select_lang($tdata, "{$tmeta}[default_lang]", "language"); break;
							case 'style':		$opt = select_style($tdata, "{$tmeta}[default_style]", "../templates"); break;
							case 'tz':			$opt = select_tz($tdata, "{$tmeta}[default_timezone]"); break;
							case 'userlevel':	$opt = select_level($tdata, "{$tmeta}[$tname]", $vars_type['params']); break;
							case 'datetime':	$opt = sprintf('%s.%s.%s - %s:%s',
													select_date('select', 'day',	"{$pre}day",	date('d', is_numeric($vars_type['params']) ? $tdata : $time), $time),
													select_date('select', 'month',	"{$pre}month",	date('m', is_numeric($vars_type['params']) ? $tdata : $time), $time),
													select_date('select', 'year',	"{$pre}year",	date('Y', is_numeric($vars_type['params']) ? $tdata : $time), $time),
													select_date('select', 'hour',	"{$pre}hour",	date('H', is_numeric($vars_type['params']) ? $tdata : $time), $time),
													select_date('select', 'min',	"{$pre}min",	date('i', is_numeric($vars_type['params']) ? $tdata : $time), $time));
								break;
												/* match */
												
							case 'duration':	$opt = select_date('select', 'duration', "{$pre}duration", ( $tdata - $data[$vars_type['params']] ) / 60, $tmeta); break;
												/* match */
							case 'team':		$opt = select_team($tdata, $tmeta, $tname, $vars_type['params']); break;
												/* training, match */
							case 'match_type':	$opt = match_types($tdata, $tmeta, $tname); break;
												/* match */
							case 'match_war':	$opt = match_types($tdata, $tmeta, $tname); break;
												/* match */
							case 'match_league':$opt = match_types($tdata, $tmeta, $tname); break;	
												/* match */
							case 'dtype':
							
								$mime_type = array('meta_application', 'meta_image', 'meta_text', 'meta_video');
								$tdata = unserialize($tdata);
								
								$opt .= '<select name="' . sprintf('%s[%s][]', $tmeta, $tname) . '" id="' . $f_id . '" size="15" multiple="multiple">';
								
								foreach ( $mime_type as $meta_type )
								{
									$lang_type = str_replace('meta', 'type', $meta_type);
				
									$opt .= "<optgroup label=\"" . sprintf($lang['stf_select_format'], $lang[$meta_type]) . "\">";
									
									foreach ( $lang[$lang_type] as $key => $row )
									{
										$marked = ( in_array($key, $tdata) ) ? ' selected="selected"' : '';
										$opt .= '<option value="' . $key . '"' . $marked . '>' . sprintf($lang['stf_select_format'], $key) . '</option>';
									}
								}
				
								$opt .= '</select>';
							
								break;
											
							case 'rank':
							
							#	debug($tname);
							
								$sql = 'SELECT rank_id, rank_name, rank_order FROM ' . RANKS . ' WHERE rank_type = ' . $vars_type['params'] . ' ORDER BY ' . (( $vars_type['params'] != RANK_FORUM ) ? 'rank_order' : 'rank_min');
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
							
								$opt .= '<select name="' . sprintf('%s[%s]', $tmeta, $tname) . '" id="' . $f_id . '">';
								$opt .= '<option value="">' . sprintf($lang['stf_select_format'], $lang['notice_select_rank']) . '</option>';
								
								while ( $row = $db->sql_fetchrow($result) )
								{
									$selected = ( $row['rank_id'] == $tdata ) ? ' selected="selected"' : '';
									$opt .= '<option value="' . $row['rank_id'] . '"' . $selected . '>' . sprintf($lang['stf_select_format'], $row['rank_name']) . '</option>';
								}
								$opt .= '</select>';
							
								break;
							
							default: $opt .= 'drop default'; break;
						}
						
						break;
						
					case 'links':	/* news */
					
						$f_name	= sprintf('%s[%s][name][]', $tmeta, $tname);
						$f_link	= sprintf('%s[%s][link][]', $tmeta, $tname);
						
						$utdata = unserialize($tdata);
					
						if ( isset($utdata) )
						{
							foreach ( $utdata as $row )
							{
								list($link, $name) = explode('|', $row);
								
								$opt .= '<div><ul>';
								$opt .= '<input type="text" size="' . $option . '" name="' . $f_link . '" id="' . $f_id . '" value="' . $link . '">';
								$opt .= '<span style="padding:4px;">&bull;</span>';
								$opt .= '<input type="text" size="' . $option . '" name="' . $f_name . '" value="' . $name . '">&nbsp;';
								$opt .= '<input class="more" type="button" value="' . $lang['com_remove'] . '" onClick="this.parentNode.parentNode.removeChild(this.parentNode)">';
								$opt .= '</ul></div>';
							}
						}
						
						$opt .= '<div><div>';
						$opt .= '<input type="text" size="' . $option . '" name="' . $f_link . '" id="' . $f_id . '" value="" />';
						$opt .= '<span style="padding:4px;">&bull;</span>';
						$opt .= '<input type="text" size="' . $option . '" name="' . $f_name . '" value="" />&nbsp;';
						$opt .= '<input class="more" type="button" value="' . $lang['common_more'] . '"onclick="clone(this)">';
						$opt .= '</div></div>';

						break;
						
					case 'radio':
					
						$tmp_lang = '';
						$tmp_main = array('dl', 'forum', 'gallery', 'menu', 'profile');
						
						if ( isset($lang[$ttype]) )
						{
							$tmp_lang = $lang[$ttype];
						}
						else if ( $option == 'icons' )
						{
							$tmp_db = data(ICONS, 'WHERE icon_download = 1', 'icon_order ASC', 1, false);
							
							if ( $tmp_db )
							{
								foreach ( $tmp_db as $tmp_key => $tmp_value )
								{
									$tmp_lang[$tmp_value['icon_id']] = $tmp_value['icon_path'];
								}
							}
							
							array_unshift($tmp_lang, 'none');
						}
						else if ( in_array($tmeta, $tmp_main) )
						{
							$label = $menu = false;
							
							debug($action, 'action 2');
														
							switch ( $tmeta )
							{
								case 'dl':		$tbl = DOWNLOAD;	break;
								case 'gallery':	$tbl = GALLERY_NEW;	break;
								case 'profile':	$tbl = PROFILE;		break;
								
								case 'forum':	$tbl = FORUM;	$label = true;	break;
								case 'menu':	$tbl = MENU;	$label = ($action == 'acp') ? true : false; $menu = true;	break;
							}
							
							$_id	= $tmeta . '_id';
							$_name	= $tmeta . '_name';
							$_order	= $tmeta . '_order';
							
							$sql = "SELECT * FROM $tbl " . ($menu ? (($action == 'acp') ? "WHERE action = 'acp'" : "WHERE action = 'pcp'") : '') . " ORDER BY main ASC, $_order ASC";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$cat = $lab = array();
							
							if ( $db->sql_numrows($result) )
							{
								$tmp = $db->sql_fetchrowset($result);
								
								foreach ( $tmp as $rows )
								{
									if ( in_array($rows['type'], array(0, 3)) )
									{
										$main[$rows[$_id]] = $rows;
									}
									else if ( in_array($rows['type'], array(1, 4)) )
									{
										$labels[$rows['main']][$rows[$_id]] = $rows;
									}
									else
									{
										$entry[$rows['main']][$rows[$_id]] = $rows;
									}
								}
							}
							
							$switch = $settings['smain'][$tmeta . '_switch'];
							$entrys = $settings['smain'][$tmeta . '_entrys'];
						#	$subs	= (isset($settings['smain'][$tmeta . '_subs'])) ? $settings['smain'][$tmeta . '_subs'] : false;
							
							#			0/3	1/4		2
							# dl		cat file
							# gallery	cat file
							# profile	cat field
							# forum		cat label	forum
							# menu		cat label	$menu
							
							foreach ( $main as $m_key => $m_row )
							{
								$tmp_lang[$m_row[$_id]] = array('id' => $m_row[$_id], 'typ' => 1, 'lng' => lang($m_row[$_name]));
								
								if ( isset($labels[$m_key]) && ($label ? true : $entrys) )
								{
									foreach ( $labels[$m_key] as $l_key => $l_row )
									{
										$tmp_lang[$l_row[$_id]] = array('id' => $l_row[$_id],'typ' => 2, 'lng' => lang($l_row[$_name]));
										
										if ( isset($entry[$l_key]) && $entrys )
										{
											foreach ( $entry[$l_key] as $e_key => $e_row )
											{
												$tmp_lang[$e_row[$_id]] = array('id' => $e_row[$_id], 'typ' => 3, 'lng' => lang($e_row[$_name]));
											}
										}
									}
								}
							}
						}
						else
						{
							$sql = "SELECT * FROM " . $tbl . " WHERE $tname = 0 ORDER BY {$tmeta}_order ASC";
							if ( !($result = $db->sql_query($sql)) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							$tmp_db = $db->sql_fetchrowset($result);
							
							if ( $tmp_db )
							{
								foreach ( $tmp_db as $tmp_key => $tmp_value )
								{
									$tmp_lang[$tmp_value["{$tmeta}_id"]] = $tmp_value["{$tmeta}_name"];
								}
							}
						}
						
						$first = true;
						$break = '';
						$click = '';
						
					#	debug($tmp_lang, 'tmp_lang');
						
						$opt .= ( $option == 'main' && in_array($tmeta, $tmp_main) ) ? '<div id="close">' : '';
						
						foreach ( $tmp_lang as $var => $lng )
						{
							if ( isset($vars_type['params']) )
							{
								$break = $vars_type['params'][1];
								
								switch ( $vars_type['params'][0] )
								{
									case 'gameq':	$click = ' onclick="setRequest(\'' . $var . '\', \'' . $data['server_game'] . '\');"';	break;
									case 'type':	$click = ' onclick="display_options(this.value);"';		break;
									case 'ajax':	$click = ' onclick="setRequest(\'' . $var . '\');"';	break;
																																																																							#type, meta, name, curt, mode, data
							#		case 'combi':	$click = ' onclick="display_options(this.value); setRequest(' . sprintf('this.value, \'%s\', \'%s\', \'%s\', \'%s\', \'%s\'', $tmeta, ((isset($vars_type['params'][2])) ? $vars_type['params'][2] : $tname ), $data['type'], $mode, ((isset($data["{$tmeta}_id"])) ? $data["{$tmeta}_id"] : 0)) . ');"';	break;
									case 'combi':	$click = ' onclick="display_options(this.value); setRequest(' . sprintf('this.value, \'%s\', \'%s\', \'%s\', \'%s\', \'%s\'', $tmeta, ((isset($vars_type['params'][2])) ? $vars_type['params'][2] : $tname ), $data['type'], $mode, ( $tmeta != 'menu' ? ((isset($data["{$tmeta}_id"])) ? $data["{$tmeta}_id"] : 0) : $data['main'])) . ');"';	break;
								}
							}
							
						#	debug($tdata, 'tdata');
						#	debug($var, 'var');
							
							if ( $tmeta == 'group' && ( $var == GROUP_SYSTEM || $tdata == GROUP_SYSTEM || ($tname == 'group_access' && $data['group_type'] == GROUP_SYSTEM) ) )
							{
								$click = ' disabled="disabled"';
							}
							
							$idcheck = ( $first )			? ' id="' . $f_id . '"' : '';
							$checked = ( $tdata == $var )	? ' checked="checked"' : '';
							$onclick = $click;
							
							if ( $option == 'main' && in_array($tmeta, $tmp_main) )
							{
								if ( $data['type'] == 2 )
								{
									switch ( $lng['typ'] )
									{
										case 1: $opt .= '<label><input type="radio" disabled="disabled" />&nbsp;' . $lng['lng'] . '</label>'; break;
										case 2:	$opt .= '&nbsp; &nbsp;<label><input type="radio" name="' . $f_name . '" value="' . $lng['id'] . '"' . $checked . ' />&nbsp;' . $lng['lng'] . '</label>'; break;
										case 3:	$opt .= '&nbsp; &nbsp;&nbsp; &nbsp;<label><input type="radio" disabled="disabled" />&nbsp;' . $lng['lng'] . '</label>'; break;
									}
								}
								else if ( $data['type'] == 4 )
								{
									switch ( $lng['typ'] )
									{
										case 1: $opt .= '<label><input type="radio" name="' . $f_name . '" value="' . $lng['id'] . '"' . $checked . ' />&nbsp;' . $lng['lng'] . '</label>'; break;
										case 2:	$opt .= '&nbsp; &nbsp;<label><input type="radio" disabled="disabled" />&nbsp;' . $lng['lng'] . '</label>'; break;
									}
								}
								else
								{
									switch ( $lng['typ'] )
									{
										case 1: $opt .= '<label><input type="radio" name="' . $f_name . '" value="' . $lng['id'] . '"' . $checked . ' />&nbsp;' . $lng['lng'] . '</label>'; break;
										case 2:	$opt .= '&nbsp; &nbsp;<label><input type="radio" disabled="disabled" />&nbsp;' . $lng['lng'] . '</label>'; break;
										case 3:	$opt .= '&nbsp; &nbsp;&nbsp; &nbsp;<label><input type="radio" disabled="disabled" />&nbsp;' . $lng['lng'] . '</label>'; break;
									}
								}
							}
							else
							{
								$opt .= '<label><input type="radio" name="' . $f_name . '" value="' . $var . '"' . $idcheck . $onclick . $checked . ' />&nbsp;' . ((in_array(substr($lng, strrpos($lng, '.')), array('.png', '.jpg', '.jpeg', '.gif'))) ? '<img src="' . $root_path . $lng . '" alt="" />' : $lng) . '</label>';
							}
							
							$opt .= ( $break ) ? "<br />\n" : '<span style="padding:4px;"></span>';
							
							$first = false;
						}
						
						$opt .= ( $option == 'main' && in_array($vars_key, $tmp_main) ) ? '</div><div id="ajax_content"></div>' : '';
						$opt .= ( $tmeta == 'group' && $tname == 'group_type' && $tdata == GROUP_SYSTEM ) ? '<input type="hidden" name="' . $f_name . '" value="' . GROUP_SYSTEM . '" />' : '';
						$opt .= ( $tmeta == 'group' && $tname == 'group_access' && $data['group_type'] == GROUP_SYSTEM ) ? '<input type="hidden" name="' . $f_name . '" value="' . $data['group_access'] . '" />' : '';
						
						break;
						
					case 'text':
					
						list($size, $max) = explode(';', $option);
					
						if ( is_array($tdata) )
						{
							( count($tdata) == 4 ) ? list($value, $order, $default, $url) = array_values($tdata) : list($value, $order, $default) = array_values($tdata);
						}
						else if ( isset($vars_type['params']) && $vars_type['params'] == 'format' )
						{
							$value = ( $tdata != '' ) ? date('d.m.Y H:i', $tdata) : (int) 0;
						}
						else
						{
							$value = $tdata;
						}
						
						/* für Kommentarfunktion: placeholder="Kommentar schreiben ..." */
						
						$opt .= '<input type="text"' . ( isset($vars_type['class'] ) ? ' class="' . $vars_type['class'] . '"' : '') . ' size="' . $size . '" maxlength="' . $max . '" name="' . $f_name . '" id="' . $f_id . '" value="' . $value . '" />';
						$opt .= ( $tname == 'path' || $vars_key == 'path' ) ? is_writable($root_path . $tdata) ? '&nbsp;' . img('i_iconn', 'icon_accept', '') : '&nbsp;' . img('i_iconn', 'icon_cancel', '') : '';
						
						if ( $tmp_opts )
						{
							$f_url		= sprintf('%s_url%s', $tmeta, $tname);
							$f_order	= sprintf('%s_order%s', $tmeta, $tname);
							$f_default	= sprintf('%s_default', $tmeta, $tname);
							
							$checked = ( $default ) ? ' checked="checked"' : '';
							
							$opt .= ( in_array('url',	$tmp_opts) ) ? '<input type="text" name="' . $f_url . ' value="id" />&nbsp;' : '';
							$opt .= ( in_array('drop',	$tmp_opts) ) ? '<input type="text" name="' . $f_order . '" value="' . $order . '" />&nbsp;' : '';
							$opt .= ( in_array('radio',	$tmp_opts) ) ? '<input type="radio" name="' . $f_default . '" value="' . $tname . '"' . $checked . '" />' : '';
						}
						
						break;
						
					case 'textarea':
					
						$switch = false;
					
						if ( isset($vars_type['params']) )
						{
							if ( $vars_type['params'] == TINY_NORMAL )
							{
								print '<script type="text/javascript" src="./../includes/js/tiny_mce/jquery.tinymce.js"></script>';
								print '<script type="text/javascript" src="./../includes/js/tiny_mce/normal.js"></script>';
							}
							else if ( $vars_type['params'] == TINY_NEWS )
							{
								print '<script type="text/javascript" src="./../includes/js/tiny_mce/jquery.tinymce.js"></script>';
								print '<script type="text/javascript" src="./../includes/js/tiny_mce/news.js"></script>';
					
							#	$switch = true;
							}
						}
						
						if ( substr_count($ttype, ':') >= 3 )
						{
							list($type, $cols, $max) = explode(':', $ttype);
							
							$opt .= '<textarea ' . ( isset($vars_type['class'] ) ? 'class="' . $vars_type['class'] . '" ' : '') . 'cols="' . $cols . '" maxlength="' . $max . '" name="' . $f_name . '" id="' . $f_id . '">' . $tdata . '</textarea>';
						}
						else
						{
							list($type, $cols) = explode(':', $ttype);
							
							$opt .= '<textarea ' . ( isset($vars_type['class'] ) ? 'class="' . $vars_type['class'] . '" ' : '') . 'cols="' . $cols . '" name="' . $f_name . '" id="' . $f_id . '">' . $tdata . '</textarea>';
						#	$opt .= ($switch) ? '<br /><a href="javascript:;" onmousedown="$(\'#' . $f_id . '\').tinymce().show();">[Show]</a><a href="javascript:;" onmousedown="$(\'#' . $f_id . '\').tinymce().hide();">[Hide]</a>' : '';
						}
						
						break;
						
					case 'upload':	/* downloads, groups, network, teams */
					
					#	debug($type, 'type');
					#	debug($option, 'option');
					#	debug($tname, 'tname');
					
						if ( $option == 'file' )
						{
							$opt .= '<input type="file" name="upload_file">';
							$opt .= ( $tdata ) ? '<input type="hidden" name="current_file" value="' . $tdata . '" />' : '';
						}
						else
						{
						#	debug($vars_type['params']);
							$filesize	= ( $settings['path_' . $vars_type['params'][1]]['filesize'] )	? $settings['path_' . $vars_type['params'][1]]['filesize'] : '';
							$dimension	= ( $settings['path_' . $vars_type['params'][1]]['dimension'] )	? $settings['path_' . $vars_type['params'][1]]['dimension'] : '';
							
							$opt .= '<input type="file" name="upload:' . $tname . '">';
							$opt .= ( $filesize && $dimension ) ? '<br /><span>' . sprintf($lang['stf_upload_info'], str_replace(':', ' x ', $dimension), round($filesize*1024)) . '</span>' : '';
							$opt .= ( $tdata ) ? '<br /><br /><img src="' . $vars_type['params'][0] . $tdata . '" alt="" /><br /><br /><label><input type="checkbox" name="' . sprintf('%s_delete', $tname) . '">&nbsp;' . $lang['com_image_delete'] . '</label>' : '';
							$opt .= ( $tdata ) ? '<input type="hidden" name="current:' . $tname . '" value="' . $tdata . '" />' : '';
						}
						
						break;
						
					case 'select':
					
						switch ( $option )
						{
							case 'types':
					
								$mime_type = array('mime_application', 'mime_image', 'mime_text', 'mime_video');
								$tdata = unserialize($tdata);
								
								$opt .= '<select name="' . sprintf('%s[%s][]', $tmeta, $tname) . '" id="' . $f_id . '" size="15" multiple="multiple">' . "\n";
								
								foreach ( $mime_type as $mime )
								{
									$opt .= '<optgroup label="' . sprintf($lang['stf_select_format'], $lang[$mime]) . '">' . "\n";
									
									foreach ( $lang["{$mime}_type"] as $typ => $mime )
									{
										$opt .= '<option value="' . $mime . '"' . ((in_array($mime, $tdata)) ? ' selected="selected"' : '') . '>' . sprintf($lang['stf_select_format'], $typ) . '</option>' . "\n";
									}
								}
				
								$opt .= '</select>';
								
								break;
								
							case 'navi_user':
							case 'navi_menu':
							
								$sql = 'SELECT * FROM ' . MENU . ' WHERE action = "pcp" AND type = 3 ORDER BY main ASC, menu_order ASC';
								if ( !($result = $db->sql_query($sql)) )
								{
									message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
								}
								$tmp = $db->sql_fetchrowset($result);
								
								$cnt = count($tmp);
								
								$tdata = unserialize($tdata);
								
								$opt .= '<select name="' . sprintf('%s[%s][]', $tmeta, $tname) . '" id="' . $f_id . '" size="' . $cnt . '"' . (($option == 'navi_menu') ? ' multiple="multiple"': '') . '>' . "\n";
								
								foreach ( $tmp as $row )
								{
									$opt .= '<option value="' . $row['menu_id'] . '"' . ((in_array($row['menu_id'], $tdata)) ? ' selected="selected"' : '') . '>' . sprintf($lang['stf_select_format'], $row['menu_name']) . '</option>' . "\n";
								}
				
								$opt .= '</select>';
							
								break;
						}
					
						break;
						
					case 'show':
					
						switch ( $option )
						{
							case 'time':
							
								$opt .= create_date($userdata['user_dateformat'], $tdata, $userdata['user_timezone']);
								$opt .= ( $tdata ) ? '<input type="hidden" name="current_file" value="' . $tdata . '" />' : '';
							
								break;
						}
					
						break;
						
					default: $opt .= "else opt";
					
						break;					
				}
				
				if ( isset($vars_type['divbox']) )
				{
					$trid = is_bool($vars_type['divbox']) ? $vars_opt : $vars_type['divbox'];
					$none = '';
					
					if ( ($tmeta != 'gallery' || $tmeta != 'rank' || $tmeta != 'calendar') && isset($data['type']) )
					{
					#	debug($data['type'], 'data type');
						$type_0 = array('copy',
										'menu_file', 'menu_opts',
										'map_info', 'map_file',
										'forum_desc', 'forum_icons', 'forum_legend', 'forum_status',
										'profile_field', 'profile_typ', 'profile_lang', 'profile_show_user', 'profile_show_member', 'profile_show_register', 'profile_required',
										'dl_file');
						$type_0 = ($mode == 'create') ?  array_merge($type_0, array('main')) : $type_0;
						
						$type_1 = array('menu_file', 'menu_opts', 'map_tag', 'dl_desc', 'dl_icon', 'dl_types', 'dl_size');
						$type_3 = array('main', 'menu_target', 'menu_intern', 'menu_file', 'menu_opts');
						$type_4 = array('menu_opts');
						
						if ( $data['type'] == 0 && in_array($trid, $type_0) ) { $none = 'none'; }
						if ( $data['type'] == 1 && in_array($trid, $type_1) ) { $none = 'none'; }
						if ( $data['type'] == 3 && in_array($trid, $type_3) ) { $none = 'none'; }
						if ( $data['type'] == 4 && in_array($trid, $type_4) ) { $none = 'none'; }
					}
					
					/* Calendar added 30.06.2013 */
					if ( $tmeta == 'calendar' && isset($settings['calendar']['show']) )
					{
						if ( $settings['calendar']['show'] == 0 && in_array($trid, array('compact')) ) { $none = 'none'; }
					}
					
				#	if ( $tmeta && isset($data['type']) )
				#	{
				#		if ( $data['type'] == 0 && in_array($trid, array('main', 'map_info', 'map_file')) ) { $none = 'none'; }
				#	}
					
					if ( $tmeta == 'gallery' && isset($data['type']) )
					{
						$type_0 = array('gallery_picture');
						$type_0 = ($mode == 'create') ?  array_merge($type_0, array('main')) : $type_0;
						$type_1 = array('copy', 'gallery_acpview', 'gallery_dimension', 'gallery_format', 'gallery_filesize', 'gallery_thumbnail');
						
						if ( $data['type'] == 0 && in_array($trid, $type_0) ) { $none = 'none'; }
						if ( $data['type'] == 1 && in_array($trid, $type_1) ) { $none = 'none'; }
					}
					
					if ( $tmeta == 'rank' && isset($data['rank_type']) )
					{
						$type_1 = array('rank_special', 'rank_min');
						$type_2 = array('rank_standard', 'rank_min');
						$type_3 = array('rank_special', 'rank_min');
						$type_4 = array('rank_standard');
						
						if ( $data['rank_type'] == 1 && in_array($trid, $type_1) ) { $none = 'none'; }
						if ( $data['rank_type'] == 2 && $data['rank_special'] == 4 && in_array($trid, $type_2) ) { $none = 'none'; }
						if ( $data['rank_type'] == 2 && $data['rank_special'] == 5 && in_array($trid, $type_4) ) { $none = 'none'; }
						if ( $data['rank_type'] == 3 && in_array($trid, $type_3) ) { $none = 'none'; }
					}
					
					if ( $tmeta == 'type' && isset($data['cash_type']) )
					{
						$type_1 = array('cash_month');
						
						if ( $data['cash_type'] == 0 && in_array($trid, $type_1) ) { $none = 'none'; }
					}
					
					if ( isset($data['training_on']) ) { if ( $data['training_on'] == 0 && in_array($trid, array('training_date', 'training_duration', 'training_maps', 'training_text')) ) { $none = 'none'; } }
				}
				
				$css .= (isset($vars_type['required'])) ? 'red' : '';
				$css .= (isset($vars_type['explain']) && isset($lang[$explain])) ? ' u' : '';
				
				$template->assign_block_vars("$tpl.row.tab.option", array(
					'L_NAME'	=> $lngs,
					'DIV_START'	=> (isset($vars_type['divbox'])) ? '<div id="' . $tname . '" style="display:' . $none . ';">' : '',
					'DIV_END'	=> (isset($vars_type['divbox'])) ? '</div>' : '',
					'CSS'		=> $css,
					'LABEL'		=> "{$vars_key}_{$vars_opt}",
					'OPTION'	=> $opt,
					'EXPLAIN'	=> ( isset($vars_type['explain']) && isset($lang[$explain]) ) ? ' title="' . $lang[$explain] . '"' : '',
				));
			}
			
			if ( $vars_opt == 'label_type' )
			{
				global $action;
				
				$access = $acl_groups = array();
				
			#	debug($data['label_id'], 'label_id');
				
				if ( isset($data['label_id']) )
				{
					$sql = 'SELECT o.*, d.*
								FROM ' . ACL_OPTION . ' o
									LEFT JOIN ' . ACL_LABEL_DATA . ' d ON o.auth_option_id = d.auth_option_id
								WHERE o.auth_option LIKE "' . $tdata . '%"
									AND d.label_id = ' . $data['label_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					/*
					$fetchrowset = $db->sql_fetchrowset($result);
					debug($fetchrowset);
					
					debug($sql);
					
					SELECT o.*, f.*
FROM cms_acl_options o
LEFT JOIN cms_acl_fields f ON o.field_id = f.field_id
WHERE f.field_name LIKE  "g_%"
AND o.option_id =7
LIMIT 0 , 30
					*/
					while ( $row = $db->sql_fetchrow($result) )
					{
						$access[$row['auth_option']] = $row['auth_value'];
					}
					$db->sql_freeresult($result);
					
				#	debug($access, 'access');
				
					$sql = 'SELECT g.group_id, g.group_name
								FROM ' . GROUPS . ' g, ' . ACL_GROUPS . ' ag
							WHERE g.group_id = ag.group_id
								AND label_id = ' . $data['label_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$label_acl_groups = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
				#	debug($label_acl_groups, 'label_acl_groups');
					
					$sql = 'SELECT u.user_id, u.user_name
								FROM ' . USERS . ' u, ' . ACL_USERS . ' au
							WHERE u.user_id = au.user_id
								AND label_id = ' . $data['label_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$label_acl_users = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
				#	debug($label_acl_users, 'label_acl_users');
				
					if ( $label_acl_groups )
					{
						global $ityp;
						
						foreach ( $label_acl_groups as $row )
						{
							$acl_gps[] = href('a_txt', 'admin_groups.php?', array('id' => $row['group_id'], 'i' => $ityp), $row['group_name'], $row['group_name']);
						}
						
						$implode_groups = implode(', ', $acl_gps);

						$template->assign_block_vars('input.acl_groups', array('GROUPS' => $implode_groups));
					}
					
					if ( $label_acl_users )
					{
						global $ityp;
						
						/* noch anpassen */
						
						foreach ( $label_acl_users as $row )
						{
							$acl_urs[] = href('a_txt', 'admin_user.php?', array('id' => $row['user_id'], 'i' => $ityp), $row['user_name'], $row['user_name']);
						}
						
						$implode_users = implode(', ', $acl_urs);

						$template->assign_block_vars('input.acl_users', array('USERS' => $implode_users));
					}
				}
				
				$sql = 'SELECT * FROM ' . ACL_OPTION . '  WHERE auth_option LIKE "' . $tdata . '%"';
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $row = $db->sql_fetchrow($result) )
				{
					$acl_option[$row['auth_option']] = isset($data['label_id']) ? isset($row['auth_value']) ? $row['auth_value'] : 0 : 0;
					$acl_groups[$row['auth_group']][$row['auth_option']] = $row['auth_option'];
				}
				$db->sql_freeresult($result);
				
				$acl_group = $acl_groups;
				$acl_groups = array();
				
				foreach ( $acl_group as $grouped => $vars )
				{
					foreach ( $vars as $keys )
					{
						$acl_groups[$grouped][] = $keys;
					}
				}
				
				foreach ( $acl_groups as $cat => $rows )
				{
					$template->assign_block_vars('input.cats', array(
						'CAT'		=> $cat,
						'NAME'		=> $lang["tabs:{$action}"][$cat],
						'OPTIONS'	=> "options00{$cat}",
					));
					
					foreach ( $rows as $row )
					{
						$row_format = sprintf('set[%s]', $row);
						
						$template->assign_block_vars('input.cats.auths', array(
							'LANG'		=> lang($row),
							'OPT_YES'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s_y', $row) . '" value="1"' . (( @$access[$row] == 1 ) ? ' checked="checked"' : '') . ' /></label>',
							'OPT_UNSET'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s_u', $row) . '" value="0"' . (( @$access[$row] == 0 ) ? ' checked="checked"' : '') . ' /></label>',
							'OPT_NEVER'	=> '<label><input type="radio" name="' . $row_format . '" id="' . sprintf('%s_n', $row) . '" value="-1"' . (( @$access[$row] == -1 ) ? ' checked="checked"' : '') . ' /></label>',
						));
					}
				}
			}
		}
	}
}

?>