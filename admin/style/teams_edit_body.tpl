
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
	<th>{L_TEAM_TITLE} - {L_TEAM_NEW_EDIT}</th>
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