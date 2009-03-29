<!-- BEGIN display -->
<form method="post" action="{S_MATCH_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>{L_MATCH_TITLE}</th>
</tr>
<tr>
	<td class="row2">{L_MATCH_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="3">{L_MATCH_NAME}</td>
	<td class="rowHead">{L_TRAINING}</td>
	<td class="rowHead" colspan="3">{L_MATCH_SETTINGS}</td>
</tr>
<tr>
		<td class="rowHead" colspan="7">{L_UPCOMING}</td>
	</tr>
<!-- BEGIN match_row_n -->
<tr>
	<td class="{display.match_row_n.CLASS}" align="center" width="1%">{display.match_row_n.MATCH_GAME}</td>
	<td class="{display.match_row_n.CLASS}" align="left" width="100%">{display.match_row_n.MATCH_NAME}</td>
	<td class="{display.match_row_n.CLASS}" align="center" nowrap>{display.match_row_n.MATCH_DATE}</td>
	<td class="{display.match_row_n.CLASS}" align="center"><a href="{display.match_row_n.U_TRAINING}">{display.match_row_n.TRAINING}</a></td>
	<td class="{display.match_row_n.CLASS}" align="center" width="1%"><a href="{display.match_row_n.U_EDIT}">{L_MATCH_SETTING}</a></td>
	<td class="{display.match_row_n.CLASS}" align="center" width="1%"><a href="{display.match_row_n.U_DETAILS}">{L_MATCH_DETAILS}</a></td>
	
	<td class="{display.match_row_n.CLASS}" align="center" width="1%"><a href="{display.match_row_n.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END match_row_n -->
<!-- BEGIN no_entry -->
	<tr>
		<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry -->
	<tr>
		<td class="rowHead" colspan="7">{L_EXPIRED}</td>
	</tr>
<!-- BEGIN match_row_o -->
<tr>
	<td class="{display.match_row_o.CLASS}" align="center" width="1%">{display.match_row_o.MATCH_GAME}</td>
	<td class="{display.match_row_o.CLASS}" align="left" width="100%">{display.match_row_o.MATCH_NAME}</td>
	<td class="{display.match_row_o.CLASS}" align="center" nowrap>{display.match_row_n.MATCH_DATE}</td>
	<td class="{display.match_row_o.CLASS}" align="center"> - </td>
	<td class="{display.match_row_o.CLASS}" align="center" width="1%"><a href="{display.match_row_o.U_EDIT}">{L_MATCH_SETTING}</a></td>
	<td class="{display.match_row_o.CLASS}" align="center" width="1%"><a href="{display.match_row_o.U_DETAILS}">{L_MATCH_DETAILS}</a></td>
	
	<td class="{display.match_row_o.CLASS}" align="center" width="1%"><a href="{display.match_row_o.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END match_row_o -->
<!-- BEGIN no_entry -->
	<tr>
		<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry -->
	</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right">{S_TEAMS}</td>
	<td><input class="button" type="submit" name="add" value="{L_MATCH_CREATE}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN match_edit -->
<form action="{S_MATCH_ACTION}" method="post" name="form" onSubmit="javascript:return checkForm()">
<table class="head" cellspacing="0">
<tr>
	<th>{L_MATCH_TITLE} - {L_MATCH_NEW_EDIT}</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />
<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="edit" cellspacing="1">
<tr>
	<th colspan="2">{L_MATCH_INFOS}</th>
</tr>
<tr>
	<td class="row1" width="160">{L_MATCH_TEAM}: *</td>
	<td class="row3">{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1">{L_MATCH_TYPE}: *</td>
	<td class="row3">{S_TYPE}</td>
</tr>
<tr>
	<td class="row1">{L_MATCH_CATEGORIE}: *</td>
	<td class="row3">{S_CATEGORIE}</td>
</tr>
<tr>
	<td class="row1">{L_MATCH_LEAGUE}: *</td>
	<td class="row3">{S_LEAGUE}</td>
</tr>
<tr>
	<td class="row1">{L_MATCH_LEAGUE_URL}:</td>
	<td class="row3"><input class="post" type="text" name="match_league_url" value="{LEAGUE_URL}" ></td>
</tr>
<tr>
	<td class="row1">{L_LEAGUE_MATCH}:</td>
	<td class="row3"><input class="post" type="text" name="match_league_match" value="{LEAGUE_MATCH}" ></td>
</tr>
<tr>
	<td class="row1">{L_MATCH_DATE}:</td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}  </td>
</tr>
<tr>
	<td class="row1">{L_MATCH_PUBLIC}:</td>
	<td class="row3"><input type="radio" name="match_public" value="1" {S_CHECKED_PUB_YES} /> {L_YES} <input type="radio" name="match_public" value="0" {S_CHECKED_PUB_NO} /> {L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_MATCH_COMMENTS}:</td>
	<td class="row3"><input type="radio" name="match_comments" value="1" {S_CHECKED_COM_YES} /> {L_YES} <input type="radio" name="match_comments" value="0" {S_CHECKED_COM_NO} /> {L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_MATCH_RIVAL}: *</td>
	<td class="row3"><input id="match_rival" onBlur="javascript:checkEntry(this)" class="post" type="text" name="match_rival" value="{MATCH_RIVAL}" ></td>
</tr>
<tr>
	<td class="row1">{L_MATCH_RIVALTAG}: *</td>
	<td class="row3"><input id="match_rival_tag" onBlur="javascript:checkEntry(this)" class="post" type="text" name="match_rival_tag" value="{MATCH_RIVAL_TAG}" ></td>
</tr>
<tr>
	<td class="row1">{L_MATCH_RIVALURL}:</td>
	<td class="row3"><input class="post" type="text" name="match_rival_url" value="{MATCH_RIVAL_URL}" ></td>
</tr>

<tr>
	<td class="row1">{L_MATCH_SERVER}: *</td>
	<td class="row3"><input id="server" onBlur="javascript:checkEntry(this)" class="post" type="text" name="server" value="{SERVER}" ></td>
</tr>
<tr>
	<td class="row1">{L_MATCH_SERVERPW}:</td>
	<td class="row3"><input class="post" type="text" name="server_pw" value="{SERVER_PW}" ></td>
</tr>
<tr>
	<td class="row1">{L_MATCH_HLTV}:</td>
	<td class="row3"><input class="post" type="text" name="server_hltv" value="{SERVER_HLTV}" ></td>
</tr>
<tr>
	<td class="row1">{L_MATCH_HLTVPW}:</td>
	<td class="row3"><input class="post" type="text" name="server_hltv_pw" value="{SERVER_HLTV_PW}" ></td>
</tr>
<tr>
	<td class="row1">{L_MATCH_TEXT}:</td>
	<td class="row3"><textarea class="post" rows="5" cols="50" name="match_text"></textarea></td>
</tr>
<!-- BEGIN new_match -->
<tr>
	<td class="row1">{L_TRAINING}:</td>
	<td class="row3"><input type="radio" name="train" value="1" onChange="document.getElementById('trainbox').style.display = '';" /> {L_YES} <input type="radio" name="train" value="0" onChange="document.getElementById('trainbox').style.display = 'none';" checked="checked" /> {L_NO} </td>
</tr>
<tbody id="trainbox" style="display: none;">
<tr>
	<td class="row1">{L_TRAINING_DATE}:</td>
	<td class="row3">{S_TDAY} . {S_TMONTH} . {S_TYEAR} - {S_THOUR} : {S_TMIN} - {S_TDURATION}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_MAPS}:</td>
	<td class="row3"><input class="post" type="text" name="training_maps" value="" ></td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_TEXT}:</td>
	<td class="row3"><textarea class="post" rows="5" cols="50" name="training_text"></textarea></td>
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
<!-- END match_edit -->

<!-- BEGIN match_details -->
<form action="{S_TEAM_ACTION}" method="post" id="list" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>{L_MATCH_TITLE} - {L_MATCH_DETAILS}</th>
	<th style=" text-align:right;">{L_MATCH_TITLE} - {L_MATCH_DETAILS}</th>
</tr>
<tr>
	<td class="row2" colspan="2">{L_MATCH_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="normal" cellspacing="0">
<tr>
	<td valign="top">
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead" align="center" colspan="2">{L_MATCH_INFO}</td>
		</tr>
		<tr>
			<td class="row_class1">{L_RIVAL}</td>
			<td class="row_class2">{MATCH_RIVAL} - <a href="{U_MATCH_RIVAL_URL}">{MATCH_RIVAL_URL}</a></td>
		</tr>
		<tr>
			<td class="row_class1">{L_RIVAL_TAG}</td>
			<td class="row_class2">{MATCH_RIVAL_TAG}</td>
		</tr>
		<tr>
			<td class="row_class1">{L_MATCH_DETAILS}</td>
			<td class="row_class2">{MATCH_CATEGORIE}: {MATCH_TYPE} - {MATCH_LEAGUE_INFO}</td>
		</tr>
		<tr>
			<td class="row_class1">{L_SERVER}</td>
			<td class="row_class2">{SERVER} {SERVER_PW}</td>
		</tr>
		<!-- BEGIN hltv -->
		<tr>
			<td class="row_class1">{L_HLTV}</td>
			<td class="row_class2">{HLTV} {HLTV_PW}</td>
		</tr>
		<!-- END hltv -->
		</table>
	</td>
	<td>&nbsp;</td>
	<td valign="top">
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead" colspan="3">{L_USERNAME}</td>
		</tr>
		<!-- BEGIN members_row -->
		<tr>
			<td class="{match_details.members_row.CLASS}" align="left">{match_details.members_row.USERNAME}</td>
			<td class="{match_details.members_row.CLASS}" align="left">{match_details.members_row.STATUS}</td>
			<td class="{match_details.members_row.CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{match_details.members_row.USER_ID}" /></td>
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

	</td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>

<form action="{S_TEAM_ACTION}" method="post" id="post" name="post" enctype="multipart/form-data">
<table class="edit" cellspacing="1">
<tr>
	<td class="row1">{L_RIVAL_LINEUP}:</td>
	<td class="row3" colspan="5"><input class="post" type="text" size="50" name="details_lineup_rival" value="{DETAILS_LINEUP_RIVAL}" ></td>
</tr>
<tr>
	<td class="row1" width="160">{L_MAP} {L_POINTS}:{L_POINTS}<br /><span class="small">{L_POINTS_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" name="details_mapa" value="{DETAILS_MAPA}" > <input class="post" type="text" name="details_mapa_clan" value="{DETAILS_MAPA_CLAN}" size="2" > : <input class="post" type="text" name="details_mapa_rival" value="{DETAILS_MAPA_RIVAL}" size="2" >
	<td rowspan="3" width="1%">{DETAILS_PIC_A}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN pictureadel -->
		<input type="checkbox" name="pictureadel" />
		<!-- END pictureadel -->
	</td>
	<td rowspan="3" width="1%">{DETAILS_PIC_B}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN picturebdel -->
		<input type="checkbox" name="picturebdel" />
		<!-- END picturebdel -->
	</td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD_MAP}:</td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_a"></td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD_MAP}:</td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_b"></td>
</tr>

<tr>
	<td class="row1">{L_MAP} {L_POINTS}:{L_POINTS}<br /><span class="small">{L_POINTS_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" name="details_mapb" value="{DETAILS_MAPB}" > <input class="post" type="text" name="details_mapb_clan" value="{DETAILS_MAPB_CLAN}" size="2" > : <input class="post" type="text" name="details_mapb_rival" value="{DETAILS_MAPB_RIVAL}" size="2" >
	<td rowspan="3" width="1%">{DETAILS_PIC_C}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN picturecdel -->
		<input type="checkbox" name="picturecdel" />
		<!-- END picturecdel -->
	</td>
	<td rowspan="3" width="1%">{DETAILS_PIC_D}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN pictureddel -->
		<input type="checkbox" name="pictureddel" />
		<!-- END pictureddel -->
	</td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD_MAP}:</td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_c"></td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD_MAP}:</td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_d"></td>
</tr>
<tr>
	<td colspan="6" class="row3"><a onClick="document.getElementById('mapc').style.display = '';" href="#" >{L_MAP_MORE}</a> :: <a onClick="document.getElementById('mapc').style.display = 'none'; document.getElementById('mapd').style.display = 'none';" href="#" >{L_MAP_CLOSE}</a></td>
	</tr>

<tbody id="mapc" style="display:{MAPC};">
<tr>
	<td class="row1">{L_MAP} {L_POINTS}:{L_POINTS}<br /><span class="small">{L_POINTS_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" name="details_mapc" value="{DETAILS_MAPC}" > <input class="post" type="text" name="details_mapc_clan" value="{DETAILS_MAPC_CLAN}" size="2" > : <input class="post" type="text" name="details_mapc_rival" value="{DETAILS_MAPC_RIVAL}" size="2" >
	<td rowspan="3" width="1%">{DETAILS_PIC_E}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN pictureedel -->
		<input type="checkbox" name="pictureedel" value="pic_e" />
		<!-- END pictureedel -->
	</td>
	<td rowspan="3" width="1%">{DETAILS_PIC_F}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN picturefdel -->
		<input type="checkbox" name="picturefdel" value="pic_f" />
		<!-- END picturefdel -->
	</td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD_MAP}:</td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_e"></td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD_MAP}:</td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_f"></td>
</tr>
<tr>
	<td colspan="6" class="row3"><a onClick="document.getElementById('mapd').style.display = '';" href="#" >{L_MAP_MORE}</a> :: <a onClick="document.getElementById('mapd').style.display = 'none';" href="#" >{L_MAP_CLOSE}</a></td>
	</tr>

</tbody>
<tbody id="mapd" style="display:{MAPD};">
<tr>
	<td class="row1">{L_MAP} {L_POINTS}:{L_POINTS}<br /><span class="small">{L_POINTS_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" name="details_mapd" value="{DETAILS_MAPD}" > <input class="post" type="text" name="details_mapd_clan" value="{DETAILS_MAPD_CLAN}" size="2" > : <input class="post" type="text" name="details_mapd_rival" value="{DETAILS_MAPD_RIVAL}" size="2" >
	<td rowspan="3" width="1%">{DETAILS_PIC_G}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN picturegdel -->
		<input type="checkbox" name="picturegdel" />
		<!-- END picturegdel -->
	</td>
	<td rowspan="3" width="1%">{DETAILS_PIC_H}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN picturehdel -->
		<input type="checkbox" name="picturehdel" />
		<!-- END picturehdel -->
	</td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD_MAP}:</td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_g"></td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD_MAP}:</td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_h"></td>
</tr>
</tbody>
<tr>
	<td class="row1">{L_MATCH_COMMENT}:</td>
	<td class="row3" colspan="5"><textarea class="post" rows="5" cols="50" name="details_comment">{DETAILS_COMMENT}</textarea></td>
</tr>
</table>


<table class="foot" cellspacing="2">
	<tr>
		<td align="right"><input type="submit" name="update" value="{L_SUBMIT}" class="button" /></td>
	</tr>
</table>
{S_HIDDEN_FIELDS}{S_HIDDEN_FIELDB}
</form>

<form action="{S_TEAM_ACTION}" method="post" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>{L_MATCH_TITLE} - {L_MATCH_LINEUP}</th>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
</table>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="30%">{L_MATCH_LINEUP_STATUS}:</td>
	<td class="row3" width="70%"><input type="radio" name="status" value="0" checked="checked" />&nbsp;{L_PLAYER}&nbsp;&nbsp;<input type="radio" name="status" value="1" />&nbsp;{L_REPLACE}</td>
</tr>
<tr>
	<td class="row1" valign="top">{L_MATCH_LINUP_ADD}:<br /><span class="small">{L_MATCH_LINUP_ADD_EX}</span></td>
	<td class="row3">{S_ADDUSERS}</td>
</tr>
<tr>
	<td class="row3">
		<table class="foot" cellspacing="2">
		<tr>
			<td align="right"><input type="submit" value="{L_SUBMIT}" class="button" /></td>
		</tr>
		</table>
	</td>
	<td class="row3">&nbsp;</td>
</tr>
</table>


{S_HIDDEN_FIELDS}{S_HIDDEN_FIELDC}
</form>
<!-- END match_details -->
