<form action="{S_TEAM_ACTION}" method="post">
	
<table class="head" cellspacing="0">
<tr>
	<th>{L_SERVER_HEAD} - {L_SERVER_NEW_EDIT}</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row3" width="80%"><input class="post" type="text" name="server_name" value="{SERVER_NAME}" ></td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_type" value="1" {S_CHECKED_TYPE_GAME} /> {L_SERVER_GAME}
		<input type="radio" name="server_type" value="2" {S_CHECKED_TYPE_VOICE} /> {L_SERVER_VOICE}
	</td>
</tr>
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row3"><input class="post" type="text" name="server_ip" value="{SERVER_IP}" ></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row3"><input class="post" type="text" name="server_port" value="{SERVER_PORT}" ></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row3"><input class="post" type="text" name="server_qport" value="{SERVER_QPORT}" ></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_SERVER_NAME}: *</td>
	<td class="row3"><input class="post" type="text" name="server_pw" value="{SERVER_PW}" ></td>
</tr>
<tr>
	<td class="row1">{L_SERVER_LIVE}:</td>
	<td class="row3">{S_LIVE}&nbsp;
	</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_live" value="0" {S_CHECKED_LIVE_NO} /> {L_NO}
		<input type="radio" name="server_live" value="1" {S_CHECKED_LIVE_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_list" value="0" {S_CHECKED_LIST_NO} /> {L_NO}
		<input type="radio" name="server_list" value="1" {S_CHECKED_LIST_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_show" value="0" {S_CHECKED_SHOW_NO} /> {L_NO}
		<input type="radio" name="server_show" value="1" {S_CHECKED_SHOW_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="server_own" value="0" {S_CHECKED_ONW_NO} /> {L_NO}
		<input type="radio" name="server_own" value="1" {S_CHECKED_OWN_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>