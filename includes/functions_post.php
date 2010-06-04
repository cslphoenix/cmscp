<?php

function _bbcode($text)
{
	$text = preg_replace("/\[b\](.*)\[\/b\]/Usi", "<b>\\1</b>", $text); 
	$text = preg_replace("/\[i\](.*)\[\/i\]/Usi", "<i>\\1</i>", $text); 
	$text = preg_replace("/\[u\](.*)\[\/u\]/Usi", "<u>\\1</u>", $text); 
	$text = preg_replace("/\[color=(.*)\](.*)\[\/color\]/Usi", "<span color=\"\\1\">\\2</span>", $text); 
	$text = preg_replace("/\[email=(.*)\](.*)\[\/email\]/Usi", "<a href=\"mailto:\\1\">\\2</a>", $text);
	
	$text = preg_replace_callback("/\[url=(.*)\](.*)\[\/url\]/Usi", 'linkLenght', $text); 
	
	// "reine" URLs umwandeln 
	$text = preg_replace_callback('#(( |^)(((ftp|http|https|)://)|www.)\S+)#mi', 'linkLenght', $text);
	
	return;

}

// Pr�ft die Linkl�nge und passt sie gegebenenfalls an 
// wird f�r preg_replace_callback definiert 
function linkLenght($treffer) 
{ 
	// $treffer[1] ist die URL 
	$url = trim($treffer[1]); 
	if(substr($url,0,7)!= 'http://') 
			$url = "http://" . $url; 
	// $treffer[2] ist der Ausgabename 
	// wurde kein Name angegeben, wird die URL als Name gew�hlt 
	if(strlen(trim($treffer[2]))!=0) 
		$linkname = $treffer[2]; 
	else 
		$linkname = $treffer[1]; 
	// legt eine maximale L�nge von 50 Zeichen fest 
	// Ausnahme bei [img]-Tags 
	if(strlen($linkname)>40 AND !substr_count(strtolower($linkname), '[img]') AND !substr_count(strtolower($linkname), '[/img]')) 
		$linkname = substr($linkname, 0, 32)."...".substr($linkname, -5); 
	// R�ckgabelink 
	$ergebnis = "<a href=\"" . $url."\" target=\"_blank\">" . $linkname."</a>"; 
	return $ergebnis; 
}

//
//	Funktion zum Eintragen von Kommentaren
//	17.03.2009 Anpassung an Trainingskommentare
//	�nderung zu Gastkommentaren und Benutzerkommentaren
//
function _comment_message($mode, $table, $id, $posterid, $posterip, $message, $posternick='', $postermail='', $posterhp='')
{
	global $config, $settings, $lang, $db;
	global $userdata, $user_ip;
	
	$message = preg_replace("/\[b\](.*)\[\/b\]/Usi", "<b>\\1</b>", $message); 
	$message = preg_replace("/\[i\](.*)\[\/i\]/Usi", "<i>\\1</i>", $message); 
	$message = preg_replace("/\[u\](.*)\[\/u\]/Usi", "<u>\\1</u>", $message); 
	$message = preg_replace("/\[color=(.*)\](.*)\[\/color\]/Usi", "<span color=\"\\1\">\\2</span>", $message); 
//	$message = preg_replace("/\[email=(.*)\](.*)\[\/email\]/Usi", "<a href=\"mailto:\\1\">\\2</a>", $message);
	$message = preg_replace_callback("/\[url=(.*)\](.*)\[\/url\]/Usi", 'linkLenght', $message); 
	
	// "reine" URLs umwandeln 
	$message = preg_replace_callback('#(( |^)(((ftp|http|https|)://)|www.)\S+)#mi', 'linkLenght', $message);
	
	//	Kommentar HTML Tags umwandeln
	//	htmlentities - (Wandelt alle geeigneten Zeichen in entsprechende HTML-Codes um)
	//	ENT_QUOTES - (Konvertiert sowohl doppelte als auch einfache Anf�hrungszeichen.)
	$message = htmlentities($message, ENT_QUOTES);
	
	//	Benutzer festlegen ob G�ste oder registerter Benutzer
	if ( $userdata['user_id'] == ANONYMOUS )
	{
		$poster_id		= ANONYMOUS;
		$poster_nick	= $posternick;
		$poster_email	= $postermail;
		$poster_hp		= $posterhp;
		$poster_ip		= $user_ip;
		$sql_fields		= 'poster_nick, poster_email, poster_hp, ';
		$sql_data		= "'" . str_replace("\'", "''", $poster_nick) . "', '" . str_replace("\'", "''", $poster_email) . "', '" . str_replace("\'", "''", $poster_hp) . "', ";
	}
	else
	{
		//	Benutzername sowie Benutzermail und Homepage
		//	werden nicht eingetragen, Zwecks dynamischer
		//	Erhaltung der Daten, kurz, falls ein Benutzer
		//	Daten ver�ndert, werden diese beim Auslesen der
		//	Kommentare auch immer richtig und Akuell sein!
		$poster_id		= $userdata['user_id'];
		$poster_nick	= '';
		$poster_email	= '';
		$poster_hp		= '';
		$poster_ip		= $userdata['session_ip'];
		$sql_fields		= '';
		$sql_data		= '';
	}
	
	//	Tabellen auswahl mit entsprechender ID
	switch ($table)
	{
		case 'news':
			$table_name = NEWS;
			$table_com	= NEWS_COMMENTS;
			$id_name	= 'news_id';
		break;
		case 'match':
			$table_name = MATCH;
			$table_com	= MATCH_COMMENTS;
			$id_name	= 'match_id';
		break;
		case 'training':
			$table_name = TRAINING;
			$table_com	= TRAINING_COMMENTS;
			$id_name	= 'training_id';
		break;
		case 'bugtracker':
			$table_name = BUGTRACKER;
			$table_com	= BUGTRACKER_COMMENTS;
			$id_name	= 'bugtracker_id';
		break;
		default:
			message(GENERAL_ERROR, 'No Table given');
		break;
	}
	
	if ( $mode == 'add' )
	{
		$sql = 'INSERT INTO ' . $table_com . " ($id_name, poster_id, $sql_fields poster_ip, poster_text, time_create)
			VALUES ('" . intval($id) . "', '" . intval($poster_id) . "', $sql_data '" . $poster_ip . "', '" . str_replace("\'", "''", $message) . "', '" . time() . "')";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$sql = "UPDATE $table_name SET " . $table . "_comment = " . $table . "_comment + 1 WHERE $id_name = $id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		log_add(LOG_USER, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_COMMENT, 'comment_' . $table);
	}
	
	return;
}

?>