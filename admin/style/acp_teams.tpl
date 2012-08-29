<!-- BEGIN input -->
<script type="text/javascript">
// <![CDATA[

function update_image(newimage)
{
	document.getElementById('image').src = (newimage) ? "{IPATH}" + encodeURI(newimage) : "./../images/spacer.gif";
}

// ]]>
</script>
{TINYMCE}
<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
<!-- BEGIN member -->
<li><a href="{S_MEMBER}">{L_MEMBER}</a></li>
<!-- END member -->
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<div class="update">
<!-- BEGIN tab -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul>
<!-- BEGIN option -->
<div{input.row.tab.option.ID}>
<dl>			
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
</div>
<!-- END option -->
<!-- END tab -->
</div>
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
<form action="{S_ACTION}" method="post" id="list" name="list">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_INPUT}">{L_INPUT}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_MEMBER}</a></li>
</ul>
<ul id="navinfo"><li>{L_MEMBERS_EXPLAIN}</li></ul>

{ERROR_BOX}

<table class="normal">
<tr>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MODERATORS}</a></li></ul></th>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_REGISTER}</a></li></ul></th>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_JOIN}</a></li></ul></th>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_RANK}</a></li></ul></th>
</tr>
<!-- BEGIN mod -->
<tr onclick="checked({member.mod.ID})" class="hover">
	<td>{member.mod.NAME}</td>
	<td>{member.mod.REG}</td>
	<td>{member.mod.JOIN}</td>
	<td>{member.mod.RANK}</td>
	<td><input type="checkbox" name="member[]" value="{member.mod.ID}" id="check_{member.mod.ID}"></td>
</tr>
<!-- END mod -->
<!-- BEGIN no_moderators -->
<tr>
	<td class="empty" colspan="5" align="center">{L_NO_MODERATORS}</td>
</tr>
<!-- END no_moderators -->
</table>

<br />

<table class="normal">
<tr>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MEMBERS}</a></li></ul></th>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_REGISTER}</a></li></ul></th>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_JOIN}</a></li></ul></th>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_RANK}</a></li></ul></th>
</tr>
<!-- BEGIN mem -->
<!--<tr class="hover">-->
<tr>
	<td><label for="check_{member.mem.ID}">{member.mem.NAME}</label></td>
	<td><label for="check_{member.mem.ID}">{member.mem.REG}</label></td>
	<td><label for="check_{member.mem.ID}">{member.mem.JOIN}</label></td>
	<td><label for="check_{member.mem.ID}">{member.mem.RANK}</label></td>
	<td><input type="checkbox" name="member[]" value="{member.mem.ID}" id="check_{member.mem.ID}"></td>
</tr>
<!-- END mem -->
<!-- BEGIN no_members -->
<tr>
	<td class="empty" colspan="5" align="center">{L_NO_MODERATORS}</td>
</tr>
<!-- END no_members -->
</table>

<table class="rfooter">
<tr>
	<td>{S_OPTIONS}</td>
	<td><div id="ajax_content"></div></td>
	<td><input type="submit" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td align="right" colspan="3"><a href="#" onclick="marklist('list', 'member', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>

<!-- BEGIN add -->
<form action="{S_ACTION}" method="post" name="post" id="list">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MEMBERS_ADD}</a></li></ul>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_MEMBERS_ADD_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1"><label for="moderator" title="{L_MEMBER_ADD_MOD}">{L_MEMBER_ADD_MOD}:</label></td>
	<td class="row2"><label><input type="radio" name="moderator" id="moderator" value="1" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="moderator" value="0" checked="checked" />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="rank_id">{L_MEMBER_ADD_RANK}:</label></td>
	<td>{S_RANK_SELECT}</td>
</tr>
<tr>
	<td class="row1"><label for="members">{L_USERNAME}:</label></td>
	<td class="row2"><textarea class="textarea" name="members" style="width:50%" rows="5"></textarea><br />{S_USERS}</td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
</table>
{S_FIELDS}
<input type="hidden" name="smode" value="user_create" />
</form>
<!-- END add -->
<!-- END member -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
<p>{L_EXPLAIN}</p>

<br />

<table class="rows">
<tr>
	<th><span class="right">{L_COUNT}</span>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{display.row.COUNT}</span>{display.row.GAME}{display.row.NAME}</td>
	<td>{display.row.MEMBER}{display.row.MOVE_UP}{display.row.MOVE_DOWN}{display.row.UPDATE}{display.row.DELETE}</td>
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