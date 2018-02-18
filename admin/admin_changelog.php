<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'acp_change',
		'CAT'		=> 'addition',
		'MODES'		=> array(
			'MAIN'	=> array('TITLE' => 'acp_change'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_change';
	
	include('./pagestart.php');
	
	add_lang('changelog');
	acl_auth('change');
	
	$error	= '';
	$index	= '';
	$fields = '';
	
	$time	= time();
	$log	= SECTION_CHANGELOG;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$main	= request('main', TYP);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);

	( $cancel ) ? redirect('admin/' . check_sid(basename(__FILE__))) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_changelog.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$mode = (in_array($mode, array('create', 'update', 'delete'))) ? $mode : 'default';
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';

	if ( $mode )
	{
		switch ( $mode )
		{
			case 'create':
			case 'update':
			
				$template->assign_block_vars('input', array());
				
				$vars = array(
					'change' => array(
						'title'	=> 'INPUT_DATA',
							'type'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('combi', false, 'main')),
							'main'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:main', 'divbox' => true, 'params' => array(false, true, false)),
							'change_num'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'divbox' => true, 'required' => array('input_num', 'type', 0)),
							'change_date'	=> array('validate' => TXT,	'explain' => false,	'type' => 'text:25;25', 'divbox' => true, 'required' => array('input_date', 'type', 0), 'params' => 'format'),
							'change_typ'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:typ', 'divbox' => true, 'params' => array(false, true, false)),
							'change_file'	=> array('validate' => TXT,	'explain' => false, 'type' => 'drop:files', 'divbox' => true, 'params' => array($root_path, '.php', 'self', 'navi')),
							'change_text'	=> array('validate' => TXT,	'explain' => false,	'type' => 'textarea:100', 'divbox' => true, 'params' => TINY_NORMAL, 'class' => 'tinymce'),
							'change_bug'	=> array('validate' => INT,	'explain' => false,	'type' => 'drop:tracker', 'divbox' => true),
							'change_user'	=> 'hidden',
							'change_order'	=> 'hidden',
					),
				);
				
				if ( $mode == 'create' && !$submit )
				{
					$data_sql = array(
						'type'			=> 0,
						'main'			=> ($main ? $main : 0),
						'change_num'	=> '',
						'change_date'	=> $time,
						'change_typ'	=> 0,
						'change_file'	=> '',
						'change_text'	=> '',
						'change_bug'	=> '',
						'change_user'	=> $userdata['user_id'],
						'change_order'	=> 0,
					);
				}
				else if ( $mode == 'update' && !$submit )
				{
					$data_sql = data(CHANGELOG, $data, false, 1, 'row');
				}
				else
				{
					$data_sql = build_request(CHANGELOG, $vars, $error, $mode);
					
					if ( !$error )
					{
						if ( $mode == 'create' )
						{
							$sql = sql(CHANGELOG, $mode, $data_sql);
							$msg = sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
						}
						else
						{
							$sql = sql(CHANGELOG, $mode, $data_sql, 'change_id', $data);
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
				
				build_output(CHANGELOG, $vars, $data_sql);

				$template->assign_vars(array(
					'L_HEADER'	=> msg_head($mode, $lang['TITLE'], $data_sql['change_num']),
					'L_EXPLAIN'	=> $lang['COMMON_REQUIRED'],
					
					'S_ACTION'	=> check_sid("$file&mode=$mode&id=$data"),
					'S_FIELDS'	=> $fields,
				));

				break;
		
			case 'delete':
				
				$data_sql = data(GAMES, $data, false, 1, 'row');
	
				if ( $data && $accept && $userauth['a_game_delete'] )
				{
					$sql = sql(GAMES, $mode, $data_sql, 'game_id', $data);
					$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);
	
					orders(GAMES);
	
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else if ( $data && !$accept && $userauth['a_game_delete'] )
				{
					$fields .= build_fields(array(
						'mode'	=> $mode,
						'id'	=> $data,
					));
					
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['COMMON_CONFIRM'],
						'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM'], $data_sql['game_name']),
	
						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else
				{
					message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
				}

				break;
			
			default:
		
				$template->assign_block_vars('display', array());
				
				$fields .= build_fields(array('mode' => 'create'));
				
				$change = data(CHANGELOG, '', 'change_id DESC', 0, 4);
				
				if ( !$main )
				{
					if ( !$change['main'] )
					{
						$template->assign_block_vars('display.none', array());
					}
					else
					{
						$max = count($change['main']);
				
						foreach ( $change['main'] as $row )
						{
							$change_id		= $row['change_id'];
							$change_num		= $row['change_num'];
							$change_date	= $row['change_date'];
							
							$template->assign_block_vars('display.row', array(
								'NAME'		=> href('a_txt', $file, array('main' => $change_id), $change_num, $change_num),
								'DATE'		=> create_date('Y-m-d', $row['change_date'], $userdata['user_timezone']),
								'INFO'		=> isset($change['data_id'][$change_id]) ? count($change['data_id'][$change_id]) : 0,
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $change_id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $change_id), 'icon_cancel', 'COMMON_DELETE'),
							));
						}
					}
				}
				else
				{
					if ( isset($change['data_id'][$main]) )
					{
						foreach ( $change['data_id'][$main] as $row )
						{
							$change_id	= $row['change_id'];
							$change_typ	= $lang['radio:typ'][$row['change_typ']];
							
							$template->assign_block_vars('display.row', array( 
								'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $change_id), $change_typ, $change_typ),
								'DATE'		=> $row['change_file'],
								'INFO'		=> $row['change_user'],
								'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $change_id), 'icon_update', 'COMMON_UPDATE'),
								'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $change_id), 'icon_cancel', 'COMMON_DELETE'),
							));
						}
					}
					else
					{
						$template->assign_block_vars('display.none', array());
					}
				}
				
				$template->assign_vars(array(
					'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
					'L_CREATE'	=> sprintf($lang['STF_CREATE'], $lang['TITLE']),
					'L_EXPLAIN'	=> $lang['EXPLAIN'],
			
					'L_NAME'	=> $lang['change_num'],
			
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				break;
		}
		$template->pparse($_tpl);
	}
	acp_footer();
}

?>