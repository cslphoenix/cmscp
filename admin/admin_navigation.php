<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_navi'] )
	{
		$module['hm_main']['sm_navi'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'sm_navi';

	include('./pagestart.php');
	
	add_lang('navi');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_NAVI;
	$url	= POST_NAVI;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_type	= request('type', INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', TXT);
	
	$dir_path	= $root_path . $settings['path_games'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_navi'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_navigation.tpl',
		'ajax'		=> 'style/ajax_order.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$template->assign_var_from_handle('AJAX', 'ajax');
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					list($type) = ( isset($_POST['navi_type']) ) ? each($_POST['navi_type']) : '';
					$name		= ( isset($_POST['navi_name']) ) ? str_replace("\'", "'", $_POST['navi_name'][$type]) : '';
					
					$data = array(
						'navi_name'		=> $name,
						'navi_type'		=> $type,
						'navi_url'		=> '',
						'navi_lang'		=> '0',
						'navi_show'		=> '1',
						'navi_target'	=> '0',
						'navi_intern'	=> '0',
						'navi_order'	=> '',
					);
				}
				else if ( $mode == 'update' && !request('submit', TXT) )
				{
					$data = data(NAVI, $data_id, false, 1, true);
				}
				else
				{
					$data = array(
						'navi_name'		=> request('navi_name', 2),
						'navi_type'		=> request('navi_type', 0),
						'navi_url'		=> request('navi_url', 2),
						'navi_lang'		=> request('navi_lang', 0),
						'navi_show'		=> request('navi_show', 0),
						'navi_target'	=> request('navi_target', 0),
						'navi_intern'	=> request('navi_intern', 0),
						'navi_order'	=> request('navi_order', 0) ? request('navi_order', 0) : request('navi_order_new', 0),
					);
							
					$error .= ( !$data['navi_name'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					$error .= ( !$data['navi_url'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_url'] : '';
					$error .= ( !$data['navi_type'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_type'] : '';
					
					if ( !$error )
					{
						$data['navi_url'] = $data['navi_target'] ? set_http($data['navi_url']) : './' . $data['navi_url'];
						$data['navi_order'] = ( !$data['navi_order'] ) ? maxa(NAVI, 'navi_order', 'navi_type = ' . $data['navi_type']) : $data['navi_order'];
						
						if ( $mode == 'create' )
						{
							$sql = sql(NAVI, $mode, $data);
							$msg = $lang['create'] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(NAVI, $mode, $data, 'navi_id', $data_id);
							$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data_id"));
						}
						
						orders(NAVI, $data['navi_type']);
						
						$oCache->deleteCache('dsp_navi');
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						log_add(LOG_ADMIN, $log, 'error', $error);
						
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'error');
					}
				}
				
				$navi_url = str_replace('./', '', $data['navi_url']);

				$folder = $root_path;
				$files = scandir($folder);
				
				$s_list = '<select class="selectsmall" name="navi_url" id="navi_url" onchange="select.value = this.value;">';
				$s_list .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_file']) . "</option>";
				
				foreach ( $files as $sfile )
				{
					if ( strstr($sfile, '.php') )
					{
						$selected = ( $sfile == $navi_url ) ? 'selected="selected"' : '';
						$s_list .= "<option value=\"$sfile\" $selected>" . sprintf($lang['sprintf_select_format'], $sfile) . "</option>";
					}
				}
				$s_list .= '</select>';
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'		=> sprintf($lang["sprintf_$mode"], $lang['field'], $data['navi_name']),
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['field']),
					'L_SET'			=> $lang['titles'],
					'L_URL'			=> $lang['url'],
					'L_TYPE'		=> sprintf($lang['sprintf_type'], $lang['field']),
					'L_LANGUAGE'	=> $lang['common_language'],
					'L_SHOW'		=> $lang['common_visible'],
					'L_INTERN'		=> $lang['common_intern'],

					'L_TARGET'		=> $lang['target'],
					'L_TARGET_NEW'	=> $lang['target_new'],
					'L_TARGET_SELF'	=> $lang['target_self'],
					'L_TYPE_MAIN'	=> $lang['main'],
					'L_TYPE_CLAN'	=> $lang['clan'],
					'L_TYPE_COM'	=> $lang['com'],
					'L_TYPE_MISC'	=> $lang['misc'],
					'L_TYPE_USER'	=> $lang['user'],

					'NAME'			=> $data['navi_name'],
					'URL'			=> $navi_url,
					
					'CUR_TYPE'		=> $data['navi_type'],
					'CUR_ORDER'		=> $data['navi_order'],

					'S_LIST'		=> $s_list,
				
					'S_LANG_NO'		=> (!$data['navi_lang'] ) ? 'checked="checked"' : '',
					'S_LANG_YES'	=> ( $data['navi_lang'] ) ? 'checked="checked"' : '',
					'S_SHOW_NO'		=> (!$data['navi_show'] ) ? 'checked="checked"' : '',
					'S_SHOW_YES'	=> ( $data['navi_show'] ) ? 'checked="checked"' : '',
					'S_INTERN_NO'	=> (!$data['navi_intern'] ) ? 'checked="checked"' : '',
					'S_INTERN_YES'	=> ( $data['navi_intern'] ) ? 'checked="checked"' : '',
					
					'S_TARGET_SELF'	=> (!$data['navi_target'] ) ? 'checked="checked"' : '',
					'S_TARGET_NEW'	=> ( $data['navi_target'] ) ? 'checked="checked"' : '',
					
					'S_TYPE_MAIN'	=> ( $data['navi_type'] == NAVI_MAIN ) ? 'checked="checked"' : '',
					'S_TYPE_CLAN'	=> ( $data['navi_type'] == NAVI_CLAN ) ? 'checked="checked"' : '',
					'S_TYPE_COM'	=> ( $data['navi_type'] == NAVI_COM ) ? 'checked="checked"' : '',
					'S_TYPE_MISC'	=> ( $data['navi_type'] == NAVI_MISC ) ? 'checked="checked"' : '',
					'S_TYPE_USER'	=> ( $data['navi_type'] == NAVI_USER ) ? 'checked="checked"' : '',

					'S_ORDER'		=> simple_order(NAVI, $data['navi_type'], 'select', $data['navi_order']),

					'S_SET'			=> check_sid("$file?mode=_settings"),
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));

				if ( request('submit', TXT) )
				{
					
				}
			
				$template->pparse('body');
				
				break;
			
			case 'order':
			
				update(NAVI, 'navi', $move, $data_id);
				orders(NAVI, $data_type);
				
				$oCache->deleteCache('dsp_navi');
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
	
				break;
				
			case 'delete':
			
				$data = data(NAVI, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{
					$sql = sql(NAVI, $mode, $data, 'navi_id', $data_id);
					$msg = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				
				}
				else if ( $data_id && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['navi_name']),
						
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}
				
				$template->pparse('confirm');
				
				break;

			case 'settings':
			
				$template->assign_block_vars('settings', array());
				
				$fields .= '<input type="hidden" name="mode" value="_settings" />';
				
				$sql = 'SELECT * FROM ' . SETTINGS;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				else
				{
					while ( $set = $db->sql_fetchrow($result) )
					{
						$settings_name	= $set['settings_name'];
						$settings_value	= $set['settings_value'];
						
						$old[$settings_name] = isset($_POST['submit']) ? str_replace("'", "\'", $settings_value) : $settings_value;
						$new[$settings_name] = isset($_POST[$settings_name] ) ? $_POST[$settings_name] : $old[$settings_name];
						
						if ( request('submit', TXT))
						{
							/*
							if ( strpos($settings_name, 'path_') !== false )
							{
								$new[$settings_name] = trim($new[$settings_name], '/') . '/';
							}
							
							if ( strpos($settings_name, 'filesize_') !== false )
							{
								$new[$settings_name] = ($new[$settings_name] * 1048576);
							}
							
							if ( strpos($settings_name, 'dimension_') !== false || strpos($settings_name, 'preview_') !== false )
							{
								if ( !empty($new[$settings_name][0]) && !empty($new[$settings_name][1]) )
								{
									$new[$settings_name] = implode(':', array($new[$settings_name][0], $new[$settings_name][1]));
								}
								else
								{
									$new[$settings_name] = '';
								}
							}
							*/
						
							if ( isset($_POST[$settings_name]) )
							{
								$a = $b = $ary = array();
								$text = $spos = '';
								
								foreach ( $_POST[$settings_name] as $key => $row )
								{
									$rows = str_replace("'", "\'", $row);
									$spos = strrpos($key, '_');
									$text = substr($key, 0, $spos);
									
									if ( strpos($key, '_type') !== false )
									{
										$a[$text] = $rows;
									}
									
									if ( strpos($key, '_value') !== false )
									{
										$b[$text] = ( !is_array($rows) ) ? ( $text == 'filesize' ) ? ($rows * 1048576) : $rows : "{$row[0]}:{$row[1]}";
									}
								}
								
								foreach ( $a as $akey => $arow )
								{
									foreach ( $b as $bkey => $brow )
									{
										if ( $akey == $bkey )
										{
											$ary[$akey] = array('type' => $arow, 'value' => $brow);
										}
									}
								}
								
								$new[$settings_name] = serialize($ary);
							}
							
							$sql = "UPDATE " . SETTINGS . " SET settings_value = '{$new[$settings_name]}' WHERE settings_name = '$settings_name'";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error: ' . $settings_name, '', __LINE__, __FILE__, $sql);
							}
						}
						else
						{
							$setting[$set['settings_name']] = $set['settings_value'];
						}
					}
				}
				
				if ( request('submit', TXT) )
				{
					$oCache->deleteCache('cfg_setting');
			
					$msg = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode"));
					
					message(GENERAL_MESSAGE, $msg);
				}
				
				foreach ( $setting as $key => $set )
				{
					if ( in_array($key, array('subnavi_downloads', 'subnavi_match', 'subnavi_network', 'subnavi_news', 'subnavi_newusers', 'subnavi_next_match', 'subnavi_next_training', 'subnavi_server', 'subnavi_stats', 'subnavi_teams', 'subnavi_topics')) )
					{
						$dsp[$key] = unserialize($set);
					}
				}
				
				foreach ( $dsp as $key => $value )
				{
					if		( $key == 'subnavi_news' )			{ $display[0][$key] = $value; }
					else if ( $key == 'subnavi_match' )			{ $display[1][$key] = $value; }
					else if ( $key == 'subnavi_topics' )		{ $display[2][$key] = $value; }
					else if ( $key == 'subnavi_downloads' )		{ $display[3][$key] = $value; }
					else if ( $key == 'subnavi_newusers' )		{ $display[4][$key] = $value; }
					else if ( $key == 'subnavi_teams' )			{ $display[5][$key] = $value; }
					else if ( $key == 'subnavi_network' )		{ $display[6][$key] = $value; }
					else if ( $key == 'subnavi_stats' )			{ $display[7][$key] = $value; }
					else if ( $key == 'subnavi_server' )		{ $display[8][$key] = $value; }
					else if ( $key == 'subnavi_next_match' )	{ $display[9][$key] = $value; }
					else if ( $key == 'subnavi_next_training' )	{ $display[10][$key] = $value; }
					else
					{
						$display[][$key] = $value;
					}
				}
				
				ksort($display);
				
				foreach ( $display as $value )
				{
					foreach ( $value as $vkey => $vvalue )
					{
						$sub[$vkey] = $vvalue;
					}
				}
				
				$mode_data = $sub;
				
				foreach ( $mode_data as $key => $value )
				{
					$lng = $key;
					$lng = isset($lang[$lng]) ? $lang[$lng] : $lng;
					
					$template->assign_block_vars("$mode._row", array(
						'LNG' => $lng,
						'KEY' => $key,
						
					#	'AID'	=> !($i % 2) ? 'current' : 'right',
					#	'LID'	=> !($i % 2) ? 'active' : 'right',
						'AID'	=> 'current',
						'LID'	=> 'active',
						
					#	'SPACE' => ( $key == 'subnavi_stats' ) ? '<br /><br /><br />' : '',
					));
					
				#	$i = ( $key == 'subnavi_stats' ) ? 2 : $i;
				#	$i = ( $key == 'subnavi_next_match' ) ? 2 : $i;
					
					foreach ( $value as $keys => $rows )
					{
						$keys = $keys;
						$lngs = isset($lang[$keys]) ? $lang[$keys] : $keys;
						
						$template->assign_block_vars("$mode.row._option", array(
							'KEYS' => $keys,
							'LNGS' => $lngs,
						));
						
						if ( $rows['type'] == 'input' )
						{
							$template->assign_block_vars("$mode.row._option._input", array(
								'TYPE'	=> $rows['type'],
								'VALUE' => ( $keys == 'filesize' ) ? ( $rows['value'] / 1048576 ) : $rows['value'],
								'CHECK' => ( $keys == 'path' ) ? ( is_writable($root_path . $rows['value']) ) ? img('i_iconn', 'icon_accept', '') : img('i_iconn', 'icon_cancel', '') : '',
							));
						}
						
						if ( $rows['type'] == 'opt' )
						{
							$template->assign_block_vars("$mode.row._option._opt_switch", array(
								'TYPE'	=> $rows['type'],
								
								'S_YES'	=> ( $rows['value'] == 1 ) ? 'checked="checked"' : '',
								'S_NO'	=> ( $rows['value'] == 0 )	? 'checked="checked"' : '',
							));
						}
						
						if ( strpos($rows['type'], 'input:') !== false )
						{
							$width = $height = '';
							
							if ( !empty($rows['value']) )
							{
								list($width, $height) = explode(':', $rows['value']);
							}
							
							$template->assign_block_vars("$mode.row._option._input_switch", array(
								'TYPE'		=> $rows['type'],
								'WIDTH'		=> $width,
								'HEIGHT'	=> $height,
							));
						}
						
						if ( strpos($rows['type'], 'opt:') !== false )
						{
							list($type, $lng) = explode(':', $rows['type']);
							
							foreach ( $lang[$lng] as $lkey => $lrow )
							{
								$template->assign_block_vars("$mode.row._option._opt_row", array(
									'L_LNG' => $lrow,
									
									'TYPE'	=> $rows['type'],
									'VALUE' => $lkey,
									
									'S_OPT'	=> ( $rows['value'] == $lkey )	? 'checked="checked"' : '',
								));
							}
						}
					}
					
				#	$i++;
				}
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_SET'			=> $lang['titles'],
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
	
	$fields .= '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['field']),
		
		'L_SET'		=> $lang['titles'],
		
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_MAIN'	=> $lang['navi_main'],
		'L_CLAN'	=> $lang['navi_clan'],
		'L_COM'		=> $lang['navi_com'],
		'L_MISC'	=> $lang['navi_misc'],
		'L_USER'	=> $lang['navi_user'],
		
		'S_SET'		=> check_sid("$file?mode=_settings"),
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
	$max_main	= maxi(NAVI, 'navi_order', 'navi_type = ' . NAVI_MAIN);
	$max_clan	= maxi(NAVI, 'navi_order', 'navi_type = ' . NAVI_CLAN);
	$max_com	= maxi(NAVI, 'navi_order', 'navi_type = ' . NAVI_COM);
	$max_misc	= maxi(NAVI, 'navi_order', 'navi_type = ' . NAVI_MISC);
	$max_user	= maxi(NAVI, 'navi_order', 'navi_type = ' . NAVI_USER);
	
	$tmp_main	= data(NAVI, 'navi_type = ' . NAVI_MAIN, 'navi_order ASC', 1, false);
	$tmp_clan	= data(NAVI, 'navi_type = ' . NAVI_CLAN, 'navi_order ASC', 1, false);
	$tmp_com	= data(NAVI, 'navi_type = ' . NAVI_COM, 'navi_order ASC', 1, false);
	$tmp_misc	= data(NAVI, 'navi_type = ' . NAVI_MISC, 'navi_order ASC', 1, false);
	$tmp_user	= data(NAVI, 'navi_type = ' . NAVI_USER, 'navi_order ASC', 1, false);
	
	if ( !$tmp_main )
	{
		$template->assign_block_vars('display.main_empty', array());
	}
	else
	{
		$cnt_main = count($tmp_main);
		
		for ( $i = $start; $i < $cnt_main; $i++ )
		{
			$id		= $tmp_main[$i]['navi_id'];
			$order	= $tmp_main[$i]['navi_order'];
			$lng	= $tmp_main[$i]['navi_lang'] ? $lang[$tmp_main[$i]['navi_name']] : $tmp_main[$i]['navi_name'];
			$lng	= $tmp_main[$i]['navi_intern'] ? sprintf($lang['sprintf_intern'], $lng) : $lng;
			
			$template->assign_block_vars('display.main_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $lng, ''),
				'LANG'		=> $tmp_main[$i]['navi_lang']	? img('i_icon', 'icon_lang', 'common_info_lang') : img('i_icon', 'icon_lang2', 'common_info_lang2'),
				'SHOW'		=> $tmp_main[$i]['navi_show']	? img('i_icon', 'icon_show', 'common_info_show') : img('i_icon', 'icon_show2', 'common_info_show2'),
				'INTERN'	=> $tmp_main[$i]['navi_intern']	? img('i_icon', 'icon_intern', 'common_info_intern') : img('i_icon', 'icon_intern2', 'common_info_intern2'),
				'URL'		=> $tmp_main[$i]['navi_url'],
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_MAIN, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_main )	? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_MAIN, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_clan )
	{
		$template->assign_block_vars('display.clan_empty', array());
	}
	else
	{
		$cnt_clan = count($tmp_clan);
		
		for ( $i = $start; $i < $cnt_clan; $i++ )
		{
			$id		= $tmp_clan[$i]['navi_id'];
			$order	= $tmp_clan[$i]['navi_order'];
			$lng	= $tmp_clan[$i]['navi_lang'] ? $lang[$tmp_clan[$i]['navi_name']] : $tmp_clan[$i]['navi_name'];
			$lng	= $tmp_clan[$i]['navi_intern'] ? sprintf($lang['sprintf_intern'], $lng) : $lng;
				
			$template->assign_block_vars('display.clan_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $lng, ''),
				'LANG'		=> $tmp_clan[$i]['navi_lang']	? img('i_icon', 'icon_lang', 'common_info_lang') : img('i_icon', 'icon_lang2', 'common_info_lang2'),
				'SHOW'		=> $tmp_clan[$i]['navi_show']	? img('i_icon', 'icon_show', 'common_info_show') : img('i_icon', 'icon_show2', 'common_info_show2'),
				'INTERN'	=> $tmp_clan[$i]['navi_intern']	? img('i_icon', 'icon_intern', 'common_info_intern') : img('i_icon', 'icon_intern2', 'common_info_intern2'),
				'URL'		=> $tmp_clan[$i]['navi_url'],
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_CLAN, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_clan )	? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_CLAN, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_com )
	{
		$template->assign_block_vars('display.com_empty', array());
	}
	else
	{
		$cnt_com = count($tmp_com);
		
		for ( $i = $start; $i < $cnt_com; $i++ )
		{
			$id		= $tmp_com[$i]['navi_id'];
			$order	= $tmp_com[$i]['navi_order'];
			$lng	= $tmp_com[$i]['navi_lang'] ? $lang[$tmp_com[$i]['navi_name']] : $tmp_com[$i]['navi_name'];
			$lng	= $tmp_com[$i]['navi_intern'] ? sprintf($lang['sprintf_intern'], $lng) : $lng;
			
			$template->assign_block_vars('display.com_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $lng, ''),
				'LANG'		=> $tmp_com[$i]['navi_lang']	? img('i_icon', 'icon_lang', 'common_info_lang') : img('i_icon', 'icon_lang2', 'common_info_lang2'),
				'SHOW'		=> $tmp_com[$i]['navi_show']	? img('i_icon', 'icon_show', 'common_info_show') : img('i_icon', 'icon_show2', 'common_info_show2'),
				'INTERN'	=> $tmp_com[$i]['navi_intern']	? img('i_icon', 'icon_intern', 'common_info_intern') : img('i_icon', 'icon_intern2', 'common_info_intern2'),
				'URL'		=> $tmp_com[$i]['navi_url'],
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_COM, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_com )	? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_COM, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_misc )
	{
		$template->assign_block_vars('display.misc_empty', array());
	}
	else
	{
		$cnt_misc = count($tmp_misc);
		
		for ( $i = $start; $i < $cnt_misc; $i++ )
		{
			$id		= $tmp_misc[$i]['navi_id'];
			$order	= $tmp_misc[$i]['navi_order'];
			$lng	= $tmp_misc[$i]['navi_lang'] ? $lang[$tmp_misc[$i]['navi_name']] : $tmp_misc[$i]['navi_name'];
			$lng	= $tmp_misc[$i]['navi_intern'] ? sprintf($lang['sprintf_intern'], $lng) : $lng;
				
			$template->assign_block_vars('display.misc_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $lng, ''),
				'LANG'		=> $tmp_misc[$i]['navi_lang']	? img('i_icon', 'icon_lang', 'common_info_lang') : img('i_icon', 'icon_lang2', 'common_info_lang2'),
				'SHOW'		=> $tmp_misc[$i]['navi_show']	? img('i_icon', 'icon_show', 'common_info_show') : img('i_icon', 'icon_show2', 'common_info_show2'),
				'INTERN'	=> $tmp_misc[$i]['navi_intern']	? img('i_icon', 'icon_intern', 'common_info_intern') : img('i_icon', 'icon_intern2', 'common_info_intern2'),
				'URL'		=> $tmp_misc[$i]['navi_url'],
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_MISC, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_misc )	? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_MISC, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	if ( !$tmp_user )
	{
		$template->assign_block_vars('display.user_empty', array());
	}
	else
	{
		$cnt_user = count($tmp_user);
		
		for ( $i = $start; $i < $cnt_user; $i++ )
		{
			$id		= $tmp_user[$i]['navi_id'];
            $order	= $tmp_user[$i]['navi_order'];
			$lng	= $tmp_user[$i]['navi_lang'] ? $lang[$tmp_user[$i]['navi_name']] : $tmp_user[$i]['navi_name'];
			$lng	= $tmp_user[$i]['navi_intern'] ? sprintf($lang['sprintf_intern'], $lng) : $lng;
			
			$template->assign_block_vars('display.user_row', array(
				'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $id), $lng, ''),
				'LANG'		=> $tmp_user[$i]['navi_lang']	? img('i_icon', 'icon_lang', 'common_info_lang') : img('i_icon', 'icon_lang2', 'common_info_lang2'),
				'SHOW'		=> $tmp_user[$i]['navi_show']	? img('i_icon', 'icon_show', 'common_info_show') : img('i_icon', 'icon_show2', 'common_info_show2'),
				'INTERN'	=> $tmp_user[$i]['navi_intern']	? img('i_icon', 'icon_intern', 'common_info_intern') : img('i_icon', 'icon_intern2', 'common_info_intern2'),
				'URL'		=> $tmp_user[$i]['navi_url'],
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_USER, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_user )	? href('a_img', $file, array('mode' => '_order', 'type' => NAVI_USER, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>