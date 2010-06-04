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
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
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

function select_box($type, $class, $field_id, $field_name, $default = '', $switch = '')
{
	global $db, $lang, $config, $settings;
	
	switch ( $type )
	{
		case 'match';
			break;

		case 'newscategory';
			break;

		case 'team';
			$table = TEAMS;
			$where = ( $switch != '0' ) ? ( $switch == '2' ) ? ' WHERE team_join = 1' : ' WHERE team_fight = 1' : '';
			$order = ' ORDER BY team_order';
			break;

		case 'user';
			$table = USERS;
			$where = ' WHERE user_id <> ' . ANONYMOUS;
			$order = ' ORDER BY user_id DESC';
			break;
			
		default:
			
			message(GENERAL_ERROR, 'Error', '');
			
			break;
	}
	
	$sql = 'SELECT ' . $field_id . ', ' . $field_name . ' FROM ' . $table . $where . $order;
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data = $db->sql_fetchrowset($result);
	
	$select = '<select class="' . $class . '" name="' . $field_id . '">';
	$select .= '<option value="0">&raquo; ' . $lang['msg_select_' . $type ] . '</option>';
	
	foreach ( $data as $info => $value )
	{
		$selected = ( $value[$field_id] == $default ) ? 'selected="selected"' : '';
		$select .= '<option value="' . $value[$field_id] . '" ' . $selected . '>&raquo; ' . $value[$field_name] . '&nbsp;</option>';
	}
	$select .= '</select>';

	return $select;
	
}

function select_lang_box($var, $name, $default, $class)
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
		
	$sql = 'SELECT * FROM ' . NEWSCAT . " ORDER BY newscat_order";
	if (!($result = $db->sql_query($sql)))
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	$func_select = '<select name="newscat_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
	$func_select .= '<option value="0">----------</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['newscat_id'] == $default ) ? ' selected="selected"' : '';
		$func_select .= '<option value="' . $row['newscat_image'] . '"' . $selected . '>' . $row['newscat_title'] . '&nbsp;</option>';
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
	$func_select .= '<option value="0">&raquo; ' . $lang['select_match'] . '</option>';
	
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
function select_date($default, $var, $value)
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
				'03'	=> 'M&auml;rz',
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
				'03'	=> 'M&auml;rz',
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

	$disable_select = '<select class="select" name="' . $select_name . '[]" id="' . $select_name . '" multiple="multiple">';
	foreach ( $lang['page_disable_mode_opt'] as $const => $name)
	{
		$selected = (in_array($const, $default)) ? ' selected="selected"' : '';
		$disable_select .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
	}
	$disable_select .= '</select>';

	return $disable_select;
}


?>