<?php

define('IN_CMS', true);
$root_path = './';

include('./common.php');

$userdata = session_pagestart($user_ip, PAGE_POSTING);
init_userprefs($userdata);

$template->set_filenames(array(
	'body'		=> 'posting_body.tpl',
	'error'		=> 'error_body.tpl',
));

$page_title = $lang['match'];
$page_title = $lang['match_details'];

$url	= POST_MATCH;

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$data	= request(POST_MATCH, INT);
$mode	= request('mode', TXT);
$smode	= request('smode', 1);

$time	= time();
$user	= $userdata['user_id'];

$error	= '';
$fielde	= '';
$fields	= '';

main_header();

if ( $mode == 'newtopic' )
{
	if ( $mode == 'newtopic' && !request('submit', TXT) )
	{
		$post = array(
					'post_subject'	=> '',
					'post_message'	=> '',
					'post_notify'	=> true,
					'post_first'	=> true,
					'topic_icon'	=> '',
					'topic_type'	=> 0,
				);
	}
	else
	{
		$post = array(
					'post_subject'	=> request('post_subject', 2),
					'post_message'	=> request('post_message', 2),
					'post_notify'	=> ( request('post_notify', 1) == 'on' ) ? 1 : 0,
					'post_first'	=> request('post_first', 0),
					'topic_icon'	=> request('topic_icon', 0),
					'topic_type'	=> ( in_array(request('topic_type', 0), array(POST_NORMAL, POST_STICKY, POST_ANNOUNCE, POST_GLOBAL)) ) ? request('topic_type', 0) : POST_NORMAL,
				);
	}
	
	$s_type = $s_icon = '';
	
	if ( $mode == 'newtopic' )
	{
		$template->assign_block_vars('type_icon', array());
		$template->assign_block_vars('type_toggle', array());
		
		$s_type .= '<label><input type="radio" name="topic_type" value="' . POST_NORMAL . '"';
		$s_type .= ( $post['topic_type'] == POST_NORMAL ) ? ' checked="checked"' : '';
		$s_type .= ' /> ' . $lang['post_normal'] . '</label>&nbsp;';
		
		$s_type .= '<label><input type="radio" name="topic_type" value="' . POST_STICKY . '"';
		$s_type .= ( $post['topic_type'] == POST_STICKY ) ? ' checked="checked"' : '';
		$s_type .= ' /> ' . $lang['post_sticky'] . '</label>&nbsp;';
		
		$s_type .= '<label><input type="radio" name="topic_type" value="' . POST_ANNOUNCE . '"';
		$s_type .= ( $post['topic_type'] == POST_ANNOUNCE ) ? ' checked="checked"' : '';
		$s_type .= ' /> ' . $lang['post_announce'] . '</label>&nbsp;';
		
		$s_type .= '<label><input type="radio" name="topic_type" value="' . POST_GLOBAL . '"';
		$s_type .= ( $post['topic_type'] == POST_GLOBAL ) ? ' checked="checked"' : '';
		$s_type .= ' /> ' . $lang['post_global'] . '</label>&nbsp;';
		
		$s_icon .= 'icons halt so auflisten und so kram ;)';
	}
	
	$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
	$fields .= "<input type=\"hidden\" name=\"post_first\" value=\"" . $post['post_first'] . "\" />";
				
	$template->assign_vars(array(
		'POST_SUBJECT'	=> $post['post_subject'],
		'POST_MESSAGE'	=> $post['post_message'],
		
		'S_ICON'		=> $s_icon,
		'S_TYPE'		=> $s_type,
		'S_NOTIFY'		=> $post['post_notify'] ? 'checked="checked"' : '',
		
		'S_FIELDS'		=> $fields,
	));
	
	if ( request('submit', TXT) )
	{
		$error .= !$post['post_subject'] ? ( $error ? '<br />' : '' ) . $lang['msg_empty_subject'] : '';
		$error .= !$post['post_message'] ? ( $error ? '<br />' : '' ) . $lang['msg_empty_message'] : '';
		
		if ( !$error )
		{
			$sql = "INSERT INTO " . POSTS . " () VALUES ()";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$msg = $lang['newtopic'];
			
			debug($sql);
			
		#	log_add(LOG_ADMIN, $log, $mode, $sql);
			message(GENERAL_MESSAGE, $msg);
		}
		else
		{
		#	log_add(LOG_ADMIN, $log, 'error', $error);
			
			$template->assign_vars(array('ERROR_MESSAGE' => $error));
			$template->assign_var_from_handle('ERROR_BOX', 'error');
		}
	}
	
	$template->pparse('body');
}

main_footer();

?>