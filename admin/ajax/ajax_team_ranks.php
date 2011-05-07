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
	$s_select = '';
	
	if ( $_POST['name'] == '_user_setrank' )
	{
		$sql = "SELECT * FROM " . RANKS . " WHERE rank_type = " . RANK_TEAM . " ORDER BY rank_special DESC, rank_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$rank = $db->sql_fetchrowset($result);
	
		$s_select .= "<select class=\"selectsmall\" name=\"rank_id\" id=\"rank_id\">";
		$s_select .= "<option value=\"\">" . sprintf($lang['sprintf_select_format'], $lang['msg_select_ranks']) . "</option>";
		
		foreach ( $rank as $info => $value )
		{
			$s_select .= "<option value=\"" . $value['rank_id'] . "\">" . sprintf($lang['sprintf_select_format'], sprintf($lang['msg_select_rank_set'], $value['rank_name'])) . "</option>";
		}
		
		$s_select .= "</select>";
	}
	
	echo $s_select;
}
else
{
	echo sprintf($lang['sprintf_select_format'], $lang['msg_empty_ranks']);
}

?>