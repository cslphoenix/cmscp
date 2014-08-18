<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_SERVER);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_SERVER;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request('id', INT);	
$mode	= request('mode', TXT);

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
$tmp = $db->sql_fetchrowset($result);
#$server = _cached($sql, 'data_server');

if ( $tmp && !$mode )
{
	$template->assign_block_vars('list', array());
	
#	$page_title = $lang['server'];
	
	main_header();

	if ( !$tmp )
	{
		$template->assign_block_vars('list.empty_game', array());
		$template->assign_block_vars('list.empty_voice', array());
	}
	else
	{
		$game = $voice = $online = array();
			
		foreach ( $tmp as $row )
		{
			if ( !$row['server_type'] )
			{
				$game[] = $row;
			}
			else
			{
				$voice[] = $row;
			}
			
			if ( $row['server_live'] )
			{
				$online[] = array('type' => $row['server_game'], 'host' => $row['server_ip'] . ':' . $row['server_port'], 'id' => $row['server_id']);
			}
		}
		
	#	debug($online);
		
		include($root_path . 'includes/class_gameq.php');
		
	#	$gq = new GameQ(); // or $gq = GameQ::factory();
	#	$gq->setOption('timeout', 1); // Seconds
	#	$gq->setOption('debug', TRUE);
	#	$gq->setFilter('normalise');
	#	$gq->addServers($online);
	#	$gq_serv = $gq->requestData();
		$gq_serv = cached_gameq($online, 'data_servers', 240);
		
	#	$gq = new GameQ();
	#	$gq->addServers($ary);
	#	$gq->setOption('timeout', 200);
	#	$serv = $gq->requestData();
	
	#	debug($gq_serv);
	
		if ( !$game )
		{
			$template->assign_block_vars('list.entry_empty_game', array());
		}
		else
		{
			foreach ( $game as $row )
		#	for ( $i = 0; $i < count($game); $i++ )
			{
				$id		= $row['server_id'];
				$name	= $row['server_name'];
				
				$template->assign_block_vars('list.game_row', array(
			#		'CLASS'		=> ( $i % 2 ) ? 'row1r' : 'row2r',
					
			#		'GAME'		=> display_gameicon($new[$i]['game_size'], $new[$i]['game_image']),
					'NAME'		=> "<a href=\"" . check_sid("$file?mode=view&id=$id") . "\" >$name</a>",
					
			#		'USERS'		=> ( isset($gq_serv[$id]['gq_online']) && $gq_serv[$id]['gq_online'] ) ? 'Online' : 'Offline',
					'STATUS'	=> ( isset($gq_serv[$id]['gq_online']) && $gq_serv[$id]['gq_online'] ) ? 'Online' : 'Offline',
				));
			}
		}
		
		if ( !$voice )
		{
			$template->assign_block_vars('list._entry_empty_voice', array());
		}
		else
		{
			foreach ( $voice as $row )
		#	for ( $i = 0; $i < count($voice); $i++ )
			{
				$id		= $row['server_id'];
				$name	= $row['server_name'];
				
				$template->assign_block_vars('list.voice_row', array(
			#		'CLASS'		=> ( $i % 2 ) ? 'row1r' : 'row2r',
					
			#		'GAME'		=> display_gameicon($old[$i]['game_size'], $old[$i]['game_image']),
					'NAME'		=> "<a href=\"" . check_sid("$file?mode=view&id=$id") . "\" >$name</a>",
			#		'DATE'		=> create_date($userdata['user_dateformat'], $old[$i]['match_date'], $userdata['user_timezone']),
					
			#		'CSS'		=> $css,
					'STATUS'	=> ( isset($gq_serv[$id]['gq_online']) && $gq_serv[$id]['gq_online'] ) ? 'Online' : 'Offline',
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
	include($root_path . 'includes/class_gameq.php');
	
	$template->assign_block_vars('view', array());
	
	foreach ( $tmp as $row )
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

#	$live[] = array('type' => $row['server_game'], 'host' => $row['server_ip'] . ':' . $row['server_port'], 'id' => $row['server_id']);
	
	main_header();
	
#	$serv[] = array($view['server_game'], $view['server_ip'], $view['server_port']);
	$serv[] = array('type' => $view['server_game'], 'host' => $view['server_ip'] . ':' . $view['server_port'], 'id' => $view['server_id']);
	$gq = new GameQ(); // or $gq = GameQ::factory();
	$gq->setOption('timeout', 1); // Seconds
	$gq->setOption('debug', TRUE);
	$gq->setFilter('normalise');
	$gq->addServers($serv);
	$gq_server = $gq->requestData();
#	$gq_server = cached_file($gq->requestData(), "data_server_$data", 120);

#	debug($gq_server);

	function time_convert($played)
	{
		$uptime = round($played);
			
		$d = floor($uptime / 86400);
		$h = $uptime - ($d * 86400);
		$h = floor($h / 3600);
		$m = $uptime - ($h * 3600);
		$m = floor($m / 60);
		$s = $uptime - (($h * 3600) + ($m * 60));
		
		$h = ( $h < 10 ) ? 0 . $h : $h;
		$m = ( $m < 10 ) ? 0 . $m : $m;
		$s = ( $s < 10 ) ? 0 . $s : $s;
		
		return sprintf('%s:%s:%s', $h, $m, $s);
	}
	
	$cl = $gq_server[$view['server_id']]['teams'];
	$pl = $gq_server[$view['server_id']]['players'];
	
	$chan = $gq_server[$view['server_id']]['teams'];
	
#	debug($gq_server);

	$server = $gq_server[$view['server_id']];
	
#	debug($gq_server);
	
	
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
			
			$template->assign_block_vars('view.channel', array('CHANNEL' => $channel));
			
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
						
						$template->assign_block_vars('view.channel.user', array('USER' => $user));
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
					
					$template->assign_block_vars('view.channel.subchannel', array('CHANNEL' => $subchannel));
					
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
								
								$template->assign_block_vars('view.channel.subchannel.user', array('USER' => $user));
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
	else if ( $view['server_game'] == 'teamspeak3' )
	{
		$template->assign_block_vars('view.ts3', array());
		
		$channels = $gq_server[$view['server_id']]['teams'];
		$players = $gq_server[$view['server_id']]['players'];
		$servergroups = $gq_server[$view['server_id']]['servergroups'];
		$channelgroups = $gq_server[$view['server_id']]['channelgroups'];
		
	#	debug($channels);
	#	debug($players);
	#	debug($servergroups);
	#	debug($channelgroups);
		
		foreach ( $channelgroups as $row )
		{
			$channelgroup[$row['cgid']] = array(
				'cgid'	=> $row['cgid'],
				'name'	=> $row['name'],
				'icon'	=> $row['iconid'],
			);
		}
		
		foreach ( $servergroups as $row )
		{
			$servergroup[$row['sgid']] = array(
				'sgid'	=> $row['sgid'],
				'name'	=> $row['name'],
				'icon'	=> $row['iconid'],
			);
		}
				
		foreach ( $channels as $row )
		{
			if ( !$row['pid'] )
			{
				$channel[$row['cid']] = array(
					'c_id'		=> $row['cid'],	// channel id
					'p_id'		=> $row['pid'],	// parent id
					'c_order'	=> $row['channel_order'], // channel order
					'c_name'	=> $row['channel_name'], // channel name
					'c_topic'	=> $row['channel_topic'], // channel topic
					'c_pass'	=> $row['channel_flag_password'],
					'c_type'	=> array(
						'c_default'	=> $row['channel_flag_default'],
						'c_tpower'	=> $row['channel_needed_talk_power'],
					),
				);
			}
			else
			{
				$subchannel[$row['pid']][] = array(
					'c_id'		=> $row['cid'],	// channel id
					'p_id'		=> $row['pid'],	// parent id
					'c_order'	=> $row['channel_order'], // channel order
					'c_name'	=> $row['channel_name'], // channel name
					'c_topic'	=> $row['channel_topic'], // channel topic
					'c_pass'	=> $row['channel_flag_password'],
					'c_type'	=> array(
						'c_default'	=> $row['channel_flag_default'],
						'c_tpower'	=> $row['channel_needed_talk_power'],
					),
					
				);
			}
		}
		
		foreach ( $players as $row )
		{
			$player[$row['cid']][] = array(
				'c_id'		=> $row['cid'],
				'u_name'	=> $row['gq_name'],
				'u_away'	=> $row['client_away'],
				'm_input'	=> $row['client_input_muted'],
				'm_output'	=> $row['client_output_muted'],
				'm_hinput'	=> $row['client_input_hardware'],
				'm_houtput'	=> $row['client_output_hardware'],
				'u_groups'	=> $row['client_servergroups'],
				'u_channel'	=> $row['client_channel_group_id'],
			);
		}
		
	#	debug($channel);
		
		function ts_channelicon($ary)
		{
			
		}
		
		function ts_img($img, $name = '')
		{
			return '<img src="./images/teamspeak/' . $img . '" alt="' . $name . '" border="0">&nbsp;';
		}
		
		function ts_user($away, $m_input, $m_output, $m_hinput, $m_houtput)
		{
			if ( $away )
			{
				return '<img src="./images/teamspeak/16x16_away.png" alt="" border="0">&nbsp;';
			}
			else
			{
				if ( $m_input && !$m_output && $m_hinput && $m_houtput )
				{
					return '<img src="./images/teamspeak/16x16_input_muted.png" alt="" border="0">&nbsp;';
				}
				else if ( !$m_input && $m_output && $m_hinput && $m_houtput )
				{
					return '<img src="./images/teamspeak/16x16_output_muted.png" alt="" border="0">&nbsp;';
				}
				else if ( !$m_input && !$m_output && !$m_hinput && $m_houtput )
				{
					return '<img src="./images/teamspeak/16x16_hardware_input_muted.png" alt="" border="0">&nbsp;';
				}
				else if ( !$m_input && !$m_output && $m_hinput && !$m_houtput )
				{
					return '<img src="./images/teamspeak/16x16_hardware_output_muted.png" alt="" border="0">&nbsp;';
				}
				else
				{
					return '<img src="./images/teamspeak/16x16_player_off.png" alt="" border="0">&nbsp;';
				}
			}
		}
		
		function ts_user_icons($in_group, $in_channel)
		{
			global $servergroup, $channelgroup;
			
			$explode = explode(',', $in_group);
			$grps = array();
			
			if ( in_array($in_channel, array_keys($channelgroup)) )
			{
				if ( in_array($channelgroup[$in_channel]['icon'], array(100, 200, 300, 500, 600)) )
				{
					$grps[] = '<img src="./images/teamspeak/group_' . $channelgroup[$in_channel]['icon'] . '.png" alt="" title="' . $channelgroup[$in_channel]['name'] . '" border="0">';
				}
				else if ( $channelgroup[$in_channel]['icon'] != 0 )
				{
					$grps[] = '<img src="./images/teamspeak/group_custom.png" alt="" title="' . $channelgroup[$in_channel]['name'] . '" border="0">';
				}
			}
		
			foreach ( $explode as $row )
			{
				if ( in_array($row, array_keys($servergroup)) )
				{
					if ( in_array($servergroup[$row]['icon'], array(100, 200, 300, 500, 600)) )
					{
						$grps[] = '<img src="./images/teamspeak/group_' . $servergroup[$row]['icon'] . '.png" alt="" title="' . $servergroup[$row]['name'] . '" border="0">';
					}
					else if ( $servergroup[$row]['icon'] != 0 )
					{
						$grps[] = '<img src="./images/teamspeak/group_custom.png" alt="" title="' . $servergroup[$row]['name'] . '" border="0">';
					}
				}
			}
			
			return implode('', $grps);
		}
	
		$template->assign_vars(array(
			'SERV' => ts_img('16x16_server_green.png') . utf8_decode($server['gq_hostname']),
		));
		
		$lc = end(array_keys($channel));
		
		$ts_end = ts_img('16x16_tree_end.gif');
		$ts_mid = ts_img('16x16_tree_mid.gif');
		$ts_line = ts_img('16x16_tree_line.gif');
		$ts_blank = ts_img('16x16_tree_blank.png');
		
		foreach ( $channel as $ck => $c )
		{
			$tree_icon = ($lc == $ck && !isset($player[$c['c_id']])) ? $ts_end : $ts_mid;
			$chan_icon = ($c['c_pass']) ? ts_img('16x16_channel_private.png') : ts_img('16x16_channel_green.png');
			
			$template->assign_block_vars('view.ts3.channel', array(
				'CHANNEL' => $tree_icon . $chan_icon . utf8_decode($c['c_name']) . ts_channelicon($c['c_type'])
			));
			
			if ( isset($player[$c['c_id']]) )
			{
				$lu = end(array_keys($player[$c['c_id']]));
				
				foreach ( $player[$c['c_id']] as $pk => $p )
				{
					$utree_icon = $ts_line . (($lu == $pk && !isset($subchannel[$c['c_id']])) ? $ts_end : $ts_mid);
					
					$template->assign_block_vars('view.ts3.channel.user', array(
						'USER' => $utree_icon . ts_user($p['u_away'], $p['m_input'], $p['m_output'], $p['m_hinput'], $p['m_houtput']) . $p['u_name'] . ts_user_icons($p['u_groups'], $p['u_channel'])
					));
				}
			}
			
			if ( isset($subchannel[$c['c_id']]) )
			{
				$ls = end(array_keys($subchannel[$c['c_id']]));
				
				foreach ( $subchannel[$c['c_id']] as $sk => $s )
				{
					$stree_icon = (($lc == $ck) ? $ts_blank : $ts_line) . (($ls == $sk && !isset($player[$sk])) ? $ts_end : $ts_mid);
					$schan_icon = ($s['c_pass']) ? ts_img('16x16_channel_private.png') : ts_img('16x16_channel_green.png');
					
					$template->assign_block_vars('view.ts3.channel.subchannel', array(
						'CHANNEL' => $stree_icon . $schan_icon . utf8_decode($s['c_name']) . ts_channelicon($s['c_type'])
					));
					
					if ( isset($player[$s['c_id']]) )
					{
						$lsu = end(array_keys($player[$s['c_id']]));
						
						foreach ( $player[$s['c_id']] as $suk => $us )
						{
						#	if ( $lsu == $suk && isset($subchannel[$sk]) )
						#	{
						#		$sutree_icon = $ts_line . $ts_line . $ts_end;
						#	}
						#	else
						#	{
						#		$sutree_icon = $ts_line . $ts_blank . $ts_mid;
						#	}
							
							$sutree_icon = $ts_line . (($ls == $sk) ? $ts_blank : $ts_line) . (($lsu == $suk && !isset($player[$sk])) ? $ts_end : $ts_mid);
							
							$template->assign_block_vars('view.ts3.channel.subchannel.user', array(
								'USER' => $sutree_icon . ts_user($us['u_away'], $us['m_input'], $us['m_output'], $us['m_hinput'], $us['m_houtput']) . $us['u_name'] . ts_user_icons($us['u_groups'], $us['u_channel'])
							));
						}
					}
				}
			}
		}
	}
	else
	{
	#	debug($gq_server, 'server');
		
	#	foreach ( $pl as $row )
	#	{
	#		debug(time_convert($row['time']));
	#	}
	
	#	debug($gq_server[$view['server_id']], 'server');
		
		$players = $gq_server[$view['server_id']]['players'];
		
	#	debug($players);
		
		foreach ( $players as $p )
		{
			$player[] = array(
				'name'	=> $p['name'],
				'score'	=> $p['score'],
				'time'	=> round($p['time']),
			);
		}
		
		$players = $player;
		
		$details = array(
			'gq_address'	=> $gq_server[$view['server_id']]['gq_address'],
			'gq_port'		=> $gq_server[$view['server_id']]['gq_port'],
            'gq_hostname'	=> $gq_server[$view['server_id']]['gq_hostname'],
            'gq_mapname'	=> $gq_server[$view['server_id']]['gq_mapname'],
            'gq_maxplayers'	=> $gq_server[$view['server_id']]['gq_maxplayers'],
            'gq_mod'		=> $gq_server[$view['server_id']]['gq_mod'],
            'gq_numplayers'	=> $gq_server[$view['server_id']]['gq_numplayers'],
            'gq_online'		=> $gq_server[$view['server_id']]['gq_online'],
            'gq_password'	=> $gq_server[$view['server_id']]['gq_password'],
            'gq_protocol'	=> $gq_server[$view['server_id']]['gq_protocol'],
            'gq_type'		=> $gq_server[$view['server_id']]['gq_type'],
			'gq_nextmap'	=> $gq_server[$view['server_id']]['sm_nextmap'],
		);
		
		$template->assign_block_vars('view.' . $details['gq_mod'], array());
		
		switch ( $details['gq_mod'] )
		{
			case 'cstrike': $protocol = sprintf('steam://connect/%s:%s', $details['gq_address'], $details['gq_port']); break;
		}
		
	#	debug($players);
		debug($details);
	
		$template->assign_vars(array(
			'L_DETAILS' => $lang['server_details'],
			
			'L_HOSTNAME'	=> $lang['server_hostname'],
			'L_ADDRESS' 	=> $lang['server_address'],
			'L_JOIN' 		=> $lang['server_join'],
			'L_MAP'			=> $lang['server_map'],
			'L_NEXTMAP'		=> $lang['server_nextmap'],
			'L_PLAYERS' 	=> $lang['server_players'],
			
			'HOSTNAME'	=> $details['gq_hostname'],
			'ADDRESS' 	=> sprintf('%s:%s', $details['gq_address'], $details['gq_port']),
			'JOIN'		=> '<a href="' . $protocol . '">connect</a>',
			'MAP'		=> $details['gq_mapname'],
			'NEXTMAP'	=> $details['gq_nextmap'],
			'PLAYERS' 	=> sprintf('%s / %s', $details['gq_numplayers'], $details['gq_maxplayers']),
			
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