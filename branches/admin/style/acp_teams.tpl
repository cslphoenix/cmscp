<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
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
	<td class="rowHead" colspan="2">{L_NAME}</td>
	<td class="rowHead">{L_MEMBERCOUNT}</td>
	<td class="rowHead" colspan="4" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN teams_row -->
<tr>
	<td class="row_class1" align="center">{display.teams_row.GAME}</td>
	<td class="row_class1" align="left" width="100%">{display.teams_row.NAME}</td>
	<td class="row_class1" align="center" width="1%">{display.teams_row.MEMBER_COUNT}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{display.teams_row.MOVE_UP} {display.teams_row.MOVE_DOWN}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.teams_row.U_MEMBER}">{L_MEMBER}</a></td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.teams_row.U_UPDATE}">{L_UPDATE}</a></td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.teams_row.U_DELETE}">{L_DELETE}</a></td>
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
	<td><input class="button" type="submit" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN team_edit -->

<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{GAME_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_ACTION}" method="post" name="post" enctype="multipart/form-data">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_ACTION}" method="post">{L_TITLE}</a></li>
				<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
				<!-- BEGIN member -->
				<li><a href="{S_MEMBER}">{L_MEMBER}</a></li>
				<!-- END member -->
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INFOS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="25%">{L_NAME}: *</td>
	<td class="row3"><input type="text" class="post" name="team_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1">{L_GAME}:</td>
	<td class="row3">{S_GAME}&nbsp;<img src="{GAME_IMAGE}" id="image" alt="" width="{GAME_SIZE}" height="{GAME_SIZE}"></td>
</tr>
<tr>
	<td class="row1" valign="top">{L_DESC}:</td>
	<td class="row3"><textarea class="textarea" name="team_description" rows="5" cols="40">{DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top">
				<table class="edit" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th colspan="2">
						<div id="navcontainer">
							<ul id="navlist">
								<li id="active"><a href="#" id="current">{L_MENU_SETTINGS}</a></li>
							</ul>
						</div>
					</th>
				</tr>
				<tr>
					<td class="row1" width="160">{L_NAVI}:</td>
					<td class="row3"><input type="radio" name="team_navi" value="1" {CHECKED_NAVI_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="team_navi" value="0" {CHECKED_NAVI_NO} />&nbsp;{L_NO}</td> 
				</tr>
				<tr>
					<td class="row1">{L_AWARDS}:</td>
					<td class="row3"><input type="radio" name="team_awards" value="1" {CHECKED_SAWARDS_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="team_awards" value="0" {CHECKED_SAWARDS_NO} />&nbsp;{L_NO}</td> 
				</tr>
				<tr>
					<td class="row1">{L_FIGHTS}:</td>
					<td class="row3"><input type="radio" name="team_wars" value="1" {CHECKED_SWARS_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="team_wars" value="0" {CHECKED_SWARS_NO} />&nbsp;{L_NO}</td> 
				</tr>
				<tr>
					<td class="row1">{L_JOIN}:</td>
					<td class="row3"><input type="radio" name="team_join" value="1" {CHECKED_JOIN_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="team_join" value="0" {CHECKED_JOIN_NO} />&nbsp;{L_NO}</td> 
				</tr>
				<tr>
					<td class="row1">{L_FIGHT}:</td>
					<td class="row3"><input type="radio" name="team_fight" value="1" {CHECKED_FIGHT_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="team_fight" value="0" {CHECKED_FIGHT_NO} />&nbsp;{L_NO}</td> 
				</tr>
				<tr>
					<td class="row1">{L_VIEW}:</td>
					<td class="row3"><input type="radio" name="team_view" value="1" {CHECKED_VIEW_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="team_view" value="0" {CHECKED_VIEW_NO} />&nbsp;{L_NO}</td> 
				</tr>
				<tr>
					<td class="row1">{L_SHOW}:</td>
					<td class="row3"><input type="radio" name="team_show" value="1" {CHECKED_SHOW_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="team_show" value="0" {CHECKED_SHOW_NO} />&nbsp;{L_NO}</td> 
				</tr>
				</table>
			</td>
			<td valign="top">
				<!-- BEGIN logo_upload -->
				<table class="edit" cellspacing="1" style="vertical-align:top;">
				<tr>
					<th colspan="2">
						<div id="navcontainer">
							<ul id="navlist">
								<li id="active"><a href="#" id="current">{L_LOGO_SETTINGS}</a></li>
							</ul>
						</div>
					</th>
				</tr>
				<!-- BEGIN team_logo_upload -->
				<tr>
					<td class="row1" width="160">{L_LOGO_UP}:<br><span class="small">{L_LOGO_UP_EXPLAIN}</span></td>
					<td class="row3"><input class="post" type="file" name="team_logo"></td>
				</tr>
				<tr>
					<td colspan="2">{LOGO}<br><input type="checkbox" name="logodel"></td>
				</tr>
				<!-- END team_logo_upload -->
				<!-- BEGIN team_logos_upload -->
				<tr>
					<td class="row1">{L_LOGOS_UP}:<br><span class="small">{L_LOGOS_UP_EXPLAIN}</span></td>
					<td class="row3"><input class="post" type="file" name="team_logos"></td>
				</tr>
				<tr>
					<td colspan="2">{LOGOS}<br><input type="checkbox" name="logosdel"></td>
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
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END team_edit -->

<!-- BEGIN team_member -->
<form action="{S_ACTION}" method="post" id="list" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_ACTION}" method="post">{L_TITLE}</a></li>
				<li><a href="{S_EDIT}">{L_EDIT}</a></li>
				<li id="active"><a href="#" id="current">{L_MEMBER}</a></li>
			</ul>
		</div>
	</th>

</tr>
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" align="center">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">{L_JOIN}</td>
	<td class="rowHead" align="center">{L_RANK}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<tr>
	<td class="rowHead" colspan="7">{L_MODERATOR}</td>
</tr>
<!-- BEGIN mods_row -->
<tr>
	<td class="{team_member.mods_row.CLASS}" align="left">{team_member.mods_row.USERNAME}</td>
	<td class="{team_member.mods_row.CLASS}" align="center">{team_member.mods_row.REGISTER}</td>
	<td class="{team_member.mods_row.CLASS}" align="center">{team_member.mods_row.JOINED}</td>
	<td class="{team_member.mods_row.CLASS}" align="left">{team_member.mods_row.RANK}</td>
	<td class="{team_member.mods_row.CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{team_member.mods_row.USER_ID}"></td>
</tr>
<!-- END mods_row -->
<!-- BEGIN switch_no_moderators -->
<tr>
	<td colspan="7" class="row_class1" align="center"><span class="gen">{L_NO_MODERATORS}</span></td>
</tr>
<!-- END switch_no_moderators -->

<tr>
	<td class="rowHead" colspan="7">{L_MEMBER}</td>
</tr>
<!-- BEGIN nomods_row -->
<tr>
	<td class="{team_member.nomods_row.CLASS}" align="left">{team_member.nomods_row.USERNAME}</td>
	<td class="{team_member.nomods_row.CLASS}" align="center">{team_member.nomods_row.REGISTER}</td>
	<td class="{team_member.nomods_row.CLASS}" align="center">{team_member.nomods_row.JOINED}</td>
	<td class="{team_member.nomods_row.CLASS}" align="left">{team_member.nomods_row.RANK}</td>
	<td class="{team_member.nomods_row.CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{team_member.nomods_row.USER_ID}"></td>
</tr>
<!-- END nomods_row -->
<!-- BEGIN switch_no_members -->
<tr>
	<td colspan="7" class="row_class1" align="center"><span class="gen">{L_NO_MEMBERS}</span></td>
</tr>
<!-- END switch_no_members -->

<!-- BEGIN no_members_row -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_TEAMS}</td>
</tr>
<!-- END no_members_row -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td colspan="2" align="right">
		{S_SELECT_OPTIONS}
		<input type="submit" name="send" value="{L_SUBMIT}" class="button2" />
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'member', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>

<!-- BEGIN user_add -->
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
	<td><span class="small">{L_ADD_MEMBER_EX}</span></td>
</tr>
</table>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2" valign="top"><textarea class="textarea" name="members" style="width:100%" rows="5"></textarea></td>
	<td class="row2" valign="top" width="1%">{S_SELECT_USERS}</td>
	<td class="row2" valign="top">{S_RANK_SELECT}<br /><span class="small"><em>({L_RANK})</em></span></td>
</tr>
<tr>
	<td colspan="2" class="row2"><input type="checkbox" name="mod" /> Moderatorstatus</td>
</tr>
<tr>
	<td colspan="2" class="row2"><input type="submit" name="send2" value="{L_SUBMIT}" class="button2"></td>
</tr>
</table>
{S_HIDDEN_FIELDS2}
</form>
<!-- END user_add -->
<!-- END team_member -->