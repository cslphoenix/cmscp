<?php

if ( !defined('IN_CMS') )
{
	exit;
}

$lang = array_merge($lang, array(

	'BANK'		=> 'Bankdaten',
	'USER'		=> 'Bank-Benutzer',
	'TYPE'		=> 'Clankassen-Art',

	'TITLE'		=> 'Clankasse',
	'EXPLAIN'	=> 'Clankassen verwaltung!',
	
	'INPUT_DATA'	=> 'Kostendaten',
	
	'CASH_REASON'	=> 'Kosten',
	'CASH_PAID'		=> 'Bezahlt',
#	'cash_interval'	=> 'Zahlungsintervall',
#	'cash_postage'	=> 'Gesammtbetrag',
	
#	'cash_bank'		=> 'Bank',
#	'cash_user'		=> 'Benutzer',
	
	'BANK_DATA'		=> 'Kontodaten',
	'BANK_HOLDER'	=> 'Kontoinhaber',
	'BANK_NAME'		=> 'Bankname',
	'BANK_BLZ'		=> 'Bankleitzahl',
	'BANK_NUMBER'	=> 'Kontonummer',
	'BANK_REASON'	=> 'Verwendungszweck',
	'BANK_DELETE'	=> 'Bankdaten löschen',
#	'bank_create'	=> 'Bankdaten eintragen',
	
#	'type_create'		=> 'Type eintragen',
	
#	'msg_empty_holder'	=> 'Bitte Inhaber des Kontos eintragen!',
#	'msg_empty_name'	=> 'Bitte Bankname eintragen!',
#	'msg_empty_blz'		=> 'Bitte Bankleitzahl eintragen!',
#	'msg_empty_number'	=> 'Bitte Kontonummer eintragen!',
#	'msg_empty_reason'	=> 'Bitte Verwendungszweck eintragen!',
	
#	'user'		=> 'Benutzer',
#	'amount'	=> 'Betrag',
#	'month'		=> 'Monat',
	
#	'interval'			=> 'Zahlungsintervall',
	'INTERVAL_ONLY'		=> 'Einmalig',
	'INTERVAL_MONTH'	=> 'Monatlich',
	'INTERVAL_WEEKS'	=> 'alle 2 Wochen',
	'INTERVAL_WEEKLY'	=> 'Wöchentlich',
	
	'TYPE_GAME'		=> 'Gameserver',
	'TYPE_VOICE'	=> 'Voiceserver',
	'TYPE_OTHER'	=> 'sonstiges',
	
#	'cash_received'		=> 'Bezahlt',
#	'cast_notreceived'	=> 'nicht Bezahlt',
	
	/* error msg */
#	'msg_select_amount'		=> 'Bitte einen Betrag eintragen!',
	
	/* added 14.07 */
	'CASH_NAME'		=> 'Benutzer / Zweck',
	'CASH_AMOUNT'	=> 'Betrag',
	'CASH_MONTH'	=> 'Monat(e)',
	'CASH_TYPE'		=> 'Kosten Type',
	'CASH_INTERVAL'	=> 'Zahlungsintervall',
	'CASH_PAID'		=> 'Gezahlt',
	
	'CURRENT_MONTH'	=> ' (<span style="color:#F00; font-weight:bold;">Aktueller Monat</span>)',
	
	
#	'create'	=> 'hinzugefügt',
#	'update'	=> 'geändert',
	
#	'select_month'	=> 'Bitte ein Monat auswählen',
	
	

#	'confirm_bank'	=> 'Bist du sicher, das die Bankdaten gelöscht werden soll?',

	'STF_PAID'	=> 'Übersicht über gezahlte Beiträge',

));

$lang = array_merge($lang, array(	
	'radio:interval'	=> array(0 => $lang['INTERVAL_MONTH'], 1 => $lang['INTERVAL_ONLY']),
	'radio:cinterval'	=> array(0 => $lang['INTERVAL_MONTH'], 1 => $lang['INTERVAL_WEEKS'], 2 => $lang['INTERVAL_WEEKLY']),
	'radio:type'		=> array(0 => $lang['TYPE_GAME'], 1 => $lang['TYPE_VOICE'], 2 => $lang['TYPE_OTHER']),
	'radio:user'		=> array(0 => $lang['INTERVAL_MONTH'], 1 => $lang['INTERVAL_ONLY']),
	'radio:type'		=> array(0 => $lang['INTERVAL_MONTH'], 1 => $lang['INTERVAL_WEEKS'], 2 => $lang['INTERVAL_WEEKLY']),
	'radio:ctype'		=> array(0 => $lang['TYPE_GAME'], 1 => $lang['TYPE_VOICE'], 2 => $lang['TYPE_OTHER']),
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