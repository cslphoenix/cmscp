<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	
	if ( $userauth['auth_newscat'] || $userdata['user_level'] == ADMIN )
	{
		$module['news']['newscat'] = $filename;
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
	
	if ( !$userauth['auth_newscat'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_newscat.php', true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_NEWSCAT_URL]) || isset($HTTP_GET_VARS[POST_NEWSCAT_URL]) )
	{
		$news_category_id = ( isset($HTTP_POST_VARS[POST_NEWSCAT_URL]) ) ? intval($HTTP_POST_VARS[POST_NEWSCAT_URL]) : intval($HTTP_GET_VARS[POST_NEWSCAT_URL]);
	}
	else
	{
		$news_category_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
	}
	else
	{
		if ( isset($HTTP_POST_VARS['newscat_add']) || isset($HTTP_GET_VARS['newscat_add']) )
		{
			$mode = 'newscat_add';
		}
		else
		{
			$mode = '';
		}
	}
	
	$show_index = '';
		
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case 'newscat_add':
			case 'newscat_edit':
				
				if ( $mode == 'newscat_edit' )
				{
					$newscat	= get_data('newscat', $news_category_id, 0);
					$new_mode	= 'newscat_update';
				}
				else
				{
					$newscat  = array (
						'news_category_title'	=> ( isset($HTTP_POST_VARS['news_category_title']) ) ? trim($HTTP_POST_VARS['news_category_title']) : '',
						'news_category_image'	=> '',
					);
		
					$new_mode = 'newscat_create';
				}
		
				$template->set_filenames(array('body' => 'style/acp_newscat.tpl'));
				$template->assign_block_vars('newscat_edit', array());
				
				$folder = $root_path . $settings['path_news_category'];
				$files = scandir($folder);
				
				$newscat_list = '';
				$newscat_list .= '<select name="news_category_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
				$newscat_list .= '<option value="">----------</option>';
				
				foreach ($files as $file)
				{
					if ($file != '.' && $file != '..' && $file != 'index.htm')
					{
						$selected = ( $file == $newscat['news_category_image'] ) ? ' selected="selected"' : '';
						$newscat_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$newscat_list .= '</select>';
		
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_NEWSCAT_URL . '" value="' . $news_category_id . '" />';
		
				$template->assign_vars(array(
					'L_NEWSCAT_HEAD'		=> $lang['newscat_head'],
					'L_NEWSCAT_NEW_EDIT'	=> ($mode == 'add') ? $lang['newscat_add'] : $lang['newscat_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_NEWSCAT_TITLE'		=> $lang['newscat_title'],
					'L_NEWSCAT_IMAGE'		=> $lang['newscat_image'],
									
					'L_SUBMIT'				=> $lang['common_submit'],
					'L_RESET'				=> $lang['common_reset'],
					'L_YES'					=> $lang['common_yes'],
					'L_NO'					=> $lang['common_no'],
					
					'NEWSCAT_TITLE'			=> $newscat['news_category_title'],
					'NEWSCAT_IMAGE'			=> ($mode == 'add') ? $root_path . 'images/spacer.gif' : $root_path . $settings['path_news_category'] . '/' . $newscat['news_category_image'],
					'NEWSCAT_PATH'			=> $root_path . $settings['path_news_category'],
					
					'S_NEWSCAT_LIST'		=> $newscat_list,
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_NEWSCAT_ACTION'		=> append_sid('admin_newscat.php'),
				));
			
				$template->pparse('body');
				
			break;
			
			case 'newscat_create':
		
				$newscat_title	= ( isset($HTTP_POST_VARS['news_category_title']) )	? trim($HTTP_POST_VARS['news_category_title']) : '';
				$newscat_image	= ( isset($HTTP_POST_VARS['news_category_image']) )	? trim($HTTP_POST_VARS['news_category_image']) : '';
				
				if ( $newscat_title == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
				
				$sql = 'SELECT MAX(news_category_order) AS max_order FROM ' . NEWS_CATEGORY;
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
		
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
		
				$sql = 'INSERT INTO ' . NEWS_CATEGORY . " (news_category_title, news_category_image, news_category_order)
					VALUES ('" . str_replace("\'", "''", $newscat_title) . "', '" . str_replace("\'", "''", $newscat_image) . "', $next_order)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_newscat_add', $newscat_title);
		
				$message = $lang['create_newscat'] . sprintf($lang['click_return_newscat'], '<a href="' . append_sid('admin_newscat.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
		
				break;
			
			case 'newscat_update':
				
				$newscat_title		= ( isset($HTTP_POST_VARS['news_category_title']) )	? trim($HTTP_POST_VARS['news_category_title']) : '';
				$newscat_image		= ( isset($HTTP_POST_VARS['news_category_image']) )	? trim($HTTP_POST_VARS['news_category_image']) : '';
				
				if ( $newscat_title == '' )
				{
					message_die(GENERAL_ERROR, $lang['empty_name'] . $lang['back']);
				}
		
				$sql = "UPDATE " . NEWS_CATEGORY . " SET
							news_category_title	= '" . str_replace("\'", "''", $newscat_title) . "',
							news_category_image	= '" . str_replace("\'", "''", $newscat_image) . "'
						WHERE news_category_id		= " . intval($HTTP_POST_VARS[POST_NEWSCAT_URL]);
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_newscat_edit');
				
				$message = $lang['update_news'] . sprintf($lang['click_return_newscat'], '<a href="' . append_sid('admin_newscat.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
		
				break;
		
			case 'newscat_order':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NEWS_CATEGORY . " SET news_category_order = news_category_order + $move WHERE news_category_id = $news_category_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
		
				renumber_order('newscat');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_newscat_order');
				
				$show_index = TRUE;
		
				break;
			
			case 'newscat_delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $news_category_id && $confirm )
				{	
					$newscat = get_data('newscat', $news_category_id);
					
					$sql = 'UPDATE ' . NEWS . ' SET news_category = 0 WHERE news_category = ' . $news_category_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Error getting information', '', __LINE__, __FILE__, $sql);
					}
				
					$sql = 'DELETE FROM ' . NEWS_CATEGORY . ' WHERE news_category_id = ' . $news_category_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_category_delete', $newscat['news_category_title']);
					
					$message = $lang['delete_newscat'] . sprintf($lang['click_return_newscat'], '<a href="' . append_sid('admin_newscat.php') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $news_category_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="newscat_delete" />';
					$hidden_fields .= '<input type="hidden" name="' . POST_NEWSCAT_URL . '" value="' . $news_category_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_newscat'],
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
						'S_CONFIRM_ACTION'	=> append_sid('admin_newscat.php'),
						'S_HIDDEN_FIELDS'	=> $hidden_fields,
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['msg_must_select_newscat']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
					
				message_die(GENERAL_ERROR, $lang['no_mode']);
					
				break;
			}
		
			if ( $show_index != TRUE )
			{
				include('./page_footer_admin.php');
				exit;
			}
		}
			
	$template->set_filenames(array('body' => 'style/acp_newscat.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_NEWSCAT_TITLE'	=> $lang['newscat_head'],
		'L_NEWSCAT_EXPLAIN'	=> $lang['newscat_explain'],
		'L_NEWSCAT_NAME'	=> $lang['team_name'],
		'L_NEWSCAT_ADD'		=> $lang['newscat_add'],
		'L_SETTINGS'		=> $lang['settings'],
		'L_EDIT'			=> $lang['edit'],
		'L_DELETE'			=> $lang['delete'],
		
		'NEWSCAT_PATH'		=> $root_path . $settings['path_news_category'],
		
		'S_TEAM_ACTION'		=> append_sid('admin_newscat.php'),
	));
	
	$sql = 'SELECT * FROM ' . NEWS_CATEGORY . ' ORDER BY news_category_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$newscat_data = $db->sql_fetchrowset($result);
	
	$sql = 'SELECT MAX(news_category_order) AS max FROM ' . NEWS_CATEGORY;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$max_order = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if ( !$newscat_data )
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($newscat_data)); $i++ )
		{
			$newscat_id	= $newscat_data[$i]['news_category_id'];
			$icon_up	= ( $newscat_data[$i]['news_category_order'] != '10' ) ? '<img src="' . $images['icon_acp_arrow_u'] . '" alt="" />' : '';
			$icon_down	= ( $newscat_data[$i]['news_category_order'] != $max_order['max'] ) ? '<img src="' . $images['icon_acp_arrow_d'] . '" alt="" />' : '';

			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$template->assign_block_vars('display.newscat_row', array(
				'CLASS' 		=> $class,
				'NEWSCAT_NAME'	=> $newscat_data[$i]['news_category_title'],
				'NEWSCAT_IMAGE'	=> $newscat_data[$i]['news_category_image'],
				
				'MOVE_UP'		=> ( $newscat_data[$i]['news_category_order'] != '10' )				? '<a href="' . append_sid('admin_newscat.php?mode=newscat_order&amp;move=-15&amp;' . POST_NEWSCAT_URL . '=' . $newscat_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $newscat_data[$i]['news_category_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_newscat.php?mode=newscat_order&amp;move=15&amp;' . POST_NEWSCAT_URL . '=' . $newscat_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_DELETE'		=> append_sid('admin_newscat.php?mode=newscat_delete&amp;' . POST_NEWSCAT_URL . '=' . $newscat_id),
				'U_EDIT'		=> append_sid('admin_newscat.php?mode=newscat_edit&amp;' . POST_NEWSCAT_URL . '=' . $newscat_id),
			));
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>