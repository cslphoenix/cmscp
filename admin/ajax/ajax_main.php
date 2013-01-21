<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['type']) )
{
	$type = $_POST['type'];
	$meta = $_POST['meta'];
	$name = $_POST['name'];
	$curt = $_POST['curt'];
	$mode = $_POST['mode'];
	$data = $_POST['data'];
	
	switch ( $meta )
	{
		case 'dl':		$tbl = DOWNLOAD;	$simple = true;	break;
		case 'gallery':	$tbl = GALLERY_NEW;	$simple = true;	break;
		case 'profile':	$tbl = PROFILE;		$simple = true;	break;
		
		case 'forum':	$tbl = FORUM;	$simple = false;	break;
		case 'menu':	$tbl = MENU;	$simple = false;	break;
	}
	
	$f_id		= $meta . '_id';
	$f_name		= $meta . '_name';
	$f_lang		= $meta . '_lang';
	$f_order	= $meta . '_order';
	
	$sql = 'SELECT * FROM ' . $tbl . (($type == 4) ? ' WHERE action = \'pcp\'' : '') . ' ORDER BY main ASC, ' . $f_order . ' ASC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	$cat = $entry = array();

	if ( $db->sql_numrows($result) )
	{
		while ( $row = $db->sql_fetchrow($result) )
		{
			if ( in_array($row['type'], array(0, 3)) )
			{
				$cat[$row[$f_id]] = $row;
			}
			else if ( in_array($row['type'], array(1, 4)) )
			{
				$entry[$row['main']][$row[$f_id]] = $row;
			}
			else
			{
				$sub[$row['main']][$row[$f_id]] = $row;
			}
		}
		
		ksort($cat);
		ksort($entry);
	}
	
#	debug($cat);
#	debug($lab);

	$switch = $settings['smain'][$meta . '_switch'];
	$entrys = $settings['smain'][$meta . '_entrys'];
	$subs	= (isset($settings['smain'][$meta . '_subs'])) ? $settings['smain'][$meta . '_subs'] : false;
	
	$sort = array();
	
	foreach ( $cat as $ckey => $crow )
	{
		$sort[] = array('id' => $crow[$f_id], 'typ' => 1, 'lng' => (isset($crow[$f_lang])) ? lang($crow[$f_name]) : $crow[$f_name]);

		if ( isset($entry[$ckey]) && $entrys )
		{
			foreach ( $entry[$ckey] as $hkey => $hrow )
			{
				$sort[] = array('id' => $hrow[$f_id],'typ' => 2, 'lng' => (isset($hrow[$f_lang])) ? lang($hrow[$f_name]) : $hrow[$f_name]);
				
				if ( isset($sub[$hkey]) && $subs )
				{
					foreach ( $sub[$hkey] as $skey => $srow )
					{
						$sort[] = array('id' => $srow[$f_id], 'typ' => 3, 'lng' => (isset($srow[$f_lang])) ? lang($srow[$f_name]) : $srow[$f_name]);
					}
				}
			}
		}
	}
	
#	debug($sort);
	
	$opt = '<div id="close">';
	
	if ( count($sort) > 0 )
	{
		if ( $switch )
		{
			if ( $mode == 'create' )
			{
				$opt .= '<select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
				
				foreach ( $sort as $row )
				{
					switch ( $type )
					{
						case 1:
						case 3:
							
							switch ( $row['typ'] )
							{
								case 1: $opt .= '<option value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
								case 2: $opt .= '<option value="' . $row['id'] . '" disabled="disabled">&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;									
								case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;
							}
							
							break;
							
						case 2:
						case 4:
						
							switch ( $row['typ'] )
							{
								case 1: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
								case 2: $opt .= '<option value="' . $row['id'] . '">&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;									
								case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;
							}
							
							break;
					}
				}
			}
			else
			{
				if ( $type == $curt )
				{
					$opt .= '<select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
					
					if ( $type != 0 )
					{
						foreach ( $sort as $row )
						{
							switch ( $type )
							{
								case 1:
								case 3:
									
									switch ( $row['typ'] )
									{
										case 1: $opt .= '<option value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
										case 2: $opt .= '<option value="' . $row['id'] . '" disabled="disabled">&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;									
										case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;
									}
									
									break;
									
								case 2:
								case 4:
								
									switch ( $row['typ'] )
									{
										case 1: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
										case 2: $opt .= '<option value="' . $row['id'] . '">&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;									
										case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;
									}
									
									break;
							}
						}
					}
					else
					{
						$opt .= '<option value="0">Keine Aktion' . "</option>\n";
					}
				}
				else if ( $type != $curt && $meta == 'forum' ) /* move und delte immer */
				{
					$opt .= '<select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
					
					foreach ( $sort as $row )
					{
						switch ( $type )
						{
							case 1:
							case 3:
								
								switch ( $row['typ'] )
								{
									case 1: $opt .= '<option value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
									case 2: $opt .= '<option value="' . $row['id'] . '" disabled="disabled">&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;									
									case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;
								}
								
								break;
								
							case 2:
							case 4:
							
								switch ( $row['typ'] )
								{
									case 1: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
									case 2: $opt .= '<option value="' . $row['id'] . '">&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;									
									case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;
								}
								
								break;
						}
					}
				}
				else if ( $type != $curt && !in_array($meta, array('forum', 'menu')) ) /* move und delte immer */
				{
					echo 'Test';
					
					if ( $type < $curt )
					{
						echo ' Test <';
						$opt .= '<option value="0">Keine Aktion' . "</option>\n";
					}
					else if ( $type > $curt )
					{
						echo ' Test >';
						$opt .= '<label><input type="radio" value="0" name="test" checked="checked">&nbsp;' . $lang['common_delete'] . "</label><br />\n";
						$opt .= '<label><input type="radio" value="1" name="test">&nbsp;' . $lang['common_move'] . "</label>:\n";
						$opt .= '<select name="' . sprintf('%s[%s]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
						
						foreach ( $sort as $row )
						{
							switch ( $type )
							{
								case 1:
								case 3:
									
									switch ( $row['typ'] )
									{
										case 1: $opt .= '<option value="' . $row['id'] . '"' . (($data == $row['id']) ? ' disabled="disabled"' : '') . '>' . $row['lng'] . "</option>\n"; break;
										case 2: $opt .= '<option value="' . $row['id'] . '" disabled="disabled">&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;									
										case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;
									}
									
									break;
									
								case 2:
								case 4:
								
									switch ( $row['typ'] )
									{
										case 1: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
										case 2: $opt .= '<option value="' . $row['id'] . '">&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;									
										case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp;&not;&nbsp;' . $row['lng'] . "</option>\n"; break;
									}
									
									break;
							}
						}
					}
					
					
				}
				else
				{
					$opt .= '<option disabled="disabled">test2' . "</option>\n";
					echo 'Test2';
				}
			}
		/*	
			foreach ( $sort as $row )
			{
				if ( $type == 2 )
				{
					$opt .= ($row['typ'] == 1) ? '<option disabled="disabled" value="' . $row['id'] . '">' . $row['lng'] . "</option>\n" : '<option value="' . $row['id'] . '">&nbsp; &nbsp;' . $row['lng'] . "</option>\n";
				}
				else
				{
					$opt .= ($row['typ'] == 1) ? '<option value="' . $row['id'] . '">' . $row['lng'] . "</option>\n" : (($entrys) ? '<option value="' . $row['id'] . '" disabled="disabled">&nbsp; &nbsp;' . $row['lng'] . "</option>\n" : '');
				}
			#	$opt .= '<option value="' . $row['id'] . '"' . (($row['cat']) ? (($type == 2) ? ' disabled="disabled"': '') : (($type == 1) ? ' disabled="disabled"': '')) . '>' . (($row['cat']) ? '' : '&nbsp; &nbsp;') . lang($row['lng']) . "</option>\n";
			}
		*/	
			$opt .= '</select>';
		}
		else
		{
			foreach ( $sort as $row )
			{
				if ( $type == 2 )
				{
					$opt .= ($row['cat']) ? '<label><input type="radio" disabled="disabled" />&nbsp;' . $row['lng'] . "</label><br />\n" : '&nbsp; &nbsp;<label><input type="radio" name="' . sprintf('%s[%s]', $meta, $name) . '" value="' . $row['id'] . '" />&nbsp;' . $row['lng'] . "</label><br />\n";
				}
				else
				{
					$opt .= ($row['cat']) ? '<label><input type="radio" name="' . sprintf('%s[%s]', $meta, $name) . '" value="' . $row['id'] . '" />&nbsp;' . $row['lng'] . "</label><br />\n" : (($entrys) ? '&nbsp; &nbsp;<label><input type="radio" disabled="disabled" />&nbsp;' . $row['lng'] . "</label><br />\n" : '');
				}
			}
		}
	}
	else
	{
		$opt .= 'Keine Kategorie Vorhanden!';
	}
	
	$opt .= '</div><div id="ajax_content"></div>';
	
	echo $opt;
}
else
{
	echo 'There should be no direct access to this script!';
}

?>