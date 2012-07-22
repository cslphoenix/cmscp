<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['hm_maintenance']['sm_backup']		= "$root_file?mode=backup";
		$module['hm_maintenance']['sm_restore']	= "$root_file?mode=restore";
		$module['hm_maintenance']['sm_optimize']	= "$root_file?mode=optimize";
	}
	
	return;
}
else
{
	define('IN_CMS', true);
	
	$header		= true;
	$current	= 'sm_database';
	
	include("./pagestart.php");
	include("{$root_path}includes/sql_parse.php");
	
	add_lang('database');
	
	$error	= '';
	$index	= '';
	$fields	= '';
	
	$log	= SECTION_DATABASE;
	$url	= 'file';
	$file	= basename(__FILE__);
	$time	= time();
	
	$data_id	= request($url, INT);
	$confirm	= request('confirm', TXT);
	$mode		= request('mode', TXT);

	$dir_path	= "{$root_path}files/";
	$acp_title	= sprintf($lang['sprintf_head'], $lang['title']);
	
	if ( $userdata['user_level'] != ADMIN xor !$userdata['user_founder'] )
	{
		log_add(LOG_ADMIN, $log, 'auth_fail', $current);
		message(GENERAL_ERROR, sprintf($lang['msg_auth_fail'], $lang[$current]));
	}
	
	define("VERBOSE", INT);
	@set_time_limit(1200);
	
	function gzip_PrintFourChars($Val)
	{
		$return = '';
		
		for ($i = 0; $i < 4; $i ++)
		{
			$return .= chr($Val % 256);
			$Val = floor($Val / 256);
		}
		
		return $return;
	}
	
	$template->set_filenames(array(
		'body'	=> 'style/acp_database.tpl',
		'info'	=> 'style/info_message.tpl',
	));
	
	switch ( $mode )
	{
		case 'backup':
		
			$tables		= ( request('table', 4) ) ? request('table', 4) : '';
			$type		= ( request('type', 1) ) ? request('type', 1) : '';
			$download	= ( request('download', 1) ) ? request('download', 1) : '';
			$compress	= ( request('compress', 1) ) ? request('compress', 1) : '';
			$save_name	= ( $download == 'server' ) ? $type . '_' . $time : 'backup_' . $type . '_' . $db_name . '_' . date('dmY_Hi', $time);
				
			if ( !request('backupstart', 1) )
			{
				include('./page_header_admin.php');
				
				$template->assign_block_vars('backup', array());
				
				$sql = "SHOW TABLE STATUS";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$table = $db->sql_fetchrowset($result);
				
				$select = '<select class="postsmall" id="table" name="table[]" size="10" multiple="multiple">';
			
				foreach ( $table as $key => $value )
				{
					$name = str_replace($db_prefix, '', $value['Name']);
					$select .= '<option value="' . $name . '" >' . sprintf($lang['sprintf_select_format'], $value['Name']) . '</option>';
				}
				
				$select .= '</select>';
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";

				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_EXPLAIN'		=> $lang['explain'],
					'L_BACKUP'		=> $lang['data_backup'],
					'L_OPTIMIZE'	=> $lang['data_optimize'],
					'L_RESTORE'		=> $lang['data_restore'],

					'L_TYPE'		=> $lang['type'],
					'L_TYPE_FULL'	=> $lang['type_full'],
					'L_TYPE_STRU'	=> $lang['type_structure'],
					'L_TYPE_DATA'	=> $lang['type_data'],

					'L_TABLE'		=> $lang['table'],
					'L_TABLE_DEV'	=> $lang['table_dev'],
					'L_TABLE_FULL'	=> $lang['table_full'],
					'L_TABLE_MIN'	=> $lang['table_min'],

					'L_COMPRESS'	=> $lang['gzip'],
					
					'L_DOWNLOAD'		=> $lang['download'],
					'L_DOWNLOAD_FILE'	=> $lang['download_file'],
					'L_DOWNLOAD_SERVER'	=> $lang['download_server'],
					
					'S_TABLE'		=> $select,
					
					'S_OPTIMIZE'	=> check_sid("$file?mode=_optimize"),
					'S_RESTORE'		=> check_sid("$file?mode=_restore"),
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				$template->pparse("body");
				
				break;
			}
			
			if ( !$tables )
			{
				message(GENERAL_ERROR, $lang['msg_empty_tables']);
			}
			
			$do_compress = false;
				
			if ( $compress )
			{
				if ( extension_loaded("zlib") )
				{
					$do_compress = true;
				}
			}
			
			if ( $download == 'server' )
			{
				$return = "# Host: " . $config['server_name'] . "\n";
				$return .= "# Create: " . gmdate("d-m-Y H:i:s", $time) . "\n";
				$return .= "# Server Version: " . mysql_get_server_info() . "\n";
				$return .= "# PHP-Version: " . phpversion() . "\n";
				$return .= "#\n";
				$return .= "# Datenbank: `$db_name`\n";
				$return .= "#\n";
				$return .= "#\n";
				
				foreach ( $tables as $table )
				{
					$content = $tbl = $field_name = $tmp = $value = $entry = $values = '';

					$sql = "OPTIMIZE TABLE $db_prefix$table";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = "SELECT * FROM $db_prefix$table";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$content = $db->sql_fetchrowset($result);
					
					$sql = "SHOW CREATE TABLE $db_prefix$table";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					
					$tbl = $row['Table'];
					$tmp = $row['Create Table'];
					
					if ( $type == 'full' || $type == 'structure' )
					{
						$return .= "#\n";
						$return .= "# Tabellenstruktur: `$tbl`\n";
						$return .= "#\n";
						$return .= "DROP TABLE IF EXISTS `$tbl`;\n";
						$return .= "$tmp;\n";
					}

					if ( $type == 'full' || $type == 'data' )
					{                                       
						if ( $content )
						{
							$return .= "#\n";
							$return .= "# Tabellendaten: `$tbl`\n";
							$return .= "#\n";
							$return .= "INSERT INTO `$tbl` (`" . implode('`, `', array_keys($content[0])) . "`) VALUES \n(\n";
							
							$field_name = array_keys($content[0]);
							
							for ( $i = 0; $i < count($content); $i++ ) 
							{
								$entry = $content[$i];
								
								for ( $j = 0; $j < count($entry); $j++ ) 
								{
									$value[$i][] = is_numeric($entry[$field_name[$j]]) ? $entry[$field_name[$j]] : "'" . str_replace("'", "\'", $entry[$field_name[$j]]) . "'";
								}
								
								$values[] = implode(', ', $value[$i]);
							}
							
							$return .= implode("),\n(", $values);
							$return .= ");\n";
						}
					}
				}
				
				if ( $do_compress )
				{
					$handle = gzopen("./../files/$save_name.sql.gz", "w9");
					gzwrite($handle, $return);
					gzclose($handle);
				}
				else
				{
					$handle = fopen("./../files/$save_name.sql", "w+");
					fwrite($handle, $return);
					fclose($handle);
				}
				
				$msg = $lang['save_file'] . sprintf($lang['return'], check_sid("$file?mode=$mode"), $acp_title);
				
				log_add(LOG_ADMIN, $log, $mode);
				message(GENERAL_MESSAGE, $msg);
			}
			else
			{
				header("Pragma: no-cache");
				
				if ( $do_compress )
				{
					@ob_start();
					@ob_implicit_flush(0);
					header("Content-Type: application/x-gzip; name=\"$save_name.sql.gz\"");
					header("Content-disposition: attachment; filename=$save_name.sql.gz");
				}
				else
				{
					header("Content-Type: text/x-delimtext; name=\"$save_name.sql\"");
					header("Content-disposition: attachment; filename=$save_name.sql");
				}
				
				echo "# Host: " . $config['server_name'] . "\n";
				echo "# Create: " . gmdate("d-m-Y H:i:s", $time) . "\n";
				echo "# Server Version: " . mysql_get_server_info() . "\n";
				echo "# PHP-Version: " . phpversion() . "\n";
				echo "#\n";
				echo "# Datenbank: `$db_name`\n";
				echo "#\n";
				echo "#\n";
								
				foreach ( $tables as $table )
				{
				#	$content = $tbl = $field_name = $values = '';
					$content = $tbl = $field_name = $tmp = $value = $entry = $values = '';

					$sql = "OPTIMIZE TABLE $db_prefix$table";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = "SELECT * FROM $db_prefix$table";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$content = $db->sql_fetchrowset($result);
					
					$sql = "SHOW CREATE TABLE $db_prefix$table";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					
					$tbl = $row['Table'];
					$tmp = $row['Create Table'];
					
					if ( $type == 'full' || $type == 'structure' )
					{
						echo "#\n";
						echo "# Tabellenstruktur: `$tbl`\n";
						echo "#\n";
						echo "DROP TABLE IF EXISTS `$tbl`;\n";
						echo "$tmp;\n\n";
					}
					
					if ( $type == 'full' || $type == 'data' )
					{                                       
						if ( $content )
						{
							echo "#\n";
							echo "# Tabellendaten: `$tbl`\n";
							echo "#\n";
							echo "INSERT INTO `$tbl` (`" . implode('`, `', array_keys($content[0])) . "`) VALUES \n(\n";
							
							$field_name = array_keys($content[0]);
							
							for ( $i = 0; $i < count($content); $i++ ) 
							{
								$entry = $content[$i];
								
								for ( $j = 0; $j < count($entry); $j++ ) 
								{
									$value[$i][] = is_numeric($entry[$field_name[$j]]) ? $entry[$field_name[$j]] : "'" . str_replace("'", "\'", $entry[$field_name[$j]]) . "'";
								}
								
								$values[] = implode(', ', $value[$i]);
							}
							
							echo implode("),\n(", $values);
							echo ");\n";
						}
					}
				}

				if ( $do_compress )
				{
					$Size = ob_get_length();
					$Crc = crc32(ob_get_contents());
					$contents = gzcompress(ob_get_contents());
					ob_end_clean();
					echo "\x1f\x8b\x08\x00\x00\x00\x00\x00".substr($contents, 0, strlen($contents) - 4).gzip_PrintFourChars($Crc).gzip_PrintFourChars($Size);
				}
				
				log_add(LOG_ADMIN, $log, $mode);
				
				exit;
			}
			
			break;

		case 'optimize':
			
			include('./page_header_admin.php');
			
			$template->assign_block_vars('optimize', array());
	
			
			
			//
			// If has been clicked the button configure
			//
			if ( isset( $HTTP_POST_VARS['configure'] ) || isset( $HTTP_POST_VARS['show_begin_for'] ) )
		#	if ( request('configure', 1) || request('db_show_begin_for', 1) )
			{
				$sql = "UPDATE " . CONFIG . " SET config_value = '" . request('db_show_begin_for', 1) . "' WHERE config_name = 'db_show_begin_for'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				if ( isset( $HTTP_POST_VARS['configure'] ) )
			#	if ( request('configure', 1) )
				{
					//
					// Update optimize database cronfiguration
					//
					$sql = "UPDATE " . CONFIG . " SET config_value = '" . request('db_show_begin_for', 1) . "' WHERE config_name = 'db_show_begin_for'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = "UPDATE " . CONFIG . " SET config_value = '" . request('db_show_not_optimized', 1) . "' WHERE config_name = 'db_show_not_optimized'";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
			}

			if(!isset($HTTP_POST_VARS['optimize']))
		#	if ( !request('optimize', 1) )
			{
				$sql = "SHOW TABLE STATUS LIKE '%" . $config['db_show_begin_for'] . "%'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$tables = $db->sql_fetchrowset($result);
				
				$total_tbls = $total_rows = $total_size = $total_free = 0;
				
				$cnt = count($tables);
				
				for ( $i = 0; $i < $cnt; $i++ )
				{
					if ( $tables[$i]['Data_free'] != 0 || !$config['db_show_not_optimized'] )
					{
						$name		= $tables[$i]['Name'];
						$size_tbl	= $tables[$i]['Index_length'] + $tables[$i]['Data_length'];
						$size_data	= $tables[$i]['Data_free'];
						$size_free	= ( $size_data ) ? _size($size_data, 1) : "Optimize";
								
						$s_check	= ( $size_data ) ? 'checked="checked"' : "";
						
						$template->assign_block_vars('optimize._optimize_row', array(
							'NUM'		=> $i,
							'NAME'		=> $name,
							'ROWS'		=> $tables[$i]['Rows'],
							'SIZE'		=> size_round($size_tbl, 1),
							'FREE'		=> $size_free,
							
							'S_SELECT'	=> "<input type=\"checkbox\" name=\"selected_tbl[]\" id=\"check_$i\" value=\"" . $tables[$i]['Name'] . "\" $s_check>",
						));
						
						$total_tbls = $i;
						$total_rows = $total_rows + $tables[$i]['Rows'];
						$total_size = $total_size + $size_tbl;
						
						if ( $size_data )
						{
							$total_free = $total_free + $size_data;
						}
					}
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";

				if ( $i != 1 )
				{
					$select_scritp = "
						<script language=\"JavaScript\">
						// I have copied and modified a script of phpMyAdmin.net
						<!--
						function setCheckboxes(the_form, do_check)
						{
							var elts = (typeof(document.forms[the_form].elements['selected_tbl[]']) != 'undefined') ? document.forms[the_form].elements['selected_tbl[]'] : document.forms[the_form].elements = '';
							var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;
							
							if (elts_cnt)
							{
								for (var i = 0; i < elts_cnt; i++)
								{
									if (do_check == \"invert\")
									{
										elts[i].checked == true ? elts[i].checked = false : elts[i].checked = true;
									}
									else
									{
										elts[i].checked = do_check;
									}
								} // end for
							}
							else
							{
								elts.checked = do_check;
							}// end if... else
					
						return true;
						}
						//-->
						</script>
					";
				}
				else
				{
					$select_scritp = "
						<script language=\"JavaScript\">
						<!--
						function setCheckboxes(the_form, do_check)
						{
					
						}
						//-->
						</script>
					";
				}
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_EXPLAIN'		=> $lang['explain'],
					'L_BACKUP'		=> $lang['data_backup'],
					'L_OPTIMIZE'	=> $lang['data_optimize'],
					'L_RESTORE'		=> $lang['data_restore'],
				
					'S_SCRIPT'	=> $select_scritp,
					
					'L_TABLE'	=> $lang['opt_table'],
					'L_ROWS'	=> $lang['opt_rows'],
					'L_SIZE'	=> $lang['opt_size'],
					'L_STATUS'	=> $lang['opt_status'],
				
					'TOTAL_TBLS'	=> $total_tbls+1,
					'TOTAL_ROWS'	=> $total_rows,
					'TOTAL_SIZE'	=> _size($total_size, 1),
					'TOTAL_FREE'	=> _size($total_free, 1),
					
				#	"L_SHOW_NOT_OPTIMIZED" => $lang['Optimize_Show_not_optimized'],
				#	"L_SHOW_BEGIN_FOR" => $lang['Optimize_Show_begin_for'],	
				
				#	"L_STATUS" => $lang['Optimize_Status'],
				#	"L_CHECKALL" => $lang['Optimize_CheckAll'],
				#	"L_UNCHECKALL" => $lang['Optimize_UncheckAll'],
				#	"L_INVERTCHECKED" => $lang['Optimize_InvertChecked'],
				#	"L_START_OPTIMIZE" => $lang['Optimize'],
				
					'S_OPTIMIZED_NO'	=> ( $config['db_show_not_optimized'] == 0 ) ? ' checked="checked"' : '',
					'S_OPTIMIZED_YES'	=> ( $config['db_show_not_optimized'] != 0 ) ? ' checked="checked"' : '',
					
					
					"S_SHOW_BEGIN_FOR" => $config['db_show_begin_for'],
					
					'S_BACKUP'		=> check_sid("$file?mode=_backup"),
					'S_RESTORE'		=> check_sid("$file?mode=_restore"),
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
					
				));

				$template->pparse('body');
			}
			else
			{
				$sql = "OPTIMIZE TABLE ";

				if ( request('selected_tbl', 4) != '' )
				{
					foreach ( request('selected_tbl', 4) as $var => $value )
					{
						if ( $value != 'on' )
						{
							$_sql[] = "`$value`";
						#	$sql .= ( $i < count(request('selected_tbl', 4)) ) ? "`$value`, " : "`$value`";
						#	$i++;
						}
					}
					
					$sql .= implode(', ', $_sql);
				}					
				$sql .= " ;";
			
				if ( !$result = $db->sql_query($sql) )
				{
					$msg = $lang['Optimize_NoTableChecked'] . sprintf($lang['return'], check_sid("$file?mode=$mode"), $acp_title);
				}
				else
				{
					$msg = $lang['Optimize_success'] . sprintf($lang['return'], check_sid("$file?mode=$mode"), $acp_title);
				}
				
				message(GENERAL_MESSAGE, $msg);
			}
			
			break;
			
		case 'restore':
		
			$template->assign_block_vars('restore', array());
				
			if ( !isset($HTTP_POST_VARS['restore_start']) )
			{
				include('./page_header_admin.php');
				
				if ( is_dir("{$root_path}files/") )
				{
					$files = array_diff(scandir("{$root_path}files/", 1), array('.', '..', '.htaccess'));
					
					if ( is_array($files) )
					{
						$select = '<select class="postsmall" name="file" id="file" size="10">';
						
						foreach ( $files as $_file )
						{
							if ( ( strpos($_file, 'full_') !== false ) && ( strpos($_file, '.sql.gz') !== false ) )
							{
								$_files = preg_replace('/^full_|.sql.gz$/', '', $_file);
								$_times = create_date($userdata['user_dateformat'], $_files, $userdata['user_timezone']);
								$_sizes = size_round(@filesize("{$root_path}files/$_file"), 2);
							
								$select .= '<option value="' . $_file . '">' . sprintf($lang['sprintf_select_format'], sprintf($lang['sprintf_empty_line'], $_times, $_sizes)) . '</option>';
								
							#	break;
							}
						}
						
						$select .= '</select>';
					}
				}
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
				
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['title']),
					'L_EXPLAIN'		=> $lang['explain'],
					'L_BACKUP'		=> $lang['data_backup'],
					'L_OPTIMIZE'	=> $lang['data_optimize'],
					'L_RESTORE'		=> $lang['data_restore'],
					
					'L_FILE'			=> $lang['file_select'],
					'L_FILE_RESTORE'	=> $lang['file_restore'],
					'S_SELECT'			=> $select,
					
					'S_BACKUP'		=> check_sid("$file?mode=_backup"),
					'S_OPTIMIZE'	=> check_sid("$file?mode=_optimize"),
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				$template->pparse('body');
				
				break;
			}
			else
			{
				$file = request('file', 2);
				$file = $dir_path . $file;
				
				if( file_exists(cms_realpath($file)) )
				{
					if( preg_match("/\.gz$/is", $file) )
					{
						$do_gzip_compress = FALSE;
						$phpver = phpversion();
						if($phpver >= "4.0")
						{
							if(extension_loaded("zlib"))
							{
								$do_gzip_compress = TRUE;
							}
						}

						if($do_gzip_compress)
						{
							$gz_ptr = gzopen($file, 'rb');
							$sql_query = "";
							while( !gzeof($gz_ptr) )
							{
								$sql_query .= gzgets($gz_ptr, 100000);
							}
						}
						else
						{
							message(GENERAL_ERROR, $lang['Restore_Error_decompress']);
						}
					}
					else
					{
						$sql_query = file($file);
					}
				}
				else
				{
					message(GENERAL_ERROR, $lang['Restore_Error_uploading']);
				}

				if($sql_query != "")
				{
					// Strip out sql comments...
					$sql_query = remove_remarks($sql_query);
					$pieces = split_sql_file($sql_query, ";");

					$sql_count = count($pieces);
					for($i = 0; $i < $sql_count; $i++)
					{
						$sql = trim($pieces[$i]);

						if(!empty($sql) and $sql[0] != "#")
						{
							if(VERBOSE == 1)
							{
								echo "Executing: $sql\n<br>";
								flush();
							}

							$result = $db->sql_query($sql);

							if(!$result && ( !(SQL_LAYER == 'postgresql' && eregi("drop table", $sql) ) ) )
							{
								message(GENERAL_ERROR, "Error importing backup file", "", __LINE__, __FILE__, $sql);
							}
						}
					}
				}

				include('./page_header_admin.php');

				$message = $lang['Restore_success'];

				$template->assign_vars(array(
					"MESSAGE_TITLE" => $lang['Database_Utilities'] . " : " . $lang['Restore'],
					"MESSAGE_TEXT" => $message)
				);
				
				message(GENERAL_MESSAGE, $message);

				$template->pparse('body');
				
				break;
			}
				
			break;
		}
}

include('./page_footer_admin.php');

?>