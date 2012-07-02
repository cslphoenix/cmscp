<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);


if ( isset($_POST['rival_name']) )
{
	$rival = str_replace("'", "\'", $_POST['rival_name']);
	$rival = str_replace('*', '%', strtolower($rival));
		
	if ( strlen($rival) > 0 )
	{
		$sql = "SELECT DISTINCT match_rival_name, match_rival_tag, match_rival_url, match_rival_logo FROM " . MATCH . " WHERE LOWER(match_rival_name) LIKE '%$rival%'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message(CRITICAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$tmp = $db->sql_fetchrowset($result);
		
		if ( $tmp )
		{
			$logohp = '';
			
			$cnt = count($tmp);
			$num = $cnt - 5;
			
			for ( $i = 0; $i < $cnt; $i++ )
			{
				$name	= $tmp[$i]['match_rival_name'];
				$tag	= $tmp[$i]['match_rival_tag'];
				$url	= $tmp[$i]['match_rival_url'];
				$logo	= $tmp[$i]['match_rival_logo'];
				
				if		( $url && !$logo )	{ $logohp = 'HP'; }				
				else if ( !$url && $logo )	{ $logohp = 'Logo'; }
				else if ( $url && $logo )	{ $logohp = 'HP & Logo'; }
				else	{ $logohp = ''; }
				
			#	echo '<li onClick="set_rival(\'' . $tmp[$i]['match_rival_name'] . '\'); set_infos(\'match_rival_tag\', \'' . $tmp[$i]['match_rival_tag'] . '\'); set_infos(\'match_rival_url\', \'' . $tmp[$i]['match_rival_url'] . '\'); set_infos(\'match_rival_logo\', \'' . $tmp[$i]['match_rival_logo'] . '\');">' . $tmp[$i]['match_rival_name'] . '</li>';
				echo "<li onClick=\"set_rival_name('$name'); set_infos('match_match_rival_tag', '$tag'); set_infos('match_match_rival_url', '$url'); set_infos('match_match_rival_logo', '$logo');\">$name <span style=\"font-size:10px;\">$logohp</span></li>";
				
				if ( $i == 4 )
				{
					echo '&nbsp;&raquo;&nbsp;' . sprintf($lang['sprintf_ajax_more'], $num) . '';
					break;
				}
			}
		}
		else
		{
			echo "<li  onClick=\"set_rival('$rival');\">" . $lang['new_entry'] . "</li>";
		}
		
	}
}
else
{
	echo $_POST;
}

?>