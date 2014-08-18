<?php

header('content-type: text/html; charset=ISO-8859-1');

define('IN_CMS', true);
define('IN_ADMIN', true);

$root_path = './../../';

require($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if ( isset($_POST['string']) )
{
	$string = str_replace('*', '%', (string) strtolower($_POST['string']));
	
#	str_replace(<Muster>, <Ersetzung>, <String>)
	
	if ( strlen($string) > 0 )
	{
	#	$sql = "SELECT DISTINCT match_rival_name, match_rival_tag, match_rival_url, match_rival_logo FROM " . MATCH . " WHERE LOWER(match_rival_name) LIKE '%$rival%' or LOWER(match_rival) LIKE '%$rival%'";
		$sql = "SELECT DISTINCT * FROM " . GAMES . " WHERE game_id != '-1' AND (LOWER(game_name) LIKE '%$string%' OR LOWER(game_tag) LIKE '%$string%')";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo 'SQL Error in Line: ' . __LINE__ . ' on File: ' . __FILE__;
			exit;
		}
		$tmp = $db->sql_fetchrowset($result);
		
		if ( $tmp )
		{
			$logohp = '';
			
			for ( $i = 0; $i < count($tmp); $i++ )
			{
				$name	= $tmp[$i]['game_name'];
				$tag	= $tmp[$i]['game_tag'];
				
			#	if		( $url && !$logo )	{ $logohp = 'HP'; }				
			#	else if ( !$url && $logo )	{ $logohp = 'Logo'; }
			#	else if ( $url && $logo )	{ $logohp = 'HP & Logo'; }
				
			#	echo '<li onClick="set_rival(\'' . $tmp[$i]['match_rival_name'] . '\'); set_infos(\'match_rival_tag\', \'' . $tmp[$i]['match_rival_tag'] . '\'); set_infos(\'match_rival_url\', \'' . $tmp[$i]['match_rival_url'] . '\'); set_infos(\'match_rival_logo\', \'' . $tmp[$i]['match_rival_logo'] . '\');">' . $tmp[$i]['match_rival_name'] . '</li>';
				
				echo "<li onClick=\"set_cat('$tag');\">$tag - <i>$name</i></li>";
			}
		}
		else
		{
			echo "<li onClick=\"set_cat('$string');\">" . $lang['new_entry'] . "</li>";
		}
		
	}
}
else
{
	echo 'There should be no direct access to this script!';
}

?>