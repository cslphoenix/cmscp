<!-- BEGIN display -->
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
<form action="{S_ACTION}" method="post">
<ul id="navinfo"><li>{L_EXPLAIN}<br /><a href="{U_SYNC}">{L_SYNC}</a></li></ul>
<ul id="navopts"><li>{L_SORT}: {S_SORT}</li></ul>
</form>

<br />

<table class="rows">
<tr>
	<th>{L_UPCOMING}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN new_row -->
<tr>
	<td><span class="right">{display.new_row.DATE}</span>{display.new_row.GAME} {display.new_row.NAME}</td>
	<td>{display.new_row.TRAIN}{display.new_row.DETAIL}{display.new_row.UPDATE}{display.new_row.DELETE}</td>
</tr>
<!-- END new_row -->
<!-- BEGIN new_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END new_empty -->
</table>

<form action="{S_ACTION}" method="post">
<table class="lfooter">
<tr>
	<td>{S_TEAM}</td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>

<br />

<table class="rows">
<tr>
	<th>{L_EXPIRED}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN old_row -->
<tr>
	<td><span style="float: right;">{display.old_row.DATE}</span>{display.old_row.GAME} {display.old_row.NAME}</td>
	<td>{display.old_row.DETAIL} {display.old_row.UPDATE} {display.old_row.DELETE}</td>
</tr>
<!-- END old_row -->
<!-- BEGIN old_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END old_empty -->
</table>


<table class="footer">
<tr>
	<td></td>
	<td></td>
	<td></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
<!-- END display -->

<!-- BEGIN input -->
{AJAX}
<script type="text/JavaScript">
<!-- BEGIN ajax -->
function look_{input.ajax.NAME}(input_{input.ajax.NAME})
{
	if ( input_{input.ajax.NAME}.length == 0 )
	{
		$('#{input.ajax.NAME}').hide();
	}
	else
	{
		$.post("./ajax/{input.ajax.FILE}", {{input.ajax.NAME}: ""+input_{input.ajax.NAME}+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#{input.ajax.NAME}').show();
					$('#auto_{input.ajax.NAME}').html(data);
				}
			}
		);
	}
}
function set_{input.ajax.NAME}(thisValue)
{
	$('#input_{input.ajax.NAME}').val(thisValue);
	setTimeout("$('#{input.ajax.NAME}').hide();", 200);
}
<!-- END ajax -->
function set_infos(id,text)
{
	var obj = document.getElementById(id).value = text;
}


</script>
<form action="{S_ACTION}" method="post" name="form">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_TITLE}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
	<!-- BEGIN update -->
	<li><a href="{S_DETAIL}">{L_DETAIL}</a></li>
	<!-- END update -->
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>


<!-- BEGIN row -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}" {input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
<!-- END row -->


<!--
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_STANDARD}</a></li></ul>

<table class="update" id="settings" style="display:">
<tr>
	<td class="row1r"><label for="team_id">{L_TEAM}:</label></td>
	<td class="row2">{S_TEAM}</td>
</tr>
<tr>
	<td class="row1r"><label for="match_type">{L_TYPE}:</label></td>
	<td class="row2">{S_TYPE}
		<!-- BEGIN type ->
		<label><input type="radio" name="match_type" value="{_input._type.TYPE}" {_input._type.MARK}/>&nbsp;{_input._type.NAME}</label><br />
		<!-- END type ->
	</td>
</tr>
<tr>
	<td class="row1r"><label for="match_war">{L_WAR}:</label></td>
	<td class="row2">{S_WAR}
		<!-- BEGIN war ->
		<label><input type="radio" name="match_war" value="{_input._war.TYPE}" {_input._war.MARK} />&nbsp;{_input._war.NAME}</label><br />
		<!-- END war ->
	</td>
</tr>
<tr>
	<td class="row1r"><label for="match_league">{L_LEAGUE}:</label></td>
	<td class="row2">{S_LEAGUE}
		<!-- BEGIN league ->
		<label><input type="radio" name="match_league" value="{_input._league.TYPE}" {_input._league.MARK} onclick="{_input._league.CLICK}" />&nbsp;{_input._league.NAME}</label><br />
		<!-- END league ->
	</td>
</tr>
<tr>
	<td class="row1"><label for="match_league_match">{L_LEAGUE_MATCH}:</label></td>
	<td class="row2"><input type="text" name="match_league_match" id="match_league_match" value="{LEAGUE_MATCH}"></td>
</tr>
<tr>
	<td class="row1"><label>{L_DATE}:</label></td>
	<td class="row2">{S_DAY}.{S_MONTH}.{S_YEAR} - {S_HOUR}:{S_MIN}</td>
</tr>
<tr>
	<td class="row1"><label for="match_public">{L_PUBLIC}:</label></td>
	<td class="row2"><label><input type="radio" name="match_public" id="match_public" value="1" {S_PUBLIC_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="match_public" value="0" {S_PUBLIC_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="match_comments">{L_COMMENTS}:</label></td>
	<td class="row2"><label><input type="radio" name="match_comments" id="match_comments" value="1" {S_COMMENT_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="match_comments" value="0" {S_COMMENT_NO} />&nbsp;{L_NO}</label></td>
</tr>
<!-- BEGIN reset ->
<tr>
	<td class="row1"><label for="listdel">{L_RESET_LIST}:</label></td>
	<td class="row2"><label><input type="radio" name="listdel" id="listdel" value="1" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="listdel" value="0" checked="checked" />&nbsp;{L_NO}</label></td>
</tr>
<!-- END reset ->
</table>

<br />

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_RIVAL} / {L_SERVER}</a></li></ul>

<table class="update">
<tr>
	<td class="row1r"><label for="input_rival">{L_RIVAL_NAME}:</label></td>
	<td class="row2"><input type="text" name="match_rival_name" id="input_rival" value="{RIVAL_NAME}" onkeyup="look_rival(this.value);" onblur="set_rival();" autocomplete="off"><div class="suggestionsBox" id="rival" style="display:none;"><div class="suggestionList" id="auto_rival"></div></div></td>
</tr>
<tr>
	<td class="row1r"><label for="match_rival_tag">{L_RIVAL_TAG}:</label></td>
	<td class="row2"><input type="text" name="match_rival_tag" id="match_rival_tag" value="{RIVAL_TAG}"></td>
</tr>
<tr>
	<td class="row1"><label for="match_rival_url">{L_RIVAL_URL}:</label></td>
	<td class="row2"><input type="text" name="match_rival_url" id="match_rival_url" value="{RIVAL_URL}"></td>
</tr>
<tr>
	<td class="row1"><label for="match_rival_logo">{L_RIVAL_LOGO}:</label></td>
	<td class="row2"><input type="text" name="match_rival_logo" id="match_rival_logo" value="{RIVAL_LOGO}"></td>
</tr>
<tr>
	<td class="row1"><label for="match_rival_lineup" title="{L_RIVAL_LINEUP_EXP}">{L_RIVAL_LINEUP}:</label></td>
	<td class="row2"><input type="text" name="match_rival_lineup" id="match_rival_lineup" title="{L_RIVAL_LINEUP_EXP}" value="{RIVAL_LINEUP}"></td>
</tr>
<tr>
	<td class="row1r"><label for="input_server">{L_SERVER_IP}:</label></td>
	<td class="row2"><input type="text" name="match_server_ip" id="input_server" value="{SERVER_IP}" onkeyup="look_server(this.value);" onblur="set_server();" autocomplete="off" /><div class="suggestionsBox" id="server" style="display:none;"><div class="suggestionList" id="auto_server"></div></div></td>
</tr>
<tr>
	<td class="row1"><label for="match_server_pw">{L_SERVER_PW}:</label></td>
	<td class="row2"><input type="text" name="match_server_pw" id="match_server_pw" value="{SERVER_PW}"></td>
</tr>
<tr>
	<td class="row1"><label for="input_hltv">{L_HLTV_IP}:</label></td>
	<td class="row2"><input type="text" name="match_hltv_ip" id="input_hltv" value="{HLTV_IP}" onkeyup="look_hltv(this.value);" onblur="set_hltv();" autocomplete="off" /><div class="suggestionsBox" id="hltv" style="display:none;"><div class="suggestionList" id="auto_hltv"></div></div></td>
</tr>
<tr>
	<td class="row1"><label for="match_hltv_pw">{L_HLTV_PW}:</label></td>
	<td class="row2"><input type="text" name="match_hltv_pw" id="match_hltv_pw" value="{HLTV_PW}"></td>
</tr>
</table>

<br />

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MESSAGE}</a></li></ul>
<table class="update">
<tr>
	<td class="row1"><label for="match_comment" title="{L_COMMENT_EXP}">{L_COMMENT}:</label></td>
	<td class="row2"><textarea name="match_comment" id="match_comment" cols="40" title="{L_COMMENT_EXP}">{MATCH_COMMENT}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="match_report" title="{L_REPORT_EXP}">{L_REPORT}:</label></td>
	<td class="row2"><textarea name="match_report" id="match_report" cols="40" title="{L_REPORT_EXP}">{MATCH_REPORT}</textarea></td>
</tr>
</table>

<br />

<!-- BEGIN create ->
<a name="#training"></a>
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_TRAINING}</a></li></ul>

<table class="update">
<tr>
	<td class="row1"><label for="training">{L_TRAINING}:</label></td>
	<td class="row2"><label><input type="radio" name="training" id="training" value="1" onclick="document.getElementById('trainbox').style.display = ''; document.location='#training';" {S_TRAINING_YES} >&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="training" value="0" onChange="document.getElementById('trainbox').style.display = 'none';" {S_TRAINING_NO} >&nbsp;{L_NO}</label></td>
</tr>
<tbody id="trainbox" style="display:{S_TRAINING_NONE};">
<tr>
	<td class="row1"><label>{L_TRAINING_DATE}:</label></td>
	<td>{S_TDAY}.{S_TMONTH}.{S_TYEAR} - {S_THOUR}:{S_TMIN} - {S_TDURATION}</td>
</tr>
<tr>
	<td class="row1"><label for="training_maps">{L_TRAINING_MAPS}:</label></td>
	<td><div id="close">{S_MAPS}</div><div id="ajax_content"></div></td>
</tr>
<tr>
	<td class="row1"><label for="training_text">{L_TRAINING_TEXT}:</label></td>
	<td class="row2"><textarea cols="40" name="training_text" id="training_text">{TRAINING_TEXT}</textarea></td>
</tr>
</tbody>
</table>

<br/>
<!-- END create ->
-->

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN detail -->
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_INPUT}">{L_INPUT}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_DETAIL}</a></li>
</ul>

<table class="header">
<tr>
	<td class="info">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX_PLAYER}</div>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="50%" class="top">
		<form action="{S_ACTION}" method="post" name="post">
		<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_LINEUP}</a></li></ul>
		<table class="update">
		<!-- BEGIN team_users -->
		<tr>
			<td class="row1"><label for="player_status">{L_LINEUP_STATUS}:</label></td>
			<td class="row2"><label><input type="radio" name="status" value="0" id="player_status" checked="checked">&nbsp;{L_LINEUP_PLAYER}</label><span style="padding:4px;"></span><label><input type="radio" name="status" value="1">&nbsp;{L_LINEUP_REPLACE}</label></td>
		</tr>
		<tr>
			<td class="row1"><label for="table" title="{L_LINEUP_ADD_INFO}">{L_LINEUP_ADD}:</label></td>
			<td class="row2">{S_USERS}<br /><a href="#" class="small" onclick="selector(true); return false;">{L_MARK_ALL}</a><br /><a href="#" class="small" onclick="selector(false); return false;">{L_MARK_DEALL}</a></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" class="button2" value="{L_ADD}"></td>
		</tr>
		<!-- END team_users -->
		<!-- BEGIN entry_empty -->
		<tr>
			<td align="center" colspan="2">{S_USERS}</td>
		</tr>
		<!-- END entry_empty -->
		</table>
		<input type="hidden" name="smode" value="_user_add" />
		{S_FIELDS}
		</form>
	</td>
	<td width="50%" class="top">
		<form action="{S_ACTION}" method="post" id="list">
		<div id="navcontainer">
		<ul id="navlist">
			<li><a href="#" id="right" onclick="return false;">{L_LINEUP_STATUS}</a></li>
			<li><a href="#" id="current" onclick="return false;">{L_USERNAME}</a></li></ul>
		</div>
		<table class="update">
		<!-- BEGIN list_users -->
		<!-- BEGIN member_row -->
		<tr onclick="checked({_detail._list_users._memberrow.USER_ID})">
			<td class="row4" width="90%" align="left" style="padding-left:15px;">{_detail._list_users._memberrow.USERNAME}</td>
			<td class="row4" width="5%" align="right" style="padding-right:15px;">{_detail._list_users._memberrow.STATUS}</td>
			<td class="row4" width="5%" align="center"><input type="checkbox" name="members[]" value="{_detail._list_users._memberrow.USER_ID}" id="check_{_detail._list_users._memberrow.USER_ID}"></td>
		</tr>
		<!-- END member_row -->
		</table>
		<br />
		<table class="rfooter">
		<tr>
			<td>{S_OPTIONS}</td>
			<td><input type="submit" class="button2" value="{L_SUBMIT}"></td>
		</tr>
		<tr>
			<td colspan="2"><a class="small" href="#" onclick="marklist('list', 'member', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a class="small" href="#" onclick="marklist('list', 'member', false); return false;">{L_MARK_DEALL}</a></td>
		</tr>
		<!-- END list_users -->
		<!-- BEGIN no_list_users -->
		<tr>
			<td class="row_noentry1" align="center" colspan="3">{L_NO_STORE}</td>
		</tr>
		<!-- END no_list_users -->
		<!-- BEGIN entry_empty -->
		<tr>
			<td align="center" colspan="2">{S_TEAM_USERS}</td>
		</tr>
		<!-- END entry_empty -->
		</table>
{S_FIELDS}
</form>
	</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX_MAPS}</div>
<!-- BEGIN maps -->
<form action="{S_ACTION}" method="post" id="map_delete" enctype="multipart/form-data">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MAPS_OVERVIEW}</a></li>
	<li></li>
	<li></li></ul>
<table>
<!-- BEGIN map_row -->
<tr>
	<td>
		<input type="hidden" name="map_id[]" value="{_detail._maps._maprow.MAP_ID}">
		<table class="update list">
		<tr>
			<td class="row1">{L_DETAIL_MAP}:</td>
			<td>{_detail._maps._maprow.S_MAP}</td>
			<td rowspan="3" width="1%" nowrap="nowrap">{_detail._maps._maprow.PIC_URL}
				<!-- BEGIN delete -->
				<br /><input type="checkbox" name="map_pic[{_detail._maps._maprow.MAP_ID}]" id="del_{_detail._maps._maprow.MAP_ID}" value="on">&nbsp;<label for="del_{_detail._maps._maprow.MAP_ID}">{L_DELETE}</label>
				<!-- END delete -->
			</td>
			<td rowspan="3" width="1%" nowrap="nowrap">{_detail._maps._maprow.MOVE_UP}{_detail._maps._maprow.MOVE_DOWN}</td>
			<td rowspan="3" width="1%" nowrap="nowrap"><input type="checkbox" name="map_delete[{_detail._maps._maprow.MAP_ID}]" title="{L_DELETE}" value="on"></td>
		</tr>
		<tr>
			<td class="row1">{L_DETAIL_POINTS}:</td>
			<td class="row2"><input type="text" name="map_points_home[{_detail._maps._maprow.MAP_ID}]" value="{_detail._maps._maprow.MAP_HOME}" size="2">&nbsp;:&nbsp;<input type="text" name="map_points_rival[{_detail._maps._maprow.MAP_ID}]" value="{_detail._maps._maprow.MAP_RIVAL}" size="2">&nbsp;{_detail._maps._maprow.S_ROUND}</td>
		</tr>
		<tr>
			<td class="row1">{L_DETAIL_MAPPIC}:</td>
			<td class="row2 top"><input type="file" name="ufile[{_detail._maps._maprow.MAP_ID}]" id="ufile[]" size="12" /></td>
		</tr>
		</table>
		<hr />
	</td>
</tr>
<!-- END map_row -->
<tr>
	<td colspan="4" align="right" style=""><a href="#" class="small" onclick="marklist('map_delete', 'map_delete', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" class="small" onclick="marklist('map_delete', 'map_delete', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
<tr>
	<td colspan="4" align="center"><input type="submit" class="button2" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_UPDATE}
{S_FIELDS}
</form>
<!-- END maps -->

<br /><div align="center">{ERROR_BOX_UPLOAD}</div>
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MAPS_PIC}</a></li></ul>
<table class="update">
<tr>
	<td class="row1">{L_DETAIL_MAP}&nbsp;/&nbsp;{L_DETAIL_POINTS}&nbsp;:&nbsp;{L_DETAIL_POINTS}&nbsp;/&nbsp;{L_DETAIL_MAPPIC}:</td>
	<td class="row2"><div><div>{S_MAP}&nbsp;&nbsp;<input type="text" name="map_points_home[]" id="map_points_home[]" size="2"><span style=" padding:4px;">:</span><input type="text" name="map_points_rival[]" id="map_points_rival[]" size="2">&nbsp;<input type="button" class="more" value="{L_MORE}" onclick="clone(this)"><br /><input type="file" name="ufile[]" id="ufile[]"></div></div></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_UPLOAD}"></td>
</tr>
</table>
{S_CREATE}
{S_FIELDS}
</form>
<!-- END detail -->

<!-- BEGIN sync -->

<table class="rows">
<!-- BEGIN row -->
<tr>
	<th colspan="4">{_sync.row.GAME} {_sync.row.TEAM} vs. {_sync.row.RIVAL}</th>
	<th nowrap="nowrap">{_sync.row.CHECK} {_sync.row.WRITE}</th>
	<th><input type="checkbox" name="map_delete[{_sync.row.MAP_ID}]" title="{L_DELETE}" value="on"></th>
</tr>
<!-- BEGIN maps -->
<tr>
	<td>{_sync.row._maps.NAME}</td>
	<td align="center">{_sync.row._maps.HOME}</td>
	<td align="center">{_sync.row._maps.RIVAL}</td>
	<td align="center">{_sync.row._maps.PICTURE}</td>
	<td align="center">{_sync.row._maps.PREVIEW}</td>
	<td><input type="checkbox" name="map_delete[{_sync.row._maps.MAP_ID}]" title="{L_DELETE}" value="on"></td>
</tr>
<!-- BEGIN result -->
<tr>
	<td>{_sync.row._maps._result.COUNT}</td>
	<td>{_sync.row._maps._result.HOME}</td>
	<td>{_sync.row._maps._result.RIVAL}</td>
	<td colspan="2"></td>
	<td></td>
</tr>
<!-- END result -->
<!-- BEGIN result_row -->
<tr>
	<td colspan="5">{_sync.row._maps._resultrow.NAME}</td>
	<td><input type="checkbox" name="map_delete[{_sync.row._maps._resultrow.MAP_ID}]" title="{L_DELETE}" value="on"></td>
</tr>
<!-- END result_row -->
<!-- END maps -->
<!-- END row -->
</table>

<!-- END sync -->