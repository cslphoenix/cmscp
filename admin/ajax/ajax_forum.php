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
	$type = ( $_POST['name'] ) ? " WHERE cat_id = " . intval($_POST['name']) : false;
	
	$sql = "SELECT * FROM " . FORUM_CAT . "$type ORDER BY cat_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
		exit;
	}
	$cats = $db->sql_fetchrowset($result);
	
	$sql = "SELECT * FROM " . FORUM . "$type ORDER BY forum_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
		exit;
	}
	$forms = $db->sql_fetchrowset($result);
	
	$s_select = '';
	
	if ( $forms )
	{
		$s_select .= "<select class=\"select\" name=\"cat_order_new\" id=\"cat_order\">";
		$s_select .= "<option selected=\"selected\">" . sprintf($lang['stf_select_format'], $lang['msg_select_order']) . "</option>";
		
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id		= $cats[$i]['cat_id'];
			$cat_name	= $cats[$i]['cat_name'];
			
			$s_forms = '';
			
			for ( $j = 0; $j < count($forms); $j++ )
			{
				$forum_cat_id	= $forms[$j]['cat_id'];
				$forum_name		= $forms[$j]['forum_name'];
				$forum_order	= $forms[$j]['forum_order'];
				
				if ( $cat_id == $forum_cat_id )
				{
					$s_forms .= ( $forum_order == 10 ) ? "<option value=\"5\">" . sprintf($lang['sprintf_select_before'], $forum_name) . "</option>" : '';
					$s_forms .= "<option value=\"" . ( $forum_order + 5 ) . "\">" . sprintf($lang['sprintf_select_order'], $forum_name) . "</option>";
				}
			}
			
			$s_select .= ( $s_forms != '' ) ? "<optgroup label=\"$cat_name\">$s_forms</optgroup>" : '';
		}
	
		$s_select .= "</select>";
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