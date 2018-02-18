<?php

if ( !empty($setmodules) )
{
	return array(
		'FILENAME'	=> basename(__FILE__),
		'TITLE'		=> 'ACP_LOGS',
		'CAT'		=> 'SYSTEM',
		'MODES'		=> array(
			'ACP'	=> array('TITLE'		=> 'ACP_LOG_ACP'),
			'MCP'	=> array('TITLE'		=> 'ACP_LOG_MCP'),
			'UCP'	=> array('TITLE'		=> 'ACP_LOG_UCP'),
			'ERROR'	=> array('TITLE'		=> 'ACP_LOG_ERROR'),
		)
	);
}
else
{
	define('IN_CMS', true);
	
	$cancel = ( isset($_POST['cancel']) ) ? true : false;
	$submit = ( isset($_POST['submit']) ) ? true : false;
	
	$current = 'ACP_LOGS';
	
	include('./pagestart.php');
	
	add_lang(array('games', 'match', 'server', 'menu', 'news', 'logs'));
	acl_auth('A_LOG');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_LOG;
	$file	= basename(__FILE__) . $iadds;
	
	$data	= request('id', INT);
	$start	= request('start', INT);
	$mode	= request('mode', TYP);
	$accept	= request('accept', TYP);
	$action	= request('action', TYP);
	
	$_top = sprintf($lang['STF_HEADER'], $lang['TITLE']);
	
	( $cancel ) ? redirect("admin/$file") : false;
	
	$template->set_filenames(array('body' => "style/$current.tpl"));
	$_tpl = ($mode === 'delete') ? 'confirm' : 'body';
	$mode = (in_array($mode, array('error', 'delete', 'delete_all'))) ? $mode : false;
	
#	if ( request('delete', 1) || request('delete_all', 1) )
#	{
#		if ( request('delete', 1) )
#		{
#			$mode = 'delete';
#		}
#		else if ( request('delete_all', 1) )
#		{
#			$mode = '_delete_all';
#		}
#	}
	
#	
	switch ( $mode )
	{
		/*
		case 'error':
			
			$template->assign_block_vars('error', array());
			
			$errors	= data(ERROR, false, 'error_id ASC', 1, false);
			$error	= count($errors);
			
			if ( !$errors )
			{
				$template->assign_block_vars('error._entry_empty', array());
			}
			else
			{
				for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $error); $i++ )
				{
					$class = ( $i % 2 ) ? 'row_class1' : 'row_class2';
					
					$error_id			= $errors[$i]['error_id'];
					$error_user			= $errors[$i]['error_userid'];
					$error_msg_title	= $errors[$i]['error_msg_title'];
					$error_msg_text		= $errors[$i]['error_msg_text'];
					$error_sql_code		= $errors[$i]['error_sql_code'];
					$error_sql_text		= $errors[$i]['error_sql_text'];
					$error_sql_store	= $errors[$i]['error_sql_store'];
					$error_file			= str_replace(array(cms_realpath($root_path), '\\'), array('', '/'), $errors[$i]['error_file']);
					$error_file_line	= $errors[$i]['error_file_line'];
					$error_time			= create_date($config['default_dateformat'], $errors[$i]['error_time'], $config['default_timezone']);
					
					$template->assign_block_vars('error._error_row', array(
						'CLASS'	=> $class,
						'ID'		=> $error_id,
						'USER'		=> $error_user,
						'TIME'		=> $error_time,
						'FILE'		=> $error_file,
						'FILE_LINE'	=> $error_file_line,
						'MSG_TITLE'	=> $error_msg_title,
						'MSG_TEXT'	=> $error_msg_text,
						'SQL_CODE'	=> $error_sql_code,
						'SQL_TEXT'	=> $error_sql_text,
						'SQL_STORE'	=> $error_sql_store,
					));
				}
			}
		
			$current_page = ( !$error ) ? 1 : ceil( $error / $settings['per_page_entry']['acp'] );
		
			$template->assign_vars(array(
				'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
				'L_ERROR'	=> sprintf($lang['STF_HEADER'], $lang['title_error']),
				'L_EXPLAIN'	=> $lang['explain_error'],
				
				'L_DELETE'		=> $lang['Delete'],
				'L_MARK_ALL'	=> $lang['mark_all'],
				'L_MARK_DEALL'	=> $lang['MARK_DEALL'],
				'L_GOTO_PAGE'	=> $lang['Goto_page'],
				
				'PAGE_PAGING'	=> generate_pagination('admin_logs.php?mode=_error', $error, $settings['per_page_entry']['acp'], $start),
				'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ),
				
				
				'S_ACTION'		=> check_sid($file),
			));
			
			$template->pparse('body');
		
			break;
		*/	
		case 'deleteerror':

			$confirm	= isset($HTTP_POST_VARS['confirm']);
			$log_id		= ( isset($HTTP_POST_VARS['log_id']) )	? implode(', ', $HTTP_POST_VARS['log_id']) : '';
			$log_ids	= ( isset($HTTP_POST_VARS['log_ids']) )	? $HTTP_POST_VARS['log_ids'] : '';
			
			if ( $confirm && $log_ids )
			{
				$sql = 'DELETE FROM ' . ERROR . " WHERE error_id IN ($log_ids)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				log_add(LOG_ADMIN, $log, 'acp_log_delete_error');
				
				$message = $lang['delete_log_error'] . sprintf($lang['click_return_log_error'], '<a href="' . check_sid('admin_logs.php?mode=error'));
				message(GENERAL_MESSAGE, $message);
	
			}
			else if ( !$confirm && $log_id )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields = '<input type="hidden" name="mode" value="deleteerror" />';
				$fields .= '<input type="hidden" name="log_ids" value="' . $log_id . '" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['COMMON_CONFIRM'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_log_error'],
	
					'L_YES'				=> $lang['COMMON_YES'],
					'L_NO'				=> $lang['COMMON_NO'],
	
					'S_ACTION'	=> check_sid('admin_logs.php?mode=error'),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_log']);
			}
		
			$template->pparse('body');
			
			break;
		
		case 'delete_all':
		
			$confirm	= isset($HTTP_POST_VARS['confirm']);

			if ( $confirm )
			{
				$sql = 'TRUNCATE TABLE ' . LOGS;
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				log_add(LOG_ADMIN, $log, 'acp_log_delete_all');
				
				$message = $lang['delete_log_all'] . sprintf($lang['click_return_log'], '<a href="' . check_sid($file));
				message(GENERAL_MESSAGE, $message);
	
			}
			else if ( !$confirm )
			{
				$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
	
				$fields = '<input type="hidden" name="mode" value="delete_all" />';
	
				$template->assign_vars(array(
					'MESSAGE_TITLE'		=> $lang['COMMON_CONFIRM'],
					'MESSAGE_TEXT'		=> $lang['confirm_delete_all_log'],
	
					'L_YES'				=> $lang['COMMON_YES'],
					'L_NO'				=> $lang['COMMON_NO'],
	
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_MESSAGE, $lang['msg_must_select_log']);
			}
		
			$template->pparse('body');
			
			break;
			
		case 'delete':
			
			$log_id	= request('log_id', 4) ? request('log_id', 4) : '';
			$log_id = is_array($log_id) ? implode(', ', $log_id) : $log_id;
			
			$log_ids = request('log_ids', 2) ? request('log_ids', 2) : '';
			$log_ids = explode(', ', $log_ids);
			
			if ( $log_ids && $confirm )
			{
				$sql = sql(LOGS, $mode, true, 'log_id', $log_ids);
				$msg = $lang['DELETE'] . sprintf($lang['RETURN'], langs($mode), check_sid($file), $_top);

				log_add(LOG_ADMIN, $log, $mode, $sql);
				message(GENERAL_MESSAGE, $msg);
			}
			else if ( !$confirm && $log_id )
			{
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"log_ids\" value=\"$log_id\" />";

				$template->assign_vars(array(
					'M_TITLE'	=> $lang['COMMON_CONFIRM'],
					'M_TEXT'	=> sprintf($lang['NOTICE_CONFIRM_DELETE'], $lang['CONFIRM']),

					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
			}
			else
			{
				message(GENERAL_ERROR, sprintf($lang['MSG_SELECT_MUST'], $lang['TITLE']));
			}
		
			$template->pparse('confirm');
			
			break;
			
		default:
		
			switch ( $action )
			{
				case 'acp':
				case 'mcp':
				case 'ucp':
				
					$template->assign_block_vars('display', array());
					$lang_action = 'TITLE';
					
					$log_type = ($action == 'acp') ? LOG_ADMIN : (($action == 'mcp') ? LOG_MOD : LOG_USERS);
					
					$sql = 'SELECT l.*, u.user_id, u.user_name, u.user_color FROM ' . LOGS . ' l, ' . USERS . ' u WHERE l.user_id = u.user_id AND log_type = ' . $log_type . ' ORDER BY log_id DESC';
					if (!($result = $db->sql_query($sql)))
					{
						message(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
					}
					$tmp = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					if ( !$tmp )
					{
						$template->assign_block_vars('display.none', array());
					}
					else
					{
						$cnt = count($tmp);
						
						for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt); $i++ )
						{
							$class = '';
							
							$message = $tmp[$i]['log_message'];
							$section = $tmp[$i]['log_section'];
							$section = isset($lang['section'][$section]) ? $lang['section'][$section] : $section;
							
							if ( $message )
							{
								$class = ( $message == 'error' || strstr($message, 'auth_fail') ) ? 'row_error' : '';
								$message = langs('COMMON_' . strtoupper($message));
							}
							
							switch ( $tmp[$i]['log_section'] )
							{
								case SECTION_NEWS:	$log_title = data(NEWS, 'in_send = 0', 'news_date DESC, news_id DESC', 1, 3); break;
							}
							
							
							/*
							if		( strstr($message, 'create') )		{ $message = $lang['common_create']; }
							else if ( strstr($message, 'update') )		{ $message = $lang['COMMON_UPDATE']; }
							else if ( strstr($message, 'delete') )		{ $message = $lang['COMMON_DELETE']; }
							else if ( strstr($message, 'delete') )		{ $message = $lang['COMMON_DELETE']; }
							else if ( strstr($message, 'login_acp') )	{ $message = $lang['common_login_acp']; }
							else if ( strstr($message, 'login') )		{ $message = $lang['common_login']; }
							else if ( strstr($message, 'order') )		{ $message = $lang['common_order']; }
							else if ( strstr($message, 'settings') )	{ $message = $lang['common_settings']; }
							else if ( strstr($message, 'error') )		{ $message = $lang['common_error'];		$class = 'row_error'; }
							else										{ $message = $tmp[$i]['log_message']; }
							*/
							
							$log_data = unserialize($tmp[$i]['log_data']);
							$msg_data = '<br />&raquo;';
							
						#	debug($log_data, 'sdas', true);
							
							if ( is_array($log_data) )
							{
								$msg = array();
								
								foreach ( $log_data as $row )
								{
									$_meta	= isset($row['meta'])	? $row['meta'] : false;
									$_data	= isset($row['data'])	? $row['data'] : false;
									$_post	= isset($row['post'])	? $row['post'] : false;
									$field	= isset($row['field'])	? $row['field'] : false;
									
									if ( strpos($tmp[$i]['log_message'], 'create') !== false )
									{
										$msg[] = sprintf($lang['STF_LOG_CREATE'], langs($field), $row['post']);
									}
									else if ( strpos($tmp[$i]['log_message'], 'update') !== false )
									{
										if ( $field == 'main' )
										{
											switch ( $tmp[$i]['log_section'] )
											{
												case SECTION_MENU: 
												
													$menu = data(MENU, false, false, 1, 'set');
													
													foreach ( $menu as $_menu )
													{
														$_new[$_menu['menu_id']] = $_menu['menu_name'];
													}
													
													break;
											}
										}
										
										switch ( $tmp[$i]['log_section'] )
										{
											case SECTION_NEWS:	$_meta = sprintf('&nbsp;%s<br />&raquo;', $log_title[$row['meta']]['news_title']); break;
										}
										
										switch ( $field )
										{
											case 'time_update':
		
												$_lang = langs($field);
												$_data = (isset($_new[$row['data']]) ? create_shortdate($userdata['user_dateformat'], $_new[$row['data']], $userdata['user_timezone']) : create_shortdate($userdata['user_dateformat'], $row['data'], $userdata['user_timezone']));
												$_post = (isset($_new[$row['post']]) ? create_shortdate($userdata['user_dateformat'], $_new[$row['post']], $userdata['user_timezone']) : create_shortdate($userdata['user_dateformat'], $row['post'], $userdata['user_timezone']));
												
												break;
											
											default:
											
												$_lang = langs($field);
												$_data = (isset($_new[$row['data']]) ? $_new[$row['data']] : $row['data']);
												$_post = (isset($_new[$row['post']]) ? $_new[$row['post']] : $row['post']);
												
												break;
										}
										
										$msg[] = sprintf($lang['STF_LOG_CHANGE'], $_lang, $_data, $_post);
									#	$msg[] = sprintf($lang['STF_LOG_CHANGE'], langs($field), (isset($_new[$row['data']]) ? $_new[$row['data']] : $row['data']), (isset($_new[$row['post']]) ? $_new[$row['post']] : $row['post']));
										
										unset($_new);
									}
									else if ( strpos($tmp[$i]['log_message'], 'delete') !== false )
									{
										$msg[] = sprintf($lang['STF_LOG_DELETE'], langs($field), $row['data']);
									}
									else
									{
										$msg[] = sprintf($lang['STF_LOG_ERROR'], langs($field), $row);
									}
								}
								
								$msg_data .= implode('<br />&raquo;', $msg);
							}
							else
							{
								$msg_data = '&nbsp;' . langs($log_data);
							}
							
							/*
							$moep = '';
							
							if ( $tmp[$i]['log_section'] == SECTION_SERVER )
							{
								$msg = array();
								
								$data = unserialize($tmp[$i]['log_data']);
								
								$msg_data = '<br />&nbsp;&raquo;&nbsp;&nbsp;&raquo;&nbsp;';
								
								for ( $k = 0; $k < count($data); $k++ )
								{
									
									
									$_meta	= isset($log_data[$k]['meta'])	? $log_data[$k]['meta'] : '';
									$_data	= isset($log_data[$k]['data'])	? $log_data[$k]['data'] : '';
									$_post	= isset($log_data[$k]['post'])	? $log_data[$k]['post'] : '';
									$_field	= isset($log_data[$k]['field'])	? $log_data[$k]['field'] : '';
									
								#	$lng	= isset($lang[$_field]) ? $lang[$_field] : $_field;
									$lng	= $_field;
									
									if ( strstr($tmp[$i]['log_message'], 'update') && strstr($_field, '_own') ) { $moep = true; }
									
									if ( isset($_data) && isset($_post) )
									{
										$msg[] = sprintf($lang['stf_log_change'], $lng, $_data, $_post);
									}
									else if ( !$_data && $_post )
									{
										$msg[] = sprintf($lang['stf_log_create'], $lng, $_post);
									}
									else if ( $_data && !$_post )
									{
										$msg[] = sprintf($lang['stf_log_delete'], $lng, $_data);
									}
								}
								
								$msg_data .= implode('<br />&nbsp;&raquo;&nbsp;&nbsp;&raquo;&nbsp;', $msg);
								
								echo 'hmpf';
							}
							*/
							
							$template->assign_block_vars('display.row', array(
								'CLASS'		=> $class,
								
								'LOG_ID'		=> $tmp[$i]['log_id'],
							#	'USERNAME'      => $tmp[$i]['user_name'],
								'USERNAME'		=> href('ahref_style', 'admin_user.php', array('i' => '4', 'mode' => 'update', 'id' => $tmp[$i]['user_id']), $tmp[$i]['user_color'], $tmp[$i]['user_name'], $tmp[$i]['user_name']),
								'IP'            => decode_ip($tmp[$i]['user_ip']),
								'DATE'          => create_date($userdata['user_dateformat'], $tmp[$i]['log_time'], $userdata['user_timezone']),
								'SEKTION'       => $section,
								'MESSAGE'       => $message,
								'DATA'          => $msg_data,
							));
						}
					}
					
					$current_page = ( !count($tmp) ) ? 1 : ceil( count($tmp) / $settings['per_page_entry']['acp'] );
					
					$template->assign_vars(array(
						'PAGE_PAGING'	=> generate_pagination($file, count($tmp), $settings['per_page_entry']['acp'], $start),
						'PAGE_NUMBER'   => sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ),
					));
				
					break;
					
				case 'error':
				
					$template->assign_block_vars('error', array());
					
					$data	= data(ERROR, false, 'error_id ASC', 1, false);
					$cnt	= count($data);
					
					if ( !$data )
					{
						$template->assign_block_vars('error.empty', array());
					}
					else
					{
						for ( $i = $start; $i < min($settings['per_page_entry']['acp'] + $start, $cnt); $i++ )
						{
							$class = ( $i % 2 ) ? 'row_class1' : 'row_class2';
							
							$error_id			= $data[$i]['error_id'];
							$error_user			= $data[$i]['error_userid'];
							$error_msg_title	= $data[$i]['error_msg_title'];
							$error_msg_text		= $data[$i]['error_msg_text'];
							$error_sql_code		= $data[$i]['error_sql_code'];
							$error_sql_text		= $data[$i]['error_sql_text'];
							$error_sql_store	= $data[$i]['error_sql_store'];
							$error_file			= str_replace(array(cms_realpath($root_path), '\\'), array('', '/'), $data[$i]['error_file']);
							$error_file_line	= $data[$i]['error_file_line'];
							$error_time			= create_date($config['default_dateformat'], $data[$i]['error_time'], $config['default_timezone']);
							
							$template->assign_block_vars('error.row', array(
								'CLASS'	=> $class,
								'ID'		=> $error_id,
								'USER'		=> $error_user,
								'TIME'		=> $error_time,
								'FILE'		=> $error_file,
								'FILE_LINE'	=> $error_file_line,
								'MSG_TITLE'	=> $error_msg_title,
								'MSG_TEXT'	=> $error_msg_text,
								'SQL_CODE'	=> $error_sql_code,
								'SQL_TEXT'	=> $error_sql_text,
								'SQL_STORE'	=> $error_sql_store,
							));
						}
					}
				
					$current_page = ( !$error ) ? 1 : ceil( $error / $settings['per_page_entry']['acp'] );
				
					$template->assign_vars(array(
						'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang_action),
						'L_ERROR'	=> sprintf($lang['STF_HEADER'], $lang['TITLE_ERROR']),
						'L_EXPLAIN'	=> $lang['explain_error'],
						
						'L_DELETE'		=> $lang['Delete'],
						'L_MARK_ALL'	=> $lang['MARK_ALL'],
						'L_MARK_DEALL'	=> $lang['MARK_DEALL'],
						'L_GOTO_PAGE'	=> $lang['Goto_page'],
						
						'PAGE_PAGING'	=> generate_pagination('admin_logs.php?mode=_error', $error, $settings['per_page_entry']['acp'], $start),
						'PAGE_NUMBER'	=> sprintf($lang['common_page_of'], ( floor( $start / $settings['per_page_entry']['acp'] ) + 1 ), $current_page ),
						
						
						'S_ACTION'		=> check_sid($file),
					));
				
					break;
				}
			
				$template->assign_vars(array(
					'L_HEADER'	=> sprintf($lang['STF_HEADER'], $lang['TITLE']),
					'L_EXPLAIN'	=> $lang['EXPLAIN'],
			
					'L_IP'			=> $lang['LOG_IP'],
					'L_TIME'		=> $lang['LOG_TIME'],
					'L_SECTION'		=> $lang['LOG_SECTION'],
					
					'S_ACTION'		=> check_sid($file),
				));
		
			break;
	}
	$template->pparse($_tpl);
	acp_footer();
}

?>