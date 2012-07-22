<?php

if ( !defined('IN_CMS') )
{
	die('Hacking attempt');
}

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
	9	=> array(AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM),	//	Administrator
);

$forum_auth_fields = array(
	'auth_view',
	'auth_read',
	'auth_post',
	'auth_reply',
	'auth_edit',
	'auth_delete',
	'auth_sticky',
	'auth_announce',
	'auth_globalannounce',
	'auth_poll',
	'auth_pollcreate'
);

$forum_auth_levels	= array('all', 'register', 'trial', 'member', 'moderator', 'admin');
$forum_auth_const	= array(AUTH_ALL, AUTH_REG, AUTH_TRI, AUTH_MEM, AUTH_MOD, AUTH_ADM);

/*
$gallery_auth_fields	= array('auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_upload');
$gallery_auth_levels	= array('guest', 'user', 'trial', 'member', 'coleader', 'leader', 'uploader');
$gallery_auth_constants	= array(AUTH_GUEST, AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOADER);
#	values for rights form upload
$gallery_auth_upload_levels		= array('user', 'trial', 'member', 'coleader', 'leader');
$gallery_auth_upload_constants	= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER);
#	values for rights form no guests
$gallery_auth_none_levels		= array('user', 'trial', 'member', 'coleader', 'leader', 'uploader');
$gallery_auth_none_constants	= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOADER);
*/

$fields_auth = array('auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_comment', 'auth_upload', 'auth_approve', 'auth_report');
$simple_auth = array(
	0 => array(AUTH_ALL, AUTH_UPL, AUTH_UPL, AUTH_REG, AUTH_REG, AUTH_MEM, AUTH_MOD, AUTH_ALL),	// Öffentlich
	1 => array(AUTH_REG, AUTH_UPL, AUTH_UPL, AUTH_REG, AUTH_REG, AUTH_MEM, AUTH_MOD, AUTH_REG),	// Benutzer
	2 => array(AUTH_TRI, AUTH_UPL, AUTH_UPL, AUTH_TRI, AUTH_TRI, AUTH_MEM, AUTH_MOD, AUTH_TRI),	// Trail
	3 => array(AUTH_MEM, AUTH_UPL, AUTH_UPL, AUTH_MEM, AUTH_MEM, AUTH_MEM, AUTH_MOD, AUTH_MEM),	// Member
	4 => array(AUTH_MOD, AUTH_UPL, AUTH_UPL, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_ADM, AUTH_MOD),	// Moderatoren
	5 => array(AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM),	// Administrator
);

?>