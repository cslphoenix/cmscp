<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_forum'] )
	{
		$module['_headmenu_02_forum']['_submenu_settings'] = $root_file;
	}

	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= '_submenu_forum';
	
	include('./pagestart.php');
	
	load_lang('forums');

	include($root_path . 'includes/acp/acp_constants.php');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= LOG_SEK_FORUM;
	$url	= POST_FORUM_URL;
	$url_c	= POST_CATEGORY_URL;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', 0) ) ? request('start', 0) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, 0);
	$data_cat	= request($url_c, 0);
	$data_type	= request('cat_type', 0);
	$data_form	= request('cat_form', 0);
	$confirm	= request('confirm', 1);
	$mode		= request('mode', 1);
	$move		= request('move', 1);
	
	$path_dir	= $root_path . 'images/forum/icon/';
	$acp_title	= sprintf($lang['sprintf_head'], $lang['forum']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_forum'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail' . $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
			'body' => 'style/acp_forum.tpl',
			'tiny' => 'style/tinymce_news.tpl',
			'uimg' => 'style/inc_java_img.tpl',					
		));
	/*
	//
	// Start program - define vars
	//
	//                View       Read      Post      Reply     Edit     Delete    Sticky   Announce Globalannounce Poll Pollcreate
	$simple_auth_ary = array(
		0	=> array(AUTH_ALL, AUTH_ALL, AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_REG),	//	Öffentlich
		1	=> array(AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_MOD),	//	Benutzer
		2	=> array(AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_MOD),	//	Benutzer versteckt
		3	=> array(AUTH_REG, AUTH_REG, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_TRI, AUTH_MOD),	//	Trail
		4	=> array(AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_TRI, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_TRI, AUTH_MOD),	//	Trail versteckt
		5	=> array(AUTH_REG, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MOD, AUTH_MEM, AUTH_MEM),	//	Member
		6	=> array(AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MOD, AUTH_MEM, AUTH_MEM),	//	Member versteckt		
		7	=> array(AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD),	//	Moderatoren
		8	=> array(AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD),	//	Moderatoren versteckt
		9	=> array(AUTH_REG, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_MOD, AUTH_ACL, AUTH_ACL),	//	Privat
		10	=> array(AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL),	//	Privat versteckt
		11	=> array(AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM),	//	Administrator
	);
	
	$simple_auth_types = array(
		$lang['forms_public'],
		$lang['forms_register'],	sprintf($lang['forms_hidden'], $lang['forms_register']),
		$lang['forms_trial'],		sprintf($lang['forms_hidden'], $lang['forms_trial']),
		$lang['forms_member'],		sprintf($lang['forms_hidden'], $lang['forms_member']),
		$lang['forms_mod'],			sprintf($lang['forms_hidden'], $lang['forms_mod']),
		$lang['forms_privat'],		sprintf($lang['forms_hidden'], $lang['forms_privat']),
		$lang['forms_admin'],
		$lang['forms_special'],
	);
	
	$forum_auth_fields = array('auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_globalannounce', 'auth_poll', 'auth_pollcreate');
	
	$field_names = array(
		'auth_view'				=> $lang['forms_view'],
		'auth_read'				=> $lang['forms_read'],
		'auth_post'				=> $lang['forms_post'],
		'auth_reply'			=> $lang['forms_reply'],
		'auth_edit'				=> $lang['forms_edit'],
		'auth_delete'			=> $lang['forms_delete'],
		'auth_sticky'			=> $lang['forms_sticky'],
		'auth_announce'			=> $lang['forms_announce'],
		'auth_globalannounce'	=> $lang['forms_globalannounce'],
		'auth_poll'				=> $lang['forms_poll'],
		'auth_pollcreate'		=> $lang['forms_pollcreate'],
	);
	
	$forum_auth_levels	= array('all', 'reg', 'trial', 'member', 'moderator', 'private', 'admin');
	$forum_auth_const	= array(AUTH_ALL, AUTH_REG, AUTH_TRI, AUTH_MEM, AUTH_MOD, AUTH_ACL, AUTH_ADM);
	*/
	
	function simple_auth($forum_row)
	{
		global $simple_auth_ary, $forum_auth_fields, $lang;
	
		for ( $i = 0; $i < count($simple_auth_ary); $i++ )
		{
			$matched = 1;
			$forum_auth_ary = $simple_auth_ary[$i];
			
			for ( $j = 0; $j < count($forum_auth_ary); $j++ )
			{
				if ( $forum_row[$forum_auth_fields[$j]] != $forum_auth_ary[$j] )
				{
					$matched = 0;
					
					break;
				}
			}
			
			if ( $matched )
			{
				return $i;
			}
		}
		return $i;
	}
	
	
	/*
	function get_info($mode, $id)
	{
		global $db;
	
		switch ( $mode )
		{
			case 'category':
				$table = FORUM_CAT;
				$idfield = 'cat_id';
				$namefield = 'cat_title';
				break;
	
			case 'forum':
				$table = FORUM;
				$idfield = 'forum_id';
				$namefield = 'forum_name';
				break;
	
			default:
				message(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
				break;
		}
		$sql = "SELECT count(*) as total
			FROM $table";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, "Couldn't get Forum/Category information", "", __LINE__, __FILE__, $sql);
		}
		$count = $db->sql_fetchrow($result);
		$count = $count['total'];
	
		$sql = "SELECT *
			FROM $table
			WHERE $idfield = $id"; 
	
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, "Couldn't get Forum/Category information", "", __LINE__, __FILE__, $sql);
		}
	
		if( $db->sql_numrows($result) != 1 )
		{
			message(GENERAL_ERROR, "Forum/Category doesn't exist or multiple forums/categories with ID $id", "", __LINE__, __FILE__);
		}
	
		$return = $db->sql_fetchrow($result);
		$return['number'] = $count;
		return $return;
	}
	
	function get_list($mode, $id, $select)
	{
		global $db;
	
		switch ( $mode )
		{
			case 'category':
				$table = FORUM_CAT;
				$idfield = 'cat_id';
				$namefield = 'cat_title';
				break;
	
			case 'forum':
				$table = FORUM;
				$idfield = 'forum_id';
				$namefield = 'forum_name';
				break;
	
			default:
				message(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
				break;
		}
	
		$sql = "SELECT * FROM $table";
		
		if ( $select == 0 )
		{
			$sql .= " WHERE $idfield <> $id";
		}
		
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, "Couldn't get list of Categories/Forums", "", __LINE__, __FILE__, $sql);
		}
	
		$catlist = '<select name="c" class="post">';
		while ( $row = $db->sql_fetchrow($result) )
		{
			$selected = ( $row[$idfield] == $id ) ? ' selected="selected"' : '';
			$catlist .= '<option value="' . $row[$idfield] . '"' . $selected . '>' . $row[$namefield] . '</option>';
		}
		$catlist .= '</select>';
	
		return($catlist);
	}
	*/
	//
	// End function block
	// ------------------
	
	if ( request('add_forum', 1) || request('add_cat', 1) )
	{
		$mode = ( request('add_forum', 1) ) ? '_create' : '_create_cat';
	
		if ( $mode == '_create' )
		{
			list($cat_id) = each($_POST['add_forum']);
			$cat_id = intval($cat_id);
			
			$name = trim(htmlentities(str_replace("\'", "'", $_POST['forum_name'][$cat_id]), ENT_COMPAT));
		}
	}
		
	if ( $mode )
	{
		switch ( $mode )
		{
			case '_create':
			case '_update':
			
				
				$template->assign_block_vars('_input', array());
				
				$template->assign_vars(array('PATH' => $path_dir));
				$template->assign_var_from_handle('UIMG', 'uimg');
				$template->assign_var_from_handle('TINYMCE', 'tiny');
				
				if ( $mode == '_create' && !(request('submit', 1)) )
				{
					$data = array(
								'forum_name'			=> (isset($name))	? $name : '',
								'cat_id'				=> (isset($cat_id))	? $cat_id : '',
								'forum_sub'				=> '0',
								'forum_desc'			=> '',
								'forum_icon'			=> '',
								'forum_status'			=> '0',
								'forum_legend'			=> '1',
								'forum_auth'			=> '0',
								'forum_order'			=> '',
								'auth_view'				=> AUTH_ALL,
								'auth_read'				=> AUTH_ALL,
								'auth_post'				=> AUTH_ALL,
								'auth_reply'			=> AUTH_ALL,
								'auth_edit'				=> AUTH_REG,
								'auth_delete'			=> AUTH_REG,
								'auth_sticky'			=> AUTH_MOD,
								'auth_announce'			=> AUTH_MOD,
								'auth_globalannounce'	=> AUTH_MOD,
								'auth_poll'				=> AUTH_REG,
								'auth_pollcreate'		=> AUTH_REG,
							);
				}
				else if ( $mode == '_update' && !(request('submit', 1)) )
				{
					$data = data(FORUM, $data_id, false, 1, 1);
				}
				else
				{
					$data = array(
								'sub'					=> request('sub'),
								'forum_name'			=> request('forum_name', 2),
								'cat_id'				=> request('cat_id', 0),
								'forum_sub'				=> request('forum_sub', 0),
								'forum_desc'			=> request('forum_desc', 2),
								'forum_icon'			=> request('forum_icon', 2),
								'forum_status'			=> request('forum_status', 1),
								'forum_legend'			=> request('forum_legend', 1),
								'forum_auth'			=> request('forum_auth', 1),
								'forum_order'			=> request('forum_order', 0) ? request('forum_order', 0) : request('forum_order_new', 0),
								'auth_view'				=> request('auth_view', 1),
								'auth_read'				=> request('auth_read', 1),
								'auth_post'				=> request('auth_post', 1),
								'auth_reply'			=> request('auth_reply', 1),
								'auth_edit'				=> request('auth_edit', 1),
								'auth_delete'			=> request('auth_delete', 1),
								'auth_sticky'			=> request('auth_sticky', 1),
								'auth_announce'			=> request('auth_announce', 1),
								'auth_globalannounce'	=> request('auth_globalannounce', 1),
								'auth_poll'				=> request('auth_poll', 1),
								'auth_pollcreate'		=> request('auth_pollcreate', 1),
							);
				}
				
				for ( $j = 0; $j < count($forum_auth_fields); $j++ )
				{
					$custom_auth[$j] = '<select class="selectsmall" name="' . $forum_auth_fields[$j] . '" id="' . $forum_auth_fields[$j] . '">';
					
					for ( $k = 0; $k < count($forum_auth_levels); $k++ )
					{
						$selected = ( $data[$forum_auth_fields[$j]] == $forum_auth_const[$k] ) ? ' selected="selected"' : '';
						$custom_auth[$j] .= "<option value=\"" . $forum_auth_const[$k] . "\"$selected>" . sprintf($lang['sprintf_select_format'], $lang['auth_' . $forum_auth_levels[$k]]) . "</option>";
					}
					
					$custom_auth[$j] .= '</select>';
		
					$template->assign_block_vars('_input._auth', array(
						'TITLE'		=> $lang['auth_forum'][$forum_auth_fields[$j]],
						'INFO'		=> $forum_auth_fields[$j],
						'SELECT'	=> $custom_auth[$j],
					));
				}

				@reset($simple_auth_ary);
				while ( list($key, $auth_levels) = each($simple_auth_ary) )
				{
					$matched = 1;
					for ( $k = 0; $k < count($auth_levels); $k++ )
					{
						$matched_type = $key;
			
						if ( $data[$forum_auth_fields[$k]] != $auth_levels[$k] )
						{
							$matched = 0;
						}
					}
			
					if ( $matched )
					{
						break;
					}
				}
					
				$simple_auth = '<select class="selectsmall" name="simpleauth" id="simpleauth">';

				for ( $j = 0; $j < count($simple_auth_types)-1; $j++ )
				{
					$selected = ( $matched_type == $j ) ? ' selected="selected"' : '';
					$simple_auth .= '<option value="' . $j . '"' . $selected . '>' . $simple_auth_types[$j] . '</option>';
				}
				
				$simple_auth .= '</select>';
				
				$auth = '';
				$teste = array();
				
				for ( $j = 0; $j < count($simple_auth_types)-1; $j++ )
				{
					foreach ( $simple_auth_ary[$j] as $key_s => $value_s )
					{
						foreach ( $forum_auth_fields as $key_f => $value_f )
						{
							if ( $key_s == $key_f )
							{
								$new[$j][$value_f] = $value_s;
							}
						}
					}
				
					foreach ( $new[$j] as $key_n => $value_n )
					{
						$set_right[$j][] = "set_right('$key_n', '$value_n')";
						
					}
					
					$set_right[$j] = implode('; ', $set_right[$j]);
					
					$auth[] = '<a style="cursor:pointer" onclick="' . $set_right[$j] . '; set_right(\'simpleauth\', \'' . $j . '\')">' . $simple_auth_types[$j] . '</a>';
				}
				
				$auth = implode('<br />', $auth);
				
				$template->assign_block_vars('_input._auth_simple', array(
					'SELECT'	=> $auth,
					'S_SELECT'	=> $simple_auth,
				));
							
				$data_cat = data(FORUM_CAT, false, 'cat_order ASC', 1, false);
				
				if ( $data_cat )
				{
					$template->assign_block_vars('_input._cats', array());
					
					for ( $j = 0; $j < count($data_cat); $j++ )
					{
						$template->assign_block_vars('_input._cats._cat', array(
							'CAT_ID'	=> $data_cat[$j]['cat_id'],
							'CAT_NAME'	=> $data_cat[$j]['cat_name'],
							
							'S_MARK'	=> ( $data['cat_id'] == $data_cat[$j]['cat_id'] ) ? 'checked="checked"' : '',
						));
					}
				}
				
				$where_cat	= ( $data['cat_id'] ) ? "cat_id = " . $data['cat_id'] : false;
				$where_form	= ( $data['forum_sub'] ) ? "forum_sub = " . $data['forum_sub'] : false;
				
				$s_order = '';
							
				if ( !$where_cat )
				{
					$s_order .= $lang['no_entry'];
				}
				else
				{
					$cats	= data(FORUM_CAT, $where_cat, 'cat_order ASC', 1, false);
					$forms	= data(FORUM, $where_cat, 'forum_order ASC', 1, false);
					
					$s_order .= "<select class=\"select\" name=\"forum_order_new\" id=\"forum_order\">";
					$s_order .= "<option selected=\"selected\" value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
					
					if ( $forms )
					{
						for ( $i = 0; $i < count($cats); $i++ )
						{
							$s_forms	= '';
							$cat_id		= $cats[$i]['cat_id'];
							$cat_name	= $cats[$i]['cat_name'];
							
							for ( $j = 0; $j < count($forms); $j++ )
							{
								$forum_cat_id	= $forms[$j]['cat_id'];
								$forum_id		= $forms[$j]['forum_id'];
								$forum_name		= $forms[$j]['forum_name'];
								$forum_order	= $forms[$j]['forum_order'];
									
								if ( $cat_id == $forum_cat_id )
								{
									$s_forms .= ( $forum_order == 10 ) ? '<option value="5">' . sprintf($lang['sprintf_select_before'], $forum_name) . '</option>' : '';
									$s_forms .= '<option value="' . ( $forum_order + 5 ) . '">' . sprintf($lang['sprintf_select_order'], $forum_name) . '</option>';
									
									$subs = data(FORUM, "forum_sub = $forum_id", 'forum_order ASC', 1, false);
									
									for ( $k = 0; $k < count($subs); $k++ )
									{
										$sub_id	= $subs[$k]['forum_id'];
										$sub_name = '&nbsp;&not;&nbsp;' . $subs[$k]['forum_name'];
										
										if ( $forum_id == $subs[$k]['forum_sub'] )
										{
											$s_forms .= "<option value=\"$sub_id\" disabled=\"disabled\">" . sprintf($lang['sprintf_select_format'], $sub_name) . "</option>";
										}
									}
								}
							}
							
							$s_order .= ( $s_forms != '' ) ? "<optgroup label=\"$cat_name\">$s_forms</optgroup>" : '';
						}
						
						$s_order .= '</select>';
					}
					else
					{
						$s_order = $lang['no_entry'];
					}
				}
				
				if ( $where_form )
				{
					$order_tmp = data(FORUM, $where_form, 'forum_order ASC', 1, false);
					
					$s_sorder = '';
					
					if ( $order_tmp )
					{
						$s_sorder .= "<select class=\"select\" name=\"forum_suborder\" id=\"forum_suborder\">";
						$s_sorder .= "<option selected=\"selected\" value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
					
						for ( $j = 0; $j < count($order_tmp); $j++ )
						{
							$forum_name	= $order_tmp[$j]['forum_name'];
							$forum_order	= $order_tmp[$j]['forum_order'];
							
							$s_sorder .= ( $forum_order == 10 ) ? "<option value=\"5\">" . sprintf($lang['sprintf_select_before'], $forum_name) . "</option>" : '';
							$s_sorder .= "<option value=\"" . ( $forum_order + 5 ) . "\">" . sprintf($lang['sprintf_select_order'], $forum_name) . "</option>";
						}
						
						$s_sorder .= "</select>";
					}
					else
					{
						$s_sorder = $lang['no_entry'];
					}
				}
				else
				{
					$s_sorder = $lang['no_entry'];
				}
				
				$list_tmp = data(FORUM, false, 'forum_order ASC', 1, false);
				$list_cat = data(FORUM_CAT, false, 'cat_order ASC', 1, false);
				
				$s_forms = '';
					
				if ( $list_cat || $list_tmp )
				{
					$s_forms .= "<select class=\"select\" name=\"forum_sub\" id=\"forum_sub\" onchange=\"setRequest2(this.options[selectedIndex].value);\">";
					$s_forms .= "<option selected=\"selected\" value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_forum']) . "</option>";
					
					for ( $i = 0; $i < count($list_cat); $i++ )
					{
						$tmp = '';
						$cat_name = $list_cat[$i]['cat_name'];
						
						for ( $j = 0; $j < count($list_tmp); $j++ )
						{
							$subs = data(FORUM, 'forum_sub = ' . $list_tmp[$j]['forum_id'], 'forum_order ASC', 1, false);
							
							if ( $list_cat[$i]['cat_id'] == $list_tmp[$j]['cat_id'] )
							{
								$id		= $list_tmp[$j]['forum_id'];
								$name	= $list_tmp[$j]['forum_name'];
								$marked	= ( $id== $data['forum_sub'] ) ? 'selected="selected"' : '';
								
								$tmp .= "<option value=\"$id\" $marked>" . sprintf($lang['sprintf_select_format'], $name) . "</option>";
							
								for ( $k = 0; $k < count($subs); $k++ )
								{
									$sub_id = $subs[$k]['forum_id'];
									$sub_name = '&nbsp;&not;&nbsp;' . $subs[$k]['forum_name'];
									
									if ( $list_tmp[$j]['forum_id'] ==  $subs[$k]['forum_sub'] )
									{
										$tmp .= "<option value=\"$sub_id\" disabled=\"disabled\">" . sprintf($lang['sprintf_select_format'], $sub_name) . "</option>";
									}
								}
							}
						}
						
						$s_forms .= ( $tmp != '' ) ? "<optgroup label=\"$cat_name\">$tmp</optgroup>" : '';
					}
					
					$s_forms .= '</select>';
				}
				
				$s_copy = '';
					
				if ( !$list_tmp )
				{
					$s_copy = $lang['no_entry'];
				}
				else
				{
					$s_copy .= "<select class=\"selectsmall\" name=\"auth_copy\" id=\"auth_copy\">";
					$s_copy .= "<option selected=\"selected\" value=\"0\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_forms']) . "</option>";
					
					for ( $i = 0; $i < count($list_cat); $i++ )
					{
						$auth = '';
						$cat_id = $list_cat[$i]['cat_id'];
						$cat_name = $list_cat[$i]['cat_name'];
						
						for ( $j = 0; $j < count($list_tmp); $j++ )
						{
							$subs = data(FORUM, 'forum_sub = ' . $list_tmp[$j]['forum_id'], 'forum_order ASC', 1, false);
							
							if ( $cat_id == $list_tmp[$j]['cat_id'] )
							{
								$forum_id	= $list_tmp[$j]['forum_id'];
								$forum_name	= $list_tmp[$j]['forum_name'];
								
								$auth .= "<option value=\"$forum_id\">" . sprintf($lang['sprintf_select_format'], $forum_name) . "</option>";
							
								for ( $k = 0; $k < count($subs); $k++ )
								{
									$sub_id		= $subs[$k]['forum_id'];
									$forum_name	= '&nbsp;&not;&nbsp;' . $subs[$k]['forum_name'];
									
									if ( $list_tmp[$j]['forum_id'] ==  $subs[$k]['forum_sub'] )
									{
										$auth .= "<option value=\"$sub_id\">" . sprintf($lang['sprintf_select_format'], $forum_name) . "</option>";
									}
								}
							}
						}
						
						$s_copy .= ( $auth != '' ) ? "<optgroup label=\"$cat_name\">$auth</optgroup>" : '';
					}
					
					$s_copy .= '</select>';
				}
								
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
				$fields .= '<input type="hidden" name="forum_order" value="' . $data['forum_order'] . '" />';
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['forum']),
					'L_INPUT'	=> sprintf($lang['sprintf' . $mode], $lang['forum'], $data['forum_name']),
				
					'L_NAME'		=> sprintf($lang['sprintf_name'], $lang['forum']),
					'L_CAT'			=> $lang['common_cat'],
					'L_DESC'		=> sprintf($lang['sprintf_desc'], $lang['forum']),
					
					'L_SUB'			=> $lang['sub'],
					'L_MAIN'		=> $lang['main'],
					'L_ICON'		=> $lang['icon'],
					'L_COPY'		=> $lang['copy'],
					
					'L_STATUS'		=> $lang['status'],
					'L_LOCKED'		=> $lang['locked'],
					'L_UNLOCKED'	=> $lang['unlocked'],
					
					'L_LEGEND'		=> $lang['legend'],
					'L_LEGEND_EX'	=> $lang['legend_ex'],
					
					'L_AUTH'		=> sprintf($lang['sprintf_auth'], $lang['forum']),
					'L_SIMPLE'		=> $lang['auth_simple'],
					'L_EXPAND'		=> $lang['auth_extended'],
					
					'NAME'			=> $data['forum_name'],
					'DESC'			=> $data['forum_desc'],
				#	'FIELD'			=> str_replace('profile_', '', $data['profile_field']),
					
					'IMAGE'			=> ( $data['forum_icon'] ) ? $path_dir . $data['forum_icon'] : $images['icon_acp_spacer'],
					
					'S_SUB_NO'		=> (!$data['forum_sub'] ) ? 'checked="checked"' : '',
					'S_SUB_YES'		=> ( $data['forum_sub'] ) ? 'checked="checked"' : '',
					'S_AUTH_SIMPLE'	=> (!$data['forum_auth'] ) ? 'checked="checked"' : '',
					'S_AUTH_EXPAND'	=> ( $data['forum_auth'] ) ? 'checked="checked"' : '',
					'S_UNLOCKED'	=> (!$data['forum_status'] ) ? 'checked="checked"' : '',
					'S_LOCKED'		=> ( $data['forum_status'] ) ? 'checked="checked"' : '',
					'S_LEGEND_NO'	=> (!$data['forum_legend'] ) ? 'checked="checked"' : '',
					'S_LEGEND_YES'	=> ( $data['forum_legend'] ) ? 'checked="checked"' : '',
					
					'S_SUB'		=> ( $data['forum_sub'] ) ? '' : 'none',
					'S_MAIN'	=> ( $data['forum_sub'] ) ? 'none' : '',
					'S_SIMPLE'	=> ( $data['forum_auth'] ) ? 'none' : '',
					'S_EXPAND'	=> ( $data['forum_auth'] ) ? '' : 'none',
					
					'S_COPY'	=> $s_copy,
					'S_FORMS'	=> $s_forms,
					'S_ORDER'	=> $s_order,
					'S_SORDER'	=> $s_sorder,
					
					'S_IMAGE'	=> select_box_files('post', 'forum_icon', $root_path . 'images/forum/icon/', $data['forum_icon']),
						
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$data = array(
								'forum_name'			=> request('forum_name', 2),
								'cat_id'				=> request('cat_id', 0),
								'forum_sub'				=> request('forum_sub', 0),
								'forum_desc'			=> request('forum_desc', 2),
								'forum_icon'			=> request('forum_icon', 2),
								'forum_status'			=> request('forum_status', 1),
								'forum_legend'			=> request('forum_legend', 1),
								'forum_order'			=> request('forum_order', 0) ? request('forum_order', 0) : request('forum_order_new', 0),
								'forum_auth'			=> request('forum_auth', 1),
								'auth_view'				=> request('auth_view', 1),
								'auth_read'				=> request('auth_read', 1),
								'auth_post'				=> request('auth_post', 1),
								'auth_reply'			=> request('auth_reply', 1),
								'auth_edit'				=> request('auth_edit', 1),
								'auth_delete'			=> request('auth_delete', 1),
								'auth_sticky'			=> request('auth_sticky', 1),
								'auth_announce'			=> request('auth_announce', 1),
								'auth_globalannounce'	=> request('auth_globalannounce', 1),
								'auth_poll'				=> request('auth_poll', 1),
								'auth_pollcreate'		=> request('auth_pollcreate', 1),
							);
							
					$auth_copy = request('auth_copy', 1);
					
					$type = ( request('sub', 1) ) ? 'sub' : 'forum';
					$t_id = ( request('sub', 1) ) ? $data['forum_sub'] : $data['cat_id'];
					
					if ( request('sub', 1) )
					{
						$data['cat_id']			= '0';
						$data['forum_order']	= request('forum_suborder', 0);
						$data['forum_legend']	= '0';
					}
					else
					{
						$data['forum_sub'] = '0';
					}
					
					if ( $data['forum_order'] == '0' )
					{
						$order = ( request('sub', 1) ) ? 'forum_sub = ' . $data['forum_sub'] : 'cat_id = ' . $data['cat_id'];
						
						$data['forum_order'] = maxi(FORUM, 'forum_order', $order);
					}
					
					if ( $data['forum_auth'] == '0' || $auth_copy )
					{
						if ( !$auth_copy )
						{
							$simple = request('simpleauth', 0);
							
							foreach ( $simple_auth_ary[$simple] as $key_s => $value_s )
							{
								foreach ( $forum_auth_fields as $key_f => $value_f )
								{
									if ( $key_s == $key_f )
									{
										$data[$value_f] = $value_s;
									}
								}
							}
						}
						else
						{
							$auth = data(FORUM, $auth_copy, false, 1, 1);

							foreach ( $auth as $key_s => $value_s )
							{
								foreach ( $forum_auth_fields as $key_f => $value_f )
								{
									if ( $key_s == $value_f )
									{
										$data[$value_f] = $value_s;
									}
								}
							}
						}
					}
					
					$error .= ( !$data['forum_name'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
				#	$error .= ( !$data['forum_desc'] ) ? ( $error ? '<br />' : '' ) . $lang['msg_select_profilefield'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create' )
						{
							$db_data = sql(FORUM, $mode, $data);
							
							$message = $lang['create']
								. sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$db_data = sql(FORUM, $mode, $data, 'forum_id', $data_id);
							
							 $message = $lang['update'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url=$data_id"));
						}
						
						#$oCache -> sCachePath = './../cache/';
						#$oCache -> deleteCache('authlist');
						
						orders_new(FORUM, $type, $t_id);
						
						log_add(LOG_ADMIN, $log, $mode, $db_data);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');
				
				break;
				
			case '_order':
				
				update(FORUM, 'forum', $move, $data_id);
				orders(FORUM, $data_type);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_order_sub':
				
				update(FORUM, 'forum', $move, $data_id);
				orders_new(FORUM, 'sub', $data_form);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete':
			
				$data = data(FORUM, $data_id, false, 1, 1);
			
				if ( $data_id && $confirm )
				{
					$type = ( $data['forum_sub'] ) ? 'sub' : 'forum';
					$t_id = ( $data['forum_sub'] ) ? $data['forum_sub'] : $data['cat_id'];
					
				#	sql(GAMES, 'delete', false, 'game_id', $data_id);
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
					$fields .= "<input type=\"hidden\" name=\"$url\" value=\"$data_id\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm'], $data['forum_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['forum'])); }
				
				$template->pparse('body');
				
				break;
			
			case '_create_cat':
			case '_update_cat':
			
				$template->set_filenames(array('body' => 'style/acp_forum.tpl'));
				$template->assign_block_vars('_input_cat', array());
				
				if ( $mode == '_create_cat' && !(request('submit', 1)) )
				{
					$data = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_order'	=> '',
							);
				}
				else if ( $mode == '_update_cat' && !(request('submit', 1)) )
				{
					$data = data(FORUM_CAT, $data_cat, false, 1, 1);
				}
				else
				{
					$data = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_order'	=> ( request('cat_order_new', 0) ) ? request('cat_order_new', 0) : request('cat_order', 0),
							);
				}
				
				$sql = "SELECT * FROM " . FORUM_CAT . " ORDER BY cat_order ASC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$cats = $db->sql_fetchrowset($result);
				
				$s_order = '';
				
				if ( $cats )
				{
					$s_order .= "<select class=\"select\" name=\"cat_order_new\" id=\"cat_order\">";
					$s_order .= "<option selected=\"selected\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_order']) . "</option>";
					
					for ( $i = 0; $i < count($cats); $i++ )
					{
						$cat_name	= $cats[$i]['cat_name'];
						$cat_order	= $cats[$i]['cat_order'];
						
						$disabled	= ( $cat_order == $data['cat_order'] ) ? ' disabled="disabled"' : '';
						
						$s_order .= ( $cat_order == 10 ) ? "<option value=\"5\"$disabled>" . sprintf($lang['sprintf_select_before'], $cat_name) . "</option>" : '';
						$s_order .= "<option value=\"" . ( $cat_order + 5 ) . "\"$disabled>" . sprintf($lang['sprintf_select_order'], $cat_name) . "</option>";
					}
					
					$s_order .= '</select>';
				}
				else
				{
					$s_order .= $lang['no_entry'];
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$data_cat\" />";
				
				$template->assign_vars(array(
					'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['forum']),
					'L_INPUT'	=> sprintf($lang['sprintf' . str_replace('_cat', '', $mode)], $lang['forum_c'], $data['cat_name']),
					'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['forum']),
					
					'NAME'		=> $data['cat_name'],
					
					'S_ORDER'	=> $s_order,
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));
				
				if ( request('submit', 1) )
				{
					$data = array(
								'cat_name'	=> request('cat_name', 2),
								'cat_order'	=> request('cat_order', 0) ? request('cat_order', 0) : request('cat_order_new', 0),
							);
							
					$data['cat_order'] = ( !$data['cat_order'] ) ? maxa(FORUM_CAT, 'cat_order', '') : $data['cat_order'];
					
					$error .= ( !$data['cat_name'] )	? ( $error ? '<br />' : '' ) . $lang['msg_empty_name'] : '';
					
					if ( !$error )
					{
						if ( $mode == '_create_cat' )
						{
							$db_data = sql(FORUM_CAT, $mode, $data);
							
							$message = $lang['create_c']
								. sprintf($lang['return'], check_sid($file), $acp_title);
						}
						else
						{
							$db_data = sql(FORUM_CAT, $mode, $data, 'cat_id', $data_cat);
							
							$message = $lang['update_c'] . sprintf($lang['return_update'], check_sid($file), $acp_title, check_sid("$file?mode=$mode&amp;$url_c=$data_cat"));
						}
						
						orders(FORUM_CAT);
						
						log_add(LOG_ADMIN, $log, $mode, $db_data);
						message(GENERAL_MESSAGE, $message);
					}
					else
					{
						log_add(LOG_ADMIN, $log, $mode, $error);
						
						$template->set_filenames(array('reg_header' => 'style/info_error.tpl'));
						$template->assign_vars(array('ERROR_MESSAGE' => $error));
						$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
					}
				}
				
				$template->pparse('body');
			
				break;
				
			case '_order_cat':
				
				update(FORUM_CAT, 'cat', $move, $data_cat);
				orders(FORUM_CAT);
				
				log_add(LOG_ADMIN, $log, $mode);
				
				$index = true;
				
				break;
				
			case '_delete_cat':
			
				$data = data(FORUM_CAT, $data_cat, false, 1, 1);
			
				if ( $data_cat && $confirm )
				{
					$db_data = sql(FORUM_CAT, $mode, $data, 'cat_id', $data_cat);
					
					$message = $lang['delete_c']
						. sprintf($lang['return'], check_sid($file), $acp_title);
					
				#	$oCache -> sCachePath = './../cache/';
				#	$oCache -> deleteCache('_display_navi_news');
					
					orders(PROFILE_CAT);
					
					log_add(LOG_ADMIN, $log, $mode, $db_data);
					message(GENERAL_MESSAGE, $message);
				}
				else if ( $data_cat && !$confirm )
				{
					$template->set_filenames(array('body' => 'style/info_confirm.tpl'));
		
					$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
					$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$data_cat\" />";
		
					$template->assign_vars(array(
						'M_TITLE'	=> $lang['common_confirm'],
						'M_TEXT'	=> sprintf($lang['msg_confirm_delete'], $lang['confirm_c'], $data['cat_name']),

						'S_ACTION'	=> check_sid($file),
						'S_FIELDS'	=> $fields,
					));
				}
				else { message(GENERAL_MESSAGE, sprintf($lang['msg_select_must'], $lang['forum_cat'])); }
				
				$template->pparse('body');
				
				break;
				
			default: message(GENERAL_ERROR, $lang['msg_select_module']); break;
		}
	
		if ( $index != true )
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	$template->set_filenames(array('body' => 'style/acp_forum.tpl'));
	$template->assign_block_vars('_display', array());
	
	$template->assign_vars(array(
		'L_HEAD'			=> sprintf($lang['sprintf_head'], $lang['forum']),
		'L_CREATE_FORUM'	=> sprintf($lang['sprintf_new_creates'], $lang['forum']),
		'L_CREATE_CAT'		=> sprintf($lang['sprintf_new_create'], $lang['forum_c']),
		
		'L_AUTH'			=> sprintf($lang['sprintf_auth'], $lang['forum']),
		
		'S_CREATE_CAT'		=> check_sid("$file?mode=_create_cat"),
		'S_CREATE_FORUM'	=> check_sid("$file?mode=_create"),
		
	));
	
	$max = maxi(FORUM_CAT, 'cat_order', '');
	$tmp = data(FORUM_CAT, false, 'cat_order ASC', 1, false);
	
	if ( $tmp )
	{
		for ( $i = 0; $i < count($tmp); $i++ )
		{
			$cat_id = $tmp[$i]['cat_id'];
			
			$forms = data(FORUM, 'cat_id = ' . $cat_id, 'cat_id, forum_order ASC', 1, false);
			
			$sql = "SELECT MAX(forum_order) AS max$cat_id FROM " . FORUM . " WHERE cat_id = $cat_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$max_forum = $db->sql_fetchrow($result);
	
			$template->assign_block_vars('_display._cat_row', array(
				'NAME'		=> $tmp[$i]['cat_name'],
				
				'MOVE_UP'	=> ( $tmp[$i]['cat_order'] != '10' ) ? '<a id="right" href="' . check_sid("$file?mode=_order_cat&amp;move=-15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" /></a>',
				'MOVE_DOWN'	=> ( $tmp[$i]['cat_order'] != $max ) ? '<a id="right" href="' . check_sid("$file?mode=_order_cat&amp;move=+15&amp;$url_c=$cat_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" /></a>',
				
				'U_UPDATE'	=> check_sid("$file?mode=_update_cat&amp;$url_c=$cat_id"),
				'U_DELETE'	=> check_sid("$file?mode=_delete_cat&amp;$url_c=$cat_id"),
			
				'S_SUBMIT'	=> "add_forum[$cat_id]",
				'S_NAME'	=> "forum_name[$cat_id]",
			));
			
			if ( $forms )
			{
				for ( $j = 0; $j < count($forms); $j++ )
				{
					$forum_id = $forms[$j]['forum_id'];
					
					$subs = data(FORUM, 'forum_sub = ' . $forum_id, 'forum_order ASC', 1, false);
					
					$sql = "SELECT MAX(forum_order) AS max$forum_id FROM " . FORUM . " WHERE forum_sub = $forum_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$max_sub = $db->sql_fetchrow($result);
						
					if ( $forms[$j]['cat_id'] == $cat_id )
					{
						$simple_auth = $simple_auth_types[simple_auth($forms[$j])];
						
						$template->assign_block_vars('_display._cat_row._forum_row', array(
							'NAME'		=> $forms[$j]['forum_name'],
							'DESC'		=> $forms[$j]['forum_desc'],
							'POSTS'		=> $forms[$j]['forum_posts'],
							'TOPICS'	=> $forms[$j]['forum_topics'],
							
							'AUTH'		=> $simple_auth,
							
							'MOVE_UP'	=> ( $forms[$j]['forum_order'] != '10' )						? '<a href="' . check_sid("$file?mode=_order&amp;cat_type=$cat_id&amp;move=-15&amp;$url=$forum_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
							'MOVE_DOWN'	=> ( $forms[$j]['forum_order'] != $max_forum['max' . $cat_id] )	? '<a href="' . check_sid("$file?mode=_order&amp;cat_type=$cat_id&amp;move=+15&amp;$url=$forum_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
							
							'U_RESYNC' => check_sid("$file?mode=_resync&amp;$url=$forum_id"),
							'U_UPDATE' => check_sid("$file?mode=_update&amp;$url=$forum_id"),						
							'U_DELETE' => check_sid("$file?mode=_delete&amp;$url=$forum_id"),
						));
					}
					
					for ( $k = 0; $k < count($subs); $k++ )
					{
						$sub_id = $subs[$k]['forum_id'];
						
						$simple_auth_sub = $simple_auth_types[simple_auth($subs[$k])];
						
						if ( $forum_id == $subs[$k]['forum_sub'] )
						{
							$template->assign_block_vars('_display._cat_row._forum_row._sub_row', array(
								'NAME'		=> $subs[$k]['forum_name'],
								'POSTS'		=> $subs[$k]['forum_posts'],
								'TOPICS'	=> $subs[$k]['forum_topics'],
								
								'AUTH'		=> $simple_auth_sub,
								
								'MOVE_UP'	=> ( $subs[$k]['forum_order'] != '10' )							? '<a href="' . check_sid("$file?mode=_order_sub&amp;cat_form=$forum_id&amp;move=-15&amp;$url=$sub_id") . '"><img src="' . $images['icon_acp_arrow_u'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_u2'] . '" alt="" />',
								'MOVE_DOWN'	=> ( $subs[$k]['forum_order'] != $max_sub['max' . $forum_id] )	? '<a href="' . check_sid("$file?mode=_order_sub&amp;cat_form=$forum_id&amp;move=+15&amp;$url=$sub_id") . '"><img src="' . $images['icon_acp_arrow_d'] . '" alt="" /></a>' : '<img src="' . $images['icon_acp_arrow_d2'] . '" alt="" />',
								
								'U_RESYNC' => check_sid("$file?mode=_resync&amp;$url=$sub_id"),
								'U_UPDATE' => check_sid("$file?mode=_update&amp;$url=$sub_id"),						
								'U_DELETE' => check_sid("$file?mode=_delete&amp;$url=$sub_id"),
							));
						}
					}
				}
			}
			else
			{
				$template->assign_block_vars('_display._cat_row._no_entry', array());
			}
		}	
	}
}

$template->pparse('body');

include('./page_footer_admin.php');

?>