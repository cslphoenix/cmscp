<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>

<p>{L_EXPLAIN}<br /><br />{L_SWITCH}<br />{L_SORT}<br />{L_SECTION}</p>

<form action="{S_ACTION}" method="post">
<!-- BEGIN head -->
<form action="{S_ACTION}" method="post">
<ul id="navopts"><li>{S_MODE} <input type="submit" value="{L_GO}" /></li></ul>
</form>

<form action="{S_ACTION}" method="post" autocomplete="off">
<!-- END head -->

<!-- BEGIN input -->
<!--
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>

<table class="update">
<tr>
	<td class="row1r"><label for="user_name">{L_NAME}:</label></td>
	<td class="row2"><input class="post" type="text" name="user_name" id="user_name" value="{USERNAME}"></td>
</tr>
<!-- BEGIN update >
<tr>
	<td class="row1"><label>{L_REGISTER}:</label></td>
	<td class="row2">{REGISTER}</td>
</tr>
<tr>
	<td class="row1"><label>{L_LASTLOGIN}:</label></td>
	<td class="row2">{LASTLOGIN}</td>
</tr>
<!-- END update >
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
-->
<script type="text/javascript">

function display_options(value)
{
	if ( value == '0' )
	{
		dE('password_input', -1);
		dE('password_random', 1);
	}
	else
	{
		dE('password_input', 1);
		dE('password_random', -1);
	}
}
</script>
{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<!-- BEGIN tab -->
<fieldset>
	<legend>{input.row.tab.L_LANG}</legend>
<!-- BEGIN option -->
{input.row.tab.option.DIV_START}
<dl>			
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
{input.row.tab.option.DIV_END}
<!-- END option -->

<div id="password_input" style="display:{NONE_INPUT};">
<dl>
	<dt><label for="password_new">{L_PASSWORD}:</label></dt>
	<dd><input type="password" name="password_new" id="password_new" value="" autocomplete="off"></dd>
</dl>
<dl>
	<dt><label for="password_confirm">{L_PASSWORD_CONFIRM}:</label></dt>
	<dd><input type="password" name="password_confirm" id="password_confirm" value="" autocomplete="off"></dd>
</dl>
</div>

<div id="password_random" style="display:{NONE_PASSWORD};">
<dl>
	<dt><label>{L_PASSWORD}:</label></dt>
	<dd><label><input type="radio" name="password" value="0" /> {PASS_1}</label><br />
		<label><input type="radio" name="password" value="1" /> {PASS_2}</label><br />
		<label><input type="radio" name="password" value="2" /> {PASS_3}</label><br />
		<label><input type="radio" name="password" value="3" /> {PASS_4}</label><br />
		<label><input type="radio" name="password" value="4" /> {PASS_5}</label><br />
		<label><input type="radio" name="password" value="5" /> {PASS_6}</label>
	</dd>
</dl>
</div>
</fieldset>
<!-- END tab -->
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>

<!-- END input -->

<!-- BEGIN settings -->

<form action="{S_ACTION}" method="post">
{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{settings.row.hidden.HIDDEN}
<!-- END hidden -->
<!-- BEGIN tab -->
<fieldset>
	<legend>{settings.row.tab.L_LANG}</legend>
<!-- BEGIN option -->
{settings.row.tab.option.DIV_START}
<dl>			
	<dt class="{settings.row.tab.option.CSS}"><label for="{settings.row.tab.option.LABEL}"{settings.row.tab.option.EXPLAIN}>{settings.row.tab.option.L_NAME}:</label></dt>
	<dd>{settings.row.tab.option.OPTION}</dd>
</dl>
{settings.row.tab.option.DIV_END}
<!-- END option -->
</fieldset>
<!-- END tab -->
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>

<!-- BEGIN row ->
<table class="update">
<!-- BEGIN tab ->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option ->
<tr>
	<td class="row1{settings.row.tab.option.CSS}"><label for="{settings.row.tab.option.LABEL}" {settings.row.tab.option.EXPLAIN}>{settings.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{settings.row.tab.option.OPTION}</td>
</tr>
<!-- END option ->
<!-- END tab ->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
<!-- END row ->

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>

{S_FIELDS}
</form>
-->
<!-- END settings -->

<!-- BEGIN fields -->

<form action="{S_ACTION}" method="post">
{ERROR_BOX}

<!-- BEGIN hidden -->
{fields.hidden.HIDDEN}
<!-- END hidden -->
<!-- BEGIN tab -->
<fieldset>
	<legend>{fields.tab.L_LANG}</legend>
<!-- BEGIN option -->
<dl>			
	<dt class="{fields.tab.option.CSS}"><label for="{fields.tab.option.LABEL}"{fields.tab.option.EXPLAIN}>{fields.tab.option.L_NAME}:</label></dt>
	<dd>{fields.tab.option.OPTION}</dd>
</dl>
<!-- END option -->
</fieldset>
<!-- END tab -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>

<!--
<table class="update">
<tr>
	<td colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_FIELDS}</a></li></ul></td>
</tr>
<!-- BEGIN cat_row ->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{fields.catrow.CAT_NAME}</a></li></ul></th>
</tr>
<!-- BEGIN field_row ->
<tr> 
	<td class="row1">{fields.catrow.fieldrow.NAME}</td>
	<td class="row2">{fields.catrow.fieldrow.INPUT}</td>
</tr>
<!-- END field_row ->
<!-- END cat_row ->

<!-- BEGIN cat ->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{fields.cat.CAT_NAME}</a></li></ul></th>
</tr>
<!-- BEGIN field ->
<tr> 
	<td class="row1{fields.cat.field.REQ}">{fields.cat.field.NAME}:</td>
	<td class="row2">{fields.cat.field.INPUT}</td>
</tr>
<!-- END field ->
<!-- END cat ->
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
-->
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

<!-- BEGIN group -->
<div style="float: {S_FLOAT_BEGIN}; width: {S_WIDTH}%;">
<fieldset>
	<legend>{L_GROUPS}</legend>
	<!-- BEGIN row -->
	<dl class="test">
		<dt style="width: 45%;"><label for="{groups.group.row.FIELD}">{groups.group.row.NAME}:</label></dt>
		<dd style="margin-left: 45%;"><label><input type="radio" onclick="activate_checkbox('checkbox_group_{groups.group.row.S_MARK_ID}');" name="{groups.group.row.S_MARK_NAME}" id="{groups.group.row.FIELD}" value="{groups.group.row.S_MARK_ID}" {groups.group.row.S_ASSIGNED_GROUP} />&nbsp;{L_YES}</label><span style="padding:2px;"></span><label><input type="radio" onClick="disable_checkbox('checkbox_group_{groups.group.row.S_MARK_ID}');" name="{groups.group.row.S_MARK_NAME}" value="{groups.group.row.S_NEG_MARK_ID}" {groups.group.row.S_UNASSIGNED_GROUP} />&nbsp;{L_NO}</label><span style="padding:4px;"></span><label><input type="checkbox" id="checkbox_group_{groups.group.row.S_MARK_ID}" name="mod_group_{groups.group.row.S_MARK_ID}" {groups.group.row.S_MOD_GROUP} />&nbsp;{L_MOD}</label><span style="padding:4px;"></span>{groups.group.row.RIGHT} {groups.group.row.U_USER_PENDING}</dd>
	</dl>
	<!-- END row -->
	<br />
	<dl>
		<dt style="width: 45%;"><label for="email_group">{L_MAIL}</label></dt>
		<dd style="margin-left: 45%;"><label><input type="radio" name="email_group" id="email_group" value="1">&nbsp;{L_YES}</label><span style="padding:2px;"></span><label><input type="radio" name="email_group" value="0" checked="checked">&nbsp;{L_NO}</label></dd>
	</dl>
	
</fieldset>
</div>
<!-- END group -->
<!-- BEGIN team -->
<div style="float: {S_FLOAT_END}; width: {S_WIDTH}%;">
<fieldset>
	<legend>{L_TEAMS}</legend>
	<!-- BEGIN row -->
	<dl>			
		<dt style="width: 45%;"><label for="{groups.team.row.FIELD}">{groups.team.row.NAME}:</label></dt>
		<dd style="margin-left: 45%;"><label><input type="radio" onclick="activate_checkbox('checkbox_team_{groups.team.row.S_MARK_ID}');" name="{groups.team.row.S_MARK_NAME}" id="{groups.team.row.FIELD}" value="{groups.team.row.S_MARK_ID}" {groups.team.row.S_ASSIGNED_TEAM} />&nbsp;{L_YES}</label><span style="padding:2px;"></span><label><input type="radio" onClick="disable_checkbox('checkbox_team_{groups.team.row.S_MARK_ID}');" name="{groups.team.row.S_MARK_NAME}" value="{groups.team.row.S_NEG_MARK_ID}" {groups.team.row.S_UNASSIGNED_TEAM} />&nbsp;{L_NO}</label><span style="padding:4px;"></span><label><input type="checkbox" id="checkbox_team_{groups.team.row.S_MARK_ID}" name="mod_team_{groups.team.row.S_MARK_ID}" {groups.team.row.S_MOD_TEAM} />&nbsp;{L_MOD}</label></dd>
	</dl>
	<!-- END row -->
	<br />
	<dl>			
		<dt style="width: 45%;"><label for="email_group">{L_MAIL}</label></dt>
		<dd style="margin-left: 45%;"><label><input type="radio" name="email_team" id="email_team" value="1">&nbsp;{L_YES}</label><span style="padding:2px;"></span><label><input type="radio" name="email_team" value="0" checked="checked">&nbsp;{L_NO}</label></dd>
	</dl>
</fieldset>
</div>
<!-- END team -->
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

<!-- BEGIN view -->
<form action="{S_ACTION}" method="post">
{S_OPTIONS}

<!-- BEGIN row -->
<fieldset>
	<legend id="legend">{view.row.NAME}</legend>
<!-- BEGIN parent -->
<fieldset class="views" id="view">
	<legend id="legend">{view.row.parent.NAME}</legend>
	<div class="views-switch">
		{GROUPS}
		<a href="#" onClick="toggle('{view.row.parent.AUTHS}'); return false;">{L_PERMISSION}</a>
	</div>
	<div id="{view.row.parent.AUTHS}" align="center">
	    <br />
		<div class="tabs">
			<ul>
				<!-- BEGIN cats -->
				<li><a {view.row.parent.cats.AUTH} href="#{view.row.parent.cats.CAT}">{view.row.parent.cats.NAME}</a></li>
				<!-- END cats -->
			</ul>
			<!-- BEGIN cats -->
			<div name="#{view.row.parent.cats.CAT}" id="{view.row.parent.cats.OPTIONS}">
				<table class="ttabs" cellpadding="1" cellspacing="1">
				<tr>
					<th>{L_VIEW_AUTH}</th>
					<th>{L_YES}</th>
					<th>{L_NO}</th>
				</tr>
				<!-- BEGIN auths -->
				<tr>
					<td><span class="right">{view.row.parent.cats.auths.OPT_INFO}</span>{view.row.parent.cats.auths.OPT_NAME}</td>
					<td class="{view.row.parent.cats.auths.CSS_YES}">&nbsp;</td>
					<td class="{view.row.parent.cats.auths.CSS_NO}">&nbsp;</td>
					
				</tr>
				<!-- END auths -->
				</table>
			</div>
			<!-- END cats -->
		</div>
	</div>
</fieldset>
<!-- END parent -->
</fieldset>
<!-- END row -->
{S_FIELDS}
</form>
<!-- END view -->

<!-- BEGIN log -->
<fieldset>
	<legend>{L_ACP_LOG}</legend>
	<p>{L_EXPLAIN3}</p>
	<div>
		<!-- BEGIN row -->
		<dl>
			<dt>{log.row.DATE} :: {log.row.IP}</dt>
			<dd><strong>{log.row.SEKTION}&nbsp;&raquo;&nbsp;{log.row.MESSAGE}</strong>&nbsp;{log.row.DATA}</dd>
		</dl>
		<!-- END row -->
	</div>
</fieldset>
<table class="footer">
<tr>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
<br />
<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END log -->

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
<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{display.row.REGISTER}</span>{display.row.USERNAME}</td>
	<td>{display.row.AUTH}{display.row.FIELD}{display.row.GROUP}{display.row.UPDATE}{display.row.DELETE}</td>		
</tr>
<!-- END row -->
</table>

<table class="footer">
<tr>
	<td>
		<input type="text" name="user_name" id="user_name" onkeyup="lookup(this.value, 1, 0);" onblur="fill();" autocomplete="off">
		<div class="suggestionsBox" id="suggestions" style="display:none;">
			<div class="suggestionList" id="autoSuggestionsList"></div>
		</div>
	</td>
	<td><input type="submit" value="{L_UPDATE} / {L_CREATE}"></td>
	<td></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->	