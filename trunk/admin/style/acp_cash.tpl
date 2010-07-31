<!-- BEGIN _display -->
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a href="{S_CREATE_USER}">{L_CREATE_USER}</a></li>
	<li><a id="setting" href="{S_BANKDATA}">{L_BD_INFO}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- BEGIN _bank -->
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

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="hidden" name="mode" value="_bankdata_delete"><input type="submit" class="button" value="{L_BD_DELETE}"></td>
</tr>
</table>
</form>

<br />
<!-- END _bank -->

<form action="{S_ACTION}" method="post">
<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_NAME}</td>
	<td class="rowHead" width="1%" align="center">{L_INTERVAL}</td>
	<td class="rowHead" width="1%" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _cash_row -->
<tr>
	<td class="row_class1" width="1%"><img src="{_display._cash_row.TYPE}" alt="" /></td>
	<td class="row_class1" align="left" width="99%"><span style="float:right;">{_display._cash_row.AMOUNT}&nbsp;</span>{_display._cash_row.NAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_display._cash_row.DATE}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{_display._cash_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cash_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _cash_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END _no_entry -->
<tr>
	<td class="rowHead" colspan="2" width="100%" align="right">{POSTAGE_CASH}</td>
	<td class="rowHead" colspan="3">{L_POSTAGE}</td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="cash_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
<input type="hidden" name="mode" value="_create">
</form>

<br />

<form action="{S_ACTION}" method="post">
<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" width="100%">{L_USERNAME}</td>
	<td class="rowHead" width="1%" align="center">{L_INTERVAL}</td>
	<td class="rowHead" width="1%" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _cashuser_row -->
<tr>
	<td class="row_class1" align="left" width="99%"><span style="float:right;">{_display._cashuser_row.AMOUNT}&nbsp;</span>{_display._cashuser_row.USERNAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_display._cashuser_row.INTERVAL}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{_display._cashuser_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cashuser_row.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END _cashuser_row -->
<!-- BEGIN _no_entry_user -->
<tr>
	<td class="row_noentry" align="center" colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END _no_entry_user -->
<tr>
	<td class="rowHead" width="100%" align="right">{POSTAGE_CASHUSER}</td>
	<td class="rowHead" colspan="2">{L_POSTAGE}</td>
</tr>
<tr>
	<td class="rowHead" width="100%" align="right"><span class="{POSTAGE_CLASS}">{POSTAGE}</span></td>
	<td class="rowHead" colspan="2">&nbsp;</td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right">{S_CREATE_USER_BOX}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE_USER}"></td>
</tr>
</table>
<input type="hidden" name="mode" value="_create_user">
</form>
<!-- END _display -->

<!-- BEGIN _input -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1"><label for="cash_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cash_name" id="cash_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="cash_amount">{L_AMOUNT}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cash_amount" id="cash_amount" value="{AMOUNT}"></td>
</tr>
<tr>
	<td class="row1 top"><label for="cash_type">{L_TYPE}:</label></td>
	<td class="row2">
		<label><input type="radio" name="cash_type" id="cash_type" value="0" {S_TYPE_GAME} />&nbsp;{L_TYPE_GAME}</label><br />
		<label><input type="radio" name="cash_type" value="1" {S_TYPE_VOICE} />&nbsp;{L_TYPE_VOICE}</label><br />
		<label><input type="radio" name="cash_type" value="2" {S_TYPE_OTHER} />&nbsp;{L_TYPE_OTHER}</label>
	</td>
</tr>
<tr>
	<td class="row1 top"><label for="cash_interval">{L_INTERVAL}:</label></td>
	<td class="row2">
		<label><input type="radio" name="cash_interval" id="cash_interval" value="0" {S_INT_MONTH} />&nbsp;{L_INTAVAL_MONTH}</label><br />
		<label><input type="radio" name="cash_interval" value="1" {S_INT_WEEKS} />&nbsp;{L_INTAVAL_WEEKS}</label><br />
		<label><input type="radio" name="cash_interval" value="2" {S_INT_WEEKLY} />&nbsp;{L_INTAVAL_WEEKLY}</label>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->

<!-- BEGIN _input_user -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="155">{L_USER}: *</td>
	<td class="row2">{S_USER}</td>
</tr>
<tr>
	<td class="row1">{L_USER_AMOUNT}: *</td>
	<td class="row2"><input type="text" class="post" name="user_amount" value="{AMOUNT}"></td>
</tr>
<tr>
	<td class="row1 top">{L_USER_MONTH}:</td>
	<td class="row2">{S_MONTH}</td>
</tr>
<tr>
	<td class="row1 top"><label for="user_interval">{L_USER_INTERVAL}:</label></td>
	<td class="row2">
		<label><input type="radio" name="user_interval" id="user_interval" value="0" {S_INTAVAL_MONTH} />&nbsp;{L_INTAVAL_MONTH}</label><br />
		<label><input type="radio" name="user_interval" value="1" {S_INTAVAL_ONLY} />&nbsp;{L_INTAVAL_ONLY}</label>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input_user -->

<!-- BEGIN _bankdata -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="#" id="right">{L_BANKDATA}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="155"><label for="bankdata_name">{L_NAME}:</label></td>
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
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _bankdata -->