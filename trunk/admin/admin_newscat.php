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
		$module['_headmenu_news']['_submenu_newscat'] = $filename;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_header	= $cancel;
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	$start		= ( request('start') ) ? request('start') : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	$newscat_id	= request(POST_NEWSCAT_URL);
	$move		= request('move');
	$confirm	= request('confirm');
	$mode		= request('mode');
	$show_index = '';
	
	if ( !$userauth['auth_newscat'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_newscat.php', true));
	}
		
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
				
				if ( $mode == '_update' )
				{
					$newscat	= get_data('newscat', $newscat_id, 0);
					$new_mode	= '_update_save';
				}
				else
				{
					$newscat  = array (
						'news_category_title'	=> ( request('news_category_title') ) ? request('news_category_title', 'text') : '',
						'news_category_image'	=> '',
					);
					$new_mode = '_create_save';
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
					if ($file != '.' && $file != '..' && $file != 'index.htm' && $file != '.svn')
					{
						$selected = ( $file == $newscat['news_category_image'] ) ? ' selected="selected"' : '';
						$newscat_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$newscat_list .= '</select>';
		
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" /><input type="hidden" name="' . POST_NEWSCAT_URL . '" value="' . $newscat_id . '" />';
		
				$template->assign_vars(array(
											 
					'L_NEWSCAT_HEAD'		=> sprintf($lang['sprintf_head'], $lang['newscat']),
					'L_NEWSCAT_NEW_EDIT'	=> ( $mode == '_create' ) ? sprintf($lang['sprintf_add'], $lang['newscat']) : sprintf($lang['sprintf_edit'], $lang['newscat']),
					'L_REQUIRED'			=> $lang['required'],
					
					'L_NEWSCAT_TITLE'		=> sprintf($lang['sprintf_title'], $lang['newscat']),
					'L_NEWSCAT_IMAGE'		=> sprintf($lang['sprintf_image'], $lang['newscat']),
									
					'L_NO'					=> $lang['common_no'],
					'L_YES'					=> $lang['common_yes'],
					'L_RESET'				=> $lang['common_reset'],
					'L_SUBMIT'				=> $lang['common_submit'],
					
					'NEWSCAT_TITLE'			=> $newscat['news_category_title'],
					'NEWSCAT_IMAGE'			=> ( $mode == '_create' ) ? $root_path . 'images/spacer.gif' : $root_path . $settings['path_news_category'] . '/' . $newscat['news_category_image'],
					'NEWSCAT_PATH'			=> $root_path . $settings['path_news_category'],
					
					'S_NEWSCAT_LIST'		=> $newscat_list,
					'S_FIELDS'		=> $s_hidden_fields,
					'S_ACTION'		=> append_sid('admin_newscat.php'),
				));
			
				$template->pparse('body');
				
				break;
			
			case '_create_save':
			
				$newscat_title = request('news_category_title', 'text');
				$newscat_image = request('news_category_image', 'text');
				
				if ( !$newscat_title )
				{
					message(GENERAL_ERROR, $lang['msg_select_title'] . $lang['back']);
				}
				
				$max_row	= get_data_max(NEWS_CATEGORY, 'news_category_order', '');
				$max_order	= $max_row['max'];
				$next_order	= $max_order + 10;
		
				$sql = "INSERT INTO " . NEWS_CATEGORY . " (news_category_title, news_category_image, news_category_order)
							VALUES ('$newscat_title', '$newscat_image', '$next_order')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['create_newscat'] . sprintf($lang['click_return_newscat'], '<a href="' . append_sid('admin_newscat.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSCAT, 'create_newscat');
				message(GENERAL_MESSAGE, $message);
		
				break;
			
			case '_update_save':
				
				$newscat_title = request('news_category_title', 'text');
				$newscat_image = request('news_category_image', 'text');
				
				if ( !$newscat_title )
				{
					message(GENERAL_ERROR, $lang['msg_select_title'] . $lang['back']);
				}
		
				$sql = "UPDATE " . NEWS_CATEGORY . " SET
							news_category_title	= '$newscat_title',
							news_category_image	= '$newscat_image'
						WHERE news_category_id = $newscat_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['update_news'] . sprintf($lang['click_return_newscat'], '<a href="' . append_sid('admin_newscat.php') . '">', '</a>');
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSCAT, 'update_newscat');
				message(GENERAL_MESSAGE, $message);
		
				break;
		
			case '_order':
			
				update(NEWS_CATEGORY, 'news_category', $move, $newscat_id);
				orders('newscat');
				
				log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSCAT, 'acp_newscat_order');
				
				$show_index = TRUE;
				
				break;
			
			case '_delete':
			
				if ( $newscat_id && $confirm )
				{	
				#	$newscat = get_data('newscat', $newscat_id);
					
					$sql = "UPDATE " . NEWS . " SET news_category = 0 WHERE news_category = $newscat_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'Error getting information', '', __LINE__, __FILE__, $sql);
					}
				
					$sql = "DELETE FROM " . NEWS_CATEGORY . " WHERE news_category_id = $newscat_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete_newscat'] . sprintf($lang['click_return_newscat'], '<a href="' . append_sid('admin_newscat.php') . '">', '</a>');
					log_add(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWSCAT, 'delete_newscat');
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( $newscat_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$s_fields = '<input type="hidden" name="mode" value="_delete" /><input type="hidden" name="' . POST_NEWSCAT_URL . '" value="' . $newscat_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_newscat'],
						
						'L_NO'				=> $lang['common_no'],
						'L_YES'				=> $lang['common_yes'],
						
						'S_FIELDS'	=> $s_fields,
						'S_ACTION'	=> append_sid('admin_newscat.php'),
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_newscat']);
				}
				
				$template->pparse('body');
				
				break;
			
			default:
					
				message(GENERAL_ERROR, $lang['no_mode']);
					
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
	
	$s_hidden_fields = '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(	 
		'L_NEWSCAT_HEAD'	=> sprintf($lang['sprintf_head'], $lang['newscat']),
		'L_NEWSCAT_CREATE'	=> sprintf($lang['sprintf_create'], $lang['newscat']),
		'L_NEWSCAT_TITLE'	=> sprintf($lang['sprintf_title'], $lang['newscat']),
		'L_NEWSCAT_EXPLAIN'	=> $lang['newscat_explain'],
				
		'L_UPDATE'			=> $lang['common_update'],
		'L_DELETE'			=> $lang['common_delete'],
		'L_SETTINGS'		=> $lang['common_settings'],
		
		'NEWSCAT_PATH'		=> $root_path . $settings['path_news_category'],
		
		'S_FIELDS'	=> $s_hidden_fields,
		'S_NEWSCAT_CREATE'	=> append_sid('admin_newscat.php?mode=_create'),
		'S_ACTION'	=> append_sid('admin_newscat.php'),
	));
	
	$max_order		= get_data_max(NEWS_CATEGORY, 'news_category_order', '');
	$newscat_data	= get_data_array(NEWS_CATEGORY, '', 'news_category_order', 'ASC');
	
	if ( $newscat_data )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($newscat_data)); $i++ )
		{
			$newscat_id	= $newscat_data[$i]['news_category_id'];

			$template->assign_block_vars('display.newscat_row', array(
				'CLASS' 		=> ( $i % 2 ) ? 'row_class1' : 'row_class2',
				'NEWSCAT_TITLE'	=> $newscat_data[$i]['news_category_title'],
				'NEWSCAT_IMAGE'	=> $newscat_data[$i]['news_category_image'],
				
				'MOVE_UP'		=> ( $newscat_data[$i]['news_category_order'] != '10' )					? '<a href="' . append_sid('admin_newscat.php?mode=_order&amp;move=-15&amp;' . POST_NEWSCAT_URL . '=' . $newscat_id) .'"><img src="' . $images['icon_acp_arrow_u'] . '" alt=""></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="">',
				'MOVE_DOWN'		=> ( $newscat_data[$i]['news_category_order'] != $max_order['max'] )	? '<a href="' . append_sid('admin_newscat.php?mode=_order&amp;move=15&amp;' . POST_NEWSCAT_URL . '=' . $newscat_id) .'"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="">',
				
				'U_DELETE'		=> append_sid('admin_newscat.php?mode=_delete&amp;' . POST_NEWSCAT_URL . '=' . $newscat_id),
				'U_UPDATE'		=> append_sid('admin_newscat.php?mode=_update&amp;' . POST_NEWSCAT_URL . '=' . $newscat_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>