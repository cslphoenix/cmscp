<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'acp_contact',
		'CAT'		=> 'system',
		'MODES'		=> array(
			'overview'	=> array('TITLE'		=> 'acp_overview'),
			'contact'	=> array('TITLE'		=> 'acp_contact_contact'),
			'joinus'	=> array('TITLE'		=> 'acp_contact_joinus'),
			'fightus'	=> array('TITLE'		=> 'acp_contact_fightus'),
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
	
	$time	= time();
	$log	= SECTION_CONTACT;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$_top	= sprintf($lang['STF_HEADER'], $lang['contact']);
	
	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
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
				'CONTACT_TYPE'		=> ($row['contact_type'] != '0') ? $row['contact_type'] == '2' ? langs('contact_joinus') : langs('contact_fightus') : langs('contact_contact'),
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
		$template->assign_block_vars('display.none', array());
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
		'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['contact']),
		'L_CONTACT'		=> $lang['contact'],
		'L_FIGHTUS'		=> $lang['fightus'],
		'L_JOINUS'		=> $lang['joinus'],
		'L_EXPLAIN'	=> $lang['EXPLAIN'],
		
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