<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN || $userauth['auth_forum_perm'] )
	{
		$module['hm_forum']['sm_perm2'] = $root_file;
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= ( isset($_POST['cancel']) ) ? true : false;
#	$current	= 'sm_perm_list';
	
	include('./pagestart.php');
	
#	add_lang(array('forums', 'forum_auth'));
	add_lang('forum_auth');

	include($root_path . 'includes/acp/acp_constants.php');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_FORUM;
	$url_f	= POST_FORUM;
	$url_c	= POST_FORUM_CAT;
	$url_s	= POST_FORUM_SUB;
	$file	= basename(__FILE__);
	
	$data_cats	= request($url_c, INT);
	$data_forms	= request($url_f, INT);
	$data_subs	= request($url_s, INT);
	
	$mode		= request('mode', TXT);
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['forum']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_forum_perm'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	( $header ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array('body' => 'style/acp_forum_auth.tpl'));
	
	debug($_POST);
	
	if ( request('submit', TXT) )
	{
		$sql = '';
		
		if ( $data_cats )
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
			
			$db_data = sql(FORUM, 'update', $sql, 'cat_id', $data_cats);
		}
	
	#	$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . check_sid($file) . '">'));
	
		$message = $lang['update']
			. sprintf($lang['return'], check_sid($file), $acp_title)
			. sprintf($lang['return_update'], '<a href="' . check_sid("$file?$url_c=$data_cats"));
	
		log_add(LOG_ADMIN, $log, 'forms', $db_data);
		message(GENERAL_MESSAGE, $message);
	}	
	
	if ( $data_cats )
	{
		$cats = data(FORUM_CAT, $data_cats, 'cat_order ASC', 1, false);
		
		$template->assign_block_vars('cats', array());
		
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id = $cats[$i]['cat_id'];
			$cat_name = $cats[$i]['cat_name'];
			
			$forms	= data(FORUM, "cat_id = $cat_id", "forum_order ASC", 1, false);
			$subs	= data(FORUM, "forum_sub != 0", "forum_order ASC", 1, false);
			
			for ( $t = 0; $t < count($forum_auth_fields); $t++ )
			{
				$template->assign_block_vars('cats._image', array(
					'TITLE' => $field_names[$forum_auth_fields[$t]],
					'IMAGE' => $field_images[$forum_auth_fields[$t]],
				));
			}
			
			$template->assign_block_vars('cats._cats_row', array(
				'NAME' => "<a href=\"" . check_sid("$file?$url_c=$cat_id") . "\">$cat_name</a>",
			));
			
			for ( $t = 0; $t < count($forum_auth_fields); $t++ )
			{
				$template->assign_block_vars('cats._catsrow._image', array(
					'TITLE' => $field_names[$forum_auth_fields[$t]],
					'IMAGE' => $field_images[$forum_auth_fields[$t]],
				));
			}
			
			if ( $forms )
			{
				for ( $j = 0; $j < count($forms); $j++ )
				{
					$forms_id = $forms[$j]['forum_id'];
					$forms_cat = $forms[$j]['cat_id'];
					$forms_name = $forms[$j]['forum_name'];
					
					if ( $cat_id == $forms_cat )
					{
						$template->assign_block_vars('cats._catsrow._forms_row', array(
							'CLASS'	=> ( !($j % 2) ) ? 'row_class1' : 'row_class2',
							'NAME'	=> "<a href=\"" . check_sid("$file?$url_f=$forms_id") . "\">$forms_name</a>",
						));
						
						for ( $k = 0; $k < count($forum_auth_fields); $k++ )
						{
							$item_auth_value = $forms[$j][$forum_auth_fields[$k]];
							
							for ( $l = 0; $l < count($forum_auth_const); $l++ )
							{
								if ( $item_auth_value == $forum_auth_const[$l] )
								{
									$item_auth_level = $forum_auth_levels[$l];
									break;
								}
							}
							
							$template->assign_block_vars('cats._catsrow._formsrow._forms_auth', array(
								'IMAGE'		=> $images['auth_' . $item_auth_level],
								'EXPLAIN'	=> sprintf($lang['auth_forum_explain_' . $forum_auth_fields[$k]], $lang['auth_forum_explain_' . $item_auth_level]))
							);
						}
					}
					
				}
			}
		}
		
		for ( $i = 0; $i < count($forum_auth_levels); $i++ )
		{
			$template->assign_block_vars('cats._set', array(
				'NAME' => $lang['auth_' . $forum_auth_levels[$i]]
			));
			
			for ( $j = 0; $j < count($forum_auth_fields); $j++ )
			{
				$custom_auth[$i] = '';
			
				$checked = ( $forms[0][$forum_auth_fields[$j]] == $i ) ? ' checked="checked"' : '';
				$custom_auth[$i] = "<label><input type=\"radio\" style=\"height:2em;\" value=\"" . $i . "\" name=\"" . $forum_auth_fields[$j] . "\" id=\"" . $forum_auth_fields[$j] . "_" . $i . "\"$checked>&nbsp;<img src=" . $images['auth_' . $forum_auth_levels[$i]] . " alt=\"\" /></label>";
			
				$template->assign_block_vars('cats._set._auth', array(
					'SELECT'	=> $custom_auth[$i],
				));
			}
		}
		
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
		
		$standards = implode('<br />', $auth);
		
		$fields .= "<input type=\"hidden\" name=\"$url_c\" value=\"$cat_id\">";
		
		$template->assign_vars(array(
			'STANDARDS'	=> $standards,
			
			'S_FIELDS'	=> $fields,
		));
	}
	else
	{
		$cats = data(FORUM_CAT, false, 'cat_order ASC', 1, false);
		
		$template->assign_block_vars('display', array());
		
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id = $cats[$i]['cat_id'];
			$cat_name = $cats[$i]['cat_name'];
			
			$forms	= data(FORUM, "cat_id = $cat_id", "forum_order ASC", 1, false);
			$subs	= data(FORUM, "forum_sub != 0", "forum_order ASC", 1, false);
			
			
			$template->assign_block_vars('display.cats_row', array(
				'NAME' => "<a href=\"" . check_sid("$file?$url_c=$cat_id") . "\">$cat_name</a>",
			));
			
			for ( $t = 0; $t < count($forum_auth_fields); $t++ )
			{
				$template->assign_block_vars('display.catsrow._image', array(
					'TITLE' => $field_names[$forum_auth_fields[$t]],
					'IMAGE' => $field_images[$forum_auth_fields[$t]],
				));
			}
			
			if ( $forms )
			{
				for ( $j = 0; $j < count($forms); $j++ )
				{
					$forms_id = $forms[$j]['forum_id'];
					$forms_cat = $forms[$j]['cat_id'];
					$forms_name = $forms[$j]['forum_name'];
					
					if ( $cat_id == $forms_cat )
					{
						$template->assign_block_vars('display.catsrow._forms_row', array(
							'CLASS'	=> ( !($j % 2) ) ? 'row_class1' : 'row_class2',
							'NAME'	=> "<a href=\"" . check_sid("$file?$url_f=$forms_id") . "\">$forms_name</a>",
							'SUBS'	=> "<a href=\"" . check_sid("$file?$url_s=$forms_id") . "\">Subs</a>",
						));
						
						for ( $k = 0; $k < count($forum_auth_fields); $k++ )
						{
							$item_auth_value = $forms[$j][$forum_auth_fields[$k]];
							
							for ( $l = 0; $l < count($forum_auth_const); $l++ )
							{
								if ( $item_auth_value == $forum_auth_const[$l] )
								{
									$item_auth_level = $forum_auth_levels[$l];
									break;
								}
							}
							
							$template->assign_block_vars('display.catsrow._formsrow._forms_auth', array(
								'IMAGE'		=> $images['auth_' . $item_auth_level],
								'EXPLAIN'	=> sprintf($lang['auth_forum_explain_' . $forum_auth_fields[$k]], $lang['auth_forum_explain_' . $item_auth_level]))
							);
						}
						
						for ( $l = 0; $l < count($subs); $l++ )
						{
							$sub_id = $subs[$l]['forum_id'];
							
							if ( $forms_id == $subs[$l]['forum_sub'] )
							{
								$template->assign_block_vars('display.catsrow._formsrow._subs_row', array(
									'CLASS'	=> ( !($l % 2) ) ? 'row_class2' : 'row_class1',
									'NAME'	=> '&nbsp;&not;&nbsp;' . $subs[$l]['forum_name'],
								));
							
								for ( $m = 0; $m < count($forum_auth_fields); $m++ )
								{
									$item_auth_value = $subs[$j][$forum_auth_fields[$m]];
									
									for ( $n = 0; $n < count($forum_auth_const); $n++ )
									{
										if ( $item_auth_value == $forum_auth_const[$n] )
										{
											$item_auth_level = $forum_auth_levels[$n];
											break;
										}
									}
									
									$template->assign_block_vars('display.catsrow._formsrow._subsrow._subs_auth', array(
										'IMAGE'		=> $images['auth_' . $item_auth_level],
										'EXPLAIN'	=> sprintf($lang['auth_forum_explain_' . $forum_auth_fields[$m]], $lang['auth_forum_explain_' . $item_auth_level]))
									);
								}
							}
						}
					}
					
				}
			}
			else
			{
				$template->assign_block_vars('display.catsrow._entry_empty', array());
			}
		}
	}
	
	$template->assign_vars(array(
		'L_HEAD'	=> sprintf($lang['sprintf_head'], $lang['forum']),
		
	#	'L_EXPLAIN'	=> $lang['explain'],
	
		'NO_ENTRY'	=> $lang['no_entry'],
		
		'S_ACTION'	=> check_sid($file),
	));
		
	$template->pparse('body');

	include('./page_footer_admin.php');
}

?>