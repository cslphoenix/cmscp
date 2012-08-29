<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_forum',
		'modes'		=> array(
			'main'	=> array('title' => 'acp_forum'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$update = ( isset($_POST['submit']) ) ? true : false;
	
	$current	= 'acp_forum';
	
	include('./pagestart.php');
	
	add_lang('forums');

	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_FORUM;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$accept	= request('accept', TYP);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['a_forum'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_forum.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	/*	
	if ( isset($_POST['add_forum']) || isset($_POST['add_cat']) )
	{
		if ( isset($_POST['add_forum']) )
		{
			$mode = 'create';
			$cat_id = key($_POST['add_forum']);
			$name = request(array('sub_name', $cat_id), TXT);
		}
		else
		{
			$mode = 'create_cat';
			$name = request('cat_name', TXT);
		}
	}
	*/
	
	debug($_POST);
	
	$mode = ( in_array($mode, array('create', 'update', 'list', 'move_down', 'move_up', 'delete')) ) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
				
				$template->assign_block_vars('input', array());
				
			#	$typs = isset($_POST['forum']['type']) ? $_POST['forum']['type'] : 0;
				
				$vars = array(
					'forum' => array(
						'title1' => 'input_data',
						'forum_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'type'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type', 'params' => array('combi', false, 'main')),
						'main'			=> array('validate' => ARY,	'explain' => false,	'type' => 'drop:main',		'trid' => 'main'),
						'copy'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:copy',		'trid' => 'copy'),
						'forum_desc'	=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40',	'trid' => 'forum_desc'),
						'forum_icons'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno',	'trid' => 'forum_icons'),
						'forum_legend'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:legend',	'trid' => 'forum_legend'),
						'forum_status'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:status',	'trid' => 'forum_status'),
					)
				);
				
				$keys = ( !isset($_POST['forum_name']) ) ? (( isset($_POST['submit_subforum']) ) ? key($_POST['submit_subforum']) : $main) : 0;
				$name = ( !isset($_POST['forum_name']) ) ? (( isset($_POST['submit_subforum']) ) ? request(array('forum_subforum', $keys), TXT) : request('forum_forum', TXT) ) : request('forum_name', TXT);
				$type = ( !isset($_POST['forum_name']) ) ? (( isset($_POST['submit_subforum']) ) ? 2 : 1 ) : 0;
				
				if ( $mode == 'create' && !$update )
				{
					$data_sql = array(
						'forum_name'	=> $name,
						'type'			=> $type,
						'main'			=> $keys,
						'copy'			=> '',
						'forum_desc'	=> '',
						'forum_icons'	=> '',
						'forum_legend'	=> 0,
						'forum_status'	=> 0,
						'forum_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$update )
				{
					$data_sql = data(FORMS, $data, false, 1, true);
				}
				else
				{
					$data_sql = build_request(FORMS, $vars, $error, $mode, false, array('copy'));
					
					debug($data_sql);
					
					if ( $data_sql['type'] == 0 )
					{
						$data_sql['main'] = 0;
					}
					
					if ( !$error )
					{
						
						foreach ( $data_sql as $key => $value )
						{
							if ( in_array($key, array('copy')) )
							{
								$rights[$key] = $value;
							}
							else
							{
								$forums[$key] = $value;
							}
						}
						
						if ( $mode == 'create' )
						{
							$forums['forum_order'] = maxa(FORMS, 'forum_order', "main = " . $forums['main']);
							
							$sql = sql(FORMS, $mode, $forums);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(FORMS, $mode, $forums, 'forum_id', $data);
							$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						}
						
						log_add(LOG_ADMIN, $log, $mode, $sql);
						message(GENERAL_MESSAGE, $msg);
					}
					else
					{
						error('ERROR_BOX', $error);
					}
				}
				
				build_output(FORMS, $vars, $data_sql);
								
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang["sprintf_$mode"], $lang['forum'], $data['forum_name']),
				
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));
				
				$template->pparse('body');
				
				break;
				
			case 'delete':
			
				$data_sql = data(FORUM, $data_id, false, 1, 1);
			
				if ( $data && $confirm )
				{
					$type = ( $data['main'] ) ? 'sub' : 'forum';
					$t_id = ( $data['main'] ) ? $data['main'] : $data['cat_id'];
					
				#	sql(GAMES, 'delete', false, 'game_id', $data);
					$sql = "DELETE FROM " . FORUM . " WHERE forum_id = $data_id";
					if ( !$db->sql_query($sql) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['delete'] . sprintf($lang['return'], check_sid($file), $acp_title);
					
					orders_new(FORUM, $type, $t_id);
					
					log_add(LOG_ADMIN, $log, $mode, $data['forum_name']);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_id && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"id\" value=\"$data\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['forum_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['msg_select_must'], $lang['title']));
				}
				
				$template->pparse('body');
				
				break;
			
			case 'move_up':
			case 'move_down':
			
				moveset(FORMS, $mode, $order, $main, $type, $usub);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	if ( $main )
	{
		$template->assign_block_vars('list', array());
				
		$tmp = data(FORMS, false, 'main ASC, forum_order ASC', 1, false);
		
		if ( $tmp )
		{
			foreach ( $tmp as $rows )
			{
				if ( $rows['forum_id'] == $main )
				{
					$cat = $rows;
				}
				else if ( $rows['main'] == $main )
				{
					$forum[$rows['forum_id']] = $rows;
				}
			}
			
			if ( $forum )
			{
				$keys_labels = array_keys($forum);
				
				foreach ( $tmp as $rows )
				{
					if ( in_array($rows['main'], $keys_labels) )
					{
						$subforum[$rows['main']][] = $rows;
					}
					
				}
			}
		}
		else
		{
			$cat = $subforum = $forum = array();
		}
		
	#	debug($cat, 'cat');
	#	debug($subforum, 'subforum');
	#	debug($forum, 'forum');
		
		$cid = $cat['forum_id'];
		
		$template->assign_vars(array(
			'CAT'	=> href('a_txt', $file, array('action' => 'acp'), strtoupper($action), strtoupper($action)),
			'NAME'	=> isset($lang[$cat['forum_name']]) ? $lang[$cat['forum_name']] : $cat['forum_name'],
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
		
		if ( $forum )
		{
			$lmax = array_pop(end($forum));
			
			foreach ( $forum as $lrow )
			{
				$lid	= $lrow['forum_id'];
				$lname	= isset($lang[$lrow['forum_name']]) ? $lang[$lrow['forum_name']] : $lrow['forum_name'];
				$lorder	= $lrow['forum_order'];
				
				$template->assign_block_vars('list.row', array(
					'NAME'          => href('a_txt', $file, array('mode' => 'update', 'id' => $lid), $lname, $lname),
					
					'MOVE_UP'       => ( $lorder != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'order' => $lorder), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'     => ( $lorder != $lmax )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'order' => $lorder), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
	
					'UPDATE'        => href('a_img', $file, array('mode' => 'update', 'id' => $lid), 'icon_update', 'common_update'),
					'DELETE'        => href('a_img', $file, array('mode' => 'delete', 'id' => $lid), 'icon_cancel', 'common_delete'),
					
					'S_NAME'	=> "forum_subforum[$lid]",
					'S_SUBMIT'	=> "submit_subforum[$lid]",
				));
				
				if ( isset($subforum[$lid]) )
				{
				#	debug($mod[$lid]);
					$mmax[$lid] = array_pop(end($subforum[$lid]));
					
					foreach ( $subforum[$lid] as $mrow )
					{
						$mid	= $mrow['forum_id'];
						$msub	= $mrow['main'];
						$mname	= isset($lang[$mrow['forum_name']]) ? $lang[$mrow['forum_name']] : $mrow['forum_name'];
						$morder	= $mrow['forum_order'];
						
						$template->assign_block_vars('list.row.mod', array( 
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $mid), $mname, $mname),
							
							'MOVE_UP'	=> ( $morder != '1' )			? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'usub' => $lid, 'type' => 2, 'order' => $morder), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $morder != $mmax[$lid] )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'usub' => $lid, 'type' => 2, 'order' => $morder), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
				
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $mid), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $mid), 'icon_cancel', 'common_delete'),
						));
					}
				}
			}
		}
		else
		{
			$template->assign_block_vars('list.empty', array());
		}
		
		$fields .= '<input type="hidden" name="mode" value="create" />';
		$fields .= '<input type="hidden" name="main" value="' . $main . '" />';
		
		$template->assign_vars(array(
			'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['title']),
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
	}
	else
	{
		$template->assign_block_vars('display', array());
		
		$tmp = data(FORMS, false, 'main ASC, forum_order ASC', 1, false);
		
		if ( $tmp )
		{
			foreach ( $tmp as $row )
			{
				if ( !$row['type'] )
				{
					$db_cat[$row['forum_id']] = $row;
				}
				else if ( $row['type'] == 1 )
				{
					$db_frs[$row['main']][$row['forum_id']] = $row;
				}
				else
				{
					$db_sub[$row['main']][$row['forum_id']] = $row;
				}
			}
		}
		else
		{
			$db_cat = $db_frs = $db_sub = array();
		}
		
	#	debug($db_cat);
	#	debug($db_frs);
	#	debug($db_sub);
	
		if ( $db_cat )
		{
			$cmax = array_pop(end($db_cat));
			
			foreach ( $db_cat as $ckey => $crow )
			{
				$cid	= $crow['forum_id'];
				$csub	= $crow['main'];
				$cname	= $crow['forum_name'];
				$corder	= $crow['forum_order'];
				
				$template->assign_block_vars('display.cat', array(
					'NAME'		=> href('a_txt', $file, array('main' => $cid), $cname, $cname),
					
					'MOVE_UP'	=> ( $corder != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $corder), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $corder != $cmax )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $corder), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
					
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'common_delete'),
				));
			}
		}
		
		$fields	= '<input type="hidden" name="mode" value="create" />';
	}
	
	$template->assign_vars(array(
		'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['title']),
		'L_CREATE_FORUM'	=> sprintf($lang['sprintf_new_creates'], $lang['forum']),
		'L_CREATE_CAT'		=> sprintf($lang['sprintf_create'], $lang['forum_c']),
		
		'L_AUTH'			=> sprintf($lang['sprintf_auth'], $lang['forum']),
		
		'S_CREATE_CAT'		=> check_sid("$file?mode=_create_cat"),
		'S_CREATE_FORUM'	=> check_sid("$file?mode=create"),
		
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
}

$template->pparse('body');

include('./page_footer_admin.php');

?>