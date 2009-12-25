<!-- BEGIN display -->
<form action="{S_MATCH_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MATCH_HEAD}</a></li>
	<li><a href="{S_MATCH_CREATE}">{L_MATCH_CREATE}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_MATCH_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2">{L_MATCH_DETAILS}</td>
	<td class="rowHead" align="center">{L_TRAINING}</td>
	<td class="rowHead" align="center" colspan="3">{L_SETTINGS}</td>
</tr>
<tr>
		<td class="rowHead" colspan="6">{L_UPCOMING}</td>
	</tr>
<!-- BEGIN match_row_new -->
<tr>
	<td class="{display.match_row_new.CLASS}" align="center" width="1%">{display.match_row_new.MATCH_GAME}</td>
	<td class="{display.match_row_new.CLASS}" align="left" width="100%"><span style="float: right;">{display.match_row_new.MATCH_DATE}</span>{display.match_row_new.MATCH_NAME}</td>
	<td class="{display.match_row_new.CLASS}" align="center"><a href="{display.match_row_new.U_TRAINING}">{display.match_row_new.TRAINING}</a></td>
	<td class="{display.match_row_new.CLASS}" align="center" width="1%"><a href="{display.match_row_new.U_DETAILS}">{L_DETAILS}</a></td>
	<td class="{display.match_row_new.CLASS}" align="center" width="1%"><a href="{display.match_row_new.U_UPDATE}">{L_UPDATE}</a></td>
	<td class="{display.match_row_new.CLASS}" align="center" width="1%"><a href="{display.match_row_new.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END match_row_new -->
<!-- BEGIN no_entry_new -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_new -->
<tr>
	<td class="rowHead" colspan="6">{L_EXPIRED}</td>
</tr>
<!-- BEGIN match_row_old -->
<tr>
	<td class="{display.match_row_old.CLASS}" align="center" width="1%">{display.match_row_old.MATCH_GAME}</td>
	<td class="{display.match_row_old.CLASS}" align="left" width="100%"><span style="float: right;">{display.match_row_old.MATCH_DATE}</span>{display.match_row_old.MATCH_NAME}</td>
	<td class="{display.match_row_old.CLASS}" align="center"> - </td>
	<td class="{display.match_row_old.CLASS}" align="center" width="1%"><a href="{display.match_row_old.U_DETAILS}">{L_DETAILS}</a></td>
	<td class="{display.match_row_old.CLASS}" align="center" width="1%"><a href="{display.match_row_old.U_UPDATE}">{L_UPDATE}</a></td>
	<td class="{display.match_row_old.CLASS}" align="center" width="1%"><a href="{display.match_row_old.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END match_row_old -->
<!-- BEGIN no_entry_old -->
<tr>
	<td class="row_noentry" align="center" colspan="6">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_old -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right">{S_TEAMS}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_MATCH_CREATE}"></td>
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
<form action="{S_MATCH_ACTION}" method="post" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_MATCH_ACTION}" method="post">{L_MATCH_TITLE}</a></li>
	<li id="active"><a href="#" id="current">{L_MATCH_NEW_EDIT}</a></li>
	<!-- BEGIN edit_match -->
	<li><a href="{S_MATCH_DETAILS}">{L_MATCH_DETAILS}</a></li>
	<!-- END edit_match -->
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
			<li id="active"><a href="#" id="current">{L_MATCH_INFO_A}</a></li>
		</ul>
		</div>
		<table class="edit">
		<tr>
			<td class="row1" width="23%"><label for="team_id">{L_MATCH_TEAM}: *</label></td>
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
			<td class="row1"><label for="match_league_url">{L_MATCH_LEAGUE_URL}:</label></td>
			<td class="row3"><input type="text" class="post" name="match_league_url" id="match_league_url" value="{MATCH_LEAGUE_URL}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="match_league_match">{L_LEAGUE_MATCH}:</label></td>
			<td class="row3"><input type="text" class="post" name="match_league_match" id="match_league_match" value="{MATCH_LEAGUE_MATCH}"></td>
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
			<td class="row1"><label for="match_public">{L_MATCH_PUBLIC}:</label></td>
			<td class="row3"><input type="radio" name="match_public" id="match_public" value="1" {S_PUBLIC_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="match_public" value="0" {S_PUBLIC_NO} />&nbsp;{L_NO}</td>
		</tr>
		<tr>
			<td class="row1"><label for="match_comments">{L_MATCH_COMMENTS}:</label></td>
			<td class="row3"><input type="radio" name="match_comments" id="match_comments" value="1" {S_COMMENT_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="match_comments" value="0" {S_COMMENT_NO} />&nbsp;{L_NO}</td>
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
			<li id="active"><a href="#" id="current">{L_MATCH_INFO_B} / {L_MATCH_INFO_C}</a></li>
		</ul>
		</div>
	</td>
</tr>
<tr>
	<td class="top">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1" width="46%"><label for="match_rival">{L_MATCH_RIVAL}: *</label></td>
			<td class="row3"><input type="text" class="post" name="match_rival" id="match_rival" value="{MATCH_RIVAL}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="match_rival_tag">{L_MATCH_RIVAL_TAG}: *</label></td>
			<td class="row3"><input type="text" class="post" name="match_rival_tag" id="match_rival_tag" value="{MATCH_RIVAL_TAG}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="match_rival_url">{L_MATCH_RIVAL_URL}:</label></td>
			<td class="row3"><input type="text" class="post" name="match_rival_url" id="match_rival_url" value="{MATCH_RIVAL_URL}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="match_rival_lineup">{L_MATCH_RIVAL_LINEUP}:</label></td>
			<td class="row3"><input type="text" class="post" name="match_rival_lineup" id="match_rival_lineup" value="{MATCH_RIVAL_LINEUP}"></td>
		</tr>
		</table>
	</td>
	<td class="top">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1" width="46%"><label for="server_ip">{L_MATCH_SERVER_IP}: *</label></td>
			<td class="row3"><input type="text" class="post" name="server_ip" id="server_ip" value="{SERVER_IP}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="server_pw">{L_MATCH_SERVER_PW}:</label></td>
			<td class="row3"><input type="text" class="post" name="server_pw" id="server_pw" value="{SERVER_PW}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="server_hltv">{L_MATCH_HLTV}:</label></td>
			<td class="row3"><input type="text" class="post" name="server_hltv" id="server_hltv" value="{SERVER_HLTV}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="server_hltv_pw">{L_MATCH_HLTV_PW}:</label></td>
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
			<li id="active"><a href="#" id="current">{L_MATCH_INFO_D}</a></li>
		</ul>
		</div>
		<table class="edit">
		<tr>
			<td class="row1 top" width="23%"><label for="match_comment">{L_MATCH_COMMENT}:</label></td>
			<td class="row3"><textarea class="post" rows="5" cols="50" name="match_comment" id="match_comment">{MATCH_COMMENT}</textarea></td>
		</tr>
		
		<tr>
			<td class="row1 top"><label for="match_report">{L_MATCH_REPORT}:</label></td>
			<td class="row3"><textarea class="post" rows="5" cols="50" name="match_report" id="match_report">{MATCH_REPORT}</textarea></td>
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
			<li id="active"><a href="#" id="current">{L_MATCH_INFO_E}</a></li>
		</ul>
		</div>
		
		<table class="edit">
		<tr>
			<td class="row1" width="23%">{L_TRAINING}:</td>
			<td class="row3"><input type="radio" name="train" value="1" onChange="document.getElementById('trainbox').style.display = '';">&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="train" value="0" onChange="document.getElementById('trainbox').style.display = 'none';" checked="checked">&nbsp;{L_NO}</td>
		</tr>
		<tbody  id="trainbox" style="display: none;">
		<tr>
			<td class="row1">{L_TRAINING_DATE}:</td>
			<td class="row3">{S_TDAY} . {S_TMONTH} . {S_TYEAR} - {S_THOUR} : {S_TMIN} - {S_TDURATION}</td>
		</tr>
		<tr>
			<td class="row1"><label for="training_maps">{L_TRAINING_MAPS}:</label></td>
			<td class="row3"><input type="text" class="post" name="training_maps" id="training_maps" size="60"></td>
		</tr>
		<tr>
			<td class="row1 top"><label for="training_comment">{L_TRAINING_TEXT}:</label></td>
			<td class="row3"><textarea class="post" rows="5" cols="50" name="training_comment" id="training_comment"></textarea></td>
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
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
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
	<li><a href="{S_MATCH_ACTION}" method="post">{L_MATCH_HEAD}</a></li>
	<li><a href="{S_MATCH_EDIT}">{L_MATCH_EDIT}</a></li>
	<li id="active"><a href="#" id="current">{L_MATCH_DETAILS}</a></li>
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
			<li id="active"><a href="#" id="current">{L_MATCH_LINEUP}</a></li>
			<li><a href="#" id="right">{L_LINEUP_PLAYER}</a></li>
		</ul>
		</div>
	</td>
</tr>
<tr>
	<form action="{S_ACTION}" method="post" name="post">
	<td width="50%" valign="top" class="row2">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="row1">{L_MATCH_LINEUP_STATUS}:</td>
			<td class="row3"><input type="radio" name="status" value="0" checked="checked">&nbsp;{L_PLAYER}&nbsp;&nbsp;<input type="radio" name="status" value="1">&nbsp;{L_REPLACE}</td>
		</tr>
		<tr>
			<td class="row1 top" width="46%"><label for="members" title="{L_MATCH_LINUP_ADD_EX}">{L_MATCH_LINUP_ADD}:</label></td>
			<td class="row3">{S_ADDUSERS}</td>
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
		<form action="{S_ACTION}" method="post" id="list" name="post">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table class="normal" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN members_row -->
				<tr class="wihte">
					<label for="{match_details.members_row.USER_ID}"><td align="center">{match_details.members_row.USERNAME}</td>
					<td align="center">{match_details.members_row.STATUS}</td></label>
					<td align="right"><input type="checkbox" name="members[]" id="{match_details.members_row.USER_ID}" value="{match_details.members_row.USER_ID}"></td>
				</tr>
				<!-- END members_row -->
				<!-- BEGIN no_members_row -->
				<tr>
					<td align="center" colspan="3"><b>{NO_MEMBER}</b></td>
				</tr>
				<!-- END no_members_row -->
				</table>
			</td>
		</tr>		
		</table>
		
		<table class="footer" border="0" cellspacing="1" cellpadding="2">
		<tr>
			<td colspan="2" align="right">{S_ACTION_OPTIONS} <input type="submit" class="button2" value="{L_SUBMIT}"></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'member', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
		</tr>
		</table>
		{S_FIELDS}
		</form>
	</td>
</tr>
</table>


<!-- BEGIN maps -->
<form action="{S_ACTION}" method="post" id="post" name="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_DETAILS_MAPS}</a></li>
</ul>
</div>
<table class="edit" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN info_row -->
<tr>
	<td class="row1" width="25%"><b>{L_MAP}:</b></td>
	<td class="row3"><input type="text" class="post" name="map_name[]" value="{match_details.maps.info_row.INFO_MAP_NAME}"></td>
	<td class="row2" rowspan="3">{match_details.maps.info_row.INFO_PIC_URL}</td>
	<td class="row2" rowspan="3"><input type="hidden" name="id[]" value="{match_details.maps.info_row.INFO_MAP_ID}"></td>
</tr>
<tr>
	<td class="row1"><b>{L_POINTS}:</b></td>
	<td class="row3"><input type="text" class="post" name="map_points_home[]" value="{match_details.maps.info_row.INFO_MAP_HOME}" size="5">&nbsp;&nbsp;:&nbsp;&nbsp;<input type="text" class="post" name="map_points_rival[]" value="{match_details.maps.info_row.INFO_MAP_RIVAL}" size="5"></td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD_MAP}:</td>
	<td class="row3"><input type="file" class="post" name="ufile[]2" id="ufile[]2" size="12" /></td>
</tr>
<!-- END info_row -->
<tr>
	<td colspan="4" class="row2">&nbsp;</td>
	</tr>
<tr>
	<td colspan="4" class="row2"><input type="submit" class="button2" value="{L_SUBMIT}"></td>
</tr>
</table>


<input type="hidden" name="mode" value="_update_map" />
{S_FIELDS}
</form>
<!-- END maps -->

<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_DETAILS_UPLOAD}</a></li>
</ul>
</div>
<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 top" width="23%">{L_MAP}&nbsp;/&nbsp;{L_POINTS}&nbsp;:&nbsp;{L_POINTS}<br />{L_UPLOAD_MAP}:</td>
	<td class="row3"><div><div><input type="text" class="post" name="map_name[]" id="map_name[]" size="12">&nbsp;/&nbsp;<input type="text" class="post" name="map_points_home[]" id="map_points_home[]" size="2">&nbsp;:&nbsp;<input type="text" class="post" name="map_points_rival[]" id="map_points_rival[]" size="2"><br /><input type="file" class="post" name="ufile[]" id="ufile[]" size="12"> <input class="button2" type="button" value="{L_MORE}" onclick="clone(this)"><br /><br /></div></div></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="{L_UPLOAD}" class="button2"></td>
</tr>
</table>
<input type="hidden" name="mode" value="_details_upload" />
{S_FIELDS}
</form>
<!-- END match_details -->