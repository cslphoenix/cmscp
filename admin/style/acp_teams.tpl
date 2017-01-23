<li class="header">{L_HEADER}<span class="right">{L_OPTION}</span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript">

function update_image(newimage)
{
	document.getElementById('image').src = (newimage) ? "{IPATH}" + encodeURI(newimage) : "./../images/spacer.gif";
}

</script>

{ERROR_BOX}

<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
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
{AJAX}
{ERROR_BOX}
<form action="{S_ACTION}" method="post" name="post" id="list">
<fieldset>
	<legend>{L_MEMBER}</legend>
	<table class="users">
	<tr>
		<th>{L_MEMBER}</th>
		<th>{L_RANK}</th>
		<th>{L_MAIN}</th>
		<th>{L_JOIN}</th>
		<th>{L_REGISTER}</th>
		<th>&nbsp;</th>
	</tr>
	<!-- BEGIN row -->
	<tr onclick="checked({member.row.ID})" class="hover">
        <td>{member.row.NAME}{member.row.MOD}</td>
		<td>{member.row.RANK}</td>
		<td>{member.row.MAIN}</td>
		<td>{member.row.JOIN}</td>
		<td>{member.row.REG}</td>
		<td><input type="checkbox" name="members[]" value="{member.row.ID}" id="check_{member.row.ID}"></td>
	</tr>
	<!-- END row -->
	<!-- BEGIN no_row -->
	<tr>
		<td class="empty" colspan="5" align="center">{L_NO_MEMBER}</td>
	</tr>
	<!-- END no_row -->
	</table>
</fieldset>

<table class="footer2">
<tr>
	<td rowspan="2" width="150%">{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
	<td>{S_OPTIONS}</td>
	<td><div id="close"></div><div id="ajax_content"></div></td>
	<td><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td colspan="3"><a href="#" onclick="marklist('list', 'members', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'members', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>

<form action="{S_ACTION}" method="post" name="post" id="list">
<fieldset>
	<legend>{L_MEMBERS_ADD}</legend>
	<table class="update" border="0" cellspacing="0" cellpadding="0">
	
	<tr>
		<td class="row1"><label for="status" title="{L_MEMBER_ADD_MOD}">{L_MEMBER_ADD_MOD}:</label></td>
		<td class="row2"><label><input type="radio" name="status" id="status" value="1" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="status" value="0" checked="checked" />&nbsp;{L_NO}</label></td>
	</tr>
	<tr>
		<td class="row1"><label for="rank_id">{L_MEMBER_ADD_RANK}:</label></td>
		<td class="row2">{S_RANK_SELECT}</td>
	</tr>
	<tr>
		<td class="row1"><label for="textarea">{L_USERNAME}:</label></td>
		<td class="row2">{L_MEMBERS_ADD_EXPLAIN}<br/><br/><textarea class="textarea" name="textarea" id="textarea" style="width:95%" rows="5"></textarea></td>
	</tr>
	</table>
    </fieldset>
    
	<table class="rfooter">
	<tr>
    	<td align="right"><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
	</tr>
	</table>
    {S_FIELDS}
    <input type="hidden" name="smode" value="create" />
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
	<td><span class="right">{display.row.COUNT}</span>{display.row.GAME}{display.row.NAME}</td>
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
	<td><input type="text" name="team_name"></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->