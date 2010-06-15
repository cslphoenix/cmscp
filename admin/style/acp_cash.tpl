<!-- BEGIN display -->
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a href="{S_USER_CREATE}">{L_CASH_USER_CREATE}</a></li>
	<li><a id="setting" href="{S_BANKDATA}">{L_BD_INFO}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- BEGIN show_bank -->
<form action="{S_ACTION}" method="post">
<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_BD_INFO}</td>
</tr>
<tr>
	<td class="row_class1" width="200">{L_BD_NAME}</td>
	<td class="row_class2">{NAME}</td>
</tr>
<tr>
	<td class="row_class1">{L_BD_BANK} / {L_BD_BLZ}</td>
	<td class="row_class2">{BANK} / {BLZ}</td>
</tr>
<tr>
	<td class="row_class1">{L_BD_NUMBER}</td>
	<td class="row_class2">{NUMBER}</td>
</tr>
<tr>
	<td class="row_class1">{L_BD_REASON}</td>
	<td class="row_class2">{REASON}</td>
</tr>
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="hidden" name="mode" value="_bankdata_delete"><input type="submit" class="button" value="{L_BD_DELETE}"></td>
</tr>
</table>
</form>

<br />
<!-- END show_bank -->

<form action="{S_ACTION}" method="post">
<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_NAME}</td>
	<td class="rowHead">{L_INTERVAL}</td>
	<td class="rowHead" colspan="2" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN cash_row -->
<tr>
	<td class="{display.cash_row.CLASS}" align="left" width="99%">{display.cash_row.CASH_NAME}</td>
	<td class="{display.cash_row.CLASS}" align="right" width="99%">{display.cash_row.CASH_AMOUNT}&nbsp;</td>
	<td class="{display.cash_row.CLASS}" align="center" nowrap="nowrap">{display.cash_row.CASH_DATE}</td>
	<td class="{display.cash_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_row.U_UPDATE}">{L_UPDATE}</a></td>		
	<td class="{display.cash_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END cash_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
<tr>
	<td class="rowHead" colspan="2" width="100%" align="right">{TOTAL_CASH}</td>
	<td class="rowHead" colspan="3">Gesammtbetrag</td>
</tr>
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="cash_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
<input type="hidden" name="mode" value="_create_cash">
</form>

<br />

<form action="{S_ACTION}" method="post">
<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_CASHUSER_USERNAME}</td>
	<td class="rowHead">{L_INTERVAL}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN cash_users_row -->
<tr>
	<td class="{display.cash_users_row.CLASS}" align="left" width="99%">{display.cash_users_row.CASHUSER_USERNAME}</td>
	<td class="{display.cash_users_row.CLASS}" align="right" width="99%">{display.cash_users_row.CASHUSER_AMOUNT}&nbsp;</td>
	<td class="{display.cash_users_row.CLASS}" align="center" nowrap="nowrap">{display.cash_users_row.CASHUSER_INTERVAL}</td>
	<td class="{display.cash_users_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_users_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.cash_users_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_users_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END cash_users_row -->
<!-- BEGIN no_entry_users -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_users -->
<tr>
	<td class="rowHead" colspan="2" width="100%" align="right">{USER_CASH}</td>
	<td class="rowHead" colspan="4">Gesammtbetrag</td>
</tr>
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right">{S_CASHUSER_ADD}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CASHUSER_CREATE}"></td>
</tr>
</table>
<input type="hidden" name="mode" value="_create_cashuser">
</form>
<!-- END display -->

<!-- BEGIN cash_edit -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_GAME_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%"><label for="cash_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cash_name" id="cash_name" value="{CASH_NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="cash_amount">{L_AMOUNT}:</label></td>
	<td class="row3"><input type="text" class="post" name="cash_amount" id="cash_amount" value="{CASH_AMOUNT}"></td>
</tr>
<tr>
	<td class="row1"><label for="cash_type">{L_TYPE}:</label></td>
	<td class="row3">
		<input type="radio" name="cash_type" value="0" id="cash_type" {S_TYPE_A} />&nbsp;{L_TYPE_A}
		<input type="radio" name="cash_type" value="1" {S_TYPE_B} />&nbsp;{L_TYPE_B}
		<input type="radio" name="cash_type" value="2" {S_TYPE_C} />&nbsp;{L_TYPE_C}
	</td>
</tr>
<tr>
	<td class="row1"><label for="cash_interval">{L_INTERVAL}:</label></td>
	<td class="row3">
		<input type="radio" name="cash_interval" value="0" id="cash_interval" {S_INT_0} />&nbsp;{L_INTAVAL_0}
		<input type="radio" name="cash_interval" value="1" {S_INT_1} />&nbsp;{L_INTAVAL_1}
		<input type="radio" name="cash_interval" value="2" {S_INT_2} />&nbsp;{L_INTAVAL_2}
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END cash_edit -->

<!-- BEGIN cashuser_edit -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_CASHUSER_NEW_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%">{L_CASHUSER}: *</td>
	<td class="row2">{S_CASHUSER}</td>
</tr>
<tr>
	<td class="row1">{L_CASHUSER_AMOUNT}: *</td>
	<td class="row3"><input type="text" class="post" name="user_amount" value="{CASH_AMOUNT}"></td>
</tr>
<tr>
	<td class="row1">{L_CASHUSER_MONTH}:</td>
	<td class="row3">{S_MONTH}</td>
</tr>
<tr>
	<td class="row1">{L_CASHUSER_INTERVAL}:</td>
	<td class="row3"><input type="radio" name="user_interval" value="0" {S_INTAVAL_M} />&nbsp;{L_INTAVAL_M}&nbsp;&nbsp;<input type="radio" name="user_interval" value="1" {S_INTAVAL_O} />&nbsp;{L_INTAVAL_O}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END cash_user_edit -->

<!-- BEGIN cash_bankdata -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="#" id="right">{L_BANKDATA}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%"><label for="bankdata_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" class="post" name="bankdata_name" id="bankdata_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="bankdata_bank">{L_BANK}:</label></td>
	<td class="row2"><input type="text" class="post" name="bankdata_bank" id="bankdata_bank" value="{BANK}"></td>
</tr>
<tr>
	<td class="row1"><label for="bankdata_blz">{L_BLZ}:</label></td>
	<td class="row2"><input type="text" class="post" name="bankdata_blz" id="bankdata_blz" value="{BLZ}"></td>
</tr>
<tr>
	<td class="row1"><label for="bankdata_number">{L_NUMBER}:</label></td>
	<td class="row2"><input type="text" class="post" name="bankdata_number" id="bankdata_number" value="{NUMBER}"></td>
</tr>
<tr>
	<td class="row1"><label for="bankdata_reason">{L_REASON}:</label></td>
	<td class="row2"><input type="text" class="post" name="bankdata_reason" id="bankdata_reason" value="{REASON}"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END cash_bankdata -->