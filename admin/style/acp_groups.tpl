<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>

<p>{L_EXPLAIN}<br /><br />{L_SWITCH}</p>

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
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
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
	<thead>
	<tr>
		<th>{L_MEMBER}</th>
		<th>{L_MAIN}</th>
		<th>{L_JOIN}</th>
		<th>{L_REGISTER}</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th colspan="5">{L_MODERATORS}</th>
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
	<!-- BEGIN moderators_none -->
	<tr>
		<td colspan="5" style="text-align:center;">{L_MODERATORS_NONE}</td>
	</tr>
	<!-- END moderators_none -->
	<tr>
		<th colspan="6">{L_MEMBERS}</th>
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
	<!-- BEGIN members_none -->
	<tr>
		<td colspan="5" style="text-align:center;">{L_MEMBERS_NONE}</td>
	</tr>
	<!-- END members_none -->
	<!-- BEGIN pendings -->
	<tr>
		<th colspan="6">{L_PENDING}</th>
	</tr>
	<!-- BEGIN row -->
	<tr onclick="checked({member.pendings.row.ID})" class="hover">
		<td>{member.pendings.row.NAME}</td>
		<td>{member.pendings.row.MAIN}</td>
		<td>{member.pendings.row.JOIN}</td>
		<td>{member.pendings.row.REG}</td>
		<td><input type="checkbox" name="pending[]" value="{member.pendings.row.ID}" id="check_{member.pendings.row.ID}" checked="checked"></td>
	</tr>
	<!-- END row -->
	<!-- END pending -->
	</tbody>
	</table>
</fieldset>

<div class="pagination">
<ul>
	<li>{PAGE_NUMBER}&nbsp;{PAGE_PAGING}</li>
</ul>
</div>

<table class="footer2">
<tr>
	<td rowspan="2" width="150%"></td>
	<td>{S_OPTIONS}</td>
	<td><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td colspan="2"><a href="#" onclick="marklist('list', 'members', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'members', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- BEGIN pending -->
<br />

<table class="users">
<tr>
	<th>{L_PENDING}</th>
	<th>{L_JOIN}</th>
	<th>{L_REGISTER}</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN row -->
<tr onclick="checked({member.pending.row.ID})" class="hover">
	<td>{member.pending.row.NAME}</td>
    <td>{member.pending.row.JOIN}</td>
	<td>{member.pending.row.REG}</td>
	<td><input type="checkbox" name="pending[]" value="{member.pending.row.ID}" id="check_{member.pending.row.ID}" checked="checked"></td>
</tr>
<!-- END row -->
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
		<dd><textarea class="textarea" name="textarea" id="textarea" style="width:95%" rows="5"></textarea><br/><br/>{L_ADD_EXPLAIN}</dd>
	</dl>
</fieldset>
	<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd></dd>
</dl>
</div>

<input type="hidden" name="smode" value="create" />
{S_FIELDS}
</form>
<!-- END member -->

<!-- BEGIN permission -->
<form action="{S_ACTION}" method="post">
<!-- BEGIN row -->
<fieldset>
	<legend id="legend">{permission.row.NAME}</legend>
<!-- BEGIN parent -->
<fieldset class="views" id="view">
	<legend id="legend">{permission.row.parent.NAME}</legend>
	<div>
		<div class="tabs">
			<ul>
				<!-- BEGIN cats -->
				<li><a {permission.row.parent.cats.AUTH} href="#{permission.row.parent.cats.CAT}">{permission.row.parent.cats.NAME}</a></li>
				<!-- END cats -->
			</ul>
			<!-- BEGIN cats -->
			<div name="#{permission.row.parent.cats.CAT}" id="{permission.row.parent.cats.OPTIONS}">
				<table class="ttabs" cellpadding="1" cellspacing="1">
				<tr>
					<th>{L_VIEW_AUTH}</th>
					<th>{L_YES}</th>
					<th>{L_NO}</th>
				</tr>
				<!-- BEGIN auths -->
				<tr>
					<td><span class="right">{permission.row.parent.cats.auths.OPT_INFO}</span>{permission.row.parent.cats.auths.OPT_NAME}</td>
					<td class="{permission.row.parent.cats.auths.CSS_YES}">&nbsp;</td>
					<td class="{permission.row.parent.cats.auths.CSS_NO}">&nbsp;</td>
					
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
<!-- END permission -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<thead>
<tr>
	<th>{L_NAME}</th>
	<th>{L_COUNT}</th>
	<th>{L_SETTINGS}</th>
</tr>
</thead>
<tbody>
<!-- BEGIN row -->
<tr>
	<td>{display.row.NAME}</td>
	<td>{display.row.COUNT}</td>
	<td>{display.row.MEMBER}&nbsp;{display.row.UPDATE}&nbsp;{display.row.MOVE_DOWN}{display.row.MOVE_UP}&nbsp;{display.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END none -->
</tbody>
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