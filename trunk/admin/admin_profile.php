<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($userauth['auth_navi'] || $userdata['user_level'] == ADMIN)
	{
		$module['main']['navi'] = $filename;
	}
	return;
}
else
{
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	include($root_path . 'includes/functions_admin.php');
	
	if (!$userauth['auth_games'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_navigation.php", true));
	}
	
	if ( isset($HTTP_POST_VARS[POST_NAVIGATION_URL]) || isset($HTTP_GET_VARS[POST_NAVIGATION_URL]) )
	{
		$navi_id = ( isset($HTTP_POST_VARS[POST_NAVIGATION_URL]) ) ? intval($HTTP_POST_VARS[POST_NAVIGATION_URL]) : intval($HTTP_GET_VARS[POST_NAVIGATION_URL]);
	}
	else
	{
		$navi_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		if (isset($HTTP_POST_VARS['add']))
		{
			$mode = 'add';
		}
		else
		{
			$mode = '';
		}
	}
	
	$show_index = '';
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
				
				if ( $mode == 'edit' )
				{
					$navi		= get_data('navi', $navi_id, 0);
					$new_mode	= 'editrank';
				}
				else if ( $mode == 'add' )
				{
					$navi = array (
						'navi_name'		=> trim($HTTP_POST_VARS['navi_name']),
						'navi_type'		=> '1',
						'navi_url'		=> '',
						'navi_lang'		=> '1',
						'navi_show'		=> '1',
						'navi_target'	=> '0',
						'navi_intern'	=> '0',
					);

					$new_mode = 'addnavi';
				}
				$template->set_filenames(array('body' => './../admin/style/acp_navigation.tpl'));
				$template->assign_block_vars('navigation_edit', array());

				$navi_url = str_replace('./', '', $navi['navi_url']);
				
				$folder = $root_path;
				$files = scandir($folder);
				
				$filename_list = '';
				$filename_list .= '<select name="navi_url" class="post" onchange="select.value = this.value;">';
				$filename_list .= '<option value="">----------</option>';
				
				foreach ($files as $file)
				{
					if ( strstr($file, '.php') )
					{
						$selected = ( $file == $navi_url ) ? ' selected="selected"' : '';
						$filename_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$filename_list .= '</select>';
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_NAVIGATION_URL . '" value="' . $navi_id . '" />';

				$template->assign_vars(array(
					'L_NAVI_HEAD'			=> $lang['navi_head'],
					'L_NAVI_NEW_EDIT'		=> ($mode == 'add') ? $lang['navi_add'] : $lang['navi_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_NAVI_NAME'			=> $lang['navi_name'],
					'L_NAVI_URL'			=> $lang['navi_url'],
					'L_NAVI_TYPE'			=> $lang['navi_type'],
					'L_NAVI_LANGUAGE'		=> $lang['navi_language'],
					'L_NAVI_SHOW'			=> $lang['navi_show'],
					'L_NAVI_INTERN'			=> $lang['navi_intern'],
					'L_NAVI_TARGET'			=> $lang['navi_target'],
					'L_NAVI_NEW'			=> $lang['navi_new'],
					'L_NAVI_SELF'			=> $lang['navi_self'],
					'L_TYPE_MAIN'			=> $lang['navi_main'],
					'L_TYPE_CLAN'			=> $lang['navi_clan'],
					'L_TYPE_COM'			=> $lang['navi_com'],
					'L_TYPE_MISC'			=> $lang['navi_misc'],
					'L_TYPE_USER'			=> $lang['navi_user'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'NAVI_NAME'				=> $navi['navi_name'],
					'NAVI_URL'				=> $navi_url,
					
					'CHECKED_LANG_YES'		=> ( $navi['navi_lang'] ) ? ' checked="checked"' : '',
					'CHECKED_LANG_NO'		=> ( !$navi['navi_lang'] ) ? ' checked="checked"' : '',
					'CHECKED_SHOW_YES'		=> ( $navi['navi_show'] ) ? ' checked="checked"' : '',
					'CHECKED_SHOW_NO'		=> ( !$navi['navi_show'] ) ? ' checked="checked"' : '',
					'CHECKED_INTERN_YES'	=> ( $navi['navi_intern'] ) ? ' checked="checked"' : '',
					'CHECKED_INTERN_NO'		=> ( !$navi['navi_intern'] ) ? ' checked="checked"' : '',
					'CHECKED_TARGET_NEW'	=> ( $navi['navi_target'] ) ? ' checked="checked"' : '',
					'CHECKED_TARGET_SELF'	=> ( !$navi['navi_target'] ) ? ' checked="checked"' : '',
					'CHECKED_TYPE_MAIN'		=> ( $navi['navi_type'] == '1' ) ? ' checked="checked"' : '',
					'CHECKED_TYPE_CLAN'		=> ( $navi['navi_type'] == '2' ) ? ' checked="checked"' : '',
					'CHECKED_TYPE_COM'		=> ( $navi['navi_type'] == '3' ) ? ' checked="checked"' : '',
					'CHECKED_TYPE_MISC'		=> ( $navi['navi_type'] == '4' ) ? ' checked="checked"' : '',
					'CHECKED_TYPE_USER'		=> ( $navi['navi_type'] == '5' ) ? ' checked="checked"' : '',
					
					'S_FILENAME_LIST'		=> $filename_list,
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_NAVI_ACTION'			=> append_sid("admin_navigation.php")
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addnavi':
				
				$navi_name		= ( isset($HTTP_POST_VARS['navi_name']) )	? trim($HTTP_POST_VARS['navi_name']) : '';
				$navi_type		= ( isset($HTTP_POST_VARS['navi_type']))	? intval($HTTP_POST_VARS['navi_type']) : 0;
				$navi_url		= ( isset($HTTP_POST_VARS['navi_url']) )	? trim($HTTP_POST_VARS['navi_url']) : '';
				$navi_lang		= ( $HTTP_POST_VARS['navi_lang'] == 1 )		? 1 : 0;
				$navi_show		= ( $HTTP_POST_VARS['navi_show'] == 1 )		? 1 : 0;
				$navi_target	= ( $HTTP_POST_VARS['navi_target'] == 1 )	? 1 : 0;
				$navi_intern	= ( $HTTP_POST_VARS['navi_intern'] == 1 )	? 1 : 0;
				
				if ( $navi_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back'], '');
				}
	
				$sql = 'SELECT MAX(navi_order) AS max_order FROM ' . NAVIGATION . ' WHERE navi_type = ' . intval($HTTP_POST_VARS['navi_type']);
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);

				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
				
				// There is no problem having duplicate forum names so we won't check for it.
				$sql = 'INSERT INTO ' . NAVIGATION . " (navi_name, navi_type, navi_url, navi_lang, navi_show, navi_target, navi_intern, navi_order)
					VALUES ('" . str_replace("\'", "''", $navi_name) . "', '" . $navi_type . "', '" . str_replace("\'", "''", $navi_url) . "', $navi_lang, $navi_show, $navi_target, $navi_intern, $next_order)";
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_add', $navi_name);
	
				$message = $lang['create_navigation'] . '<br /><br />' . sprintf($lang['click_return_navigation'], '<a href="' . append_sid("admin_navigation.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editrank':
			
				$navi_name		= ( isset($HTTP_POST_VARS['navi_name']) )	? trim($HTTP_POST_VARS['navi_name']) : '';
				$navi_type		= ( isset($HTTP_POST_VARS['navi_type']))	? intval($HTTP_POST_VARS['navi_type']) : 0;
				$navi_url		= ( isset($HTTP_POST_VARS['navi_url']) )	? trim($HTTP_POST_VARS['navi_url']) : '';
				$navi_lang		= ( $HTTP_POST_VARS['navi_lang'] == 1 )		? 1 : 0;
				$navi_show		= ( $HTTP_POST_VARS['navi_show'] == 1 )		? 1 : 0;
				$navi_target	= ( $HTTP_POST_VARS['navi_target'] == 1 )	? 1 : 0;
				$navi_intern	= ( $HTTP_POST_VARS['navi_intern'] == 1 )	? 1 : 0;
				
				if ( $navi_name == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back'], '');
				}

				$sql = "UPDATE " . NAVIGATION . " SET
							navi_name		= '" . str_replace("\'", "''", $navi_name) . "',
							navi_type		= $navi_type,
							navi_url		= './" . $navi_url . "',
							navi_lang		= $navi_lang,
							navi_show		= $navi_show,
							navi_target		= $navi_target,
							navi_intern		= $navi_intern
						WHERE navi_id = " . intval($HTTP_POST_VARS[POST_NAVIGATION_URL]);
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_edit');
				
				$message = $lang['update_navigation'] . '<br /><br />' . sprintf($lang['click_return_navigation'], '<a href="' . append_sid("admin_navigation.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $navi_id && $confirm )
				{	
					$navi = get_data('navi', $navi_id, 0);

					$sql = 'DELETE FROM ' . NAVIGATION . " WHERE navi_id = $navi_id";
					$result = $db->sql_query($sql);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_delete', $navi['navi_name']);
					
					$message = $lang['delete_navigation'] . '<br /><br />' . sprintf($lang['click_return_navigation'], '<a href="' . append_sid("admin_navigation.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $navi_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_NAVIGATION_URL . '" value="' . $navi_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_navigation'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_navigation.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_profile']);
				}
				
				$template->pparse('body');
				
				break;
			
			case 'order_main':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NAVIGATION . " SET navi_order = navi_order + $move WHERE navi_id = $navi_id";
				$result = $db->sql_query($sql);
		
				renumber_order('navi', NAVI_MAIN);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_order', NAVI_MAIN);
				
				$show_index = TRUE;
	
				break;
			
			case 'order_clan':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NAVIGATION . " SET navi_order = navi_order + $move WHERE navi_id = $navi_id";
				$result = $db->sql_query($sql);
		
				renumber_order('navi', NAVI_CLAN);
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_order', NAVI_CLAN);
				
				$show_index = TRUE;
	
				break;
			
			case 'order_com':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NAVIGATION . " SET navi_order = navi_order + $move WHERE navi_id = $navi_id";
				$result = $db->sql_query($sql);
		
				renumber_order('navi', NAVI_COM);
					
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_order', NAVI_COM);
				
				$show_index = TRUE;
	
				break;
			
			case 'order_misc':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NAVIGATION . " SET navi_order = navi_order + $move WHERE navi_id = $navi_id";
				$result = $db->sql_query($sql);
		
				renumber_order('navi', NAVI_MISC);
					
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_order', NAVI_MISC);
				
				$show_index = TRUE;
	
				break;
				
			case 'order_user':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NAVIGATION . " SET navi_order = navi_order + $move WHERE navi_id = $navi_id";
				$result = $db->sql_query($sql);
		
				renumber_order('navi', NAVI_USER);
					
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_order', NAVI_USER);
				
				$show_index = TRUE;
	
				break;
	
			default:
				message_die(GENERAL_ERROR, $lang['no_select_module'], '');
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/acp_navigation.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_NAVI_TITLE'		=> $lang['navi_head'],
		'L_NAVI_EXPLAIN'	=> $lang['navi_explain'],
		
		'L_NAVI_MAIN'		=> $lang['navi_main'],
		'L_NAVI_CLAN'		=> $lang['navi_clan'],
		'L_NAVI_COM'		=> $lang['navi_com'],
		'L_NAVI_MISC'		=> $lang['navi_misc'],
		'L_NAVI_USER'		=> $lang['navi_user'],
		
		'L_LANGUAGE'		=> $lang['navi_language'],
		'L_SHOW'			=> $lang['navi_show'],
		
		'L_NAVI_ADD'		=> $lang['navi_add'],
		
		'L_EDIT'			=> $lang['edit'],
		'L_SETTINGS'		=> $lang['settings'],
		'L_DELETE'			=> $lang['delete'],
		
		'L_MOVE_UP'			=> $lang['Move_up'], 
		'L_MOVE_DOWN'		=> $lang['Move_down'], 
		
		'S_TEAM_ACTION'		=> append_sid("admin_navigation.php")
	));
	
	$sql = 'SELECT MAX(navi_order) AS max FROM ' . NAVIGATION . ' WHERE navi_type = ' . NAVI_MAIN;
	$result = $db->sql_query($sql);
	$max_main = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT MAX(navi_order) AS max FROM ' . NAVIGATION . ' WHERE navi_type = ' . NAVI_CLAN;
	$result = $db->sql_query($sql);
	$max_clan = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT MAX(navi_order) AS max FROM ' . NAVIGATION . ' WHERE navi_type = ' . NAVI_COM;
	$result = $db->sql_query($sql);
	$max_com = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT MAX(navi_order) AS max FROM ' . NAVIGATION . ' WHERE navi_type = ' . NAVI_MISC;
	$result = $db->sql_query($sql);
	$max_misc = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT MAX(navi_order) AS max FROM ' . NAVIGATION . ' WHERE navi_type = ' . NAVI_USER;
	$result = $db->sql_query($sql);
	$max_user = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$sql = 'SELECT * FROM ' . NAVIGATION . ' ORDER BY navi_type ASC, navi_order ASC';
	$result = $db->sql_query($sql);
	
	$color = '';
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$class = ($color % 2) ? 'row_class1' : 'row_class2';
		$color++;
		
		$navi_id	= $row['navi_id'];
		$navi_lang	= ($row['navi_lang']) ? $lang[$row['navi_name']] : $row['navi_name'];
		
		if ($row['navi_type'] == NAVI_MAIN)
		{
			$template->assign_block_vars('display.main_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> ( $row['navi_intern']) ? '<em><b>' . $navi_lang . '</b></em>' : $navi_lang,
				
				'NAVI_LANG'		=> ( $row['navi_lang'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
				'NAVI_SHOW'		=> ( $row['navi_show'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
				
				'MOVE_UP'		=> ( $row['navi_order'] != '10' )				? '<a href="' . append_sid("admin_navigation.php?mode=order_main&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $row['navi_order'] != $max_main['max'] )	? '<a href="' . append_sid("admin_navigation.php?mode=order_main&amp;move=15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
			));
		}
		else if ($row['navi_type'] == NAVI_CLAN)
		{
			$template->assign_block_vars('display.clan_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> ( $row['navi_intern']) ? '<em><b>' . $navi_lang . '</b></em>' : $navi_lang,
				
				'NAVI_LANG'		=> ( $row['navi_lang'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
				'NAVI_SHOW'		=> ( $row['navi_show'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
				
				'MOVE_UP'		=> ( $row['navi_order'] != '10' )				? '<a href="' . append_sid("admin_navigation.php?mode=order_clan&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $row['navi_order'] != $max_clan['max'] )	? '<a href="' . append_sid("admin_navigation.php?mode=order_clan&amp;move=15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
			));
		}
		else if ($row['navi_type'] == NAVI_COM)
		{
			$template->assign_block_vars('display.com_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> ( $row['navi_intern']) ? '<em><b>' . $navi_lang . '</b></em>' : $navi_lang,
				
				'NAVI_LANG'		=> ( $row['navi_lang'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
				'NAVI_SHOW'		=> ( $row['navi_show'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
				
				'MOVE_UP'		=> ( $row['navi_order'] != '10' )				? '<a href="' . append_sid("admin_navigation.php?mode=order_com&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $row['navi_order'] != $max_com['max'] )	? '<a href="' . append_sid("admin_navigation.php?mode=order_com&amp;move=15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
			));
		}
		else if ($row['navi_type'] == NAVI_MISC)
		{
			$template->assign_block_vars('display.misc_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> ( $row['navi_intern']) ? '<em><b>' . $navi_lang . '</b></em>' : $navi_lang,
				
				'NAVI_LANG'		=> ( $row['navi_lang'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
				'NAVI_SHOW'		=> ( $row['navi_show'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',

				'MOVE_UP'		=> ( $row['navi_order'] != '10' )				? '<a href="' . append_sid("admin_navigation.php?mode=order_misc&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $row['navi_order'] != $max_misc['max'] )	? '<a href="' . append_sid("admin_navigation.php?mode=order_misc&amp;move=15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
			));
		}
		else if ($row['navi_type'] == NAVI_USER)
		{
			$template->assign_block_vars('display.user_row', array(
				'CLASS' 		=> $class,
				'NAVI_TITLE'	=> ( $row['navi_intern']) ? '<em><b>' . $navi_lang . '</b></em>' : $navi_lang,
				
				'NAVI_LANG'		=> ( $row['navi_lang'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
				'NAVI_SHOW'		=> ( $row['navi_show'] ) ? '<img src="' . $images['icon_acp_yes'] . '" alt="" >' : '<img src="' . $images['icon_acp_no'] . '" alt="" >',
				
				'MOVE_UP'		=> ( $row['navi_order'] != '10' )				? '<a href="' . append_sid("admin_navigation.php?mode=order_user&amp;move=-15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $row['navi_order'] != $max_user['max'] )	? '<a href="' . append_sid("admin_navigation.php?mode=order_user&amp;move=15&amp;" . POST_NAVIGATION_URL . "=" . $navi_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',

				'U_DELETE'		=> append_sid("admin_navigation.php?mode=delete&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
				'U_EDIT'		=> append_sid("admin_navigation.php?mode=edit&amp;" . POST_NAVIGATION_URL . "=" . $navi_id),
			));
		}
		
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>
