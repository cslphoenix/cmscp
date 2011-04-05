<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang['database']		= 'Datenbank';
$lang['optimize']		= 'Optimieren';
$lang['backup']			= 'Backup';
$lang['restore']		= 'Wiederherstellen';

$lang['explain']		= 'Die Datenbank kann gespeichert, optimiert oder auch wiederhergestellt werden.';


$lang['opt_cron_enable']		= 'Automatische Optimierung';
$lang['opt_cron_intervall']		= 'Optimierungsintervall';

$lang['opt_int_month']	= 'Monatlich';
$lang['opt_int_weeks']	= '2 Wochen';
$lang['opt_int_week']	= '1 Woche';
$lang['opt_int_days']	= '3 Tage';
$lang['opt_int_day']	= '1 Tad';
$lang['opt_int_hours']	= '6 Stunden';
$lang['opt_int_hour']	= '1 Stunde';
$lang['opt_int_mins']	= '30 Minuten';
$lang['opt_int_secs']	= '20 Sekunden (nur für Tests!!!)';

$lang['type']			= 'Backup-Optionen';
$lang['type_full']		= 'Vollständig';
$lang['type_structure']	= 'nur Struktur';
$lang['type_data']		= 'nur Daten';
$lang['table']			= 'Tabellen';
$lang['table_dev']		= 'Entwicklung';
$lang['table_full']		= 'Vollstädnig';
$lang['table_min']		= 'minimal';

$lang['table_add']		= 'weitere Tabellen';
$lang['table_zip']		= 'GZip Datei';

$lang['download']		= 'Download der Datei:';
$lang['dl_file']		= 'Rechner';
$lang['dl_serv']		= 'Server';
$lang['dl_both']		= 'beides';


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
$lang['Optimize_success'] = 'The Database has been successfully optimized';
$lang['Optimize_NoTableChecked'] = '<b>No</b> Tables Checked';

?>