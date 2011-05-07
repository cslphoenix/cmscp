<?php

/*
 * Idee von phpBB3
 */

function request($request_var, $request_type = '', $filter = '')
{
	global $_POST, $_GET;
	
	if ( isset($_POST[$request_var]) || isset($_GET[$request_var]) )
	{
		switch ( $request_type )
		{
			/*
			 *	Zahlen
			 */
			case 0:
				$var = ( isset($_POST[$request_var]) ) ? intval($_POST[$request_var]) : intval($_GET[$request_var]);
				$var = ( isset($var) ) ? $var : 0;
				break;
				
			/*
			 *	Mode/Confirm/Sort
			 */
			case 1:
				$var = ( isset($_POST[$request_var]) ) ? str_replace("'", "\'", $_POST[$request_var]) : str_replace("'", "\'", $_GET[$request_var]);
				break;
				
			/*
			 *	Texte
			 */
			case 2:
				$var = ( isset($_POST[$request_var]) ) ? trim(htmlentities(str_replace("'", "\'", $_POST[$request_var]), ENT_COMPAT)) : trim(htmlentities(str_replace("'", "\'", $_GET[$request_var]), ENT_COMPAT));
				break;
				
			/*
			 *	Texte ohne HTML Tags
			 */
			case 3:
				$var = ( isset($_POST[$request_var]) ) ? trim(htmlentities(str_replace("'", "\'", strip_tags($_POST[$request_var])), ENT_COMPAT)) : trim(htmlentities(str_replace("'", "\'", strip_tags($_GET[$request_var])), ENT_COMPAT));
				break;

			/*
			 *	Arrays
			 */
			case 4:
				
				$var = ( isset($_POST[$request_var]) ) ? $_POST[$request_var] : $_GET[$request_var];
				
				foreach ( $var as $_k => $_v )
				{
					if ( $_v != '' )
					{
						if ( $filter == 'url' )
						{
							if ( !preg_match('#^http[s]?:\/\/#i', $_v) )
							{
								$_var = 'http://' . $_v;
							}
							else if ( $_v == '' )
							{
								$_var = '';
							}
						}
						else
						{
							$_var = trim(htmlentities(str_replace("'", "\'", $_v), ENT_COMPAT));
						}
						
						$var[$_k] = $_v;
					}
					else
					{
						$var[$_k] = '';
					}
				}
				
				break;
				
			case 5:
				
				/*	URL	*/
				$var = ( isset($_POST[$request_var]) ) ? $_POST[$request_var] : $_GET[$request_var];
			
				break;
			
			/*	Image-URL	*/	
			case 6:
				
				/*	URL	*/
				$var = ( isset($_POST[$request_var]) ) ? $_POST[$request_var] : $_GET[$request_var];
			
				break;
			
			default:
				
				$var = ( isset($_POST[$request_var]) ) ? $_POST[$request_var] : $_GET[$request_var];
				
				break;
		}
	}
	else
	{
		$var = '';
	}
	
	return $var;
}

function request_file($request_var)
{
	global $_FILES;
	
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

function cut_string($string, $length)
{
	/***

	@param string $string	enthält den Titel
	@param int $length		enthält die maximale Länge
	
	@return string
	
	***/
	
	$string = ( strlen($string) <= $length ) ? $string : substr($string, 0, ($length-3)) . '...';
	
	return $string;
}

function get_authlist()
{
	/***

	@return string
	
	***/
	
	global $db;
	
	$sql = 'SELECT authlist_name FROM ' . AUTHLIST . ' ORDER BY authlist_name';
	
	if ( defined('IN_ADMIN') )
	{
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$authlist_data = $db->sql_fetchrowset($result);
	}
	else
	{
		$authlist_data = _cached($sql, 'authlist', 0);
	}

	for ( $i = 0; $i < count($authlist_data); $i++ )
	{
		$authlist[] = $authlist_data[$i]['authlist_name'];
	}
	
	return $authlist;
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

function group_set_auth($user_id, $group_id)
{
	global $db, $oCache;
	
	$sql = "SELECT group_access, group_color FROM " . GROUPS . " WHERE group_id = $group_id AND group_type <> " . GROUP_HIDDEN;
	if ( !$result = $db->sql_query($sql) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$group = $db->sql_fetchrow($result);
	
	$sql = "SELECT user_level FROM " . USERS . " WHERE user_id = $user_id";
	if ( !$result = $db->sql_query($sql) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$user = $db->sql_fetchrow($result);
	
	$group_access	= $group['group_access'];
	$group_color	= $group['group_color'];
	$user_level		= $user['user_level'];
	
	if ( $group_access == ADMIN && ( $user_level < ADMIN || $user_level < MOD || $user_level < MEMBER || $user_level < TRIAL ) )
	{
		$sql = "UPDATE " . USERS . " SET user_level = " . ADMIN . ", user_color = '#$group_color' WHERE user_id = $user_id";
		if ( !$result = $db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
	else if ( $group_access == MOD && ( $user_level < MOD || $user_level < MEMBER || $user_level < TRIAL ) )
	{
	#	$sql = 'UPDATE ' . USERS . ' SET user_level = ' . MOD . ', user_color = "#' . $group_color . '" WHERE user_id = ' . $user_id;
		$sql = "UPDATE " . USERS . " SET user_level = " . MOD . ", user_color = '#$group_color' WHERE user_id = $user_id";
		if ( !$result = $db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
	else if ( $group_access == MEMBER && ( $user_level < MEMBER || $user_level < TRIAL ) )
	{
	#	$sql = 'UPDATE ' . USERS . ' SET user_level = ' . MEMBER . ', user_color = "#' . $group_color . '" WHERE user_id = ' . $user_id;
		$sql = "UPDATE " . USERS . " SET user_level = " . MEMBER . ", user_color = '#$group_color' WHERE user_id = $user_id";
		if ( !$result = $db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
	else if ( $group_access == TRIAL && $user_level <= TRIAL )
	{
	#	$sql = 'UPDATE ' . USERS . ' SET user_level = ' . TRIAL . ', user_color = "#' . $group_color . '" WHERE user_id = ' . $user_id;
		$sql = "UPDATE " . USERS . " SET user_level = " . TRIAL . ", user_color = '#$group_color' WHERE user_id = $user_id";
		if ( !$result = $db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
	
	##	Löschung des Cacheintrages
	$oCache -> deleteCache('display_subnavi_newusers'); # neuste Benutzer
}

function group_reset_auth($user_id, $group_id)
{
	global $db;
	
	$sql = 'SELECT g.group_access, g.group_color
				FROM ' . GROUPS . ' g, ' . GROUPS_USERS . ' gu
				WHERE gu.user_id <> ' . ANONYMOUS . '
					AND g.group_id = gu.group_id
					AND gu.user_id = ' . $user_id . '
					AND g.group_single_user = 0
					AND NOT g.group_id = ' . $group_id . '
				GROUP BY g.group_id ASC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$group = $db->sql_fetchrow($result);
	
	$sql = 'SELECT user_founder FROM ' . USERS . ' WHERE user_id = ' . $user_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$user = $db->sql_fetchrow($result);
	
	if ( $user['user_founder'] == '0' )
	{
		$sql = "UPDATE " . USERS . " SET user_level = " . $group['group_access'] . ", user_color = '#" . $group['group_color'] . "' WHERE user_id = $user_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
	}
}

function generate_user_info(&$row, $date_format, $group_mod, &$from, &$posts, &$joined, &$poster_avatar, &$profile_img, &$profile, &$search_img, &$search, &$pm_img, &$pm, &$email_img, &$email, &$www_img, &$www, &$icq_status_img, &$icq_img, &$icq, &$aim_img, &$aim, &$msn_img, &$msn, &$yim_img, &$yim)
{
	global $lang, $images, $config;

	$from = ( !empty($row['user_from']) ) ? $row['user_from'] : '&nbsp;';
	$joined = create_date($date_format, $row['user_regdate'], $config['page_timezone']);

	$posts = ( $row['user_posts'] ) ? $row['user_posts'] : 0;

	if ( !empty($row['user_viewemail']) || $group_mod )
	{
		$email_uri = ( $config['page_email_form'] ) ? check_sid('profile.php?mode=email&amp;' . POST_USER_URL .'=' . $row['user_id']) : 'mailto:' . $row['user_email'];

		$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>';
		$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
	}
	else
	{
		$email_img = '&nbsp;';
		$email = '&nbsp;';
	}

	$temp_url = check_sid('profile.php?mode=viewprofile&amp;' . POST_USER_URL . '=' . $row['user_id']);
	$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" border="0" /></a>';
	$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

	$temp_url = check_sid('privmsg.php?mode=post&amp;' . POST_USER_URL . '=' . $row['user_id']);
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

function _cache_clear()
{
	global $oCache;
	
	$oCache -> truncateCache();
}

function _cut($text)
{
	// Wörter mit mehr als 60 Zeichen werden ab dem 60. Zeichen um ein Leerzeichen ergänzt 
	// damit der Browser den Text umbrechen kann (, sonst wird das Layout zerstört) 
	$max_word_lenght = 60; 
	// Die Länge von Links ist größer, da sie nur im Quelltext als 'lang' erscheinen 
	// Links werden später noch gesondert behandelt 
	$max_link_lenght = 200; 
	// Trennzeichen 
	$splitter = ' ';
	// Text in Zeilen aufteilen, sonst würden Zeilenumbrüche (\n) nicht als Worttrennung erkannt 
	$lines = explode("\n", $text);
	
	foreach ($lines as $key_line => $line)
	{
		// jede Zeile in Wörter aufteilen 
		$words = explode(' ', $line);
		// jedes Wort prüfen
		
		foreach ($words as $key_word => $word)
		{
			// für Links wird die maximale Länge erhöht
			if (substr(strtolower($word), 0, 7)== 'http://' OR substr(strtolower($word), 0, 8)== 'https://' OR substr(strtolower($word), 0, 4)=='www.')
			{
				$max_lenght = $max_link_lenght; 
			}
			else
			{
				$max_lenght = $max_word_lenght;
			}
			$word = trim($word);
			
			// BB-Code Tags entfernen, da sie nicht zur Buchstabenlänge eines Wortes zählen
			$word = preg_replace('/\[(.*)\]/Usi', '', $word); 
			
			if (strlen($word)>$max_lenght)
			{ 
				// Trennen des Wortes nach max_length Buchstaben
				$words[$key_word] = chunk_split($words[$key_word], $max_lenght, $splitter);
				
				// abziehen der Länge des Trennzeichens, dieses wird am Ende automatisch
				// noch einmal eingefügt
				$length = strlen($words[$key_word])-strlen($splitter);
				$words[$key_word] = substr($words[$key_word],0,$length);
			}
		}
		// fügt die veränderten Wörter wieder zur Zeile als String zusammen
		$lines[$key_line] = implode(" ", $words);
	}
	// fügt Zeilen wieder zum gesamten Text als String zusammen 
	$text = implode("\n", $lines);
	
	return $text;
}

function _cached($sql, $name, $row = '', $time = '')
{
	/***

	@param string $sql		enthält die SQL Abfrage
	@param string $name		enthält den Namen der Cachdatei
	@param int $row			sql_fetchrow/sql_fetchrowset
	@param int $time		Lebensdauer der Cachdatei
	
	@return string
	
	***/
	
	global $db, $oCache;
	
	if ( defined('CACHE') )
	{
		$sCacheName = $name;
		if ( ($fetch = $oCache -> readCache($sCacheName)) === false)
		{
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$fetch = ( $row == '1' ) ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			
			$oCache->writeCache($sCacheName, $fetch, $time);
		}
	}
	else
	{
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$fetch = ( $row == '1' ) ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
	}
	
	return $fetch;
}

function error_handler($errno, $errstr, $errfile, $errline)
{
	global $root_path, $template, $test_msg;
	
	$errfile = str_replace(array(cms_realpath($root_path), '\\'), array('', '/'), $errfile);
	
	$errno = $errno & error_reporting();
	
	if ( $errno == 0 )
	{
		return;
	}
	
	if ( !defined('E_STRICT') )
	{
		define('E_STRICT', 2048);
	}
    
	if ( !defined('E_RECOVERABLE_ERROR') )
	{
		define('E_RECOVERABLE_ERROR', 4096);
	}
	
	$msg = '<div align="left"><b>[';
	
	switch ( $errno )
	{
		case E_ERROR:
			$msg .= 'Error';
		break;
		case E_WARNING:
			$msg .= 'Warning';
		break;
		case E_PARSE:
			$msg .= 'Parse Error';
		break;
		case E_NOTICE:
			$msg .= 'Information';
		break;
		case E_CORE_ERROR:
			$msg .= 'Core Error';
		break;
		case E_CORE_WARNING:
			$msg .= 'Core Warning';
		break;
		case E_COMPILE_ERROR:
			$msg .= 'Compile Error';
		break;
		case E_COMPILE_WARNING:
			$msg .= 'Compile Warning';
		break;
		case E_USER_ERROR:
			$msg .= 'User Error';
		break;
		case E_USER_WARNING:
			$msg .= 'User Warning';
		break;
		case E_USER_NOTICE:
			$msg .= 'User Notice';
		break;
		case E_STRICT:
			$msg .= 'Strict Notice';
		break;
		case E_RECOVERABLE_ERROR:
			$msg .= 'Recoverable Error';
		break;
		default:
			$msg .= 'Unknown error ($errno)';
		break;
	}
	
	$msg .= "]:</b> $errstr in <b>$errfile</b> Zeile: <b>$errline</b>";
	$msg .= "</div>";
	
	if (isset($GLOBALS['error_fatal']))
	{
		if ($GLOBALS['error_fatal'] & $errno)
		{
			die('fatal');
		}
	}
	
	echo $msg;
}

function error_fatal($mask = NULL){
    if(!is_null($mask)){
        $GLOBALS['error_fatal'] = $mask;
    }elseif(!isset($GLOBALS['die_on'])){
        $GLOBALS['error_fatal'] = 0;
    }
    return $GLOBALS['error_fatal'];
}

function debug($data, $name = '')
{
	print '<div align="left">';
	print '<-- start -->';
	print '<br>';
	print ( $name ) ? '<br>' . $name : '';
	print '<br>';
	print '<pre>';
	print_r($data);
	print '</pre>';
	print '<br>';
	print '<-- end -->';
	print '<br>';
	print '</div>';
}

function debuge($data)
{
	global $root_path, $db, $config, $template;
	
	print '<br>';
	print '<pre>';
	print_r($data);
	print '</pre>';
	print '<br>';
	
	$inc = ( defined('IN_ADMIN') ) ? 'admin/page_footer_admin.php' : 'includes/page_footer.php';
	
	include($root_path . $inc);
	
	exit;
}

function check_image_type(&$type)
{
	global $lang;

	switch( $type )
	{
		case 'jpeg':
		case 'pjpeg':
		case 'jpg':
			return '.jpg';
			break;
		case 'gif':
			return '.gif';
			break;
		case 'png':
			return '.png';
			break;
		default:
			$error_msg = $lang['wrong_filetype'];

			message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
			break;
	}

	return false;
}

function team_logo_upload($mode, $format, &$current_logo, &$current_type, $logo_filename, $logo_realname, $logo_filesize, $logo_filetype)
{
	global $db, $settings, $lang;

	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	$width = $height = 0;
	$type = '';

	if ( ( file_exists(@cms_realpath($logo_filename)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $logo_realname) )
	{
		$sfilesize	= ($format == 'n') ? $settings['team_logo_filesize'] : $settings['team_logos_filesize'];
		$lfilesize	= ($format == 'n') ? $lang['logo_filesize'] : $lang['logos_filesize'];
		
		if ( $logo_filesize <= $sfilesize && $logo_filesize > 0 )
		{
			preg_match('#image\/[x\-]*([a-z]+)#', $logo_filetype, $logo_filetype);
			$logo_filetype = $logo_filetype[1];
		}
		else
		{
			$error_msg = sprintf($lfilesize, round($sfilesize / 1024));
			
			message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
		}

		list($width, $height, $type) = @getimagesize($logo_filename);
	}

	if ( !($imgtype = check_image_type($logo_filetype)) )
	{
		return;
	}

	switch ($type)
	{
		// GIF
		case 1:
			if ($imgtype != '.gif')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// JPG, JPC, JP2, JPX, JB2
		case 2:
		case 9:
		case 10:
		case 11:
		case 12:
			if ($imgtype != '.jpg' && $imgtype != '.jpeg')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// PNG
		case 3:
			if ($imgtype != '.png')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		default:
			@unlink($tmp_filename);
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
	}
	
	$slogo_max_width	= ($format == 'n') ? $settings['team_logo_max_width'] : $settings['team_logos_max_width'];
	$slogo_max_height	= ($format == 'n') ? $settings['team_logo_max_height'] : $settings['team_logos_max_height'];

	if ( $width > 0 && $height > 0 && $width <= $slogo_max_width && $height <= $slogo_max_height )
	{
		$new_filename = uniqid(rand()) . $imgtype;

		if ( $mode == 'editteam' && $current_type == LOGO_UPLOAD && $current_logo != '' )
		{
			team_logo_delete($format, $current_type, $current_logo);
		}

		if ( @$ini_val('open_basedir') != '' )
		{
			if ( @phpversion() < '4.0.3' )
			{
				message(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file', '', __LINE__, __FILE__);
			}

			$move_file = 'move_uploaded_file';
		}
		else
		{
			$move_file = 'copy';
		}

		if (!is_uploaded_file($logo_filename))
		{
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
		}
		
		$spath = ($format == 'n') ? $settings['team_logo_path'] : $settings['team_logos_path'];
		
		$move_file($logo_filename, './../' . $spath . "/$new_filename");

		@chmod('./../' . $spath . "/$new_filename", 0777);
		
		if ($format == 'n')
		{
			$logo_sql = ( $mode == 'editteam' ) ? "team_logo = '$new_filename', team_logo_type = " . LOGO_UPLOAD . ", " : "'$new_filename', " . LOGO_UPLOAD;
		}
		else
		{
			$logos_sql = ( $mode == 'editteam' ) ? "team_logos = '$new_filename', team_logos_type = " . LOGO_UPLOAD . ", " : "'$new_filename', " . LOGO_UPLOAD;
		}
	}
	else
	{
		$limagesize			= ($format == 'n') ? $lang['logo_imagesize'] : $lang['logos_imagesize'];
		$slogo_max_width	= ($format == 'n') ? $settings['team_logo_max_width'] : $settings['team_logos_max_width'];
		$slogo_max_height	= ($format == 'n') ? $settings['team_logo_max_height'] : $settings['team_logos_max_height'];
		
		$error_msg = sprintf($limagesize, $slogo_max_width, $slogo_max_height);
			
		message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
	}
	
	if ($format == 'n')
	{
		return $logo_sql;
	}
	else
	{
		return $logos_sql;
	}
}

function team_logo_delete($format, $logo_type, $logo_file)
{
	global $settings;
	
	$spath = ($format == 'n') ? $settings['team_logo_path'] : $settings['team_logos_path'];

	$logo_file = basename($logo_file);
	if ( $logo_type == LOGO_UPLOAD && $logo_file != '' )
	{
		if ( @file_exists(@cms_realpath('./../' . $spath . '/' . $logo_file)) )
		{
			@unlink('./../' . $spath . '/' . $logo_file);
		}
	}
	
	if ($format == 'n')
	{
		return "team_logo = '', team_logo_type = " . LOGO_NONE . ", ";
	}
	else
	{
		return "team_logos = '', team_logos_type = " . LOGO_NONE . ", ";
	}
}

function picture_upload($num, &$current_logo, &$current_logo_preview, $logo_filename, $logo_realname, $logo_filetype)
{
	global $settings, $db, $lang;

	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	$type = '';
	
	if ( ( file_exists(@cms_realpath($logo_filename)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $logo_realname) )
	{
		preg_match('#image\/[x\-]*([a-z]+)#', $logo_filetype, $logo_filetype);
		$logo_filetype = $logo_filetype[1];
			
		list($width, $height, $type) = @getimagesize($logo_filename);
	}
	
	if ( !($imgtype = check_image_type($logo_filetype)) )
	{
		return;
	}
	
	switch ($type)
	{
		// GIF
		case 1:
			if ($imgtype != '.gif')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// JPG, JPC, JP2, JPX, JB2
		case 2:
		case 9:
		case 10:
		case 11:
		case 12:
			if ($imgtype != '.jpg' && $imgtype != '.jpeg')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// PNG
		case 3:
			if ($imgtype != '.png')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		default:
			@unlink($tmp_filename);
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
	}
	
	
	
//	$new_filename = uniqid(rand()) . $imgtype;
	$new_filename = uniqid(rand());
	$new_filename_preview = $new_filename . '_preview' . $imgtype;
	$new_filename = $new_filename . $imgtype;
/*	
	echo $new_filename;
	echo '<br/>';
	echo $new_filename_preview;
	exit;
*/
	//	neue größe
	$new_width = '100';
	$new_height = '75';
	
	//	ändern
	$image_p = imagecreatetruecolor($new_width, $new_height);
	
	switch ($imgtype)
	{
		case '.jpeg':
		case '.pjpeg':
		case '.jpg':
			$image = imagecreatefromjpeg($logo_filename);
		break;
		case '.gif':
			$image = imagecreatefromgif($logo_filename);
		break;
		case '.png':
			$image = imagecreatefrompng($logo_filename);
		break;
	}
	
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	
	switch ($imgtype)
	{
		case '.jpeg':
		case '.pjpeg':
		case '.jpg':
			imagejpeg($image_p, './../' . $settings['path_match_picture'] . "/$new_filename_preview", 100);
		break;
		case '.gif':
			imagegif($image_p, './../' . $settings['path_match_picture'] . "/$new_filename_preview");
		break;
		case '.png':
			imagepng($image_p, './../' . $settings['path_match_picture'] . "/$new_filename_preview");
		break;
	}
	
	if ( $current_logo != '' )
	{
		picture_delete($num, $current_logo, $current_logo_preview);
	}

	if ( @$ini_val('open_basedir') != '' )
	{
		if ( @phpversion() < '4.0.3' )
		{
			message(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file', '', __LINE__, __FILE__);
		}

		$move_file = 'move_uploaded_file';
	}
	else
	{
		$move_file = 'copy';
	}

	if (!is_uploaded_file($logo_filename))
	{
		message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
	}

	$move_file($logo_filename, './../' . $settings['path_match_picture'] . "/$new_filename");

	@chmod('./../' . $settings['path_match_picture'] . "/$new_filename", 0777);
	
	$pic = "details_map_pic_" . $num . " = '$new_filename', pic_" . $num . "_preview = '$new_filename_preview',";
	
	return $pic;
}

function picture_delete($num, $logo_file, $logo_preview_file)
{
	global $settings;
	
	$logo_file = basename($logo_file);
	$logo_preview_file = basename($logo_preview_file);
	if ($logo_file != '' )
	{
		if ( @file_exists(@cms_realpath('./../' . $settings['path_match_picture'] . '/' . $logo_file)) )
		{
			@unlink('./../' . $settings['path_match_picture'] . '/' . $logo_file);
		}
		
		if ( @file_exists(@cms_realpath('./../' . $settings['path_match_picture'] . '/' . $logo_preview_file)) )
		{
			@unlink('./../' . $settings['path_match_picture'] . '/' . $logo_preview_file);
		}
	}
	
	return "details_map_pic_$num = '', pic_" . $num . "_preview = '', ";
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
				log_sektion = '$log',
				log_message = '$msg',
				log_data = '$data'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'Log SQL Error ', '', __LINE__, __FILE__, $sql);
	}
}

/*
log_id, log_type, log_time, user_id, user_ip, log_sektion, log_message, log_data

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
				WHERE user_id <> " . ANONYMOUS;
			break;

		case 'newestuser':
			$sql = "SELECT user_id, user_name
				FROM " . USERS . "
				WHERE user_id <> " . ANONYMOUS . "
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
			$config['page_timezone'] = $userdata['user_timezone'];
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
	$ip_sep = explode('.', $dotquad_ip);
	return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}

function decode_ip($int_ip)
{
	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
	return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

//
// Create date/time from format and timezone
//
function create_date($format, $gmepoch, $tz)
{
	global $config, $lang;
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

	$page_string = $lang['Goto_page'] . ' ' . $page_string;

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
	if( !isset($msg_history) )
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
	
	if(defined('HAS_DIED'))
	{
	//	die("message() was called multiple times. This isn't supposed to happen. Was message() used in page_tail.php?");
		//
		// This message is printed at the end of the report.
		// Of course, you can change it to suit your own needs. ;-)
		//
		$custom_error_message = 'Please, contact the %swebmaster%s. Thank you.';
		if ( !empty($board_config) && !empty($board_config['board_email']) )
		{
			$custom_error_message = sprintf($custom_error_message, '<a href="mailto:' . $board_config['board_email'] . '">', '</a>');
		}
		else
		{
			$custom_error_message = sprintf($custom_error_message, '', '');
		}
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
		die("</body>\n</html>");
	}
	
	define('HAS_DIED', 1);
	

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
			include($root_path . 'includes/page_header.php');
		}
		else
		{
			include($root_path . 'admin/page_header_admin.php');
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
			$template->set_filenames(array(
				'message_body' => 'message_body.tpl')
			);
		}
		else
		{
			$template->set_filenames(array(
				'message_body' => './../admin/style/info_message.tpl')
			);
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
			include($root_path . 'includes/page_tail.php');
		}
		else
		{
			include($root_path . 'admin/page_footer_admin.php');
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
	global $root_path, $phpEx;

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

?>