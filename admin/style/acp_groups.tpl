<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a id="setting" href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%"><span style="float:right;">{L_MEMBERCOUNT}</span>{L_NAME}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_group -->
<tr>
	<td class="row_class1" align="left"><span style="float:right;">{display.row_group.COUNT}&nbsp;</span>{display.row_group.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{display.row_group.MOVE_UP} {display.row_group.MOVE_DOWN} <a href="{display.row_group.U_MEMBER}">{I_MEMBER}</a> <a href="{display.row_group.U_UPDATE}">{I_UPDATE}</a> {display.row_group.DELETE}</td>
</tr>
<!-- END row_group -->
<!-- BEGIN no_groups -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_GROUPS}</td>
</tr>
<!-- END no_groups -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="group_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN groups_edit -->
<script type="text/javascript" src="./../admin/style/jscolor.js"></script>

<script type="text/javascript">
// <![CDATA[

function activated()
{
	<!-- BEGIN group_auth_data -->
	document.getElementById("{group_auth_data.NAME}").checked = true;
	<!-- END group_auth_data -->
}

function deactivated()
{
	var radios = document.forms["post"].elements["deactivated"];
	
	for ( var i = 0; i < radios.length; i++ )
	{
		radios[i].checked = true;
	}
}

// ]]>
</script>
<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
	<!-- BEGIN edit_group -->
	<li><a href="{S_MEMBER}">{L_MEMBER}</a></li>
	<!-- END edit_group -->
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_DATA}</a></li>
	<li><a href="#" id="right">{L_AUTH}</a></li>
</ul>
</div>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1" width="23%"><label for="group_name">{L_NAME}: *</label></td>
			<td class="row3"><input type="text" class="post" name="group_name" id="group_name" value="{NAME}"></td>
		</tr>
		<!-- BEGIN add_group -->
		<tr>
			<td class="row1"><label for="user_id">{L_MOD}: *</label></td>
			<td class="row3">{S_MOD}</td>
		</tr>
		<!-- END add_group -->
		<tr>
			<td class="row1"><label for="group_access">{L_ACCESS}:</label></td>
			<td class="row3">{S_ACCESS}</td>
		</tr>
		<tr>
			<td class="row1"><label for="group_type">{L_TYPE}:</label></td>
			<td class="row3">{S_TYPE}</td>
		</tr>
		<tr>
			<td class="row1 top"><label for="group_desc">{L_DESC}:</label></td>
			<td class="row3"><textarea class="textarea" name="group_desc" id="group_desc" rows="5" cols="40">{GROUP_DESC}</textarea></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_color">{L_COLOR}:</label></td>
			<td class="row3"><input size="7" class="color post" type="text" name="group_color" id="group_color" value="{COLOR}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_legend">{L_LEGEND}:</label></td>
			<td class="row3"><label><input type="radio" name="group_legend" id="group_legend" value="1" {S_LEGEND_YES} />&nbsp;{L_SHOW}</label>&nbsp;&nbsp;<label><input type="radio" name="group_legend" value="0" {S_LEGEND_NO} />&nbsp;{L_NOSHOW}</label></td>
		</tr>
		<tr>
			<td class="row1"><label for="rank_id">{L_RANK}:</label></td>
			<td class="row3">{S_RANK}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
				<div id="navcontainer">
				<ul id="navlist">
					<li id="active"><a href="#" id="current">{L_IMAGE}</a></li>
				</ul>
				</div>
			</td>
		</tr>
		<!-- BEGIN image -->
		<tr>
			<td class="row1 top">{L_IMAGE_CURRENT}:</td>
			<td class="row3"><img src="{IMAGE}" alt="" /><br /><input type="checkbox" name="group_image_delete">&nbsp;{L_IMAGE_DELETE}</td>
		</tr>
		<!-- END image -->
		<tr>
			<td class="row1"><label for="ufile">{L_IMAGE_UPLOAD}:</label></td>
			<td class="row3"><input type="file" class="post" name="group_image" /></td>
		</tr>		
		</table>
	</td>
	<td valign="top" align="right">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN group_auth_data -->
		<tr>
			<td class="row1"><label for="{groups_edit.group_auth_data.NAME}">{groups_edit.group_auth_data.TITLE}</label></td>
			<td class="row3">{groups_edit.group_auth_data.S_SELECT}</td>
		</tr>
		<!-- END group_auth_data -->
		<tr>
			<td class="row2" colspan="2" align="right"><a class="small" href="#" onclick="activated();">&raquo;&nbsp;{L_MARK_YES}</a>&nbsp;&nbsp;<a class="small" href="#" onclick="deactivated();">&raquo;&nbsp;{L_MARK_NO}</a></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END groups_edit -->

<!-- BEGIN group_member -->
<form action="{S_ACTION}" method="post" name="post" id="list">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_EDIT}">{L_EDIT}</a></li>
	<li id="active"><a href="#" id="current">{L_MEMBER}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
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
	<td class="rowHead" align="left" width="100%">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN mods_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{group_member.mods_row.USERNAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{group_member.mods_row.REGISTER}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{group_member.mods_row.USER_ID}"></td>
</tr>
<!-- END mods_row -->
<!-- BEGIN switch_no_moderators -->
<tr>
	<td class="row_noentry" colspan="3" align="center">{L_NO_MODERATORS}</td>
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
	<td class="rowHead" align="left" width="100%">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN nomods_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{group_member.nomods_row.USERNAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{group_member.nomods_row.REGISTER}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{group_member.nomods_row.USER_ID}"></td>
</tr>
<!-- END nomods_row -->
<!-- BEGIN switch_no_members -->
<tr>
	<td class="row_noentry" colspan="3" align="center">{L_NO_MEMBERS}</td>
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

<form action="{S_ACTION}" method="post" name="post" id="list">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_ADD_MEMBER}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_ADD_MEMBER_EX}</span></td>
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
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="#" id="right">{L_OVERVIEW}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_OVERVIEW_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN groups_data -->
	<td>
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{groups_list.groups_data.NAME}</a></li>
		</ul>
		</div>
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN groups_auth -->
		<tr>
			{groups_list.groups_data.groups_auth.TITLE}
			<td class="row3">{groups_list.groups_data.groups_auth.SELECT}</td>
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