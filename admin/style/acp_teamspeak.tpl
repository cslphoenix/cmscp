<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<!-- BEGIN _management -->
	<li><a href="{S_MEMBER}">{L_MEMBER}member</a></li>
	<!-- END _management -->
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
<!-- BEGIN _teamspeak_row -->
<tr class="hover">
	<td class="row_class1" align="left">{_display._teamspeak_row.NAME}</td>
	<td class="row_class2" align="center">{_display._teamspeak_row.UPDATE} {_display._teamspeak_row.DELETE}</td>		
</tr>
<!-- END _teamspeak_row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td class="entry_empty" align="center" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="teamspeak_name" value=""></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- BEGIN _server -->
<table class="edit" width="100%" cellspacing="1">
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_CURRENT}</a></li>
			</ul>
		</div>
	</td>
</tr>
<!-- BEGIN _off -->
<tr>
	<td colspan="2" align="center">Fehler: Server Offline oder falsche Daten</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- END _off -->
<!-- BEGIN _on -->
<tr>
	<td>{L_SERVER_NAME}</td>
	<td>{_display._server._on.SERVER_NAME}</td>
</tr>
<tr>
	<td>{L_SERVER_PLATFORM}</td>
	<td>{_display._server._on.SERVER_PLATFORM}</td>
</tr>
<tr>
	<td>{L_SERVER_WELCOME_MSG}</td>
	<td>{_display._server._on.SERVER_WELCOME_MSG}</td>
</tr>
<tr>
	<td>{L_SERVER_WEB_LINK}</td>
	<td>{_display._server._on.SERVER_WEB_LINK}</td>
</tr>
<tr>
	<td>{L_SERVER_WEB_POST}</td>
	<td>{_display._server._on.SERVER_WEB_POST}</td>
</tr>
<tr>
	<td>{L_SERVER_PASSWORD}</td>
	<td>{_display._server._on.SERVER_PASSWORD}</td>
</tr>
<tr>
	<td>{L_SERVER_TYPE}</td>
	<td>{_display._server._on.SERVER_TYPE}</td>
</tr>
<tr>
	<td>{L_SERVER_USER_MAX}</td>
	<td>{_display._server._on.SERVER_USER_MAX}</td>
</tr>
<tr>
	<td>{L_SERVER_USER_CURRENT}</td>
	<td>{_display._server._on.SERVER_USER_CURRENT}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_1}</td>
	<td>{_display._server._on.SERVER_CODEC_1}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_2}</td>
	<td>{_display._server._on.SERVER_CODEC_2}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_3}</td>
	<td>{_display._server._on.SERVER_CODEC_3}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_4}</td>
	<td>{_display._server._on.SERVER_CODEC_4}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_5}</td>
	<td>{_display._server._on.SERVER_CODEC_5}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_6}</td>
	<td>{_display._server._on.SERVER_CODEC_6}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_7}</td>
	<td>{_display._server._on.SERVER_CODEC_7}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_8}</td>
	<td>{_display._server._on.SERVER_CODEC_8}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_9}</td>
	<td>{_display._server._on.SERVER_CODEC_9}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_10}</td>
	<td>{_display._server._on.SERVER_CODEC_10}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_11}</td>
	<td>{_display._server._on.SERVER_CODEC_11}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_12}</td>
	<td>{_display._server._on.SERVER_CODEC_12}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_13}</td>
	<td>{_display._server._on.SERVER_CODEC_13}</td>
</tr>
<tr>
	<td>{L_SERVER_SEND_PACKET}</td>
	<td>{_display._server._on.SERVER_SEND_PACKET}</td>
</tr>
<tr>
	<td>{L_SERVER_SEND_BYTE}</td>
	<td>{_display._server._on.SERVER_SEND_BYTE}</td>
</tr>
<tr>
	<td>{L_SERVER_RECEIVED_PACKET}</td>
	<td>{_display._server._on.SERVER_RECEIVED_PACKET}</td>
</tr>
<tr>
	<td>{L_SERVER_RECEIVED_BYTE}</td>
	<td>{_display._server._on.SERVER_RECEIVED_BYTE}</td>
</tr>
<tr>
	<td>{L_SERVER_UPTIME}</td>
	<td>{_display._server._on.SERVER_UPTIME}</td>
</tr>
<tr>
	<td>{L_SERVER_NUM_CHANNELS}</td>
	<td>{_display._server._on.SERVER_NUM_CHANNELS}</td>
</tr>
<!-- END _on -->
</table>
<!-- END _server -->
<!-- END _display -->

<!-- BEGIN _input -->
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
	<td class="row1"><label for="teamspeak_type">{L_TYPE}:</label></td>
	<td class="row2"><label><input type="radio" name="teamspeak_type" value="0" {S_TYPE_TS2} />&nbsp;{L_TS2}</label><span style="padding:4px;"></span><label><input type="radio" name="teamspeak_type" value="1" {S_TYPE_TS3} />&nbsp;{L_TS3}</label></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="teamspeak_name" id="teamspeak_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_ip">{L_IP}: *</label></td>
	<td class="row2"><input type="text" class="post" name="teamspeak_ip" id="teamspeak_ip" value="{IP}"></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_port" title="{L_PORT_EXPLAIN}">{L_PORT}: *</label></td>
	<td class="row2"><input type="text" class="post" name="teamspeak_port" id="teamspeak_port" value="{PORT}"></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_qport" title="{L_QPORT_EXPLAIN}">{L_QPORT}: *</label></td>
	<td class="row2"><input type="text" class="post" name="teamspeak_qport" id="teamspeak_qport" value="{QPORT}"></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_pass">{L_PASS}:</label></td>
	<td class="row2"><input type="text" class="post" name="teamspeak_pass" id="teamspeak_pass" value="{PASS}"></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_show">{L_SHOW}:</label></td>
	<td class="row2"><label><input type="radio" name="teamspeak_show" value="1" {S_SHOW_YES} />&nbsp;{L_SHOW}</label><span style="padding:4px;"></span><label><input type="radio" name="teamspeak_show" value="0" {S_SHOW_NO} />&nbsp;{L_NOSHOW}</label></td>
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
<!--
<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="20%">{L_NAME}: *</td>
	<td class="row3" width="80%"><input id="teamspeak_name" class="post" type="text" name="teamspeak_name" size="25" value="{TEAMSPEAK_NAME}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_IP}: *</td>
	<td class="row2"><input id="teamspeak_ip" class="post" type="text" name="teamspeak_ip" size="25" value="{TEAMSPEAK_IP}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_PORT}: *</td>
	<td class="row2"><input id="teamspeak_port" class="post" type="text" name="teamspeak_port" value="{TEAMSPEAK_PORT}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_QPORT}: *</td>
	<td class="row2"><input id="teamspeak_qport" class="post" type="text" name="teamspeak_qport" value="{TEAMSPEAK_QPORT}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_PASS}: *</td>
	<td class="row2"><input id="teamspeak_pass" class="post" type="text" name="teamspeak_pass" value="{TEAMSPEAK_PASS}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_JOIN}: *</td>
	<td class="row2"><input id="teamspeak_join_name" class="post" type="text" name="teamspeak_join_name" value="{TEAMSPEAK_JOIN}"></td>
</tr>
<tr>
	<td class="row1">{L_CSTATS}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_cstats" value="1" {S_CSTATS_YES} /> {L_SHOW}
		<input type="radio" name="teamspeak_cstats" value="0" {S_CSTATS_NO} /> {L_NOSHOW}
	</td>
</tr>
<tr>
	<td class="row1">{L_USTATS}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_ustats" value="1" {S_USTATS_YES} /> {L_SHOW}
		<input type="radio" name="teamspeak_ustats" value="0" {S_USTATS_NO} /> {L_NOSHOW}
	</td>
</tr>
<tr>
	<td class="row1">{L_SSTATS}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_sstats" value="1" {S_SSTATS_YES} /> {L_SHOW}
		<input type="radio" name="teamspeak_sstats" value="0" {S_SSTATS_NO} /> {L_NOSHOW}
	</td>
</tr>
<tr>
	<td class="row1">{L_MOUSEO}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_mouseover" value="1" {S_MOUSEO_YES} /> {L_YES}
		<input type="radio" name="teamspeak_mouseover" value="0" {S_MOUSEO_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1">{L_VIEWER}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_show" value="1" {S_VIEWER_YES} /> {L_SHOW}
		<input type="radio" name="teamspeak_show" value="0" {S_VIEWER_NO} /> {L_NOSHOW}
	</td>
</tr>
</table>
-->
</form>
<!-- END _input -->