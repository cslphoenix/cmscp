<!-- BEGIN _display -->
<form action="{S_SERVER_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_SERVER_HEAD}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_SERVER_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead">{L_SERVER_NAME}</td>
	<td class="rowHead" colspan="3">{L_SETTINGS}</td>
</tr>
<!-- BEGIN server_row -->
<tr>
	<td class="{_display.server_row.CLASS}" align="left">{_display.server_row.SERVER_NAME}</td>
	<td class="{_display.server_row.CLASS}" align="center" width="1%"><a href="{_display.server_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{_display.server_row.CLASS}" align="center" width="6%"><a href="{_display.server_row.U_MOVE_UP}">{_display.server_row.ICON_UP}</a> <a href="{_display.server_row.U_MOVE_DOWN}">{_display.server_row.ICON_DOWN}</a></td>
	<td class="{_display.server_row.CLASS}" align="center" width="1%"><a href="{_display.server_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END server_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="game_name" type="text" value=""></td>
	<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_SERVER_ADD}"></td>
</tr>
</table>
</form>
<!-- END _display -->


<!-- BEGIN server_edit -->
<form action="{S_SERVER_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_SERVER_ACTION}">{L_SERVER_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_SERVER_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row3" width="80%"><input type="text" class="post" name="server_name" value="{SERVER_NAME}"></td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_type" value="1" {S_TYPE_GAME} /> {L_SERVER_GAME}
		<input type="radio" name="server_type" value="2" {S_TYPE_VOICE} /> {L_SERVER_VOICE}
	</td>
</tr>
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row2"><input type="text" class="post" name="server_ip" value="{SERVER_IP}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row2"><input type="text" class="post" name="server_port" value="{SERVER_PORT}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row2"><input type="text" class="post" name="server_qport" value="{SERVER_QPORT}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row2"><input type="text" class="post" name="server_pw" value="{SERVER_PW}"></td>
</tr>
<tr>
	<td class="row1">{L_SERVER_LIVE}:</td>
	<td class="row2">{S_LIVE}&nbsp;
	</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_live" value="0" {S_LIVE_NO} />&nbsp;{L_NO}
		<input type="radio" name="server_live" value="1" {S_LIVE_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_list" value="0" {S_LIST_NO} />&nbsp;{L_NO}
		<input type="radio" name="server_list" value="1" {S_LIST_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_show" value="0" {S_SHOW_NO} />&nbsp;{L_NO}
		<input type="radio" name="server_show" value="1" {S_SHOW_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_own" value="0" {S_ONW_NO} />&nbsp;{L_NO}
		<input type="radio" name="server_own" value="1" {S_OWN_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END server_edit -->

<!-- BEGIN _other -->

<!-- END _other -->