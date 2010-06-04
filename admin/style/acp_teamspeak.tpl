<!-- BEGIN display -->
<form action="{S_TEAMSPEAK_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_TEAMSPEAK_TITLE}</a></li>
				<li><a href="{S_TEAMSPEAK_EDIT}">{L_TEAMSPEAK_NEW_EDIT}</a></li>
				<!-- BEGIN user -->
				<li><a href="{S_TEAMSPEAK_MEMBER}">{L_TEAMSPEAK_USER}</a></li>
				<!-- END user -->
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_TEAMSPEAK_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="3">{L_TEAMSPEAK_SERVER}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN ts_data -->
<tr>
	<td class="{display.ts_data.CLASS}" align="left" width="100%">{display.ts_data.TS_NAME}</td>
	<td class="{display.ts_data.CLASS}" align="left" nowrap="nowrap">{display.ts_data.TS_IP}:{display.ts_data.TS_PORT}</td>
	<td class="{display.ts_data.CLASS}" align="center" nowrap="nowrap">{display.ts_data.TS_VIEW}</td>
	<td class="{display.ts_data.CLASS}" align="center" nowrap="nowrap"><a href="{display.ts_data.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.ts_data.CLASS}" align="center" nowrap="nowrap"><a href="{display.ts_data.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END ts_data -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<!-- BEGIN server -->
<table class="edit" width="100%" cellspacing="1">
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_TEAMSPEAK_CURRENT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<!-- BEGIN off -->
<tr>
	<td colspan="2" align="center">Fehler: Server Offline oder falsche Daten</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- END off -->
<!-- BEGIN on -->
<tr>
	<td>{L_SERVER_NAME}</td>
	<td>{display.server.on.SERVER_NAME}</td>
</tr>
<tr>
	<td>{L_SERVER_PLATFORM}</td>
	<td>{display.server.on.SERVER_PLATFORM}</td>
</tr>
<tr>
	<td>{L_SERVER_WELCOME_MSG}</td>
	<td>{display.server.on.SERVER_WELCOME_MSG}</td>
</tr>
<tr>
	<td>{L_SERVER_WEB_LINK}</td>
	<td>{display.server.on.SERVER_WEB_LINK}</td>
</tr>
<tr>
	<td>{L_SERVER_WEB_POST}</td>
	<td>{display.server.on.SERVER_WEB_POST}</td>
</tr>
<tr>
	<td>{L_SERVER_PASSWORD}</td>
	<td>{display.server.on.SERVER_PASSWORD}</td>
</tr>
<tr>
	<td>{L_SERVER_TYPE}</td>
	<td>{display.server.on.SERVER_TYPE}</td>
</tr>
<tr>
	<td>{L_SERVER_USER_MAX}</td>
	<td>{display.server.on.SERVER_USER_MAX}</td>
</tr>
<tr>
	<td>{L_SERVER_USER_CURRENT}</td>
	<td>{display.server.on.SERVER_USER_CURRENT}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_1}</td>
	<td>{display.server.on.SERVER_CODEC_1}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_2}</td>
	<td>{display.server.on.SERVER_CODEC_2}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_3}</td>
	<td>{display.server.on.SERVER_CODEC_3}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_4}</td>
	<td>{display.server.on.SERVER_CODEC_4}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_5}</td>
	<td>{display.server.on.SERVER_CODEC_5}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_6}</td>
	<td>{display.server.on.SERVER_CODEC_6}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_7}</td>
	<td>{display.server.on.SERVER_CODEC_7}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_8}</td>
	<td>{display.server.on.SERVER_CODEC_8}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_9}</td>
	<td>{display.server.on.SERVER_CODEC_9}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_10}</td>
	<td>{display.server.on.SERVER_CODEC_10}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_11}</td>
	<td>{display.server.on.SERVER_CODEC_11}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_12}</td>
	<td>{display.server.on.SERVER_CODEC_12}</td>
</tr>
<tr>
	<td>{L_SERVER_CODEC_13}</td>
	<td>{display.server.on.SERVER_CODEC_13}</td>
</tr>
<tr>
	<td>{L_SERVER_SEND_PACKET}</td>
	<td>{display.server.on.SERVER_SEND_PACKET}</td>
</tr>
<tr>
	<td>{L_SERVER_SEND_BYTE}</td>
	<td>{display.server.on.SERVER_SEND_BYTE}</td>
</tr>
<tr>
	<td>{L_SERVER_RECEIVED_PACKET}</td>
	<td>{display.server.on.SERVER_RECEIVED_PACKET}</td>
</tr>
<tr>
	<td>{L_SERVER_RECEIVED_BYTE}</td>
	<td>{display.server.on.SERVER_RECEIVED_BYTE}</td>
</tr>
<tr>
	<td>{L_SERVER_UPTIME}</td>
	<td>{display.server.on.SERVER_UPTIME}</td>
</tr>
<tr>
	<td>{L_SERVER_NUM_CHANNELS}</td>
	<td>{display.server.on.SERVER_NUM_CHANNELS}</td>
</tr>
<!-- END on -->
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td align="right" colspan="2"><input class="button" type="submit" value="{L_TEAMSPEAK_EDIT}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END server -->


<!-- BEGIN nothing -->
<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_TEAMSPEAK_CURRENT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td align="center">{NO_ENTRY}</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td align="right"><input type="hidden" name="mode" value="add" /><input class="button" type="submit" value="{L_TEAMSPEAK_ADD}"></td>
</tr>
</table>

<!-- END nothing -->
<!-- END display -->


<!-- BEGIN teamspeak_edit -->
<form action="{S_TEAMSPEAK_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_TEAMSPEAK_ACTION}">{L_TEAMSPEAK_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_TEAMSPEAK_NEW_EDIT}</a></li>
				<!-- BEGIN user -->
				<li id="active"><a href="{S_TEAMSPEAK_MEMBER}">{L_TEAMSPEAK_USER}</a></li>
				<!-- END user -->

			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="20%">{L_TEAMSPEAK_NAME}: *</td>
	<td class="row3" width="80%"><input id="teamspeak_name" class="post" type="text" name="teamspeak_name" size="25" value="{TEAMSPEAK_NAME}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_TEAMSPEAK_IP}: *</td>
	<td class="row3"><input id="teamspeak_ip" class="post" type="text" name="teamspeak_ip" size="25" value="{TEAMSPEAK_IP}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_TEAMSPEAK_PORT}: *</td>
	<td class="row3"><input id="teamspeak_port" class="post" type="text" name="teamspeak_port" value="{TEAMSPEAK_PORT}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_TEAMSPEAK_QPORT}: *</td>
	<td class="row3"><input id="teamspeak_qport" class="post" type="text" name="teamspeak_qport" value="{TEAMSPEAK_QPORT}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_TEAMSPEAK_PASS}: *</td>
	<td class="row3"><input id="teamspeak_pass" class="post" type="text" name="teamspeak_pass" value="{TEAMSPEAK_PASS}"></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_TEAMSPEAK_JOIN}: *</td>
	<td class="row3"><input id="teamspeak_join_name" class="post" type="text" name="teamspeak_join_name" value="{TEAMSPEAK_JOIN}"></td>
</tr>
<tr>
	<td class="row1">{L_TEAMSPEAK_CSTATS}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_cstats" value="1" {S_CSTATS_YES} /> {L_TEAMSPEAK_SHOW}
		<input type="radio" name="teamspeak_cstats" value="0" {S_CSTATS_NO} /> {L_TEAMSPEAK_NOSHOW}
	</td>
</tr>
<tr>
	<td class="row1">{L_TEAMSPEAK_USTATS}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_ustats" value="1" {S_USTATS_YES} /> {L_TEAMSPEAK_SHOW}
		<input type="radio" name="teamspeak_ustats" value="0" {S_USTATS_NO} /> {L_TEAMSPEAK_NOSHOW}
	</td>
</tr>
<tr>
	<td class="row1">{L_TEAMSPEAK_SSTATS}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_sstats" value="1" {S_SSTATS_YES} /> {L_TEAMSPEAK_SHOW}
		<input type="radio" name="teamspeak_sstats" value="0" {S_SSTATS_NO} /> {L_TEAMSPEAK_NOSHOW}
	</td>
</tr>
<tr>
	<td class="row1">{L_TEAMSPEAK_MOUSEO}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_mouseover" value="1" {S_MOUSEO_YES} /> {L_YES}
		<input type="radio" name="teamspeak_mouseover" value="0" {S_MOUSEO_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1">{L_TEAMSPEAK_VIEWER}:</td>
	<td class="row3">
		<input type="radio" name="teamspeak_show" value="1" {S_VIEWER_YES} /> {L_TEAMSPEAK_SHOW}
		<input type="radio" name="teamspeak_show" value="0" {S_VIEWER_NO} /> {L_TEAMSPEAK_NOSHOW}
	</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END teamspeak_edit -->

<!-- BEGIN teamspeak_member -->
<form action="{S_TEAMSPEAK_ACTION}" method="post">
<table class="head" cellspacing="0">

<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_TEAMSPEAK_ACTION}">{L_TEAMSPEAK_TITLE}</a></li>
				<li><a href="{S_TEAMSPEAK_EDIT}">{L_TEAMSPEAK_NEW_EDIT}</a></li>
				<li id="active"><a href="#" id="current">{L_TEAMSPEAK_USER}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">&nbsp;</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="4">{L_USERNAME}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN member_row -->
<tr>
	<td class="{teamspeak_member.member_row.CLASS}" align="left" width="100%">{teamspeak_member.member_row.USERNAME}</td>
	<td class="{teamspeak_member.member_row.CLASS}" align="center" nowrap="nowrap">{teamspeak_member.member_row.USER_LEVEL}</td>
	<td class="{teamspeak_member.member_row.CLASS}" align="center" nowrap="nowrap">{teamspeak_member.member_row.REGISTER}</td>
	<td class="{teamspeak_member.member_row.CLASS}" align="center" nowrap="nowrap">{teamspeak_member.member_row.LASTVIEW}</td>
	<td class="{teamspeak_member.member_row.CLASS}" align="center" width="1%">{teamspeak_member.member_row.EDIT}</td>
	<td class="{teamspeak_member.member_row.CLASS}" align="center" width="1%">{teamspeak_member.member_row.DELETE}</td>
</tr>
<!-- END member_row -->
	</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right">{S_TEAMS}</td>
	<td><input class="button" type="submit" name="add" value="{L_MATCH_CREATE}"></td>
</tr>
</table>
</form>
<!-- END teamspeak_member -->