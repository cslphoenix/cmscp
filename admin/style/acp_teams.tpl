<!-- BEGIN display -->
<form method="post" action="{S_TEAM_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_TEAM_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_TEAM_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_TEAM_NAME}</td>
	<td class="rowHead" nowrap="nowrap">{L_TEAM_MEMBERCOUNT}</td>
	<td class="rowHead" colspan="4">{L_TEAM_SETTINGS}</td>
</tr>
<!-- BEGIN teams_row -->
<tr>
	<td class="row_class1" align="center">{display.teams_row.TEAM_GAME}</td>
	<td class="row_class1" align="left" width="100%">{display.teams_row.TEAM_NAME}</td>
	<td class="row_class1" align="center" width="1%">{display.teams_row.TEAM_MEMBER_COUNT}</td>
	<td class="row_class2" align="center" width="1%"><a href="{display.teams_row.U_MEMBER}">{L_TEAM_MEMBER}</a></td>
	<td class="row_class2" align="center" width="1%"><a href="{display.teams_row.U_EDIT}">{L_TEAM_SETTING}</a></td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.teams_row.U_MOVE_UP}">{display.teams_row.ICON_UP}</a> <a href="{display.teams_row.U_MOVE_DOWN}">{display.teams_row.ICON_DOWN}</a></td>
	<td class="row_class2" align="center" width="1%"><a href="{display.teams_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END teams_row -->
<!-- BEGIN no_teams -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_TEAMS}</td>
</tr>
<!-- END no_teams -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="team_name" type="text" value=""></td>
	<td><input class="button" type="submit" name="add" value="{L_TEAM_CREATE}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN teams_edit -->

<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{GAME_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_TEAM_ACTION}" method="post" name="post" enctype="multipart/form-data">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_TEAM_ACTION}">{L_TEAM_TITLE}</a></li>
				<li id="active"><a href="#" id="current">{L_TEAM_NEW_EDIT}</a></li>
				<li><a href="{S_TEAM_MEMBER}">{L_TEAM_MEMBER}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<th colspan="2">{L_TEAM_INFOS}</th>
</tr>
<tr>
	<td class="row1" width="160">{L_TEAM_NAME}: *</td>
	<td class="row3"><input class="post" type="text" name="team_name" value="{TEAM_NAME}" ></td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3">{S_TEAM_GAME}&nbsp;<img src="{GAME_IMAGE}" id="image" alt="" width="{GAME_SIZE}" height="{GAME_SIZE}" /></td>
</tr>
<tr>
	<td class="row1" valign="top">{L_TEAM_DESCRIPTION}:</td>
	<td class="row3"><textarea class="textarea" name="team_description" rows="5" cols="40">{TEAM_DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2">
		<table class="edit" cellspacing="1">
		<tr>
			<td valign="top">
				<table class="edit" cellspacing="1">
				<tr>
					<th colspan="2">{L_MENU_SETTINGS}</th>
				</tr>
				<tr>
					<td class="row1" width="160">{L_TEAM_NAVI}:</td>
					<td class="row3"><input type="radio" name="team_navi" value="1" {CHECKED_NAVI_YES} /> {L_YES} <input type="radio" name="team_navi" value="0" {CHECKED_NAVI_NO} /> {L_NO} </td> 
				</tr>
				<tr>
					<td class="row1">{L_TEAM_SAWARDS}:</td>
					<td class="row3"><input type="radio" name="team_show_awards" value="1" {CHECKED_SAWARDS_YES} /> {L_YES} <input type="radio" name="team_show_awards" value="0" {CHECKED_SAWARDS_NO} /> {L_NO} </td> 
				</tr>
				<tr>
					<td class="row1">{L_TEAM_SFIGHT}:</td>
					<td class="row3"><input type="radio" name="team_show_wars" value="1" {CHECKED_SWARS_YES} /> {L_YES} <input type="radio" name="team_show_wars" value="0" {CHECKED_SWARS_NO} /> {L_NO} </td> 
				</tr>
				<tr>
					<td class="row1">{L_TEAM_JOIN}:</td>
					<td class="row3"><input type="radio" name="team_join" value="1" {CHECKED_JOIN_YES} /> {L_YES} <input type="radio" name="team_join" value="0" {CHECKED_JOIN_NO} /> {L_NO} </td> 
				</tr>
				<tr>
					<td class="row1">{L_TEAM_FIGHT}:</td>
					<td class="row3"><input type="radio" name="team_fight" value="1" {CHECKED_FIGHT_YES} /> {L_YES} <input type="radio" name="team_fight" value="0" {CHECKED_FIGHT_NO} /> {L_NO} </td> 
				</tr>
				<tr>
					<td class="row1">{L_TEAM_VIEW}:</td>
					<td class="row3"><input type="radio" name="team_view" value="1" {CHECKED_VIEW_YES} /> {L_YES} <input type="radio" name="team_view" value="0" {CHECKED_VIEW_NO} /> {L_NO} </td> 
				</tr>
				<tr>
					<td class="row1">{L_TEAM_SHOW}:</td>
					<td class="row3"><input type="radio" name="team_show" value="1" {CHECKED_SHOW_YES} /> {L_YES} <input type="radio" name="team_show" value="0" {CHECKED_SHOW_NO} /> {L_NO} </td> 
				</tr>
				</table>
			</td>
			<td valign="top">
				<!-- BEGIN logo_upload -->
				<table class="edit" cellspacing="1" style="vertical-align:top;">
				<tr>
					<th colspan="2">{L_LOGO_SETTINGS}</th>
				</tr>
				<!-- BEGIN team_logo_upload -->
				<tr>
					<td class="row1" width="160">{L_TEAM_LOGO_UP}:<br /><span class="small">{L_LOGO_UP_EXPLAIN}</span></td>
					<td class="row3"><input class="post" type="file" name="team_logo"></td>
				</tr>
				<tr>
					<td colspan="2">{TEAM_LOGO}<br /><input type="checkbox" name="logodel" /></td>
				</tr>
				<!-- END team_logo_upload -->
				<!-- BEGIN team_logos_upload -->
				<tr>
					<td class="row1">{L_TEAM_LOGOS_UP}:<br /><span class="small">{L_LOGOS_UP_EXPLAIN}</span></td>
					<td class="row3"><input class="post" type="file" name="team_logos"></td>
				</tr>
				<tr>
					<td colspan="2">{TEAM_LOGOS}<br /><input type="checkbox" name="logosdel" /></td>
				</tr>
				<!-- END team_logos_upload -->
				</table>
				<!-- END logo_upload -->
			</td>
		</tr>
		</table>
	
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>


<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END teams_edit -->

<!-- BEGIN teams_member -->
<form action="{S_TEAM_ACTION}" method="post" id="list" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_TEAM_ACTION}">{L_TEAM_TITLE}</a></li>
				<li><a href="{S_TEAM_EDIT}">{L_TEAM_NEW_EDIT}</a></li>
				<li id="active"><a href="#" id="current">{L_TEAM_MEMBER}</a></li>
			</ul>
		</div>
	</th>

</tr>
<tr>
	<td>{L_TEAM_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" align="center">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">{L_JOIN}</td>
	<td class="rowHead" align="center">{L_RANK}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN members_row -->
<tr>
	<td class="{teams_member.members_row.CLASS}" align="left">{teams_member.members_row.USERNAME}</td>
	<td class="{teams_member.members_row.CLASS}" align="center">{teams_member.members_row.REGISTER}</td>
	<td class="{teams_member.members_row.CLASS}" align="center">{teams_member.members_row.JOINED}</td>
	<td class="{teams_member.members_row.CLASS}" align="left">{teams_member.members_row.RANK}</td>
	<td class="{teams_member.members_row.CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{teams_member.members_row.USER_ID}" /></td>
</tr>
<!-- END members_row -->
<!-- BEGIN no_members_row -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_TEAMS}</td>
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

<form action="{S_TEAM_ACTION}" method="post" name="post" id="list">
<table class="head" cellspacing="0">
<tr>
	<th>{L_TEAM_TITLE} - {L_TEAM_ADD_MEMBER}</th>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
</table>

<table class="edit" cellspacing="1">
	<tr>
		<td class="row1" width="40%"><b>{L_RANK}:</b></td>
		<td class="row3" width="60%">{S_RANK_SELECT}</td>
	</tr>
	<tr>
		<td class="row1" valign="top"><b>{L_TEAM_ADD}:</b><br /><span class="small">{L_TEAM_ADD_MEMBER_EX}</span></td>
		<td class="row3"><textarea class="textarea" name="members" cols="80" rows="5"></textarea></td>
	</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td align="right"><input type="submit" name="send" value="{L_SUBMIT}" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS2}
</form>
<!-- END teams_member -->