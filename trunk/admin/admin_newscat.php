<?php

/***

	
	admin_newscat.php
	
	Erstellt von Phoenix
	
	
***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($userdata['auth_newscat'] || $userdata['user_level'] == ADMIN)
	{
		$module['main']['newscat'] = $filename;
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
	
	if (!$userdata['auth_newscat'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_newscat.php", true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	
	//	ID Abfrage
	if ( isset($HTTP_POST_VARS[POST_NEWSCAT_URL]) || isset($HTTP_GET_VARS[POST_NEWSCAT_URL]) )
	{
		$news_categorie_id = ( isset($HTTP_POST_VARS[POST_NEWSCAT_URL]) ) ? intval($HTTP_POST_VARS[POST_NEWSCAT_URL]) : intval($HTTP_GET_VARS[POST_NEWSCAT_URL]);
	}
	else
	{
		$news_categorie_id = 0;
	}
	
	//	mode Abfrage
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		$mode = '';
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
					//	Infos der Spiele
					$sql = 'SELECT * FROM ' . NEWS_CATEGORIE_TABLE . ' WHERE news_categorie_id = ' . $news_categorie_id;
					$result = $db->sql_query($sql);
			
					if ( !($newscat  = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['news_not_exist']);
					}
			
					$new_mode = 'editnews';
				}
				else if ( $mode == 'add' )
				{
					//	Start Werte setzen
					$newscat  = array (
						'news_categorie_title'	=> ( isset($HTTP_POST_VARS['news_categorie_title']) ) ? trim($HTTP_POST_VARS['news_categorie_title']) : '',
						'news_categorie_image'	=> '',
					);

					$new_mode = 'addnews';
				}
				//	Template definieren
				$template->set_filenames(array('body' => './../admin/style/newscat_edit_body.tpl'));
				
				//	Rangbilder auslesen, ganz einfach, mit Dropdown erstelluung
				$folder = $root_path . $settings['news_categorie_path'];
				$files = scandir($folder);
				
				$newscat_list = '';
				$newscat_list .= '<select name="news_categorie_image" class="post" onchange="update_image(this.options[selectedIndex].value);">';
				$newscat_list .= '<option value="">----------</option>';
				
				foreach ($files as $file)
				{
					if ($file != '.' && $file != '..' && $file != 'index.htm')
					{
						$selected = ( $file == $newscat['news_categorie_image'] ) ? ' selected="selected"' : '';
						$newscat_list .= '<option value="' . $file . '" ' . $selected . '>' . $file . '&nbsp;</option>';
					}
				}
				$newscat_list .= '</select>';

				//	Unsichtbare Felder für andere Infos
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_NEWSCAT_URL . '" value="' . $news_categorie_id . '" />';

				//	Variablen zur Ausgabe
				$template->assign_vars(array(
					'L_NEWS_HEAD'			=> $lang['news_head'],
					'L_NEWS_NEW_EDIT'		=> ($mode == 'add') ? $lang['news_add'] : $lang['news_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_NEWS_NAME'			=> $lang['news_title'],
					'L_NEWS_KAT'			=> $lang['news_kat'],
									
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'NEWSCAT_TITLE'			=> $newscat['news_categorie_title'],
					'NEWSCAT_IMAGE'			=> ($mode == 'add') ? $root_path . 'images/spacer.gif' : $root_path . $settings['news_categorie_path'] . '/' . $newscat['news_categorie_image'],

					
					'NEWSCAT_PATH'			=> $root_path . $settings['news_categorie_path'],
					
					'NEWSCAT'				=> $newscat_list,
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_TEAM_ACTION'			=> append_sid("admin_newscat.php")
				));
			
				// Template ausgabe
				$template->pparse('body');
				
			break;
			
			case 'addnews':

				$newscat_title		= ( isset($HTTP_POST_VARS['news_categorie_title']) )	? trim($HTTP_POST_VARS['news_categorie_title']) : '';
				$newscat_image		= ( isset($HTTP_POST_VARS['news_categorie_image']) )	? trim($HTTP_POST_VARS['news_categorie_image']) : '';
				
				if( $newscat_title == '' )
				{
					message_die(GENERAL_ERROR, $lang['news_not_exist'], '', __LINE__, __FILE__);
				}
				
				$sql = 'SELECT MAX(news_categorie_order) AS max_order FROM ' . NEWS_CATEGORIE_TABLE;
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
	
				$max_order = $row['max_order'];
				$next_order = $max_order + 10;
	
				// There is no problem having duplicate forum names so we won't check for it.
				$sql = 'INSERT INTO ' . NEWS_CATEGORIE_TABLE . " (news_categorie_title, news_categorie_image, news_categorie_order)
					VALUES ('" . str_replace("\'", "''", $newscat_title) . "', '" . str_replace("\'", "''", $newscat_image) . "', $next_order)";
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL ERROR', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_news_add', $news_title);
	
				$message = $lang['news_create'] . '<br /><br />' . sprintf($lang['click_return_news'], "<a href=\"" . append_sid("admin_newscat.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

				break;
			
			case 'editnews':
			
				$newscat_title		= ( isset($HTTP_POST_VARS['news_categorie_title']) )	? trim($HTTP_POST_VARS['news_categorie_title']) : '';
				$newscat_image		= ( isset($HTTP_POST_VARS['news_categorie_image']) )	? trim($HTTP_POST_VARS['news_categorie_image']) : '';
				
				if( $news_title == '' )
				{
					message_die(GENERAL_MESSAGE, $lang['news_not_exist'], '', __LINE__, __FILE__);
				}

//				$log_data = ($team_info['team_name'] != $HTTP_POST_VARS['team_name']) ? $team_info['team_name'] . '>' . str_replace("\'", "''", $HTTP_POST_VARS['team_name'])  : '';
				$sql = "UPDATE " . NEWS_CATEGORIE_TABLE . " SET
							news_categorie_title	= '" . str_replace("\'", "''", $newscat_title) . "',
							news_categorie_image	= '" . str_replace("\'", "''", $newscat_image) . "',
						WHERE news_categorie_id		= " . intval($HTTP_POST_VARS[POST_NEWSCAT_URL]);
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, 'acp_news_edit');
				
				$message = $lang['news_update'] . '<br /><br />' . sprintf($lang['click_return_news'], "<a href=\"" . append_sid("admin_newscat.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
				break;
		
			case 'order':
				
				$move = intval($HTTP_GET_VARS['move']);
				
				$sql = 'UPDATE ' . NEWS_CATEGORIE_TABLE . " SET news_categorie_order = news_categorie_order + $move WHERE news_categorie_id = $news_categorie_id";
				$result = $db->sql_query($sql);
		
				renumber_order('newscat');
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NAVI, 'acp_navi_order', NAVI_CLAN);
				
				$show_index = TRUE;
	
				break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $news_categorie_id && $confirm )
				{	
					$sql = 'SELECT * FROM ' . NEWS_TABLE . " WHERE news_categorie_id = $news_categorie_id";
					$result = $db->sql_query($sql);
			
					if ( !($team_info = $db->sql_fetchrow($result)) )
					{
						message_die(GENERAL_MESSAGE, $lang['news_not_exist']);
					}
				
					$sql = 'DELETE FROM ' . NEWS_TABLE . " WHERE news_categorie_id = $news_categorie_id";
					$result = $db->sql_query($sql);
				
					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_NEWS, ACP_NEWS_DELETE, $team_info['news_title']);
					
					$message = $lang['team_delete'] . '<br /><br />' . sprintf($lang['click_return_news'], "<a href=\"" . append_sid("admin_newscat.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				
				}
				else if ( $news_categorie_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_NEWSCAT_URL . '" value="' . $news_categorie_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_news'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_newscat.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['must_select_news']);
				}
				
				$template->pparse("body");
				
				break;
			
			default:
				message_die(GENERAL_ERROR, 'kein modul');
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => './../admin/style/newscat_body.tpl'));
			
	$template->assign_vars(array(
		'L_NEWS_TITLE'			=> $lang['news_head'],
		'L_NEWS_EXPLAIN'		=> $lang['news_explain'],
		
		'L_NEWS_ADD'			=> $lang['news_add'],
		
		'L_SETTING'				=> $lang['setting'],
		'L_SETTINGS'			=> $lang['settings'],
		'L_DELETE'				=> $lang['delete'],
		
		'L_MOVE_UP'				=> $lang['Move_up'], 
		'L_MOVE_DOWN'			=> $lang['Move_down'], 
		
		'NEWSCAT_PATH'			=> $root_path . $settings['news_categorie_path'],
		
		'S_TEAM_ACTION'		=> append_sid("admin_newscat.php")
	));
	
	$sql = 'SELECT * FROM ' . NEWS_CATEGORIE_TABLE . ' ORDER BY news_categorie_order';
	$result = $db->sql_query($sql);
	$newscat_data = $db->sql_fetchrowset($result);
	
	$sql = 'SELECT MAX(news_categorie_order) AS max FROM ' . NEWS_CATEGORIE_TABLE;
	$result = $db->sql_query($sql);
	$max_order = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if ( !$newscat_data )
	{
		$template->assign_block_vars('no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['entry_per_page'] + $start, count($newscat_data)); $i++ )
		{
			$icon_up	= ( $newscat_data[$i]['news_categorie_order'] != '10' ) ? '<img src="' . $images['icon_acp_arrow_u'] . '" alt="" />' : '';
			$icon_down	= ( $newscat_data[$i]['news_categorie_order'] != $max_order['max'] ) ? '<img src="' . $images['icon_acp_arrow_d'] . '" alt="" />' : '';

			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$template->assign_block_vars('newscat_row', array(
				'CLASS' 		=> $class,
				'NEWSCAT_NAME'	=> $newscat_data[$i]['news_categorie_title'],
				'NEWSCAT_IMAGE'	=> $newscat_data[$i]['news_categorie_image'],
				
				'ICON_UP'		=> $icon_up,
				'ICON_DOWN'		=> $icon_down,
				
				'U_PUBLIC'		=> append_sid("admin_newscat.php?mode=public&amp;" . POST_NEWSCAT_URL . "=" . $newscat_data[$i]['news_categorie_id']),
				'U_DELETE'		=> append_sid("admin_newscat.php?mode=delete&amp;" . POST_NEWSCAT_URL . "=" . $newscat_data[$i]['news_categorie_id']),
				'U_EDIT'		=> append_sid("admin_newscat.php?mode=edit&amp;" . POST_NEWSCAT_URL . "=" . $newscat_data[$i]['news_categorie_id']),
				
				'U_MOVE_UP'		=> append_sid("admin_newscat.php?mode=order&amp;move=-15&amp;" . POST_NEWSCAT_URL . "=" . $newscat_data[$i]['news_categorie_id']),
				'U_MOVE_DOWN'	=> append_sid("admin_newscat.php?mode=order&amp;move=15&amp;" . POST_NEWSCAT_URL . "=" . $newscat_data[$i]['news_categorie_id']),
			));
		}
	}
	
	$template->pparse("body");
			
	include('./page_footer_admin.php');
}
?>