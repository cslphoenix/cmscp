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
	$select .= '<option value="">&raquo;&nbsp;' . $lang['msg_select_' . $type ] . '&nbsp;</option>';
	
	foreach ( $path_files as $file )
	{
		if ( $file != '.' && $file != '..' && $file != 'index.htm' && $file != '.svn' )
		{
			$marked = ( $file == $default ) ? ' selected="selected"' : '';
			$select .= '<option value="' . $file . '" ' . $marked . '>' . $file . '&nbsp;</option>';
		}
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
	$lang;
	
	switch ( $default )
	{
		case 'day':
		
			if ( isset($create) && $create <= (time()- 86400) )
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
					$selected = ( $i == $value ) ? 'selected="selected"' : '';
				#	$select .= '<option value="' . $i . '" ' . $selected . '>' . $i . '&nbsp;</option>';
					$select .= "<option value=\"$i\" $selected>&raquo;&nbsp;$i&nbsp;</option>";
				}
				$select .= "</select>";
			}
			
		break;
		
		case 'month':
		
			$select = "<select class=\"$class\" name=\"$var\">";
			for ( $i = 1; $i < 13; $i++ )
			{
				if ( $i < 10 )
				{
					$i = '0'.$i;
				}
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
			#	$select .= '<option value="' . $i . '" ' . $selected . '>' . $i . '&nbsp;</option>';
				$select .= "<option value=\"$i\" $selected>&raquo;&nbsp;$i&nbsp;</option>";
			}
			$select .= '</select>';
			
		break;
		
		case 'monthn':
		
			$monate = array(
				'01'	=> 'Januar',
				'02'	=> 'Februar',
				'03'	=> 'März',
				'04'	=> 'April',
				'05'	=> 'Mai',
				'06'	=> 'Juni',
				'07'	=> 'Juli',
				'08'	=> 'August',
				'09'	=> 'September',
				'10'	=> 'Oktober',
				'11'	=> 'November',
				'12'	=> 'Dezember'
			);
		
			$select = "<select class=\"$class\" name=\"$var\">";
			for ( $i = 1; $i < 13; $i++ )
			{
				if ( $i < 10 )
				{
					$i = '0'.$i;
				}
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
			#	$select .= '<option value="' . $i . '" ' . $selected . '>' . $monate[$i] . '&nbsp;</option>';
				$select .= "<option value=\"$i\" $selected>&raquo;&nbsp;$i&nbsp;</option>";
			}
			$select .= '</select>';
			
		break;
		
		case 'monthm':
		
			$monate = array(
				'01'	=> 'Januar',
				'02'	=> 'Februar',
				'03'	=> 'März',
				'04'	=> 'April',
				'05'	=> 'Mai',
				'06'	=> 'Juni',
				'07'	=> 'Juli',
				'08'	=> 'August',
				'09'	=> 'September',
				'10'	=> 'Oktober',
				'11'	=> 'November',
				'12'	=> 'Dezember'
			);
			
			if ( !is_array($value) )
			{
				$value = explode(',', $value);
			}
		
			$select = '<select class=\"$class\" name="' . $var . '[]" multiple="multiple rows="12" size="12">';
			
			foreach ($monate as $const => $name)
			{
				$selected = (in_array($const, $value)) ? ' selected="selected"' : '';
				$select .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
			}
			$select .= '</select>';
			
		break;
		
		case 'year':
		
			$select = "<select class=\"$class\" name=\"$var\">";
			for ($i=$value; $i < $value+2; $i++)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
			#	$select .= '<option value="' . $i . '" ' . $selected . '>' . $i . '&nbsp;</option>';
				$select .= "<option value=\"$i\" $selected>&raquo;&nbsp;$i&nbsp;</option>";
			}
			$select .= '</select>';
		
		break;
		
		case 'hour':
		
			$select = "<select class=\"$class\" name=\"$var\">";
			for ($i=0; $i < 24; $i++)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
			#	$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
				$select .= "<option value=\"$i\" $selected>&raquo;&nbsp;$i&nbsp;</option>";
			}
			$select .= '</select>';
			
		break;
		
		case 'min':
		
			$select = "<select class=\"$class\" name=\"$var\">";
			for ($i="00"; $i < 60; $i = $i + 15)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
			#	$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
				$select .= "<option value=\"$i\" $selected>&raquo;&nbsp;$i&nbsp;</option>";
			}
			$select .= '</select>';
			
		break;
		
		case 'duration':
		
			$select = "<select class=\"$class\" name=\"$var\" id=\"duration\">";
			for ($i="00"; $i < 260; $i = $i + 30)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
			#	$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
				$select .= "<option value=\"$i\" $selected>&raquo;&nbsp;$i&nbsp;</option>";
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


?>