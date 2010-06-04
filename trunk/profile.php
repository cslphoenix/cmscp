<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);

if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

//	Set default email variables
$script_name		= preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['page_path']));
$script_name		= ( $script_name != '' ) ? $script_name . '/profile.'.$phpEx : 'profile.'.$phpEx;
$server_name		= trim($config['server_name']);
$server_protocol	= ( $config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port		= ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';
$server_url			= $server_protocol . $server_name . $server_port . $script_name;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
	$mode = htmlspecialchars($mode);

	if ( $mode == 'view' )
	{
		$page_title = $lang['Viewing_profile'];
		
		$template->set_filenames(array('body' => 'body_profile.tpl'));
		$template->assign_block_vars('details', array());
		
		if ( empty($HTTP_GET_VARS[POST_USERS_URL]) || $HTTP_GET_VARS[POST_USERS_URL] == ANONYMOUS )
		{
			message(GENERAL_MESSAGE, $lang['No_user_id_specified']);
		}
		
		$user_data = get_userdata($HTTP_GET_VARS[POST_USERS_URL]);
		$user_info = get_profiledata($user_data['user_id']);
		
		if (!$user_data)
		{
			message(GENERAL_MESSAGE, $lang['No_user_id_specified']);
		}
		
		$sql = 'SELECT * FROM ' . PROFILE_CATEGORY . ' ORDER BY category_order';
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		if ( $total_categories = $db->sql_numrows($result) )
		{
			$category_rows = $db->sql_fetchrowset($result);
			
			$sql = 'SELECT *
						FROM ' . PROFILE . '
						ORDER BY profile_category, profile_order';
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
		
			if ( $total_profile = $db->sql_numrows($result) )
			{
				$profile_rows = $db->sql_fetchrowset($result);
			}
			
			for ( $i = 0; $i < $total_categories; $i++ )
			{
				$cat_id = $category_rows[$i]['profile_category_id'];
		
				$template->assign_block_vars('details.info_cat', array( 
					'CATEGORY_ID'			=> $cat_id,
					'CATEGORY_NAME'			=> $category_rows[$i]['category_name'],
				));
				
				for ($j = 0; $j < $total_profile; $j++ )
				{
					$profile_id = $profile_rows[$j]['profile_id'];
					
					if ( $profile_rows[$j]['profile_category'] == $cat_id )
					{
						$value = $user_info[$profile_rows[$j]['profile_field']];
						
						$template->assign_block_vars('details.info_cat.info_data',	array(
							'NAME'	=> $profile_rows[$j]['profile_name'],
							'FIELD' => $value,
						));
					}
				}
			}
		}
		
		
	}
	else if ( $mode == 'edit' || $mode == 'register' )
	{
		
	}
	else if ( $mode == 'password' )
	{
		
	}
	else if ( $mode == 'activate' )
	{
		
	}
	else if ( $mode == 'email' )
	{
		
	}
	
	include($root_path . 'includes/page_header.php');
	
	$template->pparse('body');
	
	include($root_path . 'includes/page_tail.php');
}

redirect(append_sid('index.php', true));
