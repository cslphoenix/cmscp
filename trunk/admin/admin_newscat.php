<?php

/*
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
 *	Content-Management-System by Phoenix
 *
 *	@autor:	Sebastian Frickel © 2009, 2010, 2011
 *	@code:	Sebastian Frickel © 2009, 2010, 2011
 *
 *	Newscat - Newskategorien
 *
 */

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_newscat'] )
	{
		$module['_headmenu_news']['_submenu_newscat'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$s_header	= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_newscat';
	
	include('./pagestart.php');
	include($root_path . 'includes/acp/acp_selects.php');
	include($root_path . 'includes/acp/acp_functions.php');
	
	load_lang('newscat');
	
	$start		= ( request('start', 0) ) ? request('start', 0) : 0;
	$start		= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request(POST_NEWSCAT_URL, 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$error		= '';
	$s_index	= '';
	$s_fields	= '';
	$r_file		= basename(__FILE__);
	$path_dir	= $root_path . $settings['path_newscat'] . '/';
	
	$p_url	= POST_NEWSCAT_URL;
	$l_sec	= LOG_SEK_NEWSCAT;
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_newscat'] )
	{
		log_add(LOG_ADMIN, $l_sec, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_sprintf_auth_fail'], $lang[$current]));
	}
	
	( $s_header ) ? redirect('admin/' . append_sid($r_file, true)) : false;
		
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				$template->set_filenames(array('body' => 'style/acp_newscat.tpl'));
				$template->assign_block_vars('_input', array());
				
				if ( $mode == '_create' && !(request('submit', 1)) )
				{
					$max	= get_data_max(GAMES, 'game_order', '');
					$data	= array(
								'newscat_title'	=> ( request('newscat_title', 2) ) ? request('newscat_title', 2) : '',
								'newscat_image'	=> '',
								'newscat_order'	=> $max['max'] + 10,
							);
				}
				else if ( $mode == '_update' && !(request('submit', 1)) )
				{
					$data = data(NEWSCAT, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
								'newscat_title'	=> request('newscat_title', 2),
								'newscat_image'	=> request('newscat_image', 2),
								'newscat_order'	=> ( request('newscat_order_new', 0) ) ? request('newscat_order_new', 0) : request('newscat_order', 0),
							);
				}
				
				$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
				$s_fields .= '<input type="hidden" name="' . $p_url . '" value="' . $data_id . '" />';
				$s_fields .= '<input type="hidden" name="newscat_order" value="' . $data['newscat_order'] . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['newscat']),
					'L_INPUT'		=> sprintf($lang['sprintf' . $mode], $lang['newscat'], $data['newscat_title']),
					
					'L_TITLE'		=> sprintf($lang['sprintf_title'], $lang['newscat']),
					'L_IMAGE'		=> sprintf($lang['sprintf_image'], $lang['newscat']),
					'L_ORDER'		=> $lang['common_order'],
					
					'TITLE'			=> $data['newscat_title'],
					
					'IMAGE'			=> ( $data['newscat_image'] ) ? $path_dir . $data['newscat_image'] : $images['icon_acp_spacer'],
					'IMAGE_PATH'	=> $path_dir,
					'IMAGE_DEFAULT'	=> $images['icon_acp_spacer'],
					
					'S_ORDER'		=> select_order('select', NEWSCAT, 'newscat', $data['newscat_order']),
					'S_IMAGE'		=> select_box_files('newscat_image', 'post', $path_dir, $data['newscat_image']),
										
					'S_ACTION'		=> append_sid($r_file),
					'S_FIELDS'		=> $s_fields,
				));
				
				if ( request('submit', 2) )
				{
					$newscat_title	= request('newscat_title', 2);
					$newscat_image	= request('newscat_image', 2);
					$newscat_order	= ( request('newscat_order_new', 0) ) ? request('newscat_order_new', 0) : request('newscat_order', 0);
					
					$error .= ( !$newscat_title ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_title'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$max	= get_data_max(GAMES, 'game_order', '');
							$next	= $max['max'] + 10;
							
							$sql = "INSERT INTO " . NEWSCAT . " (newscat_title, newscat_image, newscat_order) VALUES ('$newscat_title', '$newscat_image', '$next')";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['create_newscat'] . sprintf($lang['click_return_newscat'], '<a href="' . append_sid($r_file) . '">', '</a>');
						}
						else
						{
							$sql = "UPDATE " . NEWSCAT . " SET
										newscat_title = '$newscat_title',
										newscat_image = '$newscat_image'
									WHERE newscat_id = $data_id";
							if ( !$db->sql_query($sql) )
							{
								message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
							}
							
							$message = $lang['update_news']
								. sprintf($lang['click_return_newscat'], '<a href="' . append_sid($r_file) . '">', '</a>')
								. sprintf($lang['click_return_update'], '<a href="' . append_sid($r_file . '?mode=_update&amp;' . $p_url . '=' . $data_id) . '">', '</a>');
						}
						
						orders(NEWSCAT);
						
						log_add(LOG_ADMIN, $l_sec, $mode, $newscat_title);						
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $l_sec, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
			
				$template->pparse('body');
				
				break;
			
			case '_order':
			
				update(NEWSCAT, 'newscat', $move, $data_id);
				orders(NEWSCAT);
				
				log_add(LOG_ADMIN, $l_sec, $mode);
				
				$s_index = true;
				
				break;
			
			case '_delete':
			
				$data = data(NEWSCAT, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{	
					$sql = "UPDATE " . NEWS . " SET news_category = 0 WHERE news_category = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$sql = "DELETE FROM " . NEWSCAT . " WHERE newscat_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					$message = $lang['delete_newscat'] . sprintf($lang['click_return_newscat'], '<a href="' . append_sid($r_file) . '">', '</a>');
					
					log_add(LOG_ADMIN, $l_sec, $mode, $data['newscat_title']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
					
					$s_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
					$s_fields .= '<input type="hidden" name="' . $p_url . '" value="' . $data_id . '" />';
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['sprintf_delete_confirm'], $lang['delete_confirm_newscat'], $data['newscat_title']),
						
						'S_ACTION'	=> append_sid($r_file),
						'S_FIELDS'	=> $s_fields,
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
		
		if ( $s_index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
			
	$template->set_filenames(array('body' => 'style/acp_newscat.tpl'));
	$template->assign_block_vars('_display', array());
	
	$s_fields .= '<input type="hidden" name="mode" value="_create" />';
			
	$template->assign_vars(array(	 
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['newscat']),
		'L_CREATE'	=> sprintf($lang['sprintf_new_create'], $lang['newscat']),
		'L_TITLE'	=> sprintf($lang['sprintf_title'], $lang['newscat']),
		
		'L_EXPLAIN'	=> $lang['explain'],
	
		'S_CREATE'	=> append_sid($r_file . '?mode=_create'),
		'S_ACTION'	=> append_sid($r_file),
		'S_FIELDS'	=> $s_fields,
	));
	
	$max	= get_data_max(NEWSCAT, 'newscat_order', '');
	$cats	= get_data_array(NEWSCAT, '', 'newscat_order', 'ASC');
	
	if ( $cats )
	{
		for ( $i = $start; $i < min($settings['site_entry_per_page'] + $start, count($cats)); $i++ )
		{
			$newscat_id	= $cats[$i]['newscat_id'];

			$template->assign_block_vars('_display._newscat_row', array(
				'TITLE'		=> $cats[$i]['newscat_title'],
				
				'MOVE_UP'	=> ( $cats[$i]['newscat_order'] != '10' )			? '<a href="' . append_sid($r_file . '?mode=_order&amp;move=-15&amp;' . $p_url . '=' . $newscat_id) . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
				'MOVE_DOWN'	=> ( $cats[$i]['newscat_order'] != $max['max'] )	? '<a href="' . append_sid($r_file . '?mode=_order&amp;move=+15&amp;' . $p_url . '=' . $newscat_id) . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
				
				'U_DELETE'	=> append_sid($r_file . '?mode=_delete&amp;' . $p_url . '=' . $newscat_id),
				'U_UPDATE'	=> append_sid($r_file . '?mode=_update&amp;' . $p_url . '=' . $newscat_id),
			));
		}
	}
	else
	{
		$template->assign_block_vars('_display._no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>