<!-- BEGIN input -->
<script type="text/JavaScript">
<!-- BEGIN ajax -->
function look_{input.ajax.NAME}({input.ajax.NAME}, user_new, user_level)
{
	if ( {input.ajax.NAME}.length == 0 )
	{
		$('#{input.ajax.NAME}').hide();
	}
	else
	{
		$.post("./ajax/{input.ajax.FILE}", {{input.ajax.NAME}: ""+{input.ajax.NAME}+"", user_new: ""+user_new+"", user_level: ""+user_level+""}, function(data) {
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
	$('#group_{input.ajax.NAME}').val(thisValue);
	setTimeout("$('#{input.ajax.NAME}').hide();", 200);
}
<!-- END ajax -->
function set_infos(id,text)
{
	var obj = document.getElementById(id).value = text;
}
</script>
<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">

<script type="text/JavaScript">
// <![CDATA[
function check_yes()
{
	<!-- BEGIN auth -->
	document.getElementById("{input.auth.FIELDS}").checked = true;
	<!-- END auth -->
}

function check_no()
{
	var radios = document.forms["post"].elements["deactivated"];
	
	for ( var i = 0; i < radios.length; i++ )
	{
		radios[i].checked = true;
	}
}

// ]]>
</script>
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
	<!-- BEGIN update -->
	<li><a href="{S_MEMBER}">{L_VIEWMEMBER}</a></li>
	<!-- END update -->
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}" {input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
</table>
<!-- END row -->

<!--
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_DATA}</a></li>
	<li><a href="#" id="right" onclick="return false;">{L_AUTH}</a></li>
</ul>
<table>
<tr>
	<td valign="top" width="100%">
		<table class="update">
		<tr>
			<td class="row1r"><label for="group_name">{L_NAME}:</label></td>
			<td class="row2"><input type="text" name="group_name" id="group_name" value="{NAME}"></td>
		</tr>
		<!-- BEGIN create ->
		<tr>
			<td class="row1r"><label for="user_name">{L_MOD}:</label></td>
			<td class="row2"><input type="text" name="user_id" id="user_name" value="{MOD}" onkeyup="lookup(this.value);" onblur="fill();" autocomplete="off">
				<div class="suggestionsBox" id="suggestions" style="display:none;">
					<div class="suggestionList" id="autoSuggestionsList"></div>
				</div>
			</td>
		</tr>
		<!-- END create ->
		<tr>
			<td class="row1"><label for="group_access">{L_ACCESS}:</label></td>
			<td class="row2">{S_ACCESS}</td>
		</tr>
		<tr>
			<td class="row1"><label for="group_type">{L_TYPE}:</label></td>
			<td class="row2">{S_TYPE}</td>
		</tr>
		<tr>
			<td class="row1"><label for="group_desc">{L_DESC}:</label></td>
			<td class="row2"><textarea class="textarea" name="group_desc" id="group_desc" cols="40">{GROUP_DESC}</textarea></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_color">{L_COLOR}:</label></td>
			<td class="row2"><input size="7" class="color post" type="text" name="group_color" id="group_color" value="{COLOR}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_legend">{L_LEGEND}:</label></td>
			<td class="row2"><label><input type="radio" name="group_legend" id="group_legend" value="1" {S_LEGEND_YES} />&nbsp;{L_SHOW}</label><span style="padding:4px;"></span><label><input type="radio" name="group_legend" value="0" {S_LEGEND_NO} />&nbsp;{L_NOSHOW}</label></td>
		</tr>
		<tr>
			<td class="row1"><label for="rank_id">{L_RANK}:</label></td>
			<td class="row2">{S_RANK}</td>
		</tr>
		<!-- BEGIN image ->
		<tr>
			<td class="row1">{L_IMAGE_CURRENT}:</td>
			<td class="row2"><img src="{IMAGE}" alt="" /><br /><input type="checkbox" name="group_image_delete">&nbsp;{L_IMAGE_DELETE}</td>
		</tr>
		<!-- END image ->
		<tr>
			<td class="row1"><label for="ufile">{L_IMAGE_UPLOAD}:</label></td>
			<td class="row2"><input type="file" class="post" name="group_img" /></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_order">{L_ORDER}:</label></td>
			<td class="row2">{S_ORDER}</td>
		</tr>
		</table>
	</td>
	<td valign="top" nowrap="nowrap">
		<table class="update3">
		<!-- BEGIN auth ->
		<tr>
			<td class="row1"><label for="{_input._auth.FIELDS}">{_input._auth.TITLE}:</label></td>
			<td class="row2">{_input._auth.SELECT}</td>
		</tr>
		<!-- END auth ->
		<tr>
			<td class="row2 right"><a href="#" onclick="activated(); return false;">{L_MARK_YES}</a></td>
			<td class="row2"><a href="#" onclick="deactivated(); return false;">{L_MARK_NO}</a></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2"></td>
</tr>
</table>

<br/>
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

<!-- BEGIN member -->
<form action="{S_ACTION}" method="post" name="post" id="list">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_UPDATE}">{L_INPUT}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_VIEWMEMBER}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<table class="normal">
<tr>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MODERATOR}</a></li></ul></th>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="right" onclick="return false;">{L_REGISTER}</a></li></ul></th>
</tr>
<!-- BEGIN mod_row -->
<tr>
	<td>{_member._modrow.USERNAME}</td>
	<td>{_member._modrow.REGISTER}</td>
	<td><input type="checkbox" name="members[]" value="{_member._modrow.USER_ID}"></td>
</tr>
<!-- END mod_row -->
<!-- BEGIN mod_no -->
<tr>
	<td class="empty" colspan="3">{L_MODERATOR_NO}</td>
</tr>
<!-- END mod_no -->
</table>

<br />

<table class="normal">
<tr>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MEMBER}</a></li></ul></th>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="right" onclick="return false;">{L_REGISTER}</a></li></ul></th>
</tr>
<!-- BEGIN mem_row -->
<tr>
	<td>{_member._memrow.USERNAME}</td>
	<td>{_member._memrow.REGISTER}</td>
	<td><input type="checkbox" name="members[]" value="{_member._memrow.USER_ID}"></td>
</tr>
<!-- END mem_row -->
<!-- BEGIN mem_no -->
<tr>
	<td class="empty" colspan="3">{L_MEMBER_NO}</td>
</tr>
<!-- END mem_no -->
</table>

<table class="rfooter">
<tr>
	<td colspan="2" align="right">{S_OPTIONS}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td colspan="2" align="right" class="row5"><a href="#" onclick="marklist('list', 'member', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
<!-- BEGIN pending -->
<br />

<table class="normal">
<tr>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_PENDING}</a></li></ul></th>
	<th colspan="2" align="right"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_REGISTER}</a></li></ul></th>
</tr>
<!-- BEGIN pending_row -->
<tr>
	<td>{_member._pending._pendingrow.USERNAME}</td>
	<td>{_member._pending._pendingrow.REGISTER}</td>
	<td><input type="checkbox" name="pending_members[]" value="{_member._pending._pendingrow.USER_ID}" checked="checked"></td>
</tr>
<!-- END pending_row -->
</table>

<table class="rfooter">
<tr>
	<td colspan="2" align="right">{S_PENDING}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
</table>
<!-- END pending -->
{S_FIELDS}
</form>

<form action="{S_ACTION}" method="post" name="post">

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_ADD_MEMBER}</a></li></ul>
<ul id="navinfo"><li>{L_ADD_MEMBER_EX}</li></ul>

<table class="update">
<tr>
	<td width="50%"><textarea class="textarea" name="textarea" style="width:100%" rows="5"></textarea></td>
	<td>{S_USERS}</td>
</tr>
<tr>
	<td colspan="2"><input type="checkbox" name="mod" /> Moderatorstatus</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}"></td>
</tr>
</table>
<input type="hidden" name="smode" value="_add" />
{S_FIELDS}
</form>
<!-- END member -->

<!-- BEGIN overview -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a href="#" id="right" onclick="return false;">{L_OVERVIEW}</a></li>
</ul>
<ul id="navinfo"><li>{L_OVERVIEW_EXPLAIN}</li></ul>

<br />

<table class="update3 list">
<tr>
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_OPTION}</a></li></ul></th>
	<!-- BEGIN grp_name -->
	<th><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{_overview._grp_name.NAME}</a></li></ul></th>
	<!-- END grp_name -->
</tr>
<!-- BEGIN grp_auth -->
<tr>
	<td class="row1"><label>{_overview._grp_auth.NAME}:</label></td>
	<!-- BEGIN auth -->
	<td class="row2">{_overview._grp_auth._auth.INFO}</td>
	<!-- END auth -->
</tr>
<!-- END grp_auth -->
<tr>
	<td colspan="{COLSPAN}" class="small"><span class="right">{PAGE_PAGING}</span>{PAGE_NUMBER}</td>
</tr>
<tr>
	<td colspan="{COLSPAN}" align="center"><input type="submit" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END overview -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li><li><a href="{S_CREATE}">{L_CREATE}</a></li><li><a id="setting" href="{S_OVERVIEW}">{L_OVERVIEW}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<table class="rows">
<tr>
	<th><span class="right">{L_COUNT}</span>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{display.row.COUNT}</span>{display.row.NAME}</td>
	<td>{display.row.MEMBER}{display.row.MOVE_DOWN}{display.row.MOVE_UP}{display.row.UPDATE}{display.row.DELETE}</td>
</tr>
<!-- END row -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="group_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->