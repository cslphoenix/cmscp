<?php

//
//	Rang (Page/Forum/Team) Select
//
//	default:		Auswahl Rang
//	type:			1 = Page / 2 = Forum / 3 = Team
//
function _select($default, $type)
{
	global $db;
	
	$order = ($type == '2') ? 'rank_order' : 'rank_id';
	
	$sql = 'SELECT rank_id, rank_title, rank_order FROM ' . RANKS_TABLE . " WHERE rank_type = $type ORDER BY $order";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
	}

	$func_select = '<select class="post" name="rank_id">';
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['rank_order'] == $default ) ? ' selected="selected"' : '';
		$func_select .= '<option value="' . $row['rank_id'] . '"' . $selected . '>' . $row['rank_title'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}

//
//	Game Select
//
//	default: id
//
function _select_game($default)
{
	global $db, $lang;
	
	$sql = 'SELECT * FROM ' . GAMES_TABLE . ' WHERE game_id != -1 ORDER BY game_order';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
	}
	
	$func_select = '<select class="post" name="game_image" onchange="update_image(this.options[selectedIndex].value);">';
	$func_select .= '<option value="">&raquo; ' . $lang['game_select'] . '</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['game_id'] == $default ) ? 'selected="selected"' : '';
		$func_select .= '<option value="' . $row['game_image'] . '" ' . $selected . ' >&raquo; ' . $row['game_name'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}

//
//	Team Select
//
//	default:	id
//	class:		css class
//	type:		alle/fight/join
//
function _select_team($default, $type, $class)
{
	global $db, $lang;
	
	$typ = ($type != '0') ? ($type == '2') ? ' WHERE team_join = 1' : ' WHERE team_fight = 1' : '';

	$sql = 'SELECT team_id, team_name FROM ' . TEAMS_TABLE . $typ . ' ORDER BY team_order';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
	}
	
	$func_select = '<select id="team_id" class="' . $class . '" name="team_id">';
	$func_select .= '<option value="">&raquo; ' . $lang['select_team'] . '</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['team_id'] == $default ) ? 'selected="selected"' : '';
		$func_select .= '<option value="' . $row['team_id'] . '" ' . $selected . ' >&raquo; ' . $row['team_name'] . '&nbsp;</option>';
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
function _select_match($default, $class)
{
	global $db, $lang;
	
	$sql = 'SELECT match_id, match_rival, match_rival_tag FROM ' . MATCH_TABLE . ' WHERE match_date > ' . time() . ' ORDER BY match_date';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
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
function _select_date($default, $var, $value)
{
	$lang;
	
	switch($default)
	{
		case 'day':
		
			$select = '<select class="post" name="' . $var . '">';
			for ($i=1; $i < 32; $i++)
			{
				if ($i < 10)
				{
					$i = '0'.$i;
				}
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
			
		break;
		
		case 'month':
		
			$select = '<select class="post" name="' . $var . '">';
			for ($i=1; $i < 13; $i++)
			{
				if ($i < 10)
				{
					$i = '0'.$i;
				}
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
			
		break;
		
		case 'year':
		
			$select = '<select class="post" name="' . $var . '">';
			for ($i=$value; $i < $value+2; $i++)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
		
		break;
		
		case 'hour':
		
			$select = '<select class="post" name="' . $var . '">';
			for ($i=0; $i < 24; $i++)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
			
		break;
		
		case 'min':
		
			$select = '<select class="post" name="' . $var . '">';
			for ($i="00"; $i < 60; $i = $i + 15)
			{
				$selected = ( $i == $value ) ? 'selected="selected"' : '';
				$select .= '<option value="' . $i . '" ' . $selected . ' >' . $i . '&nbsp;</option>';
			}
			$select .= '</select>';
			
		break;
		
		case 'duration':
		
			$select = '<select class="post" name="' . $var . '">';
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

?>