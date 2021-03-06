<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

$starttime = microtime();
$trc_loc_start = $trc_loc_end = 0;

ob_start();

#error_reporting (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
#error_reporting(E_ALL ^ E_NOTICE); // will report all errors

#set_magic_quotes_runtime(0); // Disable magic_quotes_runtime
ini_set("magic_quotes_runtime", 0);

// The following code (unsetting globals)
// Thanks to Matt Kavanagh and Stefan Esser for providing feedback as well as patch files

// PHP5 with register_long_arrays off?
if (@phpversion() >= '5.0.0' && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off'))
{
	$HTTP_POST_VARS = $_POST;
	$HTTP_GET_VARS = $_GET;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_COOKIE_VARS = $_COOKIE;
	$HTTP_ENV_VARS = $_ENV;
	$HTTP_POST_FILES = $_FILES;

	// _SESSION is the only superglobal which is conditionally set
	if (isset($_SESSION))
	{
		$HTTP_SESSION_VARS = $_SESSION;
	}
}

// Protect against GLOBALS tricks
if (isset($HTTP_POST_VARS['GLOBALS']) || isset($HTTP_POST_FILES['GLOBALS']) || isset($HTTP_GET_VARS['GLOBALS']) || isset($HTTP_COOKIE_VARS['GLOBALS']))
{
	die('Hacking attempt');
}

// Protect against HTTP_SESSION_VARS tricks
if (isset($HTTP_SESSION_VARS) && !is_array($HTTP_SESSION_VARS))
{
	die('Hacking attempt');
}

if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
{
	// PHP4+ path
	$not_unset = array('HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_COOKIE_VARS', 'HTTP_SERVER_VARS', 'HTTP_SESSION_VARS', 'HTTP_ENV_VARS', 'HTTP_POST_FILES', 'root_path');

	// Not only will array_merge give a warning if a parameter
	// is not an array, it will actually fail. So we check if
	// HTTP_SESSION_VARS has been initialised.
	if (!isset($HTTP_SESSION_VARS) || !is_array($HTTP_SESSION_VARS))
	{
		$HTTP_SESSION_VARS = array();
	}

	// Merge all into one extremely huge array; unset
	// this later
	$input = array_merge($HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_SESSION_VARS, $HTTP_ENV_VARS, $HTTP_POST_FILES);

	unset($input['input']);
	unset($input['not_unset']);

	while (list($var,) = @each($input))
	{
		if (in_array($var, $not_unset))
		{
			die('Hacking attempt!');
		}
		unset($$var);
	}

	unset($input);
}

// PHP5 with register_long_arrays off?
if ( !isset($HTTP_POST_VARS) && isset($_POST) )
{
	$HTTP_POST_VARS = $_POST;
	$HTTP_GET_VARS = $_GET;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_COOKIE_VARS = $_COOKIE;
	$HTTP_ENV_VARS = $_ENV;
	$HTTP_POST_FILES = $_FILES;
}
//
// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//
if( !get_magic_quotes_gpc() )
{
	if( is_array($HTTP_GET_VARS) )
	{
		while( list($k, $v) = each($HTTP_GET_VARS) )
		{
			if( is_array($HTTP_GET_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
				{
					$HTTP_GET_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_GET_VARS[$k]);
			}
			else
			{
				$HTTP_GET_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_GET_VARS);
	}

	if( is_array($HTTP_POST_VARS) )
	{
		while( list($k, $v) = each($HTTP_POST_VARS) )
		{
			if( is_array($HTTP_POST_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
				{
					$HTTP_POST_VARS[$k][$k2] = @addslashes($v2);
				}
				@reset($HTTP_POST_VARS[$k]);
			}
			else
			{
				$HTTP_POST_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_POST_VARS);
	}

	if( is_array($HTTP_COOKIE_VARS) )
	{
		while( list($k, $v) = each($HTTP_COOKIE_VARS) )
		{
			if( is_array($HTTP_COOKIE_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]) )
				{
					$HTTP_COOKIE_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_COOKIE_VARS[$k]);
			}
			else
			{
				$HTTP_COOKIE_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_COOKIE_VARS);
	}
}

//
// Define some basic configuration arrays this also prevents
// malicious rewriting of language and otherarray values via
// URI params
//
$config		= array();
$settings	= array();
$userdata	= array();
$theme		= array();
$images		= array();
$lang		= array();
$nav_links	= array();
$dss_seeded	= false;
$gen_simple_header = false;

include($root_path . 'includes/config.php');

if ( !defined('CMS_INSTALLED') )
{
	header('Location: ./install.php');
	exit;
}

include($root_path . 'includes/constants.php');
include($root_path . 'includes/template.php');
include($root_path . 'includes/sessions.php');
include($root_path . 'includes/auth.php');
include($root_path . 'includes/class_cache.php');
include($root_path . 'includes/functions.php');
include($root_path . 'includes/functions_sql.php');
include($root_path . 'includes/functions_display.php');
include($root_path . 'includes/functions_cache.php');
include($root_path . 'includes/functions_post.php');
include($root_path . 'includes/functions_selects.php');
include($root_path . 'includes/db.php');

unset($db_pwd);

if ( defined('DEBUG') )
{
	include($root_path . 'includes/functions_debug.php');
	
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_USER_ERROR);
	set_error_handler('error_handler');
}
else
{
	error_reporting(0);
}

$client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
$user_ip = encode_ip($client_ip);

if ( defined('IN_ADMIN') )
{
	$sql = 'SELECT * FROM ' . CONFIG;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$config[$row['config_name']] = $row['config_value'];
	}
	
	$sql = 'SELECT * FROM ' . SETTINGS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$unserialize = unserialize($row['settings_value']);
		
		foreach ( $unserialize as $keys => $rows )
		{
			if ( in_array($row['settings_name'], array('path_games', 'path_maps', 'path_newscat', 'path_downloads', 'path_gallery', 'path_ranks', 'path_icons') ) )
			{
				$settings[$row['settings_name']] = $rows;
			}
			else if ( in_array($row['settings_name'], array('match_type', 'match_type2', 'match_war', 'match_war2', 'match_league', 'match_league2') ) )
			{
				$settings[$row['settings_name']][$keys] = $rows;
			}
			else
			{
				$settings[$row['settings_name']][$keys] = $rows;
			}
		}
	}
}
else
{
	if ( defined('CACHE') )
	{
		$oCache->sCachePath = './cache/';
		
		if ( ( $config = $oCache->readCache('cfg_config') ) === false )
		{
			$sql = "SELECT * FROM " . CONFIG;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$config[$row['config_name']] = $row['config_value'];
			}
			
			$oCache->writeCache('cfg_config', $config);
		}

		if ( ( $settings = $oCache->readCache('cfg_setting') ) === false )
		{
			$sql = "SELECT * FROM " . SETTINGS;
			if ( !($result = $db->sql_query($sql)) )
			{
				message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				
				$unserialize = unserialize($row['settings_value']);
				
				foreach ( $unserialize as $keys => $rows )
				{
					
					if ( in_array($row['settings_name'], array('path_games', 'path_maps', 'path_newscat', 'path_downloads', 'path_gallery', 'path_ranks', 'path_icons') ) )
					{
						$settings[$row['settings_name']] = $rows;
					}
					else
					{
						$settings[$row['settings_name']][$keys] = $rows;
					}
				}
			}
			
			$oCache->writeCache('cfg_setting', $settings);
		}
	}
	else
	{
		$sql = "SELECT * FROM " . CONFIG;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$config[$row['config_name']] = $row['config_value'];
		}
		
		$sql = "SELECT * FROM " . SETTINGS;
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$unserialize = unserialize($row['settings_value']);
			
			foreach ( $unserialize as $keys => $rows )
			{
				if ( in_array($row['settings_name'], array('path_games', 'path_maps', 'path_newscat', 'path_downloads', 'path_gallery', 'path_ranks', 'path_icons') ) )
				{
					$settings[$row['settings_name']] = $rows['value'];
				}
				else
				{
					$settings[$row['settings_name']][$keys] = $rows['value'];
				}
			}
		}
	}
}

$settings['per_page_entry_site'] = $settings['per_page_entry']['site'];
$settings['ppec'] = $settings['per_page_entry']['comments'];

$settings['ppe_acp'] = $settings['per_page_entry']['acp'];

if ( file_exists('install') )
{
	message(GENERAL_MESSAGE, 'Please_remove_install_contrib');
}

?>