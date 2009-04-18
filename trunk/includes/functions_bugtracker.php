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

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

function bt_type($default)
{
	global $lang;
	
	$bt_type_select = '<select name="bt_type" class="post">';
	$bt_type_select .= '<option value="0">Bitte eine Auswahl treffen</option>';
	
	foreach ($lang['bt_error'] as $const => $name)
	{
		$selected = ( $const == $default ) ? ' selected="selected"' : '';
		$bt_type_select .= '<option value="' . $const . '"' . $selected . '>' . $name . '</option>';
	}
	$bt_type_select .= '</select>';
	
	return $bt_type_select;
}

//	bt_add('add', $userdata['user_id'], $bt_title, $bt_desc, $bt_type, $bt_php, $bt_sql, $bt_message);

function bt_add($creator, $title, $desc, $type, $php, $sql, $message)
{
	global $config, $settings, $lang, $db;
	global $userdata;
	
	//	bbcodes
	$message = preg_replace("/\[b\](.*)\[\/b\]/Usi", "<b>\\1</b>", $message);
	$message = preg_replace("/\[i\](.*)\[\/i\]/Usi", "<i>\\1</i>", $message);
	$message = preg_replace("/\[u\](.*)\[\/u\]/Usi", "<u>\\1</u>", $message);
	$message = preg_replace_callback("/\[url=(.*)\](.*)\[\/url\]/Usi", 'linkLenght', $message);
	
	// "reine" URLs umwandeln
	$message = preg_replace_callback('#(( |^)(((ftp|http|https|)://)|www.)\S+)#mi', 'linkLenght', $message);
	
	//	Kommentar HTML Tags umwandeln
	//	htmlentities - (Wandelt alle geeigneten Zeichen in entsprechende HTML-Codes um)
	//	ENT_QUOTES - (Konvertiert sowohl doppelte als auch einfache Anführungszeichen.)
	$message = htmlentities($message, ENT_QUOTES);
	
	//	Einfügen
	$sql = 'INSERT INTO ' . BUGTRACKER . " (bugtracker_title, bugtracker_description, bugtracker_message, bugtracker_php, bugtracker_sql, bugtracker_creator, bugtracker_type, bugtracker_status, bugtracker_create)
		VALUES ('" . str_replace("\'", "''", $title) . "', '" . str_replace("\'", "''", $desc) . "', '" . str_replace("\'", "''", $message) . "', '" . str_replace("\'", "''", $php) . "', '" . str_replace("\'", "''", $sql) . "', '" . $userdata['user_id'] . "', '" . str_replace("\'", "''", $type) . "', 'bt_new', '" . time() . "')";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	_log(LOG_USER, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_BUGTRACKER, 'bt_add');
	
	return;
}

function bt_edit($bt_id, $title, $desc, $type, $php, $sql, $message)
{
	global $config, $settings, $lang, $db;
	global $userdata;
	
	//	bbcodes
	$message = preg_replace("/\[b\](.*)\[\/b\]/Usi", "<b>\\1</b>", $message);
	$message = preg_replace("/\[i\](.*)\[\/i\]/Usi", "<i>\\1</i>", $message);
	$message = preg_replace("/\[u\](.*)\[\/u\]/Usi", "<u>\\1</u>", $message);
	$message = preg_replace_callback("/\[url=(.*)\](.*)\[\/url\]/Usi", 'linkLenght', $message);
	
	// "reine" URLs umwandeln
	$message = preg_replace_callback('#(( |^)(((ftp|http|https|)://)|www.)\S+)#mi', 'linkLenght', $message);
	
	//	Kommentar HTML Tags umwandeln
	//	htmlentities - (Wandelt alle geeigneten Zeichen in entsprechende HTML-Codes um)
	//	ENT_QUOTES - (Konvertiert sowohl doppelte als auch einfache Anführungszeichen.)
	$message = htmlentities($message, ENT_QUOTES);
	
	//	Einfügen
	$sql = 'UPDATE ' . BUGTRACKER . "
				SET
					bugtracker_title		= '" . str_replace("\'", "''", $title) . "',
					bugtracker_description	= '" . str_replace("\'", "''", $desc) . "',
					bugtracker_message		= '" . str_replace("\'", "''", $message) . "',
					bugtracker_php			= '" . str_replace("\'", "''", $php) . "',
					bugtracker_sql			= '" . str_replace("\'", "''", $sql) . "',
					bugtracker_type			= '" . str_replace("\'", "''", $type) . "',
					bugtracker_update		= '" . time() . "'
				WHERE bugtracker_id = $bt_id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
	}

	_log(LOG_USER, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_BUGTRACKER, 'bt_edit');
	
	return;
}

?>