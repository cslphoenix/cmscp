<li class="header">{L_HEAD}<span class="right">{L_OPTION}</span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<!--
<script type="text/JavaScript">
<!-- BEGIN ajax ->
function look_{input.ajax.NAME}({input.ajax.NAME}, user_new, user_level)
{
	if ( {input.ajax.NAME}.length == 0 )
	{
		$('#{input.ajax.NAME}').hide();
	}
	else
	{
		$.post("./ajax/{input.ajax.FILE}", {{input.ajax.NAME}: ""+{input.ajax.NAME}+"", user_new: ""+user_new+"", user_level: ""+user_level+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#{input.ajax.NAME}').show();
					$('#auto_{input.ajax.NAME}').html(data);
				}
			}
		);
	}
}
function set_{input.ajax.NAME}(thisValue)
{
	$('#group_{input.ajax.NAME}').val(thisValue);
	setTimeout("$('#{input.ajax.NAME}').hide();", 200);
}
<!-- END ajax ->
function set_infos(id,text)
{
	var obj = document.getElementById(id).value = text;
}
</script>

<script type="text/JavaScript">
// <![CDATA[
function check_yes()
{
	<!-- BEGIN auth ->
	document.getElementById("{input.auth.FIELDS}").checked = true;
	<!-- END auth ->
}

function check_no()
{
	var radios = document.forms["post"].elements["deactivated"];
	
	for ( var i = 0; i < radios.length; i++ )
	{
		radios[i].checked = true;
	}
}

// ]]>
</script>
-->
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
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
	<dt class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
{input.row.tab.option.DIV_END}
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
<!-- END input -->

<!-- BEGIN member -->
<form action="{S_ACTION}" method="post" name="post" id="list">
{ERROR_BOX}

<table class="normal db">
<tr>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MODERATOR}</a></li></ul></th>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="right" onclick="return false;">{L_REGISTER}</a></li></ul></th>
</tr>
<!-- BEGIN mod -->
<tr>
	<td><label for="u{member.mod.USER_ID}">{member.mod.USERNAME}</label></td>
	<td>{member.mod.REGISTER}</td>
	<td><input type="checkbox" name="members[]" value="{member.mod.USER_ID}" id="u{member.mod.USER_ID}" /></td>
</tr>
<!-- END mod -->
<!-- BEGIN mod_no -->
<tr>
	<td class="empty" colspan="3">{L_MODERATOR_NO}</td>
</tr>
<!-- END mod_no -->
</table>

<br />

<table class="normal db">
<tr>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MEMBER}</a></li></ul></th>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="right" onclick="return false;">{L_REGISTER}</a></li></ul></th>
</tr>
<!-- BEGIN mem -->
<tr>
	<td><label for="u{member.mem.USER_ID}">{member.mem.USERNAME}</label></td>
	<td>{member.mem.REGISTER}</td>
	<td><input type="checkbox" name="members[]" value="{member.mem.USER_ID}" id="u{member.mem.USER_ID}" /></td>
</tr>
<!-- END mem -->
<!-- BEGIN mem_no -->
<tr>
	<td class="empty" colspan="3">{L_MEMBER_NO}</td>
</tr>
<!-- END mem_no -->
</table>

<table class="rfooter">
<tr>
	<td colspan="2" align="right">{S_OPTIONS}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td colspan="2" align="right" class="row5"><a href="#" onclick="marklist('list', 'member', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
<!-- BEGIN pending -->
<br />

<table class="normal db">
<tr>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_PENDING}</a></li></ul></th>
	<th colspan="2" align="right"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_REGISTER}</a></li></ul></th>
</tr>
<!-- BEGIN pending_row -->
<tr>
	<td>{member.pending.pen.USERNAME}</td>
	<td>{member.pending.pen.REGISTER}</td>
	<td><input type="checkbox" name="pendingmembers[]" value="{member.pending.pen.USER_ID}" checked="checked"></td>
</tr>
<!-- END pending_row -->
</table>

<table class="rfooter">
<tr>
	<td colspan="2" align="right">{S_PENDING}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
</table>
<!-- END pending -->
{S_FIELDS}
</form>

<form action="{S_ACTION}" method="post" name="post">

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_ADD_MEMBER}</a></li></ul>
<ul id="navinfo"><li>{L_ADD_MEMBER_EX}</li></ul>

<table class="update">
<tr>
	<td width="50%"><textarea class="textarea" name="textarea" style="width:100%" rows="5"></textarea></td>
	<td>{S_USERS}</td>
</tr>
<tr>
	<td colspan="2"><input type="checkbox" name="mod" /> Moderatorstatus</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}"></td>
</tr>
</table>
<input type="hidden" name="smode" value="_add" />
{S_FIELDS}
</form>
<!-- END member -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th><span class="right">{L_COUNT}</span>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{display.row.COUNT}</span>{display.row.NAME}</td>
	<td>{display.row.MEMBER}{display.row.MOVE_DOWN}{display.row.MOVE_UP}{display.row.UPDATE}{display.row.DELETE}</td>
</tr>
<!-- END row -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="group_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->