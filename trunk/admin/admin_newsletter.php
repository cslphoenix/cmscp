<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_newsletter'] )
	{
		$module['hm_system']['sm_newsletter'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);

	$root_path = './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	
	if ( !$userauth['auth_newsletter'] && $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid('admin_newsletter.php', true)) : false;
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_NEWSLETTER]) || isset($HTTP_GET_VARS[POST_NEWSLETTER]) )
	{
		$newsletter_id = ( isset($HTTP_POST_VARS[POST_NEWSLETTER]) ) ? intval($HTTP_POST_VARS[POST_NEWSLETTER]) : intval($HTTP_GET_VARS[POST_NEWSLETTER]);
	}
	else
	{
		$newsletter_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
	}
	else
	{
		$mode = '';
	}
	
	$s_index = '';
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'add':
			case 'edit':
				
				if ( $mode == 'edit' )
				{
					$newsletter	= get_data('newsletter', $newsletter_id, INT);
					$new_mode	= 'editnewsletter';
				}
				else if ( $mode == 'add' )
				{
					$newsletter  = array (
						'newsletter_mail'	=> ( isset($HTTP_POST_VARS['newsletter_mail']) ) ? trim($HTTP_POST_VARS['newsletter_mail']) : '',
					);

					$new_mode = 'addnewsletter';
				}
				
				$template->set_filenames(array('body' => 'style/acp_newsletter.tpl'));
				$template->assign_block_vars('newsletter_edit', array());
				
				$fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$fields .= '<input type="hidden" name="' . POST_NEWSLETTER . '" value="' . $newsletter_id . '" />';

				$template->assign_vars(array(
					'L_NL_HEAD'			=> $lang['newsletter_head'],
					'L_NL_INPUT'		=> ($mode == 'add') ? $lang['newsletter_add'] : $lang['newsletter_edit'],
					'L_REQUIRED'		=> $lang['required'],
					
					'L_NL_EMAIL'		=> $lang['newsletter_mail'],
					'L_NL_TYPE'			=> $lang['newsletter_type'],
					'L_NL_TYPE_EXPLAIN'	=> $lang['newsletter_type_explain'],
									
					'L_SUBMIT'			=> $lang['Submit'],
					'L_RESET'			=> $lang['Reset'],
					'L_NEW'				=> $lang['newsletter_type_new'],
					'L_ACTIVE'			=> $lang['newsletter_type_active'],
					
					'NL_MAIL'			=> $newsletter['newsletter_mail'],
					
					'S_FIELDS'	=> $fields,
					'S_NL_ACTION'		=> check_sid('admin_newsletter.php'),
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addnewsletter':

				$newsletter_mail = ( isset($HTTP_POST_VARS['newsletter_mail']) ) ? trim($HTTP_POST_VARS['newsletter_mail']) : '';
				
				if ( $newsletter_mail == '' || check_mail_subscribe($newsletter_mail) )
				{
					message(GENERAL_ERROR, $lang['empty_mail'] . $lang['back']);
				}
	
				$sql = 'INSERT INTO ' . NEWSLETTER . " (newsletter_mail, newsletter_status)
					VALUES ('" . str_replace("\'", "''", $newsletter_mail) . "', '1')";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				log_add(LOG_ADMIN, $log, 'acp_newsletter_edit', $newsletter_mail);
	
				$message = $lang['create_newsletter'] . sprintf($lang['click_return_newsletter'], '<a href="' . check_sid('admin_newscat.php'));
				message(GENERAL_MESSAGE, $message);

				break;
			
			
			case 'editnewsletter':
				
				$newsletter_mail = ( isset($HTTP_POST_VARS['newsletter_mail']) ) ? trim($HTTP_POST_VARS['newsletter_mail']) : '';
				
				if ( $newsletter_mail == '' || check_mail_subscribe($newsletter_mail) )
				{
					message(GENERAL_ERROR, $lang['empty_mail'] . $lang['back']);
				}

				$sql = "UPDATE " . NEWSLETTER . " SET newsletter_mail = '" . str_replace("\'", "''", $newsletter_mail) . "' WHERE newsletter_id = $newsletter_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				log_add(LOG_ADMIN, SECTION_NEWSLETTER, 'acp_newsletter_edit');
				
				$message = $lang['update_newsletter'] . sprintf($lang['click_return_newsletter'], '<a href="' . check_sid('admin_newsletter.php'));
				message(GENERAL_MESSAGE, $message);
	
				break;
		
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $newsletter_id && $confirm )
				{	
					$newsletter = get_data('newsletter', $newsletter_id);
					
					$sql = 'DELETE FROM ' . NEWSLETTER . ' WHERE newsletter_id = ' . $newsletter_id;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					log_add(LOG_ADMIN, SECTION_NEWSLETTER, 'acp_newsletter_delete', $newsletter['newsletter_mail']);
					
					$message = $lang['delete_newsletter'] . sprintf($lang['click_return_newsletter'], '<a href="' . check_sid('admin_newsletter.php'));
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( $newsletter_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields = '<input type="hidden" name="mode" value="delete" />';
					$fields .= '<input type="hidden" name="' . POST_NEWSLETTER . '" value="' . $newsletter_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_newsletter'],
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
						'S_ACTION'	=> check_sid('admin_newsletter.php'),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_newsletter']);
				}
				
				$template->pparse('body');
				
				break;
				
			case 'alldelete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $confirm )
				{	
					$sql = 'TRUNCATE TABLE ' . NEWSLETTER;
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				
					log_add(LOG_ADMIN, SECTION_NEWSLETTER, 'acp_category_clear', 'Alle Einträge gelöscht!');
					
					$message = $lang['delete_newsletter'] . sprintf($lang['click_return_newsletter'], '<a href="' . check_sid('admin_newsletter.php'));
					message(GENERAL_MESSAGE, $message);
				
				}
				else if ( !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields = '<input type="hidden" name="mode" value="alldelete" />';
					
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['common_confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_newsletter_all'],
						'L_YES'				=> $lang['common_yes'],
						'L_NO'				=> $lang['common_no'],
						'S_ACTION'	=> check_sid('admin_newsletter.php'),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['msg_must_select_newsletter']);
				}
				
				$template->pparse('body');
				
				break;
			
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
				break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_newsletter.tpl'));
	$template->assign_block_vars('display', array());
			
	$template->assign_vars(array(
		'L_NL_TITLE'		=> $lang['newsletter_head'],
		'L_NL_EXPLAIN'		=> $lang['newsletter_explain'],
		'L_NL_EMAIL'		=> $lang['newsletter_email'],
		'L_NL_ADD'			=> $lang['newsletter_add'],
		
		'L_SETTINGS'		=> $lang['common_settings'],
		'L_EDIT'			=> $lang['common_update'],
		'L_DELETE'			=> $lang['common_delete'],
		'L_ALLDELETE'		=> $lang['alldelete'],
		
		'S_NL_ACTION'		=> check_sid('admin_newsletter.php'),
	));
	
	$sql = 'SELECT * FROM ' . NEWSLETTER . ' ORDER BY newsletter_id';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$newsletter_data = $db->sql_fetchrowset($result);
	
	if ( !$newsletter_data )
	{
		$template->assign_block_vars('display.entry_empty', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, count($newsletter_data)); $i++ )
		{
			$newsletter_id	= $newsletter_data[$i]['newsletter_id'];

			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$template->assign_block_vars('display.newsletter_row', array(
				'CLASS' 		=> $class,
				'NL_MAIL'		=> $newsletter_data[$i]['newsletter_mail'],
				'NL_STATUS'		=> $newsletter_data[$i]['newsletter_status'],
				
				'U_PUBLIC'		=> check_sid('admin_newsletter.php?mode=public&amp;' . POST_NEWSLETTER . '=' . $newsletter_id),
				'U_DELETE'		=> check_sid('admin_newsletter.php?mode=delete&amp;' . POST_NEWSLETTER . '=' . $newsletter_id),
				'U_EDIT'		=> check_sid('admin_newsletter.php?mode=edit&amp;' . POST_NEWSLETTER . '=' . $newsletter_id),
			));
		}
	}
	
	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>