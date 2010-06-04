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
	<td class="rowHead" colspan="2" width="100%">{L_UPCOMING}</td>
	<td class="rowHead" align="center">{L_TRAINING}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_match_new -->
<tr>
	<td class="row_class1" align="center">{display.row_match_new.GAME}</td>
	<td class="row_class1" align="left" width="100%"><span style="float: right;">{display.row_match_new.DATE}</span>{display.row_match_new.NAME}</td>
	<td class="row_class1" align="center">&nbsp;<a href="{display.row_match_new.U_TRAINING}">{display.row_match_new.L_TRAINING}</a>&nbsp;</td>
	<td class="row_class2" align="center"><a href="{display.row_match_new.U_DETAILS}">{I_DETAILS}</a> <a href="{display.row_match_new.U_UPDATE}">{I_UPDATE}</a> <a href="{display.row_match_new.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END row_match_new -->
<!-- BEGIN no_entry_new -->
<tr>
	<td class="row_noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_new -->
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_EXPIRED}</td>
	<td class="rowHead" align="center">{L_TRAINING}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_match_old -->
<tr>
	<td class="row_class1">{display.row_match_old.GAME}</td>
	<td class="row_class1" align="left" width="100%"><span style="float: right;">{display.row_match_old.DATE}</span>{display.row_match_old.NAME}</td>
	<td class="row_class1" align="center"> - </td>
	<td class="row_class2" align="center"><a href="{display.row_match_old.U_DETAILS}">{I_DETAILS}</a> <a href="{display.row_match_old.U_UPDATE}">{I_UPDATE}</a> <a href="{display.row_match_old.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END row_match_old -->
<!-- BEGIN no_entry_old -->
<tr>
	<td class="row_noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_old -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right">{S_TEAMS}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN match_edit -->
<form action="{S_ACTION}" method="post" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_TITLE}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
	<!-- BEGIN edit_match -->
	<li><a href="{S_DETAILS}">{L_DETAILS}</a></li>
	<!-- END edit_match -->
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_INFO_A}</a></li>
		</ul>
		</div>
		<table class="edit">
		<tr>
			<td class="row1" width="23%"><label for="team_id">{L_TEAM}: *</label></td>
			<td class="row3">{S_TEAMS}</td>
		</tr>
		<tr>
			<td class="row1"><label for="match_type">{L_TYPE}: *</label></td>
			<td class="row3">{S_TYPE}</td>
		</tr>
		<tr>
			<td class="row1"><label for="match_categorie">{L_CATEGORIE}: *</label></td>
			<td class="row3">{S_CATEGORIE}</td>
		</tr>
		<tr>
			<td class="row1"><label for="match_league">{L_LEAGUE}: *</label></td>
			<td class="row3">{S_LEAGUE}</td>
		</tr>
		<tr>
			<td class="row1"><label for="match_league_url">{L_LEAGUE_URL}:</label></td>
			<td class="row3"><input type="text" class="post" name="match_league_url" id="match_league_url" value="{MATCH_LEAGUE_URL}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="match_league_match">{L_LEAGUE_MATCH}:</label></td>
			<td class="row3"><input type="text" class="post" name="match_league_match" id="match_league_match" value="{MATCH_LEAGUE_MATCH}"></td>
		</tr>
		<tr>
			<td class="row1"><label>{L_DATE}:</label></td>
			<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}
				<!-- BEGIN reset_match -->
				<input type="checkbox" name="listdel" /> {L_RESET_LIST}
				<!-- END reset_match -->
			</td>
		</tr>
		<tr>
			<td class="row1"><label for="match_public">{L_PUBLIC}:</label></td>
			<td class="row3"><label><input type="radio" name="match_public" id="match_public" value="1" {S_PUBLIC_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="match_public" value="0" {S_PUBLIC_NO} />&nbsp;{L_NO}</label></td>
		</tr>
		<tr>
			<td class="row1"><label for="match_comments">{L_COMMENTS}:</label></td>
			<td class="row3"><label><input type="radio" name="match_comments" id="match_comments" value="1" {S_COMMENT_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="match_comments" value="0" {S_COMMENT_NO} />&nbsp;{L_NO}</label></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_INFO_B} / {L_INFO_C}</a></li>
		</ul>
		</div>
	</td>
</tr>
<tr>
	<td class="top">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1" width="46%"><label for="match_rival">{L_RIVAL}: *</label></td>
			<td class="row3"><input type="text" class="post" name="match_rival" id="match_rival" value="{RIVAL}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="match_rival_tag">{L_RIVAL_TAG}: *</label></td>
			<td class="row3"><input type="text" class="post" name="match_rival_tag" id="match_rival_tag" value="{RIVAL_TAG}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="match_rival_url">{L_RIVAL_URL}:</label></td>
			<td class="row3"><input type="text" class="post" name="match_rival_url" id="match_rival_url" value="{RIVAL_URL}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="match_rival_lineup">{L_RIVAL_LINEUP}:</label></td>
			<td class="row3"><input type="text" class="post" name="match_rival_lineup" id="match_rival_lineup" value="{RIVAL_LINEUP}"></td>
		</tr>
		</table>
	</td>
	<td class="top">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1" width="46%"><label for="server_ip">{L_SERVER_IP}: *</label></td>
			<td class="row3"><input type="text" class="post" name="server_ip" id="server_ip" value="{SERVER_IP}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="server_pw">{L_SERVER_PW}:</label></td>
			<td class="row3"><input type="text" class="post" name="server_pw" id="server_pw" value="{SERVER_PW}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="server_hltv">{L_HLTV}:</label></td>
			<td class="row3"><input type="text" class="post" name="server_hltv" id="server_hltv" value="{SERVER_HLTV}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="server_hltv_pw">{L_HLTV_PW}:</label></td>
			<td class="row3"><input type="text" class="post" name="server_hltv_pw" id="server_hltv_pw" value="{SERVER_HLTV_PW}"></td>
		</tr>
		</table>
	</td>
	
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_INFO_D}</a></li>
		</ul>
		</div>
		<table class="edit">
		<tr>
			<td class="row1 top"><label for="match_report">{L_REPORT}:</label></td>
			<td class="row3"><textarea class="post" rows="5" cols="50" name="match_report" id="match_report">{MATCH_REPORT}</textarea></td>
		</tr>
		<tr>
			<td class="row1 top" width="23%"><label for="match_comment">{L_COMMENT}:</label></td>
			<td class="row3"><textarea class="post" rows="5" cols="50" name="match_comment" id="match_comment">{MATCH_COMMENT}</textarea></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- BEGIN new_match -->
<tr>
	<td colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_INFO_E}</a></li>
		</ul>
		</div>
		
		<table class="edit">
		<tr>
			<td class="row1" width="23%"><label for="training">{L_TRAINING}:</label></td>
			<td class="row3"><label><input type="radio" name="training" id="training" value="1" onChange="document.getElementById('trainbox').style.display = '';" {S_TRAINING_YES} >&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="training" value="0" onChange="document.getElementById('trainbox').style.display = 'none';" {S_TRAINING_NO} >&nbsp;{L_NO}</label></td>
		</tr>
		<tbody  id="trainbox" style="display:{S_TRAINING_NONE};">
		<tr>
			<td class="row1"><label>{L_TRAINING_DATE}:</label></td>
			<td class="row3">{S_TDAY} . {S_TMONTH} . {S_TYEAR} - {S_THOUR} : {S_TMIN} - {S_TDURATION}</td>
		</tr>
		<tr>
			<td class="row1"><label for="training_maps">{L_TRAINING_MAPS}:</label></td>
			<td class="row3"><input type="text" class="post" name="training_maps" id="training_maps" size="60" value="{TRAINING_MAPS}"></td>
		</tr>
		<tr>
			<td class="row1 top"><label for="training_text">{L_TRAINING_TEXT}:</label></td>
			<td class="row3"><textarea class="post" rows="5" cols="50" name="training_text" id="training_text">{TRAINING_TEXT}</textarea></td>
		</tr>
		</tbody>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- END new_match -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END match_edit -->

<!-- BEGIN match_details -->
<script type="text/JavaScript">
// <![CDATA[
function clone(objButton)
{
	if ( objButton.parentNode )
	{
		tmpNode		= objButton.parentNode.cloneNode(true);
		target		= objButton.parentNode.parentNode;
		arrInput	= tmpNode.getElementsByTagName("input");
		
		for ( var i = 0; i < arrInput.length; i++ )
		{
			if ( arrInput[i].type=='text' )
			{
				arrInput[i].value='';
			}
			
			if ( arrInput[i].type=='file' )
			{
				arrInput[i].value='';
			}
		}
		
		target.appendChild(tmpNode);
		objButton.value="{L_REMOVE}";
		objButton.onclick=new Function('f1','this.parentNode.parentNode.removeChild(this.parentNode)');
	}
}
// ]]>
</script>
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_EDIT}">{L_EDIT}</a></li>
	<li id="active"><a href="#" id="current">{L_DETAILS}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_LINEUP}</a></li>
			<li><a href="#" id="right">{L_LINEUP_PLAYER}</a></li>
		</ul>
		</div>
	</td>
</tr>
<tr>
	<form action="{S_ACTION}" method="post" name="post">
	<td width="50%" class="row2 top">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1" width="46%"><label for="player_status">{L_LINEUP_STATUS}:</label></td>
			<td class="row3"><label><input type="radio" name="player_status" value="0" id="player_status" checked="checked">&nbsp;{L_PLAYER}</label>&nbsp;&nbsp;<label><input type="radio" name="player_status" value="1">&nbsp;{L_REPLACE}</label></td>
		</tr>
		<tr>
			<td class="row1 top"><label for="members" title="{L_LINUP_ADD_EX}">{L_LINEUP_ADD}:</label></td>
			<td class="row3">{S_SELECT}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}"></td>
		</tr>
		</table>
		<input type="hidden" name="mode" value="_details_user_add" />
		{S_FIELDS}
	</td>
	</form>
	<td width="50%" class="row2 top">
		<form action="{S_ACTION}" method="post" id="list">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table class="normal" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center">{L_LINEUP_USERNAME}</td>
					<td align="center">{L_LINEUP_STATUS}</td>
					<td align="center">{}</td>
				</tr>
				<!-- BEGIN row_members -->
				<tr class="wihte">
					<td align="center">{match_details.row_members.USERNAME}</td>
					<td align="center">{match_details.row_members.STATUS}</td>
					<td align="right"><input type="checkbox" name="members[]" value="{match_details.row_members.USER_ID}"></td>
				</tr>
				<!-- END row_members -->
				<!-- BEGIN no_row_members -->
				<tr>
					<td class="row_noentry1" align="center" colspan="3">{NO_MEMBER}</td>
				</tr>
				<!-- END no_row_members -->
				</table>
			</td>
		</tr>		
		</table>
		<!-- BEGIN option -->
		<table class="footer" border="0" cellspacing="1" cellpadding="2">
		<tr>
			<td colspan="2" align="right">{S_OPTIONS}<input type="submit" class="button2" value="{L_SUBMIT}"></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><a class="small" href="#" onclick="marklist('list', 'member', true); return false;">&raquo;&nbsp;{L_MARK_ALL}</a>&nbsp;<a class="small" href="#" onclick="marklist('list', 'member', false); return false;">&raquo;&nbsp;{L_MARK_DEALL}</a></td>
		</tr>
		</table>
		<!-- END option -->
		{S_FIELDS}
		</form>
	</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX_1}</div>
<!-- BEGIN maps -->
<form action="{S_ACTION}" method="post" id="delete" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MAPS_OVERVIEW}</a></li>
	<li><a href="#" id="right" onclick="marklist('delete', 'delete', false); return false;">&raquo;&nbsp;{L_MARK_DEALL}</a></li>
	<li><a href="#" id="right" onclick="marklist('delete', 'delete', true); return false;">&raquo;&nbsp;{L_MARK_ALL}</a></li>
</ul>
</div>
<table class="edit" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN info_row -->
<tr class="wihte">
	<td>
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1" width="28%"><input type="hidden" name="id[]" value="{match_details.maps.info_row.INFO_MAP_ID}">{L_DETAIL_MAP}:</td>
			<td class="row3" width="28%"><input type="text" class="post" name="map_name[]" value="{match_details.maps.info_row.INFO_MAP_NAME}"></td>
			<td class="row3" rowspan="3" width="43%" align="center">
				{match_details.maps.info_row.INFO_PIC_URL}
				<!-- BEGIN delete -->
				<br /><input type="checkbox" name="mappic_delete[]" value="{match_details.maps.info_row.INFO_MAP_ID}">&nbsp;{L_IMAGE_DELETE}
				<!-- END delete -->
			</td>
			<td class="row3" rowspan="3" width="43%" align="center" nowrap="nowrap">{match_details.maps.info_row.MOVE_UP}{match_details.maps.info_row.MOVE_DOWN}</td>
			<td class="row3" rowspan="3" width="1%"><input type="checkbox" name="delete[]" title="{L_DELETE}" value="{match_details.maps.info_row.INFO_MAP_ID}"></td>
		</tr>
		<tr>
			<td class="row1">{L_DETAIL_POINTS}:</td>
			<td class="row3"><input type="text" class="post" name="map_points_home[]" value="{match_details.maps.info_row.INFO_MAP_HOME}" size="2">&nbsp;&nbsp;:&nbsp;&nbsp;<input type="text" class="post" name="map_points_rival[]" value="{match_details.maps.info_row.INFO_MAP_RIVAL}" size="2"></td>
		</tr>
		<tr>
			<td class="row1">{L_DETAIL_MAPPIC}:</td>
			<td class="row3" width="1"><input type="file" class="post" name="ufile[]2" id="ufile[]2" size="12" /></td>
		</tr>
		</table>
	</td>
</tr>
<!-- END info_row -->
<tr>
	<td colspan="4" class="row2"></td>
	</tr>
<tr>
	<td colspan="4" class="row2"><input type="submit" name="_details_update" class="button2" value="{L_SUBMIT}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END maps -->

<br /><div align="center">{ERROR_BOX_2}</div>
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MAPS}</a></li>
</ul>
</div>
<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 top" width="28%">{L_DETAIL_MAP}&nbsp;/&nbsp;{L_DETAIL_POINTS}&nbsp;:&nbsp;{L_DETAIL_POINTS}:</td>
	<td class="row3"><div><div><input type="button" class="button2" value="{L_MORE}" onclick="clone(this)">&nbsp;<input type="text" class="post" name="map_name[]" id="map_name[]" size="12">&nbsp;&nbsp;<input type="text" class="post" name="map_points_home[]" id="map_points_home[]" size="2">&nbsp;&nbsp;<input type="text" class="post" name="map_points_rival[]" id="map_points_rival[]" size="2"></div></div></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="_details_map" value="{L_SUBMIT}" class="button2"></td>
</tr>
</table>
{S_FIELDS}
</form>

<br /><div align="center">{ERROR_BOX_3}</div>
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MAPS_PIC}</a></li>
</ul>
</div>
<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 top" style="padding-top:10px;" width="28%">{L_DETAIL_MAP}&nbsp;/&nbsp;{L_DETAIL_POINTS}&nbsp;:&nbsp;{L_DETAIL_POINTS}&nbsp;/&nbsp;{L_DETAIL_MAPPIC}:</td>
	<td class="row3"><div><div><input type="button" class="button2" value="{L_MORE}" onclick="clone(this)">&nbsp;<input type="text" class="post" name="map_name[]" id="map_name[]" size="12">&nbsp;&nbsp;<input type="text" class="post" name="map_points_home[]" id="map_points_home[]" size="2">&nbsp;&nbsp;<input type="text" class="post" name="map_points_rival[]" id="map_points_rival[]" size="2">&nbsp;&nbsp;<input type="file" class="post" name="ufile[]" id="ufile[]" size="12"></div></div></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="_details_mappic" class="button2" value="{L_UPLOAD}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END match_details -->