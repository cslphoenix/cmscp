<!-- BEGIN input -->
<script type="text/JavaScript">
// <![CDATA[

var request = false;

function setRequest(value, meta, name)
{
	if ( window.XMLHttpRequest ) { request = new XMLHttpRequest(); } else { request = new ActiveXObject("Microsoft.XMLHTTP"); }
	
	if ( !request )
	{
		alert("Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	else
	{
		var url = "ajax/ajax_listmaps.php";
		
		request.open('post', url, true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.send('type='+value+'&meta='+meta+'&name='+name);
		request.onreadystatechange = interpretRequest;
	}
}

function interpretRequest()
{
	switch (request.readyState)
	{
		case 4:
		
			if ( request.status != 200 )
			{
				alert("Der Request wurde abgeschlossen, ist aber nicht OK\nFehler:"+request.status);
			}
			else
			{
				var content = request.responseText;
				document.getElementById('ajax_content').innerHTML = content;
			}
			break;
		
		default: document.getElementById('close').style.display = "none"; break;
	}
}

<!-- BEGIN ajax -->
function look_{input.ajax.NAME}({input.ajax.NAME})
{
	if ( {input.ajax.NAME}.length == 0 )
	{
		$('#{input.ajax.NAME}').hide();
	}
	else
	{
		$.post("./ajax/{input.ajax.FILE}", {{input.ajax.NAME}: ""+{input.ajax.NAME}+""}, function(data) {
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
	$('#match_{input.ajax.NAME}').val(thisValue);
	setTimeout("$('#{input.ajax.NAME}').hide();", 200);
}
<!-- END ajax -->

function set_infos(id,text)
{
	var obj = document.getElementById(id).value = text;
}

function display_options(value)
{
	if ( value == '0' )
	{
		dE('training_date', -1);
		dE('training_duration', -1);
		dE('training_maps', -1);
		dE('training_text', -1);
	}
	else
	{
		dE('training_date', 1);
		dE('training_duration', 1);
		dE('training_maps', 1);
		dE('training_text', 1);
	}
}

// ]]>
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

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<div class="update">
<!-- BEGIN tab -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul>
<!-- BEGIN option -->
<div{input.row.tab.option.ID}>
<dl>			
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
</div>
<!-- END option -->
<!-- END tab -->
</div>
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
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
		<input type="hidden" name="smode" value="user_add" />
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
		<table class="update index">
		<!-- BEGIN list_users -->
		<!-- BEGIN member_row -->
		<tr>
			<td class="row4" width="90%" align="left" style="padding-left:15px;"><label for="{detail.list_users.member_row.USER_ID}">{detail.list_users.member_row.USERNAME}</label></td>
			<td class="row4" width="5%" align="right" style="padding-right:15px;"><label for="{detail.list_users.member_row.USER_ID}">{detail.list_users.member_row.STATUS}</label></td>
			<td class="row4" width="5%" align="center"><input type="checkbox" name="members[]" value="{detail.list_users.member_row.USER_ID}" id="check_{detail.list_users.member_row.USER_ID}"></td>
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
		<input type="hidden" name="map_id[]" value="{detail.maps.map_row.MAP_ID}">
		<table class="update list">
		<tr>
			<td class="row1">{L_DETAIL_MAP}:</td>
			<td>{detail.maps.map_row.S_MAP}</td>
			<td rowspan="3" width="1%" nowrap="nowrap">{detail.maps.map_row.PIC_URL}
				<!-- BEGIN delete -->
				<br /><input type="checkbox" name="map_pic[{detail.maps.map_row.MAP_ID}]" id="del_{detail.maps.map_row.MAP_ID}" value="on">&nbsp;<label for="del_{detail.maps.map_row.MAP_ID}">{L_DELETE}</label>
				<!-- END delete -->
			</td>
			<td rowspan="3" width="1%" nowrap="nowrap">{detail.maps.map_row.MOVE_UP}{detail.maps.map_row.MOVE_DOWN}</td>
			<td rowspan="3" width="1%" nowrap="nowrap"><input type="checkbox" name="map_delete[{detail.maps.map_row.MAP_ID}]" title="{L_DELETE}" value="on"></td>
		</tr>
		<tr>
			<td class="row1">{L_DETAIL_POINTS}:</td>
			<td class="row2"><input type="text" name="map_points_home[{detail.maps.map_row.MAP_ID}]" value="{detail.maps.map_row.MAP_HOME}" size="2">&nbsp;:&nbsp;<input type="text" name="map_points_rival[{detail.maps.map_row.MAP_ID}]" value="{detail.maps.map_row.MAP_RIVAL}" size="2">&nbsp;{detail.maps.map_row.S_ROUND}</td>
		</tr>
		<tr>
			<td class="row1">{L_DETAIL_MAPPIC}:</td>
			<td class="row2 top"><input type="file" name="ufile[{detail.maps.map_row.MAP_ID}]" id="ufile[]" size="12" /></td>
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
	<td>{_sync.row.maps.NAME}</td>
	<td align="center">{_sync.row.maps.HOME}</td>
	<td align="center">{_sync.row.maps.RIVAL}</td>
	<td align="center">{_sync.row.maps.PICTURE}</td>
	<td align="center">{_sync.row.maps.PREVIEW}</td>
	<td><input type="checkbox" name="map_delete[{_sync.row.maps.MAP_ID}]" title="{L_DELETE}" value="on"></td>
</tr>
<!-- BEGIN result -->
<tr>
	<td>{_sync.row.maps.result.COUNT}</td>
	<td>{_sync.row.maps.result.HOME}</td>
	<td>{_sync.row.maps.result.RIVAL}</td>
	<td colspan="2"></td>
	<td></td>
</tr>
<!-- END result -->
<!-- BEGIN result_row -->
<tr>
	<td colspan="5">{_sync.row.maps.resultrow.NAME}</td>
	<td><input type="checkbox" name="map_delete[{_sync.row.maps.resultrow.MAP_ID}]" title="{L_DELETE}" value="on"></td>
</tr>
<!-- END result_row -->
<!-- END maps -->
<!-- END row -->
</table>

<!-- END sync -->

<!-- BEGIN display -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li><li><a href="{S_CREATE}">{L_CREATE}</a></li></ul>
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

<table class="rfooter">
<tr>
	<td></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
<!-- END display -->