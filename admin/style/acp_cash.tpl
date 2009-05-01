<!-- BEGIN display -->
<form action="{S_CASH_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_CASH_HEAD}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_CASH_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="3" width="100%">{L_CASH_NAME}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN cash_row -->
<tr>
	<td class="{display.cash_row.CLASS}" align="left" width="99%">{display.cash_row.CASH_NAME}</td>
	<td class="{display.cash_row.CLASS}" align="left" width="99%">{display.cash_row.CASH_AMOUNT}</td>
	<td class="{display.cash_row.CLASS}" nowrap="nowrap">{display.cash_row.CASH_DATE}</td>
	<td class="{display.cash_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.cash_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cash_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END cash_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="cash_name" type="text" value=""></td>
	<td><input class="button" type="submit" name="cash_add" value="{L_CASH_ADD}" /></td>
</tr>
</table>

<br>
<br>
<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="3" width="100%">{L_CASH_USERNAME}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN cashusers_row -->
<tr>
	<td class="{display.cashusers_row.CLASS}" align="left" width="99%">{display.cashusers_row.USERNAME}</td>
	<td class="{display.cashusers_row.CLASS}" align="left" width="99%">{display.cashusers_row.USER_AMOUNT}</td>
	<td class="{display.cashusers_row.CLASS}" nowrap="nowrap">{display.cashusers_row.CASH_DATE}</td>
	<td class="{display.cashusers_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cashusers_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.cashusers_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.cashusers_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END cashusers_row -->
<!-- BEGIN no_entry_users -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_users -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="username" type="text" value=""></td>
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
	<td class="row1">{L_CASH_COMMENTS}:</td>
	<td class="row3">
		<input type="radio" name="cash_type" value="0" {S_CHECKED_TYPE_A} />&nbsp;{L_CASH_TYPE_A}
		<input type="radio" name="cash_type" value="1" {S_CHECKED_TYPE_B} />&nbsp;{L_CASH_TYPE_B}
		<input type="radio" name="cash_type" value="2" {S_CHECKED_TYPE_C} />&nbsp;{L_CASH_TYPE_C}
	</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END cash_edit -->

<!-- BEGIN cashuser_edit -->

<!-- END cashuser_edit -->