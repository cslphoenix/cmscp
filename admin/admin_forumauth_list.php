<?php

if ( !empty($setmodules) )
{
	$module['sm_forumauth_list'] = array(
		'filename'	=> basename(__FILE__),
		'modes'		=> array(
			'index'	=> 'sm_forumauth_list',
		),
	);
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_perm_list';
	
	include('./pagestart.php');
	
	add_lang('forum_auth');
	
	include($root_path . 'includes/acp/acp_constants.php');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_GAMES;
	$url	= POST_FORUM;
	$url_c	= POST_CATEGORY;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$data_cat	= request($url_c, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', INT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['forum']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_forum_auth'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	debug($_POST);
	
	$cat_id		= ( $data_cat )	? $data_cat : '';
	$cat_sql	= ( $data_cat )	? "AND c.cat_id = $data_cat" : '';
	$forum_id	= ( $data_id )	? $data_id : '';
	$forum_sql	= ( $data_id )	? "AND forum_id = $data_id" : '';
	
	if ( $update )
	{
		$sql = '';
	
		if ( !empty($forum_id) )
		{
			for ( $i = 0; $i < count($forum_auth_fields); $i++ )
			{
				$value = intval($_POST[$forum_auth_fields[$i]]);

				if ( $forum_auth_fields[$i] == 'auth_vote' )
				{
					if ( $_POST['auth_vote'] == AUTH_ALL )
					{
						$value = AUTH_REG;
					}
				}

			#	$sql .= ( ( $sql != '' ) ? ', ' : '' ) .$forum_auth_fields[$i] . ' = ' . $value;
				
				$sql[$forum_auth_fields[$i]] = $value;
			}
			
			$db_data = sql(FORUM, 'update', $sql, 'forum_id', $forum_id);
			
			$forum_sql = '';
		}
		elseif (!empty($cat_id))
		{
			for ( $i = 0; $i < count($forum_auth_fields); $i++ )
			{
				$value = intval($_POST[$forum_auth_fields[$i]]);
	
				if ( $forum_auth_fields[$i] == 'auth_poll' )
				{
					if ( $_POST['auth_poll'] == AUTH_ALL )
					{
						$value = AUTH_REG;
					}
				}
				
				$sql[$forum_auth_fields[$i]] = $value;
			}
			
			$db_data = sql(FORUM, 'update', $sql, 'cat_id', $cat_id);
			
			$cat_sql = '';
		}
		
	#	$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid($file) . '">'));
		
		$message = $lang['update']
			. sprintf($lang['return'], check_sid($file), $acp_title);
	
		log_add(LOG_ADMIN, $log, 'forms', $db_data);
		message(GENERAL_MESSAGE, $message);
	}
	
	$sql = "SELECT f.* FROM " . FORUM . " f, " . FORUM_CAT . " c
				WHERE c.cat_id = f.cat_id $forum_sql $cat_sql
			ORDER BY c.cat_order ASC, f.forum_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$forum = $db->sql_fetchrowset($result);
	
	if ( !$cat_id && !$forum_id )
	{
		$template->set_filenames(array('body' => 'style/acp_forum_auth.tpl'));
		$template->assign_block_vars('auth_list', array());
	
		$template->assign_vars(array(
			
			'L_LEGEND'	=> $lang['legende'],
		
			'L_AUTH_TITLE' => $lang['Permissions_List'],
			'L_AUTH_EXPLAIN' => $lang['Forum_auth_list_explain'],
			'L_FORUM_NAME' => $lang['Forum_name'],
			'S_COLUMN_SPAN' => count($forum_auth_fields)+1,
		));
		
		/*	Legende	*/
		for ( $i = 0; $i < count($forum_auth_fields); $i++ )
		{
			$template->assign_block_vars('auth_list._title', array(
				'TITLE' => $field_names[$forum_auth_fields[$i]],
				'IMAGE' => $field_images[$forum_auth_fields[$i]],
			));
			
			if ( $i == 6 )
			{
				break;
			}
		}
		
		for ( $i = 7; $i < count($forum_auth_fields); $i++ )
		{
			$template->assign_block_vars('auth_list._title2', array(
				'TITLE' => $field_names[$forum_auth_fields[$i]],
				'IMAGE' => $field_images[$forum_auth_fields[$i]],
			));
		}
		
		for ( $i = 0; $i < count($forum_auth_levels); $i++ )
		{
			$template->assign_block_vars('auth_list._auth', array(
				'TITLE' => $lang['auth_' . $forum_auth_levels[$i]],
				'IMAGE' => $images['auth_' . $forum_auth_levels[$i]],
			));
		}
		/*	Legende	*/
		
		for ( $i = 0; $i < count($forum_auth_fields); $i++ )
		{
			$template->assign_block_vars('auth_list._titles', array(
				'TITLE' => $field_names[$forum_auth_fields[$i]],
				'IMAGE' => $field_images[$forum_auth_fields[$i]],
			));
		}
		
		$sql = "SELECT * FROM " . FORUM_CAT . " ORDER BY cat_order";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
		}
		$cats = $db->sql_fetchrowset($result);
	
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id = $cats[$i]['cat_id'];
	
			$template->assign_block_vars('auth_list.cat_row', array(
			#	'CAT_NAME'	=> $cats[$i]['cat_name'],
				'CAT_NAME'	=> '<a href="'.check_sid('admin_forumauth_list.php?'.$url_c.'='.$cats[$i]['cat_id']).'">'.$cats[$i]['cat_name'].'</a>',
			));
			
			for ( $j = 0; $j < count($forum); $j++ )
			{
				$forum_id = $forum[$j]['forum_id'];
				
				$subs = data(FORUM, 'forum_sub = ' . $forum_id, 'forum_order ASC', 1, false);
				
				if ( $cat_id == $forum[$j]['cat_id'] )
				{
					$template->assign_block_vars('auth_list.catrow.forum_row', array(
						'ROW_CLASS' => ( !($j % 2) ) ? 'row_class1' : 'row_class2',
						'FORUM_NAME' => '<a href="'.check_sid('admin_forumauth_list.php?'.$url.'='.$forum[$j]['forum_id']).'">'.$forum[$j]['forum_name'].'</a>')
					);
	
					for ($k=0; $k<count($forum_auth_fields); $k++)
					{
						$item_auth_value = $forum[$j][$forum_auth_fields[$k]];
						for ($l=0; $l<count($forum_auth_const); $l++)
						{
							if ($item_auth_value == $forum_auth_const[$l])
							{
								$item_auth_level = $forum_auth_levels[$l];
								break;
							}
						}
						$template->assign_block_vars('auth_list.catrow.forumrow.forum_auth_data', array(
							'CELL_VALUE' => $images['auth_' . $item_auth_level],
							'AUTH_EXPLAIN' => sprintf($lang['auth_forum_explain_' . $forum_auth_fields[$k]], $lang['auth_forum_explain_' . $item_auth_level]))
						);
					}
					
					for ( $l = 0; $l < count($subs); $l++ )
					{
						$sub_id = $subs[$l]['forum_id'];
						
						if ( $forum_id == $subs[$l]['forum_sub'] )
						{
							$template->assign_block_vars('auth_list.catrow.forumrow._sub_row', array(
								'ROW'	=> ( !($l % 2) ) ? 'row_class2' : 'row_class1',
								'NAME'	=> '&nbsp;&not;&nbsp;' . $subs[$l]['forum_name'],
							));
							
							for ( $m=0; $m<count($forum_auth_fields); $m++)
							{
								$item_auth_value = $subs[$j][$forum_auth_fields[$m]];
								for ($n=0; $n<count($forum_auth_const); $n++)
								{
									if ($item_auth_value == $forum_auth_const[$n])
									{
										$item_auth_level = $forum_auth_levels[$n];
										break;
									}
								}
								$template->assign_block_vars('auth_list.catrow.forumrow._subrow._auth_sub', array(
									'CELL_VALUE' => $images['auth_' . $item_auth_level],
									'AUTH_EXPLAIN' => sprintf($lang['auth_forum_explain_' . $forum_auth_fields[$m]], $lang['auth_forum_explain_' . $item_auth_level]))
								);
							}
						}
					}
				}
			}
		}
	}
	elseif ( !empty($forum_id) )
	{
		//
		// Output the authorisation details if an forum id was
		// specified
		//
		$template->set_filenames(array(
			'body' => 'style/auth_forum_body.tpl')
		);
	
		$forum_name = $forum[0]['forum_name'];
	
		@reset($simple_auth_ary);
		while( list($key, $auth_levels) = each($simple_auth_ary))
		{
			$matched = 1;
			for($k = 0; $k < count($auth_levels); $k++)
			{
				$matched_type = $key;
	
				if ( $forum[0][$forum_auth_fields[$k]] != $auth_levels[$k] )
				{
					$matched = 0;
				}
			}
	
			if ( $matched )
			{
				break;
			}
		}
	
		//
		// If we didn't get a match above then we
		// automatically switch into 'advanced' mode
		//
		if ( !isset($adv) && !$matched )
		{
			$adv = 1;
		}
	
		$s_column_span == 0;
	
		if ( empty($adv) )
		{
			$simple_auth = '<select name="simpleauth">';
	
			for($j = 0; $j < count($simple_auth_types); $j++)
			{
				$selected = ( $matched_type == $j ) ? ' selected="selected"' : '';
				$simple_auth .= '<option value="' . $j . '"' . $selected . '>' . $simple_auth_types[$j] . '</option>';
			}
	
			$simple_auth .= '</select>';
	
			$template->assign_block_vars('forum_auth_titles', array(
				'CELL_TITLE' => $lang['Simple_mode'])
			);
			$template->assign_block_vars('forum_auth_data', array(
				'S_AUTH_LEVELS_SELECT' => $simple_auth)
			);
	
			$s_column_span++;
		}
		else
		{
			//
			// Output values of individual
			// fields
			//
			for($j = 0; $j < count($forum_auth_fields); $j++)
			{
				$custom_auth[$j] = '&nbsp;<select name="' . $forum_auth_fields[$j] . '">';
	
				for($k = 0; $k < count($forum_auth_levels); $k++)
				{
					$selected = ( $forum[0][$forum_auth_fields[$j]] == $forum_auth_const[$k] ) ? ' selected="selected"' : '';
					$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . $lang['auth_' . $forum_auth_levels[$k]] . '</option>';
				}
				$custom_auth[$j] .= '</select>&nbsp;';
	
				$cell_title = $field_names[$forum_auth_fields[$j]];
	
				$template->assign_block_vars('forum_auth_titles', array(
					'CELL_TITLE' => $cell_title)
				);
				$template->assign_block_vars('forum_auth_data', array(
					'S_AUTH_LEVELS_SELECT' => $custom_auth[$j])
				);
	
				$s_column_span++;
			}
		}
	
		$adv_mode = ( empty($adv) ) ? '1' : '0';
		$switch_mode = check_sid('admin_forumauth_list.php?id=' . $forum_id . "&adv=". $adv_mode);
		$switch_mode_text = ( empty($adv) ) ? $lang['Advanced_mode'] : $lang['Simple_mode'];
		$u_switch_mode = '<a href="' . $switch_mode . '">' . $switch_mode_text . '</a>';
	
		$fields = '<input type="hidden" name="id" value="' . $forum_id . '">';
	
		$template->assign_vars(array(
		
			
		
		'FORUM_NAME' => $forum_name,
	
			'L_FORUM' => $lang['Forum'], 
			'L_AUTH_TITLE' => $lang['Auth_Control_Forum'],
			'L_AUTH_EXPLAIN' => $lang['Forum_auth_explain'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
	
			'U_SWITCH_MODE' => $u_switch_mode,
	
			'S_FORUMAUTH_ACTION' => check_sid($file),
			'S_COLUMN_SPAN' => $s_column_span,
			'S_FIELDS' => $fields)
		);
	
	}
	else
	{
		$template->set_filenames(array('body' => 'style/auth_cat_body.tpl'));
		
		for ( $i = 0; $i < count($forum_auth_fields); $i++ )
		{
			$template->assign_block_vars('forum_auth_titles', array(
				'CELL_TITLE' => $field_names[$forum_auth_fields[$i]],
				'CELL_IMAGE' => $field_images[$forum_auth_fields[$i]],
			));
		}
		
		$sql = 'SELECT c.cat_id, c.cat_name, c.cat_order
					FROM ' . FORUM_CAT . ' c
					WHERE c.cat_id = ' . $cat_id . '
				ORDER BY c.cat_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
		}
		$category_rows = $db->sql_fetchrowset($result);
	
		$cat_id = $category_rows[0]['cat_id'];
		$cat_name = $category_rows[0]['cat_name'];
		
		$template->assign_block_vars('cat_row', array(
			'CAT_NAME'	=> $cat_name,
			'CAT'	=> check_sid('admin_forumauth_list.php?' . $url_c . '=' .$cat_id)
		));
		
		for ( $i = 0; $i < count($forum_auth_fields); $i++ )
		{
			$template->assign_block_vars('catrow.forum_auth_titles', array(
				'CELL_TITLE' => $field_names[$forum_auth_fields[$i]],
				'CELL_IMAGE' => $field_images[$forum_auth_fields[$i]],
			));
		}
	
		for ( $j=0; $j < count($forum); $j++ )
		{
			if ( $cat_id == $forum[$j]['cat_id'] )
			{
				$forum_id	= $forum[$j]['forum_id'];
				$forum_name	= $forum[$j]['forum_name'];
				
				$template->assign_block_vars('catrow.forum_row', array(
					'ROW_CLASS' => ( !($j % 2) ) ? 'row_class1' : 'row_class2',
					'FORUM_NAME' => '<a href="' . check_sid('admin_forumauth_list.php?id=' . $forum_id) . '">' . $forum_name . '</a>'
				));
				
				for ( $k=0; $k < count($forum_auth_fields); $k++ )
				{
					$item_auth_value = $forum[$j][$forum_auth_fields[$k]];
					
					for ( $l=0; $l < count($forum_auth_const); $l++ )
					{
						if ( $item_auth_value == $forum_auth_const[$l] )
						{
							$item_auth_level = $forum_auth_levels[$l];
							break;
						}
					}
					$template->assign_block_vars('catrow.forumrow.forum_auth_data', array(
						'CELL_VALUE'	=> $images['auth_' . $item_auth_level],
						'AUTH_EXPLAIN'	=> sprintf($lang['auth_forum_explain_' . $forum_auth_fields[$k]], $lang['auth_forum_explain_' . $item_auth_level])
					));
				}
			}
		}
	
		/*
		for($j = 0; $j < count($forum_auth_fields); $j++)
		{
			$custom_auth[$j] = '&nbsp;<select class="selectsmall" name="' . $forum_auth_fields[$j] . '">';
	
			for($k = 0; $k < count($forum_auth_levels); $k++)
			{
				$selected = ( $forum[0][$forum_auth_fields[$j]] == $forum_auth_const[$k] ) ? ' selected="selected"' : '';
				$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . $lang['auth_small_' . $forum_auth_levels[$k]] . '</option>';
			}
			$custom_auth[$j] .= '</select>&nbsp;';
	
			$template->assign_block_vars('forum_auth_data', array(
				'S_AUTH_LEVELS_SELECT' => $custom_auth[$j]
			));
		}
		*/
		
		
		for ( $j = 0; $j < count($forum_auth_fields); $j++ )
		{
			$custom_auth[$j] = '';
			
			for ( $k = 0; $k < count($forum_auth_levels); $k++ )
			{
				$checked = ( $forum[0][$forum_auth_fields[$j]] == $forum_auth_const[$k] ) ? ' checked="checked"' : '';
		#		$custom_auth[$j] .= ( $forum[0][$forum_auth_fields[$j]] == $forum_auth_const[$k] ) ? 'yes'. $forum_auth_const[$k] : 'no'. $forum_auth_const[$k];
				$custom_auth[$j] .= "<label><input type=\"radio\" style=\"height:2em;\" value=\"" . $forum_auth_const[$k] . "\" name=\"" . $forum_auth_fields[$j] . "\"$checked><img src=" . $images['auth_' . $forum_auth_levels[$k]] . " alt=\"\" /></label>";
			}
			
			$template->assign_block_vars('auth_normal', array(
				'SELECT'	=> $custom_auth[$j],
			));
		}
		
		
		for ( $i = 0; $i < count($forum_auth_levels); $i++ )
		{
			$template->assign_block_vars('set', array(
				'NAME' => $lang['auth_' . $forum_auth_levels[$i]]
			));
			
			for ( $j = 0; $j < count($forum_auth_fields); $j++ )
			{
				$custom_auth[$i] = '';
			
				$checked = ( $forum[0][$forum_auth_fields[$j]] == $i ) ? ' checked="checked"' : '';
				$custom_auth[$i] = "<label><input type=\"radio\" style=\"height:2em;\" value=\"" . $i . "\" name=\"" . $forum_auth_fields[$j] . "\" id=\"" . $forum_auth_fields[$j] . "_" . $i . "\"$checked>&nbsp;<img src=" . $images['auth_' . $forum_auth_levels[$i]] . " alt=\"\" /></label>";
			
				$template->assign_block_vars('set._auth', array(
					'SELECT'	=> $custom_auth[$i],
				));
			}
		}
		
		$auth = '';
		
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
				$set_right[$j][] = "set_right('$key_n" . "_" . "$value_n')";
			}
			
			$set_right[$j] = implode('; ', $set_right[$j]);
			
			$auth[] = '<a style="cursor:pointer" onclick="' . $set_right[$j] . '">' . $simple_auth_types[$j] . '</a>';
		}
		
		$standards = implode(', ', $auth);
	
		
		$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$cat_id\">";
	
		$template->assign_vars(array(
		
			'STANDARDS'	=> $standards,
			
			'CAT_NAME' => $cat_name,
	
			'L_AUTH_TITLE' => $lang['Auth_Control_Category'],
			'L_AUTH_EXPLAIN' => $lang['Cat_auth_list_explain'],
			'L_CATEGORY' => $lang['Category'],
			'L_FORUM_NAME' => $lang['Forum_name'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
	
			
			'S_COLUMN_SPAN' => count($forum_auth_fields)+1,
			
			'S_ACTION' => check_sid($file),
			'S_FIELDS' => $fields,
		));
	
	
	}
}

$template->pparse('body');

include('./page_footer_admin.php');

?>