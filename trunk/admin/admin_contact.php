<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_contact',
		'cat'		=> 'system',
		'modes'		=> array(
			'overview'	=> array('title' => 'acp_overview'),
			'contact'	=> array('title' => 'acp_contact_contact'),
			'joinus'	=> array('title' => 'acp_contact_joinus'),
			'fightus'	=> array('title' => 'acp_contact_fightus'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_contact';
	
	include('./pagestart.php');
	
	add_lang('contact');
	acl_auth(array('a_contact', 'a_fightus', 'a_joinus'));
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_CONTACT;
	$time	= time();
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$acp_title	= sprintf($lang['stf_head'], $lang['contact']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_contact.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$template->assign_block_vars('display', array());
	
	$where = '';
	
	if ( @$userauth['a_contact'] && ($action == 'contact' || $action == 'overview') )
	{
		$where .= ' WHERE contact_type IN (' . CONTACT_NORMAL;
		$template->assign_block_vars('display.contact', array());
	}
	
	if ( @$userauth['a_fightus'] && ($action == 'fightus' || $action == 'overview') )
	{
		$where .= ( $where ) ? ', ' . CONTACT_FIGHTUS : ' WHERE contact_type IN (' . CONTACT_FIGHTUS;
		$template->assign_block_vars('display.fightus', array());
	}
	
	if ( @$userauth['a_joinus'] && ($action == 'joinus' || $action == 'overview') )
	{
		$where .= ( $where ) ? ', ' . CONTACT_JOINUS : ' WHERE contact_type IN (' . CONTACT_JOINUS;
		$template->assign_block_vars('display.joinus', array());
	}
	
	$where .= ')';

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
	
	$data = array();
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$data[$row['contact_type']][] = $row;
	}
	
	foreach ( $data as $_data )
	{
		$template->assign_block_vars('display.type', array());
		
		foreach ( $_data as $row )
		{
			$template->assign_block_vars('display.type.row', array(
			#	'CLASS' 			=> $class,
				'CONTACT_ID' 		=> $row['contact_id'],
			#	'CONTACT_GAME'		=> $game_image,
				'CONTACT_TYPE'		=> ($row['contact_type'] != '0') ? $row['contact_type'] == '2' ? lang('contact_joinus') : lang('contact_fightus') : lang('contact_contact'),
			#	'CONTACT_STATUS'	=> ($row['contact_status'] != '0') ? ($row['contact_status'] == '2') ? $lang['contact_type_edit'] : $lang['contact_type_close'] : $lang['contact_type_open'],
				'CONTACT_FROM'		=> $row['contact_from'],
				'CONTACT_MAIL'		=> $row['contact_mail'],
			#	'CONTACT_HOMEPAGE'	=> $row['contact_homepage'],
				'CONTACT_DATE'		=> create_date($userdata['user_dateformat'], $row['contact_time'], $userdata['user_timezone']),

			));
		}
	}
	/*
	if ( !$data )
	{
		$template->assign_block_vars('display.empty', array());
	}
	else
	{
		for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, count($data)); $i++ )
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
			
		#	$game_size	= $data[$i]['game_size'];
		#	$game_image	= '<img src="' . $root_path . $settings['path_games'] . '/' . $data[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
				
			$template->assign_block_vars('display.contact_row', array(
			#	'CLASS' 			=> $class,
				'CONTACT_ID' 		=> $data[$i]['contact_id'],
			#	'CONTACT_GAME'		=> $game_image,
			#	'CONTACT_TYPE'		=> ($data[$i]['contact_type'] != '0') ? ($data[$i]['contact_type'] == '2') ? $lang['contact_joinus'] : $lang['contact_fightus'] : $lang['contact'],
			#	'CONTACT_STATUS'	=> ($data[$i]['contact_status'] != '0') ? ($data[$i]['contact_status'] == '2') ? $lang['contact_type_edit'] : $lang['contact_type_close'] : $lang['contact_type_open'],
				'CONTACT_FROM'		=> $data[$i]['contact_from'],
			#	'CONTACT_MAIL'		=> $data[$i]['contact_mail'],
			#	'CONTACT_HOMEPAGE'	=> $data[$i]['contact_homepage'],
				'CONTACT_DATE'		=> create_date($userdata['user_dateformat'], $data[$i]['contact_time'], $userdata['user_timezone']),

			));
		}
	}
	*/
	$current_page = ( !count($data) ) ? 1 : ceil( count($data) / $settings['per_page_entry']['acp'] );
	
	$template->assign_vars(array(
		'L_HEAD'		=> sprintf($lang['stf_head'], $lang['contact']),
		'L_CONTACT'		=> $lang['contact'],
		'L_FIGHTUS'		=> $lang['fightus'],
		'L_JOINUS'		=> $lang['joinus'],
		'L_EXPLAIN'		=> $lang['explain'],
		
	#	'TAB_AKTIV0'	=> $tab_aktiv0,
	#	'TAB_AKTIV1'	=> $tab_aktiv1,
	#	'TAB_AKTIV2'	=> $tab_aktiv2,
	#	'TAB_AKTIV3'	=> $tab_aktiv3,
		
		'PAGINATION' => ( count($data) ) ? generate_pagination('admin_contact.php?', count($data), $settings['per_page_entry']['acp'], $start) : '',
		'PAGE_NUMBER' => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ), 
		
		'S_NORMAL'	=> check_sid("$file?mode=contact"),
		'S_JOINUS'	=> check_sid("$file?mode=joinus"),
		'S_FIGHTUS'	=> check_sid("$file?mode=fightus"),
		'S_ACTION'	=> check_sid($file),
	));

	$template->pparse('body');
			
	acp_footer();
}

?>