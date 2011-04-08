<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%" colspan="2">{L_NAME}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _server_row -->
<tr>
	<td class="row_class1" align="left" width="1%">{_display._server_row.IMAGE}</td>
	<td class="row_class1" align="left">{_display._server_row.NAME}</td>
	<td class="row_class2" align="center">{_display._server_row.MOVE_UP}{_display._server_row.MOVE_DOWN} <a href="{_display._server_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._server_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _server_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END _no_entry -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="server_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
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

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1"><label for="server_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="server_name" id="server_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="server_type">{L_LIVE}:</label></td>
	<td class="row2">{S_LIVE}</td>
<tr>
	<td class="row1"><label for="server_ip">{L_IP}: *</label></td>
	<td class="row2"><input type="text" class="post" name="server_ip" id="server_ip" value="{IP}"></td>
</tr>
<tr>
	<td class="row1"><label for="server_port">{L_PORT}: *</label></td>
	<td class="row2"><input type="text" class="post" name="server_port" id="server_port" value="{PORT}"></td>
</tr>
<tr>
	<td class="row1"><label for="server_qport">{L_QPORT}: *</label></td>
	<td class="row2"><input type="text" class="post" name="server_qport" id="server_qport" value="{QPORT}"></td>
</tr>
<tr>
	<td class="row1"><label for="server_pw">{L_PW}:</label></td>
	<td class="row2"><input type="text" class="post" name="server_pw" id="server_pw" value="{PW}"></td>
</tr>
<tr>
	<td class="row1"><label for="server_list">{L_LIST}:</label></td>
	<td class="row2"><label><input type="radio" name="server_list" id="server_list" value="1" {S_LIST_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="server_list" value="0" {S_LIST_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="server_show">{L_SHOW}:</label></td>
	<td class="row2"><label><input type="radio" name="server_show" id="server_show" value="1" {S_SHOW_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="server_show" value="0" {S_SHOW_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="server_own">{L_OWN}:</label></td>
	<td class="row2"><label><input type="radio" name="server_own" id="server_own" value="1" {S_OWN_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="server_own" value="0" {S_OWN_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1 top"><label for="server_order">{L_ORDER}:</label></td>
	<td class="row2 top">{S_ORDER}</td>
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