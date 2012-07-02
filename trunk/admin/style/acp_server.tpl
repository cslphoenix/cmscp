<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN server_row -->
<tr>
	<td><span style="float: right;">{display.server_row.USERS} {display.server_row.STATUS}</span>{display.server_row.TYPE} {display.server_row.NAME}</td>
	<td>{display.server_row.MOVE_UP}{display.server_row.MOVE_DOWN}{display.server_row.UPDATE} {display.server_row.DELETE}</td>
</tr>
<!-- END server_row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="server_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN input -->
{AJAX}
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo">
	<li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="server_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="server_name" id="server_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="server_type">{L_TYPE}:</label></td>
	<td class="row2"><label><input type="radio" name="server_type" onclick="setRequest('0')" id="server_type" value="0" {S_GAMESERVER} />&nbsp;{L_GAMESERVER}</label><span style="padding:4px;"></span>
		<label><input type="radio" name="server_type" onclick="setRequest('1')" value="1" {S_VOICESERVER} />&nbsp;{L_VOICESERVER}</label></td>
</tr>
<tr>
	<td class="row1r"><label for="server_game">{L_GAME}:</label></td>
	<td><div id="close">{S_GAME}</div><div id="ajax_content"></div></td>
</tr>
<tr>
	<td class="row1r"><label for="server_ip">{L_IP}:</label></td>
	<td class="row2"><input type="text" name="server_ip" id="server_ip" value="{IP}"></td>
</tr>
<tr>
	<td class="row1r"><label for="server_port">{L_PORT}:</label></td>
	<td class="row2"><input type="text" name="server_port" id="server_port" value="{PORT}"></td>
</tr>
<tr>
	<td class="row1"><label for="server_pw">{L_PW}:</label></td>
	<td class="row2"><input type="text" name="server_pw" id="server_pw" value="{PW}"></td>
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
	<td>{S_ORDER}</td>
</tr>
</tbody>
<tr>
	<td colspan="2"></td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->