<?php

function build_options($var)
{
	global $lang;
	
	$new_fields = '';
	
	foreach ( $var as $name => $vars )
	{
		if ( is_array($vars) )
		{
			foreach ( $vars as $_var => $_value )
			{
				if ( is_array($_value) )
				{
					foreach ( $_value as $_v1 => $_v2 )
					{
						$new_fields .= '<option value="' . $_v1 . '">' . sprintf($lang['STF_SELECT_FORMAT'], sprintf($lang[$_var], $lang[$_v2])) . "</option>\n";
					}
				}
				else
				{
					$new_fields .= '<option value="' . $_var . '">' . sprintf($lang['STF_SELECT_FORMAT'], $lang[$_value]) . "</option>\n";
				}
			}
		}
		else
		{
			$new_fields .= '<select name="' . $name . '"' . ($vars === false ? '' : ' onchange="' . $vars . '"') . '>';
		}
	}
	
	$new_fields .= '</select>';
	
	return $new_fields;
	
	#	$s_options .= '<select name="mode" onchange="setRequest(this.options[selectedIndex].value);">';
	#	$s_options .= '<option value="">' . sprintf($lang['STF_SELECT_FORMAT'], $lang['COMMON_SELECT_OPTION']) . "</option>\n";
	#	$s_options .= '<option value="uchange">' . sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_SELECT_PERMISSION']) . "</option>\n";
	#	$s_options .= '<option value="uranks">' . sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_SELECT_RANK']) . "</option>\n";
	#	$s_options .= '<option value="udefault">' . sprintf($lang['STF_SELECT_FORMAT'], $lang['notice_select_default']) . "</option>\n";
	#	$s_options .= '<option value="udelete">' . sprintf($lang['STF_SELECT_FORMAT'], $lang['COMMON_DELETE']) . "</option>\n";
	#	$s_options .= '</select>';
}

function _build_fields($key, $value)
{
	$fields = '';

	if (!is_array($value))
	{
		$fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
	}
	else
	{
		foreach ($value as $_key => $_value)
		{
		#	debug($_key, 'key');
			$fields .= _build_fields($key . '[' . $_key . ']', $_value);
		}
	}

	return $fields;
}

function build_fields($var)
{
	$new_fields = '';
	
	foreach ($var as $name => $vars)
	{
		$new_fields .= _build_fields($name, $vars);
	}
	
	return $new_fields;
}

/*
 * Prüft ob der Eintrag im Langarray vorhanden ist.
 */

function langs($lng)
{
	global $lang;
	
	return isset($lang[strtoupper($lng)]) ? $lang[strtoupper($lng)] : $lng;
}

/*
 * Idee von phpBB3
 */

function request($request_var, $request_type, $filter = '')
{
	$tmp = '';
	
	if ( is_array($request_var) )
	{
		if ( count($request_var) == 3 )
		{
			list($typ, $opt, $field) = $request_var;
		#	$tmp = ( $request == 'POST' ) ? $_POST[$typ][$opt][$field] : $_GET[$typ][$opt][$field];
			$tmp = ( isset($_POST[$typ][$opt][$field]) || isset($_GET[$typ][$opt][$field]) ) ? ( isset($_POST[$typ][$opt][$field]) ) ? $_POST[$typ][$opt][$field] : $_GET[$typ][$opt][$field] : '';
		#	$tmp = ( $request == 'POST' ) ? $_POST[$typ][$opt][$field] : $_GET[$typ][$opt][$field];
		}
		else
		{
			
			list($typ, $opt) = $request_var;
		#	$tmp = ( $request == 'POST' ) ? $_POST[$typ][$opt] : $_GET[$typ][$opt];
			$tmp = ( isset($_POST[$typ][$opt]) || isset($_GET[$typ][$opt]) ) ? ( isset($_POST[$typ][$opt]) ) ? $_POST[$typ][$opt] : $_GET[$typ][$opt] : '';
		#	$tmp = ( $request == 'POST' ) ? $_POST[$typ][$opt] : $_GET[$typ][$opt];
		
		#	debug($tmp, $typ);
		#	debug($tmp, $opt);
		}
	}
	else
	{
		$tmp = ( isset($_POST[$request_var]) || isset($_GET[$request_var]) ) ? (isset($_POST[$request_var])) ? $_POST[$request_var] : $_GET[$request_var] : '';
#		$tmp = ( $request == 'POST' ) ? $_POST[$request_var] : $_GET[$request_var];
	}
	
#	debug($tmp, $request_var, true);
	
	$var = $opt = '';
	
#	if ( $tmp != '' )
#	{
#	debug($request_type, $tmp);

	if ( is_array($tmp) )
	{
	#	if ( is_array($tmp) && ( count($tmp) >= 1 || ( $opt == 'user_month' || $typ == 'training' || $typ == 'match' ) ) )
	#	{
		#	debug($tmp, print_r($request_var));
		
	#	debug($tmp, 'tmp');
		
		foreach ( $tmp as $key => $value )
		{
			if ( is_array($value) )
			{
				$v_tmp = array();
				
				foreach ( $value as $k_key => $v_value )
				{
					if ( $request_type == INT )
					{
						$v_tmp[$key][] = (int) $v_value;
					}
					else if ( $request_type == TXT )
					{
						$v_tmp[$key][] = trim(htmlspecialchars($v_value, ENT_COMPAT, 'UTF-8'));
					}
					else
					{
						$v_tmp[$key][] = (is_numeric($v_value) ? (int) $v_value : trim(htmlspecialchars($v_value, ENT_COMPAT, 'UTF-8')));
					}
				}
				
				$var[$key] = $v_tmp[$key];
			}
			else
			{
				if ( $request_type == INT )
				{
					$_tmp[$key] = intval($value);
				}
				else if ( $request_type == TXT )
				{
					$_tmp[$key] = trim(htmlspecialchars($value, ENT_COMPAT, 'UTF-8'));
				}
				else
				{
					$_tmp[$key] = (is_numeric($value) ? (int) $value : trim(htmlspecialchars($value, ENT_COMPAT, 'UTF-8')));
				}
				
				$var[$key] = $_tmp[$key];
			}
		}
	}
	else
	{
		switch ( $request_type )
		{
			case INT: $var = ( isset($tmp) ) ? intval($tmp) : ''; break;
			case TYP: $var = ( isset($tmp) ) ? (is_numeric($tmp)) ? (int) $tmp : trim(htmlspecialchars($tmp, ENT_COMPAT, 'UTF-8')) : ''; break;
			case TXT: $var = ( isset($tmp) ) ? trim(htmlspecialchars($tmp, ENT_COMPAT, 'UTF-8')) : ''; break;
			case CLN: $var = ( isset($tmp) ) ? trim(htmlspecialchars($tmp, ENT_COMPAT, 'UTF-8')) : ''; break;
			case HTM: $var = ( isset($tmp) ) ? trim(htmlspecialchars(strip_tags($tmp), ENT_COMPAT, 'UTF-8')) : ''; break;
			case URL:
				
				if ( $tmp != '' )
				{
					if ( !preg_match('#^http[s]?:\/\/#i', $tmp) )
					{
						$tmp = 'http://' . $tmp;
					}
			
					if ( !preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $tmp) )
					{
						$tmp = '';
					}
				}
				
				break;
				
			case ARY:
					
				switch ( $filter )
				{
					case INT:	$var = ( isset($tmp) ) ? (int) $tmp : 0; break;
					case TXT:	$var = ( isset($tmp) ) ? (string) $tmp : ''; break;
					default:	$var = ( isset($tmp) ) ? (is_numeric($tmp) ? (int) $tmp : (string) $tmp) : ''; break;
				}
			
				break;
					
		}
	}

		/*
		switch ( $request_type )
		{
			//	int = Zahlen
			//	type = Mode/Confirm/Sort
			//	text = Texte
			//	clean = Texte ohne HTML Tags
			//	html = entfernt alle html tags
			///	array = Arrays ( URL / Apostroph ' )
			//	ulr = URL
			//	image = Image	not work

			case INT: $var = ( isset($tmp) ) ? intval($tmp) : ''; break;
			case TYP: $var = ( isset($tmp) ) ? (is_numeric($tmp)) ? intval($tmp) : trim(htmlspecialchars($tmp, ENT_COMPAT)) : ''; break;
			case TXT: $var = ( isset($tmp) ) ? trim(htmlspecialchars($tmp, ENT_COMPAT)) : ''; break;
			case CLN: $var = ( isset($tmp) ) ? trim(htmlspecialchars($tmp, ENT_COMPAT)) : ''; break;
			case HTM: $var = ( isset($tmp) ) ? trim(htmlspecialchars(strip_tags($tmp), ENT_COMPAT)) : ''; break;
			case ARY: $tmp_tmp = '';
							
					#		debug($typ, 'opt');
					#		debug($tmp, 'ary request!');
							
							if ( is_array($tmp) && ( count($tmp) >= 1 || ( $opt == 'user_month' || $typ == 'training' || $typ == 'match' ) ) )
							{
							#	debug($tmp, 'tmp', true);
								
								foreach ( $tmp as $tmp_key => $tmp_value )
								{
									$tmp_tmp[$tmp_key] = '';
									$tmp_var = '';
									
									if ( $tmp_value != '' )
									{
										switch ( $filter )
										{
											case 'int':	$tmp_var = (int) $tmp_value; break;
											case 'text':$tmp_var = trim(htmlspecialchars($tmp_value, ENT_COMPAT)); break;
											case 'url': if ( !preg_match('#^http[s]?:\/\/#i', $tmp_value) )
														{
															$tmp_var = 'http://' . $tmp_value;
														}
														
														if ( !preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $tmp_value) )
														{
															$tmp_var = '';
														}
														break;
											default: $tmp_var = trim(htmlspecialchars($tmp_value, ENT_COMPAT)); break;
										}
									}
									
									$tmp_tmp[$tmp_key] = $tmp_var;
								}
							}
							else
							{
								$tmp_tmp = $tmp;
							}
								
							$var = $tmp_tmp;
						#	debug($var, 'ary request!');
							
					break;
			case 'url':		if ( $tmp != "" )
							{
								if ( !preg_match('#^http[s]?:\/\/#i', $tmp) )
								{
									$var = 'http://' . $tmp;
								}
								
								if ( !preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $tmp) )
								{
									$var = '';
								}
							}
					break;	
					
			default:	$var = trim(htmlspecialchars($var, ENT_COMPAT)); break;
		}
		*/
#	}
#	else
#	{
#		$var = ( $request_type == INT ) ? 0 : '';
#	}
#	debug($var, $request_var, true);
#	debug($var, $request_var);
	return $var;
}

function request_file($request_var)
{
	global $_FILES;
	
#	debug($request_var, 'request_var');
	
	$var['temp'] = $_FILES[$request_var]['tmp_name'];
	$var['name'] = $_FILES[$request_var]['name'];
	$var['type'] = $_FILES[$request_var]['type'];
	$var['size'] = $_FILES[$request_var]['size'];
	$var['error'] = $_FILES[$request_var]['error'];
	
	if ( $var['error'] == '4' )
	{
		return false;
	}
	else
	{
		return $var;
	}
}

function request_files($request_var)
{
	global $_FILES;
	
	$var['temp'] = $_FILES[$request_var]['tmp_name'];
	$var['name'] = $_FILES[$request_var]['name'];
	$var['type'] = $_FILES[$request_var]['type'];
	$var['size'] = $_FILES[$request_var]['size'];
	$var['error'] = $_FILES[$request_var]['error'];
	
	foreach ( $var['error'] as $key => $value )
	{
		if ( $value == '4' )
		{
			return false;
		}
		else
		{
			return $var;
		}
	}
}

function add_lang($file)
{
	global $root_path, $userdata, $lang;
	
	/*
		@param: string	$file		example: games
	*/
	if ( defined('IN_ADMIN') )
	{
		if ( is_array($file) )
		{
			foreach ( $file as $val )
			{
				if ( file_exists("{$root_path}language/lang_{$userdata['user_lang']}/acp/{$val}.php") )
				{
					include("{$root_path}language/lang_{$userdata['user_lang']}/acp/{$val}.php");
				}
			}
		}
		else
		{
			if ( file_exists("{$root_path}language/lang_{$userdata['user_lang']}/acp/{$file}.php") )
			{
				include("{$root_path}language/lang_{$userdata['user_lang']}/acp/{$file}.php");
			}
		}
	}
	else
	{
		if ( file_exists("{$root_path}language/lang_{$userdata['user_lang']}/{$file}.php") )
		{
			include("{$root_path}language/lang_{$userdata['user_lang']}/{$file}.php");
		}
	}
}

function href($type, $file, $params, $text, $lng = '', $comment = false)
{
	global $lang, $images;
	
	$return = $url = '';
	$lng = strip_tags($lng);	/* 19.05.2013 hinzugefügt - entfernt html zeichen und stört damit nicht die ausgabe */
	
	if ( $params )
	{
		foreach ( $params as $k => $v )
		{
			$url[] = (is_array($v)) ? "$k=$v[0]" : "$k=$v";
		}
	}
	
#	$url	= '?' . implode('&amp;', $url);
#	$url	= '&amp;' . implode('&amp;', $url);
	$url	= is_array($url) ? '&amp;' . implode('&amp;', $url) : '';
	$txt	= strstr($type, 'img') ? ( isset($images[$text]) ? $images[$text] : $text ) : $text;
	$lng	= isset($lang[$lng]) ? $lang[$lng] : $lng;
	
	( $type == 'a_txt' ) ? list($txt, $lng) = array($lng, $txt) : '';
	
	$return = sprintf($lang[$type], check_sid($file . $url, true), $txt, $lng, $comment);
	
	return $return;
}

function img($type, $icon, $lng = '')
{
	global $lang, $images;
	
	$_lng	= isset($lang[$lng]) ? $lang[$lng] : $lng;
	
	$return = '';
	
	$return = sprintf($lang[$type], $images[$icon], $_lng);
	
	return $return;
}

function cal_string($entry, $css, $lng, $array, $viewer)
{
	$msg = '';
	
	$tbl_start = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
	$tbl_mid = '</td></tr><tr><td>';
	$tbl_end = '</td></tr></table>';
	
	$div_start	= '<div class="cal_list"><ul>';
	$div_middle	= '</ul><ul>';
	$div_end	= '</u></div>';
			
	if ( $viewer )
	{
		$msg .= ( $entry == '' ) ? $div_start : $div_middle;
		$msg .= ( !$css ) ? "<b>$lng</b>$div_middle" : "<span class=\"$css\">$lng</span>$div_middle";
		$msg .= implode('<br />', $array);
	}
	else
	{
		$msg .= ( $entry == '' ) ? '' : '<br />';
		$msg .= ( !$css ) ? "<b>$lng</b><br />" : "<span class=\"$css\">$lng</span><br />";
		$msg .= implode('<br />', $array);
	}
		
	return $msg;
}

function cut_cal($string, $length)
{
	$string = ( strlen($string) <= $length ) ? $string : substr($string, 0, ($length-3));
	
	return $string;
}

function cut_string($string, $length)
{
	/***

	@param string $string	enthï¿½lt den Titel
	@param int $length		enthï¿½lt die maximale Lï¿½nge
	
	@return string
	
	***/
	
	$string = ( strlen($string) <= $length ) ? $string : substr($string, 0, ($length-3)) . '...';
	
	return $string;
}

function random_password($minlength = 7, $maxlength = 14, $uselower = true, $useupper = false, $usenumbers = true, $usespecial = false)
{
	$charset = '';
	
	if ($useupper)
	{
		$charset .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}
    
	if ($usenumbers)
	{
		$charset .= '1234567890';
	}
	
	if ($usespecial)
	{
		$charset .= '~@#$%^*()_+-={}|][';
	}
	
	if (empty($charset) || $uselower)
	{
		$charset .= 'abcdefghijklmnopqrstuvwxyz';
	}
	
	$length = ($minlength > $maxlength) ? mt_rand($maxlength, $minlength) : mt_rand($minlength, $maxlength);
	
	$charsetlength = strlen($charset) - 1;
	
	$str = '';
	
	for ( $i=0; $i < $length; $i++ )
	{
		$str .= $charset[mt_rand(0, $charsetlength)];
	}
	
	return $str; 
}

function gen_userinfo(&$row, &$name, &$join, &$posts, &$comments)
{
	global $userdata;
	
	$name		= href('a_style', 'profile.php', array('mode' => 'view', 'id' => $row['user_id']), $row['user_color'], $row['user_name']);
	$join		= create_shortdate($userdata['user_dateformat'], isset($row['team_join']) ? $row['team_join'] : $row['user_regdate'], $userdata['user_timezone']);
	$posts		= $row['user_posts'];
	$comments	= $row['user_comments'];
}

function generate_user_info(&$row, $date_format, $group_mod, &$from, &$posts, &$joined, &$poster_avatar, &$profile_img, &$profile, &$search_img, &$search, &$pm_img, &$pm, &$email_img, &$email, &$www_img, &$www, &$icq_status_img, &$icq_img, &$icq, &$aim_img, &$aim, &$msn_img, &$msn, &$yim_img, &$yim)
{
	global $lang, $images, $config;

	$from = ( !empty($row['user_from']) ) ? $row['user_from'] : '&nbsp;';
	$joined = create_date($date_format, $row['user_regdate'], $config['default_timezone']);

	$posts = ( $row['user_posts'] ) ? $row['user_posts'] : 0;

	if ( !empty($row['user_viewemail']) || $group_mod )
	{
		$email_uri = ( $config['page_email_form'] ) ? check_sid('profile.php?mode=email&amp;' . POST_USER .'=' . $row['user_id']) : 'mailto:' . $row['user_email'];

		$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>';
		$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
	}
	else
	{
		$email_img = '&nbsp;';
		$email = '&nbsp;';
	}

	$temp_url = check_sid('profile.php?mode=viewprofile&amp;id=' . $row['user_id']);
	$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" border="0" /></a>';
	$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

	$temp_url = check_sid('privmsg.php?mode=post&amp;id=' . $row['user_id']);
	$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
	$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

	return;
}

//
// Get Userdata, $user can be user_name or user_id. If force_str is true, the user_name will be forced.
//
function get_userdata($user, $force_str = false)
{
	global $db;

	if ( !is_numeric($user) || $force_str )
	{
		$user = phpbb_clean_user_name($user);
	}
	else
	{
		$user = intval($user);
	}

	$sql = 'SELECT * FROM ' . USERS . ' WHERE ';
	$sql .= ( ( is_integer($user) ) ? 'user_id = ' . $user : 'user_name = "' .  str_replace("\'", "''", $user) . "'" ) . ' AND user_id <> ' . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	return ( $row = $db->sql_fetchrow($result) ) ? $row : false;
}

function get_profiledata($user_id)
{
	global $db;

	$sql = 'SELECT * FROM ' . PROFILE_DATA . ' WHERE user_id = ' . $user_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}

	return ( $row = $db->sql_fetchrow($result) ) ? $row : false;
}



function _cut($text)
{
	// Wï¿½rter mit mehr als 60 Zeichen werden ab dem 60. Zeichen um ein Leerzeichen ergï¿½nzt 
	// damit der Browser den Text umbrechen kann (, sonst wird das Layout zerstï¿½rt) 
	$max_word_lenght = 60; 
	// Die Lï¿½nge von Links ist grï¿½ï¿½er, da sie nur im Quelltext als 'lang' erscheinen 
	// Links werden spï¿½ter noch gesondert behandelt 
	$max_link_lenght = 200; 
	// Trennzeichen 
	$splitter = ' ';
	// Text in Zeilen aufteilen, sonst wï¿½rden Zeilenumbrï¿½che (\n) nicht als Worttrennung erkannt 
	$lines = explode("\n", $text);
	
	foreach ($lines as $key_line => $line)
	{
		// jede Zeile in Wï¿½rter aufteilen 
		$words = explode(' ', $line);
		// jedes Wort prï¿½fen
		
		foreach ($words as $key_word => $word)
		{
			// fï¿½r Links wird die maximale Lï¿½nge erhï¿½ht
			if (substr(strtolower($word), 0, 7)== 'http://' OR substr(strtolower($word), 0, 8)== 'https://' OR substr(strtolower($word), 0, 4)=='www.')
			{
				$max_lenght = $max_link_lenght; 
			}
			else
			{
				$max_lenght = $max_word_lenght;
			}
			$word = trim($word);
			
			// BB-Code Tags entfernen, da sie nicht zur Buchstabenlï¿½nge eines Wortes zï¿½hlen
			$word = preg_replace('/\[(.*)\]/Usi', '', $word); 
			
			if (strlen($word)>$max_lenght)
			{ 
				// Trennen des Wortes nach max_length Buchstaben
				$words[$key_word] = chunk_split($words[$key_word], $max_lenght, $splitter);
				
				// abziehen der Lï¿½nge des Trennzeichens, dieses wird am Ende automatisch
				// noch einmal eingefï¿½gt
				$length = strlen($words[$key_word])-strlen($splitter);
				$words[$key_word] = substr($words[$key_word],0,$length);
			}
		}
		// fï¿½gt die verï¿½nderten Wï¿½rter wieder zur Zeile als String zusammen
		$lines[$key_line] = implode(" ", $words);
	}
	// fï¿½gt Zeilen wieder zum gesamten Text als String zusammen 
	$text = implode("\n", $lines);
	
	return $text;
}

function log_add($type, $log, $message, $data = '')
{
	global $db, $userdata;
	
	$msg	= strtolower($message);
	$data	= serialize($data);
	
	$sql = "INSERT INTO " . LOGS . " SET
				log_type = '$type',
				log_time = " . time() . ",
				user_id = '" . $userdata['user_id'] . "',
				user_ip = '" . $userdata['session_ip'] . "',
				log_section = '$log',
				log_message = '$msg',
				log_data = '$data'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error ', '', __LINE__, __FILE__, $sql);
	}
}

/*
log_id, log_type, log_time, user_id, user_ip, log_section, log_message, log_data

type user, mod, admin
sektion news, teams usw
message langdaten erst kurz dann lang ;)
data array daten sowie alle titel und so weiter mal schauen wie das am besten geht
*/


/*
function get_db_stat($mode)
{
	global $db;

	switch( $mode )
	{
		case 'usercount':
			$sql = "SELECT COUNT(user_id) AS total
				FROM " . USERS . "
				WHERE user_id <> " . GUEST;
			break;

		case 'newestuser':
			$sql = "SELECT user_id, user_name
				FROM " . USERS . "
				WHERE user_id <> " . GUEST . "
				ORDER BY user_id DESC
				LIMIT 1";
			break;

		case 'postcount':
		case 'topiccount':
			$sql = "SELECT SUM(forum_topics) AS topic_total, SUM(forum_posts) AS post_total
				FROM " . FORUM;
			break;
	}

	if ( !($result = $db->sql_query($sql)) )
	{
		return false;
	}

	$row = $db->sql_fetchrow($result);

	switch ( $mode )
	{
		case 'usercount':
			return $row['total'];
			break;
		case 'newestuser':
			return $row;
			break;
		case 'postcount':
			return $row['post_total'];
			break;
		case 'topiccount':
			return $row['topic_total'];
			break;
	}

	return false;
}
*/

// added at phpBB 2.0.11 to properly format the user_name
function phpbb_clean_user_name($user_name)
{
	$user_name = substr(htmlspecialchars(str_replace("\'", "'", trim($user_name))), 0, 25);
	$user_name = phpbb_rtrim($user_name, "\\");
	$user_name = str_replace("'", "\'", $user_name);

	return $user_name;
}

/**
* This function is a wrapper for ltrim, as charlist is only supported in php >= 4.1.0
* Added in phpBB 2.0.18
*/
function phpbb_ltrim($str, $charlist = false)
{
	if ($charlist === false)
	{
		return ltrim($str);
	}
	
	$php_version = explode('.', PHP_VERSION);

	// php version < 4.1.0
	if ((int) $php_version[0] < 4 || ((int) $php_version[0] == 4 && (int) $php_version[1] < 1))
	{
		while ($str{0} == $charlist)
		{
			$str = substr($str, 1);
		}
	}
	else
	{
		$str = ltrim($str, $charlist);
	}

	return $str;
}

// added at phpBB 2.0.12 to fix a bug in PHP 4.3.10 (only supporting charlist in php >= 4.1.0)
function phpbb_rtrim($str, $charlist = false)
{
	if ($charlist === false)
	{
		return rtrim($str);
	}
	
	$php_version = explode('.', PHP_VERSION);

	// php version < 4.1.0
	if ((int) $php_version[0] < 4 || ((int) $php_version[0] == 4 && (int) $php_version[1] < 1))
	{
		while ($str{strlen($str)-1} == $charlist)
		{
			$str = substr($str, 0, strlen($str)-1);
		}
	}
	else
	{
		$str = rtrim($str, $charlist);
	}

	return $str;
}

/**
* Our own generator of random values
* This uses a constantly changing value as the base for generating the values
* The board wide setting is updated once per page if this code is called
* With thanks to Anthrax101 for the inspiration on this one
* Added in phpBB 2.0.20
*/
function dss_rand()
{
	global $db, $config, $dss_seeded;

	$val = $config['rand_seed'] . microtime();
	$val = md5($val);
	$config['rand_seed'] = md5($config['rand_seed'] . $val . 'a');
   
	if($dss_seeded !== true)
	{
		$sql = "UPDATE " . CONFIG . " SET
			config_value = '" . $config['rand_seed'] . "'
			WHERE config_name = 'rand_seed'";
		
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'Unable to reseed PRNG', '', __LINE__, __FILE__, $sql);
		}

		$dss_seeded = true;
	}

	return substr($val, 4, 16);
}

/*
//
// Get Userdata, $user can be user_name or user_id. If force_str is true, the user_name will be forced.
//
function get_userdata($user, $force_str = false)
{
	global $db;

	if (!is_numeric($user) || $force_str)
	{
		$user = phpbb_clean_user_name($user);
	}
	else
	{
		$user = intval($user);
	}

	$sql = "SELECT *
		FROM " . USERS . " 
		WHERE ";
	$sql .= ( ( is_integer($user) ) ? "user_id = $user" : "user_name = '" .  str_replace("\'", "''", $user) . "'" ) . " AND user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'Tried obtaining data for a non-existent user', '', __LINE__, __FILE__, $sql);
	}

	return ( $row = $db->sql_fetchrow($result) ) ? $row : false;
}
*/

//
// Initialise user settings on page load
function init_userprefs($userdata)
{
	global $config, $theme, $images;
	global $template, $lang, $root_path, $db;
	global $nav_links;
	
	if ( $userdata['user_id'] != ANONYMOUS )
	{
		if ( !empty($userdata['user_lang']))
		{
			$default_lang = phpbb_ltrim(basename(phpbb_rtrim($userdata['user_lang'])), "'");
		}

		if ( !empty($userdata['user_dateformat']) )
		{
			$config['default_dateformat'] = $userdata['user_dateformat'];
		}

		if ( isset($userdata['user_timezone']) )
		{
			$config['default_timezone'] = $userdata['user_timezone'];
		}
	}
	else
	{
		$default_lang = phpbb_ltrim(basename(phpbb_rtrim($config['default_lang'])), "'");
	}

	if ( !file_exists(@cms_realpath($root_path . 'language/lang_' . $default_lang . '/lang_main.php')) )
	{
		if ( $userdata['user_id'] != ANONYMOUS )
		{
			// For logged in users, try the board default language next
			$default_lang = phpbb_ltrim(basename(phpbb_rtrim($config['default_lang'])), "'");
		}
		else
		{
			// For guests it means the default language is not present, try english
			// This is a long shot since it means serious errors in the setup to reach here,
			// but english is part of a new install so it's worth us trying
			$default_lang = 'english';
		}

		if ( !file_exists(@cms_realpath($root_path . 'language/lang_' . $default_lang . '/lang_main.php')) )
		{
			message(CRITICAL_ERROR, 'Could not locate valid language pack');
		}
	}

	// If we've had to change the value in any way then let's write it back to the database
	// before we go any further since it means there is something wrong with it
	if ( $userdata['user_id'] != ANONYMOUS && $userdata['user_lang'] !== $default_lang )
	{
		$sql = 'UPDATE ' . USERS . "
			SET user_lang = '" . $default_lang . "'
			WHERE user_lang = '" . $userdata['user_lang'] . "'";

		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'Could not update user language info');
		}

		$userdata['user_lang'] = $default_lang;
	}
	elseif ( $userdata['user_id'] == ANONYMOUS && $config['default_lang'] !== $default_lang )
	{
		$sql = 'UPDATE ' . CONFIG . "
			SET config_value = '" . $default_lang . "'
			WHERE config_name = 'default_lang'";

		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'Could not update user language info');
		}
	}

	$config['default_lang'] = $default_lang;

	include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_main.php');
#	include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_newsletter.php');
#	include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_teamspeak.php');
#	include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_contact.php');
#	include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_ucp.php');
#	include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_imprint.php');
	
	if ( file_exists($root_path . 'language/lang_' . $config['default_lang'] . '/lang_bugtracker.php') )
	{
		include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_bugtracker.php');
	}
	
	if ( defined('IN_ADMIN') )
	{
		if ( !file_exists(@cms_realpath($root_path . 'language/lang_' . $config['default_lang'] . '/acp/common.php')) )
		{
			$config['default_lang'] = 'german';
		}
		
	#	if ( file_exists($root_path . 'language/lang_' . $config['default_lang'] . '/lang_bugtracker.php') )
	#	{
	#		include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_bugtracker.php');
	#	}
		
	#	include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_admin.php');
	#	include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_adm.php');
	#	include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_acp.php');
		include($root_path . 'language/lang_' . $config['default_lang'] . '/acp/common.php');
	}
	
	board_disable();

	//
	// Set up style
	//
	if ( !$config['override_user_style'] )
	{
		if ( $userdata['user_id'] != ANONYMOUS && $userdata['user_style'] > 0 )
		{
			if ( $theme = setup_style($userdata['user_style']) )
			{
				return;
			}
		}
	}

	$theme = setup_style($config['default_style']);

	//
	// Mozilla navigation bar
	// Default items that should be valid on all pages.
	// Defined here to correctly assign the Language Variables
	// and be able to change the variables within code.
	//
	$nav_links['top'] = array ( 
		'url' => check_sid($root_path . 'index.php'),
		'title' => sprintf($lang['Forum_Index'], $config['page_name'])
	);
	$nav_links['search'] = array ( 
		'url' => check_sid($root_path . 'search.php'),
		'title' => $lang['Search']
	);
	$nav_links['help'] = array ( 
		'url' => check_sid($root_path . 'faq.php'),
		'title' => $lang['FAQ']
	);
	$nav_links['author'] = array ( 
		'url' => check_sid($root_path . 'memberlist.php'),
		'title' => $lang['Memberlist']
	);
	
	return;
}

function setup_style($style)
{
	global $db, $config, $template, $images, $root_path;

	$sql = 'SELECT * FROM ' . THEMES . ' WHERE themes_id = ' . (int) $style;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'Could not query database for theme info');
	}

	if ( !($row = $db->sql_fetchrow($result)) )
	{
		// We are trying to setup a style which does not exist in the database
		// Try to fallback to the board default (if the user had a custom style)
		// and then any users using this style to the default if it succeeds
		if ( $style != $config['default_style'])
		{
			$sql = 'SELECT *
				FROM ' . THEMES . '
				WHERE themes_id = ' . (int) $config['default_style'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'Could not query database for theme info');
			}

			if ( $row = $db->sql_fetchrow($result) )
			{
				$db->sql_freeresult($result);

				$sql = 'UPDATE ' . USERS . '
					SET user_style = ' . (int) $config['default_style'] . "
					WHERE user_style = $style";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'Could not update user theme info');
				}
			}
			else
			{
				message(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]");
			}
		}
		else
		{
			message(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]");
		}
	}

	$template_path = 'templates/' ;
	$template_name = $row['template_name'] ;

	$template = new Template($root_path . $template_path . $template_name);
	
	if ( defined('IN_ADMIN') )
	{
		$template->set_rootdir($root_path . 'admin/style');
	}
	
#	if ( defined('IN_ADMIN') )
#	{
#		$template->set_rootdir($root_path . 'admin/style');
#	}
	
	if ( $template )
	{
		$current_template_path = $template_path . $template_name;
		@include($root_path . $template_path . $template_name . '/' . $template_name . '.cfg');

		if ( !defined('TEMPLATE_CONFIG') )
		{
			message(CRITICAL_ERROR, "Could not open $template_name template config file", '', __LINE__, __FILE__);
		}

		$img_lang = ( file_exists(@cms_realpath($root_path . $current_template_path . '/images/lang_' . $config['default_lang'])) ) ? $config['default_lang'] : 'english';

		while( list($key, $value) = @each($images) )
		{
			if ( !is_array($value) )
			{
				$images[$key] = str_replace('{LANG}', 'lang_' . $img_lang, $value);
			}
		}
	}

	return $row;
}

function encode_ip($dotquad_ip)
{
	if (function_exists('filter_var'))
	{
		if (filter_var($dotquad_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false)
		{
			// ipv6 - "::1" => "0000000000000000000000000001"
			$new_ip = '';
			if(strstr($dotquad_ip,"::" ))
			{
				$ip_sep = array();
				$e = explode(":", $dotquad_ip);
				$s = 8-count($e);
				
				foreach($e as $value)
				{
					if ($value == '')
					{
						for($i = 0; $i <= $s; $i++) $ip_sep[] = 0;
					}
					else
					{
						$ip_sep[] = $value;
					}
				}
			}
			else
			{
				$ip_sep = explode(':', $dotquad_ip);
			}
			for ($i = 0; $i <= 7; $i++) $new_ip .= str_pad($ip_sep[$i], 4, '0', STR_PAD_LEFT);
			return $new_ip;
		}
		else if (filter_var($dotquad_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false)
		{
			// ipv4 - "127.0.0.1" => "7F000001"
			$ip_sep = explode('.', $dotquad_ip);
			return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
	   }
	}
	else
	{
		if (preg_match('/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/', $dotquad_ip))
		{
			// ipv6 - "::1" => "0000000000000000000000000001"
			$new_ip = '';
			if(strstr($dotquad_ip,"::" ))
			{
				$ip_sep = array();
				$e = explode(":", $dotquad_ip);
				$s = 8-count($e);
				
				foreach($e as $value)
				{
					if ($value == '')
					{
						for($i = 0; $i <= $s; $i++) $ip_sep[] = 0;
					}
					else
					{
						$ip_sep[] = $value;
					}
				}
			}
			else
			{
				$ip_sep = explode(':', $dotquad_ip);
			}
			for ($i = 0; $i <= 7; $i++) $new_ip .= str_pad($ip_sep[$i], 4, '0', STR_PAD_LEFT);
			return $new_ip;
		}
		else if (preg_match('/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $dotquad_ip))
		{
			// ipv4 - "127.0.0.1" => "7F000001"
			$ip_sep = explode('.', $dotquad_ip);
			return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
	   }
	
	}
}

function decode_ip($int_ip)
{
   if (strlen($int_ip) == 32)
   {
		// ipv6 - "0000000000000000000000000001" => "::1"
		$hexipbang = array();
		
		if (function_exists('str_split'))
		{
			$e = str_split($int_ip, 4);
		}
		else
		{
			$e = explode(':', chunk_split($int_ip, 4, ':'));
		}
		
		foreach($e as $key => $value)
		{
			$value = ltrim($value, '0');
			$hexipbang[] = $value ? $value : '0';
		}
		
		$new_ip = implode(':', $hexipbang);
		if (preg_match_all('/:(0:)+/s', $new_ip, $zeros, PREG_PATTERN_ORDER))
		{
			rsort($zeros[0], SORT_STRING);
			$new_ip = str_replace($zeros[0][0], '::', $new_ip);
		}
		return $new_ip;
	}
	else if (strlen($int_ip) == 8)
	{
		// ipv4 - "7F000001" => "127.0.0.1"
		$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
		return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
	}
}

function iptoint($userip)
{
	return ip2long(decode_ip("$userip"));
}

function inttoip($int)
{
	return long2ip(encode_ip("$int"));
}

//
// Create date/time from format and timezone
//
function create_date($format, $gmepoch, $tz)
{
	global $config, $lang, $userdata;
	static $translate;
	
	if ( empty($translate) && $config['default_lang'] != 'english' )
	{
		@reset($lang['datetime']);
		while ( list($match, $replace) = @each($lang['datetime']) )
		{
			$translate[$match] = $replace;
		}
	}
	
	return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * ($tz+date("I", $gmepoch)))), $translate) : @gmdate($format, $gmepoch + (3600 * ($tz+date("I", $gmepoch))));
//	$thetime = ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * ($tz+date("I", $gmepoch)))), $translate) : @gmdate($format, $gmepoch + (3600 * ($tz+date("I", $gmepoch))));
//	
//	$L_Today = 'Heute um ';
//	$L_Yesterday = 'Gestern um';
//	$L_Tomorrow = 'Morgen um';
//	$time_format = "H:i";// - hour:minute am/pm
//	
//	$date = getdate();
//	$today = $date['mday'];
//	$month = $date['mon'];
//	$year = $date['year'];
//
//	$forum_date_today = @gmdate ("d", $gmepoch);
//	$forum_date_month = @gmdate ("m", $gmepoch);
//	$forum_date_year = @gmdate ("Y", $gmepoch);
//	
//	if ($forum_date_today == $today && $forum_date_month == $month && $forum_date_year == $year)
//		$thetime = $L_Today . @gmdate ($time_format, $gmepoch);//today
//	
//	else
//	if ($today != 1 && $forum_date_today == ($today-1) && $forum_date_month == $month && $forum_date_year == $year)
//		$thetime = $L_Yesterday . @gmdate ($time_format, $gmepoch);//yesterday
//	
//	else
//	if ($today != 1 && $forum_date_today == ($today+1) && $forum_date_month == $month && $forum_date_year == $year)
//		$thetime = $L_Tomorrow . @gmdate ($time_format, $gmepoch);//yesterday
//		
//	else
//	//if today is 1 and the month is not 1, then we have to check how many days in the previews month
//	//and then set $yesterday to the last day in the previews month
//	if ($today == 1 && $month != 1)
//	{
//	$yesterday = date ("t", mktime(0,0,0,($month-1),1,$year));//returns how many days in the previews month
//	if ($forum_date_today == $yesterday && $forum_date_month == ($month-1) && $forum_date_year == $year)
//		$thetime = $L_Yesterday . @gmdate ($time_format, $gmepoch);//yesterday
//	}
//	else 
//	//if we are in the first day in the year
//	if ($today == 1 && $month == 1)
//	{
//	$yesterday = date ("t", mktime(0,0,0,12,1,($year -1)));
//	if ($forum_date_today == $yesterday && $forum_date_month == 12 && $forum_date_year == ($year-1))
//		$thetime = $L_Yesterday . @gmdate ($time_format, $gmepoch);//yesterday
//	}
//
//   	return ($thetime);
}

function create_shortdate($user_format, $date, $user_zone)
{
	global $userdata, $lang;
	
	//
	// MOD - TODAY AT - BEGIN
	// PARSE DATEFORMAT TO GET TIME FORMAT 
	//
	$time_reg = '/([gh][[:punct:][:space:]]{1,2}[i][[:punct:][:space:]]{0,2}[a]?[[:punct:][:space:]]{0,2}[S]?)/i';
	//eregi($time_reg, $config['default_dateformat'], $regs);
	preg_match($time_reg, $userdata['user_dateformat'], $regs);
	$userdata['default_timeformat'] = $regs[1];
	unset($time_reg);
	unset($regs);
	
	//
	// GET THE TIME TODAY AND YESTERDAY
	//
	$today_ary = explode('|', create_date('m|d|Y', time(), $userdata['user_timezone']));
	$zeit = localtime(time() , 1);
	
	if ( $zeit['tm_isdst'] )
	{
		$userdata['time_today'] = gmmktime(0 - $userdata['user_timezone'] - date("I"),0,0,$today_ary[0],$today_ary[1],$today_ary[2]);
	}
	else
	{
		$userdata['time_today'] = gmmktime(0 - $userdata['user_timezone'],0,0,$today_ary[0],$today_ary[1],$today_ary[2]);
	}
	
	#$userdata['time_today'] = gmmktime(0 - $userdata['user_timezone'] - $userdata['dstime'],0,0,$today_ary[0],$today_ary[1],$today_ary[2]);
	$userdata['time_yesterday'] = $userdata['time_today'] - 86400;
	$userdata['time_tomorrow'] = $userdata['time_today'] + 86400;
	unset($today_ary);
	
	$return = create_date($user_format, $date, $user_zone);
	
	if ( $date > $userdata['time_yesterday'] )
	{
		$return = sprintf($lang['at_yesterday'], create_date($userdata['default_timeformat'], $date, $user_zone));
	}
	
	if ( $date > $userdata['time_today'] )
	{
		$return = sprintf($lang['at_today'], create_date($userdata['default_timeformat'], $date, $user_zone));
	}
	
	if ( $date > $userdata['time_tomorrow'] )
	{
		$return = sprintf($lang['at_tomorrow'], create_date($userdata['default_timeformat'], $date, $user_zone)); 
	}
	
	if ( $date > $userdata['time_tomorrow']+86400 )
	{
		$return = create_date($user_format, $date, $user_zone); 
	}
	
	return $return;
}

//
// Pagination routine, generates
// page number sequence
//
function generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = TRUE)
{
	global $lang;

	$total_pages = ceil($num_items/$per_page);

	if ( !( $num_items > 0 ) )
	{
		return '';
	}

	$on_page = floor($start_item / $per_page) + 1;

	$page_string = '';
	if ( $total_pages > 10 )
	{
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

		for($i = 1; $i < $init_page_max + 1; $i++)
		{
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . check_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
			if ( $i <  $init_page_max )
			{
				$page_string .= ", ";
			}
		}

		if ( $total_pages > 3 )
		{
			if ( $on_page > 1  && $on_page < $total_pages )
			{
				$page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';

				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

				for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
				{
					$page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . check_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
					if ( $i <  $init_page_max + 1 )
					{
						$page_string .= ', ';
					}
				}

				$page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
			}
			else
			{
				$page_string .= ' ... ';
			}

			for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
			{
				$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>'  : '<a href="' . check_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
				if( $i <  $total_pages )
				{
					$page_string .= ", ";
				}
			}
		}
	}
	else
	{
		for($i = 1; $i < $total_pages + 1; $i++)
		{
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . check_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
			if ( $i <  $total_pages )
			{
				$page_string .= ', ';
			}
		}
	}

	if ( $add_prevnext_text )
	{
		if ( $on_page > 1 )
		{
			$page_string = ' <a href="' . check_sid($base_url . "&amp;start=" . ( ( $on_page - 2 ) * $per_page ) ) . '">' . $lang['Previous'] . '</a>&nbsp;&nbsp;' . $page_string;
		}

		if ( $on_page < $total_pages )
		{
			$page_string .= '&nbsp;&nbsp;<a href="' . check_sid($base_url . "&amp;start=" . ( $on_page * $per_page ) ) . '">' . $lang['Next'] . '</a>';
		}

	}

#	$page_string = $lang['Goto_page'] . ' ' . $page_string;

	return $page_string;
}

//
// This does exactly what preg_quote() does in PHP 4-ish
// If you just need the 1-parameter preg_quote call, then don't bother using this.
//
function phpbb_preg_quote($str, $delimiter)
{
	$text = preg_quote($str);
	$text = str_replace($delimiter, '\\' . $delimiter, $text);
	
	return $text;
}

//
// Obtain list of naughty words and build preg style replacement arrays for use by the
// calling script, note that the vars are passed as references this just makes it easier
// to return both sets of arrays
//
function obtain_word_list(&$orig_word, &$replacement_word)
{
	global $db;

	//
	// Define censored word matches
	//
	$sql = "SELECT word, replacement
		FROM  " . WORDS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'Could not get censored words from database', '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		do 
		{
			$orig_word[] = '#\b(' . str_replace('\*', '\w*?', preg_quote($row['word'], '#')) . ')\b#i';
			$replacement_word[] = $row['replacement'];
		}
		while ( $row = $db->sql_fetchrow($result) );
	}

	return true;
}

//
// This is general replacement for die(), allows templated
// output in users (or default) language, etc.
//
// $msg_code can be one of these constants:
//
// GENERAL_MESSAGE : Use for any simple text message, eg. results 
// of an operation, authorisation failures, etc.
//
// GENERAL ERROR : Use for any error which occurs _AFTER_ the 
// common.php include and session code, ie. most errors in 
// pages/functions
//
// CRITICAL_MESSAGE : Used when basic config data is available but 
// a session may not exist, eg. banned users
//
// CRITICAL_ERROR : Used when config data cannot be obtained, eg
// no database connection. Should _not_ be used in 99.5% of cases
//
function message($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $template, $config, $settings, $theme, $lang, $root_path, $nav_links, $gen_simple_header, $images;
	global $userdata, $user_ip, $session_length;
	global $starttime;
	
	static $msg_history;
	
	if ( !isset($msg_history) )
	{
		$msg_history = array();
	}
	
	$msg_history[] = array(
		'msg_code'	=> $msg_code,
		'msg_text'	=> $msg_text,
		'msg_title'	=> $msg_title,
		'err_line'	=> $err_line,
		'err_file'	=> $err_file,
		'sql'		=> $sql
	);
	
	if ( defined('HAS_DIED') )
	{
	//	
		//
		// This message is printed at the end of the report.
		// Of course, you can change it to suit your own needs. ;-)
		//
		$custom_error_message = 'Please, contact the %swebmaster%s. Thank you.';
		if ( !empty($config) && !empty($config['board_email']) )
		{
			$custom_error_message = sprintf($custom_error_message, '<a href="mailto:' . $config['board_email'] . '">', '</a>');
		}
		else
		{
			$custom_error_message = sprintf($custom_error_message, '', '');
		}
#		echo "<b>Critical Error!</b><br>\nmessage() was called multiple times.<br>&nbsp;<hr />";
		echo "<html>\n<body>\n<b>Critical Error!</b><br>\nmessage() was called multiple times.<br>&nbsp;<hr />";
		for( $i = 0; $i < count($msg_history); $i++ )
		{
			echo '<b>Error #' . ($i+1) . "</b>\n<br>\n";
			if( !empty($msg_history[$i]['msg_title']) )
			{
				echo '<b>' . $msg_history[$i]['msg_title'] . "</b>\n<br>\n";
			}
			echo $msg_history[$i]['msg_text'] . "\n<br><br>\n";
			if( !empty($msg_history[$i]['err_line']) )
			{
				echo '<b>Line :</b> ' . $msg_history[$i]['err_line'] . '<br><b>File :</b> ' . $msg_history[$i]['err_file'] . "</b>\n<br>\n";
			}
			if( !empty($msg_history[$i]['sql']) )
			{
				echo '<b>SQL :</b> ' . $msg_history[$i]['sql'] . "\n<br>\n";
			}
			echo "&nbsp;<hr />\n";
		}
		echo $custom_error_message . '<hr /><br clear="all">';
		
		if ( defined('IN_ADMIN') )
		{
	#		die("</td></tr></table></div><div id=\"page-footer\"></div></div><div class=\"border-bottom\"><span><span></span></span></div></div></div></div></div></div></div></div></body></html>");
		}
		
		die("</body>\n</html>");
	}
	
	define('HAS_DIED', true);
	

	$sql_store = $sql;
	
	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent 
	// subsequent queries from overwriting the status of sql_error()
	//
	if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ( $sql_error['message'] != '' )
		{
			$debug_text .= '<br><br>SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ( $sql_store != '' )
		{
			$debug_text .= "<br><br>$sql_store";
		}

		if ( $err_line != '' && $err_file != '' )
		{
			$debug_text .= '<br><br>Line : ' . $err_line . '<br>File : ' . basename($err_file);
		}
	}

	if( empty($userdata) && ( $msg_code == GENERAL_MESSAGE || $msg_code == GENERAL_ERROR ) )
	{
		$userdata = session_pagestart($user_ip, PAGE_INDEX);
		init_userprefs($userdata);
	}

	//
	// If the header hasn't been output then do it
	//
	if ( !defined('HEADER_INC') && $msg_code != CRITICAL_ERROR )
	{
		if ( empty($lang) )
		{
			if ( !empty($config['default_lang']) )
			{
				include($root_path . 'language/lang_' . $config['default_lang'] . '/lang_main.php');
			}
			else
			{
				include($root_path . 'language/lang_german/lang_main.php');
			}
		}

		if ( empty($template) || empty($theme) )
		{
			$theme = setup_style($config['default_style']);
		}

		//
		// Load the Page Header
		//
		if ( !defined('IN_ADMIN') )
		{
			main_header();
		}
		else
		{
			include_once($root_path . 'admin/page_header_admin.php');
			include_once($root_path . 'includes/acp/acp_functions.php');
		}
	}

	switch($msg_code)
	{
		case GENERAL_MESSAGE:
			if ( $msg_title == '' )
			{
				$msg_title = $lang['Information'];
				$class = 'agree';
			}
			break;

		case CRITICAL_MESSAGE:
			if ( $msg_title == '' )
			{
				$msg_title = $lang['Critical_Information'];
			}
			break;

		case GENERAL_ERROR:
			if ( $msg_text == '' )
			{
				$msg_text = $lang['An_error_occured'];
				$class = 'disagree';
			}

			if ( $msg_title == '' )
			{
				$msg_title = $lang['General_Error'];
				$class = 'disagree';
			}
			break;

		case CRITICAL_ERROR:
			//
			// Critical errors mean we cannot rely on _ANY_ DB information being
			// available so we're going to dump out a simple echo'd statement
			//
			include($root_path . 'language/lang_german/lang_main.php');

			if ( $msg_text == '' )
			{
				$msg_text = $lang['A_critical_error'];
			}

			if ( $msg_title == '' )
			{
				$msg_title = 'CMS : <b>' . $lang['Critical_Error'] . '</b>';
			}
			break;
	}
	
	if ($err_line != '' && $err_file != '')
	{
		$error_time = time();
		$error_userid = $userdata['user_id'];
		$error_sql_code = $sql_error['code'];
		
		$error_sql_text  = ($sql_error['message']);
		$error_sql_text  = str_replace('\\', '\\\\', $error_sql_text);
		$error_sql_text  = str_replace('\'', '\\\'', $error_sql_text);
		
		$error_sql_store  = $sql_store;
		$error_sql_store  = str_replace('\\', '\\\\', $error_sql_store);
		$error_sql_store  = str_replace('\'', '\\\'', $error_sql_store);
		
		$error_file  = $err_file;
		$error_file  = str_replace('\\', '\\\\', $error_file);
		$error_file  = str_replace('\'', '\\\'', $error_file);
		
		$sql = "INSERT INTO  " . ERROR . " (error_sql_code, error_userid, error_file_line, error_time, error_sql_text, error_file, error_sql_store, error_msg_title, error_msg_text) VALUES ('$error_sql_code', '$error_userid', '$err_line', '$error_time', '$error_sql_text', '$error_file', '$error_sql_store', '$msg_title', '$msg_text')";
		
		if (!($result = $db->sql_query($sql)))
		{
			$error_message = '<br><br><b>Error Message not saved in Database</b>';
		}
		else
		{
			$error_message = '<br><br><b>Error Message saved in Database</b>';
		}
    }
	//
	// Add on DEBUG info if we've enabled debug mode and this is an error. This
	// prevents debug info being output for general messages should DEBUG be
	// set TRUE by accident (preventing confusion for the end user!)
	//
	if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
	{
		if ( $debug_text != '' )
		{
			$msg_text = $msg_text . '<br><br><b><u>DEBUG MODE</u></b>' . $debug_text . $error_message;
		}
	}

	if ( $msg_code != CRITICAL_ERROR )
	{
		if ( !empty($lang[$msg_text]) )
		{
			$msg_text = $lang[$msg_text];
		}

		if ( !defined('IN_ADMIN') )
		{
			$template->set_filenames(array('message_body' => 'message_body.tpl'));
		}
		else
		{
			$template->set_filenames(array('message_body' => 'info_message.tpl'));
		}

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $msg_title,
			'MESSAGE_TEXT' => $msg_text,
			'CLASS' => (!empty($class)) ? $class : 'normal',
			'BACK' => (!empty($back)) ? $lang['back'] : ''
		));
		$template->pparse('message_body');

		if ( !defined('IN_ADMIN') )
		{
			main_footer();
		}
		else
		{
			acp_footer();
		}
	}
	else
	{
		echo "<html>\n<body>\n" . $msg_title . "\n<br><br>\n" . $msg_text . "</body>\n</html>";
	}

	exit;
}

//
// This function is for compatibility with PHP 4.x's realpath()
// function.  In later versions of PHP, it needs to be called
// to do checks with some functions.  Older versions of PHP don't
// seem to need this, so we'll just return the original value.
// dougk_ff7 <October 5, 2002>
function cms_realpath($path)
{
	global $root_path;

	return (!@function_exists('realpath') || !@realpath($root_path . 'includes/functions.php')) ? $path : @realpath($path);
}

function redirect($url)
{
	global $db, $config;

	if (!empty($db))
	{
		$db->sql_close();
	}

	if (strstr(urldecode($url), "\n") || strstr(urldecode($url), "\r") || strstr(urldecode($url), ';url'))
	{
		message(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
	}

	$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
	$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '';
	$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['page_path']));
	$script_name = ($script_name == '') ? $script_name : '/' . $script_name;
	$url = preg_replace('#^\/?(.*?)\/?$#', '/\1', trim($url));
	$url = str_replace('&amp;', '&', $url);

	// Redirect via an HTML form for PITA webservers
	if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
	{
		header('Refresh: 0; URL=' . $server_protocol . $server_name . $server_port . $script_name . $url);
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><meta http-equiv="refresh" content="0; url=' . $server_protocol . $server_name . $server_port . $script_name . $url . '"><title>Redirect</title></head><body><div align="center">If your browser does not support meta redirection please click <a href="' . $server_protocol . $server_name . $server_port . $script_name . $url . '">HERE</a> to be redirected</div></body></html>';
		exit;
	}
	
	// Behave as per HTTP/1.1 spec for others
	header('Location:' . $server_protocol . $server_name . $server_port . $script_name . $url);
	exit;
}

function board_disable()
{
	global $config, $lang, $userdata;

	// avoid multiple function calls
	static $called = false;
	if ($called == true)
	{
		return;
	}
	$called = true;

	if ($config['page_disable'] && !defined('IN_ADMIN') && !defined('IN_LOGIN'))
	{
		$disable_mode = explode(',', $config['page_disable_mode']);
		$user_level = ($userdata['user_id'] == ANONYMOUS) ? ANONYMOUS : $userdata['user_level'];

		if (in_array($user_level, $disable_mode))
		{
			$disable_message = (!empty($config['page_disable_msg'])) ? htmlspecialchars($config['page_disable_msg']) : $lang['Board_disable'];
			message(GENERAL_MESSAGE, str_replace("\n", '<br>', $disable_message));
		}
		else
		{
			define('PAGE_DISABLE', true);
		}
	}
}



function rating_bar($id, $units = '', $static = '', $rating_unitwidth = 30)
{
	global $db;
	
	//set some variables
	$ip = $_SERVER['REMOTE_ADDR'];
	if (!$units) {$units = 10;}
	if (!$static) {$static = FALSE;}
	// get votes, values, ips for the current rating bar
	$sql = "SELECT * FROM " . RATE . " WHERE rate_type_id = '$id'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrowset($result);
	
#	if ( !$row )
#	{
#		$sql = "INSERT INTO " . RATE . " SET rate_type_id = '$id', rate_type = 1";
#		if ( !($result = $db->sql_query($sql)) )
#		{
#			message(GENERAL_ERROR, 'SQL Error ', '', __LINE__, __FILE__, $sql);
#		}
#	}
#	else
#	{
#		i
#	}

	if ( count($row) < 1 )
	{
		$count = 0;
	}
	else
	{
		$count = count($row); //how many votes total
	}
		
#	$query=mysql_query("SELECT total_votes, total_value, used_ips FROM $rating_dbname.$rating_tableName WHERE id='$id' ")or die(" Error: ".mysql_error());
	
	// insert the id in the DB if it doesn't exist already
	// see: http://www.masugadesign.com/the-lab/scripts/unobtrusive-ajax-star-rating-bar/#comment-121
#	if (mysql_num_rows($query) == 0)
#	{
#		$sql = "INSERT INTO $rating_dbname.$rating_tableName (`id`,`total_votes`, `total_value`, `used_ips`) VALUES ('$id', '0', '0', '')";
#		$result = mysql_query($sql);
#	}
#	
#	$numbers=mysql_fetch_assoc($query);
#	
#	if ( count($row) < 1)
#	{
#		$count = 0;
#	}
#	else
#	{
#		$count=$numbers['total_votes']; //how many votes total
#	}
	
	$current_rating = count($row); //total number of rating added together and stored
	$tense=($count==1) ? "vote" : "votes"; //plural form votes/vote
	
	// determine whether the user has voted, so we know how to draw the ul/li
#	$voted=mysql_num_rows(mysql_query("SELECT used_ips FROM $rating_dbname.$rating_tableName WHERE used_ips LIKE '%".$ip."%' AND id='".$id."' "));

	if ( $row )
	{
		
	}
	else
	{
		$voted = false;
	}
	
	// now draw the rating bar
	$rating_width = @number_format($current_rating/$count,2)*$rating_unitwidth;
	$rating1 = @number_format($current_rating/$count,1);
	$rating2 = @number_format($current_rating/$count,2);
	
	if ($static == 'static')
	{
		$static_rater = array();
		$static_rater[] .= "\n".'<div class="ratingblock">';
		$static_rater[] .= '<div id="unit_long'.$id.'">';
		$static_rater[] .= '<ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
		$static_rater[] .= '<li class="current-rating" style="width:'.$rating_width.'px;">Currently '.$rating2.'/'.$units.'</li>';
		$static_rater[] .= '</ul>';
		$static_rater[] .= '<p class="static">'.$id.'. Rating: <strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.' cast) <em>This is \'static\'.</em></p>';
		$static_rater[] .= '</div>';
		$static_rater[] .= '</div>'."\n\n";
		
		return join("\n", $static_rater);
	}
	else
	{
		$rater ='';
		$rater.='<div class="ratingblock">';

		$rater.='<div id="unit_long'.$id.'">';
		$rater.='  <ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
		$rater.='     <li class="current-rating" style="width:'.$rating_width.'px;">Currently '.$rating2.'/'.$units.'</li>';
		
		for ($ncount = 1; $ncount <= $units; $ncount++) { // loop from 1 to the number of units
		if(!$voted) { // if the user hasn't yet voted, draw the voting stars
		  $rater.='<li><a href="db.php?j='.$ncount.'&amp;q='.$id.'&amp;t='.$ip.'&amp;c='.$units.'" title="'.$ncount.' out of '.$units.'" class="r'.$ncount.'-unit rater" rel="nofollow">'.$ncount.'</a></li>';
		}
		}
		$ncount=0; // resets the count
		
		$rater.='  </ul>';
		$rater.='  <p';
		if($voted){ $rater.=' class="voted"'; }
		$rater.='>'.$id.' Rating: <strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.' cast)';
		$rater.='  </p>';
		$rater.='</div>';
		$rater.='</div>';
		return $rater;
	}
}

function _in_ary($sql_grp, $match_group)
{
	$_in_group = '';
	
	foreach ( $sql_grp as $groups )
	{
		foreach ( $groups as $group_id )
		{
			if ( in_array($group_id, $match_group) )
			{
				$_in_group = true;
			}
		}
	}
	
	return $_in_group;
}

function main_header($page_title = '')
{
	global $config, $settings, $theme, $root_path, $userdata, $template, $db, $lang;
	global $oCache;
	
	if ( defined('HEADER_INC') )
	{
		return;
	}

	define('HEADER_INC', true);
	
	/* gzip_compression */
	if ( $config['page_gzip'] )
	{
		$phpver = phpversion();
	
		$useragent = (isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) ? $HTTP_SERVER_VARS['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');
	
		if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
		{
			if ( extension_loaded('zlib') )
			{
				ob_start('ob_gzhandler');
			}
		}
		else if ( $phpver > '4.0' )
		{
			if ( strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') )
			{
				if ( extension_loaded('zlib') )
				{
					$gzip = TRUE;
					ob_start();
					ob_implicit_flush(0);
	
					header('Content-Encoding: gzip');
				}
			}
		}
	}
	$gzip = FALSE;
	
	//
	// Parse and show the overall header.
	//
	$template->set_filenames(array('overall_header' => ( empty($gen_simple_header) ) ? 'overall_header.tpl' : 'simple_header.tpl'));
	
	//
	// Generate logged in/logged out status
	//
	if ( $userdata['session_logged_in'] )
	{
		$u_login_logout = 'login.php?logout=true&amp;sid=' . $userdata['session_id'];
	//	$l_login_logout = $lang['Logout'] . ' [ ' . $userdata['user_name'] . ' ]';
		$l_login_logout = $lang['Logout'];
	}
	else
	{
		$smart_redirect = strrchr($_SERVER['PHP_SELF'], '/');
		$smart_redirect = substr($smart_redirect, 1, strlen($smart_redirect));
	
		if ( ($smart_redirect == ('profile.php')) || ($smart_redirect == ('login.php')) || ($smart_redirect == ('index.php')) )
		{
			$smart_redirect = '';
		}
	
		if ( isset($HTTP_GET_VARS) && !empty($smart_redirect) )
		{		
			$smart_get_keys = array_keys($HTTP_GET_VARS);
	
			for ($i = 0; $i < count($HTTP_GET_VARS); $i++)
			{
				if ($smart_get_keys[$i] != 'sid')
				{
					$smart_redirect .= '&amp;' . $smart_get_keys[$i] . '=' . $HTTP_GET_VARS[$smart_get_keys[$i]];
				}
			}
		}
		
		
	
		$u_login_logout = 'login.php';
		$u_login_logout .= (!empty($smart_redirect)) ? '?redirect=' . $smart_redirect : '';
		$l_login_logout = $lang['Login'];
	}
	
	$s_last_visit = ( $userdata['session_logged_in'] ) ? create_date($config['default_dateformat'], $userdata['user_lastvisit'], $config['default_timezone']) : '';
	
	//
	//	Counter
	//	von http://www.mywebsolution.de/
	//
	if ( $config['page_counter'] == '1' )
	{
		include("{$root_path}includes/functions_counter.php");

		counter_update();
		counter_result();
	}
	
	$sql = "SELECT group_id, group_name, group_color, group_legend FROM " . GROUPS . " WHERE group_legend = 1 ORDER BY group_order";
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
#	}
#	$groups = $db->sql_fetchrowset($result);
	$groups = _cached($sql, 'dsp_groups');
	
	if ( $groups )
	{
		$list = array();
		
		for ( $i = 0; $i < count($groups); $i++ )
		{
			$groups_id		= $groups[$i]['group_id'];
			$groups_name	= $groups[$i]['group_name'];
			$groups_style	= $groups[$i]['group_color'];
			
			$list[] = "<a href=\"" . check_sid("users.php?mode=g&id=$groups_id") . "\" style=\"color:$groups_style\"><b>$groups_name</b></a>";
		}
		
		$groups_list = implode(', ', $list);
	}
	else
	{
		$groups_list = '';
	}
				
	//
	// Get basic (user_names + totals) online
	// situation
	//
	$logged_visible_online = 0;
	$logged_hidden_online = 0;
	$guests_online = 0;
	$online_userlist = '';
	$l_online_users = '';
	$l_online_users_head = '';
	
	$user_forum_sql = ( !empty($forum_id) ) ? "AND s.session_page = " . intval($forum_id) : '';
	$sql = "SELECT u.user_name, u.user_id, u.user_allow_viewonline, u.user_level, u.user_color, s.session_logged_in, s.session_ip
		FROM ".USERS." u, ".SESSIONS." s
		WHERE u.user_id = s.session_user_id
			AND s.session_time >= ".( time() - 300 ) . "
			$user_forum_sql
		ORDER BY u.user_name ASC, s.session_ip ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$userlist_ary = array();
	$userlist_visible = array();
	
	$prev_user_id = 0;
	$prev_user_ip = $prev_session_ip = '';
	
	while( $row = $db->sql_fetchrow($result) )
	{
		// User is logged in and therefor not a guest
		if ( $row['session_logged_in'] )
		{
			// Skip multiple sessions for one user
			if ( $row['user_id'] != $prev_user_id )
			{
				$style_color = '';
				
				$row['user_name'] = '<b>' . $row['user_name'] . '</b>';
				$style_color = 'style="color:' . $row['user_color'] . '"';
	
				if ( $row['user_allow_viewonline'] )
				{
					$user_online_link = '<a href="' . check_sid('profile.php?mode=view&amp;id=' . $row['user_id']) . '"' . $style_color .'>' . $row['user_name'] . '</a>';
					$logged_visible_online++;
				}
				else
				{
					$user_online_link = '<a href="' . check_sid('profile.php?mode=view&amp;id=' . $row['user_id']) . '"' . $style_color .'><i>' . $row['user_name'] . '</i></a>';
					$logged_hidden_online++;
				}
	
				if ( $row['user_allow_viewonline'] || $userdata['user_level'] == ADMIN )
				{
					$online_userlist .= ( $online_userlist != '' ) ? ', ' . $user_online_link : $user_online_link;
				}
			}
	
			$prev_user_id = $row['user_id'];
		}
		else
		{
			// Skip multiple sessions for one user
			if ( $row['session_ip'] != $prev_session_ip )
			{
				$guests_online++;
			}
		}
	
		$prev_session_ip = $row['session_ip'];
	}
	$db->sql_freeresult($result);
	
	if ( empty($online_userlist) )
	{
		$online_userlist = $lang['None'];
	}
	$online_userlist = ( ( isset($forum_id) ) ? $lang['Browsing_forum'] : $lang['Registered_users'] ) . ' ' . $online_userlist;
	
	$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;
	
	if ( $total_online_users > $config['record_online_users'] )
	{
		$config['record_online_users'] = $total_online_users;
		$config['record_online_date'] = time();
	
		$sql = "UPDATE " . CONFIG . " SET config_value = '$total_online_users' WHERE config_name = 'record_online_users'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	
		$sql = "UPDATE " . CONFIG . " SET config_value = '" . $config['record_online_date'] . "' WHERE config_name = 'record_online_date'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
	
	$l_t_user_s = ( $total_online_users != 0 )		? ( $total_online_users == 1 )		? $l_t_user_s = $lang['online_user_total'] : $l_t_user_s = $lang['online_users_total'] : $lang['online_users_zero_total'];
	$l_r_user_s = ( $logged_visible_online != 0 )	? ( $logged_visible_online == 1 )	? $l_r_user_s = $lang['reg_user_total'] : $l_r_user_s = $lang['reg_users_total'] : $l_r_user_s = $lang['reg_users_zero_total'];
	$l_h_user_s = ( $logged_hidden_online != 0 )	? ( $logged_hidden_online == 1 )	? $l_h_user_s = $lang['hidden_user_total'] : $l_h_user_s = $lang['hidden_users_total'] : $l_h_user_s = $lang['hidden_users_zero_total'];	
	$l_g_user_s = ( $guests_online != 0 )			? ( $guests_online == 1 )			? $l_g_user_s = $lang['guest_user_total'] : $l_g_user_s = $lang['guest_users_total'] : $l_g_user_s = $lang['guest_users_zero_total'];
	
	$l_t_user_s_h = ( $total_online_users != 0 )	? ( $total_online_users == 1 )		? $l_t_user_s = $lang['online_user_total_h'] : $l_t_user_s = $lang['online_users_total_h'] : $lang['online_users_zero_total_h'];
	$l_r_user_s_h = ( $logged_visible_online != 0 )	? ( $logged_visible_online == 1 )	? $l_r_user_s = $lang['reg_user_total_h'] : $l_r_user_s = $lang['reg_users_total_h'] : $l_r_user_s = $lang['reg_users_zero_total_h'];
	$l_h_user_s_h = ( $logged_hidden_online != 0 )	? ( $logged_hidden_online == 1 )	? $l_h_user_s = $lang['hidden_user_total_h'] : $l_h_user_s = $lang['hidden_users_total_h'] : $l_h_user_s = $lang['hidden_users_zero_total_h'];	
	$l_g_user_s_h = ( $guests_online != 0 )			? ( $guests_online == 1 )			? $l_g_user_s = $lang['guest_user_total_h'] : $l_g_user_s = $lang['guest_users_total_h'] : $l_g_user_s = $lang['guest_users_zero_total_h'];
	
	$l_online_users = sprintf($l_t_user_s, $total_online_users);
	$l_online_users .= sprintf($l_r_user_s, $logged_visible_online);
	$l_online_users .= sprintf($l_h_user_s, $logged_hidden_online);
	$l_online_users .= sprintf($l_g_user_s, $guests_online);
	
	if ( $settings['module_stats']['show_header_online'] )
	{
		$l_online_users_head = sprintf($l_t_user_s_h, $total_online_users);
		$l_online_users_head .= sprintf($l_r_user_s_h, $logged_visible_online);
		$l_online_users_head .= sprintf($l_h_user_s_h, $logged_hidden_online);
		$l_online_users_head .= sprintf($l_g_user_s_h, $guests_online);
	}		
	/*
	//
	// Obtain number of new private messages
	// if user is logged in
	//
	if ( ($userdata['session_logged_in']) && (empty($gen_simple_header)) )
	{
		if ( $userdata['user_new_privmsg'] )
		{
			$l_message_new = ( $userdata['user_new_privmsg'] == 1 ) ? $lang['New_pm'] : $lang['New_pms'];
			$l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']);
	
			if ( $userdata['user_last_privmsg'] > $userdata['user_lastvisit'] )
			{
				$sql = "UPDATE " . USERS . "
					SET user_last_privmsg = " . $userdata['user_lastvisit'] . "
					WHERE user_id = " . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'Could not update private message new/read time for user', '', __LINE__, __FILE__, $sql);
				}
	
				$s_privmsg_new = 1;
				$icon_pm = $images['pm_new_msg'];
			}
			else
			{
				$s_privmsg_new = 0;
				$icon_pm = $images['pm_new_msg'];
			}
		}
		else
		{
			$l_privmsgs_text = $lang['No_new_pm'];
	
			$s_privmsg_new = 0;
			$icon_pm = $images['pm_no_new_msg'];
		}
	
		if ( $userdata['user_unread_privmsg'] )
		{
			$l_message_unread = ( $userdata['user_unread_privmsg'] == 1 ) ? $lang['Unread_pm'] : $lang['Unread_pms'];
			$l_privmsgs_text_unread = sprintf($l_message_unread, $userdata['user_unread_privmsg']);
		}
		else
		{
			$l_privmsgs_text_unread = $lang['No_unread_pm'];
		}
	}
	else
	{
		$icon_pm = $images['pm_no_new_msg'];
		$l_privmsgs_text = $lang['Login_check_pm'];
		$l_privmsgs_text_unread = '';
		$s_privmsg_new = 0;
	}
	*/
	
	//
	// Generate HTML required for Mozilla Navigation bar
	//
	if (!isset($nav_links))
	{
		$nav_links = array();
	}
	
	$nav_links_html = '';
	$nav_link_proto = '<link rel="%s" href="%s" title="%s" />' . "\n";
	while( list($nav_item, $nav_array) = @each($nav_links) )
	{
		if ( !empty($nav_array['url']) )
		{
			$nav_links_html .= sprintf($nav_link_proto, $nav_item, check_sid($nav_array['url']), $nav_array['title']);
		}
		else
		{
			// We have a nested array, used for items like <link rel='chapter'> that can occur more than once.
			while( list(,$nested_array) = each($nav_array) )
			{
				$nav_links_html .= sprintf($nav_link_proto, $nav_item, $nested_array['url'], $nested_array['title']);
			}
		}
	}
	
	$userauth = auth_acp_check($userdata['user_id']);
#	debug($userauth);
#	$auth = array();
	
#	foreach ( $userauth as $key => $value )
#	{
#		if ( $value != '0' )
#		{
#			$auth[$key] = $value;
#		}
#	}
	
	//
	// Show the overall footer.
	//
	$admin_link = (	$userdata['user_level'] == ADMIN || $userauth || $userdata['user_founder'] ) ? '<a href="admin/index.php?sid=' . $userdata['session_id'] . '">' . $lang['Admin_panel'] . '</a>' : '';
	
	//$sql = 'SELECT * FROM ' . CHANGELOG . ' ORDER BY changelog_id';
	//if ( !($result = $db->sql_query($sql)) )
	//{
	//	message(CRITICAL_ERROR, 'Could not query config information', '', __LINE__, __FILE__, $sql);
	//}
	//
	//$changelog = $db->sql_fetchrow($result);
	
	//
	// Standardseitentitel
	//
	if (!isset($page_title))
	{
	   $page_title = $lang['Information'];
	}
	
	// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
	$l_timezone = explode('.', $config['default_timezone']);
	$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $config['default_timezone'])] : $lang[number_format($config['default_timezone'])];
	
	//
	// The following assigns all _common_ variables that may be used at any point
	// in a template.
	//
	$template->assign_vars(array(
		'L_ENTRY_NO' => $lang['no_entry'],
		'NO_ENTRY' => $lang['no_entry'],
		
		
		'VERSION'		=> $config['page_version'],
	#	'CMS_VERSION'	=> $changelog['changelog_number'],
	
		/* navi */
		'L_NAVI_MAIN'		=> $lang['navi_main'],
		'L_NAVI_CLAN'		=> $lang['navi_clan'],
		'L_NAVI_COM'		=> $lang['navi_com'],
		'L_NAVI_MISC'		=> $lang['navi_misc'],
		'L_NAVI_USER'		=> $lang['navi_user'],
	
		/* subnavi */
		'L_SN_NEWS'				=> $lang['sn_news'],
		'L_SN_MATCH'			=> $lang['sn_match'],
		'L_SN_TOPICS'			=> $lang['sn_topics'],
		'L_SN_DOWNLOADS'		=> $lang['sn_downloads'],
		'L_SN_TEAMS'			=> $lang['sn_teams'],
		'L_SN_NEWUSERS'			=> sprintf($lang['sn_newest_users'], $settings['module_newusers']['limit']),
		'L_SN_TEAMS'			=> $lang['sn_teams'],
		'L_SN_LINKS'			=> $lang['sn_link'],
		'L_SN_PARTNER'			=> $lang['sn_partner'],
		'L_SN_SPONSOR'			=> $lang['sn_sponsor'],
		'L_SN_STATSONLINE'		=> $lang['sn_statsonline'],
		'L_SN_MINICAL'			=> $lang['sn_minical'],
		'L_SN_NEXT_MATCH'		=> $lang['sn_nextmatch'],
		'L_SN_NEXT_TRAINING'	=> $lang['sn_nexttraining'],
		
		'L_MARK_ALL'	=> $lang['MARK_ALL'],
	'L_MARK_DEALL'	=> $lang['MARK_DEALL'],

		'SITENAME' => $config['page_name'],
		
		'ADMIN_LINK'	=> $admin_link,
		
		'PAGE_TITLE' => $page_title,
		
		
	'L_NO'			=> $lang['COMMON_NO'],
	'L_YES'			=> $lang['COMMON_YES'],
	
	'L_SUBMIT'		=> $lang['common_submit'],
	'L_RESET'		=> $lang['common_reset'],
		
		'L_FORUM'	=> $lang['forum_index'],
		'U_FORUM'	=> check_sid('forum.php'),
		
		'LAST_VISIT_DATE' => sprintf($lang['You_last_visit'], $s_last_visit),
		'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['default_timezone'])),
		
	#	'TOTAL_USERS_ONLINE' => $l_online_users,
		
		
		'SO_HEADER'	=> $l_online_users_head,
		
		'GROUPS_LEGENED'	=> 'Gruppen: ' . $groups_list,
		
		'LOGGED_IN_USER_LIST' => $online_userlist,
		'RECORD_USERS' => sprintf($lang['Record_online_users'], $config['record_online_users'], create_date($config['default_dateformat'], $config['record_online_date'], $config['default_timezone'])),
	
		'L_USERNAME' => $lang['Username'],
		'L_PASSWORD' => $lang['Password'],
		'L_LOGIN_LOGOUT' => $l_login_logout,
		'L_LOGIN' => $lang['Login'],
		'L_LOGS_ME_IN' => $lang['Log_me_in'],
		'L_AUTO_LOGIN' => $lang['Log_me_in'],
		
		'L_REGISTER' => $lang['Register'],
		'L_PROFILE' => $lang['Profile'],
		'L_SEARCH' => $lang['Search'],
		'L_PRIVATEMSGS' => $lang['Private_Messages'],
		'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
		'L_MEMBERLIST' => $lang['Memberlist'],
		'L_FAQ' => $lang['FAQ'],
		'L_USERGROUPS' => $lang['Usergroups'],
		'L_SEARCH_NEW' => $lang['Search_new'],
		'L_SEARCH_UNANSWERED' => $lang['Search_unanswered'],
		'L_SEARCH_SELF' => $lang['Search_your_posts'],
		'L_WHOSONLINE_ADMIN' => sprintf($lang['Admin_online_color'], '<span style="color:#' . $theme['fontcolor3'] . '">', '</span>'),
		'L_WHOSONLINE_MOD' => sprintf($lang['Mod_online_color'], '<span style="color:#' . $theme['fontcolor2'] . '">', '</span>'),
	
		'U_SEARCH_UNANSWERED' => check_sid('search.php?search_id=unanswered'),
		'U_SEARCH_SELF' => check_sid('search.php?search_id=egosearch'),
		'U_SEARCH_NEW' => check_sid('search.php?search_id=newposts'),
		
		'U_REGISTER' => check_sid('profile.php?mode=register'),
		'U_PROFILE' => check_sid('profile.php?mode=editprofile'),
		'U_PRIVATEMSGS' => check_sid('privmsg.php?folder=inbox'),
		'U_PRIVATEMSGS_POPUP' => check_sid('privmsg.php?mode=newpm'),
		'U_SEARCH' => check_sid('search.php'),
		'U_MEMBERLIST' => check_sid('memberlist.php'),
		'U_MODCP' => check_sid('modcp.php'),
		'U_FAQ' => check_sid('faq.php'),
		'U_VIEWONLINE' => check_sid('viewonline.php'),
		'U_LOGIN_LOGOUT' => check_sid($u_login_logout),
		'U_GROUP_CP' => check_sid('groupcp.php'),
	
		'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
		'S_CONTENT_ENCODING' => $lang['ENCODING'],
		'S_CONTENT_DIR_LEFT' => $lang['LEFT'],
		'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],
		'S_TIMEZONE' => sprintf($lang['All_times'], $l_timezone),
		'S_LOGIN_ACTION' => check_sid('login.php'),
		
		'redirect' => ( !$userdata['session_logged_in'] ) ? $smart_redirect : '',
	
		'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
		'T_BODY_BACKGROUND' => $theme['body_background'],
		'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
		
		'S_NEWSLETTER_ACTION'	=> check_sid('newsletter.php'),
		
		'NAV_LINKS' => $nav_links_html)
		
	);
	
	display_navi($l_login_logout, $u_login_logout);
	
#	if ( $settings['subnavi_newusers'] )	{ display_newusers(); }
#	if ( $settings['subnavi_teams'] )		{ display_teams(); }
#	if ( $settings['subnavi_minical'] )		{ display_minical(); }
#	if ( $settings['subnavi_links'] )		{ display_network('links'); }
#	if ( $settings['subnavi_partner'] )		{ display_network('partner'); }
#	if ( $settings['subnavi_sponsor'] )		{ display_network('sponsor'); }
#	if ( $settings['subnavi_next_match'] )	{ display_next_match(); }

	$settings['module_news']['show']			? display_news()				: false;
	$settings['module_match']['show']			? display_match()				: false;
	$settings['module_topics']['show']			? display_topics()				: false;
	$settings['module_downloads']['show']		? display_downloads()			: false;
	$settings['module_newusers']['show']		? display_newusers()			: false;
	$settings['module_teams']['show']			? display_teams()				: false;
	$settings['module_calendar']['show']		? display_minical()				: false;
	$settings['module_server']['show']			? display_server()				: false;
	$settings['module_network']['show_links']	? display_network('links')		: false;
	$settings['module_network']['show_partner']	? display_network('partner')	: false;
	$settings['module_network']['show_sponsor']	? display_network('sponsor')	: false;
	$settings['module_next_match']['show']		? display_next_match()			: false;
	$settings['module_next_training']['show']	? display_next_training()		: false;
	
	if ( $settings['module_stats']['show_navi_online'] )
	{
		$l_t_user_s_n = ( $total_online_users != 0 )	? ( $total_online_users == 1 )		? $l_t_user_s = $lang['n_online_user_total'] : $l_t_user_s = $lang['n_online_users_total'] : $lang['n_online_users_zero_total'];
		$l_r_user_s_n = ( $logged_visible_online != 0 )	? ( $logged_visible_online == 1 )	? $l_r_user_s = $lang['n_reg_user_total'] : $l_r_user_s = $lang['n_reg_users_total'] : $l_r_user_s = $lang['n_reg_users_zero_total'];
		$l_h_user_s_n = ( $logged_hidden_online != 0 )	? ( $logged_hidden_online == 1 )	? $l_h_user_s = $lang['n_hidden_user_total'] : $l_h_user_s = $lang['n_hidden_users_total'] : $l_h_user_s = $lang['n_hidden_users_zero_total'];	
		$l_g_user_s_n = ( $guests_online != 0 )			? ( $guests_online == 1 )			? $l_g_user_s = $lang['n_guest_user_total'] : $l_g_user_s = $lang['n_guest_users_total'] : $l_g_user_s = $lang['n_guest_users_zero_total'];
		
		$l_online_users_total	= sprintf($l_t_user_s_n, $total_online_users);
		$l_online_users_visible	= sprintf($l_r_user_s_n, $logged_visible_online);
		$l_online_users_hidden	= sprintf($l_h_user_s_n, $logged_hidden_online);
		$l_online_users_guests	= sprintf($l_g_user_s_n, $guests_online);
		
		$template->assign_vars(array(
			'SO_TOTAL' 		=> $l_online_users_total,
			'SO_VISIBLE' 	=> $l_online_users_visible,
			'SO_HIDDEN' 	=> $l_online_users_hidden,
			'SO_GUESTS' 	=> $l_online_users_guests,
		));
		
		$template->set_filenames(array('statsonline' => 'navi_statsonline.tpl'));
		$template->assign_var_from_handle('STATSONLINE', 'statsonline');
	}
	
	//
	// Login box?
	//
	if ( !$userdata['session_logged_in'] )
	{
		$template->assign_block_vars('switch_user_logged_out', array());
		//
		// Allow autologin?
		//
		if (!isset($config['allow_autologin']) || $config['allow_autologin'] )
		{
			$template->assign_block_vars('switch_allow_autologin', array());
			$template->assign_block_vars('switch_user_logged_out.switch_allow_autologin', array());
		}
	}
	else
	{
		$template->assign_block_vars('switch_user_logged_in', array());
	
		if ( !empty($userdata['user_popup_pm']) )
		{
			$template->assign_block_vars('switch_enable_pm_popup', array());
		}
	}
	
	if ( defined('PAGE_DISABLE') )
	{
		$disable_message = (!empty($config['page_disable_msg'])) ? htmlspecialchars($config['page_disable_msg']) : $lang['Board_disable'];
		$template->assign_block_vars('page_disable', array('MSG' => str_replace("\n", '<br>', $disable_message)));
	}
	
	if ( !empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2') )
	{
		header("Cache-Control: no-cache, pre-check=0, post-check=0", FALSE);
	}
	else
	{
		header("Cache-Control: private, pre-check=0, post-check=0, max-age=0");
	}
	header('Expires:0');
	header('Pragma:no-cache');
	
	$template->pparse('overall_header');

#	debug($_POST);
	
	return;
}

function main_footer()
{
	global $gzip, $userdata, $template, $db, $lang;
	
	$template->set_filenames(array('overall_footer' => ( empty($gen_simple_header) ) ? 'overall_footer.tpl' : 'simple_footer.tpl'));
	
	$cache = (CACHE === true) ? '[ Cache: on ]' : '[ Cache: off ]';
	$debug = (DEBUG === true) ? '[ Debug: on ]' : '[ Debug: off ]';
	
	$template->assign_vars(array(
		'CACHE' => $cache,
		'DEBUG' => $debug,		
	));
	
	if ( empty($gen_simple_header) && DEBUG_SQL === true )
	{
		$stat_run = new stat_run_class(microtime());
		$stat_run->display();
	}
	
	$template->pparse('overall_footer');
	
	$db->sql_close();
	
	if ( $gzip )
	{
		$gzip_contents = ob_get_contents();
		ob_end_clean();
	
		$gzip_size = strlen($gzip_contents);
		$gzip_crc = crc32($gzip_contents);
	
		$gzip_contents = gzcompress($gzip_contents, 9);
		$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);
	
		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $gzip_contents;
		echo pack('V', $gzip_crc);
		echo pack('V', $gzip_size);
	}
	
	ob_end_flush();
	
	exit;
}

?>