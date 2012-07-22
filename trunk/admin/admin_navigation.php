<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_navi'] )
	{
		$module['hm_system']['sm_navi'] = $root_file;
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
	#	'ajax'		=> 'style/ajax_order.tpl',
	#	'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	debug($_POST);
	
	$mode = ( in_array($mode, array('create', 'update', 'order', 'delete')) ) ? $mode : '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
			#	$template->assign_var_from_handle('AJAX', 'ajax');
			
				$vars = array(
					'navi' => array(
						'title' => 'data_input',
						'navi_name'		=> array('validate' => TXT,	'type' => 'text:25:25',		'explain' => true, 'required' => 'input_name', 'check' => true),
						'navi_url'		=> array('validate' => TXT,	'type' => 'drop:files',		'explain' => true),
						'navi_type'		=> array('validate' => INT,	'type' => 'radio:type',		'explain' => true, 'params' => true, 'ajax' => 'ajax_order:ajax_order'),
						'navi_target'	=> array('validate' => INT,	'type' => 'radio:target',	'explain' => true),
						'navi_intern'	=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'navi_show'		=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'navi_lang'		=> array('validate' => INT,	'type' => 'radio:yesno',	'explain' => true),
						'navi_order'	=> array('validate' => INT,	'type' => 'drop:order',		'explain' => true),
					),
				);
				
				if ( $mode == 'create' && !request('submit', TXT) )
				{
					
				#	$cat_id = key($_POST['add_field']);
				#	$name = request(array('sub_field', $cat_id), TXT);
			
					$type = ( isset($_POST['navi_type']) ) ? key($_POST['navi_type']) : '';
					$name = ( isset($_POST['navi_name']) ) ? str_replace("\'", "'", $_POST['navi_name'][$type]) : '';
					
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
					$data = build_request(NAVI, $vars, 'navi', $error);
					
					if ( !$error )
					{
						$data['navi_url'] = $data['navi_target'] ? set_http($data['navi_url']) : './' . $data['navi_url'];
						$data['navi_order']	= $data['navi_order'] ? $data['navi_order'] : maxa(NAVI, 'navi_order', 'navi_type = ' . $data['navi_type']);
						
						if ( $mode == 'create' )
						{
							$sql = sql(NAVI, $mode, $data);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(NAVI, $mode, $data, 'navi_id', $data_id);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&$url=$data_id"));
						}
						
						orders(NAVI, $data['navi_type']);
						
						$oCache->deleteCache('dsp_navi');
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output($data, $vars, 'input', false, NAVI);
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'		=> sprintf($lang["sprintf_$mode"], $lang['field'], $data['navi_name']),
				
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));

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
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
	
	$fields .= '<input type="hidden" name="mode" value="create" />';
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE'	=> sprintf($lang['sprintf_create'], $lang['field']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_MAIN'	=> $lang['navi_main'],
		'L_CLAN'	=> $lang['navi_clan'],
		'L_COM'		=> $lang['navi_com'],
		'L_MISC'	=> $lang['navi_misc'],
		'L_USER'	=> $lang['navi_user'],
		
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
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_MAIN, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_main )	? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_MAIN, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
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
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_CLAN, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_clan )	? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_CLAN, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
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
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_COM, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_com )	? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_COM, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
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
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_MISC, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_misc )	? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_MISC, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
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
				
				'MOVE_UP'	=> ( $order != '10' )		? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_USER, 'move' => '-15', $url => $id), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max_user )	? href('a_img', $file, array('mode' => 'order', 'type' => NAVI_USER, 'move' => '+15', $url => $id), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $id), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $id), 'icon_cancel', 'common_delete'),
			));
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>