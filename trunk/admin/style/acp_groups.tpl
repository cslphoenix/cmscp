<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a id="setting" href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%">{L_NAME}</td>
	<td class="rowHead">{L_MEMBERCOUNT}</td>
	<td class="rowHead" colspan="4" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _row_groups -->
<tr>
	<td class="row_class1" align="left">{_display._row_groups.NAME}</td>
	<td class="row_class1" align="center" width="1%">{_display._row_groups.COUNT}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._row_groups.MOVE_UP} {_display._row_groups.MOVE_DOWN} <a href="{_display._row_groups.U_MEMBER}">{I_MEMBER}</a> <a href="{_display._row_groups.U_UPDATE}">{I_UPDATE}</a> {_display._row_groups.DELETE}</td>
</tr>
<!-- END _row_groups -->
<!-- BEGIN _no_groups -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_GROUPS}</td>
</tr>
<!-- END _no_groups -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="group_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
<script type="text/javascript" src="./../admin/style/jscolor.js"></script>

<script type="text/javascript">
// <![CDATA[
	
	function activated()
	{
		<!-- BEGIN _auth -->
		document.getElementById("{_auth.FIELDS}").checked = true;
		<!-- END _auth -->
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
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	<!-- BEGIN _input -->
	<li><a href="{S_MEMBER}">{L_MEMBER}</a></li>
	<!-- END _input -->
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA}</a></li>
				<li><a href="#" id="right">{L_AUTH}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td valign="top">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1" width="155"><label for="group_name">{L_NAME}: *</label></td>
			<td class="row2"><input type="text" class="post" name="group_name" id="group_name" value="{NAME}"></td>
		</tr>
		<!-- BEGIN _create -->
		<tr>
			<td class="row1"><label for="user_id">{L_MOD}: *</label></td>
			<td class="row2">{S_MOD}</td>
		</tr>
		<!-- END _create -->
		<tr>
			<td class="row1"><label for="group_access">{L_ACCESS}:</label></td>
			<td class="row2">{S_ACCESS}</td>
		</tr>
		<tr>
			<td class="row1"><label for="group_type">{L_TYPE}:</label></td>
			<td class="row2">{S_TYPE}</td>
		</tr>
		<tr>
			<td class="row1 top"><label for="group_desc">{L_DESC}:</label></td>
			<td class="row2"><textarea class="textarea" name="group_desc" id="group_desc" rows="5" cols="40">{GROUP_DESC}</textarea></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_color">{L_COLOR}:</label></td>
			<td class="row2"><input size="7" class="color post" type="text" name="group_color" id="group_color" value="{COLOR}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_legend">{L_LEGEND}:</label></td>
			<td class="row2"><label><input type="radio" name="group_legend" id="group_legend" value="1" {S_LEGEND_YES} />&nbsp;{L_SHOW}</label><span style="padding:4px;"></span><label><input type="radio" name="group_legend" value="0" {S_LEGEND_NO} />&nbsp;{L_NOSHOW}</label></td>
		</tr>
		<tr>
			<td class="row1"><label for="rank_id">{L_RANK}:</label></td>
			<td class="row2">{S_RANK}</td>
		</tr>
		<tr>
			<td class="row1"><label for="rank_id">{L_ORDER}:</label></td>
			<td class="row2">{S_ORDER}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<th colspan="2">
				<div id="navcontainer">
				<ul id="navlist">
					<li id="active"><a href="#" id="current">{L_IMAGE}</a></li>
				</ul>
				</div>
			</th>
		</tr>
		<!-- BEGIN _image -->
		<tr>
			<td class="row1 top">{L_IMAGE_CURRENT}:</td>
			<td class="row2"><img src="{IMAGE}" alt="" /><br /><input type="checkbox" name="group_image_delete">&nbsp;{L_IMAGE_DELETE}</td>
		</tr>
		<!-- END _image -->
		<tr>
			<td class="row1"><label for="ufile">{L_IMAGE_UPLOAD}:</label></td>
			<td class="row2"><input type="file" class="post" name="group_img" /></td>
		</tr>		
		</table>
	</td>
	<td valign="top" align="right">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN _auth -->
		<tr>
			<td class="row1"><label for="{_input._auth.FIELDS}">{_input._auth.TITLE}</label></td>
			<td class="row2">{_input._auth.SELECT}</td>
		</tr>
		<!-- END _auth -->
		<tr>
			<td class="row3" colspan="2" align="right"><a class="small" href="#" onclick="activated();">{L_MARK_YES}</a>&nbsp;&bull;&nbsp;<a class="small" href="#" onclick="deactivated();">{L_MARK_NO}</a></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->

<!-- BEGIN _member -->
<form action="{S_ACTION}" method="post" name="post" id="list">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_EDIT}">{L_INPUT}</a></li>
	<li id="active"><a href="#" id="current">{L_MEMBER}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
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
<!-- BEGIN _mods_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{_member._mods_row.USERNAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._mods_row.REGISTER}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{_member._mods_row.USER_ID}"></td>
</tr>
<!-- END _mods_row -->
<!-- BEGIN _no_moderators -->
<tr>
	<td class="row_noentry" colspan="3" align="center">{L_NO_MODERATORS}</td>
</tr>
<!-- END _no_moderators -->
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
<!-- BEGIN _nomods_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{_member._nomods_row.USERNAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._nomods_row.REGISTER}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{_member._nomods_row.USER_ID}"></td>
</tr>
<!-- END _nomods_row -->
<!-- BEGIN _no_members -->
<tr>
	<td class="row_noentry" colspan="3" align="center">{L_NO_MEMBERS}</td>
</tr>
<!-- END _no_members -->
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
	<td class="{_member.pending.pending_row.CLASS}" align="left" width="100%">{_member.pending.pending_row.USERNAME}</td>
	<td class="{_member.pending.pending_row.CLASS}" align="center" nowrap="nowrap">{_member.pending.pending_row.REGISTER}</td>
	<td class="{_member.pending.pending_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="pending_members[]" value="{_member.pending.pending_row.USER_ID}" checked="checked"></td>
</tr>
<!-- END pending_row -->
</table>
<!-- END pending -->

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td colspan="2" align="right">{S_ACTION_OPTIONS}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'member', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
<input type="hidden" name="rank_id" value="" />
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

<table class="update" border="0" cellspacing="0" cellpadding="0">
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
<input type="hidden" name="mode" value="_user_add" />
{S_FIELDS}
</form>
<!-- END team_member -->

<!-- BEGIN _overview -->
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

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN groups_data -->
	<td>
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{_overview.groups_data.NAME}</a></li>
		</ul>
		</div>
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN groups_auth -->
		<tr>
			{_overview.groups_data.groups_auth.TITLE}
			<td class="row2">{_overview.groups_data.groups_auth.SELECT}</td>
		</tr>
		<!-- END groups_auth -->
		</table>
	</td>
	<!-- END groups_data -->
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
<!-- END _overview -->