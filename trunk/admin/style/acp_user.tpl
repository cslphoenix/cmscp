<!-- BEGIN head -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<p>{L_EXPLAIN}</p>
<ul id="navopts"><li>{S_MODE} <input type="submit" value="{L_GO}" /></li></ul>
</form>

<br /><div>{ERROR_BOX}</div>

<form action="{S_ACTION}" method="post" autocomplete="off">
<!-- END head -->

<!-- BEGIN input -->

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>

<table class="update">
<tr>
	<td class="row1r"><label for="user_name">{L_NAME}:</label></td>
	<td class="row2"><input class="post" type="text" name="user_name" id="user_name" value="{USERNAME}"></td>
</tr>
<!-- BEGIN update -->
<tr>
	<td class="row1"><label>{L_REGISTER}:</label></td>
	<td class="row2">{REGISTER}</td>
</tr>
<tr>
	<td class="row1"><label>{L_LASTLOGIN}:</label></td>
	<td class="row2">{LASTLOGIN}</td>
</tr>
<!-- END update -->
<tr>
	<td class="row1"><label for="user_founder">{L_FOUNDER}:</label></td>
	<td class="row2"><label><input type="radio" name="user_founder" value="1" {S_FOUNDER_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="user_founder" id="user_founder" value="0" {S_FOUNDER_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="user_active">{L_ACTIVE}:</label></td>
	<td class="row2"><label><input type="radio" name="user_active" id="user_active" value="1" {S_ACTIVE_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="user_active" value="0" {S_ACTIVE_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label>{L_LEVEL}:</label></td>
	<td class="row2">{S_LEVEL}</td>
</tr>
<tr>
	<td class="row1"><label>{L_BIRTHDAY}:</label></td>
	<td class="row2"><input class="post" type="text" name="user_birthday" id="datepicker" value="{BIRTHDAY}"></td>
</tr>
<tr>
	<td class="row1"><label for="user_email">{L_EMAIL}:</label></td>
	<td class="row2"><input type="text" name="user_email" id="user_email" value="{USEREMAIL}"></td>
</tr>
<tr>
	<td class="row1"><label for="user_email_confirm">{L_CONFIRM}:</label></td>
	<td class="row2"><input type="text" name="user_email_confirm" id="user_email_confirm"></td>
</tr>
<tr>
	<td class="row1"><label>{L_PASSWORD}:</label></td>
	<td class="row2"><label><input type="radio" name="pass_switch" value="1" onChange="document.getElementById('pass_random').style.display = ''; document.getElementById('pass_input').style.display = 'none';" {S_INPUT} />&nbsp;{L_PASSWORD_RANDOM}</label><br />
	<label><input type="radio" name="pass_switch" value="0" onChange="document.getElementById('pass_random').style.display = 'none'; document.getElementById('pass_input').style.display = '';" {S_RANDOM} />&nbsp;{L_PASSWORD_INPUT}</label>
	</td>
</tr>
<tbody id="pass_input" style="display:{INPUT};">
<tr>
	<td class="row1"><label for="password_new">{L_PASSWORD}:</label></td>
	<td class="row2"><input type="password" name="password_new" id="password_new" value=""></td>
</tr>
<tr>
	<td class="row1"><label for="password_confirm">{L_PASSWORD_CONFIRM}:</label></td>
	<td class="row2"><input type="password" name="password_confirm" id="password_confirm" value=""></td>
</tr>
</tbody>
<tbody id="pass_random" style="display:{RANDOM};">
<tr>
	<td class="row1"><label>{L_PASSWORD}:</label></td>
	<td class="row2"><label><input type="radio" name="password" value="0" /> {PASS_1}</label><br />
		<label><input type="radio" name="password" value="1" /> {PASS_2}</label><br />
		<label><input type="radio" name="password" value="2" /> {PASS_3}</label><br />
		<label><input type="radio" name="password" value="3" /> {PASS_4}</label><br />
		<label><input type="radio" name="password" value="4" /> {PASS_5}</label><br />
		<label><input type="radio" name="password" value="5" /> {PASS_6}</label>
	</td>
</tr>
</tbody>
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

<!-- BEGIN settings -->

<!-- BEGIN row -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1{settings.row.tab.option.CSS}"><label for="{settings.row.tab.option.LABEL}" {settings.row.tab.option.EXPLAIN}>{settings.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{settings.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
<!-- END row -->

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END settings -->

<!-- BEGIN fields -->
<table class="update">
<tr>
	<td colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_FIELDS}</a></li></ul></td>
</tr>
<!-- BEGIN cat_row -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{fields.catrow.CAT_NAME}</a></li></ul></th>
</tr>
<!-- BEGIN field_row -->
<tr> 
	<td class="row1">{fields.catrow.fieldrow.NAME}</td>
	<td class="row2">{fields.catrow.fieldrow.INPUT}</td>
</tr>
<!-- END field_row -->
<!-- END cat_row -->

<!-- BEGIN cat -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{fields.cat.CAT_NAME}</a></li></ul></th>
</tr>
<!-- BEGIN field -->
<tr> 
	<td class="row1{fields.cat.field.REQ}">{fields.cat.field.NAME}:</td>
	<td class="row2">{fields.cat.field.INPUT}</td>
</tr>
<!-- END field -->
<!-- END cat -->
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
<!-- END fields -->

<!-- BEGIN groups -->
<script language="javascript" type="text/javascript">
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

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_GROUPS}</a></li></ul>

<table class="update2">
<!-- BEGIN group_row -->
<tr>
	<td class="row1"><label for="{groups.group_row.GROUP_FIELD}" title="{groups.group_row.GROUP_INFO}">{groups.group_row.GROUP_NAME}:</label></td>
	<td class="row2"><label><input type="radio" onClick="activate_checkbox('checkbox_group_{groups.group_row.S_MARK_ID}');" name="{groups.group_row.S_MARK_NAME}" id="{groups.group_row.GROUP_FIELD}" value="{groups.group_row.S_MARK_ID}" {groups.group_row.S_ASSIGNED_GROUP} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" onClick="disable_checkbox('checkbox_group_{groups.group_row.S_MARK_ID}');" name="{groups.group_row.S_MARK_NAME}" value="{groups.group_row.S_NEG_MARK_ID}" {groups.group_row.S_UNASSIGNED_GROUP} />&nbsp;{L_NO}</label><span style="padding:4px;"></span><label><input type="checkbox" id="checkbox_group_{groups.group_row.S_MARK_ID}" name="mod_group_{groups.group_row.S_MARK_ID}" {groups.group_row.S_MOD_GROUP} />&nbsp;{L_MOD}</label><span style="padding:4px;"></span>{groups.group_row.RIGHT} {groups.group_row.U_USER_PENDING}
	</td>
</tr>
<!-- END group_row -->
<tr>
	<td colspan="2"></td>
</tr>
<tr>
	<td class="row1"><label for="email_group">{L_MAIL}</label></td>
	<td class="row2"><label><input type="radio" name="email_group" id="email_group" value="1">&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="email_group" value="0" checked="checked">&nbsp;{L_NO}</label></td>
</tr>
</table>

<table class="update2">
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_TEAMS}</a></li></ul></th>
</tr>
<!-- BEGIN team_row -->
<tr>
	<td class="row1">{groups.team_row.U_TEAM_NAME}</td>
	<td class="row2"><label><input type="radio" onClick="activate_checkbox('checkbox_team_{groups.team_row.S_MARK_ID}');" name="{groups.team_row.S_MARK_NAME}" value="{groups.team_row.S_MARK_ID}" {groups.team_row.S_ASSIGNED_TEAM} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" onClick="disable_checkbox('checkbox_team_{groups.team_row.S_MARK_ID}');" name="{groups.team_row.S_MARK_NAME}" value="{groups.team_row.S_NEG_MARK_ID}" {groups.team_row.S_UNASSIGNED_TEAM} />&nbsp;{L_NO}</label><span style="padding:4px;"></span><label><input type="checkbox" id="checkbox_team_{groups.team_row.S_MARK_ID}" name="mod_team_{groups.team_row.S_MARK_ID}" {groups.team_row.S_MOD_TEAM} />&nbsp;{L_MOD}</label></td>
</tr>
<!-- END team_row -->
<tr>
	<td colspan="2"></td>
</tr>
<tr>
	<td class="row1"><label for="email_team">{L_MAIL}</label></td>
	<td class="row2"><label><input type="radio" name="email_team" id="email_team" value="1">&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="email_team" value="0" checked="checked">&nbsp;{L_NO}</label></td>
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
<!-- END groups -->

<!-- BEGIN auth -->
<script type="text/javascript">
// <![CDATA[
			
function activated()
{
	<!-- BEGIN list -->
	document.getElementById("{auth.list.NAME}").checked = true;
	document.getElementById("color_{auth.list.NAME}").style.color = '#2e8b57';
	<!-- END list -->
}

function deactivated()
{
	<!-- BEGIN list -->
	document.getElementById("de_{auth.list.NAME}").checked = true;
	document.getElementById("color_{auth.list.NAME}").style.color = '#ff6347';
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

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_AUTH}</a></li></ul>

<table class="update2">
<!-- BEGIN data -->
<tr>
	<td class="row1"><label for="{auth.data.NAME}">{auth.data.TITLE}</label></td>
	<td class="row2" {auth.data.STATUS} id="color_{auth.data.NAME}" nowrap="nowrap">{auth.data.S_SELECT} {auth.data.S_DEFAULT}</td>
</tr>
<!-- END data -->
<tr>
	<td class="row2 right"><a class="small" onclick="activated();">{L_SPECIAL}</a></td>
	<td class="row2"><a class="small" onclick="deactivated();">{L_DEFAULT}</a></td>
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
<!-- END auth -->

<!-- BEGIN display -->
<script type="text/JavaScript">

function lookup(user_name, user_new, user_level)
{
	if ( user_name.length == 0 ) { $('#suggestions').hide(); }
	else
	{
		$.post("./ajax/ajax_user.php", {user_name: ""+user_name+"", user_new: ""+user_new+"", user_level: ""+user_level+""}, function(data)
		{
			if ( data.length > 0 )
			{
				$('#suggestions').show();
				$('#autoSuggestionsList').html(data);
			}
		});
	}
}

function fill(thisValue)
{
	$('#user_name').val(thisValue);
	setTimeout("$('#suggestions').hide();", 200);
}

</script>
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
<p>{L_EXPLAIN}</p>

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{display.row.LEVEL}&nbsp;&bull;&nbsp;{display.row.REGISTER}</span>{display.row.USERNAME}</td>
	<td>{display.row.AUTH}{display.row.FIELD}{display.row.GROUP}{display.row.UPDATE}{display.row.DELETE}</td>		
</tr>
<!-- END row -->
</table>

<table class="footer">
<tr>
	<td>
		<input type="text" name="user_name" id="user_name" onkeyup="lookup(this.value, 1, 0);" onblur="fill();" autocomplete="off"> <input type="submit" class="button2" value="{L_UPDATE} / {L_CREATE}">
		<div class="suggestionsBox" id="suggestions" style="display:none;">
			<div class="suggestionList" id="autoSuggestionsList"></div>
		</div>
	</td>
	<td></td>
	<td></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->