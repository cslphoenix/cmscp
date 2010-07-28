<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
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
	<td class="rowHead" width="99%" colspan="2">{L_NAME}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_MEMBERCOUNT}</td>
	<td class="rowHead" align="center" nowrap="nowrap" colspan="4">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _team_row -->
<tr>
	<td class="row_class1" align="center" width="1%">{_display._team_row.GAME}</td>
	<td class="row_class1" align="left" width="100%">{_display._team_row.NAME}</td>
	<td class="row_class1" align="center" width="1%">{_display._team_row.MEMBER_COUNT}</td>
	<td class="row_class2" align="center">{_display._team_row.MOVE_UP}{_display._team_row.MOVE_DOWN} <a href="{_display._team_row.U_MEMBER}">{I_MEMBER}</a> <a href="{_display._team_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._team_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _team_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END _no_entry -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="team_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
<script type="text/javascript">
// <![CDATA[
	
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{GAME_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
	
// ]]>
</script>

<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	<!-- BEGIN _member -->
	<li><a href="{S_MEMBER}">{L_MEMBER}</a></li>
	<!-- END _member -->
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
				<li id="active"><a href="#" id="current">{L_INFOS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="155"><label for="team_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="team_name" id="team_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1">{L_GAME}: *</td>
	<td class="row2">{S_GAME}&nbsp;<img src="{GAME_IMAGE}" id="image" alt="" width="{GAME_SIZE}" height="{GAME_SIZE}"></td>
</tr>
<tr>
	<td class="row1 top">{L_DESC}: *</td>
	<td class="row2"><textarea class="textarea" name="team_desc" rows="5" cols="40">{DESC}</textarea></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="50%" valign="top">
				<table class="update" border="0" cellspacing="0" cellpadding="0">
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
					<td class="row1" width="155"><label for="team_navi">{L_NAVI}:</label></td>
					<td class="row2"><label><input type="radio" name="team_navi" id="team_navi" value="1" {S_NAVI_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="team_navi" value="0" {S_NAVI_NO} />&nbsp;{L_NO}</label></td> 
				</tr>
				<tr>
					<td class="row1"><label for="team_awards">{L_AWARDS}:</label></td>
					<td class="row2"><label><input type="radio" name="team_awards" id="team_awards" value="1" {S_SAWARDS_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="team_awards" value="0" {S_SAWARDS_NO} />&nbsp;{L_NO}</label></td> 
				</tr>
				<tr>
					<td class="row1"><label for="team_wars">{L_FIGHTS}:</label></td>
					<td class="row2"><label><input type="radio" name="team_wars" id="team_wars" value="1" {S_SWARS_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="team_wars" value="0" {S_SWARS_NO} />&nbsp;{L_NO}</label></td> 
				</tr>
				<tr>
					<td class="row1"><label for="team_join">{L_JOIN}:</label></td>
					<td class="row2"><label><input type="radio" name="team_join" id="team_join" value="1" {S_JOIN_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="team_join" value="0" {S_JOIN_NO} />&nbsp;{L_NO}</label></td> 
				</tr>
				<tr>
					<td class="row1"><label for="team_fight">{L_FIGHT}:</label></td>
					<td class="row2"><label><input type="radio" name="team_fight" id="team_fight" value="1" {S_FIGHT_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="team_fight" value="0" {S_FIGHT_NO} />&nbsp;{L_NO}</label></td> 
				</tr>
				<tr>
					<td class="row1"><label for="team_view">{L_VIEW}:</label></td>
					<td class="row2"><label><input type="radio" name="team_view" id="team_view" value="1" {S_VIEW_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="team_view" value="0" {S_VIEW_NO} />&nbsp;{L_NO}</label></td> 
				</tr>
				<tr>
					<td class="row1"><label for="team_show">{L_SHOW}:</label></td>
					<td class="row2"><label><input type="radio" name="team_show" id="team_show" value="1" {S_SHOW_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="team_show" value="0" {S_SHOW_NO} />&nbsp;{L_NO}</label></td> 
				</tr>
				</table>
			</td>
			<!-- BEGIN _upload -->
			<td width="1%">&nbsp;</td>
			<td width="49%" valign="top">
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
				<!-- BEGIN _logo -->
				<tr>
					<td class="row1 top" width="150" nowrap="nowrap"><label for="team_logo">{L_LOGO_UP}:</label></td>
					<td class="row2"><input class="post" type="file" name="temp_logo"><br /><span class="small">{L_LOGO_UP_INFO}</span></td>
				</tr>
				<!-- BEGIN _img -->
				<tr>
					<td class="row1 top">{L_LOGO_CURRENT}:</td>
					<td class="row2"><img src="{LOGO}" alt="" /><br /><input type="hidden" name="image_logo" value="{IMG_LOGO}" /><input type="checkbox" name="delete_logo" id="delete_logo">&nbsp;<label for="delete_logo">{L_DELETE}</label></td>
				</tr>
				<!-- END _img -->
				<!-- END _logo -->
				<!-- BEGIN _flag -->
				<tr>
					<td class="row1 top" width="25%" nowrap="nowrap">{L_FLAG_UP}:</td>
					<td class="row2"><input class="post" type="file" name="temp_flag" /><br /><span class="small">{L_FLAG_UP_INFO}</span></td>
				</tr>
				<!-- BEGIN _img -->
				<tr>
					<td class="row1 top">{L_FLAG_CURRENT}:</td>
					<td class="row2"><img src="{FLAG}" alt="" /><br /><input type="hidden" name="image_flag" value="{IMG_FLAG}" /><input type="checkbox" name="delete_flag" id="delete_flag">&nbsp;<label for="delete_flag">{L_DELETE}</label></td>
				</tr>
				<!-- END _img -->
				<!-- END _flag -->
				</table>
			</td>
			<!-- END _upload -->
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;<input type="reset" class="button" name="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->

<!-- BEGIN _member -->
<form action="{S_ACTION}" method="post" id="list" name="list">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_INPUT}">{L_INPUT}</a></li>
	<li id="active"><a href="#" id="current">{L_MEMBER}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_MEMBERS_EXPLAIN}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MODERATOR}</a></li>
</ul>
</div>
<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" align="center" width="49%">{L_USERNAME}</td>
	<td class="rowHead" align="center" width="15%">{L_REGISTER}</td>
	<td class="rowHead" align="center" width="15%">{L_JOIN}</td>
	<td class="rowHead" align="center" width="20%">{L_RANK}</td>
	<td class="rowHead" align="center" width="1%">#</td>
</tr>
<!-- BEGIN _mod_row -->
<tr>
	<td class="row_class1" align="left">{_member._mod_row.NAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._mod_row.REG}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._mod_row.JOIN}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._mod_row.RANK}</td>
	<td class="row_class2" align="center"><input type="checkbox" name="members[]" value="{_member._mod_row.ID}"></td>
</tr>
<!-- END _mod_row -->
<!-- BEGIN _no_moderators -->
<tr>
	<td class="row_noentry" colspan="5" align="center">{L_NO_MODERATORS}</td>
</tr>
<!-- END _no_moderators -->
</table>

<br />

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MEMBER}</a></li>
</ul>
</div>
<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" align="center" width="49%">{L_USERNAME}</td>
	<td class="rowHead" align="center" width="15%">{L_REGISTER}</td>
	<td class="rowHead" align="center" width="15%">{L_JOIN}</td>
	<td class="rowHead" align="center" width="20%">{L_RANK}</td>
	<td class="rowHead" align="center" width="1%">#</td>
</tr>
<!-- BEGIN _member_row -->
<tr>
	<td class="row_class1" align="left">{_member._member_row.NAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._member_row.REG}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._member_row.JOIN}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._member_row.RANK}</td>
	<td class="row_class2" align="center"><input type="checkbox" name="members[]" value="{_member._member_row.ID}"></td>
</tr>
<!-- END _member_row -->
<!-- BEGIN _no_members -->
<tr>
	<td class="row_noentry" colspan="5" align="center">{L_NO_MEMBERS}</td>
</tr>
<!-- END _no_members -->
</table>

<table class="footer" cellspacing="2">
<tr>
	<td align="right">{S_OPTIONS} {S_RANKS}</td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td align="right" colspan="2"><a href="#" onclick="marklist('list', 'member', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>

<!-- BEGIN user_add -->
<form action="{S_ACTION}" method="post" name="post" id="list">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_MEMBERS_ADD}</a></li>
	</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_MEMBERS_ADD_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="155"><label for="moderator" title="{L_MEMBER_ADD_MOD}">{L_MEMBER_ADD_MOD}:</label></td>
	<td class="row2"><label><input type="radio" name="moderator" id="moderator" value="1" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="moderator" value="0" checked="checked" />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="rank_id">{L_MEMBER_ADD_RANK}:</label></td>
	<td class="row2">{S_RANK_SELECT}</td>
</tr>
<tr>
	<td class="row1 top"><label for="members">{L_USERNAME}:</label></td>
	<td class="row2"><textarea class="textarea" name="members" style="width:50%" rows="5"></textarea><br />{S_SELECT_USERS}</td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
</table>
{S_FIELDS}
<input type="hidden" name="smode" value="_user_create" />
</form>
<!-- END user_add -->
<!-- END _member -->