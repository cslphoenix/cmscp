<!-- BEGIN _display -->
<script type="text/JavaScript">

function lookup(user_name)
{
	if ( user_name.length == 0 )
	{
		// Hide the suggestion box.
		$('#suggestions').hide();
	}
	else
	{
		$.post("./ajax/ajax_user.php", {user_name: ""+user_name+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			}
		);
	}
}

function fill(thisValue)
{
	$('#user_name').val(thisValue);
	setTimeout("$('#suggestions').hide();", 200);
}

</script>
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
	<td class="row_class1" align="left"><span style="float:right;">{_display._user_row.LEVEL} {_display._user_row.REGISTER}</span>{_display._user_row.USERNAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._user_row.LINKS}</td>		
</tr>
<!-- END _user_row -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="left" width="100px"><input type="text" class="post" name="user_name" id="user_name" onkeyup="lookup(this.value);" onblur="fill();" autocomplete="off" style="width:165px;">
		<div class="suggestionsBox" id="suggestions" style="display:none;">
			<img src="style/images/upArrow.png" style="position: relative; top: -12px; left: 30px;" alt="upArrow" />
			<div class="suggestionList" id="autoSuggestionsList"></div>
		</div>
	</td>
	<td align="left" class="top"><input type="submit" class="button2" value="{L_UPDATE} / {L_CREATE}"></td>
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

<!-- BEGIN _head -->
<div id="navcontainer">
	<ul id="navlist">
		<li><a href="{S_ACTION}">{L_HEAD}</a></li>
		<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	</ul>
</div>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
<tr>
	<td class="row4 small" align="right"><form action="{S_ACTION}" method="post">{S_MODE}</form></td>
</tr>
</table>
<br /><div align="center">{ERROR_BOX}</div>

<form action="{S_ACTION}" method="post" autocomplete="off">
<!-- END _head -->

<!-- BEGIN _input -->
<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1" width="155"><label for="user_name">{L_NAME}: *</label></td>
	<td class="row2"><input class="post" type="text" name="user_name" id="user_name" value="{USERNAME}"></td>
</tr>
<!-- BEGIN _update -->
<tr>
	<td class="row1"><label>{L_REGISTER}:</label></td>
	<td class="row2">{REGISTER}</td>
</tr>
<tr>
	<td class="row1"><label>{L_LASTLOGIN}:</label></td>
	<td class="row2">{LASTLOGIN}</td>
</tr>
<!-- END _update -->
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
	<td class="row1"><label for="user_email">{L_EMAIL}:</label></td>
	<td class="row2"><input type="text" class="post" name="user_email" id="user_email" value="{USEREMAIL}"></td>
</tr>
<tr>
	<td class="row1"><label for="user_email_confirm">{L_CONFIRM}:</label></td>
	<td class="row2"><input type="text" class="post" name="user_email_confirm" id="user_email_confirm"></td>
</tr>
<tr>
	<td class="row1"><label>{L_PASSWORD}:</label></td>
	<td class="row2">
	<label><input type="radio" name="s_pass" value="1" onChange="document.getElementById('pass_random').style.display = ''; document.getElementById('pass_input').style.display = 'none';" {S_INPUT} />&nbsp;{L_PASSWORD_RANDOM}</label><br />
	<label><input type="radio" name="s_pass" value="0" onChange="document.getElementById('pass_random').style.display = 'none'; document.getElementById('pass_input').style.display = '';" {S_RANDOM} />&nbsp;{L_PASSWORD_INPUT}</label>
	</td>
</tbody>
</tr>
<tbody class="trhover" id="pass_input" style="display:{INPUT};">
<tr>
	<td class="row1"><label for="password_new">{L_PASSWORD}:</label></td>
	<td class="row2"><input class="post" type="password" name="password_new" id="password_new" value=""></td>
</tr>
<tr>
	<td class="row1"><label for="password_confirm">{L_PASSWORD_CONFIRM}:</label></td>
	<td class="row2"><input class="post" type="password" name="password_confirm" id="password_confirm" value=""></td>
</tr>
</tbody>
<tbody class="trhover" id="pass_random" style="display:{RANDOM};">
<tr>
	<td class="row1"><label>{L_PASSWORD}:</label></td>
	<td class="row2">
		<label><input type="radio" name="password" value="0" /> {PASS_1}</label><br />
		<label><input type="radio" name="password" value="1" /> {PASS_2}</label><br />
		<label><input type="radio" name="password" value="2" /> {PASS_3}</label><br />
		<label><input type="radio" name="password" value="3" /> {PASS_4}</label><br />
		<label><input type="radio" name="password" value="4" /> {PASS_5}</label><br />
		<label><input type="radio" name="password" value="5" /> {PASS_6}</label>
	</td>
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
<!-- END _input -->

<!-- BEGIN _fields -->
<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_FIELDS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<!-- BEGIN _cat_row -->
<tr>
	<th></th>
	<th>{_fields._cat_row.CAT_NAME}</th>
</tr>
<!-- BEGIN _field_row -->
<tr> 
	<td class="row1">{_fields._cat_row._field_row.NAME}</td>
	<td class="row2">{_fields._cat_row._field_row.INPUT}</td>
</tr>
<!-- END _field_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td></td>
	<td class="row4">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry -->
<!-- END _cat_row -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _fields -->

<!-- BEGIN _groups -->
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
<table class="update" border="0" cellspacing="0" cellpadding="0">
<th colspan="2">
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_GROUPS}</a></li>
		</ul>
	</div>
</th>
<!-- BEGIN _group_row -->
<tr>
	<td class="row1" nowrap="nowrap"><label for="{_groups._group_row.GROUP_FIELD}" title="{_groups._group_row.GROUP_INFO}">{_groups._group_row.GROUP_NAME}</label></td>
	<td class="row2">
		<label>
			<input type="radio" onClick="activate_checkbox('checkbox_group_{_groups._group_row.S_MARK_ID}');" name="{_groups._group_row.S_MARK_NAME}" id="{_groups._group_row.GROUP_FIELD}" value="{_groups._group_row.S_MARK_ID}" {_groups._group_row.S_ASSIGNED_GROUP} />&nbsp;{L_YES}
		</label>
		<span style="padding:4px;"></span>
		<label>
			<input type="radio" onClick="disable_checkbox('checkbox_group_{_groups._group_row.S_MARK_ID}');" name="{_groups._group_row.S_MARK_NAME}" value="{_groups._group_row.S_NEG_MARK_ID}" {_groups._group_row.S_UNASSIGNED_GROUP} />&nbsp;{L_NO}
		</label>
		<span style="padding:4px;"></span>
		<label>
			<input type="checkbox" id="checkbox_group_{_groups._group_row.S_MARK_ID}" name="mod_group_{_groups._group_row.S_MARK_ID}" {_groups._group_row.S_MOD_GROUP} />&nbsp;{L_MOD}
		</label>
		{_groups._group_row.U_USER_PENDING}
	</td>
</tr>
<!-- END _group_row -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td class="row1"><label for="email_group">{L_MAIL}</label></td>
	<td class="row2"><label><input type="radio" name="email_group" id="email_group" value="1">&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="email_group" value="0" checked="checked">&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
</table>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<th colspan="2">
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_TEAMS}</a></li>
		</ul>
	</div>
</th>
<!-- BEGIN _team_row -->
<tr>
	<td class="row1" nowrap="nowrap">{_groups._team_row.U_TEAM_NAME}</td>
	<td class="row2">
		<label>
			<input type="radio" onClick="activate_checkbox('checkbox_team_{_groups._team_row.S_MARK_ID}');" name="{_groups._team_row.S_MARK_NAME}" value="{_groups._team_row.S_MARK_ID}" {_groups._team_row.S_ASSIGNED_TEAM} />&nbsp;{L_YES}
		</label>
		<span style="padding:4px;"></span>
		<label>
			<input type="radio" onClick="disable_checkbox('checkbox_team_{_groups._team_row.S_MARK_ID}');" name="{_groups._team_row.S_MARK_NAME}" value="{_groups._team_row.S_NEG_MARK_ID}" {_groups._team_row.S_UNASSIGNED_TEAM} />&nbsp;{L_NO}
		</label>
		<span style="padding:4px;"></span>
		<label>
			<input type="checkbox" id="checkbox_team_{_groups._team_row.S_MARK_ID}" name="mod_team_{_groups._team_row.S_MARK_ID}" {_groups._team_row.S_MOD_TEAM} />&nbsp;{L_MOD}
		</label>
	</td>
</tr>
<!-- END _team_row -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td class="row1"><label for="email_team">{L_MAIL}</label></td>
	<td class="row2"><label><input type="radio" name="email_team" id="email_team" value="1">&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="email_team" value="0" checked="checked">&nbsp;{L_NO}</label></td>
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
<!-- END _groups -->

<!-- BEGIN _auth -->
<script type="text/javascript">
// <![CDATA[
			
function activated()
{
	<!-- BEGIN _list -->
	document.getElementById("{_auth._list.NAME}").checked = true;
	document.getElementById("color_{_auth._list.NAME}").style.color = '#2e8b57';
	<!-- END _list -->
}

function deactivated()
{
	<!-- BEGIN _list -->
	document.getElementById("de_{_auth._list.NAME}").checked = true;
	document.getElementById("color_{_auth._list.NAME}").style.color = '#ff6347';
	<!-- END _list -->
}

function set_color(name,farbe)
{
	name = name;
	farbe = farbe;
	document.getElementById(name).style.color = farbe;
}

// ]]>
</script>
<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_AUTH}</a></li>
			</ul>
		</div>
	</th>
</tr>
<!-- BEGIN _data -->
<tr>
	<td class="row1" width="155"><label for="{_auth._data.NAME}">{_auth._data.TITLE}</label></td>
	<td class="row2" {_auth._data.STATUS} id="color_{_auth._data.NAME}" nowrap="nowrap">{_auth._data.S_SELECT} {_auth._data.S_DEFAULT}</td>
</tr>
<!-- END _data -->
<tr>
	<td class="row5"></td>
	<td class="row4"><a class="small" onclick="activated();">{L_SPECIAL}</a>&nbsp;&bull;&nbsp;<a class="small" onclick="deactivated();">{L_DEFAULT}</a></td>
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
<!-- END _auth -->