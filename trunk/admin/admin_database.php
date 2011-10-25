<?php

if ( !empty($setmodules) )
{
	$root_file = basename(__FILE__);
	
	if ( $userdata['user_level'] == ADMIN && $userdata['user_founder'] )
	{
		$module['hm_database']['sm_backup'] = "$root_file?mode=_backup";
		
		$file_uploads = (@phpversion() >= '4.0.0') ? @ini_get('file_uploads') : @get_cfg_var('file_uploads');

		if ( (empty($file_uploads) || $file_uploads != 0) && (strtolower($file_uploads) != 'off') && (@phpversion() != '4.0.4pl1') )
		{
			$module['hm_database']['sm_restore'] = "$root_file?mode=_restore";
		}
		$module['hm_database']['sm_optimize'] = "$root_file?mode=_optimize";
	}
	
	return;
	
#	db_show_begin_for
#	show_not_optimized
}
else
{
	define('IN_CMS', true);
	
	$root_path	= './../';
	$header		= true;
	$current	= 'sm_database';
	
	include('./pagestart.php');
	
	load_lang('database');

	$mode	= request('mode', 1);
	$file	= basename(__FILE__);
	$fields	= '';
	
	$acp_title	= sprintf($lang['sprintf_head'], $lang['database']);
	
	define("VERBOSE", 0);
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
<<<<<<< .mine
	} 

=======
	}
>>>>>>> .r85
	
<<<<<<< .mine
=======
	$template->set_filenames(array(
		'body' => 'style/acp_database.tpl',
	));
>>>>>>> .r85
	
<<<<<<< .mine
	
	
	
	
	$template->set_filenames(array(
		'body' => 'style/acp_database.tpl',
	));
	
	switch ( $mode )
	{
		case '_backup':
		
			if ( !(request('submit', 1)) )
=======
	switch ( $mode )
	{
		case '_backup':
		
			if ( !(request('submit', 1)) )
>>>>>>> .r85
			{
				include('./page_header_admin.php');
				
				$template->assign_block_vars('_backup', array());
				
				$fields .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";

				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['database']),
					'L_EXPLAIN'		=> $lang['explain'],
					'L_BACKUP'		=> $lang['backup'],
					'L_OPTIMIZE'	=> $lang['optimize'],					
					'L_RESTORE'		=> $lang['restore'],
					
					'L_TYPE'		=> $lang['type'],
					'L_TYPE_FULL'	=> $lang['type_full'],
					'L_TYPE_STRU'	=> $lang['type_structure'],
					'L_TYPE_DATA'	=> $lang['type_data'],
					
					'L_TABLE'		=> $lang['table'],
					'L_TABLE_DEV'	=> $lang['table_dev'],
					'L_TABLE_FULL'	=> $lang['table_full'],
					'L_TABLE_MIN'	=> $lang['table_min'],
					
					'L_TABLES_ADD'	=> $lang['table_add'],
					'L_TABLES_ZIP'	=> $lang['table_zip'],
					
					'L_DOWNLOAD'	=> $lang['download'],
					'L_DL_FILE'		=> $lang['dl_file'],
					'L_DL_BOTH'		=> $lang['dl_both'],
					'L_DL_SERV'		=> $lang['dl_serv'],
					
					'S_OPTIMIZE'	=> check_sid("$file?mode=_optimize"),
					'S_RESTORE'		=> check_sid("$file?mode=_restore"),
					'S_ACTION'		=> check_sid($file),
					'S_FIELDS'		=> $fields,
				));
				
				$template->pparse('body');
			}
			else
			{
				/* Idee von phpBB & http://davidwalsh.name/backup-mysql-database-php */
				
				$add_tables		= ( request('add_tables', 1) ) ? request('add_tables', 1) : '';
				$backup_type	= ( request('backup_type', 1) ) ? request('backup_type', 1) : '';
				$table_type		= ( request('table_type', 1) ) ? request('table_type', 1) : 'min';
				$gzip_type		= ( request('gzip_type', 0) ) ? request('gzip_type', 0) : 0;
				$download_type	= ( request('download_type', 1) ) ? request('download_type', 1) : '';
				
				$sql = "SHOW TABLE STATUS";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$tbl = $db->sql_fetchrowset($result);
				
				if ( $table_type == 'full' )
				{	
					foreach ( $tbl as $key => $row )
					{
					#	$name = str_replace($db_prefix, '', $row['Name']);
						$name = $row['Name'];
						
						$tables[] = $name;
					}
				}
				else if ( $table_type == 'min' )
				{
					foreach ( $tbl as $key => $row )
					{
						$name = str_replace($db_prefix, '', $row['Name']);
						
						if ( in_array($name, array('banlist', 'cash', 'cash_bank', 'cash_users', 'config', 'contact', 'disallow', 'event', 'gallery', 'gallery_pic', 'gallery_settings', 'groups', 'groups_users', 'navi', 'profile', 'profile_cat', 'profile_data', 'settings', 'teamspeak', 'themes', 'themes_name', 'users') ) )
						{
							$tables[] = $name;
						}
					}
				}
				else if ( $table_type == 'dev' )
				{
					foreach ( $tbl as $key => $row )
					{
					#	$name = str_replace($db_prefix, '', $row['Name']);
						$name = $row['Name'];
						
					#	if ( in_array($name, array('config', 'settings') ) )
						if ( in_array($name, array('cms_authlist', 'cms_banlist') ) )
						{
							$tables[] = $name;
						}
					}
				}
				
				if ( !empty($add_tables) )
				{
					if ( ereg(',', $add_tables) )
					{
						$add_tables = split(',', $add_tables);
	
						for ( $i = 0; $i < count($add_tables); $i++ )
						{
							$tables[] = trim($add_tables[$i]);
						}
					}
					else
					{
						$tables[] = trim($add_tables);
					}
				}
			
				header("Pragma: no-cache");

				$do_gzip_compress = false;
				
				if ( $gzip_type )
				{
					if ( extension_loaded("zlib") )
					{
						$do_gzip_compress = true;
					}
				}
				
				$save_name = $do_gzip_compress ? 'db_' . date('dmY_His', time()) . '.sql.gz' : 'db_' . date('dmY_His', time()) . '.sql';
				
				$return = "#\n";
				$return .= "# ClanPortal Backup\n";
				$return .= "# Dump of tables for $db_name\n";
				$return .= "#\n# Datum: " . date("d.m.Y H:i:s", time()) . "\n";
				$return .= "#\n\n";
				
				foreach ( $tables as $table )
				{
					$content = $tbl = $field_name = $tmp = $value = $values = '';
					
					$sql = "OPTIMIZE TABLE $table";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					
					$sql = "SELECT * FROM $table";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$content = $db->sql_fetchrowset($result);
					
					$sql = "SHOW CREATE TABLE $table";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					
					$tbl = $row['Table'];
					$tmp = $row['Create Table'];
					
					if ( $backup_type == 'full' || $backup_type == 'structure' )
					{					
						$return .= "DROP TABLE `$tbl`;";
						$return .= "\n\n$tmp;\n";
					}
					
					if ( $backup_type == 'full' || $backup_type == 'data' )
					{					
						if ( $content )
						{
							$return .= "\nINSERT INTO `$tbl` (`" . implode('`, `', array_keys($content[0])) . "`) VALUES \n(";
							
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
							$return .= ');';
						}
					}
					
					$return .= "\n";
				}
				
				if ( $do_gzip_compress )
				{
					if ( $download_type == 'both' || $download_type == 'serv' )
					{
						$handle = gzopen("./../files/$save_name", "w9");
						gzwrite($handle, $return);
						gzclose($handle);
					}
					
					if ( $download_type == 'both' || $download_type == 'file' )
					{
						@ob_start();
						@ob_implicit_flush(0);
						header("Content-Type: application/x-gzip; name=\"$save_name\"");
						header("Content-disposition: attachment; filename=$save_name");
						
						$Size = ob_get_length();
						$Crc = crc32(ob_get_contents());
						$contents = gzcompress(ob_get_contents());
						ob_end_clean();
						
						echo $return . substr($contents, 0, strlen($contents) - 4) . gzip_PrintFourChars($Crc) . gzip_PrintFourChars($Size);
					}
				}
				else
				{
					if ( $download_type == 'both' || $download_type == 'serv' )
					{
						$handle = fopen("./../files/$save_name", "w+");
						fwrite($handle, $return);
						fclose($handle);
					}
					
					if ( $download_type == 'both' || $download_type == 'file' )
					{
						header("Content-Type: text/x-delimtext; name=\"$save_name\"");
						header("Content-disposition: attachment; filename=$save_name");
						
						echo $return;
					}
				}
				
				exit;
			}
			
			break;

		case '_restore':
				
			if ( !isset($HTTP_POST_VARS['restore_start']) )
			{
				$s_hidden_fields = "<input type=\"hidden\" name=\"perform\" value=\"restore\" /><input type=\"hidden\" name=\"perform\" value=\"$perform\" />";

				$template->assign_vars(array(
					"L_DATABASE_RESTORE" => $lang['Database_Utilities'] . " : " . $lang['Restore'],
					"L_RESTORE_EXPLAIN" => $lang['Restore_explain'],
					"L_SELECT_FILE" => $lang['Select_file'],
					"L_START_RESTORE" => $lang['Start_Restore'],

					"S_DBUTILS_ACTION" => check_sid("admin_db_utilities.$phpEx"),
					"S_HIDDEN_FIELDS" => $s_hidden_fields)
				);
				$template->pparse("body");

				break;

			}
			else
			{
				//
				// Handle the file upload ....
				// If no file was uploaded report an error...
				//
				$backup_file_name = (!empty($HTTP_POST_FILES['backup_file']['name'])) ? $HTTP_POST_FILES['backup_file']['name'] : "";
				$backup_file_tmpname = ($HTTP_POST_FILES['backup_file']['tmp_name'] != "none") ? $HTTP_POST_FILES['backup_file']['tmp_name'] : "";
				$backup_file_type = (!empty($HTTP_POST_FILES['backup_file']['type'])) ? $HTTP_POST_FILES['backup_file']['type'] : "";

				if($backup_file_tmpname == "" || $backup_file_name == "")
				{
					message_die(GENERAL_MESSAGE, $lang['Restore_Error_no_file']);
				}
				//
				// If I file was actually uploaded, check to make sure that we
				// are actually passed the name of an uploaded file, and not
				// a hackers attempt at getting us to process a local system
				// file.
				//
				if( file_exists(phpbb_realpath($backup_file_tmpname)) )
				{
					if( preg_match("/^(text\/[a-zA-Z]+)|(application\/(x\-)?gzip(\-compressed)?)|(application\/octet-stream)$/is", $backup_file_type) )
					{
						if( preg_match("/\.gz$/is",$backup_file_name) )
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
								$gz_ptr = gzopen($backup_file_tmpname, 'rb');
								$sql_query = "";
								while( !gzeof($gz_ptr) )
								{
									$sql_query .= gzgets($gz_ptr, 100000);
								}
							}
							else
							{
								message_die(GENERAL_ERROR, $lang['Restore_Error_decompress']);
							}
						}
						else
						{
							$sql_query = fread(fopen($backup_file_tmpname, 'r'), filesize($backup_file_tmpname));
						}
						//
						// Comment this line out to see if this fixes the stuff...
						//
						//$sql_query = stripslashes($sql_query);
					}
					else
					{
						message_die(GENERAL_ERROR, $lang['Restore_Error_filename'] ." $backup_file_type $backup_file_name");
					}
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['Restore_Error_uploading']);
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
								message_die(GENERAL_ERROR, "Error importing backup file", "", __LINE__, __FILE__, $sql);
							}
						}
					}
				}

				include('./page_header_admin.'.$phpEx);

				$template->set_filenames(array(
					"body" => "admin/admin_message_body.tpl")
				);

				$message = $lang['Restore_success'];

				$template->assign_vars(array(
					"MESSAGE_TITLE" => $lang['Database_Utilities'] . " : " . $lang['Restore'],
					"MESSAGE_TEXT" => $message)
				);

				$template->pparse("body");
				break;
			}
				
			break;
				
		case '_optimize':
			
			include('./page_header_admin.php');
			
			$template->assign_block_vars('_optimize', array());
	
			$current_time = time();

			//
			// If has been clicked the button reset
			//
			if ( isset( $_POST['reset'] ) )
			{
				$sql = "UPDATE " . OPTIMIZE . " SET db_show_begin_for = '', show_not_optimized = '0'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			}
		
			//
			// If has been clicked the button configure
			//
			if ( isset( $_POST['configure'] ) || isset( $_POST['db_show_begin_for'] ) )
			{
				$sql = "UPDATE " . OPTIMIZE . " SET db_show_begin_for = '" . $_POST['db_show_begin_for'] . "'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
			
				if ( isset( $_POST['configure'] ) )
				{
					//
					// Update optimize database cronfiguration
					//
					$sql = "UPDATE " . OPTIMIZE . " SET db_show_begin_for = '" . $_POST['db_show_begin_for'] . "', show_not_optimized = '" . $_POST['db_show_optimized'] . "' ";
					if ( !($result = $db->sql_query($sql)) )
					{
						message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
					}
				}
			}

			//
			// Optimize database configuration
			//
		#	$sql_opt = "SELECT * FROM " . OPTIMIZE;
		#	$opt_conf_result = $db->sql_query($sql_opt);
		#	if ( !( $opt_conf = $db->sql_fetchrow($opt_conf_result) ) )
		#	{
		#		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
		#	}

			//
			// If has been clicked the button optimize
			//
			if ( !request('optimize', 1) )
			{
				$sql = "SHOW TABLE STATUS LIKE '" . $config['db_show_begin_for'] . "%'";
				if ( !($result = $db->sql_query($sql)) )
				{
					message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
				}
				$tables = $db->sql_fetchrowset($result);
				
			#	debug($tables);
				
				$total_tbls = $total_rows = $total_size = $total_free = 0;
				
				for ( $i = 0; $i < count($tables); $i++ )
				{
					if ( !$tables[$i]['Data_free'] || !$config['db_show_optimized'] )
					{
						$size_tbl	= $tables[$i]['Index_length'] + $tables[$i]['Data_length'];
						$size_data	= $tables[$i]['Data_free'];
						$size_free	= ( $size_data ) ? _size($size_data, 1) : "Optimize";
								
						$s_check	= ( $size_data ) ? 'checked="checked"' : "";
						
						$template->assign_block_vars('_optimize._optimize_row', array(
							'NAME'		=> $tables[$i]['Name'],
							'ROWS'		=> $tables[$i]['Rows'],
							'SIZE'		=> _size($size_tbl, 1),
							'FREE'		=> $size_free,
							'S_SELECT' => "<input type=\"checkbox\" name=\"selected_tbl[]\" value=\"" . $tables[$i]['Name'] . "\" $s_check>",
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
						var elts = (typeof(document.forms[the_form].elements['selected_tbl[]']) != 'undefined') 
						? document.forms[the_form].elements['selected_tbl[]'] 
						: document.forms[the_form].elements = '';
					
						var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;
					
						if (elts_cnt) {
							for (var i = 0; i < elts_cnt; i++) {
								if (do_check == \"invert\"){
								elts[i].checked == true ? elts[i].checked = false : elts[i].checked = true;
								} else {
								elts[i].checked = do_check;
								}
								} // end for
							} else {
								elts.checked = do_check;
							} // end if... else
					
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
		
				//
				// Make the template
				//
				$template->assign_vars(array(
					'L_HEAD'		=> sprintf($lang['sprintf_head'], $lang['database']),
					'L_BACKUP'		=> $lang['backup'],
					'L_OPTIMIZE'	=> $lang['optimize'],					
					'L_RESTORE'		=> $lang['restore'],
				
					"SELECT_SCRIPT" => $select_scritp,
					
				#	'L_NAME'		=> $lang['opt_name'],
				#	'L_ROWS'		=> $lang['opt_rows'],
				#	'L_SIZE'		=> $lang['opt_size'],
				
					'TOTAL_TBLS'	=> $total_tbls+1,
					'TOTAL_ROWS'	=> $total_rows,
					'TOTAL_SIZE'	=> _size($total_size, 1),
					'TOTAL_FREE'	=> _size($total_free, 1),
					
					"L_SHOW_NOT_OPTIMIZED" => $lang['Optimize_Show_not_optimized'],
					"L_SHOW_BEGIN_FOR" => $lang['Optimize_Show_begin_for'],	
				
					
				
					
				
					
				
					
					
				#	"L_STATUS" => $lang['Optimize_Status'],
					"L_CHECKALL" => $lang['Optimize_CheckAll'],
					"L_UNCHECKALL" => $lang['Optimize_UncheckAll'],
					"L_INVERTCHECKED" => $lang['Optimize_InvertChecked'],
				#	"L_START_OPTIMIZE" => $lang['Optimize'],
					
				#	'S_ENABLE_NOT_OPTIMIZED_YES'	=> ( $config['db_show_optimized'] )	? ' checked="checked"' : '',
				#	'S_ENABLE_NOT_OPTIMIZED_NO'		=> (!$config['db_show_optimized'] )	? ' checked="checked"' : '',
					
				#	"S_SHOW_BEGIN_FOR" => $config['db_show_begin_for'],
					
					'S_ACTION'	=> check_sid($file),
					'S_FIELDS'	=> $fields,
				));

				$template->pparse('body');
			}
			else
			{
				$sql = "OPTIMIZE TABLE ";

				if ( $_POST['selected_tbl'] != '' )
				{
					$i = 1;
					
					foreach ( $_POST['selected_tbl'] as $var => $value )
					{
						$sql .= ( $i < count($_POST['selected_tbl']) ) ? "`$value`, " : "`$value`";
						$i++;
					}
				}					
				$sql .= " ;";
				
				if ( !$result = $db->sql_query($sql) )
				{
					$optimize_notablechecked = true;
				}

				//
				// Create information message 
				//
				if ( $optimize_notablechecked == true )
				{
					$message = $lang['Optimize_NoTableChecked']
						. "<br /><br />" . sprintf($lang['Optimize_return'], "<a href=\"" . check_sid("admin_database.php?perform=optimize") . "\">", "</a>")
						. "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . check_sid("index.php?pane=right") . "\">", "</a>");
				}
				else
				{
					$message = $message = $lang['Optimize_success']
						. "<br /><br />" . sprintf($lang['Optimize_return'], "<a href=\"" . check_sid("admin_database.php?perform=optimize") . "\">", "</a>")
						. "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . check_sid("index.php?pane=right") . "\">", "</a>");
				}	
				
				message(GENERAL_MESSAGE, $message);
			}
			
			break;
		}
}

include('./page_footer_admin.php');

?>