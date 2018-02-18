<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_FORUM',
		'CAT'		=> 'SITE',
		'MODES'		=> array(
			'MAIN'	=> array(
				'TITLE'	=> 'ACP_FORUM',
				'AUTH'	=> array('A_FORUM', 'A_FORUM_ASSORT', 'A_FORUM_CREATE', 'A_FORUM_DELETE')),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$current = $log = 'ACP_FORUM';
	
	include('./pagestart.php');
	
	add_lang('forums');
	add_tpls('acp_forum');
	acl_auth(array('A_FORUM', 'A_FORUM_ASSORT', 'A_FORUM_CREATE', 'A_FORUM_DELETE'));
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$file	= basename(__FILE__) . $iadds;
	$time	= time();
	$base	= ($settings['smain']['forum_switch']) ? 'drop:main' : 'radio:main';
	
	$data	= request('id', INT);
	$main	= request('main', TYP);
	$mode	= request('mode', TYP);
	$usub	= request('usub', TYP);
	$type	= request('type', TYP);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$accept	= request('accept', TYP);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	( $cancel )	? redirect('admin/' . $file) : false;
	
	$mode	= (in_array($mode, array('create', 'update', 'move_down', 'move_up', 'delete'))) ? $mode : 'display';
	
	debug($main, '$main');
	debug($mode, '$mode');
	
	$_top	= sprintf($lang['STF_HEADER'], $lang['TITLE']);
	$_tpl	= ($mode === 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
			
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'forum' => array(
					'title'			=> 'INPUT_DATA',
					'forum_name'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'required' => 'input_name'),
					'type'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type', 'params' => array('combi', false, 'main')),
					'main'			=> array('validate' => INT,	'explain' => false,	'type' => $base,			'divbox' => true, 'params' => array(false, true, false), 'required' => array('main', 'type', array(1, 2))),
					'copy'			=> array('validate' => INT,	'explain' => false,	'type' => 'drop:copy',		'divbox' => true),
					'forum_desc'	=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:40',	'divbox' => true, 'params' => ''),
					'forum_icons'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno',	'divbox' => true),
					'forum_legend'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:legend',	'divbox' => true),
					'forum_status'	=> array('validate' => TXT,	'explain' => false,	'type' => 'radio:status',	'divbox' => true),
					'forum_order'	=> 'hidden',
				)
			);
			
			if ( $mode == 'create' && !$submit && $userauth['A_FORUM_CREATE'] )
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
				$data_sql = data(FORUM, $data, false, 1, 'row');
			}
			else
			{
				$data_sql = build_request(FORUM, $vars, $error, $mode, false, array('copy'));
				
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
					
					if ( $mode == 'create' && $userauth['A_FORUM_CREATE'] )
					{
						$data_sql['forum_order'] = _max(FORUM, 'forum_order', "main = " . $data_sql['main']);
						
						$sql = sql(FORUM, $mode, $data_sql);
						$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
					}
					else if ( $userauth['a_forum'] )
					{
						$sql = sql(FORUM, $mode, $data_sql, 'forum_id', $data);
						$msg = sprintf($lang['RETURN_UPDATE'], langs($mode), check_sid($file), $_top, check_sid("$file&mode=$mode&id=$data"));
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
				'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['forum_name']),
				'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
				
				'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
				'S_FIELDS'	=> $fields,
			));
							
			break;
			
		case 'delete':
		
			$data_sql = data(FORUM, $data_id, false, 1, 1);
		
			if ( $data && $confirm && $userauth['A_FORUM_DELETE'] )
			{
				$type = ( $data['main'] ) ? 'sub' : 'forum';
				$t_id = ( $data['main'] ) ? $data['main'] : $data['cat_id'];
				
			#	sql(GAMES, 'delete', false, 'game_id', $data);
				$sql = "DELETE FROM " . FORUM . " WHERE forum_id = $data_id";
				if ( !$db->sql_query($sql) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$message = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
				
				orders_new(FORUM, $type, $t_id);
				
				log_add(LOG_ADMIN, $log, $mode, $data['forum_name']);
				message(GENERAL_MESSAGE, $message);
			}
			else if ( $data && !$accept && $userauth['A_FORUM_DELETE'] )
			{
				$fields .= build_fields(array(
					'mode'	=> $mode,
					'id'	=> $data,
				));
				
				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['forum_name']),
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}
			
			break;
			
		case 'move_up':
		case 'move_down':
		
			if ( $userauth['A_FORUM_ASSORT'] )
			{
				move(FORUM, $mode, $order, $main, $type, $usub);
				log_add(LOG_ADMIN, $log, $mode);
			}
			
		case 'display':
		
			$template->assign_block_vars('display', array());
			
			$fields = isset($main) ? build_fields(array('mode' => 'create', 'main' => $main)) : build_fields(array('mode' => 'create'));
			$sqlout	= data(FORUM, false, 'main ASC, forum_order ASC', 1, 4);
			
			debug($sqlout, '$sqlout');
			
			if ( !$main )
			{
				$option[] = '';
				
				if ( !$sqlout['main'] )
				{
					$template->assign_block_vars('display.none', array());
				}
				else
				{
					$max = count($sqlout['main']);

					foreach ( $sqlout['main'] as $row )
					{
						$id		= $row['forum_id'];
						$name	= $row['forum_name'];
						$order	= $row['forum_order'];

						$template->assign_block_vars('display.row', array(
							'NAME'		=> href('a_txt', $file, array('main' => $id), $name, $name),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'COMMON_DELETE'),

							'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
							'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
						));
					}
				}
			}
			else
			{
				$main_info = sqlout_id($sqlout['main'], $main, 'forum_id');
				$option[] = href('a_txt', $file, false, $lang['COMMON_OVERVIEW'], $lang['COMMON_OVERVIEW']);
				$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $main_info['id']), sprintf($lang['STF_UPDATE'], $lang['main'], $main_info['name']), $main_info['name']);
				
				if ( isset($sqlout['data_id'][$main]) )
				{
					$max_cnt = 0;
					$max_tmp = count($sqlout['data_id'][$main]);
					
					foreach ( $sqlout['data_id'][$main] as $row )
					{
						$main_id	= $row['forum_id'];
						$main_name	= $row['forum_name'];
						$main_order	= $row['forum_order'];

						$template->assign_block_vars('display.row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $main_id), $main_name, $main_name),
							
							'MOVE_UP'	=> ( $main_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'order' => $main_order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
							'MOVE_DOWN'	=> ( $main_order != $max_tmp )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'order' => $main_order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),

							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), 'icon_update', 'COMMON_UPDATE'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $main_id), 'icon_cancel', 'COMMON_DELETE'),
							
							'S_NAME'	=> "subforum_name[$main_id]",
							'S_SUBMIT'	=> "subforum_submit[$main_id]",
						));
						
						$max_cnt++;
					
						if ( $max_tmp != $max_cnt )
						{
							$template->assign_block_vars('display.row.br_empty', array());
						}
						else
						{
							$template->assign_block_vars('display.row.br_empty2', array());
						}
						
						if ( $row['type'] == 1 )
						{
							if ( isset($sqlout['data_id'][$main_id]) )
							{
								$sub_max = count($sqlout['data_id'][$main_id]);
								
								foreach ( $sqlout['data_id'][$main_id] as $subrow )
								{
									$sub_id		= $subrow['forum_id'];
									$sub_name	= $subrow['forum_name'];
									$sub_order	= $subrow['forum_order'];
									
									$template->assign_block_vars('display.row.subrow', array(
										'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $sub_id), $sub_name, $sub_name),
										
										'MOVE_UP'	=> ( $sub_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'usub' => $main_id, 'type' => 2, 'order' => $sub_order), 'icon_arrow_u', 'COMMON_ORDER_U') : img('i_icon', 'icon_arrow_u2', 'COMMON_ORDER_U'),
										'MOVE_DOWN'	=> ( $sub_order != $sub_max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'usub' => $main_id, 'type' => 2, 'order' => $sub_order), 'icon_arrow_d', 'COMMON_ORDER_D') : img('i_icon', 'icon_arrow_d2', 'COMMON_ORDER_D'),
										
										'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $sub_id), 'icon_update', 'COMMON_UPDATE'),
										'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $sub_id), 'icon_cancel', 'COMMON_DELETE'),
									));
								}
							}
						}
					}
				}
				else
				{
					$template->assign_block_vars('display.none', array());
				}
			}
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_EXPLAIN'	=> $lang['EXPLAIN'],
				'L_NAME'	=> $lang['TYPE_0'],
				'MAIN'		=> $main ? $main : 0,
				
				'CREATE'		=> sprintf($lang['STF_CREATE'], (( !$main ) ? $lang['TYPE_0'] : $lang['TYPE_1'])),
				
				'INPUT_NAME'	=> ( !$main ) ? 'cat_name' : 'forum_name',
				'INPUT_SUBMIT'	=> ( !$main ) ? 'submit_cat' : 'submit_forum',
				
				'L_CREATE'			=> sprintf($lang['STF_CREATE'], $lang['TYPE_0']),
				'L_CREATE_FORUM'	=> sprintf($lang['STF_CREATE'], $lang['TYPE_1']),
				'L_CREATE_SUBFORUM'	=> sprintf($lang['STF_CREATE'], $lang['TYPE_2']),
				
				'L_OPTION'	=> implode($lang['COMMON_BULL'], $option),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
		break;
	}
	$template->pparse($_tpl);
	acp_footer();
}
?>