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
	if ( $userauth['auth_contact'] || $userauth['auth_joinus'] || $userauth['auth_fightus'] || $userdata['user_level'] == ADMIN )
	{
		$module['contact']['contact_over'] = $filename;
	}
	
	if ( $userauth['auth_contact'] || $userdata['user_level'] == ADMIN )
	{
		$module['contact']['contact'] = $filename . "?mode=contact";
	}
	
	if ( $userauth['auth_joinus'] || $userdata['user_level'] == ADMIN )
	{
		$module['contact']['contact_joinus'] = $filename . "?mode=joinus";
	}
	
	if ( $userauth['auth_fightus'] || $userdata['user_level'] == ADMIN )
	{
		$module['contact']['contact_fightus'] = $filename . "?mode=fightus";
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
	include($root_path . 'includes/functions_selects.php');
	
	if ( $cancel )
	{
		redirect('admin/' . append_sid('admin_contact.php', true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ( $start < 0 ) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_CONTACT_URL]) || isset($HTTP_GET_VARS[POST_CONTACT_URL]) )
	{
		$contact_id = ( isset($HTTP_POST_VARS[POST_CONTACT_URL]) ) ? intval($HTTP_POST_VARS[POST_CONTACT_URL]) : intval($HTTP_GET_VARS[POST_CONTACT_URL]);
	}
	else
	{
		$contact_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
	}
	else
	{
		$mode = '';
	}
	
	if ( $mode == 'contact' && !$userauth['auth_contact'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $mode == 'joinus' && (!$userauth['auth_joinus'] && $userdata['user_level'] != ADMIN ))
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	if ( $mode == 'fightus' && !$userauth['auth_fightus'] && $userdata['user_level'] != ADMIN )
	{
		message_die(GENERAL_ERROR, $lang['auth_fail']);
	}
	
	$show_index = '';
	
	if ( !empty($mode) )
	{
		switch ( $mode )
		{
			case 'contact':
			case 'fightus':
			case 'joinus':
			
				$template->set_filenames(array('body' => 'style/acp_contact.tpl'));
				$template->assign_block_vars('categorie', array());
				
				if ( $userauth['auth_contact'] || $userdata['user_level'] == ADMIN )
				{
					$template->assign_block_vars('categorie.contact', array());
				}
				
				if ( $userauth['auth_joinus'] || $userdata['user_level'] == ADMIN )
				{
					$template->assign_block_vars('categorie.joinus', array());
				}
				
				if ( $userauth['auth_fightus'] || $userdata['user_level'] == ADMIN )
				{
					$template->assign_block_vars('categorie.fightus', array());
				}
				
				$where = '';
				if ( $mode == 'contact')
				{
					$where = ' WHERE contact_type = ' . CONTACT_NORMAL;
					$tab_aktiv1	= ' id="active"><a id="current" ';
					$tab_aktiv2	= '><a ';
					$tab_aktiv3	= '><a ';
				}
				else if ( $mode == 'joinus')
				{
					$where = ' WHERE contact_type = ' . CONTACT_JOINUS;
					$tab_aktiv1	= '><a ';
					$tab_aktiv2	= ' id="active"><a id="current" ';
					$tab_aktiv3	= '><a ';
				}
				else if ( $mode == 'fightus')
				{
					$where = ' WHERE contact_type = ' . CONTACT_FIGHTUS;
					$tab_aktiv1	= '><a ';
					$tab_aktiv2	= '><a ';
					$tab_aktiv3	= ' id="active"><a id="current" ';
				}
				else
				{
					$where = '';
				}
				
				
				$template->assign_vars(array(
					'L_CONTACT_TITLE'			=> $lang['contact_overview'],
					'L_CONTACT_EXPLAIN'			=> $lang['contact'],
					'L_CONTACT_DETAILS'			=> $lang['contact_details'],
					
					'L_CONTACT_HEAD_NORMAL'		=> $lang['contact_head_normal'],
					'L_CONTACT_HEAD_FIGHTUS'	=> $lang['contact_head_fightus'],
					'L_CONTACT_HEAD_JOINUS'		=> $lang['contact_head_joinus'],
			
					'L_CONTACT_SETTINGS'		=> $lang['settings'],
					'L_CONTACT_SETTING'			=> $lang['setting'],
					'L_CONTACT_MEMBER'			=> $lang['contact'],
					
					'L_DELETE'				=> $lang['common_delete'],
					
					'L_TRAINING'			=> $lang['training'],
					
					'TAB_AKTIV1'				=> $tab_aktiv1,
					'TAB_AKTIV2'				=> $tab_aktiv2,
					'TAB_AKTIV3'				=> $tab_aktiv3,
					
					'S_CONTACT_ACTION'		=> append_sid('admin_contact.php'),
					'S_CONTACT_NORMAL'		=> append_sid('admin_contact.php?mode=contact'),
					'S_CONTACT_JOINUS'		=> append_sid('admin_contact.php?mode=joinus'),
					'S_CONTACT_FIGHTUS'		=> append_sid('admin_contact.php?mode=fightus'),
				));
									
				$sql = 'SELECT c.*, t.team_name, g.game_image, g.game_size
						FROM ' . CONTACT . ' c
							LEFT JOIN ' . TEAMS . ' t ON c.contact_team = t.team_id
							LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
							' . $where . '
						ORDER BY c.contact_time DESC';
				$result = $db->sql_query($sql);
				$contact_entry = $db->sql_fetchrowset($result); 
				$db->sql_freeresult($result);
				
				if ( !$contact_entry )
				{
					$template->assign_block_vars('categorie.no_entry', array());
					$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
				}
				else
				{
					for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($contact_entry)); $i++)
					{
						$class = ($i % 2) ? 'row_class1' : 'row_class2';
							
						$game_size	= $contact_entry[$i]['game_size'];
						$game_image	= '<img src="' . $root_path . $settings['path_game'] . '/' . $contact_entry[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
						
						
						$template->assign_block_vars('categorie.contact_row', array(
							'CLASS' 			=> $class,
							'CONTACT_ID' 		=> $contact_entry[$i]['contact_id'],
							'CONTACT_GAME'		=> $game_image,
							'CONTACT_TYPE'		=> ($contact_entry[$i]['contact_type'] != '0') ? ($contact_entry[$i]['contact_type'] == '2') ? $lang['contact_joinus'] : $lang['contact_fightus'] : $lang['contact'],
							'CONTACT_STATUS'	=> ($contact_entry[$i]['contact_status'] != '0') ? ($contact_entry[$i]['contact_status'] == '2') ? $lang['contact_type_edit'] : $lang['contact_type_close'] : $lang['contact_type_open'],
							'CONTACT_FROM'		=> $contact_entry[$i]['contact_from'],
							'CONTACT_MAIL'		=> $contact_entry[$i]['contact_mail'],
							'CONTACT_HOMEPAGE'	=> $contact_entry[$i]['contact_homepage'],
							'CONTACT_DATE'		=> create_date($userdata['user_dateformat'], $contact_entry[$i]['contact_time'], $userdata['user_timezone']),
						));
					}
				}
				
				$current_page = ( !count($contact_entry) ) ? 1 : ceil( count($contact_entry) / $settings['site_entry_per_page'] );
			
				$template->assign_vars(array(
					'PAGINATION' => ( count($contact_entry) ) ? generate_pagination('admin_contact.php?', count($contact_entry), $settings['site_entry_per_page'], $start) : '',
					'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 
			
					'L_GOTO_PAGE' => $lang['Goto_page'])
				);
			
				$template->pparse('body');
				
			break;
			
			default:
				$show_index = TRUE;
				message_die(GENERAL_ERROR, $lang['no_mode']);
				break;
		}
	
		if ( $show_index != TRUE )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_contact.tpl'));
	$template->assign_block_vars('display', array());
	
	$template->assign_vars(array(
		'L_CONTACT_TITLE'			=> $lang['contact_overview'],
		'L_CONTACT_EXPLAIN'			=> $lang['contact'],
		'L_CONTACT_DETAILS'			=> $lang['contact_details'],
		
		'L_CONTACT_HEAD_NORMAL'		=> $lang['contact_head_normal'],
		'L_CONTACT_HEAD_FIGHTUS'	=> $lang['contact_head_fightus'],
		'L_CONTACT_HEAD_JOINUS'		=> $lang['contact_head_joinus'],

		'L_CONTACT_SETTINGS'		=> $lang['settings'],
		'L_CONTACT_SETTING'			=> $lang['setting'],
		'L_CONTACT_MEMBER'			=> $lang['contact'],
		
		'L_DELETE'				=> $lang['common_delete'],
		
		'L_TRAINING'			=> $lang['training'],
		
		'S_CONTACT_ACTION'		=> append_sid('admin_contact.php'),
		'S_CONTACT_NORMAL'		=> append_sid('admin_contact.php?mode=contact'),
		'S_CONTACT_JOINUS'		=> append_sid('admin_contact.php?mode=joinus'),
		'S_CONTACT_FIGHTUS'		=> append_sid('admin_contact.php?mode=fightus'),
	));
	
	$where = '';
	if ( $userauth['auth_contact'] || $userdata['user_level'] == ADMIN )
	{
		$where .= ( isset($where) ) ? ' WHERE contact_type IN ( ' . CONTACT_NORMAL : ' WHERE contact_type IN ( ' . CONTACT_NORMAL;
		$template->assign_block_vars('display.contact', array());
	}
	
	if ( $userauth['auth_joinus'] || $userdata['user_level'] == ADMIN )
	{
		$where .= ( isset($where) ) ? ', ' . CONTACT_JOINUS : ' WHERE contact_type IN ( ' . CONTACT_JOINUS;
		$template->assign_block_vars('display.joinus', array());
	}
	
	if ( $userauth['auth_fightus'] || $userdata['user_level'] == ADMIN )
	{
		$where .= ( isset($where) ) ? ', ' . CONTACT_FIGHTUS : ' WHERE contact_type IN ( ' . CONTACT_FIGHTUS;
		
		$template->assign_block_vars('display.fightus', array());
	}
	$where .= ')';
	
	if ( $userdata['user_level'] == ADMIN )
	{
		$where = '';
	}
	
	$sql = 'SELECT c.*, t.team_name, g.game_image, g.game_size
			FROM ' . CONTACT . ' c
				LEFT JOIN ' . TEAMS . ' t ON c.contact_team = t.team_id
				LEFT JOIN ' . GAMES . ' g ON t.team_game = g.game_id
				' . $where . '
			ORDER BY c.contact_time DESC';
	$result = $db->sql_query($sql);
	$contact_entry = $db->sql_fetchrowset($result); 
	$db->sql_freeresult($result);
	
	if ( !$contact_entry )
	{
		$template->assign_block_vars('display.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['site_entry_per_page'] + $start, count($contact_entry)); $i++)
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
				
			$game_size	= $contact_entry[$i]['game_size'];
			$game_image	= '<img src="' . $root_path . $settings['path_game'] . '/' . $contact_entry[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
			
			
			$template->assign_block_vars('display.contact_row', array(
				'CLASS' 			=> $class,
				'CONTACT_ID' 		=> $contact_entry[$i]['contact_id'],
				'CONTACT_GAME'		=> $game_image,
				'CONTACT_TYPE'		=> ($contact_entry[$i]['contact_type'] != '0') ? ($contact_entry[$i]['contact_type'] == '2') ? $lang['contact_joinus'] : $lang['contact_fightus'] : $lang['contact'],
				'CONTACT_STATUS'	=> ($contact_entry[$i]['contact_status'] != '0') ? ($contact_entry[$i]['contact_status'] == '2') ? $lang['contact_type_edit'] : $lang['contact_type_close'] : $lang['contact_type_open'],
				'CONTACT_FROM'		=> $contact_entry[$i]['contact_from'],
				'CONTACT_MAIL'		=> $contact_entry[$i]['contact_mail'],
				'CONTACT_HOMEPAGE'	=> $contact_entry[$i]['contact_homepage'],
				'CONTACT_DATE'		=> create_date($userdata['user_dateformat'], $contact_entry[$i]['contact_time'], $userdata['user_timezone']),

			));
		}
	}
	
	$current_page = ( !count($contact_entry) ) ? 1 : ceil( count($contact_entry) / $settings['site_entry_per_page'] );

	$template->assign_vars(array(
		'PAGINATION' => ( count($contact_entry) ) ? generate_pagination('admin_contact.php?', count($contact_entry), $settings['site_entry_per_page'], $start) : '',
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['site_entry_per_page'] ) + 1 ), $current_page ), 

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}
?>