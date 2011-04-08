<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');
include($root_path . 'includes/acp/acp_functions.php');

$userdata = session_pagestart($user_ip, PAGE_GALLERY);
init_userprefs($userdata);

$start		= ( request('start', 0) ) ? request('start', 0) : 0;
$start		= ( $start < 0 ) ? 0 : $start;
$data_id	= request(POST_GALLERY_URL, 0);
$mode		= request('mode', 1);
$path_dir	= $root_path . $settings['path_gallery'] . '/';

if ( $data_id )
{
	$page_title = $lang['gallery'];
	
	$template->set_filenames(array('body' => 'body_gallery.tpl'));
	$template->assign_block_vars('details', array());
	
	$sql = 'SELECT * FROM ' . GALLERY . ' WHERE gallery_id = ' . $data_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$gallery = $db->sql_fetchrow($result);
#	$gallery = _cached($sql, 'gallery_' . $data_id, 0);

#	debug($gallery);

	if ( !$gallery )
	{
		message(GENERAL_MESSAGE, 'Diese Galerie ID ist nicht verhanden!');
	}
	
	if ( $userdata['user_level'] < $gallery['auth_view'] )
	{
		if ( !$userdata['session_logged_in'] )
		{
			redirect(append_sid('login.php?redirect=gallery.php&' . POST_GALLERY_URL . '=' . $data_id, true));
		}
		else
		{
			message(GENERAL_ERROR, 'no rights', '');
		}
	}
	
	$sql = 'SELECT * FROM ' . GALLERY_PIC . ' WHERE gallery_id = ' . $data_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$galleries_pics = $db->sql_fetchrowset($result);
#	$galleries_pics = _cached($sql, 'galleries_pics_' . $data_id, 1);

	$s_auth_can =	( ( $userdata['user_level'] >= $gallery['auth_edit'] || $userdata['user_level'] == ADMIN )		? $lang['gallery_rule_can_edit'] : $lang['gallery_rule_cannot_edit'] ) . '<br />';
	$s_auth_can .=	( ( $userdata['user_level'] >= $gallery['auth_delete'] || $userdata['user_level'] == ADMIN )	? $lang['gallery_rule_can_delete'] : $lang['gallery_rule_cannot_delete'] ) . '<br />';
	$s_auth_can .=	( ( $userdata['user_level'] >= $gallery['auth_rate'] || $userdata['user_level'] == ADMIN )		? $lang['gallery_rule_can_rate'] : $lang['gallery_rule_cannot_rate'] ) . '<br />';
	$s_auth_can .=	( ( $userdata['user_level'] >= $gallery['auth_upload'] || $userdata['user_level'] == ADMIN )	? $lang['gallery_rule_can_upload'] : $lang['gallery_rule_cannot_upload'] ) . '<br />';
	
#	debug($s_auth_can);
	
	if ( $galleries_pics )
	{
		for ( $i = $start; $i < min($gallery['per_cols'] + $start, count($galleries_pics)); $i += $gallery['per_rows'] )
		{
			if ( count($galleries_pics) > 0 )
			{
				$template->assign_block_vars('details.row_pics', array());
			}
			
			for ( $j = $i; $j < ( $i + $gallery['per_rows'] ); $j++ )
			{
				if ( $j >= count($galleries_pics) )
				{
					break;
				}
				
				$prev	= $path_dir . $gallery['gallery_path'] . '/' . $galleries_pics[$j]['pic_preview'];
				$image	= $path_dir . $gallery['gallery_path'] . '/' . $galleries_pics[$j]['pic_filename'];
				
				list($width, $height, $type, $attr) = getimagesize($root_path . $settings['path_gallery'] . '/' . $gallery['gallery_path'] . '/' . $galleries_pics[$j]['pic_filename']);
				
				$template->assign_block_vars('details.row_pics.col_pics', array(
					'PIC_ID'	=> $galleries_pics[$j]['pic_id'],
					'TITLE'		=> ( $galleries_pics[$j]['pic_title'] ) ? $galleries_pics[$j]['pic_title'] : 'kein Titel',							
					'PREV'		=> $prev,
					'IMAGE'		=> $image,							
					'WIDTH'		=> $width,
					'HEIGHT'	=> $height,
					'NAME'		=> $galleries_pics[$j]['pic_filename'],
					'SIZE'		=> size_file($galleries_pics[$j]['pic_size']),
				));
			}
		}
		
		$current_page = ( !count($galleries_pics) ) ? 1 : ceil( count($galleries_pics) / $gallery['per_cols'] );

		$template->assign_vars(array(
			'L_GALLERY'		=> sprintf($lang['gallery_overview'], $gallery['gallery_name']),
			
			'PER_ROWS'		=> $gallery['per_rows'],
			
			'PAGE_NUMBER'	=> sprintf($lang['Page_of'], ( floor( $start / $gallery['per_cols'] ) + 1 ), $current_page ),
			'PAGINATION'	=> generate_pagination('gallery.php?mode=_overview&' . POST_GALLERY_URL . '=' . $data_id, count($galleries_pics), $gallery['per_cols'], $start),
			
			'S_AUTH'		=> $s_auth_can,
		));
	}
	else
	{
		$template->assign_block_vars('gallery_overview.no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
}
else
{
#	$page_title = $lang['gallery'];
	
	$template->set_filenames(array('body' => 'body_gallery.tpl'));
	$template->assign_block_vars('select', array());
	
	$sql = 'SELECT gallery_id, gallery_name, gallery_desc, gallery_pics, gallery_path, gallery_create, gallery_update, auth_view, auth_edit, auth_delete, per_rows, per_cols, gallery_order FROM ' . GALLERY . ' ORDER BY gallery_order';
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$galleries = $db->sql_fetchrowset($result);
#	$galleries = _cached($sql, 'list_galleries', 0);

#	debug($galleries);
	
	for ( $i = 0; $i < count($galleries); $i++ )
	{
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
		$template->assign_block_vars('select.row_galleries', array(
			'CLASS'				=> $row_class,													   

			'GALLERY_ID'		=> $galleries[$i]['gallery_id'],
			'GALLERY_NAME'		=> $galleries[$i]['gallery_name'],
			'GALLERY_DESC'		=> $galleries[$i]['gallery_desc'],
			'GALLERY_PICS'		=> sprintf($lang['sprintf_pic'], $galleries[$i]['gallery_pics']),
			'GALLERY_CREATE'	=> create_date($config['default_dateformat'], $galleries[$i]['gallery_pics'], $userdata['user_timezone']),
			'GALLERY_UPDATE'	=> create_date($config['default_dateformat'], $galleries[$i]['gallery_pics'], $userdata['user_timezone']),
			
			'U_GALLERY'			=> append_sid('gallery.php?' . POST_GALLERY_URL . '=' . $galleries[$i]['gallery_id']),
#			'TEAM_FIGHTUS'	=> ( $teams[$j]['team_fight'] )	? '<a href="' . append_sid('contact.php?mode=fightus&amp;' . POST_TEAMS_URL . '=' . $team_id) . '">' . $lang['match_fightus'] . '</a>'  : '',
#			'TO_TEAM'		=> append_sid('gallery.php?mode=view&amp;' . POST_GALLERY_URL . '=' . $teams[$j]['team_id']),
		));
	}
}	

include($root_path . 'includes/page_header.php');

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>