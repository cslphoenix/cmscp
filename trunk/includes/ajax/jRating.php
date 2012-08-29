<?php

define('IN_CMS', true);

$root_path = './../../';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, -1);
init_userprefs($userdata);

if( isset($_POST['action']) )
{
	if( $_POST['action'] == 'rating' )
	{
		$type	= floatval($_POST['type']);
		$typeid	= intval($_POST['idbox']);
		$value	= floatval($_POST['rate']);
		
		$sql = "INSERT INTO " . RATE . " SET
			rate_type = '$type',
			rate_type_id = '$typeid',
			rate_value = '$value',
			rate_userid = '{$userdata['user_id']}',
			rate_userip = '" . iptoint($userdata['session_ip']) . "',
			rate_time = '" . time() . "'";
		if ( !$db->sql_query($sql) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		
		// if request successful
		$success = true;
		// else $success = false;
		
		
		// json datas send to the js file
		if($success)
		{
		#	$sql = "SELECT news_id, count_comment AS count FROM " . NEWS . " WHERE news_id = '$typeid'";
		#	$sql = "SELECT rate_type_id, rate_userid, rate_value FROM " . RATE . " WHERE = '$typeid'";
			$sql = "SELECT  rate_value FROM " . RATE . " WHERE rate_type = '$type' AND rate_type_id = '$typeid'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrowset($result);
			
			$value = 0;
			$count = count($row);
			
			foreach ( $row as $rate )
			{
				$value += $rate['rate_value'];
			}
		#	$value = array_values($row['rate_value']);
			
		#	debug($count);
		#	debug($value);
			
			$return = sprintf('%s %s &oslash; %s/%s', $count, $lang['common_rating'], round(($value/$count), 1), $settings['rating_news']['maximal']);
			
		#	$aResponse = '';
		#	$aResponse['message'] = 'Your rate has been successfuly recorded. Thanks for your rate :)';
			
			// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
		#		$aResponse['server'] = '<strong>Success answer :</strong> Success : Your rate has been recorded. Thanks for your rate :)<br />';
		#		$aResponse['server'] .= '<strong>Rate received :</strong> '.$rate.'<br />';
		#		$aResponse['server'] .= '<strong>ID to update :</strong> '.$id;
			// END ONLY FOR DEMO
			
		#	$aResponse = "tesT";
		#	debug($return);
			echo json_encode($return);
		}
		else
		{
			$aResponse['error'] = true;
			$aResponse['message'] = 'An error occured during the request. Please retry';
			
			// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
				$aResponse['server'] = '<strong>ERROR :</strong> Your error if the request crash !';
			// END ONLY FOR DEMO
			
			
			echo json_encode($aResponse);
		}
	}
	else
	{
		$aResponse['error'] = true;
		$aResponse['message'] = '"action" post data not equal to \'rating\'';
		
		// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
			$aResponse['server'] = '<strong>ERROR :</strong> "action" post data not equal to \'rating\'';
		// END ONLY FOR DEMO
			
		
		echo json_encode($aResponse);
	}
}
else
{
	$aResponse['error'] = true;
	$aResponse['message'] = '$_POST[\'action\'] not found';
	
	// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
		$aResponse['server'] = '<strong>ERROR :</strong> $_POST[\'action\'] not found';
	// END ONLY FOR DEMO
	
	
	echo json_encode($aResponse);
}

$db->sql_close();
exit;