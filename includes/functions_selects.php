<?php


function select_lang_box2($var, $name, $default, $class)
{
	global $lang;
		
	$select_switch = '<select name="' . $name . '" class="' . $class . '">';
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


function _select_newscat($default)
{
	global $db;
		
	$sql = 'SELECT * FROM ' . NEWS_CAT . " ORDER BY cat_order";
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$func_select = '<select name="cat_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
	$func_select .= '<option value="0">----------</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['cat_id'] == $default ) ? ' selected="selected"' : '';
		$func_select .= '<option value="' . $row['cat_image'] . '"' . $selected . '>' . $row['cat_title'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}


//
//	Match Select
//
//	default:	id
//	class:		css class
//
function _select_match2($default, $type, $class)
{
	global $db, $lang;
	
	$where = ($type == '') ? ' WHERE match_date > ' . time() : '';
	
	$sql = 'SELECT match_id, match_rival_name, match_rival_tag FROM ' . MATCH . $where . ' ORDER BY match_date';
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$func_select = '<select class="' . $class . '" name="match_id">';
	$func_select .= '<option value="0">&raquo; ' . $lang['select_match'] . '</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['match_id'] == $default ) ? 'selected="selected"' : '';
		$func_select .= '<option value="' . $row['match_id'] . '" ' . $selected . ' >&raquo; ' . $row['match_rival_name'] . ' :: ' . $row['match_rival_tag'] . '&nbsp;</option>';
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
function select_date2($default, $var, $value)
{
	$lang;
	
	switch ($default)
	{
		case 'day':
		
			$select = '<select class="select" name="' . $var . '">';
			for ( $i = 1; $i < 32; $i++ )
			{
				if ($i < 10)
				{
					$i = '0'.$i;
				}
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . '>' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
			
		break;
		
		case 'month':
		
			$select = '<select class="select" name="' . $var . '">';
			for ( $i = 1; $i < 13; $i++ )
			{
				if ( $i < 10 )
				{
					$i = '0'.$i;
				}
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . '>' . $i . '&nbsp;</option>';
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
		
			$select = '<select class="select" name="' . $var . '">';
			for ( $i = 1; $i < 13; $i++ )
			{
				if ( $i < 10 )
				{
					$i = '0'.$i;
				}
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . '>' . $monate[$i] . '&nbsp;</option>';
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
		
			$select = '<select class="select" name="' . $var . '[]" multiple="multiple rows="12" size="12">';
			foreach ($monate as $const => $name)
			{
				$selected = (in_array($const, $value)) ? ' selected="selected"' : '';
				$select .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
			}
			$select .= '</select>';
			
		break;
		
		case 'year':
		
			$select = '<select class="select" name="' . $var . '">';
			for ($i=$value; $i < $value+2; $i++)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . '>' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
		
		break;
		
		case 'hour':
		
			$select = '<select class="select" name="' . $var . '">';
			for ($i=0; $i < 24; $i++)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
			
		break;
		
		case 'min':
		
			$select = '<select class="select" name="' . $var . '">';
			for ($i="00"; $i < 60; $i = $i + 15)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
			
		break;
		
		case 'duration':
		
			$select = '<select class="select" name="' . $var . '">';
			for ($i="00"; $i < 260; $i = $i + 30)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
			
		break;
			
	}
	
	return $select;
}

/*
 *	orignal from phpBB2
 */
function select_tz($default, $select_name = 'timezone')
{
	global $sys_timezone, $lang;

	if ( !isset($default) )
	{
		$default == $sys_timezone;
	}
	$tz_select = '<select class="select" name="' . $select_name . '">';

	while( list($offset, $zone) = @each($lang['tz']) )
	{
		$selected = ( $offset == $default ) ? ' selected="selected"' : '';
		$tz_select .= '<option value="' . $offset . '"' . $selected . '>' . sprintf($lang['stf_select_format'], $zone) . '</option>';
	}
	$tz_select .= '</select>';

	return $tz_select;
}

//
// Pick a language, any language ...
//
function select_language($default, $select_name = "language", $dirname = "language")
{
	global $root_path, $lang;

	$dir = opendir($root_path . $dirname);

	$language = array();
	
	while ( $file = readdir($dir) )
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@cms_realpath($root_path . $dirname . '/' . $file)) && !is_link(@cms_realpath($root_path . $dirname . '/' . $file)))
		{
			$filename = trim(str_replace("lang_", "", $file));
			$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
			$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
			$language[$displayname] = $filename;
		}
	}

	closedir($dir);

	@asort($language);
	@reset($language);

	$lang_select = '<select class="select" name="' . $select_name . '">';
	
	while ( list($displayname, $filename) = @each($language) )
	{
		$name = isset($lang['language'][$displayname]) ? $lang['language'][$displayname] : $displayname;
		
		$selected = ( strtolower($default) == strtolower($filename) ) ? ' selected="selected"' : '';
		$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . sprintf($lang['stf_select_format'], $name) . '</option>';
	}
	$lang_select .= '</select>';

	return $lang_select;
}

function select_lang($default, $select_name = "language", $dirname = "language")
{
	global $root_path, $lang;

	$dir = opendir($root_path . $dirname);

	$language = array();
	
	while ( $file = readdir($dir) )
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@cms_realpath($root_path . $dirname . '/' . $file)) && !is_link(@cms_realpath($root_path . $dirname . '/' . $file)))
		{
			$filename = trim(str_replace("lang_", "", $file));
			$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
			$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
			$language[$displayname] = $filename;
		}
	}

	closedir($dir);

	@asort($language);
	@reset($language);

	$lang_select = '<select class="select" name="' . $select_name . '">';
	
	while ( list($displayname, $filename) = @each($language) )
	{
		$name = isset($lang['language'][$displayname]) ? $lang['language'][$displayname] : $displayname;
		
		$selected = ( strtolower($default) == strtolower($filename) ) ? ' selected="selected"' : '';
		$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . sprintf($lang['stf_select_format'], $name) . '</option>';
	}
	$lang_select .= '</select>';

	return $lang_select;
}

//
// Pick a template/theme combo, 
//
function select_style($default, $select_name = "style", $dirname = "templates")
{
	global $db, $lang;

	$sql = "SELECT themes_id, style_name FROM " . THEMES . " ORDER BY template_name, themes_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrowset($result);

	$select = "<select class=\"select\" name=\"$select_name\">";
	
	for ( $i = 0; $i < count($row); $i++ )
	{
		$selected = ( $default == $row[$i]['themes_id'] ) ? ' selected="selected"' : '';

		$select .= '<option value="' . $row[$i]['themes_id'] . '"' . $selected . '>' . sprintf($lang['stf_select_format'], $row[$i]['style_name']) . '</option>';
	}
	$select .= "</select>";

	return $select;
}

?>