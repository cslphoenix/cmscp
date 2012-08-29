<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_contact',
		'modes'		=> array(
			'main'		=> array('title' => 'acp_overview'),
			'contact'	=> array('title' => 'acp_contact'),
			'joinus'	=> array('title' => 'acp_joinus'),
			'fightus'	=> array('title' => 'acp_fightus'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_overview';
	
	include('./pagestart.php');
	
	add_lang('contact');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GAMES;
	$url	= POST_CONTACT;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', INT);
	
	$dir_path	= $root_path . $settings['path_games'];
	$acp_title	= sprintf($lang['sprintf_head'], $lang['contact']);
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_contact.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	switch ($mode)
	{
		case 'contact':
			
			$current = 'sm_contact';
			
			if ( $userdata['user_level'] != ADMIN && !$userauth['auth_contact'] )
			{
				log_add(LOG_ADMIN, $log, 'auth_fail', $current);
				message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
			}
			
			break;
			
		case 'joinus':
		
			$current = 'sm_joinus';
			
			if ( $userdata['user_level'] != ADMIN && !$userauth['auth_joinus'] )
			{
				log_add(LOG_ADMIN, $log, 'auth_fail', $current);
				message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
			}
			
			break;
			
		case 'fightus':
		
			$current = 'sm_fightus';
			
			if ( $userdata['user_level'] != ADMIN && !$userauth['auth_fightus'] )
			{
				log_add(LOG_ADMIN, $log, 'auth_fail', $current);
				message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
			}
			
			break;
			
		default: 
		
			if ( $userdata['user_level'] != ADMIN && !$userauth['auth_contact'] && !$userauth['auth_joinus'] && !$userauth['auth_fightus'] )
			{
				log_add(LOG_ADMIN, $log, 'auth_fail', $current);
				message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
			}
		
			break;
	}
	
	$template->assign_block_vars('display', array());
	
	$where = '';
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_contact'] )
	{
		$where .= ' WHERE contact_type IN (' . CONTACT_NORMAL;
		$template->assign_block_vars('display.contact', array());
	}
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_fightus'] )
	{
		$where .= ( $where ) ? ', ' . CONTACT_FIGHTUS : ' WHERE contact_type IN (' . CONTACT_FIGHTUS;
		$template->assign_block_vars('display.fightus', array());
	}
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_joinus'] )
	{
		$where .= ( $where ) ? ', ' . CONTACT_JOINUS : ' WHERE contact_type IN (' . CONTACT_JOINUS;
		$template->assign_block_vars('display.joinus', array());
	}
	
	$where .= ')';
	
	if ( $mode == 'contact' && ($userdata['user_level'] == ADMIN || $userauth['auth_contact']) )
	{
		unset($where);
		$where = ' WHERE contact_type = ' . CONTACT_NORMAL;
		
		$tab_aktiv0	= '><a ';
		$tab_aktiv1	= ' id="active"><a id="current" ';
		$tab_aktiv2	= '><a ';
		$tab_aktiv3	= '><a ';
	}
	
	if ( $mode == 'fightus' && ($userdata['user_level'] == ADMIN || $userauth['auth_fightus']) )
	{
		unset($where);
		$where = ' WHERE contact_type = ' . CONTACT_FIGHTUS;
		
		$tab_aktiv0	= '><a ';
		$tab_aktiv1	= '><a ';
		$tab_aktiv2	= '><a ';
		$tab_aktiv3	= ' id="active"><a id="current" ';
	}
	
	if ( $mode == 'joinus' && ($userdata['user_level'] == ADMIN || $userauth['auth_joinus']) )
	{
		unset($where);
		$where = ' WHERE contact_type = ' . CONTACT_JOINUS;
		
		$tab_aktiv0	= '><a ';
		$tab_aktiv1	= '><a ';
		$tab_aktiv2	= ' id="active"><a id="current" ';
		$tab_aktiv3	= '><a ';
	}
	
	if ( !$mode )
	{
		$tab_aktiv0	= ' id="active"><a id="current" ';
		$tab_aktiv1	= '><a ';
		$tab_aktiv2	= '><a ';
		$tab_aktiv3	= '><a ';
	}
	
	$sql = "SELECT c.*, t.team_name, g.game_image
			FROM " . CONTACT . " c
				LEFT JOIN " . TEAMS . " t ON c.contact_team = t.team_id
				LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
				$where
			ORDER BY c.contact_time DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$data = $db->sql_fetchrowset($result); 
	
	if ( !$data )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, count($data)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
			$game_size	= $data[$i]['game_size'];
			$game_image	= '<img src="' . $root_path . $settings['path_games'] . '/' . $data[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
				
			$template->assign_block_vars('display.contact_row', array(
				'CLASS' 			=> $class,
				'CONTACT_ID' 		=> $data[$i]['contact_id'],
				'CONTACT_GAME'		=> $game_image,
			#	'CONTACT_TYPE'		=> ($data[$i]['contact_type'] != '0') ? ($data[$i]['contact_type'] == '2') ? $lang['contact_joinus'] : $lang['contact_fightus'] : $lang['contact'],
			#	'CONTACT_STATUS'	=> ($data[$i]['contact_status'] != '0') ? ($data[$i]['contact_status'] == '2') ? $lang['contact_type_edit'] : $lang['contact_type_close'] : $lang['contact_type_open'],
				'CONTACT_FROM'		=> $data[$i]['contact_from'],
				'CONTACT_MAIL'		=> $data[$i]['contact_mail'],
				'CONTACT_HOMEPAGE'	=> $data[$i]['contact_homepage'],
				'CONTACT_DATE'		=> create_date($userdata['user_dateformat'], $data[$i]['contact_time'], $userdata['user_timezone']),

			));
		}
	}
	
	$current_page = ( !count($data) ) ? 1 : ceil( count($data) / $settings['per_page_entry']['acp'] );
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['contact']),
		'L_CONTACT'		=> $lang['contact'],
		'L_FIGHTUS'		=> $lang['fightus'],
		'L_JOINUS'		=> $lang['joinus'],
		'L_EXPLAIN'		=> $lang['explain'],
		
		'TAB_AKTIV0'	=> $tab_aktiv0,
		'TAB_AKTIV1'	=> $tab_aktiv1,
		'TAB_AKTIV2'	=> $tab_aktiv2,
		'TAB_AKTIV3'	=> $tab_aktiv3,
		
		'PAGINATION' => ( count($data) ) ? generate_pagination('admin_contact.php?', count($data), $settings['per_page_entry']['acp'], $start) : '',
		'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ), 
		
		'S_NORMAL'	=> check_sid("$file?mode=contact"),
		'S_JOINUS'	=> check_sid("$file?mode=joinus"),
		'S_FIGHTUS'	=> check_sid("$file?mode=fightus"),
		'S_ACTION'	=> check_sid($file),
	));

	$template->pparse('body');
			
	include('./page_footer_admin.php');
}

?>