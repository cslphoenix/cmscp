<!-- BEGIN display -->
<form action="{S_CASH_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_CASH_HEAD}</a></li>
				<li><a href="{S_CASH_BD}">{L_CASH_BD}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_CASH_EXPLAIN}</td>
</tr>
</table>

<br>

<!-- BEGIN show_bd -->
<table class="row" width="100%" cellspacing="0">
<tr>
	<td class="row_class1" width="200">{L_BD_NAME}</td>
	<td class="row_class2">{BD_NAME}</td>
</tr>
<tr>
	<td class="row_class1">{L_BD_BANK} / {L_BD_BLZ}</td>
	<td class="row_class2">{BD_BANK} / {BD_BLZ}</td>
</tr>
<tr>
	<td class="row_class1">{L_BD_NUMBER}</td>
	<td class="row_class2">{BD_NUMBER}</td>
</tr>
<tr>
	<td class="row_class1">{L_BD_REASON}</td>
	<td class="row_class2">{BD_REASON}</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td align="right"><input class="button" type="submit" name="bankdata_clear" value="{L_CASH_BANK_CLEAR}" /></td>
</tr>
</table>
<br>

<!-- END show_bd -->

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_CASH_NAME}</td>
	<td class="rowHead">{L_CASH_INTERVAL}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN cash_row -->
<tr>
	<td class="{display.cash_row.CLASS}" align="left" width="99%">{display.cash_row.CASH_NAME}</td>
	<td class="{display.cash_row.CLASS}" align="right" width="99%">{display.cash_row.CASH_AMOUNT}&nbsp;</td>
	<td class="{display.cash_row.CLASS}" align="center" nowrap="nowrap">{display.cash_row.CASH_DATE}</td>
	<td class="{display.cash_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.cash_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END cash_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
<tr>
	<td class="rowHead" colspan="2" width="100%" align="right">{TOTAL_CASH}</td>
	<td class="rowHead" colspan="3">Gesammtbetrag</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="cash_name" type="text" value=""></td>
	<td><input class="button" type="submit" name="cash_add" value="{L_CASH_ADD}" /></td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_CASH_USERNAME}</td>
	<td class="rowHead">{L_CASH_INTERVAL}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN cash_users_row -->
<tr>
	<td class="{display.cash_users_row.CLASS}" align="left" width="99%">{display.cash_users_row.CASH_USERNAME}</td>
	<td class="{display.cash_users_row.CLASS}" align="right" width="99%">{display.cash_users_row.CASH_USER_AMOUNT}&nbsp;</td>
	<td class="{display.cash_users_row.CLASS}" align="center" nowrap="nowrap">{display.cash_users_row.CASH_USER_INTERVAL}</td>
	<td class="{display.cash_users_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_users_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.cash_users_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_users_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END cash_users_row -->
<!-- BEGIN no_entry_users -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_users -->
<tr>
	<td class="rowHead" colspan="2" width="100%" align="right">{USER_CASH}</td>
	<td class="rowHead" colspan="4">Gesammtbetrag</td>
</tr>

</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right">{S_CASH_USER_ADD}</td>
	<td><input class="button" type="submit" name="cash_user" value="{L_CASH_USER_ADD}" /></td>
</tr>
</table>
</form>

<!-- END display -->

<!-- BEGIN cash_edit -->
<form action="{S_CASH_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_CASH_ACTION}">{L_CASH_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_CASH_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="160">{L_CASH_NAME}: *</td>
	<td class="row2"><input class="post" type="text" name="cash_name" value="{CASH_NAME}" ></td>
</tr>
<tr>
	<td class="row1">{L_CASH_AMOUNT}:</td>
	<td class="row3"><input class="post" type="text" name="cash_amount" value="{CASH_AMOUNT}" ></td>
</tr>
<tr>
	<td class="row1">{L_CASH_TYPE}:</td>
	<td class="row3">
		<input type="radio" name="cash_type" value="0" {S_CHECKED_TYPE_A} />&nbsp;{L_CASH_TYPE_A}
		<input type="radio" name="cash_type" value="1" {S_CHECKED_TYPE_B} />&nbsp;{L_CASH_TYPE_B}
		<input type="radio" name="cash_type" value="2" {S_CHECKED_TYPE_C} />&nbsp;{L_CASH_TYPE_C}
	</td>
</tr>
<tr>
	<td class="row1">{L_CASH_INTERVAL}:</td>
	<td class="row3">
		<input type="radio" name="cash_interval" value="0" {S_CHECKED_INTAVAL_0} />&nbsp;{L_INTAVAL_0}
		<input type="radio" name="cash_interval" value="1" {S_CHECKED_INTAVAL_1} />&nbsp;{L_INTAVAL_1}
		<input type="radio" name="cash_interval" value="2" {S_CHECKED_INTAVAL_2} />&nbsp;{L_INTAVAL_2}
	</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END cash_edit -->

<!-- BEGIN cash_user_edit -->
<form action="{S_CASH_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_CASH_ACTION}">{L_CASH_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_CASH_USER_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="160">{L_CASH_USER}: *</td>
	<td class="row2">{S_CASH_USER}</td>
</tr>
<tr>
	<td class="row1">{L_CASH_USER_AMOUNT}:</td>
	<td class="row3"><input class="post" type="text" name="user_amount" value="{CASH_AMOUNT}" ></td>
</tr>
<tr>
	<td class="row1">{L_CASH_USER_MONTH}:</td>
	<td class="row3">{S_MONTH}</td>
</tr>
<tr>
	<td class="row1">{L_CASH_USER_INTERVAL}:</td>
	<td class="row3"><input type="radio" name="user_interval" value="0" {S_CHECKED_INTAVAL_M} />&nbsp;{L_INTAVAL_M}&nbsp;&nbsp;<input type="radio" name="user_interval" value="1" {S_CHECKED_INTAVAL_O} />&nbsp;{L_INTAVAL_O}</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END cash_user_edit -->

<!-- BEGIN cash_bankdata -->
<form action="{S_CASH_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_CASH_ACTION}">{L_CASH_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_CASH_BD}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="200">{L_BD_NAME}</td>
	<td class="row2"><input class="post" type="text" name="bd_name" value="{BD_NAME}" ></td>
</tr>
<tr>
	<td class="row1">{L_BD_BANK}</td>
	<td class="row2"><input class="post" type="text" name="bd_bank" value="{BD_BANK}" ></td>
</tr>
<tr>
	<td class="row1">{L_BD_BLZ}</td>
	<td class="row2"><input class="post" type="text" name="bd_blz" value="{BD_BLZ}" ></td>
</tr>
<tr>
	<td class="row1">{L_BD_NUMBER}</td>
	<td class="row2"><input class="post" type="text" name="bd_number" value="{BD_NUMBER}" ></td>
</tr>
<tr>
	<td class="row1">{L_BD_REASON}</td>
	<td class="row2"><input class="post" type="text" name="bd_reason" value="{BD_REASON}" ></td>
</tr>
<tr>
	<td class="row2" colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END cash_bankdata -->