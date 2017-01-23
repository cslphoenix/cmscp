<?php

if ( !empty($setmodules) )
{
	return array(
		'filename'	=> basename(__FILE__),
		'title'		=> 'acp_profile',
		'cat'		=> 'usergroups',
		'modes'		=> array(
			'main'		=> array('title' => 'acp_profile'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'acp_profile';
	
	include('./pagestart.php');

	add_lang('profile');
	acl_auth('a_user_fields');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_PROFILE;	
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$main	= request('main', TYP);
	$usub	= request('usub', TYP);
	$mode	= request('mode', TYP);
	$type	= request('type', TYP);
	$start	= request('start', INT);
	$order	= request('order', INT);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);

	$acp_title	= sprintf($lang['stf_header'], $lang['title']);
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_profile.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$base = ($settings['smain']['profile_switch']) ? 'drop:main' : 'radio:main';
	$mode = (in_array($mode, array('create', 'update', 'delete', 'move_up', 'move_down'))) ? $mode : false;
	$_tpl = ($mode == 'delete') ? 'confirm' : 'body';
	
	switch ( $mode )
	{
		case 'create':
		case 'update':
		
			$template->assign_block_vars('input', array());
			
			$vars = array(
				'profile' => array(
					'title' => 'input_data',
					'profile_name'			=> array('validate' => TYP,	'explain' => false,	'type' => 'text:25;25',	'required' => 'input_name', 'check' => true),
					'type'					=> array('validate' => INT,	'explain' => false,	'type' => 'radio:type',	'params' => array('combi', false, 'main')),
					'main'					=> array('validate' => INT,	'explain' => false,	'type' => $base,		'divbox' => true, 'params' => array(false, true, false)),
					'profile_field'			=> array('validate' => TXT,	'explain' => true,	'type' => 'text:25;25',	'divbox' => true, 'required' => array('input_fields', 'type', '1'), 'check' => true, 'prefix' => 'field_'),
					'profile_typ'			=> array('validate' => INT,	'explain' => true,	'type' => 'radio:typ',	'divbox' => true, 'params' => array(false, true, false)),
					'profile_lang'			=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
					'profile_show_user'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
					'profile_show_member'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
					'profile_show_register'	=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
					'profile_required'		=> array('validate' => INT,	'explain' => false,	'type' => 'radio:yesno', 'divbox' => true),
					'profile_order'			=> 'hidden',
				)
			);
			
			if ( $mode == 'create' && !$submit && $userauth['a_user_fields'] )
			{
				$name = ( isset($_POST['profile_name']) ) ? request('profile_name', TXT) : request('profile_field', TXT);
				$type = ( isset($_POST['profile_name']) ) ? 0 : 1;
				
				$data_sql = array(
					'profile_name'			=> $name,
					'main'					=> $main,
					'type'					=> $type,
					'profile_field'			=> 'field_' . str_replace(' ', '_', strtolower($name)),
					'profile_lang'			=> 0,
					'profile_typ'			=> 0,
					'profile_show_user'		=> 0,
					'profile_show_member'	=> 0,
					'profile_show_register'	=> 0,
					'profile_required'		=> 0,
					'profile_order'			=> 0,
				);
			}
			else if ( $mode == 'update' && !$submit )
			{
				$data_sql = data(PROFILE, $data, false, 1, true);
				$option[] = href('a_txt', $file, array('main' => $data_sql['main']), $lang['common_overview'], $lang['common_overview']);
			}
			else
			{
				$data_sql = build_request(PROFILE, $vars, $error, $mode);
				
				if ( !$error )
				{
					$current_typ	= request('current_typ', INT);
					$current_field	= request('current_field', TXT);
					
					if ( !$data_sql['type'] )
					{
						$data_sql['main'] = 0;
						$data_sql['profile_field'] = '';
					}
					
					switch ( $data_sql['profile_typ'] )
					{
						case PROFILE_TEXT: $typ = 'VARCHAR(255) NOT NULL'; break;
						case PROFILE_AREA: $typ = 'TEXT NOT NULL'; break;
						case PROFILE_TYPE: $typ = 'TINYINT(1) UNSIGNED NOT NULL'; break;
					}
					
					if ( $mode == 'create' )
					{
						$data_sql['profile_order'] = maxa(PROFILE, 'profile_order', 'main = ' . $data_sql['main']);
						
						if ( $data_sql['type'] )
						{
							sql(PROFILE_DATA, 'alter', array('part' => 'ADD `' . $data_sql['profile_field'] . '`', 'type' => $typ));
						}
						
						$sql = sql(PROFILE, $mode, $data_sql);
						$msg = $lang[$mode] . sprintf($lang['return'], check_sid($file), $acp_title);
					}
					else
					{
						if ( $data_sql['type'] == $current_typ && $current_field != $data_sql['profile_field'] )
						{
							sql(PROFILE_DATA, 'alter', array('part' => 'CHANGE `' . $current_field . '` `' . $data_sql['profile_field'] . '`', 'type' => $typ));
						}
						
						$sql = sql(PROFILE, $mode, $data_sql, 'profile_id', $data);
						$msg = $lang[$mode] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file&mode=$mode&id=$data"));
						
						orders(PROFILE, $data_sql['type']);
					}
					
					log_add(LOG_ADMIN, $log, $mode, $sql);
					message(GENERAL_MESSAGE, $msg);
				}
				else
				{
					error('ERROR_BOX', $error);
				}
				
			}
			
			build_output(PROFILE, $vars, $data_sql);

			$fields .= build_fields(array(
				'mode'			=> $mode,
				'id'			=> $data,
				'current_typ'   => $data_sql['type'],
				'current_field' => $data_sql['profile_field'],
			));
			
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
				'L_INPUT'	=> sprintf($lang['stf_' . $mode], $lang['title'], $data_sql['profile_name']),
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),

				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));

			break;
			
		case 'move_up':
		case 'move_down':
		
			if ( $userauth['a_user_fields'] )
			{
				move(PROFILE, $mode, $order, $main, $type, $usub);
				log_add(LOG_ADMIN, $log, $mode);
			}
			
		default:
		
			$template->assign_block_vars('display', array());
			
			$fields = isset($main) ? build_fields(array('mode' => 'create', 'main' => $main)) : build_fields(array('mode' => 'create'));
			$sqlout	= data(PROFILE, false, 'main ASC, profile_order ASC', 1, 4);
		
			if ( !$main )
			{
				if ( !$sqlout['main'] )
				{
					$template->assign_block_vars('display.empty', array());
				}
				else
				{
					$max = count($sqlout['main']);

					foreach ( $sqlout['main'] as $row )
					{
						$id     = $row['profile_id'];
						$name	= lang($row['profile_name']);
						$order	= $row['profile_order'];

						$template->assign_block_vars('display.row', array(
							'NAME'		=> href('a_txt', $file, array('main' => $id), $name, $name),
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $id), 'icon_update', 'com_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $id), 'icon_cancel', 'com_delete'),

							'MOVE_UP'	=> ( $order != '1' )	? href('a_img', $file, array('mode' => 'move_up',	'main' => 0, 'order' => $order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $order != $max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => 0, 'order' => $order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
						));
					}

					$template->assign_block_vars('display.main', array());
				}
			}
			else
			{
				if ( isset($sqlout['data_id'][$main]) )
				{
					$main_max = count($sqlout['data_id'][$main]);
					
					foreach ( $sqlout['data_id'][$main] as $row )
					{
						$main_id	= $row['profile_id'];
						$main_name	= lang($row['profile_name']);
						$main_order	= $row['profile_order'];

						$template->assign_block_vars('display.row', array(
							'NAME'		=> href('a_txt', $file, array('mode' => 'update', 'id' => $main_id), $main_name, $main_name),
							
							'MOVE_UP'	=> ( $main_order != '1' )		? href('a_img', $file, array('mode' => 'move_up',	'main' => $main, 'order' => $main_order), 'icon_arrow_u', 'common_order_u') : img('i_icon', 'icon_arrow_u2', 'common_order_u'),
							'MOVE_DOWN'	=> ( $main_order != $main_max )	? href('a_img', $file, array('mode' => 'move_down',	'main' => $main, 'order' => $main_order), 'icon_arrow_d', 'common_order_d') : img('i_icon', 'icon_arrow_d2', 'common_order_d'),
							
							'UPDATE'	=> href('a_img', $file, array('mode' => 'update', 'id' => $main_id), 'icon_update', 'com_update'),
							'DELETE'	=> href('a_img', $file, array('mode' => 'delete', 'id' => $main_id), 'icon_cancel', 'com_delete'),
							
							'S_NAME'	=> "menu_module[$main_id]",
							'S_SUBMIT'	=> "submit_module[$main_id]",
						));
					}
				}
				else
				{
					$template->assign_block_vars('display.empty', array());
				}
			}
			
			if ( $main )
			{
				$main_info = sqlout_id($sqlout['main'], $main, 'profile_id');
				$option[] = href('a_txt', $file, false, $lang['common_overview'], $lang['common_overview']);
				$option[] = href('a_txt', $file, array('mode' => 'update', 'id' => $main_info['id']), sprintf($lang['stf_update'], $lang['main'], $main_info['name']), $main_info['name']);
			}
			else
			{
				$option[] = '';
			}

			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['stf_header'], $lang['title']),
				'L_EXPLAIN'	=> $lang['explain'],
				'L_NAME'	=> $lang['type_0'],
				
				'L_OPTION'	=> implode($lang['com_bull'], $option),
				
				'L_CREATE_CAT'		=> sprintf($lang['stf_create'], $lang['type_0']),
				'L_CREATE_FIELD'	=> sprintf($lang['stf_create'], $lang['type_1']),
				
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
		
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>