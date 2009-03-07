<form action="{S_TEAM_ACTION}" method="post" id="list" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>{L_MATCH_TITLE} - {L_TEAM_DETAILS}</th>
</tr>
<tr>
	<td>{L_TEAM_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" align="center" colspan="2">{L_MATCH_INFO}</td>
</tr>
<tr>
	<td>{L_RIVAL}</td>
	<td>{MATCH_RIVAL} - <a href="{U_MATCH_RIVAL_URL}">{MATCH_RIVAL_URL}</a></td>
</tr>
<tr>
	<td>{L_RIVAL_TAG}</td>
	<td>{MATCH_RIVAL_TAG}</td>
</tr>
<tr>
	<td>{L_MATCH_DETAILS}</td>
	<td>{MATCH_CATEGORIE}: {MATCH_TYPE} - {MATCH_LEAGUE_INFO}</td>
</tr>
<tr>
	<td>{L_SERVER}</td>
	<td>{SERVER} {SERVER_PW}</td>
</tr>
<!-- BEGIN hltv -->
<tr>
	<td>{L_HLTV}</td>
	<td>{HLTV} {HLTV_PW}</td>
</tr>
<!-- END hltv -->
</table>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" align="center" colspan="2">{L_USERNAME}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN members_row -->
<tr>
	<td class="{members_row.CLASS}" align="left">{members_row.USERNAME}</td>
	<td class="{members_row.CLASS}" align="left">{members_row.STATUS}</td>
	<td class="{members_row.CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{members_row.USER_ID}" /></td>
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

<form action="{S_TEAM_ACTION}" method="post" id="post" name="post" enctype="multipart/form-data">
<table class="edit" cellspacing="1">
<tr>
	<td class="row1"><b>{L_RIVAL_LINEUP}:</b></td>
	<td class="row3" colspan="5"><input class="post" type="text" size="50" name="details_lineup_rival" value="{DETAILS_LINEUP_RIVAL}" ></td>
</tr>
<tr>
	<td class="row1" width="160"><b>{L_MAP} {L_POINTS}:{L_POINTS}</b><br /><span class="small">{L_POINTS_EXPLAIN}</span></td>
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
		<!-- END picturebdel -->
	</td>
</tr>
<tr>
	<td class="row1"><b>{L_UPLOAD_MAP}:</b></td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_a"></td>
</tr>
<tr>
	<td class="row1"><b>{L_UPLOAD_MAP}:</b></td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_b"></td>
</tr>

<tr>
	<td class="row1"><b>{L_MAP} {L_POINTS}:{L_POINTS}</b><br /><span class="small">{L_POINTS_EXPLAIN}</span></td>
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
	<td class="row1"><b>{L_UPLOAD_MAP}:</b></td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_c"></td>
</tr>
<tr>
	<td class="row1"><b>{L_UPLOAD_MAP}:</b></td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_d"></td>
</tr>
<tr>
	<td class="row1"><b>{L_MORE_MAPS}:</b></td>
	<td class="row3" colspan="5"><a onClick="document.getElementById('mapc').style.display = '';" href="#" >more</a> <a onClick="document.getElementById('mapc').style.display = 'none';" href="#" >schlieﬂen</a></td>
</tr>

<tbody id="mapc" style="display:{MAPC};">
<tr>
	<td class="row1"><b>{L_MAP} {L_POINTS}:{L_POINTS}</b><br /><span class="small">{L_POINTS_EXPLAIN}</span></td>
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
	<td class="row1"><b>{L_UPLOAD_MAP}:</b></td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_e"></td>
</tr>
<tr>
	<td class="row1"><b>{L_UPLOAD_MAP}:</b></td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_f"></td>
</tr>
<tr>
	<td class="row1"><b>{L_MORE_MAPS}:</b></td>
	<td class="row3" colspan="5"><a onClick="document.getElementById('mapd').style.display = '';" href="#" >more</a> <a onClick="document.getElementById('mapd').style.display = 'none';" href="#" >schlieﬂen</a></td>
</tr>

</tbody>
<tbody id="mapd" style="display:{MAPD};">
<tr>
	<td class="row1"><b>{L_MAP} {L_POINTS}:{L_POINTS}</b><br /><span class="small">{L_POINTS_EXPLAIN}</span></td>
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
	<td class="row1"><b>{L_UPLOAD_MAP}:</b></td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_g"></td>
</tr>
<tr>
	<td class="row1"><b>{L_UPLOAD_MAP}:</b></td>
	<td class="row3"><input type="file" class="post" name="details_map_pic_h"></td>
</tr>
</tbody>
<tr>
	<td class="row1"><b>{L_UPLOAD_MAP}:</b></td>
	<td class="row3" colspan="5"><textarea class="post" rows="5" cols="50" name="details_comment">{DETAILS_COMMENT}</textarea></td>
</tr>
</table>
<input type="checkbox" name="picturebdel" />

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
		<td class="row1" width="40%"><b>{L_MATCH_STATUS}:</b></td>
		<td class="row3" width="60%">{S_RANK_SELECT}</td>
	</tr>
	<tr>
		<td class="row1" valign="top"><b>{L_MATCH_LINUP_ADD}:</b><br /><span class="small">{L_MATCH_LINUP_ADD_EX}</span></td>
		<td class="row3">{S_ADDUSERS}</td>
	</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td align="right"><input type="submit" value="{L_SUBMIT}" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}{S_HIDDEN_FIELDC}
</form>