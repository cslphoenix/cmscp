<?php

function _cached($sql, $sCacheName, $rows = 0, $time = '')
{
	/*
		@param string $sql		enthält die SQL Abfrage
		@param string $name		enthält den Namen der Cachdatei
		@param int $row			sql_fetchrow/sql_fetchrowset
		@param int $time		Lebensdauer der Cachdatei
	
		@return string
	*/
	
	global $db, $oCache;
	
#	debug($sql, $sCacheName);
	
	if ( defined('CACHE') )
	{
		if ( ( $data = $oCache->readCache($sCacheName) ) === false )
		{
			if ( !($result = $db->sql_query($sql)) )
			{
				message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
			}
			
			$data = ( $rows == '1' ) ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
			
			if ( $data != NULL )
			{
			#	debug($data, $sCacheName, true);
				( $time != '' ) ? $oCache->writeCache($sCacheName, $data, (int) $time) : $oCache->writeCache($sCacheName, $data);
			}
			
			$db->sql_freeresult($result);
		}
	}
	else
	{
		if ( !($result = $db->sql_query($sql)) )
		{
			message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		}
		$data = $rows ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
	}
	
	return $data;
}

function cached($type, $data, $name, $ary = '', $time = '')
{
	/*
		@param string $type		enthï¿½lt die Type (SQL, Daten)
		@param string $data		enthï¿½lt die SQL Abfrage oder Daten die gespeichert werden sollen
		@param string $name		enthï¿½lt den Namen der Cachdatei
		@param int $ary			sql_fetchrow/sql_fetchrowset
		@param int $time		Lebensdauer der Cachdatei
	
		@return SQL Daten oder Daten die gespeichert worden
	*/
	
	global $db, $oCache;
	
	if ( defined('CACHE') )
	{
		switch ( $type )
		{
			case 'sql':
			
				if ( ( $tmp = $oCache->readCache($name) ) === false )
				{
					if ( !($result = $db->sql_query($data)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $data);
					}
					
					$tmp = ( $ary == '1' ) ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
					
					( $time != '' ) ? $oCache->writeCache($name, $tmp, (int) $time) : $oCache->writeCache($name, $tmp);
					
					$db->sql_freeresult($result);
				}
				
				break;
		
			case 'ary':
			
				if ( ( $tmp = $oCache->readCache($name) ) === false )
				{
					$tmp = $data;
						
					( $time != '' ) ? $oCache->writeCache($name, $tmp, (int) $time) : $oCache->writeCache($name, $tmp);
				}
				
				break;
		}
	}
	else
	{
		switch ( $type )
		{
			case 'sql':
			
				if ( !($result = $db->sql_query($data)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				
				$tmp = ( $ary == '1' ) ? $db->sql_fetchrow($result) : $db->sql_fetchrowset($result);
				
				$db->sql_freeresult($result);
			
				break;
		
			case 'ary':
			
				$tmp = $data;

				break;
		}
	}
	
	return $tmp;
}

function cached_gameq($online, $sCacheName, $time = '')
{
	global $oCache;
	
	if ( ( $sContent = $oCache->readCache($sCacheName) ) === false )
	{
		$gq = new GameQ(); // or $gq = GameQ::factory();
		$gq->setOption('timeout', 1); // Seconds
	#	$gq->setOption('debug', TRUE);
		$gq->setFilter('normalise');
		$gq->addServers($online);
		$sContent = $gq->requestData();
		
		$time = ( $time != '' ) ? $oCache->writeCache($sCacheName, $sContent, (int) $time) : $oCache->writeCache($sCacheName, $sContent);
	}
	
	return $sContent;
}

function cached_file($tmp, $sCacheName, $time = '')
{
	/***

	@param string $ary		enthï¿½lt das Array
	@param string $name		enthï¿½lt den Namen der Cachdatei
	@param int $time		Lebensdauer der Cachdatei
	
	@return string
	
	***/
	
	global $oCache;
	
	if ( ( $data = $oCache->readCache($sCacheName) ) === false )
	{
		$data = $tmp;
		$time = ( $time != '' ) ? $oCache->writeCache($sCacheName, $data, (int) $time) : $oCache->writeCache($sCacheName, $data);
	}
	
	return $data;
}

function _cache_clear()
{
	global $oCache;
	
	$oCache->truncateCache();
}

?>