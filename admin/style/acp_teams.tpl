<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>
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
	<thead>
	<tr>
		<th>{L_MODERATORS}</th>
		<th>{L_RANK}</th>
		<th>{L_MAIN}</th>
		<th>{L_JOIN}</th>
		<th>{L_REGISTER}</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<!-- BEGIN moderators -->
	<tr onclick="checked({member.moderators.ID})" class="hover">
        <td>{member.moderators.NAME}</td>
		<td>{member.moderators.RANK}</td>
		<td>{member.moderators.MAIN}</td>
		<td>{member.moderators.JOIN}</td>
		<td>{member.moderators.REG}</td>
		<td><input type="checkbox" name="members[]" value="{member.moderators.ID}" id="check_{member.moderators.ID}"></td>
	</tr>
	<!-- END moderators -->
	<!-- BEGIN moderators_none -->
	<tr>
		<td colspan="6" style="text-align:center;">{L_MODERATORS_NONE}</td>
	</tr>
	<!-- END moderators_none -->
	<tr>
		<th colspan="6">{L_MEMBERS}</th>
	</tr>
	<!-- BEGIN members -->
	<tr onclick="checked({member.members.ID})" class="hover">
        <td>{member.members.NAME}</td>
		<td>{member.members.RANK}</td>
		<td>{member.members.MAIN}</td>
		<td>{member.members.JOIN}</td>
		<td>{member.members.REG}</td>
		<td><input type="checkbox" name="members[]" value="{member.members.ID}" id="check_{member.members.ID}"></td>
	</tr>
	<!-- END members -->
	<!-- BEGIN members_none -->
	<tr>
		<td colspan="6" style="text-align:center;">{L_MEMBERS_NONE}</td>
	</tr>
	<!-- END members_none -->
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
	<td><div id="close"></div><div id="ajax_content"></div></td>
	<td><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td colspan="3"><a href="#" onclick="marklist('list', 'members', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'members', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
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
		<dt><label for="default">{L_RANK}:</label></dt>
		<dd>{S_RANKS}</dd>
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

<!-- BEGIN wars -->
<table class="rows">
<tr>
	<th>{L_MATCH_UPCOMING}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN mupcoming -->
<tr>
	<td><span class="right">{wars.mupcoming.DATE}</span>{wars.mupcoming.NAME}</td>
	<td>{wars.mupcoming.UPDATE}&nbsp;{wars.mupcoming.DELETE}</td>
</tr>
<!-- END mupcoming -->
<!-- BEGIN mupcoming_none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END mupcoming_none -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_MATCH_EXPIRED}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN mexpired -->
<tr>
	<td><span style="float: right;">{wars.mexpired.DATE}</span>{wars.mexpired.NAME}</td>
	<td>{wars.mexpired.UPDATE}&nbsp;{wars.mexpired.DELETE}</td>
</tr>
<!-- END mexpired -->
<!-- BEGIN mexpired_none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END mexpired_none -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_TRAINING_UPCOMING}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN tupcoming -->
<tr>
	<td><span class="right">{wars.tupcoming.DATE}</span>{wars.tupcoming.NAME}</td>
	<td>{wars.tupcoming.UPDATE}&nbsp;{wars.tupcoming.DELETE}</td>
</tr>
<!-- END tupcoming -->
<!-- BEGIN tupcoming_none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END tupcoming_none -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_TRAINING_EXPIRED}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN texpired -->
<tr>
	<td><span style="float: right;">{wars.texpired.DATE}</span>{wars.texpired.NAME}</td>
	<td>{wars.texpired.UPDATE}&nbsp;{wars.texpired.DELETE}</td>
</tr>
<!-- END texpired -->
<!-- BEGIN texpired_none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END texpired_none -->
</table>
<!-- END wars -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_COUNT}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{display.row.GAME}{display.row.NAME}</td>
	<td>{display.row.COUNT}</td>
	<td>{display.row.MEMBER}&nbsp;{display.row.UPDATE}&nbsp;{display.row.MOVE_DOWN}{display.row.MOVE_UP}&nbsp;{display.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END none -->
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