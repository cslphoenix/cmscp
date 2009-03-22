<?php

function _bbcode()
{
$text = preg_replace("/\[b\](.*)\[\/b\]/Usi", "<b>\\1</b>", $text); 
$text = preg_replace("/\[i\](.*)\[\/i\]/Usi", "<i>\\1</i>", $text); 
$text = preg_replace("/\[u\](.*)\[\/u\]/Usi", "<u>\\1</u>", $text); 
$text = preg_replace("/\[color=(.*)\](.*)\[\/color\]/Usi", "<span color=\"\\1\">\\2</span>", $text); 
$text = preg_replace("/\[email=(.*)\](.*)\[\/email\]/Usi", "<a href=\"mailto:\\1\">\\2</a>", $text);

$text = preg_replace_callback("/\[url=(.*)\](.*)\[\/url\]/Usi", 'linkLenght', $text); 

// "reine" URLs umwandeln 
$text = preg_replace_callback('#(( |^)(((ftp|http|https|)://)|www.)\S+)#mi', 'linkLenght', $text);

}
// Prüft die Linklänge und passt sie gegebenenfalls an 
    // wird für preg_replace_callback definiert 
    function linkLenght($treffer) 
    { 
        // $treffer[1] ist die URL 
        $url = trim($treffer[1]); 
        if(substr($url,0,7)!= 'http://') 
                $url = "http://".$url; 
        // $treffer[2] ist der Ausgabename 
        // wurde kein Name angegeben, wird die URL als Name gewählt 
        if(strlen(trim($treffer[2]))!=0) 
            $linkname = $treffer[2]; 
        else 
            $linkname = $treffer[1]; 
        // legt eine maximale Länge von 50 Zeichen fest 
        // Ausnahme bei [img]-Tags 
        if(strlen($linkname)>50 AND !substr_count(strtolower($linkname), '[img]') AND !substr_count(strtolower($linkname), '[/img]')) 
            $linkname = substr($linkname, 0, 45-3)."...".substr($linkname, -5); 
        // Rückgabelink 
        $ergebnis = "<a href=\"".$url."\" target=\"_blank\">".$linkname."</a>"; 
        return $ergebnis; 
    }

//
//	Funktion zum Eintragen von Kommentaren
//	17.03.2009 Anpassung an Trainingskommentare
//	Änderung zu Gastkommentaren und Benutzerkommentaren
//
function _comment_message($mode, $table, $id, $posterid, $posterip, $message, $posternick='', $postermail='', $posterhp='')
{
	global $config, $settings, $lang, $db;
	global $userdata, $user_ip;
	
	//	Kommentar HTML Tags umwandeln
	//	htmlentities - (Wandelt alle geeigneten Zeichen in entsprechende HTML-Codes um)
	//	ENT_QUOTES - (Konvertiert sowohl doppelte als auch einfache Anführungszeichen.)
	$message = htmlentities($message, ENT_QUOTES);
		
	//	Benutzer festlegen ob Gäste oder registerter Benutzer
	if ($userdata['user_id'] == ANONYMOUS)
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
		//	Daten verändert, werden diese beim Auslesen der
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
			$table_name = NEWS_COMMENTS_TABLE;
			$id_name	= 'news_id';
		break;
		case 'match':
			$table_name = MATCH_COMMENTS_TABLE;
			$id_name	= 'match_id';
		break;
		case 'training':
			$table_name = TRAINING_COMMENTS_TABLE;
			$id_name	= 'training_id';
		break;
		default:
			message_die(GENERAL_ERROR, 'No Table given', '', __LINE__, __FILE__);
		break;
	}
	
	//	Einfügen
	if ($mode == 'add')
	{
		$sql = 'INSERT INTO ' . $table_name . " ($id_name, poster_id, $sql_fields poster_ip, text, time_create, time_update)
			VALUES ('" . intval($id) . "', '" . intval($poster_id) . "', $sql_data '" . $poster_ip . "', '" . str_replace("\'", "''", $message) . "', '" . time() . "', 0)";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		//	geändert von UCP_MATCH_COMMENT auf Allgemeine Message der Logfunktion
		_log(LOG_USER, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_COMMENT, 'comment_' . $table);
	}
	
	return;
}
/*
function _comment_read($mode, $table_type, $type_id, $user_id)
{
	global $config, $settings, $lang, $db;
	global $userdata, $user_ip;
	
	//	Tabellen auswahl mit entsprechender ID
	switch ($table_type)
	{
		case 'news':
			$table_name = NEWS_COMMENTS_TABLE;
			$id_name	= 'news_id';
		break;
		case 'match':
			$table_name = MATCH_COMMENTS_READ_TABLE;
			$id_name	= 'match_id';
		break;
		case 'training':
			$table_name = TRAINING_COMMENTS_TABLE;
			$id_name	= 'training_id';
		break;
		default:
			message_die(GENERAL_ERROR, 'No Table given', '', __LINE__, __FILE__);
		break;
	}
	
	//	Einfügen
	if ($mode == 'add')
	{
		$sql = 'INSERT INTO ' . $table_name . " ($id_name, comment_match_id, user_id, read_time)
			VALUES ('" . intval($id) . "', '" . intval($poster_id) . "', $sql_data '" . $poster_ip . "', '" . time() . "')";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		//	geändert von UCP_MATCH_COMMENT auf Allgemeine Message der Logfunktion
		_log(LOG_USER, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_COMMENT, 'comment_' . $table);
	}
	
	return;
}

*/?>