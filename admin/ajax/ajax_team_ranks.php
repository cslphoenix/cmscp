<?php

header('content-type: text/html; charset=UTF-8');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['smode']) )
{
	$s_select = '';
	
	if ( $_POST['smode'] == 'ranks' )
	{
		$sql = "SELECT * FROM " . RANKS . " WHERE rank_type = " . RANK_TEAM . " ORDER BY rank_special DESC, rank_order ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
			exit;
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
				$s_select .= "<option value=\"" . $value['rank_id'] . "\">" . sprintf($lang['STF_SELECT_FORMAT'], sprintf($lang['STF_SELECT_RANK_SET'], $value['rank_name'])) . "</option>";
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