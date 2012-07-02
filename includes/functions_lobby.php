<?php

function ucp_read($type, $ary, $user)
{
	global $db, $userdata;
	
	$sql = "SELECT type_id FROM " . COMMENT_READ . " WHERE user_id = " . $userdata['user_id'] . " AND type = $type AND type_id IN (" . implode(', ', $ary) . ")";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$tmp_read = $db->sql_fetchrowset($result);
	
	if ( $tmp_read )
	{
		foreach ( $tmp_read as $key => $row )
		{
			$ary_sort[] = $row['type_id'];
		}
		
		$ary_diff = array_diff($ary, $ary_sort);
		
		if ( $ary_diff )
		{
			foreach ( $ary_diff as $key => $row )
			{
				$count[$row] = 'u';
			}
		}
		
		for ( $i = 0; $i < count($ary_sort); $i++ )
		{
			$sql = "SELECT COUNT(comment_id) AS total
						FROM " . COMMENT . " c
							LEFT JOIN " . COMMENT_READ . " cr ON c.type_id = cr.type_id
						WHERE c.type_id = " . $ary_sort[$i] . "
							AND cr.user_id = $user
							AND cr.type = $type
							AND cr.read_time < c.time_create";
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			$tmp_count = $db->sql_fetchrow($result);
			
			$count[$ary_sort[$i]] = $tmp_count['total'];
		}
	}
	else
	{
		foreach ( $ary as $key => $row )
		{
			$count[$row] = 'u';
		}
	}
	
	return $count;
}

function ucp_diff($type, $ary, $user)
{
	global $db, $userdata;
	
	$sql = "SELECT type_id FROM " . COMMENT_READ . " WHERE user_id = $user AND type = $type AND type_id IN (" . implode(', ', $ary) . ")";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$tmp_read = $db->sql_fetchrowset($result);
	
	$ary_sort = $ary_diff = $diff = '';
	
	if ( $tmp_read )
	{
		foreach ( $tmp_read as $key => $row )
		{
			$ary_sort[] = $row['type_id'];
		}
		
		$ary_diff = array_diff($ary, $ary_sort);
	}
	
	$diff = array('r' => $ary_sort, 'u' => $ary_diff);
		
	
	return $diff;
}

?>