<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(
	
	'title'		=> 'Clankasse',
	'explain'	=> 'Clankassen verwaltung!',
	
	'cash_bank'		=> 'Bank',
	'cash_reason'	=> 'Kosten',
	'cash_user'		=> 'Benutzer',
	
	'bank_data'		=> 'Kontodaten',
	
	'bank_holder'	=> 'Kontoinhaber',
	'bank_name'		=> 'Bankname',
	'bank_blz'		=> 'Bankleitzahl',
	'bank_number'	=> 'Kontonummer',
	'bank_reason'	=> 'Verwendungszweck',
	'bank_delete'	=> 'Bankdaten löschen',
	
	'msg_empty_holder'	=> 'Bitte Inhaber des Kontos eintragen!',
	'msg_empty_name'	=> 'Bitte Bankname eintragen!',
	'msg_empty_blz'		=> 'Bitte Bankleitzahl eintragen!',
	'msg_empty_number'	=> 'Bitte Kontonummer eintragen!',
	'msg_empty_reason'	=> 'Bitte Verwendungszweck eintragen!',
	
	'create_bank'	=> 'Bankdaten erfolgreich eingetragen.',
	'update_bank'	=> 'Bankdaten erfolgreich geändert.',
	'delete_bank'	=> 'Die Bankdaten wurden gelöscht!',
	'confirm_bank'	=> 'Bist du sicher, das die Bankdaten gelöscht werden soll?',
	
	'create_user'	=> 'Neuen Benutzereintrag hinzugefügt.',
	'update_user'	=> 'Benutzereintrag erfolgreich geändert.',
	'delete_user'	=> 'Der Benutzereintrag wurde gelöscht!',
	'confirm_user'	=> 'das der Benutzereintrag:',
	
	'user'		=> 'Benutzer',
	
	'amount'	=> 'Betrag',
	'month'		=> 'Monat',
	
	'interval'			=> 'Zahlungsintervall',
	'interval_only'		=> 'Einmalig',
	'interval_month'	=> 'Monatlich',
	'interval_weeks'	=> 'alle 2 Wochen',
	'interval_weekly'	=> 'Wöchentlich',
	
	'type_game'		=> 'Gameserver',
	'type_voice'	=> 'Voiceserver',
	'type_other'	=> 'sonstiges',
	
	'postage' => 'Gesammtbetrag',
	
	'cash_received'		=> 'Bezahlt',
	'cast_notreceived'	=> 'nicht Bezahlt',
	
	/* error msg */
	'msg_select_amount'		=> 'Bitte einen Betrag eintragen!',
	
	/* added 14.07 */
	'cash_name'		=> 'Kostenname',
	'cash_amount'	=> 'Betrag',
	'cash_type'		=> 'Kosten Type',
	'cash_interval'	=> 'Zahlungsintervall',
	
	
	
));

$lang = array_merge($lang, array(	
	'radio:interval'	=> array(0 => $lang['interval_month'], 1 => $lang['interval_only']),
	'radio:cinterval'	=> array(0 => $lang['interval_month'], 1 => $lang['interval_weeks'], 2 => $lang['interval_weekly']),
	'radio:type'		=> array(0 => $lang['type_game'], 1 => $lang['type_voice'], 2 => $lang['type_other']),
));

/*

//
//	Clankasse (Cash)
//
$lang['cash_amount']			= 'Betrag';
$lang['cash_name']				= 'Beitragsname';
$lang['cash_interval']			= 'Zahlungsintervall';
$lang['cash_interval_m']		= 'Monatlich';
$lang['cash_interval_o']		= 'Einmalig';
$lang['cash_interval_month']	= 'Monatlich';
$lang['cash_interval_weeks']	= 'alle 2 Wochen';
$lang['cash_interval_weekly']		= 'Wöchentlich';
$lang['cash_no_entry']			= 'kein Eintrag';
$lang['interval_m']				= 'Monatlich ab: %s';
$lang['interval_o']				= 'Einmalig für Monat: %s';



$lang['create_cash']				= 'Neuen Betrag hinzugefügt.';
$lang['create_cash_user']			= 'Neuen Benutzer zu Liste hinzugefügt.';

$lang['update_cash']				= 'Daten erfolgreich geändert';
$lang['update_cash_bankdata']		= 'Bankdaten erfolgreich geändert';		
$lang['update_cash_user']			= 'Benutzerdaten erfolgreich geändert';

$lang['delete_confirm_cash']		= 'das der Eintrag:';
$lang['delete_confirm_cash_user']	= 'das der Benutzer:';

$lang['delete_cash']				= 'Der Eintrag wurde gelöscht!';
$lang['delete_cash_bankdata']		= 'Die Bankdaten wurden gelöscht!';
$lang['delete_cash_user']			= 'Der Benutzereintrag wurde gelöscht!';

$lang['delete_confirm_bankdata']	= 'Bist du sicher, das die Bankdaten gelöscht werden soll?';


$lang['cash']			= 'Clankasse';
$lang['cash_user']		= 'Benutzereintrag';
$lang['cash_explain']	= 'Clankassen verwaltung!';

$lang['cash_field']		= 'Beitrags';
$lang['cash_amount']	= 'Betrag';
$lang['cash_interval']	= 'Zahlungsintervall';

$lang['type_game']		= 'Gameserver';
$lang['type_voice']		= 'Voiceserver';
$lang['type_other']		= 'sonstiges';

$lang['cash_user_add']		= 'Neuen Benutzereintrag erstellen';
$lang['cash_user_new_add']	= 'Benutzer hinzufügen';
$lang['cash_user_edit']		= 'Benutzer bearbeiten';

$lang['cash_user_month']		= 'Monat';
$lang['interval_month']			= 'Monatlich';
$lang['interval_only']			= 'Einmalig';

$lang['postage']	= 'Gesammtbetrag';

$lang['cash_interval_month']	= 'Monatlich';
$lang['cash_interval_weeks']	= 'alle 2 Wochen';
$lang['cash_interval_weekly']	= 'Wöchentlich';



$lang['msg_select_name']	= 'Bitte Inhaber des Kontos eintragen';
$lang['msg_select_bank']	= 'Bitte Bankname eintragen';
$lang['msg_select_blz']		= 'Bitte Bankleitzahl eintragen';
$lang['msg_select_number']	= 'Bitte Kontonummer eintragen';
$lang['msg_select_reason']	= 'Bitte Verwendungszweck eintragen';

*/
?>