<?php

function error_handler($errno, $errstr, $errfile, $errline)
{
	global $root_path, $template;
	
	$errfile = str_replace(array(cms_realpath($root_path), '\\'), array('', '/'), $errfile);
	
	$errno = $errno & error_reporting();
	
	if ( $errno == 0 )
	{
		return;
	}
	
	if ( !defined('E_STRICT') )
	{
		define('E_STRICT', 2048);
	}
    
	if ( !defined('E_RECOVERABLE_ERROR') )
	{
		define('E_RECOVERABLE_ERROR', 4096);
	}
	
	$err_msg = "<div align=\"left\"><b>[";
	
	switch ( $errno )
	{
		case E_ERROR:				$err_msg .= 'Error';					break;
		case E_WARNING:				$err_msg .= 'Warning';					break;
		case E_PARSE:				$err_msg .= 'Parse Error';				break;
		case E_NOTICE:				$err_msg .= 'Notice';					break;
		case E_CORE_ERROR:			$err_msg .= 'Core Error';				break;
		case E_CORE_WARNING:		$err_msg .= 'Core Warning';				break;
		case E_COMPILE_ERROR:		$err_msg .= 'Compile Error';			break;
		case E_COMPILE_WARNING:		$err_msg .= 'Compile Warning';			break;
		case E_USER_ERROR:			$err_msg .= 'User Error';				break;
		case E_USER_WARNING:		$err_msg .= 'User Warning';				break;
		case E_USER_NOTICE:			$err_msg .= 'User Notice';				break;
		case E_STRICT:				$err_msg .= 'Strict Notice';			break;
		case E_RECOVERABLE_ERROR:	$err_msg .= 'Recoverable Error';		break;
		default:					$err_msg .= 'Unknown error ($errno)';	break;
	}
	
	$err_msg .= "]:</b> $errstr in <b>$errfile</b> Zeile: <b>$errline</b>";
	$err_msg .= "</div>\n";
	
	if ( isset($GLOBALS['error_fatal']) )
	{
		if ( $GLOBALS['error_fatal'] & $errno )
		{
			die('fatal');
		}
	}

	echo $err_msg;
}

function error_fatal($mask = NULL)
{
	if ( !is_null($mask) )
	{
		$GLOBALS['error_fatal'] = $mask;
	}
	else if ( !isset($GLOBALS['die_on']) )
	{
		$GLOBALS['error_fatal'] = 0;
	}
	
	return $GLOBALS['error_fatal'];
}

function debug($data, $name = '', $var = false)
{
	$name = ( $name != '' ) ? " :: $name" : '';
	
	print '<div align="left">';
	print "<-- start debug $name -->";
	print '<pre>';
	print_r($data);
	print '</pre>';
	
	if ( $var )
	{
		print '<pre>';
		var_dump($data);
		print '</pre>';
	}
	
	print "<-- start end $name -->";
	print "</div>\n";
}

function debuge($data)
{
	global $root_path, $db, $config, $template;
	
	print '<br>';
	print '<pre>';
	print_r($data);
	print '</pre>';
	print '<br>';
	
	include($root_path . defined('IN_ADMIN') ? 'admin/page_footer_admin.php' : main_footer());
	
	exit;
}

?>