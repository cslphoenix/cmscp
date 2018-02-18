<?php

header('content-type: text/html; charset=UTF-8');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');
include($root_path . 'includes/acp/acp_functions.php');

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
	
#	debug($type, 'type');
#	debug($meta, 'meta');
#	debug($name, 'name');
#	debug($curt, 'curt');
#	debug($mode, 'mode');
#	debug($data, 'data');
	
	$menu = $label = $_name = false;
	$sort = array();
	
	switch ( $meta )
	{
		case 'dl':		$tbl = DOWNLOAD;	$fields = array('info' => 'dl', 'size' => 'dl_maxsize', 'type' => 'dl_types');	break;
		case 'gallery':	$tbl = GALLERY_NEW; $fields = array('info' => 'gl', 'size' => 'gallery_filesize', 'type' => 'gallery_dimension');	break;
		case 'profile':	$tbl = PROFILE;		break;
		case 'change':	$tbl = CHANGELOG;	$_name = true; break;
		case 'forum':	$tbl = FORUM;		$label = true;	break;		
		case 'menu':	$tbl = MENU;		$label = ($type != 4) ? true : false;	$menu = true;	break;
	}
	
	$f_id		= $meta . '_id';
	$f_name		= $meta . ($_name ? '_num' : '_name');
#	$f_types	= $meta . '_types';
	$f_order	= $meta . '_order';
	
	$sql = "SELECT * FROM $tbl " . ($menu ? (($type == 4) ? "WHERE action = 'PCP'" : "WHERE action = 'ACP'") : '') . " ORDER BY main ASC, $f_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
		exit;
	}
	
	$cat = $lab = $sub = array();

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
				$lab[$row['main']][$row[$f_id]] = $row;
			}
			else
			{
				$sub[$row['main']][$row[$f_id]] = $row;
			}
		}
	}
	
	$switch = isset($settings['smain'][$meta . '_switch']) ? $settings['smain'][$meta . '_switch'] : 0;
	$entrys = isset($settings['smain'][$meta . '_entrys']) ? $settings['smain'][$meta . '_entrys'] : 0;
	
	foreach ( $cat as $c_key => $c_row )
	{
	#	if ( isset($fields) )
	#	{
	#		switch ( $fields['info'] )
	#		{
	#			case 'dl':
	#			
	#				$c_row[$info] = sprintf('%s|%s', $c_row[$fields['size']], ($c_row[$fields['type']] != 'a:0:{}' ? uns_ary($c_row[$fields['type']]) : $c_row[$fields['type']]));
	#			
	#				break;
	#				
	#			case 'gl':
	#			
	#				$c_row[$info] = sprintf('%s|%s', $c_row[$fields['size']], $c_row[$fields['type']]);
	#			
	#				break;
	#		}
	#	}
		
		$sort[] = array(
			'id'	=> $c_row[$f_id],
			'typ'	=> 1,
			'lng'	=> langs($c_row[$f_name]),
			'info'	=> sprintf('%s|%s', $c_row[$fields['size']], ($fields['info'] == 'dl' ? ($c_row[$fields['type']] != 'a:0:{}' ? uns_ary($c_row[$fields['type']]) : $c_row[$fields['type']]) : sprintf('%s|%s', $c_row[$fields['size']], $c_row[$fields['type']]))),
			#((isset($fields)) ? isset($c_row[$f_types]) ? ($c_row[$f_types] != 'a:0:{}' ? uns_ary($c_row[$f_types]) : $c_row[$f_types]) : '',
		#	'uplg'	=> ((isset($fields)  ? isset($c_row[$fields['size']]) ? sprintf('%s|%s', $c_row[$fields['size']], $c_row[$fields['type']]) : '' : ''),
		);

		if ( isset($lab[$c_key]) && ($label ? true : $entrys) )
		{
			foreach ( $lab[$c_key] as $l_key => $h_row )
			{
				$sort[] = array(
					'id'	=> $h_row[$f_id],
					'typ'	=> 2,
					'lng'	=> langs($h_row[$f_name])
				);
				
				if ( isset($sub[$l_key]) && $entrys )
				{
					foreach ( $sub[$l_key] as $s_key => $s_row )
					{
						$sort[] = array(
							'id'	=> $s_row[$f_id],
							'typ'	=> 3,
							'lng'	=> langs($s_row[$f_name]));
					}
				}
			}
		}
	}
	
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
								case 2: $opt .= '<option value="' . $row['id'] . '" disabled="disabled">&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;									
								case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;
							}
							
							break;
							
						case 2:
						case 4:
							
							switch ( $row['typ'] )
							{
								case 1: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
								case 2: $opt .= '<option value="' . $row['id'] . '">&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;									
								case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;
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
										case 2: $opt .= '<option value="' . $row['id'] . '" disabled="disabled">&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;									
										case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;
									}
									
									break;
									
								case 2:
								case 4:
									
									switch ( $row['typ'] )
									{
										case 1: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
										case 2: $opt .= '<option value="' . $row['id'] . '">&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;									
										case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;
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
									case 2: $opt .= '<option value="' . $row['id'] . '" disabled="disabled">&nbsp; &nbsp;;' . $row['lng'] . "</option>\n"; break;									
									case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;
								}
								
								break;
								
							case 2:
							case 4:
							
								switch ( $row['typ'] )
								{
									case 1: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
									case 2: $opt .= '<option value="' . $row['id'] . '">&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;									
									case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;
								}
								
								break;
						}
					}
				}
				else if ( $type != $curt && !in_array($meta, array('forum', 'menu')) ) /* move und delte immer */
				{
					if ( $type < $curt )
					{
						echo ' Test <';
						$opt .= "Keine Aktion\n";
					}
					else if ( $type > $curt )
					{
						echo ':convert:';
						$opt .= '<label><input type="radio" value="0" name="main" checked="checked">&nbsp;' . $lang['COMMON_DELETE'] . "</label><br />\n";
						$opt .= '<label><input type="radio" name="main">&nbsp;' . $lang['common_move'] . "</label>:\n";
						$opt .= '<select name="' . sprintf('%s[main]', $meta, $name) . '" id="' . sprintf('%s_%s', $meta, $name) . '">';
						
						foreach ( $sort as $row )
						{
							switch ( $type )
							{
								case 1:
								case 3:
									
									switch ( $row['typ'] )
									{
										case 1: $opt .= '<option value="' . $row['id'] . '"' . (($data == $row['id']) ? ' disabled="disabled"' : '') . '>' . $row['lng'] . "</option>\n"; break;
										case 2: $opt .= '<option value="' . $row['id'] . '" disabled="disabled">&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;									
										case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;
									}
									
									break;
									
								case 2:
								case 4:
								
									switch ( $row['typ'] )
									{
										case 1: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">' . $row['lng'] . "</option>\n"; break;
										case 2: $opt .= '<option value="' . $row['id'] . '">&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;									
										case 3: $opt .= '<option disabled="disabled" value="' . $row['id'] . '">&nbsp; &nbsp;&nbsp; &nbsp;' . $row['lng'] . "</option>\n"; break;
									}
									
									break;
							}
						}
					}
				}
				else
				{
					echo 'Test =';
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
			#	$opt .= '<option value="' . $row['id'] . '"' . (($row['cat']) ? (($type == 2) ? ' disabled="disabled"': '') : (($type == 1) ? ' disabled="disabled"': '')) . '>' . (($row['cat']) ? '' : '&nbsp; &nbsp;') . langs($row['lng']) . "</option>\n";
			}
		*/	
			$opt .= '</select>';
		}
		else
		{
			$upload_info = $download_info = false;
			
			foreach ( $sort as $row )
			{
				$checked = ( $row['id'] == $data )	? ' checked="checked"' : '';
				
				if ( $type == 2 )
				{
					switch ( $row['typ'] )
					{
						case 1: $opt .= '<label><input type="radio" disabled="disabled" />&nbsp;' . $row['lng'] . '</label><br />'; break;
						case 2:	$opt .= '&nbsp; &nbsp;<label><input type="radio" name="' . $name . '" value="' . $row['id'] . '"' . $checked . ' />&nbsp;' . $row['lng'] . '</label><br />'; break;
						case 3:	$opt .= '&nbsp; &nbsp;&nbsp; &nbsp;<label><input type="radio" disabled="disabled" />&nbsp;' . $row['lng'] . '</label><br />'; break;
					}
				}
				else if ( $type == 4 )
				{
					switch ( $row['typ'] )
					{
						case 1: $opt .= '<label><input type="radio" name="' . $name . '" value="' . $row['id'] . '"' . $checked . ' />&nbsp;' . $row['lng'] . '</label><br />'; break;
						case 2:	$opt .= '&nbsp; &nbsp;<label><input type="radio" disabled="disabled" />&nbsp;' . $row['lng'] . '</label><br />'; break;
					}
				}
				else
				{
					if ( isset($fields) && isset($row['info']) && $fields['info'] == 'gl' )
					{
						list($filzsize, $dimension) = explode('|', $row['info']);
						list($filesizes, $typesize)	= explode(';', $filzsize);
						$upload_info = true;
					}
					else if ( isset($fields) && isset($row['info']) && $fields['info'] == 'dl' )
					{
						list($filzsize, $dimension) = explode('|', $row['info']);
						list($filesizes, $typesize)	= explode(';', $filzsize);
						$download_info = true;
					}
					
					switch ( $row['typ'] )
					{
						case 1:	$opt .= '<label><input type="radio" name="' . $name . '" value="' . $row['id'] . '"' . $checked . ' />&nbsp;' . $row['lng'] . '</label>';
								$opt .= $upload_info ?		'<span class="normal" style="color:#f00;">(' . sprintf($lang['STF_UPLOAD_INFO'], str_replace(':', ' x ', $dimension), $filesizes, $lang['drop_size'][$typesize]) . ')</span>' : '';
								$opt .= $download_info ?	'<span class="normal" style="color:#f00;">(' . sprintf($lang['STF_DOWNLOAD_INFO'], ($dimension != '' ? $dimension : langs($lang['DL_ALL_FILES'])), $filesizes, $lang['drop_size'][$typesize]) . ')</span>' : '';
								$opt .= '<br />'; break;
						case 2:	$opt .= '&nbsp; &nbsp;<label><input type="radio" disabled="disabled" />&nbsp;' . $row['lng'] . '</label><br />'; break;
						case 3:	$opt .= '&nbsp; &nbsp;&nbsp; &nbsp;<label><input type="radio" disabled="disabled" />&nbsp;' . $row['lng'] . '</label><br />'; break;
					}
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