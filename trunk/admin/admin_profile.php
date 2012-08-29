<?php

if ( !empty($setmodules) )
{
	return;
}
else
{
	define('IN_CMS', true);

	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_profile';
	
	include('./pagestart.php');

	add_lang('profile');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_PROFILE;
	$url	= POST_PROFILE;
	$cat	= POST_CAT;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_cat	= request($cat, INT);
	$data_type	= request('cat_type', INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', INT);
	
	$dir_path	= $root_path . $settings['path_games'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['profile']);
	
	if ( $userdata['user_level'] != ADMIN )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_profile.tpl',
		'ajax'		=> 'style/ajax_order.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	if ( request('add_field', 1) || request('add_cat', 1) )
	{
		$mode = ( request('add_field', 1) ) ? 'create' : '_create_cat';
	
		if ( $mode == 'create' )
		{
			list($cat_id) = each($_POST['add_field']);
			$cat_id = intval($cat_id);
			
			$field_name = trim(htmlentities(str_replace("\'", "'", $_POST['field_name'][$cat_id]), ENT_COMPAT));
		}
	}
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
		case 'update':
			
				$template->assign_block_vars('input', array());
				
				$template->assign_vars(array('FILE' => 'ajax_order'));
				$template->assign_var_from_handle('AJAX', 'ajax');
				
				if ( $mode == 'create' && !$update )
				{
					$data_sql = array(
								'profile_name'			=> $field_name,
								'profile_cat'			=> $cat_id,
								'profile_type'			=> '0',
								'profile_field'			=> '',
								'profile_language'		=> '0',
								'profile_necessary'		=> '0',
								'profile_show_guest'	=> '0',
								'profile_show_member'	=> '0',
								'profile_show_register'	=> '0',
								'profile_order'			=> maxa(PROFILE, 'profile_order', $cat_id),
							);
				}
				else if ( $mode == 'update' && !$update )
				{
					$data_sql = data(PROFILE, $data_id, false, 1, 1);
				}
				else
				{
					$data_sql = array(
								'profile_name'			=> request('profile_name', 2),
								'profile_cat'			=> request('profile_cat', 0),
								'profile_type'			=> request('profile_type', 0),
								'profile_field'			=> strtolower(str_replace('profile_', '', request('profile_field', 2))),
								'profile_language'		=> request('profile_language', 0),
								'profile_necessary'		=> request('profile_necessary', 0),
								'profile_show_guest'	=> request('profile_show_guest', 0),
								'profile_show_member'	=> request('profile_show_member', 0),
								'profile_show_register'	=> request('profile_show_register', 0),
								'profile_order'			=> request('profile_order', 0) ? request('profile_order', 0) : request('profile_order_new', 0),
							);
				}
			
				$data_cat = data(PROFILE_CAT, false, 'cat_order ASC', 1, false);
				
				for ( $i = 0; $i < count($data_cat); $i++ )
				{
					$template->assign_block_vars('input._cat', array(
						'CAT_ID'	=> $data_cat[$i]['cat_id'],
						'CAT_NAME'	=> $data_cat[$i]['cat_name'],

						'S_MARK'	=> ( $data_cat[$i]['cat_id'] == $data['profile_cat'] ) ? 'checked="checked"' : '',
					));
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
				$fields .= "<input type=\"hidden\" name=\"current_field\" value=\"" . $data['profile_field'] . "\" />";

				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['field'], $data['profile_name']),
				
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['field']),
					'L_FIELD'		=> $lang['field'],
					'L_CAT'			=> $lang['common_cat'],
					'L_LANGUAGE'	=> $lang['common_language'],
					'L_TYPE'		=> sprintf($lang['sprintf_type'], $lang['field']),
					
					'L_SHOW'		=> $lang['common_view'],
					'L_GUEST'		=> sprintf($lang['sprintf_select_format'], $lang['show_guest']),
					'L_MEMBER'		=> sprintf($lang['sprintf_select_format'], $lang['show_member']),
					'L_REGISTER'	=> sprintf($lang['sprintf_select_format'], $lang['show_register']),
					
					'L_NECESSARY'	=> $lang['necessary'],

					'L_TYPE_TEXT'	=> $lang['type_text'],
					'L_TYPE_AREA'	=> $lang['type_area'],
					
					'NAME'			=> $data['profile_name'],
					
					'CUR_CAT'	=> $data['profile_cat'],
					'CUR_ORDER'	=> $data['profile_order'],
					
					'FIELD'			=> str_replace('profile_', '', $data['profile_field']),
					
					'S_TYPE_AREA'	=> (!$data['profile_type'] ) ? ' checked="checked"' : '',
					'S_TYPE_TEXT'	=> ( $data['profile_type'] ) ? ' checked="checked"' : '',
					'S_LANG_NO'		=> (!$data['profile_language'] ) ? ' checked="checked"' : '',
					'S_LANG_YES'	=> ( $data['profile_language'] ) ? ' checked="checked"' : '',
					
					'S_GUEST_NO'		=> (!$data['profile_show_guest'] ) ? ' checked="checked"' : '',
					'S_GUEST_YES'		=> ( $data['profile_show_guest'] ) ? ' checked="checked"' : '',
					'S_MEMBER_NO'		=> (!$data['profile_show_member'] ) ? ' checked="checked"' : '',
					'S_MEMBER_YES'		=> ( $data['profile_show_member'] ) ? ' checked="checked"' : '',
					'S_REGISTER_NO'		=> (!$data['profile_show_register'] ) ? ' checked="checked"' : '',
					'S_REGISTER_YES'	=> ( $data['profile_show_register'] ) ? ' checked="checked"' : '',
					'S_NECESSARY_NO'	=> (!$data['profile_necessary'] ) ? ' checked="checked"' : '',
					'S_NECESSARY_YES'	=> ( $data['profile_necessary'] ) ? ' checked="checked"' : '',
					
					'S_ORDER'	=> simple_order(PROFILE, $data['profile_cat'], 'select', $data['profile_order']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( $update )
				{
					$error[] = ( !$data['profile_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_name'] : '';
					$error[] = ( !$data['profile_field'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_profilefield'] : '';
					
					if ( !$error )
					{
						$data['profile_field'] = 'profile_' . $data['profile_field'] . '';
						$data['profile_order'] = ( !$data['profile_order'] ) ? maxa(PROFILE, 'profile_order', $data['profile_cat']) : $data['profile_order'];
						
						if ( $mode == 'create' )
						{
							$db_data = sql(PROFILE, $mode, $data_sql);
							
							$sql = "ALTER TABLE " . PROFILE_DATA . " ADD `" . $data['profile_field'] . "`";
							$sql .= ( $data['profile_type'] ) ? " VARCHAR ( 255 ) NOT NULL;" : " TEXT NOT NULL;";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create']
								. sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$db_data = sql(PROFILE, $mode, $data_sql, 'profile_id', $data);
							
							$sql = "ALTER TABLE " . PROFILE_DATA . " CHANGE `" . request('current_field', 1) . "` `" . $data['profile_field'] . "`";
							$sql .= ( $data['profile_type'] ) ? " VARCHAR ( 255 ) NOT NULL;" : " TEXT NOT NULL;";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&amp;id=$data"));
						}
						
						orders(PROFILE, $data['profile_cat']);
						
						log_add(LOG_ADMIN, $log, $mode, $db_data);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				$template->pparse('body');
				
				break;
			
			case 'order_field':
				
				update(PROFILE, 'profile', $move, $data_id);
				orders(PROFILE, $data_type);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
			
//			case 'delete_field':
//			
//				if ( $profile_id && $confirm )
//				{	
//					$profile = get_data('profile', $profile_id, INT);
//
//					$sql = 'DELETE FROM ' . NAVI . " WHERE profile_id = $profile_id";
//					if ( !($result = $db->sql_query($sql)) )
//					{
//						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
//					}
//				
//					log_add(LOG_ADMIN, $log, 'acp_profile_delete', $profile['profile_name']);
//					
//					$message = $lang['delete_profile'] . sprintf($lang['return'], check_sid($file), $acp_title);
//					message(GENERAL_MESSAGE, $message);
//				
//				}
//				else if ( $profile_id && !$confirm )
//				{
//					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
//		
//					$fields = '<input type="hidden" name="mode" value="delete" />';
//					$fields .= '<input type="hidden" name="' . $cat . '" value="' . $profile_id . '" />';
//		
//					$template->assign_vars(array(
//						'MESSAGE_TITLE'		=> $lang['common_confirm'],
//						'MESSAGE_TEXT'		=> $lang['confirm_delete_profile'],
//		
//						'L_YES'				=> $lang['common_yes'],
//						'L_NO'				=> $lang['common_no'],
//		
//						'S_ACTION'	=> check_sid($file),
//						'S_FIELDS'	=> $fields,
//					));
//				}
//				else
//				{
//					message(GENERAL_MESSAGE, $lang['msg_must_select_profile']);
//				}
//				
//				$template->pparse('body');
//				
//				break;
			
			case 'create_cat':
			case 'update_cat':
			
				$template->assign_block_vars('input_cat', array());
				
				if ( $mode == '_create_cat' && !$update )
				{
					$data_sql = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_order'	=> '',
							);
				}
				else if ( $mode == '_update_cat' && !$update )
				{
					$data_sql = data(PROFILE_CAT, $data_cat, false, 1, 1);
				}
				else
				{
					$data_sql = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_order'	=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
							);
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data_cat\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_cat', '', $mode)], $lang['profile_c'], $data['cat_name']),
					'L_NAME'	=> sprintf($lang['sprintf_cat'], $lang['field']),
					
					'NAME'		=> $data['cat_name'],
					
					'S_ORDER'	=> simple_order(PROFILE_CAT, false, 'select', $data['cat_order']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( $update )
				{
					debug($_POST);
					
					$data['cat_order'] = ( !$data['cat_order'] ) ? maxa(PROFILE_CAT, 'cat_order', false) : $data['cat_order'];
					
					$error[] = ( !$data['cat_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create_cat' )
						{
							$db_data = sql(PROFILE_CAT, $mode, $data_sql);
							
							$message = $lang['create_c']
								. sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							foreach ( $data as $key => $var )
							{
								$input[] = "$key = '$var'";
							}
							
							$sql = "UPDATE " . PROFILE_CAT . " SET " . implode(', ', $input) . " WHERE cat_id = $data_cat";
						#	$sql = "UPDATE " . PROFILE_CAT . " SET cat_name = '$cat_name' WHERE cat_id = $data_cat";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_c'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$cat=$data_cat"));
						}
						
						orders(PROFILE_CAT);
						
						log_add(LOG_ADMIN, $log, $mode, $data['cat_name']);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				$template->pparse('body');
			
				break;
			
			case 'order_cat':
				
				update(PROFILE_CAT, 'cat', $move, $data_cat);
				orders(PROFILE_CAT);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case 'delete_cat':
			
				$data_sql = data(PROFILE_CAT, $data_cat, false, 1, 1);
			
				if ( $data_cat && $confirm )
				{
				#	sql(PROFILE_CAT, 'delete', false, 'cat_id', $data);
					$sql = "DELETE FROM " . PROFILE_CAT . " WHERE cat_id = $data_cat";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['delete_c'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					#$oCache -> sCachePath = './../cache/';
					#$oCache -> deleteCache('_display_navi_news');
					
					orders(PROFILE_CAT);
					
					log_add(LOG_ADMIN, $log, $mode, $data['cat_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_cat && !$confirm )
				{
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$cat\" value=\"$data_cat\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm_c'], $data['cat_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['profile_cat'])); }
				
				$template->pparse('confirm');
				
				break;
				
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['profile']),
		'L_EXPLAIN'	=> $lang['explain'],
		
		'L_CREATE_CAT'		=> sprintf($lang['sprintf_create'], $lang['cat']),
		'L_CREATE_FIELD'	=> sprintf($lang['sprintf_new_creates'], $lang['field']),
		
		'S_ACTION'	=> check_sid($file),
	));
	
	$max = maxi(PROFILE_CAT, 'cat_order', '');
	$tmp = data(PROFILE_CAT, false, 'cat_order ASC', 1, false);
	
	if ( $tmp )
	{
		$cnt = count($tmp);
		
		for ( $i = 0; $i < $cnt; $i++ )
		{
			$cid	= $tmp[$i]['cat_id'];
			$name	= $tmp[$i]['cat_name'];
			$order	= $tmp[$i]['cat_order'];
			
			$profile = data(PROFILE, 'profile_cat = ' . $cid, 'profile_cat, profile_order ASC', 1, false);
			
			$sql = "SELECT MAX(profile_order) AS max$cid FROM " . PROFILE . " WHERE profile_cat = $cid";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_profile = $db->sql_fetchrow($result);
			
			
			
			$template->assign_block_vars('display.cat_row', array( 
				'ID'		=> $tmp[$i]['cat_id'],
			#	'NAME'		=> $tmp[$i]['cat_name'],
				
				'NAME'		=> href('a_txt', $file, array('mode' => '_update_cat', $cat => $cid), $name, $name),
				
				'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order_cat', 'move' => '-15', $cat => $cid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
				'MOVE_DOWN'	=> ( $order != $max ) ? href('a_img', $file, array('mode' => '_order_cat', 'move' => '+15', $cat => $cid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),

				'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $cat => $cid), 'icon_update', 'common_update'),
				'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $cat => $cid), 'icon_cancel', 'common_delete'),
				
			#	'MOVE_UP'	=> ( $tmp[$i]['cat_order'] != '10' )		? '<a id="right" href="' . check_sid("$file?mode=_order_cat&amp;move=-15&amp;$cat=$cid") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" /></a>',
			#	'MOVE_DOWN'	=> ( $tmp[$i]['cat_order'] != $max )	? '<a id="right" href="' . check_sid("$file?mode=_order_cat&amp;move=+15&amp;$cat=$cid") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" /></a>',
				
			#	'U_UPDATE'	=> check_sid("$file?mode=_update_cat&amp;$cat=$cid"),
			#	'U_DELETE'	=> check_sid("$file?mode=_delete_cat&amp;$cat=$cid"),
				
				'S_NAME'	=> "field_name[$cid]",
				'S_SUBMIT'	=> "add_field[$cid]",
			));
			
			if ( !$profile )
			{
				$template->assign_block_vars('display.catrow._entry_empty', array());
			}
			else
			{
				for ( $j = 0; $j < count($profile); $j++ )
				{
				#	$profile_id = $profile[$j]['profile_id'];
				#	$profile_cat = $profile[$j]['profile_cat'];
					
					$pid	= $profile[$j]['profile_id'];
					$pcat	= $profile[$j]['profile_cat'];
					$name	= $profile[$j]['profile_name'];
					$field	= $profile[$j]['profile_field'];
					$order	= $profile[$j]['profile_order'];
	
					if ( $pcat == $cid )
					{
						$template->assign_block_vars('display.catrow._profile_row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', $url => $pid), $name, $name),
							'FIELD'		=> $field,
						
							'MOVE_UP'	=> ( $order != '10' ) ? href('a_img', $file, array('mode' => '_order', 'move' => '-15', $url => $pid), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $order != $max_profile['max' . $cid] ) ? href('a_img', $file, array('mode' => '_order', 'move' => '+15', $url => $pid), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
			
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', $url => $pid), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', $url => $pid), 'icon_cancel', 'common_delete'),
						#
						#	'NAME'	=> $profile[$j]['profile_name'],
						#	'FIELD'	=> $profile[$j]['profile_field'],
							
						#	'MOVE_UP'	=> ( $profile[$j]['profile_order'] != '10' )						? '<a href="' . check_sid("$file?mode=_order_field&amp;cat_type=$cid&amp;move=-15&amp;$url=$pid") . '"><img src="' . $images['icon_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_u2'] . '" alt="" />',
						#	'MOVE_DOWN'	=> ( $profile[$j]['profile_order'] != $max_profile['max' . $cid] )	? '<a href="' . check_sid("$file?mode=_order_field&amp;cat_type=$cid&amp;move=+15&amp;$url=$pid") . '"><img src="' . $images['icon_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_arrow_d2'] . '" alt="" />',
							
						#	'U_UPDATE' => check_sid("$file?mode=_update&amp;$url=$pid"),
						#	'U_DELETE' => check_sid("$file?mode=_delete&amp;$url=$pid"),
						));
					}
				}
			}
		}
	}

	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>