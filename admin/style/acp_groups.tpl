<!-- BEGIN display -->
<form action="{S_GROUP_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_GROUP_TITLE}</a></li>
				<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_GROUP_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead">{L_GROUP_NAME}</td>
	<td class="rowHead" nowrap="nowrap">{L_GROUP_MEMBERCOUNT}</td>
	<td class="rowHead" colspan="4">{L_SETTINGS}</td>
</tr>
<!-- BEGIN group_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{display.group_row.NAME}</td>
	<td class="row_class1" align="center" width="1%">{display.group_row.MEMBER_COUNT}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.group_row.U_MEMBER}">{L_MEMBER}</a></td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.group_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="row_class2" align="center" nowrap="nowrap">{display.group_row.MOVE_UP} {display.group_row.MOVE_DOWN}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.group_row.U_DELETE}">{display.group_row.L_DELETE}</a></td>
</tr>
<!-- END group_row -->
<!-- BEGIN no_groups -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_GROUPS}</td>
</tr>
<!-- END no_groups -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="group_name" type="text" value=""></td>
	<td><input type="hidden" name="mode" value="group_add"><input class="button" type="submit" value="{L_GROUP_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN groups_edit -->
<script type="text/javascript" src="./../admin/style/jscolor.js"></script>
<form action="{S_GROUP_ACTION}" method="post">	
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_GROUP_ACTION}">{L_GROUP_HEAD}</a></li>
				<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
				<li id="active"><a href="#" id="current">{L_GROUP_NEW_EDIT}</a></li>
				<!-- BEGIN edit_group -->
				<li><a href="{S_MEMBER_ACTION}">{L_GROUP_MEMBER}</a></li>
				<!-- END edit_group -->
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>
<table class="edit" cellspacing="0">
<tr>
	<td valign="top">
		<table class="edit" cellspacing="1">
		<tr>
			<th colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{L_GROUP_DATA}</a></li>
					</ul>
				</div>
			</th>
		</tr>
		<tr>
			<td class="row1" width="25%">{L_GROUP_NAME}: *</td>
			<td class="row2" width="75%"><input class="post" type="text" name="group_name" value="{GROUP_NAME}" ></td>
		</tr>
		<!-- BEGIN add_group -->
		<tr>
			<td class="row1" width="25%">{L_GROUP_MOD}: *</td>
			<td class="row2" width="75%">{S_GROUP_MOD}</td>
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
			<td class="row1" valign="top">{L_GROUP_DESCRIPTION}:</td>
			<td class="row3"><textarea class="textarea" name="group_description" rows="5" cols="40">{GROUP_DESCRIPTION}</textarea></td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_COLOR}:</td>
			<td class="row2">
				<input size="7" class="color" type="text" name="group_color" value="{GROUP_COLOR}">
				
			</td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_LEGEND}:</td>
			<td class="row3"><input type="radio" name="group_legend" value="1" {S_CHECKED_LEGEND_YES} /> {L_SHOW} <input type="radio" name="group_legend" value="0" {S_CHECKED_LEGEND_NO} /> {L_NOSHOW} </td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_RANK}:</td>
			<td class="row3">{S_GROUP_RANK}</td>
		</tr>
		<tr>
			<td class="row3" colspan="2">&nbsp;</td>
		</tr>
		</table>
		
		<table class="edit" cellspacing="1">
		<tr>
			<th colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{L_GROUP_LOGO}</a></li>
					</ul>
				</div>
			</th>
		</tr>
		
		<tr>
					<td class="row1" width="160">{L_TEAM_LOGO_UP}:<br><span class="small">{L_LOGO_UP_EXPLAIN}</span></td>
					<td class="row3"><input class="post" type="file" name="team_logo"></td>
				</tr>
				<tr>
					<td colspan="2">{TEAM_LOGO}<br><input type="checkbox" name="logodel" /></td>
				</tr>
		
		</tr>
		
		</table>
	</td>
	<td valign="top">
		<table class="edit" cellspacing="1">
		<tr>
			<th colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{L_GROUP_AUTH}</a></li>
					</ul>
				</div>
			</th>
		</tr>
		<!-- BEGIN group_auth_data -->
		<tr>
			<td class="row1">{groups_edit.group_auth_data.CELL_TITLE}</td>
			<td class="row3">{groups_edit.group_auth_data.S_AUTH_LEVELS_SELECT}</td>
		</tr>
		<!-- END group_auth_data -->
		</table>
	</td>
<tr>
	<td align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END groups_edit -->

<!-- BEGIN group_member -->
<form action="{S_GROUP_ACTION}" method="post" id="list" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_GROUP_ACTION}">{L_GROUP_TITLE}</a></li>
				<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
				<li><a href="{S_GROUP_EDIT}">{L_GROUP_NEW_EDIT}</a></li>
				<li id="active"><a href="#" id="current">{L_GROUP_MEMBER}</a></li>
			</ul>
		</div>
	</th>

</tr>
<tr>
	<td>{L_GROUP_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" align="left">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<tr>
	<td class="rowHead" colspan="7">{L_MODERATOR}</td>
</tr>
<!-- BEGIN mods_row -->
<tr>
	<td class="{group_member.mods_row.CLASS}" align="left" width="100%">{group_member.mods_row.USERNAME}</td>
	<td class="{group_member.mods_row.CLASS}" align="center" nowrap="nowrap">{group_member.mods_row.REGISTER}</td>
	<td class="{group_member.mods_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{group_member.mods_row.USER_ID}" /></td>
</tr>
<!-- END mods_row -->
<!-- BEGIN switch_no_moderators -->
<tr>
	<td colspan="7" class="row_noentry" align="center"><span class="gen">{L_NO_MODERATORS}</span></td>
</tr>
<!-- END switch_no_moderators -->
<tr>
	<td class="rowHead" colspan="7">{L_MEMBER}</td>
</tr>
<!-- BEGIN nomods_row -->
<tr>
	<td class="{group_member.nomods_row.CLASS}" align="left" width="100%">{group_member.nomods_row.USERNAME}</td>
	<td class="{group_member.nomods_row.CLASS}" align="center" nowrap="nowrap">{group_member.nomods_row.REGISTER}</td>
	<td class="{group_member.nomods_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{group_member.nomods_row.USER_ID}" /></td>
</tr>
<!-- END nomods_row -->
<!-- BEGIN switch_no_members -->
<tr>
	<td colspan="7" class="row_noentry" align="center"><span class="gen">{L_NO_MEMBERS}</span></td>
</tr>
<!-- END switch_no_members -->
<!-- BEGIN pending -->
<tr>
	<td class="rowHead" colspan="7">{L_PENDING_MEMBER}</td>
</tr>
<!-- BEGIN pending_row -->
<tr>
	<td class="{group_member.pending.pending_row.CLASS}" align="left" width="100%">{group_member.pending.pending_row.USERNAME}</td>
	<td class="{group_member.pending.pending_row.CLASS}" align="center" nowrap="nowrap">{group_member.pending.pending_row.REGISTER}</td>
	<td class="{group_member.pending.pending_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="pending_members[]" value="{group_member.pending.pending_row.USER_ID}" checked="checked" /></td>
</tr>
<!-- END pending_row -->
<!-- END pending -->

<!-- BEGIN no_members_row -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_GROUPS}</td>
</tr>
<!-- END no_members_row -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td colspan="2" align="right">
		{S_ACTION_OPTIONS}
		<input type="submit" name="send" value="{L_SUBMIT}" class="button" />
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'member', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
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

<table class="edit" cellspacing="1">
<tr>
	<td class="row2" valign="top"><textarea class="textarea" name="members" style="width:100%" rows="5"></textarea></td>
	<td class="row2" align="left" valign="top">{S_ACTION_ADDUSERS}</td>
</tr>
<tr>
	<td colspan="2" class="row2"><input type="checkbox" name="mod" /> Moderatorstatus</td>
</tr>
<tr>
	<td colspan="2" class="row2"><input type="submit" name="send2" value="{L_SUBMIT}" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS2}
</form>
<!-- END team_member -->
<!--
<tr>
	<td class="row1">{groups_list.group_auth.CELL_TITLE}</td>
	<td class="row3">{groups_list.group_auth.S_AUTH_LEVELS_SELECT}</td>
</tr>
-->


<!-- BEGIN groups_list -->
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_GROUP_ACTION}">{L_GROUP_TITLE}</a></li>
				<li id="active"><a href="#" id="current">{L_OVERVIEW}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_OVERVIEW_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="edit" cellspacing="0">
<tr>
	<!-- BEGIN groups_data -->
	<td>
		<table class="edit" cellspacing="0">
		<tr>
			<th colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{groups_list.groups_data.GROUP_NAME}</a></li>
					</ul>
				</div>
			</th>
		</tr>
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

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
<!-- END groups_list -->