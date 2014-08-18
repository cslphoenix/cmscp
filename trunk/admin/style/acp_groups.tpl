<li class="header">{L_HEAD}<span class="right">{L_OPTION}</span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
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

<fieldset>
	<legend>{L_USERS}</legend>
	<table class="users">
	<tr>
		<th>{L_MODERATOR}</th>
		<th>{L_MAIN}</th>
		<th>{L_JOIN}</th>
		<th>{L_REGISTER}</th>
		<th>&nbsp;</th>
	</tr>
	<!-- BEGIN moderators -->
	<tr onclick="checked({member.moderators.ID})" class="hover">
		<td>{member.moderators.NAME}</td>
		<td>{member.moderators.MAIN}</td>
		<td>{member.moderators.JOIN}</td>
		<td>{member.moderators.REG}</td>
		<td><input type="checkbox" name="members[]" value="{member.moderators.ID}" id="check_{member.moderators.ID}"></td>
	</tr>
	<!-- END moderators -->
	<!-- BEGIN no_moderators -->
	<tr>
		<td class="empty" colspan="3">{L_MODERATOR_NO}</td>
	</tr>
	<!-- END no_moderators -->
	</table>

	<br />

	<table class="users">
	<tr>
		<th>{L_MEMBER}</th>
		<th>{L_MAIN}</th>
		<th>{L_JOIN}</th>
		<th>{L_REGISTER}</th>
		<th>&nbsp;</th>
	</tr>
	<!-- BEGIN members -->
	<tr onclick="checked({member.members.ID})" class="hover">
		<td>{member.members.NAME}</td>
		<td>{member.members.MAIN}</td>
		<td>{member.members.JOIN}</td>
		<td>{member.members.REG}</td>
		<td><input type="checkbox" name="members[]" value="{member.members.ID}" id="check_{member.members.ID}"></td>
	</tr>
	<!-- END members -->
	<!-- BEGIN no_members -->
	<tr>
		<td class="empty" colspan="3">{L_MEMBER_NO}</td>
	</tr>
	<!-- END no_members -->
	</table>
	
	<br />

</fieldset>

	<!-- BEGIN options -->
	<table class="rfooter">
	<tr>
		<td colspan="2" align="right">{S_OPTIONS}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}" /></td>
	</tr>
	<tr>
		<td colspan="2" align="right" class="row5"><a href="#" onclick="marklist('list', 'member', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">{L_MARK_DEALL}</a></td>
	</tr>
	</table>
	<!-- END options -->
<!-- BEGIN pending -->
<br />

<table class="users">
<tr>
	<th>{L_PENDING}</th>
	<th>{L_JOIN}</th>
	<th>{L_REGISTER}</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN row_pending -->
<tr onclick="checked({member.pending.row_pending.ID})" class="hover">
	<td>{member.pending.row_pending.NAME}</td>
    <td>{member.pending.row_pending.JOIN}</td>
	<td>{member.pending.row_pending.REG}</td>
	<td><input type="checkbox" name="pending[]" value="{member.pending.row_pending.ID}" id="check_{member.pending.row_pending.ID}" checked="checked"></td>
</tr>
<!-- END row_pending -->
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
<fieldset>
	<legend>{L_ADD}</legend>
	<dl>			
		<dt><label for="status">{L_MOD}:</label></dt>
		<dd><label><input type="radio" name="status" value="1" id="status" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="status" value="0" checked="checked" />&nbsp;{L_NO}</label></dd>
	</dl>
	<dl>			
		<dt><label for="default">{L_MAIN}:</label></dt>
		<dd><label><input type="radio" name="default" value="1" id="default" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="default" value="0" checked="checked" />&nbsp;{L_NO}</label></dd>
	</dl>
	<dl>			
		<dt><label for="textarea">{L_USERNAME}:</label></dt>
		<dd><textarea class="textarea" name="textarea" id="textarea" style="width:95%" rows="5"></textarea></dd>
	</dl>
</fieldset>
	<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd></dd>
</dl>
</div>

<input type="hidden" name="mode" value="ucreate" />
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