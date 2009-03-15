<?php

/***

CREATE TABLE IF NOT EXISTS `cms_contact` (
  `contact_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `contact_from` varchar(50) COLLATE utf8_bin NOT NULL,
  `contact_type` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `contact_mail` varchar(100) COLLATE utf8_bin NOT NULL,
  `contact_homepage` varchar(200) COLLATE utf8_bin NOT NULL,
  `contact_rival_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `contact_rival_tag` varchar(50) COLLATE utf8_bin NOT NULL,
  `contact_maps` varchar(50) COLLATE utf8_bin NOT NULL,
  `contact_message` text COLLATE utf8_bin NOT NULL,
  `contact_team` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `contact_age` mediumint(2) unsigned NOT NULL DEFAULT '0',
  `contact_date` int(11) unsigned NOT NULL DEFAULT '0',
  `contact_wartype` smallint(3) unsigned NOT NULL DEFAULT '0',
  `contact_categorie` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `contact_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `contact_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`contact_id`)
);





	
	admin_contact.php
	
	Erstellt von Phoenix
	
	
***/

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	if ($userdata['user_level'] == ADMIN)
	{
		$module['contact']['contact_over'] = $filename;
	}
	if ($userdata['auth_contact'] || $userdata['user_level'] == ADMIN)
	{
		$module['contact']['contact'] = $filename . "?mode=contact";
	}
	if ($userdata['auth_joinus'] || $userdata['user_level'] == ADMIN)
	{
		$module['contact']['contact_joinus'] = $filename . "?mode=joinus";
	}
	if ($userdata['auth_fightus'] || $userdata['user_level'] == ADMIN)
	{
		$module['contact']['contact_fightus'] = $filename . "?mode=fightus";
	}

	return;
}
else
{
	
	define('IN_CMS', 1);

	$root_path = './../';
	$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;
	$no_page_header = $cancel;
	require('./pagestart.php');
	
	include($root_path . 'includes/functions_admin.php');
	include($root_path . 'includes/functions_selects.php');
	
	
	
	if ($cancel)
	{
		redirect('admin/' . append_sid("admin_contact.php", true));
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	
	if ( isset($HTTP_POST_VARS[POST_CONTACT_URL]) || isset($HTTP_GET_VARS[POST_CONTACT_URL]) )
	{
		$contact_id = ( isset($HTTP_POST_VARS[POST_CONTACT_URL]) ) ? intval($HTTP_POST_VARS[POST_CONTACT_URL]) : intval($HTTP_GET_VARS[POST_CONTACT_URL]);
	}
	else
	{
		$contact_id = 0;
	}
	
	if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
		$mode = htmlspecialchars($mode);
	}
	else
	{
		$mode = '';
	}
	
	if ( $mode == 'contact' && !$userdata['auth_contact'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail'] . '1');
	}
	
	if ( $mode == 'joinus' && (!$userdata['auth_joinus'] && $userdata['user_level'] != ADMIN))
	{
		message_die(GENERAL_ERROR, $lang['auth_fail'] . '2');
	}
	
	if ( $mode == 'fightus' && !$userdata['auth_fightus'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_ERROR, $lang['auth_fail'] . '3');
	}
	
	function _select_type($default)
	{
		global $lang;
		
		$type = array (
			$lang['select_type']	=> '0',
			$lang['select_type1']	=> '1',
			$lang['select_type2']	=> '2',
			$lang['select_type3']	=> '3',
			$lang['select_type4']	=> '4',
			$lang['select_type5']	=> '5',
			$lang['select_type6']	=> '6'
		);
		
		$select_type = '';
		$select_type .= '<select name="contact_type" class="post">';
		foreach ($type as $typ => $valve)
		{
			$selected = ( $valve == $default ) ? ' selected="selected"' : '';
			$select_type .= '<option value="' . $valve . '" ' . $selected . '>&raquo; ' . $typ . '&nbsp;</option>';
		}
		$select_type .= '</select>';
		
		return $select_type;	
	}
	
	
	
	function _select_league($default)
	{
		global $lang;
		
		$league = array (
			$lang['select_league']	=> '0',
			$lang['select_league1']	=> '1',	//	http://www.esl.eu/
			$lang['select_league2']	=> '2',	//	http://www.stammkneipe.de/
			$lang['select_league3']	=> '3',	//	http://www.0815liga.de/
			$lang['select_league4']	=> '4',	//	http://www.lgz.de/
			$lang['select_league5']	=> '5',	//	http://www.tactical-esports.de/
			$lang['select_league6']	=> '6',	//	http://www.xgc-online.de/
			$lang['select_league7']	=> '7',	//	http://www.ncsl.de/
			$lang['select_league8']	=> '8'
		);
		
		$select_league = '';
		$select_league .= '<select name="contact_league" class="post">';
		foreach ($league as $ligs => $valve)
		{
			$selected = ( $valve == $default ) ? ' selected="selected"' : '';
			$select_league .= '<option value="' . $valve . '" ' . $selected . '>&raquo; ' . $ligs . '&nbsp;</option>';
		}
		$select_league .= '</select>';
		
		return $select_league;	
	}
	
	function _select_categorie($default)
	{
		global $lang;
		
		$league = array (
			$lang['select_categorie']	=> '0',
			$lang['select_categorie1']	=> '1',
			$lang['select_categorie2']	=> '2',
			$lang['select_categorie3']	=> '3',
			$lang['select_categorie4']	=> '4'
		);
		
		$select_categorie = '';
		$select_categorie .= '<select name="contact_categorie" class="post">';
		foreach ($league as $liga => $valve)
		{
			$selected = ( $valve == $default ) ? ' selected="selected"' : '';
			$select_categorie .= '<option value="' . $valve . '" ' . $selected . '>&raquo; ' . $liga . '&nbsp;</option>';
		}
		$select_categorie .= '</select>';
		
		return $select_categorie;	
	}
	
	if( !empty($mode) ) 
	{
		switch($mode)
		{
			case 'add':
			case 'edit':
			
				$template->set_filenames(array('body' => './../admin/style/contact_edit_body.tpl'));
				
				if ( $mode == 'edit' )
				{
					$sql = 'SELECT t.team_id, t.team_name, m.* FROM ' . CONTACT_TABLE . ' m, ' . TEAMS_TABLE . ' t WHERE t.team_id = m.team_id AND m.contact_id = ' . $contact_id;
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Error getting contact information', '', __LINE__, __FILE__, $sql);
					}
			
					if (!($contact = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['contact_not_exist']);
					}
			
					$new_mode = 'editcontact';
				}
				else if ( $mode == 'add' )
				{
					$contact = array (
						'team_id'			=> trim($HTTP_POST_VARS['team_id']),
						'contact_type'		=> '',
						'contact_league'		=> '',
						'contact_league_url'	=> '',
						'contact_date'		=> time(),
						'contact_categorie'	=> '0',
						'contact_public'		=> '1',
						'contact_comments'	=> $settings['comments_contactes'],
						'contact_rival'		=> '',
						'contact_rival_tag'	=> '',
						'contact_rival_url'	=> '',
						'server'			=> '',
						'server_pw'			=> '',
						'server_hltv'		=> '',
						'server_hltv_pw'	=> '',
						'contact_create'		=> '',
						'contact_update'		=> ''
					);
					
					$template->assign_block_vars('new_contact', array());
					
					$new_mode = 'addcontact';
				}
				
				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $new_mode . '" />';
				$s_hidden_fields .= '<input type="hidden" name="' . POST_CONTACT_URL . '" value="' . $contact_id . '" />';
				
				$template->assign_vars(array(
					'L_CONTACT_TITLE'			=> $lang['contact_head'],
					'L_CONTACT_NEW_EDIT'		=> ($mode == 'add') ? $lang['contact_new_add'] : $lang['contact_edit'],
					'L_REQUIRED'			=> $lang['required'],
					
					'L_CONTACT_NAME'			=> $lang['contact_name'],
					'L_CONTACT_DESCRIPTION'	=> $lang['contact_description'],
					'L_CONTACT_GAME'			=> $lang['contact_game'],
					
					'L_CONTACT_LOGO_UP'		=> $lang['contact_picture_upload'],
					'L_CONTACT_LOGO_LINK'		=> $lang['contact_picture_link'],
					'L_UPLOAD_LOGO'			=> $lang['picture_upload'],
					'L_CONTACT_LOGOS_UP'		=> $lang['contact_pictures_upload'],
					'L_CONTACT_LOGOS_LINK'	=> $lang['contact_pictures_link'],
					'L_UPLOAD_LOGOS'		=> $lang['pictures_upload'],
					
					'L_CONTACT_NAVI'			=> $lang['contact_navi'],
					'L_CONTACT_SACONTACTDS'		=> $lang['contact_sacontactds'],
					'L_CONTACT_SFIGHT'		=> $lang['contact_sfight'],
					'L_CONTACT_JOIN'			=> $lang['contact_join'],
					'L_CONTACT_FIGHT'			=> $lang['contact_fight'],
					'L_CONTACT_VIEW'			=> $lang['contact_view'],
					'L_CONTACT_SHOW'			=> $lang['contact_show'],
					
					'L_CONTACT_INFOS'			=> $lang['contact_infos'],
					'L_LOGO_SETTINGS'		=> $lang['contact_picture_setting'],
					'L_MENU_SETTINGS'		=> $lang['contact_menu_setting'],
					
					'L_SUBMIT'				=> $lang['Submit'],
					'L_RESET'				=> $lang['Reset'],
					'L_YES'					=> $lang['Yes'],
					'L_NO'					=> $lang['No'],
					
					'LEAGUE_URL'			=> $contact['contact_league_url'],
					'LEAGUE_CONTACT'			=> $contact['contact_league_contact'],
					'CONTACT_RIVAL'			=> $contact['contact_rival'],
					'CONTACT_RIVAL_TAG'		=> $contact['contact_rival_tag'],
					'CONTACT_RIVAL_URL'		=> $contact['contact_rival_url'],
					'SERVER'				=> $contact['server'],
					'SERVER_PW'				=> $contact['server_pw'],
					'SERVER_HLTV'			=> $contact['server_hltv'],
					'SERVER_HLTV_PW'		=> $contact['server_hltv_pw'],
					
					'S_TEAMS'				=> _select_team($contact['team_id'], 0, 'post'),
					'S_TYPE'				=> _select_type($contact['contact_type']),
					'S_LEAGUE'				=> _select_league($contact['contact_league']),
					'S_DAY'					=> _select_date('day', 'day',		date('d', $contact['contact_date'])),
					'S_MONTH'				=> _select_date('month', 'month',	date('m', $contact['contact_date'])),
					'S_YEAR'				=> _select_date('year', 'year',		date('Y', $contact['contact_date'])),
					'S_HOUR'				=> _select_date('hour', 'hour',		date('H', $contact['contact_date'])),
					'S_MIN'					=> _select_date('min', 'min',		date('i', $contact['contact_date'])),
					

					'S_CATEGORIE'			=> _select_categorie($contact['contact_categorie']),
					
					'S_CHECKED_PUB_NO'		=> (!$contact['contact_public']) ? ' checked="checked"' : '',
					'S_CHECKED_PUB_YES'		=> ( $contact['contact_public']) ? ' checked="checked"' : '',
					'S_CHECKED_COM_NO'		=> (!$contact['contact_comments']) ? ' checked="checked"' : '',
					'S_CHECKED_COM_YES'		=> ( $contact['contact_comments']) ? ' checked="checked"' : '',
					
					'S_TDAY'				=> _select_date('day', 'tday',		date('d', time()-86400)),
					'S_TMONTH'				=> _select_date('month', 'tmonth',	date('m', time())),
					'S_TYEAR'				=> _select_date('year', 'tyear',	date('Y', time())),
					'S_THOUR'				=> _select_date('hour', 'thour',	date('H', time()-7200)),
					'S_TMIN'				=> _select_date('min', 'tmin',		date('i', time())),
					
					'S_TDURATION'			=> _select_date('duration', 'dmin',		date('i', time())),

					'S_CONTACT_ACTION'		=> append_sid("admin_contact.php"),
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields
				));
			
				$template->pparse('body');
				
			break;
			
			case 'addcontact':
			
				$error = ''; 
				$error_msg = '';
			
				if ( intval($HTTP_POST_VARS['team_id']) == '0' )
				{
					$error = true;
					$error_msg = $lang['select_contact_team'];
				}
				
				if ( intval($HTTP_POST_VARS['contact_type']) == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['select_contact_type'];
				}
				
				if ( intval($HTTP_POST_VARS['contact_categorie']) == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['select_contact_cate'];
				}
				
				if ( intval($HTTP_POST_VARS['contact_league']) == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['select_contact_league'];
				}
				
				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . 'date';
				}
				
				if ($error)
				{
					message_die(GENERAL_ERROR, $error_msg, '');
				}
								
				$contact_date = mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				
				$sql = "INSERT INTO " . CONTACT_TABLE . " (team_id, contact_type, contact_league, contact_league_url, contact_league_contact, contact_date, contact_categorie, contact_public, contact_comments, contact_rival, contact_rival_tag, contact_rival_url, server, server_pw, server_hltv, server_hltv_pw, contact_create)
					VALUES ('" . intval($HTTP_POST_VARS['team_id']) . "',
								'" . intval($HTTP_POST_VARS['contact_type']) . "',
								'" . intval($HTTP_POST_VARS['contact_league']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['contact_league_url']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['contact_league_contact']) . "',
								$contact_date,
								'" . intval($HTTP_POST_VARS['contact_categorie']) . "',
								'" . intval($HTTP_POST_VARS['contact_public']) . "',
								'" . intval($HTTP_POST_VARS['contact_comments']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['contact_rival']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['contact_rival_tag']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['contact_rival_url']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['server']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['server_pw']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['server_hltv']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['server_hltv_pw']) . "',
								'" . time() . "')";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not insert row in contact table', '', __LINE__, __FILE__, $sql);
				}
				
				$contact_id = $db->sql_nextid();
				
				$sql = "INSERT INTO " . CONTACT_DETAILS_TABLE . " (contact_id, details_create) VALUES ($contact_id, " . time() . ")";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not insert row in contact table', '', __LINE__, __FILE__, $sql);
				}
				
				if (!empty($HTTP_POST_VARS['train']))
				{
					$training_start		= mktime($HTTP_POST_VARS['thour'], $HTTP_POST_VARS['tmin'], 00, $HTTP_POST_VARS['tmonth'], $HTTP_POST_VARS['tday'], $HTTP_POST_VARS['tyear']);
					$training_duration	= mktime($HTTP_POST_VARS['thour'], $HTTP_POST_VARS['tmin'] + $HTTP_POST_VARS['dmin'], 00, $HTTP_POST_VARS['tmonth'], $HTTP_POST_VARS['tday'], $HTTP_POST_VARS['tyear']);
					
					$sql = "INSERT INTO " . TRAINING_TABLE . " (training_vs, team_id, contact_id, training_start, training_duration, training_create, training_maps, training_comment)
						VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['contact_rival']) . "',
								'" . intval($HTTP_POST_VARS['team_id']) . "',
								$contact_id,
								$training_start,
								$training_duration,
								" . time() . ",
								'" . str_replace("\'", "''", $HTTP_POST_VARS['training_maps']) . "',
								'" . str_replace("\'", "''", $HTTP_POST_VARS['training_comment']) . "')";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not insert row in contact table', '', __LINE__, __FILE__, $sql);
					}
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CONTACT, ACP_CONTACT_ADD, str_replace("\'", "''", $HTTP_POST_VARS['contact_name']));
	
				$message = $lang['contact_create'] . '<br /><br />' . sprintf($lang['click_return_contact'], "<a href=\"" . append_sid("admin_contact.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);

			break;
			
			case 'editcontact':
			
				$error = ''; 
				$error_msg = '';
			
				if ( intval($HTTP_POST_VARS['team_id']) == '0' )
				{
					$error = true;
					$error_msg = $lang['select_contact_team'];
				}
				
				if ( intval($HTTP_POST_VARS['contact_type']) == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['select_contact_type'];
				}
				
				if ( intval($HTTP_POST_VARS['contact_categorie']) == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['select_contact_cate'];
				}
				
				if ( intval($HTTP_POST_VARS['contact_league']) == '0' )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['select_contact_league'];
				}
				
				if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
				{
					$error = true;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . 'date';
				}
				
				if ($error)
				{
					message_die(GENERAL_ERROR, $error_msg, '');
				}
				
				$contact_date = mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']);
				
				$sql = "UPDATE " . CONTACT_TABLE . " SET
							team_id				= '" . intval($HTTP_POST_VARS['team_id']) . "',
							contact_type			= '" . intval($HTTP_POST_VARS['contact_type']) . "',
							contact_league		= '" . intval($HTTP_POST_VARS['contact_league']) . "',
							contact_league_url	= '" . str_replace("\'", "''", $HTTP_POST_VARS['contact_league_url']) . "',
							contact_league_contact	= '" . str_replace("\'", "''", $HTTP_POST_VARS['contact_league_contact']) . "',
							contact_date			= $contact_date,
							contact_categorie		= '" . intval($HTTP_POST_VARS['contact_categorie']) . "',
							contact_public		= '" . intval($HTTP_POST_VARS['contact_public']) . "',
							contact_comments		= '" . intval($HTTP_POST_VARS['contact_comments']) . "',
							contact_rival			= '" . str_replace("\'", "''", $HTTP_POST_VARS['contact_rival']) . "',
							contact_rival_tag		= '" . str_replace("\'", "''", $HTTP_POST_VARS['contact_rival_tag']) . "',
							contact_rival_url		= '" . str_replace("\'", "''", $HTTP_POST_VARS['contact_rival_url']) . "',
							server				= '" . str_replace("\'", "''", $HTTP_POST_VARS['server']) . "',
							server_pw			= '" . str_replace("\'", "''", $HTTP_POST_VARS['server_pw']) . "',
							server_hltv			= '" . str_replace("\'", "''", $HTTP_POST_VARS['server_hltv']) . "',
							server_hltv_pw		= '" . str_replace("\'", "''", $HTTP_POST_VARS['server_hltv_pw']) . "',
							contact_update		= '" . time() . "'
						WHERE contact_id = $contact_id";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update contact information', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CONTACT, ACP_CONTACT_EDIT, $log_data);
				
				$message = $lang['contact_update'] . '<br /><br />' . sprintf($lang['click_return_contact'], "<a href=\"" . append_sid("admin_contact.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
	
			break;
			
			case 'delete':
			
				$confirm = isset($HTTP_POST_VARS['confirm']);
				
				if ( $contact_id && $confirm )
				{
					$sql = 'SELECT * FROM ' . CONTACT_TABLE . " WHERE contact_id = $contact_id";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Error getting contact information', '', __LINE__, __FILE__, $sql);
					}
			
					if (!($contact_info = $db->sql_fetchrow($result)))
					{
						message_die(GENERAL_MESSAGE, $lang['contact_not_exist']);
					}
					
					$picture_file	= $contact_info['contact_picture'];
					$picture_type	= $contact_info['contact_picture_type'];
					$pictures_file	= $contact_info['contact_pictures'];
					$pictures_type	= $contact_info['contact_pictures_type'];
					
					$picture_file = basename($picture_file);
					$pictures_file = basename($pictures_file);
					
					if ( $picture_type == LOGO_UPLOAD && $picture_file != '' )
					{
						if ( @file_exists(@phpbb_realpath($root_path . $settings['contact_picture_path'] . '/' . $picture_file)) )
						{
							@unlink($root_path . $settings['contact_picture_path'] . '/' . $picture_file);
						}
					}
					
					if ( $pictures_type == LOGO_UPLOAD && $pictures_file != '' )
					{
						if ( @file_exists(@phpbb_realpath($root_path . $settings['contact_pictures_path'] . '/' . $pictures_file)) )
						{
							@unlink($root_path . $settings['contact_pictures_path'] . '/' . $pictures_file);
						}
					}
				
					$sql = 'DELETE FROM ' . CONTACT_TABLE . " WHERE contact_id = $contact_id";
					if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
					{
						message_die(GENERAL_ERROR, 'Could not delete contact', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . CONTACT_COMMENTS_TABLE . " WHERE contact_id = $contact_id";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete contact', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . CONTACT_DETAILS_TABLE . " WHERE contact_id = $contact_id";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete contact', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . CONTACT_LINEUP_TABLE . " WHERE contact_id = $contact_id";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete contact', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = 'DELETE FROM ' . CONTACT_USERS_TABLE . " WHERE contact_id = $contact_id";
					if (!($result = $db->sql_query($sql, END_TRANSACTION)))
					{
						message_die(GENERAL_ERROR, 'Could not delete contact', '', __LINE__, __FILE__, $sql);
					}

					_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CONTACT, ACP_CONTACT_DELETE, $contact_info['contact_name']);
					
					$message = $lang['contact_delete'] . '<br /><br />' . sprintf($lang['click_return_contact'], "<a href=\"" . append_sid("admin_contact.php") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
		
				}
				else if ( $contact_id && !$confirm)
				{
					$template->set_filenames(array('body' => './../admin/style/confirm_body.tpl'));
		
					$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="' . POST_CONTACT_URL . '" value="' . $contact_id . '" />';
		
					$template->assign_vars(array(
						'MESSAGE_TITLE'		=> $lang['confirm'],
						'MESSAGE_TEXT'		=> $lang['confirm_delete_contact'],
		
						'L_YES'				=> $lang['Yes'],
						'L_NO'				=> $lang['No'],
		
						'S_CONFIRM_ACTION'	=> append_sid("admin_contact.php"),
						'S_HIDDEN_FIELDS'	=> $hidden_fields
					));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['Must_select_contact']);
				}
			
				$template->pparse("body");
				
			break;
				
			case 'details':
			
				$template->set_filenames(array('body' => './../admin/style/contact_details_body.tpl'));
				
				//	alle Infos
//			$sql = 'SELECT	m.contact_type, m.contact_league, m.contact_categorie, m.contact_rival, m.contact_rival_tag, m.contact_rival_url, m.server, m.server_pw, m.server_hltv, m.server_hltv_pw,
//								md.*,
//								t.team_id, t.team_name,
//								g.game_image
//							FROM ' . CONTACT_TABLE . ' m, ' . CONTACT_DETAILS_TABLE . ' md, ' . TEAMS_TABLE . ' t, ' . GAMES_TABLE . " g
//						WHERE m.contact_id = md.contact_id AND m.team_id = t.team_id AND t.team_game = g.game_id AND m.contact_id = $contact_id";
						
				$sql = 'SELECT	m.*,
								md.*,
								t.team_id, t.team_name,
								g.game_image,
								tr.training_vs, tr.training_start
							FROM ' . CONTACT_TABLE . ' m
						LEFT JOIN ' . CONTACT_DETAILS_TABLE . ' md ON m.contact_id = md.contact_id
						LEFT JOIN ' . TEAMS_TABLE . ' t ON m.team_id = t.team_id
						LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
						LEFT JOIN ' . TRAINING_TABLE . ' tr ON tr.contact_id = m.contact_id
						WHERE ' . $contact_id . ' = m.contact_id';
				
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
				}

				if (!($row = $db->sql_fetchrow($result)))
				{
					message_die(GENERAL_MESSAGE, '', '', __LINE__, __FILE__);
				}
				
				$picture_path = $root_path . $settings['contact_picture_path'];
				
				$map_pic_a	= $row['details_map_pic_a'];
				$map_pic_b	= $row['details_map_pic_b'];
				$map_pic_c	= $row['details_map_pic_c'];
				$map_pic_d	= $row['details_map_pic_d'];
				$map_pic_e	= $row['details_map_pic_e'];
				$map_pic_f	= $row['details_map_pic_f'];
				$map_pic_g	= $row['details_map_pic_g'];
				$map_pic_h	= $row['details_map_pic_h'];
				
				$pic_a	= $row['pic_a_preview'];
				$pic_b	= $row['pic_b_preview'];
				$pic_c	= $row['pic_c_preview'];
				$pic_d	= $row['pic_d_preview'];
				$pic_e	= $row['pic_e_preview'];
				$pic_f	= $row['pic_f_preview'];
				$pic_g	= $row['pic_g_preview'];
				$pic_h	= $row['pic_h_preview'];
				
				$pic_a	= ( $map_pic_a ) ? '<a href="' . $picture_path . '/' . $map_pic_a . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_a . '" alt="" border="" /></a>' : '';
				$pic_b	= ( $map_pic_b ) ? '<a href="' . $picture_path . '/' . $map_pic_b . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_b . '" alt="" border="" /></a>' : '';
				$pic_c	= ( $map_pic_c ) ? '<a href="' . $picture_path . '/' . $map_pic_c . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_c . '" alt="" border="" /></a>' : '';
				$pic_d	= ( $map_pic_d ) ? '<a href="' . $picture_path . '/' . $map_pic_d . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_d . '" alt="" border="" /></a>' : '';
				$pic_e	= ( $map_pic_e ) ? '<a href="' . $picture_path . '/' . $map_pic_e . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_e . '" alt="" border="" /></a>' : '';
				$pic_f	= ( $map_pic_f ) ? '<a href="' . $picture_path . '/' . $map_pic_f . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_f . '" alt="" border="" /></a>' : '';
				$pic_g	= ( $map_pic_g ) ? '<a href="' . $picture_path . '/' . $map_pic_g . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_g . '" alt="" border="" /></a>' : '';
				$pic_h	= ( $map_pic_h ) ? '<a href="' . $picture_path . '/' . $map_pic_h . '" rel="lightbox"><img src="' . $picture_path . '/' . $pic_h . '" alt="" border="" /></a>' : '';
				
				//	Lineup + Ersatz
				$sql = 'SELECT u.user_id, u.username, ml.status
						FROM ' . CONTACT_LINEUP_TABLE . ' ml, ' . USERS_TABLE . ' u
						WHERE ml.contact_id = ' . $contact_id . ' AND ml.user_id = u.user_id
						ORDER BY ml.status';
				if (!($result_users = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sql);
				}
				
				while ( $users = $db->sql_fetchrow($result_users) )
				{
					$class = ($color % 2) ? 'row_class1' : 'row_class2';
					$color++;

					$template->assign_block_vars('members_row', array(
						'CLASS' 		=> $class,
						'USER_ID'		=> $users['user_id'],
						'USERNAME'		=> $users['username'],
						'STATUS'		=> (!$users['status']) ? $lang['contact_player'] : $lang['contact_replace']
					));
				}
				
				if (!$db->sql_numrows($result_users))
				{
					$template->assign_block_vars('no_members_row', array());
					$template->assign_vars(array('NO_TEAMS' => $lang['member_empty']));
				}
				//	Lineup

				
				//	Add Member aus Liste
				$sql = 'SELECT u.user_id, u.username
						FROM ' . USERS_TABLE . ' u, ' . TEAMS_USERS_TABLE . ' tu
						WHERE tu.team_id = ' . $row['team_id'] . ' AND tu.user_id = u.user_id
						ORDER BY u.username';
				if (!($result_addusers = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not query table', '', __LINE__, __FILE__, $sqla);
				}

				$s_addusers_select = '<select class="post" name="members[]" rows="5" multiple>';
				while ($addusers = $db->sql_fetchrow($result_addusers))
				{
					$s_addusers_select .= '<option value="' . $addusers['user_id'] . '">' . $addusers['username'] . '&nbsp;</option>';
				}
				$s_addusers_select .= '</select>';
				
				//	Lineup
				
				//	Dropdown
				$s_action_options = '';
				$s_action_options .= '<select class="postselect" name="mode">';
				$s_action_options .= '<option value="option">&raquo; ' . $lang['option_select'] . '</option>';
				$s_action_options .= '<option value="player">&raquo; ' . sprintf($lang['status_set'], $lang['contact_player']) . '&nbsp;</option>';
				$s_action_options .= '<option value="replace">&raquo; ' . sprintf($lang['status_set'], $lang['contact_replace']) . '&nbsp;</option>';
				$s_action_options .= '<option value="deluser">&raquo; ' . $lang['delete'] . '</option>';
				$s_action_options .= '</select>';
				
				switch ($row['contact_categorie'])
				{
					case '1':
						$contact_categorie = $lang['select_categorie1'];
					break;
					case '2':
						$contact_categorie = $lang['select_categorie2'];
					break;
					case '3':
						$contact_categorie = $lang['select_categorie3'];
					break;
					case '4':
						$contact_categorie = $lang['select_categorie4'];
					break;
					case '5':
						$contact_categorie = $lang['select_categorie5'];
					break;
				}
				
				switch ($row['contact_type'])
				{
					case '1':
						$contact_type = $lang['select_type1'];
					break;
					case '2':
						$contact_type = $lang['select_type2'];
					break;
					case '3':
						$contact_type = $lang['select_type3'];
					break;
					case '4':
						$contact_type = $lang['select_type4'];
					break;
					case '5':
						$contact_type = $lang['select_type5'];
					break;
					case '6':
						$contact_type = $lang['select_type6'];
					break;
				}
				
				switch ($row['contact_league'])
				{
					case '1':
						$contact_league = '<a href="' . $lang['select_league1i'] . '">' . $lang['select_league1'] . '</a>';
					break;
					case '2':
						$contact_league = '<a href="' . $lang['select_league2i'] . '">' . $lang['select_league2'] . '</a>';
					break;
					case '3':
						$contact_league = '<a href="' . $lang['select_league3i'] . '">' . $lang['select_league3'] . '</a>';
					break;
					case '4':
						$contact_league = '<a href="' . $lang['select_league4i'] . '">' . $lang['select_league4'] . '</a>';
					break;
					case '5':
						$contact_league = '<a href="' . $lang['select_league5i'] . '">' . $lang['select_league5'] . '</a>';
					break;
					case '6':
						$contact_league = '<a href="' . $lang['select_league6i'] . '">' . $lang['select_league6'] . '</a>';
					break;
					case '7':
						$contact_league = '<a href="' . $lang['select_league7i'] . '">' . $lang['select_league7'] . '</a>';
					break;
					case '8':
						$contact_league = $lang['select_league8'];
					break;
				}
				
				if ( $map_pic_a ) { $template->assign_block_vars('pictureadel', array()); }
				if ( $map_pic_b ) { $template->assign_block_vars('picturebdel', array()); }
				if ( $map_pic_c ) { $template->assign_block_vars('picturecdel', array()); }
				if ( $map_pic_d ) { $template->assign_block_vars('pictureddel', array()); }
				if ( $map_pic_e ) { $template->assign_block_vars('pictureedel', array()); }
				if ( $map_pic_f ) { $template->assign_block_vars('picturefdel', array()); }
				if ( $map_pic_g ) { $template->assign_block_vars('picturegdel', array()); }
				if ( $map_pic_h ) { $template->assign_block_vars('picturehdel', array()); }
				
				if ( $row['server_hltv'] ) { $template->assign_block_vars('hltv', array()); }
					
				$s_hidden_fields = '<input type="hidden" name="' . POST_CONTACT_URL . '" value="' . $contact_id . '" />';
				
				$s_hidden_fielda = '<input type="hidden" name="mode" value="lineup" />';
				$s_hidden_fieldb = '<input type="hidden" name="mode" value="update" />';
				$s_hidden_fieldc = '<input type="hidden" name="mode" value="adduser" />';
				
				$template->assign_vars(array(
					'L_CONTACT_TITLE'			=> $lang['contact_head'],
					'L_TEAM_DETAILS'		=> $lang['contact_head'],
					'L_TEAM_EXPLAIN'		=> $lang['contact_head'],
					'L_CONTACT_INFO'			=> $lang['contact_head'],
					'L_RIVAL'				=> $lang['contact_head'],
					'L_RIVAL_TAG'			=> $lang['contact_head'],
					'L_CONTACT_DETAILS'		=> $lang['contact_head'],
					'L_SERVER'				=> $lang['contact_head'],
					'L_HLTV'				=> $lang['contact_head'],
					
					'L_CONTACT_LINEUP'		=> $lang['contact_head'],
					'L_CONTACT_STATUS'		=> $lang['contact_head'],
					'L_CONTACT_LINUP_ADD'		=> $lang['contact_head'],
					'L_CONTACT_LINUP_ADD_EX'	=> $lang[''],
							
					
					'L_USERNAME'			=> $lang['username'],
					'L_MARK_ALL'			=> $lang['mark_all'],
					'L_MARK_DEALL'			=> $lang['mark_deall'],
					'L_SUBMIT'				=> $lang['Submit'],
					
					'CONTACT_RIVAL'			=> $row['contact_rival'],
					'U_CONTACT_RIVAL_URL'		=> $row['contact_rival_url'],
					'CONTACT_RIVAL_URL'		=> $row['contact_rival_url'],
					'CONTACT_RIVAL_TAG'		=> $row['contact_rival_tag'],
					

					'CONTACT_CATEGORIE'		=> $contact_categorie,
					'CONTACT_TYPE'			=> $contact_type,
					'CONTACT_LEAGUE_INFO'		=> $contact_league,
					'SERVER'				=> $row['server'],
					'SERVER_PW'				=> $row['server_pw'],
					'HLTV'					=> $row['server_hltv'],
					'HLTV_PW'				=> $row['server_hltv_pw'],
					
					'MAPC'					=> ($row['details_mapc']) ? '' : 'none',
					'MAPD'					=> ($row['details_mapd']) ? '' : 'none',
					
					'DETAILS_LINEUP_RIVAL'	=> $row['details_lineup_rival'],
					
					'DETAILS_MAPA'			=> $row['details_mapa'],
					'DETAILS_MAPB'			=> $row['details_mapb'],
					'DETAILS_MAPC'			=> $row['details_mapc'],
					'DETAILS_MAPD'			=> $row['details_mapd'],
					'DETAILS_MAPA_CLAN'		=> $row['details_mapa_clan'],
					'DETAILS_MAPB_CLAN'		=> $row['details_mapb_clan'],
					'DETAILS_MAPC_CLAN'		=> $row['details_mapc_clan'],
					'DETAILS_MAPD_CLAN'		=> $row['details_mapd_clan'],
					'DETAILS_MAPA_RIVAL'	=> $row['details_mapa_rival'],
					'DETAILS_MAPB_RIVAL'	=> $row['details_mapb_rival'],
					'DETAILS_MAPC_RIVAL'	=> $row['details_mapc_rival'],
					'DETAILS_MAPD_RIVAL'	=> $row['details_mapd_rival'],
					
					'DETAILS_PIC_A'			=> $pic_a,
					'DETAILS_PIC_B'			=> $pic_b,
					'DETAILS_PIC_C'			=> $pic_c,
					'DETAILS_PIC_D'			=> $pic_d,
					'DETAILS_PIC_E'			=> $pic_e,
					'DETAILS_PIC_F'			=> $pic_f,
					'DETAILS_PIC_G'			=> $pic_g,
					'DETAILS_PIC_H'			=> $pic_h,
					
					'DETAILS_COMMENT'		=> $row['details_comment'],

					'S_ACTION_OPTIONS'		=> $s_action_options,
					
					'S_ADDUSERS'			=> $s_addusers_select,

					'S_HIDDEN_FIELDA'		=> $s_hidden_fielda,
					'S_HIDDEN_FIELDB'		=> $s_hidden_fieldb,
					'S_HIDDEN_FIELDC'		=> $s_hidden_fieldc,
					
					'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
					'S_CONTACT_ACTION'		=> append_sid("admin_contact.php"))
				);
			
				$template->pparse('body');
			
			break;
			
			case 'update':
			
				$sql = 'SELECT * FROM ' . CONTACT_DETAILS_TABLE . " WHERE contact_id = $contact_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Error getting team information', '', __LINE__, __FILE__, $sql);
				}
		
				if (!($contact_info = $db->sql_fetchrow($result)))
				{
					message_die(GENERAL_MESSAGE, 'contact_not_exist');
				}
			
				$picturea_upload		= ( $HTTP_POST_FILES['details_map_pic_a']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_a']['tmp_name'] : '';
				$picturea_name			= ( !empty($HTTP_POST_FILES['details_map_pic_a']['name']) ) ? $HTTP_POST_FILES['details_map_pic_a']['name'] : '';
				$picturea_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_a']['type']) ) ? $HTTP_POST_FILES['details_map_pic_a']['type'] : '';
				
				$pictureb_upload		= ( $HTTP_POST_FILES['details_map_pic_b']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_b']['tmp_name'] : '';
				$pictureb_name			= ( !empty($HTTP_POST_FILES['details_map_pic_b']['name']) ) ? $HTTP_POST_FILES['details_map_pic_b']['name'] : '';
				$pictureb_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_b']['type']) ) ? $HTTP_POST_FILES['details_map_pic_b']['type'] : '';
				
				$picturec_upload		= ( $HTTP_POST_FILES['details_map_pic_c']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_c']['tmp_name'] : '';
				$picturec_name			= ( !empty($HTTP_POST_FILES['details_map_pic_c']['name']) ) ? $HTTP_POST_FILES['details_map_pic_c']['name'] : '';
				$picturec_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_c']['type']) ) ? $HTTP_POST_FILES['details_map_pic_c']['type'] : '';
				
				$pictured_upload		= ( $HTTP_POST_FILES['details_map_pic_d']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_d']['tmp_name'] : '';
				$pictured_name			= ( !empty($HTTP_POST_FILES['details_map_pic_d']['name']) ) ? $HTTP_POST_FILES['details_map_pic_d']['name'] : '';
				$pictured_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_d']['type']) ) ? $HTTP_POST_FILES['details_map_pic_d']['type'] : '';
				
				$picturee_upload		= ( $HTTP_POST_FILES['details_map_pic_e']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_e']['tmp_name'] : '';
				$picturee_name			= ( !empty($HTTP_POST_FILES['details_map_pic_e']['name']) ) ? $HTTP_POST_FILES['details_map_pic_e']['name'] : '';
				$picturee_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_e']['type']) ) ? $HTTP_POST_FILES['details_map_pic_e']['type'] : '';
				
				$picturef_upload		= ( $HTTP_POST_FILES['details_map_pic_f']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_f']['tmp_name'] : '';
				$picturef_name			= ( !empty($HTTP_POST_FILES['details_map_pic_f']['name']) ) ? $HTTP_POST_FILES['details_map_pic_f']['name'] : '';
				$picturef_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_f']['type']) ) ? $HTTP_POST_FILES['details_map_pic_f']['type'] : '';
				
				$pictureg_upload		= ( $HTTP_POST_FILES['details_map_pic_g']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_g']['tmp_name'] : '';
				$pictureg_name			= ( !empty($HTTP_POST_FILES['details_map_pic_g']['name']) ) ? $HTTP_POST_FILES['details_map_pic_g']['name'] : '';
				$pictureg_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_g']['type']) ) ? $HTTP_POST_FILES['details_map_pic_g']['type'] : '';
				
				$pictureh_upload		= ( $HTTP_POST_FILES['details_map_pic_h']['tmp_name'] != "none") ? $HTTP_POST_FILES['details_map_pic_h']['tmp_name'] : '';
				$pictureh_name			= ( !empty($HTTP_POST_FILES['details_map_pic_d']['name']) ) ? $HTTP_POST_FILES['details_map_pic_h']['name'] : '';
				$pictureh_filetype		= ( !empty($HTTP_POST_FILES['details_map_pic_h']['type']) ) ? $HTTP_POST_FILES['details_map_pic_h']['type'] : '';
				
				$picturea_sql = '';
				$pictureb_sql = '';
				$picturec_sql = '';
				$pictured_sql = '';
				$picturee_sql = '';
				$picturef_sql = '';
				$pictureg_sql = '';
				$pictureh_sql = '';
				
				if ( isset($HTTP_POST_VARS['pictureadel']) )
				{
					$picturea_sql = picture_delete('a', $contact_info['details_map_pic_a'], $contact_info['pic_a_preview']);
				}
				else if (!empty($picturea_upload))
				{
					$picturea_sql = picture_upload('a', $contact_info['details_map_pic_a'], $contact_info['pic_a_preview'], $picturea_upload, $picturea_name, $picturea_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturebdel']) )
				{
					$pictureb_sql = picture_delete('b', $contact_info['details_map_pic_b'], $contact_info['pic_b_preview']);
				}
				else if (!empty($pictureb_upload))
				{
					$pictureb_sql = picture_upload('b', $contact_info['details_map_pic_b'], $contact_info['pic_b_preview'], $pictureb_upload, $pictureb_name, $pictureb_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturecdel']) )
				{
					$picturec_sql = picture_delete('c', $contact_info['details_map_pic_c'], $contact_info['pic_c_preview']);
				}
				else if (!empty($picturec_upload))
				{
					$picturec_sql = picture_upload('c', $contact_info['details_map_pic_c'], $contact_info['pic_c_preview'], $picturec_upload, $picturec_name, $picturec_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['pictureddel']) )
				{
					$pictured_sql = picture_delete('d', $contact_info['details_map_pic_d'], $contact_info['pic_d_preview']);
				}
				else if (!empty($pictured_upload))
				{
					$pictured_sql = picture_upload('d', $contact_info['details_map_pic_d'], $contact_info['pic_d_preview'], $pictured_upload, $pictured_name, $pictured_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['pictureedel']) )
				{
					$picturee_sql = picture_delete('e', $contact_info['details_map_pic_e'], $contact_info['pic_e_preview']);
				}
				else if (!empty($picturee_upload))
				{
					$picturee_sql = picture_upload('e', $contact_info['details_map_pic_e'], $contact_info['pic_e_preview'], $picturee_upload, $picturee_name, $picturee_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturefdel']) )
				{
					$picturef_sql = picture_delete('f', $contact_info['details_map_pic_f'], $contact_info['pic_f_preview']);
				}
				else if (!empty($picturef_upload))
				{
					$picturef_sql = picture_upload('f', $contact_info['details_map_pic_f'], $contact_info['pic_f_preview'], $picturef_upload, $picturef_name, $picturef_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturegdel']) )
				{
					$pictureg_sql = picture_delete('g', $contact_info['details_map_pic_g'], $contact_info['pic_g_preview']);
				}
				else if (!empty($pictureg_upload))
				{
					$pictureg_sql = picture_upload('g', $contact_info['details_map_pic_g'], $contact_info['pic_g_preview'], $pictureg_upload, $pictureg_name, $pictureg_filetype);
				}
				
				if ( isset($HTTP_POST_VARS['picturehdel']) )
				{
					$pictureh_sql = picture_delete('h', $contact_info['details_map_pic_h'], $contact_info['pic_h_preview']);
				}
				else if (!empty($pictureh_upload))
				{
					$pictureh_sql = picture_upload('h', $contact_info['details_map_pic_h'], $contact_info['pic_h_preview'], $pictureh_upload, $pictureh_name, $pictureh_filetype);
				}
				
				$sql = "UPDATE " . CONTACT_DETAILS_TABLE . " SET
							details_lineup_rival	= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_lineup_rival']) . "',
							details_mapa_clan		= '" . intval($HTTP_POST_VARS['details_mapa_clan']) . "',
							details_mapa_rival		= '" . intval($HTTP_POST_VARS['details_mapa_rival']) . "',
							details_mapb_clan		= '" . intval($HTTP_POST_VARS['details_mapb_clan']) . "',
							details_mapb_rival		= '" . intval($HTTP_POST_VARS['details_mapb_rival']) . "',
							details_mapc_clan		= '" . intval($HTTP_POST_VARS['details_mapc_clan']) . "',
							details_mapc_rival		= '" . intval($HTTP_POST_VARS['details_mapc_rival']) . "',
							details_mapd_clan		= '" . intval($HTTP_POST_VARS['details_mapd_clan']) . "',

							details_mapd_rival		= '" . intval($HTTP_POST_VARS['details_mapd_rival']) . "',
							details_mapa			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_mapa']) . "',
							details_mapb			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_mapb']) . "',
							details_mapc			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_mapc']) . "',
							details_mapd			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_mapd']) . "',
							$picturea_sql
							$pictureb_sql
							$picturec_sql
							$pictured_sql
							$picturee_sql
							$picturef_sql
							$pictureg_sql
							$pictureh_sql
							details_comment			= '" . str_replace("\'", "''", $HTTP_POST_VARS['details_comment']) . "',
							details_update			= " . time() . "
						WHERE contact_id = $contact_id";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update team information', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CONTACT, ACP_CONTACT_EDIT, $log_data);
				
				$message = $lang['team_update'] . '<br /><br />' . sprintf($lang['click_return_contact'], "<a href=\"" . append_sid("admin_contact.php") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
				
			break;
			
			case 'player':
			case 'replace':
			
				$member = $_POST['members'];
				$status = ($mode == 'player') ? '0' : '1';
			
				// If we have no users
				if (!sizeof($member))
				{
					message_die(GENERAL_ERROR, $lang['contact_no_select']);
				}
				
				$members = $member;
				
				$i	= 0;
				$g	= 0;
				$e	= 1;
				
				while ($i < count($members))
				{
					$member = array_slice($members, $g, $e);
					$member = current($member);
					
					$sql = "UPDATE " . CONTACT_LINEUP_TABLE . " SET status = $status WHERE contact_id = $contact_id AND user_id = $member";
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not add contact member', '', __LINE__, __FILE__, $sql);
					}
					
					$i++; $g--;
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CONTACT, ACP_CONTACT_ORDER, $members);
				
				$message = $lang['contact_change_member'] . '<br /><br />' . sprintf($lang['click_return_contact'], "<a href=\"" . append_sid("admin_contact.php") . '">', '</a>')
					. '<br /><br />' . sprintf($lang['click_return_contact_member'], "<a href=\"" . append_sid("admin_contact.php?mode=member&t=$contact_id") . '">', '</a>');				
				message_die(GENERAL_MESSAGE, $message);
				
				break;

			case 'adduser':
			
				$member = $_POST['members'];
				$status = $_POST['status'];
				
				if (!$_POST['members'])
				{
					message_die(GENERAL_ERROR, $lang['contact_no_select']);
				}
				
				$user_id_ary = $member;
				$user_id_ary_im = implode(', ', $member);
				
				// Remove users who are already members of this group
				$sql = 'SELECT user_id FROM ' . CONTACT_LINEUP_TABLE . ' WHERE user_id IN ('.$user_id_ary_im.') AND contact_id = ' . $contact_id;
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
				}
				
				$add_id_ary = $user_id_ary_im = array();
				
				while ($row = $db->sql_fetchrow($result))
				{
					$add_id_ary[] = (int) $row['user_id'];
				}
				$db->sql_freeresult($result);
				
				// Do all the users exist in this group?
				$add_id_ary = array_diff($user_id_ary, $add_id_ary);
				
				// If we have no users
				if (!sizeof($add_id_ary) && !sizeof($user_id_arya))
				{
					message_die(GENERAL_ERROR, $lang['team_no_new'], '', __LINE__, __FILE__);
				}
				
				if (sizeof($add_id_ary))
				{
					$sql_ary = array();
			
					foreach ($add_id_ary as $user_id)
					{
						$sql_ary[] = array(
							'contact_id'		=> (int) $contact_id,
							'user_id'		=> (int) $user_id,							
							'status'		=> (int) $status,
						);
					}
			
					if (!sizeof($sql_ary))
					{
						message_die(GENERAL_ERROR, 'Fehler', '', __LINE__, __FILE__);
					}
					
					$ary = array();
					foreach ($sql_ary as $id => $_sql_ary)
					{
						$values = array();
						foreach ($_sql_ary as $key => $var)
						{
							$values[] = intval($var);
						}
						$ary[] = '(' . implode(', ', $values) . ')';
					}
		
					
					$sql = 'INSERT INTO ' . CONTACT_LINEUP_TABLE . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary);
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
					}
				}

				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CONTACT, ACP_CONTACT_ADD_MEMBER , '');
			
				$message = $lang['contact_add_member'] . '<br /><br />' . sprintf($lang['click_return_contact'], "<a href=\"" . append_sid("admin_contact.php") . '">', '</a>')
					. '<br /><br />' . sprintf($lang['click_return_contact_member'], "<a href=\"" . append_sid("admin_contact.php?mode=member&t=$contact_id") . '">', '</a>');				
				message_die(GENERAL_MESSAGE, $message);
				
			break;
			
			case 'deluser':
			
				$members = $_POST['members'];
				$contact_name = $_POST['contact_name'];
				
				if (!$_POST['members'])
				{
					message_die(GENERAL_ERROR, $lang['contact_no_select']);
				}
				
				$sql_in = implode(", ", $members);
				
				$sql = "DELETE FROM " . CONTACT_LINEUP_TABLE . " WHERE user_id IN ($sql_in) AND contact_id = $contact_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not delete memer', '', __LINE__, __FILE__, $sql);
				}
				
				_log(LOG_ADMIN, $userdata['user_id'], $userdata['session_ip'], LOG_SEK_CONTACT, ACP_CONTACT_ADD_MEMBER , '');
			
				$message = $lang['contact_del_member'] . '<br /><br />' . sprintf($lang['click_return_contact'], "<a href=\"" . append_sid("admin_contact.php") . '">', '</a>')
					. '<br /><br />' . sprintf($lang['click_return_contact_member'], "<a href=\"" . append_sid("admin_contact.php?mode=member&t=$contact_id") . '">', '</a>');				
				message_die(GENERAL_MESSAGE, $message);
			
			break;
			
			default:
				$show_index = TRUE;
				message_die(GENERAL_ERROR, $lang['no_mode']);
				break;
		}
	
		if ($show_index != TRUE)
		{
			include('./page_footer_admin.php');
			exit;
		}
	}
	
	//	Template
	$template->set_filenames(array('body' => './../admin/style/contact_body.tpl'));
	
	$template->assign_vars(array(
		'L_CONTACT_TITLE'			=> $lang['contact'],
		'L_CONTACT_EXPLAIN'			=> $lang['contact'],
		
		'L_CONTACT_CREATE'			=> $lang['contact'],
		'L_CONTACT_NAME'			=> $lang['contact'],
		'L_CONTACT_GAME'			=> $lang['contact'],
		'L_CONTACT_MEMBERCOUNT'		=> $lang['contact'],
		'L_CONTACT_SETTINGS'		=> $lang['settings'],
		'L_CONTACT_SETTING'			=> $lang['setting'],
		'L_CONTACT_MEMBER'			=> $lang['contact'],
		
		'L_DELETE'				=> $lang['delete'],
		
		'L_TRAINING'			=> $lang['training'],
		
		'S_CONTACT_ACTION'		=> append_sid("admin_contact.php")
	));
	
	$sql = 'SELECT c.*, t.team_name, g.game_image, g.game_size
			FROM ' . CONTACT_TABLE . ' c
				LEFT JOIN ' . TEAMS_TABLE . ' t ON c.contact_team = t.team_id
				LEFT JOIN ' . GAMES_TABLE . ' g ON t.team_game = g.game_id
			ORDER BY c.contact_time DESC';
	$result = $db->sql_query($sql);
	$contact_entry = $db->sql_fetchrowset($result); 
	$db->sql_freeresult($result);
	
	if ( !$contact_entry )
	{
		$template->assign_block_vars('no_entry', array());
		$template->assign_vars(array('NO_ENTRY' => $lang['no_entry']));
	}
	else
	{
		for ($i = $start; $i < min($settings['entry_per_page'] + $start, count($contact_entry)); $i++)
		{
			$class = ($i % 2) ? 'row_class1' : 'row_class2';
				
			$game_size	= $contact_entry[$i]['game_size'];
			$game_image	= '<img src="' . $root_path . $settings['game_path'] . '/' . $contact_entry[$i]['game_image'] . '" alt="" width="' . $game_size . '" height="' . $game_size . '" >';
			
			
			$template->assign_block_vars('contact_row', array(
				'CLASS' 			=> $class,
				'CONTACT_ID' 		=> $contact_entry[$i]['contact_id'],
				'CONTACT_GAME'		=> $game_image,
				'CONTACT_TYPE'		=> ($contact_entry[$i]['contact_type'] != '0') ? ($contact_entry[$i]['contact_type'] == '2') ? $lang['contact_joinus'] : $lang['contact_fightus'] : $lang['contact'],
				'CONTACT_STATUS'	=> ($contact_entry[$i]['contact_status'] != '0') ? ($contact_entry[$i]['contact_status'] == '2') ? $lang['contact_type_edit'] : $lang['contact_type_close'] : $lang['contact_type_open'],
				'CONTACT_FROM'		=> $contact_entry[$i]['contact_from'],
				'CONTACT_MAIL'		=> $contact_entry[$i]['contact_mail'],
				'CONTACT_HOMEPAGE'	=> $contact_entry[$i]['contact_homepage'],
				'CONTACT_DATE'		=> create_date($userdata['user_dateformat'], $contact_entry[$i]['contact_time'], $userdata['user_timezone']),

			));
		}
	}
	
	$current_page = ( !count($contact_entry) ) ? 1 : ceil( count($contact_entry) / $settings['entry_per_page'] );

	$template->assign_vars(array(
		'PAGINATION' => ( count($contact_entry) ) ? generate_pagination("admin_contact.php?", count($contact_entry), $settings['entry_per_page'], $start) : '',
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $settings['entry_per_page'] ) + 1 ), $current_page ), 

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);

	$template->pparse("body");
			
	include('./page_footer_admin.php');
}
?>