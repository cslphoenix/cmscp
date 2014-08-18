<?php

function acl_auth($acl_check, $founder = false)
{
	global $db, $current, $log, $userdata, $lang, $mode;
	
	$gauth_access = $gaccess = array();
	$uauth_access = array();
	$options = array();
	$tmp_auth = array();
	$userauth = array();
	$auth_group = array();
	
	if ( $founder && !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['notice_auth_fail1'], lang($acl_check[0])));
	}
	
	$label_type = ( is_array($acl_check) ) ? substr($acl_check[0], 0, 2) : substr($acl_check, 0, 2);
	
	/* Gruppen des Benutzers abfragen */
	$sql = "SELECT type_id AS group_id
				FROM " . LISTS . "
					WHERE type = " . TYPE_GROUP . "
						AND user_pending = 0
						AND user_id = {$userdata['user_id']}";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$auth_group[] = $row['group_id'];
	}
	$db->sql_freeresult($result);
	
	/* Prüfen ob Gruppen bestehen, falls ja, Prüfen auf Rechte der Gruppen */
	if ( $auth_group )
	{
		$sql = "SELECT * FROM " . ACL_GROUPS . " WHERE group_id IN (" . implode(', ', $auth_group) . ")";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$gauth_access[] = $row;
		}
		$db->sql_freeresult($result);
	}
	
	/* Prüfen ob Benutzer extra Rechte hat */
	$sql = "SELECT * FROM  " . ACL_USERS . " WHERE user_id = {$userdata['user_id']}";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
		
	while ( $row = $db->sql_fetchrow($result) )
	{
		$uauth_access[] = $row;
	}
	$db->sql_freeresult($result);
	
	/* Abfragen der Label für Adminrechte, keine Auswirkung wieviele man erstellt */
	$acl_label = data(ACL_LABEL, "WHERE label_type = '$label_type'", false, 1, 3, true);

	$sql = "SELECT * FROM " . ACL_LABEL_DATA . " d
				LEFT JOIN " . ACL_OPTION . " o ON o.auth_option_id = d.auth_option_id
					WHERE d.label_id IN (" . implode(', ', array_keys($acl_label)) . ")";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$options[$row['label_id']][$row['auth_option_id']] = $row['auth_value'];
	}
	
	$acl_fields = data(ACL_OPTION, "WHERE auth_option LIKE '$label_type%'", false, 1, 3, true, false, 'auth_option');

	if ( $gauth_access )
	{
		foreach ( $gauth_access as $keys => $rows )
		{
			if ( $rows['label_id'] != 0 )
			{
				if ( isset($options[$rows['label_id']]) )
				{
					foreach ( $options[$rows['label_id']] as $key => $row )
					{
						$gaccess[$keys][$acl_fields[$key]] = $row;
					}
				}
				
				$tlabel = $rows['label_id'];
			}
			
			if ( $rows['auth_option_id'] != 0 )
			{
				$gaccess[$keys][$acl_fields[$rows['auth_option_id']]] = $rows['auth_value'];
			}
		}
		
		if ( $gaccess )
		{
			foreach ( $gaccess as $rows )
			{
				foreach ( $rows as $key => $row )
				{
					if ( $row == '1' )
					{
						if ( isset($tmp_auth[$key]) )
						{
							if ( $tmp_auth[$key] == '-1' )
							{
								$tmp_auth[$key] = '';
							}
							else if ( $tmp_auth[$key] == '0' )
							{
								$tmp_auth[$key] = '1';
							}
						}
						else
						{
							$tmp_auth[$key] = '1';
						}
					}
					else if ( $row == '0' )
					{
						if ( isset($tmp_auth[$key]) )
						{
							if ( $tmp_auth[$key] == '-1' )
							{
								$tmp_auth[$key] = '';
							}
							else if ( $tmp_auth[$key] == '1' )
							{
								$tmp_auth[$key] = '1';
							}
							
						}
						else
						{
							$tmp_auth[$key] = '';
						}
					}
					else
					{
						$tmp_auth[$key] = '';
					}
				}
			}
		}
	}

	if ( $uauth_access )
	{
		$uaccess = array();
		
		foreach ( $uauth_access as $keys => $rows )
		{
			if ( $rows['label_id'] != 0 )
			{
				if ( isset($options[$rows['label_id']]) )
				{
					foreach ( $options[$rows['label_id']] as $key => $row )
					{
						$uaccess[$keys][$acl_fields[$key]] = $row;
					}
				}
				
				$tlabel = $rows['label_id'];
			}
			
			if ( $rows['auth_option_id'] != 0 )
			{
				$uaccess[$keys][$acl_fields[$rows['auth_option_id']]] = $rows['auth_value'];
			}
		}
		
		foreach ( $uaccess as $rows )
		{
			foreach ( $rows as $key => $row )
			{
				if ( $row == '1' )
				{
					if ( isset($tmp_auth[$key]) )
					{
						if ( $tmp_auth[$key] == '-1' )
						{
							$tmp_auth[$key] = '';
						}
						else if ( $tmp_auth[$key] == '0' )
						{
							$tmp_auth[$key] = '1';
						}
					}
					else
					{
						$tmp_auth[$key] = '1';
					}
				}
				else if ( $row == '0' )
				{
					if ( isset($tmp_auth[$key]) )
					{
						if ( $tmp_auth[$key] == '-1' )
						{
							$tmp_auth[$key] = '';
						}
						else if ( $tmp_auth[$key] == '1' )
						{
							$tmp_auth[$key] = '1';
						}
						
					}
					else
					{
						$tmp_auth[$key] = '';
					}
				}
				else
				{
					$tmp_auth[$key] = '';
				}
			}
		}
	}
	
	if ( $tmp_auth )
	{
		foreach ( $tmp_auth as $key => $row )
		{
			if ( $row )
			{
				$userauth[$key] = $row;
			}
		}
	}
	
	if ( is_array($acl_check) )
	{
		$check = '';
		
		foreach ( $acl_check as $acl )
		{
			if ( in_array($acl, array_keys($userauth)) )
			{
				$check[$acl] = true;
			}
		#	else
		#	{
		#		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		#		message(GENERAL_ERROR, sprintf($lang['notice_auth_fail'], $lang[$current]));
		#	}
		}
		
		if ( $check )
		{
			foreach ( $check as $_check )
			{
				if ( $_check )
				{
					return true;
				}
				else
				{
					log_add(LOG_ADMIN, $log, 'auth_fail', $current);
					message(GENERAL_ERROR, sprintf($lang['notice_auth_fail2'], $lang[$_check]));
				}
			}
		}
		else
		{
			log_add(LOG_ADMIN, $log, 'auth_fail', $current);
			message(GENERAL_ERROR, sprintf($lang['notice_auth_fail3'], $lang[$current]));
		}
	}
	else
	{
		if ( in_array($acl_check, array_keys($userauth)) )
		{
			return true;
		}
		else
		{
			log_add(LOG_ADMIN, $log, 'auth_fail', $current);
			message(GENERAL_ERROR, sprintf($lang['notice_auth_fail4'], lang($acl_check)));
		}
	}
}

function auth_check($auth)
{
	global $userdata, $userauth, $log, $current, $lang;
	
	if ( $userdata['user_level'] != ADMIN && !$auth )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['notice_auth_fail5'], $lang[$current]));
	}
}

/*
 * Error Box ausgabe.
 */
function error($tpl_box, &$error_ary)
{
	global $template, $log;
	
	if ( is_array($error_ary) )
	{
		foreach ( $error_ary as $row )
		{
			if ( $row != '' )
			{
				$error[] = $row;
			}
		}
		
		if ( isset($error) )
		{
			$error_msg = implode('<br />', $error);
			
			$template->set_filenames(array('error' => 'style/info_error.tpl'));
			$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
			$template->assign_var_from_handle($tpl_box, 'error');
								
			log_add(LOG_ADMIN, $log, 'error', $error);
		}
	}
}

function right($tpl_box, $msg)
{
	global $template, $log;
	
	$template->set_filenames(array('right' => 'style/info_right.tpl'));
	$template->assign_vars(array('RIGHT_MESSAGE' => $msg));
	$template->assign_var_from_handle($tpl_box, 'right');
}

/*
 * Verschiebt das Element entweder nach oben oder nach unten, anhand der Orderzahl
 */
function move($tbl, $mode, $order, $main = false, $type = false, $usub = false, $action = false, $sort = false, $act = false)
{
	global $db;
	
	$sql = 'SHOW FIELDS FROM ' . $tbl;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$temp[] = $row['Field'];
	}
	
	$field_id = array_shift($temp);
	$field_order = array_pop($temp);
	
	$sql = "SELECT $field_id, $field_order FROM $tbl";

	if ( $type )
	{
		$sql .= " WHERE type = $type AND main = $usub";
	}
	else if ( $main >= 0 && !is_bool($main) )
	{
		$sql .= " WHERE main = $main";
	}

	if ( $action && $action != 'cat' )
	{
		$sql .= " AND action = '$action'";
	}
	
	if ( $sort )
	{
		switch ( $tbl )
		{
			case ACL_LABEL: $type = 'label_type'; break;
			case RANKS: $type = 'rank_type'; break; 
		}

		$sql .= " WHERE $type = '$sort'";
		
		if ( $tbl == RANKS && $act == 'forum' )
		{
			$sql .= " AND rank_min = 0";
		}
	}
	
	$sql .= " ORDER BY $field_order ASC";
#	debug($sql);
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
#	debug($db->sql_fetchrowset($result));
	$data_temp = array();
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$data_temp[$row[$field_id]] = $row[$field_order];
	}
	
	foreach ( $data_temp as $keys => $value )
	{
		if ( $value == $order )
		{
			$data_temp[$keys] = $value + (($mode == 'move_up') ? - '1.5' : + '1.5');
		}
	}
	
	asort($data_temp);
	
	$i = 1;

	foreach ( $data_temp as $key => $row )
	{
		$sql = "UPDATE $tbl SET $field_order = $i WHERE $field_id = $key";
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}

		$i += 1;
	}
}

/*
 *	Funktion um Benutzernamen in IDs umzu bauen
 */
function get_user_name_id(&$user_ids, &$user_names)
{
	global $db, $lang;
	
	if ($user_ids && $user_names)
	{
		return false;
	}
	else if (!$user_ids && !$user_names)
	{
		return true;
	}
	
	$user_names = explode("\n", $user_names);
	
	foreach ( $user_names as $_names )
	{
		$_user_names[] = trim($_names);
	}
	
	$user_ids = $user_names = array();
	
	$sql = "SELECT user_id, user_name FROM " . USERS . " WHERE LOWER(user_name) IN ('" . strtolower(implode("', '", $_user_names)) . "')";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $db->sql_numrows($result) )
	{
		while ( $row = $db->sql_fetchrow($result) )
		{
			$user_names[$row['user_id']] = $row['user_name'];
			$user_ids[] = $row['user_id'];
		}
	}
	else
	{
		return true;
	}

	return false;
}

/*
 * Versions abfrage von der Hauptseite
 */
function get_version($host, $directory, $filename, &$errstr, &$errno, $port = 80, $timeout = 2)
{
	global $oCache;
	
	/*
		Versionsabfrage Idee von phpBB / andere Scripte
		
		@param: string	$host		example: cms-phoenix.de
		@param: string	$directory	example: /updatecheck
		@param: string	$filename	example: version.txt
		@param: string	$errstr		example: &$errstr
		@param: string	$errno		example: &$errno
		@param: string	$port		example: 80
		@param: string	$timeout	example: 2
	*/
	
	$sCacheName = 'version_cms';
	
	if ( ($info = $oCache -> readCache($sCacheName)) === false)
	{
		if ( $fsock = @fsockopen($host, $port, $errno, $errstr, $timeout))
		{
			@fputs($fsock, "GET $directory/$filename HTTP/1.1\r\n");
			@fputs($fsock, "HOST: $host\r\n");
			@fputs($fsock, "Connection: close\r\n\r\n");
	
			$get_info = false;
			
			while ( !@feof($fsock) )
			{
				if ( $get_info )
				{
					$info .= @fread($fsock, 1024);
				}
				else
				{
					if ( @fgets($fsock, 1024) == "\r\n" )
					{
						$get_info = true;
					}
				}
			}
			@fclose($fsock);

			$info = explode("\n", $info);
		}
		else
		{
			$info = 'fail host';
		}
		
		$oCache->writeCache($sCacheName, $info);
	}
	
	return $info;
}

/*
 * PHP Info Prase
 */
function parsePHPInfo()
{
	/*	notwendig für die PHP/MySQL Supportinfos
	
		von http://www.php.net/manual/en/function.phpinfo.php#70306 - parse php modules from phpinfo
	
		alternativen:
		
		get a module setting
		function getModuleSetting($pModuleName,$pSetting)
		{
			$vModules = parsePHPModules();
			
			return $vModules[$pModuleName][$pSetting];
		}
	
		// Sample Usage
		debug(getModuleSetting('apache2handler','Apache Version')); // returns "bundled (2.0.28 compatible)"
		debug(getModuleSetting('Core','register_globals'));
	
		ob_start () ;
		phpinfo () ;
		$pinfo = ob_get_contents () ;
		ob_end_clean () ;
		
		// the name attribute "module_Zend Optimizer" of an anker-tag is not xhtml valide, so replace it with "module_Zend_Optimizer"
		echo '<div id="phpinfo">' . ( str_replace ( "module_Zend Optimizer", "module_Zend_Optimizer", preg_replace ( '%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo ) ) ) . '</div>';
	
		http://www.php.net/manual/en/function.phpinfo.php#106862
	
		function phpinfo_array()
		{
			ob_start();
			phpinfo();
			$info_arr = array();
			$info_lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
			$cat = "General";
			foreach($info_lines as $line)
			{
				// new cat?
				preg_match("~<h2>(.*)</h2>~", $line, $title) ? $cat = $title[1] : null;
				if(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
				{
					$info_arr[$cat][$val[1]] = $val[2];
				}
				elseif(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
				{
					$info_arr[$cat][$val[1]] = array("local" => $val[2], "master" => $val[3]);
				}
			}
			return $info_arr;
		}
		
		echo "<pre>".print_r(phpinfo_array(), 1)."</pre>";
	*/
	
	ob_start();
	phpinfo();
	$s = ob_get_contents();
	ob_end_clean();
	
	$s = strip_tags($s,'<h2><th><td>');
	$s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
	$s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
	$vTmp = preg_split('/(<h2>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);
	
	$vModules = array();
	
	for ( $i = 1; $i < count($vTmp); $i++ )
	{
		if ( preg_match('/<h2>([^<]+)<\/h2>/',$vTmp[$i],$vMat) )
		{
			$vName = trim($vMat[1]);
			$vTmp2 = explode("\n",$vTmp[$i+1]);
			
			foreach ( $vTmp2 AS $vOne )
			{
				$vPat = '<info>([^<]+)<\/info>';
				$vPat3 = "/$vPat\s*$vPat\s*$vPat/";
				$vPat2 = "/$vPat\s*$vPat/";
				
				if ( preg_match($vPat3,$vOne,$vMat) )
				{
					// 3cols
					$vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
				}
				else if ( preg_match($vPat2,$vOne,$vMat) )
				{
					// 2cols
					$vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
				}
			}
		}
	}
	
	return $vModules;
}

/*
 * Größe runden
 */
function size_round($size, $round)
{
	global $lang;
	
	/*
		Größe anzeigen mit Komma
		
		@param: int		$size		example: 12356
		@param: int		$round		example: 2
	*/
	
	$return = 0;
	
	if ( $size >= 1073741824 )
	{
		$return = round($size/1073741824, $round) . $lang['size_gb'];
	}
	else if ( $size >= 1048576 )
	{
		$return = round($size/1048576, $round) . $lang['size_mb'];
	}
	else if ( $size >= 1024 )
	{
		$return = round($size/1024, $round) . $lang['size_kb'];
	}
	else
	{
		$return = round($size, $round) . $lang['size_by'];
	}
	
	return $return;
}

/*
 * Begegnungen Sync funktion
 */
function match_check_image($path, $count, $pics, $maps)
{
	global $root_path;
	
	$check = '';
	
	for ( $i = 0; $i < $count; $i++ )
	{
		foreach ( $pics as $key => $pic )
		{
			if ( $pic == $maps[$i]['map_picture'] )
			{
				$check['in']['picture'][] = ( file_exists($path . '/' . $maps[$i]['map_picture']) ) ? $maps[$i]['map_picture'] : 'nicht vorhanden';
				unset($pics[$key]);
			}
			
			if ( $pic == $maps[$i]['map_preview'] )
			{
				$check['in']['preview'][] = ( file_exists($path . '/' . $maps[$i]['map_preview']) ) ? $maps[$i]['map_preview'] : 'nicht vorhanden';
				unset($pics[$key]);
			}
		}
	}
	
	if ( $pics )
	{
		foreach ( $pics as $pic )
		{
			$check['out'][] = $pic;
		}
	}
	
	return $check;
}

function maxi($table, $order, $where)
{
	global $db;
	
	$where_to = ( $where ) ? "WHERE $where" : "";
	
	$sql = "SELECT MAX($order) AS $order FROM $table $where_to";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	return $return[$order];
}

function maxa($table, $order, $where)
{
	global $db;
	
	$where_to = ( $where ) ? "WHERE $where" : false;
	
	$sql = "SELECT MAX($order) AS $order FROM $table $where_to";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$return = $db->sql_fetchrow($result);
	
	return $return[$order] + 1;
}

function update($table, $index, $move, $index_id)
{
	global $db;
	
	$sql = "UPDATE $table SET {$index}_order = {$index}_order+{$move} WHERE {$index}_id = $index_id";
#	$sql = "UPDATE $table SET " . $index . "_order = " . $index . "_order+" . $move . " WHERE " . $index . "_id = " . $index_id;
	if ( !$db->sql_query($sql) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
}

function orders_new($mode, $type, $id)
{
	global $db;

	switch ( $mode )
	{
		case FORUM:
			$idfield	= 'forum_id';
			$orderfield	= 'forum_order';
			$typefield	= 'cat_id';
			break;
	}

	$sql = "SELECT $idfield, $orderfield FROM $mode";
	
	switch ( $type )
	{
		case 'cat':
			$sql .= " WHERE ";
			break;
			
		case 'forum':
			$sql .= " WHERE cat_id = $id";
			break;
			
		case 'sub':
			$sql .= " WHERE forum_sub = $id";
			break;
	}
	
	/*
	if ( $type == '-1' )
	{
		$sql .= " WHERE $idfield != $type";
	}
	else if ( $type != '' )
	{
		$sql .= " WHERE $typefield = $type";
		$sql .= ( $mode == 'ranks' && $type == RANK_FORUM ) ?  ' AND rank_special = 1' : '';
	}
	*/
	$sql .= " ORDER BY $orderfield ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$i = 10;

	while ( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $mode SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$i += 10;
	}
}

#function orders($mode, $type = false, $subs = false)
function orders($mode, $type = '')
{
#	debug($type);
	global $db;
	
	if ( in_array($mode, array(DOWNLOADS_CAT, NEWS_CAT)) )
	{
		$idfield = 'cat_id';
		$orderfield	= 'cat_order';
	}

	switch ( $mode )
	{
		case MENU:			$idfield = 'menu_id';		$orderfield = 'menu_order';		$typefield = 'main';				break;
		
		
		case FORUM:			$idfield = 'forum_id';		$orderfield = 'forum_order';	$typefield = 'forum_sub'; /* $subfield = 'forum_sub';*/	break;
		
		case GAMES:			$idfield = 'game_id';		$orderfield = 'game_order';		break;
		case SERVER:		$idfield = 'server_id';		$orderfield	= 'server_order';	break;
		case TEAMS:			$idfield = 'team_id';		$orderfield = 'team_order';		break;
		case MATCH_MAPS:	$idfield = 'map_id';		$orderfield = 'map_order';		break;
		case GALLERY:		$idfield = 'gallery_id';	$orderfield = 'gallery_order';	break;
		
		
		
		case DOWNLOADS:		$idfield = 'file_id';		$orderfield = 'file_order';		$typefield = 'cat_id';				break;
		case MENU:			$idfield = 'file_id';		$orderfield = 'file_order';		$typefield = 'cat_id';				break;
		case FORUM:			$idfield = 'forum_id';		$orderfield = 'forum_order';	$typefield = 'cat_id';				break;
		case MAPS:			$idfield = 'map_id';		$orderfield	= 'map_order';		$typefield = 'map_sub';				break;
		case GALLERY_PIC:	$idfield = 'pic_id';		$orderfield = 'pic_order';		$typefield = 'gallery_id';			break;
		case RANKS:			$idfield = 'rank_id';		$orderfield = 'rank_order';		$typefield = 'rank_type';			break;
		
		case FIELDS;		$idfield = 'field_id';		$orderfield = 'field_order';	$typefield = 'field_sub';			break;
		
		case PROFILE;		$idfield = 'profile_id';	$orderfield = 'profile_order';	$typefield = 'main';			break;
		case NAVI:			$idfield = 'navi_id';		$orderfield = 'navi_order';		$typefield = 'navi_type';			break;
		case GROUPS:		$idfield = 'group_id';		$orderfield	= 'group_order';										break;
		case NETWORK:		$idfield = 'network_id';	$orderfield = 'network_order';	$typefield = 'network_type';		break;
		case SERVER:		$idfield = 'server_id';		$orderfield = 'server_order';	$typefield = 'server_type';			break;
		case NAVI:			$idfield = 'navi_id';		$orderfield = 'navi_order';		$typefield = 'navi_type';			break;
		case FORUM:			$idfield = 'forum_id';		$orderfield = 'forum_order';	$typefield = 'cat_id';				break;
	}
	
	$sql = "SELECT $idfield, $orderfield FROM $mode";

	if ( $type != '' || $type == '0' )
	{
		$sql .= " WHERE $typefield = $type";
		$sql .= ( $mode == 'ranks' && $type == RANK_FORUM ) ?  ' AND rank_special = 1' : '';
	}
	
#	if ( $subs != '' || $subs == '0' )
#	{
#		$sql .= " AND $subfield = $subs";
#	}
	
	$sql .= " ORDER BY $orderfield ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$i = 1;

	while ( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $mode SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		$i += 2;
	}
}

function size_dir($path)
{
	global $lang;
	
	$size = 0;

	if ( $dir = @opendir($path) )
	{
		while ( $file = @readdir($dir) )
		{
			if ( $file != '.' && $file != '..' && $file != 'index.htm' && $file != '.htaccess' && !strstr($file, '_preview') )
			{
				$size += @filesize($path . "/" . $file);
			}
		}
		@closedir($dir);

		//
		// This bit of code translates the avatar directory size into human readable format
		// Borrowed the code from the PHP.net annoted manual, origanally written by:
		// Jesse (jesse@jess.on.ca)
		//
		if ( $size >= 1048576 )
		{
			$size = round($size/1048576) . " MB";
		}
		else if( $size >= 1024 )
		{
			$size = round($size/1024) . " KB";
		}
		else
		{
			$size = $size . " Bytes";
		}
	}
	else
	{
		$size = $lang['msg_unavailable_size_dir'];
	}
	
	return $size;
}

function size_dir2($tmp_path)
{
	global $root_path, $lang;
	
	$path = $root_path . $tmp_path;
	$size = 0;	
	$dirs = is_dir($path) ? array_diff(scandir($path), array('.', '..', 'index.htm', '.svn', 'Thumbs.db', '.htaccess')) : false;
	
	if ( $dirs )
	{
		foreach ( $dirs as $subdir )
		{
			if ( is_dir($path . $subdir) )
			{
				$subdirs = array_diff(scandir($path . $subdir), array('.', '..', 'index.htm', '.svn', 'Thumbs.db', '.htaccess'));
				
				foreach ( $subdirs as $file )
				{
					if ( !strpos($file, '_preview') )
					{
						$size += @filesize($path . $subdir . '/' . $file);
					}
				}
			}
			else
			{
				if ( !strpos($subdir, '_preview') )
				{
					$size += @filesize($path . $subdir);
				}
			}
		}
		$size = size_round($size, 2);
	}
	
	$size = ( $size != 0 ) ? $size : $lang['msg_sizedir_empty'];
	
	return $size;
}

function size_file($file)
{
	$size = 0;
	
	if ( $file >= 1048576 )
	{
		$size = round($file / 1048576 * 100) / 100 . " MB";
	}
	else if( $file >= 1024 )
	{
		$size = round($file / 1024 * 100) / 100 . " KB";
	}
	else
	{
		$size = $size . " Bytes";
	}
	
	return $size;
}

function _size($size, $round = '')
{
	global $lang;
	
	$return = 0;
	
	if ( $size >= 1073741824 )
	{
		$return = round($size/1073741824, $round) . $lang['size_gb'];
	}
	else if ( $size >= 1048576 )
	{
		$return = round($size/1048576, $round) . $lang['size_mb'];
	}
	else if ( $size >= 1024 )
	{
		$return = round($size/1024, $round) . $lang['size_kb'];
	}
	else
	{
		$return = round($size, $round) . $lang['size_by'];
	}
	
	return $return;
}

/*
 *	Ordner erstellen. match, gallery
 *
 *	@param: string	$path		example: ./../upload/matchs/
 *	@param: string	$name		example: 01022001_
 *	@param: both	$cryp		example: true or false
 *
 */
function create_folder($path, $name, $cryp)
{
	global $lang;
	
	$folder_name = ( $cryp ) ? uniqid($name) : $name;
	$folder_path = $path . $folder_name;		
	
	mkdir("$folder_path", 0755);
	
	$file	= 'index.htm';
	$code	= $lang['empty_site'];
	$create	= fopen("$folder_path/$file", "w");
	
	fwrite($create, $code);
	fclose($create);
	
	return $folder_name;
}

function delete_folder($path)
{
	$dir = opendir($path);
	
	while ( $entry = readdir($dir) )
	{
		if ( $entry == '..' || $entry == '.' )
		{
			continue;
		}
		
		if ( is_dir($path . $entry) )
		{
			delete_folder($path . $entry . "/");
		}
		else
		{
			unlink($path . $entry);
		}
	}
	closedir($dir);
	rmdir($path);
}

function set_http(&$website)
{
	if ( !preg_match('#^http[s]?:\/\/#i', $website) )
	{
		$website = 'http://' . $website;
	}
	
	return $website;
}

function set_chmod($host, $port, $user, $pass, $path, $file, $perms)
{
	global $root_path, $db, $config, $settings, $lang;
	
	$conn = ftp_connect($host, $port, 3);
	
	if (!$conn) die('Verbindung zu ftp.example.com konnte nicht aufgebaut werden');
	
	// Login mit Benutzername und Passwort
	if (!ftp_login($conn, $user, $pass)) die('Fehler beim Login zu ftp.example.com');
	
	// Kommando "SITE CHMOD 0600 /home/user/privatefile" an den Server senden */
	if (ftp_site($conn, 'CHMOD 0' . $perms . ' ' . $path))
	{
		echo "Kommando erfolgreich ausgeführt.\n";
	}
	else
	{
		die('Kommando fehlgeschlagen.');
//		message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
	}
}

/*
 *	ID/Namen abfrage für DropDown Menüs
 *
 *	@param: string	$type	example: GAMES
 *	@param: string	$mode	example: 'name' / id
 *	@param: string	$select	example: 1 / 'cs.png'
 */
function search_image($type, $mode, $select)
{
	global $db;
	
	switch ( $mode )
	{
		case 'name':
		
			switch ( $type )
			{
				case GAMES:
					
					$sql = "SELECT game_image FROM $type WHERE game_id = $select";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
					
				case NEWS_CAT:
				
					$sql = "SELECT cat_image FROM $type WHERE cat_id = $select";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
			}
			
			break;
			
		case 'id':
		
			switch ( $type )
			{
				case GAMES:
					
					$sql = "SELECT game_id FROM $type WHERE game_image = '$select'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
					
				case NEWS_CAT:
				
					$sql = "SELECT cat_id FROM $type WHERE cat_image = '$select'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					break;
			}
			
			break;
	}
	
	$tmp = $db->sql_fetchrow($result);
	
	$key = @array_keys($tmp);
	$msg = $tmp[$key[0]];

	return $msg;
}

function search_user($type, $select)
{
	global $db;
	
	switch ( $type )
	{
		case 'name':	$sql = "SELECT user_name FROM " . USERS . " WHERE user_id = $select";	break;
		case 'id':		$sql = "SELECT user_id FROM " . USERS . " WHERE user_name = '$select'";	break;
	}
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$tmp = $db->sql_fetchrow($result);
	$key = array_keys($tmp);
	$msg = $tmp[$key[0]];

	return $msg;
}

?>