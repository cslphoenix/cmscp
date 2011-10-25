<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _server_row -->
<tr>
	<td><span style="float: right;">{_display._server_row.USERS} {_display._server_row.STATUS}</span>{_display._server_row.GAME} {_display._server_row.NAME}</td>
	<td>{_display._server_row.MOVE_UP}{_display._server_row.MOVE_DOWN}{_display._server_row.UPDATE} {_display._server_row.DELETE}</td>
</tr>
<!-- END _server_row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td class="entry_empty" align="center" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="server_name"></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
{AJAX}
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li><a href="{S_ACTION}">{L_HEAD}</a></li>
		<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="server_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="server_name" id="server_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="server_type">{L_TYPE}:</label></td>
	<td class="row2">
		<label><input type="radio" name="server_type" onclick="setRequest('0')" id="server_type" value="0" {S_GAMESERVER} />&nbsp;{L_GAMESERVER}</label><span style="padding:4px;"></span>
		<label><input type="radio" name="server_type" onclick="setRequest('1')" value="1" {S_VOICESERVER} />&nbsp;{L_VOICESERVER}</label></td>
</tr>
<tr>
	<td class="row1"><label for="server_game">{L_GAME}: *</label></td>
	<td class="row2"><div id="close">{S_GAME}</div><div id="content"></div></td>
</tr>
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
	<td class="row1"><label for="server_live">{L_LIVE}:</label></td>
	<td class="row2"><label><input type="radio" name="server_live" id="server_live" value="1" {S_LIVE_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="server_live" value="0" {S_LIVE_NO} />&nbsp;{L_NO}</label></td>
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
	<td class="row1"><label for="server_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
</tr>
</tbody>
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