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

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="3">{L_USER}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN user_list -->
<tr>
	
	<td class="{display.user_list.CLASS}" align="left" width="100%">{display.user_list.USERNAME}</td>
	<td class="{display.user_list.CLASS}" align="left" width="1%">{display.user_list.USER_LEVEL}</td>
	<td class="{display.user_list.CLASS}" align="center" nowrap="nowrap">{display.user_list.JOINED}</td>
	<td class="{display.user_list.CLASS}" align="center" width="1%">{display.user_list.EDIT}</td>		
	<td class="{display.user_list.CLASS}" align="center" width="1%">{display.user_list.DELETE}</td>
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
	<td width="99%" align="right"><input class="post" name="username" type="text" /></td>
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

<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_USER_REGISTER}</a></li>
				<li><a href="{S_USER_FIELDS}">{L_USER_FIELDS}</a></li>
				<li><a href="{S_USER_SETTINGS}">{L_USER_SETTINGS}</a></li>
				<li><a href="{S_USER_IMAGES}">{L_USER_IMAGES}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>
<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="160">{L_USERNAME}: *</td>
	<td class="row3"><input id="username" onBlur="javascript:checkEntry(this)" class="post" type="text" name="username" value="{USERNAME}" ></td>
</tr>
<!-- BEGIN edituser -->
<tr>
	<td class="row1">{L_REGISTER}:</td>
	<td class="row3">{JOINED}</td>
</tr>
<tr>
	<td class="row1">{L_LAST_LOGIN}:</td>
	<td class="row3">{LAST_VISIT}</td>
</tr>
<tr>
	<td class="row1">{L_FOUNDER}:</td>
	<td class="row3"><input type="radio" name="user_founder" value="1" {S_CHECKED_FOUNDER_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="user_founder" value="0" {S_CHECKED_FOUNDER_NO} />&nbsp;{L_NO} </td>
</tr>
<!-- END edituser -->
<tr>
	<td class="row1" width="160">{L_EMAIL}:</td>
	<td class="row3"><input class="post" type="text" name="user_email" value="{USER_EMAIL}" ></td>
</tr>
<tr>
	<td class="row1" width="160">{L_EMAIL_CONFIRM}:</td>
	<td class="row3"><input class="post" type="text" name="email_confirm" value="" ></td>
</tr>
<tr>
	<td class="row1" width="160">{L_PASSWORD}:</td>
	<td class="row3">
		<input type="radio" name="pass" value="1" onChange="document.getElementById('pass').style.display = '';" /> Passwort eintragen
		<input type="radio" name="pass" value="0" onChange="document.getElementById('pass').style.display = 'none';" checked="checked" /> Passwort generieren
	</td>
</tr>
<tr>
	<td class="row1" width="160">{L_PASSWORD}:</td>
	<td class="row3">
		<input type="radio" name="random" value="0" /> {PASS_1}<br>
		<input type="radio" name="random" value="1" /> {PASS_2}<br>
		<input type="radio" name="random" value="2" /> {PASS_3}<br>
		<input type="radio" name="random" value="3" /> {PASS_4}<br>
		<input type="radio" name="random" value="4" /> {PASS_5}<br>
		<input type="radio" name="random" value="5" /> {PASS_6}
	</td>
</tr>
<tbody id="pass" style="display: none;">
<tr>
	<td class="row1" width="160">{L_PASSWORD}:</td>
	<td class="row2"><input class="post" type="password" name="new_password" value="" ></td>
</tr>
<tr>
	<td class="row1" width="160">{L_PASSWORD_CONFIRM}:</td>
	<td class="row2"><input class="post" type="password" name="password_confirm" value="" ></td>
</tr>
</tbody>
<!-- BEGIN catrow -->
<tr>
	<td class="row1" colspan="2">{user_edit.catrow.CATEGORY_NAME}</td>
</tr>
<!-- BEGIN profilerow -->
<tr> 
	<td class="row1" >{user_edit.catrow.profilerow.NAME}</td>
	<td class="row3">{user_edit.catrow.profilerow.FIELD}</td>
</tr>
<!-- END profilerow -->
<!-- END catrow -->
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END user_edit -->

<!-- BEGIN user_fields -->
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

<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				
				<li><a href="{S_USER_EDIT}">{L_USER_REGISTER}</a></li>
				<li id="active"><a href="#" id="current">{L_USER_FIELDS}</a></li>
				<li><a href="{S_USER_SETTINGS}">{L_USER_SETTINGS}</a></li>
				<li><a href="{S_USER_IMAGES}">{L_USER_IMAGES}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<table class="edit" cellspacing="1">
<!-- BEGIN catrow -->
<tr>
	<th colspan="2">{user_fields.catrow.CATEGORY_NAME}</th>
</tr>
<!-- BEGIN profilerow -->
<tr> 
	<td class="row1" width="160">{user_fields.catrow.profilerow.NAME}</td>
	<td class="row3">{user_fields.catrow.profilerow.FIELD}</td>
</tr>
<!-- END profilerow -->
<!-- END catrow -->
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END user_fields -->

<!-- BEGIN user_settings -->
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

<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				
				<li><a href="{S_USER_EDIT}">{L_USER_REGISTER}</a></li>
				<li><a href="{S_USER_FIELDS}">{L_USER_FIELDS}</a></li>
				<li id="active"><a href="#" id="current">{L_USER_SETTINGS}</a></li>
				<li><a href="{S_USER_IMAGES}">{L_USER_IMAGES}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<table class="edit" cellspacing="1">
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END user_settings -->

<!-- BEGIN user_groups -->
<script>
function disable_checkbox(name)
{
	checkbox_name = name;
	document.getElementById(checkbox_name).disabled = 'disabled';
}

function activate_checkbox(name)
{
	checkbox_name = name;
	document.getElementById(checkbox_name).disabled = '';
}
</script>
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

<br>

<table class="edit" cellspacing="1">
<tr>
	<th colspan="2">{L_USER_GROUPS}</th>
</tr>
<!-- BEGIN groups_row -->
<tr>
	<td class="row1">{user_groups.groups_row.U_GROUP_NAME}</td>
	<td class="row3">
		<input type="radio" onClick="activate_checkbox('checkbox_{user_groups.groups_row.S_MARK_ID}');" name="{user_groups.groups_row.S_MARK_NAME}" value="{user_groups.groups_row.S_MARK_ID}" {user_groups.groups_row.S_ASSIGNED_GROUP}  />&nbsp;{L_YES}&nbsp;&nbsp;
		<input type="radio" onClick="disable_checkbox('checkbox_{user_groups.groups_row.S_MARK_ID}');" name="{user_groups.groups_row.S_MARK_NAME}" value="{user_groups.groups_row.S_NEG_MARK_ID}" {user_groups.groups_row.S_UNASSIGNED_GROUP}  />&nbsp;{L_NO}&nbsp;&nbsp;
		<input type="checkbox" id="checkbox_{user_groups.groups_row.S_MARK_ID}" name="mod_{user_groups.groups_row.S_MARK_ID}" {user_groups.groups_row.S_MOD_GROUP} />&nbsp;{L_MODERATOR}&nbsp;&nbsp;
		{user_groups.groups_row.U_USER_PENDING}
	</td>
</tr>
<!-- END groups_row -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td class="row1">{L_EMAIL_NOTIFICATION}</td>
	<td class="row3"><input type="radio" name="email_notification" value="1" />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="email_notification" value="0" checked="checked" />&nbsp;{L_NO}</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<th colspan="2">{L_USER_TEAMS}</th>
</tr>
<!-- BEGIN teams_row -->
<tr>
	<td class="row1">{user_groups.teams_row.U_TEAM_NAME}</td>
	<td class="row3">
		
		<input type="radio" name="{user_groups.teams_row.S_MARK_NAME}" value="{user_groups.teams_row.S_MARK_ID}" {user_groups.teams_row.S_ASSIGNED_TEAM}  />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="{user_groups.teams_row.S_MARK_NAME}" value="{user_groups.teams_row.S_NEG_MARK_ID}" {user_groups.teams_row.S_UNASSIGNED_TEAM}  />&nbsp;{L_NO}&nbsp;&nbsp;
		<input type="checkbox" name="mod_{user_groups.teams_row.S_MARK_ID}" {user_groups.teams_row.S_MOD_TEAM} />&nbsp;{L_MODERATOR}
	</td>
</tr>
<!-- END teams_row -->
<tr>
	<td colspan="3" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<span class="middle">Checkboxen noch ohne Funktion!</span>
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

<br>


<script>
function set_color(name,farbe) {

	name = name;
	farbe = farbe; 
	document.getElementById(name).style.background = farbe;

}

/* Farbe setzen */

count = 18;
</script>



<table class="edit" cellspacing="1">
<!-- BEGIN user_auth_data -->
<tr>
	<td class="row1" width="49%" align="right">{user_auths.user_auth_data.CELL_TITLE}</td>
	<td class="row3" {user_auths.user_auth_data.STATUS} id="auth_farbe_{user_auths.user_auth_data.TEMP_ID}" width="1%" nowrap="nowrap">{user_auths.user_auth_data.S_AUTH_LEVELS_SELECT} {user_auths.user_auth_data.S_AUTH_LEVELS_DEFAULT}</td>
	<td class="row3" width="50%" nowrap="nowrap"></td>
</tr>
<!-- END user_auth_data -->
<tr>
	<td colspan="3" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>

<!-- 
<script>
for(i=0; i<19; i++) {
	int = 'sett_ja_' + i;
	wert =	document.getElementById(int).checked;

	if(wert == false) {
		document.getElementById('auth_farbe_' + i).style.background = 'red';
	}

	if(wert == true) {
		document.getElementById('auth_farbe_' + i).style.background = 'green';
	}
}
</script>
/-->


<!-- END user_auths -->

<!-- BEGIN user_settings -->

<!-- END user_settings -->