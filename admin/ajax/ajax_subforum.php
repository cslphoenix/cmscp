<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['name']) )
{	
	$forum_id = intval($_POST['name']);

	$sql = "SELECT * FROM " . FORUM . " WHERE forum_sub = $forum_id ORDER BY forum_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
		exit;
	}
	$forms = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	if ( $forms )
	{
		$s_select .= "<select class=\"select\" name=\"forum_suborder\" id=\"forum_suborder\">";
		$s_select .= "<option selected=\"selected\">" . sprintf($lang['stf_select_format'], $lang['msg_select_order']) . "</option>";
	
		for ( $i = 0; $i < count($forms); $i++ )
		{
			$forum_name = $forms[$i]['forum_name'];
			$forum_order = $forms[$i]['forum_order'];
			
			$s_select .= ( $forum_order == 10 ) ? "<option value=\"5\">" . sprintf($lang['sprintf_select_before'], $forum_name) . "</option>" : '';
			$s_select .= "<option value=\"" . ( $forum_order + 5 ) . "\">" . sprintf($lang['sprintf_select_order'], $forum_name) . "</option>";
		}
		
		$s_select .= '</select>';
	}
	else
	{
		$s_select = $lang['no_entry'];
	}
		
	echo $s_select;
}
else
{
	echo sprintf($lang['stf_select_format'], $lang['notice_empty_maps']);
}

?>