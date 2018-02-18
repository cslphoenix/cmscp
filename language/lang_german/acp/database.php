<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'TITLE'		=> 'Datenbank',
	'EXPLAIN'	=> 'Hier können Backups erstellt werden usw.',

	'data_backup'	=> 'Backup',
	'data_optimize'	=> 'Optimieren',
	'data_restore'	=> 'Wiederherstellen',
	
	'type'	=> 'Optionen',
	'type_full'	=> 'Vollständig',
	'type_structure' => 'nur Struktur',
	'type_data'	=> 'nur Daten',
	'gzip'	=> 'GZip Datei',
	'download'	=> 'Download',
	'download_file'	=> 'Rechner',
	'download_server'	=> 'Server',
	
	'table'	=> 'Tabellen',
	'table_dev'	=> 'Entwicklung',
	'table_full'	=> 'Vollstädnig',
	'table_min'	=> 'minimal',
	
	'file_select'	=> 'Backup auswählen',
	'file_restore'	=> 'Backup einspielen',
	
	'opt_table'		=> 'Tabelle',
	'opt_rows'		=> 'Einträge',
	'opt_size'		=> 'Größe',
	'opt_status'	=> 'Status',
	
	'opt_success'	=> 'Die Tabellen wurden optimiert.',
	'opt_nocheck'	=> 'Kein Tabelle ausgewählt!',



	'save_file'	=> 'Die Backupdatei wurde erfolgreich gespeichert.',
	
	/* errors */
	'msg_empty_tables'			=> 'Bitte 1 Tabelle auswählen!',
	
));


/*

$lang['CREATE'] = 'DB erstellt!';






$lang['Backup_options'] = 'Backup-Optionen';
$lang['Start_backup'] = 'Backup beginnen';
$lang['Full_backup'] = 'Vollständiges Backup';
$lang['Structure_backup'] = 'Nur-Struktur-Backup';
$lang['Data_backup'] = 'Nur-Daten-Backup';
$lang['Additional_tables'] = 'Zusätzliche Tabellen';
$lang['Gzip_compress'] = 'GZip-Komprimierungs Datei';

$lang['Select_file'] = 'Wähle eine Datei';
$lang['Start_Restore'] = 'Wiederherstellung beginnen';
$lang['Restore_success'] = 'Die Datenbank wurde wieder hergestellt.<br /><br />Dein Board sollte jetzt wieder den Stand des Backups haben.';
$lang['Backup_download'] = 'Dein Download wird in Kürze beginnen - bitte etwas Geduld';
$lang['Backups_not_supported'] = 'Fehler: Dein Datenbanksystem unterstützt Datenbank-Backups nicht!';

$lang['Restore_Error_uploading'] = 'Fehler beim Hochladen der Backup-Datei';
$lang['Restore_Error_filename'] = 'Probleme mit dem Dateinamen, probiere einen anderen';
$lang['Restore_Error_decompress'] = 'Die GZip-Version kann nicht dekomprimiert werden, nutze bitte eine Nur-Text-Datei';
$lang['Restore_Error_no_file'] = 'Es wurde keine Datei hochgeladen';


$lang['Optimize_Enable_cron'] = "Enable Cron";


$lang['Optimize'] = 'Optimize';
$lang['Optimize_explain'] = 'Here it\'s possible to optimize the contained data in the tables of the database. You can eliminate in this way the parts of the data that contain some empty spaces.<br />This operation must regularly be performs so that your database to make reliable and it has to maintain a speed of correct execution.';
$lang['Optimize_DB'] = 'Optimize Database';
$lang['Optimize_Enable_cron'] = "Enable Cron";
$lang['Optimize_Cron_every'] = "Cron Every";
$lang['Optimize_month'] = "Month";
$lang['Optimize_2weeks'] = "2 weeks";
$lang['Optimize_week'] = "Week";
$lang['Optimize_3days'] = "3 days";
$lang['Optimize_day'] = "Day";
$lang['Optimize_6hours'] = "6 hours";
$lang['Optimize_hour'] = "Hour";
$lang['Optimize_30minutes'] = "30 minutes";
$lang['Optimize_20seconds'] = "20 seconds (only for test)";
$lang['Optimize_Current_time'] = "Current Time";
$lang['Optimize_Next_cron_action'] = "Next Cron Action";
$lang['Optimize_Performed_Cron'] = "Performed Cron";
$lang['Optimize_Show_not_optimized'] = 'Show only tables not optimized';
$lang['Optimize_Show_begin_for'] = 'Show only tables that begin for';
$lang['Optimize_Configure'] = 'Configure';
$lang['Optimize_Table'] = 'Table';
$lang['Optimize_Record'] = 'Record';
$lang['Optimize_Type'] = 'Type';
$lang['Optimize_Size'] = 'Size';
$lang['Optimize_Status'] = 'Status';
$lang['Optimize_CheckAll'] = 'Check All';
$lang['Optimize_UncheckAll'] = 'Uncheck All';
$lang['Optimize_InvertChecked'] = 'Invert Checked';
$lang['Optimize_return'] = 'Click %sHere%s to return to the Optimize Database';



$lang['Database_Utilities'] = 'Database Utilities';

$lang['Restore'] = 'Restore';
$lang['Backup'] = 'Backup';
$lang['Restore_explain'] = 'This will perform a full restore of all phpBB tables from a saved file. If your server supports it, you may upload a gzip-compressed text file and it will automatically be decompressed. <b>WARNING</b>: This will overwrite any existing data. The restore may take a long time to process, so please do not move from this page until it is complete.';
$lang['Backup_explain'] = 'Here you can back up all your phpBB-related data. If you have any additional custom tables in the same database with phpBB that you would like to back up as well, please enter their names, separated by commas, in the Additional Tables textbox below. If your server supports it you may also gzip-compress the file to reduce its size before download.';

$lang['Backup_options'] = 'Backup options';
$lang['Start_backup'] = 'Start Backup';
$lang['Full_backup'] = 'Full backup';
$lang['Structure_backup'] = 'Structure-Only backup';
$lang['Data_backup'] = 'Data only backup';
$lang['Additional_tables'] = 'Additional tables';
$lang['Gzip_compress'] = 'Gzip compress file';
$lang['Select_file'] = 'Select a file';
$lang['Start_Restore'] = 'Start Restore';

$lang['Restore_success'] = 'The Database has been successfully restored.<br /><br />Your board should be back to the state it was when the backup was made.';
$lang['Backup_download'] = 'Your download will start shortly; please wait until it begins.';
$lang['Backups_not_supported'] = 'Sorry, but database backups are not currently supported for your database system.';

$lang['Restore_Error_uploading'] = 'Error in uploading the backup file';
$lang['Restore_Error_filename'] = 'Filename problem; please try an alternative file';
$lang['Restore_Error_decompress'] = 'Cannot decompress a gzip file; please upload a plain text version';
$lang['Restore_Error_no_file'] = 'No file was uploaded';
*/
?>