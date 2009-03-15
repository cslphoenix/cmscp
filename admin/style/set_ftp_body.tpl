<form action="{S_SET_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>{L_SET_TITLE}</th>
</tr>
<tr>
	<td class="row2">{L_SET_EXPLAIN}:</b></td>
</tr>
</table>

<br />

<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="edit" cellspacing="1">

	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="row1" width="50%"><b>{L_SERVER_NAME}server: / port{L_SERVER_PORT}:</b></td>
		<td class="row3" width="50%"><input class="post" type="text" maxlength="255" size="25" name="server" value="{SERVER}" /> : <input class="post" type="text" maxlength="5" size="5" name="port" value="{PORT}" /></td>
	</tr>
	
	<tr>
		<td class="row1"><b>{L_SCRIPT_PATH}path:</b></td>
		<td class="row3">{S_PATH_PAGE}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_SITE_NAME}user:</b></td>
		<td class="row3">{S_PATH_PERMS}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_SITE_NAME}user:</b></td>
		<td class="row3"><input class="post" type="text" size="25" maxlength="100" name="user" value="{USER}" /></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_SITE_NAME}pass:</b></td>
		<td class="row3"><input class="post" type="password" size="25" maxlength="100" name="pass" value="{PASS}" /></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}senden" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}reset" class="button" />
		</td>
	</tr>
</table></form>

<br clear="all" />
