<li class="header">{L_HEADER}<span class="right">{L_OPTION}</span></li>
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
	<tr>
		<th>{L_MEMBER}</th>
		<th>{L_MAIN}</th>
		<th>{L_JOIN}</th>
		<th>{L_REGISTER}</th>
		<th>&nbsp;</th>
	</tr>
	<!-- BEGIN row -->
	<tr onclick="checked({member.row.ID})" class="hover">
		<td>{member.row.NAME}{member.row.MOD}</td>
		<td>{member.row.MAIN}</td>
		<td>{member.row.JOIN}</td>
		<td>{member.row.REG}</td>
		<td><input type="checkbox" name="members[]" value="{member.row.ID}" id="check_{member.row.ID}"></td>
	</tr>
	<!-- END row -->
	<!-- BEGIN no_row -->
	<tr>
		<td class="empty" colspan="3">{L_NO_MEMBER}</td>
	</tr>
	<!-- END no_row -->
	</table>
</fieldset>

	<table class="footer2">
    <tr>
        <td rowspan="2" width="150%">{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
        <td>{S_OPTIONS}</td>
        <td><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
    </tr>
    <tr>
        <td colspan="2"><a href="#" onclick="marklist('list', 'members', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'members', false); return false;">{L_MARK_DEALL}</a></td>
    </tr>
    </table>
	
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
		<dd><textarea class="textarea" name="textarea" id="textarea" style="width:95%" rows="5"></textarea></dd>
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
{S_OPTIONS}

<!-- BEGIN row -->
<fieldset>
	<legend id="legend">{permission.row.NAME}</legend>
<!-- BEGIN parent -->
<fieldset class="views" id="view">
	<legend id="legend">{permission.row.parent.NAME}</legend>
	<div class="views-switch">
		{GROUPS}
		<a href="#" onClick="toggle('{permission.row.parent.AUTHS}'); return false;">{L_PERMISSION}</a>
	</div>
	<div id="{permission.row.parent.AUTHS}" align="center">
	    <br />
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
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
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