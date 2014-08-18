<?php

if ( !empty($setmodules) )
{
	return false;
#	return array(
#		'filename'	=> basename(__FILE__),
#		'modes'		=> array(
#			'addnew'	=> 'sm_addnew',
#			'create'	=> 'sm_create',
#			'export'	=> 'sm_export',
#			'manage'	=> 'sm_manage',
#		),
#	);
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$cancel		= ( isset($_POST['cancel']) ) ? true : false;
	$current	= 'acp_themes';
	
	include('./pagestart.php');
	
	add_lang('themes');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_THEMES;
	$url	= POST_THEMES;
	$file	= basename(__FILE__);
	
	$start	= ( request('start', INT) ) ? request('start', INT) : 0;
	$start	= ( $start < 0 ) ? 0 : $start;
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);
	$move		= request('move', INT);
	
	$dir_path	= $root_path . $settings['path_themes'];
	$acp_title	= sprintf($lang['stf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN && !$userauth['auth_themes'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['notice_auth_fail'], $lang[$current]));
	}
	
	( $cancel ) ? redirect('admin/' . check_sid($file, true)) : false;
	
	$template->set_filenames(array(
		'body'		=> 'style/acp_themes.tpl',
		'error'		=> 'style/info_error.tpl',
		'confirm'	=> 'style/info_confirm.tpl',
	));
	
	$no_page_header = (!empty($HTTP_POST_VARS['send_file']) || !empty($_POST['send_file']) || $cancel) ? TRUE : FALSE;
	
	if ( $userdata['user_level'] != ADMIN )
	{
		message(GENERAL_ERROR, sprintf($lang['notice_auth_fail'], $lang[$current]));
	}
	
	switch( $mode )
	{
		case "addnew":
			
			$install_to = ( isset($HTTP_GET_VARS['install_to']) ) ? urldecode($HTTP_GET_VARS['install_to']) : $HTTP_POST_VARS['install_to'];
			$theme_name = ( isset($HTTP_GET_VARS['theme']) ) ? urldecode($HTTP_GET_VARS['theme']) : $HTTP_POST_VARS['theme'];
		
			if( isset($install_to) )
			{
	
				include($root_path. "templates/" . basename($install_to) . "/theme_info.cfg");
	
				$template_name = $$install_to;
				$found = FALSE; 
				
				for($i = 0; $i < count($template_name) && !$found; $i++)
				{
					if( $template_name[$i]['theme_name'] == $theme_name )
					{
						while(list($key, $val) = each($template_name[$i]))
						{
							$db_fields[] = $key;
							$db_values[] = str_replace("\'", "''" , $val);
						}
					}
				}
						
				$sql = "INSERT INTO " . THEMES . " (";
	
				for($i = 0; $i < count($db_fields); $i++)
				{
					$sql .= $db_fields[$i];
					if($i != (count($db_fields) - 1))
					{
						$sql .= ", ";
					}
					
				}
	
				$sql .= ") VALUES (";
	
				for($i = 0; $i < count($db_values); $i++)
				{
					$sql .= "'" . $db_values[$i] . "'";
					if($i != (count($db_values) - 1))
					{
						$sql .= ", ";
					}
				}
				$sql .= ")";
				
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Could not insert theme data!", "", __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['Theme_installed'] . "<br /><br />" . sprintf($lang['Click_return_themeadmin'], "<a href=\"" . check_sid('admin_themes.php') . "\">", "</a>");
	
				message(GENERAL_MESSAGE, $message);
			}
			else
			{
				$installable_themes = array();
				
				if( $dir = @opendir($root_path . "templates/") )
				{
					while( $sub_dir = @readdir($dir) )
					{
						print_r($sub_dir);
						
						if( !is_file(cms_realpath($root_path . 'templates/' .$sub_dir)) && !is_link(cms_realpath($root_path . 'templates/' .$sub_dir)) && $sub_dir != "." && $sub_dir != ".." && $sub_dir != "CVS" )
						{
							if( @file_exists(@cms_realpath($root_path. "templates/" . $sub_dir . "/theme_info.cfg")) )
							{
								include($root_path. "templates/" . $sub_dir . "/theme_info.cfg");
								
								for($i = 0; $i < count($$sub_dir); $i++)
								{
									$working_data = $$sub_dir;
									
									$style_name = $working_data[$i]['style_name'];
															
									$sql = "SELECT themes_id FROM " . THEMES . " WHERE style_name = '" . str_replace("\'", "''", $style_name) . "'";
									if(!$result = $db->sql_query($sql))
									{
										message(GENERAL_ERROR, "Could not query themes table!", "", __LINE__, __FILE__, $sql);
									}
	
									if(!$db->sql_numrows($result))
									{
										$installable_themes[] = $working_data[$i];
									}
								}
							}
						}
					}
					
					
					
					$template->assign_vars(array(
						"L_STYLES_TITLE" => $lang['Styles_admin'],
						"L_STYLES_ADD_TEXT" => $lang['Styles_addnew_explain'],
						"L_STYLE" => $lang['Style'],
						"L_TEMPLATE" => $lang['Template'],
						"L_INSTALL" => $lang['Install'],
						"L_ACTION" => $lang['Action'])
					);
						
					for($i = 0; $i < count($installable_themes); $i++)
					{
						$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
						$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
						$template->assign_block_vars("themes", array(
							"ROW_CLASS" => $row_class,
							"ROW_COLOR" => "#" . $row_color,
							"STYLE_NAME" => $installable_themes[$i]['theme_name'],
							"TEMPLATE_NAME" => $installable_themes[$i]['template_name'],
	
							"U_STYLES_INSTALL" => check_sid("admin_themes.php?mode=addnew&amp;theme=" . urlencode($installable_themes[$i]['theme_name']) . "&amp;install_to=" . urlencode($installable_themes[$i]['template_name'])))
						);
					
					}
					$template->pparse("body");
						
				}
				closedir($dir);
			}
			break;
		
		case "create":
		case "edit":
			$submit = ( isset($HTTP_POST_VARS['submit']) ) ? TRUE : 0;
			
			if( $submit )
			{
				//	
				// DAMN! Thats alot of data to validate...
				//
				$submitd['theme_name'] = $HTTP_POST_VARS['theme_name'];
				$submitd['template_name'] = $HTTP_POST_VARS['template_name'];
				$submitd['head_themesheet'] = $HTTP_POST_VARS['head_themesheet'];
				$submitd['body_background'] = $HTTP_POST_VARS['body_background'];
				$submitd['body_bgcolor'] = $HTTP_POST_VARS['body_bgcolor'];
				$submitd['body_text'] = $HTTP_POST_VARS['body_text'];
				$submitd['body_link'] = $HTTP_POST_VARS['body_link'];
				$submitd['body_vlink'] = $HTTP_POST_VARS['body_vlink'];
				$submitd['body_alink'] = $HTTP_POST_VARS['body_alink'];
				$submitd['body_hlink'] = $HTTP_POST_VARS['body_hlink'];
				$submitd['tr_color1'] = $HTTP_POST_VARS['tr_color1'];
				$submitd_name['tr_color1_name'] =  $HTTP_POST_VARS['tr_color1_name'];
				$submitd['tr_color2'] = $HTTP_POST_VARS['tr_color2'];
				$submitd_name['tr_color2_name'] = $HTTP_POST_VARS['tr_color2_name'];
				$submitd['tr_color3'] = $HTTP_POST_VARS['tr_color3'];
				$submitd_name['tr_color3_name'] = $HTTP_POST_VARS['tr_color3_name'];
				$submitd['tr_class1'] = $HTTP_POST_VARS['tr_class1'];
				$submitd_name['tr_class1_name'] = $HTTP_POST_VARS['tr_class1_name'];
				$submitd['tr_class2'] = $HTTP_POST_VARS['tr_class2'];
				$submitd_name['tr_class2_name'] = $HTTP_POST_VARS['tr_class2_name'];
				$submitd['tr_class3'] = $HTTP_POST_VARS['tr_class3'];
				$submitd_name['tr_class3_name'] = $HTTP_POST_VARS['tr_class3_name'];
				$submitd['th_color1'] = $HTTP_POST_VARS['th_color1'];
				$submitd_name['th_color1_name'] = $HTTP_POST_VARS['th_color1_name'];
				$submitd['th_color2'] = $HTTP_POST_VARS['th_color2'];
				$submitd_name['th_color2_name'] = $HTTP_POST_VARS['th_color2_name'];
				$submitd['th_color3'] = $HTTP_POST_VARS['th_color3'];
				$submitd_name['th_color3_name'] = $HTTP_POST_VARS['th_color3_name'];
				$submitd['th_class1'] = $HTTP_POST_VARS['th_class1'];
				$submitd_name['th_class1_name'] = $HTTP_POST_VARS['th_class1_name'];
				$submitd['th_class2'] = $HTTP_POST_VARS['th_class2'];
				$submitd_name['th_class2_name'] = $HTTP_POST_VARS['th_class2_name'];
				$submitd['th_class3'] = $HTTP_POST_VARS['th_class3'];
				$submitd_name['th_class3_name'] = $HTTP_POST_VARS['th_class3_name'];
				$submitd['td_color1'] = $HTTP_POST_VARS['td_color1'];
				$submitd_name['td_color1_name'] = $HTTP_POST_VARS['td_color1_name'];
				$submitd['td_color2'] = $HTTP_POST_VARS['td_color2'];
				$submitd_name['td_color2_name'] = $HTTP_POST_VARS['td_color2_name'];
				$submitd['td_color3'] = $HTTP_POST_VARS['td_color3'];
				$submitd_name['td_color3_name'] = $HTTP_POST_VARS['td_color3_name'];
				$submitd['td_class1'] = $HTTP_POST_VARS['td_class1'];
				$submitd_name['td_class1_name'] = $HTTP_POST_VARS['td_class1_name'];
				$submitd['td_class2'] = $HTTP_POST_VARS['td_class2'];
				$submitd_name['td_class2_name'] = $HTTP_POST_VARS['td_class2_name'];
				$submitd['td_class3'] = $HTTP_POST_VARS['td_class3'];
				$submitd_name['td_class3_name'] = $HTTP_POST_VARS['td_class3_name'];
				$submitd['fontface1'] = $HTTP_POST_VARS['fontface1'];
				$submitd_name['fontface1_name'] = $HTTP_POST_VARS['fontface1_name'];
				$submitd['fontface2'] = $HTTP_POST_VARS['fontface2'];
				$submitd_name['fontface2_name'] = $HTTP_POST_VARS['fontface2_name'];
				$submitd['fontface3'] = $HTTP_POST_VARS['fontface3'];
				$submitd_name['fontface3_name'] = $HTTP_POST_VARS['fontface3_name'];
				$submitd['fontsize1'] = intval($HTTP_POST_VARS['fontsize1']);
				$submitd_name['fontsize1_name'] = $HTTP_POST_VARS['fontsize1_name'];
				$submitd['fontsize2'] = intval($HTTP_POST_VARS['fontsize2']);
				$submitd_name['fontsize2_name'] = $HTTP_POST_VARS['fontsize2_name'];
				$submitd['fontsize3'] = intval($HTTP_POST_VARS['fontsize3']);
				$submitd_name['fontsize3_name'] = $HTTP_POST_VARS['fontsize3_name'];
				$submitd['fontcolor1'] = $HTTP_POST_VARS['fontcolor1'];
				$submitd_name['fontcolor1_name'] = $HTTP_POST_VARS['fontcolor1_name'];
				$submitd['fontcolor2'] = $HTTP_POST_VARS['fontcolor2'];
				$submitd_name['fontcolor2_name'] = $HTTP_POST_VARS['fontcolor2_name'];
				$submitd['fontcolor3'] = $HTTP_POST_VARS['fontcolor3'];
				$submitd_name['fontcolor3_name'] = $HTTP_POST_VARS['fontcolor3_name'];
				$submitd['span_class1'] = $HTTP_POST_VARS['span_class1'];
				$submitd_name['span_class1_name'] = $HTTP_POST_VARS['span_class1_name'];
				$submitd['span_class2'] = $HTTP_POST_VARS['span_class2'];
				$submitd_name['span_class2_name'] = $HTTP_POST_VARS['span_class2_name'];
				$submitd['span_class3'] = $HTTP_POST_VARS['span_class3'];
				$submitd_name['span_class3_name'] = $HTTP_POST_VARS['span_class3_name'];
				$theme_id = intval($HTTP_POST_VARS['theme_id']);
				//
				// Wheeeew! Thank heavens for copy and paste and search and replace :D
				//
				
				if($mode == "edit")
				{
					$sql = "UPDATE " . THEMES . " SET ";
					$count = 0;
	
					while(list($key, $val) = each($submitd))
					{
						if($count != 0)
						{
							$sql .= ", ";
						}
	
						//
						// I don't like this but it'll keep MSSQL from throwing
						// an error and save me alot of typing
						//
						$sql .= ( stristr($key, "fontsize") ) ? "$key = $val" : "$key = '" . str_replace("\'", "''", $val) . "'";
	
						$count++;
					}
					
					$sql .= " WHERE themes_id = $theme_id";
					
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Could not update themes table!", "", __LINE__, __FILE__, $sql);
					}
					
					//
					// Check if there's a names table entry for this theme
					//
					$sql = "SELECT themes_id 
						FROM " . THEMES_NAME . " 
						WHERE themes_id = $theme_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Could not get data from themes_name table", "", __LINE__, __FILE__, $sql);
					}
					
					if($db->sql_numrows($result) > 0)
					{
						$sql = "UPDATE " . THEMES_NAME . " 
							SET ";
						$count = 0;
						while(list($key, $val) = each($submitd_name))
						{
							if($count != 0)
							{
								$sql .= ", ";
							}
				
							$sql .= "$key = '$val'";
				
							$count++;
						}
						
						$sql .= " WHERE themes_id = $theme_id";
					}
					else
					{
						//
						// Nope, no names entry so we create a new one.
						//
						$sql = "INSERT INTO " . THEMES_NAME . " (themes_id, ";
						while(list($key, $val) = each($submitd_name))
						{
							$fields[] = $key;
							$vals[] = str_replace("\'", "''", $val);
						}
	
						for($i = 0; $i < count($fields); $i++)
						{
							if($i > 0)
							{
								$sql .= ", ";
							}
							$sql .= $fields[$i];
						}
						
						$sql .= ") VALUES ($theme_id, ";
						for($i = 0; $i < count($vals); $i++)
						{
							if($i > 0)
							{
								$sql .= ", ";
							}
							$sql .= "'" . $vals[$i] . "'";
						}
						
						$sql .= ")";
					}
											
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Could not update themes name table!", "", __LINE__, __FILE__, $sql);
					}
								
					$message = $lang['Theme_updated'] . "<br /><br />" . sprintf($lang['Click_return_themeadmin'], "<a href=\"" . check_sid('admin_themes.php') . "\">", "</a>");
	
					message(GENERAL_MESSAGE, $message);
				}
				else
				{
					//
					// First, check if we already have a theme by this name
					//
					$sql = "SELECT themes_id 
						FROM " . THEMES . " 
						WHERE theme_name = '" . str_replace("\'", "''", $submitd['theme_name']) . "'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Could not query themes table", "", __LINE__, __FILE__, $sql);
					}
					
					if($db->sql_numrows($result))
					{
						message(GENERAL_ERROR, $lang['Style_exists'], $lang['Error']);
					}				
					
					while(list($key, $val) = each($submitd))
					{
						$field_names[] = $key;
	
						if(stristr($key, "fontsize"))
						{
							$values[] = "$val";
						}
						else
						{
							$values[] = "'" . str_replace("\'", "''", $val) . "'";
						}
					}
					
					$sql = "INSERT 
						INTO " . THEMES . " (";
					for($i = 0; $i < count($field_names); $i++)
					{
						if($i != 0)
						{
							$sql .= ", ";
						}
						$sql .= $field_names[$i];
					}
					
					$sql .= ") VALUES (";
					for($i = 0; $i < count($values); $i++)
					{
						if($i != 0)
						{
							$sql .= ", ";
						}
						$sql .= $values[$i];
					}
					$sql .= ")";
					
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Could not update themes table!", "", __LINE__, __FILE__, $sql);
					}
					
					$theme_id = $db->sql_nextid();
					
					// 
					// Insert names data
					//
					$sql = "INSERT INTO " . THEMES_NAME . " (themes_id, ";
					while(list($key, $val) = each($submitd_name))
					{
						$fields[] = $key;
						$vals[] = $val;
					}
	
					for($i = 0; $i < count($fields); $i++)
					{
						if($i > 0)
						{
							$sql .= ", ";
						}
						$sql .= $fields[$i];
					}
					
					$sql .= ") VALUES ($theme_id, ";
					for($i = 0; $i < count($vals); $i++)
					{
						if($i > 0)
						{
						$sql .= ", ";
						}
					$sql .= "'" . $vals[$i] . "'";
					}
					
					$sql .= ")";
											
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Could not insert themes name table!", "", __LINE__, __FILE__, $sql);
					}
					
					$message = $lang['Theme_created'] . "<br /><br />" . sprintf($lang['Click_return_themeadmin'], "<a href=\"" . check_sid('admin_themes.php') . "\">", "</a>");
	
					message(GENERAL_MESSAGE, $message);
				}
			}
			else
			{
				if($mode == "edit")
				{
					$themes_title = $lang['Edit_theme'];
					$themes_explain = $lang['Edit_theme_explain'];
					
					$theme_id = intval($HTTP_GET_VARS['theme_id']);
					
					$selected_names = array();
					$selected_values = array();
					// 
					// Fetch the Theme Info from the db
					//
					$sql = "SELECT * 
						FROM " . THEMES . " 
						WHERE themes_id = $theme_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Could not get data from themes table", "", __LINE__, __FILE__, $sql);
					}
					
					if ( $selected_values = $db->sql_fetchrow($result) )
					{
						while(list($key, $val) = @each($selected_values))
						{
							$selected[$key] = $val;
						}
					}
					
					//
					// Fetch the Themes Name data
					//
					$sql = "SELECT * 
						FROM " . THEMES_NAME . " 
						WHERE themes_id = $theme_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, "Could not get data from themes name table", "", __LINE__, __FILE__, $sql);
					}
					
					if ( $selected_names = $db->sql_fetchrow($result) )
					{
						while(list($key, $val) = @each($selected_names))
						{
							$selected[$key] = $val;
						}
					}
	
					$fields = '<input type="hidden" name="theme_id" value="' . $theme_id . '" />';
				}
				else
				{
					$themes_title = $lang['Create_theme'];
					$themes_explain = $lang['Create_theme_explain'];
				}
				
				$template->set_filenames(array(
					"body" => "./../admin/theme/themes_edit_body.tpl")
				);
				
				if( $dir = @opendir($root_path . 'templates/') )
				{	
					$s_template_select = '<select name="template_name">';
					while( $file = @readdir($dir) )
					{	
						if( !is_file(cms_realpath($root_path . 'templates/' . $file)) && !is_link(cms_realpath($root_path . 'templates/' . $file)) && $file != "." && $file != ".." && $file != "CVS" )
						{
							if($file == $selected['template_name'])
							{
								$s_template_select .= '<option value="' . $file . '" selected="selected">' . $file . "</option>\n";
							}
							else
							{
								$s_template_select .= '<option value="' . $file . '">' . $file . "</option>\n";
							}
						}
					}
					$s_template_select .= '</select>';
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['No_template_dir']);
				}
	
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
	
				$template->assign_vars(array(
					"L_THEMES_TITLE" => $themes_title,
					"L_THEMES_EXPLAIN" => $themes_explain,
					"L_THEME_NAME" => $lang['Theme_name'],
					"L_TEMPLATE" => $lang['Template'],
					"L_THEME_SETTINGS" => $lang['Theme_settings'],
					"L_THEME_ELEMENT" => $lang['Theme_element'],
					"L_SIMPLE_NAME" => $lang['Simple_name'],
					"L_VALUE" => $lang['Value'],
					"L_STYLESHEET" => $lang['Stylesheet'],
					"L_STYLESHEET_EXPLAIN" => $lang['Stylesheet_explain'],
					"L_BACKGROUND_IMAGE" => $lang['Background_image'],
					"L_BACKGROUND_COLOR" => $lang['Background_color'],
					"L_BODY_TEXT_COLOR" => $lang['Text_color'],
					"L_BODY_LINK_COLOR" => $lang['Link_color'],
					"L_BODY_VLINK_COLOR" => $lang['VLink_color'],
					"L_BODY_ALINK_COLOR" => $lang['ALink_color'],
					"L_BODY_HLINK_COLOR" => $lang['HLink_color'],
					"L_TR_COLOR1" => $lang['Tr_color1'],
					"L_TR_COLOR2" => $lang['Tr_color2'],
					"L_TR_COLOR3" => $lang['Tr_color3'],
					"L_TR_CLASS1" => $lang['Tr_class1'],
					"L_TR_CLASS2" => $lang['Tr_class2'],
					"L_TR_CLASS3" => $lang['Tr_class3'],
					"L_TH_COLOR1" => $lang['Th_color1'],
					"L_TH_COLOR2" => $lang['Th_color2'],
					"L_TH_COLOR3" => $lang['Th_color3'],
					"L_TH_CLASS1" => $lang['Th_class1'],
					"L_TH_CLASS2" => $lang['Th_class2'],
					"L_TH_CLASS3" => $lang['Th_class3'],
					"L_TD_COLOR1" => $lang['Td_color1'],
					"L_TD_COLOR2" => $lang['Td_color2'],
					"L_TD_COLOR3" => $lang['Td_color3'],
					"L_TD_CLASS1" => $lang['Td_class1'],
					"L_TD_CLASS2" => $lang['Td_class2'],
					"L_TD_CLASS3" => $lang['Td_class3'],
					"L_FONTFACE_1" => $lang['fontface1'],
					"L_FONTFACE_2" => $lang['fontface2'],
					"L_FONTFACE_3" => $lang['fontface3'],
					"L_FONTSIZE_1" => $lang['fontsize1'],
					"L_FONTSIZE_2" => $lang['fontsize2'],
					"L_FONTSIZE_3" => $lang['fontsize3'],
					"L_FONTCOLOR_1" => $lang['fontcolor1'],
					"L_FONTCOLOR_2" => $lang['fontcolor2'],
					"L_FONTCOLOR_3" => $lang['fontcolor3'],
					"L_SPAN_CLASS_1" => $lang['span_class1'],
					"L_SPAN_CLASS_2" => $lang['span_class2'],
					"L_SPAN_CLASS_3" => $lang['span_class3'],
					"L_SAVE_SETTINGS" => $lang['Save_Settings'], 
					"THEME_NAME" => $selected['theme_name'],
					"HEAD_STYLESHEET" => $selected['head_themesheet'],
					"BODY_BACKGROUND" => $selected['body_background'],
					"BODY_BGCOLOR" => $selected['body_bgcolor'],
					"BODY_TEXT_COLOR" => $selected['body_text'],
					"BODY_LINK_COLOR" => $selected['body_link'],
					"BODY_VLINK_COLOR" => $selected['body_vlink'],
					"BODY_ALINK_COLOR" => $selected['body_alink'],
					"BODY_HLINK_COLOR" => $selected['body_hlink'],
					"TR_COLOR1" => $selected['tr_color1'],
					"TR_COLOR2" => $selected['tr_color2'],
					"TR_COLOR3" => $selected['tr_color3'],
					"TR_CLASS1" => $selected['tr_class1'],
					"TR_CLASS2" => $selected['tr_class2'],
					"TR_CLASS3" => $selected['tr_class3'],
					"TH_COLOR1" => $selected['th_color1'],
					"TH_COLOR2" => $selected['th_color2'],
					"TH_COLOR3" => $selected['th_color3'],
					"TH_CLASS1" => $selected['th_class1'],
					"TH_CLASS2" => $selected['th_class2'],
					"TH_CLASS3" => $selected['th_class3'],
					"TD_COLOR1" => $selected['td_color1'],
					"TD_COLOR2" => $selected['td_color2'],
					"TD_COLOR3" => $selected['td_color3'],
					"TD_CLASS1" => $selected['td_class1'],
					"TD_CLASS2" => $selected['td_class2'],
					"TD_CLASS3" => $selected['td_class3'],
					"FONTFACE1" => $selected['fontface1'],
					"FONTFACE2" => $selected['fontface2'],
					"FONTFACE3" => $selected['fontface3'],
					"FONTSIZE1" => $selected['fontsize1'],
					"FONTSIZE2" => $selected['fontsize2'],
					"FONTSIZE3" => $selected['fontsize3'],
					"FONTCOLOR1" => $selected['fontcolor1'],
					"FONTCOLOR2" => $selected['fontcolor2'],
					"FONTCOLOR3" => $selected['fontcolor3'],
					"SPAN_CLASS1" => $selected['span_class1'],
					"SPAN_CLASS2" => $selected['span_class2'],
					"SPAN_CLASS3" => $selected['span_class3'],
	
					"TR_COLOR1_NAME" => $selected['tr_color1_name'],
					"TR_COLOR2_NAME" => $selected['tr_color2_name'],
					"TR_COLOR3_NAME" => $selected['tr_color3_name'],
					"TR_CLASS1_NAME" => $selected['tr_class1_name'],
					"TR_CLASS2_NAME" => $selected['tr_class2_name'],
					"TR_CLASS3_NAME" => $selected['tr_class3_name'],
					"TH_COLOR1_NAME" => $selected['th_color1_name'],
					"TH_COLOR2_NAME" => $selected['th_color2_name'],
					"TH_COLOR3_NAME" => $selected['th_color3_name'],
					"TH_CLASS1_NAME" => $selected['th_class1_name'],
					"TH_CLASS2_NAME" => $selected['th_class2_name'],
					"TH_CLASS3_NAME" => $selected['th_class3_name'],
					"TD_COLOR1_NAME" => $selected['td_color1_name'],
					"TD_COLOR2_NAME" => $selected['td_color2_name'],
					"TD_COLOR3_NAME" => $selected['td_color3_name'],
					"TD_CLASS1_NAME" => $selected['td_class1_name'],
					"TD_CLASS2_NAME" => $selected['td_class2_name'],
					"TD_CLASS3_NAME" => $selected['td_class3_name'],
					"FONTFACE1_NAME" => $selected['fontface1_name'],
					"FONTFACE2_NAME" => $selected['fontface2_name'],
					"FONTFACE3_NAME" => $selected['fontface3_name'],
					"FONTSIZE1_NAME" => $selected['fontsize1_name'],
					"FONTSIZE2_NAME" => $selected['fontsize2_name'],
					"FONTSIZE3_NAME" => $selected['fontsize3_name'],
					"FONTCOLOR1_NAME" => $selected['fontcolor1_name'],
					"FONTCOLOR2_NAME" => $selected['fontcolor2_name'],
					"FONTCOLOR3_NAME" => $selected['fontcolor3_name'],
					"SPAN_CLASS1_NAME" => $selected['span_class1_name'],
					"SPAN_CLASS2_NAME" => $selected['span_class2_name'],
					"SPAN_CLASS3_NAME" => $selected['span_class3_name'],
					
					"S_THEME_ACTION" => check_sid('admin_themes.php'),
					"S_TEMPLATE_SELECT" => $s_template_select,
					"S_HIDDEN_FIELDS" => $fields)
				);
				
				$template->pparse("body");
			}
			break;
	
		case "export";
			if($HTTP_POST_VARS['export_template'])
			{
				$template_name = $HTTP_POST_VARS['export_template'];
	
				$sql = "SELECT * 
					FROM " . THEMES . " 
					WHERE template_name = '" . str_replace("\'", "''", $template_name) . "'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, "Could not get theme data for selected template", "", __LINE__, __FILE__, $sql);
				}
				
				$theme_rowset = $db->sql_fetchrowset($result);
				
				if( count($theme_rowset) == 0 )
				{
					message(GENERAL_MESSAGE, $lang['No_themes']);
				}
				
				$theme_data = '<?php'."\n\n";
				$theme_data .= "//\n// phpBB 2.x auto-generated theme config file for $template_name\n// Do not change anything in this file!\n//\n\n";
	
				for($i = 0; $i < count($theme_rowset); $i++)
				{
					while(list($key, $val) = each($theme_rowset[$i]))
					{
						if(!intval($key) && $key != "0" && $key != "themes_id")
						{
							$theme_data .= '$' . $template_name . "[$i]['$key'] = \"" . addslashes($val) . "\";\n";
						}
					}
					$theme_data .= "\n";
				}
				
				$theme_data .= '?' . '>';// Done this to prevent highlighting editors getting confused!
				
				@umask(0111);
	
				$fp = @fopen($root_path . 'templates/' . basename($template_name) . '/theme_info.cfg', 'w');
	
				if( !$fp )
				{
					//
					// Unable to open the file writeable do something here as an attempt
					// to get around that...
					//
					$fields = '<input type="hidden" name="theme_info" value="' . htmlspecialchars($theme_data) . '" />';
					$fields .= '<input type="hidden" name="send_file" value="1" /><input type="hidden" name="mode" value="export" />';
					
					$download_form = '<form action="' . check_sid('admin_themes.php') . '" method="post"><input class="mainoption" type="submit" name="submit" value="' . $lang['Download'] . '" />' . $fields;
	
					$template->set_filenames(array(
						"body" => "message_body.tpl")
					);
	
					$template->assign_vars(array(
						"MESSAGE_TITLE" => $lang['Export_themes'],
						"MESSAGE_TEXT" => $lang['Download_theme_cfg'] . "<br /><br />" . $download_form)
					);
	
					$template->pparse('body');
					exit();
				}
	
				$result = @fputs($fp, $theme_data, strlen($theme_data));
				fclose($fp);
				
				$message = $lang['Theme_info_saved'] . "<br /><br />" . sprintf($lang['Click_return_themeadmin'], "<a href=\"" . check_sid('admin_themes.php') . "\">", "</a>");
	
				message(GENERAL_MESSAGE, $message);
	
			}
			else if($HTTP_POST_VARS['send_file'])
			{
				
				header("Content-Type: text/x-delimtext; name=\"theme_info.cfg\"");
				header("Content-disposition: attachment; filename=theme_info.cfg");
	
				echo stripslashes($HTTP_POST_VARS['theme_info']);
			}
			else
			{
				$template->set_filenames(array(
					"body" => "./../admin/theme/themes_exporter.tpl")
				);
				
				if( $dir = @opendir($root_path . 'templates/') )
				{	
					$s_template_select = '<select name="export_template">';
					while( $file = @readdir($dir) )
					{	
						if( !is_file(cms_realpath($root_path . 'templates/' . $file)) && !is_link(cms_realpath($root_path . 'templates/' .$file)) && $file != "." && $file != ".." && $file != "CVS" )
						{
							$s_template_select .= '<option value="' . $file . '">' . $file . "</option>\n";
						}
					}
					$s_template_select .= '</select>';
				}
				else
				{
					message(GENERAL_MESSAGE, $lang['No_template_dir']);
				}
				
				$template->assign_vars(array(
					"L_STYLE_EXPORTER" => $lang['Export_themes'],
					"L_EXPORTER_EXPLAIN" => $lang['Export_explain'],
					"L_TEMPLATE_SELECT" => $lang['Select_template'],
					"L_SUBMIT" => $lang['Submit'], 
	
					"S_EXPORTER_ACTION" => check_sid('admin_themes.php?mode=export'),
					"S_TEMPLATE_SELECT" => $s_template_select)
				);
				
				$template->pparse("body");
				
			}
			break;
	
		case "delete":
			$theme_id = ( isset($HTTP_GET_VARS['theme_id']) ) ? intval($HTTP_GET_VARS['theme_id']) : intval($HTTP_POST_VARS['theme_id']);
			
			if( !$confirm )
			{
				if($theme_id == $board_config['default_theme'])
				{
					message(GENERAL_MESSAGE, $lang['Cannot_remove_theme']);
				}
				
				$fields = '<input type="hidden" name="mode" value="'.$mode.'" /><input type="hidden" name="theme_id" value="'.$theme_id.'" />';
				
				//
				// Set template files
				//
				$template->set_filenames(array(
					"confirm" => "./../admin/theme/confirm_body.tpl")
				);
	
				$template->assign_vars(array(
					"MESSAGE_TITLE" => $lang['Confirm'],
					"MESSAGE_TEXT" => $lang['Confirm_delete_theme'],
	
					"L_YES" => $lang['Yes'],
					"L_NO" => $lang['No'],
	
					"S_CONFIRM_ACTION" => check_sid('admin_themes.php'),
					"S_HIDDEN_FIELDS" => $fields)
				);
	
				$template->pparse("confirm");
	
			}
			else
			{
				//
				// The user has confirmed the delete. Remove the theme, the theme element
				// names and update any users who might be using this theme
				//
				$sql = "DELETE FROM " . THEMES . " 
					WHERE themes_id = $theme_id";
				if(!$result = $db->sql_query($sql, BEGIN_TRANSACTION))
				{
					message(GENERAL_ERROR, "Could not remove theme data!", "", __LINE__, __FILE__, $sql);
				}
				
				//
				// There may not be any theme name data so don't throw an error
				// if the SQL dosan't work
				//
				$sql = "DELETE FROM " . THEMES_NAME . " 
					WHERE themes_id = $theme_id";
				$db->sql_query($sql);
	
				$sql = "UPDATE " . USERS . " 
					SET user_theme = " . $board_config['default_theme'] . " 
					WHERE user_theme = $theme_id";
				if(!$result = $db->sql_query($sql, END_TRANSACTION))
				{
					message(GENERAL_ERROR, "Could not update user theme information", "", __LINE__, __FILE__, $sql);
				}
				
				$message = $lang['Style_removed'] . "<br /><br />" . sprintf($lang['Click_return_themeadmin'], "<a href=\"" . check_sid('admin_themes.php') . "\">", "</a>");
	
				message(GENERAL_MESSAGE, $message);
			}
			break;
	
		default:
		
			$template->assign_block_vars('display', array());
			
			$themes = data(THEMES, false, false, 1, false);
			
			
			$fields .= '<input type="hidden" name="mode" value="create" />';
			
			$template->assign_vars(array(
				'L_HEAD'	=> sprintf($lang['stf_head'], $lang['title']),
				'L_CREATE'	=> sprintf($lang['sprintf_new_creates'], $lang['title']),
				'L_NAME'	=> sprintf($lang['sprintf_name'], $lang['title']),
				
				'L_EXPLAIN'	=> $lang['explain'],
				
				'S_CREATE'	=> check_sid("$file?mode=create"),
				'S_ACTION'	=> check_sid($file),
				'S_FIELDS'	=> $fields,
			));
			
			for ( $i = 0; $i < count($themes); $i++ )
			{
				$themes_id = $themes[$i]['themes_id'];
	
				$template->assign_block_vars('display.style_row', array(
					'NAME_STYLE'	=> $themes[$i]['style_name'],
					'NAME_TEMPLATE'	=> $themes[$i]['template_name'],
					
					'UPDATE'	=> '<a href="' . check_sid("$file?mode=_update&amp;$url=$themes_id") . '" alt="" /><img src="' . $images['icon_update'] . '" title="' . $lang['common_update'] . '" alt="" /></a>',
					'DELETE'	=> '<a href="' . check_sid("$file?mode=_delete&amp;$url=$themes_id") . '" alt="" /><img src="' . $images['icon_cancel'] . '" title="' . $lang['com_delete'] . '" alt="" /></a>',
				));
			}
			
			$template->pparse('body');
			
			break;
	}
}

if (empty($HTTP_POST_VARS['send_file']))
{
	acp_footer();
}

?>