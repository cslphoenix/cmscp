<!-- BEGIN display -->
<form method="post" action="{S_MATCH_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_MATCH_HEAD}</a></li>
				<li><a href="{S_MATCH_ADD}">{L_MATCH_CREATE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_MATCH_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_MATCH_DETAILS}</td>
	<td class="rowHead" align="center">{L_TRAINING}</td>
	<td class="rowHead" align="right" colspan="3">{L_SETTINGS}</td>
</tr>
<tr>
		<td class="rowHead" colspan="7">{L_UPCOMING}</td>
	</tr>
<!-- BEGIN match_row_new -->
<tr>
	<td class="{display.match_row_new.CLASS}" align="center" width="1%">{display.match_row_new.MATCH_GAME}</td>
	<td class="{display.match_row_new.CLASS}" align="left" width="100%"><span style="float: right;">{display.match_row_new.MATCH_DATE}</span>{display.match_row_new.MATCH_NAME}</td>
	<td class="{display.match_row_new.CLASS}" align="center"><a href="{display.match_row_new.U_TRAINING}">{display.match_row_new.TRAINING}</a></td>
	<td class="{display.match_row_new.CLASS}" align="center" width="1%"><a href="{display.match_row_new.U_EDIT}">{L_SETTING}</a></td>
	<td class="{display.match_row_new.CLASS}" align="center" width="1%"><a href="{display.match_row_new.U_DETAILS}">{L_MATCH_DETAILS}</a></td>
	<td class="{display.match_row_new.CLASS}" align="center" width="1%"><a href="{display.match_row_new.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END match_row_new -->
<!-- BEGIN no_entry_new -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_new -->
<tr>
	<td class="rowHead" colspan="7">{L_EXPIRED}</td>
</tr>
<!-- BEGIN match_row_old -->
<tr>
	<td class="{display.match_row_old.CLASS}" align="center" width="1%">{display.match_row_old.MATCH_GAME}</td>
	<td class="{display.match_row_old.CLASS}" align="left" width="100%"><span style="float: right;">{display.match_row_old.MATCH_DATE}</span>{display.match_row_old.MATCH_NAME}</td>
	<td class="{display.match_row_old.CLASS}" align="center"> - </td>
	<td class="{display.match_row_old.CLASS}" align="center" width="1%"><a href="{display.match_row_old.U_EDIT}">{L_SETTING}</a></td>
	<td class="{display.match_row_old.CLASS}" align="center" width="1%"><a href="{display.match_row_old.U_DETAILS}">{L_MATCH_DETAILS}</a></td>
	<td class="{display.match_row_old.CLASS}" align="center" width="1%"><a href="{display.match_row_old.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END match_row_old -->
<!-- BEGIN no_entry_old -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_old -->
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
	<td><input type="hidden" name="mode" value="match_add"><input class="button" type="submit" value="{L_MATCH_CREATE}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN match_edit -->
<form action="{S_MATCH_ACTION}" method="post" name="form" onSubmit="javascript:return checkForm()">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_MATCH_ACTION}">{L_MATCH_TITLE}</a></li>
				<li id="active"><a href="#" id="current">{L_MATCH_NEW_EDIT}</a></li>
				<!-- BEGIN edit_match -->
				<li><a href="{S_MATCH_DETAILS}">{L_MATCH_DETAILS}</a></li>
				<!-- END edit_match -->
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>
<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="edit" cellspacing="1">
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
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}
		<!-- BEGIN reset_match -->
		<input type="checkbox" name="listdel" /> {L_RESET_LIST}
		<!-- END reset_match -->
	</td>
</tr>
<tr>
	<td class="row1">{L_MATCH_PUBLIC}:</td>
	<td class="row3"><input type="radio" name="match_public" value="1" {S_CHECKED_PUB_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="match_public" value="0" {S_CHECKED_PUB_NO} />&nbsp;{L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_MATCH_COMMENTS}:</td>
	<td class="row3"><input type="radio" name="match_comments" value="1" {S_CHECKED_COM_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="match_comments" value="0" {S_CHECKED_COM_NO} />&nbsp;{L_NO} </td>
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
	<td class="row3"><input type="radio" name="train" value="1" onChange="document.getElementById('trainbox').style.display = '';" />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="train" value="0" onChange="document.getElementById('trainbox').style.display = 'none';" checked="checked" />&nbsp;{L_NO} </td>
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
<!-- END match_edit -->

<!-- BEGIN match_details -->
<form action="{S_TEAM_ACTION}" method="post" id="list" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_MATCH_ACTION}">{L_MATCH_TITLE}</a></li>
				<li><a href="{S_MATCH_EDIT}">{L_MATCH_NEW_EDIT}</a></li>
				<li id="active"><a href="#" id="current">{L_MATCH_DETAILS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="normal" cellspacing="0">
<tr>
	<td valign="top">
		<table class="edit" cellspacing="1">
		<tr>
			<th colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{L_MATCH_INFO}</a></li>
					</ul>
				</div>
			</th>
		</tr>
		<tr>
			<td class="row3">{L_RIVAL}</td>
			<td class="row3">{MATCH_RIVAL} - <a href="{U_MATCH_RIVAL_URL}">{MATCH_RIVAL_URL}</a></td>
		</tr>
		<tr>
			<td class="row3">{L_RIVAL_TAG}</td>
			<td class="row3">{MATCH_RIVAL_TAG}</td>
		</tr>
		<tr>
			<td class="row3">{L_MATCH_DETAILS}</td>
			<td class="row3">{MATCH_CATEGORIE}: {MATCH_TYPE} - {MATCH_LEAGUE_INFO}</td>
		</tr>
		<tr>
			<td class="row3">{L_SERVER}</td>
			<td class="row3">{SERVER} {SERVER_PW}</td>
		</tr>
		<!-- BEGIN hltv -->
		<tr>
			<td class="row3">{L_HLTV}</td>
			<td class="row3">{HLTV} {HLTV_PW}</td>
		</tr>
		<!-- END hltv -->
		</table>
	</td>
	<td>&nbsp;</td>
	<td valign="top">
		<table class="edit" cellspacing="1">
		<tr>
			<th colspan="3">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{L_LINEUP_PLAYER}</a></li>
					</ul>
				</div>
			</th>
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
			<td class="noentry" align="center" colspan="3">{NO_MEMBER}</td>
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
	<th colspan="6">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DETAILS_MAPS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="160">{L_MAP} {L_POINTS}:{L_POINTS}<br><span class="small">{L_POINTS_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" name="details_mapa" value="{DETAILS_MAPA}" > <input class="post" type="text" name="details_mapa_clan" value="{DETAILS_MAPA_CLAN}" size="2" > : <input class="post" type="text" name="details_mapa_rival" value="{DETAILS_MAPA_RIVAL}" size="2" >
	<td rowspan="3">{DETAILS_PIC_A}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN pictureadel -->
		<input type="checkbox" name="pictureadel" />
		<!-- END pictureadel -->
	</td>
	<td rowspan="3">{DETAILS_PIC_B}</td>
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
	<td class="row1">{L_MAP} {L_POINTS}:{L_POINTS}<br><span class="small">{L_POINTS_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" name="details_mapb" value="{DETAILS_MAPB}" > <input class="post" type="text" name="details_mapb_clan" value="{DETAILS_MAPB_CLAN}" size="2" > : <input class="post" type="text" name="details_mapb_rival" value="{DETAILS_MAPB_RIVAL}" size="2" >
	<td rowspan="3">{DETAILS_PIC_C}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN picturecdel -->
		<input type="checkbox" name="picturecdel" />
		<!-- END picturecdel -->
	</td>
	<td rowspan="3">{DETAILS_PIC_D}</td>
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
	<td class="row1">{L_MAP} {L_POINTS}:{L_POINTS}<br><span class="small">{L_POINTS_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" name="details_mapc" value="{DETAILS_MAPC}" > <input class="post" type="text" name="details_mapc_clan" value="{DETAILS_MAPC_CLAN}" size="2" > : <input class="post" type="text" name="details_mapc_rival" value="{DETAILS_MAPC_RIVAL}" size="2" >
	<td rowspan="3">{DETAILS_PIC_E}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN pictureedel -->
		<input type="checkbox" name="pictureedel" value="pic_e" />
		<!-- END pictureedel -->
	</td>
	<td rowspan="3">{DETAILS_PIC_F}</td>
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
	<td class="row1">{L_MAP} {L_POINTS}:{L_POINTS}<br><span class="small">{L_POINTS_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" name="details_mapd" value="{DETAILS_MAPD}" > <input class="post" type="text" name="details_mapd_clan" value="{DETAILS_MAPD_CLAN}" size="2" > : <input class="post" type="text" name="details_mapd_rival" value="{DETAILS_MAPD_RIVAL}" size="2" >
	<td rowspan="3">{DETAILS_PIC_G}</td>
	<td rowspan="3" width="1%">
		<!-- BEGIN picturegdel -->
		<input type="checkbox" name="picturegdel" />
		<!-- END picturegdel -->
	</td>
	<td rowspan="3">{DETAILS_PIC_H}</td>
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
<tr>
	<td class="row1">{L_RIVAL_LINEUP}</td>
	<td class="row3" colspan="5"><input class="post" type="text" size="50" name="details_lineup_rival" value="{DETAILS_LINEUP_RIVAL}" ></td>
</tr>
<tr>
	<td align="right" colspan="6"><input type="submit" name="update" value="{L_SUBMIT}" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}{S_HIDDEN_FIELDB}
</form>

<form action="{S_TEAM_ACTION}" method="post" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_MATCH_LINEUP}</a></li>
			</ul>
		</div>
	</th>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="30%">{L_MATCH_LINEUP_STATUS}:</td>
	<td class="row3" width="70%"><input type="radio" name="status" value="0" checked="checked" />&nbsp;{L_PLAYER}&nbsp;&nbsp;<input type="radio" name="status" value="1" />&nbsp;{L_REPLACE}</td>
</tr>
<tr>
	<td class="row1" valign="top">{L_MATCH_LINUP_ADD}:<br><span class="small">{L_MATCH_LINUP_ADD_EX}</span></td>
	<td class="row3">{S_ADDUSERS}&nbsp;<input type="submit" value="{L_SUBMIT}" class="button" /></td>
</tr>
</table>


{S_HIDDEN_FIELDS}{S_HIDDEN_FIELDC}
</form>
<!-- END match_details -->
