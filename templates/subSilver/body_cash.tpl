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
	<td>{L_BLZ} / {L_BANK}</td>
	<td class="row2">{BD_BLZ} / {BD_BANK}</td>
</tr>
<tr>
	<td>{L_NUMBER}</td>
	<td class="row2">{BD_NUMBER}</td>
</tr>
<tr>
	<td>{L_REASON}</td>
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
	<td class="{_display.cashrow.CLASS}" align="left" width="99%">{_display.cashrow.CASH_NAME}</td>
	<td class="{_display.cashrow.CLASS}" align="right" width="99%">{_display.cashrow.CASH_AMOUNT}&nbsp;</td>
	<td class="{_display.cashrow.CLASS}" align="center" nowrap="nowrap">{_display.cashrow.CASH_DATE}</td>
</tr>
<!-- END cash_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row3" align="center" colspan="3">{L_NONE}</td>
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
<!-- BEGIN cash_user_row -->
<tr>
	<td class="{_display.cash_userrow.CLASS}" align="left" width="99%">{_display.cash_userrow.CASHUSER_USERNAME} <span class="small">({_display.cash_userrow.CASHUSER_MONTH})</span></td>
	<td class="{_display.cash_userrow.CLASS}" align="right" width="99%">{_display.cash_userrow.CASHUSER_AMOUNT}&nbsp;</td>
	<td class="{_display.cash_userrow.CLASS}" align="center" nowrap="nowrap">{_display.cash_userrow.CASHUSER_INTERVAL}</td>
</tr>
<!-- END cash_user_row -->
<!-- BEGIN cash_user -->
<tr>
	<td class="{_display.cash_user.CLASS}" align="left" width="99%"><b>{_display.cash_user.CASHUSER_NAME}</b> <span class="small">({_display.cash_user.CASHUSER_MONTH})</span></td>
	<td class="{_display.cash_user.CLASS}" align="center" width="99%"><b>{_display.cash_user.CASHUSER_AMOUNT}</b>&nbsp;</td>
	<td class="{_display.cash_user.CLASS}" align="center" nowrap="nowrap"><b>{_display.cash_user.CASHUSER_INTERVAL}</b></td>
</tr>
<!-- END cash_user -->
<!-- BEGIN no_entry_users -->
<tr>
	<td class="row3" align="center" colspan="3">{L_NONE}</td>
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