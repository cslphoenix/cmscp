<!-- BEGIN display -->
<form action="{S_CASH_ACTION}" method="post">
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="2">{L_HEAD_CASH}</td>
</tr>
<tr>
	<td class="row1" width="200">{L_NAME}</td>
	<td class="row2">{BD_NAME}</td>
</tr>
<tr>
	<td class="row1">{L_BLZ} / {L_BANK}</td>
	<td class="row2">{BD_BLZ} / {BD_BANK}</td>
</tr>
<tr>
	<td class="row1">{L_NUMBER}</td>
	<td class="row2">{BD_NUMBER}</td>
</tr>
<tr>
	<td class="row1">{L_REASON}</td>
	<td class="row2">{BD_REASON}</td>
</tr>
</table>

<br>

<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" width="100%">{L_CASH_NAME}</td>
	<td class="info_head">{L_CASH_AMOUNT}</td>
	<td class="info_head">{L_CASH_INTERVAL}</td>
</tr>
<!-- BEGIN cash_row -->
<tr>
	<td class="{display.cash_row.CLASS}" align="left" width="99%">{display.cash_row.CASH_NAME}</td>
	<td class="{display.cash_row.CLASS}" align="right" width="99%">{display.cash_row.CASH_AMOUNT}&nbsp;</td>
	<td class="{display.cash_row.CLASS}" align="center" nowrap="nowrap">{display.cash_row.CASH_DATE}</td>
</tr>
<!-- END cash_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row3" align="center" colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
<tr>
	<td class="info_foot" width="100%">&nbsp;</td>
	<td class="info_foot" style="text-align:right;">{CASH_T_AMOUNT}&nbsp;</td>
	<td class="info_foot">Gesammtbetrag</td>
</tr>
</table>

<br>

<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" width="100%">{L_CASHUSER_USERNAME}</td>
	<td class="info_head">{L_CASH_AMOUNT}</td>
	<td class="info_head">{L_CASH_INTERVAL}</td>
</tr>
<!-- BEGIN cash_users_row -->
<tr>
	<td class="{display.cash_users_row.CLASS}" align="left" width="99%">{display.cash_users_row.CASHUSER_USERNAME} <span class="small">({display.cash_users_row.CASHUSER_MONTH})</span></td>
	<td class="{display.cash_users_row.CLASS}" align="right" width="99%">{display.cash_users_row.CASHUSER_AMOUNT}&nbsp;</td>
	<td class="{display.cash_users_row.CLASS}" align="center" nowrap="nowrap">{display.cash_users_row.CASHUSER_INTERVAL}</td>
</tr>
<!-- END cash_users_row -->
<!-- BEGIN cash_user -->
<tr>
	<td class="{display.cash_user.CLASS}" align="left" width="99%"><b>{display.cash_user.CASHUSER_NAME}</b> <span class="small">({display.cash_user.CASHUSER_MONTH})</span></td>
	<td class="{display.cash_user.CLASS}" align="center" width="99%"><b>{display.cash_user.CASHUSER_AMOUNT}</b>&nbsp;</td>
	<td class="{display.cash_user.CLASS}" align="center" nowrap="nowrap"><b>{display.cash_user.CASHUSER_INTERVAL}</b></td>
</tr>
<!-- END cash_user -->
<!-- BEGIN no_entry_users -->
<tr>
	<td class="row3" align="center" colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_users -->
<tr>
	<td class="info_foot" width="100%">&nbsp;</td>
	<td class="info_foot" style="text-align:right;">{CASH_U_AMOUNT}&nbsp;</td>
	<td class="info_foot">Gesammtbetrag</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td class="info_foot" width="100%">{CASH_COUNT} Zahlen diesen Monat ein</td>
	<td class="info_foot" style="text-align:right;"><span class="{CASH_CLASS}">{CASH_TOTAL}</span>&nbsp;</td>
	<td class="info_foot">Zusammen</td>
</tr>
</table>
</form>
<!-- END display -->