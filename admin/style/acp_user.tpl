<!-- BEGIN display -->
<form action="{S_USER_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_USER_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_USER_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="3">{L_USER}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN user_list -->
<tr>
	
	<td class="{display.user_list.CLASS}" align="left" width="100%">{display.user_list.USERNAME}</td>
	<td class="{display.user_list.CLASS}" align="left" width="1%">{display.user_list.USER_LEVEL}</td>
	<td class="{display.user_list.CLASS}" align="center" nowrap>{display.user_list.JOINED}</td>
	<td class="{display.user_list.CLASS}" align="center" width="1%"><a href="{display.user_list.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.user_list.CLASS}" align="center" width="1%"><a href="{display.user_list.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END user_list -->
</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="99%" align="right"><input class="post" name="training_vs" type="text" value="" /></td>
	<td width="1%" align="right">{S_TEAMS}</td>
	<td><input class="button" type="submit" name="add" value="{L_USER_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN user_edit -->
<form action="{S_USER_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_USER_ACTION}">{L_USER_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_USER_NEW_EDIT}</a></li>
				<li><a href="{S_USER_GROUP}">{L_USER_GROUP}</a></li>
				<li><a href="{S_USER_AUTHS}">{L_USER_AUTHS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />
<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="160">{L_USERNAME}: *</td>
	<td class="row2"><input id="username" onBlur="javascript:checkEntry(this)" class="post" type="text" name="username" value="{USERNAME}" ></td>
</tr>
<tr>
	<td class="row1">{L_REGISTER}:</td>
	<td class="row2">{JOINED}</td>
</tr>
<tr>
	<td class="row1">{L_LAST_LOGIN}:</td>
	<td class="row2">{LAST_VISIT}</td>
</tr>
<tr>
	<td class="row1">{L_FOUNDER}:</td>
	<td class="row3"><input type="radio" name="user_founder" value="1" {S_CHECKED_FOUNDER_YES} /> {L_YES} <input type="radio" name="user_founder" value="0" {S_CHECKED_FOUNDER_NO} /> {L_NO} </td>
</tr>
<tr>
	<td class="row1" width="160">{L_EMAIL}:</td>
	<td class="row2"><input class="post" type="text" name="user_email" value="{USER_EMAIL}" ></td>
</tr>
<tr>
	<td class="row1" width="160">{L_EMAIL_CONFIRM}:</td>
	<td class="row2"><input class="post" type="text" name="user_email_confirm" value="{USER_EMAIL_CONFIRM}" ></td>
</tr>
<tr>
	<td class="row1" width="160">{L_PASSWORD}:</td>
	<td class="row2"><input class="post" type="password" name="user_password" value="{USER_PASSWORD}" ></td>
</tr>
<tr>
	<td class="row1" width="160">{L_PASSWORD_CONFIRM}:</td>
	<td class="row2"><input class="post" type="password" name="user_password_confirm" value="{USER_PASSWORD_CONFIRM}" ></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END user_edit -->

<!-- BEGIN user_groups -->
<form action="{S_USER_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_USER_ACTION}">{L_USER_HEAD}</a></li>
				<li><a href="{S_USER_EDIT}">{L_USER_NEW_EDIT}</a></li>
				<li id="active"><a href="#" id="current">{L_USER_GROUP}</a></li>
				<li><a href="{S_USER_AUTHS}">{L_USER_AUTHS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<th colspan="2">{L_USER_GROUPS}</th>
</tr>
<!-- BEGIN groups_row -->
<tr>
	<td class="row1">{user_groups.groups_row.U_GROUP_NAME}</td>
	<td class="row3">
		<input type="radio" name="{user_groups.groups_row.S_MARK_NAME}" value="{user_groups.groups_row.S_MARK_ID}" {user_groups.groups_row.S_ASSIGNED_GROUP}  />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="{user_groups.groups_row.S_MARK_NAME}" value="{user_groups.groups_row.S_NEG_MARK_ID}" {user_groups.groups_row.S_UNASSIGNED_GROUP}  />&nbsp;{L_NO}
		{user_groups.groups_row.U_USER_PENDING}
	</td>
</tr>
<!-- END groups_row -->
<tr>
	<td class="row1">{L_EMAIL_NOTIFICATION}mail</td>
	<td class="row3">
		<input type="radio" name="email_notification" value="1" checked="checked" />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="email_notification" value="0"/> {L_NO}
	</td>
</tr>
<tr>
	<th colspan="2">{L_USER_TEAMS}</th>
</tr>
<!-- BEGIN teams_row -->
<tr>
	<td class="row1">{user_groups.teams_row.U_TEAM_NAME}</td>
	<td class="row3">
		<input type="radio" name="{user_groups.teams_row.S_MARK_NAME}" value="{user_groups.teams_row.S_MARK_ID}" {user_groups.teams_row.S_ASSIGNED_TEAM}  />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="{user_groups.teams_row.S_MARK_NAME}" value="{user_groups.teams_row.S_NEG_MARK_ID}" {user_groups.teams_row.S_UNASSIGNED_TEAM}  />&nbsp;{L_NO}
	</td>
</tr>
<!-- END teams_row -->
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END user_groups -->

<!-- BEGIN user_auths -->
<form action="{S_USER_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_USER_ACTION}">{L_USER_HEAD}</a></li>
				<li><a href="{S_USER_EDIT}">{L_USER_NEW_EDIT}</a></li>
				<li><a href="{S_USER_GROUP}">{L_USER_GROUP}</a></li>
				<li id="active"><a href="#" id="current">{L_USER_AUTHS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_USER_AUTHS_EXPLAIN}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<!-- BEGIN user_auth_data -->
<tr>
	<td class="row1" width="49%" align="right">{user_auths.user_auth_data.CELL_TITLE}</td>
	<td class="row3" width="1%" nowrap>{user_auths.user_auth_data.S_AUTH_LEVELS_SELECT}</td>
	<td class="row3" width="50%" nowrap>{user_auths.user_auth_data.S_AUTH_LEVELS_DEFAULT}</td>
</tr>
<!-- END user_auth_data -->
<tr>
	<td colspan="3" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>

<!-- END user_auths -->

<!-- BEGIN user_settings -->

<!-- END user_settings -->