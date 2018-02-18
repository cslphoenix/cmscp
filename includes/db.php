<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

#include($root_path . 'includes/mysql4.php');

switch ( $dbms )
{
	case 'mysqli':
		include($root_path . 'includes/mysqli.php');
		break;

	case 'mysql4':
		include($root_path . 'includes/mysql4.php');
		break;
}

// Make the database connection.
//$db = new sql_db($db_host, $db_user, $db_pwd, $db_name, false);
include_once($root_path . 'includes/class_db.php');
$db = new db_class($db_host, $db_user, $db_pwd, $db_name, false);
if (!$db->db_connect_id)
{
	message(CRITICAL_ERROR, "Could not connect to the database");
}

?>