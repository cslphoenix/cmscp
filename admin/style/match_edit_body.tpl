<form action="{S_MATCH_ACTION}" method="post" name="form" onSubmit="javascript:return checkForm()">
<table class="head" cellspacing="0">
<tr>
	<th>{L_TEAM_TITLE} - {L_TEAM_NEW_EDIT}</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />
<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="edit" cellspacing="1">
<tr>
	<th colspan="2">{L_TEAM_INFOS}</th>
</tr>
<tr>
	<td class="row1" width="160">{L_TEAM_NAME}: *</td>
	<td class="row3">{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}: *</td>
	<td class="row3">{S_TYPE}</td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}: *</td>
	<td class="row3">{S_CATEGORIE}</td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}: *</td>
	<td class="row3">{S_LEAGUE}</td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><input class="post" type="text" name="match_league_url" value="{LEAGUE_URL}" ></td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><input class="post" type="text" name="match_league_match" value="{LEAGUE_MATCH}" ></td>
</tr>

<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}  </td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><input type="radio" name="match_public" value="1" {S_CHECKED_PUB_YES} /> {L_YES} <input type="radio" name="match_public" value="0" {S_CHECKED_PUB_NO} /> {L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><input type="radio" name="match_comments" value="1" {S_CHECKED_COM_YES} /> {L_YES} <input type="radio" name="match_comments" value="0" {S_CHECKED_COM_NO} /> {L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}: *</td>
	<td class="row3"><input id="match_rival" onBlur="javascript:checkEntry(this)" class="post" type="text" name="match_rival" value="{MATCH_RIVAL}" ></td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}: *</td>
	<td class="row3"><input id="match_rival_tag" onBlur="javascript:checkEntry(this)" class="post" type="text" name="match_rival_tag" value="{MATCH_RIVAL_TAG}" ></td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><input class="post" type="text" name="match_rival_url" value="{MATCH_RIVAL_URL}" ></td>
</tr>


<tr>
	<td class="row1">{L_TEAM_GAME}: *</td>
	<td class="row3"><input id="server" onBlur="javascript:checkEntry(this)" class="post" type="text" name="server" value="{SERVER}" ></td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><input class="post" type="text" name="server_pw" value="{SERVER_PW}" ></td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><input class="post" type="text" name="server_hltv" value="{SERVER_HLTV}" ></td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><input class="post" type="text" name="server_hltv_pw" value="{SERVER_HLTV_PW}" ></td>
</tr>
<!-- BEGIN new_match -->
<tr>
	<td class="row1">{L_TRAIN}:</td>
	<td class="row3"><input type="radio" name="train" value="1" onChange="document.getElementById('trainbox').style.display = '';" /> {L_YES} <input type="radio" name="train" value="0" onChange="document.getElementById('trainbox').style.display = 'none';" checked="checked" /> {L_NO} </td>
</tr>
<tbody id="trainbox" style="display: none;">
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3">{S_TDAY} . {S_TMONTH} . {S_TYEAR} - {S_THOUR} : {S_TMIN} - {S_TDURATION}</td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><input class="post" type="text" name="training_maps" value="" ></td>
</tr>
<tr>
	<td class="row1">{L_TEAM_GAME}:</td>
	<td class="row3"><textarea class="post" rows="5" cols="50" name="training_comment"></textarea></td>
</tr>
</tbody>
<!-- END new_match -->

<tr>
	<td colspan="2">&nbsp;</td>
</tr>


<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>