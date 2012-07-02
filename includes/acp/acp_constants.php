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
	9	=> array(AUTH_REG, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_MOD, AUTH_ACL, AUTH_ACL),	//	Privat
	10	=> array(AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL),	//	Privat versteckt
	11	=> array(AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM, AUTH_ADM),	//	Administrator
);

$simple_auth_types = array(
	$lang['forms_public'],
	$lang['forms_register'],	sprintf($lang['forms_hidden'], $lang['forms_register']),
	$lang['forms_trial'],		sprintf($lang['forms_hidden'], $lang['forms_trial']),
	$lang['forms_member'],		sprintf($lang['forms_hidden'], $lang['forms_member']),
	$lang['forms_moderator'],	sprintf($lang['forms_hidden'], $lang['forms_moderator']),
	$lang['forms_privat'],		sprintf($lang['forms_hidden'], $lang['forms_privat']),
	$lang['forms_admin'],
	$lang['forms_special'],
);

$field_names = array(
	'auth_view'				=> $lang['forms_view'],
	'auth_read'				=> $lang['forms_read'],
	'auth_post'				=> $lang['forms_post'],
	'auth_reply'			=> $lang['forms_reply'],
	'auth_edit'				=> $lang['forms_edit'],
	'auth_delete'			=> $lang['forms_delete'],
	'auth_sticky'			=> $lang['forms_sticky'],
	'auth_announce'			=> $lang['forms_announce'],
	'auth_globalannounce'	=> $lang['forms_globalannounce'],
	'auth_poll'				=> $lang['forms_poll'],
	'auth_pollcreate'		=> $lang['forms_pollcreate'],
);

$field_images = array(
	'auth_view'				=> $images['forms_view'],
	'auth_read'				=> $images['forms_read'],
	'auth_post'				=> $images['forms_post'],
	'auth_reply'			=> $images['forms_reply'],
	'auth_edit'				=> $images['forms_edit'],
	'auth_delete'			=> $images['forms_delete'],
	'auth_sticky'			=> $images['forms_sticky'],
	'auth_announce'			=> $images['forms_announce'],
	'auth_globalannounce'	=> $images['forms_globalannounce'],
	'auth_poll'				=> $images['forms_poll'],
	'auth_pollcreate'		=> $images['forms_pollcreate'],
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

$forum_auth_levels	= array('all', 'register', 'trial', 'member', 'moderator', 'private', 'admin');
$forum_auth_const	= array(AUTH_ALL, AUTH_REG, AUTH_TRI, AUTH_MEM, AUTH_MOD, AUTH_ACL, AUTH_ADM);

$gallery_auth_fields	= array('auth_view', 'auth_edit', 'auth_delete', 'auth_rate', 'auth_upload');
$gallery_auth_levels	= array('guest', 'user', 'trial', 'member', 'coleader', 'leader', 'uploader');
$gallery_auth_constants	= array(AUTH_GUEST, AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOADER);
/* values for rights form upload */
$gallery_auth_upload_levels		= array('user', 'trial', 'member', 'coleader', 'leader');
$gallery_auth_upload_constants	= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER);
/* values for rights form no guests */
$gallery_auth_none_levels		= array('user', 'trial', 'member', 'coleader', 'leader', 'uploader');
$gallery_auth_none_constants	= array(AUTH_USER, AUTH_TRIAL, AUTH_MEMBER, AUTH_COLEADER, AUTH_LEADER, AUTH_UPLOADER);

?>