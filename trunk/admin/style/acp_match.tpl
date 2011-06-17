<!-- BEGIN _display -->
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
<tr>
	<td align="right"><form action="{S_ACTION}" method="post">{L_SORT}: {S_SORT}</form></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_UPCOMING}</th>
	<th>{L_TRAINING}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _match_new_row -->
<tr>
	<td><span style="float: right;">{_display._match_new_row.DATE}</span>{_display._match_new_row.GAME} {_display._match_new_row.NAME}</td>
	<td><a href="{_display._match_new_row.U_TRAIN}">{_display._match_new_row.L_TRAIN}</a></td>
	<td><a href="{_display._match_new_row.U_DETAIL}">{I_DETAILS}</a> <a href="{_display._match_new_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._match_new_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _match_new_row -->
<!-- BEGIN _entry_empty_new -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_new -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_EXPIRED}</th>
	<th>{L_TRAINING}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _match_old_row -->
<tr>
	<td><span style="float: right;">{_display._match_old_row.DATE}</span>{_display._match_old_row.GAME} {_display._match_old_row.NAME}</td>
	<td> - </td>
	<td><a href="{_display._match_old_row.U_DETAIL}">{I_DETAILS}</a> <a href="{_display._match_old_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._match_old_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _match_old_row -->
<!-- BEGIN _entry_empty_old -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_old -->
</table>

<form action="{S_ACTION}" method="post">
<table class="footer">
<tr>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
	<td>{S_TEAM}</td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
{AJAX}
<script type="text/JavaScript">

function look_server(input_server)
{
	if ( input_server.length == 0 )
	{
		// Hide the suggestion box.
		$('#server').hide();
	}
	else
	{
		$.post("./ajax/ajax_gs.php", {server: ""+input_server+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#server').show();
					$('#auto_server').html(data);
				}
			}
		);
	}
}

function look_hltv(input_hltv)
{
	if ( input_hltv.length == 0 )
	{
		// Hide the suggestion box.
		$('#hltv').hide();
	}
	else
	{
		$.post("./ajax/ajax_gs.php", {hltv: ""+input_hltv+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#hltv').show();
					$('#auto_hltv').html(data);
				}
			}
		);
	}
}

function look_rival(input_rival)
{
	if ( input_rival.length == 0 )
	{
		// Hide the suggestion box.
		$('#rival').hide();
	}
	else
	{
		$.post("./ajax/ajax_rival.php", {rival: ""+input_rival+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#rival').show();
					$('#auto_rival').html(data);
				}
			}
		);
	}
}

function set_server(thisValue)
{
	$('#input_server').val(thisValue);
	setTimeout("$('#server').hide();", 200);
}

function set_hltv(thisValue)
{
	$('#input_hltv').val(thisValue);
	setTimeout("$('#hltv').hide();", 200);
}

function set_rival(thisValue)
{
	$('#input_rival').val(thisValue);
	setTimeout("$('#rival').hide();", 200);
}

function set_infos(id,text)
{
	var obj = document.getElementById(id).value = text;
}

function set_site(name, text)
{
	var obj = document.getElementById(name).value = text;
}
</script>
<form action="{S_ACTION}" method="post" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_TITLE}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	<!-- BEGIN _update -->
	<li><a href="{S_DETAIL}">{L_DETAIL}</a></li>
	<!-- END _update -->
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current" onclick="clip('settings')"><img src="style/images/collapse.gif" id="img_settings" width="9" height="9" border="0" /> {L_STANDARD}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody id="settings" style="display:">
<tr>
	<td class="row1"><label for="team_id">{L_TEAM}: *</label></td>
	<td class="row2">{S_TEAM}</td>
</tr>
<tr>
	<td class="row1"><label for="match_type">{L_TYPE}: *</label></td>
	<td class="row2">
		<!-- BEGIN _type -->
		<label><input type="radio" name="match_type" value="{_input._type.TYPE}" {_input._type.MARK} />&nbsp;{_input._type.NAME}</label><br />
		<!-- END _type -->
	</td>
</tr>
<tr>
	<td class="row1"><label for="match_war">{L_WAR}: *</label></td>
	<td class="row2">
		<!-- BEGIN _war -->
		<label><input type="radio" name="match_war" value="{_input._war.TYPE}" {_input._war.MARK} />&nbsp;{_input._war.NAME}</label><br />
		<!-- END _war -->
	</td>
</tr>
<tr>
	<td class="row1"><label for="match_league">{L_LEAGUE}: *</label></td>
	<td class="row2">
		<!-- BEGIN _league -->
		<label><input type="radio" name="match_league" value="{_input._league.TYPE}" {_input._league.MARK} onclick="{_input._league.CLICK}" />&nbsp;{_input._league.NAME}</label><br />
		<!-- END _league -->
	</td>
</tr>
<tr>
	<td class="row1"><label for="match_league_url">{L_LEAGUE_URL}:</label></td>
	<td class="row2"><input type="text" class="post" name="match_league_url" id="match_league_url" value="{LEAGUE_URL}"></td>
</tr>
<tr>
	<td class="row1"><label for="match_league_match">{L_LEAGUE_MATCH}:</label></td>
	<td class="row2"><input type="text" class="post" name="match_league_match" id="match_league_match" value="{LEAGUE_MATCH}"></td>
</tr>
<tr>
	<td class="row1"><label>{L_DATE}:</label></td>
	<td class="row2">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1"><label for="match_public">{L_PUBLIC}:</label></td>
	<td class="row2"><label><input type="radio" name="match_public" id="match_public" value="1" {S_PUBLIC_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="match_public" value="0" {S_PUBLIC_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="match_comments">{L_COMMENTS}:</label></td>
	<td class="row2"><label><input type="radio" name="match_comments" id="match_comments" value="1" {S_COMMENT_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="match_comments" value="0" {S_COMMENT_NO} />&nbsp;{L_NO}</label></td>
</tr>
<!-- BEGIN _reset -->
<tr>
	<td class="row1"><label for="listdel">{L_RESET_LIST}:</label></td>
	<td class="row2"><label><input type="radio" name="listdel" id="listdel" value="1" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="listdel" value="0" checked="checked" />&nbsp;{L_NO}</label></td>
</tr>
<!-- END _reset -->
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current" onclick="clip('show_server')"><img src="style/images/collapse.gif" id="img_show_server" width="9" height="9" border="0" /> {L_RIVAL} / {L_SERVER}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody id="show_server" style="display:">
<tr>
	<td class="row1"><label for="input_rival">{L_RIVAL_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="match_rival_name" id="input_rival" value="{RIVAL_NAME}" onkeyup="look_rival(this.value);" onblur="set_rival();" autocomplete="off"><div class="suggestionsBox" id="rival" style="display:none;"><div class="suggestionList" id="auto_rival"></div></div></td>
</tr>
<tr>
	<td class="row1"><label for="match_rival_tag">{L_RIVAL_TAG}: *</label></td>
	<td class="row2"><input type="text" class="post" name="match_rival_tag" id="match_rival_tag" value="{RIVAL_TAG}"></td>
</tr>
<tr>
	<td class="row1"><label for="match_rival_url">{L_RIVAL_URL}:</label></td>
	<td class="row2"><input type="text" class="post" name="match_rival_url" id="match_rival_url" value="{RIVAL_URL}"></td>
</tr>
<tr>
	<td class="row1"><label for="match_rival_logo">{L_RIVAL_LOGO}:</label></td>
	<td class="row2"><input type="text" class="post" name="match_rival_logo" id="match_rival_logo" value="{RIVAL_LOGO}"></td>
</tr>
<tr>
	<td class="row1"><label for="match_rival_lineup" title="{L_RIVAL_LINEUP_EXP}">{L_RIVAL_LINEUP}:</label></td>
	<td class="row2"><input type="text" class="post" name="match_rival_lineup" id="match_rival_lineup" title="{L_RIVAL_LINEUP_EXP}" value="{RIVAL_LINEUP}"></td>
</tr>
<tr>
	<td class="row1"><label for="input_server">{L_SERVER_IP}: *</label></td>
	<td class="row2"><input type="text" class="post" name="match_server_ip" id="input_server" value="{SERVER_IP}" onkeyup="look_server(this.value);" onblur="set_server();" size="23" autocomplete="off" /><div class="suggestionsBox" id="server" style="display:none;"><div class="suggestionList" id="auto_server"></div></div></td>
</tr>
<tr>
	<td class="row1"><label for="match_server_pw">{L_SERVER_PW}:</label></td>
	<td class="row2"><input type="text" class="post" name="match_server_pw" id="match_server_pw" value="{SERVER_PW}"></td>
</tr>
<tr>
	<td class="row1"><label for="input_hltv">{L_HLTV_IP}:</label></td>
	<td class="row2"><input type="text" class="post" name="match_hltv_ip" id="input_hltv" value="{HLTV_IP}" onkeyup="look_hltv(this.value);" onblur="set_hltv();" size="23" autocomplete="off" /><div class="suggestionsBox" id="hltv" style="display:none;"><div class="suggestionList" id="auto_hltv"></div></div></td>
</tr>
<tr>
	<td class="row1"><label for="match_hltv_pw">{L_HLTV_PW}:</label></td>
	<td class="row2"><input type="text" class="post" name="match_hltv_pw" id="match_hltv_pw" value="{HLTV_PW}"></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current" onclick="clip('msg')"><img src="style/images/collapse.gif" id="img_msg" width="9" height="9" border="0" /> {L_MESSAGE}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody id="msg" style="display:">
<tr>
	<td class="row1"><label for="match_comment" title="{L_COMMENT_EXP}">{L_COMMENT}:</label></td>
	<td class="row2"><textarea class="post" rows="5" cols="50" name="match_comment" id="match_comment" title="{L_COMMENT_EXP}">{MATCH_COMMENT}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="match_report" title="{L_REPORT_EXP}">{L_REPORT}:</label></td>
	<td class="row2"><textarea class="post" rows="5" cols="50" name="match_report" id="match_report" title="{L_REPORT_EXP}">{MATCH_REPORT}</textarea></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- BEGIN _create -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_TRAINING}</a></li>
		</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="training">{L_TRAINING}:</label></td>
	<td class="row2"><label><input type="radio" name="training" id="training" value="1" onChange="document.getElementById('trainbox').style.display = '';" {S_TRAINING_YES} >&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="training" value="0" onChange="document.getElementById('trainbox').style.display = 'none';" {S_TRAINING_NO} >&nbsp;{L_NO}</label></td>
</tr>
<tbody  id="trainbox" style="display:{S_TRAINING_NONE};">
<tr>
	<td class="row1"><label>{L_TRAINING_DATE}:</label></td>
	<td class="row2">{S_TDAY} . {S_TMONTH} . {S_TYEAR} - {S_THOUR} : {S_TMIN} - {S_TDURATION}</td>
</tr>
<tr>
	<td class="row1"><label for="training_maps">{L_TRAINING_MAPS}:</label></td>
	<td class="row2"><div id="close">{S_MAPS}</div><div id="content"></div></td>
</tr>
<tr>
	<td class="row1"><label for="training_text">{L_TRAINING_TEXT}:</label></td>
	<td class="row2"><textarea class="post" rows="5" cols="50" name="training_text" id="training_text">{TRAINING_TEXT}</textarea></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- END _create -->
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->

<!-- BEGIN _detail -->
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
	<li><a href="{S_INPUT}">{L_INPUT}</a></li>
	<li id="active"><a href="#" id="current">{L_DETAIL}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX_PLAYER}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="50%" class="row5 top">
		<form action="{S_ACTION}" method="post" name="post">
		<table class="update">
<tr>
	<td colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{L_LINEUP}</a></li>
					</ul>
		</div>
	</td>
</tr>
		<!-- BEGIN _team_users -->
		<tr>
			<td class="row1" width="46%"><label for="player_status">{L_LINEUP_STATUS}:</label></td>
			<td class="row2"><label><input type="radio" name="status" value="0" id="player_status" checked="checked">&nbsp;{L_LINEUP_PLAYER}</label><span style="padding:4px;"></span><label><input type="radio" name="status" value="1">&nbsp;{L_LINEUP_REPLACE}</label></td>
		</tr>
		<tr>
			<td class="row1"><label for="members" title="{L_LINEUP_ADD_INFO}">{L_LINEUP_ADD}:</label></td>
			<td class="row2">{S_USERS}</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" class="button2" value="{L_ADD}"></td>
		</tr>
		<!-- END _team_users -->
		<!-- BEGIN _entry_empty -->
		<tr>
			<td align="center" colspan="2">{S_USERS}</td>
		</tr>
		<!-- END _entry_empty -->
		</table>
		<input type="hidden" name="smode" value="_user_add" />
		{S_FIELDS}
		</form>
	</td>
	<td width="50%" class="row5 top">
		<form action="{S_ACTION}" method="post" id="list">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th colspan="3">
				<div id="navcontainer">
					<ul id="navlist">
						<li><a href="#" id="right">#</a></li>
						<li><a href="#" id="right">{L_LINEUP_STATUS}</a></li>
						<li><a href="#" id="current">{L_USERNAME}</a></li>
					</ul>
		</div>
	</td>
</tr>
		<!-- BEGIN _list_users -->
		<!-- BEGIN _member_row -->
		<tr class="hover" onclick="checked({_detail._list_users._member_row.USER_ID})">
			<td align="left" style="padding: 0px 0px 0px 5px;">{_detail._list_users._member_row.USERNAME}</td>
			<td align="center">{_detail._list_users._member_row.STATUS}</td>
			<td align="center"><input type="checkbox" name="members[]" value="{_detail._list_users._member_row.USER_ID}" id="check_{_detail._list_users._member_row.USER_ID}"></td>
		</tr>
		<!-- END _member_row -->
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="right">{S_OPTIONS}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}"></td>
		</tr>
		<tr>
			<td colspan="3" align="right"><a class="small" href="#" onclick="marklist('list', 'member', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a class="small" href="#" onclick="marklist('list', 'member', false); return false;">{L_MARK_DEALL}</a></td>
		</tr>
		<!-- END _list_users -->
		<!-- BEGIN _no_list_users -->
		<tr>
			<td class="row_noentry1" align="center" colspan="3">{L_NO_STORE}</td>
		</tr>
		<!-- END _no_list_users -->
		<!-- BEGIN _entry_empty -->
		<tr>
			<td align="center" colspan="2">{S_TEAM_USERS}</td>
		</tr>
		<!-- END _entry_empty -->
		</table>
		{S_FIELDS}
		</form>
	</td>
</tr>

</table>

<br /><div align="center">{ERROR_BOX_MAPS}</div>
<!-- BEGIN _maps -->
<form action="{S_ACTION}" method="post" id="map_delete" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MAPS_OVERVIEW}</a></li>
	<li></li>
	<li></li>
</ul>
</div>
<table class="update" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN _map_row -->
<tr class="hover">
<td>
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1" width="28%">{L_DETAIL_MAP}:</td>
		<td class="row2" width="28%">{_detail._maps._map_row.S_MAP}</td>
		<td class="row3" rowspan="3" width="43%" align="center">{_detail._maps._map_row.PIC_URL}
			<!-- BEGIN _delete -->
			<br /><input type="checkbox" name="map_pic[{_detail._maps._map_row.MAP_ID}]" value="on">&nbsp;{L_IMAGE_DELETE}
			<!-- END _delete -->
		</td>
		<td class="row3" rowspan="3" width="43%" align="center" nowrap="nowrap">{_detail._maps._map_row.MOVE_UP}{_detail._maps._map_row.MOVE_DOWN}</td>
		<td class="row3" rowspan="3" width="1%"><input type="checkbox" name="map_delete[{_detail._maps._map_row.MAP_ID}]" title="{L_DELETE}" value="on"></td>
	</tr>
	<tr>
		<td class="row1">{L_DETAIL_POINTS}:</td>
		<td class="row2"><input type="text" class="post" name="map_points_home[{_detail._maps._map_row.MAP_ID}]" value="{_detail._maps._map_row.MAP_HOME}" size="2"><span style=" vertical-align:top; padding:2px;">:</span><input type="text" class="post" name="map_points_rival[{_detail._maps._map_row.MAP_ID}]" value="{_detail._maps._map_row.MAP_RIVAL}" size="2"> {_detail._maps._map_row.S_ROUND}</td>
	</tr>
	<tr>
		<td class="row1">{L_DETAIL_MAPPIC}:</td>
		<td class="row2" width="1"><input type="file" class="post" name="ufile[{_detail._maps._map_row.MAP_ID}]" id="ufile[]" size="12" /></td>
	</tr>
	</table>
	<input type="hidden" name="map_id[]" value="{_detail._maps._map_row.MAP_ID}">
</td>
</tr>
<!-- END _map_row -->
<tr>
	<td colspan="4">&nbsp;</td>
	</tr>
<tr>
	<td colspan="4" align="right"><input type="submit" class="button2" value="{L_SUBMIT}"></td>
</tr>
<tr>
	<td colspan="4" align="right" class="row5"><a href="#" class="small" onclick="marklist('map_delete', 'map_delete', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" class="small" onclick="marklist('map_delete', 'map_delete', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
{S_UPDATE}
{S_FIELDS}
</form>
<!-- END _maps -->

<br /><div align="center">{ERROR_BOX_UPLOAD}</div>
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MAPS_PIC}</a></li>
</ul>
</div>
<table class="edit" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="row1">{L_DETAIL_MAP}&nbsp;/&nbsp;{L_DETAIL_POINTS}&nbsp;:&nbsp;{L_DETAIL_POINTS}&nbsp;/&nbsp;{L_DETAIL_MAPPIC}:</td>
	<td class="row2"><div><div>{S_MAP}&nbsp;&nbsp;<input type="text" class="post" name="map_points_home[]" id="map_points_home[]" size="2"><span style=" vertical-align:middle; padding:2px;">:</span><input type="text" class="post" name="map_points_rival[]" id="map_points_rival[]" size="2">&nbsp;&nbsp;<input type="file" class="post" name="ufile[]" id="ufile[]" size="12">&nbsp;<input type="button" class="button2" value="{L_MORE}" onclick="clone(this)"></div></div></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_UPLOAD}"></td>
</tr>
</table>
{S_CREATE}
{S_FIELDS}
</form>
<!-- END _detail -->