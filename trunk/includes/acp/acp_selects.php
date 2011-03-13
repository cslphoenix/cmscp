<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010
 *	@code:	Sebastian Frickel © 2009, 2010
 *
 */

/*
 *	@param string $type			enthält den Titel
 *
 *	$type = art
 *	$class = cssstyle
 *	$field_id = user_id/group_id
 *	$field_name = username/group_name
 *	$default = startauswahl
 *	$switch = team_join/team_fight
 *
 */

function select_box($type, $class, $default = '', $switch = '')
{
	global $db, $lang;
	
	switch ( $type )
	{
		case 'match':
			$table	= MATCH;
			$fields	= 'match_id, match_rival';
			$where	= ' WHERE match_date >= ' . time();
			$order	= ' ORDER BY match_id DESC';
			$selct	= false;
			break;

		case 'newscat':
			$table	= NEWSCAT;
			$fields	= 'newscat_id, newscat_title, newscat_image';
			$where	= '';
			$order	= ' ORDER BY newscat_order ASC';
			$selct	= true;
			break;

		case 'team';
			$table	= TEAMS;
			$fields	= 'team_id, team_name';
			$where	= ( $switch != '' ) ? ( $switch == '2' ) ? ' WHERE team_join = 1' : ' WHERE team_fight = 1' : '';
			$order	= ' ORDER BY team_order';
			$selct	= false;
			break;

		case 'user':
			$table	= USERS;
			$fields	= 'user_id, username';
			$where	= ' WHERE user_id <> ' . ANONYMOUS;
			$order	= ' ORDER BY user_id DESC';
			$selct	= false;
			break;
			
		case 'game':
			$table	= GAMES;
			$fields	= 'game_id, game_name, game_image';
			$where	= ' WHERE game_id != -1 ';
			$order	= ' ORDER BY game_order ASC';
			$selct	= true;
			break;
			
		case 'ranks':
			$table	= RANKS;
			$fields	= 'rank_id, rank_title';
			$where	= ' WHERE rank_type = ' . $switch;
			$order	= ' ORDER BY rank_order ASC';
			$selct	= false;
			break;
		
		default:
			message(GENERAL_ERROR, 'Error', '');
			break;
	}
	
	$sql = 'SELECT ' . $fields . ' FROM ' . $table . $where . $order;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data = $db->sql_fetchrowset($result);
	
	$field	= explode(', ', $fields);
	$fielda	= $field[0];
	$fieldb	= $field[1];
	$fieldc	= ( isset($field[2]) ) ? $field[2] : $field[0];

	if ( $data )
	{		
		$select = ( !$selct ) ? '<select class="' . $class . '" name="' . $fielda . '" id="' . $fielda . '">' : '<select class="' . $class . '" name="' . $fieldc . '" id="' . $fieldc . '" onchange="update_image(this.options[selectedIndex].value);">';
		$select .= '<option value="-1">&raquo;&nbsp;' . $lang['msg_select_' . $type ] . '&nbsp;</option>';
		
		foreach ( $data as $info => $value )
		{
			$selected = ( $value[$fielda] == $default ) ? 'selected="selected"' : '';
			$select .= '<option value="' . $value[$fieldc] . '" ' . $selected . '>&raquo;&nbsp;' . $value[$fieldb] . '&nbsp;</option>';
		}
		$select .= '</select>';
	}
	else
	{
		$select = '&nbsp;&raquo;&nbsp;' . sprintf($lang['msg_sprintf_noentry'], $lang[$type]) . '!';
	}

	return $select;
}

function select_box_files($type, $class, $path, $default = '')
{
	global $db, $lang;
	
	$path_files = scandir($path);
				
	$select = '<select class="' . $class . '" name="' . $type . '" id="' . $type . '" onchange="update_image(this.options[selectedIndex].value);">';
	$select .= '<option value="./../spacer.gif">&raquo;&nbsp;' . $lang['msg_select_' . $type ] . '&nbsp;</option>';
	
	$endung = array('png', 'jpg', 'jpeg', 'gif');
	
	foreach ( $path_files as $files )
	{
		if ( $files != '.' && $files != '..' && $files != 'index.htm' && $files != '.svn' )
		{
			if ( in_array(substr($files, -3), $endung) )
			{
				$file[] = $files;
			}
		}
	}
	
	foreach ( $file as $files )
	{
		$filter = str_replace(substr($files, strrpos($files, '.')), "", $files);

		$marked = ( $files == $default ) ? ' selected="selected"' : '';
		$select .= '<option value="' . $files . '" ' . $marked . '>' . $filter . '&nbsp;</option>';
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

function _select_rank($class, $default, $type)
{
	global $db, $lang;
	
	$order = ( $type == '2' ) ? 'rank_order' : 'rank_id';
	
	$sql = 'SELECT rank_id, rank_title, rank_order FROM ' . RANKS . " WHERE rank_type = $type ORDER BY $order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$func_select = '<select class="' . $class . '" name="rank_id">';
	$func_select .= '<option value="0">----------</option>';
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['rank_id'] == $default ) ? ' selected="selected"' : '';
		$func_select .= '<option value="' . $row['rank_id'] . '"' . $selected . '>' . $row['rank_title'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}

/*
function select_newscategory($default)
{
	global $db, $lang;
		
	$sql = 'SELECT * FROM ' . NEWSCAT . " ORDER BY newscat_order";
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$func_select = '<select name="news_category" class="post" onchange="update_image(this.options[selectedIndex].value);">';
	$func_select .= '<option value="0">' . $lang['newscat_select'] . '</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['newscat_id'] == $default ) ? ' selected="selected"' : '';
		$func_select .= '<option value="' . $row['newscat_image'] . '"' . $selected . '>&raquo;&nbsp;' . $row['newscat_title'] . '&nbsp;</option>';
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
//	Rang (Page/Forum/Team) Select
//
//	type:			1 = Page / 2 = Forum / 3 = Team
//


//
//	Match Select
//
//	default:	id
//	class:		css class
//
function _select_match($default, $type, $class)
{
	global $db, $lang;
	
	$where = ($type == '') ? ' WHERE match_date > ' . time() : '';
	
	$sql = 'SELECT match_id, match_rival, match_rival_tag FROM ' . MATCH . $where . ' ORDER BY match_date';
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$func_select = '<select class="' . $class . '" name="match_id">';
	$func_select .= '<option value="0">&raquo;&nbsp;' . $lang['msg_select_match'] . '&nbsp;</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['match_id'] == $default ) ? 'selected="selected"' : '';
		$func_select .= '<option value="' . $row['match_id'] . '" ' . $selected . ' >&raquo; ' . $row['match_rival'] . ' :: ' . $row['match_rival_tag'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}

//
//	Date Select
//
//	default:	day/month/year/hour/min
//	value:		select Wert
//
function select_date($class, $default, $var, $value, $create = '')
{
	global $lang;
	
	switch ( $default )
	{
		case 'day':
		
			if ( $create <= ( time()- 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				for ( $i = 1; $i < 32; $i++ )
				{
					if ( $i < 10 )
					{
						$i = '0' . $i;
					}
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>&raquo;&nbsp;$i&nbsp;</option>";
				}
				$select .= "</select>";
			}
			
			break;
		
		case 'month':
		
			if ( $create <= ( time()- 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				for ( $i = 1; $i < 13; $i++ )
				{
					if ( $i < 10 )
					{
						$i = '0' . $i;
					}
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>&raquo;&nbsp;$i&nbsp;</option>";
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
			
			if ( $create <= ( time()- 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				for ( $i = 1; $i < 13; $i++ )
				{
					if ( $i < 10 )
					{
						$i = '0' . $i;
					}
					$mark = ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>&raquo;&nbsp;$i&nbsp;</option>";
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
				$mark = ( in_array($const, $value) ) ? ' selected="selected"' : '';
			#	$select .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
				
				$select .= "<option value=\"$const\" $mark>&raquo;&nbsp;$name&nbsp;</option>";
			}
			$select .= '</select>';
			
			break;
		
		case 'year':
		
			if ( $create <= ( time()- 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				for ( $i = $value; $i < $value + 2; $i++ )
				{
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>&raquo;&nbsp;$i&nbsp;</option>";
				}
				$select .= '</select>';
			}
		
			break;
		
		case 'hour':
		
			if ( $create <= ( time()- 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				for ( $i = 0; $i < 24; $i++ )
				{
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>&raquo;&nbsp;$i&nbsp;</option>";
				}
				$select .= '</select>';
			}
			
			break;
		
		case 'min':
		
			if ( $create <= ( time()- 86400 ) )
			{
				$select = $value . "<input type=\"hidden\" name=\"$var\" value=\"$value\">";
			}
			else
			{
				$select = "<select class=\"$class\" name=\"$var\">";
				for ( $i = '00';$i < 60; $i = $i + 15 )
				{
					$mark	= ( $i == $value ) ? 'selected="selected"' : '';
					$select .= "<option value=\"$i\" $mark>&raquo;&nbsp;$i&nbsp;</option>";
				}
				$select .= '</select>';
			}
			
			break;
		
		case 'duration':
		
			$select = "<select class=\"$class\" name=\"$var\" id=\"duration\">";
			for ( $i='00';$i < 260; $i = $i + 30 )
			{
				$mark	= ( $i == $value ) ? 'selected="selected"' : '';
				$select .= "<option value=\"$i\" $mark>&raquo;&nbsp;$i&nbsp;</option>";
			}
			$select .= '</select>';
			
			break;
	}
	
	return $select;
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

	$disable_select = '<select class="select" name="' . $select_name . '[]" multiple="multiple">';
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
	global $db, $lang;
	
	$select = '';
	
	switch ( $table )
	{
		case NEWSCAT:
			$fields	= 'newscat_title, newscat_order';
			$where	= '';
			$order	= 'ORDER BY newscat_order ASC';
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
			
		case GROUPS:
			
			$fields	= 'group_name, group_order';
			$where	= 'WHERE group_single_user = 0 ';
			$order	= 'ORDER BY group_order ASC';
			
			break;
		
		default:
		
			message(GENERAL_ERROR, 'Error', '');
			
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

function select_lang($class, $field, $field_lang, $type, $data)
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

function img_num($table, $field, $name)
{
	global $db;
	
	$sql = "SELECT " . $field . "_id FROM $table WHERE " . $field . "_image = '$name'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	
	$num = ( $row[$field . "_id"] ) ? $row[$field . "_id"] : '-1';
	
	return $num;
}

function img_name($table, $field, $num)
{
	global $db;
	
	$sql = "SELECT " . $field . "_image FROM $table WHERE " . $field . "_id = '$num'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	
	$name = ( $row[$field . "_image"] ) ? $row[$field . "_image"] : '-1';
	
	return $name;
}

function select_maps($tag = '')
{
	global $db, $lang;
	
	$maps = data(MAPS, '', true, 0, 0);
	$cats = data(MAPS_CAT, '', true, 0, 0);
	
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

?>