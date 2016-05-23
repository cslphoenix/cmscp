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
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_forum';
	
	include('./pagestart.php');
	
	add_lang('forums');
#	acl_auth(array('a_training', 'a_training_create', 'a_training_delete', 'a_training_manage'));

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
	
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_forum.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$base = ($settings['smain']['forum_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'delete'))) ? $mode : false;
	
	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
				
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'forum' => array(
						'title1' => 'input_data',
						'forum_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
						'type'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type', 'params' => array('combi', false, 'main')),
						'main'			=> array('validate' => INT,	'explain' => false,	'type' => $base,			'divbox' => true, 'params' => array(false, true, false)),
						'copy'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:copy',		'divbox' => true),
						'forum_desc'	=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40',	'divbox' => true, 'params' => ''),
						'forum_icons'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno',	'divbox' => true),
						'forum_legend'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:legend',	'divbox' => true),
						'forum_status'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:status',	'divbox' => true),
					)
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$keys = ( !isset($_POST['cat_name']) ) ? (( isset($_POST['submit_subforum']) ) ? key($_POST['submit_subforum']) : $main) : 0;
					$name = ( !isset($_POST['cat_name']) ) ? (( isset($_POST['submit_subforum']) ) ? request(array('forum_subforum', $keys), TXT) : request('forum_forum', TXT) ) : request('cat_name', TXT);
					$type = ( !isset($_POST['cat_name']) ) ? (( isset($_POST['submit_subforum']) ) ? 2 : 1 ) : 0;
					
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
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(FORUM, $data, false, 1, true);
					
					debug($data_sql);
				}
				else
				{
					$data_sql = build_request(FORUM, $vars, $error, $mode, false, array('copy'));
					
					if ( $data_sql['type'] == 0 )
					{
						$data_sql['main'] = 0;
					}
					
				#	debug($data_sql);
					
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
							$data_sql['forum_order'] = maxa(FORUM, 'forum_order', "main = " . $data_sql['main']);
							
							$sql = sql(FORUM, $mode, $data_sql);
							$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$sql = sql(FORUM, $mode, $data_sql, 'forum_id', $data);
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
				
				build_output(FORUM, $vars, $data_sql);
								
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
					'L_INPUT'	=> sprintf($lang['stf_' . $mode], $lang['forum'], $data['forum_name']),
				
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
						'M_TITLE'	=> $lang['com_confirm'],
						'M_TEXT'	=> sprintf($lang['notice_confirm_delete'], $lang['confirm'], $data['forum_name']),

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
			
				move(FORUM, $mode, $order, $main, $type, $usub);
				log_add(LOG_ADMIN, $log, $mode);
			
				$index = true;
				
				break;
		}
	
		if ( $index != true )
		{
			acp_footer();
			exit;
		}
	}
	
	if ( $main )
	{
		$template->assign_block_vars('list', array());
				
		$tmp = data(FORUM, false, 'main ASC, forum_order ASC', 1, false);
		
		$cat = $subforum = $forum = array();
		
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
		
	#	$cid = $cat['forum_id'];
		$cid	= $cat['forum_id'];
		$name	= lang($cat['forum_name']);
		
		$template->assign_vars(array(
			'CAT'		=> href('a_txt', $file, array($file), $lang['acp_overview'], $lang['acp_overview']),
			'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $cid), $name, $name),
			'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $cid), 'icon_update', 'common_update'),
			'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $cid), 'icon_cancel', 'com_delete'),
			
			'S_ACTION'	=> check_sid($file),
			'S_FIELDS'	=> $fields,
		));
		
		if ( $forum )
		{
			$fmax = array_pop(end($forum));
			
			foreach ( $forum as $frow )
			{
				$fid	= $frow['forum_id'];
				$forder	= $frow['forum_order'];
				$fname	= isset($lang[$frow['forum_name']]) ? $lang[$frow['forum_name']] : $frow['forum_name'];
				
				$template->assign_block_vars('list.row', array(
					'NAME'          => href('a_txt', $file, array('mode' => 'update', 'id' => $fid), $fname, $fname),
					'UPDATE'        => href('a_img', $file, array('mode' => 'update', 'id' => $fid), 'icon_update', 'common_update'),
					'DELETE'        => href('a_img', $file, array('mode' => 'delete', 'id' => $fid), 'icon_cancel', 'com_delete'),
					
					'MOVE_UP'       => ( $forder != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'order' => $forder), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'     => ( $forder != $fmax )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'order' => $forder), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
	
					'S_NAME'	=> "forum_subforum[$fid]",
					'S_SUBMIT'	=> "submit_subforum[$fid]",
				));
				
				if ( isset($subforum[$fid]) )
				{
					$smax[$fid] = array_pop(end($subforum[$fid]));
					
					foreach ( $subforum[$fid] as $srow )
					{
						$sid	= $srow['forum_id'];
						$sorder	= $srow['forum_order'];
						$sname	= isset($lang[$srow['forum_name']]) ? $lang[$srow['forum_name']] : $srow['forum_name'];
						
						$template->assign_block_vars('list.row.sub', array( 
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $sid), $sname, $sname),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $sid), 'icon_update', 'common_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $sid), 'icon_cancel', 'com_delete'),
							
							'MOVE_UP'	=> ( $sorder != '1' )			? href('a_img', $file, array('mode' => 'move_up',	'main' => $cid, 'usub' => $fid, 'type' => 2, 'order' => $sorder), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $sorder != $smax[$fid] )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $cid, 'usub' => $fid, 'type' => 2, 'order' => $sorder), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
						));
					}
					
				}
				else
				{
					$template->assign_block_vars('list.row.empty_sub', array());
				}
			}
		}
		else
		{
			$template->assign_block_vars('list.empty', array());
		}
		
		$fields .= '<input type="hidden" name="mode" value="create" />';
		$fields .= '<input type="hidden" name="main" value="' . $main . '" />';
	}
	else
	{
		$template->assign_block_vars('display', array());
		
		$tmp = data(FORUM, "WHERE type = 0", 'forum_order ASC', 1, false);

		if ( !$tmp )
		{
			$template->assign_block_vars('display.empty', array());
		}
		else
		{
			$max = count($tmp);
			
			foreach ( $tmp as $row )
			{
				$id		= $row['forum_id'];
				$name	= $row['forum_name'];
				$order	= $row['forum_order'];
				
				$template->assign_block_vars('display.row', array(
					'NAME'		=> href('a_txt', $file, array('main' => $id), $name, $name),
					
					'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
					'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
					
					'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'common_update'),
					'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),
				));
			}
		}
		
		$fields	.= '<input type="hidden" name="mode" value="create" />';
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
		'L_EXPLAIN'	=> $lang['explain'],
		'L_NAME'	=> $lang['type_0'],
		
		'L_CREATE'			=> sprintf($lang['stf_create'], $lang['type_0']),
		'L_CREATE_FORUM'	=> sprintf($lang['stf_create'], $lang['type_1']),
		'L_CREATE_SUBFORUM'	=> sprintf($lang['stf_create'], $lang['type_2']),
		
		'S_ACTION'	=> check_sid($file),
		'S_FIELDS'	=> $fields,
	));
	
#	$template->assign_vars(array(
#		'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
#		'L_EXPLAIN'	=> $lang['explain'],
#		
#		'L_CREATE_CAT'		=> sprintf($lang['stf_create'], $lang['cat']),
#		'L_CREATE_FORUM'	=> sprintf($lang['sprintf_new_creates'], $lang['forum']),
#		'L_CREATE_SUBFORUM'	=> sprintf($lang['sprintf_new_creates'], $lang['subforum']),
#		
#		'L_EMPTY_FORUM'		=> $lang['empty_forum'],
#		'L_EMPTY_SUBFORUM'	=> $lang['empty_subforum'],
#		
#		'S_ACTION'	=> check_sid($file),
#		'S_FIELDS'	=> $fields,
#	));
}

$template->pparse('body');

acp_footer();

?>