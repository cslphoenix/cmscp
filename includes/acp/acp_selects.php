<?php

/*
	function select_path()				//	acp_settings
	function select_perms()				//	acp_settings
	function select_box_image();		//	acp_news
	function select_team();				//	acp_teams
	function select_level();			//
	function select_box();				//
	function select_box_files();		//	acp_games, acp_newscat
!	function select_box_game();			//
	function select_rank();				//
!	function select_newscategory();		//
	function select_lang_box();			//
	function _select_match();			//
	function page_mode_select();		//
	function select_cat();				//
	function select_order_cat();		//
	function select_order();			//
	function select_add_lang();			//
*/

function select_main($default, $meta, $name, $data)
{
	global $db, $lang;
	
	switch ( $meta )
	{
		case 'menu':	$tbl = MENU2; $field_id = $meta . '_id'; $field_name = $meta . '_name'; $field_order = $meta . '_order'; break;
		case 'forum':	$tbl = FORMS; $field_id = $meta . '_id'; $field_name = $meta . '_name'; $field_order = $meta . '_order'; break;
	}
		
	$sql = 'SELECT * FROM ' . $tbl . ' ORDER BY main ASC, ' . $field_order . ' ASC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $db->sql_numrows($result) )
	{
		$tmp = $db->sql_fetchrowset($result);
		
		foreach ( $tmp as $rows )
		{
			if ( !$rows['type'] )
			{
				$cat[$rows[$field_id]] = $rows;
			}
			else if ( $rows['type'] == 1 )
			{
				$lab[$rows['main']][$rows[$field_id]] = $rows;
			}
			else
			{
				$sub[$rows['main']][$rows[$field_id]] = $rows;
			}
		}
	}
	
	ksort($cat);
	ksort($lab);
	
#	debug($cat);
#	debug($sub);
#	debug($lab);

#	debug($default);
	
	$return = '<div id="close"><select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
	
	foreach ( $cat as $ckey => $crow )
	{
		$lng = isset($lang[$crow[$field_name]]) ? $lang[$crow[$field_name]] : $crow[$field_name];
		
		$return .= '<option value="' . $crow[$field_id] . '"' . (( $crow[$field_id] == $default ) ? ' selected="selected"' : '') . (( $data['type'] == 2 ) ? ' disabled="disabled"': '') . '>' . $lng . '</option>';
			
		if ( isset($lab[$ckey]) )
		{
			foreach ( $lab[$ckey] as $lkey => $lrow )
			{
				$lng = isset($lang[$lrow[$field_name]]) ? $lang[$lrow[$field_name]] : $lrow[$field_name];
				
				$return .= '<option value="' . $lrow[$field_id] . '"' . (( $lrow[$field_id] == $default ) ? ' selected="selected"' : '') . (( $data['type'] == 1 ) ? ' disabled="disabled"': '') . '>&nbsp; &nbsp;' . $lng . '</option>';
			}
		}
	}
	
	$return .= '</select></div><div id="ajax_content"></div>';
	
	return $return;
}

function select_forms($default, $meta, $name, $params)
{
	global $db, $lang;
	
#	$tmp_db = data(FORMS, false, 'main ASC, forum_order ASC', 1, false);
	
	$sql = "SELECT * FROM " . FORMS . " ORDER BY main ASC, forum_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$tmp_db = $db->sql_fetchrowset($result);
	
#	debug($tmp_db);
							
	if ( isset($tmp_db) )
	{
		foreach ( $tmp_db as $rows )
		{
			if ( !$rows['type'] )
			{
				$cats[$rows['forum_id']] = $rows;
			}
			else if ( $rows['type'] == 1 )
			{
				$forms[$rows['main']][$rows['forum_id']] = $rows;
			}
			else
			{
				$subs[$rows['main']][$rows['forum_id']] = $rows;
			}
		}
	}
	
	ksort($forms);
	ksort($subs);
	
#	debug($default, '$default');
	
	$type = ( $params == 'forms' ) ? true : false;
#	$typs = ( $default != 0 ) ? ( $default > 0 ? 1 : 2 ) : 0;
#	$ftyp = ( $data['forum_type'] != 0 ) ? ( $data['forum_type'] > 1 ? 2 : 1 ) : 0;
	
	$opt = ( $type ) ? '<div id="close">' : '';
	$opt .= '<select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
	$opt .= ( $type ) ? '' : '<option value="0" selected="selected">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_file']) . '</option>';
	
	foreach ( $cats as $ckey => $crow )
	{
		$mark = ( $default == $crow['forum_id'] ) ? ' selected="selected"' : '';
		$shut = ( $type ) ? ( $crow['type'] == '2' ) ? ' disabled="disabled"': '' : ' disabled="disabled"';
		$opt .= '<option value="' . $crow['forum_id'] . '"' . $mark . $shut . '>' . $crow['forum_name'] . ' :: ' . $crow['forum_id'] . '</option>';
			
		if ( isset($forms[$ckey]) )
		{
			foreach ( $forms[$ckey] as $fkey => $frow )
			{
			#	$default = $tdata > 0 ? $tdata : $tdata * -1;
				$mark = ( $default == $frow['forum_id'] ) ? ' selected="selected"' : '';
				$shut = ( $type ) ? ( $crow['type'] == '1' ) ? ' disabled="disabled"': '' : (@$data['forum_id'] == $frow['forum_id']) ? ' disabled="disabled"' : '';
				$opt .= '<option value="' . $frow['forum_id'] . '"' . $mark . $shut . '>&nbsp; &nbsp;' . $frow['forum_name'] . ' :: ' . $frow['forum_id'] . '</option>';
				
				if ( isset($subs[$fkey]) )
				{
					foreach ( $subs[$fkey] as $skey => $srow )
					{
						$shut = ( $type ) ? ' disabled="disabled"' : (@$data['forum_id'] == $srow['forum_id']) ? ' disabled="disabled"' : '';
						$opt .= '<option value="' . $srow['forum_id'] . '"' . @$shut . '>&nbsp; &nbsp;&nbsp; &nbsp;' . $srow['forum_name'] . '</option>';
					}
				}
			}
		}
	}
	
	$opt .= '</select>';
	$opt .= ( $type ) ?  '</div><div id="ajax_content"></div>' : '';
	
	return $opt;
}

/* tdata = default, tmeta = name, tname = name, params = typ */
function select_server($default, $meta, $name, $typ)
{
	global $lang;
	
	$type = ( $typ ) ? 0 : 1;
	
	$data_sql = data(SERVER_TYPE, "type_sort = $type", false, 1, false);
	
	$box = '<select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
	$box .= '<option value="">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_gametype']) . '</option>';
							
	foreach ( $data_sql as $row )
	{
		$mark = ( $default == $row['type_game'] ) ? ' selected="selected"' : '';
		$box .= '<option value="' . $row['type_game'] . '"' . $mark . '>' . sprintf($lang['sprintf_select_format'], $row['type_name']) . '</option>';
	}
	
	$box .= '</select>';
	
	return $box;
}

function select_path()
{
	global $root_path, $lang, $settings;
	
	/* Pfad angaben */	
	$paths = array (
		'cache/', 'files/',
		/* image files */
		$settings['path_games'], $settings['path_maps'], $settings['path_newscat'], $settings['path_ranks'],
		/* upload files */
		$settings['path_downloads'], $settings['path_gallery'], $settings['path_groups']['path'], $settings['path_matchs']['path'], $settings['path_network']['path'], $settings['path_team_flag']['path'], $settings['path_team_logo']['path'], $settings['path_users']['path'],
	);
	
	$select_path = '<select name="path" class="selectsmall">';
	
	foreach ( $paths as $path )
	{
		$status = is_writable($root_path . $path) ? $lang['writable_yes'] : $lang['writable_no'];
		$select_path .= "<option value=\"$path\">&raquo;&nbsp;$path&nbsp;&bull;&nbsp;$status&nbsp;</option>";
	}
	
	$select_path .= '</select>';
	
	return $select_path;	
}

function select_perms($default = '')
{
	global $lang;
	
	$perms = array (
		'777'	=> '777',
		'755'	=> '755',
		'644'	=> '644',
	);
	
	$select_perm = '';
	$select_perm .= '<select name="perms" class="selectsmall">';
	foreach ($perms as $perm)
	{
		$selected = ( $perm == $default ) ? ' selected="selected"' : '';
		$select_perm .= '<option value="' . $perm . '" ' . $selected . '>&raquo; ' . $perm . '&nbsp;</option>';
	}
	$select_perm .= '</select>';
	
	return $select_perm;	
}

function select_newscat($default, $main, $name, $path)
{
	global $db, $lang;
	
	$sql = "SELECT * FROM " . NEWS_CAT . " ORDER BY cat_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data = $db->sql_fetchrowset($result);
	
	$select = "<select name=\"{$main}[$name]\" id=\"{$main}_$name\" onkeyup=\"update_image(this.options[selectedIndex].value);\" onchange=\"update_image(this.options[selectedIndex].value);\">";
	$select .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_cat_image']) . "</option>";
	
	$current_image = '';
	
	for ( $i = 0; $i < count($data); $i++ )
	{
		if ( $data[$i]['cat_id'] == $default )
		{
			$current_image = $path . $data[$i]['cat_image'];
		}
		
		$marked = ( $default == $data[$i]['cat_id'] ) ? ' selected="selected"' : '';
		$select .= "<option value=\"" . $data[$i]['cat_image'] . "\"$marked>" . sprintf($lang['sprintf_select_format'], $data[$i]['cat_name']) . "</option>";
	}
	
	$select .= "</select><br /><img src=\"$current_image\" id=\"image\" alt=\"\" />";
	
	return $select;
}

function select_box_image($class, $table, $field, $default = '')
{
	#params $class		> css
	#params $table		> table
	#params $field		> field
	#params $default	> vorgabe
	
	global $db, $lang;
	
	switch ( $table )
	{
		case NEWS_CAT:	$lang_field = 'news' . $field;	break;
		default:		$lang_field = $field;			break;
	}
	
	$sql = "SELECT " . $field . "_id, " . $field . "_name, " . $field . "_image FROM $table ORDER BY " . $field . "_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data = $db->sql_fetchrowset($result);
	
	$field_id		= $field . '_id';
	$field_title	= $field . '_name';
	$field_image	= $field . '_image';
	$field_order	= $field . '_order';
	
	$select = '';
	
	$select .= "<select class=\"$class\" name=\"$field_image\" id=\"$field_image\" onchange=\"update_image(this.options[selectedIndex].value);\">";
	$select .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_' . $lang_field ]) . "</option>";
	
	foreach ( $data as $info => $value )
	{
		$marked = ( $value[$field_id] == $default ) ? ' selected="selected"' : '';
		$select .= "<option value=\"" . $value[$field_image] . "\"$marked>" . sprintf($lang['sprintf_select_format'], $value[$field_title]) . "</option>";
	}
	
	$select .= "</select>";
	
	return $select;
}


#select_team($tmp_data, $tmp_meta, $tmp_name, 'request')
function select_team($default, $meta, $name, $select, $css = false)
{
	global $db, $lang;
	
	/*
		@param	$css	= CSS Style
		@param	$msg	= msg
		@param	$chg	= select option
		@param	$name	= Name / ID
		@param	$mark	= ausgewählt
		@return	Rückgabe von der Auswahl
	*/
	
	$sql = "SELECT * FROM " . TEAMS . " ORDER BY team_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$tmp = $db->sql_fetchrowset($result);
	
	$return = '';
	
	if ( $tmp )	
	{
	#	$select = "<select name=\"{$meta}[$name]\" id=\"{$meta}_$name\">";
	
		$css = $css ? "class=\"$css\"" : '';
		
		$field_name = $meta ? "{$meta}[$name]" : $name;
		$field_id = $meta ? "{$meta}_$name" : $name;
		
		if ( is_array($select) )
		{
			list($type, $params) = array_values($select);
		}
		else
		{
			$type = $select;
			$params = '';
		}
		
	#	debug($select, 'select');
	#	debug($type, 'type');
	#	debug($params, 'option');
		
		if ( $type == 'submit' ) 
		{
			$return .= "<select $css name=\"$field_name\" id=\"$field_id\" onchange=\"if (this.options[this.selectedIndex].value != '') this.form.submit();\">";
		}
		else if ( $type == 'request' )
		{
			$return .= ( $params ) ? '<select ' . $css . ' name="' . $field_name . '" id="' . $field_id . '" onchange="setRequest(this.options[selectedIndex].value, \''. $meta . '\', \'' . $name . '\');">' : "<select $css name=\"$field_name\" id=\"$field_id\" onchange=\"setRequest(this.options[selectedIndex].value);\">";
		}
		else
		{
			$return .= "<select $css name=\"$field_name\" id=\"$field_id\">";
		}
		$return .= "<option value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_team']) . "</option>";
		
		foreach ( $tmp as $k => $v )
		{
			$team_id	= $v['team_id'];
			$team_name	= $v['team_name'];
			
			$selected = ( $team_id == $default ) ? ' selected="selected"' : '';
			$return .= "<option value=\"$team_id\"$selected>" . sprintf($lang['sprintf_select_format'], $team_name) . "</option>";
		}
		$return .= "</select>";
	
	}
	else
	{
		$return .= sprintf($lang['sprintf_select_format'], $lang['commen_noteams']);
	}
	
	return $return;
}

function select_level($default, $name, $level = '')
{
	global $lang;
	
	$s_select = '';
	
	$s_select .= "<select name=\"$name\" id=\"$name\" onchange=\"if (this.options[this.selectedIndex].value != '') this.form.submit();\">";
	$s_select .= "<option value=\"-1\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_userlevel']) . "</option>";
	
	$lang_level = $lang['switch_level'];
	
	if ( !empty($level) )
	{
		if ( is_array($level) )
		{
			foreach ( $lang_level as $key => $row )
			{
				if ( in_array($key, $level) )
				{
					$lng_lvl[$key] = $row;
				}
			}
		}
		else if ( $level == 'user_level' )
		{
			foreach ( $lang_level as $key => $row )
			{
				if ( in_array($key, array(GUEST, USER, TRIAL, MEMBER, MOD, ADMIN)) )
				{
					$lng_lvl[$key] = $row;
				}
			}
		}
	}
	else
	{
		$lng_lvl = $lang_level;
	}
	
	foreach ( $lng_lvl as $lvl => $name )
	{
		$selected = ( $lvl == $default ) ? ' selected="selected"' : '';
		$s_select .= "<option value=\"$lvl\"$selected>" . sprintf($lang['sprintf_select_format'], $name) . "</option>";
	}
	$s_select .= "</select>";
	
	return $s_select;
}

function select_box($table, $default = '', $switch = '')
{
	/*
	 *	@param string $type			enthält den Titel
	 *
	 *	$type = art
	 *	$default = startauswahl
	 *	$switch = team_join/team_fight
	 *
	 */
	
	global $db, $lang, $db_prefix;
	
	$start = false;
	
	switch ( $table )
	{
		case GAMES:
			$fields	= array('game_id', 'game_name', 'game_image');
			$where	= 'WHERE game_id != -1 ';
			$order	= 'ORDER BY game_order ASC';
			$selct	= true;
			$start	= '-1';
			break;
			
		case RANKS:
			$fields	= array('rank_id', 'rank_name');
			$where	= ' WHERE rank_type = ' . $switch;
			$order	= ' ORDER BY rank_order ASC';
			$selct	= false;
			break;
			
		case MATCH:
			$fields	= array('match_id', 'match_rival_name');
			$where	= ' WHERE match_date >= ' . time();
			$order	= ' ORDER BY match_id DESC';
			$selct	= false;
			break;

		case 'newscat':
			$table	= NEWS_CAT;
			$fields	= 'cat_id, cat_title, cat_image';
			$where	= '';
			$order	= ' ORDER BY cat_order ASC';
			$selct	= true;
			break;

		case TEAMS;
			$fields	= array('team_id', 'team_name');
			$where	= ( $switch != '' ) ? ( $switch == '2' ) ? ' WHERE team_join = 1' : ' WHERE team_fight = 1' : '';
			$order	= ' ORDER BY team_order';
			$selct	= false;
			break;

		case USERS:
		
			$fields	= array('user_id', 'user_name');
			$where	= " WHERE user_id <> " . ANONYMOUS;
			$where .= $switch ? " AND user_level >= $switch" : '';
			$order	= ' ORDER BY user_id DESC';
			$selct	= false;
			break;
			
		default: message(GENERAL_ERROR, 'SQL Error', ''); break;
	}
	
	$sql = "SELECT " . implode(', ', $fields) . " FROM $table $where $order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$tmp = $db->sql_fetchrowset($result);
	
	$id		= $fields[0];
	$name	= $fields[1];
	$last	= ( count($fields)> 2 ) ? array_pop($fields) : $fields[0];

	if ( $tmp )
	{
		if ( $selct )
		{
			$select = "<select name=\"$last\" id=\"$last\" onkeyup=\"update_image(this.options[selectedIndex].value);\" onchange=\"update_image(this.options[selectedIndex].value);\">";
		}
		else
		{
			$select = "<select name=\"$id\" id=\"$id\">";
		}
		
		$table = str_replace($db_prefix, '', $table);
		
	#	$select .= "<option value=\"" . isset($start) ? $start : '' . "\">" . sprintf($lang['sprintf_select_format'], $lang["msg_select_$type"]) . "</option>";
		$select .= "<option value=\"$start\">" . sprintf($lang['sprintf_select_format'], $lang["msg_select_$table"]) . "</option>";
		
		foreach ( $tmp as $value )
		{
			$selected = ( $value[$last] == $default ) ? ' selected="selected"' : '';
			$select .= "<option value=\"{$value[$last]}\"$selected>" . sprintf($lang['sprintf_select_format'], $value[$name]) . "</option>";
		}
		$select .= '</select>';
	}
	else
	{
		$select = '&nbsp;&raquo;&nbsp;' . sprintf($lang['msg_sprintf_noentry'], $lang[$type]) . '!';
	}

	return $select;
}

function select_games($default, $main, $name, $path)
{
	global $lang, $db, $settings;
	
	$sql = 'SELECT * FROM ' . GAMES . " ORDER BY game_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$current_image = '';
	
	$select = '<select name="' . sprintf('%s[%s]', $main, $name) . '" id="' . sprintf('%s_%s', $main, $name ) . '"' . ( $path ? 'onkeyup="update_image(this.options[selectedIndex].value);" onchange="update_image(this.options[selectedIndex].value);"' : '' ) . '>';
	$select .= '<option value="">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_' . $main]) . '</option>';
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		if ( $path && $row['game_image'] == $default )
		{
			$current_image = $path . $row['game_image'];
		}
		
		$game = ( $path ) ? $row['game_image'] : substr($row['game_image'], 0, strrpos($row['game_image'], '.'));
		$mark = ( $game == $default ) ? ' selected="selected"' : '';
		
		$select .= '<option value="' . $game . '"' .$mark . '>' . sprintf($lang['sprintf_select_format'], $row['game_name']) . '</option>';
	}
	
	$select .= '</select>';
	$select .= ( $path ) ? '&nbsp;<img class="icon" src="' . $current_image . '" id="image" alt="" height="15" />' : '';

	return $select;
}

function select_image($default, $main, $name, $path, $line)
{
	global $lang;
	
	$files	= scandir($path);
	$format	= array('png', 'jpg', 'jpeg', 'gif');
	
	$select = "<select name=\"{$main}[$name]\" id=\"{$main}_$name\" onkeyup=\"update_image(this.options[selectedIndex].value);\" onchange=\"update_image(this.options[selectedIndex].value);\">";
	
	$select .= '<option value="">' . sprintf($lang['sprintf_select_format'], $lang["msg_select_$main"]) . '</option>';
	
	foreach ( $files as $file )
	{
		if ( $file != '.' && $file != '..' && $file != 'index.htm' && $file != '.svn' && $file != 'spacer.gif' )
		{
			if ( in_array(substr($file, -3), $format) )
			{
				$clear_files[] = $file;
			}
		}
	}
	
	$current_image = '';
	
	foreach ( $clear_files as $file )
	{
		$filter = str_replace(substr($file, strrpos($file, '.')), "", $file);
		
		if ( $file == $default )
		{
			$current_image = $path . $file;
		}

		$marked = ( $file == $default ) ? ' selected="selected"' : '';
		
		$select .= "<option value=\"$file\"$marked>" . sprintf($lang['sprintf_select_format'], $filter) . "</option>";
	}
	$select .= "</select>";
	$select .= $line ? " <img src=\"$current_image\" id=\"image\" alt=\"\" height=\"15\" />" : "<br /><img src=\"$current_image\" id=\"image\" alt=\"\" />";
	
	return $select;
}

function select_box_files($class, $type, $path, $default = '')
{
	global $db, $lang, $images;
	
	$path_files = scandir($path);
	
	$select = '<select class="' . $class . '" name="' . $type . '" id="' . $type . '" onkeyup="update_image(this.options[selectedIndex].value);" onchange="update_image(this.options[selectedIndex].value);">';
	$select .= '<option value="">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_' . $type ]) . '</option>';
	
	$endung = array('png', 'jpg', 'jpeg', 'gif');
	
	foreach ( $path_files as $files )
	{
		if ( $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' && $files != 'spacer.gif' )
		{
			if ( in_array(substr($files, -3), $endung) )
			{
				$clear_files[] = $files;
			}
		}
	}
	
	foreach ( $clear_files as $files )
	{
		$filter = str_replace(substr($files, strrpos($files, '.')), "", $files);

		$marked = ( $files == $default ) ? ' selected="selected"' : '';
		$select .= '<option value="' . $files . '" ' . $marked . '>' . sprintf($lang['sprintf_select_format'], $filter) . '</option>';
	}
	$select .= '</select>';
	
	return $select;
}

/*
function select_box_game($class, $default)
{
	global $db, $lang;
	
	$sql = 'SELECT game_id, game_name, game_image FROM ' . GAMES . ' WHERE game_id != -1 ORDER BY game_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$func_select = '<select class="' . $class . '" name="game_image" onchange="update_image(this.options[selectedIndex].value);">';
	$func_select .= '<option value="-1">&raquo; ' . $lang['msg_select_game'] . '&nbsp;</option>';
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$selected = ( $row['game_id'] == $default ) ? 'selected="selected"' : '';
		$func_select .= '<option value="' . $row['game_image'] . '" ' . $selected . ' >&raquo; ' . $row['game_name'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}
*/

function select_rank($default, $type)
{
	global $db, $lang;
	
	$order = ( $type == RANK_FORUM ) ? 'rank_order' : 'rank_id';
	
	$sql = 'SELECT rank_id, rank_name, rank_order FROM ' . RANKS . " WHERE rank_type = $type ORDER BY $order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$func_select = '<select class="' . $class . '" name="rank_id">';
	$func_select .= '<option value="0">----------</option>';
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['rank_id'] == $default ) ? ' selected="selected"' : '';
		$func_select .= '<option value="' . $row['rank_id'] . '"' . $selected . '>' . $row['rank_name'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}

/*
function select_newscategory($default)
{
	global $db, $lang;
		
	$sql = 'SELECT * FROM ' . NEWS_CAT . " ORDER BY cat_order";
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$func_select = '<select name="news_cat" class="post" onchange="update_image(this.options[selectedIndex].value);">';
	$func_select .= '<option value="0">' . $lang['cat_select'] . '</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['cat_id'] == $default ) ? ' selected="selected"' : '';
		$func_select .= '<option value="' . $row['cat_image'] . '"' . $selected . '>&raquo;&nbsp;' . $row['cat_title'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}
*/

function select_lang_box($var, $name, $default, $class)
{
	global $lang;
		
	$select_switch = "<select class=\"$class\" name=\"$name\" id=\"$name\">";
	foreach ( $lang[$var] as $key_s => $value_s )
	{
		$selected = ( $key_s == $default ) ? ' selected="selected"' : '';
		
		if ( $name != 'match_league' )
		{
			$select_switch .= '<option value="' . $key_s . '" ' . $selected . '>&raquo; ' . $value_s . '&nbsp;</option>';
		}
		else
		{
			$select_switch .= '<option onClick="this.form.match_league_url.value=[\'' . $value_s['league_link'] . '\']" value="' . $value_s['league_id'] . '" ' . $selected . '>&raquo; ' . $value_s['league_name'] . '&nbsp;</option>';
		}
	}
	$select_switch .= '</select>';
	
	return $select_switch;
}

//
//	Match Select
//
//	default:	id
//	class:		css class
//
function select_match($default, $main, $name)
{
	global $db, $lang, $userdata;
	
	$sql = 'SELECT match_id, match_rival_name, match_rival_tag, match_date FROM ' . MATCH . '  WHERE match_date >= ' . time() . ' ORDER BY match_date ASC';
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$select = "<select name=\"{$main}[$name]\" id=\"{$main}_$name\">";
	$select .= '<option value="">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_match']) . '</option>';
	
	while ( $row = $db->sql_fetchrow($result) )
	{
	#	debug($row);
		$selected = ( $row['match_id'] == $default ) ? ' selected="selected"' : '';
		$select .= "<option value='" . $row['match_id'] . "'$selected>" . sprintf($lang['sprintf_select_format'], sprintf('%s :: %s :: %s', create_date($userdata['user_dateformat'], $row['match_date'], $userdata['user_timezone']), $row['match_rival_name'], $row['match_rival_tag'])) . "</option>";
	}
	$select .= '</select>';

	return $select;
}

function _select_match($default, $type, $class)
{
	global $db, $lang;
	
	$where = ($type == '') ? ' WHERE match_date > ' . time() : '';
	
	$sql = 'SELECT match_id, match_rival_name, match_rival_tag FROM ' . MATCH . $where . ' ORDER BY match_date';
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$func_select = '<select class="' . $class . '" name="match_id">';
	$func_select .= '<option value="0">&raquo;&nbsp;' . $lang['msg_select_match'] . '&nbsp;</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['match_id'] == $default ) ? 'selected="selected"' : '';
		$func_select .= '<option value="' . $row['match_id'] . '" ' . $selected . ' >&raquo; ' . $row['match_rival_name'] . ' :: ' . $row['match_rival_tag'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}

//
// Disable modes
//
function page_mode_select($default, $select_name = 'page_disable_mode')
{
	global $lang;

	if (!is_array($default))
	{
		$default = explode(',', $default);
	}

	$disable_select = '<select class="select" name="' . $select_name . '[]" id="' . $select_name . '" multiple="multiple" size="5">';
	foreach ($lang['page_disable_mode_opt'] as $const => $name)
	{
		$selected = (in_array($const, $default)) ? ' selected="selected"' : '';
		$disable_select .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
	}
	$disable_select .= '</select>';

	return $disable_select;
}

/*
 * @param	$class = CSS Style
 * @param	$table = Datenbanktabelle
 * @param	$field = ID Feld
 * @param	$default = Vorgabe
 * @return	Rückgabe von der Auswahl
 */
function select_cat($class, $table, $field, $default = '')
{
	global $db, $lang;
	
	$sql = "SELECT * FROM $table";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$select = '<select class="' . $class . '" name="' . $field . '_id" id="' . $field . '_id" >';
#	$select .= '<option value="0">&raquo;&nbsp;' . $lang['msg_select_' . strtolower($table)] . '&nbsp;</option>';
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$mark = ( $row[$field . '_id'] == $default ) ? 'selected="selected"' : '';
		$select .= '<option value="' . $row[$field . '_id'] . '" ' . $mark . ' >&raquo;&nbsp;' . $row[$field . '_name'] . '&nbsp;</option>';
	}
	$select .= '</select>';

	return $select;
}

function select_order_cat($class, $table, $id, $order)
{
	global $db, $lang;
	
	switch ( $table )
	{
		case MAPS:
		
			$fields = 'map_id, map_name, map_file, map_order';
			$table1 = MAPS_CAT;
			$order1 = 'cat_order';
			$table2 = MAPS;
			$order2 = 'map_order';
			
			break;
			
		default:
		
			message(GENERAL_ERROR, 'Error', '');
			
			break;
	}
	
	$field	= explode(', ', $fields);
	$fielda	= $field[0];
	$fieldb	= $field[1];
	$fieldc	= $field[2];
	$fieldd = $field[3];
	
	$sql = "SELECT * FROM $table1 ORDER BY $order1 ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cats = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	$select = '';
	
	if ( $cats )
	{
		$sql = "SELECT * FROM $table2 ORDER BY $order2 ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$maps = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		$select	.= "<select class=\"$class\" name=\"fieldd_new\" id=\"\">";
		$select .= "<option selected=\"selected\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
		
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$map = '';
			
			for ( $j = 0; $j < count($maps); $j++ )
			{
				if ( $cats[$i]['cat_id'] == $maps[$j]['cat_id'] )
				{
					$mark = ( $fieldd == $order && $fielda == $id ) ? ' disabled' : '';
					$map .= ( $fieldd == 10 ) ? "<option value=\"5\"$mark>" . sprintf($lang['sprintf_select_before'], $fieldb . " :: " . $fieldc) . "</option>" : '';
					$map .= "<option value=\"" . ($fieldc + 5) . "\"$mark>" . sprintf($lang['sprintf_select_order'], $fieldb . " :: " . $fieldc) . "</option>";
				}
			}
			
			if ( $map != '' )
			{
				$select .= '<optgroup label="' . $cats[$i]['cat_name'] . '">';
				$select .= $map;
				$select .= '</optgroup>';
			}
		}
		$select .= '</select>';
	}
	
	return $select;
}

function select_order($class, $table, $cat, $default = '')
{
	/*
		require:	acp_games
	*/
	
	global $db, $lang;
	
	$select = '';
	
	switch ( $table )
	{
		case SERVER:
			$fields	= 'server_name, server_order';
			$where	= '';
			$order	= 'ORDER BY server_order ASC';
			break;
			
		case NEWS_CAT:
			$fields	= 'cat_title, cat_order';
			$where	= '';
			$order	= 'ORDER BY cat_order ASC';
			break;
			
		case MAPS:
			
			$fields	= 'map_name, map_order';
			$where	= 'WHERE cat_id = ' . $cat;
			$order	= 'ORDER BY map_order ASC';
			
			break;
			
		case MAPS_CAT:
			
			$fields	= 'cat_name, cat_order';
			$where	= '';
			$order	= 'ORDER BY cat_order ASC';
			
			break;
		
		case GAMES:
			
			$fields	= 'game_name, game_order';
			$where	= 'WHERE game_id != -1 ';
			$order	= 'ORDER BY game_order ASC';
			
			break;
			
		case TEAMS:
			
			$fields	= 'team_name, team_order';
			$where	= '';
			$order	= 'ORDER BY team_order ASC';
			
			break;
			
		case GROUPS:
			
			$fields	= 'group_name, group_order';
			$where	= 'WHERE group_single_user = 0 ';
			$order	= 'ORDER BY group_order ASC';
			
			break;
		
		default:
		
			message(GENERAL_ERROR, 'SQL order error', '');
			
			break;
	}
	
	$field	= explode(', ', $fields);
	$fielda	= $field[0];
	$fieldb	= $field[1];
	
	$sql = "SELECT $fields FROM $table $where $order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	$marked = ( $default == $data[0][$fieldb]-5 ) ? ' selected="selected"' : '';
	
	$select .= "<select class=\"$class\" name=\"" . $fieldb . "_new\" id=\"$fieldb\">";
	$select .= "<option value=\"$default\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
	$select .= "<option value=\"" . ($data[0][$fieldb] - 5) . "\"$marked>" . sprintf($lang['sprintf_select_before'], $data[0][$fielda]) . "</option>";
	
	for ( $i = 0; $i < count($data); $i++ )
	{
		$marked = ( $default == $data[$i][$fieldb]+5 ) ? ' selected="selected"' : '';
		$disable = ( $data[$i][$fieldb] == $default ) ? ' disabled' : '';
		$select .= "<option value=\"" . ($data[$i][$fieldb] + 5) . "\"$marked$disable>" . sprintf($lang['sprintf_select_order'], $data[$i][$fielda]) . "</option>";
	}
	
	$select .= "</select>";
	
	return $select;
	
	/*
		<select>
			<optgroup label="Swedish Cars">
				<option value="volvo">Volvo</option>
				<option value="saab">Saab</option>
			</optgroup>
			<optgroup label="German Cars">
				<option value="mercedes">Mercedes</option>
				<option value="audi">Audi</option>
			</optgroup>
		</select>
	*/
}

function select_add_lang($class, $field, $field_lang, $type, $data)
{
	global $db, $lang;
	
	#	$s_level = '';
	#	$s_level .= '<select class="select" name="event_level" id="event_level">';
	#	$s_level .= '<option value="-1">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_userlevel']) . '</option>';
	#	foreach ( $lang['switch_level'] as $const => $name )
	#	{
	#		$selected = ( $data['event_level'] == $const ) ? ' selected="selected"' : '';			
	#		$s_level .= "<option value=\"$const\" $selected>&raquo;&nbsp;$name&nbsp;</option>";
	#	}
	#	$s_level .= '</select>';
	
	$select = "";
	$select .= "<select class=\"$class\" name=\"$field\" id=\"$field\">";
	$select .= "<option value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_' . $type]) . "</option>";
	
	foreach ( $lang[$field_lang] as $key_name => $value_name )
	{
		$mark = ( $data == $key_name ) ? " selected=\"selected\"" : "";			
		$select .= "<option value=\"$key_name\" $mark>" . sprintf($lang['sprintf_select_format'], $value_name) . "</option>";
	}
	$select .= '</select>';
	
	return $select;
}

function select_maps($tag = '')
{
	global $db, $lang;
	
	$maps = data(MAPS, '', true, 0, INT);
	$cats = data(MAPS_CAT, '', true, 0, INT);
	
	$select = "";
	$select .= "<select class=\"selectsmall\" name=\"map_name[]\" id=\"map_name[]\">";
	$select .= "<option value=\"-1\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_maps']) . "</option>";
	
	for ( $i = 0; $i < count($cats); $i++ )
	{
		if ( $cats[$i]['cat_tag'] == $tag )
		{
			$cat_id = $cats[$i]['cat_id'];
			
			$select .= "<optgroup label=\"" . sprintf($lang['sprintf_select_format'], $cats[$i]['cat_name'] . ' - ' . $cats[$i]['cat_tag']) . "\">";
			
			for ( $j = 0; $j < count($maps); $j++ )
			{
				$cat_map = $maps[$j]['cat_id'];
				
				if ( $cat_id == $cat_map )
				{
					$values = explode ('.', $maps[$j]['map_file']);
					$select .= "<option value=\"" . $values[0] . "\">" . sprintf($lang['sprintf_select_format2'], $maps[$j]['map_name'] . $values[0]) . "</option>";
				}
			}
			$select .= "</optgroup>";
		}
	}
	
	$select .= '</select>';
	
	return $select;
}

//
//	Date Select
//
//	default:	day/month/year/hour/min
//	value:		select Wert
//
function select_date($class, $default, $var, $value, $ending = '')
{
	#	$class		> css
	#	$default	>
	#	$var		>
	#	$value		>
	#	$ending		>
	
	global $lang;
	
	$time = time();
	
	switch ( $default )
	{
		case 'day':
		
			if ( $ending <= ( $time - 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				
				for ( $i = 1; $i < 32; $i++ )
				{
					$i = ( $i < 10 ) ? '0' . $i : $i;
					
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>" . sprintf($lang['sprintf_select_format'], $i) . "</option>";
				}
				
				$select .= "</select>";
			}
			
			break;
		
		case 'month':
		
			if ( $ending <= ( $time - 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				
				for ( $i = 1; $i < 13; $i++ )
				{
					$i = ( $i < 10 ) ? '0' . $i : $i;
					
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>" . sprintf($lang['sprintf_select_format'], $i) . "</option>";
				}
				
				$select .= '</select>';
			}
			
			break;
		
		case 'monthn':
		
			$monate = array(
				'01'	=> $lang['datetime']['month_01'],
				'02'	=> $lang['datetime']['month_02'],
				'03'	=> $lang['datetime']['month_03'],
				'04'	=> $lang['datetime']['month_04'],
				'05'	=> $lang['datetime']['month_05'],
				'06'	=> $lang['datetime']['month_06'],
				'07'	=> $lang['datetime']['month_07'],
				'08'	=> $lang['datetime']['month_08'],
				'09'	=> $lang['datetime']['month_09'],
				'10'	=> $lang['datetime']['month_10'],
				'11'	=> $lang['datetime']['month_11'],
				'12'	=> $lang['datetime']['month_12'],
			);
			
			if ( $ending <= ( $time - 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				
				for ( $i = 1; $i < 13; $i++ )
				{
					$i = ( $i < 10 ) ? '0' . $i : $i;
					
					$mark = ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>" . sprintf($lang['sprintf_select_format'], $i) . "</option>";
				}
				
				$select .= '</select>';
			}
			
			break;
		
		case 'monthm':
		
			$monate = array(
				'01'	=> $lang['datetime']['month_01'],
				'02'	=> $lang['datetime']['month_02'],
				'03'	=> $lang['datetime']['month_03'],
				'04'	=> $lang['datetime']['month_04'],
				'05'	=> $lang['datetime']['month_05'],
				'06'	=> $lang['datetime']['month_06'],
				'07'	=> $lang['datetime']['month_07'],
				'08'	=> $lang['datetime']['month_08'],
				'09'	=> $lang['datetime']['month_09'],
				'10'	=> $lang['datetime']['month_10'],
				'11'	=> $lang['datetime']['month_11'],
				'12'	=> $lang['datetime']['month_12'],
			);
			
			if ( !is_array($value) )
			{
				$value = explode(',', $value);
			}
		
			$select = "<select class=\"$class\" name=\"" . $var . "[]\" multiple=\"multiple\" rows=\"12\" size=\"12\">";
			
			foreach ( $monate as $const => $name )
			{
				$mark = ( in_array($const, $value) ) ? 'selected="selected"' : '';
			#	$select .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
			#	$select .= "<option value=\"$const\" $mark>&raquo;&nbsp;$name&nbsp;</option>";
				$select .= "<option value=\"$const\" $mark>" . sprintf($lang['sprintf_select_format'], $name) . "</option>";
			}
			
			$select .= '</select>';
			
			break;
		
		case 'year':
		
			if ( $ending <= ( $time - 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				
				for ( $i = $value; $i < $value + 2; $i++ )
				{
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>" . sprintf($lang['sprintf_select_format'], $i) . "</option>";
				}
				
				$select .= '</select>';
			}
		
			break;
		
		case 'hour':
		
			if ( $ending <= ( $time - 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				
				for ( $i = 0; $i < 24; $i++ )
				{
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>" . sprintf($lang['sprintf_select_format'], $i) . "</option>";
				}
				
				$select .= '</select>';
			}
			
			break;
		
		case 'min':
		
			if ( $ending <= ( $time - 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				
				for ( $i = '00'; $i < 60; $i = $i + 15 )
				{
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>" . sprintf($lang['sprintf_select_format'], $i) . "</option>";
				}
				
				$select .= '</select>';
			}
			
			break;
		
		case 'duration':
		
			$select = "<select class=\"$class\" name=\"$var\" id=\"duration\">";
			
			for ( $i='00'; $i < 260; $i = $i + 30 )
			{
				$mark	= ( $i == $value ) ? 'selected="selected"' : '';
				$select .= "<option value=\"$i\" $mark>" . sprintf($lang['sprintf_select_format'], $i) . "</option>";
			}
			
			$select .= '</select>';
			
			break;
	}
	
	return $select;
}

function match_round($css, $round, $default)
{
	global $lang;
	
	$select = "<select class=\"$css\" name=\"map_round[$round]\">";
				
	for ( $i = 1; $i < 11; $i++ )
	{
	#	$i = ( $i < 10 ) ? '0' . $i : $i;
		
		$mark	= ( $i == $default ) ? 'selected="selected"' : '';
		$select .= "<option value=\"$i\" $mark>" . sprintf($lang['sprintf_select_format'], sprintf($lang['sprintf_round'], $i)) . "</option>";
	}
	
	$select .= "</select>";
	
	return $select;
}

/*
 *	Gibt eine Liste wieder, wonach sortiert werden kann
 *
 *	@param: string	$mode		example: games
 *	@param: string	$option		example: game_id != -1
 *	@param: string	$css		example: select
 *	@param:	int		$default	example: 10
 *
 */
 
function simple_order($table, $where, $current_order, $main_array, $field)
{
	global $db, $db_prefix, $lang;
	
	$filter = array(NEWS_CAT, MENU_CAT, DOWNLOADS_CAT, PROFILE_CAT);

	$cats = '';
	
	$db_field = rtrim(str_replace($db_prefix, '', $table), 's');
	
	if ( in_array($table, $filter) )
	{
		$db_field = 'cat';
	}
	
	if ( $where != '' )
	{
		$where = "WHERE $where";
	}
	
#	switch ( $mode )
#	{
#		case FIELDS:	$field = 'field';	break;
#	}
	
	$sql = "SELECT * FROM $table $where ORDER BY {$db_field}_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$entries = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	if ( $entries )	
	{
		$name	= isset($main_array) ? "{$main_array}[{$field}_order_new]" : "{$field}_order_new";
		$id		= isset($main_array) ? "{$main_array}_{$field}_order" : "{$field}_order";
		
		$s_select .= "<div id=\"close\"><select name=\"$name\" id=\"$id\">";
		$s_select .= "<option selected=\"selected\" value=\"$current_order\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
		
		$cnt = count($entries);
		$max = '';
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$name	= $entries[$i]["{$db_field}_name"];
			$order	= $entries[$i]["{$db_field}_order"];
			
			$disabled = ( $order == $current_order ) ? 'disabled="disabled"' : '';
				
			$s_select .= ( $order == 10 ) ? "<option value=\"5\" $disabled>" . sprintf($lang['sprintf_select_before'], $name) . "</option>" : '';
			$s_select .= "<option value=\"" . ( $order + 5 ) . "\" $disabled>" . sprintf($lang['sprintf_select_order'], $name) . "</option>";
		}
		
		$s_select .= "</select></div><div id=\"ajax_content\"></div>";
	}
	else
	{
		$s_select = '<div id="close">' . $lang['no_entry'] . '</div><div id="ajax_content"></div>';
	}

/*
function simple_order($mode, $option, $css, $default)
{
	//require: acp_game, acp_group, acp_map, acp_navi, acp_network, acp_newscat, acp_profile, acp_rank

	global $db, $lang;

	$cats = '';

	$filter = array(NEWS_CAT, MENU_CAT, DOWNLOADS_CAT, MAPS_CAT, PROFILE_CAT);

	if ( in_array($mode, $filter) )
	{
		$field = 'cat';
				
		$sql = "SELECT * FROM $mode ORDER BY cat_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}

		
	}
	
	if ( isset($option) )
	{
		$where = "WHERE $option";
	}
	else
	{
		$where = '';
	}
	
	switch ( $mode )
	{
		case TEAMS:		$field = 'team';	break;
		case MENU:		$field = 'file';	break;
		case FIELDS:	$field = 'field';	break;
		case SERVER:	$field = 'server';	break;
		case GAMES:		$field = 'game';	break;
		case GROUPS:	$field = 'group';	break;
		case GALLERY:	$field = 'gallery';	break;

		case MAPS:
		
			$field = 'map';
		
			$sql = "SELECT cat_id AS cat_type, cat_name FROM " . MAPS_CAT . " WHERE cat_id = $option ORDER BY cat_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$cats = $db->sql_fetchrowset($result);
			
			$sql = "SELECT map_name, map_order, cat_id AS map_type FROM " . MAPS . " WHERE cat_id = $option ORDER BY cat_id, map_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
		
		case NAVI:
		
			$field = 'navi';
		
			$cats = array(
				'0' => array('cat_type' => NAVI_MAIN,	'cat_name' => $lang['main']),
				'1' => array('cat_type' => NAVI_CLAN,	'cat_name' => $lang['clan']),
				'2' => array('cat_type' => NAVI_COM,	'cat_name' => $lang['com']),
				'3' => array('cat_type' => NAVI_MISC,	'cat_name' => $lang['misc']),
				'4' => array('cat_type' => NAVI_USER,	'cat_name' => $lang['user']),
			);
			
			$sql = "SELECT * FROM " . NAVI . " WHERE navi_type = $option ORDER BY navi_type, navi_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case NETWORK:
		
			$field = 'network';
			
			$cats = array(
				'0' => array('cat_type' => NETWORK_LINK,	'cat_name' => $lang['link']),
				'1' => array('cat_type' => NETWORK_PARTNER,	'cat_name' => $lang['partner']),
				'2' => array('cat_type' => NETWORK_SPONSOR,	'cat_name' => $lang['sponsor']),
			);
			
			$sql = "SELECT * FROM " . NETWORK . " WHERE network_type = $option ORDER BY network_type, network_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
			
		case RANKS:
		
			$field = 'rank';	
				
			$cats = array(
				'0' => array('cat_type' => RANK_PAGE,	'cat_name' => $lang['page']),
				'1' => array('cat_type' => RANK_FORUM,	'cat_name' => $lang['forum']),
				'2' => array('cat_type' => RANK_TEAM,	'cat_name' => $lang['team']),
			);
			
			$sql = "SELECT * FROM " . RANKS . " WHERE rank_type = $option ORDER BY rank_type, rank_order ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			break;
	}
	
	$sql = "SELECT * FROM $mode $where ORDER BY {$field}_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$entries = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	if ( $entries )	
	{
		$s_select .= "<div id=\"close\"><select name=\"{$css}[{$field}_order_new]\" id=\"{$css}_{$field}_order\">";
		$s_select .= "<option selected=\"selected\" value=\"$default\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
		
		if ( $cats )
		{
			for ( $i = 0; $i < count($cats); $i++ )
			{
				$entry = '';
				
				$cat_name = $cats[$i]['cat_name'];
				$cat_type = $cats[$i]['cat_type'];
				
				for ( $j = 0; $j < count($entries); $j++ )
				{
					$name = $entries[$j][$field . '_name'];
					$type = $entries[$j][$field . '_type'];
					$order = $entries[$j][$field . '_order'];
					
					if ( $cat_type == $type )
					{
						$disabled = ( $entries[$j][$field . '_order'] == $default ) ? ' disabled="disabled"' : '';
						
						$entry .= ( $order == 10 ) ? "<option value=\"5\"$disabled>" . sprintf($lang['sprintf_select_before'], $name) . "</option>" : '';
						$entry .= "<option value=\"" . ( $order + 5 ) . "\"$disabled>" . sprintf($lang['sprintf_select_order'], $name) . "</option>";
					}
				}
				
				$s_select .= ( $entry != '' ) ? "<optgroup label=\"$cat_name\">$entry</optgroup>" : '';
			}
		}
		else
		{
			for ( $j = 0; $j < count($entries); $j++ )
			{
				$name = $entries[$j][$field . '_name'];
				$order = $entries[$j][$field . '_order'];
				
				$disabled = ( $entries[$j][$field . '_order'] == $default ) ? ' disabled="disabled"' : '';
					
				$s_select .= ( $order == 10 ) ? "<option value=\"5\"$disabled>" . sprintf($lang['sprintf_select_before'], $name) . "</option>" : '';
				$s_select .= "<option value=\"" . ( $order + 5 ) . "\"$disabled>" . sprintf($lang['sprintf_select_order'], $name) . "</option>";
			}
		}
		
		$s_select .= "</select></div><div id=\"ajax_content\"></div>";
	}
	else
	{
		$s_select = $lang['no_entry'];
	}
	*/
	return $s_select;
}

function select_map($team, $num, $default = '')
{
	global $db, $lang;
	
	$sql = "SELECT mc.*
				FROM " . MAPS_CAT . " mc
					LEFT JOIN " . TEAMS . " t ON t.team_id = $team
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
			WHERE mc.cat_tag = g.game_tag";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$cats = $db->sql_fetchrow($result);
	
	$s_select = '';
		
	if ( $cats )
	{
		$cat_id		= $cats['cat_id'];
		$cat_name	= $cats['cat_name'];
		
		$sql = "SELECT * FROM " . MAPS . " WHERE cat_id = " . $cats['cat_id'] . " ORDER BY map_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$maps = $db->sql_fetchrowset($result);
		
		if ( $maps )
		{
			$s_select .= "<select class=\"select\" name=\"map_name[$num]\" id=\"map_name\">";
			$s_select .= "<optgroup label=\"" . sprintf($lang['sprintf_select_format'], $lang['msg_select_map']) . "\" >";
			
			$s_maps = '';
				
			for ( $j = 0; $j < count($maps); $j++ )
			{
				$map_id		= $maps[$j]['map_id'];
				$map_cat	= $maps[$j]['cat_id'];
				$map_name	= $maps[$j]['map_name'];
				
				$selected	= ( $map_id == $default ) ? 'selected="selected"' : "";
	
				$s_maps .= ( $cat_id == $map_cat ) ? "<option value=\"$map_id\"$selected>" . sprintf($lang['sprintf_select_format'], $map_name) . "</option>" : '';
			}
			
			$s_select .= ( $s_maps != '' ) ? "<optgroup label=\"$cat_name\">$s_maps</optgroup>" : '';
			$s_select .= "</optgroup></select>";
		}
		else
		{
			$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
		}
	}
	else
	{
		$s_select = sprintf($lang['sprintf_select_format'], $lang['msg_empty_maps']);
	}
	
	return $s_select;
}

function match_types($default, $meta, $name)
{
	global $lang, $settings;
	
#	debug($default);
#	debug($meta);
#	debug($name);
	
	$data = $settings[$name];
	$mcut = str_replace('match_', '', $name);
	$show = array_shift($data);
	
	$select = '';
	
	foreach ( $data as $key => $array )
	{
		$sort[$key] = $array['order'];
	}
	array_multisort($sort, SORT_ASC, SORT_NUMERIC, $data);
	
#	debug($data);
#	debug($show);

	if ( $show )
	{
	#	$select .= "<select name=\"$name\" id=\"$name\">";
		$select = "<select name=\"{$meta}[$name]\" id=\"{$meta}_$name\">";
		$select .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang["msg_select_{$mcut}"]) . "</option>";
		
		foreach ( $data as $key => $value )
		{
			$name = isset($lang[$value['value']]) ? $lang[$value['value']] : $value['value'];
			$mark = ( $default != '' ) ? ( $default == $key ) ? ' selected="selected"' : '' : ( $value['default'] == 1 ) ? ' selected="selected"' : '';
			
			$select .= "<option value=\"$key\"$mark>" . sprintf($lang['sprintf_select_format'], $name) . "</option>";
		}
		$select .= '</select>';
		
		return $select;
	}
	else
	{
		$radio = '';
		
		foreach ( $data as $key => $value )
		{
			$checked = ( $default != '' ) ? ( $default == $key ) ? ' checked="checked"' : '' : ( $value['default'] == 1 ) ? ' checked="checked"' : '';
			
			$radio .= '<label><input type="radio" name="' . sprintf('%s[%s]', $meta, $name) . '" value="' . $key . '"' . $checked . ' />&nbsp;' . $value['value'] . '</label>';
			$radio .= '<br />';
		}
		
		return $radio;
	}
}

#	select_auth($typ, $tmp_vars)
#	$opt .= select_auth($tmp_data, $tmp_meta, $tmp_name, $type); break;
function select_auth($default, $meta, $name, $type)
{
	global $root_path, $lang, $template, $settings;
#	global $gallery_auth_levels, $gallery_auth_constants;
#	global $gallery_auth_none_levels, $gallery_auth_none_constants;
#	global $gallery_auth_upload_levels, $gallery_auth_upload_constants;
#	debug($default, 'default');

	global $simple_auth, $fields_auth;
	
	for ( $j = 0; $j < count($lang['simple_auth_types']); $j++ )
	{
		foreach ( $simple_auth[$j] as $key_s => $value_s )
		{
			foreach ( $fields_auth as $key_f => $value_f )
			{
				if ( $key_s == $key_f )
				{
					$new[$j][$value_f] = $value_s;
				}
			}
		}
	
		foreach ( $new[$j] as $key_n => $value_n )
		{
			$set_right[$j][] = "set_right('$key_n', '$value_n')";
			
		}
		
		$set_right[$j] = implode('; ', $set_right[$j]);
		
		$auth[] = '<a style="cursor:pointer" onclick="' . $set_right[$j] . '; set_right(\'simpleauth\', \'' . $j . '\')">' . $lang['simple_auth_types'][$j] . '</a>';
	}
	
	$auth = implode('<br />', $auth);
	
#	debug($auth);
	
#	debug($simple_auth, "simple_auth");
	
	$default = @unserialize($default);
	
	$tmp = is_array($default) ? $default : unserialize($default);
	
	if ( empty($tmp) )
	{
		$default = array(																		#		0		1		2		3		  4		  5			6		  7
			'auth_view' =>		( $type == GROUPS ) ? $settings['gallery']['auth_view'] : 1,	#	'guest', 'user', 'trial', 'member', 'mod', 'admin', 'uploader'
			'auth_edit' =>		( $type == GROUPS ) ? $settings['gallery']['auth_view'] : 6,	#										'mod', 'admin', 'uploader'
			'auth_delete' =>	( $type == GROUPS ) ? $settings['gallery']['auth_view'] : 6,	#										'mod', 'admin', 'uploader'
			'auth_rate' =>		( $type == GROUPS ) ? $settings['gallery']['auth_view'] : 1,	#	'guest', 'user', 'trial', 'member', 'mod', 'admin', 'uploader'
			'auth_comment' =>	( $type == GROUPS ) ? $settings['gallery']['auth_view'] : 1,	#	'guest', 'user', 'trial', 'member', 'mod', 'admin', 'uploader'
			'auth_upload' =>	( $type == GROUPS ) ? $settings['gallery']['auth_view'] : 3,	#			 'user', 'trial', 'member', 'mod', 'admin'
			'auth_approve' =>	( $type == GROUPS ) ? $settings['gallery']['auth_view'] : 4,	#										'mod', 'admin',			  'disabled'
			'auth_report' =>	( $type == GROUPS ) ? $settings['gallery']['auth_view'] : 1,	#	'guest', 'user', 'trial', 'member', 'mod', 'admin', 'uploader'
		);
	}
	
	$auth_all		= array('auth_view', 'auth_rate', 'auth_comment', 'auth_report');
	$auth_edit		= array('auth_edit', 'auth_delete');
	$auth_up		= array('auth_upload');
	$auth_app		= array('auth_approve');
	
	$auth_all_lvl	= array('guest', 'user', 'trial', 'member', 'mod', 'admin', 'uploader');
	$auth_edit_lvl	= array('mod', 'admin', 'uploader');
	$auth_up_lvl	= array('user', 'trial', 'member', 'mod', 'admin');
	$auth_app_lvl	= array('mod', 'admin', 'disabled');
	
	$auth_all_con	= array(AUTH_GUEST, AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_MOD, AUTH_ADMIN, AUTH_UPLOADER);
	$auth_edit_con	= array(AUTH_MOD, AUTH_ADMIN, AUTH_UPLOADER, AUTH_DISABLED);
	$auth_up_con	= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_MOD, AUTH_ADMIN);
	$auth_app_con	= array(AUTH_MOD, AUTH_ADMIN, AUTH_DISABLED);
	
	$opt = '';
	
	foreach ( $default as $dkey => $drow )
	{
		$opt .= '<label><select name="' . sprintf('%s[%s][%s]', $meta, $name, $dkey) . '" id="' . $dkey . '">';
		$opt .= '<option value="-1">' . sprintf($lang['sprintf_select_format'], $lang['msg_select_item']) . '</option>';
		
		if ( in_array($dkey, $auth_all) )
		{
			foreach ( $auth_all_lvl as $levelskey => $levels )
			{
				$selected = ( $default[$dkey] == $auth_all_con[$levelskey] ) ? ' selected="selected"' : '';
				$opt .= '<option value="' . $auth_all_con[$levelskey] . '"' . $selected . '>' . sprintf($lang['sprintf_select_format'], $lang["common_auth_$levels"]) . '</option>';
			}
		}
		else if ( in_array($dkey, $auth_edit) )
		{
			foreach ( $auth_edit_lvl as $levelskey => $levels )
			{
				$selected = ( $default[$dkey] == $auth_edit_con[$levelskey] ) ? ' selected="selected"' : '';
				$opt .= '<option value="' . $auth_edit_con[$levelskey] . '"' . $selected . '>' . sprintf($lang['sprintf_select_format'], $lang["common_auth_$levels"]) . '</option>';
			}
		}
		else if ( in_array($dkey, $auth_up) )
		{
			foreach ( $auth_up_lvl as $levelskey => $levels )
			{
				$selected = ( $default[$dkey] == $auth_up_con[$levelskey] ) ? ' selected="selected"' : '';
				$opt .= '<option value="' . $auth_up_con[$levelskey] . '"' . $selected . '>' . sprintf($lang['sprintf_select_format'], $lang["common_auth_$levels"]) . '</option>';
			}
		}
		else if ( in_array($dkey, $auth_app) )
		{
			foreach ( $auth_app_lvl as $levelskey => $levels )
			{
				$selected = ( $default[$dkey] == $auth_app_con[$levelskey] ) ? ' selected="selected"' : '';
				$opt .= '<option value="' . $auth_app_con[$levelskey] . '"' . $selected . '>' . sprintf($lang['sprintf_select_format'], $lang["common_auth_$levels"]) . '</option>';
			}
		}
		
		$opt .= '</select>&nbsp;' . $lang['common_'.$dkey] . '</label><br />';
	}
	
	return array($opt, $auth);
}

?>