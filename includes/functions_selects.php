<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel � 2009
	* @code:	Sebastian Frickel � 2009

***/

function select_box($type, $class, $field_id, $field_name, $default = '')
{
	global $db, $lang, $config, $settings;
	
	switch ($type)
	{
		case 'user';
		
			$table	= USERS;
			$where	= ' WHERE user_id <> ' . ANONYMOUS;
			$order	= ' ORDER BY user_id DESC';
			
			break;
			
		default:
			
			message_die(GENERAL_ERROR, 'Error', '');
			
			break;
	}
	
	$sql = 'SELECT ' . $field_id . ', ' . $field_name . ' FROM ' . $table . $where . $order;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data = $db->sql_fetchrowset($result);
	
	$select = '<select class="' . $class . '" name="' . $field_id . '">';
	$select .= '<option value="0">&raquo; ' . $lang['msg_select_' . $type] . '</option>';
	
	foreach ( $data as $info => $value)
	{
		$selected = ( $value[$field_id] == $default ) ? 'selected="selected"' : '';
		$select .= '<option value="' . $value[$field_id] . '" ' . $selected . '>&raquo; ' . $value[$field_name] . '&nbsp;</option>';
	}
	$select .= '</select>';

	return $select;
	
}

function _select_user($class, $default = '')
{
	global $db, $lang;
	
	$sql = 'SELECT user_id, username
				FROM ' . USERS . '
				WHERE user_id <> ' . ANONYMOUS . '
			ORDER BY user_level DESC';
//	$data_users = _cached($sql, 'data_users', 0);
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data_users = $db->sql_fetchrowset($result);

	$users = array();
	for ( $i = 0; $i < count($data_users); $i++ )
	{
		$users[] = $data_users[$i];
	}
	
	$select = '<select class="' . $class . '" name="user_id">';
	$select .= '<option value="">&raquo; ' . $lang['msg_select_user'] . '</option>';
	
	foreach($users as $user => $user_info)
	{
		$selected = ( $user_info['user_id'] == $default ) ? 'selected="selected"' : '';
		$select .= '<option value="' . $user_info['user_id'] . '" ' . $selected . '>&raquo; ' . $user_info['username'] . '&nbsp;</option>';
	}
	$select .= '</select>';

	return $select;
}

function _select_box($default, $type)
{
	global $db, $lang;
	
	$func_select = '<select class="select" name="rank_id">';
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['rank_order'] == $default ) ? ' selected="selected"' : '';
		$func_select .= '<option value="' . $row['rank_id'] . '"' . $selected . '>' . $row['rank_title'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}

function _select_newscat($default)
{
	global $db;
		
	$sql = 'SELECT * FROM ' . NEWS_CATEGORY . " ORDER BY news_category_order";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
	}

	$func_select = '<select name="news_category_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
	$func_select .= '<option value="0">----------</option>';
	
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['news_category_id'] == $default ) ? ' selected="selected"' : '';
		$func_select .= '<option value="' . $row['news_category_image'] . '"' . $selected . '>' . $row['news_category_title'] . '&nbsp;</option>';
	}
	$func_select .= '</select>';

	return $func_select;
}

//
//	Rang (Page/Forum/Team) Select
//
//	type:			1 = Page / 2 = Forum / 3 = Team
//
function _select_rank($default, $type)
{
	global $db;
	
	$order = ($type == '2') ? 'rank_order' : 'rank_id';
	
	$sql = 'SELECT rank_id, rank_title, rank_order FROM ' . RANKS . " WHERE rank_type = $type ORDER BY $order";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
	}

	$func_select = '<select class="select" name="rank_id">';
	$func_select .= '<option value="0">----------</option>';
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ( $row['rank_id'] == $default ) ? ' selected="selected"' : '';
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
	
	$sql = 'SELECT * FROM ' . GAMES . ' WHERE game_id != -1 ORDER BY game_order';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
	}
	
	$func_select = '<select class="select" name="game_image" onchange="update_image(this.options[selectedIndex].value);">';
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

	$sql = 'SELECT team_id, team_name FROM ' . TEAMS . $typ . ' ORDER BY team_order';
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
function _select_match($default, $type, $class)
{
	global $db, $lang;
	
	$where = ($type == '') ? ' WHERE match_date > ' . time() : '';
	
	$sql = 'SELECT match_id, match_rival, match_rival_tag FROM ' . MATCH . $where . ' ORDER BY match_date';
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