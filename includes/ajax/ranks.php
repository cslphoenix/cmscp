<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['mode']) )
{
	$s_select = '';
	
	if ( $_POST['mode'] == '_user_setrank' )
	{
		$sql = "SELECT * FROM " . RANKS . " WHERE rank_type = " . RANK_TEAM . " ORDER BY rank_special DESC, rank_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp = $db->sql_fetchrowset($result);
		
		if ( !$tmp )
		{
			$s_select = sprintf($lang['STF_SELECT_FORMAT'], $lang['msg_empty_ranks']);
		}
		else
		{
			$s_select .= "<select name=\"rank_id\" id=\"rank_id\">";
			$s_select .= "<option value=\"\">" . sprintf($lang['STF_SELECT_FORMAT'], $lang['NOTICE_SELECT_RANK']) . "</option>";
			
			foreach ( $tmp as $info => $value )
			{
				$s_select .= "<option value=\"" . $value['rank_id'] . "\">" . sprintf($lang['STF_SELECT_FORMAT'], sprintf($lang['msg_select_rank_set'], $value['rank_name'])) . "</option>";
			}
			
			$s_select .= "</select>";
		}
	}
	else
	{
		$s_select = '';
	}
	
	echo $s_select;
}
else
{
	echo '';
}

?>