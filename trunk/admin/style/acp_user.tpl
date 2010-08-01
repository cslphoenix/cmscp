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
	<td class="rowHead" width="99%">{L_NAME}</td>
	<td class="rowHead">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _user_row -->
<tr>
	<td class="row_class1" align="left"><span style="float:right;">{_display._user_row.USER_LEVEL} {_display._user_row.JOINED}</span>{_display._user_row.USERNAME}</td>
	<td class="row_class2" align="center">{_display._user_row.EDIT} {_display._user_row.DELETE}</td>		
</tr>
<!-- END _user_row -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="username"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN user_regedit -->
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
	<td class="row4 small" align="right">{S_MODE}</td>
</tr>
</table>

<br />

</form>
<form action="{S_ACTION}" method="post">

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_REGISTER}</a></li>
</ul>
</div>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="155"><label for="username">{L_USERNAME}: *</label></td>
	<td class="row2"><input class="post" type="text" name="username" id="username" value="{USERNAME}"></td>
</tr>
<!-- BEGIN edituser -->
<tr>
	<td class="row1"><label>{L_REGISTER}:</label></td>
	<td class="row2">{REGISTER}</td>
</tr>
<tr>
	<td class="row1"><label>{L_LASTLOGIN}:</label></td>
	<td class="row2">{LASTLOGIN}</td>
</tr>
<tr>
	<td class="row1"><label for="user_founder">{L_FOUNDER}:</label></td>
	<td class="row2"><label><input type="radio" name="user_founder" value="1" {S_FOUNDER_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="user_founder" id="user_founder" value="0" {S_FOUNDER_NO} />&nbsp;{L_NO}</label></td>
</tr>
<!-- END edituser -->
<tr>
	<td class="row1"><label for="user_email">{L_EMAIL}:</label></td>
	<td class="row2"><input type="text" class="post" name="user_email" id="user_email" value="{USER_EMAIL}"></td>
</tr>
<tr>
	<td class="row1"><label for="email_confirm">{L_EMAIL_CONFIRM}:</label></td>
	<td class="row2"><input type="text" class="post" name="email_confirm" id="email_confirm" value=""></td>
</tr>
<tr>
	<td class="row1"><label>{L_PASSWORD}:</label></td>
	<td class="row2">
	<label><input type="radio" name="pass" value="1" onChange="document.getElementById('pass').style.display = 'none';document.getElementById('pass2').style.display = '';" />&nbsp;{L_PASSWORD_REGISTER}</label><br />
	<label><input type="radio" name="pass" value="0" onChange="document.getElementById('pass2').style.display = 'none';document.getElementById('pass').style.display = '';" checked="checked" />&nbsp;{L_PASSWORD_GENERATE}</label>
</td>
</tr>
<tbody id="pass" style="display:;">
<tr>
	<td class="row1 top"><label>{L_PASSWORD}:</label></td>
	<td class="row2">
		<label><input type="radio" name="random" value="0" /> {PASS_1}</label><br />
		<label><input type="radio" name="random" value="1" /> {PASS_2}</label><br />
		<label><input type="radio" name="random" value="2" /> {PASS_3}</label><br />
		<label><input type="radio" name="random" value="3" /> {PASS_4}</label><br />
		<label><input type="radio" name="random" value="4" /> {PASS_5}</label><br />
		<label><input type="radio" name="random" value="5" /> {PASS_6}</label>
	</td>
</tr>
</tbody>
<tbody id="pass2" style="display:none;">
<tr>
	<td class="row1"><label for="password_new">{L_PASSWORD}:</label></td>
	<td class="row2"><input class="post" type="password" name="password_new" id="password_new" value=""></td>
</tr>
<tr>
	<td class="row1"><label for="password_confirm">{L_PASSWORD_CONFIRM}:</label></td>
	<td class="row2"><input class="post" type="password" name="password_confirm" id="password_confirm" value=""></td>
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
<!-- END user_regedit -->

<!-- BEGIN catrow -->
<tr>
	<td class="row1" colspan="2">{user_regedit.catrow.CATEGORY_NAME}</td>
</tr>
<!-- BEGIN profilerow -->
<tr> 
	<td class="row1" >{user_regedit.catrow.profilerow.NAME}</td>
	<td class="row2">{user_regedit.catrow.profilerow.FIELD}</td>
</tr>
<!-- END profilerow -->
<!-- END catrow -->


<!-- BEGIN user_fields -->
<form action="{S_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_ACTION}">{L_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
				<li><a href="{S_GROUP}">{L_GROUP}</a></li>
				<li><a href="{S_AUTHS}">{L_AUTHS}</a></li>
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
				
				<li><a href="{S_EDIT}">{L_REGISTER}</a></li>
				<li id="active"><a href="#" id="current">{L_FIELDS}</a></li>
				<li><a href="{S_SETTINGS}">{L_SETTINGS}</a></li>
				<li><a href="{S_IMAGES}">{L_IMAGES}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN catrow -->
<tr>
	<th colspan="2">{user_fields.catrow.CATEGORY_NAME}</th>
</tr>
<!-- BEGIN profilerow -->
<tr> 
	<td class="row1" width="160">{user_fields.catrow.profilerow.NAME}</td>
	<td class="row2">{user_fields.catrow.profilerow.FIELD}</td>
</tr>
<!-- END profilerow -->
<!-- END catrow -->
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END user_fields -->

<!-- BEGIN user_settings -->
<form action="{S_ACTION}" method="post">
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="{S_EDIT}" id="current">{L_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_AUTH_EXPLAIN}</td>
	<td class="row2 small" align="right">{S_MODE}</td>
</tr>
</table>

<br />

</form>
<form action="{S_ACTION}" method="post" name="post">

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_SETTINGS}</a></li>
</ul>
</div>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="155"><label for="username">{L_USERNAME}: *</label></td>
	<td class="row2"><input class="post" type="text" name="username" id="username" value="{USERNAME}"></td>
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
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="{S_EDIT}" id="current">{L_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
	<td class="row2 small" align="right">{S_MODE}</td>
</tr>
</table>

<br />

</form>
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_GROUPS}</a></li>
</ul>
</div>
<table class="update" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN row_group -->
<tr>
	<td class="row1" width="46%">{user_groups.row_group.U_GROUP_NAME}</td>
	<td class="row2"><label><input type="radio" onClick="activate_checkbox('checkbox_group_{user_groups.row_group.S_MARK_ID}');" name="{user_groups.row_group.S_MARK_NAME}" value="{user_groups.row_group.S_MARK_ID}" {user_groups.row_group.S_ASSIGNED_GROUP}  />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" onClick="disable_checkbox('checkbox_group_{user_groups.row_group.S_MARK_ID}');" name="{user_groups.row_group.S_MARK_NAME}" value="{user_groups.row_group.S_NEG_MARK_ID}" {user_groups.row_group.S_UNASSIGNED_GROUP} />&nbsp;{L_NO}</label><span style="padding:4px;"></span><label><input type="checkbox" id="checkbox_group_{user_groups.row_group.S_MARK_ID}" name="mod_group_{user_groups.row_group.S_MARK_ID}" {user_groups.row_group.S_MOD_GROUP} />&nbsp;{L_MODERATOR}</label>{user_groups.row_group.U_USER_PENDING}</td>
</tr>
<!-- END row_group -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td class="row1"><label for="email_notification">{L_EMAIL_NOTIFICATION}</label></td>
	<td class="row2"><label><input type="radio" name="email_notification" value="1">&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="email_notification" id="email_notification" value="0" checked="checked">&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
</table>

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_TEAMS}</a></li>
</ul>
</div>
<table class="update" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN row_team -->
<tr>
	<td class="row1" width="46%">{user_groups.row_team.U_TEAM_NAME}</td>
	<td class="row2"><label><input type="radio" onClick="activate_checkbox('checkbox_team_{user_groups.row_team.S_MARK_ID}');" name="{user_groups.row_team.S_MARK_NAME}" value="{user_groups.row_team.S_MARK_ID}" {user_groups.row_team.S_ASSIGNED_TEAM}  />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" onClick="disable_checkbox('checkbox_team_{user_groups.row_team.S_MARK_ID}');" name="{user_groups.row_team.S_MARK_NAME}" value="{user_groups.row_team.S_NEG_MARK_ID}" {user_groups.row_team.S_UNASSIGNED_TEAM} />&nbsp;{L_NO}</label><span style="padding:4px;"></span><label><input type="checkbox" id="checkbox_team_{user_groups.row_team.S_MARK_ID}" name="mod_team_{user_groups.row_team.S_MARK_ID}" {user_groups.row_team.S_MOD_TEAM} />&nbsp;{L_MODERATOR}</label></td>
</tr>
<!-- END row_team -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td class="row1"><label for="email_notification2">{L_EMAIL_NOTIFICATION}</label></td>
	<td class="row2"><label><input type="radio" name="email_notification2" value="1">&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="email_notification2" id="email_notification2" value="0" checked="checked">&nbsp;{L_NO}</label></td>
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
<!-- END user_groups -->

<!-- BEGIN user_auth -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="{S_EDIT}" id="current">{L_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_AUTH_EXPLAIN}</td>
	<td class="row2 small" align="right">{S_MODE}</td>
</tr>
</table>

<br />

</form>
<form action="{S_ACTION}" method="post" name="post">
<script type="text/javascript">
// <![CDATA[
			
function activated()
{
	<!-- BEGIN list -->
	document.getElementById("{user_auth.list.NAME}").checked = true;
	document.getElementById("color_{user_auth.list.NAME}").style.color = '#2e8b57';
	<!-- END list -->
}

function deactivated()
{
	<!-- BEGIN list -->
	document.getElementById("de_{user_auth.list.NAME}").checked = true;
	document.getElementById("color_{user_auth.list.NAME}").style.color = '#ff6347';
	<!-- END list -->
}

function set_color(name,farbe)
{
	name = name;
	farbe = farbe;
	document.getElementById(name).style.color = farbe;
}

// ]]>
</script>

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_AUTH}</a></li>
</ul>
</div>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN data -->
<tr>
	<td class="row1" width="155"><label for="{user_auth.data.NAME}">{user_auth.data.TITLE}</label></td>
	<td class="row3" {user_auth.data.STATUS} id="color_{user_auth.data.NAME}" nowrap="nowrap">{user_auth.data.S_SELECT} {user_auth.data.S_DEFAULT}</td>
</tr>
<!-- END data -->
<tr>
	<td class="row2">&nbsp;</td>
	<td class="row2"><a class="small" href="#" onclick="activated();">&raquo;&nbsp;{L_SPECIAL}</a>&nbsp;&nbsp;<a class="small" href="#" onclick="deactivated();">&raquo;&nbsp;{L_DEFAULT}</a></td>
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
<!-- END user_auth -->

<!-- BEGIN user_settings -->

<!-- END user_settings -->