<!-- BEGIN display -->
<form action="{S_GROUP_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_GROUP_HEAD}</a></li>
	<li><a href="{S_GROUP_CREATE}">{L_GROUP_CREATE}</a></li>
	<li><a id="settings" href="{S_GROUP_OVERVIEW}">{L_GROUP_OVERVIEW}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_GROUP_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead">{L_GROUP_NAME}</td>
	<td class="rowHead" nowrap="nowrap">{L_GROUP_MEMBERCOUNT}</td>
	<td class="rowHead" colspan="4" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN group_row -->
<tr>
	<td class="row_class1" align="left" width="95%">{display.group_row.NAME}</td>
	<td class="row_class1" align="center">{display.group_row.MEMBER_COUNT}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{display.group_row.MOVE_UP} {display.group_row.MOVE_DOWN}</td>
	<td class="row_class2" align="center"><a href="{display.group_row.U_MEMBER}">{L_MEMBER}</a></td>
	<td class="row_class2" align="center"><a href="{display.group_row.U_UPDATE}">{L_UPDATE}</a></td>
	<td class="row_class2" align="center"><a href="{display.group_row.U_DELETE}">{display.group_row.L_DELETE}</a></td>
</tr>
<!-- END group_row -->
<!-- BEGIN no_groups -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_GROUPS}</td>
</tr>
<!-- END no_groups -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="group_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_GROUP_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN groups_edit -->
<script type="text/javascript" src="./../admin/style/jscolor.js"></script>
<form action="{S_GROUP_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_GROUP_ACTION}" method="post">{L_GROUP_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_GROUP_NEW_EDIT}</a></li>
	<!-- BEGIN edit_group -->
	<li><a href="{S_MEMBER_ACTION}" method="post">{L_GROUP_MEMBER}</a></li>
	<!-- END edit_group -->
	<li><a id="settings" href="{S_GROUP_OVERVIEW}">{L_GROUP_OVERVIEW}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_GROUP_DATA}</a></li>
	<li><a href="#" id="right">{L_GROUP_AUTH}</a></li>
</ul>
</div>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1" width="23%"><label for="group_name">{L_GROUP_NAME}: *</label></td>
			<td class="row2"><input type="text" class="post" name="group_name" id="group_name" value="{GROUP_NAME}"></td>
		</tr>
		<!-- BEGIN add_group -->
		<tr>
			<td class="row1">{L_GROUP_MOD}: *</td>
			<td class="row2">{S_GROUP_MOD}</td>
		</tr>
		<!-- END add_group -->
		<tr>
			<td class="row1">{L_GROUP_ACCESS}:</td>
			<td class="row2">{S_GROUP_ACCESS}</td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_TYPE}:</td>
			<td class="row2">{S_GROUP_TYPE}</td>
		</tr>
		<tr>
			<td class="row1 top"><label for="group_desc">{L_GROUP_DESC}:</label></td>
			<td class="row3"><textarea class="textarea" name="group_desc" id="group_desc" rows="5" cols="40">{GROUP_DESC}</textarea></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_color">{L_GROUP_COLOR}:</label></td>
			<td class="row2"><input size="7" class="color post" type="text" name="group_color" id="group_color" value="{GROUP_COLOR}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_legend">{L_GROUP_LEGEND}:</label></td>
			<td class="row3"><input type="radio" name="group_legend" id="group_legend" value="1" {S_LEGEND_YES} /> {L_SHOW} <input type="radio" name="group_legend" value="0" {S_LEGEND_NO} /> {L_NOSHOW} </td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_RANK}:</td>
			<td class="row3">{S_GROUP_RANK}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		</table>
		
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_GROUP_IMAGE}</a></li>
		</ul>
		</div>
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1 top">{L_GROUP_IMAGE_CURRENT}:</td>
			<td class="row3 top"><img src="{GROUP_IMAGE}" alt="" />&nbsp;<input type="checkbox" name="group_image_delete">&nbsp;{L_IMAGE_DELETE}</td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_IMAGE_UPLOAD}:</td>
			<td class="row3"></td>
		</tr>
		
		</table>
	</td>
	<td valign="top">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN group_auth_data -->
		<tr>
			<td class="row1"><label for="{groups_edit.group_auth_data.S_AUTH_NAME}">{groups_edit.group_auth_data.CELL_TITLE}</label></td>
			<td class="row3">{groups_edit.group_auth_data.S_AUTH_LEVELS_SELECT}</td>
		</tr>
		<!-- END group_auth_data -->
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END groups_edit -->

<!-- BEGIN group_member -->
<form action="{S_GROUP_ACTION}" method="post" name="post" id="list">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_GROUP_ACTION}" method="post">{L_GROUP_HEAD}</a></li>
	<li><a href="{S_GROUP_EDIT}">{L_GROUP_EDIT}</a></li>
	<li id="active"><a href="#" id="current">{L_GROUP_MEMBER}</a></li>
	<li><a id="settings" href="{S_GROUP_OVERVIEW}">{L_GROUP_OVERVIEW}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_GROUP_EXPLAIN}</td>
</tr>
</table>

<br />

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MODERATOR}</a></li>
</ul>
</div>
<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" align="left" width="98%">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN mods_row -->
<tr>
	<td class="{group_member.mods_row.CLASS}" align="left" width="100%">{group_member.mods_row.USERNAME}</td>
	<td class="{group_member.mods_row.CLASS}" align="center" nowrap="nowrap">{group_member.mods_row.REGISTER}</td>
	<td class="{group_member.mods_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{group_member.mods_row.USER_ID}"></td>
</tr>
<!-- END mods_row -->
<!-- BEGIN switch_no_moderators -->
<tr>
	<td colspan="7" class="row_noentry" align="center"><span class="gen">{L_NO_MODERATORS}</span></td>
</tr>
<!-- END switch_no_moderators -->
</table>

<br />

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MEMBER}</a></li>
</ul>
</div>
<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" align="left" width="98%">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN nomods_row -->
<tr>
	<td class="{group_member.nomods_row.CLASS}" align="left" width="100%">{group_member.nomods_row.USERNAME}</td>
	<td class="{group_member.nomods_row.CLASS}" align="center" nowrap="nowrap">{group_member.nomods_row.REGISTER}</td>
	<td class="{group_member.nomods_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{group_member.nomods_row.USER_ID}"></td>
</tr>
<!-- END nomods_row -->
<!-- BEGIN switch_no_members -->
<tr>
	<td colspan="7" class="row_noentry" align="center"><span class="gen">{L_NO_MEMBERS}</span></td>
</tr>
<!-- END switch_no_members -->
</table>

<!-- BEGIN pending -->
<br />

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_PENDING_MEMBER}</a></li>
</ul>
</div>
<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" align="left" width="98%">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN pending_row -->
<tr>
	<td class="{group_member.pending.pending_row.CLASS}" align="left" width="100%">{group_member.pending.pending_row.USERNAME}</td>
	<td class="{group_member.pending.pending_row.CLASS}" align="center" nowrap="nowrap">{group_member.pending.pending_row.REGISTER}</td>
	<td class="{group_member.pending.pending_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="pending_members[]" value="{group_member.pending.pending_row.USER_ID}" checked="checked"></td>
</tr>
<!-- END pending_row -->
</table>
<!-- END pending -->

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td colspan="2" align="right">{S_ACTION_OPTIONS}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'member', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>

<form action="{S_GROUP_ACTION}" method="post" name="post" id="list">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_GROUP_ADD_MEMBER}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_GROUP_ADD_MEMBER_EX}</span></td>
</tr>
</table>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 top" width="50%"><textarea class="textarea" name="members" style="width:100%" rows="5"></textarea></td>
	<td class="row2 top">{S_ACTION_ADDUSERS}</td>
</tr>
<tr>
	<td class="row2" colspan="2"><input type="checkbox" name="mod" /> Moderatorstatus</td>
</tr>
<tr>
	<td class="row2" colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}"></td>
</tr>
</table>
{S_HIDDEN_FIELDS2}
</form>
<!-- END team_member -->

<!-- BEGIN groups_list -->
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_GROUP_ACTION}" method="post">{L_GROUP_HEAD}</a></li>
	<li><a href="#" id="right">{L_GROUP_OVERVIEW}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_GROUP_OVERVIEW_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN groups_data -->
	<td>
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{groups_list.groups_data.GROUP_NAME}</a></li>
		</ul>
		</div>
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN groups_auth -->
		<tr>
			{groups_list.groups_data.groups_auth.CELL_TITLE}
			<td class="row3">{groups_list.groups_data.groups_auth.S_AUTH_LEVELS_SELECT}</td>
		</tr>
		<!-- END groups_auth -->
		</table>
	</td>
	<!-- END groups_data -->
</tr>
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
<!-- END groups_list -->