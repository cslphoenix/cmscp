<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_SERVER);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', 0) ) ? request('start', 0) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_SERVER;
$url	= POST_SERVER;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, 0);	
$mode	= request('mode', 1);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_server.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'error_body.tpl',
));

$sql = "SELECT * FROM " . SERVER;
if ( !($result = $db->sql_query($sql)) )
{
	message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
}
$server = $db->sql_fetchrowset($result);
#$server = _cached($sql, 'data_server');

if ( $mode == '' )
{
	$template->assign_block_vars('_list', array());
	
#	$page_title = $lang['server'];
	
	main_header();

	if ( !$server )
	{
		$template->assign_block_vars('_list._entry_empty_game', array());
		$template->assign_block_vars('_list._entry_empty_voice', array());
	}
	else
	{
		include($root_path . 'includes/server/gameq/GameQ.php');
		
		$game = $voice = $ary = array();
			
		foreach ( $server as $keys => $row )
		{
			if ( $row['server_type'] == TYPE_GAME )
			{
				$game[] = $row;
			}
			else if ( $row['server_type'] == TYPE_VOICE )
			{
				$voice[] = $row;
			}
			
			if ( $row['server_live'] == '1' )
			{
				$ary[$row['server_id']] = array($row['server_game'], $row['server_ip'], $row['server_port']);
			}
		}
		
		$gq = new GameQ();
		$gq->addServers($ary);
		$gq->setOption('timeout', 200);
		$serv = $gq->requestData();
		
		if ( !$game )
		{
			$template->assign_block_vars('_list._entry_empty_game', array());
		}
		else
		{
			for ( $i = 0; $i < count($game); $i++ )
			{
				$server_id	= $game[$i]['server_id'];
				$name		= $game[$i]['server_name'];
			
				$template->assign_block_vars('_list._game_row', array(
					'CLASS'		=> ( $i % 2 ) ? 'row1r' : 'row2r',
					
			#		'GAME'		=> display_gameicon($new[$i]['game_size'], $new[$i]['game_image']),
					'NAME'		=> "<a href=\"" . check_sid("$file?mode=view&$url=$server_id") . "\" >$name</a>",
					
					'STATUS'	=> ( isset($serv[$server[$i]['server_id']]['gq_online']) ) ? 'Online' : 'Offline',
				));
			}
		}
		
		if ( !$voice )
		{
			$template->assign_block_vars('_list._entry_empty_voice', array());
		}
		else
		{
			for ( $i = 0; $i < count($voice); $i++ )
			{
				$server_id	= $voice[$i]['server_id'];
				$name		= $voice[$i]['server_name'];
			
				$template->assign_block_vars('_list._voice_row', array(
					'CLASS'		=> ( $i % 2 ) ? 'row1r' : 'row2r',
					
			#		'GAME'		=> display_gameicon($old[$i]['game_size'], $old[$i]['game_image']),
					'NAME'		=> "<a href=\"" . check_sid("$file?mode=view&$url=$server_id") . "\" >$name</a>",
			#		'DATE'		=> create_date($userdata['user_dateformat'], $old[$i]['match_date'], $userdata['user_timezone']),
					
			#		'CSS'		=> $css,
					'STATUS'	=> ( isset($serv[$server[$i]['server_id']]['gq_online']) ) ? 'Online' : 'Offline',
				));
			}
		}
	}
	
	$template->assign_vars(array(
#		'L_DETAILS'		=> $lang['match_details'],
#		'L_TEAMS'		=> $lang['teams'],
		'L_GAMESERVER'	=> 'Gameserver',
		'L_VOICESERVER'	=> 'Voiceserver',
#		'L_GOTO_PAGE'	=> $lang['Goto_page'],
#		
	));
}
else if ( $mode == 'view' && $data )
{
	include($root_path . 'includes/server/gameq/GameQ.php');
	
	$template->assign_block_vars('_view', array());
	
	foreach ( $server as $key => $row )
	{
		if ( $row['server_id'] == $data )
		{
			$view = $row;
		}
	}
	
	if ( !$view )
	{
		message(GENERAL_ERROR, $lang['msg_server_fail']);
	}
	
#	$page_title = sprintf($lang['server_head_info'], $view['match_rival_name']);
	
	main_header();
	
	$serv[] = array($view['server_game'], $view['server_ip'], $view['server_port']);
	$gq = new GameQ();
	$gq->addServers($serv);
	$gq->setOption('timeout', 200);
	$gq->setFilter('normalise');
	$gq->setFilter('sortplayers', 'gq_name');
	$serv = $gq->requestData();
	
	$cl = $serv[0]['teams'];
	$pl = $serv[0]['players'];
	
	$chan = $serv[0]['teams'];
	
	if ( $view['server_game'] == 'ts2' )
	{
		$img_path = $root_path . 'images/teamspeak/';
		
		$channels = $subchannels = $sl_players = $cl_players = '';

		foreach ( $cl as $keys => $rows )
		{
			if ( $rows['parent'] == -1 )
			{
				$channels[]		= $rows;
				$channel_id[]	= $rows['c_id'];
				$sort_name[]	= $rows['name'];
				$sort_order[]	= $rows['order'];
			}
			else
			{
				$subchannels[]		= $rows;
				$subchannel_id[]	= $rows['c_id'];
				$subsort_name[]		= $rows['name'];
				$subsort_order[]	= $rows['order'];
			}
		}
		
		foreach ( $pl as $keys => $rows )
		{
			if ( in_array($rows['c_id'], $channel_id) )
			{
				$cl_players[] = $rows;
			}
			else if ( in_array($rows['c_id'], $subchannel_id) )
			{
				$sl_players[] = $rows;
			}
		}
		
		array_multisort($sort_order, SORT_ASC, $sort_name, SORT_ASC, $channels);
		array_multisort($subsort_order, SORT_ASC, $subsort_name, SORT_ASC, $subchannels);
		
		$cnt_cl	= count($channels);
		$cnt_sl	= count($subchannels);		
		$cnt_cl_players	= count($cl_players);
		$cnt_sl_players = count($sl_players);
		
		$serv_pwd	= $serv[0]['gq_password'];
		$pwd		= $view['server_pw'];
		
		for ( $i = 0; $i < $cnt_cl; $i++ )
		{
			$name = trim($channels[$i]['name'], '"');
			$spwd = $serv_pwd ? "?password=$pwd" : '';
			
			$channel = '';
			$channel .= ( !$channels[$i]['pwd'] ) ? '<img src="./images/teamspeak/channel.gif" alt="" class="icon">' : '<img src="./images/teamspeak/channel_close.gif" alt="" class="icon">';
			$channel .= '<a href="teamspeak://' . $view['server_ip'] . ':' . $view['server_port'] . '?nickname=' . $userdata['user_name'] . '?channel=' . rawurlencode($name) . $spwd . '">' . $name . '</a>';
			$channel .= ' (' . ts_cflags($channels[$i]['flags']) . ')';
			
			$template->assign_block_vars('_view.channel', array('CHANNEL' => $channel));
			
			if ( $cl_players )
			{
				for ( $j = 0; $j < $cnt_cl_players; $j++ )
				{
					if ( $channels[$i]['c_id'] == $cl_players[$j]['c_id'] )
					{
						$user = '';
						$user .= '<img src="./images/teamspeak/blank.gif" alt="" class="icon">';
						$user .= trim($cl_players[$j]['name'], '"');
						$user .= ' (' . ts_pright($cl_players[$j]['p_right']) . ts_cright($cl_players[$j]['c_right'], $cl_players[$j]['flags']) . ')';
						
						$template->assign_block_vars('_view.channel.user', array('USER' => $user));
					}
				}// primär players
			}
			
			// sub channels
			for ( $k = 0; $k < $cnt_sl; $k++ )
			{
				if ( $channels[$i]['c_id'] == $subchannels[$k]['parent'] )
				{
					$name = trim($subchannels[$k]['name'], '"');
					
					$subchannel = '';
					$subchannel .= '<img src="./images/teamspeak/blank.gif" alt="" class="icon">';
					$subchannel .= ( !$subchannels[$k]['pwd'] ) ? '<img src="./images/teamspeak/channel.gif" alt="" class="icon">' : '<img src="./images/teamspeak/channel_close.gif" alt="" class="icon">';
					$subchannel .= '<a href="teamspeak://' . $view['server_ip'] . ':' . $view['server_port'] . '?nickname=' . $userdata['user_name'] . '?channel=' . rawurlencode($name) . $spwd . '">' . $name . '</a>';
				#	$subchannel .= trim($subchannels[$k]['name'], '"');
					
					$template->assign_block_vars('_view.channel.subchannel', array('CHANNEL' => $subchannel));
					
					if ( $sl_players )
					{
						// sub players
						for ( $l = 0; $l < $cnt_sl_players; $l++ )
						{
							if ( $subchannels[$k]['c_id'] == $sl_players[$l]['c_id'] )
							{
								$user = '';
								$user .= '<img src="./images/teamspeak/blank.gif" alt="" class="icon"><img src="./images/teamspeak/blank.gif" alt="" class="icon">';
								$user .= trim($sl_players[$l]['name'], '"');
								$user .= ' (' . ts_pright($sl_players[$l]['p_right']) . ts_cright($sl_players[$l]['c_right'], $sl_players[$l]['flags']) . ')';
								
								$template->assign_block_vars('_view.channel.subchannel.user', array('USER' => $user));
							}
						}// sub players
					}
				}
			}// sub channels
		}
		
		$pwd = $serv_pwd ? '<img src="./images/teamspeak/channel_close.gif" alt="" class="icon" alt="">' : '<img src="./images/teamspeak/channel.gif" alt="" class="icon" alt="">';
		
		$template->assign_vars(array(
			'SERV'	=>  $pwd . $serv[0]['gq_hostname'],
		));
	}
}
else
{
	redirect(check_sid($file, true));
}

$template->pparse('body');

main_footer();

?>