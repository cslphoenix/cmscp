<?php

/***

							___.          
	  ____   _____   ______ \_ |__ ___.__.
	_/ ___\ /     \ /  ___/  | __ <   |  |
	\  \___|  Y Y  \\___ \   | \_\ \___  |
	 \___  >__|_|  /____  >  |___  / ____|
		 \/      \/     \/       \/\/     
	__________.__                         .__        
	\______   \  |__   ____   ____   ____ |__|__  ___
	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
				   \/            \/     \/         \/

	* Content-Management-System by Phoenix

	* @autor:	Sebastian Frickel © 2009
	* @code:	Sebastian Frickel © 2009

***/

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//	Start session management
$userdata = session_pagestart($user_ip, PAGE_CONTACT);
init_userprefs($userdata);

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
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
	$select_type .= '<select name="contact_wartype" class="post">';
	foreach ($type as $typ => $valve)
	{
		$selected = ( $valve == $default ) ? ' selected="selected"' : '';
		$select_type .= '<option value="' . $valve . '" ' . $selected . '>&raquo; ' . $typ . '&nbsp;</option>';
	}
	$select_type .= '</select>';
	
	return $select_type;	
}

if ( $mode == 'contact' || $mode == 'joinus' || $mode == 'fightus' )
{
	session_start();
	
	$page_title = ($mode != 'contact') ? ($mode == 'joinus') ? $lang['contact_joinus'] : $lang['contact_fightus'] : $lang['contact'];
	
	include($root_path . 'includes/page_header.php');
	include($root_path . 'includes/functions_selects.php');
	
	$template->set_filenames(array('body' => 'contact_body.tpl'));
	
	if ($mode == 'contact')
	{
		$template->assign_block_vars('contact', array());
	}
	else if ($mode == 'joinus')
	{
		$template->assign_block_vars('joinus', array());
	}
	else if ($mode == 'fightus')
	{
		$template->assign_block_vars('fightus', array());
	}
	
	$info = ($mode != 'contact') ? ($mode == 'joinus') ? $lang['contact_info_joinus'] : $lang['contact_info_fightus'] : $lang['contact_info'];
	
	$typ = ($mode != 'contact') ? ($mode == 'joinus') ? '2' : '1' : '0';
	
	$error = FALSE;
	$error_msg = '';
	
	$team_id = ( isset($HTTP_GET_VARS[POST_TEAM_URL]) ) ? intval($HTTP_GET_VARS[POST_TEAM_URL]) : '0';
	$contact_categorie = 0;
	$contact_wartype = 0;
	
	if ( isset($HTTP_POST_VARS['submit']) )
	{
		if ( $mode == 'fightus' )
		{
			$team_id			= intval($HTTP_POST_VARS['team_id']);
			$contact_categorie	= intval($HTTP_POST_VARS['contact_categorie']);
			$contact_wartype	= intval($HTTP_POST_VARS['contact_wartype']);
			
			$contact_rival_name	= trim(stripslashes($HTTP_POST_VARS['contact_rival_name']));
			$contact_rival_tag	= trim(stripslashes($HTTP_POST_VARS['contact_rival_tag']));
			$contact_map		= trim(stripslashes($HTTP_POST_VARS['contact_map']));
		}
		
		if ( $mode == 'joinus' )
		{
			$team_id = intval($HTTP_POST_VARS['team_id']);
		}

		$contact_nick		= trim($HTTP_POST_VARS['contact_nick']);
		$contact_mail		= trim($HTTP_POST_VARS['contact_mail']);
		$contact_hp			= trim($HTTP_POST_VARS['contact_hp']);
		$contact_message	= trim($HTTP_POST_VARS['contact_message']);
		
		
		$template->assign_vars(array(
			'CONTACT_NICK'			=> $contact_nick,
			'CONTACT_MAIL'			=> $contact_mail,
			'CONTACT_HP'			=> $contact_hp,
			'CONTACT_MESSAGE'		=> $contact_message,
		));
		
		if ( $mode == 'fightus' )
		{
			$template->assign_vars(array(
				'CONTACT_RIVAL_NAME'	=> $contact_rival_name,
				'CONTACT_RIVAL_TAG'		=> $contact_rival_tag,
				'CONTACT_MAP'			=> $contact_map,
			));
		}
		
		$captcha = $HTTP_POST_VARS['captcha'];
				
		if ($captcha != $HTTP_SESSION_VARS['captcha'])
		{
			$error = true;
			$error_msg = $lang['contact_fail_captcha'];
		}
		
		if ( empty($HTTP_POST_VARS['contact_nick']) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_nick'];
		}
		
		if ( empty($HTTP_POST_VARS['contact_mail']) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_mail'];
		}
		
		if ( $mode == 'fightus' )
		{
			if ( empty($HTTP_POST_VARS['contact_rival_name']) )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_rival_name'];
			}
			
			if ( empty($HTTP_POST_VARS['contact_rival_tag']) )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_rival_tag'];
			}
			
			if ( !checkdate($HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_date'];
			}
			
			if ( intval($HTTP_POST_VARS['team_id']) == '0' || intval($HTTP_POST_VARS['team_id']) == '' )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_team'];
			}
			
			if ( intval($HTTP_POST_VARS['contact_categorie']) == '0' || intval($HTTP_POST_VARS['contact_categorie']) == '' )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_categorie'];
			}
			
			if ( intval($HTTP_POST_VARS['contact_wartype']) == '0' || intval($HTTP_POST_VARS['contact_wartype']) == '' )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_type'];
			}
		}
		
		if ( $mode == 'joinus' )
		{
			if ( intval($HTTP_POST_VARS['team_id']) == '0' || intval($HTTP_POST_VARS['team_id']) == '' )
			{
				$error = true;
				$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_team'];
			}
		}
		
		if ( empty($HTTP_POST_VARS['contact_message']) )
		{
			$error = true;
			$error_msg .= ( ( isset($error_msg) ) ? '<br>' : '' ) . $lang['contact_fail_message'];
		}

		if ( $error )
		{
			$template->set_filenames(array('reg_header' => 'error_body.tpl'));
			$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
			$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
		}

		if ( !$error )
		{
			$contact_date		= ( $mode == 'fightus' ) ? mktime($HTTP_POST_VARS['hour'], $HTTP_POST_VARS['min'], 00, $HTTP_POST_VARS['month'], $HTTP_POST_VARS['day'], $HTTP_POST_VARS['year']) : '';
			$contact_type		= ( $mode != 'contact' ) ? ($mode == 'joinus') ? '2' : '1' : '0';
			$contact_rival_name	= ( $mode == 'fightus' ) ? str_replace("\'", "''", $HTTP_POST_VARS['contact_rival_name']) : '';
			$contact_rival_tag	= ( $mode == 'fightus' ) ? str_replace("\'", "''", $HTTP_POST_VARS['contact_rival_tag']) : '';
			$contact_maps		= ( $mode == 'fightus' ) ? str_replace("\'", "''", $HTTP_POST_VARS['contact_map']) : '';
			$contact_message	= htmlentities(str_replace("\'", "''", $HTTP_POST_VARS['contact_message']), ENT_QUOTES);
			$contact_team		= ( $mode == 'fightus' || $mode == 'joinus' ) ? intval($HTTP_POST_VARS['team_id']) : '';
			$contact_age		= ( $mode == 'joinus' ) ? str_replace("\'", "''", $HTTP_POST_VARS['contact_age']) : '';
			$contact_categorie	= ( $mode == 'fightus' ) ? intval($HTTP_POST_VARS['contact_categorie']) : '';
			$contact_wartype	= ( $mode == 'fightus' ) ? intval($HTTP_POST_VARS['contact_wartype']) : '';
				
			$sql = "INSERT INTO " . CONTACT . " (contact_from, contact_type, contact_mail, contact_homepage, contact_rival_name, contact_rival_tag, contact_maps, contact_message, contact_team, contact_age, contact_date, contact_categorie, contact_wartype, contact_status, contact_time)
				VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['contact_nick']) . "', $contact_type, '" . str_replace("\'", "''", $HTTP_POST_VARS['contact_mail']) . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['contact_hp']) . "', '$contact_rival_name', '$contact_rival_tag', '$contact_maps', '$contact_message', '$contact_team', '$contact_age', '$contact_date', '$contact_categorie', '$contact_wartype', 0, '" . time() . "')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		
			$message = $lang['contact_save'] . '<br><br>' . sprintf($lang['click_return_contact'], '<a href="' . append_sid("contact.php?mode=$mode") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	
	$s_hidden_field = '<input type="hidden" name="mode" value="' . $mode . '" />';
	
	$template->assign_vars(array(
		'L_CONTACT'			=> $page_title,
		'L_CONTACT_INFO'	=> $info,
		'L_REQUIRED'		=> $lang['items_required'],
		
		'S_DAY'				=> _select_date('day', 'day',		date('d', time())),
		'S_MONTH'			=> _select_date('month', 'month',	date('m', time())),
		'S_YEAR'			=> _select_date('year', 'year',		date('Y', time())),
		'S_HOUR'			=> _select_date('hour', 'hour',		date('H', time())),
		'S_MIN'				=> _select_date('min', 'min',		date('i', time())),
		
		'S_TYPE'			=> _select_type($contact_wartype),
		'S_TEAM'			=> _select_team($team_id, $typ, 'post'),
		'S_CATEGORIE'		=> _select_categorie($contact_categorie),
		
		'S_HIDDEN_FIELD'	=> $s_hidden_field,
		'S_CONTACT_ACTION'	=> append_sid('contact.php?mode=$mode'),
		
	));

}
else
{
	redirect(append_sid('contact.php?mode=contact', true));
}

$template->pparse('body');

include($root_path . 'includes/page_tail.php');

?>